<?php
/**
 * Add googleadsense  view  file
 * 
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8.1
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
 */
?>
<script type="text/javascript">
	folder = '<?php echo balanceTags( $dirPage ); ?>'
</script>
<script type="text/javascript">
function validateGoogleAdSense(){
 var googleadsenseCode  = document.getElementById("googleadsense_code").value;
 var googleadsensetitle = document.getElementById("googleadsense_title").value;
 googleadsensetitle =  googleadsensetitle.trim();
 googleadsenseCode  =  googleadsenseCode.trim();
 document.getElementById("googleadsense_codeerror").innerHTML ='';
 document.getElementById("googleadsense_titleerror").innerHTML ='';
 var error = 0;
 if( googleadsenseCode =='' ){
	 document.getElementById("googleadsense_codeerror").innerHTML='<label>Please Enter the Google AdSense</label>';
	 error++;
  }
 if(googleadsensetitle == '' ){ 
	 document.getElementById("googleadsense_titleerror").innerHTML='<label>Enter the Google AdSense title</label>';
	 error++;
	 }
 if(error){
	 return false;
 }else{
	 return true;
 }
}
</script>
<div class="apptha_gallery">
     <?php if( isset ( $editGoogleAdsense->id ) ) { ?>
	<h2 class="option_title"><?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . 'images/google_adsense.png" alt="move" width="30"/>'; ?><?php esc_attr_e( 'Update Google AdSense', 'video_gallery' ); ?></h2> <?php } else {
		?> <h2  class="option_title"><?php echo '<img src="' . APPTHA_VGALLERY_BASEURL . 'images/google_adsense.png" alt="move" width="30"/>'; ?><?php esc_attr_e( 'Add new Google AdSense', 'video_gallery' ); ?></h2> <?php } ?>
	<?php if ( isset( $msg ) ): ?>
		<div class="updated below-h2">
		<p>
				<?php
		echo balanceTags ( $msg );
		?>
			</p>
	</div>
	<?php endif; ?>
	<?php if(isset($editGoogleAdsense)&& $editGoogleAdsense !==''){
		  $adsense_code = $adsense_option = $adsense_reopen = $adsense_reopen_time = $adsenseshow_time = $adsense_status = $adsense_title ='';
		  $googleadsense_details = unserialize($editGoogleAdsense->googleadsense_details);
		  if(isset($googleadsense_details['googleadsense_title'])){
		    $adsense_title = $googleadsense_details['googleadsense_title'];
		  }
		  $adsense_code = $googleadsense_details['googleadsense_code'];
		  $adsense_option = $googleadsense_details['adsense_option'];
		  $adsense_reopen = $googleadsense_details['adsense_reopen'];
		  $adsense_reopen_time = $googleadsense_details['adsense_reopen_time'];
		  $adsenseshow_time = $googleadsense_details['adsenseshow_time'];
		  $adsense_status = $googleadsense_details['publish'];
	} else { 
		 $adsense_code = $adsense_option = $adsense_reopen = $adsense_reopen_time = $adsenseshow_time = $adsense_status = $adsense_title ='';
	} ?>
	<div id="post-body" class="has-sidebar">
		<div id="post-body-content" class="has-sidebar-content">
			<form method="post" action="admin.php?page=googleadsense">
				<table>
					<tbody>
					    <tr>
					        <td width="150"><?php esc_attr_e( 'Title', 'video_gallery' ) ?></td>
							<td colspan="2"><input type="text" name="googleadsense_title" id="googleadsense_title" value="<?php echo $adsense_title ; ?>" />
		                   <div id="googleadsense_titleerror" style="color:#ff0000;"></div>
		                   </td>
					    </tr>
						<tr>
							<td width="150"><?php esc_attr_e( 'Google AdSense Code', 'video_gallery' ) ?></td>
							<td colspan="2"><textarea name="googleadsense_code" id="googleadsense_code" col="60" row="20"><?php echo $adsense_code ; ?></textarea>
		                    <div id="googleadsense_codeerror" style="color:#ff0000;"></div>
		                    </td>
						</tr>
						<tr>
						  <td width="150"><?php esc_attr_e('Option','video_gallery'); ?>				     
						  </td>
						  <td>
						     <input value="always_show" <?php if($adsense_option =='always_show') { echo "checked";} ?> type="radio" name="alway_open" checked="checked" />&nbsp;&nbsp;<?php esc_attr_e('Always show','video_gallery');?>
						     <input value="close" <?php if($adsense_option =='close') { echo "checked";} ?> type="radio" name="alway_open" />&nbsp;&nbsp;<?php esc_attr_e('Close After:','video_gallery');?>
						     </td>
						     <td>&nbsp;&nbsp;<input type="text" value="<?php echo $adsenseshow_time ;?>" name="adsense_show_second" size="15" /> <?php esc_attr_e('Sec','video_gallery');?>
						  </td>
						</tr>
						<tr>
						    <td width="150"><?php esc_attr_e('Reopen','video_gallery'); ?></td>
						 	<td>
						 		<input type="checkbox" value="1" <?php if($adsense_reopen =='1') { echo "checked";} ?> name="reopen"  />&nbsp;&nbsp;<?php esc_attr_e('Reopen After:','video_gallery');?>
						 	 </td>
						 	 <td>&nbsp;&nbsp;<input type="text" value="<?php echo $adsense_reopen_time ; ?>" name="adsense_reopen_second" size="15" /> <?php esc_attr_e('Sec','video_gallery');?>						 	 
						 	</td>
						</tr>
						<tr>
							<td width="150"><?php esc_attr_e( 'Publish', 'video_gallery' ) ?></td>
							<td colspan="2"><input type="radio" name="status" checked="checked" value="1" <?php if($adsense_status =='1') { echo "checked";} ?> /><?php esc_attr_e('Yes','video_gallery');?>
							   <input type="radio" name="status" value="0"  <?php if($adsense_status =='0') { echo "checked";} ?> /><?php esc_attr_e('No','video_gallery');?>
						    </td>
						</tr>
						<tr>
						<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">
							    <?php if(isset($editGoogleAdsense->id)) {  ?>
							    <input type="hidden" value="<?php echo $editGoogleAdsense->id; ?>" name="videogoogleadId">
							   	<input type="submit" value="<?php esc_attr_e('Update' , 'video_gallery'); ?>" name="updatebutton" onclick="return validateGoogleAdSense();" class="button-primary"> 
								<?php } else { ?>
								<input type="submit" value="<?php esc_attr_e('Save' , 'video_gallery'); ?>" name="updatebutton" onclick="return validateGoogleAdSense();" class="button-primary"> 
								<?php } ?>							
							&nbsp;&nbsp;
							<a href="<?php echo admin_url('admin.php?page=googleadsense');?>" class="button">Cancel</a></td>
						</tr>
					</tbody>
				</table>
			</form>

		</div>
	</div>