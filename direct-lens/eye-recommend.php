<?php include('inc/header-den.php');?>
    
   
<div id="content">
    <div id="content-text">
             <h2>Welcome Eye Recommend Members</h2>
            <p><img src="http://www.direct-lens.com/direct-lens/images-design/ER_logo.jpg" width="620"  /><h3>Would you like to create an account?&nbsp;&nbsp;&nbsp;<a href="/direct-lens/create-account-den.php">Click Here</a></h3></p>
            <h4 align="center">Otherwise login with your Username and Password:</h4> 
                
    </div> 
    <div id="side-bar">    
   	
          
        <h2><?php echo 'CUSTOMER LOGIN' ?></h2>       
      <div class="form connexion">
	
    <h3><?php echo "Connect here"; ?></h3>
	<form method="post" name="connectform" id="connectform" action="dllogin.php">        
        <p>
            <label for="username">User Name:</label>
            <input name="username" id="username" type="text" />
        </p>
        <p>
            <label for="password">Password:</label>
            <input name="password"  id="password" type="password" />
        </p>	
        
        <p>
        	<input type="button" onClick="checkconnexionen('connectform', this.name);" name="connexion" id="connexion" value="Connect" class="submit" />
        </p>
    </form>   
    <br/>
    	<p>You do not have a Direct-Lens account yet? <a href="create-account-den.php"><b>Create an account now</b></a></p>      
</div>
    </div>  
    <div class="clear"></div>                  
</div> 



<div id="middle-nav">
    <div class="box">
    <h2>Our Offer</h2>
        <p>Fill in the prescription form and choose a lens from the available results. Add to cart and have it shipped 
        right to your office.</p> 
    </div>  
    <div class="box">
            <h2>Exclusive Coatings</h2>
            <p>Try Maxivue: 99.8% clear</p>   
    </div> 
    <div class="box">
            <h2>Unique Products</h2>
            <p>Try the Revolution casted 1.59 index - distortion free polycarbonate.</p>
    </div>
    
    <div>
		<h4 align="left">Customer Service: 1-855-770-2124</h4>
	</div>
    
    <div class="clear"></div>                         
</div>  


<?php include('inc/footer-den.php');?>