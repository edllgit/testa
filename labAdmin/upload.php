<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Emplacement où vous souhaitez enregistrer les fichiers sur votre serveur
    $uploadDir = '/Bitnami/wampstack-7.1.14-0/apache2/htdocs/labAdmin/S3-direct-lens-public/';

    // Assurez-vous que le répertoire d'upload existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Vérifiez s'il y a des erreurs lors de l'envoi du fichier
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Obtenez le nom du fichier et déplacez-le vers le répertoire d'upload
        $fileName = basename($_FILES['file']['name']);
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            // Redirection vers la page de confirmation
            $ordernum = isset($_POST['order_num']) ? $_POST['order_num'] : '';
			$key = isset($_POST['pkey']) ? $_POST['pkey'] : '';

            $filename = urlencode($fileName); // Assurez-vous que le nom de fichier est correctement encodé
            $confirmationPage = constant('DIRECT_LENS_URL') . '/labAdmin/close_page2.php?ordernum=' . $ordernum . '&filename=' . $filename;
            
            header("Location: $confirmationPage");
            exit; // Assurez-vous de terminer le script après la redirection
        } else {
            echo "Erreur lors du déplacement du fichier.";
        }
    } else {
        echo "Erreur lors de l'envoi du fichier. Code d'erreur : " . $_FILES['file']['error'];
    }
} else {
    echo "Accès non autorisé.";
}
?>
