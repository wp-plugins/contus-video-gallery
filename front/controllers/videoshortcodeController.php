<?php
/**  
 * Video detail and short tags controller file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8.1
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

include_once( $frontModelPath . 'videoshortcode.php' );									
if ( class_exists( 'ContusVideoShortcodeController' ) != true ) {

	class ContusVideoShortcodeController extends ContusShortcode {

		public function __construct() {													
			parent::__construct();
		}																				

		function more_pageid() {														
			return $this->get_more_pageid();
		}																				
        /**
         * function get the  video detail.
         * @param unknown $vid
         * @return type mixed
         */
		function video_detail( $vid ) {													
			return $this->get_video_detail( $vid );
		}																				
        /**
         * get videos playlist details.
         * @param unknown $vid
         */
		function playlist_detail( $vid ) {												
			return $this->get_playlist_detail( $vid);
		}	
		/**
		 * Function  google adsense detail for  video.
		 */
		public function get_video_google_adsense_details($vid){
			return $this->get_googleads_detail($vid);
		}
		/**
		 * function current user detail
		 */
		public function get_current_user_email() {
			global $current_user;
			$user_email = $current_user->user_email;
			return $user_email;
		}
	}																					
} else {
	echo 'class contusVideo already exists';
}
include_once( $frontViewPath . 'videoshortcode.php' );									// including ContusVideo shortcode view file.
?>