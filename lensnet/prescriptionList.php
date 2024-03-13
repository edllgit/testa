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
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");
require('../includes/dl_order_functions.inc.php');
global $drawme;

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

catchOrderData();

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
//Lens Type
case 'AO Compact':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Compact Ultra HD':			$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'EZ':							$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'FT28':						$lenscategory = " product_name like '%28%' "; break;
case 'FT35':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'FT45':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'FF BY IOT':					$lenscategory = " product_name like '%FF BY IOT%' ";      break;
case 'GT2':							$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Infinissim':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Innovative 1.0':				$lenscategory = " product_name like '%Innovative 1.0%' "; break;
case 'Innovative 2.0':				$lenscategory = " product_name like '%Innovative 2.0%' "; break;
case 'Innovative 3.0':				$lenscategory = " product_name like '%Innovative 3.0%' "; break;
case 'RD':							$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Single Vision':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Sola Easy':					$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'SolaOne':						$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Trifocal 7x28':				$lenscategory = " product_name like '%$Lens_Category%' "; break;
case 'Trifocal 8x35':				$lenscategory =	" product_name like '%$Lens_Category%' "; break;
case 'SelectionRx':					$lenscategory = " product_name like '%$Selection%' "; break;
//Manufacturer
case 'ESSILOR':						$lenscategory = " (product_name like '%CMF%' OR product_name like '%ELPS%' OR product_name like '%PSI%' OR product_name like '%OVATION%'  OR product_name like '%Smallfit%')  "; break;
case 'SOLA':						$lenscategory = " (product_name like '%AO COMPACT%' OR product_name like '%COMPACT ULTRA%' OR product_name like '%SOLAONE%' OR product_name like '%SOLA EASY%') "; break;
case 'RODENSTOCK':					$lenscategory = " ( product_name like '%VISION CLASSIQUE%'  OR product_name like '%PURELIFE XS%' ) "; break;
case 'HOYA':						$lenscategory = " ( product_name like '%SUMMIT%') ";   break;
case 'SHAMIR':						$lenscategory = " ( product_name like '%SHAMIR GENESIS%'  OR product_name like '%SHAMIR PICCOLO%' ) "; break;
case 'ZEISS':						$lenscategory = " ( product_name like '%GT2%'  OR product_name like '%EZ%' )"; break;
case 'KODAK':						$lenscategory = " product_name like '%kodak%' ";      break;
case 'OPTOVISION':					$lenscategory = " product_name like '%optovision%' "; break;
case 'IOT':                         $lenscategory = " product_name like '%IOT%' ";        break;
case 'OPTOTECH':                    $lenscategory = " product_name like '%OPTOTECH%' ";   break;
case 'SOLA/ZEISS':                  $lenscategory = " product_name like '%sola%' ";       break;
default:   							$lenscategory = " AND 99 = 99 ";       
}
$COATING=$_POST[COATING];

switch ($COATING) {
	case 'ANY': $COATING = "('DH1','DH2','Hard Coat','Aqua Dream AR','Dream AR','ITO AR','MultiClear AR','Smart AR','Uncoated', 'Xlr','CrizalF','Xlr')";  break;
	case 'Hard Coat': $COATING = "('DH1','DH2','Hard Coat')"; break;
	case 'AR': $COATING = "('Aqua Dream AR','Dream AR','ITO AR','MultiClear AR','Smart AR','Xlr','CrizalF')"; break;
	case 'Uncoated': $COATING = "('Uncoated')"; break;                                    
}

$INDEX=$_POST[INDEX];
$tintable="yes";

switch ($INDEX) {
case 'ANY' :$INDEX = "(1.57, 1.60, 1.70, 1.50, 1.67, 1.53, 1.56, 1.74, 1.59, 1.54, 1.80, 1.90, 1.52, 1.58)"; break;
case '1.57': $INDEX = "(1.57)"; break;
case '1.60': $INDEX = "(1.60)"; break;
case '1.70': $INDEX = "(1.70)"; break;
case '1.50': $INDEX = "(1.50)"; break;
case '1.67': $INDEX = "(1.67)"; break;
case '1.53': $INDEX = "(1.53)"; break;
case '1.56': $INDEX = "(1.56)"; break;
case '1.74': $INDEX = "(1.74)"; break;
case '1.59': $INDEX = "(1.59)"; break;
case '1.58': $INDEX = "(1.58)"; break;
case '1.592':$INDEX = "(1.59)"; $tintable="no"; break;
case '1.54': $INDEX = "(1.54)"; break;
case '1.80': $INDEX = "(1.80)"; break;
case '1.90': $INDEX = "(1.90)"; break;
case '1.52': $INDEX = "(1.52)"; break;
}

