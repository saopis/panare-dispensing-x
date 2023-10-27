<?php 
include('con_add_on.php');
?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_hos = "172.16.0.2";
$database_hos = "hos";
$username_hos = "sa";
$password_hos = "sa";
$hos = mysql_connect($hostname_hos, $username_hos, $password_hos) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES utf8");
?>