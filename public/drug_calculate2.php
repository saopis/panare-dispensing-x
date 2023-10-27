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
$query_rs_drug = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode order by d2.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>    
<script>
$(document).ready(function(){
	$('#calculate').click(function(){
                $("#calculate_result").load('drug_calculate_result.php?drug='+$('#drug').val()+'&bw='+$('#bw').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });		
	});
});
</script>

</head>

<body>
<nav class="navbar navbar-dark bg-info text-white " >
  <!-- Navbar content -->
  <span class="card-title font_bord"  >&ensp;คำนวนขนาดยาเด็ก</span>
</nav>
<form id="form1" name="form1" method="post" action="">
<div class="p-3">
	<div class="card">
	<div class="card-body">
	  <div class="row">
		  <div class="col-sm-2">เลือกตัวยา</div>
		  <div class="col-sm-auto"><select name="drug" id="drug" class="form-control form-control-sm">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_drug['icode']?>"><?php echo $row_rs_drug['drugname']?></option>
        <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
      </select></div>
		</div>
	<div class="row mt-2">
		<div class="col-sm-2">น้ำหนัก</div>
		<div class="col-sm-auto"><input name="bw" type="text" id="bw" class="form-control form-control-sm" /> </div>
		<div class="col-sm-auto">กิโลกรัม</div>
		<div class="col-sm-auto"><input type="button" class="btn btn-primary btn-sm" id="calculate" value="คำนวณ"/></div>
		</div>
	  </div>
	</div>
	<div id="calculate_result" class="mt-2"></div>
</div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
