<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video view file.
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
$page = $ordervalue = '';
$url = get_bloginfo('url') . '/wp-admin/admin.php?page=video';
if (isset($_GET['pagenum'])){
        $page = '&pagenum=' . $_GET['pagenum'];
}
?>
<script type="text/javascript">
    // When the document is ready set up our sortable with it's inherant function(s)
    var dragdr = jQuery.noConflict();
    var videoid = new Array();
    dragdr(document).ready(function() {
        dragdr("#test-list").sortable({
            handle : '.handle',
            update : function () {
                var order = dragdr('#test-list').sortable('serialize');
                orderid= order.split("listItem[]=");
                for(i=1;i<orderid.length;i++)
                {
                    videoid[i]=orderid[i].replace('&',"");
                    oid= "ordertd_"+videoid[i];
                }
                dragdr.post("<?php echo get_bloginfo('url'); ?>/wp-admin/admin-ajax.php?action=videosortorder<?php echo $page; ?>",order);
            }
        });
    });
</script>
<div class="apptha_gallery">
    <!--   MENU OPTIONS STARTS  --->
    <h2 class="nav-tab-wrapper">
        <a href="?page=video" class="nav-tab nav-tab-active"><?php _e('All Videos', 'video_gallery'); ?></a>
        <a href="?page=playlist" class="nav-tab"><?php _e('Categories', 'video_gallery'); ?></a>
        <a href="?page=videoads" class="nav-tab"><?php _e('Video Ads', 'video_gallery'); ?></a>
        <a href="?page=hdflvvideosharesettings" class="nav-tab"><?php _e('Settings', 'video_gallery'); ?></a>
    </h2>
    <!--  MENU OPTIONS ENDS --->
    <?php
    
    $selfurl = get_bloginfo('url') . "/wp-admin/admin.php?page=video" . $page;
    ?>    <div class="wrap">
        <h2 class="option_title">
            <?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "/images/manage_video.png' alt='move' width='30'/>"; ?>
            <?php _e('Manage Videos', 'video_gallery'); ?><a class="button-primary" href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=newvideo" style="margin-left: 10px;"><?php _e('Add Video', 'video_gallery'); ?></a></h2>
        <?php
        function get_current_user_role() {
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        return $user_role;
    };
    $user_role = get_current_user_role();
    if($user_role!='subscriber'){
?>
        
        <div class="admin_short_video_info"><span class="hint_heading"><?php _e('How To Use?', 'video_gallery'); ?></span>
            <?php _e("Once you install 'Wordpress Video Gallery' plugin, the page 'Videos' will be created automatically. If you would like to display the video gallery on any other page or post, you can use the following plugin code.-", "video_gallery"); ?>


            <strong><?php _e('[videohome]', 'video_gallery'); ?><br><br></strong>

            <?php _e('To display single video player on any page or post use the plugin code in any of the formats specified below.', 'video_gallery'); ?>
            <br><br>

            <strong><?php _e('[hdvideo id=11 playlistid=4 width=400 height=400] or [hdvideo playlistid=5] or [hdvideo id=10] or [hdvideo playlistid=2 relatedvideos=on]', 'video_gallery'); ?></strong><br><br>

            <?php _e("id - The Video ID, you can find the video id on 'All Videos' admin page.", "video_gallery"); ?><br><br>
            <?php _e('Playlist id - You can find the Category ID on manage Category page.', 'video_gallery'); ?><br><br>
            <?php _e('relatedvideos - You can enable/disable Related Videos on the page or post under the player. By default, it will be in "off" status.', 'video_gallery'); ?><br><br>

            <?php _e("Both the Video ID and Category ID will be generated automatically once you add new Video or Category to 'Wordpress Video Gallery'.", "video_gallery"); ?><br><br>

            <?php _e('You can use the plugin code with flashvars when you would like to display a player on any page/post with some specific settings.', 'video_gallery'); ?><br><br>

            <strong><?php _e("[hdvideo id=4 flashvars=autoplay=true&zoomIcon=false]", "video_gallery"); ?></strong><br><br>
            <?php _e('You can also enable ratings and view count for the video using the below short code.', 'video_gallery'); ?><br><br>
            <strong><?php _e("[hdvideo id=1 ratingscontrol=on views=on title=on]", "video_gallery"); ?></strong><br><br>
            <?php _e('ratingscontrol - You can enable/disable Ratings on the page or post under the player. By default, it will be in "off" status.', 'video_gallery'); ?><br><br>
            <?php _e('title - You can enable/disable Title on the page or post above the player. By default, it will be in "off" status.', 'video_gallery'); ?><br><br>
            <?php _e('views - You can enable/disable View count of the video on the page or post under the player. By default, it will be in "off" status.', 'video_gallery'); ?>
        </div>
        <?php 
    }
        if ($displayMsg): ?>
                <div class="updated below-h2">
                    <p>
                <?php echo $displayMsg; ?>
            </p>
        </div>
        <?php
                endif;
                $orderFilterlimit = filter_input(INPUT_GET, 'filter');
                $orderField = filter_input(INPUT_GET, 'order');
                $orderby = filter_input(INPUT_GET, 'orderby');
                $direction = isset($orderField) ? $orderField : false;
                if(!empty($orderby) && !empty($orderField)){
                    $ordervalue = "&orderby=$orderby&order=$orderField";
                }
                
                $reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
                if (isset($_REQUEST["videosearchbtn"])) {
        ?>
                    <div  class="updated below-h2">
            <?php
                    $searchmsg = filter_input(INPUT_POST, 'videosearchQuery');
                    if (count($gridVideo)) {
                        echo count($gridVideo) . "   Search Result(s) for '" . $searchmsg . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
                    } else {
                        echo " No Search Result(s) for '" . $searchmsg . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
                    }
            ?>
                </div>
        <?php } ?>
                <form class="admin_video_search" name="videos" action="<?php echo $url.'&#videofrm'; ?>" method="post" onsubmit="return videosearch();">
                    <p class="search-box">
                        <input type="text"  name="videosearchQuery" id="VideosearchQuery" value="<?php if (isset($searchmsg))
                    echo $searchmsg; ?>">
                       <?php //echo '<script>document.getElementById("videosearchQuery").value="'.$searchmsg.'"</script>'; ?>
                <input type="hidden"  name="page" value="videos">
                <input type="submit" name="videosearchbtn"  class="button" value="<?php _e('Search Videos', 'video_gallery'); ?>"></p>
        </form>
        <form  class="admin_video_action" name="videofrm" id="videofrm" action="" method="post" onsubmit="return VideodeleteIds()">
            <div class="tablenav top">
                <div class="alignleft actions" style="margin-bottom:10px;">
                    <select name="videoactionup" id="videoactionup">
                        <option value="-1" selected="selected"><?php _e('Bulk Actions', 'video_gallery'); ?></option>
                        <option value="videodelete"><?php _e('Delete', 'video_gallery'); ?></option>                        
                    </select>
                    <input type="submit" name="videoapply"  class="button-secondary action" value="<?php _e('Apply', 'video_gallery'); ?> ">
                </div>
                 <div class="alignleft actions" style="margin-bottom:10px;">
            <select name="videofilteraction" id="videofilteraction" onchange="window.location.href=this.value" >
                <option value="" selected="selected">Select Limit</option>
                <option <?php if($orderFilterlimit == 5) { echo 'selected'; } ?> value="<?php echo $url.$page.$ordervalue; ?>&filter=5#videofrm">5</option>                        
                <option <?php if($orderFilterlimit == 10) { echo 'selected'; } ?> value="<?php echo $url.$page.$ordervalue; ?>&filter=10#videofrm">10</option>                        
                <option <?php if($orderFilterlimit == 20) { echo 'selected'; } ?> value="<?php echo $url.$page.$ordervalue; ?>&filter=20#videofrm">20</option>                        
                <option <?php if($orderFilterlimit == 50) { echo 'selected'; } ?> value="<?php echo $url.$page.$ordervalue; ?>&filter=50#videofrm">50</option>                        
                <option <?php if($orderFilterlimit == 100) { echo 'selected'; } ?> value="<?php echo $url.$page.$ordervalue; ?>&filter=100#videofrm">100</option>                        
                <option <?php if($orderFilterlimit == 'all') { echo 'selected'; } ?>value="<?php echo $url.$page.$ordervalue; ?>&filter=all#videofrm">All</option>                        
            </select>
         </div>
                <?php
                       if(!empty($orderFilterlimit) && $orderFilterlimit !== 'all'){
                            $limit = $orderFilterlimit;
                        } else if($orderFilterlimit === 'all'){
                            $limit = $Video_count;
                        } else {
                       $limit = 20;
                        }
                       $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
                       $total = $Video_count;
                       $num_of_pages = ceil($total / $limit);
                       $arr_params     = array ( 'pagenum' => '%#%', '#videofrm'=> '' );
                       $page_links = paginate_links(array(
                                   'base' => add_query_arg($arr_params),
                                   'format' => '',
                                   'prev_text' => __('&laquo;', 'aag'),
                                   'next_text' => __('&raquo;', 'aag'),
                                   'total' => $num_of_pages,
                                   'current' => $pagenum
                               ));

                       if ($page_links) {
                           echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                       }
                ?>
                       <br/><br/>

                       <div style="float:right ; font-weight: bold;" ><?php if (isset($pagelist))
                           echo $pagelist; ?></div>
                       <div style="clear: both;"></div>
                   <table class="wp-list-table widefat fixed tags" cellspacing="0" width="100%">
                       <thead>
                           <tr>
                               <th width="3%"  scope="col" style="" class="manage-column column-cb check-column">
                                   <input type="checkbox" name="" ></th>
                               <th width="3%" scope="col"  style="">
                                   <span>
                                    <?php _e('', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></th>
                            <th width="4%" scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=id&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('ID', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th width="6%" scope="col"  style="">
                                <span class="sorting-indicator"></span></th>
                            <th width="30%" scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Title', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th width="14%" scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=author&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Author', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="14%" scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=category&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Categories', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="8%" scope="col" class="manage-column column-description sortable desc text_center"  style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=fea&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Featured', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th  width="4%" scope="col"  class="manage-column column-slug sortable desc" style="width:10%">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=date&order=<?php echo $reverse_direction; ?>"><span><?php _e('Date', 'digi'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="7%" scope="col" class="text_center manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=publish&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Publish', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="7%" scope="col" class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=ordering&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('Order', 'video_gallery'); ?></span><span class="sorting-indicator"></span>                                 
                                </a>
                            </th>


                        </tr>
                    </thead>
                    <tbody id="test-list" class="list:tag">
                        <?php
                                        $i = 0;
                                        foreach ($gridVideo as $videoView) {
                                            $i++;
                        ?>
                                            <tr id="listItem_<?php echo $videoView->vid; ?>">
                                                <th scope="row" class="check-column">
                                                    <input type="checkbox" name="video_id[]" value="<?php echo $videoView->vid ?>"></th>
                                                
                                                <td>
                                                    <?php if($user_role!='subscriber'){ ?>
                                                    <span class="hasTip content" title="<?php _e('Click and Drag', 'video_gallery'); ?>" style="padding: 6px;">
                                                        <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/arrow.png'; ?>" alt="move"
                                                             width="16" height="16" class="handle" />
                                                    </span>
                                                    <?php } ?>
                                                </td>
                                                
                                                <td class="image column-image">
                                                    <a title="Edit <?php echo $videoView->name; ?>"  href="<?php echo get_bloginfo('url'); ?>?page=newvideo&videoId=<?php echo $videoView->vid; ?>" ><?php echo $videoView->vid; ?></a>
                                                </td>
                                                <td class="image column-image">
                                                    <?php 
                                                    $image_path                     = str_replace('plugins/'.$dirPage.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                                                    $_imagePath                     = APPTHA_VGALLERY_BASEURL . 'images' . DS;
                                                    $thumb_image                    = $videoView->image;               ## Get thumb image
                                                    $file_type                      = $videoView->file_type;           ## Get file type of a video
                                                    if ($thumb_image == '') {       ## If there is no thumb image for video
                                                        $thumb_image                = $_imagePath . 'nothumbimage.jpg';
                                                    } else {
                                                        if ($file_type == 2 || $file_type == 5) {      ## For uploaded image
                                                            $thumb_image            = $image_path . $thumb_image;
                                                        }
                                                    }  ?>
                                                    <a title="Edit <?php echo $videoView->name; ?>"  href="<?php echo get_bloginfo('url'); ?>?page=newvideo&videoId=<?php echo $videoView->vid; ?>" >
                                                        <img width="60" height="60" src="<?php echo $thumb_image; ?>" class="attachment-80x60" alt="Hydrangeas"></a>
                                                </td>
                                                <td>
                                                    <a title="Edit <?php echo $videoView->name; ?>" class="row-title" href="<?php echo get_bloginfo('url'); ?>?page=newvideo&videoId=<?php echo $videoView->vid; ?>" ><?php echo $videoView->name; ?></a>
                                                </td>
                                                <td class="description column-description"><?php echo $videoView->display_name; ?></td>
                                                <td class="description column-description"><?php 
                                                $videoOBJ = new VideoController();
                                                $playlistData = $videoOBJ->get_playlist_detail($videoView->vid);
                                                $incre                  = 0;
                                                $playlistname = '';
                                                foreach ($playlistData as $playlist) {
                                                    if ($incre > 0) {
                                                        $playlistname   .= ', '. $playlist->playlist_name;
                                                    } else {
                                                        $playlistname   .= $playlist->playlist_name;
                                                    }
                                                    $incre++;
                                                }
                                                echo $playlistname;
                                                 ?></td>

                                                <td class="description column-featured" style="text-align:center"> <?php
                                            $feaStatus = 1;
                                            $feaImage = __("deactivate.jpg");
                                            $feaPublish = __("Click here to Activate");
                                            if ($videoView->featured == 1) {
                                                $feaStatus = 0;
                                                $feaImage = __("activate.jpg");
                                                $feaPublish = __("Click here to InActivate");
                                            }
                        ?>
                                            <a  title="Edit <?php echo $videoView->name; ?>" href="<?php echo $selfurl; ?>&videoId=<?php echo $videoView->vid; ?>&featured=<?php echo $feaStatus; ?>">   <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/' . $feaImage ?>" title="<?php echo $feaPublish; ?>" title="<?php echo $feaPublish; ?>"   />
                                            </a>
                                        </td>
                                        <td class="description column-description"><?php echo date("Y/m/d",strtotime($videoView->post_date)); ?></td>

                                        <td class="description column-description column-publish" style="text-align:center"><?php
                                            $status = 1;
                                            $image = __("deactivate.jpg");
                                            $publish = __("Click here to Activate");
                                            if ($videoView->publish == 1) {
                                                $status = 0;
                                                $image = __("activate.jpg");
                                                $publish = __("Click here to InActivate");
                                            }
                        ?>
                                            <a  title="Edit <?php echo $videoView->name; ?>" href="<?php echo $selfurl; ?>&videoId=<?php echo $videoView->vid; ?>&status=<?php echo $status; ?>">   <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/' . $image ?>" title="<?php echo $publish; ?>" title="<?php echo $publish; ?>"   /> </a>

                                        </td>
                                        <td style="text-align:center">
                                            <?php echo $videoView->ordering; ?>
                                        </td>
                                    </tr>
                        <?php
                                        }

                                        if (count($gridVideo) == 0) {
                        ?>
                                            <tr class="no-items"><td class="colspanchange" colspan="5"><?php _e('No videos found.', 'video_gallery'); ?></td></tr> <?php
                                        }
                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="col"  class="manage-column column-cb check-column" style="">
                                                <input type="checkbox" name="" ></th>
                                            <th width="5%" scope="col"  style="">
                                                <span>
                                    <?php _e('', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></th>
                                <th scope="col"  class="manage-column column-name sortable desc" style="">
                                    <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=id&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('ID', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th width="4%" scope="col"  style="">
                                <span class="sorting-indicator"></span></th>
                                <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Title', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=author&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Author', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=category&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Categories', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-slug sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=fea&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Featured', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-slug sortable desc" style="width:10%">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=date&order=<?php echo $reverse_direction; ?>"><span><?php _e('Date', 'digi'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th scope="col" class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=publish&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Publish', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>

                            <th scope="col" class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=ordering&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('Order', 'video_gallery'); ?></span><span class="sorting-indicator"></span>
                                </a>
                            </th>

                        </tr>
                    </tfoot>
                </table>
                <div class="alignleft actions" style="margin-top:10px;">
                    <select name="videoactiondown" id="videoactiondown">
                        <option value="-1" selected="selected"><?php _e('Bulk Actions', 'video_gallery'); ?></option>
                        <option value="videodelete"><?php _e('Delete', 'video_gallery'); ?></option>
                    </select>
                    <input type="submit" name="videoapply"  class="button-secondary action" value="<?php _e('Apply', 'video_gallery'); ?>">
                </div>
                <?php
                                        if ($page_links) {
                                            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                                        }
                ?>
            </div>
        </form>
    </div>
</div>