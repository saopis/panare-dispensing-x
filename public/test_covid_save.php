<?php require_once('Connections/hos.php'); ?>
<?php
echo "sadf";
include('include/function.php');

$hn=str_pad($_POST['hn'], 9, "0", STR_PAD_LEFT);
mysql_select_db($database_hos, $hos);
$query_rs_vn = "select * from vn_stat where hn='".$hn."' and vstdate = '".date_th2db($_POST['date1'])."' ";
$rs_vn = mysql_query($query_rs_vn, $hos) or die(mysql_error());
$row_rs_vn = mysql_fetch_assoc($rs_vn);
$totalRows_rs_vn = mysql_num_rows($rs_vn);

if($totalRows_rs_vn==1){
mysql_select_db($database_hos, $hos);
$query_rs_er = "select * from er_regist where vn='".$row_rs_vn['vn']."'";
$rs_er = mysql_query($query_rs_er, $hos) or die(mysql_error());
$row_rs_er = mysql_fetch_assoc($rs_er);
$totalRows_rs_er = mysql_num_rows($rs_er);    
    if($totalRows_rs_er==1){
        mysql_select_db($database_hos, $hos);
        $query_update = "update er_regist set er_pt_type='4',er_emergency_type='4',er_dch_type='1',er_emergency_level_id='5',er_depcode='043' where vn='".$row_rs_vn['vn']."'";
        $update = mysql_query($query_update, $hos) or die(mysql_error()); 
    }
    else{
        mysql_select_db($database_hos, $hos);
        $query_insert = "insert into er_regist (er_period,er_pt_type,er_emergency_type,er_dch_type,er_emergency_level_id,er_depcode,vn) value ('1','4','4','1','5','043','".$row_rs_vn['vn']."')";
        $insert = mysql_query($query_insert, $hos) or die(mysql_error()); 
        
    }
mysql_free_result($rs_er);

//ovstdiag
mysql_select_db($database_hos, $hos);
$query_rs_ovst = "select * from ovstdiag where vn='".$row_rs_vn['vn']."'";
$rs_ovst = mysql_query($query_rs_ovst, $hos) or die(mysql_error());
$row_rs_ovst = mysql_fetch_assoc($rs_ovst);
$totalRows_rs_ovst = mysql_num_rows($rs_ovst);    
    if($totalRows_rs_ovst==1){
        mysql_select_db($database_hos, $hos);
        $query_update = "update ovstdiag set icd10='U119',diagtype='1' where vn='".$row_rs_vn['vn']."'";
        $update = mysql_query($query_update, $hos) or die(mysql_error()); 
    }
    else{
        mysql_select_db($database_hos, $hos);
        $query_rs_get_serial = "select get_serialnumber('ovst_diag_id') as cc ";
        $rs_get_serial = mysql_query($query_rs_get_serial, $hos) or die(mysql_error());
        $row_rs_get_serial = mysql_fetch_assoc($rs_get_serial);
        $totalRows_rs_get_serial = mysql_num_rows($rs_get_serial);
        $ovst_diag_id=$row_rs_get_serial['cc'];
        mysql_free_result($rs_get_serial);
        
        mysql_select_db($database_hos, $hos);
        $query_insert = "insert into ovstdiag (ovst_diag_id,vn,icd10,hn,vstdate,diagtype) value ('".$ovst_diag_id."','".$row_rs_vn['vn']."','U119','".$hn."','".date_th2db($_POST['date1'])."','1')";
        $insert = mysql_query($query_insert, $hos) or die(mysql_error()); 
        
    }
mysql_free_result($rs_ovst);
    
    echo "<script>alert('บันทึกข้อมูลเรียบร้อย');</script>";
}
else{
    echo "<script>alert('ผู้ป่วยไม่ได้ visit ในวันที่เลือกหรือมามากกว่า 1 ครั้ง');</script>";
    exit();
}
echo $row_rs_vn['vn'];
mysql_free_result($rs_vn);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>