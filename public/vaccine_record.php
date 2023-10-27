<?php require_once('Connections/hos.php'); ?>
<?php 
        include('include/function.php');

if(isset($_POST['save'])&&($_POST['save']=="บันทึก"))
{
    mysql_select_db($database_hos, $hos);
    $query_insert = "insert into ".$database_kohrx.".kohrx_person_vaccine_record (vn,person_vaccine_id,vaccine_lot_no,vaccine_number,vaccine_dose_number,vaccine_sn,doctorcode,vaccine_datetime,vaccine_order) value ('".$_POST['patient']."','".$_POST['person_vaccine_id']."','".$_POST['lot']."','".$_POST['vaccnum']."','".$_POST['vaccdn']."','".$_POST['sn']."','".$_POST['doctor']."','".date_th2db($_POST['date'])." ".$_POST['time'].":00','".$_POST['order_time']."')";
    $insert = mysql_query($query_insert, $hos) or die(mysql_error());

    echo "<script>window.location='vaccine_record.php?sn=".$_POST['sn']."&vaccnum=".$_POST['vaccnum']."&vaccdn=".$_POST['vaccdn']."'</script>";
    exit();
}
if(isset($_POST['delete'])&&($_POST['delete']=="ลบข้อมูล"))
{
    mysql_select_db($database_hos, $hos);
    $query_delete = "delete from ".$database_kohrx.".kohrx_person_vaccine_record where vn ='".$_POST['vn']."'";
    $delete = mysql_query($query_delete, $hos) or die(mysql_error());

    echo "<script>window.location='vaccine_record.php?sn=".$_POST['sn']."&vaccnum=".$_POST['vaccnum']."&vaccdn=".$_POST['vaccdn']."'</script>";
    exit();

}
        mysql_select_db($database_hos, $hos);
        $query_vaccine = "select p.vaccine_name,pv.vaccine_order as vaccine_order2,r.*,pv.vn,concat(pt.pname,pt.fname,' ',pt.lname) as ptname,pv.doctorcode,pv.vaccine_datetime from ".$database_kohrx.".kohrx_vaccine_record r left outer join person_vaccine p on p.person_vaccine_id=r.person_vaccine_id left outer join ".$database_kohrx.".kohrx_person_vaccine_record pv on pv.person_vaccine_id=r.person_vaccine_id and pv.vaccine_number=r.vaccine_number and pv.vaccine_lot_no=r.vaccine_lot_no and pv.vaccine_dose_number=r.vaccine_dose_number and pv.vaccine_sn =r.vaccine_sn left outer join vn_stat v on v.vn=pv.vn left outer join patient pt on pt.hn=v.hn where r.vaccine_sn='".$_GET['sn']."' and r.vaccine_number='".$_GET['vaccnum']."' and r.vaccine_dose_number='".$_GET['vaccdn']."'";
        //echo $query_vaccine;
        $vaccine = mysql_query($query_vaccine, $hos) or die(mysql_error());
        $row_vaccine = mysql_fetch_assoc($vaccine);
        $totalRows_vaccine = mysql_num_rows($vaccine);

        mysql_select_db($database_hos, $hos);
        $query_pt = "select concat(p.pname,p.fname,' ',p.lname) as ptname,p.hn,v.vn from vn_stat v left outer join patient p on p.hn=v.hn where vstdate = CURDATE()  and v.vn not in (select vn from ".$database_kohrx.".kohrx_person_vaccine_record)";
        $pt = mysql_query($query_pt, $hos) or die(mysql_error());
        $row_pt = mysql_fetch_assoc($pt);
        $totalRows_pt = mysql_num_rows($pt);     
 
        mysql_select_db($database_hos, $hos);
        $query_doctor = "select name,code from doctor where active='Y' and position_id='5'";
        $rs_doctor = mysql_query($query_doctor, $hos) or die(mysql_error());
        $row_doctor = mysql_fetch_assoc($rs_doctor);
        $totalRows_doctor = mysql_num_rows($rs_doctor);   
 
        mysql_select_db($database_hos, $hos);
        $query_doctor2 = "select doctorcode from dispensing.kohrx_person_vaccine_record where substr(vaccine_datetime,1,10)=CURDATE() group by doctorcode";
        $rs_doctor2 = mysql_query($query_doctor2, $hos) or die(mysql_error());
        $row_doctor2 = mysql_fetch_assoc($rs_doctor2);
        $totalRows_doctor2 = mysql_num_rows($rs_doctor2);   
        
        include('include/function_sql.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบบันทึกการให้บริการวัคซีน โรงพยาบาลมหาชนะชัย</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('java_css_file.php'); ?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="include/select/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="include/select/js/bootstrap-select.min.js"></script>
<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" ></script>
<script>
  $(document).ready(function(){
    $('#doctor').change(function(){
        $('input[name=doctor2]').prop('checked', false);
        })
        $("#date").inputmask({"mask": "99/99/9999"});   
        $("#time").inputmask({"mask": "99:99"}); 
        var dt = new Date();
        var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        var d = new Date();
        var curr_hour = pad(d.getHours(),2);
        var curr_min = pad(d.getMinutes(),2);
        var curr_now=curr_hour+":"+curr_min;
        $('#time').val(curr_now);
        
        
  });
    
    function pad(n, len) 
    {
    s = n.toString();
    if (s.length < len) 
    {
        s = ('0000000000' + s).slice(-len);
    }    
    return s;
    }
    
    function ConfirmDelete()
    {
      var x = confirm("ต้องการลบจริงหรือไม่?");
      if (x)
          return true;
      else
        return false;
    }
    function radiovalue(id)
    {
        //alert(id);
        $('#doctor').val(id);
    }

</script>
<style>
    @import('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/css/bootstrap.min.css') 

.funkyradio div {
  clear: both;
  overflow: hidden;
}

.funkyradio label {
  width: 100%;
  border-radius: 3px;
  border: 1px solid #D1D3D4;
  font-weight: normal;
}

.funkyradio input[type="radio"]:empty,
.funkyradio input[type="checkbox"]:empty {
  display: none;
}

.funkyradio input[type="radio"]:empty ~ label,
.funkyradio input[type="checkbox"]:empty ~ label {
  position: relative;
  line-height: 2.5em;
  text-indent: 3.25em;
 
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.funkyradio input[type="radio"]:empty ~ label:before,
.funkyradio input[type="checkbox"]:empty ~ label:before {
  position: absolute;
  display: block;
  top: 0;
  bottom: 0;
  left: 0;
  content: '';
  width: 2.5em;
  background: #D1D3D4;
  border-radius: 3px 0 0 3px;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
  color: #888;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #C2C2C2;
}

.funkyradio input[type="radio"]:checked ~ label,
.funkyradio input[type="checkbox"]:checked ~ label {
  color: #000000;
  border:solid 1px #1E90FF;
  background-color:gainsboro;
}

.funkyradio input[type="radio"]:checked ~ label:before,
.funkyradio input[type="checkbox"]:checked ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #333;
  background-color: #ccc;
}

.funkyradio input[type="radio"]:focus ~ label:before,
.funkyradio input[type="checkbox"]:focus ~ label:before {
  box-shadow: 0 0 0 3px #999;
}

.funkyradio-default input[type="radio"]:checked ~ label:before,
.funkyradio-default input[type="checkbox"]:checked ~ label:before {
  color: #333;
  background-color: #ccc;
}

.funkyradio-primary input[type="radio"]:checked ~ label:before,
.funkyradio-primary input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #337ab7;
}

