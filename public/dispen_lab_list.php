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

if(isset($_GET['action'])&&($_GET['action']=="add")&&($_GET['id']!="")){
	
		mysql_select_db($database_hos, $hos);
		$query_rs_lab = "select l.lab_items_code from ".$database_kohrx.".kohrx_dispensing_lab l where l.lab_items_code ='".$_GET['id']."'";
		$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
		$row_rs_lab = mysql_fetch_assoc($rs_lab);
		$totalRows_rs_lab = mysql_num_rows($rs_lab);
	
		if($totalRows_rs_lab==0){
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_dispensing_lab (lab_items_code) value ('".$_GET['id']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
		
		
		}
	else{
		
		echo "<script>รายการซ้ำ</script>";
	}
	mysql_free_result($rs_lab);

}
if(isset($_GET['action'])&&($_GET['action']=="del")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_dispensing_lab where id='".$_GET['id']."'";
		$delete = mysql_query($query_delete, $hos) or die(mysql_error());
}
mysql_select_db($database_hos, $hos);
$query_rs_lab = "select l.id,i.lab_items_code,i.lab_items_name from ".$database_kohrx.".kohrx_dispensing_lab l left outer join lab_items i on i.lab_items_code=l.lab_items_code order by lab_items_name";
$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
//$row_rs_lab = mysql_fetch_assoc($rs_lab);
$totalRows_rs_lab = mysql_num_rows($rs_lab);

mysql_select_db($database_hos, $hos);
$query_rs_lab_item = "SELECT lab_items_code,lab_items_name FROM lab_items where lab_items_code not in (select lab_items_code from ".$database_kohrx.".kohrx_dispensing_lab) ORDER BY lab_items_name ASC";
$rs_lab_item = mysql_query($query_rs_lab_item, $hos) or die(mysql_error());
$row_rs_lab_item = mysql_fetch_assoc($rs_lab_item);
$totalRows_rs_lab_item = mysql_num_rows($rs_lab_item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
$(document).ready(function() {
	$('#lab-add').click(function(){
		actionLab($('#lab_items').val(),'add');
		});    
});

function actionLab(code,action){
          $('#lab_list').load('dispen_lab_list.php?id='+code+'&action='+action,function(responseTxt, statusTxt, xhr){
          if(statusTxt == "success")
                // $('#counseling_indicator').hide();
           	if(statusTxt == "error")
                 alert("Error: " + xhr.status + ": " + xhr.statusText);    
              });    
	
	}
    
</script>
</head>

<body>
<div class="card">
<div class="card-header">
<div class="row" ><div class="col-sm-auto">รายการ Lab ที่ต้องดู</div><div class="col-sm-auto">
	   <select name="lab_items" id="lab_items" class=" form-control form-control-sm thfont">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_lab_item['lab_items_code']?>"><?php echo $row_rs_lab_item['lab_items_name']?></option>
        <?php
} while ($row_rs_lab_item = mysql_fetch_assoc($rs_lab_item));
  $rows = mysql_num_rows($rs_lab_item);
  if($rows > 0) {
      mysql_data_seek($rs_lab_item, 0);
	  $row_rs_lab_item = mysql_fetch_assoc($rs_lab_item);
  }
?>
        </select>
	   </div><div class="col-sm-auto"><input type="button" name="lab-add" id="lab-add" value="เพิ่ม" class="btn btn-success btn-sm"  /></div></div></div>
<div class="card-body">
<div style="padding: 10px;">
<div class="row">
<?php if ($totalRows_rs_lab > 0) { // Show if recordset not empty 
		$count = 0;
	while ($row_rs_lab = mysql_fetch_assoc($rs_lab)){ 
        if ($count && $count % 6 == 0) echo '</div><div class="row">';
		echo '<div class="col-sm-2" style="padding:5px;"><div class="card"><button type="button" class="close position-absolute" style="right:5px; margin-top:0px;" aria-label="Close" onclick="if(confirm(\'ต้องการลบ Lab : '.$row_rs_lab['lab_item_name'].' จริงหรือไม่\')==true){ actionLab(\''.$row_rs_lab['id'].'\',\'del\'); } "><span aria-hidden="true">&times;</span></button>
<div class="card-body font13" style="background-color:#DFD8DC" style="padding:0px;">'.$row_rs_lab['lab_items_name'].'</div></div></div>';
        $count++;
    }
}
echo '</div>';    
    ?>
</div>
</div>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_lab);
mysql_free_result($rs_lab_item);
?>
