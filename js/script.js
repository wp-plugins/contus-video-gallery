/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: VIdeo Gallery plugin script file.
Version: 2.3.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

function current_video(vid,title){

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
 //alert(xmlhttp);
  }
else
  {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
    }
  }

xmlhttp.open("GET",baseurl+"/wp-content/plugins/"+folder+"/hitCount.php?vid="+vid,true);
xmlhttp.send();
}
 