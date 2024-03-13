<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";
session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
require('../Connections/sec_connect.inc.php');
require('includes/dl_order_functions.inc.php');
mysql_query("SET CHARACTER SET UTF8");

catchOrderData();

$_SESSION['PACKAGE']=$_POST['PACKAGE'];//CATCH PACKAGE TYPE


//Filtre teinte (Tint = No trivex)
$FiltreTint = " AND 6 = 6 ";
if ($_SESSION['PrescrData']['TINT_COLOR'] <> '')
{
	$FiltreTint = " AND index_v <> '1.53'";	
}


//Filtre teinte  spéciales Swiss (Si on a une teinte swiss, on affiche que des produits qui vont chez swiss)
$FiltreTintSwiss = " AND 8 = 8 ";
switch($_SESSION['PrescrData']['TINT_COLOR']){
	case 'SW010'    :$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW027/50' :$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW030/50' :$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW051' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW035' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'GOL' 		:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW015' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'RAV' 		:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW034' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW012' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW023' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW046' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW025' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW004' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW036' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW054' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW062' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW026' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW032' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'TEN' 		:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'AZU' 		:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW007' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	case 'SW001' 	:$FiltreTintSwiss = " AND collection IN ('Entrepot Swiss')"; break;
	default         :$FiltreTintSwiss = " AND 8 = 8 ";	
}


//Filtre mirroir
$FiltreMiroirSwiss = " AND 12 = 12 ";
/*switch($_SESSION['PrescrData']['MIRROR']){//Un mirroir a été demandé, donc seul swiss peut produire
	case 'Aston':   	  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;	break;
	case 'Balloon Blue':  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Canyon':   	  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;	break;
	case 'Dona':   		  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Ocean Flash':   $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Pasha Silver':  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Pink Panther':  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Sahara':   	  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Tank':   		  $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	case 'Pine Green':    $FiltreMiroirSwiss = " AND collection IN ('Entrepot Swiss')" ;    break;
	default: $FiltreMiroirSwiss = " AND 12 = 12 ";
}//End Switch Mirror*/




//Filtre Drill et Notch ou Nylon groove avec index 1.50
$FiltreDrillNotchNylonGroove 		       = " AND 7 = 7 ";

$AvertissementIndex15NylongrooveDrillNotch = "";
if ($_SESSION['PrescrData']['FRAME_TYPE'] == 'Metal Groove')
{
	$FiltreDrillNotchNylonGroove = " AND index_v <> '1.50'";	
	
	if ($mylang == 'lang_french') { 
		$AvertissementIndex15NylongrooveDrillNotch = "Aucun indice 1.50 n'est affiché du au type de montage";
   	}else{ 
    	$AvertissementIndex15NylongrooveDrillNotch = "Index 1.50 are not available because of the frame type you selected";
	}	
}





//Promo
//Filtre pour afficher les produits Entrepot pour la promo 2 pour 1
$FiltrePromoEntrepot = " AND collection IN ('Entrepot Promo','Entrepot Crystal','Entrepot HKO','Entrepot SKY','Entrepot STC') AND (product_name like '%promo duo%' OR product_name like '%Promo%' OR product_name like '%premium office%' OR product_name like '%Promo Prog%'  OR product_name like '%Promo enfant%')  ";
if ($_SESSION['PrescrData']['REFERENCE_PROMO'] == '')//S'il n'y a pas de référence promo = compte n'est pas de l'entrepot OU ils ne veulent pas voir les produits Promo
{
$FiltrePromoEntrepot = " AND collection not in ('Entrepot Promo') and product_name not like '%promo%' and product_name not like '%premium office%'";	
}
 
 
//FiltreStock pour empecher les stock d'apparaitre si un prisme est demandé
$FiltrePrisme = " AND 5=5 ";
if (($_SESSION['PrescrData']['RE_PR_AX']>0) || ($_SESSION['PrescrData']['RE_PR_AX2']>0) || ($_SESSION['PrescrData']['LE_PR_AX']>0) || ($_SESSION['PrescrData']['LE_PR_AX2']>0))
$FiltrePrisme = " AND product_name not like '%stock%'";

//FiltreStock pour empecher les stock d'apparaitre si un prisme est demandé
$FiltreTeinte = " AND 9=9 ";
//if (strtolower($_POST[TINT]) == 'solid')
//$FiltreTeinte = " AND product_name not like '%stock%'";
//if (strtolower($_POST[TINT]) == 'gradient')
//$FiltreTeinte = " AND product_name not like '%stock%'";

