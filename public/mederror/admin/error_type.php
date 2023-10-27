<?php require_once('../../Connections/hos.php'); ?>
<? 
if(isset($type)&&($type=="down")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type-1 where order_type ='$order_up'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type+1 where id='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());


}
if(isset($type)&&($type=="up")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type+1 where order_type ='$order_down'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update ".$database_kohrx.".kohrx_med_error_error_type set order_type=order_type-1 where id='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}
if(isset($status)&&($status=="show")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set  status=1 where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($status)&&($status=="not")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_type set  status=2 where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($del)&&($del=="del")){
$search = "select count(c.id) as count_id from ".$database_kohrx.".kohrx_med_error_cause c left outer join ".$database_kohrx.".kohrx_med_error_error_cause ec on ec.id=c.cause_id  where ec.type_id='$id'";
$query_search=mysql_query($search,$hos) or die (mysql_error());
$row_search = mysql_fetch_assoc($query_search);
$totalRows_search = mysql_num_rows($query_search);
if($row_search['count_id']!=0){
echo '<br /><br /><div align="center">ลบไม่ไำ้ด้เนื่องจาก  มีบางรายงานที่ยังใช้ประเภทความคลาดคลื่อนนี้ <br />
    <span class="red2"><a href="javascript:history.back()">&lt;&lt; ย้อนกลับ</a> </span> </div>';
exit();
}
mysql_select_db($database_hos, $hos);
$update ="delete from ".$database_kohrx.".kohrx_med_error_error_type where id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="delete from ".$database_kohrx.".kohrx_med_error_error_cause where type_id ='$id'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}
?>
<?php
mysql_select_db($database_hos, $hos);
$query_type_error = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_type ORDER BY `order_type`ASC";
$type_error = mysql_query($query_type_error, $hos) or die(mysql_error());
$row_type_error = mysql_fetch_assoc($type_error);
$totalRows_type_error = mysql_num_rows($type_error);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
html,body { height:100%; overflow: hidden; }

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
        max-height:300px !important;
        overflow: auto !important;
    	font-family: th_saraban;
		src:url(font/thsarabunnew-webfont.woff);
        font-size: 14px;

}

</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white ">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-exclamation-circle font20"></i>&ensp;บันทึกความคลาดเคลื่อนทางด้านยา กลุ่มงานเภสัชกรรมชุมชน <?php echo $row_rs_config['hospitalname']; ?></span></nav>

<table width="659" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td height="92" valign="top" background="../images/type_bar_r1_c1.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="33%" height="53">&nbsp;</td>
        <td width="63%">&nbsp;</td>
        <td width="4%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="white_middle"><strong class="orang"><a href="error_type_add.php" class="orang">+ เพิ่มประเภทความคลาดเคลื่อน + </a></strong></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="32" background="../images/type_bar_r3_c1.jpg">&nbsp;</td>
  </tr>
  <tr>
<? $NRecord=0; ?> 

    <td height="84" valign="top" bgcolor="#F2EEED"><?php do {  $NRecord++;
if(($NRecord)%2==1)
{
$bgColor="#F2EEED";
}
else
{
$bgColor="#FFFFFF";
} 
?>
        <table width="100%" border="0" cellpadding="5" cellspacing="0" class="normal">
          <tr>
            <td width="2%">&nbsp;</td>
            <td width="10%" align="center" bgcolor="<? echo $bgColor; ?>"><?php echo $row_type_error['order_type']; ?></td>
            <td width="63%" align="left" bgcolor="<? echo $bgColor; ?>"><strong><?php echo $row_type_error['type_thai']; ?> (<?php echo $row_type_error['type_eng']; ?>) </strong></td>
            <td bgcolor="<? echo $bgColor; ?>"><a href="error_cause.php?id=<?php echo $row_type_error['id']; ?>"><img src="../images/sub.gif"  width="36" height="9" border="0" /></a>&nbsp; <a href="error_type_edit.php?id=<?php echo $row_type_error['id']; ?>"><img src="../images/edit_item.png" width="16" height="16" border="0" /></a><a href="error_type.php?del=del&amp;id=<?php echo $row_type_error['id']; ?>" onClick="return confirm('ต้องการลบรายการประเภทความคลาดเคลื่อนนี้หรือไม่?');"><img src="../images/trash.png" width="16" height="16" border="0" /></a>
              &nbsp;<? if($row_type_error['order_type']!=$totalRows_type_error){  echo '<a href="error_type.php?id='. $row_type_error['id'].'&amp;order='. $row_type_error['order_type'].'&amp;type=down"><img src="../images/down.gif" width="9" height="5" border="0" /></a>'; } if($row_type_error['order_type']!=1){ echo '&nbsp;&nbsp;<a href="error_type.php?id='. $row_type_error['id'].'&amp;order='. $row_type_error['order_type'].'&amp;type=up"><img src="../images/up.gif" width="9" height="5" border="0" /></a> '; } ?> &nbsp;&nbsp;</td>
            <td bgcolor="<? echo $bgColor; ?>"><? if($row_type_error['status']==2){ echo '<a href="error_type.php?id='. $row_type_error['id'].'&amp;status=show"><img src="../images/accept_item.png" width="16" height="16" border="0" alt="เปิดใช้งาน" /></a>'; } else { echo '<a href="error_type.php?id='. $row_type_error['id'].'&amp;status=not"><img src="../images/delete_item.png" width="16" height="16" border="0"  alt="ปิด/ ไม่ใช้งาน" /></a>'; } ?></td>
          </tr>
      </table>
        <?php } while ($row_type_error = mysql_fetch_assoc($type_error)); ?></td>
  </tr>
  <tr>
    <td height="19"><img src="../images/type_bar_r3_c2.jpg" width="659" height="19" /></td>
  </tr>
  <tr>
    <td height="19" class="blue_mid">คำอธิบายสำหรับสัญลักษณ์<span class="normal1"> ( <img src="../images/sub.gif" width="36" height="9" />= เพิ่มหัวข้อย่อย <img src="../images/edit_item.png" width="16" height="16" />= แก้ไข <img src="../images/trash.png" width="16" height="16" />= ลบ <img src="../images/up.gif" width="9" height="5" /> = move up <img src="../images/down.gif" width="9" height="5" />= move down <img src="../images/accept_item.png" width="16" height="16" />= เปิดใช้งาน <img src="../images/delete_item.png" width="16" height="16" />= ปิดการใช้งาน ) </span></td>
  </tr>
  <tr>
    <td height="19" class="dong"></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($type_error);
?>
