<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

function login_to_dl($user_test, $password_test){/* check user_id and password on login */
include "../sec_connectEDLL.inc.php";
	
	
	$ip		 	  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$level   	  = 'Lensnetclub';
	$datetime 	  = date("Y-m-d G i:s");
	$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR'];	
	
	$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser) VALUES ('$user_test', '$password_test', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";
	$resultInsert=mysqli_query($con,$queryInsert)		or die ("Could not insert" . mysqli_error($con));
		
	$query="SELECT * FROM accounts WHERE product_line = 'lensnetclub' and user_id = '$user_test' and password = '$password_test'";
	$result=mysqli_query($con,$query)		or die ("Could not find account");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0)/* user id and/or password are not valid */
	{
		header("Location:loginfail.php");
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$sessionUserData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$date1 = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$ajd = date("Y/m/d", $date1);
	
	//Validation si le client se connecte après plus de 4 mois-->Envoyer un email
	$DerniereConnexion = strtotime($sessionUserData[last_connexion]);
	$Compagnie = $sessionUserData[company];
	$date2 = strtotime($ajd);
	$interval = ($DerniereConnexion - $date2)/86400;
	$interval = abs($interval);
	if ($interval>119){ //Plus de 4 mois
		EnvoyerEmailPlusde4Mois($Compagnie,$interval,$ip,$ip2);
	}
	
	
	//We save the connexion as this customer last login
	$QueryLastLogin = "Update accounts SET last_connexion = '$ajd' where user_id = '$user_test'";
	$resultLastLogin=mysqli_query($con,$QueryLastLogin)		or die ("Could not find account");

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
		$query="SELECT bg_name, global_dsc FROM buying_groups where primary_key = '$sessionUserData[buying_group]'";
		$result=mysqli_query($con,$query)			or die ("Could not find bg data");
		$bgData=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$sessionUserData[bg_name]=$bgData[bg_name];
		$sessionUserData[global_dsc]=$bgData[global_dsc];
		}
	}
	return($sessionUserData);
}

function login_to_bg($user_test, $password_test){/* check user_id and password on login */
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM buying_groups where username = '$user_test' and password = '$password_test'";
	$result=mysqli_query($con,$query) or die ("Could not find account");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0)/* user id and/or password are not valid */
	{
		header("Location:loginfail.php");
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$sessionBGData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	$compPW=strcmp($password_test, $sessionBGData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compPW != 0){
		header("Location:loginfail.php");
		exit();
		}

	}
	return($sessionBGData);
}



function create_account_lensnet()
{
include "../sec_connectEDLL.inc.php";

	$addData[user_id]=addslashes($_POST[user_id]);
		$query="SELECT * FROM accounts where user_id = '$addData[user_id]'";/* check for the new user_id already in db */
		$result=mysqli_query($con,$query) or die ("Could not execute select login query");
		$user_idtest=mysqli_num_rows($result);
		if ($user_idtest != 0){ /* if new acct and the new user id is not unique */
			return false;
		}

	$result   	  = mysqli_query($con,"SHOW TABLE STATUS LIKE 'accounts'");
	$array	  	  = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$next_key 	  = $array[Auto_increment]; /* get next primary key from accounts table for account number */
	$date1   	  = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
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
	$addData[language]=addslashes(strtolower($_POST[language]));
	$addData[privilege] =addslashes($_POST[privilege]);
	$addData[account_no]=addslashes($_POST[account_no]);
	$addData[member_since]=$datecomplete;
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$currency = $_POST[currency];
	if ($_POST[main_lab] == 56){
	$currency  	   	  = "EUR";
	$addData[language]= "french";
	}
	
	if ($addData[account_no] == ''){
	$addData[account_num] = $next_key;
	}else {
	$addData[account_num] = $addData[account_no];
	}
	
	if ($_POST[pay_credit_card]=='yes'){
	$Pay_Credit_Card = 'yes';
	}else{	
	$Pay_Credit_Card = 'no';	
	}
	
	switch ($_POST['findus'])
	{
		case 'rep':			$addData[findus] = 'Sales rep';				break;
		case 'trade':		$addData[findus] = 'Trade Show';			break;
		case 'other':		$addData[findus] = 'Other';					break;
		case 'optik':		$addData[findus] = 'Magazine Optik';		break;
		case 'larevue':		$addData[findus] = 'Magazine La Revue';		break;
		case 'vision':		$addData[findus] = 'Magazine Vision';		break;
		case 'infoclip':	$addData[findus] = 'Web Infoclip.ca';		break;
		case 'optiguide':	$addData[findus] = 'Web Opti-Guide.com';	break;
		case 'pointclip':	$addData[findus] = 'eBulletin Point Clip';	break;
		case 'optinews':	$addData[findus] = 'eBulletin Opti-News';	break;
	}
	

	
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

	$query="insert into accounts (language, findus, title, first_name, last_name, account_num, company, business_type, buying_group, main_lab, VAT_no, bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, ship_address1, ship_address2, ship_city, ship_state, ship_zip, ship_country, phone, other_phone, fax, email, currency, purchase_unit, mfg_pref, user_id, password, terms, approved, privilege, member_since, ip_address, lnc_reward_points,product_line, pay_credit_card) 
	values 
	
	('$addData[language]', '$addData[findus]', '$_POST[title]', '$addData[first_name]', '$addData[last_name]', '$addData[account_num]', '$addData[company]', '$_POST[business_type]', '$_POST[buying_group]', '$_POST[main_lab]', '$addData[VAT_no]', '$addData[bill_address1]', '$addData[bill_address2]', '$addData[bill_city]', '$addData[bill_state]', '$addData[bill_zip]', '$_POST[bill_country]', '$addData[ship_address1]', '$addData[ship_address2]', '$addData[ship_city]', '$addData[ship_state]', '$addData[ship_zip]', '$addData[ship_country]', '$addData[phone]', '$addData[other_phone]', '$addData[fax]', '$addData[email]', '$currency', '$_POST[purchase_unit]', '$_POST[mfg_pref]', '$addData[user_id]', '$addData[password]', '$_POST[terms]', 'pending',  '$_POST[privilege]', '$addData[member_since]', '$ip', 100,'lensnetclub','$Pay_Credit_Card')";
	$result=mysqli_query($con,$query) or die ("Could not create account" . mysqli_error($con));
			
	$query="SELECT primary_key, lab_name, lab_email FROM labs WHERE primary_key=$_POST[main_lab]";
	$result=mysqli_query($con,$query)		or die ("Could not find lab");
	$labData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company], $labData[lab_email]);
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'dbeaulieu@direct-lens.com');
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'rco.daniel@gmail.com');
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'thahn@direct-lens.com');

