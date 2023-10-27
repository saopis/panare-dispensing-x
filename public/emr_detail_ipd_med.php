<?php require_once('Connections/hos.php'); ?>
<?php 
if($_GET['order_no']==""){
mysql_select_db($database_hos, $hos);
$query_rs_order_no = "select o.order_no from (select order_no from opitemrece where  an='".$_GET['an']."' union select order_no from opitemrece_arc where  an='".$_GET['an']."') as o left outer join ipt_order_no i on i.order_no=o.order_no where i.order_type in ('IRx','Hme')  group by o.order_no order by o.order_no DESC limit 1";
$rs_order_no = mysql_query($query_rs_order_no, $hos) or die(mysql_error());
$row_rs_order_no = mysql_fetch_assoc($rs_order_no);
$totalRows_rs_order_no = mysql_num_rows($rs_order_no);
    
$order=$row_rs_order_no['order_no'];

}
else{
$order=$_GET['order_no'];    
}

mysql_select_db($database_hos, $hos);
$query_s_drug = "select m1.order_no,concat(s.name,' ',s.strength,' ',s.units) as name ,d.shortlist  , mp.med_order_number,m1.icode,concat(s.name,' ',s.strength,' ',s.units) as drugname,concat(sp.name1,sp.name2,sp.name3) as sp_name, m1.qty,mp.med_plan_number,mp.med_real_pay_qty,mp.day_number,substring(m1.icode,1,1) as scode   from (select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no from opitemrece where order_no='".$order."' and an='".$_GET['an']."' union select an,order_no,icode,qty,drugusage,hos_guid,sp_use,item_no from opitemrece_arc where order_no='".$order."' and an='".$_GET['an']."') m1    left outer join s_drugitems s on s.icode=m1.icode   left outer join drugusage d on d.drugusage=m1.drugusage  left outer join medpay_ipd mp on mp.hos_guid = m1.hos_guid left outer join sp_use sp on sp.sp_use=m1.sp_use order by item_no ";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>

</head>

<body>
<div class="card">
            <!-- indicator -->
        <div id="indicator_medpay" align="center" class="spinner position-absolute" style="margin-top:20px; margin-left: 45%">
                <button class="btn btn-light" type="button" style="width:200px;" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  <span >กำลังโหลด..</span>
                </button>
        </div>
        <!-- indicator --> 

   <div class="card-body p-2">
<table width="100%" border="0" align="left" cellpadding="2" cellspacing="0" class="table_border"  >
      <tr class="table_head_small">
        <td width="6%" height="28" align="center" bgcolor="#CCCCCC" class=" rounded-left">ลำดับ</td>
        <td width="39%" align="center" class="" bgcolor="#CCCCCC" >ชื่อยา</td>
        <td width="45%" align="center" class="" bgcolor="#CCCCCC" >วิธีใช้</td>
        <td width="10%" align="center" class=" rounded-right" bgcolor="#CCCCCC" >จำนวน </td>
      </tr>
      <?php $i=0; do { $i++;
  if($bgcolor=="#FFFFFF") { $bgcolor="#F2F2F2"; $font="#FFFFFF"; } else { $bgcolor="#FFFFFF"; $font="#999999";  }

?>
      <tr class="grid3">
        <td height="27" align="center" bgcolor="<? echo $bgcolor; ?>" style="border-right: solid 1px #EEE"><?=$i; ?></td>
        <td bgcolor="<? echo $bgcolor; ?>" class=" font12"  style="border-right: solid 1px #EEE; padding-right:5px"><?php echo $row_s_drug['drugname']; ?></td>
        <td align="left" bgcolor="<? echo $bgcolor; ?>" style="border-right: solid 1px #EEE">
          <input type="text" name="textfield10" id="textfield10" class="font12"  style="width:100%; border:1px #FFFFFF; background-color:transparent; alignment-baseline:middle" value="<?php if($row_s_drug['sp_use']=="") {echo "$row_s_drug[shortlist]"; } else { echo "$row_s_drug[sp_name]"; } ?>"/>
        </td>
        <td align="center" class="font12" bgcolor="<? echo $bgcolor; ?>" ><?php echo "$row_s_drug[qty]"; ?></td>
      </tr>
      <?php 
 } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </table>
    </div>
  </div>
</body>
</html>
<?php mysql_free_result($s_drug); ?>