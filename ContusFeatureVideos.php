<?php
/*
Plugin Name: Contus Feature Videos
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
Description: Contus Feature Videos widget with the standard system of wordpress.
Version: 1.0
Author: Contus Support
wp-content\plugins\contus-hd-flv-player\ContusFeatureVideos.php
Date : 21/2/2011
*/

function widget_ContusFeatureVideos_init() {
    if ( !function_exists('register_sidebar_widget') )
    return;
    function widget_ContusFeatureVideos($args)
    {
        // and after_title are the array keys." - These are set up by the theme

        extract($args);
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
            $options = get_option('widget_ContusFeatureVideos');
            $title = $options['title'];  // Title in sidebar for widget
            $show = $options['show'];  // # of Posts we are showing
            $excerpt = $options['excerpt'];  // Showing the excerpt or not
            $exclude = $options['exclude'];  // Categories to exclude
            $site_url = get_bloginfo('url');
            $dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
?>
  <!-- Recent videos -->
<script type="text/javascript" src="<?php echo $site_url;?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />
<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage;?>'
</script>
<!-- For Getting The Page Id More and Video-->
<?php
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
$site_url = get_bloginfo('url');

?>
 <!-- Getting our contus style -->
 <?php
        if($styleSheet == 'contus')
        { ?>
          <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/contusStyle.css" />
       <?php  } ?>

<!-- For Feature videos -->
<?php
echo $before_widget;
$div = '<div id="contusfeatured"  class="sidebar-wrap clearfix">
<div><a href="'.$site_url.'/?page_id='.$moreName.'&more=fea"><h2 class="widget-title">Feature Videos</h2></a></div>';
$show   =$options['show'];
$sql = "select * from ".$wpdb->prefix."hdflvvideoshare WHERE featured='ON' LIMIT ".$show;
$features = $wpdb->get_results($sql);
$moreF = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
$countF = $moreF[0]->contus;
$div .='<ul class="ulwidget">';
// were there any posts found?
if (!empty($features))
    {
    // posts were found, loop through them
    foreach ($features as $feature)
        {
        $imageFea = $feature->image;
        $vidF     = $feature->vid;
        $name     = strlen($feature->name);

        $getPlaylist   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidF'");
        $playlist_id   = $getPlaylist->playlist_id;
        $fetPlay       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id'");
        $fetched      = $fetPlay->playlist_name;
        if($imageFea!='')
        //output to screen
        {

                 $div .='<li><div class="sideThumb"><div class="imgBorder">
                         <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$feature->vid.'">
                   <img src="'.$imageFea.'" alt="'.$feature->post_title.'"  class="img" />
                    </a></div>';
                  $div .='<div class="videoName"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$feature->vid.'">';
                  if($name > 25) {
                     $div .= substr($feature->name, 0, 25).'...'; }
                      else {
                       $div .= $feature->name;

                       }
                  $div .='</a>';
                  $div .='<div class="clear"></div>';
                  if($feature->hitcount != 0) {
                       $div .='<div class="views">'.$feature->hitcount.' views'.'</div>';
                     }
                     $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">'.$fetched.'</a></div>';
                     $div .='</div>';
                     $div .='</div></li>';

        }
        else
        {
            $div .='<li><div class="sideThumb">';
            $div .= '<div class="imgBorder">
                     <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$feature->vid.'">
                     <img src="'.$site_url.'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg" alt="'.$post->post_title.'" class="img" />
                     </a></div>';
                    $div .='<div class="videoName"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$feature->vid.'">';
                    if($name > 25) {
                       $div .=substr($feature->name, 0, 25).'...';}
                       else {
                           $div .=$feature->name;
                             }
                     $div .='</a>';
                    $div .='<div class="clear"></div>';

                   if($feature->hitcount != 0) {
                        $div .='<div class="views">';
                        $div .=$feature->hitcount.' views';
                        $div .='</div>';
                        }
                   $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">'.$fetched.'</a></div></div>';
                   $div .='</div></li>';

              }
      }
} else $div .="<li>No Feature videos</li>";
// end list
if (($show < $countF) || ($show==$countF))
  {
$div .='<div align="right"><a href="'.$site_url.'/?page_id='.$moreName.'&more=fea">More</a></div>';
  }
  else
  {
  $div .='<div align="right"> </div>';
  }
$div .='</ul></div>';
echo $div;
// echo widget closing tag
echo $after_widget;
}

// Settings form
function widget_ContusFeatureVideos_control()
{
// Get options
$options = get_option('widget_ContusFeatureVideos');
// options exist? if not set defaults
if ( !is_array($options) )
{
  $options = array('title'=>'Feature Videos', 'show'=>'3', 'excerpt'=>'1','exclude'=>'');
}
// form posted?
if ( $_POST['ContusFeatureVideos-submit'] )
    {

        // Remember to sanitize and format use input appropriately.
        $options['title'] = strip_tags(stripslashes($_POST['ContusFeatureVideos-title']));
        $options['show'] = strip_tags(stripslashes($_POST['ContusFeatureVideos-show']));
        $options['excerpt'] = strip_tags(stripslashes($_POST['ContusFeatureVideos-excerpt']));
        $options['exclude'] = strip_tags(stripslashes($_POST['ContusFeatureVideos-exclude']));
        update_option('widget_ContusFeatureVideos', $options);
   }
// Get options for form fields to show
$title = htmlspecialchars($options['title'], ENT_QUOTES);
$show = htmlspecialchars($options['show'], ENT_QUOTES);
$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

// The form fields
echo '<p style="text-align:right;">
        <label for="ContusFeatureVideos-title">' . __('Title:') . '
        <input style="width: 200px;" id="ContusFeatureVideos-title" name="ContusFeatureVideos-title" type="text" value="'.$title.'" />
        </label></p>';
echo '<p style="text-align:right;">
        <label for="ContusFeatureVideos-show">' . __('Show:') . '
        <input style="width: 200px;" id="ContusFeatureVideos-show" name="ContusFeatureVideos-show" type="text" value="'.$show.'" />
        </label></p>';

echo '<input type="hidden" id="ContusFeatureVideos-submit" name="ContusFeatureVideos-submit" value="1" />';
}

// Register widget for use
register_sidebar_widget(array('Contus Feature Videos', 'widgets'), 'widget_ContusFeatureVideos');

// Register settings for use, 300x100 pixel form
register_widget_control(array('Contus Feature Videos', 'widgets'), 'widget_ContusFeatureVideos_control', 300, 200);
}
// Run code and init
add_action('widgets_init', 'widget_ContusFeatureVideos_init');
?>