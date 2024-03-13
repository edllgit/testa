<?php
// Connexion à la base de données (à adapter selon votre configuration)
$servername = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com";
$username = "appuser";
$password = "p1a1nt3xtbad";
$dbname = "direct54_dirlens";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Requête SQL pour compter le nombre de commandes pour l'année 2023
$sql = "SELECT COUNT(*) AS nombre_commandes FROM orders WHERE YEAR(order_date_processed) = 2023 AND user_id LIKE '%entrepotifc%' ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Récupération du résultat
    $row = $result->fetch_assoc();
    $nombreCommandes = $row["nombre_commandes"];

    // Enregistrement du nombre de commandes dans un fichier en mode append
    $filename = 'nombre_commandes_2023.txt';
    file_put_contents($filename, "Le nombre de commandes pour l'année 2023 Trois-rivieres est : " . $nombreCommandes . PHP_EOL, FILE_APPEND);

    echo "Le nombre de commandes pour l'année 2023 a été ajouté au fichier $filename.";
} else {
    echo "Aucun résultat trouvé.";
}

// Fermeture de la connexion
$conn->close();
?>
