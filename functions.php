<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Common function need throughout the plugin.
 * @Creation Date : Feb 21 2011
 * @Modified Date : December 07 2011
 * */


require_once( dirname(__FILE__) . '/hdflv-config.php');
if (isset($_GET['name'])) {
    return hd_ajax_add_playlist($_GET['name'], $_GET['media']);
}
/*
  +----------------------------------------------------------------+
  +	hdflv-admin-functions
  +
  +   required for hdflv
  +----------------------------------------------------------------+
 */

function render_error($message) {
?>
    <div class="wrap"><h2>&nbsp;</h2>
        <div class="error" id="error">
            <p><strong><?php echo $message ?></strong></p>
        </div></div>
<?php
}

/*
 *
 * *****************************************************************
 */
/* get_playlist by ID
 * **************************************************************** */

function get_playlistname_by_ID($pid = 0) {
    global $wpdb;

    $pid = (int) $pid;
    $result = $wpdb->get_var("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = $pid ");

    return $result;
}

function get_tagsname_by_ID($vtag_id = 0) {
    global $wpdb;
    $vtag_id = (int) $vtag_id;
    $result = $wpdb->get_var("SELECT tags_name FROM " . $wpdb->prefix . "hdflvvideoshare_tags WHERE vtag_id = $vtag_id");
    return $result;
}

function get_sortorder($mediaid = 0, $pid) {
    global $wpdb;

    $mediaid = (int) $mediaid;
    $result = $wpdb->get_var("SELECT sorder FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id = $mediaid and playlist_id= $pid");

    return $result;
}

/* * ****************************************************************
  /* return filename of a complete url
 * **************************************************************** */

function wpt_filename($urlpath) {
    $filename = substr(($t = strrchr($urlpath, '/')) !== false ? $t : '', 1);
    return $filename;
}

/* * ****************************************************************
  /* get_playlist output f?r DBX
 * **************************************************************** */

