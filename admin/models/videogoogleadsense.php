<?php
 /**  
 * Video videogoogleadsense model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'VideogoogleadsenseModel' ) != true ) {										

	class VideogoogleadsenseModel {															

		public $_videogoogleadId;
		public $_videoadtable;

		public function __construct() {												
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_videoadtable = $this->_wpdb->prefix . 'hdflvvideoshare_vgoogleadsense';
			$this->_videogoogleadId    = absint( filter_input( INPUT_GET, 'videogoogleadsenseId' ) );
		}
		/**
		 * Function for get all the google adsense.  
		 */											
		public function get_videogoogleadsenses($searchValue,$searchBtn,$order,$orderDirection){
			$where   = '';
			$pagenum =  absint( filter_input(INPUT_GET, 'pagenum' ) );
			if( empty ( $pagenum ) ) {
				$pagenum = 1;
			}
			$limit   = 20;
			$offset  = ( $pagenum - 1 ) * $limit;
			$query = "SELECT * FROM ". $this->_videoadtable ;
			if ( isset( $searchBtn ) ) {
				$query .= ' WHERE googleadsense_details LIKE %s';
			}
			if (!isset( $orderDirection ) ) {
				$query .= ' ORDER BY '. $order.' DESC';
			} else {
				$query .= ' ORDER BY '. $order.' '.$orderDirection;
			}			
			$query .= ' LIMIT '.$offset.', '.$limit;
			if( isset( $searchBtn )){
				$query =  $this->_wpdb->prepare($query , '%' .$searchValue . '%');
			} else {
				$query = $query;
			}
			return $this->_wpdb->get_results( $query );
		}	
		/**
		 * function save googleadsense details
		 */						
		public function videogoogleadsense_insert($videogoogleadData){
			return $this->_wpdb->insert($this->_videoadtable,$videogoogleadData);
		}
		/**
		 * Update Google adsense details.  
		 */
		public function videogoogleadsense_update($googleadsenseId, $videoadData, $videoadDataformat) {		
			return $this->_wpdb->update( $this->_videoadtable, $videoadData, array( 'id' => $googleadsenseId ), $videoadDataformat );
		}				
		/**
		 * Google adsense details.
		 */
		public function videogoogleadsense_edit($googleadsenseId) {								
			return $this->_wpdb->get_row( 'SELECT * FROM ' . $this->_videoadtable . ' WHERE id='.$googleadsenseId);
		}	
		/**
		 * Function Google adsense delete.
		 * @param $deleteId
		 */
		public  function videogooglead_delete($googlead_ids){
			return $this->_wpdb->get_results("DELETE FROM " . $this->_videoadtable . " WHERE id IN($googlead_ids)"); 
		}
		/**
		 * Function count all googleadsense
		 */
		public function videogoogleadsensecount( $searchBtn , $searchValue ){
			$query =  'SELECT count(*) FROM ' . $this->_videoadtable;
			if( isset ( $searchBtn ) ) {
				$query .= ' WHERE googleadsense_details LIKE %s';
		    } 
			if( isset ( $searchBtn) ) { 
			 $query  =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' );
			} else {
				$query  =  $query;
			}
			$countgoogleadsense =  $this->_wpdb->get_var( $query );
			
			return $countgoogleadsense;
		}
		/**
		 * Function  publish the google Adsense 
		 * @param $google_adsense_id
		 * @param $status 
		 */
		public function videogoogleadsense_publish($googleadsenseId){
						return $this->_wpdb->update( $this->_videoadtable, $videoadData, array( 'id' => $googleadsenseId ), $videoadDataformat );
		}																		
	}																				
}																					
?>