<?php
// Video Gallery Popular Videos
//Popular Videos widget with the standard system of wordpress.
class widget_ContusPopularVideos_init  extends WP_Widget  {

function widget_ContusPopularVideos_init()
{
     $widget_ops = array('classname' => 'widget_ContusPopularVideos_init ', 'description' => 'Contus Popular Videos');
     $this->WP_Widget('widget_ContusPopularVideos_init', 'Contus Popular Videos', $widget_ops);
}

	 function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => 'Popular Videos','show' => '3',));
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
                echo $before_title .  $after_title;
            // WIDGET CODE GOES HERE
            $tt = 1;
            global $wpdb, $wp_version, $popular_posts_current_ID;
		// These are our own options
		$options = get_option('widget_ContusPopularVideos');
		$title = $instance['title'];  // Title in sidebar for widget
		 $show = $instance['show'];  // # of Posts we are showing
		$excerpt = $options['excerpt'];  // Showing the excerpt or not
		$exclude = $options['exclude'];  // Categories to exclude
		$site_url = get_bloginfo('url');
		$dir = dirname(plugin_basename(__FILE__));
		$dirExp = explode('/', $dir);
		$dirPage = $dirExp[0];
		?>
<script	type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/js/script.js"></script>
<script type="text/javascript">
    var baseurl;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage ;?>'
</script>
<link rel="stylesheet" type="text/css"	href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/style.css" />
<!-- For Getting The Page Id More and Video Page-->
		<?php
		$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]' and post_status='publish' and post_type='page' limit 1");
		$moreName =$wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
		$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
		?>


<!-- For Popular videos -->
		<?php
		echo $before_widget;
		$div = '<div id="popular-videos" class="sidebar-wrap clearfix">
                <h3 class="widget-title"><a href="'.$site_url.'/?page_id='.$moreName.'&more=pop">'.$title.'</a></h3>';
		$show       = $instance['show']; //Number of shows
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
					$div .='<div class="durationtimer">'.$popular1->duration.'</div>';
					$div .='<li class="clearfix sideThumb">';
					$div .='<div class="imgBorder">
                <a href="'.$site_url.'/?page_id='.$vPageID.'video&vid='.$popular->vid.'">
                  <img src="'.$imagePop.'" alt="'.$popular->post_title.'" class="img" />
                </a></div>';
					$div .='<div class="videoName">
              <a href="'.$site_url.'/?page_id='.$vPageID.'video&vid='.$popular->vid.'">';
					if($name > 25) {
						$div .= substr($popular->name, 0, 25).''; }
						else {
							$div .= $popular->name; }
							$div .='</a><div class="clear"></div>';	if($popular->hitcount != 0)
							{
								$div .='<span class="views">';
								if($popular->duration == 0.00)
                                                                {
                                                                $div .=$popular->hitcount.' views';
                                                                }
                                                                else
                                                                {
                                                                    $div .=$popular->duration.' '.'|'.' '.$popular->hitcount.' views';

                                                                }
								$div .=' </span>';
							}
							//$div .='<span class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">'$fetched;$div .='</a></span>';
							  $div .='<div class="clear"></div><a class="playlistName"  href="' . $site_url .'?page_id='.$moreName.'&playid=' . $playlist_id . '">'.$fetched.'</a>';
							$div .='<div class="clear"></div>';

							$div .= '</div>';
							$div .='</li>';
							$div .='<div class="clear"></div>';
				}
				else
				{
					$div .='<div class="durationtimer">'.$popular1->duration.'</div>';
					$div .='<li class="clearfix sideThumb">';
					$div .='<div class="imgBorder"><a href="'.$site_url.'/video?vid='.$popular->vid.'">
                 <img src="'.$site_url.'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/hdflv.jpg"
                 alt="'.$post->post_title.'" class="img" />
           </a></div>';
					$div .='<div class="videoName">
                   <a href="'.$site_url.'/?vid='.$popular->vid.'">';
					if($name > 25) {
						$div .=substr($popular->name, 0, 25).''; }
						else {
							$div .=$popular->name; }
							$div .='</a>';
							$div .='<div class="clear"></div>';
							$div .='<span class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id.'">';
							$div .=$fetched;
							$div .='</a></span>';
							$div .='<div class="clear"></div>';
							if($popular->hitcount != 0) {
							$div .='<span class="views">';

                                                                if($popular->duration)
                                                                {
                                                                $div .= $popular->hitcount.' views'; }
                                                                else {
                                                                    $div .= $popular->duration.' '.'|'.' '.$popular->hitcount.' views'; }

								$div .='</span>';
							}
							$div .='</li>';
							$div .='<div class="clear"></div>';
				}
			}
		}
		else
		$div .="<li>No Popular videos</li>";
		// end list
		//For More Button
		if (($show < $countP) || ($show==$countP))
		{
			$div .='<div class="right video-more"><a href="'.$site_url.'/?page_id='.$moreName.'&more=pop">More videos</a></div>';
			$div .='<div class="clear"></div>';
		} else
		{
			$div .='<div align="right"> </div>';
		}
		$div .='</ul></div>';


	echo $div;
		// echo widget closing tag
		echo $after_widget;
} }
// Run code and init
add_action('widgets_init', create_function('', 'return register_widget("widget_ContusPopularVideos_init");'));//adding product tag widget
?>