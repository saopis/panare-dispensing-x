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
	$query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile_medplan (icode,med_plan_type,drugusage) value ('".$_POST['drugname']."','".$_POST['med_plan_type']."','".$_POST['drugusage']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_med_reconcile_medplan (icode,med_plan_type) value (\'".$_POST['drugname']."\',\'".$_POST['med_plan_type']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}
 if(isset($_GET['do'])&&($_GET['do']=="delete")){
 	mysql_select_db($database_hos, $hos);
	$query_insert = "delete from ".$database_kohrx.".kohrx_med_reconcile_medplan where icode='".$_GET['icode']."'";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_med_reconcile_medplan where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
 }
mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM drugitems WHERE istatus='Y' and icode like '1%' and icode not in (select icode from ".$database_kohrx.".kohrx_med_reconcile_medplan) ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_medplan = "SELECT d.name,m.*,u.shortlist FROM ".$database_kohrx.".kohrx_med_reconcile_medplan m left outer join drugitems d on d.icode=m.icode left outer join drugusage u on u.drugusage=m.drugusage";
$rs_medplan = mysql_query($query_rs_medplan, $hos) or die(mysql_error());
$row_rs_medplan = mysql_fetch_assoc($rs_medplan);
$totalRows_rs_medplan = mysql_num_rows($rs_medplan);

mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select shortlist,drugusage from drugusage where status='Y' ";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="include/jquery/css/jquery-ui.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>    
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$('#save').prop('disabled',true);
	$('#drugname').change(function(){
		if($('#drugname').val()!=""){
			$('#save').prop('disabled',false);

		}
		else {
			$('#save').prop('disabled',true);
			
		}
	});
});
</Script>
    
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
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>&nbsp;CSV',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>&nbsp;Excel',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)'
					}
				}	
			, 
				{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>&nbsp;PDF',
				className: 'btn btn-default',
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


<style>
.ui-autocomplete {
	padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
	}
/*   css ส่วนของรายการที่แสดง  */   
	/*  css  ส่วนปุ่มคลิกเลือกแสดงรายการทั้งหมด*/ 

</style>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  กำหนดประเภทรายการยาจากโรงพยาบาลอื่น</div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="">
      <div class="form-group row">
        <label for="drugusage" class="col-sm-2 col-form-label">รายการยา</label>
        <div class="col-sm-5">
        <select name="drugname" id="drugname"  class="form-control">
          		<option value="">เลือกรายการยา</option>
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
        <label for="real_use" class="col-sm-auto col-form-label">ประเภทยา</label>
        <div class="col-sm-2">
            <select id="med_plan_type" name="med_plan_type" class="form-control">
                <option value="1">ยากิน</option>
                <option value="2">ยาฉีดและอื่นๆ</option>
            </select>
        </div>
		</div>
		<div class="row">
        <label for="drugusage" class="col-sm-2 col-form-label">วิธีใช้</label>
        <div class="col-sm-6">
            <select id="drugusage" name="drugusage" class="form-control">
          <?php
			do {  
			?>
					  <option value="<?php echo $row_rs_drugusage['drugusage']?>" ><?php echo  $row_rs_drugusage['shortlist'];
			?></option>
					  <?php
			} while ($row_rs_drugusage = mysql_fetch_assoc($rs_drugusage));
			  $rows = mysql_num_rows($rs_drugusage);
			  if($rows > 0) {
				  mysql_data_seek($rs_drugusage, 0);
				  $row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
			  }
			?>

            </select>
        </div>
	  
        <?php if(!isset($_GET['e_drugusage'])){ ?>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info col-sm-1" />
        <?php } else {?>
        <input type="submit" name="save2" id="save2" value="แก้ไข" class="btn btn-warning col-sm-1" />
      <?php } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
      </div>
      <!-- .form-group row -->
    </form>

  </div>
  <!-- .card-body -->
</div>
<!-- .card -->
 <div style="padding-top:20px;">
<?php if ($totalRows_rs_medplan > 0) { // Show if recordset not empty ?>
<table id="table" class="table table-striped table-bordered row-border hover " >
<thead>
    <tr>
      <td  align="center">id</td>
      <td >drugname</td>
      <td  align="center">ประเภทยา</td>
      <td  align="center">วิธีใช้</td>
      <td  align="center">&nbsp;</td>
    </tr>
 </thead>
 <tbody>
    <?php $i=0; do { $i++; ?>
    <tr>
      <td align="center" ><?php echo $i; ?></td>
      <td ><?php echo $row_rs_medplan['name']; ?></td>
      <td align="center" ><?php if($row_rs_medplan['med_plan_type']=="1"){ echo "ยากิน"; } else { echo "ยาฉีดและอื่นๆ"; } ?></td>
      <td align="center" ><?php echo $row_rs_medplan['shortlist']; ?></td>
      <td align="center" ><?php echo $row_rs_medplan['code']; ?> <button onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_med_plan.php?icode=<?php echo $row_rs_medplan['icode']; ?>&amp;do=delete'; }" class="btn btn-dark"><i class="far fa-trash-alt font16"></i></button></td>
    </tr>
    <?php } while ($row_rs_medplan = mysql_fetch_assoc($rs_medplan)); ?>
   </tbody>
  </table>
  <?php } // Show if recordset not empty ?>    

</div>

</div>
<!-- .container -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<script>
$(document).ready(function() {
    $('#table').DataTable();
    $('#table2').DataTable();
} );</script>

</body>
</html>
<?php
mysql_free_result($rs_drug);

mysql_free_result($rs_medplan);

mysql_free_result($rs_drugusage);
?>
