<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video view file.
  Version: 2.3.1.0.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

$dir = dirname(plugin_basename(__FILE__));
$dirExp = explode('/', $dir);
$dirPage = $dirExp[0];
?>
<script type="text/javascript" src="<?php echo APPTHA_VGALLERY_BASEURL . "/js/jquery-1.3.2.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo APPTHA_VGALLERY_BASEURL . "/js/jquery-ui-1.7.1.custom.min.js"; ?>"></script>
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
                    //                    document.getElementById(oid).innerHTML=i-1;
                }
                dragdr.post("<?php echo get_bloginfo('url') . "/wp-content/plugins/$dirPage/sortorder.php"; ?>",order);

                <!-- Codes by Quackit.com -->

            }
        });
    });
</script>
<script>jQuery.noConflict(true);</script>
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
    $page = '';
    if (isset($_GET['pagenum']))
        $page = '&pagenum=' . $_GET['pagenum'];
    $selfurl = get_bloginfo('url') . "/wp-admin/admin.php?page=video" . $page;
    ?>    <div class="wrap">
        <h2 class="option_title">
            <?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "/images/manage_video.png' alt='move' width='30'/>"; ?>
            <?php _e('Manage Videos', 'video_gallery'); ?><a class="button-primary" href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=newvideo" style="margin-left: 10px;"><?php _e('Add Video', 'video_gallery'); ?></a></h2>
        <div class="admin_short_video_info"><span class="hint_heading"><?php _e('How To Use?', 'video_gallery'); ?></span>
            <?php _e("Once you install 'Wordpress Video Gallery' plugin, the page 'Videos' will be created automatically. If you would like to display the video gallery on any other page or post, you can use the following plugin code.-", "video_gallery"); ?>


            <strong><?php _e('[videohome]', 'video_gallery'); ?><br><br></strong>

            <?php _e('To display single video player on any page or post use the plugin code in any of the formats specified below.', 'video_gallery'); ?>
            <br><br>

            <strong><?php _e('[hdvideo id=3 playlistid=2 width=400 height=400] or [hdvideo playlistid=2] or [hdvideo id=3] or [hdvideo playlistid=2 relatedvideos=on]', 'video_gallery'); ?></strong><br><br>

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



        <?php if ($displayMsg): ?>
                <div class="updated below-h2">
                    <p>
                <?php echo $displayMsg; ?>
            </p>
        </div>
        <?php
                endif;
                $orderField = filter_input(INPUT_GET, 'order');
                $direction = isset($orderField) ? $orderField : false;
                $reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
                if (isset($_REQUEST["videosearchbtn"])) {
        ?>
                    <div  class="updated below-h2">
            <?php
                    $url = get_bloginfo('url') . '/wp-admin/admin.php?page=video';
                    $searchmsg = filter_input(INPUT_POST, 'videosearchQuery');
                    if (count($gridVideo)) {
                        echo count($gridVideo) . "   Search Result(s) for '" . $searchmsg . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
                    } else {
                        echo " No Search Result(s) for '" . $searchmsg . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
                    }
            ?>
                </div>
        <?php } ?>
                <form class="admin_video_search" name="videos" action="" method="post" onsubmit="return videosearch();">
                    <p class="search-box">
                        <input type="text"  name="videosearchQuery" id="VideosearchQuery" value="<?php if (isset($searchmsg))
                    echo $searchmsg; ?>">
                       <?php //echo '<script>document.getElementById("videosearchQuery").value="'.$searchmsg.'"</script>'; ?>
                <input type="hidden"  name="page" value="videos">
                <input type="submit" name="videosearchbtn"  class="button" value="<?php _e('Search Videos', 'video_gallery'); ?>"></p>
        </form>
        <form  class="admin_video_action" name="videofrm" action="" method="post" onsubmit="return VideodeleteIds()">
            <div class="tablenav top">
                <div class="alignleft actions" style="margin-bottom:10px;">
                    <select name="videoactionup" id="videoactionup">
                        <option value="-1" selected="selected"><?php _e('Bulk Actions', 'video_gallery'); ?></option>
                        <option value="videodelete"><?php _e('Delete', 'video_gallery'); ?></option>                        
                    </select>
                    <input type="submit" name="videoapply"  class="button-secondary action" value="<?php _e('Apply', 'video_gallery'); ?> ">
                </div>
                <?php
                       $limit = 20;
                       $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
                       $total = $Video_count;
                       $num_of_pages = ceil($total / $limit);
                       $page_links = paginate_links(array(
                                   'base' => add_query_arg('pagenum', '%#%'),
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
                               <th width="5%"  scope="col" style="" class="manage-column column-cb check-column">
                                   <input type="checkbox" name="" ></th>
                               <th width="5%" scope="col"  style="">
                                   <span>
                                    <?php _e('', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></th>
                            <th width="7%" scope="col"  style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=id&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Video ID', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th width="30%" scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Title', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th width="28%" scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=desc&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Path', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="8%" scope="col" class="text_center"  style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=fea&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Featured', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th  width="5%" scope="col"  class="manage-column column-slug sortable desc" style="width:10%">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=date&order=<?php echo $reverse_direction; ?>"><span><?php _e('Date', 'digi'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="6%" scope="col" class="text_center manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=publish&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Publish', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
                            <th width="8%" scope="col" class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=ordering&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('Sort Order', 'video_gallery'); ?></span>                                    
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
                                                    <span class="hasTip content" title="<?php _e('Click and Drag', 'video_gallery'); ?>" style="padding: 6px;">
                                                        <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/arrow.png'; ?>" alt="move"
                                                             width="16" height="16" class="handle" />
                                                    </span>
                                                </td>
                                                <td class="image column-image" style='text-align:center;'>
                                                    <a title="Edit <?php echo $videoView->name; ?>"  href="<?php echo $_SERVER["PHP_SELF"]; ?>?page=newvideo&videoId=<?php echo $videoView->vid; ?>" ><?php echo $videoView->vid; ?></a>
                                                </td>
                                                <td>
                                                    <a title="Edit <?php echo $videoView->name; ?>" class="row-title" href="<?php echo $_SERVER["PHP_SELF"]; ?>?page=newvideo&videoId=<?php echo $videoView->vid; ?>" ><?php echo $videoView->name; ?></a>
                                                </td>
                                                <td class="description column-description"><?php echo $videoView->file; ?></td>

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
                                        <td class="description column-description"><?php echo date("M j, Y",strtotime($videoView->post_date)); ?></td>

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
                                            <a title="Edit <?php echo $videoView->ordering; ?>" class="row-title" ><?php echo $videoView->ordering; ?></a>
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
                                        <?php _e('Video ID', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Title', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></a></th>
                            <th scope="col"  class="manage-column column-description sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=video&orderby=desc&order=<?php echo $reverse_direction; ?>"><span>
                                        <?php _e('Path', 'video_gallery'); ?></span><span class="sorting-indicator"></span></a></th>
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
                                    <span><?php _e('Sort Order', 'video_gallery'); ?></span>
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