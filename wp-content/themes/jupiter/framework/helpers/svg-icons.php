<?php
if (!defined('THEME_FRAMEWORK'))
    exit('No direct script access allowed');

/**
 * This file is resposnible for generating SVG icons from the given font family and icon name
 *
 * @author      Bob Ulusoy & Bartosz Makos
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 1.0
 * @package     artbees
 */


class Mk_SVG_Icons
{
    
    
    function __construct()
    {
        
        
        add_action('wp_ajax_nopriv_mk_get_icon', array(
            &$this,
            'get_icons'
        ));
        add_action('wp_ajax_mk_get_icon', array(
            &$this,
            'get_icons'
        ));

    }
    
    

    /**
     * Deletes the svg_icons transient cache
     *
     * @param bool|true $clear_db
     * @return bool
     *
     */
    static function delete_transient_mk_svg_icons()
    {

        global $wpdb;
        $sql = "
               DELETE
               FROM {$wpdb->options}
               WHERE option_name like '\_transient\_timeout\_mk_svg_icons\_%'
               OR option_name like '\_transient\_mk_svg_icons\_%'
           ";
        $wpdb->query($sql);

    }



    /**
     * Compares svg_icons transient versions with current theme version.
     * If versions are equal returns true if not returns false
     *
     * @param bool|true $clear_db
     * @return bool
     *
     */
    static public function check_transient_svg_icons_versions()
    {
        if(get_option('mk_jupiter_theme_current_version') != get_option('mk_svg_icons_version')) {
            self::delete_transient_mk_svg_icons();
            update_option("mk_svg_icons_version", get_option('mk_jupiter_theme_current_version'));
        }
        return false;
    }
    
    
    
    /**
     * Safely and securely get file from server.
     * It attempts to read file using Wordpress native file read functions
     * If it fails, we use wp_remote_get. if the site is ssl enabled, we try to convert it http as some servers may fail to get file
     *
     * @param $file_url 		string    its directory URI
     * @return $wp_file_body  	string  	
     */
    private function remote_get($file_url)
    {
        global $wp_filesystem;
        
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        
        $wp_get_file_body = $wp_filesystem->get_contents($file_url);
        
        if ($wp_get_file_body == false) {
            $wp_remote_get_file = wp_remote_get($file_url);
            
            if (is_array($wp_remote_get_file) and array_key_exists('body', $wp_remote_get_file)) {
                $wp_remote_get_file_body = $wp_remote_get_file['body'];
                
            } else if (is_numeric(strpos($file_url, "https://"))) {
                
                $file_url           = str_replace("https://", "http://", $file_url);
                $wp_remote_get_file = wp_remote_get($file_url);
                
                if (!is_array($wp_remote_get_file) or !array_key_exists('body', $wp_remote_get_file)) {
                    echo "SSL connection error. Code: icon-get";
                    die;
                }
                
                $wp_remote_get_file_body = $wp_remote_get_file['body'];
            }
            
            $wp_file_body = $wp_remote_get_file_body;
            
        } else {
            $wp_file_body = $wp_get_file_body;
        }
        
        return $wp_file_body;
    }
    
    
    
    /**
     * get SVG freindly directions
     *
     * @param $direction 	string
     * @return string
     */
    function get_gradient_cords($direction)
    {
        switch ($direction) {
            case 'right':
                return 'x1="0%" y1="0%" x2="100%" y2="0%"';
            case 'bottom':
                return 'x1="0%" y1="100%" x2="0%" y2="0%"';
            case 'right-bottom':
                return 'x1="0%" y1="100%" x2="100%" y2="0%"';
            case 'right-top':
                return 'x1="0%" y1="0%" x2="100%" y2="100%"';
            default:
                return 'x1="0%" y1="100%" x2="0%" y2="0%"';
        }
    }


    function get_width($svg, $h) {
        preg_match_all('`"([^"]*)"`', $svg, $m);
        $vb = $m[1][1];
        $vb_arr = explode(' ', $vb);
        $natural_width = floatval($vb_arr[2]);
        $natural_height = floatval($vb_arr[3]);

        $p = $natural_height / $h;
        $width = ($p != 0 && $natural_width != 0) ? ($natural_width / $p) : false;

        return $width;
    }
    
    
    
    /**
     * Find first occurance of the given param and replace
     *
     * @param $search 	string  
     * @param $replace 	string
     * @param $subject 	string
     * @return string
     */
    function str_replace_first($search, $replace, $subject)
    {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }


