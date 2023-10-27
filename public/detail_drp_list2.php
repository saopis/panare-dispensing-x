<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

if(isset($_GET['action'])&&($_GET['action']=="delete")){
	mysql_select_db($database_hos, $hos);
	$query_delete = "delete from drp_problem_list where drp_problem_list_id='".$_GET['id']."' ";
	$delete = mysql_query($query_delete, $hos) or die(mysql_error());

	//delete replicate_log
	mysql_select_db($database_hos, $hos);
	$query_ptdepart = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','delete from drp_problem_list where drp_problem_list_id=\'".$_GET['id']."\'')";
	$ptdepart = mysql_query($query_ptdepart, $hos) or die(mysql_error());	

}

if(isset($_GET['vstdate'])){ $vstdate=date_th2db($_GET['vstdate']); }
if(isset($_POST['vstdate'])){ $vstdate=date_th2db($_POST['vstdate']); }

if(isset($_GET['hn'])){ $hn_search=$_GET['hn']; }
if(isset($_POST['hn'])){ $hn_search=$_POST['hn']; }


if(isset($_GET['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_GET['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);
    
    }

if(isset($_POST['q'])){ 
    mysql_select_db($database_hos, $hos);
    $query_rs_hn = "select hn from ovst where vstdate='".$vstdate."' and oqueue='".$_POST['q']."'";
    $rs_hn = mysql_query($query_rs_hn, $hos) or die(mysql_error());
    $row_rs_hn = mysql_fetch_assoc($rs_hn);
    $totalRows_rs_hn = mysql_num_rows($rs_hn);

    $hn_search=$row_rs_hn['hn']; 

    mysql_free_result($rs_hn);

    }


mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

$hn=sprintf("%".$row_setting[24]."d", $hn_search);

mysql_select_db($database_hos, $hos);
$query_rs_vn = "select vn from vn_stat where hn='".$hn."' and vstdate='".$vstdate."' ";
$rs_vn = mysql_query($query_rs_vn, $hos) or die(mysql_error());
$row_rs_vn = mysql_fetch_assoc($rs_vn);
$totalRows_rs_vn = mysql_num_rows($rs_vn);

$vn=$row_rs_vn['vn'];

mysql_free_result($rs_vn);

mysql_select_db($database_hos, $hos);
$query_rs_problem_list = "select l.drp_problem_list_id,l.drp_datetime,c.std_code,c.drp_cause_name,o.drp_outcome_type_name,i.drp_intervention_type_name as intervention1,i2.drp_intervention_type_name as intervention2,i3.drp_intervention_type_name as intervention3,l.intervention_note,l.need_follow_up,l.staff,concat(s.name,s.strength) as drugname,r.hn,r.vn from drp_problem_list l  left outer join drp_regist r on r.drp_regist_id=l.drp_regist_id left outer join drp_cause c on c.drp_cause_id=l.drp_cause_id left outer join drp_outcome_type o on o.drp_outcome_type_id=l.drp_outcome_type_id left outer join drp_intervention_type i on i.drp_intervention_type_id=l.drp_intervention_type_id_1 left outer join drp_intervention_type i2 on i2.drp_intervention_type_id=l.drp_intervention_type_id_2 left outer join drp_intervention_type i3 on i3.drp_intervention_type_id=l.drp_intervention_type_id_3 left outer join s_drugitems s on s.icode=l.icode where vn='".$vn."' order by l.drp_datetime DESC";
$rs_problem_list = mysql_query($query_rs_problem_list, $hos) or die(mysql_error());
$row_rs_problem_list = mysql_fetch_assoc($rs_problem_list);
$totalRows_rs_problem_list = mysql_num_rows($rs_problem_list);

mysql_select_db($database_hos, $hos);
$query_rs_problem_list2 = "select count(*) as count_drp from drp_problem_list l  left outer join drp_regist r on r.drp_regist_id=l.drp_regist_id where hn='".$hn."'";
$rs_problem_list2 = mysql_query($query_rs_problem_list2, $hos) or die(mysql_error());
$row_rs_problem_list2 = mysql_fetch_assoc($rs_problem_list2);
$totalRows_rs_problem_list2 = mysql_num_rows($rs_problem_list2);

$total_drp=$row_rs_problem_list2['count_drp'];

mysql_free_result($rs_problem_list2);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<script>
$(document).ready(function() {
		$('.drp-long2').hide();	
	
    $('.drp-short2').click(function(){
		$('.drp-table2').slideUp(500);
		$('.drp-short2').hide();	
		$('.drp-long2').show();	
	});
    $('.drp-long2').click(function(){
		$('.drp-table2').slideDown(500);
		$('.drp-long2').hide();	
		$('.drp-short2').show();	
	});

});
</script>

<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

</head>

<body>
<?php if($total_drp<>0){ ?>
<div class="card ">
  <div class="card-header p-2">
    <span class=" font14 font_bord">ปัญหาจากการใช้ยา (Drug Related Problem : DRP)&emsp;<div class="float-right"><span class="badge badge-primary font14 cursor"  onclick="alertload('drp2.php?hn=<?php echo $hn; ?>&vn=<?php echo $vn; ?>','90%','90%')" style="padding:9px;" ><i class="fas fa-save"></i>&nbsp;บันทึกข้อมูล</span>&nbsp;<span class="badge badge-primary font14 cursor p-2" onClick="alertload('drp2.php?hn=<?php echo $hn; ?>&action=showall','90%','90%')"><i class="fas fa-align-justify"  style="cursor:pointer"></i>&nbsp;แสดงทั้งหมด <span class="badge badge-light"><?php echo $total_drp; ?></span></span><button type="button" class="btn btn-light btn-sm drp-short2" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อ 
</button><button type="button" class="btn btn-light btn-sm drp-long2"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย <span class="badge badge-primary"><?php echo $totalRows_rs_couselling; ?></span>
</button></div></span>
  </div>
  <?php if($totalRows_rs_problem_list<>0){ ?>
  <div class="card-body drp-table2">
<table class="table table-sm display font12 " style="width:100%; margin-top:-10px; margin-bottom:-10px;" >
  <thead class="text-center">
    <tr >
      <th align="center" valign="top" >วันที่</th>
      <th align="center" valign="top" >เวลา</th>
      <th align="center" valign="top" >รหัสปัญหา</th>
      <th align="center" valign="top" >รายละเอียดปัญหา</th>
      <th align="center" valign="top" >เวชภัณฑ์ที่เกี่ยวข้อง</th>
      <th align="center" valign="top" >ผู้บันทึก</th>
      <th valign="top" >&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php do{ ?>
    <tr class="grid4">
      <td align="center"><?php echo date_db2th(substr($row_rs_problem_list['drp_datetime'],0,10)); ?></td>
      <td align="center"><?php print substr($row_rs_problem_list['drp_datetime'],11,5); ?></td>
      <td align="center"><?php print $row_rs_problem_list['std_code']; ?></td>
      <td><?php print mb_substr($row_rs_problem_list['drp_cause_name'],0,30,'UTF-8'); if(strlen($row_rs_problem_list['drp_cause_name'])>50){ echo "...";} ?></td>
      <td><?php print substr($row_rs_problem_list['drugname'],0,30); ?></td>
      <td align="center"><?php print $row_rs_problem_list['staff']; ?></td>
      <td align="center"><i class="fas fa-pen-square" style="color:#0066CC; font-size:20px; cursor:pointer" onClick="alertload('drp2.php?hn=<?php echo $hn; ?>&vn=<?php echo $vn; ?>&id=<?php echo $row_rs_problem_list['drp_problem_list_id']; ?>','90%','90%')"></i>&ensp;<i class="fas fa-minus-square" onClick="if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){drp_load2('<?php echo $hn; ?>','delete','<?php echo $row_rs_problem_list['drp_problem_list_id']; ?>'); }" style="color: #F10101; font-size:20px; cursor:pointer"></i></td>
    </tr>
   	<?php } while($row_rs_problem_list = mysql_fetch_assoc($rs_problem_list)); ?>
    </tbody>
  </table>
  </div>
<?php } ?>
</div>
<?php } ?>
</body>
</html>
<?php 
mysql_free_result($rs_problem_list);
?>