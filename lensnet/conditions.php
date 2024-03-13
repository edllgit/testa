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
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>

<!--[if !IE]>-->
<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
      }
    </script>
<!--<![endif]-->
<!--<![endif]-->

<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="../send_email.php" method="post" name="contactForm" id="contactForm">
           
<div class="bigwelcome">
<?php
if ($mylang == 'lang_french') {
echo 'Conditions d\'utilisation';	
}else{
echo 'Terms and Conditions';
}
			
?>
</div>  
            
            
            
            
	    <div> <?php if ($mylang == 'lang_french') { 
				 echo '<br><br><p><b>Garantie</b><br>
Il n\'y a pas de garantie d\'après-vente.<br><br>
LensNet Club LLC garanti la qualité et les normes de tolérance pour chaque verre non-taillé. Dans le cas qu\'un produit ne réponds à la commande effectuée, retournez la(les) lentille(s) défectueuse(s) et LensNet LLC émettra un crédit après vérification par le laboratoire.<br><br>
Toutes demandes pour un échange ou un remboursement doit être soumis par la poste au laboratoire 20 jours après la réception de la commande.<br><br>Un agent du service à la clientèle vous contactera après l\'approbation de votre compte pour vos transactions futures.</p>';
				 }else if ($mylang == 'lang_english'){
				 echo '<br><br><p><b>Warranty</b><br>
There is no after-sale warranty.<br><br>
Lensnet Club LLC guarantees the quality and tolerances for each uncut order. In case of non-compliance of a product received, please return the defective lenses and Lensnet Club LLC will issue a credit if warranted in accordance with the verification of the partner laboratory. <br><br>
All claims, requests for exchange or refund must be made by post with a DirectLab laboratory in charge of order management within 20 days of the receipt of the order. <br><br>
A Customer Service Agent will contact you upon approval of your account for future transactions.</p>';
				 } else {
				 echo '<br><br><a style="text-decoration:none;" target="_blank" href="pdf/terms_sp.pdf">LensNet Club Terms</a> ';
				 } ?>
        <p>&nbsp;</p>
      </div>
		   
		    <div align="center" style="margin:11px">
		      	<p>
		      	
	      	  </p>
   	  </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>