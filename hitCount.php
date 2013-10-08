<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video hitcount file.
  Version: 2.3.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid            = $_GET['vid'];             ## Get video id from url
$hit            = mysql_query("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid='" . intval($vid) . "'");
$hitList        = mysql_fetch_object($hit);
$hitCount       = $hitList->hitcount;       ## Get view count for particular video and increase it
$hitInc         = ++$hitCount;
## Update Hit count here
mysql_query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET hitcount='" . intval($hitInc) . "' WHERE vid = '" . intval($vid) . "'");
?>