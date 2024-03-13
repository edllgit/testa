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
include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

$_SESSION['prFormVars']=$_POST;// CATCH FOR RETRY
mysqli_query($con,"SET CHARACTER SET UTF8");
catchOrderData();

$_SESSION['PACKAGE']=  $_POST['PACKAGE'];//CATCH PACKAGE TYPE

$Lens_Category=$_POST[lens_category];
switch ($Lens_Category) {
	case 'all'      :$lenscategory = " AND lens_category NOT IN ('bifocal','sv')"; 	break;
	case 'bifocal'  :$lenscategory = " AND lens_category IN ('bifocal')"; 			break;  
	case 'prog'     :$lenscategory = " AND lens_category IN ('prog')"; 	 	 		break;     
	case 'sv' 	    :$lenscategory = " AND lens_category IN ('sv')";      			break;        
}

$RE_SPHERE=$_SESSION['PrescrData']['RE_SPHERE'];
$LE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];


$ModelChoisit = $_SESSION['PrescrData']['TEMPLE_MODEL'];
$Filtre6000   = " AND 7 = 7" ;
//Si le client choisit un de ces modeles il est limitié en sphere entre +2 à -2
switch($ModelChoisit){
	case '6001_BLK' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_BLUE': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_BRNZ': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_CRY' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_RED' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_GREY': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_SIL' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_WHT' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6002_BLUE': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6002_PINK': $Filtre6000 = " AND collection = '6000'";   break;	
}


//echo 'Model choisit:'. $_SESSION['PrescrData']['TEMPLE_MODEL'];



if (($LE_SPHERE > -2.01) || ($LE_SPHERE < 2.01) || ($RE_SPHERE > -2.01) || ($RE_SPHERE < 2.01)){
	$Filtre6000   = " AND 8 = 8" ;
}

$_SESSION['PrescrData']['RE_CT']= '';
$_SESSION['PrescrData']['RE_ET']= '';
$_SESSION['PrescrData']['LE_CT']= '';
$_SESSION['PrescrData']['LE_ET']= '';

//Set the correct Thickness depending of the requested spheres
switch($RE_SPHERE) {
	case '+0.00':
	$_SESSION['PrescrData']['RE_CT'] = '3.0';
	$_SESSION['PrescrData']['RE_ET'] = '';
	break;

	case '-0.00':
	$_SESSION['PrescrData']['RE_CT'] = '3.0';
	$_SESSION['PrescrData']['RE_ET'] = '';
	break;
	
	case $RE_SPHERE > 0:
	$_SESSION['PrescrData']['RE_ET'] = '3.0';
	$_SESSION['PrescrData']['RE_CT'] = '';
	break;
	
	case $RE_SPHERE < 0:
	$_SESSION['PrescrData']['RE_CT'] = '3.0';
	$_SESSION['PrescrData']['RE_ET'] = '';
	break;
}

switch($LE_SPHERE) {
	case '+0.00':
	$_SESSION['PrescrData']['LE_CT'] = '3.0';
	$_SESSION['PrescrData']['LE_ET'] = '';
	break;
	
	case '-0.00':
	$_SESSION['PrescrData']['LE_CT'] = '3.0';
	$_SESSION['PrescrData']['LE_ET'] = '';
	break;
		
	case $LE_SPHERE > 0:
	$_SESSION['PrescrData']['LE_ET'] = '3.0';
	$_SESSION['PrescrData']['LE_CT'] = '';
	break;

	case $LE_SPHERE < 0:
	$_SESSION['PrescrData']['LE_CT'] = '3.0';
	$_SESSION['PrescrData']['LE_ET'] = '';
	break;
}


$SelectedTint=$_POST[TINT];
switch ($SelectedTint) {
	case 'None'	   :$Tint_Filter = "('0','60','80')";  break;
	case 'Solid 60':$Tint_Filter = "('60','85','70')"; break;
	case 'Solid 80':$Tint_Filter = "('80')";		   break;    
	case 'Solid 70':$Tint_Filter = "('70','80','85')"; break;
	case 'Solid 85':$Tint_Filter = "('85','80','70')"; break;  
	case 'SNBR70'  :$Tint_Filter = "('85','80','70')"; break; 
	case 'SNBR85'  :$Tint_Filter = "('85','80')";      break; 
	case 'RBGY80'  :$Tint_Filter = "('85','80')";	   break; 
	case 'DMGY70'  :$Tint_Filter = "('70','80','85')"; break; 
	case 'DMGY85'  :$Tint_Filter = "('80','85')";	   break; 
	case 'DMGY50'  :$Tint_Filter = "('70','80','85')"; break; 
}

