<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script>
$(document).ready(function() {
    $('#keyword').keyup(function(){
	$("#search_result").load('search_bar_patient_list.php?method='+$('#search_option').val()+'&keyword='+encodeURIComponent($('#keyword').val())+'&vstdate='+encodeURIComponent($('#vstdate').val()), function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success")
      //alert("External content loaded successfully!");
    if(statusTxt == "error")
      alert("Error: " + xhr.status + ": " + xhr.statusText);
  });
  });
  
  //datatable
  $('#t1').DataTable( {
        "paging":   false,
        "info":     false
    } );
  
  //option chang
   
});

</script>
</head>

<body>
<div style="padding:5px; background-color: #EFEFEF">
    <form id="search_patient" class="form-inline" action="search_bar_patient_list.php">
    <label for="keyword">&ensp;คำค้น&nbsp;(hn,cid,ชื่อ นามสกุล)&nbsp;</label>
    <input type="text" id="keyword" name="keyword" class="form-control" style='height: 30px;'/>
  <input type="hidden" value="<?php echo $_GET['vstdate']; ?>" id="vstdate"/>
</form>
</div>
<div id="search_result">
</div>
</body>
</html>