<?php

/* Author : John Thomas M R
 * Email  : johnthomas@contus.in
 * Purpose: Player Configuration Settings
 *
 *
 *
 */
ob_clean();
header("cache-control: private");
header ("Pragma: public");
header("Content-type: application/xml");
header("content-type:text/xml;charset=utf-8");
require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
global $site_url;
$settingsRecord = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$ski = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/skin';
$skinpath = $ski . '/' . $settingsRecord->skin . '/' . $settingsRecord->skin . '.swf';
$logoPath = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/images/';
$xmlPath = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/';
$playXml = $xmlPath . 'myextractXML.php';
$langXML = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/languageXML.php';
$emailPath = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/email.php';
$adsXml = $xmlPath . 'myadsXML.php';
$timer = $settingsRecord->timer == 1 ? 'true' : 'false';
$zoom = $settingsRecord->zoom == 1 ? 'true' : 'false';
$email = $settingsRecord->email ? 'true' : 'false';
$fullscreen = $settingsRecord->fullscreen == 1 ? 'true' : 'false';
$autoplay = ($settingsRecord->autoplay == 1) ? 'true' : 'false';
$playlistauto = ($settingsRecord->playlistauto == 1) ? 'true' : 'false';
$HD_default = ($settingsRecord->HD_default == 1) ? 'true' : 'false';
$download = ($settingsRecord->download == 1) ? 'true' : 'false';
$skin_autohide = ($settingsRecord->skin_autohide == 1) ? 'true' : 'false';
$embed_visible = ($settingsRecord->embed_visible == 1) ? 'true' : 'false';
$playlist = ($settingsRecord->playlist == 1) ? 'true' : 'false';
$debug = ($settingsRecord->debug == 1) ? 'true' : 'false';
$prerollads  = ($settingsRecord->preroll == 0) ? 'true' : 'false';
$postrollads = ($settingsRecord->postroll == 0) ? 'true' : 'false';



/* Configuration Start */
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<config

      buffer="' . $settingsRecord->buffer . '"
      height="' . $settingsRecord->height . '"
      width="' . $settingsRecord->width . '"
      normalscale="' . $settingsRecord->normalscale . '"
      fullscreenscale="' . $settingsRecord->fullscreenscale . '"
      languageXML = "' . $langXML . '"
      logopath="' . $logoPath . $settingsRecord->logopath . '"
      logo_target="'.$settingsRecord->logo_target.'"
      autoplay  ="' . $autoplay . '"
      playlistauto  ="' . $playlistauto . '"
      Volume="' . $settingsRecord->volume . '"
      logoalign="' . $settingsRecord->logoalign . '"
      HD_default="' . $HD_default . '"
      Download="' . $download . '"
      logoalpha = "' . $settingsRecord->logoalpha . '"
      skin_autohide="' . $skin_autohide . '"
      stagecolor="' . $settingsRecord->stagecolor . '"
      scaleToHideLogo = "'.$settingsRecord->hideLogo.'"
      shareURL ="'. $emailPath. '"
      skin="' . $skinpath . '"
      embed_visible="' . $embed_visible . '"
      playlistXML="' . $playXml . '"
      preroll_ads="'.$prerollads.'"
      postroll_ads="'.$postrollads.'"
      adXML="'.$adsXml.'"
      UseYouTubeApi="flash"
      showPlaylist ="' . $playlist . '"
      license = "'.$settingsRecord->license.'"
      debug="' . $debug . '">';

echo '<timer>' . $timer . '</timer>';

echo '<zoom>' . $zoom . '</zoom>';

echo '<email>' . $email . '</email>';

echo '<fullscreen>' . $fullscreen . '</fullscreen>';

echo '</config>';
exit;


/* Configuration ends */
exit;
?>