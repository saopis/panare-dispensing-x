<?php require_once('Connections/hos.php'); ?>
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

if(isset($_GET['action'])&&($_GET['action']=="del")){
mysql_select_db($database_hos, $hos);
$query_rs_del = "update ".$database_kohrx.".kohrx_rx_person set active='N' where doctorcode='".$_GET['doctorcode']."'";
$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());
}
if(isset($_GET['action'])&&($_GET['action']=="add")&&($_GET['doctorcode']!="")){
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "select doctorcode from ".$database_kohrx.".kohrx_rx_person where doctorcode='".$_GET['doctorcode']."'";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
//$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

if($totalRows_rs_doctor==0){
mysql_select_db($database_hos, $hos);
$query_rs_insert = "insert into ".$database_kohrx.".kohrx_rx_person (doctorcode,active) value ('".$_GET['doctorcode']."','Y')";
$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
}
else {
mysql_select_db($database_hos, $hos);
$query_rs_del = "update ".$database_kohrx.".kohrx_rx_person set active='Y' where doctorcode='".$_GET['doctorcode']."'";
$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());	
}

	mysql_free_result($rs_doctor);
	
}

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "select d.name,d.code from ".$database_kohrx.".kohrx_rx_person k left outer join doctor d on d.code=k.doctorcode where k.active='Y' order by d.name";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
//$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
$(document).ready(function() {
	$('#person-add').click(function(){
		actionPerson($('#doctorcode').val(),'add');
		$('#doctorcode').val("");
		$('#person').val("");
		
		});    
});

function actionPerson(code,action){
          $('#rx-person').load('dispen_rx_person.php?doctorcode='+code+'&action='+action,function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
                // $('#counseling_indicator').hide();
           	if(statusTxt == "error")
                 alert("Error: " + xhr.status + ": " + xhr.statusText);    
              });    
	
	}

</script>

</head>

<body>
<div style="padding:10px;" >
<div class="row">
<?php if ($totalRows_rs_doctor > 0) { 
		$count = 0;
	while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor)){ 
        if ($count && $count % 4 == 0) echo '</div><div class="row">';
		echo '<div class="col-sm-3" style="padding:5px;"><div class="card"><button type="button" class="close position-absolute" style="right:5px; margin-top:0px;" aria-label="Close" onclick="if(confirm(\'ต้องการลบชื่อ '.$row_rs_doctor['name'].' จริงหรือไม่\')==true){ actionPerson(\''.$row_rs_doctor['code'].'\',\'del\'); } "><span aria-hidden="true">&times;</span></button>
<div class="card-body" style="background-color:#DFD8DC;"><span class=" font-weight-bold">'.$row_rs_doctor['code'].'</span>&ensp;<span class="font13">'.$row_rs_doctor['name'].'</span></div></div></div>';
        $count++;
    }
}
echo '</div>';
?>
</div>

</body>
</html>
<?php
mysql_free_result($rs_doctor);
?>
