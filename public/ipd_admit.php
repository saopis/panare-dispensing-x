<?php require_once('Connections/hos.php'); ?>
<?php
mysql_select_db($database_hos, $hos);
$query_ward = "select * from ward where ward_active='Y'";
$rs_ward = mysql_query($query_ward, $hos) or die(mysql_error());
$row_ward = mysql_fetch_assoc($rs_ward);
$totalRows_rs_ward = mysql_num_rows($rs_ward);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Doctor Order Online</title>
<?php include('bootstrap4.php'); ?>

    <style>
   
    </style>
<script>
    $(document).ready(function () {
        $('#indicator').show();	
        $('.checkdate').hide();
		//when click search
			//var dataString="datepicker1="+$('#datepicker').val()+"&datepicker2="+$('#datepicker2').val();		
            $.ajax({
				   type: "POST",
				   url: "ipd_admit_list.php",
				   cache: false,
				   data: {pt_type:'<?php echo $_GET['pt_type']; ?>'},
				   success: function(html)
					{
						$('#indicator').hide();	
                        
                        $("#result").html(html);

					}
				 });

	
		
	});
</script>
<style>
	html,body{overflow-x: hidden;}
</style>    
</head>

<body>
<nav class="navbar navbar-light bg-light text-center">
  <a class="navbar-brand text-center" href="#">
	<center><i class="fas fa-procedures" style="font-size: 20px"></i>
	  รายชื่อผู้ป่วย admit ปัจจุบัน
    </center>    
  </a>
</nav>
<div class="mt-2" id="tools" style="padding-left: 15px; padding-right: 15px; display: none">
<div class="card" id="panel-search">
    <div class="card-body">
        <button type="button" class="close" id="close-search" style="position: absolute;right: 5px; top: 0px;">&times;</button>        
        <div class="form-group" style="margin-left: -15px;" >
            <div class="col-auto">
                <label for="reportrange" class="control-label"><input type="checkbox" id="datecheck" name="datecheck"/>&ensp;เลือกวันที่ Admit/Discharge</label>
			</div>
			<div class="col-auto checkdate">
				<select id="pttype" name="pttype" class="form-control form-control-sm">
					<option value="ad">ผู้ป่วยกำลังนอน</option>					
					<option value="dc">ผู้ป่วยถูกจำหน่ายแล้ว</option>
				</select>
			</div>            
            <div class="col-auto mt-2  checkdate">
                <div id="reportrange" class="form-contol" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                    <span></span> 
                  <i class="far fa-calendar-check" style="font-size: 20px"></i>
                </div>			
		
        	</div>

            </div>

		
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />

	<div class="row">
			<div class="col-auto"><label for="pttype" class="control-label">ตึกผู้ป่วย</label></div>
            
 			<div class="col-auto">
                <select class="form-control form-control-sm" id="ward" name="ward" >
                <option value="">ทั้งหมด</option>
                <?php do{ ?>
                <option value="<?php echo $row_ward['ward']; ?>"><?php echo $row_ward['name']; ?></option>
                <?php }while($row_ward = mysql_fetch_assoc($rs_ward)); ?>
                </select></div>
        <div class="col-auto"><label for="hnan" class="control-label">ค้นหา HN/AN</label></div>      
        <div class="col-auto">
            <input type="text" class="form-control form-control-sm" id="hnan" name="hnan" placeholder="HN/AN"/>
        </div>
		<label class="col-form-label col-auto col-form-label-sm" >เรียงตาม</label>
			<div class="col-auto">
				<select class="form-control form-control-sm" id="order_type2" name="order_type2" >
                    <option value="1">AN</option>
                    <option value="2">เตียง</option>
                </select>
		</div>          
			<div class="col-auto"><button class="btn btn-sm btn-info" id="search-button" name="search-button"><i class="fas fa-search"></i>&nbsp;ค้นหา</button></div>        

	</div>
    </div>
    </div>
<div class="card mt-2" id="panel-order">
    <div class="card-body">
    <button type="button" class="close" id="close-order" style="position: absolute;right: 5px; top: 0px;">&times;</button>            
	<div class="row pl-2">
		<label class="col-form-label col-auto col-form-label-sm" >เรียงตาม</label>
			<div class="col-auto">
				<select class="form-control form-control-sm" id="order_type" name="order_type" style="width:80px">
                    <option value="1">AN</option>
                    <option value="2">เตียง</option>
                </select>
		</div>
	</div>        
    </div>
</div>    
</div>
<div class="p-2" id="result"></div>	
<center>
<button class="btn btn-primary position-fixed" id="indicator" style="position: absolute; top: 50%;   left: 50%;
  margin-left: -4em;" disabled>
  <span class="spinner-border spinner-border-sm" style="font-size: 20px;"></span>&ensp;กรุณารอสักครู่..
</button>
</center> 

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="bootstrap4/js/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    
<script type="text/javascript">
$(function() {

    var start = moment().subtract(7, 'days');
    var end = moment().subtract(0, 'days');

    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		$('#datestart').val(start.format('Y-MM-DD'));
		$('#dateend').val(end.format('Y-MM-DD'));

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
		lang:'th',
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'ย้อนหลัง 7 วัน': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
           '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		   'ปีงบประมาณนี้':[moment([new Date().getFullYear(), 9, 01]).subtract(1,'year'),moment([new Date().getFullYear(), 8, 30])],
		   'ปีงบประมาณก่อน':[moment([new Date().getFullYear(), 9, 01]).subtract(2,'year'),moment([new Date().getFullYear(), 8, 30]).subtract(1,'year')],
        }
    }, cb);
	
    cb(start, end);
	

});
</script>
    
</body>
</html>
<?php mysql_free_result($rs_ward); ?>