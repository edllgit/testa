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
<title><?php echo $sitename;?></title>
 
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">   
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"> <form action="forgot_password.php" method="post" name="forgotPwd" id="forgotPwd">
		    <div class="header">
		 <?php if ($mylang=='lang_french'){  
                echo 'SVP Contactez-nous';
				}else{
				echo 'Please Contact Us';
				}?>     
            </div>
		    <br />
		    <br />
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="left"> 
			<?php if ($mylang=='lang_french'){  
                echo 'Nous ne sommes pas en mesure d\'identifier votre compte. SVP,  Veuillez nous contacter au 1(877) 570-3522 Poste 203';
				}else{
				echo 'We are unable to identify your account. Please contact us at 1(877) 570-3522 Ext 203';
				}?></div></td>
              	</tr>
            </table>
            
	 	   <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>