<?php
/**
 * Wordpress video gallery Related videos widget.
 * 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8.1
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

class Widget_ContusRelatedVideos_init extends WP_Widget {

	function Widget_ContusRelatedVideos_init() {
		$widget_ops = array( 'classname' => 'Widget_ContusRelatedVideos_init ', 'description' => 'Contus Related Videos' );
		$this->WP_Widget( 'Widget_ContusRelatedVideos_init', 'Contus Related Videos', $widget_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Related Videos', 'show' => '3' ) );
		// These are our own options
		$title = esc_attr( $instance['title'] );
		$show =  isset( $instance['show'] ) ? absint( $instance['show'] ) : 3;
		?>
		<p><label for='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>'>Title: <input class='widefat' id='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>' name='<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>' type='text' value='<?php echo esc_html( $title ); ?>' /></label></p>
		<p><label for='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>'>Show: <input class='widefat' id='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>' name='<?php echo esc_html( $this->get_field_name( 'show' ) ); ?>' type='text' value='<?php echo esc_html( $show ); ?>' /></label></p>
				<?php
			}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show']  = (int)$new_instance['show'];
		return $instance;
	}

	function widget( $args, $instance ) {
		// and after_title are the array keys." - These are set up by the theme
		extract( $args, EXTR_SKIP );
		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
		$show = 3;
		global $wpdb;
		// These are our own options
		if($instance['show']){
           if( absint( $instance['show'] ) ){
				$show = $instance['show']; 
			}
		}

		$site_url = get_site_url();
		$dir      = dirname( plugin_basename( __FILE__ ) );
		$dirExp   = explode( '/', $dir );
		$dirPage  = $dirExp[0];
		$countF   = $div = '';
		?>
		<!-- For Getting The Page Id More and Video-->
		<?php
		global $post;
		echo $before_widget;
		$videoID = $post->ID;
		if ( isset( $_GET['p'] ) ) {
			$videoID = intval( $_GET['p'] );
		}
		$moreName = $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" AND post_status="publish" AND post_type="page" LIMIT 1' );
		if ( ! empty( $videoID ) ) {
			$videoID = $wpdb->get_var( 'SELECT vid FROM ' . $wpdb->prefix . 'hdflvvideoshare WHERE slug='.$videoID );
			if ( ! empty( $videoID ) ) {
				$video_playlist_id = $wpdb->get_var( 'SELECT playlist_id FROM ' . $wpdb->prefix . 'hdflvvideoshare_med2play WHERE media_id='.$videoID );
				$settings_result   = $wpdb->get_row( 'SELECT ratingscontrol,view_visible FROM ' . $wpdb->prefix . 'hdflvvideoshare_settings WHERE settings_id=1' );
				$site_url  = get_site_url();
				$ratearray = array( 'nopos1', 'onepos1', 'twopos1', 'threepos1', 'fourpos1', 'fivepos1' );
				$viewslang = __( 'Views', 'video_gallery' );
				$viewlang  = __( 'View', 'video_gallery' );

				$show = $instance['show'];

				$sql = 'SELECT distinct a.*,s.guid,b.playlist_id,p.playlist_name,p.playlist_slugname
						FROM ' . $wpdb->prefix . 'hdflvvideoshare a
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play b ON a.vid=b.media_id
						LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=b.playlist_id
						LEFT JOIN ' . $wpdb->prefix . 'posts s ON s.ID=a.slug
						WHERE b.playlist_id=' . $video_playlist_id . ' AND a.vid != ' . $videoID . ' and a.publish=1 AND p.is_publish=1 GROUP BY a.vid ORDER BY a.vid DESC LIMIT ' . $show;

				$relatedVideos = $wpdb->get_results( $sql );
				if ( ! empty( $relatedVideos ) ) {
					$playlistID = $relatedVideos[0]->playlist_id;
					$playlist_slugname = $relatedVideos[0]->playlist_slugname;
					$moreF = $wpdb->get_results( 'SELECT COUNT(a.vid) as relatedcontus from ' . $wpdb->prefix . 'hdflvvideoshare a LEFT JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play b ON a.vid=b.media_id WHERE b.playlist_id=' . $playlistID . ' ORDER BY a.vid DESC' );
					$countF = $moreF[0]->relatedcontus;
				}
			}
		}
		if ( ! empty( $video_playlist_id ) ) {
			$link = '<a href=' . $site_url . '/?page_id=' . $moreName . '&amp;playid=' . $video_playlist_id . '>' . $title . '</a>';
		} else {
			$link = $title;
		}
		$div .= '<div id="related-videos"  class="sidebar-wrap "><h3 class="widget-title">' . $link . '</h3>';
		$div .= '<ul class="ulwidget">';
		if ( ! empty( $videoID ) ) {
			// were there any posts found?
			if ( ! empty( $relatedVideos ) ) {
				// posts were found, loop through them
				$image_path = str_replace( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
				$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
				foreach ( $relatedVideos as $feature ) {
					$file_type = $feature->file_type; // Video Type
					$imageFea  = $feature->image;
					$guid = get_video_permalink( $feature->slug ); //guid
					if ( $imageFea == '' ) {  //If there is no thumb image for video
						$imageFea = $_imagePath . 'nothumbimage.jpg';
					} else {
						if ( $file_type == 2 || $file_type == 5 ) {		  //For uploaded image
							if( $file_type ==  2  &&  strpos($imageFea ,  '/'  )) {
	                        	$imageFea = $imageFea;
	                        }else{
	                        	$imageFea = $image_path . $imageFea;
	                        }
						} else if ( $file_type == 3 ) {
							    $imageFea  =   $imageFea;
						} 
					}
					$name = strlen( $feature->name );
					//output to screen
					$div .= '<li class="clearfix sideThumb">';
					$div .= '<div class="imgBorder"><a href="' . $guid . '" title="'.$feature->name.'" ><img src="' . $imageFea . '" alt="' . $feature->name . '"  class="img" style="width: 120px; height: 80px;" width="120" height="80"  /></a>';
					if ( $feature->duration != 0.00 ) {
						$div .= '<span class="video_duration">' . $feature->duration . '</span>';
					}
					$div .= '</div>';
					$div .= '<div class="side_video_info"><a class="videoHname" title="'.$feature->name.'" href="' . $guid . '">';
					if ( $name > 25 ) {
						$div .= substr( $feature->name, 0, 25 ) . '..';
					} else {
						$div .= $feature->name;
					}
					$div .= '</a><div class="clear"></div>';
					if ( $settings_result->view_visible == 1 ) {
						if ( $feature->hitcount > 1 ) {
							$viewlanguage = $viewslang;
						} else {
							$viewlanguage = $viewlang;
						}
						$div .= '<span class="views">' . $feature->hitcount . ' ' . $viewlanguage;
						$div .= '</span>';
					}
					// Rating starts here
					if ( $settings_result->ratingscontrol == 1 ) {
						if ( isset( $feature->ratecount) && $feature->ratecount != 0 ) {
							$ratestar = round( $feature->rate / $feature->ratecount );
						} else {
							$ratestar = 0;
						}
						$div .= '<span class="ratethis1 ' . $ratearray[$ratestar] . '"></span>';
					}
					// Rating ends here
					$div .= '</span>';
					$div .= '<div class="clear"></div>';
					$div .= '</div>';
					$div .= '</li>';
				}
			} else {
				$div .= '<li>' . __( 'No Related videos', 'video_gallery' ) . '</li>';
			}
		} else {
			$div .= '<li>' . __( 'No Related videos', 'video_gallery' ) . '</li>';
		}

		if ( ( $show < $countF ) || ( $show == $countF ) ) {
			$playlist_url = get_playlist_permalink( $moreName, $playlistID, $playlist_slugname );
			$div .= '<li><div class="right video-more"><a href="' . $playlist_url . '">' . __( 'More&nbsp;Videos', 'video_gallery' ) . '&nbsp;&#187;</a></div>';
			$div .= '<div class="clear"></div></li>';
		}
		$div .= '</ul></div><div class="clear"></div>';
		echo balanceTags( $div );
		echo $after_widget;
	}
}

// Run code and init
add_action( 'widgets_init', create_function( '', 'return register_widget("Widget_ContusRelatedVideos_init");' ) ); //adding product tag widget
?>