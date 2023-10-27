<?php require_once('Connections/hos.php'); ?>
<?php
if($_POST['action']=="search"){
	
		if($_POST['dateselect']=="Y"){
			if($_POST['pttype']=="1"){
				$search=" and ipt.regdate between '".$_POST['date1']."' and '".$_POST['date2']."' and ipt.dchdate is NULL";
			}
			else if($_POST['pttype']=="2"){
				$search=" and ipt.dchdate between '".$_POST['date1']."' and '".$_POST['date2']."'";
			} 
		}
		if($_POST['dateselect']=="N"){
				$search="";
		}
		
        if($_POST['ward']!=""){
            $search.=" and ipt.ward='".$_POST['ward']."'";
        }
	
}

include('include/function.php');


mysql_select_db($database_hos, $hos);
$query_ipt = "select ipt.an,ipt.hn,ipt.vn,ipt.regdate,ipt.regtime,iptadm.bedno,roomno.name as room,concat(patient.pname,patient.fname,' ',patient.lname) as name,aa.age_y,aa.age_m,ward.name as wardname
from ipt left outer join iptadm on iptadm.an=ipt.an left outer join patient on patient.hn=ipt.hn left outer join roomno on roomno.roomno=iptadm.roomno left outer join an_stat aa on aa.an=ipt.an left outer join ward w on w.ward = ipt.ward left outer join ward on ward.ward=roomno.ward
  where ipt.dchstts is null ".$search."   order by ipt.an";
//echo $query_ipt;
$ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
$row_ipt = mysql_fetch_assoc($ipt);
$totalRows_ipt = mysql_num_rows($ipt);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
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
    
<?php include('include/bootstrap/datatable_report.php'); ?>    
</head>

<body>
<table  id="example" class="table table-striped table-bordered table-hover table-sm" style="width:100%; font-size:14px" >
  <thead>
    <tr>
      <td align="center" >ลำดับ</td>
      <td align="center" >วันที่</td>
      <td align="center" >AN</td>
      <td align="center" >ชื่อ</td>
      <td align="center" >ประเภทสี</td>
      <td align="center" >ATK</td>
      <td align="center" >RT PCR</td>
      <td align="center" >PCR date</td>
      <td align="center" >ตรวจที่</td>
      <td align="center" >วันครบกำหนดกักตัว</td>
    </tr>
  </thead>
  <tbody>
    <?php  $i=0; do { $i++;	  ?>
    <?php 
                     
        mysql_select_db($database_hos, $hos);         
        $query_ue = "select ud170.universal_item_value_date as enddate,ud171.universal_item_value_text as pttype,ud172.universal_item_value_text as detect_result,ud173.universal_item_value_date as detect_date,ud178.universal_item_value_text as hos_reportor,ud179.universal_item_value_text as ptstatus,ud180.universal_item_value_text as atk from universal_form uf left outer join universal_head uh on uh.universal_form_id=uf.universal_form_id left outer join universal_detail ud170 on ud170.universal_head_id=uh.universal_head_id and ud170.universal_item_id='170' left outer join universal_detail ud171 on ud171.universal_head_id=uh.universal_head_id and ud171.universal_item_id='171' left outer join universal_detail ud172 on ud172.universal_head_id=uh.universal_head_id and ud172.universal_item_id='172' left outer join universal_detail ud173 on ud173.universal_head_id=uh.universal_head_id and ud173.universal_item_id='173' left outer join universal_detail ud178 on ud178.universal_head_id=uh.universal_head_id and ud178.universal_item_id='178' left outer join universal_detail ud179 on ud179.universal_head_id=uh.universal_head_id and ud179.universal_item_id='179' left outer join universal_detail ud180 on ud180.universal_head_id=uh.universal_head_id and ud180.universal_item_id='180' where uf.universal_form_id='10' and uh.vn='".$row_ipt['an']."' order by uh.universal_head_id DESC limit 1";
        //echo $query_ue;
        $ue = mysql_query($query_ue, $hos) or die(mysql_error());
        $row_ue = mysql_fetch_assoc($ue);
        $totalRows_ue = mysql_num_rows($ue);
        
        switch ($row_ue['ptstatus']) {
          case '1':
            $ptcolor="#008000";
            $ptcolor1="เขียวเข้ม";            
            break;
          case '2':
            $ptcolor="#80ff80";
            $ptcolor1="เขียวอ่อน";            
            break;
          case '3':
            $ptcolor="#ffff00";
            $ptcolor1="เหลือง";            
            break;
          case '4':
            $ptcolor="#e62e00";
            $ptcolor1="แดง";            
            break;
          default:
            $ptcolor="#ffffff";
            $ptcolor1="ไม่ได้ประเมิน";            
}   
        switch ($row_ue['atk']) {
          case '1':
            $atk="ไม่ได้ตรวจ";
            break;
          case '2':
            $atk="positive";
            break;
          case '3':
            $atk="negative";
            break;
          default:
            $atk="ไม่ทราบ";
}                        
        switch ($row_ue['detect_result']) {
          case 'N':
            $pcr="negative";
            break;
          case 'Y':
            $pcr="positive";
            break;
          default:
            $pcr="ไม่ทราบ";
}                        
      ?>  
    <tr class="grid2">
      <td align="center" ><?php echo $i; ?></td>
      <td align="center" ><?php echo date_db2th($row_ipt['regdate']); ?></td>
      <td align="center" ><?php echo $row_ipt['an']; ?></td>
      <td align="center" ><?php echo $row_ipt['name']; ?></td>
      <td align="center" ><?php echo $ptcolor1; ?>&nbsp;<span class="badge rounded-circle" style="border: solid 1px #777373;background-color:<?php echo $ptcolor; ?>">&nbsp;</span></td>
      <td align="center" ><?php echo $atk; ?></td>
      <td align="center" ><?php echo $pcr; ?></td>
      <td align="center" ><?php echo date_db2th($row_ue['detect_date']); ?></td>
      <td align="center" ><?php echo $row_ue['hos_reportor']; ?></td>
      <td align="center" ><?php echo date_db2th($row_ue['enddate']); ?></td>
    </tr>
    <?php mysql_free_result($ue); ?>  
    <?php } while ($row_ipt = mysql_fetch_assoc($ipt)); ?>
  </tbody>
</table>
</body>
</html>
<?php mysql_free_result($ipt); ?>