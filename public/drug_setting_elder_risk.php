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

if(isset($_POST['save'])&($_POST['save']=="บันทึก")){
	if(trim($_FILES["fileUpload"]["tmp_name"]) != "")
{
$length = 10;

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		$images = $_FILES["fileUpload"]["tmp_name"];
		$ext = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_EXTENSION);
		$filename=$randomString.".".$ext;
		copy($_FILES["fileUpload"]["tmp_name"],"upload/".$filename);
chmod("upload/".$filename, 0755);

	}

	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_elder_risk (icode,severity,rational,recommendation,age_range1,age_range2,file_link) value ('".$_POST['drug']."','".$_POST['severity']."','".$_POST['rational']."','".$_POST['recommendation']."','".$_POST['age1']."','".$_POST['age2']."','".$filename."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	}

if(isset($_POST['button2'])&($_POST['button2']=="แก้ไข")){
		if(trim($_FILES["fileUpload"]["tmp_name"]) != "")
{
	if($_POST['oldfile']!=""){
	unlink("upload/".$_POST['oldfile']);
	}
	$length = 10;

$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

		$images = $_FILES["fileUpload"]["tmp_name"];
		$ext = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_EXTENSION);
		$filename=$randomString.".".$ext;
		copy($_FILES["fileUpload"]["tmp_name"],"upload/".$filename);
$condition=",file_link='".$filename."'";

chmod("upload/".$filename, 0755);

	}


	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_drug_elder_risk set icode='$drug',severity='$severity',rational='$rational',recommendation='$recommendation',age_range1='$age1',age_range2='$age2'".$condition." where id='$id'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
	echo "<script>window.location='drug_setting_elder_risk.php';</script>";
	echo die();
}

if(isset($_GET['do'])&($_GET['do']=="delete")){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from ".$database_kohrx.".kohrx_drug_elder_risk  where id='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());
	
	echo "<script>window.location='drug_setting_elder_risk.php';</script>";
	echo die();

}

mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y'  ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);

mysql_select_db($database_hos, $hos);
$query_rs_elder = "select concat(d.name,' ',d.strength) as drugname,e.* from ".$database_kohrx.".kohrx_drug_elder_risk e left outer join drugitems d on d.icode=e.icode order by d.name ASC";
$rs_elder = mysql_query($query_rs_elder, $hos) or die(mysql_error());
$row_rs_elder = mysql_fetch_assoc($rs_elder);
$totalRows_rs_elder = mysql_num_rows($rs_elder);

