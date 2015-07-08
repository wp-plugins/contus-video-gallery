<?php
/**
 * Video gallery admin addvideo form and update form for video added. 
 * 
 * Add video with multiple method 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8.1
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
?>
<!-- Add A Video -->
<?php
$dir     = dirname( plugin_basename( __FILE__ ) );
$dirExp  = explode( '/', $dir );
$dirPage = $dirExp[0];
?>
<script type="text/javascript">
	folder = '<?php echo balanceTags( $dirPage ); ?>';
    var	videogallery_plugin_folder =  '<?php echo plugins_url().'/'.$dirPage ; ?>' ;
    var upload_nonce = '<?php  echo wp_create_nonce( 'upload-video');?>';
</script>
<?php
$act_vid = 0;
$video_description = '';
$site_url = get_option( 'siteurl' );
if ( isset( $_GET['videoId'] ) ) {
	$act_vid = ( int ) $_GET['videoId'];
} 
?>
	<?php if ( $displayMsg ) { ?>
		<div class="updated below-h2">
			<p>
				<?php echo balanceTags( $displayMsg ); ?>
			</p>
		</div>
	<?php } ?>
<div class="apptha_gallery">
	<div class="wrap">
		<script type="text/javascript">
			function savePlaylist( playlistName, mediaId ) {
				var name = playlistName.value;
				name = name.trim();
				document.getElementById('jaxcat').innerHTML="";
				var playlistajax = jQuery.noConflict();
				if(name == '' ){
				  document.getElementById('jaxcat').innerHTML="<p>Enter the playlist name </p>";	
                  return false;
				}
				playlistajax.ajax( {
					type: "GET",
					url: "admin.php?page=ajaxplaylist",
					data: "name=" + name + "&media=" + mediaId,
					success: function( msg ) {
						var response = msg.split( '##' );
						document.getElementById( 'playlistchecklist' ).innerHTML = msg;
					}
				} );
			}
			function getyoutube_details(){
	               var youtube_url =  document.getElementById("filepath1").value;
	               if(youtube_url.indexOf('youtube') != -1) {
		               var video_id = youtube_url.split('v=')[1];
		               var ampersandPosition = video_id.indexOf('&');
		               if(ampersandPosition != -1) {
		                 video_id = video_id.substring(0, ampersandPosition);
		               }
	               } else if(youtube_url.indexOf('youtu.be') != -1) {
		               var video_id = youtube_url.split('/')[3];
	               }
	               var urlmatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
	               var errormsg = "<p>Enter Valid Video URL</p>";
	               if( !urlmatch.test(youtube_url) ){
	            	   document.getElementById('Youtubeurlmessage').innerHTML = errormsg;
	            	   document.getElementById('Youtubeurlmessage').style.display = "block";
	            	   return false;
		           }
	               var playlistajax = jQuery.noConflict();
	               document.getElementById('loading_image').style.display ="block";         
		           var requesturl = '<?php echo admin_url('admin-ajax.php?action=getyoutubedetails'); ?>'; 
		           playlistajax.ajax({
	                       url:requesturl,
	                       type:"GET",
	                       data:"filepath="+ video_id,
	                       success : function( msg ){
                         var resultdata =  playlistajax.parseJSON(msg);
                         document.getElementById( 'name' ).value = resultdata[0];
                     	   document.getElementById( 'filepath1' ).value = resultdata[4];
                     		var tag_name = resultdata[6];

                     	   if(resultdata[5] !== undefined){
                     		tinymce.activeEditor.setContent(resultdata[5]);
                     		tinymce.execCommand('mceAddControl',true,'description');
                     	   }

                     	   if( tag_name !== undefined ) {	   
                     	   	 document.getElementById( 'tags_name' ).value = resultdata[6];
                     	   }	                      
	                    	   document.getElementById( 'embedvideo').style.display = "none";
                         document.getElementById('loading_image').style.display ='none';
                      }  
	               } ); 
	           }
			
		</script>
		<?php
		$adminPage = filter_input( INPUT_GET, 'page' );
		$videoId   = filter_input( INPUT_GET, 'videoId' );
		if ( $adminPage == 'newvideo' && ! empty( $videoId ) ) {
			$editbutton = 'Update';
			$page_title = 'Edit video';
		} else {
			$editbutton = 'Save';
			$page_title = 'Add a new video';
		}
		
		/**
		 * Function get user roles 
		 */
		function get_current_user_role() {
			global $current_user;
			get_currentuserinfo();
			$user_roles = $current_user->roles;
			$user_role  = array_shift( $user_roles );
			return $user_role;
		}
		$user_role = get_current_user_role();
		$player_colors = unserialize($settingsGrid->player_colors);
		$user_allowed_method = explode(',',$player_colors['user_allowed_method']);
      ?>
		<h2 class="option_title"> <?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . 'images/manage_video.png" alt="move" width="30"/>'; ?><?php echo esc_attr_e( $page_title, 'video_gallery' ); ?> </h2>
		<div id="poststuff" class="has-right-sidebar">
			<?php if ( isset( $get_key ) && $get_title != $get_key ) {
				?>
				<a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
					<img src="<?php echo plugins_url() . $folder . '/images/buynow.png'; ?>" style="float:right;margin-top: 4px;" width="125" height="28"  height="43" /></a>
			<?php } ?>
			<div class="stuffbox videoform" name="youtube" >
				<h3 class="hndle videoform_title">
				    <?php if(in_array('c', $user_allowed_method) || $user_role == 'administrator') { ?>
					 	<span><input type="radio" name="agree" id="btn2" value="c" onClick="t1( this )" /> <?php esc_attr_e( 'YouTube URL / Viddler / Dailymotion', 'video_gallery' ); ?></span>
					<?php } ?>
					<?php if(in_array('y', $user_allowed_method) || $user_role == 'administrator'){?>
						<span><input type="radio" name="agree" id="btn1" value="y" onClick="t1( this )" /> <?php esc_attr_e( 'Upload file', 'video_gallery' ); ?></span>
					<?php } ?>
					<?php if(in_array('url', $user_allowed_method) || $user_role == 'administrator') { ?>
						<span><input type="radio" name="agree" id="btn3" value="url" onClick="t1( this )" /> <?php esc_attr_e( 'Custom URL', 'video_gallery' ); ?></span>
					<?php } ?>
					<?php if(in_array('rmtp', $user_allowed_method) || $user_role == 'administrator'){ ?>
						<span><input type="radio" name="agree" id="btn4" value="rtmp" onClick="t1( this )" /> <?php esc_attr_e( 'RTMP', 'video_gallery' ); ?></span>
					<?php } ?>
					<?php if ( isset( $settingsGrid->license ) && ( strpos( $settingsGrid->license ,'CONTUS' ) ) ) { ?>
					<?php if(in_array('embed', $user_allowed_method) || $user_role == 'administrator') {?>
				  		<span><input type="radio" name="agree" id="btn5" value="embed" onClick="t1( this )" /> <?php esc_attr_e( 'Embed Video', 'video_gallery' ); ?></span>
					 <?php } ?>
					<?php  } ?>
				</h3>


				<form method=post>
					<div id="youtube" class="rtmp_inside inside">
						<table class="form-table">
							<tr>
								<th scope="row"><?php esc_attr_e( 'Video URL', 'video_gallery' ) ?></th>
								<td class="rtmp_td"><input type="text" class="youtubelinkinput" name="filepath" size="50" value="<?php
											if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 1 ) {
												echo balanceTags( $videoEdit->link );
											}
											?>" id="filepath1" onkeyup="generate12( this.value );" />&nbsp;&nbsp<input id="generate" type="button" name="youtube_media" class="button-primary" value="<?php esc_attr_e( 'Generate details', 'video_gallery' ); ?>" onClick="return getyoutube_details();" />
									<div id="loading_image" align="center" style="display:none;"><img src="<?php echo plugins_url($dirPage.'/images/ajax-loader.gif');?>" /></div>
									<span id="Youtubeurlmessage" style="display: block; "></span>
									<div class="youtubelinkinfo"><p><?php esc_attr_e( 'Here you need to enter the video URL', 'video_gallery' ) ?></p>
									<p><?php esc_attr_e( 'It accepts YouTube links like : https://www.youtube.com/watch?v=-umZJqaBY8Y or http://youtu.be/0vrdgDdPApQ', 'video_gallery' ) ?></p>
									<p><?php esc_attr_e( 'You need to enter YouTube API in settings tab for Youtube videos', 'video_gallery' ) ?></p>
									<p><?php esc_attr_e( 'Viddler link like : http://www.viddler.com/v/67b33b8f', 'video_gallery' ) ?></p>
									<p><?php esc_attr_e( 'Dailymotion link like : http://www.dailymotion.com/video/x16787y_nature-catskills_news', 'video_gallery' ) ?></p>
								</div>
								</td>
							</tr>
						</table>
					</div>

					<div id="embedvideo" class="rtmp_inside inside" style="display:none;">
						<table class="form-table">
							<tr>
								<th scope="row"><?php esc_attr_e( 'Embed Code', 'video_gallery' ) ?></th>
								<td class="rtmp_td">
									<textarea id="embedcode" name="embedcode" rows="5" cols="60"><?php if ( isset( $videoEdit->embedcode ) ) echo balanceTags( stripslashes( $videoEdit->embedcode ) ); ?></textarea>
									<span id="embedmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
								</td>
							</tr>
						</table>
					</div>

					<div id="customurl" class="rtmp_inside inside">
						<table class="form-table">
							<tr id="stream1" >
								<th scope="row"><?php esc_attr_e( 'Streamer Path', 'video_gallery' ) ?></th>
								<td class="rtmp_td">
									<input type="text" name="streamname"  id="streamname" onkeyup="validatestreamurl();" value="<?php
									if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 4 )
										echo balanceTags( $videoEdit->streamer_path );
									?>" />

									<span id="streamermessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to enter the RTMP Streamer Path', 'video_gallery' ) ?></p>
								</td>

							</tr>
							<tr id="islive_visible">
								<th scope="row"><?php esc_attr_e( 'Is Live', 'video_gallery' ) ?></th>
								<td>
									<input type="radio" style="float:none;" name="islive"  id="islive2" <?php if ( isset( $videoEdit->islive ) && $videoEdit->islive == '1' ) { echo 'checked="checked" '; } ?>  value="1" />
									<label><?php esc_attr_e( 'Yes', 'video_gallery' ) ?></label>
									<input type="radio" style="float:none;" name="islive"  id="islive1"  <?php if ( isset( $videoEdit->islive ) && ( $videoEdit->islive == '0' || $videoEdit == '' ) ) { echo 'checked="checked" '; } ?>  value="0" />
									<label><?php esc_attr_e( 'No', 'video_gallery' ) ?></label>
									<span id="rtmplivemessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to select whether your RTMP video is a live video or not', 'video_gallery' ) ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_attr_e( 'Video URL', 'video_gallery' ) ?></th>
								<td class="rtmp_td">
									<input type="text" size="50" name="filepath2" id="filepath2" onkeyup="validatevideourl();" value="<?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 3 || $videoEdit->file_type == 4 ) ) { echo balanceTags( $videoEdit->file ); } ?>"/>
									<span id="videourlmessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to enter the video URL', 'video_gallery' ) ?></p>
								</td></tr>
							<tr id="hdvideourl"><th scope="row"><?php esc_attr_e( 'HD Video URL ( Optional )', 'video_gallery' ) ?></th>
								<td class="rtmp_td"><input type="text" size="50" name="filepath3" id="filepath3" value="<?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 3 || $videoEdit->file_type == 4 ) ) { echo balanceTags( $videoEdit->hdfile ); } ?>"/>
									<span id="videohdurlmessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to enter the HD video URL ', 'video_gallery' ) ?></p>
								</td>
							</tr>
							<tr><th scope="row"><?php esc_attr_e( 'Thumb Image URL', 'video_gallery' ) ?></th>
								<td class="rtmp_td"><input type="text" size="50" name="filepath4" id="filepath4" onkeyup="validatethumburl();"  value="<?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 3 || $videoEdit->file_type == 4 ) ) { echo balanceTags( $videoEdit->image ); } ?>"/>
									<span id="thumburlmessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to enter the URL of thumb image', 'video_gallery' ) ?></p>
								</td>
							</tr>
							<tr><th scope="row"><?php esc_attr_e( 'Preview Image URL ( Optional )', 'video_gallery' ) ?></th>
								<td class="rtmp_td"><input type="text" size="50" name="filepath5" id="filepath5" value="<?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 3 || $videoEdit->file_type == 4 ) ) { echo balanceTags( $videoEdit->opimage ); } ?>"/>
									<span id="previewurlmessage" style="display: block;"></span>
									<p><?php esc_attr_e( 'Here you need to enter the URL of preview image', 'video_gallery' ) ?></p>
								</td>
							</tr>
						</table>
					</div>

				</form>
				<div id="upload2" class="inside">
					<div id="supportformats"><b><?php esc_attr_e( 'Supported video formats:', 'video_gallery' ) ?></b> <?php esc_attr_e( '(  MP4, M4V, M4A, MOV, Mp4v or F4V )', 'video_gallery' ) ?></div>
					<table class="form-table">
						<tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1"><th style="vertical-align: middle;"><?php esc_attr_e( 'Upload Video', 'video_gallery' ) ?></th>
							<td>
								<div id="f1-upload-form" >
									<form name="normalvideoform" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile" onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="<?php esc_attr_e( 'Upload Video', 'video_gallery' ) ?>" disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="video" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 )
										echo balanceTags( $videoEdit->file );
									?></label>
									</form>
								</div>
								<span id="uploadmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
								<div id="f1-upload-progress" style="display:none">
									<div style="float:left"><img id="f1-upload-image" src="<?php echo plugins_url().'/'.$dirPage.'/images/empty.gif'; ?>" alt="Uploading"  style="padding-top:2px"/>
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f1-upload-filename">PostRoll.flv</label></div>
									<div style="float:right"> <span id="f1-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'normalvideoform' );" name="submitcancel">Cancel</a>
										</span>
										<label id="f1-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f1-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>


								</div>
							</td></tr>

						<tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new1"> <th><?php esc_attr_e( 'Upload HD Video ( Optional )', 'video_gallery' ) ?></th>
							<td>
								<div id="f2-upload-form" >
									<form name="hdvideoform" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile" onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="Upload Video" disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="video" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 )
										echo balanceTags( $videoEdit->hdfile );
									?></label>
									</form>
								</div>

								<div id="f2-upload-progress" style="display:none">
									<div style="float:left"><img id="f2-upload-image" src="<?php echo balanceTags( plugins_url().'/'.$dirPage.'/images/empty.gif' ); ?>" alt="Uploading"  style="padding-top:2px" />
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f2-upload-filename">PostRoll.flv</label></div>
									<div style="float:right"><span id="f2-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'hdvideoform' );" name="submitcancel">Cancel</a>

										</span>
										<label id="f2-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f2-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>

								</div>

							</td></tr>



						<tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new1"><th><?php esc_attr_e( 'Upload Thumb Image', 'video_gallery' ) ?></th><td>
								<div id="f3-upload-form" >
									<form name="thumbimageform" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile"  onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="Upload Image"  disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="image" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 2 || $videoEdit->file_type == 5 ) )
										echo balanceTags( $videoEdit->image );
									?></label>
									</form>
								</div>
								<span id="uploadthumbmessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
								<div id="f3-upload-progress" style="display:none">
									<div style="float:left"><img id="f3-upload-image" src="<?php echo balanceTags( plugins_url().'/'. $dirPage . '/images/empty.gif' ); ?>" alt="Uploading" style="padding-top:2px" />
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f3-upload-filename">PostRoll.flv</label></div>
									<div style="float:right"> <span id="f3-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'thumbimageform' );" name="submitcancel">Cancel</a>
										</span>
										<label id="f3-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f3-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>

								</div>

							</td></tr>

						<tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new1"><th><?php esc_attr_e( 'Upload Preview Image ( Optional )', 'video_gallery' ) ?></th><td>
								<div id="f4-upload-form" >
									<form name="previewimageform" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile" onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="Upload Image" disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="image" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 )
										echo balanceTags( $videoEdit->opimage );
									?></label>
									</form>
								</div>
								<div id="f4-upload-progress" style="display:none">
									<div style="float:left"><img id="f4-upload-image" src="<?php echo balanceTags(  plugins_url().'/'. $dirPage . '/images/empty.gif' ); ?>" alt="Uploading" style="padding-top:2px" />
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f4-upload-filename">PostRoll.flv</label></div>
									<div style="float:right"><span id="f4-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'previewimageform' );" name="submitcancel">Cancel</a>
										</span>
										<label id="f4-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f4-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>


								</div>
								<div id="nor"><iframe id="uploadvideo_target" name="uploadvideo_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></div>
							</td></tr>
						<!--Subtitle starts here-->

						<tr id="ffmpeg_disable_new5" name="ffmpeg_disable_new5">
							<th><?php esc_attr_e( 'Upload srt file for Subtitle1', 'video_gallery' ) ?></th>
							<td>
								<div id="f5-upload-form" >
									<form name="subtitle1form" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile" onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="Upload File" disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="srt" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type != 5 )
										echo balanceTags( $videoEdit->srtfile1 );
									?></label>
									</form>
								</div>

								<div id="f5-upload-progress" style="display:none">
									<div style="float:left"><img id="f5-upload-image" src="<?php echo balanceTags(plugins_url().'/' . $dirPage . '/images/empty.gif' ); ?>" alt="Uploading" style="padding-top:2px" />
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f5-upload-filename">Subtitle.srt</label></div>
									<div style="float:right"><span id="f5-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'subtitle1form' );" name="submitcancel">Cancel</a>
										</span>
										<label id="f5-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f5-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>


								</div>
							</td></tr>
						<tr id="subtilelang1" style="display:none;"><th width="17%"><?php echo esc_attr_e( 'Enter Subtitle1 language' ); ?></th>
							<td width="83%"><input type="text" name="subtile_lang1"  id="subtile_lang1" style="width:300px" maxlength="250" value="<?php if ( isset( $videoEdit->subtitle_lang1 ) ) echo balanceTags( htmlentities( $videoEdit->subtitle_lang1 ) ); ?>" />
								<span id="uploadsrt1message" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
							</td>
						</tr>
						<tr id="ffmpeg_disable_new6" name="ffmpeg_disable_new6"><th><?php esc_attr_e( 'Upload srt file for Subtitle2', 'video_gallery' ) ?></th><td>
								<div id="f6-upload-form" >
									<form name="subtitle2form" method="post" enctype="multipart/form-data" >
										<input type="file" name="myfile" onchange="enableUpload( this.form.name );" />
										<input type="button" class="button" name="uploadBtn" value="Upload File" disabled="disabled" onclick="return addQueue( this.form.name, this.form.myfile.value );" />
										<input type="hidden" name="mode" value="srt" />
										<label id="lbl_normal"><?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type != 5 )
										echo balanceTags( $videoEdit->srtfile2 );
									?></label>
									</form>
								</div>

								<div id="f6-upload-progress" style="display:none">
									<div style="float:left"><img id="f6-upload-image" src="<?php echo balanceTags(  plugins_url().'/'. $dirPage . '/images/empty.gif' ); ?>" alt="Uploading" style="padding-top:2px" />
										<label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f6-upload-filename">SubTitle.srt</label></div>
									<div style="float:right"><span id="f6-upload-cancel">
											<a style="float:right;padding-right:10px;" href="javascript:cancelUpload( 'subtitle2form' );" name="submitcancel">Cancel</a>
										</span>
										<label id="f6-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
										<span id="f6-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
											<b><?php esc_attr_e( 'Upload Failed:', 'video_gallery' ) ?></b> <?php esc_attr_e( 'User Cancelled the upload', 'video_gallery' ) ?>
										</span></div>


								</div>
							</td></tr>
						<tr id="subtilelang2" style="display:none;"><th width="17%"><?php echo esc_attr_e( 'Enter Subtitle2 language' ); ?></th>
							<td width="83%"><input type="text" name="subtile_lang2"  id="subtile_lang2" style="width:300px" maxlength="250" value="<?php if ( isset( $videoEdit->subtitle_lang2 ) ) echo balanceTags( htmlentities( $videoEdit->subtitle_lang2 ) ); ?>" />
								<span id="uploadsrt2message" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<form name="table_options" enctype="multipart/form-data" method="post" id="video_options" onsubmit="return chkbut()">
			<input type="hidden" name="normalvideoform-value" id="normalvideoform-value" value="<?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 ) echo balanceTags( $videoEdit->file ); ?>"  />
			<input type="hidden" name="hdvideoform-value" id="hdvideoform-value" value="<?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 ) echo balanceTags( $videoEdit->hdfile ); ?>" />
			<input type="hidden" name="thumbimageform-value" id="thumbimageform-value"  value="<?php if ( isset( $videoEdit->file_type ) && ( $videoEdit->file_type == 2 || $videoEdit->file_type == 5 ) ) echo balanceTags( $videoEdit->image ); ?>" />
			<input type="hidden" name="previewimageform-value" id="previewimageform-value"  value="<?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 ) echo balanceTags( $videoEdit->opimage ); ?>" />
			<input type="hidden" name="subtitle1form-value" id="subtitle1form-value"  value="<?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type != 5 ) echo balanceTags( $videoEdit->srtfile1 ); ?>" />
			<input type="hidden" name="subtitle2form-value" id="subtitle2form-value"  value="<?php if ( isset( $videoEdit->file_type ) && $videoEdit->file_type != 5 ) echo balanceTags( $videoEdit->srtfile2 ); ?>" />
			<input type="hidden" name="subtitle_lang1" id="subtitle_lang1" value="" />
			<input type="hidden" name="subtitle_lang2" id="subtitle_lang2" value="" />
			<input type="hidden" name="youtube-value" id="youtube-value"  value="" />
			<input type="hidden" name="streamerpath-value" id="streamerpath-value" value="" />
			<input type="hidden" name="embed_code" id="embed_code" value="" />
			<input type="hidden" name="islive-value" id="islive-value" value="0" />
			<input type="hidden" name="customurl" id="customurl1"  value="" />
			<input type="hidden" name="customhd" id="customhd1"  value="" />
			<input type="hidden" name="member_id" id="member_id"  value="<?php if ( isset( $videoEdit->member_id ) ) echo balanceTags( $videoEdit->member_id ); ?>" />
			<input type="hidden" name="customimage" id="customimage"  value="" />
			<input type="hidden" name="custompreimage" id="custompreimage"  value="" />
			<?php if( $player_colors['amazonbuckets_enable'] && $player_colors['amazonbuckets_link'] && $player_colors['amazonbuckets_name'] ) { ?>
				<input type="hidden" name="amazon_buckets" id="amazon_buckets" value="1" />
			<?php } else{ ?>
				<input  type="hidden" name="amazon_buckets" id="amazon_buckets" value="0">
			<?php } ?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">

						<div class="stuffbox">
							<h3 class="hndle"><span><?php esc_attr_e( 'Enter Title / Name', 'video_gallery' ); ?></span></h3>
							<div class="inside">
								<table class="form-table">
									<tr>
										<th scope="row"><?php esc_attr_e( 'Title / Name', 'video_gallery' ) ?></th>
										<td><input value="<?php if ( isset( $videoEdit->name ) ) echo htmlentities( $videoEdit->name ); ?>" type="text" size="50" maxlength="200" name="name" onkeyup="validatevideotitle();" id="name" />
											<span id="titlemessage" style="display: block; margin-top:10px;color:red;font-size:12px;font-weight:bold;"></span>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_attr_e( 'Description', 'video_gallery' ) ?></th>
										<td><?php if ( isset( $videoEdit->description ) ) { $video_description = $videoEdit->description; } wp_editor( $video_description, 'description' ); ?> </td>
									</tr>
									<tr>
										<th scope="row"><?php esc_attr_e( 'Tags / Keywords', 'video_gallery' ) ?></th>
										<td><input value="<?php if ( isset( $videoEdit->tags_name ) ) { echo balanceTags( $videoEdit->tags_name ); } ?>" type="text" size="50" maxlength="200" name="tags_name" id="tags_name" />
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_attr_e( 'Featured Video', 'video_gallery' ) ?></th>
										<td>
										<?php if( isset($videoEdit->featured ) && $videoEdit->featured ) {
											    $feature_enable  ="checked";
											    $feature_disable ="";
											  }else if( isset( $videoEdit->featured ) &&  $videoEdit->featured == '0' ){
												$feature_enable  ="";
												$feature_disable ="checked";
											  }else{
											  	$feature_enable  ="";
											  	$feature_disable = "checked";
											  }  ?>
											<input type="radio" id="feature_on" name="feature" <?php echo $feature_enable ; ?> value="1"> <label>Yes</label>
											<input type="radio" name="feature" <?php echo $feature_disable; ?> value="0"> <label>No</label>

										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_attr_e( 'Download', 'video_gallery' ) ?></th>
										<td>
											<input type="radio" id="" name="download" <?php if ( isset( $videoEdit->download ) && $videoEdit->download == '1' ) { echo 'checked="checked"'; } ?> value="1"> <label>Yes</label>
											<input type="radio" id="" name="download" <?php if (!isset( $videoEdit->download )){ echo 'checked="checked"';} if ( isset( $videoEdit->download ) && ( $videoEdit->download == '' || $videoEdit->download == '0' ) ) { echo 'checked="checked"'; } ?> value="0"> <label>No</label>
											<br/><?php esc_attr_e( 'Note : Supported Only For Uploaded videos', 'video_gallery' ) ?>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php esc_attr_e( 'Publish', 'video_gallery' ) ?></th>
										<td>
											<input type="radio" id="" name="publish" <?php if ( !isset( $videoEdit->publish ) ){ echo 'checked="checked"'; } if ( isset( $videoEdit->publish ) && $videoEdit->publish == '1' ) { echo 'checked="checked"'; } ?> value="1"> <label>Yes</label>
											<input type="radio" id="" name="publish" <?php  if ( isset( $videoEdit->publish ) && $videoEdit->publish == '0' ) { echo 'checked="checked"'; } ?> value="0"> <label>No</label>

										</td>
									</tr>
								</table>
							</div>
						</div>
						<!-- To display the list of pre roll ads -->
