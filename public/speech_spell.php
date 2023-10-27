<?php require_once('Connections/hos.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script>
$(document).ready(function() {
	$('#list_indicator').hide();
    $('#button').click(function(){                      
       $('#list_indicator').show();
	   $('#spell_add').html("");
       $('#spell_list').load('speech_spell_list.php?search='+encodeURIComponent($('#search_name').val())+'&type='+$('#search_type').val(),function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success")
              $('#list_indicator').hide();
           if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);    
           });
		
	});
	
});

function speech_edit(name_type,ptname){
       $('#list_indicator').show();
       $('#spell_add').load('speech_spell_add.php?search='+encodeURIComponent(ptname)+'&type='+name_type,function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success")
              $('#list_indicator').hide();
           if(statusTxt == "error")
              alert("Error: " + xhr.status + ": " + xhr.statusText);    
           });
		
	
	}
</script>
<style>
html,body{overflow:hidden; }
::-webkit-scrollbar {
    width: 15px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
</style>

</head>

<body >

<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fab fa-whmcs font20"></i>&ensp;ตั้งค่าการสะกดชื่อ</span>
</nav>
<div style="margin-top:40px; padding:10px; height:520px">

<div id="search">
<div class="card">
<div class="card-header">ค้นหาชื่อ/นามสกุล</div>
<div class="card-body">
<!--indicator-->
<div id="list_indicator" align="center" class="spinner" style="position:absolute; margin-top:0px; margin-left:45%; ">
     <button class="btn btn-info" type="button" disabled style="opacity:0.5">
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span >กำลังโหลด..</span>
</button>
</div>
<!--indicator-->

	<div class="form-row">
    <div class="col-sm-auto"><label for="search_type" class="col-form-label-sm font14">ประเภทค้นหา</label></div>
    <div class="col-auto"><select name="search_type" class=" form-control form-control-sm" id="search_type">
        <option value="fname">ชื่อ</option>
        <option value="lname">นามสกุล</option>
      </select></div>
    <div class="col-sm-auto"><label for="search" class="col-form-label-sm">ค้นหา</label></div>
    <div class="col-sm-auto"><input name="search_name" type="text" class=" form-control form-control-sm " id="search_name"  /></div>
    <div class="col"><input type="button" name="button" id="button" value="ค้นหา" class="btn btn-info" /></div>
    </div>
</div>
</div>

<div class="row">
<div class="col-sm-6" style="padding:10px; padding-left:15px;">
<div id="spell_list" ></div>
</div>
<div class="col-sm-6" style="margin-top:10px;">
<div id="spell_add" ></div>
</div>
</div>

</body>
</html>
