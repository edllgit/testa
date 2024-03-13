<?php include('inc/header.php');?>
    
<div id="content" class="page">
    <div id="content-text" class="full">

	<?php  if ($mylang == 'lang_french') {  ?>
    	<h2>Création d'un nouveau compte</h2>        
        <p class="conf-pos">
        	Merci d'avoir créé un compte Direct-Lens.com.
        </p> 
        <p class="conf-pos">
        	Lorsque votre compte sera approuvé, vous allez recevoir un email de confirmation avec votre nom d'usager et mot de passe.
        </p> 
    <?php  }else{ ?>
    	<h2>New Account</h2>        
        <p class="conf-pos">
        	Thank you for opening an account with Direct-Lens.com.
        </p> 
        <p class="conf-pos">
        	After your account has been approved you will receive an email from confirming your User name and password.
        </p> 
    <?php  } ?>
                     
    </div>                
</div> 

<?php include('inc/footer.php');?>