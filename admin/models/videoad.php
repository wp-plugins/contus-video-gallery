<?php
 /**  
 * Video videoad model file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'VideoadModel' ) != true ) {										## checks the VideoadModel class has been defined if starts

	class VideoadModel {															## VideoadModel class starts

		public $_videoadId;

		public function __construct() {												## contructor starts
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_videoadtable = $this->_wpdb->prefix . 'hdflvvideoshare_vgads';
			$this->_videoadId    = absint( filter_input( INPUT_GET, 'videoadId' ) );
		}																			## contructor ends

		public function insert_videoad( $videoadData, $videoadDataformat ) {		## function for inserting video starts
			if ( $this->_wpdb->insert( $this->_videoadtable, $videoadData, $videoadDataformat ) ) {
				return $this->_wpdb->insert_id;
			}
		}																			## function for inserting video ends

		public function videoad_update( $videoadData, $videoadDataformat, $videoadId ) {		## function for updating video starts
			return $this->_wpdb->update( $this->_videoadtable, $videoadData, array( 'ads_id' => $videoadId ), $videoadDataformat );
		}																			## function for updating video ends

		public function status_update( $videoadId, $status ) {						## function for updating status of video starts
			return $this->_wpdb->update( $this->_videoadtable, array( 'publish' => $status ), array( 'ads_id' => $videoadId ) );
		}																			## function for updating status of video ends

		public function get_videoaddata( $searchValue, $searchBtn, $order, $orderDirection ) {	## function for getting search videos starts
			$where   = '';
			$pagenum =  filter_input(INPUT_GET, 'pagenum');
			if( empty ( $pagenum ) ){
				$pagenum = 1; 
			}
			$limit   = 20;
			$offset  = ( $pagenum - 1 ) * $limit;
		    $query = 'SELECT * FROM ' . $this->_videoadtable ;
			
			if ( isset( $searchBtn ) ) {
				$query .= ' WHERE title LIKE %s';
			}
			
			if ( ! isset( $orderDirection ) ) {
				$query .= ' ORDER BY '. $order .' DESC';
			} else {
				$query .= ' ORDER BY '.$order. ' ' . $orderDirection;  
			}
			$query .= ' LIMIT ' .$offset.', '.$limit;
			if( isset( $searchBtn ) ) {
				$query =  $this->_wpdb->prepare( $query , '%'.$searchValue.'%' );
			} else {
				$query =   $query;
			}
			return $this->_wpdb->get_results( $query );
		}																			## function for getting search videos ends

		public function videoad_count( $searchValue, $searchBtn ) {					## function for getting single video starts
			$query ='SELECT COUNT( `ads_id` ) FROM ' . $this->_videoadtable;
			if ( isset( $searchBtn ) ) {
				$query .= ' WHERE title LIKE %s';
			}
			if( isset ( $searchBtn ) ) {
				$query = $this->_wpdb->prepare( $query  , '%'.$searchValue.'%' );
			} else {
				$query =  $query;
			}
			
			return 	$this->_wpdb->get_var( $query ) ;
		}

		public function videoad_edit( $videoadId ) {								## function for getting single video starts
			return $this->_wpdb->get_row( 'SELECT * FROM ' . $this->_videoadtable . ' WHERE ads_id ='.$videoadId );
		}																			## function for getting single video ends

		public function videoad_delete( $videoadId ) {								## function for deleting video starts
			$query = 'DELETE FROM ' . $this->_videoadtable . '  WHERE ads_id IN ( ' . $videoadId . ' )';
			return $this->_wpdb->query( $query );
		}																			## function for deleting video ends
	}																				## VideoadModel class ends
}																					## checks the VideoadModel class has been defined if ends
?>