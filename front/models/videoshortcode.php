<?php
/**  
 * Add Video Gallery to your website to showcase demos, Portfolio And Movie Trailers..
 * Get Video Players details , Related video for particular video player.
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
if ( class_exists( 'ContusShortcode' ) != true ) {												## checks the ContusShortcode class has been defined if starts

	class ContusShortcode {																	## ContusShortcode class starts

		public function __construct() {														
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_videosettingstable = $this->_wpdb->prefix . 'hdflvvideoshare_settings';
			$this->_videoinfotable     = $this->_wpdb->prefix . 'hdflvvideoshare';
		}																					
        /**
         * function  for get  more page
         * @return Ambigous <string, NULL>
         */
		public function get_more_pageid() {													
			$moreName = $this->_wpdb->get_var( 'SELECT ID FROM ' . $this->_wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" and post_status="publish" and post_type="page" limit 1' );
			return $moreName;
		}
                /**
                 * function  for video player details
                 * @global type $wpdb
                 * @param type $vid
                 * @return type
                 */
		public function get_video_detail( $vid ) {		   
			global $wpdb;
			$video_count = $this->_wpdb->get_row(
					'SELECT t1.vid,t5.ID,t5.display_name,t1.amazon_buckets,t1.description,t1.slug,t4.tags_name,t1.name,t1.post_date,t1.publish,t1.google_adsense,t1.google_adsense_value,t1.image,t1.file,t1.hitcount,t1.ratecount,t1.file_type,t1.embedcode,t1.rate,t2.playlist_id,t3.playlist_name'
					. ' FROM ' . $this->_videoinfotable . ' AS t1'
					. ' LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS t2'
					. ' ON t2.media_id = t1.vid'
					. ' LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS t3'
					. ' ON t3.pid = t2.playlist_id'
					. ' LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_tags AS t4'
					. ' ON t1.vid = t4.media_id'
					. ' LEFT JOIN ' . $wpdb->users . ' AS t5'
					. ' ON t1.member_id = t5.ID'
					. ' WHERE t1.publish=1 AND t3.is_publish=1 AND t1.vid="' . intval( $vid ) . '" LIMIT 1'
					);
			return $video_count;
		}
                /**
                 * function for get video description ,author inforamation,hitscount and rating control
                 * @global type $wpdb
                 * @param type $vid
                 * @return type
                 */
		public function get_playlist_detail( $vid) {											
			global $wpdb;
			$video_count = $this->_wpdb->get_results(
					'SELECT t3.playlist_name,t3.pid,t3.playlist_slugname'
					. ' FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS t3'
					. ' LEFT JOIN  ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS t2'
					. ' ON t3.pid = t2.playlist_id'
					. ' WHERE t3.is_publish=1 AND t2.media_id="' . intval( $vid ) . '"');
			return $video_count;
		}
		/**
		 * Function google Adsense details based video selected.
		 */
		public function get_googleads_detail($vid){
			global $wpdb;
			$google_addsense_details = $this->_wpdb->get_row("SELECT g.*,v.google_adsense FROM ".$wpdb->prefix."hdflvvideoshare_vgoogleadsense g
												  LEFT JOIN ".$wpdb->prefix."hdflvvideoshare v
					                              ON g.id = v.google_adsense_value");
			return $google_addsense_details;
			
		}
		
	}																					
}																						
?>