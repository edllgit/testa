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
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");
global $drawme;


if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Lensnet Club</title>

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


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form.js.inc.php";?>

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
<div id="rightColumn"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validate(this);">
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
 
  
  <?php  
  //Valider si ce client a deja une commande dans le systeme, et si oui, on sélectionne les informations de la commande la plus récente.
  $queryLastOrder = "SELECT count(order_num) as NbOrder, max(order_num) as order_num FROM orders WHERE user_id = '" .$_SESSION["sessionUser_Id"]. "' AND eye ='Both' AND order_product_type='exclusive' AND order_status <> 'cancelled' ";
  $resultLastOrder=mysqli_query($con,$queryLastOrder);
  $DataLastOrder=mysqli_fetch_array($resultLastOrder,MYSQLI_ASSOC);
  
  $queryLoadLastRx  = "SELECT auto_load_last_rx FROM accounts WHERE user_id = '" .$_SESSION["sessionUser_Id"]. "'";
  $resultLoadLastRx = mysqli_query($con,$queryLoadLastRx);
  $DataLoadLastRx   = mysqli_fetch_array($resultLoadLastRx,MYSQLI_ASSOC);
  $ChargerDerniereCommande = false;
  
 // echo '$DataLoadLastRx[auto_load_last_rx]:' . $DataLoadLastRx[auto_load_last_rx];
  if($DataLoadLastRx[auto_load_last_rx]==1){
		  if ($DataLastOrder[NbOrder] > 0){
		  $ChargerDerniereCommande = true;
		  $queryLastOrderDetail = "SELECT * FROM orders WHERE order_num = $DataLastOrder[order_num]";
		  $ResultLastOrderDetail=mysqli_query($con,$queryLastOrderDetail);
		  $DataLastOrderDetail=mysqli_fetch_array($ResultLastOrderDetail,MYSQLI_ASSOC);
		  $queryTintDetail = "SELECT * FROM extra_product_orders WHERE category='Tint' AND  order_num = $DataLastOrder[order_num]";
		  $ResultTintDetail=mysqli_query($con,$queryTintDetail);
		  $DataTintDetail=mysqli_fetch_array($ResultTintDetail,MYSQLI_ASSOC);
		  }
	}  
  ?> 
             
            
              <div>
                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                  <tr >
                    <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_patient;?></td>
                    <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_refnum_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo 'Tray (Lab Only)';?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_slsperson_txt;?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input onfocus="fixRE_SPH(this.form)"  name="LAST_NAME" type="text" class="formText" id="LAST_NAME" size="20" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[order_patient_last] . '"'; ?> /></td>
                    <td class="formCellNosides"><input onfocus="fixRE_SPH(this.form)" name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" size="20" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[order_patient_first] . '"'; ?> /></td>
                    <td class="formCellNosides"><input onfocus="fixRE_SPH(this.form)" name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" size="10"  /></td>
                    <td class="formCellNosides"><input onfocus="fixRE_SPH(this.form)" name="TRAY_NUM" type="text" class="formText" id="TRAY_NUM" size="8" /></td>
                    <td class="formCellNosides"><select name="SALESPERSON_ID" class="formText" id="SALESPERSON_ID">
                      <option value="" selected="selected"><?php echo $lbl_slsperson1;?></option>
                      <?php
						$user_id=$_SESSION["sessionUser_Id"];
  $query="SELECT sales_id,first_name,last_name FROM salespeople WHERE acct_user_id='$user_id' AND removed!='Yes' ORDER by last_name,first_name"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	 echo "<option value=\"$listItem[sales_id]\">";$name=stripslashes($listItem[first_name])." ".stripslashes($listItem[last_name]);echo "$name</option>";}?>
                      </select></td>
                    </tr>
                </table>
              </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                 <tr>
                   <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_prescription_txt_pl;?>
                       <input name="EYE" id="Both" type="radio" value="Both" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[eye]=='Both')) echo ' checked="checked"'; else echo 'checked="checked"'; ?>  onclick="ActivateAll_fields(this.form);" onselect="ActivateAll_fields(this.form);"  />
                       <?php echo $lbl_prescription1_pl;?> 
                       &nbsp;
                       <input name="EYE" type="radio" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[eye]=='R.E.')) echo ' checked="checked"'; ?> onclick="DesactivateLE_fields(this.form);" value="<?php echo $lbl_re_txt_pl;?>" />
                       <?php echo $lbl_prescription2_pl;?> 
                       <input name="EYE" type="radio" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[eye]=='L.E.')) echo ' checked="checked"'; ?> onclick="DesactivateRE_fields(this.form);" value="<?php echo $lbl_le_txt_pl;?>" />
                       <?php echo $lbl_prescription3_pl;?> </td>
                   </tr>
                 <tr>
                   <td colspan="2" valign="middle"  class="formCell">&nbsp;</td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_sphere_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo '<a target="_blank" title="Aide à la conversion/Help convert Cylinder"  href="http://www.dicoptic.izispot.com/conversions_785.htm#ConvCyl">' . $lbl_cylinder_txt_pl . '</a>';?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_axis_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_addition_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCell"><?php echo $lbl_prism_txt_pl;?></td>
                   </tr>
                 <tr >
                 
                   <?php
				   $PositionPointRe = strpos($DataLastOrderDetail[re_sphere],'.');
				   $PositionPointLe = strpos($DataLastOrderDetail[le_sphere],'.');
				   
				   $LongeurRE       = strlen($DataLastOrderDetail[re_sphere]);
				   $LongeurLE       = strlen($DataLastOrderDetail[le_sphere]);
				   
				   $LongeurReSph = $LongeurRE -  $PositionPointRe -1;
				   $LongeurLeSph = $LongeurLE -  $PositionPointLe -1;
				   
				   $DebutReNum =  $LongeurReSph ;
				   $DebutLeNum =  $LongeurLeSph ;
				   					
				   $re_sph_num = substr($DataLastOrderDetail[re_sphere],0,$LongeurReSph);
				   $le_sph_num = substr($DataLastOrderDetail[le_sphere],0,$LongeurLeSph);
				   $re_sph_dec = substr($DataLastOrderDetail[re_sphere],$DebutReNum,3);
				   $le_sph_dec = substr($DataLastOrderDetail[le_sphere], $DebutLeNum ,3);
				   
				   $re_cyl_num = substr($DataLastOrderDetail[re_cyl],0,2);
				   $le_cyl_num = substr($DataLastOrderDetail[le_cyl],0,2);
				   
				   $re_cyl_dec = substr($DataLastOrderDetail[re_cyl],2,3);
				   $le_cyl_dec = substr($DataLastOrderDetail[le_cyl],2,3);
				   
				   ?>
                 
                 
                 
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/lensnet/images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)" >
                     <option value="+14"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+14')) echo ' selected="selected"'; ?>>+14</option>
                     <option value="+13"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+13')) echo ' selected="selected"'; ?>>+13</option>
                     <option value="+12"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+12')) echo ' selected="selected"'; ?>>+12</option>
                     <option value="+11"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+11')) echo ' selected="selected"'; ?>>+11</option>
                     <option value="+10"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+10')) echo ' selected="selected"'; ?>>+10</option>
                     <option value="+9"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+9')) echo ' selected="selected"'; ?>>+9</option>
                     <option value="+8"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+8')) echo ' selected="selected"'; ?>>+8</option>
                     <option value="+7"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+7')) echo ' selected="selected"'; ?>>+7</option>
                     <option value="+6"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+6')) echo ' selected="selected"'; ?>>+6</option>
                     <option value="+5"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+5')) echo ' selected="selected"'; ?>>+5</option>
                     <option value="+4"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+4')) echo ' selected="selected"'; ?>>+4</option>
                     <option value="+3"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+3')) echo ' selected="selected"'; ?>>+3</option>
                     <option value="+2"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+2')) echo ' selected="selected"'; ?>>+2</option>
                     <option value="+1"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='+1')) echo ' selected="selected"'; ?>>+1</option>
                     <option value="+0"   <?php if(($ChargerDerniereCommande) && ($re_sph_num ==="+0")) echo ' selected="selected"'; ?> <?php if(($ChargerDerniereCommande) && ($re_sph_num ==="0.")) echo ' selected="selected"'; ?><?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?> >+0</option>
                     <option value="-0"   <?php if(($ChargerDerniereCommande) && ($re_sph_num ==="-0")) echo ' selected="selected"'; ?>>-0</option>
                     <option value="-1"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-1')) echo ' selected="selected"'; ?>>-1</option>
                     <option value="-2"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-2')) echo ' selected="selected"'; ?>>-2</option>
                     <option value="-3"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-3')) echo ' selected="selected"'; ?>>-3</option>
                     <option value="-4"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-4')) echo ' selected="selected"'; ?>>-4</option>
                     <option value="-5"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-5')) echo ' selected="selected"'; ?>>-5</option>
                     <option value="-6"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-6')) echo ' selected="selected"'; ?>>-6</option>
                     <option value="-7"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-7')) echo ' selected="selected"'; ?>>-7</option>
                     <option value="-8"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-8')) echo ' selected="selected"'; ?>>-8</option>
                     <option value="-9"   <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-9')) echo ' selected="selected"'; ?>>-9</option>
                     <option value="-10"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-10')) echo ' selected="selected"'; ?>>-10</option>
                     <option value="-11"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-11')) echo ' selected="selected"'; ?>>-11</option>
                     <option value="-12"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-12')) echo ' selected="selected"'; ?>>-12</option>
                     <option value="-13"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-13')) echo ' selected="selected"'; ?>>-13</option>
                     <option value="-14"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-14')) echo ' selected="selected"'; ?>>-14</option>
                     <option value="-15"  <?php if(($ChargerDerniereCommande) && ($re_sph_num =='-15')) echo ' selected="selected"'; ?>>-15</option>
                     </select>
                     <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC" onchange="fixRE_SPH(this.form)">
                       <option value=".75" <?php if(($ChargerDerniereCommande) && ($re_sph_dec =='.75')) echo ' selected="selected"'; ?>>.75</option>
                       <option value=".50" <?php if(($ChargerDerniereCommande) && ($re_sph_dec =='.50')) echo ' selected="selected"'; ?>>.50</option>
                       <option value=".25" <?php if(($ChargerDerniereCommande) && ($re_sph_dec =='.25')) echo ' selected="selected"'; ?>>.25</option>
                       <option value=".00" <?php if(($ChargerDerniereCommande) && ($re_sph_dec =='.00')) echo ' selected="selected"'; ?> <?php if(($ChargerDerniereCommande) && ($re_sph_dec =='00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>.00</option>
                       </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                     <option value="-0" <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>-0</option>
                     <option value="-1" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-1')) echo ' selected="selected"'; ?>>-1</option>
                     <option value="-2" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-2')) echo ' selected="selected"'; ?>>-2</option>
                     <option value="-3" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-3')) echo ' selected="selected"'; ?>>-3</option>
                     <option value="-4" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-4')) echo ' selected="selected"'; ?>>-4</option>
                     <option value="-5" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-5')) echo ' selected="selected"'; ?>>-5</option>
                     <option value="-6" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-6')) echo ' selected="selected"'; ?>>-6</option>
                     <option value="-7" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-7')) echo ' selected="selected"'; ?>>-7</option>
                     <option value="-8" <?php if(($ChargerDerniereCommande) && ($re_cyl_num =='-8')) echo ' selected="selected"'; ?>>-8</option>
                     </select>
                     <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                       <option value=".75" <?php if(($ChargerDerniereCommande) && ($re_cyl_dec =='.75')) echo ' selected="selected"'; ?>>.75</option>
                       <option value=".50" <?php if(($ChargerDerniereCommande) && ($re_cyl_dec =='.50')) echo ' selected="selected"'; ?>>.50</option>
                       <option value=".25" <?php if(($ChargerDerniereCommande) && ($re_cyl_dec =='.25')) echo ' selected="selected"'; ?>>.25</option>
                       <option value=".00" <?php if(($ChargerDerniereCommande) && ($re_cyl_dec =='.00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>.00</option>
                       </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[re_axis] . '"'; ?> />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
                     <option value="+4.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+4.00')) echo ' selected="selected"'; ?>>+4.00</option>
                     <option value="+3.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+3.50')) echo ' selected="selected"'; ?>>+3.50</option>
                     <option value="+3.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+3.25')) echo ' selected="selected"'; ?>>+3.25</option>
                     <option value="+3.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+3.00')) echo ' selected="selected"'; ?>>+3.00</option>
                     <option value="+2.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+2.75')) echo ' selected="selected"'; ?>>+2.75</option>
                     <option value="+2.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+2.50')) echo ' selected="selected"'; ?>>+2.50</option>
                     <option value="+2.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+2.25')) echo ' selected="selected"'; ?>>+2.25</option>
                     <option value="+2.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+2.00')) echo ' selected="selected"'; ?>>+2.00</option>
                     <option value="+1.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+1.75')) echo ' selected="selected"'; ?>>+1.75</option>
                     <option value="+1.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+1.50')) echo ' selected="selected"'; ?>>+1.50</option>
                     <option value="+1.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+1.25')) echo ' selected="selected"'; ?>>+1.25</option>
                     <option value="+1.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+1.00')) echo ' selected="selected"'; ?>>+1.00</option>
                     <option value="+0.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+0.75')) echo ' selected="selected"'; ?>>+0.75</option>
                     <option value="+0.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+0.50')) echo ' selected="selected"'; ?>>+0.50</option>
                     <option value="+0.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+0.25')) echo ' selected="selected"'; ?>>+0.25</option>
                     <option value="+0.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_add]=='+0.00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?> >+0.00</option>
                     </select></td>
                   <td align="right" valign="top"class="formCell">
                     <input name="RE_PR_IO" type="radio" value="In"  <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_io]=='In')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="Out" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_io]=='Out')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="None" checked="checked" id="RE_PR_IO_None" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_io]=='None')) echo ' checked="checked"'; ?>
                      <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_io]=='')) echo ' checked="checked"'; ?>  />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" <?php if($ChargerDerniereCommande) 
					 echo ' value="'.$DataLastOrderDetail[re_pr_ax] . '"'; ?> /><br />
                     <input name="RE_PR_UD" type="radio" value="Up" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_ud]=='Up')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="Down" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_ud]=='Down')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="None" checked="checked"  id="RE_PR_UD_None"  <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_ud]=='None')) echo ' checked="checked"'; ?>
                     <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[re_pr_ud]=='')) echo ' checked="checked"'; ?>/>
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4" <?php if($ChargerDerniereCommande) 
					 echo ' value="'.$DataLastOrderDetail[re_pr_ax2] . '"'; ?> />
                     </td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                     <option value="+14" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+14")) echo ' selected="selected"'; ?>>+14</option>
                     <option value="+13" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+13")) echo ' selected="selected"'; ?>>+13</option>
                     <option value="+12" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+12")) echo ' selected="selected"'; ?>>+12</option>
                     <option value="+11" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+11")) echo ' selected="selected"'; ?>>+11</option>
                     <option value="+10" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+10")) echo ' selected="selected"'; ?>>+10</option>
                     <option value="+9"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+9")) echo ' selected="selected"'; ?>>+9</option>
                     <option value="+8"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+8")) echo ' selected="selected"'; ?>>+8</option>
                     <option value="+7"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+7")) echo ' selected="selected"'; ?>>+7</option>
                     <option value="+6"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+6")) echo ' selected="selected"'; ?>>+6</option>
                     <option value="+5"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+5")) echo ' selected="selected"'; ?>>+5</option>
                     <option value="+4"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+4")) echo ' selected="selected"'; ?>>+4</option>
                     <option value="+3"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+3")) echo ' selected="selected"'; ?>>+3</option>
                     <option value="+2"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+2")) echo ' selected="selected"'; ?>>+2</option>
                     <option value="+1"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+1")) echo ' selected="selected"'; ?>>+1</option>
                     <option value="+0"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="+0")) echo ' selected="selected"'; ?> <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="0.")) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>+0</option>
                     <option value="-0"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-0")) echo ' selected="selected"'; ?>>-0</option>
                     <option value="-1"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-1")) echo ' selected="selected"'; ?>>-1</option>
                     <option value="-2"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-2")) echo ' selected="selected"'; ?>>-2</option>
                     <option value="-3"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-3")) echo ' selected="selected"'; ?>>-3</option>
                     <option value="-4"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-4")) echo ' selected="selected"'; ?>>-4</option>
                     <option value="-5"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-5")) echo ' selected="selected"'; ?>>-5</option>
                     <option value="-6"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-6")) echo ' selected="selected"'; ?>>-6</option>
                     <option value="-7"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-7")) echo ' selected="selected"'; ?>>-7</option>
                     <option value="-8"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-8")) echo ' selected="selected"'; ?>>-8</option>
                     <option value="-9"  <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-9")) echo ' selected="selected"'; ?>>-9</option>
                     <option value="-10" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-10")) echo ' selected="selected"'; ?>>-10</option>
                     <option value="-11" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-11")) echo ' selected="selected"'; ?>>-11</option>
                     <option value="-12" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-12")) echo ' selected="selected"'; ?>>-12</option>
                     <option value="-13" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-13")) echo ' selected="selected"'; ?>>-13</option>
                     <option value="-14" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-14")) echo ' selected="selected"'; ?>>-14</option>
                     <option value="-15" <?php if(($ChargerDerniereCommande) && ($le_sph_num ==="-15")) echo ' selected="selected"'; ?>>-15</option>
                     </select>
                     <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC" onchange="fixLE_SPH(this.form)" >
                       <option value=".75" <?php if(($ChargerDerniereCommande) && ($le_sph_dec =='.75')) echo ' selected="selected"'; ?>>.75</option>
                       <option value=".50" <?php if(($ChargerDerniereCommande) && ($le_sph_dec =='.50')) echo ' selected="selected"'; ?>>.50</option>
                       <option value=".25" <?php if(($ChargerDerniereCommande) && ($le_sph_dec =='.25')) echo ' selected="selected"'; ?>>.25</option>
                       <option value=".00" <?php if(($ChargerDerniereCommande) && ($le_sph_dec =='.00')) echo ' selected="selected"'; ?> <?php if(($ChargerDerniereCommande) && ($le_sph_dec =='00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>.00</option>
                       </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">
                     <option value="-0" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-0')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>-0</option>
                     <option value="-1" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-1')) echo ' selected="selected"'; ?>>-1</option>
                     <option value="-2" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-2')) echo ' selected="selected"'; ?>>-2</option>
                     <option value="-3" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-3')) echo ' selected="selected"'; ?>>-3</option>
                     <option value="-4" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-4')) echo ' selected="selected"'; ?>>-4</option>
                     <option value="-5" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-5')) echo ' selected="selected"'; ?>>-5</option>
                     <option value="-6" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-6')) echo ' selected="selected"'; ?>>-6</option>
                     <option value="-7" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-7')) echo ' selected="selected"'; ?>>-7</option>
                     <option value="-8" <?php if(($ChargerDerniereCommande) && ($le_cyl_num =='-8')) echo ' selected="selected"'; ?>>-8</option>
                     </select>
                     <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                       <option value=".75" <?php if(($ChargerDerniereCommande) && ($le_cyl_dec =='.75')) echo ' selected="selected"'; ?>>.75</option>
                       <option value=".50" <?php if(($ChargerDerniereCommande) && ($le_cyl_dec =='.50')) echo ' selected="selected"'; ?>>.50</option>
                       <option value=".25" <?php if(($ChargerDerniereCommande) && ($le_cyl_dec =='.25')) echo ' selected="selected"'; ?>>.25</option>
                       <option value=".00" <?php if(($ChargerDerniereCommande) && ($le_cyl_dec =='.00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>.00</option>
                       </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[le_axis] . '"'; ?> />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
                     <option value="+4.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+4.00')) echo ' selected="selected"'; ?>>+4.00</option>
                     <option value="+3.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+3.50')) echo ' selected="selected"'; ?>>+3.50</option>
                     <option value="+3.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+3.25')) echo ' selected="selected"'; ?>>+3.25</option>
                     <option value="+3.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+3.00')) echo ' selected="selected"'; ?>>+3.00</option>
                     <option value="+2.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+2.75')) echo ' selected="selected"'; ?>>+2.75</option>
                     <option value="+2.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+2.50')) echo ' selected="selected"'; ?>>+2.50</option>
                     <option value="+2.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+2.25')) echo ' selected="selected"'; ?>>+2.25</option>
                     <option value="+2.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+2.00')) echo ' selected="selected"'; ?>>+2.00</option>
                     <option value="+1.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+1.75')) echo ' selected="selected"'; ?>>+1.75</option>
                     <option value="+1.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+1.50')) echo ' selected="selected"'; ?>>+1.50</option>
                     <option value="+1.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+1.25')) echo ' selected="selected"'; ?>>+1.25</option>
                     <option value="+1.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+1.00')) echo ' selected="selected"'; ?>>+1.00</option>
                     <option value="+0.75" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+0.75')) echo ' selected="selected"'; ?>>+0.75</option>
                     <option value="+0.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+0.50')) echo ' selected="selected"'; ?>>+0.50</option>
                     <option value="+0.25" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+0.25')) echo ' selected="selected"'; ?>>+0.25</option>
                     <option value="+0.00" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_add]=='+0.00')) echo ' selected="selected"'; ?> <?php if($ChargerDerniereCommande == false) echo ' selected="selected"'; ?>>+0.00</option>
                     </select></td>
                   <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_io]=='In')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="Out" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_io]=='Out')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="None" checked="checked" id="LE_PR_IO_None" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_io]=='None')) echo ' checked="checked"'; ?> <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_io]=='')) echo ' checked="checked"'; ?>  />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" <?php if($ChargerDerniereCommande) 
					 echo ' value="'.$DataLastOrderDetail[le_pr_ax] . '"'; ?>  size="4" maxlength="4" /><br />
                     <input name="LE_PR_UD" type="radio"  value="Up" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_ud]=='Up')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="Down" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_ud]=='Down')) echo ' checked="checked"'; ?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="None" checked="checked" id="LE_PR_UD_None" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_ud]=='None')) echo ' checked="checked"'; ?> <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[le_pr_ud]=='')) echo ' checked="checked"'; ?>  />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="4" maxlength="4" <?php if($ChargerDerniereCommande) 
					 echo ' value="'.$DataLastOrderDetail[le_pr_ax2] . '"'; ?> /></td>
                   </tr>
               </table>
             </div>
             

            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                        
                     <select name="INDEX" class="formText" id="INDEX">  
                        <option value="ANY" selected="selected">ANY</option>
                        <option value="1.50" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.50')) echo ' selected="selected"'; ?>>1.50</option>
                        <option value="1.52" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.52')) echo ' selected="selected"'; ?>>1.52</option>
                        <option value="1.53" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.53')) echo ' selected="selected"'; ?>>1.53</option>
                        <option value="1.54" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.54')) echo ' selected="selected"'; ?>>1.54</option>
                        <option value="1.56" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.56')) echo ' selected="selected"'; ?>>1.56</option>
                        <option value="1.57" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.57')) echo ' selected="selected"'; ?>>1.57</option>
                        <option value="1.58" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.58')) echo ' selected="selected"'; ?>>1.58</option>
                        <option value="1.59" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.59')) echo ' selected="selected"'; ?>>1.59 Tintable</option>
                        <option value="1.592">1.59 Tegra</option>
                        <option value="1.60" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.60')) echo ' selected="selected"'; ?>>1.60</option>
                        <option value="1.67" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.67')) echo ' selected="selected"'; ?>>1.67</option>
                        <option value="1.70" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.70')) echo ' selected="selected"'; ?>>1.70</option>
                        <option value="1.74" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.74')) echo ' selected="selected"'; ?>>1.74</option>
                        <option value="1.80" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.80')) echo ' selected="selected"'; ?>>1.80</option>
                        <option value="1.90" <?php if(($ChargerDerniereCommande) && ($DataLastOrderDetail[order_product_index]=='1.90')) echo ' selected="selected"'; ?>>1.90</option>                      
                    </select>
                      
                     </span></td>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="selected">ANY</option>
                      <option value="Hard Coat">Hard Coat</option>
                      <option value="AR">AR</option>
                      <option value="Uncoated">Uncoated</option>                    

                    
                       </select>
                     </span></td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_photochro_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px"><select name="PHOTO" class="formText" id="PHOTO">
                     <option value="None" selected="selected"><?php echo $lbl_photochro1;?></option>
                     <?php
  $query="SELECT photo FROM exclusive  WHERE photo not in ('Yellow','Orange','Pink','Blue','Violet','Extra Active Brown') AND prod_status='active' group by photo asc"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 if ($listItem[photo]!="None"){
 echo "<option value=\"$listItem[photo]\"";
 
 if($ChargerDerniereCommande)
 {
	 if ($DataLastOrderDetail['order_product_photo']=="$listItem[photo]") 
	 echo "selected=\"selected\"";
	 echo ">";
 }else{
	echo ">"; 
	 }
 
 $name=stripslashes($listItem[photo]);
 echo "$name</option>";}}?>
                     </select></span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px"><select name="POLAR" class="formText" id="POLAR">
                     <option value="None" selected="selected"><?php echo $lbl_polarized1;?></option>
                     <?php
  $query="SELECT polar FROM exclusive group by polar asc"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 if ($listItem[polar]!="None"){
 echo "<option value=\"$listItem[polar]\"";
  if($ChargerDerniereCommande)
 {
	 if ($DataLastOrderDetail['order_product_polar']=="$listItem[polar]") 
	 echo "selected=\"selected\"";
	 echo ">";
 }else{
	echo ">"; 
	 }
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}}?>
                     </select></span></td>
                   </tr>
                   
                   
                   
                   
                     <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
				echo 'Filtre de verres:';
				}else {
				echo 'Lens Filter';
				}
				?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                   
                   <?php if ($mylang == 'lang_french'){				?>
        <select name="lens_category" id="lens_category">
                 <option   disabled="disabled" value="">CATÉGORIE DE VERRES*</option>
                 <option   value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>Tous</option> 
                 <option   value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                 <option   value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                 <option   value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>Tous Progressifs</option>
                 <option   value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressif Classique</option>
                 <option   value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                 <option   value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option   value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                 <option  disabled="disabled" value="">&nbsp;</option>
                 <option   disabled="disabled" value="">TYPE DE VERRES*</option>
                 <option   value="AO Compact" <?php if ($_POST['lens_category']=="AO Compact") echo 'selected="selected"'; ?>>AO COMPACT</option> 
                 <option   value="Compact Ultra HD" <?php if ($_POST['lens_category']=="Compact Ultra HD") echo 'selected="selected"'; ?>>COMPACT ULTRA HD</option> 
				 <option   value="EZ" <?php if ($_POST['lens_category']=="EZ") echo 'selected="selected"'; ?>>EZ BY CZV</option> 
				 <option   value="FT28" <?php if ($_POST['lens_category']=="FT28") echo 'selected="selected"'; ?>>FT28</option> 
                 <option   value="FT35" <?php if ($_POST['lens_category']=="FT35") echo 'selected="selected"'; ?>>FT35</option> 
                 <option   value="FT45" <?php if ($_POST['lens_category']=="FT45") echo 'selected="selected"'; ?>>FT45</option> 
                 <option   value="Innovative 1.0" <?php if ($_POST['lens_category']=="Innovative 1.0") echo 'selected="selected"'; ?>>INNOVATIVE 1.0</option> 
                 <option   value="Innovative 2.0" <?php if ($_POST['lens_category']=="Innovative 2.0") echo 'selected="selected"'; ?>>INNOVATIVE 2.0</option> 
                 <option   value="Innovative 3.0" <?php if ($_POST['lens_category']=="Innovative 3.0") echo 'selected="selected"'; ?>>INNOVATIVE 3.0</option> 
                 <option   value="RD" <?php if ($_POST['lens_category']=="RD") echo 'selected="selected"'; ?>>RD</option> 
                 <option   value="SelectionRx" <?php if ($_POST['lens_category']=="SelectionRx") echo 'selected="selected"'; ?>>SELECTIONRX</option> 
  				 <option   value="Single Vision" <?php if ($_POST['lens_category']=="Single Vision") echo 'selected="selected"'; ?>>SINGLE VISION</option> 
				 <option   value="Sola Easy" <?php if ($_POST['lens_category']=="Sola Easy") echo 'selected="selected"'; ?>>SOLA EASY</option> 
                 <option   value="Trifocal 7x28" <?php if ($_POST['lens_category']=="Trifocal 7x28") echo 'selected="selected"'; ?>>TRIFOCAL 7x28</option>
   				 <option   value="Trifocal 8x35" <?php if ($_POST['lens_category']=="Trifocal 8x35") echo 'selected="selected"'; ?>>TRIFOCAL 8x35</option> 
                 <option   disabled="disabled" value="">&nbsp;</option>
				 <option   disabled="disabled" value="">FABRICANT*</option>
                 <option   value="IOT" <?php if ($_POST['lens_category']=="IOT") echo 'selected="selected"'; ?>>IOT</option> 
                 <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option>  
                 <option   value="SOLA/ZEISS" <?php if ($_POST['lens_category']=="SOLA/ZEISS") echo 'selected="selected"'; ?>>SOLA/ZEISS</option>  
        </select>
                <?php 
				}else {
				?>
			<select name="lens_category" id="lens_category" >
                  <option  disabled="disabled" value="">LENS CATEGORY*</option>
                  <option  value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>All</option>
                  <option  value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                  <option  value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                  <option  value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>All Progressives</option>
                  <option  value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressive Classic</option>
                  <option  value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressive DS</option>
                  <option  value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressive FF</option>
                  <option  value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>SV</option>
                  <option  disabled="disabled" value="">&nbsp;</option>
                  <option  disabled="disabled" value="">LENS TYPE*</option>
                  <option   value="AO Compact" <?php if ($_POST['lens_category']=="AO Compact") echo 'selected="selected"'; ?>>AO COMPACT</option> 
                  <option   value="Compact Ultra HD" <?php if ($_POST['lens_category']=="Compact Ultra HD") echo 'selected="selected"'; ?>>COMPACT ULTRA HD</option>  
                  <option   value="EZ" <?php if ($_POST['lens_category']=="EZ") echo 'selected="selected"'; ?>>EZ BY CZV</option> 
                  <option   value="FT28" <?php if ($_POST['lens_category']=="FT28") echo 'selected="selected"'; ?>>FT28</option> 
                  <option   value="FT35" <?php if ($_POST['lens_category']=="FT35") echo 'selected="selected"'; ?>>FT35</option> 
                  <option   value="FT45" <?php if ($_POST['lens_category']=="FT45") echo 'selected="selected"'; ?>>FT45</option> 
                  <option   value="Innovative 1.0" <?php if ($_POST['lens_category']=="Innovative 1.0") echo 'selected="selected"'; ?>>INNOVATIVE 1.0</option> 
                  <option   value="Innovative 2.0" <?php if ($_POST['lens_category']=="Innovative 2.0") echo 'selected="selected"'; ?>>INNOVATIVE 2.0</option> 
                  <option   value="Innovative 3.0" <?php if ($_POST['lens_category']=="Innovative 3.0") echo 'selected="selected"'; ?>>INNOVATIVE 3.0</option> 
                  <option   value="RD" <?php if ($_POST['lens_category']=="RD") echo 'selected="selected"'; ?>>RD</option> 
                  <option   value="SelectionRx" <?php if ($_POST['lens_category']=="SelectionRx") echo 'selected="selected"'; ?>>SELECTIONRX</option> 
                  <option   value="Single Vision" <?php if ($_POST['lens_category']=="Single Vision") echo 'selected="selected"'; ?>>SINGLE VISION</option> 
                  <option   value="Sola Easy" <?php if ($_POST['lens_category']=="Sola Easy") echo 'selected="selected"'; ?>>SOLA EASY</option> 
                  <option   value="Trifocal 7x28" <?php if ($_POST['lens_category']=="Trifocal 7x28") echo 'selected="selected"'; ?>>TRIFOCAL 7x28</option>
                  <option   value="Trifocal 8x35" <?php if ($_POST['lens_category']=="Trifocal 8x35") echo 'selected="selected"'; ?>>TRIFOCAL 8x35</option> 
                  <option   disabled="disabled" value="">&nbsp;</option>
                  <option   disabled="disabled" value="">MANUFACTURER*</option>
                  <option   value="IOT" <?php if ($_POST['lens_category']=="IOT") echo 'selected="selected"'; ?>>IOT</option> 
                  <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option>  
                  <option   value="SOLA/ZEISS" <?php if ($_POST['lens_category']=="SOLA/ZEISS") echo 'selected="selected"'; ?>>SOLA/ZEISS</option>  
           </select>
				<?php
                }
				?>
                   
                   </span>
                   </td>
                   
                   
                       <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
				echo 'Prioritaire';
				}else {
				echo 'Rush';
				}
				?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  <select name="rush" class="formText" id="rush">
                    	<option selected="selected" value="no" >No</option>
                      	<option value="yes">Yes</option>                    
					</select></span>
                   </td>
                   </tr>
                   
                   
                   <tr> 
                    <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
				echo 'Courbure de base';
				}else {
				echo 'Base Curve';
				}
				?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  
                  <select name="base_curve" class="formText" id="base_curve">
                    	<option selected="selected" value="" >Select Base Curve</option>
                      	<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>                   
					</select></span>
                   </td></tr>
                   
                                      
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#000099" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[re_pd] . '"'; ?> />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="left" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[le_pd] . '"'; ?> />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[re_pd_near] . '"'; ?> />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[le_pd_near] . '"'; ?> />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[re_height] . '"'; ?> />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[le_height] . '"'; ?> />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   </tr>
               </table>
             </div>
   

             
                <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead"><?php echo $lbl_mywrldcoll_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_pt_txt_pl;?></td>
                   <td align="left" class="formCellNosides">&nbsp;
                     <input name="PT" type="text" class="formText" id="PT" size="2" maxlength="2"  <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[PT] . '"'; ?> />
                     <?php echo $lbl_pt1_pl;?></td>
                   <td align="right" class="formCell"><?php echo $lbl_pa_txt_pl;?></td>
                   <td align="left" class="formCellNosides"><input name="PA" type="text" class="formText" id="PA" size="2" maxlength="2" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[PA] . '"'; ?> />
                     <?php echo $lbl_pa1_pl;?></td>
                   <td align="right" class="formCell"><?php echo $lbl_vertex_txt_pl;?></td>
                   <td align="left" class="formCellNosides"><input name="VERTEX" type="text" class="formText" id="VERTEX" size="2" maxlength="2" <?php if($ChargerDerniereCommande) echo ' value="'.           $DataLastOrderDetail[vertex] . '"'; ?>>
                     <?php echo $lbl_vertex1_pl;?></td>
                   </tr>
               </table>
             </div>   
             
 
             
             <input / type="hidden" name="WARRANTY" ID="WARRANTY" value="0">
            <?php /*?>  <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				   <?php if ($mylang == 'lang_french'){
				echo 'Extra garantie';
				}else {
				echo 'Extra Warranty';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="WARRANTY" class="formText" id="WARRANTY">
                      <option value="0" selected="selected">
                       <?php if ($mylang == 'lang_french'){
						echo "Aucune";
						}else {
						echo "None";
						}
						?>
                      </option>
                      <option value="1">
					    <?php if ($mylang == 'lang_french'){
						echo "1 an (6$ extra)";
						}else {
						echo "1 year ($6 extra)";
						}
						?></option>
                     
                      <option value="2">
                       <?php if ($mylang == 'lang_french'){
						echo "2 ans (10$ extra)";
						}else {
						echo "2 years ($10 extra)";
						}
						?>
                      </option>
                       
                      <option value="gold">
                       <?php if ($mylang == 'lang_french'){
						echo "Garantie Or (20$ extra)";
						}else {
						echo "Gold Warranty ($20 extra)";
						}
						?>
                      </option> 
                      
                     </select>
                     </span>
                      <?php if ($mylang == 'lang_french'){
						echo "<font size=\"3\">Cliquez</font><a style=\"font-size:16px;\" target=\"_blank\" href=\"http://www.direct-lens.com/lensnet/pdf/gold_fr.pdf\"><b> ici </b></a><font size=\"3\">pour plus de renseignements sur le nouveau Programme OR!</font>";
						}else {
						echo "<font size=\"3\">Click</font><a style=\"font-size:16px;\" target=\"_blank\" href=\"http://www.direct-lens.com/lensnet/pdf/gold_en.pdf\"><b> here</b></a><font size=\"3\"> for more information about the New Gold Warranty Plan!</font>";
						}
						?>
                     
                     </td>
                 </tr> 
               </table>
             </div><?php */?> 
             
      
    
       <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				   <?php if ($mylang == 'lang_french'){
				echo 'Frais d\'entrée de données';
				}else {
				echo 'Data entry fee';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">   
                    <?php if ($mylang == 'lang_french'){
				echo '2$ Frais d\'entrée de données';
				}else {
				echo '$2 Data entry fee';
				}
				?>
                 <input name="entry_fee" id="entry_fee" type="checkbox" value="yes" />  </span></td>
                 </tr>
               </table>
             </div> 
             
   
                
          <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="10" bgcolor="#000099" class="tableHead">SPECIAL THICKNESS&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCell">RE CT</td>
                   <td align="left" class="formCellNosides"><input name="RE_CT" type="text" class="formText" id="RE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE CT</td>
                   <td align="left" class="formCellNosides"><input name="LE_CT" type="text" class="formText" id="LE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
               
                   <td align="left" class="formCell">RE ET</td>
                   <td align="left" class="formCellNosides"><input name="RE_ET" type="text" class="formText" id="RE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE ET</td>
                   <td align="left" class="formCellNosides"><input name="LE_ET" type="text" class="formText" id="LE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                 </tr>
               </table>
             </div>   
   
   
   
             
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#000099" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_engrav_txt_pl;?> </td>
                   <td align="left" class="formCellNosides">&nbsp;
                     <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" size="4" maxlength="8" <?php if($ChargerDerniereCommande) echo ' value="'. $DataLastOrderDetail[engraving] . '"'; ?> /></td>
                   <td align="right" class="formCell"><?php echo $lbl_tint_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px"><select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                     <option value="None" <?php if(($ChargerDerniereCommande) && ($DataTintDetail[tint]=='None')) echo ' selected="selected"'; ?>><?php echo $lbl_tint1_pl;?></option>
                     <option value="Solid" <?php if(($ChargerDerniereCommande) && ($DataTintDetail[tint]=='Solid')) echo ' selected="selected"'; ?>><?php echo $lbl_tint2_pl;?></option>
                     <option value="Gradient" <?php if(($ChargerDerniereCommande) && ($DataTintDetail[tint]=='Gradient')) echo ' selected="selected"'; ?>><?php echo $lbl_tint3_pl;?></option>
                     </select> </span></td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_from_txt_pl;?>
                     <input name="FROM_PERC" type="text" class="formText" id="FROM_PERC" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataTintDetail[from_perc] . '"';else echo 'disabled'; ?>  />
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_to_txt_pl;?>
                     <input name="TO_PERC" type="text" class="formText" id="TO_PERC" size="4" maxlength="4" <?php if($ChargerDerniereCommande) echo ' value="'. $DataTintDetail[to_perc] . '"'; else echo 'disabled'; ?> />
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_color_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR"  class="formText" id="TINT_COLOR" <?php if($ChargerDerniereCommande) echo '';else echo 'disabled'; ?>>
                       <option value="Grey" <?php if(($ChargerDerniereCommande) &&  ($DataTintDetail[tint_color]=='Grey')) echo ' selected="selected"'; ?> ><?php echo $lbl_color2_pl;?></option>
                       <option value="Brown" <?php if(($ChargerDerniereCommande) && ($DataTintDetail[tint_color]=='Brown')) echo ' selected="selected"'; ?>>Brown</option>
                       </select>
                     </span></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>
                  <?php if ($mylang == 'lang_french') {
                        echo ': A ou ED ≥ 60 mm : <b>Frais additionnels de 8$</b>';
                        }else{
                        echo ': A or ED ≥ 60 mm: <b>$8 Extra</b>';
                        }?>
                   
                   &nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_eye_txt_pl;?></td>
                   <td align="left" class="formCellNosides">                <?php echo $lbl_a_txt_pl;?>
                     &nbsp;
                     <input name="FRAME_A" type="text" class="formText" id="FRAME_A" size="4" maxlength="4" />
                     &nbsp;
                     
                     <?php echo $lbl_b_txt_pl;?>
                     <input name="FRAME_B" type="text" class="formText" id="FRAME_B" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_ed_txt_pl;?>
                     <input name="FRAME_ED" type="text" class="formText" id="FRAME_ED" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_dbl_txt_pl;?>
                     <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL" size="4" maxlength="4" />
                     &nbsp;<?php echo $lbl_temple_txt_pl;?>
                     <input name="TEMPLE" type="text" class="formText" id="TEMPLE" value="0" size="4" />
                     </td>
                   <td align="right" class="formCell"><?php echo $lbl_type_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCell">
                     <select name="FRAME_TYPE" class="formText"  id="FRAME_TYPE">
                       <option value=""><?php echo $lbl_type1_pl;?></option>
                       <option value="Nylon Groove"><?php echo $lbl_type2_pl;?></option>
                       <option value="Metal Groove"><?php echo $lbl_type3_pl;?></option>
                       <option value="Plastic"><?php echo $lbl_type4_pl;?></option>
                       <option value="Metal"><?php echo $lbl_type5_pl;?></option>
                       <option value="Edge Polish"><?php echo $lbl_type6_pl;?></option>
                       <option value="Drill and Notch"><?php echo $lbl_type7_pl;?></option>
                       </select>                    </td>
                   </tr>
                   
                      
                   
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $lbl_jobtype_txt_pl;?>
                     <select name="JOB_TYPE" class="formText" d="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Uncut"><?php echo $lbl_jobtype1_pl;?></option>
                       <option value="Edge and Mount"><?php echo $lbl_jobtype2_pl;?></option>
                       <option value="remote edging">
						<?php if ($mylang == 'lang_french'){
						echo 'Taillé Non monté';
						}else {
						echo 'Remote Edging';
						}
						?></option>
                       </select>
                     &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_frame_txt_pl;?>
                     <select name="ORDER_TYPE" class="formText" id="ORDER_TYPE" disabled="disabled">
                       <option value="To Follow"><?php echo $lbl_frame1_pl;?></option>
                       <option value="Provide" selected="selected" ><?php echo $lbl_frame2_pl;?></option>
                       </select>
                     &nbsp;&nbsp;&nbsp; </td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell">&nbsp; 
                     <?php echo $lbl_supplier_txt_pl;?> 
                     <input name="SUPPLIER" type="text" class="formText" id="SUPPLIER" size="12"  disabled/>
                     &nbsp;&nbsp;<?php echo $lbl_shapemodel_txt_pl;?> 
                     <input name="FRAME_MODEL" type="text" class="formText" id="FRAME_MODEL" size="12" disabled/>
                     </span> &nbsp;&nbsp;&nbsp;<?php echo $lbl_framemodel_txt_pl;?>
                     <input name="TEMPLE_MODEL" type="text" class="formText" id="TEMPLE_MODEL" size="12" disabled/>                  
                     &nbsp;&nbsp;&nbsp;<?php echo $lbl_color_txt_pl;?> 
                     <input name="COLOR" type="text" class="formText" id="COLOR" size="12"disabled />             </td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">             
                 <?php
				 $querySpecialInst  = "SELECT allow_special_instruction, account_past_due,credit_hold from accounts  WHERE user_id = '". $_SESSION["sessionUser_Id"] . "'";
				 $resultSpecialInst = mysqli_query($con,$querySpecialInst)	or die ("Could not select items");
  				 $DataSpecialInst   = mysqli_fetch_array($resultSpecialInst,MYSQLI_ASSOC);
				 
				 
				 $Account_Past_Due	 = 	 $DataSpecialInst[account_past_due];
				 $CreditHold	     = 	 $DataSpecialInst[credit_hold];
				 if ($DataSpecialInst[allow_special_instruction]=='no') 
				 {?>
                    <input type="hidden" name="SPECIAL_INSTRUCTIONS" value="" /><?php
				        if ($mylang == 'lang_french') {
                        echo 'Toutes les demandes spéciales doivent passer par les produits Direct-Lens ou téléphonez à votre service à la clientèle si besoin.';
                        }else{
                        echo 'All special requests need to go through Direct-Lens products or call your customers services if needed.';
                        }?>
		<?php    }else{ ?>
				 <input type="text" name="SPECIAL_INSTRUCTIONS" size="75"  class="formText"  id="SPECIAL_INSTRUCTIONS">
            <?php } ?>
               </td>
                   </tr>
               </table>
             </div>
              <input name="INTERNAL_NOTE" type="hidden"  id="INTERNAL_NOTE" value="">
            <br />
           <?php if ($Account_Past_Due=='yes'){ ?>
           <div align="center" style="color:#EF0D10; width:770px; font-weight:bold;">
           <?php
            if ($mylang == 'lang_french') {
            	echo '<div>VOTRE COMPTE EST ÉCHU. VEUILLEZ NOUS CONTACTER AU 1-877-570-3522.</div>';
            }else{
            	echo '<div>YOUR ACCOUNT IS PAST DUE. PLEASE CALL 1-855-770-2124.</div>';
            }
			?>
            </div>
            <?php } ?>
            
            
            <?php if ($CreditHold==1){ ?>
           <div align="center" style="color:#EF0D10; width:770px; font-weight:bold;">
           <?php
            if ($mylang == 'lang_french') {
            	echo '<div>Votre compte a atteint la limite de crédit autorisée.<br> Pour transférer vos commandes, un paiement par carte de crédit est requis.<br>Veuillez nous contacter au 1-877-570-3522 pour effectuer un paiement.<br> Si vous nous avez déja un paiement, veuillez ne pas tenir compte de cet avis et contactez notre Service à la clientèle pour débloquer votre compte.</div>';
            }else{
            	echo '<div>Your account has reached the credit limit.<br>To process your order(s), a payment by credit card is required.<br> Please, call 1-855-770-2124 to arrange for payment.<br> If  a payment was already sent, simply disregard this notice and contact us to unlock your account.</div>';
            }
			?>
            </div>
            <?php } ?>
           
            
            
            
            
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="button" onclick="resetform(this.form);" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
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

$sucess_action_redirect= constant('DIRECT_LENS_URL').'/lensnet/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement

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
            <tr bgcolor="#000099">
                   <td width="134" align="center" valign="top"  class="tableHead"><?php echo 'UPLOAD A SHAPE';?></td>
              <tr >
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
                   <input type="file" name="file" onclick="btnupload.disabled=false;btnupload.value='Upload'" id="file" size="40">&nbsp;
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