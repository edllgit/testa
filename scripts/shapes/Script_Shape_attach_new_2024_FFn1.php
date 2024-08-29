<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$ftp_server_destination = '175.45.24.67'; // Remplacez par l'adresse de votre serveur FTP
$ftp_port = 22; // Le port SSH
$ftp_user_destination = 'rco'; // Nom d'utilisateur FTP
$ftp_pass_destination = 'rco2009'; // Mot de passe FTP

// Établir la connexion SSH
$connection = ssh2_connect($ftp_server_destination, $ftp_port);
if (!$connection) {
    die('La connexion SSH a échoué : Impossible d\'établir une connexion.');
} else {
    echo 'Connexion SSH réussie.<br>';
}

// Authentification avec nom d'utilisateur et mot de passe
if (!ssh2_auth_password($connection, $ftp_user_destination, $ftp_pass_destination)) {
    die('L\'authentification SSH a échoué : Nom d\'utilisateur ou mot de passe incorrect.');
} else {
    echo 'Authentification SSH réussie.<br>';
}

// Exécuter une commande simple
$command = 'echo "Hello, World!"';
$stream = ssh2_exec($connection, $command);
if (!$stream) {
    die('Échec de l\'exécution de la commande.');
} else {
    stream_set_blocking($stream, true);
    $output = stream_get_contents($stream);
    fclose($stream);
    echo "Sortie de la commande :<br><pre>$output</pre>";
}

// Fermer la connexion
ssh2_disconnect($connection);
echo 'Connexion SSH fermée.';
?>
