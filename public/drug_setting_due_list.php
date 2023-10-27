<?php require_once('Connections/hos.php'); 
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

if($_GET['action']=="save"){
mysql_select_db($database_hos, $hos);
$insert = "insert into ".$database_kohrx.".kohrx_due_drug (icode) value ('".$_GET['drug']."')";
$rs_insert = mysql_query($insert, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_due_drug (icode) value (\'".$_GET['drug']."\')')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
}

if($_GET['action']=="delete"){
mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_due_drug where icode='".$_GET['icode']."'";
$rs_delete = mysql_query($delete, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_drug where icode=\'".$_GET['icode']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_due_cause where icode='".$_GET['icode']."'";
$rs_delete = mysql_query($delete, $hos) or die(mysql_error());

//insert replicate_log		
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_due_cause where icode=\'".$_GET['icode']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

}

mysql_select_db($database_hos, $hos);
$query_rs_drug_list = "SELECT concat(d.name,d.strength) as drugname,d.icode from ".$database_kohrx.".kohrx_due_drug k left outer join drugitems d on d.icode=k.icode order by name";
$rs_drug_list = mysql_query($query_rs_drug_list, $hos) or die(mysql_error());
$row_rs_drug_list = mysql_fetch_assoc($rs_drug_list);
$totalRows_rs_drug_list = mysql_num_rows($rs_drug_list);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />

<script>
	$(document).ready(function(){
	});
function load_modal(id,drugname){
                $("#modal-body").load('drug_setting_due_edit.php?icode='+id, function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#modal-title').html('สาเหตุที่เลือกใช้&ensp;'+drugname);
                         $("#myModal").modal({
                                backdrop: 'static',
                                keyboard: false
                            });                    
                    if(statusTxt == "error")
                       alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });		
	
}


</script>
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

</head>

<body>
<div class="p-3">
<?php if ($totalRows_rs_drug_list > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0"  id="tables" class="table table-striped table-bordered table-sm table-hover " style="width:100%; ">
	<thead>
	  <tr>
      <td width="36" align="center" class="table_head_small_bord">ลำดับ</td>
      <td width="233" align="center" class="table_head_small_bord">รายการ</td>
      <td width="469" align="center" class="table_head_small_bord">เหตุผลในการใช้</td>
      <td width="38" align="center">&nbsp;</td>
    </tr>
	</thead>
    <tbody>
	  <?php $i=0; do { $i++; 

mysql_select_db($database_hos, $hos);
$query_rs_cause = "SELECT use_cause from ".$database_kohrx.".kohrx_due_cause where icode='".$row_rs_drug_list['icode']."'";
$rs_cause = mysql_query($query_rs_cause, $hos) or die(mysql_error());
$row_rs_cause = mysql_fetch_assoc($rs_cause);
$totalRows_rs_cause = mysql_num_rows($rs_cause);

	?>
    <tr class="grid">
      <td align="center" valign="top"><?php echo $i; ?></td>
      <td align="left" valign="top"><?php echo $row_rs_drug_list['drugname']; ?></td>
      <td >
        <?php if ($totalRows_rs_cause > 0) { // Show if recordset not empty ?>
          <?php $i=0; do { $i++; ?>
            <div class="text-left">
              <?php echo $i.". ".$row_rs_cause['use_cause']; ?>
            </div>
          <?php } while ($row_rs_cause = mysql_fetch_assoc($rs_cause)); ?>
		  <?php } // Show if recordset not empty ?>
      </td>
      <td align="center" valign="top"><button onClick="load_modal('<?php echo $row_rs_drug_list['icode']; ?>','<?php echo $row_rs_drug_list['drugname']; ?>');" style="cursor: pointer" class="btn btn-success btn-sm   ">แก้ไขเหตุผล</button>&ensp;<button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt font20" style="cursor: pointer" onClick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){due_drug_delete('<?php echo $row_rs_drug_list['icode']; ?>');}"></i></button></td>
    </tr>      
    <?php mysql_free_result($rs_cause);
 } while ($row_rs_drug_list = mysql_fetch_assoc($rs_drug_list)); ?>
	</tbody>
  </table>
  <?php } // Show if recordset not empty ?>
	</div>
</body>
</html>
<?php
mysql_free_result($rs_drug_list);

?>
