<?php require_once('Connections/hos.php'); 

if(isset($_POST['button9'])&&$_POST['button9']=="เพิ่ม"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_warning_popup_item (icode) value ('$lasa_drug')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}
if(isset($_GET['do'])&&$_GET['do']=="spacial_delete"){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_warning_popup_item where icode ='$icode'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}

mysql_select_db($database_hos, $hos);
$query_rs_sp1 = "SELECT concat(d.name,' ',d.strength) as drugname,s.icode FROM ".$database_kohrx.".kohrx_warning_popup_item s left outer join s_drugitems d on s.icode=d.icode order by d.name ASC";
$rs_sp1 = mysql_query($query_rs_sp1, $hos) or die(mysql_error());
$row_rs_sp1 = mysql_fetch_assoc($rs_sp1);
$totalRows_rs_sp1 = mysql_num_rows($rs_sp1);

mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_warning_popup_item) ORDER BY name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยาที่ต้องเก็บให้พ้นแสง</title>
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>
	
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

	
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/i18n/defaults-*.min.js"></script>
	
<!-- bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
<!-- fontawesome -->
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://kit.fontawesome.com/1ed6ef1358.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
	
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
                           columns: [ 0, 1 ] //Your Colume value those you want
                           }
                         }
			
        ],

    } );
});
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

<div class="p-3">
<div class="card">
<div class="card-header">กำหนดรายการที่แสดง pop up แจ้งเตือน</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="drug_setting_popup_item.php" class="form-group" style="margin-bottom: -5px;">
<div class="row">
    <div class="col-sm-auto">
    <select name="lasa_drug" id="lasa_drug" class="selectpicker form-control"  data-container="body" data-live-search="true" title="กรุณาเลือกรายการ" data-hide-disabled="true">
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
    <div class="col-sm-auto">    
    <input name="button9" type="submit" class="btn btn-primary" id="button9" value="เพิ่ม" />
    </div>
</div>
</form>
</div>
</div>
<div class="p-3">
 <?php if ($totalRows_rs_sp1 > 0) { // Show if recordset not empty ?>
  <table id="tables" width="100%" border="0" class="table table-sm table-striped table-hover ">
    <thead>
    <tr>
      <td width="59" align="center">ลำดับ</td>
      <td width="290" align="center">ชื่อยา</td>
      <td width="233" align="center" class="notexport">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; 

	  ?><tr>
      
      <td align="center" ><?php echo $i; ?></td>
      <td  ><?php echo $row_rs_sp1['drugname']; ?></td>
      <td align="center" class="notexport" ><button class="btn btn-sm btn-danger" onClick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_popup_item.php?do=spacial_delete&amp;icode=<?php echo $row_rs_sp1["icode"]; ?>';}">ลบ</button></td>
      </tr>      <?php } while ($row_rs_sp1 = mysql_fetch_assoc($rs_sp1)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>	

</body>

	
	
</html>
<?php 
mysql_free_result($rs_drug2);


?>