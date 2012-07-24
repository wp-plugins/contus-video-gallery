<?php
/*
  Plugin Name: Wordpress Video Gallery
  Version: 1.3
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Simplifies the process of adding video to a WordPress blog. Powered by Apptha.
  Author: Apptha
  Author URI: http://www.apptha.com
 */

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

if (file_exists($widgetPath . '/contusBannerSlideshow.php')) {
    include_once($widgetPath . '/contusBannerSlideshow.php');
} else {
    include_once(dirname(__FILE__) . '/contusBannerSlideshow.php');
}
$videoid = 0;
$site_url = get_option('siteurl');

function HDFLV_ShareParse($content) {
    $content = preg_replace_callback('/\[hdvideo ([^]]*)\o]/i', 'HDFLV_shareRender', $content);
    $content = preg_replace_callback('/\[videohome]/', 'HDFLV_homemainplayer', $content);
    //$content = preg_replace_callback('/\[videohome\]/', 'HDFLV_homepage', $content);
    $content = preg_replace_callback('/\[videomore\]/', 'HDFLV_morepage', $content);
    $content = preg_replace_callback('/\[video\]/', 'HDFLV_videopage', $content);
    $content = preg_replace_callback('/\[banner ([^]]*)\r]/i', 'HDFLV_banner', $content);

    return $content;
}

function HDFLV_banner($content) {


    global $wpdb;
    include_once("contusBannerSlideshow.php");
    $pageObj = new default_banner();
    $returnPlayer = $pageObj->videosbanner($content);
    return $returnPlayer;
}
/* * ** CONDITION FOR INCLUDE PLAUGIN LAYOUT FROM THEMES. *** */
$videoGalleryPath = get_template_directory() . '/html/contusvideogallery.php';
if (file_exists($videoGalleryPath)) {
//File include from current theme.
    require_once($videoGalleryPath);
} else {
function HDFLV_homemainplayer() {
    global $wpdb;
    include_once("themes/default/home.php");
    $pageObj = new default_home();
    // $returnPlayer = $pageObj->videosSharePlayer();
    if (!empty($_REQUEST['video_search'])) {
        return $returnPopular = $pageObj->hdvSearchVideos();
    } else {
        include_once("themes/default/home.php");
        include_once("themes/default/videocategory.php");
        $returncategories = $pageObj->categories();
        //$returnPlayer = $pageObj->videosSharePlayer();
        $videosbanner = $pageObj->videosbanner();
        //$catList = new videoCategory();
        // $catList->categoryList();
        $returnFeatures = $pageObj->featureVideos();
        $returnRecent = $pageObj->recentVideos();
        $returnPopular = $pageObj->popularVideos();

        return $returnFeatures . $returnRecent . $returnPopular . $returncategories . $videosbanner;
    }
}


    function HDFLV_homepage() {
        global $wpdb;
        include_once("themes/default/home.php");
        $pageObj = new default_home();
        if (!empty($_REQUEST['video_search'])) {
            return $returnPopular = $pageObj->hdvSearchVideos();
        } else {
            include_once("themes/default/videocategory.php");
            // return $returnPlayer = $pageObj->videosSharePlayer();
            $videosbanner = $pageObj->videosbanner();
            $catList = new videoCategory();
            // $catList->categoryList();
            $returnFeatures = $pageObj->featureVideos();
            $returnRecent = $pageObj->recentVideos();
            $returnPopular = $pageObj->popularVideos();
            return $videosbanner . $returnFeatures . $returnRecent . $returnPopular;
        }
    }

    function HDFLV_morepage() {
        global $wpdb;
        include("themes/default/more.php");
        $moreObj = new default_more();
        $moreFeature = $moreObj->featureVideos();
        $moreRecent = $moreObj->recentVideos();
        $morePopular = $moreObj->popularVideos();
        $morePlaylist = $moreObj->relatedPlaylist();
        $moreCategorylist = $moreObj->categoryList();
        return $moreFeature . $moreRecent . $morePopular . $morePlaylist . $moreCategorylist;
    }

    function HDFLV_videopage() {
        global $wpdb;
        include("themes/default/video.php");
        $pageVideos = new default_videos();
        $listVideos = $pageVideos->listVideos();
        return $listVideos;
    }

}

function HDFLV_shareRender($arguments= array()) {
    global $wpdb;
    global $videoid, $site_url;

    $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    if (isset($arguments['width'])) {
        $width = $arguments['width'];
    } else {
        $width = $configXML->width;
    }
    if (isset($arguments['height'])) {
        $height = $arguments['height'];
    } else {
        $height = $configXML->height;
    }

    $output .= "\n" . '<div align="center" id="mediaspace"><span id="video' . $videoid . '" class="HDFLV">' . "\n";
    $output .= '<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</span>' . "\n";
    $output .= '<script type="text/javascript">' . "\n";
    $output .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf' . '","n' . $videoid . '","' . $width . '","' . $height . '","7");' . "\n";
    $output .= 's' . $videoid . '.addParam("allowfullscreen","true");' . "\n";
    $output .= 's' . $videoid . '.addParam("allowscriptaccess","always");' . "\n";
    $output .= 's' . $videoid . '.addParam("wmode","opaque");' . "\n";
    $flashvars = "baserefW=" . get_option('siteurl');


    if (isset($arguments['playlistid']) && isset($arguments['id'])) {
        $flashvars .="&pid=" . $arguments['playlistid'];
        $flashvars .="&vid=" . $arguments['id'];
    } elseif (isset($arguments['playlistid'])) {
        $flashvars .="&pid=" . $arguments['playlistid'];
    } else {
        $flashvars .="&vid=" . $arguments['id'];
    }
    if (isset($arguments['flashvars'])) {
        $flashvars .= '&' . $arguments['flashvars'];
    }
    $output .= 's' . $videoid . '.addParam("FlashVars","' . $flashvars . '");' . "\n";
    $output .= 's' . $videoid . '.write("video' . $videoid . '");' . "\n";
    $output .= '</script></div>' . "\n";
    $videoid++;
    //--------------------------------HTML5 START-------------------------------------------------------------//

    /* Error Msg for Video not supported to player. */

    $output .= '<script type="text/javascript">

            function failed(e) {
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {
            alert("Player doesnot support this video."); } }</script>';
    /* Player Div */
    $vid = $arguments['id'];
    $output .='<div id="player" style="display:none;height:100%">';
    $select = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'";
    $fetched = $wpdb->get_results($select);
    foreach ($fetched as $media) {
        $videourl = $media->file;
        $imgurl = $media->image;
    }
    /* if video is youtube. */
    if (preg_match("/www\.youtube\.com\/watch\?v=[^&]+/", $videourl, $vresult)) {
        $urlArray = split("=", $vresult[0]);
        $videoid = trim($urlArray[1]);
        $output .='<iframe  type="text/html" width="' . $configXML->width . '" height="' . $configXML->height . '" src="http://www.youtube.com/embed/' . $videoid . '" frameborder="0"></iframe>';
    }

    /* if video is uploaded or direct path. */ else {
        $output .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $configXML->width . '" height="' . $configXML->height . '" autobuffer controls onerror="failed(event)">
     Html5 Not support This video Format.
</video>';
    }
    $output .='</div>';

    /* Player Div closed.
     * Script for checking platform.
     */

    $output .=' <script>
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {
               document.getElementById("player").style.display = "block";
                document.getElementById("mediaspace").style.display = "none";

            }else{
                document.getElementById("player").style.display = "none";
                document.getElementById("mediaspace").style.display = "block";

            }
        </script>';
    //--------------------------------HTML5 End-------------------------------------------------------------//
    return $output;
}

