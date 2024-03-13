<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
    
</head>

<body>
<div id="container">    
    
    <div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">À Propos</div>
            <div class="Subheader" style="height:300px;">
                <p>Des montures stylées à des prix plus que compétitif, voilà ce que propose SAFE Avantages Sécurité. </p>
              <p>Comme   entreprise, vous achetez des lunettes de sécurité en grande quantité et   nous voulons vous faire bénéficier de prix de groupe pour vous faire   économiser. </p>
                <p>Nous offrons une vaste gamme de produits à   des prix plus que compétitifs. Tous nos produits sont certifiés AINSI   z87.1-2010 et CSA z94.3-07 : des normes qui garantissent l’aspect   sécuritaire de nos lunettes. Toutes nos lunettes sont testées pour   résister à l’impact et ainsi vous garantir un maximum de sécurité. Des   verres de qualité sont installés sur nos montures de sécurité. Il vous   est aussi possible d’opter pour des verres en polycarbonate de notre   gamme Révolution pour un verre encore plus résistant. Et pourquoi pas   ajouter un anti-reflet pour maximiser votre confort visuel.  </p>
<p>Un   représentant peut se rendre dans votre entreprise pour vous faire   économiser temps et argent. Il vous fera part de nos offres avantageuses   et il pourra vous informer à propos de nos produits de qualité. Il vous   indiquera aussi les accessoires qui s’adaptent à vos montures pour   personnaliser celles-ci et les rendre plus confortable et mieux adaptées   à votre travail.  </p>
<p><strong>Le confort, le style et la sécurité : voilà la promesse SAFE Avantages Sécurité ! </strong></p>
<p>&nbsp;</p>
            </div><!--END Subheader-->             
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->

</body>
</html>