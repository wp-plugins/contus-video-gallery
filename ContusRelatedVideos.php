<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Wordpress video gallery Related videos widget.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

class widget_ContusRelatedVideos_init extends WP_Widget {

    function widget_ContusRelatedVideos_init() {
        $widget_ops = array('classname' => 'widget_ContusRelatedVideos_init ', 'description' => 'Contus Related Videos');
        $this->WP_Widget('widget_ContusRelatedVideos_init', 'Contus Related Videos', $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => 'Related Videos', 'show' => '3',));
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusVideoCategory');
        $title = esc_attr($instance['title']);
        $show = esc_attr($instance['show']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('show'); ?>">Show: <input class="widefat" id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" type="text" value="<?php echo $show; ?>" /></label></p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['show'] = $new_instance['show'];
        return $instance;
    }

    function widget($args, $instance) {
        // and after_title are the array keys." - These are set up by the theme
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        if (!empty($title))
        //  echo $before_title .  $after_title;
        // WIDGET CODE GOES HERE
            $tt = 1;
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusRelatedVideos');
        $title = $instance['title'];  // Title in sidebar for widget
        $show = $instance['show'];  // # of Posts we are showing
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
        $countF = '';
        ?>
<!-- Recent videos -->
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/js/script.js"></script>

<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>'
</script>
<!-- For Getting The Page Id More and Video-->
<?php
        $videoID = $this->url_to_custompostid(get_permalink());
if(isset($_GET['p']))
        $videoID = intval($_GET['p']);

        if (!empty($videoID)) {
            $videoID = $wpdb->get_var("select vid from " . $wpdb->prefix . "hdflvvideoshare WHERE slug='$videoID'");
            $video_playlist_id = $wpdb->get_var("select playlist_id from " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='$videoID'");
            $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
            $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
            $site_url = get_bloginfo('url');
?>
            <!-- Getting our contus style -->
<?php
            echo $before_widget;
            $div = '<div id="related-videos"  class="sidebar-wrap clearfix">
                    <h3 class="widget-title">' . __('Related Videos', 'video_gallery') . '</h3>';
            $show = $instance['show'];

            $sql = "SELECT distinct a.*,s.guid,b.playlist_id,p.playlist_name
                    FROM " . $wpdb->prefix . "hdflvvideoshare a
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=b.playlist_id
                    INNER JOIN " . $wpdb->prefix . "posts s ON s.ID=a.slug
                    WHERE b.playlist_id=" . $video_playlist_id . " AND a.vid != " . $videoID . " and a.publish='1' AND p.is_publish='1' GROUP BY a.vid ORDER BY a.vid DESC LIMIT " . $show;

            $relatedVideos = $wpdb->get_results($sql);
            $playlistID = $relatedVideos[0]->playlist_id;
            $fetched = $relatedVideos[0]->playlist_name;
            $moreF = $wpdb->get_results("select count(a.vid) as relatedcontus from " . $wpdb->prefix . "hdflvvideoshare a INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id WHERE b.playlist_id=" . $playlistID . " ORDER BY a.vid DESC");
            $countF = $moreF[0]->relatedcontus;
            $div .='<ul class="ulwidget">';
            // were there any posts found?
            if (!empty($relatedVideos)) {
                // posts were found, loop through them
                $image_path = str_replace('plugins/video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                $_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
                foreach ($relatedVideos as $feature) {
                    $file_type = $feature->file_type; // Video Type
                    $imageFea = $feature->image;
                    $guid = $feature->guid; //guid
                    if ($imageFea == '') {  //If there is no thumb image for video
                        $imageFea = $_imagePath . 'nothumbimage.jpg';
                    } else {
                        if ($file_type == 2) {          //For uploaded image
                            $imageFea = $image_path . $imageFea;
                        }
                    }
                    $vidF = $feature->vid;
                    $name = strlen($feature->name);
                    //output to screen
                    $div .='<li class="clearfix sideThumb">';
                    $div .='<div class="imgBorder">
                         <a href="' . $guid . '">
                   <img src="' . $imageFea . '" alt="' . $feature->name . '"  class="img" />
                    </a>';
                    if ($feature->duration != 0.00) {
                        $div .= '<span class="video_duration">' . $feature->duration . '</span>';
                    }
                    $div .='</div>';
                    $div .='<div class="side_video_info"><h6><a href="' . $guid . '">';
                    if ($name > 25) {
                        $div .= substr($feature->name, 0, 25) . '';
                    } else {
                        $div .= $feature->name;
                    }
                    $div .='</a></h6><div class="clear"></div>';
                    $div .='<span class="views">' . $feature->hitcount . ' '.__('Views', 'video_gallery');
                    $div .= '</span>';
                    $div .='</span>';
                    $div .='<div class="clear"></div>';
                    $div .='</div>';
                    $div .='</li>';
                    $div .='<div class="clear"></div>';
                }
            }
            else {
            $div = '<div id="related-videos"  class="sidebar-wrap clearfix">
<h3 class="widget-title">' . $title . '</h3>';
            $div .="<li>".__('No Related videos', 'video_gallery')."</li>";
        }
        } 
        if (($show < $countF) || ($show == $countF)) {
            $div .='<div class="right video-more"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlistID . '">'.__('More videos', 'video_gallery').' &#187;</a></div>';
            $div .='<div class="clear"></div>';
        }
        $div .='<div class="clear"></div>';
        echo $div;

        //echo widget closing tag
        echo $after_widget;
    }

    function url_to_custompostid($url) {
        global $wp_rewrite;

        $url = apply_filters('url_to_postid', $url);

        // First, check to see if there is a 'p=N' or 'page_id=N' to match against
        if (preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values)) {
            $id = absint($values[2]);
            if ($id)
                return $id;
        }

        // Check to see if we are using rewrite rules
        $rewrite = $wp_rewrite->wp_rewrite_rules();

        // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
        if (empty($rewrite))
            return 0;

        // Get rid of the #anchor
        $url_split = explode('#', $url);
        $url = $url_split[0];

        // Get rid of URL ?query=string
        $url_split = explode('?', $url);
        $url = $url_split[0];

        // Add 'www.' if it is absent and should be there
        if (false !== strpos(home_url(), '://www.') && false === strpos($url, '://www.'))
            $url = str_replace('://', '://www.', $url);

        // Strip 'www.' if it is present and shouldn't be
        if (false === strpos(home_url(), '://www.'))
            $url = str_replace('://www.', '://', $url);

        // Strip 'index.php/' if we're not using path info permalinks
        if (!$wp_rewrite->using_index_permalinks())
            $url = str_replace('index.php/', '', $url);

        if (false !== strpos($url, home_url())) {
            // Chop off http://domain.com
            $url = str_replace(home_url(), '', $url);
        } else {
            // Chop off /path/to/blog
            $home_path = parse_url(home_url());
            $home_path = isset($home_path['path']) ? $home_path['path'] : '';
            $url = str_replace($home_path, '', $url);
        }

        // Trim leading and lagging slashes
        $url = trim($url, '/');

        $request = $url;

        // Look for matches.
        $request_match = $request;
        foreach ((array) $rewrite as $match => $query) {

            // If the requesting file is the anchor of the match, prepend it
            // to the path info.
            if (!empty($url) && ($url != $request) && (strpos($match, $url) === 0))
                $request_match = $url . '/' . $request;

            if (preg_match("!^$match!", $request_match, $matches)) {

                if ($wp_rewrite->use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
                    // this is a verbose page match, lets check to be sure about it
                    if (!get_page_by_path($matches[$varmatch[1]]))
                        continue;
                }

                // Got a match.
                // Trim the query of everything up to the '?'.
                $query = preg_replace("!^.+\?!", '', $query);

                // Substitute the substring matches into the query.
                $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));

                // Filter out non-public query vars
                global $wp;
                global $wpdb;
                parse_str($query, $query_vars);

                $query = array();
                foreach ((array) $query_vars as $key => $value) {

                    if (in_array($key, $wp->public_query_vars)) {
                        $query[$key] = $value;
                    }
                }
                // Do the query
                $moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_name='" . $query['videogallery'] . "' LIMIT 1");
                return $moreName;
            }
        }
        return 0;
    }

}

// Run code and init
add_action('widgets_init', create_function('', 'return register_widget("widget_ContusRelatedVideos_init");')); //adding product tag widget
?>