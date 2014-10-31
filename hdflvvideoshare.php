<?php
/**
  Plugin Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Widely favored by lot of customers! The hugest advantage of deploying WordPress Video Gallery is it can help to integrate, display, and set up video gallery on any WordPress page and it works great with the existing themes as well. Also, it is powered with social sharing facility which helps users to share awesome videos via popular social channels. Powered by Apptha.
  Version: 2.7
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
define( 'APPTHA_VGALLERY_BASEURL', plugin_dir_url( __FILE__ ) );
define( 'APPTHA_VGALLERY_BASEDIR', dirname( __FILE__ ) );
load_theme_textdomain( 'video_gallery', APPTHA_VGALLERY_BASEDIR . '/language' );

/** 
 * Define Constants
 */
define( 'DS', '/' );
global $dirPage, $adminControllerPath, $adminModelPath, $adminViewPath, $frontControllerPath, $frontModelPath,$frontViewPath;
$adminModelPath      = APPTHA_VGALLERY_BASEDIR . '/admin/models/';
$adminControllerPath = APPTHA_VGALLERY_BASEDIR . '/admin/controllers/';
$adminViewPath       = APPTHA_VGALLERY_BASEDIR . '/admin/views/';
$frontModelPath      = APPTHA_VGALLERY_BASEDIR . '/front/models/';
$frontControllerPath = APPTHA_VGALLERY_BASEDIR . '/front/controllers/';
$frontViewPath       = APPTHA_VGALLERY_BASEDIR . '/front/views/';
$widgetPath          = get_template_directory() .'/html/widgets';
$adminAjaxpath       = APPTHA_VGALLERY_BASEDIR . '/admin/ajax/';
$dir     = dirname( plugin_basename( __FILE__ ) );
$dirExp  = explode( '/', $dir );
$dirPage = $dirExp[0];
$_SESSION['stream_plugin'] = $dirPage;

include_once( APPTHA_VGALLERY_BASEDIR . '/helper/query.php' );
if ( file_exists( $widgetPath . '/ContusFeatureVideos.php' ) ) {
		include_once( $widgetPath . '/ContusFeatureVideos.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusFeatureVideos.php' );
}
if ( file_exists( $widgetPath . '/ContusPopularVideos.php' ) ) {
		include_once( $widgetPath . '/ContusPopularVideos.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusPopularVideos.php' );
}
if ( file_exists( $widgetPath . '/ContusRecentVideos.php' ) ) {
		include_once( $widgetPath . '/ContusRecentVideos.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusRecentVideos.php' );
}
if ( file_exists( $widgetPath . '/ContusRelatedVideos.php' ) ) {
		include_once( $widgetPath . '/ContusRelatedVideos.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusRelatedVideos.php' );
}
if ( file_exists( $widgetPath . '/ContusVideoCategory.php' ) ) {
		include_once( $widgetPath . '/ContusVideoCategory.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusVideoCategory.php' );
}
if ( file_exists( $widgetPath . '/ContusVideoSearch.php' ) ) {
		include_once( $widgetPath . '/ContusVideoSearch.php' );
} else {
		include_once( dirname( __FILE__ ) . '/ContusVideoSearch.php' );
}
if ( file_exists( $widgetPath . '/contusBannerSlideshow.php' ) ) {
		include_once( $widgetPath . '/contusBannerSlideshow.php' );
}
if ( file_exists( $widgetPath . '/ContusRandomVideos.php' ) ) {
	   include_once( $widgetPath . '/ContusRandomVideos.php' );
}else {
	  include_once( dirname(__FILE__) . '/ContusRandomVideos.php' );
}


/**
 *  Code for Ajax Playlist in Add video Page
 */
if ( isset( $_GET['page']) && $_GET['page'] == 'ajaxplaylist' ) {
		ob_start();
		ob_clean();
		global $adminControllerPath;
		include_once( $adminControllerPath . 'ajaxplaylistController.php' );
		exit;
}
/**
 * youtube details
 */

function hd_parseyoutubedetails( $ytVideoXML ) {

	##  Create parser, fill it with xml then delete it
	$yt_xml_parser = xml_parser_create();
	xml_parse_into_struct( $yt_xml_parser, $ytVideoXML, $yt_vals );
	xml_parser_free( $yt_xml_parser );
	##  Init individual entry array and list array
	$yt_video = $yt_vidlist = array();

	##  is_entry tests if an entry is processing
	$is_entry = true;
	##  is_author tests if an author tag is processing
	$is_author = false;
	foreach ( $yt_vals as $yt_elem ) {

		##  If no entry is being processed and tag is not start of entry, skip tag
		if ( ! $is_entry && $yt_elem['tag'] != 'ENTRY' ) {
			continue;
		}

		##  Processed tag
		switch ( $yt_elem['tag'] ) {
			case 'ENTRY' :
				if ( $yt_elem['type'] == 'open' ) {
					$is_entry = true;
					$yt_video = array();
				} else {
					$yt_vidlist[] = $yt_video;
					$is_entry     = false;
				}
				break;
			case 'ID' :
				$yt_video['id']   = substr( $yt_elem['value'], -11 );
				$yt_video['link'] = $yt_elem['value'];
				break;
			case 'PUBLISHED' :
				$yt_video['published'] = substr( $yt_elem['value'], 0, 10 ) . ' ' . substr( $yt_elem['value'], 11, 8 );
				break;
			case 'UPDATED' :
				$yt_video['updated'] = substr( $yt_elem['value'], 0, 10 ) . ' ' . substr( $yt_elem['value'], 11, 8 );
				break;
			case 'MEDIA:TITLE' :
				$yt_video['title'] = $yt_elem['value'];
				break;
			case 'MEDIA:KEYWORDS' :
				if ( isset( $yt_elem['value'] ) )
					$yt_video['tags'] = $yt_elem['value'];
				break;
			case 'MEDIA:DESCRIPTION' :
				if ( isset( $yt_elem['value'] ) )
					$yt_video['description'] = $yt_elem['value'];
				break;
			case 'MEDIA:CATEGORY' :
				$yt_video['category'] = $yt_elem['value'];
				break;
			case 'YT:DURATION' :
				$yt_video['duration'] = $yt_elem['attributes'];
				break;
			case 'MEDIA:THUMBNAIL' :
				if ( $yt_elem['attributes']['HEIGHT'] == 240 ) {
					$yt_video['thumbnail']     = $yt_elem['attributes'];
					$yt_video['thumbnail_url'] = $yt_elem['attributes']['URL'];
				}
				break;
			case 'YT:STATISTICS' :
				$yt_video['viewed'] = $yt_elem['attributes']['VIEWCOUNT'];
				break;
			case 'GD:RATING' :
				$yt_video['rating'] = $yt_elem['attributes'];
				break;
			case 'AUTHOR' :
				$is_author = ( $yt_elem['type'] == 'open' );
				break;
			case 'NAME' :
				if ( $is_author )
					$yt_video['author_name'] = $yt_elem['value'];
				break;
			case 'URI' :
				if ( $is_author )
					$yt_video['author_uri'] = $yt_elem['value'];
				break;
			default :
		}
	}

	unset( $yt_vals );

	return $yt_vidlist;
}

