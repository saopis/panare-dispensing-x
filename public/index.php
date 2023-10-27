<?
ob_start();
session_start();
?>
<?php require_once('Connections/hos.php'); ?>
<?php require('include/get_channel.php'); ?>
<?php 
include('include/function.php');
include('include/function_sql.php');
// check การ login
mysql_select_db($database_hos, $hos);
$query_delete = "delete from ".$database_kohrx.".kohrx_login_check where last_time < CURDATE()";
$delete = mysql_query($query_delete, $hos) or die(mysql_error());

if($_SESSION["username_log"]==""){
header("location: login.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
exit();
}
else{
mysql_select_db($database_hos, $hos);
$query_login_check = "SELECT *,format((TIMESTAMPDIFF(SECOND,last_time, NOW())/60),2) as timediff from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION["username_log"]."' and ipaddress='".$get_ip."'";
$login_check = mysql_query($query_login_check, $hos) or die(mysql_error());
$row_login_check = mysql_fetch_assoc($login_check);
$totalRows_login_check = mysql_num_rows($login_check);

    //echo $query_login_check;
    //exit();

if($totalRows_login_check<>0){
//ถ้าเกิน 1 ชั่วโมงให้ logout
if($row_login_check['timediff']>=60){
echo "<script>window.location='login.php';</script>";
exit();
	}
}
else {
	header("location: login.php");	
	}
mysql_free_result($login_check);
}


//===== setting ==========//
mysql_select_db($database_hos, $hos);
$query_rs_setting = "select * from ".$database_kohrx.".kohrx_dispensing_setting order by id ASC";
$rs_setting = mysql_query($query_rs_setting, $hos) or die(mysql_error());
$row_rs_setting = mysql_fetch_assoc($rs_setting);
$totalRows_rs_setting = mysql_num_rows($rs_setting);

$i=0; do{ $i++;
$row_setting[$i]=$row_rs_setting['value'];} 
while($row_rs_setting = mysql_fetch_assoc($rs_setting));

mysql_free_result($rs_setting);

//===== setting ==========//


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="images/dispensing_icon.png" />
<title>easy DISPENSING</title>
<?php include('java_css_file.php'); ?>    
<style>
html,body { height:100%; }

::-webkit-scrollbar { width: 15px; }

::-webkit-scrollbar-track {
    box-shadow: inset 0 0 10px 10px #E6E6E6;
    border: solid 3px transparent;
}

::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 10px 10px #CCCCCC;
    border: solid 3px transparent;
}
.kohrx-search{
	background-color:#F0F0F0;
	/*margin-top:62px;*/
	}
.dispen-body{
	padding:0px;
}
.modal-header-success {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #5cb85c;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
     border-top-left-radius: 4px;
     border-top-right-radius: 4px;
}
.modal-header-gray {
    color: #666;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #CCC;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
     border-top-left-radius: 4px;
     border-top-right-radius: 4px;
}

.modal-header-warning {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #f0ad4e;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-danger {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #d9534f;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-info {
    color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #5bc0de;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;
}
.modal-header-primary {
	color:#fff;
    padding:9px 15px;
    border-bottom:1px solid #eee;
    background-color: #428bca;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
     border-top-left-radius: 5px;
     border-top-right-radius: 5px;

}
.spinner {
  position: fixed;
  z-index: 1;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  width: 50px;
  height: 50px;
  margin: auto;
  }

.shadow {
  &.top {
    box-shadow: 0px -15px 10px -15px #111;    
  }
  &.bottom {
    box-shadow: 0px 0px 8px 2px #000000;    
  }
  &.left {
    box-shadow: -15px 0px 10px -15px #111;    
  }
  &.right {
    box-shadow: 15px 0px 10px -15px #111;    
  }
}

/* The side navigation menu */
.sidenav {
  margin-top: 110px;
  height: 100%; /* 100% Full-height */
  width: 0; /* 0 width - change this with JavaScript */
  position: fixed; /* Stay in place */
  z-index: 1; /* Stay on top */
  top: 0; /* Stay at the top */
  left: 0;
  background-color: #111; /* Black*/
  overflow-x: hidden; /* Disable horizontal scroll */
  padding-top: 60px; /* Place content 60px from the top */
  transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
}

