<?php

$adresse_ip_ou_dns = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com";
$nom_utilisateur = "appuser";
$mot_de_passe = "p1a1nt3xtbad";
$nom_de_la_base_de_donnees = "direct54_dirlens";

//conexion a la BD
$con = mysqli_connect($adresse_ip_ou_dns, $nom_utilisateur, $mot_de_passe, $nom_de_la_base_de_donnees);

//Verifiction de la Conexion
if (!$con) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

 echo '<!DOCTYPE html>
<html>
<head>
    <title>Veuillez entrer la date avant d\'afficher les heures a laquelle les listes de montures ont été envoyées au labo</title>
	    <style>
        /* Styles pour centrer la div */

        .haut {
			border-radius: 70px;
			background-color: #f4a29e;
            position: absolute;
			text-align: center;
			width: 100%;
        }

		
		.bas {
			border-radius: 20px;
			display: flex;
			justify-content: space-evenly;
			background-color: #bb9a98;
			margin-top: 110px;
			width: 100%;
			position: absolute;
			flex-wrap: wrap;
			align-content: space-around;
			align-items: center;
			flex-direction: column;
		}
		
		table {
			border-collapse: collapse;
			border: 1px solid black;
			width: inherit;
			text-align: center;
		}
		
		 th, td {
            
            padding: 10px; /* Espacement interne des cellules */
            text-align: center; /* Centrer le texte dans les cellules */
        }
		
    </style>
</head>
<body>
	<div  class="haut">

	
	<h2>Veuillez entrer la date  pour afficher les heures a laquelle les listes de montures ont été envoyées au lab 
	<br> Please enter the date for displaying the times the frames lists where sent to the lab</h2>
	
	
    <form method="post">
        <label for = \"password\"  > Date (Year-Month-Day)  : </label>
        <input type = \"text\" name =date placeholder="aaaa-mm-jj">
        <input type="submit" name="afficher" value="afficher"> 
    </form><br> </div>' ;

echo '<br><br> <div class="bas"><hr>';



$date = $_POST[date];

//$fin = $_POST[fin_date];

//requete SQL
$sql = "SELECT clic_user_id,heure_clic FROM  clics where DATE(heure_clic) = '$date' ";


// Exécution de la requête
$resultat = mysqli_query($con, $sql);


// Vérification des erreurs d'exécution de la requête
if (!$resultat) {
    die("Erreur dans la requête : " . mysqli_error($con));
}

// Affichage des données
echo "<table >";
echo "<tr><th style='border: 1px solid black;' >Nom Succursales / Name Branches </th><th style='border: 1px solid black;'> Dates et Heures / Dates And Hours </th></tr>"; 

while ($ligne = mysqli_fetch_assoc($resultat)) {
    echo "<tr>";
    echo "<td style='border: 1px solid black;'>" . $ligne['clic_user_id'] . "</td>"; // Remplacez "id" par le nom de la colonne
    echo "<td style='border: 1px solid black;'><b>" . $ligne['heure_clic'] . "</b></td>"; // Remplacez "nom" par le nom de la colonne
    echo "</tr>";
}

echo "</table> <hr></div>  ";

echo "</html></body>";

// Fermeture de la connexion
mysqli_close($con);




?>