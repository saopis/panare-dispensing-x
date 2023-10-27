<?php require_once('Connections/hos.php'); ?>
<?php
// Parameter
include('include/function.php');
$date=explode('/',(explode(' ',$_POST['datetimepicker'])[0]));
$order_date= $date[2]."-".$date[1]."-".$date[0];
$order_time= explode(' ',$_POST['datetimepicker'])[1];
if($_POST['remark']==""){
	$remark="NULL";
}		  
else{
	$remark="'".$_POST['remark']."'";
}
$an=$_POST['an'];

//include_once('config.php');
//$file = $_FILES["fileUpload"]["name"];
$file=iconv("UTF-8", "TIS-620", $HTTP_POST_FILES["fileUpload"]["name"]);
$file_image		=	'';
if($_FILES['fileUpload']['name']!=""){
    extract($_REQUEST);
	$infoExt =   getimagesize($_FILES['fileUpload']['tmp_name']);
    $image = $_FILES['fileUpload']['tmp_name'];
    
	if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
		$file	=	preg_replace('/\\s+/', '-', time().$file);
		$path   =   'uploads/'.$file;
		$imageFile = curl_file_create('/ipd/uploads/'.$file, 'image/jpg', $file);
		//move_uploaded_file($_FILES['fileUpload']['tmp_name'],$path);
		$data   =   array(
			'img_name'=>$file,
			'img_order'=>1,
 			'imageFile' => $imageFile,			
		);
		//$insert     =   $db->insert('dispensing.kohrx_images_upload',$data);
		//if($insert){ echo 1; } else { echo 0; }
        mysql_select_db($database_hos, $hos);
        $query_ipt = "select i.an,i.hn,concat(p.pname,p.fname,' ',p.lname) as patient_name from ipt i left outer join patient p on p.hn=i.hn where i.an='".$an."'";
        $ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
        $row_ipt = mysql_fetch_assoc($ipt);
        $totalRows_ipt = mysql_num_rows($ipt);
        
        $ptname=$row_ipt['patient_name'];
        
        mysql_free_result($ipt);
        
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into dispensing.kohrx_ipd_order_image (image_name,an,order_date,order_time,capture_date,remark) value ('".$image."','".$an."','".$order_date."','".$order_time."',NOW(),'".$remark."')";
		//echo $query_insert;
		//exit();
		$db_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
		if($db_insert){ echo 1;

                        //Message

function notify_message($params, $token) {
  $queryData = array(
    'message'          => $params["message"],
    'stickerPackageId' => $params["stickerPkg"],
    'stickerId'        => $params["stickerId"],
    'imageThumbnail'   => $params["imageThumbnail"],
    'imageFullsize'    => $params["imageFullsize"],
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

    $mymessage = "มีการส่งภาพ order sheet \n"; //Set new line with '\n'
    $mymessage .= "ชื่อ: ".$ptname." \n";
    $mymessage .= "AN: ".$an." \n";
    $mymessage .= "วันที่สั่ง :".dateThai($order_date)." \n";
    //$imageFile = new CURLFile('/ipd/uploads/'.$file, $file); // Local Image file Path
	$imageFile=new CurlFile('/ipd/uploads/'.$file, 'image/jpg', $file);				   
                       
define('LINE_API', "https://notify-api.line.me/api/notify");
$token = "x6BbhnPNyC3y15GMYpfOcGa3ysnIgKQBG7wvV4OHCXo"; //ใส่Token ที่copy เอาไว้
$params = array(
  "stickerPkg"     => 2, //stickerPackageId
  "stickerId"      => 34, //stickerId
  "message"        => $mymessage, //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
  "imageThumbnail" => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", // max size 240x240px JPEG
  "imageFullsize"  => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", //max size 1024x1024px JPEG
);
$res = notify_message($params, $token);
// print_r($res);
 
			                       
                      } else {
                        echo 0;
                      }
		
	}else{
		echo 2;
	}
}
?>
