<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Loads the wodpress configuration to the plugin.
 * @Creation Date : Feb 21 2011
 * @Modified Date : December 07 2011
 * */



/**
 * Bootstrap file for getting the ABSPATH constant to wp-load.php
 * This is requried when a plugin requires access not via the admin screen.
 *
 * If the wp-load.php file is not found, then an error will be displayed
 *
 * wp-content\plugins\contus-hd-flv-player\hdflv-config.php
 */

/** Define the server path to the file wp-config here, if you placed WP-CONTENT outside the classic file structure */

$path  = ''; // It should be end with a trailing slash    

/** That's all, stop editing from here **/

if ( !defined('WP_LOAD_PATH') )
{
    /** classic root path if wp-content and plugins is below wp-config.php */
    $classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;

    if (file_exists( $classic_root . 'wp-load.php') )
    define( 'WP_LOAD_PATH', $classic_root);
    else
    if (file_exists( $path . 'wp-load.php') )
    define( 'WP_LOAD_PATH', $path);
    else
    exit("Could not find wp-load.php");
}

// let's load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php');
?>