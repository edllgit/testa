<?php 

function getUserEmail($user_id){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT email FROM accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$email=$listItem[email];
	return $email;
}

function getNewOrderNum(){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM last_order_num WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$order_num=$listItem[last_order_num]+1;
	
	$query="UPDATE last_order_num SET last_order_num='$order_num' WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));


	$_SESSION['PrescrData']['myordnum']=$order_num; ///////////// pt 10/21/10 for xml uploader

	return $order_num;
}

function addOrderNumShiptoOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){
include "../sec_connectEDLL.inc.php";
$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	//echo $query;
	
}

function addOrderNumShiptoFrameOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){
include "../sec_connectEDLL.inc.php";
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
	//echo $query;
	
}

function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){
	include "../sec_connectEDLL.inc.php";
	$order_date_processed=date("Y-m-d");
	$order_status="processing";

	$query="SELECT  user_id,primary_key,order_product_name,order_product_id, coupon_dsc, warranty, order_product_type, order_product_coating, order_product_photo, order_product_polar, order_num, salesperson_id FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
	$result=mysqli_query($con,$query)	or die ('Could not update because: '  . mysqli_error($con));
		
	while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		
		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];
		
//Insertion dans Swiss_edging_barcode
$queryEdgingSwiss  = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($orderNum)";
$resultEdgingSwiss = mysqli_query($con,$queryEdgingSwiss)	or die ('Could not update because: '  . mysqli_error($con));		
		
//1- Traitement des garanties (En attente de savoir si on conserve cette partie)	
	switch ($listItem[warranty]){
		case "0":		$promo_points_warranty	 = "0"; 	break;
		case "1":		$promo_points_warranty	 = "0"; 	break;
		case "2":		$promo_points_warranty   = "10";	break;
		default: 		$promo_points_warranty	 = "0";		break;
	}
	
	if ($promo_points_warranty > 0){
		$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
		$datecomplete = date("Y/m/d", $ladate);
		
		$query="SELECT  lnc_reward_points,company FROM accounts WHERE user_id  = '$listItem[user_id]'";
		$acctResult=mysqli_query($con,$query)	or die ("Could not find account");
		$Data=mysqli_fetch_array($acctResult,MYSQLI_ASSOC);
		
		$nouveauTotal = $promo_points_warranty + $Data[lnc_reward_points];
		$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
		$resultUpdate=mysqli_query($con,$queryUpdate)		or die (mysqli_error($con));
		
		//Insert in lnc_reward_history and update point in the customer's account
		$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, detail_fr, amount, datetime,user_id, order_num) VALUES ('','Warranty: $listItem[warranty] years ','Garantie: $listItem[warranty] ans ','$promo_points_warranty', '$datecomplete', '$listItem[user_id]', '$orderNum')" ;
		$resultinsert=mysqli_query($con,$queryInsert)		or die (mysqli_error($con) . $queryInsert);
	}
			
		

	//2- Traitement des Loyalty Program  et coating sur la commande
	$queryLoyalty   = "SELECT loyalty_program FROM accounts WHERE user_id = '$listItem[user_id]'";
	$resultLoyalty  = mysqli_query($con,$queryLoyalty)	or die ('Could not update because: '  . mysqli_error($con));
	$DataLoyalty    = mysqli_fetch_array($resultLoyalty,MYSQLI_ASSOC);
	$LoyaltyProgram = $DataLoyalty[loyalty_program];
	$ClientaDroitauBonusOptipoints = 'non';
		
	switch($LoyaltyProgram){
		case "platinum": $OptiPointReward = 20; $ProgramDetail = 'Platinum'; $ClientaDroitauBonusOptipoints = 'oui';    break;
		case "gold":  	 $OptiPointReward = 10; $ProgramDetail = 'Gold';     $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "silver":   $OptiPointReward =  5; $ProgramDetail = 'Silver';   $ClientaDroitauBonusOptipoints = 'oui';	break;
		case "none":     $OptiPointReward =  0; $ProgramDetail = '&nbsp;'; 	 $ClientaDroitauBonusOptipoints = 'non';	break;	
		default: 		           											 $ClientaDroitauBonusOptipoints = 'non'; 	
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
				default: 		            $ClientaDroitauBonusOptipoints = 'non'; 	
			}//End Switch
			
			$queryLensCategory  = "SELECT lens_category  FROM  exclusive WHERE primary_key  = $listItem[order_product_id]";
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
					default: 		        $ClientaDroitauBonusOptipoints = 'non'; 	
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




