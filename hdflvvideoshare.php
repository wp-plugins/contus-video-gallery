<?php
/*
  Plugin Name: Contus VideoGallery Plugin
  Version: 1.1
  Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
  Description: Simplifies the process of adding video to a WordPress blog. Powered by Contus Support HDFLVPlayer and SWFObject.
  Author: Contus Support.
 
 */

$videoid = 0;
$site_url = get_option('siteurl'); 

function HDFLV_ShareParse($content) {
    $content = preg_replace_callback('/\[hdvideo ([^]]*)\o]/i', 'HDFLV_shareRender', $content);
    $content = preg_replace_callback('/\[contusHome\]/', 'HDFLV_Hpages', $content);
    $content = preg_replace_callback('/\[contusMore\]/', 'HDFLV_Mpages', $content);
    $content = preg_replace_callback('/\[contusVideo\]/', 'HDFLV_Vpages', $content);
    return $content;
}
function HDFLV_Hpages()
{
    global $wpdb;
    include_once("themes/default/contusHome.php");
    $pageObj    = new contusHome();
    $returnPlayer  = $pageObj->videosSharePlayer();
    $returnFeatures= $pageObj->featureVideos();
    $returnRecent = $pageObj->recentVideos();
    $returnPopular = $pageObj->popularVideos();
    return $returnPlayer.$returnFeatures.$returnRecent.$returnPopular;

}
function HDFLV_Mpages()
{
    global $wpdb;
    include("themes/default/contusMore.php");
    $moreObj = new contusMore();
    $moreFeature = $moreObj->featureVideos();
    $moreRecent  = $moreObj->recentVideos();
    $morePopular = $moreObj->popularVideos();
    $morePlaylist  = $moreObj->relatedPlaylist();
    return $moreFeature.$moreRecent.$morePopular.$morePlaylist;

}
function HDFLV_Vpages()
{
    global $wpdb;
    include("themes/default/contusVideo.php");
    $pageVideos = new contusVideos();
    $listVideos = $pageVideos->listVideos();
    return $listVideos;

}

