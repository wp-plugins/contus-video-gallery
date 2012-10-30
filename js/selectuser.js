var xmlhttp;
var myarray = [];
var myarray1;
function showUser(str,order)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
var url="../wp-content/plugins/contus-hd-flv-player/process-sortable.php";
url=url+"?"+order;
url=url+"&playid="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function stateChanged()
{
if (xmlhttp.readyState==4)
{
     myarray = xmlhttp.responseText;
       myarray1 = myarray.split(",");
        var length1 = myarray1.length-1;
        var i=0;
        for(i=0;i<=length1;i++ )
        {
            document.getElementById('txtHint['+myarray1[i]+']').innerHTML=i;
        }
    
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}