<?php
require_once(__DIR__.'/../../constants/url.constant.php');

function login_to_admin(){/* check user id and password on login */
	require_once("../Connections/sec_connect.inc.php");

	$query="select username, password from labs where username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysql_query($query)
		or die ("Could not find user");
	$usercount=mysql_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysql_fetch_array($result);

	$compUser=strcmp($_POST[username_test], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
	return true;
}



function login_access(){/* check user id and password on login */
	require_once("../Connections/sec_connect.inc.php");

	$query="select username, password from access where username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysql_query($query)
		or die ("Could not find user");
	$usercount=mysql_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysql_fetch_array($result);

	$compUser=strcmp($_POST[username_test], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		print "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
	return true;
}




function edit_account($pkey)
{
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

if ($_POST[display_double_warning] != 'yes'){
$_POST[display_double_warning] = 'no';
}

	$query="update accounts set bbg_1_dsc  ='$_POST[bbg_1_dsc]', 
	 bbg_2_dsc  ='$_POST[bbg_2_dsc]', 
	 bbg_3_dsc  ='$_POST[bbg_3_dsc]', 
	 bbg_4_dsc  ='$_POST[bbg_4_dsc]', 
	 bbg_5_dsc  ='$_POST[bbg_5_dsc]', 
	 bbg_6_dsc  ='$_POST[bbg_6_dsc]', 
	 bbg_7_dsc  ='$_POST[bbg_7_dsc]', 
	 bbg_8_dsc  ='$_POST[bbg_8_dsc]', 
	 bbg_9_dsc  ='$_POST[bbg_9_dsc]', 
	 bbg_10_dsc  ='$_POST[bbg_10_dsc]', 
	 bbg_11_dsc  ='$_POST[bbg_11_dsc]', 
	 bbg_12_dsc  ='$_POST[bbg_12_dsc]', 
	 svision_dsc  ='$_POST[svision_dsc]', 
	 svision_2_dsc  ='$_POST[svision_2_dsc]', 
	 nesp_dsc  ='$_POST[nesp_dsc]', 
	 conant_dsc  ='$_POST[conant_dsc]', 
	 optovision_dsc  ='$_POST[optovision_dsc]', 
	private_6_dsc ='$_POST[private_6_dsc]', private_7_dsc ='$_POST[private_7_dsc]',  innovation_ff_hd_dsc = '$_POST[innovation_ff_hd_dsc]' , innovation_ii_ds_dsc = '$_POST[innovation_ii_ds_dsc]' ,innovation_ds_dsc = '$_POST[innovation_ds_dsc]',innovation_ff_dsc = '$_POST[innovation_ff_dsc]',coupon_code = '$_POST[coupon_code]' , offer = '$_POST[offer]', account_rebate = '$_POST[account_rebate]',  language = '$_POST[language]',  email_notification = '$_POST[email_notification]', title='$_POST[title]', first_name='$_POST[first_name]', last_name='$_POST[last_name]', company='$_POST[company]', business_type='$_POST[business_type]', buying_group='$_POST[buying_group]', VAT_no='$_POST[VAT_no]', bill_address1='$_POST[bill_address1]', bill_address2='$_POST[bill_address2]', bill_city='$_POST[bill_city]', bill_state='$_POST[bill_state]', bill_zip='$_POST[bill_zip]', bill_country='$_POST[bill_country]', ship_address1='$_POST[ship_address1]', ship_address2='$_POST[ship_address2]', ship_city='$_POST[ship_city]', ship_state='$_POST[ship_state]', ship_zip='$_POST[ship_zip]', ship_country='$_POST[ship_country]', phone='$_POST[phone]', other_phone='$_POST[other_phone]', fax='$_POST[fax]', email='$_POST[email]', password='$_POST[password]', approved='$_POST[approved]', currency='$_POST[currency]', purchase_unit='$_POST[purchase_unit]', innovative_dsc='$_POST[innovative_dsc]', infocus_dsc='$_POST[infocus_dsc]', precision_dsc='$_POST[precision_dsc]', visionpro_dsc='$_POST[visionpro_dsc]', visionpropoly_dsc='$_POST[visionpropoly_dsc]', visioneco_dsc='$_POST[visioneco_dsc]', generation_dsc='$_POST[generation_dsc]', truehd_dsc='$_POST[truehd_dsc]', easy_fit_dsc='$_POST[easy_fit_dsc]', private_1_dsc='$_POST[private_1_dsc]', private_2_dsc='$_POST[private_2_dsc]', private_3_dsc='$_POST[private_3_dsc]', private_4_dsc='$_POST[private_4_dsc]', private_5_dsc='$_POST[private_5_dsc]', vot_dsc='$_POST[vot_dsc]',glass_dsc='$_POST[glass_dsc]',glass_2_dsc='$_POST[glass_2_dsc]', glass_3_dsc='$_POST[glass_3_dsc]', eco_dsc='$_POST[eco_dsc]', rodenstock_dsc='$_POST[rodenstock_dsc]', rodenstock_hd_dsc='$_POST[rodenstock_hd_dsc]',credit_hold='$_POST[credit_hold]',display_double_warning='$_POST[display_double_warning]',account_type='$_POST[account_type]',sales_rep='$_POST[mysalesrep]',sales_commission='$_POST[mycommission]' where primary_key = '$pkey'";
	$result=mysql_query($query)
			or die  ('I cannot update account because: ' . mysql_error());
		
//credit limit check
	$today = date("Y-m-d");
	$limitQuery="SELECT cl_user_id, cl_limit_amt from acct_credit_limit WHERE cl_user_id = '$_POST[user_id]'";
	$limitResult=mysql_query($limitQuery)
		or die ("Could not find credit limit because ".mysql_error());
	$limitcount=mysql_num_rows($limitResult);
	
	// limit exists and has been reset to 0 or empty, so delete it from the table
	if (($limitcount > 0) && ((!$_POST["cl_limit_amt"]) || ($_POST["cl_limit_amt"] == 0))) {
		$limitQuery="DELETE from acct_credit_limit where cl_user_id = '$_POST[user_id]'";
		$limitResult=mysql_query($limitQuery)
			or die ("Could not delete credit limit because ".mysql_error());
	}
	// limit exists and has been set to valid amount, check amount to see if date should be updated
	elseif (($limitcount > 0) && ($_POST["cl_limit_amt"] > 0)) {
		$limitData = mysql_fetch_assoc($limitResult);
		if ($limitData["cl_limit_amt"] != $_POST["cl_limit_amt"]){
			$limitQuery="UPDATE acct_credit_limit set cl_limit_amt='$_POST[cl_limit_amt]', cl_date='$today' where cl_user_id = '$_POST[user_id]'";
			$limitResult=mysql_query($limitQuery)
				or die ("Could not update credit limit because ".mysql_error());
		}
	}
	// limit does not exist and has been set to valid amount, so add it to the table
	elseif (($limitcount == 0) && ($_POST["cl_limit_amt"] > 0)) {
		$limitQuery="INSERT into acct_credit_limit (cl_user_id, cl_limit_amt, cl_date) values ('$_POST[user_id]', '$_POST[cl_limit_amt]', '$today')";
		$limitResult=mysql_query($limitQuery)
			or die ("Could not add credit limit because ".mysql_error());
	}
	
	if(($_POST[approved]=="approved")&&($_POST[notifyApproved]=="pending")){ /* check for initial admin approval of account and if so, send email */
		
		//Verify if account is a lens net account 
		$queryAccountType = "SELECT product_line, main_lab from accounts where primary_key = '$pkey'" ;
		$resultAcctType   = mysql_query($queryAccountType) or die(mysql_error());		
		$DataAcctType = mysql_fetch_assoc($resultAcctType);
		$Product_line = $DataAcctType['product_line'];
		$mainLab = $DataAcctType['main_lab'];
		//echo $Product_line;
				
		
		sendEmail();
		sendEmailMarjorie();
		
		
		
		//If the account is a lens net account, we activate the lens net club collections
		
		switch($mainLab) {
		
		case 28://Lens net QC
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
	   
   		case 33:// Lens net atlantic
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
      
    	case 34://Lens net West
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 31://Lens net Elite
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 29://Lens net Ontario
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;

		}
		
		
		
		
			if ($Product_line == "lensnetclub")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
		
			
			//Then we activate lens net collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','32')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','34')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','35')";
			$rs = mysql_query($query_rs) or die(mysql_error());
	
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','36')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','37')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','38')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','58')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','60')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','61')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','62')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','63')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','66')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','67')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','69')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			}//End IF account = LENS NET CLUB
			
			
			
			  if ($Product_line == "directlens"){
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
			
		   //Then we activate Direct-Lens collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','1')"; // Easy Fit HD
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','4')"; //Precision
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','14')"; //My World
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','5')"; //TrueHD
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','21')"; //Private 3 = Easy One
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','29')"; //Rodenstock
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','30')"; //Rodenstock HD
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','6')"; //Vision Pro
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
		   
		   }
		   
			
			
			
			
			if ($Product_line == "mybbgclub")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
					
			//Then we activate Bbg collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','76')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','77')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','78')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','79')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','80')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','81')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','82')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','83')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','84')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','85')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','86')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','87')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			}//End IF account = My Bbg Club
			
		
	}
	return ($pkey);
}

