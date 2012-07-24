<?php
/*
  Plugin Name: Contus Recent Videos
  Plugin URI:  http://www.hdflvplayer.net/wordpress-video-gallery/
  Description: Contus Recent Videos widget with the standard system of wordpress.
  Version: 1.0
  Author: Contus Support
  wp-content\plugins\contus-hd-flv-player\ContusRecentVideos.php
  Date : 21/2/2011
 */
function widget_ContusRecentVideos_init()
{
    if (!function_exists('register_sidebar_widget'))
        return;
    function widget_ContusRecentVideos($args)
    {
        // "$args is an array of strings that help widgets to conform to
        // the active theme: before_widget, before_title, after_widget,
        // and after_title are the array keys." - These are set up by the theme
        extract($args);
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusRecentVideos');
        $title = $options['title'];  // Title in sidebar for widget
        $show = $options['show'];  // # of Posts we are showing
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
        $vPageID = $wpdb->get_var("select 	ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
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
        $div ='<div id="contusrecent" class="sidebar-wrap clearfix"><div>
            <a href="'.$site_url.'/?page_id='.$moreName.'&more=rec"><h2 class="widget-title">Recent Videos</h2></a></div>';
        $show = $options['show'];
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
        $div .='<li><div class="sideThumb"><div class="imgBorder">
                    <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$post->vid.'">
                    <img src="' . $image . '" alt="' . $post->post_title . '" class="img" />
                    <a/></div>';

                $div .='<div class="videoName"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$post->vid.'">';
                 if ($name > 25) {
                 $div .= substr($post->name, 0, 25) .'...'; }
                 else {
                $div .=$post->name;
                  }
                 $div .='</a>';
                $div .='<div class="clear"></div>';

                    if ($post->hitcount != 0) {
                    $div .='<div class="views">';
                    $div .=$post->hitcount . ' views';
                    $div .='</div>';
                     }
                    $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
                    $div .=   $fetched;
                    $div .='</a></div></div>';
                    $div .='</div></li>';

                }
                else
                {
                $div .='<li><div class="sideThumb">
                  <div class="imgBorder"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$post->vid.'">
                   <img src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg"
                    alt="' . $post->post_title . '" class="img"  />
                   </a></div>';
                      $div .='<div class="videoName"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$post->vid.'">';
                    if ($name > 25) {
                     $div .=substr($post->name, 0, 25) .'...';}
                     else {
                    $div .=$post->name;
                    }
                        $div .=' </a>';
                        $div .='<div class="clear"></div>';

                    if ($post->hitcount != 0) {
                       $div .='<div class="views">';
                       $div .=$post->hitcount . ' views';
                       $div .='</div>';
                       }
                   $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
                   $div .=$fetched;
                   $div .='</a></div></div>';
                   $div .='</div></li>';

                }
              }
            }
            else
              $div .="<li>No recent Videos</li>";
// end list
            if (($show < $countR) || ($show == $countR))  {
            $div .='<div align="right"><a href="'.$site_url.'/?page_id='.$moreName.'&more=rec">More</a></div>';
            }
   $div .='</ul></div>';
   echo $div;
// echo widget closing tag
  echo $after_widget;
 }

// Settings form
 function widget_ContusRecentVideos_control()
{
  // Get options
  $options = get_option('widget_ContusRecentVideos');
  // options exist? if not set defaults
  if (!is_array($options))
  {
   $options = array('title' => 'Recent Videos', 'show' => '5', 'excerpt' => '1', 'exclude' => '');
  }
  // form posted?
  if ($_POST['ContusRecentVideos-submit'])
  {
    // Remember to sanitize and format use input appropriately.
     $options['title'] = strip_tags(stripslashes($_POST['ContusRecentVideos-title']));
     $options['show'] = strip_tags(stripslashes($_POST['ContusRecentVideos-show']));
     $options['excerpt'] = strip_tags(stripslashes($_POST['ContusRecentVideos-excerpt']));
     $options['exclude'] = strip_tags(stripslashes($_POST['ContusRecentVideos-exclude']));
     update_option('widget_ContusRecentVideos', $options);
  }
// Get options for form fields to show
 $title   = htmlspecialchars($options['title'], ENT_QUOTES);
 $show    = htmlspecialchars($options['show'], ENT_QUOTES);
 $excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
 $exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

// The form fields
echo '<p style="text-align:right;">
<label for="ContusRecentVideos-title">' . __('Title:') . '
<input style="width: 200px;" id="ContusRecentVideos-title" name="ContusRecentVideos-title" type="text" value="' . $title . '" />
</label></p>';
 echo '<p style="text-align:right;">
<label for="ContusRecentVideos-show">' . __('Show:') . '
<input style="width: 200px;" id="ContusRecentVideos-show" name="ContusRecentVideos-show" type="text" value="' . $show . '" />
</label></p>';
echo '<input type="hidden" id="ContusRecentVideos-submit" name="ContusRecentVideos-submit" value="1" />';
 }

// Register widget for use
 register_sidebar_widget(array('Contus Recent Videos', 'widgets'), 'widget_ContusRecentVideos');

// Register settings for use, 300x100 pixel form
register_widget_control(array('Contus Recent Videos', 'widgets'), 'widget_ContusRecentVideos_control', 300, 200);
  }
// Run code and init
  add_action('widgets_init', 'widget_ContusRecentVideos_init');
  ?>