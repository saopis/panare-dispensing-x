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

include('include/function.php');
    if(isset($_POST['hn'])&&($_POST['hn']!="")){
        $hn=$_POST['hn']; 
    }
    if(isset($_GET['hn'])&&($_GET['hn']!="")){
        $hn=$_GET['hn']; 
    }
    if(isset($_POST['vstdate'])&&($_POST['vstdate']!="")){
        $vstdate=$_POST['vstdate']; 
    }
    if(isset($_GET['vstdate'])&&($_GET['vstdate']!="")){
        $vstdate=$_GET['vstdate']; 
    }
    if(isset($_POST['vn'])&&($_POST['vn']!="")){
        $vn=$_POST['vn']; 
    }
    if(isset($_GET['vn'])&&($_GET['vn']!="")){
        $vn=$_GET['vn']; 
    }



	if(isset($_GET['action'])&&($_GET['action']=="delete")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from  ".$database_kohrx.".kohrx_emergency_drug where id='".$_GET['id']."'";
		$delete = mysql_query($query_delete, $hos) or die(mysql_error());
		$success=1;
		}
		
	if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){

		for($i=0;$i<count($_POST['checkbox']);$i++){
			if($_POST['checkbox']!=""){
			$emer_drug1=substr($_POST['checkbox'][$i],0,7);
			$vn=substr($_POST['checkbox'][$i],7,12);
			$rxtime=substr($_POST['checkbox'][$i],19,8);

			mysql_select_db($database_hos, $hos);
			$query_emer_drug = "select * from  ".$database_kohrx.".kohrx_emergency_drug where vn='".$vn."' and icode='".$emer_drug1."' and rxtime='".$rxtime."'";
			$emer_drug = mysql_query($query_emer_drug, $hos) or die(mysql_error());
			$row_emer_drug = mysql_fetch_assoc($emer_drug);
			$totalRows_emer_drug = mysql_num_rows($emer_drug);
			
			if($totalRows_emer_drug==0){
			mysql_select_db($database_hos, $hos);
			$query_insert = "insert into  ".$database_kohrx.".kohrx_emergency_drug (vn,icode,rxtime,reciever,doctor,dispen_date,dispen_time,receive_time) values('".$vn."','".$emer_drug1."','".$rxtime."','".$_POST['respondent']."','".$_POST['rx_check']."',NOW(),'".$_POST['dispen_time']."','".$_POST['receive_time']."')";
			$insert = mysql_query($query_insert, $hos) or die(mysql_error());
				
			}
			}
			}
			
			$success=1;
            mysql_free_result($emer_drug);
			}
		if($success==1){
                echo "<script>parent.drug_list_load_vn('".$vn."');parent.$.fn.colorbox.close();</script>";
                    exit();


			}
	
mysql_select_db($database_hos, $hos);
$query_s_drug = "select o.rxtime,o.icode,substring(o.icode,1,1) as scode,o.income,concat(s.name,' ',s.strength,' ',s.units) as drugname,s.did,o.qty, d.drugusage,concat('.',d.code) as code,d.name1,d.name2,d.name3,d.shortlist,o.vn,dc.dosage_min,dc.dosage_max,dc.dose_perunit,d.ccperdose,d.iperday,concat(sp.name1,sp.name2,sp.name3) as sp_name,sp.sp_use,s.pregnancy,s.pregnancy_notify_text,breast_feeding_alert_text,du.real_use,o.hos_guid,kc.icode as qtycheck,dl.link   from opitemrece o  left outer join sp_use sp on sp.sp_use=o.sp_use  left outer join drugitems s on s.icode=o.icode left outer join  ".$database_kohrx.".kohrx_drugitems_calculate dc on dc.icode=s.icode left outer join  ".$database_kohrx.".kohrx_drugusage_realuse du on du.drugusage=o.drugusage left outer join drugusage d on d.drugusage=o.drugusage    left outer join sp_use u on u.sp_use = o.sp_use  left outer join ovst ov on ov.vn=o.vn  left outer join  ".$database_kohrx.".kohrx_drugqty_check kc on kc.icode=o.icode   left outer join  ".$database_kohrx.".kohrx_drug_lexi_link dl on dl.icode=o.icode   where o.vn='".$vn."'  and o.icode like '1%' group by o.hos_guid,o.icode,s.name,s.strength,s.units,o.qty,o.unitprice,d.drugusage,d.code,d.name1,d.name2,d.name3,d.shortlist,u.name1,u.name2,u.name3   order by o.item_no";
$s_drug = mysql_query($query_s_drug, $hos) or die(mysql_error());
$row_s_drug = mysql_fetch_assoc($s_drug);
$totalRows_s_drug = mysql_num_rows($s_drug);

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from  ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_doctor = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode";
$rs_doctor = mysql_query($query_rs_doctor, $hos) or die(mysql_error());
$row_rs_doctor = mysql_fetch_assoc($rs_doctor);
$totalRows_rs_doctor = mysql_num_rows($rs_doctor);