if(isset($_GET['do'])&($_GET['do']=="edit")){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_drug_elder_risk where id='".$_GET['id']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยาที่ต้องระมัดระวังในผู้ป่วยสูงอายุ</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
			
			$('#recommendation').summernote({
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
			$('#rational').summernote({
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
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
<div class="card-header">ระบบบันทึกยาที่ต้องระวังใช้ในผู้ป่วยสูงอายุ</div>
  <div class="card-body">    	
        <!-- .card-header -->
                <form id="form1" name="form1" method="post" action="drug_setting_elder_risk.php">
                  <div class="form-group row">
                    <label for="drug" class="col-sm-2 col-form-label">รายการยา                    </label>
                    <div class="col-sm-10">					
                    <select name="drug" class=" form-control" id="drug">
					<?php do {  ?>
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
                    <input name="id" type="hidden" id="id" value="<?php echo $row_rs_edit['id']; ?>" />
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="age1" class="col-sm-2 col-form-label">ช่วงอายุ</label>
                    <div class="col-sm-1">	
                    <input name="age1" type="text" id="age1" class="form-control" value="<?php echo $row_rs_edit['age_range1']; ?>" />				
					</div>
                    <label for="age2" class="col-sm-1 col-form-label">ถึง</label>
                     <div class="col-sm-1">	
					<input name="age2" type="text" id="age2" value="<?php echo $row_rs_edit['age_range2']; ?>" class="form-control" />					
                    </div>
                   	            	
                </div>
                <div class="form-group row">
                    <label for="severity" class="col-sm-2 col-form-label">Severity</label>
                     <div class="col-sm-1">	
                        <select name="severity" id="severity" class="form-control">
                                <option value="1" <?php if (!(strcmp(1, $row_rs_edit['severity']))) {echo "selected=\"selected\"";} ?>>1</option>
                                <option value="2" <?php if (!(strcmp(2, $row_rs_edit['severity']))) {echo "selected=\"selected\"";} ?>>2</option>
                                <option value="3" <?php if (!(strcmp(3, $row_rs_edit['severity']))) {echo "selected=\"selected\"";} ?>>3</option>
                         </select>
                     </div>
                </div>
<div style="font-size:12px;">
<p>
ระดับ 1 (Mild)&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;= ให้ใช้ได้&nbsp; แต่ต้องใช้ตามข้อบ่งใช้ที่ชัดเจน หรือใช้ระยะสั้น&nbsp; หรือใช้อย่างมีการติดตามการใช้อย่างใกล้ชิด (Use within condition or short term use or with intensiv monitoring)</p>
      <p>ระดับ 2 (Moderate) = ควรหลีกเลี่ยง&nbsp;เนื่องจากมีทางเลือกอื่น (note recommend, avoid by using alternative choices)</p>
      <p>ระดับ 3 (Severe)&nbsp; &nbsp;= ไม่แนะนำให้ใช้เรื่องจากไม่เกิดประโยชน์ (Not recommendation, No benefit)                   
</p> 
</div>
                <div class="form-group row">
                    <label for="recommendation" class="col-sm-2 col-form-label">ข้อแนะนำ</label>
                     <div class="col-sm-10">
                     <textarea name="recommendation" id="recommendation" class="form-control" ><?php echo $row_rs_edit['recommendation']; ?></textarea>	
                     </div>
                </div>
                <div class="form-group row">
                    <label for="rational" class="col-sm-2 col-form-label">ข้อแนะนำ</label>
                     <div class="col-sm-10">
					<textarea name="rational" id="rational" class="form-control"><?php echo $row_rs_edit['rational']; ?></textarea>					 
                     </div>									
                </div>                
				<?php if($row_rs_edit['file_link']!=""){ ?>
                <div class="form-group row">
                    <label for="oldfile" class="col-sm-2 col-form-label">ไฟล์ที่อัพโหลดแล้ว</label>
                    <div class="col-sm-10">
                    <input name="oldfile" type="hidden" id="oldfile" class="form-control" value="<?php echo $row_rs_edit['file_link']; ?>" />
					</div>                   

                </div>
                <?php } ?>
                <div class="form-group row">
                    <label for="fileUpload" class="col-sm-2 col-form-label">PDF file</label>
                    <div class="col-sm-10">
                    <input type="file" name="fileUpload" id="fileUpload" class="form-control" /></td>
					</div>
                </div>
                <div class="form-group row">
                    <label for="save" class="col-sm-2 col-form-label">.</label>
                    <div class="col-sm-2">
                    <?php if(!isset($_GET['do'])){
?>
        <input type="submit" name="save" id="save" value="บันทึก" class=" btn btn-info" />
      <?php } ?>
      <?php if(isset($_GET['do'])&($_GET['do']=="edit")){
?>
      <input type="submit" name="save" id="save" value="แก้ไข" class=" btn btn-info" /><?php } ?>
					</div>
                </div>
			</form>
        </div>
        <!-- .body -->
    </div>
    <!-- .card -->
    <div style="padding-top:10px;">
<?php if ($totalRows_rs_elder > 0) { // Show if recordset not empty ?>

<table   id="tables" class="table table-striped table-sm table-bordered table-hover ">
  <thead>
  <tr >
      <td width="4%" align="center">ลำดับ</td>
      <td width="24%" align="center">รายการยา</td>
      <td width="10%" align="center">ช่วงอายุ</td>
      <td width="5%" align="center">ระดับ</td>
      <td width="24%" align="center">ข้อแนะนำ</td>
      <td width="26%" align="center">เหตุผลที่ควรหลีกเลี่ยง</td>
      <td width="7%" align="center">&nbsp;</td>
    </tr>
   </thead>
   <tbody>
    <?php $i=0; do { $i++; 
	?>
    <tr >
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_elder[drugname]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_elder[age_range1]-$row_rs_elder[age_range2]"; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_elder[severity]"; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_elder[recommendation]"; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_elder[rational]"; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>">
      <nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_elder_risk.php?do=edit&amp;id=<?php echo $row_rs_elder['id']; ?>';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_elder_risk.php?do=delete&amp;id=<?php echo $row_rs_elder['id']; ?>';}"></i></nobr>
</td>
    </tr>      
    <?php } while ($row_rs_elder = mysql_fetch_assoc($rs_elder)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
      </div>

</body>
</html>
<?php
mysql_free_result($rs_drug3);
if ($totalRows_rs_elder > 0) {
mysql_free_result($rs_elder);
}
if($do=="edit"){
mysql_free_result($rs_edit);
}
?>
