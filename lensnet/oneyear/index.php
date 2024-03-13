<?php 
session_start();
$_SESSION['Language_Promo']= 'english'; 
include('inc/header.php'); ?>

<a href="index_fr.php" class="vf">Version Fran&ccedil;aise</a>

<h1>Prepay and Save !</h1>
<h2>Choose your program and get the extra you want:</h2>   

<form id="form1" name="form1" method="post" action="login_promo.php">
<div id="content-left">
    <blockquote>
        <h1>Silver Program</h1>
        
        <h2>Choose any one of the following 4 options per every <big>$1000</big> prepaid:</h2>
        
        <p><input type="radio"  onClick="ActivateSubmit();" name="oneyear" id="1000-futureshop" value="1000-futureshop" /> 
        $50 Future Shop Gift Certificate</p>
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-lens" value="1000-lens" /> 
        $100 bonus in lenses</p>                
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-optipoints" value="1000-optipoints" /> 
        150 Opti-Points</p>
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="1000-ar" value="1000-ar" /> 
        15 pairs of FREE AR <small>($300 value - must be used in 30 days)</small></p>

    </blockquote> 	      
  <blockquote>
  <h1>Gold Program</h1>            
        
        <h2>Choose any one of the following 3 options per every <big>$5000</big> prepaid:</h2>
        
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-futureshop" value="5000-futureshop" />
        $200 Future Shop Gift Certificate</p>               
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-lens" value="5000-lens" />
        $300 bonus in lenses</p>     
        <p><input type="radio" onClick="ActivateSubmit();" name="oneyear" id="5000-optipoints" value="5000-optipoints" />
        400 Opti-Points</p>       
        <p><input type="checkbox" name="monthAr" id="5000-optipoints" value="5000-optipoints" checked="checked" disabled="disabled" />
        Unlimited free AR (INCLUDED in all options)</p>  
        
        <p>* With the only exception of the 2nd pair at $1 plus coating promotion ($21)</p>                         

    </blockquote>  
    
    <p style="margin-left:200px;"><input name="envoyer" type="submit" value="Buy a Program" disabled="disabled"  /></p> 
    
    <br />
    
    <h2>Limited Time Offer</h2>
</div> 
</form>
<div id="content-right">
    <img src="http://www.direct-lens.com/lensnet/images/iphone.png" width="350" height="560" alt="iphone" />
</div>    
<div class="clear"></div>      

<?php include('inc/footer.php'); ?>    