//2011-08-03:  Code that sends en email to the customer with the coupon code that he 
	//						will be able to use on another order of the same collection
	$queryCollection="SELECT collection, index_v from exclusive WHERE primary_key =$listItem[order_product_id]";
	$resultCollection=mysqli_query($con,$queryCollection)		or die ('Could not update becauser: ' . mysqli_error($con));
	$listItemCollection=mysqli_fetch_array($resultCollection,MYSQLI_ASSOC);
	

	$Index = $listItemCollection[index_v];
	//echo 'i :	'.$Index;
	//exit(); 
	$valeur_coupon	  = "0";
		
	if ($Index == '1.50'){
	$ItemElligible = '1.5';
		switch ($listItemCollection[collection]) {
		case "Innovation FF":		$valeur_coupon	  = "68";  $lacollection = "Innovation FF";		break;
		case "Innovation DS":		$valeur_coupon	  = "48";  $lacollection = "Innovation DS";		break;
		case "Innovation II DS":	$valeur_coupon    = "58";  $lacollection = "Innovation II DS";	break;
		case "Innovation FF HD":	$valeur_coupon    = "78";  $lacollection = "Innovation FF HD";	break;
		case "Identity":			$valeur_coupon    = "103"; $lacollection = "Identity";			break;
		case "Innovative Plus":		$valeur_coupon    = "98";  $lacollection = "Innovative Plus";	break;
		case "Nesp":				$valeur_coupon    = "88";  $lacollection = "Nesp";				break;
		default: 					$valeur_coupon	  = "0";										break;
		}
	}//End if index 1.50
		
		
		//If product is polycarbonate, we change the value of the coupon (2nd pair for 1$)
		if ($Index == '1.59'){
		$ItemElligible = '1.59';
			switch ($listItemCollection[collection]) {
			case "Innovation FF 159":		$valeur_coupon	  = "78";  $lacollection = "Innovation FF 159";		break;
			case "Innovation FF HD 159":	$valeur_coupon    = "78";  $lacollection = "Innovation FF HD 159";	break;
			case "Nesp":					$valeur_coupon    = "88";  $lacollection = "Nesp";					break;
			default: 						$valeur_coupon	  = "0";											break;
			}
		}
		
	 
	
	if (($valeur_coupon<>"0") && ($listItem[coupon_dsc]=='0.00')) {
		$dans1mois = mktime(0,0,0,date("m"),date("d")+30,date("Y"));
		$datedans1mois = date("Y/m/d", $dans1mois);
		$code = "promo". $orderNum;
		//Le code doit etre g�n�r� au hasard (on utilise le order num)
		$queryCoupon = "Insert into coupon_codes(code,type,select_by,date,collection,amount) VALUES ('$code','one-time','collection','$datedans1mois','$lacollection','$valeur_coupon')";
		$resultCoupon=mysqli_query($con,$queryCoupon)		or die ("Could not create coupon". mysqli_error($con));				
			 //Envoyer le code cr�� par email a l'adresse enregistr� dans le compte du client		  
			
	$queryEmail="SELECT email,language from accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"]  . '\'';
	$resultEmail=mysqli_query($con,$queryEmail)		or die ('Could not update becauser: ' . mysqli_error($con));
	$listItemEmail=mysqli_fetch_array($resultEmail,MYSQLI_ASSOC);
	$to = $listItemEmail['email'];
	$langue = $listItemEmail['language'];
	
	if ($listItemCollection[collection] == 'Den 4'){
	//$lacollection = 'Identity';
	}else{
	$lacollection = $listItemCollection[collection];
	}
	
	if ($listItemCollection[collection] == 'Bbg 8'){
	$lacollection = 'Innovative +';
	}
	
		
	if ($listItemCollection[collection] == 'Innovative Plus'){
	$lacollection = 'Innovative +';
	}
	
	
	
	
