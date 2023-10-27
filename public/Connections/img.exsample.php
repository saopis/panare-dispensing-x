<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_img = "127.0.0.1";
$database_img = "hos";
$username_img = "root";
$password_img = "sa";
$img = mysql_connect($hostname_img, $username_img, $password_img) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES utf8");
?>