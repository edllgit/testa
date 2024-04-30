<?php
// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    // Vérification si aucun fichier n'a été sélectionné
    if ($_FILES["file"]["error"] > 0) {
        echo "Erreur lors du téléchargement du fichier : " . $_FILES["file"]["error"];
    } else {
        // Récupérer le nom du fichier téléchargé
        $filename = $_FILES["file"]["name"];

        // Vérifier si le fichier a été téléchargé avec succès
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            // Enregistrement du nom du fichier dans la base de données
            $pdo = new PDO('mysql:host=SRVWEB-Prod;dbname=direct54_dirlens', 'appuser', 'p1a1nt3xtbad');
            $statement = $pdo->prepare("UPDATE orders SET shape_name_bk = :filename WHERE order_num = :id");
            $statement->execute([
                ':filename' => $filename,
                ':id' => $_POST['order_num']
            ]);

            // Envoyer une réponse JSON pour indiquer que le téléchargement a réussi
            echo json_encode(["success" => true, "filename" => $filename]);
			echo "<br><b>votre Shapes a été relier a la commande  avec success. merci </b> ";
			//echo '<meta http-equiv="refresh" content="3;url=javascript:history.go(-1)">'; // Redirection vers la page précédente après 3 secondes
			// Utiliser JavaScript pour la redirection après 3 secondes
			echo '<script>
					setTimeout(function() {
						window.history.go(-1); // Redirection vers la page précédente après 3 secondes
					}, 3000);
				  </script>';
            exit; // Arrêter le script PHP ici pour éviter tout autre affichage
        } else {
            echo json_encode(["success" => false, "message" => "Une erreur s'est produite lors du téléchargement du fichier."]);
            exit;
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Shape Uploaded Successfully</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<script>
// Fonction pour mettre à jour les informations sur la page après le téléchargement réussi
function updatePageWithSuccess(filename) {
    // Mettre à jour les informations sur la page ici
    var successMessage = 'Le fichier ' + filename + ' a été téléchargé et enregistré avec succès.';
    alert(successMessage); // Afficher un message de confirmation
    // Mettre à jour d'autres éléments de la page si nécessaire
}

// Soumettre le formulaire en utilisant AJAX
$('form').submit(function(event) {
    event.preventDefault(); // Empêcher le comportement par défaut du formulaire
    var formData = new FormData($(this)[0]); // Récupérer les données du formulaire
    $.ajax({
        url: $(this).attr('action'), // URL de traitement PHP
        type: $(this).attr('method'), // Méthode POST
        data: formData, // Données du formulaire
        processData: false,
        contentType: false,
        success: function(response) {
            // Vérifier si le téléchargement a réussi
            if (response.success) {
                // Mettre à jour les informations sur la page
                updatePageWithSuccess(response.filename);
            } else {
                // Afficher un message d'erreur si le téléchargement a échoué
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Une erreur s\'est produite lors du traitement de la requête.');
        }
    });
});
</script>

<form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="file" name="file">
    <input type="submit" value="Télécharger">
</form>
</body>
</html>
