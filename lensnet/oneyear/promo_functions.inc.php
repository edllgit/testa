<?php
function login_to_dl($user_test, $password_test){/* check user_id and password on login */
	include_once("../../Connections/sec_connect.inc.php");
	$query="select * from accounts where product_line = 'lensnetclub' and user_id = '$user_test' and password = '$password_test'";
	$result=mysql_query($query)
		or die ("Could not find account");
	$usercount=mysql_num_rows($result);
	if ($usercount == 0)/* user id and/or password are not valid */
	{
		header("Location:login_promo.php");
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$sessionUserData=mysql_fetch_array($result);	
	
	$date1 = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$ajd = date("Y/m/d", $date1);
	//We save the connexion as this customer last login
	$QueryLastLogin = "Update accounts set last_connexion = '$ajd' where user_id = '$user_test'";
	$resultLastLogin=mysql_query($QueryLastLogin)		or die ("Could not find account");

	$compPW=strcmp($password_test, $sessionUserData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compPW != 0){
		header("Location:loginfail.php");
		exit();
		}
	if ($sessionUserData[approved] != "approved"){
		header("Location:loginpending.php");
		exit();
		}
	if($sessionUserData[buying_group]!=""){
		$query="select bg_name, global_dsc from buying_groups where primary_key = '$sessionUserData[buying_group]'";
		$result=mysql_query($query)
			or die ("Could not find bg data");
		$bgData=mysql_fetch_array($result);
		$sessionUserData[bg_name]=$bgData[bg_name];
		$sessionUserData[global_dsc]=$bgData[global_dsc];
		}
	}
	return($sessionUserData);
}

function sendEmail($company, $promo, $email, $payment_Type){/* sends the emails */
	$message="A new promo One Year has been sold. \r\n\r\n";
	$message.="NEW Lens Net Club Promo One Year\r\n\r\n";
	$message.="Company: $company \r\n\r\n";
	$message.="Promo: $promo\r\n\r\n";
	$message.="Payment type: $payment_Type \r\n\r\n";
	$headers = "From: info@lensnetclub.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("$email", "New Lens Net Club Promo Sold", "$message", "$headers");
}


function ActivateLensnetCollections($user_id){
	
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = ".$user_id."";
			$listCol = mysql_query($queryCol) or die(mysql_error());
		
			
			//Then we activate lens net collections
			
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','32')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','34')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','35')";
			$rs = mysql_query($query_rs) or die(mysql_error());
	
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','36')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','37')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','38')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','58')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','60')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','61')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','62')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','63')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','66')";
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','67')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','69')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$user_id."','100')";
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			
}



function create_account_promo_one_year()
{
include_once("../../Connections/sec_connect.inc.php");

	$addData[user_id]=addslashes($_POST[user_id]);
		$query="select * from accounts where user_id = '$addData[user_id]'";/* check for the new user_id already in db */
		$result=mysql_query($query)
			or die ("Could not execute select login query");
		$user_idtest=mysql_num_rows($result);
		if ($user_idtest != 0){ /* if new acct and the new user id is not unique */
			return false;
		}

	$result=mysql_query("SHOW TABLE STATUS FROM $mysql_db LIKE 'accounts'");
	$array=mysql_fetch_array($result);
	$next_key=$array[Auto_increment]; /* get next primary key from accounts table for account number */
	
	$date1 = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$datecomplete = date("Y/m/d", $date1);

	$addData[company]=ucwords(addslashes($_POST[company]));
	$addData[first_name]=ucwords(addslashes($_POST[first_name]));
	$addData[last_name]=ucwords(addslashes($_POST[last_name]));
	$addData[bill_address1]=ucwords(addslashes($_POST[bill_address1]));
	$addData[bill_address2]=ucwords(addslashes($_POST[bill_address2]));
	$addData[bill_city]=ucwords(addslashes($_POST[bill_city]));
	$addData[bill_state]=ucwords(addslashes($_POST[bill_state]));
	$addData[bill_zip]=addslashes($_POST[bill_zip]);
	$addData[findus]=addslashes($_POST[findus]);
	$addData[privilege] =addslashes($_POST[privilege]);
	$addData[account_no]=addslashes($_POST[account_no]);
	$addData[member_since]=$datecomplete;
	
	if ($addData[account_no] == ''){
	$addData[account_num] = $next_key;
	}else {
	$addData[account_num] = $addData[account_no];
	}
	
	 switch ($_POST['findus'])
	{
	case 'rep':
	$addData[findus] = 'Sales rep';
	break;

	case 'trade':
	$addData[findus] = 'Trade Show';
	break;
	
	case 'other':
	$addData[findus] = 'Other';
	break;
	
	case 'optik':
	$addData[findus] = 'Magazine Optik';
	break;
	
	case 'larevue':
	$addData[findus] = 'Magazine La Revue';
	break;
	
	case 'vision':
	$addData[findus] = 'Magazine Vision';
	break;
		
	case 'infoclip':
	$addData[findus] = 'Web Infoclip.ca';
	break;
		
	case 'optiguide':
	$addData[findus] = 'Web Opti-Guide.com';
	break;
	
	case 'pointclip':
	$addData[findus] = 'eBulletin Point Clip';
	break;
	
	case 'optinews':
	$addData[findus] = 'eBulletin Opti-News';
	break;

	}
	
$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	
	if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
		$addData[ship_address1]=$addData[bill_address1];
		$addData[ship_address2]=$addData[bill_address2];
		$addData[ship_city]=$addData[bill_city];
		$addData[ship_state]=$_POST[bill_state];
		$addData[ship_zip]=$addData[bill_zip];
		$addData[ship_country]=$_POST[bill_country];
	}else{
		$addData[ship_address1]=ucwords(addslashes($_POST[ship_address1]));
		$addData[ship_address2]=ucwords(addslashes($_POST[ship_address2]));
		$addData[ship_city]=ucwords(addslashes($_POST[ship_city]));
		$addData[ship_state]=ucwords(addslashes($_POST[ship_state]));
		$addData[ship_zip]=addslashes($_POST[ship_zip]);
		$addData[ship_country]=$_POST[ship_country];
	}
	$addData[phone]=addslashes($_POST[phone]);
	$addData[other_phone]=addslashes($_POST[other_phone]);
	$addData[fax]=addslashes($_POST[fax]);
	$addData[email]=addslashes($_POST[email]);
	$addData[password]=addslashes($_POST[password]);
	$addData[VAT_no]=addslashes($_POST[VAT_no]);


