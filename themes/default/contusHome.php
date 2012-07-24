<?php
/*
  Plugin Name: Contus VideoGallery
  Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
  Description: Contus Videos Share with the standard system of wordpress.
  Version: 1.0
  Edited By: Saranya
  wp-content\plugins\hdvideoshare\themes\default\contusHome.php
  Date : 21/2/2011
 */
global $wpdb;
$site_url= get_bloginfo('url');
$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage;?>/css/style.css" />
<?php
// For Getting pageid for More and video file
$meta = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_content='[contusVideo]' and post_status='publish'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[contusMore]' and post_status='publish'");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
$sql = "select  * from " . $wpdb->prefix . "hdflvvideoshare WHERE vid='$vid'";
$videos = $wpdb->get_results($sql);
//Including Contus Style Sheet for the Share
if($styleSheet == 'contus')
  { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage;?>/css/contusStyle.css" />
<?php  } ?>
<!-- For the Player to display -->
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/swfobject.js"></script>
<script type="text/javascript">
        var baseurl,folder;
        baseurl = '<?php echo $site_url; ?>';
        folder  = '<?php echo $dirPage; ?>';
        keyApps = '<?php echo $configXML->keyApps; ?>';
        videoPage = '<?php echo $meta; ?>';
</script>
<!-- Page Content Starts -->
<?php
class contusHome
{
// For Feature Videos
    function videosSharePlayer()
    {
    global $wpdb;
    $site_url= get_bloginfo('url');
    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    //For Default Video To Play
    $vidDb = $wpdb->get_row("select vid,name from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT 1");
if ($vidDb->vid == '') {
    $vid = '1';
} else {
    $vid = $vidDb->vid;
}
$div ='<div align="center">';

$div .='<h3 id="video_title" style="width:'.$configXML->width.';"  class="more_title" align="left"></h3>';
$videoid = 0;
// Player Starts Here
$div .='<div name="mediaspace" id="mediaspace" class="mediaspace"><div id="flashplayer">';
      $div .='<script type="text/javascript">';
      $div .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/' . $dirPage . '/hdflvplayer/hdplayer.swf' . '","n' . $videoid . '","' . $configXML->width . '","' . $configXML->height . '","7");' . "\n";
      $div .=  's' . $videoid . '.addParam("allowfullscreen","true");' . "\n";
      $div .= 's' . $videoid . '.addParam("allowscriptaccess","always");' . "\n";
      $div .= 's' . $videoid . '.addParam("wmode","opaque");' . "\n";
      $div .= 's' . $videoid . '.addVariable("baserefW","'.$site_url.'");' . "\n";
      $div .= 's' . $videoid . '.addVariable("vid","'.$vid.'");' . "\n";
      $div .= 's' . $videoid . '.write("mediaspace");' . "\n";
      $div .='</script></div>';
	  //--------------------------------HTML5 START-------------------------------------------------------------//

        /* Error Msg for Video not supported to player. */

       $div .= '<script type="text/javascript">

            function failed(e) {
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
            {
            alert("Player doesnot support this video."); } }</script>';
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
            $div .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $configXML->width .'" height="' . $configXML->height . '" autobuffer controls onerror="failed(event)">
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
      $div .='</div>';

      return $div;
    }
function featureVideos()
{
    global $wpdb;
    $site_url= get_bloginfo('url');
    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $vPageID = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_name='contusVideo'");
    $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='contusMore'");
    // Feature Videos listing Starts
    $settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $site_url = get_bloginfo('url');
    $feaSet = $settingsFetch->feature;
    $rowF = $settingsFetch->rowsFea;
    $colF = $settingsFetch->colFea;
    $showF = $rowF * $colF;
    $playerWidth = $configXML->width;
    $thumbTotalWidth = 0;
    $class = '';
    $remainPlayer = 0;
    if ($feaSet == on)
        {

            $div ='<div style="width:'.$configXML->width .'px;" class="paddBotm">';
            $sql1 = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'ORDER BY vid DESC LIMIT " . $showF . "";
            $moreF = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
            $countF = $moreF[0]->contus;
            $features = $wpdb->get_results($sql1);
               // were there any posts found?
        if (!empty($features))
        {
            // posts were found, loop through them
            $j = 0;
            $clearwidth = 0;
            $clear = '';
            $div .='<div style="width:'.$configXML->width .'px;float:left"  class="video_header"><a href="'.$site_url.'/?page_id='.$moreName.'&more=fea" style="float:left"><h3 align="left"> Feature Videos </h3></a>';
            if (($showF <= $countF))
            {
                     $div .='<a href="'.$site_url.'/?page_id='.$moreName.'&more=fea" class="more" style="float:right">more</a><div class="clear"></div></div>';
            } else 
            {
                       $div .='</div>';
            }
            foreach ($features as $feature)
            {
                $imageFea[$j] = $feature->image; //Video Image
                $vidF[$j] = $feature->vid; //Video Id
                $nameF[$j] = $feature->name; //Video Name
                $hitcount[$j] = $feature->hitcount; //Video Hitcount
                $getPlaylist[$j]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidF[$j]'");
                $playlist_id[$j]   = $getPlaylist[$j]->playlist_id;
                $fetPlay[$j]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$j]'");
                $fetched[$j]       = $fetPlay[$j]->playlist_name;
                $j++;
            }
         $div .= '<div>';
               for ($j = 0; $j < count($features); $j++)
              {
                $class = '<div class="clear"></div>';

                if (($j % $colF) == 0) {
                    //colums count
                    $rowCount++;
                    $div .=  '<div class="clear"></div><div class="contusHome">';
                    if ($imageFea[$j] != '') {

                        $div .='<div  class="imageContus">
                            <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" />
                            </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 35) {
                            $div .=substr($nameF[$j], 0, 35).'...';
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a>';
                        if ($hitcount[$j] != 0) {
                            $div .= '<div class="views">';
                            $div .= $hitcount[$j] . ' views';
                            $div .='</div>';
                        }

                        if($fetched[$j] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                        }

                    } else {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '"  />
                            </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 35) {
                            $div .=substr($nameF[$j], 0, 35).'...';
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a>';
                        if ($hitcount[$j] != 0) {
                            $div .=' <div class="views">';
                            $div .= $hitcount[$j] . ' views';
                             $div .='</div>';
                        }
                        if($fetched[$j] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                        }
                    }
                    $div .='</div>';
                } else { //$rowCount++;
                    $div .=  '<div class="contusHome">';
                    if ($imageFea[$j] != '') {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                        <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome"
                            title="' . $nameF[$j] . '" /></a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 35) {
                            $div .=substr($nameF[$j], 0, 35).'...';
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a>';
                        if ($hitcount[$j] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$j] . ' views';
                            $div .='</div>';
                        }
                         if($fetched[$j] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                        }
                    } else {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg"
                        alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" />
                              </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 35) {
                            $div .=substr($nameF[$j], 0, 35).'...';
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a>';
                        if ($hitcount[$j] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$j] . ' views';
                            $div .='</div>';
                        }
                           if($fetched[$j] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                        }
                    }
                    $div .='</div>';
                }
            }

