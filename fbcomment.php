<?php
/*
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
Description: Contus Videos Share with the standard system of wordpress.
Edited By: Saranya
Version: 1.0
wp-content\plugins\contus-hd-flv-player\themes\default\fbcomment.php
Date : 29/3/2011
*/
require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid = $_REQUEST['vid'];
$vname =$_REQUEST['vname'];
$site_url = $_REQUEST['siturl'];
$dirPage     = $_REQUEST['folder'];
$vtagid = $_REQUEST['vtagid'];

if($vid != '') {
$div .= '<div id="fbcomments"><fb:comments numposts="10" href="'.$site_url.'/contusvideo/?vid='.$vid.'" width="550"
    title="'.$vname.'" publish_feed="true"></fb:comments></div>';
echo  $div;
exit;
}
if($vtagid != '')
{
$tagDel = mysql_query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_tags  WHERE vtag_id = '$vtagid'");
}
?>
