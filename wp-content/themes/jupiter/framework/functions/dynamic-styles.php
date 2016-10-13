<?php
if (!defined('THEME_FRAMEWORK')) exit('No direct script access allowed');

/**
 * This file is responsible from all dynamic css and js proccess and minification
 *
 * @author      Bob Ulusoy & Uğur Mirza ZEYREK 
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 1.0
 * @package     artbees
 */

class Mk_Static_Files
{
    const GLOBAL_ASSETS = 'global_assets';
    const THEME_OPTIONS_CSS = 'theme-options.css';
    
    function __construct($with_actions = true) {
        
        global $mk_dev;
        global $global_assets_diff;
        global $dynamic_global_components_css;
        global $dynamic_global_components_js;
        
        $mk_dev = (defined('MK_DEV') ? constant("MK_DEV") : false);
        
        // Minify Static files
        require_once THEME_INCLUDES . '/minify/src/Minifier.php';
        require_once THEME_INCLUDES . '/minify/src/SimpleCssMinifier.php';
        
        if ($with_actions) {
            
            if (($mk_dev) != true) {
                $priority = 11;
            } 
            else {
                $priority = 999;
            }

            $theme_option = get_option(THEME_OPTIONS);

            if(isset($theme_option['pagespeed-optimization']) and $theme_option['pagespeed-optimization'] != 'false' and !$this->is_page_backend()) {
                add_action('after_setup_theme', array(&$this, 'buffer_start'));
                add_action('wp_footer', array(&$this, 'buffer_end'), 999999999);
            }

            add_action('wp_head', array(&$this, 'critical_path_css'),1);

            add_action('wp_enqueue_scripts', array(&$this,
                'process_global_styles'
            ));
            
            add_action('wp_enqueue_scripts', array(&$this,
                'enqueue_default_stylesheet'
            ), 30);
            
            $global_assets = self::GLOBAL_ASSETS;
            add_action('wp_footer', array(&$this,
                $global_assets
            ) , 99999);
            add_action('wp_footer', array(&$this,
                'dynamic_styles'
            ) , $priority);
            add_action('wp_footer', array(&$this,
                'addGlobalAssetJSToEnqueue'
            ) , $priority);
            add_action('wp_enqueue_scripts', array(&$this,
                'addGlobalAssetCSSToEnqueue'
            ) , $priority);
            
            // if the theme options file isn't exists in order to access global variable we need to use different priority level
            if (get_option("theme_options") == "") $priority = 999;
            add_action('wp_enqueue_scripts', array(&$this,
                'addThemeOptionsCSSToEnqueue'
            ) , $priority);
            add_action('wp_footer', array(&$this,
                'dynamic_global_assets'
            ) , 99999);
            add_action('wp_footer', array(&$this,
                'dynamic_theme_options'
            ) , 99999);
        }
    }


    static function is_referer_admin_ajax()
    {
        global $pagenow;
        
        $result = in_array($pagenow, array(
            'admin-ajax.php'
        ));

        if($result) {
            return true;
        }
    }


