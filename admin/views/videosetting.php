<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video settings view file.
Version: 2.3
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>
<!--   MENU OPTIONS STARTS  --->
<?php 
$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];
$player_colors          = unserialize($settingsGrid->player_colors);
?>
<script type="text/javascript">
    folder  = '<?php echo $dirPage; ?>'
</script>
<div class="apptha_gallery">
<h2 class="nav-tab-wrapper">
    <a href="?page=video" class="nav-tab "><?php _e('All Videos', 'video_gallery'); ?></a>
    <a href="?page=playlist" class="nav-tab"><?php _e('Categories', 'video_gallery'); ?></a>
    <a href="?page=videoads" class="nav-tab"><?php _e('Video Ads', 'video_gallery'); ?></a>
    <a href="?page=hdflvvideosharesettings" class="nav-tab nav-tab-active"><?php _e('Settings', 'video_gallery'); ?></a>
</h2>
       <?php  if ($displayMsg): ?>
                <div class="updated below-h2">
                <p>
                <?php echo $displayMsg; ?>
                </p>
                </div>
                <?php endif; ?>
<!--  MENU OPTIONS ENDS --->
<div class="wrap">


    <link rel="stylesheet" href="<?php echo APPTHA_VGALLERY_BASEURL . 'admin/css/jquery.ui.all.css'; ?>">

            <script src="<?php echo APPTHA_VGALLERY_BASEURL . 'admin/js/jquery-1.4.4.js'; ?>"></script>
            <script src="<?php echo APPTHA_VGALLERY_BASEURL . 'admin/js/jquery.ui.core.js'; ?>"></script>
            <script src="<?php echo APPTHA_VGALLERY_BASEURL. 'admin/js/jquery.ui.widget.js'; ?>"></script>
            <script src="<?php echo APPTHA_VGALLERY_BASEURL. 'admin/js/jquery.ui.mouse.js'; ?>"></script>
            <script src="<?php echo APPTHA_VGALLERY_BASEURL. 'admin/js/jquery.ui.sortable.js'; ?>"></script>
            <script type="text/javascript">

                function enablefbapi(val) {
	if(val == 0 || val == 1) {
		document.getElementById('facebook_api').style.display = 'none';
		document.getElementById('disqus_api').style.display = 'none';
	} else if(val == 2) {
		document.getElementById('facebook_api').style.display = 'table-row';
		document.getElementById('disqus_api').style.display = 'none';
	}else if(val == 3) {
		document.getElementById('facebook_api').style.display = 'none';
		document.getElementById('disqus_api').style.display = 'table-row';
	}
}
                function enablerelateditems(val) {
                        if(val == 'side') {
                                document.getElementById('related_scroll_barColor').style.display = '';
                                document.getElementById('related_scroll_barbgColor').style.display = '';
                                document.getElementById('related_bgColor').style.display = '';
                                document.getElementById('related_playlist_open').style.display = '';
                        } else{
                                document.getElementById('related_scroll_barColor').style.display = 'none';
                                document.getElementById('related_scroll_barbgColor').style.display = 'none';
                                document.getElementById('related_bgColor').style.display = 'none';
                                document.getElementById('related_playlist_open').style.display = 'none';
                        }
                }
                $(function() {
                    $( ".column" ).sortable({
                        connectWith: ".column"
                    });

                    $( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
                    .find( ".portlet-header" )
                    .addClass( "ui-widget-header ui-corner-all" )
                    .prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
                    .end()
                    .find( ".portlet-content" );

                    $( ".portlet-header .ui-icon" ).click(function() {
                        $( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
                        $( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
                    });

                });
            </script>

                <form method="post" enctype="multipart/form-data" action="admin.php?page=hdflvvideosharesettings">
                  <h2 class="option_title">
           <?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "images/setting.png' alt='move' width='30'/>"; ?>
           <?php _e('Settings', 'video_gallery'); ?>
           <input class='button-primary' style="float:right;  "type='submit'  name="updatebutton" value='<?php _e("Update Options", "video_gallery"); ?>'>
        </h2>

                    <div class="admin_settings">
                    <div class="column">
                        <div class="portlet">
                            <div class="portlet-header"><b><?php _e("License Configuration", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'><?php _e("License Key", "video_gallery"); ?></th>
                                        <td valign="top"><input type='text' name="license" value="<?php echo $settingsGrid->license ?>"  style="float: left;" size=35 /> <?php if(isset($settingsGrid->license) && strlen($settingsGrid->license) != 31){ ?><?php echo "<a target='_blank' href='http://www.apptha.com/checkout/cart/add/product/12'><img src='" . APPTHA_VGALLERY_BASEURL . "images/buynow.gif' alt='Buy'/></a>"; } ?></td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Logo Configuration", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'><?php _e("Logo Path", "video_gallery"); ?></th>
                                        <td>
                                            <input type='file' name="logopath" value="" size=40  /><?php echo $settingsGrid->logopath ?>
                                            <input type='hidden' name="logopathvalue" value="<?php echo $settingsGrid->logopath ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Logo Target", "video_gallery"); ?></th>
                                        <td><input type='text' name="logotarget" value="<?php if(isset($settingsGrid->logo_target)) echo $settingsGrid->logo_target ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Logo Align", "video_gallery"); ?></th>
                                        <td> <select name="logoalign" style="width:150px;">
                                                <option <?php if ($settingsGrid->logoalign == 'TL') { ?> selected="selected" <?php } ?> value="TL"><?php _e("Top Left", "video_gallery"); ?></option>
                                                <option <?php if ($settingsGrid->logoalign == 'TR') { ?> selected="selected" <?php } ?> value="TR"><?php _e("Top Right", "video_gallery"); ?></option>
                                                <option <?php if ($settingsGrid->logoalign == 'BL') { ?> selected="selected" <?php } ?> value="BL"><?php _e("Left Bottom", "video_gallery"); ?></option>
                                                <option <?php if ($settingsGrid->logoalign == 'BR') { ?> selected="selected" <?php } ?> value="BR"><?php _e("Right Bottom", "video_gallery"); ?></option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Logo Alpha", "video_gallery"); ?></th>
                                        <td><input type='text' name="logoalpha" value="<?php echo $settingsGrid->logoalpha ?>" size=45  /></td>
                                    </tr>
<!--                                    <tr>
                                        <th scope='row'><?php _e("Hide YouTube Logo", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' <?php if ($settingsGrid->hideLogo == 1) { ?> checked <?php } ?> name="hideLogo" value="1" size=45  /></td>
                                    </tr>-->
                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Display Configuration", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'><?php _e("Auto Play", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="autoplay" <?php if ($settingsGrid->autoplay == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Player Width", "video_gallery"); ?></th>
                                        <td><input type='text' name="width" value="<?php echo $settingsGrid->width ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Player Height", "video_gallery"); ?></th>
                                        <td><input type='text' name="height" value="<?php echo $settingsGrid->height ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Stage Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="stagecolor" value="<?php echo $settingsGrid->stagecolor ?>" size=45  />
                                            <br /><?php _e('Ex : 0xdddddd ', 'video_gallery') ?>
                                        </td>
                                    </tr>
                                    <!--  Display Description on the player-->
                                     <tr>
                                        <th scope='row'><?php _e("Show Description", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="showTag" <?php if ($settingsGrid->showTag == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                                    </tr>
                                    <!--  Display Default Image-->
                                     <tr>
                                        <th scope='row'><?php _e("Display Default Image", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="imageDefault" <?php if ($settingsGrid->imageDefault == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="portlet">
                    <div class="portlet-header"><b><?php _e("General Settings", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">

                                    <tr>
                                <th scope='row'><?php _e("FFMPEG Path", "video_gallery"); ?></th>
                                <td><input type='text' name="ffmpeg_path" value="<?php echo $settingsGrid->ffmpeg_path; ?>" size=45  /></td>
                                    </tr>
                                    <tr>
                                <th scope='row'><?php _e("Normal Scale", "video_gallery"); ?></th>
                                <td>
                                    <select name="normalscale" style="width:150px;">
                                        <option value="0" <?php if ($settingsGrid->normalscale == 0) { ?> selected="selected" <?php } ?> ><?php _e("Aspect Ratio", "video_gallery"); ?></option>
                                        <option value="1" <?php if ($settingsGrid->normalscale == 1) { ?> selected="selected" <?php } ?>><?php _e("Original Screen", "video_gallery"); ?></option>
                                        <option value="2" <?php if ($settingsGrid->normalscale == 2) { ?> selected="selected" <?php } ?>><?php _e("Fit To Screen", "video_gallery"); ?></option>
                                    </select>
                                </td>
                                    </tr>
                                    <tr>
                                <th scope='row'><?php _e("Full Screen Scale", "video_gallery"); ?></th>
                                <td>
                                    <select name="fullscreenscale" style="width:150px;">
                                        <option value="0" <?php if ($settingsGrid->fullscreenscale == 0) { ?> selected="selected" <?php } ?>><?php _e("Aspect Ratio", "video_gallery"); ?></option>
                                        <option value="1" <?php if ($settingsGrid->fullscreenscale == 1) { ?> selected="selected" <?php } ?>><?php _e("Original Screen", "video_gallery"); ?></option>
                                        <option value="2" <?php if ($settingsGrid->fullscreenscale == 2) { ?> selected="selected" <?php } ?>><?php _e("Fit To Screen", "video_gallery"); ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope='row'><?php _e("Embed Visible", "video_gallery"); ?></th>
                                <td><input type='checkbox' class='check' <?php if ($settingsGrid->embed_visible == 1) { ?> checked <?php } ?> name="embed_visible" value="1" size=45  /></td>
                                    </tr>
                            <tr>
                                <th scope='row'><?php _e("Enable Ratings", "video_gallery"); ?></th>
                                <td><input type='checkbox' class='check' <?php if ($settingsGrid->ratingscontrol == 1) { ?> checked <?php } ?> name="ratingscontrol" value="1" size=45  /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Player Color Settings", "video_gallery"); ?> <?php _e('Ex : 0xdddddd ', 'video_gallery') ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <!-- Share Popup Header color -->
                                    <tr>
                                        <th scope='row'><?php _e("Share Popup Header Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="sharepanel_up_BgColor" value="<?php echo $player_colors['sharepanel_up_BgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Share Popup Background color -->
                                    <tr>
                                        <th scope='row'><?php _e("Share Popup Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="sharepanel_down_BgColor" value="<?php echo $player_colors['sharepanel_down_BgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Share Popup Text color -->
                                    <tr>
                                        <th scope='row'><?php _e("Share Popup Text Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="sharepaneltextColor" value="<?php echo $player_colors['sharepaneltextColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Send Button Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Send Button Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="sendButtonColor" value="<?php echo $player_colors['sendButtonColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Send Button Text Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Send Button Text Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="sendButtonTextColor" value="<?php echo $player_colors['sendButtonTextColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Player Text Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Player Text Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="textColor" value="<?php echo $player_colors['textColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Skin Background Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Skin Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="skinBgColor" value="<?php echo $player_colors['skinBgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Seek Bar Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Seek Bar Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="seek_barColor" value="<?php echo $player_colors['seek_barColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Buffer Bar Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Buffer Bar Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="buffer_barColor" value="<?php echo $player_colors['buffer_barColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Skin Icons Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Skin Icons Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="skinIconColor" value="<?php echo $player_colors['skinIconColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Progress Bar Background Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Progress Bar Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="pro_BgColor" value="<?php echo $player_colors['pro_BgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Play Button Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Play Button Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="playButtonColor" value="<?php echo $player_colors['playButtonColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Play Button Background Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Play Button Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="playButtonBgColor" value="<?php echo $player_colors['playButtonBgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Player Buttons Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Player Buttons Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="playerButtonColor" value="<?php echo $player_colors['playerButtonColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Player Buttons Background Color -->
                                    <tr>
                                        <th scope='row'><?php _e("Player Buttons Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="playerButtonBgColor" value="<?php echo $player_colors['playerButtonBgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Related Videos Background Color -->
                                    <tr id="related_bgColor" style="display:none;">
                                        <th scope='row'><?php _e("Related Videos Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="relatedVideoBgColor" value="<?php echo $player_colors['relatedVideoBgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Related Videos Scroll Bar Color -->
                                    <tr id="related_scroll_barColor" style="display:none;">
                                        <th scope='row'><?php _e("Related Videos Scroll Bar Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="scroll_barColor" value="<?php echo $player_colors['scroll_barColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Related Videos Scroll Bar Background Color -->
                                    <tr id="related_scroll_barbgColor" style="display:none;">
                                        <th scope='row'><?php _e("Related Videos Scroll Bar Background Color", "video_gallery"); ?></th>
                                        <td><input type='text' name="scroll_BgColor" value="<?php echo $player_colors['scroll_BgColor']; ?>" size=45  />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        
              </div>
              <div class="column">

                  <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Playlist Configuration", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <tr>
                                        <th scope='row'><?php _e("Playlist", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="playlist" <?php if ($settingsGrid->playlist == 1) { ?> checked <?php } ?> value="1"  /></td>

                                    </tr>
                                    <tr id="related_playlist_open" style="display:none;">
                                        <th scope='row'><?php _e("Playlist Open", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="playlist_open" <?php if ($settingsGrid->playlist_open == 1) { ?> checked <?php } ?> value="1"  /></td>

                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("HD Default", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' name="HD_default" <?php if ($settingsGrid->HD_default == 1) { ?> checked <?php } ?> value="1"  /></td>
                                    </tr>
                                    <tr>
                                        <th scope='row'><?php _e("Playlist Autoplay", "video_gallery"); ?></th>
                                        <td><input type='checkbox' class='check' <?php if ($settingsGrid->playlistauto == 1) { ?> checked <?php } ?> name="playlistauto" value="1" /></td>

                                    </tr>
                                    <!-- Select Related Video View-->
                                    <tr>
                                        <th scope='row'><?php _e("Related Video View", "video_gallery"); ?></th>
                                <td>
                                      <select name="relatedVideoView" onchange="enablerelateditems(this.value)">
						<option value="side"
						<?php if ($settingsGrid->relatedVideoView == 'side')
						echo "selected=selected"; ?>>side</option>
						<option value="center"
						<?php if ($settingsGrid->relatedVideoView == 'center')
						echo "selected=selected"; ?>>center</option>
                                    </select>

                            </tr>
                                </table>
                            </div>
                        </div>

                  <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Ads Settings", "video_gallery"); ?></b></div>
                            <div class="portlet-content">
                                <table class="form-table">
                                    <!-- Preroll -->
                                    <tr>
                                        <th scope='row'><?php _e("Preroll Ads", "video_gallery"); ?></th>
                                        <td>
                                            <input name="preroll" id="preroll" type='radio' value="0"  <?php if ($settingsGrid->preroll == 0)
                                                    echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                            <input name="preroll" id="preroll" type='radio' value="1"  <?php if ($settingsGrid->preroll == 1)
                            echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                        </td>
                                    </tr>
                                    <!-- Postroll -->
                                    <tr>
                                        <th scope='row'><?php _e("Postroll Ads", "video_gallery"); ?></th>
                                        <td>
                                            <input name="postroll" id="postroll" type='radio' value="0"  <?php if ($settingsGrid->postroll == 0)
                            echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                            <input name="postroll" id="postroll" type='radio' value="1"  <?php if ($settingsGrid->postroll == 1)
                            echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                        </td>
                                    </tr>
                                    <!-- Midroll Ads -->
                                    <tr>
                                        <th scope='row'><?php _e("Midroll Ads", "video_gallery"); ?></th>
                                        <td>
                                            <input name="midroll_ads" id="midroll_ads" type='radio' value="0"  <?php if ($settingsGrid->midroll_ads == 0)
                            echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                            <input name="midroll_ads" id="midroll_ads" type='radio' value="1"  <?php if ($settingsGrid->midroll_ads == 1)
                            echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                        </td>
                                    </tr>
                                    <!-- IMA Ads -->
                                    <tr>
                                        <th scope='row'><?php _e("IMA Ads", "video_gallery"); ?></th>
                                        <td>
                                            <input name="imaAds" id="imaAds" type='radio' value="0"  <?php if ($settingsGrid->imaAds == 0)
                            echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                            <input name="imaAds" id="imaAds" type='radio' value="1"  <?php if ($settingsGrid->imaAds == 1)
                            echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                        </td>
                                    </tr>
                                    
                                    <!-- Ad Skip -->
                                    <tr>
                                        <th scope='row'><?php _e("Ad Skip", "video_gallery"); ?></th>
                                        <td>
                                            <input name="adsSkip" id="adsSkip" type='radio' value="0"  <?php if ($settingsGrid->adsSkip == 0)
                            echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                            <input name="adsSkip" id="adsSkip" type='radio' value="1"  <?php if ($settingsGrid->adsSkip == 1)
                            echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                        </td>
                                    </tr>
                                    <!-- Ad Skip Duration -->
                                    <tr>
                                        <th scope='row'><?php _e("Ad Skip Duration", "video_gallery"); ?></th>
                                        <td><input type='text' name="adsSkipDuration" value="<?php echo $settingsGrid->adsSkipDuration ?>" size=45  />
                                        </td>
                                    </tr>
                                    <!-- Track Code -->
                                    <tr>
                                        <th scope='row'><?php _e("Track Code", "video_gallery"); ?></th>
                                        <td><input type='text' name="trackCode" value="<?php echo $settingsGrid->trackCode ?>" size=45  />
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                  <div class="portlet">
                            <div class="portlet-header"><b><?php _e("Comment Settings", "video_gallery"); ?></b></div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <tr>
                                        <th scope='row'><?php _e("Select Comment Type", "video_gallery"); ?></th>
                                <td>
                                      <select name="comment_option" onchange="enablefbapi(this.value)">
						<option value="0"
						<?php if ($settingsGrid->comment_option == 0)
						echo "selected=selected"; ?>>None</option>
						<option value="1"
						<?php if ($settingsGrid->comment_option == 1)
						echo "selected=selected"; ?>>Default Comment</option>
						<option value="2"
						<?php if ($settingsGrid->comment_option == 2)
						echo "selected=selected"; ?>>Face Book Comment</option>
						<option value="3"
						<?php if ($settingsGrid->comment_option == 3)
						echo "selected=selected"; ?>>DisQus Comment</option>
                                    </select>

                            </tr>
                                    <tr id="facebook_api" style="display: none;" >
                                        <th scope='row'><?php _e("App ID", "video_gallery"); ?></th>
                                        <td><input type='text' name="keyApps" value="<?php echo $settingsGrid->keyApps ?>" size=45  /></td>
                            </tr>
                                    <tr id="disqus_api" style="display: none;" >
                                        <th scope='row'><?php _e("Shot Name", "video_gallery"); ?></th>
                                        <td><input type='text' name="keydisqusApps" value="<?php echo $settingsGrid->keydisqusApps ?>" size=45  /></td>
                            </tr>
                                    <tr><td> <a href="http://developers.facebook.com/" target="_blank"><?php _e("Link to create Facebook App ID", "video_gallery"); ?></a></td></tr>
                        </table>
                    </div>
                </div>
                <div class="portlet">
                <div class="portlet-header"><b><?php _e("Video Configuration", "video_gallery"); ?></b></div>
                <div class="portlet-content">
                    <table class="form-table">

                        <tr>
                            <th scope='row'><?php _e("Download", "video_gallery"); ?></th>
                            <td><input type='checkbox' class='check' name="download" <?php if ($settingsGrid->download == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'><?php _e("Buffer", "video_gallery"); ?></th>
                            <td><input type='text' name="buffer" value="<?php echo $settingsGrid->buffer ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'><?php _e("Volume", "video_gallery"); ?></th>
                            <td><input type='text' name="volume" value="<?php echo $settingsGrid->volume ?>" size=45  /></td>
                        </tr>
                    </table>
                </div>
            </div>

 <div class="portlet">
                <div class="portlet-header"><b><?php _e("Skin Configuration", "video_gallery"); ?></b></div>
                <div class="portlet-content">
                    <table class="form-table">
                                  <tr>
                                      <th scope='row'><?php _e("Display Timer", "video_gallery"); ?></th>
                                      <td>
                                          <input type='checkbox' class='check'  name="timer" <?php if ($settingsGrid->timer == 1) { ?> checked <?php } ?> value="1" /></td>
                                      <td>
                              </tr>
                              <tr>
                                  <th scope='row'><?php _e("Display Zoom", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check' <?php if ($settingsGrid->zoom == 1) { ?> checked <?php } ?> name="zoom" value="1" /></td>
                              </tr>
                               <!-- Display Email Icon-->
                              <tr>
                                  <th scope='row'><?php _e("Display Email", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check'  name="email" <?php if ($settingsGrid->email == 1) { ?> checked <?php } ?>value="1"   /></td>
                              </tr>
                              <!-- Display Share Icon-->
                              <tr>
                                  <th scope='row'><?php _e("Display Share", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check'  name="shareIcon" <?php if ($settingsGrid->shareIcon == 1) { ?> checked <?php } ?>value="1"   /></td>
                              </tr>
                              <!-- Display Volume Icon-->
                              <tr>
                                  <th scope='row'><?php _e("Display Volume", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check'  name="volumecontrol" <?php if ($settingsGrid->volumecontrol == 1) { ?> checked <?php } ?>value="1"   /></td>
                              </tr>
                              <!-- Display Progress Bar-->
                              <tr>
                                  <th scope='row'><?php _e("Display Progress Bar", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check'  name="progressControl" <?php if ($settingsGrid->progressControl == 1) { ?> checked <?php } ?>value="1"   /></td>
                              </tr>
                              <tr>
                                  <th scope='row'><?php _e("Display Full Screen", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check' <?php if ($settingsGrid->fullscreen == 1) { ?> checked <?php } ?> name="fullscreen" value="1" size=45  /></td>
                              </tr>
                              <tr>
                                  <th scope='row'><?php _e("Skin Autohide", "video_gallery"); ?></th>
                                  <td><input type='checkbox' class='check' <?php if ($settingsGrid->skin_autohide == 1) { ?> checked <?php } ?> name="skin_autohide" value="1" size=45  /></td>
                              </tr>
                          </table>
                      </div>
                  </div>
                  <div class="portlet">
                      <div class="portlet-header"><b><?php _e("Videos Page Settings", "video_gallery"); ?></b></div>
                      <div class="portlet-content">
                          <table class="form-table">

                                  <!--videos page banner settings-->

                                  <!-- Popular Videos-->
                                  <tr><th><?php _e("Gutter Space (px)", "video_gallery"); ?></th>
                                      <td><input type="text" name="gutterspace" id="gutterspace" size="20" value="<?php echo $settingsGrid->gutterspace; ?>"></td>
                                  </tr>
<!--                                  <tr class="gallery_separator">

                                      <th><?php _e("Player in Home Page", "video_gallery"); ?></th>
                                      <td><input type='radio' name="default_player"  value="0"  <?php if ($settingsGrid->default_player == 0)
                                                  echo 'checked'; ?> /><label><?php _e("Single player", "video_gallery"); ?></label>
                                          <input  type='radio' name="default_player"  value="1" <?php if ($settingsGrid->default_player == 1)
                                              echo 'checked'; ?> /><label><?php _e("Banner", "video_gallery"); ?></label>
                                      </td>
                                  </tr>-->
                                  <tr class="gallery_separator">

                                      <th><?php _e("Popular Videos", "video_gallery"); ?></th>
                                      <td><input  type='radio' name="popular"  value="1" <?php if ($settingsGrid->popular == 1)
                                              echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                          <input type='radio' name="popular"  value="0"  <?php if ($settingsGrid->popular == 0)
                                                  echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                      </td>
                                  </tr>
                                  <tr class="gallery_separator_row">
                                      <td><label><?php _e("Rows", "video_gallery"); ?></label><input type="text" name="rowsPop" id="rowsPop" size="10" value="<?php echo $settingsGrid->rowsPop; ?>"></td>
                                      <td><label><?php _e("Columns", "video_gallery"); ?> </label><input type="text" name="colPop" id="colPop" size="10" value="<?php echo $settingsGrid->colPop; ?>"></td>
                                  </tr>

                               <!-- Recent Videos-->
                               <tr class="gallery_separator">
                                   <th><?php _e("Recent Videos", "video_gallery"); ?></th>
                                   <td><input type='radio' name="recent"  value="1" <?php if ($settingsGrid->recent == 1)
                                                  echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                       <input type='radio' name="recent"  value="0"  <?php if ($settingsGrid->recent == 0)
                                                  echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                   </td>
                               </tr>
                               <tr class="gallery_separator_row">
                                   <td><label><?php _e("Rows", "video_gallery"); ?></label><input type="text" name="rowsRec" id="rowsRec" size="10" value="<?php echo $settingsGrid->rowsRec; ?>"></td>
                                   <td><label><?php _e("Columns", "video_gallery"); ?> </label><input type="text" name="colRec" id="colRec" size="10" value="<?php echo $settingsGrid->colRec; ?>">
                                   </td>
                               </tr>

                               <!-- Featured Videos  -->
                               <tr class="gallery_separator">
                                   <th><?php _e("Featured Videos", "video_gallery"); ?></th>
                                   <td><input type='radio' name="feature"  value="1" <?php if ($settingsGrid->feature == 1)
                                                  echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                       <input  type='radio' name="feature"  value="0"  <?php if ($settingsGrid->feature == 0)
                                                  echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                   </td>
                               </tr>
                               <tr class="gallery_separator_row"><td><label><?php _e("Rows", "video_gallery"); ?></label><input type="text" name="rowsFea" id="rowsFea" size="10" value="<?php echo $settingsGrid->rowsFea; ?>"></td>
                                   <td><label><?php _e("Columns", "video_gallery"); ?></label><input type="text" name="colFea" id="colFea" size="10" value="<?php echo $settingsGrid->colFea; ?>">
                                  </td>
                              </tr>


                              <tr class="gallery_separator">
                                  <th><?php _e("Category Videos", "video_gallery"); ?></th>
                                  <td><input type='radio' name="homecategory"  value="1" <?php if ($settingsGrid->homecategory == 1)
                                                  echo 'checked'; ?> /><label><?php _e("Enable", "video_gallery"); ?></label>
                                      <input type='radio' name="homecategory"  value="0"  <?php if ($settingsGrid->homecategory == 0)
                                                  echo 'checked'; ?> /><label><?php _e("Disable", "video_gallery"); ?></label>
                                  </td>
                              </tr>

                              <tr class="gallery_separator_row">
                                  <td><label><?php _e("Rows", "video_gallery"); ?></label><input type="text" name="rowCat" id="rowCat" size="10" value="<?php echo $settingsGrid->rowCat; ?>"></td>
                                  <td><label><?php _e("Columns", "video_gallery"); ?></label><input type="text" name="colCat" id="colCat" size="10" value="<?php echo $settingsGrid->colCat; ?>">
                                   </td>
                              </tr>
                              <tr class="gallery_separator"><th><?php _e("No Of Categories in Home page", "video_gallery"); ?></th>
                                      <td><input type="text" name="category_page" id="category_page" size="20" value="<?php echo $settingsGrid->category_page; ?>"></td>
                                  </tr>
                               <tr class="gallery_separator"><th><?php _e("More Page", "video_gallery"); ?></th>

                               </tr>

                                <tr class="gallery_separator_row"><td><label><?php _e("Rows", "video_gallery"); ?></label><input type="text" name="rowMore" id="rowMore" size="10" value="<?php echo $settingsGrid->rowMore; ?>"></td>
                                   <td><label><?php _e("Columns", "video_gallery"); ?></label><input type="text" name="colMore" id="colMore" size="10" value="<?php echo $settingsGrid->colMore; ?>">
                                   </td>
                               </tr>
                           </table>
                       </div>
                   </div>
                  <div class="bottom_btn">
<input class='button-primary' style="float:right; " name="updatebutton"  type='submit' value='<?php _e("Update Options", "video_gallery"); ?>'>
                 </div>
                  </div>
                  </div>
                  <div class="clear"></div>
              </form>
          </div>
</div>

<script type="text/javascript">
<?php
                                                    if (isset($settingsGrid->comment_option) && $settingsGrid->comment_option == 2) {
?>
                                                 enablefbapi('2');
 <?php
                                                    }
                                                    elseif (isset($settingsGrid->comment_option) && $settingsGrid->comment_option == 3) {
?>
                                                 enablefbapi('3');
<?php
                                                    }
                                                    if (isset($settingsGrid->relatedVideoView) && $settingsGrid->relatedVideoView == 'side') {
?>
                                                  enablerelateditems('side');
<?php 
                                                    } elseif (isset($settingsGrid->relatedVideoView) && $settingsGrid->relatedVideoView == 'center') {
?>
                                                 enablerelateditems('center');
<?php
                                                    }
?>
</script>