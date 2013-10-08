<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress video gallery Recent videos widget.
  Version: 2.3.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
class widget_ContusRecentVideos_init extends WP_Widget {

    function widget_ContusRecentVideos_init() {
        $widget_ops             = array('classname' => 'widget_ContusRecentVideos_init ', 'description' => 'Contus Recent Videos');
        $this->WP_Widget('widget_ContusRecentVideos_init', 'Contus Recent Videos', $widget_ops);
    }

    function form($instance) {
        $instance               = wp_parse_args((array) $instance, array('title' => 'Recent Videos', 'show' => '3',));
        ## These are our own options
        $options                = get_option('widget_ContusVideoCategory');
        $title                  = esc_attr($instance['title']);
        $show                   = esc_attr($instance['show']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('show'); ?>">Show: <input class="widefat" id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" type="text" value="<?php echo $show; ?>" /></label></p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance               = $old_instance;
        $instance['title']      = $new_instance['title'];
        $instance['show']       = $new_instance['show'];
        return $instance;
    }

    function widget($args, $instance) {
        ## and after_title are the array keys." - These are set up by the theme
        extract($args, EXTR_SKIP);

        $title                  = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        if (!empty($title))
        ## echo $before_title .  $after_title;
        ## WIDGET CODE GOES HERE
            $tt                 = 1;
        global $wpdb, $wp_version, $popular_posts_current_ID;
        ## These are our own options
        $options                = get_option('widget_ContusRecentVideos');
        //$title                  = $instance['title'];  ## Title in sidebar for widget
        $show                   = $instance['show'];  ## # of Posts we are showing
        $excerpt                = $options['excerpt'];  ## Showing the excerpt or not
        $exclude                = $options['exclude'];  ## Categories to exclude
        $site_url               = get_bloginfo('url');
        $dir                    = dirname(plugin_basename(__FILE__));
        $dirExp                 = explode('/', $dir);
        $dirPage                = $dirExp[0];
        ?>

<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/js/script.js"></script>

<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>'
</script>
<!-- For Getting The Page Id More and Video-->
<?php
        $moreName               = $wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content='[videomore]' AND post_status='publish' AND post_type='page' LIMIT 1");
        $ratingscontrol         = $wpdb->get_var("SELECT ratingscontrol FROM " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
?>

        <!-- Recent videos -->

<?php
        echo $before_widget;
        $fetched                = '';
        $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
        $viewslang              = __('Views', 'video_gallery');
        $viewlang               = __('View', 'video_gallery');
        $div                    = '<div id="recent-videos" class="sidebar-wrap ">
                                   <h3 class="widget-title"><a href="' . $site_url . '/?page_id=' . $moreName . '&amp;more=rec">' . $title . '</a></h3>';
        $show                   = $instance['show'];
        $sql                    = "SELECT distinct a.*,s.guid,b.playlist_id,p.playlist_name from " . $wpdb->prefix . "hdflvvideoshare a
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id
                                INNER JOIN " . $wpdb->prefix . "posts s ON s.ID=a.slug
                                WHERE a.publish='1' AND p.is_publish='1' GROUP BY a.vid ORDER BY a.vid DESC LIMIT " . $show;
        $posts                  = $wpdb->get_results($sql);
        if (!empty($posts)) {
            $playlist_id        = $posts[0]->playlist_id;
            $fetched = $posts[0]->playlist_name;
        }
        $moreR                  = $wpdb->get_results("select count(a.vid) as contus from " . $wpdb->prefix . "hdflvvideoshare a
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id WHERE a.publish='1' AND p.is_publish='1' ORDER BY a.vid DESC");
        $countR                 = $moreR[0]->contus;
        $div                    .= '<ul class="ulwidget">';
        ## were there any posts found?
        if (!empty($posts)) {
        ## posts were found, loop through them
            $image_path         = str_replace('plugins/'.$dirPage.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
            $_imagePath         = APPTHA_VGALLERY_BASEURL . 'images' . DS;
            foreach ($posts as $post) {
                $file_type      = $post->file_type; ## Video Type
                $image          = $post->image;
                $guid           = $post->guid; ##guid
                if ($image == '') {  ##If there is no thumb image for video
                    $image      = $_imagePath . 'nothumbimage.jpg';
                } else {
                    if ($file_type == 2 || $file_type == 5 ) {          ##For uploaded image
                        $image  = $image_path . $image;
                    }
                }
                $vid            = $post->vid;
                $name           = strlen($post->name);
                ##output to screen
                $div            .= '<li class="clearfix sideThumb">';
                $div            .= '<div class="imgBorder"><a href="' . $guid . '"><img src="' . $image . '" alt="' . $post->name . '" class="img" width="120" height="80" style="width: 120px; height: 80px;" /></a>';
                if ($post->duration != 0.00) {
                    $div        .= '<span class="video_duration">' . $post->duration . '</span>';
                }
                $div            .= '</div>';

                $div            .= '<div class="side_video_info"><a class="videoHname" href="' . $guid . '">';
                if ($name > 25) {
                    $div        .= substr($post->name, 0, 25) . '..';
                } else {
                    $div        .= $post->name;
                }
                $div            .= '</a><div class="clear"></div>';
                if ($post->hitcount > 1)
                    $viewlanguage = $viewslang;
                else
                    $viewlanguage = $viewlang;
                $div             .= '<span class="views">' . $post->hitcount . ' ' . $viewlanguage;
                $div             .= '</span>';
                ## Rating starts here
                if ($ratingscontrol == 1) {
                        if (isset($post->ratecount) && $post->ratecount != 0) {
                            $ratestar    = round($post->rate / $post->ratecount);
                        } else {
                            $ratestar    = 0;
                        }
                        $div             .= '<span class="ratethis1 '.$ratearray[$ratestar].'"></span>';
                    }
                ## Rating ends here
                $div             .= '<div class="clear"></div>';
                $div             .= '</div>';
                $div             .= '</li>';
                
            }
        } else
            $div                 .= "<li>" . __('No recent Videos', 'video_gallery') . "</li>";
        ## end list
        if (($show < $countR) || ($show == $countR)) {
            $div                 .= '<li><div class="right video-more"><a href="' . $site_url . '/?page_id=' . $moreName . '&amp;more=rec">' . __('More Videos', 'video_gallery') . ' &#187;</a></div>';
            $div                 .= '<div class="clear"></div></li>';
        }
        $div                     .='</ul></div>';
        echo $div;
        ## echo widget closing tag
        echo $after_widget;
    }

    ## Register widget for use
}

## Run code and init
add_action('widgets_init', create_function('', 'return register_widget("widget_ContusRecentVideos_init");'));
?>