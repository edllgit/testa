<?php
require_once(__DIR__.'/../constants/url.constant.php');

//MAIN ADMIN FUNCTION FILE
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
 
function login_to_admin(){/* check user id and password on login */
	include "../sec_connectEDLL.inc.php";

	$query="SELECT username, password FROM labs WHERE username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysqli_query($con,$query) or die ("Could not find user");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	$compUser=strcmp($_POST[username_test], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
	return true;
}


function edit_lab($pkey)
{
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="UPDATE labs set
	revolution_lab		     = '$_POST[revolution_lab]',
	eco_visionease_lab		 = '$_POST[eco_visionease_lab]',
	hd_premier_choix_lab 	 = '$_POST[hd_premier_choix_lab]',
	dl_somo_lab   			 = '$_POST[dl_somo_lab]',
	essilor_2_lab   		 = '$_POST[essilor_2_lab]',
	nesp_2_lab   			 = '$_POST[nesp_2_lab]',
	innovation_ff_hd_2_lab   = '$_POST[innovation_ff_hd_2_lab]',
	axial_mini_hko_lab       = '$_POST[axial_mini_hko_lab]',
	axial_mini_somo_lab      = '$_POST[axial_mini_somo_lab]',
	generation_grm_lab		 = '$_POST[generation_grm_lab]', 	
	axial_grm_lab			 = '$_POST[axial_grm_lab]', 
	image_lab 				 = '$_POST[image_lab]', 
	identity_lab			 = '$_POST[identity_lab]', 
	innovation_ff_159_lab    = '$_POST[innovation_ff_159_lab]', 
	innovation_ff_hd_159_lab = '$_POST[innovation_ff_hd_159_lab]', 
	selection_rx_lab 	 = '$_POST[selection_rx_lab]', 
	ovation_lab 		 = '$_POST[ovation_lab]', 
	innovative_plus_lab  = '$_POST[innovative_plus_lab]',
	svision_lab			 = '$_POST[svision_lab]',
	nesp_lab 			 = '$_POST[nesp_lab]',
	private_grm_1_lab	 = '$_POST[private_grm_1_lab]',
	private_grm_2_lab	 = '$_POST[private_grm_2_lab]',
	private_grm_3_lab 	 = '$_POST[private_grm_3_lab]',
	private_collection_lab 	 = '$_POST[private_collection_lab]',
	nesp_lab 			 = '$_POST[nesp_lab]', 
	svision_2_lab 		 = '$_POST[svision_2_lab]',
	svision_3_lab 		 = '$_POST[svision_3_lab]', 
	optovision_lab  	 = '$_POST[optovision_lab]',  
	lab_name			 = '$_POST[lab_name]', 
	address1			 = '$_POST[address1]',
	address2 			 = '$_POST[address2]', 
	city				 = '$_POST[city]', 
	state				 = '$_POST[state]', 
	zip					 = '$_POST[zip]', 
	country				 = '$_POST[country]', 
	phone				 = '$_POST[phone]', 
	tollfree_phone	 	 = '$_POST[tollfree_phone]', 
	fax					 = '$_POST[fax]',
	tollfree_fax		 = '$_POST[tollfree_fax]', 
	fax_notify			 = '$_POST[fax_notify]',
	lab_email			 = '$_POST[email]', 
	notification_email	 = '$_POST[notification_email]',
	reports_email		 = '$_POST[reports_email]', 
	password			 = '$_POST[password]', 
	buying_level		 = '$_POST[buying_level]', 
	ship_chg_stock		 = '$_POST[ship_chg_stock]', 
	ship_chg_rx			 = '$_POST[ship_chg_rx]', 
	innovative_lab		 = '$_POST[innovative_lab]', 
	infocus_lab			 = '$_POST[infocus_lab]', 
	precision_vp_lab	 = '$_POST[precision_vp_lab]', 
	visionpropoly_lab	 = '$_POST[visionpropoly_lab]', 
	generation_lab		 = '$_POST[generation_lab]', 
	truehd_lab			 = '$_POST[truehd_lab]', 
	easy_fit_lab 		 = '$_POST[easy_fit_lab]', 
	other_lab			 = '$_POST[other_lab]', 
	visioneco_lab		 = '$_POST[visioneco_lab]', 
	private_1_lab		 = '$_POST[private_1_lab]', 
	private_2_lab		 = '$_POST[private_2_lab]', 
	private_3_lab		 = '$_POST[private_3_lab]', 
	den_1_lab			 = '$_POST[den_1_lab]',
	den_2_lab			 = '$_POST[den_2_lab]', 
	den_3_lab			 = '$_POST[den_3_lab]', 
	den_4_lab			 = '$_POST[den_4_lab]', 
	den_5_lab			 = '$_POST[den_5_lab]',
	bbg_1_lab			 = '$_POST[bbg_1_lab]', 
	bbg_2_lab			 = '$_POST[bbg_2_lab]', 
	bbg_3_lab			 = '$_POST[bbg_3_lab]', 
	bbg_4_lab			 = '$_POST[bbg_4_lab]', 
	bbg_5_lab			 = '$_POST[bbg_5_lab]', 
	bbg_6_lab			 = '$_POST[bbg_6_lab]',	
	bbg_7_lab			 = '$_POST[bbg_7_lab]',	
	bbg_8_lab			 = '$_POST[bbg_8_lab]',  
	bbg_9_lab 			 = '$_POST[bbg_9_lab]', 
	bbg_10_lab			 = '$_POST[bbg_10_lab]',
	bbg_11_lab			 = '$_POST[bbg_11_lab]', 
	bbg_12_lab			 = '$_POST[bbg_12_lab]', 
	glass_lab			 = '$_POST[glass_lab]', 
	glass_2_lab			 = '$_POST[glass_2_lab]', 
	rodenstock_lab		 = '$_POST[rodenstock_lab]', 
	rodenstock_hd_lab	 = '$_POST[rodenstock_hd_lab]',
	glass_3_lab			 = '$_POST[glass_3_lab]',
	vot_lab				 = '$_POST[vot_lab]',
	private_4_lab		 = '$_POST[private_4_lab]', 
	private_5_lab		 = '$_POST[private_5_lab]', 
	private_5_lab		 = '$_POST[private_5_lab]',
	innovation_ff_lab	 = '$_POST[innovation_ff_lab]',
	innovation_ds_lab	 = '$_POST[innovation_ds_lab]',
	innovation_ii_ds_lab = '$_POST[innovation_ii_ds_lab]',
	innovation_ff_hd_lab = '$_POST[innovation_ff_hd_lab]',
	private_6_lab		 = '$_POST[private_6_lab]',
	private_7_lab		 = '$_POST[private_7_lab]',
	private_8_lab		 = '$_POST[private_8_lab]',	
	eco_lab				 = '$_POST[eco_lab]',
	eco_1_lab			 = '$_POST[eco_1_lab]',
	eco_2_lab			 = '$_POST[eco_2_lab]',
	eco_3_lab			 = '$_POST[eco_3_lab]',
	eco_4_lab			 = '$_POST[eco_4_lab]',
	eco_5_lab			 = '$_POST[eco_5_lab]',
	eco_6_lab			 = '$_POST[eco_6_lab]',
	eco_7_lab			 = '$_POST[eco_7_lab]',
	eco_8_lab			 = '$_POST[eco_8_lab]',
	eco_9_lab			 = '$_POST[eco_9_lab]',
	eco_10_lab			 = '$_POST[eco_10_lab]',
	eco_or_lab			 = '$_POST[eco_or_lab]',	 
	eco_conant_lab		 = '$_POST[eco_conant_lab]' 
	WHERE primary_key = '$pkey'";
	

	
	$result=mysqli_query($con,$query)		or die ("Could not update lab because ".mysqli_error($con). $query );
	return ($pkey);
}