mysql_select_db($database_hos, $hos);
$query_rs_respondent = "select * from  ".$database_kohrx.".kohrx_adr_check_respondent";
$rs_respondent = mysql_query($query_rs_respondent, $hos) or die(mysql_error());
$row_rs_respondent = mysql_fetch_assoc($rs_respondent);
$totalRows_rs_respondent = mysql_num_rows($rs_respondent);

include('include/function_sql.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />

<?php include('java_css_file.php'); ?>
<!-- input Mask -->  
<script src="include/masked/js/jquery.mask.min.js" ></script>
<script>
    $(document).ready(function(){		
            
			const timenow = Date().slice(16,21);
            $('#dispen_time').val(timenow);
        
            $('#save').prop('disabled',true);
			$("#receive_time").inputmask({"mask": "99:99"});
			$("#dispen_time").inputmask({"mask": "99:99"});
            $("#receive_time").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $("#dispen_time").keypress(function(event) {
                return /\d/.test(String.fromCharCode(event.keyCode));
            });
            $('#receive_time').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });
            $('#dispen_time').keyup(function(){
                   // regular expression to match required date format
                    re = /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/;
                    if(form1.receive_time.value == '' || !form1.receive_time.value.match(re)||form1.dispen_time.value == '' || !form1.dispen_time.value.match(re)){
                        $('#save').prop('disabled',true);                       
                    }
                    else{ $('#save').prop('disabled',false);}
            });        
     ///////////// checkall ////////////
 $("#checkedAll").change(function(){
    if(this.checked){
      $(".checkSingle").each(function(){
        this.checked=true;
      })              
    }else{
      $(".checkSingle").each(function(){
        this.checked=false;
      })              
    }
  });

  $(".checkSingle").click(function () {
    if ($(this).is(":checked")){
      var isAllChecked = 0;
      $(".checkSingle").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              
      if(isAllChecked == 0){ $("#checkedAll").prop("checked", true); }     
    }else {
      $("#checkedAll").prop("checked", false);
    }
  });	
///////////////////////////////////
   
    });
	
//คีย์ doctorcode แล้วให้ listbox เปลี่ยน
function key_doctor(doctor,list_id)
	{
		if(doctor!=""){
            $('#'+list_id).val(doctor);
        }
            
	}
function list_doctor(doctor,text_id){
        if(doctor!=""){
            $('#'+text_id).val(doctor);
        }
}
</script>
<style>
html,body{overflow:hidden; }
	
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
	
</style>
</head>

