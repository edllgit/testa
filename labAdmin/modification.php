<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

//echo 'Contenu de la variable session dans la page redoeditV3:<br><br>'. var_dump($_SESSION).'<br><br>';

//Inclusions
/*
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
//include("admin_functions.inc.php");
include("edit_order_functions.inc.php");
include("redo_order_functions.inc.php");
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
*/

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_redo'])) {
    // Récupérer les données du formulaire
    $orderNum = $_POST['order_num'];
    $userId = $_POST['user_id'];
    $pkey = $_POST['pkey'];
    $shapeNameBkField = $_POST['shape_name_bk_field'];
	
	
/*		//============================================
	
	$min_height = $_POST['min_height'];
	$max_height = $_POST['max_height'];
	$primary_key		  = $_POST['primary_key'];
	$order_product_id		  = $_POST['order_product_id'];
	$redo_reason_id		  = $_POST['redo_reason_id'];
	$explication_reprise		  = $_POST['explication_reprise'];
	$po_num		  = $_POST['po_num'];
	//$order_num		  = $_POST['order_num'];
	$eye		  = $_POST['eye'];
	$order_patient_first		  = $_POST['order_patient_first'];
	$order_patient_last		  = $_POST['order_patient_last'];
	$patient_ref_num		  = $_POST['patient_ref_num'];
	$tray_num		  = $_POST['tray_num'];
	$re_pr_io		  = $_POST['re_pr_io'];
	$re_pr_ax		  = $_POST['re_pr_ax'];
	$re_pr_ud		  = $_POST['re_pr_ud'];
	$re_pr_ax2		  = $_POST['re_pr_ax2'];
	$re_pd		  = $_POST['re_pd'];
	$re_pd_near		  = $_POST['re_pd_near'];
	$re_height		  = $_POST['re_height'];
	$le_pr_io		  = $_POST['le_pr_io'];
	$le_pr_ax		  = $_POST['le_pr_ax'];
	$le_pr_ud		  = $_POST['le_pr_ud'];
	$le_pr_ax2		  = $_POST['le_pr_ax2'];
	$le_pd		  = $_POST['le_pd'];
	$le_pd_near		  = $_POST['le_pd_near'];
	$le_height		  = $_POST['le_height'];
	$le_cyl		  = $_POST['le_cyl'];
	$re_cyl		  = $_POST['re_cyl'];
	$le_sphere		  = $_POST['le_sphere'];
	$re_sphere		  = $_POST['re_sphere'];
	$RE_CT		  = $_POST['RE_CT'];
	$LE_CT		  = $_POST['LE_CT'];
	$RE_ET		  = $_POST['RE_ET'];
	$LE_ET		  = $_POST['LE_ET'];
	$special_instructions		  = $_POST['special_instructions'];
	$internal_note		  = $_POST['internal_note'];
	$extra_product		  = $_POST['extra_product'];
	$job_type		  = $_POST['job_type'];
	$base_curve		  = $_POST['base_curve'];
	$tint_color		  = $_POST['tint_color'];
	$tint		  = $_POST['tint'];
	$frame_type		  = $_POST['frame_type'];
	$frame_a		  = $_POST['frame_a'];
	$frame_b		  = $_POST['frame_b'];
	$frame_ed		  = $_POST['frame_ed'];
	$frame_dbl		  = $_POST['frame_dbl'];
	$ep_frame_a		  = $_POST['ep_frame_a'];
	$supplier		  = $_POST['supplier'];
	$temple_model_num		  = $_POST['temple_model_num'];
	$color		  = $_POST['color'];
	$order_date_processed		  = $_POST['order_date_processed'];
	$order_num		  = $_POST['order_num'];
	$user_id		  = $_POST['user_id'];
	$shape_name_bk		  = $_POST['shape_name_bk'];
				*/
	//=================================================

    // ... Autres données du formulaire ...

    // Connexion à la base de données
    $servername = "SRVWEB-Prod";
    $username = "appuser";
    $password = "p1a1nt3xtbad";
    $dbname = "direct54_dirlens";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer et exécuter la requête de mise à jour
    $sql = "UPDATE Orders SET user_id = '$userId', Order_num = '$orderNum', po_num ='$po_num', tray_num = '$tray_num' , eye = '$eye', myupload = '$shapeNameBkField', shape_name_bk = '$shapeNameBkField', order_date_processed = '$order_date_processed',
            order_patient_first = '$order_patient_first', order_patient_last = '$order_patient_last', patient_ref_num = '$patient_ref_num', le_cyl = '$le_cyl', re_cyl = '$re_cyl', le_sphere ='$le_sphere', re_sphere ='$re_sphere', re_pr_io = '$re_pr_io',
            re_pr_io = '$re_pr_io' WHERE pkey = '$pkey'";

    echo '<br> query' . $sql;

    if ($conn->query($sql) === TRUE) {
        echo 'Mise à jour réussie';
        $message = "<b><font color=\"#00FF00\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - Lens UPDATED successfully </font></b>";

        // Rediriger vers la page précédente après 2 secondes
       // header("refresh:2;url=javascript://history.go(-1)");
	    echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "javascript://history.go(-1)"; }, 2000);</script>';
    } else {
        // Erreur lors de la mise à jour
        $message = "<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - Error updating lens: " . $conn->error . "</font></b>";
    }

    // Fermer la connexion
    $conn->close();
}
?>
