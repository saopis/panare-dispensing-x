<?php require_once('Connections/hos.php'); ?>

<?php 
if(isset($_POST['button8'])&&($_POST['button8']=="เพิ่ม"))
{
	if($_POST['pulse_low']==""){ $pulse_low=60; }
	if($_POST['pulse_hight']==""){ $pulse_hight=100; }
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_pulse (icode,pulse_low,pulse_hight,age) value ('$drug_pulse','$pulse_low','$pulse_hight','$age')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_pulse (icode,pulse_low,pulse_hight,age) value (\'".$drug_pulse."\',\'".$pulse_low."\',\'".$pulse_hight."\',\'".$age."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if(isset($do)&&($do=="drug_pulse_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drug_pulse where icode='$icode'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_pulse where icode=\'".$icode."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_pulse) ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);

mysql_select_db($database_hos, $hos);
$query_rs_drug_pulse = "select p.age,p.icode,concat(d.name,' ',d.strength) as drugname,p.pulse_low,p.pulse_hight from ".$database_kohrx.".kohrx_drug_pulse p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_pulse = mysql_query($query_rs_drug_pulse, $hos) or die(mysql_error());
$row_rs_drug_pulse = mysql_fetch_assoc($rs_drug_pulse);
$totalRows_rs_drug_pulse = mysql_num_rows($rs_drug_pulse);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยาที่ต้องระวังเรื่องอัตราการเต้นของหัวใจ</title>
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
                           columns: [ 0, 1,2,3,4  ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
<div class="card-header">
ยาที่ต้องระวังเรื่องอัตราการเต้นของหัวใจ
</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="drug_setting_pulse.php" class="thfont">
 <div class="form-group row">
<label for="drug_pulse" class="col-sm-auto col-form-label">รายการยา</label>
<div class="col-sm-10">
  <select name="drug_pulse" id="drug_pulse" class="form-control">
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
 </div>
 <div class="form-group row">
<label for="pulse_low" class="col-sm-auto col-form-label">pulse low</label>
<div class="col-sm-2">
<input name="pulse_low" type="text" id="pulse_low" class="form-control" />
</div>
<label for="pulse_hight" class="col-sm-auto col-form-label">pulse hight</label>
<div class="col-sm-2">
<input name="pulse_hight" type="text" id="pulse_hight" class="form-control" />
</div>
<label for="age" class="col-sm-auto col-form-label">อายุ &ge;</label>
<div class="col-sm-1">
<input name="age" type="text" id="age"  class="form-control" />
</div>
<div class="col-sm-2">
<input name="button8" type="submit" class=" btn btn-danger" id="button8" value="เพิ่ม" />
</div>
</div>
<span class="small_red thfont"> **ระบบจะตรวจสอบและเตือนหาก pulse อยู่นอกช่วยที่กรอก&nbsp; ถ้าไม่กรอก pulse low และ hight ระบบจะใช้เกณฑ์ปกติ 60-100</span>
<br />
</form>
</div>
</div>
<div style="margin-top:10px;">
  <table width="100%" id="tables" class="table table-striped table-bordered table-sm table-hover " style="width:100%; ">
	<thead>
    <tr>
    <td width="39" align="center" >ลำดับ</td>
    <td width="235" align="center" >ชื่อยา</td>
    <td width="98" align="center" >pulse_low</td>
    <td width="93" align="center" >pulse_hight</td>
    <td width="69" align="center" >ตั้งแต่อายุ</td>
    <td width="30" align="center" >&nbsp;</td>
  </tr>
  </thead>
  <tbody>
  <?php $j=0; do { $j++; 

	  ?>
  <tr>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $j; ?></td>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug_pulse[drugname]"; ?></td>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug_pulse[pulse_low]"; ?></td>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug_pulse[pulse_hight]"; ?></td>
    <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drug_pulse[age]"; ?></td>
    <td align="center" ><button class="btn btn-danger" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_pulse.php?do=drug_pulse_delete&amp;icode=<?php echo $row_rs_drug_pulse["icode"]; ?>';}">ลบ</button></td>
  </tr>
  <?php } while ($row_rs_drug_pulse = mysql_fetch_assoc($rs_drug_pulse)); ?>
  </tbody>
</table>
</div>
</div>
</body>
</html>