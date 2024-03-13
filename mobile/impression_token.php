<?php
//TODO OPTIONNEL: AJOUTER DANS CE RAPPORT L'OPTION DE GÉNÉRER POUR TOUS LES MAGASINS D'UN CLIQUE, SANS DEVOIR REFAIRE LA SELECTION POUR CHAQUE SUCCURSALE
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include("../sec_connectEDLL.inc.php");
require_once('../class.ses.php');
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>                                                                         </title>
    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php
$Token      = $_REQUEST[tok];
$Provider 	= $_REQUEST[provider];
$ID			= $_REQUEST[ID];

//TODO: Tenter d'identifier le token a imprimer en validant certaines informations
$queryValiderToken 		  = "SELECT * FROM shipping WHERE token='$Token' AND compagnie_a_utiliser ='$Provider' AND shipping_ID= $ID";
//echo 	'<br>'. $queryValiderToken;
$resultValiderToken		  =  mysqli_query($con,$queryValiderToken);	
$NombreResultat=mysqli_num_rows($resultValiderToken);	
	
if ($NombreResultat>0){	
	$DataValiderToken  		  =  mysqli_fetch_array($resultValiderToken,MYSQLI_ASSOC);
	$NombreImpressionAvantMAJ =  $DataValiderToken[nombre_demande_impression];
	$NombreImpressionApresMAJ =  $NombreImpressionAvantMAJ+1;
	//echo "<br>Nombre d'impression Avant MAJ:". $NombreImpressionAvantMAJ;
	//echo "<br>Nombre d'impression APRES MAJ:". $NombreImpressionApresMAJ;

	//TODO: Enregistrer que le token a été imprimé par l'utilisateur, et le nombre de fois qu'il est imprimé également. [nombre_demande_impression]
	$QueryUpdate = "UPDATE shipping SET  nombre_demande_impression =$NombreImpressionApresMAJ
					WHERE token='$Token' AND shipping_ID= $ID";
	//echo 	'<br>'.$QueryUpdate;
	$resultUpdate 		     =  mysqli_query($con,$QueryUpdate);

	//Afficher le Token afin que l'employé puisse l'imprimer
	echo '<h3 align="center">Token:'. $Token.'</h3>';
}
	
?>

</body>