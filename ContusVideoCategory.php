<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Video Gallery Categories
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */

//  Video Gallery Categories
 //   Video Categories widget with the standard system of wordpress.


class widget_ContusVideoCategory_init extends WP_Widget {
function widget_ContusVideoCategory_init() {
  $widget_ops = array('classname' => 'widget_ContusVideoCategory_init ', 'description' => 'Contus Video Categories');
     $this->WP_Widget('widget_ContusVideoCategory_init', 'Contus Video Category', $widget_ops);
}
  function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => 'Video Categories','show' => '3',));
            global $wpdb, $wp_version, $popular_posts_current_ID;
           // These are our own options
            $options = get_option('widget_ContusVideoCategory');
            $title = $instance['title'];
             $show = $instance['show'];
    ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('show'); ?>">Show: <input class="widefat" id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" type="text" value="<?php echo  attribute_escape($show);  ?>" /></label></p>
    <?php
     }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
             $instance['show'] = $new_instance['show'];
            return $instance;
        }
	function widget($args,$instance) {
        // and after_title are the array keys." - These are set up by the theme
      extract($args, EXTR_SKIP);
            
            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            if (!empty($title))
             //   echo $before_title .  $after_title;
            // WIDGET CODE GOES HERE
            $tt = 1;
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusVideoCategory');
        $title =$instance['title'];  // Title in sidebar for widget
        $show = $instance['show']; // # of Posts we are showing
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
?>
        <!-- Recent videos -->
        <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/js/script.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/css/style.css" />
        <script type="text/javascript">
            var baseurl;
            baseurl = '<?php echo $site_url; ?>';
            folder  = '<?php echo $dirPage; ?>'
        </script>
        <!-- For Getting The Page Id More and Video-->
<?php
        $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
        $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
        $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        $site_url = get_bloginfo('url');
?>
        <!-- Getting our contus style -->
<?php
        if ($styleSheet == 'contus') {
 ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/css/contusStyle.css" />
<?php } ?>

        <!-- For Featured Videos -->
<?php
        echo $before_widget;
      
        $sql = "select * from " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE 1 LIMIT " . $show;
        $features = $wpdb->get_results($sql);
        $moreCategories = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE 1");
        $countCategories = $moreCategories[0]->contus;
        $div .= '<div id="featured-videos"  class="sidebar-wrap clearfix">

                    <h3 class="widget-title"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=categories">'.$title.'</a></h3>
                 ';

        $div .='<ul class="ulwidget clearfix">';
        // were there any posts found?
        if (!empty($features)) {
            // posts were found, loop through them
            foreach ($features as $feature) {
                $fetched = $feature->playlist_name;
                $playlist_id = $feature->pid;
                $div .='<li>';

                $div .='<div class="clear"></div><a class="playlistName"  href="' . $site_url .'?page_id='.$moreName.'&playid=' . $playlist_id . '">'.$fetched.'</a>';

                $div .='</li>';
            }
        } else {
            $div .="<li>No Categories</li>";
        }
// end list
        if (($show < $countCategories)) {
            $div .='<li  class="right"><a href="' . $site_url . '/?page_id=' . $moreName . '&more=categories">More Categories</a></li>';
        } else {
            $div .='<li align="right"></li>';
        }
        $div .='</ul></div>';

// Settings form

// Get options
        $options = get_option('widget_ContusVideoCategory');
// options exist? if not set defaults
      echo $div;
// echo widget closing tag
        echo $after_widget;
}

}
add_action('widgets_init', create_function('', 'return register_widget("widget_ContusVideoCategory_init");'));//adding product tag widget

?>