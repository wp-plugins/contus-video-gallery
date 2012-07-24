<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  : 1.3
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	  : GNU General Public License version 2 or later; see LICENSE.txt
 * @Creation Date : Fev 21 2011
 * @Modified Date : December 07 2011
 * */
$contus = dirname(plugin_basename(__FILE__));
$site_url = get_option('siteurl');
?>
<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/js/selectuser.js"></script>
<link rel='stylesheet' href='../wp-content/plugins/<?php echo $contus ?>/css/styles123.css' type='text/css' media='all' />
<script type="text/javascript">
    // When the document is ready set up our sortable with it's inherant function(s)
      $(document).ready(function() {
        $("#test-list").sortable({
            handle : '.handle',
            update : function () {
                var order = $('#test-list').sortable('serialize');
                   var url = "../wp-content/plugins/<?php echo $contus ?>/process-sortable.php";
                //alert(order);
                var playid = document.getElementById('playlistid2').value;
                //$("#info").load("../wp-content/plugins/<?php echo $contus ?>/process-sortable.php?"+order+"&playid="+playid);
//                 $.post("../wp-content/plugins/<?php echo $contus ?>/process-sortable.php",{getSortOrder:"getSortOrder",order:order,playid:playid},function(result){
//                        jQuery("#viewCount"+ival).html(result);
//                  });
                showUser(url,playid,order);
                //alert(myarray1);
                //document.filterType.submit();

            }
        });
    });
    function savePlaylist(playlistName , mediaId){
        var name = playlistName.value;
        $.ajax({
            type: "GET",
            url: "<?php echo $site_url; ?>/wp-content/plugins/<?php echo $contus; ?>/functions.php",
            data: "name="+name+"&media="+mediaId,
            success: function(msg){
                var response = msg.split('##');
                document.getElementById('playlistchecklist').innerHTML = msg;
            }
        });
    }


    function validateInput(){
        if(document.getElementById('btn2').checked == true && document.getElementById('filepath1').value == ''){
            document.getElementById('message').innerHTML = 'Enter Youtube URL';
            return false;
        }
        if(document.getElementById('btn1').checked == true && document.getElementById('f1-upload-form').style.display != 'none'){
            document.getElementById('message').innerHTML = 'Upload Video';
            return false;
        }
        if(document.getElementById('name').value == ''){
            document.getElementById('message').innerHTML = 'Upload Video';
            return false;
        }
        if(document.getElementById('btn3').checked == true && document.getElementById('filepath2').value == ''){
            document.getElementById('message').innerHTML = 'Enter Video URl';
            return false;
        }
    }

</script>
<script>

    var uploadqueue = [];
    var uploadmessage = '';

    function addQueue(whichForm,myfile)
    {
        var  extn = extension(myfile);
        if( whichForm == 'normalvideoform' || whichForm == 'hdvideoform' )
        {
            if(extn != 'flv' && extn != 'FLV' && extn != 'mp4' && extn != 'MP4' && extn != 'm4v' && extn != 'M4V' && extn != 'mp4v' && extn != 'Mp4v' && extn != 'm4a' && extn != 'M4A' && extn != 'mov' && extn != 'MOV' && extn != 'f4v' && extn != 'F4V')
            {
                alert(extn+" is not a valid Video Extension");
                return false;
            }
        }
        else
        {
            if(extn != 'jpg' && extn != 'png' )
            {
                alert(extn+" is not a valid Image Extension");
                return false;
            }
        }
        uploadqueue.push(whichForm);
        if (uploadqueue.length == 1)
        {

            processQueue();
        }
        else
        {

            holdQueue();
        }


    }
    function processQueue()
    {
        if (uploadqueue.length > 0)
        {
            form_handler = uploadqueue[0];
            setStatus(form_handler,'Uploading');
            submitUploadForm(form_handler);
        }
    }
    function holdQueue()
    {
        form_handler = uploadqueue[uploadqueue.length-1];
        setStatus(form_handler,'Queued');
    }
    function updateQueue(statuscode,statusmessage,outfile)
    {
        uploadmessage = statusmessage;
        form_handler = uploadqueue[0];
        if (statuscode == 0)
            document.getElementById(form_handler+"-value").value = outfile;
        setStatus(form_handler,statuscode);
        uploadqueue.shift();
        processQueue();

    }

    function submitUploadForm(form_handle)
    {
        document.forms[form_handle].target = "uploadvideo_target";
        document.forms[form_handle].action = "../wp-content/plugins/<?php echo $contus; ?>/upload1.php?processing=1";
        document.forms[form_handle].submit();
    }
    function setStatus(form_handle,status)
    {
        switch(form_handle)
        {
            case "normalvideoform":
                divprefix = 'f1';
                break;
            case "hdvideoform":
                divprefix = 'f2';
                break;
            case "thumbimageform":
                divprefix = 'f3';
                break;
            case "previewimageform":
                divprefix = 'f4';
                break;
        }
        switch(status)
        {
            case "Queued":
                document.getElementById(divprefix + "-upload-form").style.display = "none";
                document.getElementById(divprefix + "-upload-progress").style.display = "";
                document.getElementById(divprefix + "-upload-status").innerHTML = "Queued";
                document.getElementById(divprefix + "-upload-message").style.display = "none";
                document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
                document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/<?php echo $contus; ?>/images/empty.gif';
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
                break;

            case "Uploading":
                document.getElementById(divprefix + "-upload-form").style.display = "none";
                document.getElementById(divprefix + "-upload-progress").style.display = "";
                document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading";
                document.getElementById(divprefix + "-upload-message").style.display = "none";
                document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
                document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/<?php echo $contus; ?>/images/loader.gif';
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
                break;
            case "Retry":
            case "Cancelled":
                //uploadqueue = [];
                document.getElementById(divprefix + "-upload-form").style.display = "";
                document.getElementById(divprefix + "-upload-progress").style.display = "none";
                document.forms[form_handle].myfile.value = '';
                enableUpload(form_handle);
                break;
            case 0:
                document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/<?php echo $contus; ?>/images/success.gif';
                document.getElementById(divprefix + "-upload-status").innerHTML = "";
                document.getElementById(divprefix + "-upload-message").style.display = "";
                document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
                document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage;
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
                break;


            default:
                document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/<?php echo $contus; ?>/images/error.gif';
                document.getElementById(divprefix + "-upload-status").innerHTML = " ";
                document.getElementById(divprefix + "-upload-message").style.display = "";
                document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage + " <a href=javascript:setStatus('" + form_handle + "','Retry')>Retry</a>";
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
                break;
            }



        }

        function enableUpload(whichForm,myfile)
        {
            if (document.forms[whichForm].myfile.value != '')
                document.forms[whichForm].uploadBtn.disabled = "";
            else
                document.forms[whichForm].uploadBtn.disabled = "disabled";
        }

        function cancelUpload(whichForm)
        {
            document.getElementById('uploadvideo_target').src = '';
            setStatus(whichForm,'Cancelled');
            pos = uploadqueue.lastIndexOf(whichForm);
            if (pos == 0)
            {
                if (uploadqueue.length >= 1)
                {
                    uploadqueue.shift();
                    processQueue();
                }
            }
            else
            {
                uploadqueue.splice(pos,1);
            }

        }
        function chkbut()
        {
            if(uploadqueue.length <= 0 )
            {
                if(document.getElementById('btn2').checked)
                {
                    document.getElementById('youtube-value').value= document.getElementById('filepath1').value;
                    document.getElementById('customurl1').value = document.getElementById('filepath2').value;
                    document.getElementById('customhd1').value = document.getElementById('filepath3').value;
                    return true;
                }
                if(document.getElementById('btn3').checked)
                {
                    document.getElementById('customurl1').value = document.getElementById('filepath2').value;
                    document.getElementById('customhd1').value = document.getElementById('filepath3').value;
                    document.getElementById('customimage').value = document.getElementById('filepath4').value;
                    document.getElementById('custompreimage').value = document.getElementById('filepath5').value;
                    return true;
                }
            }else { alert("Wait for Uploading to Finish"); return false; }

        }
        function extension(fname)
        {
            var pos = fname.lastIndexOf(".");

            var strlen = fname.length;

            if(pos != -1 && strlen != pos+1)
            {
                var ext = fname.split(".");
                var len = ext.length;
                var extension = ext[len-1].toLowerCase();
            }
            else
            {

                extension = "No extension found";

            }

            return extension;

        }
