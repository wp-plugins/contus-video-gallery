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

global $wpdb;
$site_url = get_bloginfo('url');
$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
require_once('pagination.php');
?>
<script	type="text/javascript" 	src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/css/style.css" />
<?php
// For Getting pageid for More and video file
$meta = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1' limit 1");
$sql = "select  * from " . $wpdb->prefix . "hdflvvideoshare WHERE vid='$vid'";
$videos = $wpdb->get_results($sql);
//Including Contus Style Sheet for the Share
if ($styleSheet == 'contus') {
	?>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/css/contusStyle.css" />
	<?php } ?>
<!-- For the Player to display -->
<script type="text/javascript" 	src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage; ?>/swfobject.js"></script>
<script type="text/javascript">
    var baseurl,folder;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>';
    keyApps = '<?php echo $configXML->keyApps; ?>';
    videoPage = '<?php echo $meta; ?>';
</script>
<!-- Page Content Starts -->
	<?php
	class default_home extends pagination  {
        function videosbanner() {
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
        $pluginPath = $site_url . '/wp-content/plugins/'.$dirPage ;
?>
<?php
//[banner type="popular"  width="600" height="300" ][banner type="recent"  width="600" height="300" ][banner type="featured"  width="600" height="300"][banner type="category" catid="2" width="600" height="300"]
        global $wpdb;

       $homebannercategories = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings ");
       $bannertype = $homebannercategories->vbannercategory;
       $show = $homebannercategories->numvideos;
       $bannerwidth = $homebannercategories->bannerw;
       $playerwidth = $homebannercategories->playerw;

          switch ($bannertype) {
        case 'vpopular' :
            $bannervideos ="select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $show;

            break;
        case 'vrecent' :
            $bannervideos = "select  * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY post_date DESC LIMIT " . $show;

         break;
        case 'vfeatured' :
            $bannervideos = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON' ORDER BY vid DESC LIMIT " . $show;
            break;
        case 'vcategory' :
             $homebannercategorylist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings ");
            $playid = $homebannercategorylist->vbanner_categorylist;
            $bannervideos = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $playid . " group by m.media_id DESC LIMIT " . $show;
            break;
        default;
    }
?>
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
        }
        </script>
<?php
        $dirPage = $dirExp[0];
        $sql = $bannervideos;
        $bannerSlideShow = $wpdb->get_results($sql);
        //print_r($bannerSlideShow)

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
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];

        $div .='<ul class="ulwidget">';
        if (!empty($bannerSlideShow)) {
        //$bannerSlideShow[$i]->vid;

?>
<div id="featured" style="width: 100%;" >
    <div id="lofslidecontent45"	class="page-lof-slidecontent" style="width:<?php echo $bannerwidth; ?>px ">
                <div class="right_side">
                    <div id="videoPlay" class="ui-tabs-panel" style="height:100%">
                    </div>
                </div>
                <input type="hidden" id="activeCSS" value="fragment-1" />
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) {
 //echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf'; ?>
                <div id="fragment-<?php echo $i + 1; ?>" >
                    <objec classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
                           style="width:<?php echo $playerwidth?>px; height: 318px">
                        <param name="movie"
                               value="<?php echo $site_url . '/wp-content/plugins/'.$dirPage.'/hdflvplayer/hdplayer.swf'; ?>" />
                        <param name="flashvars"
                               value="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>" />
                        <param name="allowFullScreen" value="true" />
                        <param name="wmode" value="transparent" />
                        <param name="allowscriptaccess" value="always" />
                        <embe
                            src="<?php echo $site_url . '/wp-content/plugins/'.$dirPage.'/hdflvplayer/hdplayer.swf'; ?>"
                            flashvars="baserefW=<?php echo $site_url; ?>&vid=<?php echo $bannerSlideShow[$i]->vid; ?>&Preview=<?php echo $bannerSlideShow[$i]->image; ?>"
                            style="width:<?php echo $playerwidth?>px; height: 318px" allowFullScreen="true"
                            allowScriptAccess="always" type="application/x-shockwave-flash"
                            wmode="transparent"></embed>
                    </object>
                </div>
<?php } ?>
        <!-- NAVIGATOR -->
        <div class="page-bannershort" id="slider_banner" >
            <ul class="page-lof-navigator">
