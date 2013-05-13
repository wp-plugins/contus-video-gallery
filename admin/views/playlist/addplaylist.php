<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: add playlist model file.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>
<div class="apptha_gallery">
<?php       if(isset($playListId)){ ?>
<h2><?php _e('Update Playlist','digi'); ?></h2> <?php } else { ?> <h2  class="option_title"><?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "images/vid_ad.png' alt='move' width='30'/>"; ?><?php _e('Add a New Playlist','digi'); ?></h2> <?php } ?>
    <?php if ($displayMsg): ?>
    <div class="updated below-h2">
        <p>
            <?php echo $displayMsg;
            $url = get_bloginfo('url').'/wp-admin/admin.php?page=playlist';
            echo "<a href='$url' >Back to Playlists</a>";
            ?>
        </p>
    </div>
    <?php endif; ?>
    <div id="post-body" class="has-sidebar">
        <div id="post-body-content" class="has-sidebar-content">
            <div class="stuffbox">
                <h3 class="hndle"><span><?php _e('Enter Title / Name', 'ads'); ?></span></h3>
                <div class="inside" style="margin:15px;">
                    <form name="adsform" method="post" enctype="multipart/form-data" >
                        <table class="form-table">
                            <tr>
                            <th scope="row"><?php _e('Title / Name', 'ads') ?></th>
                            <td>
                                <input type="text" size="50" maxlength="200" name="playlistname" value="<?php echo (isset($playlistEdit->playlist_name))? $playlistEdit->playlist_name:""; ?>"  />
                            </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Description', 'hdflvvideoshare') ?></th>
                                <td>
                                    <textarea id="description" name="playlistdescription" rows="8" cols="52"  ><?php echo (isset($playlistEdit->playlist_desc))? $playlistEdit->playlist_desc:""; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Publish', 'hdflvvideoshare') ?></th>
                                <td>                                   
                                    <input type="radio" name="ispublish" <?php if ($playlistEdit->is_publish == 1) {  echo "checked"; } ?> value="1"> <label>Yes</label>
                                    <input type="radio" name="ispublish" <?php if ($playlistEdit->is_publish == 0) { echo "checked"; } ?> value="0"><label> No</label>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="ordering" id="ordering" value="<?php echo $Playlistorder+1; ?>">
                    <?php       if(isset($playListId)){ ?>
                    <input type="submit" name="playlistadd" class="button-primary"  value="<?php _e('Update playlist', 'ads'); ?>" class="button" /></p> <?php }  else{?>
                    <input type="submit" name="playlistadd" class="button-primary"  value="<?php _e('Add playlist', 'ads'); ?>" class="button" /></p> <?php }  ?>
                    </form>
                </div>
            </div>
        </div>
        <p>
    </div>
</div>