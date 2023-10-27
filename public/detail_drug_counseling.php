<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }

if(isset($_GET['hn'])){ $hn=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn=$_POST['hn']; }


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select  *,concat(date_format(record_date,'%d/%m/'),(date_format(record_date,'%Y')+543)) as record_date2 from ".$database_kohrx.".kohrx_couselling where hn='".$hn."' order by id DESC";
$rs_couselling = mysql_query($query_rs_couselling, $hos) or die(mysql_error());
$row_rs_couselling = mysql_fetch_assoc($rs_couselling);
$totalRows_rs_couselling = mysql_num_rows($rs_couselling);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

<script>
$(document).ready(function() {
		$('.counseling-long').hide();	
	
    $('.counseling-short').click(function(){
		$('.counseling_table').fadeOut(500);
		$('.counseling-short').hide();	
		$('.counseling-long').show();	
	});
    $('.counseling-long').click(function(){
		$('.counseling_table').fadeIn(500);
		$('.counseling-long').hide();	
		$('.counseling-short').show();	
	});

});
</script>
</head>

<body>
<?php if($totalRows_rs_couselling<>0){ ?>
<div class="card">
<div class="card-header"><i class="fas fa-user-md" style="font-size:20px;"></i>&ensp;<span class="card-title" >&nbsp;ประวัติการได้รับคำแนะนำในการใช้ยาที่ต้องใช้เทคนิคพิเศษ</span></div>
<div class="card-body" style="padding:0px;">
                <div id="counseling_indicator" align="center" class="spinner" style="position:absolute; margin-top:0px;">
                <button class="btn btn-secondary" type="button" style="width:200px;" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span >กำลังโหลดรายการ Counseling</span>
</button>
                </div>
<div  style="position:absolute; right:10px; margin-top:-40px;"><button type="button" class="btn btn-light btn-sm counseling-short" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อ 
</button><button type="button" class="btn btn-light btn-sm counseling-long"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย <span class="badge badge-primary"><?php echo $totalRows_rs_couselling; ?></span>
</button>
</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="3"  class="table table-striped table-hover head_small_gray table-sm counseling_table">
    <thead class="thfont font13 font_bord text-center  thead-dark" style="height:25px">    
    <tr class="head">
      <th  align="center" bgcolor="#CCCCCC" scope="col">วันที่</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">ประเภท</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">ยาที่ให้คำแนะนำ</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">แนะนำแก่</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">ผล</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">ปัญหาที่พบ</th>
      <th  align="center" bgcolor="#CCCCCC" scope="col">note</th>
      <th  colspan="2" align="center" bgcolor="#CCCCCC" scope="col">ผู้ให้คำปรึกษา
        <input type="hidden" name="do5" id="do5" />
        <input type="hidden" name="id5" id="id5" /></th>
      </tr>
    </thead>
    <tbody>
    <?php do { 
  if($bgcolor=="#FFFFFF") { $bgcolor="#F9F9F9"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999"; }
  
  mysql_select_db($database_hos, $hos);
$query_rs_drug = "select concat(name,' ',strength) as drugname from s_drugitems where icode='".$row_rs_couselling['icode']."'";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);

mysql_select_db($database_hos, $hos);
$query_rs_couseller = "select name from doctor where code='".$row_rs_couselling['couseller']."'";
$rs_couseller = mysql_query($query_rs_couseller, $hos) or die(mysql_error());
$row_rs_couseller = mysql_fetch_assoc($rs_couseller);
$totalRows_rs_couseller = mysql_num_rows($rs_couseller);


	$date2=explode("-",$row_rs_couselling['record_date']);
	$edate2=($date2[2]."/".$date2[1]."/".($date2[0]+543));
  ?>
    <tr class="table_head_small">
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_couselling[record_date2]"; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_couselling[patient_type]"; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_drug[drugname]"; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php if($row_rs_couselling['patient']=='1'){ echo "ผู้ป่วย";} else{ echo "$row_rs_couselling[other]"; }; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php if($row_rs_couselling['result']==1){echo "<i class=\"fas fa-laugh-beam\" style=\"font-size:25px;color:#339933\"></i>";} if($row_rs_couselling['result']==2){echo "<i class=\"fas fa-flushed\" style=\"font-size:25px; color:#FF985A;\"></i>";} if($row_rs_couselling['result']==3){echo "<i class=\"fas fa-tired\" style=\"font-size:25px;color:#F2626B\"></i>";}  ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_couselling[problem]"; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_couselling[note]"; ?></td>
      <td  align="center" bgcolor="<? echo $bgcolor; ?>"><?php echo "$row_rs_couseller[name]"; ?></td>
      <td align="center" bgcolor="<? echo $bgcolor; ?>"><i class="fas fa-pen" style=" cursor:pointer;color:#666666;font-size:16px;" onClick="alertload('form_couselling.php?id=<?php echo $row_rs_couselling['id']; ?>','80%','80%');" ></i></td>
    </tr>
    <?php mysql_free_result($rs_drug);
	mysql_free_result($rs_couseller);
	} while ($row_rs_couselling = mysql_fetch_assoc($rs_couselling)); ?>
    </tbody>
  </table>
</div>
<!-- card body -->
</div>
<!-- card -->
<?php } ?>
</body>
</html>
<?php
mysql_free_result($rs_couselling);
?>