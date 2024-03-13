<?php 

function getUserEmail($user_id){

	$query="SELECT email FROM accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$email=$listItem[email];

	return $email;
}

function getNewOrderNum(){
	
	include "../sec_connectEDLL.inc.php";
	$query="select * from last_order_num WHERE primary_key='1'";
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$order_num=$listItem[last_order_num]+1;
	
	$query="UPDATE last_order_num SET last_order_num='$order_num' WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));

	$_SESSION['PrescrData']['myordnum']=$order_num; ///////////// pt 10/21/10 for xml uploader

	return $order_num;
}

function addOrderNumShiptoOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){

$order_date_processed = date("Y-m-d");
$order_status         = "processing";
$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	//echo $query;
	
}


function addOrderNumShiptoFrameOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){

$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type='frame_stock_tray'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	
}


function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){

include "../sec_connectEDLL.inc.php";
	

$order_date_processed=date("Y-m-d");
$order_status="processing";
$query="SELECT  user_id,primary_key,order_product_name, order_product_coating,order_product_id, coupon_dsc, warranty FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
$result=mysqli_query($con,$query)	or die ('Could not update because: '  . mysqli_error($con));
		
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		

		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];



//Insertion dans Swiss_edging_barcode
$queryEdgingSwiss = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($orderNum)";
$resultEdgingSwiss=mysqli_query($con, $queryEdgingSwiss)	or die ('Could not update because: '  . mysqli_error($con));


