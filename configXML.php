<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: ConfigXML file for player.
  Version: 2.2
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

ob_clean();
header("cache-control: private");
header("Pragma: public");
header("Content-type: application/xml");
header("content-type:text/xml;charset=utf-8");

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
global $site_url;

$contusOBJ              = new ContusVideoView();
$settingsData           = $contusOBJ->_settingsData;
$mId                    = filter_input(INPUT_GET, 'mid');
$logoPath               = str_replace('plugins/contus-video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);

$playXml                = APPTHA_VGALLERY_BASEURL . 'myextractXML.php';
$midrollXml             = APPTHA_VGALLERY_BASEURL . 'mymidrollXML.php';
$imaAdsXML              = APPTHA_VGALLERY_BASEURL . 'myimaadsXML.php';


$langXML                = APPTHA_VGALLERY_BASEURL . 'languageXML.php';
$emailPath              = APPTHA_VGALLERY_BASEURL . 'hdflvplayer/email.php';
$downloadPath           = APPTHA_VGALLERY_BASEURL . 'hdflvplayer/download.php';
$adsXml                 = APPTHA_VGALLERY_BASEURL . 'myadsXML.php';
$skinPath               = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'skin/skin_hdflv_white.swf';
$playerTimer            = $settingsData->timer == 1 ? 'true' : 'false';
$adsSkip                = $settingsData->adsSkip == 0 ? 'true' : 'false';
$showTag                = $settingsData->showTag == 1 ? 'true' : 'false';
$imageDefault           = $settingsData->imageDefault == 1 ? 'true' : 'false';
$progressControl        = $settingsData->progressControl == 1 ? 'true' : 'false';
$volumecontrol          = $settingsData->volumecontrol == 1 ? 'true' : 'false';
$shareIcon              = $settingsData->shareIcon == 1 ? 'true' : 'false';
$imaAds                 = $settingsData->imaAds == 0 ? 'true' : 'false';
$playerZoom             = $settingsData->zoom == 1 ? 'true' : 'false';
$playerEmail            = $settingsData->email ? 'true' : 'false';
$playerFullscreen       = $settingsData->fullscreen == 1 ? 'true' : 'false';
$playerAutoplay         = ($settingsData->autoplay == 1) ? 'true' : 'false';
$playlistAuto           = ($settingsData->playlistauto == 1) ? 'true' : 'false';
$hdDefault              = ($settingsData->HD_default == 1) ? 'true' : 'false';
$playerDownload         = ($settingsData->download == 1) ? 'true' : 'false';
$skinAutohide           = ($settingsData->skin_autohide == 1) ? 'true' : 'false';
$embedVisible           = ($settingsData->embed_visible == 1) ? 'true' : 'false';
$showPlaylist           = ($settingsData->playlist == 1) ? 'true' : 'false';
$playlist_open          = ($settingsData->playlist_open == 1) ? 'true' : 'false';
$playerDebug            = ($settingsData->debug == 1) ? 'true' : 'false';
$prerollAds             = ($settingsData->preroll == 0) ? 'true' : 'false';
$postrollAds            = ($settingsData->postroll == 0) ? 'true' : 'false';
$midroll_ads            = ($settingsData->midroll_ads == 0) ? 'true' : 'false';
$youtube_hide_logo      = ($settingsData->hideLogo == 1) ? 'true' : 'false';
$trackCode              = ($settingsData->trackCode == 0) ? '' : $settingsData->trackCode;

/* Configuration Start */
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<config>
        <stagecolor>' . $settingsData->stagecolor . '</stagecolor>
        <autoplay>' . $playerAutoplay . '</autoplay>
        <buffer>' . $settingsData->buffer . '</buffer>
        <volume>' . $settingsData->volume . '</volume>
        <normalscale>' . $settingsData->normalscale . '</normalscale>
        <fullscreenscale>' . $settingsData->fullscreenscale . '</fullscreenscale>
        <license>' . $settingsData->license . '</license>
        <logopath>' . $logoPath . $settingsData->logopath . '</logopath>
        <logoalpha>' . $settingsData->logoalpha . '</logoalpha>
        <logoalign>' . $settingsData->logoalign . '</logoalign>
        <logo_target>' . $settingsData->logo_target . '</logo_target>
        <sharepanel_up_BgColor>' . $settingsData->sharepanel_up_BgColor . '</sharepanel_up_BgColor>
        <sharepanel_down_BgColor>' . $settingsData->sharepanel_down_BgColor . '</sharepanel_down_BgColor>
        <sharepaneltextColor>' . $settingsData->sharepaneltextColor . '</sharepaneltextColor>
        <sendButtonColor>' . $settingsData->sendButtonColor . '</sendButtonColor>
        <sendButtonTextColor>' . $settingsData->sendButtonTextColor . '</sendButtonTextColor>
        <textColor>' . $settingsData->textColor . '</textColor>
        <skinBgColor>' . $settingsData->skinBgColor . '</skinBgColor>
        <seek_barColor>' . $settingsData->seek_barColor . '</seek_barColor>
        <buffer_barColor>' . $settingsData->buffer_barColor . '</buffer_barColor>
        <skinIconColor>' . $settingsData->skinIconColor . '</skinIconColor>
        <pro_BgColor>' . $settingsData->pro_BgColor . '</pro_BgColor>
        <playButtonColor>' . $settingsData->playButtonColor . '</playButtonColor>
        <playButtonBgColor>' . $settingsData->playButtonBgColor . '</playButtonBgColor>
        <playerButtonColor>' . $settingsData->playerButtonColor . '</playerButtonColor>
        <playerButtonBgColor>' . $settingsData->playerButtonBgColor . '</playerButtonBgColor>
        <relatedVideoBgColor>' . $settingsData->relatedVideoBgColor . '</relatedVideoBgColor>
        <scroll_barColor>' . $settingsData->scroll_barColor . '</scroll_barColor>
        <scroll_BgColor>' . $settingsData->scroll_BgColor . '</scroll_BgColor>
        <skin>' . $skinPath . '</skin>
        <skin_autohide>' . $skinAutohide . '</skin_autohide>
        <languageXML>' . $langXML . '</languageXML>
        <playlistXML>' . $playXml . '</playlistXML>
        <playlist_open>' . $playlist_open . '</playlist_open>
        <showPlaylist>' . $showPlaylist . '</showPlaylist>
        <HD_default>' . $hdDefault . '</HD_default>
        <adXML>' . $adsXml . '</adXML>
        <preroll_ads>' . $prerollAds . '</preroll_ads>
        <postroll_ads>' . $postrollAds . '</postroll_ads>
        <midrollXML>' . $midrollXml . '</midrollXML>
        <midroll_ads>' . $midroll_ads . '</midroll_ads>
        <shareURL>' . $emailPath . '</shareURL>
        <embed_visible>' . $embedVisible . '</embed_visible>
        <Download>' . $playerDownload . '</Download>
        <downloadUrl>' . $downloadPath . '</downloadUrl>
        <adsSkip>' . $adsSkip . '</adsSkip>
        <adsSkipDuration>' . $settingsData->adsSkipDuration . '</adsSkipDuration>
        <relatedVideoView>' . $settingsData->relatedVideoView . '</relatedVideoView>
        <imaAds>' . $imaAds . '</imaAds>
        <imaAdsXML>' . $imaAdsXML . '</imaAdsXML>
        <trackCode>' . $trackCode . '</trackCode>
        <showTag>' . $showTag . '</showTag>
        <timer>' . $playerTimer . '</timer>
        <zoomIcon>' . $playerZoom . '</zoomIcon>
        <email>' . $playerEmail . '</email>
        <shareIcon>' . $shareIcon . '</shareIcon>
        <fullscreen>' . $playerFullscreen . '</fullscreen>
        <volumecontrol>' . $volumecontrol . '</volumecontrol>
        <playlist_auto>' . $playlistAuto . '</playlist_auto>
        <progressControl>' . $progressControl . '</progressControl>
        <imageDefault>' . $imageDefault . '</imageDefault>
    </config>';
// Configuration ends 
?>