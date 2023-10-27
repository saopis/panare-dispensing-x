<?php 
ob_start();
session_start();
?>
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

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
    mysql_select_db($database_hos, $hos);
    $query_rs_med = "select * from ".$database_kohrx.".kohrx_med_reconcile_header  where hn='".$_POST['hn']."' and vstdate='".$_POST['vstdate2']."'";
    $rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
    $row_rs_med = mysql_fetch_assoc($rs_med);
    $totalRows_rs_med = mysql_num_rows($rs_med);

    if($totalRows_rs_med<>0){
        mysql_select_db($database_hos, $hos);
        $query_update = "update ".$database_kohrx.".kohrx_med_reconcile_header set create_time=NOW() where hn='".$_POST['hn']."' and vstdate='".$_POST['vstdate2']."'";
        $rs_update = mysql_query($query_update, $hos) or die(mysql_error());            
    }
    else{
        mysql_select_db($database_hos, $hos);
        $query_insert = "insert into ".$database_kohrx.".kohrx_med_reconcile_header (hn,vstdate,create_time,creator) value ('".$_POST['hn']."','".$_POST['vstdate2']."',NOW(),'".$_SESSION['doctorcode']."')";
        $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());        
    }
    mysql_free_result($rs_med);
    
	for($k=0;$k<=count($_POST['icode']);$k++){
	if($_POST['icode'][$k]!=""){
		mysql_select_db($database_hos, $hos);
$query_rs_drugs = "select   concat(d.name,' ',d.strength) as drugname,u.shortlist,s.sp_use,o.qty,f.hospitalcode,concat(h.hosptype,h.name) as hospname,o.icode,s.name1,s.name2,s.name3,a.nextdate from opitemrece o left outer join oapp a on a.vn=o.vn  left outer join drugitems d on d.icode=o.icode  left outer join drugusage u on u.drugusage=o.drugusage left outer join sp_use s on s.sp_use=o.sp_use join opdconfig f left outer join hospcode h on h.hospcode=f.hospitalcode where o.hos_guid='".$_POST['icode'][$k]."'";
//echo $query_rs_drugs;
$rs_drugs = mysql_query($query_rs_drugs, $hos) or die(mysql_error());
$row_rs_drugs = mysql_fetch_assoc($rs_drugs);
$totalRows_rs_drugs = mysql_num_rows($rs_drugs);
		
		if($row_rs_drugs['sp_use']!=""){
            if($row_rs_drugs['name1']!=""){
                $name1=$row_rs_drugs['name1'];
            }
			if($row_rs_drugs['name2']!=""){
                $name2=$row_rs_drugs['name2'];
            }
            if($row_rs_drugs['name3']!=""){
                $name3=$row_rs_drugs['name3'];
            }
            $drugname=$row_rs_drugs['drugname'];
			$drugusage=$name1." ".$name2.$name3;
			}
		else{
            $drugname=$row_rs_drugs['drugname'];
			$drugusage=$row_rs_drugs['shortlist'];
			}
		
		//ถ้าพบ record
		if($totalRows_rs_drugs<>0){
		mysql_select_db($database_hos, $hos);
		$query_rs_search = "select * from ".$database_kohrx.".kohrx_med_reconcile where hn='".$_POST['hn']."'  and drug_name='".$row_rs_drugs['drugname']."' and drugusage='".$drugusage."' and vstdate2='".$_POST['vstdate2']."'";
		$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
		$row_rs_search = mysql_fetch_assoc($rs_search);
		$totalRows_rs_search = mysql_num_rows($rs_search);
		
		if($totalRows_rs_search==0){
		if($row_rs_drugs['nextdate']!=""){
			$nextdate="'".$row_rs_drugs['nextdate']."'";
		}
		else{
			$nextdate="NULL";
		}
		mysql_select_db($database_hos, $hos);
		$insert = "insert into ".$database_kohrx.".kohrx_med_reconcile (hn,vstdate,vstdate2,hos_guid,drug_name,drugusage,qty,src_hospcode,icode,appdate) value ('".$_POST['hn']."','".$_POST['vstdate']."','".$_POST['vstdate2']."','".$_POST['icode'][$k]."','".$drugname."','".$drugusage."','".$row_rs_drugs['qty']."','".$row_rs_drugs['hospname']."','".$row_rs_drugs['icode']."',".$nextdate.")";
		$q_insert = mysql_query($insert, $hos) or die(mysql_error());
		}
		else{
		mysql_select_db($database_hos, $hos);
		$update = "update ".$database_kohrx.".kohrx_med_reconcile set vstdate='".$_POST['vstdate']."' where hn='".$_POST['hn']."'  and drug_name='".$drugname."' and drugusage='".$drugusage."' and vstdate2='".$_POST['vstdate2']."' ";
		$q_update = mysql_query($update, $hos) or die(mysql_error());
		
		}
		mysql_free_result($rs_search);

	}
		
		mysql_free_result($rs_drugs);
		}
	}
	
	echo "<script>parent.$.fn.colorbox.close();parent.med_reconcile_load();</script>";
}

