<?php require_once('Connections/hos.php'); ?>
<?php 
        include('include/function.php');
        
        if(isset($_POST['search'])&&($_POST['search']=="ค้นหา")){
          if($_POST['zone']!=""){
            $condition =" and concat(pt.chwpart,pt.amppart,pt.tmbpart,TRIM(LEADING '0' FROM pt.moopart)) in (select concat(chwpart,amppart,tmbpart,moopart) from hospcode_cup where hospcode='".$_POST['zone']."')";
          }
          if($_POST['vaccine']!=""){
            $condition.=" and k.person_vaccine_id = '".$_POST['vaccine']."'";
            $con_vacc=" and person_vaccine_id = '".$_POST['vaccine']."'";
          }
          if($_POST['pttype']==1){
            $condition.="";
          }
          else if($_POST['pttype']==2){
            $condition.=" and k.vn in (select vn from ovst_vaccine where immunization_datetime between '".date_th2db($_POST['date1'])." ".$_POST['time1'].":00' and '".date_th2db($_POST['date2'])." ".$_POST['time2'].":00' ".$con_vacc." )";
          }
          else if($_POST['pttype']==3){
            $condition.=" and k.vn not in (select vn from ovst_vaccine where immunization_datetime between '".date_th2db($_POST['date1'])." ".$_POST['time1'].":00' and '".date_th2db($_POST['date2'])." ".$_POST['time2'].":00' ".$con_vacc." )";
          }          

          mysql_select_db($database_hos, $hos);
          $query_rs_report = "SELECT
          k.vaccine_datetime,k.vn,pt.hn,concat( `pt`.`pname`, `pt`.`fname`, ' ', `pt`.`lname` ) AS `ptname`,pv.`vaccine_name`,
          k.`vaccine_lot_no`,k.`vaccine_number`,k.`vaccine_dose_number`,
          kv.`vaccine_sn`,k.vaccine_order 
          FROM dispensing.`kohrx_person_vaccine_record` k LEFT JOIN vn_stat v ON v.vn = k.vn
            LEFT JOIN patient pt ON pt.hn=v.hn 
            LEFT JOIN person_vaccine pv ON pv.`person_vaccine_id` = k.`person_vaccine_id` 
            LEFT JOIN dispensing.`kohrx_vaccine_record` kv ON kv.`person_vaccine_id` = pv.`person_vaccine_id` AND kv.`vaccine_number` = k.`vaccine_number`   AND kv.`vaccine_dose_number` = k.`vaccine_dose_number` 
          WHERE k.vaccine_datetime between '".date_th2db($_POST['date1'])." ".$_POST['time1'].":00' and '".date_th2db($_POST['date2'])." ".$_POST['time2'].":00' ".$condition." group by k.vn ";
            //echo $query_rs_report;
          $rs_report = mysql_query($query_rs_report, $hos) or die(mysql_error());
          $row_rs_report = mysql_fetch_assoc($rs_report);
          $totalRows_rs_report = mysql_num_rows($rs_report);   
  
        }
        if($_POST['date1']!=""){ $date1=$_POST['date1']; } 
        else{ $date1=date('d/m/').(date('Y')+543); }
        if($_POST['date2']!=""){ $date2=$_POST['date2']; } 
        else{ $date2=date('d/m/').(date('Y')+543); } 

        mysql_select_db($database_hos, $hos);
        $query_rs_vaccine = "select * from dispensing.kohrx_person_vaccine_record";
        $rs_vaccine = mysql_query($query_rs_vaccine, $hos) or die(mysql_error());
        $row_rs_vaccine = mysql_fetch_assoc($rs_vaccine);
        $totalRows_rs_vaccine = mysql_num_rows($rs_vaccine);   

        mysql_select_db($database_hos, $hos);
        $query_rs_hoszone = "select hospcode,name from hospcode_cup group by hospcode";
        $rs_hoszone = mysql_query($query_rs_hoszone, $hos) or die(mysql_error());
        $row_rs_hoszone = mysql_fetch_assoc($rs_hoszone);
        $totalRows_rs_hoszone = mysql_num_rows($rs_hoszone);   

        mysql_select_db($database_hos, $hos);
        $query_rs_vacc = "select person_vaccine_id,vaccine_name from person_vaccine where person_vaccine_id in ('67','68','76','84')";
        $rs_vacc = mysql_query($query_rs_vacc, $hos) or die(mysql_error());
        $row_rs_vacc = mysql_fetch_assoc($rs_vacc);
        $totalRows_rs_vacc = mysql_num_rows($rs_vacc);   
        
        include('include/function_sql.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>รายชื่อผู้มารับบริการวัคซีน โรงพยาบาลมหาชนะชัย</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('java_css_file.php'); ?>
	<!-- container-fluid -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>    
<link rel="stylesheet" type="text/css" href="include/datepicker/css/datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>   

<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    
 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap5.min.css"/>
<style>
 html,body { height:100%; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
} 
html,body{
    overflow: hidden
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
    $("#date1").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });
    $("#date2").datepicker( {
    format: "dd/mm/yyyy",
    startView: "days", 
    minViewMode: "days"
    });    

});
</script>
<?php include('include/bootstrap/datatable_report.php'); ?>