//1- Traitement des garanties (En attente de savoir si on conserve cette partie)	
	switch ($listItem[warranty]){
		case "0":		$promo_points_warranty	 = "0"; 	break;
		case "1":		$promo_points_warranty	 = "0"; 	break;
		case "2":		$promo_points_warranty   = "10";  	break;
		default: 		$promo_points_warranty	 = "0";		break;
	}
	

	//2- Traitement des Loyalty Program  et coating sur la commande
	$queryLoyalty   = "SELECT loyalty_program FROM accounts WHERE user_id = '$listItem[user_id]'";
	$resultLoyalty  = mysqli_query($con,$queryLoyalty)	or die ('Could not update because: '  . mysqli_error($con));
	$DataLoyalty    = mysqli_fetch_array($resultLoyalty,MYSQLI_ASSOC);
	$LoyaltyProgram = $DataLoyalty[loyalty_program];
	$ClientaDroitauBonusOptipoints = 'non';
	
	
	 	if (($promo_points_warranty > 0) && ($LoyaltyProgram <> 'none')){
			$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
			$datecomplete = date("Y/m/d", $ladate);
			
			$query="select  lnc_reward_points,company from accounts WHERE user_id  = '$listItem[user_id]'";
			$acctResult=mysqli_query($con,$query)	or die ("Could not find account");
			$Data=mysqli_fetch_array($acctResult,MYSQLI_ASSOC);
			
			$nouveauTotal = $promo_points_warranty + $Data[lnc_reward_points];
			$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
			$resultUpdate=mysqli_query($con,$queryUpdate)		or die (mysqli_error($con));
			
			//Insert in lnc_reward_history and update point in the customer's account
			$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, detail_fr, amount, datetime,user_id, order_num) VALUES ('','Warranty: $listItem[warranty] years ','Garantie: $listItem[warranty] ans ','$promo_points_warranty', '$datecomplete', '$listItem[user_id]', '$orderNum')" ;
			$resultinsert=mysqli_query($con,$queryInsert)		or die (mysqli_error($con) . $queryInsert);
		}	
	
	

		
	switch($LoyaltyProgram){
		case "platinum": $OptiPointReward = 20; $ProgramDetail = 'Platinum'; $ClientaDroitauBonusOptipoints = 'oui';    break;
		case "gold":  	 $OptiPointReward = 10; $ProgramDetail = 'Gold';     $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "silver":   $OptiPointReward =  5; $ProgramDetail = 'Silver';   $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "none":     $OptiPointReward =  0; $ProgramDetail = '&nbsp;'; 	 $ClientaDroitauBonusOptipoints = 'non';	break;
	}
		
		
		if (($OptiPointReward > 0) && ($ClientaDroitauBonusOptipoints == 'oui')){//Le client fait partie d'un programme de loyaut� donc on �value le coating
			
			switch($listItem[order_product_coating]){
				//Donne des optipoints (Selon le Loyalty Program)
				case "Dream AR":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "Smart AR":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
				case "StressFree":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "StressFree 32":		$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "StressFree Noflex":	$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
				case "ITO AR":				$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "Xlr":					$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
				case "MaxiiVue":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "CrizalF":				$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
				case "Blue AR":				$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "Aqua Dream AR":		$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
				case "MultiClear AR":		$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				case "Iblu":				$ClientaDroitauBonusOptipoints = 'oui'; 	break;
				//Ne donne aucun optipoints (Peu importe le Loyalty Program)
				case "Hard Coat":			$ClientaDroitauBonusOptipoints = 'non'; 	break;
				case "HC":					$ClientaDroitauBonusOptipoints = 'non'; 	break;
				case "DH1":					$ClientaDroitauBonusOptipoints = 'non'; 	break;
				case "DH2":					$ClientaDroitauBonusOptipoints = 'non';		break;
				case "Uncoated":			$ClientaDroitauBonusOptipoints = 'non';		break;	
			}//End Switch
			
			$queryLensCategory  = "SELECT lens_category  FROM  ifc_ca_exclusive WHERE primary_key  = $listItem[order_product_id]";
			$resultLensCategory = mysqli_query($con,$queryLensCategory)	or die ("Could not find account");
			$DataLensCategory   = mysqli_fetch_array($resultLensCategory,MYSQLI_ASSOC);
			$Lens_Category 	    = $DataLensCategory[lens_category];			
			
			if ($ClientaDroitauBonusOptipoints == 'oui'){
				//Selon le lens_category du produit command�
				switch($Lens_Category){
					//Donne des optipoints
					case "prog ff":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
					case "prog ds":			$ClientaDroitauBonusOptipoints = 'oui'; 	break;	
					//Ne donnent pas aucun Optipoints
					case "sv":				$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "prog 20":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "prog 16":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "prog 14":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "bifocal":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "prog cl":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
					case "glass":			$ClientaDroitauBonusOptipoints = 'non'; 	break;	
				}//End Switch
			}//END IF

			if (($ClientaDroitauBonusOptipoints == 'oui') && ($OptiPointReward > 0)){
				//Insertion dans lnc_reward_history et mise a jour nombre d'optipoints du Compte Client
				$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
				$datecomplete = date("Y/m/d", $ladate);
			
				$query="select  lnc_reward_points,company from accounts WHERE user_id  = '$listItem[user_id]'";
				$acctResult=mysqli_query($con,$query)	or die ("Could not find account");
				$Data=mysqli_fetch_array($acctResult,MYSQLI_ASSOC);
				
				$nouveauTotal = $OptiPointReward + $Data[lnc_reward_points];
				$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
				$resultUpdate=mysqli_query($con,$queryUpdate)		or die (mysqli_error($con));
				
				//Insert in lnc_reward_history and update point in the customer's account
				$OptipointDetail = "Product $listItem[order_product_name]";
				$Detail_fr = "Produit $listItem[order_product_name]";
				$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, detail_fr, amount, datetime,user_id, order_num, loyalty_program) VALUES ('','$OptipointDetail','$Detail_fr','$OptiPointReward', '$datecomplete', '$listItem[user_id]','$orderNum','$ProgramDetail')" ;
				$resultinsert=mysqli_query($con,$queryInsert)		or die (mysqli_error($con) . $queryInsert);
			}
			
			
		}//End IF



	//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
	$order_date_processed = date("Y-m-d");
	$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
	addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	//echo $query2;	
	$result2=mysqli_query($con,$query2)		or die ('Could not update because: ' . mysqli_error($con));
		

	//Code rajout� par Charles 2010-07-22
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	//$timeAfterOneHour = $currentTime-((60*60)*4);	
	$timeAfterOneHour = $currentTime-((60*60)*5);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	
	//Update status history with the customer ip address ad the ip
	$queryStatus="INSERT INTO status_history (order_num,order_status,update_time, update_type,update_ip) VALUES($orderNum,'processing','$datecomplete','manual','$ip') ";
	$resultStatus=mysqli_query($con,$queryStatus)	or die  ('I  cannot Insert into status history because: ' . mysqli_error($con));
				
	$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
	$e_result=mysqli_query($con,$e_query)		or die ('Could not update because: ' . mysqli_error($con));
		
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);	
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
		
		$discAmount=bcmul(.02, $gTotal, 2);
		$subAmount2 = bcsub($gTotal, $discAmount, 2);
		$amount=bcadd($subAmount2, $shipCost, 2);
	
		$msg=sendPmtConfirmEmail($gTotal, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
	}
	
	
	//UPDATE IFC FRAMES INVENTORY
	include_once 'includes/ifc_inventory_func.inc.php';
	//updateIFCInventory($orderNum);//DOIT rester en commentaire sinon l'inventaire sera Impact� par les commandes Package
	Automatic_ReOrder_OPB($orderNum);
	Automatic_ReOrder_Armourx($orderNum);
	
		
		
	$lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query6="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result6=mysqli_query($con,$query6)	or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItemML=mysqli_fetch_array($result6,MYSQLI_ASSOC);
	
	if ($listItemML[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray=str_split($listItemML[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
	//sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"true",$faxNum);//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
	}
	
	
	//COURRIEL DE CONFIRMATION ENVOY� AUX ENTREPOTS		
	//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
	
	$emailtest = "dbeaulieu@direct-lens.com";
	//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$emailtest,$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATION TO MAIN LAB
	
	$emailtest = "dbeaulieu@direct-lens.com";
	//sendPrescriptionConfirmationEn($listItemML[lab_email],$listItemML[logo_file],$orderNum,$emailtest,$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATION TO MAIN LAB
	


	$queryLangue = "SELECT language  FROM accounts WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
	$resultLangue=mysqli_query($con,$queryLangue)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataLangue=mysqli_fetch_array($resultLangue,MYSQLI_ASSOC);
	
	 if ($DataLangue['language'] == 'french'){
		//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
 }else {
		//sendPrescriptionConfirmationEn($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
 }
	
	
		
	$product_name=$listItem[order_product_name];
		
	$query3="SELECT collection FROM ifc_ca_exclusive WHERE product_name='$product_name'";
	$result3=mysqli_query($con,$query3) or die ('Could not update because: ' . mysqli_error($con));
		
		$listItem2=mysqli_fetch_array($result3,MYSQLI_ASSOC);
		$collection=$listItem2[collection];
		
		if (($collection=="Other")||($collection=="IFC Simple")||($collection=="Verres") ||($collection=="IFC Progressif court")||($collection=="IFC Progressif long")){
			$lab_id=$_SESSION["sessionUserData"]["main_lab"];
			$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT other_lab from labs WHERE primary_key='$lab_id')";//LOOK UP OTHER LAB EMAIL ADDRESS
			$result5=mysqli_query($con,$query5) or die  ('I cannot select items because: ' . mysqli_error($con));
			$listItem=mysqli_fetch_array($result5,MYSQLI_ASSOC);
		
			if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
				$faxNumArray=str_split($listItem[fax]);
				$numCount=count($faxNumArray);
				$faxNum="";
					for ($i=0;$i<$numCount;$i++){
						if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
						}
					}
				//sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);//SEND CONFIRMATIOn TO OTHER LAB
			}
			
		
			//sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO OTHER LAB
			}
		
		
		

		
		}//END WHILE
//uploadfinish();



}//END FUNCTION


function add_Pmt_Marker($user_id, $order_num, $gTotal){/* Set payment marker to show order as PAID */
	$transData=$_SESSION["transData"];
	$today = date("Y-m-d");
	$discAmount=bcmul(.02, $gTotal, 2);
	$subAmount2 = bcsub($gTotal, $discAmount, 2);
	$amount=bcadd($subAmount2, $shipCost, 2);
	$query="INSERT into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$transData[cclast4]', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";

	$result=mysqli_query($con,$query) or die ("could not add marker " . mysqli_error($con));
		
	return true;
}

function add_Order_Ref($Master_Order_ID, $order_num){/* Set Master Order ID for this order number */
	$query="INSERT into order_num_master_id_ref (ref_master_id, ref_order_num) values ('$Master_Order_ID', '$order_num')";

	$result=mysqli_query($con,$query) or die ("could not add order reference " . mysqli_error($con));
		
	return true;
}
?>
