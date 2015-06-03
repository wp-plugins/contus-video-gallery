<?php
/**
 * Video gallery admin video view file
 * All Video manage admin page  
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
$dir = dirname ( plugin_basename ( __FILE__ ) );
$dirExp = explode ( '/', $dir );
$dirPage = $dirExp [0];
$page = $ordervalue = '';
$url = get_site_url () . '/wp-admin/admin.php?page=video';
$pagenum = absint ( filter_input ( INPUT_GET, 'pagenum' ) );
if ($pagenum) {
	$page = '&pagenum=' . $_GET ['pagenum'];
}
?>
<link rel="stylesheet"
	href="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ). 'admin/css/jquery.ui.all.css'; ?>">
<script type="text/javascript">
// When the document is ready set up our sortable with it's inherant function( s )
	var dragdr = jQuery.noConflict();
	var videoid = new Array();
	dragdr( document ).ready( function() {
		dragdr( "#test-list" ).sortable( {
			handle: '.handle',
			update: function() {
				var order = dragdr( '#test-list' ).sortable( 'serialize' );
				orderid = order.split( "listItem[]=" );
				for ( i = 1; i < orderid.length; i++ )
				{
					videoid[i] = orderid[i].replace( '&', "" );
					oid = "ordertd_" + videoid[i];
				}
				dragdr.post( "<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php?action=videosortorder<?php echo balanceTags( $page ); ?>", order );
			}
		} );
        dragdr( ".portlet-content" ).hide();
		dragdr( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
				.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-plusthick'></span>" )
				.end()
				.find( ".portlet-content" );

		dragdr( ".portlet-header .ui-icon" ).click( function() {
			dragdr( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
			dragdr( this ).parents( ".portlet" ).find( ".portlet-content" ).toggle();
		} );
	} );
</script>
<div class="apptha_gallery">
	<!--   MENU OPTIONS STARTS  --->
	<h2 class="nav-tab-wrapper">
		<a href="?page=video" class="nav-tab nav-tab-active"><?php esc_attr_e( 'All Videos', 'video_gallery' ); ?></a>
		<a href="?page=playlist" class="nav-tab"><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></a>
		<a href="?page=videoads" class="nav-tab"><?php esc_attr_e( 'Video Ads', 'video_gallery' ); ?></a>
		<a href="?page=hdflvvideosharesettings" class="nav-tab"><?php esc_attr_e( 'Settings', 'video_gallery' ); ?></a>
		<a href="?page=googleadsense" class="nav-tab"><?php esc_attr_e( 'Google AdSense', 'video_gallery' ); ?></a>
	</h2>
	<!--  MENU OPTIONS ENDS --->
	<?php
	$selfurl = get_site_url () . '/wp-admin/admin.php?page=video' . $page;
	?>    <div class="wrap">
		<h2 class="option_title">
			<?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . '/images/manage_video.png" alt="move" width="30"/>'; ?>
			<?php esc_attr_e( 'Manage Videos', 'video_gallery' ); ?><a
				class="button-primary"
				href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideo"
				style="margin-left: 10px;"><?php esc_attr_e( 'Add New', 'video_gallery' ); ?></a>
		</h2>
			<?php
			function get_current_user_role() {
				global $current_user;
				get_currentuserinfo ();
				$user_roles = $current_user->roles;
				$user_role = array_shift ( $user_roles );
				return $user_role;
			}
			$user_role = get_current_user_role ();
			if ($user_role != 'subscriber') {
				?>
			<div class="show-hide-intro-shortcode">
			<div class="portlet">
				<div class="portlet-header"><?php esc_attr_e( 'How To Use?', 'video_gallery' ); ?></div>
				<div class="portlet-content admin_short_video_info">
						<p> Once you installed <strong>"Wordpress Video Gallery"</strong> plugin, the page <strong>"Videos"</strong> will be created automatically. If you would like to display the video gallery home page on any other page/post, you can use the following shortcode <strong>[videohome]</strong>.
						</p>
						<p>To display the single video player on any page/post use <strong> [hdvideo id=10]</strong>. This shortcode will have the following parameters.
						</p>
						<table class="info_videoshartcode" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>
										<p>
											Parameters
										</p>
									</th>
									<th>
										<p>  Description	</p>
									</th>
								</tr>
								</thead>
								<tbody>
								<tr >
									<td>id</td>
									<td>
										<p>Video ID where you can find in <strong>&ldquo;All Videos &rdquo;</strong> tab
										</p>
									</td>
								</tr>
								<tr >
									<td>
										playlistid
									</td>
									<td>
										<p>
											Category ID where you can find in <strong>&ldquo;Categories&rdquo;</strong> tab
										</p>
									</td>
								</tr>
								<tr >
									<td>
											width
									</td>
									<td>
										<p>
											Specify	the width of the player
										</p>
									</td>
								</tr>
								<tr >
									<td>
											height
									</td>
									<td>
										<p>
											Specify	the height of the player
										</p>
									</td>
								</tr>
								<tr >
									<td>
											relatedvideos
									</td>
									<td>
										<p>
											You	can enable/disable the related videos slider under the
													player by on/off this parameter <strong>(eg: relatedvideos=on)</strong>. If
													you didn't mention this parameter then it will be in
													<strong>&ldquo;off&rdquo;</strong> status.
										</p>
									</td>
								</tr>
								<tr >
									<td>
											ratingscontrol
									</td>
									<td>
										<p>
											Enable/disable the ratings below the player in any post/page by using the value <strong>on/off</strong>.
										</p>
									</td>
								</tr>
								<tr >
									<td>
											title
									</td>
									<td>
										<p>
											Enable/disable the title below the player in any post/page by using the value <strong>on/off</strong>.
										</p>
									</td>
								</tr>
								<tr >
									<td>
											Views
									</td>
									<td>
										<p>
											Enable/disable the views below the player in any post/page by using the value <strong>on/off</strong>.
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p>	You	can also control the player settings for a specific video by including <strong>&ldquo; flashvars&rdquo;</strong> parameter in the shortcode. Flashvars parameter will include the following options.</p>
						<table class="info_videoshartcode" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th>
										<p>
											Parameters
										</p>
									</th>
									<th>
										<p>  Description	</p>
									</th>
								</tr>
								</thead>
								<tbody>
								<tr >
									<td>
										autoplay
									</td>
									<td>
										<p>This will play the video automatically if you specify with value <strong>&ldquo;true&rdquo;</strong>.
										</p>
									</td>
								</tr>
								<tr >
									<td>
										ZoomIcon
									</td>
									<td>
										<p>
											This will enable zoom icon on the player if you specify with
													value &ldquo;true&rdquo; <strong>(eg :[hdvideo id=4
													flashvars=autoplay=true&amp;zoomIcon=false]</strong>. which
													will enable autoplay and disable zoom icon).
											
										</p>
									</td>
								</tr>
								<tr >
									<td>
										
											email
									</td>
									<td>
										<p>
This will enable email option on the player when you click share
													icon if you specify with value <strong>&ldquo;true&rdquo;</strong>										</p>
									</td>
								</tr>
								<tr >
									<td>
											shareIcon
									</td>
									<td>
										<p>
											This will enable share icon on the player if you specify with value <strong>&ldquo;true&rdquo;</strong>
										</p>
									</td>
								</tr>
								<tr >
									<td>
											fullscreen
									</td>
									<td>
										<p>
											This will enable fullscreen option on the player if you specify
													with value  <strong>&ldquo;true&rdquo;</strong>
										</p>
									</td>
								</tr>
								<tr >
									<td>
											volumecontrol
									</td>
									<td>
										<p>
											Hide/show the volume control on the player with the value <strong>false/true</strong>
										</p>
									</td>
								</tr>
								<tr >
									<td>
											playlist_auto
									</td>
									<td>
										<p>
											Autoplay the videos from playlist using <strong>false/true</strong> value.
										</p>
									</td>
								</tr>
								<tr >
									<td>
											progressControl
									</td>
									<td>
										<p>
											Hide/show the progress bar by specifying the value <strong>false/true</strong>
										
										</p>
									</td>
								</tr>
								<tr >
									<td>
											skinVisible
									</td>
									<td>
										<p>
											Hide/show the entire skin by specifying the value <strong>false/true</strong>
										
										</p>
									</td>
								</tr><tr >
									<td>
											timer
									</td>
									<td>
										<p>
											Hide/show timer by specifying the value <strong>false/true</strong>
										</p>
									</td>
								</tr><tr >
									<td>
											Download
									</td>
									<td>
										<p>
											Hide/show download button by specifying the value <strong>false/true</strong>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p>
							You	can also get more parameters from <strong><?php  echo admin_url('admin-ajax.php?action=configXML'); ?></strong>.
							
						</p>
						
						<p>
							To display a particular category videos thumb images in any post/page you can use the following shortcode <strong>[categoryvideothumb type=category id=2]</strong> - which will fetch thumb images from category ID 2.	
						</p>
						<p>
							To
									display popular videos thumb images in any post/page you can
									use the shortcode <strong>[popularvideo]</strong>
						</p>
						<p>
							To display recent videos thumb images in any post/page you can use the shortcode <strong>[recentvideo]</strong>
						</p>
						<p>
							To display featured videos thumb images in any post/page you can use the shortcode <strong>[featuredvideo]</strong>
						</p>
				</div>
			</div>
			<div class="portlet">
				<div class="portlet-header"><?php esc_attr_e('How to use RSS Feeds?','video_gallery'); ?></div>
				<div class="portlet-content admin_short_video_info">
			    <?php esc_attr_e('Mentioned below are the appropriate URLs to get RSS Feeds for:','video_gallery');?><br>
					<br>
			    <?php esc_attr_e('Popular Videos','video_gallery'); ?> - <strong><?php esc_attr_e(get_site_url().'/wp-admin/admin-ajax.php?action=rss&task=popular','video_gallery');?></strong><br>
					<br>
			    <?php esc_attr_e('Featured Videos','video_gallery'); ?> - <strong><?php esc_attr_e(get_site_url().'/wp-admin/admin-ajax.php?action=rss&task=featured','video_gallery');?></strong><br>
					<br>
			   	<?php esc_attr_e('Recent Videos','video_gallery'); ?> - <strong><?php esc_attr_e(get_site_url().'/wp-admin/admin-ajax.php?action=rss&task=recent','video_gallery');?></strong><br>
					<br>
			    <?php esc_attr_e('Any particular playlist','video_gallery');?> - <strong><?php esc_attr_e(get_site_url().'/wp-admin/admin-ajax.php?action=rss&task=category','video_gallery');?></strong><br>
					<br>
			    <?php esc_attr_e('Any particular video','video_gallery');?> - <strong><?php esc_attr_e(get_site_url().'/wp-admin/admin-ajax.php?action=rss&task=video&vid=1','video_gallery');?></strong><br>
					<br>
				</div>
			</div>
		</div>
		
			<?php
			}
			if ($displayMsg) :
				?>
			<div class="updated below-h2">
			<p>
			<?php echo balanceTags( $displayMsg ); ?>
				</p>
		</div>
			
			<?php
		endif;
			$orderFilterlimit = filter_input ( INPUT_GET, 'filter' );
			$orderField = filter_input ( INPUT_GET, 'order' );
			$orderby = filter_input ( INPUT_GET, 'orderby' );
			$direction = isset ( $orderField ) ? $orderField : false;
			if (! empty ( $orderby ) && ! empty ( $orderField )) {
				$ordervalue = '&orderby=' . $orderby . '&order=' . $orderField;
			}
			
			$reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
			if (isset ( $_REQUEST ['videosearchbtn'] )) {
				?>
	<div class="updated below-h2">
		<?php
				$searchmsg = filter_input ( INPUT_POST, 'videosearchQuery' );
				if (count ( $gridVideo )) {
					echo balanceTags ( count ( $gridVideo ) ) . '   Search Result( s ) for "' . $searchmsg . '".&nbsp&nbsp&nbsp<a href="' . $url . '" >Back to Videos List</a>';
				} else {
					echo ' No Search Result( s ) for "' . $searchmsg . '".&nbsp&nbsp&nbsp<a href="' . $url . '" >Back to Videos List</a>';
				}
				?>
	</div>
			   <?php } ?>
		<form class="admin_video_search alignright" name="videos"
			action="<?php echo balanceTags( $url ) . '&#videofrm'; ?>"
			method="post" onsubmit="return videosearch();">
			<p class="search-box">
				<input type="text" name="videosearchQuery" id="VideosearchQuery"
					value="<?php if ( isset( $searchmsg ) ) echo balanceTags( $searchmsg ); ?>">
				<input type="hidden" name="page" value="videos"> <input
					type="submit" name="videosearchbtn" class="button"
					value="<?php esc_attr_e( 'Search Videos', 'video_gallery' ); ?>">
			</p>
		</form>
		<form class="admin_video_action" name="videofrm" id="videofrm"
			action="" method="post" onsubmit="return VideodeleteIds()">
			<div class="alignleft actions" style="margin-bottom: 10px;">
				<select name="videoactionup" id="videoactionup">
					<option value="-1" selected="selected"><?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?></option>
					<option value="videodelete"><?php esc_attr_e( 'Delete', 'video_gallery' ); ?></option>
					<option value="videopublish"><?php esc_attr_e('Publish','video_gallery'); ?></option>
					<option value="videounpublish"><?php esc_attr_e('Unpublish','video_gallery'); ?></option>
					<option value="videofeatured"><?php esc_attr_e('Add to Featured','video_gallery'); ?></option>
					<option value="videounfeatured"><?php esc_attr_e('Remove from Feature','video_gallery'); ?></option>
				</select> <input type="submit" name="videoapply"
					class="button-secondary action"
					value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?> ">
			</div>
			<div class="alignleft actions responsive"
				style="margin-bottom: 10px;">
				<select name="videofilteraction" id="videofilteraction"
					onchange="window.location.href = this.value">
					<option value="" selected="selected">Select Limit</option>
					<option
						<?php
						
if ($orderFilterlimit == 5) {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ).$ordervalue; ?>&filter=5#videofrm">5</option>
					<option
						<?php
						
if ($orderFilterlimit == 10) {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ) . $ordervalue; ?>&filter=10#videofrm">10</option>
					<option
						<?php
						
if ($orderFilterlimit == 20) {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ). $ordervalue; ?>&filter=20#videofrm">20</option>
					<option
						<?php
						
if ($orderFilterlimit == 50) {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ). $ordervalue; ?>&filter=50#videofrm">50</option>
					<option
						<?php
						
if ($orderFilterlimit == 100) {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ). $ordervalue; ?>&filter=100#videofrm">100</option>
					<option
						<?php
						
if ($orderFilterlimit == 'all') {
							echo 'selected';
						}
						?>
						value="<?php echo balanceTags( $url ). $ordervalue; ?>&filter=all#videofrm">All</option>
				</select>
			</div>
				<?php
				if (! empty ( $orderFilterlimit ) && $orderFilterlimit !== 'all') {
					$limit = $orderFilterlimit;
				} else if ($orderFilterlimit === 'all') {
					$limit = $Video_count;
				} else {
					$limit = 20;
				}
				$pagenum = absint ( filter_input ( INPUT_GET, 'pagenum' ) );
				if (empty ( $pagenum )) {
					$pagenum = 1;
				}
				$total = $Video_count;
				$num_of_pages = ceil ( $total / $limit );
				$arr_params = array (
						'pagenum' => '%#%',
						'#videofrm' => '' 
				);
				$page_links = paginate_links ( array (
						'base' => add_query_arg ( $arr_params ),
						'format' => '',
						'prev_text' => __ ( '&laquo;', 'aag' ),
						'next_text' => __ ( '&raquo;', 'aag' ),
						'total' => $num_of_pages,
						'current' => $pagenum 
				) );
				
				if ($page_links) {
					echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
				}
				?>
				<br />
			<br />

			<div style="float: right; font-weight: bold;"><?php if ( isset( $pagelist ) ) echo balanceTags( $pagelist ); ?></div>
			<div style="clear: both;"></div>
			<table class="wp-list-table widefat fixed posts" cellspacing="0"
				width="100%">
				<thead>
					<tr>
						<th width="3%" scope="col" style=""
							class="manage-column column-cb check-column"><input
							type="checkbox" name="" id="manage-column-video-1"></th>
						<th width="3%" scope="col" class="manage-column column-ordering">
							<span>
										<?php esc_attr_e( '', 'video_gallery' ); ?> </span><span
							class="sorting-indicator"></span>
						</th>
						<th width="4%" scope="col"
							class="manage-column column-id sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=video&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'ID', 'video_gallery' ); ?> </span><span
								class="sorting-indicator"></span></a></th>
						<th width="6%" scope="col" class="manage-column column-image"><span
							class="sorting-indicator"></span></th>
						<th width="30%" scope="col"
							class="manage-column column-name sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Title', 'video_gallery' ); ?> </span><span
								class="sorting-indicator"></span></a></th>
						<th width="14%" scope="col"
							class="manage-column column-author sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=author&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Author', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="14%" scope="col"
							class="manage-column column-playlistname sortable desc" style="">
							<a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=category&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Categories', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a>
						</th>
						<th width="8%" scope="col"
							class="manage-column column-feature sortable desc text_center"
							style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=fea&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
						<?php esc_attr_e( 'Featured', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="4%" scope="col"
							class="manage-column column-createddate sortable desc"
							style="width: 10%"><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=date&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Date', 'digi' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="7%" scope="col"
							class="text_center manage-column column-publish sortable desc"
							style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="7%" scope="col"
							class="manage-column column-ordering sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=ordering&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Order', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span>
						</a></th>


					</tr>
				</thead>
				<tbody id="test-list" class="list:tag">
<?php
$i = 0;
foreach ( $gridVideo as $videoView ) {
	$i ++;
	?>
							<tr id="listItem_<?php echo balanceTags( $videoView->vid ); ?>">
						<th scope="row" class="check-column"><input type="checkbox"
							name="video_id[]"
							value="<?php echo balanceTags( $videoView->vid ); ?>"></th>

						<td class="column-id">
									<?php if ( $user_role != 'subscriber' ) { ?>
										<span class="hasTip content"
							title="<?php esc_attr_e( 'Click and Drag', 'video_gallery' ); ?>"
							style="padding: 6px;"> <img
								src="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ) . 'images/arrow.png'; ?>"
								alt="move" width="16" height="16" class="handle" />
						</span>
									<?php } ?>
								</td>

						<td class="image column-ordering"><a
							title="Edit <?php echo balanceTags( $videoView->name ); ?>"
							href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideo&videoId=<?php echo balanceTags( $videoView->vid ); ?>"><?php echo balanceTags( $videoView->vid ); ?></a>
						</td>
						<td class="image column-image">
	<?php
	$image_path = str_replace ( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
	$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
	$thumb_image = $videoView->image; // Get thumb image
	$file_type = $videoView->file_type; // Get file type of a video
	if ($thumb_image == '') { // If there is no thumb image for video
		$thumb_image = $_imagePath . 'nothumbimage.jpg';
	} else {
		if ($file_type == 2 || $file_type == 5) { // For uploaded image
			if (strpos ( $thumb_image, '/' )) {
				$thumb_image = $thumb_image;
			} else if ($file_type == 3) {
				$thumb_image = $thumb_image;
			} else {
				$thumb_image = $image_path . $thumb_image;
			}
		}
	}
	?>
									<a title="Edit <?php echo balanceTags( $videoView->name ); ?>"
							href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideo&videoId=<?php echo balanceTags( $videoView->vid ); ?>">
								<img width="60" height="60"
								src="<?php echo balanceTags( $thumb_image ); ?>"
								class="attachment-80x60" alt="Hydrangeas">
						</a>
						</td>
						<td class="column-name"><a
							title="Edit <?php echo balanceTags( $videoView->name ); ?>"
							class="row-title"
							href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideo&videoId=<?php echo balanceTags( $videoView->vid ); ?>"><?php echo balanceTags( $videoView->name ); ?></a>
						</td>
						<td class="description column-author"><?php echo balanceTags( $videoView->display_name ); ?></td>
						<td class="description column-playlistname">
	<?php
	$videoOBJ = new VideoController ();
	$playlistData = $videoOBJ->get_playlist_detail ( $videoView->vid );
	$incre = 0;
	$playlistname = '';
	foreach ( $playlistData as $playlist ) {
		if ($incre > 0) {
			$playlistname .= ', ' . $playlist->playlist_name;
		} else {
			$playlistname .= $playlist->playlist_name;
		}
		$incre ++;
	}
	echo balanceTags ( $playlistname );
	?>
								</td>

						<td class="description column-featured" style="text-align: center"> <?php
	$feaStatus = 1;
	$feaImage = 'deactivate.jpg';
	if ($videoView->featured == 1) {
		$feaStatus = 0;
		$feaImage = 'activate.jpg';
	}
	?>
									<a
							title="<?php if ( $feaStatus == 0 ) { esc_attr_e( 'Remove from featured' ); } else { esc_attr_e( 'Add to Feature' ); } ?>"
							href="<?php echo balanceTags( $selfurl ); ?>&videoId=<?php echo balanceTags( $videoView->vid ); ?>&featured=<?php echo balanceTags( $feaStatus ); ?>">
								<img
								src="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ) . 'images/' . $feaImage ?>" />
						</a>
						</td>
						<td class="description column-createddate"><?php echo date_i18n( get_option('date_format') , strtotime( $videoView->post_date ) ); ?></td>

						<td class="description column-publish column-publish"
							style="text-align: center"><?php
	$status = 1;
	$image = 'deactivate.jpg';
	if ($videoView->publish == 1) {
		$status = 0;
		$image = 'activate.jpg';
	}
	?>
									<a
							title="<?php if ( $status == 0 ) { esc_attr_e( 'Unpublish' ); } else { esc_attr_e( 'publish' ); } ?>"
							href="<?php echo balanceTags( $selfurl ); ?>&videoId=<?php echo balanceTags( $videoView->vid ); ?>&status=<?php echo balanceTags( $status ); ?>">
								<img
								src="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ) . 'images/' . $image ?>" />
						</a></td>
						<td class="column-ordering">
										<?php echo balanceTags( $videoView->ordering ); ?>
								</td>
					</tr>
											<?php
}

if (count ( $gridVideo ) == 0) {
	?>
							<tr class="no-items">
						<td class="colspanchange" colspan="5"><?php esc_attr_e( 'No videos found.', 'video_gallery' ); ?></td>
					</tr> <?php
}
?>
					</tbody>
				<tfoot>
					<tr>
						<th width="3%" scope="col" style=""
							class="manage-column column-cb check-column"><input
							type="checkbox" name="" id="manage-column-video-1"></th>
						<th width="3%" scope="col" class="manage-column column-ordering">
							<span>
										<?php esc_attr_e( '', 'video_gallery' ); ?> </span><span
							class="sorting-indicator"></span>
						</th>
						<th width="4%" scope="col"
							class="manage-column column-id sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=video&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'ID', 'video_gallery' ); ?> </span><span
								class="sorting-indicator"></span></a></th>
						<th width="6%" scope="col" class="manage-column column-image"><span
							class="sorting-indicator"></span></th>
						<th width="30%" scope="col"
							class="manage-column column-name sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Title', 'video_gallery' ); ?> </span><span
								class="sorting-indicator"></span></a></th>
						<th width="14%" scope="col"
							class="manage-column column-author sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=author&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Author', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="14%" scope="col"
							class="manage-column column-playlistname sortable desc" style="">
							<a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=category&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Categories', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a>
						</th>
						<th width="8%" scope="col"
							class="manage-column column-feature sortable desc text_center"
							style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=fea&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
						<?php esc_attr_e( 'Featured', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="4%" scope="col"
							class="manage-column column-createddate sortable desc"
							style="width: 10%"><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=date&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Date', 'digi' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="7%" scope="col"
							class="text_center manage-column column-publish sortable desc"
							style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>"><span>
<?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span></a></th>
						<th width="7%" scope="col"
							class="manage-column column-ordering sortable desc" style=""><a
							href="<?php echo balanceTags( get_site_url() ) ?>/wp-admin/admin.php?page=video&orderby=ordering&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Order', 'video_gallery' ); ?></span><span
								class="sorting-indicator"></span>
						</a></th>


					</tr>
				</tfoot>
			</table>
			<div class="alignleft actions" style="margin-top: 10px;">
				<select name="videoactiondown" id="videoactiondown">
					<option value="-1" selected="selected"><?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?></option>
					<option value="videodelete"><?php esc_attr_e( 'Delete', 'video_gallery' ); ?></option>
					<option value="videopublish"><?php esc_attr_e('Publish','video_gallery'); ?></option>
					<option value="videounpublish"><?php esc_attr_e('Unpublish','video_gallery'); ?></option>
					<option value="videofeatured"><?php esc_attr_e('Add to  Feature','video_gallery'); ?></option>
					<option value="videounfeatured"><?php esc_attr_e('Remove from Featured','video_gallery'); ?></option>
				</select> <input type="submit" name="videoapply"
					class="button-secondary action"
					value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?>">
			</div>
<?php
if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>
			
	
	</div>
	</form>
</div>