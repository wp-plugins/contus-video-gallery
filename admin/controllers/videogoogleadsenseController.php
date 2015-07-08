<?php
/**
 * Video Google Adsense Controller.
 * 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8.1
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
include_once( $adminModelPath . 'videogoogleadsense.php' );							
if ( class_exists( 'VideoadController' ) != true ) {			

	class VideogoogleadsenseController extends VideogoogleadsenseModel {	
        public $_status;
		public $_msg;
		public $_search;
		public $_videoadsearchQuery;
		public $_addnewVideoad;
		public $_searchBtn;
		public $_update;
		public $_add;
		public $_del;
		public $_publish;
		public $_orderDirection;
		public $_orderBy;
		public $_settingsUpdate;
		public $_videogoogleadsenseId;
		public $_videogoogleadupdateId;

		public function __construct() {						## contructor starts
			parent::__construct();
			$this->_addnewVideoad		= filter_input( INPUT_POST, 'videoadsadd' );
			$this->_status				= filter_input( INPUT_GET, 'status' );
			$this->_searchBtn			= filter_input( INPUT_POST, 'videoadsearchbtn' );
			$this->_videoadsearchQuery	= filter_input( INPUT_POST, 'videoadssearchQuery' );
			$this->_update				= filter_input( INPUT_GET, 'update' );
			$this->_add					= filter_input( INPUT_GET, 'add' );
			$this->_del					= filter_input( INPUT_GET, 'del' );
			$this->_orderDirection      = filter_input( INPUT_GET, 'order' );
			$this->_orderBy				= filter_input( INPUT_GET, 'orderby' );
			$this->_publish             = filter_input( INPUT_GET , 'publish');
			$this->_settingsUpdate      = filter_input( INPUT_POST, 'updatebutton' );
			$this->_videogoogleadsenseId= absint( filter_input( INPUT_GET, 'videogoogleadId' ) );
			$this->_videogoogleadupdateId=absint(filter_input( INPUT_POST, 'videogoogleadId' ) );				
			$this->_search              = filter_input(INPUT_POST,'videoadssearchQuery');
				
		}													## contructor ends
		/**
		 * Function redirect the page . 
		 */
		public function admin_redirect( $url ) {							
			echo '<script>window.open( "' . $url . '","_top",false )</script>';
		}
        /**
         * function get video google adsense details. 
         */
		public function videoad_data() {
			return $this->videogoogleadsense_edit($this->_videogoogleadsenseId);
		}

		/**
		 * function message.
		 */
		public function get_message() {								
			if ( isset( $this->_update ) && $this->_update == '1' ) {
				$this->_msg = 'Video Google AdSense Updated Successfully ...';
			} else if ( $this->_update == '0' ) {
				$this->_msg = 'Video Google AdSense Not Updated  Successfully ...';
			} else if($this->_add == '0'){
				$this->_msg ="Video Google AdSense Not Added Successfully";
			}else if($this->_add == '1'){
				$this->_msg ="Video Google AdSense Added Successfully";
			}else if($this->_del == '1'){
				$this->_msg ="Video Google AdSense Deleted Successfully";
			}else if($this->_del == '0'){
				$this->_msg ="Video Google AdSense Not Deleted Successfully";
			}else if($this->_publish == 2){
				$this->_msg ="Video Google AdSense Published Successfully";
			}else if($this->_publish == 3){
				$this->_msg ="Video Google AdSense Not Published Successfully";
			}else if($this->_publish == 1){
				$this->_msg ="Video Google AdSense Unpublish published Successfully";
			}else if( isset( $this->_publish ) && $this->_publish == 0){
				$this->_msg ="Video Google AdSense Not Unpublished Successfully";
			}
			return $this->_msg;
		}
		/**
		 * Function add the video google adsense details
		 */
		public function insert_googleadsense(){
			if($this->_settingsUpdate){
				$googleadsense =  filter_input(INPUT_POST ,'googleadsense_code');
				$googleadsensestaus =  filter_input(INPUT_POST ,'alway_open');
				$googleadsenseshowtime = filter_input(INPUT_POST ,'adsense_show_second');
				$googleadsensereopen = filter_input(INPUT_POST ,'reopen');
				$adsense_reopen_second = filter_input(INPUT_POST ,'adsense_reopen_second');
				$publish = filter_input(INPUT_POST ,'status');
				$google_adsense_title = filter_input(INPUT_POST, 'googleadsense_title');				
				$videoadData =  array(
						'googleadsense_code' =>$googleadsense,
						'publish'	         =>$publish,
						'adsense_option'     =>$googleadsensestaus,
						'adsense_reopen'     =>$googleadsensereopen,
						'adsense_reopen_time'=>$adsense_reopen_second,
						'adsenseshow_time'   =>$googleadsenseshowtime,
						'googleadsense_title'=>$google_adsense_title
				);
				$videogoogleadData = serialize($videoadData); 
				$videoData =array('googleadsense_details'=>$videogoogleadData); 				
				$video_data_format = array('%s');
				$update = $this->videogoogleadsense_insert($videoData);
				if($update){
					$this->admin_redirect( 'admin.php?page=googleadsense&add=1' );
					exit;
				}else{
					$this->admin_redirect( 'admin.php?page=googleadsense&add=0' );
					exit;
				}
			}
		}
		/**
		 * Function update  the detail of  the  googleadsense details.
		 */
		public function update_googleadsense(){
			if($this->_settingsUpdate){
				$googleadsense =  filter_input(INPUT_POST ,'googleadsense_code');
				$googleadsensestaus =  filter_input(INPUT_POST ,'alway_open');
				$googleadsenseshowtime = filter_input(INPUT_POST ,'adsense_show_second');
				$googleadsensereopen = filter_input(INPUT_POST ,'reopen');
				$adsense_reopen_second = filter_input(INPUT_POST ,'adsense_reopen_second');
				$publish = filter_input(INPUT_POST ,'status');
				$videogoogleadsenseId = filter_input( INPUT_POST, 'videogoogleadId' );
				$google_adsense_title = filter_input(INPUT_POST, 'googleadsense_title');
				$videoadData =  array(
						'googleadsense_code' =>$googleadsense,
						'publish'	         =>$publish,
						'adsense_option'     =>$googleadsensestaus,
						'adsense_reopen'     =>$googleadsensereopen,
						'adsense_reopen_time'=>$adsense_reopen_second,
						'adsenseshow_time'   =>$googleadsenseshowtime,
						'googleadsense_title'=>$google_adsense_title
				);
				$videogoogleadData = serialize($videoadData); 
				$videoadData =array('googleadsense_details'=>$videogoogleadData); 				
				$video_data_format = array('%s');
			    $update = $this->videogoogleadsense_update($videogoogleadsenseId,$videoadData,$video_data_format);
				if($update){
					$this->admin_redirect( 'admin.php?page=googleadsense&update=1' );
					exit;
				}else{
					$this->admin_redirect( 'admin.php?page=googleadsense&update=1' );
					exit;
				}
		 }
		}
		public function  googleadsense_publish($googleadsenseId,$status){
				$details = $this->videogoogleadsense_edit($googleadsenseId);
			    $serialize = unserialize($details->googleadsense_details);
			    $googleadsense_code = $serialize['googleadsense_code'];
			    $googleadsense_title = $serialize['googleadsense_title'];
			    $googleadsense_option  = $serialize['adsense_option'];
			    $adsense_reopen        = $serialize['adsense_reopen'];
			    $adsense_reopen_time   = $serialize['adsense_reopen_time'];
			    $adsenseshow_time      = $serialize['adsenseshow_time']; 
			    $status                = $status;
			    $videoadData =  array(
			    		'googleadsense_code' =>$googleadsense_code,
			    		'publish'	         =>$status,
			    		'adsense_option'     =>$googleadsense_option,
			    		'adsense_reopen'     =>$adsense_reopen,
			    		'adsense_reopen_time'=>$adsense_reopen_time,
			    		'adsenseshow_time'   =>$adsenseshow_time,
			    		'googleadsense_title'=>$googleadsense_title
			    );
			    $videogoogleadData = serialize($videoadData);
			    $googleadsenseData =array('googleadsense_details'=>$videogoogleadData);
			    $video_data_format = array('%s');
			    $update = $this->videogoogleadsense_update($googleadsenseId,$googleadsenseData,$video_data_format);
			    if($update){
			    	if($status == 0){
			    	    $this->admin_redirect( 'admin.php?page=googleadsense&publish=1' );
			    		exit;
			    	}else if($status==1){
			    		$this->admin_redirect( 'admin.php?page=googleadsense&publish=2' );
			    		exit;
			    	}
			    }else{
			        if($status == 0){
			    	    $this->admin_redirect( 'admin.php?page=googleadsense&publish=0' );
			    		exit;
			    	}else if($status==1){
			    		$this->admin_redirect( 'admin.php?page=googleadsense&publish=3' );
			    		exit;
			    	}
			    }
			    
			    
		}
		public function googleadsenses( ){
		    $orderBy = array( 'id','publish' );
			$order   = 'id';

			if ( isset( $this->_orderBy ) && in_array( $this->_orderBy, $orderBy ) ) {
				$order = $this->_orderBy;
			}

			switch ( $order ) {
				case 'id':
					$order = 'id';
					break;
				case 'publish':
					$order = 'googleadsense_details';
					break;
				case 'title':
					$order = 'googleadsense_details';
					break;	
				default:
					$order = 'id';
			}
			return $this->get_videogoogleadsenses($this->_videoadsearchQuery, $this->_searchBtn,$order,$this->_orderDirection);
		} 
		public function getgoogleadsensecount(){
		   return $this->videogoogleadsensecount($this->_videoadsearchQuery ,$this->_searchBtn ); 
		}
		public function deletegoogleadsense(){
			$videoadApply      = filter_input( INPUT_POST, 'videoadapply' );
			$videoadActionup   = filter_input( INPUT_POST, 'videogoogleadactionup' );
			$videoadActiondown = filter_input( INPUT_POST, 'videogoogleadactiondown' );
			$videogoogleadcheckId    = filter_input( INPUT_POST, 'videogooglead_id', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
			if ( isset( $videoadApply ) ) {												
				if ( $videoadActionup || $videoadActiondown == 'videogoogleaddelete' ) {	
					if (is_array( $videogoogleadcheckId ) ) {
						$videogoogleadId  = implode( ',', $videogoogleadcheckId );
						$deleteflag = $this->videogooglead_delete( $videogoogleadId );
						if ( ! $deleteflag ) {
							$this->admin_redirect( 'admin.php?page=googleadsense&del=0' );
						} else {
							$this->admin_redirect( 'admin.php?page=googleadsense&del=1' );
						}
					}
				}																	
			}
		}
																					
	}
																					
}																					
$videoadOBJ           = new VideogoogleadsenseController();
$searchMsg            = $videoadOBJ->_videoadsearchQuery;
$videogoogleadsenseId = $videoadOBJ->_videogoogleadsenseId;
$videogoogleadupdateId =$videoadOBJ->_videogoogleadupdateId;
$videogooglead_del    = $videoadOBJ->_del;
$status               = $videoadOBJ->_status;
if( $videogoogleadsenseId && (isset($status) && ( $status == 0  || $status == 1) ) ){
	$videoadOBJ->googleadsense_publish($videogoogleadsenseId,$status);
}
if($videogoogleadupdateId){
	$updateGoogleadsense  = $videoadOBJ->update_googleadsense();	
}else{
	$insert_adsense       = $videoadOBJ->insert_googleadsense();
}
$gridVideoad          = $videoadOBJ->googleadsenses();
if( $videogoogleadsenseId ){
   $editGoogleAdsense = $videoadOBJ->videoad_data( $videogoogleadsenseId );
}else{
   $editGoogleAdsense ='';
}
$videoadOBJ->deletegoogleadsense();
$displayMsg = $videoadOBJ->get_message();
$videoad_count = $videoadOBJ->getgoogleadsensecount();
$adminPage  = filter_input( INPUT_GET, 'page' );
if ($adminPage == 'googleadsense' ) {														
	require_once( APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videogoogleadsense/videogoogleadsense.php' );
}																					
else if ( $adminPage == 'addgoogleadsense' ) {												
	require_once( APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videogoogleadsense/videoaddgoogleadsense.php' );
}

?>	
																				