mysql_select_db($database_hos, $hos);
$query_rs_visit = "
 select v.vstdate,o.vsttime,a.an,v.vn,v.hn
 from vn_stat v 
 left outer join an_stat a on a.vn=v.vn 
 left outer join opitemrece o on o.vn=v.vn
 where v.hn='".$_GET['hn']."' group by v.vn order by vstdate DESC
 ";
$rs_visit = mysql_query($query_rs_visit, $hos) or die(mysql_error());
$row_rs_visit = mysql_fetch_assoc($rs_visit);
$totalRows_rs_visit = mysql_num_rows($rs_visit);




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
html,body{overflow:hidden; }
	::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}

</style>
<script>
$(document).ready(function() {
    $('#drug_indicator').hide();
 	 
});
function drug_load(vn,an,vstdate,vstdate2){
                        $('#drug_indicator').show();
                        $('#drug_list').load('med_reconcile_drug_load.php?vn='+vn+'&an='+an+'&vstdate='+encodeURIComponent(vstdate)+'&vstdate2='+vstdate2,function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#drug_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });	
	}

</script>
</head>

<body>
<div class="row">
<div class="col" style="-ms-flex: 0 0 250px;flex: 0 0 250px;background-color:#F5F5F5; padding:0px; height:100vh;">
<div style=" height:57px; width:100%; padding-top:15px;" class="font16 text-white text-center bg-secondary thfont">เลือกวันที่รับบริการ</div>
<div style="margin-top: 0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh;">
<table class="table font14 table-striped table-hover">
<tbody>
<?php do{ ?>
<tr class="cursor" onclick="drug_load('<?php echo $row_rs_visit['vn']; ?>','<?php echo $row_rs_visit['an']; ?>','<?php echo $row_rs_visit['vstdate']; ?>','<?php echo $_GET['vstdate2']; ?>');">
<td width="70%" style=" padding:2px; padding-left:30px;">
<?php echo dateThai($row_rs_visit['vstdate']); ?><?php if($row_rs_visit['an']!=""){ ?><span class="badge badge-primary">IPD</span><?php }?> 
</td>
<td width="30%" style="padding:2px;" class="text-center">
<?php echo $row_rs_visit['vsttime']; ?>
</td>
</tr>
<?php } while($row_rs_visit = mysql_fetch_assoc($rs_visit)); ?>
</tbody>
</table>
</div>
</div>

<div class="col " style="padding:0px;">
<nav class="navbar navbar-dark thfont bg-info" style="background-color: #F69;">
  <!-- Navbar content -->
  <span class="card-title text-white" style="padding-top:5px;">
บันทึกรายการยา Medication Reconciliation
</span>
</nav>
<div style="margin-top: 0px; overflow:scroll;overflow-x:hidden;overflow-y:auto; height:90vh; margin-right:15px;">
	<div style="padding:0px; padding-right:0px;">
	<!--indicator-->
	<div id="drug_indicator" align="center" class="spinner">
	<div class="spinner-border" style="width: 5rem; height: 5rem;" role="status"></div>

	  <span class="sr-only">Loading...</span>
	</div>
		<div id="drug_list"  ></div>
	</div>
</div>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rs_visit);


?>