$Lens_Category=$_POST[lens_category];
switch ($Lens_Category) {
	case 'all'         			:$lenscategory = " "; 								 	break;
	case 'bifocal'     			:$lenscategory = " AND lens_category IN ('bifocal')"; 	break;  
	case 'glass'       			:$lenscategory = " AND lens_category IN ('glass')";   	break;      
	case 'sv' 	       			:$lenscategory = " AND lens_category IN ('sv','glass')"; break;  
	case 'sv-entrepot' 			:$lenscategory = " AND collection IN ('Entrepot SV')";  break;  
	case 'iaction-entrepot' 	:$lenscategory = " AND product_name like  ('iAction')"; break; 
	case 'irelax-entrepot'  	:$lenscategory = " AND product_name like  ('iRelax')";  break;
	case 'ioffice-entrepot' 	:$lenscategory = " AND product_name like  ('iOffice')"; break; 	
	case 'iaction-sv-entrepot'  :$lenscategory = " AND product_name like  ('iAction') AND product_name like  ('SV') "; break; 	
	case 'sv-glass'  			:$lenscategory = " AND lens_category like  ('glass')  "; break; 	
	case 'ireader-entrepot'  	:$lenscategory = " AND product_name like '%ireader%' ";  break; 	
}

if ($_SESSION['PACKAGE']=='NURBS')
$FiltreNurbs = " AND collection = 'NURBS sunglasses'";
else
$FiltreNurbs = " AND 2=2 ";

$COATING=$_POST[COATING];
switch ($COATING) {
	case 'ANY':   				$COATING = " "; break;
	case 'HC':    				$COATING = " AND coating IN('Hard Coat','HC')";break;
	case 'AR':    				$COATING = " AND coating IN ('Aqua Dream AR','MultiClear AR','Smart AR','AR')";break;
	case 'AR Backside':    		$COATING = " AND coating IN ('AR Backside')";break;
	case 'AR+ETC':				$COATING = " AND coating IN('HMC EMI', 'AR+ETC','Dream AR','ITO AR')";break; 
	case 'XLR'   :				$COATING = " AND coating IN('Xlr')";break; 
	case 'HD AR'   :			$COATING = " AND coating IN('HD AR')";break; 
	case 'iBlu'   :				$COATING = " AND coating IN('iBlu')";break;       
	case 'StressFree':			$COATING = " AND coating IN('StressFree')";break;   
	case 'StressFree 32':		$COATING = " AND coating IN('StressFree 32')";break;    
	case 'StressFree Noflex':	$COATING = " AND coating IN('StressFree Noflex')";break; 
	case 'SPF':			  	    $COATING = " AND coating IN ('SPF')";break;                                                     
}

$INDEX=$_POST[INDEX];
switch ($INDEX) {
	case 'ANY' :$INDEX = " 1=1  "; break;
	case '1.57':$INDEX = " index_v IN (1.57)";break;
	case '1.58':$INDEX = " index_v IN (1.58)";break;
	case '1.60':$INDEX = " index_v IN (1.60)";break;
	case '1.70':$INDEX = " index_v IN (1.70)";break;
	case '1.50':$INDEX = " index_v IN (1.50)";break;
	case '1.67':$INDEX = " index_v IN (1.67)";break;
	case '1.53':$INDEX = " index_v IN (1.53)";break;
	case '1.56':$INDEX = " index_v IN (1.56)";break;
	case '1.74':$INDEX = " index_v IN (1.74)";break;
	case '1.59':$INDEX = " index_v IN (1.59)";break;
	case '1.54':$INDEX = " index_v IN (1.54)";break;
	case '1.80':$INDEX = " index_v IN (1.80)";break;
	case '1.90':$INDEX = " index_v IN (1.90)";break;
	case '1.52':$INDEX = " index_v IN (1.52)";break;
}

$LE_HEIGHT=$_POST[LE_HEIGHT];
$RE_HEIGHT=$_POST[RE_HEIGHT];

if ($LE_HEIGHT =="") $LE_HEIGHT = 0;
if ($RE_HEIGHT =="") $RE_HEIGHT = 0;

$PHOTO=$_POST[PHOTO];
$POLAR=$_POST[POLAR];


$ORDER_PATIENT_LAST=$_POST[LAST_NAME];

$RE_SPHERE=$_SESSION['PrescrData']['RE_SPH_NUM'] .  $_SESSION['PrescrData']['RE_SPH_DEC'];
$LE_SPHERE=$_SESSION['PrescrData']['LE_SPH_NUM'] .  $_SESSION['PrescrData']['LE_SPH_DEC'];

