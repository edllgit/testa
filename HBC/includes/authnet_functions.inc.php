<?php
function getUniqueId(){
	
	$flag=false;
	
	while ($flag==false):
	
		$number=rand(970000000,979999999);
		$sql="SELECT * FROM contacts WHERE uniqueid='$number'";
	
		$result=mysql_query($sql)
			or die ('I cannot select items because: ' . mysql_error());
		$usercount=mysql_num_rows($result);
		if ($usercount==0){
			$flag=true;
		}

	endwhile;
	
	return $number;
	
}

function genRandomString() {
	$length=rand(6,10);
    $characters = "01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $string ="";    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function billCard($formVars1, $formVars2,$unique_transID){ /* authorize.net gateway */
	include("../authnet_connect.inc.php");

	### $auth_net_url				= "https://test.authorize.net/gateway/transact.dll";
	#  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
	$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
	$exp_date = $formVars2["creditcard"]["month"].$formVars2["creditcard"]["year"];
	$amount = money_format('%.2n', $formVars2["product"]["initial"]);
	$name = explode(" ", $formVars2["creditcard"]["name_on_account"]);
	$firstname = $name[0];
	if($name[2])
		$lastname = $name[2];
	else
		$lastname = $name[1];

	$authnet_values				= array
	(
	"x_test_request"		=> "FALSE",
	"x_login"				=> $auth_net_login_id,
	"x_version"				=> "3.1",
	"x_delim_char"			=> "|",
	"x_delim_data"			=> "TRUE",
	"x_type"				=> "AUTH_CAPTURE",
	"x_method"				=> "CC",
 	"x_tran_key"			=> $auth_net_tran_key,
 	"x_relay_response"		=> "FALSE",
	"x_cust_id"				=> $unique_transID,
	"x_card_num"			=> $formVars2["creditcard"]["cc_number"],
	"x_exp_date"			=> $exp_date,
	"x_card_code"			=> $formVars2["creditcard"]["entercvv"],
	"x_amount"				=> $amount,
	"x_first_name"			=> $firstname,
	"x_last_name"			=> $lastname,
	"x_address"				=> $formVars2["billingaddress"]["street1"],
	"x_city"				=> $formVars2["billingaddress"]["city"],
	"x_state"				=> $formVars2["billingaddress"]["state"],
	"x_zip"					=> $formVars2["billingaddress"]["zip"],
	"x_country"				=> "US",
	"x_phone"				=> $formVars1["phone"]["primaryphone"],
	"x_email"				=> $formVars1["contact"]["email"],
	"x_invoice_num"			=> $unique_transID,
	"x_email_customer"		=> "FALSE",
	"x_description"			=> $formVars2["product"]["short"]
	);

	$fields = "";
	foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

	### $ch = curl_init("https://test.authorize.net/gateway/transact.dll"); 
	###  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
	$ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); 
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);
	$transData=explode("|", $resp);
	return ($transData);
}
function billCardUpgrade($userData, $formVars,$unique_transID){ /* authorize.net gateway */
	include("../authnet_connect.inc.php");

	//$auth_net_url				= "https://test.authorize.net/gateway/transact.dll";
	#  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
	$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
	$exp_date = $formVars["creditcard"]["month"].$formVars["creditcard"]["year"];
	$amount = "199.00";
	$name = explode(" ", $formVars["creditcard"]["name_on_account"]);
	$firstname = $name[0];
	if($name[2])
		$lastname = $name[2];
	else
		$lastname = $name[1];

	$authnet_values				= array
	(
	"x_test_request"		=> "FALSE",
	"x_login"				=> $auth_net_login_id,
	"x_version"				=> "3.1",
	"x_delim_char"			=> "|",
	"x_delim_data"			=> "TRUE",
	"x_type"				=> "AUTH_CAPTURE",
	"x_method"				=> "CC",
 	"x_tran_key"			=> $auth_net_tran_key,
 	"x_relay_response"		=> "FALSE",
	"x_cust_id"				=> $unique_transID,
	"x_card_num"			=> $formVars["creditcard"]["cc_number"],
	"x_exp_date"			=> $exp_date,
	"x_card_code"			=> $formVars["creditcard"]["entercvv"],
	"x_amount"				=> $amount,
	"x_first_name"			=> $firstname,
	"x_last_name"			=> $lastname,
	"x_address"				=> $formVars["billingaddress"]["street1"],
	"x_city"				=> $formVars["billingaddress"]["city"],
	"x_state"				=> $formVars["billingaddress"]["state"],
	"x_zip"					=> $formVars["billingaddress"]["zip"],
	"x_country"				=> "US",
	"x_phone"				=> $userData["number"],
	"x_email"				=> $userData["email"],
	"x_invoice_num"			=> $unique_transID,
	"x_email_customer"		=> "FALSE",
	"x_description"			=> "Travel and Leisure PLUS Upgrade"
	);

	$fields = "";
	foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

	//$ch = curl_init("https://test.authorize.net/gateway/transact.dll"); 
	###  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
	$ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); 
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);
	$transData=explode("|", $resp);
			
	return ($transData);
}
?>
