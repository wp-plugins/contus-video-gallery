/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Admin action javacript file.
Version: 2.3.1.0.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

function checkingarray(checkname) //for giving the coma seprerated  selcted checkbox id's
{

    checkarr=document.getElementsByName(checkname);
    checkarray="";
    if(checkarr.length>0)
    {
        for(i=0;i<checkarr.length;i++)
        {

            if(checkarr[i].checked)
            {
                checkarray+=checkarr[i].value+",";
            }
        }
        checkarray = checkarray.substring(0, checkarray.length-1);
        return checkarray;
    }
    else
    {
        return false
    }
}
function PlaylistdeleteIds()
{
    playlistactiondown = document.getElementById("playlistactiondown").value;
    playlistactionup = document.getElementById("playlistactionup").value;
    playlistID = checkingarray('pid[]');
    if(playlistactiondown == "playlistdelete" || playlistactionup == "playlistdelete")
    {
        if(playlistID)
        {
            alert("You want to delete Category? ");
            return true;
        }
        else
        {
            alert("Please select a Category to delete");
            return false;
        }
    }
    else
    {
        alert("Please select an action");
    }
    return false;
}

function clear_upload(){
       document.getElementById("normalvideoform-value").value = '';
    }

function Videoadtype(adtype)
{
    if(adtype=="prepostroll")
    {
       document.getElementById('admethod').value = "prepost";
       document.getElementById('videoadmethod').style.display = "block";
       document.getElementById('videoaddetails').style.display = "block";
       document.getElementById('adimpresurl').style.display = "block";
        document.getElementById('adclickurl').style.display = "block";
        document.getElementById('adtargeturl').style.display = "block";
        document.getElementById('addescription').style.display = "block";
        document.getElementById('adtitle').style.display = "block";
       document.getElementById('videoimaaddetails').style.display = "none";
    }

    if(adtype=="midroll")
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadmethod').style.display = "none";
        document.getElementById('admethod').value = "midroll";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('videoaddetails').style.display = "block";
        document.getElementById('adimpresurl').style.display = "block";
        document.getElementById('adclickurl').style.display = "block";
        document.getElementById('adtargeturl').style.display = "block";
        document.getElementById('addescription').style.display = "block";
        document.getElementById('adtitle').style.display = "block";
        document.getElementById('videoimaaddetails').style.display = "none";
    }
   else if(adtype=="imaad")
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadmethod').style.display = "none";
        document.getElementById('admethod').value = "imaad";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('videoaddetails').style.display = "block";
        document.getElementById('videoimaaddetails').style.display = "block";
        document.getElementById('adimpresurl').style.display = "none";
        document.getElementById('adclickurl').style.display = "none";
        document.getElementById('adtargeturl').style.display = "none";
        document.getElementById('addescription').style.display = "none";
        document.getElementById('adtitle').style.display = "";
        document.getElementById('imaadTypevideo').checked=true;
        changeimaadtype('videoad');
    }


}
function Videoadtypemethod(adtype)
{ 
     if(adtype=="fileuplo")
    { 
        document.getElementById('upload2').style.display = "block";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('adtype').style.display = "file";
    }

    else if(adtype=="urlad")
    { 
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadurl').style.display = "block";
        document.getElementById('adtype').value  = "url";
    }


}
function changeimaadtype(adtype)
{
     if(adtype=="textad")
    {
        document.getElementById('adimapath').style.display = "none";
        document.getElementById('adimawidth').style.display = "";
        document.getElementById('adimaheight').style.display = "";
        document.getElementById('adimapublisher').style.display = "";
        document.getElementById('adimacontentid').style.display = "";
        document.getElementById('adimachannels').style.display = "";
         document.getElementById('imaadTypetext').checked=true;
    }

    else if(adtype=="videoad")
    {
        document.getElementById('adimapath').style.display = "";
        document.getElementById('adimawidth').style.display = "none";
        document.getElementById('adimaheight').style.display = "none";
        document.getElementById('adimapublisher').style.display = "none";
        document.getElementById('adimacontentid').style.display = "none";
        document.getElementById('adimachannels').style.display = "none";
         document.getElementById('imaadTypevideo').checked=true;
    }
}