if (($LE_SPHERE > 0) || ($RE_SPHERE > 0))
{
		//Une des sphère est negative, on calcule  TOUJOURS le diamètre nécessaire pour un verre STOCK
		
		//Logique de Calcul
		//1: Frame_A + Frame_DBL  
		//2: Resultat divisé par 2
		//3: Résultat: On soustrait le plus petit des 2 PD
		//4: Résultat: On le multiplie par 2
		//5: Résultat: On additionne le Frame_ED
		
		$Frame_A     = $_SESSION['PrescrData']['FRAME_A'];
		$Frame_DBL   = $_SESSION['PrescrData']['FRAME_DBL'];
		$Frame_ED    = $_SESSION['PrescrData']['FRAME_ED'];
		$LE_PD       = $_SESSION['PrescrData']['LE_PD']; 
		$RE_PD       = $_SESSION['PrescrData']['RE_PD']; 
		$PlusPetitPD = $LE_PD ;
		if ($RE_PD < $LE_PD)
		$PlusPetitPD = $RE_PD ;
		
		/*echo '<br>Frame A:'     . $Frame_A ;
		echo '<br>Frame DBL:' 	  . $Frame_DBL ;
		echo '<br>Frame ED:'  	  . $Frame_ED ;
		echo '<br>LE PD:'	  	  . $LE_PD ;
		echo '<br>RE PD:'	  	  . $RE_PD ;
		echo '<br>Plus petit PD:' . $PlusPetitPD ;*/
		
		$DiametreRequis = $Frame_A + $Frame_DBL;//35 + 37 = 72
		//echo '<br>Etape 1 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis / 2;
		//echo '<br>Etape 2 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis - $PlusPetitPD;
		//echo '<br>Etape 3 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis * 2;
		//echo '<br>Etape 4 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis + $Frame_ED + 3;//+3 ajouté le 26 juin 2015 Demandé par Lynn	
		//echo '<br>Diamètre Requis:'. $DiametreRequis;	
		if ($DiametreRequis > 65)
		$FiltreDiameter = " AND diameter >=  $DiametreRequis";
}


$RE_CYL=$_SESSION['PrescrData']['RE_CYL_NUM'] . $_SESSION['PrescrData']['RE_CYL_DEC'];
$LE_CYL=$_SESSION['PrescrData']['LE_CYL_NUM'] . $_SESSION['PrescrData']['LE_CYL_DEC'] ;

$RE_AXIS=$_SESSION['PrescrData']['RE_AXIS'];
$LE_AXIS=$_SESSION['PrescrData']['LE_AXIS'];

if ($_SESSION['PrescrData']['EYE']=="R.E."){
	$LE_SPHERE=$_SESSION['PrescrData']['RE_SPHERE'];
	$LE_CYL=$_SESSION['PrescrData']['RE_CYL'];

}

if ($_SESSION['PrescrData']['EYE']=="L.E."){
	$RE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];
	$RE_CYL=$_SESSION['PrescrData']['LE_CYL'];
}

$_SESSION['PACKAGE']=$_POST['PACKAGE'];//CATCH PACKAGE TYPE
$filtrePrix = ' price_can <> 0';
   	 
	 
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN ifc_ca_exclusive on (liste_collection_info.collection_name = ifc_ca_exclusive.collection) 
WHERE prod_status='active' 
AND min_height <= $LE_HEIGHT
AND max_height >= $RE_HEIGHT
$FiltrePromoEntrepot
$COATING
$FiltreNurbs
$FiltreMiroirSwiss
AND  $INDEX 
AND $filtrePrix 
$FiltreDiameter
$FiltrePrisme
$FiltreTint
$FiltreTintSwiss
$FiltreDrillNotchNylonGroove
$FiltreTeinte 
$lenscategory";


