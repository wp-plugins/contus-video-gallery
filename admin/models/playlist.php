<?php
/**  
 * Video playlist admin model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'PlaylistModel' ) != true ) {							

	class PlaylistModel {													

		public $_playListId;

		public function __construct() {										
			global $wpdb;
			$this->_wpdb		  = $wpdb;
			$this->_playlisttable = $this->_wpdb->prefix . 'hdflvvideoshare_playlist';
			$this->_playListId    = absint( filter_input( INPUT_GET, 'playlistId' ) );
		}																	
        /**
         * Function insert new playlist
         */
		public function insert_playlist( $playlsitData ) {	
			if ( $this->_wpdb->insert( $this->_playlisttable, $playlsitData ) ) {
				return $this->_wpdb->insert_id;
			}
		}																	
		/**
		 * Update the playlist details 
		 */
		public function playlist_update( $playlistData, $playlistId ) {		
			return $this->_wpdb->update( $this->_playlisttable, $playlistData, array( 'pid' => $playlistId ) );
		}																	
		/**
		 * Status changes via ajax
		 */
		public function status_update( $playlistId, $status ) {				
			return $this->_wpdb->update( $this->_playlisttable, array( 'is_publish' => $status ), array( 'pid' => $playlistId ) );
		}																	
		/**
		 * Get all playlists for grid  layout.  
		 */
		public function get_playlsitdata( $searchValue, $searchBtn, $order, $orderDirection ) {		
			$where   = '';
			$pagenum =  absint( filter_input(INPUT_GET , 'pagenum') ) ;
			if(empty( $pagenum) ) {
				$pagenum = 1;
			}
			$limit   = 20;
			$offset  = ( $pagenum - 1 ) * $limit;
			$query = 'SELECT * FROM ' . $this->_playlisttable;
			if ( isset( $searchBtn ) ) {
				$query .= ' WHERE playlist_name LIKE %s OR playlist_desc LIKE %s';
			}
			if ( ! isset( $orderDirection ) ) {
				$query .= ' ORDER BY '. $order.' DESC';
			} else {
				$query  .= ' ORDER BY '. $order.' ' .$orderDirection;
			}
			if( isset( $searchBtn) ) {
				$query = $this->_wpdb->prepare( $query , '%'.$searchValue.'%' , '%'.$searchValue.'%' );
			} else {
				$query =  $query;
			}
			return $this->_wpdb->get_results( $query );
		}																	
		/**
		 * Get single playlistsdetails
		 */
		public function playlist_edit( $playlistId ) {						
			return $this->_wpdb->get_row( 'SELECT * FROM ' . $this->_playlisttable . ' WHERE pid ='.$playlistId );
		}																	
		/**
		 * Get total playlists for paginations
		 */
		public function playlist_count( $searchValue, $searchBtn ) {		
			$query ='SELECT COUNT( `pid` ) FROM ' . $this->_playlisttable;
			if ( isset( $searchBtn ) ) {
				$query  .= ' WHERE playlist_name LIKE %s OR playlist_desc LIKE %s';
			}			
			if( isset ($searchBtn) ) {
				return $this->_wpdb->get_var( $this->_wpdb->prepare( $query ,  '%'.$searchValue.'%' , '%'.$searchValue.'%') );
			} else {
			   return $this->_wpdb->get_var($query);
			}
		}																	
		/**
		 * Delete function for playlists 
		 */
		public function playlist_delete( $playListId ) {					
			$query = 'DELETE FROM ' . $this->_playlisttable . '  WHERE pid IN ( ' . $playListId . ' )';
			return $this->_wpdb->query( $query );
		}																	
	}																		
}																			
?>