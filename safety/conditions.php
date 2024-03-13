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
include("includes/pw_functions.inc.php");
global $drawme;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
    
</head>
<body>
<div id="container">    

    <div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">Conditions d'utilisation</div>
            <div class="messageText" style="line-height:15px">      
                         
                <div class="header2">Paiement</div>
                
                <p>Les termes sont NET, payable le 15 du mois suivant (15 jours après réception de l'état de compte). Les paiements par carte de crédit 
                (VISA, Mastercard,AMEX) sont acceptés.</p>
                
                <div class="header2">Annulation</div>
                
                <p>Une annulation d’une commande est possible tant que les lentilles ne sont pas en production, dans le cas contraire, l’annulation 
                entrainera des frais de 50%.</p>                
                
                <div class="header2">Changement de RX et reprise</div>
                
                <p>Un rabais de 50% sur facture peut être émis si le numéro de la première commande est inscris lors de la deuxième commande. Pour
                être élligible au rabais de 50%, on ne peut y trouver plus que 3 changements à la prescription (comme par exemple; monture, hauteur 
                d’ajustement, sphère, cylindre, axe, addition, distance pupillaire).</p>
                
                <div class="header2">Commande par téléphone</div>
                
                <p>La commande par téléphone et fax est disponible, des frais de 2.50$ seront chargés. Lors d’une prise de commande par téléphone, 
                la prescription sera répétée, en cas d’erreur de prescription, un rabais de 50% sera émis pour une reprise.</p>
                
                <div class="header2">Non-adaptation (sur progressif seulement)</div>
                
                <p>Toute demande de non-adaptation doit être retournée dans un délai de 90 jours de la date de commande. Le numéro de la première 
                commande et la raison de non-adaptation doit être soumis lors de la deuxième commande.</p>

                <div class="header2">Aucun remboursement</div>         

			</div>
        </div>
        <?php include("footer.inc.php"); ?>
	</div>

</div>

</body>
</html>
