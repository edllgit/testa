<?php include('inc/header.php');?>
 
<div id="content" class="page">
    <div id="content-text" class="full">
    
	<?php  if (($mylang == 'lang_french') && ($_REQUEST[err]=='accountnum')) {  ?>
    	<h2>Le numéro de compte entré est déja utilisé.</h2>        
        <p class="conf-neg">
        	SVP veuillez en essayer un autre. Merci.
        </p> 
    <?php  } ?>
    <?php  if (($mylang == 'lang_english') && ($_REQUEST[err]=='accountnum')) {  ?>
    	<h2>The Account Number That You Entered Is Already in Use</h2>        
        <p class="conf-neg">
        	Please try a different account number. Thanks.
        </p> 
    <?php  } ?>   
    
    
    <?php  if (($mylang == 'lang_french') && ($_REQUEST[err]=='userid')) {  ?>
    	<h2>Le nom d'utilisateur que vous avez choisi, est déjà utilisé.</h2>        
        <p class="conf-neg">
        	SVP veuillez en essayer un autre. Merci.
        </p> 
    <?php  } ?>
    <?php  if (($mylang == 'lang_english') && ($_REQUEST[err]=='userid')) {  ?>
    	<h2>The User Name You Entered is in Use</h2>        
        <p class="conf-neg">
        	Please try a different login. Thanks.
        </p> 
    <?php  } ?> 
    </div>                
</div> 

<?php include('inc/footer.php');?>