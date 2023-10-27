<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
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
?>
<? 
include('include/function.php');
$pt="IPD";

//หา an จาก search
mysql_select_db($database_hos, $hos);
$query_rs_search = "select * from an_stat where an='".$_GET['an']."'";
$rs_search = mysql_query($query_rs_search, $hos) or die(mysql_error());
$row_rs_search = mysql_fetch_assoc($rs_search);
$totalRows_rs_search = mysql_num_rows($rs_search);			


//หาข้อมูลทั่วไปของผู้ป่วย
mysql_select_db($database_hos, $hos);
$query_rs_patient = "SELECT i.bedno,a.hn,a.an,concat(p.pname,p.fname,'  ',p.lname) as patientname,a.ward,a.age_y,a.age_m,a.age_d,os.bw,w.name,ptt.name as pttypename,a.regdate,a.dchdate,icd.name as dxname ,dd.name as doctorname,a.pdx,a.dx0,a.dx1,a.dx2,a.dx3,a.dx4,a.dx5 FROM an_stat a left outer join pttype ptt on ptt.pttype=a.pttype left outer join opdscreen os on os.vn=a.vn left outer join patient p on p.hn=a.hn left outer join iptadm i on i.an=a.an left outer join ward w on w.ward=a.ward left outer join icd101 icd on icd.code=a.pdx left join doctor dd on dd.code=a.dx_doctor WHERE a.an ='".$row_rs_search['an']."'";
$rs_patient = mysql_query($query_rs_patient, $hos) or die(mysql_error());
$row_rs_patient = mysql_fetch_assoc($rs_patient);
$totalRows_rs_patient = mysql_num_rows($rs_patient);

// หาผลต่างของวันนับจากวันเข้ารักษา
$datediff = DateDiff($row_rs_patient['regdate'],date('Y-m-d'))+1;

$day1=substr($row_rs_patient['regdate'],8,2);
if(strpos($day1,'0')==0 && strpos($day1,'0') != false ){ 
 $newday = intval(substr($day1,1));
}
else {
$newday=$day1;	
}

//หาวันย้อนหลัง 30 day
mysql_select_db($database_hos, $hos);
$query_rs_interval = "SELECT ADDDATE(CURDATE(), -30) as diffdate;";
$rs_interval = mysql_query($query_rs_interval, $hos) or die(mysql_error());
$row_rs_interval = mysql_fetch_assoc($rs_interval);
$totalRows_rs_interval = mysql_num_rows($rs_interval);

//หายา home med
mysql_select_db($database_hos, $hos);
$query_rs_hm = "select rxdate,order_no from ipt_order_no where an ='".$an."' and order_type='Hme'";
$rs_hm = mysql_query($query_rs_hm, $hos) or die(mysql_error());
$row_rs_hm = mysql_fetch_assoc($rs_hm);
$totalRows_rs_hm = mysql_num_rows($rs_hm);

if($totalRows_rs_hm<>0){
	$order_no=$row_rs_hm['order_no'];
	$rxdate=$row_rs_hm['rxdate'];
}
mysql_free_result($rs_hm);

if($rxdate<>""){
	$datecheck=$rxdate;
	$condition="and ((m1.orderstatus = 'C'))";
}
else{
	$datecheck=date('Y-m-d');
	$condition="and ((m1.orderstatus = 'C' and m1.offdate is null) or (m1.orderstatus='S' and m1.orderdate=CURDATE())  )";
}

//หาข้อมูลยาใน profile
mysql_select_db($database_hos, $hos);
$query_rs_medplan = " select m1.icode,concat(s.name,' ',s.strength,' ',s.units) as name ,substr(m1.orderdate,1,10) as orderdate,d.shortlist,m1.orderstatus,concat('>',sp.name1,sp.name2,sp.name3) as sp_use_name,m1.sp_use,m1.med_plan_number,m1.note,a.age_y,a.age_m,a.hn,iptadm.bedno
from medplan_ipd m1
left outer join an_stat a on a.an=m1.an
left outer join iptadm on iptadm.an=a.an
left outer join s_drugitems s on s.icode=m1.icode   
left outer join drugusage d on d.drugusage=m1.drugusage left outer join sp_use sp on sp.sp_use=m1.sp_use   
where m1.an='".$row_rs_patient['an']."'  and m1.icode like '1%' and m1.orderdate >= DATE_SUB('".$row_rs_patient['regdate']."', INTERVAL 1 MONTH)  ".$condition."  order by m1.orderstatus,m1.orderdate";
//echo $query_rs_medplan;
$rs_medplan = mysql_query($query_rs_medplan, $hos) or die(mysql_error());
$row_rs_medplan = mysql_fetch_assoc($rs_medplan);
$totalRows_rs_medplan = mysql_num_rows($rs_medplan);


