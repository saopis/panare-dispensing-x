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
		$detail= str_replace("\n", "<br>\n", $_POST['detail']); 

	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_icd10 (icode,icd101,icd102,detail) value ('".$_POST['drugname']."','".$_POST['icd101']."','".$_POST['icd102']."','".$detail."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_icd10 (icode,icd101,icd102,detail) value (\'".$_POST['drugname']."\',\'".$_POST['icd101']."\',\'".$_POST['icd102']."\',\'".$detail."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if(isset($_GET['do'])&&($_GET['do']=="edit")){
	mysql_select_db($database_hos, $hos);
	$query_rs_edit = "select * from ".$database_kohrx.".kohrx_drug_icd10 where id='".$_GET['id']."'";
	$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
	$row_rs_edit = mysql_fetch_assoc($rs_edit);
	$totalRows_rs_edit = mysql_num_rows($rs_edit);		
}
if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){

			$detail= str_replace("\n", "<br>\n", $_POST['detail']); 

	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_drug_icd10 set icd101='".$_POST['icd101']."',icd102='".$_POST['icd102']."',detail='".$detail."' where id ='".$_POST['id']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drug_icd10 set icd101=\'".$_POST['icd101']."\',icd102=\'".$_POST['icd102']."\',detail=\'".$detail."\' where id =\'".$_POST['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if(isset($_GET['do'])&&($_GET['do']=="delete")){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_drug_icd10 where id ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());		

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_icd10 where id =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	  
	echo "<script>window.location='drug_setting_icd10.php';</script>";
  	exit();

}


mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select u.id,u.icode,concat(d.name,' ',d.strength) as drugname,u.icd101,u.icd102,u.detail from ".$database_kohrx.".kohrx_drug_icd10 u left outer join drugitems d on d.icode=u.icode order by drugname ASC";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยาที่ห้ามสั่งกับโรคที่ระบุใน ICD10</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<?php include('java_css_online.php'); ?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
			
			$('#detail').summernote({
			  //minHeight: 180,
			  placeholder: 'พิมพ์ข้อความตรงนี้ ...',
			  focus: false,
			  airMode: false,
			  fontNames: ['Roboto', 'Calibri', 'Times New Roman', 'Arial'],
			  fontNamesIgnoreCheck: ['Roboto', 'Calibri'],
			  dialogsInBody: true,
			  dialogsFade: true,
			  disableDragAndDrop: false,
			  toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline', 'clear']],
				['para', ['style', 'ul', 'ol', 'paragraph']],
				['fontsize', ['fontsize']],
				['font', ['strikethrough', 'superscript', 'subscript']],
				['color', ['color']],
				['table', ['table']],
				['height', ['height']],
				['misc', ['undo', 'redo', 'print', 'help', 'fullscreen']]
			  ],
			  popover: {
				air: [
				  ['color', ['color']],
				  ['font', ['bold', 'underline', 'clear']]
				]
			  },
			  print: {
				//'stylesheetUrl': 'url_of_stylesheet_for_printing'
			  }	});

});
</script>
<!-- include summernote css/js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.min.js"></script>
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
                           columns: [ 0, 1 ,2,3 ] //Your Colume value those you want
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

<script type="text/javascript">
function formEdit(a,b,c,d,e,f){
$('#id').val(a);
$('#do').val(b);
$('#drugname').val(c);
$('#icd101').val(d);
$('#icd102').val(e);
$('#detail').val(f);
$('#drugname').attr('disabled', 'disabled');
$('#button').val('แก้ไข');
document.getElementById('button').onclick=function(){formSubmit('edit','displayDiv','indicator',a);};			

}

function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	 var URL = "drug_setting_icd10_list.php";	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	$('#drugname').removeAttr('disabled');
	$('#button').val("บันทึก"); 	
document.getElementById('button').onclick=function(){formSubmit('save','displayDiv','indicator');};
$('#id').val('');
$('#do').val('');
$('#drugname').val('');
$('#icd101').val('');
$('#icd102').val('');
$('#detail').val('');
			
	}
