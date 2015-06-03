<?php
/**  
 * Video googleadsense view file.
 *
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */

$page = '';
$url = get_site_url() . '/wp-admin/admin.php?page=googleadsense';
if ( isset( $_GET['pagenum'] ) ) {
	$page = '&pagenum=' . $_GET['pagenum'];
}
$selfurl = get_site_url() . '/wp-admin/admin.php?page=googleadsense' . $page;
?>
<!--   MENU OPTIONS STARTS  --->
<div class="apptha_gallery">
	<h2 class="nav-tab-wrapper">
		<a href="?page=video" class="nav-tab"><?php esc_attr_e( 'All Videos', 'video_gallery' ); ?></a>
		<a href="?page=playlist" class="nav-tab"><?php esc_attr_e( 'Categories', 'video_gallery' ); ?></a>
		<a href="?page=videoads" class="nav-tab"><?php esc_attr_e( 'Video Ads', 'video_gallery' ); ?></a>
		<a href="?page=hdflvvideosharesettings" class="nav-tab"><?php esc_attr_e( 'Settings', 'video_gallery' ); ?></a>
		<a href="?page=googleadsense" class="nav-tab nav-tab-active"><?php esc_attr_e('Google AdSense','video_gallery');?></a>
	</h2>

	<!--  MENU OPTIONS ENDS --->

	<div class="wrap">
		<h2 class="option_title">
			<?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . '/images/google_adsense.png" alt="move" width="30"/>'; ?>
			<?php esc_attr_e( 'Google AdSense', 'video_gallery' ); ?>
			<a class="button-primary" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=addgoogleadsense" ><?php esc_attr_e('Add New','video_gallery');?></a>
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
		$url = get_site_url() . '/wp-admin/admin.php?page=videogoogleadsense';
	if (count( $gridVideoad ) ) {
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
				<input type="submit" name="videoadsearchbtn"  class="button" value="Search Google Ads">
			</p>
		</form>
		<form  name="videogoogleadsfrm" action="" method="post" onsubmit="return VideogoogleaddeleteIds();" >
			<div class="alignleft actions">
				<select name="videogoogleadactionup" id="videogoogleadactionup">
					<option value="-1" selected="selected">
<?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?>
					</option>
					<option value="videogoogleaddelete">
			<?php esc_attr_e( 'Delete', 'video_gallery' ); ?>
					</option>
				</select>
				<input type="submit" name="videoadapply"  class="button-secondary action" value="<?php esc_attr_e( 'Apply', 'video_gallery' ); ?>">
			</div>

			<?php
			$limit = 20;
			$pagenum = absint( filter_input(INPUT_GET,'pagnum') );
			if( empty ($pagenum) ) {
				$pagenum = 1;
			}
			$total = $videoad_count;
			$num_of_pages = ceil( $total/$limit );
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
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col"  class="manage-column column-cb check-column" style="">
							<input type="checkbox" name="" id="manage-column-video-1" >
						</th>
						<th scope="col"  class="manage-column column-id sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=googleadsense&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'ID', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-name sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=googleadsense&orderby=googleadsense_title&order=<?php echo balanceTags( $reverse_direction ); ?>" > <span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
							 <span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-publish sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=googleadsense&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
					</tr>
				</thead> 
				<tfoot>
					<tr>
						<th scope="col"  class="manage-column column-cb check-column" style="">
							<input type="checkbox" name="" id="manage-column-video-1" >
						</th>
						<th scope="col"  class="manage-column column-id sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=videoads&orderby=id&order=<?php echo balanceTags( $reverse_direction ); ?>">
								<span><?php esc_attr_e( 'ID', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-name sortable desc">
							<a > <span><?php esc_attr_e( 'Title', 'video_gallery' ); ?></span>
							</a>
						</th>
						<th scope="col" class="manage-column column-publish sortable desc" style="">
							<a  href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=googleadsense&orderby=publish&order=<?php echo balanceTags( $reverse_direction ); ?>" ><span><?php esc_attr_e( 'Publish', 'video_gallery' ); ?></span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
					</tr>
				</tfoot>
				               
				<tbody id="the-list" class="list:tag">
      <?php
               foreach ( $gridVideoad as $videoAdview) {
                $googleadsense_details = unserialize($videoAdview->googleadsense_details);
				$adsense_code = $googleadsense_details['googleadsense_title'];
				$adsense_option = $googleadsense_details['adsense_option'];
				$adsense_reopen = $googleadsense_details['adsense_reopen'];
				$adsense_reopen_time = $googleadsense_details['adsense_reopen_time'];
				$adsenseshow_time = $googleadsense_details['adsenseshow_time'];
				$adsense_status = $googleadsense_details['publish'];
				$image  = 'deactivate.jpg';
				if ( $adsense_status == 1 ) {
					$adsense_status = 0;
					$image  = 'activate.jpg';
				}else{
					$image  = 'deactivate.jpg';
					$adsense_status = 1;
				}
	?>
						<tr>
							<th scope="row" class="check-column">
								<input type="checkbox" name="videogooglead_id[]" value="<?php echo balanceTags( $videoAdview->id ); ?>">
							</th>
							<td class="column-id">
								<a title="Edit <?php echo balanceTags( $videoAdview->id ); ?>" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=addgoogleadsense&videogoogleadId=<?php echo balanceTags( $videoAdview->id ); ?>" ><?php echo $videoAdview->id; ?></a><div class="row-actions">
							</td>
							<td class="column-name">
							     <?php if( strlen( $adsense_code ) > 50 ) {  
							     	 $adsense_code  =  substr( $adsense_code , 0 , 50) . '&hellip;'; } else { $adsense_code = $adsense_code ; } ?>
								<a title="Edit <?php echo balanceTags( $videoAdview->id ); ?>" href="<?php echo balanceTags( get_site_url() ); ?>/wp-admin/admin.php?page=addgoogleadsense&videogoogleadId=<?php echo balanceTags( $videoAdview->id ); ?>" ><?php echo balanceTags( $adsense_code); ?></a><div class="row-actions">
							</td>
							<td class="column-publish" id="videoad-publish-icon">
		                     <a title="<?php if ( $adsense_status == 0 ) { esc_attr_e( 'Unpublish' ); } else { esc_attr_e( 'Publish' ); } ?>" href="<?php echo balanceTags( $selfurl ); ?>&videogoogleadId=<?php echo balanceTags( $videoAdview->id ); ?>&status=<?php echo balanceTags( $adsense_status ); ?>">   <img src="<?php echo balanceTags( APPTHA_VGALLERY_BASEURL ) . 'images/' . $image ?>" /> </a>
							</td>
						</tr>
						<?php
					}
if ( count( $gridVideoad ) == 0 ) {
						?>
						<tr class="no-items"><td class="column-id"></td><td class="colspanchange" colspan="3"><?php esc_attr_e('No Google AdSense Found.' , 'video_gallery' );?></td></tr>
	<?php
}
?>
				</tbody>
			</table>
			<div style="clear: both;"></div>
			<div class="alignleft actions" style="margin-top:10px;">
				<select name="videogoogleadactiondown" id="videogoogleadactiondown">
					<option value="-1" selected="selected">
<?php esc_attr_e( 'Bulk Actions', 'video_gallery' ); ?>
					</option>
					<option value="videogoogleaddelete">
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