//หาวันที่ให้ยาทั้งหมด
mysql_select_db($database_hos, $hos);
$query_rs_meddate = "select order_date from medpay_ipd where an ='".$row_rs_patient['an']."' group by order_date order by order_date ASC";
$rs_meddate = mysql_query($query_rs_meddate, $hos) or die(mysql_error());
$row_rs_meddate = mysql_fetch_assoc($rs_meddate);
$totalRows_rs_meddate = mysql_num_rows($rs_meddate);

mysql_select_db($database_hos, $hos);
$query_s_medpay = "SELECT i.an,i.rxdate,DATE_FORMAT(i.rxdate,'%e/%c/%Y') as rxdate2,i.order_no,i.order_locked,i.order_type,i.entry_staff,i.rxtime  ,w.name as ward_name  ,i.item_count, i.confirm_prepare,i.confirm_pay, i.amount,t.name as medication_type_name,i.day_queue,i.rxdate as maxdate FROM ipt_order_no i  left outer join ward w on w.ward = i.ward  left outer join medpay_ipd_head m on m.med_rx_number = i.order_no  left outer join ipt_medication_type t on t.code = m.ipt_medication_type WHERE i.an='".$_GET['an']."'  and i.order_type in ('IRx','Hme') ORDER BY i.rxdate desc,i.rxtime desc";
//echo $query_s_medpay;
$s_medpay = mysql_query($query_s_medpay, $hos) or die(mysql_error());
$row_s_medpay = mysql_fetch_assoc($s_medpay);
$totalRows_s_medpay = mysql_num_rows($s_medpay);

if($_GET['action']=="allergy_save"){
// บันทึกใน kohrx_dispen_staff_operation
mysql_select_db($database_hos, $hos);
$insert_rx = "INSERT INTO ".$database_kohrx.".kohrx_adr_check (an,hn,check_date,doctorcode,respondent,answer) VALUES ('".$_GET['an']."','".$row_rs_patient['hn']."',NOW(),'".$_SESSION['doctorcode']."','".$_GET['respondent']."','".$_GET['answer']."')
";
$rs_insert_rx = mysql_query($insert_rx, $hos) or die(mysql_error());

//บันทึกข้อมูการบันทึก kohrx_dispen_staff_operation ใน replicate_log
mysql_select_db($database_hos, $hos);
$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','INSERT INTO ".$database_kohrx.".kohrx_adr_check (an,hn,check_date,doctorcode,respondent,answer) VALUES (\'".$_GET['an']."\',\'".$_GET['hn']."\',NOW(),\'".$_SESSION['doctorcode']."\',\'".$_GET['respondent']."\',\'".$_GET['answer']."\'')";
$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
    
}
else{

mysql_select_db($database_hos, $hos);
$query_rs_respondent = "select * from ".$database_kohrx.".kohrx_adr_check_respondent";
$rs_respondent = mysql_query($query_rs_respondent, $hos) or die(mysql_error());
$row_rs_respondent = mysql_fetch_assoc($rs_respondent);
$totalRows_rs_respondent = mysql_num_rows($rs_respondent);

mysql_select_db($database_hos, $hos);
$query_rs_answer = "select * from ".$database_kohrx.".kohrx_adr_check_answer";
$rs_answer = mysql_query($query_rs_answer, $hos) or die(mysql_error());
$row_rs_answer = mysql_fetch_assoc($rs_answer);
$totalRows_rs_answer = mysql_num_rows($rs_answer);
}
mysql_select_db($database_hos, $hos);
$query_rs_adr = "select * from ".$database_kohrx.".kohrx_adr_check where an='".$_GET['an']."'";
$rs_adr = mysql_query($query_rs_adr, $hos) or die(mysql_error());
$row_rs_adr = mysql_fetch_assoc($rs_adr);
$totalRows_rs_adr = mysql_num_rows($rs_adr);
//drp
mysql_select_db($database_hos, $hos);
$query_rs_drp = "select * from ".$database_kohrx.".kohrx_drp_record where hn='".$row_rs_patient['hn']."'";
$rs_drp = mysql_query($query_rs_drp, $hos) or die(mysql_error());
$row_rs_drp = mysql_fetch_assoc($rs_drp);
$totalRows_rs_drp = mysql_num_rows($rs_drp);
//counselling
mysql_select_db($database_hos, $hos);
$query_rs_couselling = "select  *,concat(date_format(record_date,'%d/%m/'),(date_format(record_date,'%Y')+543)) as record_date2 from ".$database_kohrx.".kohrx_couselling where hn='".$row_rs_patient['hn']."' order by id DESC";
//echo $query_rs_couselling;
$rs_couselling = mysql_query($query_rs_couselling, $hos) or die(mysql_error());
$row_rs_couselling = mysql_fetch_assoc($rs_couselling);
$totalRows_rs_couselling = mysql_num_rows($rs_couselling);