if ($lacollection == 'Innovative +')
{
	$message = "To take advantage of your 2nd pair for 1$ per lens, please use your <br>
		<b>promo-code number: " . $code . "</b>" . " <br><br> 
		The promo-code will be valid only on purchase of a $ItemElligible index<br> clear pair from the Innovative + collection that has been ordered. Get this second pair at<br> the low price of 1$ per lens plus a mandatory AR coating ($56, net $28 per pair including 	an Easy to Clean.)<br><br>
		The promo-code will be valid for 30 days only. ";	
	

if ($langue == 'french') {			
$subject ="Coupon rabais Innovative +";	
}else {
$subject ="Innovative + Promo-code";	
}	
$customerEmail = str_split($to,150);
$send_to_address= $customerEmail + array('dbeaulieu@direct-lens.com');
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);


			
			
//Fin Si collection = Bbg 8	/ innovative +	
}elseif ($lacollection == 'Identity')
{
$message = "To take advantage of your 2nd pair for 1$ per lens, please use your <br>
	<b>promo-code number: " . $code . "</b>" . " <br><br> 
	The promo-code will be valid only on purchase of a $ItemElligible index<br> clear pair from the Identity collection that has been ordered. Get this second pair at<br> the low price of 1$ per lens plus a mandatory AR coating ($56, net $28 per pair including an Easy to Clean.)<br><br>
	The promo-code will be valid for 30 days only. ";	

if ($langue == 'french') {			
$subject ="Coupon rabais Innovative";	
}else {
$subject ="Innovative Promo-code";	
}	

$customerEmail = str_split($to,150);
$send_to_address= $customerEmail + array('dbeaulieu@direct-lens.com');
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);



		
//Fin Si collection = identity		
}else{//Si collection autre que identity on verifie la langue et on envoi le email dans la bonne langue


	if ($langue == 'french') {			
	$message = "Bonjour,<br>
	Vous pouvez maintenant profiter de votre 2i�me paire � seulement 1$!<br>
	<b>Votre coupon rabais: " . $code . "</b>" . " <br> Entrer le num�ro du coupon rabais lors de l'acquisition de votre prochaine <br>paire en verre clair en indice $ItemElligible de la m�me collection Innovative que votre achat et vous obtiendrez<br> votre paire pour 1$ avec frais de traitements en sus. <br><br>Ce coupon rabais sera valide pour une p�riode de 30 jours. "; 	
	}else {
	$message = "To take advantage of your 2nd pair for 1$, please use your <br>
	<b>coupon-code number: " . $code . "</b>" . " <br><br> 
	The coupon-code will be valid only on purchase of a $ItemElligible index<br> clear pair from the ". $lacollection ." collection that has been ordered. Get this second pair at<br> the low price of 1$ plus a mandatory AR coating (20$).Easy to clean can be added for an extra 8$<br><br>
	The coupon-code will be valid for 30 days only. ";	
	}	


if ($langue == 'french') {			
$subject ="Coupon rabais Innovative";	
}else {
$subject ="Innovative Promo-code";	
}	

$customerEmail = str_split($to,150);
$send_to_address= $customerEmail + array('dbeaulieu@direct-lens.com');
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

		   
		
	}//FIN DE SI PAS UTILIS� DE COUPON DANS LA COMMANDE
	
	
	
	
//PROMO LENSNET TRANSITIONS
//Evaluate and send an email to the customer with the coupon code that he will be able to use on another order of the same collection
$queryCollection     = "SELECT collection, index_v from exclusive WHERE primary_key =$listItem[order_product_id]";
$resultCollection    = mysqli_query($con,$queryCollection)		or die ('Could not update becauser: ' . mysqli_error($con));
$listItemCollection  = mysqli_fetch_array($resultCollection,MYSQLI_ASSOC);
$Index 		      	 = $listItemCollection[index_v];
$elligible_promo  	 = 'no';
$valeur_coupon	  	 =    0;
		
