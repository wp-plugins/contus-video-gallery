<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video hitcount file.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid = $_GET['vid'].'<br/>';
$hit     = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare where vid='".intval($vid)."'");
$hitList = mysql_fetch_object($hit);
$hitCount= $hitList->hitcount;
$hitInc  = ++$hitCount;
mysql_query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET hitcount='".intval($hitInc)."' WHERE vid = '".intval($vid)."'");

?>
