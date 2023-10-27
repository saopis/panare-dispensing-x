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
 if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_adherance (icode) value ('".$_POST['drugname']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_adherance (icode) value (\'".$_POST['drugname']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}
if($_GET['do']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_drug_adherance where icode ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());	
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_adherance where icode =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}

mysql_select_db($database_hos, $hos);
$query_rs_drugqty = "select concat(d.name,' ',d.strength) as drugname,u.icode from ".$database_kohrx.".kohrx_drug_adherance u left outer join drugitems d on d.icode=u.icode order by drugname ASC";
$rs_drugqty = mysql_query($query_rs_drugqty, $hos) or die(mysql_error());
$row_rs_drugqty = mysql_fetch_assoc($rs_drugqty);
$totalRows_rs_drugqty = mysql_num_rows($rs_drugqty);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_adherance) ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ต้องติดตามความร่วมมือในการใช้</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var printCounter = 0;
    $('#tables').append('<caption style="caption-side: bottom"></caption>');
    $('#tables').DataTable( {
		dom: 'lfrtip',
		paging: false,
		retrieve: true,
        dom: 'Bfrtip',
        buttons: [         

            {
				extend: 'copy',
				text: '<i class="fas fa-copy"></i>&nbsp;Copy',
				className: 'btn btn-secondary',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>&nbsp;CSV',
				className: 'btn btn-secondary',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>&nbsp;Excel',
				className: 'btn btn-secondary',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}	
			, 
				{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>&nbsp;PDF',
				className: 'btn btn-secondary',
				titleAttr: 'PDF',
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}			
			,
                       {
                       extend: 'print',
					   text: '<i class="fas fa-print"></i> Print',
					   titleAttr: 'PRINT',                        
                       exportOptions: {
                          // stripHtml : false,
                           messageBottom: null,
                           columns: [ 0, 1  ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>
</head>

<body>
<div class="p-3" style="margin-top:20px;">
<div class="card">
<div class="card-header">
ยาที่ต้องตรวจสอบความร่วมมือในการใช้
</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="">
<div class="form-group row">
<label for="drugname" class="col-sm-2 form-check-label">รายการยา</label>
<div class="col-sm-8">
  <select name="drugname" id="drugname"  class="form-control">
    <option value="">-</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rs_drug['icode']?>" <?php if (!(strcmp($row_rs_drug['icode'], $icode))) {echo "selected=\"selected\"";} ?>><?php echo  $row_rs_drug['name']." ".$row_rs_drug['strength'];
?></option>
    <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
  </select>
  </div>
<div class="col-sm-2">
  <input type="submit" name="save" id="save" value="บันทึก" class=" btn btn-primary form-control"/>
 </div>
 </div>
 </form>
</div>
</div>
<div style="margin-top:10px;" >
<?php if ($totalRows_rs_drugqty > 0) { // Show if recordset not empty ?>
  <table width="100%" id="tables" class="table table-striped table-bordered table-hover" style="width:100%">
	<thead>
    <tr>      
    <td width="31" align="center" >id</td>
      <td width="342" >drugname</td>
      <td width="109" align="center" ></td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; ?>
    <tr>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugqty['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drugusage[code]"; ?> <button class="btn btn-sm btn-danger noexport" onClick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_adherance.php?id=<?php echo $row_rs_drugqty['icode']; ?>&do=delete'; }">ลบ</button></td>
    </tr>
    <?php } while ($row_rs_drugqty = mysql_fetch_assoc($rs_drugqty)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>  
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drugqty);
?>
