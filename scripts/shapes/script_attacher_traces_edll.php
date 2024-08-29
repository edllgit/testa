<?php
ini_set('display_errors',1); 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 

require_once(__DIR__.'/../../constants/ftp.constant.php');

//Connexion Database EDLL
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

echo exec('whoami');

$heure       	  = date("H:i:s"); // 17:16:18 // On doit soustraire 4 heure à cause du changement de fuseau horaire
$todayDate   	  = date("Y-m-d G:i:s");// current date
$currentTime 	  = time($todayDate); //Change date into time
$timeAfterOneHour = $currentTime-((60*60)*4);//Add one hour equavelent seconds 60*60	
$heure     		  = date("H:i:s",$timeAfterOneHour);
$dateShort        = date("Y-m-d",$timeAfterOneHour);
$fulldate         = $dateShort . ' ' . $heure;

//0-Sortir les commandes Fabriqués par Swiss, HKO, GKB dont le status est parmis ('processing') et dont la valeur du shape_copied_swiss_ftp est '0000-00-00 00:00:00'
/*$queryShape =  "SELECT * FROM orders 
				WHERE 	prescript_lab IN (10,25,69,73) 			
				AND   	order_status IN ('processing')
				AND 	shape_copied_ftp = '0000-00-00 00:00:00'
				AND     shape_name_bk<>''";*/

//SANS GKB PUISQUE LEUR FTP EST INNACESSIBLE PRÉSENTEMENT
$queryShape =  "SELECT * FROM orders 
				WHERE 	prescript_lab IN (10,25,73,69,76) 			
				AND   	order_status IN ('processing')
				AND 	shape_copied_ftp = '0000-00-00 00:00:00'
				AND     shape_name_bk<>''";
//TODO: Ajouter les autres fournisseurs qui peuvent recevoir des shapes éventuellement (Quand la partie Swiss fonctionnera #1)				
echo '<br>'. $queryShape. '<br>';				
echo "Oui1"; 
	
$resultShape = mysqli_query($con,$queryShape)	or die ("Could not select items 4". mysql_error($con));

