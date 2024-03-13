<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "Connections/hbc.php";
include "includes/getlang.php";
include "config.inc.php";
$product_line = 'hbc';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
    
<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}
</script>
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
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
<div id="rightColumn"> <form action="forgot_password.php" method="post" name="forgotPwd" id="forgotPwd">
		    <div class="header">
		 <?php if ($mylang=='lang_french'){  
                echo 'Oubli&eacute; votre mot de passe';
				}else{
				echo 'Forgot your password';
				}?>               
            </div>
		    <br />
		    <br />
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
              <tr>
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
              </tr>
              
				<tr>
					<td align="left"  class="formCellNosides">
					<?php if ($mylang=='lang_french'){  
					echo 'Svp contacter le siège social pour de l\'assistance avec ce problème';
					}else{
					echo 'Please contact the Head Office to get assistance with this problem';
					}?>
					</td>
              	</tr>
             
        <div align="center">
	      	  
      	</div></td>
              </tr>
            </table>
	    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>


</body>
</html>