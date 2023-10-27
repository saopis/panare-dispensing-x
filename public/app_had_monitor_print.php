<?php require_once('Connections/hos.php'); ?>
<?php

  	mysql_select_db($database_hos, $hos);
	$query_logo = "select * from ".$database_kohrx.".kohrx_dispensing_setting where name = 'logo' ";
	$rs_logo = mysql_query($query_logo, $hos) or die(mysql_error());
	$row_logo = mysql_fetch_assoc($rs_logo);
	$totalRows_check = mysql_num_rows($rs_logo);

  	mysql_select_db($database_hos, $hos);
	$query_patient = "select concat(p.pname,p.fname,' ',p.lname) as ptname,v.vn,p.hn,v.age_y,v.age_m,v.age_d,o.bw from vn_stat v left outer join patient p on p.hn=v.hn left outer join opdscreen o on o.vn=v.vn where p.hn = '".$_GET['hn']."' and v.vstdate='".$_GET['vstdate']."' ";
	//echo $query_patient;
	$rs_patient = mysql_query($query_patient, $hos) or die(mysql_error());
	$row_patient = mysql_fetch_assoc($rs_patient);

        
		mysql_select_db($database_hos, $hos);
		$query_rs_allergy = "select agent from opd_allergy where hn='".$_GET['hn']."' order by agent ";
		$rs_allergy = mysql_query($query_rs_allergy, $hos) or die(mysql_error());
		$row_rs_allergy = mysql_fetch_assoc($rs_allergy);
		$totalRows_rs_allergy = mysql_num_rows($rs_allergy);

			mysql_select_db($database_hos, $hos);
			$query_rs_drug = "select name as drugname,strength from drugitems where icode='".$_GET['icode']."' ";
			//echo $query_patient;
			$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
			$row_rs_drug = mysql_fetch_assoc($rs_drug);
?>
<? 
 $ThaiMonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

function ThaiDate($InputDate)
{
global $ThaiMonth;
$day=substr($InputDate,8,2);
$month=substr($InputDate,5,2);
$month=(int)$month-1;
$year=substr($InputDate,0,4);
$year=$year+543;
$month=$ThaiMonth[$month];
$thaidatenew= (int)$day." ".$month."  พ.ศ. ".$year;
return $thaidatenew;
} 

function date_db2th($date){
	if($date!=""){
	$date1=explode('-',$date);
	$edate=$date1[2]."/".$date1[1]."/".($date1[0]+543);
	return $edate;
	}
}	
function date_db2th2($date){
	if($date!=""){
	$date1=explode('-',$date);
	$edate=$date1[2]."/".$date1[1]."/".($date1[0]);
	return $edate;
	}
}	
?>

<?php
//The function returns the no. of business days between two dates and it skips the holidays
function getWorkingDays($startDate,$endDate,$holidays){
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            $no_remaining_days -= 2;
        }
    }

   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}
?>
<?php
	function compareDate($date1,$date2) {
		$arrDate1 = explode("-",$date1);
		$arrDate2 = explode("-",$date2);
		$timStmp1 = mktime(0,0,0,$arrDate1[1],$arrDate1[2],$arrDate1[0]);
		$timStmp2 = mktime(0,0,0,$arrDate2[1],$arrDate2[2],$arrDate2[0]);

		if ($timStmp1 == $timStmp2) {
			$result= 1;
			return;
		} else if ($timStmp1 > $timStmp2) {
			$result= 2;
			return;
		} else if ($timStmp1 < $timStmp2) {
			$result= 3;
			return;
		}
	}

function convert($number){ 
$txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
$txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
$number = str_replace(",","",$number); 
$number = str_replace(" ","",$number); 
$number = str_replace("บาท","",$number); 
$number = explode(".",$number); 
if(sizeof($number)>2){ 
return 'ทศนิยมหลายตัวนะจ๊ะ'; 
exit; 
} 
$strlen = strlen($number[0]); 
$convert = ''; 
for($i=0;$i<$strlen;$i++){ 
	$n = substr($number[0], $i,1); 
	if($n!=0){ 
		if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
		elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
		elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
		else{ $convert .= $txtnum1[$n]; } 
		$convert .= $txtnum2[$strlen-$i-1]; 
	} 
} 

$convert .= 'บาท'; 
if($number[1]=='0' OR $number[1]=='00' OR 
$number[1]==''){ 
$convert .= 'ถ้วน'; 
}else{ 
$strlen = strlen($number[1]); 
for($i=0;$i<$strlen;$i++){ 
$n = substr($number[1], $i,1); 
	if($n!=0){ 
	if($i==($strlen-1) AND $n==1){$convert 
	.= 'เอ็ด';} 
	elseif($i==($strlen-2) AND 
	$n==2){$convert .= 'ยี่';} 
	elseif($i==($strlen-2) AND 
	$n==1){$convert .= '';} 
	else{ $convert .= $txtnum1[$n];} 
	$convert .= $txtnum2[$strlen-$i-1]; 
	} 
} 
$convert .= 'สตางค์'; 
} 
return $convert; 
} 

/**
 * เวลาเรียกใช้ให้เรียก ThaiBahtConversion(1234020.25); ประมาณนี้
 * @param numberic $amount_number
 * @return string 
 */