function login_access(){/* check user id and password on login */
	include_once("../sec_connectEDLL.inc.php");

	$query="SELECT username, password FROM access WHERE username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysqli_query($con,$query)		or die ("Could not find user");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	$compUser=strcmp($_POST[username_test], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
	return true;
}




function edit_account($pkey)
{
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

if ($_POST[display_double_warning] != 'yes'){
$_POST[display_double_warning] = 'no';
}


if ($_POST[account_past_due] != 'yes'){
$_POST[account_past_due] = 'no';
}




//Check if Billing address has been updated
	$queryBillingAddress = "SELECT bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, product_line, loyalty_program, user_id  from accounts WHERE  primary_key = '$pkey'";
	$resultBillingAddress=mysqli_query($con,$queryBillingAddress)		or die  ('I cannot update account because: ' . mysqli_error($con));
	$DataBillingAddress=mysqli_fetch_array($resultBillingAddress,MYSQLI_ASSOC);	
	
	$old_bill_address1 = 	$DataBillingAddress[bill_address1];
	$old_bill_address2 = 	$DataBillingAddress[bill_address2];
	$old_bill_city     = 	$DataBillingAddress[bill_city];
	$old_bill_state    = 	$DataBillingAddress[bill_state];
	$old_bill_zip      = 	$DataBillingAddress[bill_zip];
	$old_bill_country  = 	$DataBillingAddress[bill_country];
	$old_product_line  = 	$DataBillingAddress[product_line];
	$old_loyalty_program =  $DataBillingAddress[loyalty_program];
	$user_id 			 =  $DataBillingAddress[user_id];
	


  
  if ($old_loyalty_program <> $_POST[loyalty_program]){
 	//The loyalty Program for this account has been changed, we need the document the change
	
		switch($_POST[loyalty_program]){
			case 'none':     
				$DetailFr  = "Aucun programme de loyaut&eacute;" ; 
				$DetailEn  = "No loyalty program" ; 
				$Program   = 'None';	   					     
			break;	
			
			case 'platinum': 
				$DetailFr  = "Votre compte a &eacute;t&eacute; transf&eacute;r&eacute; dans le programme de loyaut&eacute; Platine";  
				$DetailEn  = "Your account has been transferred to Platinum Loyalty Program";  
				$Program   = 'Platinum';	
			break;	
			
			case 'gold':     
				$DetailFr  = "Votre compte a &eacute;t&eacute; transf&eacute;r&eacute; dans le programme de loyaut&eacute; Or."; 
				$DetailEn  = "Your account has been transferred to Gold Loyalty Program";   
				$Program   = 'Gold';    
			break;
				
			case 'silver':   
				$DetailFr  = "Votre compte a &eacute;t&eacute; transf&eacute;r&eacute; dans le programme de loyaut&eacute; Argent.";   
				$DetailEn  = "Your account has been transferred to Silver Loyalty Program";   
				$Program   = 'Silver';    
			break;	
		}
	
	
		$tomorrow    	   = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$datecomplete 	   = date("Y-m-d", $tomorrow);
	
		$queryLoyalty  = "INSERT INTO lnc_reward_history (detail, detail_fr,datetime,user_id,loyalty_program) VALUES('$DetailEn','$DetailFr','$datecomplete','$user_id','$Program')";
		$resultLoyalty = mysqli_query($con,$queryLoyalty) or die  ('I cannot update account because: ' . mysqli_error($con));
		
  }
	 
	   $prestige_level = $_POST[prestige_level];
		
	 switch($prestige_level){
		 //1- Appliquer les pourcentages de rabais sur les collections Prestige/eye-recommend 
		 case 'high':  //45% rabais sur facture + 5% rabais statement 
		 	 $queryRabaisCollection = "UPDATE ACCOUNTS SET 
			 er_swiss_dsc      = 45.00,  er_tr_dsc         = 45.00,  er_csc_dsc    = 45.00, er_versano_dsc = 45.00,
			 er_hko_dsc	       = 45.00,  er_crystal_dsc    = 45.00,  er_stc_dsc    = 45.00,
			 prestige_level = '$prestige_level', account_rebate = 5
			 WHERE primary_key = '$pkey'";
			 $resultRabaisCollection = mysqli_query($con,$queryRabaisCollection)		or die  ('I cannot set the Prestige Level  because: ' . mysqli_error($con));
		 break;
		 
		 case 'medium'://25% rabais sur facture + 5% rabais statement 
			 $queryRabaisCollection = "UPDATE ACCOUNTS SET 
			 er_swiss_dsc      = 25.00,  er_tr_dsc         = 25.00, er_csc_dsc    = 25.00, er_versano_dsc = 25.00,
			 er_hko_dsc	       = 25.00,  er_crystal_dsc    = 25.00, er_stc_dsc    = 25.00,
			 prestige_level = '$prestige_level', account_rebate = 5
			 WHERE primary_key = '$pkey'";
			  $resultRabaisCollection = mysqli_query($con,$queryRabaisCollection)		or die  ('I cannot set the Prestige Level  because: ' . mysqli_error($con));
		 break;

		 case 'low':  //10% rabais sur facture + 5% rabais statement
			 $queryRabaisCollection = "UPDATE ACCOUNTS SET 
			 er_swiss_dsc      = 10.00,  er_tr_dsc         = 10.00,  er_csc_dsc   = 10.00, er_versano_dsc = 10.00,
			 er_hko_dsc	       = 10.00,  er_crystal_dsc    = 10.00, er_stc_dsc    = 10.00,
			 prestige_level = '$prestige_level', account_rebate = 5 
			 WHERE primary_key = '$pkey'"; 
			 $resultRabaisCollection = mysqli_query($con,$queryRabaisCollection)		or die  ('I cannot set the Prestige Level  because: ' . mysqli_error($con));
		 break;
		 
		  case 'none':  //0% rabais sur facture + 0% rabais statement
			 $queryRabaisCollection = "UPDATE ACCOUNTS SET 
			 er_swiss_dsc      = 0,  er_tr_dsc         = 0, er_csc_dsc    = 0, er_versano_dsc = 0,
			 er_hko_dsc	       = 0,  er_crystal_dsc    = 0, er_stc_dsc    = 0,
			 prestige_level = '$prestige_level'
			 WHERE primary_key = '$pkey'"; 
			 $resultRabaisCollection = mysqli_query($con,$queryRabaisCollection)		or die  ('I cannot set the Prestige Level  because: ' . mysqli_error($con));
		 break;
	

	 }
	 
	 
	

	$query="update accounts set 
	bill_to       = '$_POST[bill_to]',  
	depot_number  = '$_POST[depot_number]', 
	bbg_1_dsc   ='$_POST[bbg_1_dsc]', 
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
	private_6_dsc ='$_POST[private_6_dsc]', private_7_dsc ='$_POST[private_7_dsc]',  innovation_ff_hd_dsc = '$_POST[innovation_ff_hd_dsc]' , innovation_ii_ds_dsc = '$_POST[innovation_ii_ds_dsc]' ,innovation_ds_dsc = '$_POST[innovation_ds_dsc]',innovation_ff_dsc = '$_POST[innovation_ff_dsc]',coupon_code = '$_POST[coupon_code]' , offer = '$_POST[offer]', email_notification = '$_POST[email_notification]', title='$_POST[title]', first_name='$_POST[first_name]', last_name='$_POST[last_name]', company='$_POST[company]', business_type='$_POST[business_type]', buying_group='$_POST[buying_group]', VAT_no='$_POST[VAT_no]', bill_address1='$_POST[bill_address1]', bill_address2='$_POST[bill_address2]', bill_city='$_POST[bill_city]', bill_state='$_POST[bill_state]', bill_zip='$_POST[bill_zip]', bill_country='$_POST[bill_country]', ship_address1='$_POST[ship_address1]', ship_address2='$_POST[ship_address2]', ship_city='$_POST[ship_city]', ship_state='$_POST[ship_state]', ship_zip='$_POST[ship_zip]', ship_country='$_POST[ship_country]', phone='$_POST[phone]', other_phone='$_POST[other_phone]', fax='$_POST[fax]', email='$_POST[email]', password='$_POST[password]', approved='$_POST[approved]', currency='$_POST[currency]', purchase_unit='$_POST[purchase_unit]', innovative_dsc='$_POST[innovative_dsc]', infocus_dsc='$_POST[infocus_dsc]', precision_dsc='$_POST[precision_dsc]', visionpro_dsc='$_POST[visionpro_dsc]', visionpropoly_dsc='$_POST[visionpropoly_dsc]', visioneco_dsc='$_POST[visioneco_dsc]', generation_dsc='$_POST[generation_dsc]', truehd_dsc='$_POST[truehd_dsc]', easy_fit_dsc='$_POST[easy_fit_dsc]', private_1_dsc='$_POST[private_1_dsc]', private_2_dsc='$_POST[private_2_dsc]', private_3_dsc='$_POST[private_3_dsc]', private_4_dsc='$_POST[private_4_dsc]', private_5_dsc='$_POST[private_5_dsc]', vot_dsc='$_POST[vot_dsc]',glass_dsc='$_POST[glass_dsc]',glass_2_dsc='$_POST[glass_2_dsc]', glass_3_dsc='$_POST[glass_3_dsc]', eco_dsc='$_POST[eco_dsc]', rodenstock_dsc='$_POST[rodenstock_dsc]', rodenstock_hd_dsc='$_POST[rodenstock_hd_dsc]',credit_hold='$_POST[credit_hold]',sales_rep='$_POST[mysalesrep]',sales_commission='$_POST[mycommission]', 
	main_lab = '$_POST[main_lab]', product_line = '$_POST[product_line]',
	buying_level = '$_POST[buying_level]',
	e_lab = '$_POST[e_lab]',
	 shipping_code    = '$_POST[shipping_code]',
	 loyalty_program  = '$_POST[loyalty_program]',
	 account_past_due = '$_POST[account_past_due]'
	 where primary_key = '$pkey'";
	 

	 
	$result=mysqli_query($con,$query) or die  ('I cannot update account because: ' . mysqli_error($con));
		
//credit limit check
	$today = date("Y-m-d");
	$limitQuery="SELECT cl_user_id, cl_limit_amt from acct_credit_limit WHERE cl_user_id = '$_POST[user_id]'";
	$limitResult=mysqli_query($con,$limitQuery) or die ("Could not find credit limit because ".mysqli_error($con));
	$limitcount=mysqli_num_rows($limitResult);
	
	// limit exists and has been reset to 0 or empty, so delete it from the table
	if (($limitcount > 0) && ((!$_POST["cl_limit_amt"]) || ($_POST["cl_limit_amt"] == 0))) {
		$limitQuery="DELETE from acct_credit_limit where cl_user_id = '$_POST[user_id]'";
		$limitResult=mysqli_query($con,$limitQuery) or die ("Could not delete credit limit because ".mysqli_error($con));
	}
	// limit exists and has been set to valid amount, check amount to see if date should be updated
	elseif (($limitcount > 0) && ($_POST["cl_limit_amt"] > 0)) {
		$limitData = mysqli_fetch_array($limitResult,MYSQLI_ASSOC);
		if ($limitData["cl_limit_amt"] != $_POST["cl_limit_amt"]){
			$limitQuery="UPDATE acct_credit_limit set cl_limit_amt='$_POST[cl_limit_amt]', cl_date='$today' where cl_user_id = '$_POST[user_id]'";
			$limitResult=mysqli_query($con,$limitQuery) or die ("Could not update credit limit because ".mysqli_error($con));
		}
	}
	// limit does not exist and has been set to valid amount, so add it to the table
	elseif (($limitcount == 0) && ($_POST["cl_limit_amt"] > 0)) {
		$limitQuery="INSERT into acct_credit_limit (cl_user_id, cl_limit_amt, cl_date) values ('$_POST[user_id]', '$_POST[cl_limit_amt]', '$today')";
		$limitResult=mysqli_query($con,$limitQuery)
			or die ("Could not add credit limit because ".mysqli_error($con));
	}
	
	if(($_POST[approved]=="approved")&&($_POST[notifyApproved]=="pending")){ /* check for initial admin approval of account and if so, send email */
		
		//Verify if account is a lens net account 
		$queryAccountType = "SELECT product_line, main_lab, language, business_type, user_id, pay_credit_card   from accounts where primary_key = '$pkey'" ;
		$resultAcctType   = mysqli_query($con,$queryAccountType) or die(mysqli_error($con));		
		$DataAcctType 	  = mysqli_fetch_array($resultAcctType,MYSQLI_ASSOC);
		$Product_line     = $DataAcctType['product_line'];
		$mainLab 	      = $DataAcctType['main_lab'];
		$BusinessType     = $DataAcctType['business_type'];			
		$userId           = $DataAcctType['user_id'];	
		$Pay_Credit_Card  = $DataAcctType['pay_credit_card'];	
		sendEmailMarjorie();
		
		
		//Si lab = IFC CLUB, on envoie email francais
		if ($mainLab == 37){
			sendEmailIfc();
		}elseif($mainLab == 47){
			sendEmailAit();
		}else{
	    	sendEmailLensnet($DataAcctType['language'],$BusinessType,$userId,$Pay_Credit_Card);
		}
		
		
		//If the account is a lens net account, we activate the lens net club collections
		switch($mainLab) {
		
		case 28://Lens net QC
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
	   
   		case 33:// Lens net atlantic
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
      
    	case 34://Lens net West
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 31://Lens net Elite
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 44://Lens net Pacific
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 42://Lens net Italia
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 43://DirectLab Pacific
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 41://DirectLab USA
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 40://DirectLab Italia
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 36://DirectLab Atlantic
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 35://Somo ClearI
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 21://Directlab Trois-Rivieres
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 22://Directlab Drummondville
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 1://VOT
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 3://Directlab Saint-Catharines
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 46://Directlab Illinois
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		break;
		
		case 29://Lens net Ontario
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 59://Safety Jean-Nicolas Boisvert
		$query_rs = "UPDATE accounts set sales_rep  = 32 WHERE primary_key ='$pkey' ";//SALES REP = JNC for this lab
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
				
		case 66://Entrepots Qc
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 67://Warehouses Canada
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 59://SAFETY
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 32://Lens net USA
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 47://Ait lens club
		$query_rs = "UPDATE accounts set shipping_code = 'OR005US' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		
		$query_rs = "UPDATE accounts set bill_to = 'B00024' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;
		
		case 50://Directlab Eagle
		$query_rs = "UPDATE accounts set shipping_code = 'OR005US' WHERE primary_key ='$pkey' ";
		$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
        break;

		}
		
		
		if ($Product_line == "safety")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','172')"; //Safety HKO
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','138')"; //Safety
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','189')"; //Safety STC
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','191')"; //Safety Swiss
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			}//Fin Safety
			
			
			if ($Product_line == "eye-recommend")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
			
			//Then we activate Eye Recommend collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','174')"; //ER Swiss
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','175')"; //ER Crystal
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','176')"; //ER HKO
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','177')"; //ER TR
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','190')"; //ER STC
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','192')"; //ER CSC
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','193')"; //ER Versano
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			}//Fin Eye Recommend
		
		
		if ($Product_line == "ifcclub")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
		
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','101')"; //IFC Simple
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','103')"; //IFC Progressif court 	
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','104')"; //IFC Progressif long
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','108')"; //Verres
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			}//End IF account = IFC CLUB
			

		
		if ($Product_line == "ifcclubca"){
			
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));

			//Then we activate ifc club ca  collections for every customer
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','161')"; //FT IFC
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','162')"; //IFC Crystal
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','163')"; //IFC SteCath
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','164')"; //IFC Swiss
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','165')"; //SV IFC
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','126')"; //NURBS sunglasses
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

		}//End IF account = IFC CLUB CA 
		
		
	
			if ($Product_line == "lensnetclub")
			{
				//First we delete all the collections that could have been activated for this account
				$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
				$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
					
				//Then we activate lens net collections 2017
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','199')";//Innovative 1
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','200')";//Innovative 2
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','201')";//Innovative 3
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','205')";//LNC GKC
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','206')";//LNC STC
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','207')";//LNC HKO
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','208')";//LNC SWISS
				$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
				
			}//End IF account = LENS NET CLUB
			
			
			
			
			
			 if ($Product_line == "directlens"){
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
			
		   //Then we activate Direct-Lens collections
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','197')"; // VOT DL
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','196')"; // CSC DL
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','195')"; // STC DL
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
						
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','148')"; // Optimize 3
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','152')"; // Horizon
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','153')"; // Fit
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','139')"; // Optimize
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','105')"; // Ovation
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','24')"; // Private 4
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','1')"; // Easy Fit HD
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','117')"; // Identity
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','4')"; //Precision
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','14')"; //My World
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','5')"; //TrueHD
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		   
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','21')"; //Private 3 = Easy One
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','6')"; //Vision Pro
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','88')"; //Svision
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','99')"; //Selection Rx
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','12')"; //Vision pro poly
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','22')"; // Glass
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','28')"; // Glass 2
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','31')"; // Glass 3
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','173')"; // STC EXTRA CHARGES
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	


			switch($mainLab) {
		
			case 22://Dlab Drummond
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
		   
			case 21://Dlab TR
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 39://Dlab Rive-Sud
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 43://Dlab Pacific
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 36://Dlab Atlantic
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 41://Dlab Atlantic
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 50://Dlab Eagle	
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','147')"; // Eagle Extra Charges
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
			
			case 3://Dlab St. catharines
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Other
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			break;
	
			}//End Switch

		   }
		   
			
				
			
			
			if ($Product_line == "aitlensclub")
			{
				
			//DELETE ALL STOCK COLLECTIONS FOR USER		
			$query="DELETE FROM accounts_stock_collections WHERE accounts_id='$pkey'";
			$result=mysqli_query($con,$query)		or die ("ERROR" . mysqli_error($con));
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysqli_query($con,$queryCol) or die(mysqli_error($con));
		
			//Then we activate AIT collections
			$query_rs = "insert into accounts_stock_collections (accounts_id,stock_collections_id ) values  ('".$pkey."','1')"; //Somo Stock
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
					
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','187')"; //Versano AIT
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','159')"; //Eco AIT
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','185')"; //Universal 2
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','186')"; //Universal 2 174 transitions
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
	
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','147')"; // Eagle Extra Charges
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));	
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','128')";//Nesp 2
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','127')";//Innovation FF HD 2
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','92')";//Nesp
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','115')";//Innovation FF 159
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','118')";//Image
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','116')";//Innovation FF HD 159
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','32')";//Eco 1
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','34')";//Eco 3
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','58')";//Eco 7
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','60')";//Innovation FF
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','61')";//Innovation DS
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','62')";//Innovation II DS
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','63')";//Innovation FF HD
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','109')";//Eco Essilor
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
						
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','98')";//Innovative Plus
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
			
			}//End IF account = Ait  lens CLUB
			
		
	}
	return ($pkey);
}

