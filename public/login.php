<?php require_once('Connections/hos.php'); ?>
<?php 
ob_start();
session_start();
?>
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

$get_ip=$_SERVER["REMOTE_ADDR"];


	if(@$_POST['username_log']!=""&&@$_POST['password_log']!=""){
mysql_select_db($database_hos, $hos);
$query_rs_login = "select o.*,k.right_opd,k.right_ipd,k.right_admin,k.right_finance,d.name as deptname,doc.position_id from opduser o left outer join hospital_department d on d.id=o.hospital_department_id left outer join ".$database_kohrx.".kohrx_user_setting k on k.doctorcode=o.doctorcode left outer join doctor doc on doc.code=o.doctorcode where o.loginname='".$_POST['username_log']."' and o.passweb='".md5($_POST['password_log'])."'";
$rs_login = mysql_query($query_rs_login, $hos) or die(mysql_error());
$row_rs_login = mysql_fetch_assoc($rs_login);
$totalRows_rs_login = mysql_num_rows($rs_login);

if($totalRows_rs_login<>0){
mysql_select_db($database_hos, $hos);
$query_update = "update ".$database_kohrx.".kohrx_queue_caller_channel set doctor_type='".$_POST['doctor_type']."',cursor_position='".$_POST['cursor']."' where ip='".$get_ip."'";
$rs_update = mysql_query($query_update, $hos) or die(mysql_error());

// ถ้าไม่ติ๊กถูก Login
$_SESSION['doctor_type']=$_POST['doctor_type'];
$_SESSION["doctorcode"]=$row_rs_login['doctorcode'];
$_SESSION["position_id"]=$row_rs_login['position_id'];
$_SESSION["username_log"]=$row_rs_login['loginname'];
$_SESSION["password_log"]=$row_rs_login['passweb'];
$_SESSION["r_opd"]=$row_rs_login['right_opd'];
$_SESSION["r_ipd"]=$row_rs_login['right_ipd'];
$_SESSION["r_admin"]=$row_rs_login['right_admin'];
$_SESSION["r_finance"]=$row_rs_login['right_finance'];

if(strpos($row_rs_login['accessright'],'ADMIN') == true){
$_SESSION["member_status"]="admin";
}
else{
$_SESSION["member_status"]="user";
}

$_SESSION["nameuser"]=$row_rs_login['name'];
$_SESSION["deptname"]=$row_rs_login['deptname'];
$_SESSION["deptid"]=$row_rs_login['hospital_department_id'];

//ค้นหาข้อมูลการ login
mysql_select_db($database_hos, $hos);
$query_rs_login2 = "select * from ".$database_kohrx.".kohrx_login_check where login_name='".$_SESSION['username_log']."' and ipaddress='".$get_ip."' and substr(last_time,1,10)=CURDATE()";
$rs_login2 = mysql_query($query_rs_login2, $hos) or die(mysql_error());
$row_rs_login2 = mysql_fetch_assoc($rs_login2);
$totalRows_rs_login2 = mysql_num_rows($rs_login2);

    //ถ้าพบ
	if($totalRows_rs_login2<>0){
	mysql_select_db($database_hos, $hos);
	$update = "update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name='".$_SESSION['username_log']."' and substr(last_time,1,10)=CURDATE()";
	$rs_update = mysql_query($update, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','update ".$database_kohrx.".kohrx_login_check set last_time=NOW() where login_name=\'".$_SESSION['username_log']."\' and substr(last_time,1,10)=CURDATE()')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	}
//ถ้าไม่พบ
	else{
	mysql_select_db($database_hos, $hos);
	$insert = "insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value ('".$_SESSION['username_log']."','".$get_ip."',NOW())";
	$rs_insert = mysql_query($insert, $hos) or die(mysql_error());
	
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_login_check (login_name,ipaddress,last_time) value (\'".$_SESSION['username_log']."\',\'".$get_ip."\',NOW())')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());
	}

mysql_free_result($rs_login2);

//บนทึกข้อมูลลงบน kohrx_login_log
	mysql_select_db($database_hos, $hos);
	$insert2 = "insert into ".$database_kohrx.".kohrx_login_log (login_name,ipaddress,time_check) value ('".$_SESSION['username_log']."','".$get_ip."',NOW())";
	$rs_insert2 = mysql_query($insert2, $hos) or die(mysql_error());
		//บันทึกลง log
		mysql_select_db($database_hos, $hos);
		$insert_log = "insert into replicate_log (`event_time`,`client`,`sql`) values (current_timestamp,'KOHRX','insert into ".$database_kohrx.".kohrx_login_log (login_name,ipaddress,time_check) value (\'".$_SESSION['username_log']."\',\'".$get_ip."\',NOW())')";
		$rs_insert_log = mysql_query($insert_log, $hos) or die(mysql_error());

//exit();
session_write_close(); 
header("location:index.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม

}
 
else {
header("location:login.php"); //ไม่ถูกต้องให้กับไปหน้าเดิม
}

mysql_free_result($rs_login);

}

