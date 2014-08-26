<?php
/**  
 * Video ajaxplaylist admin model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'AjaxPlaylistModel' ) != true ) {		## checks the VideoadModel class has been defined if starts

	class AjaxPlaylistModel {							## PlaylistModel class starts

		public $_playListId;

		public function __construct() {					## contructor starts
			global $wpdb;
			$this->_wpdb		  = $wpdb;
			$this->_playlisttable = $this->_wpdb->prefix . 'hdflvvideoshare_playlist';
			$this->_playListId    = filter_input( INPUT_GET, 'playlistId' );
		}												## contructor ends
	}													## PlaylistModel class ends
}														## checks the PlaylistModel class has been defined if ends
?>