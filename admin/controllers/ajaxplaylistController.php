<?php
/** 
 * Ajax Playlist Controller.
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
include_once( $adminModelPath . 'ajaxplaylist.php' );				## including Playlist model file for get database information.
if ( class_exists( 'AjaxPlaylistController' ) != true ) {			## checks if the PlaylistController class has been defined starts

	class AjaxPlaylistController extends AjaxPlaylistModel {	## VideoadController class starts

		public $_status;
		public $_msg;
		public $_search;
		public $_playlistsearchQuery;
		public $_addnewPlaylist;
		public $_searchBtn;
		public $_update;
		public $_add;
		public $_del;
		public $_orderDirection;
		public $_orderBy;
        /**
         * Contructor function
         */
		public function __construct() {							
			parent::__construct();
			$this->_playlistsearchQuery = filter_input( INPUT_POST, 'PlaylistssearchQuery' );
			$this->_addnewPlaylist = filter_input( INPUT_POST, 'playlistadd' );
			$this->_status = filter_input( INPUT_GET, 'status' );
			$this->_searchBtn = filter_input( INPUT_POST, 'playlistsearchbtn' );
			$this->_update = filter_input( INPUT_GET, 'update' );
			$this->_add = filter_input( INPUT_GET, 'add' );
			$this->_del = filter_input( INPUT_GET, 'del' );
			$this->_orderDirection = filter_input( INPUT_GET, 'order' );
			$this->_orderBy = filter_input( INPUT_GET, 'orderby' );
		}


		public function hd_ajax_add_playlist( $name, $media ) {
			global $wpdb;
			$p_name = addslashes( trim( $name ) );
			$p_slugname = sanitize_title($name);
			$p_description   = '';
			$p_playlistorder = $wpdb->get_var( 'SELECT MAX(playlist_order) FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist' );
			$playlist_order  = $p_playlistorder+1;  
			$playlistname1   = 'SELECT playlist_name FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE playlist_name="' . $p_name . '"';
			$planame1 = mysql_query( $playlistname1 );
			if ( mysql_fetch_array( $planame1, MYSQL_NUM ) ) {
				$this->render_error( __( 'Failed, category name already exist', 'hdflvvideoshare' ) ) . $this->get_playlist_for_dbx( $media );
				return;
			}
			if ( ! empty( $p_name ) ) {
				$insert_plist = mysql_query( ' INSERT INTO ' . $wpdb->prefix . 'hdflvvideoshare_playlist ( playlist_name, playlist_desc,is_publish, playlist_order,playlist_slugname ) VALUES ( "'.$p_name.'", "'.$p_description.'", "1", "'.$playlist_order.'","'.$p_slugname.'" )' );
				if ( $insert_plist != 0 ) {
					$this->render_message( __( 'Category', 'hdflvvideoshare' ) . ' ' . $name . __( ' added successfully', 'hdflvvideoshare' ) ) . $this->get_playlist_for_dbx( $media );
				}
			}
			return;
		}

		public function render_message( $message, $timeout = 0 ) {
			?>
			<div class="wrap">
				<div class="fade updated" id="message" onclick="this.parentNode.removeChild( this )">
					<p><strong><?php echo balanceTags( $message ); ?></strong></p>
				</div></div>
			<?php
		}

		public function render_error( $message ) {
			?>
			<div class="wrap">
				<div class="error" id="error">
					<p><strong><?php echo balanceTags( $message ); ?></strong></p>
				</div></div>
			<?php
		}
        /**
         * Get all playlists for our site
         */
		public function get_playlist() {

			global $wpdb;
			$playids = $wpdb->get_col( 'SELECT pid FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1' );
			$mediaid = '';
			$videoId = filter_input( INPUT_GET, 'videoId' );
			if ( isset( $videoId ) )
				$mediaid = $videoId;

			$checked_playlist = $wpdb->get_col(
								'SELECT playlist_id,sorder
								FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist,' . $wpdb->prefix . 'hdflvvideoshare_med2play
								WHERE is_publish=1 and ' . $wpdb->prefix . 'hdflvvideoshare_med2play.playlist_id = pid AND ' . $wpdb->prefix . 'hdflvvideoshare_med2play.media_id = "'.$mediaid.'"'
					);
			if ( count( $checked_playlist ) == 0 ) {
				$checked_playlist[] = 0;
			}

			$result = array();
			if ( is_array( $playids ) ) {
				foreach ( $playids as $playid ) {
					$result[$playid]['playid']  = $playid;
					$result[$playid]['checked'] = in_array( $playid, $checked_playlist );
					$result[$playid]['name']    = $this->get_playlistname_by_id( $playid );
					$result[$playid]['sorder']  = $this->get_sortorder( $mediaid, $playid );
				}
			}


			$hiddenarray = array();

			echo '<table>';
			foreach ( $result as $playlist ) {

				$hiddenarray[] = $playlist['playid'];
				echo '<tr><td style="font-size:11px"><input value="' . $playlist['playid']
						. '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
						. '"' . ( $playlist['checked'] ? ' checked="checked"' : '' ) . '/> <label for="playlist-' . $playlist['playid']
						. '" class="selectit">' . esc_html( $playlist['name'] ) . '</label></td ></tr>
							';
			}
			echo '</table>';
			$comma_separated = implode( ',', $hiddenarray );
			echo '<input type=hidden name=hid value = "'.$comma_separated.'" >';
		}
        /**
         * Get media playlist for the videos
         * @param unknown $mediaid
         */
		public function get_playlist_for_dbx( $mediaid ) {
			global $wpdb;
			$playids = $wpdb->get_col( 'SELECT pid FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist where is_publish=1' );
			$mediaid = ( int ) $mediaid;
			$checked_playlist = $wpdb->get_col(
								'SELECT playlist_id,sorder
								FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist,' . $wpdb->prefix . 'hdflvvideoshare_med2play
								WHERE  is_publish=1 AND ' . $wpdb->prefix . 'hdflvvideoshare_med2play.playlist_id = pid AND ' . $wpdb->prefix . 'hdflvvideoshare_med2play.media_id = "'.$mediaid.'"'
					);
			if ( count( $checked_playlist ) == 0 )
				$checked_playlist[] = 0;
			$result = array();
			if ( is_array( $playids ) ) {
				foreach ( $playids as $playid ) {
					$result[$playid]['playid']  = $playid;
					$result[$playid]['checked'] = in_array( $playid, $checked_playlist );
					$result[$playid]['name']    = $this->get_playlistname_by_id( $playid );
					$result[$playid]['sorder']  = $this->get_sortorder( $mediaid, $playid );
				}
			}
			$hiddenarray = array();
			echo '<table>';
			foreach ( $result as $playlist ) {
				$hiddenarray[] = $playlist['playid'];
				echo '<tr><td style="font-size:11px"><input value="' . $playlist['playid']
						. '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
						. '" ' . ( $playlist['checked'] ? ' checked="checked"' : '' ) . ' /><label for="playlist-' . $playlist['playid']
						. '" class="selectit"> ' . esc_html( $playlist['name'] ) . '</label></td ></tr>
							';
			}
			echo '</table>';
			$comma_separated = implode( ',', $hiddenarray );
			echo '<input type=hidden name=hid value = "'.$comma_separated.'" >';
		}
        /**
         * Get playlist sort order change
         * @param number $mediaid
         * @param unknown $pid
         * @return Ambigous <string, NULL>
         */
		public function get_sortorder( $mediaid = 0, $pid ) {
			global $wpdb;

			$mediaid = ( int ) $mediaid;
			$result  = $wpdb->get_var( 'SELECT sorder FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id = ' . $mediaid . ' and playlist_id= '.$pid );

			return $result;
		}
		/**
		 * Get playlist name by playlist id
		 * @param number $pid
		 * @return Ambigous <string, NULL>
		 */
		public function get_playlistname_by_id( $pid = 0 ) {
			global $wpdb;

			$pid    = ( int ) $pid;
			$result = $wpdb->get_var( 'SELECT playlist_name FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE pid = '.$pid.' AND is_publish=1' );

			return $result;
		}

	}

}
$ajaxplaylistOBJ = new AjaxPlaylistController();
$playlist_name   = filter_input( INPUT_GET, 'name' );
if ( isset( $playlist_name ) ) {
	return $ajaxplaylistOBJ->hd_ajax_add_playlist( filter_input( INPUT_GET, 'name' ), filter_input( INPUT_GET, 'media' ) );
}
?>
