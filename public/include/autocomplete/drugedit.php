<?php require_once('../../Connections/hos.php'); ?>
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

include('../function.php');

mysql_select_db($database_hos, $hos);
include('../function_sql.php');

if(isset($_GET['vn'])&&($_GET['vn']!="")){ $vn=$_GET['vn'];}
if(isset($_POST['vn'])&&($_POST['vn']!="")){ $vn=$_POST['vn'];}

if(isset($_GET['icode'])&&($_GET['icode']!="")){ $icode=$_GET['icode'];}
if(isset($_POST['icode'])&&($_POST['icode']!="")){ $icode=$_POST['icode'];}

if(isset($_GET['qty'])&&($_GET['qty']!="")){ $qty=$_GET['qty'];}
if(isset($_POST['qty'])&&($_POST['qty']!="")){ $qty=$_POST['qty'];}

if(isset($_GET['hos_guid'])&&($_GET['hos_guid']!="")){ $hos_guid=$_GET['hos_guid'];}
if(isset($_POST['hos_guid'])&&($_POST['hos_guid']!="")){ $hos_guid=$_POST['hos_guid'];}


if(isset($_POST['save'])&&($_POST['save']=="แก้ไข")){
	mysql_select_db($database_hos, $hos);
	//update dep_time
	$update="update ovst set cur_dep_time = now() where vn = '".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "1. ".$update;

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ovst set cur_dep_time = now() where vn = \'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "2. ".$update;
	
	//update last_send_time
		mysql_select_db($database_hos, $hos);
	$update="UPDATE service_time SET last_send_time=now() WHERE vn='".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "3. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE service_time SET last_send_time=now() WHERE vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "4. ".$update;
	
	
	if($_POST['code3']!=$_POST['code']){
	//ถ้าไม่ใช่ตามแพทย์สั่ง
	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="UPDATE opitemrece SET qty='$qty',drugusage='".$_POST['code']."',discount=NULL,sum_price=unitprice*'".$qty."',sp_use=NULL WHERE hos_guid='".$hos_guid."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE opitemrece SET qty=\'".$qty."\',drugusage=\'".$_POST['code']."\',discount=NULL,sum_price=unitprice*\'".$qty."\' WHERE hos_guid=\'".$hos_guid."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "6. ".$update;
	}
	if($_POST['code3']==$_POST['code']){
	//echo "asdf";
	//ถ้าใช่ตามแพทย์สั่ง
///////////////////////////////////////
/*
	//get serial
	mysql_select_db($database_hos, $hos);
	$getserial="select get_serialnumber('sp_use') as cc";
	$sgetserial= mysql_query($getserial, $hos) or die(mysql_error());
	$row_getserial = mysql_fetch_assoc($sgetserial);	
	echo "1. ".$getserial."</br>";
	//replicate_log serial number
	mysql_select_db($database_hos, $hos);
	$replicate="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update serial set serial_no = \'".$row_getserial['cc']."\' where name =\'sp_use\'')";
	$sreplicate= mysql_query($replicate, $hos) or die(mysql_error());
	echo "2. ".$replicate."</br>";
	
	//insert sp_use
	mysql_select_db($database_hos, $hos);
	$insert="INSERT INTO sp_use (sp_use,sp_name,name1,name2,name3,`user`,sp_use_guid,oldcode,hos_guid,hos_guid_ext) VALUES ('".$row_getserial['cc']."','".$code2."','$sp_use1','$sp_use2','$sp_use3','kohrx',NULL,NULL,NULL,NULL)";
	$sinsert= mysql_query($insert, $hos) or die(mysql_error());
	echo "3. ".$insert."</br>";

	//replicate_log serial number
	mysql_select_db($database_hos, $hos);
	$replicate="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO sp_use (sp_use,sp_name,name1,name2,name3,`user`,sp_use_guid,oldcode,hos_guid,hos_guid_ext) VALUES (\'".$row_getserial['cc']."\',\'".$code2."\',\'$sp_use1\',\'$sp_use2\',\'$sp_use3\',\'kohrx\',NULL,NULL,NULL,NULL)')";
	$sreplicate= mysql_query($replicate, $hos) or die(mysql_error());
	echo "4. ".$replicate."</br>";

	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="UPDATE opitemrece SET qty='$qty',drugusage='$code',sp_use='".$row_getserial['cc']."',discount=NULL,sum_price=unitprice*'$qty' WHERE hos_guid='$hos_guid'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;
	echo "5. ".$update."</br>";


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE opitemrece SET qty=\'".$qty."\',drugusage=\'".$code."\',sp_use=\'".$row_getserial['cc']."\',discount=NULL,sum_price=unitprice*\'".$qty."\' WHERE hos_guid=\'".$hos_guid."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	echo "6. ".$update."</br>";
///////////////////////////////
*/
	//update sp_use
	mysql_select_db($database_hos, $hos);
	$update="UPDATE sp_use SET name1='".$_POST['sp_use1']."',name2='".$_POST['sp_use2']."',name3='".$_POST['sp_use3']."' WHERE sp_use='".$_POST['code4']."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE sp_use SET name2=\'".$_POST['sp_name2']."\',name3=\'".$_POST['sp_name3']."\' WHERE sp_use=\'".$_POST['code4']."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="UPDATE opitemrece SET qty='".$qty."',discount=NULL,sum_price=unitprice*'".$qty."' WHERE hos_guid='".$hos_guid."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE opitemrece SET qty=\'".$qty."\',discount=NULL,sum_price=unitprice*\'".$qty."\' WHERE hos_guid=\'".$hos_guid."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());

	}
	
	//update service_time
		mysql_select_db($database_hos, $hos);
	$update="update service_time set rx_time_type = '' where vn='".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update service_time set rx_time_type = \'\' where vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
    echo "<script>parent.drug_list_load_vn('".$_POST['vn']."','".date_db2th(vnVstdate($_POST['vn']))."');parent.$.fn.colorbox.close();</script>";
    exit();
	}

