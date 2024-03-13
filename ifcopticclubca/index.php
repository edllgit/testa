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
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
<link rel="shortcut icon" href="favicon.ico"/>

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function ChangeLang(mylang){
	
		var cur_lang=readCookie("mylang");

		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		
		//if (cur_lang=='lang_france'){
		//	window.location = "index.php";
		//}
}
</script>

</head>

<?php   if ($mylang == 'lang_france') {  
echo '<body onLoad="ChangeLang(\'lang_french\')">';
}else{
echo '<body>';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
      <table width="1050" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="143" rowspan="2" align="right" valign="bottom">
        	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc_club_logo.gif" alt="By DirectLab" 
        	width="143" height="135" border="0"/>            
        </td>
        <td colspan="2">          
          <div style="float:right">                
                <div class="home_top_nav">
                	<div align="right"><br /><?php include("includes/translator.php"); ?></div>                                         
                </div>
                
            </div>
        </td>
        </tr>
      <tr>
        <td valign="top" bgcolor="#19AEDD"><table width="662" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            	<td  align="center">
                <div class="home_mid_nav">
                	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/glasses.gif" width="35" height="18" hspace="5" />
                    <a href="login.php"><?php echo $lbl_btn_custlogin;?></a>
                </div>
                </td>
            </tr>
        </table>
        </td>
        <td width="245" align="right" bgcolor="#19AEDD">

                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/direct-lab_logo.gif" 
            	width="245" height="43" border="0" /></a></td> 
           
      </tr>
      <tr>
        <td colspan="3" class="grey-bord-left grey-bord-right">
        	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece_ifs-2.jpg" width="1050" height="309" />
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center" ><table width="1050"  border="0" cellpadding="0" cellspacing="8" style="background-color:#FFFFFF; margin-top:8px">
          <tr>
		<?php 	if ($mylang == 'lang_french') {  ?>
            <td align="center"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/left_box.jpg" width="652" height="210" /></td>
            <td align="center"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/right_box.jpg" width="356" height="210" /></td>
        <?php  	}else{ ?>
            <td align="center"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/left_box-en.jpg" width="652" height="210" /></td>
            <td align="center"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/right_box-en.jpg" width="356" height="210" /></td>
        <?php 	} ?>         
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="3" align="center" ><table  border="0" cellspacing="5" cellpadding="0" style="background-color:#FFFFFF">
          <tr>
            <td>
                <div class="home_features_boxes">
                    <div class="home_features_header" style="padding-left:0px">
						  
                    </div>              

                </div>
            </td>
            <td>
            	<div class="home_features_boxes">
              		<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/home_feature.png" width="313" height="181" />
              	</div>
                </td>
            	<td>                	
                    <div class="home_features_boxes">
                	<?php 	if ($mylang == 'lang_french') {  ?>
              			<div class="home_features_header" style="padding-left:0px">VOUS SOUHAITEZ EN SAVOIR PLUS ?</div>
              			<div class="home_features_body">Contactez au plus vite notre équipe commerciale.</div>
 						<div class="home_features_body">
                            <b>Appelez-nous au 1-855-770-2124</b><br /><br />
                            <b><a href="contact.php">Ou cliquez ici pour plus d'information</a></b>
						</div>                                         
                    <?php  	}else{ ?>
              			<div class="home_features_header" style="padding-left:0px">WANT TO KNOW MORE?</div>
              			<div class="home_features_body">Contact our sales team.</div>
 						<div class="home_features_body">
                            <b>Call us at 1-800-418-2901</b><br /><br />
                            <b><a href="contact.php">Or click here for more information </a></b>  
                        </div> 
					<?php 	} ?> 
              		</div>                                         
                </td>
            </tr>
          </table></td>
      	</tr>
  		<tr>
            <td colspan="3" class="grey-bord-left grey-bord-right grey-bord-bottom">
                <div id="footerBox">
                    <div class="home_top_nav">
                        © 2019 IFC-International Frame Club Canada by DirectLab</a> 
                        and <a href="http://Direct-lens.com">Direct-lens.com</a> | 
                        <?php 	if ($mylang == 'lang_french') {  ?>
                            <a href="conditions.php">Conditions</a>             
                        <?php  	}else{ ?>
                            <a href="conditions-en.php">Terms and Conditions</a>
                        <?php 	} ?>         
                    </div>
                </div>
            </td>
        </tr>
    </table></td>
  </tr>
</table>



</body>
</html>