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

$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
	//echo $query;
	
}

function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){

	$order_date_processed=date("Y-m-d");
	$order_status="processing";

	$query="SELECT  user_id,primary_key,order_product_name, order_product_coating,order_product_id, coupon_dsc, warranty  FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
	$result=mysql_query($query)	or die ('Could not update because: '  . mysql_error());
		
	while ($listItem=mysql_fetch_array($result)){
		
		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];
		

//Insertion dans Swiss_edging_barcode
$queryEdgingSwiss  = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($orderNum)";
$resultEdgingSwiss = mysql_query($queryEdgingSwiss)	or die ('Could not update because: '  . mysql_error());

//1- Traitement des garanties (En attente de savoir si on conserve cette partie)	
	switch ($listItem[warranty]){
		case "0":		$promo_points_warranty	 = 0; 	break;
		case "1":		$promo_points_warranty	 = 0; 	break;
		case "2":		$promo_points_warranty   = 10;	break;
		default: 		$promo_points_warranty	 = 0;	break;
	}
	
	if ($promo_points_warranty > 0){
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
			
		

	//2- Traitement des Loyalty Program  et coating sur la commande
	$queryLoyalty   = "SELECT loyalty_program FROM accounts WHERE user_id = '$listItem[user_id]'";
	$resultLoyalty  = mysql_query($queryLoyalty)	or die ('Could not update because: '  . mysql_error());
	$DataLoyalty    = mysql_fetch_array($resultLoyalty);
	$LoyaltyProgram = $DataLoyalty[loyalty_program];
	$ClientaDroitauBonusOptipoints = 'non';
		
	switch($LoyaltyProgram){
		case "platinum": $OptiPointReward = 20; $ProgramDetail = 'Platinum'; $ClientaDroitauBonusOptipoints = 'oui';    break;
		case "gold":  	 $OptiPointReward = 10; $ProgramDetail = 'Gold';     $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "silver":   $OptiPointReward =  5; $ProgramDetail = 'Silver';   $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "none":     $OptiPointReward =  0; $ProgramDetail = '&nbsp;'; 	 $ClientaDroitauBonusOptipoints = 'non';	break;	
	}
		
		
		if (($OptiPointReward > 0) && ($ClientaDroitauBonusOptipoints == 'oui')){//Le client fait partie d'un programme de loyauté donc on évalue le coating
			
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
				//Selon le lens_category du produit commandé
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
				$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, amount, datetime,user_id, order_num, loyalty_program) VALUES ('','$OptipointDetail','$OptiPointReward', '$datecomplete', '$listItem[user_id]','#$orderNum','$ProgramDetail')" ;
				$resultinsert=mysql_query($queryInsert)		or die (mysql_error() . $queryInsert);
			}
			
			
		}//End IF





//PROMO LENSNET TRANSITIONS Evaluate and send an email to the customer with the coupon code that he will be able to use on another order of the same collection
	$queryCollection     = "SELECT collection, index_v from exclusive WHERE primary_key =$listItem[order_product_id]";
	$resultCollection    = mysql_query($queryCollection)		or die ('Could not update becauser: ' . mysql_error());
	$listItemCollection  = mysql_fetch_array($resultCollection);
	$Index 		      	 = $listItemCollection[index_v];
	$elligible_promo  	 = 'no';
	$valeur_coupon	  	 =    0;
		
//TODO ETRE PLUS PRÉCIS CAR CE NE SONT PAS TOUS LES PRODUITS DE VERSANO QUI DOIVENT GENERER UN COUPON
//EN CE MOMENT, LE COUPON SERAIT GÉNÉRÉ MAIS PAS ENVOYÉ PAR EMAIL
	switch ($listItemCollection[collection]) {//Valider si le produit  commandé fait partie des produits élligibles à la promo
		case "ClearI":		$elligible_promo = "yes"; $la_collection = 'ClearI';  break;
		case "Versano":		$elligible_promo = "yes"; $la_collection = 'Versano'; break;
		default: 			$elligible_promo = "no";							  break;
	}	
	switch ($Index) {//Valider si l'index commandé est élligible à la promo
		//Will generate No Coupon
		case "1.53":		$valeur_coupon   =     0; break;
		case "1.74":		$valeur_coupon   =     0; break;
		//Will Generate A coupon
		case "1.50":		$valeur_coupon   =  7.50; break;
		case "1.59":		$valeur_coupon   = 10.00; break;
		case "1.60":		$valeur_coupon   = 10.00; break;
		case "1.67":		$valeur_coupon   = 10.00; break;
		default: 			$valeur_coupon   =     0; break;
	}	
	
	if ($_SESSION["sessionUser_Id"]=='redoqc'){
		echo '<br>Elligible Promo:'. $elligible_promo;
		echo '<br>Valeur coupon:'. $valeur_coupon;	
		echo '<br>Coupon utilisé sur commande:'. $listItem[coupon_dsc];
		exit();
		}

	if (($elligible_promo =='yes')&&($valeur_coupon<>"0")&&($listItem[coupon_dsc]=='0.00')){
		$dans1mois 	   = mktime(0,0,0,date("m"),date("d")+30,date("Y"));
		$datedans1mois = date("Y/m/d", $dans1mois);
		$code          = "promo". $orderNum;
		//Le code doit etre généré de façon unique (on utilise le order num)
		$queryCoupon  = "INSERT INTO coupon_codes (code,type,select_by,date,collection,amount) 
		VALUES ('$code','one-time','collection','$datedans1mois','$lacollection','$valeur_coupon')";
		$resultCoupon = mysql_query($queryCoupon)		or die ("Could not create coupon". mysql_error());				
		//Envoyer le code créé par email a l'adresse enregistré dans le compte du client		  
		$queryEmail    = "SELECT email,language from accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"]  . '\'';
		$resultEmail   = mysql_query($queryEmail)		or die ('Could not update becauser: ' . mysql_error());
		$listItemEmail = mysql_fetch_array($resultEmail);
		$to            = $listItemEmail['email'];
		$langue        = $listItemEmail['language'];
	}//End IF order is elligible for Promo LNC Transitions
	
	
		
