<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Increase the hitcounts for the video.
 * @Creation Date : Feb 21 2011
 * @Modified Date : December 07 2011
 * */

?>

<?php
global $wpdb;
$site_url = get_bloginfo('url');
$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
$configXML = $wpdb->get_row("SELECT configXML,width,height,keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$pluginPath = $site_url . '/wp-content/plugins/' . $dirPage;
?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/css/style.css" />
<!--[if lte IE 6]>
<style type="text/css">
</style>
<![endif]-->

<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/css/style.css" />
<!-- Jquery For the Slider -->
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/js/jquery-1.2.3.pack.js"></script>
<!--
  jCarousel library
-->
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/js/jquery.jcarousel.pack.js"></script>
<!--
  jCarousel core stylesheet
-->
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/css/jquery.jcarousel.css" />
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/css/skins.css" />
<script src="http://connect.facebook.net/en_US/all.js"></script>

<script type="text/javascript">
    function mycarousel_initCallback(carousel)
    {
        // Disable autoscrolling if the user clicks the prev or next button.
        carousel.buttonNext.bind('click', function() {
            carousel.startAuto(0);
        });

        carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });

        // Pause autoscrolling if the user moves with the cursor over the clip.
        carousel.clip.hover(function() {
            carousel.stopAuto();
        }, function() {
            carousel.startAuto();
        });carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });
    };

    jQuery(document).ready(function() {
        jQuery('#mycarousel').jcarousel({
            auto: 0,
            wrap: 'last',
            scroll:1,
            initCallback: mycarousel_initCallback
        });
    });
    function MM_preloadImages() { //v3.0
        var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
            var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
        }

        function MM_swapImgRestore() { //v3.0
            var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
        }

        function MM_findObj(n, d) { //v4.01
            var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
                d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
            if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
            for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
            if(!x && d.getElementById) x=d.getElementById(n); return x;
        }

        function MM_swapImage() { //v3.0
            var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
            if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
        }