function get_playlist_for_dbx($mediaid) {

    global $wpdb;


    // get playlist ID's
    $playids = $wpdb->get_col("SELECT pid FROM " . $wpdb->prefix . "hdflvvideoshare_playlist");
    // echo "SELECT pid FROM $wpdb->hdflv_playlist";
    // to be sure
    $mediaid = (int) $mediaid;

    // get checked ID's'
    $checked_playlist = $wpdb->get_col("
		SELECT playlist_id,sorder
		FROM " . $wpdb->prefix . "hdflvvideoshare_playlist," . $wpdb->prefix . "hdflvvideoshare_med2play
		WHERE " . $wpdb->prefix . "hdflvvideoshare_med2play.playlist_id = pid AND " . $wpdb->prefix . "hdflvvideoshare_med2play.media_id = '$mediaid'");

    if (count($checked_playlist) == 0)
        $checked_playlist[] = 0;

    $result = array();
    //print_r($playids);
    // create an array with playid, checked status and name
    if (is_array($playids)) {
        foreach ($playids as $playid) {
            $result[$playid]['playid'] = $playid;
            $result[$playid]['checked'] = in_array($playid, $checked_playlist);
            $result[$playid]['name'] = get_playlistname_by_ID($playid);
            $result[$playid]['sorder'] = get_sortorder($mediaid, $playid);
        }
    }

    $hiddenarray = array();
    echo "<table>";
    foreach ($result as $playlist) {

        $hiddenarray[] = $playlist['playid'];
        echo '<tr><td style="font-size:11px"><label for="playlist-' . $playlist['playid']
        . '" class="selectit"><input value="' . $playlist['playid']
        . '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
        . '"' . ($playlist['checked'] ? ' checked="checked"' : "") . '/> ' . wp_specialchars($playlist['name']) . "</label></td >&nbsp;<td style='font-size:11px;padding-left:13px'><input type=text size=3 id=sort-" . $playlist['playid'] . " name=sorder[] value=" . $playlist['sorder'] . ">Sort order</td></tr>
            ";
    }
    echo "</table>";
    $comma_separated = implode(",", $hiddenarray);
    echo "<input type=hidden name=hid value = $comma_separated >";
}

// For Tags

function get_vidtags_dbx($mediaid) {
    global $wpdb;

    $vtagid = $wpdb->get_var("SELECT vtag_id FROM  " . $wpdb->prefix . "hdflvvideoshare_tags WHERE media_id='$mediaid'");
    echo get_tagsname_by_ID($vtagid);
}

// End of Tags

function get_playlist() {

    global $wpdb;


    // get playlist ID's
    $playids = $wpdb->get_col("SELECT pid FROM " . $wpdb->prefix . "hdflvvideoshare_playlist");
    // echo "SELECT pid FROM $wpdb->hdflv_playlist";
    // to be sure
    $mediaid = (int) $mediaid;

    if (count($checked_playlist) == 0)
        $checked_playlist[] = 0;

    $result = array();
    //print_r($playids);
    // create an array with playid, checked status and name
    if (is_array($playids)) {
        foreach ($playids as $playid) {
            $result[$playid]['playid'] = $playid;
            $result[$playid]['checked'] = in_array($playid, $checked_playlist);
            $result[$playid]['name'] = get_playlistname_by_ID($playid);
            $result[$playid]['sorder'] = get_sortorder($mediaid, $playid);
        }
    }

    $hiddenarray = array();
    echo "<table>";
    foreach ($result as $playlist) {

        $hiddenarray[] = $playlist['playid'];
        echo '<tr><td style="font-size:11px"><label for="playlist-' . $playlist['playid']
        . '" class="selectit"><input value="' . $playlist['playid']
        . '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
        . '"' . ($playlist['checked'] ? ' checked="checked"' : "") . '/> ' . wp_specialchars($playlist['name']) . "</label></td >&nbsp;<td style='font-size:11px;padding-left:13px'><input type=text size=3 id=sort-" . $playlist['playid'] . " name=sorder[] value=" . $playlist['sorder'] . ">Sort order</td></tr>
            ";
    }
    echo "</table>";
    $comma_separated = implode(",", $hiddenarray);
    echo "<input type=hidden name=hid value = $comma_separated >";
}

function get_vtags() {
    global $wpdb;
    // get playlist ID's
    $media_id = $_GET['media'];
    $getTagsid = $wpdb->get_col("SELECT vtag_id FROM  " . $wpdb->prefix . "hdflvvideoshare_tags WHERE video_id='$media_id'");
    echo "<table>";
    foreach ($getTagsid as $key => $taglist) {
        $vtagid = $taglist;


        //$checked_id = in_array($vtagid, $checked_playlist);
        $hiddenvtags = $wpdb->get_col("SELECT vtagid FROM " . $wpdb->prefix . "hdflvvideoshare_tags WHERE video_id='$media_id'");
        echo '<tr><td style="font-size:11px"><label for="vtags-' . $vtagid
        . '" class="selectit">' . get_tagsname_by_ID($vtagid) . "</label></td><td style='font-size:11px;padding-left:13px'></td></tr>
           ";
    }
    echo "</table>";
}

/* * ************************************************************* */
/* Add video
  /*************************************************************** */

function hd_add_media($wptfile_abspath, $wp_urlpath) {
	global $wpdb;
    $uploadPath = $wpdb->get_row("SELECT uploads,ffmpeg_path FROM " . $wpdb->prefix . "hdflvvideoshare_settings order by settings_id desc limit 1");
    $uPath = $uploadPath->uploads;
    $pieces = explode(",", $_POST['hid']);
    $video1 = $_POST['normalvideoform-value'];
    $video2 = $_POST['hdvideoform-value'];
    $img1 = $_POST['thumbimageform-value'];
    $img2 = $_POST['previewimageform-value'];
    $img3 = $_POST['customimage'];
    $pre_image = $_POST['custompreimage'];
    if ($uPath != '') {
        $wp_urlpath = $wp_urlpath . $uPath . '/';
    } else {
        $wp_urlpath = $wp_urlpath . '/';
    }


    // Get input informations from POST
    $sorder = $_POST['sorder'];

    $act_name = trim($_POST['name']);
    $act_description = trim($_POST['description']);
    if ($_POST['feature'] != '') {
        $act_feature = $_POST['feature'];
    } else {
        $act_feature = 'OFF';
    }
    if ($_POST['prerollads'] != '') {
        $prerollads = $_POST['prerollads'];
    } else {
        $prerollads = '';
    }
    if ($_POST['postrollads'] != '') {
        $postrollads = $_POST['postrollads'];
    } else {
        $postrollads = '';
    }
    if ($_POST['youtube-value'] != '') {
        $act_filepath = addslashes(trim($_POST['youtube-value']));
        $file_type = '1';
        $ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";
        preg_match($ytb_pattern, stripslashes($act_filepath), $match);
        $youtube_data = hd_GetSingleYoutubeVideo($match[1]);
        $sec = $youtube_data['duration']['SECONDS'];
        $duration = convertTime($sec);
    } else {
        $act_filepath1 = $wp_urlpath . $_REQUEST['normalvideoform-value'];
        $act_filepath 	= addslashes(trim($_POST['customurl']));
        $ffmpeg_path = $uploadPath->ffmpeg_path;
        $file_type = '2';
        ob_start();
        passthru("$ffmpeg_path -i \"" . $act_filepath .$act_filepath1. "\" 2>&1");
        $duration = ob_get_contents();
        ob_end_clean();
        preg_match('/Duration: (.*?),/', $duration, $matches);
        $duration_array = split(':', $matches[1]);
        $sec = ceil($duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2]);

        $duration = convertTime($sec);
    }
    $act_filepath2 = trim($_POST['customhd']);
    $act_image = addslashes(trim($_POST['urlimage']));
    $act_link = '';
    $act_playlist = $_POST['playlist'];
    $act_tags = addslashes(trim($_POST['act_tags']));
    $act_download = addslashes(trim($_POST['download']));
    if (!empty($act_filepath)) {
        $ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";
        if (preg_match($ytb_pattern, stripslashes($act_filepath), $match)) {
            $youtube_data = hd_GetSingleYoutubeVideo($match[1]);
            if ($youtube_data) {
                if ($act_name == '')
                    $act_name = addslashes($youtube_data['title']);
                if ($act_image == '')
                    $act_image = "http://img.youtube.com/vi/" . $youtube_data['id'] . "/0.jpg";
                if ($act_link == '')
                    $act_link = $act_filepath;
                $act_filepath = preg_replace('/^(http)s?:\/+/i', '', $act_filepath);
                 parse_str( parse_url( $act_filepath, PHP_URL_QUERY ), $youtubeID );
                 $act_image = "http://img.youtube.com/vi/" .$youtubeID['v']. "/1.jpg";
                 $act_opimage = "http://img.youtube.com/vi/" .$youtubeID['v']. "/0.jpg";
            } else
                render_error(__('Could not retrieve Youtube video information', 'hdflvvideoshare'));
        }else {
            $act_hdpath = $act_filepath2;
            $act_image = $img3;
            $act_opimage = $pre_image;
        }
    } else {
        if ($video1 != '')
            $act_filepath = $wp_urlpath . "$video1";
        if ($video2 != '')
            $act_hdpath = $wp_urlpath . "$video2";
        if ($img1 != '')
            $act_image = $wp_urlpath . "$img1";
        if ($img2 != '')
            $act_opimage = $wp_urlpath . "$img2";
    }
    if ($_POST['tag_name'] != '') {
        $tag_name = $_POST['tag_name'];
        $seo_tag = preg_replace('/[&:\s]+/i', '-', $tag_name);
    }
    $now = date("Y-m-d H:i:s", time());
    
    $getLastVideoData = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE `vid` = (SELECT MAX(vid) FROM " . $wpdb->prefix. "hdflvvideoshare) ");
    $videoName = $getLastVideoData->name;
    $file = $getLastVideoData->file;
    $image = $getLastVideoData->image;
    $link = $getLastVideoData->link;
    //SELECT * FROM `wp_hdflvvideoshare` WHERE `vid` = (SELECT MAX(vid) FROM `wp_hdflvvideoshare`)
    if(($act_name != $videoName) && ($file != $act_filepath) && ($image != $act_image) && ($link != $act_link))
    {
   $insert_video = $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare ( name,description,file, file_type,duration, hdfile, image, opimage , download, link, featured, hitcount, post_date,prerollads,postrollads)
   VALUES ( '$act_name','$act_description','$act_filepath','$file_type','$duration','$act_hdpath', '$act_image', '$act_opimage', '$act_download', '$act_link' ,'$act_feature','0','$now','$prerollads','$postrollads')") or die('not inserting');
    }

    if ($insert_video != 0) {
        $video_aid = $wpdb->insert_id;  // get index_id
        $insert_tlist = mysql_query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_tags (tags_name,seo_name,media_id)
                                                                                 VALUES ('$tag_name','$seo_tag','$video_aid')");

        //wp_set_object_terms($video_aid, $tags, WORDTUBE_TAXONOMY);
        render_message(__('Media file', 'hdflvvideoshare') . ' ' . substr($act_name,0,10) . __(' added successfully', 'hdflvvideoshare'));
    }


    // Add any link to playlist?
    if ($video_aid && is_array($act_playlist)) {
        $add_list = array_diff($act_playlist, array());

        if ($add_list) {
            foreach ($add_list as $new_list) {
                $new_list1 = $new_list - 1;
                if ($sorder[$new_list1] == '')
                    $sorder[$new_list1] = '0';
                $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_med2play (media_id,playlist_id,sorder) VALUES ($video_aid, $new_list, $sorder[$new_list1])");
            }
        }

        $i = 0;
        foreach ($pieces as $new_list) {
            $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '$sorder[$i]' WHERE media_id = '$video_aid' and playlist_id = '$new_list'");

            $i++;
        }
    }


    $i = 0;
    foreach ($pieces as $new_list) {
        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '$sorder[$i]' WHERE media_id = '$video_aid' and playlist_id = '$new_list'");
        $i++;
    }

    return;
}

