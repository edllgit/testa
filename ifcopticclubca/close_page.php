<?php session_start();
//On doit sauvegarder le nom de la shape uploadé  dans  $_SESSION['PrescrData']['myupload'] = $_POST["file"];
$key       = $_REQUEST[key];
$Nom_Trace = substr($key,7,strlen($key)-7);
//$Nom_Trace = $key;
$_SESSION['PrescrData']['myupload'] = $Nom_Trace;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Shape Uploaded Sucessfully</title>
<h1>Shape Envoye avec Sucess Merci</h1>
</head>
<body onLoad="javascript:window.close()"> 
<body>
<script>
        // Fermer la fenêtre actuelle après un délai de 3 secondes (3000 millisecondes)
        setTimeout(function () {
            window.close();
        }, 3000);

        // Revenir à la page précédente après 3 secondes (peut être supprimé si vous ne le souhaitez pas)
        setTimeout(function () {
            history.back();
        }, 3000);
    </script>
</body>
</html>