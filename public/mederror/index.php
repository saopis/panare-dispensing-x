<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
html,body { height:100%; overflow: hidden; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

.ui-autocomplete {
	    position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:300px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
        font-size: 14px;

}

</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#indicator').show();
    $("#result").load('result.php', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                    });
    
                
    //form load
    $("#medform").load('med_form.php', function(responseTxt, statusTxt, xhr){
                   
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });    


});

</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>

</head>

<body>
<div class="row" style="height: 50px;">
	<div class="col bg-dark text-white text-center" style="-ms-flex: 0 0 200px;flex: 0 0 150px;"><div class=" font_bord" style="font-size: 30px; margin-top: -5px;">ME</div>
<div class="font12" style="margin-top: -10px;">medication error</div></div>
	<div class="col bg-info text-white" style="padding-top: 10px;">บันทึกความคลาดเคลื่อนทางด้านยา (full form)
	<div style=" position: absolute; top: 10px; right: 50px;"><button class="btn btn-dark btn-sm " onClick="window.location='error_indiv.php';">บันทึกจัดยา (short form)</button>&nbsp;<button class="btn btn-secondary btn-sm" onClick="alertload('config_error_type.php','900','500');">ตั้งค่า</button></div>
	</div>	
	
</div>
<div class="row">
<div class="col-7" style=" padding-right: 0px; overflow:scroll;overflow-x:hidden;overflow-y:auto; height:95vh;"><div id="medform"></div>
</div>
<div class="col-5" style="right: 15px; overflow:scroll;overflow-x:hidden;overflow-y:auto; height:95vh; padding-right: 0px;">
<div class="position-absolute text-center" id="indicator" style="width: 100%;z-index: 2;">
<div  align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.5;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <br />
<span>กำลังโหลด</span>
  </button>
</div>
</div>

	<div id="result" class="p-2"></div>
</div>

</div>
</body>
</html>
<?php
mysql_free_result($rs_error_type);
mysql_free_result($drug);

?>