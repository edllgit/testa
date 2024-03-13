<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
ini_set('display_errors', '1');
session_start();
require_once("../sec_connect_requisitions.inc.php");

echo '<html><head>
<meta charset=\"utf-8\">
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<!-- Bootstrap core CSS -->
<link href="http://www.direct-lens.com/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
<![endif]-->
</head><body>';

$ip		      = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level 		  = 'Requisitions Section Admin';
$datetime	  = date("Y-m-d G i:s");
$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR']; 
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le browser Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	
/*$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser)
 VALUES ('$_POST[inputusername]', '$_POST[inputpassword]', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";*/
//$resultInsert=mysqli_query($con,$queryInsert)		or die ("Could not insert" . mysqli_error($con));
$query     = "SELECT * FROM acces_requisitions WHERE acces_nom_utilisateur = '$_POST[inputusername]' AND acces_mot_de_passe = '$_POST[inputpassword]'";
//echo $query;
$result    = mysqli_query($con,$query)		or die ("Could not find user");
$usercount = mysqli_num_rows($result);

if ($usercount == 0){ //Aucun Résultat = username ou password invalides
	$Redirection = 'no';
}else{
	/* user id and password are valid and a match -- fetch user data  */
	$labAdminData = mysqli_fetch_array($result,MYSQLI_ASSOC);
	//$compUser     = strcmp($_POST[inputusername], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	//$compPW	      = strcmp($_POST[inputpassword], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	/*if ($compUser != 0 or $compPW != 0){
		$Redirection = 'no';
	}*/
}

if ($Redirection <> 'no'){
	$_SESSION[idMobile] =$_POST[inputusername];
	session_write_close();
	header("Location: gestion_requisitions_edll.php");/* go to admin home page */
}else{
	echo '<br><p align="center"><b>Erreur dans votre combinaison, veuillez ré-essayer</b>   <a href="index.php">Retour</a></p>';
	//header("Location: index.php?err=y");/* go to admin home page */	
}
?>

<?php  include("inc/footer.inc.php"); ?>
</body>
</html>