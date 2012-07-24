<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Video Gallery Search
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */
//Video Gallery Search
 //Video Search widget with the standard system of wordpress.
 
 //..................................................... FRONTEND ....................................................

  class widget_ContusVideoSearch_init extends WP_Widget {

function widget_ContusVideoSearch_init() {
	    $widget_ops = array('classname' => 'widget_ContusVideoSearch_init ', 'description' => 'Displays Product tag link in product page');
            $this->WP_Widget('widget_ContusVideoSearch', 'Contus Video Search', $widget_ops);
}
  function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => 'Video Search'));
            $title = $instance['title'];
    ?>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
    <?php
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            return $instance;
        }
	function widget($args,$instance) {
		// and after_title are the array keys." - These are set up by the theme
      extract($args, EXTR_SKIP);
           
            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            if (!empty($title))
             //   echo $before_title . $after_title;
            // WIDGET CODE GOES HERE
            $tt = 1;
		global $wpdb, $wp_version, $popular_posts_current_ID;
		// These are our own options
		$options = get_option('widget_ContusVideoSearch');
		$title = $options['title'];  // Title in sidebar for widget
		$show = $options['show'];  // # of Posts we are showing
		$excerpt = $options['excerpt'];  // Showing the excerpt or not
		$exclude = $options['exclude'];  // Search to exclude
		$site_url = get_bloginfo('url');
		$dir = dirname(plugin_basename(__FILE__));
		$dirExp = explode('/', $dir);
		$dirPage = $dirExp[0];
		?>

<!-- For Getting The Page Id More and Video-->
		<?php
		$homePageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videohome]' and post_status='publish' and post_type='page' limit 1");
		$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
		$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
		$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
		$site_url = get_bloginfo('url');
		?>
<!-- Getting our contus style -->
		<?php
		if ($styleSheet == 'contus') {
			?>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/css/contusStyle.css" />
			<?php } ?>

<!-- For Featured Videos -->
			<?php
			//  $searchVal = (!empty($_REQUEST['video_search']))?$_REQUEST['video_search']:'video search ...';
			$searchVal = 'video search ...';
			echo $before_widget;
			$focusVal = 'onfocus="if(this.value == \''.$searchVal.'\')this.value= \'\' "';
			$blurVal = 'onblur="if(this.value == \'\')this.value= \''.$searchVal.'\' "';
			$div = '<div id="featured-videos"  class="sidebar-wrap clearfix">';
			$div .= '<form role="search" method="POST" id="searchform" action="' . home_url( '/') .'?page_id='.$homePageID .'" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . $searchVal . '" '.$focusVal.$blurVal.' name="video_search" id="video_search"  />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</div>
	</form>';
			$div .='</div>';
			echo $div;
			// echo widget closing tag
			echo $after_widget;
	}
  }

add_action('widgets_init', create_function('', 'return register_widget("widget_ContusVideoSearch_init");'));//adding product tag widget

?>