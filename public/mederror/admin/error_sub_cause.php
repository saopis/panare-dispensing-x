<?php require_once('../Connections/mederror.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO error_sub_cause (cause_id, sub_name) VALUES (%s, %s)",
                       GetSQLValueString($_POST['c_id'], "int"),
                       GetSQLValueString($_POST['sub_cause'], "text"));

  mysql_select_db($database_mederror, $mederror);
  $Result1 = mysql_query($insertSQL, $mederror) or die(mysql_error());

  $insertGoTo = "error_sub_cause.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<?
if(isset($_POST['Submit2'])&&($_POST['Submit2']=="update")){
$update="update error_sub_cause set sub_name='$name' where id='$uid'";
$q_upate=mysql_query($update,$mederror) or die (mysql_error());
echo '<meta http-equiv="refresh" content="0;URL=error_sub_cause.php?cause_id='.$id.'" />';

}
 
if(isset($del)&&($del=="del")){
$search = "select count(c.id) as count_id from med_error_subtype c   where c.sub_id='$sid'";
$query_search=mysql_query($search,$mederror) or die (mysql_error());
$row_search = mysql_fetch_assoc($query_search);
$totalRows_search = mysql_num_rows($query_search);

if($row_search['count_id']!=0){
echo '<br /><br /><div align="center">ลบไม่ไำ้ด้เนื่องจาก  มีบางรายงานที่ยังใช้ประเภทย่อยของความคลาดคลื่อนนี้ <br />
    <span class="red2"><a href="javascript:history.back()">&lt;&lt; ย้อนกลับ</a> </span> </div>';
exit();
}
mysql_select_db($database_mederror, $mederror);
$update ="delete from error_sub_cause where id ='$sid'";
$query_update = mysql_query($update,$mederror) or die (mysql_error());
if($query_update!=""){
echo '<meta http-equiv="refresh" content="0;URL=error_sub_cause.php?cause_id='.$cause_id.'" />';
}
}

if(isset($status)&&($status=="show")){
mysql_select_db($database_mederror, $mederror);
$update ="update error_sub_cause set  status=1 where id ='$sid'";
$query_update = mysql_query($update,$mederror) or die (mysql_error());
}

if(isset($status)&&($status=="not")){
mysql_select_db($database_mederror, $mederror);
$update ="update error_sub_cause set  status=2 where id ='$sid'";
$query_update = mysql_query($update,$mederror) or die (mysql_error());
}


mysql_select_db($database_mederror, $mederror);
$query_cause = "SELECT error_cause.id,error_cause.name FROM error_cause WHERE error_cause.id='$cause_id'";
$cause = mysql_query($query_cause, $mederror) or die(mysql_error());
$row_cause = mysql_fetch_assoc($cause);
$totalRows_cause = mysql_num_rows($cause);

mysql_select_db($database_mederror, $mederror);
$query_sub_cause = "SELECT * FROM error_sub_cause WHERE error_sub_cause.cause_id='$cause_id'";
$sub_cause = mysql_query($query_sub_cause, $mederror) or die(mysql_error());
$row_sub_cause = mysql_fetch_assoc($sub_cause);
$totalRows_sub_cause = mysql_num_rows($sub_cause);

isset($startRow_sub_cause)? $orderNum=$startRow_sub_cause:$orderNum=0;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<link href="../dong.css" rel="stylesheet" type="text/css" />
</head>

<body>
เพิ่มชนิดความคลาดเคลื่อนย่อยของ :
<strong><?php echo $row_cause['name']; ?></strong>
<? if(!isset($_GET['update'])){  ?>
<br />

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="300" border="0" cellpadding="0" cellspacing="0" class="normal">
    <tr>
      <td width="76">ชนิดย่อย</td>
      <td width="224"><input name="sub_cause" type="text" id="sub_cause" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Submit" /></td>
    </tr>
  </table>
  <input name="c_id" type="hidden" id="c_id" value="<?php echo $cause_id; ?>" />
  <input type="hidden" name="MM_insert" value="form1">
</form> <? } if(isset($_GET['update'])&&($_GET['update']=="update")){   ?>
<form id="form2" name="form2" method="POST" action="error_sub_cause.php">
<table width="300" border="0" cellpadding="2" cellspacing="0" bgcolor="#FF9900" class="normal">
  <tr>
    <td width="72">&nbsp;&nbsp;error_name</td>
    <td width="156"><input name="name" type="text" id="name" value="<?php echo $row_sub_cause['sub_name']; ?>" /></td>
    <td width="72"><input type="submit" name="Submit2" value="update" /></td>
  </tr>
</table>
<input name="uid" type="hidden" id="uid" value="<?php echo $row_sub_cause['id']; ?>" />
<input name="id" type="hidden" id="id" value="<?php echo $row_sub_cause['cause_id']; ?>" />
</form><? } ?>
<br />
<?php if ($totalRows_sub_cause > 0) { // Show if recordset not empty ?>
  <table width="300" border="0" cellpadding="3" cellspacing="1" bgcolor="#000000" class="normal">
    <tr>
      <td width="32" align="center" bgcolor="#999999">ลำดับ</td>
      <td width="173" align="center" bgcolor="#999999">ชื่อชนิดย่อย</td>
      <td width="73" align="center" bgcolor="#999999">แก้ไข</td>
    </tr>
    <?php do { ?>
      <tr>
        
        <td align="center" bgcolor="#FFFFFF"><?php echo ++$orderNum; ?></td>
        <td bgcolor="#FFFFFF"><?php echo $row_sub_cause['sub_name']; ?></td>
        <td align="center" bgcolor="#FFFFFF"><a href="error_sub_cause.php?cause_id=<?php echo $row_cause['id']; ?>"> </a> <a href="error_sub_cause.php?update=update&amp;uid=<?php echo $row_sub_cause['id']; ?>&amp;cause_id=<?php echo $row_cause['id']; ?>"><img src="../images/edit_item.png" width="16" height="16" border="0" /></a><a href="error_sub_cause.php?del=del&amp;sid=<?php echo $row_sub_cause['id']; ?>&amp;cause_id=<?php echo $row_sub_cause['cause_id']; ?>" onclick="return confirm('ต้องการลบรายการประเภทความคลาดเคลื่อนนี้หรือไม่?');"><img src="../images/trash.png" width="16" height="16" border="0" /></a>
          <? if($row_sub_cause['status']==2){ echo '<a href="error_sub_cause.php?sid='. $row_sub_cause['id'].'&amp;status=show&amp;cause_id='.$row_sub_cause['cause_id'].'"><img src="../images/accept_item.png" width="16" height="16" border="0" alt="เปิดใช้งาน" /></a>'; } else { echo '<a href="error_sub_cause.php?sid='. $row_sub_cause['id'].'&amp;status=not&amp;cause_id='.$row_sub_cause['cause_id'].'"><img src="../images/delete_item.png" width="16" height="16" border="0"  alt="ปิด/ ไม่ใช้งาน" /></a>'; } ?></td>
      </tr>
      <?php } while ($row_sub_cause = mysql_fetch_assoc($sub_cause)); ?>
      </table>
  <?php } // Show if recordset not empty ?></body>
</html>
<?php
mysql_free_result($cause);

mysql_free_result($sub_cause);
?>
