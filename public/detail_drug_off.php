<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>

<?php
include('include/get_channel.php');

$vstdate1=date_th2db($_GET['vstdate']);

//===== setting ==========//
if($row_setting[41]=="Y"){
///// ค้นหายาที่แพทย์ off //////
//========== pdx =================//
mysql_select_db($database_hos, $hos);
$query_s_pdx = "select code,name from icd101 where code='".$_GET['pdx']."'";
$s_pdx = mysql_query($query_s_pdx, $hos) or die(mysql_error());
$row_s_pdx = mysql_fetch_assoc($s_pdx);
$totalRows_s_pdx = mysql_num_rows($s_pdx);

// หาว่า diag ปัจจุบันมีในรายการที่กำหนดหรือไม่
mysql_select_db($database_hos, $hos);
$query_chronic_diag = "select * from ".$database_kohrx.".kohrx_icd10_chronic_check where '".$row_s_pdx['code']."' BETWEEN diag1 and diag2";
//echo $query_chronic_diag;
$chronic_diag = mysql_query($query_chronic_diag, $hos) or die(mysql_error());
$row_chronic_diag = mysql_fetch_assoc($chronic_diag);
$totalRows_chronic_diag = mysql_num_rows($chronic_diag);

	//ถ้ามี
	if($totalRows_chronic_diag<>0){
		//หา vn ล่าสุดที่ได้รับการ diag เช่นครั้งนี้
		mysql_select_db($database_hos, $hos);
		$query_rs_last_vn = "SELECT s.vn from opitemrece_summary s LEFT JOIN vn_stat v on v.vn=s.vn where rxdate < '".$vstdate1."' and v.pdx='".$row_s_pdx['code']."' and v.hn='".$_GET['hn']."' order by rxdate DESC limit 1 ";
		//echo $query_rs_last_vn;
		$rs_last_vn = mysql_query($query_rs_last_vn, $hos) or die(mysql_error());
		$row_rs_last_vn = mysql_fetch_assoc($rs_last_vn);
		$totalRows_rs_last_vn = mysql_num_rows($rs_last_vn);
	}
		mysql_free_result($chronic_diag);

}
else if($row_setting[41]=='N'){
		mysql_select_db($database_hos, $hos);
		$query_rs_last_vn = "SELECT s.vn from opitemrece_summary s LEFT JOIN vn_stat v on v.vn=s.vn where rxdate < '".$vstdate1."' and v.hn='".$_GET['hn']."' and icode like '1%' order by rxdate DESC limit 1 ";
		//echo $query_rs_last_vn;
		$rs_last_vn = mysql_query($query_rs_last_vn, $hos) or die(mysql_error());
		$row_rs_last_vn = mysql_fetch_assoc($rs_last_vn);
		$totalRows_rs_last_vn = mysql_num_rows($rs_last_vn);
}


