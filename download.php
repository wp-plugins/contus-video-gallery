<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress video gallery Featured videos widget.
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
require_once( dirname(__FILE__) . '/hdflv-config.php');
$url                    = filter_input(INPUT_GET, 'f');         ## Get file from URL
$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];
$image_path             = str_replace('plugins/'.$dirPage, 'uploads/videogallery/', APPTHA_VGALLERY_BASEDIR);   ## Get file path
$filename               = $image_path.'/'.$url;         ## Genearate file name
$allowedExtensions      = array("avi", "AVI", "dv", "DV", "3gp", "3GP", "3g2", "3G2", "mpeg", "MPEG", "wav", "WAV", "rm",
                        "RM", "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v",
                        "F4V", "f4v");
$extension              = end(explode(".", $url));      ## Get file extension
$output                 = in_array($extension, $allowedExtensions);    ## Check for valid extension
if (!$output) { 
    return false;
} else { 
    if(file_exists($filename)){             ## If file exist download file
        ob_clean();
        header("Content-Type: video/$extension");
        header('Content-disposition: attachment; filename=' . basename($filename));
        header("Content-length: " . filesize($filename) . "\n\n"); 
        readfile($filename);
        exit;
    }
}
?>