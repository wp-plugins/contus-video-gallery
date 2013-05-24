/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Admin action javacript file.
Version: 2.1
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
            alert("You want to delete Playlist? ");
            return true;
        }
        else
        {
            alert("Please select a Playlist to delete");
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

function Videoadtype()
{
    if(document.getElementById('filebtn').checked==true)
    {
        document.getElementById('upload2').style.display = "block";
        document.getElementById('videoadurl').style.display = "none";
    }

    if(document.getElementById('urlbtn').checked==true)
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadurl').style.display = "block";
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
    document.forms[form_handle].action = "../wp-content/plugins/contus-video-gallery/admin/ajax/videoupload.php?processing=1";
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
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/contus-video-gallery/images/empty.gif';
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
            break;

        case "Uploading":
            document.getElementById(divprefix + "-upload-form").style.display = "none";
            document.getElementById(divprefix + "-upload-progress").style.display = "";
            document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading";
            document.getElementById(divprefix + "-upload-message").style.display = "none";
            document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/contus-video-gallery/images/loader.gif';
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
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/contus-video-gallery/images/success.gif';
            document.getElementById(divprefix + "-upload-status").innerHTML = "";
            document.getElementById(divprefix + "-upload-message").style.display = "";
            document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
            document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage;
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
            break;


        default:
            document.getElementById(divprefix + "-upload-image").src = '../wp-content/plugins/contus-video-gallery/images/error.gif';
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
    if(document.getElementById('btn2').checked == true && document.getElementById('filepath1').value == ''){
        document.getElementById('Youtubeurlmessage').innerHTML = 'Enter Youtube URL';
        document.getElementById('filepath1').focus();
        return false;
    } else if(document.getElementById('btn1').checked == true && document.getElementById('f1-upload-form').style.display != 'none' && document.getElementById('lbl_normal').innerHTML==''){
        document.getElementById('uploadmessage').innerHTML = 'Upload Video';
        return false;
    } else if(document.getElementById('btn1').checked == true && document.getElementById('f3-upload-form').style.display != 'none' && document.getElementById('thumbimageform-value').value==''){
        document.getElementById('uploadthumbmessage').innerHTML = 'Upload Thumb Image';
        return false;
    } else if(document.getElementById('btn3').checked == true && document.getElementById('filepath2').value == ''){
        document.getElementById('videourlmessage').innerHTML = 'Enter Video URL';
        document.getElementById('filepath2').focus();
        return false;
    } else if(document.getElementById('btn3').checked == true && document.getElementById('filepath4').value == ''){
        document.getElementById('thumburlmessage').innerHTML = 'Enter Image URL';
        document.getElementById('filepath4').focus();
        return false;
    } else if(document.getElementById('btn4').checked == true)
    {
        var streamer_name = document.getElementById('streamname').value;
        document.getElementById('streamerpath-value').value=streamer_name;
        var islivevalue2=(document.getElementById('islive2').checked);
        var tomatch= /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/
        if(streamer_name == ''){
            alert( "You must provide a streamer path!" )
            return false;
        } else if (!tomatch.test(streamer_name))
        {
            alert( "Please enter a valid streamer path" )
            document.getElementById('streamname').focus();
            return false;
        } else if(islivevalue2==true) {
            document.getElementById('islive-value').value=1;
        } else {
            document.getElementById('islive-value').value=0;
        }
    } else if(document.getElementById('name').value == ''){
        document.getElementById('titlemessage').innerHTML = 'Enter Title';
        return false;
    }
    var check_box = document.getElementsByTagName('input');
    for (var i = 0; i < check_box.length; i++)
    {
        if (check_box[i].type == 'checkbox')
        {
            if (check_box[i].checked) {
                return true
            }
        }
    }
    alert("Select any playlist for your Video")
    return false;
       
}