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
	$query_insert = "insert into ".$database_kohrx.".kohrx_drugusage_check (icode,code) value ('".$_POST['drugname']."','".$_POST['keyword']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drugusage_check (icode,code) value (\'".$_POST['drugname']."\',\'".$_POST['keyword']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if($_GET['do']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_drugusage_check where id ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());		

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drugusage_check where id =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

  echo "<script>window.location='drug_setting_usage.php';</script>";
  exit();

}

mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select u.id,concat(d.name,' ',d.strength) as drugname,u.code from ".$database_kohrx.".kohrx_drugusage_check u left outer join drugitems d on d.icode=u.icode order by drugname ASC";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาจะต้องตรวจสอบวิธีการใช้</title>
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

<body >
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  ตรวจสอบการสั่งใช้ยา  
  </div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="">
      <div class="form-group row">
      <label for="drugname" class="col-sm-3 col-form-label"><b>รายการยา</b></label>
      <div class="col-sm-9">
      <select name="drugname" class="form-control" id="drugname" onchange="drugtoicode(this.value)" onkeydown="setNextFocus('among');">
          <option value="">-</option>
          <?php do {  ?>
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
      </div>
      <!-- .form-group row -->
      <div class="form-group row">
      <label for="keyword" class="col-sm-3 col-form-label">keyword</label>
      <div class="col-sm-2">
      <input name="keyword" type="text" id="keyword" class="form-control" />
      </div>
      <div class="col-sm-2">
      <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info"  />
      </div>
      </div>
      <!-- .form-group row -->

    </form>
  </div>
  <!-- .card-body -->
</div>
<!-- .card -->
  

    <div  style="padding-top:10px;">
    <?php if ($totalRows_rs_drugusage > 0) { // Show if recordset not empty ?>
  <table  id="tables" class="table table-striped table-bordered row-border hover ">
    <thead>
    <tr>
      <td width="31" align="center" >id</td>
      <td width="342" >drugname</td>
      <td width="109" align="center" >keyword</td>
      <td width="109" align="center" ></td>      
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; ?>
    <tr >
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugusage['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_drugusage[code]"; ?></td>
      <td align="center"><i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_usage.php?do=delete&id=<?php echo $row_rs_drugusage['id']; ?>'; }"></i></td>
    </tr> <?php } while ($row_rs_drugusage = mysql_fetch_assoc($rs_drugusage)); ?>
    </tbody>
    </table>
    <?php } // Show if recordset not empty ?>
    </div>
    </div>
</body>

</html>
<?php
mysql_free_result($rs_drug);
?>
