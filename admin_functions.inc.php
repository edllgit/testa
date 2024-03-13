<?php
require_once(__DIR__.'/constants/aws.constant.php');
require_once(__DIR__.'/constants/url.constant.php');
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

//Lab Admin file

function login_to_admin(){/* check user id and password on login */
	require_once("../Connections/sec_connect.inc.php");

	$query="select username, password from labs where username = '$_POST[username_test]' and password = '$_POST[password_test]'";
	$result=mysql_query($query)
		or die ("Could not find user");
	$usercount=mysql_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysql_fetch_array($result);

	$compUser=strcmp($_POST[username_test], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
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
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$labAdminData=mysql_fetch_array($result);

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
	require_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	
	
	

if ($_POST[free_edging] != 'yes'){
$_POST[free_edging] = 'no';
}

if ($_POST[access_short_order_form] != 'yes'){
$_POST[access_short_order_form] = 'no';
}


if ($_POST[charge_dispensing_fee] != 'yes'){
$_POST[charge_dispensing_fee] = 'no';
}



if ($_POST[display_double_warning] != 'yes'){
$_POST[display_double_warning] = 'no';
}

if ($_POST[allow_special_instruction] != 'yes'){
$_POST[allow_special_instruction] = 'no';
}

if ($_POST[auto_load_last_rx] != 'yes'){
$_POST[auto_load_last_rx] = '0';
}

if ($_POST[auto_load_last_rx] == 'yes'){
$_POST[auto_load_last_rx] = '1';
}

if ($_POST[selected_promotion] == 'ar toonie'){
$_POST[selected_promotion] = 'ar toonie';
$_POST[ar_toonie_promo_code] = $_POST[holiday_promo_code];
}elseif ($_POST[selected_promotion]=='sizzling summer'){
$_POST[selected_promotion] = 'sizzling summer';
}


//Check if Billing address has been updated
	$queryBillingAddress = "SELECT bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, product_line  from accounts WHERE  primary_key = '$pkey'";
	$resultBillingAddress=mysql_query($queryBillingAddress)		or die  ('I cannot update account because: ' . mysql_error());
	$DataBillingAddress=mysql_fetch_array($resultBillingAddress);	
	
	$old_bill_address1 = 	$DataBillingAddress[bill_address1];
	$old_bill_address2 = 	$DataBillingAddress[bill_address2];
	$old_bill_city     = 	$DataBillingAddress[bill_city];
	$old_bill_state    = 	$DataBillingAddress[bill_state];
	$old_bill_zip      = 	$DataBillingAddress[bill_zip];
	$old_bill_country  = 	$DataBillingAddress[bill_country];
	$old_product_line  = 	$DataBillingAddress[product_line];


	$query="update accounts set
	charge_dispensing_fee = '$_POST[charge_dispensing_fee]',
	safety_plan =  '$_POST[safety_plan]',
	access_short_order_form =  '$_POST[access_short_order_form]',
	free_edging =  '$_POST[free_edging]',
	promo_code = '$_POST[promo_code]',
	selected_promotion = '$_POST[selected_promotion]',
	contact_name =  '$_POST[contact_name]',
	auto_load_last_rx =  '$_POST[auto_load_last_rx]',
	revolution_dsc    =  '$_POST[revolution_dsc]',
	horizon_dsc = '$_POST[horizon_dsc]',
	fit_dsc = '$_POST[fit_dsc]',
	goyette_swiss_dsc = '$_POST[goyette_swiss_dsc]',
	goyette_crystal_dsc = '$_POST[goyette_crystal_dsc]',
	optimize_2_dsc = '$_POST[optimize_2_dsc]',
	optimize_3_dsc = '$_POST[optimize_3_dsc]',
	optimize_4_dsc = '$_POST[optimize_4_dsc]',
	optimize_dsc = '$_POST[optimize_dsc]',
	az2ph2_dsc = '$_POST[az2ph2_dsc]',
	allow_special_instruction = '$_POST[allow_special_instruction]',
	bill_to    = '$_POST[bill_to]',
	axial_mini_somo_dsc = '$_POST[axial_mini_somo_dsc]',
	axial_mini_hko_dsc  = '$_POST[axial_mini_hko_dsc]',
	image_dsc  = '$_POST[image_dsc]',
	bbg_1_dsc  = '$_POST[bbg_1_dsc]',
	 depot_number  ='$_POST[depot_number]', 
	 ff_by_iot_dsc ='$_POST[ff_by_iot_dsc]', 
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
	 identity_dsc = '$_POST[identity_dsc]', 
	 younger_prog_dsc  ='$_POST[younger_prog_dsc]', 	
	 ovation_dsc  ='$_POST[ovation_dsc]', 	 
	 svision_dsc  ='$_POST[svision_dsc]', 
	 svision_2_dsc  ='$_POST[svision_2_dsc]', 
	 svision_3_dsc  ='$_POST[svision_3_dsc]', 
	  selection_rx_dsc  ='$_POST[selection_rx_dsc]', 
	 nesp_dsc  ='$_POST[nesp_dsc]', 
	 conant_dsc  ='$_POST[conant_dsc]', 
	 private_grm_1_dsc  ='$_POST[private_grm_1_dsc]', 
	 private_grm_2_dsc  ='$_POST[private_grm_2_dsc]', 
	  private_grm_3_dsc  ='$_POST[private_grm_3_dsc]', 
	  innovative_plus_dsc  ='$_POST[innovative_plus_dsc]', 
	  innovation_ff_hd_159_dsc = '$_POST[innovation_ff_hd_159_dsc]', 
	  innovation_ff_159_dsc = '$_POST[innovation_ff_159_dsc]', 
	 optovision_dsc  ='$_POST[optovision_dsc]', 
	private_6_dsc ='$_POST[private_6_dsc]',
	 private_7_dsc ='$_POST[private_7_dsc]', 
	  innovation_ff_hd_dsc = '$_POST[innovation_ff_hd_dsc]' , 
	  innovation_ii_ds_dsc = '$_POST[innovation_ii_ds_dsc]' ,
	  innovation_ds_dsc = '$_POST[innovation_ds_dsc]',
	  innovation_ff_dsc = '$_POST[innovation_ff_dsc]',
	  coupon_code = '$_POST[coupon_code]' ,
	   offer = '$_POST[offer]', 
	   language = '$_POST[language]',  
	   email_notification = '$_POST[email_notification]',
	    title='$_POST[title]',
		 first_name='$_POST[first_name]',
		  last_name='$_POST[last_name]',
		   company='$_POST[company]',
		    business_type='$_POST[business_type]',
			 buying_group='$_POST[buying_group]',
			  VAT_no='$_POST[VAT_no]',
			   bill_address1='$_POST[bill_address1]', bill_address2='$_POST[bill_address2]', bill_city='$_POST[bill_city]', bill_state='$_POST[bill_state]', bill_zip='$_POST[bill_zip]', bill_country='$_POST[bill_country]', ship_address1='$_POST[ship_address1]', ship_address2='$_POST[ship_address2]', ship_city='$_POST[ship_city]', ship_state='$_POST[ship_state]', ship_zip='$_POST[ship_zip]', ship_country='$_POST[ship_country]', phone='$_POST[phone]', other_phone='$_POST[other_phone]', fax='$_POST[fax]', email='$_POST[email]', password='$_POST[password]', approved='$_POST[approved]', currency='$_POST[currency]', purchase_unit='$_POST[purchase_unit]', innovative_dsc='$_POST[innovative_dsc]', infocus_dsc='$_POST[infocus_dsc]', precision_dsc='$_POST[precision_dsc]', visionpro_dsc='$_POST[visionpro_dsc]', visionpropoly_dsc='$_POST[visionpropoly_dsc]', visioneco_dsc='$_POST[visioneco_dsc]', generation_dsc='$_POST[generation_dsc]', truehd_dsc='$_POST[truehd_dsc]', easy_fit_dsc='$_POST[easy_fit_dsc]', private_1_dsc='$_POST[private_1_dsc]', private_2_dsc='$_POST[private_2_dsc]', private_3_dsc='$_POST[private_3_dsc]', private_4_dsc='$_POST[private_4_dsc]', private_5_dsc='$_POST[private_5_dsc]', vot_dsc='$_POST[vot_dsc]',glass_dsc='$_POST[glass_dsc]',glass_2_dsc='$_POST[glass_2_dsc]', glass_3_dsc='$_POST[glass_3_dsc]', eco_dsc='$_POST[eco_dsc]', rodenstock_dsc='$_POST[rodenstock_dsc]', rodenstock_hd_dsc='$_POST[rodenstock_hd_dsc]',credit_hold='$_POST[credit_hold]',display_double_warning='$_POST[display_double_warning]',sales_commission='$_POST[mycommission]' WHERE primary_key = '$pkey'";

	
	
	$result=mysql_query($query)			or die  ('I cannot update account because: ' . mysql_error());
		

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
		$queryAccountType = "SELECT product_line, main_lab, language, business_type, user_id, pay_credit_card   from accounts where primary_key = '$pkey'" ;
		$resultAcctType   = mysql_query($queryAccountType) or die(mysql_error());		
		$DataAcctType 	  = mysql_fetch_assoc($resultAcctType);
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
			
			
		case 44://Lens net Pacific
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 42://Lens net Italia
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 43://DirectLab Pacific
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 41://DirectLab USA
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 40://DirectLab Italia
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 36://DirectLab Atlantic
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 35://Somo ClearI
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		
		case 21://Directlab Trois-Rivieres
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 22://Directlab Drummondville
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 1://VOT
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 3://Directlab Saint-Catharines
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
		case 46://Directlab Illinois
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
		
				
		case 29://Lens net Ontario
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 59://Safety Jean-Nicolas Boisvert
		$query_rs = "UPDATE accounts set sales_rep  = 32 WHERE primary_key ='$pkey' ";//SALES REP = JNC for this lab
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 32://Lens net USA
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 66://Entrepots QC
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 67://Warehouses Canada
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 59://SAFETY
		$query_rs = "UPDATE accounts set shipping_code = 'OR005DLN' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 47://Ait lens club
		$query_rs = "UPDATE accounts set shipping_code = 'OR005US' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
		
		$query_rs = "UPDATE accounts set bill_to = 'B00024' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;
		
		case 50://Directlab Eagle
		$query_rs = "UPDATE accounts set shipping_code = 'OR005US' WHERE primary_key ='$pkey' ";
		$rs = mysql_query($query_rs) or die(mysql_error());
        break;

		}
		
		
			if ($Product_line == "ifcclub")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','101')"; //IFC Simple
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','103')"; //IFC Progressif court 	
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','104')"; //IFC Progressif long
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','108')"; //Verres
			$rs = mysql_query($query_rs) or die(mysql_error());

			
			}//End IF account = IFC CLUB
		
		
		
		
		
			if ($Product_line == "ifcclubca"){
			
				//First we delete all the collections that could have been activated for this account
				$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
				$listCol = mysql_query($queryCol) or die(mysql_error());
	
				//Then we activate ifc club ca  collections for every customer
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','161')"; //FT IFC
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','162')"; //IFC Crystal
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','163')"; //IFC SteCath
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','164')"; //IFC Swiss
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','165')"; //SV IFC
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','126')"; //NURBS sunglasses
				$rs = mysql_query($query_rs) or die(mysql_error());

			}//End IF account = IFC CLUB CA  
		
		
		
		if ($Product_line == "safety")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','138')"; //Safety
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','172')"; //Safety HKO
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','189')"; //Safety STC
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			//Then we activate ifc club collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','191')"; //Safety Swiss
			$rs = mysql_query($query_rs) or die(mysql_error());
			}//Fin Safety
			
			
			
			if ($Product_line == "eye-recommend")
			{
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
			
			//Then we activate Eye Recommend collections
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','174')"; //ER Swiss
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','175')"; //ER Crystal
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','176')"; //ER HKO
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','177')"; //ER TR
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','190')"; //ER STC
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','192')"; //ER CSC
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','193')"; //ER Versano
			$rs = mysql_query($query_rs) or die(mysql_error());
			}//Fin Eye Recommend
		
		

		if ($Product_line == "lensnetclub")
			{
				//First we delete all the collections that could have been activated for this account
				$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
				$listCol = mysql_query($queryCol) or die(mysql_error());
					
				//Then we activate lens net collections 2017
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','199')";//Innovative 1
				$rs = mysql_query($query_rs) or die(mysql_error());
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','200')";//Innovative 2
				$rs = mysql_query($query_rs) or die(mysql_error());
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','201')";//Innovative 3
				$rs = mysql_query($query_rs) or die(mysql_error());
	
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','205')";//LNC GKC
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','206')";//LNC STC
				$rs = mysql_query($query_rs) or die(mysql_error());
				
				$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','207')";//LNC HKO
				$rs = mysql_query($query_rs) or die(mysql_error());

				
			}//End IF account = LENS NET CLUB
			
			
			
			  if ($Product_line == "directlens"){
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
			
		   //Then we activate Direct-Lens collections   
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','197')"; // VOT DL
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','196')"; // CSC DL
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
		    $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','195')"; // STC DL
			$rs = mysql_query($query_rs) or die(mysql_error());
		  			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','173')"; // STC EXTRA CHARGES
			$rs = mysql_query($query_rs) or die(mysql_error());	
						
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','148')"; // Optimize 3
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','152')"; // Horizon
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','153')"; // Fit
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','139')"; // Optimize
			$rs = mysql_query($query_rs) or die(mysql_error());
			
		   $query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','105')"; // Ovation
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			
		   	$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','24')"; // Private 4
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','1')"; // Easy Fit HD
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','117')"; // Identity
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','4')"; //Precision
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','14')"; //My World
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','5')"; //TrueHD
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','21')"; //Private 3 = Easy One
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','6')"; //Vision Pro
			$rs = mysql_query($query_rs) or die(mysql_error());
		   
		   	$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','88')"; //Svision
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','99')"; //Selection Rx
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','12')"; //Vision pro poly
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','22')"; // Glass
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','28')"; // Glass 2
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','31')"; // Glass 3
			$rs = mysql_query($query_rs) or die(mysql_error());	



		switch($mainLab) {
		
		case 22://Dlab Drummond
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 50://Dlab Eagle
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','147')"; // Eagle Extra Charges
		$rs = mysql_query($query_rs) or die(mysql_error());
		break;
	   
   		case 21://Dlab TR
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 39://Dlab Rive-Sud
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 43://Dlab Pacific
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 36://Dlab Atlantic
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 41://Dlab Atlantic
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Private 5
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;
		
		case 3://Dlab St. catharines
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','13')"; // Other
		$rs = mysql_query($query_rs) or die(mysql_error());	
		break;

		}


		   }
		   
			
	
		
		
		
		if ($Product_line == "aitlensclub")
			{
				
			//DELETE ALL STOCK COLLECTIONS FOR USER		
			$query="DELETE FROM accounts_stock_collections WHERE accounts_id='$pkey'";
			$result=mysql_query($query)		or die ("ERROR" . mysql_error());
			//First we delete all the collections that could have been activated for this account
			$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
			$listCol = mysql_query($queryCol) or die(mysql_error());
		
			//Then we activate AIT collections
			$query_rs = "insert into accounts_stock_collections (accounts_id,stock_collections_id ) values  ('".$pkey."','1')"; //Somo Stock
			$rs = mysql_query($query_rs) or die(mysql_error());
					
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','187')"; //Versano AIT
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','159')"; //Eco AIT
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','185')"; //Universal 2
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','186')"; //Universal 2 174 transitions
			$rs = mysql_query($query_rs) or die(mysql_error());
			
	
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','147')"; // Eagle Extra Charges
			$rs = mysql_query($query_rs) or die(mysql_error());	
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','128')";//Nesp 2
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','127')";//Innovation FF HD 2
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','92')";//Nesp
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','115')";//Innovation FF 159
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','118')";//Image
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','116')";//Innovation FF HD 159
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','32')";//Eco 1
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','34')";//Eco 3
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','58')";//Eco 7
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','60')";//Innovation FF
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','61')";//Innovation DS
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','62')";//Innovation II DS
			$rs = mysql_query($query_rs) or die(mysql_error());

			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','63')";//Innovation FF HD
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','109')";//Eco Essilor
			$rs = mysql_query($query_rs) or die(mysql_error());
						
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','98')";//Innovative Plus
			$rs = mysql_query($query_rs) or die(mysql_error());
			
			}//End IF account = Ait  lens CLUB



		if ($mainLab==25){//It's an HKO customer, we need to desactivate all the collections, and activate only Private Hko
		
		//First we delete all the collections that were active for this account
		$queryCol = "delete FROM acct_collections where acct_id = '".$pkey."'";
		$listCol = mysql_query($queryCol) or die(mysql_error());
		
		//Then we activate Private Hko
		$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$pkey."','125')";
		$rs = mysql_query($query_rs) or die(mysql_error());
		}
		
		
	}
	return ($pkey);
}

