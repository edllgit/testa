<?php
require_once(__DIR__.'/../constants/aws.constant.php');
?>
<div id="footerBox">
    <div class="accueil_footer_nav" style="float:left;">
        © 2022 Safety - Avantage Sécurité - <a href="http://Direct-lens.com">Direct-lens.com</a> | 
        <?php 	if ($mylang == 'lang_french') {  ?>
            <a href="conditions.php">Conditions</a>             
        <?php  	}else{ ?>
            <a href="conditions-en.php">Terms and Conditions</a>
        <?php 	} ?>  
        | <a href="contact.php">Contact</a>       
    </div>
	<div style="float:right;margin-top:5px;margin-right:15px;"><a 
    href="https://www.facebook.com/AvantagesSecurite"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/safety/images/facebook.gif" width="35" /></a></div>
    <div style="float:right;margin-top:5px;"><?php include("includes/translator.php"); ?></div>
	<div style="clear:both;"></div>    
</div>
    
