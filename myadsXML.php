<?php

/*
 * version : 1.3
 * Edited by : k.laxmi
 * Email : lakshmi.rani@contus.in
 * Purpose : Create ads for player
 * Path:/wp-content/plugins/contus-hd-flv-player/myadsXML.php
 * Date:3/3/11
 *
 */


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