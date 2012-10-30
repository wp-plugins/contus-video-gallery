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
    <!--    <script language="javascript" type="text/javascript" src="<?php //echo $pluginPath;  ?>/js/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php //echo $pluginPath;  ?>/js/jquery-ui-min.js"></script>-->
    <link rel="stylesheet" type="text/css"	href="<?php echo $pluginPath; ?>/css/bannerstyle.css" />
    <script type="text/javascript">
        var baseurl;
        baseurl = '<?php echo $site_url; ?>';
        folder  = '<?php echo $dirPage; ?>'
    </script>
    <script type="text/javascript">
        function switchVideo(vid){
            sourceCode = document.getElementById(vid).innerHTML;
            objectCode = sourceCode.replace('OBJEC','object');
            embedCode  = objectCode.replace('embe','embed');
            document.getElementById("nav-"+vid).className = 'ui-tabs-nav-item ui-tabs-selected';

            removeSelectItem = document.getElementById("activeCSS").value;
            document.getElementById("nav-"+removeSelectItem).className = 'ui-tabs-nav-item';

            document.getElementById('videoPlay').innerHTML = embedCode;
            document.getElementById("activeCSS").value = vid;

        }
        window.onload = function(){
            vid = "fragment-1";
            sourceCode = document.getElementById(vid).innerHTML;
            objectCode = sourceCode.replace('OBJEC','object');
            embedCode  = objectCode.replace('embe','embed');
            document.getElementById("nav-"+vid).className = 'ui-tabs-nav-item ui-tabs-selected';
            document.getElementById('videoPlay').innerHTML = embedCode;
        }    </script>
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
        <div id="featured_banner" style="width: <?php echo $hwidth; ?>px">
            <div id="lofslidecontent45" class="lof-slidecontent lof-snleft">
                <div class="right_side">
                    <div id="videoPlay" class="ui-tabs-panel" style="height:100%">
                    </div>
                </div>
                <input type="hidden" id="activeCSS" value="fragment-1" />
        <?php for ($i = 0; $i < count($bannerSlideShow); $i++) {
        ?>
            <div id="fragment-<?php echo $i + 1; ?>" >
                <objec classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                       codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                       width="<?php echo $hplayerwidth; ?>px" height="305px">
                    <param name="movie"
                           value="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>" />
                    <param name="flashvars"
                           value="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>" />
                    <param name="allowFullScreen" value="true" />
                    <param name="wmode" value="transparent" />
                    <param name="allowscriptaccess" value="always" />
                    <embe
                        src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>"
                        flashvars="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>"
                        style="width: <?php echo $hplayerwidth; ?>px; height: 305px" allowFullScreen="true"
                        allowScriptAccess="always" type="application/x-shockwave-flash"
                        wmode="transparent"></embed>
                        </object>
                        </div>
                    <?php } ?>

                    <!-- NAVIGATOR -->
                    <div class="page-bannershort" id="slider_banner1" >
                        <ul class="page-lof-navigator">
                            <?php for ($i = 0; $i < count($bannerSlideShow); $i++) {
 ?>
                                <li class="ui-tabs-nav-item clearfix" id="nav-fragment-<?php echo $i + 1; ?>">
                                    <div class="nav_container">
                                        <a href="javascript:void(0)" onclick=switchVideo("fragment-<?php echo $i + 1; ?>") >
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
                            <link rel="stylesheet" type="text/css" href="<?php echo $pluginPath; ?>/css/bannerstyle.css" />
                            <script type="text/javascript">
                                var baseurl;
                                baseurl = '<?php echo $site_url; ?>';
                                folder  = '<?php echo $dirPage; ?>'
                            </script>
                            <script type="text/javascript">
                                function switchVideo(vid){
                                    sourceCode = document.getElementById(vid).innerHTML;
                                    objectCode = sourceCode.replace('OBJEC','object');
                                    embedCode  = objectCode.replace('embe','embed');
                                    document.getElementById("nav-"+vid).className = 'ui-tabs-nav-item ui-tabs-selected';

                                    removeSelectItem = document.getElementById("activeCSS").value;
                                    document.getElementById("nav-"+removeSelectItem).className = 'ui-tabs-nav-item';

                                    document.getElementById('videoPlay').innerHTML = embedCode;
                                    document.getElementById("activeCSS").value = vid;

                                }
                                window.onload = function(){
                                    vid = "fragment-1";
                                    sourceCode = document.getElementById(vid).innerHTML;
                                    objectCode = sourceCode.replace('OBJEC','object');
                                    embedCode  = objectCode.replace('embe','embed');
                                    document.getElementById("nav-"+vid).className = 'ui-tabs-nav-item ui-tabs-selected';
                                    document.getElementById('videoPlay').innerHTML = embedCode;
                                }
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
                                <div id="featured" style="width:<?php echo $bannerwidth; ?>px ">
                                    <div id="lofslidecontent45"	class="page-lof-slidecontent">
                                        <div class="right_side">
                                            <div id="videoPlay" class="ui-tabs-panel" style="height:100%">
                                            </div>
                                        </div>
                                        <input type="hidden" id="activeCSS" value="fragment-1" />
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>
                                    <div id="fragment-<?php echo $i + 1; ?>">


                                        <objec classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                                               codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                                               style="width:<?php echo $playerwidth; ?>px; height: 303px" >
                                            <param name="movie"
                                                   value="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>" />
                                            <param name="flashvars"
                                                   value="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>" />
                                            <param name="allowFullScreen" value="true" />
                                            <param name="wmode" value="transparent" />
                                            <param name="allowscriptaccess" value="always" />
                                            <embe
                                                src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>"
                                                flashvars="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>"
                                                style="width: <?php echo $playerwidth; ?>px; height: 303px" allowFullScreen="true"
                                                allowScriptAccess="always" type="application/x-shockwave-flash"
                                                wmode="transparent"></embed>
                                                </object>
                                                </div>
<?php } ?>

                                        <!-- NAVIGATOR -->
                                        <div class="page-bannershort" id="slider_banner" >
                                            <ul class="page-lof-navigator">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>
                                                <li class="ui-tabs-nav-item clearfix" id="nav-fragment-<?php echo $i + 1; ?>">
                                                    <div class="nav_container">
                                                        <a href="javascript:void(0)" onclick=switchVideo("fragment-<?php echo $i + 1; ?>") >
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

                            }

