<?php require_once('../Connections/hos.php'); ?>
<?php
include('include/function.php');
include('../include/function.php');

$get_ip=$_SERVER["REMOTE_ADDR"];
// check การ login
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_login_check where last_time < CURDATE()";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

mysql_select_db($database_hos, $hos);
$query_channel = "SELECT channel_name,cursor_position,r.room_name,r.id as room_id,q.queue_list,q.kskdepart from ".$database_kohrx.".kohrx_queue_caller_channel q left outer join ".$database_kohrx.".kohrx_queue_caller_channel_name n on n.id=q.channel left outer join ".$database_kohrx.".kohrx_queue_caller_room r on r.id=q.room_id WHERE ip='".$get_ip."'";
$channel = mysql_query($query_channel, $hos) or die(mysql_error());
$row_channel = mysql_fetch_assoc($channel);
$totalRows_channel = mysql_num_rows($channel);

mysql_select_db($database_hos, $hos);
$query_rs_room = "SELECT * from ".$database_kohrx.".kohrx_queue_caller_room";
$rs_room = mysql_query($query_rs_room, $hos) or die(mysql_error());
$row_rs_room = mysql_fetch_assoc($rs_room);
$totalRows_rs_room = mysql_num_rows($rs_room);

$stamp= time('Hm');

mysql_select_db($database_hos, $hos);
$query_rs_error_type = "select * from ".$database_kohrx.".kohrx_med_error_error_type";
$rs_error_type = mysql_query($query_rs_error_type, $hos) or die(mysql_error());
$row_rs_error_type = mysql_fetch_assoc($rs_error_type);
$totalRows_rs_error_type = mysql_num_rows($rs_error_type);

$like1=iconv( 'UTF-8', 'TIS-620', "คิด");
mysql_select_db($database_hos, $hos);
$query_drug = "SELECT concat(drugitems.icode,'/',drugitems.did) as drugcode,concat(drugitems.name, drugitems.strength) as drugname FROM drugitems WHERE drugitems.name not like '%คิด%' ORDER BY drugitems.name ASC";
$drug = mysql_query($query_drug, $hos) or die(mysql_error());
$row_drug = mysql_fetch_assoc($drug);
$totalRows_drug = mysql_num_rows($drug);

//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));
mysql_free_result($rs_setting);

mysql_select_db($database_hos, $hos);
$query_department = "SELECT * FROM hospital_department ";
$department = mysql_query($query_department, $hos) or die(mysql_error());
$row_department = mysql_fetch_assoc($department);
$totalRows_department = mysql_num_rows($department);

mysql_select_db($database_hos, $hos);
$insert = "delete from ".$database_kohrx.".kohrx_med_error_report_drug where (rid is NULL or rid='0') and d_update < substr(NOW(),1,10) ";
$qinsert = mysql_query($insert,$hos)  or die (mysql_error());

