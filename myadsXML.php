<?php
/**
 * AdsXML files
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
/* Used to import plugin configuration */
require_once( dirname( __FILE__ ) . '/hdflv-config.php' );
global $wpdb;
$selectPlaylist = 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_vgads WHERE publish=1';
$themediafiles  = $wpdb->get_results( $selectPlaylist );
ob_clean();
header( 'content-type: text/xml' );
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<ads random="false">';
if ( count( $themediafiles ) > 0 ) {
	foreach ( $themediafiles as $rows ) {
		$admethod = $rows->admethod;
		if ( $admethod == 'prepost' ) {	   // Allow only for preroll or post roll ads
			$postvideo = $rows->file_path;
			echo '<ad id="' . $rows->ads_id . '" url="' . $postvideo . '" targeturl="' . $rows->targeturl . '" clickurl="' . $rows->clickurl . '" impressionurl="' . $rows->impressionurl . '">';
			echo '<![CDATA[' . $rows->description . ']]>';
			echo '</ad>';
		}
	}
}
echo '</ads>';
?>