function addLabsStockRedirections($pkey){
include_once("../sec_connectEDLL.inc.php");
//DELETE ALL STOCK REDIRECTIONS FOR LAB
		
$query="DELETE FROM labs_stock_redirections WHERE main_lab_id='$pkey'";
$result=mysqli_query($con,$query) or die ("ERROR" . mysqli_error($con));

	
//INSERT STOCK REDIRECTIONS FOR LAB
$stockcollectionsQuery="SELECT * FROM stock_collections WHERE active='1'";
$stockcollectionsResult=mysqli_query($con,$stockcollectionsQuery) or die ("ERROR ".mysqli_error($con));

while($stockCollectionsItem=mysqli_fetch_array($stockcollectionsResult,MYSQLI_ASSOC)){

$var_name='stock_redirection_lab'.$stockCollectionsItem['stock_collections_id'];

if ($_POST[$var_name]!=""){
	
$query="INSERT INTO labs_stock_redirections (main_lab_id, labs_id, stock_collections_id) VALUES ('$pkey','$_POST[$var_name]' ,'$stockCollectionsItem[stock_collections_id]')";
$result=mysqli_query($con,$query)	or die ("ERROR" . mysqli_error($con));

		}//END IF
	
	}//END WHILE

}


function 	addAccountStockCollections($pkey){
include("../sec_connectEDLL.inc.php");
//DELETE ALL STOCK COLLECTIONS FOR USER
		
$query="DELETE FROM accounts_stock_collections WHERE accounts_id='$pkey'";
$result=mysqli_query($con,$query)	or die ("ERROR" . mysqli_error($con));
		
//INSERT STOCK COLLECTIONS FOR USER
if (isset($_POST['stock_collection'])) {
	$stock_collection = $_POST['stock_collection'];
	foreach($stock_collection as $k=>$v){
	
$query="INSERT INTO accounts_stock_collections (accounts_id, stock_collections_id) VALUES ('$pkey' ,'$k')";
$result=mysqli_query($con,$query) or die ("ERROR" . mysqli_error($con));
		}//END FOREACH
	}//END ISSET
}

