
<div id="menu">
		<br /><br />
<?php 	if ($mylang == 'lang_french') {  ?>

	<ul>
    	<li><a href="index.php">ACCUEIL</a><br /><br /></li>
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">CONNEXION CLIENT</a></li>
    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>    
        <li><a href="basket.php">MON PANIER D'ACHAT</a></li>
        <li><a href="order_history.php">HISTORIQUE D'ACHAT</a></li> 
		
		 
	<tr bgcolor="#DDDDDD">
		<td align="left">
			<form target="_blank" action="http://www.direct-lens.com/labAdmin/fastPrint.php" method="post" name="form1" id="form1">
				<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer commande #';
                }else {
                echo 'Print Order #';
                }?>			
                <input type="text" name="print_order_num" id="print_order_num" size="7">  
                <input type="submit"  name="Submit" value="<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer';
                }else {
                echo 'Print';
                }?>" class="formField" />			
                <?php if ($print_order_with_price == "yes"){  ?>
                <input type="hidden" name="pr" value="yes">  
                <?php  } //end if ?>
            </form>
       	</td>
	</tr>

		
	                     
    <?php }?>       
        
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>                
        <li><br /><br /><a href='logout.php'>DECONNEXION</a></li>
    <?php }?>         
	</ul>
        
<?php  	}else{ ?>

<ul>
    	<li><a href="index.php">HOME</a><br /><br /></li>
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href="login.php">CUSTOMER LOGIN</a></li>
    <?php }?> 
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>    
        <li><a href="basket.php">MY SHOPPING CART</a></li>
        <li><a href="order_history.php">ORDER HISTORY</a></li> 
           
          
    <?php }?>     
    
	<?php  if($_SESSION["sessionUser_Id"]!=""){ ?>                
        <li><br /><br /><a href='logout.php'>LOGOUT</a></li>
    <?php }?>         
	</ul>

<?php 	} ?>       

	<br /><br />
	<?php include("includes/translator.php"); ?>
</div>
<br /><br />
