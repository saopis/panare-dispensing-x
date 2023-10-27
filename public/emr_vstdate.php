<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');
include('include/get_channel.php');

//ถ้ามีการเปิดใช้ image server ให้แนบ connection img.php มาด้วย
	if($row_setting[43]=="Y"){
		require_once('Connections/img.php');
	}

mysql_select_db($database_hos, $hos);
$query_rs_vstdate = "select vstdate,hn,vn from vn_stat where hn='".$_GET['hn']."' order by vstdate DESC";
$rs_vstdate = mysql_query($query_rs_vstdate, $hos) or die(mysql_error());
$row_rs_vstdate = mysql_fetch_assoc($rs_vstdate);
$totalRows_rs_vstdate = mysql_num_rows($rs_vstdate);

mysql_select_db($database_hos, $hos);
$query_rs_regdate = "select regdate,an,hn from an_stat where hn='".$_GET['hn']."' order by regdate DESC";
$rs_regdate = mysql_query($query_rs_regdate, $hos) or die(mysql_error());
$row_rs_regdate = mysql_fetch_assoc($rs_regdate);
$totalRows_rs_regdate = mysql_num_rows($rs_regdate);

mysql_select_db($database_hos, $hos);
$query_rs_lab = "SELECT vn,order_date,concat(DATE_FORMAT(order_date,'%d/%m/'),(DATE_FORMAT(order_date,'%Y'))+543) as order_date1 FROM lab_head WHERE hn='".$_GET['hn']."' GROUP BY order_date order by order_date DESC";
$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
$row_rs_lab = mysql_fetch_assoc($rs_lab);
$totalRows_rs_lab = mysql_num_rows($rs_lab);

include('include/function_sql.php');

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>

    <style>
        html,body{ 
        margin-left: 0;
        margin-bottom: 0;
        margin-right: 0;
        margin-top: 0;
        
        }
    </style>
