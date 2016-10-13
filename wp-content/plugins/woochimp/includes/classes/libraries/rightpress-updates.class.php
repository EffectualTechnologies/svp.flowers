<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * RightPress Automatic Updates Class
 *
 * Sets the following WordPress options:
 * - rightpress_up_pc_{plugin_key} - Purchase Code
 * - rightpress_up_nag_{plugin_key} - Display Purchase Code nag, value is set to new version
 * - rightpress_up_nag_{version_key}_{plugin_key} - Version-specific Purchase Code nag dismissed
 *
 * @class RightPress_Updates
 * @author RightPress
 */
if (!class_exists('RightPress_Updates')) {

final class RightPress_Updates
{
    private $endpoint_url;
    private $plugin_path;
    private $plugin_basename;
    private $plugin_slug;
    private $plugin_key;
    private $purchase_code;
    private $nag_error_message;
    private $nag_value;

    /**
     * Constructor class
     *
     * @access public
     * @param string $plugin_path
     * @param string $plugin_version
     * @return void
     */
    public function __construct($plugin_path, $plugin_version)
    {
        $this->endpoint_url     = 'http://updates.rightpress.net/';
        $this->plugin_path      = $plugin_path;
        $this->plugin_version   = $plugin_version;
        $this->plugin_basename  = plugin_basename($this->plugin_path);
        $this->plugin_slug      = $this->get_plugin_slug();
        $this->plugin_key       = $this->get_plugin_key();
        $this->purchase_code    = $this->get_purchase_code();

        // Force WordPress to check for updates on plugin activation
        register_activation_hook($plugin_path, array($this, 'on_activation'));

        // Register plugin with WordPress updater
        add_filter('pre_set_site_transient_update_plugins', array($this, 'register_plugin'));

        // Override WordPress.org Plugin Install API
        add_filter('plugins_api', array($this, 'plugins_api_actions'), 10, 3);

        // Maybe display Purchase Code nag
        add_action('admin_notices', array($this, 'display_purchase_code_nag'));

        // Some code needs to be executed later
        add_action('init', array($this, 'on_wp_init'), 1);
    }

    /**
     * Initialize update class for plugin
     *
     * @access public
     * @param string $plugin_path
     * @param string $plugin_version
     * @return void
     */
    public static function init($plugin_path, $plugin_version)
    {
        // Initialize updates class
        new self($plugin_path, $plugin_version);
    }

    /**
     * Force WordPress to check for updates on activation
     *
     * @access public
     * @return void
     */
    public function on_activation()
    {
        if (!get_option('rightpress_up_pc_' . $this->plugin_key) && !get_option('rightpress_up_nag_' . $this->plugin_key)) {
            set_site_transient('update_plugins', null);
        }
    }

    /**
     * Executed on WordPress init action
     *
     * @access public
     * @return void
     */
    public function on_wp_init()
    {
        // Intercept Purchase Code submit
        if (isset($_POST['rightpress_updates_purchase_code'])) {
            $this->save_purchase_code($_POST['rightpress_updates_purchase_code']);
            unset($_POST['rightpress_updates_purchase_code']);
        }

        // Intercept nag dismiss request
        if (!empty($_REQUEST['rightpress_up_nag_dismiss'])) {
            if (!empty($_REQUEST['rightpress_up_plugin_slug']) && $_REQUEST['rightpress_up_plugin_slug'] === $this->plugin_slug) {
                $this->dismiss_purchase_code_nag();
            }
        }
    }

    /**
     * Register plugin with WordPress updater
     *
     * @access public
     * @param object $transient
     * @return array
     */
    public function register_plugin($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        // Get current version
        if (is_array($transient->checked) && isset($transient->checked[$this->plugin_basename])) {
            $current_version = $transient->checked[$this->plugin_basename];
        }
        else {
            $current_version = $this->plugin_version;
        }

        // Get data from RightPress Update service
        $response = $this->check_for_update($current_version);

        // Maybe delete Purchase Code from options
        if (is_object($response) && !empty($response->reset_purchase_code) && $response->reset_purchase_code) {
            $this->reset_purchase_code();
        }

        // Extend data
        if (is_object($response) && !empty($response->data) && is_object($response->data)) {
            $response->data->plugin = $this->plugin_basename;
        }

        // Purchase Code is empty
        if (empty($this->purchase_code)) {

            // Get version for nag
            if (is_object($response) && !empty($response->data) && is_object($response->data) && !empty($response->data->new_version)) {
                $nag_version = $response->data->new_version;
            }
            else {
                $nag_version = $current_version;
            }

            // Maybe add Purchase Code nag
            $this->maybe_add_purchase_code_nag($nag_version);

            return $transient;
        }

        // Register with WordPress updater only if package URL is set
        if (is_object($response) && !empty($response->data) && is_object($response->data) && !empty($response->data->package)) {
            $transient->response[$this->plugin_basename] = $response->data;
        }
        // Suppress wordpress.org plugins with the same name
        else if (isset($transient->response[$this->plugin_basename])) {
            if (strpos($transient->response[$this->plugin_basename]->package, 'wordpress.org') !== false) {
                unset($transient->response[$this->plugin_basename]);
            }
        }

        return $transient;
    }

    /**
     * Override WordPress.org Plugin Install API
     *
     * @acceess public
     * @param mixed $result
     * @param string $action
     * @param array $args
     * @return mixed
     */
    public function plugins_api_actions($result, $action, $args)
    {
        // Check if it's a call for this plugin
        if (empty($args->slug) || $args->slug !== $this->plugin_slug) {
            return $result;
        }

        // Get current plugin version
        $current_version = $this->get_current_plugin_version();

        // Check if plugin version was determined
        if (!$current_version) {
            return $result;
        }

        // Send request to remote service
        $response = $this->remote_post($action, array(
            'current_version'   => $current_version,
            'purchase_code'     => $this->purchase_code,
        ));

        // Error occurred
        if (!$response || empty($response->data)) {
            return new WP_Error('plugins_api_failed', __('An unexpected error occurred.', 'rightpress-updates'));
        }

        // Unserialize data object
        $data = unserialize($response->data);

        // Check if data object looks valid
        if ($action === 'plugin_information' && (!is_object($data) || empty($data->name))) {
            return new WP_Error('plugins_api_failed', __('An unexpected error occurred.', 'rightpress-updates'));
        }

        return $data;
    }

    /**
     * Check for update with RightPress Update service
     *
     * @access public
     * @param string $current_version
     * @return mixed
     */
    public function check_for_update($current_version)
    {
        // Send request to update service
        $response = $this->remote_post('updates', array(
            'current_version'   => $current_version,
            'purchase_code'     => $this->purchase_code,
        ));

        return $response;
    }

    /**
     * Prepare and send remote post request
     *
     * @access public
     * @param string $action
     * @param array $args
     * @return mixed
     */
    public function remote_post($action, $args = array())
    {
        global $wp_version;

        // Format request url
        $request_url = $this->endpoint_url . $action;

        // Push additional properties to request arguments
        $args['plugin_slug']    = $this->plugin_slug;
        $args['home_url']       = home_url();

        // Send request
        $response = wp_remote_post($request_url, array(
            'body'          => $args,
            'user-agent'    => ('WordPress/' . $wp_version),
        ));

        // Check response
        if (is_wp_error($response) || !is_array($response) || empty($response['body'])) {
            return false;
        }

        // Return response body
        return json_decode($response['body']);
    }

    /**
     * Get current plugin version
     *
     * @access public
     * @return mixed
     */
    public function get_current_plugin_version()
    {
        // Load plugin update data
        $update_plugins = get_site_transient('update_plugins');

        // Check if version is set and return it
        if (is_object($update_plugins) && isset($update_plugins->checked) && is_array($update_plugins->checked) && !empty($update_plugins->checked[$this->plugin_basename])) {
            return $update_plugins->checked[$this->plugin_basename];
        }

        return false;
    }

    /**
     * Maybe add Purchase Code nag
     *
     * @access public
     * @param array $version
     * @return void
     */
    public function maybe_add_purchase_code_nag($version)
    {
        // Nag is already displayed
        if (get_option('rightpress_up_nag_' . $this->plugin_key)) {
            return;
        }

        // Admin has dismissed nag for this version
        if (get_option('rightpress_up_nag_' . $this->get_version_key($version) . '_'  . $this->plugin_key)) {
            return;
        }

        // Add nag
        update_option(('rightpress_up_nag_' . $this->plugin_key), $version);
    }

    /**
     * Maybe display Purchase Code nag
     *
     * @access public
     * @return void
     */
    public function display_purchase_code_nag()
    {
        // Get nag version
        $nag_version = get_option('rightpress_up_nag_' . $this->plugin_key);

        // No nag to display
        if (!$nag_version) {
            return;
        }

        // Get current plugin version
        $current_version = $this->get_current_plugin_version();

        // Check if plugin version was determined
        if (!$current_version) {
            return;
        }

        // Get plugin name
        $plugin_data = get_plugin_data($this->plugin_path);
        $plugin_name = ((is_array($plugin_data) && !empty($plugin_data['Name'])) ? $plugin_data['Name'] : 'Plugin');

        // Display initial nag
        if ($nag_version === $current_version) {
            $description = sprintf(__('<strong>%s</strong> supports automatic updates. To receive them, enter your CodeCanyon Purchase Code below.', 'rightpress-updates'), $plugin_name);
            echo self::get_purchase_code_nag_html($nag_version, $description);
        }
        // Display new version nag
        else {
            $description = sprintf(__('New version of <strong>%s</strong> is available. To enable automatic updates, enter your CodeCanyon Purchase Code below.', 'rightpress-updates'), $plugin_name);
            echo self::get_purchase_code_nag_html($nag_version, $description);
        }
    }

    /**
     * Display new version Purchase Code nag
     *
     * @access public
     * @param string $nag_version
     * @param string $description
     * @return string
     */
    public function get_purchase_code_nag_html($nag_version, $description)
    {
        // Open container
        $html = '<div class="update-nag" style="display: block;">';

        // Title
        $html .= '<h3 style="margin-top: 0.3em; margin-bottom: 0.6em;">' . __('Automatic Update Setup', 'rightpress-updates') . '</h3>';

        // Description
        $html .= '<div style="margin-bottom: 0.6em; font-size: 13px;">' . $description . '</div>';

        // Error
        if (isset($this->nag_error_message) && !empty($this->nag_error_message)) {
            $html .= '<div style="margin-bottom: 0.6em; font-size: 13px; color: red;">' . $this->nag_error_message . '</div>';
        }

        // Open form
        $html .= '<form method="post" style="margin-bottom: 0.6em;">';

        // Field
        $html .= '<input type="text" name="rightpress_updates_purchase_code" value="' . (isset($this->nag_value) ? $this->nag_value : '') . '" placeholder="' . __('Purchase Code', 'rightpress-updates') . '" style="width: 50%; margin-right: 10px;">';

        // Button
        $html .= '<button type="submit" class="button button-primary" title="' . __('Submit', 'rightpress-updates') . '">' . __('Submit', 'rightpress-updates') . '</button>';

        // Close form
        $html .= '</form>';

        // Note
        $html .= '<div><small><a href="http://www.rightpress.net/purchase-code-help">' . __('Where do I find my Purchase Code?', 'rightpress-updates') . '</a>&nbsp;&nbsp;&nbsp;<a href="' . self::url_with_vars(array('rightpress_up_nag_dismiss' => 1, 'rightpress_up_plugin_slug' => $this->plugin_slug, 'rightpress_up_nag_version' => $nag_version)) . '">' . __('Hide This Notice', 'rightpress-updates') . '</a></small></div>';

        // Close container
        $html .= '</div>';

        return $html;
    }

    /**
     * Dismiss Purchase Code nag
     *
     * @access public
     * @return void
     */
    public function dismiss_purchase_code_nag()
    {
        // Remove nag
        delete_option('rightpress_up_nag_' . $this->plugin_key);

        // Never show nag for this version again
        if (!empty($_REQUEST['rightpress_up_nag_version'])) {
            update_option('rightpress_up_nag_' . $this->get_version_key($_REQUEST['rightpress_up_nag_version']) . '_'  . $this->plugin_key, 1);
        }

        // Get original page url
        $redirect_url = self::url_without_vars(array('rightpress_up_nag_dismiss', 'rightpress_up_plugin_slug', 'rightpress_up_nag_version'));

        // Redirect user and exit
        wp_redirect($redirect_url);
        exit;
    }

    /**
     * Process submitted Purchase Code
     *
     * @access public
     * @param string $purchase_code
     * @return void
     */
    public function save_purchase_code($purchase_code)
    {
        // Remove white space
        $purchase_code = trim($purchase_code);

        // Process Purchase Code
        try {

            // No Purchase Code or invalid format
            if (empty($purchase_code) || !$this->purchase_code_has_valid_format($purchase_code)) {
                throw new Exception(__('Purchase Code format is invalid.', 'rightpress-updates'));
            }

            // Validate Purchase Code
            $result = $this->validate_purchase_code($purchase_code);

            // Unable to verify Purchase Code right now (e.g. server is down)
            if ($result === false || $result === 'error') {
                throw new Exception(__('Unable to verify Purchase Code right now. Please try again later.', 'rightpress-updates'));
            }
            // Purchase Code is not valid
            else if ($result === 'not_valid') {
                throw new Exception(__('Purchase Code is not valid.', 'rightpress-updates'));
            }
            // Purchase Code belongs to a different product
            else if ($result === 'bad_product') {
                throw new Exception(__('Purchase Code belongs to another product.', 'rightpress-updates'));
            }
            // Purchase Code is valid - save it
            else if ($result === 'valid') {

                // Remove nag
                delete_option('rightpress_up_nag_' . $this->plugin_key);

                // Save Purchase Code
                update_option(('rightpress_up_pc_' . $this->plugin_key), $purchase_code);

                // Force WordPress to check for updates again
                set_site_transient('update_plugins', null);

                // Redirect user so that RightPress Updates is loaded with new config
                wp_redirect(self::current_url());
                exit;
            }
        }
        catch (Exception $e) {

            // Set nag error message and value
            $this->nag_error_message    = $e->getMessage();
            $this->nag_value            = $purchase_code;
        }
    }

    /**
     * Validate Purchase Code
     *
     * @access public
     * @param string $purchase_code
     * @return mixed
     */
    public function validate_purchase_code($purchase_code)
    {
        // Send request to Purchase Code validation service
        $response = $this->remote_post('purchase_code_validation', array(
            'purchase_code' => $purchase_code,
        ));

        // Check if request succeeded
        if ($response && is_object($response) && !empty($response->result)) {
            if (in_array($response->result, array('valid', 'not_valid', 'bad_product'))) {
                return $response->result;
            }
        }

        return false;
    }

    /**
     * Check if Purchase Code is of valid format
     *
     * @access public
     * @param string $purchase_code
     * @return bool
     */
    public function purchase_code_has_valid_format($purchase_code)
    {
        return (bool) preg_match('/[0-9a-zA-Z]{8}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{4}-[0-9a-zA-Z]{12}/', $purchase_code);
    }

    /**
     * Get Purchase Code
     *
     * @access public
     * @return mixed
     */
    public function get_purchase_code()
    {
        return get_option(('rightpress_up_pc_' . $this->plugin_key), '');
    }

    /**
     * Reset Purchase Code
     *
     * @access public
     * @return void
     */
    public function reset_purchase_code()
    {
        delete_option('rightpress_up_pc_' . $this->plugin_key);
        $this->purchase_code = '';
    }

    /**
     * Get plugin slug
     *
     * @access public
     * @return string
     */
    public function get_plugin_slug()
    {
        return dirname($this->plugin_basename);
    }

    /**
     * Get plugin key for use in WordPress option key
     *
     * @access public
     * @return string
     */
    public function get_plugin_key()
    {
        return preg_replace('/[^A-Za-z_]/', '', str_replace('-', '_', substr($this->plugin_slug, 0, 32)));
    }

    /**
     * Get version key for use in WordPress option key
     *
     * @access public
     * @param string $version
     * @return string
     */
    public static function get_version_key($version)
    {
        return str_replace('.', '_', $version);
    }

    /**
     * Retrieve URL with additional query variables
     *
     * @access public
     * @param array $input
     * @param string $url
     * @return string
     */
    public static function url_with_vars($input, $url = null)
    {
        return self::current_url('add', $url, $input);
    }

    /**
     * Retrieve URL without specified query variables
     *
     * @access public
     * @param array $input
     * @return string
     */
    public static function url_without_vars($input, $url = null)
    {
        return self::current_url('remove', $url, $input);
    }

    /**
     * Returns current URL
     * Adds or removes query variables if passed in arguments
     *
     * @access public
     * @param string $action
     * @param string $url
     * @param array $input
     * @return string
     */
    public static function current_url($action = 'add', $url = null, $input = array())
    {
        // Get URL
        if ($url === null) {
            $url_origin = self::get_request_url_origin();
            $url = $url_origin . $_SERVER['REQUEST_URI'];
        }
        else {
            $url_origin = untrailingslashit(preg_replace('/\?.*/', '', $url));
        }

        // Do not modify URL of no input is given
        if (empty($input) || !is_array($input)) {
            return $url;
        }

        // Parse URL
        $parsed = parse_url($url);

        // Get all query variables
        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $vars);
        }
        else {
            $vars = array();
        }

        // Proceed depending on action
        if ($action === 'add') {

            // Merge vars arrays
            $vars = array_merge($vars, $input);
        }
        else if ($action === 'remove') {

            // Iterate over keys and unset query vars
            if (is_array($input)) {
                foreach ($input as $key) {
                    if (isset($vars[$key])) {
                        unset($vars[$key]);
                    }
                }
            }
        }

        // Make query string
        $query_string = http_build_query($vars);

        // Start building URL
        $url = $url_origin . $parsed['path'];

        // Add query string
        if (!empty($query_string)) {
            $url .= '?' . $query_string;
        }

        // Return URL
        return $url;
    }

    /**
     * Get URL origin of current request
     * Note: This function does not support fancy stuff like user/pass
     *
     * @access public
     * @return string
     */
    public static function get_request_url_origin()
    {
        $s          = $_SERVER;
        $ssl        = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp         = strtolower($s['SERVER_PROTOCOL']);
        $protocol   = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port       = $s['SERVER_PORT'];
        $port       = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host       = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host       = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

}
}