function sendEmail(){/* sends the emails */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$message="Thank you for opening an account with direct-lens.com. Your account has been approved. For your records, your login is $_POST[user_id]. Your password is $_POST[password]. Please keep this information in a safe place. We look forward to serving you.\r\n";
$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';
$subject='Your account information';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}



function sendEmailLensnet($language,$BusinessType,$userId,$Pay_Credit_Card){/* sends the welcome  email for LensnetClub */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$datedujour		 = date("Y-m-d");
switch($BusinessType)
{
	//2500$ pour les optométristes
	case 'Optometrist Office': 
		$CreditLimitEn 	   = "Your account had been pre-approved to $2500. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute; avec une limite de 2500$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 		
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 2500,'$datedujour')"; 
		$resultCreditLimit = mysqli_query($con,$QueryCreditLimite)	or die ("ERROR" . mysqli_error($con));
	break;
	//1000$ pour les autres
	case 'Optician Office': 		
		$CreditLimitEn 	   = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysqli_query($con,$QueryCreditLimite)	or die ("ERROR" . mysqli_error($con));
	break;	
	case 'Lab': 					
		$CreditLimitEn 	   = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysqli_query($con,$QueryCreditLimite)	or die ("ERROR" . mysqli_error($con));
	break;	
	case 'Laboratorio': 			 
		$CreditLimitEn     = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysqli_query($con,$QueryCreditLimite)	or die ("ERROR" . mysqli_error($con));
	break;		
	default:	
	$CreditLimitEn     = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
	$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
	$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
	$resultCreditLimit = mysqli_query($con,$QueryCreditLimite)	or die ("ERROR" . mysqli_error($con));
}

