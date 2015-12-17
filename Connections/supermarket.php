<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_supermarket = "localhost";
$database_supermarket = "supermarket";
$username_supermarket = "root";
$password_supermarket = "";
$supermarket = mysql_pconnect($hostname_supermarket, $username_supermarket, $password_supermarket) or trigger_error(mysql_error(),E_USER_ERROR); 
?>