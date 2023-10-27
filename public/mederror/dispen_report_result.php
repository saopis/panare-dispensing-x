<?php require_once('../Connections/hos.php'); ?>
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

	include('../include/function.php');
	include('include/function.php');

$conditions="";

if($_GET['timecheck']=="Y"){
	$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";	
    $conditons="";
}

mysql_select_db($database_hos, $hos);
$query_rs_person = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ORDER BY name";
$rs_person = mysql_query($query_rs_person, $hos) or die(mysql_error());
$row_rs_person = mysql_fetch_assoc($rs_person);
$totalRows_rs_person = mysql_num_rows($rs_person);

mysql_select_db($database_hos, $hos);
$query_rs_truncate = "truncate ".$database_kohrx.".kohrx_med_error_indiv";
$rs_truncate = mysql_query($query_rs_truncate, $hos) or die(mysql_error());

?>
<?php
//เงื่อนไขต่างๆ
$last_date=days_in_month($_GET['month'],($_GET['year']-543));
$year=$_GET['year']-543;
$month=$_GET['month'];
$ym=$year."-".$month;
$pttype=$_GET['pttype'];
$datestart=$ym."-01";
$dateend=$ym."-".$last_date;
$prepares=$_GET['prepare'];

if($pttype!="all"){
	$condition.=" and pttype='$pttype' ";
	}
if($_GET['room_id']!=""){
	$condition.=" and room_id='".$_GET['room_id']."'";
    $conditions.=" and room_id='".$_GET['room_id']."'";
}
for($i=1;$i<=$last_date;$i++){
	$dd=sprintf("%02d",$i);
	$day=$ym."-".$dd;
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_med_error_indiv (date_error,date_error2) value ('$ym','$dd')";
	$rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

	
$rs_person2 = mysql_query($query_rs_person, $hos) or die(mysql_error());
$row_rs_person2 = mysql_fetch_assoc($rs_person2);
$totalRows_rs_person2 = mysql_num_rows($rs_person2);

$j=0; do{ $j++;
mysql_select_db($database_hos, $hos);
$query_rs_person_error = "SELECT count(*) as cc_error FROM ".$database_kohrx.".kohrx_med_error_indiv2 where date_error='".$day."' and doctor_code = '".$row_rs_person2['doctorcode']."' ".$condition;
//echo $query_rs_person_error;
$rs_person_error = mysql_query($query_rs_person_error, $hos) or die(mysql_error());
$row_rs_person_error = mysql_fetch_assoc($rs_person_error);
$totalRows_rs_person_error = mysql_num_rows($rs_person_error);

mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_med_error_indiv set name$j='".$row_rs_person_error['cc_error']."' where date_error='$ym' and date_error2='$dd'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

}while ($row_rs_person2 = mysql_fetch_assoc($rs_person2)); 
mysql_free_result($rs_person2);
mysql_free_result($rs_person_error);
	}

mysql_select_db($database_hos, $hos);
$query_sum1 = "select sum(name1),sum(name2),sum(name3),sum(name4),sum(name5),sum(name6),sum(name7),sum(name8),sum(name9),sum(name10),sum(name11),sum(name12),sum(name13),sum(name14),sum(name15),sum(name16),sum(name17),sum(name18),sum(name19),sum(name20),sum(name21),sum(name22),sum(name23),sum(name23),sum(name24),sum(name25),sum(name26),sum(name27),sum(name28),sum(name29),sum(name30) from ".$database_kohrx.".kohrx_med_error_indiv";
$sum1 = mysql_query($query_sum1, $hos) or die(mysql_error());
$row_sum1 = mysql_fetch_assoc($sum1);
$totalRows_sum1 = mysql_num_rows($sum1);

if($_GET['pttype']!="all"){ $condition.=" and pttype='".$_GET['pttype']."'"; }
else if($_GET['pttype']=="all"){ $condition .=""; $condition .=""; }

mysql_select_db($database_hos, $hos);
$query_person_error = "select doctor_code,count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
$person_error = mysql_query($query_person_error, $hos) or die(mysql_error());
$row_person_error = mysql_fetch_assoc($person_error);
$totalRows_person_error = mysql_num_rows($person_error);

function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
    // Declare an empty array 
    $array = array(); 
      
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 
$Date = getDatesFromRange($datestart, $dateend); 


//แจกแจงรายละเอียดความคลาดเคลื่อนรายบุคคล
mysql_select_db($database_hos, $hos);
$query_countdate = "select id from ".$database_kohrx.".kohrx_med_error_indiv where concat(date_error,'-',date_error2) between '".$datestart."' and '".$dateend."'";
$countdate = mysql_query($query_countdate, $hos) or die(mysql_error());
$row_countdate = mysql_fetch_assoc($countdate);
$totalRows_countdate = mysql_num_rows($countdate);

