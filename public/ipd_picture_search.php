<?php require_once('Connections/hos.php'); ?>
<?php
mysql_select_db($database_hos, $hos);
$query_ward = "select * from ward where ward_active='Y'";
$rs_ward = mysql_query($query_ward, $hos) or die(mysql_error());
$row_ward = mysql_fetch_assoc($rs_ward);
$totalRows_rs_ward = mysql_num_rows($rs_ward);
?>
<?php include('include/function.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('bootstrap4.php'); ?>
    <link href="include/datepicker/css/datepicker.css" rel="stylesheet" media="screen">
    <link href="//getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js" ></script>
	
<script>
$(document).ready(function(){
        $('#panel-search').fadeOut( "slow" );
        $('#panel-order').fadeOut( "slow" );
    var Digital=new Date()
    var hours=Digital.getHours()
    var minutes=Digital.getMinutes()

			const timenow = Date().slice(16,21);
            var date = new Date();
            var addMinutes = 30;
            date.setTime(date.getTime() + (addMinutes * 60 * 1000));  
            var timeAdd = date.getHours() + ":" + date.getMinutes() ;
    
            $('#time1').val('00:00');
            $('#time2').val(timeAdd);

  
         $('#search-picture').click(function(){
            $('#indicator').show();	
            var dataString="action=search&date1="+$('#date1').val()+'&time1='+$('#time1').val()+'&date2='+$('#date2').val()+'&time2='+$('#time2').val()+'&ward='+$('#ward1').val()+'&checked='+$('#checked').val()+'&ordered='+$('#ordered').val()+'&orders_type='+$('#orders_type').val()+'&pt_type='+$('#pt_type').val();
             $.ajax({
				   type: "POST",
				   url: "ipd_picture_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#picture_list").html(html);
						$('#indicator').hide();	
					}
				 });
        }); 
    $('#btnNow').click(function(){
			const timenow = Date().slice(16,21);    
            $('#time2').val(timenow);        
    });
    $('#btn30').click(function(){
            var date = new Date();
            var addMinutes = 30;
            date.setTime(date.getTime() + (addMinutes * 60 * 1000));  
            var timeAdd = date.getHours() + ":" + date.getMinutes() ;
    
            $('#time2').val(timeAdd);
        
    });
});  

function order_save(medthod,id){
            
            $('#indicator').show();	
            var dataString="action=search&date1="+$('#date1').val()+'&time1='+$('#time1').val()+'&date2='+$('#date2').val()+'&time2='+$('#time2').val()+'&ward='+$('#ward1').val()+'&checked='+$('#checked').val()+'&ordered='+$('#ordered').val()+'&orders_type='+$('#orders_type').val()+'&order_save='+medthod+'&id='+id;
             $.ajax({
				   type: "POST",
				   url: "ipd_picture_list.php",
				   cache: false,
				   data: dataString,
				   success: function(html)
					{
						$("#picture_list").html(html);
						$('#indicator').hide();	
					}
				 });
    
}    
</script>
</head>

<body>
<div style="position: absolute; right: 15px; top:10px; font-size: 20px;">
    <button class="btn btn-sm btn-light" id="togger-list" onclick="window.location='ipd_admit.php<?php if(isset($_GET['pt_type'])&&($_GET['pt_type']!="")){ echo "?pt_type=".$_GET['pt_type']; } ?>'"><i class="fas fa-list-ol" style="font-size: 25px;"></i></button>
</div>    
    <div class="card">
        <div class="card-header"><span class="card-title font-weight-bold">ค้นหารูปภาพ</span></div>
    <div class="card-body">
		
        <div class="form-row" >
			<div class="form-group col-3">
                <label for="reportrange" class="col-form-label col-form-label-sm">Upload วันที่ </label>
                <input class="form-control form-control-sm datepicker" type="text" data-provide="datepicker" id="date1" name="date1" data-date-language="th-th" value="<?php echo date_db2th(date('Y-m-d')); ?>" style="width: 100px" />

            </div>   
                    <div class="form-group col-2">
                        <label>เวลา</label>
                          <input type="text" id="time1" name="time1" class="form-control form-control-sm" style="padding: 3px; width: 50px;" />  
                        
                    </div>
			<div class="form-group col-3">
                <label for="reportrange" class="col-form-label col-form-label-sm">ถึงวันที่ </label>
                <input class="form-control form-control-sm datepicker" type="text" data-provide="datepicker" id="date2" name="date2" data-date-language="th-th" value="<?php echo date_db2th(date('Y-m-d')); ?>" style="width: 100px" />

            </div>   
                    <div class="form-group col-2">
                        <label>เวลา</label>
                          <input type="text" id="time2" name="time2" class="form-control form-control-sm" style="padding: 3px; width: 50px;" />

                    </div> 
                    <div class="form-group col-2">
                        <label>time option</label>
                        <nobr>    
                          <button class="btn btn-success btn-sm" id="btnNow">Now</button>
                          <button class="btn btn-danger btn-sm" id="btn30">+30</button>
                        </nobr>
                    </div    
            
        </div>
        <div class="form-row" style="margin-top: -10px;">
                    <div class="form-group col-3">
						<label>ตึก</label>
						<select class="form-control form-control-sm" id="ward1" name="ward1" >
						<option value="">ทั้งหมด</option>
						<?php do{ ?>
						<option value="<?php echo $row_ward['ward']; ?>"><?php echo $row_ward['name']; ?></option>
						<?php }while($row_ward = mysql_fetch_assoc($rs_ward)); ?>
						</select>
					</div>
					<div class="form-group col-md-auto">
					<label>ประเภทผู้ป่วย</label>
					<select name="pt_type" id="pt_type" class="form-control form-control-sm">
						<option value="">ทั้งหมด</option>
						<option value="1" >Admit</option>
						<option value="2" >Continue</option>
						<option value="3" >Discharge</option>
					</select>	
					</div>				
                    <div class="form-group col-auto">
						<label>การบันทึก</label>
						<select class="form-control form-control-sm" id="checked" name="checked" >
						<option value="">ทั้งหมด</option>
						<option value="Y">บันทึกแล้ว</option>
						<option value="N" selected>ยังไม่บันทึก</option>
						</select>
					</div>  
                    <div class="form-group col-auto">
						<label>เรียงตาม</label>
						<select class="form-control form-control-sm" id="ordered" name="ordered" >
						<option value="an">AN</option>
						<option value="datetime" selected>วันเวลาบันทึก</option>
						</select>
					</div>              
                    <div class="form-group col-auto">
						<label>การเรียง</label>
						<select class="form-control form-control-sm" id="orders_type" name="orders_type" >
						<option value="ASC">A-Z</option>
						<option value="DESC" selected>Z-A</option>
						</select>
					</div>              
			
            <div class="form-group colcol-auto">
                <button class="btn btn-sm btn-info" style="margin-top: 32px;" id="search-picture" name="search-picture"><i class="fas fa-search" ></i>&nbsp;ค้นหา</button>
        	</div>          
            </div>
    </div>
    </div>  
    <div id="picture_list" class="mt-2" ></div>
<script src="//getbootstrap.com/2.3.2/assets/js/jquery.js"></script>

    <script src="include/datepicker/js/bootstrap-datepicker.js"></script>
    <script src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
    <script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>

    <script id="example_script"  type="text/javascript">
      function demo() {
        $('.datepicker').datepicker();
      }
    </script>    
</body>
</html>
<?php mysql_free_result($rs_ward); ?>