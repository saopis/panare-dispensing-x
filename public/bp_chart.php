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

mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_select_db($database_hos, $hos);
$query_rs_lab = "select * from (select vstdate,vsttime,bps,bpd from opdscreen where hn='".$_GET['hn']."' and vsttime is not NULL and (bps or bpd) !=0  order by vstdate DESC limit 20) as s order by s.vstdate ASC";
$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
$row_rs_lab = mysql_fetch_assoc($rs_lab);
$totalRows_rs_lab = mysql_num_rows($rs_lab);

//
$rs_lab2 = mysql_query($query_rs_lab, $hos) or die(mysql_error());
$row_rs_lab2 = mysql_fetch_assoc($rs_lab2);
$totalRows_rs_lab2 = mysql_num_rows($rs_lab2);


?>
<HTML>
<HEAD>
	<TITLE>
	FusionCharts Free - Database Example
	</TITLE>
	<SCRIPT LANGUAGE="Javascript" SRC="include/FusionCharts/FusionCharts.js"></SCRIPT>
	<style type="text/css">
	<!--
	body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	}
	.text{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	-->
	</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/kohrx.css" rel="stylesheet" type="text/css">
	<!-- js chart -->
	<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	
<script>
window.onload = function () {
	
	var chart = new CanvasJS.Chart("chartContainer1",
    {
	animationEnabled: true,
	theme: "light1",

	title:{
		fontSize: 20,
		padding: {
		 top: 1,
		 right: 1,
		 bottom: 20,
		 left: 2
	   },		
	   text: "กราฟแสดงการวัดค่าความดัน",
	},
	axisX:{
		title: 'วันที่ ',
		titleFontSize: 14,
		labelFontSize: 10,		
		valueFormatString: "D/MM/YYYY",
		crosshair: {
			enabled: true,
			snapToDataPoint: true
		}
	},
	axisY:{
		title: "ความดัน (mmHg)",
		titleFontSize: 16,
		labelFontSize: 10,
		labelAutoFit: true,
		lineColor: "#C24642",
		tickColor: "#C24642",
		labelFontColor: "#C24642",
		titleFontColor: "#C24642",
		crosshair: {
			enabled: true
				},
		suffix: "",
		/*
		stripLines:[
			{                
				startValue:100,
				endValue:130,                
				color:"#FED7B9" 
				  
		}
		]*/
		},
	        
		data: [
        {
		type: "rangeSplineArea",
		name: "BPS",
		color: "#8C92AC",
         indexLabel: "{y}",
         indexLabelPlacement: "outside",  
         indexLabelOrientation: "horizontal",		
		 indexLabelFontSize: 10,	
		xValueFormatString: "D/M/YYYY,HH:mm",
		//showInLegend: true,
		dataPoints: [
		<?php $i=0; do{
			$i++;
			echo "{x: new Date(".substr(($row_rs_lab['vstdate']+543),0,4)." ,".(ltrim(substr($row_rs_lab['vstdate'],5,2),"0")-1)." ,".substr($row_rs_lab['vstdate'],8,10)." ,".substr($row_rs_lab['vsttime'],0,2)." ,".substr($row_rs_lab['vsttime'],3,2)." ,".substr($row_rs_lab['vsttime'],6,2)."), y: [".$row_rs_lab['bps'].", ".$row_rs_lab['bpd']."] }";if($i!=$totalRows_rs_lab){ echo ","; } 
			}while($row_rs_lab = mysql_fetch_assoc($rs_lab)); ?>		

		]
        }

        ]
    });
chart.render();


}

</script>
	
</HEAD>
<BODY>
	
<CENTER>
<div class=" container-fluid" style="padding:10px;">
	<div style="padding:0px; ">
    <div id="chartContainer1" style=" margin-right:10px;width: 100%; height:500px;"></div>
    </div>
</div>

</CENTER>
</BODY>
</HTML>
<?php
mysql_free_result($rs_lab);
mysql_free_result($rs_lab2);

?>
