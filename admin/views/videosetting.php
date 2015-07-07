<?php
/**
 * Video setting view file. 
 * 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
?>
<!--   MENU OPTIONS STARTS  --->
<?php
$dir = dirname( plugin_basename( __FILE__ ) );
$dirExp = explode( '/', $dir );
$dirPage = $dirExp[0];
$player_colors = unserialize( $settingsGrid->player_colors );
?>
<script type="text/javascript">
	folder = '<?php echo balanceTags( $dirPage ); ?>'
</script>
<div class="apptha_gallery">
	<h2 class="nav-tab-wrapper">
		<a href="?page=video" class="nav-tab "><?php esc_attr_e( 'All Videos', 'video_gallery' ); ?></a>
		<a href="?page=playlist" class="nav-tab"><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></a>
		<a href="?page=videoads" class="nav-tab"><?php esc_attr_e( 'Video Ads', 'video_gallery' ); ?></a>
		<a href="?page=hdflvvideosharesettings" class="nav-tab nav-tab-active"><?php esc_attr_e( 'Settings', 'video_gallery' ); ?></a>
		<a href="?page=googleadsense" class="nav-tab"><?php esc_attr_e( 'Google AdSense', 'video_gallery' ); ?></a>		
	</h2>
	<div id="trackcodeerror"></div>
	<?php if ( $displayMsg ): ?>
		<div class="updated below-h2">
			<p>
				<?php echo balanceTags( $displayMsg ); ?>
			</p>
		</div>
	<?php endif; ?>
	<!--  MENU OPTIONS ENDS --->
	<div class="wrap">
		<link rel="stylesheet" href="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ). 'admin/css/jquery.ui.all.css'; ?>">
		<script type="text/javascript">

			function enablefbapi( val ) {
				if ( val == 0 || val == 1 ) {
					document.getElementById( 'facebook_api' ).style.display = 'none';
					document.getElementById( 'facebook_api_link' ).style.display = 'none';
					document.getElementById( 'disqus_api' ).style.display = 'none';
				} else if ( val == 2 ) {
					document.getElementById( 'facebook_api' ).style.display = 'table-row';
					document.getElementById( 'facebook_api_link' ).style.display = 'table-row';
					document.getElementById( 'disqus_api' ).style.display = 'none';
				} else if ( val == 3 ) {
					document.getElementById( 'facebook_api' ).style.display = 'none';
					document.getElementById( 'facebook_api_link' ).style.display = 'none';
					document.getElementById( 'disqus_api' ).style.display = 'table-row';
				}
			}
			function enablerelateditems( val ) {
				if ( val == 'side' ) {
					document.getElementById( 'related_scroll_barColor' ).style.display = '';
					document.getElementById( 'related_scroll_barbgColor' ).style.display = '';
					document.getElementById( 'related_bgColor' ).style.display = '';
					document.getElementById( 'related_playlist_open' ).style.display = '';
				} else {
					document.getElementById( 'related_scroll_barColor' ).style.display = 'none';
					document.getElementById( 'related_scroll_barbgColor' ).style.display = 'none';
					document.getElementById( 'related_bgColor' ).style.display = 'none';
					document.getElementById( 'related_playlist_open' ).style.display = 'none';
				}
			}
			var sortdr = jQuery.noConflict();
			sortdr( function() {
				sortdr( ".column" ).sortable( {
					connectWith: ".column" 				     		
				 });
				sortdr( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
						.find( ".portlet-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.prepend( "<span class='ui-icon ui-icon-minusthick'></span>" )
						.end()
						.find( ".portlet-content" );

				sortdr( ".portlet-header .ui-icon" ).click( function() {
					sortdr( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
					sortdr( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
				} );

				sortdr('#videogallery_setting').click(function(){
					var trackcode = sortdr('#trackcode').val();
					var trackcodepattern = /^ua-\d{4,9}-\d{1,4}$/i;
					if( ( !trackcodepattern.test(trackcode) )  && trackcode!='' ) {
						   sortdr('#trackcodeerror').html('Enter valid Google Analytics Tracking Code');
						   sortdr('#trackcodeerror').addClass('updated below-h2');
	                       return false;                       
				    }
				    return true;
				} );

			} );
		</script>

		<form method="post" enctype="multipart/form-data" action="admin.php?page=hdflvvideosharesettings" >
			<h2 class="option_title">
				<?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . 'images/setting.png" alt="move" width="30"/>'; ?>
				<?php esc_attr_e( 'Settings', 'video_gallery' ); ?>
				<input class='button-primary' id='videogallery_setting' style="float:right;  "type='submit'  name="updatebutton" value='<?php esc_attr_e( 'Update Options', 'video_gallery' ); ?>'>
			</h2>

			<div class="admin_settings">
				<div class="column">
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'License Configuration', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'License Key', 'video_gallery' ); ?></th>
									<td valign="top">
									<input type='text' name="license" value="<?php echo balanceTags( $settingsGrid->license ); ?>"  style="float: left;" size="45" /> 
									<?php if ( isset( $settingsGrid->license ) && ( !strpos( $settingsGrid->license ,'CONTUS' ) ) ) { ?><?php echo "<a class='buynow-btn' target='_blank' href='http://www.apptha.com/checkout/cart/add/product/12'><img valign='top' src='" . APPTHA_VGALLERY_BASEURL . "images/buynow.gif' alt='Buy'/></a>";
				                    } ?>               
				                    </td>
								</tr>

							</table>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Logo Configuration', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Logo Path', 'video_gallery' ); ?></th>
									<td>
										<input type='file' name="logopath" value="" size=40  /><?php echo balanceTags( $settingsGrid->logopath ); ?>
										<input type='hidden' name="logopathvalue" value="<?php echo balanceTags( $settingsGrid->logopath ); ?>" />
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Logo Target', 'video_gallery' ); ?></th>
									<td><input type='text' name="logotarget" value="<?php if ( isset( $settingsGrid->logo_target ) ) echo balanceTags( $settingsGrid->logo_target ); ?>" size=45  /><br>
									<br/><?php esc_attr_e( '', 'video_gallery' ) ?></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Logo Align', 'video_gallery' ); ?></th>
									<td> <select name="logoalign" style="width:150px;">
											<option <?php if ( $settingsGrid->logoalign == 'TL' ) { ?> selected="selected" <?php } ?> value="TL"><?php esc_attr_e( 'Top Left', 'video_gallery' ); ?></option>
											<option <?php if ( $settingsGrid->logoalign == 'TR' ) { ?> selected="selected" <?php } ?> value="TR"><?php esc_attr_e( 'Top Right', 'video_gallery' ); ?></option>
											<option <?php if ( $settingsGrid->logoalign == 'BL' ) { ?> selected="selected" <?php } ?> value="BL"><?php esc_attr_e( 'Left Bottom', 'video_gallery' ); ?></option>
											<option <?php if ( $settingsGrid->logoalign == 'BR' ) { ?> selected="selected" <?php } ?> value="BR"><?php esc_attr_e( 'Right Bottom', 'video_gallery' ); ?></option>
										</select></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Logo Alpha', 'video_gallery' ); ?></th>
									<td><input type='text' name="logoalpha" value="<?php echo balanceTags( $settingsGrid->logoalpha ); ?>" size=45  /></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Player Configuration', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Auto Play', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="autoplay" <?php if ( $settingsGrid->autoplay == 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Player Width', 'video_gallery' ); ?></th>
									<td><input type='text' name="width" value="<?php echo balanceTags( $settingsGrid->width ); ?>" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Player Height', 'video_gallery' ); ?></th>
									<td><input type='text' name="height" value="<?php echo balanceTags( $settingsGrid->height ); ?>" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Stage Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="stagecolor" value="<?php echo balanceTags( $settingsGrid->stagecolor ); ?>" size=45  />
										<br /><?php esc_attr_e( 'Ex : 0xdddddd ', 'video_gallery' ) ?>
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Download', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="download" <?php if ( $settingsGrid->download == 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Buffer', 'video_gallery' ); ?></th>
									<td><input type='text' name="buffer" value="<?php echo balanceTags( $settingsGrid->buffer ); ?>" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Volume', 'video_gallery' ); ?></th>
									<td><input type='text' name="volume" value="<?php echo balanceTags( $settingsGrid->volume ); ?>" size=45  /></td>
								</tr>
                                 <!-- New feature  for enable /disable  social icon  posted by and related video  under  player-->
                                 <tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Social Icon', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="showSocialIcon" <?php if ( $player_colors['show_social_icon']== 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
                                 <tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Posted By', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="ShowPostBy" <?php if ( $player_colors['show_posted_by']== 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Show related video', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="show_related_video" <?php if ( isset( $player_colors['show_related_video'] ) && $player_colors['show_related_video'] == 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								 <tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Show Added On', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="show_added_on" <?php if ( $player_colors['show_added_on']== 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<!------- Rss Disable / Enable option -------->
								<tr class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Show Rss Feed Icon', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="show_rss_icon" <?php if ( $player_colors['show_rss_icon']== 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								</table>
						</div>
					</div>

					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'General Settings', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">

								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'FFMPEG Path', 'video_gallery' ); ?></th>
									<td><input type='text' name="ffmpeg_path" value="<?php echo balanceTags( $settingsGrid->ffmpeg_path ); ?>" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Normal Scale', 'video_gallery' ); ?></th>
									<td>
										<select name="normalscale" style="width:150px;">
											<option value="0" <?php if ( $settingsGrid->normalscale == 0 ) { ?> selected="selected" <?php } ?> ><?php esc_attr_e( 'Aspect Ratio', 'video_gallery' ); ?></option>
											<option value="1" <?php if ( $settingsGrid->normalscale == 1 ) { ?> selected="selected" <?php } ?>><?php esc_attr_e( 'Original Screen', 'video_gallery' ); ?></option>
											<option value="2" <?php if ( $settingsGrid->normalscale == 2 ) { ?> selected="selected" <?php } ?>><?php esc_attr_e( 'Fit To Screen', 'video_gallery' ); ?></option>
										</select>
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Full Screen Scale', 'video_gallery' ); ?></th>
									<td>
										<select name="fullscreenscale" style="width:150px;">
											<option value="0" <?php if ( $settingsGrid->fullscreenscale == 0 ) { ?> selected="selected" <?php } ?>><?php esc_attr_e( 'Aspect Ratio', 'video_gallery' ); ?></option>
											<option value="1" <?php if ( $settingsGrid->fullscreenscale == 1 ) { ?> selected="selected" <?php } ?>><?php esc_attr_e( 'Original Screen', 'video_gallery' ); ?></option>
											<option value="2" <?php if ( $settingsGrid->fullscreenscale == 2 ) { ?> selected="selected" <?php } ?>><?php esc_attr_e( 'Fit To Screen', 'video_gallery' ); ?></option>
										</select>
									</td>
								</tr>

								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Embed Visible', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->embed_visible == 1 ) { ?> checked <?php } ?> name="embed_visible" value="1" size=45  /></td>
								</tr>
								<!-- Iframe visible  -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Iframe Visible', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if (isset($player_colors['iframe_visible'])&&$player_colors['iframe_visible']==1 ) { ?> checked <?php } ?> name="iframe_visible" value="1" size=45  /></td>
								</tr>
								<!-- Report video setting -->
								
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable Report', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $player_colors['report_visible'] == 1 ) { ?> checked <?php } ?> name="report_visible" value="1" size=45  /></td>
								</tr>
								<!-- End Report show setting -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable Views', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->view_visible == 1 ) { ?> checked <?php } ?> name="view_visible" value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable Ratings', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->ratingscontrol == 1 ) { ?> checked <?php } ?> name="ratingscontrol" value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable Tags', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->tagdisplay == 1 ) { ?> checked <?php } ?> name="tagdisplay" value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable Category', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->categorydisplay == 1 ) { ?> checked <?php } ?> name="categorydisplay" value="1" size=45  /></td>
								</tr>
								<!--  Display title on Home  , Category details page  -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Enable video title in Home page', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if (isset($player_colors['showTitle']) && $player_colors['showTitle'] == 1 ) { ?> checked <?php } ?> name="showTitle" value="1" size=45  /></td>
								</tr>
								<!--  Display Description on the player-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Show Description', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="showTag" <?php if ( $settingsGrid->showTag == 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<!--  Display Default Image-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Default Image', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="imageDefault" <?php if ( $settingsGrid->imageDefault == 1 ) { ?> checked <?php } ?> value="1" size=45  /></td>
								</tr>
								<!--  Subtitle settings starts here-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Subtitle Text Color', 'video_gallery' ); ?></th>
									<td>
										<input type='text' name="subTitleColor" value="<?php if ( ! empty( $player_colors['subTitleColor'] ) ) { echo balanceTags( $player_colors['subTitleColor'] ); } ?>" size=45  />
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Subtitle Background Color', 'video_gallery' ); ?></th>
									<td>
										<input type='text' name="subTitleBgColor" value="<?php if ( ! empty( $player_colors['subTitleBgColor'] ) ) { echo balanceTags( $player_colors['subTitleBgColor'] ); } ?>" size=45  />
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Subtitle Font Family', 'video_gallery' ); ?></th>
									<td>
										<input type='text' name="subTitleFontFamily" value="<?php if ( ! empty( $player_colors['subTitleFontFamily'] ) ) { echo balanceTags( $player_colors['subTitleFontFamily'] ); } ?>" size=45  />
									</td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Subtitle Font Size', 'video_gallery' ); ?></th>
									<td>
										<input type='text' name="subTitleFontSize" value="<?php if ( ! empty( $player_colors['subTitleFontSize'] ) ) { echo balanceTags( $player_colors['subTitleFontSize'] ); } ?>" size=45  />
									</td>
								</tr>
								<!--  Subtitle settings ends here-->
							</table>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Player Color Settings', 'video_gallery' ); ?> <?php esc_attr_e( 'Ex : 0xdddddd ', 'video_gallery' ) ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<!-- Share Popup Header color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Share Popup Header Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="sharepanel_up_BgColor" value="<?php echo balanceTags( $player_colors['sharepanel_up_BgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Share Popup Background color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Share Popup Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="sharepanel_down_BgColor" value="<?php echo balanceTags( $player_colors['sharepanel_down_BgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Share Popup Text color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Share Popup Text Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="sharepaneltextColor" value="<?php echo balanceTags( $player_colors['sharepaneltextColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Send Button Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Send Button Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="sendButtonColor" value="<?php echo balanceTags( $player_colors['sendButtonColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Send Button Text Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Send Button Text Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="sendButtonTextColor" value="<?php echo balanceTags( $player_colors['sendButtonTextColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Player Text Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Player Text Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="textColor" value="<?php echo balanceTags( $player_colors['textColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Skin Background Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Skin Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="skinBgColor" value="<?php echo balanceTags( $player_colors['skinBgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Seek Bar Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Seek Bar Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="seek_barColor" value="<?php echo balanceTags( $player_colors['seek_barColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Buffer Bar Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Buffer Bar Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="buffer_barColor" value="<?php echo balanceTags( $player_colors['buffer_barColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Skin Icons Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Skin Icons Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="skinIconColor" value="<?php echo balanceTags( $player_colors['skinIconColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Progress Bar Background Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Progress Bar Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="pro_BgColor" value="<?php echo balanceTags( $player_colors['pro_BgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Play Button Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Play Button Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="playButtonColor" value="<?php echo balanceTags( $player_colors['playButtonColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Play Button Background Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Play Button Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="playButtonBgColor" value="<?php echo balanceTags( $player_colors['playButtonBgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Player Buttons Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Player Buttons Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="playerButtonColor" value="<?php echo balanceTags( $player_colors['playerButtonColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Player Buttons Background Color -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Player Buttons Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="playerButtonBgColor" value="<?php echo balanceTags( $player_colors['playerButtonBgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Related Videos Background Color -->
								<tr class="gallery_separator" id="related_bgColor" style="display:none;">
									<th scope='row'><?php esc_attr_e( 'Related Videos Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="relatedVideoBgColor" value="<?php echo balanceTags( $player_colors['relatedVideoBgColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Related Videos Scroll Bar Color -->
								<tr class="gallery_separator" id="related_scroll_barColor" style="display:none;">
									<th scope='row'><?php esc_attr_e( 'Related Videos Scroll Bar Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="scroll_barColor" value="<?php echo balanceTags( $player_colors['scroll_barColor'] ); ?>" size=45  />
									</td>
								</tr>
								<!-- Related Videos Scroll Bar Background Color -->
								<tr class="gallery_separator" id="related_scroll_barbgColor" style="display:none;">
									<th scope='row'><?php esc_attr_e( 'Related Videos Scroll Bar Background Color', 'video_gallery' ); ?></th>
									<td><input type='text' name="scroll_BgColor" value="<?php echo balanceTags( $player_colors['scroll_BgColor'] ); ?>" size=45  />
									</td>
								</tr>
							</table>
						</div>
					</div>


				</div>
				<div class="column">

					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Playlist Configuration', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Playlist', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="playlist" <?php if ( $settingsGrid->playlist == 1 ) { ?> checked <?php } ?> value="1"  /></td>

								</tr>
								<tr class="gallery_separator" id="related_playlist_open" style="display:none;">
									<th scope='row'><?php esc_attr_e( 'Playlist Open', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="playlist_open" <?php if ( $settingsGrid->playlist_open == 1 ) { ?> checked <?php } ?> value="1"  /></td>

								</tr>
								<tr class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'HD Default', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' name="HD_default" <?php if ( $settingsGrid->HD_default == 1 ) { ?> checked <?php } ?> value="1"  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Playlist Autoplay', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->playlistauto == 1 ) { ?> checked <?php } ?> name="playlistauto" value="1" /></td>

								</tr>
								<!-- Select Related Video View-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Related Video View', 'video_gallery' ); ?></th>
									<td>
										<select name="relatedVideoView" onchange="enablerelateditems( this.value )">
											<option value="side" <?php if ( $settingsGrid->relatedVideoView == 'side' ) { echo 'selected=selected'; } ?>>side</option>
											<option value="center" <?php if ( $settingsGrid->relatedVideoView == 'center' ) { echo 'selected=selected'; } ?>>center</option>
										</select>
								</tr>
							</table>
						</div>
					</div>

					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Ads Settings', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<!-- Preroll -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Preroll Ads', 'video_gallery' ); ?></th>
									<td>
										<input name="preroll" id="preroll" type='radio' value="0"  <?php if ( $settingsGrid->preroll == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="preroll" id="preroll" type='radio' value="1"  <?php if ( $settingsGrid->preroll == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<!-- Postroll -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Postroll Ads', 'video_gallery' ); ?></th>
									<td>
										<input name="postroll" id="postroll" type='radio' value="0"  <?php if ( $settingsGrid->postroll == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="postroll" id="postroll" type='radio' value="1"  <?php if ( $settingsGrid->postroll == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<!-- Midroll Ads -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Midroll Ads', 'video_gallery' ); ?></th>
									<td>
										<input name="midroll_ads" id="midroll_ads" type='radio' value="0"  <?php if ( $settingsGrid->midroll_ads == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="midroll_ads" id="midroll_ads" type='radio' value="1"  <?php if ( $settingsGrid->midroll_ads == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<!-- IMA Ads -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'IMA Ads', 'video_gallery' ); ?></th>
									<td>
										<input name="imaAds" id="imaAds" type='radio' value="0"  <?php if ( $settingsGrid->imaAds == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="imaAds" id="imaAds" type='radio' value="1"  <?php if ( $settingsGrid->imaAds == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
                                <!-- Google adsense option -->
                                <tr class="gallery_separator">
                                    <th scope='row'><?php esc_attr_e( 'Google Ads', 'video_gallery' ); ?></th>
									<td>
										<input name="googleadsense_visible" id="googleadsense_visible" type='radio' value="1"  <?php if ( isset($player_colors['googleadsense_visible']) && ($player_colors['googleadsense_visible'] == 1 ) ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="googleadsense_visible" id="googleadsense_visible" type='radio' value="0"  <?php if ( isset($player_colors['googleadsense_visible']) && ($player_colors['googleadsense_visible'] == 0 ) ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
                                </tr>
								<!-- Ad Skip -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Ad Skip', 'video_gallery' ); ?></th>
									<td>
										<input name="adsSkip" id="adsSkip" type='radio' value="0"  <?php if ( $settingsGrid->adsSkip == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input name="adsSkip" id="adsSkip" type='radio' value="1"  <?php if ( $settingsGrid->adsSkip == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<!-- Ad Skip Duration -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Ad Skip Duration', 'video_gallery' ); ?></th>
									<td><input type='text' name="adsSkipDuration" value="<?php echo balanceTags( $settingsGrid->adsSkipDuration ); ?>" size=45  />
									</td>
								</tr>
								<!-- Track Code -->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Track Code', 'video_gallery' ); ?></th>
									<td><input type='text' id="trackcode" name="trackCode" value="<?php echo balanceTags( $settingsGrid->trackCode ); ?>" size=45  />
									<div id="trackcodeerror"></div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Comment Settings', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Select Comment Type', 'video_gallery' ); ?></th>
									<td>
										<select name="comment_option" onchange="enablefbapi( this.value )">
											<option value="0" <?php if ( $settingsGrid->comment_option == 0 ) { echo 'selected=selected'; } ?>>None</option>
											<option value="1" <?php if ( $settingsGrid->comment_option == 1 ) { echo 'selected=selected'; } ?>>Default Comment</option>
											<option value="2" <?php if ( $settingsGrid->comment_option == 2 ) { echo 'selected=selected'; } ?>>Facebook Comment</option>
											<option value="3" <?php if ( $settingsGrid->comment_option == 3 ) { echo 'selected=selected'; } ?>>DisQus Comment</option>
										</select>

								</tr>
								<tr class="gallery_separator" id="facebook_api" style="display: none;" >
									<th scope='row'><?php esc_attr_e( 'App ID', 'video_gallery' ); ?></th>
									<td><input type='text' name="keyApps" value="<?php echo balanceTags( $settingsGrid->keyApps ); ?>" size=45  /></td>
								</tr>
								<tr class="gallery_separator" id="disqus_api" style="display: none;" >
									<th scope='row'><?php esc_attr_e( 'Shot Name', 'video_gallery' ); ?></th>
									<td><input type='text' name="keydisqusApps" value="<?php echo balanceTags( $settingsGrid->keydisqusApps ); ?>" size=45  /></td>
								</tr>
								<tr class="gallery_separator" id="facebook_api_link" style="display: none;" ><th> <a href="http://developers.facebook.com/" target="_blank"><?php esc_attr_e( 'Create Facebook App ID', 'video_gallery' ); ?></a></th></tr>
							</table>
						</div>
					</div>

					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Skin Configuration', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Timer', 'video_gallery' ); ?></th>
									<td>
										<input type='checkbox' class='check'  name="timer" <?php if ( $settingsGrid->timer == 1 ) { ?> checked <?php } ?> value="1" /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Zoom', 'video_gallery' ); ?> </th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->zoom == 1 ) { ?> checked <?php } ?> name="zoom" value="1" />&nbsp;( Not supported for viddler videos )</td>
								</tr>
								<!-- Display Email Icon-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Email', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check'  name="email" <?php if ( $settingsGrid->email == 1 ) { ?> checked <?php } ?>value="1"   /></td>
								</tr>
								<!-- Display Share Icon-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Share', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check'  name="shareIcon" <?php if ( $settingsGrid->shareIcon == 1 ) { ?> checked <?php } ?>value="1"   /></td>
								</tr>
								<!-- Display Volume Icon-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Volume', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check'  name="volumecontrol" <?php if ( $settingsGrid->volumecontrol == 1 ) { ?> checked <?php } ?>value="1"   /></td>
								</tr>
								<!-- Display Progress Bar-->
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Progress Bar', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check'  name="progressControl" <?php if ( $settingsGrid->progressControl == 1 ) { ?> checked <?php } ?>value="1"   /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Display Full Screen', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->fullscreen == 1 ) { ?> checked <?php } ?> name="fullscreen" value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Skin Autohide', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( $settingsGrid->skin_autohide == 1 ) { ?> checked <?php } ?> name="skin_autohide" value="1" size=45  /></td>
								</tr>
								<tr  class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Skin Visible', 'video_gallery' ); ?></th>
									<td><input type='checkbox' class='check' <?php if ( isset( $player_colors['skinVisible'] ) && $player_colors['skinVisible'] == 1 ) { ?> checked <?php } ?> name="skinVisible" value="1" size=45  /></td>
								</tr>
								<tr class="gallery_separator">
									<th scope='row'><?php esc_attr_e( 'Skin Opacity', 'video_gallery' ); ?></th>
									<td><input type='text' name="skin_opacity" value="<?php if ( isset( $player_colors['skin_opacity'] ) ) { echo balanceTags( $player_colors['skin_opacity'] ); } ?>" size=45  />
										<br/> ( Range from 0 to 1 )
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-header"><b><?php esc_attr_e( 'Videos Page Settings', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
							<table class="form-table">

								<!--videos page banner settings-->

								<!-- Popular Videos-->
								<tr class="gallery_separator">
								<th><?php esc_attr_e( 'Gutter Space ( px )', 'video_gallery' ); ?></th>
									<td><input type="text" name="gutterspace" id="gutterspace" size="20" value="<?php echo balanceTags( $settingsGrid->gutterspace ); ?>"></td>
								</tr>								
								<!-- Related video count setting -->
								<tr class="gallery_separator">
								<th><?php esc_attr_e( 'Limit Related Videos count', 'video_gallery' ); ?></th>
									<td><input type="text" name="related_video_count" id="related_video_count" size="20" value="<?php echo balanceTags( $player_colors['related_video_count']); ?>"></td>
								</tr>
								<tr class="gallery_separator"><th><?php esc_attr_e( 'No Of Categories in Home page', 'video_gallery' ); ?></th>
									<td><input type="text" name="category_page" id="category_page" size="20" value="<?php echo balanceTags( $settingsGrid->category_page ); ?>"></td>
								</tr>
								 <!-- Order selected by the recent videos -->
                                 <tr class="gallery_separator">
									<th><?php esc_attr_e('Videos Order', 'video_gallery' ); ?></th>
									<td>
                                       <select name="recent_video_order" class="recent_video_order_setting">
                                           <option value="id" <?php if($player_colors['recentvideo_order'] =='id'){ echo "selected='selected'";} ?>><?php echo esc_attr('Recent','video_gallery'); ?> </option>        
                                           <option value="hitcount" <?php if($player_colors['recentvideo_order'] =='hitcount'){ echo "selected='selected'";} ?>><?php echo esc_attr('Most viewed','video_gallery'); ?> </option> 
                                           <option value="default" <?php if($player_colors['recentvideo_order'] =='default'){ echo "selected='selected'";} ?>><?php echo esc_attr('Default ( Ordering)','video_gallery'); ?> </option>                                                                            
                                        </select>
                                        <div><?php echo esc_attr('Only Applicable for Featured and  Category Videos','video_gallery');?></div>
                                     </td>
								</tr>	
								<tr class="gallery_separator">

									<th><?php esc_attr_e( 'Popular Videos', 'video_gallery' ); ?></th>
									<td>
										<input  type='radio' name="popular"  value="1" <?php if ( $settingsGrid->popular == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input type='radio' name="popular"  value="0"  <?php if ( $settingsGrid->popular == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<tr class="gallery_separator_row">
									<td><label><?php esc_attr_e( 'Rows', 'video_gallery' ); ?></label><input type="text" name="rowsPop" id="rowsPop" size="10" value="<?php echo balanceTags( $settingsGrid->rowsPop ); ?>"></td>
									<td><label><?php esc_attr_e( 'Columns', 'video_gallery' ); ?> </label><input type="text" name="colPop" id="colPop" size="10" value="<?php echo balanceTags( $settingsGrid->colPop ); ?>"></td>
								</tr>

								<!-- Recent Videos-->
								<tr class="gallery_separator">
									<th><?php esc_attr_e( 'Recent Videos', 'video_gallery' ); ?></th>
									<td>
										<input type='radio' name="recent"  value="1" <?php if ( $settingsGrid->recent == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input type='radio' name="recent"  value="0"  <?php if ( $settingsGrid->recent == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<tr class="gallery_separator_row">
									<td><label><?php esc_attr_e( 'Rows', 'video_gallery' ); ?></label><input type="text" name="rowsRec" id="rowsRec" size="10" value="<?php echo balanceTags( $settingsGrid->rowsRec ); ?>"></td>
									<td><label><?php esc_attr_e( 'Columns', 'video_gallery' ); ?> </label><input type="text" name="colRec" id="colRec" size="10" value="<?php echo balanceTags( $settingsGrid->colRec ); ?>">
									</td>
								</tr>
								<!-- Featured Videos  -->
								<tr class="gallery_separator">
									<th><?php esc_attr_e( 'Featured Videos', 'video_gallery' ); ?></th>
									<td>
										<input type='radio' name="feature"  value="1" <?php if ( $settingsGrid->feature == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input  type='radio' name="feature" value="0" <?php if ( $settingsGrid->feature == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<tr class="gallery_separator_row"><td><label><?php esc_attr_e( 'Rows', 'video_gallery' ); ?></label><input type="text" name="rowsFea" id="rowsFea" size="10" value="<?php echo balanceTags( $settingsGrid->rowsFea ); ?>"></td>
									<td><label><?php esc_attr_e( 'Columns', 'video_gallery' ); ?></label><input type="text" name="colFea" id="colFea" size="10" value="<?php echo balanceTags( $settingsGrid->colFea ); ?>">
									</td>
								</tr>


								<tr class="gallery_separator">
									<th><?php esc_attr_e( 'Category Videos', 'video_gallery' ); ?></th>
									<td>
										<input type='radio' name="homecategory"  value="1" <?php if ( $settingsGrid->homecategory == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input type='radio' name="homecategory"  value="0" <?php if ( $settingsGrid->homecategory == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>

								<tr class="gallery_separator_row">
									<td><label><?php esc_attr_e( 'Rows', 'video_gallery' ); ?></label><input type="text" name="rowCat" id="rowCat" size="10" value="<?php echo balanceTags( $settingsGrid->rowCat ); ?>"></td>
									<td><label><?php esc_attr_e( 'Columns', 'video_gallery' ); ?></label><input type="text" name="colCat" id="colCat" size="10" value="<?php echo balanceTags( $settingsGrid->colCat ); ?>">
									</td>
								</tr>
								
								<tr class="gallery_separator"><th><?php esc_attr_e( 'More Page', 'video_gallery' ); ?></th>

								</tr>

								<tr class="gallery_separator_row"><td><label><?php esc_attr_e( 'Rows', 'video_gallery' ); ?></label><input type="text" name="rowMore" id="rowMore" size="10" value="<?php echo balanceTags( $settingsGrid->rowMore ); ?>"></td>
									<td><label><?php esc_attr_e( 'Columns', 'video_gallery' ); ?></label><input type="text" name="colMore" id="colMore" size="10" value="<?php echo balanceTags( $settingsGrid->colMore ); ?>">
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="portlet">
					<div class="portlet-header"><b><?php esc_attr_e( 'Amazon S3 Bucket Setting', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
						    <table class="form-table">								
								<tr class="gallery_separator">
									<th><?php esc_attr_e( 'Store Videos in Amazon S3 Bucket', 'video_gallery' ); ?></th>
									<td>
										<input  type='radio' name="amazonbuckets_enable" id="amazonbuckets_enable" checked="checked" value="1" <?php if ( isset($player_colors['amazonbuckets_enable']) && $player_colors['amazonbuckets_enable'] == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input  type='radio' name="amazonbuckets_enable" id="amazonbuckets_enable" value="0" <?php if ( isset($player_colors['amazonbuckets_enable']) && $player_colors['amazonbuckets_enable'] == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
									</td>
								</tr>
								<tr class="gallery_separator">
								 	<th><?php esc_attr_e( 'Enter Amazon S3 Bucket name', 'video_gallery' ); ?></th>
									<td><input type="text" name="amazonbuckets_name" id="amazonbuckets_name" size="20" value="<?php if(isset( $player_colors['amazonbuckets_name'])){ echo balanceTags( $player_colors['amazonbuckets_name'] );} ?>"></td>
								</tr>
								<tr class="gallery_separator">
								 	<th><?php esc_attr_e( 'Enter Amazon S3 Bucket link', 'video_gallery' ); ?></th>
									<td><input type="text" name="amazonbuckets_link" id="amazonbuckets_link" size="20" value="<?php if(isset( $player_colors['amazonbuckets_link'])){ echo balanceTags( $player_colors['amazonbuckets_link'] );} ?>"></td>
								</tr>
								<tr class="gallery_separator">
								 	<th><?php esc_attr_e( 'Enter Amazon S3 Bucket Access Key', 'video_gallery' ); ?></th>
									<td><input type="text" name="amazon_bucket_access_key" id="amazon_bucket_access_key" size="20" value="<?php if(isset( $player_colors['amazon_bucket_access_key'])){ echo balanceTags( $player_colors['amazon_bucket_access_key'] );} ?>"></td>
								</tr><tr class="gallery_separator">
								 	<th><?php esc_attr_e( 'Enter Amazon S3 Bucket Access Secret Key', 'video_gallery' ); ?></th>
									<td><input type="text" name="amazon_bucket_access_secretkey" id="amazon_bucket_access_secretkey" size="20" value="<?php if(isset( $player_colors['amazon_bucket_access_secretkey'])){ echo balanceTags( $player_colors['amazon_bucket_access_secretkey'] );} ?>"></td>
								</tr>								
						    </table>
						  
					    </div>
					 </div>   	
					 <div class="portlet">
					<div class="portlet-header"><b><?php esc_attr_e( 'User Video Setting', 'video_gallery' ); ?></b></div>
						<div class="portlet-content">
						    <table class="form-table">
						    <tr class="gallery_separator">
							 	<th><?php esc_attr_e( 'Enter Youtube API Key', 'video_gallery' ); ?></th>
									<td><input type="text" name="youtube_key" id="youtube_key" size="20" value="<?php if(isset( $player_colors['youtube_key'])){ echo balanceTags( $player_colors['youtube_key'] );} ?>"></td>
								</tr>	
						    <tr class="gallery_separator">
						         <th><?php esc_attr_e( 'Video Upload Option to Members', 'video_gallery' ); ?></th>
						         <td>
										<input  type='radio' name="member_upload_enable" id="member_upload_enable" checked="checked" value="1" <?php if ( isset($player_colors['member_upload_enable']) && $player_colors['member_upload_enable'] == 1 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Enable', 'video_gallery' ); ?></label>
										<input  type='radio' name="member_upload_enable" id="member_upload_enable" value="0" <?php if ( isset($player_colors['member_upload_enable']) && $player_colors['member_upload_enable'] == 0 ) { echo 'checked'; } ?> /><label><?php esc_attr_e( 'Disable', 'video_gallery' ); ?></label>
								</td>
						      </tr>
						      <tr class="gallery_separator" >
						            <th><?php esc_attr_e( 'Select upload method(s) for users', 'video_gallery' ); ?></th>
									<td> <?php echo esc_attr_e('( Press ctrl button and Choose Multiple Option)','video_gallery');?><?php $allowed_method = explode(',',$player_colors['user_allowed_method']); ?>
										<span><select name="user_allowed_method[]" size="5" multiple="multiple" >
											<option value="c" <?php if(in_array('c',$allowed_method)){ echo 'selected'; } ?>><?php esc_attr_e( 'YouTube URL / Viddler / Dailymotion', 'video_gallery' ); ?></option>
											<option value="y" <?php if(in_array('y',$allowed_method)){ echo 'selected'; } ?>><?php esc_attr_e( 'Upload file', 'video_gallery' ); ?></option>										
											<option value="url" <?php if(in_array('url',$allowed_method)){ echo 'selected'; } ?>><?php esc_attr_e( 'Custom URL', 'video_gallery' ); ?></option>
											<option value="rmtp" <?php if(in_array('rmtp',$allowed_method)){ echo 'selected'; } ?>><?php esc_attr_e( 'RTMP', 'video_gallery' ); ?></option>												
										    <?php if ( isset( $settingsGrid->license ) && ( strpos( $settingsGrid->license ,'CONTUS' ) ) ) { ?>
											<option value="embed" <?php if(in_array('embed',$allowed_method)){ echo 'selected'; } ?>><?php esc_attr_e( 'Embed Video', 'video_gallery' ); ?></option>				
										    <?php }  ?>
										</select>
										</span>
									</td>
						      </tr>
						       </table>
						 </div>
					</div>	    
					<div class="bottom_btn">
						<input class='button-primary' id='videogallery_setting' style="float:right; " name="updatebutton"  type='submit' value='<?php esc_attr_e( 'Update Options', 'video_gallery' ); ?>'>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</form>
	</div>
</div>

<script type="text/javascript">
<?php
if ( isset( $settingsGrid->comment_option ) && $settingsGrid->comment_option == 2 ) {
	?>
		enablefbapi( '2' );
	<?php
} elseif ( isset( $settingsGrid->comment_option ) && $settingsGrid->comment_option == 3 ) {
	?>
		enablefbapi( '3' );
	<?php
}
if ( isset( $settingsGrid->relatedVideoView ) && $settingsGrid->relatedVideoView == 'side' ) {
	?>
		enablerelateditems( 'side' );
	<?php
} elseif ( isset( $settingsGrid->relatedVideoView ) && $settingsGrid->relatedVideoView == 'center' ) {
	?>
		enablerelateditems( 'center' );
	<?php
}
?>
</script>