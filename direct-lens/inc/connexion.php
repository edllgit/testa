<div class="form connexion">
	
    <h3><?php echo ($mylang == "lang_french") ? "Se connecter ici": "Connect here"; ?></h3>
	<form method="post" name="connectform" id="connectform" action="dllogin.php" autocomplete="off">        
        <p>
            <label for="username"><?php echo ($mylang == "lang_french") ? "Nom d'usager ": "User Name"; ?>:</label>
            <input name="username" id="username" type="text" />
        </p>
        <p>
            <label for="password"><?php echo ($mylang == "lang_french") ? "Mot de passe ": "Password"; ?>:</label>
            <input name="password"  id="password" type="password" />
        </p>	
        
        <p>
         <?php  if ($mylang == 'lang_french'){?>
                <input type="button" onClick="checkconnexionfr('connectform', this.name);" name="connexion" id="connexion" value="se connecter"  class="submit" />
           <?php }else{ ?>
                <input type="button" onClick="checkconnexionen('connectform', this.name);" name="connexion" id="connexion" value="Connect" class="submit" />
           <?php }  ?>
        </p>
    </form>   
    <br/><br/>
	<?php  if ($mylang == 'lang_french') {  ?>
        <p>Votre compte <a href="/direct-lens/forgot_password.php">n'est pas accessible ou vous avez oubli&eacute; votre mot de passe ?</a></p>
        
    <?php  }else{ ?>
    	<p>Your account is <a href="/direct-lens/forgot_password.php">not accessible or you forgot your password</a>?</p>
    	
    <?php  } ?>      
</div>