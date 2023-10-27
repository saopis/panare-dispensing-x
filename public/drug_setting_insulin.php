<?php require_once('Connections/hos.php'); ?>
<?php 
if(isset($_GET['do'])&&($_GET['do']=="drug_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drug_insulin where icode='".$_GET['icode']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_insulin where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	echo "<script>window.location='drug_setting_insulin.php';</script>";
}

if(isset($_POST['save'])&&($_POST['save']=="เพิ่ม"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_insulin (icode,units) value ('".$_POST['drug']."','".$_POST['units']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_insulin (icode,units) value (\'".$_POST['drug']."\',\'".$_POST['units']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}


mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_insulin) ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.units from ".$database_kohrx.".kohrx_drug_insulin p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยา insulin</title>
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
                           columns: [ 0, 1 ,2 ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>
<style>
@media print {
    table,table thead, table tr, table td {
        border-top: #000 solid 1px;
        border-bottom: #000 solid 1px;
        border-left: #000 solid 1px;
        border-right: #000 solid 1px;
        font-size: 16px;
    }
    table {
    border:solid #000 !important;
    border-width:1px 0 0 1px !important;
}
th, td {
    border:solid #000 !important;
    border-width:0 1px 1px 0 !important;
}
} 
</style>

</head>

<body>
<div class="p-3" style="margin-top:10px">
<div class="card">
<div class="card-header">
รายการยา insulin
  *เพื่อแสดงรายการการใช้เข็มฉีด</div>
<div class="card-body">  
<form id="form1" name="form1" method="post" action="" class="thfont font12">
<div class="form-group row">
<label for="drug" class="col-sm-2 col-form-label">รายการยา</label>
<div class="col-4">
  <select name="drug" id="drug" class=" form-control" >
    <?php
do {  
?>
    <option value="<?php echo $row_rs_drug3['icode']?>"><?php echo $row_rs_drug3['drugname']?></option>
    <?php
} while ($row_rs_drug3 = mysql_fetch_assoc($rs_drug3));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
  </select>
  </div>
  <label for="units" class="col-1 col-form-label">ขนาด</label> 
 <div class="col-2">
  <input type="text" name="units" id="units" placeholder="ยูนิต" class=" form-control" />
  </div>
  <div class="col-2">
    <input name="save" type="submit" class=" btn btn-primary" id="save" value="เพิ่ม" />
</div>
</div>
</form>
</div>
</div>
<div style="margin-top:10px;">
  <table width="100%" id="tables" class="table table-striped table-bordered table-hover" style="width:100%">
	<thead>
    <tr>      
    <td width="49" align="center" >ลำดับ</td>
      <td width="389" align="center" >ชื่อยา</td>
      <td width="40" align="center" >ขนาด(units)</td>
      <td width="40" align="center" >&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $j=0; do { $j++;  ?>
    <tr>
      <td align="center" ><?php echo $j; ?></td>
      <td align="center" ><?php echo "$row_rs_drug[drugname]"; ?></td>
      <td align="center" ><?php echo "$row_rs_drug[units]"; ?></td>
      <td align="center" ><nobr><i class="fas fa-trash" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_insulin.php?do=drug_delete&amp;icode=<?php echo $row_rs_drug["icode"]; ?>';}" style="color:#666; cursor:pointer"></i></nobr></td>
    </tr>
    <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
    </tbody>
  </table>
  </div>
  </div>
</body>
</html>
<?php 
mysql_free_result($rs_drug);
mysql_free_result($rs_drug3);
?>