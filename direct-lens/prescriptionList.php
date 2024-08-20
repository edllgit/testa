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
include_once('includes/dl_order_functions.inc.php');
global $drawme;



if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");


catchOrderData();
//Cacher les produits Svision Stock si le client demande un prisme
$EmpecherProduitSvisionStock = ' AND 1 = 1 ';
if ($_POST[RE_PR_AX]<> '')
$EmpecherProduitSvisionStock = " AND product_name not like '%svision stock%' ";
if ($_POST[RE_PR_AX2]<> '')
$EmpecherProduitSvisionStock = " AND product_name not like '%svision stock%' ";
if ($_POST[LE_PR_AX]<> '')
$EmpecherProduitSvisionStock = " AND product_name not like '%svision stock%' ";
if ($_POST[LE_PR_AX2]<> '')
$EmpecherProduitSvisionStock = " AND product_name not like '%svision stock%' ";

$Lens_Category=$_POST[lens_category];
switch ($Lens_Category) {
//Lens category
case 'all' 	    :$lenscategory = " 1=1"; break;
case 'bifocal'  :$lenscategory = " lens_category IN('bifocal')"; break;  
case 'all prog' :$lenscategory = " lens_category IN('prog ds', 'prog ff', 'prog cl')"; break;
case 'prog cl'  :$lenscategory = " lens_category IN('prog cl')"; break;   
case 'prog ds'  :$lenscategory = " lens_category IN('prog ds')"; break;    
case 'prog ff'  :$lenscategory = " lens_category IN('prog ff')"; break;    
case 'glass'    :$lenscategory = " lens_category IN('glass')"; break;      
case 'sv' 	    :$lenscategory = " lens_category IN('sv')"; break; 
case 'stock' 	:$lenscategory = " lens_category IN('stock')"; break; 
//Lens Type
case 'PSI HD':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Precision+ S':				$lenscategory = " product_name like '%Precision+ S%' ";   break;
case 'Precision+360': 				$lenscategory = " product_name like '%Precision+ 360%' ";  break;
case 'Maxiwide':					$lenscategory = " product_name like '%Maxiwide%' "; 	  break;
case 'iRoom':				        $lenscategory = " product_name like '%iRoom%' "; 		  break;
case 'Alpha':						$lenscategory = " product_name like '%Alpha%' "; 		  break;
case 'Alpha HD':					$lenscategory = " product_name like '%Alpha HD%' "; 	  break;
case 'FT28':						$lenscategory = " product_name like '%FT28%' "; 		  break;
case 'Single Vision':				$lenscategory = " product_name like '%Single Vision%' ";  break;
case 'Single Vision Stock':			$lenscategory = " product_name like '%Single Vision Stock%' "; 		  break;
case 'IPL':						    $lenscategory = " product_name like '%IPL%' "; 		      break;
case 'Acuform':					    $lenscategory = " product_name like '%Acuform%' "; 	      break;
case 'FIT':					        $lenscategory = " product_name like '%Optimize FIT%' ";   break;
case 'Horizon':					    $lenscategory = " product_name like '%Horizon%' ";        break;
case 'DMT':					        $lenscategory = " product_name like '%DMT%' "; 	    	  break;
case 'Lifestyle':					$lenscategory = " product_name like '%office premium%' "; break;
case 'Anti-Fatigue':				$lenscategory = " product_name like '%FATIGUE%'";         break;
case 'ELPS HD':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Ovation':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'SelectionRx':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'SV':							$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'ST-28':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'ST-25':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Life XS':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Life II':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Purelife HD':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Vision Classique HD':			$lenscategory = " product_name like '%vision classique%' "; break;
case 'Innovative II DS':			$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Pro EZ HD':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Econo Choice Ultra One':		$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Econo Choice Ultra Short':	$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'iOffice':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Econo Choice':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Mini Pro HD':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'iFree':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'iAction':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'CMF 2 HD':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Vision Pro HD':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Infocus Single Vision':		$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Infocus Flat Top':			$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Infocus RX Direct Progressive':$lenscategory = " product_name like '%Infocus%' "; break;
case 'EasyOne':						$lenscategory = " product_name like '%Easy One%' "; break;
case 'TrueHD':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Identity by Optotech':		$lenscategory = " product_name like '%by optotech%' "; break;
case 'Precision SV HD':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Precision Active':			$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'iAction SV':					$lenscategory = " product_name like '%iAction%'"; break;
case 'iRelax':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'iReader':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Precision Daily':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'camber':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'maxiwide':					$lenscategory = " product_name like '%maxiwide%' "; break;
case 'Pro EZ':					    $lenscategory = " product_name like '%EZ HD%' "; break;  
case 'revolution':					$lenscategory = " product_name like '%revolution%' "; break;  
case 'revolution sv':				$lenscategory = " product_name like '%revolution%' and lens_Category ='sv'"; break;  
//Manufacturer
/*case 'SOLA':						$lenscategory = " (product_name like '%Econo Choice%' OR product_name like '%Pro EZ%') "; break;
case 'SHAMIR':						$lenscategory = " ( product_name like '%iFree%'  OR product_name like '%iRelax%' OR product_name like '%iAction%' OR product_name like '%iOffice%') "; break;
case 'SEIKO':						$lenscategory = " (product_name like '%trueHD%' OR product_name like '%EasyOne%') "; break; 
case 'RODENSTOCK':					$lenscategory = " ( product_name like '%Vision Classique HD%'  OR product_name like '%Purelife HD%' OR product_name like '%Life II%' OR product_name like '%Life XS%' ) "; break;
case 'ESSILOR':						$lenscategory = " (product_name like '%CMF%' OR product_name like '%Ovation%' OR product_name like '%PSI HD%' OR product_name like '%ELPS HD%')  "; break;
case 'MY WORLD':					$lenscategory = " (product_name like '%iFree%' OR product_name like '%iAction%' OR product_name like '%iOffice%' OR product_name like '%iRelax%') "; break;
case 'PRECISION':					$lenscategory = " ( product_name like '%Precision Daily%'  OR product_name like '%Precision Active%' OR product_name like '%Precision SV HD%' )"; break;  
case 'OPTIMIZE':					$lenscategory = "  product_name like '%Optimize%' "; break;     
case 'CZV':							$lenscategory = "  product_name like '%EZ HD%' "; break;   
case 'IOT':							$lenscategory = "  product_name like '%IOT%' "; break;      
case 'OPTOTECH':					$lenscategory = "  product_name like '%Optimize%' "; break; */            
}