    static function is_page_backend()
    {
        $is_admin = !(!is_numeric(strpos($_SERVER["REQUEST_URI"],"?wc-ajax")) and !is_admin() and !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php','admin-ajax.php')) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"/wp-admin")) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"wc-ajax"))   ));

        if($is_admin) {
            return true;
        }
    }


    /**
     * Append shortcode css files into app_dynamic_styles global variable
     * @return mixed
     */
    static function shortcode_id() {
        global $mk_shortcode_order;
        
        $mk_shortcode_order++;
        return $mk_shortcode_order;
    }
    
    /**
     * Define the global variables for dynamic shortcode styles, global and posts dynamic styles.
     *
     */
    public function init_globals() {
        
        $app_dynamic_styles = array();
        $app_global_dynamic_styles = $app_local_dynamic_styles = '';
        
        global $app_dynamic_styles, $app_global_dynamic_styles, $app_local_dynamic_styles;
    }
    
    /**
     * Load the dynamic files. these files can be overrided in child themes if needed.
     *
     */
    public function include_files() {
        $styles_dir = get_template_directory() . '/dynamic-styles/*/*.php';
        
        $styles = glob($styles_dir);
        
        foreach ($styles as $style) {
            include_once ($style);
        }
    }
    
    /**
     * Append shortcode css files into app_dynamic_styles global variable.
     * @param string $app_styles
     * @param int $id
     */
    static function addCSS($app_styles, $id) {
        global $app_dynamic_styles;
        
        $app_dynamic_styles[] = array(
            'id' => $id,
            'inject' => $app_styles
        );

        if(self::is_referer_admin_ajax()) {
            echo '<style type="text/css">'.$app_styles.'</style>';  
        }

    }
    
    /**
     * Appends short code names into the app_global_assets array
     *
     * @param $shortcode_name
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function addAssets($shortcode_name) {
        global $app_global_assets;
        if (!is_array($app_global_assets)) $app_global_assets = array();
        
        if (!in_array($shortcode_name, $app_global_assets)) {
            $app_global_assets[] = $shortcode_name;
        }
        if(self::is_referer_admin_ajax() && !in_array($shortcode_name, array('vc_row', 'vc_column', 'vc_row_inner'))) {

            $extensions = array('js', 'css');
            foreach ($extensions as $extension) {

                $filename = "/components/shortcodes/" . $shortcode_name . "/" . $shortcode_name . "." . $extension;

                if (file_exists(get_template_directory() . $filename)) {
                        
                    if (file_exists(get_stylesheet_directory() . $filename)) {
                        echo "<link rel='stylesheet' href='".get_stylesheet_directory_uri() . $filename."' type='text/css' media='all' />";
                    } else {
                        echo "<link rel='stylesheet' href='".get_template_directory_uri() . $filename."' type='text/css' media='all' />";
                    }

                }
            }

            
        }
    }
    
    /**
     * Stores the asset and updates the option if specified.
     *
     * @param $folder
     * @param $filename
     * @param $file_content
     */
    static function StoreAsset($folder, $filename, $file_content) {
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        self::createPath($folder);
        $sha1_concat_string = sha1($file_content);
        $file_path = path_convert($folder . '' . $filename);
        if (get_option($filename . "_sha1") != $sha1_concat_string or !file_exists($file_path)) {
            
            //  $comment =  "/* option:  ".get_option($filename."_sha1")."  sha1_concat_string:  $sha1_concat_string file_path: $file_path exists : ".file_exists($file_path)." ".time()." */
            //          ";
            $comment = "";
            $wp_filesystem->put_contents($file_path, $comment . $file_content, FS_CHMOD_FILE
            
            // predefined mode settings for WP files
            );
            
            update_option($filename . "_sha1", $sha1_concat_string);
        }
    }


    /**
     * Store Assets which is already defined in merged_assets array
     *
     * @param array $merged_assets
     * @param bool  $minify
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update Version 5.1
     */
    static function DynamicGlobalAssets($merged_assets = array(),$minify = true) {
        global $wp_filesystem;
        global $dynamic_global_components_css;
        global $dynamic_global_components_js;
        
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        $dynamic_global_components_css = self::ConcatenateAssetsByExtension($merged_assets, "css", $minify);
        $dynamic_global_components_js = self::ConcatenateAssetsByExtension($merged_assets, "js", $minify);
    }
    
    /**
     * Store Assets which is already defined in merged_assets array
     *
     * Usage Example:
     *
     * $minify = true;
     * $store_options["js"] = "combined.min.js";
     * $store_options["css"] = "combined.min.css";
     * $merged_assets = array("shortcode1","shortcode2");
     *
     * @param array $merged_assets
     * @param array $store_options
     * @param bool|true $minify
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function StoreGlobalAssets($merged_assets = array(), $store_options = array() , $minify = true) {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        global $components_filename;
        
        update_option('global_assets_filename', $components_filename, true);
        $merged_assets = array_unique($merged_assets);
        foreach ($store_options as $extension => $filename) {
            $concat_string = self::ConcatenateAssetsByExtension($merged_assets, $extension, $minify);
            $upload_dir = self::get_global_asset_upload_folder("directory");
            self::createPath($upload_dir);
            $sha1_concat_string = sha1($concat_string);
            $file_path = path_convert($upload_dir . '' . $filename);
            if (get_option($filename . "_sha1") != $sha1_concat_string or !file_exists($file_path)) {
                
                $wp_filesystem->put_contents($upload_dir . '' . $filename, $concat_string, FS_CHMOD_FILE
                
                // predefined mode settings for WP files
                );
                
                update_option($filename . "_sha1", $sha1_concat_string);
            }
        }
    }
    
    /**
     * Stores the app_global_dynamic_styles into the mk_assets/theme_options.css file
     *
     * @param bool|true $minify
     */
    static function StoreThemeOptionStyles($minify = true) {
        global $app_global_dynamic_styles;
        global $mk_dev;
        global $mk_options;
        global $dynamic_theme_options_css;
        
        $extension = "css";
        $time = "production";
        
        if ($mk_dev) $time = "dev";
        
        $filename = str_replace(".css", "-" . $time . ".css", self::THEME_OPTIONS_CSS);
        $folder = self::get_global_asset_upload_folder("directory");
        $string = $app_global_dynamic_styles;
        
        if ($minify && $mk_dev != true && $mk_options['minify-' . $extension] != 'false') {
            $string = self::minify_string($app_global_dynamic_styles, "css");
        }
        
        $file_path = $folder . '' . $filename;
        if (self::deleteFile($folder . '' . $filename) != true) {
            die("A problem occurred while trying to delete theme-options css file: $file_path ");
        }
        
        $dynamic_theme_options_css = $string;
        
        self::StoreAsset($folder, $filename, $string);
        update_option("theme_options", $filename, true);
    }


    /**
     * Deletes the the mk_assets/theme_options-***.css file
     *
     * @return bool
     */
    public function DeleteThemeOptionStyles() {
        $filename = get_option('theme_options');
        $folder = $this->get_global_asset_upload_folder("directory");
        
        if ($this->deleteFile($folder . '' . $filename) != true and $filename != "") {
            die("A problem occurred while trying to delete theme-options css file");
        } 
        else {
            update_option("theme_options", "", true);
            return true;
        }
    }
    
    /**
     * Concatenates defined assets in merged_assets array by extension and gets the output as a string
     *
     * Usage Example:
     *
     * $minify = true;
     * $extension = "js";
     * $merged_assets = array("shortcode1","shortcode2");
     *
     * @param $merged_assets
     * @param $extension
     * @param bool|true $minify
     * @return string
     *
     * @author      Ugur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0.5
     * @last_update Version 5.1
     */
    static function ConcatenateAssetsByExtension($merged_assets, $extension, $minify = true) {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        global $mk_options;
        global $mk_dev;
        global $dynamic_global_components_css;
        global $dynamic_global_components_js;
        $file_contents = "";

        if ($extension != "css" and $extension != "js") {
            return $file_contents;
        }

        $wp_remote_get_file_body = "";

        if (is_array($merged_assets) && !empty($merged_assets)) {
           $merged_assets = array_unique($merged_assets);
           foreach ($merged_assets as $asset) {
               $transient_name = "mk_assets_".$asset."_".$extension;
               $filename = "/components/shortcodes/" . $asset . "/" . $asset . "." . $extension;
               $wp_file_body = get_transient($transient_name);

               if(!$wp_file_body or $mk_dev) {

                   if (file_exists(get_template_directory() . $filename)) {

                       $wp_get_file_body = $wp_filesystem->get_contents(get_template_directory().$filename);

                           if($wp_get_file_body == false ) {
                                $base_dir = mk_base_url();
                                $get_template_directory_uri = get_template_directory_uri();

                                if(is_numeric(strpos($get_template_directory_uri, $base_dir))) {
                                    $get_template_directory_uri = $get_template_directory_uri;
                                } else {
                                    $get_template_directory_uri = $base_dir . $get_template_directory_uri;
                                }

                               $file_url = $get_template_directory_uri . $filename;
                               $wp_remote_get_file = wp_remote_get($file_url);

                               if(is_array($wp_remote_get_file) and array_key_exists('body', $wp_remote_get_file)) {

                                   $wp_remote_get_file_body = $wp_remote_get_file['body'];

                                } else if (is_numeric(strpos($file_url, "https://"))) {

                                   $file_url = str_replace("https://","http://",$file_url);
                                   $wp_remote_get_file = wp_remote_get($file_url);

                                   if(!is_array($wp_remote_get_file) or !array_key_exists('body', $wp_remote_get_file)) {
                                       echo "SSL connection error. Code: ds-ConcatenateAssetsByExtension";
                                       die;
                                   }

                                   $wp_remote_get_file_body = $wp_remote_get_file['body'];

                               }
                               $wp_file_body = $wp_remote_get_file_body;
                               unset($wp_remote_get_file);
                               unset($wp_remote_get_file_body);
                           } else {
                               $wp_file_body = $wp_get_file_body;
                               unset($wp_get_file_body);
                           }
                   }

                   $wp_file_body = ($wp_file_body) ? $wp_file_body : " /* ".$asset." */ ";
                   set_transient($transient_name, $wp_file_body, 30*60*60);
               }

               $file_contents.= $wp_file_body . " \n ";
               unset($wp_file_body);
           }
        }

        if (($minify && $mk_dev != true && $mk_options['minify-' . $extension] != 'false') or (isset($mk_options['pagespeed-optimization']) and $mk_options['pagespeed-optimization'] != 'false')) {
            $file_contents = self::minify_string($file_contents, $extension);
        }
        
        return $file_contents;
    }
    
    /**
     * Enqueues Global Asset Javascript File
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update Version 5.0.5
     */
    static function addGlobalAssetJSToEnqueue() {
        global $mk_dev;
        $global_assets_js = self::get_global_asset_address("js", "url");
        global $dynamic_global_assets;
        
        if (!file_exists(self::get_global_asset_address("js", "directory"))) {
            self::jupiter_delete_row("type", "short_code");
            self::global_assets();
            $dynamic_global_assets = true;
        } 
        else {
            if ($mk_dev == false) wp_enqueue_script('global-assets-js', $global_assets_js, array(), self::global_assets_timestamp());
        }
    }
    
    /**
     * Enqueues Global Asset CSS File
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update Version 5.0.5
     */
    static function addGlobalAssetCSSToEnqueue() {
        global $mk_dev;
        global $dynamic_global_assets;
        
        if (!file_exists(self::get_global_asset_address("css", "directory"))) {
            self::jupiter_delete_row("type", "short_code");
            self::global_assets();
            $dynamic_global_assets = true;
        } 
        else {
            $global_assets_css = self::get_global_asset_address("css", "url");
            if ($global_assets_css) {
                if ($mk_dev == false) wp_enqueue_style('global-assets-css', $global_assets_css, array(), self::global_assets_timestamp());
            }
        }
    }
    
    /**
     * Enqueues Theme Options CSS File
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update Version 5.0.5
     */
    static function addThemeOptionsCSSToEnqueue() {
        global $mk_dev;
        global $dynamic_theme_options_css;
        
        if (get_option("theme_options") == "" or $mk_dev or !file_exists(self::get_global_asset_upload_folder("directory") . get_option("theme_options"))) {
            $dynamic_theme_options_css = true;
        }
        
        if ((get_option("theme_options") != "" and file_exists(self::get_global_asset_upload_folder("directory") . get_option("theme_options")))) {
            $dynamic_theme_options_css = false;
            $theme_options_css = self::get_global_asset_upload_folder("url") . get_option("theme_options");
            if ($theme_options_css) {
                if ($mk_dev == false) wp_enqueue_style('theme-options', $theme_options_css, array(), self::global_assets_timestamp());
            }
        }
    }

    function enqueue_default_stylesheet()
    {
        wp_enqueue_style('mk-style', get_stylesheet_uri() , false, false, 'all');
    }
    
    function process_global_styles() {
        
        
        // declaring the globals
        global $mk_options, $app_local_dynamic_styles;
        
        $this->init_globals();
        $this->include_files();
        
        $output = $app_local_dynamic_styles;
        
        $output.= mk_enqueue_woocommerce_font_icons();
        
        $output.= $this->insert_shortcode_styles(global_get_post_id());
        
        $output.= $mk_options['custom_css'];
        
        // instantiate minify class
        //   if ($mk_dev != true && $mk_options['minify-'.$extension] != 'false') {
        $minifier = new SimpleCssMinifier();
        $output = $minifier->minify($output);
        
        //   }
        
        wp_enqueue_style('theme-dynamic-styles', get_template_directory_uri() . '/custom.css');
        
        wp_add_inline_style('theme-dynamic-styles', $output);
    }
    
    /**
     * Takes shortcode assets from global. Creates static .js and .css files. Combines and minifies assets into those files.
     * merge array with saved assets then update option
     *
     * @author      Uğur Mirza ZEYREK & Bob Ulusoy
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update     Version 5.1
     */
    
    static function global_assets() {
        global $app_global_assets;
        global $components_filename;
        global $mk_dev;
        global $global_assets_diff;
        global $dynamic_global_assets;
        global $mk_options;

        $saved_assets = array();
        $merged_assets = array();
        $global_assets_diff = array();
        $path = get_template_directory() . "/components/shortcodes/";
        $defined_constants = get_defined_constants(true);

        if ($mk_dev) {
        $components_suffix = "dev";
        } else if(isset($mk_options['disable-dynamic-assets']) and $mk_options['disable-dynamic-assets'] == 'true') {
        $components_suffix = "full";
        } else {
        $components_suffix = "production";
        }

        $components_filename = "components-" . $components_suffix;
        $saved_assets_db_object = self::jupiter_get_rows("type", "short_code", "name");
        
        if (is_array($saved_assets_db_object)) {
            foreach ($saved_assets_db_object as $saved_asset_db_object) {
                $saved_assets[] = $saved_asset_db_object->name;
            }
        }
        
        if (!$app_global_assets) $app_global_assets = array();

        if($components_suffix == "full") {
            $app_global_assets = mk_scandir($path, GLOB_ONLYDIR);
            $merged_assets = array_merge($saved_assets, $app_global_assets);
        } else {
            $merged_assets = array_merge($saved_assets, $app_global_assets);
        }
        
        if($mk_dev) {
            $global_assets_diff = mk_scandir($path, GLOB_ONLYDIR);
        } else {
            $global_assets_diff = self::fast_array_diff($merged_assets, $saved_assets);
        }
        
        if (sizeof($global_assets_diff) or $mk_dev) {
            $global_assets_diff = array_unique($global_assets_diff);
            self::DynamicGlobalAssets($global_assets_diff);
            $insert_arrays = array();
            $current_time = current_time('mysql');
            foreach ($global_assets_diff as $asset) {
                if (self::jupiter_get_row("name", $asset) == false) {
                    $insert_arrays[] = array(
                        'type' => "short_code",
                        'status' => 1,
                        'name' => $asset,
                        'added_date' => $current_time,
                        'last_update' => $current_time
                    );
                }
            }

            if (count($insert_arrays))
            self::jupiter_insert_rows($insert_arrays);
            $store_options["js"] = $components_filename . ".min.js";
            $store_options["css"] = $components_filename . ".min.css";
            update_option('global_assets_filename', "");
            self::delete_global_assets(false);
            self::StoreGlobalAssets($merged_assets, $store_options, true);
            update_option('global_assets_timestamp', time());
        }
    }

    /**
     * Takes the timestamp of the global assets. Creates if it's not yet created.
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0.5
     * @last_update Version 5.0.5
     */
    static function global_assets_timestamp() {

        $timestamp = get_option('global_assets_timestamp');

        if(!is_numeric($timestamp)) {
            $timestamp = time();
            update_option('global_assets_timestamp',$timestamp);
        }

        return $timestamp;
    }

    /**
     * Adds critical styles in the header
     *
     * @author      Ugur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0.7
     * @last_update Version 5.0.7
     */

    function critical_path_css() {

        $critical_path_css_body = get_transient("mk_critical_path_css");
        $status = "";
        if (!$critical_path_css_body) {

            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }

            $wp_remote_get_file_body = "";
            $critical_path_css_filename = "/assets/stylesheet/min/critical-path.css";
            $critical_path_css_body = $wp_filesystem->get_contents(get_template_directory() . $critical_path_css_filename);

            if (!$critical_path_css_body) {

                $base_dir = mk_base_url();
                $get_template_directory_uri = get_template_directory_uri();
                if(is_numeric(strpos($get_template_directory_uri, $base_dir))) {
                    $get_template_directory_uri = $get_template_directory_uri;
                } else {
                    $get_template_directory_uri = $base_dir . $get_template_directory_uri;
                }

                $file_url = $get_template_directory_uri . $critical_path_css_filename;
                $wp_remote_get_file = wp_remote_get($file_url);

                if(is_array($wp_remote_get_file) and array_key_exists('body', $wp_remote_get_file)) {

                    $wp_remote_get_file_body = $wp_remote_get_file['body'];

                } else if (is_numeric(strpos($file_url, "https://"))) {

                    $file_url = str_replace("https://","http://",$file_url);
                    $wp_remote_get_file = wp_remote_get($file_url);

                    if(!is_array($wp_remote_get_file) or !array_key_exists('body', $wp_remote_get_file)) {
                        echo " Dynamic styling error. Code: ds-critical_path_css";
                        die;
                    }

                    $wp_remote_get_file_body = $wp_remote_get_file['body'];

                }
                $critical_path_css_body = $wp_remote_get_file_body;
            }
            $status .= "/* non cached */ ";
            set_transient("mk_critical_path_css", $critical_path_css_body, 15 * 60);
        }

        echo "<style id=\"critical-path-css\" type='text/css'>".$status."" . $critical_path_css_body . "</style>";
    }

