<?php
/* This is where you would inject your sql into the database 
   but we're just going to format it and send it back
*/
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