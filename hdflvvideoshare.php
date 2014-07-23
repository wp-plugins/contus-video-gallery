<?php
/*
  Plugin Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Widely favored by lot of customers! The hugest advantage of deploying WordPress Video Gallery is it can help to integrate, display, and set up video gallery on any WordPress page and it works great with the existing themes as well. Also, it is powered with social sharing facility which helps users to share awesome videos via popular social channels. Powered by Apptha.
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
define('APPTHA_VGALLERY_BASEURL', plugin_dir_url(__FILE__));
define('APPTHA_VGALLERY_BASEDIR', dirname(__FILE__));
load_theme_textdomain('video_gallery', APPTHA_VGALLERY_BASEDIR . '/language');

## Define Constants
define('DS', '/');
$adminModelPath = APPTHA_VGALLERY_BASEDIR . '/admin/models/';
$adminControllerPath = APPTHA_VGALLERY_BASEDIR . '/admin/controllers/';
$adminViewPath = APPTHA_VGALLERY_BASEDIR . '/admin/views/';
$frontModelPath = APPTHA_VGALLERY_BASEDIR . '/front/models/';
$frontControllerPath = APPTHA_VGALLERY_BASEDIR . '/front/controllers/';
$frontViewPath = APPTHA_VGALLERY_BASEDIR . '/front/views/';
$widgetPath = get_template_directory() . '/html/widgets';

global $dirPage;
$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
$_SESSION["stream_plugin"] = $dirPage;

## To load helper file
include_once(APPTHA_VGALLERY_BASEDIR . '/helper/query.php');
## Load widgets
if (file_exists($widgetPath . '/ContusFeatureVideos.php')) {
    include_once($widgetPath . '/ContusFeatureVideos.php');
} else {
    include_once(dirname(__FILE__) . '/ContusFeatureVideos.php');
}
if (file_exists($widgetPath . '/ContusPopularVideos.php')) {
    include_once($widgetPath . '/ContusPopularVideos.php');
} else {
    include_once(dirname(__FILE__) . '/ContusPopularVideos.php');
}
if (file_exists($widgetPath . '/ContusRecentVideos.php')) {
    include_once($widgetPath . '/ContusRecentVideos.php');
} else {
    include_once(dirname(__FILE__) . '/ContusRecentVideos.php');
}
if (file_exists($widgetPath . '/ContusRelatedVideos.php')) {
    include_once($widgetPath . '/ContusRelatedVideos.php');
} else {
    include_once(dirname(__FILE__) . '/ContusRelatedVideos.php');
}
if (file_exists($widgetPath . '/ContusVideoCategory.php')) {
    include_once($widgetPath . '/ContusVideoCategory.php');
} else {
    include_once(dirname(__FILE__) . '/ContusVideoCategory.php');
}
if (file_exists($widgetPath . '/ContusVideoSearch.php')) {
    include_once($widgetPath . '/ContusVideoSearch.php');
} else {
    include_once(dirname(__FILE__) . '/ContusVideoSearch.php');
}
if (file_exists($widgetPath . '/contusBannerSlideshow.php')) {
    include_once($widgetPath . '/contusBannerSlideshow.php');
}

## Code for Ajax Playlist in Add video Page
if (isset($_GET['page']) && $_GET['page'] == 'ajaxplaylist') {
    ob_start();
    ob_clean();
    global $adminControllerPath;
    include_once ($adminControllerPath . 'ajaxplaylistController.php');
    exit;
}
## Register Video Gallery Custom Post
add_action('init', 'videogallery_register');
add_action('admin_init', 'videogallery_admin_init');

function add_my_rule() {
    global $wp, $wpdb;

    $morepage_id = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");

    $wp->add_query_var('more');
    add_rewrite_rule('(.*)_videos', 'index.php?page_id=' . $morepage_id . '&more=$matches[1]', 'top');

    $wp->add_query_var('playlist_name');
    add_rewrite_rule('categoryvideos\/(.*)', 'index.php?page_id=' . $morepage_id . '&playlist_name=$matches[1]', 'top');

    $wp->add_query_var('user_name');
    add_rewrite_rule('user\/(.*)', 'index.php?page_id=' . $morepage_id . '&user_name=$matches[1]', 'top');

    $wp->add_query_var('video_search');
    add_rewrite_rule('search/(.*)', 'index.php?page_id=' . $morepage_id . '&video_search=$matches[1]', 'top');

    /* global $wp_rewrite; */
