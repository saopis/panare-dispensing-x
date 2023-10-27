<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html lang="en-US">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html, body
{
  height: 95%;
}
</style>
<!-- jquery -->
<script src="include/jquery/js/jquery.min.js" ></script>
<script language="javascript" type="text/javascript">

$(document).ready(function() {
setInterval(reloadChat, 2000);        
});
function reloadChat () {
     $('#recheck').load('queue_caller_recheck.php');
}
function page_load(text){
//	$("#aud").attr("src", page);	
                $("#aud").load('queue_audio2.php?text='+encodeURIComponent(text), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");                   
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}


</script>
<script type="text/javascript">
function iframeload(url,framename){
	document.frames[framename].location.href=url;;
	};

$(document).ready(function() {

        var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()
    var seconds=Digital.getSeconds()
    var dn="AM"
    if (hours>12){
        dn="PM"
        hours=hours
        //this is so the hours written out is in 12-hour format, instead of the default //24-hour format.
    }
    if (hours==0)
        hours=0
    //this is so the hours written out when hours=0 (meaning 12a.m) is 12
    if (minutes<=9)
        minutes="0"+minutes
    if (seconds<=9)
        seconds="0"+seconds
 });
 

</script>

<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<body>
<div style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:80%;width:100%;position:absolute;top:100px;left:0px;right:0px;bottom:0px; ">
<div id="recheck"></div>
<div id="aud" hidden></div>
</div>
</body>
</html>
