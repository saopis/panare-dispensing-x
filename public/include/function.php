<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?
	function mthai($month,$year){
	switch($month) {
	case "01":
	$thaidate= "ม.ค. ".substr($year+543,2,2);
	break;
	case "02":
	$thaidate="ก.พ.".substr($year+543,2,2);
	break;
	case "03":
	$thaidate="มี.ค. ".substr($year+543,2,2);
	break;
	case "04":
	$thaidate="เม.ย. ".substr($year+543,2,2);
	break;
	case "05":
	$thaidate="พ.ค. ".substr($year+543,2,2);
	break;
	case "06":
	$thaidate="มิ.ย. ".substr($year+543,2,2);
	break;
	case "07":
	$thaidate="ก.ค. ".substr($year+543,2,2);
	break;
	case "08":
	$thaidate= "ส.ค. ".substr($year+543,2,2);
	break;
	case "09":
	$thaidate= "ก.ย. ".substr($year+543,2,2);
	break;
	case "10":
	$thaidate= "ต.ค. ".substr($year+543,2,2);
	break;
	case "11":
	$thaidate= "พ.ย. ".substr($year+543,2,2);
	break;
	case "12":
	$thaidate= "ธ.ค. ".substr($year+543,2,2);
	break;
	}
	return $thaidate;
	}
$num_w=array("1","2","3","4","5","6","7");
$thai_w=array("จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์","อาทิตย์");
$thai_n=array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
$w=$thai_w[date("w")];
$d=date("d");
$n=$thai_n[date("n") -1];
$y=date("Y") +543;
$t=date("àÇÅÒ H ¹ÒÌÔ¡Ò i ¹Ò·Õ s ÇÔ¹Ò·Õ");

	function DateDiff($strDate1,$strDate2)
	 {
				return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	 }
	 
	 function dateThai($date){
	if($date!=""){
	$_month_name = array("01"=>"มกราคม","02"=>"กุมภาพันธ์","03"=>"มีนาคม","04"=>"เมษายน","05"=>"พฤษภาคม","06"=>"มิถุนายน","07"=>"กรกฎาคม","08"=>"สิงหาคม","09"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)." ".$_month_name[$mm]." ".$yy." ".$time;
	return $dateT;
		}
	 }

function datethai2($iorder)
	 {
	   $y1=substr($iorder,-8,4);$m1=substr($iorder,-4,2);$d1=substr($iorder,-2,2);
	   return $y1."-".$m1."-".$d1;	
	 }

function dateThai3($date){
	if($date!=""){
	$_month_name = array("01"=>"1","02"=>"2","03"=>"3","04"=>"4","05"=>"5","06"=>"6","07"=>"7","08"=>"8","09"=>"9","10"=>"10","11"=>"11","12"=>"12");
	$yy=substr($date,0,4);$mm=substr($date,5,2);$dd=substr($date,8,2);$time=substr($date,11,8);
	$yy+=543;
	$dateT=intval($dd)."/".$_month_name[$mm]."/".substr($yy,2,2)." ".$time;
	return $dateT;
		}
	 }

function date_th2db($date){

	$date1=explode('/',$date);
	$edate=($date1[2]-543)."-".$date1[1]."-".$date1[0];
	return $edate;

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

function day_name($day){
	switch($day) {
	case "1":
	echo "วันจันทร์";
	break;
	case "2":
	echo "วันอังคาร";
	break;
	case "3":
	echo "วันพุธ";
	break;
	case "4":
	echo "วันพฤหัสบดี";
	break;
	case "5":
	echo "วันศุกร์";
	break;
	case "6":
	echo "วันเสาร์";
	break;
	case "7":
	echo "วันอาทิตย์";
	break;

	}
	}

function number_format2($number){
	if($number!=0){
	$number2=str_replace('.00','',number_format($number,2));
	return $number2;
	}
	if($number==0||$number==""){
	return 0;
	}
}

function num2wordsThai($num){   
    $num=str_replace(",","",$num);
    $num_decimal=explode(".",$num);
    $num=$num_decimal[0];
    $returnNumWord;   
    $lenNumber=strlen($num);   
    $lenNumber2=$lenNumber-1;   
    $kaGroup=array("","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน");   
    $kaDigit=array("","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ต","แปด","เก้า");   
    $kaDigitDecimal=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ต","แปด","เก้า");   
    $ii=0;   
    for($i=$lenNumber2;$i>=0;$i--){   
        $kaNumWord[$i]=substr($num,$ii,1);   
        $ii++;   
    }   
    $ii=0;   
    for($i=$lenNumber2;$i>=0;$i--){   
        if(($kaNumWord[$i]==2 && $i==1) || ($kaNumWord[$i]==2 && $i==7)){   
            $kaDigit[$kaNumWord[$i]]="ยี่";   
        }else{   
            if($kaNumWord[$i]==2){   
                $kaDigit[$kaNumWord[$i]]="สอง";        
            }   
            if(($kaNumWord[$i]==1 && $i<=2 && $i==0) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==6)){   
                if($kaNumWord[$i+1]==0){   
                    $kaDigit[$kaNumWord[$i]]="หนึ่ง";      
                }else{   
                    $kaDigit[$kaNumWord[$i]]="เอ็ด";       
                }   
            }elseif(($kaNumWord[$i]==1 && $i<=2 && $i==1) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==7)){   
                $kaDigit[$kaNumWord[$i]]="";   
            }else{   
                if($kaNumWord[$i]==1){   
                    $kaDigit[$kaNumWord[$i]]="หนึ่ง";   
                }   
            }   
        }   
        if($kaNumWord[$i]==0){   
            if($i!=6){
                $kaGroup[$i]="";   
            }
        }   
        $kaNumWord[$i]=substr($num,$ii,1);   
        $ii++;   
        $returnNumWord.=$kaDigit[$kaNumWord[$i]].$kaGroup[$i];   
    }      
    if(isset($num_decimal[1])){
        $returnNumWord.="จุด";
        for($i=0;$i<strlen($num_decimal[1]);$i++){
                $returnNumWord.=$kaDigitDecimal[substr($num_decimal[1],$i,1)];  
        }
    }       
    return $returnNumWord;   
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

function nodata(){
	echo "<div style='padding: 20px;' class='font20'><i class='far fa-times-circle font20'></i>&ensp;ไม่พบรายการที่ค้นหา</div>";
}
function ifnotempty($date,$show){
	if($date!=""||$date!=NULL){
		echo $show;
	}
}
function time4digit($time){
	return substr($time,0,5);
}
function timediff($start,$end){
$starttime = strtotime($start);
$endtime = strtotime($end);
if(($endtime - $starttime) / 60==0){ return ""; }
else {
return number_format2(($endtime - $starttime) / 60);
}
}


	 function TimeDiff2($strTime1,$strTime2)
	 {
				return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	 }
	 function DateTimeDiff($strDateTime1,$strDateTime2)
	 {
				return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	 }

function datetime_db2th($date){
	if($date!=""){
    $date11=explode(' ',$date);
	$date1=explode('-',$date11[0]);
	$edate=$date1[2]."/".$date1[1]."/".($date1[0]+543)." ".substr($date,11,16);
	return $edate;
	}
}

function days_in_month($month, $year) {
    // calculate number of days in a month
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>