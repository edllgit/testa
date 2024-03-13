
<?php
ini_set('display_errors',1); 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
require_once(__DIR__.'/../../constants/ftp.constant.php');
// Informations d'identification FTP
//define("HKO_FTP", getenv('HKO_FTP'));  var_dump(HKO_FTP);
//define('HKO_FTP', 'ftp.direct-lens.com');
define("FTP_WINDOWS_VM", getenv('FTP_WINDOWS_VM'));var_dump(FTP_WINDOWS_VM);
//define('FTP_USER_HKO', getenv('FTP_USER_HKO'));var_dump(FTP_USER_HKO);
/*
define('FTP_PASSWORD_HKO', getenv('FTP_PASSWORD_HKO')); var_dump(FTP_PASSWORD_HKO);// Lors de la connexion à HKO_FTP
define('FTP_PASSWORD_HKO_ALT', getenv('FTP_PASSWORD_HKO_ALT'));var_dump(FTP_PASSWORD_HKO_ALT); // Lors de la connexion à HKO_FTP dans votre script


$conn_idKNR = ftp_connect(constant("FTP_WINDOWS_VM"));
		// login with username and password
		$login_result = ftp_login($conn_idKNR, constant("FTP_USER_KANDR"), constant("FTP_PASSWORD_KANDR")); */

// KANDR
define('FTP_USER_KANDR', getenv('FTP_USER_KANDR')); var_dump(FTP_USER_KANDR);
define('FTP_PASSWORD_KANDR', getenv('FTP_PASSWORD_KANDR'));var_dump(FTP_PASSWORD_KANDR);

	$ftp_server = constant("FTP_WINDOWS_VM");var_dump(FTP_WINDOWS_VM);
	$ftp_user   = constant("FTP_USER_SYNC_SHAPES");var_dump(FTP_USER_SYNC_SHAPES);
	$ftp_pass   = constant("FTP_PASSWORD_SYNC_SHAPES");var_dump(FTP_PASSWORD_SYNC_SHAPES);

// Connexion au serveur FTP
$conn_id = ftp_connect(FTP_WINDOWS_VM);

if ($conn_id) {
    // Tentative d'authentification
    $login_result = ftp_login($conn_id, FTP_USER_KANDR, FTP_PASSWORD_KANDR);

    if ($login_result) {
        echo "Connexion FTP réussie en tant que " . FTP_USER_KANDR . "\n";

        // D'autres opérations FTP peuvent être effectuées ici, par exemple, lister les fichiers du répertoire courant
        $file_list = ftp_nlist($conn_id, ".");
        echo "Liste des fichiers dans le répertoire courant :\n";
        print_r($file_list);

        // Fermer la connexion FTP
        ftp_close($conn_id);
    } else {
        echo "Échec de l'authentification FTP\n";
    }
} else {
    echo "Impossible de se connecter au serveur FTP\n";
}

?>
