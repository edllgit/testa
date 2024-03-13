<?php
// Informations de connexion à la base de données
$serveur = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com";
$utilisateur = "appuser";
$mot_de_passe = "p1a1nt3xtbad";
$base_de_donnees = "direct54_dirlens";

// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Requête SQL
$sql = "SELECT * FROM `orders` where order_product_id in (107490,
107491) and YEAR(order_date_processed) = 2022";

// Exécution de la requête
$resultat = $connexion->query($sql);

// Vérifier si la requête a réussi
if ($resultat) {
    // Nom du fichier CSV avec timestamp pour le rendre unique
    $timestamp = time();
    $nom_fichier_csv = "/Bitnami/wampstack-7.1.14-0/apache2/htdocs/mobile/csv_FF/commande_en_erreur_2022_$timestamp.csv";

    // Création et ouverture du fichier CSV en écriture
    $fichier_csv = fopen($nom_fichier_csv, 'w');

    // Écriture de l'en-tête avec les noms de colonnes
    $entete = $resultat->fetch_fields();
    $entete_csv = [];
    foreach ($entete as $colonne) {
        $entete_csv[] = $colonne->name;
    }
    fputcsv($fichier_csv, $entete_csv);

    // Écriture des données dans le fichier CSV
    while ($ligne = $resultat->fetch_assoc()) {
        fputcsv($fichier_csv, $ligne);
    }

    // Fermeture du fichier CSV
    fclose($fichier_csv);

    echo "Exportation réussie vers $nom_fichier_csv";
} else {
    echo "Erreur dans la requête : " . $connexion->error;
}

// Fermeture de la connexion à la base de données
$connexion->close();
?>