//Insert the complimentary 100  optipoints
$queryPts="insert into lnc_reward_history (access_id, detail, amount, datetime, user_id) 
	values 	(14, 'Complimentary', 100, '$addData[member_since]', '$addData[user_id]')";
	$resultPts=mysqli_query($con,$queryPts)		or die ("Could not create account" . mysqli_error($con));


return true;
}


function create_account()
{
include "../sec_connectEDLL.inc.php";

	$addData[user_id]=addslashes($_POST[user_id]);
		$query="SELECT * FROM accounts WHERE user_id = '$addData[user_id]'";/* check for the new user_id already in db */
		$result=mysqli_query($con,$query) or die ("Could not execute select login query");
		$user_idtest=mysqli_num_rows($result);
		if ($user_idtest != 0){ /* if new acct and the new user id is not unique */
			return false;
		}

	$result=mysqli_query($con,"SHOW TABLE STATUS FROM $mysql_db LIKE 'accounts'");
	$array=mysqli_fetch_array($result,MYSQLI_ASSOC);
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

	$query="insert into accounts (findus, title, first_name, last_name, account_num, company, business_type, buying_group, main_lab, VAT_no, bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, ship_address1, ship_address2, ship_city, ship_state, ship_zip, ship_country, phone, other_phone, fax, email, currency, purchase_unit, mfg_pref, user_id, password, terms, approved, privilege, product_line, member_since) 
	values 
	
	('$addData[findus]', '$_POST[title]', '$addData[first_name]', '$addData[last_name]', '$addData[account_num]', '$addData[company]', '$_POST[business_type]', '$_POST[buying_group]', '$_POST[main_lab]', '$addData[VAT_no]', '$addData[bill_address1]', '$addData[bill_address2]', '$addData[bill_city]', '$addData[bill_state]', '$addData[bill_zip]', '$_POST[bill_country]', '$addData[ship_address1]', '$addData[ship_address2]', '$addData[ship_city]', '$addData[ship_state]', '$addData[ship_zip]', '$addData[ship_country]', '$addData[phone]', '$addData[other_phone]', '$addData[fax]', '$addData[email]', '$_POST[currency]', '$_POST[purchase_unit]', '$_POST[mfg_pref]', '$addData[user_id]', '$addData[password]', '$_POST[terms]', 'pending',  '$_POST[privilege]', 'lensnetclub','$addData[member_since]')";
	$result=mysqli_query($con,$query) or die ("Could not create account" . mysqli_error($con));
		
	$query="SELECT primary_key, lab_email, lab_name FROM labs WHERE primary_key=$_POST[main_lab]";
	$result=mysqli_query($con,$query)		or die ("Could not find lab");
	$labData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	sendEmail($_POST[last_name], $_POST[first_name], $_POST[company], $labData[lab_email]);
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'dbeaulieu@direct-lens.com');
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'rco.daniel@gmail.com');
	sendEmail($_POST[last_name], $_POST[first_name],  $_POST[company]  . '  ' .  $labData[lab_name],'dbeaulieu@direct-lens.com');

	if ($_POST[main_lab] == 28){
	$message="A new account has been requested. Please log into the labAdmin area to view the new account and approve or decline the request.\r\n\r\n";
	$message.="NEW ACCOUNT INFORMATION\r\n\r\n";
	$message.="Last Name: $_POST[last_name]\r\n";
	$message.="First Name:  $_POST[first_name]\r\n";
	$message.="Company:  $_POST[company]\r\n\r\n";
	$headers = "From: info@direct-lens.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("labo@direct-lens.com", "New direct-lens.com Account Request", "$message", "$headers");
	}

