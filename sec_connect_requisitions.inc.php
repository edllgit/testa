<?php
require_once(__DIR__.'/constants/mysql.constant.php');

global $con;

$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER_REQUISITIONS");
$mysql_password = constant("MYSQL_PASSWORD_REQUISITIONS");
$mysql_db		= constant("MYSQL_DB_REQUISITIONS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
	
$usa_key = "m4a1x0vCi0eV77mpiAzfd53JSCyOD9Yt";

if (!function_exists('money_format')){
	function money_format($myspec, $myamount){
	$myreturn = $lbl_moneysym.number_format($myamount, 2, '.', '');
	// 1234.57
	// '%.2n',$acctTotal
	return $myreturn;
	}
}

?>