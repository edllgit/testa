<?php

// Configuration FTP
$ftp_server = 'ftp.direct-lens.com';
$ftp_user = 'optiproedll';
$ftp_pass = 'C9jrBrpou6N.4y7WaY-!hvTpYZ';

// Connexion FTP
$conn_id = ftp_connect($ftp_server) or die("Impossible de se connecter à $ftp_server");
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo 'Connexion FTP réussie.';
} else {
    die('Échec de la connexion FTP');
}

// Activer le mode passif
ftp_pasv($conn_id, true);

// Changer de répertoire si nécessaire
ftp_chdir($conn_id, '/ftp_root/Fichier recus de POS pour importation/Optipro EDLL');

// Récupérer la liste des fichiers
$contents = ftp_nlist($conn_id, ".");
if ($contents === false) {
    echo "Erreur lors de la récupération de la liste des fichiers.";
    exit;
}

// Date d'aujourd'hui
$today = date("Y-m-d");

// Filtrer les fichiers créés aujourd'hui
$files_today = array_filter($contents, function ($file) use ($today) {
    // Adapter ce filtre selon le format du nom de vos fichiers
    return strpos($file, $today) !== false;
});

// Traiter les fichiers trouvés
foreach ($files_today as $file) {
    // Faites quelque chose avec chaque fichier
    echo "Traitement du fichier : $file <br>";

    // Exemple : Télécharger le fichier localement
    $local_file = '/chemin/vers/dossier_local/' . $file;
    if (ftp_get($conn_id, $local_file, $file, FTP_BINARY)) {
        echo "Téléchargement réussi : $local_file <br>";
    } else {
        echo "Échec du téléchargement pour : $file <br>";
    }
}

// Fermer la connexion FTP
ftp_close($conn_id);

?>
