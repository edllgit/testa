<?php

// Chemin local où les fichiers seront stockés
$uploadPath = 'ftp_root/Banque de traces/redo/';

// Vérifier si un fichier a été soumis
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];

    // Générer un nom de fichier unique
    $fileName = uniqid('file_', true) . '_' . $file['name'];

    // Chemin complet pour le stockage local
    $destination = $uploadPath . $fileName;

    // Déplacer le fichier téléchargé vers le chemin local
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Le fichier a été téléchargé avec succès, vous pouvez effectuer d'autres opérations ici

        // Exemple : Redirection vers une page de succès
        header('Location: http://c.direct-lens.com/labAdmin/close_page2.php?filename=' . $fileName);
        exit;
    } else {
        // Erreur lors du déplacement du fichier
        echo "Erreur lors du téléchargement du fichier.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de fichier</title>
</head>
<body>

    <form method="post" enctype="multipart/form-data" action="chemin_local_vers_ce_script.php" name="formShape" id="formShape" target="_blank">
        <tr align="center">
            <td align="left" bgcolor="#FFDA85" colspan="8">
                <strong>ATTACHER UNE FORME:&nbsp;</strong>
            </td>
        </tr>

        <tr>
            <td align="left" colspan="8">
                <?php
                echo "Forme présentement attachée: <b>None</b>";
                ?>
            </td>
        </tr>

        <tr>
            <td align="left">&nbsp;
                <input type="file" name="file" id="file" onclick="btnupload.disabled=false;btnupload.value='Upload'" size="40">&nbsp;
            </td>

            <td align="left" colspan="7">&nbsp;
                <input type="submit" name="btnupload" id="btnupload" value="Upload" onclick="this.disabled=true;this.value='Uploaded';"/>
            </td>
        </tr>
    </form>

</body>
</html>
