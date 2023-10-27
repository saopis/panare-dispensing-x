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
if($_GET['do']=="search"){
include('include/function.php');
$date1=date_th2db($_POST['date1']);
$date2=date_th2db($_POST['date2']);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

if($_GET['pttype']=="1"){
	$condition=" and an is null";
	}
else if($_GET['pttype']=="2"){
	$condition=" and an is not null";
	}
if($_GET['room']!=""){
	$condition.=" and k.room_id='".$_GET['room']."'";
	}
if($_GET['hn']!=""){
	$condition.=" and o.hn =LPAD('".$_GET['hn']."','".$row_setting[24]."','0')";
	}
if($_GET['drugusage']!=""){
	$condition.=" and o.drugusage='".$_GET['drugusage']."'";
}
mysql_select_db($database_hos, $hos);
$query_rs_search = "select sum(qty) as sumqty,'".$_GET['datestart']."' as seldate1,'".$_GET['dateend']."' as seldate2,'".$_GET['drug']."' as icode,concat(d.name,' ',d.strength) as drugname,d.units,unitcost,o.vn,o.an  from opitemrece o left outer join drugitems d on d.icode=o.icode left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=o.vn where concat(rxdate,' ',rxtime) between '".date_th2db($_GET['date1'])." ".$_GET['time1'].":00' and '".date_th2db($_GET['date2'])." ".$_GET['time2'].":00' and o.icode='".$_GET['drug']."'".$condition;
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);

mysql_select_db($database_hos, $hos);
$query_rs_search2 = "select concat(p.pname,p.fname,'  ',p.lname) as patientname,o.hn,u.shortlist,o.qty,concat(DATE_FORMAT(o.vstdate,'%d/%m/'),(DATE_FORMAT(o.vstdate,'%Y'))+543) as vstdate,o.vn,o.an from opitemrece o left outer join patient p on p.hn=o.hn left outer join drugusage u on u.drugusage=o.drugusage left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=o.vn where concat(rxdate,' ',rxtime) between '".date_th2db($_GET['date1'])." ".$_GET['time1'].":00' and '".date_th2db($_GET['date2'])." ".$_GET['time2'].":00' and o.icode='".$_GET['drug']."'".$condition." order by vstdate,hn ASC";
$rs_search2 = mysql_query($query_rs_search2, $hos) or die(mysql_error());
$row_rs_search2 = mysql_fetch_assoc($rs_search2);
$totalRows_rs_search2 = mysql_num_rows($rs_search2);

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายงานการใช้ยา: <?php echo $row_rs_search['drugname']; ?></title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: top;font-size:20px;" class=" font-weight-bord">รายงานการใช้ยา: <?php echo $row_rs_search['drugname']; ?>&ensp;ทั้งหมด&nbsp; <span class="badge badge-info font20"><?php echo number_format($row_rs_search['sumqty']); ?></span> <?php echo $row_rs_search['units']; ?>&nbsp;มูลค่า <span class="badge badge-success font20"><?php echo number_format($row_rs_search['unitcost']*$row_rs_search['sumqty'],2); ?></span> บาท</caption>');

	$('#example').DataTable( {
		
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		
        dom: 'Bfrtip',
		
        buttons: [  
				{
				extend: 'colvis',
				text: '<i class="fas fa-table"></i>&nbsp;Column',
				className: 'btn btn-default',
				titleAttr: 'COLOUMN',	
				columnText: function ( dt, idx, title ) {
					return (idx+1)+': '+title;
					}
				}
			,
            {
				extend: 'copy',
				text: '<i class="fas fa-copy"></i>&nbsp;Copy',
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'

					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>&nbsp;CSV',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>&nbsp;Excel',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}	
			, 
				{
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>&nbsp;PDF',
				className: 'btn btn-default',
				titleAttr: 'PDF',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
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
						   columns: ':not(.notexport)',
						   columns: ':visible'
						   
                           //columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
                           }
                         }
			
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
});
function indicator_hide(){
	$('#indicator').hide();
}
</script>
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}

</style>

</head>

<body>
<?php if ($totalRows_rs_search2 > 0) {  // Show if recordset not empty ?>
<div>ผู้ป่วยใช้ยา <strong class="font20 text-danger"><?php echo $row_rs_search['drugname']; ?></strong></div>
<table id="example" class="table table-striped table-bordered table-hover table-sm display " style="width:100%; font-size:14px">
    <thead>      
    <tr >
      <td align="center">ลำดับ</td>
      <td align="center">วันที่ได้รับ</td>
      <td align="center">HN</td>
      <td>ชื่อ</td>
      <td align="center">จำนวน(<?php echo $row_rs_search['units']; ?>)</td>
      <td align="center">วิธีใช้</td>
      <td align="center">ประเภทผู้ป่วย</td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; ?>
    <tr class="table_head_small">
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo $row_rs_search2['vstdate']; ?></td>
      <td align="center" ><?php echo $row_rs_search2['hn']; ?></td>
      <td class="text-center" ><?php echo $row_rs_search2['patientname']; ?></td>
      <td class="text-center"><?php echo $row_rs_search2['qty']; ?></td>
      <td ><?php echo $row_rs_search2['shortlist']; ?></td>
      <td class="text-center"><?php if($row_rs_search2['vn']!=""){echo "OPD"; }else{ echo "IPD"; } ?></td>
    </tr>
    <?php } while ($row_rs_search2 = mysql_fetch_assoc($rs_search2)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
	
<script>
    $(function() {
		$('#indicator').hide();
	});
</script>
	
</body>
</html>
<?php

if($_GET['do']=="search"){

    mysql_free_result($rs_search);
    mysql_free_result($rs_search2);

}
?>
