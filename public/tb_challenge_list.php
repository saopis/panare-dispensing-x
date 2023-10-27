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

$hn=sprintf("%".$row_setting[24]."d", $_POST['hn']);

mysql_select_db($database_hos, $hos);
$query_rs_patient = "select p.hn,concat(p.pname,p.fname,' ',p.lname) as patient_name,v.age_y,o.bw from vn_stat v left outer join patient p on p.hn=v.hn left outer join opdscreen o on o.vn=v.vn where v.hn ='".$_GET['hn']."' order by v.vn DESC limit 1";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

mysql_select_db($database_hos, $hos);
$query_rs_challenge2 = "select * from ".$database_kohrx.".kohrx_tb_challenge";
$rs_challenge2 = mysql_query($query_rs_challenge2, $hos) or die(mysql_error());
$row_rs_challenge2 = mysql_fetch_assoc($rs_challenge2);
$totalRows_rs_challenge2 = mysql_num_rows($rs_challenge2);


?>
<?php
if($_GET['i_challenge']==100){
$i_array=array('50','50','100','100');	
	}
if($_GET['i_challenge']==200)
{
$i_array=array('100','100','200','200');	
}
if($_GET['i_challenge']==300)
{
$i_array=array('100','200','300','300');	
}
//// rifam ///
if($_GET['r_challenge']==300)
{
$r_array=array('300','300','300','300');	
}
if($_GET['r_challenge']==450)
{
$r_array=array('300','300','450','450');	
}
if($_GET['r_challenge']==600)
{
$r_array=array('300','450','600','600');	
}
/// etham ///
if($_GET['e_challenge']==400)
{
$e_array=array('400','400','400','400');	
}
if($_GET['e_challenge']==500)
{
$e_array=array('400','400','500','500');	
}
if($_GET['e_challenge']==800)
{
$e_array=array('400','500','800','800');	
}
if($_GET['e_challenge']==900)
{
$e_array=array('400','500','800','900');	
}
if($_GET['e_challenge']==1000)
{
$e_array=array('400','500','800','1000');	
}
if($_GET['e_challenge']==1200)
{
$e_array=array('400','800','1000','1200');	
}
if($_GET['e_challenge']==1400)
{
$e_array=array('500','800','1000','1400');	
}
if($_GET['e_challenge']==1500)
{
$e_array=array('500','800','1200','1500');	
}
/// pyrazinamide ///
if($_GET['p_challenge']==250)
{
$p_array=array('250','250','250','250');	
}
if($_GET['p_challenge']==500)
{
$p_array=array('250','250','500','500');	
}
if($_GET['p_challenge']==750)
{
$p_array=array('250','500','750','750');	
}
if($_GET['p_challenge']==1000)
{
$p_array=array('250','500','750','1000');	
}
if($_GET['p_challenge']==1250)
{
$p_array=array('500','750','100','1250');	
}
if($_GET['p_challenge']==1500)
{
$p_array=array('500','1000','1250','1500');	
}

mysql_select_db($database_hos, $hos);
$query_check = "SELECT COUNT(*) as ctable
FROM information_schema.tables 
WHERE table_schema = '".$database_kohrx."' 
AND table_name = 'kohrx_tb_challenge'";
$check = mysql_query($query_check, $hos) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);

if($row_check['ctable']==0){
mysql_select_db($database_hos, $hos);
$table_build = "CREATE TABLE ".$database_kohrx.".`kohrx_tb_challenge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_date` date DEFAULT NULL,
  `tb1` char(100) DEFAULT NULL,
  `strength1` int(4) DEFAULT NULL,
  `tb2` char(100) DEFAULT NULL,
  `strength2` int(4) DEFAULT NULL,
  `tb3` char(100) DEFAULT NULL,
  `strength3` int(4) DEFAULT NULL,
  `tb4` char(100) DEFAULT NULL,
  `strength4` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=tis620;

";
$build = mysql_query($table_build, $hos) or die(mysql_error());	
}