add_shortcode('hdvideo', 'HDFLV_shareRender');
add_shortcode('banner', 'HDFLV_banner');


/* Adding page & options */

function HDFLVShareAddPage() {

    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    add_menu_page("Video Gallery", "Video Gallery", 2, "hdflvvideoshare", "show_Sharemenu", get_bloginfo('url') . "/wp-content/plugins/$dirPage/images/apptha.png");
    add_submenu_page("hdflvvideoshare", "Video Gallery", "All Videos", 4, "hdflvvideoshare", "show_Sharemenu");
    add_submenu_page("hdflvvideoshare", "Video Gallery", "Play List", 4, "playlist", "show_Sharemenu");
//add_media_page(__('hdflvvideoshare', 'hdflvvideoshare'), __('Wordpress VideoGallery', 'hdflvvideoshare'), 'edit_posts', 'hdflvvideoshare', 'show_Sharemenu');
    add_submenu_page("hdflvvideoshare", "Video ADs", "Video ADs", 4, "vgads", "show_Sharemenu");
//add_media_page(__('ads', 'vgads'), __('ADS VideoGallery', 'vgads'), 'edit_posts', 'vgads', 'show_Sharemenu');
    add_submenu_page("hdflvvideoshare", "GallerySettings", "Settings", 4, "hdflvvideosharesettings", "show_Sharemenu");
//    add_options_page('Wordpress GallerySettings', 'Wordpress GallerySettings', '8', 'hdflvvideoshare.php', 'FlashShareOptions');
}

