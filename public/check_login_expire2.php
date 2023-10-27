<?php ob_start();?>
<?php session_start();?>
<?php 
if($_SESSION['doctorcode']==""&&$_GET['page']=="main"){
		echo "<script>loadModal();</script>";
}
if($_SESSION['doctorcode']==""&&$_GET['page']=="profile"){
		echo "<script>parent.$.fn.colorbox.close();</script>";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>