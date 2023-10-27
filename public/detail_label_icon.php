<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php include('include/function.php'); ?>
<?php
if(isset($_POST['hn'])&&$_POST['hn']!=""){
	$hn=$_POST['hn'];
}
if(isset($_GET['hn'])&&$_GET['hn']!=""){
	$hn=$_GET['hn'];
}

if(isset($_POST['save'])&&$_POST['save']=="บันทึก"){
	mysql_select_db($database_hos, $hos);
	$query_insert = "insert into ".$database_kohrx.".kohrx_label_icon_patient (hn,label_id,label_comment,doctor) value ('".$hn."','".$_POST['label']."','".$_POST['comment']."','".$_SESSION['doctorcode']."')";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>window.location='detail_label_icon.php?hn=".$hn."';parent.label_load('".$hn."');</script>";
}
if(isset($_POST['edit'])&&$_POST['edit']=="แก้ไข"){
	mysql_select_db($database_hos, $hos);
	$query_insert = "update ".$database_kohrx.".kohrx_label_icon_patient set label_comment='".$_POST['comment']."',doctor='".$_SESSION['doctorcode']."' where label_id='".$_POST['id']."' and hn='".$hn."'";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>window.location='detail_label_icon.php?hn=".$hn."';parent.label_load('".$hn."');</script>";
}
if(isset($_POST['delete'])&&$_POST['delete']=="ลบ"){
	mysql_select_db($database_hos, $hos);
	$query_insert = "delete from ".$database_kohrx.".kohrx_label_icon_patient where label_id='".$_POST['id']."' and hn='".$hn."'";
	$insert = mysql_query($query_insert, $hos) or die(mysql_error());
	echo "<script>window.location='detail_label_icon.php?hn=".$hn."';parent.label_load('".$hn."');</script>";
}

if(!isset($_GET['id'])){
	$condition=" l.id not in (select label_id from ".$database_kohrx.".kohrx_label_icon_patient where hn='".$hn."') ";
}
else{
	$condition=" l.id ='".$_GET['id']."'";
}
mysql_select_db($database_hos, $hos);
$query_rs_icon = "select l.id,i.icon,l.label_name,i.icon_html,i.icon_name,l.label_color,p.label_comment from ".$database_kohrx.".kohrx_label_icon_list l left outer join ".$database_kohrx.".kohrx_label_icon i on i.id=l.label_icon_id left outer join ".$database_kohrx.".kohrx_label_icon_patient p on p.label_id=l.id and p.hn='".$hn."' where ".$condition." group by l.id order by l.id ASC";
//echo $query_rs_icon;
$rs_icon = mysql_query($query_rs_icon, $hos) or die(mysql_error());
$row_rs_icon = mysql_fetch_assoc($rs_icon);
$totalRows_rs_icon = mysql_num_rows($rs_icon);

$label_comment=$row_rs_icon['label_comment'];

mysql_select_db($database_hos, $hos);
$query_rs_icon_list = "select l.id,i.icon,l.label_name,i.icon_html,i.icon_name,l.label_color,p.hn,p.label_comment,p.doctor from ".$database_kohrx.".kohrx_label_icon_patient p left outer join ".$database_kohrx.".kohrx_label_icon_list l on l.id=p.label_id left outer join ".$database_kohrx.".kohrx_label_icon i on i.id=l.label_icon_id where p.hn='".$hn."' order by l.id ASC";
$rs_icon_list = mysql_query($query_rs_icon_list, $hos) or die(mysql_error());
$row_rs_icon_list = mysql_fetch_assoc($rs_icon_list);
$totalRows_rs_icon_list = mysql_num_rows($rs_icon_list);

