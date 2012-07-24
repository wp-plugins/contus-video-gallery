<?php
/**
 * @name          : Wordpress VideoGallery.
 * @version	  	  : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Facebook comments page
 * @Creation Date : Feb 21, 2011
 * @Modified Date : Jul 19, 2012
 * */

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $wpdb;
$vid = $_REQUEST['vid'];
$vname =$_REQUEST['vname'];
$site_url = $_REQUEST['siturl'];
$dirPage     = $_REQUEST['folder'];
$vtagid = $_REQUEST['vtagid'];

if($vid != '') {
$div .= '<div id="fbcomments"><fb:comments numposts="10" css="'.$site_url.'/wp-content/plugins/'.$dirPage.'/css/fb_comments.css" width="550" xid="'.$vid.'"
    title="'.$vname.'" publish_feed="true"></fb:comments></div>';
echo  $div;
exit;
}
if($vtagid != '')
{
$tagDel = mysql_query("DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_tags  WHERE vtag_id = '$vtagid'");
}
?>
