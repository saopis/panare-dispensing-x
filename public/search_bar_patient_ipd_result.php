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

include('include/function.php');

if($_GET['pttype']=='1'){
	$cpttype=" ipt.dchstts is null ";
	}
if($_GET['pttype']=='2'){
	$cpttype=" ipt.dchstts is not null and ipt.dchdate between '".$_GET['datestart']."' and '".$_GET['dateend']."'  ";
	}

if($_GET['pttype']=='3'){
	$cpttype=" ipt.dchstts is null and ipt.regdate between '".$_GET['datestart']."' and '".$_GET['dateend']."' ";
	}
	
if($_GET['hn']!=""){
$condition.="";	

if(is_numeric($_GET['hn'])){
$condition.=" and ipt.hn like '%".$_GET['hn']."%'";
	}

if(!is_numeric($_GET['hn'])){
$sexplode=explode(' ',$_GET['hn']);
$fname=$sexplode[0];
$lname=$sexplode[1];
if($sexplode[0]!=""){
$condition.=" and patient.fname like '%".$fname."%'";
}
if($sexplode[1]!=""){
$condition.=" and patient.lname like '%".$lname."%'";
}
}
}
if($_GET['an']!=""){

if(is_numeric($_GET['an'])){
$condition.=" and ipt.an like '%".$_GET['an']."%'";
	}
}

if($_GET['ward']!=""){
	$condition.=" and roomno.ward='".$_GET['ward']."'";
}

mysql_select_db($database_hos, $hos);
$query_ipt = "select ipt.an,ipt.hn,ipt.vn,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,dt.name as admdoctor_name ,aa.age_y,aa.age_m,ward.name as wardname   from ipt   left outer join spclty on spclty.spclty=ipt.spclty   left outer join iptadm on iptadm.an=ipt.an   left outer join patient on patient.hn=ipt.hn   left outer join doctor dt on dt.code = ipt.admdoctor   left outer join roomno on roomno.roomno=iptadm.roomno   left outer join iptdiag on iptdiag.an=ipt.an and iptdiag.diagtype='1'   left outer join icd101 i1 on i1.code=substring(iptdiag.icd10,1,3)   left outer join an_stat aa on aa.an=ipt.an   left outer join ward w on w.ward = ipt.ward  left outer join ipt_finance_status fs on fs.an = ipt.an   left outer join finance_status ft on ft.finance_status = fs.finance_status   left outer join pttype ptt on ptt.pttype=ipt.pttype left outer join ward on ward.ward=roomno.ward  where ".$cpttype." ".$condition."  and ipt.hn is not null order by ipt.an ASC";
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
//$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
	
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
</head>

<body>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; padding-top: 0px;">
<table width="100%" class="table table-sm table-striped table-hover">
  <tr class="font14 bg-dark text-white" >
    <td align="center" ><strong>ลำดับ</strong></td>
    <td align="center" ><strong>AN</strong></td>
    <td align="center" ><strong>HN</strong></td>
    <td align="center" ><strong>ชื่อ - นามสกุล </strong></td>
    <td align="center" ><strong>icon</strong></td>	  
    <td align="center" ><strong>แพ้ยา</strong></td>
    <td align="center" ><strong>อายุ</strong></td>
    <td align="center" ><strong>regdate</strong></td>
    <td align="center" ><strong>time</strong></td>
    <td align="center" ><strong>เตียง </strong></td>
    <td align="center" ><strong>MR</strong></td>
    <td align="center" ><strong>ห้อง</strong></td>
    <td align="center" ><strong>ตึก</strong></td>
  </tr>
  <? for($i=1;$i<=$totalRows_ipt;$i++){ $row_ipt = mysql_fetch_assoc($ipt); 
 mysql_select_db($database_hos, $hos);
$query_allergy = "select agent,symptom from opd_allergy where hn = '$row_ipt[hn]'";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

mysql_select_db($database_hos, $hos);
$query_mr = "select * from ".$database_kohrx.".kohrx_med_reconcile where hn = '".$row_ipt['hn']."' and vstdate2='".$row_ipt['regdate']."' group by hn";
$rs_mr = mysql_query($query_mr, $hos) or die(mysql_error());
$row_rs_mr = mysql_fetch_assoc($rs_mr);
$totalRows_rs_mr = mysql_num_rows($rs_mr);

mysql_select_db($database_hos, $hos);
$query_rs_icon = "select o.icode,t.* from opitemrece o left outer join ".$database_kohrx.".kohrx_drug_trigger t on t.icode=o.icode where o.icode in (select icode from ".$database_kohrx.".kohrx_drug_trigger ) and o.an='".$row_ipt['an']."' group by o.icode";
$rs_icon = mysql_query($query_rs_icon, $hos) or die(mysql_error());
$row_rs_icon = mysql_fetch_assoc($rs_icon);
$totalRows_rs_icon = mysql_num_rows($rs_icon);
									   
  ?>
  <tr onClick="parent.an_search('<?php echo $row_ipt['an']; ?>');parent.$.fn.colorbox.close();" class="font12">
    <td align="center"><?php echo $i; ?>&nbsp;</td>
    <td align="center"><a  > <?php echo $row_ipt['an']; ?></a></td>
    <td align="center"><a  > <?php echo $row_ipt['hn']; ?></a></td>
    <td align="left" ><a  > <strong><?php echo $row_ipt['name']; ?></strong></a></td>
    <td align="left" ><?php do{ ?><span class="badge badge-<?php echo $row_rs_icon
	['trigger_color']; ?> font14"><?php echo $row_rs_icon['drug_prefix']; ?></span><?php }while($row_rs_icon = mysql_fetch_assoc($rs_icon)); ?></td>
    <td align="center"><?php if($totalRows_allergy<>0){  ?>
      <span class="badge badge-danger font14" onclick="MM_openBrWindow('allergy.php?hn=<?php echo $row_ipt['hn']; ?>','','scrollbars=yes,width=500,height=500')">แพ้ยา</span>
      <? } ?></td>
    <td align="center"><?php echo $row_ipt['age_y']; ?> ปี <?php echo $row_ipt['age_m']; ?> เดือน </td>
    <td align="center"><?php echo dateThai3($row_ipt['regdate']); ?></td>
    <td align="center"><?php echo substr($row_ipt['regtime'],0,5); ?></td>
    <td align="center"><span class="badge badge-dark font14" style="width: 50px;" ><?php echo $row_ipt['bedno']; ?></span></td>
    <td align="center"><?php if($totalRows_rs_mr<>0){ ?><span class="badge badge-success font14" >MR</span><?php } ?></td>
    <td align="center"><?php echo $row_ipt['room']; ?></td>
    <td align="center"><?php echo $row_ipt['wardname']; ?></td>	 
  </tr>
  <? mysql_free_result($allergy);mysql_free_result($rs_mr);mysql_free_result($rs_icon);} ?>
</table>
	</div>
</body>
</html>
<?php
//mysql_free_result($rs_patient);

mysql_free_result($ipt);
?>