function hd_getyoutubepage( $url ) {

	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$xml = curl_exec( $ch );
		curl_close( $ch );
	}
	//  If not found, try to use file_get_contents ( requires php > 4.3.0 and allow_url_fopen )
	else {
		$xml = @file_get_contents( $url );
	}
	return $xml;
}
/** function for adding video ends
 *
 */
function hd_getsingleyoutubevideo( $youtube_media ) {

	if ( $youtube_media == '' ) {
		return;
	}
	$url = 'http://gdata.youtube.com/feeds/api/videos/' . $youtube_media;
	$ytb = hd_parseyoutubedetails( hd_getyoutubepage( $url ) );
	return $ytb[0];
}

/**
 * youtube function
 */
function youtubeurl() {
	$video_id = addslashes( trim( $_GET['filepath'] ) );
	if ( ! empty( $video_id ) ) {
			$act_filepath = 'http://www.youtube.com/watch?v=' . $video_id;
			$youtube_data = hd_getsingleyoutubevideo( $video_id );
			if ( $youtube_data ) {
				$act[0] = addslashes( $youtube_data['title'] );
				if ( isset( $youtube_data['thumbnail_url'] ) ) {
					$act[3] = $youtube_data['thumbnail_url'];
				}
				$act[4] = $act_filepath;
				if ( isset( $youtube_data['description'] ) ) {
					$act[5] = addslashes( $youtube_data['description'] );
				}
				if ( isset( $youtube_data['tags'] ) ) {
					$act[6] = addslashes( $youtube_data['tags'] );
				}
			}
			else {
				$this->render_error( __( 'Could not retrieve Youtube video information', 'hdflvvideoshare' ) );
			}
		return $act;
	}
}

/**
 * Function  for  get youtube media details
 * @param $youtube url.
 */
add_action( 'wp_ajax_getyoutubedetails', 'admin_youtube_deatils' );
add_action( 'wp_ajax_nopriv_getyoutubedetails', 'admin_youtube_deatils' );
function admin_youtube_deatils(){
	 $act1 = youtubeurl();	 
	 echo json_encode($act1);
	 die();
}
/** 
 * Register Video Custom Post
 */
add_action( 'init', 'videogallery_register' );
add_action( 'admin_init', 'videogallery_admin_init' );

function add_my_rule() {
	global $wp, $wpdb;

	$morepage_id = $wpdb->get_var( 'select ID from ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" and post_status="publish" and post_type="page" limit 1' );

	$wp->add_query_var( 'more' );
	add_rewrite_rule( '(.*)_videos', 'index.php?page_id=' . $morepage_id . '&more=$matches[1]', 'top' );

	$wp->add_query_var( 'playlist_name' );
	add_rewrite_rule( 'categoryvideos\/(.*)', 'index.php?page_id=' . $morepage_id . '&playlist_name=$matches[1]', 'top' );

	$wp->add_query_var( 'user_name' );
	add_rewrite_rule( 'user\/(.*)', 'index.php?page_id=' . $morepage_id . '&user_name=$matches[1]', 'top' );

	$wp->add_query_var( 'video_search' );
	add_rewrite_rule( 'search/(.*)', 'index.php?page_id=' . $morepage_id . '&video_search=$matches[1]', 'top' );
}

add_action( 'init', 'add_my_rule' );
$video_search = filter_input( INPUT_GET, 'video_search' );
$wp_rewrite   = new WP_Rewrite();
$link         = $wp_rewrite->get_page_permastruct();

/** 
 * Convert non-sef URL to seo friendly URL
 */
if ( ! empty( $video_search ) && ! empty( $link ) ) {
				$location = get_site_url() . '/search/' . urlencode( $video_search );
				header( "Location: $location", true, 301 );
				exit;
}

/** 
 * Video Sort order function
 */
add_action( 'wp_ajax_videosortorder', 'videosort_function' );

function videosort_function() {
	global $wpdb;
	$listitem = $_POST['listItem'];
	$ids      = implode( ',', $listitem );
	$sql      = 'UPDATE `' . $wpdb->prefix . 'hdflvvideoshare` SET `ordering` = CASE vid ';
	if ( isset( $_GET['pagenum'] ) ) {
		$page = ( 20 * ( $_GET['pagenum'] - 1 ) );
	}
	foreach ( $listitem as $key => $value ) {
		$listitems[$key + $page] = $value;
	}
	foreach ( $listitems as $position => $item ) {
		$sql .= sprintf( 'WHEN %d THEN %d ', $item, $position );
	}
	$sql .= ' END WHERE vid IN ( ' . $ids . ' )';
	$wpdb->query( $sql );
	die();
}

/**
 *  Playlist Sort order function
 */
add_action( 'wp_ajax_playlistsortorder', 'playlist_function' );

function playlist_function() {
	global $wpdb;
	$listitem = $_POST['listItem'];
	$ids      = implode( ',', $listitem );
	$sql      = 'UPDATE `' . $wpdb->prefix . 'hdflvvideoshare_playlist` SET `playlist_order` = CASE pid ';
	if ( isset( $_GET['pagenum'] ) ) {
		$page = ( 20 * ( $_GET['pagenum'] - 1 ) );
	}
	foreach ( $listitem as $key => $value ) {
		$listitems[$key + $page] = $value;
	}
	foreach ( $listitems as $position => $item ) {
		$sql .= sprintf( 'WHEN %d THEN %d ', $item, $position );
	}
	$sql .= ' END WHERE pid IN ( ' . $ids . ' )';
	$wpdb->query( $sql );
	die();
}

/**
 * Video Hit count increase function
 */
add_action( 'wp_ajax_videohitcount', 'videohitcount_function' );
add_action( 'wp_ajax_nopriv_videohitcount', 'videohitcount_function' );

function videohitcount_function() {
		global $wpdb;
		$vid      = $_GET['vid'];						 
		$hitList  = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare WHERE vid="' . intval( $vid ) . '"' );
		$hitCount = $hitList->hitcount;			 
		$hitInc   = ++$hitCount;
		$wpdb->update( $wpdb->prefix . 'hdflvvideoshare', array( 'hitcount' => intval( $hitInc ) ), array( 'vid' => intval( $vid ) ) );
		die();
}