//calling via Ajax to add playlist
function hd_ajax_add_playlist($name, $media) {

    global $wpdb;

    // Get input informations from POST
    $p_name = addslashes(trim($name));
    $p_description = '';
    $p_playlistorder = 0;
    if (empty($p_playlistorder))
        $p_playlistorder = "ASC";

    $playlistname1 = "select playlist_name from " . $wpdb->prefix . "hdflvvideoshare_playlist where playlist_name='" . $p_name . "'";
    $planame1 = mysql_query($playlistname1);
    if (mysql_fetch_array($planame1, MYSQL_NUM)) {
        render_error(__('Failed, Playlist name already exist', 'hdflvvideoshare')) . get_playlist_for_dbx($media);
        return;
    }

    // Add playlist in db
    if (!empty($p_name)) {
        $insert_plist = mysql_query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_playlist (playlist_name, playlist_desc, playlist_order) VALUES ('$p_name', '$p_description', '$p_playlistorder')");
        if ($insert_plist != 0) {
            $pid = $wpdb->insert_id;  // get index_id
            render_message(__('Playlist', 'hdflvvideoshare') . ' ' . substr($name,0,10) . __(' added successfully', 'hdflvvideoshare')) . get_playlist_for_dbx($media);
        }
    }

    return;
}

function hd_ajax_add_tags($tagsname, $media) {

    global $wpdb;

    // Get input informations from POST
    $tags_name = addslashes(trim($tagsname));


    $tagsname1 = "select tags_name from " . $wpdb->prefix . "hdflvvideoshare_tags where tags_name='" . $tags_name . "' and media_id='" . $media . "'";
    $tgname1 = mysql_query($tagsname1);
    if (mysql_fetch_array($tgname1, MYSQL_NUM)) {
        render_error(__('Failed, Tags name already exist', 'hdflvvideoshare')) . get_vidtags_dbx($media);
        return;
    }


    return;
}

