<?php 
// AFFICHER LES ERREURS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../sec_connectEDLL.inc.php";
session_start();

$order_num = isset($_REQUEST['order_num']) ? $_REQUEST['order_num'] : '';

$que = "SELECT primary_key FROM ORDERS WHERE order_num = $order_num";
$resultMajTrace1 = mysqli_query($con, $que) or die ('Erreur dans la requête de sélection : ' . $que  . mysqli_error($con));

$key = '';

if ($row = mysqli_fetch_assoc($resultMajTrace1)) {
    $key = $row['primary_key'];
    echo 'La valeur de primary_key est : ' . $key;

    // Extraction du nom de la trace de l'URL actuelle
    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $parsed_url = parse_url($url);
    parse_str($parsed_url['query'], $query_params);
    $Nom_Trace = urldecode($query_params['key']);

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
    echo 'Aucun résultat trouvé pour la requête.';
}

mysqli_free_result($resultMajTrace1);
?>
