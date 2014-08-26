<?php
/**  
 * Video random video widget file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
class Widget_ContusRandomVideos_init extends WP_Widget {
	/**
	 * Widget intiation detail
	 */
	function Widget_ContusRandomVideos_init() {
		$widget_ops = array (
				'classname' => 'Widget_ContusRandomVideos_init ',
				'description' => 'Contus Random Videos' 
		);
		$this->WP_Widget ( 'Widget_ContusRandomVideos_init', 'Contus Random Videos', $widget_ops );
	}
	/**
	 * Admin fields for deatils
	 */
	function form($instance) {
		$instance = wp_parse_args ( ( array ) $instance, array (
				'title' => 'Random Videos',
				'show' => '3' 
		) );
		// These are our own options
		$title = esc_attr ( $instance ['title'] );
		$show =  isset( $instance['show'] ) ? absint( $instance['show'] ) : 3;
		?>
<p>
	<label for='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>'>Title:
		<input class='widefat'
		id='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>'
		name='<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>'
		type='text' value='<?php echo esc_html( $title ); ?>' />
	</label>
</p>
<p>
	<label for='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>'>Show:
		<input class='widefat'
		id='<?php echo esc_html( $this->get_field_id( 'show' ) ); ?>'
		name='<?php echo esc_html( $this->get_field_name( 'show' ) ); ?>'
		type='text' value='<?php echo esc_html( $show ); ?>' />
	</label>
</p>
<?php
	}
	/**
	 * Widget detail update
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		$instance ['show'] = (int)$new_instance ['show'];
		return $instance;
	}
	/**
	 * Front end widget show function
	 */
	function widget($args, $instance) {
		// and after_title are the array keys." - These are set up by the theme
		extract ( $args, EXTR_SKIP );
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );
		global $wpdb;
		// These are our own options
		$site_url = get_site_url ();
		$dir = dirname ( plugin_basename ( __FILE__ ) );
		$dirExp = explode ( '/', $dir );
		$dirPage = $dirExp [0];
		?>
<!-- For Getting The Page Id More and Video-->
<?php
		$moreName = $wpdb->get_var ( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" AND post_status="publish" AND post_type="page" LIMIT 1' );
		$settings_result = $wpdb->get_row ( 'SELECT ratingscontrol,view_visible FROM ' . $wpdb->prefix . 'hdflvvideoshare_settings WHERE settings_id=1' );
		$more_videos_link = get_morepage_permalink ( $moreName, 'random' );
		?>
<!-- Random videos -->
<?php
		$fetched = '';
		$ratearray = array (
				'nopos1',
				'onepos1',
				'twopos1',
				'threepos1',
				'fourpos1',
				'fivepos1' 
		);
		$viewslang = __ ( 'Views', 'video_gallery' );
		$viewlang = __ ( 'View', 'video_gallery' );
		echo $before_widget;
		$div = '<div id="random-videos" class="sidebar-wrap "><h3 class="widget-title"><a href="' . $more_videos_link . '">' . $title . '</a></h3>';
		//show no of  video  show
		if($instance['show']){
			if( absint( $instance['show'] ) ){
		    	$show = $instance ['show'];
			}else{
			    $show = 3;
			}
		}else{
				$show = 3;
		}
		$sql = 'SELECT DISTINCT a.*,s.guid,b.playlist_id,p.playlist_name FROM ' . $wpdb->prefix . 'hdflvvideoshare a
				INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play b ON a.vid=b.media_id
				INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=b.playlist_id
				INNER JOIN ' . $wpdb->prefix . 'posts s ON s.ID=a.slug
				WHERE a.publish=1 AND p.is_publish=1 GROUP BY a.vid ORDER BY RAND() LIMIT ' . $show;
		$posts = $wpdb->get_results ( $sql );
		if (! empty ( $posts )) {
			$fetched = $posts [0]->playlist_name;
		}
		$moreR = $wpdb->get_results ( 'SELECT count(a.vid) as contus from ' . $wpdb->prefix . 'hdflvvideoshare a
				INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_med2play b ON a.vid=b.media_id
				INNER JOIN ' . $wpdb->prefix . 'hdflvvideoshare_playlist p ON p.pid=b.playlist_id
				WHERE a.publish=1 AND p.is_publish=1 ORDER BY a.vid DESC' );
		$countR = $moreR [0]->contus;
		$div .= '<ul class="ulwidget">';
		// were there any posts found?
		if (! empty ( $posts )) {
			// posts were found, loop through them
			$image_path = str_replace ( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
			$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
			foreach ( $posts as $post ) {
				$file_type = $post->file_type; // Video Type
				$image = $post->image;
				$guid = get_video_permalink ( $post->slug ); // guid
				if ($image == '') { // If there is no thumb image for video
					$image = $_imagePath . 'nothumbimage.jpg';
				} else {
					if ($file_type == 2 || $file_type == 5) { // For uploaded image
                        if( $file_type ==  2  &&  strpos($image ,  '/'  )) {
                        	$image = $image;
                        }else{
                        	$image = $image_path . $image;
                        }
					}
					if( $file_type == 3 ){
						$image = $image;
					}
				}
				$name = strlen ( $post->name );
				// output to screen
				$div .= '<li class="clearfix sideThumb">';
				$div .= '<div class="imgBorder"><a href="' . $guid . '" title="'.$post->name.'"><img src="' . $image . '" alt="' . $post->name . '" class="img" width="120" height="80" style="width: 120px; height: 80px;" /></a>';
				if ($post->duration != 0.00) {
					$div .= '<span class="video_duration">' . $post->duration . '</span>';
				}
				$div .= '</div>';
				$div .= '<div class="side_video_info"><a title="'.$post->name.'" class="videoHname" href="' . $guid . '">';
				if ($name > 25) {
					$div .= substr ( $post->name, 0, 25 ) . '..';
				} else {
					$div .= $post->name;
				}
				$div .= '</a><div class="clear"></div>';
				if ($settings_result->view_visible == 1) {
					if ($post->hitcount > 1) {
						$viewlanguage = $viewslang;
					} else {
						$viewlanguage = $viewlang;
					}
					$div .= '<span class="views">' . $post->hitcount . ' ' . $viewlanguage;
					$div .= '</span>';
				}
				// Rating starts here
				if ($settings_result->ratingscontrol == 1) {
					if (isset ( $post->ratecount ) && $post->ratecount != 0) {
						$ratestar = round ( $post->rate / $post->ratecount );
					} else {
						$ratestar = 0;
					}
					$div .= '<span class="ratethis1 ' . $ratearray [$ratestar] . '"></span>';
				}
				// Rating ends here
				$div .= '<div class="clear"></div>';
				$div .= '</div>';
				$div .= '</li>';
			}
		} else {
			$div .= '<li>' . __ ( 'No Random Videos', 'video_gallery' ) . '</li>';
		}
		// end list
		if (($show < $countR) || ($show == $countR)) {
			$more_videos_link = get_morepage_permalink ( $moreName, 'random' );
			$div .= '<li><div class="right video-more"><a href="' . $more_videos_link . '">' . __ ( 'More&nbsp;Videos', 'video_gallery' ) . '&nbsp;&#187;</a></div>';
			$div .= '<div class="clear"></div></li>';
		}
		$div .= '</ul></div>';
		echo balanceTags ( $div );
		echo $after_widget;
	}
	// Register widget for use
}

// Run code and init
add_action ( 'widgets_init', create_function ( '', 'return register_widget("Widget_ContusRandomVideos_init");' ) );
?>