$LE_HEIGHT=$_POST[LE_HEIGHT];
$RE_HEIGHT=$_POST[RE_HEIGHT];

if ($LE_HEIGHT =="")
{
$LE_HEIGHT = 0;
}

if ($RE_HEIGHT =="")
{
$RE_HEIGHT = 0;
}

/*
if(($_SESSION["sessionUser_Id"]=="jeannicolaslnc") && ($RE_HEIGHT > 0) && ($LE_HEIGHT > 0))
{
// 1-Déterminer si les deux hauteurs sont pareilles
	$RE_HEIGHT = $RE_HEIGHT -2;	
}*/



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


//EMPECHER 1.59 tegra + teinte
if ((strtolower($_POST[TINT]) == 'gradient')&& ($_POST[INDEX] == '1.592'))
$POLAR='doesnotexist';
if ((strtolower($_POST[TINT]) == 'solid')&& ($_POST[INDEX] == '1.592'))
$POLAR='doesnotexist';

//EMPECHER 1.59 + teinte
if ((strtolower($_POST[TINT]) == 'gradient')&& ($_POST[INDEX] == '1.59'))
$POLAR='doesnotexist';
if ((strtolower($_POST[TINT]) == 'solid')&& ($_POST[INDEX] == '1.59'))
$POLAR='doesnotexist';


$TeintePlus60 = 'non';
$Pasde159 = " AND 1=1 ";
if (($_POST[TO_PERC]   > 60) && ($_POST[TO_PERC]   <> "") && (isset($_POST[TO_PERC]))){
$TeintePlus60 = 'oui';
$Pasde159 = " AND INDEX_v NOT IN (1.59)" ;
}
if (($_POST[FROM_PERC] > 60) && ($_POST[FROM_PERC]  <> "")&& (isset($_POST[FROM_PERC]))){
$TeintePlus60 = 'oui';
$Pasde159 = " AND INDEX_v NOT IN (1.59)" ;
}

//EMPECHER 1.59 tintable + teinte > 60% (on accepte teinte en bas de 61%)
if (($TeintePlus60=='oui') && ($_POST[INDEX] == '1.59'))
$POLAR='doesnotexist';



$ORDER_PATIENT_LAST=$_POST[LAST_NAME];

$RE_SPHERE=$_SESSION['PrescrData']['RE_SPHERE'];
$LE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];

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
	$LE_HEIGHT = $RE_HEIGHT;
}

if ($_SESSION['PrescrData']['EYE']=="L.E."){
	$RE_SPHERE=$_SESSION['PrescrData']['LE_SPHERE'];
	$RE_CYL=$_SESSION['PrescrData']['LE_CYL'];
	$RE_ADD=$_SESSION['PrescrData']['LE_ADD'];
	$RE_HEIGHT = $LE_HEIGHT;
}

if (($_SESSION['PrescrData']['FRAME_MODEL']!="")&&($_SESSION['PrescrData']['ORDER_TYPE']=="Provide")){
			  
		$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
		$F_query="SELECT * FROM frames 
		LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
		WHERE model_num='$frame_model_num'";
		$F_result=mysqli_query($con,$F_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
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
	}//END collectionNum
}//END IF FRAME

if ($_SESSION["sessionUserData"]["currency"]=="CA"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND coating IN $COATING 
AND index_v IN $INDEX 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
order by product_name asc"; //EXCLUSIVE



if ($COATING=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND index_v='$INDEX' 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
order by product_name asc"; //EXCLUSIVE
}

if ($INDEX=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
$Pasde159
AND tintable = '$tintable'
AND coating='$COATING' 
AND photo='$PHOTO' 
AND  $lenscategory
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
order by product_name asc"; //EXCLUSIVE
}

if (($INDEX=="ANY")&($COATING=="ANY")){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
$Pasde159
AND tintable = '$tintable'
AND photo='$PHOTO' 
AND polar='$POLAR' 
AND  $lenscategory
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
order by product_name asc"; //EXCLUSIVE
}

}//END CA CONDITIONAL



