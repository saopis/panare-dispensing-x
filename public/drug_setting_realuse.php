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
$query_update = "update ".$database_kohrx.".kohrx_drugusage_realuse set real_use='".$_POST['real_use']."' where id='".$_POST['id']."'";
$rs_udpate = mysql_query($query_update, $hos) or die(mysql_error());
	if($rs_udpate){  echo "<script>window.location='drug_setting_realuse.php';</script>";
    exit();
  
	}
}
if(isset($_GET['do'])&&($_GET['do']=="delete")){
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_drugusage_realuse where id='".$_GET['id']."'";
$rs_delete = mysql_query($query_delete, $hos) or die(mysql_error());
echo "<script>window.location='drug_setting_realuse.php';</script>";
    exit();	
}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_drugusage_realuse (drugusage,real_use) value ('".$_POST['drugusage_id']."','".$_POST['real_use']."')";
$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
}

if(!isset($_GET['e_drugusage'])){
mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select shortlist,drugusage from drugusage where status='Y' and drugusage not in (select drugusage from ".$database_kohrx.".kohrx_drugusage_realuse)";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);
}
else {
mysql_select_db($database_hos, $hos);
$query_rs_drugusage = "select shortlist,drugusage from drugusage where status='Y' and drugusage ='".$_GET['e_drugusage']."'";
$rs_drugusage = mysql_query($query_rs_drugusage, $hos) or die(mysql_error());
$row_rs_drugusage = mysql_fetch_assoc($rs_drugusage);
$totalRows_rs_drugusage = mysql_num_rows($rs_drugusage);
	
}
mysql_select_db($database_hos, $hos);
$query_rs_real_use = "select r.id,u.shortlist,r.real_use,r.drugusage from ".$database_kohrx.".kohrx_drugusage_realuse r left outer join drugusage u on u.drugusage=r.drugusage order by id DESC";
$rs_real_use = mysql_query($query_rs_real_use, $hos) or die(mysql_error());
$row_rs_real_use = mysql_fetch_assoc($rs_real_use);
$totalRows_rs_real_use = mysql_num_rows($rs_real_use);
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

<script type="text/javascript">
    $(function() {
         
        $( "#drugusage" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
            minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drugusage_autocomplete.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){ // เมื่อมีการแสดงรายการ autocomplete
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
                // สำหรับทดสอบแสดงค่า เมื่อเลือกรายการ
//              console.log( ui.item ?
//                  "Selected: " + ui.item.label :
//                  "Nothing selected, input was " + this.value);
                $("#drugusage_id").val(ui.item.id); // เก็บ id ไว้ใน hiden element ไว้นำค่าไปใช้งาน
//                setTimeout(function(){
//                  $("#h_input_q").parents("form").submit(); // เมื่อเลือกรายการแล้วให้ส่งค่าฟอร์ม ทันที
//                },500);
            }
        });
 
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

</style>
</head>

<body>
<div class="p-3">
<div class="card" style="margin-top:10px">
  <div class="card-header">
  กำหนดเม็ดยาที่ต้องใช้ต่อวัน
  </div>
  <div class="card-body">
    <form id="form1" name="form1" method="post" action="">
      <div class="form-group row">
        <label for="drugusage" class="col-sm-2 col-form-label">วิธีการใช้ยา</label>
        <div class="col-sm-5">
        <input type="text" id="drugusage" name="drugusage" class="form-control" value="<?php if($_GET['e_drugusage']!=""){ echo $row_rs_drugusage['shortlist']; } ?>" <?php if($_GET['e_drugusage']!=""){ echo "readonly=\"readonly\""; } ?>  />
        <input type="hidden" id="drugusage_id" name="drugusage_id" value="<?php if($_GET['e_drugusage']!=""){ echo $row_rs_drugusage['code']; } ?>" />
        </div>
        <label for="real_use" class="col-sm-2 col-form-label">จำนวนเม็ด/วัน</label>
        <div class="col-sm-2">
        <input name="real_use" type="text" class="form-control" id="real_use" value="<?php echo $_GET['e_realuse']; ?>" /> 
        </div>
        <?php if(!isset($_GET['e_drugusage'])){ ?>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info col-sm-1" />
        <?php } else {?>
        <input type="submit" name="save2" id="save2" value="แก้ไข" class="btn btn-warning col-sm-1" />
      <?php } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
      </div>
      <!-- .form-group row -->
    </form>

  </div>
  <!-- .card-body -->
</div>
<!-- .card -->
 <div style="padding-top:20px;">
 <table id="tables" class="table table-striped table-bordered table-hover ">
 <thead>
  <tr >
    <td  width="5%" align="center">no.</td>
    <td  width="75%" align="center">drugusage</td>
    <td width="10%" align="center">จำนวนเม็ด/วัน</td>
    <td width="10%" align="center"></td>
  </tr>
</thead>
<tbody>
     <?php $i=0; do { $i++; 

	 ?>
      <tr >
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px"><?php echo $row_rs_real_use['shortlist']; ?></td>
      <td width="127" align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $row_rs_real_use['real_use']; ?></td>
      <td width="41" align="center" bgcolor="<?php echo $bgcolor; ?>"><nobr><i class="fas fa-edit" style="color:#333; font-size:18px; cursor:pointer;" onclick="window.location='drug_setting_realuse.php?e_drugusage=<?php echo $row_rs_real_use['drugusage']; ?>&amp;e_realuse=<?php echo $row_rs_real_use['real_use']; ?>&amp;id=<?php echo $row_rs_real_use['id']; ?>';"></i>&nbsp;<i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_realuse.php?do=delete&amp;id=<?php echo $row_rs_real_use["id"]; ?>';}"></i></nobr>
      </td>
      </tr>
        <?php } while ($row_rs_real_use = mysql_fetch_assoc($rs_real_use)); ?>
</tbody>
</table>
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
mysql_free_result($rs_drugusage);

mysql_free_result($rs_real_use);
?>
