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


$site_url = get_bloginfo('url');
$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
?>
<?php
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
?>
<?php
//Including Contus Style Sheet for the Share
if ($styleSheet == 'contus') {
	?>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/css/contusStyle.css" />
	<?php } ?>
<script
	type="text/javascript"
	src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/js/script.js"></script>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage ?>/css/style.css" />
<script type="text/javascript">
    var baseurl,folder;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>';
</script>
<style>
.post_title {
	display: none;
}
</style>
<!--  Content For More Page -->
	<?php
	require_once('pagination.php');
	class default_more extends pagination {
		function featureVideos() {
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$limit = $pageFetch->page;
            $configXML = $wpdb->get_row("SELECT configXML,width,height,gutterspace  FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			// Featured Videos listing Starts
			$settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$feaSet = $settingsFetch->feature;
			$rowF = $settingsFetch->rowsFea;
			$colF = $settingsFetch->colFea;
			$showF = $rowF * $colF;
			$playerWidth = $configXML->width;
			$thumbTotalWidth = 0;
			$class = '';
			$div = '<div class="video-cat-thumb">';
			// Featured Videos listing Starts
			$options = get_option('HDFLVSettings');
			$more = $_REQUEST['more'];
			if ($more == 'fea') {
				$div .='<div><h1 class="entry-title">Featured Videos</h1>';
                           
				/* Find the start depending on $_GET['page'] (declared if it's null) */
				$start = self::findStart($limit);
				/* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
				$sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
				$count = mysql_num_rows($sql);

				/* Find the number of pages based on $count and $limit */
				$pages = self::findPages($count, $limit);

				/* Now we use the LIMIT clause to grab a range of rows */
				$result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'  LIMIT " . $start . "," . $limit . "");
				// were there any posts found?
				$features = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'  ORDER BY vid DESC LIMIT " . $start . "," . $limit . "");
				// were there any posts found?
$div .= '<style> .video-block { padding-right:'.$configXML->gutterspace.'px } </style>';
				if (!empty($features)) {
					// posts were found, loop through them

                                   // $div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=fea">Featured Videos</a>';
                                    $j = 0;
					$clearwidth = 0;
					$clear = '';
					if (($show < $countF)) {
						$div .='<a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=fea" class="more">more videos</a></h3>';
					} else if (($show == $countR)) {
						$div .='<div style="float:right"> </div>';
					}
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
									$div .='<div class="clear"></div><a class="playlistName"  href="' . $site_url . '?page_id='.$moreName.'&more=category&playid=' . $playlist_id[$j] . '">'.$fetched[$j].'</a>';
								}
                                                                $div .='</div>';
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
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                $div .='</div>';
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
								$div .='</a></h5>';

								$div .= ' <div class="clear"></div>';
								if ($hitcount[$j] != 0) {
									$div .='<div class="views">';

                                                                        if($duration[$j]== 0.00){
                                                                            $div .= $hitcount[$j] . ' views';
                                                                        }
									else{
                                                                            $div .= $duration[$j].' '.'|'.' '.$hitcount[$j] . ' views';
                                                                        }
                                                                        $div .='</div>';
								}if ($fetched[$j] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                $div .='</div>';
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
									$div .='<br/><span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a></span>';
								}
                                                                $div .= '</div>';
							}
							$div .='</div>';
						}
					}
					$div.='</div>';
					$div .='<div class="clear"></div>';
				} else {
					$div .="No Featured Videos";
				}
				// end list
				$pagelist = self::pageList($_GET['page'], $pages, $more, '', $_GET['page_id']);
				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';
				$div .='</div>';
			}
			return $div;
		}

		function recentVideos() {
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$pageFetch = $wpdb->get_row("SELECT page,ffmpeg_path,gutterspace FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$limit = $pageFetch->page;

            $settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$rowR = $settingsFetch->rowsRec;
			$colR = $settingsFetch->colRec;
			$more = $_REQUEST['more'];
			if ($more == 'rec') {
				$div = '<div><h3 class="more_title">Recent Videos</h3>';
				/* Find the start depending on $_GET['page'] (declared if it's null) */
				$start = self::findStart($limit);
				/* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
				$sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC");
				$count = mysql_num_rows($sql);
				/* Find the number of pages based on $count and $limit */
				$pages = self::findPages($count, $limit);
				$more = $_REQUEST['more'];
				/* Now we use the LIMIT clause to grab a range of rows */
				$result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $start . "," . $limit . "");
				$posts = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $start . "," . $limit . "");
				if (!empty($posts)) {
					// posts were found, loop through them
					$l = 0;
					//$div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=rec">Recent Videos</a>';
					if (($show < $countR)) {
						$div .='<a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=rec" class="more">more videos</a></h3>';
					} else if (($show == $countR)) {
						$div .='<div style="float:right"> </div>';
					}
					$div .= '<style> .video-block { padding-right:'.$pageFetch->gutterspace.'px } </style>';
					
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
                                                                        }
									$div .= '</div>';
								}if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
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
                                                                        }
									$div .= '</div>';
								}if ($fetched[$l] != '') {
									$div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id[$l] . '">' . $fetched[$l] . '</a></span>';
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
				$more = $_REQUEST['more'];
				$pagelist = self::pageList($_GET['page'], $pages, 'rec', $_GET['playid'], $_GET['page_id']);
				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';
				 } else
				$div .="No recent Videos";
				$div .='</div>';
			}

			return $div;
		}



		function popularVideos() {
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$limit = $pageFetch->page;
			$more = $_REQUEST['more'];
            $configXML = $wpdb->get_row("SELECT configXML,width,height,gutterspace FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$settingsFetch = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$rowP = $settingsFetch->rowsPop; //row field of popular videos
			$colP = $settingsFetch->colPop; //column field of popular videos
			$showP = $rowP * $colP;

			if ($more == 'pop') {

				$div = '<div> <h1 class="entry-title">Popular Videos</h1>';

				/* Find the start depending on $_GET['page'] (declared if it's null) */
				$start = self::findStart($limit);
				/* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
				$sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC");
				$count = mysql_num_rows($sql);

				/* Find the number of pages based on $count and $limit */
				$pages = self::findPages($count, $limit);

				/* Now we use the LIMIT clause to grab a range of rows */
				$result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC  LIMIT " . $start . "," . $limit . "");

				$populars = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $start . "," . $limit . "");
	$div .= '<style> .video-block { padding-right:'.$configXML->gutterspace.'px } </style>';
					
				// were there any posts found?
				if (!empty($populars)) {
					//$div .='<h3 class="more_title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=pop">Popular Videos</a>';
					if (($showP < $countP)) {
						$div .='<a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=pop" class="more">more videos</a></h3>';
					} else if (($showP == $countP)) {
						$div .='<div style="float:right"> </div>';
					}
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
				$more = $_REQUEST['more'];
				$pagelist = self::pageList($_GET['page'], $pages, 'pop', $_GET['playid'], $_GET['page_id']);
				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';


                                } else
				$div .="No Popular videos";
				// end list
				$div .='</div>';
			}
			$div .='</div>';

			return $div;
		}


		function relatedPlaylist() {
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$pageFetch = $wpdb->get_row("SELECT page,gutterspace FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
			$limit = $pageFetch->page;
			$more = $_REQUEST['more'];
			//Search Videos listing Starts
			if (isset($_REQUEST['playid'])&& $more=='') {

				$start = self::findStart($limit);
				$pages = self::findPages($count, $limit);
				$getPlaylist = $_REQUEST['playid'];

				$countCheck = mysql_query("SELECT count(*) FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        WHERE (m.playlist_id = '$getPlaylist'
        AND m.media_id = w.vid) GROUP BY w.vid");
				$count = mysql_num_rows($countCheck);

				/* Find the number of pages based on $count and $limit */
				$pages = self::findPages($count, $limit);


				/* Now we use the LIMIT clause to grab a range of rows */
				 $fetch_video = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        WHERE (m.playlist_id = '$getPlaylist'
        AND m.media_id = w.vid) GROUP BY w.vid ORDER BY m.sorder";
                                 
				$relatedSearch = $wpdb->get_results($fetch_video . " LIMIT " . $start . "," . $limit . "");
                            
				$playlist = $wpdb->get_var("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = '$getPlaylist'");
				$div = '<div> <h1> ' . $playlist . ' Videos</h1>';
				$div .= '<style> .video-block { padding-right:'.$pageFetch->gutterspace.'px } </style>';
				//print_r($relatedSearch);
				if (!empty($relatedSearch)) {
					// posts were found, loop through them
					$p = 0;

					foreach ($relatedSearch as $playlistVideo) {
						$image[$p] = $playlistVideo->image;
						$vidS[$p] = $playlistVideo->vid;
						$nameS[$p] = $playlistVideo->name;
						$hitcount[$p] = $playlistVideo->hitcount;
						$duration[$p] = $playlistVideo->duration;
                                               // $sorder[$p] = $playlistVideo->sorder;
						$p++;
					}


					$rowCount = 0;
					$colPlay = 4;
					$div .= '<div>';
					for ($p = 0; $p < count($relatedSearch); $p++) {
						if (($p % $colPlay) == 0) {
							$rowCount++;
							$div .= '<div  class="video-block">';
							if ($image[$p] != '') {
								//$div .='<div class="duration">' . $duration[$p] . '</div>';
								$div .='<div  class="video-thumbimg">
                                <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '">
                                <img src="' . $image[$p] . '" alt="' . $nameS[$p] . '" class="imgHome" title="' . $nameS[$p] . '"></a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '" class="videoHname">';
								if (strlen($nameS[$p]) > 17) {
									$div.=substr($nameS[$p], 0, 17). "";
								} else {
									$div.=$nameS[$p];
								}
								$div .='</a></h5>';
								if ($hitcount[$p] != 0) {
									$div .='</a><div class="views">';
								   if($duration[$p]== 0.00){
                                                                            $div .= $hitcount[$p] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$p].' '.'|'.' '.$hitcount[$p] . ' views';
                                                                        }
									$div .= '</div>';
								}
								$div .= '<div class="clear"></div>';

								$div .='<div class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $getPlaylist . '">' . $playlist . '</a></div>';
							} else {
								//$div .='<div class="duration">' . $duration[$p] . '</div>';
								$div .='<div  class="video-thumbimg">
                                     <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '">
                                    <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameS[$p] . '" class="imgHome"
                            title="' . $nameS[$p] . '"></a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '" class="videoHname">';
								if (strlen($nameS[$p]) > 17) {
									$div.=substr($nameS[$p], 0, 17). "";
								} else {
									$div.=$nameS[$p];
								}
								$div .='</a></h5>';
								if ($hitcount[$p] != 0) {
									$div .='</a><div class="views">';
									   if($duration[$p]== 0.00){
                                                                            $div .= $hitcount[$p] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$p].' '.'|'.' '.$hitcount[$p] . ' views';
                                                                        }
									$div .= '</div>';
								}
								$div .= '<div class="clear"></div>';

								$div .='<div class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $getPlaylist . '">' . $playlist . '</a></div>';
							}
							$div .='</div>';
						} else {
							$div .= '<div  class="video-block">';
							if ($image[$p] != '') {
								//$div .='<div class="duration">' . $duration[$p] . '</div>';
								$div .='<div  class="video-thumbimg">
                                 <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '">
                                <img src="' . $image[$p] . '" alt="' . $nameS[$p] . '" class="imgHome" title="' . $nameS[$p] . '"></a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '" class="videoHname">';
								if (strlen($nameS[$p]) > 17) {
									$div.=substr($nameS[$p], 0, 17) . '<br>';
								} else {
									$div.=$nameS[$p];
								}
								$div .='</a></h5>';
								if ($hitcount[$p] != 0) {
									$div .='</a><div class="views">';
								   if($duration[$p]== 0.00){
                                                                            $div .= $hitcount[$p] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$p].' '.'|'.' '.$hitcount[$p] . ' views';
                                                                        }
									$div .= '</div>';
								}
								$div .= '<div class="clear"></div>';
								$div .='<div class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $getPlaylist . '">' . $playlist . '</a></div>';
							} else {
								//$div .='<div class="duration">' . $duration[$p] . '</div>';
								$div .='<div  class="video-thumbimg">
                                     <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '">
                                    <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="' . $nameS[$p] . '"
                            class="imgHome" title="' . $nameS[$p] . '"></a></div>';
								$div .='<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $vidS[$p] . '" class="videoHname">';
								if (strlen($nameS[$p]) > 17) {
									$div.=substr($nameS[$p], 0, 17). "";
								} else {
									$div.=$nameS[$p];
								}
								$div .='</a></h5>';
								if ($hitcount[$p] != 0) {
									$div .='</a><div class="views">';
								   if($duration[$p]== 0.00){
                                                                            $div .= $hitcount[$p] . ' views';
                                                                        }
									else
                                                                            {
                                                                            $div .= $duration[$p].' '.'|'.' '.$hitcount[$p] . ' views';
                                                                        }
									$div .= '</div>';
								}
								$div .= '<div class="clear"></div>';
								$div .='<div class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $getPlaylist . '">' . $playlist . '</a></div>';
							}
							$div .='</div>';
						}
					}
				
				if ($more == 'playlist') {
					$pagelist = self::pageList($_GET['page'], $pages, $more, $_REQUEST['playid'], $_GET['page_id']);
				} else {
					$pagelist = self::pageList($_GET['page'], $pages, $more, $_REQUEST['playid'], $_GET['page_id']);
				}

				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';
				$div .='</div>';} else
				$div .="No Playlist videos";

