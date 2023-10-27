<?php require_once('Connections/hos.php'); ?>
<?php
date_default_timezone_set('Asia/Bangkok');

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
include('include/function_sql.php');
include('include/function.php');

              function notify_message($params, $token) {
              $queryData = array(
                'stickerPackageId' => $params["stickerPkg"],
                'stickerId'        => $params["stickerId"],
                'message'          => $params["message"],
                //'imageThumbnail'   => $params["imageThumbnail"],
                //'imageFullsize'    => $params["imageFullsize"],
              );
              $queryData = http_build_query($queryData, '', '&');
              $headerOptions = array(
                'http' => array(
                  'method'  => 'POST',
                  'header'  => "Content-Type: application/x-www-form-urlencoded\r\n"
                  . "Authorization: Bearer " . $token . "\r\n"
                  . "Content-Length: " . strlen($queryData) . "\r\n",
                  'content' => $queryData,
                ),
              );
              $context = stream_context_create($headerOptions);
              $result = file_get_contents(LINE_API, FALSE, $context);
              $res = json_decode($result);
              return $res;
            }                       


if(isset($_GET['allow'])&&($_GET['allow']=="Y")){
        mysql_select_db($database_hos, $hos);
        $query_user = "select u.user_name,r.officer_name from dispensing.kohrx_remote_desktop_request r left outer join dispensing.kohrx_remote_desktop_user u on u.id=r.user_id where r.id='".$_GET['id']."'";
        $rs_user = mysql_query($query_user, $hos) or die(mysql_error());
        $row_user = mysql_fetch_assoc($rs_user);
        $totalRows_user = mysql_num_rows($rs_user);  
    
        $username=$row_user['user_name'];
    
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_request set allow_by_admin='Y',date_allowed=NOW(),window_config='N' where id='".$_GET['id']."' ";
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

                $mymessage  = "\n".$row_user['officer_name']."\n"; //Set new line with '\n'
                $mymessage .= "ที่ร้องขอใช้งาน remotedesktop\n";
                $mymessage .= "ได้รับการอนุมัติแล้ว!!\n";
                $mymessage .= "วันที่: ".dateThai(date('Y-m-d'))."\n";
                $mymessage .= "เวลา : ".date("H:i")."\n";
                //$imageFile = new CURLFile('/ipd/uploads/'.$file, $file); // Local Image file Path
                //$imageFile=new CurlFile('/ipd/uploads/'.$file, 'image/jpg', $file);				   

                define('LINE_API', "https://notify-api.line.me/api/notify");
                $token = "c58zezc3sZAEZNGVaTP4odirYsyRD4MTzyik8n0mWKX"; //ใส่Token ที่copy เอาไว้
                //$token = "x6BbhnPNyC3y15GMYpfOcGa3ysnIgKQBG7wvV4OHCXo"; //ใส่Token ที่copy เอาไว้    

                $params = array(
                  //"stickerPkg"     => 2, //stickerPackageId
                  //"stickerId"      => 34, //stickerId
                  "message"        => $mymessage, //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
                  //"imageThumbnail" => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", // max size 240x240px JPEG
                  //"imageFullsize"  => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", //max size 1024x1024px JPEG
                );
                $res = notify_message($params, $token);
                // print_r($res);
   
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_request set notify_status='Y' where id='".$_GET['id']."'";
    //echo $query_insert;
    //exit();
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

        mysql_free_result($rs_user);
    
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
    echo "<center>อนุญาติให้กับ user : ".$username." เรียบร้อยแล้ว</center>";
    exit();

}

