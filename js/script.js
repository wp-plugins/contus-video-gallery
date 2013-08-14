/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: VIdeo Gallery plugin script file.
Version: 2.2
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

function currentvideo(title,vid){

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
        //alert('i am ready');
    }
  }

xmlhttp.open("GET",baseurl+"/wp-content/plugins/"+folder+"/hitCount.php?vid="+vid,true);
xmlhttp.send();
document.getElementById('video_title').innerHTML=title;
 }
 