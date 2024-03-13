<?php

/*
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);


include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$query="select auto_connect_username, auto_connect_pwd from labs where primary_key = $_REQUEST[lab_id]";
$labResult=mysql_query($query)	or die ("Could not find lab");
$labData=mysql_fetch_array($labResult);

if (($labData['auto_connect_username'] <> '') && ($labData['auto_connect_pwd'] <> '')  )
{
//Connexion automatique avec le lab choisit 
$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level = 'Lab admin/Auto Access by main admin';
$datetime = date("Y-m-d G i:s");

	$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, level) VALUES ('$labData[auto_connect_username]', '$labData[auto_connect_pwd]', '$datetime', '$ip', '$level')";
	$resultInsert=mysql_query($queryInsert)		or die ("Could not insert" . mysql_error());
	
	$queryLab="select username, password, lab_primary_key, id from access where username = '$labData[auto_connect_username]' and password = '$labData[auto_connect_pwd]'";
	echo $queryLab;
	$resultLab=mysql_query($queryLab)		or die ("Could not find user");
	$usercount=mysql_num_rows($resultLab);
	echo 'count: '.$usercount;
	if ($usercount == 0){ // user id and/or password are not valid 
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{//user id and password are valid and a match -- fetch user data  
	$labAdminData=mysql_fetch_array($resultLab);
	$id = $labAdminData[id];
	$mainLab= $labAdminData[lab_primary_key];
	echo 'ID:'. $id;
	$query="select * from labs where primary_key = ". $_REQUEST[lab_id] ;
	echo '<br>'. $query;
	$result=mysql_query($query)		or die ("Could not find lab account");
	$_SESSION["labAdminData"]=mysql_fetch_array($result);
	$_SESSION["accessid"]=$id;
	$_SESSION["lab_pkey"]=$_SESSION["labAdminData"]["primary_key"];
	session_write_close();
	//exit();
	header("Location: http://c.direct-lens.com/labAdmin/adminHome.php");// go to admin home page 
	exit();
	}
//Fin de la connexion automatique
}else{
exit();
}

exit();
*/
?>