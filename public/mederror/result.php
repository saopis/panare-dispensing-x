<?php require_once('../Connections/hos.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


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

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<? if($_GET['do']=="save"){
   /* date1'+encodeURIComponent($('#date1').val())+'&time1='+encodeURIComponent($('#time1').val())+'&reporter='+$('#reporter').val()+'&person_error='+$('#person_error').val()+'&hn='+$('#hn').val()+'&ptype='+$('#ptype').val()+'&reciew='+$('#reciew').val()+'&detail='+encodeURIComponent($('#detail').val())+'&dep_report='+$('#dep_report').val()+'&dep_error='+$('#dep_error').val()+'&category='+$('#category').val()+'&med_error_type='+$('#med_error_type').val()+'&cause_id='+$('#cause_id').val()+'&sub_id='+$('#sub_id').val()+'&note='+$('#note').val()+'&drugtype='+$('#drugtype').val()+'&suggess='+$('#suggest').val()+'&pharmacist='+$('#pharmacist').val()
   */
		 $date1=date_th2db($_GET['date1']);
		 $time1=$_GET['time1'];
		 $reporter=$_GET['reporter'];
		 $person_error=$_GET['person_error'];
		 $hn=$_GET['hn'];
		 $ptype=$_GET['ptype'];
		 $reciew=$_GET['reciew'];
		 $detail=$_GET['detail'];
		 $dep_report=$_GET['dep_report'];
		 $dep_error=$_GET['dep_error'];
		 $category=$_GET['category'];
		 $med_error_type=$_GET['med_error_type'];
		 $cause_id=$_GET['cause_id'];
		 $sub_id=$_GET['sub_id'];    
         $note=$_GET['note'];
         $drugtype=$_GET['drugtype'];
		 $suggest=$_GET['suggest'];
		 $pharmacist=$_GET['pharmacist'];
         $stamp=$_GET['stamp'];
         $room_id=$_GET['room_id'];
        
         $stop="";

        if($_GET['hn']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณากรอก HN ผู็ป่วย</div>";
            $stop="Y";
        }
        else if($_GET['detail']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาบรรยายเหตุการ</div>";
            $stop="Y";
        }
        else if($_GET['med_error_type']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาเลือกประเภทความคลาดเคลื่อน</div>";
            $stop="Y";
        }
        else if($_GET['cause_id']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาเลือกประเภทความคลาดเคลื่อนย่อย 1</div>";
            $stop="Y";
        }
        else if($_GET['pharmacist']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณากรอกชื่อผู้บันทึก</div>";
            $stop="Y";
        }
        
    /*
        echo "<script>document.getElementById('med_form').reset();</script>";
    */    
    if($stop==""){
            
		if(isset($other)){
		  $cause = $other.$med_error_type;
		}
		mysql_select_db($database_hos, $hos);
		$insert = "insert into ".$database_kohrx.".kohrx_med_error_report (reporter,date,time,detail,hn,dep_report,dep_error,category,suggest,reciew,drugtype,pharmacist,ptype,error_person,error_type,error_cause,error_subtype,error_other,room_id) VALUES ('".$reporter."','".$date1."','".$time1."','".$detail."','".$hn."','".$dep_report."','".$dep_error."','".$category."','".$suggest."','".$reciew."','".$drugtype."','".$pharmacist."','".$ptype."','".$person_error."','".$med_error_type."','".$cause_id."','".$sub_id."','".$note."','".$room_id."')";
        
		$insert_com=mysql_query($insert,$hos) or die (mysql_error());;

		mysql_select_db($database_hos, $hos);		
		$q_id = "select id from ".$database_kohrx.".kohrx_med_error_report order by id DESC LIMIT 1";
		$r_id =mysql_query($q_id,$hos) or die (mysql_error());
		$row_id = mysql_fetch_assoc($r_id);

		//insert drug
		mysql_select_db($database_hos, $hos);
		$drug = "update ".$database_kohrx.".kohrx_med_error_report_drug set rid='".$row_id['id']."' where stamp='".$stamp."'";
		$qdrug=mysql_query($drug,$hos) or die(mysql_error());
						
			
		if($insert_com){ echo "<script>document.getElementById('med_form').reset();resultSearch();</script>"; }		 
        }
    }

 if($_GET['do']=="edit"){
		 $date1=date_th2db($_GET['date1']);
		 $time1=$_GET['time1'];
		 $reporter=$_GET['reporter'];
		 $person_error=$_GET['person_error'];
		 $hn=$_GET['hn'];
		 $ptype=$_GET['ptype'];
		 $reciew=$_GET['reciew'];
		 $detail=$_GET['detail'];
		 $dep_report=$_GET['dep_report'];
		 $dep_error=$_GET['dep_error'];
		 $category=$_GET['category'];
		 $med_error_type=$_GET['med_error_type'];
		 $cause_id=$_GET['cause_id'];
		 $sub_id=$_GET['sub_id'];    
         $note=$_GET['note'];
         $drugtype=$_GET['drugtype'];
		 $suggest=$_GET['suggest'];
		 $pharmacist=$_GET['pharmacist'];
         $stamp=$_GET['stamp'];
         $room_id=$_GET['room_id']; 
        
         $stop="";

        if($_GET['hn']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณากรอก HN ผู็ป่วย</div>";
            $stop="Y";
        }
        else if($_GET['detail']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาบรรยายเหตุการ</div>";
            $stop="Y";
        }
        else if($_GET['med_error_type']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาเลือกประเภทความคลาดเคลื่อน</div>";
            $stop="Y";
        }
        else if($_GET['cause_id']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณาเลือกประเภทความคลาดเคลื่อนย่อย 1</div>";
            $stop="Y";
        }
        else if($_GET['pharmacist']==""){
            echo "<div class=\"alert alert-primary\" role=\"alert\">กรุณากรอกชื่อผู้บันทึก</div>";
            $stop="Y";
        }
        
    /*
        echo "<script>document.getElementById('med_form').reset();</script>";
    */    
    if($stop==""){
            
		if(isset($other)){
		  $cause = $other.$med_error_type;
		}
		mysql_select_db($database_hos, $hos);
		$insert = "
		update ".$database_kohrx.".kohrx_med_error_report set reporter='".$reporter."',date='".$date1."',time='".$time1."',detail='".$detail."',hn='".$hn."',dep_report='".$dep_report."',dep_error='".$dep_error."',category='".$category."',suggest='".$suggest."',reciew='".$reciew."',drugtype='".$drugtype."',pharmacist='".$pharmacist."',ptype='".$ptype."',error_person='".$person_error."',error_type='".$med_error_type."',error_cause='".$cause_id."',error_subtype='".$sub_id."',error_other='".$note."',room_id='".$room_id."' where id='".$_GET['id']."'";
		$insert_com=mysql_query($insert,$hos) or die (mysql_error());;
						
			
		if($insert_com){ echo "<script>resultSearch();</script>"; }		 
        }
    }
// แก้ไข

///////////////////////// จบการ แก้ไข

///// เริ่มลบ
if($_POST['do']=="delete"){
	mysql_select_db($database_mederror, $mederror);
	$delete = "delete from med_error_report where id='$id'";
	$qdelete=mysql_query($delete,$mederror) or die(mysql_error());
	mysql_select_db($database_mederror, $mederror);
	$delete1 = "delete from med_error_report_drug where rid='$id'";
	$qdelete1=mysql_query($delete1,$mederror) or die(mysql_error());

}



mysql_select_db($database_hos, $hos);
$query_rs_error_type = "select * from ".$database_kohrx.".kohrx_med_error_error_type";
$rs_error_type = mysql_query($query_rs_error_type, $hos) or die(mysql_error());
$row_rs_error_type = mysql_fetch_assoc($rs_error_type);
$totalRows_rs_error_type = mysql_num_rows($rs_error_type);

?>
<!doctype html>
<html>
<head>
	
<meta charset="utf-8">
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script type="text/javascript" src="../include/datepicker/js/moment.min.js"></script>
<script type="text/javascript" src="../include/datepicker/js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../include/datepicker/css/daterangepicker.css" />
<script type="text/javascript">
$(function() {
    
	$('#indicator2').hide();

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
	

//เลือกความคลาดเคลื่อนหลัก
$("#med_error_type1").change(function()
    {
    var id=$(this).val();
    var dataString = 'id='+ id+'&type=main';
    $("#sub_id1").val("");
    $.ajax
    ({
    type: "POST",
    url: "get_error_type.php",
    data: dataString,
    cache: false,
    success: function(html)
    {
    $("#cause_id1").html(html);
    } 
    });
});
//===============//
//เลือกความคลาดเคลื่อนย่อย1
$("#cause_id1").change(function()
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
    $("#sub_id1").html(html);
    } 
    });
});
//===============//
$("#result_list").load('result_list.php?do=load&startdate='+encodeURIComponent($('#datestart').val())+'&enddate='+encodeURIComponent($('#dateend').val()), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success")
            //alert("External content loaded successfully!");
            $('#indicator').hide();
                    
            if(statusTxt == "error")
             alert("Error: " + xhr.status + ": " + xhr.statusText);
             });
	
	$('#search').click(function(){
		resultSearch();
	});

	$('#classify').click(function(){
		alertload('med_error_report.php?date1='+$('#datestart').val()+'&date2='+$('#dateend').val()+'&category1='+$('#category1').val()+'&med_error_type1='+$('#med_error_type1').val()+'&cause_id1='+$('#cause_id1').val()+'&sub_id1='+$('#sub_id1').val()+'&ptype1='+$('#ptype1').val()+'&room_id='+$('#room2').val(),'90%', '90%');
	});
				
});
	