function HDFLV_shareRender($arguments= array()) {
    global $wpdb;
    global $videoid, $site_url;
    
    $configXML = $wpdb->get_row("SELECT configXML,width,height FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    if(isset($arguments['width']))
    {
      $width = $arguments['width'];
    }
    else
    {
       $width = $configXML->width;
    }
     if(isset($arguments['height']))
    {
      $height = $arguments['height'];
    }
    else
    {
       $height =  $configXML->height;
    }
  
    $output .= "\n" . '<div id="mediaspace"><span id="video' . $videoid . '" class="HDFLV">' . "\n";
    $output .= '<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</span>' . "\n";
    $output .= '<script type="text/javascript">' . "\n";
    $output .= 'var s' . $videoid . ' = new SWFObject("' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/hdplayer.swf' . '","n' . $videoid . '","' .$width. '","' .$height. '","7");' . "\n";
    $output .= 's' . $videoid . '.addParam("allowfullscreen","true");' . "\n";
    $output .= 's' . $videoid . '.addParam("allowscriptaccess","always");' . "\n";
    $output .= 's' . $videoid . '.addParam("wmode","opaque");' . "\n";
    $flashvars="baserefW=" . get_option('siteurl');


    if (isset($arguments['playlistid']) && isset($arguments['id'])) {
        $flashvars .="&pid=" . $arguments['playlistid'];
         $flashvars .="&vid=" . $arguments['id'];
        
    } elseif (isset($arguments['playlistid'])) {
        $flashvars .="&pid=" . $arguments['playlistid'];
    } else {
         $flashvars .="&vid=" . $arguments['id'];
    }
       if(isset($arguments['flashvars']))
    {
          $flashvars .= '&'.$arguments['flashvars'];

      
    }
    $output .= 's' . $videoid . '.addParam("FlashVars","' . $flashvars . '");' . "\n";
    $output .= 's' . $videoid . '.write("video' . $videoid . '");' . "\n";
    $output .= '</script></div>' . "\n";
    $videoid++;
	 //--------------------------------HTML5 START-------------------------------------------------------------//

        /* Error Msg for Video not supported to player. */

       $output .= '<script type="text/javascript">

            function failed(e) {
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
            {
            alert("Player doesnot support this video."); } }</script>';
        /* Player Div */
        $vid = $arguments['id'];
        $output .='<div id="player" style="display:none;height:100%">';
        $select = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare where vid='$vid'";
        $fetched = $wpdb->get_results($select);
        foreach ($fetched as $media) {
            $videourl = $media->file;
            $imgurl = $media->image;
        }
        /* if video is youtube. */
        if (preg_match("/www\.youtube\.com\/watch\?v=[^&]+/", $videourl, $vresult)) {
            $urlArray = split("=", $vresult[0]);
            $videoid = trim($urlArray[1]);
            $output .='<iframe  type="text/html" width="' . $configXML->width .'" height="' . $configXML->height . '" src="http://www.youtube.com/embed/' . $videoid . '" frameborder="0"></iframe>';
        }

        /* if video is uploaded or direct path. */ else {
            $output .='<video id="video" poster="' . $imgurl . '"   src="' . $videourl . '" width="' . $configXML->width .'" height="' . $configXML->height . '" autobuffer controls onerror="failed(event)">
     Html5 Not support This video Format.
</video>';
        }
        $output .='</div>';

        /* Player Div closed.
         * Script for checking platform.
         */

        $output .=' <script>
            txt =  navigator.platform ;

            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
            {
               document.getElementById("player").style.display = "block";
                document.getElementById("mediaspace").style.display = "none";

            }else{
                document.getElementById("player").style.display = "none";
                document.getElementById("mediaspace").style.display = "block";

            }
        </script>';
        //--------------------------------HTML5 End-------------------------------------------------------------//
    return $output;
}
add_shortcode('hdvideo', 'HDFLV_shareRender');


/*Adding page & options*/
function HDFLVShareAddPage() {
    add_media_page(__('hdflvvideoshare', 'hdflvvideoshare'), __('Contus VideoGallery', 'hdflvvideoshare'), 'edit_posts', 'hdflvvideoshare', 'show_Sharemenu');
    add_media_page(__('ads', 'vgads'), __('ADS VideoGallery', 'vgads'), 'edit_posts', 'vgads', 'show_Sharemenu');

    add_options_page('Contus GallerySettings', 'Contus GallerySettings', '8', 'hdflvvideoshare.php', 'FlashShareOptions');
}


function show_Sharemenu() {
    switch ($_GET['page']) {
        case 'hdflvvideoshare' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/manage.php');
            $MediaCenter = new HDFLVShareManage();
            break;
         case 'vgads' :

            include_once (dirname(__FILE__) . '/functions.php'); // admin functions
            include_once (dirname(__FILE__) . '/manageAds.php');
           $MediaCenter = new HDVIDEOManageAds();
            break;
    }
}


/*Function used to Edit player settings and generate settings form elements*/
function FlashShareOptions() {
    global $wpdb;
    global $site_url;
    $message = '';
    $g = array(0 => 'Properties');

    $options = get_option('HDFLVSettings');

    if ($_POST) {
        if(isset($_POST['feature'])) {
           $feature = $_POST['feature'];
        }
        if(isset($_POST['recent'])) {
           $recent =  $_POST['recent'];
        }
        if(isset($_POST['popular'])) {
            $popular = $_POST['popular'];
        }

// For the Player Setting checking whether the field is empty insert or else update

        $settings = $wpdb->get_col("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");

            if (count($settings) > 0) {
                echo  $_POST['autoplay'];
               $query = " UPDATE " . $wpdb->prefix . "hdflvvideoshare_settings SET
			autoplay= '" . $_POST['autoplay'] . "',playlist='" . $_POST['playlist'] . "',playlistauto='" . $_POST['playlistauto']
                    . "',buffer='" . $_POST['buffer'] . "',normalscale='" . $_POST['normalscale'] . "',fullscreenscale='" . $_POST['fullscreenscale'] . "'";
            if ($_FILES['logopath']["name"] != '') {
                $query .= ",logopath='" . $_FILES['logopath']["name"] . "'";
            }
                             $query .=",logo_target='" . $_POST['logotarget'] . "',volume='" . $_POST['volume'] . "',logoalign='" . $_POST['logoalign'] . "',hdflvplayer_ads='" . $_POST['hdflvplayer_ads']
                            . "',HD_default='" . $_POST['HD_default'] . "',download='" . $_POST['download'] . "',logoalpha='" . $_POST['logoalpha'] . "',skin_autohide='" . $_POST['skin_autohide']
                            . "',stagecolor='" . $_POST['stagecolor'] . "',skin='" . $_POST['skin'] . "',embed_visible='" . $_POST['embed_visible'] . "',shareURL='" . $_POST['shareURL']
                            . "',playlistXML='" . $_POST['playlistXML'] . "',debug='" . $_POST['debug'] . "',timer='" . $_POST['timer'] . "',zoom='" . $_POST['zoom']
                            . "',email='" . $_POST['email'] . "',fullscreen='" . $_POST['fullscreen'] . "',width='" . $_POST['width'] . "',height='" . $_POST['height']
                            . "',display_logo='" . $_POST['display_logo'] . "',uploads='" . $_POST['uploads']. "',license='" .$_POST['license']
                            . "',hideLogo='" . $_POST['hideLogo'] ."',keyApps ='" . $_POST['keyApps'] . "',preroll ='" . $_POST['preroll'] ."',postroll ='" . $_POST['postroll'] . "',feature='" . $_POST['feature'] . "',recent='" . $_POST['recent'] . "',popular='" . $_POST['popular']
                            . "',rowsFea='" . $_POST['rowsFea'] . "',colFea='" .$_POST['colFea'] . "',rowsRec='" . $_POST['rowsRec'] . "',colRec='" . $_POST['colRec']
                            . "',rowsPop='" . $_POST['rowsPop']. "',colPop='" . $_POST['colPop']. "',page='" . $_POST['page']. "',stylesheet='" . $_POST['stylesheet']
                            . "' WHERE settings_id = " . $settings[0]['settings_id'];
                             $updateSettings = $wpdb->query($query);
        } else {
            $insertSettings = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_settings
						VALUES (" . $_POST['autoplay'] . "," . $_POST['playlist'] . "," . $_POST['playlistauto'] . "," . $_POST['buffer']
                            . "," . $_POST['normalscale'] . "," . $_POST['fullscreenscale'] . "," . $_POST['logopath'] . "," . $_POST['logotarget']
                            . "," . $_POST['volume'] . "," . $_POST['logoalign'] . "," . $_POST['hdflvplayer_ads'] . "," . $_POST['HD_default']
                            . "," . $_POST['download'] . "," . $_POST['logoalpha'] . "," . $_POST['skin_autohide'] . "," . $_POST['stagecolor']
                            . "," . $_POST['skin'] . "," . $_POST['embed_visible'] . "," . $_POST['shareURL'] . "," . $_POST['playlistXML']
                            . "," . $_POST['uploads'] . "," . $_POST['debug'] . "," . $_POST['timer'] . "," . $_POST['zoom'] . "," . $_POST['email']
                            . "," . $_POST['fullscreen'] . "," . $_POST['width'] . "," . $_POST['height'] . "," . $_POST['display_logo'] . "," . $_POST['uploadurl'] . "," . $_POST['license']
                            . "," . $_POST['hideLogo'] . "," . $_POST['keyApps'] . "," . $_POST['preroll'] ."," . $_POST['postroll'] ."," . $_POST['feature'] . "," . $_POST['rowsFea'] . "," .$_POST['colFea']
                            . "," . $_POST['recent'] . "," . $_POST['rowsRec'] . "," . $_POST['colRec']
                            . "," . $_POST['popular'] . "," . $_POST['rowsPop'] . "," . $_POST['colPop']. "," . $_POST['page']. "," . $_POST['stylesheet'].")");
        }
        move_uploaded_file($_FILES["logopath"]["tmp_name"], "../wp-content/plugins/" . dirname(plugin_basename(__FILE__)) . "/hdflvplayer/images/" . $_FILES["logopath"]["name"]);
        $message = '<div class="updated"><p><strong>Options saved.</strong></p></div>';
   

     $langSettings = $wpdb->get_col("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_language");
      if (count($langSettings) > 0) {
          
                    $langsetUpdate ="UPDATE " . $wpdb->prefix . "hdflvvideoshare_language SET
	   play= '" . $_POST['play']. "',pause= '" . $_POST['pause']. "',hdison= '" . $_POST['hdison']. "',hdisoff= '" . $_POST['hdisoff']. "',lang_zoom= '" . $_POST['lang_zoom']. "'
           ,lang_share= '" . $_POST['lang_share']. "',lang_fullscreen= '" . $_POST['lang_fullscreen']. "',relatedvideos= '" . $_POST['relatedvideos']. "'
           ,sharetheword= '" . $_POST['sharetheword']. "',sendanemail= '" . $_POST['sendanemail']."' ,download= '" . $_POST['ldownload']. "' ,`to`= '" . $_POST['to']. "',`from`= '" . $_POST['from']. "',`note`= '" . $_POST['note']. "',`send`= '" . $_POST['send']. "',`copylink`= '" . $_POST['copylink']. "'
           ,`copyembed`= '" . $_POST['copyembed']. "',`facebook`= '" . $_POST['facebook']. "',reddit= '" . $_POST['reddit']. "',friendfeed= '" . $_POST['friendfeed']. "',slashdot= '" . $_POST['slashdot']. "'
           ,delicious= '" . $_POST['delicious']. "',myspace= '" . $_POST['myspace']. "',wong= '" . $_POST['wong']. "',digg= '" . $_POST['digg']. "',blinklist= '" . $_POST['blinklist']. "'
           ,bebo= '" . $_POST['bebo']. "',fark= '" . $_POST['fark']. "',tweet= '" . $_POST['tweet']. "',furl= '" . $_POST['furl']. "' WHERE lang_id=1";
           $langUpdated = $wpdb->query($langsetUpdate);


      } else {
         $langsetInsert = $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_language
             VALUES(" . $_POST['play'] . "," . $_POST['pause'] . "," . $_POST['hdison'] . "," . $_POST['hdisoff'] . "," . $_POST['lang_zoom'] .
                  "," . $_POST['lang_share'] . "," . $_POST['lang_fullscreen'] . "," . $_POST['relatedvideos'] ."," . $_POST['sharetheword']  . "," . $_POST['sendanemail'] ."," . $_POST['ldownload'] .
                  "," . $_POST['to'] . "," . $_POST['from'] . "," . $_POST['note'] ."," . $_POST['send']  . "," . $_POST['copylink'] .
                  "," . $_POST['copyembed'] . "," . $_POST['facebook'] . "," . $_POST['reddit'] ."," . $_POST['friendfeed']  . "," . $_POST['slashdot'] .
                  "," . $_POST['delicious'] . "," . $_POST['myspace'] . "," . $_POST['wong'] ."," . $_POST['digg'] ."," . $_POST['blinklist'] .
                  "," . $_POST['bebo'] ."," . $_POST['fark'] ."," . $_POST['tweet'] ."," . $_POST['furl'] .")");
         
      }

 }
// For the Language XML settings checking whether the field is empty insert or else update





    echo $message;

    $ski = str_replace('wp-admin', 'wp-content', dirname($_SERVER['SCRIPT_FILENAME'])) . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/hdflvplayer/skin';

    $skins = array();

    // Pull the directories listed in the skins folder to generate the dropdown list with valid skin files
    chdir($ski);
    if ($handle = opendir($ski)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($file)) {
                    $skins[] = $file;
                }
            }
        }
        closedir($handle);
    }

    $fetchSettings = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
    $fetchLanguage = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_language");
