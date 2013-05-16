<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: ConfigXML file for player.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

ob_clean();
header("cache-control: private");
header ("Pragma: public");
header("Content-type: application/xml");
header("content-type:text/xml;charset=utf-8");

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
global $site_url;

$contusOBJ = new ContusVideoView();
$settingsData = $contusOBJ->_settingsData;
$mId = filter_input(INPUT_GET, 'mid');

$logoPath = APPTHA_VGALLERY_BASEURL .'images/';
//if(empty($mId))
    $playXml = APPTHA_VGALLERY_BASEURL .'myextractXML.php';
//else
//$playXml = APPTHA_VGALLERY_BASEURL .'myextractXML.php&mid='.$mId;

$langXML =  APPTHA_VGALLERY_BASEURL .'languageXML.php';
$emailPath = APPTHA_VGALLERY_BASEURL.'hdplayer/email.php';
$adsXml = APPTHA_VGALLERY_BASEURL .'myadsXML.php';
$skinPath= APPTHA_VGALLERY_BASEURL .'hdflvplayer'.DS.'skin'.DS.$settingsData->skin .DS. $settingsData->skin . '.swf';
$playerTimer = $settingsData->timer == 1 ? 'true' : 'false';
$playerZoom = $settingsData->zoom == 1 ? 'true' : 'false';
$playerEmail = $settingsData->email ? 'true' : 'false';
$playerFullscreen = $settingsData->fullscreen == 1 ? 'true' : 'false';
$playerAutoplay = ($settingsData->autoplay == 1) ? 'true' : 'false';
$playlistAuto = ($settingsData->playlistauto == 1) ? 'true' : 'false';
$hdDefault = ($settingsData->HD_default == 1) ? 'true' : 'false';
$playerDownload = ($settingsData->download == 1) ? 'true' : 'false';
$skinAutohide = ($settingsData->skin_autohide == 1) ? 'true' : 'false';
$embedVisible = ($settingsData->embed_visible == 1) ? 'true' : 'false';
$showPlaylist = ($settingsData->playlist == 1) ? 'true' : 'false';
$playerDebug = ($settingsData->debug == 1) ? 'true' : 'false';
$prerollAds  = ($settingsData->preroll == 0) ? 'true' : 'false';
$postrollAds = ($settingsData->postroll == 0) ? 'true' : 'false';
$youtube_hide_logo = ($settingsData->hideLogo == 1) ? 'true' : 'false';

/* Configuration Start */
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<config
        buffer="' . $settingsData->buffer . '"
        height="' . $settingsData->height . '"
        width="' . $settingsData->width . '"
        normalscale="' . $settingsData->normalscale . '"
        fullscreenscale="' . $settingsData->fullscreenscale . '"
        languageXML = "' . $langXML . '"
        logopath="' . $logoPath . $settingsData->logopath . '"
        logo_target="'.$settingsData->logo_target.'"
        autoplay  ="' . $playerAutoplay . '"
        playlist_autoplay  ="' . $playlistAuto . '"
        Volume="' . $settingsData->volume . '"
        logoalign="' . $settingsData->logoalign . '"
        HD_default="' . $hdDefault . '"
        Download="' . $playerDownload . '"
        logoalpha = "' . $settingsData->logoalpha . '"
        skin_autohide="' . $skinAutohide . '"
        stagecolor="' . $settingsData->stagecolor . '"
        scaleToHideLogo = "'.$youtube_hide_logo.'"
        shareURL ="'. $emailPath. '"
        skin="' . $skinPath . '"
        embed_visible="' . $embedVisible . '"
        playlistXML="' . $playXml . '"
        preroll_ads="'.$prerollAds.'"
        postroll_ads="'.$postrollAds.'"
        adXML="'.$adsXml.'"
        UseYouTubeApi="flash"
        showPlaylist ="' . $showPlaylist . '"
        license = "'.$settingsData->license.'"
        debug="' . $playerDebug . '"><timer>' . $playerTimer . '</timer>
        <zoom>' . $playerZoom . '</zoom>
        <email>' . $playerEmail . '</email>
        <fullscreen>' . $playerFullscreen . '</fullscreen></config>';
// Configuration ends 
?>