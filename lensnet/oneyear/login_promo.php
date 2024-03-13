<?php 
include ('../../Connections/directlens.php');
session_start();
$_SESSION['Promo_oneyear']= $_POST[oneyear]; 
include('inc/header.php');
?>

<?php if ($_SESSION['Language_Promo']== 'french')
{
echo "<h1>Connexion</h1>";
echo "<h2>Si vous &ecirc;tes d&eacute;ja membre de Lensnet Club, Veuillez vous connecter</h2>";   
}else{
echo "<h1>Login</h1>";
echo "<h2>If you already have a Lensnet Club account, Please log in</h2>";   
}  ?>   


<form id="form_login" name="form_login" method="post" action="dllogin.php">
 	<input type="hidden"   name="oneyear" id="oneyear" value="<?= $_POST[oneyear]; ?>" />
   
    <blockquote>
       <?php if ($_SESSION['Language_Promo']== 'french')
		{
	echo "<p>Nom d'utilisateur <input name=\"user_id\" type=\"text\" id=\"user_id\" size=\"10\">           
		Mot de passe <input name=\"password\" type=\"password\" id=\"password\" size=\"10\"> 
       </p> ";   
		}else{
	   	echo "<p>Login <input name=\"user_id\" type=\"text\" id=\"user_id\" size=\"20\">           
		Password <input name=\"password\" type=\"password\" id=\"password\" size=\"20\"> 
       </p> "; 
		}  ?>                  
    </blockquote> 	     


 <?php if ($_SESSION['Language_Promo']== 'french')
		{
		echo "<p style=\"margin-left:200px;\"><input name=\"envoyer\" type=\"submit\" value=\"Soumettre\"  /></p>"; 
		}else{
		echo "<p style=\"margin-left:200px;\"><input name=\"envoyer\" type=\"submit\" value=\"Submit\"  /></p>";  
		}  ?>   
    
    
    
    
    <br />
    
    
    <?php if ($_SESSION['Language_Promo']== 'french')
		{
		echo " <h2>Si vous n'avez pas de compte Lensnet Club account, <br />Veuillez en cr&eacute;er un ici</h2>"; 
		}else{
			echo " <h2>If you don't already have a Lensnet Club account, <br />Please create one here</h2>";   
		}  ?>   
   
</form>  
           
<form id="form_create_acct" name="form_create_acct" method="post" action="promo_create_account.php">
 	<input type="hidden" id="oneyear"  name="oneyear" value="<?= $_POST[oneyear] ;?>" />
     
	 <?php if ($_SESSION['Language_Promo']== 'french')
		{
		echo "<p style=\"margin-left:200px;\"><input name=\"envoyer\" type=\"submit\" value=\"Cr&eacute;er un compte\"  /></p> "; 
		}else{
		echo "<p style=\"margin-left:200px;\"><input name=\"envoyer\" type=\"submit\" value=\"Create an account\"  /></p> "; 
		}  ?>   
        
  
</form>
<?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  
 ?>  