<?php
include "../sec_connectEDLL.inc.php";
session_start();

$redo_password=$_REQUEST['redo_password'];
$data=mysqli_query($con,"SELECT name FROM access_redo where password ='$redo_password'");
if(mysqli_num_rows($data)>0){
$DataAccessRedo = mysqli_fetch_array($data,MYSQLI_ASSOC);	
$Employee = $DataAccessRedo[name];
}

if(mysqli_num_rows($data)>0)
{
	echo "<span style=\"color:green;\">Merci $Employee</span>";
	$_SESSION['PrescrData']["authorized_by"] = $Employee;
}else{
	echo "<span style=\"color:red;\">Le mot de passe que vous avez saisi est incorrect</span>";
	$_SESSION['PrescrData']["authorized_by"] = '';
}
?>
