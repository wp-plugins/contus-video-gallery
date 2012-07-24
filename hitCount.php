<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Increase the hitcounts for the video.
 * @Creation Date : Feb 21 2011
 * @Modified Date : December 07 2011
 * */

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid = $_GET['vid'].'<br/>';
$hit     = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'");
$hitList = mysql_fetch_object($hit);
$hitCount= $hitList->hitcount;
$hitInc  = ++$hitCount;
mysql_query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare SET hitcount='$hitInc' WHERE vid = '$vid'");

?>