function resultSearch(){
	$('#indicator2').show();
	$("#result_list").load('result_list.php?do=load&startdate='+encodeURIComponent($('#datestart').val())+'&enddate='+encodeURIComponent($('#dateend').val())+'&category1='+$('#category1').val()+'&med_error_type1='+$('#med_error_type1').val()+'&cause_id1='+$('#cause_id1').val()+'&sub_id1='+$('#sub_id1').val()+'&ptype1='+$('#ptype1').val()+'&room_id='+$('#room2').val(), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "success")
            //alert("External content loaded successfully!");
            $('#indicator2').hide();
                    
            if(statusTxt == "error")
             alert("Error: " + xhr.status + ": " + xhr.statusText);
             });
	
}
	
function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

}	
</script>
    
</head>

<body>
<div class="card" >
<div class="card-header"><i class="fas fa-poll font20"></i>&ensp;แสดงรายงาน</div>
<div class="card-body p-2" style="padding-bottom: 0px;">
<div class="form-group row " >
           <label for="reportrange" class="col-form-label-sm col-sm-3">เลือกวันที่</label>
           <div class="col-auto">

                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="far fa-calendar-alt"></i>&nbsp;
                    <span></span> 
                  <i class="fas fa-sort-down"></i>
                </div>			
		
   	  </div>
    </div>
            <input name="datestart" type="hidden" id="datestart" value="" /><input name="dateend" type="hidden" id="dateend" value="" />