return true;
}

function create_newsletter_member()
{
include "../sec_connectEDLL.inc.php";
	
	$addData[company]=ucwords(addslashes($_POST[company]));
	$addData[first_name]=ucwords(addslashes($_POST[first_name]));
	$addData[last_name]=ucwords(addslashes($_POST[last_name]));
	$addData[phone]=addslashes($_POST[phone]);
	$addData[email]=addslashes($_POST[email]);
	$addData[title]=addslashes($_POST[title]);
	$addData[address]=addslashes($_POST[address]);
	$addData[language]=addslashes($_POST[language]);
	$addData[want_to_receive_promo]=addslashes($_POST[want_to_receive_promo]);
	if ($addData[want_to_receive_promo] == ""){
	$addData[want_to_receive_promo] = 0 ; }else {
	$addData[want_to_receive_promo] = 1 ; 
	}
	

	$query="insert into newletters (first_name,last_name,title,company,phone_work,address,email,want_to_receive_promo,language) 
	VALUES 
	
	('$addData[first_name]', '$addData[last_name]', '$addData[title]', '$addData[company]', '$_POST[phone]',  '$addData[address]', '$addData[email]', '$addData[want_to_receive_promo]', '$addData[language]' )";
	$result=mysqli_query($con,$query) or die ("Could not create newsletter member" . mysqli_error($con));
		
return true;
}

