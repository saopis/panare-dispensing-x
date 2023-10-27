<?php 
ob_start();
session_start();
$vn=$_GET['vn'];
?>
<?php require_once('Connections/hos.php'); ?>
<?php require('include/get_channel.php'); ?>
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
if($row_setting[42]=="4"){ $table="printserver_list"; $field="name"; } else { $table="printserver"; $field="server_name"; }

mysql_select_db($database_hos, $hos);
$query_rs_print = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$get_ip."'";
$rs_print = mysql_query($query_rs_print, $hos) or die(mysql_error());
$row_rs_print = mysql_fetch_assoc($rs_print);
$totalRows_rs_print = mysql_num_rows($rs_print);

mysql_select_db($database_hos, $hos);
$query_rs_printserver = "select ".$field." from ".$table." order by ".$field." ASC";
$rs_printserver = mysql_query($query_rs_printserver, $hos) or die(mysql_error());
$row_rs_printserver = mysql_fetch_assoc($rs_printserver);
$totalRows_rs_printserver = mysql_num_rows($rs_printserver);

mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.icode,concat(s.name,' ',s.strength,' ',s.units) as drugname,o.qty, d.drugusage,d.shortlist,substring(o.icode,1,1) as scode 
from opitemrece o 
left outer join drugitems s on s.icode=o.icode
left outer join drugusage d on d.drugusage=o.drugusage  
 left outer join ovst ov on ov.vn=o.vn
where ov.vn='".$vn."' and o.icode like '1%'
group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist   order by scode,s.name ASC ";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;

}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}
//  End -->
</script>
<style type="text/css">
.white {	color:#FFFFFF;
	font-size:12px;
	font-weight:bolder;
}
tr.grid:hover {
    background-color:#CDCDCD;
}

tr.grid:hover td {
    background-color: transparent; /* or #000 */
}
tr.grid2:hover {
    background-color:#D6EAEF;
}

tr.grid2:hover td {
    background-color: transparent; /* or #000 */
}
div.wrap {
word-wrap: break-word;
}
html,body{overflow:hidden; }
::-webkit-scrollbar {
    width: 15px;
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
<script>
	$(document).ready(function(){
     ///////////// checkall ////////////
 $("#checkedAll").change(function(){
    if(this.checked){
      $(".checkSingle").each(function(){
        this.checked=true;
      })              
    }else{
      $(".checkSingle").each(function(){
        this.checked=false;
      })              
    }
  });

  $(".checkSingle").click(function () {
    if ($(this).is(":checked")){
      var isAllChecked = 0;
      $(".checkSingle").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ $("#checkedAll").prop("checked", true); }     
    }else {
      $("#checkedAll").prop("checked", false);
    }
  });	
///////////////////////////////////
		
	});
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="sticker_print_data.php" onsubmit="return confirm('ต้องการพิมพ์สติ๊กเกอร์ยาที่เลือกจริงหรือไม่?')">

<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><strong>&nbsp;พิมพ์สติ๊กเกอร์ยา </strong><br />
        <input type="checkbox" name="checkbox" id="checkbox" style="width: 20px; height: 20px" />
<span class="text-white"> พิมพ์หัวสติ๊กเกอร์</span>&emsp;<input type="checkbox" name="checkedAll" id="checkedAll" style="width: 20px; height: 20px" /><label for="checkedAll" class="text-white " style="cursor: pointer">&nbsp;เลือกทั้งหมด</label></span>
        <input name="vn" type="hidden" id="vn" value="<?php echo $vn; ?>" />
        Printer Server&ensp;<select name="print_server" id="print_server" class=" form-control form-control-sm" style=" width:200px; position:absolute; right:220px;">
          <?php
do {  
?>
          <option value="<?php if($row_setting[42]=="4"){ echo $row_rs_printserver['name']; } else { echo $row_rs_printserver['server_name']; } ?>"<?php if($row_setting[42]=="4"){ if(!(strcmp($row_rs_printserver['name'], $row_rs_print['print_server']))) {echo "selected=\"selected\"";} } else { if(!(strcmp($row_rs_printserver['server_name'], $row_rs_print['print_server']))) {echo "selected=\"selected\"";} } ?>><?php if($row_setting[42]=="4"){ echo $row_rs_printserver['name']; } else { echo $row_rs_printserver['server_name']; } ?></option>
          <?php
} while ($row_rs_printserver = mysql_fetch_assoc($rs_printserver));
  $rows = mysql_num_rows($rs_printserver);
  if($rows > 0) {
      mysql_data_seek($rs_printserver, 0);
	  $row_rs_printserver = mysql_fetch_assoc($rs_printserver);
  }
?>
        </select>
<button type="submit" width="36" style=" position:absolute;right:40px;" class="btn btn-primary font16" /><i class="fas fa-print" style="font-size:30px;"></i>&ensp;พิมพ์สติ๊กเกอร์</button>
</nav>
<div>
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="table_head_small" style="border-collapse:collapse; margin-top:78px;">
    <tr class="table_head_small_bord bg-gray1">
      <td width="7%" height="28" align="center" >ลำดับ</td>
      <td width="43%" align="center">รายการยา</td>
      <td width="38%" align="center">วิธีใช้</td>
      <td width="12%" align="center" >จำนวน</td>
    </tr>
</table>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:390px; margin-top:0px;">
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="table_head_small" style="border-collapse:collapse; ">

<?php $i=0; do { $i++; 
  if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

?>
    <tr class="grid2" onclick="changbgcolor(this.id)" >
      <td height="28" width="7%" align="center" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #F0F0F0"  ><input name="chk[]" type="checkbox" id="chk[]" class="checkSingle" value="<?php echo $row_s_drug['icode']; ?>" style="width: 20px; height: 20px" />        <?php echo $i;  ?>&nbsp;</td>
      <td width="43%" align="left" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px;border:solid 1px #F0F0F0" ><?php echo $row_s_drug['drugname']; ?></td>
      <td width="38%" align="left" bgcolor="<?php echo $bgcolor; ?>" style="padding-left:10px;border:solid 1px #F0F0F0"><?php echo $row_s_drug['shortlist']; ?></td>
      <td width="12%" align="center" bgcolor="<?php echo $bgcolor; ?>" style="border:solid 1px #F0F0F0"><?php echo $row_s_drug['qty']; ?></td>
    </tr>
    <?php } while($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
  </table>
</div>
</form>
</body>
</html>
<?php
mysql_free_result($rs_print);

mysql_free_result($rs_printserver);

mysql_free_result($s_drug);
?>
