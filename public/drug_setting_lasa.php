<?php require_once('Connections/hos.php'); 

if(isset($_POST['button9'])&&$_POST['button9']=="เพิ่ม"){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drug_lasa_label (icode) value ('$lasa_drug')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}
if(isset($_GET['do'])&&$_GET['do']=="spacial_delete"){
mysql_select_db($database_hos, $hos);
$query_insert = "delete from ".$database_kohrx.".kohrx_drug_lasa_label where icode ='$icode'";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}

mysql_select_db($database_hos, $hos);
$query_rs_sp1 = "SELECT concat(d.name,' ',d.strength) as drugname,s.icode FROM ".$database_kohrx.".kohrx_drug_lasa_label s left outer join s_drugitems d on s.icode=d.icode order by d.name ASC";
$rs_sp1 = mysql_query($query_rs_sp1, $hos) or die(mysql_error());
$row_rs_sp1 = mysql_fetch_assoc($rs_sp1);
$totalRows_rs_sp1 = mysql_num_rows($rs_sp1);

mysql_select_db($database_hos, $hos);
$query_rs_drug2 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_lasa_label) ORDER BY name ASC";
$rs_drug2 = mysql_query($query_rs_drug2, $hos) or die(mysql_error());
$row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
$totalRows_rs_drug2 = mysql_num_rows($rs_drug2);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยา LASA</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>
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

</head>

<body>
<div class="p-3">
<div class="card">
<div class="card-header">รายการยา LASA</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="drug_setting_lasa.php">
<div class="row">
    <div class="col-sm-auto">
    <select name="lasa_drug" id="lasa_drug" class="form-control">
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
</div>
    
<div class="p-3">
 <?php if ($totalRows_rs_sp1 > 0) { // Show if recordset not empty ?>
  <table id="tables" width="100%" border="0" class="table table-sm table-striped table-hover ">
    <thead>
      <tr>
      <td width="59" align="center" >ลำดับ</td>
      <td width="290" align="center" >ชื่อยา</td>
      <td width="233" align="center"  class="notexport"></td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; 	  ?>
    <tr>  
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo $row_rs_sp1['drugname']; ?></td>
      <td align="center" class="notexport" ><button class="btn btn-danger" onClick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_lasa.php?do=spacial_delete&amp;icode=<?php echo $row_rs_sp1["icode"]; ?>';}">ลบ</button></td>
      </tr>      <?php } while ($row_rs_sp1 = mysql_fetch_assoc($rs_sp1)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
</div>


</body>
</html>
<?php 
mysql_free_result($rs_drug2);


?>