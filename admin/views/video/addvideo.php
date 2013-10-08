<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Add video view file.
  Version: 2.3.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
?>
<!-- Add A Video -->
<?php 
$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];
?>
<script type="text/javascript">
    folder  = '<?php echo $dirPage; ?>'
</script>
<?php
$act_vid = 0;
$site_url = get_option('siteurl');
if (isset($_GET['videoId']))
    $act_vid = (int) $_GET['videoId'];
?>
<?php  if ($displayMsg): ?>
                <div class="updated below-h2">
                <p>
                <?php echo $displayMsg; ?>
                </p>
                </div>
                <?php endif; ?>
<div class="apptha_gallery">
<div class="wrap">
    <script type="text/javascript" src="../wp-content/plugins/<?php echo $dirPage; ?>/admin/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../wp-content/plugins/<?php echo $dirPage; ?>/admin/js/jquery-ui-1.7.1.custom.min.js"></script>

    <script type="text/javascript">
        function t1(t2)
        { 
            if(t2.value == "y" || t2 == "y")
            {
                document.getElementById('upload2').style.display = "block";
                document.getElementById('supportformats').style.display = "";
                document.getElementById('ffmpeg_disable_new4').style.display = "";
                document.getElementById('ffmpeg_disable_new2').style.display = "";
                document.getElementById('ffmpeg_disable_new1').style.display = "";
                document.getElementById('youtube').style.display = "none";
                document.getElementById('embedvideo').style.display = "none";
                document.getElementById('customurl').style.display = "none";
            } else if(t2.value == "c" || t2 == "c"){
                document.getElementById('youtube').style.display = "block";
                document.getElementById('upload2').style.display = "none";
                document.getElementById('embedvideo').style.display = "none";
                document.getElementById('customurl').style.display = "none";
            } else if(t2.value == "url" || t2 == "url"){
                document.getElementById('customurl').style.display = "block";
                document.getElementById('embedvideo').style.display = "none";
                document.getElementById('islive_visible').style.display = "none";
                document.getElementById('stream1').style.display = "none";
                document.getElementById('hdvideourl').style.display = "";
                document.getElementById('youtube').style.display = "none";
                document.getElementById('upload2').style.display = "none";
            } else if(t2.value == "rtmp" || t2 == "rtmp"){
                document.getElementById('customurl').style.display = "block";
                document.getElementById('islive_visible').style.display = "";
                document.getElementById('stream1').style.display = "";
                document.getElementById('embedvideo').style.display = "none";
                document.getElementById('hdvideourl').style.display = "none";
                document.getElementById('youtube').style.display = "none";
                document.getElementById('upload2').style.display = "none";
            } else if(t2.value == "embed" || t2 == "embed"){
                document.getElementById('embedvideo').style.display = "block";
                document.getElementById('islive_visible').style.display = "";
                document.getElementById('stream1').style.display = "";
                document.getElementById('customurl').style.display = "none";
                document.getElementById('hdvideourl').style.display = "none";
                document.getElementById('youtube').style.display = "none";
                document.getElementById('adstypebox').style.display = "none";
                document.getElementById('upload2').style.display = "block"
                document.getElementById('supportformats').style.display = "none";
                document.getElementById('ffmpeg_disable_new4').style.display = "none";
                document.getElementById('ffmpeg_disable_new2').style.display = "none";
                document.getElementById('ffmpeg_disable_new1').style.display = "none";
            }
        }

        function savePlaylist(playlistName , mediaId){
            var name = playlistName.value;
            $.ajax({
                type: "GET",
                url: "admin.php?page=ajaxplaylist",
                data: "name="+name+"&media="+mediaId,
                success: function(msg){
                    var response = msg.split('##');
                    document.getElementById('playlistchecklist').innerHTML = msg;
                }
            });
        }
    </script>



    <h2> <?php _e('Add a new video file', 'video_gallery'); ?> </h2>

    <div id="poststuff" class="has-right-sidebar">
        <?php if (isset($get_key) && $get_title != $get_key) {
        ?>
            <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
                <img src="<?php echo $site_url . '/wp-content/plugins/' . $folder . '/images/buynow.png'; ?>" style="float:right;margin-top: 4px;" width="125" height="28"  height="43" /></a>
        <?php } ?>
        <div class="stuffbox videoform" name="youtube" >
            <h3 class="hndle videoform_title">
                <span><input type="radio" name="agree" id="btn2" value="c" onClick="t1(this)" /> <?php _e('YouTube URL', 'video_gallery'); ?></span>
                <span><input type="radio" name="agree" id="btn1" value="y" onClick="t1(this)" /> <?php _e('Upload file', 'video_gallery'); ?></span>
                <span><input type="radio" name="agree" id="btn3" value="url" onClick="t1(this)" /> <?php _e('Custom URL', 'video_gallery'); ?></span>
                <span><input type="radio" name="agree" id="btn4" value="rtmp" onClick="t1(this)" /> <?php _e('RTMP', 'video_gallery'); ?></span>
                <?php if(isset($settingsGrid->license) && strlen($settingsGrid->license) == 31){ ?>
                <span><input type="radio" name="agree" id="btn5" value="embed" onClick="t1(this)" /> <?php _e('Embed Video', 'video_gallery'); ?></span>
            <?php } ?>
            </h3>
                    

            <form method=post>
                <div id="youtube" class="rtmp_inside inside" >
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Video URL', 'video_gallery') ?></th>
                            <td class="rtmp_td"><input type="text" size="50" name="filepath" value="<?php
        if (isset($videoEdit->file_type)

            )if ($videoEdit->file_type == 1)
                echo $videoEdit->link
        ?>" id="filepath1" onkeyup="generate12(this.value);" />&nbsp;&nbsp<input id="generate" type="submit" name="youtube_media" class="button-primary" value="<?php _e('Generate details', 'video_gallery'); ?>" />
                                <span id="Youtubeurlmessage" style="display: block; "></span>
                                <p><?php _e('Here you need to enter the video URL', 'video_gallery') ?></p>
                                <p><?php _e('It accepts Youtube links like : http://www.youtube.com/watch?v=tTGHCRUdlBs or http://youtu.be/tTGHCRUdlBs', 'video_gallery') ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div id="embedvideo" class="rtmp_inside inside" >
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Embed Code', 'video_gallery') ?></th>
                            <td class="rtmp_td">
                                <textarea id="embedcode" name="embedcode" rows="5" cols="60"><?php if (isset($videoEdit->embedcode))echo stripslashes($videoEdit->embedcode); ?></textarea>
                             <span id="embedmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="customurl" class="rtmp_inside" style="margin:15px;">
                    <table class="form-table">
                        <tr id="stream1" >
                            <th scope="row"><?php _e('Streamer Path', 'video_gallery') ?></th>
                            <td class="rtmp_td">
                                <input type="text" name="streamname"  id="streamname" value="<?php
                                       if (isset($videoEdit->file_type) && $videoEdit->file_type == 4)
                                               echo $videoEdit->streamer_path; ?>" />

                                <span id="streamermessage" style="display: block;"></span>
                                    <p><?php _e('Here you need to enter the RTMP Streamer Path', 'video_gallery') ?></p>
                            </td>

                                </tr>
                                <tr id="islive_visible">
                                    <th scope="row"><?php _e('Is Live', 'video_gallery') ?></th>
                                    <td>
                                        <input type="radio" style="float:none;" name="islive"  id="islive2" <?php
                                               if (isset($videoEdit->islive) && $videoEdit->islive == '1') {
                                                   echo 'checked="checked" ';
                                               }
                                               ?>  value="1" /><label><?php _e('Yes', 'video_gallery') ?></label>
                                        <input type="radio" style="float:none;" name="islive"  id="islive1"  <?php
                                               if (isset($videoEdit->islive) && $videoEdit->islive == '0' || $videoEdit == '') {
                                                   echo 'checked="checked" ';
                                               }
        ?>  value="0" /><label><?php _e('No', 'video_gallery') ?></label>
                                        <span id="rtmplivemessage" style="display: block;"></span>
                                        <p><?php _e('Here you need to select whether your RTMP video is a live video or not', 'video_gallery') ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php _e('Video URL', 'video_gallery') ?></th>
                                    <td class="rtmp_td"><input type="text" size="50" name="filepath2" id="filepath2" value="<?php
                                               if (isset($videoEdit->file_type)

                                                   )if ($videoEdit->file_type == 3 || $videoEdit->file_type == 4)
                                                       echo $videoEdit->file
        ?>"/>
                                                <span id="videourlmessage" style="display: block;"></span>
                                                <p><?php _e('Here you need to enter the video URL', 'video_gallery') ?></p>
                                        </td></tr>
                                        <tr id="hdvideourl"><th scope="row"><?php _e('HD Video URL (Optional)', 'video_gallery') ?></th>
                                            <td class="rtmp_td"><input type="text" size="50" name="filepath3" id="filepath3" value="<?php
                                                       if (isset($videoEdit->file_type)

                                                           )if ($videoEdit->file_type == 3 || $videoEdit->file_type == 4)
                                                               echo $videoEdit->hdfile
        ?>"/><span id="videohdurlmessage" style="display: block;"></span>
                                                        <p><?php _e('Here you need to enter the HD video URL ', 'video_gallery') ?></p>
                                                    </td>
                                                </tr>
                                                <tr><th scope="row"><?php _e('Thumb Image URL', 'video_gallery') ?></th>
                                                    <td class="rtmp_td"><input type="text" size="50" name="filepath4" id="filepath4" value="<?php
                                                               if (isset($videoEdit->file_type)

                                                                   )if ($videoEdit->file_type == 3 || $videoEdit->file_type == 4)
                                                                       echo $videoEdit->image
        ?>"/>
                                                                <span id="thumburlmessage" style="display: block;"></span>
                                                                <p><?php _e('Here you need to enter the URL of thumb image', 'video_gallery') ?></p>
                                                        </td>
                                                        </tr>
                                                        <tr><th scope="row"><?php _e('Preview Image URL (Optional)', 'video_gallery') ?></th>
                                                            <td class="rtmp_td"><input type="text" size="50" name="filepath5" id="filepath5" value="<?php
                                                                       if (isset($videoEdit->file_type)

                                                                           )if ($videoEdit->file_type == 3 || $videoEdit->file_type == 4)
                                                                               echo $videoEdit->opimage
        ?>"/><span id="previewurlmessage" style="display: block;"></span>
                                                                       <p><?php _e('Here you need to enter the URL of preview image', 'video_gallery') ?></p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>

                                                    </form>
                                                    <div id="upload2" class="inside" style="margin:15px;">
                <div id="supportformats"><?php _e('<b>Supported video formats:</b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'video_gallery') ?></div>
                                                                               <table class="form-table">
                                                                                   <tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1"><td style="vertical-align: middle;"><?php _e('Upload Video', 'video_gallery') ?></td>
                                                                                       <td>
                                                                                           <div id="f1-upload-form" >
                                                                                               <form name="normalvideoform" method="post" enctype="multipart/form-data" >
                                                                                                   <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                                                                   <input type="button" class="button" name="uploadBtn" value="<?php _e('Upload Video', 'video_gallery') ?>" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                                                                   <input type="hidden" name="mode" value="video" />
                                                                                                   <label id="lbl_normal"><?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->file; ?></label>
                                                                                               </form>
                                                                                           </div>
                                                                                           <span id="uploadmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
                                                                                           <div id="f1-upload-progress" style="display:none">
                                                                                               <div style="float:left"><img id="f1-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/'.$dirPage.'/images/empty.gif' ?>" alt="Uploading"  style="padding-top:2px"/>
                                                                                                   <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f1-upload-filename">PostRoll.flv</label></div>
                                                                                               <div style="float:right"> <span id="f1-upload-cancel">
                                                                                                       <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('normalvideoform');" name="submitcancel">Cancel</a>
                                                                                                   </span>
                                                                                                   <label id="f1-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                                                                   <span id="f1-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                                                                       <b><?php _e('Upload Failed:', 'video_gallery') ?></b> <?php _e('User Cancelled the upload', 'video_gallery') ?>
                                                                                                   </span></div>


                                                                                           </div>
                                                                                       </td></tr>

                                                                                   <tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new1"> <td><?php _e('Upload HD Video (Optional)', 'video_gallery') ?></td>
                                                                                       <td>
                                                                                           <div id="f2-upload-form" >
                                                                                               <form name="hdvideoform" method="post" enctype="multipart/form-data" >
                                                                                                   <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                                                                   <input type="button" class="button" name="uploadBtn" value="Upload Video" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                                                                   <input type="hidden" name="mode" value="video" />
                                                                                                   <label id="lbl_normal"><?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->hdfile; ?></label>
                                                                                               </form>
                                                                                           </div>

                                                                                           <div id="f2-upload-progress" style="display:none">
                                                                                               <div style="float:left"><img id="f2-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/'.$dirPage.'/images/empty.gif' ?>" alt="Uploading"  style="padding-top:2px" />
                                                                                                   <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f2-upload-filename">PostRoll.flv</label></div>
                                                                                               <div style="float:right"><span id="f2-upload-cancel">
                                                                                                       <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('hdvideoform');" name="submitcancel">Cancel</a>

                                                                                                   </span>
                                                                                                   <label id="f2-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                                                                   <span id="f2-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                                                                       <b><?php _e('Upload Failed:', 'video_gallery') ?></b> <?php _e('User Cancelled the upload', 'video_gallery') ?>
                                                                                                   </span></div>

                                                                                           </div>

                                                                                       </td></tr>



                                                                                   <tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new1"><td><?php _e('Upload Thumb Image', 'video_gallery') ?></td><td>
                                                                                           <div id="f3-upload-form" >
                                                                                               <form name="thumbimageform" method="post" enctype="multipart/form-data" >
                                                                                                   <input type="file" name="myfile"  onchange="enableUpload(this.form.name);" />
                                                                                                   <input type="button" class="button" name="uploadBtn" value="Upload Image"  disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                                                                   <input type="hidden" name="mode" value="image" />
                                                                                                   <label id="lbl_normal"><?php if (isset($videoEdit->file_type) && ($videoEdit->file_type == 2 || $videoEdit->file_type == 5))
                                                                                   echo $videoEdit->image; ?></label>
                                                                                               </form>
                                                                                           </div>
                                                                                           <span id="uploadthumbmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
                                                                                           <div id="f3-upload-progress" style="display:none">
                                                                                               <div style="float:left"><img id="f3-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/'.$dirPage.'/images/empty.gif' ?>" alt="Uploading" style="padding-top:2px" />
                                                                                                   <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f3-upload-filename">PostRoll.flv</label></div>
                                                                                               <div style="float:right"> <span id="f3-upload-cancel">
                                                                                                       <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('thumbimageform');" name="submitcancel">Cancel</a>
                                                                                                   </span>
                                                                                                   <label id="f3-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                                                                   <span id="f3-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                                                                      <b><?php _e('Upload Failed:', 'video_gallery') ?></b> <?php _e('User Cancelled the upload', 'video_gallery') ?>
                                                                                                   </span></div>

                                                                                           </div>

                                                                                       </td></tr>

                                                                                   <tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new1"><td><?php _e('Upload Preview Image (Optional)', 'video_gallery') ?></td><td>
                                                                                           <div id="f4-upload-form" >
                                                                                               <form name="previewimageform" method="post" enctype="multipart/form-data" >
                                                                                                   <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                                                                   <input type="button" class="button" name="uploadBtn" value="Upload Image" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                                                                   <input type="hidden" name="mode" value="image" />
                                                                                                   <label id="lbl_normal"><?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->opimage; ?></label>
                                                                                               </form>
                                                                                           </div>
                                                                                           <div id="f4-upload-progress" style="display:none">
                                                                                               <div style="float:left"><img id="f4-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/'.$dirPage.'/images/empty.gif' ?>" alt="Uploading" style="padding-top:2px" />
                                                                                                   <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f4-upload-filename">PostRoll.flv</label></div>
                                                                                               <div style="float:right"><span id="f4-upload-cancel">
                                                                                                       <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('previewimageform');" name="submitcancel">Cancel</a>
                                                                                                   </span>
                                                                                                   <label id="f4-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                                                                   <span id="f4-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                                                                       <b><?php _e('Upload Failed:', 'video_gallery') ?></b> <?php _e('User Cancelled the upload', 'video_gallery') ?>
                                                                                                   </span></div>


                                                                                           </div>
                                                                                           <div id="nor"><iframe id="uploadvideo_target" name="uploadvideo_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></div>
                                                                                       </td></tr>

                                                                               </table>
                                                                           </div>
                                                                       </div>
                                                                   </div>

                                                                   <form name="table_options" enctype="multipart/form-data" method="post" id="video_options" onsubmit="return chkbut()">
                                                                       <div id="poststuff" class="has-right-sidebar">
                                                                           <input type="hidden" name="normalvideoform-value" id="normalvideoform-value" value="<?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->file; ?>"  />
                                                                           <input type="hidden" name="hdvideoform-value" id="hdvideoform-value" value="<?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->hdfile; ?>" />
                                                                           <input type="hidden" name="thumbimageform-value" id="thumbimageform-value"  value="<?php if (isset($videoEdit->file_type) && ($videoEdit->file_type == 2 || $videoEdit->file_type == 5))
                                                                                   echo $videoEdit->image; ?>" />
                                                                           <input type="hidden" name="previewimageform-value" id="previewimageform-value"  value="<?php if (isset($videoEdit->file_type) && $videoEdit->file_type == 2)
                                                                                   echo $videoEdit->opimage; ?>" />
                                                                           <input type="hidden" name="youtube-value" id="youtube-value"  value="" />
                                                                           <input type="hidden" name="streamerpath-value" id="streamerpath-value" value="" />
                                                                           <input type="hidden" name="embed_code" id="embed_code" value="" />
                                                                           <input type="hidden" name="islive-value" id="islive-value" value="0" />
                                                                           <input type="hidden" name="customurl" id="customurl1"  value="" />
                                                                           <input type="hidden" name="customhd" id="customhd1"  value="" />
                                                                           <input type="hidden" name="customimage" id="customimage"  value="" />
                                                                           <input type="hidden" name="custompreimage" id="custompreimage"  value="" />
                                                                           <!-- Start of sidebar  -->
                                                                           <div class="inner-sidebar" >
                                                                               <div id="submitdiv" class="postbox">
                                                                                   <h3 class="hndle" style="color:white;background:none;background-color:black"><span><?php _e('Category', 'video_gallery') ?></span></h3>
                                                                                   <div class="inside" style="color:blue" >
                                                                                       <div id="submitpost" class="submitbox">

                                                                                           <div class="misc-pub-section">
                                                                                               <h4><?php _e('Category', 'video_gallery'); ?>&nbsp;&nbsp;
                                                                                                   <a style="cursor:pointer"  onclick="playlistdisplay()"><?php _e('Create New', 'video_gallery') ?></a></h4>
                                                                                               <div id="playlistcreate1"><?php _e('Name', 'video_gallery'); ?><input type="text" size="20" name="p_name" id="p_name" value="" />
                                                                                                   <input type="button" class="button-primary" name="add_pl1" value="<?php _e('Add'); ?>" onclick="return savePlaylist(document.getElementById('p_name') , <?php echo $act_vid ?>);" class="button button-highlighted" />
                                                                                                   <a style="cursor:pointer;margin: 5px 0 0 175px;display: inline-block;text-decoration: underline;" onclick="playlistclose()"><b>Close</b></a></div>
                                                                                               <div id="jaxcat"></div>
                                                                                               <div id="playlistchecklist"><?php $ajaxplaylistOBJ->get_playlist(); ?></div>
                                                                                           </div>
                                                                                       </div>
                                                                                   </div>
                                                                               </div>

                                                                           </div>
                                                                           <!-- End of sidebar -->
                                                                           <div id="post-body" class="has-sidebar"><br>
                                                                               <div id="post-body-content" class="has-sidebar-content">

                                                                                   <div class="stuffbox">
                                                                                       <h3 class="hndle"><span><?php _e('Enter Title / Name', 'video_gallery'); ?></span></h3>
                                                        <div class="inside" style="margin:15px;">
                                                            <table class="form-table">
                                                                <tr>
                                                                    <th scope="row"><?php _e('Title / Name', 'video_gallery') ?></th>
                                                                    <td><input value="<?php if (isset($videoEdit->name)

                                                                                   )echo $videoEdit->name; ?>" type="text" size="50" maxlength="200" name="name" id="name" />
                                                                           <span id="titlemessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
                                                                       </td>
                                                                   </tr>
                                                                   <tr>
                                                                       <th scope="row"><?php _e('Description', 'video_gallery') ?></th>
                                                                       <td><textarea id="description" name="description" rows="5" cols="60"><?php if (isset($videoEdit->description)

                                                                                   )echo $videoEdit->description; ?></textarea></td>
                                                                       </tr>
                                                                       <tr>
                                                                    <th scope="row"><?php _e('Tags / Keywords', 'video_gallery') ?></th>
                                                                    <td><input value="<?php if (isset($videoEdit->tags_name)

                                                                                   )echo $videoEdit->tags_name; ?>" type="text" size="50" maxlength="200" name="tags_name" id="tags_name" />
                                                                        </td>
                                                                   </tr>
                                                                       <tr>
                                                                           <th scope="row"><?php _e('Featured Video', 'video_gallery') ?></th>
                                                                           <td>
                                                                               <input type="radio" id="feature_on" name="feature" <?php
                                                                               if (isset($videoEdit->featured) && $videoEdit->featured == '1') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="1"> <label>Yes</label>
                                                                        <input type="radio" id="feature_off" name="feature" <?php
                                                                               if (isset($videoEdit->featured) && $videoEdit->featured == '') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="0"> <label>No</label>

                                                                           </td>
                                                                       </tr>
                                                                       <tr>
                                                                           <th scope="row"><?php _e('Download', 'video_gallery') ?></th>
                                                                           <td>
                                                                               <input type="radio" id="download_on" name="download" <?php
                                                                               if (isset($videoEdit->download) && $videoEdit->download == '1') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="1"> <label>Yes</label>
                                                                        <input type="radio" id="download_off" name="download" <?php
                                                                               if (isset($videoEdit->download) && ($videoEdit->download == '' || $videoEdit->download == '0')) {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="0"> <label>No</label>
                                                                               <br/><?php _e('Note : Not supported for YouTube and Embed videos', 'video_gallery') ?>
                                                                           </td>
                                                                       </tr>
                                                                       <tr>
                                                                           <th scope="row"><?php _e('Publish', 'video_gallery') ?></th>
                                                                           <td>
                                                                               <input type="radio" id="publish_on" name="publish" <?php
                                                                               if (isset($videoEdit->publish) && $videoEdit->publish == '1') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="1"> <label>Yes</label>
                                                                        <input type="radio" id="publish_off" name="publish" <?php
                                                                               if (isset($videoEdit->publish) && $videoEdit->publish == '0') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="0"> <label>No</label>

                                                                                               </td>
                                                                                           </tr>
                                                                                       </table>
                                                                                   </div>
                                                                               </div>
                                                                               <!-- To display the list of pre roll ads -->
                    <?php
                                                                               //check whether preroll ads are enable
                                                                               // get the ads list
                                                                               global $wpdb;
                                                                               $tables = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads WHERE admethod='prepost'");

                                                                               $settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
                                                                               if ($settings[0]->preroll == 0 || $settings[0]->postroll == 0 || $settings[0]->midroll_ads == 0 || $settings[0]->imaAds == 1) {
                    ?>

                                                                           <div class="stuffbox" id="adstypebox">
                                                                               <h3 class="hndle"><span><?php _e('Select Ads', 'video_gallery'); ?></span></h3>
                                                                               <div class="inside" style="margin:15px;">
<?php if ($settings[0]->preroll == 0) { ?>
                                                                       <table class="form-table">
                                                                           <tr>
                                                                               <th scope="row"><?php _e('Preroll ads', 'video_gallery') ?></th>
                                                                                   <td>
                                                                                       <select name="prerollads" id="prerollads" >
                                                                                           <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                                                                               <option id="6<?php echo $table->ads_id; ?>" name="<?php echo $table->ads_id ?>" value="<?php echo $table->ads_id ?>" > <?php echo $table->title ?></option>
<?php } ?>
                                                                                       </select>
<?php
                                                                                       if (isset($videoEdit->prerollads)) {
                                                                                           echo '<script>document.getElementById("6' . $videoEdit->prerollads . '").selected="selected"</script>';
                                                                                       }
?>
                                                                                               </td>
                                                                                           </tr>
                                                                                       </table>
<?php } if ($settings[0]->postroll == 0) { ?>


                                                                                       <table class="form-table">

                                                                                           <tr>
                                                                                               <th scope="row"><?php _e('Postroll ads', 'video_gallery') ?></th>
                                                                                   <td>
                                                                                       <select name="postrollads" id="postrollads" >
                                                                                           <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                                                                               <option id="5<?php echo $table->ads_id; ?>" name="<?php echo $table->ads_id ?>" value="<?php echo $table->ads_id ?>" > <?php echo $table->title ?></option>
                            <?php } ?>
                                                                                                   </select>
<?php
                                                                                       if (isset($videoEdit->postrollads)) {
                                                                                           echo '<script>document.getElementById("5' . $videoEdit->postrollads . '").selected="selected"</script>';
                                                                                       }
?>
                                                                                                       </td>
                                                                                                   </tr>
                                                                                               </table>
                    <?php } ?>