    /**
     * Collect Shortcode dynamic styles and using javascript inject them to <head>
     */
    
    function dynamic_styles() {
        global $app_dynamic_styles;
        
        $post_id = global_get_post_id();
        
        $saved_styles = get_post_meta($post_id, '_dynamic_styles', true);
        
        $saved_styles_build = get_post_meta($post_id, '_theme_options_build', true);
        $theme_option_build = get_option(THEME_OPTIONS_BUILD);
        
        $styles = unserialize(base64_decode(get_post_meta($post_id, '_dynamic_styles', true)));
        
        if (empty($styles)) {
            $css = '';
            if (is_array($app_dynamic_styles) && !empty($app_dynamic_styles)) {
                foreach ($app_dynamic_styles as $style) {
                    $css.= $style['inject'];
                }
            }
            $css = preg_replace('/\r|\n|\t/', '', $css);
            echo "<style id=\"dynamic_styles\" type='text/css'>" . $css . "</style>";
        }
        
        if (empty($saved_styles) || $saved_styles_build != $theme_option_build) {
            update_post_meta($post_id, '_dynamic_styles', base64_encode(serialize(($app_dynamic_styles))));
            update_post_meta($post_id, '_theme_options_build', $theme_option_build);
        }
    }
    
    function dynamic_global_assets() {
        global $dynamic_global_components_css;
        global $dynamic_global_components_js;
        global $mk_dev;
        
        if ($dynamic_global_components_css or $mk_dev) echo "<style id='dynamic-global-assets-css' type='text/css'> /* " . time() . " - */ " . $dynamic_global_components_css . "</style>";
        if ($dynamic_global_components_js or $mk_dev) echo "<script id='dynamic-global-assets-js' type=\"text/javascript\"> /*  " . time() . " */ " . $dynamic_global_components_js . "</script>";
    }
    