if(isset($_POST['save'])&&($_POST['save']=="บันทึก")){
        
        if((date('H:i:s')>"12:00:00" && date('H:i:s')<"13:00:00") or (date('H:i:s')>"17:00:00" && date('H:i:s')<"23:59:59") or (date('H:i:s')>"00:00:00" && date('H:i:s')<"08:00:00") ){
            echo "<script>alert('กรุณาขอใช้งาน remote desktop ในช่วงเวลา 8.00-12.00 และ 13.00-17.00 นอกจากนั้นกรุณาติดต่อ Admin'); window.location='remote_desktop_request.php'</script>";
            exit();            
        }

        mysql_select_db($database_hos, $hos);
        $query_time = "select CURTIME() as curtime";
        $rs_time = mysql_query($query_time, $hos) or die(mysql_error());
        $row_time = mysql_fetch_assoc($rs_time);
        $totalRows_time = mysql_num_rows($rs_time);  
    
        $curtime=$row_time['curtime'];
        
        mysql_free_result($rs_time);
        
    
	function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	$randompass=generateRandomString();
        mysql_select_db($database_hos, $hos);
        $query_user = "select * from dispensing.kohrx_remote_desktop_user where id='".$_POST['user']."' and user_active='N'";
        $rs_user = mysql_query($query_user, $hos) or die(mysql_error());
        $row_user = mysql_fetch_assoc($rs_user);
        $totalRows_user = mysql_num_rows($rs_user);  
        
        if($totalRows_user<>0){
            echo "<script>alert('user นี้ถูกใช้งานแล้วเมื่อไม่กี่วินาทีนี้ กรุณาขออีกครั้งโดยใช้ user อื่น'); window.location='remote_desktop_request.php'</script>";
            exit();
        }
        mysql_free_result($rs_user);
    
    mysql_select_db($database_hos, $hos);
    $query_insert = "insert into dispensing.kohrx_remote_desktop_request (hospcode,officer_name,user_id,request_date,notify_status,random_password,allow_by_admin,window_config) value ('".$_POST['hospcode']."','".$_POST['officer']."','".$_POST['user']."',NOW(),'N','".$randompass."','N','N')";
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());
    
    mysql_select_db($database_hos, $hos);
    $query_insert = "update dispensing.kohrx_remote_desktop_user set user_active='N' where id='".$_POST['user']."'";
    $rs_insert = mysql_query($query_insert, $hos) or die(mysql_error());

        mysql_select_db($database_hos, $hos);
        $query_new_user = "select id from dispensing.kohrx_remote_desktop_request order by id DESC limit 1";
        $rs_new_user = mysql_query($query_new_user, $hos) or die(mysql_error());
        $row_new_user = mysql_fetch_assoc($rs_new_user);
        $totalRows_new_user = mysql_num_rows($rs_new_user);  
    
        $new_id=$row_new_user['id'];
    
        mysql_free_result($rs_new_user);
    
        mysql_select_db($database_hos, $hos);
        $query_hospcode = "select hospcode,name from hospcode_cup where hospcode='".$_POST['hospcode']."'";
        //echo $query_hospcode;
        $hospcode = mysql_query($query_hospcode, $hos) or die(mysql_error());
        $row_hospcode = mysql_fetch_assoc($hospcode);
        $totalRows_hospcode = mysql_num_rows($hospcode);
    
        mysql_select_db($database_hos, $hos);
        $query_user = "select * from dispensing.kohrx_remote_desktop_user where id='".$_POST['user']."'";
        $rs_user = mysql_query($query_user, $hos) or die(mysql_error());
        $row_user = mysql_fetch_assoc($rs_user);
        $totalRows_user = mysql_num_rows($rs_user);  
    
    

                $mymessage = "มีการขอใช้ user Remote desktop\n"; //Set new line with '\n'
                $mymessage .= "ผู้ขอใช้: ".$_POST['officer']."\n";
                $mymessage .= "สถานบริการ: ".$row_hospcode['name']."\n";
                $mymessage .= "user: ".$row_user['user_name']."\n";
                $mymessage .= "http://159.192.104.60/organization/pharmacy/service/dispensingx/remote_desktop_request.php?id=".$new_id."&allow=Y";
                //$imageFile = new CURLFile('/ipd/uploads/'.$file, $file); // Local Image file Path
                //$imageFile=new CurlFile('/ipd/uploads/'.$file, 'image/jpg', $file);				   

                define('LINE_API', "https://notify-api.line.me/api/notify");
                
                //$token = "x6BbhnPNyC3y15GMYpfOcGa3ysnIgKQBG7wvV4OHCXo"; //ใส่Token ที่copy เอาไว้    
                $token = "8kaO80I2F02iOnzzfcHKb8QOHOSKLipWpx2JsEOfQJ7"; //ใส่Token ที่copy เอาไว้
                $params = array(
                  //"stickerPkg"     => 2, //stickerPackageId
                  //"stickerId"      => 34, //stickerId
                  "message"        => $mymessage, //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
                  //"imageThumbnail" => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", // max size 240x240px JPEG
                  //"imageFullsize"  => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", //max size 1024x1024px JPEG
                );
                $res = notify_message($params, $token);
                // print_r($res);
				$username=$row_user['user_name'];

}

