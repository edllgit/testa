<?php 
require_once(__DIR__.'/../../constants/aws.constant.php');
include "../../Connections/directlens.php";
include "../../includes/getlang.php";
include("../../Connections/sec_connect.inc.php");
session_start();
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
   
<link href="../ln.css" rel="stylesheet" type="text/css" />
<link href="../ln_pt.css" rel="stylesheet" type="text/css" />
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<style type="text/css">
<!--
.select1 {width:100px}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {color: #FFFFFF}
-->
</style>
<script type="text/javascript" src="../../includes/formvalidator.js"></script>
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
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>

</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php //include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><div class="header">


<?php if ($_SESSION['Language_Promo']== 'french')
{
echo 'Ouvrir un compte: Promo ';   
}else{
echo $lbl_titlemast_oa; 
} ?> 

</div>

            <form action="new_promo_account.php" method="post" name="accountForm" id="accountForm">
       		<input type="hidden" id="oneyear"   name="oneyear" value="<?= $_POST[oneyear] ?>" />
         
          <table border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formText"><div style="width:770px; text-align:center;">
			  
			  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Pour ouvrir un compte, veuilelz remplir les champs suivants. Entrer une adresse de courriel valide et un mot de passe afin que vous puissez accéder à votre compte.';   
				}else{
				echo $lbl_submast;
				} ?> 
			 </div></td>
              </tr>
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right"> 
			    <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Titre'; 
				}else{
				echo $lbl_titletxt;
				} ?> 
			 
              </div></td>
              <td align="left" class="formCellNosides"><select name="title" id="title">
                <option value=""><?php echo $lbl_selectxt;?></option>
                <option value="<?php echo $lbl_selectxt1;?>"><?php echo $lbl_selectxt1;?></option>
                <option value="<?php echo $lbl_selectxt2;?>"><?php echo $lbl_selectxt2;?></option>
                <option value="<?php echo $lbl_selectxt3;?>"><?php echo $lbl_selectxt3;?></option>
                <option value="<?php echo $lbl_selectxt4;?>"><?php echo $lbl_selectxt4;?></option>
              </select></td>
              <td align="left" nowrap class="formCellNosides">&nbsp;</td>
              <td align="left" class="formCellNosides">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Prénom'; 
				}else{
				echo $lbl_fname_txt;
				} ?> 
              </div></td>
              <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Nom de famille'; 
				}else{
				echo $lbl_lname_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20"></td>
              </tr>
            <tr>
              
              <td align="left"  class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Compagnie'; 
				}else{
				echo $lbl_comp_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="company" type="text" id="company" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Groupe d\'achat'; 
				}else{
				echo $lbl_buygrp_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><select name="buying_group" class="formField">
                <option value="1" selected="selected"><?php echo $lbl_buygrp_txt1;?></option>
                <?php
	$query="select primary_key, display_dropdown, bg_name from buying_groups WHERE display_dropdown = 'yes' and primary_key NOT IN (6,8,9,10,11,12,13,14,15,16,17) order by bg_name";
	$result=mysql_query($query)
		or die ("Could not find bg list");
	while ($bgList=mysql_fetch_array($result)){
		if($bgList[primary_key]!=1)
			print "<option value=\"$bgList[primary_key]\">$bgList[bg_name]</option>";
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
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Numéro de compte'; 
				}else{
				echo 'Account number';
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="account_no" type="text" id="account_no" size="6">
			  </td>
              </tr>
			  
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Téléphone'; 
				}else{
				echo $lbl_phonetxt1;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
              
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Autre Téléphone'; 
				}else{
				echo $lbl_phonetxt2;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Fax'; 
				}else{
				echo $lbl_faxtxt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="fax" type="text" id="fax" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php //echo $lbl_vatnumtxt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="VAT_no" type="hidden" id="VAT_no" value=""></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
				 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse courriel'; 
				}else{
				echo $lbl_emailtxt;
				} ?> 
                </div></td>
              <td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="40">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Langue'; 
				}else{
				echo $lbl_langtxt;
				} ?> 

                <select name="language" id="language">
                  <option value=""><?php echo $lbl_langtxt1;?></option>
                  <option value="<?php echo $lbl_langtxt2;?>"><?php echo $lbl_langtxt2;?></option>
                  <option value="<?php echo $lbl_langtxt3;?>"><?php echo $lbl_langtxt3;?></option>
                  </select>    						
                </td>
              
  
              
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Type d\'entreprise'; 
				}else{
				echo $lbl_busstype_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><select name="business_type">
                <option value="<?php echo $lbl_busstype_txt1;?>"><?php echo $lbl_busstype_txt1;?></option>
                <option value="<?php echo $lbl_busstype_txt2;?>"><?php echo $lbl_busstype_txt2;?></option>
                <option value="<?php echo $lbl_busstype_txt3;?>"><?php echo $lbl_busstype_txt3;?></option>
                </select></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Laboratoire principal'; 
				}else{
				echo $lbl_mlprf_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides">

       <select name="main_lab" class="formField">
              <option value="29">Lensnet Club On</option>
              <option value="28">Lensnet Club Qc</option>              
              <option value="32">Lensnet Club USA</option>
              <option value="33">Lensnet Club Atlantic</option>
              <option value="34">Lensnet Club West</option>
              <option value="44">Lensnet Club Pacific</option>
              <option value="38">Lensnet Afrique de l'Ouest</option>
      </select></td>
            


              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Devise'; 
				}else{
				echo $lbl_currency_txt;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides">
                <select name="currency" id="currency">
                  <option value="<?php echo $lbl_currency_abr_txt1;?>"><?php echo $lbl_currency_txt1;?></option>
                  <option value="<?php echo $lbl_currency_abr_txt2;?>"><?php echo $lbl_currency_txt2;?></option>
                  <option value="<?php echo $lbl_currency_abr_txt3;?>"><?php echo $lbl_currency_txt3;?></option>
                  </select>
                </td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Quantité commandé'; 
				}else{
				echo $lbl_orderunits_txt;
				} ?> 
                
                </div></td>
              <td align="left" class="formCellNosides"><input name="purchase_unit" type="radio" value="single" checked="checked" /><?php echo $lbl_orderunits1;?> 
                <input name="purchase_unit" type="radio" value="pair" /><?php echo $lbl_orderunits2;?></td>
              </tr>
         
		 <tr align="right">
          <?php if ($_SESSION['Language_Promo']== 'french')
				{
				//echo '<div align="right"> <td align="right" class="formCellNosides">Comment nous avez-vous trouvé ?</div></td>';
				}else{
				//echo '<div align="right"> <td align="right" class="formCellNosides">How did you find us ?</div></td>';
				} ?> 
         
		
		 <td>
		  <input type="hidden" name="findus" id="findus" value="">
    
		 </td>
		 </tr>
		
		 	 
		 
		    <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse de facturation'; 
				}else{
				echo $lbl_titlemast2;
				} ?> 					
                </div></td>	
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse 1'; 
				}else{
				echo $lbl_address1_ba;
				} ?> 	
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address1" type="text" id="bill_address1" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse2'; 
				}else{
				echo $lbl_address2_ba;
				} ?> 	
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address2" type="text" id="bill_address2" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Ville'; 
				}else{
				echo $lbl_city_ba;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_city" type="text" id="bill_city" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'État/Province'; 
				}else{
				echo $lbl_state_ba;
				} ?> 
                </div>              	</td>
              <td align="left" class="formCellNosides"><input name="bill_state" type="text" id="bill_state" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Code Postal'; 
				}else{
				echo $lbl_zip_ba;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_zip" type="text" id="bill_zip" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                 <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Pays'; 
				}else{
				echo $lbl_country_txt_ba;
				} ?> 
                </div></td>
              <td align="left" class="formCellNosides">
                <select name = "bill_country" id="bill_country">
                     <option value="">Select One</option>
                  <option value ="BE">Benin</option>
		 		  <option value ="BF">Burkina Faso</option>
                  <option value ="CA">Canada</option>
                  <option value ="CAM">Cameroun</option>
                  <option value ="CR">Caribbean</option>
     		      <option value ="CB">Congo-Brazzaville</option>
                  <option value ="CI">Cote d'Ivoire</option>
                  <option value ="FR">France</option>
				  <option value ="GA">Gabon</option>
                  <option value ="IT">Italy</option>
			  	  <option value ="MA">Mali</option>
				  <option value ="RDC">Republique démocratique du Congo</option>
                  <option value ="SE">Senegal</option>
                  <option value ="TO">Togo</option>
                  <option value ="US">United States</option>
               </select></td>
              </tr>
            <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center">
                <span class="style1">
                  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse d\'expédition (laisser vide si exactement la même que l\'adresse de facturation'; 
				}else{
				echo $lbl_titlemast3;
				} ?> 
                 </span>
                </div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> 
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse 1'; 
				}else{
				echo $lbl_address1_sa;
				} ?> 
              </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address1" type="text" id="ship_address1" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Adresse 2'; 
				}else{
				echo $lbl_address2_sa;
				} ?>  
               </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address2" type="text" id="ship_address2" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> 
			  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Ville'; 
				}else{
				echo $lbl_city_sa;
				} ?> 
              
              </div></td>
              <td align="left" class="formCellNosides"><input name="ship_city" type="text" id="ship_city" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> 
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'État/Province'; 
				}else{
				echo $lbl_state_sa;
				} ?>
               </div></td>
              <td align="left" class="formCellNosides"><input name="ship_state" type="text" id="ship_state" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> 
			  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Code Postal'; 
				}else{
				echo $lbl_zip_sa;
				} ?>
			</div></td>
              <td align="left" class="formCellNosides"><input name="ship_zip" type="text" id="ship_zip" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> 
			   <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Pays'; 
				}else{
				echo $lbl_country_txt_sa;
				} ?>
               </div></td>
              <td align="left" class="formCellNosides">
              <select name = "ship_country" id="ship_country">
                  <option value="">Select One</option>
                  <option value ="BE">Benin</option>
		 		  <option value ="BF">Burkina Faso</option>
                  <option value ="CA">Canada</option>
                  <option value ="CAM">Cameroun</option>
                  <option value ="CR">Caribbean</option>
     		      <option value ="CB">Congo-Brazzaville</option>
                  <option value ="CI">Cote d'Ivoire</option>
                  <option value ="FR">France</option>
				  <option value ="GA">Gabon</option>
                  <option value ="IT">Italy</option>
			  	  <option value ="MA">Mali</option>
				  <option value ="RDC">Republique démocratique du Congo</option>
                  <option value ="SE">Senegal</option>
                  <option value ="TO">Togo</option>
                  <option value ="US">United States</option>
                </select></td>
              </tr>
            <tr bgcolor="#000099">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"> 
			  <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Information de connexion'; 
				}else{
				echo $lbl_titlemast4;
				} ?>
              </div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Nom d\'utilisateur'; 
				}else{
				echo $lbl_login_txt_oa;
				} ?>
              </div></td>
              <td align="left" class="formCellNosides"><input name="user_id" type="text" id="user_id" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
               <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Mot de passe'; 
				}else{
				echo $lbl_pw_txt_oa;
				} ?>
              </div></td>
              <td align="left" class="formCellNosides"><input name="password" type="password" id="password" size="20"></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                </div></td>
              <td align="left" class="formCellNosides">&nbsp;</td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
				{
				echo 'Confirmation mot de passe'; 
				}else{
				echo $lbl_pw_confirm_oa;
				} ?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="pw_confirm" type="password" id="pw_confirm" size="20"></td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formCellNosides"><div align="center">
                <input name="terms" type="checkbox" id="terms" value="agree">
                 <?php if ($_SESSION['Language_Promo']== 'french') { 
				 echo 'J\'accepte les <a target="_blank" href="pdf/conditions_fr.pdf">termes d\'utilisation</a> de Lensnet club. ';
				 }else{
				 echo 'I agree to the <a target="_blank" href="pdf/conditions_en.pdf">terms</a> of Lensnet club.';
				 } ?>
                </div>              	</td>
              </tr>
			  
			  
		
			  
			  
          </table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="requestTest" value="yes">
		      		
                     <?php if ($_SESSION['Language_Promo']== 'french')	{?>
				  <input name="openAcct" type="button" class="formText" value="<?php echo 'Soumettre';?>" onClick="check('accountForm', this.name);">
				<?php }else{ ?>
			 <input name="openAcct" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('accountForm', this.name);">
				<?php } ?>
                    
                  
		      	</p>
		      	<p class="formText"><?php echo $lbl_footer_oa;?></p>
  </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>
<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>