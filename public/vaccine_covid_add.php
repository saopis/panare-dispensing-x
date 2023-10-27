<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
            for($i=$_POST['numberstart'];$i<=(($_POST['numberstart']-1)+$_POST['qty']);$i++){
             for($j=1;$j<=$_POST['dose'];$j++){
                if($_POST['person_vaccine']=="67"){
                    $vaccine_number=$_POST['prefix'].str_pad($i, 3, '0', STR_PAD_LEFT);
                }
                else{
                    $vaccine_number=$i;
                }                 
                mysql_select_db($database_hos, $hos);
                $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,vaccine_sn,url) value (".$_POST['person_vaccine'].",'".$_POST['lot']."','".$_POST['exp']."','".$vaccine_number."','".$j."','".date_th2db($_POST['receive_date'])."','".$_POST['receive_time']."','".$_POST['vaccine_dose']."','".$_POST['sn']."','http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
                $rs = mysql_query($query_rs, $hos) or die(mysql_error());
             }
        }    
    
}
/*
for($i=509;$i<=733;$i++){
    for($j=1;$j<=12;$j++){
    mysql_select_db($database_hos, $hos);
    $query_rs = "insert into dispensing.kohrx_vaccine_record (person_vaccine_id,vaccine_lot_no,vaccine_exp,vaccine_number,vaccine_dose_number,vaccine_receive_date,vaccine_receive_time,vaccine_order,url) value ('68','A1068','31/12/2021
','".$i."','".$j."','2021-09-17','11:00:00',2,'http://159.192.104.60/organization/pharmacy/service/dispensingx/vaccine_record.php')";
    $rs = mysql_query($query_rs, $hos) or die(mysql_error());
    }

}
*/
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบบันทึก Vaccine covid ลงฐานข้อมูล</title>
<?php include('java_css_file.php'); ?>    
</head>

<body>
<div class="p-3">
    <form method="post" action="vaccine_covid_add.php">
        <div class="form-row">
            <div class="form-group col-2">
                <label for="person_vaccine">เลือกวัคซีน</label>
                <select id="person_vaccine" name="person_vaccine" class="form-control" >
                    <option value="67" <?php if($_POST['person_vaccine']=="67"){ echo "selected"; } ?> >Sinovac</option>
                    <option value="68" <?php if($_POST['person_vaccine']=="68"){ echo "selected"; } ?> >Astrazeneca</option>
                    <option value="76" <?php if($_POST['person_vaccine']=="76"){ echo "selected"; } ?>>Pfizer</option>
                    <option value="84" <?php if($_POST['person_vaccine']=="84"){ echo "selected"; } ?> >Sinopharm</option>
                </select>
            </div>
            <div class="form-group col-2">
                <label for="vaccine_dose">เข็มที่</label>
                <select id="vaccine_dose" name="vaccine_dose" class="form-control">
                    <option value="1" <?php if($_POST['vaccine_dose']=="1"){ echo "selected"; } ?>>เข็มที่ 1</option>
                    <option value="2" <?php if($_POST['vaccine_dose']=="2"){ echo "selected"; } ?>>เข็มที่ 2</option>
                    <option value="3" <?php if($_POST['vaccine_dose']=="3"){ echo "selected"; } ?>>เข็มที่ 3</option>
                </select>    
            </div>            
            
        </div>
        <div class="form-row">
            <div class="form-group col-1">
                <label for="prefix">อักษรขึ้นต้น</label>
                <input type="text" id="prefix" name="prefix" class="form-control" value="<?php echo $_POST['prefix']; ?>"/>                
            </div>            
            <div class="form-group col-1">
                <label for="numberstart">เริ่มขวดที่</label>
                <input type="text" id="numberstart" name="numberstart" class="form-control" value="<?php echo $_POST['numberstart']; ?>"/>                
            </div>            
            <div class="form-group col-1">
                <label for="qty">จำนวนขวด</label>
                <input type="text" value="<?php echo $_POST['qty']; ?>" id="qty" name="qty" class="form-control"/>                
            </div>            
            <div class="form-group col-1">
                <label for="dose">โดส/ขวด</label>
                <input type="text" value="<?php echo $_POST['dose']; ?>" id="dose" name="dose" class="form-control"/>                
            </div>            
        </div>
        <div class="form-row">
            <div class="form-group col-2">
                <label for="lot">LOT NO</label>
                <input type="text" value="<?php echo $_POST['lot']; ?>" id="lot" name="lot" class="form-control"/>                
            </div>            
            <div class="form-group col-2">
                <label for="exp">Expired Date</label>
                <input type="text" value="<?php echo $_POST['exp']; ?>" id="exp" name="exp" class="form-control"/>                
            </div>            
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                <label for="sn">Serial Number</label>
                <textarea id="sn" name="sn" class="form-control" ><?php echo $_POST['sn']; ?></textarea>    
            </div>            
        </div>
        <div class="form-row">
            <div class="form-group col-2">
                <label for="receive_date">วันที่รับ</label>
                <input type="text" id="receive_date" name="receive_date" class="form-control" value="<?php echo date_db2th(date('Y-m-d')); ?>"/>                
            </div>            
            <div class="form-group col-2">
                <label for="receive_time">เวลารับ</label>
                <input type="text" id="receive_time" name="receive_time" class="form-control" value="<?php echo date('H:m:s'); ?>"/>                
            </div>            
        </div>
        <div class="form-row">
            <input type="submit" id="save" name="save" value="บันทึก" class="btn btn-primary"/>
        </div>
    </form>
</div>    
<?php if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
echo "<div class='m-4'><div class=\"alert alert-success\" role=\"alert\">บันทึกเรียบร้อยแล้ว</div></div>";
} ?>
</body>
</html>