</script>



<?php
/*
  +----------------------------------------------------------------+
  +	hdflv-admin
  +
  +   required for hdflv
  +----------------------------------------------------------------+
 */

class HDFLVShareManage {

    var $mode = 'main';
    var $wptfile_abspath;
    var $wp_urlpath;
    var $act_vid = false;
    var $act_pid = false;
    var $base_page = '?page=hdflvvideoshare';
    var $PerPage = 10;

    function HDFLVShareManage() {
        global $hdflvvideoshare;

        // get the options
        $this->options = get_option('HDFLVSettings');

        // Manage upload dir
        add_filter('upload_dir', array(&$this, 'upload_dir'));

        $wp_upload = wp_upload_dir();

        $this->wptfile_abspath = $wp_upload['path'];
        $this->wp_urlpath = $wp_upload['url'];

        // output Manage screen
        $this->controller();
    }

    /**
     * Renders an admin section of display code
     * @author     John Godley (http://urbangiraffe.com)
     *
     * @param string $ug_name Name of the admin file (without extension)
     * @param string $array Array of variable name=>value that is available to the display code (optional)
     * @return void
     * */
    function render_admin($ug_name, $ug_vars = array()) {
        //echo $ug_name."".$ug_vars;
        // exit();
        $function_name = array($this, 'show_' . $ug_name);

        if (is_callable($function_name))
            call_user_func_array($function_name, $ug_vars);
        else
            echo "<p>Rendering of admin function show_$ug_name failed</p>";
    }

    // Return custom upload dir/url
    function upload_dir($uploads) {

        if ($this->options[0][27]['v'] == 0) {
            $dir = ABSPATH . trim($this->options[0][28]['v']);
            $url = trailingslashit(get_option('siteurl')) . trim($this->options[0][28]['v']);


            // Make sure we have an uploads dir
            if (!wp_mkdir_p($dir)) {
                $message = sprintf(__('Unable to create directory %s. Is its parent directory writable by the server?', 'hdflvvideoshare'), $dir);
                $uploads['error'] = $message;
                return $uploads;
            }
            $uploads = array('path' => $dir, 'url' => $url, 'error' => false);
        }
        return $uploads;
    }

    function render_message($message, $timeout = 0) {
?>
        <div class="wrap"><h2>&nbsp;</h2>
            <div class="fade updated" id="message" onclick="this.parentNode.removeChild (this)">
                <p><strong><?php echo $message ?></strong></p>
            </div>
        </div>
<?php
    }

    function controller() {
        global $wpdb;
        $this->mode = trim($_GET['mode']);

        $this->act_vid = (int) $_GET['id'];
        $this->act_pid = (int) $_GET['pid'];

     
//TODO:Include nonce !!!

     if(isset($_REQUEST['doactionVideos']))
		{
			//
			if (isset($_REQUEST['actionVideos']) == 'delete')
         {
         	for ($i = 0; $i < count($_POST['videoList']); $i++)
            {
            	$videoListId = is_numeric($_POST['videoList'][$i]);
            	
            	if($videoListId)
            	{
            		$videoListVal = $_POST['videoList'][$i];
            		$wpdb->query(" DELETE FROM " . $wpdb->prefix . "hdflvvideoshare WHERE vid = $videoListVal");
            	}
         	//print_r($_REQUEST);
            }
            $msg = 'Video(s) Deleted Successfully';
         }
		}
    if(isset($_REQUEST['doactionPlaylist']))
		{
			//
			if (isset($_REQUEST['actionPlaylist']) == 'delete')
         {
         	for ($i = 0; $i < count($_POST['checkList']); $i++)
            {
            	$playListId = is_numeric($_POST['checkList'][$i]);
            	
            	if($playListId)
            	{
            		$playListVal = $_POST['checkList'][$i];
            		$wpdb->query(" DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = $playListVal");
            	}
         	//print_r($_REQUEST);
            }
            $this->mode = 'playlist';
                                       // show playlist
                                       $this->render_admin($this->mode);
         
         }
		}
        if (isset($_POST['add_media'])) {
            hd_add_media($this->wptfile_abspath, $this->wp_urlpath);
            $this->mode = 'main';
        }

        if (isset($_POST['youtube_media'])) {
            $act1 = youtubeurl();
?> <input type="hidden" name="act" id="act3" value="<?php echo $act1[3] ?>" />
            <input type="hidden" name="act" id="act0" value="<?php echo $act1[0] ?>" />
            <input type="hidden" name="act" id="act4" value="<?php echo $act1[4] ?>" />
            <input type="hidden" name="act" id="act5" value="<?php echo $act1[5] ?>" />
            <input type="hidden" name="act" id="act6" value="<?php echo $act1[6] ?>" />
<?php
            $this->mode = 'add'; // hd_add_media($this->wptfile_abspath, $this->wp_urlpath);
        }
        if (isset($_POST['edit_update'])) {
             $videourlmyfile= $_FILES["videourlmyfile"];
             $hdurlmyfile= $_FILES["hdurlmyfile"];
               $thumurlmyfile= $_FILES["thumurlmyfile"];
               $preimgurlmyfile= $_FILES["preimgurlmyfile"];
               $linkurlmyfile= $_FILES["linkurlmyfile"];
            hd_update_media($this->act_vid,$videourlmyfile,$hdurlmyfile,$thumurlmyfile,$preimgurlmyfile,$linkurlmyfile);
            $this->mode = 'main';
        }
        if (isset($_POST['cancel']) || isset($_POST['search']))
            $this->mode = 'main';

        if (isset($_POST['show_add']))
            $this->mode = 'add';

        if (isset($_POST['add_pl'])) {
            hd_add_playlist();
            $this->mode = 'edit';
        }

        if (isset($_POST['add_pl1'])) {
            hd_add_playlist();
            $this->mode = 'add';
        }

        if (isset($_POST['add_playlist'])) {
            hd_add_playlist();
            $this->mode = 'playlist';
        }

        if (isset($_POST['update_playlist'])) {
            hd_update_playlist();
            $this->mode = 'playlist';
        }

        if ($this->mode == 'delete') {
            hd_delete_media($this->act_vid, $this->options['deletefile']);
            $this->mode = 'main';
        }

//Let's show the main screen if no one selected
        if (empty($this->mode))
            $this->mode = 'main';


// render the admin screen
        $this->render_admin($this->mode);
    }

