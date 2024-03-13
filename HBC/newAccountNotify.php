<?php
session_start();
include("includes/pw_functions.inc.php");






//Save existing input in case of error in the validation code
	$_SESSION[newaccountIfc][title] = ucwords(addslashes($_POST[title]));
	$_SESSION[newaccountIfc][buying_group] = ucwords(addslashes($_POST[buying_group]));
	$_SESSION[newaccountIfc][main_lab] = ucwords(addslashes($_POST[main_lab]));
	$_SESSION[newaccountIfc][user_id] = ucwords(addslashes($_POST[user_id]));
	$_SESSION[newaccountIfc][language] = ucwords(addslashes($_POST[language]));
	$_SESSION[newaccountIfc][company] = ucwords(addslashes($_POST[company]));
	$_SESSION[newaccountIfc][first_name] =ucwords(addslashes($_POST[first_name]));
	$_SESSION[newaccountIfc][last_name] =ucwords(addslashes($_POST[last_name]));
	$_SESSION[newaccountIfc][bill_address1] =ucwords(addslashes($_POST[bill_address1]));
	$_SESSION[newaccountIfc][bill_address2] =ucwords(addslashes($_POST[bill_address2]));
	$_SESSION[newaccountIfc][bill_city] =ucwords(addslashes($_POST[bill_city]));
	$_SESSION[newaccountIfc][bill_state] =ucwords(addslashes($_POST[bill_state]));
	$_SESSION[newaccountIfc][bill_country] =ucwords(addslashes($_POST[bill_country]));
	$_SESSION[newaccountIfc][bill_zip] =ucwords(addslashes($_POST[bill_zip]));
	$_SESSION[newaccountIfc][findus] =ucwords(addslashes($_POST[findus]));
	$_SESSION[newaccountIfc][account_no] =ucwords(addslashes($_POST[account_no]));
	$_SESSION[newaccountIfc][phone] =ucwords(addslashes($_POST[phone]));
	$_SESSION[newaccountIfc][other_phone] =ucwords(addslashes($_POST[other_phone]));
	$_SESSION[newaccountIfc][fax] =ucwords(addslashes($_POST[fax]));
	$_SESSION[newaccountIfc][email] =ucwords(addslashes($_POST[email]));
	$_SESSION[newaccountIfc][VAT_no] =ucwords(addslashes($_POST[VAT_no]));
	$_SESSION[newaccountIfc][business_type] =ucwords(addslashes($_POST[business_type]));
	$_SESSION[newaccountIfc][currency] =ucwords(addslashes($_POST[currency]));
	$_SESSION[newaccountIfc][findus] =ucwords(addslashes($_POST[findus]));
	
	
	if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
		$_SESSION[newaccountIfc][ship_address1]  = ucwords(addslashes($_SESSION[newaccountIfc][bill_address1]));
		$_SESSION[newaccountIfc][ship_address2]  = ucwords(addslashes($_SESSION[newaccountIfc][bill_address2]));
		$_SESSION[newaccountIfc][ship_city] 	 = ucwords(addslashes($_SESSION[newaccountIfc][bill_city]));
		$_SESSION[newaccountIfc][ship_state] 	 = ucwords(addslashes($_SESSION[newaccountIfc][bill_state]));
		$_SESSION[newaccountIfc][ship_zip] 	 	 = ucwords(addslashes($_SESSION[newaccountIfc][bill_zip]));
		$_SESSION[newaccountIfc][ship_country]   = ucwords(addslashes($_SESSION[newaccountIfc][bill_country]));
	}else{
		$_SESSION[newaccountIfc][ship_address1]  = ucwords(addslashes($_POST[ship_address1]));
		$_SESSION[newaccountIfc][ship_address2]  = ucwords(addslashes($_POST[ship_address2]));
		$_SESSION[newaccountIfc][ship_city] 	 = ucwords(addslashes($_POST[ship_city]));
		$_SESSION[newaccountIfc][ship_state] 	 = ucwords(addslashes($_POST[ship_state]));
		$_SESSION[newaccountIfc][ship_zip] 		 = ucwords(addslashes($_POST[ship_zip]));
		$_SESSION[newaccountIfc][ship_country]   = ucwords(addslashes($_POST[ship_country]));	
	}
	


if ($_POST[requestTest]=="yes"){
	$success=create_accountIFC();
}
if($success==true)
	header("Location:openAccountThanks.php");
else
	header("Location:accountProblem.php");
?>
