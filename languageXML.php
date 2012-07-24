<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Langauge XML
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */

ob_clean();
header ("content-type: text/xml");
require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$langSettings = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_language");

if (count($langSettings) > 0)
{
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<language>';
    echo'<play>';
    echo '<![CDATA['.$langSettings->play.']]>';
    echo  '</play>';
    echo '<pause>';
    echo '<![CDATA['.$langSettings->pause.']]>';
    echo '</pause>';
    echo '<hdison>';
    echo '<![CDATA['.$langSettings->hdison.']]>';
    echo '</hdison>';
    echo '<hdisoff>';
    echo '<![CDATA['.$langSettings->hdisoff.']]>';
    echo '</hdisoff>';
    echo '<zoom>';
    echo '<![CDATA['.$langSettings->zoom.']]>';
    echo '</zoom>';
    echo'<share>';
    echo '<![CDATA['.$langSettings->share.']]>';
    echo '</share>';
    echo'<fullscreen>';
    echo '<![CDATA['.$langSettings->lang_fullscreen.']]>';
    echo '</fullscreen>';
    echo'<relatedvideos>';
    echo '<![CDATA['.$langSettings->relatedvideos.']]>';
    echo '</relatedvideos>';
    echo'<sharetheword>';
    echo '<![CDATA['.$langSettings->sharetheword.']]>';
    echo '</sharetheword>';
    echo'<sendanemail>';
    echo '<![CDATA['.$langSettings->sendanemail.']]>';
    echo '</sendanemail>';
    echo'<to>';
    echo '<![CDATA['.$langSettings->to.']]>';
    echo '</to>';
    echo'<from>';
    echo '<![CDATA['.$langSettings->from.']]>';
    echo '</from>';
    echo'<note>';
    echo '<![CDATA['.$langSettings->note.']]>';
    echo '</note>';
    echo'<send>';
    echo '<![CDATA['.$langSettings->send.']]>';
    echo '</send>';
    echo'<copylink>';
    echo '<![CDATA['.$langSettings->copylink.']]>';
    echo '</copylink>';
    echo'<copyembed>';
    echo '<![CDATA['.$langSettings->copyembed.']]>';
    echo '</copyembed>';
    echo'<facebook>';
    echo '<![CDATA['.$langSettings->facebook.']]>';
    echo '</facebook>';
    echo'<reddit>';
    echo '<![CDATA['.$langSettings->reddit.']]>';
    echo '</reddit>';
    echo'<friendfeed>';
    echo '<![CDATA['.$langSettings->friendfeed.']]>';
    echo '</friendfeed>';
    echo'<slashdot>';
    echo '<![CDATA['.$langSettings->slashdot.']]>';
    echo '</slashdot>';
    echo'<delicious>';
    echo '<![CDATA['.$langSettings->delicious.']]>';
    echo '</delicious>';
    echo'<myspace>';
    echo '<![CDATA['.$langSettings->myspace.']]>';
    echo '</myspace>';
    echo'<wong>';
    echo '<![CDATA['.$langSettings->wong.']]>';
    echo '</wong>';
    echo'<digg>';
    echo '<![CDATA['.$langSettings->digg.']]>';
    echo '</digg>';
    echo'<blinklist>';
    echo '<![CDATA['.$langSettings->blinklist.']]>';
    echo '</blinklist>';
    echo'<bebo>';
    echo '<![CDATA['.$langSettings->bebo.']]>';
    echo '</bebo>';
    echo'<fark>';
    echo '<![CDATA['.$langSettings->fark.']]>';
    echo '</fark>';
    echo'<tweet>';
    echo '<![CDATA['.$langSettings->tweet.']]>';
    echo '</tweet>';
    echo'<furl>';
    echo '<![CDATA['.$langSettings->furl.']]>';
    echo '</furl>';
    echo '<adindicator><![CDATA[Your selection will follow this sponsors message in - seconds]]>';
    echo '</adindicator>';
    echo '<Skip><![CDATA[Skip this Video]]></Skip>';
    echo '<Skip><![CDATA[Download this Video]]></Skip>';
    echo '<errormessage><![CDATA['.$rs_lang[0]->errormessage.']]></errormessage>';
    echo '<buttonname><![CDATA['.$rs_lang[0]->btnname.']]></buttonname>';
    echo '</language>';
}
exit();
