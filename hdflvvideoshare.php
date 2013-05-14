<?php

/*
  Plugin Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Simplifies the process of adding video to a WordPress blog. Powered by Apptha.
  Version: 2.0
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
define('APPTHA_VGALLERY_BASEURL', plugin_dir_url(__FILE__));
define('APPTHA_VGALLERY_BASEDIR', dirname(__FILE__));
load_theme_textdomain( 'video_gallery', APPTHA_VGALLERY_BASEDIR . '/language' );
define('DS', '/');
$adminModelPath = APPTHA_VGALLERY_BASEDIR . '/admin/models/';
$adminControllerPath = APPTHA_VGALLERY_BASEDIR . '/admin/controllers/';
$adminViewPath = APPTHA_VGALLERY_BASEDIR . '/admin/views/';
$frontModelPath = APPTHA_VGALLERY_BASEDIR . '/front/models/';
$frontControllerPath = APPTHA_VGALLERY_BASEDIR . '/front/controllers/';
$frontViewPath = APPTHA_VGALLERY_BASEDIR . '/front/views/';
wp_register_style('videogallery_cssJs', plugins_url('/css/style.css', __FILE__));
wp_enqueue_style('videogallery_cssJs');
$widgetPath = get_template_directory() . '/html/widgets';
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
if (isset($_GET['page']) && $_GET['page'] == 'ajaxplaylist') {
    ob_start();
    ob_clean();
    global $adminControllerPath;
    include_once ($adminControllerPath . 'ajaxplaylistController.php');
    exit;
}
add_action('init', 'videogallery_register');

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
        'supports' => array('title', 'editor', 'thumbnail')
    );

    register_post_type('videogallery', $args);
}

function videogallery_addpages() {//function to ddd videogallery menu list to wp admin action starts
    add_menu_page("Video Gallery", "Video Gallery", 'manage_options', "video", "videogallery_menu", APPTHA_VGALLERY_BASEURL . "/images/apptha.png");
    add_submenu_page("video", "Video Gallery", "All Videos", 'manage_options', "video", "videogallery_menu");
    add_submenu_page("", "New Videos", "", 'manage_options', "newvideo", "videogallery_menu");
    add_submenu_page("video", "Video Gallery", "Play List", 'manage_options', "playlist", "videogallery_menu");
    add_submenu_page("", "Video Gallery", "Ajax Play List", 'manage_options', "ajaxplaylist", "videogallery_menu");
    add_submenu_page("", "New Playlist", "", 'manage_options', "newplaylist", "videogallery_menu");
    add_submenu_page("video", "Video Ads", "Video Ads", 'manage_options', "videoads", "videogallery_menu");
    add_submenu_page("", "New Videos", "", 'manage_options', "newvideoad", "videogallery_menu");
    add_submenu_page("video", "GallerySettings", "Settings", 'manage_options', "hdflvvideosharesettings", "videogallery_menu");
}

//function to ddd videogallery menu list to wp admin action ends
add_action('admin_menu', 'videogallery_addpages');
require_once(APPTHA_VGALLERY_BASEDIR . '/install.php');
///install file
register_activation_hook(__FILE__, 'videogallery_install');

if($_GET['action']=="activate-plugin" && $_GET['plugin']=="contus-video-gallery/hdflvvideoshare.php"){
    global $wpdb;
    $table_name = $wpdb->prefix . 'hdflvvideoshare';
    $table_settings = $wpdb->prefix . 'hdflvvideoshare_settings';
    $table_playlist = $wpdb->prefix . 'hdflvvideoshare_playlist';

    $charset_collate = '';

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }

    $updateSlug=$updatestreamer_path=$updateislive=$updateordering=$updatekeyApps=$updatekeydisqusApps='';
         $updateSlug = AddColumnIfNotExists($errorMsg, "$table_name", "slug","TEXT $charset_collate NOT NULL");
         $updatestreamer_path = AddColumnIfNotExists($errorMsg, "$table_name", "streamer_path","MEDIUMTEXT $charset_collate NOT NULL");
         $updatepublish = AddColumnIfNotExists($errorMsg, "$table_name", "publish","INT( 11 ) NOT NULL");
         $updateispublish = AddColumnIfNotExists($errorMsg, "$table_playlist", "is_publish","INT( 11 ) NOT NULL");
         $updateislive = AddColumnIfNotExists($errorMsg, "$table_name", "islive","INT( 11 ) NOT NULL");
         $updateordering = AddColumnIfNotExists($errorMsg, "$table_name", "ordering","INT( 11 ) NOT NULL");
         $updatekeyApps = AddColumnIfNotExists($errorMsg, "$table_settings", "keyApps","varchar(50) $charset_collate NOT NULL");
         $updatekeydisqusApps = AddColumnIfNotExists($errorMsg, "$table_settings", "keydisqusApps","varchar(50) $charset_collate NOT NULL");
         upgrade_videos();
}


function videogallery_menu() { //function  to declare the videogalery admin pages starts
    global $adminControllerPath, $adminModelPath, $adminViewPath;
    $adminPage = filter_input(INPUT_GET, 'page');
    switch ($adminPage) {//switch case for including the admin pages starts
        case 'video' :
        case 'newvideo':
            include_once ($adminControllerPath . 'ajaxplaylistController.php');         // Include Ajax playlist controller to create new playlist in video page
            include_once ($adminControllerPath . 'videosController.php');               // Include Video controller
            break;
        case 'playlist' :
        case 'newplaylist' :
            include_once($adminControllerPath . 'playlistController.php');              // Include playlist controller to create new playlist
            $playlistoBj = new PlaylistController();
            break;
        case 'videoads' :
        case 'newvideoad' :
            include_once($adminControllerPath . 'videoadsController.php');              // Include videoads controller to create new Vidoe ads
            break;
        case 'hdflvvideosharesettings' :
            include_once ($adminControllerPath . 'videosettingsController.php');        // Include videosettingsController to controll Plugin settings
            break;
    }//switch case for including the admin pages ends
}

//function  to declare the videogalery admin pages ends
function videogallery_cssJs() {//function for adding css and javascript files starts
    wp_register_style('videogallery_css', plugins_url('admin/css/adminsettings.css', __FILE__));
    wp_register_script('videogallery_cssJs', plugins_url('admin/js/admin.js', __FILE__));
    wp_enqueue_script('videogallery_cssJs');
    wp_enqueue_style('videogallery_css');
}

//function for adding css and javascript files ends
add_action('admin_init', 'videogallery_cssJs');

// Function to add meta tag

add_action('wp_head', 'add_meta_details');

function add_meta_details() {
    global $wpdb;
    $videoID = url_to_custompostid(get_permalink());
    if (isset($_GET['p']))
        $videoID = intval($_GET['p']);

    if (!empty($videoID)) {
        $keyApps = $wpdb->get_var("SELECT keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
        $videoID = $wpdb->get_var("select vid from " . $wpdb->prefix . "hdflvvideoshare WHERE slug='$videoID'");
        $video_count = $wpdb->get_row("SELECT t1.description,t4.tags_name,t1.name,t1.image"
                        . " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
                        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                        . " ON t2.media_id = t1.vid"
                        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                        . " ON t3.pid = t2.playlist_id"
                        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_tags AS t4"
                        . " ON t1.vid = t4.media_id"
                        . " WHERE t1.publish='1' and t3.is_publish='1' and t1.vid='" . intval($videoID) . "' limit 1");

        $image_path = str_replace('plugins/video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
        $_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
        $imageFea = $video_count->image;
        if ($imageFea == '') {  //If there is no thumb image for video
            $imageFea = $_imagePath . 'nothumbimage.jpg';
        } else {
            if ($file_type == 2) {          //For uploaded image
                $imageFea = $image_path . $imageFea;
            }
        }
        if (strpos($imageFea, 'youtube') > 0) {
            $imgstr = explode("/", $imageFea);
            $imageFea = "http://img.youtube.com/vi/" . $imgstr[4] . "/mqdefault.jpg";
        }
        $videoname = $video_count->name;
        $des = $video_count->description;
        $tags_name = $video_count->tags_name;
        echo '<title>' . $videoname . '</title>
<meta name="description" content="' . $des . '" />
<meta name="keyword" content="' . $tags_name . '" />
<link rel="image_src" href="' . $imageFea . '"/>
 <link rel="canonical" href="'.  get_permalink().'"/>
         <meta property="fb:app_id" content="'.$keyApps.'"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="'.get_permalink().'"/>
    <meta property="og:title" content="'.$videoname.'"/>
    <meta property="og:description" content="'.$des.'"/>
    <meta property="og:image" content="' . $imageFea . '"/>
';
    }
}

//adding css and javascript files
function WPimport($path) {//function for includeing files starts
    include APPTHA_VGALLERY_BASEDIR . DS . 'admin' . DS . $path;
}

//function for includeing files ends
include_once $frontControllerPath . 'videohomeController.php';

function videogallery_pagereplace($pageContent) {//function for replacing content of the pages starts
    $pageContent = preg_replace_callback('/\[hdvideo ([^]]*)\o]/i', 'video_shortcodeplace', $pageContent);
    $pageContent = preg_replace_callback('/\[videohome]/', 'video_homereplace', $pageContent);
    $pageContent = preg_replace_callback('/\[videomore\]/', 'video_morereplace', $pageContent);

//$pageContent = preg_replace_callback('/\[banner ([^]]*)\r]/i', 'HDFLV_banner', $pageContent);
    return $pageContent;
}

//function for replacing content of the pages ends
add_filter('the_content', 'videogallery_pagereplace'); //content filter for adding the pages

function url_to_custompostid($url) {
    global $wp_rewrite, $wpdb;
    $url = apply_filters('url_to_postid', $url);

    // First, check to see if there is a 'p=N' or 'page_id=N' to match against
    if (preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values)) {
        $id = absint($values[2]);
        if ($id)
            return $id;
    }

    // Check to see if we are using rewrite rules
    $rewrite = $wp_rewrite->wp_rewrite_rules();

    // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
    if (empty($rewrite))
        return 0;

    // Get rid of the #anchor
    $url_split = explode('#', $url);
    $url = $url_split[0];

    // Get rid of URL ?query=string
    $url_split = explode('?', $url);
    $url = $url_split[0];

    // Add 'www.' if it is absent and should be there
    if (false !== strpos(home_url(), '://www.') && false === strpos($url, '://www.'))
        $url = str_replace('://', '://www.', $url);

    // Strip 'www.' if it is present and shouldn't be
    if (false === strpos(home_url(), '://www.'))
        $url = str_replace('://www.', '://', $url);

    // Strip 'index.php/' if we're not using path info permalinks
    if (!$wp_rewrite->using_index_permalinks())
        $url = str_replace('index.php/', '', $url);

    if (false !== strpos($url, home_url())) {
        // Chop off http://domain.com
        $url = str_replace(home_url(), '', $url);
    } else {
        // Chop off /path/to/blog
        $home_path = parse_url(home_url());
        $home_path = isset($home_path['path']) ? $home_path['path'] : '';
        $url = str_replace($home_path, '', $url);
    }

    // Trim leading and lagging slashes
    $url = trim($url, '/');

    $request = $url;

    // Look for matches.
    $request_match = $request;
    foreach ((array) $rewrite as $match => $query) {

        // If the requesting file is the anchor of the match, prepend it
        // to the path info.
        if (!empty($url) && ($url != $request) && (strpos($match, $url) === 0))
            $request_match = $url . '/' . $request;

        if (preg_match("!^$match!", $request_match, $matches)) {

            if ($wp_rewrite->use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
                // this is a verbose page match, lets check to be sure about it
                if (!get_page_by_path($matches[$varmatch[1]]))
                    continue;
            }

            // Got a match.
            // Trim the query of everything up to the '?'.
            $query = preg_replace("!^.+\?!", '', $query);

            // Substitute the substring matches into the query.
            $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));

            // Filter out non-public query vars
            global $wp;
            global $wpdb;
            parse_str($query, $query_vars);

            $query = array();
            foreach ((array) $query_vars as $key => $value) {

                if (in_array($key, $wp->public_query_vars)) {
                    $query[$key] = $value;
                }
            }
            // Do the query
            $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='" . $query['videogallery'] . "' LIMIT 1");
            return $moreName;
        }
    }
    return 0;
}

// Function to display Plugin home page
function video_homereplace() {
    global $frontControllerPath;
    include_once ($frontControllerPath . 'videohomeController.php');
    $pageOBJ = new ContusVideoView();
    $contentPlayer = $pageOBJ->home_player();
    $contentPopular = $pageOBJ->home_thumb('pop');
    $contentRecent = $pageOBJ->home_thumb('rec');
    $contentFeatured = $pageOBJ->home_thumb('fea');
    $contentCategories = $pageOBJ->home_thumb('cat');
    return $contentPlayer . $contentPopular . $contentRecent . $contentFeatured . $contentCategories;
}

function video_shortcodeplace($arguments= array()) {

    global $frontControllerPath, $frontModelPath, $frontViewPath;
    include_once ($frontControllerPath . 'videoshortcodeController.php');
    $pageOBJ = new ContusVideoShortcodeView();
    $contentPlayer = $pageOBJ->HDFLV_shareRender($arguments);
    return $contentPlayer;
}

add_shortcode('hdvideo', 'video_shortcodeplace');

// Function to display more page
function video_morereplace() {
    global $frontControllerPath, $frontModelPath, $frontViewPath;
    include_once ($frontControllerPath . 'videomoreController.php');
    $more = filter_input(INPUT_GET, 'more');
    $playid = '';
    $playid = filter_input(INPUT_GET, 'playid');
    if (!empty($playid))
        $more = 'cat';
    $video_search = filter_var(filter_input(INPUT_POST, 'video_search'), FILTER_SANITIZE_STRING);
    $video_search1 = filter_var(filter_input(INPUT_GET, 'video_search'), FILTER_SANITIZE_STRING);
    if (!empty($video_search) || !empty($video_search1))
        $more = 'search';
    $videoOBJ = new ContusMoreView();
    $contentvideoPlayer = $videoOBJ->video_more_pages($more);
    return $contentvideoPlayer;
}

function render_error($message) {
    echo '<div class="wrap"><h2>&nbsp;</h2>
        <div class="error" id="error">
            <p><strong>' . $message . '</strong></p>
        </div></div>';
}

function videopluginUninstalling() { //for uninstalling digicommerce-plugin tables in database
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
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare_language");
    $wpdb->query(" DROP TABLE " . $wpdb->prefix . "hdflvvideoshare");
    $wpdb->query(" DELETE FROM " . $wpdb->prefix . "posts WHERE post_type = 'videogallery'");
}

register_uninstall_hook(__FILE__, 'videopluginUninstalling');
?>