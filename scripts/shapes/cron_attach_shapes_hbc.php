<?php
ini_set('display_errors',1); 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 

require_once(__DIR__.'/../../constants/ftp.constant.php');

//CONNEXION Database HBC
include("../../connexion_hbc.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

//LOGGER LE DÉBUT DE CE SCRIPT
$heure       	  = date("H:i:s"); // 17:16:18 // On doit soustraire 4 heure à cause du changement de fuseau horaire
$todayDate   	  = date("Y-m-d g:i:s");// current date
$currentTime 	  = time($todayDate); //Change date into time
$timeAfterOneHour = $currentTime-((60*60)*4);//Add one hour equavelent seconds 60*60	
$heure     		  = date("H:i:s",$timeAfterOneHour);
$dateShort        = date("Y-m-d",$timeAfterOneHour);
$fulldate         = $dateShort . ' ' . $heure;

//0-Sortir les commandes Fabriqués par Swiss, dont le status est parmis ('processing') et dont la valeur du shape_copied_swiss_ftp est '0000-00-00 00:00:00'
/*$querySwiss =  "SELECT * FROM orders 
				WHERE 	prescript_lab IN (10,73,2) 			
				AND   	order_status IN ('processing')
				AND     shape_name_bk<>''
				AND 	(shape_copied_swiss_ftp = '0000-00-00 00:00:00' OR shape_copied_ftp = '0000-00-00 00:00:00')";	*/
				
				$querySwiss =  "SELECT * FROM orders 
				WHERE 	prescript_lab IN (10,2,73) 			
				AND   	order_status IN ('processing')
				AND     shape_name_bk<>''
				AND 	shape_copied_ftp = '0000-00-00 00:00:00'";
				
		
echo '<br>'.$querySwiss . '<br>';

$resultSwiss = mysqli_query($con,$querySwiss)	or die ("Could not select items 4b". mysqli_error($con));


while ($DataSwiss   = mysqli_fetch_array($resultSwiss,MYSQLI_ASSOC)){
$Prescription_Lab   = $DataSwiss[prescript_lab];
/*//Générer l'identifiant Unique
$random_number     = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9). rand(0,9). rand(0,9) . rand(0,9). rand(0,9) . rand(0,9) . rand(0,9)  . rand(0,9) ); // random(ish) 5 digit int
$IdentifiantUnique = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)). chr(rand(65,90)). chr(rand(65,90))  . chr(rand(65,90))  . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)); // random(ish) 5 character string
$todayDateexact   	  = date("Y-m-d_g-i-s");// current date
$IdentifiantUnique =$IdentifiantUnique .'_' . $todayDateexact ;*/

/*
[X]	0-Passer les commandes  fabriqués par SWISS et KNR pour lesquels aucune shape n'a été envoyé a Swiss une par une   
[X]	1-Aller trouver la forme OMA qui correspond parmis la bibliothèque sur Serveur Windows VM dans C:\ftp_root\Banque de traces
[X]	2-La copier 
[X]	3-La renommer avec # commande, 
[X]	4-L'envoyer sur le ftp de Swiss/KNR et 
[X]	5-Flagger cette action dans shape_copied_swiss_ftp.*/
	echo '<br>----------------------------------------------------------------------------
	<br>HBC Order <b>#'. $DataSwiss[order_num] . '</b><br> Shape: <b>'. $DataSwiss[shape_name_bk] . '</b>';
	$NomdeLaFormeAtrouver = './' . $DataSwiss[shape_name_bk];
	$NumeroCommandeHBC 	  = $DataSwiss[order_num];
	
	//1-Aller trouver la forme OMA qui correspond parmis la bibliothèque sur Serveur Windows VM dans C:\ftp_root\Banque de traces
	//Connexion au Ftp sur Windows VM qui donne accès à la banque de traces OMA (qui est, elle-même, synchronisé avec le Broker EDLL)
	$ftp_server = constant("FTP_WINDOWS_VM");
	$ftp_user   = constant("FTP_USER_SYNC_SHAPES");
	$ftp_pass   = constant("FTP_PASSWORD_SYNC_SHAPES");
	$conn_id    = ftp_connect($ftp_server) or die("Couldn't connect to S3: $ftp_server"); 
	//Login
	if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		//echo '<br><b>Connexion au ftp  Windows VM (Instance Windows AWS) Reussie</b>';
	}else{
		echo '<br><b> !!! Erreur durant la tentative de connexion avec ftp Windows VM !!!</b>';	
	}
	//Enable PASV ( Note: must be done after ftp_login() 
	ftp_pasv($conn_id, true);	
	//Dès que  la connexion est établie, nous sommes dans le 'Home Directory' qui contient les +4000 formes (du format OMA)	
	$directory=ftp_pwd($conn_id);
	
	//Insérer les fichiers au format OMA dans un Array
	$filteredOMAFiles = ftp_nlist( $conn_id, ".");
	$filteredOMAFiles = preg_grep( '/\.oma$/i', $filteredOMAFiles );
	
	//2-3: Chercher la forme dans l'array
	//echo 'Je cherche ceci dans l\'array: '. $NomdeLaFormeAtrouver.'<br>';
	$PositionFormedansArray = array_search($NomdeLaFormeAtrouver, $filteredOMAFiles);
	
	//Si un résultat a été trouvé: on poursuit
	if ($PositionFormedansArray!=false){
		echo '<br>Position du fichier dans l\'Array:<b>' . $PositionFormedansArray.'</b><br>';
		$local_file = "../../../../../../ftp_root/Banque de traces/En cours de copie/$NumeroCommandeHBC.OMA";
		$server_file = $filteredOMAFiles[$PositionFormedansArray];
		$server_file = str_replace('./','',$server_file);
		//echo '<b>Server file</b>: ' . $server_file ;
		//echo '<br> <b>Local file</b>: ' . $local_file ;
		//Download server file
		if (ftp_get($conn_id, $local_file, $server_file, FTP_ASCII)){
			echo "<b>Successfully</b> written $local_file.";
		}else{
			echo "<b>Error</b> downloading $server_file.";
		}		
		//close connection
		ftp_close($conn_id);
		
	if ($Prescription_Lab == 10){//Si Swiss fabrique les verres 	
	//4-Copier ce fichier renommé sur le ftp de Swisscoat
	echo "<br><b>Copying File to Swisscoat's Ftp...</b>";
	$remote_file  = $NumeroCommandeHBC . '.OMA';
	$conn_idSwiss = ftp_connect(constant('SWISSCOAT_FTP'));
	// login with username and password
	$login_result = ftp_login($conn_idSwiss, constant("FTP_USER_0D013"), constant("FTP_PASSWORD_0D013"));

	ftp_pasv($conn_idSwiss,true);//Activate Passive mode
	ftp_chdir($conn_idSwiss,"FROM_GG");
	$directorySwiss=ftp_pwd($conn_idSwiss);
	//echo "Dossier actuel : ".$directorySwiss;
	ftp_chdir($conn_idSwiss,"shapes");
	$directorySwiss=ftp_pwd($conn_idSwiss);
	//echo "<br>Dossier actuel : ".$directorySwiss;
	//echo '<br><b>Remote file</b>: ' . $remote_file ;
	//echo '<br><b>Local file</b>: ' . $local_file ;
	$currentTime 	  = time($todayDate); //Change date into time
	if (ftp_put($conn_idSwiss, $remote_file, $local_file,  FTP_BINARY)) {
		echo "<br><b>Successfully</b> uploaded $remote_file\n";
		//Flagger la shape comme copiée sur le ftp de SWISS dans la DB
		//avec les champs 
		//shape_copied_swiss_ftp et result_copy_ftp_swiss
		$queryUpdateFlag =  "UPDATE orders
				SET 		shape_copied_swiss_ftp = '$todayDate',
							result_copy_ftp_swiss  = 'successful',
							shape_copied_ftp = '$todayDate',
							result_copy_ftp    = 'successful',
							shape_sent_to_who  = 'swiss'
							
							
				WHERE 	order_num = $NumeroCommandeHBC";	
				echo '<br>'.$queryUpdateFlag.'<br>';
		$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4a". mysqli_error($con));
	}else{
		echo "<br><b>There was a problem</b> while uploading $local_file\n";
		print_r(error_get_last());
	}
	ftp_close($conn_idSwiss);
	//exit();
	}//FIN DE SWISS
	
	
	
	
	if ($Prescription_Lab == 73){//Si KNR fabrique les verres 	
	//4-Copier ce fichier renommé sur le ftp de KNR
	echo "<br><b>Copying File to KNR's Ftp...</b>";
	$remote_file  = $NumeroCommandeHBC . '.OMA';
	$conn_idKNR = ftp_connect(constant("FTP_WINDOWS_VM"));
	// login with username and password
	$login_result = ftp_login($conn_idKNR, constant("FTP_USER_KANDR"), constant("FTP_PASSWORD_KANDR"));

	ftp_pasv($conn_idKNR,true);//Activate Passive mode
	ftp_chdir($conn_idKNR,"FROM DIRECT-LENS");
	$directoryKNR=ftp_pwd($conn_idKNR);
	//echo "Dossier actuel : ".$directoryKNR;
	ftp_chdir($conn_idKNR,"shapes");
	$directoryKNR=ftp_pwd($conn_idKNR);
	//echo "<br>Dossier actuel : ".$directoryKNR;
	//echo '<br><b>Remote file</b>: ' . $remote_file ;
	//echo '<br><b>Local file</b>: ' . $local_file ;
	$currentTime 	  = time($todayDate); //Change date into time
	if (ftp_put($conn_idKNR, $remote_file, $local_file,  FTP_BINARY)) {
		echo "<br><b>Successfully</b> uploaded $remote_file\n";
		//Flagger la shape comme copiée sur le ftp de SWISS dans la DB
		//avec les champs 
		//shape_copied_swiss_ftp et result_copy_ftp_swiss
		$queryUpdateFlag =  "UPDATE orders
				SET 		shape_copied_swiss_ftp = '$todayDate',
							result_copy_ftp_swiss  = 'successful',
							shape_copied_ftp = '$todayDate',
							result_copy_ftp    = 'successful',
							shape_sent_to_who  = 'knr'
				WHERE 	order_num = $NumeroCommandeHBC";	
				echo '<br>'.$queryUpdateFlag.'<br>';
		$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4c". mysqli_error($con));
	}else{
		echo "<br><b>There was a problem</b> while uploading $local_file\n";
		print_r(error_get_last());
	}
	ftp_close($conn_idKNR);
	//exit();
	}//END IF KNR
	
	
	
	if ($Prescription_Lab == 2){//Si HKO fabrique les verres 
		//4-Copier ce fichier renommé sur le ftp d'HKO
		echo "<br><b>Copying File to HKO's Ftp...</b>";
		$remote_file  = $NumeroCommandeHBC . '.OMA';
		$conn_idHKO = ftp_connect(constant('FTP_WINDOWS_VM'));
		// login with username and password
		$login_result = ftp_login($conn_idHKO, constant('FTP_USER_HKO'), constant('FTP_PASSWORD_HKO'));
		ftp_pasv($conn_idHKO,true);//Activate Passive mode
		ftp_chdir($conn_idHKO,"hko");
		$directorySwiss=ftp_pwd($conn_idHKO);
		ftp_chdir($conn_idHKO,"FROM DIRECT-LENS");
		$directorySwiss=ftp_pwd($conn_idHKO);
		//echo "Dossier actuel : ".$directorySwiss;
		ftp_chdir($conn_idHKO,"shapes");
		$directorySwiss=ftp_pwd($conn_idHKO);
		echo "<br>Dossier actuel : ".$directorySwiss;
		echo '<br><b>Remote file</b>: ' . $remote_file ;
		echo '<br><b>Local file</b>: ' . $local_file ;
		$currentTime 	  = time($todayDate); //Change date into time
		if (ftp_put($conn_idHKO, $remote_file, $local_file,  FTP_BINARY)) {
			echo "<br><b>Successfully</b> uploaded $remote_file\n";
			//Flagger la shape comme copiée sur le ftp d'HKO dans la DB
			//avec les champs 
			//shape_copied_swiss_ftp et result_copy_ftp_swiss
			$queryUpdateFlag =  "UPDATE orders
					SET 		shape_copied_swiss_ftp = '$todayDate',
							result_copy_ftp_swiss  = 'successful',
							shape_copied_ftp = '$todayDate',
							result_copy_ftp    = 'successful',
							shape_sent_to_who  = 'hko'
					WHERE 	order_num = $NumeroCommandeHBC";	
					echo '<br>'.$queryUpdateFlag.'<br>';
			$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4d". mysqli_error($con));
		}else{
			echo "<br><b>There was a problem</b> while uploading $local_file\n";
			print_r(error_get_last());
		}
		
		ftp_close($conn_idHKO);
		//exit();
	}//End if Prescription_Lab = 2 (HKO)
	
	
		//1-Flagger la commande 'shape not found' et noter la date précise (incluant l'heure) de recherche dans la bibliotheque de cette forme
		//2-Stopper le traitement
	//	echo '<br>Forme introuvable dans la bibliothèque..<br>1-Je dois Flagger la commande comme \'forme introuvable\' et <br>2-Stoppertop le traitement<br>';		
		$queryUpdateFlag =  "UPDATE orders
				SET 		shape_copied_swiss_ftp = '$todayDate',
							result_copy_ftp_swiss  = 'shape not found'
				WHERE 	order_num = $NumeroCommandeHBC";	
		//echo '<br>'.$queryUpdateFlag.'<br>';
		//$resultUpdateFlag = mysqli_query($con,$queryUpdateFlag)	or die ("Could not select items 4e". mysqli_error($con));
	}
	//exit();
	
	}//End While
?>