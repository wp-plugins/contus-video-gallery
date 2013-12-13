/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: VIdeo Gallery plugin script file.
Version: 2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

function current_video(vid,title){
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
       xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4) {
        }
    }
    xmlhttp.open("GET",baseurl+"/wp-admin/admin-ajax.php?action=videohitCount&vid="+vid,true);
    xmlhttp.send();
}
 
function enableEmbed(){
embedFlag = document.getElementById("flagembed").value;
if(embedFlag !== 1){
    document.getElementById("embedcode").style.display = "block";
    document.getElementById("flagembed").value = "1";
} else{
    document.getElementById("embedcode").style.display = "none";
    document.getElementById("flagembed").value = "0";
}
}

function videogallery_change_player(embedcode,id,player_div,file_type,vid,title){ 
    if(file_type === 5){
        current_video(vid,''); 
    }
    document.getElementById("mediaspace"+id).innerHTML = "";
    document.getElementById(player_div+id).innerHTML = embedcode;
    document.getElementById(player_div+id).focus();
    document.getElementById("video_title"+id).innerHTML=title;
}