?>
<!--HTML design for admin settings -->
    <link rel="stylesheet" href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/jquery.ui.all.css'; ?>">

    <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery-1.4.4.js'; ?>"></script>
    <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.core.js'; ?>"></script>
    <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.widget.js'; ?>"></script>
    <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.mouse.js'; ?>"></script>
    <script src="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/js/jquery.ui.sortable.js'; ?>"></script>
    <style>
        .column { width: 500px; float: left; padding-bottom: 100px; }
        .portlet { margin: 0 1em 1em 0; }
        .portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 10px;padding-top: 4px;font-size:12px; }
        .portlet-header .ui-icon { float: right; }
        .portlet-content { padding: 0.4em; font-size:12px;}
        .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
        .ui-sortable-placeholder * { visibility: hidden; }
    </style>
    <script>
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

            $( ".column" ).disableSelection();
        });
    </script>

  <div class="wrap">
        <h2>Contus Video Gallery Settings</h2>
        <form method="post" enctype="multipart/form-data" action="options-general.php?page=hdflvvideoshare.php">
            <div><p style="float:left">Welcome to the Contus Video Gallery Settings plugin options menu! &nbsp;&nbsp; <a style="color:red;" href='<?php echo $site_url; ?>/wp-admin/upload.php?page=hdflvvideoshare'>Add Video</a></p>
            <p class='submit' style="float:left; padding-left: 350px"><input class='button-primary' type='submit' value='Update Options'></p></div>
            <div style="clear:both"></div>
            <div class="column">

                <div class="portlet">
                    <div class="portlet-header">Display Configuration</div>
                    <div class="portlet-content">
                        <table class="form-table">
                             <tr>
                                <th scope='row'>Auto Play</th>
                                <td><input type='checkbox' class='check' name="autoplay" <?php if ($fetchSettings->autoplay == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                             </tr>
                            <tr>
                                <th scope='row'>Player Width</th>
                                <td><input type='text' name="width" value="<?php echo $fetchSettings->width ?>" size=45  /></td>
                            </tr>
                            <tr>
                                <th scope='row'>Player Height</th>
                                <td><input type='text' name="height" value="<?php echo $fetchSettings->height ?>" size=45  /></td>
                            </tr>
                            <tr>
                                <th scope='row'>Stagecolor</th>
                                <td><input type='text' name="stagecolor" value="<?php echo $fetchSettings->stagecolor ?>" size=45  /></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="portlet">
                    <div class="portlet-header">Playlist Configuration</div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <tr>
                                <th scope='row'>Playlist</th>
                                <td><input type='checkbox' class='check' name="playlist" <?php if ($fetchSettings->playlist == 1) { ?> checked <?php } ?> value="1" size=45   /></td>
                                 
                            </tr>
                            <tr>
                                <th scope='row'>HD Default</th>
                                <td><input type='checkbox' class='check' name="HD_default" <?php if ($fetchSettings->HD_default == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                            </tr>
                            <tr>
                                <th scope='row'>Playlist Autoplay</th>
                                <td><input type='checkbox' class='check' <?php if ($fetchSettings->playlistauto == 1) { ?> checked <?php } ?> name="playlistauto" value="1" size=45  /></td>
                                
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="portlet">
                    <div class="portlet-header">License Configuration</div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <tr>
                                <th scope='row'>License Key</th>
                                <td><input type='text' name="license" value="<?php echo $fetchSettings->license ?>" size=45  /></td>
                             </tr>

                        </table>
                    </div>
                </div>

                 <div class="portlet">
                    <div class="portlet-header">App Key Facebook</div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <tr>
                                <th scope='row'>App Key</th>
                                <td><input type='text' name="keyApps" value="<?php echo $fetchSettings->keyApps ?>" size=45  /></td>
                             </tr>

                        </table>
                    </div>
                </div>
                  <div class="portlet">
                    <div class="portlet-header">Ads Settings</div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <!-- Preroll -->
                            <tr>
                                <th scope='row'>Preroll Ads</th>
                                <td>
                                    <input name="preroll" id="preroll" type='radio' value="0"  <?php if ($fetchSettings->preroll == 0) echo 'checked'; ?> />Enable
                                    <input name="preroll" id="preroll" type='radio' value="1"  <?php if ($fetchSettings->preroll == 1) echo 'checked'; ?> />Disable
                                </td>
                            </tr>
                            <!-- Postroll -->
                            <tr>
                                <th scope='row'>Postroll Ads</th>
                                <td>
                                    <input name="postroll" id="postroll" type='radio' value="0"  <?php if ($fetchSettings->postroll == 0) echo 'checked'; ?> />Enable
                                    <input name="postroll" id="postroll" type='radio' value="1"  <?php if ($fetchSettings->postroll == 1) echo 'checked'; ?> />Disable
                                </td>
                            </tr>

                    </table>
                </div>
            </div>

                <div class="portlet">
                <div class="portlet-header">Logo Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">
                        <tr>
                            <th scope='row'>Logo Path</th>
                            <td><input type='file' name="logopath" value="" size=40  /><?php echo $fetchSettings->logopath ?></td>
                        </tr>
                        <tr>
                            <th scope='row'>Logo Target</th>
                            <td><input type='text' name="logotarget" value="<?php echo $fetchSettings->logo_target ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Logo Align</th>
                            <td> <select name="logoalign" style="width:150px;">
                                    <option <?php if ($fetchSettings->logoalign == 'TL') { ?> selected="selected" <?php } ?> value="TL">Top Left</option>
                                    <option <?php if ($fetchSettings->logoalign == 'TR') { ?> selected="selected" <?php } ?> value="TR">Top Right</option>
                                    <option <?php if ($fetchSettings->logoalign == 'LB') { ?> selected="selected" <?php } ?> value="LB">Left Bottom</option>
                                    <option <?php if ($fetchSettings->logoalign == 'RB') { ?> selected="selected" <?php } ?> value="RB">Right Bottom</option>
                                </select></td>
                        </tr>
                         <tr>
                                  <th scope='row'>Logo Alpha</th>
                                  <td><input type='text' name="logoalpha" value="<?php echo $fetchSettings->logoalpha ?>" size=45  /></td>
                              </tr>
                                <tr>
                                <th scope='row'>Hide YouTube Logo</th>
                                <td><input type='checkbox' class='check' <?php if ($fetchSettings->hideLogo == true) { ?> checked <?php } ?> name="hideLogo" value="true" size=45  /></td>
                            </tr>
                    </table>
                </div>
            </div>
                 <div class="portlet">
                <div class="portlet-header">StyleSheet Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">


    <tr><td>
        <input type='radio' name="stylesheet"  value="default" <?php if( $fetchSettings->stylesheet == 'default') echo 'checked'; ?> />Current Theme
        <input  type='radio' name="stylesheet"  value="contus"  <?php if( $fetchSettings->stylesheet == 'contus') echo 'checked'; ?> />Custom Theme</td></tr>

                    </table>
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-header">Video Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">

                        <tr>
                            <th scope='row'>Download</th>
                            <td><input type='checkbox' class='check' name="download" <?php if ($fetchSettings->download == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Buffer</th>
                            <td><input type='text' name="buffer" value="<?php echo $fetchSettings->buffer ?>" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Volume</th>
                            <td><input type='text' name="volume" value="<?php echo $fetchSettings->volume ?>" size=45  /></td>
                        </tr>
                    </table>
                </div>
            </div>

                           <div class="portlet">
                    <div class="portlet-header">General Settings</div>
                    <div class="portlet-content">
                        <table class="form-table">
                            <tr>
                                <th scope='row'>Normal Scale</th>
                                <td>
                                    <select name="normalscale" style="width:150px;">
                                        <option value="0" <?php if ($fetchSettings->normalscale == 0) { ?> selected="selected" <?php } ?> >Aspect Ratio</option>
                                        <option value="1" <?php if ($fetchSettings->normalscale == 1) { ?> selected="selected" <?php } ?>>Original Screen</option>
                                        <option value="2" <?php if ($fetchSettings->normalscale == 2) { ?> selected="selected" <?php } ?>>Fit To Screen</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope='row'>Full Screen Scale</th>
                                <td>
                                    <select name="fullscreenscale" style="width:150px;">
                                        <option value="0" <?php if ($fetchSettings->fullscreenscale == 0) { ?> selected="selected" <?php } ?>>Aspect Ratio</option>
                                        <option value="1" <?php if ($fetchSettings->fullscreenscale == 1) { ?> selected="selected" <?php } ?>>Original Screen</option>
                                        <option value="2" <?php if ($fetchSettings->fullscreenscale == 2) { ?> selected="selected" <?php } ?>>Fit To Screen</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope='row'>Uploads</th>
                                <td>
                <!--                    <label><input name="usedefault" type='radio' value="1" <?php if ($setting['v'] == 1)
        echo 'checked'; ?> /> <?php _e('Standard upload folder : ', 'hdflvvideoshare') ?></label><code><?php echo get_option('upload_path'); ?></code><br />-->
                                <label><input name="usedefault" type='radio' value="0"  <?php if ($setting['v'] == 0)
                                    echo 'checked'; ?> /> <?php _e('Store uploads in this folder : ', 'hdflvvideoshare') ?></label>
                                <input type="text" size="50" maxlength="200" name='uploads' value="<?php echo $fetchSettings->uploads; ?>" />
                            </td>
                        </tr>
                         <tr>
                <th scope='row'>Embed Visible</th>
                <td><input type='checkbox' class='check' <?php if ($fetchSettings->embed_visible == 1) { ?> checked <?php } ?> name="embed_visible" value="1" size=45  /></td>
            </tr>
            <tr>
                <th scope='row'>Debug</th>
                <td><input type='checkbox' class='check' <?php if ($fetchSettings->debug == 1) { ?> checked <?php } ?> name="debug" value="1" size=45  /></td>
            </tr>

                    </table>
                </div>
            </div>


            <div class="portlet">
                <div class="portlet-header">Skin Configuration</div>
                <div class="portlet-content">
                    <table class="form-table">
                          <tr>
                <th scope='row'>Skin</th>
                <td>
                    <select name="skin" style="width:150px;">
                        <?php foreach ($skins as $skin) {
                        ?>
                                                  <option <?php if ($fetchSettings->skin == $skin) {
                        ?> selected="selected" <?php } ?> value="<?php echo $skin; ?>"><?php echo $skin; ?></option>
                            <?php } ?>
                                      </select>
                                  </td>
                              </tr>
                        <tr>
                            <th scope='row'>Timer</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->timer == 1) { ?> checked <?php } ?> name="timer" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Zoom</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->zoom == 1) { ?> checked <?php } ?> name="zoom" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Share</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->email == 1) { ?> checked <?php } ?> name="email" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Full Screen</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->fullscreen == 1) { ?> checked <?php } ?> name="fullscreen" value="1" size=45  /></td>
                        </tr>
                        <tr>
                            <th scope='row'>Skin Autohide</th>
                            <td><input type='checkbox' class='check' <?php if ($fetchSettings->skin_autohide == 1) { ?> checked <?php } ?> name="skin_autohide" value="1" size=45  /></td>
                        </tr>
                    </table>
                </div>
            </div>






        </div>
        <div class="column">


            <div class="portlet">
                <div class="portlet-header">Home Page Settings</div>
                <div class="portlet-content">
                    <table class="form-table">

