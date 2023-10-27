<?php ob_start();?>
<?php session_start();?>
<?php if(($_SESSION['r_opd']!='Y') and ($_SESSION['r_finance']!='Y')){
	echo "คุณไม่ได้รับสิทธิ์ให้ใช้งานในส่วนนี้";
	exit();
	} ?>
<?php require_once('Connections/hos.php'); ?>
<?php require('include/get_channel.php'); ?>

<?php include('include/function.php'); ?>
<?php
mysql_select_db($database_hos, $hos);
$query_rs_visit_list = "SELECT  v.hn,concat(DATE_FORMAT(v.vstdate,'%d/%m/'),(substring(v.vstdate,1,4)+543)) as date1,v.vstdate,o.vsttime,v.pdx,i.name,p.name as pttype_name,d.name as doctor_name,v.vn,o.oqueue FROM vn_stat v left outer join icd101 i on i.code=v.pdx left outer join pttype p on p.pttype=v.pttype left outer join ovst o on o.vn=v.vn left outer join doctor d on d.code=o.doctor WHERE v.hn='".$_GET['hn']."' and v.vstdate='".$_GET['vstdate']."'";
//echo $query_rs_visit_list;
$rs_visit_list = mysql_query($query_rs_visit_list, $hos) or die(mysql_error());
$row_rs_visit_list = mysql_fetch_assoc($rs_visit_list);
$totalRows_rs_visit_list = mysql_num_rows($rs_visit_list);
?>
<?php if($_GET['action']=="popup"){ $parent="parent."; }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="include/kohrx/css/kohrx.css"/>
<?php if($_GET['action']=="popup"){ include('java_css_online.php'); } ?>
</head>

<body>
<div class="p-3">
<div class="row">
    <div class="col-sm-auto">
        <img src="images/multi_visit.png" width="50" height="50" alt=""/>      
    </div>
    <div class="col-sm">
        <div class="alert bg-info text-white" role="alert">
        ผู้ป่วยรับบริการในวันที่ <?php echo dateThai($_GET['vstdate']); ?> มากกว่า 1 ครั้ง กรุณาเลือก visit ที่ผู้ป่วยมารับบริการจากรายการข้างล่าง</div>
    </div>
</div>
          <table width="1000" class="display table table-hover">
            <thead>
            <tr >
              <th class="text-center" ><strong>ลำดับ</strong></th>
              <th class="text-center"><strong>วันที่รับบริการ</strong></th>
              <th class="text-center"><strong>สิทธิ์</strong></th>
              <th class="text-center" ><strong>วินิจฉัย</strong></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=0; do { $i++;             ?>
            <tr style="cursor: pointer" onClick="<?php echo $parent; ?>detail_load_vn('<?php echo $row_rs_visit_list['vn']; ?>','<?php echo $row_rs_visit_list['hn']; ?>','<?php echo date_db2th($row_rs_visit_list['vstdate']); ?>');<?php echo $parent; ?>vstdate_fill('<?php echo date_db2th($_GET['vstdate']); ?>');<?php if($row_channel['cursor_position']=='queue'){ echo $parent."q_fill('".$row_rs_visit_list['oqueue']."');"; } else if($row_channel['cursor_position']=='hn_search'){ echo $parent."hn_fill('".$row_rs_visit_list['hn']."');"; } else { echo $parent."fistFocus('hn'); $parent.hn_fill('".$row_rs_visit_list['hn']."');"; } ?>;parent.$.fn.colorbox.close();">
              <td height="30" align="center" ><?php echo "<span class=\"badge badge-dark text-white font16\">".$i."</span>"; ?></td>
              <td align="center" "><?php echo dateThai($row_rs_visit_list['vstdate']); ?> &nbsp; <?php echo $row_rs_visit_list['vsttime']; ?></td>
              <td align="center" "><?php echo $row_rs_visit_list['pttype_name']; ?></td>
              <td align="left" "><?php if(!empty($row_rs_visit_list['pdx'])){ echo "($row_rs_visit_list[pdx])"."  "."$row_rs_visit_list[name]"; } ?></td>
            </tr>
            <?php } while ($row_rs_visit_list = mysql_fetch_assoc($rs_visit_list)); ?>
            </tbody>
          </table>

</div>
<form id="form4" name="form4" method="post" action="">
  <input type="hidden" name="do4" id="do4" />
  <input type="hidden" name="id4" id="id4" />
  <br />
</form>
</body>
</html>
<?php
mysql_free_result($rs_visit_list);
mysql_free_result($channel);

?>