function show_Sharemenu() {
    switch ($_GET['page']) {
        case 'hdflvvideoshare' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/manage.php');
            $MediaCenter = new HDFLVShareManage();
            break;
        case 'playlist' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/playlist.php');
            $MediaCenter = new HDFLVShareManage();
            break;


        case 'vgads' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/manageAds.php');
            $MediaCenter = new HDVIDEOManageAds();
            break;


        /* Function used to Edit player settings and generate settings form elements */


        case 'hdflvvideosharesettings' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/manage.php');

            global $wpdb;
            global $site_url;
            $message = '';
            $g = array(0 => 'Properties');

            $options = get_option('HDFLVSettings');

            if ($_POST) {
                if (isset($_POST['feature'])) {
                    $feature = $_POST['feature'];
                }
                if (isset($_POST['recent'])) {
                    $recent = $_POST['recent'];
                }
                if (isset($_POST['popular'])) {
                    $popular = $_POST['popular'];
                }

// For the Player Setting checking whether the field is empty insert or else update

                $settings = $wpdb->get_col("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
                if (count($settings) > 0) {
                    $query = " UPDATE " . $wpdb->prefix . "hdflvvideoshare_settings SET
			autoplay= '" . $_POST['autoplay'] . "',playlist='" . $_POST['playlist'] . "',playlistauto='" . $_POST['playlistauto']
                            . "',buffer='" . $_POST['buffer'] . "',normalscale='" . $_POST['normalscale'] . "',fullscreenscale='" . $_POST['fullscreenscale'] . "'";
                    if ($_FILES['logopath']["name"] != '') {
                        $query .= ",logopath='" . $_FILES['logopath']["name"] . "'";
                    }
                    $query .=",colCat = '" . $_POST['colCat'] . "',rowCat = '" . $_POST['rowCat'] . "',comment_option = '" . $_POST['comment_option'] . "',logo_target='" . $_POST['logotarget'] . "',volume='" . $_POST['volume'] . "',logoalign='" . $_POST['logoalign'] . "',hdflvplayer_ads='" . $_POST['hdflvplayer_ads']
                            . "',HD_default='" . $_POST['HD_default'] . "',download='" . $_POST['download'] . "',logoalpha='" . $_POST['logoalpha'] . "',skin_autohide='" . $_POST['skin_autohide']
                            . "',stagecolor='" . $_POST['stagecolor'] . "',skin='" . $_POST['skin'] . "',embed_visible='" . $_POST['embed_visible'] . "',enable_social_share='" . $_POST['enable_social_share'] . "',enable_banner_slider='".$_POST['enable_banner_slider']."',shareURL='" . $_POST['shareURL']
                            . "',playlistXML='" . $_POST['playlistXML'] . "',debug='" . $_POST['debug'] . "',timer='" . $_POST['timer'] . "',zoom='" . $_POST['zoom']
                            . "',email='" . $_POST['email'] . "',fullscreen='" . $_POST['fullscreen'] . "',width='" . $_POST['width'] . "',height='" . $_POST['height']
                            . "',display_logo='" . $_POST['display_logo'] . "',uploads='" . $_POST['uploads'] . "',license='" . trim($_POST['license']) . "',ffmpeg_path='" . $_POST['ffmpeg_path']
                            . "',hideLogo='" . $_POST['hideLogo'] . "',keyApps ='" . $_POST['keyApps'] . "',preroll ='" . $_POST['preroll'] . "',postroll ='" . $_POST['postroll'] . "',feature='" . $_POST['feature'] . "',recent='" . $_POST['recent'] . "',popular='" . $_POST['popular']
                            . "',gutterspace='" . $_POST['gutterspace']. "',rowsFea='" . $_POST['rowsFea'] . "',colFea='" . $_POST['colFea'] . "',rowsRec='" . $_POST['rowsRec'] . "',colRec='" . $_POST['colRec']
                            . "',rowsPop='" . $_POST['rowsPop'] . "',colPop='" . $_POST['colPop'] . "',page='" . $_POST['page'] . "',category_page='" . $_POST['category_page'] . "',stylesheet='" . $_POST['stylesheet'] . "',homecategory='" . $_POST['homecategory'] . "',bannercategory='" . $_POST['bannercategory'] . "',banner_categorylist='" . $_POST['banner_categorylist'] . "',hbannercategory='" . $_POST['hbannercategory'] . "',hbanner_categorylist='" . $_POST['hbanner_categorylist']
                            . "',vbannercategory='" . $_POST['vbannercategory'] . "',vbanner_categorylist='" . $_POST['vbanner_categorylist']
                            . "',bannerw='" . $_POST['bannerw'] . "',playerw='" . $_POST['playerw'] . "',numvideos='" . $_POST['numvideos']
                            . "' WHERE settings_id = " . $settings[0]['settings_id'];

                    $updateSettings = $wpdb->query($query);
                } else {
                    $insertSettings = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_settings
						VALUES (" . $_POST['autoplay'] . "," . $_POST['playlist'] . "," . $_POST['playlistauto'] . "," . $_POST['buffer']
                                    . "," . $_POST['normalscale'] . "," . $_POST['fullscreenscale'] . "," . $_POST['logopath'] . "," . $_POST['logotarget']
                                    . "," . $_POST['volume'] . "," . $_POST['logoalign'] . "," . $_POST['hdflvplayer_ads'] . "," . $_POST['HD_default']
                                    . "," . $_POST['download'] . "," . $_POST['logoalpha'] . "," . $_POST['skin_autohide'] . "," . $_POST['stagecolor']
                                    . "," . $_POST['skin'] . "," . $_POST['embed_visible'] . "," . $_POST['shareURL'] . "," . $_POST['playlistXML']
                                    . "," . $_POST['uploads'] . "," . $_POST['debug'] . "," . $_POST['timer'] . "," . $_POST['zoom'] . "," . $_POST['email']
                                    . "," . $_POST['fullscreen'] . "," . $_POST['width'] . "," . $_POST['height'] . "," . $_POST['display_logo'] . "," . $_POST['uploadurl'] . "," . trim($_POST['license'])
                                    . "," . $_POST['hideLogo'] . "," . $_POST['keyApps'] . "," . $_POST['preroll'] . "," . $_POST['postroll'] . "," . $_POST['feature'] . "," . $_POST['rowsFea'] . "," . $_POST['colFea']
                                    . "," . $_POST['gutterspace'] . "," . $_POST['recent'] . "," . $_POST['rowsRec'] . "," . $_POST['colRec'] . "," . $_POST['ffmpeg_path']
                                    . "," . $_POST['popular'] . "," . $_POST['rowsPop'] . "," . $_POST['colPop'] . "," . $_POST['page'] . "," . $_POST['category_page'] . "," . $_POST['stylesheet'] . "," . $_POST['comment_option'] . "," . $_POST['rowCat'] . "," . $_POST['colCat'] . "," . $_POST['homecategory'] . "," . $_POST['bannercategory'] . "," . $_POST['banner_categorylist'] . "," . $_POST['vbannercategory'] . "," . $_POST['vbanner_categorylist']
                                    . "," . $_POST['bannerw'] . "," . $_POST['playerw'] . "," . $_POST['numvideos'] . ")");
                }
                move_uploaded_file($_FILES["logopath"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["logopath"]["name"]);
                $message = '<div class="updated"><p><strong>Options saved.</strong></p></div>';


                $langSettings = $wpdb->get_col("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_language");
                if (count($langSettings) > 0) {

                    $langsetUpdate = "UPDATE " . $wpdb->prefix . "hdflvvideoshare_language SET
	   play= '" . $_POST['play'] . "',pause= '" . $_POST['pause'] . "',hdison= '" . $_POST['hdison'] . "',hdisoff= '" . $_POST['hdisoff'] . "',zoom= '" . $_POST['lang_zoom'] . "'
           ,share= '" . $_POST['lang_share'] . "',lang_fullscreen= '" . $_POST['lang_fullscreen'] . "',relatedvideos= '" . $_POST['relatedvideos'] . "'
           ,sharetheword= '" . $_POST['sharetheword'] . "',sendanemail= '" . $_POST['sendanemail'] . "' ,`to`= '" . $_POST['to'] . "',`from`= '" . $_POST['from'] . "',`note`= '" . $_POST['note'] . "',`send`= '" . $_POST['send'] . "',`copylink`= '" . $_POST['copylink'] . "'
           ,`copyembed`= '" . $_POST['copyembed'] . "',`facebook`= '" . $_POST['facebook'] . "',reddit= '" . $_POST['reddit'] . "',friendfeed= '" . $_POST['friendfeed'] . "',slashdot= '" . $_POST['slashdot'] . "'
           ,delicious= '" . $_POST['delicious'] . "',myspace= '" . $_POST['myspace'] . "',wong= '" . $_POST['wong'] . "',digg= '" . $_POST['digg'] . "',blinklist= '" . $_POST['blinklist'] . "'
           ,bebo= '" . $_POST['bebo'] . "',fark= '" . $_POST['fark'] . "',tweet= '" . $_POST['tweet'] . "',furl= '" . $_POST['furl'] . "' WHERE lang_id=1";
                    $langUpdated = $wpdb->query($langsetUpdate);
                } else {
                    $langsetInsert = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_language
             VALUES(" . $_POST['play'] . "," . $_POST['pause'] . "," . $_POST['hdison'] . "," . $_POST['hdisoff'] . "," . $_POST['lang_zoom'] .
                                    "," . $_POST['lang_share'] . "," . $_POST['lang_fullscreen'] . "," . $_POST['relatedvideos'] . "," . $_POST['sharetheword'] . "," . $_POST['sendanemail'] .
                                    "," . $_POST['to'] . "," . $_POST['from'] . "," . $_POST['note'] . "," . $_POST['send'] . "," . $_POST['copylink'] .
                                    "," . $_POST['copyembed'] . "," . $_POST['facebook'] . "," . $_POST['reddit'] . "," . $_POST['friendfeed'] . "," . $_POST['slashdot'] .
                                    "," . $_POST['delicious'] . "," . $_POST['myspace'] . "," . $_POST['wong'] . "," . $_POST['digg'] . "," . $_POST['blinklist'] .
                                    "," . $_POST['bebo'] . "," . $_POST['fark'] . "," . $_POST['tweet'] . "," . $_POST['furl'] . ")");
                }
            }
// For the Language XML settings checking whether the field is empty insert or else update





            echo $message;

            $ski = str_replace('wp-admin', 'wp-content', dirname($_SERVER['SCRIPT_FILENAME'])) . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/skin';

            $skins = array();

            // Pull the directories listed in the skins folder to generate the dropdown list with valid skin files
            chdir($ski);
            if ($handle = opendir($ski)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        if (is_dir($file)) {
                            $skins[] = $file;
                        }
                    }
                }
                closedir($handle);
            }

            $fetchSettings = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
            $fetchLanguage = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_language");
?>
            <!--HTML design for admin settings -->
            <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/jquery.ui.all.css'; ?>">

            <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery-1.4.4.js'; ?>"></script>
            <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.core.js'; ?>"></script>
            <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.widget.js'; ?>"></script>
            <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.mouse.js'; ?>"></script>
            <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.sortable.js'; ?>"></script>
            <style>
                .column { width: 500px; float: left; padding-bottom: 100px; }
                .portlet { margin: 0 1em 1em 0; }
                .portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 10px;padding-top: 4px;font-size:12px; }
                .portlet-header .ui-icon { float: right; }
                .portlet-content { padding: 0.4em; font-size:12px;}
                .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
                .ui-sortable-placeholder * { visibility: hidden; }
            </style>
            <script>
                $(function() {
                    $( ".column" ).sortable({
                        connectWith: ".column"
                    });

                    $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
                    .find( ".portlet-header" )
                    .addClass( "ui-widget-header ui-corner-all" )
                    .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
                    .end()
                    .find( ".portlet-content" );

                    $( ".portlet-header .ui-icon" ).click(function() {
                        $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
                        $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
                    });

                });
            </script>

            <div class="wrap">
            <?php 
             $folder   = dirname(plugin_basename(__FILE__));
            $site_url = get_bloginfo('url');
            $get_title = $wpdb->get_var("SELECT license FROM ".$wpdb->prefix."hdflvvideoshare_settings WHERE settings_id=1");
          
                  $get_key     = app_videogall_encrypt();
                  if($get_title != $get_key)
        {
            ?>
                <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
                <img src="<?php echo $site_url.'/wp-content/plugins/'.$folder.'/images/buynow.png';?>" style="float:right;margin-top:10px" width="125" height="28"  height="43" /></a>
	          <?php  } ?>             
                <h2>Wordpress Video Gallery Settings</h2>
                <form method="post" enctype="multipart/form-data" action="admin.php?page=hdflvvideoshare">
                    <div><p style="float:left">Welcome to the Wordpress Video Gallery Settings plugin options menu! &nbsp;&nbsp;
                            <input class="button-primary" type="submit" value="<?php _e('Add Video', 'hdflvvideoshare') ?> &raquo;" name="show_add" />

                        </p></div></form>
                <form method="post" enctype="multipart/form-data" action="admin.php?page=hdflvvideosharesettings">
                    <div> <p class='submit' style="float:left; padding-left: 350px"><input class='button-primary' type='submit' value='Update Options'></p></div>
                    <div style="clear:both"></div>
                    <div class="column">

                        <div class="portlet">
                            <div class="portlet-header">Display Configuration</div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'>Auto Play</th>
                                        <td><input type='checkbox' class='check' name="autoplay" <?php if ($fetchSettings->autoplay == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'>Player Width</th>
                                        <td><input type='text' name="width" value="<?php echo $fetchSettings->width ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'>Player Height</th>
                                        <td><input type='text' name="height" value="<?php echo $fetchSettings->height ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'>Stagecolor</th>
                                        <td><input type='text' name="stagecolor" value="<?php echo $fetchSettings->stagecolor ?>" size=45  />
                                            <br /><?php _e('example for stage color : 0xdddddd ', 'hdflvvideoshare') ?>



                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="portlet">
                            <div class="portlet-header">Playlist Configuration</div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'>Playlist</th>
                                        <td><input type='checkbox' class='check' name="playlist" <?php if ($fetchSettings->playlist == 1) { ?> checked <?php } ?> value="1" size=45   /></td>

                                    </tr>
                                    <tr>
                                        <th scope='row'>HD Default</th>
                                        <td><input type='checkbox' class='check' name="HD_default" <?php if ($fetchSettings->HD_default == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'>Playlist Autoplay</th>
                                        <td><input type='checkbox' class='check' <?php if ($fetchSettings->playlistauto == 1) { ?> checked <?php } ?> name="playlistauto" value="1" size=45  /></td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-header">License Configuration</div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'>License Key</th>
                                        <td><input type='text' name="license" value="<?php echo $fetchSettings->license ?>" size=45  /></td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="portlet">
                            <div class="portlet-header">Facebook Settings</div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'>Facebook Comment</th>
                                        <td> <input type="radio" <?php if ($fetchSettings->comment_option == 1) { ?>checked="checked"<?php } ?> name="comment_option" value="1" /><label>Enable</label>
                                            <input type="radio" <?php if ($fetchSettings->comment_option == 0) { ?>checked="checked"<?php } ?> name="comment_option" value="0" /><label>Disable</label></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'>App ID</th>
                                        <td><input type='text' name="keyApps" value="<?php echo $fetchSettings->keyApps ?>" size=45  /></td>
                                    </tr>
                                    <tr><td> <a href="http://developers.facebook.com/" target="_blank">Link to create App ID</a></td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-header">Ads Settings</div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <!-- Preroll -->
                                    <tr>
                                        <th scope='row'>Preroll Ads</th>
                                        <td>
                                            <input name="preroll" id="preroll" type='radio' value="0"  <?php if ($fetchSettings->preroll == 0)
                echo 'checked'; ?> />Enable
                                            <input name="preroll" id="preroll" type='radio' value="1"  <?php if ($fetchSettings->preroll == 1)
                echo 'checked'; ?> />Disable
                            </td>
                        </tr>
                        <!-- Postroll -->
                        <tr>
                            <th scope='row'>Postroll Ads</th>
                            <td>
                                <input name="postroll" id="postroll" type='radio' value="0"  <?php if ($fetchSettings->postroll == 0)
                echo 'checked'; ?> />Enable
                                <input name="postroll" id="postroll" type='radio' value="1"  <?php if ($fetchSettings->postroll == 1)
                echo 'checked'; ?> />Disable
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="portlet">
                <div class="portlet-header">Logo Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">
                        <tr>
                            <th scope='row'>Logo Path</th>
                            <td><input type='file' name="logopath" value="" size=40  /><?php echo $fetchSettings->logopath ?></td>
                        </tr>
                        <tr>
                            <th scope='row'>Logo Target</th>
                            <td><input type='text' name="logotarget" value="<?php echo $fetchSettings->logo_target ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Logo Align</th>
                            <td> <select name="logoalign" style="width:150px;">
                                    <option <?php if ($fetchSettings->logoalign == 'TL') { ?> selected="selected" <?php } ?> value="TL">Top Left</option>
                                    <option <?php if ($fetchSettings->logoalign == 'TR') { ?> selected="selected" <?php } ?> value="TR">Top Right</option>
                                    <option <?php if ($fetchSettings->logoalign == 'LB') { ?> selected="selected" <?php } ?> value="LB">Left Bottom</option>
                                    <option <?php if ($fetchSettings->logoalign == 'RB') { ?> selected="selected" <?php } ?> value="RB">Right Bottom</option>
                                </select></td>
                        </tr>
                        <tr>
                            <th scope='row'>Logo Alpha</th>
                            <td><input type='text' name="logoalpha" value="<?php echo $fetchSettings->logoalpha ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Hide YouTube Logo</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->hideLogo == true) { ?> checked <?php } ?> name="hideLogo" value="true" size=45  /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--            <div class="portlet">
                            <div class="portlet-header">StyleSheet Configuration</div>
                            <div class="portlet-content">
                                <table class="form-table">


                                    <tr><td>
                                            <input type='radio' name="stylesheet"  value="default" <?php if ($fetchSettings->stylesheet == 'default')
                echo 'checked'; ?> />Current Theme
                                            <input  type='radio' name="stylesheet"  value="contus"  <?php if ($fetchSettings->stylesheet == 'contus')
                echo 'checked'; ?> />Custom Theme</td></tr>

                                </table>
                            </div>
                        </div>-->
            <div class="portlet">
                <div class="portlet-header">Video Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">

                        <tr>
                            <th scope='row'>Download</th>
                            <td><input type='checkbox' class='check' name="download" <?php if ($fetchSettings->download == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Buffer</th>
                            <td><input type='text' name="buffer" value="<?php echo $fetchSettings->buffer ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Volume</th>
                            <td><input type='text' name="volume" value="<?php echo $fetchSettings->volume ?>" size=45  /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-header">General Settings</div>
                <div class="portlet-content">
                    <table class="form-table">

                        <tr>
                            <th scope='row'>FFMPEG Path</th>
                            <td><input type='text' name="ffmpeg_path" value="<?php echo $fetchSettings->ffmpeg_path; ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Normal Scale</th>
                            <td>
                                <select name="normalscale" style="width:150px;">
                                    <option value="0" <?php if ($fetchSettings->normalscale == 0) { ?> selected="selected" <?php } ?> >Aspect Ratio</option>
                                    <option value="1" <?php if ($fetchSettings->normalscale == 1) { ?> selected="selected" <?php } ?>>Original Screen</option>
                                    <option value="2" <?php if ($fetchSettings->normalscale == 2) { ?> selected="selected" <?php } ?>>Fit To Screen</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Full Screen Scale</th>
                            <td>
                                <select name="fullscreenscale" style="width:150px;">
                                    <option value="0" <?php if ($fetchSettings->fullscreenscale == 0) { ?> selected="selected" <?php } ?>>Aspect Ratio</option>
                                    <option value="1" <?php if ($fetchSettings->fullscreenscale == 1) { ?> selected="selected" <?php } ?>>Original Screen</option>
                                    <option value="2" <?php if ($fetchSettings->fullscreenscale == 2) { ?> selected="selected" <?php } ?>>Fit To Screen</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Uploads</th>
                            <td>
            <!--                    <label><input name="usedefault" type='radio' value="1" <?php if ($setting['v'] == 1)
                echo 'checked'; ?> /> <?php _e('Standard upload folder : ', 'hdflvvideoshare') ?></label><code><?php echo get_option('upload_path'); ?></code><br />-->
                                <label><input name="usedefault" type='radio' value="0"  <?php if ($setting['v'] == 0)
                echo 'checked'; ?> /> <?php _e('Store uploads in this folder : ', 'hdflvvideoshare') ?></label>
                                <input type="text" size="50" maxlength="200" name='uploads' value="<?php echo $fetchSettings->uploads; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Embed Visible</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->embed_visible == 1) { ?> checked <?php } ?> name="embed_visible" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Enable social share</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->enable_social_share == 1) { ?> checked <?php } ?> name="enable_social_share" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Enable banner slider</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->enable_banner_slider == 1) { ?> checked <?php } ?> name="enable_banner_slider" value="1" size=45  /></td>
                        </tr>
<!--                        <tr>
                            <th scope='row'>Debug</th>
                            <td><input type='checkbox' class='check' <?php //if ($fetchSettings->debug == 1) {  ?> checked <?php //}  ?> name="debug" value="1" size=45  /></td>
                        </tr>-->

                    </table>
                </div>
            </div>


            <div class="portlet">
                <div class="portlet-header">Skin Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">
                        <tr>
                            <th scope='row'>Skin</th>
                            <td>
                                <select name="skin" style="width:150px;">
<?php foreach ($skins as $skin) {
?>
                                                  <option <?php if ($fetchSettings->skin == $skin) { ?> selected="selected" <?php } ?> value="<?php echo $skin; ?>"><?php echo $skin; ?></option>
<?php } ?>
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Timer</th>
                                      <td><input type='checkbox' class='check' <?php if ($fetchSettings->timer == 1) { ?> checked <?php } ?> name="timer" value="1" size=45  /></td>
                              </tr>
                              <tr>
                                  <th scope='row'>Zoom</th>
                                  <td><input type='checkbox' class='check' <?php if ($fetchSettings->zoom == 1) { ?> checked <?php } ?> name="zoom" value="1" size=45  /></td>
                              </tr>
                              <tr>
                                  <th scope='row'>Share</th>
                                  <td><input type='checkbox' class='check' <?php if ($fetchSettings->email == 1) { ?> checked <?php } ?> name="email" value="1" size=45  /></td>
                              </tr>
                              <tr>
                                  <th scope='row'>Full Screen</th>
                                  <td><input type='checkbox' class='check' <?php if ($fetchSettings->fullscreen == 1) { ?> checked <?php } ?> name="fullscreen" value="1" size=45  /></td>
                              </tr>
                              <tr>
                                  <th scope='row'>Skin Autohide</th>
                                  <td><input type='checkbox' class='check' <?php if ($fetchSettings->skin_autohide == 1) { ?> checked <?php } ?> name="skin_autohide" value="1" size=45  /></td>
                              </tr>
                          </table>
                      </div>
                  </div>
              </div>
              <div class="column">


                  <div class="portlet">
                      <div class="portlet-header">Videos Page Settings</div>
                      <div class="portlet-content">
                          <table class="form-table">



                              <!--videos page banner settings-->
                              <script>

                                  function vbanner(hvalue,hcatid)
                                  {
                                      var vbanner ='';

                                      document.getElementById("vbanner_categorylist").style.display = 'none';
                                      switch(hvalue)
                                      {


                                          case "vcategory":
                                              vbanner ='vcategory,$hcatid='+hcatid;
                                              document.getElementById("vbanner_categorylist").style.display = 'block';
                                              break;

                                      }
                                  }

                              </script>

<?php
                                              $vbannercategories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist ");
                                              $vbannercategorylist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings ");
?> 


                                              <tr>
                                                  <th>Banner Videos</th>

                                                  <td><input  onclick="vbanner(this.value,'');" type='radio' name="vbannercategory"  value="vpopular" <?php if ($fetchSettings->vbannercategory == vpopular)
                                                  echo 'checked'; ?> /><?php _e('Popular Videos','hdflvvideoshare');?><br/>
                                                      <input  onclick="vbanner(this.value,'');" type='radio' name="vbannercategory"  value="vrecent"  <?php if ($fetchSettings->vbannercategory == vrecent)
                                                  echo 'checked'; ?> />Recent Videos<br/>
                                                      <input onclick="vbanner(this.value,'');" type='radio'  name="vbannercategory"  value="vfeatured"  <?php if ($fetchSettings->vbannercategory == vfeatured)
                                                  echo 'checked'; ?> />Featured Videos<br/>
                                      <input onclick="vbanner(this.value,'');" type='radio'  name="vbannercategory"  onclick="vbanner(this.value,'<?php echo $vbannercategorylist->vbanner_categorylist; ?>');" value="vcategory"  <?php if ($fetchSettings->vbannercategory == vcategory)
                                                  echo 'checked'; ?> />Category Videos



                                      <select  style="width:120px;" name='vbanner_categorylist' id='vbanner_categorylist'>
<?php
                                              $content = "";
                                              foreach ($vbannercategories as $res) {//retriving currency type from wp_digi_currency table
                                                  $content .= '<option value="' . $res->pid . '" ';
                                                  $content .= ( $vbannercategorylist->vbanner_categorylist == $res->pid) ? "selected=selected" : "";
                                                  $content .= ' >' . $res->playlist_name . '</option>';
                                              }
                                              echo $content;
?>
                                          </select>

                                          <script> vbanner("<?php echo $fetchSettings->vbannercategory; ?>",'<?php echo $vbannercategorylist->vbanner_categorylist; ?>');</script>
                                      </td>
                                  </tr>


                                  <tr><th>Banner Width</th>
                                      <td><input type="text" name="bannerw" id="colRec" size="20" value="<?php echo $fetchSettings->bannerw; ?>"></td>
                                  </tr>

                                  <tr><th>Player Width</th>
                                      <td><input type="text" name="playerw" id="colRec" size="20" value="<?php echo $fetchSettings->playerw; ?>"></td>
                                  </tr>

                                  <tr><th>Number of Videos in Banner</th>
                                      <td><input type="text" name="numvideos" id="colRec" size="20" value="<?php echo $fetchSettings->numvideos; ?>"></td>
                                  </tr>

                                  <!--videos page banner settings-->

                                  <!-- Popular Videos-->
                                   <tr><th>Gutter Space (px)</th>
                                      <td><input type="text" name="gutterspace" id="gutterspace" size="20" value="<?php echo $fetchSettings->gutterspace; ?>"></td>
                                  </tr>
                                  <tr>

                                      <th>Popular Videos</th>
                                      <td><input  type='radio' name="popular"  value="on" <?php if ($fetchSettings->popular == on)
                                                  echo 'checked'; ?> />Enable
                                          <input type='radio' name="popular"  value="off"  <?php if ($fetchSettings->popular == off)
                                                  echo 'checked'; ?> />Disable<br></td></tr>

                                  <tr><td>Rows<input type="text" name="rowsPop" id="rowsPop" size="10" value="<?php echo $fetchSettings->rowsPop; ?>"></td>
                                  <td>Columns <input type="text" name="colPop" id="colPop" size="10" value="<?php echo $fetchSettings->colPop; ?>"></td>
                               </tr>

                               <!-- Recent Videos-->
                               <tr>
                                   <th>Recent Videos</th>
                                   <td><input type='radio' name="recent"  value="on" <?php if ($fetchSettings->recent == on)
                                                  echo 'checked'; ?> />Enable
                                       <input type='radio' name="recent"  value="off"  <?php if ($fetchSettings->recent == off)
                                                  echo 'checked'; ?> />Disable<br></td></tr>

                               <tr>
                                   <td>Rows<input type="text" name="rowsRec" id="rowsRec" size="10" value="<?php echo $fetchSettings->rowsRec; ?>"></td>
                                   <td>Columns <input type="text" name="colRec" id="colRec" size="10" value="<?php echo $fetchSettings->colRec; ?>">
                                   </td>
                               </tr>

                               <!-- Featured Videos  -->
                               <tr>
                                   <th>Featured Videos</th>
                                   <td><input type='radio' name="feature"  value="on" <?php if ($fetchSettings->feature == on)
                                                  echo 'checked'; ?> />Enable
                                       <input  type='radio' name="feature"  value="off"  <?php if ($fetchSettings->feature == off)
                                                  echo 'checked'; ?> />Disable<br></td></tr>

                               <tr><td>Rows<input type="text" name="rowsFea" id="rowsFea" size="10" value="<?php echo $fetchSettings->rowsFea; ?>"></td>
                                   <td>Columns<input type="text" name="colFea" id="colFea" size="10" value="<?php echo $fetchSettings->colFea; ?>">
                                  </td>
                              </tr>


                              <tr>
                                  <th>Category Videos</th>
                                  <td><input type='radio' name="homecategory"  value="on" <?php if ($fetchSettings->homecategory == on)
                                                  echo 'checked'; ?> />Enable
                                      <input type='radio' name="homecategory"  value="off"  <?php if ($fetchSettings->homecategory == off)
                                                  echo 'checked'; ?> />Disable<br></td>
                              </tr>

                              <tr><td>Rows<input type="text" name="rowCat" id="rowCat" size="10" value="<?php echo $fetchSettings->rowCat; ?>"></td>
                                   <td>Columns<input type="text" name="colCat" id="colCat" size="10" value="<?php echo $fetchSettings->colCat; ?>">
                                   </td>
                               </tr>
                               <tr><td>No Of Categories in More page</td>
                                   <td><input type="text" name="category_page" id="category_page" value="<?php echo $fetchSettings->category_page; ?>"></td>
                               </tr>

                               <tr><td>No Of Videos in More page</td>
                                   <td><input type="text" name="page" id="page" value="<?php echo $fetchSettings->page; ?>"></td>
                               </tr>
                           </table>
                       </div>
                   </div>







                   <!-- banner settings for header--->

<?php
                                              $hbannercategories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist ");
                                              $hbannercategorylist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings ");
?>   <script>

                                                  function hbanner(hvalue,hcatid)
                                                  {
                                                      var hbanner ='';

                                                      document.getElementById("hbanner_categorylist").style.display = 'none';
                                                      switch(hvalue)
                                                      {
                                                          case "hpopular":
                                                              hbanner ="hpopular , $hcatid = ''";
                                                              break;
                                                          case "hrecent":
                                                              hbanner ="hrecent , $hcatid = ''";
                                                              break;
                                                          case "hfeatured":
                                                              hbanner ="hfeatured  , $hcatid = ''";
                                                              break;
                                                          case "hcategory":
                                                              hbanner ='hcategory,$hcatid='+hcatid;
                                                              document.getElementById("hbanner_categorylist").style.display = 'block';
                                                              break;

                                                      }
                                                      var hpop = 'Hbanner('+hbanner+' ,$hwidth=940, $hplayerwidth=640, $hnumberofvideos=5); ';
                                                      document.getElementById("hpopular").innerHTML = hpop;
                                                  }

                                              </script>
                                              <div class="portlet">
                                                  <div class="portlet-header">
                                                      Generate Short Code for Banner - Header/Index </div>
                                                  <div class="portlet-content">
                                                      <table class="form-table">
                                                          <tr>
                                                              <th>Banner Videos </th>

                                                              <td><input onclick="hbanner(this.value,'');" type='radio' name="hbannercategory"  value="hpopular" <?php if ($fetchSettings->hbannercategory == hpopular)
                                                  echo 'checked'; ?> />Popular Videos<br/>
                                                                  <input onclick="hbanner(this.value,'');" type='radio' name="hbannercategory"  value="hrecent"  <?php if ($fetchSettings->hbannercategory == hrecent)
                                                  echo 'checked'; ?> />Recent Videos<br/>

                                                                  <input type='radio' onclick="hbanner(this.value,'');"  name="hbannercategory"  value="hfeatured"  <?php if ($fetchSettings->hbannercategory == hfeatured)
                                                  echo 'checked'; ?> />Featured Videos<br/>
                                       <input type='radio' onclick="hbanner(this.value,'<?php echo $hbannercategorylist->hbanner_categorylist; ?>');"  name="hbannercategory"  value="hcategory"  <?php if ($fetchSettings->hbannercategory == hcategory)
                                                  echo 'checked'; ?> />Category Videos



                                       <select  style="width:120px;" name='hbanner_categorylist' id='hbanner_categorylist'>
<?php
                                              $content = "";
                                              foreach ($hbannercategories as $res) {//retriving currency type from wp_digi_currency table
                                                  $content .= '<option value="' . $res->pid . '" ';
                                                  $content .= ( $hbannercategorylist->hbanner_categorylist == $res->pid) ? "selected=selected" : "";
                                                  $content .= ' >' . $res->playlist_name . '</option>';
                                              }
                                              echo $content;
?>
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          <script> hbanner("<?php echo $fetchSettings->hbannercategory; ?>",'<?php echo $hbannercategorylist->hbanner_categorylist; ?>');</script>
                                          <strong style="background:#D0D0D0; width: 98%; padding: 1% 2%; list-style:none; float:left" >
                                              Copy the following text to insert the Banner in Header    </strong>
                                          <div style="background:#D0D0D0; width: 98%; padding: 1% 2%; list-style:none; float:left"> <?php echo "&lt;?php"; ?>   <span id="hpopular">
                                                  Hbanner(<?php echo $fetchSettings->hbannercategory; ?>,<?php echo ($fetchSettings->hbannercategory == 'hcategory') ? '$hcatid=' . $hbannercategorylist->hbanner_categorylist : '$hcatid=\'\''; ?>,$hwidth=940,$hplayerwidth=640,$hnumberofvideos=5);

                                              </span><?php echo "?&gt;"; ?></div></div>
                                      </td>
                                  </tr>
                              </table>
                          </div>

                      </div>
                      <!--banner settings for header>



                      <!-- banner settings for pages--->

<?php
                                              $bannercategories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist ");
                                              $bannercategorylist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings ");
?>   <script>

                                                  function banner(value,catid)
                                                  {
                                                      var banner ='';

                                                      document.getElementById("banner_categorylist").style.display = 'none';
                                                      switch(value)
                                                      {
                                                          case "popular":
                                                              banner ='type="popular"';
                                                              break;
                                                          case "recent":
                                                              banner ='type="recent"';
                                                              break;
                                                          case "featured":
                                                              banner ='type="featured"';
                                                              break;
                                                          case "category":
                                                              banner ='type="category" catid='+catid;
                                                              document.getElementById("banner_categorylist").style.display = 'block';
                                                              break;

                                                      }
                                                      var pop = '[banner '+banner+' width=650 playerwidth=450 numberofvideos=4]';
                                                      document.getElementById("popular").innerHTML = pop;
                                                  }

                                              </script>
                                              <div class="portlet">
                                                  <div class="portlet-header">
                                                      Generate Short Code for Banner - Pages / Posts </div>
                                                  <div class="portlet-content">
                                                      <table class="form-table">
                                                          <tr>
                                                              <th>Banner Videos</th>

                                                              <td ><input onclick="banner(this.value,'');" type='radio' name="bannercategory"  value="popular" <?php if ($fetchSettings->bannercategory == popular)
                                                  echo 'checked'; ?> />Popular Videos<br/>
                                                                  <input onclick="banner(this.value,'');" type='radio' name="bannercategory"  value="recent"  <?php if ($fetchSettings->bannercategory == recent)
                                                  echo 'checked'; ?> />Recent Videos<br/>

                                                                  <input type='radio' onclick="banner(this.value,'');"  name="bannercategory"  value="featured"  <?php if ($fetchSettings->bannercategory == featured)
                                                  echo 'checked'; ?> />Featured Videos<br/>
                                       <input type='radio' onclick="banner(this.value,'<?php echo $bannercategorylist->banner_categorylist; ?>');"  name="bannercategory"  value="category"  <?php if ($fetchSettings->bannercategory == category)
                                                  echo 'checked'; ?> />Category Videos



                                       <select  style="width:120px;" name='banner_categorylist' id='banner_categorylist'>
<?php
                                              $content = "";
                                              foreach ($bannercategories as $res) {//retriving currency type from wp_digi_currency table
                                                  $content .= '<option value="' . $res->pid . '" ';
                                                  $content .= ( $bannercategorylist->banner_categorylist == $res->pid) ? "selected=selected" : "";
                                                  $content .= ' >' . $res->playlist_name . '</option>';
                                              }
                                              echo $content;
?>
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          <script> banner("<?php echo $fetchSettings->bannercategory; ?>",'<?php echo $bannercategorylist->banner_categorylist; ?>');</script>
                                          <strong style="background:#D0D0D0; width: 98%; padding: 1% 2%; list-style:none; float:left" >Copy the following text to insert the banner in pages or post</strong>

                                          <div style="background:#D0D0D0; width: 98%; padding: 1% 2%; list-style:none; float:left" id="popular">
                                              [banner type="<?php echo $fetchSettings->bannercategory; ?>" <?php echo ($fetchSettings->bannercategory == 'category') ? "catid=$bannercategorylist->banner_categorylist" : ""; ?> width=650 playerwidth=450 numberofvideos=4]
                                          </div>
                                      </td>
                                  </tr>
                              </table>
                          </div>

                      </div>
            <!--banner settings for pages> -->
            <!-- Language XML -->
                      <div class="portlet">
                          <div class="portlet-header">Language Settings</div>
                          <div class="portlet-content">
                              <table class="form-table">
                                  <tr>
                                      <th scope='row'>Play</th>
                                      <td><input type='text' class='text' name="play"  value="<?php echo $fetchLanguage->play; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Pause</th>
                                      <td><input type='text' class='text' name="pause"  value="<?php echo $fetchLanguage->pause; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>HD is On</th>
                                      <td><input type='text' class='text' name="hdison"  value="<?php echo $fetchLanguage->hdison; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>HD is Off</th>
                                      <td><input type='text' class='text' name="hdisoff"  value="<?php echo $fetchLanguage->hdisoff; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Zoom</th>
                                      <td><input type='text' class='text' name="lang_zoom"  value="<?php echo $fetchLanguage->zoom; ?>"  size=25  /></td>
                                  </tr>


                                  <tr>
                                      <th scope='row'>Share</th>
                                      <td><input type='text' class='text' name="lang_share"  value="<?php echo $fetchLanguage->share; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Fullscreen</th>
                                      <td><input type='text' class='text' name="lang_fullscreen"  value="<?php echo $fetchLanguage->lang_fullscreen; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Related Video</th>
                                      <td><input type='text' class='text' name="relatedvideos"  value="<?php echo $fetchLanguage->relatedvideos; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Share the Word</th>
                                      <td><input type='text' class='text' name="sharetheword"  value="<?php echo $fetchLanguage->sharetheword; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Send an Email</th>
                                      <td><input type='text' class='text' name="sendanemail"  value="<?php echo $fetchLanguage->sendanemail; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>To</th>
                                      <td><input type='text' class='text' name="to"  value="<?php echo $fetchLanguage->to; ?>"  size=25  /></td>
                                  </tr>


                                  <tr>
                                      <th scope='row'>From</th>
                                      <td><input type='text' class='text' name="from"  value="<?php echo $fetchLanguage->from; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Note (Optional)</th>
                                      <td><input type='text' class='text' name="note"  value="<?php echo $fetchLanguage->note; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Send</th>
                                      <td><input type='text' class='text' name="send"  value="<?php echo $fetchLanguage->send; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Copy Link</th>
                                      <td><input type='text' class='text' name="copylink"  value="<?php echo $fetchLanguage->copylink; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Copy Embedcode</th>
                                      <td><input type='text' class='text' name="copyembed"  value="<?php echo $fetchLanguage->copyembed; ?>"  size=25  /></td>
                                  </tr>



                                  <tr>
                                      <th scope='row'>Facebook</th>
                                      <td><input type='text' class='text' name="facebook"  value="<?php echo $fetchLanguage->facebook; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Reddit</th>
                                      <td><input type='text' class='text' name="reddit"  value="<?php echo $fetchLanguage->reddit; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Friendfeed</th>
                                      <td><input type='text' class='text' name="friendfeed"  value="<?php echo $fetchLanguage->friendfeed; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Slashdot</th>
                                      <td><input type='text' class='text' name="slashdot"  value="<?php echo $fetchLanguage->slashdot; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Delicious</th>
                                      <td><input type='text' class='text' name="delicious"  value="<?php echo $fetchLanguage->delicious; ?>"  size=25  /></td>
                                  </tr>



                                  <tr>
                                      <th scope='row'>Myspace</th>
                                      <td><input type='text' class='text' name="myspace"  value="<?php echo $fetchLanguage->myspace; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Wong</th>
                                      <td><input type='text' class='text' name="wong"  value="<?php echo $fetchLanguage->wong; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Digg</th>
                                      <td><input type='text' class='text' name="digg"  value="<?php echo $fetchLanguage->digg; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Blinklist</th>
                                      <td><input type='text' class='text' name="blinklist"  value="<?php echo $fetchLanguage->blinklist; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Bebo</th>
                                      <td><input type='text' class='text' name="bebo"  value="<?php echo $fetchLanguage->bebo; ?>"  size=25  /></td>
                                  </tr>

                                  <tr>
                                      <th scope='row'>Fark</th>
                                      <td><input type='text' class='text' name="fark"  value="<?php echo $fetchLanguage->fark; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Tweet</th>
                                      <td><input type='text' class='text' name="tweet"  value="<?php echo $fetchLanguage->tweet; ?>"  size=25  /></td>
                                  </tr>
                                  <tr>
                                      <th scope='row'>Furl</th>
                                      <td><input type='text' class='text' name="furl"  value="<?php echo $fetchLanguage->furl; ?>"  size=25  /></td>
                                  </tr>
                              </table>
                          </div>
                      </div>
                      <!-- End of Language XML -->

                  </div>
                  <p class='submit' style="float:left; padding-left: 866px "><input class='button-primary' type='submit' value='Update Options'></p>
                  <div class="clear"></div>
              </form>
          </div>
<?php
                                              break;
                                      }
                                  }

                                  function Hdflv_Sharehead() {
                                      global $site_url;
                                      echo '<script type="text/javascript" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/swfobject.js"></script>' . "\n";
                                  }

                                  add_action('wp_head', 'Hdflv_Sharehead');

                                  /* Loading default settings of player */

                                  function HdflvloadSharedefaults() {
                                      global $wpdb;
                                      $insertSettings = $wpdb->query("INSERT INTO " . $wpdb->prefix . " hdflvvideoshare_settings
		      VALUES (1,0,0,0,1,1,1,'platoon.jpg','http://www.hdflvplayer.net/',50,'LR',1,1,0,20,1,'0x000000',
                      'skin_black',0,'hdflvplayer/videourl.php','playXml',1,1,1,1,1,630,400,1,0,'wp-content/uploads','',
                      'true','',0,0,'on', '3', '3', 'on', '3', '3', 'on', '3', '3', '3' ,'3','contus','10')");
                                      $insertLanguage = $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_language
						VALUES (1,'play','pause','HD is On','HD is Off','Zoom','Share','Fullscreen','Related Videos',
                                                'Share the Word','Send an Email','To','From','Note (Optional)','Send','Copy Link',
                                                'Copy Embedcode','Facebook','reddit','friendfeed','slashdot','delicious','myspace','wong',
                                                'digg','blinklist','bebo', 'fark','tweet','furl')");
                                     
                                   
                                      $table_tags = $wpdb->prefix . 'hdflvvideoshare_tags';
                                      $table_tagsdata = $wpdb->get_results("SELECT * FROM " . $table_tags);
                                     
                                  }
                                  
                                  
    function app_videogall_encrypt() 
	{

		$code = genenrateOscdomain(); 
		$app_videogall_getOffset = substr($code, 0, 25) . "CONTUS";
		return $app_videogall_getOffset;
		
	}

	function app_videogall_getOffset($tkey) {

		$message = "EW-VGMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

		for ($i = 0; $i < strlen($tkey); $i++) {
			$key_array[] = $tkey[$i];
		}
		$enc_message = "";
		$kPos = 0;
		$chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		for ($i = 0; $i < strlen($chars_str); $i++) {
			$chars_array[] = $chars_str[$i];
		}
		for ($i = 0; $i < strlen($message); $i++) {
			$char = substr($message, $i, 1);

			$offset = getOffset($key_array[$kPos], $char);
			$enc_message .= $chars_array[$offset];
			$kPos++;
			if ($kPos >= count($key_array)) {
				$kPos = 0;
			}
		}

		return $enc_message;
	}
	 function license()
	{
	 return 'license';
	}
	 function getOffset($start, $end) {

		$chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		for ($i = 0; $i < strlen($chars_str); $i++) {
			$chars_array[] = $chars_str[$i];
		}

		for ($i = count($chars_array) - 1; $i >= 0; $i--) {
			$lookupObj[ord($chars_array[$i])] = $i;
		}

		$sNum = $lookupObj[ord($start)];
		$eNum = $lookupObj[ord($end)];

		$offset = $eNum - $sNum;

		if ($offset < 0) {
			$offset = count($chars_array) + ($offset);
		}

		return $offset;
	}

	 function genenrateOscdomain() {
	
            $site_url = get_bloginfo('url');
            $strDomainName = $site_url;
            preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
            preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
          if (isset($matches['domain'])) {
			$customerurl = $matches['domain'];
		} else {
			$customerurl = "";
		}
		$customerurl = str_replace("www.", "", $customerurl);
		$customerurl = str_replace(".", "D", $customerurl);
		$customerurl = strtoupper($customerurl);
		if (isset($matches['domain'])) {
			$response = app_videogall_getOffset($customerurl);
		} else {
			$response = "";
		}
		return $response;
	}                         
                                  
                              

                                  /* Function to uninstall player plugin */

                                  function hdflv_Sharedeinstall() {
                                      global $wpdb, $wp_version;

                                      $hd_table = $wpdb->prefix . 'hdflvvideoshare';
                                      $hd_table_mp = $wpdb->prefix . 'hdflvvideoshare_med2play';
                                      $hd_table_pl = $wpdb->prefix . 'hdflvvideoshare_playlist';
                                      $hd_table_set = $wpdb->prefix . 'hdflvvideoshare_settings';
                                      $hd_table_lang = $wpdb->prefix . 'hdflvvideoshare_language';
                                  }

                                  /* Function to invoke install player plugin */

                                  function hd_ShareInstall() {

                                      require_once(dirname(__FILE__) . '/install.php');
                                      hdflv_install();
                                      HdflvloadSharedefaults();
                                  }

                                  /* Function to activate player plugin */

                               
                                  register_activation_hook(plugin_basename(dirname(__FILE__)) . '/hdflvvideoshare.php', 'hd_ShareInstall');
                                  register_uninstall_hook(__FILE__, 'hdflv_Sharedeinstall');

								  add_action('plugins_loaded', 'hd_ShareInstall');
                                  /* Function to deactivate player plugin */

                                  function hdflv_Sharedeactivate() {
                                      global $wpdb;
                                      delete_option('HDFLVSettings');
                                      $homeDel = "DELETE FROM " . $wpdb->prefix . "posts WHERE post_content='[contusHome]'";
                                      $homeDelete = $wpdb->get_results($homeDel);
                                  }

                                  //register_uninstall_hook(__FILE__, 'hdflv_Sharedeactivate');

// CONTENT FILTER

                                  add_filter('the_content', 'HDFLV_ShareParse');

// OPTIONS MENU
                                  add_action('admin_menu', 'HDFLVShareAddPage');
                                  // For upgrade
                                  register_uninstall_hook(__FILE__, 'videopluginUninstalling');

                                 // register_deactivation_hook(__FILE__, 'videopluginUninstalling');

//DeActivate Plugin

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
                                  }
?>