/* The navigation menu links */
.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

/* When you mouse over the navigation links, change their color */
.sidenav a:hover {
  color: #f1f1f1;
    background-color: #000000;
    }

/* Position and style the close button (top right corner) */
.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
  transition: margin-left .5s;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
body.modal-open, .modal-open .navbar-fixed-top, .modal-open .navbar-fixed-bottom {
    padding-right: 0px !important;
    overflow-y: auto;
}
.digital-clock {
  color:#666;
  font-size:16px;
  padding:5px;
  text-align: center;
}

input[type=search]::-webkit-search-cancel-button {
    -webkit-appearance: searchfield-cancel-button;
	cursor: pointer;

}

/* The Overlay (background) */
.overlay {
  /* Height & width depends on how you want to reveal the overlay (see JS below) */   
  height: 100%;
  width: 0;
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  background-color: rgb(0,0,0); /* Black fallback color */
  background-color: rgba(0,0,0, 0.9); /* Black w/opacity */
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
}

/* Position the content inside the overlay */
.overlay-content {
  position: relative;
  top: 25%; /* 25% from the top */
  width: 100%; /* 100% width */
  text-align: center; /* Centered text/links */
  margin-top: 30px; /* 30px top margin to avoid conflict with the close button on smaller screens */
}

/* The navigation links inside the overlay */
.overlay a {
  padding: 8px;
  text-decoration: none;
  font-size: 36px;
  color: #818181;
  display: block; /* Display block instead of inline */
  transition: 0.3s; /* Transition effects on hover (color) */
}

/* When you mouse over the navigation links, change their color */
.overlay a:hover, .overlay a:focus {
  color: #f1f1f1;
}

/* Position the close button (top right corner) */
.overlay .closebtn {
  position: absolute;
  top: 20px;
  right: 45px;
  font-size: 60px;
}

#navbar {
  overflow: hidden;
  background-color: #f1f1f1;
  padding: 90px 10px;
  transition: 0.4s;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 99;
}

/* When the height of the screen is less than 450 pixels, change the font-size of the links and position the close button again, so they don't overlap */
@media screen and (max-height: 450px) {
  .overlay a {font-size: 20px}
  .overlay .closebtn {
    font-size: 40px;
    top: 15px;
    right: 35px;
  }
}
@media screen and (max-width: 580px) {
  #navbar {
    padding: 20px 10px !important;
  }
  #navbar a {
    float: none;
    display: block;
    text-align: left;
  }
  #navbar-right {
    float: none;
  }
}
    
@media (max-width: 1000px) {
	.scrollable-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
    }	
	
	html,body{
    overflow: hidden
    }
}

</style>
	
<?php include('java_function.php'); ?>
<?php include('java_function2.php'); ?>

<body>
<?php require('include/navbar/navbar.php'); ?>
	
<!-- slide bar -->
    <div id="myNav" class="overlay" >
        <div class="overlay-content">
  <a href="javascript:void(0)" style="text-decoration: none" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="#" id="emr" style="text-decoration: none"><i class="fas fa-user-injured"></i>&emsp;<span style="font-size: 18px;">ประวัติรับบริการ</span></a>
  <a href="#" id="receive-history" style="text-decoration: none"><i class="fas fa-prescription"></i>&emsp;<span style="font-size: 18px;">ประวัติจ่ายยา</span></a>
        <a href="#" style="text-decoration: none" id="rx-history"><i class="fas fa-pills"></i>&emsp;<span style="font-size: 18px;">ยาที่เคยได้รับ</span></a>
  <a href="#" style="text-decoration: none"><i class="fas fa-comment-dots"></i>&emsp;<span style="font-size: 18px;">บันทึก Note</span></a>
        </div>
</div>
<!-- slide bar -->
<div id="main">
<div id="search" class="kohrx-search container-fluid " style="background-color: #B1C5C3" >
    
<div class="form-group row align-items-center " style="padding:0px;">
    <div class=" col" style=" display: flex;justify-content: left;align-items: center;">
