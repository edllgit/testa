<?php
session_start();





require_once("../../Connections/sec_connect.inc.php");

	$query="select username, password, id from access_admin where username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysql_query($query)		or die ("Could not find user");
	$usercount=mysql_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	
	$adminData=mysql_fetch_array($result);
	$_SESSION[adminData][username] = $adminData['username'];
	$compUser=strcmp($_POST[username_test], $adminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $adminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
$id = $adminData[id];
//$mainLab= $labAdminData[lab_primary_key];
$_SESSION["access_admin_id"]=$id;
	
header("Location:../report.php");
exit();
?>
