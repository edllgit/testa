
<div id="menu">
		<br /><br />
<?php 	if ($mylang == 'lang_french') {  ?>

	<ul>
    	<li><a href="index.php">ACCUEIL</a><br /><br /></li>
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">CONNEXION CLIENT</a><br><br></li>
    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>    
 		<li><a href="myAccount.php">MON COMPTE</a></li>
        <li><a href="basket.php">MON PANIER D'ACHAT</a></li>
        <li><a href="order_history.php">HISTORIQUE DE COMMANDES</a></li> 
        <li><a href="credit_history.php">HISTORIQUE DE CREDITS</a></li> 
        <br>     
        <li><a href="lens_cat_selection.php">ACHAT DE PACKAGES</a></li>  
        <li><a href="stock_frames2.php">ACHAT DE MONTURES</a></li>          
    <?php }?>     
        <li><a href="contact.php">CONTACTEZ-NOUS</a></li>
        <li><a href="conditions.php">CONDITIONS</a></li>	
        
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>                
        <li><br /><br /><a href='logout.php'>DECONNEXION</a></li>
    <?php }?>         
	</ul>
        
<?php  	}else{ ?>

<ul>
    	<li><a href="index.php">HOME</a><br /><br /></li>
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">CUSTOMER LOGIN</a><br><br></li>

    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>    
 		<li><a href="myAccount.php">MY ACCOUNT</a></li>
        <li><a href="basket.php">MY SHOPPING CART</a></li>
        <li><a href="order_history.php">ORDER HISTORY</a></li> 
        <li><a href="credit_history.php">CREDIT HISTORY</a></li>     
		<br>
      

        <li><a href="lens_cat_selection.php">PURCHASE PACKAGES</a></li>   
        <li><a href="stock_frames2.php">PURCHASE FRAMES</a></li>    
                 
    <?php }?>     
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