//    flush_rewrite_rules();
}
add_action('init', 'add_my_rule');
$video_search = filter_var(filter_input(INPUT_GET, 'video_search'), FILTER_SANITIZE_STRING);
	$wp_rewrite = new WP_Rewrite();
$link = $wp_rewrite->get_page_permastruct();
## Convert non-sef URL to seo friendly URL

if(!empty($video_search)) {
    
    if (!empty($link)) {
    $location = get_site_url() . "/search/" . $video_search;
    header("Location: $location", true, 301);
    exit;
} 
} 
## Video Sort order function
add_action('wp_ajax_videosortorder', 'videosort_function');

function videosort_function() {
    global $wpdb;
    $listitem = $_POST['listItem'];
    $ids = implode(',', $listitem);
    $sql = 'UPDATE `' . $wpdb->prefix . 'hdflvvideoshare` SET `ordering` = CASE vid ';
    if (isset($_GET['pagenum'])) {
        $page = $_GET['pagenum'];
        $page = (20 * ($page - 1));
    }
    foreach ($listitem as $key => $value) {
        $listitems[$key + $page] = $value;
    }
    foreach ($listitems as $position => $item) {
        $sql .= sprintf("WHEN %d THEN %d ", $item, $position);
    }
    $sql .= ' END WHERE vid IN (' . $ids . ')';
    $wpdb->query($sql);
    die();
}

## Playlist Sort order function
add_action('wp_ajax_playlistsortorder', 'playlist_function');

function playlist_function() {
    global $wpdb;
    $listitem = $_POST['listItem'];
    $ids = implode(',', $listitem);
    $sql = 'UPDATE `' . $wpdb->prefix . 'hdflvvideoshare_playlist` SET `playlist_order` = CASE pid ';
    if (isset($_GET['pagenum'])) {
        $page = $_GET['pagenum'];
        $page = (20 * ($page - 1));
    }
    foreach ($listitem as $key => $value) {
        $listitems[$key + $page] = $value;
    }
    foreach ($listitems as $position => $item) {
        $sql .= sprintf("WHEN %d THEN %d ", $item, $position);
    }
    $sql .= ' END WHERE pid IN (' . $ids . ')';
    $wpdb->query($sql);
    die();
}

## Video Hit count increase function
add_action('wp_ajax_videohitCount', 'videohitCount_function');
add_action('wp_ajax_nopriv_videohitCount', 'videohitCount_function');

function videohitCount_function() {
    global $wpdb;
    $vid = $_GET['vid'];             ## Get video id from url
    $hitList = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid='" . intval($vid) . "'");
    $hitCount = $hitList->hitcount;       ## Get view count for particular video and increase it
    $hitInc = ++$hitCount;
## Update Hit count here
    $wpdb->update($wpdb->prefix . "hdflvvideoshare", array('hitcount' => intval($hitInc)), array('vid' => intval($vid)));
    die();
}

## Video Hit count increase function
add_action('wp_ajax_rateCount', 'rateCount_function');
add_action('wp_ajax_nopriv_rateCount', 'rateCount_function');

function rateCount_function() {
    global $wpdb;
    $vid = $_GET['vid'];             ## Get video id from url
    $get_rate = $_GET['rate'];            ## Get Rate count from url
    if ($get_rate) {
## Update rate count count here
        mysql_query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET rate=" . intval($get_rate) . "+rate,ratecount=1+ratecount WHERE vid = '" . intval($vid) . "'");
        $ratecount = mysql_query("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid='" . intval($vid) . "'");
        $rateList = mysql_fetch_object($ratecount);
        $rateCount = $rateList->ratecount;       ## Get rate count for particular video and display it
        echo $rateCount;
    }
    die();
}

## Configxml function
add_action('wp_ajax_configXML', 'configXML_function');
add_action('wp_ajax_nopriv_configXML', 'configXML_function');

function configXML_function() {
    require_once( dirname(__FILE__) . '/configXML.php');
    die();
}

## myextractXML function
add_action('wp_ajax_myextractXML', 'myextractXML_function');
add_action('wp_ajax_nopriv_myextractXML', 'myextractXML_function');

function myextractXML_function() {
    require_once( dirname(__FILE__) . '/myextractXML.php');
    die();
}

## mymidrollXML function
add_action('wp_ajax_mymidrollXML', 'mymidrollXML_function');
add_action('wp_ajax_nopriv_mymidrollXML', 'mymidrollXML_function');

function mymidrollXML_function() {
    require_once( dirname(__FILE__) . '/mymidrollXML.php');
    die();
}