//TODO ETRE PLUS PR�CIS CAR CE NE SONT PAS TOUS LES PRODUITS DE VERSANO QUI DOIVENT GENERER UN COUPON
//EN CE MOMENT, LE COUPON SERAIT G�N�R� MAIS PAS ENVOY� PAR EMAIL
	switch ($listItemCollection[collection]) {//Valider si le produit  command� fait partie des produits �lligibles � la promo
		case "ClearI":		$elligible_promo = "yes"; $la_collection = 'ClearI';  break;
		case "Versano":		$elligible_promo = "yes"; $la_collection = 'Versano'; break;
		default: 			$elligible_promo = "no";							  break;
	}	
	switch ($Index) {//Valider si l'index command� est �lligible � la promo
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
	

	if (($elligible_promo =='yes')&&($valeur_coupon<>"0")&&($listItem[coupon_dsc]=='0.00')){
		$dans1mois 	   = mktime(0,0,0,date("m"),date("d")+30,date("Y"));
		$datedans1mois = date("Y/m/d", $dans1mois);
		$code          = "promo". $orderNum;
		//Le code doit etre g�n�r� de fa�on unique (on utilise le order num)
		$queryCoupon  = "INSERT INTO coupon_codes (code,type,select_by,date,collection,amount) 
		VALUES ('$code','one-time','collection','$datedans1mois','$la_collection','$valeur_coupon')";
		$resultCoupon = mysqli_query($con,$queryCoupon)		or die ("Could not create coupon". mysqli_error($con));				
		//Envoyer le code cr�� par email a l'adresse enregistr� dans le compte du client		  
		$queryEmail    = "SELECT email,language from accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"]  . '\'';
		$resultEmail   = mysqli_query($con,$queryEmail)		or die ('Could not update becauser: ' . mysqli_error($con));
		$listItemEmail = mysqli_fetch_array($resultEmail,MYSQLI_ASSOC);
		$to            = $listItemEmail['email'];
		$langue        = $listItemEmail['language'];
}//End IF order is elligible for Promo LNC Transitions
//FIN PROMO LENSNET TRANSITIONS
	

	
		
		//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
		$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
		addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	$result2=mysqli_query($con,$query2) or die ('Could not update because: ' . mysqli_error($con));
		
			
	
	//Code rajout� par Charles 2010-07-22
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	
	//Update status history with the customer ip address ad the ip
	$queryStatus="INSERT INTO status_history (order_num,order_status,update_time, update_type,update_ip) VALUES($orderNum,'processing','$datecomplete','manual','$ip') ";
	$resultStatus=mysqli_query($con,$queryStatus)	or die  ('I  cannot Insert into status history because: ' . mysqli_error($con));
		
		
		
	$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
	$e_result=mysqli_query($con,$e_query) or die ('Could not update because: ' . mysqli_error($con));
		
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);	
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
		
		$discAmount=bcmul(.02, $gTotal, 2);
		$subAmount2 = bcsub($gTotal, $discAmount, 2);
		$amount=bcadd($subAmount2, $shipCost, 2);
	
		//$msg=sendPmtConfirmEmail($gTotal, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
	}
		
	$lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query6="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result6=mysqli_query($con,$query6)or die  ('I cannot select items because: ' . mysqli_error($con));
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
		
	//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
		
	//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
		
	$product_name=$listItem[order_product_name];
		
	$query3="SELECT collection FROM exclusive WHERE product_name='$product_name'";
	$result3=mysqli_query($con,$query3) or die ('Could not update because: ' . mysqli_error($con));
		
		$listItem2=mysqli_fetch_array($result3,MYSQLI_ASSOC);
		$collection=$listItem2[collection];
				
			
				

			
		//Promo FSV 2015-02-03
				$queryEye       = "SELECT eye, order_product_id, order_from FROM ORDERS WHERE order_num = " . $orderNum;
				//echo  '<br>'    . $queryEye;
				$resultEye      = mysqli_query($con,$queryEye) or die  ('I cannot select items because: ' . mysqli_error($con));
				$DataEye        = mysqli_fetch_array($resultEye,MYSQLI_ASSOC);		
				$Oeil           = $DataEye['eye'];
				$Prod_ID        = $DataEye['order_product_id'];
				$OrderFrom      = $DataEye['order_from'];

			
				
				if ($Oeil == "Both"){//La commande est bien pour une paire, sinon , le client n'est pas �lligible pour cette Promotion
				
						switch($OrderFrom){
							//G�n�rerons un coupon
							case 'directlens':    $queryProductID = "SELECT lens_category FROM exclusive WHERE primary_key = ". $Prod_ID;   break;
							case 'eye-recommend': $queryProductID = "SELECT lens_category FROM exclusive WHERE primary_key = ". $Prod_ID;   break;
							//Ne g�n�rerons pas de coupon
							case 'aitlensclub':   $queryProductID = "";	break;
							case 'lensnetclub':   $queryProductID = ""; break;
							case 'ifcclubca':  	  $queryProductID = "";	break;
							case 'safety':  	  $queryProductID = "";	break;
							case 'milano6769':    $queryProductID = "";	break;
							case 'ifcclub':  	  $queryProductID = "";	break;
						}//End Switch
						
						if ($queryProductID <> ""){
							$resultProductID   = mysqli_query($con,$queryProductID) or die  ('I cannot select items because: ' . mysqli_error($con));
							$DataProductID     = mysqli_fetch_array($resultProductID,MYSQLI_ASSOC);
							$Lens_Cat          = $DataProductID[lens_category];  	
							$coupon 		   = false;					
							if (($Lens_Cat == 'prog ff') || ($Lens_Cat == 'prog ds')){
								//On atteint tous les pr�-requis pour g�n�rer le coupon	
								$coupon = true;
								$dans1mois = mktime(0,0,0,date("m"),date("d")+30,date("Y"));
								$datedans1mois = date("Y/m/d", $dans1mois);
								$code = "FSV". $orderNum;
								$queryCoupon = "Insert into coupon_codes(code,type,select_by,date,amount,description) VALUES ('$code','one-time','all','$datedans1mois','25','Promo Free FSV 25$ Rebate on a Stock Order')";
								$resultCoupon=mysqli_query($con,$queryCoupon)		or die ("Could not create coupon". mysqli_error($con));	
								
								//Pr�paration du courriel
								$message="";
								$message="<html>";
								$message.="<head><style type='text/css'>
								<!--
								.TextSize {
									font-size: 8pt;
									font-family: Arial, Helvetica, sans-serif;
								}
								-->
								</style></head>";
						
								$message.="<body>";
								$queryLanguage    = "SELECT language, email FROM accounts WHERE user_id = '". $_SESSION["sessionUser_Id"] . "'";
								$ResultLanguage   = mysqli_query($con,$queryLanguage) or die  ('I cannot select items because: ' . mysqli_error($con));
								$DataLanguage     = mysqli_fetch_array($ResultLanguage,MYSQLI_ASSOC);
								$CustomerLanguage = $DataLanguage[language];
								$CustomerEmail    = $DataLanguage[email];
								if (strtolower($CustomerLanguage) == 'french') 
								{
									$message = "Nous vous remercions d'avoir command&eacute; une paire  de progressif parmi nos verres num&eacute;riques de qualit&eacute; sup&eacute;rieure. Ci-dessous votre coupon (valide pour 30 jours) pour une paire gratuite de SV Stock.<b><br>Coupon: " . $code . "</b>
									<br><br><b><u>CONDITIONS</u></b><br><br>
									Ce coupon est applicable aux commandes SV Stock en indice 1.5 ou 1.59 avec AR, Tailler-Monter (monture plastique ou m&eacute;tal) sur <a href=\"www.direct-lens.com\">www.direct-lens.com</a> pour une valeur maximale de 25$.
<br><b>N. B.</b> : Si une commande exc&egrave;de ce montant, un coupon-rabais de 25$ sera appliqu&eacute;e &agrave; la facture et vous payerez uniquement la diff&eacute;rence.";
								}else{
									$message = "Thank you for ordering a pair of digital progressive lenses. Please find below your coupon code for a free pair of stock lenses.
									 This coupon code is valid for 30 days.
									<br><b>Coupon code: " . $code . "</b><br><br><b><u>CONDITIONS</u></b><br><br>
									This coupon is valid for Stock SV lenses in 1.5 or 1.59 index with AR, edged & mounted (plastic or metal frame) from <a href=\"www.direct-lens.com\">www.direct-lens.com</a> to a maximum value of $25.
<br> <b>N.B.</b>: Orders exceeding $25 will have a $25 rebate applied to the invoice and only the difference will be charged.";
								}
								
								//Envoie du courriel contenant le code
								if (strtolower($CustomerLanguage) == 'french') 
								{
									$subject= "Promotion Stock Gratuit";
								}else{
									$subject= "Promo Free FSV";
								}
								$send_to_address = str_split($CustomerEmail,150);
								$curTime= date("m-d-Y");	
								$to_address=$send_to_address;
								$from_address='donotreply@entrepotdelalunette.com';
								$response=office365_mail($to_address, $from_address, $subject, null, $message);
								
								//Copie pour l'admin
								//$AdminEmail 	 = "dbeaulieu@direct-lens.com";
								//$send_to_address = str_split($AdminEmail,150);
								//$response=office365_mail($to_address, $from_address, $subject, null, $message);
							}//End if Lens cat = Prog FF
		
						}//End if Query is not empty
				
				}//End IF Oeil == 'Both'
			//End Promo FSV 2015-02-03
			
		
			
		
		}//END WHILE