<?php for ($i = 0; $i < count($bannerSlideShow); $i++) { ?>

                <li class="ui-tabs-nav-item" id="nav-fragment-<?php echo $i + 1; ?>">
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

<?php
        }//if end<
        else {
            echo "No Banner videos";
        }
        // end list
        // echo widget closing tag;
    }


		function featureVideos() {

			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$configXML = $wpdb->get_row("SELECT configXML,width,height,bannerw,gutterspace FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			// Featured Videos listing Starts
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

			if ($feaSet == on) {
				$div = '<div style="width:100%" class="paddBotm">';
                                $div .= '<style type="text/css"> .video-block {  padding-right:'.$configXML->gutterspace.'px} </style>';
				$sql1 = "select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'ORDER BY vid DESC LIMIT " . $showF . "";
				$moreF = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
				$countF = $moreF[0]->contus;
				$features = $wpdb->get_results($sql1);
                                // were there any posts found?
				if (!empty($features)) {
					// posts were found, loop through them
                                    $div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=fea">Featured Videos</a></h3>';
                                    $div .='<div class="line_right"></div>';
                                    $div .='<div class="line"></div>';
                                    $j = 0;
					$clearwidth = 0;
					$clear = '';
                                        foreach ($features as $feature) {
                                                $duration[$j] = $feature->duration;
						$imageFea[$j] = $feature->image; //Video Image
						$vidF[$j] = $feature->vid; //Video Id
						$nameF[$j] = $feature->name; //Video Name
						$hitcount[$j] = $feature->hitcount; //Video Hitcount
						$getPlaylist[$j] = $wpdb->get_row("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='$vidF[$j]'");
						$playlist_id[$j] = $getPlaylist[$j]->playlist_id;
						$fetPlay[$j] = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$j]'");
						$fetched[$j] = $fetPlay[$j]->playlist_name;
						$j++;
					}
					$div .= '<div>';
					for ($j = 0; $j < count($features); $j++) {
						$class = '<div class="clear"></div>';
						if (($j % $colF) == 0) {
							//colums count
							$rowCount++;
							$div .= '<div class="clear"></div><div class="video-block">';
							if ($imageFea[$j] != '') {
								$div .='<div  class="video-thumbimg">
                            <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">
                            <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" />
                            </a></div>';
								$div .='<div class="vid_info"><div class="videoHname"><h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">';
								if (strlen($nameF[$j]) > 17) {
									$div .=substr($nameF[$j], 0, 17) . '';
								} else {
									$div .=$nameF[$j];
								}
								$div .='</a></h5></div>';

								$div .= ' <div class="clear"></div>';
								if ($hitcount[$j] != 0) {
									$div .='<div class="views">';
                                                                               if($duration[$j]== 0.00){
                                                                            $div .= $hitcount[$j] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$j].' '.'|'.' '.$hitcount[$j] . ' views';
                                                                        }

									$div .= '</div>';
								}
                                                                if ($fetched[$j] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                $div .= '</div>';
							} else {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '"  />
                            </a></div>';
								$div .='<div class="vid_info"><div class="videoHname"><h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">';
								if (strlen($nameF[$j]) > 17) {
									$div .=substr($nameF[$j], 0, 17) . '';
								} else {
									$div .=$nameF[$j];
								}
								$div .='</a></h5></div>';

								$div .= ' <div class="clear"></div>';
							if ($hitcount[$j] != 0) {
									$div .='<div class="views">';
                                                                               if($duration[$j]== 0.00){
                                                                            $div .= $hitcount[$j] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$j].' '.'|'.' '.$hitcount[$j] . ' views';
                                                                        }

									$div .= '</div>';
								}if ($fetched[$j] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                 $div .= '</div>';
							}
							$div .='</div>';
						} else { //$rowCount++;
							$div .= '<div class="video-block">';
							if ($imageFea[$j] != '') {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">
                        <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome"
                            title="' . $nameF[$j] . '" /></a></div>';
								$div .='<div class="vid_info"><h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '" class="videoHname">';
								if (strlen($nameF[$j]) > 17) {
									$div .=substr($nameF[$j], 0, 17) . '';
								} else {
									$div .=$nameF[$j];
								}
								$div .='</a></h5>

								<div class="views">';
                                                                               if($duration[$j]== 0.00){
                                                                            $div .= $hitcount[$j] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$j].' '.'|'.' '.$hitcount[$j] . ' views';
                                                                        }

									$div .= '</div>';

                                                                if ($fetched[$j] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                 $div .= '</div>';
							} else {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg"
                        alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" />
                              </a></div>';
								$div .='<div class="vid_info"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidF[$j] . '" class="videoHname">';
								if (strlen($nameF[$j]) > 17) {
									$div .=substr($nameF[$j], 0, 17) . '';
								} else {
									$div .=$nameF[$j];
								}
								$div .='</a>';

								$div .= ' <div class="clear"></div>';
								if ($hitcount[$j] != 0) {
									$div .='</a><div class="views">';
                                                                               if($duration[$j]== 0.00){
                                                                            $div .= $hitcount[$j] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$j].' '.'|'.' '.$hitcount[$j] . ' views';
                                                                        }

									$div .= '</div>';
								}if ($fetched[$j] != '') {
									$div .='<br/><span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span></div>';
								}
							}
							$div .='</div>';
						}
					}
					$div.='</div>';
					$div .='<div class="clear"></div>';
				if (($show < $countF)) {
						$div .='<h3 class="more_title" ><a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=fea" class="more">More videos</a></h3>';
					} else if (($show == $countR)) {
						$div .='<div style="float:right"> </div>';
					}  } else
				$div .='No Featured Videos';
				$div .='</div>';
			}
			return $div;
		}

		//Featured Videos Function over
		// For Recent Videos

		function recentVideos() {
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$recSet = $settingsFetch->recent;
			$rowR = $settingsFetch->rowsRec;
			$colR = $settingsFetch->colRec;
			if ($recSet == on) {


				$div = '<div style="width:100%" class="paddBotm">';
				$rowCount = 0;
				$show = $rowR * $colR;
				$sql = "select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $show . "";
				$moreR = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC");
				$countR = $moreR[0]->contus;
				$posts = $wpdb->get_results($sql);

				// were there any posts found?
				if (!empty($posts)) {
					// posts were found, loop through them
					$l = 0;
					$div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=rec">Recent Videos</a></h3>';
                                             $div .='<div class="line_right"></div>';
    $div .='<div class="line"></div>';
					foreach ($posts as $post) {
						$imageRec[$l] = $post->image; //video Image
                                                $duration[$l]= $post->duration;
						$vidR[$l] = $post->vid;   //video Id
						$nameR[$l] = $post->name;  //video Name
						$hitcount[$l] = $post->hitcount; //video hitcount
						$getPlaylist[$l] = $wpdb->get_row("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='$vidR[$l]'");
						$playlist_id[$l] = $getPlaylist[$l]->playlist_id;
						$fetPlay[$l] = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$l]'");
						$fetched[$l] = $fetPlay[$l]->playlist_name; //Playlist Name
						$l++;
					}
					$div .= '<div>';
					for ($l = 0; $l < count($posts); $l++) {
						if (($l % $colR) == 0) {
							$rowCount++;
							$div .= ' <div class="clear"></div><div class="video-block">';
							if ($imageRec[$l] != '') {
								$div .='<div class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '">
                            <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                           <a/></div>';
								$div .=' <h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '" class="videoHname">';
								if (strlen($nameR[$l]) > 17) {
									$div .=substr($nameR[$l], 0, 17) . '';
								} else {
									$div .=$nameR[$l];
								}
								$div .='</a></h5>';



								$div .= ' <div class="clear"></div>';
													if ($hitcount[$l] != 0) {
									$div .='</a><div class="views">';


                                                                               if($duration[$l]== 0.00){
                                                                            $div .= $hitcount[$l] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$l].' '.'|'.' '.$hitcount[$l] . ' views';
                                                                        }if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
								}
									$div .= '</div>';
								}
							} else {

								$div .='<div  class="video-thumbimg"> <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                            </a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '" class="videoHname">';
								if (strlen($nameR[$l]) > 17) {
									$div .=substr($nameR[$l], 0, 17) . '';
								} else {
									$div .=$nameR[$l];
								}
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
													if ($hitcount[$l] != 0) {
									$div .='</a><div class="views">';


                                                                               if($duration[$l]== 0.00){
                                                                            $div .= $hitcount[$l] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$l].' '.'|'.' '.$hitcount[$l] . ' views';
                                                                        }if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
								}
									$div .= '</div>';
								}
							}
							$div .='</div>';
						} else { //$rowCount++;
							$div .= '<div class="video-block">';
							if ($imageRec[$l] != '') {
								$div .='<div  class="video-thumbimg"> <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '">
                                <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                                </a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '" class="videoHname">';
								if (strlen($nameR[$l]) > 17) {
									$div .=substr($nameR[$l], 0, 17) . '';
								} else {
									$div .=$nameR[$l];
								}
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
													if ($hitcount[$l] != 0) {
									$div .='</a><div class="views">';


                                                                               if($duration[$l]== 0.00){
                                                                            $div .= $hitcount[$l] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$l].' '.'|'.' '.$hitcount[$l] . ' views';
                                                                        }if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
								}
									$div .= '</div>';
								}
							} else {
								$div .='<div  class="video-thumbimg"> <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '">
                                <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameR[$l] . '" class="imgHome" title="' . $nameR[$l] . '" />
                                </a></div>';
								$div .=' <h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidR[$l] . '" class="videoHname">';
								if (strlen($nameR[$l]) > 17) {
									$div .=substr($nameR[$l], 0, 17) . '';
								} else {
									$div .=$nameR[$l];
								}
								$div .='</a></h5>';

								$div .= '<div class="clear"></div>';
													if ($hitcount[$l] != 0) {
									$div .='</a><div class="views">';


                                                                               if($duration[$l]== 0.00){
                                                                            $div .= $hitcount[$l] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$l].' '.'|'.' '.$hitcount[$l] . ' views';
                                                                        }if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
								}
									$div .= '</div>';
								}
							}
							$div .='</div>';
						}
					}

					$div.='</div>';
					$div .='<div class="clear"></div>';
				if (($show < $countR)) {
						$div .='<h3 class="more_title" ><a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=rec" class="more">More videos</a></h3>';
					} else if (($show == $countR)) {
						$div .='<div style="float:right"> </div>';
					}

                                        } else
				$div .="No recent Videos";
				$div .='</div>';
			}
			return $div;
		}

		//Recent Videos Function over
		//For Popular Videos

		function popularVideos() {

			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$popSet = $settingsFetch->popular; //Popular Videos
			$rowP = $settingsFetch->rowsPop; //row field of popular videos
			$colP = $settingsFetch->colPop; //column field of popular videos
			$showP = $rowP * $colP;
			if ($popSet == on) {
				$div = '<div style="width:100%" class="paddBotm">';
				$sql2 = "select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $showP . "";
				$moreCount = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare");
				$countP = $moreCount[0]->contus;
				$populars = $wpdb->get_results($sql2);

				// were there any posts found?
				if (!empty($populars)) {
					$div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=pop">Popular Videos</a></h3>';
                                          $div .='<div class="line_right"></div>';
                                            $div .='<div class="line"></div>';
					// posts were found, loop through them
					$k = 0;
					foreach ($populars as $popular) {
                                               $duration[$k] = $popular->duration;
						$imagePop[$k] = $popular->image; //video Image
						$vidP[$k] = $popular->vid; //video Id
						$nameP[$k] = $popular->name; //video Name
						$hitcount[$k] = $popular->hitcount; //video hitcount
						$getPlaylist[$k] = $wpdb->get_row("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='$vidP[$k]'");
						$playlist_id[$k] = $getPlaylist[$k]->playlist_id;
						$fetPlay[$k] = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$k]'");
						$fetched[$k] = $fetPlay[$k]->playlist_name; //playlist Name
						$k++;
					}
					$div .= '<div>';

					for ($k = 0; $k < count($populars); $k++) {
						if (($k % $colP) == 0) {
							$rowCount++;
							$div .= ' <div class="clear"></div><div class="video-block">';
							if ($imagePop[$k] != '') {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '">
                            <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                               </a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '" class="videoHname">';
								if (strlen($nameP[$k]) > 17) {
									$div.=substr($nameP[$k], 0, 17) . '';
								} else {
									$div.=$nameP[$k] . '<br />';
								}
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
									if ($hitcount[$k] != 0) {
									$div .='</a><div class="views">';
                                                                        if($duration[$k]== 0.00){
                                                                            $div .= $hitcount[$k] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$k].' '.'|'.' '.$hitcount[$k] . ' views';
                                                                        }if ($fetched[$k] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$k] . '">' . $fetched[$k] . '</a></span>';
								}
									$div .= '</div>';
								}
							} else {
								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '">
                            <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '"  />
                            </div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '" class="videoHname">';
								if (strlen($nameP[$k]) > 17) {
									$div.=substr($nameP[$k], 0, 17) . '';
								} else {
									$div.=$nameP[$k] . '<br />';
								}
								$div .='</a></h5>';


								$div .= ' <div class="clear"></div>';
									if ($hitcount[$k] != 0) {
									$div .='</a><div class="views">';
                                                                        if($duration[$k]== 0.00){
                                                                            $div .= $hitcount[$k] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$k].' '.'|'.' '.$hitcount[$k] . ' views';
                                                                        }if ($fetched[$k] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$k] . '">' . $fetched[$k] . '</a></span>';
								}
									$div .= '</div>';
								}
							}
							$div .='</div>';
						} else { //$rowCount++;
							$div .= '<div class="video-block">';
							if ($imagePop[$k] != '') {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '">
                            <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                            </a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '" class="videoHname">';
								if (strlen($nameP[$k]) > 17) {
									$div.=substr($nameP[$k], 0, 17) . '';
								} else {
									$div.=$nameP[$k] . '<br />';
								}
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
									if ($hitcount[$k] != 0) {
									$div .='</a><div class="views">';
                                                                        if($duration[$k]== 0.00){
                                                                            $div .= $hitcount[$k] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$k].' '.'|'.' '.$hitcount[$k] . ' views';
                                                                        }if ($fetched[$k] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$k] . '">' . $fetched[$k] . '</a></span>';
								}
									$div .= '</div>';
								}
							} else {

								$div .='<div  class="video-thumbimg"><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '">
                         <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameP[$k] . '" class="imgHome" title="' . $nameP[$k] . '" />
                           </a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidP[$k] . '" class="videoHname">';
								if (strlen($nameP[$k]) > 17) {
									$div.=substr($nameP[$k], 0, 17) . '';
								} else {
									$div.=$nameP[$k] . '<br />';
								}
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
								if ($hitcount[$k] != 0) {
									$div .='</a><div class="views">';
                                                                        if($duration[$k]== 0.00){
                                                                            $div .= $hitcount[$k] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$k].' '.'|'.' '.$hitcount[$k] . ' views';
                                                                        }if ($fetched[$k] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$k] . '">' . $fetched[$k] . '</a></span>';
								}
									$div .= '</div>';
								}
							}
							$div .='</div>';
						}
					}
					$div .='</div>';
					$div .='<div class="clear"></div>';
				if (($showP < $countP)) {
						$div .='<h3 class="more_title" ><a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=pop" class="more">More videos</a></h3>';
					} else if (($showP == $countP)) {
						$div .='<div style="float:right"> </div>';
					} } else
				$div .="No Popular videos";
				// end list
				$div .='</div>';
			}
			$div .='</div>';
			return $div;
		}
		// Popular videos function over
		function hdvSearchVideos() {
			// THE DEFAULT THREE RELATED SERCHED VIDEOS LIST
			global $wpdb;
			$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$limit = $pageFetch->page;
			$start = self::findStart($limit);
			$sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare where name like '%".$_REQUEST['video_search']."%'");
			$count = mysql_num_rows($sql);
			$pages = self::findPages($count, $limit);
                         $stringsearch = $_REQUEST['video_search'];
                        $stringsearch = trim($stringsearch);
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
		        $searchSqlStr = "SELECT t1.vid,t1.name,t1.description,t1.image,t1.file,t1.file_type,t1.duration,t1.hitcount,t2.playlist_id,t3.playlist_name"
			. " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
			. " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
			. " ON t2.media_id = t1.vid"
			. " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
			. " ON t3.pid = t2.playlist_id"
                        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_tags AS t4"
			. " ON t4.media_id = t1.vid"
			. " WHERE ( t4.tags_name REGEXP '[[:<:]]$stringsearch [[:>:]]' || t1.description REGEXP '[[:<:]]$stringsearch [[:>:]]' || t1.name LIKE '%" . $stringsearch . "%')"
			. "LIMIT " . $start . "," . $limit . "";

			$get_feature_list = $wpdb->get_results($searchSqlStr);
			$searchCount = count($get_feature_list);
			$content = '';

                     $searchVal = ($_REQUEST['video_search'] != 'video search ')?'  '.$_REQUEST['video_search']:'';
			$content .='<div class="feature-tab video-cat-thumb"><h3>Search Results - <i style="color:red;">'.'"'.$searchVal.'"'.'</i></h3><div class="search-video-list">';
			if ($searchCount != '') {
				$slimit = 4;
				$inc = 1;
				foreach ($get_feature_list as $get_result_feature) {
					if (strlen($get_result_feature->name) > 17) {
						$video_name = substr(ucfirst(strtolower($get_result_feature->name)), 0, 17) . '';
					} else {
						$video_name = $get_result_feature->name;
					}
					 $duration = $get_result_feature->duration;
					$content .='<div class="video-block">';
					//$content .='<div class="duration">' . $duration . '</div>';
					$content .='<div class="imageContus"><a href="?page_id=' . $vPageID . '&vid=' . $get_result_feature->vid . '">
                   <img src="' . $get_result_feature->image . '" alt="' . $get_result_feature->name . '" title="' . $get_result_feature->name . '" class="imgHome"/></a></li>';
					$content .='<h5 class="video_title"><a href="?page_id=' . $vPageID . '&vid=' . $get_result_feature->vid . '">' . $video_name . '</a></h5>';

                                        $site_url = get_bloginfo('url');

                                        $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
					$content .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=category&playid=' . $get_result_feature->playlist_id . '">' . $get_result_feature->playlist_name . '</a></span><div class="clear"></div>';

                                      if($get_result_feature->duration == 0.00)

                                      {
                                         $content .='<span class="views">' .$get_result_feature->hitcount . ' views</span>';
                                      }
                                          else
                                          {

                                              $content .='<span class="views">' .$get_result_feature->duration.' '.'|'.' '.$get_result_feature->hitcount . ' views</span>';
                                          }





                                        $content .='</div></div>';

					 if( $inc % ($slimit) == 0) {
                     $content .= '<div class="clear"></div>';
                     }
                     $inc++;
				}
				$content .='<div class="clear"></div>';
			} else {
				$content .='<div class="video-none">No Videos Available</div>';
			}

			$pagelist = self::pageList($_GET['page'], $pages, $more, '', $_GET['page_id'],$_REQUEST['video_search']);
			$content .='<div class="clear"></div>';
			$content .='<div class="right video-pagination">' . $pagelist . '</div>';
			$content .='</div></div>';

			return $content;
		}



        function categories(){

            global $wpdb;
$baseDir = dirname(__FILE__);
if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
    $limit = " LIMIT " . (($page - 1) * $items) . ",$items";
} else {
    $limit = " LIMIT $items";
}
$sqlStr = "SELECT t1.vid,t1.name,t1.image,t1.file,t1.hitcount,t2.playlist_id,t3.playlist_name"
        . " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
        . " ON t2.media_id = t1.vid"
        . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
        . " ON t3.pid = t2.playlist_id"
        . " WHERE 1 ORDER BY t1.hitcount";
