<?php 
session_start();
include('inc/header.php'); 
?>
  
  
<?php if ($_SESSION['Language_Promo']== 'french')
{ ?>
<h1>Veuillez envoyer votre ch&egrave;que a:</h1>
<h3 style="margin-left:100px;">
LensNet Club<br /> 
2505 Boulevard Des Récollets<br />
Trois-Rivières QC <br />
G8Z 4G1</h3><br />
<h2>*Les chèques doivent &ecirc;tre re&ccedil;us au plus tard le 30 Juin 2012 pour profiter de cette promotion.</h2>   
<?php }else{ ?>
<h1>Please send cheques to:</h1>
<h3 style="margin-left:100px;">
LensNet Club<br /> 
2505 Boulevard Des Récollets<br />
Trois-Rivières QC <br />
G8Z 4G1</h3><br />
<h2>*Cheques must be received no later than June 30th to qualify for the promotion.</h2>  


<?php }  ?>  
   
 

<form action="check_confirm.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);">
	
   <?php if ($_SESSION['Language_Promo']== 'french')
{
 echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" value="Je m\'engage à envoyer mon chèque avant la date limite"  /></p>';     
}else{
 echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" value="I commit to send my check before the date limit"  /></p>';
}  ?>   

 
   
  
</form>
               
<?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  ?>   