if(isset($_POST['delete'])&&($_POST['delete']=="ลบ")){
	mysql_select_db($database_hos, $hos);
	//update dep_time
	$update="update ovst set cur_dep_time = now() where vn = '".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "1. ".$update;

	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ovst set cur_dep_time = now() where vn = \'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "2. ".$update;
	
	//update last_send_time
		mysql_select_db($database_hos, $hos);
	$update="UPDATE service_time SET last_send_time=now() WHERE vn='".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "3. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','UPDATE service_time SET last_send_time=now() WHERE vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "4. ".$update;

	//update opitemrece
	mysql_select_db($database_hos, $hos);
	$update="DELETE FROM opitemrece WHERE hos_guid='".$hos_guid."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "5. ".$update;


	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','DELETE FROM opitemrece WHERE hos_guid=\'".$hos_guid."\'')
";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "6. ".$update;
	
	
	//update service_time
		mysql_select_db($database_hos, $hos);
	$update="update service_time set rx_time_type = '' where vn='".$vn."'";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
	//update replicate_log
	mysql_select_db($database_hos, $hos);
	$update="insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update service_time set rx_time_type = \'\' where vn=\'".$vn."\'')";
	$qupdate= mysql_query($update, $hos) or die(mysql_error());
	//echo "7. ".$update;
	
    echo "<script>parent.drug_list_load_vn('".$_POST['vn']."');parent.$.fn.colorbox.close();</script>";
    exit();
	}

mysql_select_db($database_hos, $hos);
$query_drugitems = "SELECT concat(name,' ',strength) as drugname,qty,d.shortlist,o.drugusage,o.icode,sp.sp_use,sp.sp_name,sp.name1 as sp_name1,sp.name2 as sp_name2,sp.name3 as sp_name3,d.name1,d.name2,d.name3,o.hn,o.vstdate FROM opitemrece o left outer join  s_drugitems s on s.icode=o.icode left outer join drugusage d on d.drugusage=o.drugusage left outer join sp_use sp on sp.sp_use=o.sp_use WHERE o.icode ='".$icode."' and vn='".$vn."'";
//echo $query_drugitems;
$drugitems = mysql_query($query_drugitems, $hos) or die(mysql_error());
$row_drugitems = mysql_fetch_assoc($drugitems);
$totalRows_drugitems = mysql_num_rows($drugitems);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="jquery.js"></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />

<script type="text/javascript">
$().ready(function() {
	//$("#showusage").hide();
	//$("#sp_use").hide();

	$("#drugusage").autocomplete("get_course_list.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#drugusage").result(function(event, data, formatted) {
		$("#code").val(data[1]); $("#code2").val(data[5]); 
		
			if(data[1]!="0201"){$("#name1").val(data[2]); $("#name2").val(data[3]); $("#name3").val(data[4]); $("#showusage").show();}	
			if(data[1]=="0201"){ $("#sp_use1").val(data[2]); $("#sp_use2").val(data[3]); $("#sp_use3").val(data[4]); $("#sp_use").show();}
	});

});
</script>
<!-- bootstrap -->
<link rel="stylesheet" href="../../include/bootstrap/css/bootstrap.min.css">
<script src="../../include/bootstrap/js/popper.min.js"></script>
<script src="../../include/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../include/bootstrap/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../include/bootstrap/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../include/bootstrap/css/jquery.dataTables.min.css"/>
</head>
<!-- fontawesome -->
<link rel="stylesheet" href="../../include/fontawesome/css/all.css">
<!-- kohrx -->
<link rel="stylesheet" href="../../include/kohrx/css/kohrx.css">
<!-- datepicker -->
<link rel="stylesheet" href="../../include/datepicker/css/jquery.datetimepicker.css">
<script src="../../include/datepicker/js/jquery.datetimepicker.full.js"></script>
<!-- colorbox -->
<script src="../../include/colorbox/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="../../include/colorbox/css/colorbox.css">
<!-- Material icon -->    
<link rel="stylesheet" href="../../include/materialicon/css/materialicon.css">
</head>

<body>
<div style="margin:0px;">
<nav class="navbar navbar-dark bg-info text-white">
  <!-- Navbar content -->
  แก้ไขรายการยา
