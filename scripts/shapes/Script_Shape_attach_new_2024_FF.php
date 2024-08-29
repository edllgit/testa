<?php

ini_set('display_errors',1); 
error_reporting(E_ALL);
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 

require_once(__DIR__.'/../../constants/ftp.constant.php');

//Connexion Database EDLL
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');


$todayDate   	  = date("Y-m-d G:i:s");// current date
// Connexion à la base de données MySQL
$con = mysqli_connect("SRVWEB-Prod", "appuser", "p1a1nt3xtbad", "direct54_dirlens");

if (mysqli_connect_errno()) {
    echo "Échec de la connexion à MySQL: " . mysqli_connect_error();
    exit();
}else {
	echo '<br>good';
}


// Requête SQL pour sélectionner les ordres 
$queryShape = "SELECT * FROM orders 
               WHERE prescript_lab IN (10,25,73,69,76,77)		
               AND order_status = 'processing'
               AND shape_copied_ftp = '0000-00-00 00:00:00'
               AND shape_name_bk <>''  ";

// Exécution de la requête SQL
$resultShape = mysqli_query($con, $queryShape);
echo '<br>QUERY : '.$queryShape;

var_dump($resultShape);
var_dump(constant("FTP_WINDOWS_VM"));

// Vérification des erreurs
if (!$resultShape) {
    die("Erreur lors de l'exécution de la requête SQL: " . mysqli_error($con));
}else {
	echo '<br>errorquery';
}
echo '<br>bonj6';