<?php
## check whether preroll ads are enable
global $wpdb;
$tables   = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_vgads WHERE admethod="prepost" AND publish=1' );
$settings = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_settings' );
$google_adsenses = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'hdflvvideoshare_vgoogleadsense' );

if ( $settings[0]->preroll == 0 || $settings[0]->postroll == 0 || $settings[0]->midroll_ads == 0 || $settings[0]->imaAds == 0 || $player_colors['googleadsense_visible'] == 1  ) {
	?>

							<div class="stuffbox" id="adstypebox">
								<h3 class="hndle"><span><?php esc_attr_e( 'Select Ads', 'video_gallery' ); ?></span></h3>
								<div class="inside">
	<?php if ( $settings[0]->preroll == 0 ) { ?>
										<table class="form-table">
											<tr>
												<th scope="row"><?php esc_attr_e( 'Preroll ads', 'video_gallery' ) ?></th>
												<td>
													<select name="prerollads" id="prerollads" >
														<option value="0">select</option>
	<?php foreach ( $tables as $table ) { ?>
															<option id="6<?php echo balanceTags( $table->ads_id ); ?>" name="<?php echo balanceTags( $table->ads_id ); ?>" value="<?php echo balanceTags( $table->ads_id ); ?>" > <?php echo balanceTags( $table->title ); ?></option>
	<?php } ?>
													</select>
		<?php
		if ( isset( $videoEdit->prerollads )&&($videoEdit->prerollads)) { echo '<script>document.getElementById( "6' . $videoEdit->prerollads . '" ).selected="selected"</script>'; } ?>
												</td>
											</tr>
										</table>
	<?php } if ( $settings[0]->postroll == 0 ) { ?>
										<table class="form-table">
											<tr>
												<th scope="row"><?php esc_attr_e( 'Postroll ads', 'video_gallery' ) ?></th>
												<td>
													<select name="postrollads" id="postrollads" >
                                                                                                                <option value="0">select</option>
	<?php  foreach ( $tables as $table ) { ?>
															<option id="5<?php echo balanceTags( $table->ads_id ); ?>" name="<?php echo balanceTags( $table->ads_id ); ?>" value="<?php echo balanceTags( $table->ads_id ); ?>" > <?php echo balanceTags( $table->title ); ?></option>
	<?php } ?>
													</select>
													<?php
		if ( isset( $videoEdit->postrollads )&& ($videoEdit->postrollads)) {
			echo '<script>document.getElementById( "5' . $videoEdit->postrollads . '" ).selected="selected"</script>';
		}
													?>
												</td>
											</tr>
										</table>
	<?php } ?>
	<?php if ( $settings[0]->midroll_ads == 0 ) { ?>


										<table class="form-table">
                                            <?php $videodisable = ''; if(!isset($videoEdit->midrollads)) { 
                                            	  $videodisable = 'checked';
                                            }?>
											<tr>
												<th scope="row"><?php esc_attr_e( 'Midroll Ad', 'video_gallery' ) ?></th>
												<td>
												    <input type="radio" id="midrollads_on" name="midrollads" <?php if ( isset( $videoEdit->midrollads ) && $videoEdit->midrollads == '1' ) { echo 'checked="checked"'; } ?> value="1"> <label>Enable</label>
													<input type="radio" id="midrollads_off" name="midrollads" <?php if ( isset( $videoEdit->midrollads ) && $videoEdit->midrollads == '0' ) { echo 'checked="checked"'; } echo $videodisable; ?> value="0"> <label>Disable</label>

												</td>
											</tr>
										</table>
							<?php } ?>
							<?php if ( $settings[0]->imaAds == 0 ) { ?>
	                                    <?php $videodisable = ''; 
	                                        if(!isset($videoEdit->imaAds)) { 
                                            	$videodisable = 'checked';
                                            } else {
                                            	$videodisable = 'checked';
                                            }?>
										<table class="form-table">
											<tr>
												<th scope="row"><?php esc_attr_e( 'IMA Ad', 'video_gallery' ) ?></th>
												<td>
													<input type="radio" id="imaad" name="imaad" <?php if ( isset( $videoEdit->imaad ) && $videoEdit->imaad == '1' ) { echo 'checked="checked"'; } ?> value="1"> <label>Enable</label>
													<input type="radio" id="imaad" name="imaad" <?php if (!isset( $videoEdit->imaad)) { echo 'checked'; }  if ( isset( $videoEdit->imaad ) && $videoEdit->imaad == '0' ) { echo 'checked="checked"'; } ?> value="0"> <label>Disable</label>

												</td>
											</tr>
										</table>
									<?php } ?>  
									<?php  if( isset($player_colors['googleadsense_visible']) && $player_colors['googleadsense_visible'] == 1 ) { ?>                                                      
	                                   <table class="form-table">
	                                   <tr>  <?php $videodisable = ''; 
	                                        if(!isset($videoEdit->google_adsense)) { 
                                            	$videodisable = 'checked';
                                            }?>
	                                       <th scope="row"> <?php esc_attr_e('Google Adsense Show','video_gallery');?></th>
	                                       <td>
	                                       <input type="radio" id="googleadsense" name="googleadsense" <?php if ( isset( $videoEdit->google_adsense ) && $videoEdit->google_adsense == '1' ) { echo 'checked="checked"'; } ?> value="1"> <label>Enable</label>
										   <input type="radio" id="googleadsense" name="googleadsense" <?php if ( isset( $videoEdit->google_adsense ) && $videoEdit->google_adsense == '0' ) { echo 'checked="checked"'; } echo $videodisable ; ?> value="0"> <label>Disable</label>
	                                       
	                                       </td>
	                                      </tr>
	                                     </table>
	                                   <table class="form-table">
	                                      <tr>
	                                       <th scope="row"> <?php esc_attr_e('Google Adsense','video_gallery');?></th>
	                                       <td>
	                                         <select name="google_adsense_value">
	                                         <?php if(isset($videoEdit->google_adsense_value)){
                                                    	$editGoogleadsense = $videoEdit->google_adsense_value;
                                                    }else{
                                                    	$editGoogleadsense ='0';
                                                    } ?>
	                                         <option value="0" <?php if($editGoogleadsense == 0){ echo 'seleceted';} ?>><?php esc_attr_e('Select','video_gallery');?></option> 
	                                         <?php if($google_adsenses){
	                                         	    foreach($google_adsenses as $google_adsense){                                    	
	                                         	    $googleadsense_details = unserialize($google_adsense->googleadsense_details); $google_code = $googleadsense_details['googleadsense_title']; ?>
	                                         	<option value="<?php echo $google_adsense->id;?>" <?php if($google_adsense->id ==$editGoogleadsense ){ echo "selected";} ?>><?php echo $google_code ;?></option>
	                                         <?php }
	                                          }  ?>
	                                         </select>
	                                       </td>
	                                      </tr>
	                                   </table>
	                                   <?php } ?>
								</div>
							</div>
	<?php } ?>
					</div>
					<!-- Start of sidebar  -->
					<div id="postbox-container-1" class="postbox-container">
						<div id="side-sortables" class="inner-sidebar meta-box-sortables ui-sortable" >
							<div id="categorydiv" class="postbox">
								<div class="handlediv" title="Click to toggle"><br></div>
								<h3 class="hndle"><span><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></span></h3>                                                                                   
								<div class="inside" style="" >
									<div id="submitpost" class="submitbox">

										<div class="misc-pub-section">
										<?php
										if ($user_role != 'subscriber') {
										?>
											<h4><span>
													<a style="cursor:pointer"  onclick="playlistdisplay()"><?php esc_attr_e( 'Create New', 'video_gallery' ) ?></a></span></h4>
											<div id="playlistcreate1"><?php esc_attr_e( 'Name', 'video_gallery' ); ?><input type="text" style="width:100%;" name="p_name" id="p_name" value="" />
												<input type="button" class="button-primary button button-highlighted" name="add_pl1" value="<?php esc_attr_e( 'Add' ); ?>" onclick="return savePlaylist( document.getElementById( 'p_name' ), <?php echo balanceTags( $act_vid ); ?> );"  />
												<a  class="button cancelplaylist"  onclick="playlistclose()"><b>Close</b></a></div>
											<?php } ?>
											<div id="jaxcat"></div>
											<div id="playlistchecklist"><?php $ajaxplaylistOBJ->get_playlist(); ?></div>
											 <input type="hidden" name="filetypevalue" id="filetypevalue" value="1"  />
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<!-- End of sidebar -->
					<p>
						<input type="submit" name="add_video" class="button-primary"  onclick="return validateInput();" value="<?php echo esc_attr_e( $editbutton, 'video_gallery' ); ?>" class="button" />
						<input type="button" onclick="window.location.href = 'admin.php?page=video'" class="button-secondary" name="cancel" value="<?php esc_attr_e( 'Cancel', 'video_gallery' ); ?>" class="button" />
					</p>
				</div><!--END Post body -->
			</div><!--END Poststuff -->
		</form>




		<script>
				document.getElementById( 'generate' ).style.visibility = "hidden";
		</script>

	</div><!--END wrap -->