$startdate=$date_error."-01";
$enddate=$date_error."-".$totalRows_countdate;

			mysql_select_db($database_hos, $hos); 
			$query_e_type4 = "SELECT id,type_id,name as cause_name FROM ".$database_kohrx.".kohrx_med_error_error_cause where type_id='2'";
			$e_type4 = mysql_query($query_e_type4, $hos) or die(mysql_error());
			$row_e_type4 = mysql_fetch_assoc($e_type4);
			$totalRows_e_type4 = mysql_num_rows($e_type4);


?>
<?php
/*
		for($i=0;$i<count($Date);$i++){
		echo "['".date_db2th($Date[$i])."',";

		if($_GET['timecheck']=="Y"){
			$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";
			$conditions="";
			  
		  }
  
		  if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
		  else if($_GET['pttype']=="all"){ $condition .=""; $conditions .=""; }	
	  
	  $query_person_error2 = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
	  //echo $query_person_error2;  
	  $person_error2 = mysql_query($query_person_error2) or die(mysql_error());
	  $row_person_error2 = mysql_fetch_assoc($person_error2);

		$k=0;
		do
			{
			$k++;
				if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
				else if($_GET['pttype']=="all"){ $condition .=""; $conditions .="";  }

			mysql_select_db($database_hos, $hos);
			$query_person_error_count = "select count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error = '".$Date[$i]."' ".$condition." and doctor_code='".$row_person_error2['doctor_code']."'";
            //echo $query_person_error_count;
			$person_error_count = mysql_query($query_person_error_count, $hos) or die(mysql_error());
			$row_person_error_count = mysql_fetch_assoc($person_error_count);
			$totalRows_person_error_count = mysql_num_rows($person_error_count);
			echo $row_person_error_count['count_error'];
			mysql_free_result($person_error_count);
			if($k<$totalRows_person_error){
				echo ",";
			}
		}while($row_person_error2=mysql_fetch_assoc($person_error2));
		mysql_free_result($person_error2);

	echo "]";
	if(($i+1)<count($Date)){
		echo ",";
	}
}
exit();
*/
		  ?>
<?php 	/*	
            if($prepare=="Y"){ 
echo "<table>";

            $conditons="";	
	
			if($_GET['timecheck']=="Y"){
				$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";
				$conditions="";
			  }
		
			  if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
			  else if($_GET['pttype']=="all"){ $condition .=""; $conditions .=""; }	
		  
		  mysql_select_db($database_hos, $hos);		  
		  $query_person_error2 = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
		  //echo $query_person_error2;  
		  $person_error2 = mysql_query($query_person_error2) or die(mysql_error());
		  $row_person_error2 = mysql_fetch_assoc($person_error2);
		
		  	$k=0;
			do
			{ 	
			$k++;
			//สุ่มสี
			$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
			$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];    	
			 
			//จำนวน error
				if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'";}
				else if($_GET['pttype']=="all"){ $condition .=""; }
				
			mysql_select_db($database_hos, $hos);
			$query_person_error_count = "select count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error between '".$datestart."' and '".$dateend."' ".$condition." and doctor_code='".$row_person_error2['doctor_code']."'";
			$person_error_count = mysql_query($query_person_error_count, $hos) or die(mysql_error());
			$row_person_error_count = mysql_fetch_assoc($person_error_count);
			$totalRows_person_error_count = mysql_num_rows($person_error_count);

			$error_count=$row_person_error_count['count_error'];
			mysql_free_result($person_error_count);
			//จำนวนจัดยา

			//ค้นหาจำนวนจัด OPD
			$date11=explode("-",$datestart);
			$esdate1=substr(($date11[0]+543),2,4).$date11[1].$date11[2];

			$date11=explode("-",$dateend);
			$esdate2=substr(($date11[0]+543),2,4).$date11[1].$date11[2];	

			if($timecheck=="Y"){
				$condition2=" and rx_time between '".$_GET['time1']."' and '".$_GET['time2']."'";
				$condition3=" and substr(prepare_datetime,11,6) between '".$_GET['time1']."' and '".$_GET['time2']."'";
			}
			if($_GET['room_id']=="Y"){
				$condition2.=" and k.room_id='".$_GET['room_id']."'";
			}
		
		
			if($pttype=="OPD"||$pttype=="all"){			
			mysql_select_db($database_hos, $hos);
			$query_prepare = "
			select count(*) as prepare_count from rx_operator r left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn where r.pay='Y' and r.check_staff= '".$row_person_error2['doctor_code']."' and substring(r.vn,1,6) between '".$esdate1."' and '".$esdate2."'".$condition2;
			echo $query_prepare;
			$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
			$row_prepare = mysql_fetch_assoc($prepare);
			$totalRows_prepare = mysql_num_rows($prepare);
			$total_count_OPD=$row_prepare['prepare_count'];
			mysql_free_result($prepare);
			}	
			if($pttype=="IPD"||$pttype=="all"){			
			//ค้นหาจำนวนจัด IPD
			mysql_select_db($database_hos, $hos);
			$query_prepare = "select count(*) as prepare_count from ipt_dispense_prepare   where substr(prepare_datetime,1,10) between '".$datestart."' and '".$dateend."' and prepare_staff='".$row_person_error2['doctor_code']."'".$condition3;
			$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
			$row_prepare = mysql_fetch_assoc($prepare);
			$totalRows_prepare = mysql_num_rows($prepare);
			$total_count_IPD=$row_prepare['prepare_count'];
			$sum+=$total_counts;
			mysql_free_result($prepare);
			}	
		  if($_GET['pttype']=="OPD"){
			  $total_counts=$total_count_OPD;
			  echo $total_count."<br>";
		  }
		  else if($_GET['pttype']=="IPD"){
			  $total_counts=$total_count_IPD;
		  }
		  else if($_GET['pttype']=="all"){
			  $total_counts=$total_count_IPD+$total_count_OPD;
		  }
		  
		  ?>

				<tr class="font14">
				<td><?php echo doctorname($row_person_error2['doctor_code']); ?></td>	
				<td><?php echo $total_counts; ?></td>	
				<td><?php echo $error_count; ?></td>	
				<td><?php echo number_format2(($error_count/$total_counts)*100)." %"; ?></td>	
				</tr>
		<?php 
		
	}while($row_person_error2 = mysql_fetch_assoc($person_error2));  ?>

		</table>

	<?php exit(); } */ ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<script type="text/javascript" src="../include/google_chart/js/loader.js"></script>
