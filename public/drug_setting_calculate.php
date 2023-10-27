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
if(isset($_GET['icode'])&&($_GET['icode']!=""))
{
$condition=" icode ='".$_GET['icode']."'";
}
else {
$condition=" icode not in ('select icode form drugitems_calculate')";	
}

if(isset($_POST['button10'])&&($_POST['button10']=="บันทึก"))
{
mysql_select_db($database_hos, $hos);
$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drugitems_calculate (icode,dosage_max,dosage_min,dose_perunit) value ('".$_POST['drug']."' ,'".$_POST['dosage_max']."','".$_POST['dosage_min']."','".$_POST['dose_perunit']."')";
$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drugitems_calculate (icode,dosage_max,dosage_min,dose_perunit) value (\'".$_POST['drug']."\' ,\'".$_POST['dosage_max']."\',\'".$_POST['dosage_min']."\',\'".$_POST['dose_perunit']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if(isset($_POST['button10'])&&($_POST['button10']=="แก้ไข"))
{
mysql_select_db($database_hos, $hos);
$query_rs_edit = "update ".$database_kohrx.".kohrx_drugitems_calculate set icode='".$_POST['drug']."' ,dosage_max='".$_POST['dosage_max']."',dosage_min='".$_POST['dosage_min']."',dose_perunit='".$_POST['dose_perunit']."'  where icode='".$_POST['icode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drugitems_calculate set icode=\'".$_POST['drug']."\' ,dosage_max=\'".$_POST['dosage_max']."\',dosage_min=\'".$_POST['dosage_min']."\',dose_perunit=\'".$_POST['dose_perunit']."\'  where icode=\'".$_POST['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

echo "<script>window.location='drug_setting_calculate.php';</script>";
exit();

}

if(isset($_GET['do'])&&($_GET['do']=="delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drugitems_calculate where icode='".$_GET['icode']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drugitems_calculate where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

echo "<script>window.location='drug_setting_calculate.php';</script>";
exit();
}

if(isset($_GET['do'])&&($_GET['do']=="edit"))
{
mysql_select_db($database_hos, $hos);
$query_rs_edit = "SELECT * FROM ".$database_kohrx.".kohrx_drugitems_calculate where icode='".$_GET['icode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
}

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,concat(name,strength) as drugname FROM drugitems WHERE ".$condition." and istatus='Y' and (name not like '%คิด%' or name not like '%ต่อ%') ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT concat(d2.name,' ',d2.strength) as drugname,d.icode,d.dosage_min,d.dosage_max,d.dose_perunit FROM ".$database_kohrx.".kohrx_drugitems_calculate d left outer join drugitems d2 on d2.icode=d.icode order by d2.name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ต้องคำนวณสำหรับเด็ก</title>
<link href="css/kohrx.css" rel="stylesheet" type=
"text/css" />
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
                           columns: [ 0, 1,2,3,4  ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>
<style type="text/css">
.white {	color:#FFFFFF;
	font-size:12px;
	font-weight:bolder;
}
tr.grid:hover {
    background-color: #FC3;
}

tr.grid:hover td {
    background-color: transparent; /* or #000 */
}
tr.grid2:hover {
    background-color: #F96;
}

tr.grid2:hover td {
    background-color: transparent; /* or #000 */
}
</style>

</head>

<body >
<div class="p-3" style="margin-top:10px">
<div class="card">
<div class="card-header">
ตารางคำนวณขนาดยาเด็ก
</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="">
  <div class=" form-group row">
  <label for="drug" class="col-sm-2 form-check-label">รายการยา</label>
  <div class="col-sm-10">
  <select name="drug" id="drug" class="thfont font12 form-control form-control-sm" >
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
      </select>
  <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
  </div>
      </div>
      <div class=" form-group row">
 		<label for="dosage_min" class="col-sm-2 col-form-label col-form-label-sm">ขนาดต่ำสุด/dose</label>	     
      <div class="col-sm-2">
      <input name="dosage_min" type="text" class="form-control form-control-sm" id="dosage_min" value="<?php echo $row_rs_edit['dosage_min']; ?>" />
      </div>
		<label for="dosage_max" class="col-sm-auto col-form-label col-form-label-sm">
      ขนาดสูงสุด/dose</label>
      <div class="col-sm-2">
        <input name="dosage_max" type="text" class="form-control form-control-sm" id="dosage_max" value="<?php echo $row_rs_edit['dosage_max']; ?>" />
      </div>
    <label for="dose_perunit" class="col-form-label col-sm-auto col-form-label-sm">ขนาดต่อ 1 cc</label>
     <div class="col-sm-2">
     <input name="dose_perunit" type="text" class="form-control form-control-sm" id="dose_perunit" value="<?php echo $row_rs_edit['dose_perunit']; ?>" /></div>
		  
      </div>
    <div class="form-group row">
    <label class="col-form-label col-sm-2 col-form-label-sm"></label>
     <div class="col-sm-4">
     <input type="submit" name="button10" id="button10" value="<?php if(isset($_GET['do'])&&($_GET['do']=="edit"))
{ echo "แก้ไข"; } else { echo "บันทึก";} ?>" class="btn btn-primary"  /> 

     </div>
     </div>
      <td>&nbsp;</td>
      <td >
      <br />
      <br /></td>
    </tr>
  </table>
</form>
</div>
</div>
<div style="margin-top:10px"> 
  <table width="100%" id="tables" class="table table-striped table-bordered table-hover ">
	<thead>
    <tr>
    <td height="24" align="center" >ลำดับ</td>
    <td align="center" >รายการยา</td>
    <td align="center" >min/dose</td>
    <td align="center" >max/dose</td>
    <td align="center" >ขนาดยา/cc</td>
    <td align="center" >&nbsp;</td>
    </tr>
  </thead>
  <tbody>
   <?php $i=0; do { $i++;

    ?><tr>
   
      <td align="center"><?php echo $i; ?></td>
      <td align="left"><?php echo $row_rs_drug2['drugname']; ?></td>
      <td align="center"><?php echo $row_rs_drug2['dosage_min']; ?></td>
      <td align="center"><?php echo $row_rs_drug2['dosage_max']; ?></td>
      <td align="center"><?php echo $row_rs_drug2['dose_perunit']; ?></td>
      <td align="center"><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_calculate.php?icode=<?php echo $row_rs_drug2['icode']; ?>&do=edit';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_calculate.php?do=delete&amp;icode=<?php echo $row_rs_drug2["icode"]; ?>';}"></i></td>
     
   </tr> 
   <?php } while ($row_rs_drug2 = mysql_fetch_assoc($rs_drug2)); ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drug2);
if(isset($_GET['do'])&&($_GET['do']=="edit"))
{
mysql_free_result($rs_edit);
}
?>
