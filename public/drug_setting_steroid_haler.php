<?php require_once('Connections/hos.php'); ?>
<?php 
if(isset($_POST['save'])&&($_POST['save']=="บันทึก"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_steroid_inhale_use (drugusage,puff_per_day) value ('".$_POST['drugusage_id']."','".$_POST['puff_per_day']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_steroid_inhale_use (drugusage,puff_per_day) value (\'".$_POST['drugusage_id']."\',\'".$_POST['puff_per_day']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
}
if(isset($_POST['button8'])&&($_POST['button8']=="เพิ่ม"))
{

		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_steroid_haler (icode,did,dose) value ('".$_POST['drug']."','','".$_POST['dose']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_steroid_haler (icode,did,dose) value (\'".$_POST['drug']."\',\'\',\'".$_POST['dose']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

if(isset($_GET['do'])&&($_GET['do']=="usage_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_steroid_inhale_use where drugusage='".$_GET['drugusage']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_steroid_inhale_use where drugusage=\'".$_GET['drugusage']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	
}
if(isset($_GET['do'])&&($_GET['do']=="drug_delete"))
{
		mysql_select_db($database_hos, $hos);
		$query_rs_del = "delete from ".$database_kohrx.".kohrx_drug_steroid_haler where icode='".$_GET['icode']."'";
		$rs_del = mysql_query($query_rs_del, $hos) or die(mysql_error());

	//insert replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_steroid_haler where icode=\'".$_GET['icode']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

}

mysql_select_db($database_hos, $hos);
$query_rs_drug3 = "SELECT icode,concat(name,strength) as drugname FROM s_drugitems WHERE icode like '1%' and istatus='Y' and icode not in (select icode from ".$database_kohrx.".kohrx_drug_steroid_haler) ORDER BY name ASC";
$rs_drug3 = mysql_query($query_rs_drug3, $hos) or die(mysql_error());
$row_rs_drug3 = mysql_fetch_assoc($rs_drug3);
$totalRows_rs_drug3 = mysql_num_rows($rs_drug3);

mysql_select_db($database_hos, $hos);
$query_rs_drug_steroid = "select p.icode,concat(d.name,' ',d.strength) as drugname,p.dose from ".$database_kohrx.".kohrx_drug_steroid_haler p left outer join s_drugitems d on d.icode=p.icode ORDER BY name ASC";
$rs_drug_steroid = mysql_query($query_rs_drug_steroid, $hos) or die(mysql_error());
$row_rs_drug_steroid = mysql_fetch_assoc($rs_drug_steroid);
$totalRows_rs_drug_steroid = mysql_num_rows($rs_drug_steroid);

mysql_select_db($database_hos, $hos);
$query_rs_drug_steroid_use = "select d.drugusage,d.shortlist,s.puff_per_day  from ".$database_kohrx.".kohrx_steroid_inhale_use s left outer join drugusage d on d.drugusage=s.drugusage";
$rs_drug_steroid_use = mysql_query($query_rs_drug_steroid_use, $hos) or die(mysql_error());
$row_rs_drug_steroid_use = mysql_fetch_assoc($rs_drug_steroid_use);
$totalRows_rs_drug_steroid_use = mysql_num_rows($rs_drug_steroid_use);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการยาพ่นสเตียรอยด์</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<?php include('java_css_online.php'); ?>
<link rel="stylesheet" href="include/jquery/css/jquery-ui.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>    
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">
    $(function() {
         
        $( "#drugusage" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
            minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "drugusage_autocomplete_steroid.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
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

<?php include('include/bootstrap/datatable_report.php'); ?>
<style>
.ui-autocomplete {
	padding-right:5px;
        max-height:200px !important;
        overflow: auto !important;
		max-width: 300px;
	}
/*   css ส่วนของรายการที่แสดง  */   
	/*  css  ส่วนปุ่มคลิกเลือกแสดงรายการทั้งหมด*/ 

</style>

</head>

<body>
<div class="row">
<div class="col">
<div class="p-1" style="margin-top:10px;">
<div class="card">
<div class="card-header">
<span class="badge badge-dark" style="font-size: 20px;">1</span>&emsp;รายการยาพ่นเสตียรอยด์ &nbsp;
** เพื่อการติดตามการจำนวนการใช้ยา
</div>
<div class="card-body">
<form id="form1" name="form1" method="post" action="" class="thfont">
<div class="form-group row">
<label for="drug" class="col-sm-2 col-form-label">รายการยา</label>
<div class="col-sm-10">
  <select name="drug" id="drug" class=" form-control">
    <?php
do {  
?>
    <option value="<?php echo $row_rs_drug3['icode']?>"><?php echo $row_rs_drug3['drugname']?></option>
    <?php
} while ($row_rs_drug3 = mysql_fetch_assoc($rs_drug3));
  $rows = mysql_num_rows($rs_drug2);
  if($rows > 0) {
      mysql_data_seek($rs_drug2, 0);
	  $row_rs_drug2 = mysql_fetch_assoc($rs_drug2);
  }
?>
  </select>
  </div>
  </div> 
  <div class="form-group row">
	<label for="dose" class="col-sm-2 form-check-label">จำนวน dose/อัน</label>
    <div class="col-sm-2">
  <input name="dose" type="text" id="dose" class="form-control" size="5" />
  </div>
    <input name="button8" type="submit" class=" btn btn-primary col-sm-1" id="button8" value="เพิ่ม" />
  </div>
  </form>
  </div>
  </div>
  
<div style="margin-top:20px;">
  <table width="100%"  id="example" class="table table-striped table-bordered table-hover table-sm" >
	<thead>
    <tr>
      <td width="48" align="center" >ลำดับ</td>
      <td width="327" align="center">ชื่อยา</td>
      <td width="109" align="center">จำนวน dose</td>
      <td width="87" align="center">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $j=0; do { $j++; 	  ?>
    <tr>
      <td align="center" ><?php echo $j; ?></td>
      <td align="center" ><?php echo "$row_rs_drug_steroid[drugname]"; ?></td>
      <td align="center" ><?php echo "$row_rs_drug_steroid[dose]"; ?></td>
      <td align="center" ><i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_steroid_haler.php?do=drug_delete&amp;icode=<?php echo $row_rs_drug_steroid["icode"]; ?>';}"></i></td>
    </tr>
    <?php } while ($row_rs_drug_steroid = mysql_fetch_assoc($rs_drug_steroid)); ?>
  </tbody>
  </table>
  </div>
</div>
</div>
<div class="col">
<div class="p-1" style="margin-top:10px;">
<div class="card">
<div class="card-header">
<span class="badge badge-dark" style="font-size: 20px;">2</span>&emsp;จำนวนการใช้ยาต่อวัน
</div>
<div class="card-body">
    <form id="form2" name="form1" method="post" action="">
      <div class="form-group row">
        <label for="drugusage" class="col-sm-auto col-form-label">วิธีการใช้ยา</label>
        <div class="col-sm-6">
        <input type="text" id="drugusage" name="drugusage" class="form-control" value="<?php if($_GET['e_drugusage']!=""){ echo $row_rs_drugusage['shortlist']; } ?>" <?php if($_GET['e_drugusage']!=""){ echo "readonly=\"readonly\""; } ?>  />
        <input type="hidden" id="drugusage_id" name="drugusage_id" value="<?php if($_GET['e_drugusage']!=""){ echo $row_rs_drugusage['code']; } ?>" />
        </div>
	</div>
	 <div class="form-group row">
        <label for="real_use" class="col-sm-auto col-form-label">จำนวนครั้ง/วัน</label>
        <div class="col-sm-auto">
        <input name="puff_per_day" type="text" class="form-control" id="puff_per_day" value="<?php echo $_GET['e_realuse']; ?>" /> 
        </div>
        <?php if(!isset($_GET['e_drugusage'])){ ?>
        <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-info col-sm-auto" />
        <?php } else {?>
        <input type="submit" name="save2" id="save2" value="แก้ไข" class="btn btn-warning col-sm-1" />
      <?php } ?>
      <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>" />
      </div>
      <!-- .form-group row -->
    </form>	
</div>	
</div>	
	<?php if($totalRows_rs_drug_steroid_use<>0){ ?>
	<div class="mt-2">
	<table width="100%"  id="example" class="table table-striped table-bordered table-hover table-sm" >
	<thead>
    <tr>
      <td width="48" align="center" >ลำดับ</td>
      <td width="327" align="center">วิธีใช้</td>
      <td width="109" align="center">จำนวน puff/วัน</td>
      <td width="87" align="center">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php $j=0; do { $j++; 	  ?>
    <tr>
      <td align="center" ><?php echo $j; ?></td>
      <td align="center" ><?php echo $row_rs_drug_steroid_use['shortlist']; ?></td>
      <td align="center" ><?php echo $row_rs_drug_steroid_use['puff_per_day']; ?></td>
      <td align="center" ><i class="fas fa-trash" style="color:#333; font-size:18px; cursor:pointer;" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_setting_steroid_haler.php?do=usage_delete&amp;drugusage=<?php echo $row_rs_drug_steroid_use["drugusage"]; ?>';}"></i></td>
    </tr>
    <?php } while ($row_rs_drug_steroid_use = mysql_fetch_assoc($rs_drug_steroid_use)); ?>
  </tbody>
  </table>
	</div>
	<?php } ?>
</div>
</div>
</body>
</html>
<?php 
mysql_free_result($rs_drug_steroid);
mysql_free_result($rs_drug_steroid_use);
mysql_free_result($rs_drug3);
?>