    /**
     * Get the font icon unicode by providing the name and font family
     *
     * @param $name         string       (e.g. mk-moon-phone-3)
     * @param $family       string       (awesome-icons, icomoon, pe-line-icons, theme-icons)   
     * @return $unicode     string/boolean
     */
    public function get_class_name_by_unicode($family, $unicode)
    {
       
        $transient_name = 'mk_svg_icons_'.$family.'_json';    
        $cached_json = get_transient($transient_name);
       
        if($cached_json === false) {
            $file_path = '/assets/icons/' . $family;
        
            $dir_uri = get_template_directory_uri() . $file_path;
            $dir     = get_template_directory() . $file_path;
            
            if (file_exists($dir)) {
                
                $map = json_decode($this->remote_get($dir . '/map.json'), true);
                
                // Store the json data into database for the next execution phase.
                set_transient($transient_name, $map, DAY_IN_SECONDS);
                return array_search($unicode, $map);
            }    

        } else {
            return array_search($unicode, $cached_json);
        }
        
        return false;
    }
    
    
    
    
    /**
     * Get the font icon unicode by providing the name and font family
     *
     * @param $name 		string  	 (e.g. mk-moon-phone-3)
     * @param $family  		string       (awesome-icons, icomoon, pe-line-icons, theme-icons)  	
     * @return $unicode 	string/boolean
     */
    function get_unicode($name, $family)
    {
       
        $transient_name = 'mk_svg_icons_'.$family.'_json';    
        $cached_json = get_transient($transient_name);
       
        if($cached_json === false) {
            $file_path = '/assets/icons/' . $family;
        
            $dir_uri = get_template_directory_uri() . $file_path;
            $dir     = get_template_directory() . $file_path;
            
            if (file_exists($dir)) {
                $map = json_decode($this->remote_get($dir . '/map.json'), true);
                
                // Store the json data into database for the next execution phase.
                set_transient($transient_name, $map, DAY_IN_SECONDS);
                
                return $map[$name];
            }    

        } else {

            return $cached_json[$name];
        }
        
        return false;
    }
    
    
    
    /**
     * Get the SVG content by given font family and unicode
     *
     * @param $unicode 		string  	 (e.g. e47e)
     * @param $family  		string       (awesome-icons, icomoon, pe-line-icons, theme-icons)  	
     * @return string/boolean
     */
    function get_svg_content($family, $unicode)
    {
        $transient_name = 'mk_svg_icons_content_'.$family.'_file_' . $unicode;    
        $cached_icon = get_transient($transient_name);

        if($cached_icon === false) {
            $file_path = '/assets/icons/' . $family . '/svg/' . $unicode . '.svg';
            
            $file_uri = get_template_directory_uri() . $file_path;
            $file     = get_template_directory() . $file_path;
            
            if (file_exists($file)) {
                $file_content =  $this->remote_get($file_uri);
                set_transient($transient_name, $file_content, DAY_IN_SECONDS);
                return $file_content;
            }
        } else {
            return $cached_icon;
        }
        
        return false;
        
    }
    
    


    /**
     * Function to get the svg icon content send via ajax. This function is hooked into a WP native ajax action.
     *
     */
    public function get_icons() {

		$config  = $_POST['config'];

        if(!empty($config)) {

            $config = json_decode(urldecode($config), true); 

		    foreach($config as $c) {

		            // Use unique ID for plumbing any leaks in advance
		            $id = uniqid();

		            // Get parameters
		            $family  = $c['family'];
		            $name    = $c['name'];
		            
		            // Check if unicode sent to the fuction, if not we will figure it out via get_unicode method.
        			$unicode = !empty($c['unicode']) ? $c['unicode'] : $this->get_unicode($name, $family);

		            // these are optional for attrs
		            $fill               = $c['fill'];
		            $gradient_type      = $c['gradient_type'];
		            $gradient_start     = $c['gradient_start'];
		            $gradient_stop      = $c['gradient_stop'];
		            $gradient_direction = $c['gradient_direction'];
		            $height             = $c['height'];
		            $id                 = $c['id'];

		            // Get the SVG icon content
        			$svg = $this->get_svg_content($family, $unicode);

		            // Prepare SVG attributes
		            $atts = '';
		            $atts .= ' class="mk-svg-icon"';
		            $atts .= ' data-name="'.$name.'"';
		            $atts .= ' data-cacheid="'.$id.'"';

		            if($fill){
		            	$atts .= ' fill="'. $fill .'"';
		            }
		            
		            if($height){
                        $width = $this->get_width($svg, $height);
		            	$atts .= ' style="height:'. $height .'; width: '.$width.'px"';
		            }

		            // Prepare defs
		            $defs = '';
		            if($gradient_type == 'linear') {
		                $cords = $this->get_gradient_cords($gradient_direction);
		                $defs .= '<linearGradient id="gradient-'.$id.'" '.$cords.'><stop offset="0%"  stop-color="'.$gradient_start.'"/><stop offset="100%" stop-color="'.$gradient_stop.'"/></linearGradient>';
		            }
		            else if($gradient_type == 'radial') {
		                $defs .= '<radialGradient id="gradient-'.$id.'"><stop offset="0%"  stop-color="'.$gradient_start.'"/><stop offset="100%" stop-color="'.$gradient_stop.'"/></radialGradient>';
		            }


		             // wrap with tags
			        $defs = !empty($defs) ? '<defs>' . $defs . '</defs>' : '';
			        
			        // Prepare PATH attributes
			        $path_atts = $gradient_type ? (' fill="url(#gradient-' . $id . ')"') : '';
			        
			        
			        // Insert attributes and defs
			        if (!empty($atts)){
			            $svg = $this->str_replace_first('<svg', '<svg ' . $atts, $svg);
			        }

			        if (!empty($defs)){
			        	$svg = $this->str_replace_first('>', '>' . $defs, $svg);
			        }

			        if (!empty($path_atts)){
			            $svg = $this->str_replace_first('<path', '<path ' . $path_atts, $svg);
			        }

		            // Print
		            if(!empty($svg)) {

                        echo $svg;
                    }
		    }

		}

        wp_die();	

    }	


}



new Mk_SVG_Icons();