/** 
 * Video Rateing video count increase function
 */
add_action( 'wp_ajax_ratecount', 'ratecount_function' );
add_action( 'wp_ajax_nopriv_ratecount', 'ratecount_function' );

function ratecount_function() {
	global $wpdb;
	$vid      = $_GET['vid'];	
	$get_rate = $_GET['rate'];   
	if ( ! empty( $get_rate ) ) {

		$ratecount = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare WHERE vid="' . intval( $vid ) . '"' );
		$wpdb->update( $wpdb->prefix . 'hdflvvideoshare', array( 'rate' => ( intval( $get_rate ) + $ratecount->rate ), 'ratecount' => ( 1 + $ratecount->ratecount ) ), array( 'vid' => intval( $vid ) ) );
		$rating = $ratecount->ratecount + 1;
		echo balanceTags( $rating );
		die();
	}
}
add_action( 'wp_ajax_configXML', 'configxml_function' );
add_action( 'wp_ajax_nopriv_configXML', 'configxml_function' );

function configxml_function() {
	require_once( dirname( __FILE__ ) . '/configXML.php' );
	die();
}
/**
 * Google Adsense for player.
 */
add_action('wp_ajax_googleadsense' ,'google_adsense');
add_action('wp_ajax_nonpriv_googleadsense' ,'google_adsense');
function google_adsense(){
	global $wpdb;
	$vid = $_GET['vid'];	
	$google_adsense_id =  $wpdb->get_var('SELECT google_adsense_value FROM '.$wpdb->prefix.'hdflvvideoshare WHERE vid ='.$vid);
	$query = $wpdb->get_var('SELECT googleadsense_details FROM '.$wpdb->prefix.'hdflvvideoshare_vgoogleadsense WHERE id='.$google_adsense_id);
	$google_adsense = unserialize($query);
	echo $google_adsense['googleadsense_code']; 
	die();
}
/** 
 * MyextractXML function
 */
add_action( 'wp_ajax_myextractXML', 'myextractxml_function' );
add_action( 'wp_ajax_nopriv_myextractXML', 'myextractxml_function' );

function myextractxml_function() {
	require_once( dirname( __FILE__ ) . '/myextractXML.php' );
	die();
}

/**
 * MymidrollXML function
 */
add_action( 'wp_ajax_mymidrollXML', 'mymidrollxml_function' );
add_action( 'wp_ajax_nopriv_mymidrollXML', 'mymidrollxml_function' );

function mymidrollxml_function() {
	require_once( dirname( __FILE__ ) . '/mymidrollXML.php' );
	die();
}

/**
 * MyimaadsXML function
 */
add_action( 'wp_ajax_myimaadsXML', 'myimaadsxml_function' );
add_action( 'wp_ajax_nopriv_myimaadsXML', 'myimaadsxml_function' );

function myimaadsxml_function() {
	require_once( dirname( __FILE__ ) . '/myimaadsXML.php' );
	die();
}

/** 
 * LanguageXML function
 */
add_action( 'wp_ajax_languageXML', 'languagexml_function' );
add_action( 'wp_ajax_nopriv_languageXML', 'languagexml_function' );

function languagexml_function() {
	require_once( dirname( __FILE__ ) . '/languageXML.php' );
	die();
}

/**
 * Email function
 */
add_action( 'wp_ajax_email', 'email_function' );
add_action( 'wp_ajax_nopriv_email', 'email_function' );

function email_function() {
	require_once( dirname( __FILE__ ) . '/email.php' );
	die();
}

/** 
 * MyadsXML function 
 */
add_action( 'wp_ajax_myadsXML', 'myadsxml_function' );
add_action( 'wp_ajax_nopriv_myadsXML', 'myadsxml_function' );

function myadsxml_function() {
	require_once( dirname( __FILE__ ) . '/myadsXML.php' );
	die();
}

/**
 *  RSS function
 */
add_action( 'wp_ajax_rss', 'rss_function' );
add_action( 'wp_ajax_nopriv_rss', 'rss_function' );

function rss_function() {
	require_once( dirname( __FILE__ ) . '/videogalleryrss.php' );
	die();
}
/**
 * Admin ajax uploaded method
 */
add_action('wp_ajax_upload','admin_upload_video');
add_action('wp_ajax_nopriv_upload','admin_upload_video');
function admin_upload_video(){
	require_once($adminAjaxpath.'videoupload.php');
} 
function videogallery_register() {
		$labels = array(
				'name' => _x( 'Contus Video Gallery', 'post type general name' ),
				'singular_name' => _x( 'Video Gallery Item', 'post type singular name' ),
				'add_new' => _x( 'Add New', 'portfolio item' ),
				'add_new_item' => __( 'Add New Video Gallery Item' ),
				'edit_item' => __( 'Edit Video Gallery Item' ),
				'new_item' => __( 'New Video Gallery Item' ),
				'view_item' => __( 'View Video Gallery Item' ),
				'search_items' => __( 'Search Video Gallery' ),
				'not_found' => __( 'Nothing found' ),
				'not_found_in_trash' => __( 'Nothing found in Trash' ),
				'parent_item_colon' => '',
		);
		$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => false,
				'query_var' => true,
				'menu_icon' => APPTHA_VGALLERY_BASEURL . '/images/apptha.png',
				'rewrite' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
		);
		register_post_type( 'videogallery', $args );
}

/**
 * Send reportvideo function   
 */
add_action('wp_ajax_reportvideo','send_report');
add_action('wp_ajax_nopriv_reportvideo','send_report');
function send_report(){            		
            global $wpdb , $current_user;
            $emailTemplatePath   = APPTHA_VGALLERY_BASEURL . 'front/emailtemplate';
			$redirect_url   = $_GET['redirect_url' ];
			$admin_email    = $_GET['admin_email' ];
			$reporter_email = $_GET['reporter_email'];
			$reportvideotype= $_GET['reporttype'];
			$video_title    = $_GET['video_title'];
			$site_url       = get_bloginfo('site_url');
			$site_name      = get_bloginfo('name');
			$sender_name    = $current_user->display_name;
			$subject        = $sender_name.' report your video';	
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: " . "<" . $reporter_email . ">\r\n";
			$headers .= "Reply-To: " . $reporter_email . "\r\n";
			$headers .= "Return-path: " . $reporter_email;
			$message = file_get_contents($emailTemplatePath.'/reportvideo.html');
            $message = str_replace('{reporter_email}',$reporter_email,$message);
            $message = str_replace('{report_type}',$message,$message);
            $message = str_replace('{username}',$sender_name,$message);
            $message = str_replace('{reportmsg}',$reportvideotype,$message);
            $message = str_replace('{video_url}', $redirect_url, $message); 
            $message = str_replace('{video_title}', $video_title, $message);
            $message = str_replace('{sender_name}', $sender_name, $message);
            if(@mail( $admin_email,$subject,$message,$headers ) ){
             	echo "send";
            }else{
            	echo "fail";
            }
            die();
		}
 /**
 * Function admin  notice  for  plugin  activation message deleted.  
 */
