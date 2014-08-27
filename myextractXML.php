<?php
/**  
 * Video player myextract xml file for video details and related  video settings.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
// Include config file
require_once( dirname( __FILE__ ) . '/hdflv-config.php' );
$pageOBJ = new ContusVideoView();								// include class from Videohome view
$contOBJ = new ContusVideoController();							// include class from Videohome controller
$getVid  = $pageOBJ->_vId;										// Get video ID from video home view
$getPid  = $pageOBJ->_pId;									// Get playlist ID from video home view
$numberofvideos = filter_input( INPUT_GET, 'numberofvideos' );	// Get number of videos from URL
if ( empty( $numberofvideos ) ) {
	$numberofvideos = 4;
}
$numberofvideos = $contOBJ->related_video_count();
$banner = 0;
$type   = filter_input( INPUT_GET, 'type' );
if ( ! empty( $numberofvideos ) && ! empty( $type ) ) {
	$banner = 1;
}
if ( ! empty( $type ) && $type == 1 ) {										// IF type = popular video
	$thumImageorder  = 'w.hitcount DESC';
	$where = '';
	$singleVideodata = $contOBJ->home_playxmldata( $getVid, $thumImageorder, $where, $numberofvideos );
} else if ( ! empty( $type ) && $type == 2 ) {								// IF type = recent video
	$thumImageorder  = 'w.vid DESC';
	$where = '';
	$singleVideodata = $contOBJ->home_playxmldata( $getVid, $thumImageorder, $where, $numberofvideos );
} else if ( ! empty( $type ) && $type == 3 ) {								// IF type = featured video
	$thumImageorder  = 'w.ordering ASC';
	$where = 'AND w.featured=1';
	$singleVideodata = $contOBJ->home_playxmldata( $getVid, $thumImageorder, $where, $numberofvideos );
} else if ( ! empty( $getVid ) ) {
	$singleVideodata = $contOBJ->video_detail( $getVid);					// Get detail for particular video
} else if ( ! empty( $getPid ) ) {
	$singleVideodata = $contOBJ->video_Pid_detail( $getPid, 'playxml',$numberofvideos);	// Get detail for particular playlist
} else {
	$singleVideodata = $pageOBJ->_featuredvideodata;						// Get detail of featured videos
}
$settingsContent = $pageOBJ->settings_data();
$tagsName = $pageOBJ->Tag_detail( $getVid );
$islive = $streamer = $videoPreview = $videotag = $postroll_id = $subtitle = '';
$pageOBJ->_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;	 // declare image path
// autoplay value
if ( $settingsContent->playlistauto == 1 ) {
	$ap = 'true';
} else {
	$ap = 'false';
}
$dir        = dirname( plugin_basename( __FILE__ ) );
$dirExp     = explode( '/', $dir );
$dirPage    = $dirExp[0];
$image_path = str_replace( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
// playlist XML starts here
header( 'content-type:text/xml;charset = utf-8' );
echo '<?xml version = "1.0" encoding = "utf-8"?>';
echo '<playlist autoplay = "'.$ap.'" random = "false">';
// Print all video details
foreach ( $singleVideodata as $media ) {
	$file_type = $media->file_type;
	$fbPath    = '';
	if ( $file_type != 5 ) {
		$videoUrl = $media->file;
		if ( ! empty( $media->duration ) && $media->duration != '0:00' ) {
			$duration = $media->duration;
		} else {
			$duration = '';
		}
		$views = $media->hitcount;
		if ( $banner != 1 ) {
			$fbPath = $media->guid;
		}
		$hdvideoUrl = $media->hdfile;
		$opimage    = $media->opimage;
		$image      = $media->image;
		$vidoeId    = $media->vid;
		// Get thumb image detail
		if ( $image == '' ) {
			$image = $pageOBJ->_imagePath . 'nothumbimage.jpg';
		} else {
			if ( $file_type == 2 ) {
				if( $file_type == 2 && strpos($image , '/' ) ){
					$image = $image;
				}else{
					$image = $image_path . $image;
				}
			}
		}
		// Get preview image detail
		if ( $opimage == '' ) {
			$opimage = $pageOBJ->_imagePath . 'noimage.jpg';
		} else {
			if ( $file_type == 2 ) {
				$opimage = $image_path . $opimage;
			}
		}
		// Get video url detail
		if ( $videoUrl != '' ) {

			if ( $file_type == 2 ) {
				if( $file_type == 2  && strpos($videoUrl , '/' ) ) { 
					$videoUrl = $videoUrl;
				} else {
					$videoUrl = $image_path . $videoUrl;
				}
				
			}
		}
		// Get HD video url detail
		if ( $hdvideoUrl != '' ) {

			if ( $file_type == 2 ) {
				if( $file_type == 2 && strpos( $hdvideoUrl, '/' ) ){
				   $hdvideoUrl = $hdvideoUrl;
				}else{
					$hdvideoUrl = $image_path . $hdvideoUrl;
				}
			}
		}
		// Get RTMP detail
		if ( $file_type == 4 ) {
			$streamer = $media->streamer_path;
			$islive   = ( $media->islive == 1 ) ? 'true' : 'false';
		}

		// Get subtitles
		$subtitle1 = $media->srtfile1;
		$subtitle2 = $media->srtfile2;
		if ( ! empty( $subtitle1 ) && ! empty( $subtitle2 ) ) {
			$subtitle = $image_path . $subtitle1. ',' . $image_path . $subtitle2 ;
		} else if ( ! empty( $subtitle1 ) ) {
			$subtitle = $image_path . $subtitle1;
		} else if ( ! empty( $subtitle2 ) ) {
			$subtitle = $image_path . $subtitle2;
		}

		// Get preroll ad detail
		if ( $settingsContent->preroll == 1 ) {
			$preroll    = ' allow_preroll = "false"';
			$preroll_id = ' preroll_id = "0"';
		} else {
			if ( $media->prerollads != 0 ) {
				$preroll    = ' allow_preroll = "true"';
				$preroll_id = ' preroll_id = "' . $media->prerollads . '"';
			} else {
				$preroll    = ' allow_preroll = "false"';
				$preroll_id = ' preroll_id = "0"';
			}
		}
		// Get midroll ad detail
		$midroll = ' allow_midroll = "false"';
		if ( $media->midrollads != 0 ) {
			$midroll = ' allow_midroll = "true"';
		}
		// Get ima ad detail
		$imaad = ' allow_ima = "false"';
		if ( $media->imaad != 0 ) {
			$imaad = ' allow_ima = "true"';
		}
		// Get postroll ad detail
		if ( $settingsContent->postroll == 1 ) {
			$postroll    = ' allow_postroll = "false"';
			$postroll_id = ' postroll_id = "0"';
		} else {
			if ( $media->postrollads != 0 ) {
				$postroll    = ' allow_postroll = "true"';
				$postroll_id = ' postroll_id = "' . $media->postrollads . '"';
			} else {
				$postroll    = ' allow_postroll = "false"';
				$postroll_id = ' postroll_id = "0"';
			}
		}
		// download allowed or not
		$individualdownload = $media->download;
		if ( ( ( ( isset( $individualdownload[0] ) && $individualdownload[0] == 1 ) || ( isset( $individualdownload ) && $individualdownload == 1  ) ) ) && $file_type != 3 ) {
			$download = 'true';
		} else {
			$download = 'false';
		}
		// Generate playlist XML
		echo '<mainvideo
			views="' . $views . '"
			subtitle ="' . $subtitle . '"
			duration="' . $duration . '"
			streamer_path="' . $streamer . '"
			video_isLive="' . $islive . '"
			video_id = "' . htmlspecialchars( $vidoeId ) . '"
			fbpath = "' . $fbPath . '"
			video_url = "' . htmlspecialchars( $videoUrl ) . '"
			thumb_image = "' . htmlspecialchars( $image ) . '"
			preview_image = "' . htmlspecialchars( $opimage ) . '"
			' . $midroll . '
			' . $imaad . '
			' . $postroll . '
			' . $preroll . '
			' . $postroll_id . '
			' . $preroll_id . '				
			Tag =  "' . $tagsName . '"
			allow_download = "' . $download . '"
			video_hdpath = "' . $hdvideoUrl . '"
			copylink = "">
			<title><![CDATA[' . strip_tags( $media->name ) . ']]></title>
			<tagline targeturl=""><![CDATA[' . strip_tags( $media->description ) . ']]></tagline>
			</mainvideo>';
	}
}
echo '</playlist>';
// XML end here
?>