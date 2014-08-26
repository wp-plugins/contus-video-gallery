<?php
/**
 * Video ad view file.
 * 
 *  @category   Apptha
 * @package    Contus video Gallery
 * @version    2.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
?>
<!--   MENU OPTIONS STARTS  --->
<?php $imaadpath = $videoAdFile = ''; //  avoid invalid variable  ?>
<div class="apptha_gallery">
	<h2 class="nav-tab-wrapper">
		<a href="?page=video" class="nav-tab"><?php esc_attr_e( 'All Videos', 'video_gallery' ); ?></a>
		<a href="?page=playlist" class="nav-tab"><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></a>
		<a href="?page=videoads" class="nav-tab nav-tab-active"><?php esc_attr_e( 'Video Ads', 'video_gallery' ); ?></a>
		<a href="?page=hdflvvideosharesettings" class="nav-tab"><?php esc_attr_e( 'Settings', 'video_gallery' ); ?></a>
		<a href="?page=googleadsense" class="nav-tab"><?php esc_attr_e( 'Google AdSense', 'video_gallery' ); ?></a>
		
	</h2>

	<!--  MENU OPTIONS ENDS --->

	<div class="wrap">
		<h2 class="option_title">
			<?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . '/images/vid_ad.png" alt="move" width="30"/>'; ?>
			<?php esc_attr_e( 'Manage Video Ads', 'video_gallery' ); ?>
			<a class="button-primary" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideoad" >Add New</a>
		</h2>

		<?php if ( $displayMsg ): ?>
			<div class="updated below-h2">
				<p>
					<?php echo balanceTags( $displayMsg ); ?>
				</p>
			</div>
		<?php
		endif;
		$orderField = filter_input( INPUT_GET, 'order' );
		$direction = isset( $orderField ) ? $orderField : false;
		$reverse_direction = ( $direction == 'DESC' ? 'ASC' : 'DESC' );
if ( isset( $searchBtn ) ) {
	?>
	<div  class="updated below-h2">
		<?php
		$url = get_site_url() . '/wp-admin/admin.php?page=videoads';
	if ( count( $gridVideoad ) ) {
		echo balanceTags( count( $gridVideoad ) ) . '    Search Result( s ) for "' . $searchMsg . '".&nbsp&nbsp&nbsp<a href="'.$url.'" >Back to Video Ads List</a>';
	} else {
		echo 'No Search Result( s ) for "' . $searchMsg . '".&nbsp&nbsp&nbsp<a href="'.$url.'" >Back to Video Ads List</a>';
	}
		?> </div> 
	<?php } ?>
		<form name="videoads" action="" method="post" class="admin_video_search alignright"  onsubmit="return prodttagsearch();" >
			<p class="search-box">
				<input type="text"  name="videoadssearchQuery" id="videoadssearchQuery" value="<?php echo balanceTags( $searchMsg ); ?>">
<?php echo '<script>document.getElementById( "videoadssearchQuery" ).value="' . $searchMsg . '"</script>'; ?>
				<input type="hidden"  name="page" value="videoads">
				<input type="submit" name="videoadsearchbtn"  class="button" value="Search Ads">
			</p>
		</form>
		<form  name="videoadsfrm" action="" method="post" onsubmit="return VideoaddeleteIds()" >
			<div class="alignleft actions">
				<select name="videoadactionup" id="videoadactionup">
					<option value="-1" selected="selected">
<?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?>
					</option>
					<option value="videoaddelete">
			<?php esc_attr_e( 'Delete', 'video_gallery' ); ?>
					</option>
				</select>
				<input type="submit" name="videoadapply"  class="button-secondary action" value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?>">
			</div>

			<?php
			$limit = 20;
			$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
			$total = $videoad_count;
			$num_of_pages = ceil( $total / $limit );
			$page_links   = paginate_links(
					array(
						'base' => add_query_arg( 'pagenum', '%#%' ),
						'format' => '',
						'prev_text' => __( '&laquo;', 'aag' ),
						'next_text' => __( '&raquo;', 'aag' ),
						'total' => $num_of_pages,
						'current' => $pagenum,
						)
					);

if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
			?>
			<div style="clear: both;"></div>
			<table class="wp-list-table widefat fixed tags" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" id="cb"  class="manage-column column-cb check-column" style="">
							<input type="checkbox" name="" id="manage-column-video-1" >
						</th>
						<th scope="col"  class="manage-column column-id sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Ad ID', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-name sortable desc" style="">
							<a href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col"  class="manage-column column-path sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=path&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Path', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

						<th scope="col" class="manage-column column-type sortable desc" style="">
							<a><span><?php esc_attr_e( 'Ad Type', 'video_gallery' ); ?></span></a>		
						</th>
						<th scope="col" class="manage-column column-admethod sortable desc" style="">
							<a><span><?php esc_attr_e( 'Ad Method', 'video_gallery' ); ?></span></a>
						</th>
						<th scope="col" class="manage-column column-publish sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
					</tr>
				</thead>                
				<tbody id="the-list" class="list:tag">
<?php
foreach ( $gridVideoad as $videoAdview ) {
	?>
						<tr>
							<th scope="row" class="check-column">
							    <label class="screen-reader-text" for="cb-select-<?php echo balanceTags( $videoAdview->ads_id ); ?>">Select <?php echo  ucfirst( balanceTags( $videoAdview->title )); ?></label>
								<input type="checkbox" name="videoad_id[]" value="<?php echo balanceTags( $videoAdview->ads_id ); ?>">
							</th>
							<td class="column-id">
								<a title="Edit <?php echo ucfirst( balanceTags( $videoAdview->title ) ); ?>" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideoad&videoadId=<?php echo balanceTags( $videoAdview->ads_id ); ?>" ><?php echo balanceTags( $videoAdview->ads_id ); ?></a><div class="row-actions">
							</td>
							<td class="column-title">
								<a title="Edit <?php echo ucfirst( balanceTags( $videoAdview->title ) ); ?>" class="row-title" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=newvideoad&videoadId=<?php echo balanceTags( $videoAdview->ads_id ); ?>" ><?php echo balanceTags( $videoAdview->title ); ?></a>
							</td>
							<td class="column-path">
							
						   <?php if( strlen( $videoAdview->file_path ) > 40 ) {
							   $videoAdFile =  balanceTags( substr($videoAdview->file_path,0,40 ) ) .'&hellip;';
							} else { 
							   $videoAdFile  =  balanceTags($videoAdview->file_path);  
							} 
							echo $videoAdFile;?>
							<?php if( $videoAdview->admethod == 'imaad') {
								if(strlen( $videoAdview->imaadpath)>40 ) {
									$imaadpath = substr($videoAdview->imaadpath , 0 , 40).'&hellip;';   
								} else {
									$imaadpath =  $videoAdview->imaadpath;
								}
							  echo $imaadpath;							
							} ?>
							
							</td>
							<?php
							$status  = 1;
							$image   = 'deactivate.jpg';
							$publish = 'Publish';
	if ( $videoAdview->publish == 1 ) {
		$status  = 0;
		$image   = 'activate.jpg';
		$publish = 'Unpublish';
	}
							?>
							<td class="column-type videoadmethod"><?php  if( $videoAdview->admethod =='prepost') { echo 'Pre/Post-roll Ad'; } else if( $videoAdview->admethod =='midroll' ) {  echo 'Mid-roll Ad';  } else if($videoAdview->admethod =='imaad' ) {  echo 'IMA Ad '; }  ?>
							</td>
							<td class="column-admethod">
	<?php if ( $videoAdview->admethod != 'midroll' ) echo balanceTags( $videoAdview->adtype ); ?>
							</td>
							<td class="column-publish" id="videoad-publish-icon">
								<a href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&videoadId=<?php echo balanceTags( $videoAdview->ads_id ); ?>&status=<?php echo balanceTags( $status ); ?>">   <img  src="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ) . 'images/' . $image ?>" title="<?php echo balanceTags( $publish ); ?>" title="<?php echo balanceTags( $publish ); ?>"  /></a>
							</td>
						</tr>
						<?php
					}
if ( count( $gridVideoad ) == 0 ) {
						?>
						<tr class="no-items"><td class="colspanchange" colspan="5">No video ad found.</td></tr>
	<?php
}
?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="col"  class="manage-column column-cb check-column" style="">
							<input type="checkbox" name="" id="manage-column-video-1" >
						</th>
						<th scope="col"  class="manage-column column-id sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Ad ID', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-name sortable desc" style="">
							<a href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=title&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col"  class="manage-column column-path sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=path&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'Path', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-adtype sortable desc" style="">
							<a><span><?php esc_attr_e( 'Ad Type', 'video_gallery' ); ?></span></a>
						</th>
						<th scope="col" class="manage-column column-admethod sortable desc" style="">
							<a><span><?php esc_attr_e( 'Ad Method', 'video_gallery' ); ?></span></a>
						</th>
						<th scope="col" class="manage-column column-publish sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
					</tr>
				</tfoot>
			</table>
			<div style="clear: both;"></div>
			<div class="alignleft actions" style="margin-top:10px;">
				<select name="videoadactiondown" id="videoadactiondown">
					<option value="-1" selected="selected">
<?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?>
					</option>
					<option value="videoaddelete">
			<?php esc_attr_e( 'Delete', 'video_gallery' ); ?>
					</option>
				</select>
				<input type="submit" name="videoadapply"  class="button-secondary action" value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?>">
			</div>
<?php
if ( $page_links ) {
	echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>
		</form>
	</div>
</div>