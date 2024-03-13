<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include "config.inc.php";
global $drawme;
require_once "../upload/phpuploader/include_phpuploader.php";

session_start();

$_SESSION['REFERRER']="sv_frame_form.php?prod=".$_GET['prod'];// CATCH FOR RETRY

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	require('../Connections/sec_connect.inc.php');

mysql_query("SET CHARACTER SET UTF8");
 
 
 if ($_GET['prod']!=""){
	$frameQuery = "SELECT * FROM ifc_frames_french WHERE ifc_frames_id='$_GET[prod]'";
	$frameResult=mysql_query($frameQuery)	or die ("ERROR:");
	$frameItem=mysql_fetch_array($frameResult);
	$frameItem[prod_tn]="http://www.direct-lens.com/ifcopticclub/prod_images/".$frameItem[image];
	$frameItem[prod_tn]="http://www.direct-lens.com/ifcopticclub/prod_images/".$frameItem['code']."/images/". $frameItem['code']."_19.jpg";
	
	$Cushion_available 			     = 'No';
	$Dust_bar_available				 = 'No';
	//Cushions
	if (strtolower($frameItem[cushion_available]) =='yes'){
		$Cushion_available     =   'Yes';
		$Cushion_available_EN  =   'Yes';
		$Cushion_available_FR  =   'Oui';
		$cushion_ID            =   $frameItem[cushion_ID];
		$cushion_selling_price =   $frameItem[cushion_selling_price];
	}else{
		$Cushion_available_EN  =   'No';
		$Cushion_available_FR  =   'Non';	
	}
	//Dust Bar
	if (strtolower($frameItem[dust_bar_available]) =='yes'){
		$Dust_bar_available     = 'Yes';
		$Dust_bar_available_FR  = 'Oui';
		$Dust_bar_available_EN  = 'Yes';
		$dust_bar_selling_price = $frameItem[dust_bar_selling_price] ;	
	}else{
		$Dust_bar_available_FR  = 'Non';
		$Dust_bar_available_EN  = 'No';	
	}
		
	if ($frameItem['collection']=='NURBS')
	$frameItem[prod_tn]="http://www.direct-lens.com/ifcopticclub/prod_images/".$frameItem['upc']."/images/". $frameItem['code']."_19.jpg";	
 }
 
  
