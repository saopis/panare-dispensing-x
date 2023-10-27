<?php require_once('Connections/hos.php'); ?>
<?php 
	$sexplode=explode(' ',$_GET['keyword']);
	$fname=$sexplode[0];
	$lname=$sexplode[1];
	if($sexplode[0]!=""){
	$condition.="where fname like '%$fname%'";
	}
	if($sexplode[1]!=""){
	$condition.=" and lname like '%$lname%'";
	}

if($_GET['keyword']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_patient = "select hn,pname,fname,lname,fathername,mathername,cid from patient where hn like '%".$_GET['keyword']."' or cid like '".$_GET['keyword']."%' or ( fname like '%".$fname."%' and lname like '%".$lname."%' ) limit 50";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php if($totalRows_rs_patient<>0){ ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="t1 table-striped  dt-responsive nowrap" style="width:100%">
  <thead>
  <tr >
    <th align="center" bgcolor="#9DBAD5" style="padding-left: 10px;" >HN</th>
    <th align="center" bgcolor="#9DBAD5"  >ชื่อ</th>
    <th align="center" bgcolor="#9DBAD5"  >นามสกุล</th>
    <th align="center" bgcolor="#9DBAD5"  >cid</th>
    <th align="center" bgcolor="#9DBAD5"  >บิดา</th>
    <th align="center" bgcolor="#9DBAD5"  >มารดา</th>
  </tr>
  </thead>
  <tbody>
  <?php do{ ?>
  <tr class="cursor" onClick="detail_load('<?php echo $row_rs_patient['hn']; ?>','<?php echo $_GET['vstdate']; ?>');" data-dismiss="modal">
    <td align="left" style="padding-left: 10px;"><?php echo "$row_rs_patient[hn]"; ?></td>
    <td align="left"><?php echo "$row_rs_patient[pname]"."$row_rs_patient[fname]"; ?></td>
    <td align="left"><?php echo "$row_rs_patient[lname]"; ?></td>
    <td align="left"><?php echo "$row_rs_patient[cid]"; ?></td>
    <td align="left"><?php echo "$row_rs_patient[fathername]"; ?></td>
    <td align="left"><?php echo "$row_rs_patient[mathername]"; ?></td>
  </tr><? }while($row_rs_patient = mysql_fetch_assoc($rs_patient)); ?>
  </tbody>
</table>
<?php } ?>
</body>
</html>
<?php
if($_GET['keyword']!=""){

mysql_free_result($rs_patient);
}
 ?>
