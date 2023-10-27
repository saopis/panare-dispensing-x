<?php require_once('Connections/hos.php'); ?>
<?php
ini_set('register_globals', 'on');
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

mysql_select_db($database_hos, $hos);
$query_rs_drug = 
"SELECT concat(d.name,d.strength) as drugname,u.shortlist,o.qty,o.vstdate,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use,o.an from (select qty,vstdate,an,sp_use,icode,drugusage from opitemrece WHERE hn = '".$_GET['hn']."' and icode='".$_GET['icode']."' union all select qty,vstdate,an,sp_use,icode,drugusage from opitemrece_arc WHERE hn = '".$_GET['hn']."' and icode='".$_GET['icode']."') as o left outer join sp_use sp on sp.sp_use=o.sp_use left outer join drugitems d on d.icode=o.icode left outer join drugusage u on u.drugusage=o.drugusage";
//$query_rs_drug.=" UNION SELECT concat(d.name,d.strength) as drugname,u.shortlist,o.qty,o.vstdate,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use from opitemrece_arc o left outer join sp_use sp on sp.sp_use=o.sp_use  left outer join drugitems d on d.icode=o.icode left outer join drugusage u on u.drugusage=o.drugusage WHERE o.hn = '$hn' and o.icode='$icode'";
$query_rs_drug.=" ORDER BY vstdate DESC";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body{overflow-y:hidden; }
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
<!-- jquery -->
<script src="include/jquery/js/jquery.min.js" ></script>
<!-- bootstrap -->
<link rel="stylesheet" href="include/bootstrap/css/bootstrap.min.css">
<script src="include/bootstrap/js/popper.min.js"></script>
<script src="include/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="include/bootstrap/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="include/bootstrap/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" href="include/bootstrap/css/jquery.dataTables.min.css"/>
</head>
<!-- fontawesome -->
<link rel="stylesheet" href="include/fontawesome/css/all.css">
<!-- kohrx -->
<link rel="stylesheet" href="include/kohrx/css/kohrx.css">
<style>
html,body{overflow-y:hidden;}
</style>
</head>

<body>
<nav class="navbar navbar-dark thfont " style="background-color: #F69; color:#FFFFFF;">
  <!-- Navbar content -->
ประว้ติการใช้ยา <?php echo $row_rs_drug['drugname']; ?><?php if($_GET['back']=='Y'){ ?>&ensp;<button class="btn btn-danger" onclick="history.back(-1);" style="margin-right:20px;"><i class="fas fa-arrow-circle-left"></i>&nbsp;ย้อนกลับ</button><?php } ?>
</nav>
<div>
<table width="100%" class="table thfont table-striped table-borderless font14" style="margin-bottom:0px;">
<thead  style="background-color:#FF99CC; " class="text-center">
  <tr style="height:25px;">
    <th width="30%" align="center" scope="col" >วันที่</th>
    <th width="50%" align="center" scope="col" >วิธีใช้</th>
    <th width="20%" align="center" scope="col" >จำนวน</th>
  </tr>
  </thead>
</table>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:400px;">
<table width="100%" class="table thfont table-striped table-borderless font14">
  <tbody class="font12">
  <?php do {   ?>
  <tr class="grid4">
    
      <td width="30%" align="center" bgcolor="<?php echo $bgcolor; ?>" <?php if($row_rs_drug['an']!=""){echo "style=\"color:red;\"";} ?> ><?php echo dateThai($row_rs_drug['vstdate']); ?></td>
    <td  width="50%" align="center" bgcolor="<?php echo $bgcolor; ?>" <?php if($row_rs_drug['an']!=""){echo "style=\"color:red;\"";} ?> ><?php if($row_rs_drug['sp_use']=="") {echo substr($row_rs_drug['shortlist'],0,200); } else { echo substr($row_rs_drug['sp_name'],0,200); } ?></td>
    <td width="20%" align="center" bgcolor="<?php echo $bgcolor; ?>" <?php if($row_rs_drug['an']!=""){echo "style=\"color:red;\"";} ?> ><?php echo $row_rs_drug['qty']; ?></td>
      
  </tr><?php } while ($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
  </tbody>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug);
?>