<body>
<div class="bg-danger" style="height: 50px; margin-top: -10px;">
<div class="position-absolute" style="margin: 10px;margin-top:15px">	
<input type="checkbox" name="checkedAll" id="checkedAll" /><label for="checkedAll" class="text-white " style="cursor: pointer">&nbsp;เลือกทั้งหมด</label>
</div>
<h6 class="text-white text-center pt-3"><i class="fas fa-check-double"></i>&emsp;บันทึกยาด่วน</h6>
</div>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:93vh;">
<div class="" style="padding: 10px 5px 10px 10px;" >
<form name="form1" method="post" action="emer_drug.php">
<div class="card shadow">
<div class="card-body p-0">
    <table width="100%" class="table table-striped table-hover font12" id="example">
      <tr class="font_bord bg-light" style="border-top: 0px;">
        <td align="center">ลำดับ</td>
        <td align="center">รายการ</td>
        <td align="center">วิธีการใช้</td>
        <td align="center">จำนวน</td>
        <td align="center"><nobr>เวลาสั่ง</nobr></td>
        <td align="center"><nobr>ผู้จ่าย</nobr></td>
        <td align="center"><nobr>ผู้รับ</nobr></td>
        <td align="center"><nobr>วันที่</nobr></td>
        <td align="center"><nobr>เวลารับ</nobr></td>
        <td align="center"><nobr>เวลาจ่าย</nobr></td>
        <td align="center"><nobr>รอคอย(นาที)</nobr></td>
        <td align="center">&nbsp;</td>
      </tr>
      <?php $i=0; do { $i++; 
	  
	  		mysql_select_db($database_hos, $hos);
			$query_emer_drug2 = "select e.*,d.name from  ".$database_kohrx.".kohrx_emergency_drug e left outer join doctor d on d.code=e.doctor where e.vn='".$row_s_drug['vn']."' and e.icode='".$row_s_drug['icode']."' and e.rxtime='".$row_s_drug['rxtime']."'";
			$emer_drug2 = mysql_query($query_emer_drug2, $hos) or die(mysql_error());
			$row_emer_drug2 = mysql_fetch_assoc($emer_drug2);
			$totalRows_emer_drug2 = mysql_num_rows($emer_drug2);
			
			$sum+=timediff($row_emer_drug2['receive_time'],$row_emer_drug2['dispen_time']); if($row_emer_drug2['receive_time']!=""&&$row_emer_drug2['dispen_time']!=""){ $total++;  }
	  ?>
      <tr class="grid4">
        <td align="center"><?php if($totalRows_emer_drug2==0){?><input name="checkbox[]" type="checkbox" id="checkbox[]" class="checkSingle" value="<?php echo $row_s_drug['icode'].$row_s_drug['vn'].$row_s_drug['rxtime']; ?>"/><?php } ?>
        <?php echo $i; ?></td>
        <td ><?php echo $row_s_drug['drugname']; ?></td>
        <td ><span style="border-right: solid 1px #EEE">
          <?php if($row_s_drug['sp_use']=="") {echo "$row_s_drug[shortlist]"; } else { echo "$row_s_drug[sp_name]"; } ?>
          </a></span></td>
        <td align="center"><font color="<?php 
		//ถ้าใช่ insulin ให้หาร 300
		if($row_s_drug['icode']==$row_setting['2']){
		$insulin_cal=300;} 
		//ถ้าไม่ใช่ insulin ไม่ต้องหาร 300
		if($row_s_drug['icode']!=$row_setting['2']){
		$insulin_cal=1;} 
	
	if($row_oapp['date_diff']!=""){if(($row_s_drug['qty']==0&&$row_s_drug['qtycheck']!="")||($row_s_drug['real_use']*$row_oapp['date_diff']!=0&&$row_s_drug['qty']<(($row_s_drug['real_use']*$row_oapp['date_diff'])/$insulin_cal)&&$row_s_drug['qtycheck']!=""&&$row_s_drug['qtycheck']!="")||($row_s_drug['qty']>((($row_s_drug['real_use']*$row_oapp['date_diff'])/$insulin_cal)+$row_setting[25])&&$row_s_drug['qtycheck']!="")){ echo "#FF0000"; } }?>"><strong><?php print $row_s_drug['qty']; ?></strong></font></td>
        <td align="center"><font color="<?php 
		//ถ้าใช่ insulin ให้หาร 300
		if($row_s_drug['icode']==$row_setting['2']){
		$insulin_cal=300;} 
		//ถ้าไม่ใช่ insulin ไม่ต้องหาร 300
		if($row_s_drug['icode']!=$row_setting['2']){
		$insulin_cal=1;} 
	
	if($row_oapp['date_diff']!=""){if(($row_s_drug['qty']==0&&$row_s_drug['qtycheck']!="")||($row_s_drug['real_use']*$row_oapp['date_diff']!=0&&$row_s_drug['qty']<(($row_s_drug['real_use']*$row_oapp['date_diff'])/$insulin_cal)&&$row_s_drug['qtycheck']!=""&&$row_s_drug['qtycheck']!="")||($row_s_drug['qty']>((($row_s_drug['real_use']*$row_oapp['date_diff'])/$insulin_cal)+$row_setting[25])&&$row_s_drug['qtycheck']!="")){ echo "#FF0000"; } }?>"><strong><?php print time4digit($row_s_drug['rxtime']); ?></strong></font></td>
        <td align="center"><nobr><?php echo $row_emer_drug2['name']; ?></nobr></td>
        <td align="center"><nobr><?php echo respondentname($row_emer_drug2['reciever']); ?></nobr></td>
        <td align="center"><nobr><?php echo date_db2th($row_emer_drug2['dispen_date']); ?></nobr></td>
        <td align="center" class="font-weight-bold"><?php echo time4digit($row_emer_drug2['receive_time']); ?></td>
        <td align="center" class="font-weight-bold"><?php echo time4digit($row_emer_drug2['dispen_time']); ?></td>
        <td align="center"><?php echo timediff($row_emer_drug2['receive_time'],$row_emer_drug2['dispen_time']); ?></td>
        <td align="center"><?php if($totalRows_emer_drug2!=0){?><a href="javascript:if(confirm('ต้องการลบรายการนี้จริงหรือไม่?')==true){window.location='emer_drug.php?vn=<?php echo $row_s_drug['vn']; ?>&action=delete&id=<?php echo $row_emer_drug2['id']; ?>&vn=<?php  echo $vn; ?>&hn=<?php  echo $hn; ?>&vstdate=<?php  echo $_GET['vstdate']; ?>'};"><i class="fas fa-times-circle text-danger" style="font-size: 20px;"></i></a><?php } ?></td>
      </tr>
      <?php } while ($row_s_drug = mysql_fetch_assoc($s_drug)); ?>
    </table>
	


   <input name="vn" type="hidden" id="vn" value="<?php echo $_GET['vn']; ?>" />
  <input name="appdate" type="hidden" id="appdate" value="<?php echo $_GET['appdate']; ?>" />
  <input name="vstdate" type="hidden" id="vstdate" value="<?php echo $_GET['vstdate']; ?>" />
  <input name="hn" type="hidden" id="hn" value="<?php echo $_GET['hn']; ?>" />
