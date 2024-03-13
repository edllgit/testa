<?php
include("../Connections/sec_connect.inc.php");
session_start();

$employee_password=$_REQUEST['employee_password'];
$data=mysql_query("SELECT name FROM access_redo where password ='$employee_password'");
if(mysql_num_rows($data)>0){
$DataAccessRedo = mysql_fetch_array($data);	
$Employee = $DataAccessRedo[name];
}


if(mysql_num_rows($data)>0)
{
	echo "<span style=\"color:green;\">Merci $Employee</span>";
	$_SESSION['PrescrData']["authorized_by"] = $Employee;
}else{
	echo "<span style=\"color:red;\">Le mot de passe que vous avez saisi est incorrect</span>";
	$_SESSION['PrescrData']["authorized_by"] = '';
}
?>
