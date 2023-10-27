<?php require_once('Connections/hos.php'); ?>
<?php require_once('Connections/hos2.php'); ?>

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

$date11=explode("/",$_POST['date1']);
$edate1=(($date11[2]-543)."-".$date11[1]."-".$date11[0]);

if($_POST['pttype']=='1'){
	$cpttype=" ipt.dchstts is null ";
	}
if($_POST['pttype']=='2'){
	$cpttype=" ipt.dchstts is not null and ipt.dchdate='$edate1' ";
	}

if($_POST['pttype']=='3'){
	$cpttype=" ipt.dchstts is null and ipt.regdate='$edate1' ";
	}
	
if($_POST['search']!=""){
$condition="";	

if(is_numeric($_POST['search'])){
$condition.=" and ipt.hn like '%$_POST[search]'";
	}

if(!is_numeric($_POST['search'])){
$sexplode=explode(' ',$_POST['search']);
$fname=$sexplode[0];
$lname=$sexplode[1];
if($sexplode[0]!=""){
$condition.=" and patient.fname like '%$fname%'";
}
if($sexplode[1]!=""){
$condition.=" and patient.lname like '%$lname%'";
}
}
}
if($_POST['an']!=""){

if(is_numeric($_POST['an'])){
$condition.=" and ipt.an like '%$_POST[an]'";
	}
}


