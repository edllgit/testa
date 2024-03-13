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

$query="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
	echo $query;
	
}

function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){

	$order_date_processed=date("Y-m-d");
	$order_status="processing";

	$query="SELECT user_id,primary_key,order_product_name,order_product_id, coupon_dsc, warranty FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
	$result=mysql_query($query)	or die ('Could not update because: '  . mysql_error());
		
	while ($listItem=mysql_fetch_array($result)){
		
		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];
		
		
		//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
		$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
		addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	$result2=mysql_query($query2)		or die ('Could not update because: ' . mysql_error());
			

	$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
	$e_result=mysql_query($e_query)		or die ('Could not update because: ' . mysql_error());
		
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);	
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
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
			
		if (($collection=="Precision")||($collection=="Vision Pro")){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT precision_vp_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRECISION LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO PRECISION LAB
				}
				if ($collection=="Precision"){
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");}
					// SEND CONFIRMATIOn TO PRECISION LAB NO PRICES  ____ JUNK DNA
				else{
					sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");
				}// SEND CONFIRMATIOn TO PRECISION LAB  ___ JUNK DNA
			}
			
		if ($collection=="Infocus"){
			$lab_id=$_SESSION["sessionUserData"]["main_lab"];
			$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT infocus_lab from labs WHERE primary_key='$lab_id')";//LOOK UP IN FOCUS LAB EMAIL ADDRESS
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
				sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO INFOCUS LAB
				}
			sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO INFOCUS LAB
			}
			
		if ($collection=="Vision Pro Poly"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT visionpropoly_lab from labs WHERE primary_key='$lab_id')";//LOOK UP VISION PRO POLY LAB EMAIL ADDRESS
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
						sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO VISION PRO LAB
				}
				sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO VISION PRO LAB
			}//END IF VISION PRO POLY
			
	if ($collection=="Generation"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT generation_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Generation LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO Generation LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO Generation LAB
			}//END IF Generation
			
	if ($collection=="TrueHD"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT truehd_lab from labs WHERE primary_key='$lab_id')";//LOOK UP TrueHD LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO TrueHD LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO TrueHD LAB
			}//END IF TrueHD
			
		
		if ($collection=="Vision Eco"){
				$lab_id=$_SESSION["sessionUserData"]["main_lab"];
				$query5="SELECT lab_email,fax_notify,fax from labs WHERE primary_key=(SELECT visioneco_lab from labs WHERE primary_key='$lab_id')";//LOOK UP Vision Eco LAB EMAIL ADDRESS
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
					sendFaxPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],"false",$faxNum);// SEND CONFIRMATIOn TO VISION ECO LAB
				}
				sendPrescriptionConfirmationLab($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"],"false");// SEND CONFIRMATIOn TO VISION ECOLAB
			}//END IF VISION ECO
		
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
