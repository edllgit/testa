<div id="footer">
       <div id="footer-top">


  			  
            <div class="box">
				<h2>Information</h2> 
                <ul>
                    <li><a href="http://directlab.ca/" target="_blank">See our company website at DirectLab</a></li>          
                </ul>            
            </div>  
            
             <div class="box">    
            </div>
            
            <div class="box">
				<h2><a name="/direct-lens/contact">Contact</a></h2> 
           		<div class="form">
	
	
    <form name="contactform" id="contactform" method="post" action="send_email.php">
  
     
    <?php if (isset($_REQUEST[err])){	?>
    
    <p class="conf-pos">
		 Please, Fill all the form.
    </p>
    
    <?php  } ?>  
    	
    <?php if (isset($_REQUEST[e])){	?>
    
    <p class="conf-pos">
		Thanks, your message has been sent.
    </p>

	<?php }else{ ?>
        
        <p>
        	<label for="contact_name">Name</label>                        
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
               <input class="submit" type="button" onClick="checkcontacten('contactform', this.name);" id="submitcontact" 
                value="Send" name="submitcontact" />
        </p>
        
    <?php  } ?>  
    
    </form>
</div> 

            </div>              
            <div class="clear"></div>                       
        </div>  
        <div id="footer-bottom">
        	<?php /*?><div class="box">&copy; Managed by <a href="http://directlab.ca" target="_blank">Directlab Network</a>.</div><?php */?>
           <div align="center"><p align="center"><a href="http://directlab.ca" target="_blank"><img src="http://www.direct-lens.com/direct-lens/images-design/logo_new.jpg" height="79"></a></p></div>
           <div class="clear"></div>                  
        </div>         
    </div> 	   
</div>  
</body>
</html>