$ip=$_SERVER['REMOTE_ADDR'];


mysql_select_db($database_hos, $hos);
$query_rs_channel = "select * from ".$database_kohrx.".kohrx_queue_caller_channel where ip='".$ip."'";
$rs_channel = mysql_query($query_rs_channel, $hos) or die(mysql_error());
$row_rs_channel = mysql_fetch_assoc($rs_channel);
$totalRows_rs_channel = mysql_num_rows($rs_channel);

//check version
if(urlExists('http://dispensing.kohrx.com/version.txt')){
$myfile = fopen("version.txt", "r") or die("Unable to open file!");
$version= explode('|',fgets($myfile));

mysql_select_db($database_hos, $hos);
$query_rs_version = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='27'";
$rs_version = mysql_query($query_rs_version, $hos) or die(mysql_error());
$row_rs_version = mysql_fetch_assoc($rs_version);
$totalRows_rs_version = mysql_num_rows($rs_version);

mysql_select_db($database_hos, $hos);
$query_rs_structure = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='34'";
$rs_structure = mysql_query($query_rs_structure, $hos) or die(mysql_error());
$row_rs_structure = mysql_fetch_assoc($rs_structure);
$totalRows_rs_structure = mysql_num_rows($rs_structure);

//update version to mysql ถ้าไม่ต้องการเช็ค version เอาออก
	if($totalRows_rs_version<>0){
		mysql_select_db($database_hos, $hos);
		$query_rs_update = "update ".$database_kohrx.".kohrx_dispensing_setting set value='".$version[0]."' where id='27'";
		$rs_update = mysql_query($query_rs_update, $hos) or die(mysql_error());
		}
	else {
		mysql_select_db($database_hos2, $hos2);
		$query_rs_update = "insert into ".$database_kohrx.".kohrx_dispensing_setting (id,name,value) values ('27','version','".$version[0]."')";
		$rs_update = mysql_query($query_rs_update, $hos2) or die(mysql_error());	
		}

$ver = file('http://dispensing.kohrx.com/version.txt');
		foreach($ver as $ver1){
			$ver2=explode('|',$ver1);
		}

	mysql_free_result($rs_version);
fclose($myfile);

}

//////////////////////////////////////////////////////

//ตรวจสอบ version อีกครั้ง
mysql_select_db($database_hos, $hos);
$query_rs_version = "select * from ".$database_kohrx.".kohrx_dispensing_setting where id='27'";
$rs_version = mysql_query($query_rs_version, $hos) or die(mysql_error());
$row_rs_version = mysql_fetch_assoc($rs_version);
$totalRows_rs_version = mysql_num_rows($rs_version); 

$new_version=$row_rs_version['value'];

mysql_free_result($rs_version);

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('java_css_file.php'); ?>    
<style>
@charset "utf-8";


@import url//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css);



