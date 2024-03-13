<?php session_start();
//On doit sauvegarder le nom de la shape uploadé  dans  le champ myupload de la table orders;
$key     					        = $_REQUEST[key];
$Nom_Trace 				            = substr($key,7,strlen($key)-7);
$_SESSION['RedoData']['myupload']   = $Nom_Trace;

//echo '<br>Key:'        .  $key;
//echo '<br>Longeur Key:'.  strlen($key);
//echo '<br>Myupload:'. $_SESSION['RedoData']['myupload'];
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