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

if(isset($_GET['action'])&&($_GET['action']=="save"))
{
if($_GET['detail']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_drug_cr = "select * from ".$database_kohrx.".kohrx_drug_creatinine_record where vn='".$_GET['vn']."' and icode='".$_GET['icode']."'";
$rs_drug_cr = mysql_query($query_rs_drug_cr, $hos) or die(mysql_error());
$row_rs_drug_cr = mysql_fetch_assoc($rs_drug_cr);
$totalRows_rs_drug_cr = mysql_num_rows($rs_drug_cr);

	if($totalRows_rs_drug_cr==0){
		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "insert into ".$database_kohrx.".kohrx_drug_creatinine_record (vn,icode,crcl,drugusage,detail,hn,cr,lab_date) value ('".$_GET['vn']."','".$_GET['icode']."','".$_GET['crcl']."','".$_GET['drugusage']."','".$_GET['detail']."','".$_GET['hn']."','".$_GET['cr']."','".$_GET['lab_date']."')";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
	}
	else if($totalRows_rs_drug_cr<>0){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_drug_creatinine_record set drugusage='".$_GET['drugusage']."',detail='".$_GET['detail']."',hn='".$_GET['hn']."',cr='".$_GET['cr']."',lab_date='".$_GET['lab_date']."' where vn='".$_GET['vn']."'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		}
	echo "บันทึกข้อมูลแล้ว";
	exit();
}
else{
  		mysql_select_db($database_hos, $hos);
		$query_rs_insert = "delete from ".$database_kohrx.".kohrx_drug_creatinine_record where vn='".$_GET['vn']."'";
		$rs_insert = mysql_query($query_rs_insert, $hos) or die(mysql_error());
  
    }
}
mysql_select_db($database_hos, $hos);
$query_rs_drug_cr = "select concat(d.name,' ',d.strength) as drugname,c.*,u.shortlist,o.drugusage,o.icode from opitemrece o left outer join drugusage u on u.drugusage=o.drugusage left outer join drugitems d on d.icode=o.icode left outer join ".$database_kohrx.".kohrx_drug_creatinine c on c.icode=d.icode where o.vn='".$_GET['vn']."' and c.id='".$_GET['id']."'";
$rs_drug_cr = mysql_query($query_rs_drug_cr, $hos) or die(mysql_error());
$row_rs_drug_cr = mysql_fetch_assoc($rs_drug_cr);
$totalRows_rs_drug_cr = mysql_num_rows($rs_drug_cr);

mysql_select_db($database_hos, $hos);
$query_rs_drug_cr_r = "select * from ".$database_kohrx.".kohrx_drug_creatinine_record where vn='".$_GET['vn']."' and icode='".$row_rs_drug_cr['icode']."'";
$rs_drug_cr_r = mysql_query($query_rs_drug_cr_r, $hos) or die(mysql_error());
$row_rs_drug_cr_r = mysql_fetch_assoc($rs_drug_cr_r);
$totalRows_rs_drug_cr_r = mysql_num_rows($rs_drug_cr_r);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<script>
    $(document).ready(function(){
        $('#save').click(function(){
            	 $('#save-div').load('drug_creatinine.php?vn=<?php echo $_GET['vn']; ?>&id=<?php echo $_GET['id']; ?>&crcl=<?php echo $_GET['crcl']; ?>&cr=<?php echo $_GET['cr']; ?>&hn=<?php echo $_GET['hn']; ?>&lab_date=<?php echo $_GET['lab_date']; ?>&icode=<?php echo $row_rs_drug_cr['icode']; ?>&drugusage=<?php echo $row_rs_drug_cr['drugusage']; ?>&detail='+encodeURIComponent($('#detail').val())+'&action=save',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                                modal_close_id('myModal-primary');    
                            if(statusTxt == "error")
                            	alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });

        });
    });
</script>    
</head>

<body>
<div class="p-3">
    <div id="save-div"></div>
  <p><span class="head_small_gray">ยาที่ผู้ป่วยได้รับ</span>&nbsp; <span class="big_red16"><?php echo $row_rs_drug_cr['drugname']; ?></span>&nbsp;= <span class="small_blue"><?php echo $row_rs_drug_cr['shortlist']; ?></span></p>
  <p>CKD GFR = <?php echo $row_rs_drug_cr['min_value']; ?> - <?php echo $row_rs_drug_cr['max_value']; ?>&nbsp;&nbsp; ml/min/1.73 m2<br />
    Cr = 
  <?php echo $row_rs_drug_cr['cr_min_value']; ?> - <?php echo $row_rs_drug_cr['cr_max_value']; ?></p>

<div class="card">
    <div class="card-header"><h6>คำแนะนำในการปรับ</h6></div>
    <div class="card-body">
        <?php echo $row_rs_drug_cr['remark']; ?>
    </div>
</div><br />

<div class="card">
    <div class="card-header"><h6>เหตุผล</h6></div>
    <div class="card-body">
        <?php echo $row_rs_drug_cr['detail']; ?>
    </div>
</div>    
  <br />
  คำอธิบายสำหรับบันทึกเหตุการณ์ / การแก้ไข
    <div class="row">
        <div class="col">
        <textarea class="form-control" name="detail" id="detail" rows="3"><?php echo $row_rs_drug_cr_r['detail']; ?></textarea>
        </div>    
    </div>
    <div class="row mt-2">
        <div class="col-2">
            <input name="save" type="submit" class="btn btn-danger" id="save" value="บันทึกเหตุการณ์" />
        </div>    
    </div>
    
</div>
</body>
</html>
<?php
mysql_free_result($rs_drug_cr);
mysql_free_result($rs_drug_cr_r);

?>