$COATING=$_POST[COATING];
switch ($COATING) {
	case 'ANY':$COATING = " AND 6=6 "; break;
	case 'Hard Coat':$COATING = "AND coating IN  ('Hard Coat')";break;
	case 'Aqua Dream AR':$COATING = "AND coating IN   ('Aqua Dream AR')";break;
	case 'Uncoated':$COATING = " AND coating IN ('Uncoated')";break;        
	case 'AR Backside':$COATING = "AND coating IN  ('AR Backside')";break;
	case 'Blue AR':$COATING = "AND coating IN   ('Blue AR')";break;
	case 'CrizalF':$COATING = " AND coating IN ('CrizalF')";break; 
	case 'DH1':$COATING = " AND coating IN ('DH1') "; break;
	case 'DH2':$COATING = "AND coating IN  ('DH2')";break;
	case 'Dream AR':$COATING = "AND coating IN   ('Dream AR')";break;
	case 'HC':$COATING = " AND coating IN ('HC')";break; 
	case 'HD AR':$COATING = " AND coating IN ('HD AR') "; break;
	case 'Iblu':$COATING = "AND coating IN  ('Iblu')";break;
	case 'ITO AR':$COATING = "AND coating IN   ('ITO AR')";break;
	case 'MaxiiVue':$COATING = " AND coating IN ('MaxiiVue')";break; 	
	case 'MultiClear AR':$COATING = "AND coating IN  ('MultiClear AR')";break;
	case 'Smart AR':$COATING = "AND coating IN   ('Smart AR')";break;
	case 'StressFree':$COATING = " AND coating IN ('StressFree')";break;
	case 'BluCut':$COATING = " AND coating IN ('BluCut')";break;
	case 'AR-ES':$COATING = " AND coating IN ('AR-ES')";break;
	case 'AR-ES Backside':$COATING = " AND coating IN ('AR-ES Backside')";break;
	case 'Xlr':$COATING = " AND coating IN ('Xlr')";break;
	case 'Xlr Backside':$COATING = " AND coating IN ('Xlr Backside')";break;
}

