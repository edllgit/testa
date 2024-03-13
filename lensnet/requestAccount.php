<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
global $drawme;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {color: #FFFFFF}
-->
</style>

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Title');
  errors += checkText(formname, 'first_name', 'First Name');
  errors += checkText(formname, 'last_name', 'Last Name');
  errors += checkText(formname, 'bill_address1', 'Billing Address 1');
  errors += checkText(formname, 'bill_city', 'Billing City');
  errors += checkSelect(formname, 'bill_state', 'Billing State/Province');
  errors += checkText(formname, 'bill_zip', 'Billing Zip/Postal Code');
  errors += checkSelect(formname, 'bill_country', 'Billing Country');
  errors += checkText(formname, 'phone', 'Phone');
  errors += checkEmail(formname, 'email', 'Email');
  errors += checkText(formname, 'user_id', 'Login');
  errors += checkText(formname, 'password', 'Password');
  errors += checkText(formname, 'pw_confirm', 'Confirm Password');
  errors += checkBox(formname, 'terms', 'I agree to the terms of Lens Net Club');
  errors += checkPW(formname, 'password', 'pw_confirm', 'Password Fields');
  errors += checkSelect(formname, 'language', 'Language');
  errors += checkText(formname, 'captcha_code', 'Validation code');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>



<script type="text/javascript">
    window.onload = function() {
    document.getElementById("user_id").onblur = function() {
    var xmlhttp;
    var user_id=document.getElementById("user_id");
    if (user_id.value != "")
    {
    if (window.XMLHttpRequest)
      {
      xmlhttp=new XMLHttpRequest();
      }
    else
      {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function()
      {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("status").innerHTML=xmlhttp.responseText;
        }
      };
    xmlhttp.open("POST","getUserid.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("user_id="+encodeURIComponent(user_id.value));
    document.getElementById("status").innerHTML="<br>Vérification / Verifying...";
    }
    };
    };
    </script>
</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><div class="bigwelcome"><?php echo $lbl_titlemast_oa;?></div>
            <form action="newAccountNotify.php" method="post" name="accountForm" id="accountForm">
            
			<br>To open an account, please contact us at 1-855-770-2124
          <?php /*<table border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formText"><div style="width:770px; text-align:center;"><?php echo $lbl_submast;?>&nbsp;</div></td>
              </tr>
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_titletxt;?></div></td>
              <td align="left" class="formCellNosides"><select name="title" id="title">
                <option value=""><?php echo $lbl_selectxt;?></option>
                <option <?php if ($_SESSION[newAccountLensnet][title] == $lbl_selectxt1) echo ' Selected';  ?> value="<?php echo $lbl_selectxt1;?>"><?php echo $lbl_selectxt1;?></option>
                <option <?php if ($_SESSION[newAccountLensnet][title] == $lbl_selectxt2) echo ' Selected';  ?> value="<?php echo $lbl_selectxt2;?>"><?php echo $lbl_selectxt2;?></option>
                <option <?php if ($_SESSION[newAccountLensnet][title] == $lbl_selectxt3) echo ' Selected';  ?> value="<?php echo $lbl_selectxt3;?>"><?php echo $lbl_selectxt3;?></option>
                <option <?php if ($_SESSION[newAccountLensnet][title] == $lbl_selectxt4) echo ' Selected';  ?> value="<?php echo $lbl_selectxt4;?>"><?php echo $lbl_selectxt4;?></option>
              </select></td>
              <td align="left" nowrap class="formCellNosides">&nbsp;</td>
              <td align="left" class="formCellNosides">&nbsp;</td>
            </tr>
			
			
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_fname_txt;?> 
              </div></td>
              <td align="left" class="formCellNosides"><input name="first_name" type="text"  value="<?php echo  $_SESSION[newAccountLensnet][first_name];?>"  id="first_name" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_lname_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="last_name" value="<?php echo  $_SESSION[newAccountLensnet][last_name];?>" type="text" id="last_name" size="20"></td>
              </tr>
            <tr>
              
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_comp_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="company" type="text" value="<?php echo  $_SESSION[newAccountLensnet][company];?>" id="company" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_buygrp_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><select name="buying_group" class="formField">
                <option value="1" selected="selected"><?php echo $lbl_buygrp_txt1;?></option>
                <?php
	$query="SELECT primary_key, display_dropdown, bg_name FROM buying_groups WHERE display_dropdown = 'yes' and primary_key NOT IN (6,8,9,10,11,12,13,14,15,16,17) order by bg_name";
	$result=mysqli_query($con,$query)
		or die ("Could not find bg list");
	while ($bgList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		if($bgList[primary_key]!=1)
			echo "<option value=\"$bgList[primary_key]\">$bgList[bg_name]</option>";
}
?>
                </select>
                </td>
              </tr>
			  
			   <tr>
              
              <td align="left"  class="formCellNosides"><div align="right">
               &nbsp;
                </div></td>
              <td align="left" class="formCellNosides">&nbsp;</td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php if ($mylang == 'lang_french'){
				echo 'No de compte';
				}else {
				echo 'Account Number';
				}
				?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="account_no" value="<?php echo  $_SESSION[newAccountLensnet][account_no];?>" type="text" id="account_no" size="6">
			  </td>
              </tr>
			  
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_phonetxt1;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" value="<?php echo  $_SESSION[newAccountLensnet][phone];?>" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_phonetxt2;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="other_phone" value="<?php echo  $_SESSION[newAccountLensnet][other_phone];?>" type="text" id="other_phone" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_faxtxt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="fax" type="text" value="<?php echo  $_SESSION[newAccountLensnet][fax];?>" id="fax" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php //echo $lbl_vatnumtxt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="VAT_no" type="hidden" id="VAT_no" value=""></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_emailtxt;?>
                </div></td>
              <td colspan="3" align="left" class="formCellNosides"><input name="email" value="<?php echo  $_SESSION[newAccountLensnet][email];?>" type="text" id="email" size="40">
                <?php echo $lbl_langtxt;?>
                
                <select name="language" id="language">
                  <option value=""><?php echo $lbl_langtxt1;?></option>
                  <option  <?php if ($_SESSION[newAccountLensnet][language] == $lbl_langtxt2) echo ' Selected';  ?> value="<?php echo $lbl_langtxt2;?>"><?php echo $lbl_langtxt2;?></option>
                  <option  <?php if ($_SESSION[newAccountLensnet][language] == $lbl_langtxt3) echo ' Selected';  ?> value="<?php echo $lbl_langtxt3;?>"><?php echo $lbl_langtxt3;?></option>
                  </select>    						
                </td>
              
  
              
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_busstype_txt;?>
                </div></td>
              <td align="left" class="formCellNosides">
              <select name="business_type">
                  <option <?php if ($_SESSION[newaccount][business_type] == 'Optometrist Office') echo ' Selected';  ?> value="Optometrist Office"><?php echo $lbl_busstype_txt1;?></option>
                  <option <?php if ($_SESSION[newaccount][business_type] == 'Optician Office')    echo ' Selected';  ?> value="Optician Office"><?php echo $lbl_busstype_txt2;?></option>
                  <option <?php if ($_SESSION[newaccount][business_type] == 'Lab')                echo ' Selected';  ?> value="Lab"><?php echo $lbl_busstype_txt3;?></option>
              </select></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_mlprf_txt;?>
                </div></td>
              <td align="left" class="formCellNosides">

       <select name="main_lab" class="formField">
              <option value="29">Lensnet Club On</option>
              <option value="28">Lensnet Club Qc</option>   
              <option value="33">Lensnet Club Atlantic</option>
              <option value="34">Lensnet Club West</option>
              <option value="44">Lensnet Club Pacific</option>
      </select></td>
            


              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_currency_txt;?>
                </div></td>
              <td align="left" class="formCellNosides">
                <select name="currency" id="currency">
                    <option value="<?php echo $lbl_currency_abr_txt1;?>"><?php echo $lbl_currency_txt1;?></option>
                    <option value="<?php echo $lbl_currency_abr_txt2;?>"><?php echo $lbl_currency_txt2;?></option>
                </select>
                </td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_orderunits_txt;?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="purchase_unit" type="radio" value="single" checked="checked" /><?php echo $lbl_orderunits1;?> 
                <input name="purchase_unit" type="radio" value="pair" /><?php echo $lbl_orderunits2;?></td>
              </tr>
         
		 <tr align="right">
		 <?php if ($mylang == 'lang_french') {  ?>
		<div align="right"> <td align="right" class="formCellNosides">Comment nous avez-vous trouvé ?</div></td>
		<?php  }else{ ?>
		<div align="right"> <td align="right" class="formCellNosides">How did you find us ?</div></td>
		<?php } ?>
		 <td align="left">
		  <select name="findus" id="findus">
		<?php if ($mylang == 'lang_french') {  ?>
		<option value="">Choisissez</option>
		<?php  }else{ ?>
		<option value="">Select</option>
		<?php } ?>

					<option value="trade">&nbsp;&nbsp;&nbsp;&nbsp;Trade show</option>
					<option value="rep">&nbsp;&nbsp;&nbsp;&nbsp;Sales rep</option>
					<option value="other">&nbsp;&nbsp;&nbsp;&nbsp;Other</option>
					<option disabled="disabled" value="">Magazine</option>
					<option value="optik">&nbsp;&nbsp;&nbsp;&nbsp;Optik</option>
					<option value="larevue">&nbsp;&nbsp;&nbsp;&nbsp;La Revue</option>
                	<option value="vision">&nbsp;&nbsp;&nbsp;&nbsp;Vision</option>
					<option disabled="disabled" value="">Web</option>
					<option value="infoclip">&nbsp;&nbsp;&nbsp;&nbsp;InfoClip.ca</option>
					<option value="optiguide">&nbsp;&nbsp;&nbsp;&nbsp;Opti-Guide.com</option>
					<option disabled="disabled" value="">eBulletin</option>
					<option value="pointclip">&nbsp;&nbsp;&nbsp;&nbsp;Capsule Point Clip</option>
					<option value="optinews">&nbsp;&nbsp;&nbsp;&nbsp;Opti-news</option>
               </select>      
		 </td>
		 </tr>
         
         
            
             <tr>
                  <td align="left"  class="formCellNosides"><div align="right"> <?php if ($mylang == 'lang_french') { 
				 	echo 'Paiement';
				 }else{
				 	echo 'Payment';
				 } ?></div></td>
                  <td colspan="3" align="left" class="formCellNosides">
                 <?php if ($mylang == 'lang_french') { 
				 	echo 'État de compte payé par carte de crédit';
				 }else{
				 	echo 'Statement Payment by Credit Card';
				 } ?>
                  <input name="pay_credit_card" type="checkbox" id="pay_credit_card" value="yes">
                  </td>

              </tr>
           
		
		 	 
		 
		    <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1">
                <?php echo $lbl_titlemast2;?>						
                </div></td>	
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_address1_ba;?>	
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address1" value="<?php echo  $_SESSION[newAccountLensnet][bill_address1];?>" type="text" id="bill_address1" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_address2_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address2" type="text" id="bill_address2" value="<?php echo  $_SESSION[newAccountLensnet][bill_address2];?>" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_city_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_city" type="text" value="<?php echo  $_SESSION[newAccountLensnet][bill_city];?>" id="bill_city" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_state_ba;?>
                </div>              	</td>
              <td align="left" class="formCellNosides"><input name="bill_state" type="text"value="<?php echo  $_SESSION[newAccountLensnet][bill_state];?>" id="bill_state" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_zip_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_zip" value="<?php echo  $_SESSION[newAccountLensnet][bill_zip];?>" type="text" id="bill_zip" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_country_txt_ba;?>
                </div></td>
              <td align="left" class="formCellNosides">
                <select name = "bill_country" id="bill_country">
                  <option <?php if ($_SESSION[newAccountLensnet][bill_country] == '') echo ' Selected';  ?> value="">Select One</option>
                  <option <?php if ($_SESSION[newAccountLensnet][bill_country] == 'CA') echo ' Selected';  ?> value ="CA">Canada</option>
                  <option <?php if ($_SESSION[newAccountLensnet][bill_country] == 'FR') echo ' Selected';  ?> value ="FR">France</option>
                  <option <?php if ($_SESSION[newAccountLensnet][bill_country] == 'US') echo ' Selected';  ?> value ="US">United States</option>
               </select></td>
              </tr>
            <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center">
                <span class="style1">
                  <?php echo $lbl_titlemast3;?></span>
                </div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_address1_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address1" value="<?php echo  $_SESSION[newAccountLensnet][ship_address1];?>" type="text" id="ship_address1" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_address2_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address2" type="text" id="ship_address2" value="<?php echo  $_SESSION[newAccountLensnet][ship_address2];?>" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_city_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_city" value="<?php echo  $_SESSION[newAccountLensnet][ship_city];?>" type="text" id="ship_city" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_state_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_state" type="text" id="ship_state" value="<?php echo  $_SESSION[newAccountLensnet][ship_state];?>" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_zip_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_zip" type="text" id="ship_zip" value="<?php echo  $_SESSION[newAccountLensnet][ship_zip];?>" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_country_txt_sa;?> </div></td>
              <td align="left" class="formCellNosides">
              <select name = "ship_country" id="ship_country">
                  <option <?php if ($_SESSION[newAccountLensnet][ship_country] == '')   echo ' Selected';  ?> value="">Select One</option>
                  <option <?php if ($_SESSION[newAccountLensnet][ship_country] == 'CA') echo ' Selected';  ?> value ="CA">Canada</option>
                  <option <?php if ($_SESSION[newAccountLensnet][ship_country] == 'FR') echo ' Selected';  ?> value ="FR">France</option>
                  <option <?php if ($_SESSION[newAccountLensnet][ship_country] == 'US') echo ' Selected';  ?> value ="US">United States</option>
                </select></td>
              </tr>
            <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"> <?php echo $lbl_titlemast4;?></div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_login_txt_oa;?></div></td>
              <td align="left" class="formCellNosides"><input name="user_id" value="<?php echo  $_SESSION[newAccountLensnet][user_id];?>" type="text" id="user_id" size="20"><span id="status"></span></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_pw_txt_oa;?></div></td>
              <td align="left" class="formCellNosides"><input name="password" type="password" id="password" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                </div></td>
              <td align="left" class="formCellNosides">&nbsp;</td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_pw_confirm_oa;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="pw_confirm" type="password" id="pw_confirm" size="20"></td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formCellNosides"><div align="center">
                <input name="terms" type="checkbox" id="terms" value="agree">
                 <?php if ($mylang == 'lang_french') { 
				 echo 'J\'accepte les <a target="_blank" href="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/lensnet/pdf/conditions_fr.pdf">termes d\'utilisation</a> de Lensnet club. ';
				 }else{
				 echo 'I agree to the <a target="_blank" href="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/lensnet/pdf/conditions_en.pdf">terms</a> of Lensnet club.';
				 } ?>
                </div>              	</td>
              </tr>
			  
			  
		 
             <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"> <?php echo 'Validation';?></div></td>
              </tr>
           
            <tr align="center" bgcolor="#FFFFFF">
           
              <td colspan="2">
              <?php if ($mylang == 'lang_french'){
				echo 'Veuillez taper le texte contenu dans cette image';
				}else {
				echo ' Please type the content of this image in this textbox:';
				}
				?>
             </td>
             
              <td align="center" class="formCellNosides"><input type="text" name="captcha_code" size="10" maxlength="6" />
	<a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">
      <?php if ($mylang == 'lang_french'){
				echo '[Changer&nbsp;d\'image]';
				}else {
				echo '[Change&nbsp;Image]';
				}
				?>
                </a></td>
 <td align="left"  class="formCellNosides"><div align="right"><img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /></div></td>
              </tr>
			  
			  
          </table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="requestTest" value="yes">
		      		<input name="Reset" type="reset" class="formText" value="<?php echo $btn_reset_txt;?> ">
		      		&nbsp;
		      		<input name="openAcct" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('accountForm', this.name);">
		      	</p>
		      	<p class="formText"><?php echo $lbl_footer_oa;?></p>
  </div>

*/ ?>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>