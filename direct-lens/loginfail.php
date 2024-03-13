<?php include('inc/header.php');?>
    
<div id="content" class="page">
    <div id="content-text" class="full">
    	
	<?php	if ($mylang == 'lang_french') {  ?>
        <h2>Nous avons un problème...</h2>
        <p>Si vous tenter de vous connecter, il y a une erreur dans votre nom d'utilisateur ou mot de passe. 
        Souvenez vous que le mot de passe distingue les minuscules et majuscules. Veuillez essayer à nouveau. Si vous étiez connecté, 
        votre session a expiré.</p>
    <?php  }else{ ?>
        <h2>We have a problem</h2>
        <p>If you are attempting to log in, there was a problem with your login or password. Remember that passwords are case sensitive. 
        Please try again. If you were previously logged in, your session has expired.
        </p>
    <?php  } ?>
    <br /><br />
    
    <?php include('inc/connexion.php'); ?>
                   	            
    </div>                
</div> 

<?php include('inc/footer.php');?>
