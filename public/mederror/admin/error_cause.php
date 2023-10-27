<?php require_once('../Connections/hos.php'); ?>
<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>
<? 
  $url_cur=explode("/",curPageURL());
  $size_array=count($url_cur)-1;
  $url=$url_cur[$size_array];
  session_register("url");
$url2=explode("?",$url);
?>
<? 
if(isset($_POST['Submit2'])&&($_POST['Submit2']=="update")){
mysql_select_db($database_hos, $hos);
$update="update ".$database_kohrx.".kohrx_med_error_error_cause set name='$name' where id='$uid'";
$q_upate=mysql_query($update,$hos) or die (mysql_error());
echo '<meta http-equiv="refresh" content="0;URL=error_cause.php?id='.$id.'" />';

}
if(isset($type)&&($type=="down")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set order_cause=order_cause-1 where order_cause ='$order_up'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update error_cause set order_cause=order_cause+1 where id='$id2'";
$query_update = mysql_query($update,$hos) or die (mysql_error());


}

if(isset($type)&&($type=="up")){
$order_down=$order-1;
$order_up=$order+1;
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set order_cause=order_cause+1 where order_cause ='$order_down'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set order_cause=order_cause-1 where id='$id2'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($del)&&($del=="del")){
mysql_select_db($database_hos, $hos);
$search = "select count(c.id) as count_id from ".$database_kohrx.".kohrx_med_error_cause c   where c.cause_id='$uid'";
$query_search=mysql_query($search,$hos) or die (mysql_error());
$row_search = mysql_fetch_assoc($query_search);
$totalRows_search = mysql_num_rows($query_search);

if($row_search['count_id']!=0){
echo '<br /><br /><div align="center">ลบไม่ไำ้ด้เนื่องจาก  มีบางรายงานที่ยังใช้ประเภทความคลาดคลื่อนนี้ <br />
    <span class="red2"><a href="javascript:history.back()">&lt;&lt; ย้อนกลับ</a> </span> </div>';
exit();
}
mysql_select_db($database_hos, $hos);
$update ="delete from ".$database_kohrx.".kohrx_med_error_error_cause where id ='$uid'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

$update ="delete from error_sub_cause where cause_id ='$uid'";
$query_update = mysql_query($update,$hos) or die (mysql_error());

}
?>
<?php
if ((isset($_POST["Submit"])) && ($_POST["Submit"] == "Add")) {
mysql_select_db($database_hos, $hos);
$selorder = "select max(order_cause)+1 as maxcause from ".$database_kohrx.".kohrx_med_error_error_cause where type_id = '$id'";
$qorder=mysql_query($selorder,$hos) or die(mysql_error());
$row_selorder = mysql_fetch_assoc($qorder);

mysql_select_db($database_hos, $hos);
$insertSQL = "INSERT INTO ".$database_kohrx.".kohrx_med_error_error_cause (type_id, name,order_cause) VALUES ('$id','$error_name','$row_selorder[maxcause]')";
$Result1 = mysql_query($insertSQL, $hos) or die(mysql_error());
echo "<meta http-equiv=\"refresh\" content=\"0;URL=error_cause.php?".$url2[1]."\" />";
}