<?php if ($settings[0]->midroll_ads == 0) { ?>


                                                                                       <table class="form-table">

                                                                                           <tr>
                                                                                               <th scope="row"><?php _e('Midroll Ad', 'video_gallery') ?></th>
                                                                                   <td>
                                                                               <input type="radio" id="midrollads_on" name="midrollads" <?php
                                                                               if (isset($videoEdit->midrollads) && $videoEdit->midrollads == '1') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="1"> <label>Enable</label>
                                                                        <input type="radio" id="midrollads_off" name="midrollads" <?php
                                                                               if (isset($videoEdit->midrollads) && $videoEdit->midrollads == '0') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="0"> <label>Disable</label>

                                                                                               </td>
                                                                                                   </tr>
                                                                                               </table>
                    <?php } ?>
<?php  if ($settings[0]->imaAds == 0) { ?>


                                                                                       <table class="form-table">

                                                                                           <tr>
                                                                                               <th scope="row"><?php _e('IMA Ad', 'video_gallery') ?></th>
                                                                                   <td>
                                                                               <input type="radio" id="imaad_on" name="imaad" <?php
                                                                               if (isset($videoEdit->imaad) && $videoEdit->imaad == '1') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="1"> <label>Enable</label>
                                                                        <input type="radio" id="imaad_off" name="imaad" <?php
                                                                               if (isset($videoEdit->imaad) && $videoEdit->imaad == '0') {
                                                                                   echo 'checked="checked"';
                                                                               }
                ?> value="0"> <label>Disable</label>

                                                                                               </td>
                                                                                                   </tr>
                                                                                               </table>
                    <?php } ?>

                                                                                       </div>
                                                                                   </div>
                    <?php } ?>


                                                                           </div>
                                                                           <p>