$promo_selected = $_SESSION['Promo_oneyear'];
 $_SESSION['Promo_oneyear'] = '';

$Futuredate = mktime(0,0,0,date("m"),date("d")+30,date("Y"));
$Date_in_30_days = date("Y-m-d", $Futuredate);

	switch($promo_selected)
	{
	
	//Promo 1000$
	case '1000-futureshop':
	$oneyear_type = '1000-futureshop';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;

	case '1000-lens':
	$oneyear_type = '1000-lens';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;
	
	case '1000-optipoints':
	$oneyear_type = '1000-optipoints';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;
	
	case '1000-ar':
	$oneyear_type = '1000-ar';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 15;
	break;
	
	
	//Promo 5000$
	case '5000-futureshop':
	$oneyear_type = '5000-futureshop';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000;
	break;
	
	case '5000-lens':
	$oneyear_type = '5000-lens';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000;
	break;


	case '5000-optipoints':
	$oneyear_type = '5000-optipoints';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000;
	break;


	}
	


	$query="insert into accounts (product_line, findus, title, first_name, last_name, account_num, company, business_type, buying_group, main_lab, VAT_no, bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, ship_address1, ship_address2, ship_city, ship_state, ship_zip, ship_country, phone, other_phone, fax, email, currency, purchase_unit, mfg_pref, user_id, password, terms, approved, privilege, member_since, ip_address, lnc_reward_points,oneyear_type, oneyear_dt, oneyear_ar_credit) 
	values 
	
	('lensnetclub','$addData[findus]', '$_POST[title]', '$addData[first_name]', '$addData[last_name]', '$addData[account_num]', '$addData[company]', '$_POST[business_type]', '$_POST[buying_group]', '$_POST[main_lab]', '$addData[VAT_no]', '$addData[bill_address1]', '$addData[bill_address2]', '$addData[bill_city]', '$addData[bill_state]', '$addData[bill_zip]', '$_POST[bill_country]', '$addData[ship_address1]', '$addData[ship_address2]', '$addData[ship_city]', '$addData[ship_state]', '$addData[ship_zip]', '$addData[ship_country]', '$addData[phone]', '$addData[other_phone]', '$addData[fax]', '$addData[email]', '$_POST[currency]', '$_POST[purchase_unit]', '$_POST[mfg_pref]', '$addData[user_id]', '$addData[password]', '$_POST[terms]', 'approved',  '$_POST[privilege]', '$addData[member_since]', '$ip', 100, '$oneyear_type', '$oneyear_dt', $oneyear_ar_credit)";
	

	$result=mysql_query($query)
		or die ("Could not create account" . mysql_error());
		
		echo $ip;
		
	$query="select primary_key, lab_name, lab_email from labs where primary_key=$_POST[main_lab]";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$labData=mysql_fetch_array($result);
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company], $labData[lab_email]);
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'dbeaulieu@direct-lens.com');
	//sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'rco.daniel@gmail.com');
	//sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'dbeaulieu@direct-lens.com');
	

//Insert the complimentary 100  optipoints
$queryPts="insert into lnc_reward_history (access_id, detail, amount, datetime, user_id) 
	values 	(14, 'Complimentary', 100, '$addData[member_since]', '$addData[user_id]')";
	$resultPts=mysql_query($queryPts)		or die ("Could not create account" . mysql_error());


return true;
}



?>