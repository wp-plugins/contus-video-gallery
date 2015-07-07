<?php
/**  
 * Video gallery admin setting controller file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
include_once ($adminModelPath . 'videosetting.php');

if (class_exists ( 'SettingsController' ) != true) { // checks if the SettingsController class has been defined starts
	class SettingsController extends SettingsModel { // SettingsController class starts
		public $_msg;
		public $_update;
		public $_extension;
		public $_settingsUpdate;
		public function __construct() {
			parent::__construct ();
			$this->_update = filter_input ( INPUT_GET, 'update' );
			$this->_settingsUpdate = filter_input ( INPUT_POST, 'updatebutton' );
			$this->_extension   =  filter_input ( INPUT_GET, 'extension' );
		}
		/**
		 * Function for setting update detail for setting data
		 */
		public function update_settingsdata() {
			if (isset ( $this->_settingsUpdate )) {
				$autoPlay = filter_input ( INPUT_POST, 'autoplay' );
				$hdDefault = filter_input ( INPUT_POST, 'HD_default' );
				$playListauto = filter_input ( INPUT_POST, 'playlistauto' );
				$keyApps = filter_input ( INPUT_POST, 'keyApps' );
				$keydisqusApps = filter_input ( INPUT_POST, 'keydisqusApps' );
				$embedVisible = filter_input ( INPUT_POST, 'embed_visible' );
				$view_visible = filter_input ( INPUT_POST, 'view_visible' );
				$ratingscontrol = filter_input ( INPUT_POST, 'ratingscontrol' );
				$tagdisplay = filter_input ( INPUT_POST, 'tagdisplay' );
				$categorydisplay = filter_input ( INPUT_POST, 'categorydisplay' );
				$downLoad = filter_input ( INPUT_POST, 'download' );
				$playerTimer = filter_input ( INPUT_POST, 'timer' );
				$playerZoom = filter_input ( INPUT_POST, 'zoom' );
				$shareEmail = filter_input ( INPUT_POST, 'email' );
				$skinAutohide = filter_input ( INPUT_POST, 'skin_autohide' );
				$homePopular = filter_input ( INPUT_POST, 'popular' );
				$homeRecent = filter_input ( INPUT_POST, 'recent' );
				$homeFeature = filter_input ( INPUT_POST, 'feature' );
				$homeCategory = filter_input ( INPUT_POST, 'homecategory' );
				$playerWidth = filter_input ( INPUT_POST, 'width' );
				$playerHeight = filter_input ( INPUT_POST, 'height' );
				$stageColor = filter_input ( INPUT_POST, 'stagecolor' );
				$commentOption = filter_input ( INPUT_POST, 'comment_option' );
				$logoTarget = filter_input ( INPUT_POST, 'logotarget' );
				$logopath = filter_input ( INPUT_POST, 'logopathvalue' );
				$logoAlign = filter_input ( INPUT_POST, 'logoalign' );
				$logoAlpha = filter_input ( INPUT_POST, 'logoalpha' );
				$ffmpegPath = filter_input ( INPUT_POST, 'ffmpeg_path' );
				$normalScale = filter_input ( INPUT_POST, 'normalscale' );
				$fullScreenscale = filter_input ( INPUT_POST, 'fullscreenscale' );
				$licenseKey = filter_input ( INPUT_POST, 'license' );
				$preRoll = filter_input ( INPUT_POST, 'preroll' );
				$postRoll = filter_input ( INPUT_POST, 'postroll' );
				$buffer = filter_input ( INPUT_POST, 'buffer' );
				$volume = filter_input ( INPUT_POST, 'volume' );
				$gutterSpace = filter_input ( INPUT_POST, 'gutterspace' );
				$category_page = filter_input ( INPUT_POST, 'category_page' );
				$rowsPop = filter_input ( INPUT_POST, 'rowsPop' );
				$colPop = filter_input ( INPUT_POST, 'colPop' );
				$rowsRec = filter_input ( INPUT_POST, 'rowsRec' );
				$colRec = filter_input ( INPUT_POST, 'colRec' );
				$rowsFea = filter_input ( INPUT_POST, 'rowsFea' );
				$colFea = filter_input ( INPUT_POST, 'colFea' );
				$rowCat = filter_input ( INPUT_POST, 'rowCat' );
				$colCat = filter_input ( INPUT_POST, 'colCat' );
				$rowMore = filter_input ( INPUT_POST, 'rowMore' );
				$colMore = filter_input ( INPUT_POST, 'colMore' );
				$playList = filter_input ( INPUT_POST, 'playlist' );
				$fullScreen = filter_input ( INPUT_POST, 'fullscreen' );
				$default_player = 0;
				$skinVisible = filter_input ( INPUT_POST, 'skinVisible' );
				$skin_opacity = filter_input ( INPUT_POST, 'skin_opacity' );
				$subTitleColor = filter_input ( INPUT_POST, 'subTitleColor' );
				$subTitleBgColor = filter_input ( INPUT_POST, 'subTitleBgColor' );
				$subTitleFontFamily = filter_input ( INPUT_POST, 'subTitleFontFamily' );
				$subTitleFontSize = filter_input ( INPUT_POST, 'subTitleFontSize' );
				$sharepanel_up_BgColor = filter_input ( INPUT_POST, 'sharepanel_up_BgColor' );
				$sharepanel_down_BgColor = filter_input ( INPUT_POST, 'sharepanel_down_BgColor' );
				$sharepaneltextColor = filter_input ( INPUT_POST, 'sharepaneltextColor' );
				$sendButtonColor = filter_input ( INPUT_POST, 'sendButtonColor' );
				$sendButtonTextColor = filter_input ( INPUT_POST, 'sendButtonTextColor' );
				$textColor = filter_input ( INPUT_POST, 'textColor' );
				$skinBgColor = filter_input ( INPUT_POST, 'skinBgColor' );
				$seek_barColor = filter_input ( INPUT_POST, 'seek_barColor' );
				$buffer_barColor = filter_input ( INPUT_POST, 'buffer_barColor' );
				$skinIconColor = filter_input ( INPUT_POST, 'skinIconColor' );
				$pro_BgColor = filter_input ( INPUT_POST, 'pro_BgColor' );
				$playButtonColor = filter_input ( INPUT_POST, 'playButtonColor' );
				$playButtonBgColor = filter_input ( INPUT_POST, 'playButtonBgColor' );
				$playerButtonColor = filter_input ( INPUT_POST, 'playerButtonColor' );
				$playerButtonBgColor = filter_input ( INPUT_POST, 'playerButtonBgColor' );
				$relatedVideoBgColor = filter_input ( INPUT_POST, 'relatedVideoBgColor' );
				$scroll_barColor = filter_input ( INPUT_POST, 'scroll_barColor' );
				$scroll_BgColor = filter_input ( INPUT_POST, 'scroll_BgColor' );
				$playlist_open = filter_input ( INPUT_POST, 'playlist_open' );
				$showPlaylist = filter_input ( INPUT_POST, 'showPlaylist' );
				$midroll_ads = filter_input ( INPUT_POST, 'midroll_ads' );
				$adsSkip = filter_input ( INPUT_POST, 'adsSkip' );
				$adsSkipDuration = filter_input ( INPUT_POST, 'adsSkipDuration' );
				$relatedVideoView = filter_input ( INPUT_POST, 'relatedVideoView' );
				$imaAds = filter_input ( INPUT_POST, 'imaAds' );
				$trackCode = filter_input ( INPUT_POST, 'trackCode' );
				$showTag = filter_input ( INPUT_POST, 'showTag' );
				$shareIcon = filter_input ( INPUT_POST, 'shareIcon' );
				$volumecontrol = filter_input ( INPUT_POST, 'volumecontrol' );
				$playlist_auto = filter_input ( INPUT_POST, 'playlistauto' );
				$progressControl = filter_input ( INPUT_POST, 'progressControl' );
				$imageDefault = filter_input ( INPUT_POST, 'imageDefault' );
				$showSocialIcon = filter_input ( INPUT_POST, 'showSocialIcon' );
				$showPostedBy = filter_input ( INPUT_POST, 'ShowPostBy' );
				$recent_video_order = filter_input ( INPUT_POST, 'recent_video_order' );
				$related_video_count = filter_input ( INPUT_POST, 'related_video_count' );
				$report_visible = filter_input ( INPUT_POST, 'report_visible' );
				$amazonbuckets_enable = filter_input ( INPUT_POST, 'amazonbuckets_enable' ); // Amazon S3 Bucket field Details
				$amazonbuckets_name = filter_input ( INPUT_POST, 'amazonbuckets_name' );
				$amazonbuckets_link = filter_input ( INPUT_POST, 'amazonbuckets_link' );
				$amazon_bucket_access_key = filter_input ( INPUT_POST, 'amazon_bucket_access_key' );
				$amazon_bucket_access_secretkey = filter_input ( INPUT_POST, 'amazon_bucket_access_secretkey' );
				$user_allowed_method = filter_input ( INPUT_POST, 'user_allowed_method', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

				if(!empty($user_allowed_method)){
					$user_allowed_method = implode ( ',', $user_allowed_method );
				}

				$iframe_visible = filter_input ( INPUT_POST, 'iframe_visible' );
				$googleadsense_visible = filter_input ( INPUT_POST, 'googleadsense_visible' );
				$show_added_on = filter_input ( INPUT_POST,'show_added_on' );
				$member_upload_enable = filter_input ( INPUT_POST,'member_upload_enable' );
				$show_title = filter_input ( INPUT_POST,'showTitle' );
				$show_related_video = filter_input ( INPUT_POST,'show_related_video' );
				$show_rss_icon  =  filter_input ( INPUT_POST,'show_rss_icon' );
				$youtube_key = filter_input ( INPUT_POST, 'youtube_key' );
				$player_color = array (
						'sharepanel_up_BgColor' => $sharepanel_up_BgColor,
						'sharepanel_down_BgColor' => $sharepanel_down_BgColor,
						'sharepaneltextColor' => $sharepaneltextColor,
						'sendButtonColor' => $sendButtonColor,
						'sendButtonTextColor' => $sendButtonTextColor,
						'textColor' => $textColor,
						'skinBgColor' => $skinBgColor,
						'seek_barColor' => $seek_barColor,
						'buffer_barColor' => $buffer_barColor,
						'skinIconColor' => $skinIconColor,
						'pro_BgColor' => $pro_BgColor,
						'playButtonColor' => $playButtonColor,
						'playButtonBgColor' => $playButtonBgColor,
						'playerButtonColor' => $playerButtonColor,
						'playerButtonBgColor' => $playerButtonBgColor,
						'relatedVideoBgColor' => $relatedVideoBgColor,
						'scroll_barColor' => $scroll_barColor,
						'scroll_BgColor' => $scroll_BgColor,
						'skinVisible' => $skinVisible,
						'skin_opacity' => $skin_opacity,
						'subTitleColor' => $subTitleColor,
						'subTitleBgColor' => $subTitleBgColor,
						'subTitleFontFamily' => $subTitleFontFamily,
						'subTitleFontSize' => $subTitleFontSize,
						'show_social_icon' => $showSocialIcon,
						'show_posted_by' => $showPostedBy,
						'show_related_video'=>$show_related_video,
						'recentvideo_order' => $recent_video_order,
						'related_video_count' => $related_video_count,
						'report_visible' => $report_visible,
						'amazon_bucket_access_secretkey' => $amazon_bucket_access_secretkey,
						'amazon_bucket_access_key' => $amazon_bucket_access_key,
						'amazonbuckets_link' => $amazonbuckets_link,
						'amazonbuckets_name' => $amazonbuckets_name,
						'amazonbuckets_enable' => $amazonbuckets_enable,
						'user_allowed_method' => $user_allowed_method,
						'iframe_visible' => $iframe_visible,
						'googleadsense_visible' => $googleadsense_visible,
						'show_added_on' => $show_added_on,
						'member_upload_enable' => $member_upload_enable,
						'showTitle' =>$show_title,
						'show_rss_icon' =>$show_rss_icon,
						'youtube_key'=>$youtube_key,
				);
				$settingsData = array (
						'default_player' => $default_player,
						'category_page' => $category_page,
						'autoplay' => $autoPlay,
						'HD_default' => $hdDefault,
						'playlistauto' => $playListauto,
						'keyApps' => $keyApps,
						'keydisqusApps' => $keydisqusApps,
						'embed_visible' => $embedVisible,
						'view_visible' => $view_visible,
						'ratingscontrol' => $ratingscontrol,
						'tagdisplay' => $tagdisplay,
						'categorydisplay' => $categorydisplay,
						'download' => $downLoad,
						'timer' => $playerTimer,
						'zoom' => $playerZoom,
						'email' => $shareEmail,
						'skin_autohide' => $skinAutohide,
						'popular' => $homePopular,
						'recent' => $homeRecent,
						'feature' => $homeFeature,
						'homecategory' => $homeCategory,
						'width' => $playerWidth,
						'height' => $playerHeight,
						'stagecolor' => $stageColor,
						'comment_option' => $commentOption,
						'logo_target' => $logoTarget,
						'logoalign' => $logoAlign,
						'logoalpha' => $logoAlpha,
						'ffmpeg_path' => $ffmpegPath,
						'normalscale' => $normalScale,
						'fullscreenscale' => $fullScreenscale,
						'license' => $licenseKey,
						'preroll' => $preRoll,
						'postroll' => $postRoll,
						'buffer' => $buffer,
						'volume' => $volume,
						'gutterspace' => $gutterSpace,
						'rowsPop' => $rowsPop,
						'colPop' => $colPop,
						'rowsRec' => $rowsRec,
						'colRec' => $colRec,
						'rowsFea' => $rowsFea,
						'colFea' => $colFea,
						'rowCat' => $rowCat,
						'colCat' => $colCat,
						'rowMore' => $rowMore,
						'colMore' => $colMore,
						'playlist' => $playList,
						'fullscreen' => $fullScreen,
						'player_colors' => serialize ($player_color),
						'playlist_open' => $playlist_open,
						'showPlaylist' => $showPlaylist,
						'midroll_ads' => $midroll_ads,
						'adsSkip' => $adsSkip,
						'adsSkipDuration' => $adsSkipDuration,
						'relatedVideoView' => $relatedVideoView,
						'imaAds' => $imaAds,
						'trackCode' => $trackCode,
						'showTag' => $showTag,
						'shareIcon' => $shareIcon,
						'volumecontrol' => $volumecontrol,
						'playlist_auto' => $playlist_auto,
						'progressControl' => $progressControl,
						'imageDefault' => $imageDefault,						
				);
								
				$wp_upload_dir = wp_upload_dir();    // Upload directory  path
                $image_path =  $wp_upload_dir['basedir'] . '/videogallery/';
                
                if ( isset( $_FILES['logopath']['name'] ) && $_FILES ['logopath'] ['name'] != '') {
					$allowedExtensions = array (
							'jpg',
							'jpeg',
							'png',
							'gif' 
					);
					$logoImage = strtolower ( $_FILES ['logopath'] ['name'] );
					if ( $logoImage && in_array ( end ( explode ( '.', $logoImage ) ), $allowedExtensions )) {
						$settingsData ['logopath'] = $_FILES ['logopath'] ['name'];
						move_uploaded_file ( $_FILES ['logopath'] ['tmp_name'], $image_path . $_FILES ['logopath'] ['name'] );
					} else {				
					  $this->admin_redirect ( 'admin.php?page=hdflvvideosharesettings&extension=1' );
					}
				} else {
					$settingsData ['logopath'] = $logopath;
				}
				$settingsDataformat = array (
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s' 
				);
				$updateflag = $this->update_settings ( $settingsData, $settingsDataformat );
				if ($updateflag) {
					$this->admin_redirect ( 'admin.php?page=hdflvvideosharesettings&update=1' );
				} else {
					$this->admin_redirect ( 'admin.php?page=hdflvvideosharesettings&update=0' );
				}
			}
		}
		
		/**
		 * function redirect after save /update datas.
		 * 
		 * @param type $url        	
		 */
		public function admin_redirect($url) {
			echo '<script>window.open( "' . $url . '","_top",false )</script>';
		}
		
		/**
		 * function for get setting data for view
		 * 
		 * @return type $settings
		 */
		public function settings_data() {
			return $this->get_settingsdata ();
		}
		
		/**
		 * function show message for setting update action
		 */
		public function get_message() {
			if (isset ( $this->_update ) && $this->_update == '1') {
				$this->_msg = 'Settings Updated Successfully ...';
			} else if ($this->_update == '0') {
				$this->_msg = 'Settings Not Updated  Successfully ...';
			} else  if( isset( $this->_extension ) && $this->_extension == 1) {
				$this->_msg = 'File Extensions : Allowed Extensions for image file [ jpg , jpeg , png ] only';
			}
			return $this->_msg;
		}
	}
}
$settingsOBJ = new SettingsController ();
$settingsOBJ->update_settingsdata ();
$settingsGrid = $settingsOBJ->settings_data ();
$displayMsg = $settingsOBJ->get_message ();
$adminPage = filter_input ( INPUT_GET, 'page' );
$ski = APPTHA_VGALLERY_BASEDIR . DS . 'hdflvplayer' . DS . 'skin';
$skins = array ();

chdir ( $ski );
$handle = opendir ( $ski );
if (isset ( $handle )) {
	while ( false !== ($file = readdir ( $handle )) ) {
		if ($file != '.' && $file != '..') {
			if (is_dir ( $file )) {
				$skins [] = $file;
			}
		}
	}
	closedir ( $handle );
}
if ($adminPage == 'hdflvvideosharesettings') {
	require_once (APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videosetting.php');
}
?>