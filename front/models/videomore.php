<?php
/**  
 * Video more front end model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
if ( class_exists( 'ContusMore' ) != true ) {											

	class ContusMore {																

		public function __construct() {												
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_videosettingstable = $this->_wpdb->prefix . 'hdflvvideoshare_settings';
			$this->_videoinfotable     = $this->_wpdb->prefix . 'hdflvvideoshare';
		}																			
                /**
                 * Get the video gallery settings
                 * @return type
                 */
		public function get_settingsdata() {										
			$query = 'SELECT * FROM ' . $this->_videosettingstable . ' WHERE settings_id = 1';
			return $this->_wpdb->get_row( $query );
		}																			
                /**
                 * Function for the more page_id get.
                 * 
                 * @return type
                 */
		public function get_more_pageid() {											
			$moreName = $this->_wpdb->get_var( 'SELECT ID FROM ' . $this->_wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" and post_status="publish" and post_type="page" limit 1' );
			return $moreName;
		}																			
                /**
                 * Player video count.
                 * @return type
                 */
		public function get_video_count() {											
			$video_count = $this->_wpdb->get_var( 'SELECT count( * ) FROM ' . $this->_videoinfotable . ' WHERE featured=1 and publish=1' );
			return $video_count;
		}
                /**
                 * Function for  get recent , feature,popular video.
                 * @param type $thumImageorder
                 * @param type $where
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @return type array
                 */
		public function get_thumdatamore( $thumImageorder, $where, $pagenum, $dataLimit ) { 
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT distinct w.*,s.guid,p.playlist_slugname,p.playlist_name FROM ' . $this->_videoinfotable . ' w
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
						LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
						WHERE w.publish=1 AND p.is_publish=1 ' . $where . ' GROUP BY w.vid ORDER BY ' . $thumImageorder . ' LIMIT ' . $offset . ',' . $dataLimit;
			return $this->_wpdb->get_results( $query );
		}
                /**
                 * 
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @return type
                 */
		public function get_categoriesthumdata( $pagenum, $dataLimit ) {				
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT * FROM ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1 ORDER BY playlist_order ASC LIMIT ' . $offset . ',' . $dataLimit;
			return $this->_wpdb->get_results( $query );
		}
                /**
                 * Function for  Search  key word based video category
                 * 
                 * @param type $thumImageorder
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @return type
                 */
		public function get_searchthumbdata( $thumImageorder, $pagenum, $dataLimit ) { 
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT t1.vid,t1.slug,t1.name,t1.ratecount,t1.rate,t1.description,s.guid,t3.pid,t3.playlist_name,t1.image,t1.file,t1.file_type,t1.duration,t1.hitcount,t2.playlist_id,t3.playlist_name,t3.playlist_slugname FROM ' . $this->_wpdb->prefix . 'hdflvvideoshare AS t1
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play AS t2 ON t2.media_id = t1.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist AS t3 ON t3.pid = t2.playlist_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_tags AS t4 ON t4.media_id = t1.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=t1.slug
					WHERE t3.is_publish=1 AND t1.publish=1';
          $query .= ' AND ( t4.tags_name LIKE %s OR t1.description LIKE %s OR t1.name LIKE %s )';	
		  $query .=' GROUP BY t1.vid  LIMIT ' . $offset . ',' . $dataLimit;
		  $count =  count($thumImageorder)-1;
          $query  =   $this->_wpdb->prepare($query ,'%'.$thumImageorder.'%' ,'%'.$thumImageorder.'%' ,'%'.$thumImageorder.'%');
		  return $this->_wpdb->get_results( $query );
		}
                /**
                 * Funtion get Count of search keyword video count.
                 * @global type $wpdb
                 * @param type $thumImageorder
                 * @return type $result
                 */
		public function get_countof_videosearch( $thumImageorder ) {					
			global $wpdb;
			$query   = 'SELECT t1.vid FROM ' . $wpdb->prefix . 'hdflvvideoshare AS t1
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS t2 ON t2.media_id = t1.vid
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS t3 ON t3.pid = t2.playlist_id
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_tags AS t4 ON t4.media_id = t1.vid
					LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=t1.slug
					WHERE t3.is_publish=1 AND t1.publish=1';
			$query .= ' AND ( t4.tags_name LIKE %s OR t1.description LIKE %s OR t1.name LIKE %s )';
			$query .=' GROUP BY t1.vid ';
			$count =  count($thumImageorder);
			$query =  $this->_wpdb->prepare( $query , '%'.$thumImageorder.'%' , '%'.$thumImageorder.'%' ,'%'.$thumImageorder.'%');
			$results = count( $wpdb->get_results( $query ) );
			return $results;
		}
                /**
                 * Function for  get count
                 * @global type $wpdb
                 * @param type $playid
                 * @param type $userid
                 * @param type $thumImageorder
                 * @param type $where
                 * @return type $result
                 */
		public function get_countof_videos( $playid, $userid, $thumImageorder, $where ) { 
			global $wpdb;
			if ( ! empty( $playid ) ) {
				$query  = 'SELECT count( * ) FROM ' . $wpdb->prefix . 'hdflvvideoshare as w 
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid 
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid 
						WHERE w.publish=1 and p.is_publish=1 and m.playlist_id=' . intval( $thumImageorder );
				$result = $this->_wpdb->get_var( $query );
			} else if ( ! empty( $userid ) ) {
				$query  = 'SELECT count( * ) FROM ' . $wpdb->prefix . 'hdflvvideoshare as w 
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid 
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid 
						LEFT JOIN '.$wpdb->users.' u ON u.ID=w.member_id 
						WHERE w.publish=1 and p.is_publish=1 and u.ID=' . intval( $thumImageorder );
				$result = $this->_wpdb->get_var( $query );
			} else {
				$query  = 'SELECT count( w.vid ) FROM ' . $this->_videoinfotable . ' w
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
						WHERE w.publish=1 '.$where.' AND p.is_publish=1 GROUP BY w.vid ORDER BY ' . $thumImageorder;
				$result_count = $this->_wpdb->get_results( $query );
				$result = count( $result_count );
			}
			return $result;
		}
                /**
                 * function for getting count of video categories
                 * @global type $wpdb
                 * @return type count
                 */
		public function get_countof_videocategories() {								
			global $wpdb;
			$query = 'SELECT count( * ) FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE is_publish=1';
			return $this->_wpdb->get_var( $query );
		}
                /**
                 * function for getting home category thumb data
                 * @global type $wpdb
                 * @param type $thumImageorder
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @return type
                 */
		public function home_catthumbdata( $thumImageorder, $pagenum, $dataLimit , $default_order ) { 
			global $wpdb;
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT s.guid,w.*,p.playlist_name,p.playlist_slugname FROM ' . $wpdb->prefix . 'hdflvvideoshare as w
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid
						LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
						WHERE w.publish=1 AND p.is_publish=1 AND m.playlist_id=' . intval( $thumImageorder ) . '
						GROUP BY w.vid ORDER BY '.$default_order.' LIMIT ' . $offset . ',' . $dataLimit;
			return $this->_wpdb->get_results( $query );
		}
                /**
                 * Get thumb data for similar user created  
                 * @global type $wpdb
                 * @param type $thumImageorder
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @return type $useradded video
                 */
		public function home_userthumbdata( $thumImageorder, $pagenum, $dataLimit ) {		
			global $wpdb;
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT s.guid,w.*,p.playlist_name,p.playlist_slugname FROM ' . $wpdb->prefix . 'hdflvvideoshare as w
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid
						LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=w.slug
						LEFT JOIN '.$wpdb->users.' u ON u.ID=w.member_id
						WHERE w.publish=1 AND p.is_publish=1 AND u.ID=' . intval( $thumImageorder ) . '
						GROUP BY w.vid ORDER BY w.ordering asc LIMIT ' . $offset . ',' . $dataLimit;
			return $this->_wpdb->get_results( $query );
		}
		/**
		 * function  for  tag based video show
		 */
		public function get_home_tagthumbdata($where, $pagenum, $dataLimit ) {
			$pagenum = !empty( $pagenum ) ? absint( $pagenum ) : 1;
			$offset  = (  $pagenum - 1  ) * $dataLimit;
			$query   = 'SELECT distinct w.*,s.guid,p.playlist_slugname,p.playlist_name FROM ' . $this->_videoinfotable . ' w
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play m ON m.media_id = w.vid
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=m.playlist_id
						LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_tags t ON t.media_id=w.vid
						LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
						WHERE w.publish=1 AND p.is_publish=1 ' . $where . ' GROUP BY w.vid  LIMIT ' . $offset . ',' . $dataLimit;
			return $this->_wpdb->get_results( $query );
		}
	}																					
}																						
?>