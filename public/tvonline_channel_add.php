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

mysql_select_db($database_hos, $hos);
$query_rs_channel_type = "select * from ".$database_kohrx.".kohrx_television_channel_type";
$rs_channel_type = mysql_query($query_rs_channel_type, $hos) or die(mysql_error());
$row_rs_channel_type = mysql_fetch_assoc($rs_channel_type);
$totalRows_rs_channel_type = mysql_num_rows($rs_channel_type);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<script src="include/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#preview').load('include/channel_list2.php');
});

	$('#imageform').ajaxForm({
		target : '#preview',
		beforSubmit:$('#preview').html('<img src="images/indicator.gif" alt="Uploading..."/>'),
		clearForm:true,
		success:function(){$('#save').val('บันทึก'); $('#photoimg').val("");
		$('#save2').hide(); //$('#uploadfile').show();
		}
		}).submit();

</script>
</head>

<body>
<p>เพิ่มรายการช่องโทรทัศน์</p>
<form action="channel_list.php" method="post" enctype="multipart/form-data" name="imageform" id="imageform">  <table width="500" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td width="86">ชื่อช่อง</td>
      <td width="402"><input name="channel" type="text" class="inputcss" id="channel" /></td>
    </tr>
    <tr>
      <td>link</td>
      <td><input name="link" type="text" class="inputcss" id="link" /></td>
    </tr>
    <tr>
      <td>ประเภท</td>
      <td><label for="channel_type"></label>
        <select name="channel_type" class="inputcss1" id="channel_type">
          <?php
do {  
?>
          <option value="<?php echo $row_rs_channel_type['id']?>"><?php echo $row_rs_channel_type['channel_type']?></option>
          <?php
} while ($row_rs_channel_type = mysql_fetch_assoc($rs_channel_type));
  $rows = mysql_num_rows($rs_channel_type);
  if($rows > 0) {
      mysql_data_seek($rs_channel_type, 0);
	  $row_rs_channel_type = mysql_fetch_assoc($rs_channel_type);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>รูปภาพ</td>
      <td><label for="file"></label>
      <input name="photoimg" type="file" class="table_head_small" id="photoimg" /> </td>
    </tr>
    <tr>
      <td>สถานะ</td>
      <td><input name="istatus" type="checkbox" id="istatus" value="Y" checked="checked" />
        ใช้งาน</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="save" id="save" value="บันทึก" class=" button gray" />
      <input type="submit" name="save2" id="save2" value="ลบ" class=" button gray2" /></td>
    </tr>
  </table>
</form>
<p><div id="preview"></div></p>
</body>
</html>
<?php
mysql_free_result($rs_channel_type);
?>
