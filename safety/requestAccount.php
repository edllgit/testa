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
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo  $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
    
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

<?php  if ($mylang == 'lang_french'){ ?>
<script type="text/javascript" src="../includes/formvalidatorIFC.js"></script>
<?php }else  { ?>
<script type="text/javascript" src="../includes/formvalidator.js"></script>
<?php } ?>
<script type="text/javascript">

<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */
<?php  if ($mylang == 'lang_french'){ ?>
function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Titre');
  errors += checkText(formname, 'first_name', 'Prénom');
  errors += checkText(formname, 'last_name', 'Nom de famille');
  errors += checkText(formname, 'bill_address1', 'Adresse de facturation 1');
  errors += checkText(formname, 'bill_city', 'Ville');
  errors += checkSelect(formname, 'bill_state', 'État/Province');
  errors += checkText(formname, 'bill_zip', 'Code Postal');
  errors += checkText(formname, 'phone', 'Téléphone');
  errors += checkEmail(formname, 'email', 'Courriel');
  errors += checkText(formname, 'user_id', 'Nom d\'utilisateur');
  errors += checkText(formname, 'password', 'Mot de passe');
  errors += checkText(formname, 'pw_confirm', 'Confirmation du mot de passe');
  errors += checkBox(formname, 'terms', 'J\'accepte les termes de Safety');
  errors += checkPW(formname, 'password', 'pw_confirm', 'Champs mot de passe');
  errors += checkSelect(formname, 'language', 'Langue');
  checkThisForm(formname, submitbutton, errors);
}
<?php }else  { ?>
function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Title');
  errors += checkText(formname, 'first_name', 'First name');
  errors += checkText(formname, 'last_name', 'Last name');
  errors += checkText(formname, 'bill_address1', 'Billing address 1');
  errors += checkText(formname, 'bill_city', 'City');
  errors += checkSelect(formname, 'bill_state', 'State/Province');
  errors += checkText(formname, 'bill_zip', 'Postal Code');
  errors += checkText(formname, 'phone', 'Telephone');
  errors += checkEmail(formname, 'email', 'Email');
  errors += checkText(formname, 'user_id', 'Username');
  errors += checkText(formname, 'password', 'Password');
  errors += checkText(formname, 'pw_confirm', 'Password confirmation');
  errors += checkBox(formname, 'terms', 'I agree to Safety terms');
  errors += checkPW(formname, 'password', 'pw_confirm', 'Password fields');
  errors += checkSelect(formname, 'language', 'Language');
  checkThisForm(formname, submitbutton, errors);
}
<?php } ?>
//-->
</script>


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
    
    <div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
           
  <div class="header"><?php echo $lbl_titlemast_oa;?></div>
  <form action="newAccountNotify.php" method="post" name="accountForm" id="accountForm">
                 
      <table border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td colspan="4" bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formText"><div style="width:770px; text-align:center;">
			  <?php echo 'Pour ouvrir un compte, veuillez nous contacter.';?>&nbsp;
			  </div></td>
              </tr>
           


