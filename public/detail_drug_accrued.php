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
$query_payable = "SELECT concat(d.name,' ',d.strength) as  drugname,k.*,concat(DATE_FORMAT(k.date_service,'%d/%m/'),(DATE_FORMAT(k.date_service,'%Y'))+543) as date1,concat(DATE_FORMAT(k.date_payable,'%d/%m/'),(DATE_FORMAT(k.date_payable,'%Y'))+543) as date2,c.name from ".$database_kohrx.".kohrx_payable k left outer join drugitems d on d.icode=k.drug left outer join doctor c on c.code=k.doctor WHERE k.hn='".$hn."' and k.date_pay is null";
$payable = mysql_query($query_payable, $hos) or die(mysql_error());
$row_payable = mysql_fetch_assoc($payable);
$totalRows_payable = mysql_num_rows($payable);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>

</head>

<body>
<?php if($totalRows_payable<>0){ ?>
<div class="card">
<div class="card-header"><span class="thfont font14 font_bord">รายการยาค้างจ่าย</span></div>
<div class="card-body" style="padding:0px; border: 0px;">
<table width="100%" border="0" cellspacing="0" class="table table-striped table-hover thfont font14" >
    <thead >
    <tr >
                      <th scope="col"   >no</th>
                      <th  scope="col" >รายการยา</th>
                      <th scope="col" >จำนวน</th>
                      <th scope="col"  >วันที่ค้างจ่าย</th>
                      <th scope="col" >วันที่นัด</th>
                      <th scope="col" >หมายเหตุ</th>
                      <th scope="col" >ผู้ค้างจ่าย</th>
                      <th scope="col" >&nbsp;</th>
                    </tr>
        </thead>
        <tbody class="font12">
                    <?php $i=0; do { $i++; ?>
                    <tr >    
                      <td height="26" align="center"><?php echo $i; ?></td>
                      <td align="left" style=" padding-left:10px"><?php echo $row_payable['drugname']; ?></td>
                      <td align="center"><?php echo $row_payable['among']; ?></td>
                      <td align="center"><?php echo $row_payable['date1']; ?></td>
                      <td align="center"><?php echo $row_payable['date2']; ?></td>
                      <td align="left" style=" padding-left:10px"><?php echo $row_payable['remark']; ?></td>
                      <td align="center" ><?php echo $row_payable['name']; ?></td>
                      <td align="center" ><a href="#" onClick="alertload('payable.php?id=<?php echo $row_payable['id']; ?>','60%','500')"><i class="far fa-edit font16"></i></a></td>
                    </tr>
                    <?php } while ($row_payable = mysql_fetch_assoc($payable)); ?>
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
mysql_free_result($payable);
?>