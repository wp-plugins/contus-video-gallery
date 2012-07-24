<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Video Gallery Recent Videos
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */
// Video Gallery Recent Videos
// Recent Videos widget with the standard system of wordpress.

class widget_ContusRecentVideos_init  extends WP_Widget  {

function widget_ContusRecentVideos_init()
{
     $widget_ops = array('classname' => 'widget_ContusRecentVideos_init ', 'description' => 'Contus Recent Videos');
     $this->WP_Widget('widget_ContusRecentVideos_init', 'Contus Recent Videos', $widget_ops);
}

	 function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => 'Recent Videos','show' => '3',));
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
               // echo $before_title .  $after_title;
            // WIDGET CODE GOES HERE
            $tt = 1;
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusRecentVideos');
        $title = $instance['title'];  // Title in sidebar for widget
        $show = $instance['show'];  // # of Posts we are showing
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
        $dirExp = explode('/', $dir);
        $dirPage = $dirExp[0];
?>
<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage;?>'
</script>
<!-- For Getting The Page Id More and Video-->
<?php
        $vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
       $moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
        $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        $site_url = get_bloginfo('url');
?>
        <!-- Recent videos -->
        <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/js/script.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />
         <!-- Getting our contus style -->
        <?php
        if($styleSheet == 'contus')
        { ?>
          <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/contusStyle.css" />
       <?php  } ?>
   <?php
    echo $before_widget;
        $div ='<div id="recent-videos" class="sidebar-wrap clearfix">
            <h3 class="widget-title"><a href="'.$site_url.'/?page_id='.$moreName.'&more=rec">'.$title.'</a></h3>';
        $show = $instance['show'];
        $sql = 'select DISTINCT * from ' . $wpdb->prefix . 'hdflvvideoshare
                ORDER BY post_date DESC LIMIT ' . $show;
        $posts = $wpdb->get_results($sql);
        $moreR = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC");
        $countR = $moreR[0]->contus;
        $div .='<ul class="ulwidget">';
// were there any posts found?
        if (!empty($posts))
        {
            // posts were found, loop through them
            foreach ($posts as $post)
            {
                // if we want to display an excerpt, get it/generate it if no excerpt found
                $image = $post->image;
                $vid = $post->vid;
                $name = strlen($post->name);
                $getPlaylist   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vid'");
                $playlist_id   = $getPlaylist->playlist_id;
                $fetPlay       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id'");
                 $fetched      = $fetPlay->playlist_name;
           
                if ($image != '')
                 {
                    //output to screen                    
        $div .='<div class="durationtimer">'.$post1->duration.'</div>';
        $div .='<li class="clearfix sideThumb">';
        $div .='<div class="imgBorder">
                    <a href="'.$site_url.'/?page_id='.$vPageID.'video&vid='.$post->vid.'">
                    <img src="' . $image . '" alt="' . $post->post_title . '" class="img" />
                        
                    <a/></div>';
                $div .='<div class="videoName"><a href="'.$site_url.'/?page_id='.$vPageID.'video&vid='.$post->vid.'">';
                 if ($name > 25) {
                 $div .= substr($post->name, 0, 25) .''; }
                 else {
                $div .=$post->name;
                  }
                 $div .='</a>';   $div .='<div class="clear"></div>'; if ($post->hitcount != 0) {
                    $div .='<span class="views">';
                    if($post->duration==0.00)
                    { $div .=$post->hitcount . ' views'; }
                    else
                    {
                        $div .=$post->duration.' '.'|'.' '.$post->hitcount . ' views';
                    }
                    $div .='</span>';
                     }
                   // $div .='<br/><span class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
                   // $div .=   $fetched;
                   // $div .='</a></span>';
                  $div .='<div class="clear"></div><a class="playlistName"  href="' . $site_url .'?page_id='.$moreName.'&playid=' . $playlist_id . '">'.$fetched.'</a>';
                    $div .='<div class="clear"></div>';
                  
                     $div .= '</div>';
                    $div .='</li>'; $div .='<div class="clear"></div>';
                }
                else
                {
                $div .='<div class="durationtimer">'.$post1->duration.'</div>';
                $div .='<li class="clearfix sideThumb">';
                $div .='<div class="imgBorder"><a href="'.$site_url.'/?page_id=' . $vPageID . '&vid='.$post->vid.'">
                   <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg"
                    alt="' . $post->post_title . '" class="img"  />
                   </a></div>';
                      $div .='<div class="videoName"><a href="'.$site_url.'/?page_id=' . $vPageID . '&vid='.$post->vid.'">';
                    if ($name > 25) {
                     $div .=substr($post->name, 0, 25) .'';}
                     else {
                    $div .=$post->name;
                    }
                        $div .=' </a>';
                        $div .='<div class="clear"></div>';
                    if ($post->hitcount != 0) {
                       $div .='<span class="views">';
                       if($post->duration == 0.00)
                       { $div .=$post->hitcount . ' views';                   }
                       else
                       {
                 $div .=$post->duration.' '.'|'.' '.$post->hitcount . ' views';  
                       }
                       $div .='</span>';
                       }
                         $div .= '<div class="clear"></div>';
                   $div .='<span class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
                   $div .=$fetched;
                   $div .='</a></span></div>';
                   $div .='</li>';
  $div .='<div class="clear"></div>';
                }
              }
            }
            else
              $div .="<li>No recent Videos</li>";
// end list
            if (($show < $countR) || ($show == $countR))  {
            $div .='<div class="right video-more"><a href="'.$site_url.'/?page_id='.$moreName.'&more=rec">More videos</a></div>';
              $div .='<div class="clear"></div>';
            }
   $div .='</ul></div>';
   echo $div;
// echo widget closing tag
  echo $after_widget;
 }

// Register widget for use
   }
// Run code and init
 add_action('widgets_init', create_function('', 'return register_widget("widget_ContusRecentVideos_init");'));
  ?>