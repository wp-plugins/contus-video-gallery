<?php
 /*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video detail and short tag page view file.
  Version: 2.0
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

if (class_exists('ContusVideoShortcodeView') != true) {

    class ContusVideoShortcodeView extends ContusVideoShortcodeController { //CLASS FOR HOME PAGE STARTS

        public $_settingsData;
        public $_videosData;
        public $_swfPath;
        public $_singlevideoData;
        public $_videoDetail;
        public $_vId;

        public function __construct() {//contructor starts
            parent::__construct();

            $this->_vId = filter_input(INPUT_GET, 'vid');
            $this->_post_type = filter_input(INPUT_GET, 'post_type');
            $this->_page_post_type = $this->url_to_custompostid(get_permalink());
            $this->_showF = 5;

            $this->_mPageid = $this->More_pageid();
            $this->_plugin_name = $dirExp[0];
            $this->_site_url = get_bloginfo('url');


            $this->_swfPath = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';
            $this->_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
        }

//contructor ends

        public function url_to_custompostid($url) {
            global $wp_rewrite;

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
                    $post_type = '';
                    // Do the query
                    if (!empty($query['videogallery']))
                        $post_type = "videogallery";
                    return $post_type;
                }
            }
            return 0;
        }

        public function social_share() {
            $dir = dirname(plugin_basename(__FILE__));
            $dirExp = explode('/', $dir);
            $dirPage = $dirExp[0];
            $homeplayerData = $this->video_detail($vid);
echo "<pre>";print_r($homeplayerData);
            if (!empty($homeplayerData)) {
                $videoUrl = $homeplayerData->file;
                $video_title = $homeplayerData->name;
                $video_description = $homeplayerData->description;
                $video_thumb = $homeplayerData->image;
            }
            if (strpos($videoUrl, 'youtube') > 0) { //IF VIDEO IS YOUTUBE
                $imgstr = explode("v=", $videoUrl);
                $imgval = explode("&", $imgstr[1]);
                $videoId1 = $imgval[0];
                $video_thumb = "http://img.youtube.com/vi/" . $videoId1 . "/mqdefault.jpg";
            }
            echo $videoUrl;
            $sd = "medium%5D=103&p%5Bvideo%5D%5Bwidth%5D=640&p%5Bvideo%5D%5Bheight%5D=360&p%5Bvideo%5D%5Bsrc%5D=" . urlencode($this->_swfPath) . "%3Ffile%3D" . urlencode($videoUrl) . "%26baserefW%3D" . urlencode(APPTHA_VGALLERY_BASEURL) . "%2F%26vid%3D" . $this->_vId . "%26embedplayer%3Dtrue%26HD_default%3Dtrue%26share%3Dfalse%26skin_autohide%3Dtrue%26showPlaylist%3Dfalse&p%5B";
            $url_fb = "http://www.facebook.com/sharer/sharer.php?s=100&amp;p%5Btitle%5D=" . $video_title . "&amp;p%5Bsummary%5D=" . strip_tags($video_description) . "&amp;p%5B" . $sd . "url%5D=" . urlencode($this->_site_url . '/?page_id=' . $this->_mPageid . '&vid=' . $this->_vId) . "&amp;p%5Bimages%5D%5B0%5D=" . urlencode($video_thumb);
            $shareIcon = '
    <!-- Facebook share Start -->
    <div class="video-socialshare">
    <div class="floatleft" style=" margin-right: 5px; "><a href="' . $url_fb . '" class="fbshare" id="fbshare" target="_blank" ></a></div>
	<!-- Facebook share End and Twitter like Start -->
	<div class="floatleft ttweet" ><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://" ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" >'.__('Tweet', 'video_gallery').'</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
	<!-- Twitter like End and Google plus one Start -->
	<div class="floatleft gplusshare" ><script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="medium" data-count="false"></div></div>
	<!-- Google plus one End -->
    </div>';
            return $shareIcon;
        }

        function HDFLV_shareRender($arguments= array()) {
            global $wpdb;
            global $videoid, $site_url;
            $output = $videourl = $imgurl = $vid = $playlistid = $homeplayerData = '';

            $configXML = $wpdb->get_row("SELECT embed_visible,keydisqusApps,comment_option,keyApps,configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

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
            if (isset($arguments['id'])) {
                $vid = $videoId = $arguments['id'];
                if ($this->_post_type == 'videogallery' || $this->_page_post_type == 'videogallery')
                    $homeplayerData = $this->video_detail($vid);
            }

            if (!empty($homeplayerData)) {
                $videoUrl = $homeplayerData->file;
                $video_title = $homeplayerData->name;
                $video_thumb = $homeplayerData->image;
                $video_playlistname = $homeplayerData->playlist_name;
                $video_playlist_id = $homeplayerData->playlist_id;
                $description = $homeplayerData->description;
                $tag_name = $homeplayerData->tags_name;
                $hitcount = $homeplayerData->hitcount;
                $post_date = $homeplayerData->post_date;
            }

            // Playlist

            $playlistData = $this->playlist_detail($vid);
            $incre=0;
            $playlistname='';

            if (isset($arguments['playlistid'])) {
                $playlistid = $arguments['playlistid'];
            }

            $flashvars = "baserefW=" . get_option('siteurl');
            if (!empty($playlistid) && !empty($vid)) {
                $flashvars .="&pid=" . $playlistid;
                $flashvars .="&vid=" . $vid;
            } elseif (!empty($playlistid)) {
                $flashvars .="&pid=" . $playlistid;
            } else if ($this->_post_type != 'videogallery' && $this->_page_post_type != 'videogallery') {
                $flashvars .="&vid=" . $vid . "&showPlaylist=false";
            } else {
                $flashvars .="&vid=" . $vid;
            }
            if (isset($arguments['flashvars'])) {
                $flashvars .= '&' . $arguments['flashvars'];
            }
            $player_not_supprot=__('Player doesnot support this video.', 'video_gallery');
            $htmlplayer_not_support=__('Html5 Not support This video Format.', 'video_gallery');
            $output .= '<div id="mediaspace' . $videoId . '">';
            $output .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="' . $width . '" height="' . $height . '">
        <param name="movie" value="' . $this->_swfPath . '">
        <param name="flashvars" value="' . $flashvars . '">
        <param name="allowFullScreen" value="true">
        <param name="wmode" value="transparent">
        <param name="allowscriptaccess" value="always">
        <embed src="' . $this->_swfPath . '" flashvars="' . $flashvars . '" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" wmode="transparent">
        </object>';
            $output .= '</div>';

            $output .= '<script type="text/javascript">
            function failed(e) {
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {
            alert("'.$player_not_supprot.'"); } }</script>';

            $output .='<div id="player' . $videoId . '" style="display:none;height:100%">';
            $select = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare where vid='".intval($vid)."'";
            $fetched = $wpdb->get_results($select);
            foreach ($fetched as $media) {
                $videourl = $media->file;
                $imgurl = $media->image;
            }
            if (preg_match("/www\.youtube\.com\/watch\?v=[^&]+/", $videourl, $vresult)) {
                $urlArray = explode("=", $vresult[0]);
                $video_id = trim($urlArray[1]);
                $videourl = "http://www.youtube.com/embed/$video_id";
                $output .='<iframe  type="text/html" width="' . $width . '" height="' . $height . '" src="' . $videourl . '" frameborder="0"></iframe>';
            } else {
                $output .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $width . '" height="' . $height . '" autobuffer controls onerror="failed(event)">
     '.$htmlplayer_not_support.'
</video>';
            }
            $output .='</div>';
            $output .=' <script>
            txt =  navigator.platform ;
            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {
               document.getElementById("player' . $videoId . '").style.display = "block";
                document.getElementById("mediaspace' . $videoId . '").style.display = "none";
            }else{
                document.getElementById("player' . $videoId . '").style.display = "none";
                document.getElementById("mediaspace' . $videoId . '").style.display = "block";
            }
        </script>';


            if ($this->_post_type == 'videogallery' || $this->_page_post_type == 'videogallery') {

                $output .='<div class="video-page-container">
                    <div class="video-page-info">
                        <div class="video-page-date"><strong>'.__("Posted on", "video_gallery").'    </strong>: ' . date("m-d-Y", strtotime($post_date)) . '</div>
                        <div class="video-page-views"><strong>'.__("Views", "video_gallery").'       </strong>: ' . $hitcount . '</div>

                    </div>
                    <div class="video-page-category"><strong>'.__("Category", "video_gallery").' </strong>: ';

                            foreach($playlistData as $playlist){

                if($incre>0){
                    $playlistname.=', '.'<a href="' . $this->_site_url . '/?page_id=' . $this->_mPageid . '&playid=' . $playlist->pid . '">' . $playlist->playlist_name . '</a>';
                }else{
                    $playlistname .=  '<a href="' . $this->_site_url . '/?page_id=' . $this->_mPageid . '&playid=' . $playlist->pid . '">' . $playlist->playlist_name . '</a>';
                }
                $incre++;
            }

               $output .=$playlistname.'</div>
                    <div class="video-page-tag"><strong>'.__("Tags", "video_gallery").'          </strong>: ' . $tag_name . ' ' . '</div>
                    ' ;
            $dir = dirname(plugin_basename(__FILE__));
            $dirExp = explode('/', $dir);
            $dirPage = $dirExp[0];

            if (strpos($videoUrl, 'youtube') > 0) { //IF VIDEO IS YOUTUBE
                $imgstr = explode("v=", $videoUrl);
                $imgval = explode("&", $imgstr[1]);
                $videoId1 = $imgval[0];
                $video_thumb = "http://img.youtube.com/vi/" . $videoId1 . "/mqdefault.jpg";
            }
            $sd = "medium%5D=103&p%5Bvideo%5D%5Bwidth%5D=640&p%5Bvideo%5D%5Bheight%5D=360&p%5Bvideo%5D%5Bsrc%5D=" . urlencode($this->_swfPath) . "%3Ffile%3D" . urlencode($videoUrl) . "%26baserefW%3D" . urlencode(APPTHA_VGALLERY_BASEURL) . "%2F%26vid%3D" . $this->_vId . "%26embedplayer%3Dtrue%26HD_default%3Dtrue%26share%3Dfalse%26skin_autohide%3Dtrue%26showPlaylist%3Dfalse&p%5B";
            $url_fb = "http://www.facebook.com/sharer/sharer.php?s=100&amp;p%5Btitle%5D=" . $video_title . "&amp;p%5Bsummary%5D=" . strip_tags($video_description) . "&amp;p%5B" . $sd . "url%5D=" . urlencode($this->_site_url . '/?page_id=' . $this->_mPageid . '&vid=' . $this->_vId) . "&amp;p%5Bimages%5D%5B0%5D=" . urlencode($video_thumb);
            $output.= '
    <!-- Facebook share Start -->
    <div class="video-socialshare">
    <div class="floatleft" style=" margin-right: 5px; "><a href="' . $url_fb . '" class="fbshare" id="fbshare" target="_blank" ></a></div>
	<!-- Facebook share End and Twitter like Start -->
	<div class="floatleft ttweet" ><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://" ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" >'.__('Tweet', 'video_gallery').'</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
	<!-- Twitter like End and Google plus one Start -->
	<div class="floatleft gplusshare" ><script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="medium" data-count="false"></div></div>
	<!-- Google plus one End -->
    </div>';

                $output .= '<script type="text/javascript" src="' . APPTHA_VGALLERY_BASEURL . 'js/jquery-1.2.3.pack.js"></script>';
                //jCarousel library
                $output .= '<script type="text/javascript" src="' . APPTHA_VGALLERY_BASEURL . 'js/jquery.jcarousel.pack.js"></script>';
                //jCarousel core stylesheet
                $output .= '<link rel="stylesheet" type="text/css" href="' . APPTHA_VGALLERY_BASEURL . 'css/jquery.jcarousel.css" />';
                //jCarousel skin stylesheet
                $output .= '<link rel="stylesheet" type="text/css" href="' . APPTHA_VGALLERY_BASEURL . 'css/skins.css" />';
                // To increase hit count of a video
                $output .= '<script type="text/javascript" src="' . APPTHA_VGALLERY_BASEURL . 'js/script.js"></script>';

                $output .= '<script type="text/javascript">
        var baseurl,folder;
        baseurl = "' . $this->_site_url . '";
        folder  = "' . $this->_plugin_name . '";
        videoPage = "' . $this->_mPageid . '";

                         function mycarousel_initCallback(carousel){
                            // Disable autoscrolling if the user clicks the prev or next button.
                            carousel.buttonNext.bind("click", function() {
                                carousel.startAuto(0);
                            });

                            carousel.buttonPrev.bind("click", function() {
                                carousel.startAuto(0);
                            });

                            // Pause autoscrolling if the user moves with the cursor over the clip.
                            carousel.clip.hover(function() {
                                carousel.stopAuto();
                            }, function() {
                                carousel.startAuto();
                            });carousel.buttonPrev.bind("click", function() {
                                carousel.startAuto(0);
                            });
                        };
                        jQuery(document).ready(function() {
                            jQuery("#mycarousel").jcarousel({
                                auto: 0,
                                wrap: "last",
                                scroll:1,
                                initCallback: mycarousel_initCallback
                            });
                        });
                    </script>';
                // related Videos.

                $output .= '<div align="center">';
                $output .= '<div id="wrap" class="video-cat-thumb">';
                $select = "SELECT distinct(a.vid),name,guid,description,file,hdfile,file_type,duration,image,opimage,download,link,featured,hitcount,
                           a.post_date,postrollads,prerollads from " . $wpdb->prefix . "hdflvvideoshare a
                           INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id
                           INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id
                           INNER JOIN " . $wpdb->prefix . "posts s ON s.ID=a.slug
                           WHERE b.playlist_id=" . intval($video_playlist_id) . " AND a.vid != " . intval($videoId) . " and a.publish='1' AND p.is_publish='1'
                           ORDER BY a.vid DESC";

if ($configXML->embed_visible == 1) {
                //embed code

                $split = explode("/", $videourl);
                if (!empty($videourl) && (preg_match('/vimeo/', $videourl)) && ($videourl != '')) {
                    $embed_code = '<iframe src="http://player.vimeo.com/video/' . $split[3] . '?title=0&amp;byline=0&amp;portrait=0&amp;color=6fde9f" width="400" height="225" class="iframe_frameborder" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                } else {
                    $embed_code = '<embed src="' . $this->_swfPath . '" flashvars="' . $flashvars . '" width="' . $width . '" height="' . $height . '" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" wmode="transparent">';
                }
                $output .='<a href="javascript:void(0)" onclick="enableEmbed();" class="embed" id="allowEmbed"><span class="embed_text">'.__("Embed Code", "video_gallery").'</span><span class="embed_arrow"></span></a>
                    <textarea onclick="this.select()" id="embedcode" name="embedcode" style="display:none;" rows="7" >' . $embed_code . '</textarea>
                    <input type="hidden" name="flagembed" id="flagembed" />
                <script type="text/javascript">
                    function enableEmbed(){
                        embedFlag = document.getElementById("flagembed").value
                        if(embedFlag != 1){
                            document.getElementById("embedcode").style.display="block";
                            document.getElementById("flagembed").value = "1";
                        }
                        else{
                            document.getElementById("embedcode").style.display="none";
                            document.getElementById("flagembed").value = "0";
                        }
                    }
                </script>';
}

                $output .='<div class="video-page-desc"><strong>'.__("Description", "video_gallery").'     </strong>: ' . $description . '</div>
                    </div>';

                $output .='<div ><h2 class="related-videos">'.__("Related Videos", "video_gallery").'</h2>';
                $related = mysql_query($select);
                if (!empty($related))
                    $result = mysql_num_rows($related);
                if ($result != '') {
                    //Slide Display Here
                    $output .= '<ul id="mycarousel" class="jcarousel-skin-tango" style="margin:0 !important;">';
                    $image_path = str_replace('plugins/video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    while ($relFet = mysql_fetch_object($related)) {
                        $file_type = $relFet->file_type; // Video Type
                        $imageFea = $relFet->image; //VIDEO IMAGE
                        $guid = $relFet->guid; //guid
                        if ($imageFea == '') {  //If there is no thumb image for video
                            $imageFea = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2) {          //For uploaded image
                                $imageFea = $image_path . $imageFea;
                            }
                        }

                        $output.='
                           <li><div  class="imgSidethumb"><a href="' . $guid . '">
                               <img src="' . $imageFea . '" alt="' . $relFet->name . '" class="related" /></a></div>';
                        $output .='<div class="vid_info"><h5><a href="' . $guid . '" class="videoHname">';
                        $output .= substr($relFet->name, 0, 30);
                        $output .='</a></h5>';
                        $output .='</li>';
                    }

                    $output .= '</ul></div></div></div>';
                } else {
                    $output .='</div></div></div>';
                }

                // Facebook Comments
                if ($configXML->comment_option == 1) {
                    $output.='<div class="clear"></div>
                    <h2 class="related-videos">'.__("Post Your Comments", "video_gallery").'</h2>
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=' . $configXML->keyApps . '";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, "script", "facebook-jssdk"));</script>';
                    $output.='<div class="fb-comments" data-href="' . get_permalink() . '" data-num-posts="5"></div>';
                }
                //Disqus Comment
                if ($configXML->comment_option == 2) {
                    $output.='<div id="disqus_thread"></div>
<script type="text/javascript">
var disqus_shortname = "' . $configXML->keydisqusApps . '";
(function() {
var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
dsq.src = "http://"+ disqus_shortname + ".disqus.com/embed.js";
(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
})();
</script>
<noscript>'.__("Please enable JavaScript to view the", "video_gallery").' <a href="http://disqus.com/?ref_noscript">'.__("comments powered by Disqus.", "video_gallery").'</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">'.__("comments powered by", "video_gallery").' <span class="logo-disqus">'.__("Disqus", "video_gallery").'</span></a>';
                }
            }
            return $output;
        }

    }

    //class over
} else {
    echo 'class contusVideo already exists';
}
?>

