<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: playlistxml file for player.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

// look up for the path
require_once( dirname(__FILE__) . '/hdflv-config.php');
$pageOBJ = new ContusVideoView();
$contOBJ = new ContusVideoController();
$getVid = $pageOBJ->_vId;
$getPid = $pageOBJ->_pId;
if (!empty($getVid)) { 
    $singleVideodata = $contOBJ->video_detail($getVid);
}else if (!empty($getPid)) { 
    $singleVideodata = $contOBJ->video_Pid_detail($getPid);
} else { 
    $singleVideodata = $pageOBJ->_featuredvideodata;
}

$settingsContent = $pageOBJ->settings_data();
$tagsName = $pageOBJ->Tag_detail($getVid);


$videothum = $islive = $streamer = '';
$videoPreview = '';
$videotag = '';
$postroll_id = '';
$pageOBJ->_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
if ($settingsContent->autoplay == 1) {
    $ap = 'true';
} else {
    $ap = 'false';
}
$image_path = str_replace('plugins/contus-video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
header("content-type:text/xml;charset = utf-8");
echo '<?xml version = "1.0" encoding = "utf-8"?>';
echo "<playlist autoplay = '$ap' random = 'false'>";
foreach ($singleVideodata as $media) {
    $file_type = $media->file_type;
    $videoUrl = $media->file;
    $views = $media->hitcount;
    $fbPath = $media->guid;
    $hdvideoUrl = $media->hdfile;
    $opimage = $media->opimage;
    $image = $media->image;
    $vidoeId = $media->vid;
    if ($image == '') {
        $image = $pageOBJ->_imagePath . 'nothumbimage.jpg';
    } else {
        if ($file_type == 2) {
            $image = $image_path . $image;
        }
    }
    if ($opimage == '') {
        $opimage = $pageOBJ->_imagePath . 'noimage.jpg';
    } else {
        if ($file_type == 2) {
            $opimage = $image_path . $opimage;
        }
    }
    if ($videoUrl != '') {
        
        if ($file_type == 2) {
            $videoUrl = $image_path . $videoUrl;
        }
        }
    if ($hdvideoUrl != '') {

        if ($file_type == 2) {
            $hdvideoUrl = $image_path . $hdvideoUrl;
        }
        }

        if ($file_type == 4) {
        $streamer=$media->streamer_path;
        $islive = ($media->islive == 1) ? 'true' : 'false';
        }
    if ($media->hdfile != '') {
        $hd = 'true';
    } else {
        $hd = 'false';
    }
    if ($settingsContent->preroll == 1) {
        $preroll = ' preroll = "false"';
        $preroll_id = ' preroll_id = "0"';
    } else {
        $preroll = ' preroll = "true"';
        $preroll_id = ' preroll_id = "' . $media->prerollads . '"';
    }

    if ($settingsContent->postroll == 1) {
        $postroll = ' postroll = "false"';
        $postroll_id = ' postroll_id = "0"';
    } else {
        $postroll = ' postroll = "true"';
        $postroll_id = ' postroll_id = "' . $media->postrollads . '"';
    }
$individualdownload=$media->download;
    if ($individualdownload[0]==1)
    {
        $download = 'true';
    } else
    {
        $download = 'false';
    }



// Create XML output of playlist

    echo '<mainvideo views="' . $views . '"  streamer="' . $streamer . '" isLive="' . $islive . '" id = "' . htmlspecialchars($vidoeId) . '" fbpath = "' . $fbPath . '" url = "' . htmlspecialchars($videoUrl) . '" thu_image = "' . htmlspecialchars($image) . '" Preview = "' . htmlspecialchars($opimage) . '" Tag =  "' . $tagsName . '"'. $postroll_id.$preroll_id. $postroll. $preroll. ' hd = "' . $hd . '" allow_download = "' . $download . '" hdpath = "' . $hdvideoUrl . '"  copylink = "' . $media->link . '"> <title><![CDATA[' . htmlspecialchars($media->name) . ']]></title>  <description><![CDATA[' . htmlspecialchars($media->description) . ']]></description> '. htmlspecialchars($media->name). '</mainvideo>';
}
echo '</playlist>';
?>