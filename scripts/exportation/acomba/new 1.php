<?php
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once(__DIR__.'/../../../constants/ftp.constant.php');
require_once(__DIR__.'/../../../constants/mysql.constant.php');

//Créer le fichier CCimport pour DirectLab Network
include("../../../sec_connectEDLL.inc.php");//RDL:LNC, Direct-Lens/Prestige sont tous dans la bd direct54_lens
$today=date("ymd") . '_' . date("Gi") ;

$datedebut = date("Y-m-d");//"2018-06-18";
$datefin   = date("Y-m-d");//"2018-06-18";
$FichierestVide = 'oui';//pour identifier si le fichier est vide ou non, et donc si on doit le copier sur le ftp

//DATE HARD CODÉ
//$datedebut ="2024-01-01";
//$datefin   ="2024-01-31";

echo '<br><br>FichierestVide:' . $FichierestVide . '<br>';
$LigneCommentaire = 'Du ' . $datedebut . ' au ' . $datefin;

echo $LigneCommentaire;
//CREATE EXPORT FILE//
//$filename="../acomba/FROM DIRECT-LENS/CCImport.C$today.001";//TODO: modifier pour dossier sur Windows VM
$filename="../../../../../../../ftp_root/acomba/CCImport.C$today.001"; //Le fichier sera créé dans un dossier ou  l'utilisateur FTP (ehandfield) à accès [en écriture]
$fp=fopen($filename, "w");
$outputstring  = $LigneCommentaire .  "\r\n" ;//Ligne 1
$outputstring .= 'LFACT=12' .  "\r\n" 	;// Ligne 2 pour laisser savoir que nos order num auront 12 caractères
fwrite($fp,$outputstring);

	echo '<br><br>Copier du fichier sur le ftp de godaddy.'; 	
	//Ajouts pour copier  le  fichier sur le  ftp de Godaddy 2013-02-21
	$ftp_server = constant("GODADDY_FTP");
	echo '<br> ftp_server'.var_dump($ftp_server);
	$ftp_user = constant("FTP_USER_AGIASSON");
	echo '<br> $ftp_user'.var_dump($ftp_user);
	$ftp_pass = constant("FTP_PASSWORD_AGIASSON");
	echo '<br> $ftp_pass'.var_dump($ftp_pass);


if ($FichierestVide =='oui')
{
	echo '<br><br>Copier du fichier sur le ftp de godaddy.'; 	
	//Ajouts pour copier  le  fichier sur le  ftp de Godaddy 2013-02-21
	$ftp_server = constant("GODADDY_FTP");
	echo '<br> ftp_server'.var_dump($ftp_server);
	$ftp_user = constant("FTP_USER_AGIASSON");
	echo '<br> $ftp_user'.var_dump($ftp_user);
	$ftp_pass = constant("FTP_PASSWORD_AGIASSON");
	echo '<br> $ftp_pass'.var_dump($ftp_pass);
	
	// set up a connection or die
	$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
	
	// try to login
	if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		echo "Connected as $ftp_user@$ftp_server\n";
	} else {
		echo "Couldn't connect as $ftp_user\n";
	}
	
	// turn passive mode on
	ftp_pasv($conn_id, true);
		
	//ftp_chdir($conn_id,"FROM_DL");
	
	$file=$filename;//"PrecisionOrderData-".$today.".csv";
	$remote_file = "CCImport.C$today.001";//"PrecisionOrderData-".$today.".csv";
	
	// upload a file
	if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
	 echo "successfully uploaded $file\n";
	} else {
	 echo "There was a problem while uploading $file\n";
	}
	
	ftp_close($conn_id);  
}else {
echo '<br><br>Fichier non copié sur le ftp de  godaddy puisqu\'il est vide.';
}//End if file is not empty

?>