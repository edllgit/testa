<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
ini_set('display_errors', '1');
session_start();
require_once("../sec_connect.inc.php");

$ip		      = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level 		  = 'Lab admin Mobile Version';
$datetime	  = date("Y-m-d G i:s");
$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR']; 
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le browser Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	
$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser)
 VALUES ('$_POST[inputusername]', '$_POST[inputpassword]', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";
$resultInsert=mysqli_query($con,$queryInsert)		or die ("Could not insert" . mysqli_error($con));
$query     = "SELECT username, password, lab_primary_key, id FROM access_mobile WHERE username = '$_POST[inputusername]' AND password = '$_POST[inputpassword]'";
$result    = mysqli_query($con,$query)		or die ("Could not find user");
$usercount = mysqli_num_rows($result);

if ($usercount == 0){ //Aucun Résultat = username ou password invalides
	$Redirection = 'no';
}else{
	/* user id and password are valid and a match -- fetch user data  */
	$labAdminData = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$compUser     = strcmp($_POST[inputusername], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW	      = strcmp($_POST[inputpassword], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		$Redirection = 'no';
	}
}

if ($Redirection <> 'no'){
	$_SESSION[idMobile] =$_POST[inputusername];
	session_write_close();
	header("Location: menu_mobile.php");/* go to admin home page */
}else{
	header("Location: index.php?err=y");/* go to admin home page */	
}
?>

<?php  include("inc/footer.inc.php"); ?>
</body>
</html>