add_action('admin_notices', 'videogallery_admin_notices');
function videogallery_admin_notices() {
	$admin_notice_video_gallery = get_option('video_gallery_adjustment_instruction');
	if( $admin_notice_video_gallery ){
		echo '<div id="message" class="updated"><p>To ajust the layout and update the video gallery plugin player setting please visit the link <strong><a href="'.admin_url('admin.php?page=hdflvvideosharesettings').'">Settings</a></strong>.</p></div>';
		delete_option('video_gallery_adjustment_instruction');
	}
}
/**
 * Function to Add videogallery menu list in wp admin
 */ 


function videogallery_addpages() {
	    global $wpdb;
		$settings_result = $wpdb->get_var ( "SELECT player_colors FROM " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'" );
		$setting_member_upload = unserialize( $settings_result );
	    if( isset ( $setting_member_upload['member_upload_enable'] ) && $setting_member_upload['member_upload_enable'] == 1 ){
		    add_menu_page( 'Video Gallery', 'Video Gallery', 'read', 'video', 'videogallery_menu', APPTHA_VGALLERY_BASEURL . '/images/apptha.png' );
			add_submenu_page( 'video', 'Video Gallery', 'All Videos', 'read', 'video', 'videogallery_menu' );
			add_submenu_page( '', 'New Videos', '', 'read', 'newvideo', 'videogallery_menu' );
	    }else {
	    	add_menu_page( 'Video Gallery', 'Video Gallery', 'manage_options', 'video', 'videogallery_menu', APPTHA_VGALLERY_BASEURL . '/images/apptha.png' );
	    	add_submenu_page( 'video', 'Video Gallery', 'All Videos', 'manage_options', 'video', 'videogallery_menu' );
	    	add_submenu_page( '', 'New Videos', '', 'manage_options', 'newvideo', 'videogallery_menu' );
	    }	
	    // End  for  the  member upload  enable option.	
		add_submenu_page( 'video', 'Video Gallery', 'Categories', 'manage_options', 'playlist', 'videogallery_menu' );
		add_submenu_page( '', 'Video Gallery', 'Ajax Category', 'manage_options', 'ajaxplaylist', 'videogallery_menu' );
		add_submenu_page( '', 'New Category', '', 'manage_options', 'newplaylist', 'videogallery_menu' );
		add_submenu_page( 'video', 'Video Ads', 'Video Ads', 'manage_options', 'videoads', 'videogallery_menu' );
		add_submenu_page( '', 'New Videos', '', 'manage_options', 'newvideoad', 'videogallery_menu' );
		add_submenu_page( 'video','Google AdSense','Google AdSense','manage_options','googleadsense','videogallery_menu');
		add_submenu_page( '','New Google AdSense','New Google AdSense','manage_options','addgoogleadsense','videogallery_menu');
		add_submenu_page( 'video', 'GallerySettings', 'Settings', 'manage_options', 'hdflvvideosharesettings', 'videogallery_menu' );
		add_submenu_page( '' , ' Video Gallery Instruction' ,'Video Gallery Instruction' ,'menu_options','videogallery_instruction','videogallery_menu' );		
		
}

add_action( 'admin_menu', 'videogallery_addpages' );

/**
 * Include install file to created database
 */ 
require_once( APPTHA_VGALLERY_BASEDIR . '/install.php' );
register_activation_hook( __FILE__, 'videogallery_install' );

$plugin_main_file = $dirPage . '/hdflvvideoshare.php';
if ( isset( $_GET['action']) && $_GET['action'] == 'activate-plugin' && $_GET['plugin'] == $plugin_main_file ) {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'hdflvvideoshare';
		$table_settings  = $wpdb->prefix . 'hdflvvideoshare_settings';
		$table_playlist  = $wpdb->prefix . 'hdflvvideoshare_playlist';
		$table_ad        = $wpdb->prefix . 'hdflvvideoshare_vgads';
		$table_googleadsense = $wpdb->prefix .'hdflvvideoshare_vgoogleadsense';
		$charset_collate = '';

	if ( version_compare( mysql_get_server_info(), '4.1.0', '>=' ) ) {
			if ( ! empty( $wpdb->charset ) )
					$charset_collate = 'CHARACTER SET $wpdb->charset';
			if ( ! empty( $wpdb->collate ) )
					$charset_collate .= ' COLLATE $wpdb->collate';
	}
    
		$updateSlug = $updatestreamer_path = $updateislive = $updateratecount = $updaterate = $updateordering = $updatekeyApps = $updatekeydisqusApps = $player_colors = $playlist_open = $updatecolMore = $updateembedcode = $updatesubtitle_lang1 = $updatemember_id = $updatesubtitle_lang2 = $updatesrtfile1 = $updatesrtfile2 = $updatedefault_player = $updaterowMore = $showPlaylist = $updatecontentId = $updateimaadpath = $updatepublisherId = $updateimaadwidth = $updateimaadheight = $midroll_ads = $adsSkip = $adsSkipDuration = $relatedVideoView = $imaAds = $trackCode = $showTag = $ratingscontrol = $view_visible = $updateaddescription = $updateimaadType = $updateadtargeturl = $updateadclickurl = $updateadimpressionurl = $updateadmethod = $updateadtype = $updateispublish = $shareIcon = $updateimaad = $updateisplaylist_slugname = $categorydisplay = $tagdisplay = $updatechannels = $updatemidrollads = $volumecontrol = $playlist_auto = $progressControl = $imageDefault = $updatepublish = $updateadpublish = '';

		// Video table Alter
		$updateSlug           = add_column_if_not_exists( $errorMsg, "$table_name", 'slug', "TEXT $charset_collate NOT NULL" );
		$updatemidrollads     = add_column_if_not_exists( $errorMsg, "$table_name", 'midrollads', 'INT( 11 ) NOT NULL DEFAULT 0' );
		$updateimaad          = add_column_if_not_exists( $errorMsg, "$table_name", 'imaad', 'INT( 11 ) NOT NULL DEFAULT 0' );
		$updatestreamer_path  = add_column_if_not_exists( $errorMsg, "$table_name", 'streamer_path', "MEDIUMTEXT $charset_collate NOT NULL" );
		$updatepublish        = add_column_if_not_exists( $errorMsg, "$table_name", 'publish', 'INT( 11 ) NOT NULL DEFAULT 1' );
		$updateislive         = add_column_if_not_exists( $errorMsg, "$table_name", 'islive', 'INT( 11 ) NOT NULL' );
		$updateordering       = add_column_if_not_exists( $errorMsg, "$table_name", 'ordering', 'INT( 11 ) NOT NULL' );
		$updateratecount      = add_column_if_not_exists( $errorMsg, "$table_name", 'ratecount', 'INT( 25 ) NOT NULL DEFAULT 0' );
		$updaterate           = add_column_if_not_exists( $errorMsg, "$table_name", 'rate', 'INT( 25 ) NOT NULL DEFAULT 0' );
		$updateembedcode      = add_column_if_not_exists( $errorMsg, "$table_name", 'embedcode', 'LONGTEXT NOT NULL' );
		$updatesrtfile1       = add_column_if_not_exists( $errorMsg, "$table_name", 'srtfile1', 'varchar( 255 ) NOT NULL' );
		$updatesrtfile2       = add_column_if_not_exists( $errorMsg, "$table_name", 'srtfile2', 'varchar( 255 ) NOT NULL' );
		$updatesubtitle_lang1 = add_column_if_not_exists( $errorMsg, "$table_name", 'subtitle_lang1', 'MEDIUMTEXT NOT NULL' );
		$updatesubtitle_lang2 = add_column_if_not_exists( $errorMsg, "$table_name", 'subtitle_lang2', 'MEDIUMTEXT NOT NULL' );
		$updatemember_id      = add_column_if_not_exists( $errorMsg, "$table_name", 'member_id', 'INT( 3 ) NOT NULL' );
		$updategoogle_adsense = add_column_if_not_exists( $errorMsg, "$table_name", 'google_adsense', 'INT( 3 ) NOT NULL' );
		$updategoogle_adsense_value = add_column_if_not_exists( $errorMsg, "$table_name", 'google_adsense_value', 'INT( 11 ) NOT NULL' );
		$update_amazon_bucket = add_column_if_not_exists($errorMsg,"$table_name",'amazon_buckets','INT ( 1 ) NOT NULL DEFAULT 0');
		

		// AD table Alter
		$updateadpublish       = add_column_if_not_exists( $errorMsg, "$table_ad", 'publish', 'INT( 11 ) NOT NULL DEFAULT 1' );
		$updateaddescription   = add_column_if_not_exists( $errorMsg, "$table_ad", 'description', "TEXT $charset_collate NOT NULL" );
		$updateadtargeturl     = add_column_if_not_exists( $errorMsg, "$table_ad", 'targeturl', "TEXT $charset_collate NOT NULL" );
		$updateadclickurl      = add_column_if_not_exists( $errorMsg, "$table_ad", 'clickurl', "TEXT $charset_collate NOT NULL" );
		$updateadimpressionurl = add_column_if_not_exists( $errorMsg, "$table_ad", 'impressionurl', "TEXT $charset_collate NOT NULL" );
		$updateadmethod        = add_column_if_not_exists( $errorMsg, "$table_ad", 'admethod', "TEXT $charset_collate NOT NULL" );
		$updateadtype          = add_column_if_not_exists( $errorMsg, "$table_ad", 'adtype', "TEXT $charset_collate NOT NULL" );
		$updateimaadwidth      = add_column_if_not_exists( $errorMsg, "$table_ad", 'imaadwidth', 'INT( 11 ) NOT NULL' );
		$updateimaadheight     = add_column_if_not_exists( $errorMsg, "$table_ad", 'imaadheight', 'INT( 11 ) NOT NULL' );
		$updateimaadpath       = add_column_if_not_exists( $errorMsg, "$table_ad", 'imaadpath', "TEXT $charset_collate NOT NULL" );
		$updatepublisherId     = add_column_if_not_exists( $errorMsg, "$table_ad", 'publisherId', "TEXT $charset_collate NOT NULL" );
		$updatecontentId       = add_column_if_not_exists( $errorMsg, "$table_ad", 'contentId', "TEXT $charset_collate NOT NULL" );
		$updateimaadType       = add_column_if_not_exists( $errorMsg, "$table_ad", 'imaadType', 'INT( 11 ) NOT NULL' );
		$updatechannels        = add_column_if_not_exists( $errorMsg, "$table_ad", 'channels', "varchar( 255 ) $charset_collate NOT NULL" );

		// Playlist table Alter
		$updateispublish           = add_column_if_not_exists( $errorMsg, "$table_playlist", 'is_publish', 'INT( 11 ) NOT NULL DEFAULT 1' );
		$updateisplaylist_slugname = add_column_if_not_exists( $errorMsg, "$table_playlist", 'playlist_slugname', "TEXT $charset_collate NOT NULL" );

		// Settings table Alter
		$updatedefault_player = add_column_if_not_exists( $errorMsg, "$table_settings", 'default_player', 'INT( 11 ) NOT NULL DEFAULT 0' );
		$updatekeyApps        = add_column_if_not_exists( $errorMsg, "$table_settings", 'keyApps', "varchar( 50 ) $charset_collate NOT NULL" );
		$updaterowMore        = add_column_if_not_exists( $errorMsg, "$table_settings", 'rowMore', "varchar( 25 ) $charset_collate NOT NULL DEFAULT 2" );
		$updatecolMore        = add_column_if_not_exists( $errorMsg, "$table_settings", 'colMore', "varchar( 25 ) $charset_collate NOT NULL DEFAULT 4" );
		$updatekeydisqusApps  = add_column_if_not_exists( $errorMsg, "$table_settings", 'keydisqusApps', "varchar( 50 ) $charset_collate NOT NULL" );
		$player_colors        = add_column_if_not_exists( $errorMsg, "$table_settings", 'player_colors', "longtext $charset_collate NOT NULL" );
		$playlist_open        = add_column_if_not_exists( $errorMsg, "$table_settings", 'playlist_open', 'INT( 3 ) NOT NULL' );
		$showPlaylist         = add_column_if_not_exists( $errorMsg, "$table_settings", 'showPlaylist', 'INT( 3 ) NOT NULL' );
		$midroll_ads          = add_column_if_not_exists( $errorMsg, "$table_settings", 'midroll_ads', 'INT( 3 ) NOT NULL' );
		$adsSkip              = add_column_if_not_exists( $errorMsg, "$table_settings", 'adsSkip', 'INT( 3 ) NOT NULL' );
		$adsSkipDuration      = add_column_if_not_exists( $errorMsg, "$table_settings", 'adsSkipDuration', 'INT( 15 ) NOT NULL' );
		$relatedVideoView     = add_column_if_not_exists( $errorMsg, "$table_settings", 'relatedVideoView', "varchar( 50 ) $charset_collate NOT NULL" );
		$imaAds               = add_column_if_not_exists( $errorMsg, "$table_settings", 'imaAds', 'INT( 3 ) NOT NULL' );
		$trackCode            = add_column_if_not_exists( $errorMsg, "$table_settings", 'trackCode', "TEXT $charset_collate NOT NULL" );
		$showTag              = add_column_if_not_exists( $errorMsg, "$table_settings", 'showTag', 'INT( 3 ) NOT NULL' );
		$ratingscontrol       = add_column_if_not_exists( $errorMsg, "$table_settings", 'ratingscontrol', 'INT( 3 ) NOT NULL' );
		$tagdisplay           = add_column_if_not_exists( $errorMsg, "$table_settings", 'tagdisplay', 'INT( 3 ) NOT NULL' );
		$categorydisplay      = add_column_if_not_exists( $errorMsg, "$table_settings", 'categorydisplay', 'INT( 3 ) NOT NULL' );
		$view_visible         = add_column_if_not_exists( $errorMsg, "$table_settings", 'view_visible', 'INT( 3 ) NOT NULL' );
		$shareIcon            = add_column_if_not_exists( $errorMsg, "$table_settings", 'shareIcon', 'INT( 3 ) NOT NULL' );
		$volumecontrol        = add_column_if_not_exists( $errorMsg, "$table_settings", 'volumecontrol', 'INT( 3 ) NOT NULL DEFAULT 1' );
		$playlist_auto        = add_column_if_not_exists( $errorMsg, "$table_settings", 'playlist_auto', 'INT( 3 ) NOT NULL' );
		$progressControl      = add_column_if_not_exists( $errorMsg, "$table_settings", 'progressControl', 'INT( 3 ) NOT NULL DEFAULT 1' );
		$imageDefault         = add_column_if_not_exists( $errorMsg, "$table_settings", 'imageDefault', 'INT( 3 ) NOT NULL' );
		
		/**
		 * Add google adsense  table.
		 */
		$sqladsense = 'CREATE TABLE IF NOT EXISTS ' . $table_googleadsense . ' (
			`id` int( 10 ) NOT NULL AUTO_INCREMENT,
			`googleadsense_details` text NOT NULL,
			PRIMARY KEY ( `id` ) )'.$charset_collate.';';
		$wpdb->query($sqladsense);
		// Alter Post table
		upgrade_videos();
		delete_video_column( "$table_settings", 'hideLogo' );
}

