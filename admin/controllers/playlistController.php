<?php
/**  
 * Video playlist admin controller file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */


include_once( $adminModelPath . 'playlist.php' );				// including Playlist model file for get database information.

if ( class_exists( 'PlaylistController' ) != true ) {			// checks if the PlaylistController class has been defined starts

	class PlaylistController extends PlaylistModel {		// VideoadController class starts

		public $_status;
		public $_msg;
		public $_search;
		public $_playlistsearchQuery;
		public $_addnewPlaylist;
		public $_searchBtn;
		public $_update;
		public $_add;
		public $_del;
		public $_orderDirection;
		public $_orderBy;

		public function __construct() {						// contructor starts
			parent::__construct();
			$this->_playlistsearchQuery = filter_input( INPUT_POST, 'PlaylistssearchQuery' );
			$this->_addnewPlaylist		= filter_input( INPUT_POST, 'playlistadd' );
			$this->_status				= filter_input( INPUT_GET, 'status' );
			$this->_searchBtn			= filter_input( INPUT_POST, 'playlistsearchbtn' );
			$this->_update				= filter_input( INPUT_GET, 'update' );
			$this->_add					= filter_input( INPUT_GET, 'add' );
			$this->_del					= filter_input( INPUT_GET, 'del' );
			$this->_orderDirection		= filter_input( INPUT_GET, 'order' );
			$this->_orderBy				= filter_input( INPUT_GET, 'orderby' );
		}

		/** 
		 * Function for add/ update playlist data.
		 * 
		 */
		public function add_playlist() {					
			global $wpdb;
			if ( isset( $this->_status ) ) {					
				$this->status_update( $this->_playListId, $this->_status );
			} 

			if ( isset( $this->_addnewPlaylist ) ) {
				$playlistName	   = filter_input( INPUT_POST, 'playlistname' );
				$playlist_slugname = sanitize_title( $playlistName );
				$playlistPublish   = filter_input( INPUT_POST, 'ispublish' );
				$playlist_slug     =  $this->_wpdb->get_var( 'SELECT COUNT(playlist_slugname) FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist WHERE playlist_slugname LIKE "'.$playlist_slugname.'%"');
			
				if($playlist_slug > 0 ){
					$playlist_slugname =  $playlist_slugname.'-'.intval($playlist_slug+1);
				}
				$playlsitData = array(
					'playlist_name'		=> $playlistName,
					'playlist_slugname' => $playlist_slugname,
					'is_publish'		=> $playlistPublish
				);

				if (  $this->_playListId  ) {			
					$updateflag = $this->playlist_update( $playlsitData, $this->_playListId );
					if ( $updateflag ) {
						$this->admin_redirect( 'admin.php?page=newplaylist&playlistId=' . $this->_playListId . '&update=1' );
					} else {
						$this->admin_redirect( 'admin.php?page=newplaylist&playlistId=' . $this->_playListId . '&update=0' );
					}
				}											
				else {										
					$ordering = $wpdb->get_var( 'SELECT COUNT( pid ) FROM ' . $wpdb->prefix . 'hdflvvideoshare_playlist' );
					$ordering = $wpdb->get_var( "SELECT MAX(playlist_order) FROM ".$wpdb->prefix."hdflvvideoshare_playlist" );
					$playlsitData['playlist_order'] = $ordering + 1;
					$addflag = $this->insert_playlist( $playlsitData );

					if ( ! $addflag ) {
						$this->admin_redirect( 'admin.php?page=playlist&add=0' );
					} else {
						$this->admin_redirect( 'admin.php?page=playlist&add=1' );
					}
				}											
			}
		}

		/** 
		 * Admin redirection url 
		 */
		public function admin_redirect( $url ) {				
			echo '<script>window.open( "' . $url . '","_top",false )</script>';
		}

		/**
		 * Edit  data fields get  function
		 */
		public function playlist_data() {					
			$orderBy = array( 'id', 'title', 'desc', 'publish', 'sorder' );
			$order   = 'id';

			if ( isset( $this->_orderBy ) && in_array( $this->_orderBy, $orderBy ) ) {
				$order = $this->_orderBy;
			}

			switch ( $order ) {
				case 'id':
					$order = 'pid';
					break;

				case 'title':
					$order = 'playlist_name';
					break;
				case 'publish':
					$order = 'is_publish';
					break;

				case 'sorder':
					$order = 'playlist_order';
					break;

				default:
					$order = 'pid';
			}
			return $this->get_playlsitdata( $this->_playlistsearchQuery, $this->_searchBtn, $order, $this->_orderDirection );
		}
		/**
		 * Displaying database message function starts
		 * @return multitype:string
		 */		
		public function get_message() {						
			$message_div = '';
			if ( isset( $this->_update ) && $this->_update == '1' ) {
				$this->_msg  = 'Category Updated Successfully ...';
				$message_div = 'addcategory';
			} else if ( $this->_update == '0' ) {
				$this->_msg  = 'Category Not Updated  Successfully ...';
				$message_div = 'addcategory';
			}

			if ( isset( $this->_add ) && $this->_add == '1' ) {
				$this->_msg  = 'Category Added Successfully ...';
				$message_div = 'addcategory';
			}

			if ( isset( $this->_del ) && $this->_del == '1' ) {
				$this->_msg  = 'Category Deleted Successfully ...';
				$message_div = 'category';
			}
			if ( isset( $this->_status ) && $this->_status == '1' ) {
				$this->_msg  = 'Category Published Successfully ...';
				$message_div = 'category';
			} else if ( $this->_status == '0' ) {
				$this->_msg  = 'Category Unpublished Successfully ...';
				$message_div = 'category';
			}
			$return_values = array( 0 => $this->_msg, 1 => $message_div );
			return $return_values;
		}
		/**
		 * function  delete the  playlist
		 */
		public function get_delete() {										
			$playlistApply		= filter_input( INPUT_POST, 'playlistapply' );
			$playlistActionup			= filter_input( INPUT_POST, 'playlistactionup' );
			$playlistActiondown = filter_input( INPUT_POST, 'playlistactiondown' );
			$playListcheckId				= filter_input( INPUT_POST, 'pid', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
			if ( isset( $playlistApply ) ) {									
				if ( $playlistActionup || $playlistActiondown == 'playlistdelete' ) {
					if ( is_array( $playListcheckId ) ) {						
						$playListId = implode( ',', $playListcheckId );
						$deleteflag = $this->playlist_delete( $playListId );
						if ( ! $deleteflag ) {
							$this->admin_redirect( 'admin.php?page=playlist&del=0' );
						} else {
							$this->admin_redirect( 'admin.php?page=playlist&del=1' );
						}
					}														
				}
			}																
		}
		
	}
																			
} 

$playlistOBJ	= new PlaylistController();									// creating object for VideoadController class
$playlistOBJ->add_playlist();
$playlistOBJ->get_delete();
$playListId		   = $playlistOBJ->_playListId;
$searchMsg		   = $playlistOBJ->_playlistsearchQuery;
$searchBtn		   = $playlistOBJ->_searchBtn;
$status            = $playlistOBJ->_status;
$update            = $playlistOBJ->_update;
$gridPlaylist   = $playlistOBJ->playlist_data();
$playlist_count = $playlistOBJ->playlist_count( $searchMsg, $searchBtn );
if ( ! empty ( $playListId ) ) {
	if(isset($_GET['status']) || isset($_GET['update']) ) {
		$playlistEdit = '';		
	}else {
		$playlistEdit = $playlistOBJ->playlist_edit( $playListId );
	}
} else {
	$playlistEdit = '';
}
$displayMsg = $playlistOBJ->get_message();
$adminPage  = filter_input( INPUT_GET, 'page' );
$adminPage  = filter_input( INPUT_GET, 'page' );
require_once( APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/playlist/playlist.php' );
?>