    function dynamic_theme_options() {
        global $mk_dev;
        global $dynamic_theme_options;
        global $dynamic_theme_options_css;
        
        if ($mk_dev == true or $dynamic_theme_options_css) {
            self::StoreThemeOptionStyles();
            echo "<style id='dynamic-theme-options-css' type='text/css'> /*  " . time() . " */ " . $dynamic_theme_options_css . "</style>";
        }
    }
    
    static function minify_string($string, $extension) {
        if ($extension == "css") {
            $minifier = new SimpleCssMinifier();
            $minified_content = $minifier->minify($string);
        } 
        else if ($extension == "js") {
            $minified_content = \JShrink\Minifier::minify($string);
        } 
        else {
            die("wrong extension for minify_string");
        }
        return $minified_content;
    }
    
    /**
     * Inset dynamic styles retrived from post meta options
     * @param int $id
     * @return string $css
     */
    function insert_shortcode_styles($id) {
        if (!$id) return;
        $css = '';
        $styles = unserialize(base64_decode(get_post_meta($id, '_dynamic_styles', true)));
        if (!empty($styles)) {
            foreach ($styles as $style) {
                $css.= $style['inject'];
            }
        }
        return $css;
    }
    
    /**
     * Append global styles that set in theme options into app_global_dynamic_styles global variable.
     * @param string $styles
     *
     */
    static function addGlobalStyle($styles) {
        global $app_global_dynamic_styles;
        
        $app_global_dynamic_styles.= $styles;
    }
    
