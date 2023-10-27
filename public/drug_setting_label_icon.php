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
if(isset($_POST['save2'])&&($_POST['save2']=="แก้ไข")){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_label_icon_list set label_icon_id='".$_POST['icon']."',label_name='".$_POST['icon_name']."',label_color='".$_POST['label_color']."' where id='".$_POST['id']."'";
$rs_udpate = mysql_query($query_update, $hos) or die(mysql_error());
	if($rs_udpate){  echo "<script>window.location='drug_setting_label_icon.php';</script>";
    exit();
  
	}
}
if(isset($_GET['do'])&&($_GET['do']=="delete")){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_label_icon_list where id='".$_GET['id']."'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
echo "<script>window.location='drug_setting_label_icon.php';</script>";
    exit();	
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_label_icon_list (label_icon_id,label_name,label_color) value ('".$_POST['icon']."','".$_POST['icon_name']."','".$_POST['label_color']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
echo "<script>window.location='drug_setting_label_icon.php';</script>";	
exit();
}

if(!isset($_GET['e_id'])){
$condition="where id not in (select label_icon_id from  ".$database_kohrx.".kohrx_label_icon_list)";
}
mysql_select_db($database_hos, $hos);
$query_rs_icon = "select * from ".$database_kohrx.".kohrx_label_icon ".$condition." order by id ASC";
$rs_icon = mysql_query($query_rs_icon, $hos) or die(mysql_error());
$row_rs_icon = mysql_fetch_assoc($rs_icon);
$totalRows_rs_icon = mysql_num_rows($rs_icon);

mysql_select_db($database_hos, $hos);
$query_rs_icon2 = "select l.id,i.icon,l.label_name,i.icon_html,i.icon_name,l.label_color from ".$database_kohrx.".kohrx_label_icon_list l left outer join ".$database_kohrx.".kohrx_label_icon i on i.id=l.label_icon_id order by l.id ASC";
//echo $query_rs_icon2;
$rs_icon2 = mysql_query($query_rs_icon2, $hos) or die(mysql_error());
$row_rs_icon2 = mysql_fetch_assoc($rs_icon2);
$totalRows_rs_icon2 = mysql_num_rows($rs_icon2);

if(isset($_GET['e_id'])&&$_GET['e_id']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_icon3 = "select l.id,i.icon,l.label_name,i.icon_html,i.icon_name,l.label_color,l.label_icon_id from ".$database_kohrx.".kohrx_label_icon_list l left outer join ".$database_kohrx.".kohrx_label_icon i on i.id=l.label_icon_id where l.id='".$_GET['e_id']."' order by l.id ASC";
//echo $query_rs_icon3;
$rs_icon3 = mysql_query($query_rs_icon3, $hos) or die(mysql_error());
$row_rs_icon3 = mysql_fetch_assoc($rs_icon3);
$totalRows_rs_icon3 = mysql_num_rows($rs_icon3);
}
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
<link rel="stylesheet" href="include/fontawesome/css/all.css"/>
<script type="text/javascript">
    $(function() {
         
 
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
select {
  font-family: 'FontAwesome', 'Second Font name'
}
.fonawesome{
  font-family: 'FontAwesome', 'Second Font name'		
}
</style>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  กำหนดป้ายชื่อกำกับ
  </div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="">
      <div class="form-group row">
        <label for="drugusage" class="col-sm-auto col-form-label">icon</label>
        <div class="col-sm-auto">
		<select id="icon" name="icon" class="form-control">
			<?php do{ ?>
			<option class="font20" value="<?php echo $row_rs_icon['id']; ?>" <?php if ($row_rs_icon['id']==$row_rs_icon3['label_icon_id']) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_icon['icon']." ".$row_rs_icon['icon_name']; ?></option>
			<?php }while($row_rs_icon = mysql_fetch_assoc($rs_icon)); ?>
		</select> 
        </div>
        <label for="icon_name" class="col-sm-auto col-form-label">ชื่อ icon</label>
        <div class="col-sm-auto">
        <input name="icon_name" type="text" class="form-control" id="icon_name" maxlength="10" value="<?php echo $row_rs_icon3['label_name']; ?>" /> 
        </div>
        <label for="color" class="col-sm-auto col-form-label">สี icon</label>
        <div class="col-sm-auto">
			<select name="label_color" class="form-control" id="label_color" >
				<option value="light" <?php if ($row_rs_icon3['label_color']=="light") {echo "selected=\"selected\"";} ?> class="bg-ligh"><span class="bg-light">light</span></option>
				<option value="info" class="bg-info" <?php if ($row_rs_icon3['label_color']=="info") {echo "selected=\"selected\"";} ?>>info</option>
				<option value="primary" class="bg-primary" <?php if ($row_rs_icon3['label_color']=="primary") {echo "selected=\"selected\"";} ?>><span class="bg-primary">primary</span></option>
				<option value="success" class="bg-success" <?php if ($row_rs_icon3['label_color']=="success") {echo "selected=\"selected\"";} ?>><span class="bg-success">success</span></option>
				<option value="warning" class="bg-warning" <?php if ($row_rs_icon3['label_color']=="warning") {echo "selected=\"selected\"";} ?>><span class="bg-warning">warning</span></option>
				<option value="danger" class="bg-danger" <?php if ($row_rs_icon3['label_color']=="danger") {echo "selected=\"selected\"";} ?> ><span class="bg-danger">danger</span></option>
				<option value="secondary" class="bg-secondary" <?php if ($row_rs_icon3['label_color']=="secondary") {echo "selected=\"selected\"";} ?>><span class="bg-secondary">secondary</span></option>
				<option value="dark" class="bg-dark" <?php if ($row_rs_icon3['label_color']=="dark") {echo "selected=\"selected\"";} ?>><span class="bg-dark">dark</span></option>
			</select> 
        </div>		  
        <?php if(!isset($_GET['e_id'])){ ?>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info col-sm-1" />
        <?php } else {?>
        <input type="submit" name="save2" id="save2" value="แก้ไข" class="btn btn-warning col-sm-1" />
      <?php } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['e_id']; ?>" />
      </div>
      <!-- .form-group row -->
    </form>

  </div>
  <!-- .card-body -->
</div>
<!-- .card -->
 <div style="padding-top:20px; max-width: 500px">
<?php if($totalRows_rs_icon2<>0){ ?>	 
 <table id="tables" class="table table-striped table-bordered table-hover ">
 <thead>
  <tr >
    <td align="center">no.</td>
    <td align="left">icon</td>
    <td align="center"></td>
  </tr>
</thead>
<tbody>
	<?php $i=0; do{ $i++; ?>
      <tr >
      <td align="center" ><?php echo $i; ?></td>
      <td align="left" style="padding-left:10px"><span class="badge badge-<?php echo $row_rs_icon2
	['label_color']; ?> font14 p-2"><i class="<?php echo $row_rs_icon2['icon_html']; ?> font20"></i>&nbsp;<?php echo $row_rs_icon2['label_name']; ?></span></td>
      <td width="41" align="center" ><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_label_icon.php?e_id=<?php echo $row_rs_icon2['id']; ?>';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_label_icon.php?do=delete&amp;id=<?php echo $row_rs_icon2["id"]; ?>';}"></i></nobr>
      </td>
      </tr>
	<?php }while($row_rs_icon2 = mysql_fetch_assoc($rs_icon2)); ?>
	
</tbody>
</table>
<?php } ?>	 
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


mysql_free_result($rs_icon);
?>