<?php
		   <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_titletxt;?></div></td>
              <td align="left" class="formCellNosides">
                  <select name="title" id="title">
                    <option value=""><?php echo $lbl_selectxt;?></option>
                    <option <?php if ($_SESSION[newaccountIfc][title] == 'Dr.') echo ' Selected';  ?> value="<?php echo 'Dr.';?>"><?php echo 'Dr.'?></option>
                    <option <?php if ($_SESSION[newaccountIfc][title] == $lbl_selectxt2) echo ' Selected';  ?> value="<?php echo $lbl_selectxt2;?>"><?php echo $lbl_selectxt2;?></option>
                    <option <?php if ($_SESSION[newaccountIfc][title] == $lbl_selectxt3) echo ' Selected';  ?> value="<?php echo $lbl_selectxt3;?>"><?php echo $lbl_selectxt3;?></option>
                    <option <?php if ($_SESSION[newaccountIfc][title] == $lbl_selectxt4) echo ' Selected';  ?> value="<?php echo $lbl_selectxt4;?>"><?php echo $lbl_selectxt4;?></option>
                  </select>
              </td>
              <td align="left" nowrap class="formCellNosides">&nbsp;</td>
              <td align="left" class="formCellNosides">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_fname_txt;?> 
              </div></td>
              <td align="left" class="formCellNosides"><input name="first_name"  value="<?php echo  $_SESSION[newaccountIfc][first_name];?>" type="text" id="first_name" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_lname_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="last_name" value="<?php echo  $_SESSION[newaccountIfc][last_name];?>" type="text" id="last_name" size="20" /></td>
              </tr>
            <tr>
              
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_comp_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="company" type="text"  value="<?php echo  $_SESSION[newaccountIfc][company];?>" id="company" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">&nbsp;
                </div></td>
              <td align="left" class="formCellNosides">
              <input type="hidden" name="buying_group" id="buying_group" value="1"  />
              </td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_phonetxt1;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="phone" type="text"  value="<?php echo  $_SESSION[newaccountIfc][phone];?>" id="phone" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_faxtxt;?>
                </div></td>
              	<td align="left" class="formCellNosides">
                  <input name="fax"  value="<?php echo  $_SESSION[newaccountIfc][fax];?>" type="text" id="fax" size="20" />
                  <input value="" name="other_phone" type="hidden" id="other_phone"/>
                  <input name="VAT_no" type="hidden" id="VAT_no" value=""/>
              	</td>              
              </tr>
   
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php 	if ($mylang == 'lang_french') {  ?>
                    Courriel                   
                <?php  	}else{ ?>
                    E-Mail
                <?php 	} ?>                 
                </div></td>
              <td colspan="3" align="left" class="formCellNosides"><input name="email" type="text"  value="<?php echo  $_SESSION[newaccountIfc][email];?>"  
              id="email" size="40" />
                <input name="language" type="hidden" id="language" value="<?php echo $lbl_langtxt2;?>" />
                <input type="hidden" name="main_lab" id="main_lab" value="59" />
                <input type="hidden" name="purchase_unit"  value="pair"/> 
                <input type="hidden" name="findus"  value=""/> 				
                </td>           
              </tr>

		    <tr bgcolor="#ee7e32">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1">
                <?php echo $lbl_titlemast2;?>						
                </div></td>	
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_address1_ba;?>	
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address1" type="text"  value="<?php echo  $_SESSION[newaccountIfc][bill_address1];?>" 
              id="bill_address1" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_address2_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_address2"  value="<?php echo  $_SESSION[newaccountIfc][bill_address2];?>" type="text" 
              id="bill_address2" size="20" /></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_city_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_city"  value="<?php echo  $_SESSION[newaccountIfc][bill_city];?>" type="text" 
              id="bill_city" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_state_ba;?>
                </div>              	</td>
              <td align="left" class="formCellNosides"><input name="bill_state"  value="<?php echo  $_SESSION[newaccountIfc][bill_state];?>" type="text" 
              id="bill_state" size="20" /></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php echo $lbl_zip_ba;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="bill_zip" type="text"  value="<?php echo  $_SESSION[newaccountIfc][bill_zip];?>" 
              id="bill_zip" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_country_txt_ba;?>
                </div></td>
              <td align="left" class="formCellNosides">
                <input name="bill_country" id="bill_country"  value="<?php echo  $_SESSION[newaccountIfc][bill_country];?>" size="20" /></td>
              </tr>
            <tr bgcolor="#ee7e32">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center">
                <span class="style1">
                  <?php echo $lbl_titlemast3;?></span>
                </div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_address1_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address1"  value="<?php echo  $_SESSION[newaccountIfc][ship_address1];?>" type="text" 
              id="ship_address1" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_address2_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_address2" type="text"  value="<?php echo  $_SESSION[newaccountIfc][ship_address2];?>" 
              id="ship_address2" size="20" /></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_city_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_city"  value="<?php echo  $_SESSION[newaccountIfc][ship_city];?>" type="text" 
              id="ship_city" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_state_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_state"  value="<?php echo  $_SESSION[newaccountIfc][ship_state];?>" type="text" 
              id="ship_state" size="20" /></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"> <?php echo $lbl_zip_sa;?> </div></td>
              <td align="left" class="formCellNosides"><input name="ship_zip" type="text"  value="<?php echo  $_SESSION[newaccountIfc][ship_zip];?>" 
              id="ship_zip" size="20" /></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_country_txt_sa;?> </div></td>
              <td align="left" class="formCellNosides">
              <input name="ship_country" id="ship_country"  value="<?php echo  $_SESSION[newaccountIfc][ship_country];?>" size="20" />
              </td>
              </tr>
            <tr bgcolor="#ee7e32">
              <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"> <?php echo $lbl_titlemast4;?></div></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_login_txt_oa;?></div></td>
              <td align="left" class="formCellNosides"><input name="user_id" type="text"  value="<?php echo  $_SESSION[newaccountIfc][user_id];?>" 
              id="user_id" size="20" /><span id="status"></span></td>
              <td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_pw_txt_oa;?></div></td>
              <td align="left" class="formCellNosides"><input name="password"  type="password" id="password" size="20" /></td>
              </tr>
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                </div></td>
              <td align="left" class="formCellNosides">&nbsp;</td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                <?php echo $lbl_pw_confirm_oa;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="pw_confirm" type="password" id="pw_confirm" size="20" /></td>
              </tr>
            <tr>
              <td colspan="4" align="left"  class="formCellNosides"><div align="center">
                <input name="terms" type="checkbox" id="terms" value="agree" />
                 <?php if ($mylang == 'lang_french') { 
				 echo 'J\'accepte les <a target="_blank" href="conditions.php">termes d\'utilisation</a> de Safety. ';
				 }else{
				 echo 'I agree to the <a target="_blank" href="conditions-en.php">terms</a> of Safety.';
				 } ?>
                </div></td>
              </tr>
                 <tr>
              <td colspan="4" align="left"  class="formCellNosides"><div align="center">
                <input name="promo_material" type="checkbox" checked="checked" id="promo_material" value="agree" />
                 <?php if ($mylang == 'lang_french') { 
				 echo 'J\'accepte de recevoir du matériel promotionnel par courriel et par la poste. ';
				 }else{
				 echo 'I agree to receive promotionnal material by email and by mail post.';
				 } ?>
                </div></td>
              </tr>
			   */ ?>
          </table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="requestTest" value="yes" />
		      		
		      		
					<?php /*<input name="openAcct" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('accountForm', this.name);" />*/?>
		      	
				</p>
		      	<p class="formText"><?php echo $lbl_footer_oa;?></p>
  </div>
  <input name="account_no" value="" type="hidden" id="account_no" size="6" />
    </form>
</div><!--END maincontent-->
    <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>