    /**
     * Append local styles that set in post meta options into app_local_dynamic_styles global variable.
     * @param string $styles
     *
     */
    static function addLocalStyle($styles) {
        global $app_local_dynamic_styles;
        
        $app_local_dynamic_styles.= $styles;
    }
    
    /**
     * Gets the upload folder address by the specified type.
     * Type variable should be passed as "directory" or "url"
     *
     * Usage Example:
     * $type = "directory";
     *
     * @param $type
     * @return string
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function get_global_asset_upload_folder($type) {
        $upload_folder_name = "mk_assets";
        $wp_upload_dir = wp_upload_dir();
        if ($type == "directory") {
            $upload_dir = $wp_upload_dir['basedir'] . '/' . $upload_folder_name . '/';
        } 
        else if ($type == "url") {
            $upload_dir = $wp_upload_dir['baseurl'] . '/' . $upload_folder_name . '/';
        } 
        else {
            return "";
        }
        
        return $upload_dir;
    }

    /**
     * Deletes the mk_getimagesize transient cache.
     *
     * @param bool|true $clear_db
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0.6
     * @last_update Version 5.0.6
     */
    static function delete_transient_mk_getimagesize()
    {

        global $wpdb;
        $sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_mk\_getimagesize\_%'
               OR option_name like '\_transient\_timeout\_mk\_getimagesize\_%'
           ";

        $wpdb->query($sql);

    }