function validateadInput (){
    var tomatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
        if(document.getElementById('targeturl').value != ''){
                var thevideoadurl=document.getElementById("targeturl").value;
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('targeterrormessage').innerHTML = 'Enter Valid Target URL';
                    document.getElementById("targeturl").focus();
                    return false;
                }
        }
        if(document.getElementById('clickurl').value != ''){
                var thevideoadurl=document.getElementById("clickurl").value;
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('clickerrormessage').innerHTML = 'Enter Valid Target URL';
                    document.getElementById("clickurl").focus();
                    return false;
                }
        }
        if(document.getElementById('impressionurl').value != ''){
                var thevideoadurl=document.getElementById("impressionurl").value;
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('impressionerrormessage').innerHTML = 'Enter Valid Target URL';
                    document.getElementById("impressionurl").focus();
                    return false;
                }
        }
    
    if(document.getElementById('prepostroll').checked==true)
    {
        if(document.getElementById('filebtn').checked==true && document.getElementById('normalvideoform-value').value == '')
    {
        document.getElementById('filepathuploaderrormessage').innerHTML = 'Upload file for Ad';
        return false;
        }else if(document.getElementById('urlbtn').checked==true )
    {
            if(document.getElementById('videoadfilepath').value == ''){
        document.getElementById('filepatherrormessage').innerHTML = 'Enter Ad URL';
        document.getElementById('videoadfilepath').focus();
        return false;
            }else{
                var thevideoadurl=document.getElementById("videoadfilepath").value;
                var tomatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('filepatherrormessage').innerHTML = 'Enter Valid Ad URL';
                    document.getElementById("videoadfilepath").focus();
                    return false;
    }
            }
        }
    if(document.getElementById('name').value == ''){
        document.getElementById('nameerrormessage').innerHTML = 'Enter Ad Name';
        document.getElementById('name').focus();
        return false;
    }
    } else if(document.getElementById('name').value == ''){
        document.getElementById('nameerrormessage').innerHTML = 'Enter Ad Name';
        document.getElementById('name').focus();
        return false;

    } 
    if(document.getElementById('imaad').checked==true){
    if(document.getElementById('imaadTypetext').checked==true && document.getElementById('publisherId').value == '')
    {
        document.getElementById('imapublisherIderrormessage').innerHTML = 'Enter IMA Ad Publisher ID';
        document.getElementById('publisherId').focus();
        return false;

    } else if(document.getElementById('imaadTypetext').checked==true && document.getElementById('contentId').value == '')
    {
        document.getElementById('imacontentIderrormessage').innerHTML = 'Enter IMA Ad Content ID';
        document.getElementById('contentId').focus();
        return false;

    }else if(document.getElementById('imaadTypetext').checked==true && document.getElementById('channels').value == '')
    {
        document.getElementById('imachannelserrormessage').innerHTML = 'Enter IMA Ad Channel';
        document.getElementById('channels').focus();
        return false;

    }else {
        if(document.getElementById('imaadTypevideo').checked==true)
    {
        if(document.getElementById('imaadpath').value == ''){
        document.getElementById('imaadpatherrormessage').innerHTML = 'Enter IMA Ad Path';
        document.getElementById('imaadpath').focus();
        return false;
        } else{
                var thevideoadurl=document.getElementById("imaadpath").value;
                var tomatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('imaadpatherrormessage').innerHTML = 'Enter Valid IMA Ad URL';
                    document.getElementById("imaadpath").focus();
                    return false;
    }
            }

    }
    }
    
}
}





function VideoaddeleteIds()
 
{
    videoadactiondown = document.getElementById("videoadactiondown").value;
    videoadactionup = document.getElementById("videoadactionup").value;
    videoadID = checkingarray('videoad_id[]');
    if(videoadactiondown == "videoaddelete" || videoadactionup == "videoaddelete")
    {
        if(videoadID)
        {
            alert("Do you want to delete Video ad? ");
            return true;
        }
        else
        {
            alert("Please select a Video ad to delete");
            return false;
        }
    }
    else
    {
        alert("Please select an action");
    }
    return false;
}



function VideodeleteIds()

{
    videoactiondown = document.getElementById("videoactiondown").value;
    videoactionup = document.getElementById("videoactionup").value;
    videoID = checkingarray('video_id[]');
    if(videoactiondown == "videodelete" || videoactionup == "videodelete")
    {
        if(videoID)
        {
            alert("You want to delete Video? ");
            return true;
        }
        else
        {
            alert("Please select a Video to delete ");
            return false;
        }
    }
    else
    {
        alert("Please select an action");
    }
    return false;
}






