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

 if(isset($_GET['action'])&&($_GET['action']=="edit")){
	 
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_drug_monograph set monograph='".$_GET['detail']."',monograph_type='".$_GET['monograph_type']."' where icode= '".$_GET['icode']."'";
	$update = mysql_query($query_update, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_drug_monograph set monograph=\'".$_GET['detail']."\',monograph_type=\'".$_GET['monograph_type']."\' where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	 
	echo "<script>window.location='drug_setting_monograph.php';</script>";
	exit();
 }

 if(isset($_GET['action'])&&($_GET['action']=="save")){
     if($_GET['icode']!=""){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into  ".$database_kohrx.".kohrx_drug_monograph (icode,monograph,monograph_type) value ('".$_GET['icode']."','".$_GET['detail']."','".$_GET['monograph_type']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into  ".$database_kohrx.".kohrx_drug_monograph (icode,monograph,monograph_type) value (\'".$_GET['icode']."\',\'".$_GET['detail']."\',\'".$_GET['monograph_type']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
    
	echo "<script>window.location='drug_setting_monograph.php';</script>";
	exit();
	 }
	
}

if($_GET['action']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from  ".$database_kohrx.".kohrx_drug_monograph where icode ='".$_GET['id']."'";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());	
	
	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from  ".$database_kohrx.".kohrx_drug_monograph where icode =\'".$_GET['id']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	echo "<script>window.location='drug_setting_monograph.php';</script>";
	exit();
}

 if(isset($_GET['action'])&&($_GET['action']=="get_edit")){
    $condition=" and icode='".$_GET['icode']."'";
 }
else{
    $condition=" and icode not in (select icode from  ".$database_kohrx.".kohrx_drug_monograph)";
}

mysql_select_db($database_hos, $hos);
$query_rs_drugqty = "select concat(d.name,' ',d.strength) as drugname,u.icode,u.monograph,u.monograph_type from ".$database_kohrx.".kohrx_drug_monograph u left outer join drugitems d on d.icode=u.icode order by drugname ASC";
$rs_drugqty = mysql_query($query_rs_drugqty, $hos) or die(mysql_error());
$row_rs_drugqty = mysql_fetch_assoc($rs_drugqty);
$totalRows_rs_drugqty = mysql_num_rows($rs_drugqty);


mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT icode,name,strength FROM s_drugitems WHERE istatus='Y' and name not like '%คิด%' and name not like '%ต่อ%' and icode like '1%' ".$condition." ORDER BY name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาที่ต้องตรวจสอบจำนวนการสั่ง</title>
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
	<?php if((isset($_GET['monograph_type'])&&($_GET['monograph_type']==1))||!isset($_GET['mongraph_type'])){ ?>
	$('#url').show();
	$('#text').hide();
	<?php } ?>
	<?php if(isset($_GET['monograph_type'])&&($_GET['monograph_type']==2)){ ?>
	$('#url').hide();
	$('#text').show();
	<?php } ?>
	
	$('#monograph_type').change(function(){
    		if ($('#monograph_type').val()==1){
				$('#url').show();
				$('#text').hide();
			}
     		else {
				$('#url').hide();
				$('#text').show();
			}
		});
	
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
        		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}

    } );
});
</script>
<script type="text/javascript">
//<![CDATA[
	function redirect(url) {
		window.location.href = url;
	}
//]]>
    function action_save(){
			var detail;
		if($('#monograph_type').val()==1){
			detail=encodeURIComponent($('#link_url').val());
		}
		else{
			detail=encodeURIComponent($('#detail').val());
		}
            window.location.href='drug_setting_monograph.php?action=save&monograph_type='+$('#monograph_type').val()+'&icode='+$('#drugname').val()+'&detail='+detail;
    }
    function action_edit(){
			var detail;
		if($('#monograph_type').val()==1){
			detail=encodeURIComponent($('#link_url').val());
		}
		else{
			detail=encodeURIComponent($('#detail').val());
		}
            window.location.href='drug_setting_monograph.php?action=edit&monograph_type='+$('#monograph_type').val()+'&icode='+$('#drugname').val()+'&detail='+detail;
    }
    function get_edit(monograph_type){
		if(monograph_type=="1"){
			$('#url').show();
			$('#text').hide();
		}
		else if(monograph_type=="2"){
			$('#url').hide();
			$('#text').show();			
		}
    }