<!-- Popular Videos-->
<tr>

    <th>Popular Videos</th>
    <td><input  type='radio' name="popular"  value="on" <?php if( $fetchSettings->popular == on) echo 'checked'; ?> />Enable
<input type='radio' name="popular"  value="off"  <?php if( $fetchSettings->popular == off) echo 'checked'; ?> />Disable<br></td></tr>

     <tr><th>Rows<input type="text" name="rowsPop" id="rowsPop" size="10" value="<?php echo $fetchSettings->rowsPop; ?>"></th>
     <td>Columns <input type="text" name="colPop" id="colPop" size="10" value="<?php echo $fetchSettings->colPop; ?>"></td>
     </tr>

<!-- Recent Videos-->
<tr>
    <th>Recent Videos</th>
    <td><input type='radio' name="recent"  value="on" <?php if($fetchSettings->recent == on) echo 'checked'; ?> />Enable
     <input type='radio' name="recent"  value="off"  <?php if($fetchSettings->recent == off) echo 'checked'; ?> />Disable<br></td></tr>

 <tr>
     <th>Rows<input type="text" name="rowsRec" id="rowsRec" size="10" value="<?php echo $fetchSettings->rowsRec; ?>"></th>
     <td>Columns <input type="text" name="colRec" id="colRec" size="10" value="<?php echo $fetchSettings->colRec; ?>">
     </td>
     </tr>

