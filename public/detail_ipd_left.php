<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/get_channel.php');

//ถ้ามีการเปิดใช้ image server ให้แนบ connection img.php มาด้วย
	if($row_setting[43]=="Y"){
		require_once('Connections/img.php');
	}
?>
<?php
include('include/function.php');
mysql_select_db($database_hos, $hos);
$query_ipt = "select i.bedno,w.name from iptadm i left outer join roomno r on r.roomno=i.roomno left outer join ward w on w.ward=r.ward  where i.an='".$_GET['an']."'";
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);

//หาข้อมูลทั่วไปของผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_patient = "SELECT i.bedno,a.hn,a.an,concat(p.pname,p.fname,'  ',p.lname) as patient_name,a.ward,a.age_y,a.age_m,a.age_d,os.bw,w.name,ptt.name as pttypename,a.regdate,a.dchdate,icd.name as dxname ,dd.name as doctorname FROM an_stat a left outer join pttype ptt on ptt.pttype=a.pttype left outer join opdscreen os on os.vn=a.vn left outer join patient p on p.hn=a.hn left outer join iptadm i on i.an=a.an left outer join ward w on w.ward=a.ward left outer join icd101 icd on icd.code=a.pdx left join doctor dd on dd.code=a.dx_doctor WHERE a.an ='".$_GET['an']."'";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);
$hn=$row_rs_patient['hn'];

mysql_select_db($database_hos, $hos);
$query_allergy = "select hn,report_date,agent,symptom,reporter from opd_allergy where hn='".$row_rs_patient['hn']."' order by report_date DESC";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

<style>
.containerimg {
  position: relative;
  width: 80%;
  left:10%;
}

.image {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  /*top: 50%;*/
  left: 50%;
  bottom: 0px;
  width: 100%;
  transform: translate(-50%);
  -ms-transform: translate( -50%);
  text-align: center;
}

.containerimg:hover .image {
  opacity: 0.3;
}

.containerimg:hover .middle {
  opacity: 1;
}

.text {
  background-color:darkgrey;
  color: white;
  font-size: 14px;
  padding: 5px;
  cursor: pointer;
	border-bottom-left-radius: 8px;
	border-bottom-right-radius: 8px;
}
.text:hover {
  background-color:dodgerblue;
  color: white;
  font-size: 14px;
  padding: 5px;
  cursor: pointer;
	border-bottom-left-radius: 8px;
	border-bottom-right-radius: 8px;
}
</style>
<script>
$(document).ready(function(){    
});
</script>
    
</head>

<body>
<div style="margin-left: 15px; ">
<div class=" p-2 text-center text-white" style="background-color:#445268"><span class="badge badge-dark font16">AN</span> <?php echo $_GET['an']; ?></div>

	<div class="containerimg mt-2">
        <?
	////////// patient name //////////
	
	//ถ้าใช้ image server
	if($row_setting[43]=="Y"){
		mysql_select_db($database_img, $img);
	}
	else{ //กรณีไม่ใช้ image server
		mysql_select_db($database_database, $hos);
	}
 	$query_selpic = "select count(*) as cc from patient_image  where hn='".$row_rs_patient['hn']."' ";
	if($row_setting[43]=="Y"){ 	//ถ้าใช้ image server
		$selpic = mysql_query($query_selpic, $img) or die(mysql_error());
	}
	else{ //กรณีไม่ใช้ image server
		$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	}	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
				if($row_selpic['cc']>0){
				if($row_setting[43]=="Y"){
					mysql_select_db($database_img, $img);
				}
				else{ //กรณีไม่ใช้ image server
					mysql_select_db($database_database, $hos);
				}				
				$query = "SELECT image as blob_img FROM patient_image where hn='".$row_rs_patient['hn']."' "; 
				if($row_setting[43]=="Y"){ 	//ถ้าใช้ image server
					$result = mysql_query($query, $img) or die(mysql_error()); 
				}
				else{ //กรณีไม่ใช้ image server
					$result = mysql_query($query, $hos) or die(mysql_error()); 
				}	
				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 
	mysql_free_result($selpic);
							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> " class="image"  width="100" height="120" vlign="middle" border="0" style="border-radius: 8px; border:solid 1px #E3E1E1"> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"100\" class=\"image\" height=\"120\" />";
							}
							?>
		  <div class="middle ">
			<div class="text" onclick="alertload('caller_key.php?hn=<?php echo $hn; ?>','0','0');        setTimeout(function(){$('#respondent').focus()}, 1000);
