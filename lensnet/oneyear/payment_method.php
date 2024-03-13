<?php 
session_start();
//echo 'Logged in as: '.  $_SESSION["sessionUser_Id"];
include('inc/header.php');
?>    
<form id="form1" name="form1" method="post" action="payment_method2.php">
   
<?php if ($_SESSION['Language_Promo']== 'french'){ ?>
<blockquote>
        <h1>Mode de paiement</h1>
        <h2>Veuillez choisir votre mode de paiement</h2>
        <p><input type="radio" name="payment_method"  onClick="ActivateSubmit();" id="payment_method" value="credit_card" /> 
        Carte de cr&eacute;dit</p>
        <p><input type="radio" name="payment_method"  onClick="ActivateSubmit();" id="payment_method" value="check" /> 
        Ch&egrave;que</p>                
</blockquote> 	  

<?php }else{ ?>
<blockquote>
        <h1>Payment method</h1>
        <h2>Choose your payment method</h2>
        <p><input type="radio" name="payment_method"  onClick="ActivateSubmit();" id="payment_method" value="credit_card" /> 
        Credit Card</p>
        <p><input type="radio"  name="payment_method"  onClick="ActivateSubmit();" id="payment_method" value="check" /> 
        Check</p>                
</blockquote> 	 
<?php }?>  
  
          
    
    
    <?php if ($_SESSION['Language_Promo']== 'french')
{
 echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" disabled=\"disabled\" value="Soumettre"  /></p>';
 echo  '<br />';
 echo '<h2>Offre limit&eacute;e</h2>';  
}else{
  echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" disabled=\"disabled\" value="Next Step"  /></p>';
 echo  '<br />';
 echo '<h2>Limited Time Offer</h2>';
}  
 ?>  
   
    
</form>
<?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  
 ?>  