///////// truncate //////////
mysql_select_db($database_hos, $hos);
$table_truncate = "truncate table ".$database_kohrx.".kohrx_tb_challenge";
$truncate = mysql_query($table_truncate, $hos) or die(mysql_error());	
//// add inh ////
for($i=0;$i<4;$i++){
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_tb_challenge (challenge_date,tb1,strength1) value (DATE_ADD(CURDATE(),INTERVAL ".$i." DAY),'ISONIAZID','".$i_array[$i]."')";
$insert = mysql_query($query_insert, $hos) or die(mysql_error());
}
//// add rif ////
for($r=0;$r<4;$r++){
$rr=$r+4;
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_tb_challenge (challenge_date,tb1,strength1,tb2,strength2) value (DATE_ADD(CURDATE(),INTERVAL ".$rr." DAY),'ISONIAZID','".$i_array[($i-1)]."','RIFAMPICIN','".$r_array[$r]."')";
$insert = mysql_query($query_insert, $hos) or die(mysql_error());	
}
//// add etham ////
for($e=0;$e<4;$e++){
$ee=$e+8;
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_tb_challenge (challenge_date,tb1,strength1,tb2,strength2,tb3,strength3) value (DATE_ADD(CURDATE(),INTERVAL ".$ee." DAY),'ISONIAZID','".$i_array[($i-1)]."','RIFAMPICIN','".$r_array[$r-1]."','ETHAMBUTOL','".$e_array[($e)]."')";
$insert = mysql_query($query_insert, $hos) or die(mysql_error());	
}
//// add pza ////
for($p=0;$p<4;$p++){
$pp=$p+12;
mysql_select_db($database_hos, $hos);
$query_insert = "insert into ".$database_kohrx.".kohrx_tb_challenge (challenge_date,tb1,strength1,tb2,strength2,tb3,strength3,tb4,strength4) value (DATE_ADD(CURDATE(),INTERVAL ".$pp." DAY),'ISONIAZID','".$i_array[($i-1)]."','RIFAMPICIN','".$r_array[$r-1]."','ETHAMBUTOL','".$e_array[($e-1)]."','PYRAZINAMIDE','".$p_array[$p]."')";
$insert = mysql_query($query_insert, $hos) or die(mysql_error());	
}

require_once ('PHPWord.php');
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$document = $PHPWord->loadTemplate('tb_challenge_temp.docx');
$document->setValue('pname',$row_rs_patient['patient_name']."  อายุ ".$row_rs_patient['age_y']." ปี   น้ำหนัก ".number_format($row_rs_patient['bw'])." กิโลกรัม  HN ".$row_rs_patient['hn']);

$k=0;
do{
$k++;
$document->setValue('date'.$k,dateThai($row_rs_challenge2['challenge_date']));	

if($row_rs_challenge2['tb1']!=""){
	$tb_data1=$row_rs_challenge2['tb1']." ".$row_rs_challenge2['strength1'];
	}
if($row_rs_challenge2['tb2']!=""){
	$tb_data2=" +".$row_rs_challenge2['tb2']." ".$row_rs_challenge2['strength2'];
	}
if($row_rs_challenge2['tb3']!=""){
	$tb_data3=" +".$row_rs_challenge2['tb3']." ".$row_rs_challenge2['strength3'];
}
if($row_rs_challenge2['tb4']!=""){
	$tb_data4=" +".$row_rs_challenge2['tb4']." ".$row_rs_challenge2['strength4'];
}

$document->setValue('data'.$k,$tb_data1.$tb_data2.$tb_data3.$tb_data4);	

}while($row_rs_challenge2 = mysql_fetch_assoc($rs_challenge2));
$document->save('tb_challenge.docx');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../css/kohrx.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="500" border="0" cellspacing="0" cellpadding="5" style="border:1px #CCCCCC solid" class="pad5">
  <tr>
    <td align="center"><a href="tb_challenge.docx" class="big_red16"><img src="images/attach_document.png" width="47" height="47" border="0" align="absmiddle" /> download เอกสาร</a>  </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_patient);

mysql_free_result($rs_setting);

mysql_free_result($rs_challenge2);
?>
