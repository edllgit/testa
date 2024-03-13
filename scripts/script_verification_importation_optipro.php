<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../connexion_hbc.inc.php");
include("../src/Twilio/autoload.php");
//include("admin_functions.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
session_start();

$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);



//1 Connexion sur le FTP EDLL 
$ftp_server_EDLL = constant("FTP_WINDOWS_VM");
$ftp_user_EDLL   = constant("FTP_USER_OPTIPRO_EDLL");

$ftp_pass_EDLL   = constant("FTP_PASSWORD_OPTIPRO_EDLL");
// set up a connection or die
$conn_id_EDLL = ftp_connect($ftp_server_EDLL) or die("Couldn't connect to $ftp_server_EDLL"); 
// try to login
if (@ftp_login($conn_id_EDLL, $ftp_user_EDLL, $ftp_pass_EDLL)) {
}else {
	echo 'Probleme de connexion';
}
ftp_pasv($conn_id_EDLL,true);
ftp_chdir($conn_id_EDLL,"Optipro EDLL");
$directory=ftp_pwd($conn_id_EDLL);
$contents=ftp_nlist($conn_id_EDLL, ".");
$Compteur_EDLL = 0;
foreach ($contents as $value) {//FIND NEWEST FILE
	$Compteur_EDLL += 1;
}
//On soustrait les 2 dossiers pour connaitre le nombre exact  de csv en attente d'importation
$Compteur_EDLL = $Compteur_EDLL -2;
//Fin de la partie EDLL



//Partie HBC
$ftp_server_HBC = constant("FTP_WINDOWS_VM");
$ftp_user_HBC   = constant("FTP_USER_OPTIPRO_HBC");

$ftp_pass_HBC   = constant("FTP_PASSWORD_OPTIPRO_HBC");

// set up a connection or die
$conn_id_HBC = ftp_connect($ftp_server_HBC) or die("Couldn't connect to $ftp_server_HBC"); 
// try to login
if (@ftp_login($conn_id_HBC, $ftp_user_HBC, $ftp_pass_HBC)) {
}else {
	echo 'Probleme de connexion';
}
ftp_pasv($conn_id_HBC,true);
ftp_chdir($conn_id_HBC,"Optipro");
$directory=ftp_pwd($conn_id_HBC);
$contents=ftp_nlist($conn_id_HBC, ".");
$Compteur_HBC = 0;
foreach ($contents as $value) {//FIND NEWEST FILE
	$Compteur_HBC += 1;
}
//On soustrait les 2 dossiers pour connaitre le nombre exact  de csv en attente d'importation
$Compteur_HBC = $Compteur_HBC -3;
//Fin de la partie HBC

//TEST CREDENTIALS
/*
$sid = "ACf057a886d6cbed8fcd6eeb076a0269c1"; // Your Account SID from www.twilio.com/console
$token = "ef4807135aac77f79cd41ee2233bdbd5"; // Your Auth Token from www.twilio.com/console
*/
//LIVE CREDENTIALS TWILIO
$sid = "ACf4ad6dfeeda0ed7460f46a9453ae9d5c"; // Your Account SID from www.twilio.com/console
$token = "5f21fc960d2784177555bd05a183316c"; // Your Auth Token from www.twilio.com/console


//Définir les niveaux de réaction
//A)Entre 0 et 44  fichiers:--->[ON N'ENVOIE PAS DE SMS]  $Couleur='Vert';   $Situation="Tout va bien";  
//B)Entre 45 et 65 fichiers:--->[UN SMS EST ENVOYÉ] 	  $Couleur='Jaune';  $Situation="Vérification nécesaire";  
//C)Entre 65 et 99 fichiers:--->[UN SMS EST ENVOYÉ]  	  $Couleur='Orange'; $Situation="Probleme détecté, intervention nécessaire";  
//D)100 fichiers et plus   :--->[UN SMS EST ENVOYÉ]   	  $Couleur='Rouge';  $Situation="Grave problème détecté, intervention rapide nécessaire";


//Calcul de la légende EDLL
if ($Compteur_EDLL <45)	{
	$Couleur_EDLL   = "Vert";
	$Situation_EDLL = "Tout va bien";
}elseif($Compteur_EDLL>44 && $Compteur_EDLL<66){
	$Couleur_EDLL   = "Jaune";
	$Situation_EDLL = "Vérification nécessaire, problème potentiel";
}elseif($Compteur_EDLL>65 && $Compteur_EDLL<100){
	$Couleur_EDLL   = "Orange";
	$Situation_EDLL = "Problème détecté, intervention nécessaire";
}elseif($Compteur_EDLL>99){
	$Couleur_EDLL   = "Rouge";
	$Situation_EDLL = "Grave problème détecté, intervention URGENTE nécessaire";
}//End IF


//Calcul de la légende HBC
if ($Compteur_HBC <45)	{
	$Couleur_HBC   = "Vert";
	$Situation_HBC = "Tout va bien";
}elseif($Compteur_HBC>44 && $Compteur_HBC<66){
	$Couleur_HBC   = "Jaune";
	$Situation_HBC = "Vérification nécessaire, problème potentiel";
}elseif($Compteur_HBC>65 && $Compteur_HBC<100){
	$Couleur_HBC   = "Orange";
	$Situation_HBC = "Problème détecté, intervention nécessaire";
}elseif($Compteur_HBC>99){
	$Couleur_HBC   = "Rouge";
	$Situation_HBC = "Grave problème détecté, intervention URGENTE nécessaire";
}//End IF



echo '<br><br><b>HBC: </b>'.$Couleur_HBC . ': '.$Compteur_HBC .' commande(s) en attente d\'importation. <b>'. $Situation_HBC . '</b>';
echo '<br><br><b>EDLL: </b>'.$Couleur_EDLL. ': '.$Compteur_EDLL .' commande(s) en attente d\'importation. <b>'. $Situation_EDLL . '</b>';







if ($Couleur_HBC<>'Vert'){
	$client = new Twilio\Rest\Client($sid, $token);
	$message_HBC = $client->messages->create(
	  '18193831723', // Text this number
	  array(
		'from' => '18198055552', // From a valid Twilio number
		'body' => 'Importation HBC: '.$Couleur_HBC . ' ' . $Situation_HBC.'. CSV en attente d\'importation: '.$Compteur_HBC
		)
	  );
	print $message_HBC->sid;
	echo '<br><br>SMS-->HBC:envoyé!';	
}//END IF


if ($Couleur_EDLL<>'Vert'){
	$client = new Twilio\Rest\Client($sid, $token);
	$message_EDLL = $client->messages->create(
	  '18193831723', // Text this number
	  array(
		'from' => '18198055552', // From a valid Twilio number
		'body' => 'Importation EDLL: '.$Couleur_EDLL . ' ' . $Situation_EDLL.'. CSV en attente d\'importation: '.$Compteur_EDLL
	  )
	);
	print $message_EDLL->sid;
	echo '<br><br>SMS-->EDLL:envoyé!';	
}//END IF


exit();
?>
	

  <p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>