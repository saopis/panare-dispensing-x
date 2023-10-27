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
	
	if($_POST['consult']!=""){
		if($_POST['consult']=="2"){
		 $condition=",icode2='".$_POST['item']."'"; }
		else {$condition=",icode2=NULL";}
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_drug_elder_risk_record set consult='".$_POST['consult']."'".$condition." where id='".$_POST['id_record']."' ";
	$update = mysql_query($query_update, $hos) or die(mysql_error());
echo "<script>parent.$.fn.colorbox.close();</script>";
if(isset($_POST['rdu'])&&($_POST['rdu']=="Y")){
echo "<script>parent.window.location.reload();</script>";	
	}
exit();
	}
}
if(isset($_POST['delete'])&&($_POST['delete']=="ลบ")){
	mysql_select_db($database_hos, $hos);
	$query_update = "update ".$database_kohrx.".kohrx_drug_elder_risk_record set consult=NULL,icode2=NULL where id='".$_POST['id_record']."' ";
	$update = mysql_query($query_update, $hos) or die(mysql_error());

echo "<script>parent.$.fn.colorbox.close();</script>";
if(isset($_POST['rdu'])&&($_POST['rdu']=="Y")){
echo "<script>parent.window.location.reload();</script>";	
	}
exit();
}

mysql_select_db($database_hos, $hos);
$query_rs_edit = "select * from ".$database_kohrx.".kohrx_drug_elder_risk_record where vn='".$_GET['vn']."' and icode='".$_GET['icode']."'";
$rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
$row_rs_edit = mysql_fetch_assoc($rs_edit);
$totalRows_rs_edit = mysql_num_rows($rs_edit);

mysql_select_db($database_hos, $hos);
$query_rs_risk = "select concat(d.name,' ',d.strength) as drugname,e.* from ".$database_kohrx.".kohrx_drug_elder_risk e left outer join drugitems d on d.icode=e.icode where e.id='$id'";
$rs_risk = mysql_query($query_rs_risk, $hos) or die(mysql_error());
$row_rs_risk = mysql_fetch_assoc($rs_risk);
$totalRows_rs_risk = mysql_num_rows($rs_risk);

mysql_select_db($database_hos, $hos);
$query_rs_record = "SELECT * FROM ".$database_kohrx.".kohrx_drug_elder_risk_record WHERE vn='".$_GET['vn']."' and icode='".$_GET['icode']."'";
$rs_record = mysql_query($query_rs_record, $hos) or die(mysql_error());
$row_rs_record = mysql_fetch_assoc($rs_record);
$totalRows_rs_record = mysql_num_rows($rs_record);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT concat(name,' ',strength) as drugname,icode FROM drugitems WHERE istatus='Y' and icode !='".$_GET['icode']."' order by name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);


switch($row_rs_risk['severity']){
	case  1 :
	$msg="(Mild) = ให้ใช้ได้ แต่ต้องใช้ตามข้อบ่งใช้ที่ชัดเจน หรือใช้ระยะสั้น หรือใช้อย่างมีการติดตามการใช้อย่างใกล้ชิด (Use within condition or short term use or with intensiv monitoring)";
	break;
	case 2 :
	$msg="(Moderate) = ควรหลีกเลี่ยง เนื่องจากมีทางเลือกอื่น (note recommend, avoid by using alternative choices)";
	break;
	case 3 :
	$msg="(Severe) = ไม่แนะนำให้ใช้เรื่องจากไม่เกิดประโยชน์ (Not recommendation, No benefit)";
	break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>    
<script>
$(document).ready(function() {
<?php if($row_rs_edit['consult']==2){  ?>
  $('#item2').show();
  $('#item').show();	  
<?php }  else{  ?>
  $('#item2').hide();
  $('#item').hide();
<?php } ?>
  $('#consult').change(function(){
  if($('#consult').val()==2){
  $('#item2').show();	  
  $('#item').show();	  

  }
  else {
  $('#item2').hide();
  $('#item').val('');
  $('#item').hide();	  
	  }
	})  
});

</script>
</head>

<body>
<div align="center" class="p-3">
<p class="big_red16"><?php echo $row_rs_risk['drugname']; ?></p>

	<div class="card">
		<div class="card-header">บันทึกการปรึกษาแพทย์ผู้สั่งใช้</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<input name="id_record" type="hidden" id="id_record" value="<?php echo $row_rs_record['id']; ?>" />
      				<input name="rdu" type="hidden" id="rdu" value="<?php echo $_GET['rdu']; ?>" />
					<select name="consult" id="consult" class="form-control" >
					  <option value="" <?php if (!(strcmp("", $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>=== กรุณาเลือก ===</option>
					  <option value="1" <?php if (!(strcmp(1, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาระยะสั้น</option>
					  <option value="2" <?php if (!(strcmp(2, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>เลี่ยงไปใช้ทางเลือกอื่น</option>
					  <option value="3" <?php if (!(strcmp(3, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาแต่มีนัดติดตามดูอาการ</option>
			<option value="4" <?php if (!(strcmp(4, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาและไม่นัดติดตามดูอาการ</option>
					</select>					
				</div>
			</div>
			<div class="row mt-2" id="item2" <?php if($row_rs_edit['consult']==2){ echo "style=\"display;none\""; } ?>>
				<div class="col">
					<select name="item" id="item" <?php if($row_rs_edit['consult']==2){ echo "style=\"display;none\""; } else { echo "disable=\"disable\""; } ?> class="form-control" >
							  <?php
					do {  
					?>
							  <option value="<?php echo $row_rs_drug['icode']?>"<?php if (!(strcmp($row_rs_drug['icode'], $row_rs_edit['icode2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_drug['drugname']?></option>
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
			<div class="row mt-2">
				<div class="col text-left">
					<input type="button" class="btn btn-primary" name="save" id="save" value="บันทึก" />
							<?php if($row_rs_record['consult']!=""){ ?><input type="button" name="delete" class="btn btn-danger" id="delete" value="ลบ" /><?php } ?>	
				</div>
			</div>
		</div>
	</div>
	

<table width="100%" border="0" cellpadding="3" cellspacing="0" class="head_small_gray thfont font14">
  <tr>
    <td height="40" colspan="2" valign="middle" class="table_head_small_bord" style="color:red; font-size:16px;">รายละเอียด/คำอธิบาย</td>
  </tr>
  <tr>
    <td width="142" valign="top" class="table_head_small_bord">Severity</td>
    <td width="346" valign="top">ระดับ <?php echo $row_rs_risk['severity']; ?> <?php echo $msg; ?></td>
  </tr>
  <tr>
    <td valign="top" class="table_head_small_bord">เหตุผลที่ควรหลีกเลี่ยง</td>
    <td valign="top"><?php echo $row_rs_risk['rational']; ?></td>
  </tr>
  <tr>
    <td valign="top" class="table_head_small_bord">คำแนะนำ</td>
    <td valign="top"><?php echo $row_rs_risk['recommendation']; ?></td>
  </tr>
      <?php if($row_rs_risk['file_link']!=""){ ?><tr>
    <td valign="top" bgcolor="#F4F4F4" class="table_head_small_bord">reference</td>
    <td valign="top" bgcolor="#F4F4F4" ><a href="upload/<?php echo $row_rs_risk['file_link']; ?>" target="_new"><img src="images/Pdf_icon.png" width="28" height="30" border="0" align="absmiddle" /></a> <span class="small_red_bord"><?php echo $row_rs_risk['file_link']; ?></span></td>
  </tr><?php } ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_risk);

mysql_free_result($rs_record);

mysql_free_result($rs_drug);

mysql_free_result($rs_edit);
?>