<div class="form-group row " >
    <label for="category1" class="col-form-label-sm col-sm-3">category</label>
    <div class="col-sm-auto">
                        <select name="category1"  id="category1" class="form-control form-control-sm font14" style="width:300px;">
                            <option value="">เลือกระดับความรุนแรง</option>
                            <option value="A">A : ไม่มีความคลาดเคลื่อนเกิดขึ้น แต่มีเหตุการณ์ที่อาจทำให้เกิดความคลาดเคลื่อนได้</option>
                            <option value="B">B : มีความคลาดเคลื่อนเกิขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย เนื่องจากความคลาดเคลื่อนไปไม่ถึงผู้ป่วย</option>
                            <option value="C">C : มีความคลาดเคลื่อนเกิดขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย ถึงแม้ว่าความคลาดเคลื่อนนั้นจะไปถึงผู้ป่วยแล้ว</option>
                            <option value="D">D : มีความคลาดเคลื่อนเกิดขึ้น แต่ไม่เป็นอันตรายต่อผู้ป่วย แต่ยังจำเป็นต้องมีการติดตามผู้ป่วยเพิ่มเติม</option>
                            <option value="E">E : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยชั่วคราว รวมถึงจำเป็นต้องได้รับการรักษาหรือแก้ไขเพิ่มเติม</option>
                            <option value="F">F : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยเพียงชั่วคราวรวมถึงจำเป็นต้องได้รับการรักษาในโรงพยาบาล</option>
                            <option value="G">G : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยถาวร</option>
                            <option value="H">H : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอันตรายต่อผู้ป่วยจนเกือบถึงแต่ชีวิต </option>
                            <option value="I">I : มีความคลาดเคลื่อนเกิดขึ้น และเป็นอัตรายต่อผู้ป่วยจนถึงแก่ชีวิต</option>
        </select>
    </div>

