<?php
require_once(__DIR__.'/../../constants/url.constant.php');
?>
<ul id="new-menu">
	<li>                         
		<?php 	if ($mylang == 'lang_french') {  ?>
           <a href="<?php echo constant('DIRECT_LENS_URL'); ?>/safety/">Accueil</a>
        <?php  	}else{ ?>
            <a href="<?php echo constant('DIRECT_LENS_URL'); ?>/safety/">Home</a>
        <?php 	} ?>                          
	</li>
    <li>
		<?php 	if ($mylang == 'lang_french') {  ?>
           <a href="apropos.php">À propos</a>
        <?php  	}else{ ?>
            <a href="aboutus.php">About Us</a>
        <?php 	} ?>                          
	</li>
    <li>
        <a href="products.php">                            
        <?php 	if ($mylang == 'lang_french') {  ?>
            Produits
        <?php  	}else{ ?>
            Products
        <?php 	} ?>    </a>
	</li>
    <li>
        <a href="#">
        <?php 	if ($mylang == 'lang_french') {  ?>
            Accès
        <?php  	}else{ ?>
            Access
        <?php 	} ?>                           
        </a>
        <ul>
            <li>
                <a href="login.php">         
                <?php 	if ($mylang == 'lang_french') {  ?>
                    Accès clients
                <?php  	}else{ ?>
                    Customer Access
                <?php 	} ?></a>
            </li>
            <li>
                <a href="/labAdmin">          
                <?php 	if ($mylang == 'lang_french') {  ?>
                    Accès professionnels
                <?php  	}else{ ?>
                    Professional Access
                <?php 	} ?>    </a></li>  
            <li>
                <a href="requestAccount.php">
                <?php 	if ($mylang ==  'lang_french') {  ?>
                    Ouvrir un compte
                <?php  	}else{ ?>
                    Open an Account
                <?php 	} ?>                
                </a>
            </li>                                                                          
        </ul>
	</li>
    <li>
        <a href="contact.php">Contact</a>
   </li>                                                                          
</ul>
<div style="clear:both;"></div>