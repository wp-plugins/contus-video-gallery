<?php
/**
 *  Wordpress video gallery Featured videos widget.
 *  
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

class widget_ContusFeaturedVideos_init extends WP_Widget {
	function widget_ContusFeaturedVideos_init() {
		$widget_ops = array (
				'classname' => 'widget_ContusFeaturedVideos_init ',
				'description' => 'Contus Featured Videos' 
		);
		$this->WP_Widget ( 'widget_ContusFeaturedVideos_init', 'Contus Featured Videos', $widget_ops );
	}
	function form($instance) {
		$instance = wp_parse_args ( ( array ) $instance, array (
				'title' => 'Featured Videos',
				'show' => '3' 
		) );
		// These are our own options
		$options = get_option ( 'widget_ContusVideoCategory' );
		$title = esc_attr ( $instance ['title'] );
		$show =  isset( $instance['show'] ) ? absint( $instance['show'] ) : 3;
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title: <input
		class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo $title; ?>" /></label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('show'); ?>">Show: <input
		class="widefat" id="<?php echo $this->get_field_id('show'); ?>"
		name="<?php echo $this->get_field_name('show'); ?>" type="text"
		value="<?php echo $show; ?>" /></label>
</p>
<?php
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		$instance ['show'] = $new_instance ['show'];
		return $instance;
	}
	function widget($args, $instance) {
		// and after_title are the array keys." - These are set up by the theme
		extract ( $args, EXTR_SKIP );
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );
		if (! empty ( $title ))
			// WIDGET CODE GOES HERE
			$tt = 1;
		global $wpdb, $wp_version, $popular_posts_current_ID;
		// These are our own options
		$options = get_option ( 'widget_ContusFeaturedVideos' );
		// $title = $instance['title']; // Title in sidebar for widget
		
		if ($instance ['show']) {
			if( absint( $instance['show'] ) ){
				$show = $instance ['show'];
			}else{
				$show =3;	
			}
		} else {
			$show = 3;
		}
			
		$excerpt = $options ['excerpt']; // Showing the excerpt or not
		$exclude = $options ['exclude']; // Categories to exclude
		$site_url = get_site_url ();
		$dir = dirname ( plugin_basename ( __FILE__ ) );
		$dirExp = explode ( '/', $dir );
		$dirPage = $dirExp [0];
		?>
<!-- Recent videos -->
<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>'
</script>
<!-- For Getting The Page Id More and Video-->
<?php
		$moreName = $wpdb->get_var ( "SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content LIKE '%[videomore]%' AND post_status='publish' AND post_type='page' LIMIT 1" );
		$settings_result = $wpdb->get_row ( "SELECT ratingscontrol,view_visible FROM " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'" );
		$more_videos_link = get_morepage_permalink ( $moreName, 'featured' );
		?>
<!-- For Featured Videos -->
<?php
		echo $before_widget;
		$fetched = '';
		$ratearray = array (
				"nopos1",
				"onepos1",
				"twopos1",
				"threepos1",
				"fourpos1",
				"fivepos1" 
		);
		$viewslang = __ ( 'Views', 'video_gallery' );
		$viewlang = __ ( 'View', 'video_gallery' );
		$div = '<div id="featured-videos"  class="sidebar-wrap ">
                            <h3 class="widget-title"><a href="'.$more_videos_link.'">' . $title . '</a></h3>';		
	  	$sql = "SELECT DISTINCT a.*,s.guid,b.playlist_id,p.playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare a
                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id
                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id
                            INNER JOIN " . $wpdb->prefix . "posts s ON s.ID=a.slug
                            WHERE a.publish='1' AND p.is_publish='1' AND a.featured='1' GROUP BY a.vid ORDER BY a.ordering ASC  LIMIT " . $show;
		$features = $wpdb->get_results ( $sql );
		if (! empty ( $features )) {
			$playlist_id = $features [0]->playlist_id;
			$fetched = $features [0]->playlist_name;
		}
		$moreF = $wpdb->get_results ( "SELECT COUNT(a.vid) AS contus FROM " . $wpdb->prefix . "hdflvvideoshare a
                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id WHERE a.publish='1' AND p.is_publish='1' AND a.featured='1'" );
		$countF = $moreF [0]->contus;
		$div .= '<ul class="ulwidget">';
		
		// were there any posts found?
		if (! empty ( $features )) {
			// posts were found, loop through them
			$image_path = str_replace ( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
			$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
			
			foreach ( $features as $feature ) {
				$file_type = $feature->file_type; // Video Type
				$imageFea = $feature->image; // VIDEO IMAGE
				$guid = get_video_permalink ( $feature->slug ); // guid
				if ($imageFea == '') { // If there is no thumb image for video
					$imageFea = $_imagePath . 'nothumbimage.jpg';
				} else {
					if ($file_type == 2 || $file_type == 5) { // For uploaded image
						if( $file_type == 2 &&  strpos( $imageFea , '/' )){
							$imageFea = $imageFea;
					
						}else{
							$imageFea = $image_path . $imageFea;
						}
					}
					if( $file_type == 3 ){
						$imageFea = $imageFea;
					}
				}
				$vidF = $feature->vid;
				$name = strlen ( $feature->name );
				// output to screen
				$div .= '<li class="clearfix sideThumb">';
				$div .= '<div class="imgBorder"><a href="' . $guid . '" title="'.$feature->name.'"><img src="' . $imageFea . '" alt="' . $feature->name . '"  class="img" width="120" height="80" style="width: 120px; height: 80px;"  /></a>';
				if ($feature->duration != 0.00) {
					$div .= '<span class="video_duration">' . $feature->duration . '</span>';
				}
				$div .= '</div>';
				$div .= '<div class="side_video_info"><a title="'.$feature->name.'" class="videoHname" href="' . $guid . '">';
				if ($name > 25) {
					$div .= substr ( $feature->name, 0, 25 ) . '..';
				} else {
					$div .= $feature->name;
				}
				$div .= '</a>';
				$div .= '<div class="clear"></div>';
				if ($settings_result->view_visible == 1) {
					if ($feature->hitcount > 1) {
						$viewlanguage = $viewslang;
					} else {
						$viewlanguage = $viewlang;
					}
					$div .= '<span class="views">' . $feature->hitcount . ' ' . $viewlanguage . '</span>';
				}
				
				// Rating starts here
				if ($settings_result->ratingscontrol == 1) {
					if (isset ( $feature->ratecount ) && $feature->ratecount != 0) {
						$ratestar = round ( $feature->rate / $feature->ratecount );
					} else {
						$ratestar = 0;
					}
					$div .= '<span class="ratethis1 ' . $ratearray [$ratestar] . '"></span>';
				}
				// Rating ends here
				
				$div .= '<div class="clear"></div>';
				$div .= '<div class="clear"></div>';
				$div .= '</div>';
				$div .= '</li>';
			}
		} else
			$div .= "<li>" . __ ( 'No Featured Videos', 'video_gallery' ) . "</li>";
			// end list
		if (($show < $countF) || ($show == $countF)) {
			$div .= '<li><div class="video-more"><a href="' . $more_videos_link . '">' . __ ( 'More&nbsp;Videos', 'video_gallery' ) . '&nbsp;&#187;</a></div>';
			$div .= '<div class="clear"></div></li>';
		} else {
			$div .= '<li><div align="right"> </div></li>';
		}
		$div .= '</ul></div>';
		echo $div;
		// echo widget closing tag
		echo $after_widget;
	}
}

// Run code and init
add_action ( 'widgets_init', create_function ( '', 'return register_widget("widget_ContusFeaturedVideos_init");' ) ); // adding product tag widget
?>