<script>
  $(document).ready(function(){
    var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()

			const timenow = Date().slice(16,21);
        <?php if($_POST['time1']!=""){ ?> 
          $('#time1').val('<?php echo $_POST['time1']; ?>');
        <?php } else { ?>
          $('#time1').val('00:00');
        <?php } ?>
        <?php if($_POST['time2']!=""){ ?> 
          $('#time2').val('<?php echo $_POST['time2']; ?>');
        <?php } else {?>
          $('#time2').val(timenow);
        <?php }?>        
                      
            $('#date1').val('<?php echo $date1; ?>');
            $('#date2').val('<?php echo $date2; ?>');
        
            $('#save').prop('disabled',true);
			$("#time1").inputmask({"mask": "99:99"});
			$("#time2").inputmask({"mask": "99:99"});
            $("#time1").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $("#time2").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $('#time1').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });

  });

</script>
</head>
<body>
<nav class="navbar navbar-info" style="background-color: #7C8BFF;">
  <span class="text-white h5">
  <i class="fas fa-syringe"></i>&ensp;ค้นหารายชื่อผู้มารับบริการวัคซีน
</span>
</nav>
<form name="form1" method="post" action="vaccine_record_list.php">
 <div class="p-2" style="background-color: #EFEDE7;">
                <div class="form-row">
                    <div class="form-group col-auto" style="width: 120px;">
                        <label>จากวันที่</label>
                        <input type="text" class="form-control form-control-sm" name="date1" id="date1" data-date-language="th-th"  />
                    </div>    
                    <div class="form-group col-auto" style="width: 80px;">
                        <label>เวลาฉีด</label>
                          <input type="text" id="time1" name="time1" class="form-control form-control-sm" style="padding: 3px;" />  
                        
                    </div>  
                    <div class="form-group col-auto" style="width: 120px;">
                        <label>ถึงวันที่</label>
                        <input type="text" class="form-control form-control-sm" name="date2" id="date2" data-date-language="th-th" />
                    </div>    
                    <div class="form-group col-auto" style="width: 80px;">
                        <label>เวลา</label>
                          <input type="text" id="time2" name="time2" class="form-control form-control-sm" style="padding: 3px;" />  
                        
                    </div>              
                    <div class="form-group col-auto">
                    <label>ชื่อวัคซีน</label>
                    <select class="form-control form-control-sm" id="vaccine" name="vaccine">
                          <option value="">ทั้งหมด</option>
                          <?php do{ ?>
                          <option value="<?php echo $row_rs_vacc['person_vaccine_id']; ?>" <?php if($_POST['vaccine']==$row_rs_vacc['person_vaccine_id']){ echo "selected"; } ?>><?php echo $row_rs_vacc['vaccine_name']; ?></option>
                          <?php }while($row_rs_vacc = mysql_fetch_assoc($rs_vacc)); ?>
                        </select> 
                    </div>
                    <div class="form-group col-auto">
                        <label>เขตรับผิดชอบ</label>
                        <select class="form-control form-control-sm" id="zone" name="zone">
                          <option value="">ทั้งหมด</option>
                          <?php do{ ?>
                          <option value="<?php echo $row_rs_hoszone['hospcode']; ?>" <?php if($_POST['zone']==$row_rs_hoszone['hospcode']){ echo "selected"; } ?>><?php echo $row_rs_hoszone['name']; ?></option>
                          <?php }while($row_rs_hoszone = mysql_fetch_assoc($rs_hoszone)); ?>
                        </select> 

                    </div>                       
                    <div class="form-group col-auto">
                        <label>ประเภทรายชื่อ</label>
                        <select class="form-control form-control-sm" id="pttype" name="pttype">
                          <option value="1" <?php if($_POST['pttype']=="1"){ echo "selected"; } ?>>ผู้มารับบริการทั้งหมด</option>
                          <option value="2" <?php if($_POST['pttype']=="2"){ echo "selected"; } ?>>บันทึก hosxp แล้ว</option>
                          <option value="3" <?php if($_POST['pttype']=="3"){ echo "selected"; } ?>>ยังไม่บันทึก hosxp</option>
                        </select> 
                         
                    </div>   
                    <div class="form-group col-auto">
                      <input class="btn btn-danger btn-sm" name="search" id="search" value="ค้นหา" type="submit" style="margin-top: 32px;"/>
                    </div>
                                        

                </div> 
 </div>
 </form>

