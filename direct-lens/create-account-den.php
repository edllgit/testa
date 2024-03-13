<?php 
include('inc/header-den.php');
include('../Connections/sec_connect.inc.php');

//Objectif: envoyer par courriel le contenu tapé dans ce formulaire

?>    
<div id="content" class="page">
    <div id="content-text" class="full">
       
		<h2>Create Account</h2>     
    
   
       <form name="newaccount" id="newaccount" action="send_email_den.php" method="post">
  
            
           <input type="hidden" name="buying_group" id="buying_group" value="14">   
           <input type="hidden" name="currency"     id="currency" value="CA">   
            
        <div id="create-account" class="form">
           
            <h3>Login Information</h3>
			<div> 
            
            
            	<p>
                <label for="title">* Title</label>
                <input name="title" id="title" type="text"  align="right" value="Dr." />  
                </p>
                
            	<p>
                <label for="first_name">* First Name</label>
                <input name="first_name" id="first_name" type="text"  align="right" value="" />  
                </p> 
                
                <p>
                <label for="last_name">* Last Name</label>
                <input name="last_name" id="last_name" type="text"  align="right" value="" />  
                </p> 
                
                <p>
                <label for="business_name">* Business Name</label>
                <input name="business_name" id="business_name" type="text"  align="right" value="" />  
                </p> 
                
                <p>
                <label for="email">* Email</label>
                <input name="email" id="email" type="text" />
                </p>
            
           	    <p>
                <label for="den_account_num">* Eye Recommend #</label>
                <input name="den_account_num" id="den_account_num" type="text"  align="right" />  (Example A001)
                </p> 
                
                 <p>
                <label for="purchase_unit">* Purchase Unit</label>
                 <select name="purchase_unit" id="purchase_unit">
                	<option value="">- Make a selection -</option>
                    <option value="pair">Pair</option>
                    <option value="single">Single</option>
                </select>
                </p> 
                
                
               <p>
                <label for="username">* Username</label>
                <input name="username" id="username" type="text" /> (You could use your Eye Recommend Account Number. <br>If you have multiples locations,  you could add  the address of your location  Example A001-5170)
                <span id="status"></span>
                </p>                         
                <p>
                <label for="password">* Password</label>
                <input name="password" id="password" type="text" />
              	</p>                 
                <p>
                <label for="password_confirmation">* Password Confirmation</label>
                <input name="password_confirmation" id="password_confirmation" type="text" />
              	</p>  
                
                  <p>
                <label for="edging_equipment">* Do you have your own edging equipment ?</label>
                <select name="edging_equipment" id="edging_equipment">
                	<option value="">- Make a selection -</option>
                    <option value="no">No</option>
                    <option value="yes">Yes</option>
                </select>
              	</p>      
          	</div>	
	
            <div class="clear"></div>
      
            <div class="hr"><hr /></div>
                                                

	
              <div align="center"><input  value="Create"  name="Btnnewaccount" type="submit"  /></div>
         
            
        </div> 
        </form>
    </div>                
</div> 

<?php include('inc/footer-den.php');?>