$AfficherPageCommande = true;
$queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('exclusive') AND order_status NOT IN ('pre-basket')";
$ResultBasket=mysql_query($queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
$DataBasket=mysql_fetch_array($ResultBasket);
 
$querySafetyPlan  		= "SELECT charge_dispensing_fee FROM accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
$SafetyPlanResult 		= mysql_query($querySafetyPlan)	or die ("ERROR:");
$DataSafetyPlan   		= mysql_fetch_array($SafetyPlanResult);
$Charge_Dispensing_Fee  = $DataSafetyPlan[charge_dispensing_fee];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>

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


<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form_frame.js.inc.php";?>

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

 <?php if (($mylang == 'lang_french') && ($_SESSION["CompteEntrepot"] == 'no')){ ?>
<form action="svList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateSV(this);">
 <?php }elseif (($mylang == 'lang_english') && ($_SESSION["CompteEntrepot"] == 'no')){ ?>
<form action="svList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateSVEN(this);">
 <?php }elseif(($_SESSION["CompteEntrepot"] == 'yes') || ($_SESSION["sessionUser_Id"] == 'redoifc')){?>
 <form action="svList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateEntrepotSV(this);">
 <?php } ?>
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">
           <?php if ($mylang == 'lang_french'){
		   echo 'Ordonnance';
		   }else{
		   echo 'Prescription Search';
		   }
		   ?>
              </div></td><td><div id="headerGraphic">
              
             
    <?php if ($mylang == 'lang_french'){ ?>
   <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
              </div></td></tr></table>
		      
               <?php if ( $_SESSION["product_line"]=='safety'){//Client de l'entrepot est connecté dans un compte SAFE, on doit l'aviser ?>
  <div>
           	<?php
					if ($mylang == 'lang_french') { 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>Vous êtes présentement connectés dans un compte SAFE:' . $_SESSION["sessionUser_Id"].', veuillez vous reconnecter dans <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					}else{ 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>You are currently logged in a SAFETY account: ' . $_SESSION["sessionUser_Id"].', Please re-connect in <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					} 		

			?>
   </div>


<?php 
}//End IF Client est dans un compte SAFE   ?>
              
              
              
              <div>
<?php if ($mylang == 'lang_french'){
					
					echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>
					<td bgcolor="#17A2D2" colspan="2" class="tableHead">MONTURE</td>
					</tr>';
					echo "<tr><td><img src=\"$frameItem[prod_tn]\" alt=\"$frameItem[model]\" border=\"0\" title=\"$frameItem[model]\" width=\"450\" ></td ><td >";
					echo "<div class=\"frame-specs\" ><b>MODELE : $frameItem[upc]</b></div>";
					
										
					if ($frameItem['collection']=='NURBS'){
					 	if(strtolower($frameItem['frame_shape_en'])=='square wrap')
							echo '<img src="http://www.direct-lens.com/ifcopticclubca/design_images/square.jpg" alt="Square frame" border="0" title="Square frame" width="130" >';
						if(strtolower($frameItem['frame_shape_en'])=='oval wrap')
							echo '<img src="http://www.direct-lens.com/ifcopticclubca/design_images/oval.jpg" alt="Oval Frame" border="0" title="Oval Frame" width="130" >';
					}
					
                     
					
					echo "<div class=\"frame-specs\" ><b>TYPE :</b> $frameItem[type]</div>";
					echo "<div class=\"frame-specs\" ><b>GENRE :</b> $frameItem[gender]</div>";
					echo "<div class=\"frame-specs\" ><b>FORME :</b> $frameItem[frame_shape]</div>";
					echo "<div class=\"frame-specs\" ><b>MATIERE :</b> $frameItem[material]</div>";
					echo "<div class=\"frame-specs\" ><b>COULEURS :</b> $frameItem[color]</div>";
					echo "<div class=\"frame-specs\" ><b>TAILLE :</b> $frameItem[boxing]</div>";
					echo "</td></tr></table>";
					
                }else {
					
					echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>
					<td bgcolor="#17A2D2" colspan="2" class="tableHead">FRAME</td>
					</tr>';
					echo "<tr><td><img src=\"$frameItem[prod_tn]\" alt=\"$frameItem[model]\" border=\"0\" title=\"$frameItem[model]\" width=\"450\" ></td ><td >";
					echo "<div class=\"frame-specs\" ><b>MODEL: $frameItem[upc]</b></div>";
										
					if ($frameItem['collection']=='NURBS'){
					 	if(strtolower($frameItem['frame_shape_en'])=='square wrap')
							echo '<img src="http://www.direct-lens.com/ifcopticclubca/design_images/square.jpg" alt="Square frame" border="0" title="Square frame" width="130" >';
						if(strtolower($frameItem['frame_shape_en'])=='oval wrap')
							echo '<img src="http://www.direct-lens.com/ifcopticclubca/design_images/oval.jpg" alt="Oval Frame" border="0" title="Oval Frame" width="130" >';
					}
					
					echo "<div class=\"frame-specs\" ><b>TYPE:</b> $frameItem[type_en]</div>";
					echo "<div class=\"frame-specs\" ><b>GENDER:</b> $frameItem[gender_en]</div>";
					echo "<div class=\"frame-specs\" ><b>SHAPE :</b> $frameItem[frame_shape_en]</div>";
					echo "<div class=\"frame-specs\" ><b>FABRIC:</b> $frameItem[material_en]</div>";
					echo "<div class=\"frame-specs\" ><b>COLORS:</b> $frameItem[color_en]</div>";
					echo "<div class=\"frame-specs\" ><b>SIZE:</b> $frameItem[boxing]</div>";
					echo "</td></tr></table>";
					
                }?>                   

                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                

                  <tr >
                    <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_client;?></td>
                    <td bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret client'; else echo 'Customer Tray';?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret lab'; else echo 'Lab Tray';?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'ID Vendeur'; else echo 'Salesperson ID';?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php echo $_SESSION['PrescrData']['LAST_NAME'];?>" size="20" /></td>
                    <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" value="<?php echo $_SESSION['PrescrData']['FIRST_NAME'];?>" size="20" /></td>
                    <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" value="<?php echo $_SESSION['PrescrData']['PATIENT_REF_NUM'];?>" size="10" /></td>
                    
                     <td class="formCellNosides"><input name="TRAY_NUM" type="text" class="formText" id="TRAY_NUM" value="<?php echo $_SESSION['PrescrData']['TRAY_NUM'];?>" size="5" /></td>
                     
                     <td class="formCellNosides"><input name="SALESPERSON_ID" type="text" class="formText" id="SALESPERSON_ID" value="<?php echo $_SESSION['PrescrData']['SALESPERSON_ID'];?>" size="15" maxlength="15" /></td>
                     
                     
                 
                    </tr>
                </table>
              </div>
              
              
              
               <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>
         	<div>
				  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

                  <tr>
                    <td width="350" colspan="1" align="center" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                    <td colspan="2" align="center" bgcolor="#17A2D2" class="tableHead"><?php echo 'PROMO 2 pour 1';?></td>
                    <td width="350" colspan="1" align="center" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td width="250" align="center" class="formCellNosides"><?php echo 'RÃ©fÃ©rence unique';?>&nbsp;</td>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                   </tr>
                   
                  <tr>
                    <td  width="300" colspan="1"  align="center" class="formCellNosides">&nbsp;</td>
                    <td  width="250" align="center" class="formCellNosides"><input name="REFERENCE_PROMO" type="text" class="formText" id="REFERENCE_PROMO" value="<?php echo $_SESSION['PrescrData']['REFERENCE_PROMO'];?>" size="25" /></td>
                    <td  width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td  width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                  </tr>
               </table>
              </div>     
              <?php } ?>
              
              
              
              
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                 <tr >
                   <td colspan="7" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>
                    <input type="hidden" name="frame_id" value="<?php echo $_GET[prod]  ?>" />
                   <input name="EYE" type="radio" value="Both" checked="checked" />

                       <?php echo $lbl_prescription1_pl;?> 
                         &nbsp;
                        </td>
                   </tr>
                 <tr>
                   <td colspan="2" valign="middle"  class="formCell">&nbsp;</td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_sphere_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_cylinder_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_axis_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_addition_txt_pl;?></td>
                        <td align="center" valign="middle" class="formCell"><?php echo $lbl_prism_txt_pl;?></td>
                   </tr>
                 <tr >
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="OD = OG" /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                     <option value="+13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+13") echo "selected=\"selected\"";?>>+13</option>
                     <option value="+12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+12") echo "selected=\"selected\"";?>>+12</option>
                     <option value="+11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+11") echo "selected=\"selected\"";?>>+11</option>
                     <option value="+10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+10") echo "selected=\"selected\"";?>>+10</option>
                     <option value="+9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+9") echo "selected=\"selected\"";?>>+9</option>
                     <option value="+8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['PrescrData']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
                     <option value="-10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                     <option value="-11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-11") echo "selected=\"selected\"";?>>-11</option>
                     <option value="-12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-12") echo "selected=\"selected\"";?>>-12</option>
                     <option value="-13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-13") echo "selected=\"selected\"";?>>-13</option>
                     <option value="-14"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-14") echo "selected=\"selected\"";?>>-14</option>
                     <option value="-15"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-15") echo "selected=\"selected\"";?>>-15</option>
                     <option value="-16"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-16") echo "selected=\"selected\"";?>>-16</option>
                   </select>
                     <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['PrescrData']['RE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                     
                     <option value="-0" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-0") echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7" <?php if ($_SESSION['PrescrData']['RE_CYL_NUM']==="-7") echo "selected=\"selected\"";?>>-7</option>
                   </select>
                     <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['PrescrData']['RE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['PrescrData']['RE_AXIS'];?>" size="4" maxlength="3"  />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD" disabled>
                     <option value="+3.00">+3.00</option>
                     <option value="+2.75">+2.75</option>
                     <option value="+2.50">+2.50</option>
                     <option value="+2.25">+2.25</option>
                     <option value="+2.00">+2.00</option>
                     <option value="+1.75">+1.75</option>
                     <option value="+1.50">+1.50</option>
                     <option value="+1.25">+1.25</option>
                     <option value="+1.00">+1.00</option>
                     <option value="+0.75">+0.75</option>
                     <option value="+0.50">+0.50</option>
                     <option value="+0.00" selected="selected">+0.00</option>
                     </select></td>
                    <td align="right" valign="top"class="formCell">
                     <input name="RE_PR_IO" type="radio" value="In" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='In') echo 'checked="checked"';?> />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='Out') echo 'checked="checked"';?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='None') echo 'checked="checked"';?> />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" 
					value="<?php if ($_SESSION['PrescrData']['RE_PR_AX']>0) echo  $_SESSION['PrescrData']['RE_PR_AX'];?>" /><br />
                     <input name="RE_PR_UD" type="radio" value="Up" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Up') echo 'checked="checked"';?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Down') echo 'checked="checked"';?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='None') echo 'checked="checked"';?> />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['RE_PR_AX2']>0) echo  $_SESSION['PrescrData']['RE_PR_AX2'];?>" /></td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                    
                     <option value="+13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+13") echo "selected=\"selected\"";?>>+13</option>
                     <option value="+12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+12") echo "selected=\"selected\"";?>>+12</option>
                     <option value="+11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+11") echo "selected=\"selected\"";?>>+11</option>
                     <option value="+10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+10") echo "selected=\"selected\"";?>>+10</option>
                     <option value="+9" <?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+9") echo "selected=\"selected\"";?>>+9</option>              
                     <option value="+8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['PrescrData']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
                     <option value="-10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                     <option value="-11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-11") echo "selected=\"selected\"";?>>-11</option>
                     <option value="-12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-12") echo "selected=\"selected\"";?>>-12</option>
                     <option value="-13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-13") echo "selected=\"selected\"";?>>-13</option>
                     <option value="-14"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-14") echo "selected=\"selected\"";?>>-14</option>
                     <option value="-15"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-15") echo "selected=\"selected\"";?>>-15</option>
                     <option value="-16"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-16") echo "selected=\"selected\"";?>>-16</option>
                   </select>
                     <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['PrescrData']['LE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">
					 <option value="-0" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-0") echo "selected=\"selected\"";?>>-0</option> 
                     <option value="-1" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-1") echo "selected=\"selected\"";?>>-1</option> 
                     <option value="-2" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-3") echo "selected=\"selected\"";?>>-3</option> 
                     <option value="-4" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-4") echo "selected=\"selected\"";?>>-4</option> 
                     <option value="-5" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-5") echo "selected=\"selected\"";?>>-5</option>     
                     <option value="-6" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-6") echo "selected=\"selected\"";?>>-6</option>     
                     <option value="-7" <?php if ($_SESSION['PrescrData']['LE_CYL_NUM']==="-7") echo "selected=\"selected\"";?>>-7</option>     
                   </select>
                     <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['PrescrData']['LE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['PrescrData']['LE_AXIS'];?>" size="4" maxlength="3"/>
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD" disabled>
                     <option value="+3.00">+3.00</option>
                     <option value="+2.75">+2.75</option>
                     <option value="+2.50">+2.50</option>
                     <option value="+2.25">+2.25</option>
                     <option value="+2.00">+2.00</option>
                     <option value="+1.75">+1.75</option>
                     <option value="+1.50">+1.50</option>
                     <option value="+1.25">+1.25</option>
                     <option value="+1.00">+1.00</option>
                     <option value="+0.75">+0.75</option>
                     <option value="+0.50">+0.50</option>
                     <option value="+0.00" selected="selected">+0.00</option>
                     </select></td>
                     <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In"  <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='In') echo 'checked="checked"';?>/>
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='Out') echo 'checked="checked"';?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='None') echo 'checked="checked"';?> />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX'];
				 ?>" /><br /><input name="LE_PR_UD" type="radio" value="Up" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Up') echo 'checked="checked"';?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Down') echo 'checked="checked"';?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='None') echo 'checked="checked"';?>/>
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX2'];
				 ?>" /></td>
                   </tr>
               </table>
        </div>
             
             
             
            
             
            
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">

                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                  <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.50") echo 'selected="selected"';  ?> value="1.50">1.50</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.53") echo 'selected="selected"';  ?> value="1.53">1.53</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.56") echo 'selected="selected"';  ?> value="1.56">1.56</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.58") echo 'selected="selected"';  ?> value="1.58">1.58</option>  
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.59") echo 'selected="selected"';  ?> value="1.59">1.59</option>   
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.60") echo 'selected="selected"';  ?> value="1.60">1.60</option>           
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.67") echo 'selected="selected"';  ?> value="1.67">1.67</option>      
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.74") echo 'selected="selected"';  ?> value="1.74">1.74</option>               
                 </select>
                                  
                   </span></td>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">


                     <select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="selected">
                        <?php if ($mylang == 'lang_french'){?>
                            Tous
                        <?php }else {?>
                            All
                        <?php }?>
                		</option>
                       <option value="HC"		 <?php if ($_SESSION['PrescrData']['COATING']=="HC") 	 echo "selected=\"selected\"";?>>HC</option>
                       <option value="AR" 	     <?php if ($_SESSION['PrescrData']['COATING']=="AR")	 echo "selected=\"selected\"";?>>AR</option>
                       <option value="AR Backside" 	     <?php if ($_SESSION['PrescrData']['COATING']=="AR Backside") echo "selected=\"selected\"";?>>AR Backside</option>
                       <option value="AR+ETC"    <?php if ($_SESSION['PrescrData']['COATING']=="AR+ETC") echo "selected=\"selected\"";?>>AR+ETC</option>
                       <option value="XLR"        <?php if ($_SESSION['PrescrData']['COATING']=="XLR")    echo "selected=\"selected\"";?>>XLR</option>
                       <?php if ($_SESSION["CompteEntrepot"] =='yes'){  ?>
                       <option value="HD AR"      <?php if ($_SESSION['PrescrData']['COATING']=="HD AR")   				echo "selected=\"selected\"";?>>HD AR</option>
                       <option value="iBlu"      <?php if ($_SESSION['PrescrData']['COATING']=="iBlu")   				echo "selected=\"selected\"";?>>iBlu</option>
                       <option value="StressFree" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree")   		echo "selected=\"selected\"";?>>StressFree</option>
                       <option value="StressFree 32" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree 32")   		echo "selected=\"selected\"";?>>StressFree 32</option>
                       <option value="StressFree Noflex" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree Noflex")    echo "selected=\"selected\"";?>>StressFre Noflex</option>
                       <option value="SPF" <?php if ($_SESSION['PrescrData']['COATING']=="SPF")   						   	   echo "selected=\"selected\"";?>>SPF</option>
					   <?php } ?>
                     </select>
                   </span></td>
                   
                   
                   <?php if ($frameItem['collection']!='NURBS'){  ?>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                      <option value="None" selected="selected"><?php echo $lbl_polarized1;?></option>
                      <?php
					 $query="select polar from ifc_ca_exclusive group by polar asc"; /* select all openings */
					$result=mysql_query($query)	or die ("Could not select items");
					$usercount=mysql_num_rows($result);
					 while ($listItem=mysql_fetch_array($result)){
					 if ($listItem[polar]!="None"){
					 echo "<option value=\"$listItem[polar]\"";
					 if ($_SESSION['PrescrData']['POLAR']=="$listItem[polar]") 
					 echo "selected=\"selected\"";
					 echo ">";
					 $name=stripslashes($listItem[polar]);
					 echo "$name</option>";}}?>
                     </select>
                   </span></td>
             	  <?php }else{ //Nurbs special Polarized option ?>
                     <td align="right" class="formCell"><span class="tableSubHead"><?php echo 'Nurbs Polarized';?></span></td>
                    <td width="57" align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                     	<option value="None" selected="selected">None</option>
           		 		<option value="Brown" 	<?php   if ($_SESSION['PrescrData']['POLAR']=="Brown")    echo ' selected';?>>Brown</option>
                        <option value="Grey" 	<?php   if ($_SESSION['PrescrData']['POLAR']=="Grey")     echo ' selected';?>>Grey</option>
                        <option value="Drivewear"<?php   if ($_SESSION['PrescrData']['POLAR']=="Drivewear")     echo ' selected';?>>Drivewear</option>
                     </select>
                   </span></td>
                    <?php }//End Nurbs special Polarized option ?>
                   </tr>
               
                      
            <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead">
                   <?php if ($mylang == 'lang_french'){
                        echo 'Transitions';
                        }else {
                        echo 'Transitions';
                        }
                        ?>
                   </span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                      <select name="PHOTO" class="formText" id="PHOTO">
						<?php if ($mylang == 'lang_french'){ ?>
						   <option value="none" selected="selected">Aucun</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Action B") echo 'selected="selected"'; ?> value="Action B">Action B</option>           
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Blue") echo 'selected="selected"'; ?> value="Blue">Bleu</option> 
						   <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Brown") echo 'selected="selected"'; ?> value="Brown">Brun</option>  
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Day Nite") echo 'selected="selected"'; ?> value="Day Nite">Day Nite</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Drivewear") echo 'selected="selected"'; ?> value="Drivewear">Drivewear</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Extra Active Grey") echo 'selected="selected"'; ?> value="Extra Active Grey">Extra Active Grey</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Golf Green") echo 'selected="selected"'; ?> value="GOLf Green">Golf Green</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grey") echo 'selected="selected"'; ?> value="Grey">Gris</option>  
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grafite") echo 'selected="selected"'; ?> value="Grafite">Grafite</option>  
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Pink") echo 'selected="selected"'; ?> value="Pink">Rose</option> 
                        <?php }else{ ?>
						   <option value="none" selected="selected">None</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Action B") echo 'selected="selected"'; ?> value="Action B">Action B</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Blue") echo 'selected="selected"'; ?> value="Blue">Blue</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Brown") echo 'selected="selected"'; ?> value="Brown">Brown</option>        
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Day Nite") echo 'selected="selected"'; ?> value="Day Nite">Day Nite</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Drivewear") echo 'selected="selected"'; ?> value="Drivewear">Drivewear</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Extra Active Grey") echo 'selected="selected"'; ?> value="Extra Active Grey">Extra Active Grey</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Golf Green") echo 'selected="selected"'; ?> value="Golf Green">Golf Green</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grey")  echo 'selected="selected"'; ?> value="Grey">Grey</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grafite") echo 'selected="selected"'; ?> value="Grafite">Grafite</option>  
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Pink") echo 'selected="selected"'; ?> value="Pink">Pink</option>
                        <?php } ?>             
                 	</select>
                   </span></td>
                   
                   

                   
          <td width="50" align="right"  class="formCell"><span class="tableSubHead"> 
					 <?php if ($mylang == 'lang_french'){
						echo 'CatÃ©gorie de verres:';
						}else {
						echo 'Lens category';
						}
						?>
                </span></td>
                 
                   <td align="left"  class="formCellNosides"><span style="margin:11px">
                    <select name="lens_category">
 					<option  value="sv"        <?php if ($_SESSION['lens_category']=="sv")  echo 'selected="selected"'; ?>>SV</option>
                    <option  value="sv-glass"  <?php if ($_SESSION['lens_category']=="sv-glass")  echo 'selected="selected"'; ?>>SV Glass</option>
                     <option  value="ireader-entrepot"  <?php if ($_SESSION['lens_category']=="ireader-entrepot")  echo 'selected="selected"'; ?>>iReader</option>
                    </select>
                   </td>
                     




                   <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?> 
                  <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
					echo 'Courbure de base';
					}else {
					echo 'Base Curve';
					}
					?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  
                  <select name="BASE_CURVE" class="formText" id="BASE_CURVE">
                    	<option selected="selected" value="" >
					<?php if ($mylang == 'lang_french'){
					echo 'SÃ©lectionner';
					}else {
					echo 'Select Base Curve';
					}
					?></option>
                      	<option value="1" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="1") echo 'selected="selected"'; ?>>1</option>
                        <option value="2" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="2") echo 'selected="selected"'; ?>>2</option>
                        <option value="3" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="3") echo 'selected="selected"'; ?>>3</option>
                        <option value="4" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="4") echo 'selected="selected"'; ?>>4</option>
                        <option value="5" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="5") echo 'selected="selected"'; ?>>5</option>
                        <option value="6" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="6") echo 'selected="selected"'; ?>>6</option>
                        <option value="7" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="7") echo 'selected="selected"'; ?>>7</option>
                        <option value="8" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="8") echo 'selected="selected"'; ?>>8</option>                   
					</select></span>
                   </td>
                   <?php }else{ ?> 
                   <input type="hidden" name="base_curve" id="base_curve" value="" />
                   <td align="right" class="formCell"><span class="tableSubHead">&nbsp;</span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px">
                   &nbsp; </span></td>
                    <?php }//End IF compte = entrepot ?> 
            </tr>   
            
                   
               </table>
   </div>
            
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">

                 <tr>
                   <td colspan="8" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                 </tr>
                 
                 
                 
                                 
            <tr>     
    
                <?php if (strtolower($Cushion_available)=='yes') { ?>      
                <td colspan="2"  class="formCell">
                    <?php if ($mylang != 'lang_french'){?>
                    Cushion  ($<?php echo $cushion_selling_price; ?>)
                    <?php }else{?>
                    Coussinet (<?php echo $cushion_selling_price; ?>$)
                    <?php }?> 
                <input type="checkbox" id="cushion" name="cushion" <?php if ($_SESSION['PrescrData']['CUSHION']<> '') echo ' checked '; ?> value="<?php echo $cushion_ID; ?>"  />
                <input type="hidden" name="cushion_selling_price" id="cushion_selling_price" value="<?php echo $cushion_selling_price ;?>" />
                </td>
                <?php } ?> 
                
                
                
                 <?php if (strtolower($Dust_bar_available)=='yes') { ?>      
                <td colspan="2" class="formCell">
                    <?php if ($mylang != 'lang_french'){?>
                    Dust Bar  ($<?php echo $dust_bar_selling_price; ?>)
                    <?php
                     }else{?>
                    Pare-PoussiÃ¨re (<?php echo $dust_bar_selling_price; ?>$)
                    <?php
                     }?>
                &nbsp;&nbsp;&nbsp;<input type="checkbox" id="dust_bar" <?php if ($_SESSION['PrescrData']['DUST_BAR'] <> '') echo ' checked '; ?> name="dust_bar"  />
                  <input type="hidden" name="dust_bar_selling_price" id="dust_bar_selling_price" value="<?php echo $dust_bar_selling_price ;?>" />
                  </td>
                <?php } ?> 
            
            
           </tr>
                  
                 
                 
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_tint_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
             <select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                <option value="None"     <?php if ($_SESSION['PrescrData']['TINT']=="None")     echo "selected=\"selected\"";?>><?php echo $adm_none_txt;?></option>
                <option value="Solid"    <?php if ($_SESSION['PrescrData']['TINT']=="Solid")    echo "selected=\"selected\"";?>><?php echo $lbl_tint2_pl;?></option>
                <option value="Gradient" <?php if ($_SESSION['PrescrData']['TINT']=="Gradient") echo "selected=\"selected\"";?>><?php echo $adm_gradient_txt;?></option>
            </select>
                   </span></td>
               
                      <td align="left" class="formCellNosides"><?php echo $adm_from_txt;?>
                     <input name="FROM_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="FROM_PERC" value="<?php echo $_SESSION['PrescrData']['FROM_PERC'];?>" size="4" maxlength="4" />
                     %</td>
                   <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
					echo 'Ã€:';
					}else {
					echo 'To:';
					}
					?>
                     <input name="TO_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="TO_PERC" value="<?php echo $_SESSION['PrescrData']['TO_PERC'];?>" size="4" maxlength="4">
                     %</td>

                   <td align="left" class="formCellNosides"><?php echo $lbl_color_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                    <select name="TINT_COLOR" <?php if ($_SESSION['PrescrData']['TINT_COLOR'] == 'none') echo 'disabled="disabled"';  ?> class="formText"   id="TINT_COLOR">                                     
      				<?php if ($mylang == 'lang_french'){?>
                        <option value="" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="")           echo "selected=\"selected\"";?>>&nbsp;</option>
                        <option value="Brown" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Brown") echo "selected=\"selected\"";?>><?php echo 'Brown';?></option>
                        <option value="Grey" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Grey") echo "selected=\"selected\"";?>><?php echo 'Grey';?></option>
						  <option value="G-15" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="G-15") echo "selected=\"selected\"";?>><?php echo 'G-15';?></option>
                        <option value="Blue" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Blue") echo "selected=\"selected\"";?>><?php echo 'Blue';?></option>
							<?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>
                           <option value="Pine Green" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Pine Green")    echo "selected=\"selected\"";?>><?php echo 'Pine Green';?></option>
                            <option value="Serengetti" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Serengetti")    echo "selected=\"selected\"";?>><?php echo 'Serengetti';?></option>
                             <option value="SW010"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW010")    echo "selected=\"selected\"";?>><?php echo 'SW010';?></option>
                             <option value="SW027/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/50") echo "selected=\"selected\"";?>><?php echo 'SW027/50';?></option>
                             <option value="SW030/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/50") echo "selected=\"selected\"";?>><?php echo 'SW030/50';?></option>
                             <option value="SW051" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW051")    echo "selected=\"selected\"";?>><?php echo 'SW051';?></option>
                             <option value="SW035" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW035")    echo "selected=\"selected\"";?>><?php echo 'SW035';?></option>
                             <option value="GOL" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="GOL")      echo "selected=\"selected\"";?>><?php echo 'GOL';?></option>
                             <option value="SW015" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW015")    echo "selected=\"selected\"";?>><?php echo 'SW015';?></option>
                             <option value="RAV" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="RAV")      echo "selected=\"selected\"";?>><?php echo 'RAV';?></option>
                             <option value="SW034" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW034")    echo "selected=\"selected\"";?>><?php echo 'SW034';?></option>
                             <option value="SW012" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW012")    echo "selected=\"selected\"";?>><?php echo 'SW012';?></option>
                             <option value="SW023" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW023")    echo "selected=\"selected\"";?>><?php echo 'SW023';?></option>
                             <option value="SW046" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW046")    echo "selected=\"selected\"";?>><?php echo 'SW046';?></option>
                             <option value="SW025" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025")    echo "selected=\"selected\"";?>><?php echo 'SW025';?></option>
                             <option value="SW004" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW004")    echo "selected=\"selected\"";?>><?php echo 'SW004';?></option>
                             <option value="SW036" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW036")    echo "selected=\"selected\"";?>><?php echo 'SW036';?></option>
                             <option value="SW054" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW054")    echo "selected=\"selected\"";?>><?php echo 'SW054';?></option>
                             <option value="SW062"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW062")    echo "selected=\"selected\"";?>><?php echo 'SW062';?></option>
                             <option value="SW026" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW026")    echo "selected=\"selected\"";?>><?php echo 'SW026';?></option>
                             <option value="SW032" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW032")    echo "selected=\"selected\"";?>><?php echo 'SW032';?></option>
                             <option value="TEN" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="TEN")      echo "selected=\"selected\"";?>><?php echo 'TEN';?></option>
                             <option value="AZU" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="AZU")      echo "selected=\"selected\"";?>><?php echo 'AZU';?></option>
                             <option value="SW007" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW007")    echo "selected=\"selected\"";?>><?php echo 'SW007';?></option>
                             <option value="SW001" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW001")    echo "selected=\"selected\"";?>><?php echo 'SW001';?></option>
                             
                            <?php } ?>
                        
                        <?php }else {?>
                        <option value="" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="")           echo "selected=\"selected\"";?>>&nbsp;</option>
                        <option value="Brown" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Brown") echo "selected=\"selected\"";?>><?php echo 'Brown';?></option>
                        <option value="Grey" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Grey") echo "selected=\"selected\"";?>><?php echo 'Grey';?></option>
					   <option value="G-15" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="G-15") echo "selected=\"selected\"";?>><?php echo 'G-15';?></option>
                        <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>
                         	 <option value="Serengetti" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Serengetti")    echo "selected=\"selected\"";?>><?php echo 'Serengetti';?></option>
                             <option value="SW010"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW010")    echo "selected=\"selected\"";?>><?php echo 'SW010';?></option>
                             <option value="SW027/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/50") echo "selected=\"selected\"";?>><?php echo 'SW027/50';?></option>
                             <option value="SW030/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/50") echo "selected=\"selected\"";?>><?php echo 'SW030/50';?></option>
                             <option value="SW051" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW051")    echo "selected=\"selected\"";?>><?php echo 'SW051';?></option>
                             <option value="SW035" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW035")    echo "selected=\"selected\"";?>><?php echo 'SW035';?></option>
                             <option value="GOL" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="GOL")      echo "selected=\"selected\"";?>><?php echo 'GOL';?></option>
                             <option value="SW015" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW015")    echo "selected=\"selected\"";?>><?php echo 'SW015';?></option>
                             <option value="RAV" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="RAV")      echo "selected=\"selected\"";?>><?php echo 'RAV';?></option>
                             <option value="SW034" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW034")    echo "selected=\"selected\"";?>><?php echo 'SW034';?></option>
                             <option value="SW012" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW012")    echo "selected=\"selected\"";?>><?php echo 'SW012';?></option>
                             <option value="SW023" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW023")    echo "selected=\"selected\"";?>><?php echo 'SW023';?></option>
                             <option value="SW046" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW046")    echo "selected=\"selected\"";?>><?php echo 'SW046';?></option>
                             <option value="SW025" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025")    echo "selected=\"selected\"";?>><?php echo 'SW025';?></option>
                             <option value="SW004" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW004")    echo "selected=\"selected\"";?>><?php echo 'SW004';?></option>
                             <option value="SW036" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW036")    echo "selected=\"selected\"";?>><?php echo 'SW036';?></option>
                             <option value="SW054" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW054")    echo "selected=\"selected\"";?>><?php echo 'SW054';?></option>
                             <option value="SW062"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW062")    echo "selected=\"selected\"";?>><?php echo 'SW062';?></option>
                             <option value="SW026" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW026")    echo "selected=\"selected\"";?>><?php echo 'SW026';?></option>
                             <option value="SW032" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW032")    echo "selected=\"selected\"";?>><?php echo 'SW032';?></option>
                             <option value="TEN" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="TEN")      echo "selected=\"selected\"";?>><?php echo 'TEN';?></option>
                             <option value="AZU" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="AZU")      echo "selected=\"selected\"";?>><?php echo 'AZU';?></option>
                             <option value="SW007" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW007")    echo "selected=\"selected\"";?>><?php echo 'SW007';?></option>
                             <option value="SW001/25" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW001/25") echo "selected=\"selected\"";?>><?php echo 'SW001/25';?></option>
                             <option value="SW001" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW001")    echo "selected=\"selected\"";?>><?php echo 'SW001';?></option>
                            
                            <?php } ?>
                    <?php }?>   
					</select>  
                   </span></td>
                 </tr>
                 
                 
                 
                  <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>  
               <tr>
                   <td align="right" class="formCell">
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'Miroir';
				      	 }else{
					     echo 'Mirror';
					     }?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
 <select name="MIRROR" class="formText" id="MIRROR">
 <option value="none" 	  <?php if ($_SESSION['PrescrData']['MIRROR']=="None")       echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Aucun';  else echo 'None';?></option>
 <option disabled="disabled">Swiss Mirrors</option> 
 <option value="Aston" <?php if ($_SESSION['PrescrData']['MIRROR']=="Aston")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Aston';  else echo 'Aston';?></option>
 <option value="Balloon Blue" <?php if ($_SESSION['PrescrData']['MIRROR']=="Balloon Blue")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Balloon Blue';  else echo 'Balloon Blue';?></option>
 <option value="Canyon"<?php if ($_SESSION['PrescrData']['MIRROR']=="Canyon") echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Canyon'; else echo 'Canyon';?></option>
 <option value="Dona" <?php if ($_SESSION['PrescrData']['MIRROR']=="Dona")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Dona';  else echo 'Dona';?></option>    
 <option value="Ocean Flash" <?php if ($_SESSION['PrescrData']['MIRROR']=="Ocean Flash")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Ocean Flash';  else echo 'Ocean Flash';?></option> 
 <option value="Pasha Silver" <?php if ($_SESSION['PrescrData']['MIRROR']=="Pasha Silver")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Pasha Silver';  else echo 'Pasha Silver';?></option>
 <option value="Pink Panther" <?php if ($_SESSION['PrescrData']['MIRROR']=="Pink Panther")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Pink Panther';  else echo 'Pink Panther';?></option>
 <option value="Sahara" <?php if ($_SESSION['PrescrData']['MIRROR']=="Sahara")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Sahara';  else echo 'Sahara';?></option>
 <option value="Tank" <?php if ($_SESSION['PrescrData']['MIRROR']=="Tank")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Tank';  else echo 'Tank';?></option>     
  <option value="Pine Green" <?php if ($_SESSION['PrescrData']['MIRROR']=="Pine Green")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Pine Green';  else echo 'Pine Green';?></option>
  
 <option disabled="disabled">Essilor Mirrors</option>  
 <option value="Gold"  <?php if ($_SESSION['PrescrData']['MIRROR']=="Gold")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Gold';  else echo 'Gold';?></option>  
 <option value="Green" <?php if ($_SESSION['PrescrData']['MIRROR']=="Green")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Green';  else echo 'Green';?></option>  
 <option value="Ocean Blue"  <?php if ($_SESSION['PrescrData']['MIRROR']=="Ocean Blue")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Ocean Blue';  else echo 'Ocean Blue';?></option>  
 <option value="Red"  <?php if ($_SESSION['PrescrData']['MIRROR']=="Red")     echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Red';  else echo 'Red';?></option>  
 <option value="Silver"  <?php if ($_SESSION['PrescrData']['MIRROR']=="Silver")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Silver';  else echo 'Silver';?></option>  
 <option value="Yellow"  <?php if ($_SESSION['PrescrData']['MIRROR']=="Yellow")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Yellow';  else echo 'Yellow';?></option>  
 </select>
                   </span></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                 </tr>
               
                 
                 <?php }//end if ?>



                 
                 
                 
                 
             <tr>
                   <td align="right" class="formCell"><?php echo 'O.C.';?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
            <input name="OPTICAL_CENTER" type="text" class="formText" id="OPTICAL_CENTER" value="<?php echo $_SESSION['PrescrData']['OPTICAL_CENTER'];?>" size="4" maxlength="4">
                   </span></td>
                   <td colspan="1">&nbsp;</td>
                <td align="right" class="formCell"><?php if ($mylang == 'lang_french'){
					 	 echo 'Initiales (Produits Swiss Seulement)';
				      	 }else{
					     echo 'Initials (Swiss Products Only)';
					     }?> </td>
                     <td align="left" class="formCellNosides">&nbsp;
                     <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" size="4" maxlength="8" /></td>
              </tr>    
                 
               </table>
             </div>
             
        <div>
       <?php if($Charge_Dispensing_Fee=='yes'){ ?>
       <input type="hidden" checked id="DISPENSING_FEE_SV" value="20" name="DISPENSING_FEE_SV"  />
        <?php } ?>
       </div> 
             
             
     
             
			  <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td width="10" align="center" class="formCellNosides">&nbsp;</td>
                   <td width="17" align="center" class="formCellNosides">&nbsp;</td>
                   <td width="17" align="center" class="formCellNosidest">&nbsp;</td>
                   <td width="76" align="center" class="formCellNosides"> 
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.D.';
					     }?><br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" value="<?php echo $_SESSION['PrescrData']['RE_PD'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td width="98" align="center" class="formCellNosides"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td width="91" align="center" class="formCellNosides">
                    <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.G.';
					     }?><br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" value="<?php echo $_SESSION['PrescrData']['LE_PD'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                 
                 
                 <td align="center" class="formCellNosides"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['RE_PD_NEAR']>0) echo  $_SESSION['PrescrData']['RE_PD_NEAR'];?>" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/direct-lens/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PD_NEAR']>0) echo  $_SESSION['PrescrData']['LE_PD_NEAR'];?>"/>
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>

                 </tr>
               </table>
             </div>
             
           
           <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#17A2D2" class="tableHead">
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'INDIVIDUALISE IFREE SEULEMENT';
				      	 }else{
					     echo 'INDIVIDUALISEZ IFREE ONLY';
					     }?></td>
                   </tr>
                    <tr>
                <td align="right" class="formCell"><?php echo $lbl_pt_txt_pl;?></td>
                  <td align="left" class="formCellNosides">&nbsp;  <input name="PT" type="text" class="formText" id="PT" value="<?php echo $_SESSION['PrescrData']['PT'];?>" size="2" maxlength="2" />
                    
                    <?php echo $lbl_pt1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $lbl_pa_txt_pl;?>&nbsp;</td>
                  <td align="left" class="formCellNosides"><input name="PA" type="text" class="formText" id="PA" value="<?php echo $_SESSION['PrescrData']['PA'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_pa1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $adm_vertex_txt;?></td>
                  <td align="left" class="formCellNosides"><input name="VERTEX" type="text" class="formText" id="VERTEX" value="<?php echo $_SESSION['PrescrData']['VERTEX'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_vertex1_pl;?>&nbsp;</td>
                  </tr>
               </table>
             </div>   
           
             

  		 <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
             <tr>
                <td colspan="10" bgcolor="#17A2D2" class="tableHead">
				<?php if ($mylang == 'lang_french'){
				echo 'Ã‰PAISSEUR SPÃ‰CIALES';
				}else {
				echo 'SPECIAL THICKNESS';
				}
				?>&nbsp;
                </td>
                 </tr>
                 <tr>
                   <td align="left" class="formCell">RE CT</td>
                   <td align="left" class="formCellNosides"><input name="RE_CT" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['RE_CT'];?>"  id="RE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE CT</td>
                   <td align="left" class="formCellNosides"><input name="LE_CT" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['LE_CT'];?>" id="LE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
               
                   <td align="left" class="formCell">RE ET</td>
                   <td align="left" class="formCellNosides"><input name="RE_ET" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['RE_ET'];?>" id="RE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE ET</td>
                   <td align="left" class="formCellNosides"><input name="LE_ET" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['LE_ET'];?>" id="LE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                 </tr>
                   </table>
             </div>





   <div>
           <?php   
		   $AfficherFrame = true;
		   
		  $AfficherShapeModel = false;
		   if ($_REQUEST['prod'] == '463')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '464')	  $AfficherShapeModel = true;	
		   if ($_REQUEST['prod'] == '465')	  $AfficherShapeModel = true;	
		   if ($_REQUEST['prod'] == '501')	  $AfficherShapeModel = true; 
		   if ($_REQUEST['prod'] == '1461')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1462')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1463')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1664')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1673')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1672')	  $AfficherShapeModel = true;
	 	   if ($_REQUEST['prod'] == '1671')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1670')	  $AfficherShapeModel = true;
 	 	   if ($_REQUEST['prod'] == '1669')	  $AfficherShapeModel = true;
           if ($_REQUEST['prod'] == '1668')	  $AfficherShapeModel = true;
 		   if ($_REQUEST['prod'] == '1667')	  $AfficherShapeModel = true;
   	 	   if ($_REQUEST['prod'] == '1666')	  $AfficherShapeModel = true;
	  	   if ($_REQUEST['prod'] == '1665')	  $AfficherShapeModel = true;
	       if ($_REQUEST['prod'] == '1664')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1673')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1674')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1976')	  $AfficherShapeModel = true;
 		   if ($_REQUEST['prod'] == '2185')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '2292')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '1892')	  $AfficherShapeModel = true;
		   if ($_REQUEST['prod'] == '3173')	  $AfficherShapeModel = true;


	  if ($AfficherFrame)
		   {
		   ?>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
             <tr>
                    <td colspan="9" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides">                <?php echo $lbl_a_txt_pl;?>
                     &nbsp;
                     <input name="FRAME_A" type="text" class="formText" 
                     value="<?php
					  if ($_SESSION['PrescrData']['FRAME_A'] <> '')
					  	echo $_SESSION['PrescrData']['FRAME_A'];
					  elseif($frameItem[frame_a] <> '')
					  	echo $frameItem[frame_a]; 
						?>" id="FRAME_A" size="4" maxlength="4" />
                     &nbsp;
                     
                     <?php echo $lbl_b_txt_pl;?>
                     <input name="FRAME_B" type="text" class="formText"  value="<?php
					  if ($_SESSION['PrescrData']['FRAME_B'] <> '')
					  	echo $_SESSION['PrescrData']['FRAME_B'];
					  elseif($frameItem[frame_b] <> '')
					  	echo $frameItem[frame_b]; 
						?>" id="FRAME_B" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_ed_txt_pl;?>
                     <input name="FRAME_ED" type="text" class="formText"  value="<?php
					  if ($_SESSION['PrescrData']['FRAME_ED'] <> '')
					  	echo $_SESSION['PrescrData']['FRAME_ED'];
					  elseif($frameItem[frame_ed] <> '')
					  	echo $frameItem[frame_ed]; 
						?>" id="FRAME_ED" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_dbl_txt_pl;?>
                     <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL"  value="<?php
					  if ($_SESSION['PrescrData']['FRAME_DBL'] <> '')
					  	echo $_SESSION['PrescrData']['FRAME_DBL'];
					  elseif($frameItem[frame_dbl] <> '')
					  	echo $frameItem[frame_dbl]; 
						?>" size="4" maxlength="4" />
                    
                     
                     <?php
					if ($AfficherShapeModel==true) {?>
                    &nbsp;&nbsp;Frame Model:<input name="FRAME_MODEL" type="input" id="FRAME_MODEL" value=""/>
					<?php } ?>
                    
                     </td>
                      <td align="right" class="formCell"><?php echo $lbl_type_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCell">
                 <select name="FRAME_TYPE" class="formText"  id="FRAME_TYPE" onchange="validerFrameType()">
                 <option value=""><?php echo $lbl_type1_pl;?></option>
                 <option value="Nylon Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=='Nylon Groove')echo ' selected'; ?>><?php echo $lbl_type2_pl;?></option>					 				
                 <option value="Metal Groove" <?php if ($_SESSION['PrescrData']['FRAME_TYPE']=='Metal Groove')echo ' selected'; ?>><?php echo $lbl_type3_pl;?></option>
                 <option value="Plastic" <?php if ($_SESSION['PrescrData']['FRAME_TYPE']=='Plastic')echo ' selected'; ?>><?php echo $lbl_type4_pl;?></option>
                 <option value="Metal" <?php if ($_SESSION['PrescrData']['FRAME_TYPE']=='Metal')echo ' selected'; ?>><?php echo $lbl_type5_pl;?></option>
                 <option value="Drill and Notch" <?php if ($_SESSION['PrescrData']['FRAME_TYPE']=='Drill and Notch')echo ' selected'; ?>><?php echo $lbl_type7_pl;?></option>
                   </select>                    </td>
                   </tr>
                   </table>
                   <?php  
				   }else {
				   
				  ?>
                  
                  <input name="FRAME_TYPE" type="hidden" id="FRAME_TYPE" value="<?php echo $frameItem['material']; ?>" /> 
                  
                  <?php
				   }
		   ?>   
          </div>
          
          
          
          
          
          
           <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>    
       <div>    
       <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
            
                 <tr>
                   <td colspan="1" align="center" class="formCell"><?php echo $lbl_jobtype_txt_pl;?>
                     <select name="JOB_TYPE" class="formText" d="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Edge and Mount" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Edge and Mount") echo "selected=\"selected\"";?>><?php echo $lbl_jobtype2_pl;?></option>
                       <option value="remote edging" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="remote edging") echo "selected=\"selected\"";?>>
						<?php if ($mylang == 'lang_french'){
						echo 'TaillÃ© Non montÃ©';
						}else {
						echo 'Remote Edging';
						}
						?></option>
                       </select>
                    </td>
                    
                    <td colspan="3" align="center" class="formCell">
                    <?php if ($mylang == 'lang_french'){
						echo 'Polir les biseaux';
						}else {
						echo 'Edge Polish';
						}
						?>
                        &nbsp;&nbsp;<input type="checkbox"  name="EDGE_POLISH" id="EDGE_POLISH" <?php  if ($_SESSION['PrescrData']['EDGE_POLISH']=='yes') echo 'checked="checked"';?> value="yes" />
                    </td>
                   </tr>
                   </table>
       </div>    
       <?php } ?>       
           
                
              <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#17A2D2" class="tableHead">    
				<?php if ($mylang == 'lang_french'){
				echo 'Garantie';
				}else {
				echo 'Extra Warranty';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="WARRANTY" class="formText" id="WARRANTY">
                     
                       <option value="0" selected="selected"  <?php  if ($_SESSION['PrescrData']['WARRANTY']==0) echo 'selected'; ?>>
                       <?php if ($mylang == 'lang_french'){
						echo 'Aucune';
						}else {
						echo 'None';
						}
						?>
                       </option>
                       
                       <option value="1"  <?php  if ($_SESSION['PrescrData']['WARRANTY']==1) echo 'selected'; ?>>
                       <?php if ($mylang == 'lang_french'){
						echo '1 an (extra 6$)';
						}else {
						echo ' 1 year (6$ extra)';
						}
						?>
                       </option>
                       
                       <option value="2"  <?php  if ($_SESSION['PrescrData']['WARRANTY']==2) echo 'selected'; ?>>
                      
                       <?php if ($mylang == 'lang_french'){
						echo ' 2 ans (extra 10$)';
						}else {
						echo ' 2 years (10$ extra)';
						}
						?>
                       </option> 
                     </select>
                     </span></td>
                 </tr>
               </table>
             </div> 
             
             
             

             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">
                   <input type="text" name="SPECIAL_INSTRUCTIONS" size="80" class="formText" id="SPECIAL_INSTRUCTIONS" value="<?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']?>">
                   </td>
                 </tr>
                 
                <?php if ($_SESSION["CompteEntrepot"] == 'yes') {?> 
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo 'Note Interne (Peut contenir du français)';?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">
                   <input type="text" name="INTERNAL_NOTE" size="80" class="formText" id="INTERNAL_NOTE" value="<?php echo $_SESSION['PrescrData']['INTERNAL_NOTE']?>">
                   </td>
                 </tr>
                 <?php } ?>
               </table>
        </div>
               
         
       	
            
              <div>
           	<?php
            if ($DataBasket[nbrResult] > 0){
				$AfficherPageCommande = false;
					if ($mylang == 'lang_french') { 
						echo '<p align="center">Pour pouvoir commander des ensemble verres et montures , veuillez d\'abord terminer les commandes de monture qui sont actuellement dans votre panier d\'achat.</p>';
					}else{ 
						echo '<p align="center">To order some packages, please process the stock frame orders that are already in your basket.</p>';
					} 		
			}
			?>
        </div>
            
            <?php if ($AfficherPageCommande){ ?> 
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		     <?php /*?> <input name="Reset" type="button" value="<?php echo $btn_reset_txt;?>"  onclick="window.open('lens_cat_selection.php', '_top')"/><?php */?>
            <?php /*?> <input name="back" type="reset" value="<?php echo $btn_reset_txt;?>" /><?php */?>
            
             &nbsp;
              <a href="destroy.php">Reset</a>&nbsp;&nbsp;&nbsp;
		    
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
            <?php } ?> 
            
            
 <?php if ( $_SESSION["product_line"]=='safety'){//Client de l'entrepot est connecté dans un compte SAFE, on doit l'aviser ?>
  <div>
           	<?php
					if ($mylang == 'lang_french') { 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>Vous êtes présentement connectés dans un compte SAFE:' . $_SESSION["sessionUser_Id"].', veuillez vous reconnecter dans <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					}else{ 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>You are currently logged in a SAFETY account: ' . $_SESSION["sessionUser_Id"].', Please re-connect in <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					} 		

			?>
   </div>


<?php 
}//End IF Client est dans un compte SAFE   ?>            
            
            
<input name="SUPPLIER" type="hidden" id="SUPPLIER" value="<?php echo $frameItem['collection']; ?>"/>
<?php
if ($AfficherShapeModel==false) {?>
<input name="FRAME_MODEL" type="hidden" id="FRAME_MODEL" value="<?php echo $frameItem['frame_shape']; ?>"/>
<?php } ?>
<input name="PACKAGE" type="hidden" id="PACKAGE" value="<?php echo $frameItem['misc_unknown_purpose']; ?>"/>
<input name="TEMPLE_MODEL" type="hidden" id="TEMPLE_MODEL" value="<?php echo $frameItem['code']; ?>"/>
<input name="COLOR" type="hidden" id="COLOR" value="<?php echo $frameItem['color_en']; ?>" /> 


<?php  if ($_SESSION["CompteEntrepot"] == 'no') {?>
<input name="JOB_TYPE" type="hidden" id="JOB_TYPE" value="Edge and Mount" /> 
<?php  }?>

<input name="ORDER_TYPE" type="hidden" id="ORDER_TYPE" value="Provide" /> 
 </form>
 
 
<form method="post"  enctype="multipart/form-data" action="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/" name="formShape" id="formShape" target="_blank">
<?php          	
//Code pour uploader sur S3
if (!class_exists('S3')) require_once '../s3/S3.php';
	
// AWS access info
// TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
if (!defined('awsAccessKey')) define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));
if (!defined('awsSecretKey')) define('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
  // TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
	"define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));\ndefine('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));\n\n");