<form class="form-inline mt-1"><label for="vstdate" class="text-dark">วันที่</label>&ensp;<input type="text" name="vstdate" id="vstdate" value="<?php echo date('d/m/').(date('Y')+543); ?>" readonly class="form-control form-control-plaintext thfont font16 font-weight-bolder" style=" padding:2px; padding-left: 2px; padding-right:2px; width:95px; height:30px; background-color:#E6E6FA">&nbsp;<i class="btn btn-info fas fa-calendar-alt" style="cursor: pointer; padding: 5px;" id="calendar" data-placement="bottom" title="คลิ๊กเลือกวันที่"></i>&ensp;<div class="digital-clock text-dark">00:00:00</div>&nbsp;<input name="queue" type="search" id="queue" placeholder="Q" class="form-control" style="width:60px; height:30px; padding-left:5px; padding-right:2px;"  />&ensp;<input name="hn" placeholder="HN" type="search" id="hn" class="form-control" style=" padding-left:2px; padding-right:2px;width:100px; height:30px;" />&ensp;<input name="an" placeholder="AN" type="search" id="an" class="form-control" style=" padding-left:2px; padding-right:2px;width:100px; height:30px;" /></form>&ensp;
<iframe style="margin-top:-5px; margin-left: 10px; width: 0px; height: 0px; border: 0px " id="caller-panel" name="caller-panel" src="" ></iframe>	
<div style="margin-top: -5px;">
		
</div>        
<div class="col-sm-auto" style="margin-top:-5px;"><button class="btn btn-info btn-sm"  onclick="q_page_load('dispen-body','queue_list_iframe<?php if($row_channel['queue_list']=="2"){ echo ""; }else { echo "2";} ?>.php','queue_list<?php if($row_channel['queue_list']=="2"){ echo ""; }else { echo "2";} ?>.php',1);"><i class="fas fa-sort-amount-down-alt"></i>&nbsp;Q list</button></div>
</div>
    <div class="col " style="-ms-flex: 0 0 300px;flex: 0 0 300px; padding-top:5px; padding-bottom:5px;">
    <div class="row" style="margin-top:-5px;">
    <i style=" border-left:1px double #8D8D8D; height:30px; margin-top:0px"></i>
    <div class="col font12" style="color: #666;margin-top:-5px;">	
    <span class=" font14"><nobr><?php echo doctorname($_SESSION['doctorcode']); ?></nobr></span>
    <div class="row" style="margin-bottom: -5px;">
        <div class="col">
          <?php echo "<span class=\"font_bord\">".$row_channel['channel_name']."</span> : ". $row_channel['room_name']; ?>
        </div>
    </div>
    </div>
 <div class="col-sm-auto align-middle">
 <!-- Example split danger button -->
<div class="btn-group dropleft">
  <button type="button" class="btn btn-secondary" onclick="if(confirm('ออกจากระบบจริงหรือไม่?')==true){ window.location='logout.php'; }"><i class="fas fa-sign-out-alt"></i></button>
  <?php if($_SESSION['r_admin']=='Y'){ ?>
	<button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="javascript:valid(0)" onclick="alertload('dispen_setting.php','90%','90%');">ตั้งค่าท่วไป</a>
    <a class="dropdown-item" href="javascript:valid(0)" onclick="alertload('menu_create.php','90%','600');">ตั้งค่าเมนู</a>
    <a class="dropdown-item" href="javascript:valid(0)" onclick="alertload('user_setting.php','90%','90%');">ตั้งค่าสิทธิ์เข้าใช้งาน</a>
    <a class="dropdown-item" href="javascript:valid(0)" onclick="alertload('upgrade_structure.php','90%','90%');">upgrade structure</a>
  </div>
<?php } ?>
</div>
 </div>
</div>
</div>
</div>
</div>

<!-- dispen-body-->
<div class="dispen-body mh-100" id="dispen-body" style="overflow:scroll;overflow-x:hidden;overflow-y:auto; height:83vh; margin-top: -16px"></div>
<!-- dispen-body-->
<!--indicator-->
<div id="indicator" align="center" class="spinner">
<div class="spinner-border" style="width: 5rem; height: 5rem;" role="status"></div>

  <span class="sr-only">Loading...</span>
