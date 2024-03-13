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
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
<link rel="shortcut icon" href="favicon.ico"/>

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="http://www.direct-lens.com/safety/js/nivo-slider/default.css" />
    <link rel="stylesheet" type="text/css" href="http://www.direct-lens.com/safety/js/nivo-slider/nivo-slider.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://www.direct-lens.com/safety/js/nivo-slider/jquery.nivo.slider.pack.js"></script> 

<script type="text/javascript">
function ChangeLang(mylang){
	
		var cur_lang=readCookie("mylang");

		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
}
</script>

</head>

<?php   if ($mylang == 'lang_france') {  
echo '<body onLoad="ChangeLang(\'lang_french\')">';
}else{
echo '<body>';
}
?>
<table align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="954">
    <tr>
        <td width="415" align="left">            
			<?php 	if ($mylang == 'lang_french') {  ?>
                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg" width="415" border="0"/>                    
            <?php  	}else{ ?>
                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg" width="415" border="0"/>
            <?php 	} ?>                  
        </td>
        <td width="635" colspan="2" align="right">
            <div class="accueil_top_nav">
                <strong>1-877-570-3522</strong> <br />
                <a href="https://www.facebook.com/AvantagesSecurite"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/facebook.gif" width="50" /></a>
                <br /><br />
                <div align="right">
                    <?php include("includes/translator.php"); ?>
                </div>                                                                      
            </div>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ee7e32" colspan="3">
        	<?php include("includes/new-menu.php"); ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
			<?php 	if ($mylang == 'lang_french') {  ?>
                <div class="blocs">
                    <h2>Avantages entreprises</h2>
                    <p>En magasinant vos lunettes avec SAFE AVANTAGES SÉCURITÉ, vous obtenez non seulement des lunettes de qualité pour travailler en 
                    toute sécurité, mais aussi un prix avantageux pour les groupes. </p>
                    <p>Un représentant se déplace pour vous rencontrer et vous présenter les différents produits et accessoires offerts. Tous nos 
                    produits sont testés et approuvés AINSI z87.1-2010 et CSA z94.3-07 qui sont des normes de qualité en matière de lunette de sécurité.</p>
                </div>
                <div class="blocs">
                    <h2>Avantages particuliers</h2>
                    <p>SAFE AVANTAGES SÉCURITÉ vous offre des lunettes stylées et sécuritaires à bon prix. Vous obtenez des lunettes de même qualité 
                    que celles portées dans les grandes usines. Elles rencontrent les normes standard en matière de sécurité et elles vous protègent 
                    de tout impact. </p>
                    <p>Des verres de qualité sont ajoutés à votre monture stylée et plusieurs accessoires peuvent être ajoutés à la lunette pour 
                    la rendre confortable et adaptée à vos besoins.</p>
                </div> 
            <?php  	}else{ ?>
                <div class="blocs">
                    <h2>Company Advantage</h2>
                    <p>By shopping your glasses with SAFE (Safety Advantage for Everyone), you not only get quality glasses to work safely, but also an outstanding group rate. A RRepresentative will make an appointment to show you the different frames and accessories that are offered. All our products are tested and approved according to CSA Z87.1-2010 AND z94.3-07 - Canadian safety standards for safety eyeglasses.</p>
                </div>
                <div class="blocs">
                    <h2>Personal Advantage</h2>
                    <p>SAFE (Safety Advantage for Everyone)  offers stylish and safe eyeglasses at affordable prices. They meet and exceed all national safety standards. The best in high impact resistant lenses are added to your stylish frame and many accessories can be added to the glasses to make it comfortable and adaptable to your needs.</p>
                </div>             
            <?php 	} ?>            
        	<div class="blocs">
                <br /><br /><br />
                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/bonhommes.jpg" width="238" height="146" />
            </div>    
            <div style="clear:both;"></div>          
         </td>    
    </tr>
    <tr>
        <td colspan="3" bgcolor="#ee7e32">
        	<span style="font-size:10px;line-height:10px;">&nbsp;</span>   
         </td>    
    </tr>    
    <tr>
        <td colspan="3">
			<script type="text/javascript">
                $(window).load(function() {
                    $('#slider').nivoSlider();
                });
            </script>
          
            <div id="slider" class="nivoSlider">
				<?php 	if ($mylang == 'lang_french') {  ?>
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety1.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety2.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety3.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety4.jpg" />
                <?php  	}else{ ?>
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety5.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety6.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety7.jpg" />
                    <img src="http://www.direct-lens.com/safety/design_images/header-safety8.jpg" />             
                <?php 	} ?>             
      
            </div>              
        </td>
    </tr>
    <tr>
        <td colspan="3" class="title-home">

			<?php 	if ($mylang == 'lang_french') {  ?>
                Plusieurs produits disponibles             
            <?php  	}else{ ?>
                Many products availables
            <?php 	} ?>                    
        </td>    
    </tr>      

    <tr>
        <td colspan="3"><?php include("footer.inc.php"); ?></td>
    </tr>
</table>
</body>
</html>