//search med_reconcile
mysql_select_db($database_hos, $hos);
$query_rs_reconcile = "select count(*) as count_reconcile,vstdate2 from ".$database_kohrx.".kohrx_med_reconcile where hn='".$row_rs_patient['hn']."' and vstdate2 in (select vstdate from opitemrece where an = '".$_GET['an']."' group by vstdate)";
//echo $query_rs_reconcile;
$rs_reconcile = mysql_query($query_rs_reconcile, $hos) or die(mysql_error());
$row_rs_reconcile = mysql_fetch_assoc($rs_reconcile);
$totalRows_rs_reconcile = mysql_num_rows($rs_reconcile);

if($row_rs_reconcile['count_reconcile']>0){
	mysql_select_db($database_hos, $hos);
	$query_rs_med = "select * from ".$database_kohrx.".kohrx_med_reconcile  where hn='".$row_rs_patient['hn']."' and vstdate2='".$row_rs_reconcile['vstdate2']."'";
	//echo $query_rs_med;
	$rs_med = mysql_query($query_rs_med, $hos) or die(mysql_error());
	$row_rs_med = mysql_fetch_assoc($rs_med);
	$totalRows_rs_med = mysql_num_rows($rs_med);
}

mysql_select_db($database_hos, $hos);
$query_order_list = "select * from ".$database_kohrx.".kohrx_ipd_order_image where an='".$row_rs_patient['an']."' order by order_date DESC,order_time DESC,capture_date DESC";
$order_list = mysql_query($query_order_list, $hos) or die(mysql_error());
$row_order_list = mysql_fetch_assoc($order_list);
$totalRows_list = mysql_num_rows($order_list);