function Videotype()
{
    if(document.getElementById('uploadbtn').checked==true)
    {
        document.getElementById('videoupload').style.display = "block";
        document.getElementById('videoyoutube').style.display = "none";
        document.getElementById('videourl').style.display = "none";
        document.getElementById('videoffmpeg').style.display = "none";
    }
    if(document.getElementById('youtubebtn').checked==true)
    {
        document.getElementById('videoupload').style.display = "none";
        document.getElementById('videoyoutube').style.display = "block";
        document.getElementById('videourl').style.display = "none";
        document.getElementById('videoffmpeg').style.display = "none";
    }
    if(document.getElementById('ffmpegbtn').checked==true)
    {
        document.getElementById('videoupload').style.display = "none";
        document.getElementById('videoyoutube').style.display = "none";
        document.getElementById('videourl').style.display = "none";
        document.getElementById('videoffmpeg').style.display = "block";
    }
    if(document.getElementById('urlbtn').checked==true)
    {
        document.getElementById('videoupload').style.display = "none";
        document.getElementById('videoyoutube').style.display = "none";
        document.getElementById('videourl').style.display = "block";
        document.getElementById('videoffmpeg').style.display = "none";
    }
}

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
        if(extn != 'jpg' && extn != 'png' && extn != 'jpeg' )
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
    document.forms[form_handle].action = "../wp-content/plugins/"+folder+"/admin/ajax/videoupload.php?processing=1";
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
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/'+folder+'/images/empty.gif';
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
            break;

        case "Uploading":
            document.getElementById(divprefix + "-upload-form").style.display = "none";
            document.getElementById(divprefix + "-upload-progress").style.display = "";
            document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading";
            document.getElementById(divprefix + "-upload-message").style.display = "none";
            document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/'+folder+'/images/loader.gif';
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
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/'+folder+'/images/success.gif';
            document.getElementById(divprefix + "-upload-status").innerHTML = "";
            document.getElementById(divprefix + "-upload-message").style.display = "";
            document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
            document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage;
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
            break;


        default:
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/'+folder+'/images/error.gif';
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
                    
            return true;
        }
        if(document.getElementById('btn3').checked || document.getElementById('btn4').checked)
        {
            document.getElementById('customurl1').value = document.getElementById('filepath2').value;
            document.getElementById('customhd1').value = document.getElementById('filepath3').value;
            document.getElementById('customimage').value = document.getElementById('filepath4').value;
            document.getElementById('custompreimage').value = document.getElementById('filepath5').value;
            return true;
        }
    }else {
        alert("Wait for Uploading to Finish");
        return false;
    }

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
function generate12(str1)
{
    var re= /http:\/\/www\.youtube[^"]+/;
    if(re.test(str1))
        document.getElementById('generate').style.visibility = "visible";
    else document.getElementById('generate').style.visibility  = "hidden";

}

function validateInput(){

    document.getElementById('Youtubeurlmessage').innerHTML = '';
    if(document.getElementById('btn2').checked == true){
        if(document.getElementById('filepath1').value == ''){
        document.getElementById('Youtubeurlmessage').innerHTML = 'Enter Youtube URL';
        document.getElementById('filepath1').focus();
        return false;
        } else {
            var theurl=document.getElementById("filepath1").value;
            var regExp = /^.*(youtu.be\/|v\/|embed\/|watch\?|youtube.com\/user\/[^#]*#([^\/]*?\/)*)\??v?=?([^#\&\?]*).*/;
            var match = theurl.match(regExp);
            if (!match){
                document.getElementById('Youtubeurlmessage').innerHTML = 'Enter Valid Youtube URL';
                document.getElementById('filepath1').focus();
                return false;
            }
//            if (theurl.indexOf("youtube.com") == -1 || theurl.indexOf("youtu.be") == -1 ) {
//                document.getElementById('Youtubeurlmessage').innerHTML = 'Enter Valid Youtube URL';
//                document.getElementById('filepath1').focus();
//                return false;
//            }
            }
    } else if(document.getElementById('btn1').checked == true && document.getElementById('f1-upload-form').style.display != 'none' && document.getElementById('lbl_normal').innerHTML==''){
        document.getElementById('uploadmessage').innerHTML = 'Upload Video';
        return false;
    } else if(document.getElementById('btn1').checked == true && document.getElementById('f3-upload-form').style.display != 'none' && document.getElementById('thumbimageform-value').value==''){
        document.getElementById('uploadthumbmessage').innerHTML = 'Upload Thumb Image';
        return false;
    } else if(document.getElementById('btn3').checked == true){
        if(document.getElementById('filepath2').value == ''){
        document.getElementById('videourlmessage').innerHTML = 'Enter Video URL';
        document.getElementById('filepath2').focus();
        return false;
        } else {
        var thevideourl=document.getElementById("filepath2").value;
        var tomatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
        if (!tomatch.test(thevideourl))
        {
            document.getElementById('videourlmessage').innerHTML = 'Enter Valid Video URL';
            document.getElementById("filepath2").focus();
            return false;
        }
        }
        var thehdvideourl=document.getElementById("filepath3").value;
        if(thehdvideourl!=''){
        var tohdmatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
        if (!tohdmatch.test(thehdvideourl))
        {
            document.getElementById('videohdurlmessage').innerHTML = 'Enter Valid HD Video URL';
            document.getElementById("filepath3").focus();
            return false;
        }
    }
        if(document.getElementById('filepath4').value == ''){
        document.getElementById('thumburlmessage').innerHTML = 'Enter Thumb Image URL';
        document.getElementById('filepath4').focus();
        return false;
    }else{
        var thethumburl=document.getElementById("filepath4").value;
        var tothumbmatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
        if (!tothumbmatch.test(thethumburl))
        {
            document.getElementById('thumburlmessage').innerHTML = 'Enter Valid Thmub Image URL';
            document.getElementById("filepath4").focus();
            return false;
        }
    }
        if(document.getElementById('filepath5').value != ''){
        var thepreviewurl=document.getElementById("filepath5").value;
        var topreviewmatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
        if (!topreviewmatch.test(thepreviewurl))
        {
            document.getElementById('previewurlmessage').innerHTML = 'Enter Valid Preview Image URL';
            document.getElementById("filepath5").focus();
            return false;
        }
    }
    } else if(document.getElementById('btn4').checked == true)
    {
        var streamer_name = document.getElementById('streamname').value;
        document.getElementById('streamerpath-value').value=streamer_name;
        var islivevalue2=(document.getElementById('islive2').checked);
        var tomatch1= /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/
        if(streamer_name == ''){
            document.getElementById('streamermessage').innerHTML = 'You must provide a streamer path!';
            return false;
        } else if (!tomatch1.test(streamer_name))
        {
            document.getElementById('streamermessage').innerHTML = 'Please enter a valid streamer path';
            document.getElementById('streamname').focus();
            return false;
        } else if(document.getElementById('filepath2').value == ''){
        document.getElementById('videourlmessage').innerHTML = 'Enter Video URL';
        document.getElementById('filepath2').focus();
        return false;
        }else if(islivevalue2==true) {
            document.getElementById('islive-value').value=1;
        } else {
            document.getElementById('islive-value').value=0;
        }
    }
    else if(document.getElementById('btn5').checked === true)
    {
        var embed_code = document.getElementById('embedcode').value;
        embed_code = (embed_code + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
        document.getElementById('embed_code').value=embed_code;
        if(embed_code===''){
        document.getElementById('embedmessage').innerHTML = 'Enter Embed Code';
        return false;
        } else if(embed_code.indexOf('<iframe')!=0 && embed_code.indexOf('<embed')!=0 && embed_code.indexOf('<object')!=0){
        document.getElementById('embedmessage').innerHTML = 'Enter Valid Embed Code';
        return false;
        } else{
           document.getElementById('embedmessage').innerHTML = ''; 
        }
    }
    if(document.getElementById('name').value === ''){
        document.getElementById('titlemessage').innerHTML = 'Enter Title';
        return false;
    }
    var check_box = document.getElementsByTagName('input');
    for (var i = 0; i < check_box.length; i++)
    {
        if (check_box[i].type === 'checkbox')
        {
            if (check_box[i].checked) {
                return true
            }
        }
    }
    document.getElementById('jaxcat').innerHTML = 'Select any category for your Video';
    return false;
       
}

function validateplyalistInput(){
   if(document.getElementById('playlistname').value == ''){
        document.getElementById('playlistnameerrormessage').innerHTML = 'Enter Category Name';
        document.getElementById('playlistname').focus();
        return false;
    }
}
