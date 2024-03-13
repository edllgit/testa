<?php 
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "../sec_connectEDLL.inc.php";
session_start();
//On doit sauvegarder le nom de la shape uploadé  dans  le champ myupload de la table orders;




$order_num = isset($_REQUEST['ordernum']) ? $_REQUEST['ordernum'] : '';

$que ="SELECT primary_key FROM ORDERS WHERE order_num = $order_num";
$resultMajTrace1 = mysqli_query($con, $que) or die ('Erreur dans la requête de sélection : ' . $que  . mysqli_error($con));

$key = '';

// Vérifiez si des lignes ont été renvoyées
if ($row = mysqli_fetch_assoc($resultMajTrace1)) {
    // La requête a renvoyé au moins une ligne, stockez la valeur dans une variable
    //$key = $row['primary_key'];
	$key = $Nom_Trace; 
    echo 'La valeur de primary_key est : ' . $key;

    // Utilisez $key pour définir $Nom_Trace uniquement lorsque $key a une valeur
    //$Nom_Trace = substr($key, 7, strlen($key) - 7);
	$Nom_Trace = substr($key, 0, 7); 
	
	//$Nom_Trace = $listItem['shape_name_bk'];
    echo '<br>tra1: ' . $Nom_Trace;
    $_SESSION['RedoData']['myupload'] = $Nom_Trace;
    $_SESSION['NomTraceReprise'] = $Nom_Trace;
    echo '<br>tra: ' . $Nom_Trace;
    echo '<br><br><br> Query: ' . $que;
    echo '<br>Order_num: ' . $order_num;
    echo '<br>Key: ' . $key;
    echo '<br>Longueur Key: ' . strlen($key);
    echo '<br>Myupload: ' . $_SESSION['RedoData']['myupload'];

    if ((strlen($order_num) == 7) && (strlen($Nom_Trace) > 0)) {
        // Mettre à jour dans la base de données
        $queryMajNomTrace = "UPDATE orders 
            SET shape_name_bk = '$Nom_Trace',
                myupload = '$Nom_Trace' 
            WHERE order_num = '$order_num'";
        echo '<br><br><br> Update Query: ' . $queryMajNomTrace;
        $resultMajTrace = mysqli_query($con, $queryMajNomTrace) or die ('Erreur dans la requête de mise à jour : ' . $queryMajNomTrace  . mysqli_error($con));
		
		        // Redirection vers la page précédente
        echo '<script>window.history.back();</script>';
    }
} else {
    // Aucune ligne n'a été renvoyée, vous pouvez gérer cela en conséquence
    echo 'Aucun résultat trouvé pour la requête.';
}

mysqli_free_result($resultMajTrace1);

?>



<?php /*
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "../sec_connectEDLL.inc.php";
session_start();
//On doit sauvegarder le nom de la shape uploadé  dans  le champ myupload de la table orders;




$order_num = isset($_REQUEST['ordernum']) ? $_REQUEST['ordernum'] : '';

$que ="SELECT primary_key FROM ORDERS WHERE order_num = $order_num";
$resultMajTrace1 = mysqli_query($con, $que) or die ('Erreur dans la requête de sélection : ' . $que  . mysqli_error($con));

$key = '';

// Vérifiez si des lignes ont été renvoyées
if ($row = mysqli_fetch_assoc($resultMajTrace1)) {
    // La requête a renvoyé au moins une ligne, stockez la valeur dans une variable
    $key = $row['primary_key'];
    echo 'La valeur de primary_key est : ' . $key;

    // Utilisez $key pour définir $Nom_Trace uniquement lorsque $key a une valeur
    //$Nom_Trace = substr($key, 7, strlen($key) - 7);
	$Nom_Trace = substr($key, 0, 7); 
    echo '<br>tra1: ' . $Nom_Trace;
    $_SESSION['RedoData']['myupload'] = $Nom_Trace;
    $_SESSION['NomTraceReprise'] = $Nom_Trace;
    echo '<br>tra: ' . $Nom_Trace;
    echo '<br><br><br> Query: ' . $que;
    echo '<br>Order_num: ' . $order_num;
    echo '<br>Key: ' . $key;
    echo '<br>Longueur Key: ' . strlen($key);
    echo '<br>Myupload: ' . $_SESSION['RedoData']['myupload'];

    if ((strlen($order_num) == 7) && (strlen($Nom_Trace) > 0)) {
        // Mettre à jour dans la base de données
        $queryMajNomTrace = "UPDATE orders 
            SET shape_name_bk = '$Nom_Trace',
                myupload = '$Nom_Trace' 
            WHERE order_num = '$order_num'";
        echo '<br><br><br> Update Query: ' . $queryMajNomTrace;
        $resultMajTrace = mysqli_query($con, $queryMajNomTrace) or die ('Erreur dans la requête de mise à jour : ' . $queryMajNomTrace  . mysqli_error($con));
    }
} else {
    // Aucune ligne n'a été renvoyée, vous pouvez gérer cela en conséquence
    echo 'Aucun résultat trouvé pour la requête.';
}

mysqli_free_result($resultMajTrace1);*/

?>

<!--!doctype html-->
<!--html>
<head>
    <meta charset="utf-8">
    <title>Shape Uploaded Successfully</title>
</head>

<body>
    <script>
        var win = window.open('', '_self', '');
        win.close();
    </script>
</body>

</html -->



