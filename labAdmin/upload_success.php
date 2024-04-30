<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement réussi</title>
</head>
<body>
    <h1>Téléchargement réussi</h1>
    <p>Le fichier <?php echo $_GET['filename']; ?> a été téléchargé et enregistré avec succès.</p>
    <button onclick="history.go(-1);">Retour</button>
</body>
</html>
