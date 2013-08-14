<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: add playlist model file.
Version: 2.2
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>
<div class="apptha_gallery">
<?php       if(isset($playListId)){ ?>
<h2><?php _e('Update Category','video_gallery'); ?></h2> <?php } else { ?> <h2  class="option_title"><?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "images/vid_ad.png' alt='move' width='30'/>"; ?><?php _e('Add a New Category','video_gallery'); ?></h2> <?php } ?>
    <?php if ($displayMsg): ?>
    <div class="updated below-h2">
        <p>
            <?php echo $displayMsg;
            $url = get_bloginfo('url').'/wp-admin/admin.php?page=playlist';
            echo "<a href='$url' >Back to Category</a>";
            ?>
        </p>
    </div>
    <?php endif; ?>
    <div id="post-body" class="has-sidebar">
        <div id="post-body-content" class="has-sidebar-content">
            <div class="stuffbox">
                <h3 class="hndle"><span><?php _e('Enter Title / Name', 'video_gallery'); ?></span></h3>
                <div class="inside" style="margin:15px;">
                    <form name="adsform" method="post" enctype="multipart/form-data" >
                        <table class="form-table">
                            <tr>
                            <th scope="row"><?php _e('Title / Name', 'video_gallery') ?></th>
                            <td>
                                <input type="text" size="50" maxlength="200" id="playlistname" name="playlistname" value="<?php echo (isset($playlistEdit->playlist_name))? $playlistEdit->playlist_name:""; ?>"  />
                                <span id="playlistnameerrormessage" style="display: block;color:red; "></span>
                            </td>
                            </tr>
<!--                            <tr>
                                <th scope="row"><?php _e('Description', 'video_gallery') ?></th>
                                <td>
                                    <textarea id="description" name="playlistdescription" rows="8" cols="52"  ><?php echo (isset($playlistEdit->playlist_desc))? $playlistEdit->playlist_desc:""; ?></textarea>
                                </td>
                            </tr>-->
                            <tr>
                                <th scope="row"><?php _e('Publish', 'video_gallery') ?></th>
                                <td>
                                    <input type="radio" name="ispublish" id="publish_yes" <?php if (isset($playlistEdit->is_publish) && $playlistEdit->is_publish == 1) {  echo "checked"; } ?> value="1"> <label><?php _e('Yes', 'video_gallery'); ?></label>
                                    <input type="radio" name="ispublish" id="ispublish_no" <?php if (isset($playlistEdit->is_publish) && $playlistEdit->is_publish == 0) { echo "checked"; } ?> value="0"><label> <?php _e('No', 'video_gallery'); ?></label>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="ordering" id="ordering" value="<?php echo $Playlistorder+1; ?>">
                    <?php       if(isset($playListId)){ ?>
                    <input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php _e('Update Category', 'video_gallery'); ?>" class="button" /></p> <?php }  else{?>
                    <input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php _e('Add Category', 'video_gallery'); ?>" class="button" /></p> <?php }  ?>
                    </form>
                </div>
            </div>
        </div>
        <p>
    </div>
</div>
 <script type="text/javascript">
document.getElementById("publish_yes").checked = true;
 </script>
