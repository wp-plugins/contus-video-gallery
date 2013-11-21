<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: ConfigXML file for player.
  Version: 2.3.1.0.1
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

$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];

$contusOBJ              = new ContusVideoView();
$settingsData           = $contusOBJ->_settingsData;
$mId                    = filter_input(INPUT_GET, 'mid');
$logoPath               = str_replace('plugins/'.$dirPage.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);

$playXml                = APPTHA_VGALLERY_BASEURL . 'myextractXML.php';
$midrollXml             = APPTHA_VGALLERY_BASEURL . 'mymidrollXML.php';
$imaAdsXML              = APPTHA_VGALLERY_BASEURL . 'myimaadsXML.php';


$langXML                = APPTHA_VGALLERY_BASEURL . 'languageXML.php';
$emailPath              = APPTHA_VGALLERY_BASEURL . 'email.php';
$downloadPath           = APPTHA_VGALLERY_BASEURL . 'download.php';
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
$trackCode              = ($settingsData->trackCode == 0) ? '' : $settingsData->trackCode;
$player_colors          = unserialize($settingsData->player_colors);

$logotarget             = $settingsData->logo_target;
if (!preg_match("~^(?:f|ht)tps?://~i", $logotarget)) {
        $logotarget = "http://" . $logotarget;
    }
    
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
        <logo_target>' . $logotarget . '</logo_target>
        <sharepanel_up_BgColor>' . $player_colors['sharepanel_up_BgColor'] . '</sharepanel_up_BgColor>
        <sharepanel_down_BgColor>' . $player_colors['sharepanel_down_BgColor'] . '</sharepanel_down_BgColor>
        <sharepaneltextColor>' . $player_colors['sharepaneltextColor'] . '</sharepaneltextColor>
        <sendButtonColor>' . $player_colors['sendButtonColor'] . '</sendButtonColor>
        <sendButtonTextColor>' . $player_colors['sendButtonTextColor'] . '</sendButtonTextColor>
        <textColor>' . $player_colors['textColor'] . '</textColor>
        <skinBgColor>' . $player_colors['skinBgColor'] . '</skinBgColor>
        <seek_barColor>' . $player_colors['seek_barColor'] . '</seek_barColor>
        <buffer_barColor>' . $player_colors['buffer_barColor'] . '</buffer_barColor>
        <skinIconColor>' . $player_colors['skinIconColor'] . '</skinIconColor>
        <pro_BgColor>' . $player_colors['pro_BgColor'] . '</pro_BgColor>
        <playButtonColor>' . $player_colors['playButtonColor'] . '</playButtonColor>
        <playButtonBgColor>' . $player_colors['playButtonBgColor'] . '</playButtonBgColor>
        <playerButtonColor>' . $player_colors['playerButtonColor'] . '</playerButtonColor>
        <playerButtonBgColor>' . $player_colors['playerButtonBgColor'] . '</playerButtonBgColor>
        <relatedVideoBgColor>' . $player_colors['relatedVideoBgColor'] . '</relatedVideoBgColor>
        <scroll_barColor>' . $player_colors['scroll_barColor'] . '</scroll_barColor>
        <scroll_BgColor>' . $player_colors['scroll_BgColor'] . '</scroll_BgColor>
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