<?php
/*
Plugin Name: Contus Popular Videos
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
Description: Contus Popular Videos widget with the standard system of wordpress.
Version: 1.1
Author: Contus Support
Date : 21/2/2011
*/
$site_url = get_bloginfo('url');

function widget_ContusPopularVideos_init()
{
    if ( !function_exists('register_sidebar_widget') )
    return;
    function widget_ContusPopularVideos($args)
    {
        // and after_title are the array keys." - These are set up by the theme
        extract($args);
        global $wpdb, $wp_version, $popular_posts_current_ID;
        // These are our own options
        $options = get_option('widget_ContusPopularVideos');
        $title = $options['title'];  // Title in sidebar for widget
        $show = $options['show'];  // # of Posts we are showing
        $excerpt = $options['excerpt'];  // Showing the excerpt or not
        $exclude = $options['exclude'];  // Categories to exclude
        $site_url = get_bloginfo('url');
        $dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
        ?>

        <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/js/script.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />

        <!-- For Getting The Page Id More and Video Page-->
        <?php
        $vPageID = $wpdb->get_var("select ID from ".$wpdb->prefix."posts WHERE post_content ='[contusVideo]'");
        $moreName = $wpdb->get_var("select ID from ".$wpdb->prefix."posts WHERE post_content ='[contusMore]'");
        $styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
        ?>
        <!-- Getting our contus style -->
         <?php
        if($styleSheet == 'contus')
        { ?>
          <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/contusStyle.css" />
       <?php  } ?>
<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage ;?>'
</script>
<!-- For Popular videos -->
<?php
echo $before_widget;
$div = '<div id="contuspopular" class="sidebar-wrap clearfix">
        <div><a href="'.$site_url.'/?page_id='.$moreName.'&more=pop"><h2 class="widget-title">Popular Videos</h2></a></div>';
$show       = $options['show']; //Number of shows
$sql        = "select * from ".$wpdb->prefix."hdflvvideoshare ORDER BY hitcount DESC LIMIT ".$show;
$populars   = $wpdb->get_results($sql);
$moreCount  = $wpdb->get_results("select count(*) as contus from " . $wpdb->prefix . "hdflvvideoshare");
$countP     = $moreCount[0]->contus;
$div .='<ul class="ulwidget">';
// were there any posts found?
if (!empty($populars))
    {
    // posts were found, loop through them
    foreach ($populars as $popular)
        {
        // format a date for the posts
       // if we want to display an excerpt, get it/generate it if no excerpt found
        $imagePop = $popular->image;
        $vidP     = $popular->vid;
        $name    = strlen($popular->name);
        //Getting playlist name
        $getPlaylist   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidP'");
        $playlist_id   = $getPlaylist->playlist_id;
        $fetPlay       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id'");
        $fetched       = $fetPlay->playlist_name;
        if($imagePop!='')
        //output to screen
        {
           $div .='<li><div class="sideThumb">
            <div class="imgBorder">
                <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$popular->vid.'">
                  <img src="'.$imagePop.'" alt="'.$popular->post_title.'" class="img" />
                </a></div>';
            $div .='<div class="videoName">
               <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$popular->vid.'">';
            if($name > 25) {
               $div .= substr($popular->name, 0, 25).'...'; }
               else {
               $div .= $popular->name; }
        $div .='</a>';
        $div .='<div class="clear"></div>';
            if($popular->hitcount != 0) 
            {
             $div .='<div class="views">';
             $div .=$popular->hitcount.' views';
             $div .=' </div>';
            }
       $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
       $div .=$fetched;
       $div .='</a></div></div>';
       $div .='</div></li>';
       }
       else
       {
          $div .='<li><div class="sideThumb">
           <div class="imgBorder"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$popular->vid.'">
                 <img src="'.$site_url.'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg"
                 alt="'.$post->post_title.'" class="img" />
           </a></div>';
                 $div .='<div class="videoName">
                   <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$popular->vid.'">';
                 if($name > 25) {
                 $div .=substr($popular->name, 0, 25).'...'; }
                  else {
                 $div .=$popular->name; }
                 $div .='</a>';
                 $div .='<div class="clear"></div>';

              if($popular->hitcount != 0) {
                  $div .='<div class="views">';
                  $div .= $popular->hitcount.' views';
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
    $div .="<li>No Popular videos</li>";
// end list

//For More Button
if (($show < $countP) || ($show==$countP))
  {
$div .='<div align="right"><a href="'.$site_url.'/?page_id='.$moreName.'&more=pop" class=" more">MORE</a></div>';
  } else
  {
    $div .='<div align="right"> </div>';
  }
    $div .='</ul></div>';
    echo $div;
// echo widget closing tag
echo $after_widget;
   }
// Settings form
      ?>
<?php
function widget_ContusPopularVideos_control()
{
// Get options
$options = get_option('widget_ContusPopularVideos');
// options exist? if not set defaults
if ( !is_array($options) )
{
$options = array('title'=>'Popular Videos', 'show'=>'3', 'excerpt'=>'1','exclude'=>'');
}

// form posted?
if ( $_POST['ContusPopularVideos-submit'] )
{
    // Remember to sanitize and format use input appropriately.
    $options['title'] = strip_tags(stripslashes($_POST['ContusPopularVideos-title']));
    $options['show'] = strip_tags(stripslashes($_POST['ContusPopularVideos-show']));
    $options['excerpt'] = strip_tags(stripslashes($_POST['ContusPopularVideos-excerpt']));
    $options['exclude'] = strip_tags(stripslashes($_POST['ContusPopularVideos-exclude']));
    update_option('widget_ContusPopularVideos', $options);
}

// Get options for form fields to show
$title = htmlspecialchars($options['title'], ENT_QUOTES);
$show = htmlspecialchars($options['show'], ENT_QUOTES);
$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);

// The form fields
echo '<p style="text-align:right;">
<label for="ContusPopularVideos-title">' . __('Title:') . '
<input style="width: 200px;" id="ContusPopularVideos-title" name="ContusPopularVideos-title" type="text" value="'.$title.'" />
</label></p>';
echo '<p style="text-align:right;">
<label for="ContusPopularVideos-show">' . __('Show:') . '
<input style="width: 200px;" id="ContusPopularVideos-show" name="ContusPopularVideos-show" type="text" value="'.$show.'" />
</label></p>';
echo '<input type="hidden" id="ContusPopularVideos-submit" name="ContusPopularVideos-submit" value="1" />';
}

// Register widget for use
register_sidebar_widget(array('Contus Popular Videos', 'widgets'), 'widget_ContusPopularVideos');

// Register settings for use, 300x100 pixel form
register_widget_control(array('Contus Popular Videos', 'widgets'), 'widget_ContusPopularVideos_control', 300, 200);
}
// Run code and init
add_action('widgets_init', 'widget_ContusPopularVideos_init');
?>