## myimaadsXML function
add_action('wp_ajax_myimaadsXML', 'myimaadsXML_function');
add_action('wp_ajax_nopriv_myimaadsXML', 'myimaadsXML_function');

function myimaadsXML_function() {
    require_once( dirname(__FILE__) . '/myimaadsXML.php');
    die();
}

## languageXML function
add_action('wp_ajax_languageXML', 'languageXML_function');
add_action('wp_ajax_nopriv_languageXML', 'languageXML_function');

function languageXML_function() {
    require_once( dirname(__FILE__) . '/languageXML.php');
    die();
}

## email function
add_action('wp_ajax_email', 'email_function');
add_action('wp_ajax_nopriv_email', 'email_function');

function email_function() {
    require_once( dirname(__FILE__) . '/email.php');
    die();
}

## download function
add_action('wp_ajax_download', 'download_function');
add_action('wp_ajax_nopriv_download', 'download_function');

function download_function() {
    require_once( dirname(__FILE__) . '/download.php');
    die();
}

## myadsXML function
add_action('wp_ajax_myadsXML', 'myadsXML_function');
add_action('wp_ajax_nopriv_myadsXML', 'myadsXML_function');

function myadsXML_function() {
    require_once( dirname(__FILE__) . '/myadsXML.php');
    die();
}

## RSS function
add_action('wp_ajax_rss', 'rss_function');
add_action('wp_ajax_nopriv_rss', 'rss_function');

function rss_function() {
    require_once( dirname(__FILE__) . '/videogalleryrss.php');
    die();
}

function videogallery_register() {
    $labels = array(
        'name' => _x('Contus Video Gallery', 'post type general name'),
        'singular_name' => _x('Video Gallery Item', 'post type singular name'),
        'add_new' => _x('Add New', 'portfolio item'),
        'add_new_item' => __('Add New Video Gallery Item'),
        'edit_item' => __('Edit Video Gallery Item'),
        'new_item' => __('New Video Gallery Item'),
        'view_item' => __('View Video Gallery Item'),
        'search_items' => __('Search Video Gallery'),
        'not_found' => __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
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
        'supports' => array('title', 'editor', 'thumbnail', 'comments')
    );
    register_post_type('videogallery', $args);
}

## function to Add videogallery menu list in wp admin

function videogallery_addpages() {
    add_menu_page("Video Gallery", "Video Gallery", 'read', "video", "videogallery_menu", APPTHA_VGALLERY_BASEURL . "/images/apptha.png");
    add_submenu_page("video", "Video Gallery", "All Videos", 'read', "video", "videogallery_menu");
    add_submenu_page("", "New Videos", "", 'read', "newvideo", "videogallery_menu");
    add_submenu_page("video", "Video Gallery", "Categories", 'manage_options', "playlist", "videogallery_menu");
    add_submenu_page("", "Video Gallery", "Ajax Category", 'manage_options', "ajaxplaylist", "videogallery_menu");
    add_submenu_page("", "New Category", "", 'manage_options', "newplaylist", "videogallery_menu");
    add_submenu_page("video", "Video Ads", "Video Ads", 'manage_options', "videoads", "videogallery_menu");
    add_submenu_page("", "New Videos", "", 'manage_options', "newvideoad", "videogallery_menu");
    add_submenu_page("video", "GallerySettings", "Settings", 'manage_options', "hdflvvideosharesettings", "videogallery_menu");
}

add_action('admin_menu', 'videogallery_addpages');

## Include install file to created database
require_once(APPTHA_VGALLERY_BASEDIR . '/install.php');
register_activation_hook(__FILE__, 'videogallery_install');

$plugin_main_file = $dirPage . "/hdflvvideoshare.php";
if (isset($_GET['action']) && $_GET['action'] == "activate-plugin" && $_GET['plugin'] == $plugin_main_file) {

## declare table names and global variable to access WP query
    global $wpdb;
    $table_name = $wpdb->prefix . 'hdflvvideoshare';
    $table_settings = $wpdb->prefix . 'hdflvvideoshare_settings';
    $table_playlist = $wpdb->prefix . 'hdflvvideoshare_playlist';
    $table_ad = $wpdb->prefix . 'hdflvvideoshare_vgads';
    $charset_collate = '';

## define default collation for database
    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }

