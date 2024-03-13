<?php 
session_start();
$_SESSION['Language_Promo']= 'french'; 
include('inc/header.php'); ?>

<a  href="index.php" class="vf">English Version</a>

<h1>Pr&eacute;payez et &eacute;pargnez gros !</h1>
<h2>Choisissez le montant pr&eacute;pay&eacute; et obtenez le cadeau de votre choix!</h2>   

<form id="form1" name="form1" method="post" action="login_promo.php">
<div id="content-left">
    <blockquote>
        <h1>Forfait Argent</h1>
        
        <h2>Choisissez un cadeau parmi ces 4 options pour chaque tranche de <big>1,000$</big> pr&eacute;pay&eacute;e :</h2>
        
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-futureshop" value="1000-futureshop" /> 
        Certificat de 50$ chez Future Shop</p>
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-lens" value="1000-lens" /> 
        $100 en verres ophtalmiques</p>                
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-optipoints" value="1000-optipoints" /> 
        150 Opti-Points</p>
        <p><input type="radio" onClick="ActivateSubmit();"  name="oneyear" id="1000-ar" value="1000-ar" /> 
        15 traitements antireflets gratuits   <small>(Une valeur de $300 doivent &ecirc;tre utilis&eacute;es dans les 30 jours suivant l'achat)</small></p>

    </blockquote> 	      
  <blockquote>
  <h1>Forfait Or</h1>            
        
        <h2>Choisissez un cadeau parmi ces 3 options pour chaque tranche de <big>5,000$</big> pr&eacute;pay&eacute;e :</h2>
        
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-futureshop" value="5000-futureshop" />
        Certificat de 200$ chez Future Shop</p>               
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-lens" value="5000-lens" />
        $300 en verres ophtalmiques</p>     
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-optipoints" value="5000-optipoints" />
        400 Opti-Points</p>       
        <p><input type="checkbox" name="monthAr" id="5000-optipoints" value="5000-optipoints" checked="checked" disabled="disabled" />
       Traitements antireflets gratuits sur toutes vos commandes (<small>Doit &ecirc;tre utilis&eacute;es dans les 30 jours suivant l'achat</small>)</p>  
        
        <p>* Non application avec la promotion 2e paire &agrave; 1$</p>                         

    </blockquote>  
    
    <p style="margin-left:200px;"><input name="envoyer" type="submit" value="Acheter un programme"  disabled="disabled" /></p> 
    
    <br />
    
    <h2>Offre limit&eacute;e</h2>
</div> 
</form>
<div id="content-right">
    <img src="http://www.direct-lens.com/lensnet/images/iphone.png" width="350" height="560" alt="iphone" />
</div>    
<div class="clear"></div>      

<?php include('inc/footer_fr.php'); ?>    