.funkyradio-success input[type="radio"]:checked ~ label:before,
.funkyradio-success input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5cb85c;
}

.funkyradio-danger input[type="radio"]:checked ~ label:before,
.funkyradio-danger input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #d9534f;
}

.funkyradio-warning input[type="radio"]:checked ~ label:before,
.funkyradio-warning input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #f0ad4e;
}

.funkyradio-info input[type="radio"]:checked ~ label:before,
.funkyradio-info input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: #5bc0de;
}

</style>    
</head>
<body>
<nav class="navbar navbar-info bg-info">
  <span class="text-white h5">
  <i class="fas fa-syringe"></i>&ensp;ระบบบันทึกการให้บริการวัคซีน
</span>
<i class="fas fa-list-alt text-white" onclick="window.open('vaccine_record_list.php','blank');" style="font-size: 30px; cursor: pointer;"></i>
</nav>
<form method="post" action="vaccine_record.php">    
<div class="p-3">
    <div class="h4">
        <?php echo $row_vaccine['vaccine_name']; ?>    
    </div>
    <div>
    <span class="h6">Lot No :</span><?php echo $row_vaccine['vaccine_lot_no']; ?>&ensp;<span class="h6">Expire Date : </span><?php echo $row_vaccine['vaccine_exp']; ?>
    </div>
    <div>
    <span class="h6">ขวดที่ : </span><span class="badge badge-light font-weight-bolder border border-secondary" style="font-size: 25px;"><?php echo $row_vaccine['vaccine_number']; ?></span>&ensp;<span class="h6">โดสที่ : </span><span class="badge badge-light font-weight-bolder border border-secondary " style="font-size: 25px;"><?php echo $row_vaccine['vaccine_dose_number']; ?></span>
    </div>
    <div>
        <span class="h6">Serial Number</span>
    <div class=" mt-1 text-center bg-light border border-secondary rounded p-2 h4 " >
        <span class="text-secondary" style="text-decoration: none;"><?php echo $row_vaccine['vaccine_sn']; ?></span>
    </div>
    <?php if($row_vaccine['vn']==""){ ?>
    <div class="mt-2">ผู้รับบริการ&emsp;<i class=" fas fa-star-of-life h6 text-danger"></i></div>
        <div class="mt-2">
            <select required data-live-search="true" class="form-control selectpicker" id="patient" name="patient" require data-size="10">
            <option value="">เลือกผู้รับบริการ</option>
            <?php do{ ?>
                <option value="<?php echo $row_pt['vn']; ?>"><?php echo $row_pt['ptname']; ?></option>
            <?php }while($row_pt = mysql_fetch_assoc($pt)); ?>
            </select>
            <div class="invalid-feedback">โปรดเลือกผู้รับบริการ</div>
        </div>
        <div class="mt-2">ผู้ฉีด&emsp;<i class=" fas fa-star-of-life h6 text-danger"></i></div>
        <div class="mt-2">
            <select required data-live-search="true" class="form-control selectpicker" id="doctor" name="doctor" require data-size="10">
            <option value="">เลือกผู้ฉีด</option>
            <?php do{ ?>
                <option value="<?php echo $row_doctor['code']; ?>"><?php echo $row_doctor['name']; ?></option>
            <?php }while($row_doctor = mysql_fetch_assoc($rs_doctor)); ?>
            </select>
            <div class="invalid-feedback">โปรดเลือกผู้ฉีด</div>
            <?php if($totalRows_doctor2<>0){ ?>
            <div class="mt-2 funkyradio text-secondary">    
            <?php $r=0;do{ $r++; ?>
                <div class="funkyradio-primary">
                <input type="radio" onClick="radiovalue(this.value);" id="doctor<?php echo $r; ?>" name="doctor2" value="<?php echo $row_doctor2['doctorcode']; ?>" /><label for="doctor<?php echo $r; ?>"><?php echo doctorname($row_doctor2['doctorcode']); ?></label>
            </div>
            <?php }while($row_doctor2 = mysql_fetch_assoc($rs_doctor2)); ?>
            </div>
   

            <?php } ?>

        </div>
        <div class="form-row mt-2">
              <div class="form-group">
              <label>&nbsp;ฉีดเข็มที่&emsp;<i class=" fas fa-star-of-life h6 text-danger"></i></label>
                <div class="pl-5">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="order_time1" name="order_time" class="custom-control-input" value="1" <?php if($row_vaccine['vaccine_order']=="1"){ echo "checked";  } ?> required>
                  <label class="custom-control-label" for="order_time1">1</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="order_time2" name="order_time" class="custom-control-input" <?php if($row_vaccine['vaccine_order']=="2"){ echo "checked";  } ?>  value="2" required>
                  <label class="custom-control-label" for="order_time2">2</label>
                </div>                
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" id="order_time3" name="order_time" class="custom-control-input" value="3" <?php if($row_vaccine['vaccine_order']=="3"){ echo "checked";  } ?>  required>
                  <label class="custom-control-label" for="order_time3">3 (กระตุ้น)</label>
                </div>                
                </div>
              </div>
        </div>  
        <div class="mt-2 form-row">
              <div class="form-group col-5">
              <label>วันที่ฉีด</label>
                  <input id="date" name="date" type="text" class="form-control" value="<?php echo date_db2th(date('Y-m-d')); ?>"  />
              </div>
              <div class="form-group col-3">
              <label>เวลาที่ฉีด</label>
                  <input id="time" name="time" type="text" class="form-control"  />
              </div>
              <div class="form-group col text-right">
                  <input type="submit" class="btn btn-success btn-lg" id="save" name="save" value="บันทึก" style="margin-top: 23px;"/>
              </div>              
        </div>  

        <?php } else { ?>
            
            <div class="mt-4 h3 text-primary text-center"><?php echo $row_vaccine['ptname']; ?></div>
            
            <div class="mt-2 h5 font-weight-bold text-center text-secondary">ผู้ให้บริการ : <?php echo doctorname($row_vaccine['doctorcode']); ?></div>
            <div class="mt-2 h6 text-center text-secondary">วัน เวลา ฉีด : <?php echo dateThai3($row_vaccine['vaccine_datetime']); ?></div>
            <div class="mt-2 h4 text-center text-secondary">เข็มที่ : <?php echo $row_vaccine['vaccine_order2']; ?></div>


            <div class="mt-5 text-center">
            <input type="submit" class="btn btn-danger btn-lg" id="delete" name="delete" value="ลบข้อมูล" Onclick="return ConfirmDelete();"/>
            </div>
                  
        <?php } ?>
    </div>
   

</div>
<input type="hidden" id="sn" name="sn" value="<?php echo $_GET['sn']; ?>" />
<input type="hidden" id="vaccnum" name="vaccnum" value="<?php echo $_GET['vaccnum']; ?>" />
<input type="hidden" id="vaccdn" name="vaccdn" value="<?php echo $_GET['vaccdn']; ?>" />
<input type="hidden" id="lot" name="lot" value="<?php echo $row_vaccine['vaccine_lot_no']; ?>" />
<input type="hidden" id="person_vaccine_id" name="person_vaccine_id" value="<?php echo $row_vaccine['person_vaccine_id']; ?>" />
<input type="hidden" id="vn" name="vn" value="<?php echo $row_vaccine['vn']; ?>" />

</form>
</body>
</html>
<?php mysql_free_result($vaccine); mysql_free_result($pt); mysql_free_result($rs_doctor); ?>