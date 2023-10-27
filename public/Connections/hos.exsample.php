<?php 
include('con_add_on.php');
?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_hos = "127.0.0.1";
$database_hos = "hos";
$username_hos = "root";
$password_hos = "root";
$hos = mysql_connect($hostname_hos, $username_hos, $password_hos) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES utf8");
?>