if($_GET['do']=="edit"){
    mysql_select_db($database_hos, $hos);
    $query_rs_edit = "SELECT r.* FROM  ".$database_kohrx.".kohrx_med_error_report r where r.id='".$_GET['rid']."'";
    $rs_edit = mysql_query($query_rs_edit, $hos) or die(mysql_error());
    $row_rs_edit = mysql_fetch_assoc($rs_edit);
    $totalRows_rs_edit = mysql_num_rows($rs_edit);    
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript">
$(document).ready(function(){

Drugshow();
    
$('#detail').summernote({
  minHeight: 180,
  placeholder: 'พิมพ์ข้อความตรงนี้ ...',
  focus: false,
  airMode: false,
  fontNames: ['Roboto', 'Calibri', 'Times New Roman', 'Arial'],
  fontNamesIgnoreCheck: ['Roboto', 'Calibri'],
  dialogsInBody: true,
  dialogsFade: true,
  disableDragAndDrop: false,
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['style', 'ul', 'ol', 'paragraph']],
    ['fontsize', ['fontsize']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
	['color', ['color']],
	['table', ['table']],
    ['height', ['height']],
    ['misc', ['undo', 'redo', 'print', 'help', 'fullscreen']]
  ],
  popover: {
    air: [
      ['color', ['color']],
      ['font', ['bold', 'underline', 'clear']]
    ]
  },
  print: {
    //'stylesheetUrl': 'url_of_stylesheet_for_printing'
  }	});

$('#suggest').summernote({
  minHeight: 180,
  placeholder: 'พิมพ์ข้อความตรงนี้ ...',
  focus: false,
  airMode: false,
  fontNames: ['Roboto', 'Calibri', 'Times New Roman', 'Arial'],
  fontNamesIgnoreCheck: ['Roboto', 'Calibri'],
  dialogsInBody: true,
  dialogsFade: true,
  disableDragAndDrop: false,
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['style', 'ul', 'ol', 'paragraph']],
    ['fontsize', ['fontsize']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
	['color', ['color']],
	['table', ['table']],
    ['height', ['height']],
    ['misc', ['undo', 'redo', 'print', 'help', 'fullscreen']]
  ],
  popover: {
    air: [
      ['color', ['color']],
      ['font', ['bold', 'underline', 'clear']]
    ]
  },
  print: {
    //'stylesheetUrl': 'url_of_stylesheet_for_printing'
  }	});
    
//เลือกความคลาดเคลื่อนหลัก
$("#med_error_type").change(function()
    {
    var id=$(this).val();
    var dataString = 'id='+ id+'&type=main';
    $("#sub_id").val("");
    $.ajax
    ({
    type: "POST",
    url: "get_error_type.php",
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#cause_id").html(html);
    } 
    });
});
//===============//
//เลือกความคลาดเคลื่อนย่อย1
$("#cause_id").change(function()
    {
    var id=$(this).val();
    var dataString = 'id='+ id+'&type=sub';
    $.ajax
    ({
    type: "POST",
    url: "get_error_type.php",
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#sub_id").html(html);
    } 
    });
});
//===============//

//auto complete ชื่อเจ้าหน้าที่
        $( "#reporter_name" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "person_search.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#reporter").val(ui.item.id);
            }
        });
    
        $( "#person_error_name" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "person_search.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#person_error").val(ui.item.id);
            }
        });

        $( "#pharmacist_name" ).autocomplete({ // ใช้งาน autocomplete กับ input text id=tags
		
			minLength: 0, // กำหนดค่าสำหรับค้นหาอย่างน้อยเป็น 0 สำหรับใช้กับปุ่ใแสดงทั้งหมด
            source: "person_search.php", // กำหนดให้ใช้ค่าจากการค้นหาในฐานข้อมูล
            open:function(){
				 // เมื่อมีการแสดงรายการ autocomplete
                var valInput=$(this).val(); // ดึงค่าจาก text box id=tags มาเก็บที่ตัวแปร
                if(valInput!=""){ // ถ้าไม่ใช่ค่าว่าง
                    $(".ui-menu-item a").each(function(){ // วนลูปเรียกดูค่าทั้งหมดใน รายการ autocomplete
                        var matcher = new RegExp("("+valInput+")", "ig" ); // ตรวจสอบค่าที่ตรงกันในแต่ละรายการ กับคำค้นหา
                        var s=$(this).text();
                        var newText=s.replace(matcher, "<b>$1</b>");    //      แทนค่าที่ตรงกันเป็นตัวหนา
                        $(this).html(newText); // แสดงรายการ autocomplete หลังจากปรับรูปแบบแล้ว
                    }); 
                }
            },
            select: function( event, ui ) {
                $("#pharmacist").val(ui.item.id);
            }
        });    
    //ถ้าเอา mouse ออกจาก input HN