include('include/function_sql.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php //include('java_css_file.php'); ?>
   
<script>
$(document).ready(function(e) {
    var table = $('#freezetable').DataTable( {
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,

        paging:         false,
        searching: false,
        info: false,
        fixedColumns:   {
            left: 4
        }
    } );
    
            $('#allergy_save').prop('disabled',true);
    
            $('#respondent_list').change(function(){
                if($('#respondent_list').val()!=""&&$('#answer_list').val()!=""){
                    $('#allergy_save').prop('disabled',false);
                }    
                else{
                    $('#allergy_save').prop('disabled',true);
                }
            });
            $('#answer_list').change(function(){
                if($('#respondent_list').val()!=""&&$('#answer_list').val()!=""){
                    $('#allergy_save').prop('disabled',false);
                }    
                else{
                    $('#allergy_save').prop('disabled',true);
                }
            });
    
            $('#allergy_save').click(function(){
                $("#dispen-body").load('detail_ipd.php?an=<?php echo $_GET['an']; ?>&action=allergy_save&respondent='+$('#respondent_list').val()+'&answer='+$('#answer_list').val(), function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();
						$('#an').val('<?php echo $_GET['an']; ?>');
                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
                
            });
			
			<?php if($totalRows_rs_drp<>0){ ?>
                $("#drp_list").load('detail_drp_list.php?hn=<?php echo $row_rs_patient['hn']; ?>', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
 
			<?php } ?>
			<?php if($totalRows_rs_couselling<>0){ ?>
                        $('#counselling_list').load('detail_drug_counseling.php?hn=<?php echo $row_rs_patient['hn']; ?>',function(responseTxt, statusTxt, xhr){
                            if(statusTxt == "success")
                            $('#counseling_indicator').hide();
                            if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                        });
			<?php } ?>
 
	
              $('#chart_drug').load('detail_ipd_med.php?an=<?php echo $_GET['an']; ?>&pdx=<?php echo $row_rs_patient['pdx']; ?>&dx0=<?php echo $row_rs_patient['dx0']; ?>&dx1=<?php echo $row_rs_patient['dx1']; ?>&dx2=<?php echo $row_rs_patient['dx2']; ?>&dx3=<?php echo $row_rs_patient['dx3']; ?>&dx4=<?php echo $row_rs_patient['dx4']; ?>&dx5=<?php echo $row_rs_patient['dx5']; ?>', function(responseTxt, statusTxt, xhr){
               if(statusTxt == "success")
                //alert("External content loaded successfully!");
                $('#drug_indicator').fadeOut(1000);
                if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });
	  <?php if($totalRows_rs_drp==0){ ?>
            $('#drp_button').show();
      <?php } else{ ?>
            $('#drp_button').hide();
    
    <?php }?>

			//set ความกว้างของ div profile
			var setwidth=$(window).width()-215;
			$("#profile").css("max-width",setwidth);    
    });

function medpay(order_no,an){
	          $('#drug_indicator').show();
              $('#chart_drug').load('detail_ipd_med.php?order_no='+order_no+'&an='+an+'&pdx=<?php echo $row_rs_patient['pdx']; ?>&dx0=<?php echo $row_rs_patient['dx0']; ?>&dx1=<?php echo $row_rs_patient['dx1']; ?>&dx2=<?php echo $row_rs_patient['dx2']; ?>&dx3=<?php echo $row_rs_patient['dx3']; ?>&dx4=<?php echo $row_rs_patient['dx4']; ?>&dx5=<?php echo $row_rs_patient['dx5']; ?>', function(responseTxt, statusTxt, xhr){
               if(statusTxt == "success")
                //alert("External content loaded successfully!");
                $('#drug_indicator').fadeOut(1000);
                if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);    
               });

	}

function changbgcolor(id)
{
	var tr = document.getElementsByClassName('changcolor')
	for(var i=0;i<tr.length;i++)
	{
		if(tr[i].id == id)
		{
			document.getElementById(tr[i].id).style.background= '#A1DE93';
		}
		else
		{
		document.getElementById(tr[i].id).style.background='';
		}
	}
}

function drp_load_list(){
	                $("#drp_list").load('detail_drp_list.php?hn=<?php echo $row_rs_patient['hn']; ?>', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#indicator').hide();                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
}
function counselling_load_list(){
	                $("#counselling_list").load('detail_drug_counseling.php?hn=<?php echo $row_rs_patient['hn']; ?>', function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
                        $('#counseling_indicator').hide();                    
					if(statusTxt == "error")
                      alert("Error: " + xhr.status + ": " + xhr.statusText);
                  });
}   
function drp_button_show(){
        $('#drp_button').show();
}    
function drp_button_hide(){
        $('#drp_button').hide();
}    
	
function alertload_ipd(url,w,h,an){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ ipd_right_load(an); }});

}	
</script>
<script>
        function zoomInImage(imgid){
                $("#"+imgid).width($("#"+imgid).width()+100);
                $("#"+imgid).height($("#"+imgid).height()+100);
        }
        function zoomOutImage(imgid){
                $("#"+imgid).width($("#"+imgid).width()-100);
                $("#"+imgid).height($("#"+imgid).height()-100);
        }
    
        function rotateImage(imgid,degree) {
            $('#'+imgid).animate({
                transform: degree
            }, {
                step: function(now, fx) {
                    $(this).css({
                        '-webkit-transform': 'rotate(' + now + 'deg)',
                        '-moz-transform': 'rotate(' + now + 'deg)',
                        'transform': 'rotate(' + now + 'deg)'
                    });
                }
            });
        }
</script>
    
<style>
/* Ensure that the demo table scrolls */
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        max-width:1100px;
        margin: 0;
    }
</style>
</head>

<body>
<input type="hidden" id="hidden_hn" value="<?php echo $row_rs_patient['hn']; ?>"/>
<div class="" style="margin-top: 0px; margin-left: -5px;" >
<!-- navtab 
//////////////////////////////////////-->
<div class="bg-light" style="padding: 6px; padding-bottom: 5px; margin-left: -10px; margin-right: -10px; border-bottom: 1px solid #E3E3E3; ">
<ul class="nav nav-pills" id="myTab" role="tablist">
		<button onclick="openNav()" class="btn btn-outline-danger my-2 my-sm-0" style=" padding-bottom: 0px; padding-top: 0px; margin-right: 10px;"><i class="far fa-clock"></i>&nbsp;ประวัติ&nbsp;</button>
  <li class="nav-item">
    <a class="nav-link active font14 pt-1 pb-1" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="true"><i class="fas fa-prescription-bottle-alt fong20"></i>&ensp;สั่งยา</a>
  </li>
  <li class="nav-item">
    <a class="nav-link font14 pt-1 pb-1" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-id-badge font20"></i>&ensp;Profile Sheet</a>
  </li>
  <li class="nav-item">
    <a class="nav-link font14 pt-1 pb-1" id="profile-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false"><i class="fas fa-id-badge font20"></i>&ensp;Doument</a>
  </li>    
  <li class="nav-item">
    <a class="nav-link font14 pt-1 pb-1" id="drp-tab" data-toggle="tab" href="#drp" role="tab" aria-controls="drp" aria-selected="false"><i class="fas fa-bookmark font20"></i>&ensp;DRP</a>
  </li>
  <li class="nav-item">
    <a class="nav-link font14 pt-1 pb-1" id="counselling-tab" data-toggle="tab" href="#counselling" role="tab" aria-controls="counselling" aria-selected="false"><i class="fas fa-bookmark font20"></i>&ensp;Couselling</a>
  </li>    
