<?php
require_once(__DIR__.'/constants/mysql.constant.php');

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_directlens = constant("MYSQL_HOST");
$database_directlens = constant("MYSQL_DB_DIRECT_LENS");
$username_directlens = constant("MYSQL_USER");
$password_directlens = constant("MYSQL_PASSWORD");
//$directlens = mysql_pconnect($hostname_directlens, $username_directlens, $password_directlens) or trigger_error(mysql_error(),E_USER_ERROR); 
//Test pour changer de la fonction mysql_pconnect a mysql_connect le 22 aout 2017
$directlens = mysql_connect($hostname_directlens, $username_directlens, $password_directlens) or trigger_error(mysql_error(),E_USER_ERROR); 
?>