$('#hn').blur(function(){
        if($('#hn').val()==0){
            $('#hn').val("");
        }
        else if ($('#hn').val()!=""){
            $('#hn').val(leftPad($('#hn').val(),<?php echo $row_setting[24]; ?>));
        }
})

    // กรณีใช้แบบ input
    $("#date1").datetimepicker({
        timepicker:false,
        format:'d/m/Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear();  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $input.val(fulldateTH);
        },
    });       
    // กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
    $("#date1").on("mouseenter mouseleave",function(e){
        var dateValue=$(this).val();
        if(dateValue!=""){
                var arr_date=dateValue.split("/"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
                // ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
                //  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
                if(e.type=="mouseenter"){
                    var yearT=arr_date[2]-543;
                }       
                if(e.type=="mouseleave"){
                    var yearT=parseInt(arr_date[2])+543;
                }   
                dateValue=dateValue.replace(arr_date[2],yearT);
                $(this).val(dateValue);                                                 
        }       
    });

    
$("#time1").inputmask({"mask": "99:99"});
	
$('#add').click(function(){
	                $("#Drugadd").load('drug_add.php?stamp='+encodeURIComponent(<? echo $stamp; ?>)+'&do=insert&drug='+encodeURIComponent($('#drug').val())+'&id=<?php echo $_GET['rid']; ?>&drug_option='+$('#drug_option').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

	});	

    //บันทึกข้อมูล
    $('#save').click(function(){
                
        	       $("#result").load('result.php?do=save&date1='+encodeURIComponent($('#date1').val())+'&time1='+encodeURIComponent($('#time1').val())+'&reporter='+$('#reporter').val()+'&person_error='+$('#person_error').val()+'&hn='+$('#hn').val()+'&ptype='+$('#ptype').val()+'&reciew='+$('#reciew').val()+'&detail='+encodeURIComponent($('#detail').val())+'&dep_report='+$('#dep_report').val()+'&dep_error='+$('#dep_error').val()+'&category='+$('#category').val() +'&med_error_type='+encodeURIComponent($('#med_error_type').val())+'&cause_id='+encodeURIComponent($('#cause_id').val())+'&sub_id='+encodeURIComponent($('#sub_id').val())+'&note='+$('#note').val()+'&drugtype='+$('#drugtype').val()+'&suggest='+encodeURIComponent($('#suggest').val())+'&pharmacist='+$('#pharmacist').val()+'&stamp=<?php echo $stamp; ?>'+'&room_id='+$('#room').val(), function(responseTxt, statusTxt, xhr){
                   
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });    
    });

    //แก้ไขข้อมูล
    $('#edit').click(function(){
        	       $("#result").load('result.php?do=edit&id=<?php echo $_GET['rid']; ?>&date1='+encodeURIComponent($('#date1').val())+'&time1='+encodeURIComponent($('#time1').val())+'&reporter='+$('#reporter').val()+'&person_error='+$('#person_error').val()+'&hn='+$('#hn').val()+'&ptype='+$('#ptype').val()+'&reciew='+$('#reciew').val()+'&detail='+encodeURIComponent($('#detail').val())+'&dep_report='+$('#dep_report').val()+'&dep_error='+$('#dep_error').val()+'&category='+$('#category').val() +'&med_error_type='+encodeURIComponent($('#med_error_type').val())+'&cause_id='+encodeURIComponent($('#cause_id').val())+'&sub_id='+encodeURIComponent($('#sub_id').val())+'&note='+$('#note').val()+'&drugtype='+$('#drugtype').val()+'&suggest='+$('#suggest').val()+'&pharmacist='+$('#pharmacist').val()+'&stamp=<?php echo $stamp; ?>'+'&room_id='+$('#room').val(), function(responseTxt, statusTxt, xhr){
                   
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						   $('#medform').load('med_form.php', function(responseTxt, statusTxt, xhr){

								if(statusTxt == "success")
								  //alert("External content loaded successfully!");

								if(statusTxt == "error")
								  alert("Error: " + xhr.status + ": " + xhr.statusText);
						  	});    
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });    
    });
	
<?php if($_GET['do']=="edit"){ ?>
		// โหลดหน้ารายการยา
		 $("#Drugadd").load('drug_add.php?do=load&rid=<?php echo $_GET['rid']; ?>');    
						// finish โหลดหน้ารายการยา

<?php	} ?>	
});

