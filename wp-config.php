<?php
# Database Configuration
define( 'DB_NAME', 'wp_svpflowers2016' );
define( 'DB_USER', 'svpflowers2016' );
define( 'DB_PASSWORD', 'EOktoWs3h83fuJW8LdTo' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
//define( 'WCS_DEBUG', true );
//define( 'WP_DEBUG', true );
//define( 'WP_DEBUG_LOG', true );
 

$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '8A+z.R7ah+ 1>ci<Mlf7agw)9dMqTH--WJYnspE+7J*QXB@(,EN$siJcjjQ.$OL~');
define('SECURE_AUTH_KEY',  '?L-VL[1bK<d:{/DQ{}:;n aK0uypqDlUUzM>]RvFwz{W+MEhT~a`c0mZiT1d|}5v');
define('LOGGED_IN_KEY',    'jTS+2<amO!iS&[aj@_H 4W.--w%h0QZm+4s(byaKidh/-D~3[w%G#N8Z?(hO}S@n');
define('NONCE_KEY',        'E~o|9.7s.;O<)>;9(:jyd3/fznA2-}hoi(7cb@B#+XGs|^.[R6`r.8FA5vC;POj`');
define('AUTH_SALT',        'F eTaE=^S%2CI lYa]a7FVw=sugUcVaPB?eStJ8UBae+>}Xh+X0-]}-ZcX+QFJsi');
define('SECURE_AUTH_SALT', '6e6)t5.|J..}HFRg/lUlJdg@+2Jc+W|U:XTAnP1W0HnK0CX.;do`-)0_*ESV#>db');
define('LOGGED_IN_SALT',   'MtBPn^%.|#WMqr>YyH`oeX^@5IG[aZ&6GLyQpZ#}[eCdXC0, %Pg38cImx`u*0Hg');
define('NONCE_SALT',       '-Mtf],YFHCdc@gutegHi:|fk_4}bclKfqH|_`z%s1vXUNYnwno8KyEAOoyLZy_Ri');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'svpflowers2016' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'c8c69c162e13235bd2c6815937cbce8ed308ec63' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '33642' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', true );

define( 'FORCE_SSL_LOGIN', true );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'svp.flowers', 1 => 'svpflowers2016.wpengine.com', 2 => 'www.svp.flowers', );

$wpe_varnish_servers=array ( 0 => 'pod-33642', );

$wpe_special_ips=array ( 0 => '104.239.192.245', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

define( 'WP_SITEURL', 'http://svp.flowers' );

define( 'WP_HOME', 'http://svp.flowers' );
define('WPLANG', '');

# WP Engine ID
//changes

# WP Engine Settings







# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