if ($_SESSION["sessionUserData"]["currency"]=="US"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND price!='0.00' 
AND coating IN $COATING 
AND index_v IN $INDEX 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE

if ($COATING=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND price !='0.00' 
AND index_v IN $INDEX 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

if ($INDEX=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection)
WHERE prod_status='active' 
$Pasde159
AND tintable = '$tintable'
AND price!='0.00' 
AND coating IN $COATING 
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

if (($INDEX=="ANY")&($COATING=="ANY")){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
$Pasde159
AND tintable = '$tintable'
AND price!='0.00' 
AND photo='$PHOTO' 
AND  $lenscategory
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

}//END US CODITIONAL





//BEGIN EURO
if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND price_eur !='0.00' 
AND coating IN $COATING 
AND index_v IN $INDEX 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE

if ($COATING=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
AND price_eur !='0.00' 
AND index_v IN $INDEX 
$Pasde159
AND tintable = '$tintable'
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

if ($INDEX=="ANY"){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection)
WHERE prod_status='active' 
AND tintable = '$tintable'
AND price_eur !='0.00' 
AND coating IN $COATING 
$Pasde159
AND  $lenscategory
AND photo='$PHOTO' 
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

if (($INDEX=="ANY")&($COATING=="ANY")){
$query="select * from acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN exclusive on (liste_collection_info.collection_name = exclusive.collection) 
WHERE prod_status='active' 
$Pasde159
AND tintable = '$tintable'
AND price_eur !='0.00' 
AND photo='$PHOTO' 
AND  $lenscategory
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
AND (add_min<=$LE_ADD) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by product_name asc"; //EXCLUSIVE
}

}//END EURO CODITIONAL

//if ( $_SESSION["sessionUser_Id"] =='Lenzandtrenz'){
	//echo '<br><br>QUERY:'.$query;
//}

$result=mysqli_query($con,$query)	or die  ("Please resubmit your form.<br><a href='javascript:history.back()'>Go Back 1 step.</a>". '  QUERY: '. $query .'   '.  mysqli_error($con));
$usercount=mysqli_num_rows($result);
if ($usercount != 0){

echo"<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescriptionDetail.php\"   onSubmit=\"return validate(this.name,'product_id')\">";
}
else{
echo"<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescription_retry.php\">";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript">
  
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
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

<?php //} ?>


   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

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
<div id="rightColumn"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>
<div id="headerBox" class="header">
<?php echo $lbl_availprod_txt;?></div>
<?php if ($mylang == 'lang_french'){
	
		echo '<br><br><br><p align="center">
			Veuillez noter que le traitement XLR est maintenant disponible.</p>';
	}else{
	echo '<br><br><br><br><p  align="center">Please, be informed that the XLR coating is now available.</p>';
				
	} ?>

</td><td><div id="headerGraphic"><img src="http://www.direct-lens.com/lensnet/images/list_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
           
         

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
		   
		   			 
		//   echo '<br><br>'. $queryDoublons . '<br><br>';
		   $resultDoublons = mysqli_query($con,$queryDoublons)	or die  ("Erreur:". mysqli_error($con) );
		   $countDoublons  = mysqli_num_rows($resultDoublons);
		  // echo '<br><br>Nombre de commande pareilles: ' .$countDoublons;
		   
		    $queryAfficherWarning = "SELECT display_double_warning from accounts where user_id = '". 
		    $_SESSION["sessionUser_Id"] . "'";
		    $resultAfficherWarning = mysqli_query($con,$queryAfficherWarning) or die  ("Erreur:". mysqli_error($con) );
		   	$DataAfficherWarning=mysqli_fetch_array($resultAfficherWarning,MYSQLI_ASSOC);
			$AfficherWarning = $DataAfficherWarning['display_double_warning'];
		 //
		   
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes')){
		   echo '<br><font color="#FF0000" >Warning: An order with the same patient ref number and Rx is already in the system</font>.<br> ';
		   }
		   ?>

		    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"]?></div>
		    <div class="Subheader"><?php echo $lbl_srchres_txt;?></div>
            
           
		   
      <div class="plainText"><?php echo $lbl_numofitemsfnd_txt;?> <?php echo $usercount?>  
      <br /><br />
      				 </div>
      
      		 <?php 
			if ($Pasde159 <> " AND 1=1 "){
				 if ($mylang == 'lang_french'){
				 	echo "<br><div>L'intensité maximale est de 60% sur le 1.59 tintable</div>";
				 }else{
	 				 echo "<br><div>The max. intensity is 60% on 1.59 tintable</div>";	
				 }
			}?>
      
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
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
				else if ($_SESSION["sessionUserData"]["currency"]=="EU"){
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
			
			
			switch ($listItem[logo_file]) {
			case '1'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-essilor.gif"; 		$lien='';    	break; //logo essilor 
			case '2'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-hoya.gif";    		$lien='';       break; //logo Hoya
			case '3'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-kodak.gif";   		$lien='';	    break; //logo Kodak
			case '4'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-shamir.gif";  		$lien=''; 	    break; //logo Shamir
			case '5'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-sola.gif";     		$lien='';	    break; //logo SOLA
			case '6'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-rodenstock.gif"; 	$lien='';  		break; //logo RODENSTOCK
			case '7'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-zeiss.gif";  		$lien='';	    break; //logo ZEISS      
			case '8'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-optotech.jpg";		$lien='';   	break; //logo Optotech      
			case '9'  :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo-seiko.jpg";  	    $lien='';	    break; //logo Seiko    
			case '10' :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo_infinissim.jpg"; 	$lien=''; 		break; //logo Infinissim      
			case '18' :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo_revolution.jpg"; 	$lien=''; 		break; //logo Revolution  
			case '19' :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/logo_iot.png"; 			$lien='http://www.youtube.com/watch?v=3GqH1XgSs9o'; 	    break; //logo IOT  
			case '20' :$LogoAInclure  = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images//optovision.jpg"; 		$lien='';	    break; //logo Optovision  
			
			default  :$LogoAInclure = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/images/vide.jpg";	 				$lien='';		break; //Aucun logo, logo blanc
			}

			if (($LogoAInclure <> "") && ($lien <> ''))
			{
			//There is a logo to display
			echo "<div style=\"float:left;width:60px;\"><a target=\"_blank\" href=\"$lien\"><img src=\"$LogoAInclure\" width='60px' /></a></div>&nbsp;&nbsp;";
			}else 
			echo "<div style=\"float:left;width:60px;\"><img src=\"$LogoAInclure\" width='60px' /></div>&nbsp;&nbsp;";
			
			
			echo "<a  href=\"#\" onClick=\"MM_openBrWindow('lens_specs.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=450')\">";
			
			$Product_name =  $listItem[product_name];
			$Product_name =str_replace('CMF','Comfort',$Product_name);
			$Product_name =str_replace('ELPS','Ellipse',$Product_name);
			$Product_name =str_replace('PSI','Physio',$Product_name);
			
			echo $Product_name;
			echo "</a></td>";
			
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
			

			
			if (($_SESSION[PrescrData][WARRANTY]== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 40;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 20;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 20;
			}
			
			
			if (($_SESSION[PrescrData][WARRANTY]== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 20;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 10;
			}
			
			if (($_SESSION[PrescrData][WARRANTY]== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 10;
			}
			
			
			switch($_SESSION["sessionUserData"]["currency"]){
			case 'CA':     $CustomerCurrency = '$';     	  break;
			case 'US':     $CustomerCurrency = '$';    		  break;
			case 'EUR':    $CustomerCurrency = "&#128;";       break;
			}
			
			
						
			$price= money_format('%.2n',$price); 
				
				
			$queryLanguage = "SELECT language from accounts WHERE user_id = '" .  $_SESSION["sessionUser_Id"] . "'";
			$LanguageResult=mysqli_query($con,$queryLanguage)	or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataLanguage=mysqli_fetch_array($LanguageResult,MYSQLI_ASSOC);
			$CustomerLanguage = $DataLanguage['language'];
			if ($CustomerLanguage == 'english'){
			echo $CustomerCurrency .$price;
			}else{
			echo $price .  $CustomerCurrency;
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

 echo "<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"  onclick=\"window.open('prescription_retry.php', '_top')\"/>&nbsp;<input name=\"Submit\" type=\"submit\" value=\"".$adm_proceed_txt."\";/></div></form>";
			}
			else{
			 echo "<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"  onclick=\"window.open('prescription_retry.php', '_top')\"/></div></form>";
			}
			
			?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
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