</script>
<script type="text/javascript" src="include/nicEdit1.js"></script>
<script type="text/javascript">
//	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

</head>
<body >
<div style="padding:10px;">
	<div class="card">
    	<div class="card-header">ตรวจสอบการห้ามสั่งใช้ยากับโรคที่เป็น</div>
        <div class="card-body">
		<form id="form1" name="form1" method="post" action="drug_setting_icd10.php">
        <div class=" form-group row">
        	<label for="drugname" class="col-form-label col-sm-2">รายการยา</label>
            <div class="col-sm-4">
                    <select name="drugname" id="drugname"  class="form-control" <?php if($totalRows_rs_edit<>0){ echo "disabled=\"disabled\""; } ?>>
          <option value="">-</option>
          <?php
do {  
?>
         <option value="<?php echo $row_rs_drug['icode']?>" <?php if(!(strcmp($row_rs_drug['icode'], $row_rs_edit['icode']))) {echo "selected=\"selected\"";} ?>><?php echo  $row_rs_drug['name']." ".$row_rs_drug['strength'];
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
        	<label for="icd101" class="col-form-label col-sm-1">ICD10</label>
            <div class="col-sm-2">
			<input name="icd101" type="text" id="icd101" class="form-control" value="<?php echo $row_rs_edit['icd101']; ?>" />
            </div>
         	<label for="icd101" class="col-form-label col-sm-auto">ถึง</label>
            <div class="col-sm-2">
			<input name="icd102" type="text" id="icd102" class="form-control" value="<?php echo $row_rs_edit['icd102']; ?>" />
            </div>
			
        </div>
        <!-- .row -->
        <div class=" form-group row">
        	<label for="detail" class="col-form-label col-sm-2">รายละเอียด</label>
            <div class="col-sm-10">
			<textarea name="detail" id="detail" class="form-control" ><?php echo $row_rs_edit['detail']; ?></textarea>
            </div>		
        </div>
        <div class=" form-group row">
        	<label for="detail" class="col-form-label col-sm-2"></label>
            <div class="col-sm-2">
			<input type="submit" name="save" id="save" value="<?php if(isset($_GET['do'])&&($_GET['do']=="edit")){ echo "แก้ไข"; } else { echo "บันทึก"; } ?>" class="btn btn-info"  >
            </div>		
        </div>        
        <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" name="do" id="do" />
        
        </form>	
        </div>
        <!-- .card-body -->
    </div>
    <!-- .card -->
<?php if ($totalRows_rs_drugusage > 0) { // Show if recordset not empty ?>
<div style="margin-top:10px;">
<table   id="tables" class="table table-striped table-bordered table-hover table-sm " >
<thead>
    <tr>
      <td width="5%"  align="center" >id</td>
      <td width="20%" >drugname</td>
      <td width="10%" align="center" >icd10</td>
      <td width="55%" align="center" >รายละเอียด</td>
      <td width="10%"  align="center" >&nbsp;</td>
    </tr>
</thead>
<tbody>
    <?php $i=0; do {  ?>
    <?php $i++; ?>
    <tr >
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugusage['drugname']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php print $row_rs_drugusage['icd101']."-".$row_rs_drugusage['icd102']; ?>
      </td>
      <td align="left" valign="top" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugusage['detail']; ?></td>
      <td align="center" valign="top" bgcolor="<?php echo $bgcolor; ?>"><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_icd10.php?id=<?php echo $row_rs_drugusage['id']; ?>&do=edit';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_icd10.php?do=delete&id=<?php echo $row_rs_drugusage['id']; ?>'; }"></i></nobr></td>
    </tr> 
    <?php } while ($row_rs_drugusage = mysql_fetch_assoc($rs_drugusage)); ?>
   </tbody>
  </table>
  </div>
  <?php } // Show if recordset not empty ?>

</div>

</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drugusage);

if(isset($_GET['do'])&&($_GET['do']=="edit")){
mysql_free_result($rs_edit);
}
?>