function youtubeurl() {
    $act_filepath = addslashes(trim($_POST['filepath']));
    if (!empty($act_filepath)) {
        $ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";
        if (preg_match($ytb_pattern, stripslashes($act_filepath), $match)) {
            //print_r($match);
            $youtube_data = hd_GetSingleYoutubeVideo($match[1]);
            if ($youtube_data) {
                $act[0] = addslashes($youtube_data['title']);
                $act[3] = $youtube_data['thumbnail_url'];
                $act[4] = $act_filepath;
                $act[5] = addslashes($youtube_data['description']);
                $act[6] = addslashes($youtube_data['tags']);
            } else
                render_error(__('Could not retrieve Youtube video information', 'hdflvvideoshare'));
        }else {
            $act[4] = $act_filepath;
            render_error(__('URL entered is not a valid Youtube Url', 'hdflvvideoshare'));
        }
        return $act;
    }
}

/**
 * hd_update_media() - Call from Manage screen update update the media data
 *
 * @param int $media_id
 * @return void
 */
function hd_update_media($media_id,$videourlmyfile,$hdurlmyfile,$thumurlmyfile,$preimgurlmyfile,$linkurlmyfile) {
    global $wpdb;
    //echo  $media_id;
    // read the $_POST values
    $pieces = explode(",", $_POST['hid']);
    //print_r($pieces);
    $sorder = $_POST['sorder'];
 $act_name = addslashes(trim($_POST['act_name']));
 
    $act_description = addslashes(trim($_POST['act_description']));
    $act_filepath = addslashes(trim($_POST['act_filepath']));
    $act_image = addslashes(trim($_POST['act_image']));
    $act_hdpath = addslashes(trim($_POST['act_hdpath']));
  $act_link = addslashes(trim($_POST['act_link'])); 
    $act_download = addslashes(trim($_POST['download']));
    $act_opimg = addslashes(trim($_POST['act_opimg']));
    $act_prerollads = addslashes(trim($_POST['act_prerollads']));
    $act_postrollads = addslashes(trim($_POST['act_postrollads']));
    $act_playlist = $_POST['playlist'];
    if ($_POST['feature'] != '') {
        $act_feature = $_POST['feature'];
    } else {
        $act_feature = 'OFF';
    }

    // Update tags
    $act_tags = addslashes(trim($_POST['act_tags']));
    $tags = explode(',', $act_tags);
    //wp_set_object_terms( $media_id, $tags, WORDTUBE_TAXONOMY);

    if (!$act_playlist)
        $act_playlist = array();
    if (empty($act_autostart))
        $act_autostart = 0; // need now for sql_mode, see http://bugs.mysql.com/bug.php?id=18551
    // Read the old playlist status
    $old_playlist = $wpdb->get_col(" SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id = $media_id");
    if (!$old_playlist) {
        $old_playlist = array();
    } else {
        $old_playlist = array_unique($old_playlist);
    }

    // Delete any ?
    $delete_list = array_diff($old_playlist, $act_playlist);
    //print_r($delete_list);
    if ($delete_list) {
        foreach ($delete_list as $del) {
            $wpdb->query(" DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE playlist_id = $del AND media_id = $media_id ");
        }
    }

    // Add any?

    $add_list = array_diff($act_playlist, $old_playlist);


    if ($add_list) {
        foreach ($add_list as $new_list) {
            $new_list1 = $new_list - 1;
            if ($sorder[$new_list1] == '')
                $sorder[$new_list1] = '0';
            $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_med2play (media_id, playlist_id,sorder) VALUES ($media_id, $new_list, $sorder[$new_list1])");
        }
    }

    $i = 0;
    foreach ($pieces as $new_list) {
        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '$sorder[$i]' WHERE media_id = '$media_id' and playlist_id = '$new_list'");
        $i++;
    }

    if (empty($act_autostart))
        $act_autostart = 0; // need now for sql_mode, see http://bugs.mysql.com/bug.php?id=18551
    //print_r($sorder);
    $i = 0;
    foreach ($pieces as $new_list) {
        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '$sorder[$i]' WHERE media_id = '$media_id' and playlist_id = '$new_list'");
        $i++;
    }
// End of Tags
//    if ($_POST['tag_name'] != '') {
        $tag_name = $_POST['tag_name'];
        $seo_tag = preg_replace('/[&:\s]+/i', '-', $tag_name);
        $wpdb->query("UPDATE " . $wpdb->prefix . "hdflvvideoshare_tags SET tags_name='$tag_name',seo_name='$seo_tag' WHERE media_id='$media_id'");
//    }


    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    

    if (!empty($act_filepath)) {
    $siteurl = get_bloginfo('url');
    $act_filepath = $siteurl."/wp-content/plugins/".$dirPage."/hdflvplayer/images//".$videourlmyfile[name];
    if ($videourlmyfile['name']) {
     $act_filepath = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $videourlmyfile[name];
    } else {
    $act_filepath = addslashes(trim($_POST['act_filepath']));
    }

    $act_hdpath  = $siteurl."/wp-content/plugins/".$dirPage."/hdflvplayer/images//".$hdurlmyfile[name];
    if ($hdurlmyfile['name']) {
    $act_hdpath = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $hdurlmyfile[name];
    } else {
    $act_hdpath = addslashes(trim($_POST['act_hdpath']));
    }

    $act_image = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $thumurlmyfile[name];
    if ($thumurlmyfile['name']) {
    $act_image = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $thumurlmyfile[name];
    } else {
    $act_image = addslashes(trim($_POST['act_image']));
    }


    $act_opimg  = $siteurl."/wp-content/plugins/".$dirPage."/hdflvplayer/images//".$preimgurlmyfile[name];
    if ($preimgurlmyfile['name']) {
    $act_opimg = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $preimgurlmyfile[name];
    } else {
    $act_opimg = addslashes(trim($_POST['act_opimg']));
    }

//    $act_link  = $siteurl."/wp-content/plugins/".$dirPage."/hdflvplayer/images//".$linkurlmyfile[name];
//    if ($linkurlmyfile['name']) {
//    $act_link = $siteurl . "/wp-content/plugins/".$dirPage."/hdflvplayer/images//" . $linkurlmyfile[name];
//    } else {
//    $act_link = addslashes(trim($_POST['act_link']));
//    }
       $act_link = addslashes(trim($_POST['act_link']));
        $result = $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare SET postrollads = '$act_postrollads' , prerollads = '$act_prerollads' , name = '$act_name',description='$act_description',file='$act_filepath' ,hdfile='$act_hdpath' , image='$act_image' , opimage='$act_opimg' , download='$act_download', link='$act_link', featured='$act_feature'  WHERE vid = '$media_id' ");
        move_uploaded_file($_FILES["videourlmyfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["videourlmyfile"]["name"]);
        move_uploaded_file($_FILES["hdurlmyfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["hdurlmyfile"]["name"]);
        move_uploaded_file($_FILES["thumurlmyfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["thumurlmyfile"]["name"]);
        move_uploaded_file($_FILES["preimgurlmyfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["preimgurlmyfile"]["name"]);
       // move_uploaded_file($_FILES["linkurlmyfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["linkurlmyfile"]["name"]);
      
    }

    // Finished

    render_message(__('Update Successfully', 'hdflvvideoshare'));
    return;
}

/* * ************************************************************* */
/* Delete media
  /*************************************************************** */

function hd_delete_media($act_vid, $deletefile) {
    global $wpdb;

    // Delete file
    if ($deletefile) {

        $act_videoset = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid = $act_vid ");

        $act_filename = wpt_filename($act_videoset->file);
        $abs_filename = str_replace(trailingslashit(get_option('siteurl')), ABSPATH, trim($act_videoset->file));
        if (!empty($act_filename)) {

            $wpt_checkdel = @unlink($abs_filename);
            if (!$wpt_checkdel)
                render_error(__('Error in deleting file', 'hdflvvideoshare'));
        }

        $act_filename = wpt_filename($act_videoset->image);
        $abs_filename = str_replace(trailingslashit(get_option('siteurl')), ABSPATH, trim($act_videoset->image));
        if (!empty($act_filename)) {

            $wpt_checkdel = @unlink($abs_filename);
            if (!$wpt_checkdel)
                render_error(__('Error in deleting file', 'hdflvvideoshare'));
        }
    }

    //TODO: The problem of this routine : if somebody change the path, after he uploaded some files

    $wpdb->query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id = $act_vid");

    $delete_video = $wpdb->query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid = $act_vid");
    // Delete tag relationships
    //wp_delete_object_term_relationships($act_vid, WORDTUBE_TAXONOMY);

    if (!$delete_video)
        render_error(__('Error in deleting media file', 'hdflvvideoshare'));

    if (empty($text))
        render_message(__('Media file', 'hdflvvideoshare') . ' ' . __('deleted successfully', 'hdflvvideoshare'));

    return;
}

function convertTime($sec) {
    $hms = "";
    $hours = intval(intval($sec) / 3600);
    $hms .= ( $padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" : $hours . ":";
    if ($hms == '0:'

        )$hms = '';
    $minutes = intval(($sec / 60) % 60);
    $hms .= str_pad($minutes, 1, "0", STR_PAD_LEFT) . ":";
    $seconds = intval($sec % 60);
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
    // done!
    return $hms;
}

/* * ************************************************************* */
/* Add Playlist
  /*************************************************************** */

function hd_add_playlist() {
    global $wpdb;

    // Get input informations from POST
    $p_name = addslashes(trim($_POST['p_name']));
    $p_description = addslashes(trim($_POST['p_description']));
    $p_playlistorder = $_POST['sortorder'];
    if (empty($p_playlistorder))
        $p_playlistorder = "ASC";

    $playlistname1 = "select playlist_name from " . $wpdb->prefix . "hdflvvideoshare_playlist where playlist_name='" . $p_name . "'";
    $planame1 = mysql_query($playlistname1);
    if (mysql_fetch_array($planame1, MYSQL_NUM)) {
        render_error(__('Failed, Playlist name already exist', 'hdflvvideoshare'));
        return;
    }

    // Add playlist in db
    if (!empty($p_name)) {
        $insert_plist = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_playlist (playlist_name, playlist_desc, playlist_order) VALUES ('$p_name', '$p_description', '$p_playlistorder')");
        if ($insert_plist != 0) {
            $pid = $wpdb->insert_id;  // get index_id
            render_message(__('Playlist', 'hdflvvideoshare') . ' '.substr($p_name,0,10) . __(' added successfully', 'hdflvvideoshare'));
        }
    }

    return;
}

/* * ************************************************************* */
/* Update Playlist
  /*************************************************************** */

function hd_update_playlist() {
    global $wpdb;

    // Get input informations from POST
    $p_id = (int) ($_POST['p_id']);
    $p_name = addslashes(trim($_POST['p_name']));
    $p_description = addslashes(trim($_POST['p_description']));
    $p_playlistorder = $_POST['sortorder'];

    if (!empty($p_name)) {
        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_playlist SET playlist_name = '$p_name', playlist_desc = '$p_description', playlist_order = '$p_playlistorder' WHERE pid = '$p_id' ");
        render_message(__('Update Successfully', 'hdflvvideoshare'));
    }

    return;
}

function render_message($message, $timeout = 0) {
?>
    <div class="wrap"><h2>&nbsp;</h2>
        <div class="fade updated" id="message" onclick="this.parentNode.removeChild (this)">
            <p><strong><?php echo $message ?></strong></p>
        </div></div>
<?php
}

/* * ************************************************************* */
/* Delete Playlist
  /*************************************************************** */

function hd_delete_playlist($act_pid) {
    global $wpdb;
    $delete_plist = $wpdb->query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = $act_pid");
    $delete_plist2 = $wpdb->query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE playlist_id = $act_pid");
//    if ($delete_plist && $delete_plist2) {
//        render_message(__('Playlist', 'hdflvvideoshare') . ' ' . __('deleted successfully', 'hdflvvideoshare'));
//    }

     if(empty($text) ) {
		render_message( __('Playlist','hdflvvideoshare').'  '.__('deleted successfully','hdflvvideoshare'));
	}

    return;
}

/* * ************************************************************* */
/* Return Youtube single video
  /*************************************************************** */

function hd_GetSingleYoutubeVideo($youtube_media) {
    if ($youtube_media == '')
        return;
    $url = 'http://gdata.youtube.com/feeds/api/videos/' . $youtube_media;
    $ytb = hd_ParseYoutubeDetails(hd_GetYoutubePage($url));
    return $ytb[0];
}

/* * ************************************************************* */
/* Parse xml from Youtube
  /*************************************************************** */

function hd_ParseYoutubeDetails($ytVideoXML) {

    // Create parser, fill it with xml then delete it
    $yt_xml_parser = xml_parser_create();
    xml_parse_into_struct($yt_xml_parser, $ytVideoXML, $yt_vals);
    xml_parser_free($yt_xml_parser);
    // Init individual entry array and list array
    $yt_video = array();
    $yt_vidlist = array();

    // is_entry tests if an entry is processing
    $is_entry = true;
    // is_author tests if an author tag is processing
    $is_author = false;
    foreach ($yt_vals as $yt_elem) {

        // If no entry is being processed and tag is not start of entry, skip tag
        if (!$is_entry && $yt_elem['tag'] != 'ENTRY')
            continue;

        // Processed tag
        switch ($yt_elem['tag']) {
            case 'ENTRY' :
                if ($yt_elem['type'] == 'open') {
                    $is_entry = true;
                    $yt_video = array();
                } else {
                    $yt_vidlist[] = $yt_video;
                    $is_entry = false;
                }
                break;
            case 'ID' :
                $yt_video['id'] = substr($yt_elem['value'], -11);
                $yt_video['link'] = $yt_elem['value'];
                break;
            case 'PUBLISHED' :
                $yt_video['published'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);
                break;
            case 'UPDATED' :
                $yt_video['updated'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);
                break;
            case 'MEDIA:TITLE' :
                $yt_video['title'] = $yt_elem['value'];
                break;
            case 'MEDIA:KEYWORDS' :
                $yt_video['tags'] = $yt_elem['value'];
                break;
            case 'MEDIA:DESCRIPTION' :
                $yt_video['description'] = $yt_elem['value'];
                break;
            case 'MEDIA:CATEGORY' :
                $yt_video['category'] = $yt_elem['value'];
                break;
            case 'YT:DURATION' :
                $yt_video['duration'] = $yt_elem['attributes'];
                break;
            case 'MEDIA:THUMBNAIL' :
                if ($yt_elem['attributes']['HEIGHT'] == 240) {
                    $yt_video['thumbnail'] = $yt_elem['attributes'];
                    $yt_video['thumbnail_url'] = $yt_elem['attributes']['URL'];
                }
                break;
            case 'YT:STATISTICS' :
                $yt_video['viewed'] = $yt_elem['attributes']['VIEWCOUNT'];
                break;
            case 'GD:RATING' :
                $yt_video['rating'] = $yt_elem['attributes'];
                break;
            case 'AUTHOR' :
                $is_author = ($yt_elem['type'] == 'open');
                break;
            case 'NAME' :
                if ($is_author)
                    $yt_video['author_name'] = $yt_elem['value'];
                break;
            case 'URI' :
                if ($is_author)
                    $yt_video['author_uri'] = $yt_elem['value'];
                break;
            default :
        }
    }

    unset($yt_vals);

    return $yt_vidlist;
}

/* * ************************************************************* */
/* Returns content of a remote page
  /* Still need to do it without curl
  /*************************************************************** */

function hd_GetYoutubePage($url) {

    // Try to use curl first
    if (function_exists('curl_init')) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xml = curl_exec($ch);
        curl_close($ch);
    }
    // If not found, try to use file_get_contents (requires php > 4.3.0 and allow_url_fopen)
    else {
        $xml = @file_get_contents($url);
    }

    return $xml;
}

//Function used for adding videosads
function hd_add_ads($wptfile_abspath, $wp_urlpath) {

    global $wpdb;
    $uploadPath = $wpdb->get_col("SELECT uploads FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $uPath = $uploadPath[0];
    $pieces = explode(",", $_POST['hid']);
    $adsVideo = $_POST['adsform-value'];

    if ($uPath != '') {

        $wp_urlpath = $wp_urlpath . $uPath . '/';
    } else {
        $wp_urlpath = $wp_urlpath . '/';
    }





// Get input informations from POST
    // $sorder = $_POST['sorder'];

    $ads_name = trim($_POST['name']);

    if ($_POST['youtube-value'] != '') {

        $ads_filepath = addslashes(trim($_POST['youtube-value']));
    }

    $ads_link = '';


    if (!empty($ads_filepath)) {
        $ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";
        if (preg_match($ytb_pattern, stripslashes($ads_filepath), $match)) {
            //print_r($match);
            $youtube_data = hd_GetSingleYoutubeVideo($match[1]);
            if ($youtube_data) {
                if ($ads_name == '')
                    $ads_name = addslashes($youtube_data['title']);
                if ($ads_link == '')
                    $ads_link = $ads_filepath;
                $ads_filepath = preg_replace('/^(http)s?:\/+/i', '', $ads_filepath);



            } else
                render_error(__('Could not retrieve Youtube video information', 'hdflv'));
        }else {
            $ads_filepath = $_POST['youtube-value'];
        }
    } else {
        if ($adsVideo != '')
            $ads_filepath = $wp_urlpath . "$adsVideo";
    }
    $insert_video = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_vgads ( ads_id,  file_path , title )
	VALUES ( '', '$ads_filepath', '$ads_name' )");


    if ($insert_video != 0) {
        $video_aid = $wpdb->insert_id;  // get index_id
        $tags = explode(',', $act_tags);

        render_message(__('Media file', 'ads') . ' ' . substr($ads_name,0,10) . __(' added successfully', 'ads'));
    }

    return;
}

//Function used for updating media data(File path,name,etc..)
function hd_update_ads($ads_id,$editfilepath) {
    global $wpdb;

    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $pieces = explode(",", $_POST['hid']);
    $ads_name = addslashes(trim($_POST['ads_name']));
      $ads_filepath = addslashes(trim($_POST['ads_filepath']));
    $siteurl = get_bloginfo('url');
      if (!empty($ads_filepath)) {

    if($editfilepath[name]){
    $ads_filepath = $siteurl."/wp-content/plugins/".$dirPage."/hdflvplayer/images//".$editfilepath[name];}
else
{
     $ads_filepath = addslashes(trim($_POST['ads_filepath']));
}
        $result = $wpdb->query("UPDATE " . $wpdb->prefix . "hdflvvideoshare_vgads SET title = '$ads_name',  file_path='$ads_filepath'  WHERE ads_id = '$ads_id' ");
   move_uploaded_file($_FILES["myfile"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["myfile"]["name"]);
    render_message(__('Update Successfully', 'ads'));
    return;
}}

//Function used for deleting ads(video)
function hd_delete_ads($act_vid, $deletefile) {



    global $wpdb;



    $act_videoset = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads WHERE ads_id = $act_vid ");

    $act_filename = wpt_filename($act_videoset->file_path);
    $abs_filename = str_replace(trailingslashit(get_option('siteurl')), ABSPATH, trim($act_videoset->file_path));
    if (!empty($act_filename)) {

        $wpt_checkdel = @unlink($abs_filename);
        if (!$wpt_checkdel)
            render_error(__('Error in deleting file', 'ads'));
    }


    $delete_video = $wpdb->query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_vgads WHERE ads_id = $act_vid");
    if (!$delete_video)
        render_error(__('Error in deleting media file', 'ads'));

    if (empty($text))
        render_message(__('Media file', 'ads') . ' ' . __('deleted successfully', 'hdflv'));

    return;
}
?>