mysql_select_db($database_hos, $hos);
$query_rs_ipd = "select ipt.an,ipt.hn,ipt.vn,ipt.prediag,aa.pdx,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,dt.name as admdoctor_name ,aa.age_y,aa.age_m,i1.name as diagname,patient.sex,ks.hosp_src   from ipt   left outer join spclty on spclty.spclty=ipt.spclty   left outer join iptadm on iptadm.an=ipt.an   left outer join patient on patient.hn=ipt.hn   left outer join doctor dt on dt.code = ipt.admdoctor   left outer join roomno on roomno.roomno=iptadm.roomno   left outer join iptdiag on iptdiag.an=ipt.an and iptdiag.diagtype='1'   left outer join icd101 i1 on i1.code=substring(iptdiag.icd10,1,3)   left outer join an_stat aa on aa.an=ipt.an   left outer join ward w on w.ward = ipt.ward  left outer join ipt_finance_status fs on fs.an = ipt.an   left outer join finance_status ft on ft.finance_status = fs.finance_status   left outer join pttype ptt on ptt.pttype=ipt.pttype left outer join ".$database_kohrx.".kohrx_med_reconcile_src ks on ks.an=ipt.an  where ".$cpttype." ".$condition." and ipt.hn is not null order by ipt.an ASC";
$rs_ipd = mysql_query($query_rs_ipd, $hos) or die(mysql_error());
$row_rs_ipd = mysql_fetch_assoc($rs_ipd);
$totalRows_rs_ipd = mysql_num_rows($rs_ipd);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
<style>
table.table_bord1 tr td{border:solid 1px #CCCCCC; background-color:#FFF}
table.table_bord1{border-collapse:collapse; border-left:0px;}
table.table_bord1 tr.head td{background-color: #F4F4F4;}
</style>
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<?php include('java_function.php'); ?> 
<script>
function alertloads(url,w,h,str,queue){
	 $.colorbox({width:w,height:h, iframe:true, href:url,onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){window.location.reload();}});

	}			
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="3" class="thfont font12 table_bord1" >
  <tr class="head">
    <td align="center" width="3%">ลำดับ</td>
    <td align="center" width="5%">HN</td>
    <td align="center" width="5%">AN</td>
    <td align="center" width="10%">ชื่อ-นามสกุล</td>
    <td align="center" width="3%">เตียง</td>
    <td align="center" width="10%">แพทย์</td>
    <td align="center" width="15%">diagnosis</td>
    <td align="center" width="5%">แพ้ยา</td>
    <td align="center" width="10%">renal function</td>
    <td align="center" width="10%">med_reconcil</td>
    <td align="center" width="24%">note</td>
  </tr>
  <?php $i=1; do { 

if($row_rs_ipd['sex']==1){
	$f=1;
	$normal='97-137';
	}
if($row_rs_ipd['sex']==2){
	$f=0.85;
	$normal='88-128';
	}

 mysql_select_db($database_hos, $hos);
$query_allergy = "select agent,symptom from opd_allergy where hn = '".$row_rs_ipd['hn']."'";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

mysql_select_db($database_hos, $hos);
$query_rs_note = "select * from ".$database_kohrx.".kohrx_pharmacist_note where hn='".$row_rs_ipd['hn']."' and patient_type='IPD' order by note_date ,note_time DESC limit 1";
$rs_note = mysql_query($query_rs_note, $hos) or die(mysql_error());
$row_rs_note = mysql_fetch_assoc($rs_note);
$totalRows_rs_note = mysql_num_rows($rs_note);

mysql_select_db($database_hos, $hos);
$query_rs_lab = "select lab_order_result from lab_head h left outer join lab_order l on l.lab_order_number=h.lab_order_number where hn='".$row_rs_ipd['hn']."' and l.lab_items_code='".$row_setting['7']."' order by order_date DESC limit 1";
$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
$row_rs_lab = mysql_fetch_assoc($rs_lab);
$totalRows_rs_lab = mysql_num_rows($rs_lab);
  
	if($row_rs_lab['lab_order_result']!=""&&(is_numeric($row_rs_lab['lab_order_result'])==true)){
	//ถ้าเป็นผู้ชาย
	$xx=$row_rs_lab['lab_order_result'];
	$yy=$row_rs_ipd['age_y'];
	
	if($row_rs_ipd['sex']==1){ 
	$zz=1;
	//คำนวณ GFR CKD
	//ถ้า Cr. <=0.9
	$cr_k=$xx/0.9;
	if($xx<=0.9){
	$gfr=141*(pow($cr_k,-0.411))*(pow(0.993,$yy));}
	//ถ้า Cr. >0.9
	if($xx>0.9){
	$gfr=141*(pow($cr_k,-1.209))*(pow(0.993,$yy));}    }
	//ถ้าเป็นผู้หญิง		
	if($row_rs_ipd['sex']==2){ 
	$zz=0.742;
	//คำนวณ GFR CKD
	//ถ้า Cr. <=0.9
	$cr_k=$xx/0.7;
	if($xx<=0.7){
	$gfr=141*(pow($cr_k,-0.329))*(pow(0.993,$yy));     }
	//ถ้า Cr. >0.9
	if($xx>0.7){
	$gfr=141*(pow($cr_k,-1.209))*(pow(0.993,$yy));} 	}			
	//คำนวณ GFR MDRD
	$mdrd=186*pow($xx,-1.154)*pow($yy,-0.203)*$zz;  	}
	
	$result_cr=number_format($gfr,2);
	
	//ประเมินประสิทธิภาพของไต
		if($result_cr>=90){
		$stage="Stage 1 : ไตผิดปกติ* และ GFR ปกติหรือเพิ่มขึ้น";
		$stage2="1";
		}
	if($result_cr<=89&&$result_cr>=60){
		$stage="Stage 2 : ไตผิดปกติ* และ GFR ลดลงเล็กน้อย";
		$stage2="2";
		}
	if($result_cr<=59&&$result_cr>=30){
		$stage="Stage 3 : GFR ลดลงปานกลาง";
		$stage2="3";
		}
	if($result_cr<=29&&$result_cr>=15){
		$stage="Stage 4 : GFR ลดลงมาก";
		$stage2="4";
		}
	if($result_cr<=14){
		$stage="Stage 5 : ไตวายระยะสุดท้าย ต้องทำการฟอกไต";
		$stage2="5";
		}
  
  ?>
  <tr>
    <td align="center" valign="top"><?php echo $i; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['hn']; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['an']; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['name']; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['bedno']; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['admdoctor_name']; ?></td>
    <td align="center" valign="top"><?php echo $row_rs_ipd['diagname']; ?></td>
    <td align="center" valign="top"><?php if($row_allergy['agent']!=""){  ?>
      <img src="images/1088497.gif" width="32" height="32" onclick="alertload1('allergy.php?hn=<?php echo $row_rs_ipd['hn']; ?>','500','600');" style="cursor:pointer" />
    <? } ?></td>
    <td align="center" valign="top"><?php if($row_rs_lab['lab_order_result']!=""){ echo "Sr.Cr.=".$row_rs_lab['lab_order_result']."<br>GFR=".$result_cr; } ?></td>
    <td align="center" valign="top" style="cursor:pointer" onClick="alertloads('med_reconcile_source.php?an=<?php echo $row_rs_ipd['an']; ?>','600','150');"><?php if($row_rs_ipd['hosp_src']!=""){ echo $row_rs_ipd['hosp_src']; }?></td>
    <td align="left" valign="top" onClick="alertloads('pharmacist_note_ipd.php?hn=<?php echo $row_rs_ipd['hn']; ?>','90%','90%');" style="cursor:pointer"><?php echo $row_rs_note['pharmacist_note']; ?></td>
  </tr>
  <?php mysql_free_result($rs_note);

mysql_free_result($rs_lab);?>
  <?php
  $i++;
   } while ($row_rs_ipd = mysql_fetch_assoc($rs_ipd)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rs_ipd);

mysql_free_result($rs_setting);

?>
