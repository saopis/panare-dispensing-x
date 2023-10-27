<?php require_once('Connections/hos.php'); 

if(isset($_POST['button9'])&&$_POST['button9']=="เพิ่ม"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drug_spacial (icode,sp_id) value ('".$_POST['sp_drug']."','".$_POST['sp_use']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_spacial (icode,sp_id) value (\'".$_POST['sp_drug']."\',\'".$_POST['sp_use']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if(isset($_GET['do'])&&$_GET['do']=="spacial_delete"){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_drug_spacial where icode ='".$_GET['icode']."'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_spacial where icode =\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

mysql_select_db($database_hos, $hos);
$query_rs_sp1 = "SELECT concat(d.name,' ',d.strength) as drugname,t.name,t.detail,s.icode FROM ".$database_kohrx.".kohrx_drug_spacial s left outer join s_drugitems d on s.icode=d.icode left outer join ".$database_kohrx.".kohrx_spacial_technique t on t.id=s.sp_id order by s.sp_id DESC";
$rs_sp1 = mysql_query($query_rs_sp1, $hos) or die(mysql_error());
$row_rs_sp1 = mysql_fetch_assoc($rs_sp1);
$totalRows_rs_sp1 = mysql_num_rows($rs_sp1);

mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_spacial) ORDER BY name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

mysql_select_db($database_hos, $hos);
$query_rs_sp = "select * from ".$database_kohrx.".kohrx_spacial_technique";
$rs_sp = mysql_query($query_rs_sp, $hos) or die(mysql_error());
$row_rs_sp = mysql_fetch_assoc($rs_sp);
$totalRows_rs_sp = mysql_num_rows($rs_sp);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('java_css_online.php'); ?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ต้องมีฉลากสอนเทคนิคพิเศษ</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
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
                           columns: [ 0, 1,2  ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px;" >
<div class="card-header">กำหนดฉลากช่วยยาเทคนิคพิเศษ</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="" class="thfont">
<div class="form-group row">
<label for="sp_drug" class="col-sm-2 col-form-label">รายการยา</label>
<div class="col-sm-10">

    <select name="sp_drug" id="sp_drug" class="thfont form-control">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_drug2['icode']?>"><?php echo $row_rs_drug2['drugname']?></option>
      <?php
} while ($row_rs_drug2 = mysql_fetch_assoc($rs_drug2));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
    </select>
</div>
</div>
<div class="form-group row">
<label for="sp_use" class="col-sm-2 col-form-label">รายการยา</label>
<div class="col-sm-8">
    <select name="sp_use" id="sp_use" class="thfont form-control">
      <?php
do {  
?>
      <option value="<?php echo $row_rs_sp['id']?>"><?php echo $row_rs_sp['detail']?></option>
      <?php
} while ($row_rs_sp = mysql_fetch_assoc($rs_sp));
  $rows = mysql_num_rows($rs_sp);
  if($rows > 0) {
      mysql_data_seek($rs_sp, 0);
	  $row_rs_sp = mysql_fetch_assoc($rs_sp);
  }
?>
    </select>
</div>
<div class="col-sm-2">
    <input name="button9" type="submit" class=" btn btn-danger" id="button9" value="เพิ่ม" />

</div>
</div>
</form>
</div>
</div>
</div>
<div class="p-3" style="margin-top:10px;">
 <?php if ($totalRows_rs_sp > 0) { // Show if recordset not empty ?>
  <table width="100%" id="tables" class="table table-striped table-bordered table-hover " >
	<thead>
    <tr>
      <td  align="center" >ลำดับ</td>
      <td align="center" >ชื่อยา</td>
      <td align="center" >ฉลากช่วย</td>
      <td align="center" ></td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; 

	  ?><tr class="grid4">
      
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo $row_rs_sp1['drugname']; ?></td>
      <td align="center" ><?php echo $row_rs_sp1['detail']; ?></td>
        <td align="center"><i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_spacial.php?do=spacial_delete&amp;icode=<?php echo $row_rs_sp1["icode"]; ?>';}"></i></td>
      </tr>      
        <?php } while ($row_rs_sp1 = mysql_fetch_assoc($rs_sp1)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
</div>

</body>
</html>
<?php 
mysql_free_result($rs_drug2);

mysql_free_result($rs_sp);

?>