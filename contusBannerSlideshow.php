<?php

//for  banner header 

function Hbanner($popular, $hcatid, $hwidth, $hplayerwidth, $hnumberofvideos) {
    global $wpdb, $wp_version, $popular_posts_current_ID;
    // Categories to exclude
    $site_url = get_bloginfo('url');
    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $pluginPath = plugins_url() . '/' . dirname(plugin_basename(__FILE__));
?>
<?php
//[banner type="popular"  width="600" height="300" ][banner type="recent"  width="600" height="300" ][banner type="featured"  width="600" height="300"][banner type="category" catid="2" width="600" height="300"]
    global $wpdb;
    $bannertype = $popular;
    $show = '';
    switch ($bannertype) {
        case 'hpopular' :
            $show = $hnumberofvideos;
            $bannervideos = "select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $show;

            break;
        case 'hrecent' :
            $show = $hnumberofvideos;
            $bannervideos = "select  * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY post_date DESC LIMIT " . $show;
            break;
        case 'hfeatured' :
            $show = $hnumberofvideos;
            $bannervideos = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT " . $show;
            break;
        case 'hcategory' :
            $playid = $hcatid;
            $bannerwidth = $hwidth;
            $playerwidth = $hplayerwidth;
            $show = $hnumberofvideos;
            $bannervideos = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $playid . " group by m.media_id DESC LIMIT " . $show;
            break;
        default;
    }
?>
    <script language="javascript" type="text/javascript" src="<?php echo $pluginPath; ?>/js/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $pluginPath; ?>/js/jquery-ui-min.js"></script>
    <link rel="stylesheet" type="text/css"	href="<?php echo $pluginPath; ?>/css/bannerstyle.css" />
    <script type="text/javascript">
        var baseurl;
        baseurl = '<?php echo $site_url; ?>';
        folder  = '<?php echo $dirPage; ?>'
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            //$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 9000, true);
            $("#featured").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
            $("#featured_banner").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            var get_width = 'auto';
            // Getting the width of the theme to fix the banner fix
            //            if(get_width == 'auto')
            //            {
            //                var theme_width  =
            //            }
            //var border_width = parseInt('10');
           // var actual_width = parseInt(theme_width) - (border_width);
           // $("#featured").css('width',actual_width);
            //$("#slider_banner > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate",  '3000', true);
            // $("#featured_banner").css('width',actual_width);
            //$("#slider_banner1 > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate",  '3000', true);
        });
    </script>
<?php
    $dirPage = $dirExp[0];
    $sql = $bannervideos;
    $bannerSlideShow = $wpdb->get_results($sql);
    $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
    $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
    $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
    $site_url = get_bloginfo('url');
    $div = '<div id="contusfeatured"  class="sidebar-wrap clearfix">
         <div><a href="' . $site_url . '/?page_id=' . $moreName . '&more=fea"><h2 class="widget-title">Featured Videos</h2></a></div>';
    $sql = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT " . $show;
    $features = $wpdb->get_results($sql);
    $moreF = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
    $countF = $moreF[0]->contus;
    $div .='<ul class="ulwidget">';
    if (!empty($bannerSlideShow)) {
?>
<?php
$mobile = detect_mobile();?>
        <div id="featured_banner" style="width: <?php echo $hwidth; ?>px">
            <div id="lofslidecontent45" class="lof-slidecontent lof-snleft">
                <div class="right_side">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>
            <div id="fragment-<?php echo $i + 1; ?>" class="ui-tabs-panel" style="height:100%;float:right">
            <?php 
if($mobile === true){
   if ($bannerSlideShow[$i]->file_type == 2){ $video=$bannerSlideShow[$i]->link;?>
    <video id="video" src="<?php echo $video; ?>"  autobuffer controls onerror="failed(event)" width="701" height="303">
             Html5 Not support This video Format.
     </video>
   <?php } elseif ($bannerSlideShow[$i]->file_type == 1)
                        {
                           if (preg_match('/www\.youtube\.com\/watch\?v=[^&]+/', $bannerSlideShow[$i]->link, $vresult))
                            {
                               $urlArray = explode("=", $vresult[0]);
                               $videoid = trim($urlArray[1]);
                            }
?>
                           <iframe  type="text/html" width=<?php echo $playerwidth?>px height="318px" src="http://www.youtube.com/embed/<?php echo $videoid; ?>" frameborder="0">
                           </iframe>
<?php
                       }
}else{?>
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                        width="<?php echo $hplayerwidth; ?>px" height="305px">
                    <param name="movie"
                           value="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>" />
                    <param name="flashvars"
                           value="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>" />
                    <param name="allowFullScreen" value="true" />
                    <param name="wmode" value="transparent" />
                    <param name="allowscriptaccess" value="always" />
                    <embed
                        src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>"
                        flashvars="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>"
                        style="width: <?php echo $hplayerwidth; ?>px; height: 305px" allowFullScreen="true"
                        allowScriptAccess="always" type="application/x-shockwave-flash"
                        wmode="transparent"></embed>
                </object>
                <?php 
}
                ?>
            </div>
<?php } ?>
        </div>
        <!-- NAVIGATOR -->
        <div class="page-bannershort" id="slider_banner1" >
            <ul class="page-lof-navigator">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>
                <li class="ui-tabs-nav-item ui-tabs-selected clearfix" id="nav-fragment-<?php echo $i + 1; ?>">
                    <div class="nav_container">
                        <a href="#fragment-<?php echo $i + 1; ?>">
                            <div class="page-thumb-img"><img src="<?php echo $bannerSlideShow[$i]->image; ?>"  alt="thumb image" /></div>
                            <div class="slide_video_info" >
                                <h4><?php echo substr($bannerSlideShow[$i]->name, 0, 35); ?></h4>
                                <div class="views">
