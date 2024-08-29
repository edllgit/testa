<?php
// Connexion à la base de données et récupération des options en fonction de $_GET['index']

// Exemple : connexion à la base de données

$serveur = "SRVWEB-Prod";
$utilisateur = "appuser";
$mot_de_passe = "p1a1nt3xtbad";
$base_de_donnees = "direct54_dirlens";
$con = mysqli_connect("SRVWEB-Prod", "appuser", "p1a1nt3xtbad", "direct54_dirlens");

if (!$con) {
    die('Could not connect: ' . mysqli_error());
}

// Récupérer l'index sélectionné
$selected_index = $_GET['index'];

// Requête pour récupérer les options correspondantes
$query_coating = "SELECT DISTINCT coating FROM exclusive WHERE index_v = '$selected_index'";
$query_photo = "SELECT DISTINCT photo FROM exclusive WHERE index_v = '$selected_index'";
$query_polar = "SELECT DISTINCT polar FROM exclusive WHERE index_v = '$selected_index'";

$result_coating = mysqli_query($con, $query_coating);
$result_photo = mysqli_query($con, $query_photo);
$result_polar = mysqli_query($con, $query_polar);

$options = array(
    'coating' => array(),
    'photo' => array(),
    'polar' => array()
);

// Construire les options pour COATING
while ($row = mysqli_fetch_assoc($result_coating)) {
    $options['coating'][] = array(
        'value' => $row['coating'],
        'name' => $row['coating']
    );
}

// Construire les options pour PHOTO
while ($row = mysqli_fetch_assoc($result_photo)) {
    $options['photo'][] = array(
        'value' => $row['photo'],
        'name' => $row['photo']
    );
}

// Construire les options pour POLAR
while ($row = mysqli_fetch_assoc($result_polar)) {
    $options['polar'][] = array(
        'value' => $row['polar'],
        'name' => $row['polar']
    );
}

// Retourner les options au format JSON
echo json_encode($options);

// Fermer la connexion à la base de données
mysqli_close($con);
?>