$INDEX=$_POST[INDEX];
switch ($INDEX) {
	case 'ANY': $INDEX = " AND 7=7 "; break;
	case '1.57':$INDEX = " AND index_v IN (1.57)";break;
	case '1.60':$INDEX = " AND index_v IN (1.60)";break;
	case '1.70':$INDEX = " AND index_v IN (1.70)";break;
	case '1.50':$INDEX = " AND index_v IN (1.50)";break;
	case '1.67':$INDEX = " AND index_v IN (1.67)";break;
	case '1.53':$INDEX = " AND index_v IN (1.53)";break;
	case '1.56':$INDEX = " AND index_v IN (1.56)";break;
	case '1.74':$INDEX = " AND index_v IN (1.74)";break;
	case '1.76':$INDEX = " AND index_v IN (1.76)";break;
	case '1.59':$INDEX = " AND index_v IN (1.59)";break;
	case '1.54':$INDEX = " AND index_v IN (1.54)";break;
	case '1.80':$INDEX = " AND index_v IN (1.80)";break;
	case '1.90':$INDEX = " AND index_v IN (1.90)";break;
	case '1.52':$INDEX = " AND index_v IN (1.52)";break;
}

$LE_HEIGHT=$_SESSION['PrescrData']['LE_HEIGHT'];
$RE_HEIGHT=$_SESSION['PrescrData']['RE_HEIGHT'];

if ($LE_HEIGHT =="")
$LE_HEIGHT = 0;


if ($RE_HEIGHT =="")
$RE_HEIGHT = 0;

$PHOTO=$_POST[PHOTO];
$POLAR=$_POST[POLAR];

//Make sure no tint and polarized on same lens
if ((strtolower($_POST[TINT]) == 'solid') && ($POLAR <> 'None'))
$POLAR='doesnotexist';

if ((strtolower($_POST[TINT]) == 'gradient')&& ($POLAR <> 'None'))
$POLAR='doesnotexist';

//Make sure no tint and photochromic on same lens
if ((strtolower($_POST[TINT]) == 'solid') && ($PHOTO <> 'None'))
$PHOTO='doesnotexist';

if ((strtolower($_POST[TINT]) == 'gradient')&& ($PHOTO <> 'None'))
$PHOTO='doesnotexist';

//Prevent 1.59 + tint higher 85%
$TeintePlus60 = 'non';
$Pasde159 = " AND 1=1 ";

if (($_POST[FROM_PERC] > 85) && ($_POST[FROM_PERC]  <> "")&& (isset($_POST[FROM_PERC]))){
$TeintePlus60 = 'oui';
$Pasde159 = " AND INDEX_v NOT IN (1.59)" ;
}

if (($_POST[TO_PERC]   > 85) && ($_POST[TO_PERC]   <> "") && (isset($_POST[TO_PERC]))){
$TeintePlus60 = 'oui';
$Pasde159 = " AND INDEX_v NOT IN (1.59)" ;
}

//EMPECHER 1.59 tintable + teinte > 60% (on accepte teinte en bas de 61%)
if (($TeintePlus60=='oui') && ($_POST[INDEX] == '1.59'))
$POLAR='doesnotexist';




$ORDER_PATIENT_LAST=$_POST[LAST_NAME];

$RE_SPHERE=$_SESSION['PrescrData']['RE_SPHERE'];
$LE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];