    function show_main() {
        global $wpdb;

// init variables
        $pledit = true;
        $where = '';
        $join = '';


// check for page navigation
        $sort = 'ASC';
        $search = ( isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
        $plfilter = ( isset($_REQUEST['plfilter'])) ? $_REQUEST['plfilter'] : (isset($_REQUEST['playid']) ? $_REQUEST['playid'] : '0' );



        if ($search != '') {
            if ($where != '')
                $where .= " AND ";
            $where .= " ((name LIKE '%$search%')) ";
        }

        if ($plfilter != '0' && $plfilter != 'no') {
            $join = " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play ON (vid = media_id) ";
            if ($where != '')
                $where .= " AND ";
            $where .= " (playlist_id = '" . $plfilter . "') ";
            $pledit = true;
        } elseif ($plfilter == 'no') {
            $join = " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play ON (vid = media_id) ";
            if ($where != '')
                $where .= " AND ";
            $where .= " (media_id IS NULL) ";
            $pledit = false;
        } else
            $pledit = false;

        if ($where != '')
            $where = " WHERE " . $where;
        $total = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "hdflvvideoshare" . $join . $where);

        $total_pages = ceil($total / $this->PerPage);
        if ($total_pages == 0)
            $total_pages = 1;



        if ($pledit)
            $orderby = " ORDER BY sorder " . $sort . ", vid " . $sort;
        else
            $orderby = " ORDER BY vid " . $sort;


// Generates retrieve request.
        $tables = $wpdb->get_results("SELECT DISTINCT name,file,featured,vid FROM " . $wpdb->prefix . "hdflvvideoshare" . $join . $where . $orderby . "");
        
        
            $folder   = dirname(plugin_basename(__FILE__));
            $site_url = get_bloginfo('url');
            $get_title = $wpdb->get_var("SELECT license FROM ".$wpdb->prefix."hdflvvideoshare_settings WHERE settings_id=1");
            $get_key     = app_videogall_encrypt();
                  
        
?>

        <!-- Manage Video-->
        <div class="wrap">
        <?php   if($get_title != $get_key)   { ?>
         <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
         <img src="<?php echo $site_url.'/wp-content/plugins/'.$folder.'/images/buynow.png';?>" style="float:right;margin-top: 10px;" width="125" height="28"  height="43" /></a>
         <?php } ?>
	        <form name="filterType" method="post" id="posts-filter">
                <h2><?php _e('Manage Video Files', 'hdflvvideoshare'); ?></h2>
                <div style='background:#D0D0D0;list-style:none;width:850px;float:left'><p style='padding-left:6px'>
Once you install "Wordpress video gallery" plugin, the page "Videos" will be created automatically. If you would like to display the video gallery on any other page or post, you can use the following plugin code.-

[videohome]<br><br>

To display single video player on any page or post use the plugin code in any of the formats specified below.<br><br>

[hdvideo id=3 playlistid=2 width=400 height=400 ] or [hdvideo playlistid=2 ] or [hdvideo id=3 ]

id - The video ID, you can find the video id on "All Videos" admin page.<br><br>
playlistid - You can find the playlist ID on manage playlist page.<br><br>

Both the video id and playlist id will generated automatically once you add new video or playlist to "Wordpress Video Gallery".<br><br>

You can use the plugin code with flashvars when you would like to display a player on any page/post with some specific settings.<br><br>

[hdvideo id=3 flashvars="autoplay=true & zoom=false"] 
                      </div>


                
                <div style=clear:both></div>
                 <?php if(isset($_REQUEST['doactionVideos']))
	          {
	          	if (isset($_REQUEST['actionVideos']) == 'delete')
	          	{
 ?>
 
            <div  class="updated below-h2">
                <p><?php echo 'Video(s) Deleted Successfully'; ?></p>
            </div>
<?php } } ?>   
                <ul class="subsubsub">
                    <li>&nbsp;</li>
                </ul>
                <p class="search-box">
                    <input type="text" class="search-input" name="search" value="<?php echo $search; ?>" size="25" />
                    <input type="submit" class="button-primary" value="<?php _e('Search Video', 'hdflvvideoshare'); ?>" />
                    <input type="hidden" name="cancel" value="2"/>
                </p>
                <div class="tablenav">
                    <div class="alignleft actions">
<?php $this->playlist_filter($plfilter); ?>
                <input class="button-secondary" id="post-query-submit" type="submit" name="startfilter"  value="<?php _e('Filter', 'hdflvvideoshare'); ?> &raquo;" class="button" />
            </div>
            <div style="margin-bottom: 5px;" class="alignleft actions">
<select name="actionVideos" id="actionVideos">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete">Delete</option>
</select>
<input id="doactionVideos" name="doactionVideos" class="button-secondary action" type="submit" value="Apply" name="" onClick="return deleteVideos();">
</div>
        </div>
        <div class="tablenav">
            <div class="alignright actions">
                <input class="button-primary" type="submit" value="<?php _e('Add Video', 'hdflvvideoshare') ?> &raquo;" name="show_add" />
            </div>
            <br class="clear"/>
        </div>
        <!-- Table -->
        <table class="widefat" cellspacing="0">
            <thead>
                <tr>
                    
                    
                    <?php if (isset($_REQUEST['plfilter']) && $_REQUEST['plfilter'] != 'no' && $_REQUEST['plfilter'] != '0' || isset($_REQUEST['playid'])) {
                        $id1 = '1'; ?><th id="Sort" class="manage-column column-id" scope="col"><?php _e('Sort', 'hdflvvideoshare'); ?> </th><?php } ?>
<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
												<input name='checkAllVideos' id="checkAllVideos" type="checkbox" onclick="javascript:check_all_Videos('filterType', this)">
												</th>
<th id="id" class="manage-column column-id" scope="col"><?php _e('ID', 'hdflvvideoshare'); ?> </th>

                    <th id="title" class="manage-column column-title" scope="col"><?php _e('Title', 'hdflvvideoshare'); ?> </th>
                    <th id="feature" class="manage-column column-feature" scope="col"><?php _e('Feature', 'hdflvvideoshare'); ?> </th>
                    <th id="path" class="manage-column column-path"  scope="col"><?php _e('Path', 'hdflvvideoshare'); ?> </th>
                    <?php if (isset($_REQUEST['plfilter']) && $_REQUEST['plfilter'] != 'no' && $_REQUEST['plfilter'] != '0' || isset($_REQUEST['playid'])) {
                        $id1 = '1'; ?><th id="path" class="manage-column column-path"  scope="col"><?php _e('Sort Order', 'hdflvvideoshare'); ?> </th><?php } ?>


                </tr>
            </thead>
            <tbody id="test-list" class="list:post">
            <input type=hidden id=playlistid2 name=playlistid2 value=<?php echo $plfilter ?> >
            <div name=txtHint ></div>
            <?php //echo '<pre>';print_r($tables); exit;
                    if ($tables) {
                        $i = 0;
                        foreach ($tables as $table) {
                            
                            $class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';

                            echo "<tr $class id=\"listItem=$table->vid\" >\n";
                              if ($id1 == '1') {
                                echo " <td>   <img src='../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/arrow.png' alt='move' width='16' height='16' class='handle' /></td></th>\n";
                            }
                            echo '<td><input id="video_'.$table->vid.'"  type="checkbox" value="'.$table->vid.'" name="videoList[]"></td>';
                            echo "<th scope=\"row\" >$table->vid";
                      
                            echo "<td class='post-title column-title''><strong><a title='" . __('Edit this media', 'hdflvvideoshare') . "' href='$this->base_page&amp;mode=edit&amp;id=$table->vid'>" . stripslashes($table->name) . "</a></strong>\n";
                            echo "<span class='edit'>
                            <a title='" . __('Edit this video', 'hdflvvideoshare') . "' href='$this->base_page&amp;mode=edit&amp;id=$table->vid'>" . __('Edit') . "</a>
                                                      </span> | ";
                            echo "<span class='delete'>
                                                                <a title='" . __('Delete this video', 'hdflvvideoshare') . "' href='$this->base_page&amp;mode=delete&amp;id=$table->vid' onclick=\"javascript:check=confirm( '" . __("Delete this file ?", 'hdflvvideoshare') . "');if(check==false) return false;\">" . __('Delete') . "</a>
                                                              </span>";
            ?>
                        <td>
<?php echo $table->featured; ?>
                        </td>

            <?php
                            echo "<td>" . htmlspecialchars(stripslashes($table->file), ENT_QUOTES) . "</td>\n";
                            if (isset($_REQUEST['plfilter']) && $_REQUEST['plfilter'] != 'no' && $_REQUEST['plfilter'] != '0') {
                                $a1 = mysql_query("SELECT sorder FROM " . $wpdb->prefix . "hdflvvideoshare_med2play where playlist_id=" . $_REQUEST['plfilter'] . " and media_id=$table->vid");
                               $playlist1 = mysql_fetch_array($a1);

                                echo "<td id=txtHint[$table->vid] >" . $playlist1[0] . "</td>\n";
                            } elseif (isset($_REQUEST['playid'])) {
                                $a1 = mysql_query("SELECT sorder FROM " . $wpdb->prefix . "hdflvvideoshare_med2play where playlist_id=" . $_REQUEST['playid'] . " and media_id=$table->vid");
                                $playlist1 = mysql_fetch_array($a1); 
                                echo "<td id=txtHint[$table->vid]>" . $playlist1[0] . "</td>\n";
                            }
                            echo '</tr>';
                            $i++;
                        }
                    } else {
                        echo '<tr><td colspan="7" ><b>' . __('No entries found', 'hdflvvideoshare') . '</b></td></tr>';
                    }
            ?>
                    </tbody>
                </table>
                <div class="tablenav">
                    <div class="alignleft actions">
                        <input class="button-primary" type="submit" value="<?php _e('Add Video', 'hdflvvideoshare') ?> &raquo;" name="show_add"/>
                    </div>
                    <br class="clear"/>
                </div>
            </form>
        </div><script>
        function check_all_Videos(frm, chAll)
        {
            
            var i=0;
            comfList = document.forms[frm].elements['videoList[]'];
            checkAll = (chAll.checked)?true:false; // what to do? Check all or uncheck all.
            // Is it an array
            if (comfList.length) {
                if (checkAll) {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = true;
                    }
                }
                else {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = false;
                    }
                }
            }
            else {
                /* This will take care of the situation when your
    checkbox/dropdown list (checkList[] element here) is dependent on
                a condition and only a single check box came in a list.
                 */
                if (checkAll) {
                    comfList.checked = true;
                }
                else {
                    comfList.checked = false;
                }
            }

