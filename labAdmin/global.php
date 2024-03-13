<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer toutes les données du formulaire
    $formData = $_POST;

    // Connexion à la base de données


	$servername = "SRVWEB-Prod";
    $username = "appuser";
    $password = "p1a1nt3xtbad";
    $dbname = "direct54_dirlens";
	
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer et exécuter la requête d'insertion
    $columns = implode(", ", array_keys($formData));
    $values = "'" . implode("', '", $formData) . "'";
    $sql = "INSERT INTO votre_table ($columns) VALUES ($values)";

    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement réussi";
    } else {
        echo "Erreur lors de l'enregistrement : " . $conn->error;
    }

    // Fermer la connexion
    $conn->close();
}
?>