## declare variables
    $updateSlug = $updatestreamer_path = $updateislive = $updateratecount = $updaterate = $updateordering = $updatekeyApps = $updatekeydisqusApps =
            $player_colors = $playlist_open = $updatecolMore = $updateembedcode = $updatesubtitle_lang1 = $updatemember_id = $updatesubtitle_lang2 = $updatesrtfile1 = $updatesrtfile2 = $updatedefault_player = $updaterowMore =
            $showPlaylist = $updatecontentId = $updateimaadpath = $updatepublisherId = $updateimaadwidth = $updateimaadheight = $midroll_ads = $adsSkip = $adsSkipDuration = $relatedVideoView = $imaAds = $trackCode = $showTag = $ratingscontrol = $view_visible =
            $updateaddescription = $updateimaadType = $updateadtargeturl = $updateadclickurl = $updateadimpressionurl = $updateadmethod = $updateadtype = $updateispublish =
            $shareIcon = $updateimaad = $updateisplaylist_slugname = $categorydisplay = $tagdisplay = $updatechannels = $updatemidrollads = $volumecontrol = $playlist_auto = $progressControl = $imageDefault = $updatepublish = $updateadpublish = '';

## Video table update
    $updateSlug = AddColumnIfNotExists($errorMsg, "$table_name", "slug", "TEXT $charset_collate NOT NULL");
    $updatemidrollads = AddColumnIfNotExists($errorMsg, "$table_name", "midrollads", "INT( 11 ) NOT NULL DEFAULT 0");
    $updateimaad = AddColumnIfNotExists($errorMsg, "$table_name", "imaad", "INT( 11 ) NOT NULL DEFAULT 0");
    $updatestreamer_path = AddColumnIfNotExists($errorMsg, "$table_name", "streamer_path", "MEDIUMTEXT $charset_collate NOT NULL");
    $updatepublish = AddColumnIfNotExists($errorMsg, "$table_name", "publish", "INT( 11 ) NOT NULL DEFAULT 1");
    $updateislive = AddColumnIfNotExists($errorMsg, "$table_name", "islive", "INT( 11 ) NOT NULL");
    $updateordering = AddColumnIfNotExists($errorMsg, "$table_name", "ordering", "INT( 11 ) NOT NULL");
    $updateratecount = AddColumnIfNotExists($errorMsg, "$table_name", "ratecount", "INT( 25 ) NOT NULL DEFAULT 0");
    $updaterate = AddColumnIfNotExists($errorMsg, "$table_name", "rate", "INT( 25 ) NOT NULL DEFAULT 0");
    $updateembedcode = AddColumnIfNotExists($errorMsg, "$table_name", "embedcode", "LONGTEXT NOT NULL");
    $updatesrtfile1 = AddColumnIfNotExists($errorMsg, "$table_name", "srtfile1", "varchar(255) NOT NULL");
    $updatesrtfile2 = AddColumnIfNotExists($errorMsg, "$table_name", "srtfile2", "varchar(255) NOT NULL");
    $updatesubtitle_lang1 = AddColumnIfNotExists($errorMsg, "$table_name", "subtitle_lang1", "MEDIUMTEXT NOT NULL");
    $updatesubtitle_lang2 = AddColumnIfNotExists($errorMsg, "$table_name", "subtitle_lang2", "MEDIUMTEXT NOT NULL");
    $updatemember_id = AddColumnIfNotExists($errorMsg, "$table_name", "member_id", "INT(3) NOT NULL");

## AD table update
    $updateadpublish = AddColumnIfNotExists($errorMsg, "$table_ad", "publish", "INT( 11 ) NOT NULL DEFAULT 1");
    $updateaddescription = AddColumnIfNotExists($errorMsg, "$table_ad", "description", "TEXT $charset_collate NOT NULL");
    $updateadtargeturl = AddColumnIfNotExists($errorMsg, "$table_ad", "targeturl", "TEXT $charset_collate NOT NULL");
    $updateadclickurl = AddColumnIfNotExists($errorMsg, "$table_ad", "clickurl", "TEXT $charset_collate NOT NULL");
    $updateadimpressionurl = AddColumnIfNotExists($errorMsg, "$table_ad", "impressionurl", "TEXT $charset_collate NOT NULL");
    $updateadmethod = AddColumnIfNotExists($errorMsg, "$table_ad", "admethod", "TEXT $charset_collate NOT NULL");
    $updateadtype = AddColumnIfNotExists($errorMsg, "$table_ad", "adtype", "TEXT $charset_collate NOT NULL");
    $updateimaadwidth = AddColumnIfNotExists($errorMsg, "$table_ad", "imaadwidth", "INT( 11 ) NOT NULL");
    $updateimaadheight = AddColumnIfNotExists($errorMsg, "$table_ad", "imaadheight", "INT( 11 ) NOT NULL");
    $updateimaadpath = AddColumnIfNotExists($errorMsg, "$table_ad", "imaadpath", "TEXT $charset_collate NOT NULL");
    $updatepublisherId = AddColumnIfNotExists($errorMsg, "$table_ad", "publisherId", "TEXT $charset_collate NOT NULL");
    $updatecontentId = AddColumnIfNotExists($errorMsg, "$table_ad", "contentId", "TEXT $charset_collate NOT NULL");
    $updateimaadType = AddColumnIfNotExists($errorMsg, "$table_ad", "imaadType", "INT( 11 ) NOT NULL");
    $updatechannels = AddColumnIfNotExists($errorMsg, "$table_ad", "channels", "varchar(255) $charset_collate NOT NULL");