</div>
<!--indicator-save-->
<div id="indicator-save" align="center" class="spinner">
  <button class="btn btn-primary" style="opacity: 0.7;">
    <span class="spinner-border " style="width: 5rem; height: 5rem;" role="status"></span>
    <span>กำลังโหลดข้อมูล</span>
  </button>
</div>
<!-- dispen-body-->

</div>
<!-- id=main -->


<!-- MODAL -->
<!-- large MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-gray">
<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fingerprint"></i>&ensp;ค้นหาผู้ป่วย</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>            </div>
            <div class="modal-body" id="modal-body-lg" style=" padding:0px;">

            </div>
        </div>
    </div>
</div>
<!-- large MODAL -->
    
<!-- lab MODAL -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="myModal2">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header modal-header-danger">
<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fingerprint"></i>&ensp;ค้นหาผู้ป่วย</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>            
        </div>
            <div class="modal-body" id="modal-body-lab" style=" padding:0px;">

            </div>
        
    </div>
  </div>
</div>
<!-- lab MODAL -->
<!-- The Modal normal-->
  <div class="modal fade" id="myModal-normal">
    <div class="modal-dialog modal-normal">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-light">
          <h5 class="modal-title text-dark" id="modal-title-normal"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal-body-normal" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
        
      </div>
    </div>
  </div>	
<!-- MODAL -->

<!-- The Modal list-->
  <div class="modal fade" id="myModal-xl">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-light">
          <h5 class="modal-title text-dark" id="modal-title-xl"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body" id="modal-body-xl" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
        
      </div>
    </div>
  </div>	
<!-- MODAL -->
<!-- The Modal danger-->
  <div class="modal fade" id="myModal-danger">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="modal-title-danger"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body p-0" id="modal-body-danger" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
      </div>
    </div>
  </div>	
<!-- MODAL danger -->
<!-- The Modal primary-->
  <div class="modal fade" id="myModal-primary">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modal-title-primary"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body p-0" id="modal-body-primary" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div>
        
        
      </div>
    </div>
  </div>	
<!-- MODAL danger -->
<!-- MODAL caller check -->
  <div class="modal fade" id="myModal-caller-check" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style=" height:250px">
      
        <!-- Modal Header -->
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="modal-title-caller-check"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body p-0" id="modal-body-caller-check" style="margin-top:0px;overflow:scroll;overflow-x:hidden;overflow-y:auto; height:60vh; z-index: 2"></div> 
        
      </div>
    </div>
  </div>	
<!-- MODAL caller check -->
	
    <!-- check login -->    
<div id="check_login"></div>
<!-- check login -->    
<!-- check login --> 
	
<div id="caller_panel" style="position: fixed;float:right; bottom:  5px; right: 5px;width: 600px; display: none;">
<div class="card" id="caller_body" style="height: 305px; border: solid 1px #1E1D1D">
    <div class="card-header bg-dark text-white"><span id="caller_header">ระบบเรียกชื่อ/คิวผู้ป่วย</span><span id="caller_header2" style="display: none"><i class="fas fa-headset"></i></span></div>    
    <div class="card-body p-0">
        <iframe id="caller_panel2" name="caller_panel2" style="width: 100%; height: 100%; border: 0px;"></iframe>    
    </div>    
</div>
<button class="btn btn-dark btn-sm position-absolute" id="btn_caller_down" style="top:10px; right:50px;"><i class="fas fa-caret-down font20 text-white"></i></button>
<button class="btn btn-dark btn-sm position-absolute" id="btn_caller_up" style="top:10px; right:50px;"><i class="fas fa-caret-up font20 text-white"></i></button>    
<button class="btn btn-dark btn-sm position-absolute" id="btn_caller_close" onClick="callerClose();" style="top:10px; right:10px;"><i class="fas fa-times font20 text-white"></i></button>    
</div>  

<!-- check login -->    

</body>
</html>
<?php mysql_free_result($channel); ?>