<div id="menu">
		<br /><br />
<?php 	if ($mylang == 'lang_french') {  ?>

	<ul>
    	<li><a href="index.php">ACCUEIL</a></li>
    	<li><a href="apropos.php">&Agrave; PROPOS</a><br /><br /></li>        
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">ACC&Egrave;S CLIENTS</a></li>
        <li><a href="login.php">ACC&Egrave;S PROFESSIONNELS</a></li>        
        <li><a href="requestAccount.php">OUVRIR UN COMPTE</a></li>
        <li><a href="products.php">PRODUITS</a><br /><br /></li>
    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>      
 		<li><a href="myAccount.php">MON COMPTE</a></li>
        <li><a href="basket.php">MON PANIER D'ACHAT</a></li>
        <li><a href="order_history.php">HISTORIQUE DE COMMANDES</a></li> 
        <li><a href="lens_cat_selection.php">ACHAT DE PACKAGES</a></li>               
    <?php }?>     
    
    
    <?php  if(($_SESSION["sessionUser_Id"]=="entrepotsafe") || ($_SESSION["sessionUser_Id"]=="redosafety")){ ?>   
        <li><a href="prescription.php">ACHAT DE VERRES</a></li>               
    <?php }?>     
    
       <?php /*?> <li><a href="produits.php">PRODUITS ET SERVICES</a></li><?php */?>
        <li><a href="contact.php">CONTACTEZ-NOUS</a></li>
        <li><a href="conditions.php">CONDITIONS</a></li>	
        
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>                
        <li><br /><br /><a href='logout.php'>DECONNEXION</a></li>
    <?php }?>         
	</ul>
        
<?php  	}else{ ?>

<ul>
    	<li><a href="index.php">HOME</a></li>
    	<li><a href="aboutus.php">ABOUT US</a><br /><br /></li>                
        
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">CUSTOMER ACCESS</a></li>
        <li><a href="login.php">PROFESIONNAL ACCESS</a></li>        
        <li><a href="requestAccount.php">OPEN AN ACCOUNT</a></li>
         <li><a href="products.php">PRODUCTS</a><br /><br /></li>
    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>           
 		<li><a href="myAccount.php">MY ACCOUNT</a></li>
        <li><a href="basket.php">MY SHOPPING CART</a></li>
        <li><a href="order_history.php">ORDER HISTORY</a></li> 
        <li><a href="lens_cat_selection.php">PURCHASE PACKAGES</a></li>               
    <?php }?>     
    
     <?php  if(($_SESSION["sessionUser_Id"]=="entrepotsafe") || ($_SESSION["sessionUser_Id"]=="redosafety")){ ?>    
        <li><a href="prescription.php">PURCHASE LENS</a></li>               
    <?php }?>  
    
       <?php /*?> <li><a href="produits.php">PRODUCTS &amp; SERVICES</a></li><?php */?>
        <li><a href="contact.php">CONTACT US</a></li>
        <li><a href="conditions-en.php">TERMS AND CONDITIONS</a></li>	
        
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>                
        <li><br /><br /><a href='logout.php'>LOGOUT</a></li>
    <?php }?>         
	</ul>

<?php 	} ?>       

	<br /><br />
	<?php include("includes/translator.php"); ?>
</div>

<br /><br />

