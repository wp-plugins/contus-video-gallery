<?php
/**  
 * Video more view file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
if (class_exists ( 'ContusMoreView' ) != true) {
	class ContusMoreView extends ContusMoreController { // CLASS FOR HOME PAGE STARTS
		public $_settingsData;
		public $_vId;
		public $_playid;
		public $_pagenum;
		public function __construct() { // contructor starts
			parent::__construct ();
			global $wp_query;
			$video_search = '';
			$this->_settingsData = $this->settings_data (); // Get player settings
			$this->_mPageid = $this->more_pageid (); // Get more page id
			$this->_feaMore = $this->video_count (); // Get featured videos count
			$this->_vId = absint( filter_input ( INPUT_GET, 'vid' ) ); // Get vid from URL
			$this->_pagenum = absint (filter_input ( INPUT_GET, 'pagenum' ) ); // Get current page number
			$this->_playid =  &$wp_query->query_vars ['playid'] ;
			$this->_userid = &$wp_query->query_vars ['userid'] ;
			
			// Get pid from URL
			$this->_viewslang = __ ( 'Views', 'video_gallery' );
			$this->_viewlang = __ ( 'View', 'video_gallery' );
			// Get search keyword
			$searchVal = str_replace ( ' ', '%20', __ ( 'Video Search ...', 'video_gallery' ) );
			if (isset ( $wp_query->query_vars ['video_search'] ) && $wp_query->query_vars ['video_search'] !== $searchVal) {
				$video_search = $wp_query->query_vars ['video_search'];
			}
			$this->_video_search = stripslashes ( urldecode ( $video_search ) );
			
			$this->_showF = 5;
			$this->_colF = $this->_settingsData->colMore; // get row of more page
			$this->_colCat = $this->_settingsData->colCat; // get column of more page
			$this->_rowCat = $this->_settingsData->rowCat; // get row of category videos
			$this->_perCat = $this->_colCat * $this->_rowCat; // get column of category videos
			$dir = dirname ( plugin_basename ( __FILE__ ) );
			$dirExp = explode ( '/', $dir );
			$this->_folder = $dirExp [0]; // Get plugin folder name
			$this->_site_url = get_site_url (); // Get base url
			$this->_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS; // Declare image path
		} // contructor ends
		/**
		 * Content show in the video more page
		 * @parem $type
		 */
		function video_more_pages($type) { 
			if (function_exists ( 'homeVideo' ) != true) {
				$type_name = '';
				switch ($type) {
					case 'popular' : 
						$rowF = $this->_settingsData->rowMore; // row field of popular videos
						$colF = $this->_settingsData->colMore; // column field of popular videos
						$dataLimit = $rowF * $colF;
						$where = '';
						$thumImageorder = 'w.hitcount DESC';
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_thumbdata ( $thumImageorder, $where, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videos ( '', '', $thumImageorder, $where );
						$typename = __ ( 'Popular', 'video_gallery' );
						$type_name = 'popular';
						$morePage = '&more=pop';
						break; 
					
					case 'recent' :
						$rowF = $this->_settingsData->rowMore;
						$where = '';
						$colF = $this->_settingsData->colMore;
						$dataLimit = $rowF * $colF;
						$thumImageorder = 'w.vid DESC';
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_thumbdata ( $thumImageorder, $where, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videos ( '', '', $thumImageorder, $where );
						$typename = __ ( 'Recent', 'video_gallery' );
						$type_name = 'recent';
						$morePage = '&more=rec';
						break;
					case 'random':
						$rowF = $this->_settingsData->rowMore;
						$where = '';
						$colF = $this->_settingsData->colMore;
						$dataLimit = $rowF * $colF;
						$thumImageorder = 'w.vid DESC';
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_thumbdata ( $thumImageorder, $where, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videos ( '', '', $thumImageorder, $where );
						$typename = __ ( 'Random', 'video_gallery' );
						$type_name = 'random';
						$morePage = '&more=rand';
						break;
					case 'featured' :
						$thumImageorder = 'w.ordering ASC';
						$where = 'AND w.featured=1';
						$rowF = $this->_settingsData->rowMore;
						$colF = $this->_settingsData->colMore;
						$dataLimit = $rowF * $colF;
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$player_color = unserialize( $this->_settingsData->player_colors);
						$recent_video_order = $player_color['recentvideo_order'];	
						if ($recent_video_order == 'id') {
							$thumImageorder = 'w.vid DESC';
						} elseif ($recent_video_order == 'hitcount') {
							$thumImageorder = 'w.' . $recent_video_order . ' DESC';
						} elseif ($recent_video_order == 'default') {
							$thumImageorder = 'w.ordering ASC';
						}  else {
							$thumImageorder = 'w.vid DESC';
						}
						$TypeOFvideos = $this->home_thumbdata ( $thumImageorder, $where, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videos ( '', '', $thumImageorder, $where );
						$typename = __ ( 'Featured', 'video_gallery' );
						$type_name = 'featured';
						$morePage = '&more=fea';
						break;
					case 'cat' :
						$thumImageorder = absint( $this->_playid );
						$where = '';
						$rowF = $this->_settingsData->rowCat;
						$colF = $this->_settingsData->colCat;
						$dataLimit = $rowF * $colF;
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$player_color = unserialize( $this->_settingsData->player_colors);
						$recent_video_order = $player_color['recentvideo_order'];
						if ($recent_video_order == 'id') {
							$default_order = 'w.vid DESC';
						} elseif ($recent_video_order == 'hitcount') {
							$default_order = 'w.' . $recent_video_order . ' DESC';
						} elseif ($recent_video_order == 'default') {
							$default_order = 'w.ordering ASC';
						}  else {
							$default_order = 'w.vid DESC';
						}
						$TypeOFvideos = $this->home_catthumbdata ( $thumImageorder, $pagenum, $dataLimit ,$default_order );
						$CountOFVideos = $this->countof_videos ( absint( $this->_playid ), '', $thumImageorder, $where );
						$typename = __ ( 'Category', 'video_gallery' );
						$morePage = '&playid=' . $thumImageorder;
						break;
					case 'user' :
						$thumImageorder = $this->_userid;
						$where = '';
						$rowF = $this->_settingsData->rowCat;
						$colF = $this->_settingsData->colCat;
						$dataLimit = $rowF * $colF;
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_userthumbdata ( $thumImageorder, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videos ( '', $this->_userid, $thumImageorder, $where );
						$typename = __ ( 'User', 'video_gallery' );
						$morePage = '&userid=' . $thumImageorder;
						break;
					case 'search' :
						$video_search = str_replace ( '%20', ' ', $this->_video_search );
						if ($this->_video_search == __ ( 'Video Search ...', 'video_gallery' )) {
							$video_search = '';
						}
						$thumImageorder = $video_search;
						$rowF = $this->_settingsData->rowMore;
						$colF = $this->_settingsData->colMore;
						$dataLimit = $rowF * $colF;
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_searchthumbdata ( $thumImageorder, $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videosearch ( $thumImageorder );
						return $this->searchlist ( $video_search, $CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit );
						break;
					case 'categories' :
					default :
						$rowF = $this->_settingsData->rowCat; // category setting row value
						$colF = $this->_settingsData->colCat; // category setting column value
						$dataLimit = $rowF * $colF;
						$pagenum =  $this->_pagenum;
						if( empty($pagenum ) ) {
							$pagenum = 1;
						}
						$TypeOFvideos = $this->home_categoriesthumbdata ( $pagenum, $dataLimit );
						$CountOFVideos = $this->countof_videocategories ();
						$typename = __ ( 'Video Categories', 'video_gallery' );
						return $this->categorylist ( $CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit );
						break;
				}
				
				$div = '';
				$ratearray = array (
						'nopos1',
						'onepos1',
						'twopos1',
						'threepos1',
						'fourpos1',
						'fivepos1' 
				);
				?><?php		
				$pagenum = absint( $this->_pagenum ) ? absint ( $this->_pagenum ) : 1;
				$div = '<div class="video_wrapper" id="' . $type_name . '_video">';
				$div .= '<style type="text/css"> .video-block {  margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
				if ($typename == 'Category') {
					$playlist_name = get_playlist_name ( intval ( absint( $this->_playid ) ) );
					$div .= '<h2 class="titleouter" >' . $playlist_name . ' </h2>';
				} else if ($typename == 'User') {
					$user_name = get_user_name ( intval ( $this->_userid ) );
					$div .= '<h2 >' . $user_name . ' </h2>';
				} else {
					$div .= '<h2 >' . $typename . ' ' . __ ( 'Videos', 'video_gallery' ) . ' </h2>';
				}
				if (! empty ( $TypeOFvideos )) {
					$videolist = 0;
					$fetched [$videolist] = '';
					$image_path = str_replace ( 'plugins/' . $this->_folder . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
					foreach ( $TypeOFvideos as $video ) {
						$duration [$videolist] = $video->duration; // VIDEO DURATION
						$imageFea [$videolist] = $video->image; // VIDEO IMAGE
						$file_type = $video->file_type; // Video Type
						$guid [$videolist] = get_video_permalink ( $video->slug ); // guid
						if ($video->image == '') { // If there is no thumb image for video
							$imageFea [$videolist] = $this->_imagePath . 'nothumbimage.jpg';
						} else {
							if ($file_type == 2 || $file_type == 5 ) { // For uploaded image
                                if( $video->amazon_buckets == 1 && strpos( $video->image , '/' ) ){
                                  $imageFea[$videolist] = $imageFea[$videolist];
								} else{
								   $imageFea [$videolist] = $image_path . $imageFea [$videolist];
								}
							} else if( $file_type == 3 ) {
								$imageFea [$videolist] = $imageFea [$videolist];
							}
						}
						$vidF [$videolist]      = $video->vid;            // VIDEO ID
						$nameF [$videolist]     = $video->name;          // VIDEI NAME
						$hitcount [$videolist]  = $video->hitcount;   // VIDEO HITCOUNT
						$ratecount [$videolist] = $video->ratecount; // VIDEO RATECOUNT
						$rate [$videolist]      = $video->rate;           // VIDEO RATE
						$des[$videolist]        = substr($video->description,0,30).'&hellip;';    // video description
						if (! empty (  $this->_playid  ) ) {
							$fetched [$videolist] = $video->playlist_name;
							$fetched_pslug [$videolist] = $video->playlist_slugname;
							$playlist_id [$videolist] = absint( $this->_playid );
						} else {
							$getPlaylist = $this->_wpdb->get_row ( 'SELECT playlist_id FROM ' . $this->_wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id="' . intval ( $vidF [$videolist] ) . '"' );
							if (isset ( $getPlaylist->playlist_id )) {
								$playlist_id [$videolist] = $getPlaylist->playlist_id; // VIDEO CATEGORY ID
								$fetPlay [$videolist] = $this->_wpdb->get_row ( 'SELECT playlist_name,playlist_slugname FROM ' . $this->_wpdb->prefix . 'hdflvvideoshare_playlist WHERE pid="' . intval ( $playlist_id [$videolist] ) . '"' );
								$fetched [$videolist] = $fetPlay [$videolist]->playlist_name; // CATEOGORY NAME
								$fetched_pslug [$videolist] = $fetPlay [$videolist]->playlist_slugname; // CATEOGORY NAME
							}
						}
						$videolist ++;
					}
					$div .= '<div>';
					$div .= '<ul class="video-block-container">';
					for($videolist = 0; $videolist < count ( $TypeOFvideos ); $videolist ++) {
						if (strlen ( $nameF [$videolist] ) > 30) { // Displaying Video Title
							$videoname = substr ( $nameF [$videolist], 0, 30 ) . '..';
						} else {
							$videoname = $nameF [$videolist];
						}
						if (($videolist % $colF) == 0 && $videolist != 0) { // COLUMN COUNT
							$div .= '</ul><div class="clear"></div><ul class="video-block-container">';
						}
						$div .= '<li class="video-block">';
						$div .= '<div  class="video-thumbimg"><a href="' . $guid [$videolist] . '" title="'.$nameF[$videolist].'"><img src="' . $imageFea [$videolist] . '" alt="' . $nameF [$videolist] . '" class="imgHome" title="' . $nameF [$videolist] . '" /></a>';
						if ($duration [$videolist] != '0:00') {
							$div .= '<span class="video_duration">' . $duration [$videolist] . '</span>';
						}
						$div .= '</div>';
						$div .= '<div class="vid_info"><a href="' . $guid [$videolist] . '" title="'.$nameF[$videolist].'" class="videoHname"><span>';
						$div .= $videoname;
						$div .= '</span></a>';
						if (! empty ( $fetched [$videolist] ) && ( $this->_settingsData->categorydisplay == 1 )) {
							$playlist_url = get_playlist_permalink ( $this->_mPageid, $playlist_id [$videolist], $fetched_pslug [$videolist] );
							$div .= '<a  class="playlistName"   href="' . $playlist_url . '"><span>' . $fetched [$videolist] . '</span></a>';
						}
						// Rating starts here
						if ($this->_settingsData->ratingscontrol == 1) {
							if (isset ( $ratecount [$videolist] ) && $ratecount [$videolist] != 0) {
								$ratestar = round ( $rate [$videolist] / $ratecount [$videolist] );
							} else {
								$ratestar = 0;
							}
							$div .= '<span class="ratethis1 ' . $ratearray [$ratestar] . '"></span>';
						}
						// Rating ends and views starts here
						if ($this->_settingsData->view_visible == 1) {
							$div .= '<span class="video_views">';
							if ($hitcount [$videolist] > 1) {
								$viewlang = $this->_viewslang;
							} else {
								$viewlang = $this->_viewlang;
							}
							$div .= $hitcount [$videolist] . '&nbsp;' . $viewlang;
							$div .= '</span>';
						}
						$div .= '</div>';
						$div .= '</li>';
						// ELSE ENDS
					} // FOR EACH ENDS
					$div .= '</ul>';
					$div .= '</div>';
					$div .= '<div class="clear"></div>';
				} else {
					if ($typename == 'Category') {
						$div .= __ ( 'No', 'video_gallery' ) . '&nbsp;' . __ ( 'Videos', 'video_gallery' ) . '&nbsp;' . __ ( 'Under&nbsp;this&nbsp;Category', 'video_gallery' );
					} else {
						$div .= __ ( 'No', 'video_gallery' ) . '&nbsp;' . $typename . '&nbsp;' . __ ( 'Videos', 'video_gallery' );
					}
				}
				$div .= '</div>';
				
				// PAGINATION STARTS
				$total = $CountOFVideos;
				$num_of_pages = ceil ( $total / $dataLimit );
				$page_links = paginate_links ( array (
						'base' => add_query_arg ( 'pagenum', '%#%' ),
						'format' => '',
						'prev_text' => __ ( '&laquo;', 'aag' ),
						'next_text' => __ ( '&raquo;', 'aag' ),
						'total' => $num_of_pages,
						'current' => $pagenum 
				) );
				
				if ($page_links) {
					$div .= '<div class="tablenav"><div class="tablenav-pages" >' . $page_links . '</div></div>';
				}
				// PAGINATION ENDS
				return $div;
			}
		}
		function categorylist($CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit) {
			global $wpdb;
			$div = '';
			$pagenum = absint ( $pagenum ) ? absint ( $pagenum ) : 1; // Calculating page number
			$start = ($pagenum - 1) * $dataLimit; // Video starting from
			$ratearray = array (
					'nopos1',
					'onepos1',
					'twopos1',
					'threepos1',
					'fourpos1',
					'fivepos1' 
			);
			?>

			<?php
			$div .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
			foreach ( $TypeOFvideos as $catList ) {
				// Fetch videos for every category
				$sql = 'SELECT s.guid,w.* FROM ' . $wpdb->prefix . 'hdflvvideoshare AS w
						INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play AS m ON m.media_id = w.vid
						INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist AS p on m.playlist_id = p.pid
						INNER JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
						WHERE w.publish=1 AND p.is_publish=1 AND m.playlist_id=' . intval ( $catList->pid ) . ' GROUP BY w.vid';
				$playLists = $wpdb->get_results ( $sql );
				$moreName = $wpdb->get_var ( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" AND post_status="publish" AND post_type="page" LIMIT 1' );
				$playlistCount = count ( $playLists );
				
				$div .= '<div class="titleouter"> <h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
				if (! empty ( $playlistCount )) {
					$i = 0;
					$inc = 1;
					$image_path = str_replace ( 'plugins/' . $this->_folder . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
					$div .= '<ul class="video-block-container">';
					foreach ( $playLists as $playList ) {
						
						$duration = $playList->duration;
						$imageFea = $playList->image; // VIDEO IMAGE
						$file_type = $playList->file_type; // Video Type
						$guid = get_video_permalink ( $playList->slug ); // guid
						if ($imageFea == '') { // If there is no thumb image for video
							$imageFea = $this->_imagePath . 'nothumbimage.jpg';
						} else {
							if ($file_type == 2 || $file_type == 5  ) { // For uploaded image
                          
                                if( (isset($playList->amazon_buckets) && $playList->amazon_buckets ) && strpos($imageFea , '/' ) ) {
                                	$imageFea = $imageFea;
                                }else{
									$imageFea = $image_path . $imageFea;
								}
							} else if( $file_type == 3 ) {
								if( $file_type == 3){
									$imageFea = $imageFea;
								}
							}
						}
						if (strlen ( $playList->name ) > 30) {
							$playListName = substr ( $playList->name, 0, 30 ) . '..';
						} else {
							$playListName = $playList->name;
						}
						$playlist_more_link =  get_playlist_permalink($moreName,$catList->pid,$catList->playlist_slugname );
						$div .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '" title="'.$playList->name.'" ><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';
						if ($duration != '0:00') {
							$div .= '<span class="video_duration">' . $duration . '</span>';
						}
						$div .= '</div><div class="vid_info"><h5><a href="' . $guid . '" class="videoHname" title="'.$playList->name.'">' . $playListName . '</a></h5>';
						
						if( $this->_settingsData->categorydisplay == 1 ){
                            $div .='<a class="playlistName" href="'.$playlist_more_link .'"><span>'.$catList->playlist_name.'</span></a>'; 
                        }
                        // Rating starts here
						if ($this->_settingsData->ratingscontrol == 1) {
							if (isset ( $playList->ratecount ) && $playList->ratecount != 0) {
								$ratestar = round ( $playList->rate / $playList->ratecount );
							} else {
								$ratestar = 0;
							}
							$div .= '<span class="ratethis1 ' . $ratearray [$ratestar] . '"></span>';
						}
						// Rating ends and views starts here
						if ($this->_settingsData->view_visible == 1) {
							if ($playList->hitcount > 1) {
								$viewlang = $this->_viewslang;
							} else {
								$viewlang = $this->_viewlang;
							}
							$div .= '<span class="video_views">' . $playList->hitcount . '&nbsp;' . $viewlang . '</span>';
						}
						
						$div .= '</div></li>';
						
						if ($i > ($this->_perCat - 2)) {
							break;
						} else {
							$i = $i + 1;
						}
						if (($inc % $this->_colCat) == 0 && $inc != 0) { // COLUMN COUNT
							$div .= '</ul><div class="clear"></div><ul class="video-block-container">';
						}
						$inc ++;
					}
					$div .= '</ul>';
					$rowF = $this->_settingsData->rowCat; // category setting row value
					$colF = $this->_settingsData->colCat; // category setting column value
					if ($rowF && $colF) {
						$dataLimit = $rowF * $colF;
					} else if($rowF || $colF){
							
					} else {
						$dataLimit = 8;
					}
					if (($playlistCount > $dataLimit)) {
						
						$div .= '<a class="video-more" href="' . $playlist_more_link .'">' . __ ( 'More&nbsp;Videos', 'video_gallery' ) . '</a>';
					} else {
						$div .= '<div align="clear"> </div>';
					}
				} else { // If there is no video for category
					$div .= '<div class="titleouter">' . __ ( 'No&nbsp;Videos&nbsp;for&nbsp;this&nbsp;Category', 'video_gallery' ) . '</div>';
				}
			}
			
			$div .= '<div class="clear"></div>';
			// PAGINATION STARTS
			$total = $CountOFVideos;
			$num_of_pages = ceil ( $total / $dataLimit );
			$page_links = paginate_links ( array (
					'base' => add_query_arg ( 'pagenum', '%#%' ),
					'format' => '',
					'prev_text' => __ ( '&laquo;', 'aag' ),
					'next_text' => __ ( '&raquo;', 'aag' ),
					'total' => $num_of_pages,
					'current' => $pagenum 
			) );
			
			if ($page_links) {
				$div .= '<div class="contus_tablenav"><div class="contus_tablenav-pages" >' . $page_links . '</div></div>';
			}
			
			// PAGINATION ENDS
			return $div;
		}
		function searchlist($video_search, $CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit) {
			$div = '';
			$pagenum = isset ( $pagenum ) ? absint ( $pagenum ) : 1; // Calculating page number
			$start = ($pagenum - 1) * $dataLimit; // Video starting from
			$limit = $dataLimit; // Video Limit
			$ratearray = array (
					'nopos1',
					'onepos1',
					'twopos1',
					'threepos1',
					'fourpos1',
					'fivepos1' 
			);
			$div .= '<div class="video_wrapper" id="video_search_result"><h3 class="entry-title">' . __ ( 'Search Results', 'video_gallery' ) . ' - ' . $video_search . '</h3>';
			$div .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
			
			// Fetch videos for every category
			if (! empty ( $TypeOFvideos )) {
				$i = $inc = 0;
				$image_path = str_replace ( 'plugins/' . $this->_folder . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
				$div .= '<ul class="video-block-container">';
				
				foreach ( $TypeOFvideos as $playList ) {
					
					$duration = $playList->duration;
					$imageFea = $playList->image; // VIDEO IMAGE
					$file_type = $playList->file_type; // Video Type
					$guid = get_video_permalink ( $playList->slug ); // guid
					if ($imageFea == '') { // If there is no thumb image for video
						$imageFea = $this->_imagePath . 'nothumbimage.jpg';
					} else {
						if ($file_type == 2 || $file_type == 5) { 
							if( strpos( $imageFea , '/' ) ){
								$imageFea = $imageFea;
							} else {
								$imageFea = $image_path . $imageFea;
							}
						} else if($file_type == 3){
							$imageFea = $imageFea;
						}
					}
					if (strlen ( $playList->name ) > 30) {
						$playListName = substr ( $playList->name, 0, 30 ) . '..';
					} else {
						$playListName = $playList->name;
					}
					if (($inc % $this->_colF) == 0 && $inc != 0) { // COLUMN COUNT
						$div .= '</ul><div class="clear"></div><ul class="video-block-container">';
					}
					$div .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '" title="'.$playList->name.'"><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';
					if ($duration != '0:00') {
						$div .= '<span class="video_duration">' . $duration . '</span>';
					}
					$div .= '</div><div class="vid_info"><a href="' . $guid . '" class="videoHname" title="'.$playList->name.'" >' . $playListName . '</a>';
					if (! empty ( $playList->playlist_name )) {
						$playlist_url = get_playlist_permalink ( $this->_mPageid, $playList->pid, $playList->playlist_slugname );
						$div .= '<a class="playlistName" href="' . $playlist_url . '">' . $playList->playlist_name . '</a>';
					}
					// Rating starts here
					if ($this->_settingsData->ratingscontrol == 1) {
						if (isset ( $playList->ratecount ) && $playList->ratecount != 0) {
							$ratestar = round ( $playList->rate / $playList->ratecount );
						} else {
							$ratestar = 0;
						}
						$div .= '<span class="ratethis1 ' . $ratearray [$ratestar] . '"></span>';
					}
					// Rating ends and views starts here
					if ($this->_settingsData->view_visible == 1) {
						if ($playList->hitcount > 1) {
							$viewlang = $this->_viewslang;
						} else {
							$viewlang = $this->_viewlang;
						}
						$div .= '<span class="video_views">' . $playList->hitcount . '&nbsp;' . $viewlang . '</span>';
					}
					$div .= '</div></li>';
					
					$inc ++;
				}
				$div .= '</ul>';
			} else { // If there is no video for category
				$div .= '<div>' . __ ( 'No&nbsp;Videos&nbsp;Found', 'video_gallery' ) . '</div>';
			}
			$div .= '</div>';
			
			$div .= '<div class="clear"></div>';
			
			// PAGINATION STARTS
			$total = $CountOFVideos;
			$num_of_pages = ceil ( $total / $dataLimit );
			$video_search = str_replace ( ' ', '%20', $video_search );
			$arr_params = array (
					'pagenum' => '%#%' 
			);
			$page_links = paginate_links ( array (
					'base' => add_query_arg ( $arr_params ),
					'format' => '',
					'prev_text' => __ ( '&laquo;', 'aag' ),
					'next_text' => __ ( '&raquo;', 'aag' ),
					'total' => $num_of_pages,
					'current' => $pagenum 
			) );
			
			if ($page_links) {
				$div .= '<div class="contus_tablenav"><div class="contus_tablenav-pages" >' . $page_links . '</div></div>';
			}
			
			// PAGINATION ENDS
			return $div;
		} // CATEGORY FUNCTION ENDS
	} // class over
} else {
	echo 'class contusMore already exists';
}
?>