$ForcerCollectionSwiss = ' AND 1=1 ';
if (($_SESSION['PACKAGE']=='FUGLIES_C') &&  ($_SESSION["CompteEntrepot"] == 'no'))
$ForcerCollectionSwiss = " AND collection='IFC Swiss'";
if (($_SESSION['PACKAGE']=='FUGLIES_B') &&  ($_SESSION["CompteEntrepot"] == 'no'))
$ForcerCollectionSwiss = " AND collection='IFC Swiss'";
if (($_SESSION['PACKAGE']=='FUGLIES_A') &&  ($_SESSION["CompteEntrepot"] == 'no'))
$ForcerCollectionSwiss = " AND collection='IFC Swiss'";
if (($_SESSION['PACKAGE']=='FUGLIES_C') &&  ($_SESSION["CompteEntrepot"] == 'yes'))
$ForcerCollectionSwiss = " AND collection='Entrepot Swiss'";
if (($_SESSION['PACKAGE']=='FUGLIES_B') &&  ($_SESSION["CompteEntrepot"] == 'yes'))
$ForcerCollectionSwiss = " AND collection='Entrepot Swiss'";
if (($_SESSION['PACKAGE']=='FUGLIES_A') &&  ($_SESSION["CompteEntrepot"] == 'yes'))
$ForcerCollectionSwiss = " AND collection='Entrepot Swiss'";

 
$query.= "
AND (sphere_over_max>=$RE_SPHERE) 
AND (sphere_over_min<=$RE_SPHERE) 
AND (sphere_over_max>=$LE_SPHERE) 
AND (sphere_over_min<=$LE_SPHERE) 
AND (cyl_max>=$RE_CYL) 
AND (cyl_over_min<=$RE_CYL) 
AND (cyl_max>=$LE_CYL) 
$ForcerCollectionSwiss
AND  photo ='$PHOTO'
AND polar = '$POLAR'
AND (cyl_over_min<=$LE_CYL) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
 order by index_v, price_can "; //EXCLUSIVE

if ($_SESSION["sessionUser_Id"] == 'warehousehal'){
	//echo 'Pour CHarles:  '. $query;
	}
$result=mysql_query($query)	or die  ("Please resubmit your form.<br><a href='javascript:history.back()'>Go Back 1 step.</a>". mysql_error()." ".$query);
$usercount=mysql_num_rows($result);
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
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>

<div id="headerBox" class="header">
	<?php 	if ($mylang == 'lang_french') {  ?>
    Unifocaux
    <?php  	}else{ ?>
    Single Vision
    <?php 	} ?>    
</div>

</td><td><div id="headerGraphic">


<?php if ($mylang == 'lang_french'){ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
</div></td></tr></table>
           <?php
		   if ($_SESSION['PrescrData']['EYE']=="L.E."){
		   //echo '<br><br>left eye only<br><br>';
		   $queryDoublons = "SELECT order_num from orders where
			le_axis   = '$LE_AXIS'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE' 
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";		   
		   }
		   		   
		   if ($_SESSION['PrescrData']['EYE']=="R.E."){
		    // echo '<br><br>right eye only<br><br>';
		    $queryDoublons = "SELECT order_num from orders where
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";
		   }
		   
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="Both"){
		     //echo '<br><br>both eyes<br><br>';
           $queryDoublons = "SELECT order_num from orders where
			 le_axis   = '$LE_AXIS'
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";
		   
		   }
		   
		   		
				
				
				if ($usercount != 0){

				echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"svDetail.php\">";
				}
				else{
				echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescription_retry.php\">";
				}
				
					 
		   $resultDoublons = mysql_query($queryDoublons)	or die  ("Erreur 1:". mysql_error() . $queryDoublons);
		   $countDoublons  = mysql_num_rows($resultDoublons);		   
		   $queryAfficherWarning = "SELECT display_double_warning from accounts where user_id = '". 
		   $_SESSION["sessionUser_Id"] . "'";
		   $resultAfficherWarning = mysql_query($queryAfficherWarning)	or die  ("Erreur 2:". mysql_error() . $queryAfficherWarning);
		   	$DataAfficherWarning=mysql_fetch_array($resultAfficherWarning);
			$AfficherWarning = $DataAfficherWarning['display_double_warning'];
		 
		   if (($FiltreTintSwiss  <>  " AND 8 = 8 ") && ($mylang == 'lang_french')){
			   	echo '<br><font color="#FF0000" >Attention: Puisqu\'une teinte exclusive à Swiss est ajoutée, uniquement les produits Swiss seront affichés</font><br> ';
		   }
		   
		   if (($FiltreTintSwiss  <>  " AND 8 = 8 ") && ($mylang == 'lang_english')){
			   	echo '<br><font color="#FF0000" >Warning: Since a Swiss exclusive tint has been added, only swiss products will be displayed</font><br> ';
		   }
		   
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_french')){
		   	echo '<br><font color="#FF0000" >Attention: Une commande avec le même numéro de référence et RX est déjà dans le système </font><br> ';
		   }
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_english')){
		   	 echo '<br><font color="#FF0000" >Warning: An order with the same reference number and Rx is already in the system</font><br> ';
		   }
		   
		   if ($DiametreRequis > 65)
		   echo '<br><font color="#FF0000" >The required diametre is higher than 65. (This order requires '.$DiametreRequis.' mm) Stock products are not available for this order.</font><br> ';
		   
		   
		   if ($AvertissementIndex15NylongrooveDrillNotch <> "")
		  	 echo '<br><font color="#FF0000" >' . $AvertissementIndex15NylongrooveDrillNotch . '</font>';
		   ?>
           
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

		    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		    <div class="Subheader"><?php echo $lbl_srchres_txt;?></div>
		   
      <div class="plainText"><?php echo $lbl_numofitemsfnd_txt;?> <?php echo $usercount?> </div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="9" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
                <td align="center"  class="formCell"><?php echo $adm_prodname_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_material_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $adm_coating_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_photochro_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_polarized_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_overrang_txt;?></td>
                <td align="center" class="formCell"><?php echo $adm_price_txt;?><br /><?php 
				
				if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
					echo " E-Lab US";}
				else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
					echo " E-Lab CA";}
				else {
					
				if ($_SESSION["sessionUserData"]["currency"]=="US"){
					echo " US";}
				else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
					echo " CA";}
				else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
					echo " EUR";}
				}
				?></td>
                <td align="center" class="formCell"><?php echo $adm_select_txt;?></td>
              </tr> 
			  <?php

