<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php 
include('include/function.php');
mysql_select_db($database_hos, $hos);
$query_ipt = "select ipt.an,ipt.hn,ipt.vn,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,dt.name as admdoctor_name ,aa.age_y,aa.age_m,ward.name as wardname from ipt left outer join spclty on spclty.spclty=ipt.spclty left outer join iptadm on iptadm.an=ipt.an left outer join patient on patient.hn=ipt.hn left outer join doctor dt on dt.code = ipt.admdoctor left outer join roomno on roomno.roomno=iptadm.roomno left outer join iptdiag on iptdiag.an=ipt.an and iptdiag.diagtype='1' left outer join icd101 i1 on i1.code=substring(iptdiag.icd10,1,3) left outer join an_stat aa on aa.an=ipt.an left outer join ward w on w.ward = ipt.ward left outer join ipt_finance_status fs on fs.an = ipt.an left outer join finance_status ft on ft.finance_status = fs.finance_status left outer join pttype ptt on ptt.pttype=ipt.pttype left outer join ward on ward.ward=roomno.ward where ipt.dchstts is null and ipt.hn is not null order by ipt.an ASC";
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
//$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script>
function alertload(url,w,h){
	 $.colorbox({fixed:true,width:w,height:h, iframe:true, href:url, onOpen : function () {$('html').css('overflowY','hidden');},onCleanup :function(){
$('html').css('overflowY','auto');}
,onClosed:function(){ }});

} 
function reloadCheck () {
	//alert();
     $('#check_login').load('check_login_expire2.php?page=main');
}
function loadModal(){
	$('#myModal-danger').modal({show:true});
}   
$(document).ready(function(){
		reloadCheck();
	    setInterval(reloadCheck, 10000); 

});   
</script>    
</head>

<body>	
<table width="100%" class="table table-sm table-striped table-hover">
  <tr class="font14 bg-dark text-white" >
    <td align="center" ><strong>ลำดับ</strong></td>
    <td align="center" ><strong>AN</strong></td>
    <td align="center" ><strong>HN</strong></td>
    <td align="center" ><strong>ชื่อ - นามสกุล </strong></td>
    <td align="center" ><strong>แพ้ยา</strong></td>
    <td align="center" ><strong>อายุ <?php echo $_SESSION['doctorcode']; ?></strong></td>
    <td align="center" ><strong>regdate</strong></td>
    <td align="center" ><strong>time</strong></td>
    <td align="center" ><strong>เตียง </strong></td>
    <td align="center" ><strong>ห้อง</strong></td>
    <td align="center" ><strong>ตึก</strong></td>
    <td align="center" ><strong>profile</strong></td>
  </tr>
  <? for($i=1;$i<=$totalRows_ipt;$i++){ $row_ipt = mysql_fetch_assoc($ipt); 
 mysql_select_db($database_hos, $hos);
$query_allergy = "select agent,symptom from opd_allergy where hn = '$row_ipt[hn]'";
$allergy = mysql_query($query_allergy, $hos) or die(mysql_error());
$row_allergy = mysql_fetch_assoc($allergy);
$totalRows_allergy = mysql_num_rows($allergy);

									   
  ?>
  <tr onClick="parent.an_search('<?php echo $row_ipt['an']; ?>');parent.$.fn.colorbox.close();" class="font14">
    <td align="center"><?php echo $i; ?>&nbsp;</td>
    <td align="center"><a  > <?php echo $row_ipt['an']; ?></a></td>
    <td align="center"><a  > <?php echo $row_ipt['hn']; ?></a></td>
    <td align="left" ><a  > <strong><?php echo $row_ipt['name']; ?></strong></a></td>
    <td align="center"><?php if($totalRows_allergy<>0){  ?>
      <span class="badge badge-danger font14" onclick="MM_openBrWindow('allergy.php?hn=<?php echo $row_ipt['hn']; ?>','','scrollbars=yes,width=500,height=500')">แพ้ยา</span>
      <? } ?></td>
    <td align="center"><?php echo $row_ipt['age_y']; ?> ปี <?php echo $row_ipt['age_m']; ?> เดือน </td>
    <td align="center"><?php echo dateThai3($row_ipt['regdate']); ?></td>
    <td align="center"><?php echo substr($row_ipt['regtime'],0,5); ?></td>
    <td align="center"><span class="badge badge-dark font14" style="width: 50px;" ><?php echo $row_ipt['bedno']; ?></span></td>
    <td align="center"><?php echo $row_ipt['room']; ?></td>
    <td align="center"><?php echo $row_ipt['wardname']; ?></td>	 
    <td align="center"><button class="btn btn-success btn-sm" onClick="alertload('detail_ipd_profile_check.php?an=<?php echo $row_ipt['an']; ?>','100%','100%')">Profile</button></td>	 
  </tr>
  <? mysql_free_result($allergy);} ?>
</table>  
<div id="check_login"></div> 
<!-- The Modal primary-->
  <div class="modal fade" id="myModal-danger" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="modal-title-danger">ระบบไม่พบการ login เข้าใช้งานของท่าน</h5>
          
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal-body-danger" >
			<div>ขณะนี้โปรแกรมไม่พบผู้ใช้งานเนื่องจากเวลาของการ login ได้หมดลง</div>
			<div>กรุณา login เข้าใช้งานในหน้าจ่ายยาและกดปุ่ม "ปิด" หากได้ login แล้ว</div>
		  </div>
        
        
      </div>
    </div>
  </div>	
<!-- MODAL danger -->	
</body>
</html>