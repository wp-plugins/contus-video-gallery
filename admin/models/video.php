<?php
/**  
 * Video admin model file.
 * Save  video , multi publsh , delete video , feature video update , get gallery setting etc.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'VideoModel' ) != true ) {							## checks the VideoModel class has been defined if starts

	class VideoModel {													## VideoModel class starts

		public $_videoId;

		public function __construct() {									## contructor starts
			global $wpdb;
			$this->_wpdb		= $wpdb;
			$this->_videotable  = $this->_wpdb->prefix . 'hdflvvideoshare';
			$this->_posttable   = $this->_wpdb->prefix . 'posts';
			$this->_videosettingstable = $this->_wpdb->prefix . 'hdflvvideoshare_settings';
			$this->_videoId    = absint( filter_input( INPUT_GET, 'videoId' ) );
			$current_user      = wp_get_current_user();
			$this->member_id   = $current_user->ID;
		}																## contructor ends

		public function insert_video( $videoData ) {				## function for inserting video starts
			$post_id = $this->_wpdb->get_var( 'SELECT ID FROM ' . $this->_posttable . ' order by ID desc' );
			if ( $this->_wpdb->insert( $this->_videotable, $videoData ) ) {
				$last_insert_video_id = $this->_wpdb->insert_id;
				$post_content		  = '[hdvideo id=' . $this->_wpdb->insert_id . ']';
				$post_id			  = $post_id + 1;

				$postsData = array(
					'post_author'			=> $this->member_id,
					'post_date'				=> date( 'Y-m-d H:i:s' ),
					'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
					'post_content'			=> $post_content,
					'post_title'			=> $videoData['name'],
					'post_excerpt'			=> '',
					'post_status'			=> 'publish',
					'comment_status'		=> 'open',
					'ping_status'			=> 'closed',
					'post_password'			=> '',
					'post_name'				=> sanitize_title($videoData['name']),
					'to_ping'				=> '',
					'pinged'				=> '',
					'post_modified'			=> date( 'Y-m-d H:i:s' ),
					'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
					'post_content_filtered' => '',
					'post_parent'			=> 0,
					'menu_order'			=> '0',
					'post_type'				=> 'videogallery',
					'post_mime_type'		=> '',
					'comment_count'			=> '0',
				);
				//  Default  wordpress  method  for  post  add
				if(empty($this->_videoId)) {
					$post_ID = wp_insert_post( $postsData );
				}
				$guid = get_site_url() . '/?post_type=videogallery&#038;p=' . $post_ID;
				$this->_wpdb->update( $this->_posttable, array( 'guid' => $guid ), array( 'ID' => $post_ID ) );
				$this->_wpdb->update( $this->_videotable, array( 'slug' => $post_ID ), array( 'vid' => $last_insert_video_id ) );
				return $last_insert_video_id;
			}
		}																			## function for inserting video ends
		
		public function status_update( $videoId, $status, $feaStatus ) {			## function for updating status of playlist starts
			if ( isset( $status ) ) {
				$result = $this->_wpdb->update( $this->_videotable, array( 'publish' => $status ), array( 'vid' => $videoId ) );
			}
			if ( isset( $feaStatus ) ) {
				$result = $this->_wpdb->update( $this->_videotable, array( 'featured' => $feaStatus ), array( 'vid' => $videoId ) );
			}
			return $result;
		}																			## function for updating status of playlist ends
		
		public function video_update( $videoData, $videoId ) {				## function for updating video starts
			$this->_wpdb->update( $this->_videotable, $videoData, array( 'vid' => $videoId ) );
			$slug_id = $this->_wpdb->get_var( 'SELECT slug FROM ' . $this->_videotable . ' WHERE vid ='.$videoId );
			if ( !empty( $slug_id ) ) {
				$post_content = '[hdvideo id=' . $videoId . ']';

				$postsData = array(
					'post_author'			=> $this->member_id,
					'post_date'				=> date( 'Y-m-d H:i:s' ),
					'post_date_gmt'			=> date( 'Y-m-d H:i:s' ),
					'post_content'			=> $post_content,
					'post_title'			=> $videoData['name'],
					'post_excerpt'			=> '',
					'post_status'			=> 'publish',
					'comment_status'		=> 'open',
					'ping_status'			=> 'closed',
					'post_password'			=> '',
					'to_ping'				=> '',
					'pinged'				=> '',
					'post_modified'			=> date( 'Y-m-d H:i:s' ),
					'post_modified_gmt'		=> date( 'Y-m-d H:i:s' ),
					'post_content_filtered' => '',
					'post_parent'			=> 0,
					'menu_order'			=> '0',
					'post_type'				=> 'videogallery',
					'post_mime_type'		=> '',
					'comment_count'			=> '0',
					'ID'                    => $slug_id	
				);
				wp_update_post($postsData);
				$guid = get_site_url() . '/?post_type=videogallery&#038;p=' . $slug_id;
				$this->_wpdb->update( $this->_posttable, array( 'guid' => $guid ), array( 'ID' => $slug_id ) );
				$this->_wpdb->update( $this->_videotable, array( 'slug' => $slug_id ), array( 'vid' => $videoId ) );
			} 
			return;
		}																							## function for updating video ends
		
		function get_current_user_role() {
			global $current_user;
			get_currentuserinfo();
			$user_roles = $current_user->roles;
			$user_role  = array_shift( $user_roles );
			return $user_role;
		}

		public function get_videodata( $searchValue, $searchBtn, $order, $orderDirection ) {		## function for getting search videos starts
			global $wpdb;
			$user_role    = $this->get_current_user_role();
			$current_user = wp_get_current_user();
			$gallerySettings	 = $this->get_settingsdata();
			$player_colors 		 = unserialize($gallerySettings->player_colors);
			$user_allowed_method = explode(',',$player_colors['user_allowed_method']);
			$file_type = '';
			if( in_array('c',$user_allowed_method )) {
				$file_type = 1;
			}
			if( in_array('y',$user_allowed_method ) ) {
				if( $file_type == '' ) {
					$file_type =  2 ;
				} else  {
					$file_type = $file_type.',2';
				}
			}
			if( in_array('embed',$user_allowed_method ) ) {
				if( $file_type == '' ) {
					$file_type =  5 ;
				} else  {
					$file_type = $file_type.',5';
				}
			}if( in_array('url',$user_allowed_method ) ) {
				if( $file_type == '' ) {
					$file_type =  3 ;
				} else  {
					$file_type = $file_type.',3';
				}
			}if( in_array('rmtp',$user_allowed_method ) ) {
				if( $file_type == '' ) {
					$file_type =  4 ;
				} else  {
					$file_type = $file_type.',4';
				}
			}
			$pagenum = absint( filter_input(INPUT_GET , 'pagenum' ) );
			if( empty ( $pagenum ) ) {
				$pagenum =1;
			}
			$orderFilterlimit = filter_input( INPUT_GET, 'filter' );
				
			$query = 'SELECT DISTINCT ( a.vid ) FROM ' . $this->_videotable . ' a
					LEFT JOIN ' . $wpdb->users . ' u
					ON u.ID=a.member_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play p
					ON p.media_id=a.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist pl
					ON pl.pid=p.playlist_id WHERE pl.is_publish=1';
				
			if ( isset( $searchBtn ) ) {
				$query .= ' AND ( a.name LIKE %s OR a.description LIKE %s )';
			}
			if ( $user_role != 'administrator' ) {
				$query .= ' AND a.member_id=%d AND a.file_type IN('.$file_type.')';
			}
			if ( ! isset( $orderDirection ) ) {
				$query  .= ' ORDER BY '.$order.' DESC';
			}
			else{
				$query  .= ' ORDER BY '.$order.' '.$orderDirection;
			}
				
			if( isset( $searchBtn ) && $user_role !='administrator' ) {
				$query =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' ,'%'.$searchValue.'%' ,$current_user->ID );
			} else if ( $user_role !='administrator' && !isset( $searchBtn )  ) {
				$query =  $this->_wpdb->prepare($query , $current_user->ID);
			} else if ( isset ( $searchBtn ) ) {
				$query =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' ,'%'.$searchValue.'%' );
			}else {
				$query = $query;
			}
			$total = count( $this->_wpdb->get_results( $query ) );
			if ( ! empty( $orderFilterlimit ) && $orderFilterlimit !== 'all' ) {
				$limit = $orderFilterlimit;
			} else if ( $orderFilterlimit === 'all' ) {
				$limit = $total;
			} else {
				$limit = 20;
			}
			$offset = ( $pagenum - 1 ) * $limit;
			$query  = 'SELECT DISTINCT ( a.vid ),a.*,u.display_name FROM ' . $this->_videotable . ' a
					LEFT JOIN ' . $wpdb->users . ' u
					ON u.ID=a.member_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play p
					ON p.media_id=a.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist pl
					ON pl.pid=p.playlist_id WHERE pl.is_publish=1 ';
				
			if ( isset( $searchBtn ) ) {
				$query .= ' AND ( a.name LIKE %s OR a.description LIKE %s )';
			}
			if( $user_role != 'administrator' ) {
				$query .= ' AND a.member_id =%d AND a.file_type IN('.$file_type.')';
			}
			if ( ! isset( $orderDirection ) ) {
				$query  .= ' ORDER BY '. $order.' DESC';
			}
			else{
				$query  .= ' ORDER BY '.$order.' '.$orderDirection;
			}
			$query .=	' LIMIT '.$offset.', '.$limit;
				
			if( isset( $searchBtn ) && $user_role !='administrator' ) {
				$query =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' ,'%'.$searchValue.'%' ,$current_user->ID );
			} else if ( $user_role !='administrator' && !isset( $searchBtn )  ) {
				$query =  $this->_wpdb->prepare($query , $current_user->ID);
			} else if ( isset ( $searchBtn ) ) {
				$query =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' ,'%'.$searchValue.'%' );
			}else {
				$query = $query;
			}
			return $this->_wpdb->get_results( $query );
		}

		public function get_playlist_detail( $vid ) {					## function for getting Tag name starts
			global $wpdb;
			$video_count = $this->_wpdb->get_results(
					'SELECT t3.playlist_name,t3.pid'
					. ' FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS t3'
					. ' LEFT JOIN  ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS t2'
					. ' ON t3.pid = t2.playlist_id'
					. ' WHERE t3.is_publish=1 AND t2.media_id="' . intval( $vid ) . '"'
					);
			return $video_count;
		}

		public function video_edit( $videoId ) {
			global $current_user, $wpdb;
			if ( isset( $videoId ) && ! current_user_can( 'manage_options' ) ) {
				$user_id     = $current_user->ID;
				$video_count = $wpdb->get_var( 'SELECT count( * ) FROM '.$this->_videotable.' WHERE vid = '.$videoId.' and member_id = '.$user_id );
				if ( $video_count == 0 ) {
					wp_die( __( 'You do not have permission to access this page.' ) );
				}
			}															## function for getting single video starts
			return $this->_wpdb->get_row( 'SELECT a.*,b.tags_name FROM ' . $this->_videotable . ' as a LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_tags b ON b.media_id=a.vid WHERE a.vid ='.$videoId );
		}																## function for getting single video ends
		
		public function video_count( $searchValue, $searchBtn ) {		## function for getting single video starts
			global $wpdb;
			$where = '';
			$user_role    = $this->get_current_user_role();
			$current_user = wp_get_current_user();
			$query = 'SELECT DISTINCT ( a.vid ) FROM ' . $this->_videotable . ' a
					LEFT JOIN ' . $wpdb->users . ' u
					ON u.ID=a.member_id
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play p
					ON p.media_id=a.vid
					LEFT JOIN ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist pl
					ON pl.pid=p.playlist_id WHERE pl.is_publish=1';
			if ( isset( $searchBtn ) ) {
				$query .= ' AND ( name LIKE %s OR description LIKE %s  )';
			}
		    if ( $user_role != 'administrator' ) {
					$query .= ' AND member_id=%d';
			} 
			
			if( isset ( $searchBtn ) && $user_role !='administrator' ) {
			 	$query =  $this->_wpdb ->prepare($query ,  '%'.$searchValue.'%' , '%s'.$searchValue.'%' ,$current_user->ID); 
		    } else if ( $user_role !='administrator' && !isset( $searchBtn )  ) {
				$query =  $this->_wpdb->prepare($query , $current_user->ID );
			} else if ( isset ( $searchBtn ) ) {
				$query =  $this->_wpdb->prepare($query , '%'.$searchValue.'%' ,'%'.$searchValue.'%' );
			}else {
				$query = $query;
			}			 
			$result = $this->_wpdb->get_results($query );
			return count($result) ;
		}																## function for getting single video ends
       /**
        * Function for deleting video.
        * @param unknown $videoId
        * @return Ambigous <number, false, boolean, mixed>
        */
		public function video_delete($videoId)
        {
			$slug = $this->_wpdb->get_col("SELECT slug FROM ".$this->_videotable."  WHERE vid IN ("."$videoId".")");
			$slugid = implode(",", $slug);
			$query = "SELECT file,file_type,image,opimage,srtfile1,srtfile2 FROM " . $this->_videotable ." WHERE vid IN ("."$videoId".")";
            $file_details = $this->_wpdb->get_results($query);
			foreach($file_details as $file_detail){
				if($file_detail->file_type == 2){
					$wp_upload_dir = wp_upload_dir();
					$image_path =  $wp_upload_dir['basedir'] . '/videogallery/';
					unlink($image_path . $file_detail->file);
					if(!empty($file_detail->image))
					unlink($image_path . $file_detail->image);
					if(!empty($file_detail->opimage))
					unlink($image_path . $file_detail->opimage);
				}
				if($file_detail->strfile1){
					$wp_upload_dir = wp_upload_dir();
					$image_path =  $wp_upload_dir['basedir'] . '/videogallery/';
					unlink($image_path . $file_detail->strfile1);
				}
				if($file_detail->strfile2){
					$wp_upload_dir = wp_upload_dir();
					$image_path =  $wp_upload_dir['basedir'] . '/videogallery/';
					unlink($image_path . $file_detail->strfile2);
				}
			}
			
            $query = "DELETE FROM ".$this->_videotable."  WHERE vid IN ("."$videoId".")";
            $this->_wpdb->query($query);
            $query = "DELETE FROM ".$this->_posttable."  WHERE ID IN ("."$slugid".")";
            return $this->_wpdb->query($query);
        }
		/**
		 * Function for  multiple video featured 
		 * @param unknown $videoId
		 */																
		public function video_multifeatured($videoId){
			$query  = 'UPDATE ' . $this->_videotable . ' SET `featured`=1 WHERE vid IN (' . $videoId . ')';
			return $this->_wpdb->query( $query );
		}
		/**
		 * Function for  multiple video publish
		 * @param unknown $videoId
		 */
		public function video_multipublish($videoId){
			$query  = 'UPDATE ' . $this->_videotable . ' SET `publish`=1 WHERE vid IN (' . $videoId . ')';
			return $this->_wpdb->query( $query );
		}
		/**
		 * Function for  multiple video unfeatured
		 * @param unknown $videoId
		 */
		public function video_multiunfeatured($videoId){
			$query  = 'UPDATE ' . $this->_videotable . ' SET `featured`=0 WHERE vid IN (' . $videoId . ')';
			return $this->_wpdb->query( $query );
		}
		/**
		 * Function for  multiple video unpublish
		 * @param unknown $videoId
		 */
		public function video_multiunpublish($videoId){
			$query  = 'UPDATE ' . $this->_videotable . ' SET `publish`=0 WHERE vid IN (' . $videoId . ')';
			return $this->_wpdb->query( $query );
		}
		/**
		 * Video Gallery setting datas.
		 * @return Ambigous <mixed, NULL, multitype:>
		 */
		public function get_settingsdata() {							
			$query = 'SELECT * FROM ' . $this->_videosettingstable . ' WHERE settings_id = 1';
			return $this->_wpdb->get_row( $query );
		}		
		/**
		 * Function  for  default order for  videos
		 */
		public function get_order_details(){
			$query = 'SELECT player_colors FROM ' . $this->_videosettingstable . ' WHERE settings_id = 1';
			$setting = $this->_wpdb->get_var( $query );
			$settings =  unserialize($setting);
			$default_order = $settings['recentvideo_order'];
			return $default_order; 
		}														
	}																	
}																	