<?php if($row_rs_reconcile['count_reconcile']!=0){ ?>	
  <li class="nav-item">
    <a class="nav-link font14 pt-1 pb-1" id="reconcile-tab" data-toggle="tab" href="#reconcile" role="tab" aria-controls="reconcile" aria-selected="false"><i class="fas fa-pills font20"></i>&ensp;Med.Reconcile</a>
  </li>
 <?php } ?>
  <li class="nav-item">
	<button class="btn btn-light thfont font12" onClick="ipd_right_load('<?php echo $_GET['an']; ?>')"><i class="fas fa-sync-alt font20 text-primary"></i>&nbsp;Reload</button>	
  </li>	
</ul>
</div>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="order" role="tabpanel" aria-labelledby="order-tab">
<!-- drug order -->
<div class="row">
	<div class="col" style="-ms-flex: 0 0 260px;flex: 0 0 260px;">
   	<!-- chart -->
	<div class="mt-2">
		<div class="p-1 text-white bg-success">
			<i class="fas fa-capsules"></i>&nbsp;ซักประวัติแพ้ยา<?php if($totalRows_rs_adr<>0){ ?><i class="fas fa-check text-primary position-absolute font20" style="right: 30px; -webkit-text-stroke-width: 1px;-webkit-text-stroke-color: white;"></i><?php } ?></div>
		<div style="background: #BBFB99; border: solid 1px #349A24;border-radius: 0px 0px 5px 5px;" class="p-2 " >
			<?php if($totalRows_rs_adr==0){ ?>
            <div class="p-0">
                      <select name="respondent_list" class="font14" id="respondent_list" style="width: 85px; padding-left: 1px ; padding-right: 1px; height: 34px; border: 1px solid #A4A4A4" >
                        <option value="" >เลือกผู้ตอบ</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rs_respondent['id']?>"<?php if (!(strcmp($row_rs_respondent['id'], $row_rs_edit_adr['respondent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_respondent['respondent']?></option>
                    <?php
} while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
  $rows = mysql_num_rows($rs_respondent);
  if($rows > 0) {
      mysql_data_seek($rs_respondent, 0);
	  $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
  }
