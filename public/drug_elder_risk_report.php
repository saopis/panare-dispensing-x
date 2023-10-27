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
$query_rs_drug = "SELECT r.icode,concat(d.name,' ',d.strength) as drugname FROM ".$database_kohrx.".kohrx_drug_elder_risk r left outer join drugitems d on d.icode=r.icode GROUP BY r.icode ORDER BY d.name ASC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
	 var URL = "drug_elder_risk_report_list.php"; 
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
<link href="css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td bgcolor="#CC0000"><font color="#FFFFFF" size="+1" style="font-weight:bold">ระบบรายงานบันทึกยาที่ควรระวังในผู้สูงอายุ (created : 16/2/2557)</font></td>
  </tr>
  <tr>
    <td class="thfont font14"><form id="form1" name="form1" method="post" action="">
      <table width="800" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td width="123" align="right">เลือกช่วงวัน</td>
          <td width="457"><input name="date1" type="text" id="date1" value="<? echo date('d/m/').(date('Y')+543); ?>" size="10" />
            ถึง
            <input name="date2" type="text" id="date2" value="<? echo date('d/m/').(date('Y')+543); ?>" size="10" /></td>
        </tr>
        <tr>
          <td align="right">รายการยา</td>
          <td><label for="drug"></label>
            <select name="drug" id="drug">
              <option value="">=== ทั้งหมด ===</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rs_drug['icode']?>"><?php echo $row_rs_drug['drugname']?></option>
              <?php
} while ($row_rs_drug = mysql_fetch_assoc($rs_drug));
  $rows = mysql_num_rows($rs_drug);
  if($rows > 0) {
      mysql_data_seek($rs_drug, 0);
	  $row_rs_drug = mysql_fetch_assoc($rs_drug);
  }
?>
            </select></td>
        </tr>
        <tr>
          <td align="right">severity</td>
          <td><label for="severity"></label>
            <select name="severity" id="severity">
<option value="">== ทั้งหมด ==</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
            </select></td>
        </tr>
        <tr>
          <td align="right">consult</td>
          <td><select name="consult" id="consult" >
            <option value="" <?php if (!(strcmp("", $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>=== กรุณาเลือก ===</option>
            <option value="1" <?php if (!(strcmp(1, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาระยะสั้น</option>
            <option value="2" <?php if (!(strcmp(2, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>เลี่ยงไปใช้ทางเลือกอื่น</option>
            <option value="3" <?php if (!(strcmp(3, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาแต่มีนัดติดตามดูอาการ</option>
            <option value="4" <?php if (!(strcmp(4, $row_rs_edit['consult']))) {echo "selected=\"selected\"";} ?>>แพทย์และเภสัชกรสั่งและจ่ายยาโดยไม่มีการเปลี่ยนแปลงคำสั่งการใช้ยาและไม่นัดติดตามดูอาการ</option>
          </select></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td><input type="button" name="button" id="button" value="ค้นหา"  onclick="formSubmit('search','displayDiv','indicator')"/>
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="do" id="do" /></td>
        </tr>
      </table>
    </form><br />

    <div id="indicator"  align="center" style="position:absolute; display:none; z-index:1000;padding:0px;"><img src="images/indicator.gif" hspace="10" align="absmiddle" /></div><div id="displayDiv">&nbsp;</div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
