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

if($_POST['pt_type']=="1"){
	$pttype_text="Admit";
}
else if($_POST['pt_type']=="2"){
	$pttype_text="Continue";
}
else if($_POST['pt_type']=="3"){
	$pttype_text="Discharge";
}
//include_once('config.php');
//$file = $_FILES["fileUpload"]["name"];
$file=iconv("UTF-8", "TIS-620", $HTTP_POST_FILES["fileUpload"]["name"]);
$file_image		= '';

if($_FILES['fileUpload']['name']!=""){
  $name = $_FILES['fileUpload']['name'];
  $names= preg_replace('/\\s+/', '-', time().$name);
  $target_dir = "uploads/";
  $target_file = $target_dir . preg_replace('/\\s+/', '-', time().$name);

  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

  // Check extension
  if( in_array($imageFileType,$extensions_arr) ){
    // Upload file
    if(move_uploaded_file($_FILES['fileUpload']['tmp_name'],$target_file)){
		chmod($target_file , 0777); 
       // Convert to base64 
       $image_base64 = base64_encode(file_get_contents($_FILES['fileUpload']['tmp_name']) );
       $image = addslashes(file_get_contents($image));
       // Insert record
        mysql_select_db($database_hos, $hos);
        $query_ipt = "select i.an,i.hn,concat(p.pname,p.fname,' ',p.lname) as patient_name,a.bedno,i.hn,w.name as ward_name from ipt i left outer join patient p on p.hn=i.hn left outer join iptadm a on a.an=i.an left outer join ward w on w.ward=i.ward where i.an='".$an."'";
        $ipt = mysql_query($query_ipt, $hos) or die(mysql_error());
        $row_ipt = mysql_fetch_assoc($ipt);
        $totalRows_ipt = mysql_num_rows($ipt);
        
        $ptname=$row_ipt['patient_name'];
		$hn=$row_ipt['hn'];
		$bedno=$row_ipt['bedno'];
		$ward=$row_ipt['ward_name'];
        
        mysql_free_result($ipt);
        
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into ".$database_kohrx.".kohrx_ipd_order_image (image_name,an,order_date,order_time,capture_date,remark,checked,pt_type) value ('".$names."','".$an."','".$order_date."','".$order_time."',NOW(),".$remark.",'N','".$_POST['pt_type']."')";
		$db_insert = mysql_query($query_insert, $hos) or die(mysql_error());

        if($db_insert){ echo 1;
            //Message
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
				
                $mymessage = "มีการส่งภาพผู้ป่วย :\n"; //Set new line with '\n'
                $mymessage .= "=== ".$pttype_text." ===\n"; //Set new line with '\n'
                $mymessage .= "ชื่อ: ".$ptname."\n";
                $mymessage .= "HN: ".$hn."\n";
                $mymessage .= "AN: ".$an."\n";
                $mymessage .= "ตึก: ".$ward."\n";
                $mymessage .= "เตียง: ".$bedno."\n";
                $mymessage .= "วันที่สั่ง :".dateThai($order_date)." \n";
                $mymessage .= "ภาพ : http://159.192.104.60/organization/pharmacy/service/dispensingx/ipd_profile_image_preview.php?img=".$names;
                //$imageFile = new CURLFile('/ipd/uploads/'.$file, $file); // Local Image file Path
                //$imageFile=new CurlFile('/ipd/uploads/'.$file, 'image/jpg', $file);				   

            define('LINE_API', "https://notify-api.line.me/api/notify");
            $token = "9yxVCY7uDvvDZQ4Kd7a4pH3w7c5c9nyG1mzQcvWQGeb"; //ใส่Token ที่copy เอาไว้
            $params = array(
              //"stickerPkg"     => 2, //stickerPackageId
              //"stickerId"      => 34, //stickerId
              "message"        => $mymessage, //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
              //"imageThumbnail" => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", // max size 240x240px JPEG
              //"imageFullsize"  => "http://10.0.1.17:89/ipd/uploads/1623086294.jpg", //max size 1024x1024px JPEG
            );
            $res = notify_message($params, $token);
            // print_r($res);


        }        
    }
    
  }    
}
?>