/**
 * Function  to declare the videogalery admin pages starts
 */

function videogallery_menu() {
		global $adminControllerPath, $adminModelPath, $adminViewPath;
		$adminPage = filter_input( INPUT_GET, 'page' );

	switch ( $adminPage ) {
		case 'video' :
		case 'newvideo':
				include_once( $adminControllerPath . 'ajaxplaylistController.php' );				
				include_once( $adminControllerPath . 'videosController.php' );							
				break;
		case 'playlist' :
		case 'newplaylist' :
				include_once( $adminControllerPath . 'playlistController.php' );							
				break;
		case 'videoads' :
		case 'newvideoad' :
				include_once( $adminControllerPath . 'videoadsController.php' );							
				break;
		case 'hdflvvideosharesettings' :
				include_once( $adminControllerPath . 'videosettingsController.php' );				
				break;
		case 'googleadsense':
		case 'addgoogleadsense':
			include_once( $adminControllerPath . 'videogoogleadsenseController.php' );
			break;
	}
}

/**
 * Function to add css file for front end
 */
function videogallery_cssjs() {
	if ( is_rtl() ) {
			wp_register_style( 'videogallery_css', plugins_url( '/css/style.min.css', __FILE__ ) );
			wp_register_style( 'videogallery_css_r', plugins_url( '/css/rtl.min.css', __FILE__ ) );
			wp_enqueue_style( 'videogallery_css_r' );
			wp_enqueue_style( 'videogallery_css' );
	} else {
			wp_register_style( 'videogallery_css', plugins_url( '/css/style.min.css', __FILE__ ) );
			wp_enqueue_style( 'videogallery_css' );
	}
	wp_register_script( 'videogallery_js', plugins_url( '/js/script.min.js', __FILE__ ) );
	wp_enqueue_script( 'videogallery_js' );
}

