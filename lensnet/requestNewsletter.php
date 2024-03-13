<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include("../Connections/sec_connect.inc.php");
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
<script type="text/javascript" src="../includes/formvalidator.js"></script>


 <?php if ($mylang == 'lang_french') {  ?>

<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */
function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Titre');
  errors += checkText(formname, 'first_name', 'Prénom');
  errors += checkText(formname, 'last_name', 'Nom de famille');
   errors += checkText(formname, 'company', 'Entreprise');
  errors += checkEmail(formname, 'email', 'Courriel');
  errors += checkSelect(formname, 'language', 'Langue');
  errors += checkBox(formname, 'want_to_receive_promo', 'Vous devez cocher la case');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

<?php		}else{?>
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
   errors += checkText(formname, 'company', 'Company');
  errors += checkEmail(formname, 'email', 'Email');
  errors += checkSelect(formname, 'language', 'Language');
    errors += checkBox(formname, 'want_to_receive_promo', 'You need to agree');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>
<?php  } ?>


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
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><div class="header">
  <?php if ($mylang == 'lang_french') {  ?>
	<?php  echo 'Enregistrement à notre info lettre';
			}else{
	 echo  'Suscribe to our newsletter';
			} ?>
</div>
            <form action="newNewsletterNotify.php" method="post" name="accountForm" id="accountForm">
       
         
          <table border="1" align="left" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
              </tr>
          
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right"> *<?php echo $lbl_titletxt;?></div></td>
              <td align="left" class="formCellNosides"><select name="title" id="title">
                <option value=""><?php echo $lbl_selectxt;?></option>
                <option value="<?php echo $lbl_selectxt1;?>"><?php echo $lbl_selectxt1;?></option>
                <option value="<?php echo $lbl_selectxt2;?>"><?php echo $lbl_selectxt2;?></option>
                <option value="<?php echo $lbl_selectxt3;?>"><?php echo $lbl_selectxt3;?></option>
                <option value="<?php echo $lbl_selectxt4;?>"><?php echo $lbl_selectxt4;?></option>
              </select></td>
            
              <td align="right" class="formCellNosides"><div align="right">*  <?php if ($mylang == 'lang_french') {  ?>
	<?php  echo 'Entreprise';
			}else{
	 echo  'Company';
			} ?></div></td>
               <td align="left" class="formCellNosides"><input name="company" type="text" id="company" size="20"></td>
            </tr>
            <tr>
              <td align="left" nowrap class="formCellNosides"><div align="right">
                *<?php echo $lbl_fname_txt;?> 
              </div></td>
              <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20"></td>
              <td align="left" nowrap class="formCellNosides"><div align="right">
               * <?php echo $lbl_lname_txt;?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20"></td>
              </tr>
           
			 
			  
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
           <?php if ($mylang == 'lang_french') {  ?>
	<?php  echo 'Téléphone au travail';
			}else{
	 echo  'Phone at work';
			} ?>
                </div></td>
              <td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20"></td>
            
                <td align="left"  class="formCellNosides"><div align="right">*<?php echo $lbl_langtxt;?> </div></td>
                
                 
                <td align="left" class="formCellNosides">
                <select name="language" id="language">
                  <option value=""><?php echo $lbl_langtxt1;?></option>
                  <option value="<?php echo 'anglais';?>"><?php echo $lbl_langtxt2;?></option>
                  <option value="<?php echo 'francais';?>"><?php echo $lbl_langtxt3;?></option>
                  </select>    						
                </td>
              </tr>
           
            <tr>
              <td align="left"  class="formCellNosides"><div align="right">
               *<?php echo $lbl_emailtxt;?>
                </div></td>
              <td  align="left" class="formCellNosides"><input name="email" type="text" id="email" size="30">
               </td>
               
                
              <td colspan="4" align="left"  class="formCellNosides"><div align="center">
                <input name="want_to_receive_promo" type="checkbox" id="want_to_receive_promo" value="want_to_receive_promo">
                 <?php if ($mylang == 'lang_french') { 
				 echo 'Je souhaite recevoir gratuitement les promotions et nouveautés de LensNet Club. ';
				 }else{
				 echo 'I want to receive LensNet Club free promotions and news. ';
				 } ?>
                </div>              	</td>
             
               
              </tr>
              
                <tr>
              <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($mylang == 'lang_french') {  ?>
	<?php    echo 'Adresse';
			}else{
			 echo  'Address';
			} ?>
                </div></td>
              <td  align="left" class="formCellNosides"><textarea name="address"  id="address" ></textarea> 
               </td>
               
              </tr>


<tr>
<td>&nbsp;</td>
<td><input name="openAcct" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('accountForm', this.name);"></td>
</tr>

<tr><td colspan="4"><p class="formText"><?php echo $lbl_footer_oa;?></p></td></tr>
          </table>
		  
		      
		      		<input type="hidden" name="requestTest" value="yes">
		      		
		      		
		      	
 
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>