$TRAY_NUM=$_SESSION['PrescrData']['TRAY_NUM'];

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
	$LE_HEIGHT=$RE_HEIGHT;
}

if ($_SESSION['PrescrData']['EYE']=="L.E."){
	$RE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];
	$RE_CYL=$_SESSION['PrescrData']['LE_CYL'];
	$RE_ADD=$_SESSION['PrescrData']['LE_ADD'];
	$RE_HEIGHT=$LE_HEIGHT;
}

//echo $_SESSION['PrescrData']['EYE'];

if (($_SESSION['PrescrData']['FRAME_MODEL']!="")&&($_SESSION['PrescrData']['ORDER_TYPE']=="Provide")){
			  
		$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
		$F_query="SELECT * FROM frames 
		LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
		WHERE model_num='$frame_model_num'";
		$F_result=mysqli_query($con,$F_query)				or die  ('I cannot select items because: ' . mysqli_error($con));
		$F_listItem=mysqli_fetch_array($F_result,MYSQLI_ASSOC);
	$collections=array();
	$collections=explode(";",$F_listItem[avail_prescript_collections]);
	$collectionNum=count($collections);
	
	if ($collectionNum!=0){
		$collectionString=" AND (collection='".$collections[0]."' ";

		for($i=1;$i<$collectionNum;$i++){
		$collectionString.=" OR collection='".$collections[$i]."' ";
		}
		$collectionString.=") ";
	//echo $collectionString;
	$collectionString = " ";
	}//END collectionNum
}//END IF FRAME


if ($_SESSION["sessionUserData"]["currency"]=="US"){
$PriceNotat0 = " AND price <> 0";
$orderby = " order by  on_sale desc, product_name"; }
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
$PriceNotat0 = " AND price_can <> 0";
$orderby = "  order by on_sale desc,product_name"; }	
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
$PriceNotat0 = " AND price_eur <> 0";
$orderby = "  order by on_sale desc ,product_name "; 
}


if ($Lens_Category == 'OPTIMIZE'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'IPL'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'Acuform'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'FIT'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'Horizon'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'DMT'){
$orderb = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'Lifestyle'){
$orderby = "AND order_by <> 999 order by order_by asc";
}

if ($Lens_Category == 'Anti-Fatigue'){
$orderby = "AND order_by <> 999 order by order_by asc";
}


	

$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
$COATING
$INDEX
$Pasde159
AND $lenscategory
AND photo='$PHOTO' 
$PriceNotat0
$EmpecherProduitSvisionStock
AND polar='$POLAR' 
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
 $orderby"; //EXCLUSIVE

echo '<br><br>'.$query;

//if ($_SESSION["sessionUser_Id"] == 'Lenzandtrenz')
//echo '<br><br>'.$query;