</div><!--END wrap -->
<script type="text/javascript">
<?php
if ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 1 ) {
	?>
		t1( "c" );
		document.getElementById( "btn2" ).checked = true;
	<?php
} elseif ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 2 ) {
	?>
		t1( "y" );
		document.getElementById( "btn1" ).checked = true;
	<?php
} elseif ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 3 ) {
	?>
		t1( "url" );
		document.getElementById( "btn3" ).checked = true;
	<?php
} elseif ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 4 ) {
	?>
		t1( "rtmp" );
		document.getElementById( "btn4" ).checked = true;
	<?php
} elseif ( isset( $videoEdit->file_type ) && $videoEdit->file_type == 5 ) {
	?>
		t1( "embed" );
		document.getElementById( "btn5" ).checked = true;
	<?php
}  else if( in_array('c', $user_allowed_method) || $user_role == 'administrator') { ?>
	t1( "c" );
	document.getElementById( "btn2" ).checked = true;
<?php } else if( in_array('y', $user_allowed_method)) { ?>
	t1( "y" );
	document.getElementById( "btn1" ).checked = true;
<?php } else if( in_array('url', $user_allowed_method)) { ?>
	t1( "url" );
	document.getElementById( "btn3" ).checked = true;
<?php } else if( in_array('rmtp', $user_allowed_method)) { ?>

	t1( "rtmp" );
	document.getElementById( "btn4" ).checked = true;
<?php } else if( in_array('embed', $user_allowed_method)) { ?>
	t1( "embed" );
	document.getElementById( "btn5" ).checked = true;
<?php } else { ?>
	t1( "c" );
	document.getElementById( "btn2" ).checked = true;
<?php } ?>
</script>