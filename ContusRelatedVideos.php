<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Video Gallery Related Videos
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */
class widget_ContusRelatedVideos_init  extends WP_Widget  {

function widget_ContusRelatedVideos_init()
{
     $widget_ops = array('classname' => 'widget_ContusRelatedVideos_init ', 'description' => 'Contus Related Videos');
     $this->WP_Widget('widget_ContusRelatedVideos_init', 'Contus Related Videos', $widget_ops);
}

	 function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => 'Related Videos','show' => '3',));
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
        $videoID = intval($_GET['vid']);
        if(!empty($videoID)){
        $playlistID = $wpdb->get_var("select playlist_id from " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id=" . $videoID);
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
        <!-- For Related videos -->
<?php
        echo $before_widget;
        $div = '<div id="related-videos"  class="sidebar-wrap clearfix">
<h3 class="widget-title">'.$title.'</h3>';
        $show = $instance['show'];
        //$sql = "select a.* from " . $wpdb->prefix . "hdflvvideoshare a INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id WHERE b.playlist_id=" . $playlistID . " LIMIT " . $show;
         $sql = "select distinct(a.vid),name,description,file,hdfile,file_type,duration,image,opimage,download,link,featured,hitcount,
post_date,postrollads,prerollads from " . $wpdb->prefix . "hdflvvideoshare a INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play b ON a.vid=b.media_id WHERE b.playlist_id=" . $playlistID . " ORDER BY a.vid DESC LIMIT " . $show;
        $relatedVideos = $wpdb->get_results($sql);

        $div .='<ul class="ulwidget">';
        // were there any posts found?
        if (!empty($relatedVideos)) {
            // posts were found, loop through them
            foreach ($relatedVideos as $feature) {
                $imageFea = $feature->image;
                $vidF = $feature->vid;
                $name = strlen($feature->name);
                $getPlaylist = $wpdb->get_row("SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='$vidF'");
                $playlist_id = $getPlaylist->playlist_id;
                $fetPlay = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id'");
                $fetched = $fetPlay->playlist_name;
                if ($imageFea != '') {
                //output to screen
                    $div .='<div class="durationtimer">' . $feature1->duration . '</div>';
                    $div .='<li class="clearfix sideThumb">';

                    $div .='<div class="imgBorder">
                         <a href="' . $site_url . '/?page_id=' . $vPageID . 'video&vid=' . $feature->vid . '">
                   <img src="' . $imageFea . '" alt="' . $feature->post_title . '"  class="img" />
                    </a></div>';
                    $div .='<div class="videoName"><a href="' . $site_url . '/?page_id=' . $vPageID . 'video&vid=' . $feature->vid . '">';
                    if ($name > 25) {
                        $div .= substr($feature->name, 0, 25) . '';
                    } else {
                        $div .= $feature->name;
                    }
                    $div .='</a>';

                    $div .='<div class="clear"></div>';
             
                    if ($feature->hitcount != 0) {
                        $div .='<span class="views">';
                        if($feature->duration == 0.00)
                       {$div .=$feature->hitcount . ' views' ;}
                       else
                       {
                           $div .= $feature->duration.' '.'|'.' '.$feature->hitcount . ' views' ;
                       } $div .='</span>';
                    }       $div .='<div class="clear"></div><a   class="playlistName"href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id . '">' . $fetched . '</a>';
                    $div .='<div class="clear"></div>';
                    $div .='</div>';
                    $div .='</li>';
                    $div .='<div class="clear"></div>';
                } else {
                    $div .='<div class="durationtimer">' . $feature1->duration . '</div>';
                    $div .='<li class="clearfix sideThumb">';
                    $div .= '<div class="imgBorder">';
                    $div .='<a href="' . $site_url . '/video?vid=' . $feature->vid . '">
                     <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg" alt="' . $post->post_title . '" class="img" />
                     </a></div>';
                    $div .='<div class="videoName"><a href="' . $site_url . '/video?vid=' . $feature->vid . '">';
                    if ($name > 25) {
                        $div .=substr($feature->name, 0, 25) . '';
                    } else {
                        $div .=$feature->name;
                    }
                    $div .='</a>';
                    $div .='<div class="clear"></div>';

                    if ($feature->hitcount != 0) {
                        $div .='<span class="views">';

                           if($feature->duration == 0.00)
                       {$div .='<span class="views">' .$feature->hitcount . ' views' . '</span>';}
                       else
                       {
                           $div .='<span class="views">' . $feature->duration.' '.'|'.' '.$feature->hitcount . ' views' . '</span>';
                       }
                        $div .='</span>';
                    }
                    $div .='<div class="clear"></div>';
                    $div .='<span class="playlistName"><a href="' . $site_url . '/?page_id=' . $moreName . '&playid=' . $playlist_id . '">';
                    $div .=$fetched;
                   $div .='</a></span></div>';
                   $div .='</li>';
                    $div .='<div class="clear"></div>';
                }
            }
        }
        
        } else     { $div = '<div id="related-videos"  class="sidebar-wrap clearfix">
<h3 class="widget-title">'.$title.'</h3>';
            $div .="<li>No Related videos</li>"; $div .='<div class="clear"></div>'; }
$moreF = $wpdb->get_results("select count(*) as relatedcontus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
$countF = $moreF[0]->relatedcontus;
        // end list
//if (($show < $countF) || ($show==$countF))
//  {
//$div .='<div class="right video-more"><a href="'.$site_url.'/?page_id='.$moreName.'&more=fea">More videos</a></div>';
//  $div .='<div class="clear"></div>';
//  }

 $div .='<div class="clear"></div>';
        echo $div;

        //echo widget closing tag
        echo $after_widget;
        } 
}
// Run code and init
add_action('widgets_init', create_function('', 'return register_widget("widget_ContusRelatedVideos_init");'));//adding product tag widget
?>