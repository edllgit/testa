<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";
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
<script type="text/javascript" src="includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */
function check(formname, submitbutton) {
  var errors = '';
  errors += checkText(formname, 'user_id', 'Login');
  errors += checkText(formname, 'password', 'Password');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>

<body>
<div id="container">
  	<div id="masthead">
  		<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
	</div>
	<div id="maincontent">
		<div id="leftColumn">
			<div id="leftnav">
  				<?php include("includes/sideNav.inc.php"); ?>
			</div><!--END leftnav-->
		</div><!--END leftcolumn-->
		<div id="rightColumn">
        	<form action="hbclogin.php" method="post" name="loginForm" id="loginForm" autocomplete="off">
		    <div class="header">
				<?php 	if ($mylang == 'lang_french') {  ?>
                    Bienvenue sur HBC - 
                <?php  	}else{ ?>
                    Welcome to HBC
                <?php 	} ?>                 
            </div>
		  <br />
            

              <br />
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
            	<tr>
                	<td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
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
                	</td>

              	<tr valign="bottom">
                	<td colspan="4" align="center"  class="formCellNosides">
                    	<div align="center">
      	 					<input name="login" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('loginForm', this.name);" />
      					</div>
                    </td>
              	</tr>
            </table>
			</form>
		</div><!--END rightcolumn-->
	</div><!--END maincontent-->
	<div id="footer1">
	</div>
</div><!--END containter-->

</body>
</html>