div.main{
    background: #0264d6; /* Old browsers */
background: -moz-radial-gradient(center, ellipse cover,  #0264d6 1%, #1c2b5a 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(1%,#0264d6), color-stop(100%,#1c2b5a)); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* IE10+ */
background: radial-gradient(ellipse at center,  #0264d6 1%,#1c2b5a 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0264d6', endColorstr='#1c2b5a',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
height:calc(100vh);
width:100%;
}

[class*="fontawesome-"]:before {
  font-family: 'FontAwesome', sans-serif;
}

/* ---------- GENERAL ---------- */

* {
  box-sizing: border-box;
    margin:0px auto;

  &:before,
  &:after {
    box-sizing: border-box;
  }

}

body {
   
    color: #606468;
  font: 87.5%/1.5em 'Open Sans', sans-serif;
  margin: 0;
}

a {
	color: #eee;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

input {
	border: none;
	font-family: 'Open Sans', Arial, sans-serif;
	font-size: 14px;
	line-height: 1.5em;
	padding: 0;
	-webkit-appearance: none;
}

p {
	line-height: 1.5em;
}

.clearfix {
  *zoom: 1;

  &:before,
  &:after {
    content: ' ';
    display: table;
  }

  &:after {
    clear: both;
  }

}

.container {
  left: 50%;
  position: fixed;
  top: 50%;
  transform: translate(-50%, -50%);
}

/* ---------- LOGIN ---------- */

#login form{
	width: 250px;
}
#login, .logo{
    display:inline-block;
    width:40%;
}
#login{
  padding: 0px 0px;
  width: 59%;
}
.logo{
color:#fff;
font-size:50px;
  line-height: 125px;
}

#login form span.fa {
	background-color: #fff;
	border-radius: 3px 0px 0px 3px;
	color: #000;
	display: block;
	float: left;
	height: 50px;
    font-size:24px;
	line-height: 50px;
	text-align: center;
	width: 50px;
}

#login form input {
	height: 50px;
}
fieldset{
    padding:0;
    border:0;
    margin: 0;

}
#login form input[type="text"], input[type="password"] {
	background-color: #fff;
	border-radius: 0px 3px 3px 0px;
	color: #000;
	margin-bottom: 1em;
	padding: 0 16px;
	width: 200px;
}

#login form input[type="submit"] {
  border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  background-color: #000000;
  color: #eee;
  font-weight: bold;
  /* margin-bottom: 2em; */
  text-transform: uppercase;
  padding: 5px 10px;
  height: 30px;
}

#login form input[type="submit"]:hover {
	background-color: #d44179;
}

#login > p {
	text-align: center;
}

#login > p span {
	padding-left: 5px;
}
.middle {
  display: flex;
  width: 600px;
    
}
</style>
<script>
function copyToClipboard(text) {
    window.prompt("กรุณากด: Ctrl+C, แล้วกด Enter หรือคลิ๊กขวาแล้ว copy ข้อความในช่อง", text);
}
 
// Use JQuery
//
$(document).ready(function() { 
$('#username_log').focus();   
});

    function setNextFocus(objId){
		        if (event.keyCode == 13){

			if(objId=="login"){
				document.forms['form1'].submit();
				}
			else{
	        var obj=document.getElementById(objId);
            if (obj){
                obj.focus();
			}
			}
				}
	}

</script>
<?php
$ua=getBrowser();
?>

<title>easy DISPENSING</title>
<div style="position:absolute; width:100%; padding:10px;">
<?php if((isset($_POST['username_log'])||isset($_POST['password_log']))&&($_POST['username_log']!=""||$_POST['password_log']!="")){ ?>
<div class="alert alert-primary alert-dismissible fade show text-center" role="alert" style="opacity:0.8" >
    <?php if($_POST['username_log']!=""&&$_POST['password_log']==""){?>กรุณากรอก password ด้วยครับ
  <?php } 
    else if($_POST['username_log']==""){?>
  กรุณากรอก username ด้วยครับ
<?php } 
  else if($success==0){ ?>username หรือ password ไม่ถูกต้อง
<?php } ?>  

  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php } ?>
</div>

