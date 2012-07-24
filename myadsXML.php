<?php

/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Create ads for player
 * @Creation Date : Fev 21 2011
 * @Modified Date : December 07 2011
 * */

/* Used to import plugin configuration */
require_once( dirname(__FILE__) . '/hdflv-config.php');

// get the path url from querystring
$playlist_id = $_GET['pid'];

function get_out_now() {
    exit;
}

add_action('shutdown', 'get_out_now', -1);

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