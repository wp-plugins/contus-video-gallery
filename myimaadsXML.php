<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: AdsXML file for player.
  Version: 2.3.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
/* Used to import plugin configuration */
require_once( dirname(__FILE__) . '/hdflv-config.php');
## get the path url from querystring
global $wpdb;
$themediafiles      = array();
$selectPlaylist     = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads WHERE admethod = 'imaad' AND publish=1 LIMIT 1";
$themediafiles      = $wpdb->get_results($selectPlaylist);
$settings           = $wpdb->get_row("SELECT width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

ob_clean();
header("content-type: text/xml");
echo    '<?xml version="1.0" encoding="utf-8"?>';
echo    '<ima>';
if (count($themediafiles) > 0) {
    foreach ($themediafiles as $rows) {
        $admethod               = $rows->admethod;
        if ($admethod == 'imaad') {
            $imaadwidth = $rows->imaadwidth;
            if (empty($imaadwidth))
                $imaadwidth     = $settings->width - 30;
            $imaadheight        = $rows->imaadheight;
            if (empty($imaadheight))
                $imaadheight    = $settings->height - 60;
            $imaadpath          = $rows->imaadpath;
            $publisherId        = $rows->publisherId;
            $contentId          = $rows->contentId;
            $imaadType          = $rows->imaadType;
            if ($imaadType == 0)
                $imaadType      = '';
            else
                $imaadType      = 'Text';
            $channels           = $rows->channels;

            ## video ads
            echo '
                <adSlotWidth>' . $imaadwidth . '</adSlotWidth>
                <adSlotHeight>' . $imaadheight . '</adSlotHeight>
                <adTagUrl>' . $imaadpath . '</adTagUrl>';
            ## text ads size(468,60)
            echo '<publisherId>' . $publisherId . '</publisherId>
                  <contentId>' . $contentId . '</contentId>';
            ## Text or Overlay
            echo '<adType>' . $imaadType . '</adType>
                  <channels>' . $channels . '</channels>';
        }
    }
}else {
            ## video ads
            echo '
                <adSlotWidth>400</adSlotWidth>
                <adSlotHeight>250</adSlotHeight>
                <adTagUrl>http://ad.doubleclick.net/pfadx/N270.126913.6102203221521/B3876671.22;dcadv=2215309;sz=0x0;ord=%5btimestamp%5d;dcmt=text/xml</adTagUrl>';

            ## text ads size(468,60)
            echo '<publisherId></publisherId>
                <contentId>1</contentId>';
            ## Text or Overlay
            echo ' <adType>Text</adType>
                <channels>poker</channels>';
}
echo '</ima>';
?>