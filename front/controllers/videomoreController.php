<?php
/**  
 * Video more page controller file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

include_once( $frontModelPath . 'videomore.php' );								// including ContusVideomore model file for get database information.
if ( class_exists( 'ContusMoreController' ) != true ) {

	class ContusMoreController extends ContusMore {

		public function __construct() {											
			parent::__construct();
		}																		
        /**
         * function for get video gallery settings
         * @return mixed
         */
		function settings_data() {												
			return $this->get_settingsdata();
		}																		
        /**
         * function for get vidoe more page  
         * @return type int
         */
		function more_pageid() {												
			return $this->get_more_pageid();
		}																		
        /**
         * function for  get  video  count.
         */
		function video_count() {												
			return $this->get_video_count();
		}																		
        /**
         * function for the home thumb data.
         * @param unknown $thumImageorder
         * @param unknown $where
         * @param unknown $pagenum
         * @param unknown $dataLimit
         * @return type <mixed int>
         */
		function home_thumbdatamore( $thumImageorder, $where, $pagenum, $dataLimit ) { 
			return $this->get_thumdatamore( $thumImageorder, $where, $pagenum, $dataLimit );
		}
       /**
        * function get category read more thumb data
        * @param unknown $pagenum
        * @param unknown $dataLimit
        */
		function home_tagthumbdata( $pagenum, $dataLimit ) {				
			return $this->get_home_tagthumbdata( $pagenum, $dataLimit );
		}
		/**
		 * Function get tag thumb data
		 */
		function home_categoriesthumbdata( $pagenum, $dataLimit ) {
			return $this->get_categoriesthumdata( $pagenum, $dataLimit );
		}
		
        /**
         * search page thumb  data  
         * @param unknown $thumImageorder
         * @param unknown $pagenum
         * @param unknown $dataLimit
         */
		function home_searchthumbdata( $thumImageorder, $pagenum, $dataLimit ) {	
			return $this->get_searchthumbdata( $thumImageorder, $pagenum, $dataLimit );
		}
        /**
         * function get video count 
         * @param unknown $playid
         * @param unknown $userid
         * @param unknown $thumImageorder
         * @param unknown $where
         */
		function countof_videos( $playid, $userid, $thumImageorder, $where ) {	
			return $this->get_countof_videos( $playid, $userid, $thumImageorder, $where );
		}
        /**
         * category video  count  for pagination.
         * @return type int
         */
		function countof_videocategories() {									
			return $this->get_countof_videocategories();
		}
		/**
		 * Search keyword based video count for pagination.
		 * @return type int
		 */
		function countof_videosearch( $thumImageorder ) {							
			return $this->get_countof_videosearch( $thumImageorder );
		}

	}																			
} else {
	echo 'class contusMore already exists';
}
include_once( $frontViewPath . 'videomore.php' );									// including ContusVideomore view file.
?>