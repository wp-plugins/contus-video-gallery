<?php
/*
Description:  Wordpress video gallery
Edited By: Saranya
Version: 1.2
Plugin URI: www.hdflvplayer.net/wordpress-video-gallery/
wp-content\plugins\contus-hd-flv-player\hitCount.php
Date : 21/2/2011

*/
require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid = $_GET['vid'].'<br/>';
$hit     = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'");
$hitList = mysql_fetch_object($hit);
$hitCount= $hitList->hitcount;
$hitInc  = ++$hitCount;
mysql_query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare SET hitcount='$hitInc' WHERE vid = '$vid'");

?>
