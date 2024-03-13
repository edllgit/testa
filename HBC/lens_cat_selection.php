<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "Connections/hbc.php";
include "includes/getlang.php";
include "config.inc.php";
global $drawme;		
session_start();			
$prod_table = "ifc_frames_french";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>

<script type="text/javascript">
function CheckSelection() {
document.forms[0].Submit.disabled=false;
}
//-->
</script>

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<?php include "js/lens_selection.js.inc.php";?>
</head>

<body>
<div id="container">
    <div id="masthead">
    	<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
    </div>
	<div id="maincontent">
		<div id="leftColumn">
			<div id="leftnav">
  				<?php include("includes/sideNav.inc.php");	?>
			</div><!--END leftnav-->
		</div><!--END leftcolumn-->
		<div id="rightColumn">
			<form action="frame.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateChoice(this);">
			<div class="loginText">
				<?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
    		</div>
           

           
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="headerBox" class="header">            	
                        <?php if ($mylang == 'lang_french'){?>
                            Achat de Packages
                        <?php }else {?>
                            Purchase Packages
                        <?php }?>                
                        </div>
                    </td>
                 </tr>
            </table>	      
       
       
        <div>
           	<?php
            if ($DataBasket[nbrResult] > 0){
				$AfficherPageCommande = false;
					if ($mylang == 'lang_french') { 
						echo '<p align="center">Pour pouvoir commander des ensemble verres et montures , veuillez d\'abord terminer les commandes de monture de stock qui sont actuellement dans votre panier d\'achat.</p>';
					}else{ 
						echo '<p align="center">To order some packages, please process the stock frame orders that are already in your basket.</p>';
					} 		
			}
			?>
        </div>
           
       <?php  if ($AfficherPageCommande){?>
			<div>
        <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
         <tr>
           <td bgcolor="#17A2D2" class="tableHead">
                &nbsp;&nbsp;&nbsp;
                <?php if ($mylang == 'lang_french'){?>
                    Monture et choix de verres
                <?php }else {?>
                    Frame And Lens Choices
                <?php }?>
           </td>
           <td bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
         </tr>
         <tr>
           <td width="57%" align="center" valign="top" class="formCellNosides grey-border-right"> 
            	<div class="home_features_header">&nbsp;</div>
            		                
            	<div align="center" style="margin-bottom:15px">
            <table width="390" cellpadding="0" cellspacing="3" style="background-color:#eff9fd">
                <tr>
                    <td colspan="2">
                        <div style="padding:5px 0px 5px 0px" class="tableSubHead">
                        <?php if ($mylang == 'lang_french'){?>
                            Choisissez un modèle spécifique / ou sélectionnez un type de verres :
                        <?php }else {?>
                            Choose a Specific Model and/or Select Type of Lenses 
                        <?php }?>                            
                        </div>                            
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="model" id="model" class="formText" style="margin-right:5px; margin-bottom:3px">
                        <option value="none">
                        <?php if ($mylang == 'lang_french'){?>
                            Modèles
                        <?php }else {?>
                            Models
                        <?php }?>
                        </option>
                        <?php
						
						    if ($_SESSION["CompteEntrepot"]  == 'yes'){?>
                              <option disabled="disabled"  value="none">&nbsp;</option>	
                              <option value="OTHER FRAME PROVIDED">OTHER FRAME PROVIDED (No Fee)</option>	
						   <?php }
						
							 if ($_SESSION["CompteEntrepot"]  == 'yes'){?>
                            <option disabled="disabled"  value="none">&nbsp;</option>	
							<option value="none" disabled="disabled">MONTURES GÉNÉRIQUES</option>	
                          	<?php } ?>
                            <option value="MONTURE AUTRES">AUTRE MONTURE FOURNIE (Sans Frais)</option>	
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){?>
                            <option value="MONTURE CEBE">MONTURE CÉBÉ</option>
                            <option disabled="disabled"  value="none">&nbsp;</option>	
                            <option disabled="disabled"  value="none">MONTURES RÉGULIÈRES</option>	
							<?php } ?>

  
                  <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){//COMPTE ENTREPOT
                    	$sql= "SELECT ifc_frames_french.model, ifc_frames_french.misc_unknown_purpose  FROM ifc_frames_french WHERE  active=1 AND display_entrepot='yes' 
						AND ifc_Frames_id NOT IN (463,464,465,501,1672,1671,1670,1665,1666,1667,1668,1669,1673,1674,1873,1874,1875,1664)  GROUP BY model";
					}else{
						$sql= "SELECT ifc_frames_french.model, ifc_frames_french.misc_unknown_purpose  FROM ifc_frames_french WHERE  frame_on_sale='no' AND active=1 AND  display_on_ifcca='yes' GROUP BY model";
					}
						
                        $result=mysql_query($sql)	or die ("ERROR:".mysql_error()." sql=".$sql);
                        while ($item=mysql_fetch_assoc($result)){
                        if ($item[model] <> '')
                        echo "<option value=\"$item[model]\"> $item[model] - $item[misc_unknown_purpose] </option>";			
                        }
                        ?>
                        </select>
                        <?php //echo '<br>'. $sql; ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>                  
                <input type="hidden" name="gender" value="all"  /> 
                <input type="hidden" name="type" value="all"  /> 
                <input type="hidden" name="material" value="all"  />  
                <input type="hidden" name="color" value="all"  />  
                <input type="hidden" name="boxing" value="all"  />  
            
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
            </div>
            

               <div align="center" style="margin-bottom:15px">
            <table width="390" cellpadding="0" cellspacing="3" style="background-color:#eff9fd">
                <tr>
                    <td colspan="2">
                        <div style="padding:5px 0px 5px 0px" class="tableSubHead">
                        <?php if ($mylang == 'lang_french'){?>
                            Choisissez une collection :
                        <?php }else {?>
                            Choose a Specific Collection :
                        <?php }?>                            
                        </div>                            
                    </td>
                </tr>
                <tr>
                    <td>
                    
                    
    <?php  
 	$queryLab = "SELECT main_lab FROM accounts WHERE user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  	$resultLab=mysql_query($queryLab)	or die ("Could not select items");
  	$DataLab=mysql_fetch_array($resultLab);
  	$LabNum=$DataLab[main_lab];
  

	 
	if ($_SESSION["CompteEntrepot"]  == 'yes')
	$queryBrands  = "SELECT count(*) as NbrFree FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'FREE' AND display_entrepot = 'yes' AND active = 1";
	else
	$queryBrands  = "SELECT count(*) as NbrFree FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'FREE' AND display_on_ifcca = 'yes' AND active = 1";
	$resultBrands = mysql_query($queryBrands)	or die ("Could not select items 1");
	$DataBrands   = mysql_fetch_array($resultBrands);
	$NbrAvailableFree = $DataBrands[NbrFree];
		
					



	if ($_SESSION["CompteEntrepot"]  == 'yes')
	$queryBrands  = "SELECT count(*) as NbrFreePlus FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'FREE PLUS' and active=1 AND display_entrepot = 'yes'";
	else
	$queryBrands  = "SELECT count(*) as NbrFreePlus FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'FREE PLUS' and active=1 AND display_on_ifcca = 'yes'";
	$resultBrands = mysql_query($queryBrands)	or die ("Could not select items 2");
	$DataBrands   = mysql_fetch_array($resultBrands);
	$NbrAvailableFreePlus = $DataBrands[NbrFreePlus];
		
					
	if ($_SESSION["CompteEntrepot"]  == 'yes')
	$queryBrands  = "SELECT count(*) as NbrPremium FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'PREMIUM' and active=1 AND display_entrepot = 'yes'";
	else					
	$queryBrands  = "SELECT count(*) as NbrPremium FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'PREMIUM' and active=1 AND display_on_ifcca = 'yes'";
	$resultBrands = mysql_query($queryBrands)	or die ("Could not select items 3");
	$DataBrands   = mysql_fetch_array($resultBrands);
	$NbrAvailablePremium = $DataBrands[NbrPremium];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')
	$queryBrands  = "SELECT count(*) as NbrSafety FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'ArmouRx' and active=1 AND display_entrepot = 'yes'";
	else					
	$queryBrands  = "SELECT count(*) as NbrSafety FROM ifc_frames_french WHERE ifc_frames_french.misc_unknown_purpose = 'ArmouRx' and active=1 AND display_on_ifcca = 'yes'";
	$resultBrands = mysql_query($queryBrands)	or die ("Could not select items 4");
	$DataBrands   = mysql_fetch_array($resultBrands);
	$NbrAvailableSafety = $DataBrands[NbrSafety];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')		
	$queryBrands  = "SELECT count(*) as NbrPremiumPlus FROM ifc_frames_french WHERE  ifc_frames_french.misc_unknown_purpose = 'PREMIUM PLUS' and active=1 AND display_entrepot = 'yes'";
	else
	$queryBrands  = "SELECT count(*) as NbrPremiumPlus FROM ifc_frames_french WHERE  ifc_frames_french.misc_unknown_purpose = 'PREMIUM PLUS' and active=1 AND display_on_ifcca = 'yes'";
	$resultBrands = mysql_query($queryBrands)	or die ("Could not select items 5");
	$DataBrands   = mysql_fetch_array($resultBrands);
	$NbrAvailablePremiumPlus = $DataBrands[NbrPremiumPlus];
					
			
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBrands  = "SELECT count(*) as NbrPremiumPlus FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose IN('FUGLIES_A','FUGLIES_B','FUGLIES_C') AND display_entrepot = 'yes'";
	else
	$queryBrands  	= "SELECT count(*) as NbrPremiumPlus FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose IN('FUGLIES_A','FUGLIES_B','FUGLIES_C') AND display_on_ifcca = 'yes'";
	$resultBrands 	= mysql_query($queryBrands)	or die ("Could not select items 6");
	$DataBrands   	= mysql_fetch_array($resultBrands);
	$NbrAvailableFuglies = $DataBrands[NbrPremiumPlus];
					
					
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryRDV = "SELECT count(*) as NbrRDV FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose = 'RENDEZVOUS' AND display_entrepot = 'yes'";
	else
	$queryRDV 		 = "SELECT count(*) as NbrRDV FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose = 'RENDEZVOUS' AND display_on_ifcca = 'yes'";
	$resultRDV 		 = mysql_query($queryRDV)	or die ("Could not select items 7");
	$DataRDV   		 = mysql_fetch_array($resultRDV);
	$NbrAvailableRDV = $DataRDV[NbrRDV];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrBugetti FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BUGETTI' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrBugetti FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BUGETTI' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 8");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$NbrAvailableBugetti = $DataBugetti[nbrBugetti];
					
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')			
	$queryMilano = "SELECT count(*) as nbrMilano FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose IN ('MILANO 6769','MILANO 6769 CONSIGNE','MILANO 6769 BRERA') AND display_entrepot = 'yes'";
	else
	$queryMilano 		= "SELECT count(*) as nbrMilano FROM ifc_frames_french WHERE ifc_frames_french.active = 1 AND ifc_frames_french.misc_unknown_purpose IN('MILANO 6769','MILANO 6769 CONSIGNE','MILANO 6769 BRERA') AND display_on_ifcca = 'yes'";
	$resultMilano		= mysql_query($queryMilano)	or die ("Could not select items 9");
	$DataMilano  		= mysql_fetch_array($resultMilano);
	$NbrAvailableMilano = $DataMilano[nbrMilano];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti = "SELECT count(*) as nbrISee FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose IN ('ISEE','ISEE 2') AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrISee FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose IN ('ISEE','ISEE 2') AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 10");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableIsee    = $DataBugetti[nbrISee];
					
					
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrISee FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'POLAR' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrISee FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'POLAR' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 11");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablePolar   = $DataBugetti[nbrISee];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrHAGGAR FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HAGGAR' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrHAGGAR FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HAGGAR' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 12");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableHaggar  = $DataBugetti[nbrHAGGAR];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrDiGianni FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DI GIANNI' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrDiGianni FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DI GIANNI' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 13");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableDIGIANNI  = $DataBugetti[nbrDiGianni];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrGOIWEAR FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GO IWEAR' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrGOIWEAR FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GO IWEAR' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 14");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableGoIwear = $DataBugetti[nbrGOIWEAR];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrBlueRay FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BLUE RAY' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrBlueRay FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BLUE RAY' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 15");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableBlueRay = $DataBugetti[nbrBlueRay];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti        = "SELECT count(*) as nbrVarionet FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VARIONET' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrVarionet FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VARIONET' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 16");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableVarionet = $DataBugetti[nbrVarionet];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrTomFord FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TOM FORD' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrTomFord FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TOM FORD' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 17");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableTomFord = $DataBugetti[nbrTomFord];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')			
	$queryBugetti        = "SELECT count(*) as nbrRayban FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'RAY-BAN' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrRayban FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'RAY-BAN' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 18");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableRayban  = $DataBugetti[nbrRayban];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrOXBOW  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXBOW' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrOXBOW FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXBOW' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 19");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableOxbow   = $DataBugetti[nbrOXBOW];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrNike  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NIKE VISION' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrNike  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'NIKE VISION' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 20");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableNike    = $DataBugetti[nbrNike];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrJohnLennon  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'JOHN LENNON' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrJohnLennon  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'JOHN LENNON' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 21");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableJohnLennon   = $DataBugetti[nbrJohnLennon];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti        = "SELECT count(*) as nbrGivenchy  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GIVENCHY' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrGivenchy  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GIVENCHY' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 22");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableGivenchy   = $DataBugetti[nbrGivenchy];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrGant  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GANT' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrGant  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GANT' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 23");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableGant    = $DataBugetti[nbrGant];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrECLIPSE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ECLIPSE' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrECLIPSE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ECLIPSE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 24");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableEclipse    = $DataBugetti[nbrECLIPSE];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrCEBE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'CEBE' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrCEBE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'CEBE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 25");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableCEBE    = $DataBugetti[nbrCEBE];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrCalvinKlein  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'CALVIN KLEIN' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrCalvinKlein  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'CALVIN KLEIN' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 26");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableCalvinKlein    = $DataBugetti[nbrCalvinKlein];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti        = "SELECT count(*) as nbrArrow  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ARROW' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrArrow  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ARROW' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 27");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableArrow   = $DataBugetti[nbrArrow];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrBrendell  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'BRENDELL' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrBrendell  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'BRENDELL' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 28");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableBrendell   = $DataBugetti[nbrBrendell];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrGiaVisto  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GIA VISTO' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrGiaVisto  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'GIA VISTO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 29");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablenGiaVisto   = $DataBugetti[nbrGiaVisto];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrMarcOpolo  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'MARC OPOLO' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrMarcOpolo  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'MARC OPOLO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 30");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablenMarcOpolo   = $DataBugetti[nbrMarcOpolo];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti        = "SELECT count(*) as nbrNurbs  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'NURBS' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrNurbs  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'NURBS' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 31");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablNurbs   = $DataBugetti[nbrNurbs];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrSECG  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'SECG' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrSECG  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'SECG' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 32");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablSECG   = $DataBugetti[nbrSECG];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrSiloam  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'SILOAM' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrSiloam  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'SILOAM' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 33");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablSiloam   = $DataBugetti[nbrSiloam];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrStar FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'STAR' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrStar  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'STAR' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 34");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablStar   = $DataBugetti[nbrStar];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrVeneto FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'VENETO' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrVeneto  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'VENETO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 35");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablVeneto   = $DataBugetti[nbrVeneto];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrZenzero FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ZENZERO' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrZenzero  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose  = 'ZENZERO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 36");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablZenZero   = $DataBugetti[nbrZenzero];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrOptimize FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OPTIMIZE' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrOptimize FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OPTIMIZE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 37");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableOptimize = $DataBugetti[nbrOptimize];

	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti        = "SELECT count(*) as nbrRUIMANNI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'RUIMANNI' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrRUIMANNI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'RUIMANNI' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 38");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableRuimanni = $DataBugetti[nbrRUIMANNI];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti        = "SELECT count(*) as nbrSERMATT FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SERMATT' AND display_entrepot = 'yes'";
	else
	$queryBugetti 		 = "SELECT count(*) as nbrSERMATT FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SERMATT' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 39");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSermatt = $DataBugetti[nbrSERMATT];
	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')			
	$queryBugetti = "SELECT count(*) as nbr19V69 FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = '19V69' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbr19V69 FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = '19V69' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 40");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailablenbrnbr19V69 = $DataBugetti[nbr19V69];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMontana FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMontana FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 41");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableMontana = $DataBugetti[nbrMontana];
	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSunopticK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC K' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSunopticK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC K' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 42");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSunopticK = $DataBugetti[nbrSunopticK];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSunopticAK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC AK' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSunopticAK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC AK' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 43");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSunoptiAK = $DataBugetti[nbrSunopticAK];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti = "SELECT count(*) as nbrSunopticCP FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC CP' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSunopticCP FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC CP' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 44");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSunoptiCP = $DataBugetti[nbrSunopticCP];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSunopticMassimo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC MASSIMO' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSunopticMassimo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUNOPTIC MASSIMO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 45");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSunoptiMassimo = $DataBugetti[nbrSunopticMassimo];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrMontana FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMontana FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 46");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableMontana = $DataBugetti[nbrMontana];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrMontanaPlus FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA +' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMontanaPlus FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MONTANA +' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 47");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableMontanaPlus = $DataBugetti[nbrMontanaPlus];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrNordic FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NORDIC' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrNordic FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NORDIC' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 48");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableNordic = $DataBugetti[nbrNordic];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti = "SELECT count(*) as nbrHumSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HUM SOL' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrHumSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HUM SOL' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 49");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableHumSol  = $DataBugetti[nbrHumSol];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrKingSize FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KING SIZE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrKingSize FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KING SIZE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 50");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableKingSize= $DataBugetti[nbrKingSize];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')						
	$queryBugetti = "SELECT count(*) as nbrErnestHemingway FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ERNEST HEMINGWAY' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrErnestHemingway FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ERNEST HEMINGWAY' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 51");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableErnestHemingway= $DataBugetti[nbrErnestHemingway];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSilhouette FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SILHOUETTE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSilhouette FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SILHOUETTE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 52");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableSilhouette= $DataBugetti[nbrSilhouette];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrCasino FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CASINO' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrCasino FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CASINO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 53");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableCasino  = $DataBugetti[nbrCasino];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJellyBean FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JELLY BEAN' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJellyBean FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JELLY BEAN' AND display_on_ifcca = 'yes'";
	$resultBugetti 		 = mysql_query($queryBugetti)	or die ("Could not select items 54");
	$DataBugetti   		 = mysql_fetch_array($resultBugetti);
	$nbrAvailableJellyBean  = $DataBugetti[nbrJellyBean];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMarcHunter FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MARC HUNTER' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMarcHunter FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MARC HUNTER' AND display_on_ifcca = 'yes'";
	$resultBugetti 			= mysql_query($queryBugetti)	or die ("Could not select items 55");
	$DataBugetti   		 	= mysql_fetch_array($resultBugetti);
	$nbrAvailableMarcHunter  = $DataBugetti[nbrMarcHunter];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrJubille FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JUBILLE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJubille FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JUBILLE' AND display_on_ifcca = 'yes'";
	$resultBugetti 			= mysql_query($queryBugetti)	or die ("Could not select items 56");
	$DataBugetti   		 	= mysql_fetch_array($resultBugetti);
	$nbrAvailableJubille    = $DataBugetti[nbrJubille];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEddieBauer FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EDDIE BAUER' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEddieBauer FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EDDIE BAUER' AND display_on_ifcca = 'yes'";
	$resultBugetti 			= mysql_query($queryBugetti)	or die ("Could not select items 57");
	$DataBugetti   		 	= mysql_fetch_array($resultBugetti);
	$nbrAvailableEddieBauer = $DataBugetti[nbrEddieBauer];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrDaleJr FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DALE JR' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrDaleJr FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DALE JR' AND display_on_ifcca = 'yes'";
	$resultBugetti 			= mysql_query($queryBugetti)	or die ("Could not select items 58");
	$DataBugetti   		 	= mysql_fetch_array($resultBugetti);
	$nbrAvailableDaleJr     = $DataBugetti[nbrDaleJr];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrWoolrich FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'WOOLRICH' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrWoolrich FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'WOOLRICH' AND display_on_ifcca = 'yes'";
	$resultBugetti 			= mysql_query($queryBugetti)	or die ("Could not select items 59");
	$DataBugetti   		 	= mysql_fetch_array($resultBugetti);
	$nbrAvailableWoolrich     = $DataBugetti[nbrWoolrich];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJoanCollins FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JOAN COLLINS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJoanCollins FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JOAN COLLINS' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 60");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableJoanCollins    = $DataBugetti[nbrJoanCollins];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrNickelodeon FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NICKELODEON' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrNickelodeon FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NICKELODEON' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 61");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableNickelodeon    = $DataBugetti[nbrNickelodeon];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')				
	$queryBugetti = "SELECT count(*) as nbrHumphrey FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HUMPHREY' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrHumphrey FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HUMPHREY' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 62");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableHumphrey       = $DataBugetti[nbrHumphrey];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrClipSolaire FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CLIP SOLAIRES' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrClipSolaire FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CLIP SOLAIRES' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 63");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableClipSolaire       = $DataBugetti[nbrClipSolaire];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrDolce FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DOLCE GABANNA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrDolce FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DOLCE GABANNA' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 64");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableDolce          = $DataBugetti[nbrDolce];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrNapoleone FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NAPOLEONE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrNapoleone FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NAPOLEONE' AND display_on_ifcca = 'yes'";
	$resultBugetti 				= mysql_query($queryBugetti)	or die ("Could not select items 65");
	$DataBugetti   		 		= mysql_fetch_array($resultBugetti);
	$nbrAvailableNAPOLEONE      = $DataBugetti[nbrNapoleone];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMugler FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'THIERRY MUGLER' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMugler FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'THIERRY MUGLER' AND display_on_ifcca = 'yes'";
	$resultBugetti 		   = mysql_query($queryBugetti)	or die ("Could not select items 66");
	$DataMugler  		   = mysql_fetch_array($resultBugetti);
	$nbrAvailableMUGLER    = $DataMugler[nbrMugler];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrValerie FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VALERIE SPENCER' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrValerie FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VALERIE SPENCER' AND display_on_ifcca = 'yes'";
	$resultBugetti 		   = mysql_query($queryBugetti)	or die ("Could not select items 67");
	$DataValerie  		   = mysql_fetch_array($resultBugetti);
	$nbrAvailableValerie    = $DataValerie[nbrValerie];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrIKII FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'IKII' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrIKII FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'IKII' AND display_on_ifcca = 'yes'";
	$resultBugetti 		    = mysql_query($queryBugetti)	or die ("Could not select items 68");
	$DataIKII  		        = mysql_fetch_array($resultBugetti);
	$nbrAvailableIKII       = $DataIKII[nbrIKII];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrXONE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'XONE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrXONE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'XONE' AND display_on_ifcca = 'yes'";
	$resultBugetti 		    = mysql_query($queryBugetti)	or die ("Could not select items 69");
	$DataXONE 		        = mysql_fetch_array($resultBugetti);
	$nbrAvailableXONE       = $DataXONE[nbrXONE];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrAZARO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AZARO' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrAZARO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AZARO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		    = mysql_query($queryBugetti)	or die ("Could not select items 70");
	$DataAzaro		        = mysql_fetch_array($resultBugetti);
	$nbrAvailableAzaro      = $DataAzaro[nbrAZARO];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMilanoConsigne FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MILANO 6769 CONSIGNE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMilanoConsigne FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AZARO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		    = mysql_query($queryBugetti)	or die ("Could not select items 71");
	$DataMilanoConsigne		= mysql_fetch_array($resultBugetti);
	$nbrAvailableMilanoConsigne  = $DataMilanoConsigne[nbrMilanoConsigne];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMilanoBrera FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MILANO 6769 BRERA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMilanoBrera FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AZARO' AND display_on_ifcca = 'yes'";
	$resultBugetti 		    = mysql_query($queryBugetti)	or die ("Could not select items 72");
	$DataMilanoBrera	    = mysql_fetch_array($resultBugetti);
	$nbrAvailableMilanoBrera  = $DataMilanoBrera[nbrMilanoBrera];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPUMA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PUMA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPUMA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PUMA' AND display_on_ifcca = 'yes'";
	$resultPuma		        = mysql_query($queryBugetti)	or die ("Could not select items 73");
	$DataPUMA	            = mysql_fetch_array($resultPuma);
	$nbrAvailablePUMA       = $DataPUMA[nbrPUMA];



