<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress Video Gallery plugin config file.
  Version: 2.8
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
/**
 * Bootstrap file for getting the ABSPATH constant to wp-load.php
 * This is requried when a plugin requires access not via the admin screen.
 * * If the wp-load.php file is not found, then an error will be displayed
 * * wp-content\plugins\contus-video-gallery\hdflv-config.php
 * Define the server path to the file wp-config here, if you placed WP-CONTENT outside the classic file structure */
$path = '';	   // It should be end with a trailing slash

 error_reporting(E_ALL); 
 ini_set("display_errors", 1); 
 if ( ! defined( 'WP_LOAD_PATH' ) ) {
	// classic root path if wp-content and plugins is below wp-config.php
	$classic_root = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/';
	if ( file_exists( $classic_root . 'wp-load.php' ) )
		define( 'WP_LOAD_PATH', $classic_root );
	else
	if ( file_exists( $path . 'wp-load.php' ) )
		define( 'WP_LOAD_PATH', $path );
	else
		exit( 'Could not find wp-load.php' );
}
// let's load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php' );
?>