?>
                  </select>			
				
                    <select name="answer_list" class="font14" id="answer_list" style="width: 110px; padding-left: 1px; padding-right: 1px;height: 34px; border: 1px solid #A4A4A4"  >
                        <option value="" >เลือกคำตอบ</option>
						<?php do {  ?>
							<option value="<?php echo $row_rs_answer['id']?>"<?php if (!(strcmp($row_rs_answer['id'], $row_rs_edit_adr['answer']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_answer['answer']?></option>
						<?php
							} while ($row_rs_answer = mysql_fetch_assoc($rs_answer));
							$rows = mysql_num_rows($rs_answer);
							if($rows > 0) {
							 mysql_data_seek($rs_answer, 0);
							 $row_rs_answer = mysql_fetch_assoc($rs_answer);
							  }	?>
					</select>	
                <button class="btn btn-success btn-sm text-white p-2 " id="allergy_save"><i class="fas fa-save font16"></i></button>
			</div>
            <?php } else { ?>
            <div class="p-0 font12 text-center"><strong><?php echo respondentname($row_rs_adr['respondent'])." (".answername($row_rs_adr['answer']).")"; ?></strong></div>
            <div class="p-0 font12 text-center"><?php echo doctorname($row_rs_adr['doctorcode']); ?><i><?php echo " (".date_db2th($row_rs_adr['check_date']).")"; ?></i></div>
            <?php } ?>
		</div>
	</div>

    <div  style="margin-top: 10px;">
    	<div class=" p-0 ">
        <table width="100%" border="0" align="center" class="table table-sm font14 ">
          <thead >
            <tr class="bg-gray1">
            <td  align="center" >no.</td>
            <td  align="center" >ชนิด</td>
            <td  align="center" >วันที่่</td>
            <td  align="center" >เวลา</td>
            <td  align="center" >order_no</td>
          </tr>
        </thead>
          <?php $i=0; do { $i++; 
	  ?>
          <tr onclick="medpay('<?php echo $row_s_medpay['order_no']; ?>','<?php echo $row_s_medpay['an']; ?>');changbgcolor(this.id)" style="cursor:pointer; <?php if($i==1){ ?> background-color: #A1DE93; <?php } ?>" id="<?php echo $row_s_medpay['order_no']; ?>" class="changcolor grid4" >
            <td  align="center" ><font color="<?php echo $font; ?>"><?php echo $i; ?></font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=$row_s_medpay['order_type']; ?>
            </font></td>
            <td align="center"  ><font color="<?php echo $font; ?>"><?=date_db2th($row_s_medpay['rxdate']); ?></font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=substr($row_s_medpay['rxtime'],0,5); ?>
            </font></td>
            <td align="center" ><font color="<?php echo $font; ?>">
              <?=$row_s_medpay['order_no']; ?>
            </font></td>
          </tr>
          <?php } while ($row_s_medpay = mysql_fetch_assoc($s_medpay)); ?>
        </table>
        </div>
    </div>
   	<!-- chart -->
    </div>
	<!-- chart-drug -->
    <!--indicator-->
    <div id="drug_indicator" align="center" class="spinner" style="position:absolute; bottom: 100px; ">
    <button class="btn btn-secondary" type="button" style="width:200px;" disabled>
      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <span >กำลังโหลดรายการยา</span>
    </button>
    </div>
	
		<div   class="col" id="chart_drug" style="padding-left: 0px; padding-right: 10px;"> 
		</div>
	<!-- chart-drug-->
</div >
<!-- drug order -->
</div>
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab" style="" >

<!-- drug profile -->
<div class="card mt-2"  >
	<div class="card-header"><span class="card-title font-weight-bold"><i class="fas fa-pills font20"></i>&ensp;Drug Profile</span><button class="btn btn-sm btn-secondary" style="position: absolute; margin-left:20px; top:8px;" onClick="alertload_ipd('detail_ipd_profile_check.php?an=<?php echo $row_rs_patient['an']; ?>&action=<?php echo $_GET['action']; ?>&respondent=<?php echo $_GET['respondent']; ?>&answer=<?php echo $_GET['answer']; ?>','100%','100%','<?php echo $row_rs_patient['an']; ?>')"><i class="fas fa-check"></i>&nbsp;เช็คยา</button></div>
    <div class="card-body p-1">
	<div  style="overflow:scroll;overflow-y:auto; height:65vh; background-color: white; margin-top: 5px; ">
	<table  class=" talbe table-striped table-sm table-bordered" style="
width: 100%;" >
      <tr>
        <td align="center" bgcolor="#FFFFFF" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
        <td  align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4">&nbsp;</td>
        <td align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-info w-100 p-1">รายการยา</span></td>
        <td  align="center" bgcolor="#FFFFFF" class="big_white16" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-warning w-100 p-1">วิธีใช้</span></td>
        <?php  do {  ?>
        <td  align="center" bgcolor="#FFFFFF"   class="table_head_small_bord" style="border: 1px solid #FFF; border-bottom:1px solid #E4E4E4"><span class="badge badge-dark p-1"><?php echo substr($row_rs_meddate['order_date'],8,2)."/".substr($row_rs_meddate['order_date'],5,2); ?></span></td>
        <?php } while ($row_rs_meddate = mysql_fetch_assoc($rs_meddate)); ?>
      </tr>
      <tbody>
      <?php $n=0;do { $n++;
	mysql_select_db($database_hos, $hos);
$query_rs_meddate2 = "select m.order_date,m.icode,m.med_plan_number from medpay_ipd m";
$query_rs_meddate2.="  where m.an ='".$row_rs_patient['an']."' group by m.order_date order by m.order_date ASC";
$rs_meddate2 = mysql_query($query_rs_meddate2, $hos) or die(mysql_error());
$row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2);
	?>
      <tr >
            <td align="center" class="bg-white"><?php echo $n; ?></td>
            <td bgcolor="#FFFFFF" class="font18 font-weight-bolder <?php if($row_rs_medplan['orderstatus']!="C"){ echo "text-danger"; } else { echo "text-primary"; } ?>"><?php print $row_rs_medplan['orderstatus']; ?></td>
        <td align="left" style="font-size: 12px;" class=""><?php echo $row_rs_medplan['name']; ?></td>
        <td align="left"  style="font-size: 12px;" class=""><?php if($row_rs_medplan['sp_use']=="") {echo $row_rs_medplan['shortlist']; } else { echo $row_rs_medplan['sp_use_name']; } ?><?php if($row_rs_medplan['note']!=""){ echo "<div class='text-white badge badge-danger font-weight-bold' style='font-size:14px;'>** ".$row_rs_medplan['note']."</div>"; } ?></td>
        <? do{ 
	mysql_select_db($database_hos, $hos);
$query_rs_order_qty = "select med_order_qty from medpay_ipd where icode='$row_rs_medplan[icode]' and med_plan_number='$row_rs_medplan[med_plan_number]' and  order_date='".$row_rs_meddate2['order_date']."' and an='".$row_rs_patient['an']."'  ";
$rs_order_qty = mysql_query($query_rs_order_qty, $hos) or die(mysql_error());
$row_rs_order_qty = mysql_fetch_assoc($rs_order_qty);
$totalRows_rs_order_qty = mysql_num_rows($rs_order_qty);
	?>
        <td  align="center" style="color:#000000"><?php echo "$row_rs_order_qty[med_order_qty]"; ?></td>
        <?php } while ($row_rs_meddate2 = mysql_fetch_assoc($rs_meddate2)); ?>
      </tr>
      <?php mysql_free_result($rs_order_qty); mysql_free_result($rs_meddate2);} while ($row_rs_medplan = mysql_fetch_assoc($rs_medplan)); ?>
      </tbody>
    </table>
		</div>
    </div>
</div>
<!-- drug profile -->
</div>
    
<!-- Document -->    
<div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="profile-tab">
    <?php if($totalRows_list<>0){ do{     ?>
    <div class="card mt-2" style="width: 800px;">
    
	<div class="card-header" style="font-size: 11px;"><?php echo "วันที่สั่ง : ".date_db2th($row_order_list['order_date'])."&nbsp;".substr($row_order_list['order_time'],0,5); ?><div class="text-right" style="position: absolute; right: 10px; z-index: 1; top: 10px;"><input type="button" class="w3-btn w3-green" value="90" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="-90" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="180" onClick="rotateImage('<?php echo $row_order_list['id']; ?>',this.value);" />
        <input type="button" class="w3-btn w3-green" value="เริ่มต้น" onClick="rotateImage('<?php echo $row_order_list['id']; ?>','360');" />       
        </div></div>

	<div class="card-body" style="padding: 0px;" >

		<div style=" position: absolute;margin-top:5px; margin-left:10px;"><span style="text-shadow: 1px 1px #ffffff; font-size: 12px"><?php echo datetime_db2th($row_order_list['capture_date']); ?></span></div>
		<center>
        <img src="uploads/<?php echo $row_order_list['image_name']; ?>" class="rounded" style="display: flex; width: 100%; ;cursor: pointer" onClick="window.open('ipd_profile_image_preview.php?img=<?php echo $row_order_list['image_name']; ?>','_new');" id="<?php echo $row_order_list['id']; ?>" />
        </center>
        <?php if(($row_order_list['remark']!="") and ($row_order_list['remark']!=NULL) ){ ?><div class="alert alert-warning" style="margin-bottom: 0px;" role="alert"><?php echo $row_order_list['remark']; ?>
        </div>	
        <?php } ?>
    </div>
    </div>
    <?php }while($row_order_list = mysql_fetch_assoc($order_list)); } ?>	
</div>  
<!-- Document -->    
    
<!-- drp --->
  <div class="tab-pane fade pt-2" id="drp" role="tabpanel" aria-labelledby="drp-tab">
	 <button class="btn btn-secondary btn-sm" style="margin-right: 10px;" id="drp_button"  onclick="alertload('detail_drp.php?hn=<?php  echo $row_rs_patient['hn']; ?>&pt=<?php echo $_SESSION['pt']; ?>','90%','90%');"><span class="badge badge-light font14">+</span>&nbsp;<strong>DRP</strong></button> 
	  <div id="drp_list" class="mt-2"></div>
	</div>
<!-- drp --->
<!-- counselling --->
  <div class="tab-pane fade mt-2" id="counselling" role="tabpanel" aria-labelledby="counselling-tab">
	  <?php if($totalRows_rs_counselling==0){ ?>
	 <button class="btn btn-secondary btn-sm" style="margin-right: 10px;"  onclick="alertload('form_couselling.php?hn=<?php  echo $row_rs_patient['hn']; ?>&pt=<?php echo $_SESSION['pt']; ?>&vstdate=<?php echo date('Y-m-d'); ?>&patient_type=IPD','90%','90%');"><span class="badge badge-light font14">+</span>&nbsp;<strong>Counselling</strong></button> 
  		<?php } ?>
	  <div id="counselling_list" class="mt-2"></div>
	</div>
<!-- counselling --->	
  <?php if($row_rs_reconcile['count_reconcile']){ ?>		
  <div class="tab-pane fade" id="reconcile" role="tabpanel" aria-labelledby="reconcile-tab"  style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:75vh; background-color: white;padding: 0px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table font14 table-striped table-hover">
	<thead >
	  <tr >
		<th align="center" class="text-center" style="border-top: 0px;">ลำดับ</th>
		<th align="center" class="text-center" style="border-top: 0px;">รายการยา/วิธีใช้</th>
		<th align="center" class="text-center" style="border-top: 0px;">เหลือ</th>
		<th align="center" class="text-center" style="border-top: 0px;">ที่มา</th>
		<th align="center" class="text-center" style="border-top: 0px;">วันที่ได้</th>
		<th align="center" class="text-center" style="border-top: 0px;">วันนัด</th>
		<th align="center" class="text-center" style="border-top: 0px;">last dose</th>
		<th align="center" class="text-center" style="border-top: 0px;">หมายเหตุ</th>
	  </tr>
	</thead>
	<tbody>
	  <?php $i=0; do { $i++; ?>
	<?php 

	?>
	  <tr>
		<td align="center"><?php echo $i; ?></td>
		<td align="left" id="<?php echo $row_rs_med['hos_guid']; ?>" >
			<div>
			<?php echo "<strong>".$row_rs_med['drug_name']."</strong>"; if($row_rs_med['qty']!=""){ echo "&ensp;#&nbsp;<span class='text-danger'><strong>".$row_rs_med['qty']."</strong></span>"; } ?>
			</div>
			<div class="pl-3 font12">
			<?php echo $row_rs_med['drugusage']; ?>
			</div>
		</td>
		<td align="center"><?php echo $row_rs_med['remain']; ?></td>
		<td align="center"><?php echo $row_rs_med['src_hospcode']; ?></td>
		<td align="center"><?php echo dateThai3($row_rs_med['vstdate']); ?></td>
		<td align="center"><?php echo dateThai3($row_rs_med['appdate']); ?></td>
		<td align="center"><?php echo $row_rs_med['last_dose']; ?></td>
		<td align="center"><?php if($row_rs_med['error_count']<>0){?><div><button class="btn btn-success p-2" onClick="alertload_error('med_reconcile_error_detail.php?med_reconcile_id=<?php echo $row_rs_med['id']; ?>','90%','90%')" >error&nbsp;<span class="badge badge-light"><?php echo $row_rs_med['error_count']; ?></span></button><?php } ?></div>
			<?php echo $row_rs_med['remark']; ?></td>
	  </tr>
	  <?php } while ($row_rs_med = mysql_fetch_assoc($rs_med)); ?>
	  </tbody>
	</table>
	  <div style="padding:10px; border-top:1px #DBDBDB solid; margin-top:-17px;">
<button class="btn btn-success" onClick="window.open('med_reconcile_print.php?hn=<?php echo $row_rs_patient['hn']; ?>&vstdate=<?php echo ($row_rs_reconcile['vstdate2']); ?>','_new');"><i class="fas fa-file-prescription font20" ></i>&ensp;พิมพ์</button>&emsp;<button class="btn btn-primary" onclick="window.open('med_reconcile.php?do=link&hn=<?php echo $row_rs_patient['hn']; ?>&vstdate=<?php echo date_db2th($row_rs_reconcile['vstdate2']); ?>','_new');"><i class="fas fa-search font20" ></i>&ensp;จัดการ</button>
</div>
  </div>	
  <?php } ?>
	
</div>	
<!-- navtab 
//////////////////////////////////////-->

</div>
</body>
</html>
<?php 
mysql_free_result($rs_search);
mysql_free_result($rs_patient);
mysql_free_result($rs_interval);
mysql_free_result($rs_medplan);
mysql_free_result($rs_meddate);
mysql_free_result($s_medpay);

if($_GET['action']!="allergy_save"){
    mysql_free_result($rs_respondent);
    mysql_free_result($rs_answer);
}

mysql_free_result($rs_adr);

mysql_free_result($rs_drp);

mysql_free_result($rs_couselling);

if($row_rs_reconcile['count_reconcile']>0){
	mysql_free_result($rs_med);
}

mysql_free_result($rs_reconcile);

?>
<?php mysql_free_result($order_list); ?>