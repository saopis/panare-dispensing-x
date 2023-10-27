<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php
include('include/function.php');

mysql_select_db($database_hos, $hos);
$query_rs_pt = "select concat(pname,fname,' ',lname) as patient_name from patient where hn='".$_GET['hn']."'";
$rs_pt = mysql_query($query_rs_pt, $hos) or die(mysql_error());
$row_rs_pt = mysql_fetch_assoc($rs_pt);
$totalRows_rs_pt = mysql_num_rows($rs_pt);
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script>

                $(document).ready(function() {
                    $('#indicator').show();
                    $('#emr_left').load('emr_vstdate.php?hn=<?php echo $_GET['hn']; ?>', function(responseTxt, statusTxt, xhr){
                        if(statusTxt == "success")
                          //alert("External content loaded successfully!");
                            $('#indicator').fadeOut(1000);
                        if(statusTxt == "error")
                            alert("Error: " + xhr.status + ": " + xhr.statusText);    
                    });
                ///data-table
                    $('#data-table').DataTable( {
                            scrollY:        '50vh',
                            scrollCollapse: true,
                            paging:         false
                        } );


                });

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
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-info text-white fixed-top">
  <!-- Navbar content -->
    <span class="font18"><i class="fas fa-history font20"></i>&ensp;ประวัติรับบริการ : <span class="font_bord"><?php echo $row_rs_pt['patient_name']; ?></span></span>
</nav>
<div class="container-fluid" style="margin-top: 40px;" >
<div class="row">
    <div class="col" id="emr_left" style="padding: 10px;-ms-flex: 0 0 200px;flex: 0 0 200px;"></div>
    <div class="col" id="emr_right" style="margin-top: 5px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:80vh;"></div>  
        <!-- indicator -->
        <div id="indicator" align="center" class="spinner position-absolute" style="top:40%; left: 45%">
                <button class="btn btn-light" type="button" style="width:200px;" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  <span >กำลังโหลด..</span>
                </button>
        </div>
        <!-- indicator -->
</div>
</div>
</body>
</html>
<?php mysql_free_result($rs_pt); ?>