<!-- Feature Videos  -->
<tr>
    <th>Feature Videos</th>
    <td><input type='radio' name="feature"  value="on" <?php if( $fetchSettings->feature == on) echo 'checked'; ?> />Enable
    <input  type='radio' name="feature"  value="off"  <?php if( $fetchSettings->feature == off) echo 'checked'; ?> />Disable<br></td></tr>

    <tr><th>Rows<input type="text" name="rowsFea" id="rowsFea" size="10" value="<?php echo $fetchSettings->rowsFea; ?>"></th>
        <td>Columns<input type="text" name="colFea" id="colFea" size="10" value="<?php echo $fetchSettings->colFea; ?>">
     </td>
     </tr>

      <tr><th>No. videos in More page</th>
     <td><input type="text" name="page" id="page" value="<?php echo $fetchSettings->page; ?>"></td>
     </tr>
                    </table>
                </div>
            </div>

            <!-- Language XML -->
            <div class="portlet">
                <div class="portlet-header">Language Settings</div>
                <div class="portlet-content">
                    <table class="form-table">
                <tr>
                <th scope='row'>Play</th>
                <td><input type='text' class='text' name="play"  value="<?php echo $fetchLanguage->play;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Pause</th>
                <td><input type='text' class='text' name="pause"  value="<?php echo $fetchLanguage->pause;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>HD is On</th>
                <td><input type='text' class='text' name="hdison"  value="<?php echo $fetchLanguage->hdison;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>HD is Off</th>
                <td><input type='text' class='text' name="hdisoff"  value="<?php echo $fetchLanguage->hdisoff;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Zoom</th>
                <td><input type='text' class='text' name="lang_zoom"  value="<?php echo $fetchLanguage->lang_zoom;?>"  size=25  /></td>
                </tr>


                <tr>
                <th scope='row'>Share</th>
                <td><input type='text' class='text' name="lang_share"  value="<?php echo $fetchLanguage->lang_share;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Fullscreen</th>
                <td><input type='text' class='text' name="lang_fullscreen"  value="<?php echo $fetchLanguage->lang_fullscreen;?>"  size=25  /></td>
                </tr>
                 <tr>
                <th scope='row'>Related Video</th>
                <td><input type='text' class='text' name="relatedvideos"  value="<?php echo $fetchLanguage->relatedvideos;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Share the Word</th>
                <td><input type='text' class='text' name="sharetheword"  value="<?php echo $fetchLanguage->sharetheword;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Send an Email</th>
                <td><input type='text' class='text' name="sendanemail"  value="<?php echo $fetchLanguage->sendanemail;?>"  size=25  /></td>
                </tr>
                 <tr>
                <th scope='row'>Download</th>
                <td><input type='text' class='text' name="ldownload"  value="<?php echo $fetchLanguage->download;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>To</th>
                <td><input type='text' class='text' name="to"  value="<?php echo $fetchLanguage->to;?>"  size=25  /></td>
                </tr>


                <tr>
                <th scope='row'>From</th>
                <td><input type='text' class='text' name="from"  value="<?php echo $fetchLanguage->from;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Note (Optional)</th>
                <td><input type='text' class='text' name="note"  value="<?php echo $fetchLanguage->note;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Send</th>
                <td><input type='text' class='text' name="send"  value="<?php echo $fetchLanguage->send;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Copy Link</th>
                <td><input type='text' class='text' name="copylink"  value="<?php echo $fetchLanguage->copylink;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>Copy Embedcode</th>
                <td><input type='text' class='text' name="copyembed"  value="<?php echo $fetchLanguage->copyembed;?>"  size=25  /></td>
                </tr>

                

                <tr>
                <th scope='row'>Facebook</th>
                <td><input type='text' class='text' name="facebook"  value="<?php echo $fetchLanguage->facebook;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>reddit</th>
                <td><input type='text' class='text' name="reddit"  value="<?php echo $fetchLanguage->reddit;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>friendfeed</th>
                <td><input type='text' class='text' name="friendfeed"  value="<?php echo $fetchLanguage->friendfeed;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>slashdot</th>
                <td><input type='text' class='text' name="slashdot"  value="<?php echo $fetchLanguage->slashdot;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>delicious</th>
                <td><input type='text' class='text' name="delicious"  value="<?php echo $fetchLanguage->delicious;?>"  size=25  /></td>
                </tr>



                <tr>
                <th scope='row'>myspace</th>
                <td><input type='text' class='text' name="myspace"  value="<?php echo $fetchLanguage->myspace;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>wong</th>
                <td><input type='text' class='text' name="wong"  value="<?php echo $fetchLanguage->wong;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>digg</th>
                <td><input type='text' class='text' name="digg"  value="<?php echo $fetchLanguage->digg;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>blinklist</th>
                <td><input type='text' class='text' name="blinklist"  value="<?php echo $fetchLanguage->blinklist;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>bebo</th>
                <td><input type='text' class='text' name="bebo"  value="<?php echo $fetchLanguage->bebo;?>"  size=25  /></td>
                </tr>

                <tr>
                <th scope='row'>fark</th>
                <td><input type='text' class='text' name="fark"  value="<?php echo $fetchLanguage->fark;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>tweet</th>
                <td><input type='text' class='text' name="tweet"  value="<?php echo $fetchLanguage->tweet;?>"  size=25  /></td>
                </tr>
                <tr>
                <th scope='row'>furl</th>
                <td><input type='text' class='text' name="furl"  value="<?php echo $fetchLanguage->furl;?>"  size=25  /></td>
                </tr>
                </table>
                </div>
            </div>
            <!-- End of Language XML -->

        </div>
             <p class='submit' style="float:left; padding-left: 350px"><input class='button-primary' type='submit' value='Update Options'></p></div>
            <div class="clear"></div>
 </form>
       </div>