if ($Pay_Credit_Card =='yes'){//Pas de limite de crédit s'il paie par carte de crédit
	$CreditLimitEn     		 = "You have checked the box <b>Statement Payment by Credit Card</b>. To obtain your authorization,<a href=\"".constant('DIRECT_LENS_URL')."/lensnet/pdf/credit_card_authorization_form.pdf\">Click here to download the form</a>. <br><br> Please sign and email to:dbeaulieu@direct-lens.com or fax to 1-877-590-3522.<br> If you have any difficulties with this, please feel free to contact our Customer Service at 1-855-770-2124.<br>
	"; 
	$CreditLimitFr 	  	 	 = "Vous avez coch&eacute; la case <b>&eacute;tat de compte pay&eacute; par carte de cr&eacute;dit</b>. Vous trouverez ci-joint le formulaire d'autorisation de paiement par carte de cr&eacute;dit.<br><br> Veuillez le remplir et nous le faire parvenir par courriel &agrave; dbeaulieu@direct-lens.com ou par fax au 1-877-590-3522. <br><br>Si vous &eacute;prouvez des difficult&eacute;s, veuillez contacter le Service &agrave; la client&egrave;le au  1-877-570-3522.<br><a href=\"".constant('DIRECT_LENS_URL')."/lensnet/pdf/formulaire_autorisation_carte_credit.pdf\">Cliquer ici pour télécharger  le formulaire</a>"; 
	$QueryDeleteCreditLimite = "DELETE FROM acct_credit_limit WHERE cl_user_id = '$userId'"; 
	//echo '<br>'. $QueryDeleteCreditLimite. '<br>';
	$resultCreditLimit 	     = mysqli_query($con,$QueryDeleteCreditLimite)	or die ("ERROR" . mysqli_error($con));	
}