<?php if($prepares=="Y"){ ?>
<script type="text/javascript">
google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'วันที่');
	<?php do { ?>
      data.addColumn('number', '<?php echo doctorname($row_person_error['doctor_code']); ?>');
	<?php }while($row_person_error = mysql_fetch_assoc($person_error)); ?>

      data.addRows([
		  <?php
		for($i=0;$i<count($Date);$i++){
		echo "['".date_db2th($Date[$i])."',";

		$conditons="";	
		if($_GET['timecheck']=="Y"){
			$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";
			$conditions="";
			  
		  }
  
		  if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
		  else if($_GET['pttype']=="all"){ $condition .=""; $conditions .=""; }	
	  
	  $query_person_error2 = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
	  //echo $query_person_error2;  
	  $person_error2 = mysql_query($query_person_error2) or die(mysql_error());
	  $row_person_error2 = mysql_fetch_assoc($person_error2);

		$k=0;
		do
			{
			$k++;
				if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
				else if($_GET['pttype']=="all"){ $condition .=""; $conditions .="";  }

			mysql_select_db($database_hos, $hos);
			$query_person_error_count = "select count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error = '".$Date[$i]."' ".$condition." and doctor_code='".$row_person_error2['doctor_code']."'";
            //echo $query_person_error_count;
			$person_error_count = mysql_query($query_person_error_count, $hos) or die(mysql_error());
			$row_person_error_count = mysql_fetch_assoc($person_error_count);
			$totalRows_person_error_count = mysql_num_rows($person_error_count);
			echo $row_person_error_count['count_error'];
			mysql_free_result($person_error_count);
			if($k<$totalRows_person_error){
				echo ",";
			}
		}while($row_person_error2=mysql_fetch_assoc($person_error2));
		mysql_free_result($person_error2);

	echo "]";
	if(($i+1)<count($Date)){
		echo ",";
	}
}
		  ?>
	  ]);

      var options = {
        chart: {
          title: 'กราฟแสดงจำนวนความคลาดเคลื่อนรายบุคคล',
          subtitle: 'ระหว่างวันที่ <?php echo dateThai($datestart); ?> ถึง <?php echo dateThai($dateend); ?>'
        },
		  legend: { textStyle: {fontSize: 12}},
		width: '100vh',
        height: 400
      };

      var chart = new google.charts.Line(document.getElementById('chart_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
	
		
    }
</script>

<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "% error", { role: "style" } ],
		<?php 		
	
	$conditons="";	
	
	if($_GET['timecheck']=="Y"){
		$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";
		$conditions="";
		  
	  }

	  if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
	  else if($_GET['pttype']=="all"){ $condition .=""; $conditions .=""; }	
  
  $query_person_error2 = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
  //echo $query_person_error2;  
  $person_error2 = mysql_query($query_person_error2) or die(mysql_error());
  $row_person_error2 = mysql_fetch_assoc($person_error2);

