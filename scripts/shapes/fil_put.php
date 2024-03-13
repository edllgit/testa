<?php

// Configuration du serveur FTP
$ftp_server = 'ftp.direct-lens.com';
$ftp_user = 'synchronisation_shapes';
$ftp_password = 'r&7YEL!}&X&z.5V)z{9Hw/W';

// Chemin du fichier local à copier
$local_file = '/ftp_root/Banque de traces/En cours de copie/1652100.OMA';

// Chemin du fichier distant sur le serveur FTP
$remote_file = '/ftp_root/Echange avec Fournisseurs/HKO/FROM DIRECT-LENS/shapes/1652100.OMA';

// Établissement de la connexion FTP
$conn_id = ftp_connect($ftp_server);
if (!$conn_id) {
    die("La connexion au serveur FTP a échoué.");
}

// Connexion au serveur FTP avec nom d'utilisateur et mot de passe
$login_result = ftp_login($conn_id, $ftp_user, $ftp_password);
if (!$login_result) {
    die("La connexion au serveur FTP avec nom d'utilisateur et mot de passe a échoué.");
}

// Activation du mode binaire (pour assurer le transfert correct des fichiers binaires)
ftp_pasv($conn_id, true);
$transfer_mode = FTP_BINARY;

// Tentative de copie du fichier sur le serveur FTP
if (ftp_put($conn_id, $remote_file, $local_file, $transfer_mode)) {
    echo "Le fichier a été copié avec succès sur le serveur FTP.\n";
} else {
    echo "Une erreur s'est produite lors de la copie du fichier sur le serveur FTP.\n";
}

// Fermeture de la connexion FTP
ftp_close($conn_id);

?>