mysql_select_db($database_hos, $hos);
$query_hospcode = "select hospcode,name from hospcode_cup group by hospcode";
$rs_hospcode = mysql_query($query_hospcode, $hos) or die(mysql_error());
$row_hospcode = mysql_fetch_assoc($rs_hospcode);
$totalRows_hospcode = mysql_num_rows($rs_hospcode);  

mysql_select_db($database_hos, $hos);
$query_user = "select * from dispensing.kohrx_remote_desktop_user where user_active='Y'";
$rs_user = mysql_query($query_user, $hos) or die(mysql_error());
$row_user = mysql_fetch_assoc($rs_user);
$totalRows_user = mysql_num_rows($rs_user);  

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="include/kohrx/css/kohrx.css" rel="stylesheet" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include('java_css_online.php'); ?>
	<!-- container-fluid -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="include/datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="include/datepicker/js/locales/bootstrap-datepicker.th.js"></script>    
<link rel="stylesheet" type="text/css" href="include/datepicker/css/datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>   

<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    
 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap5.min.css"/>

<?php include('include/bootstrap/datatable_report.php'); ?>

</head>

<body>
<nav class="navbar navbar-info" style="background-color: #7CA1A0;">
  <span class="text-white h5">
  <i class="fas fa-laptop-house"></i>&ensp;แบบฟอร์มขออนุญาติใช้ remote desktop
</span>
</nav>
<div class="p-3">
<?php if(!isset($_POST['save'])){ ?>
<form class="row g-3" method="POST" action="remote_desktop_request.php">
  <div class="col-md-6">
    <label for="hospcode" class="form-label">สถานบริการ</label>
    <select id="hospcode" name="hospcode" class="form-control">
        <?php do{ ?>
        <option value="<?php echo $row_hospcode['hospcode']; ?>"><?php echo $row_hospcode['name']; ?></option>
        <?php }while($row_hospcode = mysql_fetch_assoc($rs_hospcode)); ?>
    </select>
  </div>
  <div class="col-md-6">
    <label for="user" class="form-label">username ที่ขอใช้</label>
    <select id="user" name="user" class="form-control" required>
        <?php do{ ?>
        <option value="<?php echo $row_user['id']; ?>"><?php echo $row_user['user_name']; ?></option>
        <?php }while($row_user = mysql_fetch_assoc($rs_user)); ?>
    </select>  </div>
  <div class="col-12">
    <label for="officer" class="form-label">ชื่อ-นามสกุลผู้ขอใช้</label>
    <input type="text" class="form-control" id="officer" name="officer" placeholder="ชื่อ-นามสกุล ผู้ขอใช้" required>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary" id="save" name="save" value="บันทึก">บันทึก</button>
  </div>

</form>
<?php } else {?>
    <div class="p-5">
        <div class="card">
            <div class="card-body text-center" style="background-color: #EFEDE7;">
                <span class="h5">บันทึกข้อมูลเรียบร้อยแล้ว<br>กรุณาจดจำ user และ password ต่อไปนี้ (password ใช้ได้ต่อครั้ง)<br><span class="h3">Username : <?php echo $username; ?><br>Password : <span class="text-danger"><?php echo $randompass; ?></span></span><br>หลังจากบันทึกแล้วภายใน 5 นาทีแล้วยังไม่สามารถใช้ user ได้ กรุณาติดต่อ admin (0868704201: โก้ , 0850163279 :นุ) <a href="#" onClick="window.location='remote_desktop_request.php'">กลับสู่หน้าหลัก</a></span>
            </div>        
        </div>
    </div>
<?php } ?>    
</div>
</body>
</html>
<?php
mysql_free_result($rs_hospcode);
mysql_free_result($rs_user);
?>