//--------------------------------------------------------------------------------------------
// Boucle à travers les résultats
while ($DataShape = mysqli_fetch_array($resultShape, MYSQLI_ASSOC)) {
    //var_dump($DataShape); // Afficher les données de chaque ligne pour le débogage
    // Ajoutez votre logique de traitement ici
	
	$NumeroCommandeEDLL = $DataShape['order_num'];
	
	$ftp_server_source = constant("FTP_WINDOWS_VM");
	$ftp_user_source   = constant("FTP_USER_SYNC_SHAPES");
	$ftp_pass_source   = constant("FTP_PASSWORD_SYNC_SHAPES");
	
	
		echo "<br><br><br>=======================================
		<br>Traitement de la commande: " . $DataShape['order_num'] . "<br>";
	 // echo '<br>bonj7'.$ftp_server_source;
	  
	  $conn_id_source = ftp_connect($ftp_server_source);
    ftp_login($conn_id_source, $ftp_user_source, $ftp_pass_source);

    // Vérification de la connexion FTP source
    if ((!$conn_id_source)) {
        die("La connexion FTP au serveur source a échoué !");
    }else {
		echo 'connection reussi' .  $conn_id_source;
	}
	
	$conn_id_source = ftp_connect($ftp_server_source);
	 $NumeroCommandeEDLL = $DataShape['order_num'];
    $remote_dir_source = '/ftp_root/Banque de traces/';
    $remote_file = $DataShape['shape_name_bk']; // Nom du fichier à rechercher
	$local_file = "/ftp_root/Banque de traces/En cours de copie/$NumeroCommandeEDLL.OMA";
	
	        $local_file_renamed = $local_file ;//. $NumeroCommandeEDLL . '.OMA'
		//echo '<br>bon10'.$local_file;
		//echo '<br>condes412' .$local_file_renamed;
		//var_dump($local_file);
        rename($local_file, $local_file_renamed);
	
	/*echo '<br>bonj8'.$local_file;
	echo '<br>bonj9a1' . $NumeroCommandeEDLL;
	echo '<br>bonj9a2' . $remote_dir_source;
	echo '<br>bonj9a3' . $remote_file;
	echo '<br>bonj9a4' . $conn_id_source;*/
	
	$ftp_server_source = constant("FTP_WINDOWS_VM");
	$ftp_user_source   = constant("FTP_USER_SYNC_SHAPES");
	$ftp_pass_source   =  constant("FTP_PASSWORD_SYNC_SHAPES");
	$conn_id_source    = ftp_connect($ftp_server_source) or die("Couldn't connect to S3: $ftp_server"); 
	//Login
	if (@ftp_login($conn_id_source, $ftp_user_source, $ftp_pass_source)) {
		echo '<br><b>Connexion au ftp  (Instance Windows AWS) Reussie</b>';
		
		echo '<br>nom du fichier oma =' . $remote_file;
		
		/*echo '<br>lol =' . $remote_dir_source;
			
			echo '<br>lol2 =' .$local_file;*/
	}else{
		echo '<br><b> !!! Erreur durant la tentative de connexion avec ftp !!!</b>';	
	}
	//Enable PASV ( Note: must be done after ftp_login() 
	ftp_pasv($conn_id_source, true);

	
	if (ftp_get($conn_id_source, $local_file, $remote_dir_source . $remote_file, FTP_BINARY)) {
        echo "Le fichier a été téléchargé avec succès depuis le serveur source.";
      


        // Connexion au serveur FTP de destination en fonction de prescript_lab
        $ftp_server_destination = '';
        $ftp_user_destination = '';
        $ftp_pass_destination = '';
        $remote_dir_destination = '';
		$Supplier ='';
		//echo '<br> datashape'.$DataShape['prescript_lab'] ;
		
			switch ($DataShape['prescript_lab']) {
				case 10:
					$ftp_server_destination = constant('SWISSCOAT_FTP');
					$ftp_user_destination = constant('FTP_USER_RCO');
					$ftp_pass_destination = constant('FTP_PASSWORD_RCO');
					$remote_dir_destination = 'FROM_DL/shapes/';
					$Supplier ='swisscoat';
					
					echo '<br>	FOURNISSEUR = ' . $Supplier;
					echo '<br>	SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination; */
					echo '<br>	CHEMIN DE DESTINATION = ' .$remote_dir_destination;
					
									

				

					

					break;
				case 25:
					$ftp_server_destination = constant("FTP_WINDOWS_VM");
					$ftp_user_destination = constant("FTP_USER_HKO");
					$ftp_pass_destination = constant("FTP_PASSWORD_HKO");
					$remote_dir_destination = '/ftp_root/Echange avec Fournisseurs/HKO/FROM DIRECT-LENS/shapes/';
					$Supplier ='hko';
					echo '<br>	FOURNISSEUR : ' . $Supplier;
					
					echo '<br>SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination;*/
					echo '<br>CHEMIN DE DESTINATION = ' .$remote_dir_destination;
					
					break;
				case 73:
					$ftp_server_destination = constant("FTP_WINDOWS_VM");
					$ftp_user_destination = constant("FTP_USER_KANDR");
					$ftp_pass_destination = constant("FTP_PASSWORD_KANDR");
					$remote_dir_destination = '/ftp_root/Echange avec Fournisseurs/K and R/FROM DIRECT-LENS/Order Jobs/Shapes/';
					$Supplier ='KNR';
					
					echo '<br>	FOURNISSEUR : ' . $Supplier;
					echo '<br>SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination; */
					echo '<br>CHEMIN DE DESTINATION = ' .$remote_dir_destination;
					
					break;
				case 69:
					$ftp_server_destination = constant('GKB_FTP');
					$ftp_user_destination = constant('FTP_USER_DLN');
					$ftp_pass_destination = constant('FTP_PASSWORD_DLN');
					$remote_dir_destination = '/Directlab/Order/';
					$Supplier ='gkb';
					echo '<br>	FOURNISSEUR : ' . $Supplier;
					
					echo '<br>SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination;*/
					echo '<br>CHEMIN DE DESTINATION = ' .$remote_dir_destination;
					
					break;
					
					
				case 77:
					$ftp_server_destination = constant('PROCREA_FTP');
					$ftp_user_destination = constant('FTP_USER_PROCREA');
					$ftp_pass_destination = constant('FTP_PASSWORD_PROCREA');
					$remote_dir_destination = '/';
					$Supplier ='PROCREA';
					
					
					echo '<br>	FOURNISSEUR : ' . $Supplier;
					
					echo '<br>SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination; */
					echo '<br>CHEMIN DE DESTINATION = ' .$remote_dir_destination;
			
					break; 
					
					
					
				case 76:
					$ftp_server_destination = constant('OVG_LAB_FTP');
					$ftp_user_destination = constant('FTP_USER_OVG_LAB');
					$ftp_pass_destination = constant('FTP_PASSWORD_OVG_LAB');
					$remote_dir_destination = '/Shapes/';
					$Supplier ='ovg_lab';
					echo '<br>	FOURNISSEUR : ' . $Supplier;
					
					
					echo '<br>SERVEUR = ' .$ftp_server_destination;
					/*echo '<br>	BLOCB' .$ftp_user_destination;
					echo '<br>	BLOCC' .$ftp_pass_destination;*/
					echo '<br>CHEMIN DE DESTINATION = ' .$remote_dir_destination;
					
					  $conn_id_sftp = ssh2_connect($ftp_server_destination, 22);
                if (ssh2_auth_password($conn_id_sftp, $ftp_user_destination, $ftp_pass_destination)) {
                    $sftp = ssh2_sftp($conn_id_sftp);
                    $sftp_stream = fopen("ssh2.sftp://$sftp$remote_dir_destination" . $NumeroCommandeEDLL . '.OMA', 'w');

                    if ($sftp_stream) {
                        $data_to_send = file_get_contents($local_file_renamed);
                        if ($data_to_send === false) {
                            die('Erreur lors de la lecture du fichier local.');
                        }

                        if (fwrite($sftp_stream, $data_to_send) === false) {
                            die('Erreur lors de l\'écriture du fichier sur le serveur de destination.');
                        }

                        fclose($sftp_stream);
                        echo "Le fichier a été copié avec succès sur le serveur de destination via SFTP.";
                    } else {
                        echo "Erreur lors de l'ouverture du fichier distant sur le serveur de destination.";
                    }
                } else {
                    echo "La connexion SFTP au serveur de destination a échoué.";
                }

                continue 2; // Passer à l'itération suivante de la boucle while
					
					
			
					

				default:
					// Handle the case when prescript_lab doesn't match any expected value
					echo '<br>Labo introuvable ';
					 break; // Passer à l'itération suivante de la boucle while
			}
			
		// Renommer le fichier avec la valeur de $NumeroCommandeEDLL
        $NumeroCommandeEDLL = $DataShape['order_num'];
        $local_file_renamed = $local_file ;//. $NumeroCommandeEDLL . '.OMA'
		//echo '<br>bon10'.$local_file;

        rename($local_file, $local_file_renamed);

        }
	//echo '<br>bonj9b';
	
	        $conn_id_destination = ftp_connect($ftp_server_destination);
        ftp_login($conn_id_destination, $ftp_user_destination, $ftp_pass_destination);

        // Vérification de la connexion FTP de destination
        if ((!$conn_id_destination)) {
            die("La connexion FTP au serveur de destination a échoué !");
        }else {
			echo 'con destination reussi ';
		}
		

		//echo '<br>condes1' .$conn_id_destination;
		//var_dump($conn_id_destination);
		//echo '<br>condes2' .$remote_dir_destination;
		//var_dump($remote_dir_destination);
		//echo '<br>condes3' .$NumeroCommandeEDLL;
		//var_dump($NumeroCommandeEDLL);
		//echo '<br>condes4' .$local_file_renamed;
		//var_dump($local_file_renamed);
		
ftp_pasv($conn_id_destination, true);


if ($conn_id_destination) {
    // Vérification si les variables nécessaires sont définies
    if (!empty($NumeroCommandeEDLL) && !empty($remote_dir_destination) && !empty($local_file_renamed)) {
		
		echo '<br>A1' .$conn_id_destination;
		echo '<br>A2' .$NumeroCommandeEDLL;
		echo '<br>A3' .$remote_dir_destination;
		echo '<br>A4' .$local_file_renamed;
        // Transférer le fichier vers le serveur de destination
        if (ftp_put($conn_id_destination, $remote_dir_destination . $NumeroCommandeEDLL . '.OMA', $local_file_renamed, FTP_BINARY)) {
            echo " Le fichier a été copié avec succès sur le serveur de destination.";
            // Autres opérations après la copie réussie...
        } else {
            echo "Erreur lors de la copie du fichier sur le serveur de destination: " ;
        }
    } else {
        echo "Une ou plusieurs variables nécessaires sont vides.";
    }
} else {
    echo "La connexion FTP au serveur de destination a échoué.";
}
		
}



// Fermer la connexion à la base de données MySQL
mysqli_close($con); 
?>