<?php
                                                                               $adminPage = filter_input(INPUT_GET, 'page');
                                                                               $videoId = filter_input(INPUT_GET, 'videoId');
                                                                               if ($adminPage == 'newvideo' && !empty($videoId)) {
                                                                                   $editbutton = 'Update video';
                                                                               } else {
                                                                                   $editbutton = 'Add video';
                                                                               }
?>
                                                                               <input type="submit" name="add_video" class="button-primary"  onclick="return validateInput();" value="<?php echo $editbutton; ?>" class="button" />
                                                                               <input type="button" onclick="window.location.href='admin.php?page=video'" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" />
                                                                           </p>
                                                                       </div>
                                                                   </div><!--END Poststuff -->
                                                               </form>




                                                               <script>
                                                                   document.getElementById('playlistcreate1').style.display = "none";
                                                                   document.getElementById("btn2").checked = true;
<?php if (isset($videoEdit->featured) && $videoEdit->featured == '1') { ?>
                                                                           document.getElementById("feature_on").checked = true;
<?php } else if (isset($videoEdit->featured) && $videoEdit->featured == '0') { ?>document.getElementById("feature_off").checked = true;
<?php } else { ?>document.getElementById("feature_on").checked = true;
<?php } ?>

<?php if (isset($videoEdit->download) && $videoEdit->download == '1') { ?>
                                                                           document.getElementById("download_on").checked = true;
<?php } else if (isset($videoEdit->download) && $videoEdit->download == '0') { ?>document.getElementById("download_off").checked = true;
<?php } else { ?>document.getElementById("download_off").checked = true;
<?php } ?>

