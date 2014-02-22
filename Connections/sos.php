<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sos = "localhost";
$database_sos = "sos";
$username_sos = "root";
$password_sos = "";
$sos = mysql_pconnect($hostname_sos, $username_sos, $password_sos) or trigger_error(mysql_error(),E_USER_ERROR); 
?>