</script>

<style>
th { white-space: nowrap; }

	.pull-left{float:left!important;}
.pull-right{float:right!important;}
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
    margin-right: 15px;	
    float: right;
}

</style>

</head>

<body>
<div class="p-3" style="padding-top:10px;">
  <div class="card" style="margin-top:10px">
  <div class="card-header">
  ข้อมูลยารายตัว  
  </div>
  <div class="card-body">
      <div class="form-group row" id="item">
        <label for="drugname" class="col-sm-2 col-form-label"><b>รายการยา</b></label>
        <div class="col-sm-9">
        <select name="drugname" id="drugname" class="form-control">
          <?php
             if($_GET['action']!="get_edit"){ ?>
            <option value="">-</option>
            <?php } ?>
          <?php
            do {  
            ?>
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


        <div class="form-group row">
            <label for="detail" class="col-sm-2 col-form-label">ประเภท</label>	
			<div class="col-sm-2">
				<select class="form-control" id="monograph_type">
					<option value="1" <?php if (!(strcmp($_GET['monograph_type'], "1"))) {echo "selected=\"selected\"";} ?>>ลิงค์ URL</option>
					<option value="2" <?php if (!(strcmp($_GET['monograph_type'], "2"))) {echo "selected=\"selected\"";} ?>>ข้อความ</option>					
				</select>
			</div>

	  </div>

         <div class="form-group row" id="url">
            <label for="detail" class="col-sm-2 col-form-label">URL</label>	
			<div class="col-sm-10">
				<input type="text" class="form-control" value="<?php echo $_GET['detail']; ?>" id="link_url"/>
			</div>

	  </div>
        <div class="form-group row" id="text">
            <label for="detail" class="col-sm-2 col-form-label">รายละเอียด</label>
            <div class="col-sm-10">
            <textarea name="detail" id="detail" class="form-control" style="width: 100%;"><?php echo $_GET['detail']; ?></textarea>
            </div>
        </div>
         <div class="form-group row">
             <div class="col-sm-2"></div>
            <div class="col-sm-auto"><?php  if(isset($_GET['action'])&&($_GET['action']=="get_edit")){ ?><button class="btn btn-primary" id="btn-edit" onClick="action_edit();">แก้ไข</button>&nbsp;<button class="btn btn-danger" onClick="window.location.href='drug_setting_monograph.php'">ยกเลิก</button><?php } else { ?><buttn class="btn btn-primary" id="save" onClick="action_save();">บันทึก</buttn><?php } ?></div>
        </div>
       
      <!-- .form-group row -->
              
  </div>
  <!-- .card-body -->
  </div>
  <!-- .card -->
<div style="padding-top:10px;">
<?php if ($totalRows_rs_drugqty > 0) { // Show if recordset not empty ?>
  <table id="tables" class="table table-striped table-bordered table-hover ">
    <thead>
    <tr >
      <td width="31" align="center" >id</td>
      <td width="342" >drugname</td>
      <td width="109" align="center" >&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $i=0; do { $i++; ?>
    <tr >
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_drugqty['drugname']; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><i class="fas fa-edit font20" style="color:#333; font-size:18px; cursor:pointer;" onClick="window.location.href='drug_setting_monograph.php?action=get_edit&icode=<?php echo $row_rs_drugqty['icode']; ?>&detail=<?php echo $row_rs_drugqty['monograph']; ?>&monograph_type=<?php echo $row_rs_drugqty['monograph_type']; ?>';" ></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){ window.location='drug_setting_monograph.php?id=<?php echo $row_rs_drugqty['icode']; ?>&action=delete'; }"></i></td>
    </tr>
    <?php } while ($row_rs_drugqty = mysql_fetch_assoc($rs_drugqty)); ?>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
  </div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
mysql_free_result($rs_drugqty);
?>
