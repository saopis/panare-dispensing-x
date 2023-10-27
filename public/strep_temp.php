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

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

$hn=sprintf("%".$row_setting[24]."d", $_GET['hn']);

mysql_select_db($database_hos, $hos);
$query_rs_patient = "select p.hn,concat(p.pname,p.fname,' ',p.lname) as patient_name,v.age_y,o.bw from vn_stat v left outer join patient p on p.hn=v.hn left outer join opdscreen o on o.vn=v.vn where v.hn ='".$hn."' order by v.vn DESC limit 1";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

require_once ('PHPWord.php');
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$document = $PHPWord->loadTemplate('strep_temp.docx');
$document->setValue('pname',$row_rs_patient['patient_name']."  อายุ ".$row_rs_patient['age_y']." ปี   น้ำหนัก ".number_format($row_rs_patient['bw'])." กิโลกรัม  HN ".
$row_rs_patient['hn']);
	if($row_rs_patient['age_y']>=60){
		$dosage=10;
		$usage=$dosage*$row_rs_patient['bw'];
		if($usage>=750){
			$usage2=750;
			}
		else {$usage2=$usage;}
		}
	if($row_rs_patient['age_y']<60){
		$dosage=15;
		$usage=$dosage*$row_rs_patient['bw'];
		if($usage>=1000){
			$usage2=1000;
			}
		else {$usage2=$usage;}

	}

        $document->setValue('detail','('.$dosage.'(ขนาดยา) x '.number_format($row_rs_patient['bw']).'(น้ำหนัก กก.))/500');
		$document->setValue('line1',(number_format(($usage2/500),1)*500).' mg.');	
		$document->setValue('line2',number_format(($usage2/500),1).' มิลลิลิตร'	);	
		if($_GET['im_type']==1){
			$line3="ให้ทาง IM เท่านั้น"; 
			$line4="ในวันจันทร์ อังคาร พุธ พฤหัสบดี ศุกร์";
			$line5="เว้นเสาร์และอาทิตย์";
		}
		if($_GET['im_type']==2){
			$line3="ให้ทาง IM เท่านั้น"; 
			$line4="วันเว้นวัน";
			}
		if($_GET['im_type']==3){
			$line3="ให้ทาง IM เท่านั้น"; 
			$line4="สัปดาห์ละ 3 ครั้ง";
			$line5="ในวันจันทร์ พุธ ศุกร์";
		}

		$document->setValue('line3',$line3);	
		$document->setValue('line4',$line4);	
		$document->setValue('line5',$line5);

$document->save('streptomycin.docx');
echo "asfd";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="500" border="0" cellspacing="0" cellpadding="5" style="border:1px #CCCCCC solid;" class="pad5">
  <tr>
    <td align="center"><a href="streptomycin.docx" class="big_red16"><img src="images/attach_document.png" width="47" height="47" border="0" align="absmiddle" /> download เอกสาร</a></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_patient);

mysql_free_result($rs_setting);

?>