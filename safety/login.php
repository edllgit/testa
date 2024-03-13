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
<title><?php print $sitename;?></title>
    
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>

<script type="text/javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}
</script>
<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkText(formname, 'user_id', 'Login');
  errors += checkText(formname, 'password', 'Password');
  //errors += checkRadio(formname, 'Question1', 'Question 1');
  //errors += checkText(formname, 'Question1_explain', 'Explain Question 1');
  //errors += checkSelect(formname, 'Country', 'Country Of Residence');
  //errors += checkText(formname, 'age', 'Age Of Person');
  //errors += checkNum(formname, 'age', 'Age Of Person');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>

<body>
<div id="container">    
	<div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
        	<form action="dllogin.php" method="post" name="loginForm" id="loginForm">
		    <div class="header">
				<?php 	if ($mylang == 'lang_french') {  ?>
                    Accès Clients
                <?php  	}else{ ?>
                    Client Access
                <?php 	} ?>                 
            </div>
            <div class="Subheader" style="height:300px;">             
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
              	<tr>
					<td align="left"  class="formCellNosides">
                    	<div align="right">
							<?php echo $lbl_logintxt_cust;?>
						</div>
                    </td>
              		<td width="10%" align="left" class="formCellNosides">
                    	<input name="user_id" type="text" id="user_id" size="20" />
                    </td>
              		<td align="left" nowrap class="formCellNosides">
                    	<div align="right">
              				<?php echo $lbl_pwdtxt_cust;?>
              			</div>
                    </td>
              		<td align="left" class="formCellNosides">
                    	<input name="password" type="password" id="password" size="20" />
                  		&nbsp;&nbsp;&nbsp;
                    	<a href="forgot_password.php">
                		<?php if ($mylang=='lang_french'){  
						echo "Oubli&eacute; votre mot de passe";
						}else{
						echo "Forgot your password";
						}?>
                        </a>
                	</td>
              	</tr>
              	<tr valign="bottom">
                	<td colspan="4" align="center"  class="formCellNosides">
                    	<div align="center">
                        	<input style="display:none" name="lens_type" type="radio" value="prescription" checked />
      	 					<input name="login" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('loginForm', this.name);" />
      					</div>
                    </td>
              	</tr>
            </table>
			</form>
		</div>
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>