if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFinezza FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FINEZZA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFinezza FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FINEZZA' AND display_on_ifcca = 'yes'";
	$resultPuma		        = mysql_query($queryBugetti)	or die ("Could not select items 74 ");
	$DataFinezza	        = mysql_fetch_array($resultPuma);
	$nbrAvailableFinezza    = $DataFinezza[nbrFinezza];


if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPascale FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FINEZZA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPascale FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FINEZZA' AND display_on_ifcca = 'yes'";
	$resultPuma		        = mysql_query($queryBugetti)	or die ("Could not select items 75");
	$DataPascale	        = mysql_fetch_array($resultPuma);
	$nbrAvailablePascale    = $DataPascale[nbrPascale];


if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrCharmant FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CHARMANT' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrCharmant FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CHARMANT' AND display_on_ifcca = 'yes'";
	$resultPuma		         = mysql_query($queryBugetti)	or die ("Could not select items76");
	$DataCharmant	         = mysql_fetch_array($resultPuma);
	$nbrAvailableCharmant    = $DataCharmant[nbrCharmant];
	
	
if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrAristar FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARISTAR' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrAristar FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARISTAR' AND display_on_ifcca = 'yes'";
	$resultPuma		         = mysql_query($queryBugetti)	or die ("Could not select items77");
	$DataAristar             = mysql_fetch_array($resultPuma);
	$nbrAvailableAristar     = $DataAristar[nbrAristar];



