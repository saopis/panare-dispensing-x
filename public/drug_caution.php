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
$query_person = "SELECT o.name,o.doctorcode,o.hospital_department_id FROM opduser o left outer join doctor d on d.code=o.doctorcode order by name";
$person = mysql_query($query_person, $hos) or die(mysql_error());
$row_person = mysql_fetch_assoc($person);
$totalRows_person = mysql_num_rows($person);

mysql_select_db($database_hos, $hos);
$query_rs_drug = "SELECT drugitems.icode as drugcode,concat(drugitems.name, drugitems.strength) as drugname FROM drugitems WHERE drugitems.name not like '%คิด%' ORDER BY drugitems.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" />	
<script src="include/jquery.js" type="text/javascript"></script>
<script  src="include/ajax_framework.js"></script>
<script src="include/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.10.offset.datepicker.min.js"></script>
<script type="text/javascript" src="include/ui.datepicker-th.js"></script>

<script>
jQuery(function($){ 
  $("#date1").mask("99/99/9999"); 
  $("#time1").mask("99:99");
  $("#time2").mask("99:99");
  $("#time3").mask("99:99");
  });
</script>
<script type="text/javascript">
function formSubmit(sID,displayDiv,indicator,eID) {
	if(sID!=''){ $('#do').val(sID);}
	if(eID!=''){ $('#id').val(eID);}
	 var URL = "drug_caution_report_list.php"; 
	var data = getFormData("form1");
	ajaxLoad('post', URL, data, displayDiv,indicator);
	var e = document.getElementById(indicator);
	e.style.display = 'block';
	$('#button').val("ค้นหา"); 	
	document.getElementById('button').onclick=function(){formSubmit('search','displayDiv','indicator');};			
	}
</script>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td bgcolor="#CC0000"><font color="#FFFFFF" size="+1" style="font-weight:bold">รายงานอุบัติการณ์ยาที่ห้ามสั่งใช้ใน ICD10 ที่กำหนด</font></td>
  </tr>
  <tr>
    <td><form id="form2" name="form1" method="post" action="">
      <table width="600" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td width="123">เลือกช่วงวัน</td>
          <td width="457"><input name="date1" type="text" id="date1" value="<? echo date('d/m/').(date('Y')+543); ?>" size="10" />
            ถึง
            <input name="date2" type="text" id="date2" value="<? echo date('d/m/').(date('Y')+543); ?>" size="10" /></td>
        </tr>
        <tr>
          <td>ผู้สั่งยา</td>
          <td><select name="recorder" class="table_head_small" id="recorder">
            <option value="">------เลือก-----</option>
            <?php
do {  
?>
            <option value="<?php echo $row_person['doctorcode']?>"><?php echo $row_person['name']?></option>
            <?php
} while ($row_person = mysql_fetch_assoc($person));
  $rows = mysql_num_rows($person);
  if($rows > 0) {
      mysql_data_seek($person, 0);
	  $row_person = mysql_fetch_assoc($person);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td>HN</td>
          <td><input type="text" name="hn" id="hn" /></td>
        </tr>
        <tr>
          <td>ยาที่เกี่ยวข้อง</td>
          <td><span class="normal">
            <select name="drug" class="table_head_small" id="drug">
              <option value="">=== ไม่เลือก===</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rs_drug['drugcode']?>"><?php echo $row_rs_drug['drugname']?></option>
              <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
            </select>
          </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="button" name="button" id="button" value="ค้นหา"  onclick="formSubmit('search','displayDiv','indicator')"/>
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="do" id="do" /></td>
        </tr>
      </table>
    </form>
      <br />
      <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div>
      <div id="displayDiv">&nbsp;</div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($person);

mysql_free_result($rs_drug);
?>