function sendEmail(){/* sends the emails */
	$message="Thank you for opening an account with direct-lens.com. Your account has been approved. For your records, your login is $_POST[user_id]. Your password is $_POST[password]. Please keep this information in a safe place. We look forward to serving you.\r\n";
	$headers = "From: info@direct-lens.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("$_POST[email]", "Your Account Information", "$message", "$headers");
}

function sendEmailMarjorie(){/* sends the emails */
/*$QueryMainlab = "Select lab_name from labs where primary_key = (Select main_lab from accounts where user_id = '".$_POST[user_id] . "')";
$rs = mysql_query($QueryMainlab) or die(mysql_error());
$DataMainlab=mysql_fetch_array($rs);
$lab_Name = $DataMainlab['lab_name'];
 
	$message="Nouveau compte. Compagnie: $_POST[company]   Main lab: $lab_Name\r\n";
	$headers = "From: info@direct-lens.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("dbeaulieu@direct-lens.com", "Your Account Information", "$message", "$headers");*/
}


function update_discounts($user_id)
{
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
		$price_key=explode("_", $x);//get primary key for prices table
		if($price_key[0]=="key"){
			$query="select product_name from prices where primary_key = '$price_key[1]'";//find product_name from prices table based on primary key
			$result=mysql_query($query)
				or die ("Could not find product name");
			$prodData=mysql_fetch_array($result);
			$query="select user_id from stock_discounts where user_id = '$user_id' AND product_name='$prodData[product_name]'";//see if stock discount exists for this product name and user id
			$result=mysql_query($query)
				or die ("Could not find stock discount");
			$dscCount=mysql_num_rows($result);
		
			if($dscCount==0)
				$query="insert into stock_discounts (user_id, product_name, discount) values ('$user_id', '$prodData[product_name]', '$_POST[$x]')";
			else
				$query="update stock_discounts set discount='$_POST[$x]' where user_id = '$user_id' AND product_name = '$prodData[product_name]'";

		$result=mysql_query($query)
			or die ("Could not update stock discount");
		}
	}
	return true;
}

