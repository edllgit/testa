<?php
// Informations de connexion à la base de données
$hostname = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com"; // Nom d'hôte
$username = "appuser"; // Nom d'utilisateur
$password = "p1a1nt3xtbad"; // Mot de passe
$database = "direct54_dirlens"; // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli($hostname, $username, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Requête SQL pour sélectionner toutes les données de la table "votre_table"
$sql = "SELECT user_id,order_num,lab,order_date_shipped,knr_ref_num,order_product_name,order_product_id,order_total FROM `orders`  
where orders.user_id in ('vaudreuil','vaudreuilsafe') and prescript_lab = 73 and order_date_shipped BETWEEN '2023-07-01'
 and '2023-07-30'";
$result = $conn->query($sql);

// Affichage des données




if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>user_id ||</th><th>order_num ||</th><th>lab ||</th><th>order_date_shipped ||</th><th>knr_ref_num ||</th><th>order_product_name ||</th><th>order_product_id ||</th><th>order_total ||</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["order_num"] . "</td>";
        echo "<td>" . $row["lab"] . "</td>";
        echo "<td>" . $row["order_date_shipped"] . "</td>";
        echo "<td>" . $row["knr_ref_num"] . "</td>";
        echo "<td>" . $row["order_product_name"] . "</td>";
        echo "<td>" . $row["order_product_id"] . "</td>";
        echo "<td>" . $row["order_total"] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Aucune donnée trouvée.";
}



// Fermer la connexion
$conn->close();
?>
