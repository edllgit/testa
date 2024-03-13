<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');

// Connexion à la base de données
$servername = "dlens3-3-ca.cttadsyeqj0j.ca-central-1.rds.amazonaws.com";
$username = "appuser";
$password = "p1a1nt3xtbad";
$dbname = "direct54_dirlens";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Répertoire où se trouvent les fichiers CSV
$repertoire_csv = "  /ftp_root/Echange avec Fournisseurs/K and R/TO_DIRECT_LENS";


// Ouvrir le répertoire
$dh = opendir($repertoire_csv);

if($dh !== false){
 echo 'repertoire ouvert';
}

// Parcourir tous les fichiers du répertoire
while (($fichier = readdir($dh)) !== false) {
    // Vérifier si le fichier est un fichier CSV
    if (pathinfo($fichier, PATHINFO_EXTENSION) == 'csv') {
        // Chemin complet vers le fichier
        $chemin_fichier = $repertoire_csv . $fichier;
echo '<br>fichier est un csv';
        // Ouvrir le fichier CSV en lecture
        $handle = fopen($chemin_fichier, "r");

        // Vérifier si le fichier a pu être ouvert
        if ($handle !== false) {
			echo '<br>fichier ouvert';
            // Lire les données du fichier CSV ligne par ligne
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Supposons que la première colonne est l'ID de commande,
                // la deuxième colonne est la référence KNR,
                // et la troisième colonne est le statut de la commande.
                $order_id = $data[0];
                $knr_reference = $data[4];
                //$order_status = $data[2];
				
				echo '<br>'.var_dump($order_id).'<br>'.var_dump($knr_reference);
				
				/*$Order_Number_CSV[1] 		= $orderArray[$count][1]=$data[0];
				$Status_Code_CSV[2]  		= $orderArray[$count][2]=$data[2];
				$Status_Descriptio_CSV[3] 	= $orderArray[$count][3]=$data[3];
				$KNR_REF_NUM[4] 			= $orderArray[$count][4]=$data[4];*/
				
                // Mettre à jour la base de données avec les nouvelles informations
                $query = "UPDATE orders SET knr_reference = '$knr_reference' WHERE order_id = $order_id";
				
				echo '<br>requete : '.$query.'';
                // Exécuter la requête
                $result = $conn->query($query);

                // Vérifier si la mise à jour a réussi
                if ($result) {
                    echo "Mise à jour réussie pour la commande ID $order_id\n";
                } else {
                    echo "Erreur lors de la mise à jour pour la commande ID $order_id : " . $conn->error . "\n";
                }
            }

            // Fermer le fichier CSV
            fclose($handle);
        } else {
            echo "Erreur lors de l'ouverture du fichier $chemin_fichier\n";
        }
    }
}

// Fermer le gestionnaire de répertoire
closedir($dh);

// Fermer la connexion à la base de données
$conn->close();
?>