function update_order_status()
{
	require_once("../Connections/sec_connect.inc.php");
	$today=date('Y-m-d');
	foreach($_POST as $x => $y){
		$query="update orders set order_status='filled',  order_date_shipped ='$today' where order_num = '$y'";
		$result=mysql_query($query)
			or die ("Could not update order_status");
	}
}

function make_the_pmt()
{
	$result=mysql_query("SELECT curdate()");/* get today's date */
		$today=mysql_result($result,0,0);
	$pmt_type="check";
	$_SESSION["CHECK_NO"]=$_POST[check_no];
		
	if($_POST[cc_no]){//if this is a credit card payment
		require_once("../Connections/sec_connect.inc.php");
		require_once "../../usaepay.php";
		$pmt_type="credit card";
		$query="SELECT * from accounts WHERE user_id = '$_SESSION[user_id]'";//find the account data
		$result=mysql_query($query)
			or die  ('I cannot select items because: ' . mysql_error());
		$acctData=mysql_fetch_array($result);

// USA ePay PHP Library.
//      v1.5
//
//      Copyright (c) 2002-2007 USA ePay
//      Written by Tim McEwen (dbeaulieu@direct-lens.com)
//
//  The following is an example of running a transaction using the php library.
//  Please see the README file for more information on usage.
//


		$strippers=array("$", ",", ".", " ", "-", "_", "/");
		$cents_sep=substr($_SESSION["grandTotal"], -3, 1);
		if(!ctype_digit($cents_sep)){ /* if total is NOT all numbers */
			$amount=str_replace($cents_sep, "x", $_SESSION["grandTotal"]);
			$amount=str_replace($strippers, "", $amount);
			$amount=str_replace("x", ".", $amount);
		}else{ /* if total is all numbers */
			$amount=$_SESSION["grandTotal"] . ".00";
		}

		$cust_name = $acctData[first_name] . " " . $acctData[last_name];
		$cc_no=str_replace($strippers, "", $_POST[cc_no]);
		$exp_date = $_POST[cc_month] . $_POST[cc_year];

		$tran=new umTransaction;

		$tran->key=$usa_key;
		$tran->testmode=false;/* CHANGE TO TRUE FOR TESTING, FALSE FOR LIVE */
		$tran->card=$cc_no;		// card number, no dashes, no spaces
		$tran->exp=$exp_date;			// expiration date 4 digits no /
		$tran->amount=$amount;			// charge amount in dollars (no international support yet)
		$tran->invoice="Statement Payment";   		// invoice number.  must be unique.
		$tran->cardholder=$cust_name; 	// name of card holder
		$tran->street=$acctData[bill_address1];	// street address
		$tran->zip=$acctData[bill_zip];			// zip code
		$tran->description="Online Payment";	// description of charge
		$tran->cvv2=$_POST[cvv];			// cvv2 code	
		
		if($tran->Process()){

			$resultcode=($tran->resultcode);
			$authcode=($tran->authcode);
			$cclast4=substr($cc_no, -4, 4);
			$_SESSION["CCLAST4"]=$cclast4;

		} else {
			$pmtMessage = "There was a problem with the credit card payment. Please try again.";
//	echo "<b>Card Declined</b> (" . $tran->result . ")<br>";
//	echo "<b>Reason:</b> " . $tran->error . "<br>";	
//	if($tran->curlerror) echo "<b>Curl Error:</b> " . $tran->curlerror . "<br>";	
			return($pmtMessage);
			exit();
		}		
	}	

	if($_SESSION["order_numbers"]){//current statement orders that are being paid
		$order_numbers=$_SESSION["order_numbers"];
		$order_amts=$_SESSION["order_amts"];
		$order_totals=$_SESSION["order_totals"];
		$orderCount=$_SESSION["orderCount"];
		$orderPaid=$_SESSION["order_paid"];
		for ($i = 1; $i <= $orderCount; $i++){//get order data
			$order_num=$order_numbers[$i];
			$totalCost=$order_amts[$i];
			$order_paid_in_full=$orderPaid[$i];
			$pmtQuery="SELECT * from payments WHERE order_num = '$order_num'";//find this order if it's been paid
			$pmtResult=mysql_query($pmtQuery)
				or die  ('I cannot select payments because: ' . mysql_error());
			$orderTest=mysql_num_rows($pmtResult);
			$order_balance=bcsub($order_totals[$i], $order_amts[$i], 2);//figure the ending balance
			if($order_balance < .01){
				$order_paid_in_full="y";
			}
			if($orderTest==0){//if customer never tried to pay this order before
				$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
			}else{ //if customer tried to pay this order before and failed OR only a partial payment was made
				$pmtData=mysql_fetch_assoc($pmtResult);//retrieve previous payment data
				$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
			}
			$result=mysql_query($query)
				or die ('Could not add or update current payments because: ' . mysql_error());
		}
	}
	if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous unpaid orders
		reset($_SESSION["ORDERSBALDATA"]);
		$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
		$ordersBalData=$_SESSION["ORDERSBALDATA"];
		for ($i = 1; $i <= $count_prev_orders; $i++){//get order data
			if($ordersBalData[$i]["balance"] > 0){
				$order_num=$ordersBalData[$i]["order_num"];
				$totalCost=$ordersBalData[$i]["balance"];
				$order_paid_in_full="y";
				$pmtQuery="SELECT * from payments WHERE order_num = '$order_num'";//find this order if it's been paid
				$pmtResult=mysql_query($pmtQuery)
					or die  ('I cannot select items because: ' . mysql_error());
				$orderTest=mysql_num_rows($pmtResult);
				$order_balance=0;//previous orders must be paid in full
				if($orderTest==0){//if customer never tried to pay this order before
					$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
				}else{ //if customer tried to pay this order before and failed
					$pmtData=mysql_fetch_assoc($pmtResult);//retrieve previous payment data
					$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
				}
				$result=mysql_query($query)
					or die ('Could not update previous balance payments because: ' . mysql_error());
			}
		}
	}
	if($_SESSION["MEMOCREDLIST"]){//there are memo credits that should be marked as used
		reset($_SESSION["MEMOCREDLIST"]);
		$count_memo_creds=count($_SESSION["MEMOCREDLIST"]);
		$memoCredList=$_SESSION["MEMOCREDLIST"];
		for ($i = 1; $i <= $count_memo_creds; $i++){//update memo cred data
			$query="UPDATE memo_credits SET date_mc_applied='$today' WHERE mcred_primary_key='$memoCredList[$i]'";
			$result=mysql_query($query)
				or die ('Could not update memo credit because: ' . mysql_error());
		}
	}
	
	if($_SESSION["STMTCREDLIST"]){//there are statement credits that should be marked as used
		reset($_SESSION["STMTCREDLIST"]);
		$count_stmt_creds=count($_SESSION["STMTCREDLIST"]);
		$stmtCredList=$_SESSION["STMTCREDLIST"];
		for ($i = 1; $i <= $count_stmt_creds; $i++){//update statement cred data
			$query="UPDATE statement_credits SET date_sc_applied='$today' WHERE primary_key_cr='$stmtCredList[$i]'";
			$result=mysql_query($query)
				or die ('Could not update statement credit because: ' . mysql_error());
		}
	}