function ThaiBahtConversion($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    //echo "<br/>amount = " . $amount_number . "<br/>";
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    //list($number, $fraction) = explode(".", $number);
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    //return iconv("UTF-8", "TIS-620", $ret);
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
function number_format2($number){
	if($number!=0){
	$number2=str_replace('.0000','',number_format($number,2));
	return $number2;
	}
	if($number==0||$number==""){
	return 0;
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
	
<style>
body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font-size: 16px;
    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page {
        width: 25cm;
        min-height: 37cm;
        padding: 2cm;
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);*/
    }
    .subpage {
        padding: 0px;
        /*border: 5px red solid;*/
        height: 29.7cm;
        width: 21cm;
        outline: 0 #FFFFFF solid;
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
		.noprint,.printbtn { display:none; }
        .page {
            margin: 1cm;
            padding: 1.5cm;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
            font-size: 16px;
        }
    }
p.stb { text-indent: 0; margin-top: 0.8em;}
p.mtb { text-indent: 0; margin-top: 2.17em }
p.ltb { text-indent: 0; margin-top: 3.08em }
.dotshed {  border-bottom: dotted 1px #646464;}
.footer {
  position: fixed;
  left: 0;
  bottom: 30;
  width: 100%;
  color: white;
}
    .table-bordered td, .table-bordered th{
    border-color: black !important;
}
@font-face {
    font-family: th_saraban;
    src: url(font/thsarabunnew-webfont.woff);
}
.thfont{
   font-family: th_saraban;
	}
.font12{
	font-size:13px;
}
.font13{
	font-size:14px;
}
.font14{
	font-size:15px;
}
.font15{
	font-size:16px;
}
.font16{
	font-size:17px;
}
.font17{
	font-size:18px;
}
.font18{
	font-size:19px;
}
.font19{
	font-size:20px;
}
.font20{
	font-size:21px;
}
.font_bord{
	font-weight:bold;
}
.font_border{
	font-weight:bolder;
}	
.w-a4{
		width:21cm;
}
	.w-a4-50{
		width:8cm;
	}
table, th, td .table-board {
  border: 1px solid black;
}	
.td-board {
  border: 1px solid black;
}	
	.align-top{
	text-align: center;	
	vertical-align: top;	
	}	
	.book{
		color:black;
	}
</style>
	
</head>

<body>
<div class="book">
	<!-- page 1 -->
	
	<div class="page">
		<div class="subpage">
		<table class="table-board" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td class="td-board" style="padding: 5px">                              
							<?php
                                if($row_logo['picture']!=""){
                                    echo "<img src=\"data:image/jpeg;base64,".base64_encode($row_logo['picture'])."\" vlign=\"middle\" border=\"0\" width=\"100\" height\"100\" >";
                                }
                              ?>
				</td>	
			<td class="td-board">
				<div class="thfont font-weight-bolder font18 text-center">บันทึกเฝ้าระวังการใช้ยา</div>
				<div class="thfont font-weight-bolder font16 text-center"><?php echo $row_rs_drug['drugname']; ?></div>
				<div class="thfont font-weight-bolder font16 text-center">ความแรง : <?php echo $row_rs_drug['strength']; ?></div>
			</td>	
			<td class="td-board" style="padding-left: 10px;">
				<div class="thfont font14 "><span class="font-weight-bolder">ชื่อ-สกุล</span>&nbsp;<?php echo $row_patient['ptname']; ?> <span class="font-weight-bolder">HN</span>&nbsp;<?php echo $_GET['hn']; ?>&nbsp;<span class="font-weight-bolder">AN</span>.......................</div>
				<div class="thfont font14 "><span class="font-weight-bolder">อายุ</span> <?php echo $row_patient['age_y'].' ปี '.$row_patient['age_m'].' เดือน '.$row_patient['age_d'].' วัน'; ?> <span class="font-weight-bolder">น้ำหนัก</span>&nbsp;<?php echo number_format2($row_patient['bw']).' กิโลกรัม '; ?></div>			
				<div class="thfont font14 "><span class="font-weight-bolder">แพ้ยา</span>&nbsp;<?php $i=0; do{ $i++; echo $row_rs_allergy['agent']; if($totalRows_rs_allergy<>0&&$totalRows_rs_allergy!=$i){echo ","; }  }while($row_rs_allergy = mysql_fetch_assoc($rs_allergy));?></div>			
				<div class="thfont font14 "><span class="font-weight-bolder">แพทย์</span>&nbsp;.....................................<span class="font-weight-bolder">วัน/เดือน/ปี</span>&nbsp;<?php echo date_db2th($_GET['vstdate']); ?></div>
				<div class="thfont font14 "><span class="font-weight-bolder">บริหารยาที่</span>&nbsp;[&ensp;]&nbsp;ER&emsp;[&ensp;]&nbsp;WARD&emsp;[&ensp;]&nbsp;LR&emsp;[&ensp;]&nbsp;PCU</div>
				</td>	
			</tr>
		</table>
		<div class="mt-3">
			<img src="document/had/<?php echo $_GET['icode']; ?>.png" width="100%" height="auto"/>
		</div>	
		</div>
	</div>
</div>	
	
</body>
</html>
<?php mysql_free_result($rs_allergy); mysql_free_result($rs_logo);mysql_free_result($rs_patient); ?>