<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php 
include('include/function.php');

if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }
if(isset($_GET['hn'])){ $hn=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn=$_POST['hn']; }
if(isset($_GET['vn'])){ $vn=$_GET['vn']; }


mysql_select_db($database_hos, $hos);
$query_clinic = "select substring(icd10,1,2) as subicd10 from clinicmember m left outer join clinic c on c.clinic=m.clinic where hn='".$hn."'";
$clinic = mysql_query($query_clinic, $hos) or die(mysql_error());
$row_clinic = mysql_fetch_assoc($clinic);
$totalRows_clinic = mysql_num_rows($clinic);

$clinic_array=array();
	do{
	$clinic_array[]=$row_clinic['subicd10'];
	}while($row_clinic = mysql_fetch_assoc($clinic));

mysql_free_result($clinic);

//visit count
mysql_select_db($database_hos, $hos);
$query_visit_count = "select count(hn) from vn_stat where vstdate='".$vstdate."' and hn='".$hn."' ";
$visit_count = mysql_query($query_visit_count, $hos) or die(mysql_error());
$row_visit_count = mysql_fetch_assoc($visit_count);
$totalRows_visit_count = mysql_num_rows($visit_count);

mysql_select_db($database_hos, $hos);
$query_warfarin = "SELECT * from ".$database_kohrx.".kohrx_patient_warfarin WHERE hn='".$hn."'";
$warfarin = mysql_query($query_warfarin, $hos) or die(mysql_error());
$row_warfarin = mysql_fetch_assoc($warfarin);
$totalRows_warfarin = mysql_num_rows($warfarin);

mysql_select_db($database_hos, $hos);
$query_g6pd = "SELECT * from ".$database_kohrx.".kohrx_patient_g6pd WHERE hn='".$hn."'";
$g6pd = mysql_query($query_g6pd, $hos) or die(mysql_error());
$row_g6pd = mysql_fetch_assoc($g6pd);
$totalRows_g6pd = mysql_num_rows($g6pd);

//======= screen ========//
mysql_select_db($database_hos, $hos);
$query_screen = "select bpd,bps,bw,cc,pe,hr,pulse,temperature,pregnancy,breast_feeding,height from opdscreen where hn='".$hn."' and vstdate='".$vstdate."'";
$screen = mysql_query($query_screen, $hos) or die(mysql_error());
$row_screen = mysql_fetch_assoc($screen);
$totalRows_screen = mysql_num_rows($screen);
?>
<img src="images/pathwa.png" width="54" height="54" border="0" align="absmiddle" class="cursor" onClick="pathway_show('<?php echo $_GET['vn']; ?>')" />
<?php
  if($row_visit_count['count(hn)']>1){
  echo "<a href=\"javascript:alertload('visit_list2.php?hn=".$hn."&vstdate=".$vstdate."&action=popup','70%','90%');\"><img src=\"images/multi_visit.png\" width=\"60\" height=\"60\" align=\"absmiddle\"  style=\"padding:5px\"/></a>";
  }

mysql_free_result($visit_count);

//age
if($_GET['age_y']>=60){ echo "<img src=\"images/older.jpg\" align=\"absmiddle\" width=\"60\" height=\"60\"  style=\"padding:5px\"/>"; } if ($row_screen['breast_feeding']=="Y"){ echo "<img src=\"images/lactation.jpg\" width=\"60\" align=\"absmiddle\" height=\"60\"  style=\"padding:5px\"/>"; } ?><?php if ($row_screen['pregnancy']=="Y"){ echo "<img src=\"images/pregnancy.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\"  style=\"padding:5px\"/>"; } 