$pmtMessage = "Payment has been successfully submitted.";
return($pmtMessage);
}


function create_stmt_credit($acct_user_id){
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

//	$query="SELECT * from statement_credits WHERE acct_user_id = '$acct_user_id' AND  stmt_month = '$_POST[stmt_month]' AND  stmt_year = '$_POST[stmt_year]' AND credit_option = '$_POST[credit_option]'";//find this credit if it already exists
//	$result=mysql_query($query)
//		or die  ('I cannot select credit because: ' . mysql_error());
//	$creditTest=mysql_num_rows($result);
//	if($creditTest!=0)
//		return false;
	$query="INSERT into statement_credits (acct_user_id, stmt_month, stmt_year, amount, credit_option, cr_description) VALUES ('$acct_user_id', '$_POST[stmt_month]', '$_POST[stmt_year]', '$_POST[amount]', '$_POST[credit_option]', '$_POST[cr_description]')";
	$result=mysql_query($query)
		or die ('Could not add credit because: ' . mysql_error());
	return true;
}

function delete_stmt_credit($credit_key){
	require_once("../Connections/sec_connect.inc.php");

	$query="DELETE from statement_credits where primary_key_cr = '$credit_key'"; /* delete statement credit */
	$result=mysql_query($query)
		or die ('Could not delete credit because: ' . mysql_error());
	return true;
}