/**
 * Hook for  add javascript and css file to admin
 */
function videogallery_admin_init() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'videogallery_jscss', plugins_url( 'admin/js/admin.min.js', __FILE__ ) );
		wp_enqueue_script( 'videogallery_jscss' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_register_style( 'videogallery_css1', plugins_url( 'admin/css/adminsettings.min.css', __FILE__ ) );
		wp_enqueue_style( 'videogallery_css1' );
}

/**
 * Hook for  add javascript and css file to admin
 */
function videogallery_jcar_js_css() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'videogallery_jcar_js', APPTHA_VGALLERY_BASEURL . 'js/jquery.jcarousel.pack.js' );
		wp_enqueue_script( 'videogallery_jcar_js' );
		wp_register_style( 'videogallery_jcar_css', APPTHA_VGALLERY_BASEURL . 'css/jquery.jcarousel.css' );
		wp_enqueue_style( 'videogallery_jcar_css' );
		wp_register_style( 'videogallery_jcar_skin_css', APPTHA_VGALLERY_BASEURL . 'css/skins.min.css' );
        wp_enqueue_style( 'videogallery_jcar_skin_css' );
		// Jquery ui add for tooltip
        wp_register_script( 'videogallery_jquery-ui_js', APPTHA_VGALLERY_BASEURL . 'js/jquery-ui.js' );
        wp_enqueue_script( 'videogallery_jquery-ui_js' );
        wp_register_style( 'videogallery_jquery_ui_css', APPTHA_VGALLERY_BASEURL . 'css/jquery-ui.min.css' );
        wp_enqueue_style( 'videogallery_jquery_ui_css' );
        wp_register_script( 'videogallery_jcar_init_js', APPTHA_VGALLERY_BASEURL . 'js/mycarousel.js' );
		wp_enqueue_script( 'videogallery_jcar_init_js' );
}

add_action( 'wp_enqueue_scripts', 'videogallery_cssjs' );
/** 
 * Function to add og detail for facebook open graph details
 */
add_action( 'wp_head', 'add_meta_details',1);

/**
 * Function for upload  video, images, subtitle srt files via wordpress standard method. 
 */
