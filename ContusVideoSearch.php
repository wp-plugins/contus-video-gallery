<?php
/**
 * Wordpress Video Gallery Video Search Widget.
 * 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

class Widget_ContusVideoSearch_init extends WP_Widget {

	function Widget_ContusVideoSearch_init() {
		$widget_ops = array( 'classname' => 'Widget_ContusVideoSearch_init ', 'description' => 'Displays Product tag link in product page' );
		$this->WP_Widget( 'Widget_ContusVideoSearch', 'Contus Video Search', $widget_ops );
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Video Search' ) );
		$title = esc_attr( $instance['title'] );
		?>
		<p><label for='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>'>Title: <input class='widefat' id='<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>' name='<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>' type='text' value='<?php echo esc_html( $title ); ?>' /></label></p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
		global $wpdb;

		?>

		<?php
		$moreName = $wpdb->get_var( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%[videomore]%" AND post_status="publish" AND post_type="page" LIMIT 1' );

		echo $before_widget;
		$searchVal = __( 'Video Search &hellip;', 'video_gallery' );
		$div       = '<div id="videos-search"  class="widget_search sidebar-wrap search-form-container "><h3 class="widget-title">' . $title . '</h3>';
		$div      .= '<form role="search" method="get" id="search-form" class="search-form searchform clearfix" action="' . home_url( '/' ) . '" >
					<label class="screen-reader-text" >' . __( 'Search for:' ) . '</label>
					<input type="hidden" value="' . $moreName . '" name="page_id" id="page_id"  />
					<input type="text" class="s field search-field" placeholder="'.$searchVal.'" value="" name="video_search" id="s video_search"  />
					<input type="submit" class="search-submit submit" id="videosearchsubmit" value="' . __( 'Search', 'video_gallery' ) . '" />
					</form>';
		$div     .= '</div>';
		echo balanceTags( $div );
		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Widget_ContusVideoSearch_init" );' ) ); 
?>