            return;
        }

        function deleteVideos(){
				if(document.getElementById('actionVideos').selectedIndex == 1)
				{
					var playlistDelete= confirm('Are you sure to delete video(s) ?');
					if (playlistDelete){
						return true;
					}
					else{
						return false;
					}
				}
				else if(document.getElementById('actionVideos').selectedIndex == 0)
				{
				return false;
				}

			}
        </script>
        <!-- Manage Playlist-->
        <div class="wrap">
            
            <h2><?php _e('Playlist', 'hdflvvideoshare') ?> (<a href="<?php echo $this->base_page; ?>&mode=playlist"><?php _e('Add or Edit', 'hdflvvideoshare') ?></a>)</h2>
            <!--<p><?php //_e('You can show all videos files in a playlist. Show this playlist with the tag', 'hdflvvideoshare')  ?> <strong> [hdplay playlistid=id ]</strong></p> -->
            <form name="selectlist" method="post">
                <input type="hidden" name="apage" value="<?php echo $page; ?>" />
                <input type="hidden" name="search" value="<?php echo $search; ?>" />
                <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
                <input type="hidden" name="plfilter" value="<?php echo $plfilter; ?>" />

            </form>

        </div>
<?php
                }

                function show_edit() {

                    global $wpdb;

                    $media = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare where vid = $this->act_vid");
                    $act_name = htmlspecialchars(stripslashes($media->name));
                    $act_description = htmlspecialchars(stripslashes($media->description));
                    $act_filepath = stripslashes($media->file);
                    $act_hdpath = stripslashes($media->hdfile);
                    $act_image = stripslashes($media->image);
                    $act_link = stripslashes($media->link);
                    $act_opimg = stripslashes($media->opimage);
                    $act_download = $media->download;
                    $act_feature = $media->featured;
                    $act_prerollads = stripslashes($media->prerollads);
                    $act_postrollads = stripslashes($media->postrollads);
                    //k.laxmi Mar 2 , 2011 get the ads from datas
?>
                    <!-- Edit Video -->
                    <div class="wrap">
                    <?php if($get_title != $get_key)    {  ?>
                       <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
                       <img src="<?php echo $site_url.'/wp-content/plugins/'.$folder.'/images/buynow.png';?>" style="float:right;margin-top: 10px;" width="125" height="28"  height="43" /></a>
	        <?php } ?>
                        <h2> <?php _e('Edit Video File', 'hdflvvideoshare') ?> </h2>
                        <form name="table_options" method="post" id="video_options" enctype="multipart/form-data">
                            <div id="poststuff" class="has-right-sidebar">
                                <div class="inner-sidebar">
                                    <div id="submitdiv" class="postbox">
                                        <h3 class="hndle"><span><?php _e('Playlist', 'hdflvvideoshare') ?></span></h3>
                                        <div class="inside">
                                            <div id="submitpost" class="submitbox">
<!--                                                <div class="misc-pub-section">
                                                    <p><?php //_e('See global settings for the HDFLV Player under', 'hdflvvideoshare') ?>&nbsp;<a href="options-general.php?page=hdflvvideoshareplugin"><?php// _e('Options->HDFLVPlayer', 'hdflvvideoshare') ?></a> <br /><br />
<?php// _e('If you want to show this media file in your page, enter the tag :', 'hdflvvideoshare') ?><br /><strong>[hdvideo id=<?php// echo $this->act_vid; ?> ]</strong></p>
                            </div>-->
                            <div class="misc-pub-section">


                                <h4> <?php _e('Playlist', 'hdflvvideoshare'); ?> &nbsp;&nbsp;
                                    <a style="cursor:pointer"  onclick="playlistdisplay()"><?php _e('Create New', 'hdflvvideoshare') ?></a></h4>
                                <div id="playlistcreate"><?php _e('Name', 'hdflvvideoshare'); ?><input type="text" size="20" name="p_name" id="p_name" value="" />
                                    <input type="button" class="button-primary" name="add_pl1" value="<?php _e('Add'); ?>" onclick="return savePlaylist(document.getElementById('p_name') , <?php echo$this->act_vid ?>);" class="button button-highlighted" />
                                    <a style="cursor:pointer" onclick="playlistclose()"><b>Close</b></a></div>
                                <p id="jaxcat"></p>
                                <div id="playlistchecklist"><?php get_playlist_for_dbx($this->act_vid); ?></div>
                            </div>

                            <div id="major-publishing-actions">
                                <input type="submit" class="button-primary" name="edit_update" value="<?php _e('Update'); ?>" class="button button-highlighted" />
                                <input type="submit" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Playlist -->

                <div class="inside"><h3 class="hndle"><?php _e('Tags', 'hdflvvideoshare') ?></h3>
                    <textarea name="tag_name" rows="5" cols="30" id="tag_name"><?php get_vidtags_dbx($this->act_vid); ?></textarea>
                </div>
                <!-- End of Tags -->

            </div>
            <div id="post-body" class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Video Title', 'hdflvvideoshare') ?></th>
                            <td><input type="text" size="50"  name="act_name" value="<?php echo $act_name ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Video Description', 'hdflvvideoshare') ?></th>
                            <td><textarea cols="32" rows="8"  name="act_description" ><?php echo $act_description ?></textarea></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Video URL', 'hdflvvideoshare') ?></th>
                            <td><input type="text" size="80"  name="act_filepath" value="<?php echo $act_filepath ?>" />
                                <input type="file" name="videourlmyfile" size=40  />
                                <br /><?php _e('Here you need to enter the URL to the file ( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflvvideoshare') ?>
                                <br /><?php echo _e('It also accept Youtube links. Example: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'hdflvvideoshare') ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('HD video URL', 'hdflvvideoshare') ?></th>
                            <td><input type="text" size="80"  name="act_hdpath" value="<?php echo $act_hdpath ?>" />
                                 <input type="file" name="hdurlmyfile" size=40  />
                                <br /><?php _e('Enter the URL to HD video file', 'hdflvvideoshare') ?></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Thumbnail URL', 'hdflvvideoshare') ?></th>
                            <td><input type="text" size="80"  name="act_image" value="<?php echo $act_image ?>" />
                                  <input type="file" name="thumurlmyfile" size=40  />
                                <br /><?php _e('Enter the URL to show a thumbnail of the video file', 'hdflvvideoshare') ?></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Preview Image URL', 'hdflvvideoshare') ?></th>
                            <td><input type="text" size="80"  name="act_opimg" value="<?php echo $act_opimg ?>" />
                                 <input type="file" name="preimgurlmyfile" size=40  />
                                <br /><?php _e('Enter the URL to show a preview of the video file', 'hdflvvideoshare') ?></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Link URL', 'hdflvvideoshare') ?></th> 
                            <td><input type="text" size="80" name="act_link" value="<?php echo $act_link ?>" />
<!--                                 <input type="file" name="linkurlmyfile" size=40  />-->
                                <br /><?php _e('Enter the URL to the page/file, if you click on the player.for example http://www.google.com ', 'hdflvvideoshare') ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Feature', 'hdflvvideoshare') ?></th>
                            <td>
                                <?php //echo $act_feature;  ?>
                                <input type="radio" name="feature" value="ON" value="" <?php
                                if ($act_feature == 'ON') {
                                    echo "checked";
                                } ?>>ON
                                <input type="radio" name="feature" value="OFF" value="" <?php
                                       if ($act_feature == 'OFF') {
                                           echo "checked";
                                       }
                                ?>>OFF

                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Download', 'hdflvvideoshare') ?></th>
                            <td>
                                <?php //echo $act_feature;  ?>
                                       <input type="radio" name="download" value="1" <?php
                                       if ($act_download == '1') {
                                           echo "checked";
                                       }
                                ?>>YES
                                <input type="radio" name="download" value="0"  <?php
                                       if ($act_download == '0') {
                                           echo "checked";
                                       } ?>>NO
<br/><?php _e('Note:Not supported for YouTube videos', 'hdflvvideoshare') ?>
                                           </td>
                                       </tr>


                                       <!-- To display the list of pre roll ads -->
                        <?php
                                       //check whether preroll ads are enable
                                       // get the ads list
                                       global $wpdb;
                                       $tables = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads");

                                       $settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

                                       if ($settings[0]->prerollads == 0) {
                        ?>
                               <tr valign="top">
                                   <th scope="row"><?php _e('Preroll ads', 'ads') ?></th>
                               <td>

                                   <select name="act_prerollads">
                                       <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                               <option
<?php
                                               echo 'name="' . $table->ads_id . '" value="' . $table->ads_id . '"';
                                               if ($table->ads_id == $act_prerollads) {
                                                   echo 'selected=selected';
                                               }
?> ><?php echo $table->title; ?>
                                                           </option>
<?php } ?>
                                                   </select>
                                               </td>
                                           </tr>
                                    <?php } ?>

                           <!-- Drop down for the list of postrollads -->
<?php if ($settings[0]->postrollads == 0) { ?>
                           <tr valign="top">
                               <th scope="row"><?php _e('Postroll ads', 'ads') ?></th>
                                   <td>

                                       <select name="act_postrollads">
                                           <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                                           <option
<?php
                                               echo 'name="' . $table->ads_id . '" value="' . $table->ads_id . '"';
                                               if ($table->ads_id == $act_postrollads) {
                                                   echo 'selected=selected';
                                               }
?> ><?php echo $table->title; ?>
                                                           </option>
<?php } ?>
                                                   </select>
                                               </td>
                                           </tr>
<?php } ?>
                                   </table>
                               </div>
                               <p>
                                   <input type="submit" class="button-primary" name="edit_update" value="<?php _e('Update'); ?>" class="button button-highlighted" />
                                   <input type="submit" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" />
                               </p>
                           </div>
                       </div><!--END Poststuff -->

                   </form>

               </div><script> document.getElementById('playlistcreate').style.display = "none";

                       function playlistdisplay()
                       {
                           document.getElementById('playlistcreate').style.display = "block";
                       }
                       function playlistclose()
                       {
                           document.getElementById('playlistcreate').style.display = "none";
                       }
               </script><!--END wrap -->

