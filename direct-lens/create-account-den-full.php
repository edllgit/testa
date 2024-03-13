<?php 
include('inc/header-den.php');
include('../Connections/sec_connect.inc.php');
?>    
<div id="content" class="page">
    <div id="content-text" class="full">
       
		<h2>Create Prestige Account</h2>     
    
      	   <form name="newaccount" id="newaccount" action="newaccountnotify-den.php" method="post">
           <div id="create-account" class="form">
           
            <h3>Login Information</h3>
			<div class="box"> 
                <p>
                <label for="user_id">* Username</label>
                <input name="user_id" id="user_id" type="text" />
                <span id="status"></span>
                </p>                       
                <p>
                <label for="password">* Password</label>
                <input name="password" id="password" type="text" />
              	</p>                       
          	</div>	
			<div class="box">  
                <p>
                <label for="email">* Email</label>
                <input name="email" id="email" type="text" />
                </p>                      
                <p>
                <label for="password_confirmation">* Password Confirmation</label>
                <input name="password_confirmation" id="password_confirmation" type="text" />
              	</p>               
          	</div>	
            <div class="clear"></div>
           
            <div class="hr"><hr /></div>          
            
            <h3>Personal information</h3>
			<div class="box"> 
                <p>
                <label for="title">* Title</label>
                <select name="title" id="title">
                    <option value="">- Make a selection-</option>
                    <option value="Dr.">Dr.</option>
                    <option value="Mr.">Mr.</option>
                    <option value="Ms">Ms.</option>
                    <option value="Mrs.">Mrs.</option>
                </select>
                </p>                       
                <p>
                <label for="first_name">* First name</label>
                <input name="first_name" id="first_name" type="text" />
              	</p>
              	<p>
                <label for="company">* Company</label>
                <input name="company" id="company" type="text" />
                </p>
                <p>
                <label for="phone">* Phone</label>
                <input name="phone" id="phone" type="text" />
                </p>
                <p>
                <label for="fax">Fax</label>
                <input name="fax" id="fax" type="text" />
                </p>      
               
               <input type="hidden" name="currency" id="currency" value="CA">

                <p>
                <label for="business_type">* Business Type</label>
                <select id="business_type" name="business_type">
                    <option value="">- Make a selection-</option>
                    <option value="Optometrist Office">Optometrist Office</option>
                    <option value="Optician Office">Optician Office</option>
                    <option value="Lab">Lab</option>
                </select>
                </p> 
                <p>
                <label for="purchase_unit">* Purchase Unit</label>
                <select name="purchase_unit" id="purchase_unit">
                	<option value="">- Make a selection -</option>
                    <option value="pair">Pair</option>
                    <option value="single">Single</option>
                </select>
                </p>                                                               
          	</div>	
			<div class="box">  
                <p>
                <label for="account_num">Account Number</label>
                <input name="account_num" id="account_num" type="text" />
                </p>                      
                <p>
                <label for="last_name">* Last Name</label>
                <input name="last_name" id="last_name" type="text" />
              </p>
                
               
               <input type="hidden" name="buying_group" id="buying_group" value="14"> 
               
                <p>
                <label for="other_phone">Other Phone</label>
                <input name="other_phone" id="other_phone" type="text" />
                </p>
                    
                <p>
                <label for="language">* Language</label>
                <select name="language" id="language">
                    <option value="">- Make a selection -</option>
                    <option value="English">English</option>
                </select>  
                </p>  
                <p>
                <label for="main_lab">* Main lab</label>
                <select id="main_lab" name="main_lab">
                    <option value="">- Make a selection -</option>
                    <option value="3">- Directlab Saint-Catharines -</option>
                </select>
                </p> 
                                                                      
          	</div>	
            <div class="clear"></div>
            
            <div class="hr"><hr /></div>   
            
            <h3>Billing Address</h3>
			<div class="box"> 
                <p>
                <label for="bill_address1">* Address 1</label>
                <input name="bill_address1" id="bill_address1" type="text" />
                </p>                       
                <p>
                <label for="bill_city">* City</label>
                <input name="bill_city" id="bill_city" type="text" />
              	</p>    
                <p>
                <label for="bill_zip">* Postal Code</label>
                <input name="bill_zip" id="bill_zip" type="text" />
              	</p>                                      
          	</div>	
			<div class="box">  
                <p>
                <label for="bill_address2">Address 2</label>
                <input name="bill_address2" id="bill_address2" type="text" />
                </p>                      
                <p>
                <label for="bill_state">* State/Province</label>
                <input name="bill_state" id="bill_state" type="text" />
              	</p>    
                <p>
                <label for="bill_country">* Country</label>
                <select name = "bill_country" id="bill_country">
                <option value="">- Make a selection -</option>
                    <option value ="CA">Canada</option>             
               	</select>
              	</p>                           
          	</div>	
            <div class="clear"></div> 
            <div class="hr"><hr /></div>
            
            <h3>Shipping Address</h3>           
           	<h4>Do not fill this section if the billing address is the same</h4>
                    
            
			<div class="box"> 
                <p>
                <label for="ship_address1">Address 1</label>
                <input name="ship_adress1" id="ship_address1" type="text" />
                </p>                       
                <p>
                <label for="ship_city">City</label>
                <input name="ship_city" id="ship_city" type="text" />
              	</p>    
                <p>
                <label for="ship_zip">Postal Code</label>
                <input name="ship_zip" id="ship_zip" type="text" />
              	</p>                                      
          	</div>	
			<div class="box">  
                <p>
                <label for="ship_address2">Address 2</label>
                <input name="ship_address2" id="ship_address2" type="text" />
                </p>                      
                <p>
                <label for="ship_state">State/Province</label>
                <input name="ship_state" id="ship_state" type="text" />
              	</p>    
                <p>
                <label for="ship_country">Country</label>
                <select name ="ship_country" id="ship_country">
                    <option value="">- Make a selection-</option>
                    <option value ="CA">Canada</option>
                </select>
              	</p>                           
          	</div>	
          
            <div class="clear"></div> 
            <div class="hr"><hr /></div>                                             	
            <input class="submit" value="Create"  name="Btnnewaccount" type="button" onClick="checken('newaccount', this.name);" />      
        </div> 
        </form>
    </div>                
</div> 

<?php include('inc/footer.php');?>