$k=0;
do{ 	
$k++;
//สุ่มสี
$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];    	

//จำนวน error
if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
else if($_GET['pttype']=="all"){ $condition .=""; $conditions .="";  }


mysql_select_db($database_hos, $hos);
$query_person_error_count = "select count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error between '".$datestart."' and '".$dateend."' ".$condition." and doctor_code='".$row_person_error2['doctor_code']."'";
//echo $query_person_error_count;
$person_error_count = mysql_query($query_person_error_count, $hos) or die(mysql_error());
$row_person_error_count = mysql_fetch_assoc($person_error_count);
$totalRows_person_error_count = mysql_num_rows($person_error_count);

$error_count=$row_person_error_count['count_error'];
mysql_free_result($person_error_count);
//จำนวนจัดยา


if($timecheck=="Y"){
$condition2=" and rx_time between '".$_GET['time1']."' and '".$_GET['time2']."'";
$condition3=" and substr(prepare_datetime,11,6) between '".$_GET['time1']."' and '".$_GET['time2']."'";
}
if($_GET['room_id']!=""){
	$condition2.=" and k.room_id='".$_GET['room_id']."'";
}
//ค้นหาจำนวนจัด OPD
$date11=explode("-",$datestart);
$esdate1=substr(($date11[0]+543),2,4).$date11[1].$date11[2];

$date11=explode("-",$dateend);
$esdate2=substr(($date11[0]+543),2,4).$date11[1].$date11[2];	

if($_GET['pttype']=="OPD"||$_GET['pttype']=="all"){			
mysql_select_db($database_hos, $hos);
$query_prepare = "
select count(*) as prepare_count from rx_operator r left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn where r.check_staff= '".$row_person_error2['doctor_code']."' and k.room_id in (select id from kohrx_queue_caller_room) and substring(r.vn,1,6) between '".$esdate1."' and '".$esdate2."'".$condition2;
$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
$row_prepare = mysql_fetch_assoc($prepare);
$totalRows_prepare = mysql_num_rows($prepare);
$total_count_OPD=$row_prepare['prepare_count'];
mysql_free_result($prepare);
}
if($_GET['pttype']=="IPD"||$_GET['pttype']=="all"){			
//ค้นหาจำนวนจัด IPD
mysql_select_db($database_hos, $hos);
$query_staff = "select loginname from opduser where doctorcode ='".$row_person_error2['doctor_code']."'";
//echo $query_staff;
$rs_staff = mysql_query($query_staff, $hos) or die(mysql_error());
$row_rs_staff = mysql_fetch_assoc($rs_staff);

$loginname=$row_rs_staff['loginname'];
mysql_free_result($rs_staff);

mysql_select_db($database_hos, $hos);
$query_prepare = "select count(*) as prepare_count from ipt_dispense_prepare   where substr(prepare_datetime,1,10) between '".$datestart."' and '".$dateend."' and prepare_staff='".$loginname."'".$condition3;
$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
$row_prepare = mysql_fetch_assoc($prepare);
$totalRows_prepare = mysql_num_rows($prepare);
$total_count_IPD=$row_prepare['prepare_count'];
mysql_free_result($prepare);

}

if($_GET['pttype']=="OPD"){
$total_count=$total_count_OPD;
}
else if($_GET['pttype']=="IPD"){
$total_count=$total_count_IPD;
}
else if($_GET['pttype']=="all"){
$total_count=$total_count_IPD+$total_count_OPD;
}


echo "['".doctorname($row_person_error2['doctor_code'])."', ".number_format2(($error_count/$total_count)*100).", '".$color."']";
if($k<$totalRows_person_error){
echo ",";
}

}while($row_person_error2=mysql_fetch_assoc($person_error2));
mysql_free_result($person_error2);
		  ?>

      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
		chartArea:{left:10,top:10,width:"100%",height:"80%"},	  
        width: '100vh',
        height: 400,
        bar: {groupWidth: "70%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  </script>
<?php } ?>
<style>
th.rotate {
  height:200px;
  white-space: nowrap;
  position:relative;
}

th.rotate > div {
  transform: rotate(-90deg);
  position:absolute;
  left:0;
  right:0;
	bottom: 5px;
  margin:auto;
  
}
</style>	
</head>