function sendEmail(){/* sends the emails */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$message="Thank you for opening an account with us. Your account has been approved. For your records, your login is $_POST[user_id]. Your password is $_POST[password]. Please keep this information in a safe place. We look forward to serving you.\r\n";
$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';
$subject='New account approved  :'.$curTime;
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
		$resultCreditLimit = mysql_query($QueryCreditLimite)	or die ("ERROR" . mysql_error());
	break;
	//1000$ pour les autres
	case 'Optician Office': 		
		$CreditLimitEn 	   = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysql_query($QueryCreditLimite)	or die ("ERROR" . mysql_error());
	break;	
	case 'Lab': 					
		$CreditLimitEn 	   = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysql_query($QueryCreditLimite)	or die ("ERROR" . mysql_error());
	break;	
	case 'Laboratorio': 			 
		$CreditLimitEn     = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
		$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
		$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
		$resultCreditLimit = mysql_query($QueryCreditLimite)	or die ("ERROR" . mysql_error()); 
	break;		
	default:	
	$CreditLimitEn     = "Your account had been pre-approved to $1000. Should you require a higher limit, please contact us at 1-855-770-2124."; 
	$CreditLimitFr 	   = "Votre compte a &eacute;t&eacute; pr&eacute;-approuv&eacute avec une limite de 1000$. Si vous souhaitez l'augmenter, veuillez nous contacter au 1-855-770-2124."; 	
	$QueryCreditLimite = "INSERT INTO acct_credit_limit (cl_user_id, cl_limit_amt,cl_date) VALUES ('$userId', 1000,'$datedujour')"; 
	$resultCreditLimit = mysql_query($QueryCreditLimite)	or die ("ERROR" . mysql_error());
}