$COATING=$_POST[COATING];
switch ($COATING) {
	case 'ANY':$COATING = " "; break;
	case 'HC':$COATING = " AND coating IN('Hard Coat','HC')";break;
	case 'AR':$COATING = " AND coating NOT IN ('Hard Coat','HC')";break;                                  
}



$INDEX=$_POST[INDEX];
switch ($INDEX) {
	case 'ANY':$INDEX  = " "; break;
	case '1.57':$INDEX = " AND index_v IN(1.57) ";break;
	case '1.60':$INDEX = " AND index_v IN(1.60) ";break;
	case '1.70':$INDEX = " AND index_v IN(1.70) ";break;
	case '1.50':$INDEX = " AND index_v IN(1.50) ";break;
	case '1.67':$INDEX = " AND index_v IN(1.67) ";break;
	case '1.53':$INDEX = " AND index_v IN(1.53) ";break;
	case '1.56':$INDEX = " AND index_v IN(1.56) ";break;
	case '1.58':$INDEX = " AND index_v IN(1.58) ";break;
	case '1.74':$INDEX = " AND index_v IN(1.74) ";break;
	case '1.59':$INDEX = " AND index_v IN(1.59) ";break;
	case '1.54':$INDEX = " AND index_v IN(1.54) ";break;
	case '1.80':$INDEX = " AND index_v IN(1.80) ";break;
	case '1.90':$INDEX = " AND index_v IN(1.90) ";break;
	case '1.52':$INDEX = " AND index_v IN(1.52) ";break;
}

$LE_HEIGHT=$_POST[LE_HEIGHT];
$RE_HEIGHT=$_POST[RE_HEIGHT];

if ($LE_HEIGHT =="")
$LE_HEIGHT = 0;

if ($RE_HEIGHT =="")
$RE_HEIGHT = 0;


$PHOTO=$_POST[PHOTO];
$POLAR=$_POST[POLAR];

$ORDER_PATIENT_LAST=$_POST[LAST_NAME];

$RE_CYL=$_SESSION['PrescrData']['RE_CYL'];
$LE_CYL=$_SESSION['PrescrData']['LE_CYL'];

$RE_ADD=$_SESSION['PrescrData']['RE_ADD'];
$LE_ADD=$_SESSION['PrescrData']['LE_ADD'];

$RE_AXIS=$_SESSION['PrescrData']['RE_AXIS'];
$LE_AXIS=$_SESSION['PrescrData']['LE_AXIS'];

if ($_SESSION['PrescrData']['EYE']=="R.E."){
	$LE_SPHERE=$_SESSION['PrescrData']['RE_SPHERE'];
	$LE_CYL=$_SESSION['PrescrData']['RE_CYL'];
	$LE_ADD=$_SESSION['PrescrData']['RE_ADD'];
}

if ($_SESSION['PrescrData']['EYE']=="L.E."){
	$RE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];
	$RE_CYL=$_SESSION['PrescrData']['LE_CYL'];
	$RE_ADD=$_SESSION['PrescrData']['LE_ADD'];
}


if ($_SESSION["sessionUserData"]["currency"]=="CA"){
$query="SELECT * FROM acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN safety_exclusive on (liste_collection_info.collection_name = safety_exclusive.collection) 
WHERE prod_status='active' 
$COATING 
$INDEX 
$lenscategory
$Filtre6000
AND photo='$PHOTO' AND polar='$POLAR' 
AND min_height <= $LE_HEIGHT
AND max_height >= $RE_HEIGHT
AND (sphere_over_max>=$RE_SPHERE) 
AND (sphere_over_min<=$RE_SPHERE) 
AND (sphere_over_max>=$LE_SPHERE) 
AND (sphere_over_min<=$LE_SPHERE) 
AND (cyl_max>=$RE_CYL) 
AND (cyl_over_min<=$RE_CYL) 
AND (cyl_max>=$LE_CYL) 
AND (cyl_over_min<=$LE_CYL) 
AND (add_max>=$RE_ADD) 
AND (add_min<=$RE_ADD) 
AND (add_max>=$LE_ADD) 
AND (add_min<=$LE_ADD)".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by index_v  "; //EXCLUSIVE
}