function makeAdminPmt($user_id){
	require('../Connections/sec_connect.inc.php');
	require_once "../../usaepay.php";
	if($_POST[pmt_type]=="check"){
		$query="SELECT * from payments WHERE order_num = '$_POST[order_num]' AND user_id = '$user_id' AND pmt_marker = 'pending'";//find user's pending payments
		$result=mysql_query($query)
			or die  ('I cannot select items because: ' . mysql_error());
		$orderCount=mysql_num_rows($result);
		if($orderCount==0)//if customer never tried to pay this order before (no payment markers found)
			$query="insert into payments (user_id, order_num, pmt_date, pmt_type, check_num, pmt_amount) values ('$user_id', '$_POST[order_num]', '$_POST[today]', 'check', $_POST[check_num], '$_POST[total_cost]')";
		else //if customer tried to pay this order before and failed
			$query="UPDATE payments SET pmt_marker='', pmt_date='$_POST[today]', pmt_type='check', check_num='$_POST[check_num]', pmt_amount='$_POST[total_cost]' WHERE order_num='$_POST[order_num]'";
		$result=mysql_query($query)
			or die ('Could not update because: ' . mysql_error());
	}else{

		// USA ePay PHP Library.
		//      v1.5
		//
		//      Copyright (c) 2002-2007 USA ePay
		//      Written by Tim McEwen (dbeaulieu@direct-lens.com)
		//
		//  The following is an example of running a transaction using the php library.
		//  Please see the README file for more information on usage.
		//


		$strippers=array("$", ",", ".", " ", "-", "_", "/");
		$cents_sep=substr($_POST[total_cost], -3, 1);
		if(!ctype_digit($cents_sep)){ /* if total is NOT all numbers */
			$amount=str_replace($cents_sep, "x", $_POST[total_cost]);
			$amount=str_replace($strippers, "", $amount);
			$amount=str_replace("x", ".", $amount);
		}else{ /* if total is all numbers */
			$amount=$_POST[total_cost] . ".00";
		}

		$cust_name = $_POST[first_name] . " " . $_POST[last_name];
		$cc_no=str_replace($strippers, "", $_POST[cc_no]);
		$exp_date = $_POST[cc_month] . $_POST[cc_year];

		$tran=new umTransaction;

		$tran->key=$usa_key;
		$tran->testmode=false;/* CHANGE TO TRUE FOR TESTING, FALSE FOR LIVE */
		$tran->card=$cc_no;		// card number, no dashes, no spaces
		$tran->exp=$exp_date;			// expiration date 4 digits no /
		$tran->amount=$amount;			// charge amount in dollars (no international support yet)
		$tran->invoice=$_POST[order_num];   		// invoice number.  must be unique.
		$tran->cardholder=$cust_name; 	// name of card holder
		$tran->street=$_POST[address1];	// street address
		$tran->zip=$_POST[zip];			// zip code
		$tran->description="Online Payment";	// description of charge
		$tran->cvv2=$_POST[cvv];			// cvv2 code	

		if($tran->Process()){//process the credit card

			$result=mysql_query("SELECT curdate()");/* get today's date */
			$today=mysql_result($result,0,0);
			$order_num=$_POST[order_num];
			$resultcode=($tran->resultcode);
			$authcode=($tran->authcode);
			$cclast4=substr($cc_no, -4, 4);
	
			if($_SESSION["order_numbers"]){//enter payments for order(s) placed at checkout
				$order_numbers=$_SESSION["order_numbers"];
				$orderCount=$_SESSION["orderCount"];
				for ($i = 1; $i <= $orderCount; $i++){//get order data
					$order_num=$order_numbers[$i][order_num];
					$shipCost=$order_numbers[$i][order_shipping_cost];
					$subAmount=$order_numbers[$i][order_total];
					if($_POST[pass_disc]!="")
						$discAmount=bcmul($_POST[pass_disc], $subAmount, 2);
					$subAmount2 = bcsub($subAmount, $discAmount, 2);
					$amount=bcadd($subAmount2, $shipCost, 2);
					$query="UPDATE payments SET pmt_marker='', pmt_amount='$amount', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
					$result=mysql_query($query)
						or die ('Could not update because: ' . mysql_error());
				}
			}else{//enter payments from Order History
				$query="SELECT * from payments WHERE order_num = '$_POST[order_num]' AND user_id = '$user_id' AND pmt_marker = 'pending'";//find user's pending payments
				$result=mysql_query($query)
					or die  ('I cannot select items because: ' . mysql_error());
				$orderCount=mysql_num_rows($result);
				if($orderCount==0)//if customer never tried to pay this order before (no payment markers found)
					$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode) values ('$_POST[user_id]', '$_POST[order_num]', '$today', 'credit card', '$amount', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
				else //if customer tried to pay this order before and failed
					$query="UPDATE payments SET pmt_marker='', pmt_amount='$amount', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
				$result=mysql_query($query)
						or die ('Could not update because: ' . mysql_error());
			}
		}
	}
	return ($_POST[order_num]);
}