<?php if (isset($videoEdit->publish) && $videoEdit->publish == '1') { ?>
                                                                           document.getElementById("publish_on").checked = true;
<?php } else if (isset($videoEdit->publish) && $videoEdit->publish == '0') { ?>document.getElementById("publish_off").checked = true;
<?php } else { ?>document.getElementById("publish_on").checked = true;
<?php } ?>

                                                                     document.getElementById('generate').style.visibility  = "hidden";
                                                                     function playlistdisplay()
                                                                     {
                                                                         document.getElementById('playlistcreate1').style.display = "block";
                                                                     }
                                                                     function playlistclose()
                                                                     {
                                                                         document.getElementById('playlistcreate1').style.display = "none";
                                                                     }

                                                                     function generate12(str1)
                                                                     {
                                                                         var theurl=str1;
                                                                        var theurl=document.getElementById("filepath1").value;
                                                                        var regExp = /^.*(youtu.be\/|v\/|embed\/|watch\?|youtube.com\/user\/[^#]*#([^\/]*?\/)*)\??v?=?([^#\&\?]*).*/;
                                                                        var match = theurl.match(regExp);
                                                                        if (match){
                                                                             document.getElementById('generate').style.visibility = "visible";
                                                                        }
                                                                         else document.getElementById('generate').style.visibility  = "hidden";
                                                                     }
                                                               </script>

                                                           </div><!--END wrap -->
                                                           </div><!--END wrap -->

<?php
                                                                               if (isset($_POST['youtube_media'])) {
                                                                                   $act1 = $videoOBJ->youtubeurl();
?>          <input type="hidden" name="act" id="act3" value="<?php if (isset($act1[3]))
                                                                                       echo $act1[3] ?>" />
                                                                                       <input type="hidden" name="act" id="act0" value="<?php echo stripslashes(str_replace('"', '', $act1[0])); ?>" />
                                                                                       <input type="hidden" name="act" id="act4" value="<?php echo $act1[4] ?>" />
                                                                                       <input type="hidden" name="act" id="act5" value="<?php if (isset($act1[5]))
                                                                                           echo $act1[5]; ?>" />
                                                                                       <input type="hidden" name="act" id="act6" value="<?php if (isset($act1[6]))
                                                                                           echo $act1[6] ?>" />
                                                                                           <script>
                                                                                               document.getElementById('name').value = document.getElementById('act0').value;
                                                                                               document.getElementById('filepath1').value = document.getElementById('act4').value;
                                                                                               document.getElementById('description').value = document.getElementById('act5').value;
                                                                                               document.getElementById('tags_name').value = document.getElementById('act6').value;
                                                                                           </script>
<?php } ?>
                                                                                       <script type="text/javascript">
<?php
                                                                                       if (isset($videoEdit->file_type) && $videoEdit->file_type == 1) {
?>
                                                                                           t1("c");
                                                                                           document.getElementById("btn2").checked = true;
<?php
                                                                                       } elseif (isset($videoEdit->file_type) && $videoEdit->file_type == 2) {
?>
                                                                                           t1("y");
                                                                                           document.getElementById("btn1").checked = true;
<?php
                                                                                       } elseif (isset($videoEdit->file_type) && $videoEdit->file_type == 3) {
?>
                                                                                           t1("url");
                                                                                           document.getElementById("btn3").checked = true;
<?php
                                                                                       } elseif (isset($videoEdit->file_type) && $videoEdit->file_type == 4) {
?>
                                                                                           t1("rtmp");
                                                                                           document.getElementById("btn4").checked = true;
<?php
                                                                                       } elseif (isset($videoEdit->file_type) && $videoEdit->file_type == 5) {
?>
                                                                                           t1("embed");
                                                                                           document.getElementById("btn5").checked = true;
<?php
                                                                                       }else{
                                                                                           ?>
                                                                                           t1("c");
                                                                                           document.getElementById("btn2").checked = true;
<?php
                                                                                       }
?>
</script>