add_action('wp_ajax_uploadvideo' , 'video_files_uploads');
add_action('wp_ajax_nopriv_uploadvideo','video_files_uploads');
/**
 * 
 */
function video_files_uploads(){
	global $adminAjaxpath;
	require_once ($adminAjaxpath.'videoupload.php');
}


/**
 * Function definition to add og detail for facebook
 * */

function add_meta_details() {
		global $wpdb;
		global $dirPage;
		$videoURL= get_permalink();
		$videoID = url_to_custompostid( get_permalink() );
		if ( isset( $_GET['p'] ) )
				$videoID = intval( $_GET['p'] );
	if ( ! empty( $videoID ) ) {
		$keyApps     = $wpdb->get_var( 'SELECT keyApps FROM ' . $wpdb->prefix . 'hdflvvideoshare_settings' );
		$videoID     = $wpdb->get_var( 'SELECT vid FROM ' . $wpdb->prefix . 'hdflvvideoshare WHERE slug="' . intval( $videoID ) . '"' );
		$video_count = $wpdb->get_row(
						'SELECT t1.description,t4.tags_name,t1.name,t1.ratecount,t1.rate,t1.image,t1.file_type,t1.slug
						FROM ' . $wpdb->prefix . 'hdflvvideoshare AS t1
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS t2
						ON t2.media_id = t1.vid
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS t3
						ON t3.pid = t2.playlist_id
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_tags AS t4
						ON t1.vid = t4.media_id
						WHERE t1.publish=1 AND t3.is_publish=1 AND t1.vid=' . intval( $videoID ) . ' LIMIT 1' 
						);
		$image_path = str_replace( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
		$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
		if ( ! empty( $video_count ) ) {
			$imageFea  = $video_count->image;					
			$file_type = $video_count->file_type;			

			if ( $imageFea == '' ) {													
					$imageFea = $_imagePath . 'nothumbimage.jpg';
			} else {
				if ( $file_type == 2 ) {											 
						$imageFea = $image_path . $imageFea;
				}
			}
			if ( strpos( $imageFea, 'youtube' ) > 0 || strpos( $imageFea, 'ytimg' ) > 0 ) {
					$imgstr   = explode( '/', $imageFea );
					$imageFea = 'http://img.youtube.com/vi/' . $imgstr[4] . '/hqdefault.jpg';
			}
			$videoname = $video_count->name;					
			$des       = $video_count->description;		
			$tags_name = $video_count->tags_name;			
               
				$output = '
	<meta name="description" content="' . strip_tags( $des ) . '" />
	<meta name="keyword" content="' . $tags_name . '" />
	<link rel="image_src" href="' . $imageFea . '"/>
	<link rel="canonical" href="' . get_video_permalink( $video_count->slug ) . '"/>
	<meta property="og:image" content="' . $imageFea . '"/>
	<meta property="og:url" content="' . get_video_permalink( $video_count->slug ) . '"/>
	<meta property="og:title" content="' . $videoname . '"/>
	<meta property="og:description" content="' . strip_tags( $des ) . '"/>
	<meta name="viewport" content="width=device-width"> 
	';
    $rate = $video_count->rate;	
    $ratecount =$video_count->ratecount;
    if($des){
    	$description = $des;
    }else{
    	$description = 'No description';
    }
    if($rate){
    	$rate_snippet = round($rate/$ratecount);
    }else{
    	$rate_snippet =0;
    }
    $swfPath = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';
	$output .= '<div id="video-container" class="" itemscope itemid="" itemtype="http://schema.org/VideoObject">';
	$output .= '<link itemprop="url" href="'.$videoURL.'"/>';
	$output .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
	$output .= '<meta itemprop="ratingValue" content="'.$rate_snippet.'"/><meta itemprop="ratingCount" content="'.$ratecount.'"/></div>
				<div itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
					<meta itemprop="name" content="'.$videoname.'" />
					<meta itemprop="thumbnail" content="'.$imageFea.'" />
					<meta itemprop="description" content="'.strip_tags($description).'" />
				</div>
				<meta itemprop="image" content="'.$imageFea.'" />
				<meta itemprop="thumbnailUrl" content="'.$imageFea.'" />
				<meta itemprop="embedURL" content="'.$swfPath.'" />
			</div>';
	 echo $output;
	
		}
	}
}

include_once $frontControllerPath . 'videohomeController.php';

/**
 *  Function declaration to replace content with shortcode
 */
add_shortcode('videohome','video_homereplace');
add_shortcode('videomore','video_morereplace');
add_shortcode('hdvideo','video_shortcodeplace');
add_shortcode('categoryvideothumb', 'video_moreidreplace');
add_shortcode('popularvideo','video_popular_video_shortcode');
add_shortcode('recentvideo','video_recent_video_shortcode');
add_shortcode('featuredvideo','video_featured_video_shortcode');
/**
 * Custom post type rewrite function 
 * @param unknown $url
 * @return number|Ambigous <string, NULL>
 */
function url_to_custompostid( $url ) {
		global $wp_rewrite, $wpdb;

		$moreName = '';
		$url = apply_filters( 'url_to_postid', $url );
		$rewrite = $wp_rewrite->wp_rewrite_rules();
		if ( empty( $rewrite ) )
				return 0;
	
		$url_split = explode( '#', $url );
		$url = $url_split[0];
		$url_split = explode( '?', $url );
		$url = $url_split[0];
	
		if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) )
				$url = str_replace( '://', '://www.', $url );  // Add 'www.' if it is absent and should be there
	
		if ( false === strpos( home_url(), '://www.' ) )
				$url = str_replace( '://www.', '://', $url );  // Strip 'www.' if it is present and shouldn't be
	
		if ( ! $wp_rewrite->using_index_permalinks() )
				$url = str_replace( 'index.php/', '', $url );   // Strip 'index.php/' if we're not using path info permalinks
	if ( false !== strpos( $url, home_url() ) ) {
		
		$url = str_replace( home_url(), '', $url ); // Chop off http://domain.com
	} else {
		
		$home_path = parse_url( home_url() ); // Chop off /path/to/blog
		$home_path = isset( $home_path['path'] ) ? $home_path['path'] : '';
		$url = str_replace( $home_path, '', $url );
	}
	
		$url     = trim( $url, '/' );  // Trim leading and lagging slashes
		$request = $url;
	
		$request_match = $request;  // Look for matches.
	foreach ( ( array ) $rewrite as $match => $query ) {
			if ( ! empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) )
					$request_match = $url . '/' . $request;
		if ( preg_match( "!^$match!", $request_match, $matches ) ) {
			if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
				if ( ! get_page_by_path( $matches[$varmatch[1]] ) ) {
					continue;
				}
			}
				$query = preg_replace( '!^.+\?!', '', $query );
				$query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );
				global $wp;
				global $wpdb;
				parse_str( $query, $query_vars );
				$query = array();
			foreach ( ( array ) $query_vars as $key => $value ) {
				if ( in_array( $key, $wp->public_query_vars ) ) {
						$query[$key] = $value;
				}
			}
			if ( ! empty( $query['videogallery'] ) ) {
					$moreName = $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_name="' . $query['videogallery'] . '" LIMIT 1' );
			}
				return $moreName;
		}
	}
		return 0;
}

