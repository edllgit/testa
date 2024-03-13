<?php 
include "../../Connections/directlens.php";
session_start();
$_SESSION['Promo_oneyear']= $_POST[oneyear]; 
//echo  $_SESSION['Promo_oneyear'];
include('inc/header.php');
?>



<?php if ($_SESSION['Language_Promo']== 'french')
{
echo '<h1>Connexion</h1>
<h2>Veuillez vous connecter</h2>';
}else{
echo '<h1>Login</h1>
<h2>Please log in</h2>';
}  ?> 
  

<form id="form_login" name="form_login" method="post" action="dllogin.php">
 	<input type="hidden"   name="oneyear" id="oneyear" value="<?= $_POST[oneyear]; ?>" />
    <blockquote>
       
       <?php if ($_SESSION['Language_Promo']== 'french')
{
 echo '<p>Nom d\'utilisateur <input name="user_id" type="text" id="user_id" size="10">
          Mot de passe <input name="password" type="password" id="password" size="10">';
}else{
  echo '<p>Login <input name="user_id" type="text" id="user_id" size="20">
          Password <input name="password" type="password" id="password" size="20">'; 
}  ?> 
        
       </p>               
    </blockquote> 	      
   
   <?php if ($_SESSION['Language_Promo']== 'french')
{
echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" value="Soumettre"  /></p>';     
}else{
echo '<p style="margin-left:200px;"><input name="envoyer" type="submit" value="Submit"  /></p>';     
}  ?> 
   
</form>

<?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  ?> 