//Reason Codes
function create_memo_code($lab_pkey){
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="SELECT * from memo_codes WHERE mc_lab = '$lab_pkey' AND  memo_code = '$_POST[memo_code]'";//find this memo code if it already exists
	$result=mysql_query($query)
		or die  ('I cannot select memo code because: ' . mysql_error());
	$codeTest=mysql_num_rows($result);
	if($codeTest!=0)
		return false;
	$query="INSERT into memo_codes (memo_code, mc_description, mc_lab) VALUES ('$_POST[memo_code]', '$_POST[mc_description]', '$lab_pkey')";
	$result=mysql_query($query)
		or die ('Could not add memo code because: ' . mysql_error());
	return true;
}

//Reason Codes
function edit_memo_code($lab_pkey, $mc_pkey){
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="SELECT * from memo_codes WHERE mc_lab = '$lab_pkey' AND  memo_code = '$_POST[memo_code]' AND mc_primary_key != '$mc_pkey'";//find this memo code if it already exists
	$result=mysql_query($query)
		or die  ('I cannot select memo code because: ' . mysql_error());
	$codeTest=mysql_num_rows($result);
	if($codeTest!=0)
		return false;
	$query="UPDATE memo_codes SET memo_code='$_POST[memo_code]', mc_description='$_POST[mc_description]' WHERE mc_primary_key = '$mc_pkey'";
	$result=mysql_query($query)
		or die ('Could not edit memo code because: ' . mysql_error());
	return true;
}