</nav>
<div class="container-fluid">
<div class="card" style="border:0px;">
  <div class="card-body">
	<?php echo $row_drugitems['drugname']; ?>
    </div>
</div>
<form autocomplete="off" method="post"  action="drugedit.php">
<div class="row">
<div></div>
</div>
        <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
        <input name="icode" type="hidden" id="icode" value="<?php echo $_GET['icode']; ?>" />
        <input name="hos_guid" type="hidden" id="hos_guid" value="<?php echo $hos_guid; ?>" />

<div class="form-row">
	<div class="col-sm-2"><input readonly="readonly" type="text" value="วิธีใช้" class="form-control-plaintext" /></div>
    <div class="col-sm-5"><input type="text" class="form-control" id="drugusage" name="drugusage" value="<?php if($row_drugitems['drugusage']!="0201"||$row_drugitems['drugusage']!=""){ echo $row_drugitems['shortlist']; } if(($row_drugitems['drugusage']=="0201")||($row_drugitems['drugusage'] =="")){ echo $row_drugitems['sp_name1'].$row_drugitems['sp_name2'].$row_drugitems['sp_name3']; } ?>" <?php if($row_drugitems['sp_use']!=""){ echo "disabled=\"disabled\"";} ?>>
            <span class="small_red"><?php echo $msg; ?>&nbsp;</span><div id="longlist"></div>
    <input name="code" type="hidden"  id="code" size="5" value="<?php echo $row_drugitems['drugusage']; ?>" />
        <input name="code3" type="hidden"  id="code3" size="5" value="<?php echo $row_drugitems['drugusage']; ?>" />
<input name="code2" type="hidden"  id="code2" size="5" value="<?php echo $row_drugitems['code']; ?>" />
<input name="code4" type="hidden"  id="code4" size="5" value="<?php echo $row_drugitems['sp_use']; ?>" />
<input name="hn" type="hidden"  id="hn" size="5" value="<?php echo $row_drugitems['hn']; ?>" />
<input name="vstdate" type="hidden"  id="vstdate" size="5" value="<?php echo $row_drugitems['vstdate']; ?>" /></div>
<div class="col-sm-1"><input readonly="readonly" type="text" value="#" class="form-control-plaintext" /></div>
<div class="col-sm-1"><input name="qty" type="text" id="qty" class="form-control"  value="<?php echo $row_drugitems['qty']; ?>" /></div>
<div class="col-sm-3"><input type="submit" name="save" id="save" value="แก้ไข" class="btn btn-success" />&nbsp;<input type="submit" name="delete" id="delete" class="btn btn-danger" value="ลบ" />
</div>
</div>
          <table width="600" border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td>

       <?php if($row_drugitems['drugusage']!=""&&$row_drugitems['sp_use']==""){ ?>
        <table width="500" border="0" cellpadding="2" cellspacing="0" class="table_head_small" id="showusage">
          <tr>
            <td align="center">บรรทัด 1</td>
            <td align="center"><input name="name1" type="text" disabled="disabled" id="name1" value="<?php echo $row_drugitems['name1']; ?>" size="50" /></td>
            </tr>
          <tr>
            <td align="center">บรรทัด 2</td>
            <td align="center"><input name="name2" type="text" disabled="disabled" id="name2" value="<?php echo $row_drugitems['name2']; ?>" size="50" /></td>
            </tr>
          <tr>
            <td align="center">บรรทัด 3</td>
            <td align="center"><input name="name3" type="text" disabled="disabled" id="name3" value="<?php echo $row_drugitems['name3']; ?>" size="50" /></td>
            </tr>
        </table> <?php } ?>       
<?php if(!empty($row_drugitems['drugusage'])&&($row_drugitems['sp_use']!="")){ ?>
        <table width="500" border="0" cellpadding="2" cellspacing="0" class="table_head_small" id="sp_use" >         
          <tr >
            <td colspan="2" align="center">กำหนดวิธีใช้เอง</td>
            </tr>
          <tr>
            <td align="center">บรรทัด 1</td>
            <td align="center"><input name="sp_use1" type="text" id="sp_use1" value="<?php echo $row_drugitems['sp_name1']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td align="center">บรรทัด 2</td>
            <td align="center"><input name="sp_use2" type="text" id="sp_use2" value="<?php echo $row_drugitems['sp_name2']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td align="center">บรรทัด 3</td>
            <td align="center"><input name="sp_use3" type="text" id="name6" value="<?php echo $row_drugitems['sp_name3']; ?>" size="50" /></td>
          </tr>
        </table> <? } ?>       <br />
</td>
      </tr>
  </table>
  <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />

</form>
</div>
</div>
<div class="fixed-bottom p-2" style="background-color: #F9A4A6 "><strong class="text-danger">คำเตือน !!</strong>&ensp;การแก้ไขข้อมูลในหน้านี้มีความเสี่ยงที่จะทำให้ข้อมูลค่าใช้จ่ายของผู้ป่วยไม่ตรง&nbsp;เพื่อความปลอดภัยและถูกต้องของข้อมูลกรุณาแก้ไขใน HOSxp</div>
</body>
</html>
<?php
mysql_free_result($drugitems);

?>