<body>
<table width="100%"  align="center" class="table table-bordered table-striped table-hover table-sm" style="margin-top: -10px;">
<thead class="thead-dark bg-gray2">
  <tr>
    <td align="center"  >ชื่อเจ้าหน้าที่</td>
     <? for($i=1;$i<=$last_date;$i++){ ?>
    <td align="center"  class="font12"><?=$i; ?></td>
    <? } ?>
    <td align="center"  class="font12">รวม</td>
    <?php if($prepares=="Y"){ ?>
      <td align="center"  class="font12">ยอดจัด</td>
    <?php } ?>
  </tr>
</thead>
<tbody>
   <?php $k=0;$total_prepare=0; do { $k++; 

   ?><tr class="font12">
   
     <td class="font14" ><nobr><?php echo $row_rs_person['name']; ?></nobr></td>
    <? for($i=1;$i<=$last_date;$i++){
	$dd=sprintf("%02d",$i);	   
	mysql_select_db($database_hos, $hos);
	$query_rs_person_error2 = "SELECT name$k as cc_error,sum(name$k) as sumdate  from ".$database_kohrx.".kohrx_med_error_indiv where  date_error2 ='$dd' group by name$k ";
	$rs_person_error2 = mysql_query($query_rs_person_error2, $hos) or die(mysql_error());
	$row_rs_person_error2 = mysql_fetch_assoc($rs_person_error2);
	  ?>
     <td align="center" ><span <?php if($row_rs_person_error2['cc_error']!="0"){ echo "onclick=\"alertload('predis_error_detail.php?date_error=".$ym."-".$dd."&who=".$row_rs_person['doctorcode']."&type=&pttype=".$pttype."','90%','80%');\""; } ?> style=" text-decoration:none; color:#333333; cursor:pointer"><?=$row_rs_person_error2['cc_error']; ?></span></td>
      <?
   mysql_free_result($rs_person_error2);
   } 
	  
    mysql_select_db($database_hos, $hos);
	$query_rs_sumdate = "SELECT sum(name$k) as sumdate  from ".$database_kohrx.".kohrx_med_error_indiv  ";
	$rs_sumdate = mysql_query($query_rs_sumdate, $hos) or die(mysql_error());
	$row_rs_sumdate = mysql_fetch_assoc($rs_sumdate);
    
    if($_GET['room_id']!=""){
        $room_id="and k.room_id='".$_GET['room_id']."'";
    }
    else{ $room_id=""; }
                   
    if($prepares=="Y"){  
		if($pttype=="OPD" || $pttype=="all"){
			if($_GET['timecheck']=="Y"){
				$condition_time=" and r.rx_time between '".$_GET['time1']."' and '".$_GET['time2']."'";			  
			  }
			mysql_select_db($database_hos, $hos);
			$query_rs_prepare = "
			select count(*) as prepare_count from rx_operator r left outer join dispensing.kohrx_dispen_staff_operation k on k.vn=r.vn where substring(r.vn,1,4) = '".substr(($year+543),2,2).$month."' ".$room_id." ".$condition_time." and r.check_staff='".$row_rs_person['doctorcode']."'";
			$rs_prepare = mysql_query($query_rs_prepare, $hos) or die(mysql_error());
			$row_rs_prepare = mysql_fetch_assoc($rs_prepare);
			$total_count_OPD=$row_rs_prepare['prepare_count'];
			mysql_free_result($rs_prepare);
		}
		if($pttype=="IPD" || $pttype=="all"){
			mysql_select_db($database_hos, $hos);
			$query_staff = "select loginname from opduser where doctorcode ='".$row_rs_person['doctorcode']."'";
			$rs_staff = mysql_query($query_staff, $hos) or die(mysql_error());
			$row_rs_staff = mysql_fetch_assoc($rs_staff);

			$loginname=$row_rs_staff['loginname'];
			mysql_free_result($rs_staff);
		
			if($_GET['timecheck']=="Y"){
				$condition_time=" and substr(prepare_datetime,11,8) between '".$_GET['time1']."' and '".$_GET['time2']."'";			  
			}
			//ค้นหาจำนวนจัด IPD
			mysql_select_db($database_hos, $hos);
			$query_prepare = "select count(*) as prepare_count from ipt_dispense_prepare   where substr(prepare_datetime,1,10) between '".$datestart."' and '".$dateend."' ".$condition_time." and prepare_staff='".$loginname."'";
			//echo $query_prepare;
			$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
			$row_prepare = mysql_fetch_assoc($prepare);
			$totalRows_prepare = mysql_num_rows($prepare);
			$total_count_IPD=$row_prepare['prepare_count'];
			mysql_free_result($prepare);
		}

			if($pttype=="OPD"){
				$count_prepare=$total_count_OPD;
			}

			else if($pttype=="IPD"){
				$count_prepare=$total_count_IPD;
			}
			else{
				$count_prepare=$total_count_OPD+$total_count_IPD;
			}

			$total_prepare+=$count_prepare;

    }
	  ?>
     <td align="center" ><a href="#" onClick="alertload('predis_error_detail.php?date_error=<? echo $ym; ?>&amp;who=<?=$row_rs_person['doctorcode']; ?>&type=2&pttype=<?=$pttype; ?>','90%','80%');" class="badge badge-dark font14" ><?=$row_rs_sumdate['sumdate']; 	mysql_free_result($rs_sumdate);?></a></td> 
        <?php if($prepares=="Y"){ ?>
        <td align="center"  class="font12"><?php echo number_format2($count_prepare); ?></td>
        <?php } ?>
  </tr> 
  <?php } while ($row_rs_person = mysql_fetch_assoc($rs_person)); ?>
 </tbody>
