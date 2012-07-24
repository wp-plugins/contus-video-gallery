<?php
/*
 *
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
Description: Contus Videos Share with the standard system of wordpress.
Edited By: Saranya
Version: 1.0
wp-content\plugins\contus-hd-flv-player\themes\default\contusVideo.php
Date : 21/2/2011
*/
global $wpdb;
$site_url = get_bloginfo('url');
$dir  = dirname(plugin_basename(__FILE__));
$dirExp = explode('/',$dir);
$dirPage = $dirExp[0];
$configXML = $wpdb->get_row("SELECT configXML,width,height,keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage  ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage  ?>/css/style.css" />

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
<div id="fb-root"></div>
 <script>

      function getfacebook()
      {
      FB.init({appId: keyApps, status: true, cookie: true,
               xfbml: true});
      }
  </script>
<?php
$meta = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
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
        if($styleSheet == 'contus')
        { ?>
          <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/contusStyle.css" />
       <?php  } ?>


<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo$dirPage?>/swfobject.js"></script>
<?php
class default_videos
{
    function listVideos()
    {
      global $wpdb;
      $site_url = get_bloginfo('url');
      $dir  = dirname(plugin_basename(__FILE__));
      $dirExp = explode('/',$dir);
      $dirPage = $dirExp[0];
      $vid = $_GET['vid'];
      $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish'");
      $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish'");
      $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
      $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

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

                if($i == 1){
                    $vid .= $tagRslt->media_id;
                }else{
                    $vid .= ','.$tagRslt->media_id;
                }

            }

          $getVid =  explode(',',$vid);
       }

   $name = $wpdb->get_row("SELECT name FROM " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'");
   $div = '<div  align="center">';
   $div .='<h3 id="video_title" style="width:'.$configXML->width.';padding-left:10px"  class="more_title" align="left"></h3>';
   $div .='<div name="mediaspace" id="mediaspace" class="mediaspace"><div id="flashplayer">';
      $div .='<script type="text/javascript">';
      $div .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/' . $dirPage . '/hdflvplayer/hdplayer.swf' . '","n' . $videoid . '","' . $configXML->width . '","' . $configXML->height . '","7");' . "\n";
      $div .=  's' . $videoid . '.addParam("allowfullscreen","true");' . "\n";
      $div .= 's' . $videoid . '.addParam("allowscriptaccess","always");' . "\n";
      $div .= 's' . $videoid . '.addParam("wmode","opaque");' . "\n";
      $div .= 's' . $videoid . '.addVariable("baserefW","'.$site_url.'");' . "\n";
     if ($tagName != '') {
         $div .= 's' . $videoid . '.addVariable("vid","' . $getVid[0] . '");' . "\n";
         $div .= 's' . $videoid . '.addVariable("tagname","' . $tagName . '");' . "\n";
        }
        else
        {
            $div .= 's' . $videoid . '.addVariable("vid","' . $vid . '");' . "\n";
        }
      $div .= 's' . $videoid . '.write("mediaspace");' . "\n";
      $div .='</script></div>';
	   //--------------------------------HTML5 START-------------------------------------------------------------//

        /* Error Msg for Video not supported to player. */

        $div .= '<script type="text/javascript">

            function failed(e) {

            alert("Player doesnot support this video.");  }</script>';
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
            $div .='<iframe  type="text/html" width="' . $configXML->width .'" height="' . $configXML->height . '"  src="http://www.youtube.com/embed/' . $videoid . '" frameborder="0"></iframe>';
        }

        /* if video is uploaded or direct path. */ else {
            $div .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $configXML->width .'" height="' . $configXML->height . '" controls onerror="failed(event)">
     Html5 Not support This video Format.
</video>';
        }
        $div .='</div>';

        /* Player Div closed.
         * Script for checking platform.
         */

        $div .=' <script>
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
            {

               document.getElementById("player").style.display = "block";
                document.getElementById("flashplayer").style.display = "none";

            }else{
                document.getElementById("player").style.display = "none";
                document.getElementById("flashplayer").style.display = "block";

            }
        </script>';
        //--------------------------------HTML5 End-------------------------------------------------------------//


      $div .='<div id="video_tag" class="views"></div>';
      $div .='<div class="clear"></div>';
      $vidName = $video->name;

            $getPlaylist = $wpdb->get_results("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id IN ($vid)");

            $kt = split(" ", $vidName); //Breaking the string to array of words
            $div .= '<div style="width:' . $configXML->width . ';padding-top:5px" align="center">';
            $div .= '<div id="wrap">';


            // Now let us generate the sql //(name like "%'.$vidName.'%")
            $videoArray = array();
            $like = '';
               if($tagName != '')
                {
                //$like .= "w.vid IN ($vid) OR ";
                $getVid =  explode(',',$vid);
                $vidName = $wpdb->get_var("SELECT name FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid IN ($getVid[0])");
                $kt = split(" ", $vidName);
                }
            while (list($key, $vidName) = each($kt)) {
                if ($vidName <> " " && strlen($vidName) > 3) {
                    if($key != 0){
                        $like .= "w.name LIKE '%$vidName%' OR ";
                    }else{
                        $like .= "w.name LIKE '%$vidName%' OR ";
                    }

                }
            }
            $like .= "w.name LIKE ''";
             foreach ($getPlaylist as $getPlaylists)
            {
                 if($getPlaylists->playlist_id != '')
                 {
                   $playlist_id .= $getPlaylists->playlist_id.',';
                 }
                 else
                 {
                     $playlist_id = 0;
                 }
            }
            $uppedPlaylist = $playlist_id.'0';
            
                $select = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w";
                $select .= " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m on m.media_id = w.vid";
                $select .= " WHERE ($like)";
                $select .= " OR (m.playlist_id in ($uppedPlaylist))";
                $select .= " GROUP BY w.vid ";
               

            $related = mysql_query($select);

                if (mysql_num_rows($related) != '') {
                $relWidth = $configXML->width;
                //Slide Display Here
                $div .= '<ul id="mycarousel" class="jcarousel-skin-tango">';
                while ($relFet = mysql_fetch_object($related)) {

                    if ($relFet->image != '') {
                        $div .='
                           <li><div  class="imageContus"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '">
                               <img src="' . $relFet->image . '" alt="' . $relFet->post_title . '" class="related" /></a></div>';
                        $div .='<div align="center"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '" class="videoHname">';
                        $div .= substr($relFet->name, 0, 23);
                        $div .='</a></div>';
                        $div .='</li>';
                    } else {

                        $div .='<li><div  class="imageContus"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '">
                        <img src="' . $site_url . '/wp-content/plugins/' .$dirPage . '/images/hdflv.jpg" alt="' . $relFet->post_title . '" class="related" /></a></div>';
                        $div .='<div align="center"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $relFet->vid . '"  class="videoHname">';
                        $div .= substr($relFet->name, 0, 23);
                        $div .='</a></div>';
                        $div .= '</li>';
                    }
                }
             $div .= '</ul></div></div>';
            } else {
               $div .='<div></div>';
            }

    $configXML = $wpdb->get_row("SELECT configXML,width,height,keyApps FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    if ($configXML->keyApps != '') {
         $div .= '<br/><div id="facebook"></div>';
    }
$div .='<div class="clear"></div></div>';
$div .='</div>';
return $div;
}
}
