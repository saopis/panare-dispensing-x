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

mysql_select_db($database_hos, $hos);
$query_rs_lab_head = "select  l.vn,l.form_name,l.lab_order_number,d.name,date_format(l.order_date,'%d/%m/%Y') as orderdate from lab_head l left join doctor d on d.code=l.doctor_code where l.vn='".$_GET['vn']."'";
$rs_lab_head = mysql_query($query_rs_lab_head, $hos) or die(mysql_error());
$row_rs_lab_head = mysql_fetch_assoc($rs_lab_head);
$totalRows_rs_lab_head = mysql_num_rows($rs_lab_head);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>

<script>
$('#lab-head').change(function(){
                if($(this).val()!="0"){
					$('#indicator').fadeIn(1000);
				
                    $('#lab-result').load('emr_detail_lab_result.php?order_number='+$(this).val(), function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(2000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
				}
    });


</script>
</head>

<body>
<div class="card mt-2">
<div class="card-body">
<span class="card-title font_bord text-primary"><i class="fas fa-vial text-dark font20"></i>&ensp;เลือก LAB group ที่ต้องการดูผล</span>
<select class="form-control mt-1" id="lab-head" style="width:200px;">
  <option value="0">-- กรุณาเลือก --</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rs_lab_head['lab_order_number']; ?>"><?php echo $row_rs_lab_head['form_name'];?></option>
  <?php
} while ($row_rs_lab_head = mysql_fetch_assoc($rs_lab_head));
  $rows = mysql_num_rows($rs_lab_head);
  if($rows > 0) {
      mysql_data_seek($rs_lab_head, 0);
	  $row_rs_lab_head = mysql_fetch_assoc($rs_lab_head);
  }
?>
</select>
</div>
</div>
<div id="lab-result"></div>
</body>
</html>
<?php mysql_free_result($rs_lab_head); ?>