    /**
     * Deletes the mk_critical_styles transient cache.
     *
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0.7
     * @last_update Version 5.0.7
     */
    static function delete_transient_mk_critical_path_css()
    {

        global $wpdb;
        $sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_timeout\_mk\_critical\_path\_css%'
               OR option_name like '\_transient\_mk\_critical\_path\_css%'
           ";
        $wpdb->query($sql);

    }

    /**
     * Deletes the mk_assets transient cache
     *
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.1
     * @last_update Version 5.1
     */
    static function delete_transient_mk_assets()
    {

        global $wpdb;
        $sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_timeout\_mk\_assets\_%'
               OR option_name like '\_transient\_mk\_assets\_%'
           ";
        $wpdb->query($sql);

    }

    /**
     * Compares mk_assets transient versions with current theme version.
     * If versions are equal returns true if not returns false
     *
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.1
     * @last_update Version 5.1
     */
    static function check_transient_mk_assets_versions()
    {
        if(get_option('mk_jupiter_theme_current_version') != get_option('mk_assets_version')) {
            self::delete_transient_mk_assets();
            update_option("mk_assets_version", get_option('mk_jupiter_theme_current_version'));
            return false;
        } else {
            return true;
        }
    }

    /**
     * Takes combined asset filenames with get_option and tries to delete them if they are exists
     * after returns true if both of them are not exists anymore.
     *
     * If clear_db passed as false, otherwise the function keeps db records.
     *
     * @param bool|true $clear_db
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function delete_global_assets($clear_db = true) {
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        $js_deleted = true;
        $css_deleted = true;
        $global_assets_filename = get_option('global_assets_filename');

        if (!$global_assets_filename) {
            $global_assets_filename = "";
        }
        
        $assets = glob(self::get_global_asset_upload_folder("directory") . "*");
        for ($i = 0; $i < count($assets); $i++) {
            
            if ($global_assets_filename) {
                
                if (stristr($assets[$i], "components")) {
                    $try_count = 0;
                    while (file_exists($assets[$i]) and $try_count < 3) {
                        if ($try_count > 1) sleep(1);
                        $wp_filesystem->delete($assets[$i]);
                        $try_count++;
                    }
                } else {
                    
                    // TODO: remove those after testing on live
                    //echo "<!-- not deleted $assets[$i] " . strpos($assets[$i], "components") . " --> ";
                }

            } else {

                if (stristr($assets[$i], "components")) {
                    $wp_filesystem->delete($assets[$i]);
                }

            }
        }
        
        if ($css_deleted or $js_deleted) {
            if ($clear_db) {
                self::jupiter_delete_row("type", "short_code");
                update_option('global_assets_filename', "");
            }
        }
        
        return ($js_deleted and $css_deleted);
    }
    
    /**
     * Gets the global asset address by the extension and type.
     *
     * Usage Example:
     * $extension = "js";
     * $type = "directory";
     *
     * @param $extension
     * @param $type
     * @return string
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function get_global_asset_address($extension, $type) {
        if ($extension != "css" and $extension != "js") {
            return "";
        }
        
        if (get_option('global_assets_filename')) {
            $upload_dir = self::get_global_asset_upload_folder($type);
            if (!$upload_dir) return "";
            
            $filename_pre = get_option('global_assets_filename');
            $filename = $upload_dir . $filename_pre . ".min." . $extension;
            
            return $filename;
        } 
        else {
            return "";
        }
    }
    
    /**
     * A faster way to check array differences comparing to built-in php array_diff function
     *   $a = range(1, 10000);
     *   $b = range(5000, 15000);
     *   shuffle($a);
     *   shuffle($b);
     *   $ts = microtime(true);
     *   fast_array_diff($a, $b);
     *   printf("ME =%.4f\n", microtime(true) - $ts);
     *   $ts = microtime(true);
     *   array_diff($a, $b);
     *   printf("PHP=%.4f\n", microtime(true) - $ts);
     *   fast_array_diff = 0.0140
     *   array_diff = 19.5980
     *   source = http://stackoverflow.com/questions/2479963/how-does-array-diff-work/6700430#6700430
     *
     * @param array $a
     * @param array $b
     * @return array
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function fast_array_diff($a = array() , $b = array()) {
        $map = array();
        foreach ($a as $val) $map[$val] = 1;
        foreach ($b as $val) unset($map[$val]);
        return array_keys($map);
    }
    
    /**
     * A method to insert data into this table with the $args that will be passed to the method
     *
     * @param array $row_array
     * @return false|int
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function jupiter_insert_row($row_array = array() , $jupiter_table_name = null) {
        global $wpdb;
        global $jupiter_table_name;
        $jupiter_table_name = esc_sql($jupiter_table_name);
        return $wpdb->insert($jupiter_table_name, $row_array);
    }
    
    /**
     * A method for inserting multiple rows into the specified table
     *
     *
     *  $insert_arrays = array();
     *  foreach($assets as $asset) {
     *
     *  $insert_arrays[] = array(
     *  'type' => "multiple_row_insert",
     *  'status' => 1,
     *  'name'=>$asset,
     *  'added_date' => current_time( 'mysql' ),
     *  'last_update' => current_time( 'mysql' ));
     *
     *  }
     *
     *  wp_insert_rows($insert_arrays);
     *
     * @param array $row_arrays
     * @param string $jupiter_table_name
     * @return false|int
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     *
     */
    static function jupiter_insert_rows($row_arrays = array() , $jupiter_table_name = null) {
        global $wpdb;
        global $jupiter_table_name;
        $jupiter_table_name = esc_sql($jupiter_table_name);
        
        // Setup arrays for Actual Values, and Placeholders
        $values = array();
        $place_holders = array();
        $query = "";
        $query_columns = "";
        
        $query.= "INSERT INTO {$jupiter_table_name} (";
        
        foreach ($row_arrays as $count => $row_array) {
            
            foreach ($row_array as $key => $value) {
                
                if ($count == 0) {
                    if ($query_columns) {
                        $query_columns.= "," . $key . "";
                    } 
                    else {
                        $query_columns.= "" . $key . "";
                    }
                }
                
                $values[] = $value;
                
                if (is_numeric($value)) {
                    if (isset($place_holders[$count])) {
                        $place_holders[$count].= ", '%d'";
                    } 
                    else {
                        $place_holders[$count] = "( '%d'";
                    }
                } 
                else {
                    if (isset($place_holders[$count])) {
                        $place_holders[$count].= ", '%s'";
                    } 
                    else {
                        $place_holders[$count] = "( '%s'";
                    }
                }
            }
            
            // mind closing the GAP
            $place_holders[$count].= ")";
        }
        
        $query.= " $query_columns ) VALUES ";
        
        $query.= implode(', ', $place_holders);
        if ($values) {
            $query = $wpdb->prepare($query, $values);
            
            if ($wpdb->query($query)) {
                return true;
            } 
            else {
                return false;
            }
        } 
        else {
            return true;
        }
    }
    
