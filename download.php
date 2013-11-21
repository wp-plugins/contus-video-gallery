<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress video gallery Featured videos widget.
  Version: 2.3.1.0.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
require_once( dirname(__FILE__) . '/hdflv-config.php');
$url                    = $_GET['f'];
$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];
$image_path             = str_replace('plugins/'.$dirPage, 'uploads/videogallery/', APPTHA_VGALLERY_BASEDIR);
$filename               = $image_path.$url;
$allowedExtensions      = array("avi", "AVI", "dv", "DV", "3gp", "3GP", "3g2", "3G2", "mpeg", "MPEG", "wav", "WAV", "rm",
                    "RM", "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v",
                    "F4V", "f4v");
$output                 = in_array(end(explode(".", $url)), $allowedExtensions);
if (!$output) { 
    return false;
} else { 
    if(file_exists($filename)){
    header('Content-disposition: attachment; filename=' . basename($filename));
    readfile($filename);
    }
}
?>