$result=mysqli_query($con,$query)	or die  ("Please resubmit your form.<br><a href='javascript:history.back()'>Go Back 1 step.</a>". mysqli_error($con)." sql=".$query);
$usercount=mysqli_num_rows($result);
if ($usercount != 0){

echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescriptionDetail.php\"   onSubmit=\"return validate(this.name,'product_id')\">";
}
else{
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescription_retry.php\">";
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Prescription Search</title>
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

<script language="JavaScript" type="text/javascript">
  
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<link href="dl.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg">
		<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn">
		      <?php 
	include("includes/sideNav.inc.php");
	?>
        </div></td>
   <td width="685" valign="top" >
		   <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_availprod_txt;?></div></td><td></td></tr></table>
           
         

           <?php
		   if ($_SESSION['PrescrData']['EYE']=="L.E."){
		   //echo '<br><br>left eye only<br><br>';
		   $queryDoublons = "SELECT order_num from orders where
		    le_add    = '$LE_ADD'
			and le_axis   = '$LE_AXIS'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE' 
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";		   
		   }
		   
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="R.E."){
		    // echo '<br><br>right eye only<br><br>';
		    $queryDoublons = "SELECT order_num from orders where
		    	re_add    = '$RE_ADD' 
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'
			 ";
		   }
		   
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="Both"){
		     //echo '<br><br>both eyes<br><br>';
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
		   
		   
		   $resultDoublons = mysqli_query($con,$queryDoublons)	or die  ("Erreur:". mysqli_error($con));
		   $countDoublons  = mysqli_num_rows($resultDoublons);
		   $queryAfficherWarning = "SELECT display_double_warning from accounts where user_id = '". 
		   $_SESSION["sessionUser_Id"] . "'";
		   $resultAfficherWarning = mysqli_query($con,$queryAfficherWarning)	or die  ("Erreur:". mysqli_error($con));
		   $DataAfficherWarning=mysqli_fetch_array($resultAfficherWarning,MYSQLI_ASSOC);
		   $AfficherWarning = $DataAfficherWarning['display_double_warning'];
	   
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes')){
		   echo '<br><font color="#FF0000" >Warning: An order with the same patient ref number and Rx is already in the system</font>.<br> ';
		   }
		   
		  $TrayDejaUtilise = 'no';
		   
		if ($TRAY_NUM <> '') 
		{	   
				$queryTray = "SELECT order_num as nbrResultat from orders where
				tray_num = '$TRAY_NUM'  
				and order_status NOT IN ('filled','cancelled','re-do')";
				$resultTray = mysqli_query($con,$queryTray)	or die  ("Erreur:". mysqli_error($con) );
				 $countResultat  = mysqli_num_rows($resultTray);
				 if ($countResultat>0){
					if ($mylang == 'lang_french'){
					 echo '<br><font color="#FF0000" >Ce cabaret est déjà relié à une autre commande.</font><br> ';
					}else {
					 echo '<br><font color="#FF0000" >This tray is already linked to another order</font><br> ';
					}
					$TrayDejaUtilise = 'yes';
			   
				 }
		}//End IF traynum is not empty		 
		   ?>

		    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"]?></div>
		    <div class="Subheader"><?php echo $lbl_srchres_txt;?></div>
		   
		    <div class="plainText"><?php echo $lbl_numofitemsfnd_txt;?> <?php echo $usercount?> </div>
            
             <?php 
			if ($Pasde159 <> " AND 1=1 "){
				 if ($mylang == 'lang_french'){
				 	echo "<br><div>L'intensité maximale est de 85% sur le 1.59 tintable</div>";
				 }else{
	 				 echo "<br><div>The max. intensity is 85% on 1.59 tintable</div>";	
				 }
			}?>
            
            
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="9" bgcolor="#000098" class="tableHead">&nbsp;</td>
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
	echo "<tr><td colspan=\"9\" class=\"formCell\">Sorry, no items found.</td></tr>";
	}else{
		echo "<tr>";
		while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$item++;
			echo "<td align=\"left\" class=\"formCell\">";
			
			$Lien="";
			switch ($listItem[logo_file]) {
			case '1' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-essilor.gif";      	break; //logo essilor 
			case '2' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-hoya.gif";         	break; //logo Hoya
			case '3' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-kodak.gif";	     	break; //logo Kodak
			case '4' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-shamir.gif"; 	  	break; //logo Shamir
			case '5' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-sola.gif"; 	     	break; //logo SOLA
			case '6' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-rodenstock.gif";   	break; //logo RODENSTOCK
			case '7' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-zeiss.gif"; 	    break; //logo ZEISS      
			case '8' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-optotech.jpg";     	break; //logo Optotech      
			case '9' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo-seiko.jpg"; 	    break; //logo Seiko      
			case '10' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo12.jpg";	    break; //logo Precision
			case '11' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_acuform.jpg"; 	break; //logo Acuform Optimize
			case '12' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_ipl.jpg"; 	 	break; //logo Acuform IPL
			case '13' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_eye_fatigue.jpg"; 	 break; //logo Acuform Eye Fatigue
			case '14' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_horizon.jpg"; break; //logo Horizon
			case '15' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_dmt.jpg"; 	 break; //logo DMT
			case '16' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_fit.jpg"; 	 break; //logo Fit
			case '17' :$LogoAInclure = constant('DIRECT_LENS_URL')."/direct-lens/images/logo_office.jpg";  break; //logo Office lifestyle
			case '18' :$LogoAInclure = constant('DIRECT_LENS_URL')."/lensnet/images/logo_revolution.jpg";	 break; //logo Revolution  
			default  :$LogoAInclure =  constant('DIRECT_LENS_URL')."/direct-lens/images/vide.jpg";	     break; //Aucun logo, fond blanc
			}


			if ($LogoAInclure <> "")
			{
			//There is a logo to display
				if ($Lien <> ""){
				//echo "<div style=\"float:left;width:50px;\"><a target=\"_blank\" href=\"$Lien\"><img src=\"\" width='50px' /></a></div>&nbsp;&nbsp;";
				}else{
				//echo "<div style=\"float:left;width:50px;\"><img src=\"$LogoAInclure\" width='50px' /></div>&nbsp;&nbsp;";
				}
				
				
			}
			
			
			echo "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=450')\">";
			
			
			echo $listItem[product_name];
			echo "</a>";
			//Insérer le logo Special si le produit est en spécial
			if(strtolower($listItem[on_sale]) =='yes')
			echo "<div style=\"float:left;width:50px;\"><img src=\"http://www.direct-lens.com/images/sale.gif\" width='50px' /></div>";
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[index_v];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[coating];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[photo];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[polar];
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
			
			if ($_SESSION["sessionUserData"]["currency"]=="US"){
				$price=$listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				$price=$listItem[price_can];}	
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				$price=$listItem[price_eur];}
			
			if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
				$price=$listItem[e_lab_us_price];}	
			else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
				$price=$listItem[e_lab_can_price];}
				
			if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
				$price=money_format('%.2n',$price/2);
			}
			
			
			//WARRANTY
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 6;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 10;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 3;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 5;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 1) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 3;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 2) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 5;
			}
			
						
			$price= money_format('%.2n',$price); 
				
			
			$queryLanguage = "SELECT language, currency from accounts WHERE user_id = '" .  $_SESSION["sessionUser_Id"] . "'";
			$LanguageResult=mysqli_query($con,$queryLanguage)	or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataLanguage=mysqli_fetch_array($LanguageResult,MYSQLI_ASSOC);
			$CustomerLanguage = $DataLanguage['language'];
			$CustomerCurrency = $DataLanguage['currency'];
			
			
			switch($CustomerCurrency){
			case 'CA':     $CustomerCurrency = '$';     	  break;
			case 'US':     $CustomerCurrency = '$';    		  break;
			case 'EUR':    $CustomerCurrency = "&#128;";       break;
			}
			
			
			if ($CustomerLanguage == 'english'){
			echo $CustomerCurrency .$price;
			}else{
			echo $price . $CustomerCurrency;
			}
			
			echo "</td>";
			echo "<td  align=\"center\" class=\"formCell\">";
			echo "<input type=\"radio\" name=\"product_id\" id=\"product_id\" value=\"$listItem[primary_key]\"";
			
			if ($item==1){
				echo  "checked=\"checked\" />";}
			else{
				echo"/>";}
			echo "</td></tr>";
			   }//end of while
}//end of 0 usercount
?>

          </table> <?php
if ($usercount != 0){ 

 echo "<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"  onclick=\"window.open('prescription_retry.php', '_top')\"/>&nbsp;";
 if ($TrayDejaUtilise == 'yes'){
 echo "<input name=\"Submit\" type=\"submit\" disabled=\"disabled\" value=\"".$adm_proceed_txt."\";/>";
 }else{
  echo "<input name=\"Submit\" type=\"submit\" value=\"".$adm_proceed_txt."\";/>";
 }
 
 echo "</div></form>";
			}
			else{
			 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"  onclick=\"window.open('prescription_retry.php', '_top')\"/></div></form>";
			}
			
			?></td>
  </tr>
</table>
        
		  </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>