$div .='</div>';

			}
			return $div;
		}
		function categoryList() {
			
			global $wpdb;
			$site_url = get_bloginfo('url');
			$dir = dirname(plugin_basename(__FILE__));
			$dirExp = explode('/', $dir);
			$dirPage = $dirExp[0];
			$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
			$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
			$pageFetch = $wpdb->get_row("SELECT page,category_page,gutterspace FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

			$more = $_REQUEST['more'];
			//Search Videos listing Starts
			if ($_REQUEST['more'] == 'categories') {
				$limit = $pageFetch->category_page;
				$start = self::findStart($limit);
				$div .='<div>
                                  <h1 class="entry-title">Video Categories</h1></div>';
				$catsql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist LIMIT " . $start . "," . $limit . "";
				$catLists = $wpdb->get_results($catsql);
				$sql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist";
				$countLists = $wpdb->get_results($sql);
				$count = count($countLists);
				$pages = self::findPages($count, $limit);
				$div .= '<style> .video-block { padding-right:'.$pageFetch->gutterspace.'px } </style>';
				
				foreach ($catLists as $catList) {

					$sql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $catList->pid . "";

					$playLists = $wpdb->get_results($sql);
					$playlistCount = count($playLists);

					if ($playlistCount != '') {						
						$div .='<div>
         <h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
						$i = '0';
						$catL = 4;
						$inc =1;
						
						foreach ($playLists as $playList) {

							$duration = $playList->duration; 
							if (strlen($playList->name) > 17) {
								$playListName = substr($playList->name, 0, 17) . "";
							} else {
								$playListName = $playList->name;
							}

							if ($playList->image != '') {
								$div .='<div class="video-block">';
                                //$div .='<div class="duration">' . $duration . '</div>';
      $div.='<div class="video-thumbimg">
      <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $playList->image . '" alt="" class="imgHome" title=""></a></div>
           
        <h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $playListName . '</a></h5>
       <div class="views">' ;


      if($duration == 0.00)
                      {
                          $div .='<span class="views">'.$playList->hitcount.' views'.'</span>';
                      }else
                      {
                          $div .='<span class="views">'.$duration.' '.'|'.' '.$playList->hitcount.' views'.'</span>';
                      }


        $div .='</div></div>';
							} else {
								$div .='<div class="video-block">';
                               // $div.='<div class="duration">' . $duration . '</div>';
      $div.='<div class="video-thumbimg">
      <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="" class="imgHome" title=""></a></div>

<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $playListName . '</a>
    </h5>
       <div class="views">' ;
      if($duration == 0.00)
                      {
                          $div .='<span class="views">'.$playList->hitcount.' views'.'</span>';
                      }else
                      {
                          $div .='<span class="views">'.$duration.' '.'|'.' '.$playList->hitcount.' views'.'</span>';
                      }



				    $div .='</div>';			}
							if ($i > 8) {
								break;
							} else {
								$i = $i + 1;
							}
							
							 if( $inc % ($catL) == 0) {
                     $div .= '<div class="clear"></div>';
                     }
                     $inc++;
							
							
						}

						if (($playlistCount > 8)) {

							$div .='<a class="video-more" href="' . $site_url . '/?page_id=' . $moreName . '&more=category&playid=' . $catList->pid . '">more videos</a>';
						} else {
							$div .='<div align="right"> </div>';
						}
					}
				}
				$pagelist = self::pageList($_GET['page'], $pages, $more, '', $_GET['page_id']);
				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';
			
			} else if ($_REQUEST['playid'] != ''&& $_REQUEST['more']=='category') {

				$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
				$div .='<div>
         <h1 class="entry-title">Video Categories</h1><div>';
				$catsql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid=" . $_REQUEST['playid'] . "";
				$catLists = $wpdb->get_results($catsql);				
				foreach ($catLists as $catList) {
					$div .='<div>
                    <h1 class="entry-title">' . $catList->playlist_name . '</h1><div>';
					$limit = $pageFetch->page;
					$start = self::findStart($limit);
					$sql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $catList->pid . " group by m.media_id LIMIT " . $start . "," . $limit . "";
					$playLists = $wpdb->get_results($sql);
					$countSql = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id=" . $catList->pid . " group by m.media_id";
					$countplayLists = $wpdb->get_results($countSql);
					$playlistCount = count($countplayLists);
					$pages = self::findPages($playlistCount, $limit);

					if ($playlistCount != '') {
						$catL = 4;
				        $inc =1;
						foreach ($playLists as $playList) {
							
							$duration = $playList->duration;
							$playListName = substr($playList->name, 0, 17) . "";
							if ($playList->image != '') {
								$div .='<div class="video-block">';
                                //$div.='<div class="duration">' . $duration . '</div>';
                                $div .= '<div class="video-thumbimg">
      <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $playList->image . '" alt="" class="imgHome" title=""></a></div>
   
<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $playListName . '</a>
    </h5>
          <div class="views">';
                                
                                         if($playList->duration==0.00)
                    { $div .=$playList->hitcount . ' views'; }
                    else
                    {
                        $div .=$playList->duration.' '.'|'.' '.$playList->hitcount . ' views';
                    }
                                $div.='</div></div>';
							} else {
								$div .='<div class="video-block">';
                                //$div.= '<div class="duration">' . $duration . '</div>';
                                $div.= '<div class="video-thumbimg">
      <a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '">
       <img src="' . $site_url . '/wp-content/plugins/' . $dirPage . '/images/hdflv.jpg" alt="" class="imgHome" title=""></a></div>
        
<h5><a href="' . $site_url . '/?page_id=' . $vPageID . '&vid=' . $playList->vid . '" class="videoHname">' . $playListName . '</a></h5>

<div class="views">';


if($playList->duration == 0.00) {
                            $div .=  $playList->hitcount.' views';
                        }
                        else{
                            $div .=  $playList->duration.' '.'|'.' '.  $playList->hitcount.' views';
                        }
                                        $playList->hitcount . ' views</div>
    
</div>';
							}							
							
					 if( $inc % ($catL) == 0) {
                     $div .= '<div class="clear"></div>';
                     }
                     $inc++;
							
						}
					} else {
						$div .='<div>No Videos Found</div>';
					}				
					
				}
				$more = $_REQUEST['more'];
				$pagelist = self::pageList($_GET['page'], $pages, 'category', $_GET['playid'], $_GET['page_id']);
				$div .='<div class="clear"></div>';
				$div .='<div class="right video-pagination">' . $pagelist . '</div>';
				$div .='</div></div></div></div>';
			}

			return $div;
		}

	}
	?>