<div class="p-3" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:83vh; margin-top: -16px">
<?php if ($totalRows_rs_report > 0) { // Show if recordset not empty ?>
<table id="example" class="table table-hover table-sm display " style="width:100%; font-size:12px">
<thead>  
<tr >
    <td height="24" align="center" class="rounded_top_left">ลำดับ</td>
    <td align="center">วันและเวลาฉีด</td>
    <td align="center">HN</td>
    <td align="center">ชื่อผู้รับบริการ</td>
    <td align="center">วัคซีน</td>
    <td align="center" >ขวด/โดสที่</td>
    <td align="center" ><nobr>Lot No</nobr></td>
    <td align="center" >serial number</td>
    <td align="center" class="rounded_top_right">เข็มที่</td>
  </tr>
</thead>
<tbody>
  <?php $i=0; do { $i++;   ?>
	<tr >
    <td align="center" ><?php echo $i; ?></td>
    <td align="center" ><?php print dateThai3($row_rs_report['vaccine_datetime']); ?></td>
    <td align="center" ><?php print $row_rs_report['hn']; ?></td>
    <td align="center" ><?php print $row_rs_report['ptname']; ?></td>
    <td align="left" ><?php print $row_rs_report['vaccine_name']; ?></td>
    <td align="center" ><?php print $row_rs_report['vaccine_number']."/".$row_rs_report['vaccine_dose_number']; ?></td>
    <td align="center" ><?php print $row_rs_report['vaccine_lot_no']; ?></td>
    <td align="center" ><?php print $row_rs_report['vaccine_sn']; ?></td>
    <td align="center" ><?php print $row_rs_report['vaccine_order']; ?></td>
  </tr><?php } while($row_rs_report = mysql_fetch_assoc($rs_report)); ?>
	</tbody>
	</table>
  <?php } else { echo nodata(); } // Show if recordset not empty ?> 
</div>

</body>
</html>
<?php mysql_free_result($rs_vaccine); mysql_free_result($rs_hoszone);mysql_free_result($rs_vacc);  ?>