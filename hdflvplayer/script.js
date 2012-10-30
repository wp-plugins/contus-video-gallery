function currentvideo(vid,title,tag){

document.getElementById('video_tag').innerHTML = '';
     fbcomments(vid,title);
     document.getElementById('video_title').innerHTML=title;
     if(tag != '')
     {
     var tagSplit=tag.split(",");
     var tags ='<div class="tags">Tags:&nbsp;&nbsp;</div>';

       for (var i = 0; i < tagSplit.length; i++)
        {
           if(i <(tagSplit.length)-1)
               {
                   tags += "<a href='"+baseurl+"?page_id="+videoPage+"&tagname="+tagSplit[i]+"' class='tagViews'>"+tagSplit[i]+', '+"</a>";
               }
               else
                   {
                       tags += "<a href='"+baseurl+"?page_id="+videoPage+"&tagname="+tagSplit[i]+"'  class='tagViews'>"+tagSplit[i]+"</a>";
                   }

        }

              document.getElementById('video_tag').innerHTML+=tags;
     }
     else
         {
            document.getElementById('video_tag').innerHTML+='';
         }
     if (vid=="")
  {
     // alert('Hi i am in empty');
  document.getElementById("txtHint").innerHTML="";
  return;
  }
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
       // alert('i am ready');
    }
  }

xmlhttp.open("GET",baseurl+"/wp-content/plugins/"+folder+"/hitCount.php?vid="+vid,true);
xmlhttp.send();

 }
 function fbcomments(vid,title) {
   if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
   httpxml=new XMLHttpRequest();
//alert(xmlhttp);
  }
httpxml.onreadystatechange=function()
  {
  if (httpxml.readyState==4 && httpxml.status==200)
    {
    var fbComments = httpxml.responseText;
    document.getElementById("facebook").innerHTML = fbComments;
    getfacebook();
    return false;
    }
  }
httpxml.open("GET",baseurl+"/wp-content/plugins/"+folder+"/fbcomment.php?vid="+vid+'&vname='+title+'&siturl='+baseurl+'&folder='+folder,true);
httpxml.send();
}