//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	$result2=mysql_query($query2)		or die ('Could not update because: ' . mysql_error());
		

	//Code rajouté par Charles 2010-07-22
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
	$e_result=mysql_query($e_query)
		or die ('Could not update because: ' . mysql_error());
		
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
		
	$lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query6="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result6=mysql_query($query6)
				or die  ('I cannot select items because: ' . mysql_error());
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
		
	sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
		
	sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
		
	$product_name=$listItem[order_product_name];
		
	$query3="SELECT collection FROM exclusive WHERE product_name='$product_name'";
	$result3=mysql_query($query3)
		or die ('Could not update because: ' . mysql_error());
		
		$listItem2=mysql_fetch_array($result3);
		$collection=$listItem2[collection];
		
		if ($collection=="Other"){
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
		
		if ($collection=="My World"){
			$lab_id=$_SESSION["sessionUserData"]["main_lab"];
			$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
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
				sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);//SEND CONFIRMATIOn TO INNOVATIVE LAB
			}
			sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO INNOVATIVE LAB
			}
			
	
	
		
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
			

			
			if ($collection=="Innovative 1"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_1_lab from labs WHERE primary_key='$lab_id')";//LOOK UP private grm 1  LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn Innovative 1 lab
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO  Innovative 1 lab
			}//END IF  Innovative 1 lab	
			
			
			
			
			if ($collection=="Innovative 2"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP private grm 1  LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn Innovative 2 lab
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO  Innovative 2 lab
			}//END IF  Innovative 2 lab	
			
			
			
			if ($collection=="Innovative 3"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP private grm 1  LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn Innovative 3 lab
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO  Innovative 3 lab
			}//END IF  Innovative 3 lab	
			
			
			
			
				if ($collection=="LNC GKB"){
					$lab_id=$_SESSION["sessionUserData"]["main_lab"];
					$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT lnc_gkb_lab from labs WHERE primary_key='$lab_id')";//LOOK UP  LAB EMAIL ADDRESS
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
						sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATION
					}
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn 
				}//END IF   LNC GKB
				
				
				
				if ($collection=="LNC STC"){
					$lab_id=$_SESSION["sessionUserData"]["main_lab"];
					$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT lnc_stc_lab from labs WHERE primary_key='$lab_id')";//LOOK UP  LAB EMAIL ADDRESS
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
						sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATION
					}
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn 
				}//END IF   LNC STC
				
				
				
				if ($collection=="LNC HKO"){
					$lab_id=$_SESSION["sessionUserData"]["main_lab"];
					$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT lnc_hko_lab from labs WHERE primary_key='$lab_id')";//LOOK UP LAB EMAIL ADDRESS
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
						sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATION
					}
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn 
				}//END IF   LNC HKO
				
				
				
				
				if ($collection=="LNC SWISS"){
					$lab_id=$_SESSION["sessionUserData"]["main_lab"];
					$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT lnc_swiss_lab from labs WHERE primary_key='$lab_id')";//LOOK UP LAB EMAIL ADDRESS
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
						sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATION
					}
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn 
				}//END IF   LNC SWISS
			
			
			if ($collection=="Innovative SVFT"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT innovative_svft_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Innovative SVFT  LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn Innovative SVFT
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO  Innovative SVFT
			}//END IF  Innovative SVFT
			
			
			

			
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
	$discAmount=bcmul(.00, $gTotal, 2);
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
