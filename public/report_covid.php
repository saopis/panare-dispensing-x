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
$query_ward = "select * from ward where ward in ('06','07','08')";
$rs_ward = mysql_query($query_ward, $hos) or die(mysql_error());
$row_ward = mysql_fetch_assoc($rs_ward);
$totalRows_ward = mysql_num_rows($rs_ward);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
        
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/i18n/defaults-*.min.js"></script>    <!-- Boxiocns CDN Link -->	

    
    
    
<script>
$(document).ready(function(){
        $('#search').click(function(){
                 var ward=$('#ward').val(),
                     date1=$('#datestart').val(),
                     date2=$('#dateend').val(),
                     pttype=$('#pttype').val(),
                     dateselect=($('#dateselect').prop('checked') ? 'Y' : 'N'),
                     action="search"
                 
              $.ajax({
              type:"POST",
              url: "report_covid_result.php",
              data: {ward:ward,
                     date1:date1,
                     date2:date2,
                     pttype:pttype,
                     dateselect:dateselect,
                     action:action,
                     function:'submit'},	  
              success: function(data){
                  $('#result2').html(data);
              }
            });            
        });
    
});
</script>
</head>

<body>
<div class="p-3">
<div class="h5">รายงานผู้ป่วย Covid-19 อำเภอมหาชนะชัย2</div>	
</div>
<div class="p-3" style="margin-top: -20px;">
	<div class="row" style="font-size: 12px">
		<div class="col-auto">
			<label>เลือกตึก Covid</label>
			<select id="ward" name="ward" class="form-control form-control-sm">
				<option value="">ทั้งหมด</option>
				<?php do{ ?>
				<option value="<?php echo $row_ward['ward']; ?>"><?php echo $row_ward['name']; ?></option>
				<?php }while($row_ward = mysql_fetch_assoc($rs_ward)); ?>
				
			</select>
		</div>
		    <div class="col-auto bg-light border-top border-start border-bottom rounded-top rounded-start rounded-bottom" style="padding-bottom: 5px;">
            <label  ><input type="checkbox" class="form-check-input" id="dateselect" name="dateselect" />&ensp;เลือกช่วงวันที่</label>
                <div id="reportrange" class="form-control form-control-sm" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>	

            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
                
            </div> 
        <div class="col-auto bg-light border-top border-end border-bottom rounded-top rounded-end rounded-bottom" style="padding-bottom: 5px;">
            <label >ประเภทผู้ป่วย</label>
            <select id="pttype" name="pttype" class="form-control form-control-sm">
                <option value="1">admit</option>
                <option value="2">discharge</option>
            </select>
        </div>

		<div class="col-auto">
        <label></label>
        <button id="search" name="search" class="btn btn-sm btn-secondary mt-3">แสดงข้อมูล</button>
        </div>    
        
	</div>
    
    <div id="result2" class="mt-2"></div>
</div>


<?php include('include/datepicker/datepickerrang.php'); ?>  
    
</body>
</html>
<?php mysql_free_result($rs_ward); ?>
