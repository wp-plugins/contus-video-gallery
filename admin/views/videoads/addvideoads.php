<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Add video ads view file.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>
<div class="apptha_gallery">
<?php       if(isset($videoadId)){ ?>
<h2 class="option_title"><?php _e('Update Video Ad','digi'); ?></h2> <?php } else { ?> <h2  class="option_title"><?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "images/vid_ad.png' alt='move' width='30'/>"; ?><?php _e('Add a New Video Ad','digi'); ?></h2> <?php } ?>
<?php if (isset($msg)): ?>
<div class="updated below-h2">
    <p>
        <?php echo $msg;
            $url = get_bloginfo('url').'/wp-admin/admin.php?page=videoads';
            echo "<a href='$url' >Back to VideoAds</a>";
        ?>
    </p>
</div>
<?php endif; ?>
    <div id="post-body" class="has-sidebar">
        <div id="post-body-content" class="has-sidebar-content">
            <div class="stuffbox">
                <form action="" name="videoadsform" class="videoform" method="post" enctype="multipart/form-data"  >
                    <h3 class="hndle videoform_title">
                        <span>
                            <input type="radio" name="videoad" id="filebtn" value="1" checked="checked" onClick="Videoadtype()" /> File
                        </span>
                        <span>
                            <input type="radio" name="videoad" id="urlbtn" value="2" onClick="Videoadtype()" />  URL
                        </span>
                    </h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Title / Name', 'ads') ?></th>
                        <td>
                            <input type="text" size="50" maxlength="200" name="videoadname" id="name" value="<?php echo (isset($videoadEdit->title))? $videoadEdit->title:""; ?>"  />
                        </td>
                    </tr>
                </table>
                <div id="videoadurl" style="display: none;" ><table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('URL to video file', 'hdflv') ?></th>
                        <td>
                            <input type="text" size="50" name="videoadfilepath" id="videoadfilepath"  value="<?php echo (isset($videoadEdit->file_path))? $videoadEdit->file_path:""; ?>"  />&nbsp;&nbsp
                            <br /><?php _e('Here you need to enter the URL to the ads video file', 'hdflv') ?>
                            <br /><?php _e('It accept also a Youtube link: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'ads') ?>
                        </td>
                    </tr>
                    </table>
                </div>
                <div id="videoadfile" class="inside" style="display:block;">
                    <table class="form-table">
                    <tr >
                        <th style="padding-left: 0;" scope="row"><?php _e('Upload video', 'hdflv') ?></th>
                        <td>
                            <div id="f1-upload-form" >
                            <input type="file" name="videoadfile" value="<?php echo (isset($videoadEdit->file_path))? $videoadEdit->file_path:""; ?>" />
                            </div>
                            <?php _e('Supported video formats:( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflv') ?>
                        </td>
                    </tr>
                    </table>
                </div>
                <table class="form-table add_video_publish">
                    <tr>
                        <th scope="row"><?php _e('Publish', 'hdflvvideoshare') ?></th>
                        <td class="checkbox">
                           
                            <?php //echo $act_feature;   ?>
                            <input type="radio" name="videoadpublish"  value="1" <?php
                            if (isset($videoadEdit->publish)) {
                            echo "checked";
                            }
                            ?>><label>Yes</label>
                           
                           
                            <input type="radio" name="videoadpublish" value="0"  <?php
                            if (!isset($videoadEdit->publish)) {
                            echo "checked";
                            }
                            ?>><label>No</label>
                            
                        </td>
                    </tr>
                </table>

                <?php       if(isset($videoadId)){ ?>
                <input type="submit" name="videoadsadd" class="button-primary"  value="<?php _e('Update Video Ad', 'ads'); ?>" class="button" /> <?php } else { ?> <input type="submit" name="videoadsadd" class="button-primary"  value="<?php _e('Add Video Ad', 'ads'); ?>" class="button" /> <?php } ?>

                </form>
            </div>
        </div>
    </div>
</div>
