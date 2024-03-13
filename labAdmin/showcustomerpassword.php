<?php
session_start();
include("../Connections/sec_connect.inc.php");

	$idClient = $_REQUEST['user_id'];
	
	$datecomplete=Date('Y-m-d h:i:s', strtotime("+3 hours"));
	$query="insert into password_history (dateandtime, user_id, who) 	values 	('$datecomplete', '$idClient','-')";
	$result=mysql_query($query)		or die ("Could not create ticket: " . mysql_error());
	
	$queryPwd="SELECT password from accounts where user_id = '$idClient'";
	$resultPwd=mysql_query($queryPwd)		or die ("Error while searching password:  " . mysql_error());
	$DataPwd=mysql_fetch_array($resultPwd);
	
	$lepassword = $DataPwd['password'];
	echo 'This customer password is : '. $lepassword;
?>