if ($usercount == 0){ /* no positions to list */
	if ($mylang == 'lang_french') {  ?>
	<tr><td colspan=\"9\" class=\"formCell\">Désolé, aucun article trouvé.</td></tr>
	<?php  	
	}else{ ?>
	<tr><td colspan=\"9\" class=\"formCell\">Sorry, no items found.</td></tr>
	<?php 	
	}     	
}else{
	echo "<tr>";
	while ($listItem=mysql_fetch_array($result)){
	$item++;
	echo "<td align=\"left\" class=\"formCell\">";
			
			//echo "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=450')\">";
			
			echo "<a target=\"_blank\"  href=\"lens_specs.php?pkey=$listItem[primary_key]\">";
			if ($_SESSION["sessionUser_Id"] == 'warehousestc')
			echo $listItem[product_name_en];
			else
			echo $listItem[product_name];
			echo "</a></td>";
			
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
				
					if ($mylang=='lang_french'){
					echo "O.D.";
					}else{
					echo "R.E. $";
					}
				
					$over_range=money_format('%.2n',$over_range_re);
					if ($mylang=='lang_french'){
					echo $over_range. '$';
					}else{
					echo $over_range;
					}
					echo "<br>";
				}
			}
			
			if ($_SESSION['PrescrData']['EYE']!="R.E."){
				if (($LE_SPHERE>$listItem[sphere_max])||($LE_SPHERE<$listItem[sphere_min])||($LE_CYL<$listItem[cyl_min])){
					$over_range_le=10.00;
					if ($mylang=='lang_french'){
					echo "O.G.";
					}else{
					echo "L.E. $";
					}
				
					$over_range=money_format('%.2n',$over_range_le);
					if ($mylang=='lang_french'){
					echo $over_range. '$';
					}else{
					echo $over_range;
					}
					echo "<br>";
				}
			}
			echo "</td><td  align=\"center\" class=\"formCell\">";

			$price = $listItem['price_can']; 
		    $price = money_format('%.2n',$price);
			
			if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
				$price=money_format('%.2n',$price/2);
			}
		
			//WARRANTY
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 6;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 3;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 3;
			}
					
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 10;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 5;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 5;
			}
			
		
			$price= money_format('%.2n',$price); 
				

			if ($mylang == 'lang_french') {
			echo $price."$";
			}else{
			echo "$" . $price;
			}
		
			echo "</td><td  align=\"center\" class=\"formCell\">";
			echo "<input type=\"radio\" name=\"product_id\" id=\"product_id\"  onClick=\"CheckSelection();\"  value=\"$listItem[primary_key]\"";
			
			if ($item==1){
				echo  "/>";
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
 echo "<input   name=\"Submit\" type=\"submit\" disabled=\"disabled\" value=\"".$adm_proceed_txt."\";/></div></form>";
			}
			else{
			 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"   onClick=\"history.back(); return false\" target=\"main\"/></div></form>";
			}
			
			?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
 
</div>
</div><!--END containter-->


</body>
</html>