<link rel="icon" sizes="30x30" href="images/Measurement-Units-Temperature-icon.png" />
<div class="main">
    
    
<div class="container">
<center>
<div class="middle">
      <div id="login">

  <form  method="post" action="login.php">

          <fieldset class="clearfix">
			<div style=" font-size:18px;color:#FFFFFF;">easy DISPENSING</div>
			<div style=" font-size:12px;color:#FFFFFF; margin-bottom:10px;">โปรแกรมช่วยตรวจสอบก่อนจ่ายยา</div>
            <p ><span class="fa fa-user"></span><input type="text"  Placeholder="Username" name="username_log" id="username_log" style="outline: none" onkeypress="setNextFocus('password_log');" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
            <p><span class="fa fa-lock"></span><input type="password"  Placeholder="Password" name="password_log" id="password_log" style="outline: none" onkeypress="setNextFocus('login');" required></p> <!-- JS because of IE support; better: placeholder="Password" -->

<div class="form-row">
    <div class="form-group col-md-6">
<label for="cursor" style="color:#FFFFFF">Cursor</label>
                <select name="cursor" id="cursor" class="form-control"  >
                  <option value="queue" <?php if (!(strcmp("queue", $row_rs_channel['cursor_position']))) {echo "selected=\"selected\"";} ?>>OPD queue</option>
                  <option value="hn_search" <?php if (!(strcmp("hn_search", $row_rs_channel['cursor_position']))) {echo "selected=\"selected\"";} ?>>HN</option>
                  <option value="ipd" <?php if (!(strcmp("ipd", $row_rs_channel['cursor_position']))) {echo "selected=\"selected\"";} ?>>AN</option>
                </select>
    </div>
    <div class="form-group col-md-6">
      <label for="doctor_type" style="color:#FFFFFF">Doctor code</label>
                <select name="doctor_type" class=" form-control" id="doctor_type">
                  <option value="1" <?php if (!(strcmp(1, $row_rs_channel['doctor_type']))) {echo "selected=\"selected\"";} ?>>ผู้พิมพ์</option>
                  <option value="2" <?php if (!(strcmp(2, $row_rs_channel['doctor_type']))) {echo "selected=\"selected\"";} ?>>ผู้จัด</option>
                  <option value="3" <?php if (!(strcmp(3, $row_rs_channel['doctor_type']))) {echo "selected=\"selected\"";} ?>>ผู้ตรวจสอบ</option>
                  <option value="4" <?php if (!(strcmp(4, $row_rs_channel['doctor_type']))) {echo "selected=\"selected\"";} ?>>ผู้จ่าย</option>
                  <option value="5" <?php if (!(strcmp(5, $row_rs_channel['doctor_type']))) {echo "selected=\"selected\"";} ?>>การเงิน</option>
                </select>
    </div>
  </div>  
         

             <div>
                                <span style="width:48%; text-align:left;  display: inline-block;"></span>
                                <span style="width:50%; text-align:right;  display: inline-block;"><input type="submit" value="LogIn"></span>
                            </div>

          </fieldset>
<div class="clearfix"></div>
        </form>

        <div class="clearfix"></div>

      </div> <!-- end login -->
      <div class="logo" style=" margin-left:6px;border-left:1px #FFFFFF solid"><img src="images/dispensing.png" width="380" height="100" />
      <div style="font-size:20px; margin-top:0px; margin-left:10px; height:100px;">
      <span class="table_head_small_bord" style="color:#FFF; ">Version</span> <span style="color:#FFFF00"><?php
$myfile = fopen("version.txt", "r") or die("Unable to open file!");
$cur_ver= explode('|',fgets($myfile));
echo $cur_ver[0];
fclose($myfile);
?>&ensp;<i class="fas fa-box-open text-white cursor" onClick="window.open('http://sites.google.com/view/easydispensing','_new')"></i></span><?php if($ver2[0]!=""&&$ver2[0]!=$new_version){?><div style="width:180px; height:23px; padding-top:5px; margin-top:-95px; cursor:pointer" onclick="window.open('<?php echo $ver2[1]; ?>','_new')" class="new_update font12 rounded_bottom rounded_top font_border" align="center">มีเวอร์ชั่นออกใหม่ คือ <?php echo $ver2[0]; ?> !</div><?php } ?></div>

      </div>
      
      </div>
</center>
    </div>

</div>
</body>
</html>
