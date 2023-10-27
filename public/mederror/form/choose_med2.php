<?php require_once('../../Connections/mederror.php'); ?>
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
mysql_select_db($database_mederror, $mederror);
if($_GET['getdata']==1){
	if($search!=""){
		$condition="where type_thai like '$search%'";	
	}
	$query_rs_chw="select id,type_thai as name from error_type ".$condition." order by order_type ASC";
}
if($_GET['getdata']==2){
	if($search!=""){
		$condition=" and name like '$search%'";	
	}
	$query_rs_chw="select id,name from error_cause where type_id='".$_GET['data3']."' ".$condition." order by order_cause ASC";
}
if($_GET['getdata']==3){
	if($search!=""){
		$condition=" and sub_name like '$search%'";	
	}
	$query_rs_chw="select id,sub_name as name from error_sub_cause where cause_id='".$_GET['data3']."' ".$condition." order by id ASC";
}
$rs_chw = mysql_query($query_rs_chw, $mederror) or die(mysql_error());
$row_rs_chw = mysql_fetch_assoc($rs_chw);
$totalRows_rs_chw = mysql_num_rows($rs_chw);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>

  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="table_head_small">
    <?php $i=0; do { $i++; 
	 if($bg == "#FFFFFF") { //ส่วนของการ สลับสี 
$bg = "#E6E6E6";
} else {
$bg = "#FFFFFF";
}
	?><tr>
      <td bgcolor="<?=$bg; ?>"><?=$i." "; ?> <a href="#" OnClick="lotData('<?=$row_rs_chw["id"];?>','<?=$row_rs_chw["name"];?>');"><?php echo $row_rs_chw['name']; ?></a></td>
    </tr> <?php } while ($row_rs_chw = mysql_fetch_assoc($rs_chw)); ?>
  </table>
 
</body>
</html>
<?php
mysql_free_result($rs_chw);
?>
