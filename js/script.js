/** 
 * Video Gallery plugin script file..
 * @category   Apptha
 * @package    Contus video Gallery
 * @version    2.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html 
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
    xmlhttp.open("GET",baseurl+"/wp-admin/admin-ajax.php?action=videohitcount&vid="+vid,true);
    xmlhttp.send();
}

function enableEmbed(){
embedFlag = document.getElementById("flagembed").value;
document.getElementById("report_video_response").style.display = "none";
if(embedFlag != 1){
    document.getElementById("embedcode").style.display = "block";
    document.getElementById("reportform").style.display = "none";
    document.getElementById('iframe-content').style.display="none";	
    document.getElementById("flagembed").value = "1";
    document.getElementById("reportvideo").value = "0";
	document.getElementById('iframeflag').value="0";		


} else{
    document.getElementById("embedcode").style.display = "none";
    document.getElementById("flagembed").value = "0";
}
}

function reportVideo(){
	var reportVideoFlag = document.getElementById("reportvideo").value;
	document.getElementById("report_video_response").style.display = "none";
	if(reportVideoFlag != 1){
	    document.getElementById("reportform").style.display = "block";
	    document.getElementById("embedcode").style.display = "none";
	    document.getElementById('iframe-content').style.display="none";	
	    document.getElementById("reportvideo").value = "1";
	    document.getElementById("flagembed").value = "0";
		document.getElementById('iframeflag').value="0";		

	} else{
	    document.getElementById("reportform").style.display = "none";
	    document.getElementById("reportvideo").value = "0";
	}
}
function view_iframe_code(){
	var iframeFlag = document.getElementById('iframeflag').value;
	document.getElementById("report_video_response").style.display = "none";
	if(iframeFlag !=1){
		document.getElementById('iframe-content').style.display="block";
	    document.getElementById("reportform").style.display = "none";
	    document.getElementById("embedcode").style.display = "none";
	    document.getElementById("flagembed").value = "0";
	    document.getElementById("reportvideo").value = "0";
		document.getElementById('iframeflag').value="1";		
	}else{
		document.getElementById('iframe-content').style.display="none";	
		document.getElementById('iframeflag').value="0";	
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
function reportVideoSend(){
	var xmlhttp;
	var reporttype  = document.forms["reportform"]["reportvideotype"].value ;
	var reportvideo = document.forms["reportform"]["admin_email"].value ;
	var reporter_email = document.forms["reportform"]["reporter_email"].value ;
	var video_title = document.forms["reportform"]["video_title"].value ;
	var redirect_url = document.forms["reportform"]["redirect_url"].value ;
	if(reporttype=='') {
    	document.getElementById("report_video_response").style.display = "block";
    	document.getElementById('reportform_ajax_loader').style.display="none";
		document.getElementById('report_video_response').innerHTML= "Choose report type.";
		return false;
	}
	if(reporter_email =='') {
    	document.getElementById("report_video_response").style.display = "block";
    	document.getElementById('reportform_ajax_loader').style.display="none";
		document.getElementById('report_video_response').innerHTML= "Login to Report the Video.";
		return false;
	}
    document.getElementById('reportform_ajax_loader').style.display="block";
	var ajaxURL =  baseurl+"/wp-admin/admin-ajax.php?action=reportvideo&reporttype="+reporttype+"&admin_email="+reportvideo+"&reporter_email="+reporter_email+"&video_title="+video_title+"&redirect_url="+redirect_url;
	
	if (window.XMLHttpRequest)
	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {   document.getElementById('reportform').style.display="none";
	        document.getElementById('reportform_ajax_loader').style.display="none";
	    	document.getElementById("report_video_response").style.display = "block";
	        document.getElementById("report_video_response").style.padding = "5px";
		    if(xmlhttp.responseText == 'fail') { 
		    	document.getElementById('report_video_response').innerHTML= "Login to Report the Video";
		    } else {
		    	document.getElementById('report_video_response').innerHTML= "Thank you for submitting your report.";
		    }
	    }
	}
	xmlhttp.open("GET",ajaxURL,true);
	xmlhttp.send();
}
// Function  for cancel button  action  in report video.
function hideReportForm()
{   
	document.getElementById('reportform').style.display="none";
    document.getElementById("reportvideo").value = "0";
    document.getElementById('reportform_ajax_loader').style.display="none";
    document.getElementById('report_video_response').style.display="none";
}