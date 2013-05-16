<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: AdsXML file for player.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

/* Used to import plugin configuration */
require_once( dirname(__FILE__) . '/hdflv-config.php');

// get the path url from querystring
$playlist_id = $_GET['pid'];
  
global $wpdb;

$title = 'hdflv Adslist';

$themediafiles = array();
$limit = '';



        $selectPlaylist .= " (SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads w" . ")";
        $adsFiles = $wpdb->get_results($wpdb->prepare($selectPlaylist));
        $themediafiles = $adsFiles;
        ob_clean();
       // header ("content-type: text/xml");
            echo '<?xml version="1.0" encoding="utf-8"?>';
            echo '<ads >';
            $current_path="components/com_contushdvideoshare/videos/";

            if(count($themediafiles)>0)
        {
            foreach($themediafiles as $rows)
            {
                $timage="";               
                    $postvideo=$rows->file_path;
                   // $prevideo=$rows->prevideopath;
               
                echo '<ad id="'.$rows->ads_id.'" url="'.$postvideo.'" >';
                echo '<![CDATA['.$rows->title.']]>';
                echo '</ad>';

            }
        }
            echo '</ads>';
?>