<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress Video Gallery Video Search Widget.
  Version: 2.3
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

class widget_ContusVideoSearch_init extends WP_Widget {

    function widget_ContusVideoSearch_init() {
        $widget_ops             = array('classname' => 'widget_ContusVideoSearch_init ', 'description' => 'Displays Product tag link in product page');
        $this->WP_Widget('widget_ContusVideoSearch', 'Contus Video Search', $widget_ops);
    }

    function form($instance) {
        $instance               = wp_parse_args((array) $instance, array('title' => 'Video Search'));
        $title                  = esc_attr($instance['title']);
?>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>

<?php
    }

    function update($new_instance, $old_instance) {
        $instance               = $old_instance;
        $instance['title']      = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance) {
        ## and after_title are the array keys." - These are set up by the theme
        extract($args, EXTR_SKIP);

        $title                  = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        ## if (!empty($title))
        ## WIDGET CODE GOES HERE
        $tt                     = 1;
        global $wpdb, $wp_version, $popular_posts_current_ID;
        ## These are our own options
        $options                = get_option('widget_ContusVideoSearch');
        $show                   = $options['show'];  ## # of Posts we are showing
        $excerpt                = $options['excerpt'];  ## Showing the excerpt or not
        $exclude                = $options['exclude'];  ## Search to exclude
        $site_url               = get_bloginfo('url');
        $dir                    = dirname(plugin_basename(__FILE__));
        $dirExp                 = explode('/', $dir);
        $dirPage                = $dirExp[0];
?>

        <!-- For Getting The Page Id More and Video-->
<?php
        $homePageID             = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videohome]' and post_status='publish' and post_type='page' limit 1");
        $moreName               = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
        $styleSheet             = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        $site_url               = get_bloginfo('url');
        ## Video Search
        $searchVal              = __('Video Search ...', 'video_gallery');
        echo $before_widget;
        $focusVal               = 'onfocus="if(this.value == \'' . $searchVal . '\')this.value= \'\' "';
        $blurVal                = ' onblur="if(this.value == \'\')this.value= \'' . $searchVal . '\' "';
        $div                    = '<div id="videos-search"  class="sidebar-wrap ">
                                <h3 class="widget-title">' . $title . '</h3>';
        $div                    .= '<form role="search" method="POST" id="videosearchform" action="' . home_url('/') . '?page_id=' . $moreName . '" >
                                <div><label class="screen-reader-text" >' . __('Search for:') . '</label>
                                <input type="text" value="' . $searchVal . '" ' . $focusVal . $blurVal . ' name="video_search" id="video_search"  />
                                <input type="submit" id="videosearchsubmit" value="' . __('Search', 'video_gallery') . '" />
                                </div>
                                </form>';
        $div                    .='</div>';
        echo $div;
        ## echo widget closing tag
        echo $after_widget;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("widget_ContusVideoSearch_init");')); ##adding product tag widget
?>