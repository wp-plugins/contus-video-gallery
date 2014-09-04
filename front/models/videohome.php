<?php
/**  
 * Video home front end model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
if (class_exists ( 'ContusVideo' ) != true) { // checks the ContusVideo class has been defined if starts
	class ContusVideo { // ContusVideo class starts
		public function __construct() { // CONSTRUCTOR STARTS
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_videosettingstable = $this->_wpdb->prefix . 'hdflvvideoshare_settings';
			$this->_videoinfotable = $this->_wpdb->prefix . 'hdflvvideoshare';
		} // CONSTRUCTOR ENDS
		/**
		 * Function get video gallery setting
		 */
		public function get_settingsdata() { // function for getting settings data starts
			$query = 'SELECT * FROM ' . $this->_videosettingstable . ' WHERE settings_id = 1';
			return $this->_wpdb->get_row( $query );
		} // function for getting settings data ends
		/**
		 * Get all videos.
		 */
		public function get_videosdata() { // function for getting settings data starts
			$query = 'SELECT * FROM ' . $this->_videoinfotable;
			return $this->_wpdb->get_results ( $query );
		} // function for getting settings data ends
		/**
		 * Function get count of all category /playlist.
		 */
		public function get_countof_videocategories() { // function for getting settings data starts
			global $wpdb;
			$query = 'SELECT count( * ) FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1';
			return $this->_wpdb->get_var ( $query );
		} // function for getting settings data ends
		/**
		 * Playlist thumb data
		 * 
		 * @global type $wpdb
		 * @param type $pagenum        	
		 * @param type $dataLimit        	
		 * @return type category video array
		 */
		public function get_categoriesthumdata($pagenum, $dataLimit) {
			global $wpdb;
			$pagenum = isset ( $pagenum ) ? absint ( $pagenum ) : 1;
			$offset = ($pagenum - 1) * $dataLimit;
			$query = 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1 ORDER BY playlist_order ASC LIMIT ' . $offset . ',' . $dataLimit;
			$result = $wpdb->get_results ( $query );
			return $result;
		}
		/**
		 * Function get the more page id.
		 * 
		 * @return type $moreName
		 */
		public function get_more_pageid() {
			$moreName = $this->_wpdb->get_var ( 'SELECT ID FROM ' . $this->_wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" and post_status="publish" and post_type="page" limit 1' );
			return $moreName;
		}
		/**
		 * function for feature video count
		 * 
		 * @return type $video_count
		 */
		public function get_video_count() {
			$video_count = $this->_wpdb->get_var ( 'SELECT count( * ) FROM ' . $this->_videoinfotable . ' WHERE featured=1 and publish=1' );
			return $video_count;
		}
		/**
		 * function for video tag name
		 * 
		 * @param type $vid        	
		 * @return type $video_tagname
		 */
		public function get_tag_name($vid) {
			$video_tagname = $this->_wpdb->get_var ( 'SELECT tags_name from ' . $this->_wpdb->prefix . 'hdflvvideoshare_tags WHERE media_id="' . intval ( $vid ) . '"' );
			return $video_tagname;
		}
		/**
		 * function for getting video detail
		 */
		public function get_video_detail($vid) {
			global $wpdb;
			
			$sql = $wpdb->get_var( "SELECT player_colors FROM ".$this->_videosettingstable );
			$player_colors = unserialize($sql);
			if( !empty ($player_colors['related_video_count'] )){
				$related_video_count = $player_colors['related_video_count'];
			}else{
				$related_video_count = 100; 
			}  
			$select = 'SELECT distinct w.vid,w.*,s.guid FROM ' . $wpdb->prefix . 'hdflvvideoshare w
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.vid="' . $vid . '" AND w.publish=1 AND p.is_publish=1 GROUP BY w.vid';
			
			$themediafiles = $wpdb->get_results ( $select );
			$getPlaylist = $wpdb->get_results ( 'SELECT playlist_id FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id="' . intval ( $vid ) . '" LIMIT 1' );
			foreach ( $getPlaylist as $getPlaylists ) {
				if ($getPlaylists->playlist_id != '') {
					$playlist_id = $getPlaylists->playlist_id;
				} else {
					echo 'No videos is  here';
				}
				$fetch_video = 'SELECT distinct w.vid FROM ' . $wpdb->prefix . 'hdflvvideoshare w
							LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
							LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
							WHERE ( m.playlist_id = "' . $playlist_id . '"
							AND m.media_id = w.vid AND w.file_type!=5 AND p.pid=m.playlist_id AND m.media_id != "' . intval ( $vid ) . '" AND w.publish=1 AND p.is_publish=1  ) GROUP BY w.vid LIMIT '.$related_video_count;
				$fetched = $wpdb->get_results ( $fetch_video );
				// Array rotation to autoplay the videos correctly
				$arr1 = array ();
				$arr2 = array ();
				if (count ( $fetched ) > 0) {
					foreach ( $fetched as $r ) :
						if ($r->vid > $themediafiles [0]->vid) { // Storing greater values in an array
							$query = 'SELECT distinct w.vid,w.*,s.guid FROM ' . $wpdb->prefix . 'hdflvvideoshare w
								LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
								LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
								LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
								WHERE ( w.vid=' . $r->vid . ' AND m.media_id != "' . intval ( $vid ) . '" AND w.file_type!=5 AND w.publish=1 AND p.is_publish=1  ) GROUP BY w.vid';
							
							$arrGreat = $wpdb->get_row ( $query );
							$arr1 [] = $arrGreat;
						} else { // Storing lesser values in an array
							$query = 'SELECT distinct w.vid,w.*,s.guid FROM ' . $wpdb->prefix . 'hdflvvideoshare w
								LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
								LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
								LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
								WHERE ( w.vid=' . $r->vid . ' AND m.media_id != "' . intval ( $vid ) . '" AND w.file_type!=5 AND w.publish=1 AND p.is_publish=1  ) GROUP BY w.vid';
							
							$arrLess = $wpdb->get_row ( $query );
							$arr2 [] = $arrLess;
						}
					endforeach;
				}
				
				$themediafiles = array_merge ( $themediafiles, $arr1, $arr2 );
			}
			
			return $themediafiles;
		}
		/**
		 * function pin detail
		 * 
		 * @global type $wpdb
		 * @param type $pid        	
		 * @param type $type        	
		 * @return type $themediafiles
		 */
		public function video_pid_detail($pid, $type,$number_related_video) {
			global $wpdb;		
			$select = ' SELECT w.*,s.guid,m.playlist_id,u.display_name,u.ID FROM ' . $wpdb->prefix . 'hdflvvideoshare w';
			$select .= ' LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid';
			$select .= ' LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id';
			$select .= ' LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug';
			$select .= ' LEFT JOIN ' . $wpdb->users . ' u ON u.ID=w.member_id';
			$select .= ' WHERE ( m.playlist_id = "' . intval ( $pid ) . '"';
			if ($type === 'playxml') {
				$where = 'AND w.file_type!=5';
			} else {
				$where = '';
			}
			$select .= ' AND m.media_id = w.vid ' . $where . ' AND w.publish=1 AND p.is_publish=1 ) GROUP BY w.vid ';
			$select .= ' ORDER BY w.vid ASC LIMIT '.$number_related_video;
			$themediafiles = $wpdb->get_results( $select );
			return $themediafiles;
		}
		/**
		 * function for get single videos datas
		 */
		public function get_singlevideodata() {
			$query = 'SELECT * FROM ' . $this->_videoinfotable . ' WHERE featured=1 and publish=1 ORDER BY vid DESC LIMIT 0,1';
			$feature  = $this->_wpdb->get_row($query);
			if(!$feature){
				$query = 'SELECT * FROM ' . $this->_videoinfotable . ' WHERE  publish=1 ORDER BY vid DESC LIMIT 0,1';
				$feature =  $this->_wpdb->get_row($query);
			}
			return $feature;
		}
		/**
		 * function get feature video player details.
		 * no feature video recent  added video is play.
		 * @return type array
		 */
		public function get_featuredvideodata() {
			global $wpdb;
			$related_video_count =  $wpdb->get_var(" SELECT player_colors FROM ".$this->_videosettingstable);
			$player_colors = unserialize($related_video_count);
			if( !empty( $player_colors['related_video_count']) ){
				$related_video_count = $player_colors['related_video_count'];
			} else{
				$related_video_count = 10;
			}
			$query = 'SELECT distinct w.*,s.guid,p.playlist_name,u.display_name FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					LEFT JOIN '.$wpdb->users.' u ON u.ID = w.member_id		
					WHERE featured=1 AND publish=1 AND p.is_publish=1 GROUP BY w.vid ORDER BY ordering ASC LIMIT '.$related_video_count;
			$feature = $this->_wpdb->get_results($query);
			if(!$feature){
				$query = 'SELECT distinct w.*,s.guid,p.playlist_name,u.display_name FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					LEFT JOIN '.$wpdb->users.' u ON u.ID = w.member_id
					WHERE publish=1 AND p.is_publish=1 GROUP BY w.vid ORDER BY ordering ASC LIMIT '.$related_video_count;
				$feature = $this->_wpdb->get_results($query);
				
			}
			return $feature;
		}
		/**
		 * function for get all feature video banners
		 * 
		 * @return type banners
		 */
		public function get_featuredvideodata_banner() {
			$query = 'SELECT distinct w.*,s.guid FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE featured=1 and publish=1 AND p.is_publish=1 GROUP BY w.vid ORDER BY vid ASC LIMIT 0,4';
			return $this->_wpdb->get_results ( $query );
		}
		/**
		 * function for getting thumb details for home page category section starts
		 * 
		 * @global type $wpdb
		 * @param type $thumImageorder        	
		 * @param type $dataLimit        	
		 */
		public function get_home_catthumbdata($thumImageorder, $dataLimit) {
			global $wpdb;
			$query = 'SELECT s.guid,w.*,p.playlist_name FROM ' . $wpdb->prefix . 'hdflvvideoshare as w
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid
					LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.publish=1 AND p.is_publish=1 AND m.playlist_id=' . intval ( $thumImageorder ) . '
					GROUP BY w.vid ORDER BY w.ordering asc LIMIT ' . $dataLimit;
			return $this->_wpdb->get_results ( $query );
		}
		/**
		 * Function for getting thumb details for home page starts
		 * 
		 * @param type $thumImageorder        	
		 * @param type $where        	
		 * @param type $dataLimit        	
		 */
		public function get_thumdata($thumImageorder, $where, $dataLimit) {
			$query = 'SELECT distinct w.*,s.guid,s.ID,p.playlist_name,p.pid,p.playlist_slugname FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.publish=1 AND p.is_publish=1 ' . $where . ' GROUP BY w.vid ORDER BY ' . $thumImageorder . ' LIMIT ' . $dataLimit;
			return $this->_wpdb->get_results ( $query );
		}
		/**
		 * Function get countof thumbdata
		 * 
		 * @param type $thumImageorder        	
		 * @param type $where        	
		 */
		public function get_countof_thumdata($thumImageorder, $where) {
			$query = 'SELECT w.vid FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.publish=1 AND p.is_publish=1 ' . $where . ' GROUP BY w.vid ORDER BY ' . $thumImageorder;
			return count ( $this->_wpdb->get_results ( $query ) );
		}
		/**
		 * Function for get playlist xml dat
		 * 
		 * @param type $getVid        	
		 * @param type $thumImageorder        	
		 * @param type $where        	
		 * @param type $numberofvideos        	
		 */
		public function get_playxmldata($getVid, $thumImageorder, $where, $numberofvideos) { // function for getting data for playxml starts
			$videoid = $getVid;
			$query = 'SELECT distinct w.*,s.guid,p.playlist_name,p.pid FROM ' . $this->_videoinfotable . ' w
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.publish=1 AND p.is_publish=1 AND w.vid=' . $videoid . ' GROUP BY w.vid';
			$rows = $this->_wpdb->get_results ( $query );
			if (count ( $rows ) > 0) {
				$query = 'SELECT distinct w.*,s.guid,p.playlist_name,p.pid FROM ' . $this->_videoinfotable . ' w
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
						LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
						WHERE w.publish=1 AND p.is_publish=1 ' . $where . ' AND w.vid != ' . $videoid . '
						GROUP BY w.vid ORDER BY ' . $thumImageorder . ' LIMIT ' . ($numberofvideos - 1);
				$playlist = $this->_wpdb->get_results ( $query );
				$arr1 = array ();
				$arr2 = array ();
				if (count ( $playlist ) > 0) {
					foreach ( $playlist as $r ) :
						if ($r->vid > $rows [0]->vid) { // Storing greater values in an array
							$query = 'SELECT distinct w.*,s.guid,p.playlist_name,p.pid FROM ' . $this->_videoinfotable . ' w
								LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
								LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
								LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
								WHERE w.publish=1 AND p.is_publish=1 AND w.vid=' . $r->vid;
							$arrGreat = $this->_wpdb->get_row ( $query );
							$arr1 [] = $arrGreat;
						} else { // Storing lesser values in an array
							$query = 'SELECT distinct w.*,s.guid,p.playlist_name,p.pid FROM ' . $this->_videoinfotable . ' w
								LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
								LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
								LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
								WHERE w.publish=1 AND p.is_publish=1 AND w.vid=' . $r->vid;
							$arrLess = $this->_wpdb->get_row ( $query );
							$arr2 [] = $arrLess;
						}
					endforeach
					;
				}
				
				$finalplaylist = array_merge ( $rows, $arr1, $arr2 );
			}
			return $finalplaylist;
		}		
		public function get_related_video_count(){
			global $wpdb;
			$sql = $wpdb->get_var("SELECT player_colors FROM " . $this->_wpdb->prefix ."hdflvvideoshare_settings");
			$player_colors = unserialize($sql);
			if( isset( $player_colors['related_video_count'] ) )  {
				$related_video_count = $player_colors['related_video_count'];
			} else {
				$related_video_count = 10;
			}
		} 
 	} 
} 
?>