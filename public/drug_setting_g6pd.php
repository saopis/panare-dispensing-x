<?php require_once('Connections/hos.php'); ?>
<?php 
if(isset($_POST['button8'])&&($_POST['button8']=="เพิ่ม"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_g6pd (icode,detail) value ('".$_POST['drug']."','".$_POST['detail']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_g6pd (icode,detail) value (\'".$_POST['drug']."\',\'".$_POST['detail']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if(isset($_GET['do'])&&($_GET['do']=="drug_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drug_g6pd where icode='".$_GET['icode']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());
		
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_g6pd where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

echo "<meta http-equiv=\"refresh\" content=\"0;URL=drug_setting_g6pd.php\" />";
exit();
}
if(isset($_GET['do'])&&($_GET['do']=="edit")){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "SELECT * from ".$database_kohrx.".kohrx_drug_g6pd where icode='".$_GET['icode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
}

if(isset($_POST['update'])&&($_POST['update']=="แก้ไข"))
{
mysql_select_db($database_hos, $hos);
$query_rs_edit = "update ".$database_kohrx.".kohrx_drug_g6pd set icode='".$_POST['drug']."' ,detail='".$_POST['detail']."'  where icode='".$_POST['icode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drug_g6pd set icode=\'".$_POST['drug']."\' ,detail=\'".$_POST['detail']."\'  where icode=\'".$_POST['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}
if(!isset($_GET['do'])&&($_GET['do']!="edit")){
mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_g6pd) ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);
}

if(isset($_GET['do'])&&($_GET['do']=="edit")){
mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode ='".$_GET['icode']."'";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);
}

mysql_select_db($database_hos, $hos);
$query_rs_drug = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.detail from ".$database_kohrx.".kohrx_drug_g6pd p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ห้ามสั่งใช้กับผู้ป่วยที่เป็น G6PD</title>
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
                           columns: [ 0, 1 ,2 ] //Your Colume value those you want
                           }
                         }
        ],

    } );
});
</script>

</head>

<body>
<div class="p-3" style="padding-top:10px;">
<h3>
  รายการที่ห้ามสั่งใช้กับผู้ป่วยที่เป็น G6PD
</h3>
<form id="form1" name="form1" method="post" action="drug_setting_g6pd.php">
<div class="form-group row">
<label for="drug" class="col-sm-2 col-form-label">รายการยา</label>
<div class="col-sm-10">
      <select name="drug" id="drug" class="form-control">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_drug3['icode']?>" <?php if (!(strcmp($row_rs_drug3['icode'], $row_rs_edit['icode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drug3['drugname']?></option>
          <?php
} while ($row_rs_drug3 = mysql_fetch_assoc($rs_drug3));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
        </select>
      <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
      </div>
</div>
<div class="form-group row">
<label for="detail" class="col-sm-2 col-form-label">รายละเอียด</label>
<div class="col-sm-10">
<textarea name="detail" id="detail" class="form-control" style="width: 100%;">
       <?php echo $row_rs_edit['detail']; ?>
</textarea>
</div>
</div>
<div class="form-group row">
<label for="button8" class="col-sm-2 col-form-label">&nbsp;</label>
<div class="col-sm-10">
<?php if(!isset($_GET['do'])&&($_GET['do']!="edit")){
echo "<input name=\"button8\" type=\"submit\" class=\"btn btn-primary\" id=\"button8\" value=\"เพิ่ม\" />"; } if(isset($_GET['do'])&&($_GET['do']=="edit")){ echo "<input name=\"update\" type=\"submit\" class=\"btn btn-primary\" id=\"update\" value=\"แก้ไข\" />"; } ?>
</div>
<!-- col -->
</div>
<!-- .row -->
</form>
</div>
<!-- .container -->
<hr />
<div class="p-3">
  <table width="100%"  id="tables" class="table table-striped table-bordered table-hover" style="width:100%">
	<thead>
    <tr>
      <td width="42" align="center">ลำดับ</td>
      <td width="214" align="center">ชื่อยา</td>
      <td width="285" align="center">รายละเอียด</td>
      <td width="30" align="center">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $j=0; do { $j++; 
	    
	  ?>
    <tr>
      <td align="center" ><?php echo $j; ?></td>
      <td align="center" ><?php echo "$row_rs_drug[drugname]"; ?></td>
      <td align="left" ><?php echo "$row_rs_drug[detail]"; ?></td>
      <td align="center" ><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_g6pd.php?do=edit&icode=<?php echo $row_rs_drug['icode']; ?>';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_g6pd.php?do=drug_delete&amp;icode=<?php echo $row_rs_drug["icode"]; ?>';}"></i></nobr></td>
    </tr>
    <?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
    </tbody>
  </table>

</div>

</body>
</html>
<?php 
mysql_free_result($rs_drug);
mysql_free_result($rs_drug3);
?>