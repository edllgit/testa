<?php 
include('inc/header.php');
include("inc/pw_functions.inc.php");  
include_once("../Connections/sec_connect.inc.php"); 

//Recuperate posted data
$title 			= ucwords(addslashes($_POST[title]));
$buying_group 	= ucwords(addslashes($_POST[buying_group]));
$main_lab 		= ucwords(addslashes($_POST[main_lab]));
$user_id 		= ucwords(addslashes($_POST[user_id]));
$language		= ucwords(addslashes($_POST[language]));
$company 		= ucwords(addslashes($_POST[company]));
$first_name 	= ucwords(addslashes($_POST[first_name]));
$last_name 		= ucwords(addslashes($_POST[last_name]));
$bill_address1  = ucwords(addslashes($_POST[bill_address1]));
$bill_address2 	= ucwords(addslashes($_POST[bill_address2]));
$bill_city 		= ucwords(addslashes($_POST[bill_city]));
$bill_state 	= ucwords(addslashes($_POST[bill_state]));
$bill_country 	= ucwords(addslashes($_POST[bill_country]));
$bill_zip 		= ucwords(addslashes($_POST[bill_zip]));
$findus 		= ucwords(addslashes($_POST[findus]));
$account_num 	= ucwords(addslashes($_POST[account_num]));
$phone			= ucwords(addslashes($_POST[phone]));
$other_phone 	= ucwords(addslashes($_POST[other_phone]));
$fax 			= ucwords(addslashes($_POST[fax]));
$email 			= ucwords(addslashes($_POST[email]));
$VAT_no 		= ucwords(addslashes($_POST[VAT_no]));
$business_type  = ucwords(addslashes($_POST[business_type]));
$currency 		= ucwords(addslashes($_POST[currency]));
$password 		= ucwords(addslashes($_POST[password]));
$password_confirmation= ucwords(addslashes($_POST[password_confirmation]));
$purchase_unit	= ucwords(addslashes($_POST[purchase_unit]));

if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
	$ship_address1  = ucwords(addslashes($bill_address1));
	$ship_address2 	= ucwords(addslashes($bill_address2));
	$ship_city	 	= ucwords(addslashes($bill_city));
	$ship_state 	= ucwords(addslashes($bill_state));
	$ship_zip 	    = ucwords(addslashes($bill_zip));
	$ship_country   = ucwords(addslashes($bill_country));
}else{
	$ship_address1   = ucwords(addslashes($_POST[ship_address1]));
	$ship_address2   = ucwords(addslashes($_POST[ship_address2]));
	$ship_city		 = ucwords(addslashes($_POST[ship_city]));
	$ship_state  	 = ucwords(addslashes($_POST[ship_state]));
	$ship_zip 	 	 = ucwords(addslashes($_POST[ship_zip]));
	$ship_country    = ucwords(addslashes($_POST[ship_country]));	
}


//Validation to make sure the user id and the account num is not already in use in the DB
$queryUserId="select * from accounts where user_id = '$user_id'";/* check for the new user_id already in db */
echo $queryUserId . '<br>';
$resultUserId=mysql_query($queryUserId)		or die ("Could not execute select login query");
$NbrResult=mysql_num_rows($resultUserId);
if ($NbrResult != 0){ /* if new acct and the new user id is not unique */
	header("Location:/direct-lens/accountproblem.php?err=userid");
	exit();
}

if ($account_num <> '')
{		
	$queryAccountNum="select * from accounts where account_num = '$account_num'";/* check if the new account_num already in db */
	echo $queryAccountNum . '<br>';
	$resultAccountNum=mysql_query($queryAccountNum)		or die ("Could not execute select login query");
	$NbrResultAccount=mysql_num_rows($resultAccountNum);
	if ($NbrResultAccount != 0){ /* if new account num is not unique */
		header("Location:/direct-lens/accountproblem.php?err=accountnum");
		exit();
	}
}		

	
//Create the account
$success=create_account();

if($success==true){
	$_SESSION[newaccount] = '';
	header("Location:openaccountthanks.php");
}else{
	header("Location:accountproblem.php");
}	

include('inc/footer.php');

?>