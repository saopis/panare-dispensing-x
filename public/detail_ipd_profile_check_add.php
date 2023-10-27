<?php ob_start();?>
<?php session_start();?>
<?php require_once('Connections/hos.php'); ?>
<?php
if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
	if($_POST['med_type']=="admit"){
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_ipd_profile_check (med_plan_number,order_date,check_date,check_qty,check_staff,check_type) value ('".$_POST['med_plan_number']."','".$_POST['order_date']."',NOW(),'".$_POST['qty']."','".$_SESSION['doctorcode']."','".$_POST['check_type']."')";
	}
	else{
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_ipd_profile_check (hos_guid,order_date,check_date,check_qty,check_staff,check_type) value ('".$_POST['hos_guid']."','".$_POST['order_date']."',NOW(),'".$_POST['qty']."','".$_SESSION['doctorcode']."','".$_POST['check_type']."')";		
	}
    //echo $query_rs_medplan;
    $insert = mysql_query($query_insert, $hos) or die(mysql_error());
    
    if($insert){
        echo "<script>parent.loadan();parent.$.fn.colorbox.close();parent.spinnerShow();</script>";
        exit();
    }
}
if(isset($_POST['delete'])&&($_POST['delete']=="ลบ")){
		mysql_select_db($database_hos, $hos);
		$query_delete = "delete from ".$database_kohrx.".kohrx_ipd_profile_check where hos_guid='".$_POST['hos_guid']."' and check_type='".$_POST['check_type']."'";		
        //echo $query_rs_medplan;
        $delete = mysql_query($query_delete, $hos) or die(mysql_error());
    
    if($delete){
        echo "<script>parent.window.location.reload();parent.$.fn.colorbox.close();parent.spinnerShow();</script>";
        exit();
    }    
}
if($_GET['med_type']=="admit"){
mysql_select_db($database_hos, $hos);
$query_rs_drug = "select concat(d.name,' ',d.strength) as drugname,u.name1,u.name2,u.name3,i.note,s.name1 as s_name1,s.name2 as s_name2,s.name3 as s_name3,i.sp_use,i.qty from medplan_ipd i left outer join drugitems d on d.icode=i.icode left outer join drugusage u on u.drugusage=i.drugusage left outer join sp_use s on s.sp_use=i.sp_use where i.med_plan_number='".$_GET['med_plan_number']."'";
//echo $query_rs_order_qty;
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
//echo $row_rs_drug['drugname'];
}
else if($_GET['med_type']=="dc"){
mysql_select_db($database_hos, $hos);
$query_rs_drug = "select i.hos_guid,concat(d.name,' ',d.strength) as drugname,u.name1,u.name2,u.name3,s.name1 as s_name1,s.name2 as s_name2,s.name3 as s_name3,i.sp_use,i.qty,k.hos_guid as hos_guid2 from opitemrece i left outer join drugitems d on d.icode=i.icode left outer join drugusage u on u.drugusage=i.drugusage left outer join sp_use s on s.sp_use=i.sp_use left outer join ".$database_kohrx.".kohrx_ipd_profile_check k on k.hos_guid=i.hos_guid where i.hos_guid='".$_GET['hos_guid']."'";
//echo $query_rs_order_qty;
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
//echo $row_rs_drug['drugname'];
	
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php include('java_css_file.php'); ?>
<style>
    body{overflow: hidden;}
</style> 
<script>
    $(document).ready(function(){
        $('#qty').focus();    
//SELECT TEXT RANGE
$.fn.selectRange = function(start, end) {
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};  
//SET CURSOR POSITION
$.fn.setCursorPosition = function(pos) {
  this.each(function(index, elem) {
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  });
  return this;
};        
    $('#qty').selectRange(0,3);    
    });
 
</script>    
</head>

<body class="bg-light">
<nav class="navbar navbar-dark" style="background-color: #80BA9D">
  <a class="navbar-brand font14" href="#"><i class="fas fa-check " style="font-size: 14px;"></i>&nbsp;บันทึกจัดยาผู้ป่วยใน</a>    
</nav>
<div class="card m-2">    
<div class="card-body p-2" style="font-size: 14px;">
    <div class="font16 font-weight-bold">
        <?php echo $row_rs_drug['drugname']; ?>
    </div>
    <div style="padding-left: 20px">
    <?php if($row_rs_drug['sp_use']==""){ ?>
        <div>
            <?php echo $row_rs_drug['name1']; ?>
        </div>    
        <div>
            <?php echo $row_rs_drug['name2']; ?>
        </div>    
        <div>
            <?php echo $row_rs_drug['name3']; ?>
        </div> 
    <?php } else { ?>
    <div>
        <?php echo $row_rs_drug['s_name1']; ?>
    </div>    
    <div>
        <?php echo $row_rs_drug['s_name2']; ?>
    </div>    
    <div>
        <?php echo $row_rs_drug['s_name3']; ?>
    </div>  
    <?php } ?>
    </div>
    </div> 
<?php if($row_rs_drug['note']){ ?>    
<div class="card-footer p-2" style="background-color: #F4889A; color: white"><?php echo $row_rs_drug['note']; ?></div>
<?php } ?>
</div> 
<form method="post" action="detail_ipd_profile_check_add.php">	    
<div class="p-2 text-center">
    <center>
    <div class="row text-right">
        <div class="col-auto">
            <input id="qty" name="qty" class="form-control" type="input" value="<?php echo $row_rs_drug['qty']; ?>" />
        </div>
        <div class="col-auto">
            <input type="submit" class="btn btn-success" id="save" name="save" value="บันทึก"/>
            <?php if($row_rs_drug['hos_guid2']!=""){ ?>
            <input type="submit" class="btn btn-danger" id="delete" name="delete" value="ลบ"/>            
            <?php } ?>
            <input type="hidden" id="med_plan_number" name="med_plan_number" value="<?php echo $_GET['med_plan_number']; ?>" />
            <input type="hidden" id="hos_guid" name="hos_guid" value="<?php echo $_GET['hos_guid']; ?>" />            <input type="hidden" id="order_date" name="order_date" value="<?php echo $_GET['order_date']; ?>" />
            <input type="hidden" id="med_type" name="med_type" value="<?php echo $_GET['med_type']; ?>" />
            <input type="hidden" id="check_type" name="check_type" value="<?php echo $_GET['check_type']; ?>" />
		</div>
    </div> 
    </center>    
</div>
</form>    
</body>
</html>
<?php mysql_free_result($rs_drug); ?>