<?php 
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
  errors += checkText(formname, 'Name', '<?php echo $lbl_nameerror1;?>');
  errors += checkText(formname, 'Phone', '<?php echo $lbl_phoneerror1;?>');
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
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="../send_email.php" method="post" name="contactForm" id="contactForm">
      <div class="bigwelcome">
		  	<?php echo $lbl_titlemast_contactus;?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		    <br>
		    <br>
		    <div class="Subheader"><?php echo $lbl_bodytext_contactus_ln;?></div>
	    <p>&nbsp;</p>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_fullname_cu;?></div></td>
              	<td colspan="3" align="left" class="formCellNosides"><input name="Name" type="text" id="Name" size="30"></td>
              	</tr>
              <tr>
                <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_email_cu;?> </div></td>
                <td align="left" class="formCellNosides"><input name="Email" type="text" id="Email" size="20"></td>
                <td align="left" nowrap class="formCellNosides">&nbsp;</td>
                <td align="left" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" nowrap class="formCellNosides"><div align="right"> <?php echo $lbl_phone_cu;?> </div></td>
                <td width="10%" align="left" class="formCellNosides"><input name="Phone" type="text" id="Phone" size="20"></td>
                <td align="left" nowrap class="formCellNosides">&nbsp;</td>
                <td align="left" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_comment_cu;?></div></td>
                <td colspan="3" align="left" class="formCellNosides"><textarea name="Comment" cols="50" rows="3" id="Comment"></textarea></td>
                </tr>
            </table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input name="Reset" type="reset" class="formText" value="<?php echo $btn_reset_txt;?>">
		      		&nbsp;
		      		<input name="login" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('contactForm', this.name);">
		      	    <input name="MailTo" type="hidden" id="MailTo" value="dbeaulieu@direct-lens.com" />
                    <input name="product_line" type="hidden" id="product_line" value="lensnetclub" />
                    <input name="Subject" type="hidden" id="Subject" value="Lens Net Club Contact Form Submittal" />
                    <input name="ReturnPage" type="hidden" id="ReturnPage" value="lensnet/contact_return.php" />
	      	  </p>
   	  </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>