if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEsprit FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ESPRIT' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEsprit FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ESPRIT' AND display_on_ifcca = 'yes'";
	$resultPuma		         = mysql_query($queryBugetti)	or die ("Could not select items78");
	$DataEsprit              = mysql_fetch_array($resultPuma);
	$nbrAvailableEsprit      = $DataEsprit[nbrEsprit];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSeventeen FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SEVENTEEN' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSeventeen FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SEVENTEEN' AND display_on_ifcca = 'yes'";
	$resultPuma		         = mysql_query($queryBugetti)	or die ("Could not select items79");
	$DataSeventeen           = mysql_fetch_array($resultPuma);
	$nbrAvailableSeventeen   = $DataSeventeen[nbrSeventeen];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrElegante FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELEGANTE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrElegante FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELEGANTE' AND display_on_ifcca = 'yes'";
	$resultPuma		         = mysql_query($queryBugetti)	or die ("Could not select items80");
	$DataElegante            = mysql_fetch_array($resultPuma);
	$nbrAvailableElegante    = $DataElegante[nbrElegante];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPeace FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PEACE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPeace FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PEACE' AND display_on_ifcca = 'yes'";
	$resultPuma		      = mysql_query($queryBugetti)	or die ("Could not select items81");
	$DataPeace            = mysql_fetch_array($resultPuma);
	$nbrAvailablePeace    = $DataPeace[nbrPeace];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrReflexion FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'REFLEXION' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrReflexion FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'REFLEXION' AND display_on_ifcca = 'yes'";
	$resultPuma		     	 = mysql_query($queryBugetti)	or die ("Could not select items82");
	$DataReflexion           = mysql_fetch_array($resultPuma);
	$nbrAvailableReflexion   = $DataReflexion[nbrReflexion];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFocus FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FOCUS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFocus FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FOCUS' AND display_on_ifcca = 'yes'";
	$resultPuma		     	 = mysql_query($queryBugetti)	or die ("Could not select items83");
	$DataFocus               = mysql_fetch_array($resultPuma);
	$nbrAvailableFocus       = $DataFocus[nbrFocus];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFelix FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FELIX MARCS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFelix FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FELIX MARCS' AND display_on_ifcca = 'yes'";
	$resultPuma		     	 = mysql_query($queryBugetti)	or die ("Could not select items84");
	$DataFelix               = mysql_fetch_array($resultPuma);
	$nbrAvailableFelix      = $DataFelix[nbrFelix];
	
    
    
    
    if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrArmani FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARMANI' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrArmani FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARMANI' AND display_on_ifcca = 'yes'";
	$resultPuma		     	 = mysql_query($queryBugetti)	or die ("Could not select items85");
	$DataArmani              = mysql_fetch_array($resultPuma);
	$nbrAvailableArmani      = $DataArmani[nbrArmani];
	
	
	 if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGenevieve FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGenevieve FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE' AND display_on_ifcca = 'yes'";
	$resultPuma		     	 = mysql_query($queryBugetti)	or die ("Could not select items86");
	$DataGenevieve           = mysql_fetch_array($resultPuma);
	$nbrAvailableGenevieve   = $DataGenevieve[nbrGenevieve];
	
	
	 if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGiovani_di_venezi FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GIOVANI DI VENEZI' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGiovani_di_venezi FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GIOVANI DI VENEZI' AND display_on_ifcca = 'yes'";
	$resultPuma		     			 = mysql_query($queryBugetti)	or die ("Could not select items87");
	$DataGiovaniDiVenizi   		 	 = mysql_fetch_array($resultPuma);
	$nbrAvailableGiovani_di_venezi   = $DataGiovaniDiVenizi[nbrGiovani_di_venezi];
	
	
	 if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrIdeal FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'IDEAL' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrIdeal FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'IDEAL' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items88");
	$DataIdeal   			= mysql_fetch_array($resultPuma);
	$nbrAvailablIdeal  		= $DataIdeal[nbrIdeal];
	
	
	 if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrLacoste FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'LACOSTE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrLacoste FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'LACOSTE' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items89");
	$DataLacoste   			= mysql_fetch_array($resultPuma);
	$nbrAvailablLacoste		= $DataLacoste[nbrLacoste];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModelli FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODELLI' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModelli FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODELLI' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items90");
	$DataModelli  			= mysql_fetch_array($resultPuma);
	$nbrAvailablModelli		= $DataModelli[nbrModelli];
	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModern FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModern FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items91");
	$DataModern  			= mysql_fetch_array($resultPuma);
	$nbrAvailablModern		= $DataModern[nbrModern];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModernTimes FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN TIMES' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModernTimes FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN TIMES' AND display_on_ifcca = 'yes'";
	$resultPuma		     		= mysql_query($queryBugetti)	or die ("Could not select items92");
	$DataModernTimes 			= mysql_fetch_array($resultPuma);
	$nbrAvailablModernTimes		= $DataModernTimes[nbrModernTimes];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModz FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODZ' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModz FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODZ' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items93");
	$DataModz 			    = mysql_fetch_array($resultPuma);
	$nbrAvailablModz		= $DataModz[nbrModz];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModzKids FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODZ KIDS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModzKids FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODZ KIDS' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items94");
	$DataModzKids 			= mysql_fetch_array($resultPuma);
	$nbrAvailablModzKids		= $DataModzKids[nbrModzKids];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTiffany FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TIFFANY & CO' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTiffany FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TIFFANY & CO' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items95");
	$DataTiffany			= mysql_fetch_array($resultPuma);
	$nbrAvailablTiffany		= $DataTiffany[nbrTiffany];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrVicrola FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VICROLA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrVicrola FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VICROLA' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items96");
	$DataVicrola			= mysql_fetch_array($resultPuma);
	$nbrAvailablVicrola		= $DataVicrola[nbrVicrola];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSuperClip FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUPER CLIP' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSuperClip FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SUPER CLIP' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items97");
	$DataSuperClip			= mysql_fetch_array($resultPuma);
	$nbrAvailablSuperClip	= $DataSuperClip[nbrSuperClip];
	
	
		
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrArgos FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARGOS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrArgos FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARGOS' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items98");
	$DataArgos				= mysql_fetch_array($resultPuma);
	$nbrAvailableArgos   	= $DataArgos[nbrArgos];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrCarisma FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CARISMA' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrCarisma FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CARISMA' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items99");
	$DataCarisma			= mysql_fetch_array($resultPuma);
	$nbrAvailableCarisma   	= $DataCarisma[nbrCarisma];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrAdidas FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ADIDAS' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrAdidas FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ADIDAS' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items100");
	$DataAdidas				= mysql_fetch_array($resultPuma);
	$nbrAvailableAdidas   	= $DataAdidas[nbrAdidas];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrInface FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'INFACE' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrInface FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'INFACE' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items101");
	$DataInface				= mysql_fetch_array($resultPuma);
	$nbrAvailableInface  	= $DataInface[nbrInface];
	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEdel FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EDEL' AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEdel FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EDEL' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items102");
	$DataEdel				= mysql_fetch_array($resultPuma);
	$nbrAvailableEdel  		= $DataEdel[nbrEdel];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPRO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PRO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPRO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PRO' AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailablePro		= $DataaPro[nbrPRO];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJilSander FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JIL SANDER'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJilSander FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JIL SANDER'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableJilSander  = $DataaPro[nbrJilSander];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJilSanderSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JIL SANDER SOL'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJilSanderSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JIL SANDER SOL'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableJilSanderSol  = $DataaPro[nbrJilSanderSol];
	
		
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrVALENTINO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VALENTINO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrVALENTINO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VALENTINO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailablenbrVALENTINO  = $DataaPro[nbrVALENTINO];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrHaggarHFT FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HAGGAR HFT'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrHaggarHFT FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'HAGGAR HFT'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailablenbrHAGGARHFT  = $DataaPro[nbrHaggarHFT];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrENHANCE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ENHANCE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrENHANCE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ENHANCE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableENHANCE  = $DataaPro[nbrENHANCE];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrKubik FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KUBIK'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrKubik FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KUBIK'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableKubik      = $DataaPro[nbrKubik];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPolarSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'POLAR SOL'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPolarSol FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'POLAR SOL'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailablePolarSOL   = $DataaPro[nbrPolarSol];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrCZONE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CZONE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrCZONE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CZONE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableCZONE   	= $DataaPro[nbrCZONE];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrCarrera FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CARRERA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrCarrera FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'CARRERA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableCarrera   	= $DataaPro[nbrCarrera];
		
		
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrOXYDO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXYDO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrOXYDO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXYDO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableOXYDO   	= $DataaPro[nbrOXYDO];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMARCBYMARC FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MARCBYMARC'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMARCBYMARC FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MARCBYMARC'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableMARCBYMARC   	= $DataaPro[nbrMARCBYMARC];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMARCetCO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAX&CO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMARCetCO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAX&CO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableMaxetCO   	= $DataaPro[nbrMARCetCO];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTHilfiger FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'THILFIGER'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTHilfiger FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'THILFIGER'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableTHilfiger   = $DataaPro[nbrTHilfiger];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTMichaelKors FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MICHAEL KORS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTMichaelKors FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MICHAEL KORS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableMichaelKors   = $DataaPro[nbrTMichaelKors];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTAllK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ALL-K'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTAllK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ALL-K'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableMAllK   	= $DataaPro[nbrTAllK];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFASHIONTABULOUS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FASHIONTABULOUS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFASHIONTABULOUS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FASHIONTABULOUS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableFashionTabulous   	= $DataaPro[nbrFASHIONTABULOUS];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrUROCK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'U ROCK'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrUROCK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'U ROCK'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableURock   	= $DataaPro[nbrUROCK];	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModernPlasticI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN PLASTICS I'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModernPlasticI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN PLASTICS I'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableModernPlasticI   	= $DataaPro[nbrModernPlasticI];	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGenevieveBoutique FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE BOUTIQUE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGenevieveBoutique FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE BOUTIQUE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableGenevieveBoutique   	= $DataaPro[nbrGenevieveBoutique];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGenevievePD FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE PARIS DESIGN'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGenevievePD FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GENEVIEVE PARIS DESIGN'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableGenevieveParisDesign  	= $DataaPro[nbrGenevievePD];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrModernPlasticII FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN PLASTIC II'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrModernPlasticII FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MODERN PLASTIC II'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAvailableModernPlasticII  	= $DataaPro[nbrModernPlasticII];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrVinilFactory FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VINYL FACTORY'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrVinilFactory FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VINYL FACTORY'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrVinilFactory  	    = $DataaPro[nbrVinilFactory];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTOKADO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TOKADO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTOKADO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TOKADO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrTokado  	  	    = $DataaPro[nbrTOKADO];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrVICOMTEA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VICOMTE A'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrVICOMTEA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VICOMTE A'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrVICOMTEA 	  	    = $DataaPro[nbrVICOMTEA];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrProfilo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PROFILO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrProfilo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PROFILO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrProfilo 	  	    = $DataaPro[nbrProfilo];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTrussardi FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TRUSSARDI'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTrussardi FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TRUSSARDI'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrTrussardi 	  	    = $DataaPro[nbrTrussardi];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrAfterbang FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AFTERBANG'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrAfterbang FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AFTERBANG'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrnbrAfterbang	  	    = $DataaPro[nbrAfterbang];
	
