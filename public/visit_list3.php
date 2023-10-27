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

$get_ip=$_SERVER["REMOTE_ADDR"];

?>
<?php
$date11=explode("-",$edate1);
$edate2=($date11[2]."/".$date11[1]."/".($date11[0]+543));

mysql_select_db($database_hos, $hos);
$query_rs_visit_list = "SELECT  v.hn,concat(DATE_FORMAT(v.vstdate,'%d/%m/'),(substring(v.vstdate,1,4)+543)) as date1,o.vsttime,v.pdx,i.name,p.name as pttype_name,d.name as doctor_name,v.vn,o.oqueue FROM vn_stat v left outer join icd101 i on i.code=v.pdx left outer join pttype p on p.pttype=v.pttype left outer join ovst o on o.vn=v.vn left outer join doctor d on d.code=o.doctor WHERE v.hn='$hn' and v.vstdate='$edate1'";
$rs_visit_list = mysql_query($query_rs_visit_list, $hos) or die(mysql_error());
$row_rs_visit_list = mysql_fetch_assoc($rs_visit_list);
$totalRows_rs_visit_list = mysql_num_rows($rs_visit_list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form4" name="form4" method="post" action="">
  <br />
  <span class="big_red16">  ผู้ป่วยรับบริการในวันที่ <?php echo $edate2; ?> มากกว่า 1 ครั้ง กรุณาเลือก visit ที่ผู้ป่วยมารับบริการจากรายการข้างล่าง
  <br />
  </span>
  <table width="1000" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <tr class="table_head_small_white">
      <td width="5%" align="center" bgcolor="#3366CC"><strong>ลำดับ</strong></td>
      <td width="24%" align="center" bgcolor="#3366CC"><strong>วันที่รับบริการ</strong></td>
      <td width="29%" align="center" bgcolor="#3366CC"><strong>สิทธิ์</strong></td>
      <td width="42%" align="left" bgcolor="#3366CC"><strong>วินิจฉัย</strong></td>
    </tr>
    <?php $i=0; do { $i++; 
	  if($bgcolor=="#FFFFFF") { $bgcolor="#C4DBFF"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

	?>
    <tr>
      <td height="30" align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo $i; ?></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><a href="javascript:parent.formSubmit_visit('visit','displayIndiv','indicator','?date1=<?php echo $row_rs_visit_list['date1']; ?>&oqueue=<?php echo $row_rs_visit_list['oqueue']; ?>&esp_drug=show&vn2=<?php echo $row_rs_visit_list['vn']; ?>','<?php echo $row_rs_visit_list['oqueue']; ?>','<?php echo $row_rs_visit_list['date1'];?>');parent.$.fn.colorbox.close();" class="small_blue2"><?php echo "$row_rs_visit_list[date1]" ?> &nbsp; <?php echo "$row_rs_visit_list[vsttime]"; ?></a></td>
      <td align="center" bgcolor="<?php echo $bgcolor; ?>"><?php echo "$row_rs_visit_list[pttype_name]"; ?></td>
      <td align="left" bgcolor="<?php echo $bgcolor; ?>"><?php if(!empty($row_rs_visit_list['pdx'])){ echo "($row_rs_visit_list[pdx])"."  "."$row_rs_visit_list[name]"; } ?></td>
    </tr>
    <?php } while ($row_rs_visit_list = mysql_fetch_assoc($rs_visit_list)); ?>
  </table>
  <input type="hidden" name="do4" id="do4" />
  <input type="hidden" name="id4" id="id4" />
  <br />
</form>
</body>
</html>
<?php
mysql_free_result($rs_visit_list);
?>