<tfoot class="bg-gray3">
   <tr class="font14">
     <td align="center" >รวม</td>
      <? for($i=1;$i<=$last_date;$i++){ 
	
	$dd=sprintf("%02d",$i);

	mysql_select_db($database_hos, $hos);
	$query_rs_date_sum = "SELECT (IF(name1,name1,0)+IF(name2,name2,0)+IF(name3,name3,0)+IF(name4,name4,0)+IF(name5,name5,0)+IF(name6,name6,0)+IF(name7,name7,0)+IF(name8,name8,0)+IF(name9,name9,0)+IF(name10,name10,0)+IF(name11,name11,0)+IF(name12,name12,0)+IF(name13,name13,0)+IF(name14,name14,0)+IF(name15,name15,0)+IF(name16,name16,0)+IF(name17,name17,0)+IF(name18,name18,0)+IF(name19,name19,0)+IF(name20,name20,0)+IF(name21,name21,0)+IF(name22,name22,0)+IF(name23,name23,0)+IF(name24,name24,0)+IF(name25,name25,0)+IF(name26,name26,0)+IF(name27,name27,0)+IF(name28,name28,0)+IF(name29,name29,0)+IF(name30,name30,0)) as cc_error  from ".$database_kohrx.".kohrx_med_error_indiv where date_error2='$dd' ";
	$rs_date_sum = mysql_query($query_rs_date_sum, $hos) or die(mysql_error());
	$row_rs_date_sum = mysql_fetch_assoc($rs_date_sum);
	//echo $query_rs_date_sum;
	  ?>
     <td align="center"  ><a href="#" onClick="alertload('predis_error_detail.php?date_error=<? echo $ym."-".$dd; ?>&amp;type=3&pttype=<?=$pttype; ?>','90%','80%');" class="badge badge-info font14" ><?=$row_rs_date_sum['cc_error']; ?></a></td>
     <? 
	mysql_free_result($rs_date_sum);
	} ?>
     <td align="center" ><a href="#" onclick="alertload('predis_error_detail.php?date_error=<? echo $ym; ?>&amp;type=4&pttype=<?=$pttype; ?>','90%','80%')" class="badge badge-danger font14" ><?php echo $row_sum1['sum(name1)']+$row_sum1['sum(name2)']+$row_sum1['sum(name3)']+$row_sum1['sum(name4)']+$row_sum1['sum(name5)']+$row_sum1['sum(name6)']+$row_sum1['sum(name7)']+$row_sum1['sum(name8)']+$row_sum1['sum(name9)']+$row_sum1['sum(name10)']+$row_sum1['sum(name11)']+$row_sum1['sum(name12)']+$row_sum1['sum(name13)']+$row_sum1['sum(name14)']+$row_sum1['sum(name15)']+$row_sum1['sum(name16)']; ?></a></td>
    <?php if($prepares=="Y"){ ?>
       <td align="center"  class="font12"><?php echo number_format2($total_prepare); ?></td>
    <?php } ?>   
  </tr>
</tfoot>
</table>
<?php if($prepares=="Y"){ ?>
<div id="chart_div"></div>
<?php } ?>
<hr/>

	<!-- รายละเอียด error รายบุคคล -->
	<div style="padding-right: 10px;">