<script>
$(document).ready(function(){    
//====== select  date============//
    $('#select-vstdate').change(function(){
				$('#select-regdate').val("0"); 
				$('#select-orderdate').val("0"); 
                if($(this).val()!="0"){
					$('#indicator').fadeIn(1000);
				
                    $('#emr_right').load('emr_detail.php?vn='+$(this).val(), function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(2000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
				}
    });
//========== regdate ============//
$('#select-regdate').change(function(){
				$('#select-vstdate').val("0"); 
				$('#select-orderdate').val("0"); 
                if($(this).val()!="0"){
					$('#indicator').fadeIn(1000);
				
                    $('#emr_right').load('emr_detail_ipd.php?an='+$(this).val(), function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(2000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
				}
    });
//========== orderdate ============//
$('#select-orderdate').change(function(){
				$('#select-regdate').val("0"); 
				$('#select-vstdate').val("0"); 
                if($(this).val()!="0"){
					$('#indicator').fadeIn(1000);
				
                    $('#emr_right').load('emr_detail_lab.php?vn='+$(this).val(), function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(2000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
				}
    });

});
    
</script>
    
</head>

<body>
<div class="card border-0">
    <div class="card-body p-1">
	<div align="center">
        <?
	
		$ptname=ptname($_GET['hn']);
	

	////////// patient name //////////
	
	//ถ้าใช้ image server
	if($row_setting[43]=="Y"){
		mysql_select_db($database_img, $img);
	}
	else{ //กรณีไม่ใช้ image server
		mysql_select_db($database_database, $hos);
	}

 	$query_selpic = "select count(*) as cc from patient_image  where hn='".$_GET['hn']."' ";
	if($row_setting[43]=="Y"){ 	//ถ้าใช้ image server
		$selpic = mysql_query($query_selpic, $img) or die(mysql_error());
	}
	else{ //กรณีไม่ใช้ image server
		$selpic = mysql_query($query_selpic, $hos) or die(mysql_error());
	}
	$row_selpic = mysql_fetch_assoc($selpic);
	$totalRows_selpic = mysql_num_rows($selpic);
			if($row_selpic['cc']>0){
				if($row_setting[43]=="Y"){
					mysql_select_db($database_img, $img);
				}
				else{ //กรณีไม่ใช้ image server
					mysql_select_db($database_database, $hos);
				}					
				$query = "SELECT image as blob_img FROM patient_image where hn='".$_GET['hn']."' "; 
				if($row_setting[43]=="Y"){ 	//ถ้าใช้ image server
					$result = mysql_query($query, $img) or die(mysql_error()); 
				}
				else{ //กรณีไม่ใช้ image server
					$result = mysql_query($query, $hos) or die(mysql_error()); 
				}				

				$row = mysql_fetch_array($result); 
				$jpg = $row["blob_img"]; 
	mysql_free_result($selpic);
							?>
							<img src="data:image/jpeg;base64,<?php echo base64_encode($jpg); ?> "  width="100" height="120" vlign="middle" border="0" style="border-radius: 8px; border:solid 1px #E3E1E1"> <?php 
						}
						else {
							echo "<img src=\"images/noimage.png\" width=\"80\" height=\"94\" />";
							}
							?>
        </div>
        <div align="center">
<?php echo $row_pt['patient_name']; ?><br />
	
	HN <?php echo $row_pt['hn']; ?>

    <div style="margin-bottom: 5px;">
    </div>


        </div>    
    </div>
</div>
<div class="card mt-1">
    <div class="card-header" style="padding:5px;"><nobr>&nbsp;วันที่รับบริการ <i class="fas fa-walking font20">&ensp;</i><span class="badge badge-dark font18">OPD</span></nobr></div>
        <div class="card-body " style="padding: 5px" >
        <select class="form-control" id="select-vstdate" style="background-color:#E7E7E7">
           <option value="0">-- เลือกวันที่ --</option>
		   <?php do{
			mysql_select_db($database_hos, $hos);
$query_rs_vstdate2 = "select regdate from an_stat where hn='".$_GET['hn']."' and regdate='".$row_rs_vstdate['vstdate']."'";
$rs_vstdate2 = mysql_query($query_rs_vstdate2, $hos) or die(mysql_error());
$row_rs_vstdate2 = mysql_fetch_assoc($rs_vstdate2);
$totalRows_rs_vstdate2 = mysql_num_rows($rs_vstdate2);
	       ?>
        	<option class="text-<?php if($row_rs_vstdate2['regdate']==$row_rs_vstdate['vstdate']){ echo "danger"; } else { echo "dark"; } ?>" value="<?php echo ($row_rs_vstdate['vn']); ?>"><?php echo dateThai($row_rs_vstdate['vstdate']); ?></option>
            <?php
				mysql_free_result($rs_vstdate2);
			 }while($row_rs_vstdate = mysql_fetch_assoc($rs_vstdate)); ?>
        </select>            
    	</div>
</div>
<div class="card mt-2">
    <div class="card-header" style="padding:5px;"><nobr>&nbsp;วันที่รับบริการ <i class="fas fa-procedures">&ensp;</i><span class="badge badge-dark font18">IPD</span></nobr></div>
        <div class="card-body " style="padding: 5px" >
        <select class="form-control" id="select-regdate" style="background-color:#E7E7E7">
           <option value="0">-- เลือกวันที่ --</option>

           <?php if($totalRows_rs_regdate<>0){ ?>
            <?php do{	       ?>
        	<option value="<?php echo ($row_rs_regdate['an']); ?>"><?php echo dateThai($row_rs_regdate['regdate']); ?></option>
            <?php
			 }while($row_rs_regdate = mysql_fetch_assoc($rs_regdate)); ?>
            <?php } ?>
        </select>            
    	</div>
</div>
<div class="card mt-2">
    <div class="card-header" style="padding:5px;"><nobr>&nbsp;วันที่รับบริการ <i class="fas fa-microscope">&ensp;</i><span class="badge badge-dark font18">LAB</span></nobr></div>
        <div class="card-body " style="padding: 5px" >
        <select class="form-control" id="select-orderdate" style="background-color:#E7E7E7">
           <option value="0">-- เลือกวันที่ --</option>
            <?php if($totalRows_rs_lab<>0){ ?>
           <?php do{	       ?>
        	<option value="<?php echo ($row_rs_lab['vn']); ?>"><?php echo dateThai($row_rs_lab['order_date']); ?></option>
            <?php
			 }while($row_rs_lab = mysql_fetch_assoc($rs_lab)); ?>
            <?php } ?>
        </select>            
    	</div>
</div>

</body>
</html>
<?php mysql_free_result($rs_vstdate); ?>
<?php mysql_free_result($rs_regdate); ?>
<?php mysql_free_result($rs_lab); ?>