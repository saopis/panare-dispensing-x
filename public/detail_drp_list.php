<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>

<?php
include('include/function.php');

mysql_select_db($database_hos, $hos);
$query_rs_drp = "select k.*,record_date,dd.icode from ".$database_kohrx.".kohrx_drp_record k left outer join ".$database_kohrx.".kohrx_drp_drug dd on dd.drp_id=k.id where hn='".$_GET['hn']."' group by k.id order by k.id DESC ";
$rs_drp = mysql_query($query_rs_drp, $hos) or die(mysql_error());
$row_rs_drp = mysql_fetch_assoc($rs_drp);
$totalRows_rs_drp = mysql_num_rows($rs_drp);

if($totalRows_rs_drp==0){
    echo "<script>parent.drp_button_show();</script>";
}else{
    echo "<script>parent.drp_button_hide();</script>";
    
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
$(document).ready(function() {
		$('.drp-long').hide();	
	
    $('.drp-short').click(function(){
		$('.drp-table').slideUp(500);
		$('.drp-short').hide();	
		$('.drp-long').show();	
	});
    $('.drp-long').click(function(){
		$('.drp-table').slideDown(500);
		$('.drp-long').hide();	
		$('.drp-short').show();	
	});

});
</script>

</head>

<body>
<?php if($totalRows_rs_drp<>0){ ?>
<div class="card ">
  <div class="card-header p-2">
    <span class=" font14 font_bord">ปัญหาจากการใช้ยา (Drug Related Problem : DRP)&nbsp;[kohrx version]
    <div class="float-right"><button class="btn btn-secondary btn-sm" style="margin-right: 10px;"  onclick="alertload('detail_drp.php?hn=<?php  echo $hn; ?>&pt=<?php echo $_SESSION['pt']; ?>','90%','90%');"><span class="badge badge-light font14">+</span>&nbsp;<strong>DRP</strong></button><button type="button" class="btn btn-light btn-sm drp-short" style="padding:3px;">
 <i class="fas fa-minus-circle"></i> ย่อ 
</button><button type="button" class="btn btn-light btn-sm drp-long"  style="padding:3px;">
 <i class="fas fa-plus-circle"></i> ขยาย <span class="badge badge-primary"><?php echo $totalRows_rs_couselling; ?></span>
</button></div></span>
  </div>
<div class="card-body drp-table">
<table class="table table-sm table-hover display font12 " style="width:100%; margin-top:-10px; margin-bottom:-10px;" >
	<thead>
		<tr>
			<th>ลำดับ</th>
			<th>รายละเอียด</th>
			<th>แนวทางแก้ไข</th>
			<th>ผลลัพธ์</th>
			<th>ผู้บันทึก</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; do{ $i++; ?>
		<tr>
			<td colspan="5" style="font-weight: bold"><span class="badge badge-secondary font16"><?php echo $i; ?></span>&nbsp;<?php echo "&nbsp;".$row_rs_drp['title']." <span style=\"font-weight: normal\">&nbsp;(".date_db2th($row_rs_drp['record_date']).")</span>"; if($row_rs_drp['attach']!=""){ echo "&nbsp;<i class=\"fas fa-image font20 text-danger\"></i>"; } ?><?php if($row_rs_drp['icode']!=""){ ?>&nbsp;<i class="fas fa-pills text-success font20"></i><?php } ?>&ensp;<i class="fas fa-edit font20 text-secondary cursor" onClick="alertload('detail_drp.php?id=<?php echo $row_rs_drp['id']; ?>','90%','90%');"></i></td>
		</tr>
		<tr>
			<td class="text-center"></td>
			<td><?php echo $row_rs_drp['detail']; ?></td>
			<td><?php echo $row_rs_drp['solv']; ?></td>
			<td><?php echo $row_rs_drp['result']; ?></td>
			<td></td>
		</tr>
		  <?php } while($row_rs_drp = mysql_fetch_assoc($rs_drp)); ?>
	</tbody>
</table>
</div>
	</div>
<?php } ?>
</body>
</html>
<?php mysql_free_result($rs_drp); ?>