?>
<?php include('include/function_sql.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>	
<style>
/* The container */
.container_lan {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.container_label input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 5px;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container_label:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container_label input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container_label input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container_span .checkmark:after {
 	top: 9px_labeleft: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
</style>	
<style>
	html,body{
		overflow: hidden;
	}
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
<div class="p-2 bg-secondary text-white-50">
บันทึกป้ายกำกับสำหรับผู้ป่วย
</div>
<?php if($totalRows_rs_icon<>0){ ?>	
<form name="form1" id="form1" method="post" action="detail_label_icon.php" class="mb-0">	
<div class="p-2">	
	<div class="card">
		<div class="card-body">
			<div class="row">
			<?php $intRows = 0; $i=0;
				do { $i++;
			?>
				<?php $intRows++; ?>
				<div class="col">
				<label class="container_label">	
				<input type="radio" id="lable" name="label"  value="<?php echo $row_rs_icon['id']; ?>" <?php if($row_rs_icon['id']==$_GET['id']){ echo "checked"; } ?> />
				<span class="checkmark"></span>	
				<span class="badge badge-<?php echo $row_rs_icon
	['label_color']; ?> font14 p-2 ml-3 cursor" style="width:100px;"><i class="<?php echo $row_rs_icon['icon_html']; ?> font20"></i>&nbsp;<?php echo $row_rs_icon['label_name']; ?></span>
				</label>
				</div>	
				<?php
				if(($intRows)%4==0)
				{
				?>
				</div>
				<div class="row mt-2">

				<?php 
					} 
				}while($row_rs_icon = mysql_fetch_assoc($rs_icon));
			?>
				</div>
			</div> 		
		</div>
	<div class="row mt-2">
		<label class="col-form-label-sm col-sm-auto">ข้อความ</label>
		<div class="col-sm-6"><input type="text" id="comment" name="comment" class="form-control form-control-sm" value="<?php echo $label_comment; ?>" /></div>
		<?php if(!isset($_GET['id'])){ ?>
		<div class="col-sm-auto"><input type="submit" class="btn btn-success btn-sm" value="บันทึก" id="save" name="save"/></div>
		<?php } else { ?>
		<div class="col-sm-auto"><input type="submit" class="btn btn-warning btn-sm" value="แก้ไข" id="edit" name="edit" style="width: 80px"/></div>
		<div class="col-sm-auto"><input type="submit" class="btn btn-danger btn-sm" value="ลบ" id="delete" name="delete" style="width: 80px"/></div>
		<input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>" />		
		<?php } ?>
		<input type="hidden" id="hn" name="hn" value="<?php echo $_GET['hn']; ?>" />
	</div>	
	</div>
</form>	
<?php } ?>
<?php if($totalRows_rs_icon_list<>0){ ?>	
<div class="p-2"  style="overflow:scroll;overflow-x:hidden;overflow-y:auto; <?php if($totalRows_rs_icon==0){echo "height:450px;"; }else {echo "height:300px;"; } ?>">
	<table class="table">
		<thead>
			<tr>
				<th>ลำดับ</th>
				<th>icon</th>
				<th>ข้อความ</th>
				<th>ผู้บันทึก</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0; do{ $i++; ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><span class="badge badge-<?php echo $row_rs_icon_list
	['label_color']; ?> font14 p-2" style="width:100px;"><i class="<?php echo $row_rs_icon_list['icon_html']; ?> font20"></i>&nbsp;<?php echo $row_rs_icon_list['label_name']; ?></span></td>
				<td><?php echo $row_rs_icon_list['label_comment']; ?></td>
				<td><?php echo doctorname($row_rs_icon_list['doctor']); ?></td>
				<td><i class="far fa-edit font20 cursor" onClick="window.location='detail_label_icon.php?id=<?php echo $row_rs_icon_list['id']; ?>&hn=<?php echo $row_rs_icon_list['hn']; ?>';"></i></td>
			</tr>
			<?php }while($row_rs_icon_list = mysql_fetch_assoc($rs_icon_list)); ?>
		</tbody>
	</table>
</div>	
<?php } ?>	
</body>
</html>
<?php mysql_free_result($rs_icon); ?>