<?php 
include('inc/header.php');
include('../Connections/sec_connect.inc.php');
?>    
<div id="content" class="page">
    <div id="content-text" class="full">
       
		<h2><?php echo ($mylang == "lang_french") ? "Cr&eacute;er un compte": "Create Account"; ?></h2>     
    
    <?php  if ($mylang == 'lang_french') {  ?>
            <form name="nouveaucompte" id="nouveaucompte" action="newaccountnotify.php" method="post">
    <?php  }else{ ?>
            <form name="newaccount" id="newaccount" action="newaccountnotify.php" method="post">
    <?php  } ?>   
            
        <div id="create-account" class="form">
           
            <h3><?php echo ($mylang == "lang_french") ? "Information de connexion": "Login Information"; ?></h3>
			<div class="box"> 
                <p>
                <label for="user_id">* <?php echo ($mylang == "lang_french") ? "Nom d'utilisateur": "Username"; ?></label>
                <input name="user_id" id="user_id" type="text" />
                 <span id="status"></span>
                </p>                       
                <p>
                <label for="password">* <?php echo ($mylang == "lang_french") ? "Mot de passe": "Password"; ?></label>
                <input name="password" id="password" type="text" />
              	</p>                       
          	</div>	
			<div class="box">  
                <p>
                <label for="email">* Email</label>
                <input name="email" id="email" type="text" />
                </p>                      
                <p>
                <label for="password_confirmation">* <?php echo ($mylang == "lang_french") ? "Confirmation": "Password Confirmation"; ?></label>
                <input name="password_confirmation" id="password_confirmation" type="text" />
              	</p>               
          	</div>	
            <div class="clear"></div>
           
            <div class="hr"><hr /></div>          
            
            <h3><?php echo ($mylang == "lang_french") ? "Informations Personnelles": "Personal information"; ?></h3>
			<div class="box"> 
                <p>
                <label for="title">* <?php echo ($mylang == "lang_french") ? "Titre": "Title"; ?></label>
                <select name="title" id="title">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value="Dr.">Dr.</option>
                    <option value="Mr.">Mr.</option>
                    <option value="<?php echo ($mylang == "lang_french") ? "Mlle": "Ms."; ?>"><?php echo ($mylang == "lang_french") ? "Mlle": "Ms."; ?></option>
                    <option value="<?php echo ($mylang == "lang_french") ? "Mme": "Mrs."; ?>"><?php echo ($mylang == "lang_french") ? "Mme": "Mrs."; ?></option>
                </select>
                </p>                       
                <p>
                <label for="first_name">* <?php echo ($mylang == "lang_french") ? "Pr&eacute;nom": "First name "; ?></label>
                <input name="first_name" id="first_name" type="text" />
              	</p>
              	<p>
                <label for="company">* <?php echo ($mylang == "lang_french") ? "Compagnie": "Company"; ?></label>
                <input name="company" id="company" type="text" />
                </p>
                <p>
                <label for="phone">* <?php echo ($mylang == "lang_french") ? "T&eacute;l&eacute;phone": "Phone"; ?></label>
                <input name="phone" id="phone" type="text" />
                </p>
                <p>
                <label for="fax">Fax</label>
                <input name="fax" id="fax" type="text" />
                </p>      
                <p>
                <label for="currency">* <?php echo ($mylang == "lang_french") ? "Devise": "Currency"; ?></label>
                <select name="currency" id="currency">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                <?php  if ($mylang=='lang_french'){ ?>
                     <option value="US">US Dollar Am&eacute;ricain</option>
                     <option value="CA">CND Dollar Canadien</option>
                     <option value="EU">EU Euro</option>                     
                <?php  }else{ ?>
                     <option value="US">US American Dollar</option>
                     <option value="CA">CND Canadian Dollar</option>
                     <option value="EU">EU Euro</option>
                <?php  } ?> 
                </select>
                </p> 
                <p>
                <label for="business_type">* <?php echo ($mylang == "lang_french") ? "Type d'entreprise": "Business Type"; ?></label>
                <select id="business_type" name="business_type">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value="Optometrist Office"><?php echo ($mylang == "lang_french") ? "Bureau d'optom&eacute;triste": "Optometrist Office"; ?></option>
                    <option value="Optician Office"><?php echo ($mylang == "lang_french") ? "Bureau d'opticien": "Optician Office"; ?></option>
                    <option value="Lab">Lab</option>
                </select>
                </p> 
                <p>
                <label for="purchase_unit">* <?php echo ($mylang == "lang_french") ? "Unit&eacute;s de commande": "Purchase Unit"; ?></label>
                <select name="purchase_unit" id="purchase_unit">
                	<option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value="pair"><?php echo ($mylang == "lang_french") ? "Paire": "Pair"; ?></option>
                    <option value="single"><?php echo ($mylang == "lang_french") ? "Individuelle": "Single"; ?></option>
                </select>
                </p>                                                               
          	</div>	
			<div class="box">  
                <p>
                <label for="account_num"><?php echo ($mylang == "lang_french") ? "Num&eacute;ro de compte": "Account Number"; ?></label>
                <input name="account_num" id="account_num" type="text" />
                </p>                      
                <p>
                <label for="last_name">* <?php echo ($mylang == "lang_french") ? "Nom de famille": "Last Name"; ?></label>
                <input name="last_name" id="last_name" type="text" />
              </p>
                <p>
                <label for="buying_group"><?php echo ($mylang == "lang_french") ? "Centrale d'achat": "Buying Group"; ?></label>
                <select name="buying_group" id="buying_group">
                    <?php
                    $query="select primary_key, display_dropdown, bg_name from buying_groups WHERE display_dropdown = 'yes' order by bg_name";
                    $result=mysql_query($query)		or die ("Could not find bg list");
                    while ($bgList=mysql_fetch_array($result)){
                    //if($bgList[primary_key]!=1)
                        echo "<option value=\"$bgList[primary_key]\"";
                        echo ">$bgList[bg_name]</option>";
                    }?>
                </select>
                </p>
                <p>
                <label for="other_phone"><?php echo ($mylang == "lang_french") ? "Autre t&eacute;l&eacute;phone": "Other Phone"; ?></label>
                <input name="other_phone" id="other_phone" type="text" />
                </p>
                <p>
                <label for="vat_no"><?php echo ($mylang == "lang_french") ? "Num&eacute;ro VAT": "VAT Number"; ?></label>
                <input name="vat_no" id="vat_no" type="text" />
                </p>      
                <p>
                <label for="language">* <?php echo ($mylang == "lang_french") ? "Langue": "Language"; ?></label>
                <select name="language" id="language">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value="English">English</option>
                    <option value="Francais">Francais</option>
                </select>  
                </p>  
                <p>
                <label for="main_lab">* <?php echo ($mylang == "lang_french") ? "Verrier principal": "Main lab"; ?></label>
                <select id="main_lab" name="main_lab">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <?php
                    $query="select primary_key,display_dropdown, lab_name from labs WHERE display_dropdown = 'yes' order by lab_name ";
                    $result=mysql_query($query)		or die ("Could not find lab list");
                    while ($labList=mysql_fetch_array($result)){
                    echo "<option value=\"$labList[primary_key]\"";
                    if ($labList['primary_key']== $_SESSION[newaccount][main_lab])
                    echo ' Selected';
                    echo ">$labList[lab_name]</option>";
                    }?>
                    
                </select>
                </p> 
                <p>
                <label for="findus"><?php echo ($mylang == "lang_french") ? "Vous nous avez trouv&eacute; o&ugrave;?": "How did you find us?"; ?></label>
                <select name="findus" id="findus">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value="trade">&nbsp;&nbsp;&nbsp;&nbsp;Trade show</option>
                    <option value="rep">&nbsp;&nbsp;&nbsp;&nbsp;Sales rep</option>
                    <option value="other">&nbsp;&nbsp;&nbsp;&nbsp;Other</option>
                    <option value="" disabled="disabled">Magazine</option>
                    <option value="optik">&nbsp;&nbsp;&nbsp;&nbsp;Optik</option>
                    <option value="larevue">&nbsp;&nbsp;&nbsp;&nbsp;La Revue</option>
                    <option value="vision">&nbsp;&nbsp;&nbsp;&nbsp;Vision</option>
                    <option value="" disabled="disabled">Web</option>
                    <option value="infoclip">&nbsp;&nbsp;&nbsp;&nbsp;InfoClip.ca</option>
                    <option value="optiguide">&nbsp;&nbsp;&nbsp;&nbsp;Opti-Guide.com</option>
                    <option value="" disabled="disabled">eBulletin</option>
                    <option value="pointclip">&nbsp;&nbsp;&nbsp;&nbsp;Capsule Point Clip</option>
                    <option value="optinews">&nbsp;&nbsp;&nbsp;&nbsp;Opti-news</option>
                </select>      
                </p>                                                           
          	</div>	
            <div class="clear"></div>
            
            <div class="hr"><hr /></div>   
            
            <h3><?php echo ($mylang == "lang_french") ? "Adresse de facturation": "Billing Address"; ?></h3>
			<div class="box"> 
                <p>
                <label for="bill_address1">* <?php echo ($mylang == "lang_french") ? "Addresse 1": "Address 1"; ?></label>
                <input name="bill_address1" id="bill_address1" type="text" />
                </p>                       
                <p>
                <label for="bill_city">* <?php echo ($mylang == "lang_french") ? "Ville": "City"; ?></label>
                <input name="bill_city" id="bill_city" type="text" />
              	</p>    
                <p>
                <label for="bill_zip">* <?php echo ($mylang == "lang_french") ? "Code Postal": "Postal Code"; ?></label>
                <input name="bill_zip" id="bill_zip" type="text" />
              	</p>                                      
          	</div>	
			<div class="box">  
                <p>
                <label for="bill_address2"><?php echo ($mylang == "lang_french") ? "Addresse 2": "Address 2"; ?></label>
                <input name="bill_address2" id="bill_address2" type="text" />
                </p>                      
                <p>
                <label for="bill_state">* <?php echo ($mylang == "lang_french") ? "&Eacute;tat/Province": "State/Province"; ?></label>
                <input name="bill_state" id="bill_state" type="text" />
              	</p>    
                <p>
                <label for="bill_country">* <?php echo ($mylang == "lang_french") ? "Pays": "Country"; ?></label>
                <select name = "bill_country" id="bill_country">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value ="US"><?php echo ($mylang == "lang_french") ? "&Eacute;tats-Unis": "United States"; ?></option>
                    <option value ="CA">Canada</option>
                    <option value ="FR">France</option>
                    <option value ="IT"><?php echo ($mylang == "lang_french") ? "Italie": "Italy"; ?></option>
                    <option value="" disabled="disabled">-----<?php echo ($mylang == "lang_french") ? "Autres": "Others"; ?>-----</option>
                    <option value ="BE">Benin</option>
                    <option value ="BF">Burkina Faso</option>                    
                    <option value ="CAM">Cameroun</option>
                    <option value ="CR"><?php echo ($mylang == "lang_french") ? "Cara&iuml;be": "Caribbean"; ?></option>
                    <option value ="RDC">Congo</option>
                    <option value ="CB">Congo-Brazzaville</option>
                    <option value ="CI"><?php echo ($mylang == "lang_french") ? "C&ocirc;te d'Ivoire": "Ivory Coast"; ?></option> 
                    <option value ="GA">Gabon</option>                    
                    <option value ="MA">Mali</option>
                    <option value ="SE"><?php echo ($mylang == "lang_french") ? "S&eacute;n&eacute;gal": "Senegal"; ?></option>
                    <option value ="TO">Togo</option>                       
               	</select>
              	</p>                           
          	</div>	
            <div class="clear"></div> 
            
            <div class="hr"><hr /></div>
            
            <h3><?php echo ($mylang == "lang_french") ? "Adresse d'exp&eacute;dition": "Shipping Address"; ?></h3>           
            
			<?php  if ($mylang == 'lang_french') {  ?>
            	<h4>Ne pas remplir cette section si l'adresse de facturation est identique</h4>
            <?php  }else{ ?>
           		<h4>Do not fill this section if the billing address is the same</h4>
            <?php  } ?>              
            
			<div class="box"> 
                <p>
                <label for="ship_address1"><?php echo ($mylang == "lang_french") ? "Addresse 1": "Address 1"; ?></label>
                <input name="ship_adress1" id="ship_address1" type="text" />
                </p>                       
                <p>
                <label for="ship_city"><?php echo ($mylang == "lang_french") ? "Ville": "City"; ?></label>
                <input name="ship_city" id="ship_city" type="text" />
              	</p>    
                <p>
                <label for="ship_zip"><?php echo ($mylang == "lang_french") ? "Code Postal": "Postal Code"; ?></label>
                <input name="ship_zip" id="ship_zip" type="text" />
              	</p>                                      
          	</div>	
			<div class="box">  
                <p>
                <label for="ship_address2"><?php echo ($mylang == "lang_french") ? "Addresse 2": "Address 2"; ?></label>
                <input name="ship_address2" id="ship_address2" type="text" />
                </p>                      
                <p>
                <label for="ship_state"><?php echo ($mylang == "lang_french") ? "&Eacute;tat/Province": "State/Province"; ?></label>
                <input name="ship_state" id="ship_state" type="text" />
              	</p>    
                <p>
                <label for="ship_country"><?php echo ($mylang == "lang_french") ? "Pays": "Country"; ?></label>
                <select name ="ship_country" id="ship_country">
                    <option value="">- <?php echo ($mylang == "lang_french") ? "Faites une s&eacute;lection": "Make a selection"; ?> -</option>
                    <option value ="US"><?php echo ($mylang == "lang_french") ? "&Eacute;tats-Unis": "United States"; ?></option>
                    <option value ="CA">Canada</option>
                    <option value ="FR">France</option>
                    <option value ="IT"><?php echo ($mylang == "lang_french") ? "Italie": "Italy"; ?></option>
                    <option value="" disabled="disabled">-----<?php echo ($mylang == "lang_french") ? "Autres": "Others"; ?>-----</option>
                    <option value ="BE">Benin</option>
                    <option value ="BF">Burkina Faso</option>                    
                    <option value ="CAM">Cameroun</option>
                    <option value ="CR"><?php echo ($mylang == "lang_french") ? "Cara&iuml;be": "Caribbean"; ?></option>
                    <option value ="RDC">Congo</option>
                    <option value ="CB">Congo-Brazzaville</option>
                    <option value ="CI"><?php echo ($mylang == "lang_french") ? "C&ocirc;te d'Ivoire": "Ivory Coast"; ?></option> 
                    <option value ="GA">Gabon</option>                    
                    <option value ="MA">Mali</option>
                    <option value ="SE"><?php echo ($mylang == "lang_french") ? "S&eacute;n&eacute;gal": "Senegal"; ?></option>
                    <option value ="TO">Togo</option>       
                </select>
              	</p>                           
          	</div>	
            
            
            
            <div class="clear"></div> 
            <div class="hr"><hr /></div>  
            
            
            
            <div class="box">  
                <p>
               <label for="captcha_code"><?php echo ($mylang == "lang_french") ? "TAPER CE TEXTE": "TYPE THIS TEXT"; ?></label>
               <input type="text" name="captcha_code" size="10" maxlength="6" id="captcha_code" />
                </p>
                <p>
                <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">
      <?php if ($mylang == 'lang_french'){
				echo '[Changer&nbsp;d\'image]';
				}else {
				echo '[Change&nbsp;Image]';
				}
				?>
                </a>
                </p>
                <p>
                <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
              	</p>                           
          	</div>	
            
            
            
            
            
            
            
          
            <div class="clear"></div> 
            <div class="hr"><hr /></div>                                             

	<?php  if ($mylang == 'lang_french') {  ?>
            <input class="submit" value="Envoyer" name="Btnnouveaucompte" type="button" onClick="checkfr('nouveaucompte', this.name);" />
    <?php  }else{ ?>
            <input class="submit" value="Create"  name="Btnnewaccount" type="button" onClick="checken('newaccount', this.name);" />
    <?php  } ?>              
            
        </div> 
        </form>
    </div>                
</div> 

<?php include('inc/footer.php');?>