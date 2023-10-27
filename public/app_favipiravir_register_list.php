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

include('include/function.php');

if(empty($_POST['did'])){
if($_POST['id']!=""&&empty($_POST['action'])){
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "update ".$database_kohrx.".kohrx_favipiravir_use set use_time='".$_POST['use_time']."',use_date='".date_th2db($_POST['use_date'])."',in_out_method='".$_POST['in_out']."',qty='".$_POST['qty']."',doctor='".$_POST['doctor']."',remark='".$_POST['remark']."' where id='".$_POST['id']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}
else if($_POST['id']!=""&&$_POST['action']=="delete"){
	mysql_select_db($database_hos, $hos);
	$query_rs_update = "delete from ".$database_kohrx.".kohrx_favipiravir_use  where id='".$_POST['id']."'";
	$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
}
else if($_POST['id']==""&&$_POST['action']=="receive"){
		$qty=$_POST['qty'];
		$use_date=$_POST['use_date'];
		$use_time=$_POST['use_time'];
		$in_out=13;

		$query_insert = "insert into ".$database_kohrx.".kohrx_favipiravir_use (use_date,use_time,qty,in_out_method) value ('".date_th2db($use_date)."','".$use_time.":00',".$qty.",'".$in_out."') ";
		$insert = mysql_query($query_insert, $hos) or die(mysql_error());
}	
mysql_select_db($database_hos, $hos);
$query_rs_result = "select concat(pt.pname,pt.fname,' ',pt.lname) as ptname,pt.cid,o.* from ".$database_kohrx.".kohrx_favipiravir_use o left outer join patient pt on pt.hn=o.hn order by use_date ASC,use_time ASC";
//echo $query_rs_result;
$rs_result = mysql_query($query_rs_result, $hos) or die(mysql_error());
$row_rs_result = mysql_fetch_assoc($rs_result);
$totalRows_rs_result = mysql_num_rows($rs_result);

include('include/function_sql.php');
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
$(document).ready(function(){
				/////////////////////////
				$('#btn-delete').click(function(){
							swal({
								title:"ระบบกำลังประมวลผล", 
								text:"กรุณารอสักครู่...",
								icon: "https://uploads.toptal.io/blog/image/122376/toptal-blog-image-1489080120310-07bfc2c0ba7cd0aee3b6ba77f101f493.gif",
								buttons: false,      
								closeOnClickOutside: false,
								//timer: 3000,
								//icon: "success"
							});
					
							$.ajax({
							url : "app_favipiravir_register_list.php",
							type: "POST",
							data : {id:$('#did').val(),action:'delete'},
							success: function(data, textStatus, jqXHR)
							{
								//data - response from server
								$('#Modal').modal('hide');
								$('#main-app').html(data);
								
								swal.close();
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								//$('.throw_error').fadeIn(1000).show();
							}
							});					
				});
				///////////////
		$('#btn-cancel').click(function(){
			$('#Modal').modal('hide');
		});
});	
</script>	
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
	input[type=search]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;
	cursor: pointer;

}
</style>
<style>
@font-face {
    font-family: th_saraban;
    src: url(font/thsarabunnew-webfont.woff);
}
.thfont{
   font-family: th_saraban;
	}
