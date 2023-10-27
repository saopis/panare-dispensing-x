<?php require_once('Connections/hos.php'); ?>
<? $today=date('Y-m-d'); ?>
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

include('include/function.php');

if($_GET['action']=="save"){
	mysql_select_db($database_hos, $hos);
	$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_return (icode,qty,recdate) value ('".$_GET['icode']."','".$_GET['among']."','".date_th2db($_GET['recdate'])."')";
	$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_return (icode,qty,recdate) value (\'".$_GET['icode']."\',\'".$_GET['among']."\',\'".date_th2db($_GET['recdate'])."\')')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	
	}
	
if($_GET['action']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_rs_delete = "delete from ".$database_kohrx.".kohrx_drug_return where icode='".$_GET['icode']."' and recdate='".$_GET['recdate']."' ";
	$rs_delete = mysql_query($query_rs_delete, $hos) or die(mysql_error());	

    mysql_select_db($database_hos, $hos);
	$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_return where icode=\'".$_GET['icode']."\' and recdate=\'".$_GET['recdate']."\'')";
	$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

}

mysql_select_db($database_hos, $hos);
$query_rs_druglist = "SELECT d.icode,sum(d.qty) as sumqty,concat(d2.name,' ',d2.strength) as drugname,d2.units,sum(d2.unitcost*(d.qty)) as cost,d.recdate FROM ".$database_kohrx.".kohrx_drug_return d left outer join drugitems d2 on d2.icode=d.icode where  recdate between '".($_GET['datestart'])."' and '".($_GET['dateend'])."' group by d.icode ORDER BY id DESC ";
$rs_druglist = mysql_query($query_rs_druglist, $hos) or die(mysql_error());
$row_rs_druglist = mysql_fetch_assoc($rs_druglist);
$totalRows_rs_druglist = mysql_num_rows($rs_druglist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานบันทึกยาคืน</title>
<?php include('java_css_online.php'); ?>
<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: bottom"></caption>');

	$('#example').DataTable( {
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            data = api.column( 5 ).data();
            total = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Total over this page
            data = api.column( 5, { page: 'current'} ).data();
            pageTotal = data.length ?
                data.reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                } ) :
                0;
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                pageTotal.toFixed(2) +' บาท (ทั้งหมด  '+ total.toFixed(2) +' บาท)'
            );
        },
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
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
                           columns: [ 0, 1, 2, 3, 4,5 ] //Your Colume value those you want
                           }
                         }
			
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
});
</script>
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}
th { white-space: nowrap; }
</style>
</head>

<body>
<?php if ($totalRows_rs_druglist > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="table display table-striped table-hover" id="example">
	 <thead>
    <tr>
      <td  align="center">no.</td>
      <td  align="center">วันที่</td>
      <td  align="center">รายการ</td>
      <td  align="center">จำนวน</td>
      <td  align="center">หน่วย</td>
      <td  align="center">มูลค่า</td>
		<td  align="center">&nbsp;</td>
    </tr>
		 </thead>
	  <tbody>
    <?php $i=0; do { $i++; ?><tr>
      
      <td align="center"><?php echo $i; ?></td>
      <td align="center"><?php echo dateThai($row_rs_druglist['recdate']); ?></td>
      <td align="left"><?php echo $row_rs_druglist['drugname']; ?></td>
      <td align="center"><?php echo $row_rs_druglist['sumqty']; ?></td>
      <td align="center"><?php echo $row_rs_druglist['units']; ?></td>
      <td align="center"><?php echo $row_rs_druglist['cost']; ?></td>
      <td align="center"><a href="javascript:if(confirm('คุณต้องการลบข้อมูลหรือไม่?')==true){ action_delete('<?php echo $row_rs_druglist['icode']; ?>','<?php echo $row_rs_druglist['recdate']; ?>');}"><i class="fas fa-eraser" style="font-size: 20px;"></i></a></td>
      
    </tr> <?php } while ($row_rs_druglist = mysql_fetch_assoc($rs_druglist)); ?>
		 </tbody>
	 <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">รวม :</th>
                <th></th>
            </tr>
        </tfoot>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php

mysql_free_result($rs_druglist);
?>
