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
$query_rs_lab = "select  lab_order_result,report_date,concat(date_format(report_date,'%d/%m/'),(date_format(report_date,'%Y'))+543) as report_date1,report_time from lab_order o left join lab_head h on h.lab_order_number=o.lab_order_number left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join vn_stat v on v.hn=h.hn and v.vstdate=h.order_date where h.hn='".$_GET['hn']."' and lab_items_code='".$_GET['lab']."' group by report_date order by report_date ASC";
$rs_lab = mysql_query($query_rs_lab, $hos) or die(mysql_error());
$row_rs_lab = mysql_fetch_assoc($rs_lab);
$totalRows_rs_lab = mysql_num_rows($rs_lab);

mysql_select_db($database_hos, $hos);
$query_rs_lab2 = "select  max(lab_order_result) as max_lab,min(lab_order_result) as min_lab,s.lab_items_name,s.lab_items_unit,s.lab_items_normal_value from lab_order o left outer join lab_items s on s.lab_items_code=o.lab_items_code left join lab_head h on h.lab_order_number=o.lab_order_number left join opdscreen os on os.hn=h.hn and os.vstdate=h.order_date left join vn_stat v on v.hn=h.hn and v.vstdate=h.order_date where h.hn='".$_GET['hn']."' and s.lab_items_code='".$_GET['lab']."'";
$rs_lab2 = mysql_query($query_rs_lab2, $hos) or die(mysql_error());
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
<link rel="stylesheet" href="include/kohrx/css/kohrx.css">
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
	   text: "กราฟแสดงค่าการตรวจ <?php echo $row_rs_lab2['lab_items_name']; ?>",
	   fontFamily: "tahoma"
	},
	subtitles:[
		{
			text: "ค่าปกติ <?php echo $row_rs_lab2['lab_items_normal_value']; ?>",
			fontFamily: "tahoma",
			//Uncomment properties below to see how they behave
			//fontColor: "red",
			fontSize: 15
		}
			],
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
		title: "<?php echo $row_rs_lab2['lab_items_unit']; ?>",
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
		viewportMinimum: <?php echo $row_rs_lab2['min_lab']-5; ?>,
		viewportMaximum: <?php echo $row_rs_lab2['max_lab']+10; ?>,
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
		type: "spline",
		name: "lab",
		color: "#606E79",
         indexLabel: "{y}",
         indexLabelPlacement: "outside",  
         indexLabelOrientation: "horizontal",		
		 indexLabelFontSize: 12,	
		xValueFormatString: "D/M/YYYY,HH:mm",
		//showInLegend: true,
		dataPoints: [
		<?php $i=0; do{
			$i++;
			echo "{x: new Date(".substr(($row_rs_lab['report_date']+543),0,4)." ,".(ltrim(substr($row_rs_lab['report_date'],5,2),"0")-1)." ,".substr($row_rs_lab['report_date'],8,10)." ,".substr($row_rs_lab['report_time'],0,2)." ,".substr($row_rs_lab['report_time'],3,2)." ,".substr($row_rs_lab['report_time'],6,2)."), y: ".$row_rs_lab['lab_order_result']." }";if($i!=$totalRows_rs_lab){ echo ","; } 
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
    <div id="chartContainer1" style=" margin-right:10px;width: 100%; height:450px;"></div>
    </div>
</div>

</CENTER>
</BODY>
</HTML>
<?php
mysql_free_result($rs_lab);
mysql_free_result($rs_lab2);

?>
