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
include("includes/pw_functions.inc.php");
global $drawme;
//require_once "../upload/phpuploader/include_phpuploader.php";

$_SESSION['REFERRER']="prescription_frame.php?prod=".$_GET['prod'];// CATCH FOR RETRY

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
$CompteEntrepot = 'no';
switch($_SESSION["sessionUser_Id"]){
	case 'entrepotsafe': 	 $CompteEntrepot = 'yes';
	case 'safedr': 			 $CompteEntrepot = 'yes';
	case 'lavalsafe': 		 $CompteEntrepot = 'yes';
	case 'levissafe': 		 $CompteEntrepot = 'yes';
	case 'terrebonnesafe': 	 $CompteEntrepot = 'yes';
	case 'warehousestcsafe': $CompteEntrepot = 'yes';
	case 'warehousehalsafe': $CompteEntrepot = 'yes';	
	case 'sherbrookesafe':   $CompteEntrepot = 'yes';
	case 'longueuilsafe':    $CompteEntrepot = 'yes';
}
  
mysqli_query($con,"SET CHARACTER SET UTF8");
 
 $querySafetyPlan  		= "SELECT safety_plan, charge_dispensing_fee FROM accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
 $SafetyPlanResult 		= mysqli_query($con,$querySafetyPlan)	or die ("ERROR:");
 $DataSafetyPlan   		= mysqli_fetch_array($SafetyPlanResult,MYSQLI_ASSOC);
 $SafetyPlan      		= $DataSafetyPlan[safety_plan];
 $Charge_Dispensing_Fee = $DataSafetyPlan[charge_dispensing_fee];
 $_SESSION[safety_plan] = $SafetyPlan;
//interco price  , regular price ou discounted price


 
 if ($_GET['prod']!=""){
	$frameQuery = "SELECT * FROM safety_frames_french WHERE safety_frames_id='$_GET[prod]'";
	$frameResult=mysqli_query($con,$frameQuery)	or die ("ERROR:");
	$frameItem=mysqli_fetch_array($frameResult,MYSQLI_ASSOC);
	$frameItem[prod_tn]="http://www.direct-lens.com/safety/frames_images/".$frameItem[image];
	$frameItem[prod_tn]="http://www.direct-lens.com/safety/frames_images/".$frameItem['image'];	
	$Removable_side_shield_available = 'No';
	$Cushion_available 			     = 'No';
	$Dust_bar_available				 = 'No';
	if (strtolower($frameItem[removable_side_shield_available]) =='yes'){
		$Removable_side_shield_available = 'Yes';
		$Removable_side_shield_available_EN = 'Yes';
		$Removable_side_shield_available_FR = 'Oui';
		$Removable_side_shield_ID        = $frameItem[removable_side_shield_ID];
		
		if ($SafetyPlan=='regular price'){
		$Removable_side_shield_price     = $frameItem[removable_side_shield_selling_price] ;
		}elseif($SafetyPlan=='interco price'){
		$Removable_side_shield_price     = $frameItem[removable_side_shield_interco] ;
		}elseif($SafetyPlan=='discounted price'){
		$Removable_side_shield_price     = $frameItem[removable_side_shield_discounted_price] ;
		}
				
	}else{
		$Removable_side_shield_available_EN = 'No';
		$Removable_side_shield_available_FR = 'Non';	
	}
	
	if (substr($frameItem[upc],4,1) =='P'){
	$Permanent_side_shield_include_EN = 'Yes';
	$Permanent_side_shield_include_FR = 'Oui';
	}else{
	$Permanent_side_shield_include_EN = 'No';
	$Permanent_side_shield_include_FR = 'Non';
	}
	
	if (strtolower($frameItem[cushion_available]) =='yes'){
		$Cushion_available     =   'Yes';
		$Cushion_available_EN  =   'Yes';
		$Cushion_available_FR  =   'Oui';
		$cushion_ID            =   $frameItem[cushion_ID];
		if ($SafetyPlan=='regular price'){
		$cushion_selling_price     = $frameItem[cushion_selling_price] ;
		}elseif($SafetyPlan=='interco price'){
		$cushion_selling_price     = $frameItem[cushion_interco] ;
		}elseif($SafetyPlan=='discounted price'){
		$cushion_selling_price     = $frameItem[cushion_discounted_price] ;
		}
		
	}else{
		$Cushion_available_EN  =   'No';
		$Cushion_available_FR  =   'Non';	
	}
	
	if (strtolower($frameItem[dust_bar_available]) =='yes'){
		$Dust_bar_available     = 'Yes';
		$Dust_bar_available_EN  = 'Yes';
		$Dust_bar_available_FR  = 'Oui';
		
		if ($SafetyPlan=='regular price'){
		$dust_bar_selling_price     = $frameItem[dust_bar_selling_price] ;
		}elseif($SafetyPlan=='interco price'){
		$dust_bar_selling_price     = $frameItem[dust_bar_interco] ;
		}elseif($SafetyPlan=='discounted price'){
		$dust_bar_selling_price     = $frameItem[dust_bar_discounted_price] ;
		}
	}else{
		$Dust_bar_available_EN  = 'No';
		$Dust_bar_available_FR  = 'Non';	
	}
	
 }
	
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
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">