/**
 * Function to display Plugin home page
 */ 

function video_homereplace($atts) {
		global $frontControllerPath;
		include_once( $frontControllerPath . 'videohomeController.php' );
		$pageOBJ           = new ContusVideoView();
		$contentPlayer     = $pageOBJ->home_player();
		$contentPopular    = $pageOBJ->home_thumb( 'popular' );
		$contentRecent     = $pageOBJ->home_thumb( 'recent' );
		$contentRandom     = $pageOBJ->home_thumb( 'recent' );
		$contentFeatured   = $pageOBJ->home_thumb( 'featured' );
		$contentCategories = $pageOBJ->home_thumb( 'cat' );
		return $contentPlayer . $contentPopular . $contentRecent . $contentFeatured . $contentCategories;
}
/**
 * Function to display Plugin video page
 */ 

function video_shortcodeplace( $arguments = array() ) {
		global $frontControllerPath, $frontModelPath, $frontViewPath;
		videogallery_jcar_js_css();
		include_once( $frontControllerPath . 'videoshortcodeController.php' );
		$pageOBJ       = new ContusVideoShortcodeView();
		$contentPlayer = $pageOBJ->hdflv_sharerender( $arguments );
		return $contentPlayer;
}

/**
 * Function display content for  category shortcode
 */
function video_moreidreplace($arguments = array()) {
	global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
  
	$playid = absint( $arguments['id'] ) ;
	$wp_query->query_vars["playid"] = $playid;

	include_once ($frontControllerPath . 'videomoreController.php');
	$videoOBJ = new ContusMoreView();

	if (!empty($playid)){
		$more = 'cat';
	}else{
		$more = "categories";
	}
	$contentvideoPlayer = $videoOBJ->video_more_pages($more);
	return $contentvideoPlayer;
}
/**
 * Function Popular video
 */
function video_popular_video_shortcode($arguments = array()) {
	global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
 	include_once ($frontControllerPath . 'videomoreController.php');
	$videoOBJ = new ContusMoreView();   // create  object for video more view class
	$more = 'popular';
	$contentvideoPlayer = $videoOBJ->video_more_pages($more);
	return $contentvideoPlayer;
}
/**
 * Function  recent video short code  display front end.
 */
function video_recent_video_shortcode($attr=array()){
	global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
 	include_once ($frontControllerPath . 'videomoreController.php');
	$videoOBJ = new ContusMoreView();   // create  object for video more view class
	$more = 'recent';
	$contentvideoPlayer = $videoOBJ->video_more_pages($more);
	return $contentvideoPlayer;
}
/**
 * Function for feature video  short code  display.
 */
 function video_featured_video_shortcode($attr=array()){
 	global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
 	include_once ($frontControllerPath . 'videomoreController.php');
 	$videoOBJ = new ContusMoreView();   // create  object for video more view class
 	$more = 'featured';
 	$contentvideoPlayer = $videoOBJ->video_more_pages($more);
 	return $contentvideoPlayer;
 }
/**
 * Function to display Plugin more page
 */ 

function video_morereplace() {
		global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
		$playid        = filter_input( INPUT_GET, 'playid' );
		$more          = &$wp_query->query_vars['more'];
		$playlist_name = &$wp_query->query_vars['playlist_name'];
	if ( ! empty( $playlist_name) ) {
			$playid = get_playlist_id( $playlist_name );
	}
		$wp_query->query_vars['playid'] = $playid;

		$userid    = filter_input( INPUT_GET, 'userid' );
		$user_name = &$wp_query->query_vars['user_name'];
		$user_name = str_replace( '%20', ' ', $user_name );
	if ( ! empty( $user_name) ) {
			$userid = get_user_id( $user_name );
	}
		$wp_query->query_vars['userid'] = $userid;

		include_once( $frontControllerPath . 'videomoreController.php' );
		$videoOBJ = new ContusMoreView();

	if ( ! empty( $playid) ) {
			$more = 'cat';
	}
	if ( ! empty( $userid) ) {
			$more = 'user';
	}
		$video_search = &$wp_query->query_vars['video_search'];
	if ( ! empty( $video_search) ) {
			$more = 'search';
	}
	$videotag = &$wp_query->query_vars['video_tag'];
	if(!empty($videotag)){
		$more = 'tag';
	}
		$contentvideoPlayer = $videoOBJ->video_more_pages( $more );
		return $contentvideoPlayer;
}

/**
 * Function to render error message
 */

function render_error( $message ) {
		echo '<div class="wrap"><h2>&nbsp;</h2>
    <div class="error" id="error">
        <p><strong>' . $message . '</strong></p>
    </div></div>';
}

/**
 * Function to delete plugin delate created table for install the  plugin.
 */ 

function videogallerypluginuninstalling() {
		global $wpdb;
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videomore]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videogallery]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[HDFLV_mainplayer]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[videohome]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[contusHome]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[contusMore]"' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_content = "[contusVideo]"' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_vgads' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_tags' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_settings' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_playlist' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_med2play' );
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_vgoogleadsense' );
		$table_language = $wpdb->prefix . 'hdflvvideoshare_language';
	foreach ( $wpdb->get_results( 'SHOW TABLES;', ARRAY_N ) as $row ) {
			if ( $row[0] == $table_language )
					$lfound = true;
	}
	if ( $lfound ) {
			$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare_language' );
	}
		$wpdb->query( ' DROP TABLE ' . $wpdb->prefix . 'hdflvvideoshare' );
		$wpdb->query( ' DELETE FROM ' . $wpdb->prefix . 'posts WHERE post_type = "videogallery"' );
}
/**
 * After redirect to last visited page in video. 
 * @param $redirect_url
 * $return comment  post redirect page 
 */
add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location)
{   
    global $wpdb;
	wp_redirect( $_SERVER["HTTP_REFERER"] );
	exit;
}
/**
 * Function add jQuery ui script and  stylesheet for tool tip 
 */
register_uninstall_hook( __FILE__, 'videogallerypluginuninstalling' );
?>