//if ($_SESSION["sessionUser_Id"] == 'entrepotsafe')
//echo '<br><br>Query Charles: '. $query . '<br><br>';

//if ($_SESSION["sessionUser_Id"] == 'BSG')
//echo '<br><br>Query Charles: '. $query . '<br><br>';

$result=mysqli_query($con,$query)	or die  ("Please resubmit your form.<br><a href='javascript:history.back()'>Go Back 1 step.</a>". mysqli_error($con));
$usercount=mysqli_num_rows($result);


if ($usercount != 0){

echo"<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescriptionDetail.php\" onSubmit=\"return validate(this.name,'product_id')\">";
}
else{
echo"<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescription_retry.php\">";
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

<script type="text/javascript">
function CheckSelection() {
document.forms[0].Submit.disabled=false;
}
//-->
</script>
</head>

<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_availprod_txt;?></div></td><td><div id="headerGraphic">


  <?php if ($mylang == 'lang_french'){ ?>
  <img src="http://www.direct-lens.com/safety/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="http://www.direct-lens.com/direct-lens/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
</div></td></tr></table>
           
         

           <?php
		   if ($_SESSION['PrescrData']['EYE']=="L.E."){
		   $queryDoublons = "SELECT order_num from orders where
		    le_add    = '$LE_ADD'
			and le_axis   = '$LE_AXIS'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE' 
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";		   
		   }
		   
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="R.E."){
		    $queryDoublons = "SELECT order_num from orders where
		    re_add    = '$RE_ADD' 
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";
		   }
		   
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="Both"){
           $queryDoublons = "SELECT order_num from orders where
		    	re_add    = '$RE_ADD' 
			and le_add    = '$LE_ADD'
			and le_axis   = '$LE_AXIS'
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";
		   
		   }
		   
		   			 
		   $resultDoublons = mysqli_query($con,$queryDoublons)	or die  ("Erreur:". mysqli_error($con) . $queryDoublons);
		   $countDoublons  = mysqli_num_rows($resultDoublons);
		   
		   $queryAfficherWarning = "SELECT display_double_warning from accounts where user_id = '". 
		   $_SESSION["sessionUser_Id"] . "'";
		   $resultAfficherWarning = mysqli_query($con,$queryAfficherWarning)	or die  ("Erreur:". mysqli_error($con) . $queryAfficherWarning);
		   $DataAfficherWarning=mysqli_fetch_array($resultAfficherWarning,MYSQLI_ASSOC);
		   $AfficherWarning = $DataAfficherWarning['display_double_warning'];		   
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_french')){
		   echo '<br><font color="#FF0000" >Attention: Une commande avec le même numéro de référence et RX est déjà dans le système </font>.<br> ';
		   }
		   
		    if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_english')){
		   echo '<br><font color="#FF0000" >Warning: An order with the same reference number and Rx is already in the system</font>.<br> ';
		   }
		   
		     if ($AvertissementIndex15NylongrooveDrillNotch <> "")
		  	 echo '<br><font color="#FF0000" >' . $AvertissementIndex15NylongrooveDrillNotch . '</font>';
		   ?>

		    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		    <div class="Subheader"><?php echo $lbl_srchres_txt;?></div>
		   
      <div class="plainText"><?php echo $lbl_numofitemsfnd_txt;?> <?php echo $usercount?> </div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="9" bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
                <td align="center"  class="formCell"><?php echo $adm_prodname_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_material_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $adm_coating_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_photochro_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_polarized_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_overrang_txt;?></td>
                <td align="center" class="formCell"><?php echo $adm_price_txt;?><br /><?php 
									
				if ($_SESSION["sessionUserData"]["currency"]=="US"){
					echo " US";}
				else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
					echo " CA";}
				else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
					echo " EUR";}
				
				?></td>
                <td align="center" class="formCell"><?php echo $adm_select_txt;?></td>
              </tr>
			  <?php


if ($mylang == 'lang_french') {
$Message= "Aucun item trouvé.";
}else{
$Message= "No item found.";
}