<?php if (($mylang == 'lang_french') && ($CompteEntrepot == 'no')){ ?>
<form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validate(this);">
<?php }elseif(($mylang == 'lang_english') && ($CompteEntrepot == 'no')) { ?>
<form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateFr(this);">
<?php }elseif($CompteEntrepot == 'yes'){ ?>
<form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateEntrepotSafe(this);">
<?php }//End IF ?>

   <input type="hidden" name="mylang" id="mylang" value="<?php echo $mylang ;?>" />

		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic">
             <?php if ($mylang == 'lang_french'){ ?>
              <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" />
              <?php }else{ ?>
              <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" />
              <?php } ?>
              </div></td></tr></table>
		      <div>
              
				<?php if ($mylang == 'lang_french'){
					
					echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>
					<td bgcolor="#ee7e32" colspan="2" class="tableHead">MONTURE</td>
					</tr>';
					echo "<tr><td><img src=\"$frameItem[prod_tn]\" alt=\"$frameItem[model]\" border=\"0\" title=\"$frameItem[model]\" width=\"300\" ></td ><td >";
					echo "<div class=\"frame-specs\" ><b>MODELE : $frameItem[upc]</b></div>";
					
					/*if ($frameItem['collection']=='NURBS'){
					 	if(strtolower($frameItem['frame_shape_en'])=='square wrap')
							echo '<img src="http://www.direct-lens.com/safety/design_images/f.jpg" alt="Square frame" border="0" title="Square frame" width="130" >';
						if(strtolower($frameItem['frame_shape_en'])=='oval wrap')
							echo '<img src="http://www.direct-lens.com/safety/design_images/oval.jpg" alt="Oval Frame" border="0" title="Oval Frame" width="130" >';
					}*/
      
					echo "<div class=\"frame-specs\" ><b>TYPE :</b> $frameItem[type]</div>";
					echo "<div class=\"frame-specs\" ><b>GENRE :</b> $frameItem[gender]</div>";
					echo "<div class=\"frame-specs\" ><b>MATIERE :</b> $frameItem[material]</div>";
					echo "<div class=\"frame-specs\" ><b>COULEUR :</b> $frameItem[color]</div>";
					echo "<div class=\"frame-specs\" ><b>TAILLE :</b> $frameItem[boxing]</div>";
					//echo "<div class=\"frame-specs\" ><b>DESCRIPTION :</b> $frameItem[additionnal_description]</div>";
					echo "<div class=\"frame-specs\" ><b>PROTECTION LATÉRALE AMOVIBLE :</b> $Removable_side_shield_available_FR</div>";
					echo "<div class=\"frame-specs\" ><b>PROTECTION LATÉRALE PERMANENT :</b> $Permanent_side_shield_include_FR</div>";
					echo "<div class=\"frame-specs\" ><b>COUSSINET :</b> $Cushion_available_FR</div>";
					echo "<div class=\"frame-specs\" ><b>PARE-POUSSIÈRE :</b> $Dust_bar_available_FR</div>";
					
					echo "</td></tr></table>";
					
                }else {
					
					echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>
					<td bgcolor="#ee7e32" colspan="2" class="tableHead">FRAME</td>
					</tr>';
					echo "<tr><td><img src=\"$frameItem[prod_tn]\" alt=\"$frameItem[model]\" border=\"0\" title=\"$frameItem[model_en]\" width=\"300\" ></td ><td >";
					echo "<div class=\"frame-specs\" ><b>MODEL: $frameItem[upc]</b></div>";
					
					echo "<div class=\"frame-specs\" ><b>TYPE:</b> $frameItem[type_en]</div>";
					echo "<div class=\"frame-specs\" ><b>GENDER:</b> $frameItem[gender_en]</div>";
					echo "<div class=\"frame-specs\" ><b>MATERIAL:</b> $frameItem[material_en]</div>";
					echo "<div class=\"frame-specs\" ><b>COLOUR:</b> $frameItem[color_en]</div>";
					echo "<div class=\"frame-specs\" ><b>SIZE:</b> $frameItem[boxing]</div>";
					//echo "<div class=\"frame-specs\" ><b>DESCRIPTION :</b> $frameItem[additionnal_description]</div>";
					echo "<div class=\"frame-specs\" ><b>REMOVABLE SIDE SHIELD AVAILABLE :</b> $Removable_side_shield_available_EN</div>";
					echo "<div class=\"frame-specs\" ><b>PERMANENT SIDE SHIELD INCLUDE :</b> $Permanent_side_shield_include_EN</div>";
					echo "<div class=\"frame-specs\" ><b>CUSHION AVAILABLE :</b> $Cushion_available_EN</div>";
					echo "<div class=\"frame-specs\" ><b>DUST BAR AVAILABLE :</b> $Dust_bar_available_EN</div>";
					
					echo "</td></tr></table>";
					
                }?>                   

                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                <input type="hidden" name="frame_selling_price" id="frame_selling_price" value="<?php echo $frameItem[frame_selling_price];?>" />
  				<input type="hidden" name="frame_interco" id="frame_interco" value="<?php echo $frameItem[frame_interco];?>" />
                <input type="hidden" name="frame_discounted_price" id="frame_discounted_price" value="<?php echo $frameItem[frame_discounted_price];?>" />
                  <tr >
                    <td colspan="3" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_submast_client;?></td>
                    <td bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_refnum_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_slsperson_txt;?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php echo $_SESSION['prFormVars']['LAST_NAME'];?>" size="25" /></td>
                    <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" value="<?php echo $_SESSION['prFormVars']['FIRST_NAME'];?>" size="25" /></td>
                    <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" value="<?php echo $_SESSION['prFormVars']['PATIENT_REF_NUM'];?>" size="10" /></td>
                    <td class="formCellNosides"><input name="SALESPERSON_ID" type="text" class="formText" id="SALESPERSON_ID" value="<?php echo $_SESSION['prFormVars']['SALESPERSON_ID'];?>" size="15" maxlength="15" /></td>
                    </tr>
                </table>
              </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                 <tr >
                   <td colspan="7" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_prescription_txt_pl;?>
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
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/ifcopticclub/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="OD = OG" /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php //echo $lbl_re_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                     <option value="+8"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['prFormVars']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['prFormVars']['RE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
                     <option value="-10"<?php if ($_SESSION['prFormVars']['RE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                     </select>
                     <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['prFormVars']['RE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['prFormVars']['RE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['prFormVars']['RE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['prFormVars']['RE_SPH_DEC']==".00")||(strlen($_SESSION['prFormVars']['RE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                    <option value="-0" <?php if ($_SESSION['prFormVars']['RE_CYL_NUM']==="-0") echo "selected=\"selected\"";?>>-0</option>
                    <option value="-1" <?php if ($_SESSION['prFormVars']['RE_CYL_NUM']==="-1") echo "selected=\"selected\"";?>>-1</option>
                    <option value="-2" <?php if ($_SESSION['prFormVars']['RE_CYL_NUM']==="-2") echo "selected=\"selected\"";?>>-2</option>
                    <option value="-3" <?php if ($_SESSION['prFormVars']['RE_CYL_NUM']==="-3") echo "selected=\"selected\"";?>>-3</option>
                    <option value="-4" <?php if ($_SESSION['prFormVars']['RE_CYL_NUM']==="-4") echo "selected=\"selected\"";?>>-4</option>
                   </select>
                     <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['prFormVars']['RE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['prFormVars']['RE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['prFormVars']['RE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['prFormVars']['RE_CYL_DEC']==".00")||(strlen($_SESSION['prFormVars']['RE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                   </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['prFormVars']['RE_AXIS'];?>" size="4" maxlength="3"  />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
                     <option value="+3.50"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+3.50") echo "selected=\"selected\"";?>>+3.50</option>
                     <option value="+3.25"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+3.25") echo "selected=\"selected\"";?>>+3.25</option>
                     <option value="+3.00"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+3.00") echo "selected=\"selected\"";?>>+3.00</option>
                     <option value="+2.75"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+2.75") echo "selected=\"selected\"";?>>+2.75</option>
                     <option value="+2.50"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+2.50") echo "selected=\"selected\"";?>>+2.50</option>
                     <option value="+2.25"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+2.25") echo "selected=\"selected\"";?>>+2.25</option>
                     <option value="+2.00"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+2.00") echo "selected=\"selected\"";?>>+2.00</option>
                     <option value="+1.75"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+1.75") echo "selected=\"selected\"";?>>+1.75</option>
                     <option value="+1.50"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+1.50") echo "selected=\"selected\"";?>>+1.50</option>
                     <option value="+1.25"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+1.25") echo "selected=\"selected\"";?>>+1.25</option>
                     <option value="+1.00"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+1.00") echo "selected=\"selected\"";?>>+1.00</option>
                     <option value="+0.75"<?php if ($_SESSION['prFormVars']['RE_ADD']=="+0.75") echo "selected=\"selected\"";?>>+0.75</option>
                     <option value="+0.00" <?php if (($_SESSION['prFormVars']['RE_ADD']=="+0.00")||(strlen($_SESSION['prFormVars']['RE_ADD'])<2)) echo "selected=\"selected\"";?>>+0.00</option>
                   </select></td>
                   <td align="right" valign="top"class="formCell">
                     <input name="RE_PR_IO" type="radio" value="In" />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="Out" />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" /><br />
                     <input name="RE_PR_UD" type="radio" value="Up" />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="Down" />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4" /></td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php //echo $lbl_le_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                     <option value="+8"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['prFormVars']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['prFormVars']['LE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
				     <option value="-10"<?php if ($_SESSION['prFormVars']['LE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                   </select>
                     <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['prFormVars']['LE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['prFormVars']['LE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['prFormVars']['LE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['prFormVars']['LE_SPH_DEC']==".00")||(strlen($_SESSION['prFormVars']['LE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">
                     <option value="-0"<?php if ($_SESSION['prFormVars']['LE_CYL_NUM']=="-0") echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['prFormVars']['LE_CYL_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['prFormVars']['LE_CYL_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['prFormVars']['LE_CYL_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['prFormVars']['LE_CYL_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                   </select>
                     <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['prFormVars']['LE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['prFormVars']['LE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['prFormVars']['LE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['prFormVars']['LE_CYL_DEC']==".00")||(strlen($_SESSION['prFormVars']['LE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['prFormVars']['LE_AXIS'];?>" size="4" maxlength="3"/>
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
                     <option value="+3.50"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+3.50") echo "selected=\"selected\"";?>>+3.50</option>
                     <option value="+3.25"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+3.25") echo "selected=\"selected\"";?>>+3.25</option>
                     <option value="+3.00"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+3.00") echo "selected=\"selected\"";?>>+3.00</option>
                     <option value="+2.75"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+2.75") echo "selected=\"selected\"";?>>+2.75</option>
                     <option value="+2.50"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+2.50") echo "selected=\"selected\"";?>>+2.50</option>
                     <option value="+2.25"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+2.25") echo "selected=\"selected\"";?>>+2.25</option>
                     <option value="+2.00"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+2.00") echo "selected=\"selected\"";?>>+2.00</option>
                     <option value="+1.75"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+1.75") echo "selected=\"selected\"";?>>+1.75</option>
                     <option value="+1.50"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+1.50") echo "selected=\"selected\"";?>>+1.50</option>
                     <option value="+1.25"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+1.25") echo "selected=\"selected\"";?>>+1.25</option>
                     <option value="+1.00"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+1.00") echo "selected=\"selected\"";?>>+1.00</option>
                     <option value="+0.75"<?php if ($_SESSION['prFormVars']['LE_ADD']=="+0.75") echo "selected=\"selected\"";?>>+0.75</option>
                     <option value="+0.00" <?php if (($_SESSION['prFormVars']['LE_ADD']=="+0.00")||(strlen($_SESSION['prFormVars']['LE_ADD'])<2)) echo "selected=\"selected\"";?>>+0.00</option>
                   </select></td>
                    <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In" />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="Out" />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" size="4" maxlength="4" /><br /><input name="LE_PR_UD" type="radio" value="Up" />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="Down" />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="4" maxlength="4" /></td>
                   </tr>
               </table>
             </div>
             
            
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                 <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                       <option <?php if ($_SESSION['prFormVars']['INDEX']=="1.50") echo 'selected="selected"';  ?> value="1.50">1.50</option>
                       <option <?php if ($_SESSION['prFormVars']['INDEX']=="1.53") echo 'selected="selected"';  ?> value="1.53">1.53</option>
                       <option <?php if ($_SESSION['prFormVars']['INDEX']=="1.59") echo 'selected="selected"';  ?> value="1.59">1.59</option>
                       <option <?php if ($_SESSION['prFormVars']['INDEX']=="1.67") echo 'selected="selected"';  ?> value="1.67">1.67</option>             
                 </select>
                  

                   </span></td>
                   <td width="50" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="selected">
                        <?php if ($mylang == 'lang_french'){?>
                            Tous
                        <?php }else {?>
                            All
                        <?php }?>
                		</option>
                       <option value="HC" <?php if ($_SESSION['prFormVars']['COATING']=="HC") echo "selected=\"selected\"";?>>HC</option>
                       <option value="AR" 	     <?php if ($_SESSION['prFormVars']['COATING']=="AR") echo "selected=\"selected\"";?>>AR</option>
                     </select>
                   </span></td>
                   </tr>
                   
                 <tr>
                    <td width="50" align="right"  class="formCell"><span class="tableSubHead"> 
					 <?php if ($mylang == 'lang_french'){
						echo 'Catégorie de verres:';
						}else {
						echo 'Lens category';
						}
						?>
                </span></td>
                   <td align="left"  class="formCellNosides"><span style="margin:11px">
                   
        
				 <?php if (($mylang == 'lang_french') && ($_SESSION['lens_category']<>"bifocal") && ($_SESSION['lens_category']<>"all")){ ?>
                    <select name="lens_category">
                        <option  value="all" <?php if ($_SESSION['lens_category']=="all") echo 'selected="selected"'; ?>>Tous les progressifs</option>
                        <option  value="prog ff" <?php if ($_SESSION['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                        <option  value="prog cl" <?php if ($_SESSION['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressif CL</option>
                        <option  value="bifocal" <?php if ($_SESSION['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bifocaux</option>
                    </select>
                  <?php }elseif (($mylang == 'lang_english') && ($_SESSION['lens_category']<>"bifocal") && ($_SESSION['lens_category']<>"all")) {	?>
                    <select name="lens_category">
                        <option  value="all" <?php if ($_SESSION['lens_category']=="all") echo 'selected="selected"'; ?>>All Progressives</option>
                        <option  value="prog ff" <?php if ($_SESSION['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressives FF</option>
                        <option  value="prog cl" <?php if ($_SESSION['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressives CL</option>
                        <option  value="bifocal" <?php if ($_SESSION['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bifocal</option>
                    </select>
                  <?php }elseif (($mylang == 'lang_french') && ($_SESSION['lens_category']=="bifocal")){ ?>
         		 <select name="lens_category">
                 	<option  value="bifocal" <?php if ($_SESSION['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focaux</option>
                 </select>
                 <?php }elseif (($mylang == 'lang_english') && ($_SESSION['lens_category']=="bifocal")){	?>
				  <select name="lens_category">
                	  <option  value="bifocal" <?php if ($_SESSION['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bifocal</option>
                  </select>
                  <?php  }elseif (($mylang == 'lang_french') && ($_SESSION['lens_category']=="all")){	?>
				  <select name="lens_category">
                	  <option  value="all" <?php if ($_SESSION['lens_category']=="all") echo 'selected="selected"'; ?>>Tous les progressifs</option>
                        <option  value="prog ff" <?php if ($_SESSION['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                        <option  value="prog cl" <?php if ($_SESSION['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressif CL</option>
                  </select>
			      <?php }elseif (($mylang == 'lang_english') && ($_SESSION['lens_category']=="all")){	?>
				  <select name="lens_category">
                	   <option  value="all" <?php if ($_SESSION['lens_category']=="all") echo 'selected="selected"'; ?>>All Progressives</option>
                        <option  value="prog ff" <?php if ($_SESSION['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressives FF</option>
                        <option  value="prog cl" <?php if ($_SESSION['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressives CL</option>
                  </select>
			      <?php } ?>
			

                   </span>
                   
                   </td>
                 
                 
                  
                   
                     <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                     <td width="57" align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                       <option value="None" selected="selected"><?php echo $lbl_polarized1;?></option>
                       <?php
					  $query="SELECT polar FROM safety_exclusive group by polar asc"; /* select all openings */
					$result=mysqli_query($con,$query)	or die ("Could not select items");
					$usercount=mysqli_num_rows($result);
					 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					 if ($listItem[polar]!="None"){
					 echo "<option value=\"$listItem[polar]\"";
					 if ($_SESSION['prFormVars']['POLAR']=="$listItem[polar]") 
					 echo "selected=\"selected\"";
					 echo ">";
					 $name=stripslashes($listItem[polar]);
					 echo "$name</option>";}}?>
                     </select>
                   </span></td>
                   </tr>
  
                   
                   
                   
                   <tr>
                    <td width="50" align="right"  class="formCell"><span class="tableSubHead"> 
						<?php if ($mylang == 'lang_french'){
                        echo 'Photochromique';
                        }else {
                        echo 'Photochromatic';
                        }
                        ?>
                        </span></td>
                   <td align="left"  class="formCellNosides"><span style="margin:11px">
					<select name="PHOTO" class="formText" id="PHOTO">
						<?php if ($mylang == 'lang_french'){ ?>
						   <option value="none" selected="selected">Aucun</option>
						   <option <?php if ($_SESSION['prFormVars']['PHOTO']=="Gris") echo 'selected="selected"'; ?> value="Grey">Gris</option>           
						   <option <?php if ($_SESSION['prFormVars']['PHOTO']=="Brun") echo 'selected="selected"'; ?> value="Brown">Brun</option>  
                        <?php }else { ?>
						   <option value="none" selected="selected">None</option>
						   <option <?php if ($_SESSION['prFormVars']['PHOTO']=="Grey") echo 'selected="selected"'; ?> value="Grey">Grey</option>           
						   <option <?php if ($_SESSION['prFormVars']['PHOTO']=="Brown") echo 'selected="selected"'; ?> value="Brown">Brown</option>  
                        <?php } ?>             
                 	</select>
                   </span>
                   </td>                  
                 
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
					echo 'Sélectionner';
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
                   </tr>

               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                



               <tr>
                   <td colspan="8" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
               </tr>
                 
                 
        <tr>     
       
       
			<?php if (strtolower($Removable_side_shield_available)=='yes') { ?>      
            <td colspan="4" class="formCell" align="left">
				<?php if ($mylang != 'lang_french'){?>
                Removable Side Shield ($<?php echo $Removable_side_shield_price ;?>)
                <?php }else{?>
                Protection latérale amovible (<?php echo $Removable_side_shield_price ;?>$)
                <?php }?>
            &nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if ($_SESSION['PrescrData']['REMOVABLE_SIDE_SHIELD']<> '') echo ' checked '; ?> id="removable_side_shield" value="<?php echo $Removable_side_shield_ID;?>" name="removable_side_shield"  />
             <input type="hidden" name="removable_side_shield_price" id="removable_side_shield_price" value="<?php echo $Removable_side_shield_price ;?>" />
             </td>
           <?php } ?> 
            
            
            <?php if (strtolower($Cushion_available)=='yes') { ?>      
            <td colspan="2"  class="formCell">
				<?php if ($mylang != 'lang_french'){?>
                Cushion  ($<?php echo $cushion_selling_price; ?>)
                <?php }else{?>
                Coussinet (<?php echo $cushion_selling_price; ?>$)
                <?php }?> 
            <input type="checkbox" id="cushion" <?php if ($_SESSION['PrescrData']['CUSHION']<> '') echo ' checked '; ?> name="cushion" value="<?php echo $cushion_ID; ?>"  /></td>
            <input type="hidden" name="cushion_selling_price" id="cushion_selling_price" value="<?php echo $cushion_selling_price ;?>" />
            <?php } ?> 
            
            
            
             <?php if (strtolower($Dust_bar_available)=='yes') { ?>      
            <td colspan="2" class="formCell">
				<?php if ($mylang != 'lang_french'){?>
                Dust Bar  ($<?php echo $dust_bar_selling_price; ?>)
                <?php
				 }else{?>
                Pare-Poussière (<?php echo $dust_bar_selling_price; ?>$)
                <?php
				 }?>
            &nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if ($_SESSION['PrescrData']['DUST_BAR'] <> '') echo ' checked '; ?>  id="dust_bar" name="dust_bar"  />
            <input type="hidden" name="dust_bar_selling_price" id="dust_bar_selling_price" value="<?php echo $dust_bar_selling_price ;?>" />
            </td>
            <?php } ?> 
        
        
	   </tr>
       
         
             <tr>
                   <td align="right" class="formCell"><?php echo 'O.C.';?>&nbsp;</td>
                   <td align="left" class="formCellNosides">
            <input name="OPTICAL_CENTER" type="text" class="formText" id="OPTICAL_CENTER" value="<?php echo $_SESSION['PrescrData']['OPTICAL_CENTER'];?>" size="4" maxlength="4">
                   </td>
                   <td colspan="3">&nbsp;</td>
                 </tr>   
                 
               </table>
             </div>
</table>  

         
         <div>
  				<?php if (($mylang == 'lang_french') && ($_SESSION['lens_category']=='all') && ($Charge_Dispensing_Fee=='yes')){//Correspond aux produits Progressifs
               // echo "Honoraire professionnel Progressif (30$)";
				$valueDispensingfee= 25;
				$IDDispensingFee = 'DISPENSING_FEE_PROG';
				}elseif (($mylang == 'lang_english') && ($_SESSION['lens_category']=='all') && ($Charge_Dispensing_Fee=='yes')){//Correspond aux produits Progressifs
                //echo "Dispensing Fee Progressive ($30)"; 
				$valueDispensingfee= 25;
				$IDDispensingFee = 'DISPENSING_FEE_PROG';
				}elseif (($mylang == 'lang_french') && ($_SESSION['lens_category']=='bifocal') && ($Charge_Dispensing_Fee=='yes')){//Correspond aux produits Progressifs
				//echo "Honoraire professionnel Bifocal (25$)";
				$valueDispensingfee= 20;
				$IDDispensingFee = 'DISPENSING_FEE_BIFOCAL';
				}elseif (($mylang == 'lang_english') && ($_SESSION['lens_category']=='bifocal') &&  ($Charge_Dispensing_Fee=='yes')){//Correspond aux produits Progressifs
				//echo "Dispensing Fee Bifocal ($25)"; 
				$IDDispensingFee = 'DISPENSING_FEE_BIFOCAL';
				$valueDispensingfee= 20;
				}?>  
                
      <?php  if ($Charge_Dispensing_Fee=='yes')
	          {?>
       <input type="hidden" checked  id="<?php echo $IDDispensingFee; ?>" value="<?php  echo $valueDispensingfee;?>" name="<?php echo $IDDispensingFee; ?>"  />
      <?php   } ?>
       </div> 
         

         
                 

                 
          
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                 
                   <td align="center" class="formCellNosides">P.D.<br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" value="<?php echo $_SESSION['prFormVars']['RE_PD'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/ifcopticclub/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   
                   
                   <td align="center" class="formCellNoleft">P.G.<br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" value="<?php echo $_SESSION['prFormVars']['LE_PD'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                     
                     <td align="center" class="formCellNosides"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[re_pd_near] . '"'; ?> value="<?php	if ($_SESSION['prFormVars']['RE_PD_NEAR']>0)
				 echo  $_SESSION['prFormVars']['RE_PD_NEAR'];
				 ?>" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                     
                     <td align="center" class="formCellNoleft"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[le_pd_near] . '"'; ?> value="<?php	if ($_SESSION['prFormVars']['LE_PD_NEAR']>0)
				 echo  $_SESSION['prFormVars']['LE_PD_NEAR'];
				 ?>" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                     
                     
                     
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" value="<?php echo $_SESSION['prFormVars']['RE_HEIGHT'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                     
                     
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/ifcopticclub/design_images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" value="<?php echo $_SESSION['prFormVars']['LE_HEIGHT'];?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                 </tr>
               </table>
             </div>
             
             

          <div>

               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
             <tr>
                    <td colspan="9" bgcolor="#ee7e32" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_eye_txt_pl;?></td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_a_txt_pl;?>
                     &nbsp;
  <input name="FRAME_A" type="text" value="<?php if ($_SESSION['prFormVars']['FRAME_A'] <> '') echo $_SESSION['prFormVars']['FRAME_A']; else echo $frameItem[frame_a];?>" class="formText" id="FRAME_A" size="4" maxlength="4" />
                     &nbsp;
                     <?php echo $lbl_b_txt_pl;?>
                     <input name="FRAME_B" type="text" value="<?php if ($_SESSION['prFormVars']['FRAME_B'] <> '') echo $_SESSION['prFormVars']['FRAME_B']; else echo $frameItem[frame_b];?>"  class="formText" id="FRAME_B" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_ed_txt_pl;?>
                     <input name="FRAME_ED"  type="text" value="<?php if ($_SESSION['prFormVars']['FRAME_ED'] <> '') echo $_SESSION['prFormVars']['FRAME_ED']; else echo $frameItem[frame_ed];?>"  class="formText" id="FRAME_ED" size="4" maxlength="4"  />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_dbl_txt_pl;?>
                     <input name="FRAME_DBL" type="text" value="<?php if ($_SESSION['prFormVars']['FRAME_DBL'] <> '') echo $_SESSION['prFormVars']['FRAME_DBL']; else echo $frameItem[frame_dbl];?>" class="formText" id="FRAME_DBL" size="4" maxlength="4"  />
                     &nbsp;
                     </td>
                     
                    
                   <td align="right" class="formCell"><?php echo $lbl_type_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCell">
                   <select name="FRAME_TYPE" class="formText"  id="FRAME_TYPE">
                       <option value=""><?php echo $lbl_type1_pl;?></option>
                       <option value="Nylon Groove" 	<?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Nylon Groove") echo "selected=\"selected\"";?>><?php echo $lbl_type2_pl;?></option>
                       <option value="Metal Groove" 	<?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Metal Groove") echo "selected=\"selected\"";?>><?php echo $lbl_type3_pl;?></option>
                       <option value="Plastic"      	<?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Plastic") echo "selected=\"selected\"";?>><?php echo $lbl_type4_pl;?></option>
                       <option value="Metal"        	<?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Metal") echo "selected=\"selected\"";?>><?php echo $lbl_type5_pl;?></option>
                       <option value="Edge Polish"  	<?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Edge Polish") echo "selected=\"selected\"";?>><?php echo $lbl_type6_pl;?></option>
                       <option value="Drill and Notch"  <?php if ($_SESSION['prFormVars']['FRAME_TYPE']=="Drill and Notch") echo "selected=\"selected\"";?>><?php echo $lbl_type7_pl;?></option>
                       </select>                     </td>
                   </tr>
                   </table>
          </div>   
             
             
  
            
             
             
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"><?php echo $_SESSION['prFormVars']['SPECIAL_INSTRUCTIONS'];?></textarea></td>
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

         

			
		    <div align="center" style="margin:11px"><input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/></div>
            
<input name="SUPPLIER" type="hidden" id="SUPPLIER" value="<?php echo $frameItem['collection']; ?>"/>
<input name="FRAME_MODEL" type="hidden" id="FRAME_MODEL" value="<?php echo $frameItem['frame_shape']; ?>"/>
<input name="PACKAGE" type="hidden" id="PACKAGE" value="<?php echo $frameItem['misc_unknown_purpose']; ?>"/>
<input name="TEMPLE_MODEL" type="hidden" id="TEMPLE_MODEL" value="<?php echo $frameItem['code']; ?>"/>
<input name="COLOR" type="hidden" id="COLOR" value="<?php echo $frameItem['color_en']; ?>" /> 
<input name="JOB_TYPE" type="hidden" id="JOB_TYPE" value="Edge and Mount" /> 
<input name="ORDER_TYPE" type="hidden" id="ORDER_TYPE" value="Provide" /> 
<input name="DIAMETER" type="hidden"  id="DIAMETER" value="">
                     
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>