<?php
/*
  Plugin URI: www.hdflvplayer.net/wordpress-video-gallery/
  Edited by : Saranya
  Version: 1.0
  Purpose : Create playlist for player
 * wp-content\plugins\contus-hd-flv-player\myextractXML.php
  Date : 21/2/2011
 */

// look up for the path
require_once( dirname(__FILE__) . '/hdflv-config.php');

// get the path url from querystring
$playlist_id = $_GET['pid'];

function get_out_now() {
    exit;
}

add_action('shutdown', 'get_out_now', -1);

global $wpdb;

$title = 'hdflv Playlist';

$themediafiles = array();
$limit = '';

if (isset($_GET['pid']) || isset($_GET['vid'])) {
// Fetching videos of the selected playlist
    $playlist_id = intval($_GET['pid']);
    $playlist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = '$playlist_id'");
    if ($playlist)
    {
        $select = " SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w";
        $select .= " INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m";
        $select .= " WHERE (m.playlist_id = '$playlist_id'";
        $select .= " AND m.media_id = w.vid) GROUP BY w.vid ";
        $select .= " ORDER BY m.sorder ASC , m.porder " . $playlist->playlist_order . " ,w.vid " . $playlist->playlist_order;
        $themediafiles = $wpdb->get_results($wpdb->prepare($select));
        $title = $playlist->playlist_name;
    } else
    {
        $vid = $_GET['vid'];
        $select = "SELECT * FROM " . $wpdb->prefix ."hdflvvideoshare where vid='$vid'";

        $themediafiles = $wpdb->get_results($select);
        $getPlaylist   = $wpdb->get_results("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vid'");
         foreach ($getPlaylist as $getPlaylists)
        {
            if ($getPlaylists->playlist_id != '')
            {
              $playlist_id = $getPlaylists->playlist_id;
            }
            else
            {
                echo "<script>alert('No videos is  here');</script>";
            }


        $fetch_video   = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        WHERE (m.playlist_id = '$playlist_id'
        AND m.media_id = w.vid AND m.media_id != '$vid') GROUP BY w.vid";
        $fetched       = $wpdb->get_results($fetch_video);
        $themediafiles = array_merge($themediafiles,$fetched);
        }
    }

    $options1 = get_option('HDFLVSettings');
    $autoPlay = $wpdb->get_col("SELECT autoplay FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    if ($autoPlay[0] == 1)
    {
        $ap = 'true';
    } else
    {
        $ap = 'false';
    }

// Create XML output of playlist
    header("content-type:text/xml;charset = utf-8");
    echo '<?xml version = "1.0" encoding = "utf-8"?>';
    echo "<playlist autoplay = '$ap' random = 'false'>";


    if (is_array($themediafiles))
    {

        foreach ($themediafiles as $media)
        {

            if ($media->image == '')
            {
                $image = get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg';
            } else
            {
                $image = $media->image;
            }
            $file = pathinfo($media->file);
            if ($media->hdfile != '')
            {
                $hd = 'true';
            } else
            {
                $hd = 'false';
            }
             //k.laxmi Mar 02 , 2011 To pass the preroll value
        if ($media->prerollads == 0) {
            $preroll = ' preroll = "false"';
            $preroll_id = ' preroll_id = "0"';
        }
        else {
            $preroll = ' preroll = "true"';
            $preroll_id = ' preroll_id = "' . $media->prerollads . '"';
        }

         //k.laxmi Mar 02 , 2011 To pass the preroll value
        if ($media->postrollads == 0) {
            $postroll = ' postroll = "false"';
            $postroll_id = ' postroll_id = "0"';
        }
        else {
            $postroll = ' postroll = "true"';
            $postroll_id = ' postroll_id = "' . $media->postrollads . '"';
        }
        $tagsList = $wpdb->get_col("SELECT tags_name from " . $wpdb->prefix . "hdflvvideoshare_tags WHERE media_id='$media->vid'");
        $seoList = $wpdb->get_col("SELECT seo_name from " . $wpdb->prefix . "hdflvvideoshare_tags WHERE media_id='$media->vid'");
        $tagsName = implode(',',$tagsList);
        $seoName  = implode(',',$seoList);
        if($media->download=='1')
	    $download = "true";
		else
		$download = "false";
            echo '<mainvideo';
            echo ' id = "' . htmlspecialchars($media->vid) . '"';
            echo ' url = "' . htmlspecialchars($media->file) . '"';
            echo ' thu_image = "' . htmlspecialchars($image) . '"';
             echo 'allow_download="' . htmlspecialchars($download) . '"';
            echo ' Preview = "' . htmlspecialchars($media->image) . '"';
            echo ' Tag =  "'. $tagsName. '"';
            echo ' Seo = "'. $seoName .'"';
            echo $postroll_id;
            echo $preroll_id;
            echo $postroll;
            echo $preroll;
            echo ' hd = "' . $hd . '"';
            echo ' hdpath = "' . $media->hdfile . '">';
            echo '<title><![CDATA[' . htmlspecialchars($media->name) . ']]></title> ';
            echo htmlspecialchars($media->name);
            echo '' . '</mainvideo>';
        }
    }

    echo '</playlist>';
}
?>