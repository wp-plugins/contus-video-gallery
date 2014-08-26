<?php
/** 
 * Video home page controller file.
 *
 * @category   Apptha
 * @package    Contus Video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

include_once( $frontModelPath . 'videohome.php' );		 // including ContusVideo model file for get database information.
if ( class_exists( 'ContusVideoController' ) != true ) {

	class ContusVideoController extends ContusVideo {

		public function __construct() {												
			parent::__construct();
		}																			
        /**
         * Function getting settings data.
         */
		function settings_data() {													
			return $this->get_settingsdata();
		}																			
 		/**
 		 * getting videos data function
 		 */
		function videos_data() {													
			return $this->get_videosdata();
		}																			
        /**
         * function  get more page id.
         * @return $morepage.
         */
		function more_pageid() {													
			return $this->get_more_pageid();
		}																			
        /**
         * function get  video count
         * @return type int.
         */
		function video_count() {													
			return $this->get_video_count();
		}																		
        /**
         * function get  player tag name.
         * @param unknown $vid
         * @return type string tagname
         */
		function tag_detail( $vid ) {												
			return $this->get_tag_name( $vid );
		}	
		/**
		 * Function get display number of related  videos  
		 */												
		function related_video_count(){
			$related_video_count = $this->get_related_video_count();
			if($related_video_count){
				$related_video_count = $related_video_count;	
			} else {
				$related_video_count = 10;
			}
			return $related_video_count;
		}						
       /**
        * function get playlists video
        * @param unknown $thumImageorder
        * @param unknown $dataLimit
        */
		function home_catthumbdata( $thumImageorder, $dataLimit ) {					
			return $this->get_home_catthumbdata( $thumImageorder, $dataLimit );
		}
       /**
        * function get home  thumb data.
        * @param unknown $thumImageorder
        * @param unknown $where
        * @param unknown $dataLimit
        */
		function home_thumbdata( $thumImageorder, $where, $dataLimit ) {			
			return $this->get_thumdata( $thumImageorder, $where, $dataLimit );
		}

		/**
		 * get count of home thumb data
		 * @param unknown $thumImageorder
		 * @param unknown $where
		 * @return number
		 */
		function countof_home_thumbdata( $thumImageorder, $where ) {				
			return $this->get_countof_thumdata( $thumImageorder, $where );
		}
        /**
         * function player related video.
         * @param unknown $getVid
         * @param unknown $thumImageorder
         * @param unknown $where
         * @param unknown $dataLimit
         */
		function home_playxmldata( $getVid, $thumImageorder, $where, $dataLimit ) { 
			return $this->get_playxmldata( $getVid, $thumImageorder, $where, $dataLimit );
		}
        /**
         * function get home categories thumb data.
         * @param unknown $pagenum
         * @param unknown $dataLimit
         * @return Ambigous <type, mixed, NULL, multitype:, multitype:multitype: , multitype:Ambigous <multitype:, NULL> >
         */
		function home_categoriesthumbdata( $pagenum, $dataLimit ) {					
			return $this->get_categoriesthumdata( $pagenum, $dataLimit );
		}
        /**
         * function get count of video category
         * @return type number.
         */
		function countof_videocategories() {										
			return $this->get_countof_videocategories();
		}
        /**
         * home player data.
         * @return  type mixed 
         */
		function home_playerdata() {												
			return $this->get_singlevideodata();
		}
        /**
         * 
         */
		function home_featuredvideodata() {										
			return $this->get_featuredvideodata();
		}
      /**
       * 
       * @return type mixed
       */
		function home_featuredvideodata_banner() {									
			return $this->get_featuredvideodata_banner();
		}
       /**
        * function video detail /info under video player
        * @param unknown $vid
        */
		function video_detail( $vid ) {												
			return $this->get_video_detail( $vid );
		}
	}																				
} else {
	echo 'class contusVideo already exists';
}
include_once( $frontViewPath . 'videohome.php' );									// including ContusVideo view file for get database information.
?>