## playlist table update
    $updateispublish = AddColumnIfNotExists($errorMsg, "$table_playlist", "is_publish", "INT( 11 ) NOT NULL DEFAULT 1");
    $updateisplaylist_slugname = AddColumnIfNotExists($errorMsg, "$table_playlist", "playlist_slugname", "TEXT $charset_collate NOT NULL");

## settings table update
    $updatedefault_player = AddColumnIfNotExists($errorMsg, "$table_settings", "default_player", "INT( 11 ) NOT NULL DEFAULT 0");
    $updatekeyApps = AddColumnIfNotExists($errorMsg, "$table_settings", "keyApps", "varchar(50) $charset_collate NOT NULL");
    $updaterowMore = AddColumnIfNotExists($errorMsg, "$table_settings", "rowMore", "varchar(25) $charset_collate NOT NULL DEFAULT 2");
    $updatecolMore = AddColumnIfNotExists($errorMsg, "$table_settings", "colMore", "varchar(25) $charset_collate NOT NULL DEFAULT 4");
    $updatekeydisqusApps = AddColumnIfNotExists($errorMsg, "$table_settings", "keydisqusApps", "varchar(50) $charset_collate NOT NULL");
    $player_colors = AddColumnIfNotExists($errorMsg, "$table_settings", "player_colors", "longtext $charset_collate NOT NULL");
    $playlist_open = AddColumnIfNotExists($errorMsg, "$table_settings", "playlist_open", "INT( 3 ) NOT NULL");
    $showPlaylist = AddColumnIfNotExists($errorMsg, "$table_settings", "showPlaylist", "INT( 3 ) NOT NULL");
    $midroll_ads = AddColumnIfNotExists($errorMsg, "$table_settings", "midroll_ads", "INT( 3 ) NOT NULL");
    $adsSkip = AddColumnIfNotExists($errorMsg, "$table_settings", "adsSkip", "INT( 3 ) NOT NULL");
    $adsSkipDuration = AddColumnIfNotExists($errorMsg, "$table_settings", "adsSkipDuration", "INT( 15 ) NOT NULL");
    $relatedVideoView = AddColumnIfNotExists($errorMsg, "$table_settings", "relatedVideoView", "varchar(50) $charset_collate NOT NULL");
    $imaAds = AddColumnIfNotExists($errorMsg, "$table_settings", "imaAds", "INT( 3 ) NOT NULL");
    $trackCode = AddColumnIfNotExists($errorMsg, "$table_settings", "trackCode", "TEXT $charset_collate NOT NULL");
    $showTag = AddColumnIfNotExists($errorMsg, "$table_settings", "showTag", "INT( 3 ) NOT NULL");
    $ratingscontrol = AddColumnIfNotExists($errorMsg, "$table_settings", "ratingscontrol", "INT( 3 ) NOT NULL");
    $tagdisplay = AddColumnIfNotExists($errorMsg, "$table_settings", "tagdisplay", "INT( 3 ) NOT NULL");
    $categorydisplay = AddColumnIfNotExists($errorMsg, "$table_settings", "categorydisplay", "INT( 3 ) NOT NULL");
    $view_visible = AddColumnIfNotExists($errorMsg, "$table_settings", "view_visible", "INT( 3 ) NOT NULL");
    $shareIcon = AddColumnIfNotExists($errorMsg, "$table_settings", "shareIcon", "INT( 3 ) NOT NULL");
    $volumecontrol = AddColumnIfNotExists($errorMsg, "$table_settings", "volumecontrol", "INT( 3 ) NOT NULL DEFAULT 1");
    $playlist_auto = AddColumnIfNotExists($errorMsg, "$table_settings", "playlist_auto", "INT( 3 ) NOT NULL");
    $progressControl = AddColumnIfNotExists($errorMsg, "$table_settings", "progressControl", "INT( 3 ) NOT NULL DEFAULT 1");
    $imageDefault = AddColumnIfNotExists($errorMsg, "$table_settings", "imageDefault", "INT( 3 ) NOT NULL");