function issue_memo_credit($mcred_abs_amount){
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	$query="SELECT * from memo_credits WHERE mcred_memo_num = '$_POST[lastMemoNum]'";//find this memo credit if it already exists
	$result=mysql_query($query)		or die  ('I cannot select memo credit because: ' . mysql_error());
	$creditTest=mysql_num_rows($result);
	if($creditTest!=0)
		return false;
	$timestamp=strtotime($_POST[mcred_date]);
	$mcred_date=date("Y-m-d", $timestamp);

	$query="INSERT into memo_credits_temp (mcred_order_num, mcred_acct_user_id, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date, pat_ref_num, patient_first_name, patient_last_name) VALUES ('$_POST[order_num]', '$_POST[acct_user_id]', '$_POST[lastMemoNum]', '$_POST[mcred_cred_type]', '$_POST[mcred_disc_type]', '$_POST[mcred_amount]', '$mcred_abs_amount', '$_POST[mcred_memo_code]', '$mcred_date', '$_POST[patient_ref_num]', '$_POST[order_patient_first]', '$_POST[order_patient_last]')";
	$result=mysql_query($query)		or die ('Could not issue memo because: ' . mysql_error());
	
	//Email to warn about the memo credit that needs to be approved or refused					
$message='Une nouvelle demande de crédit a été faite. Veuillez vous connecter dans le <b> Main admin</b> pour approuver ou refuser cette demande dans les pluf brefs délais.<br><br>Merci !';

		
		return true;
}