<?php
}

function Hdflv_Sharehead() {
    global $site_url;
    echo '<script type="text/javascript" src="' . $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/swfobject.js"></script>' . "\n";
}

add_action('wp_head', 'Hdflv_Sharehead');

/*Loading default settings of player*/
function HdflvloadSharedefaults() {
    global $wpdb;
    $insertSettings = $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_settings
		      VALUES (1,0,0,0,1,1,1,'platoon.jpg','http://www.hdflvplayer.net/',50,'LR',1,1,0,20,1,'0x000000',
                      'skin_black',0,'hdflvplayer/videourl.php','playXml',1,1,1,1,1,630,400,1,0,'wp-content/uploads','',
                      'true','',0,0,'on', '3', '3', 'on', '3', '3', 'on', '3', '3', '3' ,'contus')");
    $insertLanguage = $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_language
						VALUES (1,'play','pause','HD is On','HD is Off','Zoom','Share','Fullscreen','Related Videos',
                                                'Share the Word','Send an Email',Download,'To','From','Note (Optional)','Send','Copy Link',
                                                'Copy Embedcode','Facebook','reddit','friendfeed','slashdot','delicious','myspace','wong',
                                                'digg','blinklist','bebo', 'fark','tweet','furl')");

}


/*Function to uninstall player plugin*/
function hdflv_Sharedeinstall() {
    global $wpdb, $wp_version;

    $hd_table = $wpdb->prefix . 'hdflvvideoshare';
    $hd_table_mp = $wpdb->prefix . 'hdflvvideoshare_med2play';
    $hd_table_pl = $wpdb->prefix . 'hdflvvideoshare_playlist';
    $hd_table_set = $wpdb->prefix . 'hdflvvideoshare_settings';
    $hd_table_lang = $wpdb->prefix . 'hdflvvideoshare_language';

}

/*Function to invoke install player plugin*/
function hd_ShareInstall() {

    require_once(dirname(__FILE__) . '/install.php');
    hdflv_install();
}

/*Function to activate player plugin*/
function hdflv_Shareactivate() {
    HdflvloadSharedefaults();
    //update_option('HDFLVSettings', HdflvloadSharedefaults());
}

register_activation_hook(plugin_basename(dirname(__FILE__)) . '/hdflvvideoshare.php', 'hd_ShareInstall');
register_activation_hook(__FILE__, 'hdflv_Shareactivate');
register_uninstall_hook(__FILE__, 'hdflv_Sharedeinstall');


/*Function to deactivate player plugin*/
function hdflv_Sharedeactivate() {
    global $wpdb;
     delete_option('HDFLVSettings');
    $homeDel      =  "DELETE FROM ".$wpdb->prefix."posts WHERE post_content='[contusHome]'";
    $homeDelete    =  $wpdb->get_results($homeDel);
}

register_deactivation_hook(__FILE__, 'hdflv_Sharedeactivate');

// CONTENT FILTER

add_filter('the_content', 'HDFLV_ShareParse');

// OPTIONS MENU

add_action('admin_menu', 'HDFLVShareAddPage');
?>