if ($usercount == 0){ /* no positions to list */
	echo "<tr><td colspan=\"9\" class=\"formCell\">$Message</td></tr>";
	}else{
		echo "<tr>";
		while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$item++;
			echo "<td align=\"left\" class=\"formCell\"><strong>";

			echo $listItem[product_name];
			echo "</strong></td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[index_v];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[coating];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			if (($listItem[photo]=='None') && ($mylang == 'lang_french')) {
			echo 'Non';}else{
			echo $listItem[photo];
			}
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			if (($listItem[polar] == 'None') && ($mylang == 'lang_french')){ 
			echo 'Non';}else{
			echo $listItem[polar];
			}
			echo "</td>";
			
			echo "<td  align=\"right\" class=\"formCell\">";
			$over_range_re=0;
			$over_range_le=0;
			
			if ($_SESSION['PrescrData']['EYE']!="L.E."){
			
				if (($RE_SPHERE>$listItem[sphere_max])||($RE_SPHERE<$listItem[sphere_min])||($RE_CYL<$listItem[cyl_min])){
				$over_range_re=10.00;
					echo "R.E. $";
				
					$over_range=money_format('%.2n',$over_range_re);
					echo $over_range;
					echo "<br>";
				}
			}
			
			if ($_SESSION['PrescrData']['EYE']!="R.E."){
				if (($LE_SPHERE>$listItem[sphere_max])||($LE_SPHERE<$listItem[sphere_min])||($LE_CYL<$listItem[cyl_min])){
					$over_range_le=10.00;
					echo "L.E. $";
				
					$over_range=money_format('%.2n',$over_range_le);
					echo $over_range;
					echo "<br>";
				}
			}
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			
			
			//Pour obtenir le prix, on doit additionner le prix de la monture et des verres			
			if ($_SESSION[safety_plan]     == 'regular price'){
			$price = $listItem["price"] ;   //+ $_SESSION['PrescrData']['FRAME_SELLING_PRICE'] ;
			}elseif($_SESSION[safety_plan] == 'interco price'){
			$price = $listItem["price_interco"]; //+ + $_SESSION['PrescrData']['FRAME_INTERCO'] ;
			}elseif($_SESSION[safety_plan] == 'discounted price'){
			$price = $listItem["price_discounted"]; //+ + $_SESSION['PrescrData']['FRAME_DISCOUNTED_PRICE'] ;
			}
			
			if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
				$price=money_format('%.2n',$price/2);
			}
		
						
			$price= money_format('%.2n',$price); 
				
			if ($mylang == 'lang_french') {
			echo $price."$";
			}else{
			echo "$" . $price;
			}
			
			echo "</td>";
			
		
			
			echo "<td  align=\"center\" class=\"formCell\">";
			
			echo "<input type=\"radio\" name=\"product_id\"  onClick=\"CheckSelection();\" id=\"product_id\" value=\"$listItem[primary_key]\"";
			
			if ($item==1){
				echo   "/>";
				}
			else{
				echo"/>";}
			echo "</td></tr>";
			   }//end of while
}//end of 0 usercount
?>

          </table> <?php
if ($usercount != 0){ 

 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"   onClick=\"history.back(); return false\" target=\"main\"/>&nbsp;";
 echo "<input name=\"Submit\" type=\"submit\" disabled=\"disabled\" value=\"".$adm_proceed_txt."\";/></div></form>";
			}
			else{
			 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"   onClick=\"history.back(); return false\" target=\"main\"/></div></form>";
			}
			
?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<?php include("footer.inc.php"); ?>
</div><!--END containter-->
<?php

	$_SESSION['PrescrData']['myupload'] = $_POST["uploadhold"];
//////////if there is a new lens stored
	if(($_FILES['myupload']['name'])){
	$target_path = "C:\\cdrive\\websites\\directlens\\holdingfiles\\";
	$target_path = $target_path . basename( $_FILES['myupload']['name']);
	$_SESSION['PrescrData']['myupload'] = basename( $_FILES['myupload']['name']);
	move_uploaded_file($_FILES['myupload']['tmp_name'], $target_path);
	}

//////////if there is already a lens stored...but no new lens
	if((!$_FILES['myupload']['name']) && (($_SESSION['PrescrData']['myupload']) && ($_SESSION['PrescrData']['myupload'] != "none"))){
	///no need to do anything- already done
	}

//////////if there is nothing
	if((!$_FILES['myupload']['name']) && (!$_SESSION['PrescrData']['myupload']) || ($_SESSION['PrescrData']['myupload'] == "none")){
		///no need to do anything
	}
	
?>

</body>
</html>