</script>
<script>
//ใส่ 0 ข้างหน้าตัวเลข
function leftPad(value, length) { 
    return ('0'.repeat(length) + value).slice(-length); 
}

function Drugdelete(id){
		                $("#Drugadd").load('drug_add.php?stamp='+encodeURIComponent(<? echo $stamp; ?>)+'&do=delete&id='+id+'&rid=<?php echo $_GET['rid']; ?>', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}
function Drugshow(){
	                $("#Drugadd").load('drug_add.php?stamp='+encodeURIComponent(<? echo $stamp; ?>), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });

}

</script>
</head>
<body>
<form id="med_form" >
	<div class="p-2">
    <div class="card">
    <div class="card-header"><i class="fas fa-save font20"></i>&ensp;ข้อมูลทั่วไปและเหตุการณ์<input type="hidden" id="rid" name="rid" value=""/></div>
    <div class="card-body">
	<div class="form-group row">
		<label for="date1" class="col-form-label-sm col-sm-2">วันที่เกิด</label>
		<div class="col-sm-4">
			<input name="date1" type="text" id="date1" value="<?php if($_GET['do']=="edit"){ echo date_db2th($row_rs_edit['date']); } else{ echo date('d/m/').(date('Y')+543); } ?>" class="form-control form-control-sm" />
		</div>
		<label for="time1" class="col-form-label-sm col-sm-2">เวลที่เกิด</label>
		<div class="col-sm-4"><input type="text" name="time1" id="time1" value="<?php if($_GET['do']=="edit"){ echo substr($row_rs_edit['time'],0,5); } else{echo date('H:i');} ?>" class="form-control form-control-sm" /></div>
	</div>
	<div class="form-group row">
		<label for="reporter_name" class="col-form-label-sm col-sm-2">ผู้รายงาน</label>
		<div class="col-sm-4">
			<input name="reporter_name" type="text" id="reporter_name" class="form-control form-control-sm" value="<?php if($_GET['do']=="edit"){ echo  doctorname($row_rs_edit['reporter']); } ?>"  />
            <input type="hidden" name="reporter" id="reporter" value="<?php if($_GET['do']=="edit"){ echo $row_rs_edit['reporter']; } ?>" />
		</div>
		<label for="person_error_name" class="col-form-label-sm col-sm-2">ผู้คลาดเคลื่อน</label>
		<div class="col-sm-4">
            <input name="person_error_name" type="text" id="person_error_name" class="form-control form-control-sm" value="<?php if($_GET['do']=="edit"){ echo doctorname($row_rs_edit['error_person']); } ?>" />
            <input type="hidden" name="person_error" id="person_error" value="<?php if($_GET['do']=="edit"){ echo $row_rs_edit['error_person']; } ?>"/>

        </div>
	</div>

	<div class="form-group row">
		<label for="hn" class="col-form-label-sm col-sm-2">HN&nbsp;<i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-4">
			<input name="hn" type="text" id="hn" class="form-control form-control-sm" value="<?php if($_GET['do']=="edit"){ echo $row_rs_edit['hn']; } ?>" />
		</div>
		<label for="ptype" class="col-form-label-sm col-sm-2">ประเภทผู้ป่วย</label>
		<div class="col-sm-4"><select name="ptype" id="ptype" class="form-control form-control-sm">
                        <option value="opd" <?php if (!(strcmp('opd', $row_rs_edit['ptype']))) {echo "selected=\"selected\"";} ?>>OPD</option>
                        <option value="ipd" <?php if (!(strcmp('ipd', $row_rs_edit['ptype']))) {echo "selected=\"selected\"";} ?>>IPD</option>
                      </select></div>
	</div>

	<div class="form-group row">
		<label for="reciew" class="col-form-label-sm col-sm-2">การได้รับยา</label>
		<div class="col-sm-4">
			<select name="reciew" id="reciew" class="form-control form-control-sm">
                          <option value="N" <?php if (!(strcmp('N', $row_rs_edit['reciew']))) {echo "selected=\"selected\"";} ?>>ผู้ป่วยยังไม่ได้รับยา</option>
                          <option value="Y" <?php if (!(strcmp('Y', $row_rs_edit['reciew']))) {echo "selected=\"selected\"";} ?>>ผู้ป่วยรับยาแล้ว</option>
                        </select>
		</div>
	</div>
	<div class="form-group row">
		<label for="detail" class="col-form-label-sm col-sm-2">บรรยาย&nbsp;<i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-10">
			<textarea name="detail" cols="70" rows="3" id="detail" ><?php if($_GET['do']=="edit"){ echo $row_rs_edit['detail']; } ?></textarea>
		</div>
	</div>

	<div class="form-group row">
		<label for="dep_report" class="col-form-label-sm col-sm-3">หน่วยงานที่รายงาน</label>
		<div class="col-sm-3">
			<select name="dep_report" id="dep_report" class="form-control form-control-sm">
                <?php do {  ?>
                      <option value="<?php echo $row_department['id']?>" <?php if (!(strcmp($row_department['id'], $row_rs_edit['dep_report']))) {echo "selected=\"selected\"";} ?>><?php echo $row_department['name']?></option>
                <?php
} while ($row_department = mysql_fetch_assoc($department));
  $rows = mysql_num_rows($department);
  if($rows > 0) {
      mysql_data_seek($department, 0);
	 $row_department = mysql_fetch_assoc($department);
  }