<?php
                                   }

                                   function show_add() {
?>
                                       <!-- Add A Video -->

                                       <div class="wrap">

                                           <script type="text/javascript">

                                                   function t1(t2)
                                                   {
                                                       if(t2.value == "y" )
                                                       {
                                                           document.getElementById('upload2').style.display = "block"
                                                           document.getElementById('youtube').style.display = "none";
                                                           document.getElementById('customurl').style.display = "none";
                                                       }
                                                       if(t2.value == "c" ){
                                                           document.getElementById('youtube').style.display = "block";
                                                           document.getElementById('upload2').style.display = "none";
                                                           document.getElementById('customurl').style.display = "none";
                                                       }
                                                       if(t2.value == "url" ){
                                                           document.getElementById('customurl').style.display = "block";
                                                           document.getElementById('youtube').style.display = "none";
                                                           document.getElementById('upload2').style.display = "none";
                                                       }
                                                   }


                                           </script>

            
            
                                           <h2> <?php _e('Add a new video file', 'hdflv'); ?> </h2>
                                           <div id="poststuff" class="has-right-sidebar">
                                              <?php if($get_title != $get_key)    {  ?>
                                              <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
                                              <img src="<?php echo $site_url.'/wp-content/plugins/'.$folder.'/images/buynow.png';?>" style="float:right;margin-top: 4px;" width="125" height="28"  height="43" /></a>
	        								<?php  } ?>
                                               <div class="stuffbox" name="youtube" >
                                                   <h3 class="hndle"><span><input type="radio" name="agree" id="btn1" value="y" onClick="t1(this)" /> Upload file
                                                           <input type="radio" name="agree" id="btn2" value="c" checked ="checked" onClick="t1(this)" /> YouTube URL
                                                           <input type="radio" name="agree" id="btn3" value="url" onClick="t1(this)" /> Custom URL</span></h3>
                                                   <span id="message" style="margin-top:100px;margin-left:300px;color:red;font-size:12px;font-weight:bold;"></span>
                                                   <form method=post>
                                                       <div id="youtube" class="inside" style="margin:15px;">
                                                           <table class="form-table">
                                                               <tr>
                                                                   <th scope="row"><?php _e('URL to video file', 'hdflvvideoshare') ?></th>
                                                                   <td><input type="text" size="50" name="filepath" id="filepath1" onkeyup="generate12(this.value);" />&nbsp;&nbsp<input id="generate" type="submit" name="youtube_media" class="button-primary" value="<?php _e('Generate details', 'hdflvvideoshare'); ?>" />
                                                                       <br /><?php _e('Here you need to enter the URL to the video file', 'hdflvvideoshare') ?>
                                                                       <br /><?php _e('It accept also a Youtube link: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'hdflvvideoshare') ?></td>
                                                               </tr>
                                                           </table>
                                                       </div>

                                                       <div id="customurl" class="inside" style="margin:15px;">
                                                           <table class="form-table">
                                                               <tr>
                                                                   <th scope="row"><?php _e('URL to video file', 'hdflvvideoshare') ?></th>
                                                                   <td><input type="text" size="50" name="filepath2" id="filepath2" />
                                                                       <br /><?php _e('Here you need to enter the URL to the video file', 'hdflvvideoshare') ?>
                                                                   </td></tr>
                                                               <tr><th scope="row"><?php _e('URL to HD video file(optional)', 'hdflvvideoshare') ?></th>
                                                                   <td><input type="text" size="50" name="filepath3" id="filepath3" />
                                                                       <br /><?php _e('Here you need to enter the URL to the HD video file', 'hdflvvideoshare') ?>
                                                                   </td>
                                                               </tr>
                                                               <tr><th scope="row"><?php _e('URL to Thumb image file', 'hdflvvideoshare') ?></th>
                                                   <td><input type="text" size="50" name="filepath4" id="filepath4" />
                                                       <br /><?php _e('Here you need to enter the URL to the image file', 'hdflvvideoshare') ?>
                                                   </td>
                                               </tr>
                                               <tr><th scope="row"><?php _e('URL to Thumb Preview image file(Optional)', 'hdflvvideoshare') ?></th>
                                                   <td><input type="text" size="50" name="filepath5" id="filepath5" />
                                                       <br /><?php _e('Here you need to enter the URL to the Preview image file', 'hdflvvideoshare') ?>
                                                   </td>
                                               </tr>
                                           </table>
                                       </div>

                                   </form>
                                   <div id="upload2" class="inside" style="margin:15px;">
<?php _e('<b>Supported video formats:</b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflvvideoshare') ?>
                                       <table class="form-table">
                                           <tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1"><td>Upload Video</td>
                                               <td>
                                                   <div id="f1-upload-form" >
                                                       <form name="normalvideoform" method="post" enctype="multipart/form-data" >
                                                           <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                           <input type="button" class="button" name="uploadBtn" value="Upload Video" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                           <input type="hidden" name="mode" value="video" />
                                                       </form>
                                                   </div>
                                                   <div id="f1-upload-progress" style="display:none">
                                                       <div style="float:left"><img id="f1-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>" alt="Uploading"  style="padding-top:2px"/>
                                                           <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f1-upload-filename">PostRoll.flv</label></div>
                                                       <div style="float:right"> <span id="f1-upload-cancel">
                                                               <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('normalvideoform');" name="submitcancel">Cancel</a>
                                                           </span>
                                                           <label id="f1-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                           <span id="f1-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                               <b>Upload Failed:</b> User Cancelled the upload
                                                           </span></div>


                                                   </div>
                                               </td></tr>

                                           <tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new1"> <td>Upload HD Video(optional)</td>
                                               <td>
                                                   <div id="f2-upload-form" >
                                                       <form name="hdvideoform" method="post" enctype="multipart/form-data" >
                                                           <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                           <input type="button" class="button" name="uploadBtn" value="Upload Video" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                           <input type="hidden" name="mode" value="video" />
                                                       </form>
                                                   </div>
                                                   <div id="f2-upload-progress" style="display:none">
                                                       <div style="float:left"><img id="f2-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>" alt="Uploading"  style="padding-top:2px" />
                                                           <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f2-upload-filename">PostRoll.flv</label></div>
                                                       <div style="float:right"><span id="f2-upload-cancel">
                                                               <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('hdvideoform');" name="submitcancel">Cancel</a>

                                                           </span>
                                                           <label id="f2-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                           <span id="f2-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                               <b>Upload Failed:</b> User Cancelled the upload
                                                           </span></div>

                                                   </div>

                                               </td></tr>



                                           <tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new1"><td>Upload Thumb Image</td><td>
                                                   <div id="f3-upload-form" >
                                                       <form name="thumbimageform" method="post" enctype="multipart/form-data" >
                                                           <input type="file" name="myfile"  onchange="enableUpload(this.form.name);" />
                                                           <input type="button" class="button" name="uploadBtn" value="Upload Image"  disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                           <input type="hidden" name="mode" value="image" />
                                                       </form>
                                                   </div>
                                                   <div id="f3-upload-progress" style="display:none">
                                                       <div style="float:left"><img id="f3-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>" alt="Uploading" style="padding-top:2px" />
                                                           <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f3-upload-filename">PostRoll.flv</label></div>
                                                       <div style="float:right"> <span id="f3-upload-cancel">
                                                               <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('thumbimageform');" name="submitcancel">Cancel</a>
                                                           </span>
                                                           <label id="f3-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                           <span id="f3-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                               <b>Upload Failed:</b> User Cancelled the upload
                                                           </span></div>

                                                   </div>

                                               </td></tr>

                                           <tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new1"><td>Upload Preview Image(optional)</td><td>
                                                   <div id="f4-upload-form" >
                                                       <form name="previewimageform" method="post" enctype="multipart/form-data" >
                                                           <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                           <input type="button" class="button" name="uploadBtn" value="Upload Image" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                           <input type="hidden" name="mode" value="image" />
                                                       </form>
                                                   </div>
                                                   <div id="f4-upload-progress" style="display:none">
                                                       <div style="float:left"><img id="f4-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>" alt="Uploading" style="padding-top:2px" />
                                                           <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f4-upload-filename">PostRoll.flv</label></div>
                                                       <div style="float:right"><span id="f4-upload-cancel">
                                                               <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('previewimageform');" name="submitcancel">Cancel</a>
                                                           </span>
                                                           <label id="f4-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                           <span id="f4-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                               <b>Upload Failed:</b> User Cancelled the upload
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
                                   <input type="hidden" name="normalvideoform-value" id="normalvideoform-value" value=""  />
                                   <input type="hidden" name="hdvideoform-value" id="hdvideoform-value" value="" />
                                   <input type="hidden" name="thumbimageform-value" id="thumbimageform-value"  value="" />
                                   <input type="hidden" name="previewimageform-value" id="previewimageform-value"  value="" />
                                   <input type="hidden" name="youtube-value" id="youtube-value"  value="" />
                                   <input type="hidden" name="customurl" id="customurl1"  value="" />
                                   <input type="hidden" name="customhd" id="customhd1"  value="" />
                                   <input type="hidden" name="customimage" id="customimage"  value="" />
                                   <input type="hidden" name="custompreimage" id="custompreimage"  value="" />
                                   <div class="inner-sidebar" >
                                       <div id="submitdiv" class="postbox">
                                           <h3 class="hndle" style="color:white;background:none;background-color:black"><span><?php _e('Playlist', 'hdflvvideoshare') ?></span></h3>
                       <div class="inside" style="color:blue" >
                           <div id="submitpost" class="submitbox">
<!--                               <div class="misc-pub-section">
                                   <p>
<?php //_e('See global settings for the Wordpress Video Gallery under', 'hdflvvideoshare') ?> <a href="admin.php?page=hdflvvideoshare"><?php //_e('Options->Wordpress Video Gallery Settings', 'hdflvvideoshare') ?></a>
                                   </p>
                               </div>-->
                               <div class="misc-pub-section"><?php //if(mysql_num_rows($playid1)) {   ?>
                                   <h4><?php _e('Playlist', 'hdflvvideoshare'); ?>&nbsp;&nbsp;
                                       <a style="cursor:pointer"  onclick="playlistdisplay()"><?php _e('Create New', 'hdflvvideoshare') ?></a></h4>
                                   <div id="playlistcreate1"><?php _e('Name', 'hdflvvideoshare'); ?><input type="text" size="20" name="p_name" id="p_name" value="" />
                                       <input type="button" class="button-primary" name="add_pl1" value="<?php _e('Add'); ?>" onclick="return savePlaylist(document.getElementById('p_name') , <?php echo$this->act_vid ?>);" class="button button-highlighted" />
                                       <a style="cursor:pointer" onclick="playlistclose()"><b>Close</b></a></div>
                                   <div id="jaxcat"></div>
                                   <div id="playlistchecklist"><?php get_playlist(); ?></div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- END of playlist -->
                   <div id="submitdiv" class="postbox">
                       <h3 class="hndle" style="color:white;background:none;background-color:black"><span><?php _e('TAGS', 'hdflvvideoshare') ?></span></h3>
                       <div class="inside" style="color:blue" >
                           <div id="submitpost" class="submitbox">
                               <div class="misc-pub-section"><textarea rows="5" cols="30" name="tag_name" id="tag_name"></textarea>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- End of Tags -->
               </div>
               <div id="post-body" class="has-sidebar"><br>
                   <div id="post-body-content" class="has-sidebar-content">

                       <div class="stuffbox">
                           <h3 class="hndle"><span><?php _e('Enter Title / Name', 'hdflvvideoshare'); ?></span></h3>
                           <div class="inside" style="margin:15px;">
                               <table class="form-table">
                                   <tr>
                                       <th scope="row"><?php _e('Title / Name', 'hdflvvideoshare') ?></th>
                                       <td><input type="text" size="50" maxlength="200" name="name" id="name" /></td>
                                   </tr>
                                   <tr>
                                       <th scope="row"><?php _e('Description', 'hdflvvideoshare') ?></th>
                                       <td><textarea id="description" name="description" rows="8" cols="32"></textarea></td>
                                   </tr>
                                   <tr>
                                       <th scope="row"><?php _e('Featured video', 'hdflvvideoshare') ?></th>
                                       <td>
                                           <input type="radio" name="feature" value="ON">YES
                                           <input type="radio" name="feature" value="OFF">NO

                                       </td>
                                   </tr>
                                   <tr>
                                       <th scope="row"><?php _e('Download', 'hdflvvideoshare') ?></th>
                                                       <td>
                                                           <input type="radio" name="download" value="1">YES
                                                           <input type="radio" name="download" value="0">NO
<br/><?php _e('Note:Not supported for YouTube videos', 'hdflvvideoshare') ?>
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
                                       $tables = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads");

                                       $settings = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
                                       if ($settings[0]->preroll == 0 || $settings[0]->postroll == 0) {
?>

                    <div class="stuffbox">
                        <h3 class="hndle"><span><?php _e('Select Ads', 'ads'); ?></span></h3>
                        <div class="inside" style="margin:15px;">
<?php if ($settings[0]->preroll == 0) { ?>
                                               <table class="form-table">
                                                   <tr>
                                                       <th scope="row"><?php _e('Preroll ads', 'ads') ?></th>
                                                       <td>
                                                           <select name="prerollads" id="prerollads" >
                                                               <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                                                   <option name="<?php echo $table->ads_id ?>" value="<?php echo $table->ads_id ?>" > <?php echo $table->title ?></option>
                                            <?php } ?>
                                           </select>
                                       </td>
                                   </tr>
                               </table>
<?php } if ($settings[0]->postroll == 0) { ?>


                               <table class="form-table">

                                   <tr>
                                       <th scope="row"><?php _e('Postroll ads', 'ads') ?></th>
                                                               <td>
                                                                   <select name="postrollads" id="postrollads" >
                                                                       <option value="0" >select</option>
<?php foreach ($tables as $table) { ?>
                                                                           <option name="<?php echo $table->ads_id ?>" value="<?php echo $table->ads_id ?>" > <?php echo $table->title ?></option>
<?php } ?>
                                                                   </select>
                                                               </td>
                                                           </tr>
                                                       </table>
<?php } ?>

                                               </div>
                                           </div>
<?php } ?>


                                   </div>
                                   <p><input type="submit" name="add_media" class="button-primary"  onclick="return validateInput();" value="<?php _e('Add video file', 'hdflvvideoshare'); ?>" class="button" /><input type="submit" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" /></p>
                               </div>
                           </div><!--END Poststuff -->
                       </form><script>document.getElementById('upload2').style.display = "none";
                               document.getElementById('customurl').style.display = "none";
                               document.getElementById('name').value = document.getElementById('act0').value;
                               document.getElementById('filepath1').value = document.getElementById('act4').value;
                               document.getElementById('description').value = document.getElementById('act5').value;
                               document.getElementById('tag_name').value = document.getElementById('act6').value;
                       </script>
                       <script>
                               document.getElementById('playlistcreate1').style.display = "none";

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
                                   var re= /http:\/\/www\.youtube[^"]+/;
                                   if(re.test(str1))
                                       document.getElementById('generate').style.visibility = "visible";
                                   else document.getElementById('generate').style.visibility  = "hidden";
                               }
                       </script>

                   </div><!--END wrap -->
<?php
                                   }

                                   function show_plydel() {
                                       $message = hd_delete_playlist($this->act_pid);
                                       $this->render_message($message);
                                       $this->mode = 'playlist';
                                       // show playlist
                                       $this->render_admin($this->mode);
                                   }

                                   function show_plyedit() {
                                       // use the same output as playlist
                                       $this->render_admin('playlist');
                                   }

                                   // Edit or update playlst
                                   function show_playlist() {

                                       global $wpdb;

                                       // get the tables
                                       $tables = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist ");
                                       if ($this->mode == 'plyedit')
                                           $update = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = {$this->act_pid} ");
?>

                           <!-- Edit Playlist -->
                           <div class="wrap">
                              <?php if($get_title != $get_key)    {  ?>
                               <a href="http://www.apptha.com/shop/checkout/cart/add/product/12" target="_blank">
                               <img src="<?php echo $site_url.'/wp-content/plugins/'.$folder.'/images/buynow.png';?>" style="float:right;margin-top: 10px;" width="125" height="28"  height="43" /></a>
	                            <?php } ?>     
	                             <div style="clear: both"></div>
	                            <?php if(isset($_REQUEST['doactionPlaylist']))
	          {
	          	if (isset($_REQUEST['actionPlaylist']) == 'delete')
	          	{
 ?>
 
            <div  class="updated below-h2">
                <p><?php echo 'Playlist(s) Deleted Successfully'; ?></p>
            </div>
<?php } } ?>               
                               <h2><?php _e('Manage Playlist', 'hdflvvideoshare'); ?></h2>
                               <br class="clear"/>
                               
                               <form id="editplist" name="editplist" action="<?php echo $this->base_page; ?>" method="post">
                               <div style="margin-bottom: 5px;" class="alignleft actions">
<select name="actionPlaylist" id="actionPlaylist">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete">Delete</option>
</select>
<input id="doactionPlaylist" name="doactionPlaylist" class="button-secondary action" type="submit" value="Apply" name="" onClick="return deletePlaylist();">
</div>
                                   <table class="widefat" cellspacing="0">
                                       <thead>
                                           <tr>
                                           <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
												<input type="checkbox" name='checkAll' id="checkAll" onclick="javascript:check_all('editplist', this)">
												</th>
                                               <th scope="col"><?php _e('ID', 'hdflvvideoshare'); ?></th>
                                               <th scope="col"><?php _e('Name', 'hdflvvideoshare'); ?></th>
                                               <th scope="col" colspan="2"><?php _e('Action'); ?></th>
                                           </tr>
                                       </thead>
            <?php
                                       if ($tables) {
                                           $i = 0;
                                           foreach ($tables as $table) {
                                               if ($i % 2 == 0) {
                                                   echo "<tr class='alternate'>\n";
                                               } else {
                                                   echo "<tr>\n";
                                               }
                                               echo '<td><input id="user_'.$table->pid.'"  type="checkbox" value="'.$table->pid.'" name="checkList[]"></td>';
                                               echo "<th scope=\"row\">$table->pid</th>\n";
                                               echo "<td><a onclick=\"submitplay($table->pid)\" href=\"#\" >" . stripslashes($table->playlist_name) . "</td>\n";
                                               echo "<td><a href=\"$this->base_page&amp;mode=plyedit&amp;pid=$table->pid#addplist\" class=\"edit\">" . __('Edit') . "</a></td>\n";
                                               echo "<td><a href=\"$this->base_page&amp;mode=plydel&amp;pid=$table->pid\" class=\"delete\" onclick=\"javascript:check=confirm( '" . __("Delete this file ?", 'hdflvvideoshare') . "');if(check==false) return false;\">" . __('Delete') . "</a></td>\n";
                                               echo '</tr>';
                                               $i++;
                                           }
                                       } else {
                                           echo '<tr><td colspan="7" align="center"><b>' . __('No entries found', 'hdflvvideoshare') . '</b></td></tr>';
                                       }
            ?>
                                   </table>
                                   <input type="hidden" name="playid" id="playid" value="" />
                               </form>
                               <script type="text/javascript">
                               function deletePlaylist(){
                   				if(document.getElementById('actionPlaylist').selectedIndex == 1)
                   				{
                   					var playlistDelete= confirm('Are you sure to delete playlist(s) ?');
                   					if (playlistDelete){
                   						return true;
                   					}
                   					else{
                   						return false;
                   					}
                   				}
                   				else if(document.getElementById('actionPlaylist').selectedIndex == 0)
                   				{
                   				return false;
                   				}

                   			}
                               function check_all(frm, chAll)
                               {
                                   
                                   var i=0;
                                   comfList = document.forms[frm].elements['checkList[]'];
                                   checkAll = (chAll.checked)?true:false; // what to do? Check all or uncheck all.
                                   // Is it an array
                                   if (comfList.length) {
                                       if (checkAll) {
                                           for (i = 0; i < comfList.length; i++) {
                                               comfList[i].checked = true;
                                           }
                                       }
                                       else {
                                           for (i = 0; i < comfList.length; i++) {
                                               comfList[i].checked = false;
                                           }
                                       }
                                   }
                                   else {
                                       /* This will take care of the situation when your
                           checkbox/dropdown list (checkList[] element here) is dependent on
                                       a condition and only a single check box came in a list.
                                        */
                                       if (checkAll) {
                                           comfList.checked = true;
                                       }
                                       else {
                                           comfList.checked = false;
                                       }
                                   }

                                   return;
                               }
                                       function submitplay(playid)
                                       {
                                           document.getElementById('playid').value = playid;
                                           document.editplist.action = "?page=hdflvvideoshare";
                                           document.editplist.submit();
                                       }
                               </script>
                           </div>

                           <div class="wrap">
                                 
                               <div id="poststuff" class="metabox-holder">
                                   <div id="playlist_edit" class="stuffbox">
                                       <h3><?php
                                       if ($this->mode == 'playlist')
                                           echo _e('Add Playlist', 'hdflvvideoshare');
                                       if ($this->mode == 'plyedit')
                                           echo _e('Update Playlist', 'hdflvvideoshare');
            ?></h3>
                           <div class="inside">
                               <form id="addplist" action="<?php echo $this->base_page; ?>" method="post">
                                   <input type="hidden" value="<?php echo $this->act_pid ?>" name="p_id" />
                                   <p><?php _e('Name:', 'hdflvvideoshare'); ?><br/><input type="text" value="<?php echo $update->playlist_name ?>" name="p_name"/></p>
                                                           <div class="submit">
<?php
                                       if ($this->mode == 'playlist')
                                           echo '<input type="submit" name="add_playlist" value="' . __('Add Playlist', 'hdflvvideoshare') . '" class="button-primary" />';
                                       if ($this->mode == 'plyedit')
                                           echo '<input type="submit" name="update_playlist" value="' . __('Update Playlist', 'hdflvvideoshare') . '" class="button-primary" />';
?>
                                                               <input type="submit" name="cancel" value="<?php _e('Cancel', 'hdflvvideoshare'); ?>" class="button-secondary" />
                                                           </div>
                                                       </form>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
            <?php
                                   }

// Display sort form filter
// Display playlist form filter
                                   function playlist_filter($plfilter) {
                                       global $wpdb;
            ?>
                           <select name="plfilter">
                               <option value="0" <?php if ($plfilter == '0')
                                           echo 'selected="selected"'; ?>><?php _e('--Playlist--', 'hdflvvideoshare'); ?></option>
                                           <option value="no" <?php if ($plfilter == 'no')
                                           echo 'selected="selected"'; ?>><?php _e('No playlist', 'hdflvvideoshare'); ?></option>
<?php
                                       $dbresults = $wpdb->get_results(" SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist ");
                                       if ($dbresults) {
                                           foreach ($dbresults as $dbresult) :
                                               echo '<option value="' . $dbresult->pid . '"';
                                               if ($plfilter == $dbresult->pid)
                                                   echo 'selected="selected"';
                                               echo '>' . $dbresult->playlist_name . '</option>';
                                           endforeach;
                                       }
?>
                                       </select>
<?php
                                   }

                               }
                              
?>