S3::setAuth(awsAccessKey, awsSecretKey);



//Dans quel Bucket Uploader ces fichiers
$bucket = 'direct-lens-public';
$path = 'Shapes/'; // Dans quel dossier

$lifetime = 3600; // Period for which the parameters are valid
$maxFileSize = (1024 * 1024 * 50); // 50 MB



$metaHeaders = array('uid' => 123);
$requestHeaders = array(
    'Content-Type'        => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename=${filename}'
);

$sucess_action_redirect= constant('DIRECT_LENS_URL').'/ifcopticclubca/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement

$params = S3::getHttpUploadPostParams(
    $bucket,
    $path,
    S3::ACL_PUBLIC_READ,
    $lifetime,
    $maxFileSize,
    $sucess_action_redirect, // Or a URL to redirect to on success
    $metaHeaders,
    $requestHeaders,
    false // False since we're not using flash
);

foreach ($params as $p => $v)
	echo "        <input type=\"hidden\" name=\"{$p}\" value=\"{$v}\" />\n";
?>

   
             <div id="spherechoice" >
			<table width="770" align="center">
            <tr bgcolor="#17A2D2">
                   <td width="134" align="center" valign="top"  class="tableHead">
				   <?php if ($mylang == 'lang_french'){
					echo 'ENVOYER UNE TRACE';
					}else {
					echo 'UPLOAD A SHAPE';
					}
					?></td>
              <tr>
                <td colspan="7" align="right" valign="top" bgcolor="#FFFFFF"class="formCell">

               <div id="uploaderdiv" style="width:400px; margin:0 auto; text-align: center;">
               <?php 
			   $DisableUploadButton = 'no';
			   if ($_SESSION['PrescrData']['myupload'] <> '') 
			   {
			   echo 'Shape Uploaded: ' . $_SESSION['PrescrData']['myupload'];
			   $DisableUploadButton = 'yes';
			   }
			    ?>
                 <p>
                   <input type="file"  onclick="btnupload.disabled=false;btnupload.value='Upload'" name="file" id="file" size="40" >&nbsp;
                   <input type="submit" value="Upload" id="btnupload"   onclick="this.disabled=true;this.value='Uploaded';this.form.submit();" <?php if ($DisableUploadButton =='yes') echo ' disabled ';  ?>  />
                  </p>
				</div>
                 </td></tr>               
              </table>
              

               </div>
            </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->

</body>
</html>