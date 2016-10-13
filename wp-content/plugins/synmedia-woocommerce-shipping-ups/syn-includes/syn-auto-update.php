<?php
/*
	* SYN_Auto_Update class.
	* Version: 1.0.1
	* @extends SYN_Auto_Update
*/
class SYN_Auto_Update
{
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;
    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path = 'http://api.woomedia.info';
    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    public $plugin_slug;
    /**
     * Plugin name (plugin_file)
     * @var string
     */
    public $slug;
    /**
     * Envato item id
     * @var string
     */
    public $item_id;
    /**
     * Envato item licence
     * @var string
     */
    public $licence_id;
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     */
    function __construct( $current_version, $plugin_slug, $item_id, $licence_id ){
    
    	// Set the class public variables
    	$this->current_version	= $current_version[ 'Version' ];
	    $this->plugin_slug		= $plugin_slug;
	    $this->item_id			= $item_id;
	    $this->licence_id		= $licence_id;
	    
	    // define the alternative API for updating checking
	    add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
	    
	    // Define the alternative response for information checking
	    add_filter( 'plugins_api', array( $this, 'check_info' ), 16, 3 );
    }
    
    /**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 * @return object $ transient
	 */
	function check_update( $checked_data ){
	
		include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version
		
		$request_string = array(
			'body' => array(
				'action'		=> 'basic_check', 
				'slug'			=> $this->plugin_slug,
				'version'		=> $this->current_version,
				'licence_id'	=> $this->licence_id,
				'item_id'		=> $this->item_id
			),
			'user-agent'		=> 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
		
		// Start checking for an update
		$raw_response = wp_remote_post( $this->update_path, $request_string );
		
		if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) )
			return $checked_data;

		$response = (object) json_decode( wp_remote_retrieve_body( $raw_response ), true );
		
		if ( isset( $response ) && is_object( $response ) && isset( $response->new_version ) ) // Feed the update data into WP updater
			$checked_data->response[ $this->plugin_slug ] = $response;
		
		return $checked_data;
	}
	
	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info( $def, $action, $args ){
	
		if( !isset( $args->slug ) || $args->slug != $this->plugin_slug )
			return $def;
		
		include ABSPATH . WPINC . '/version.php'; // include an unmodified $wp_version
		
		$request_string = array(
			'body' => array(
				'action'		=> $action, 
				'slug'			=> $this->plugin_slug,
				'version'		=> $this->current_version,
				'licence_id'	=> $this->licence_id,
				'item_id'		=> $this->item_id
			),
			'user-agent'		=> 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
		
		$raw_response = wp_remote_post( $this->update_path, $request_string );
		
		if ( is_wp_error( $raw_response ) )
			return new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with api.woomedia.info or this server&#8217;s configuration. If you continue to have problems, please try to <a href="http://www.woomedia.info">contact us</a>.' ), $raw_response->get_error_message() );
			
		$response = (object) json_decode( wp_remote_retrieve_body( $raw_response ), true );
			
		if ( !is_object( $response ) )
			return new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with api.woomedia.info or this server&#8217;s configuration. If you continue to have problems, please try to <a href="http://www.woomedia.info">contact us</a>.' ) );

		return $response;
	}

}


?>