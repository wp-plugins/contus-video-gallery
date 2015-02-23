<?php
/**  
 * Video home and short code [videohome] view file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

if ( class_exists( 'ContusVideoView' ) != true ) {

	class ContusVideoView extends ContusVideoController {								// CLASS FOR HOME PAGE STARTS

		public $_settingsData;
		public $_videosData;
		public $_swfPath;
		public $_singlevideoData;
		public $_videoDetail;
		public $_vId;
		public $_setting_video_order;
		public $_setting_related_video_count;

		public function __construct() {													// contructor starts
			parent::__construct();
			$this->_settingsData	  = $this->settings_data();						    //  Get player settings
			$this->_videosData		  = $this->videos_data();							//  Get particular video data
			$this->_mPageid			  = $this->more_pageid();							//  Get more page id
			$this->_feaMore			  = $this->video_count();							//  Get featured videos count
			$this->_vId				  = absint( filter_input( INPUT_GET, 'vid' ) );				//  Get vid from URL
			$this->_pId				  = absint( filter_input( INPUT_GET, 'pid' ) );				//  Get pid from URL
			$this->_tagname			  = $this->tag_detail( $this->_vId );				//  Get tag detail for the current video
			$this->_pagenum			  = filter_input( INPUT_GET, 'pagenum' );			//  Get current page number
			$this->_showF			  = 5;
			$this->_colCat			  = $this->_settingsData->colCat;
			$this->_site_url		  = get_site_url();
			$this->_singlevideoData   = $this->home_playerdata();
			$this->_featuredvideodata = $this->home_featuredvideodata();				//  Get featured videos data
			$this->_viewslang		  = __( 'Views', 'video_gallery' );
			$this->_viewlang		  = __( 'View', 'video_gallery' );
			$dir					  = dirname( plugin_basename( __FILE__ ) );
			$dirExp					  = explode( '/', $dir );
			$this->_plugin_name		  = $dirExp[0];									   //  Get plugin folder name
			$this->_bannerswfPath     = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer_banner.swf';		//  Declare banner swf path
			$this->_swfPath			  = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';			//  Declare swf path
			$this->_imagePath		  = APPTHA_VGALLERY_BASEURL . 'images' . DS;		
			$this->_setting_related_video_count  = $this->settings_data();							//  Declare image path
		}																				// contructor ends
        /**
         * Show video players 
         */                        
		function home_player() {	
                           
			$settingsData = $this->_settingsData;
			$videoUrl     = $videoId = $thumb_image = $homeplayerData = $file_type = '';
			$mobile       = vgallery_detect_mobile();
			if ( ! empty( $this->_featuredvideodata[0] ) ) {
				$homeplayerData = $this->_featuredvideodata[0];
			}
			
                 	$image_path = str_replace( 'plugins/' . $this->_plugin_name . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
			$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
			if ( ! empty( $homeplayerData ) ) {
				$videoUrl    = $homeplayerData->file;									//  Get video URL
				$videoId     = $homeplayerData->vid;									//  Get Video ID
				$thumb_image = $homeplayerData->image;									//  Get thumb image
				$file_type   = $homeplayerData->file_type;
				$video_title = $homeplayerData->name;								//  Get file type of a video
				if ( $thumb_image == '' ) {												//  If there is no thumb image for video
					$thumb_image = $_imagePath . 'nothumbimage.jpg';
				} else {
					if ( $file_type == 2 || $file_type == 5 ) {							//  For uploaded image
						$amazon_imageurl  = strpos('/',$thumb_image);  
						if($homeplayerData->amazon_buckets && $amazon_imageurl ){                          //  For Amazon  S3 Buckets images   
							$thumb_image = $thumb_image;
						}else{
							$thumb_image = $image_path. $thumb_image;
						}
					}
				}
			}

			$moduleName = 'playerModule';
			$div = '<div>';																//  video player starts
			//  To increase hit count of a video
			$div    .= '<script type="text/javascript" src="' . APPTHA_VGALLERY_BASEURL . 'js/script.js"></script>';
			$div    .= '<style type="text/css" scoped> .video-block {margin-left:' . $settingsData->gutterspace . 'px !important; float:left;} </style>';
			$div    .= ' <script>
					var baseurl,folder,videoPage;
					baseurl = "' . $this->_site_url . '";
					folder  = "' . $this->_plugin_name . '";
					videoPage = "' . $this->_mPageid . '"; </script>';
			$baseref = '';
			if ( ! empty( $this->_vId ) ) {
				$baseref .= '&amp;vid=' . $this->_vId;
			} else {
				$baseref .= '&amp;featured=true';
			}
			//Show/hide  the  video title of  the  home  video players
            $settings =  $this->_settingsData;
			$player_colors = unserialize($settings->player_colors);		
			$div .= '<div id="mediaspace" class="mediaspace" style="color: #666;">';
			
			// Show  Hide / Show option for title
			if( isset( $player_colors['showTitle'] ) && $player_colors['showTitle'] ){
			  $div .='<script type="text/javascript">function current_video(vid,title){ document.getElementById("video_title").innerHTML = title; }</script>';
              $div .='<h3 id="video_title" style="width:' . $settingsData->width . ';text-align: left;"  class="more_title"></h3>';
			}
			// end title enable /disable
			$div .= '<div id="flashplayer" class="videoplayer">';
			if ( $settingsData->default_player == 1 ) {
				$swf = $this->_bannerswfPath;
				$showplaylist = '&amp;showPlaylist=true';
			} else {
				$swf = $this->_swfPath;
				$showplaylist = '';
			}
			
			if ( $homeplayerData->file_type == 5 && ! empty( $homeplayerData->embedcode ) ) {
				$playerembedcode = stripslashes( $homeplayerData->embedcode );
				$div .= str_replace( 'width=', 'width="' . $settingsData->width . '"', $playerembedcode );
				$div .= '<script> current_video( ' . $homeplayerData->vid . ',"' . $homeplayerData->name . '" ); </script>';
			} else {
				if ( $mobile == true ) {
					if ( ( preg_match( '/vimeo/', $videoUrl ) ) && ( $videoUrl != '' ) ) {					// IF VIDEO IS YOUTUBE
						$vresult = explode( '/', $videoUrl );
						$div    .= '<iframe width="100%" type="text/html" src="http://player.vimeo.com/video/"' . $vresult[3] . '" frameborder="0"></iframe>';
					} elseif ( strpos( $videoUrl, 'youtube' ) > 0 ) {
						$imgstr   = explode( 'v=', $videoUrl );
						$imgval   = explode( '&', $imgstr[1] );
						$videoId1 = $imgval[0];
						$div     .= '<iframe width="100%" type="text/html" src="http://www.youtube.com/embed/'. $videoId1 . '" frameborder="0"></iframe>';
					}  elseif (strpos ( $videourl, 'dailymotion' ) > 0) { // For dailymotion videos
						$video = $videourl;
					    $split = explode ( "/", $video );
						$split_id = explode ( "_", $split [4] );
						$video = $previewurl = $video_url = 'http://www.dailymotion.com/embed/video/' . $split_id [0]; 												
						$output .= '<iframe src="' . $video . '" width="100%" class="iframe_frameborder" ></iframe>';						
					}  else {																		// IF VIDEO IS UPLOAD OR DIRECT PATH
						if ( $file_type == 2 ) {														// For uploaded image
							if( $file_type == 2 && strpos($videoUrl , '/' ) ) {
								$videoUrl = $videoUrl;
							}else{
								$videoUrl = $image_path . $videoUrl;
							}
						} else if ( $file_type == 4 ) {												// For RTMP videos
							$streamer = str_replace( 'rtmp://', 'http://', $homeplayerData->streamer_path );
							$videoUrl = $streamer . '_definst_/mp4:' . $videoUrl . '/playlist.m3u8';
						}
						$div .= '<video width="100%" id="video" poster="' . $thumb_image . '"   src="' . $videoUrl . '" autobuffer controls onerror="failed( event )">' . __( 'Html5 Not support This video Format.', 'video_gallery' ) . '</video>';
					}
				} else {
					$site_url = get_site_url();																				//  Flash player code
					$div .= '<embed id="player" src="' . $swf . '"  flashvars="baserefW=' . $site_url . $baseref . $showplaylist . '&amp;mtype=' . $moduleName . '" width="' . $settingsData->width . '" height="' . $settingsData->height . '"   allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent" />';
				}
			}            
			$div .= '</div>';
			$windo     = '';
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			if ( strpos( $useragent, 'Windows Phone' ) > 0 )
				$windo = 'Windows Phone';
																									// section to notify not support video format
			$div .= '<script>
					var txt =  navigator.platform ;
					var windo = "' . $windo . '";
					function failed( e )
					{
					if( txt =="iPod"|| txt =="iPad" || txt == "iPhone" || windo=="Windows Phone" || txt == "Linux armv7l" || txt == "Linux armv6l" )
					{
					alert( "' . __( 'Player doesnot support this video.', 'video_gallery' ) . '" );
					}
					}
					</script>';
			$div .= '<div id="video_tag" class="views"></div>';
			$div .= '</div>';
			$div .= '</div>';
			return $div;
		}
                /**
                 * function for show  recent ,feature ,category and popular video in home page after player  
                 */                        
		function home_thumb( $type ) {		
			$recent_video_order = $this->_setting_related_video_count;
			if ( function_exists( 'homeVideo' ) != true ) {
				$TypeSet = $recent_video_order = '';
				$recent_video_order = $this->_settingsData;
				switch ( $type ) {
					case 'popular':																	   // GETTING POPULAR VIDEOS STARTS
						$TypeSet   = $this->_settingsData->popular;									   //  Popular Videos
						$rowF	   = $this->_settingsData->rowsPop;									   //  get row of popular videos
						$colF      = $this->_settingsData->colPop;									   //  get column of popular videos
						$dataLimit = $rowF * $colF;
						$thumImageorder = 'w.hitcount DESC';
						$where = '';
						$TypeOFvideos   = $this->home_thumbdata( $thumImageorder, $where, $dataLimit );
						$CountOFVideos  = $this->countof_home_thumbdata( $thumImageorder, $where );
						$typename  = __( 'Popular', 'video_gallery' );
						$type_name = 'popular';
						$morePage  = 'popular';
						break;																		// Getting recent videoS

					case 'recent':
						$TypeSet   = $this->_settingsData->recent;									//  Recent Videos
						$rowF      = $this->_settingsData->rowsRec;								    //  get row of Recent videos
						$colF      = $this->_settingsData->colRec;
						$dataLimit = $rowF * $colF;
						$where = '';
						$thumImageorder = 'w.vid DESC';
						$TypeOFvideos   = $this->home_thumbdata( $thumImageorder, $where, $dataLimit );
						$CountOFVideos  = $this->countof_home_thumbdata( $thumImageorder, $where );
						$typename  = __( 'Recent', 'video_gallery' );
						$type_name = 'recent';
						$morePage  = 'recent';
						break;

					case 'featured':
						$thumImageorder = 'w.ordering ASC';
						$where   = 'AND w.featured=1';
						$TypeSet = $this->_settingsData->feature;									//  feature Videos
						$rowF = $this->_settingsData->rowsFea;										//  get row of feature videos
						$colF = $this->_settingsData->colFea;										//  get column of feature videos
						$dataLimit      = $rowF * $colF;
						if($recent_video_order =='id'){
							$thumImageorder = 'w.vid ASC';
						}elseif($recent_video_order == 'hitcount'){
							$thumImageorder = 'w.'.$recent_video_order .' DESC';
						}elseif ($recent_video_order == 'default') {
							$thumImageorder = 'w.ordering ASC';
						}else {
							$thumImageorder = 'w.ordering ASC';
						}
						$player_color = unserialize( $this->_settingsData->player_colors);
						$TypeOFvideos   = $this->home_thumbdata( $thumImageorder, $where, $dataLimit );
						$CountOFVideos  = $this->countof_home_thumbdata( $thumImageorder, $where );
						$typename  = __( 'Featured', 'video_gallery' );
						$type_name = 'featured';
						$morePage  = 'featured';
						break;

					case 'cat':
						if ( $this->_settingsData->homecategory == 1 ) {
							$rowF = $this->_settingsData->rowCat;									//  category Videos
							$colF = $this->_settingsData->colCat;									//  get row of category videos
							$category_page = $this->_settingsData->category_page;					//  get column of category videos
							$dataLimit     = $rowF * $colF;
							$player_color = unserialize( $this->_settingsData->player_colors);
						    $recent_video_order = $player_color['recentvideo_order'];								
							if($recent_video_order =='id'){
								$thumImageorder = 'w.vid ASC';
							}elseif($recent_video_order == 'hitcount'){
								$thumImageorder = 'w.'.$recent_video_order .' DESC';
							}elseif ($recent_video_order == 'default') {
							    $thumImageorder = 'w.ordering ASC';
						    }else {
								$thumImageorder = 'w.ordering ASC';
							}
							$TypeOFvideos  = $this->home_categoriesthumbdata( $this->_pagenum, $category_page );
							$CountOFVideos = $this->countof_videocategories();
							$typename = __( 'Video Categories', 'video_gallery' );
							return $this->categorylist( $CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit, $category_page );
						}
						break;
				}

				$class = $div = '';
				$ratearray    = array( 'nopos1', 'onepos1', 'twopos1', 'threepos1', 'fourpos1', 'fivepos1' );
				$image_path   = str_replace( 'plugins/' . $this->_plugin_name . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
				if ( $TypeSet ) {																		//  CHECKING FAETURED VIDEOS ENABLE STARTS
					$div  = '<div class="video_wrapper" id="' . $type_name . '_video">';
					$div .= '<style type="text/css" scoped> .video-block {margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;}  </style>';

					if ( ! empty( $TypeOFvideos ) ) {
						$div .= '<h2 class="video_header">' . $typename . ' ' . __( 'Videos', 'video_gallery' ) . '</h2>';
						$videolist    = 0;
						foreach ( $TypeOFvideos as $video ) {
							$duration[$videolist]    = $video->duration;									//  VIDEO DURATION
							$imageFea[$videolist]    = $video->image;										//  VIDEO IMAGE
							$file_type       = $video->file_type;									//  Video Type
							$playlist_id[$videolist]   = $video->pid;										//  VIDEO CATEGORY ID
							$fetched[$videolist]     = $video->playlist_name;								//  CATEOGORY NAME
							$fetched_pslug[$videolist] = $video->playlist_slugname;							//  CATEOGORY slug NAME
							$guid[$videolist]        = get_video_permalink( $video->slug );					//  guid
							if ( $imageFea[$videolist] == '' ) {												//  If there is no thumb image for video
								$imageFea[$videolist] = $this->_imagePath . 'nothumbimage.jpg';
							} else {
								if ( $file_type == 2 ||  $file_type == 5 ) {	
									if( strpos($imageFea[$videolist] , '/') ) {
										$imageFea[$videolist] = $imageFea[$videolist];
									}else{
										$imageFea[$videolist] = $image_path . $imageFea[$videolist];
									}
								} 
								elseif($file_type == 3) {
									$imageFea[$videolist] = $imageFea[$videolist];
								}
							}
							$vidF[$videolist]      = $video->vid;											//  VIDEO ID
							$nameF[$videolist]     = $video->name;											//  VIDEI NAME
							$hitcount[$videolist]  = $video->hitcount;										//  VIDEO HITCOUNT
							$ratecount[$videolist] = $video->ratecount;										//  VIDEO RATECOUNT
							$rate[$videolist]      = $video->rate;											//  VIDEO RATE
							$videolist++;
						}

						$div .= '<div class="video_thumb_content">';
						$div .= '<ul class="video-block-container">';
						for ( $videolist = 0; $videolist < count( $TypeOFvideos ); $videolist++ ) {
							$class = '<div class="clear"></div>';
							if ( ( $videolist % $colF ) == 0 && $videolist != 0 ) {										// COLUMN COUNT
								$div .= '</ul><div class="clear"></div><ul class="video-block-container">';
							}
							$div .= '<li class="video-block">';
							$div .= '<div  class="video-thumbimg"><a href="' . $guid[$videolist] . '"><img src="' . $imageFea[$videolist] . '" alt="' . $nameF[$videolist] . '" class="imgHome" title="' . $nameF[$videolist] . '" /></a>';
							if ( $duration[$videolist] != '0:00' ) {
								$div .= '<span class="video_duration">' . $duration[$videolist] . '</span>';
							}
							$div .= '</div>';
							$div .= '<div class="vid_info"><a title="'.$nameF[$videolist].'" href="' . $guid[$videolist] . '" class="videoHname"><span>';
							if ( strlen( $nameF[$videolist] ) > 30 ) {
								$div .= substr( $nameF[$videolist], 0, 30 ) . '..';
							} else {
								$div .= $nameF[$videolist];
							}
							$div .= '</span></a>';
							$div .= '';
							if ( $fetched[$videolist] != '' &&  ( $this->_settingsData->categorydisplay == 1 ) ) {
								$playlist_url = get_playlist_permalink( $this->_mPageid, $playlist_id[$videolist], $fetched_pslug[$videolist] );
								$div .= '<a class="playlistName"  href="' . $playlist_url . '"><span>' . $fetched[$videolist] . '</span></a>';
							}
							if ( $this->_settingsData->ratingscontrol == 1 ) {
								if ( isset( $ratecount[$videolist] ) && $ratecount[$videolist] != 0 ) {
									$ratestar = round( $rate[$videolist] / $ratecount[$videolist] );
								} else {
									$ratestar = 0;
								}
								$div .= '<span class="ratethis1 ' . $ratearray[$ratestar] . '"></span>';
							}
							if ( $this->_settingsData->view_visible == 1 ) {
								if ( $hitcount[$videolist] > 1 )
									$viewlang = $this->_viewslang;
								else
									$viewlang = $this->_viewlang;
								$div .= '<span class="video_views">' . $hitcount[$videolist] . '&nbsp;' . $viewlang;
								$div .= '</span>';
							}
							$div .= '</div>';
							$div .= '</li>';
						}	   // FOR EACH ENDS
						$div .= '</ul>';
						$div .= '</div>';
						$div .= '<div class="clear"></div>';


						if ( ( $dataLimit < $CountOFVideos ) ) {								// PAGINATION STARTS
							$more_videos_link = get_morepage_permalink( $this->_mPageid, $morePage );
							$div .= '<span class="more_title" ><a class="video-more" href="' . $more_videos_link . '">' . __( 'More&nbsp;Videos', 'video_gallery' ) . '&nbsp;&#187;</a></span>';
							$div .= '<div class="clear"></div>';
						} else if ( ( $dataLimit == $CountOFVideos ) ) {
							$div .= '<div style="float:right"></div>';
						}																	// PAGINATION ENDS
					}
					else
						$div .= __( 'No', 'video_gallery' ) . ' ' . $typename . ' ' . __( 'Videos', 'video_gallery' );
					$div  .= '</div>';
				}																			// CHECKING FAETURED VIDEOS ENABLE ENDS
				return $div;
			}
		}
                /**
                 * Function  for get the video from  category based.
                 * @global type $wpdb
                 * @param type $CountOFVideos
                 * @param type $TypeOFvideos
                 * @param type $pagenum
                 * @param type $dataLimit
                 * @param type $category_page
                 * @return $category_videos
                 */                        
		function categorylist( $CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit, $category_page ) {
			global $wpdb;
			$div = '';
			$ratearray = array( 'nopos1', 'onepos1', 'twopos1', 'threepos1', 'fourpos1', 'fivepos1' );
			$pagenum   = isset( $pagenum ) ? absint( $pagenum ) : 1;							//  Calculating page number
			$div      .= '<style scoped> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;} </style>';
			foreach ( $TypeOFvideos as $catList ) {
				$sql = 'SELECT s.guid,w.* FROM ' . $wpdb->prefix . 'hdflvvideoshare as w
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m ON m.media_id = w.vid
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid
					LEFT JOIN ' . $this->_wpdb->prefix . 'posts s ON s.ID=w.slug
					WHERE w.publish=1 and p.is_publish=1 and m.playlist_id=' . intval( $catList->pid ) . ' GROUP BY w.vid LIMIT ' . $dataLimit;
				$playLists     = $wpdb->get_results( $sql );
				$playlistCount = count( $playLists );
                                //Get count video assign this category. 
                $category_video = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play as m 
					LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE m.playlist_id='.intval($catList->pid).' AND p.is_publish=1');
                $video_count = count($category_video);
                                // end of  get count
                                
				$div .= '<div class="titleouter"> <h4 class="more_title">' . $catList->playlist_name . '</h4></div>';
				
				if ( ! empty( $playlistCount ) ) {
					$inc = 1;
					$image_path = str_replace( 'plugins/' . $this->_plugin_name . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
					$div        .= '<ul class="video-block-container">';
					foreach ( $playLists as $playList ) {

						$duration  = $playList->duration;
						$imageFea  = $playList->image;		 //  VIDEO IMAGE
						$file_type = $playList->file_type;	 //  Video Type
						$guid = get_video_permalink( $playList->slug );		  //  guid - url for video detail page
						if ( $imageFea == '' ) {				  //  If there is no thumb image for video
							$imageFea = $this->_imagePath . 'nothumbimage.jpg';
						} else {
							if ( $file_type == 2 || $file_type == 5 ) {	
								if( strpos( $imageFea, '/' ) ){
								   $imageFea = $imageFea;	
								}else{
								   $imageFea = $image_path . $imageFea;
								}
							}else if( $file_type == 3 ){
									$imageFea = $imageFea;										
							}
						}
						if ( strlen( $playList->name ) > 30 ) {
							$playListName = substr( $playList->name, 0, 30 ) . '..';
						} else {
							$playListName = $playList->name;
						}

						$div .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title=""></a>';
						if ( $duration != '0:00' ) {
							$div .= '<span class="video_duration">' . $duration . '</span>';
						}
						$div .= '</div><div class="vid_info"><a href="' . $guid . '" title="'.$playList->name.'" class="videoHname"><span>' . $playListName . '</span></a>';
                                                //Rating for  category video
                       if ( $this->_settingsData->ratingscontrol == 1 ) {
							if ( isset( $playList->ratecount ) && $playList->ratecount != 0 ) {
								$ratestar = round( $playList->rate / $playList->ratecount );
							} else {
								$ratestar = 0;
							}
							$div .= '<span class="ratethis1 ' . $ratearray[$ratestar] . '"></span>';
						}
						// Show views count for video
						if ( $this->_settingsData->view_visible == 1 ) {
							if ( $playList->hitcount > 1 )
								$viewlang = $this->_viewslang;
							else
								$viewlang = $this->_viewlang;

							$div .= '<span class="video_views">' . $playList->hitcount . '&nbsp;' . $viewlang . '</span>';
						}

						$div .= '</div></li>';

						if ( ( $inc % $this->_colCat  ) == 0 && $inc != 0 ) {						// COLUMN COUNT
							$div .= '</ul><div class="clear"></div><ul class="video-block-container">';
						}
						$inc++;
					}
					$div .= '</ul>';
                    // Video category thumb based on gallery seeting number of rows,cols based 
                      $colF = $this->_settingsData->colCat;
                      $rowF = $this->_settingsData->rowCat;
                      $CatLimit = $colF * $rowF;
					if ( ( $video_count > $CatLimit) ) {
						$more_playlist_link = get_playlist_permalink( $this->_mPageid, $catList->pid, $catList->playlist_slugname );
						$div .= '<a class="video-more" href="' . $more_playlist_link . '">' . __( 'More&nbsp;Videos', 'video_gallery' ) . '</a>';
					} else {
						$div .= '<div align="clear"> </div>';
					}
				} else {																		//  If there is no video for category
					$div .= '<div class="titleouter">' . __( 'No Videos for this Category', 'video_gallery' ) . '</div>';
				}
			}

			$div .= '<div class="clear"></div>';

			if ( $category_page != 0 ) {
																								// PAGINATION STARTS
				$total = $CountOFVideos;
				$num_of_pages = ceil( $total / $category_page );
				$page_links   = paginate_links(
						array(
							'base' => add_query_arg( 'pagenum', '%#%' ),
							'format' => '',
							'prev_text' => __( '&laquo;', 'aag' ),
							'next_text' => __( '&raquo;', 'aag' ),
							'total' => $num_of_pages,
							'current' => $pagenum,
							)
						);

				if ( $page_links ) {
					$div .= '<div class="contus_tablenav"><div class="contus_tablenav-pages" >' . $page_links . '</div></div>';
				}
																								// PAGINATION ENDS
			}
			return $div;
		}																						// CATEGORY FUNCTION ENDS
	}																							// class over
} else {
	echo 'class contusVideo already exists';
}
?>