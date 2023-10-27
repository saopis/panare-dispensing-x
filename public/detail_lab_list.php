<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

//===== setting ==========//


if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }

if(isset($_GET['hn'])){ $hn=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn=$_POST['hn']; }


if(isset($_GET['vn'])){ $vn=$_GET['vn']; }
if(isset($_POST['vn'])){ $vn=$_POST['vn']; }



//ค้นหา Lab แต่ละรายการที่ต้องการแสดง
if($_GET['sex']==1){
	$f=1;
	$normal='97-137';
	}
if($_GET['sex']==2){
	$f=0.85;
	$normal='88-128';
	}

mysql_select_db($database_hos, $hos);
$query_rs_cr = "select  format((((140-v.age_y)*os.bw)/(72*lab_order_result))*".$f.",2) as lab_order_result1,lab_order_result,lab_items_unit,lab_items_normal_value,concat(date_format(order_date,'%d/%m/'),(date_format(order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max,order_date from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join vn_stat v on v.hn=h.hn and v.vstdate=h.order_date where h.hn='".$hn."' and l.lab_items_code='".$row_setting['7']."' order by order_date DESC limit 1";
$rs_cr = mysql_query($query_rs_cr, $hos) or die(mysql_error());
$row_rs_cr = mysql_fetch_assoc($rs_cr);
$totalRows_rs_cr = mysql_num_rows($rs_cr);


mysql_select_db($database_hos, $hos);
$query_dispen_lab1 = "select lab_items_name,lab_order_result,lab_items_unit,lab_items_normal_value,
order_date1,range_check_min,range_check_max,hn,lab_items_code 
from (select h.hn,i.lab_items_code,i.lab_items_name,l.lab_order_result,i.lab_items_unit,i.lab_items_normal_value,concat(date_format(h.order_date,'%d/%m/'),(date_format(h.order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  where h.hn='".$hn."'  order by h.order_date DESC  ) as test 
where  lab_items_code in (select lab_items_code from ".$database_kohrx.".kohrx_dispensing_lab) group by lab_items_code ";
$dispen_lab1 = mysql_query($query_dispen_lab1, $hos) or die(mysql_error());
$row_dispen_lab1 = mysql_fetch_assoc($dispen_lab1);
$totalRows_dispen_lab1 = mysql_num_rows($dispen_lab1);

	if($row_rs_cr['lab_order_result']!=""&&(is_numeric($row_rs_cr['lab_order_result'])==true)){
	//ถ้าเป็นผู้ชาย
	$xx=$row_rs_cr['lab_order_result'];
	$yy=$_GET['age_y'];
	
	if($_GET['sex']==1){ 
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
	if($_GET['sex']==2){ 
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

<script>
$(document).ready(function() {
		$('.lab-long').hide();	
	
    $('.lab-short').click(function(){
		$('.lab-list2').slideUp(500);
		$('.lab-short').hide();	
		$('.lab-long').show();	
	});
    $('.lab-long').click(function(){
		$('.lab-list2').slideDown(500);
		$('.lab-long').hide();	
		$('.lab-short').show();	
	});

});
</script>

</head>

<body>
<div  style="position:absolute; right:10px; margin-top:-40px;"><button type="button" class="btn btn-light btn-sm lab-short" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อ 
</button><button type="button" class="btn btn-light btn-sm lab-long"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย 
</button>
</div>
<div class="lab-list2">
<div class="row thfont font12 " style="padding: 3px;">

<?php $intRows = 0; do { $i++; $intRows++;

if($bgcolor=="#FFFFFF") { $bgcolor="#F4FAFB"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

mysql_select_db($database_hos, $hos);
$query_dispen_lab = "select h.hn,i.lab_items_code,i.lab_items_name,l.lab_order_result,i.lab_items_unit,i.lab_items_normal_value,concat(date_format(h.order_date,'%d/%m/'),(date_format(h.order_date,'%Y')+543)) as order_date1,i.range_check_min,i.range_check_max from lab_order l left join lab_head h on h.lab_order_number=l.lab_order_number left join lab_items i on i.lab_items_code=l.lab_items_code  where h.hn='".$_GET['hn']."' and l.lab_items_code='".$row_dispen_lab1['lab_items_code']."' and lab_order_result is not null order by h.order_date DESC  limit 1";
$dispen_lab = mysql_query($query_dispen_lab, $hos) or die(mysql_error());
$row_dispen_lab = mysql_fetch_assoc($dispen_lab);
$totalRows_dispen_lab = mysql_num_rows($dispen_lab); ?>

    <?php if($totalRows_dispen_lab<>0){ ?>
    <div class="col-md-3">
        <div class="row" style="font-size:12px;">
            <div class="col-md-6 "><nobr><span style="font-weight:bold;font-size:14px;"><?php print $row_dispen_lab['lab_items_name']."</span><br><span style='font-size:8px;'>".$row_dispen_lab['lab_items_normal_value']." ".$row_dispen_lab['lab_items_unit']; ?></span></nobr></div>
            <div class="col-sm text-left"><nobr><span style="font-size:14px; cursor:pointer" onclick="alertload('lab_chart.php?lab=<?php echo $row_dispen_lab['lab_items_code']; ?>&amp;hn=<?php echo $_GET['hn']; ?>&amp;sex=<?php echo $_GET['sex']; ?>&amp;age_y=<?php echo $_GET['age_y']; ?>','90%','90%');" class="badge badge-<?php if(($row_dispen_lab['lab_order_result']<$row_dispen_lab['range_check_min']) || ($row_dispen_lab['lab_order_result']>$row_dispen_lab['range_check_max'])){ echo "danger"; } else { echo "secondary"; }  ?>"><?php echo  "$row_dispen_lab[lab_order_result] "; ?></span>
       </nobr><br /><span style="font-size:10px;"><?php echo $row_dispen_lab['order_date1']; ?></span></div>
        </div>
    </div>

   <?php } ?>
   <?php if(($intRows)%4==0)
		{
        if($intRows!=$totalRows_dispen_lab){
		echo"</div><div class='row thfont font12' style='padding: 3px;' >";
        }
        else{
		echo"</div>";        
        }
		}
                        
    } while($row_dispen_lab1 = mysql_fetch_assoc($dispen_lab1)); ?> 
</div>

</div>
</div>
</body>
</html>