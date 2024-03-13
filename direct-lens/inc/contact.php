<div class="form">
	
	<?php  if ($mylang == 'lang_french') {  ?>
            <form name="formulairecontact" id="formulairecontact" method="post" action="send_email.php">
    <?php  }else{ ?>
            <form name="contactform" id="contactform" method="post" action="send_email.php">
    <?php  } ?>      
     
    <?php if (isset($_REQUEST[err])){	?>
    
    <p class="conf-pos">
		<?php echo ($mylang == "lang_french") ? "Veuillez remplir le formulaire au COMPLET": "Please, Fill all the form."; ?>
    </p>
    
    <?php  } ?>  
    	
    <?php if (isset($_REQUEST[e])){	?>
    
    <p class="conf-pos">
		<?php echo ($mylang == "lang_french") ? "Merci, votre message a &eacute;t&eacute; envoy&eacute;.": "Thanks, your message has been sent."; ?>
    </p>

	<?php }else{ ?>
        
        <p>
        	<label for="contact_name"><?php echo ($mylang == "lang_french") ? "Nom": "Name"; ?></label>                        
            <input name="contact_name" id="contact_name" type="text" />
        </p>
        <p>
            <label for="contact_email">Email</label>
            <input name="contact_email" id="contact_email" type="text" />
        </p>
        <p>
            <label for="contact_message">Message</label>
            <textarea name="contact_message" cols="20" rows="3"></textarea>
        </p>	                      	                    	
      
        <p>
			<?php  if ($mylang == 'lang_french'){ ?>
            	<input class="submit" type="button" onClick="checkcontactfr('formulairecontact', this.name);" id="submitcontact" 
                value="<?php echo ($mylang == "lang_french") ? "Envoyer": "Send"; ?>" name="submitcontact" />
            <?php }else{ ?>
            	<input class="submit" type="button" onClick="checkcontacten('contactform', this.name);" id="submitcontact" 
                value="<?php echo ($mylang == "lang_french") ? "Envoyer": "Send"; ?>" name="submitcontact" />
            <?php }  ?>
        </p>
        
    <?php  } ?>  
    
    </form>
</div> 
