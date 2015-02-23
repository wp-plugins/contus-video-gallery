<?php
/**
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: playlist model file.
  Version: 2.8
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
?>
<?php
$page = $class = '';
if ( isset( $_GET['pagenum'] ) ) {
	$page = '&pagenum=' . $_GET['pagenum'];
}
$apptha_base_url = APPTHA_VGALLERY_BASEURL;
?>
<script type="text/javascript">
// When the document is ready set up our sortable with it's inherant function(s)
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
				dragdr.post( "<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php?action=playlistsortorder<?php echo balanceTags( $page ); ?>", order );
			}
		    
		} );
	} );
</script>
<div class="apptha_gallery">

	<!--   MENU OPTIONS STARTS  --->
	<h2 class="nav-tab-wrapper">
		<a href="?page=video" class="nav-tab"><?php esc_attr_e( 'All Videos', 'video_gallery' ); ?></a>
		<a href="?page=playlist" class="nav-tab nav-tab-active"><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></a>
		<a href="?page=videoads" class="nav-tab"><?php esc_attr_e( 'Video Ads', 'video_gallery' ); ?></a>
		<a href="?page=hdflvvideosharesettings" class="nav-tab"><?php esc_attr_e( 'Settings', 'video_gallery' ); ?></a>
		<a href="?page=googleadsense" class="nav-tab"><?php esc_attr_e( 'Google AdSense', 'video_gallery' ); ?></a>
		
	</h2>
	<!--  MENU OPTIONS ENDS --->
	<div class="wrap">
		<h2 class="option_title">
			<?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "/images/manage_list.png' alt='move' width='30'/>"; ?>
<?php esc_attr_e( 'Categories', 'video_gallery' ); ?>
		</h2>
		<div class="floatleft category_addpages">
			<?php
			$dir     = dirname( plugin_basename( __FILE__ ) );
			$dirExp  = explode( '/', $dir );
			$dirPage = $dirExp[0];
			?>
			<script type="text/javascript">
				var folder = '<?php echo balanceTags( $dirPage ); ?>';
			</script>
			<div class="apptha_gallery">
				<?php if ( $playListId  ) { ?>
					<h3><?php esc_attr_e( 'Update Category', 'video_gallery' ); ?></h3>
				<?php } else { ?> <h3><?php esc_attr_e( 'Add a New Category', 'video_gallery' ); ?></h3> <?php } ?>
<?php if ( $displayMsg && $displayMsg[1] == 'addcategory' ): ?>
					<div class="updated below-h2">
						<p>
							<?php echo balanceTags( $displayMsg[0] );
							?>
						</p>
					</div>
<?php endif; ?>
				<div id="post-body" class="has-sidebar">
					<div id="post-body-content" class="has-sidebar-content">
						<div class="stuffbox">
							<div class="inside" style="margin:15px;">
								<form name="adsform" method="post" enctype="multipart/form-data" >
									<table class="form-table">
										<tr>
											<th scope="row"><?php esc_attr_e( 'Title / Name', 'video_gallery' ) ?></th>
											<td>
<?php if ( isset( $playlistEdit->playlist_name ) ) {
	$playlist_name = $playlistEdit->playlist_name;
} else {
	$playlist_name = '';
} ?>
												<input type="text" maxlength="200" id="playlistname" name="playlistname" value="<?php echo htmlentities( $playlist_name ); ?>"  />
												<span id="playlistnameerrormessage" style="display: block;color:red; "></span>
											</td>
										</tr>

										<tr>
											<th scope="row"><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></th>
											<td>
												<input type="radio" name="ispublish" id="published"  checked="checked" <?php if ( isset( $playlistEdit->is_publish ) && $playlistEdit->is_publish == 1 ) {echo 'checked="checked"';} ?> value="1" /> <label><?php esc_attr_e( 'Yes', 'video_gallery' ); ?></label>
												<input type="radio" name="ispublish" id="published" <?php if ( isset( $playlistEdit->is_publish ) && $playlistEdit->is_publish == 0 ) { echo 'checked="checked"';} ?> value="0" /><label> <?php esc_attr_e( 'No', 'video_gallery' ); ?></label>
											</td>
										</tr>
									</table>
<?php if ( $playListId ) { ?>
										<input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php esc_attr_e( 'Update', 'video_gallery' ); ?>" class="button" /> 
										<input type="button" onclick="window.location.href = 'admin.php?page=playlist'" class="button-secondary" name="cancel" value="<?php esc_attr_e( 'Cancel' ); ?>" class="button" />
	<?php } else { ?>
										<input type="submit" name="playlistadd" onclick="return validateplyalistInput();" class="button-primary"  value="<?php esc_attr_e( 'Save', 'video_gallery' ); ?>" class="button" /> <?php } ?>
								</form>
							</div>
						</div>
					</div>
					<p>
				</div>
			</div>
			<script type="text/javascript">
				document.getElementById( "publish_yes" ).checked = true;
			</script>
		</div>
		<div class="floatleft category_addpages">
			<?php if ( $displayMsg && $displayMsg[1] == 'category' ): ?>
				<div class="updated below-h2">
					<p>
				<?php echo balanceTags( $displayMsg[0] ); ?>
					</p>
				</div>
				<?php
			endif;
			$orderField = filter_input( INPUT_GET, 'order' );
			$direction  = isset( $orderField ) ? $orderField : false;
			$reverse_direction = ( $direction == 'DESC' ? 'ASC' : 'DESC' );
if ( isset( $_REQUEST['playlistsearchbtn'] ) ) {
	?>
	<div  class="updated below-h2">
		<?php
		$url = get_site_url() . '/wp-admin/admin.php?page=playlist';
		$searchmsg = filter_input( INPUT_POST, 'PlaylistssearchQuery' );
	if ( count( $gridPlaylist ) ) {
		echo esc_attr_e( 'Search Results for', 'video_gallery' ) . ' "' . $searchMsg . '"';
	} else {
		echo esc_attr_e( 'No Search Results for', 'video_gallery' ) . ' "' . $searchMsg . '"';
	}
		?> </div> <?php } ?>
			<form name="Playlists" class="admin_video_search alignright" id="searchbox-playlist" action="" method="post" onsubmit="return Playlistsearch();">
				<p class="search-box">
					<input type="text"  name="PlaylistssearchQuery" id="PlaylistssearchQuery" value="<?php if ( isset( $searchmsg ) )
					echo balanceTags( $searchmsg );
				?>">
					<input type="hidden"  name="page" value="Playlists">
					<input type="submit" name="playlistsearchbtn" id="playlistsearchButton"  class="button" value="<?php esc_attr_e( 'Search Categories', 'video_gallery' ); ?>"></p>
			</form>
			<form  name="Playlistsfrm" action="" method="post" onsubmit="return PlaylistdeleteIds()">
				<div class="alignleft actions bulk-actions">
					<select name="playlistactionup" id="playlistactionup">
						<option value="-1" selected="selected"><?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?></option>
						<option value="playlistdelete"><?php esc_attr_e( 'Delete', 'video_gallery' ); ?></option>
					</select>
					<input type="submit" name="playlistapply"  class="button-secondary action" value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?>">
				</div>
				<?php
				$limit   = 20;
				$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
				$total   = $playlist_count;
				$num_of_pages = ceil( $total / $limit );
				$page_links   = paginate_links(
						array(
							'base'		=> add_query_arg( 'pagenum', '%#%' ),
							'format'	=> '',
							'prev_text' => __( '&laquo;', 'aag' ),
							'next_text' => __( '&raquo;', 'aag' ),
							'total'		=> $num_of_pages,
							'current'	=> $pagenum,
							)
						);

if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
				?>
				<div style="float:right ; font-weight: bold;" ><?php if ( isset( $pagelist ) ) echo balanceTags( $pagelist ); ?></div>
				<div style="clear: both;"></div>
				<table class="wp-list-table widefat fixed tags" cellspacing="0">
					<thead>
						<tr>
							<th scope="col"  class="manage-column column-cb check-column" style="">
								<input type="checkbox" name="" id="manage-column-category-1" >
							</th>
							<th scope="col"  style="">
								<span><?php esc_attr_e( '', 'video_gallery' ); ?> </span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope="col"  class="manage-column column-id sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
									<span><?php esc_attr_e( 'ID', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col"  class="manage-column column-name sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>">
									<span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col" class="manage-column column-Expiry sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col" class="manage-column column-sortorder sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=sorder&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Order', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</thead>
					<tbody id="test-list" class="list:post"> <input type=hidden id=playlistid2 name=playlistid2 value="1" />
					<div name=txtHint></div>
<?php
foreach ( $gridPlaylist as $playlistView ) {
	$class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';
	?>
						<tr id="listItem_<?php echo balanceTags( $playlistView->pid ); ?>" <?php echo balanceTags( $class ); ?> >
							<th scope="row" class="check-column">
								<input type="checkbox" name="pid[]" value="<?php echo balanceTags( $playlistView->pid ); ?>">
							</th>
							<td>
								<span class="hasTip content" title="<?php esc_attr_e( 'Click and Drag', 'video_gallery' ); ?>" style="padding: 6px;">
									<img src="<?php echo balanceTags( $apptha_base_url ) . 'images/arrow.png'; ?>" alt="move"
										 width="16" height="16" class="handle" />
								</span>
							</td>
							<td class="id-column column-id">
								<a title="Edit <?php echo balanceTags( $playlistView->playlist_name ); ?>" href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo balanceTags( $playlistView->pid ); ?>" ><?php echo balanceTags( $playlistView->pid ); ?></a><div class="row-actions">
							</td>
							<td class="title-column">
								<a title="Edit <?php echo balanceTags( $playlistView->playlist_name ); ?>" class="row-title" href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=newplaylist&playlistId=<?php echo balanceTags( $playlistView->pid ); ?>" ><?php echo balanceTags( $playlistView->playlist_name ); ?></a>
							</td>
							<td class="pub-column Expiry column-Expiry"  align="center">
								<?php
								$status  = 1;
								$image   = 'deactivate.jpg';
								$publish = __( 'Publish', 'video_gallery' );
	if ( $playlistView->is_publish == 1 ) {
		$status  = 0;
		$image   = 'activate.jpg';
		$publish = __( 'Unpublish', 'video_gallery' );
	}
								?>
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist<?php if ( isset( $_GET['pagenum'] ) ) echo '&pagenum=' . $_GET['pagenum']; ?>&playlistId=<?php echo balanceTags( $playlistView->pid ); ?>&status=<?php echo balanceTags( $status ); ?>">   <img src="<?php echo balanceTags( $apptha_base_url ) . 'images/' . $image ?>" title="<?php echo balanceTags( $publish ); ?>"   /> </a>
							</td>
							<td class="order-column Expiry column-ordering column-Expiry">
						<?php echo balanceTags( $playlistView->playlist_order ); ?>
							</td>
						</tr>
						<?php
					}

if ( isset( $_REQUEST['searchplaylistsbtn'] ) ) {
	?> <tr class="no-items"><td class="colspanchange" colspan="5">No Category found.</td></tr> <?php }
if ( count( $gridPlaylist ) == 0 ) {
	?>
	<tr class="no-items"><td class="colspanchange" colspan="5">No Category found.</td></tr> <?php
}
					?>
					</tbody>
					<tfoot>
						<tr>
							<th scope="col"  class="manage-column column-cb check-column" style="">
								<input type="checkbox" name="" id="manage-column-category-1" >
							</th>
							<th width="3%" scope="col"  style="">
								<span><?php esc_attr_e( '', 'video_gallery' ); ?> </span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope="col"  class="manage-column column-id sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
									<span><?php esc_attr_e( 'ID', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col"  class="manage-column column-name sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>">
									<span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col" class="manage-column column-Expiry sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th scope="col" class="manage-column column-sortorder sortable desc" style="">
								<a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=playlist&orderby=sorder&order=<?php echo balanceTags( $reverse_direction ); ?>"><span><?php esc_attr_e( 'Order', 'video_gallery' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</tfoot>
				</table>
				<div style="clear: both;"></div>
				<div class="alignleft actions" style="margin-top:10px;">
					<select name="playlistactiondown" id="playlistactiondown">
						<option value="-1" selected="selected">
				<?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?>
						</option>    
						<option value="playlistdelete">
				<?php esc_attr_e( 'Delete', 'video_gallery' ); ?>
						</option>
					</select>
					<input type="submit" name="playlistapply"  class="button-secondary action" value="Apply">
				</div>
<?php
if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>
			</form>
		</div>
	</div>
</div>