if ($language == 'french')
{
	$message="Nous vous remercions de l'ouverture de votre compte.<br><br>". $CreditLimitFr. " <br><br>Pour vos dossiers, votre nom d'utilisateur est <b>$_POST[user_id]</b> et le mot de passe est  <b>$_POST[password]</b>. <br><br>Veuillez conserver ces informations précieusement. Au plaisir de vous servir.\r\n";
	$subject='Lensnet Club: Nouveau compte approuvé';
}else{
	$message="Thank you for opening an account with us.<br><br>". $CreditLimitEn. " <br><br>For your records, your login is <b>$_POST[user_id]</b> and the password is <b>$_POST[password]</b>.<br><br> Please keep this information in a safe place. We look forward to serving you.\r\n";
	$subject='Lensnet Club: New account approved';
}

$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';

$response=office365_mail($to_address, $from_address, $subject, null, $message);
}




function sendEmailAit(){/* sends the emails for Ait customer */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$message="Dear $_POST[title], $_POST[first_name]  $_POST[last_name] , <br><br>Your account has just been approved and you can now discover the power of our online network.<br><br>
The AIT Lens Club Team thanks you for signing up on the lens club dedicated to independents. We hope that the variety and quality of our lenses will meet and exceed your expectations.<br><br>Your satisfaction is our priority. We sincerely want to establish a long lasting relationship with you and your office.<br><br>In case you may have forgotten your login details, here they are:<br>Login page: : www.aitlensclub.com<br>Username: $_POST[user_id]    <br>Password: $_POST[password] <br><br> Customer Service is available at 1-888-274-6705.<br><br>
We thank you again for being an AIT Lens Club member,<br><br>AIT Lens Club Team\r\n";
$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';
$subject='Your Ait account information';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}



function sendEmailIfc(){/* sends the emails */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$message="Bonjour,<br>
Je vous remercie pour l'ouverture d'un compte avec IFCCLUB.com.<br>
Votre compte a &eacute;t&eacute; approuv&eacute;.<br><br>
Pour vos dossiers :<br>
Votre login est : <b>$_POST[user_id]</b><br>
Votre mot de passe est : <b>$_POST[password]</b><br>
SVP veuillez conserver cette information dans un endroit s&ucirc;r. <br>
Nous sommes impatients de vous servir.<br><br>
L'Équipe d’IFCCLUB<br>";
$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';
$subject='New account approved  :'.$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}


function sendEmailMarjorie(){/* sends the emails */
$QueryMainlab = "Select lab_name from labs where primary_key = (Select main_lab from accounts where user_id = '".$_POST[user_id] . "')";
$rs = mysqli_query($con,$QueryMainlab) or die(mysqli_error($con));
$DataMainlab=mysqli_fetch_array($rs,MYSQLI_ASSOC);
$lab_Name = $DataMainlab['lab_name'];
$message="Nouveau compte. Compagnie: $_POST[company]   Main lab: $lab_Name\r\n";
$curTime= date("m-d-Y");	
$to_address=array('dbeaulieu@direct-lens.com');
$from_address='donotreply@entrepotdelalunette.com';
$subject='New account approved  :'.$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}





function update_discounts($user_id)
{
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
		$price_key=explode("_", $x);//get primary key for prices table
		if($price_key[0]=="key"){
			$query="SELECT product_name FROM prices WHERE primary_key = '$price_key[1]'";//find product_name from prices table based on primary key
			$result=mysqli_query($con,$query) or die ("Could not find product name");
			$prodData=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$query="SELECT user_id FROM stock_discounts where user_id = '$user_id' AND product_name='$prodData[product_name]'";//see if stock discount exists for this product name and user id
			$result=mysqli_query($con,$query) or die ("Could not find stock discount");
			$dscCount=mysqli_num_rows($result);
		
			if($dscCount==0)
				$query="insert into stock_discounts (user_id, product_name, discount) values ('$user_id', '$prodData[product_name]', '$_POST[$x]')";
			else
				$query="update stock_discounts set discount='$_POST[$x]' where user_id = '$user_id' AND product_name = '$prodData[product_name]'";

		$result=mysqli_query($con,$query) or die ("Could not update stock discount");
		}
	}
	return true;
}

function update_order_status()
{
	include("../sec_connectEDLL.inc.php");
	$today=date('Y-m-d');
	foreach($_POST as $x => $y){
		$query="update orders set order_status='filled',  order_date_shipped ='$today' where order_num = '$y'";
		$result=mysqli_query($con,$query) or die ("Could not update order_status");
	}
}


function add_lab()
{
	include("../sec_connectEDLL.inc.php");
	$today=date('Y-m-d');

		$query="INSERT INTO labs (selection_rx_lab,innovative_plus_lab,svision_lab,private_grm_1_lab,private_grm_2_lab,private_grm_3_lab,nesp_lab, svision_2_lab, svision_3_lab, optovision_lab, lab_name,address1, 
address2, city, state, zip, country, phone, tollfree_phone, fax, tollfree_fax, fax_notify, lab_email, notification_email, reports_email, password, buying_level, ship_chg_stock, ship_chg_rx, 
innovative_lab	, infocus_lab, precision_vp_lab, generation_lab, truehd_lab, easy_fit_lab , other_lab, visioneco_lab, private_1_lab, private_2_lab, private_3_lab, den_1_lab, den_2_lab, 
den_3_lab, den_4_lab, den_5_lab, bbg_1_lab, bbg_2_lab, bbg_3_lab, bbg_4_lab, bbg_5_lab, bbg_6_lab, bbg_7_lab, bbg_8_lab, bbg_9_lab, bbg_10_lab, bbg_11_lab, bbg_12_lab, 
glass_lab,glass_2_lab,rodenstock_lab,rodenstock_hd_lab,glass_3_lab,vot_lab,private_4_lab,private_5_lab,	innovation_ff_lab,innovation_ds_lab,innovation_ii_ds_lab,innovation_ff_hd_lab,private_6_lab,private_7_lab,private_8_lab	,eco_lab,eco_1_lab,eco_2_lab,eco_3_lab,eco_4_lab,eco_5_lab,eco_6_lab, eco_7_lab, eco_8_lab, eco_9_lab, eco_10_lab, eco_or_lab,eco_conant_lab)


