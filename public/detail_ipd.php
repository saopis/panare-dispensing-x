<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');
$_SESSION['pt']="IPD";
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script src="https://cdn.datatables.net/fixedcolumns/4.0.1/js/dataTables.fixedColumns.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.0.1/css/fixedColumns.dataTables.min.css"/>    
    
<script>

                $(document).ready(function() {
                    $('#indicator').show();
                    $('#ipd_left').load('detail_ipd_left.php?an=<?php echo $_GET['an']; ?>', function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(1000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
                    $('#ipd_right').load('detail_ipd_profile.php?an=<?php echo $_GET['an']; ?>&action=<?php echo $_GET['action']; ?>&respondent=<?php echo $_GET['respondent']; ?>&answer=<?php echo $_GET['answer']; ?>', function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(1000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });


                });
function ipd_right_load(an){
	                    $('#ipd_right').load('detail_ipd_profile.php?an='+an+'&action=<?php echo $_GET['action']; ?>&respondent=<?php echo $_GET['respondent']; ?>&answer=<?php echo $_GET['answer']; ?>', function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(1000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
}
function resutName(icode,doctor)
	{
		if(icode!=""){
		switch(icode)
		{
			<?
			mysql_select_db($database_hos, $hos);

			$strSQL = "SELECT o.name,o.doctorcode FROM opduser o left outer join doctor d on d.code=o.doctorcode where d.active='Y'";
			$objQuery = mysql_query($strSQL);
			while($objResult = mysql_fetch_array($objQuery))
			{
			?>
				case "<?=$objResult["doctorcode"];?>":
				document.getElementById(doctor).value = "<?=$objResult["doctorcode"];?>";
							
				break;
			<?
			}
            mysql_free_result($objQuery);
			?>
			default:
			 document.getElementById(doctor).value = "0066";
		}
		}
	}
</script>
<style>
    html,body{overflow-x: hidden; overflow-y:hidden;
	}
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
.column {
  float: left;
  
}

.left {
  width: 200px;
}

.right {
  width: auto;
}
</style>
</head>

<body>
<div class="row " style="height: 100%; border-top: 1px solid #BFBFBF">
    <div class="column left" id="ipd_left" style=" background-color:#D5DBE8"></div>
    <div class="col " id="ipd_right" style="margin-right: 10px; " ></div>  
</div>
</body>
</html>