$sqlStrAux = "SELECT count(*) as total "
        . "FROM " . $wpdb->prefix . "hdflvvideoshare "
        . "WHERE featured='ON'";
 $homecategory = $wpdb->get_var("SELECT homecategory from ".$wpdb->prefix . "hdflvvideoshare_settings" );
if($homecategory=='on'){
$aux = $wpdb->get_var($sqlStrAux);
$query = $wpdb->get_results($sqlStr . $limit);
$content = '';
{
    //  THE DEFAULT THREE Featured Videos LIST
//
//$searchSqlStr = "SELECT t1.vid,t1.name,t1.image,t1.file,t1.file_type,t1.duration,t1.hitcount,t2.playlist_id,t3.playlist_name"
//            . " FROM " . $wpdb->prefix . "hdflvvideoshare AS t1"
//            . " INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
//            . " ON t2.media_id = t1.vid"
//            . " INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
//            . " ON t3.pid = t2.playlist_id"
//            . " ORDER BY t1.hitcount desc";
//
//            $searchSqlStr = "select * from ".$wpdb->prefix."hdflvvideoshare ORDER BY hitcount DESC ";
//            $get_feature_list = $wpdb->get_results($searchSqlStr);
//            $countFeature = count($get_feature_list);
//            $content = '';
//            $content .='<div class="feature-tab video-cat-thumb"><h3>Most Popular</h3><div class="feature-video-list">';
//            $i = 1;
//
//
//    foreach ($get_feature_list as $get_result_feature) {
//        if (strlen($get_result_feature->name) > 15) {
//            $video_name = substr($get_result_feature->name, 0, 15) . '';
//        } else {
//            $video_name = $get_result_feature->name;
//        }
//        $duration = $get_result_feature->duration;
//        $content .='<div class="floatleft video-block ideo_img" >';
//       // $content .='<div class="duration">' . $duration . '</div>';
//        $content .='<a href="?page_id=' . $vPageID . '&vid=' . $get_result_feature->vid . '">
//                   <img src="' . $get_result_feature->image . '" alt="' . $get_result_feature->name . '" title="' . $get_result_feature->name . '"/></a>';
//        $content .='<div class="video_title clearfix"><h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $get_result_feature->vid . '" class="videoHname">' . $video_name . '</a></h5></div>';
//        $content .='<div class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=category&playid=' . $get_result_feature->playlist_id . '">' . $get_result_feature->playlist_name . '</a></div>';     if ($duration == 0.00) {
//            $content .='<div class="views">' . $get_result_feature->hitcount . ' views</div>';
//        } else {
//            $content .='<div class="views">' . $duration . ' ' . '|' . ' ' . $get_result_feature->hitcount . ' views</div>';
//        }
//
//        $content .='</div>';
//        if ($i >= 4) {
//            break;
//        }
//        $i++;
//    }
//    $content .='<div class="clear"></div>';
//    $content .='</div>';
//
//    $gettingsettings = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
//              $rowCat = $settings->rowCat;
//              $colCat = $settings->colCat;
//              $row_col = $rowCat*$colCat;
//
//            if (($countFeature >= 4)&& ($countFeature >=$row_col)) {
//
//        $content .='<div class="right video-more"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=pop">More</a></div>';
//    } else {
//        $content .='<div align="right"> </div>';
//    }
    global $wpdb;
    $site_url = get_bloginfo('url');
    $dir = dirname(plugin_basename(__FILE__));
    $dirExp = explode('/', $dir);
    $dirPage = $dirExp[0];
    $pageFetch = $wpdb->get_row("SELECT page,rowCat,colCat FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $limit = $pageFetch->page;
    $categoryRow = $pageFetch->rowCat;
    $categoryCol = $pageFetch->colCat;
    $videoLimit = $categoryRow*$categoryCol;
    $moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
    $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
    $content .='<div class="clear"></div></div>';
    /*     * ***************************Search Videos listing Starts***************************************************************** */


    $content .='<div class="clear"></div><div class="home-category"><h3 class="home-category" ><a href="' . $site_url . '?page_id='.$moreName.'&more=categories">Video Categories</a></h3>';
         $content .='<div class="line_right"></div>';
           $div .='<div class="line_right"></div>';
    $pageFetch = $wpdb->get_row("SELECT page,category_page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $limit = $pageFetch->category_page;
    $start = findStart($limit);
    $catsql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist LIMIT " . $start . "," . $limit . "";
    $catLists = $wpdb->get_results($catsql);
    $countSql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist";
    $countLists = count($wpdb->get_results($countSql));
    $pages = findPages($countLists, $limit);
    foreach ($catLists as $catList) {
        $pageFetch = $wpdb->get_row("SELECT page,category_page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
        $sql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $catList->pid . " group by m.media_id LIMIT ". $videoLimit ;
        $playLists = $wpdb->get_results($sql);
        $countCategory = count($playLists);
        $i = 1;$moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
        if ($countCategory != '0') {
            $content .='
         <div class="catmain"><h4 class="clear more_title"><a href="'. $site_url .'?page_id=' .$moreName.'&playid='. $catList->pid . '">' . $catList->playlist_name . '</h4>';

			$inc = 1;

            foreach ($playLists as $playList) {
                $duration = $playList->duration;
                $video_name = substr(ucfirst(strtolower($playList->name)), 0, 15) . ' ';
                if ($playList->image != '') {
                    $content .='<div class="video-block">
      <div class="imageContus">';
                    //$content .='<div class="duration">' . $duration . '</div>';
    $content .='<a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $playList->image . '" alt="" class="imgHome" title=""></a></div>
      <h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $video_name . '</a></h5>';
if($duration == 0.00){
 $content .='<div class="views">' .$playList->hitcount . ' views</div>';

}
else
{
    $content .='<div class="views">' .$duration.' '.'|'.' '.$playList->hitcount . ' views</div>';
}
        $content .='</div>';
                } else {
                    $content .='<div class="video-block"><div class="imageContus">';
    //  $content .='<div class="duration">' . $duration . '</div>';
     $content .='<a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="" class="imgHome" title=""></a></div>
        <h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $video_name . '</a></h5>';

if($duration == 0.00){
 $content .='<div class="views">' .$playList->hitcount . ' views</div>';

}
else
{
    $content .='<div class="views">' .$duration.' '.'|'.' '.$playList->hitcount . ' views</div>';
}
        $content .='</div>';

                }
             if( $inc % ($categoryCol) == 0) {
           $content .= '<div class="clear"></div>';
           }
                $inc++;
            }


            $gettingsettings = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
              $rowCat = $settings->rowCat;
              $colCat = $settings->colCat;
              $row_col = $rowCat*$colCat;
    if (($countCategory >= 4)&& ($countCategory >=$row_col)) {

                $content .='<div class="clear"></div><h3><a class="video-more"  href="' . $site_url . '?page_id='.$moreName.'&more=category&playid=' . $catList->pid . '">More videos</a></h3>';
            }

            $content .='</div>';
        }

    }
    $pagelist = pageList($_GET['page'], $pages, $more, '', $_GET['page_id']);
    $content .='<div class="clear"></div>';
    $content .='<div class="right video-pagination">' . $pagelist . '</div>';
}
return $content;
echo '</div></div>';
}
        }

        }
	//class over
        ?>