    /**
     * A method that deletes the row based on a column
     *
     * @param $column_name
     * @param $match_value
     * @return false|int
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function jupiter_delete_row($column_name, $match_value) {
        global $wpdb;
        global $jupiter_table_name;
        $jupiter_table_name = esc_sql($jupiter_table_name);
        return $wpdb->delete($jupiter_table_name, array(
            $column_name => $match_value
        ));
    }
    
    /**
     * A method to retrieve table row based on a column data (in params this column should be passed, eg. $column_name, $match_value )
     * @param $column_name
     * @param $match_value
     * @param $selected_columns
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function jupiter_get_row($column_name, $match_value, $selected_columns = null) {
        global $wpdb;
        global $jupiter_table_name;
        $column_name = esc_sql($column_name);
        if ($selected_columns) {
            $selected_columns = esc_sql($selected_columns);
        } 
        else {
            $selected_columns = "*";
        }
        
        // we need to check sql injection for column_name
        $sql = "
            SELECT      $selected_columns
            FROM        {$jupiter_table_name}
            WHERE       {$column_name} = %s
            ";
        
        $postid = $wpdb->get_row($wpdb->prepare($sql, $match_value));
        if ($postid) {
            return $postid;
        } 
        else {
            return false;
        }
    }
    
    /**
     * A method to retrieve table row, based on a column data (in params this column should be passed, eg. $column_name, $match_value )
     * @param $column_name
     * @param $match_value
     * @param $selected_columns
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function jupiter_get_rows($column_name, $match_value, $selected_columns = null) {
        global $wpdb;
        global $jupiter_table_name;
        $jupiter_table_name = $wpdb->prefix . "mk_components";
        $column_name = esc_sql($column_name);
        if ($selected_columns) {
            $selected_columns = esc_sql($selected_columns);
        } 
        else {
            $selected_columns = "*";
        }
        
        // we need to check sql injection for column_name
        $sql = "
            SELECT      $selected_columns
            FROM        {$jupiter_table_name}
            WHERE       $column_name = %s
            ";
        
        $postids = $wpdb->get_results($wpdb->prepare($sql, $match_value));
        if ($postids) {
            return $postids;
        } 
        else {
            return null;
        }
    }
    
    /**
     * If the path is already exists returns true.
     * If it doesn't creates the path.
     * When something goes wrong returns false.
     * @param $path
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function createPath($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
        $return = self::createPath($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
    
    /**
     * If the file is not exists or deleted successfully returns true.
     * When something goes wrong returns false.
     * @param $filename
     * @return bool
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     */
    static function deleteFile($filename) {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        if (!$wp_filesystem->exists($filename)) return true;
        
        return $wp_filesystem->delete($filename);
    }

    /*
     * TODO: infrastructural solution for moving assets to footer
     */
    function MoveAssetsFooter() {

        remove_action('wp_head', 'wp_print_scripts');
        remove_action('wp_head', 'wp_print_head_scripts', 9);
        remove_action('wp_head', 'wp_enqueue_scripts', 1);

        add_action('wp_footer', 'wp_print_scripts', 5);
        add_action('wp_footer', 'wp_enqueue_scripts', 5);
        add_action('wp_footer', 'wp_print_head_scripts', 5);

        add_action( 'wp_enqueue_scripts', 'remove_head_scripts');
    }

    public function buffer_start() {

        global $time_start;
        $time_start = time();
        ob_start(array( $this, 'move_assets_to_footer'));
    }

    public function buffer_end() {
        
        ob_end_flush();
    }