</div>
	<div class="form-group row">
		<label for="med_error_type1" class="col-form-label-sm col-sm-3 font14">ประเภท</label>
		<div class="col-sm-auto">
        <select name="med_error_type1" class="form-control form-control-sm" id="med_error_type1" >
            <option value="">= เลือกประเภทความคลาดเคลื่อน =</option>
        <?php do { ?>
            <option value="<?php echo $row_rs_error_type['id']; ?>"><?php echo $row_rs_error_type['type_thai']; ?></option>
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
		<label for="cause_id1" class="col-form-label-sm col-sm-3 text-right"></label>
		<div class="col-sm-auto">
            <select id="cause_id1" name="cause_id1" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 1 =</option>
            </select>
		</div>
	</div>  
	<div class="form-group row">
		<label for="sub_id1" class="col-form-label-sm col-sm-3"></label>
		<div class="col-sm-auto">
            <select id="sub_id1" name="sub_id1" class="form-control form-control-sm">
                <option value="">= เลือกประเภทย่อย 2 =</option>
            </select>
		</div>
	</div> 

    <div class="form-group row">
		<label for="sub_id1" class="col-form-label-sm col-sm-3">ประเภทผู้ป่วย</label>
		<div class="col-sm-auto">
            <select name="ptype1" id="ptype1" class="form-control form-control-sm">
                <option value="">ทั้งหมด</option>
                <option value="opd">OPD</option>
                <option value="ipd">IPD</option>
            </select>
		</div>   
           
    </div> 
	<div class="form-group row">
		<label class="col-form-label col-form-label-sm col-sm-3">ห้องที่พบ</label>
		<div class="col-sm-auto">
			<select name="room2" id="room2" class="form-control form-control-sm">
				<option value="">ทั้งหมด</option>
            <?php do {  ?>
                    <option value="<?php echo $row_rs_room['id']; ?>"<?php if($row_rs_edit['room_id']!=""){ if (!(strcmp($row_rs_room['id'], $row_rs_edit['room_id']))) {echo "selected=\"selected\"";}} else {if (!(strcmp($row_rs_room['id'], $row_channel['room_id']))) {echo "selected=\"selected\"";}} ?>><?php echo $row_rs_room['room_name']?></option>
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
		<div class="col-sm-auto">
            <input id="search" name="search" type="button" value="ค้นหา" class="btn btn-info btn-sm"/>&nbsp;<button id="classify" name="classify"  class="btn btn-success btn-sm">รายละเอียด</button>
		</div>		
	</div>
</div>
</div>
<div class="position-relative">
<div class="position-absolute text-center" id="indicator2" style="width: 100%;z-index: 2;">
<div  align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.5;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <br />
<span>กำลังโหลด</span>
  </button>
</div>
</div>

<div class="mt-2" id="result_list">
    
</div>
</div>    
</body>
<?php
if($_POST['do']=="insert"||$_POST['do']=="update"){
mysql_free_result($qpatient);
mysql_free_result($qperson);
mysql_free_result($r_id);
mysql_free_result($qreporter);
}


mysql_free_result($rs_error_type);

mysql_free_result($channel);

mysql_free_result($rs_room);	
?>
