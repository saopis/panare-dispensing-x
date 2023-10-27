<?php require_once('../Connections/hos.php'); ?>
<?php
if($_GET['do']=="delete"){
mysql_select_db($database_hos, $hos);
$query_delete = "delete FROM ".$database_kohrx.".kohrx_med_error_report  where id='".$_GET['id']."'";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

echo "<script>resultSearch();</script>";
}

if($_GET['do']=="load"){
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$category1=$_GET['category1'];
$med_error_type1=$_GET['med_error_type1'];
$cause_id1=$_GET['cause_id1'];
$sub_id1=$_GET['sub_id1'];
$ptype1=$_GET['ptype1'];
$room_id=$_GET['room_id'];

if($category1!=""){
    $condition=" and r.category='".$category1."'";
}
if($med_error_type1!=""){
    $condition.=" and r.error_type='".$med_error_type1."'";
}
if($cause_id1!=""||$cause_id1!=NULL){
    $condition.=" and r.error_cause='".$cause_id1."'";
}
if($sub_id1!=""||$sub_id1!=NULL){
    $condition.=" and r.error_subtype='".$sub_id1."'";
}
if($ptype1!=""){
	$condition.=" and ptype='".$ptype1."'";
}
if($room_id!=""){
	$condition.=" and r.room_id='".$room_id."'";
}

mysql_select_db($database_hos, $hos);
$query_rs_result = "SELECT r.id,r.time,r.hn,r.reporter,r.error_person,r.ptype,r.reciew, r.detail,
r.`date`, r.dep_report,r.dep_error, r.category,r.drugtype,d1.name as reporter,d2.name as error,r.suggest,r.error_type,r.error_cause,r.error_subtype,r.error_other,r.pharmacist   
FROM ".$database_kohrx.".kohrx_med_error_report  r
left outer join doctor d1 on d1.code=r.reporter
left outer join doctor d2 on d2.code=r.error_person where r.date between '".$startdate."' and '".$enddate."' ".$condition." order by r.date DESC";
$rs_result = mysql_query($query_rs_result, $hos) or die(mysql_error());
$row_rs_result = mysql_fetch_assoc($rs_result);
//echo $query_rs_result;
$totalRows_rs_result = mysql_num_rows($rs_result);

}

include('../include/function.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
    function editResult(id,error_type,error_cause,error_subtype){
        $("#medform").load('med_form.php?rid='+id+'&do=edit', function(responseTxt, statusTxt, xhr){
                   
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");
						//cause
							
							var dataString = 'id='+error_type+'&type=main&error_id='+error_cause;
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
			
							//subtype
							var dataString = 'id='+error_cause+'&type=sub&error_id='+error_subtype;
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

			
				if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });  

    }
    
	function delReport(id){
        $("#result_list").load('result_list.php?do=delete&id='+id, function(responseTxt, statusTxt, xhr){
                   
                    if(statusTxt == "success")
                      //alert("External content loaded successfully!");			
					  $("#medform").load('med_form.php', function(responseTxt, statusTxt, xhr){
                   
							if(statusTxt == "success")
							  //alert("External content loaded successfully!");
								$('#indicator').hide();

							if(statusTxt == "error")
							  alert("Error: " + xhr.status + ": " + xhr.statusText);
					});  	
					if(statusTxt == "error")
					alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
		
		

    }

</script>
</head>

<body>
<?php if($totalRows_rs_result<>0){ ?>
   <table width="100%" class="table table-striped  table-hover font12">
  <thead class="thead-dark">
  <tr>
    <td align="center" ><span >ลำดับ</span></td>
    <td align="center" >วันที่</td>
    <td align="center" >รายละเอียด</td>
    <td align="center" >Cat.</td>
    <td align="center" >&nbsp;</td>
    </tr>
</thead>
<tbody>
  <? $i=0; do { $i++;   
  ?><tr >
    <td align="center" ><?=$i; ?></td>
    <td align="center" valign="top" ><?php echo date_db2th($row_rs_result['date']); ?></td>
    <td align="left" ><?php if(strlen($row_rs_result['detail'])<= 50){ echo $row_rs_result['detail'];} else {echo iconv_substr($row_rs_result['detail'],0,50,'UTF-8')."..."; }?></td>
    <td align="center" ><?php echo $row_rs_result['category']; ?></td>
    <td align="center" ><nobr><a href="javascript:editResult('<?php echo $row_rs_result['id']; ?>','<? echo $row_rs_result['error_type']; ?>','<? echo $row_rs_result['error_cause']; ?>','<? echo $row_rs_result['error_subtype']; ?>')"><i class="fas fa-pen-alt font20 text-dark"></i></a>&nbsp;<a href="javascript:if(confirm('ต้องการลบรายการความคลาดเคลื่อนนี้จริงหรือไม่')==true){ delReport('<?php echo $row_rs_result[id]; ?>') }"><i class="fas fa-eraser font20 text-danger"></i></a>&nbsp;<i class="fas fa-print font20 text-primary" onclick="window.open('result3.php?report_id=<?php echo $row_rs_result['id']; ?>','_new')"></i></nobr></td>
    </tr>

  <? } while($row_rs_result = mysql_fetch_assoc($rs_result)); ?>
</tbody>
</table> 
<?php } else { ?>
<div class="alert alert-dark" role="alert">
ไม่พบข้อมูล/ไม่มีข้อมูล
</div>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($rs_result);
?>