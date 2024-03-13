<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');


define("OVG_LAB_FTP", "47.100.11.65"); // Remplacez par l'adresse IP ou le nom de domaine de votre serveur FTP
define("FTP_USER_OVG_LAB", "U56yKp1a"); // Remplacez par votre nom d'utilisateur FTP
define("FTP_PASSWORD_OVG_LAB", "w9H6n3xy"); // Remplacez par votre mot de passe FTP



// Utiliser les constantes pour la connexion FTP
$ftp_server = constant("OVG_LAB_FTP");
$ftp_user = constant("FTP_USER_OVG_LAB");
$ftp_pass = constant("FTP_PASSWORD_OVG_LAB");

// Fonction pour récupérer les messages d'erreur FTP
function getFtpError($conn_id) {
    $error = error_get_last();
    if ($error && strpos($error['message'], 'ftp_') !== false) {
        return $error['message'];
    } else {
        return ftp_last_error($conn_id);
    }
}

// Établir une connexion FTP
$conn_id = ftp_connect($ftp_server) or die("Impossible de se connecter à $ftp_server");

// Tentative de connexion avec le nom d'utilisateur et le mot de passe
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connecté en tant que $ftp_user@$ftp_server\n";

    // Activer le mode passif (optionnel, en fonction de la configuration du serveur FTP)
    ftp_pasv($conn_id, true);

    // Changer le répertoire distant si nécessaire
    ftp_chdir($conn_id, "Orders");

    // Upload d'un fichier local vers le serveur FTP
    $local_file = "C:/Users/Administrator/Desktop/KNR_OrderData-2023-11-04.csv";
    $remote_file = "KNR_OrderData-2023-11-04.csv";

    if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
        echo "Téléversement réussi : $local_file vers $remote_file\n";
    } else {
        echo "Il y a eu un problème lors du téléversement de $local_file\n";
        echo "Erreur FTP : " . getFtpError($conn_id);
    }

    // Fermer la connexion FTP
    ftp_close($conn_id);
} else {
    echo "Impossible de se connecter en tant que $ftp_user\n";
}









?>