function sendEmail($last_name, $first_name, $company, $lab_email){/* sends the emails */
	$message='<div style="font-family:Verdana;"><h3>A new LENSNET account has been requested.</h3>';
	$message.='Please log into the <a href="https://www.direct-lens.com/labAdmin/" target="_blank">labAdmin area</a>, ';
	$message.="to view the new account and approve or decline the request.<br /><br /><br />";
	$message.="<h3>NEW LensNet ACCOUNT INFORMATION</h3>";
	$message.="Last Name: $last_name<br /><br />";
	$message.="First Name: $first_name<br /><br />";
	$message.="Company: $company<br /><br /></div>";
	$send_to_address=str_split($lab_email,150);
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject='New LensNet Account Request';
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	//Log email
	foreach($send_to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("NEW LNC ACCOUNT EMAIL",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("NEW LNC ACCOUNT EMAIL",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}

function editAccount($sessionUser_Id)
{
	include "../sec_connectEDLL.inc.php";
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	
		if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
		$_POST[ship_address1]=$_POST[bill_address1];
		$_POST[ship_address2]=$_POST[bill_address2];
		$_POST[ship_city]=$_POST[bill_city];
		$_POST[ship_state]=$_POST[bill_state];
		$_POST[ship_zip]=$_POST[bill_zip];
		$_POST[ship_country]=$_POST[bill_country];
	}else{
		$_POST[ship_address1]=ucwords($_POST[ship_address1]);
		$_POST[ship_address2]=ucwords($_POST[ship_address2]);
		$_POST[ship_city]=ucwords($_POST[ship_city]);
	}

	if(($_POST[newPW]!="")&&($_POST[oldPW]!="")){ /* if user is updating pw */
		$query="SELECT user_id, password from accounts where user_id = '$sessionUser_Id'";
		$result=mysqli_query($con,$query) or die ("Could not find account");
		$PWtest=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$compPW=strcmp($_POST[oldPW], $PWtest[password]);/* check that login password is case-sensitive to password in db*/
		if ($compPW != 0){
			header("Location:pwproblem.php");
			exit();
		}else{
			$query="update accounts set password='$_POST[newPW]' where user_id = '$sessionUser_Id'";
			$result=mysqli_query($con,$query) or die ("Could not update password");
		}

	}

	$query="update accounts set title='$_POST[title]', first_name='$_POST[first_name]', last_name='$_POST[last_name]', company='$_POST[company]', business_type='$_POST[business_type]', buying_group='$_POST[buying_group]', VAT_no='$_POST[VAT_no]', bill_address1='$_POST[bill_address1]', bill_address2='$_POST[bill_address2]', bill_city='$_POST[bill_city]', bill_state='$_POST[bill_state]', bill_zip='$_POST[bill_zip]', bill_country='$_POST[bill_country]', ship_address1='$_POST[ship_address1]', ship_address2='$_POST[ship_address2]', ship_city='$_POST[ship_city]', ship_state='$_POST[ship_state]', ship_zip='$_POST[ship_zip]', ship_country='$_POST[ship_country]', phone='$_POST[phone]', other_phone='$_POST[other_phone]', fax='$_POST[fax]', email='$_POST[email]', currency='$_POST[currency]', purchase_unit='$_POST[purchase_unit]', mfg_pref='$_POST[mfg_pref]' where user_id = '$sessionUser_Id'";
	$result=mysqli_query($con,$query) or die ("Could not update account");
	$query="SELECT * FROM accounts WHERE user_id = '$sessionUser_Id'";
	$result=mysqli_query($con,$query) or die ("Could not find account");
return ($result);
}

function addSalesperson($user_id)
{
include "../sec_connectEDLL.inc.php";

	$_POST[sales_id]=addslashes($_POST[sales_id]);
	$query="SELECT * FROM salespeople WHERE sales_id = '$_POST[sales_id]' AND acct_user_id = '$user_id'";/* check for the new sales_id already in db for this account */
	$result=mysqli_query($con,$query) or die ("Could not select salesperson");
	$sales_idtest=mysqli_num_rows($result);
	if ($sales_idtest != 0){ /* if new sales id is not unique */
		return false;
	}

	$addData[sales_id]=$_POST[sales_id];
	$addData[first_name]=ucwords(addslashes($_POST[first_name]));
	$addData[last_name]=ucwords(addslashes($_POST[last_name]));

	$query="insert into salespeople (first_name, last_name, acct_user_id, sales_id) values ('$addData[first_name]', '$addData[last_name]', '$user_id', '$addData[sales_id]')";
	$result=mysqli_query($con,$query) or die ("Could not create salesperson");
	return true;
}

function removeSalesperson($pkey_sp)
{
	include "../sec_connectEDLL.inc.php";

	$query="update salespeople set removed='yes' where primary_key_sp = '$pkey_sp'";
	$result=mysqli_query($con,$query) or die ("Could not remove salesperson");
	return true;
}

function returnSalesperson($pkey_sp)
{
	include "../sec_connectEDLL.inc.php";

	$query="update salespeople set removed='' where primary_key_sp = '$pkey_sp'";
	$result=mysqli_query($con,$query) or die ("Could not return salesperson");
	return true;
}

function editBGAccount($BG_pkey)
{
	include "../sec_connectEDLL.inc.php";
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	

	$query="update buying_groups set contact_first='$_POST[contact_first]', contact_last='$_POST[contact_last]', bg_email='$_POST[email]' where primary_key = '$BG_pkey'";
	$result=mysqli_query($con,$query) or die ("Could not update buying group account");
	$query="SELECT * FROM buying_groups WHERE primary_key = '$BG_pkey'";
	$result=mysqli_query($con,$query) or die ("Could not find buying group account");
	$sessionBGData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	foreach($sessionBGData as $x => $y){
		$sessionBGData[$x] = addslashes($y);
	}
return ($sessionBGData);
}




function log_email($subject,$send_to_address,$additional, $user_agent){
	include "../sec_connectEDLL.inc.php";
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail) or die  ('I cannot Send email because: ' . mysqli_error($con));	
}



function EnvoyerEmailPlusde4Mois($Compagnie,$interval,$ip,$ip2){
	$message="";
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 12pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.='<body><table width="550" cellpadding="2"  cellspacing="0" border="1" class="TextSize">';
	$message.="
	<tr>
		<th>Company</th>
		<th>Days since last login</th>
		<th>Ip</th>
		<th>Ip 2</th>
	</tr>
	<tr>
		<td align=\"center\">$Compagnie</td>
		<td align=\"center\">$interval</td>
		<td align=\"center\">$ip</td>
		<td align=\"center\">$ip2</td>
	</tr>
	</table>";	
	
	$message .=$RxData;	
	$message .=$ProdData;	
	$message .=$queryPrice;
	//Send EMAIL		
	$send_to_address = array('rapports@direct-lens.com');	
	echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Compagnie logged in after $interval days";
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

?>