while ($DataShape   = mysqli_fetch_array($resultShape,MYSQLI_ASSOC)){
$Prescription_Lab   = $DataShape[prescript_lab];
echo "Oui2"; 


/*
[x]	0-Passer les commandes  fabriqués par SWISS pour lesquels aucune shape n'a été envoyé a Swiss une par une   
[ ]	1-Aller trouver la forme OMA qui correspond parmis la bibliothèque sur Serveur Windows VM dans C:\ftp_root\Banque de traces
[ ]	2-La copier 
[ ]	3-La renommer avec # commande, 
[ ]	4-L'envoyer sur le ftp de Swiss et 
[ ]	5-Flagger cette action dans shape_copied_swiss_ftp.*/


	echo '<br>----------------------------------------------------------------------------
	<br>EDLL Order <b>#'. $DataShape[order_num] . '</b><br> Shape: <b>'. $DataShape[shape_name_bk] . '</b>';
	
	// Chaîne complète
	$shape = $DataShape['shape_name_bk'];
	
/*
// Vérifier si la variable contient le mot 'Shapes/'
if (strpos($shape, 'Shapes/') !== false) {
    echo "Oui"; // Afficher 'Oui' si le mot 'Shapes/' est présent
	// Trouver la position du premier espace après "Shapes/"
	$pos = strpos($shape, 'Shapes/') + strlen('Shapes/');
	
	// Extraire la sous-chaîne à partir de cette position
	$specific_part = substr($shape, $pos);
	
	echo "<br>Shape1: " . $specific_part;
	
	$NomdeLaFormeAtrouver = './' .$specific_part; echo '<br>nom'.$NomdeLaFormeAtrouver;
} else {
    echo "Non"; // Afficher 'Non' si le mot 'Shapes/' n'est pas présent
	$NomdeLaFormeAtrouver = './' .$shape; echo '<br>nom'.$NomdeLaFormeAtrouver;
}*/
	
	
	
	//===============================

	
	
	$NumeroCommandeEDLL   = $DataShape[order_num];
	
	//1-Aller trouver la forme OMA qui correspond parmis la bibliothèque sur Serveur Windows VM dans C:\ftp_root\Banque de traces
	//Connexion au Ftp sur Windows VM qui donne accès à la banque de traces OMA (qui est, elle-même, synchronisé avec le Broker EDLL)
	$ftp_server = constant("FTP_WINDOWS_VM");
	$ftp_user   = constant("FTP_USER_SYNC_SHAPES");
	$ftp_pass   = constant("FTP_PASSWORD_SYNC_SHAPES");
	$conn_id    = ftp_connect($ftp_server) or die("Couldn't connect to S3: $ftp_server"); 
	//Login
	if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		echo '<br><b>Connexion au ftp  Windows VM (Instance Windows AWS) Reussie</b>';
	}else{
		echo '<br><b> !!! Erreur durant la tentative de connexion avec ftp Windows VM !!!</b>';	
	}
	//Enable PASV ( Note: must be done after ftp_login() 
	ftp_pasv($conn_id, true);	
	ftp_chdir($conn_id, '/ftp_root/Banque de traces');

	//Dès que  la connexion est établie, nous sommes dans le 'Home Directory' qui contient les +4000 formes (du format OMA)	
	$directory=ftp_pwd($conn_id);//echo "Répertoire courant : $directory";
	
	//Insérer les fichiers au format OMA dans un Array
	$filteredOMAFiles = ftp_nlist( $conn_id, ".");  //echo '<br>nomfiltre' . print_r($filteredOMAFiles, true);
	$filteredOMAFiles = preg_grep( '/\.oma$/i', $filteredOMAFiles );  echo '<br>nomfiltre2' . print_r($filteredOMAFiles, true);
	
	//2-3: Chercher la forme dans l'array
	echo '<br>Je cherche ceci dans l\'array: '. $NomdeLaFormeAtrouver.'<br>';
	$decodedPath = urldecode($NomdeLaFormeAtrouver);
	echo '<br>code'.$decodedPath.'<br>';
	$PositionFormedansArray = array_search($decodedPath, $filteredOMAFiles);
	//$PositionFormedansArray = array_search($NomdeLaFormeAtrouver, $filteredOMAFiles);
	echo '<br>position'.var_dump($PositionFormedansArray);
	//echo '<br>Position dans l\'Array:'. $PositionFormedansArray ;

	//Si un résultat a été trouvé: on poursuit
	if ($PositionFormedansArray!==false){
		echo '<br>Position du fichier dans l\'Array:<b>' . $PositionFormedansArray.'</b><br>';
		$local_file = "../../../../../../ftp_root/Banque de traces/En cours de copie/$NumeroCommandeEDLL.OMA";
		$server_file = $filteredOMAFiles[$PositionFormedansArray];
		$server_file = str_replace('./','',$server_file);
		echo '<b>Server file</b>: ' . $server_file ;
		echo '<br> <b>Local file</b>: ' . $local_file ;
		//Download server file
		if (ftp_get($conn_id, $local_file, $server_file, FTP_ASCII)){
			echo "<br><b>Successfully</b> written $local_file.";
		}else{
			echo "<br><b>Error</b> downloading $server_file.";
		}		
		//close connection
		ftp_close($conn_id);
		
		
		
	if ($Prescription_Lab == 10){//Si Swiss fabrique les verres 
		//4-Copier ce fichier renommé sur le ftp de Swisscoat
		echo "<br><b>Copying File to Swisscoat's Ftp...</b>";
		$remote_file  = $NumeroCommandeEDLL . '.OMA';
		$conn_idSwiss = ftp_connect(constant('SWISSCOAT_FTP'));
		// login with username and password
		$login_result = ftp_login($conn_idSwiss, constant('FTP_USER_RCO'), constant('FTP_PASSWORD_RCO'));
		ftp_pasv($conn_idSwiss,true);//Activate Passive mode
		ftp_chdir($conn_idSwiss,"FROM_DL");
		$directorySwiss=ftp_pwd($conn_idSwiss);
		//echo "Dossier actuel : ".$directorySwiss;
		ftp_chdir($conn_idSwiss,"shapes");
		$directorySwiss=ftp_pwd($conn_idSwiss);
		echo "<br>Dossier actuel : ".$directorySwiss;
		echo '<br><b>Remote file</b>: ' . $remote_file ;
		echo '<br><b>Local file</b>: ' . $local_file ;
		$currentTime 	  = time($todayDate); //Change date into time
		

		
		$transfer_mode = FTP_BINARY;
		if (ftp_put($conn_idSwiss, $remote_file, $local_file, $transfer_mode)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			//Flagger la shape comme copiée sur le ftp de SWISS dans la DB
			//avec les champs 
			//shape_copied_swiss_ftp et result_copy_ftp_swiss
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_ftp = '$todayDate',
								result_copy_ftp    = 'successful',
								shape_sent_to_who  = 'swisscoat'
					WHERE 	order_num = $NumeroCommandeEDLL	";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}
		
		ftp_close($conn_idSwiss);
		//exit();
	}//End if Prescription_Lab = 10 (SWISS)
	
/*	if (ftp_login($conn_idHKO, $ftp_user, $ftp_pass)) {
    echo '<br><b>Connexion au ftp HKO réussie</b>';
} else {
    echo '<br><b> !!! Erreur durant la tentative de connexion avec ftp HKO !!!</b>';
}
*/
	
	if ($Prescription_Lab == 25){//Si HKO fabrique les verres 
		//4-Copier ce fichier renommé sur le ftp d'HKO
		echo "<br><b>Copying File to HKO's Ftp...</b>";
		$remote_file  = $NumeroCommandeEDLL . '.OMA';
		$conn_idHKO = ftp_connect(constant("FTP_WINDOWS_VM"));
		// login with username and password
		$login_result = ftp_login($conn_idHKO, constant("FTP_USER_HKO"), constant("FTP_PASSWORD_HKO"));

		ftp_pasv($conn_idHKO,true);//Activate Passive mode
		
		//ftp_chdir($conn_idHKO,"hko");
		//$directorySwiss=ftp_pwd($conn_idHKO);
		
		ftp_chdir($conn_idHKO,"ftp_root/Echange avec Fournisseurs/HKO/FROM DIRECT-LENS/shapes");
		$directorySwiss=ftp_pwd($conn_idHKO);
		//echo "Dossier actuel : ".$directorySwiss;
		//ftp_chdir($conn_idHKO,"shapes");
		$directorySwiss=ftp_pwd($conn_idHKO);
		echo "<br>Dossier actuel : ".$directorySwiss;
		echo '<br><b>Remote file</b>: ' . $remote_file;
		//$local_file
		//$local_file = realpath("../../../../../../ftp_root/Banque de traces/En cours de copie/1652079.OMA");

		echo '<br><b>Local file</b>: '  . $local_file;
		$currentTime 	  = time($todayDate); //Change date into time
	
		
		
	//$transfer_mode = FTP_BINARY;
		if (ftp_put($conn_idHKO, $remote_file, $local_file,  FTP_BINARY)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			//Flagger la shape comme copiée sur le ftp d'HKO dans la DB
			//avec les champs 
			//shape_copied_swiss_ftp et result_copy_ftp_swiss
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_ftp = '$todayDate',
								result_copy_ftp    = 'successful',
								shape_sent_to_who  = 'hko'
					WHERE 	order_num = $NumeroCommandeEDLL			
					AND 	shape_copied_ftp = '0000-00-00 00:00:00'";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}
		
		ftp_close($conn_idHKO);
		//exit();
	}//End if Prescription_Lab = 25 (HKO)  
	
	
	
	
	
	//PARTIE KNR (2022-01-18)
	if ($Prescription_Lab == 73){//Si KNR fabrique les verres 
		//4-Copier ce fichier renommé sur le ftp de KNR
		echo "<br><b>Copying File to KNR's Ftp...</b>";
		$remote_file  = $NumeroCommandeEDLL . '.OMA';
		$conn_idKNR = ftp_connect(constant("FTP_WINDOWS_VM"));
		// login with username and password
		$login_result = ftp_login($conn_idKNR, constant("FTP_USER_KANDR"), constant("FTP_PASSWORD_KANDR"));

		ftp_pasv($conn_idKNR,true);//Activate Passive mode
		
		ftp_chdir($conn_idKNR,"ftp_root/Echange avec Fournisseurs/K and R/FROM DIRECT-LENS/Order Jobs/Shapes");
		$directoryKNR=ftp_pwd($conn_idKNR);
		//echo "Dossier actuel : ".$directoryKNR;
		//ftp_chdir($conn_idKNR,"shapes");
		$directoryKNR=ftp_pwd($conn_idKNR);
		echo "<br>Dossier actuel : ".$directoryKNR;
		echo '<br><b>Remote file</b>: ' . $remote_file;
		echo '<br><b>Local file</b>: '  . $local_file;
		$currentTime 	  = time($todayDate); //Change date into time
		
				
		if (ftp_put($conn_idKNR, $remote_file, $local_file,  FTP_BINARY)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			//Flagger la shape comme copiée sur le ftp de knr dans la DB
			//avec les champs 
			//shape_copied_swiss_ftp et result_copy_ftp_swiss
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_ftp = '$todayDate',
								result_copy_ftp    = 'successful',
								shape_sent_to_who  = 'knr'
					WHERE 	order_num = $NumeroCommandeEDLL			
					AND 	shape_copied_ftp = '0000-00-00 00:00:00'
					AND     shape_name_bk<>''";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}  
		
		ftp_close($conn_idKNR);
		//exit();
	}//End if Prescription_Lab = 73 (KNR)
	
	
	
	
	if ($Prescription_Lab == 69){//Si GKB fabrique les verres 
		//4-Copier ce fichier renommé sur le ftp de GKB 
		echo "<br><b>Copying File to GKB's Ftp...</b>";
		$remote_file  = $NumeroCommandeEDLL . '.OMA';
		$conn_idGKB = ftp_connect(constant('GKB_FTP'));
		// login with username and password
		$login_result = ftp_login($conn_idGKB, constant('FTP_USER_DLN'), constant('FTP_PASSWORD_DLN'));
		ftp_pasv($conn_idGKB,true);//Activate Passive mode

		ftp_chdir($conn_idGKB,"Directlab");
		$directorySwiss=ftp_pwd($conn_idGKB);
		
		ftp_chdir($conn_idGKB,"Order");
		$directorySwiss=ftp_pwd($conn_idGKB);
		
		echo "<br>Dossier actuel : ".$directorySwiss;
		echo '<br><b>Remote file</b>: ' . $remote_file ;
		echo '<br><b>Local file</b>: ' . $local_file ;
		$currentTime 	  = time($todayDate); //Change date into time
		if (ftp_put($conn_idGKB, $remote_file, $local_file,  FTP_BINARY)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			
			//Flagger la shape comme copiée sur le ftp de GKB dans la DB
			//avec les champs shape_copied_ftp , result_copy_ftp et shape_sent_to_who
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_ftp = '$todayDate',
								result_copy_ftp    = 'successful',
								shape_sent_to_who  = 'gkb'
					WHERE 	order_num = $NumeroCommandeEDLL";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}
		
		ftp_close($conn_idGKB);
		//exit();
	}//End if Prescription_Lab = 69 (GKB)
	
	
	if ($Prescription_Lab == 76){//Si OVGLAB  FABRIQUE les verres 
		//4-Copier ce fichier renommé sur le ftp de OVG 
		echo "<br><b>Copying File to OVG_LAB's Ftp...</b>";
		$remote_file  = $NumeroCommandeEDLL . '.OMA';
		$conn_idOVG = ftp_connect(constant('OVG_LAB_FTP'));
		// login with username and password
		$login_result = ftp_login($conn_idOVG, constant('FTP_USER_OVG_LAB'), constant('FTP_PASSWORD_OVG_LAB'));
		ftp_pasv($conn_idOVG,true);//Activate Passive mode

		ftp_chdir($conn_idOVG,"Directlab");
		$directorySwiss=ftp_pwd($conn_idOVG);
		
		ftp_chdir($conn_idOVG,"Shapes");
		$directorySwiss=ftp_pwd($conn_idOVG);
		
		echo "<br>Dossier actuel : ".$directorySwiss;
		echo '<br><b>Remote file</b>: ' . $remote_file ;
		echo '<br><b>Local file</b>: ' . $local_file ;
		$currentTime 	  = time($todayDate); //Change date into time
		if (ftp_put($conn_idOVG, $remote_file, $local_file,  FTP_BINARY)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			
			//Flagger la shape comme copiée sur le ftp de OVG dans la DB
			//avec les champs shape_copied_ftp , result_copy_ftp et shape_sent_to_who
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_ftp = '$todayDate',
								result_copy_ftp    = 'successful',
								shape_sent_to_who  = 'OVG'
					WHERE 	order_num = $NumeroCommandeEDLL";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}
		
		ftp_close($conn_idOVG);
		//exit();
	}//End if Prescription_Lab = 76 (OVG)  
		
	
		
	}else{
		echo '<br>bloque ici';
		//exit();
		//1-Flagger la commande 'shape not found' et noter la date précise (incluant l'heure) de recherche dans la bibliotheque de cette forme
		//2-Stopper le traitement
		
		switch($Prescription_Lab){
			case 10: 	$Supplier ='swisscoat'; break;
			case 25: 	$Supplier ='hko'; 		break;	
			case 3: 	$Supplier ='stc'; 		break;	
			case 69: 	$Supplier ='gkb'; 		break;	
			case 73: 	$Supplier ='KNR'; 		break;	
			case 76: 	$Supplier ='ovg_lab'; 		break;	
			//Todo ajouter d'autres fournisseurs ? GKB ? 
		default: 	$Supplier ='unknown';
		}//End Switch
		
		echo '<br>Forme introuvable dans la bibliothèque..<br>1-Je dois Flagger la commande comme \'forme introuvable\' et <br>2-Stoppertop le traitement<br>';		
		$queryUpdateFlag =  "UPDATE orders
				SET 		shape_copied_ftp = '$todayDate',
							result_copy_ftp_swiss  = 'shape not found',
							shape_sent_to_who = '$Supplier'		
				WHERE 	order_num = $NumeroCommandeEDLL";	
		echo '<br>'.$queryUpdateFlag.'<br>';
		//$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4". mysql_error($con));
	}
	//exit();
	
	}//End While
?>