VALUES(
'$_POST[selection_rx_lab]',
'$_POST[innovative_plus_lab]',
'$_POST[svision_lab]', 
'$_POST[private_grm_1_lab]',
'$_POST[private_grm_2_lab]',
'$_POST[private_grm_3_lab]',
'$_POST[nesp_lab]',
'$_POST[svision_2_lab]',
'$_POST[svision_3_lab]',
'$_POST[optovision_lab]',
'$_POST[lab_name]',
'$_POST[address1]',
'$_POST[address2]', 
'$_POST[city]',
'$_POST[state]',
'$_POST[zip]',
'$_POST[country]',
'$_POST[phone]',
'$_POST[tollfree_phone]',
'$_POST[fax]',
'$_POST[tollfree_fax]',
'$_POST[fax_notify]',
'$_POST[email]',
'$_POST[notification_email]',    
'$_POST[reports_email]',
'$_POST[password]',
'$_POST[buying_level]',
'$_POST[ship_chg_stock]',
'$_POST[ship_chg_rx]',
'$_POST[innovative_lab]', 
'$_POST[infocus_lab]',
'$_POST[precision_vp_lab]',
 '$_POST[generation_lab]', '$_POST[truehd_lab]', '$_POST[easy_fit_lab]', '$_POST[other_lab]','$_POST[visioneco_lab]', '$_POST[private_1_lab]',  '$_POST[private_2_lab]', '$_POST[private_3_lab]','$_POST[den_1_lab]','$_POST[den_2_lab]', '$_POST[den_3_lab]',  '$_POST[den_4_lab]','$_POST[den_5_lab]', '$_POST[bbg_1_lab]', '$_POST[bbg_2_lab]', '$_POST[bbg_3_lab]','$_POST[bbg_4_lab]', '$_POST[bbg_5_lab]', '$_POST[bbg_6_lab]',	'$_POST[bbg_7_lab]','$_POST[bbg_8_lab]','$_POST[bbg_9_lab]','$_POST[bbg_10_lab]', 	'$_POST[bbg_11_lab]','$_POST[bbg_12_lab]', '$_POST[glass_lab]','$_POST[glass_2_lab]', '$_POST[rodenstock_lab]', '$_POST[rodenstock_hd_lab]','$_POST[glass_3_lab]','$_POST[vot_lab]','$_POST[private_4_lab]', '$_POST[private_5_lab]', '$_POST[innovation_ff_lab]','$_POST[innovation_ds_lab]','$_POST[innovation_ii_ds_lab]','$_POST[innovation_ff_hd_lab]',
 '$_POST[private_6_lab]','$_POST[private_7_lab]','$_POST[private_8_lab]', '$_POST[eco_lab]','$_POST[eco_1_lab]','$_POST[eco_2_lab]', '$_POST[eco_3_lab]','$_POST[eco_4_lab]',  '$_POST[eco_5_lab]',
 '$_POST[eco_6_lab]','$_POST[eco_7_lab]','$_POST[eco_8_lab]','$_POST[eco_9_lab]','$_POST[eco_10_lab]','$_POST[eco_or_lab]','$_POST[eco_conant_lab]')";		
$result=mysqli_query($con,$query)			or die ("Could not Create lab ".$query. mysqli_error($con));

}




function make_the_pmt()
{
	include("../sec_connectEDLL.inc.php");
	$result=mysqli_query($con,"SELECT curdate()");/* get today's date */
	$pmt_type="check";
	$_SESSION["CHECK_NO"]=$_POST[check_no];
		
	if($_POST[cc_no]){//if this is a credit card payment
		require_once "../../usaepay.php";
		$pmt_type="credit card";
		$query="SELECT * from accounts WHERE user_id = '$_SESSION[user_id]'";//find the account data
		$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		$acctData=mysqli_fetch_array($result,MYSQLI_ASSOC);

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
			$pmtResult=mysqli_query($con,$pmtQuery) or die  ('I cannot select payments because: ' . mysqli_error($con));
			$orderTest=mysqli_num_rows($pmtResult);
			$order_balance=bcsub($order_totals[$i], $order_amts[$i], 2);//figure the ending balance
			if($order_balance < .01){
				$order_paid_in_full="y";
			}
			if($orderTest==0){//if customer never tried to pay this order before
				$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
			}else{ //if customer tried to pay this order before and failed OR only a partial payment was made
				$pmtData=mysqli_fetch_array($pmtResult,MYSQLI_ASSOC);//retrieve previous payment data
				$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
			}
			$result=mysqli_query($con,$query) or die ('Could not add or update current payments because: ' . mysqli_error($con));
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
				$pmtResult=mysqli_query($con,$pmtQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
				$orderTest=mysqli_num_rows($pmtResult);
				$order_balance=0;//previous orders must be paid in full
				if($orderTest==0){//if customer never tried to pay this order before
					$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
				}else{ //if customer tried to pay this order before and failed
					$pmtData=mysqli_fetch_array($pmtResult,MYSQLI_ASSOC);//retrieve previous payment data
					$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
				}
				$result=mysqli_query($con,$query) or die ('Could not update previous balance payments because: ' . mysqli_error($con));
			}
		}
	}
	if($_SESSION["MEMOCREDLIST"]){//there are memo credits that should be marked as used
		reset($_SESSION["MEMOCREDLIST"]);
		$count_memo_creds=count($_SESSION["MEMOCREDLIST"]);
		$memoCredList=$_SESSION["MEMOCREDLIST"];
		for ($i = 1; $i <= $count_memo_creds; $i++){//update memo cred data
			$query="UPDATE memo_credits SET date_mc_applied='$today' WHERE mcred_primary_key='$memoCredList[$i]'";
			$result=mysqli_query($con,$query) or die ('Could not update memo credit because: ' . mysqli_error($con));
		}
	}
	
	if($_SESSION["STMTCREDLIST"]){//there are statement credits that should be marked as used
		reset($_SESSION["STMTCREDLIST"]);
		$count_stmt_creds=count($_SESSION["STMTCREDLIST"]);
		$stmtCredList=$_SESSION["STMTCREDLIST"];
		for ($i = 1; $i <= $count_stmt_creds; $i++){//update statement cred data
			$query="UPDATE statement_credits SET date_sc_applied='$today' WHERE primary_key_cr='$stmtCredList[$i]'";
			$result=mysqli_query($con,$query) or die ('Could not update statement credit because: ' . mysqli_error($con));
		}
	}
