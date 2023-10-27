<?php require_once('Connections/hos.php'); ?>
<?php 
$today=sprintf("%02d", date('d'))."/".sprintf("%02d", date('m'))."/".(date('Y')+543);

?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
table.table_bord1 tr td{border:solid 1px #CCCCCC; background-color:#FFF}
table.table_bord1{border-collapse:collapse; border-left:0px;}
table.table_bord1 tr.head td{background-color: #F4F4F4;}
</style>
<?php include('java_function2.php'); ?> 
<script>
    
$(document).ready(){
    $('#btn_search').click(function(){
	           $('#displayDiv').load('ipd_note_list.php?hn='+$('#hn').val()+'&vn='+$('#vn').val()+'&pttype='+$('#pttype').val()+'&date='+$('#date1').val(),function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
								$('#modal-title-danger').html('<i class="fas fa-exclamation-triangle font20"></i>&ensp;แจ้งเตือนการเกิดปฏิริยาระหว่างยา : Drug Interaction Check');
								$('#myModal-danger').modal('show');
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });        
    });
}
    
function alertloads(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){formSubmit('displayDiv','indicator');}});

	}			
</script>
<script>
function formSubmit(displayDiv,indicator) {
	var URL = "ipd_note_list.php";		
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	}
	
function hnselect(hn)
	{	
	var hnsel = self.opener.document.getElementById("ipd");
	hnsel.value=hn;
	window.close();

	}

</script>

</head>

<body onload="javascript:document.getElementById('search').focus();formSubmit('displayDiv','indicator');">
<div class="thfont font18 font_bord">ค้นหาผู้ป่วยใน</div>
<form name="form1" method="post" action="" class="thfont font13" >
  ชื่อ/HN 
  <input name="search" type="text" id="search"  onkeyup="formSubmit('displayDiv','indicator');" size="10" class="inputcss1 thfont font14" /> 
  AN
  <label for="an"></label>
  <input type="text" name="an" id="an" class="inputcss1 thfont font14">
  <select name="pttype" id="pttype" class="inputcss1 thfont font14">
    <option value="1">ผู้ป่วยที่ Admit ปัจจุบัน</option>
    <option value="2">ผู้ป่วย D/C ในวันที่</option>
    <option value="3">ผู้ป่วย Admit ในวันที่</option>
  </select>
  <span class="head_small_gray">
  <input name="date1" type="text" id="date1" value="<? if(!isset($vstdate)&&$vstdate==""){ echo $today; } else { echo $vstdate;}?>" size="6"  readonly="readonly" class="inputcss1 thfont font14" style="width:100px"  />
  </span>
  <input type="button" name="btn_search" id="btn_search" value="ค้นหา" onClick="formSubmit('displayDiv','indicator');" class="button blue thfont font14" /> 

<div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"> <img src="images/indicator.gif" hspace="10" align="absmiddle" />&nbsp;</div><div id="displayDiv"  style="margin-top:10px">&nbsp;</div>
</form>
</body>
</html>
<?php

mysql_free_result($rs_setting);

?>
