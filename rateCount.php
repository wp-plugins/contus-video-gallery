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
$get_rate       = $_GET['rate'];            ## Get Rate count from url
if ($get_rate) {
## Update rate count count here
mysql_query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET rate=" . intval($get_rate) . "+rate,ratecount=1+ratecount WHERE vid = '" . intval($vid) . "'");
$ratecount            = mysql_query("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid='" . intval($vid) . "'");
$rateList        = mysql_fetch_object($ratecount);
$rateCount      = $rateList->ratecount;       ## Get rate count for particular video and display it
echo $rateCount;
exit;
		}
?>