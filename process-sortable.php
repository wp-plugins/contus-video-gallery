<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	      : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : For Sortable Process
 * @Creation Date : Fev 21 2011
 * @Modified Date : Jul 19, 2012
 * */

require_once( dirname(__FILE__) . '/hdflv-config.php');

// get the path url from querystring

function get_out_now() { exit; }
add_action('shutdown', 'get_out_now', -1);

global $wpdb;


$title = 'hdflv Playlist';

$pid1 = $_GET['playid'];

foreach ($_GET['listItem'] as $position => $item) :
    mysql_query("UPDATE $wpdb->prefix"."hdflvvideoshare_med2play SET `sorder` = $position WHERE `media_id` = $item and playlist_id=$pid1 ");
endforeach;

$tables = $wpdb->get_results("SELECT vid FROM $wpdb->prefix"."hdflvvideoshare LEFT JOIN ".$wpdb->prefix."hdflvvideoshare_med2play ON (vid = media_id) WHERE (playlist_id = '$pid1') ORDER BY sorder ASC, vid ASC");


if($tables) {
                    foreach($tables as $table) {
                          $playstore1  .= $table->vid.",";
                    }
                }

 $f= fopen('text.txt','w');
fwrite($f,print_r($playstore1,true));
fclose($f);

               //$comma_playstore = implode(",", $playstore);
               print_r($playstore1);

?>