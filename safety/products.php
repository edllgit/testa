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
<title><?php print $sitename;?></title>
    
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>

<script type="text/javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}
</script>
</head>

<body>
<div id="container">   
	
	<div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">				
				<?php 	if ($mylang == 'lang_french') {  ?>
                    Nos Produits
                <?php  	}else{ ?>
                   Our Products
                <?php 	} ?> 
            </div>            
		<div class="Subheader" style="height:600px;">  
        	
			<?php // <img src="http://www.direct-lens.com/safety/design_images/logo-armouRX.jpg" width="500"/>    ?>
			
			<?php 	if ($mylang == 'lang_french') {  ?>
            <h3>Le retour des montures à la mode en tant que lunettes sécuritaires</h3>
            <p>Il s'agit d'une première dans l'industrie de la protection visuelle : La collection ArmouRx combine le look et une sécurité à toute épreuve sur
            votre lieu de travail. ArmouRx a été développée par une des plus grandes compagnie de lunettes. Avec un historique de créativité et de
            hauts standards de qualité, la Collection ArmouRx est tout autant stylisée que fonctionnelle. Chacune des montures rencontrent toutes les
            normes de sécurités sur votre lieu de travail et son testée par un tiers an d'en assurer la qualité.</p>
            <h3>TESTÉES À L’EXTERNE</h3>
            <p>Pour des résultats ables, toutes les montures sont testés par une organisation indépendante de ArmouRx.</p>
            <p>Nous avons les collections :</p>    
            <ul>
            	<li>Collection Wrap-Rx</li>
            	<li>Collection Metro</li>
            	<li>Collection Classique</li>
            	<li>Collection Basique</li>                                                
            </ul>
            <?php  	}else{ ?>
            
            <h3>BRINGING FASHION BACK INTO SAFETY</h3>
            <p>A first among the safety eyewear industry, the Armourx Collection
            combines fashion and unique designs for safety eyewear in the workplace. Armourx was developed by one of Canada’s leading eyewear companies. 
            With an extensive background in creating eyewear that is fashion-forward and high in quality craftsmanship, the Armourx Collection is 
            both stylish and functional. Each frame provides standardized eye-protection for the workplace. Look stylish and feel comfortable 
            in Armourx’s individual collections from Wrap-Rx and Metro to Classic and Basic.</p>
            <h3>THIRD-PARTY TESTING</h3>
            <p>For peace of mind, all Armourx frames are third-party tested.</p>
            <p>Our Collections:</p>    
            <ul>
            	<li>Wrap-Rx Collection </li>
            	<li>Metro Collection</li>
            	<li>Classique Collection </li>
            	<li>Basic Collection</li>                                                
            </ul>          
            <?php 	} ?>           
        </div>
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>