if ($_SESSION["CompteEntrepot"]  == 'yes')					
$queryBugetti = "SELECT count(*) as nbrJohan FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JOHANN VON GOISERN'  AND display_entrepot = 'yes'";
else
$queryBugetti = "SELECT count(*) as nbrJohan FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JOHANN VON GOISERN'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrJohan	  	   	    = $DataaPro[nbrJohan];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrELLE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELLE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrELLE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELLE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrELLE	  	   	    = $DataaPro[nbrELLE];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSYOPTICAL FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SYOPTICAL'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSYOPTICAL FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SYOPTICAL'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrSYOPTICAL	  	   	= $DataaPro[nbrSYOPTICAL];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGENY FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GEN-Y'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGENY FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GEN-Y'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrGENY	  	   	= $DataaPro[nbrGENY];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPerfecto FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PERFECTO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPerfecto FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PERFECTO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrPerfecto	  	   	= $DataaPro[nbrPerfecto];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrROGER FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ROGER'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrROGER FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ROGER'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrRoger	  	      	= $DataaPro[nbrROGER];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMaxetTiber FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAXetTIBER'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMaxetTiber FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAXetTIBER'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrMaxetTiber	  	    = $DataaPro[nbrMaxetTiber];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrKACTUS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KACTUS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrKACTUS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KACTUS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrKactus	  	      	= $DataaPro[nbrKACTUS];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrBENCH FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BENCH'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrBENCH FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BENCH'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrBench	  	      	= $DataaPro[nbrBENCH];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEYEDANCE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EYEDANCE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEYEDANCE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EYEDANCE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrEYEDANCE	  	      	= $DataaPro[nbrEYEDANCE];
	
		if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrVisible FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VISIBLE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrVisible FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'VISIBLE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrVISIBLE	  	      	= $DataaPro[nbrVisible];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrXLOOK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'X-LOOK'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrXLOOK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'X-LOOK'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrXLOOK	  	      	= $DataaPro[nbrXLOOK];
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJISCO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JISCO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJISCO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JISCO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrJISCO  	      	    = $DataaPro[nbrJISCO];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrOXIBIS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXIBIS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrOXIBIS FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'OXIBIS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrOXIBIS  	      	    = $DataaPro[nbrOXIBIS];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEXALTO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EXALTO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEXALTO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EXALTO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrEXALTO  	      	    = $DataaPro[nbrEXALTO];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrDUTZ FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DUTZ'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrDUTZ FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DUTZ'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrDUTZ  	      	    = $DataaPro[nbrDUTZ];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFACETALK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FACETALK'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFACETALK FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FACETALK'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrFACETALK  	      	    = $DataaPro[nbrFACETALK];
	
	

	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrKANGLE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KANGLE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrKANGLE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'KANGLE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrKANGLE	      	    = $DataaPro[nbrKANGLE];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrPRIIVALI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PRIIVALI'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrPRIIVALI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'PRIIVALI'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrPRIIVALI	      	    = $DataaPro[nbrPRIIVALI];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrDEEP FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DEEPS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrDEEP FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'DEEPS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrDEEP	      	    = $DataaPro[nbrDEEP];



	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrEgo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EGO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrEgo FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'EGO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrEGO  	      	    = $DataaPro[nbrEgo];
	
	
		if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrONESUN FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ONESUN'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrONESUN FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ONESUN'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrONESUN  	      	    = $DataaPro[nbrONESUN];
	
	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrALPHA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ALPHA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrALPHA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ALPHA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrALPHA  	      	    = $DataaPro[nbrALPHA];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMORRIZ  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MORRIZ OF SWEDEN'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMORRIZ FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MORRIZ OF SWEDEN'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrMORRIZ	      	    = $DataaPro[nbrMORRIZ];
		
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMILANOYoung  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MILANO YOUNG'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMILANOYoung FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MILANO YOUNG'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrMilanoYOUNG      	    = $DataaPro[nbrMILANOYoung];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSORA  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SORA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSORA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'SORA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrSORA      	        = $DataaPro[nbrSORA];	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMOSKI  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MOSKI'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMOSKI FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MOSKI'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrMOSKI      	        = $DataaPro[nbrMOSKI];	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrNouvelleTendance  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NOUVELLE TENDANCE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrNouvelleTendance FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'NOUVELLE TENDANCE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrNouvelleTendance      	        = $DataaPro[nbrNouvelleTendance];	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrElegantia  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELEGANTIA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrElegantia FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ELEGANTIA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrElegantia      	    = $DataaPro[nbrElegantia];	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrARTLIFE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARTLIFE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrARTLIFE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ARTLIFE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrARTLIFE      	    = $DataaPro[nbrARTLIFE];	
			
			
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrAIEEYEWEAR  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AIE EYEWEAR'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrAIEEYEWEAR FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'AIE EYEWEAR'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrAieEYEWEAR      	= $DataaPro[nbrAIEEYEWEAR];	
			
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrWAHO  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'WAHO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrWAHO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'WAHO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrWAHO     			= $DataaPro[nbrWAHO];		
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrMAGNETO  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAGNETO'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrMAGNETO FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'MAGNETO'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrMAGNETO    			= $DataaPro[nbrMAGNETO];		
	

	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrBUNOVIATA  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BUNOVIATA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrBUNOVIATA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'BUNOVIATA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrBUNOVIATA    		= $DataaPro[nbrBUNOVIATA];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrESQUIRE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ESQUIRE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrESQUIRE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'ESQUIRE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrESQUIRE    			= $DataaPro[nbrESQUIRE];	
	
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrGBPlus  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GB+'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrGBPlus FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'GB+'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrGBPlus    			= $DataaPro[nbrGBPlus];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrFloats  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FLOATS'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrFloats FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'FLOATS'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrFloats    			= $DataaPro[nbrFloats];
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrSTYRKA  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'STYRKA'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrSTYRKA FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'STYRKA'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrSTYRKA    			= $DataaPro[nbrSTYRKA];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrJUNGLE  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JUNGLE'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrJUNGLE FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'JUNGLE'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrJUNGLE    			= $DataaPro[nbrJUNGLE];
	
	
	if ($_SESSION["CompteEntrepot"]  == 'yes')					
	$queryBugetti = "SELECT count(*) as nbrTMX  FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TMX'  AND display_entrepot = 'yes'";
	else
	$queryBugetti = "SELECT count(*) as nbrTMX FROM ifc_frames_french WHERE ifc_frames_french.active = 1  AND ifc_frames_french.misc_unknown_purpose = 'TMX'  AND display_on_ifcca = 'yes'";
	$resultPuma		     	= mysql_query($queryBugetti)	or die ("Could not select items103" . '<br>'.$queryBugetti);
	$DataaPro				= mysql_fetch_array($resultPuma);
	$nbrTMX    				= $DataaPro[nbrTMX];	
	

	?>
    
    

 


                        <select name="collection" id="collection" class="formText" style="margin-right:5px; margin-bottom:3px">
                            <option value="none">Collection</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="19V69">19V69 (<?php echo  $nbrAvailablenbrnbr19V69; ?>)</option> 
                            <option value="AFTERBANG">AFTERBANG (<?php echo  $nbrnbrAfterbang; ?>)</option> 
                            <option value="AIE EYEWEAR">AIE EYEWEAR (<?php echo  $nbrAieEYEWEAR; ?>)</option> 
                            <option value="ALPHA">ALPHA (<?php echo  $nbrALPHA ; ?>)</option> 
                            <option value="ALL-K">ALL-K (<?php echo  $nbrAvailableMAllK; ?>)</option> 
                            <option value="ADIDAS">ADIDAS (<?php echo   $nbrAvailableAdidas ; ?>)</option> 
                            <option value="ARGOS">ARGOS (<?php echo   $nbrAvailableArgos ; ?>)</option> 
                            <option value="ARMANI">ARMANI (<?php echo $nbrAvailableArmani ; ?>)</option> 
                            <option value="ARISTAR">ARISTAR (<?php echo  $nbrAvailableAristar; ?>)</option> 
                            <option value="ARTLIFE">ARTLIFE (<?php echo  $nbrARTLIFE  ; ?>)</option> 
                            <option value="ARROW">ARROW (<?php echo $nbrAvailableArrow; ?>)</option> 
                            <option value="AZARO">AZARO (<?php echo $nbrAvailableAzaro; ?>)</option> 
                            <option value="BENCH">BENCH (<?php echo  $nbrBench; ?>)</option> 
                            <option value="BLUE RAY">BLUE RAY (<?php echo $nbrAvailableBlueRay ; ?>)</option>
                            <?php } ?>
                           
                            
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="BRENDELL">BRENDELL (<?php echo $nbrAvailableBrendell ; ?>)</option>
                            <?php } ?>
                            <option value="BUGETTI">BUGETTI (<?php echo $NbrAvailableBugetti ; ?>)</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                             <option value="BUNOVIATA">BUNOVIATA (<?php echo   $nbrBUNOVIATA ; ?>)</option>
                            <option value="CALVIN KLEIN">CALVIN KLEIN (<?php echo $nbrAvailableCalvinKlein; ?>)</option>
                            <option value="CARISMA">CARISMA (<?php echo   $nbrAvailableCarisma; ?>)</option>
                            <option value="CARRERA">CARRERA (<?php echo   $nbrAvailableCarrera  ; ?>)</option>
                            <option value="CASINO">CASINO (<?php echo $nbrAvailableCasino; ?>)</option>
                            <option value="CEBE">CEBE (<?php echo $nbrAvailableCEBE; ?>)</option>
                            <option value="CHARMANT">CHARMANT (<?php echo $nbrAvailableCharmant; ?>)</option>
                            <option value="CLIP SOLAIRES">CLIP SOLAIRES (<?php echo $nbrAvailableClipSolaire ; ?>)</option>
                            <option value="CZONE">CZONE (<?php echo $nbrAvailableCZONE  ; ?>)</option>
                            <option value="DALE JR">DALE JR (<?php echo $nbrAvailableDaleJr ; ?>)</option>
                            <option value="DEEPS">DEEPS (<?php echo  $nbrDEEP ; ?>)</option>
                            <option value="DI GIANNI">DI GIANNI (<?php echo $nbrAvailableDIGIANNI ; ?>)</option>
                            <option value="DOLCE GABANNA">DOLCE GABANNA (<?php echo $nbrAvailableDolce ; ?>)</option>
                             <option value="DUTZ">DUTZ (<?php echo $nbrDUTZ   ; ?>)</option>
							
							<?php } ?>
                            
                            
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="ECLIPSE">ECLIPSE (<?php echo $nbrAvailableEclipse ; ?>)</option>
                            <option value="EDDIE BAUER">EDDIE BAUER (<?php echo  $nbrAvailableEddieBauer ; ?>)</option>
                            <option value="EDEL">EDEL (<?php echo  $nbrAvailableEdel ; ?>)</option>
                            <option value="EGO">EGO (<?php echo  $nbrEGO ; ?>)</option>
                            
                            <option value="ELEGANTE">ELEGANTE (<?php echo  $nbrAvailableElegante ; ?>)</option> 
                            <option value="ELEGANTIA">ELEGANTIA (<?php echo   $nbrElegantia ; ?>)</option> 
                            <option value="ELLE">ELLE (<?php echo  $nbrELLE ; ?>)</option>  
                            <option value="ENHANCE">ENHANCE (<?php echo   $nbrAvailableENHANCE ; ?>)</option>  
                            <option value="ERNEST HEMINGWAY">ERNEST HEMINGWAY (<?php echo $nbrAvailableErnestHemingway ; ?>)</option>
                            <option value="ESPRIT">ESPRIT (<?php echo  $nbrAvailableEsprit  ; ?>)</option>
                            <option value="ESQUIRE">ESQUIRE (<?php echo   $nbrESQUIRE   ; ?>)</option>
                            <option value="EXALTO">EXALTO (<?php echo  $nbrEXALTO  ; ?>)</option>
                          
                            <option value="EYEDANCE">EYEDANCE (<?php echo   $nbrEYEDANCE  ; ?>)</option>
                            <option value="FACETALK">FACETALK (<?php echo     $nbrFACETALK   ; ?>)</option>
                            <option value="FASHIONTABULOUS">FASHIONTABULOUS (<?php echo  $nbrAvailableFashionTabulous  ; ?>)</option>
                            <option value="FELIX MARCS">FELIX MARCS (<?php echo  $nbrAvailableFelix  ; ?>)</option>
                            <option value="FINEZZA">FINEZZA (<?php echo  $nbrAvailableFinezza  ; ?>)</option>
                            <option value="FLOATS">FLOATS (<?php echo  $nbrFloats   ; ?>)</option>
                            <?php } ?>
                           
                            <option value="FREE">FREE (<?php echo $NbrAvailableFree ; ?>)</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="FREE PLUS">FREE PLUS (<?php echo $NbrAvailableFreePlus ; ?>)</option></option>
                            <option value="FOCUS">FOCUS (<?php echo $nbrAvailableFocus ; ?>)</option></option>
                            <?php }?>
                            <option value="FUGLIES">FUGLIES (<?php echo $NbrAvailableFuglies ; ?>)</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="GANT">GANT (<?php echo $nbrAvailableGant; ?>)</option>
                            <option value="GB+">GB+ (<?php echo $nbrGBPlus; ?>)</option>
                            <option value="GENEVIEVE BOUTIQUE">GENEVIEVE BOUTIQUE (<?php echo  $nbrAvailableGenevieveBoutique; ?>)</option>
                            <option value="GENEVIEVE PARIS DESIGN">GENEVIEVE PARIS DESIGN (<?php echo   $nbrAvailableGenevieveParisDesign; ?>)</option>
                            <option value="GEN-Y">GEN-Y (<?php echo  $nbrGENY; ?>)</option>
                            <option value="GIA VISTO">GIA VISTO (<?php echo  $nbrAvailablenGiaVisto; ?>)</option>
                            <option value="GIOVANI DI VENEZI">GIOVANI DI VENEZI (<?php echo  $nbrAvailableGiovani_di_venezi; ?>)</option>
                            <option value="GIVENCHY">GIVENCHY (<?php echo $nbrAvailableGivenchy; ?>)</option>
                            <option value="GO IWEAR">GO IWEAR (<?php echo $nbrAvailableGoIwear; ?>)</option>
                            <option value="HAGGAR">HAGGAR (<?php echo  $nbrAvailableHaggar ; ?>)</option>	
                            <option value="HAGGAR HFT">HAGGAR HFT (<?php echo  $nbrAvailablenbrHAGGARHFT ; ?>)</option>	
                            <option value="HUMPHREY">HUMPHREY (<?php echo  $nbrAvailableHumphrey  ; ?>)</option>
                            <option value="HUM SOL">HUM SOL (<?php echo  $nbrAvailableHumSol ; ?>)</option>
                            <option value="IDEAL">IDEAL (<?php echo   $nbrAvailablIdeal  ; ?>)</option>
                            <option value="IKII">IKII (<?php echo  $nbrAvailableIKII ; ?>)</option>
                            <option value="INFACE">INFACE (<?php echo   $nbrAvailableInface  ; ?>)</option>
                           
                            <?php } ?>
                            <option value="ISEE">ISEE (<?php echo $nbrAvailableIsee  ; ?>)</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="JELLY BEAN">JELLY BEAN (<?php echo $nbrAvailableJellyBean  ; ?>)</option>
                            <option value="JIL SANDER">JIL SANDER (<?php echo $nbrAvailableJilSander; ?>)</option>
                            <option value="JIL SANDER SOL">JIL SANDER SOL (<?php echo $nbrAvailableJilSanderSol; ?>)</option>
                            <option value="JISCO">JISCO (<?php echo $nbrJISCO  ; ?>)</option>
                            
                            <option value="JOAN COLLINS">JOAN COLLINS (<?php echo $nbrAvailableJoanCollins; ?>)</option>
                            <option value="JOHAN VON GOISERN">JOHAN VON GOISERN (<?php echo  $nbrJohan; ?>)</option>
                            <option value="JOHN LENNON">JOHN LENNON (<?php echo $nbrAvailableJohnLennon; ?>)</option>
                            <option value="JUBILLE">JUBILLE (<?php echo $nbrAvailableJubille; ?>)</option>
                            <option value="JUNGLE">JUNGLE (<?php echo $nbrJUNGLE ; ?>)</option>
                            
                            <option value="KACTUS">KACTUS (<?php echo  $nbrKactus; ?>)</option>
                            <option value="KING SIZE">KING SIZE (<?php echo  $nbrAvailableKingSize; ?>)</option>
                            <option value="KANGLE">KANGLE (<?php echo  $nbrKANGLE; ?>)</option>  
                            <option value="KUBIK">KUBIK (<?php echo  $nbrAvailableKubik; ?>)</option>                           
                            <option value="LACOSTE">LACOSTE (<?php echo  $nbrAvailablLacoste; ?>)</option>
                            <option value="MAGNETO">MAGNETO (<?php echo   $nbrMAGNETO; ?>)</option>
                            <option value="MARCBYMARC">MARCBYMARC (<?php echo   $nbrAvailableMARCBYMARC; ?>)</option>
                            <option value="MARC HUNTER">MARC HUNTER (<?php echo  $nbrAvailableMarcHunter; ?>)</option>
                            <option value="MARC OPOLO">MARC OPOLO (<?php echo $nbrAvailablenMarcOpolo; ?>)</option>
                            <option value="MAX&CO">MAX&CO(<?php echo $nbrAvailableMaxetCO; ?>)</option>
                            <option value="MAX&TIBER">MAX&TIBER (<?php echo  $nbrMaxetTiber ; ?>)</option>
                            <option value="MICHAEL KORS">MICHAEL KORS (<?php echo  $nbrAvailableMichaelKors ; ?>)</option>
                            <option value="MILANO 6769">MILANO 6769 (<?php echo $NbrAvailableMilano ; ?>)</option>
                            <option value="MILANO 6769 CONSIGNE">MILANO 6769 CONSIGNE (<?php echo $nbrAvailableMilanoConsigne ; ?>)</option>
                            <option value="MILANO 6769 BRERA">MILANO 6769 BRERA (<?php echo $nbrAvailableMilanoBrera ; ?>)</option>
                            <option value="MILANO YOUNG">MILANO YOUNG (<?php echo $nbrMilanoYOUNG   ; ?>)</option>
                            
                            <?php }?>
                           
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="MODELLI">MODELLI (<?php echo  $nbrAvailablModelli; ?>)</option>
                            <option value="MODERN TIMES">MODERN TIMES (<?php echo $nbrAvailablModernTimes	; ?>)</option>
                            <option value="MODERN PLASTICS I">MODERN PLASTICS I(<?php echo  $nbrAvailableModernPlasticI	; ?>)</option>
                            <option value="MODERN PLASTICS II">MODERN PLASTICS II(<?php echo   $nbrAvailableModernPlasticII	; ?>)</option>
                            <option value="MODZ">MODZ (<?php echo $nbrAvailablModz	; ?>)</option>
                            <option value="MODZ KIDS">MODZ KIDS (<?php echo $nbrAvailablModzKids; ?>)</option>
                            <option value="MONTANA">MONTANA (<?php echo $nbrAvailableMontana; ?>)</option>
                            <option value="MONTANA +">MONTANA + (<?php echo $nbrAvailableMontanaPlus; ?>)</option>
                            <option value="MORRIZ OF SWEDEN">MORRIZ OF SWEDEN (<?php echo $nbrMORRIZ; ?>)</option>
                            <option value="MOSKI">MOSKI (<?php echo $nbrMOSKI; ?>)</option>
                            <option value="NAPOLEONE">NAPOLEONE (<?php echo $nbrAvailableNAPOLEONE; ?>)</option>
                            <option value="NICKELODEON">NICKELODEON (<?php echo $nbrAvailableNickelodeon; ?>)</option>
                            <option value="NIKE VISION">NIKE VISION (<?php echo $nbrAvailableNike; ?>)</option>
                            <option value="NORDIC">NORDIC (<?php echo $nbrAvailableNordic; ?>)</option>
                            <option value="NOUVELLE TENDANCE">NOUVELLE TENDANCE (<?php echo $nbrNouvelleTendance; ?>)</option>
                            <option value="NURBS">NURBS (<?php echo  $nbrAvailablNurbs ; ?>)</option>
                            <option value="ONESUN">ONESUN (<?php echo   $nbrONESUN ; ?>)</option>
                           
                            <option value="OPTIMIZE">OPTIMIZE (<?php echo $nbrAvailableOptimize ; ?>)</option>
                            <option value="OXBOW">OXBOX (<?php echo $nbrAvailableOxbow ; ?>)</option>
                            <option value="OXIBIS">OXIBIS (<?php echo $nbrOXIBIS ; ?>)</option>
                            <option value="OXYDO">OXYDO (<?php echo $nbrAvailableOXYDO ; ?>)</option>
                            
                            <?php }?>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="PASCALE">PASCALE (<?php echo $nbrAvailablePascale  ; ?>)</option></option>
                            <option value="PEACE">PEACE (<?php echo $nbrAvailablePeace ; ?>)</option></option>
                            <option value="PERFECTO">PERFECTO (<?php echo  $nbrPerfecto ; ?>)</option></option>
                            <option value="POLAR">POLAR (<?php echo $nbrAvailablePolar ; ?>)</option></option>
                            <option value="POLAR SOL">POLAR SOL (<?php echo  $nbrAvailablePolarSOL ; ?>)</option></option>
                            <option value="PREMIUM">PREMIUM (<?php echo $NbrAvailablePremium ; ?>)</option></option>
                            <option value="PREMIUM PLUS">PREMIUM PLUS (<?php echo $NbrAvailablePremiumPlus ; ?>)</option>
                            <option value="PRIIVALI">PRIIVALI (<?php echo $nbrPRIIVALI ; ?>)</option>
                            <option value="PRO">PRO (<?php echo $nbrAvailablePro ; ?>)</option>
                            <option value="PROFILO">PROFILO (<?php echo $nbrProfilo  ; ?>)</option>
                            <option value="PUMA">PUMA (<?php echo $nbrAvailablePUMA   ; ?>)</option>
                            <option value="RAY-BAN">RAY-BAN (<?php echo $nbrAvailableRayban ; ?>)</option>
                            <option value="REFLEXION">REFLEXION (<?php echo $nbrAvailableReflexion ; ?>)</option>
                            <?php }?>

                            <option value="RENDEZVOUS">RENDEZ-VOUS (<?php echo $NbrAvailableRDV ; ?>)</option>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="ROGER">ROGER (<?php echo $nbrRoger ; ?>)</option>
                            <option value="RUIMANNI">RUIMANNI (<?php echo $nbrAvailableRuimanni ; ?>)</option>
                            <option value="ArmouRx">SAFETY (<?php echo $NbrAvailableSafety ; ?>)</option>
                            <option value="SECG">SECG (<?php echo $nbrAvailablSECG ; ?>)</option>
                            <option value="SERMATT">SERMATT (<?php echo $nbrAvailableSermatt ; ?>)</option>
                            <option value="SEVENTEEN">SEVENTEEN (<?php echo  $nbrAvailableSeventeen ; ?>)</option>
                            <option value="SILHOUETTE">SILHOUETTE (<?php echo  $nbrAvailableSilhouette ; ?>)</option>
                            <option value="SILOAM">SILOAM (<?php echo  $nbrAvailablSiloam  ; ?>)</option>
                            
                            <option value="SORA">SORA (<?php echo   $nbrSORA  ; ?>)</option>
                            <option value="STAR">STAR (<?php echo   $nbrAvailablStar  ; ?>)</option>
                            <option value="STYRKA">STYRKA (<?php echo     $nbrSTYRKA  ; ?>)</option>
                         
                            <option value="SUNOPTIC AK">SUNOPTICS AK (<?php echo $nbrAvailableSunoptiAK; ?>)</option>
                            <option value="SUNOPTIC CP">SUNOPTICS CP (<?php echo $nbrAvailableSunoptiCP  ; ?>)</option>
                            <option value="SUNOPTIC K">SUNOPTICS K (<?php echo $nbrAvailableSunopticK; ?>)</option>
                            <option value="SUNOPTIC MASSIMO">SUNOPTICS MASSIMO (<?php echo $nbrAvailableSunoptiMassimo; ?>)</option>
                            <option value="SUPER CLIP">SUPER CLIP (<?php echo  $nbrAvailablSuperClip; ?>)</option>
                            <option value="SYOPTICAL">SYOPTICAL (<?php echo  $nbrSYOPTICAL; ?>)</option>
                            <?php  } ?>
                            <?php  if ($_SESSION["CompteEntrepot"]  == 'yes'){ ?>
                            <option value="THIERRY MUGLER">THIERRY MUGLER (<?php echo  $nbrAvailableMUGLER  ; ?>)</option>
                            <option value="TIFFANY & CO">TIFFANY & CO (<?php echo $nbrAvailablTiffany ; ?>)</option>
                            <option value="TOKADO">TOKADO (<?php echo $nbrTokado  ; ?>)</option>
                            <option value="TOM FORD">TOM FORD (<?php echo $nbrAvailableTomFord ; ?>)</option>
                            <option value="THILFIGER">THILFIGER (<?php echo  $nbrAvailableTHilfiger ; ?>)</option>
                            <option value="TMX">TMX (<?php echo  $nbrTMX  ; ?>)</option>
                            
                            <option value="TRUSSARDI">TRUSSARDI (<?php echo  $nbrTrussardi ; ?>)</option>
                            <option value="U ROCK">U ROCK (<?php echo  $nbrAvailableURock ; ?>)</option>
                            <option value="VALENTINO">VALENTINO (<?php echo    $nbrAvailablenbrVALENTINO ; ?>)</option>
                            <option value="VALERIE SPENCER">VALERIE SPENCER (<?php echo $nbrAvailableValerie ; ?>)</option>
                            <option value="VARIONET">VARIONET (<?php echo $nbrAvailableVarionet ; ?>)</option>
                            <option value="VENETO">VENETO (<?php echo  $nbrAvailablVeneto ; ?>)</option> 
                            <option value="VICOMTE A">VICOMTE A (<?php echo  $nbrVICOMTEA	 ; ?>)</option> 
                            <option value="VICROLA">VICROLA (<?php echo  $nbrAvailablVicrola	 ; ?>)</option> 
                            <option value="VINYL FACTORY">VINYL FACTORY (<?php echo  $nbrVinilFactory; ?>)</option> 
                            <option value="VISIBLE">VISIBLE (<?php echo  $nbrVISIBLE	; ?>)</option> 
                            <option value="WAHO">WAHO (<?php echo    $nbrWAHO  ; ?>)</option> 
                            <option value="WOOLRICH">WOOLRICH (<?php echo   $nbrAvailableWoolrich ; ?>)</option> 
                            <option value="X-LOOK">X-LOOK (<?php echo   $nbrXLOOK ; ?>)</option> 	
                            <option value="XONE">XONE (<?php echo   $nbrAvailableXONE ; ?>)</option> 
                            <option value="ZENZERO">ZENZERO (<?php echo  $nbrAvailablZenZero ; ?>)</option> 
                            <?php }?>  

                     </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>                  
                <input type="hidden" name="gender" value="all"  /> 
                <input type="hidden" name="type" value="all"  /> 
                <input type="hidden" name="material" value="all"  />  
                <input type="hidden" name="color" value="all"  />  
                <input type="hidden" name="boxing" value="all"  />  
            
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
            </div>
            
            
            

            
            </td>
            <td width="43%" align="center" valign="top" class="formCellNosides">
           
                <?php if (($mylang == 'lang_french') && ($_SESSION["CompteEntrepot"]  == 'no')){?>
                
                <div class="home_features_header">Les verres</div>
                
                <div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                Tous Progressifs</div>                
                
                 <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog ff" /> 
                Progressif FF</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 20" /> 
                Freelux Progressif 20mm</div>              
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 16" /> 
               Freelux  Progressif 16mm</div>                  
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 14" /> 
               Freelux  Progressif 14mm</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="bifocal" /> 
                Bi-focaux</div>
                           
                <div style="font-size:14px; margin-left:20px;padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv"  />
                Unifocaux</div>
                
                <?php }elseif (($mylang == 'lang_english') && ($_SESSION["CompteEntrepot"]  == 'no'))  {?>
                
                <div class="home_features_header">Lenses</div>
                
                <div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                All Progressives</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="prog ff" />
                Progressive FF</div>                
                
                <div style="font-size:14px; margin-left:20px; padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 20" /> 
               Freelux Progressive 20mm</div>                
                 
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 16" /> 
                Freelux Progressive 16mm</div>   
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 14" /> 
                Freelux Progressive 14mm</div>
                                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="bifocal" /> 
                Bifocal</div>            
                             
                <div style="font-size:14px; margin-left:20px;padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv"  />
                Sv</div>
                
                <?php
                }elseif ($_SESSION["CompteEntrepot"]  == 'yes'){
				//Version pour JNB
				?>	
				 <div class="home_features_header">Les verres</div>
                
                <div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="progressif-entrepot" />
                Progressifs</div>                
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="crystal-entrepot" /> 
                Optimisé</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="ifree-entrepot" /> 
                Individualisé iFree</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="iaction-entrepot" /> 
                Individualisé iAction</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="irelax-entrepot" /> 
                iRelax</div>
                
                 <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="ioffice-entrepot" /> 
                iOffice</div>
                
                 <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="iroom-entrepot" /> 
                iRoom</div>
                
                
                 <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="ireader-entrepot" /> 
                iReader</div>

                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="bifocal-entrepot" /> 
                Bi-focaux</div>
                       
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="iaction-sv-entrepot" /> 
                iAction SV</div>   
                           
                <div style="font-size:14px; margin-left:20px;padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv"  />
                SV</div>
                
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog-glass" /> 
                 Progressif Glass</div>
                 
                 <div style="font-size:14px; margin-left:20px; padding:3px"> 
                 <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv-glass" /> 
                 SV Glass</div>
                	
				<?php }//End if 
                ?>
            </td>
          <tr>
            
        </table>
	</div>
    
    

    <div align="center" style="margin:11px">
        <input name="Submit" disabled="disabled" type="submit" value="<?php echo $btn_submit_txt;?>"/>
    </div>
    
      <?php  }//End if AfficherPageCommande ?> 
    
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
    
</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->

</body>
</html>