    /**
     * The key method for Google PageSpeed optimization. It get's the whole html output
     * and move asset files to the bottom. Except the critical css path.
     *
     * @author      Uğur Mirza ZEYREK
     * @copyright   Artbees LTD (c)
     * @link        http://artbees.net
     * @since       Version 5.0
     * @last_update Version 5.0.8
     *
     * @param $output
     * @return mixed|string
     */
    function move_assets_to_footer($output)
    {
        global $mk_options;
        global $time_start;
        
        $ajax_check = (isset($output{3})) ? mb_substr($output, 0, 3) : $output;
        if(is_numeric(strpos($ajax_check,"{"))) return $output;

        $is_admin = !(!is_numeric(strpos($_SERVER["REQUEST_URI"],"?wc-ajax")) and !is_admin() and !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php','admin-ajax.php')) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"/wp-admin")) and !is_numeric(strpos($_SERVER["REQUEST_URI"],"wc-ajax"))   ));
        if($is_admin) return $output;


        if(isset($mk_options['pagespeed-optimization']) and $mk_options['pagespeed-optimization'] != 'false') {

            $content = '';
            $asset = '';
            $catch_assets_regex = '';
            $asset_rel = '';

            // 1) Grab between head and <body>
            preg_match_all('#(<head[^>]*>.*?<footer id="mk_page_footer">)#ims', $output, $body);
            $content = implode('',$body[0]);

            $catch_assets_regex .= '(';
            $catch_assets_regex .= '(<\!\-\-\[if(.*?)endif\]\-\->)|';
            $catch_assets_regex .= '(<script(.*?)<\/script>)|';
            $catch_assets_regex .= '(<noscript(.*?)<\/noscript>)|';
            $catch_assets_regex .= '(<style(?! id=(\"|\')critical-path-css(\"|\'))(.*?)<\/style>)|';
            $catch_assets_regex .= '(<link(\s*?|\n*?)rel=(\"|\')stylesheet(\"|\')(.*?)\/>)';
            $catch_assets_regex .= ')';

            // 2) Find <script>s in html
            preg_match_all('#'.$catch_assets_regex.'#ims', $content, $matches);
            if(!$matches) {
                return "regex error. code: ds1138. please disable google pagespeed optimization.";
            }
            $moved_scripts_count = count($matches[0]);
            foreach ($matches[0] as $key=>$value) {
                if(strpos($value,"<link rel")===0) {
                    $asset_rel .= $value;
                } else {
                    $asset .= $value;
                }
            }

            $asset = $asset . $asset_rel;

            // 3) Remove <script>s from <body>
            $content2 = preg_replace('#'.$catch_assets_regex.'#ims', '<!-- asset moved to [/body] -->', $content);
            //   $content2 = preg_replace('#'.$catch_assets_regex.'#ims', '', $content);

            // 4) Add <script>s to bottom of <body>
            $content2 = preg_replace('#<body(.*?)<footer id="mk_page_footer">#ims', '<body$1<footer id="mk_page_footer">'.$asset.'', $content2);

            // 5) Replace <body> with new <body>
            $output = str_replace($content, $content2, $output);

            // minify html
            $output = $this->minify_html($output);

            $time_duration = time() - $time_start;

            return $output. " <!-- moved $moved_scripts_count assets & minified html in ".$time_duration." seconds -->";
        } else {
            return $output;
        }

    }

    /**
     * -----------------------------------------------------------------------------------------
     * Based on `https://github.com/mecha-cms/mecha-cms/blob/master/system/kernel/converter.php`
     * -----------------------------------------------------------------------------------------
     */
// HTML Minifier
    function minify_html($input) {
        if(trim($input) === "") return $input;
        // Remove extra white-space(s) between HTML attribute(s)
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $input));
        // Minify inline CSS declaration(s)
        if(strpos($input, ' style=') !== false) {
            $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
                return '<' . $matches[1] . ' style=' . $matches[2] . $this->minify_css($matches[3]) . $matches[2];
            }, $input);
        }
        return preg_replace(
            array(

                // t = text
                // o = tag open
                // c = tag close
                // Keep important white-space(s) after self-closing HTML tag(s)

                // Remove a line break and two or more white-space(s) between tag(s)
                '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
                '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid */
                '#\s*<!--(\s*?|\n*?)(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#ims'
            ),
            array(
                '$1$2$3',
                '$1$2$3',
                '$1$2$3$4$5',
                '$1$2$3$4$5$6$7',
                '$1$2$3',
                '<$1$2',
                '$1 ',
                '$1',
                ""
            ),
            $input);
    }
// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
    function minify_css($input) {
        if(trim($input) === "") return $input;
        return preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                // Replace `:0 0 0 0` with `:0`
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                // Replace `background-position:0` with `background-position:0 0`
                '#(background-position):0(?=[;\}])#si',
                // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                '#(?<=[\s:,\-])0+\.(\d+)#s',
                // Minify string value
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                // Minify HEX color code
                '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                // Replace `(border|outline):none` with `(border|outline):0`
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                // Remove empty selector(s)
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2'
            ),
            $input);
    }
// JavaScript Minifier
    function minify_js($input) {
        if(trim($input) === "") return $input;
        return preg_replace(
            array(
                // Remove comment(s)
                '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                // Remove white-space(s) outside the string and regex
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
                // Remove the last semicolon
                '#;+\}#',
                // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                // --ibid. From `foo['bar']` to `foo.bar`
                '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
            ),
            array(
                '$1',
                '$1$2',
                '}',
                '$1$3',
                '$1.$3'
            ),
            $input);
    }
}
new Mk_Static_Files();