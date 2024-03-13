<?php 

function getUserEmail($user_id){

	$query="select email from accounts WHERE user_id='$user_id'";$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$email=$listItem[email];

	return $email;
}

function getNewOrderNum(){
	$query="select * from last_order_num WHERE primary_key='1'";
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$order_num=$listItem[last_order_num]+1;
	
	$query="UPDATE last_order_num SET last_order_num='$order_num' WHERE primary_key='1'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());


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
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
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
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
	//echo $query;
	
}


function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){
$order_date_processed  = date("Y-m-d", strtotime('-5 hours', time()));//Compense pour le 5h de d�callage avec le B
//$order_date_processed=date("Y-m-d");
$order_status="processing";
$query="SELECT  user_id,primary_key,order_product_name, order_product_coating,order_product_id, coupon_dsc, warranty FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
$result=mysql_query($query)	or die ('Could not update because: '  . mysql_error());
		
while ($listItem=mysql_fetch_array($result)){
		

		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];



//Insertion dans Swiss_edging_barcode
$queryEdgingSwiss = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($orderNum)";
$resultEdgingSwiss=mysql_query($queryEdgingSwiss)	or die ('Could not update because: '  . mysql_error());


//1- Traitement des garanties (En attente de savoir si on conserve cette partie)	
	switch ($listItem[warranty]){
		case "0":		$promo_points_warranty	 = "0"; 	break;
		case "1":		$promo_points_warranty	 = "0"; 	break;
		case "2":		$promo_points_warranty   = "10";  	break;
		default: 		$promo_points_warranty	 = "0";		break;
	}
	

	//2- Traitement des Loyalty Program  et coating sur la commande
	$queryLoyalty   = "SELECT loyalty_program FROM accounts WHERE user_id = '$listItem[user_id]'";
	$resultLoyalty  = mysql_query($queryLoyalty)	or die ('Could not update because: '  . mysql_error());
	$DataLoyalty    = mysql_fetch_array($resultLoyalty);
	$LoyaltyProgram = $DataLoyalty[loyalty_program];
	$ClientaDroitauBonusOptipoints = 'non';
	
	
	 	if (($promo_points_warranty > 0) && ($LoyaltyProgram <> 'none')){
			$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
			$datecomplete = date("Y/m/d", $ladate);
			
			$query="select  lnc_reward_points,company from accounts WHERE user_id  = '$listItem[user_id]'";
			$acctResult=mysql_query($query)	or die ("Could not find account");
			$Data=mysql_fetch_array($acctResult);
			
			$nouveauTotal = $promo_points_warranty + $Data[lnc_reward_points];
			$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
			$resultUpdate=mysql_query($queryUpdate)		or die (mysql_error());
			
			//Insert in lnc_reward_history and update point in the customer's account
			$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, detail_fr, amount, datetime,user_id, order_num) VALUES ('','Warranty: $listItem[warranty] years ','Garantie: $listItem[warranty] ans ','$promo_points_warranty', '$datecomplete', '$listItem[user_id]', '$orderNum')" ;
			$resultinsert=mysql_query($queryInsert)		or die (mysql_error() . $queryInsert);
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
			$resultLensCategory = mysql_query($queryLensCategory)	or die ("Could not find account");
			$DataLensCategory   = mysql_fetch_array($resultLensCategory);
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
				$acctResult=mysql_query($query)	or die ("Could not find account");
				$Data=mysql_fetch_array($acctResult);
				
				$nouveauTotal = $OptiPointReward + $Data[lnc_reward_points];
				$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
				$resultUpdate=mysql_query($queryUpdate)		or die (mysql_error());
				
				//Insert in lnc_reward_history and update point in the customer's account
				$OptipointDetail = "Product $listItem[order_product_name]";
				$Detail_fr = "Produit $listItem[order_product_name]";
				$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, detail_fr, amount, datetime,user_id, order_num, loyalty_program) VALUES ('','$OptipointDetail','$Detail_fr','$OptiPointReward', '$datecomplete', '$listItem[user_id]','$orderNum','$ProgramDetail')" ;
				$resultinsert=mysql_query($queryInsert)		or die (mysql_error() . $queryInsert);
			}
			
			
		}//End IF



		//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
		$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
		addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	$result2=mysql_query($query2)		or die ('Could not update because: ' . mysql_error());
		

	//Code rajout� par Charles 2010-07-22
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	
	//Update status history with the customer ip address ad the ip
	$queryStatus="INSERT INTO status_history (order_num,order_status,update_time, update_type,update_ip) VALUES($orderNum,'processing','$datecomplete','manual','$ip') ";
	$resultStatus=mysql_query($queryStatus)	or die  ('I  cannot Insert into status history because: ' . mysql_error());
		
		
		
	$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
	$e_result=mysql_query($e_query)		or die ('Could not update because: ' . mysql_error());
		
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
	$result6=mysql_query($query6)	or die  ('I cannot select items because: ' . mysql_error());
	$listItemML=mysql_fetch_array($result6);
	
	if ($listItemML[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray=str_split($listItemML[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
	sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"true",$faxNum);//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
	}
	
	
	//COURRIEL DE CONFIRMATION ENVOY� AUX ENTREPOTS		
	sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
	
	$emailtest = "dbeaulieu@direct-lens.com";
	sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$emailtest,$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATION TO MAIN LAB
	
	$emailtest = "dbeaulieu@direct-lens.com";
	sendPrescriptionConfirmationEn($listItemML[lab_email],$listItemML[logo_file],$orderNum,$emailtest,$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATION TO MAIN LAB
	


	$queryLangue = "SELECT language  FROM accounts WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
	$resultLangue=mysql_query($queryLangue)		or die  ('I cannot select items because: ' . mysql_error());
	$DataLangue=mysql_fetch_array($resultLangue);
	
	 if ($DataLangue['language'] == 'french'){
		sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
 }else {
		sendPrescriptionConfirmationEn($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
 }
	
	
		
	$product_name=$listItem[order_product_name];
		
	$query3="SELECT collection FROM ifc_ca_exclusive WHERE product_name='$product_name'";
	$result3=mysql_query($query3)
		or die ('Could not update because: ' . mysql_error());
		
		$listItem2=mysql_fetch_array($result3);
		$collection=$listItem2[collection];
		
		if (($collection=="Other")||($collection=="IFC Simple")||($collection=="Verres") ||($collection=="IFC Progressif court")||($collection=="IFC Progressif long")){
			$lab_id=$_SESSION["sessionUserData"]["main_lab"];
			$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT other_lab from labs WHERE primary_key='$lab_id')";//LOOK UP OTHER LAB EMAIL ADDRESS
			$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
			$listItem=mysql_fetch_array($result5);
		
			if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
				$faxNumArray=str_split($listItem[fax]);
				$numCount=count($faxNumArray);
				$faxNum="";
					for ($i=0;$i<$numCount;$i++){
						if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
						}
					}
				sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);//SEND CONFIRMATIOn TO OTHER LAB
			}
			
		
			sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO OTHER LAB
			}
		
		
		
			
				if ($collection=="Fuglies"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT fuglies_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Fuglies LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Fuglies LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Fuglies LAB
			}//END IF Fuglies
		
		
		
		
			if ($collection=="FT IFC"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT ft_ifc_lab from labs WHERE primary_key='$lab_id')";//LOOK UP FT IFC LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO FT IFC LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO FT IFC LAB
			}//END IF FT IFC
			
			
			
			if ($collection=="IFC Crystal"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT ifc_crystal_lab from labs WHERE primary_key='$lab_id')";//LOOK UP IFC CRYSTAL LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO IFC CRYSTAL LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO IFC CRYSTAL LAB
			}//END IFIFC CRYSTAL
		
		
		
		
		
		if ($collection=="IFC SteCath"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT ifc_stecath_lab from labs WHERE primary_key='$lab_id')";//LOOK UP IFC SteCath LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO IFC SteCath LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO IFC SteCath LAB
			}//END IF IFC SteCath
			
			
			
			if ($collection=="IFC Swiss"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT ifc_swiss_lab from labs WHERE primary_key='$lab_id')";//LOOK UP IFC Swiss LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO IFC Swiss LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO IFC Swiss LAB
			}//END IF IFC Swiss
			
			
				if ($collection=="SV IFC"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT sv_ifc_lab from labs WHERE primary_key='$lab_id')";//LOOK UP SV IFC LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO SV IFC LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO SV IFC LAB
			}//END IF SV IFC
		
		
		
		
		
		
		
		
		
		if ($collection=="Entrepot Crystal"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_crystal_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot Crystal LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot Crystal  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot Crystal LAB
			}//END IF Entrepot Crystal 
			
			
			
			
			if ($collection=="Entrepot STC"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_stc_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot STC LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot STC  
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot STC LAB
			}//END IF Entrepot STC 
		
		
		
			
		if ($collection=="Entrepot HKO"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_hko_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot HKO LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot HKO   LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot HKO  LAB
			}//END IF Entrepot HKO  
		
			
		if ($collection=="Entrepot Swiss"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_swiss_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot Swiss LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot Swiss  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot Swiss LAB
			}//END IF Entrepot Swiss 
			
			
			if ($collection=="Entrepot CSC"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_csc_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot CSC LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot CSC  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot CSC LAB
			}//END IF Entrepot CSC 
		
		
		
			if ($collection=="Entrepot Promo"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_promo_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot Promo LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot Promo  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot Promo LAB
			}//END IF Entrepot Promo 
		
		
		
		if ($collection=="Entrepot SV"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_sv_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot SV LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot Swiss  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot SV LAB
			}//END IF Entrepot SV 
		
		
		
		if ($collection=="Entrepot FT"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_ft_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot SV LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot FT  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot FT LAB
			}//END IF Entrepot FT 
		
		
		if ($collection=="Entrepot DL"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_dl_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot DL LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot DL  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot DL LAB
			}//END IF Entrepot DL 
		
		
		if ($collection=="Nesp"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT nesp_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Nesp LAB EMAIL ADDRESS
				$result5=mysql_query($query5)			or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Nesp LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Nesp LAB
			}//END IF Nesp	
			
			
			
			
				if ($collection=="HD Premier Choix"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT hd_premier_choix_lab from labs WHERE primary_key='$lab_id')";//LOOK UP HD Premier Choix LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO HD Premier Choix LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO HD Premier Choix  LAB
			}//END IF HD Premier Choix

			
	
			if ($collection=="Selection Rx"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT selection_rx_lab from labs WHERE primary_key='$lab_id')";//LOOK UP selection rx LAB EMAIL ADDRESS
				$result5=mysql_query($query5)			or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO selection rx LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO selection rx LAB
			}//END IF selection rx	
			

			
			
				if ($collection=="Innovative Plus"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_plus_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Innovative +  LAB EMAIL ADDRESS
				$result5=mysql_query($query5)			or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO  Innovative +  LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO  Innovative +  LAB
			}//END IF  Innovative +	
			
			
			

		
		
		if ($collection=="Private 1"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT private_1_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Private 1 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 1 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO PRIVATE 1 LAB
			}//END IF PRIVATE 1	
			
					
		if ($collection=="Private 2"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT private_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 2 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}	
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 2 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO PRIVATE 2 LAB
			}//END IF PRIVATE 2 LAB	
			
		if ($collection=="Private 3"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT private_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 3 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 3 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO PRIVATE 3 LAB
			}//END IF PRIVATE 3
						
			
			
				if ($collection=="Private 4"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT private_4_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 3 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 4 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO PRIVATE 4 LAB
			}//END IF PRIVATE 4
			
			
			
			
	if ($collection=="Private 5"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT private_5_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 3 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 5 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO PRIVATE 5 LAB
			}//END IF PRIVATE 5
			
			
			
			
			
			
			
			if ($collection=="ClearI"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT cleari_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Cleari LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Cleari LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Cleari 1 LAB
			}//END IF Cleari	
			
			

			
			if ($collection=="Entrepot Sky"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT entrepot_sky_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Entrepot Sky LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Entrepot Sky LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Entrepot Sky LAB
			}//END IF Entrepot Sky
			
			
			
			
						
			if ($collection=="Svision"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT svision_lab from labs WHERE primary_key='$lab_id')";//LOOK UP svision LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO svision LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO svision LAB
			}//END IF svision
			
			
			
			
			
			
				if ($collection=="Svision 2"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT svision_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP svision 2 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO svision 2 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO svision LAB
			}//END IF svision 2
			
			
			
			
			
				if ($collection=="Svision 3"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT svision_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP svision 3 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO svision 3 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO svision 3  LAB
			}//END IF svision 3
			
			
			
			
			if ($collection=="Optovision"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT optovision_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Optovision LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Optovision LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Optovision LAB
			}//END IF Optovision
			
			
			
			
			
				if ($collection=="Conant"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT conant_lab from labs WHERE primary_key='$lab_id')";//LOOK UP conant LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO conant LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO conant LAB
			}//END IF conant
			
			
			
			
