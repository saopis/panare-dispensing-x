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

if(isset($_GET['action'])&&$_GET['action']=="delete2"){
mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_drug_use_change where icode='".$_GET['icode']."' and vn='".$_GET['vn']."' and hn='".$_GET['hn']."' and change_type='off'";
$drug_delete = mysql_query($delete, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_use_change where icode=\'".$_GET['icode']."\' and vn=\'".$_GET['vn']."\' and hn=\'".$_GET['hn']."\' and change_type=\'off\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());


    echo "<script>parent.$.fn.colorbox.close();parent.drug_off_load('".$_GET['hn']."','".$_GET['pdx']."','".$_GET['vstdate']."','".$_GET['vn']."');</script>";
    exit();

	}

if(isset($_GET['action'])&&$_GET['action']=="add"){
	//บันทึกข้อมูล
	$qty=$_GET['real_use']*$_GET['app_day'];
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type,qty) value ('".$_GET['vn']."','".$_GET['hn']."','".$_GET['icode']."','".$_GET['drugusage']."','".$_GET['doctor']."','off','".$_GET['qty']."') ";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_drug_use_change (vn,hn,icode,drugusage,doctor,change_type,qty) value (\'".$_GET['vn']."\',\'".$_GET['hn']."\',\'".$_GET['icode']."\',\'".$_GET['drugusage']."\',\'".$_GET['doctor']."\',\'off\',\'".$_GET['qty']."\')')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

    echo "<script>parent.$.fn.colorbox.close();parent.drug_off_load('".$_GET['hn']."','".$_GET['pdx']."','".$_GET['vstdate']."','".$_GET['vn']."');</script>";
    exit();

}

if(isset($_GET['action'])&&$_GET['action']=="delete"){
mysql_select_db($database_hos, $hos);
$delete = "delete from ".$database_kohrx.".kohrx_drug_use_change where id='$id'";
$drug_delete = mysql_query($delete, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from ".$database_kohrx.".kohrx_drug_use_change where id=\'$id\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	echo "<script>parent.$.fn.colorbox.close();parent.drug_list_load2('".$_GET['hn']."','hn');</script>";
exit();
	}
mysql_select_db($database_hos, $hos);
$query_drug_use_change = "select c.id,v.vn,v.vstdate,concat(d.name,' ',d.strength) as drugname,u.shortlist,date_format(v.vstdate,'%d/%m/%Y') as visitdate,change_type,dc.name as doctorname  from ".$database_kohrx.".kohrx_drug_use_change c left outer join vn_stat v on v.vn=c.vn left outer join drugitems d on d.icode=c.icode left outer join drugusage u on u.drugusage=c.drugusage left outer join doctor dc on dc.code=c.doctor where c.hn='$hn'  order by v.vstdate desc ";
$drug_use_change = mysql_query($query_drug_use_change, $hos) or die(mysql_error());
$row_drug_use_change = mysql_fetch_assoc($drug_use_change);
$totalRows_drug_use_change = mysql_num_rows($drug_use_change);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style>
html,body{overflow:hidden}
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

</style>
</head>
<body >
<nav class="navbar bg-info text-white">
  <!-- Navbar content -->
  <span class="card-title" style="padding-top:5px;">
<i class="fas fa-exchange-alt font20"></i>&ensp;รายการปรับเพิ่ม ลด เปลี่ยนแปลงวิธีใช้ยา
</span>
</nav>

<?php if ($totalRows_drug_use_change > 0) { // Show if recordset not empty ?>
<div style="">
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small" >
    <tr class=" table_head_small_white text-dark font_bord">
      <td width="3%" height="30" align="center" bgcolor="#C1E8F4" class=""><strong>ลำดับ</strong></td>
      <td width="15%" align="center" bgcolor="#C1E8F4"><strong>วันที่</strong></td>
      <td width="27%" align="center" bgcolor="#C1E8F4"><strong>ชื่อยา</strong></td>
      <td width="30%" align="center" bgcolor="#C1E8F4"><strong>วิธีใช้</strong></td>
      <td width="17%" align="center" bgcolor="#C1E8F4" >แพทย์</td>
      <td width="8%" align="center" bgcolor="#C1E8F4" class="">&nbsp;</td>
    </tr>
   </table>
  </div>
   <div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:440px;">
     <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">

    <?php $i=0; do {  $i++;
    if($bgcolor=="#F4FAFB") { $bgcolor="#D7F1FD"; $font="#F4FAFB"; } else { $bgcolor="#F4FAFB"; $font="#999999";  }

	switch($row_drug_use_change['change_type']){
		case "new":
		$picture="promotion_new.png";
		break;
		case "usage":
		$picture="icon_pc_optimization_big1.png";
		break;
		case "down":
		$picture="use_down.png";
		break;
		
		case "up":		
		$picture="use_up.png";
		break;

		case "off":		
		$picture="off.png";
		break;
		
	}
		?><tr class="grid5">
      
      <td height="30" width="3%" align="center" bgcolor="<?php echo $bgcolor; ?>" style="border-left:solid 1px #9AB6D8; <?php if($totalRows_drug_use_change==$i){echo "border-bottom:solid 1px #9AB6D8"; } ?>" <?php if($totalRows_drug_use_change==$i){echo "class=\"rounded_bottom_left\"";} ?>><?php echo $i; ?></td>
      <td align="center" width="15%" bgcolor="<?php echo $bgcolor; ?>" <?php if($totalRows_drug_use_change==$i){echo "style=\"border-bottom:solid 1px #9AB6D8\""; } ?>><?php echo dateThai($row_drug_use_change['vstdate']);  ?></td>
      <td align="center" width="27%" bgcolor="<?php echo $bgcolor; ?>"  <?php if($totalRows_drug_use_change==$i){echo "style=\"border-bottom:solid 1px #9AB6D8\""; } ?>><?php echo $row_drug_use_change['drugname']; ?></td>
      <td align="center" width="30%" bgcolor="<?php echo $bgcolor; ?>"  <?php if($totalRows_drug_use_change==$i){echo "style=\"border-bottom:solid 1px #9AB6D8\""; } ?>><?php echo $row_drug_use_change['shortlist']; ?></td>
      <td align="center" width="17%" bgcolor="<?php echo $bgcolor; ?>" style="border-right:solid 1px #9AB6D8; <?php if($totalRows_drug_use_change==$i){echo "border-bottom:solid 1px #9AB6D8"; } ?>"  ><?php echo "$row_drug_use_change[doctorname]"; ?></td>
      <td align="center" width="8%" bgcolor="<?php echo $bgcolor; ?>" style="border-right:solid 1px #9AB6D8; <?php if($totalRows_drug_use_change==$i){echo "border-bottom:solid 1px #9AB6D8"; } ?>"  <?php if($totalRows_drug_use_change==$i){echo "class=\"rounded_bottom_right\"";} ?>><img src="images/<?php echo $picture; ?>" width="35" height="35" align="absmiddle" />&ensp;<a href="JavaScript:valid();" onclick="if(confirm('ต้องการลบข้อมูลนี้จริงหรือไม่?')==true){window.location='drug_profile_log.php?action=delete&id=<?php echo $row_drug_use_change['id']; ?>&hn=<?php echo $hn; ?>';}" class="badge badge-danger font16">ลบ</a></td>
      
      </tr> <?php } while ($row_drug_use_change = mysql_fetch_assoc($drug_use_change)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($drug_use_change);
?>