<table class="table font14 m-2 table-striped table-bordered table-hover table-header-rotated" style="width: 100%">
	<thead>
  <tr class="font12 bg-dark text-white">
    <th  class="align-middle">ผู้จัด</th>
	<?
			do{
			 ?><th style="font-weight: normal " class="align-top" ><?php echo $row_e_type4['cause_name']; ?></th><? } while($row_e_type4 = mysql_fetch_assoc($e_type4)); mysql_free_result($e_type4);  ?>
  <th class="align-middle">รวม</th>
  </tr>
	  </thead>
		<tbody>
	  <? 
            mysql_select_db($database_hos, $hos);
            $query_rs_person = "SELECT d.name,o.doctorcode FROM ".$database_kohrx.".kohrx_rx_person o left outer join doctor d on d.code=o.doctorcode ORDER BY name";
            $rs_person = mysql_query($query_rs_person, $hos) or die(mysql_error());
            $row_rs_person = mysql_fetch_assoc($rs_person);
            $totalRows_rs_person = mysql_num_rows($rs_person);
            
	  		do
			{  
	  		mysql_select_db($database_hos, $hos); 
			$query_e_type5 = "SELECT * FROM ".$database_kohrx.".kohrx_med_error_error_cause where type_id='2'";
            //echo $query_e_type5;
			$e_type5 = mysql_query($query_e_type5, $hos) or die(mysql_error());
			$row_e_type5 = mysql_fetch_assoc($e_type5);
			$totalRows_e_type5 = mysql_num_rows($e_type5);
	  		
			
	  ?>
      <tr class="font12" >
      <td align="center" ><nobr><?php echo doctorname($row_rs_person['doctorcode']); ?></nobr></td>
      	<? do{ 
		
		mysql_select_db($database_hos, $hos); 
		$query_sum_type = "SELECT count(*) as sumsum,substring(date_error,1,7) FROM ".$database_kohrx.".kohrx_med_error_indiv2 where date_error between '".$datestart."' and '".$dateend."' and error_type='".$row_e_type5['id']."' and doctor_code='".$row_rs_person['doctorcode']."' ".$condition;
		$sum_type = mysql_query($query_sum_type, $hos) or die(mysql_error());
		$row_sum_type = mysql_fetch_assoc($sum_type);
		?><td align="center" ><?=$row_sum_type['sumsum']; ?></td>
      	<? } while($row_e_type5 = mysql_fetch_assoc($e_type5)); 
	mysql_select_db($database_hos, $hos); 
	$query_sum_type2 = "SELECT count(*) as sumsum,substring(date_error,1,7) FROM ".$database_kohrx.".kohrx_med_error_indiv2 where date_error between '$datestart' and '$dateend' and doctor_code='".$row_person_error2['doctor_code']."' ".$condition;
	$sum_type2 = mysql_query($query_sum_type2, $hos) or die(mysql_error());
	$row_sum_type2 = mysql_fetch_assoc($sum_type2);
		
		 ?>
            	<td align="center" ><?=$row_sum_type2['sumsum']; ?></td></tr><? 
			mysql_free_result($sum_type2);
			mysql_free_result($e_type5);
			}while($row_rs_person = mysql_fetch_assoc($rs_person));  ?>
			</tbody>
