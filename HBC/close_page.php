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
</head>
<body onLoad="javascript:window.close()"> 
<body>
</body>
</html>