## Update Post table
    upgrade_videos();

## Delete column
    delete_video_column("$table_settings", "hideLogo");
}

## function  to declare the videogalery admin pages starts

function videogallery_menu() {
    global $adminControllerPath, $adminModelPath, $adminViewPath;
    $adminPage = filter_input(INPUT_GET, 'page');

## switch case to include controller file
    switch ($adminPage) {
        case 'video' :
        case 'newvideo':
            include_once ($adminControllerPath . 'ajaxplaylistController.php');         ## Include Ajax playlist controller to create new playlist in video page
            include_once ($adminControllerPath . 'videosController.php');               ## Include Video controller
            break;
        case 'playlist' :
        case 'newplaylist' :
            include_once($adminControllerPath . 'playlistController.php');              ## Include playlist controller to create new playlist
            break;
        case 'videoads' :
        case 'newvideoad' :
            include_once($adminControllerPath . 'videoadsController.php');              ## Include videoads controller to create new Vidoe ads
            break;
        case 'hdflvvideosharesettings' :
            include_once ($adminControllerPath . 'videosettingsController.php');        ## Include videosettingsController to controll Plugin settings
            break;
    }
}

## function to add css file for front end

function videogallery_cssJs() {
    if (is_rtl()) {
        wp_register_style('videogallery_css', plugins_url('/css/style.css', __FILE__));
        wp_register_style('videogallery_css_r', plugins_url('/css/rtl.css', __FILE__));
        wp_enqueue_style('videogallery_css_r');
        wp_enqueue_style('videogallery_css');
    } else {
        wp_register_style('videogallery_css', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style('videogallery_css');
    }
## To increase hit count of a video
    wp_register_script('videogallery_js', plugins_url('/js/script.js', __FILE__));
    wp_enqueue_script('videogallery_js');
}

## function to add css and javascript files for admin

function videogallery_admin_init() {
    wp_enqueue_script('jquery');
    wp_register_script('videogallery_jscss', plugins_url('admin/js/admin.js', __FILE__));
    wp_enqueue_script('videogallery_jscss');
    wp_enqueue_script('jquery-ui-sortable');
    wp_register_style('videogallery_css1', plugins_url('admin/css/adminsettings.css', __FILE__));
    wp_enqueue_style('videogallery_css1');
}

## function to add css and javascript files for admin

function videogallery_jcar_js_css() {
    wp_enqueue_script('jquery');
    wp_register_script('videogallery_jcar_js', APPTHA_VGALLERY_BASEURL . 'js/jquery.jcarousel.pack.js');
    wp_enqueue_script('videogallery_jcar_js');
    wp_register_style('videogallery_jcar_css', APPTHA_VGALLERY_BASEURL . 'css/jquery.jcarousel.css');
    wp_enqueue_style('videogallery_jcar_css');
    wp_register_style('videogallery_jcar_skin_css', APPTHA_VGALLERY_BASEURL . 'css/skins.css');
    wp_enqueue_style('videogallery_jcar_skin_css');
    wp_register_script('videogallery_jcar_init_js', APPTHA_VGALLERY_BASEURL . 'js/mycarousel.js');
    wp_enqueue_script('videogallery_jcar_init_js');
}

add_action('wp_enqueue_scripts', 'videogallery_cssJs');
## Function to add og detail for facebook
add_action('wp_head', 'add_meta_details');
## Function to add meta tag
## Function definition to add og detail for facebook

function add_meta_details() {
    global $wpdb;
    global $dirPage;
## Get current video id from url, if permalink on
    $videoID = url_to_custompostid(get_permalink());
## Get current video id from url, if permalink off
    if (isset($_GET['p']))
        $videoID = intval($_GET['p']);
    if (!empty($videoID)) {
        $keyApps = $wpdb->get_var("SELECT keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
        $videoID = $wpdb->get_var("SELECT vid FROM " . $wpdb->prefix . "hdflvvideoshare WHERE slug='" . intval($videoID) . "'");
        $video_count = $wpdb->get_row("SELECT t1.description,t4.tags_name,t1.name,t1.image,t1.file_type"
                . " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                . " ON t2.media_id = t1.vid"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                . " ON t3.pid = t2.playlist_id"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_tags AS t4"
                . " ON t1.vid = t4.media_id"
                . " WHERE t1.publish='1' AND t3.is_publish='1' AND t1.vid='" . intval($videoID) . "' LIMIT 1");
## Get image path for thumb image
        $image_path = str_replace('plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
        $_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
        if (!empty($video_count)) {
            $imageFea = $video_count->image;          ## Get image name
            $file_type = $video_count->file_type;      ## Get File type

            if ($imageFea == '') {                          ## If there is no thumb image for video
                $imageFea = $_imagePath . 'nothumbimage.jpg';
            } else {
                if ($file_type == 2) {                      ## For uploaded image
                    $imageFea = $image_path . $imageFea;
                }
            }
            if (strpos($imageFea, 'youtube') > 0) {
                $imgstr = explode("/", $imageFea);
                $imageFea = "http://img.youtube.com/vi/" . $imgstr[4] . "/mqdefault.jpg";
            } 
            $videoname = $video_count->name;           ## Get video name
            $des = $video_count->description;    ## Get Video Description
            $tags_name = $video_count->tags_name;      ## Get Tag name

            echo '
<meta name="description" content="' . strip_tags($des) . '" />
<meta name="keyword" content="' . $tags_name . '" />
<link rel="image_src" href="' . $imageFea . '"/>
<link rel="canonical" href="' . get_permalink() . '"/>
<meta property="fb:app_id" content="' . $keyApps . '"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="' . get_permalink() . '"/>
<meta property="og:title" content="' . $videoname . '"/>
<meta property="og:description" content="' . strip_tags($des) . '"/>
<meta property="og:image" content="' . $imageFea . '"/>
';
        }
    }
}

function WPimport($path) { ## include admin files
    include APPTHA_VGALLERY_BASEDIR . DS . 'admin' . DS . $path;
}

## include controller file
include_once $frontControllerPath . 'videohomeController.php';

## function definition to replace content with shortcode

function videogallery_pagereplace($pageContent) {
    $pageContent = preg_replace_callback('/\[hdvideo ([^]]*)\o]/i', 'video_shortcodeplace', $pageContent);
    $pageContent = preg_replace_callback('/\[videohome]/', 'video_homereplace', $pageContent);
    $pageContent = preg_replace_callback('/\[videomore\]/', 'video_morereplace', $pageContent);
//$pageContent = preg_replace_callback('/\[banner ([^]]*)\r]/i', 'HDFLV_banner', $pageContent);
    return $pageContent;
}

## function declaration to replace content with shortcode
//add_filter('the_content', 'videogallery_pagereplace');

add_shortcode ( 'videohome', 'video_homereplace' );
add_shortcode ( 'videomore', 'video_morereplace' );
add_shortcode ( 'hdvideo', 'video_shortcodereplace' );

function url_to_custompostid($url) {
    global $wp_rewrite, $wpdb;

    $moreName = '';
    $url = apply_filters('url_to_postid', $url);
## First, check to see if there is a 'p=N' or 'page_id=N' to match against
## Check to see if we are using rewrite rules
    $rewrite = $wp_rewrite->wp_rewrite_rules();
## Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
    if (empty($rewrite))
        return 0;
## Get rid of the #anchor
    $url_split = explode('#', $url);
    $url = $url_split[0];
## Get rid of URL ?query=string
    $url_split = explode('?', $url);
    $url = $url_split[0];
## Add 'www.' if it is absent and should be there
    if (false !== strpos(home_url(), '://www.') && false === strpos($url, '://www.'))
        $url = str_replace('://', '://www.', $url);
## Strip 'www.' if it is present and shouldn't be
    if (false === strpos(home_url(), '://www.'))
        $url = str_replace('://www.', '://', $url);
## Strip 'index.php/' if we're not using path info permalinks
    if (!$wp_rewrite->using_index_permalinks())
        $url = str_replace('index.php/', '', $url);
    if (false !== strpos($url, home_url())) {
## Chop off http://domain.com
        $url = str_replace(home_url(), '', $url);
    } else {
## Chop off /path/to/blog
        $home_path = parse_url(home_url());
        $home_path = isset($home_path['path']) ? $home_path['path'] : '';
        $url = str_replace($home_path, '', $url);
    }
## Trim leading and lagging slashes
    $url = trim($url, '/');
    $request = $url;
## Look for matches.
    $request_match = $request;
    foreach ((array) $rewrite as $match => $query) {
## If the requesting file is the anchor of the match, prepend it
## to the path info.
        if (!empty($url) && ($url != $request) && (strpos($match, $url) === 0))
            $request_match = $url . '/' . $request;
        if (preg_match("!^$match!", $request_match, $matches)) {
            if ($wp_rewrite->use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
## this is a verbose page match, lets check to be sure about it
                if (!get_page_by_path($matches[$varmatch[1]]))
                    continue;
            }
## Got a match.
## Trim the query of everything up to the '?'.
            $query = preg_replace("!^.+\?!", '', $query);
## Substitute the substring matches into the query.
            $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));
## Filter out non-public query vars
            global $wp;
            global $wpdb;
            parse_str($query, $query_vars);
            $query = array();
            foreach ((array) $query_vars as $key => $value) {
                if (in_array($key, $wp->public_query_vars)) {
                    $query[$key] = $value;
                }
            }
## Do the query
            if (!empty($query['videogallery']))
                $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='" . $query['videogallery'] . "' LIMIT 1");
            return $moreName;
        }
    }
    return 0;
}

## Function to display Plugin home page

function video_homereplace() {
    global $frontControllerPath;
    include_once ($frontControllerPath . 'videohomeController.php');
    $pageOBJ = new ContusVideoView();
    $contentPlayer = $pageOBJ->home_player();
    $contentPopular = $pageOBJ->home_thumb('popular');
    $contentRecent = $pageOBJ->home_thumb('recent');
    $contentFeatured = $pageOBJ->home_thumb('featured');
    $contentCategories = $pageOBJ->home_thumb('cat');
    return $contentPlayer . $contentPopular . $contentRecent . $contentFeatured . $contentCategories;
}

## Function to display Plugin video page

function video_shortcodeplace($arguments = array()) {
    global $frontControllerPath, $frontModelPath, $frontViewPath;
    videogallery_jcar_js_css();
    include_once ($frontControllerPath . 'videoshortcodeController.php');
    $pageOBJ = new ContusVideoShortcodeView();
    $contentPlayer = $pageOBJ->HDFLV_shareRender($arguments);
    return $contentPlayer;
}

add_shortcode('hdvideo', 'video_shortcodeplace');

## Function to display Plugin more page

function video_morereplace() {
    global $frontControllerPath, $frontModelPath, $frontViewPath, $wp_query;
    $playid = intval(filter_input(INPUT_GET, 'playid')); 
    $more = &$wp_query->query_vars["more"];
    $playlist_name = &$wp_query->query_vars["playlist_name"];
    if (!empty($playlist_name)) {
        $playid = get_playlist_id($playlist_name);
    }
    $wp_query->query_vars["playid"] = $playid;

    $userid = intval(filter_input(INPUT_GET, 'userid')); 
    $user_name = &$wp_query->query_vars["user_name"];
    $user_name = str_replace('%20', ' ', $user_name);
    if (!empty($user_name)) {
        $userid = get_user_id($user_name);
    }
    $wp_query->query_vars["userid"] = $userid;

    include_once ($frontControllerPath . 'videomoreController.php');
    $videoOBJ = new ContusMoreView();

    if (!empty($playid)){
        $more = 'cat';
    }    
    if (!empty($userid)){
        $more = 'user';
    }    
    $video_search = &$wp_query->query_vars["video_search"];
    if (!empty($video_search)){
        $more = 'search';
    }
    $contentvideoPlayer = $videoOBJ->video_more_pages($more);
    return $contentvideoPlayer;
}

## Function to render error message

function render_error($message) {
    echo '<div class="wrap"><h2>&nbsp;</h2>
    <div class="error" id="error">
        <p><strong>' . $message . '</strong></p>
    </div></div>';
}

## Function to uninstall plugin

function videogallerypluginUninstalling() {
    global $wpdb;
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[videomore]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[video]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[HDFLV_mainplayer]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[videohome]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[contusHome]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[contusMore]'");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_content = '[contusVideo]'");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_vgads");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_tags");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_settings");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_playlist");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_med2play");
    $table_language = $wpdb->prefix . 'hdflvvideoshare_language';
    foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row) {
        if ($row[0] == $table_language)
            $lfound = true;
    }
    if ($lfound) {
        $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_language");
    }
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_type = 'videogallery'");
}

register_uninstall_hook(__FILE__, 'videogallerypluginUninstalling');
?>