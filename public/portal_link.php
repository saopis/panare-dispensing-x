<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?> 
<style>
html,body { height:100%; background-color:#E0E0E0 }

::-webkit-scrollbar { width: 15px; }

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
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#"><i class="fas fa-spinner font20 text-warning"></i>&ensp;PORTAL REPORT</a>
</nav>
<div style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:83vh; padding: 20px; ">
<div class="row">
<div class="col-6 border rounded bg-white" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:50vh; border: solid 1px #878383; padding: 0px;">
<div class="card m-2">
  <div class="card-header">
    <i class="fas fa-file-invoice font20"></i>&ensp;System Report (ระบบรายงานหลัก)
  </div>
  <div class="card-body">
  <ul class="list-group list-group-flush" >
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_opd_report.php','_new');">1. รายงานจ่ายยาผู้ป่วยนอก (Dispen OPD report)</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_ipd_report.php','_new');">2. รายงานจ่ายยาผู้ป่วยใน (Dispen IPD report)</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_counselling_report.php','_new');">3. รายงานการให้คำปรึกษาด้านยา (Couselling report)</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_kohrx_drp_report.php','_new');">4. รายงานปัญหาจากการใช้ยา (KOHRX DRP report)</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_drp_report.php','_new');">5. รายงานปัญหาจากการใช้ยา (DRP report)</li>
	<li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_creatinine_incident_report.php','_new');">6. รายงานบันทึกอุบัติการณ์การสั่งยาที่มีผลกับ creatinine</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_insulin_report.php','_new');">7. รายงานบันทึกเข็ม insulin</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_refuse_report.php','_new');">8. รายงานการปฏิเสธรับยา</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('service_drug_return.php','_new');">9. รายงานบันทึกยาคืนจากผู้ป่วย</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_drug_payable_report.php','_new');">10. รายงานบันทึกค้างจ่ายยาผู้ป่วย</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_elder_risk_report.php','_new');">11. รายงานยาที่ต้องระมัดระวังในผู้ป่วยสูงอายุ</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_drug_allergy_report.php','_new');">12. รายงานผู้ป่วยแพ้ยา</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_allergy_check_report.php','_new');">13. รายงานการซักถามการแพ้ยา</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_drug_caution_report.php','_new');">14. รายงานอุบัติการณ์ยาที่ห้ามสั่งใช้ใน ICD10 ที่กำหนด</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_due_report.php','_new');">15. รายงานการใช้ยา DUE</li>  
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_had_report.php','_new');">16. รายงานยาความเสี่ยงสูง</li>  
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_patient_warfarin_report.php','_new');">17. รายชื่อผู้ป่วยใช้ยา warfarin</li>  
        <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_patient_g6pd_report.php','_new');">18. รายชื่อผู้ป่วย G6PD</li>  
        <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_emergency_drug_report.php','_new');">19. รายงานการจ่ายยาด่วน</li>  
        <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_drug_count_report.php','_new');">20. รายงานการจ่ายยารายตัว</li>  
        <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_log_search_report.php','_new');">21. รายงานการค้นหา Drug Logs</li>  
</ul>
</div>
</div>
</div>
<div class="col-6">
<div class="card">
  <div class="card-header">
    <i class="fas fa-file-invoice font20"></i>&ensp;Medication Error Report (รายงานความคลาดเคลื่อนทางยา)
  </div>
  <ul class="list-group list-group-flush" >
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('mederror_usage_report.php','_new');">1. รายงานการสั่งยาผิดวิธีใช้</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('mederror_usage_syrup_report.php','_new');">2. รายงานการสั่งยาผิดขนาดในเด็ก</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('mederror_qty_report.php','_new');">3. รายงานการสั่งยาผิดจำนวน</li>
    <li class="list-group-item list-group-item-action cursor" onClick="window.open('dispen_med_reconcile_error_report.php','_new');">4. med. reconcile error</li>
  </ul>
</div>
</div>
	
</div>
</div>

</body>
</html>