<?php
//include_once('config.php');
$hostname_hos = "10.0.1.250";
$database_hos = "hos";
$username_hos = "mhhos";
$password_hos = "mhhos10967";
$hos = mysql_connect($hostname_hos, $username_hos, $password_hos) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES utf8");


$file			=	$_FILES['file']['name'];
$file_image		=	'';
if($_FILES['file']['name']!=""){
    extract($_REQUEST);
	$infoExt        =   getimagesize($_FILES['file']['tmp_name']);
	if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
		$file	=	preg_replace('/\\s+/', '-', time().$file);
		$path   =   '../uploads/'.$file;
		move_uploaded_file($_FILES['file']['tmp_name'],$path);
		$data   =   array(
			'img_name'=>$file,
			'img_order'=>1
		);
		//$insert     =   $db->insert('dispensing.kohrx_images_upload',$data);
		//if($insert){ echo 1; } else { echo 0; }
		mysql_select_db($database_hos, $hos);
		$query_insert = "insert into dispensing.kohrx_images_upload (img_name,created) value ('".$file."',NOW())";
		//echo $query_insert;
		//exit();
		$db_insert = mysql_query($query_insert, $hos) or die(mysql_error());
	
		if($db_insert){ echo 1;} else { echo 0;}
		
	}else{
		echo 2;
	}
}
?>
