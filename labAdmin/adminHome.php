<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
session_start();
?>
<html>
<head>
    <title><?php echo $adm_title_home;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body>

	<?php include("adminNav.php");?>
    <p>&nbsp;</p>


</body>
</html>