function email_memo_credit(){//Email memo credit to customer

	$headers = "From:".$_SESSION[labAdminData][lab_email]."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = "Direct Lens Memo Order for Order Number: $_POST[order_num]";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MEMO ORDER</title>';
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" /></head><body>";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="'.constant('DIRECT_LENS_URL').'/logos/'.$_SESSION[labAdminData][logo_file].'"/></td><td align="center"><img src="'.constant('DIRECT_LENS_URL').'/logos/direct-lens_logo.gif" width="200" height="60" /></td></tr></table>';
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Memo Credit for your Direct-Lens Order #:'.$_POST[order_num].'</div></td></tr></table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">MEMO ORDER INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Memo Order Date:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[mcred_date];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Order Number:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[order_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Order Total:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[mcred_order_total];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Customer Name: </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[company];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Customer Account: </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides" nowrap>Patient Reference Number: </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[patient_ref_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides" nowrap>Patient First Name: </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[order_patient_first];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides" nowrap>Patient Last Name: </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[order_patient_last];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Memo Order Number:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[mcred_memo_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Memo Order Value:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[mcred_abs_amount];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Reason Code:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$_POST[mcred_memo_code];
	$message.=' - ';
	$message.=$_POST[mc_description];
	$message.='</strong></td></tr></table>';
	
			
	$message.="</body></html>";

	$msgSent = mail("$_POST[customer_email]", "$subject", "$message", "$headers");
//	$msgSent = mail("dbeaulieu@direct-lens.com", "$subject", "$message", "$headers");
	if($msgSent)
		return true;
}

function email_report($outputstring, $email) { 
	$filename = "sales_rpt.csv"; 
	$path = "reports/";
	$to = $email;
	//$to = "dbeaulieu@direct-lens.com";
	$from_name = "reports@direct-lens.com"; 
	$from_mail = "reports@direct-lens.com"; 
	$subject = "Direct-Lens.com Sales Report"; 
	$message = "Direct-Lens.com Sales Report"; 
    $file = $path.$filename; 

	$fp=fopen($file, "w");
	fwrite($fp, $outputstring);
	fclose($fp);

    $file_size = filesize($file); 
    $handle = fopen($file, "r"); 
    $content = fread($handle, $file_size); 
    fclose($handle); 
    $content = chunk_split(base64_encode($content)); 
    $uid = md5(uniqid(time())); 
    $header = "From: ".$from_name." <".$from_mail.">\r\n"; 
    $header .= "MIME-Version: 1.0\r\n"; 
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n"; 
    $header .= "This is a multi-part message in MIME format.\r\n"; 
    $header .= "--".$uid."\r\n"; 
    $header .= "Content-type: text/plain; charset=UTF-8\r\n"; 
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n"; 
    $header .= $message."\r\n\r\n"; 
    $header .= "--".$uid."\r\n"; 
    $header .= "Content-Type: application/vnd.ms-excel; name=\"".$filename."\"\r\n"; // use diff. tyoes here 
    $header .= "Content-Transfer-Encoding: base64\r\n"; 
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n"; 
    $header .= $content."\r\n\r\n"; 
    $header .= "--".$uid."--"; 
    if (mail($to, $subject, "", $header)) { 
		$rptMailed = " This report successfully emailed.";
		unlink ($file);
    } else { 
		$rptMailed = " ERROR: Report not sent.";
		unlink ($file);
    } 
	return ($rptMailed);
} 

?>
