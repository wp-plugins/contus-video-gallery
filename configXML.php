<?php
/**  
 * Video player config xml file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

ob_clean();
header( 'cache-control: private' );
header( 'Pragma: public' );
header( 'Content-type: application/xml' );
header( 'content-type:text/xml;charset=utf-8' );
require_once( dirname( __FILE__ ) . '/hdflv-config.php' );
global $site_url;
$site_url = get_site_url();
$dir      = dirname( plugin_basename( __FILE__ ) );
$dirExp   = explode( '/', $dir );
$dirPage  = $dirExp[0];
$contusOBJ    = new ContusVideoView();
$settingsData = $contusOBJ->_settingsData;
$logoPath     = str_replace( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
// Generate XML Paths
$playXml				= $site_url . '/wp-admin/admin-ajax.php?action=myextractXML';
$midrollXml			= $site_url . '/wp-admin/admin-ajax.php?action=mymidrollXML';
$imaAdsXML				= $site_url . '/wp-admin/admin-ajax.php?action=myimaadsXML';
$langXML			= $site_url . '/wp-admin/admin-ajax.php?action=languageXML';
$emailPath			= $site_url . '/wp-admin/admin-ajax.php?action=email';
$downloadPath	    = '';
$adsXml				= $site_url . '/wp-admin/admin-ajax.php?action=myadsXML';
// Generated Skin path
$skinPath	= APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'skin/skin_hdflv_white.swf';
// Generate Config XML values
$playerTimer					= $settingsData->timer == 1 ? 'true' : 'false';
$adsSkip						= $settingsData->adsSkip == 0 ? 'true' : 'false';
$showTag						= $settingsData->showTag == 1 ? 'true' : 'false';
$imageDefault					= $settingsData->imageDefault == 1 ? 'true' : 'false';
$progressControl		= $settingsData->progressControl == 1 ? 'true' : 'false';
$volumecontrol				= $settingsData->volumecontrol == 1 ? 'true' : 'false';
$shareIcon						= $settingsData->shareIcon == 1 ? 'true' : 'false';
$imaAds							= $settingsData->imaAds == 0 ? 'true' : 'false';
$playerZoom						= $settingsData->zoom == 1 ? 'true' : 'false';
$playerEmail					= $settingsData->email ? 'true' : 'false';
$playerFullscreen	= $settingsData->fullscreen == 1 ? 'true' : 'false';
$playerAutoplay			= ( $settingsData->autoplay == 1 ) ? 'true' : 'false';
$playlistAuto					= ( $settingsData->playlistauto == 1 ) ? 'true' : 'false';
$hdDefault						= ( $settingsData->HD_default == 1 ) ? 'true' : 'false';
$playerDownload			= ( $settingsData->download == 1 ) ? 'true' : 'false';
$skinAutohide					= ( $settingsData->skin_autohide == 1 ) ? 'true' : 'false';
$embedVisible					= ( $settingsData->embed_visible == 1 ) ? 'true' : 'false';
$showPlaylist					= ( $settingsData->playlist == 1 ) ? 'true' : 'false';
$playlist_open				= ( $settingsData->playlist_open == 1 ) ? 'true' : 'false';
$playerDebug				= ( $settingsData->debug == 1 ) ? 'true' : 'false';
$prerollAds					= ( $settingsData->preroll == 0 ) ? 'true' : 'false';
$postrollAds				= ( $settingsData->postroll == 0 ) ? 'true' : 'false';
$midroll_ads				= ( $settingsData->midroll_ads == 0 ) ? 'true' : 'false';
$trackCode					= ( $settingsData->trackCode ) ? $settingsData->trackCode :'';
$player_colors				= unserialize( $settingsData->player_colors );
$skinVisible				= ( $player_colors['skinVisible'] == 1 ) ? 'true' : 'false';
$skin_opacity				=  $player_colors['skin_opacity'];
$subTitleColor				= ( $player_colors['subTitleColor']) ? $player_colors['subTitleColor'] : '';
$subTitleBgColor		    = ( $player_colors['subTitleBgColor'] ) ?  $player_colors['subTitleBgColor'] : '';
if ( isset( $player_colors['subTitleFontFamily'] ) ) {
	$subTitleFontFamily = $player_colors['subTitleFontFamily'];
} else {
	$subTitleFontFamily = '';
}
$subTitleFontSize = ( $player_colors['subTitleFontSize'] == 0 ) ? '' : $player_colors['subTitleFontSize'];
// Skin hide  start to play video
if($skinVisible=='false'){
	$progressControl		=  'false';
	$volumecontrol			=  'false';
	$shareIcon				=  'false';
	$playerTimer            =  'false';
	$playerDownload         =  'false';
	$playerEmail            =  'false';
	$playerFullscreen       =  'false';
	$hdDefault              =  'false';
	$playerZoom             =  'false';
}
// Add http in URL if not exist
$logotarget = $settingsData->logo_target;
if ( ! preg_match( '~^(?:f|ht)tps?://~i', $logotarget ) ) {
	$logotarget = 'http://' . $logotarget;
}
// Configuration Start
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
<skinVisible>' . $skinVisible . '</skinVisible>
<skin_opacity>' . $skin_opacity . '</skin_opacity>
<subTitleColor>' . $subTitleColor . '</subTitleColor>
<subTitleBgColor>' . $subTitleBgColor . '</subTitleBgColor>
<subTitleFontFamily>' . $subTitleFontFamily . '</subTitleFontFamily>
<subTitleFontSize>' . $subTitleFontSize . '</subTitleFontSize>
</config>';
// Configuration ends 
?>