?>
                    </select>
		</div>
		<label for="dep_error" class="col-form-label-sm col-sm-3">หน่วยงานที่คลาดเคลื่อน</label>
		<div class="col-sm-3"><select name="dep_error" class="form-control form-control-sm" id="dep_error">
                      <?php
do {  
?>
                      <option value="<?php echo $row_department['id']?>" <?php if (!(strcmp($row_department['id'], $row_rs_edit['dep_error']))) {echo "selected=\"selected\"";} ?>><?php echo $row_department['name']?></option>
                      <?php
} while ($row_department = mysql_fetch_assoc($department));
  $rows = mysql_num_rows($department);
  if($rows > 0) {
      mysql_data_seek($department, 0);
	  $row_department = mysql_fetch_assoc($department);
  }
?>
                    </select></div>
	</div>
	</div>
	</div>
    
    <div class="card mt-2">
    <div class="card-header">ประเภทและความรุนแรง</div>
    <div class="card-body">

	<div class="form-group row">
		<label for="category" class="col-form-label-sm col-sm-2">Category</label>
		<div class="col-sm-10">
			<select name="category"  id="category" class="form-control form-control-sm">
                            <option value="A" <?php if (!(strcmp('A', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>A : ไม่มีความคลาดเคลื่อนเกิดขึ้น แต่มีเหตุการณ์ที่อาจทำให้เกิดความคลาดเคลื่อนได้</option>
                            <option value="B" <?php if (!(strcmp('B', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>B : มีความคลาดเคลื่อนเกิขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย เนื่องจากความคลาดเคลื่อนไปไม่ถึงผู้ป่วย</option>
                            <option value="C" <?php if (!(strcmp('C', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>C : มีความคลาดเคลื่อนเกิดขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย ถึงแม้ว่าความคลาดเคลื่อนนั้นจะไปถึงผู้ป่วยแล้ว</option>
                            <option value="D" <?php if (!(strcmp('D', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>D : มีความคลาดเคลื่อนเกิดขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย แต่ยังจำเป็นต้องมีการติดตามผู้ป่วยเพิ่มเติม</option>
                            <option value="E" <?php if (!(strcmp('E', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>E : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยชั่วคราว รวมถึงจำเป็นต้องได้รับการรักษาหรือแก้ไขเพิ่มเติม</option>
                            <option value="F" <?php if (!(strcmp('F', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>F : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยเพียงชั่วคราวรวมถึงจำเป็นต้องได้รับการรักษาในโรงพยาบาล</option>
                            <option value="G" <?php if (!(strcmp('G', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>G : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยถาวร</option>
                            <option value="H" <?php if (!(strcmp('H', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>H : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยจนเกือบถึงแต่ชีวิต </option>
                            <option value="I" <?php if (!(strcmp('I', $row_rs_edit['category']))) {echo "selected=\"selected\"";} ?>>I : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอัตรายต่อผู้ป่วยจนถึงแก่ชีวิต</option>
                          </select>
		</div>
	</div>
        
	<div class="form-group row">
		<label for="med_error_type" class="col-form-label-sm col-sm-2">Error Type&nbsp;<i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-10">
        <select name="med_error_type" class="form-control form-control-sm" id="med_error_type" >
            <option value="">= เลือกประเภทความคลาดเคลื่อน =</option>
        <?php do { ?>
            <option value="<?php echo $row_rs_error_type['id']; ?>" <?php if (!(strcmp($row_rs_error_type['id'], $row_rs_edit['error_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_error_type['type_thai']; ?></option>
        <?php
            } while ($row_rs_error_type = mysql_fetch_assoc($rs_error_type));
              $rows = mysql_num_rows($rs_error_type);
              if($rows > 0) {
                  mysql_data_seek($rs_error_type, 0);
                 $row_rs_error_type = mysql_fetch_assoc($rs_error_type);
              }
        ?>
     
        </select>
		</div>
	</div>   
	<div class="form-group row">
		<label for="cause" class="col-form-label-sm col-sm-2 text-right"><i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-10">
            <select id="cause_id" name="cause_id" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 1 =</option>
            </select>
		</div>
	</div>  
	<div class="form-group row">
		<label for="sub" class="col-form-label-sm col-sm-2"></label>
		<div class="col-sm-10">
            <select id="sub_id" name="sub_id" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 2 =</option>
            </select>
		</div>
	</div> 
	<div class="form-group row">
		<label for="note" class="col-form-label-sm col-sm-2">อื่นๆ ระบุ</label>
		<div class="col-sm-10">
        <input name="note" type="text" class="form-control form-control-sm" id="note" size="50" value="<?php if($_GET['do']=="edit"){ echo $row_rs_edit['error_other']; } ?>" />
		</div>
	</div> 
  </div>      
  </div>  

    <div class="card mt-2">
    <div class="card-header">ยาที่เกี่ยวข้อง</div>
    <div class="card-body">
	<div class="form-group row">
		<label for="drugtype" class="col-form-label-sm col-sm-2">ประเภทยา</label>
		<div class="col-sm-4">
            <select name="drugtype" id="drugtype" class="form-control form-control-sm">
                        <option value="NM" <?php if (!(strcmp('HAD', $row_rs_edit['drugtype']))) {echo "selected=\"selected\"";} ?>>ยาทั่วไป</option>
						<option value="HAD" <?php if (!(strcmp('HAD', $row_rs_edit['drugtype']))) {echo "selected=\"selected\"";} ?>>Hight Alert Drug</option>

            </select>		
        </div>
    </div>
    	<div class="form-group row">

		<label for="drug" class="col-form-label-sm col-sm-2">รายการยา</label>
		<div class="col-sm-6">
            <select name="drug" class="form-control form-control-sm" id="drug">
            <?php do {  ?>
                <option value="<?php echo $row_drug['drugcode']?>"><?php echo $row_drug['drugname']?></option>
            <?php
            } while ($row_drug = mysql_fetch_assoc($drug));
              $rows = mysql_num_rows($drug);
              if($rows > 0) {
                  mysql_data_seek($drug, 0);
                  $row_drug = mysql_fetch_assoc($drug);
              }
            ?>
            </select>		
	    </div> 
		<div class="col-sm-2">
                  <select name="drug_option" class="form-control form-control-sm" id="drug_option">
                    <option value="1">ยาตัวที่ถูก</option>
                    <option value="2">ยาตัวที่ผิด</option>
                    <option value="3" selected="selected">ยาที่เกี่ยวข้อง</option>
                    </select>	
			        <input type="hidden" name="id" id="id" />

    </div>
		<div class="col-sm-2">
            <input type="button" class="btn btn-secondary btn-sm" id="add" name="add" value="เพิ่มรายการยา" />
        </div>
    </div>
        <div id="Drugadd"></div>    
    </div>
    </div>
    <!-- card drug -->
    <div class="card mt-2">
    <div class="card-header">แนวทางแก้ไขและอื่นๆ</div>
    <div class="card-body">
	<div class="form-group row">
		<label for="suggest" class="col-form-label-sm col-sm-2">การแก้ไข</label>
		<div class="col-sm-10">
			<textarea name="suggest" cols="70" rows="3" id="suggest"><?php if($_GET['do']=="edit"){ echo $row_rs_edit['suggest']; } ?></textarea>
		</div>
    </div>
	<div class="form-group row">
		<label for="pharmacist" class="col-form-label-sm col-sm-3">ผู้ตรวจสอบ/บันทึก&nbsp;<i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-4">
            <input type="text" name="pharmacist_name" id="pharmacist_name" class="form-control form-control-sm" value="<?php if($_GET['do']=="edit"){ echo  doctorname($row_rs_edit['pharmacist']); } ?>"/>
            <input type="hidden" name="pharmacist" id="pharmacist" class="form-control form-control-sm" value="<?php if($_GET['do']=="edit"){ echo $row_rs_edit['pharmacist']; } ?>"/>
        </div>


    </div>
	<div class="form-group row">
		<label for="pharmacist" class="col-form-label-sm col-sm-3">จุดจ่ายที่พบอุบัติการณ์&nbsp;<i class="fas fa-star-of-life text-danger"></i></label>
		<div class="col-sm-4">
            <select name="room" id="room" class="form-control form-control-sm">
            <?php do {  ?>
                    <option value="<?php echo $row_rs_room['id']?>"<?php if($row_rs_edit['room_id']!=""){ if (!(strcmp($row_rs_room['id'], $row_rs_edit['room_id']))) {echo "selected=\"selected\"";}} else {if (!(strcmp($row_rs_room['id'], $row_channel['room_id']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_room['room_name']?></option>
                    <?php
            } while ($row_rs_room = mysql_fetch_assoc($rs_room));
              $rows = mysql_num_rows($rs_room);
              if($rows > 0) {
                  mysql_data_seek($rs_room, 0);
                  $row_rs_room = mysql_fetch_assoc($rs_room);
              }
            ?>
            </select>
        </div>
		<div class="col-sm-4">
			<?php if(!isset($_GET['do'])){ ?>
            <input type="button" name="save" id="save" class="btn btn-primary btn-sm"  value="บันทึก"; /><?php } ?>
			<?php if($_GET['do']=="edit"){ ?>
            <input type="button" name="edit" id="edit" class="btn btn-success btn-sm"  value="แก้ไข"; />
			<?php } ?>	
    </div>
    </div>
    </div>        
    </div>

</div>	
</form>
</body>
</html>
<?php 
mysql_free_result($rs_room);
mysql_free_result($channel);
mysql_free_result($rs_error_type);
mysql_free_result($department);

?>