//uploadfinish();



}//END FUNCTION


function add_Pmt_Marker($user_id, $order_num, $gTotal){/* Set payment marker to show order as PAID */
	include "../sec_connectEDLL.inc.php";
	$transData=$_SESSION["transData"];
	$today = date("Y-m-d");
	//$discAmount=bcmul(.02, $gTotal, 2);
	$discAmount = 0;
	
	$queryShippingCost  = "SELECT ship_chg_stock, ship_chg_rx FROM labs WHERE primary_key =  (SELECT lab FROM orders WHERE order_num = $order_num limit 0,1)";
	$resultShippingCost = mysqli_query($con,$queryShippingCost)		or die ("could not add marker queryShippingCost " . mysqli_error($con));
	$DataShippingCost   = mysqli_fetch_array($resultShippingCost,MYSQLI_ASSOC);
    $ShippingCostStock  = $DataShippingCost[ship_chg_stock];
	$ShippingCostRx     = $DataShippingCost[ship_chg_rx];
	
	$queryOrderType  = "SELECT order_product_type FROM orders WHERE order_num = $order_num limit 0,1";
	$resultOrderType = mysqli_query($con,$queryOrderType)		or die ("Could not add marker queryOrderType " . mysqli_error($con));
	$DataOrderType   = mysqli_fetch_array($resultOrderType,MYSQLI_ASSOC);
	
	if ($DataOrderType[order_product_type]=='exclusive')
	$shipCost = $ShippingCostRx ;
	else
	$shipCost = $ShippingCostStock ;
	
	$subAmount2 = bcsub($gTotal, $discAmount, 2);
	$amount=bcadd($subAmount2, $shipCost, 2);
	$query="INSERT into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$transData[cclast4]', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";

	$result=mysqli_query($con,$query) or die ("could not add marker queryInsert" . mysqli_error($con));
		
	return true;
}

function add_Order_Ref($Master_Order_ID, $order_num){/* Set Master Order ID for this order number */
	include "../sec_connectEDLL.inc.php";
	$query="INSERT into order_num_master_id_ref (ref_master_id, ref_order_num) values ('$Master_Order_ID', '$order_num')";

	$result=mysqli_query($con,$query)or die ("could not add order reference   add_Order_Ref" . mysqli_error($con));
		
	return true;
}
?>
