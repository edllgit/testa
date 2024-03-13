<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Session</title>
</head>

<body>
<?php
session_start();
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

$REFERER  = $_SERVER[HTTP_REFERER]; 

switch($REFERER){
	//C
	case 'http://c.direct-lens.com/ifcopticclubca/prescription.php': 	   $AdresseRetour = constant('DIRECT_LENS_URL')."/ifcopticclubca/prescription.php";        break;	
	//Régulier
	//case 'http://b.direct-lens.com/ifcopticclubca/prescription.php': 	     $AdresseRetour = "http://b.direct-lens.com/ifcopticclubca/prescription.php";  break;	
	//case 'http://b.direct-lens.com/ifcopticclubca/sv_frame_form.php':      $AdresseRetour = "http://b.direct-lens.com/ifcopticclubca/prescription.php";  break;
	//case 'http://b.direct-lens.com/ifcopticclubca/prescription_frame.php': $AdresseRetour = "http://b.direct-lens.com/ifcopticclubca/prescription.php";  break;
	default:  $AdresseRetour = $REFERER; 
}

$queryPwd  	    = "SELECT password FROM accounts WHERE user_id = '" .$_SESSION["sessionUser_Id"]. "'";
$resultPWD 	    = mysql_query($queryPwd)	or die ("Could not select items" . mysql_error());
$DataPwd  	    = mysql_fetch_array($resultPWD);
$user_test 		= $_SESSION["sessionUser_Id"];
$password_test  = $DataPwd[password];
echo '<br>User id:'. $user_test;
echo '<br>pwd:'. $password_test;

//On efface tout ce qui est dans $SESSION
//Alternative, vider les champs qu'on veut seulement
$_SESSION['PrescrData']['MIRROR']				  = '';
$_SESSION['PrescrData']['RE_ET']				  = '';
$_SESSION['PrescrData']['LE_ET']				  = '';
$_SESSION['PrescrData']['RE_CT']				  = '';
$_SESSION['PrescrData']['LE_CT']				  = '';
$_SESSION['PrescrData']['BASE_CURVE']			  = '';
$_SESSION['PrescrData']['TRAY_NUM']				  = '';
$_SESSION['PrescrData']['REFERENCE_PROMO']		  = '';
$_SESSION['PrescrData']['EYE']			    	  = '';
$_SESSION['PrescrData']['LAST_NAME']	   	 	  = '';
$_SESSION['PrescrData']['FIRST_NAME']	   	 	  = '';
$_SESSION['PrescrData']['PATIENT_REF_NUM'] 	 	  = '';
$_SESSION['PrescrData']['SALESPERSON_ID']  	 	  = '';
$_SESSION['PrescrData']['RE_SPH_NUM']			  = '';
$_SESSION['PrescrData']['RE_SPH_DEC']			  = '';
$_SESSION['PrescrData']['RE_CYL_NUM']			  = '';
$_SESSION['PrescrData']['RE_CYL_DEC']			  = '';
$_SESSION['PrescrData']['RE_SPHERE']			  = '';
$_SESSION['PrescrData']['RE_CYL']				  = '';
$_SESSION['PrescrData']['RE_AXIS']				  = '';
$_SESSION['PrescrData']['RE_ADD']				  = '';
$_SESSION['PrescrData']['WARRANTY']				  = '';
$_SESSION['PrescrData']['LE_CYL']				  = '';
$_SESSION['PrescrData']['LE_SPH_NUM']			  = '';
$_SESSION['PrescrData']['LE_SPH_DEC']			  = '';
$_SESSION['PrescrData']['LE_CYL_NUM']			  = '';
$_SESSION['PrescrData']['LE_CYL_DEC']			  = '';
$_SESSION['PrescrData']['LE_AXIS']				  = '';
$_SESSION['PrescrData']['LE_ADD']				  = '';
$_SESSION['PrescrData']['RE_PR_AX']				  = '';
$_SESSION['PrescrData']['RE_PR_AX2']			  = '';
$_SESSION['PrescrData']['RE_PR_IO']				  = '';
$_SESSION['PrescrData']['RE_PR_UD']				  = '';
$_SESSION['PrescrData']['LE_SPHERE']			  = '';
$_SESSION['PrescrData']['LE_PR_AX']				  = '';
$_SESSION['PrescrData']['LE_PR_AX2']			  = '';
$_SESSION['PrescrData']['LE_PR_IO']				  = '';
$_SESSION['PrescrData']['LE_PR_UD']				  = '';
$_SESSION['PrescrData']['LE_PR_AX']				  = '';
$_SESSION['PrescrData']['LE_PR_AX2']			  = '';
$_SESSION['PrescrData']['RE_PR_AX2']			  = '';
$_SESSION['PrescrData']['RE_PR_AX']				  = '';
$_SESSION['PrescrData']['LE_PD']				  = '';
$_SESSION['PrescrData']['LE_PD_NEAR']			  = '';
$_SESSION['PrescrData']['LE_HEIGHT']			  = '';
$_SESSION['PrescrData']['RE_PD']				  = '';
$_SESSION['PrescrData']['RE_PD_NEAR']			  = '';
$_SESSION['PrescrData']['RE_HEIGHT']			  = '';
$_SESSION['PrescrData']['COATING']				  = '';
$_SESSION['PrescrData']['INDEX']				  = '';
$_SESSION['PrescrData']['PHOTO']				  = '';
$_SESSION['PrescrData']['POLAR']				  = '';
$_SESSION['PrescrData']['FRAME_A']				  = '';
$_SESSION['PrescrData']['FRAME_B']				  = '';
$_SESSION['PrescrData']['FRAME_ED']				  = '';
$_SESSION['PrescrData']['FRAME_DBL']			  = '';
$_SESSION['PrescrData']['FRAME_TYPE']			  = '';
$_SESSION['PrescrData']['ENGRAVING']			  = '';
$_SESSION['PrescrData']['TINT']					  = '';
$_SESSION['PrescrData']['TINT_COLOR']			  = '';
$_SESSION['PrescrData']['FROM_PERC']			  = '';
$_SESSION['PrescrData']['TO_PERC']				  = '';
$_SESSION['PrescrData']['JOB_TYPE']				  = '';
$_SESSION['PrescrData']['ORDER_TYPE']			  = '';
$_SESSION['PrescrData']['SUPPLIER']				  = '';
$_SESSION['PrescrData']['FRAME_MODEL']			  = '';
$_SESSION['PrescrData']['COLOR']				  = '';
$_SESSION['PrescrData']['ORDER_TYPE']			  = '';
$_SESSION['PrescrData']['TEMPLE']				  = '';
$_SESSION['PrescrData']['TEMPLE_MODEL']			  = '';
$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']   = '';
$_SESSION['PrescrData']['INTERNAL_NOTE']		  = '';
$_SESSION['PrescrData']['VERTEX']				  = '';
$_SESSION['PrescrData']['PT']					  = '';
$_SESSION['PrescrData']['PA']					  = '';
$_SESSION['PrescrData']['OPTICAL_CENTER']		  = '';
$_SESSION['PrescrData']['CUSHION']                = '';
$_SESSION['PrescrData']['CUSHION_SELLING_PRICE']  = '';
$_SESSION['PrescrData']['DUST_BAR']               = '';
$_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE'] = '';
$_SESSION['PrescrData']['myupload']               = '';

header("Location:$AdresseRetour");
?>
</body>
</html>