</table>
	</div>   
	<?php if($prepares=="Y"){ ?> 
	<div class="row">
		<div class="col-6" >
			<div class=" m-1 bg-light" style=" padding: 10px; border-bottom: solid 1px #E4E4E4;border-top: solid 1px #E4E4E4">อัตราความคลาดเคลื่อนรายบุคคล(เทียบกับการจัดยา)</div>
			<div id="columnchart_values"></div>
		</div>
        
		<div  class="col-6" >
		<table class="table table-striped font14 m-1 ">
			<tr class="bg-light">
				<td>ชื่อผู้จัด</td>	
				<td>จำนวนจัด(ครั้ง)</td>	
				<td>จำนวนจัดผิด(ครั้ง)</td>	
				<td>% error</td>	
			</tr>

<?php 		
            $conditons="";	
	
			if($_GET['timecheck']=="Y"){
				$condition=" and time1 between '".$_GET['time1']."' and '".$_GET['time2']."'";
				$conditions="";
			  }
		
			  if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'"; $conditions .=" and pttype='".$_GET['pttype']."'";}
			  else if($_GET['pttype']=="all"){ $condition .=""; $conditions .=""; }	
		  
		  mysql_select_db($database_hos, $hos);		  
		  $query_person_error2 = "select doctor_code from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error BETWEEN '".$datestart."' and '".$dateend."' ".$condition." group by doctor_code";
		  //echo $query_person_error2;  
		  $person_error2 = mysql_query($query_person_error2) or die(mysql_error());
		  $row_person_error2 = mysql_fetch_assoc($person_error2);
		
		  	$k=0;
			do
			{ 	
			$k++;
			//สุ่มสี
			$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
			$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];    	
			 
			//จำนวน error
				if($_GET['pttype']!="all"){ $condition .=" and pttype='".$_GET['pttype']."'";}
				else if($_GET['pttype']=="all"){ $condition .=""; }
				
			mysql_select_db($database_hos, $hos);
			$query_person_error_count = "select count(doctor_code) as count_error from ".$database_kohrx.".kohrx_med_error_indiv2 where date_error between '".$datestart."' and '".$dateend."' ".$condition." and doctor_code='".$row_person_error2['doctor_code']."'";
			$person_error_count = mysql_query($query_person_error_count, $hos) or die(mysql_error());
			$row_person_error_count = mysql_fetch_assoc($person_error_count);
			$totalRows_person_error_count = mysql_num_rows($person_error_count);

			$error_count=$row_person_error_count['count_error'];
			mysql_free_result($person_error_count);
			//จำนวนจัดยา

			//ค้นหาจำนวนจัด OPD
			$date11=explode("-",$datestart);
			$esdate1=substr(($date11[0]+543),2,4).$date11[1].$date11[2];

			$date11=explode("-",$dateend);
			$esdate2=substr(($date11[0]+543),2,4).$date11[1].$date11[2];	
			$condition2="";
			if($timecheck=="Y"){
				$condition2=" and rx_time between '".$_GET['time1']."' and '".$_GET['time2']."'";
				$condition3=" and substr(prepare_datetime,11,6) between '".$_GET['time1']."' and '".$_GET['time2']."'";
			}
			if($_GET['room_id']!=""){
				$condition2.=" and k.room_id='".$_GET['room_id']."'";
			}
		
			
			if($pttype=="OPD"||$pttype=="all"){			
			mysql_select_db($database_hos, $hos);
			$query_prepare = "
			select count(*) as prepare_count from rx_operator r left outer join ".$database_kohrx.".kohrx_dispen_staff_operation k on k.vn=r.vn where r.check_staff= '".$row_person_error2['doctor_code']."' and substring(r.vn,1,6) between '".$esdate1."' and '".$esdate2."'".$condition2;
			//echo $query_prepare;
			$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
			$row_prepare = mysql_fetch_assoc($prepare);
			$totalRows_prepare = mysql_num_rows($prepare);
			$total_count_OPD=$row_prepare['prepare_count'];
			mysql_free_result($prepare);
			}	
			if($pttype=="IPD"||$pttype=="all"){	
				mysql_select_db($database_hos, $hos);
				$query_staff = "select loginname from opduser where doctorcode ='".$row_person_error2['doctor_code']."'";
				//echo $query_staff;
				$rs_staff = mysql_query($query_staff, $hos) or die(mysql_error());
				$row_rs_staff = mysql_fetch_assoc($rs_staff);
	
				$loginname=$row_rs_staff['loginname'];
				mysql_free_result($rs_staff);

			//ค้นหาจำนวนจัด IPD
			mysql_select_db($database_hos, $hos);
			$query_prepare = "select count(*) as prepare_count from ipt_dispense_prepare   where substr(prepare_datetime,1,10) between '".$datestart."' and '".$dateend."' and prepare_staff='".$loginname."'".$condition3;
			//echo $query_prepare;
			$prepare = mysql_query($query_prepare, $hos) or die(mysql_error());
			$row_prepare = mysql_fetch_assoc($prepare);
			$totalRows_prepare = mysql_num_rows($prepare);
			$total_count_IPD=$row_prepare['prepare_count'];
			$sum+=$total_count;
			mysql_free_result($prepare);
			}	
		  if($_GET['pttype']=="OPD"){
			  $total_count=$total_count_OPD;
		  }
		  else if($_GET['pttype']=="IPD"){
			  $total_count=$total_count_IPD;
		  }
		  else if($_GET['pttype']=="all"){
			  $total_count=$total_count_IPD+$total_count_OPD;
		  }

            
		  
		  ?>

				<tr class="font14">
				<td><?php echo doctorname($row_person_error2['doctor_code']); ?></td>	
				<td><?php echo $total_count; ?></td>	
				<td><?php echo $error_count; ?></td>	
				<td><?php echo number_format2(($error_count/$total_count)*100)." %"; ?></td>	
				</tr>
		<?php }while($row_person_error2 = mysql_fetch_assoc($person_error2));  ?>

		</table>
		</div>
	
	
	</div>
	<?php } ?>

</body>
</html>
<?php
mysql_free_result($sum1);
mysql_free_result($person_error);
//mysql_free_result($cout_date);
mysql_free_result($rs_person);
?>