</div>
</div>
	<div class="fixed-top text-right p-1 mr-5 text-white"><?php if($sum!=0){ ?>เวลารอคอยเฉลี่ย <span class="badge badge-light font16"><?php  echo number_format2($sum/$total);  ?></span> นาที<?php } ?></div>
    <div class="card mt-2" >
        <div class="card-body" style="background-color:gainsboro; padding-bottom: 0px;">
            <div class="form-group row">
                <label class="col-form-label col-form-label-sm col-sm-auto">จ่ายให้กับ</label>
                <div class="col-sm-auto">
                  <input name="respondent" autofocus  type="text" class="form-control form-control-sm" id="respondent" style="width:30px; padding-left: 3px" onkeyup="key_doctor(this.value,'respondent_list');" onkeypress="return isNumberKey(event);" value="<?php if($totalRows_rs_edit<>0){ echo $row_rs_edit['respondent']; }else { echo "1"; } ?>" />
                </div>
                <div class="col-sm-auto">
            <select name="respondent_list" class="form-control form-control-sm" style="padding: 3px;" id="respondent_list"  onchange="list_doctor(this.value,'respondent')" onkeydown="setNextFocus('answer');">
              <?php
do {  
?>
              <option value="<?php echo $row_rs_respondent['id']?>"<?php if (!(strcmp($row_rs_respondent['id'], $row_rs_edit['respondent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs_respondent['respondent']?></option>
              <?php
} while ($row_rs_respondent = mysql_fetch_assoc($rs_respondent));
  $rows = mysql_num_rows($rs_respondent);
  if($rows > 0) {
      mysql_data_seek($rs_respondent, 0);
	  $row_rs_respondent = mysql_fetch_assoc($rs_respondent);
  }
?>
          </select>
                </div>
                <label class="col-form-label col-form-label-sm col-sm-auto">จ่ายให้กับ</label>
                <div class="col-sm-auto">
                  <input name="rx_check" type="text" class="form-control form-control-sm" style="padding: 3px"  id="rx_check" onkeyup="key_doctor(this.value,'doctorcheck');" onkeypress="return isNumberKey(event);" value="<? if($row_rs_edit['doctorcode']!=""){ echo $row_rs_edit['doctorcode']; } else {echo $_SESSION['doctorcode']; } ?>" size="2" <?php if($row_rs_edit['doctorcode']!=""){ echo "style=\"background-color:#FC0\""; } ?>  />          
                </div>
                <div class="col-sm-auto">
                  <select name="doctorcheck" class="form-control form-control-sm" style="padding: 3px" id="doctorcheck" onchange="list_doctor(this.value,'rx_check')" onkeydown="setNextFocus('remark');" >
                          <?php
            do {  
            ?>
                          <option value="<?php echo $row_rs_doctor['doctorcode']?>"<?php if($row_rs_edit['doctorcode']!=""){ if (!(strcmp($row_rs_doctor['doctorcode'],$row_rs_edit['doctorcode']))) {echo "selected=\"selected\"";}} else { if (!(strcmp($row_rs_doctor['doctorcode'],$_SESSION['doctorcode']))) {echo "selected=\"selected\"";}}  ?>><?php echo $row_rs_doctor['name']?></option>
                          <?php
            } while ($row_rs_doctor = mysql_fetch_assoc($rs_doctor));
              $rows = mysql_num_rows($rs_doctor);
              if($rows > 0) {
                  mysql_data_seek($rs_doctor, 0);
                  $row_rs_doctor = mysql_fetch_assoc($rs_doctor);
              }
            ?>
                        </select>

                </div>
                <label class="col-form-label-sm">เวลารับ</label>
                <div class="col-sm-auto">
                  <input type="text" id="receive_time" name="receive_time" class="form-control form-control-sm" style="padding: 3px;width: 60px" />    
                </div>
                <label class="col-form-label-sm">เวลาจ่าย</label>
                <div class="col-sm-auto">
                  <input type="text" id="dispen_time" name="dispen_time" class="form-control form-control-sm" style="padding: 3px;width: 60px" />    
                </div>                
                <div class="col-sm-auto">
                  <input type="submit" name="save" id="save" value="บันทึก" class="btn btn-danger">  
                </div>                
            </div>
        </div>
    </div>

	</form>

</div>
</div>	
</body>
</html>
<?php
mysql_free_result($s_drug);

mysql_free_result($rs_doctor);

mysql_free_result($rs_setting);

mysql_free_result($rs_respondent);


?>