</script>
<?php
$meta = $wpdb->get_var("select	ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
?>
<script type="text/javascript">
        var baseurl,folder;
        baseurl = '<?php echo $site_url; ?>';
        folder  = '<?php echo $dirPage; ?>';
        keyApps = '<?php echo $configXML->keyApps; ?>';
        videoPage = '<?php echo $meta; ?>';
</script>
<?php
if ($styleSheet == 'contus') {
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/css/contusStyle.css" />
<?php } ?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo$dirPage ?>/swfobject.js"></script>
<?php

class default_videos {

    function listVideos() {
        global $wpdb;
         define("CONSTVID", $_GET['vid']);
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
            $https = 's://';
        } else {
            $https = '://';
        }
        if (!empty($_SERVER['PHP_SELF']) && !empty($_SERVER['REQUEST_URI'])) {
            // To build the entire URI we need to prepend the protocol, and the http host
            // to the URI string.
            $currentURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }


        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
        $vid = $_GET['vid'];
        $vPageID    = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
        $moreName   = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
        $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        $configXML  = $wpdb->get_row("SELECT configXML,comment_option,width,height,keyApps,enable_social_share FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
        $fbAppId = $configXML->keyApps;
        $vid = $_GET['vid'];
        if ($vid != '') {
            $sql = "SELECT * from " . $wpdb->prefix . "hdflvvideoshare WHERE vid='$vid'";
            $videos = $wpdb->get_results($sql);
           
            foreach ($videos as $video) {
                if ($video->file != '') {
                    //output to screen
                    $file = $video->file;
                } else {
                    echo "<script>alert('No videos is  here');</script>";
                }
            }
            $vidName = $video->name;
        }
        $tagName = $_REQUEST['tagname'];
        if ($tagName != '') {
            $vid = '';
            $i = 0;
            $tagsRst = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_tags t WHERE t.tags_name LIKE '%$tagName%'");
            foreach ($tagsRst as $tagRslt) {
                $i++;
                if ($i == 1) {
                    $vid .= $tagRslt->media_id;
                } else {
                    $vid .= ',' . $tagRslt->media_id;
                }
            }
            $getVid = explode(',', $vid);
        }
        $name = $wpdb->get_row("SELECT name FROM " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'");
        $select = "SELECT t1.vid,t1.description,t4.tags_name,t1.name,t1.post_date,t1.image,t1.file,t1.hitcount,t2.playlist_id,t3.playlist_name"
                . " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                . " ON t2.media_id = t1.vid"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                . " ON t3.pid = t2.playlist_id"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_tags AS t4"
                . " ON t1.vid = t4.media_id"
                . " WHERE t1.vid='$vid' limit 1";


        $nameFetched = $wpdb->get_results($select);

        foreach ($nameFetched as $media) {
            $title = $media->name;
            $description = $media->description;
            $videoTags = $media->tags_name;
            $views = $media->hitcount;
            $playlist_name = $media->playlist_name;
            $playlist_id = $media->playlist_id;
            $post_date = date('M,d Y', strtotime($media->post_date));
        }

        $div = '<div class="video-cat-thumb">';

        $div .='<h1 id="video_title" class="entry-title" align="left">' . $title . '</h1>';
        $div .='<div name="mediaspace" id="mediaspace" class="mediaspace">';
        $div .='<script type="text/javascript">';
        $div .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/' . $dirPage . '/hdflvplayer/hdplayer.swf' . '","n' . $videoid . '","' . $configXML->width . '","' . $configXML->height . '","7");' . "\n";
        $div .= 's' . $videoid . '.addParam("allowfullscreen","true");' . "\n";
        $div .= 's' . $videoid . '.addParam("allowscriptaccess","always");' . "\n";
        $div .= 's' . $videoid . '.addParam("wmode","opaque");' . "\n";
        $div .= 's' . $videoid . '.addVariable("baserefW","' . $site_url . '");' . "\n";
        if ($tagName != '') {
            $div .= 's' . $videoid . '.addVariable("vid","' . $getVid[0] . '");' . "\n";
            $div .= 's' . $videoid . '.addVariable("tagname","' . $tagName . '");' . "\n";
        } else {
            $div .= 's' . $videoid . '.addVariable("vid","' . $vid . '");' . "\n";
        }
        $div .= 's' . $videoid . '.write("mediaspace");' . "\n";
        $div .='</script></div>';
        $div .='<ul class="video-page-info">
        <li class="views"><b>Posted on </b>: ' . $post_date . '</li>
          <li class="views"><b>Views     </b>: ' . $views . '</li>
              
          <li class="views"><b>Category  </b>: <a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id . '">' . $playlist_name . '</a>
                        <li class="views">
                        <b>Tags          </b>: ' . $videoTags . ' ' . '</li>

 <li class="views"><b>Description     </b>: ' . $description . '</li>
  </ul>';
        if($configXML->enable_social_share == '1')
        {
        	$div .= $this->social_share();
        }
        $div .= '<div class="clear"></div>
          <div class="clear"></div>';

        //--------------------------------HTML5 START-------------------------------------------------------------//

        /* Error Msg for Video not supported to player. */

        /* Player Div */

        $div .='<div id="player" style="display:none;">';
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
            $div .='<iframe  type="text/html" width="' . $configXML->width . '" height="' . $configXML->height . '"  src="http://www.youtube.com/embed/' . $videoid . '" frameborder="0"></iframe>';
        }

        /* if video is uploaded or direct path. */ else {

            $div .= '<script type="text/javascript">

            function failed(e) {
   if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {
           alert("Player doesnot support this video.");  }}</script>';
            $div .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $configXML->width . '" height="' . $configXML->height . '" controls onerror="failed(event)">
     Html5 Not support This video Format.
</video>';
        }
        $div .='<div id="video_tag"></div>';
        $div .='</div></div>';

        /* Player Div closed.
         * Script for checking platform.
         */

        $div .=' <script>
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7l")
            {

               document.getElementById("player").style.display = "block";
               document.getElementById("flashplayer").style.display = "none";

            }else{
                document.getElementById("player").style.display = "none";
               // document.getElementById("flashplayer").style.display = "block";

            }
        </script>';
        //--------------------------------HTML5 End-------------------------------------------------------------//
        $div .='<div class="clear"></div>';
        $vidName = $video->name;

        $getPlaylist = $wpdb->get_results("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id IN ($vid)");
   $kt = split(" ", $vidName); //Breaking the string to array of words
        $div .= '<div align="center">';
        $div .= '<div id="wrap" class="video-cat-thumb">';


        // Now let us generate the sql //(name like "%'.$vidName.'%")
        $videoArray = array();
        $like = '';
        if ($tagName != '') {
            //$like .= "w.vid IN ($vid) OR ";
            $getVid = explode(',', $vid);
            $vidName = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid IN ($getVid[0])");
            $kt = split(" ", $vidName);
        }
        while (list($key, $vidName) = each($kt)) {
            if ($vidName <> " " && strlen($vidName) > 3) {
                if ($key != 0) {
                    $like .= "w.name LIKE '%$vidName%' OR ";
                } else {
                    $like .= "w.name LIKE '%$vidName%' OR ";
                }
            }
        }
        $like .= "w.name LIKE ''";
        foreach ($getPlaylist as $getPlaylists) {
            if ($getPlaylists->playlist_id != '') {
                $playlist_id .= $getPlaylists->playlist_id . ',';
            } else {
                $playlist_id = 0;
            }
        }
      
         $select = "select distinct(a.vid),name,description,file,hdfile,file_type,duration,image,opimage,download,link,featured,hitcount,
post_date,postrollads,prerollads from " . $wpdb->prefix . "hdflvvideoshare a INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id WHERE b.playlist_id=" . $getPlaylist[0]->playlist_id . " AND a.vid != ".$vid." ORDER BY b.sorder ASC";
  
        $uppedPlaylist = $playlist_id . '0';

        $div .='<div class="related-videos"><h3>Related Videos</h3>';
        $related = mysql_query($select);

        if (!empty($related)

            )$result = mysql_num_rows($related);
        if ($result != '') {
            $relWidth = $configXML->width;
            //Slide Display Here
            $div .= '<ul id="mycarousel" class="jcarousel-skin-tango" style="margin:0 !important;">';

            while ($relFet = mysql_fetch_object($related)) {

                if ($relFet->image != '') {
                    $div.='
                           <li><div  class="imgSidethumb"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '">
                               <img src="' . $relFet->image . '" alt="' . $relFet->post_title . '" class="related" /></a></div>';
                    $div .='<div class="vid_info"><h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '" class="videoHname">';
                    $div .= substr($relFet->name, 0, 30);
                    $div .='</a></h5>';
                    $div .='</li>';
                } else {

                    $div .='<li><div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '/&vid=' . $relFet->vid . '">
                        <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $relFet->post_title . '" class="related" /></a></div>';
                    $div .='<div ><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '"  class="videoHname">';
                    $div .= substr($relFet->name, 0, 30);
                    $div .='</a></div>';
                    $div .= '</li>';
                }
            }

            $div .= '</ul></div></div>';
            $vid = $_REQUEST['vid'];
        } else {
            $div .='<div></div>';
        }
        $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
        if ($configXML->comment_option == 1) {
            $div .='<h3 class="related-videos">Post Your Comments</h3>';
            $div .='<div class="fbcomments"><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
            <fb:comments href="' .$currentURI.  '" num_posts="10" xid="' . $_GET['vid'] . '" width="500"  canpost="true"    ></fb:comments>
            </div>';
  
   $select = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'";
        $fetched = $wpdb->get_row($select);
           $videoname = $fetched->name;
            $videourl = $fetched->file;
            $des = $fetched->description;
            $imgurl = $fetched->image;
         ?>
   <title> <?php echo $videoname ; ?> </title>
<meta name="description" content="<?php echo $des ; ?> " />
<link rel="image_src" href="<?php echo $imgurl; ?>"/>
<?php
        }
        $configXML = $wpdb->get_row("SELECT configXML,width,height,keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
        if ($configXML->keyApps != '') {
        }
        $div .='<div class="clear"></div></div>';
        $div .='<script type="text/javascript">
        function facebook_share(bookmarkf){
        document.getElementById("fb_share").href = bookmarkf;
        }</script>';
        $div .='';
        return $div;
    }
    public function social_share() {
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
        $shareIcon = '
    <!-- Facebook share Start -->
    <div class="video-socialshare">
    <div class ="floatleft" style="margin:0 15px 0 15px">
		<a href="http://facebook.com" id="fb_share" TARGET="_blank">
			<img src="' . plugins_url() . '/'.$dirPage.'/images/fb_share_button.png" alt="smart suggest for magento" title="share this" />
		</a>
	</div>
	<!-- Facebook share End  -->
    <!-- Twitter like Start -->
	<div class="floatleft" style="margin:0 15px 0 15px" >
		<a href="http://twitter.com/share" class="twitter-share-button"
			data-count="horizontal" TARGET="_blank" >
                <img src="' . plugins_url() . '/'.$dirPage.'/images/twitter.png" alt="smart suggest for magento" title="share this" /></a>
		<script type="text/javascript" src=""></script>
	</div>
	<!-- Twitter like End -->

	<!-- Google plus one Start -->
	<div class="floatleft" style="width:70px">
	<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
		<div class="g-plusone" data-size="medium" data-count="true"></div>
	</div>
	<!-- Google plus one End -->
    </div>';
        return $shareIcon;
    }

}
?>