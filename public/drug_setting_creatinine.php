<?php require_once('Connections/hos.php'); ?>
<?php 
if(isset($_POST['button8'])&&($_POST['button8']=="เพิ่ม"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_creatinine (icode,max_value,min_value,remark,detail,cr_min_value,cr_max_value) value ('".$_POST['drug']."','".$_POST['max_value']."','".$_POST['min_value']."','".$_POST['remark']."','".$_POST['detail']."','".$_POST['cr_min_value']."','".$_POST['cr_max_value']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_creatinine (icode,max_value,min_value,remark,detail,cr_min_value,cr_max_value) value (\'".$_POST['drug']."\',\'".$_POST['max_value']."\',\'".$_POST['min_value']."\',\'".$_POST['remark']."\',\'".$_POST['detail']."\',\'".$_POST['cr_min_value']."\',\'".$_POST['cr_max_value']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if(isset($_GET['do'])&&($_GET['do']=="drug_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drug_creatinine where id='".$_GET['id']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_creatinine where id=\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

  echo "<script>window.location='drug_setting_creatinine.php';</script>";
  exit();

}
if(isset($_GET['do'])&&($_GET['do']=="edit")){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "SELECT * from ".$database_kohrx.".kohrx_drug_creatinine where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
}

if(isset($_POST['update'])&&($_POST['update']=="แก้ไข"))
{
mysql_select_db($database_hos, $hos);
$query_rs_edit = "update ".$database_kohrx.".kohrx_drug_creatinine set icode='".$_POST['drug']."' ,min_value='".$_POST['min_value']."' ,max_value='".$_POST['max_value']."' ,remark='".$_POST['remark']."' ,detail='".$_POST['detail']."',cr_min_value='".$_POST['cr_min_value']."',cr_max_value='".$_POST['cr_max_value']."'  where id='".$_POST['eid']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drug_creatinine set icode=\'".$_POST['drug']."\' ,min_value=\'".$_POST['min_value']."\' ,max_value=\'".$_POST['max_value']."\' ,remark=\'".$_POST['remark']."\' ,detail=\'".$_POST['detail']."\',cr_min_value=\'".$_POST['cr_min_value']."\',cr_max_value=\'".$_POST['cr_max_value']."\'  where id=\'".$_POST['eid']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

  echo "<script>window.location='drug_setting_creatinine.php';</script>";
  exit();

}
mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y'  ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);

mysql_select_db($database_hos, $hos);
$query_rs_drug_creatinine = "select p.id,p.icode,concat(d.name,' ',d.strength) as drugname,p.min_value,p.max_value,p.remark,p.detail,p.cr_min_value,p.cr_max_value from ".$database_kohrx.".kohrx_drug_creatinine p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_creatinine = mysql_query($query_rs_drug_creatinine, $hos) or die(mysql_error());
$row_rs_drug_creatinine = mysql_fetch_assoc($rs_drug_creatinine);
$totalRows_rs_drug_creatinine = mysql_num_rows($rs_drug_creatinine);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ต้องระมัดระวังในผู้ป่วยที่มีภาวะไตบกพร่อง</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
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

			$('#remark').summernote({
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
                           columns: [ 0, 1 ,2,3,4,5 ] //Your Colume value those you want
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

</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  ยาที่ต้องระวังเรื่องของการทำงานของไต
  </div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="drug_setting_creatinine.php">
      <div class="form-group row">
        <label for="drug" class="col-sm-2 col-form-label">รายการยา</label>
        <div class="col-sm-10">
        <select name="drug" id="drug" class="form-control">
          <?php
          do {  
          ?>
            <option value="<?php echo $row_rs_drug3['icode']?>"<?php if (!(strcmp($row_rs_drug3['icode'], $row_rs_edit['icode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drug3['drugname']?></option>
            <?php
          } while ($row_rs_drug3 = mysql_fetch_assoc($rs_drug3));
            $rows = mysql_num_rows($rs_drug3);
            if($rows > 0) {
                mysql_data_seek($rs_drug3, 0);
              $row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
            }
          ?>
        </select>
        <input name="eid" type="hidden" id="eid" value="<?php echo $row_rs_edit['id']; ?>" />
        </div>
        <!-- end .col-sm -->  
      </div>
      <!-- end .form-group row -->
      <div class="form-group row">
      <label for="min_value" class="col-sm-3 col-form-label"><b>ค่าของ GFR ที่ทำการตรวจสอบ</b></label>
      <label for="min_value" class="col-sm-2 col-form-label">ค่าต่ำสุด GFR</label>
        <div class="col-sm-2" align="left">
          <input name="min_value" type="text" id="min_value" class="form-control" value="<?php echo $row_rs_edit['min_value']; ?>" />
        </div>
        <!-- end .col-sm -->  
          <label for="max_value" class="col-sm-2 col-form-label">ค่าสูงสุด GFR</label>
        <div class="col-sm-2" align="left">
        <input name="max_value" class="form-control" type="text" id="max_value" value="<?php echo $row_rs_edit['max_value']; ?>"  />
        </div>
        <!-- end .col-sm -->  
      </div>
      <!-- end .form-group row -->
      <div class="form-group row">
          <label for="cr_min_value" class="col-sm-3 col-form-label"><b>ค่าของ Serum Cr ที่ทำการตรวจสอบ</b></label>
          <label for="cr_min_value" class="col-sm-2 col-form-label">ค่าต่ำสุด Creatinin</label>
        <div class="col-sm-2" align="left">
          <input name="cr_min_value" type="text" id="cr_min_value" value="<?php echo $row_rs_edit['cr_min_value']; ?>" class="form-control" /> 
        </div>
        <!-- end .col-sm -->  
          <label for="cr_max_value" class="col-sm-2 col-form-label">ค่าสูงสุด Creatinin</label>
        <div class="col-sm-2" align="left">
          <input name="cr_max_value" type="text" id="cr_max_value" value="<?php echo $row_rs_edit['cr_max_value']; ?>" class="form-control" /> 
        </div>
        <!-- end .col-sm -->  
      </div>
      <!-- end .form-group row -->
      <div class="form-group row">
        <div class="col-sm-12" align="right" style="color:red;">
          ถ้าไม่คำนึงถึงค่านี้ให้ใส่ 0-50          
        </div>
        <!-- end .col-sm -->  
      </div>
      <!-- end .form-group row --> 
      <div class="form-group row">
      <label for="remark" class="col-sm-2 col-form-label">คำแนะนำ</label>  
        <div class="col-sm-10">
         <textarea name="remark" id="remark" class="form-control" ><?php echo $row_rs_edit['remark']; ?></textarea>
        </div>
      </div>
      <!-- end .form-group row -->       
      <div class="form-group row">
      <label for="detail" class="col-sm-2 col-form-label">คำอธิบาย</label>  
        <div class="col-sm-10" id="textarea">
       <textarea id="detail" name="detail" class="form-control">
       <?php echo $row_rs_edit['detail']; ?>
      </textarea>        
      </div>
      </div>
      <!-- end .form-group row -->  
      <div class="form-group row">
      <div class="col-sm-12" align="right">
      <?php if(!isset($_GET['do'])&&($_GET['do']!="edit")){
echo "<input name=\"button8\" type=\"submit\" id=\"button8\" value=\"เพิ่ม\" class=\"btn btn-primary btn-lg\" />"; } if(isset($_GET['do'])&&($_GET['do']=="edit")){ echo "<input name=\"update\" type=\"submit\" id=\"update\" value=\"แก้ไข\" class=\"btn btn-primary btn-lg\" />"; } ?>
      </div>
      </div>
      <!-- end .form-group row -->       
      </form>
  </div>
  <!-- end .card-body -->
</div>
<!-- end .card -->

<div style="padding-top:10px;">
<table   id="tables" class="table table-striped table-sm table-bordered table-hover ">
  <thead>
  <tr >
    <td  align="center">ลำดับ</td>
    <td  align="center">ชื่อยา</td>
    <td  align="center">Serum Cr.</td>
    <td  align="center">GFR</td>
    <td  align="center">คำแนะนำ</td>
    <td  align="center">รายละเอียด</td>
    <td  align="center">&nbsp;</td>
  </tr>
  </thead>
  <tbody>
  <?php $j=0; do { $j++; 
	  ?>
  <tr>
    <td align="center" ><?php echo $j; ?></td>
    <td align="left" ><?php echo "$row_rs_drug_creatinine[drugname]"; ?></td>
    <td align="center"><?php echo "$row_rs_drug_creatinine[cr_min_value]-$row_rs_drug_creatinine[cr_max_value]"; ?></td>
    <td align="center"><?php echo "$row_rs_drug_creatinine[min_value] - $row_rs_drug_creatinine[max_value]"; ?></td>
    <td align="left" ><?php echo "$row_rs_drug_creatinine[remark]"; ?></td>
    <td align="left" ><?php echo "$row_rs_drug_creatinine[detail]"; ?></td>
    <td align="center"><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_creatinine.php?id=<?php echo $row_rs_drug_creatinine['id']; ?>&do=edit';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_creatinine.php?do=drug_delete&amp;id=<?php echo $row_rs_drug_creatinine["id"]; ?>';}"></i></nobr></td>
  </tr>
  <?php } while ($row_rs_drug_creatinine = mysql_fetch_assoc($rs_drug_creatinine)); ?>
  </tbody>
</table>
</div>
</div>
</body>
</html>