">เรียกผู้ป่วย&ensp;<i class="fas fa-headphones-alt font16"></i></div>
		  </div>
        </div>
        <div align="center" class="font14" style="color: #6A6767"><strong>
<?php echo $row_rs_patient['patient_name']; ?></strong></div> 
<div class="text-center" style="color: #6A6767">HN <?php echo $row_rs_patient['hn']; ?></div>

<?php if($totalRows_allergy<>0){ ?>
<div class="" style="border-bottom: solid 1px #B6B6B6;  margin: 10px; margin-bottom: 0px; ">    

<div class=" text-white rounded-top mt-2 p-1" style="background-color:#9F2A38;"><span class="card-title font-weight-bold font14"><i class="fas fa-allergies"></i>&ensp;Drug Allergy</span></div>
            <div class=" rounded-bottom p-1 text-white bg-danger  font12" >
            <?php $a=0; do{ $a++; echo $row_allergy['agent']; if($a!=$totalRows_allergy){ echo ", ";} } while($row_allergy = mysql_fetch_assoc($allergy)); ?>
            </div>
</div>
	<?php } ?>
<div class="" style="border-bottom: solid 1px #B6B6B6;  margin: 10px; margin-bottom: 0px; margin-top:5px; ">    
<div class=" text-white rounded-top mt-2 p-1 text-center" style="background-color:#000000;"><span class="text-white font14">ward : </span><?php echo $row_ipt['name']; ?></div>
            <div class=" rounded-bottom p-1 text-white bg-secondary text-center" >
				<div style="margin-top:-15px;margin-bottom:-10px;">
				เตียง&nbsp;<span style="font-size: 40px; "><?php echo $row_ipt['bedno']; ?></span>
				</div>	
			</div>
</div>
    <div style="margin-top: -5px;padding: 10px; padding-bottom: 5px;"><span class="badge badge-success font14"><i class="fas fa-sign-in-alt"></i>&nbsp;เข้า</span>&nbsp;<span class="font12"><?php echo  dateThai($row_rs_patient['regdate']); ?></span></div>
    <?php if($row_rs_patient['dchdate']!=""){ ?>
    <div style="padding: 10px; padding-top: 0px; padding-bottom: 0px;"><span class="badge badge-dark font14"><i class="fas fa-sign-out-alt"></i>&nbsp;ออก</span>&nbsp;<span class="font12"><?php if($row_rs_patient['dchdate']!=""){ echo  dateThai($row_rs_patient['dchdate']); } ?></span></div>
    <?php } ?>
	<hr class="my-1">
    <div class="ml-2 font14"><span class="font_border">อายุ :</span>&nbsp;<?php echo "$row_rs_patient[age_y] ปี "."$row_rs_patient[age_m] เดือน"; ?></div>
    <div class="ml-2 font14" ><span class="font_border">น้ำหนัก :</span>&nbsp;<?=number_format($row_rs_patient['bw'])."กก."; ?></div>
	<div class="ml-2 font14"><span class="font_border">สิทธิ์ :</span>&nbsp;<?php echo $row_rs_patient['pttypename']; ?></div>
	<div class="ml-2 font14"><span class="font_border">แพทย์ :</span>&nbsp;<?php echo $row_rs_patient['doctorname']; ?></div>

</body>
</html>
<?php 
mysql_free_result($ipt);
mysql_free_result($rs_patient);
mysql_free_result($allergy);
?>