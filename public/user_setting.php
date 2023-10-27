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

include('include/function_sql.php');

if(isset($_GET['action'])&&($_GET['action']=="edit")){
		mysql_select_db($database_hos, $hos);
		$query_update = "update ".$database_kohrx.".kohrx_user_setting set right_opd='".$_GET['OPD']."',right_ipd='".$_GET['IPD']."',right_admin='".$_GET['admin']."' where doctorcode='".$_GET['doctor']."'";
		$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

}

if(isset($_GET['delete'])&&($_GET['delete']=="confirm")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_user_setting  where doctorcode='".$_GET['id']."'";
		$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());

}


if(isset($_GET['action'])&&($_GET['action']=="save")){
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_user_setting (doctorcode,right_opd,right_ipd,right_admin) value ('".$_GET['doctor']."','".$_GET['OPD']."','".$_GET['IPD']."','".$_GET['admin']."')";
		//echo $query_insert;exit();
		$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

	}

if($_GET['doctorcode']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_user_setting where doctorcode='".$_GET['doctorcode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

	$condition=" and code ='".$_GET['doctorcode']."'";
	}
else {
	$condition=" and code not in (select doctorcode from ".$database_kohrx.".kohrx_user_setting)";
	}
mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT code,name FROM doctor WHERE active='Y' ".$condition." ORDER BY name ASC";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

mysql_select_db($database_hos, $hos);
$query_rs_doctor_list = "SELECT k.*,d.name FROM ".$database_kohrx.".kohrx_user_setting k left outer join  doctor d on d.code=k.doctorcode ORDER BY name  ASC";
$rs_doctor_list = mysql_query($query_rs_doctor_list, $hos) or die(mysql_error());
$row_rs_doctor_list = mysql_fetch_assoc($rs_doctor_list);
$totalRows_rs_doctor_list = mysql_num_rows($rs_doctor_list);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_online.php'); ?> 
<!-- auto complete -->    
<script src="include/jquery/js/jquery-ui.js"></script>
<link rel="stylesheet" href="include/jquery/css/jquery-ui.css" type="text/css" />

<script>
$(document).ready(function(){
	$('#save').hide();		
	$('#cancel').hide();		
	
	$('#person').blur(function(){
		if($('#doctor').val()!=""){
			$('#save').show();
		}
		else{
			$('#save').hide();		
		}
	});

				 
    $('#tables').append('<caption style="caption-side: bottom"></caption>');

	$('#tables').DataTable( {
        scrollY:        '55vh',
        scrollCollapse: true,
        paging:    false,
		
		
        dom: 'Bfrtip',
		"bInfo": false,
		
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
                           columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
                           }
                         }
			
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
	
	
	        $( "#person" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "doctorcode_search.php?type=usersetting", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				$('#doctor').val("");
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#doctor").val(ui.item.id);
            }
        });
	
});

function button_save(){
		window.location.href='user_setting.php?action=save&doctor='+$('#doctor').val()+'&OPD='+($('#OPD').prop('checked') ? 'Y' : '')+'&IPD='+($('#IPD').prop('checked') ? 'Y' : '')+'&admin='+($('#admin').prop('checked') ? 'Y' : '');
	
}

function button_edit(){
		window.location.href='user_setting.php?action=edit&doctor='+$('#doctor').val()+'&OPD='+($('#OPD').prop('checked') ? 'Y' : '')+'&IPD='+($('#IPD').prop('checked') ? 'Y' : '')+'&admin='+($('#admin').prop('checked') ? 'Y' : '');
	
}
	
function button_cancel(){
		$('#person').val("");
		$('#person').removeAttr('readonly');
		$('#doctor').val("");
		$('input[type=checkbox]').prop('checked',false);
		$('#cancel').hide();
		$('#save').hide();
		$("#save").attr("onClick","button_save();");
		$("#save").html("บันทึก");

}	
	
function user_update(doctor,doctorname,opd,ipd,admin){
	
	$('#doctor').val(doctor);
	$('#person').val(doctorname);
	$('#person').prop("readonly",true);
	if(opd=="Y"){
		$('#OPD').prop("checked",true);
	}
	else{
		$('#OPD').prop("checked",false);		
	}
	if(ipd=="Y"){
		$('#IPD').prop("checked",true);
	}
	else{
		$('#IPD').prop("checked",false);		
	}
	if(admin=="Y"){
		$('#admin').prop("checked",true);
	}
	else{
		$('#admin').prop("checked",false);		
	}
	$("#save").attr("onClick","button_edit();");
	$("#save").html("แก้ไข");
	$("#save").show();
	$("#cancel").show();
	
}


</script>
<style>