if ($collection=="Vot"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT vot_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Vot LAB EMAIL ADDRESS
				$result5=mysql_query($query5)	or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Vot LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Vot LAB
			}//END IF VOT			
			
			
			
		if ($collection=="Rodenstock"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT rodenstock_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Rodenstock LAB EMAIL ADDRESS
				$result5=mysql_query($query5)				or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Rodenstock LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Rodenstock LAB
			}//END IF Rodenstock		
			
			
			
	if ($collection=="Rodenstock HD"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT rodenstock_hd_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Rodenstock HD LAB EMAIL ADDRESS
				$result5=mysql_query($query5)				or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Rodenstock HD LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Rodenstock  HD LAB
			}//END IF Rodenstock HD
			
			
			
		if ($collection=="Glass"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT glass_lab from labs WHERE primary_key='$lab_id')";//LOOK UP GLASS LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO GLASS LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO GLASS LAB
			}//END IF GLASS	
			
			
			if ($collection=="Glass 2"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT glass_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP GLASS 2 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)		or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO GLASS 2 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO GLASS 2 LAB
			}//END IF GLASS	 2
			
			
			
				if ($collection=="Glass 3"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT glass_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP GLASS 3 LAB EMAIL ADDRESS
				$result5=mysql_query($query5)		or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO GLASS 3 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO GLASS 3 LAB
			}//END IF GLASS	3
			
			
			if ($collection=="Eco"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
			
			
			if ($collection=="Eco 1"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_1_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
			
			
				if ($collection=="Eco 2"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
			
			
			
			if ($collection=="Eco 3"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
			
			
			
			
			if ($collection=="Eco 4"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_4_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
			
			
			if ($collection=="Eco 5"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_5_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
		
		
		if ($collection=="Eco 6"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_6_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
		
		
		if ($collection=="Eco 7"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_7_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO
		
		
		
		
		
		
		
		
		if ($collection=="Eco 8"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_8_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO 8 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO 8
		
		
		
		
		
		
		
		if ($collection=="Eco 9"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_9_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco 9  LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO 9 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO LAB
			}//END IF ECO 9
		
		
		
		
			if ($collection=="Eco 10"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_10_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco 10  LAB EMAIL ADDRESS
				$result5=mysql_query($query5)					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO ECO 10 LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO ECO 10 LAB
			}//END IF ECO 10
		
		
		
		
		if ($collection=="Easy Fit HD"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT easy_fit_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Easy Fit LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO EASY FIT LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO EASY FIT LAB
			}//END IF EASY FIT
		

if ($collection=="Eco OR"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT eco_or_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Eco OR LAB EMAIL ADDRESS
				$result5=mysql_query($query5)
					or die  ('I cannot select items because: ' . mysql_error());
				$listItem=mysql_fetch_array($result5);
				
				if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
					$faxNumArray=str_split($listItem[fax]);
					$numCount=count($faxNumArray);
					$faxNum="";
						for ($i=0;$i<$numCount;$i++){
							if (is_numeric($faxNumArray[$i])) {
							$faxNum.=$faxNumArray[$i];
							}
						}
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Eco OR LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Eco OR LAB
			}//END IF EASY FIT
		
		}//END WHILE
uploadfinish();



}//END FUNCTION


function add_Pmt_Marker($user_id, $order_num, $gTotal){/* Set payment marker to show order as PAID */
	$transData=$_SESSION["transData"];
	$today = date("Y-m-d");
	$discAmount=bcmul(.02, $gTotal, 2);
	$subAmount2 = bcsub($gTotal, $discAmount, 2);
	$amount=bcadd($subAmount2, $shipCost, 2);
	$query="INSERT into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$transData[cclast4]', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";

	$result=mysql_query($query)
		or die ("could not add marker " . mysql_error());
		
	return true;
}

function add_Order_Ref($Master_Order_ID, $order_num){/* Set Master Order ID for this order number */
	$query="INSERT into order_num_master_id_ref (ref_master_id, ref_order_num) values ('$Master_Order_ID', '$order_num')";

	$result=mysql_query($query)
		or die ("could not add order reference " . mysql_error());
		
	return true;
}
?>
