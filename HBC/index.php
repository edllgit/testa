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
}
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
  <tr>
    <td align="center">
      <table width="1050" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="143" rowspan="2" align="right" valign="bottom">
        	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc_club_logo.gif" alt="By DirectLab" 
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
        	       
			<?php 	if ($mylang == 'lang_french') {  ?>
                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/direct-lab_logo.gif" 
            	width="245" height="43" border="0" /></td> 
            <?php  	}else{ ?>
                <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/direct-lab_logo-en.gif" 
            	width="245" height="43" border="0" /></a></td> 
            <?php 	} ?>                      
      </tr>
      <tr>
        <td colspan="3" class="grey-bord-left grey-bord-right">
        	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece_ifs-2.jpg" width="1050" height="309" />
        </td>
      </tr>
     
	 <tr>
        <td colspan="3" align="center" ><table width="1050"  border="0" cellpadding="0" cellspacing="8" style="background-color:#FFFFFF; margin-top:8px">
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
              		
              	</div>
                </td>
            	<td>                	
                    <div class="home_features_boxes">

              		</div>                                         
                </td>
            </tr>
          </table></td>
      	</tr>
  		<tr>
            <td colspan="3" class="grey-bord-left grey-bord-right grey-bord-bottom">
                <div id="footerBox">
                    <div class="home_top_nav">
                        © 2022 Glasses Gallery @ HBC 
                    </div>
                </div>
            </td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>