.pull-left{float:left!important;}
.pull-right{float:right!important;}
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
    margin-right: 15px;	
    float: right;
}
html,body{
		overflow: hidden;	
	}
.ui-autocomplete {
	position:absolute;
		margin-top:50px;
		margin-left:10px;
		padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
		font-size:14px;

}
::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
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
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <h4><i class="fas fa-universal-access font20"></i>&ensp;ตั้งค่าสิทธิ์การใช้งาน</h4>
</nav>
<div class="p-3 mt-5">
	<div class="card mb-2 mt-2">
		<div class="card-body">
			<div class="form-group row" style="margin-bottom: -5px;">
				<label class="col-form-label-sm col-form-label col-sm-auto font-weight-bolder">ชื่อผู้ใช้งาน</label>
				<div class="col-sm-2"><input type="text" class="form-control form-control-sm thfont font14" id="person" name="person"/><input type="hidden" name="doctor" id="doctor" /></div>
				<label class="col-form-label col-form-label-sm col-sm-atuo font-weight-bolder">สิทธิ์การใช้งาน</label>
				<div class="col-sm-auto">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="OPD" name="OPD" value="Y">
    					<label class="custom-control-label" for="OPD">ผู้ป่วยนอก</label>		
					</div>
				</div>
				<div class="col-sm-auto">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="IPD" name="IPD" value="Y">
    					<label class="custom-control-label" for="IPD">ผู้ป่วยใน</label>		
					</div>
				</div>
				<div class="col-sm-auto">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="admin" name="admin" value="Y">
    					<label class="custom-control-label" for="admin">ADMIN</label>		
					</div>
				</div>
				<div class="col-sm-auto">
					<button class="btn btn-sm btn-primary btn-action" id="save" onClick="button_save();">บันทึก</button>&nbsp;
					<button class="btn btn-sm btn-danger" id="cancel" onClick="button_cancel();">ยกเลิก</button>
				</div>

			</div>
		</div>
	</div>

<table  id="tables" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:14px" >  
	<thead class="bg-light">
	  <tr>
		<th  rowspan="2" class="text-center" valign="middle" >ลำดับ</th>
		<th  rowspan="2" class="text-center" valign="middle" >ชื่อผู้ใช้งาน</th>
		<th colspan="3" class="text-center">สิทธิ์</th>
		<th  rowspan="2" class="text-center notexport" >แก้ไข</th>
	  </tr>
	  <tr>
		<th  class="text-center" ><i class="fas fa-user-shield text-warning"></i>&nbsp;ADMIN</th>
		<th  class="text-center" >OPD</th>
		<th  class="text-center" >IPD</th>
		</tr>
	</thead>
	<tbody>
  <?php $i=0; do { $i++; ?>
  <tr >
    <td align="center"><?php echo $i; ?></td>
    <td align="left" ><?php echo $row_rs_doctor_list['name']; ?></td>
    <td align="center"><?php if($row_rs_doctor_list['right_admin']=="Y"){ echo "<i class='fas fa-check font20 text-success'></i>"; } ?></td>
    <td align="center"><?php if($row_rs_doctor_list['right_opd']=="Y"){ echo "<i class='fas fa-check font20 text-success'></i>"; } ?>
      </td>
    <td align="center"><?php if($row_rs_doctor_list['right_ipd']=="Y"){ echo "<i class='fas fa-check font20 text-success'></i>"; } ?></td>

    <td align="center" class="notexport"><button class="btn btn-primary btn-sm" onClick="user_update('<?php echo $row_rs_doctor_list['doctorcode']; ?>','<?php echo doctorname($row_rs_doctor_list['doctorcode']); ?>','<?php echo $row_rs_doctor_list['right_opd']; ?>','<?php echo $row_rs_doctor_list['right_ipd']; ?>','<?php echo $row_rs_doctor_list['right_admin']; ?>')"><i class="fas fa-pen cursor" ></i>&nbsp;แก้ไข</button>&nbsp;<button class="btn btn-danger btn-sm" onclick="if(confirm('คุณต้องการลบรายการนี้จริงหรือไม่!')==true){window.location='user_setting.php?delete=confirm&id=<?php echo $row_rs_doctor_list['doctorcode']; ?>'};"><i class="far fa-trash-alt  cursor"></i>&nbsp;ลบ</button></td>
  </tr>
  <?php } while ($row_rs_doctor_list = mysql_fetch_assoc($rs_doctor_list)); ?>
	</tbody>
</table>
</div>
	

</body>
</html>
<?php
mysql_free_result($rs_doctor);

mysql_free_result($rs_doctor_list);

if($_GET['doctorcode']!=""){
mysql_free_result($rs_edit);
}
?>