<?php echo $bannerSlideShow[$i]->duration . ' ' . '|' . ' ' . $bannerSlideShow[$i]->hitcount ?> views
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
<?php } ?>
            </ul>
        </div>
        <!-- NAVIGATOR -->
    </div>
</div>
<?php
    }//if end
    else {
        echo "No Banner videos";
    }
    

    // end list
    // echo widget closing tag;
}
function detect_mobile()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

    $mobile_browser = '0';

    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent))
        $mobile_browser++;

    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;

    $mobile_ua = substr($agent,0,4);
    $mobile_agents = array(
                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                        'wapr','webc','winw','xda','xda-'
                        );

    if(in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;

    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;

    // Pre-final check to reset everything if the user is on Windows
    if(strpos($agent, 'windows') !== false)
        $mobile_browser=0;

    // But WP7 is also Windows, with a slightly different characteristic
    if(strpos($agent, 'windows phone') !== false)
        $mobile_browser++;

    if($mobile_browser>0)
        return true;
    else
        return false;
}

// Video Gallery Banner
// BannerSlideshow widget with the standard system of wordpress.
class default_banner {
    function videosbanner($content) {
        $videoSearch = $_REQUEST['video_search'];
        global $wpdb, $wp_version, $popular_posts_current_ID;
        $options = get_option('widget_ContusBannerSlideshow');
        $title = $options['title'];  // Title in sidebar for widget
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
        $pluginPath = plugins_url() . '/' . dirname(plugin_basename(__FILE__));
?>
<?php
//[banner type="popular"  width="600" height="300" ][banner type="recent"  width="600" height="300" ][banner type="featured"  width="600" height="300"][banner type="category" catid="2" width="600" height="300"]
        global $wpdb;
        $bannertype = $content['type'];
        $show = '';
        switch ($bannertype) {
            case 'popular' :
                 $show = $content['numberofvideos'];
                $bannerwidth = $content['width'];
                $playerwidth = $content['playerwidth'];
                $bannervideos = "select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $show;
                break;
            case 'recent' :
                $show = $content['numberofvideos'];
                $bannerwidth = $content['width'];
                $playerwidth = $content['playerwidth'];
                $bannervideos = "select  * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY post_date DESC LIMIT " . $show;
                break;
            case 'featured' :
                $show = $content['numberofvideos'];
                $bannerwidth = $content['width'];
                $playerwidth = $content['playerwidth'];
                $bannervideos = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT " . $show;
                break;
            case 'category' :
                $playid = $content['catid'];
                $bannerwidth = $content['width'];
                $playerwidth = $content['playerwidth'];
                $show = $content['numberofvideos'];
                $bannervideos = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $playid . " group by m.media_id DESC LIMIT " . $show;
                break;
            default;
        }
?>
        <script language="javascript" type="text/javascript" src="<?php echo $pluginPath; ?>/js/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo $pluginPath; ?>/js/jquery-ui-min.js"></script>
        <link rel="stylesheet" type="text/css"	href="<?php echo $pluginPath; ?>/css/bannerstyle.css" />
        <script type="text/javascript">
            var baseurl;
            baseurl = '<?php echo $site_url; ?>';
            folder  = '<?php echo $dirPage; ?>'
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                //$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 9000, true);
                $("#featured").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                var get_width = 'auto';
                // Getting the width of the theme to fix the banner fix
                //            if(get_width == 'auto')
                //            {
                //                var theme_width  = <?php //echo $bannerwidth;  ?>;
                //            }
                //var border_width = parseInt('10');
                //var actual_width = parseInt(theme_width) - (border_width);
                //$("#featured").css('width',actual_width);
                //$("#slider_banner > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate",  '3000', true);
            });
        </script>