</style>
<style>
html,body { height:100%; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
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

</style>	
</head>

<body>
<?php if($totalRows_rs_result<>0){ ?>	
<table  id="example" class="table table-striped table-bordered table-hover table-sm " style="width:100%; font-size:11px" >  
    <thead>
    <tr>
      <th align="center" >ลำดับ</th>
      <th align="center" >วัน/เดือน/ปี</th>
      <th align="center" >เวลาจ่าย</th>
      <th align="center" >ประเภทรับ/จ่าย</th>
      <th align="center" >จำนวน</th>
      <th align="center" >คงเหลือ</th>
      <th align="center" >ใหม่/เก่า</th>
      <th align="center" >ชื่อ-สกุลผู้ป่วย</th>
      <th align="center" >เลขบัตรประจำตัวผู้ป่วย</th>
      <th align="center" >ผู้บันทึก</th>
      <th align="center" >หมายเหตุ</th>
   	  <th></th>	
    </tr>
    </thead>
	
    <tbody>
    <?php $remain=0; $i=0;  do{ $i++; ?>
	<?php
	mysql_select_db($database_hos, $hos);
	$query_rs_new_old = "select hn from ".$database_kohrx.".kohrx_favipiravir_use where hn='".$row_rs_result['hn']."' and concat(use_date,' ',use_time) < '".$row_rs_result['use_date']." ".$row_rs_result['use_time']."'";
	//echo $query_rs_new_old;
	$rs_new_old = mysql_query($query_rs_new_old, $hos) or die(mysql_error());
	$row_rs_new_old = mysql_fetch_assoc($rs_new_old);
	$totalRows_rs_new_old = mysql_num_rows($rs_new_old);
	
	if($totalRows_rs_new_old<>0){
		$new_old="2";
	}
	else{
		$new_old="1";
	}
	mysql_free_result($rs_new_old);
	?>
    <?php 
	if($row_rs_result['in_out_method']=="12"||$row_rs_result['in_out_method']=="13"){
		$remain+=$row_rs_result['qty'];
	}
	else {
		$remain-=$row_rs_result['qty'];		
	}
        ?>
    <tr>
      <td align="center" ><?php echo $i; ?></td>			
      <td align="center" ><?php echo date_db2th2($row_rs_result['use_date']); ?></td>
      <td align="center" ><?php echo ($row_rs_result['use_time']); ?></td>      
	  <td align="center" ><?php if($row_rs_result['in_out_method']==13){?><i class="fas fa-circle text-danger"></i>&nbsp;<?php } ?><?php if($row_rs_result['in_out_method']==12){?><i class="fas fa-circle text-success"></i>&nbsp;<?php } ?><?php echo $row_rs_result['in_out_method']; ?></td>
      <td align="center" class="<?php if($row_rs_result['in_out_method']==13){ echo "text-danger"; } else if($row_rs_result['in_out_method']==12){ echo "text-success"; } ?>" ><?php echo $row_rs_result['qty']; ?></td>
      <td align="center" ><?php echo $remain; ?></td>
      <td align="center" ><?php echo $new_old; ?></td>
      <td align="center" ><?php echo $row_rs_result['ptname']; ?></td>
      <td align="center" ><?php echo $row_rs_result['cid']; ?></td>
      <td align="center" ><?php echo explode(' ',doctorname($row_rs_result['doctor']))[0]; ?></td>
      <td align="center" ><?php echo $row_rs_result['remark']; ?></td>
	  <td><?php if($row_rs_result['in_out_method']!=13){ ?><i class="fas fa-pen-square font18" style="cursor: pointer" onClick="ModalLoad('<?php echo $row_rs_result['id']; ?>','<?php echo $row_rs_result['hn']; ?>')"></i>&nbsp;<?php } ?><i class="fas fa-eraser font18" style="cursor: pointer" onClick="delete_record('<?php echo $row_rs_result['id']; ?>');"></i></td>			
    </tr>      
    <?php } while($row_rs_result = mysql_fetch_assoc($rs_result));?>    
    </tbody>
    </table>
	<div><strong>ประเภทการรับ : </strong>&nbsp;13=รับจากที่อื่น , 12=รับคืนจากผู้ป่วย , 21=จ่ายให้กับผู้ป่วย</div>
<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: bottom"></caption>');

	$('#example').DataTable( {
		
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		"pageLength": 15,
        dom: 'Bfrtip',
		columnDefs: [
            {
                targets: 1,
                className: 'noVis'
            }
        ],
        buttons: [  

            {
				extend: 'copy',
				text: '<i class="fas fa-copy"></i><span class="thfont font12">&nbsp;Copy</span>',
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i><span class="thfont font12">&nbsp;CSV</span>',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}			
			, 
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i><span class="thfont font12">&nbsp;Excel</span>',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}	
						
			,
                       {
                       extend: 'print',
					   text: '<i class="fas fa-print"></i><span class="thfont font12">&nbsp;Print</span>',
					   titleAttr: 'PRINT',
                       exportOptions: {
                          // stripHtml : false,
                           messageBottom: null,
						   columns: ':not(.notexport)',
						   columns: ':visible'

                           //columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
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
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}

</style>	<?php } ?>
	
<?php if(!empty($_POST['did'])){
	echo "<center>ต้องการลบรายการนี้จริงหรือไม่<br><button class='btn btn-danger btn-sm' id='btn-delete'>ใช่! ลบข้อมูล</button>&nbsp;<button class='btn btn-primary btn-sm' id='btn-cancel'>ไม่ลบ</button></center><input type='hidden' id='did' value='".$_POST['did']."' />";
} ?>
<script>
$(document).ready(function(){
$("#example").DataTable().page('last').draw('page');	
});	
</script>	
</body>
</html>
<?php if(empty($_POST['did'])){ ?>
<?php mysql_free_result($rs_result); ?>
<?php } ?>