<?php require_once('Connections/hos.php'); ?>
<?php
    mysql_select_db($database_hos, $hos);
    $query_test = "insert into dispensing.test (test,d_update) value ('1',NOW()) ";
    $rs_test = mysql_query($query_test, $hos) or die(mysql_error());


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