if(isset($status)&&($status=="show")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set  status=1 where id ='$cid'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

if(isset($status)&&($status=="not")){
mysql_select_db($database_hos, $hos);
$update ="update ".$database_kohrx.".kohrx_med_error_error_cause set  status=2 where id ='$cid'";
$query_update = mysql_query($update,$hos) or die (mysql_error());
}

mysql_select_db($database_hos, $hos);
$query_cause = "SELECT c.* FROM ".$database_kohrx.".kohrx_med_error_error_cause c  WHERE c.type_id='$id' order by c.order_cause ASC";
$cause = mysql_query($query_cause, $hos) or die(mysql_error());
$row_cause = mysql_fetch_assoc($cause);
$totalRows_cause = mysql_num_rows($cause);

mysql_select_db($database_hos, $hos);
$query_error_type = "SELECT concat(error_type.type_thai, error_type.type_eng) as name,id FROM ".$database_kohrx.".kohrx_med_error_error_type WHERE error_type.id='$id'";
$error_type = mysql_query($query_error_type, $hos) or die(mysql_error());
$row_error_type = mysql_fetch_assoc($error_type);
$totalRows_error_type = mysql_num_rows($error_type);

mysql_select_db($database_hos, $hos);
$query_update_c = "select * from ".$database_kohrx.".kohrx_med_error_error_cause where id='$uid'";
$update_c = mysql_query($query_update_c, $hos) or die(mysql_error());
$row_update_c = mysql_fetch_assoc($update_c);
$totalRows_update_c = mysql_num_rows($update_c);

isset($startRow_cause)? $orderNum=$startRow_cause:$orderNum=0;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<link href="../dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="big_blue"><?php echo $row_error_type['name']; ?></span><br />
<span class="normal1">เพิ่มชนิดความคลาดเคลื่อน</span><br />
<? if(!isset($_GET['update'])&&($_GET['update']!="update")){  ?><form id="form1" name="form1" method="POST" action="error_cause.php?<? echo $url2[1]; ?>">
  <table width="300" border="0" cellpadding="0" cellspacing="0" class="normal">
    <tr>
      <td width="70">error_name</td>
      <td width="157"><input name="error_name" type="text" id="error_name" /></td>
      <td width="73"><input type="submit" name="Submit" value="Add" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input name="id" type="hidden" id="id" value="<? echo $id; ?>" />
</form><? }  if(isset($_GET['update'])&&($_GET['update']=="update")){   ?>
<form id="form2" name="form2" method="POST" action="error_cause.php">
<table width="300" border="0" cellpadding="2" cellspacing="0" bgcolor="#FF9900" class="normal">
  <tr>
    <td width="72">&nbsp;&nbsp;error_name</td>
    <td width="156"><input name="name" type="text" id="name" value="<?php echo $row_update_c['name']; ?>" /></td>
    <td width="72"><input type="submit" name="Submit2" value="update" /></td>
  </tr>
</table>
<input name="uid" type="hidden" id="uid" value="<?php echo $row_update_c['id']; ?>" />
<input name="id" type="hidden" id="id" value="<?php echo $row_error_type['id']; ?>" />
</form><? } ?>
<table width="520" border="0" cellpadding="3" cellspacing="1" bgcolor="#000000" class="normal">
  <tr>
    <td width="9%" align="center" bgcolor="#999999" class="white_middle">ลำดับ</td>
    <td width="64%" align="center" bgcolor="#999999" class="white_middle">ชื่อชนิดความคลาเคลื่อน</td>
    <td width="27%" align="center" bgcolor="#999999" class="white_middle">เพิ่ม</td>
  </tr>
  <?php do { ?><tr>
    
      <td align="center" bgcolor="#FFFFFF"><?php echo ++$orderNum; ?></td>
      <td bgcolor="#FFFFFF"><?php echo $row_cause['name']; ?></td>
      <td align="center" bgcolor="#FFFFFF"><a href="error_sub_cause.php?cause_id=<?php echo $row_cause['id']; ?>"><img src="../images/sub.gif" width="36" height="9" border="0" /> </a> <a href="error_cause.php?update=update&amp;uid=<?php echo $row_cause['id']; ?>&amp;id=<? echo $id; ?>"><img src="../images/edit_item.png" width="16" height="16" border="0" /></a><a href="error_cause.php?del=del&amp;uid=<?php echo $row_cause['id']; ?>&amp;id=<?php echo $row_error_type['id']; ?>" onclick="return confirm('ต้องการลบรายการประเภทความคลาดเคลื่อนนี้หรือไม่?');"><img src="../images/trash.png" width="16" height="16" border="0" /></a>        <? if($row_cause['status']==2){ echo '<a href="error_cause.php?cid='. $row_cause['id'].'&amp;status=show&amp;id='.$id.'"><img src="../images/accept_item.png" width="16" height="16" border="0" alt="เปิดใช้งาน" /></a>'; } else { echo '<a href="error_cause.php?cid='. $row_cause['id'].'&amp;status=not&amp;id='.$id.'"><img src="../images/delete_item.png" width="16" height="16" border="0"  alt="ปิด/ ไม่ใช้งาน" /></a>'; } ?>&nbsp;        <? if($row_cause['order_cause']!=$totalRows_cause){  echo '<a href="error_cause.php?id='.$id.'&amp;id2='. $row_cause['id'].'&amp;order='. $row_cause['order_cause'].'&amp;type=down"><img src="../images/down.gif" width="9" height="5" border="0" /></a>'; } if($row_cause['order_cause']!=1){ echo '&nbsp;&nbsp;<a href="error_cause.php?id='.$id.'&amp;id2='. $row_cause['id'].'&amp;order='. $row_cause['order_cause'].'&amp;type=up"><img src="../images/up.gif" width="9" height="5" border="0" /></a> '; } ?></td>
      </tr><?php } while ($row_cause = mysql_fetch_assoc($cause)); ?>
</table>
<br />
<span class="blue_mid">คำอธิบายสำหรับสัญลักษณ์<span class="normal1"> ( <img src="../images/sub.gif" width="36" height="9" />= เพิ่มหัวข้อย่อย <img src="../images/edit_item.png" width="16" height="16" />= แก้ไข <img src="../images/trash.png" width="16" height="16" />= ลบ  <img src="../images/accept_item.png" width="16" height="16" />= เปิดใช้งาน <img src="../images/delete_item.png" width="16" height="16" />= ปิดการใช้งาน )</span></span>
</body>
</html>
<?php
mysql_free_result($cause);

mysql_free_result($error_type);

mysql_free_result($update_c);
?>