         $div.='</div>';

         $div .='<div class="clear"></div>';

        } else
             $div .='<div>No Feature videos</div>';

     $div .='</div>';
     }
   return  $div;
 } //Feature Videos Function over
 // For Recent Videos
function recentVideos()
{
    global $wpdb;
    $site_url= get_bloginfo('url');
    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $vPageID = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_name='contusVideo'");
    $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='contusMore'");
    $recSet = $settingsFetch->recent;
    $rowR   = $settingsFetch->rowsRec;
    $colR   = $settingsFetch->colRec;
    if ($recSet == on)
    {


        $div = '<div style="width:'.$configXML->width . 'px" class="paddBotm">';
        $rowCount = 0;
        $show = $rowR * $colR;
        $sql = "select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $show . "";
        $moreR = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC");
        $countR = $moreR[0]->contus;
        $posts = $wpdb->get_results($sql);

        // were there any posts found?
        if (!empty($posts))
        {
            // posts were found, loop through them
            $l = 0;
            $div .='<div style="width:'.$configXML->width .'px;float:left"  class="video_header"><a href="'.$site_url.'/?page_id='.$moreName.'&more=rec" style="float:left"><h3 align="left">Recent Videos</h3></a>';
            if (($show <= $countR))
                {
                  $div .='<a href="'.$site_url.'/?page_id='.$moreName.'&more=rec" class="more" style="float:right">more</a><div class="clear"></div></div>';

                } else 
                {
                  $div .='</div>';
                }
            foreach ($posts as $post)
            {
                $imageRec[$l] = $post->image; //video Image
                $vidR[$l]     = $post->vid;   //video Id
                $nameR[$l]    = $post->name;  //video Name
                $hitcount[$l] = $post->hitcount; //video hitcount
                $getPlaylist[$l]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidR[$l]'");
                $playlist_id[$l]   = $getPlaylist[$l]->playlist_id;
                $fetPlay[$l]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$l]'");
                $fetched[$l]       = $fetPlay[$l]->playlist_name; //Playlist Name
                $l++;
            }
            $div .= '<div>';
            for ($l = 0; $l < count($posts); $l++)
            {
                if (($l % $colR) == 0)
                {
                    $rowCount++;
                    $div .= ' <div class="contusHome">';
                    if ($imageRec[$l] != '')
                    {
                        $div .='<div class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                            <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                           <a/></div>';
                        $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                        if (strlen($nameR[$l]) > 35)
                        {
                            $div .=substr($nameR[$l], 0, 35).'...';
                        } else
                        {
                            $div .=$nameR[$l];
                        }
                        $div .='</a>';
                        if ($hitcount[$l] != 0)
                        {
                            $div .= '<div class="views">';
                            $div .= $hitcount[$l] . ' views';
                            $div .='</div>';
                        }

                         if($fetched[$l] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }
                    } else {

                        $div .='<div  class="imageContus"> <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                            </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                        if (strlen($nameR[$l]) > 35)
                        {
                            $div .=substr($nameR[$l], 0, 35).'...';
                        } else
                        {
                            $div .=$nameR[$l];
                        }
                        $div .='</a>';

                        if ($hitcount[$l] != 0)
                        {
                            $div .='<div class="views">';
                            $div .= $hitcount[$l] . ' views';
                            $div .='</div>';
                        }

                         if($fetched[$l] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }
                    }
                    $div .='</div>';
                  } else
                  { //$rowCount++;
                        $div .= '<div class="contusHome">';
                        if ($imageRec[$l] != '')
                        {
                            $div .='<div  class="imageContus"> <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                                </a></div>';
                            $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 35)
                            {
                                $div .=substr($nameR[$l], 0, 35).'...';
                            } else
                            {
                                $div .=$nameR[$l];
                            }
                            $div .='</a>';
                            if ($hitcount[$l] != 0)
                            {
                                $div .='<div class="views">';
                                $div .= $hitcount[$l] . ' views';
                                $div .='</div>';
                            }
                              if($fetched[$l] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }

                        } else
                        {
                            $div .='<div  class="imageContus"> <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                                </a></div>';
                            $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 35)
                            {
                                $div .=substr($nameR[$l], 0, 35).'...';
                            } else
                            {
                                $div .=$nameR[$l];
                            }
                            $div .='</a>';
                            if ($hitcount[$l] != 0) {
                                $div .='<div class="views">';
                                $div .= $hitcount[$l] . ' views';
                                $div .='</div>';
                            }
                             if($fetched[$l] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }

                        }
                    $div .='</div>';
                }
            }

            $div.='</div>';
            $div .='<div class="clear"></div>';
        } else
            $div .="No recent Videos";
        $div .='</div>';
    }
  return $div;
} //Recent Videos Function over
//For Popular Videos
function popularVideos (){

    global $wpdb;
    $site_url  = get_bloginfo('url');
    $dir       = dirname(plugin_basename(__FILE__));
    $dirExp    = explode('/', $dir);
    $dirPage   = $dirExp[0];
    $vPageID   = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_name='contusVideo'");
    $moreName  = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='contusMore'");
    $configXML     = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $popSet = $settingsFetch->popular; //Popular Videos
    $rowP = $settingsFetch->rowsPop;//row field of popular videos
    $colP = $settingsFetch->colPop;//column field of popular videos
    $showP = $rowP * $colP;
    if ($popSet == on) {
        $div ='<div style="width:'.$configXML->width. 'px" class="paddBotm">';
        $sql2      = "select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $showP . "";
        $moreCount = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare");
        $countP    = $moreCount[0]->contus;
        $populars  = $wpdb->get_results($sql2);

        // were there any posts found?
        if (!empty($populars))
        {
             $div .='<div style="width:'.$configXML->width .'px;float:left" class="video_header"><a href="'.$site_url.'/?page_id='.$moreName.'&more=pop" style="float:left;">
                 <h3 align="left">Popular Videos</h3></a>';
             if (($showP <= $countP))
                    {
                       $div .='<a href="'.$site_url.'/?page_id='.$moreName.'&more=pop" class="more" style="float:right;">more</a><div class="clear"></div></div>';
                    } else 
                    {
                        $div .='<div class="clear"></div></div>';
                    }

            // posts were found, loop through them
            $k = 0;
            foreach ($populars as $popular)
            {
                $imagePop[$k]  = $popular->image;//video Image
                $vidP[$k]      = $popular->vid;//video Id
                $nameP[$k]     = $popular->name;//video Name
                $hitcount[$k]  = $popular->hitcount;//video hitcount
                $getPlaylist[$k]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidP[$k]'");
                $playlist_id[$k]   = $getPlaylist[$k]->playlist_id;
                $fetPlay[$k]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$k]'");
                $fetched[$k]       = $fetPlay[$k]->playlist_name; //playlist Name
                $k++;
            }
            $div .= '<div>';

            for ($k = 0; $k < count($populars); $k++)
            {
                if (($k % $colP) == 0)
                 {
                    $rowCount++;
                    $div .= '<div class="contusHome">';
                    if ($imagePop[$k] != '') {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                            <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                               </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                        if (strlen($nameP[$k]) > 35) {
                            $div.=substr($nameP[$k], 0, 35) .'...';
                        } else {
                            $div.=$nameP[$k] . '<br />';
                        }
                        $div .='</a>';
                        if ($hitcount[$k] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$k] . ' views';
                            $div .='</div>';
                        }

                         if($fetched[$k] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';
                        }
                    } else {
                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '"  />
                            </div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                        if (strlen($nameP[$k]) > 35) {
                            $div.=substr($nameP[$k], 0, 35) .'...';
                        } else {
                            $div.=$nameP[$k] . '<br />';
                        }
                        $div .='</a>';
                        if ($hitcount[$k] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$k] . ' views';
                            $div .='</div>';
                        }

                          if($fetched[$k] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';
                        }
                    }
                    $div .='</div>';
                } else { //$rowCount++;
                    $div .= '<div class="contusHome">';
                    if ($imagePop[$k] != '') {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                            <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                            </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                        if (strlen($nameP[$k]) > 35) {
                            $div.=substr($nameP[$k], 0, 35) .'...';
                        } else {
                            $div.=$nameP[$k] . '<br />';
                        }
                        $div .='</a>';
                        if ($hitcount[$k] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$k] . ' views';
                            $div .='</div>';
                        }
                       if($fetched[$k] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';
                        }
                    } else {

                        $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                         <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                           </a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                        if (strlen($nameP[$k]) > 35) {
                            $div.=substr($nameP[$k], 0, 35) .'...';
                        } else {
                            $div.=$nameP[$k] . '<br />';
                        }
                        $div .='</a>';
                        if ($hitcount[$k] != 0) {
                            $div .='<div class="views">';
                            $div .= $hitcount[$k] . ' views';
                            $div .='</div>';
                        }
                          if($fetched[$k] != '')
                        {
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';
                        }
                    }
                    $div .='</div>';
                }
            }

            $div .='</div>';

            $div .='<div class="clear"></div>';

        } else
            $div .="No Popular videos";
        // end list
       $div .='</div>';
    }
    $div .='</div>';
    return $div;
} // Popular videos function over
} //class over
?>