if($totalRows_rs_last_vn<>0){
mysql_select_db($database_hos, $hos);
$query_rs_last_drug = "select concat(d.name,' ',d.strength) as drugname,s.icode,s.rxdate,k.real_use,s.qty,u.shortlist,s.drugusage from opitemrece s left outer join ".$database_kohrx.".kohrx_drugusage_realuse k on k.drugusage=s.drugusage left outer join drugusage u on u.drugusage=s.drugusage left outer join vn_stat v on v.vn=s.vn left outer join drugitems d on d.icode=s.icode 
where s.icode in (select icode from ".$database_kohrx.".kohrx_drug_chronic_check) and s.icode not in 
(select icode from opitemrece_summary o LEFT outer JOIN vn_stat vn on vn.vn=o.vn where o.vn='".$_GET['vn']."' and o.icode like '1%' ) 
  and v.vn='".$row_rs_last_vn['vn']."'";
$rs_last_drug = mysql_query($query_rs_last_drug, $hos) or die(mysql_error());
$row_rs_last_drug = mysql_fetch_assoc($rs_last_drug);
$totalRows_rs_last_drug = mysql_num_rows($rs_last_drug);

mysql_free_result($rs_last_vn);
	
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php if($totalRows_rs_last_drug<>0){ ?>
<table border="0" align="center" cellpadding="2" cellspacing="0" style="width:100%">
  <tr >
    <td height="27" colspan="2" width="37%" class="table_head_small_white bg-danger" style="padding-left:10px" ><strong>::  รายการยาที่แพทย์สั่งหยุดใช้  [ OFF ]</strong></td>
    <td height="27" width="32%"  class="table_head_small_white bg-danger" >วิธีใช้</td>
    <td height="27" width="8%" align="center"  class="table_head_small_white bg-danger" >จำนวน</td>
    <td height="27" width="15%" align="center"  class="table_head_small_white bg-danger" >ได้รับครั้งสุดท้าย</td>
    <td  width="8%" class="table_head_small_white bg-danger" >&nbsp;</td>
  </tr>
  <?php $i=0; do{ $i++;
	mysql_select_db($database_hos, $hos);
	$query_rs_drug_check = "select * from ".$database_kohrx.".kohrx_drug_use_change where vn='".$vn."' and hn='".$hn."' and icode='".$row_rs_last_drug['icode']."' and  change_type='off'";
	$rs_drug_check = mysql_query($query_rs_drug_check, $hos) or die(mysql_error());
	$row_rs_drug_check = mysql_fetch_assoc($rs_drug_check);
	$totalRows_drug_check = mysql_num_rows($rs_drug_check);
 ?>
  <tr class="grid5">
    <td height="29" align="center" bgcolor="#FDD7DA" class="table_head_small" style="border-left:solid 1px #C7E4F1" ><?php echo $i.".)"; ?></td>
    <td bgcolor="#FDD7DA" class="small_red_bord text-dark" ><?php echo $row_rs_last_drug['drugname']; ?></td>
    <td bgcolor="#FDD7DA" class="small_red text-dark" ><?php echo $row_rs_last_drug['shortlist']; ?></td>
    <td align="center" bgcolor="#FDD7DA"  ><span class="small_red text-dark"><?php echo $row_rs_last_drug['qty']; ?></span></td>
    <td align="center" bgcolor="#FDD7DA" ><span class="small_red text-dark"><?php echo date_db2th($row_rs_last_drug['rxdate']); ?></span></td>
    <td align="center" bgcolor="#FDD7DA" ><?php if($totalRows_drug_check==0){ ?>
      <a href="javascript:valid();" onclick="alertload('drug_profile_log.php?action=add&amp;icode=<?php echo $row_rs_last_drug['icode']; ?>&amp;vn=<?php echo $_GET['vn']; ?>&amp;hn=<?php echo $_GET['hn']; ?>&amp;doctor=<?php echo $row_s_doctor['code']; ?>&amp;real_use=<?php echo $row_rs_last_drug['real_use']; ?>&amp;app_day=<?php echo $row_oapp['date_diff']; ?>&amp;drugusage=<?php echo $row_rs_last_drug['drugusage']; ?>&pdx=<?php echo $_GET['pdx']; ?>&vstdate=<?php echo $vstdate1; ?>');" class="badge badge-primary text-white font14">เพิ่ม</a>
      <?php } ?>
      <?php if($totalRows_drug_check<>0){ ?>
      <a href="javascript:valid();" onclick="alertload('drug_profile_log.php?action=delete2&amp;icode=<?php echo $row_rs_last_drug['icode']; ?>&amp;vn=<?php echo $_GET['vn']; ?>&amp;hn=<?php echo $_GET['hn']; ?>&amp;doctor=<?php echo $row_s_doctor['code']; ?>&pdx=<?php echo $_GET['pdx']; ?>&vstdate=<?php echo $vstdate1; ?>')" class="badge badge-danger text-white font14">ลบ</a>
      <?php } ?></td>
  </tr>
  <?php  }while($row_rs_last_drug = mysql_fetch_assoc($rs_last_drug)
); ?>
</table>
<?php } ?>
</body>
</html>

<?php 
if($totalRows_rs_last_vn<>0){ 
 mysql_free_result($rs_last_drug); 
 } ?>