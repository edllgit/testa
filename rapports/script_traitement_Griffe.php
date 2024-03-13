<?php
// Connexion à la base de données (remplacez ces valeurs par les vôtres)
$serveur = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com";
$utilisateur = "appuser";
$mot_de_passe = "p1a1nt3xtbad";
$base_de_donnees = "direct54_dirlens";

$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupération des dates du formulaire
$dateDebut = $_POST['date_debut'];
$dateFin = $_POST['date_fin'];

// Requête SQL pour récupérer les données
$sql = "SELECT order_num,order_num_optipro,order_total,salesperson_id,order_date_shipped FROM orders
 WHERE salesperson_id like '%Boutique Griffe%' AND order_date_shipped BETWEEN '$dateDebut' AND '$dateFin'  ORDER BY orders.order_date_shipped ASC";

$resultat = $connexion->query($sql);

// Affichage des résultats sous forme de tableau HTML
echo "<table border='1'>
        <tr>
            <th>Order Number</th>
            <th>OptiPro Number</th>
            <th>Order Total $</th>
            <th>Boutique</th>
            <th>Date Shipped</th>
        </tr>";

// Affichage des résultats sur la page web
/*if ($resultat->num_rows > 0) {
    while ($row = $resultat->fetch_assoc()) {
        echo "#Order Number : " . $row["order_num"] . " | #OptiPro Number : " . $row["order_num_optipro"] .
			 " | Order Total $ : " . $row["order_total"] . " | Boutique : " . $row["salesperson_id"] .
			  " | Date Shipped : " . $row["order_date_shipped"] ."<br>";
    }
} else {
    echo "Aucun résultat trouvé pour la période spécifiée.";
}*/

if ($resultat->num_rows > 0) {
    while ($row = $resultat->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["order_num"] . "</td>
                <td>" . $row["order_num_optipro"] . "</td>
                <td>" . $row["order_total"] . " $</td>
                <td>" . $row["salesperson_id"] . "</td>
                <td>" . $row["order_date_shipped"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Aucun résultat trouvé pour la période spécifiée.";
}

// Fermeture de la connexion à la base de données
$connexion->close();
?>