<?php
        $dirPage = $dirExp[0];
        $sql = $bannervideos;
        $bannerSlideShow = $wpdb->get_results($sql);

        $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
        $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
        $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        $site_url = get_bloginfo('url');

        $div = '<div id="contusfeatured"  class="sidebar-wrap clearfix">
         <div><a href="' . $site_url . '/?page_id=' . $moreName . '&more=fea"><h2 class="widget-title">Featured Videos</h2></a></div>';

        $sql = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT " . $show;
        $features = $wpdb->get_results($sql);
        $moreF = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
        $countF = $moreF[0]->contus;
        $div .='<ul class="ulwidget">';
        if (!empty($bannerSlideShow)) {
?>
<?php
$mobile = default_banner::detect_mobile();?>
<div id="featured" style="width:<?php echo $bannerwidth; ?>px ">
    <div id="lofslidecontent45"	class="page-lof-slidecontent">
        <div class="right_side">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>
                <div id="fragment-<?php echo $i + 1; ?>" class="ui-tabs-panel" style="height:100%;float:right">

<?php 
if($mobile === true){
   if ($bannerSlideShow[$i]->file_type == 2){ $video=$bannerSlideShow[$i]->link;?>
    <video id="video" src="<?php echo $video; ?>"  autobuffer controls onerror="failed(event)" width="701" height="303">
             Html5 Not support This video Format.
     </video>
   <?php } elseif ($bannerSlideShow[$i]->file_type == 1)
                        {
                           if (preg_match('/www\.youtube\.com\/watch\?v=[^&]+/', $bannerSlideShow[$i]->link, $vresult))
                            {
                               $urlArray = explode("=", $vresult[0]);
                               $videoid = trim($urlArray[1]);
                            }
?>
                           <iframe  type="text/html" width=<?php echo $playerwidth?>px height="318px" src="http://www.youtube.com/embed/<?php echo $videoid; ?>" frameborder="0">
                           </iframe>
<?php
                       }
}else{?>
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                             style="width:<?php echo $playerwidth;?>px; height: 303px" >
                        <param name="movie"
                               value="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>" />
                        <param name="flashvars"
                               value="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>" />
                        <param name="allowFullScreen" value="true" />
                        <param name="wmode" value="transparent" />
                        <param name="allowscriptaccess" value="always" />
                        <embed
                            src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>"
                            flashvars="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>"
                            style="width: <?php echo $playerwidth; ?>px; height: 303px" allowFullScreen="true"
                            allowScriptAccess="always" type="application/x-shockwave-flash"
                            wmode="transparent"></embed>
                    </object>
                    <?php } ?>
                </div>
<?php } ?>
        </div>
        <!-- NAVIGATOR -->
        <div class="page-bannershort" id="slider_banner" >
            <ul class="page-lof-navigator">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>

                <li class="ui-tabs-nav-item ui-tabs-selected clearfix" id="nav-fragment-<?php echo $i + 1; ?>">
                    <div class="nav_container">
                        <a href="#fragment-<?php echo $i + 1; ?>">
                            <div class="page-thumb-img"><img src="<?php echo $bannerSlideShow[$i]->image; ?>"  alt="thumb image" /></div>
                            <div class="slide_video_info" >
<?php echo substr($bannerSlideShow[$i]->name, 0, 35); ?>
                                <div class="views">
<?php echo $bannerSlideShow[$i]->duration . ' ' . '|' . ' ' . $bannerSlideShow[$i]->hitcount ?> views
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
<?php } ?>
            </ul>
        </div>
        <!-- NAVIGATOR -->
    </div>
</div>
<?php
        }//if end
        else {
            echo "No Banner videos";
        }
        // end list
        // echo widget closing tag;
    }
    
function detect_mobile()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

    $mobile_browser = '0';

    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent))
        $mobile_browser++;

    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;

    $mobile_ua = substr($agent,0,4);
    $mobile_agents = array(
                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                        'wapr','webc','winw','xda','xda-'
                        );

    if(in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;

    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;

    // Pre-final check to reset everything if the user is on Windows
    if(strpos($agent, 'windows') !== false)
        $mobile_browser=0;

    // But WP7 is also Windows, with a slightly different characteristic
    if(strpos($agent, 'windows phone') !== false)
        $mobile_browser++;

    if($mobile_browser>0)
        return true;
    else
        return false;
}

}