//DM
if(in_array('E1',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="E1"||substr($_GET['dx0'],0,2)=="E1"||substr($_GET['dx1'],0,2)=="E1"||substr($_GET['dx2'],0,2)=="E1"||substr($_GET['dx3'],0,2)=="E1"||substr($_GET['dx4'],0,2)=="E1"||substr($_GET['dx5'],0,2)=="E1"){ echo "<img src=\"images/dm.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>"; } 

//HT
if(in_array('I1',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="I1"||substr($_GET['dx0'],0,2)=="I1"||substr($_GET['dx1'],0,2)=="I1"||substr($_GET['dx2'],0,2)=="I1"||substr($_GET['dx3'],0,2)=="I1"||substr($_GET['dx4'],0,2)=="I1"||substr($_GET['dx5'],0,2)=="I1"){ echo "<img src=\"images/ht.jpg\" width=\"60\" height=\"60\"  style=\"padding:5px\" align=\"absmiddle\" />"; } 

//heart
if(in_array('I2',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="I2"||substr($_GET['dx0'],0,2)=="I2"||substr($_GET['dx1'],0,2)=="I2"||substr($_GET['dx2'],0,2)=="I2"||substr($_GET['dx3'],0,2)=="I2"||substr($_GET['dx4'],0,2)=="I3"||substr($_GET['dx5'],0,2)=="I2"){ echo "<img src=\"images/heart.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>"; } 

//liver
if(in_array('K7',$clinic_array,TRUE)||(substr($_GET['pdx'],0,3)>="K70"&&substr($_GET['pdx'],0,3)<="K77")||(substr($_GET['dx0'],0,3)>="K70"&&substr($_GET['dx0'],0,3)<="K77")||(substr($_GET['dx1'],0,3)>="K70"&&substr($_GET['dx1'],0,3)<="K77")||(substr($_GET['dx2'],0,3)>="K70"&&substr($_GET['dx2'],0,3)<="K77")||(substr($_GET['dx3'],0,3)>="K70"&&substr($_GET['dx3'],0,3)<="K77")||(substr($_GET['dx4'],0,3)>="K70"&&substr($_GET['dx4'],0,3)<="K77")||(substr($_GET['dx5'],0,3)>="K70"&&substr($_GET['dx5'],0,3)<="K77")){ echo "<img src=\"images/liver.jpg\" width=\"60\" height=\"60\"  style=\"padding:5px\" align=\"absmiddle\" />"; } 

//asthma
if(in_array('J4',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="J4"){ echo "<img src=\"images/asthma.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>"; } 

//psychosis
if(in_array('F1',$clinic_array,TRUE)||in_array('F2',$clinic_array,TRUE)||in_array('F3',$clinic_array,TRUE)||in_array('F4',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="F1"||substr($row_s_pdx['code'],0,2)=="F2"||substr($row_s_pdx['code'],0,2)=="F3"||substr($row_s_pdx['code'],0,2)=="F4"){ echo "<img src=\"images/psychosis.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\"  style=\"padding:5px\"/>"; }  

//thyroid
if(in_array('E0',$clinic_array,TRUE)||substr($row_s_pdx['code'],0,2)=="E0"){ echo "<img src=\"images/thyroid.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>"; } 

//kidney
if((substr($_GET['pdx'],0,3)>="N17"&&substr($_GET['pdx'],0,3)<="N19")||(substr($_GET['dx0'],0,3)>="N17"&&substr($_GET['dx0'],0,3)<="N19")||(substr($_GET['dx1'],0,3)>="N17"&&substr($_GET['dx1'],0,3)<="N19")||(substr($_GET['dx2'],0,3)>="N17"&&substr($_GET['dx2'],0,3)<="N19")||(substr($_GET['dx3'],0,3)>="N17"&&substr($_GET['dx3'],0,3)<="N19")||(substr($_GET['dx4'],0,3)>="N17"&&substr($_GET['dx4'],0,3)<="N19")||(substr($_GET['dx5'],0,3)>="N17"&&substr($_GET['dx5'],0,3)<="N19")){ echo "<img src=\"images/kidney.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\"  style=\"padding:5px\"/>"; }

//warfarin
if($totalRows_warfarin<>0){echo "<img src=\"images/warfarin.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>";}

//g6pd
if($totalRows_g6pd<>0){echo "<img src=\"images/g6pd.jpg\" width=\"60\" height=\"60\" align=\"absmiddle\" style=\"padding:5px\"/>";}

/* echo "
<table width=\"70\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"70\" >
  <tr>
    <td valign=\"bottom\" style=\"background-image:url(images/adr_check.jpg); background-repeat:no-repeat; background-position:center; background-size:60px 60px\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_head_small\">
      <tr>
        <td align=\"center\" class=\"table_head_smaller\">". $row_adr_check['check_date']."</td>
      </tr>
      <tr>
        <td align=\"center\">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
"; */
  
  if((in_array($_GET['pttype'],explode(",",$row_setting['4']))) or ($_GET['an']!="")){
	  if($totalRows_sc_pay2<>0){ $payment= "3"; } else { $payment= "2"; }
  echo "<a href=\"javascript:alertload1('income.php?vn=".$_GET['vn']."','70%','90%');\"><img src=\"images/payment".$payment.".jpg\" width=\"60\" height=\"60\" align=\"absmiddle\"  style=\"padding:5px\"/></a>";
  }

mysql_free_result($warfarin);
mysql_free_result($g6pd);
mysql_free_result($screen);

 ?>
 