if ($Pay_Credit_Card =='yes'){//Pas de limite de crédit s'il paie par carte de crédit
	$CreditLimitEn     		 = "You have checked the box <b>Statement Payment by Credit Card</b>. Please, herewith a credit card authorization form.<br><br> Please sign and email to: dbeaulieu@direct-lens.com or fax to 1-877-590-3522.<br> If you have any difficulties with this, please feel free to contact our Customer Service at 1-855-770-2124.<br>
	<a href=\"".constant('DIRECT_LENS_URL')."/lensnet/pdf/credit_card_authorization_form.pdf\">Click here to download the form</a>"; 
	$CreditLimitFr 	  	 	 = "Vous avez coch&eacute; la case <b>&eacute;tat de compte pay&eacute; par carte de cr&eacute;dit</b>. Vous trouverez ci-joint le formulaire d'autorisation de paiement par carte de cr&eacute;dit.<br><br> Veuillez le remplir et nous le faire parvenir par courriel &agrave; dbeaulieu@direct-lens.com ou par fax au 1-877-590-3522. <br><br>Si vous &eacute;prouvez des difficult&eacute;s, veuillez contacter le Service &agrave; la client&egrave;le au  1-877-570-3522.<br><a href=\"https://www.direct-lens.com/lensnet/pdf/formulaire_autorisation_carte_credit.pdf\">Cliquer ici pour télécharger  le formulaire</a>"; 
	$QueryDeleteCreditLimite = "DELETE FROM acct_credit_limit WHERE cl_user_id = '$userId'"; 
	//echo '<br>'. $QueryDeleteCreditLimite. '<br>';
	$resultCreditLimit 	     = mysql_query($QueryDeleteCreditLimite)	or die ("ERROR" . mysql_error());	
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




function sendEmailEyeRecommend(){/* sends the welcome  email for LensnetClub */
$arrayEmail 	 = str_split($_POST[email],50);
$send_to_address = $arrayEmail;
$message="Thank you for opening an account through Direct-lens.com. Welcome to exclusive products and great value! Please login with your Username and Password below.<br>Your Username is $_POST[user_id]. Your password is $_POST[password]. \r\n";
$subject='New Direct-lens.com account approved  :'.$curTime;
$curTime= date("m-d-Y");	
$to_address=$arrayEmail;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}



function sendEmailMarjorie(){/* sends the emails */
$QueryMainlab = "Select lab_name from labs where primary_key = (Select main_lab from accounts where user_id = '".$_POST[user_id] . "')";
$rs = mysql_query($QueryMainlab) or die(mysql_error());
$DataMainlab=mysql_fetch_array($rs);
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

	$queryLab="SELECT lab from orders where order_num = '$_POST[order_num]'";
	//echo 'querylab:'. $queryLab;
	$resultLab=mysql_query($queryLab)	or die ('Could not issue memo because: ' . mysql_error());
	$DataLab=mysql_fetch_array($resultLab);
	$Id_Lab = $DataLab[lab];



 if ($Id_Lab == 21) //Lab = TR, on accepte immediatement le credit/debit
 {
	$query="INSERT into memo_credits (mcred_order_num, mcred_acct_user_id, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date, pat_ref_num, patient_first_name, patient_last_name, mcred_detail) VALUES ('$_POST[order_num]', '$_POST[acct_user_id]', '$_POST[lastMemoNum]', '$_POST[mcred_cred_type]', '$_POST[mcred_disc_type]', '$_POST[mcred_amount]', '$mcred_abs_amount', '$_POST[mcred_memo_code]', '$mcred_date', '$_POST[patient_ref_num]', '$_POST[order_patient_first]', '$_POST[order_patient_last]','$_POST[mcred_detail]')";
	$result=mysql_query($query)		or die ('Could not issue memo because: ' . mysql_error());
	 //If cred type = debit  
 }else if ($_POST[mcred_cred_type] == 'debit'){  
  //We accept the credit/debit and insert into the database
 $query="INSERT into memo_credits (mcred_order_num, mcred_acct_user_id, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date, pat_ref_num, patient_first_name, patient_last_name, mcred_detail) VALUES ('$_POST[order_num]', '$_POST[acct_user_id]', '$_POST[lastMemoNum]', '$_POST[mcred_cred_type]', '$_POST[mcred_disc_type]', '$_POST[mcred_amount]', '$mcred_abs_amount', '$_POST[mcred_memo_code]', '$mcred_date', '$_POST[patient_ref_num]', '$_POST[order_patient_first]', '$_POST[order_patient_last]','$_POST[mcred_detail]')";
	$result=mysql_query($query)		or die ('Could not issue memo because: ' . mysql_error());
 }else{

  //Insert into temporary table, to wait for validation from administration
  $query="INSERT into memo_credits_temp (mcred_order_num, mcred_acct_user_id, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date, pat_ref_num, patient_first_name, patient_last_name,optipoints_to_substract, optipoints_reason, mcred_detail) VALUES ('$_POST[order_num]', '$_POST[acct_user_id]', '$_POST[lastMemoNum]', '$_POST[mcred_cred_type]', '$_POST[mcred_disc_type]', '$_POST[mcred_amount]', '$mcred_abs_amount', '$_POST[mcred_memo_code]', '$mcred_date', '$_POST[patient_ref_num]', '$_POST[order_patient_first]', '$_POST[order_patient_last]','$_POST[optipoints_to_substract]','$_POST[optipoints_reason]','$_POST[mcred_detail]')";
 $result=mysql_query($query)		or die ('Could not issue memo because: ' . mysql_error());
 
 //email send request to dbouffard  moi et marco
$send_to_address=array('dbeaulieu@direct-lens.com');
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject = "Nouvelle demande d'approbation de credit :".$curTime;
$message = "Une nouvelle demande de credit est en attente d'approbation. Vous pouvez y repondre en vous connectant dans le <a href=\"".constant('DIRECT_LENS_URL')."/admin\">Main Admin</a>. Merci!";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	
return true;
 }//end else

 }//end function



function email_memo_credit(){//Email memo credit to customer				
	$subject = "Direct Lens Memo Order for Order Number: $_POST[order_num]";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MEMO ORDER</title>';
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" /></head><body>";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="https://'.constant('AWS_S3_BUCKET').'.s3.amazonaws.com/direct-lens/logos/'.$_SESSION[labAdminData][logo_file].'"/></td><td align="center"><img src="https://'.constant('AWS_S3_BUCKET').'.s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif" width="200" height="60" /></td></tr></table>';
	
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

	$arrayEmail = str_split($_POST[customer_email],50);
	$curTime= date("m-d-Y");	
	$to_address = $arrayEmail;
	$from_address='donotreply@entrepotdelalunette.com';
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("Memo Credit $_POST[mcred_memo_num] sent to customer",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("Memo Credit $_POST[mcred_memo_num] sent to customer",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
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

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
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

?>