$pmtMessage = "Payment has been successfully submitted.";
return($pmtMessage);
}


function create_stmt_credit($acct_user_id){
	include_once("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="INSERT into statement_credits (acct_user_id, stmt_month, stmt_year, amount, credit_option, cr_description) VALUES ('$acct_user_id', '$_POST[stmt_month]', '$_POST[stmt_year]', '$_POST[amount]', '$_POST[credit_option]', '$_POST[cr_description]')";
	$result=mysqli_query($con,$query) or die ('Could not add credit because: ' . mysqli_error($con));
	return true;
}

function delete_stmt_credit($credit_key){
	include("../sec_connectEDLL.inc.php");

	$query="DELETE from statement_credits where primary_key_cr = '$credit_key'"; /* delete statement credit */
	$result=mysqli_query($con,$query) or die ('Could not delete credit because: ' . mysqli_error($con));
	return true;
}

function makeAdminPmt($user_id){
	include("../sec_connectEDLL.inc.php");
	require_once "../../usaepay.php";
	if($_POST[pmt_type]=="check"){
		$query="SELECT * from payments WHERE order_num = '$_POST[order_num]' AND user_id = '$user_id' AND pmt_marker = 'pending'";//find user's pending payments
		$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		$orderCount=mysqli_num_rows($result);
		if($orderCount==0)//if customer never tried to pay this order before (no payment markers found)
			$query="insert into payments (user_id, order_num, pmt_date, pmt_type, check_num, pmt_amount) values ('$user_id', '$_POST[order_num]', '$_POST[today]', 'check', $_POST[check_num], '$_POST[total_cost]')";
		else //if customer tried to pay this order before and failed
			$query="UPDATE payments SET pmt_marker='', pmt_date='$_POST[today]', pmt_type='check', check_num='$_POST[check_num]', pmt_amount='$_POST[total_cost]' WHERE order_num='$_POST[order_num]'";
		$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
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

			$result=mysqli_query($con,"SELECT curdate()");/* get today's date */
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
					$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
				}
			}else{//enter payments from Order History
				$query="SELECT * from payments WHERE order_num = '$_POST[order_num]' AND user_id = '$user_id' AND pmt_marker = 'pending'";//find user's pending payments
				$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
				$orderCount=mysqli_num_rows($result);
				if($orderCount==0)//if customer never tried to pay this order before (no payment markers found)
					$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode) values ('$_POST[user_id]', '$_POST[order_num]', '$today', 'credit card', '$amount', '$_POST[cc_type]', '$cclast4', '$resultcode', '$authcode')";
				else //if customer tried to pay this order before and failed
					$query="UPDATE payments SET pmt_marker='', pmt_amount='$amount', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$resultcode', transAuthCode='$authcode' WHERE order_num='$order_num'";
				$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
			}
		}
	}
	return ($_POST[order_num]);
}

//Reason Codes
function create_memo_code($lab_pkey){
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="SELECT * FROM memo_codes WHERE mc_lab = '$lab_pkey' AND  memo_code = '$_POST[memo_code]'";//find this memo code if it already exists
	$result=mysqli_query($con,$query) or die  ('I cannot select memo code because: ' . mysqli_error($con));
	$codeTest=mysqli_num_rows($result);
	if($codeTest!=0)
		return false;
	$query="INSERT into memo_codes (memo_code, mc_description, mc_lab) VALUES ('$_POST[memo_code]', '$_POST[mc_description]', '$lab_pkey')";
	$result=mysqli_query($con,$query) or die ('Could not add memo code because: ' . mysqli_error($con));
	return true;
}

//Reason Codes
function edit_memo_code($lab_pkey, $mc_pkey){
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);

	$query="SELECT * from memo_codes WHERE mc_lab = '$lab_pkey' AND  memo_code = '$_POST[memo_code]' AND mc_primary_key != '$mc_pkey'";//find this memo code if it already exists
	$result=mysqli_query($con,$query) or die  ('I cannot select memo code because: ' . mysqli_error($con));
	$codeTest=mysqli_num_rows($result);
	if($codeTest!=0)
		return false;
	$query="UPDATE memo_codes SET memo_code='$_POST[memo_code]', mc_description='$_POST[mc_description]' WHERE mc_primary_key = '$mc_pkey'";
	$result=mysqli_query($con,$query) or die ('Could not edit memo code because: ' . mysqli_error($con));
	return true;
}

function issue_memo_credit($mcred_abs_amount){
	include("../sec_connectEDLL.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	$query="SELECT * from memo_credits_rebilling WHERE mcred_memo_num = '$_POST[lastMemoNum]'";//find this memo credit if it already exists
	$result=mysqli_query($con,$query)		or die  ('I cannot select memo credit because: ' . mysqli_error($con));
	$creditTest=mysqli_num_rows($result);
	if($creditTest!=0)
		return false;
	$timestamp=strtotime($_POST[mcred_date]);
	$mcred_date=date("Y-m-d", $timestamp);


$queryLab="SELECT lab from orders  WHERE order_num = '$_POST[order_num]'";//find this memo credit if it already exists
$resultLab=mysqli_query($con,$queryLab)		or die  ('I cannot select memo credit because: ' . mysqli_error($con));
$DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);


$query="INSERT into memo_credits_rebilling (mcred_order_num, mcred_acct_user_id, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date, pat_ref_num, patient_first_name, patient_last_name) VALUES ('$_POST[order_num]', '$_POST[acct_user_id]', '$_POST[lastMemoNum]', '$_POST[mcred_cred_type]', '$_POST[mcred_disc_type]', '$_POST[mcred_amount]', '$mcred_abs_amount', '$_POST[mcred_memo_code]', '$mcred_date', '$_POST[patient_ref_num]', '$_POST[order_patient_first]', '$_POST[order_patient_last]')";

$result=mysqli_query($con,$query)		or die ('Could not issue Rebilling memo because: ' . mysqli_error($con));
			

				return true;


}//end function


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
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" /></head><body>";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="'.constant('DIRECT_LENS_URL').'/direct-lens/logos/'.$_SESSION[labAdminData][logo_file].'"/></td><td align="center"><img src="'.constant('DIRECT_LENS_URL').'/direct-lens/logos/direct-lens_logo.gif" width="200" height="60" /></td></tr></table>';
	
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
	
$arrayEmail 	 = str_split($_POST[customer_email],50);
$send_to_address = $arrayEmail;

$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//$msgSent = mail("$_POST[customer_email]", "$subject", "$message", "$headers");
if($response)
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
