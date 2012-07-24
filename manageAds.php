<?php
/*
 * version : 1.3
 * Edited by : John THomas
 * Email : johnthomas@contus.in
 * Purpose : Common functions needed throughout the plugin
 * Path:/wp-content/plugins/wordpress-video-gallery/manage.php
 * Date:13/1/11
 *
 */

$contus = dirname(plugin_basename(__FILE__));
$site_url = get_option('siteurl');
?>

<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="../wp-content/plugins/<?php echo $contus ?>/selectuser.js"></script>
<link rel='stylesheet' href='../wp-content/plugins/<?php echo $contus ?>/styles123.css' type='text/css' media='all' />
<script type="text/javascript">
    // When the document is ready set up our sortable with it's inherant function(s)
    $(document).ready(function() {
        $("#test-list").sortable({
            handle : '.handle',
            update : function () {
                var order = $('#test-list').sortable('serialize');

                //alert(order);
                var playid = document.getElementById('playlistid2').value;
                //$("#info").load("../wp-content/plugins/<?php echo $contus ?>/process-sortable.php?"+order+"&playid="+playid);

                showUser(playid,order);
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
            document.getElementById('message').innerHTML = 'Enter  URL';
            return false;
        }
        if(document.getElementById('btn1').checked == true && document.getElementById('f1-upload-form').style.display != 'none'){
            document.getElementById('message').innerHTML = 'Upload Ads';
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
        if( whichForm == 'adsform' || whichForm == 'hdvideoform' )
        {
            if(extn != 'flv' && extn != 'FLV' && extn != 'mp4' && extn != 'MP4' && extn != 'm4v' && extn != 'M4V' && extn != 'mp4v' && extn != 'Mp4v' && extn != 'm4a' && extn != 'M4A' && extn != 'mov' && extn != 'MOV' && extn != 'f4v' && extn != 'F4V' && extn != 'mp3' && extn != 'MP3')
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
        document.forms[form_handle].target = "uploadads_target";
        document.forms[form_handle].action = "../wp-content/plugins/<?php echo $contus; ?>/upload1.php?processing=1";
        document.forms[form_handle].submit();
       
    }
    function setStatus(form_handle,status)
    {
        switch(form_handle)
        {
            case "adsform":
                divprefix = 'f1';
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

class HDVIDEOManageAds {

    var $mode = 'main';
    var $wptfile_abspath;
    var $wp_urlpath;
    var $ads_vid = false;
    var $act_pid = false;
    var $base_page = '?page=vgads';
    var $PerPage = 10;

    function HDVIDEOManageAds() {
        global $hdflv;

        // get the options
        $this->options = get_option('HDFLVSettings');

        // Manage upload dir
        add_filter('upload_dir', array(&$this, 'upload_dir'));

        $wp_upload = wp_upload_dir();

        $this->wptfile_abspath = $wp_upload['path'] . '/';
        $this->wp_urlpath = $wp_upload['url'] . '';

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
                $message = sprintf(__('Unable to create directory %s. Is its parent directory writable by the server?', 'hdflv'), $dir);
                $uploads['error'] = $message;
                return $uploads;
            }
            $uploads = array('path' => $dir, 'url' => $url, 'error' => false);
        }
        return $uploads;
    }

    function render_message($message, $timeout = 0) {
?>
        <div class="wrap">
            <div class="fade updated" id="message" onclick="this.parentNode.removeChild (this)">
                <p><strong><?php echo $message ?></strong></p>
            </div>
        </div>
<?php
    }

    function controller() {
        global $wpdb;
        $this->mode = trim($_GET['mode']);

        $this->ads_vid = (int) $_GET['id'];
        $this->act_pid = (int) $_GET['pid'];

//TODO:Include nonce !!!

        if (isset($_POST['add_ads'])) {
            hd_add_ads($this->wptfile_abspath, $this->wp_urlpath);
            $this->mode = 'main';
        }

       
        if (isset($_POST['edit_update'])) {
            $editfilepath = $_FILES["myfile"];
            hd_update_ads($this->ads_vid,$editfilepath);
            $this->mode = 'main';
        }

        if (isset($_POST['cancel']) || isset($_POST['search']))
            $this->mode = 'main';

        if (isset($_POST['show_add']))
            $this->mode = 'add';

              
        if ($this->mode == 'delete') {
            hd_delete_ads($this->ads_vid, $this->options['deletefile']);
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
            $where .= " ((title LIKE '%$search%')) ";
        }

        if ($plfilter != '0' && $plfilter != 'no') {
            $join = " LEFT JOIN " . $wpdb->prefix . "hdflv_med2play ON (vid = media_id) ";
            if ($where != '')
                $where .= " AND ";
            $where .= " (playlist_id = '" . $plfilter . "') ";
            $pledit = true;
        } elseif ($plfilter == 'no') {
            $join = " LEFT JOIN " . $wpdb->prefix . "hdflv_med2play ON (vid = media_id) ";
            if ($where != '')
                $where .= " AND ";
            $where .= " (media_id IS NULL) ";
            $pledit = false;
        } else
            $pledit = false;

        if ($where != '')
            $where = " WHERE " . $where;

        $total = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "hdflvvideoshare_vgads" . $join . $where);

        $total_pages = ceil($total / $this->PerPage);
        if ($total_pages == 0)
            $total_pages = 1;



        if ($pledit)
            $orderby = " ORDER BY sorder " . $sort . ", ads_id " . $sort;
        else
            $orderby = " ORDER BY ads_id " . $sort;

// Generates retrieve request.
        $tables = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads" . $join . $where . $orderby . "");
?>

        <!-- Manage Video-->
        <div class="wrap">
            <form name="filterType" method="post" id="posts-filter">
                <h2><?php _e('Manage Ads', 'ads'); ?></h2>
                <ul class="subsubsub">
                    <li>&nbsp;</li>
                </ul>
                <p class="search-box">
                    <input type="text" class="search-input" name="search" value="<?php echo $search; ?>" size="10" />
                    <input type="submit" class="button-primary" value="<?php _e('Search Video ADs', 'hdflv'); ?>" />
                    <input type="hidden" name="cancel" value="2"/>
                </p>
                
        <!-- Table -->
        <table class="widefat" cellspacing="0">
            <thead>
                <tr>
                    <th id="id" class="manage-column column-id" scope="col"><?php // _e('ID', 'ads'); ?> </th>
                    <th id="title" class="manage-column column-title" scope="col"><?php _e('Title', 'ads'); ?> </th>
                    <th id="path" class="manage-column column-path"  scope="col"><?php _e('Path', 'ads'); ?> </th>
                    <?php if (isset($_REQUEST['plfilter']) && $_REQUEST['plfilter'] != 'no' && $_REQUEST['plfilter'] != '0' || isset($_REQUEST['playid'])) {
                        $id1 = '1'; ?><th id="path" class="manage-column column-path"  scope="col"><?php _e('Sort Order', 'hdflv'); ?> </th><?php } ?>


                </tr>
            </thead>
            <tbody id="test-list" class="list:post">
            <input type=hidden id=playlistid2 name=playlistid2 value=<?php echo $plfilter ?> >
            <div name=txtHint ></div>
            <?php
                    if ($tables) {
                        $i = 0;
                        foreach ($tables as $table) {
                            $class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';
                            echo "<tr $class id=\"listItem=$table->ads_id\" >\n";
                            echo "<th scope=\"row\" >$table1->ads_id";
                            if ($id1 == '1') {
                                echo "<img src='../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/arrow.png' alt='move' width='16' height='16' class='handle' /></th>\n";
                            }
                            echo "<td class='post-title column-title''><strong><a title='" . __('Edit this media', 'hdflv') . "' href='$this->base_page&amp;mode=edit&amp;id=$table->ads_id'>" . stripslashes($table->title) . "</a></strong>\n";
                            echo "<span class='edit'>
                                                                <a title='" . __('Edit this ads', 'hdflv') . "' href='$this->base_page&amp;mode=edit&amp;id=$table->ads_id'>" . __('Edit') . "</a>
                                                              </span> | ";
                            echo "<span class='delete'>
                                                                <a title='" . __('Delete this ads', 'hdflv') . "' href='$this->base_page&amp;mode=delete&amp;id=$table->ads_id' onclick=\"javascript:check=confirm( '" . __("Delete this Ads ?", 'ads') . "');if(check==false) return false;\">" . __('Delete') . "</a>
                                                              </span>";
                            echo "</td>\n";
                            echo "<td>" . htmlspecialchars(stripslashes($table->file_path), ENT_QUOTES) . "</td>\n";
                           

                            echo '</tr>';
                            $i++;
                        }
                    } else {
                        echo '<tr><td colspan="7" ><b>' . __('No entries found', 'ads') . '</b></td></tr>';
                    }
            ?>
                    </tbody>
                </table>
                <div class="tablenav">
                    <div class="alignleft actions">
                        <input class="button-secondary" type="submit" value="<?php _e('Add Video Ad', 'ads') ?> &raquo;" name="show_add"/>
                    </div>
                    <br class="clear"/>
                </div>
            </form>
        </div><script></script>
       

<?php
                }

                function show_edit() {


                    global $wpdb;

                    $ads = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_vgads where ads_id = $this->ads_vid");
                   
                    $ads_name = htmlspecialchars(stripslashes($ads->title));
                    $ads_filepath = stripslashes($ads->file_path);
                    
?>
                    <!-- Edit Video -->
                    <div class="wrap">
                        <h2> <?php _e('Edit video file', 'hdflv') ?> </h2>
                        <form name="table_options" method="post" id="video_options" enctype="multipart/form-data" >
                            <div id="poststuff" class="has-right-sidebar">
                  
            <div id="post-body" class="has-sidebar">
                <div id="post-body-content" class="has-sidebar-content">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Ads title', 'ads') ?></th>
                            <td><input type="text" size="50"  name="ads_name" value="<?php echo $ads_name ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Ads URL', 'ads') ?></th>

                            <td><input type="text" size="80"  name="ads_filepath" value="<?php echo $ads_filepath ?>" /> <input type="file" name="myfile" value="" size=40  />
                                <br /><?php _e('Here you need to enter the URL to the file ( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'ads') ?>
                                <br /><?php echo _e('It also accept Youtube links. Example: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'hdflv') ?>
                        </td>
                        </tr>
                        
                    </table>
                </div>
                <p>
                    <input type="submit" class="button-primary" name="edit_update" value="<?php _e('Update'); ?>" class="button button-highlighted" />
                    <input type="submit" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" />
                </p>
            </div>
        </div><!--END Poststuff -->

    </form>

</div><!--END wrap -->
<?php
                                }

                                function show_add() {
?>
                                    <!-- Add A Video -->

                                    <div class="wrap">

                                        <script type="text/javascript">

                                            function t1(t2)
                                            {
                                                if(t2.value == "file" )
                                                {
                                                    document.getElementById('upload2').style.display = "block"
                                                    document.getElementById('youtube').style.display = "none";
                                                    document.getElementById('customurl').style.display = "none";
                                                }
                                                if(t2.value == "url" ){
                                                    document.getElementById('youtube').style.display = "block";
                                                    document.getElementById('upload2').style.display = "none";
                                                    document.getElementById('customurl').style.display = "none";
                                                }
                                                
                                            }


                                        </script>
                                        <div id="playlistResponse">

                                        </div>
                                        <h2> <?php _e('Add a new ads', 'ads'); ?> </h2>
                                        <div id="poststuff" class="has-right-sidebar">
                                            <div class="stuffbox" name="youtube" >
                                                <h3 class="hndle">
                                                    <span>
                                                        <input type="radio" name="agree" id="btn1" value="file" onClick="t1(this)" /> File
                                                        <input type="radio" name="agree" id="btn2" value="url" checked ="checked" onClick="t1(this)" />  URL
                                                    </span>
                                                </h3>
                                                <span id="message" style="margin-top:100px;margin-left:300px;color:red;font-size:12px;font-weight:bold;"></span>
                                                <form method=post>
                                                    <div id="youtube" class="inside" style="margin:15px;">
                                                        <table class="form-table">
                                                            <tr>
                                                                <th scope="row"><?php _e('URL to video file', 'hdflv') ?></th>
                                                                <td><input type="text" size="50" name="filepath" id="filepath1" onkeyup="generate12(this.value);" />&nbsp;&nbsp
                                                                    <br /><?php _e('Here you need to enter the URL to the ads video file', 'hdflv') ?>
                                                                    <br /><?php _e('It accept also a Youtube link: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'ads') ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                   

                                                </form>
                                                <div id="upload2" class="inside" style="margin:15px;">
                                    <?php _e('<b>Supported video formats:</b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflv') ?>
                                    <table class="form-table">
                                        <tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1"><td>Upload Video</td>
                                            <td>
                                                <div id="f1-upload-form" >
                                                    <form name="adsform" method="post" enctype="multipart/form-data" >
                                                        <input type="file" name="myfile" onchange="enableUpload(this.form.name);" />
                                                        <input type="button" class="button" name="uploadBtn" value="Upload Video" disabled="disabled" onclick="return addQueue(this.form.name,this.form.myfile.value);" />
                                                        <input type="hidden" name="mode" value="video" />
                                                    </form>
                                                </div>
                                                <div id="f1-upload-progress" style="display:none">
                                                    <div style="float:left"><img id="f1-upload-image" src="<?php echo get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>" alt="Uploading"  style="padding-top:2px"/>
                                                        <label style="padding-top:0px;padding-left:4px;font-size:14px;font-weight:bold;vertical-align:top"  id="f1-upload-filename">PostRoll.flv</label></div>
                                                    <div style="float:right"> <span id="f1-upload-cancel">
                                                            <a style="float:right;padding-right:10px;" href="javascript:cancelUpload('adsform');" name="submitcancel">Cancel</a>
                                                        </span>
                                                        <label id="f1-upload-status" style="float:right;padding-right:40px;padding-left:20px;">Uploading</label>
                                                        <span id="f1-upload-message" style="float:right;font-size:10px;background:#FFAFAE;">
                                                            <b>Upload Failed:</b> User Cancelled the upload
                                                        </span></div>


                                                </div>
                                            </td></tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                                                <div id="nor"><iframe id="uploadads_target" name="uploadads_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></div>
                        
                        <form name="table_options" enctype="multipart/form-data" method="post" id="video_options" onsubmit="return chkbut()">

                            <div id="poststuff" class="has-right-sidebar">
                               
                                <input type="hidden" name="adsform-value" id="adsform-value" value=""  />
                                <input type="hidden" name="youtube-value" id="youtube-value"  value="" />
                                
                                

            <div id="post-body" class="has-sidebar"><br>
                <div id="post-body-content" class="has-sidebar-content">

                    <div class="stuffbox">
                        <h3 class="hndle"><span><?php _e('Enter Title / Name', 'ads'); ?></span></h3>
                        <div class="inside" style="margin:15px;">
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><?php _e('Title / Name', 'ads') ?></th>
                                    <td><input type="text" size="50" maxlength="200" name="name" id="name" /></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
                <p><input type="submit" name="add_ads" class="button-primary" onclick="return validateInput();" value="<?php _e('Add Video Ad', 'ads'); ?>" class="button" /><input type="submit" class="button-secondary" name="cancel" value="<?php _e('Cancel'); ?>" class="button" /></p>
            </div>
        </div><!--END Poststuff -->

    </form><script>document.getElementById('upload2').style.display = "none";
        document.getElementById('customurl').style.display = "none";
        document.getElementById('name').value = document.getElementById('act0').value;
        document.getElementById('filepath1').value = document.getElementById('act4').value;

    </script>
   

</div><!--END wrap -->
<?php
                                }
}


?>
