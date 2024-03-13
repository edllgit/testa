<?php
session_start();
include("includes/pw_functions.inc.php");
include_once("../Connections/sec_connect.inc.php");
include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';

//Save existing input in case of error in the validation code
	$_SESSION[newAccountLensnet][title] 	    = ucwords(addslashes($_POST[title]));
	$_SESSION[newAccountLensnet][buying_group] 	= ucwords(addslashes($_POST[buying_group]));
	$_SESSION[newAccountLensnet][main_lab] 		= ucwords(addslashes($_POST[main_lab]));
	$_SESSION[newAccountLensnet][user_id] 		= ucwords(addslashes($_POST[user_id]));
	$_SESSION[newAccountLensnet][language] 		= ucwords(addslashes($_POST[language]));
	$_SESSION[newAccountLensnet][company] 		= ucwords(addslashes($_POST[company]));
	$_SESSION[newAccountLensnet][first_name] 	= ucwords(addslashes($_POST[first_name]));
	$_SESSION[newAccountLensnet][last_name] 	= ucwords(addslashes($_POST[last_name]));
	$_SESSION[newAccountLensnet][bill_address1] = ucwords(addslashes($_POST[bill_address1]));
	$_SESSION[newAccountLensnet][bill_address2] = ucwords(addslashes($_POST[bill_address2]));
	$_SESSION[newAccountLensnet][bill_city] 	= ucwords(addslashes($_POST[bill_city]));
	$_SESSION[newAccountLensnet][bill_state] 	= ucwords(addslashes($_POST[bill_state]));
	$_SESSION[newAccountLensnet][bill_country]  = ucwords(addslashes($_POST[bill_country]));
	$_SESSION[newAccountLensnet][bill_zip]      = ucwords(addslashes($_POST[bill_zip]));
	$_SESSION[newAccountLensnet][findus] 		= ucwords(addslashes($_POST[findus]));
	$_SESSION[newAccountLensnet][account_no] 	= ucwords(addslashes($_POST[account_no]));
	$_SESSION[newAccountLensnet][phone] 		= ucwords(addslashes($_POST[phone]));
	$_SESSION[newAccountLensnet][other_phone]   = ucwords(addslashes($_POST[other_phone]));
	$_SESSION[newAccountLensnet][fax] 		    = ucwords(addslashes($_POST[fax]));
	$_SESSION[newAccountLensnet][email] 		= ucwords(addslashes($_POST[email]));
	$_SESSION[newAccountLensnet][VAT_no] 		= ucwords(addslashes($_POST[VAT_no]));
	$_SESSION[newAccountLensnet][business_type] = ucwords(addslashes($_POST[business_type]));
	$_SESSION[newAccountLensnet][currency] 	 	= ucwords(addslashes($_POST[currency]));
	$_SESSION[newAccountLensnet][findus]	 	= ucwords(addslashes($_POST[findus]));
	
	if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
		$_SESSION[newAccountLensnet][ship_address1]  = ucwords(addslashes($_SESSION[newAccountLensnet][bill_address1]));
		$_SESSION[newAccountLensnet][ship_address2]  = ucwords(addslashes($_SESSION[newAccountLensnet][bill_address2]));
		$_SESSION[newAccountLensnet][ship_city] 	 = ucwords(addslashes($_SESSION[newAccountLensnet][bill_city]));
		$_SESSION[newAccountLensnet][ship_state] 	 = ucwords(addslashes($_SESSION[newAccountLensnet][bill_state]));
		$_SESSION[newAccountLensnet][ship_zip] 	 	 = ucwords(addslashes($_SESSION[newAccountLensnet][bill_zip]));
		$_SESSION[newAccountLensnet][ship_country]   = ucwords(addslashes($_SESSION[newAccountLensnet][bill_country]));
	}else{
		$_SESSION[newAccountLensnet][ship_address1]  = ucwords(addslashes($_POST[ship_address1]));
		$_SESSION[newAccountLensnet][ship_address2]  = ucwords(addslashes($_POST[ship_address2]));
		$_SESSION[newAccountLensnet][ship_city] 	 = ucwords(addslashes($_POST[ship_city]));
		$_SESSION[newAccountLensnet][ship_state] 	 = ucwords(addslashes($_POST[ship_state]));
		$_SESSION[newAccountLensnet][ship_zip] 		 = ucwords(addslashes($_POST[ship_zip]));
		$_SESSION[newAccountLensnet][ship_country]   = ucwords(addslashes($_POST[ship_country]));	
	}


//Validate the captcha image
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
	header("Location:code_error.php");
	exit;
	}

if ($_POST[requestTest]=="yes"){
	$success=create_account_lensnet();
}

if($success==true){
	//Delete account request data since the account is now created
	unset($_SESSION[newAccountLensnet]);
	header("Location:openAccountThanks.php");
}else{
	header("Location:accountProblem.php");
}
?>