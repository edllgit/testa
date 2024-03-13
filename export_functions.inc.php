<?php
require_once(__DIR__.'/constants/mysql.constant.php');
set_time_limit(3000);


function export_order($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		//----------ici------
		if ($orderItem[order_from]!="ifcclubca"){
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		$ProdResult=mysql_query($ProdQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
		$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
				

		$queryMirror  = "select category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysql_query($queryMirror)		or die  ('I cannot select items because: ' . mysql_error());
		$DataMirror   = mysql_fetch_array($ResultMirror);
		$usercount    = mysql_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;
				}
			
				
		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "Select myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysql_query($VerifIfShape)or die  ('I cannot select items because: ' . mysql_error());
		$DataVerif=mysql_fetch_array($VerifResult);
		$TheShape = $DataVerif['myupload'];
		
		if ($TheShape <> "") {
		$TheShape ="Yes";
		echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		echo  '<br> une shape attaché: Non';
		}
				
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				
				case 'Nylon Groove':
				$frame_type="Nylon Groove";
				break;
				
				case 'Metal':
				$frame_type="Metal";
				break;
			
				case 'Drill & Notch':
				$frame_type="Drill & Notch";
				break;
				
				case 'Plastic':
				$frame_type="Plastic";
				break;
				
				case 'Metal Groove':
				$frame_type="Metal Groove";
				break;
				
				case 'Drill and Notch':
				$frame_type="Drill and Notch";
				break;

				case 'Edge Polish':
				$frame_type="Edge Polish";
				break;
					
				case 'Métal            ':
				$frame_type="Metal";
				break;
				
				case 'Fil Nylon        ':
				$frame_type="Nylon Groove";
				break;
				
				case 'Percé       ':
				$frame_type="Drill and Notch";
				break;
				
				case 'Fil Métal           ':
				$frame_type="Metal Groove";
				break;
				
				case 'Plastique':
				$frame_type="Plastic";
				break;
				
								
				default: 
				$frame_type= $EdgeItem[frame_type];
				break;
				}
				
				

				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.   '","'.$accNum. '","'.$TheShape.'"'.chr(13);
		
		
		
			}
	
	
	
	return $outputstring;
			
			
	
}



function export_order_HKO($order_num){

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$Query="SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){

	$UV400 = $orderItem[UV400];
	//Special Instruction
	$special_instructions=addslashes($orderItem["special_instructions"]);
	$special_instructions=strtoupper($special_instructions);
	
	$PositionEdgePolish = strpos($special_instructions,'EDGE POLISH');
		if ($PositionEdgePolish !== false) {
			$EDGE_POLISH   = 'Yes';
		}else{
			$EDGE_POLISH   = 'No';
		}
	
	//echo '<br>Special instruction avant enlever corridor: '. $special_instructions;
	//Enlever la mention au corridor dans la commande Demande de HKO en Mai 2018.
	$special_instructions= str_replace('CORRIDOR:9MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 9','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:11MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 11','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR: 11','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:13MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR: 13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:15MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 15','',$special_instructions);
	//Enlever autre mention inutiles pour la production chez HKO
	$special_instructions= str_replace('ship uncut to quebec','',$special_instructions);
	$special_instructions= str_replace('*EDGE POLISH*','',$special_instructions);
	$special_instructions= str_replace('edge polish','',$special_instructions);
	$special_instructions= str_replace('EDGE POLISH','',$special_instructions);
	$special_instructions= str_replace('UNCUT, SHIP TO QUEBEC','',$special_instructions);
	   
	
	//echo '<br>Special instruction apres enlever corridor: '. $special_instructions;
	
	echo '<br>Special instruction avant to upper: '. $special_instructions;
	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
		$Position_MM = strpos($special_instructions,'MM');
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center
	
	$special_instructions = ' '. $special_instructions;
	$PositionBaseCurve = strpos($special_instructions,'BASE CURVE');
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		//echo '<br>Special instruction:<br>'. $special_instructions;
		//echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		//echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		//echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve
	
	

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
		$shipping_code = "NA108";
		//code qui modifie le shipping code si le main lab est lens net USA
		if ($orderItem[lab]=='32'){$shipping_code = "NA310";}
		//code qui modifie le shipping code si le main lab est EAGLE
		if ($orderItem[lab]=='50'){$shipping_code = "NA320";}
		//code qui modifie le shipping code si le main lab est lens net USA
		if ($orderItem[lab]=='41'){$shipping_code = "NA310";}
		//code qui modifie le shipping code si le main lab est AITLENSCLUB
		if ($orderItem[lab]=='47'){$shipping_code = "NA330";}
		//code qui modifie le shipping code si le main lab est Dlab Illinois
		if ($orderItem[lab]=='46'){$shipping_code = "NA320";}
		//code qui modifie le shipping code si c'est une commande d'un EDLL Qc
		if ($orderItem[lab]=='66'){$shipping_code = "NA109";}
		//code qui modifie le shipping code si c'est une commande d'un EDLL Ca
		//if ($orderItem[lab]=='67'){$shipping_code = "NA109";}
		
		
		
		//Si la commande appartient au laboratoire #59 = Safety, afficher 'SAFETY' dans la colonne safety.
		if ($orderItem[lab]=='59'){
				$SAFETY = 'SAFETY';
		}else{
				$SAFETY = '';	
		}//END IF
		
	
	
	//code qui modifie le shipping code si c'est une commande d'un EDLL (SAFETY)
		//NA108 = DIRECTLAB              NA109 = EDLL
		if ($orderItem[user_id]=='redosafety')		{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='entrepotsafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='safedr')			{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='lavalsafe')		{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='terrebonnesafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='sherbrookesafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='chicoutimisafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='levissafe')		{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='longueuilsafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='granbysafe')		{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='stemariesafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='quebecsafe')		{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='stjeromesafe')	{$shipping_code = "NA109";}
		if ($orderItem[user_id]=='gatineausafe')	{$shipping_code = "NA109";}
				
		if ($orderItem[user_id]=='montrealsafe')	{$shipping_code = "NA108";}
		if ($orderItem[user_id]=='warehousehalsafe'){$shipping_code = "NA108";}
		
		switch($orderItem["order_product_coating"]){
						case 'Dream AR': 				$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'Xlr':		 				$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'HD AR':	 				$orderItem["order_product_coating"] = "HDC";	  			break;
						case 'HD AR Backside':	 		$orderItem["order_product_coating"] = "CR1/2G";	  			break;						
						case 'DH2':		 				$orderItem["order_product_coating"] = "HC";      			break;
						case 'DH1':		 				$orderItem["order_product_coating"] = "HC";	  				break;
						case 'Smart AR': 				$orderItem["order_product_coating"] = "CR+G";    			break;
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";	  				break;
						case 'ITO AR':   				$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'AR':		 				$orderItem["order_product_coating"] = "CR+ETC";				break;
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";				break;
						case 'MultiClear AR Backside':	$orderItem["order_product_coating"] = "1/2HDC";				break;
						case 'Uncoated': 	 			$orderItem["order_product_coating"] = " ";	  				break;
						case 'AR Backside':   			$orderItem["order_product_coating"] = "1/2ETC";  			break;
		}
		

		$accQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];
		
		$labQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$extra_product=addslashes($orderItem["extra_product"]);
		
		//echo '<br><br>Order num: '.  $order_num . ' Order from: '.  $orderItem[order_from];
		if (($orderItem[order_from]!="ifcclubca") &&  ($orderItem[order_from]!="safety")){
			$ProdQuery="SELECT product_code,color_code, corridor FROM exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="safety"){
			$ProdQuery="SELECT product_code,color_code,corridor FROM safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="ifcclubca"){
			$ProdQuery="SELECT product_code,color_code,corridor FROM ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		
		/*if($orderItem[order_from]=="safety"){
		$SAFETY = 'SAFETY';
		}else{
		$SAFETY = '';	
		}//END IF
		*/
		$ProdResult = mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$nbrResult  = mysqli_num_rows($ProdResult);
		
			
		if ($nbrResult < 1){
			$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));	
		}
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
	    $color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
		$Corridor = $ProdItem[corridor];
		
		if (strlen($Corridor)==1){
		//Demande HKO de toujours utiliser deux digits
		$Corridor = "0". $Corridor;
		}

		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
			
		$VerifIfShape = "SELECT myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
				
		if ($TheShape <> "") {
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
				
		$EngrQuery = "SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult= mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem  = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount = mysqli_num_rows($EngrResult);
		
		if ($usercount!=0){
				$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		
		$TintQuery="SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];
				
				if (strtolower($tint_color)=='black grey')//Changer Black Grey pour Grey: demande hko
					$tint_color='Grey';
				
		}else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";
		}//End IF
				
				
	
		$queryMirror  = "SELECT category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysql_error());
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;		
			}
				

		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
		
		if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
		
		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$supplier=$EdgeItem[supplier];
			}
	//Valeur par défaut des commandes fait Chez HKO = UNCUT.
	if ($job_type<>'remote edging'){
		$job_type='Uncut';
	}
	
	
	if ($supplier == 'NURBS'){
		$job_type    = 'Edge and Mount';	
		$shape_model = '';	
	}
	echo '<br> JOB TYPE AVANT EVALUATION' . $job_type ;
	
	if (($order_type == 'Frame supplied by hko') && ($supplier='ENHANCE')){
		$supplier = "ENHANCE-CA";	
		$job_type    = 'Edge and Mount';	
	}
	
	
	if ($supplier=='DEEP'){
		$order_type = 'Frame supplied by hko';	
	}else{
		$order_type = 'Provide';		
	}
	


	//Évaluer si le lab est #59, si oui, c'Est du safety et on doit  demander la commande 'Remote Edging'
	/*if ($orderItem[lab]=='59'){
		$job_type = 'remote edging';	
	}*/
		
		
	if ($supplier == 'KUBIK ONE-CA'){
	//Remplacer le ONE dans le modele pour KK, EX: ONE8011 devient --> KK8011 demande d'HKO confirmé par Roberto le 27 août 2019.
	$frame_model =str_replace('ONE','KK',$frame_model);
	//$order_type = 'Frame supplied by hko';	
	$job_type    = 'Edge and Mount';	
	}//END IF
	
	
	/*//Si collection= 'Kubik One' et que la commande est une reprise, on demande UNCUT
	if (($supplier=='KUBIK ONE-CA') && ($orderItem["redo_order_num"] <>'')){
		$job_type = 'Uncut';	
		$order_type = '';	
	}//END IF*/
	
	/*//Si collection= 'ENHANCE_CAe' et que la commande est une reprise, on demande UNCUT
	if (($supplier=='ENHANCE-CA') && ($orderItem["redo_order_num"] <>'')){
		$job_type = 'Uncut';	
		$order_type = '';	
	}//END IF*/
	
		
	echo '<br><br>Order num    ' . $orderItem["order_num"] . '&nbsp;&nbsp;Eye:'.  $orderItem["eye"];
	$THE_EYE = $orderItem["eye"];
	
	
	if($THE_EYE == "R.E."){
			$orderItem["le_pd"]      = "0";
			$orderItem["le_sphere"]  = "0";
			$orderItem["le_cyl"]     = "0";
			$orderItem["le_pr_ax2"]  = "0";
			$orderItem["le_pr_ax"]   = "0";
			$orderItem["le_axis"]    = "0";
			$orderItem["le_add"]     = "0";
			$orderItem["le_height"]  = "0";
			$orderItem["le_pd"]		 = "0";
			$orderItem["le_pd_near"] = "0";
		}
		
		if( $THE_EYE == "L.E."){
			$orderItem["re_pd"]      = "0";
			$orderItem["re_sphere"]  = "0";
			$orderItem["re_cyl"]     = "0";
			$orderItem["re_pr_ax2"]  = "0";
			$orderItem["re_pr_ax"]   = "0";
			$orderItem["re_axis"]    = "0";
			$orderItem["re_add"]     = "0";
			$orderItem["re_height"]  = "0";
			$orderItem["re_pd"]		 = "0";
			$orderItem["re_pd_near"] = "0";
		}
		
		if ($orderItem["le_add"]=='-'){
				$orderItem["le_add"] = 0;
		}
		
		if ($orderItem["re_add"]=='-'){
				$orderItem["re_add"] = 0;
		}
		
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	

		//.$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400 . '","'. $Corridor.'"'.chr(13);
		  .$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400 . '","'. $Corridor. '","' . $SAFETY.'"'.chr(13);




			}
					
	return $outputstring;
			
}



function export_order_HKO_HBC($order_num){

echo '<br>Passe numero:' . $order_num;

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$Query="SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){

	$UV400 = $orderItem[UV400];
	//Special Instruction
	$special_instructions=addslashes($orderItem["special_instructions"]);
	$special_instructions=strtoupper($special_instructions);
	
	
	$PositionEdgePolish = strpos($special_instructions,'EDGE POLISH');
		if ($PositionEdgePolish !== false) {
			$EDGE_POLISH   = 'Yes';
		}else{
			$EDGE_POLISH   = 'No';
		}
	
	//echo '<br>Special instruction avant enlever corridor: '. $special_instructions;
	//Enlever la mention au corridor dans la commande Demande de HKO en Mai 2018.
	$special_instructions= str_replace('CORRIDOR:9MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 9','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:11MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 11','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR: 11','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:13MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR: 13','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR:15MM','',$special_instructions);
	$special_instructions= str_replace('CORRIDOR 15','',$special_instructions);
	//Enlever autre mention inutiles pour la production chez HKO
	$special_instructions= str_replace('ship uncut to quebec','',$special_instructions);
	$special_instructions= str_replace('*EDGE POLISH*','',$special_instructions);
	$special_instructions= str_replace('edge polish','',$special_instructions);
	$special_instructions= str_replace('EDGE POLISH','',$special_instructions);
	$special_instructions= str_replace('UNCUT, SHIP TO QUEBEC','',$special_instructions);
	   
	
	//echo '<br>Special instruction apres enlever corridor: '. $special_instructions;
	
	echo '<br>Special instruction avant to upper: '. $special_instructions;
	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
		$Position_MM = strpos($special_instructions,'MM');
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center
	
	$special_instructions = ' '. $special_instructions;
	$PositionBaseCurve = strpos($special_instructions,'BASE CURVE');
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		//echo '<br>Special instruction:<br>'. $special_instructions;
		//echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		//echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		//echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve
	
	

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
		$shipping_code = "NA111";//INSÉRER SHIPPING CODE DÉFINIS AVEC HKO ICI

		switch($orderItem["order_product_coating"]){
						//Vraiment utilisé
						case 'Hard Coat':	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'Uncoated': 	$orderItem["order_product_coating"] = " ";	  				break;
						case 'SPC':			$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'SPC Backside':$orderItem["order_product_coating"] = "CR+1/2ETC";  		break;
						case 'Dream AR': 	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						//Non utilisé
						case 'HD AR':	 	$orderItem["order_product_coating"] = "HDC";	  			break;
						case 'HD AR Backside':	 $orderItem["order_product_coating"] = "CR1/2G";	  	break;						
						case 'DH2':		 	$orderItem["order_product_coating"] = "HC";      			break;
						case 'DH1':		 	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'Smart AR': 	$orderItem["order_product_coating"] = "CR+G";    			break;
						case 'ITO AR':   	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'AR':		 	$orderItem["order_product_coating"] = "CR+ETC";				break;
						case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";				break;
						case 'MultiClear AR Backside':$orderItem["order_product_coating"] = "1/2HDC";	break;	
		}
		

		$accQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];
		
		$labQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$extra_product=addslashes($orderItem["extra_product"]);
		
		//echo '<br><br>Order num: '.  $order_num . ' Order from: '.  $orderItem[order_from];
		$ProdQuery="SELECT product_code, corridor FROM ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult = mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$nbrResult  = mysqli_num_rows($ProdResult);
		
			
		if ($nbrResult < 1){
			$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		}
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
	    $color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
		$corridor =$ProdItem[corridor];
		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
		
		if (($corridor < 10) && ($corridor>1)){
			//Demande HKO de toujours utiliser deux digits
			$corridor = "0". $corridor;
		}	
			
		$VerifIfShape = "SELECT myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
				
		if ($TheShape <> "") {
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
				
		$EngrQuery = "SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult= mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem  = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount = mysqli_num_rows($EngrResult);
		
		if ($usercount!=0){
				$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		
		$TintQuery="SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];
				
				if (strtolower($tint_color)=='black grey')//Changer Black Grey pour Grey: demande hko
					$tint_color='Grey';
				
		}else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";
		}//End IF
				
				
	
		$queryMirror  = "SELECT category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysql_error());
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;		
			}
				

		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
		
		if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
		
		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$supplier=$EdgeItem[supplier];
			}
			
	
		
		//On demande les jobs uncut puisqu'on enverra pas de monture à HKO
		$job_type = 'Uncut';	

	
		
		
	if ($supplier == 'KUBIK ONE-CA'){
		//Remplacer le ONE dans le modele pour KK, EX: ONE8011 devient --> KK8011 demande d'HKO confirmé par Roberto le 27 août 2019.
		$frame_model =str_replace('ONE','KK',$frame_model);
		$job_type    = 'Edge and Mount';
	}//END IF
	
	
	//Demande d'HKO: remplacer la virgule dans le nom du produit par un point, car ils utilisent la virgule comme caractère délimitateur
	$orderItem["order_product_name"]=	str_replace(',','.',$orderItem["order_product_name"]);
	
	echo '<br><br>Order num    ' . $orderItem["order_num"] . '&nbsp;&nbsp;Eye:'.  $orderItem["eye"];
	$THE_EYE = $orderItem["eye"];
	
	
	if($THE_EYE == "R.E."){
			$orderItem["le_pd"]      = "0";
			$orderItem["le_sphere"]  = "0";
			$orderItem["le_cyl"]     = "0";
			$orderItem["le_pr_ax2"]  = "0";
			$orderItem["le_pr_ax"]   = "0";
			$orderItem["le_axis"]    = "0";
			$orderItem["le_add"]     = "0";
			$orderItem["le_height"]  = "0";
			$orderItem["le_pd"]		 = "0";
			$orderItem["le_pd_near"] = "0";
		}
		
		if( $THE_EYE == "L.E."){
			$orderItem["re_pd"]      = "0";
			$orderItem["re_sphere"]  = "0";
			$orderItem["re_cyl"]     = "0";
			$orderItem["re_pr_ax2"]  = "0";
			$orderItem["re_pr_ax"]   = "0";
			$orderItem["re_axis"]    = "0";
			$orderItem["re_add"]     = "0";
			$orderItem["re_height"]  = "0";
			$orderItem["re_pd"]		 = "0";
			$orderItem["re_pd_near"] = "0";
		}
		
		if ($orderItem["le_add"]=='-'){
				$orderItem["le_add"] = 0;
		}
		
		if ($orderItem["re_add"]=='-'){
				$orderItem["re_add"] = 0;
		}
		
		
	
				
				
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"]
	.'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"]
	.'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"]
	.'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'
	.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		/*$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"]
		.'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'
		.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"]
		.'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'
		.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'
		.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"]
		.'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model
		.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	
		.$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'.chr(13);*/
		
			$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"]
		.'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'
		.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"]
		.'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'
		.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'
		.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"]
		.'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model
		.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	
		.$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'. ',"'. $corridor.'"'.chr(13);



			}
					
	return $outputstring;
			
}





















function export_order_KNR($order_num){

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$Query="SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
	
	$UV400 = $orderItem[UV400];
	//Special Instruction
	$special_instructions=addslashes($orderItem["special_instructions"]);
	$special_instructions=strtoupper($special_instructions);
	//echo '<br>Special instruction avant enlever corridor: '. $special_instructions;
	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
		$Position_MM = strpos($special_instructions,'MM');
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center
	
	$special_instructions = ' '. $special_instructions;
	$PositionBaseCurve = strpos($special_instructions,'BASE CURVE');
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		//echo '<br>Special instruction:<br>'. $special_instructions;
		//echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		//echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		//echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve
	
	

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR': 	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'Xlr':		 	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'HD AR':	 	$orderItem["order_product_coating"] = "HDC";	  			break;
						case 'HD AR Backside':	 $orderItem["order_product_coating"] = "CR1/2G";	  	break;						
						case 'DH2':		 	$orderItem["order_product_coating"] = "HC";      			break;
						case 'DH1':		 	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'Smart AR': 	$orderItem["order_product_coating"] = "CR+G";    			break;
						case 'Hard Coat':	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'ITO AR':   	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'AR':		 	$orderItem["order_product_coating"] = "CR+ETC";				break;
						case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";				break;
						case 'MultiClear AR Backside':$orderItem["order_product_coating"] = "1/2HDC";	break;
						case 'Uncoated': 	 $orderItem["order_product_coating"] = " ";	  				break;
						case 'BluCut':		 $orderItem["order_product_coating"] = "Luxar Clear Blue";	break;
		}
		

		$accQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];
		
		$labQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$extra_product=addslashes($orderItem["extra_product"]);
		
		//echo '<br><br>Order num: '.  $order_num . ' Order from: '.  $orderItem[order_from];
		if (($orderItem[order_from]!="ifcclubca") &&  ($orderItem[order_from]!="safety")){
			$ProdQuery="SELECT product_code,color_code FROM exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="safety"){
			$ProdQuery="SELECT product_code,color_code FROM safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="ifcclubca"){
			$ProdQuery="SELECT product_code,color_code FROM ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		
		$ProdResult = mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$nbrResult  = mysqli_num_rows($ProdResult);
		
			
		if ($nbrResult < 1){
			$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		}
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
	    $color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];

		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
			
		$VerifIfShape = "SELECT myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
				
		if ($TheShape <> "") {
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
				
		$EngrQuery = "SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult= mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem  = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount = mysqli_num_rows($EngrResult);
		
		if ($usercount!=0){
				$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		
		$TintQuery="SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];
				
				if (strtolower($tint_color)=='black grey')//Changer Black Grey pour Grey: demande hko
					$tint_color='Grey';
				
		}else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";
		}//End IF
				
				
	
		$queryMirror  = "SELECT category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysql_error());
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;		
			}
				

		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
		
		if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
		
		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$supplier=$EdgeItem[supplier];
			}
	
		
		
	
	echo '<br><br>Order num    ' . $orderItem["order_num"] . '&nbsp;&nbsp;Eye:'.  $orderItem["eye"];
	$THE_EYE = $orderItem["eye"];
	
	
	if($THE_EYE == "R.E."){
			$orderItem["le_pd"]      = "0";
			$orderItem["le_sphere"]  = "0";
			$orderItem["le_cyl"]     = "0";
			$orderItem["le_pr_ax2"]  = "0";
			$orderItem["le_pr_ax"]   = "0";
			$orderItem["le_axis"]    = "0";
			$orderItem["le_add"]     = "0";
			$orderItem["le_height"]  = "0";
			$orderItem["le_pd"]		 = "0";
			$orderItem["le_pd_near"] = "0";
		}
		
		if( $THE_EYE == "L.E."){
			$orderItem["re_pd"]      = "0";
			$orderItem["re_sphere"]  = "0";
			$orderItem["re_cyl"]     = "0";
			$orderItem["re_pr_ax2"]  = "0";
			$orderItem["re_pr_ax"]   = "0";
			$orderItem["re_axis"]    = "0";
			$orderItem["re_add"]     = "0";
			$orderItem["re_height"]  = "0";
			$orderItem["re_pd"]		 = "0";
			$orderItem["re_pd_near"] = "0";
		}
		
		if ($orderItem["le_add"]=='-'){
				$orderItem["le_add"] = 0;
		}
		
		if ($orderItem["re_add"]=='-'){
				$orderItem["re_add"] = 0;
		}
		
		$PositionEdgePolish = strpos($special_instructions,'EDGE POLISH');
		if ($PositionEdgePolish !== false) {
			$EDGE_POLISH   = 'Yes';
		}else{
			$EDGE_POLISH   = 'No';
		}
	
			
	//Ajout demande d'Alain de demander UNCUT si il n'y a pas de trace d'Attaché dans Optipro pour éviter que KNR recoive des commandes remote edging sans traces.
	if ($orderItem[shape_name_bk]==''){
			$job_type="Uncut";
	}//END IF
			
				
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	
		.$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'.chr(13);



			}
					
	return $outputstring;
			
}//Fin export order KNR







function export_order_KNR_HBC($order_num){

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$Query="SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){

	$UV400 = $orderItem[UV400];
	//Special Instruction
	$special_instructions=addslashes($orderItem["special_instructions"]);
	$special_instructions=strtoupper($special_instructions);
	//echo '<br>Special instruction avant enlever corridor: '. $special_instructions;
	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
		$Position_MM = strpos($special_instructions,'MM');
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center
	
	$special_instructions = ' '. $special_instructions;
	$PositionBaseCurve = strpos($special_instructions,'BASE CURVE');
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		//echo '<br>Special instruction:<br>'. $special_instructions;
		//echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		//echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		//echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve
	
	

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR': 	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'Xlr':		 	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'HD AR':	 	$orderItem["order_product_coating"] = "HDC";	  			break;
						case 'HD AR Backside':	 $orderItem["order_product_coating"] = "CR1/2G";	  	break;						
						case 'DH2':		 	$orderItem["order_product_coating"] = "HC";      			break;
						case 'DH1':		 	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'Smart AR': 	$orderItem["order_product_coating"] = "CR+G";    			break;
						case 'Hard Coat':	$orderItem["order_product_coating"] = "HC";	  				break;
						case 'ITO AR':   	$orderItem["order_product_coating"] = "CR+ETC";  			break;
						case 'AR':		 	$orderItem["order_product_coating"] = "CR+ETC";				break;
						case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";				break;
						case 'MultiClear AR Backside':$orderItem["order_product_coating"] = "1/2HDC";	break;
						case 'Uncoated': 	 $orderItem["order_product_coating"] = " ";	  				break;
						case 'BluCut':		 $orderItem["order_product_coating"] = "Luxar Clear Blue";	break;
		}
		

		$accQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];
		
		$labQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$extra_product=addslashes($orderItem["extra_product"]);
		
		//echo '<br><br>Order num: '.  $order_num . ' Order from: '.  $orderItem[order_from];
		if ($orderItem[order_from]=="hbc"){

			$ProdQuery="SELECT product_code FROM ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
			echo '<br>'.$ProdQuery;
		}
		
		$ProdResult = mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$nbrResult  = mysqli_num_rows($ProdResult);
		
			
		if ($nbrResult < 1){
			$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		}
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
		$product_code=$ProdItem[product_code];

		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
			
		$VerifIfShape = "SELECT myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
				
		if ($TheShape <> "") {
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
				
		$EngrQuery = "SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult= mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem  = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount = mysqli_num_rows($EngrResult);
		
		if ($usercount!=0){
				$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		
		$TintQuery="SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];
				
				if (strtolower($tint_color)=='black grey')//Changer Black Grey pour Grey: demande hko
					$tint_color='Grey';
				
		}else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";
		}//End IF
				
				
	
		$queryMirror  = "SELECT category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysql_error());
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;		
			}
				

		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
		
		if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
		
		$EdgeQuery="SELECT * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$supplier=$EdgeItem[supplier];
			}
	
		
		
	
	echo '<br><br>Order num    ' . $orderItem["order_num"] . '&nbsp;&nbsp;Eye:'.  $orderItem["eye"];
	$THE_EYE = $orderItem["eye"];
	
	
	if($THE_EYE == "R.E."){
			$orderItem["le_pd"]      = "0";
			$orderItem["le_sphere"]  = "0";
			$orderItem["le_cyl"]     = "0";
			$orderItem["le_pr_ax2"]  = "0";
			$orderItem["le_pr_ax"]   = "0";
			$orderItem["le_axis"]    = "0";
			$orderItem["le_add"]     = "0";
			$orderItem["le_height"]  = "0";
			$orderItem["le_pd"]		 = "0";
			$orderItem["le_pd_near"] = "0";
		}
		
		if( $THE_EYE == "L.E."){
			$orderItem["re_pd"]      = "0";
			$orderItem["re_sphere"]  = "0";
			$orderItem["re_cyl"]     = "0";
			$orderItem["re_pr_ax2"]  = "0";
			$orderItem["re_pr_ax"]   = "0";
			$orderItem["re_axis"]    = "0";
			$orderItem["re_add"]     = "0";
			$orderItem["re_height"]  = "0";
			$orderItem["re_pd"]		 = "0";
			$orderItem["re_pd_near"] = "0";
		}
		
		if ($orderItem["le_add"]=='-'){
				$orderItem["le_add"] = 0;
		}
		
		if ($orderItem["re_add"]=='-'){
				$orderItem["re_add"] = 0;
		}
		
		$PositionEdgePolish = strpos($special_instructions,'EDGE POLISH');
		if ($PositionEdgePolish !== false) {
			$EDGE_POLISH   = 'Yes';
		}else{
			$EDGE_POLISH   = 'No';
		}
	
				
				
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	
		.$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'.chr(13);



			}
					
	return $outputstring;
			
}//Fin export order KNR HBC









function export_order_Conant($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		//Modification request by HKO  2010-08-11
		switch($orderItem["order_product_coating"]){
			case 'Dream AR':				$shipping_code = "NA108";			break;
			case 'DH2':						$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Hard Coat':				$shipping_code = "NA108";			break;
			case 'ITO AR':					$shipping_code = "NA108";			break;
			case 'Aqua Dream AR':			$shipping_code = "NA108";			break;
			case 'Uncoated    ':			$shipping_code = "NA108";			break;
			case 'MultiClear AR':			$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Multiclear AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;
						
		}
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";				break;
						case 'DH2':						$orderItem["order_product_coating"] = "HC";				break;
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			break;
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";				break;
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";			break;
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			break;
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";				break;
						case 'Nu':				        $orderItem["order_product_coating"] = " ";				break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		if ($orderItem[lab]==37){
		$ProdQuery="select product_code,color_code from ifc_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			//$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging' OR order_num='$orderItem[order_num]' AND category='Edging_Frame' "; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				
				
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
			if ($orderItem["frame_type"]=="Plastique")
				{
				$orderItem["frame_type"] = "Plastic";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
				
		if ($orderItem["eye"] == "Both")//Both eyes are in the Rx		
		{
				
			if ((strlen($orderItem["le_pd"])>1)  && ($orderItem["le_pd"] <> '-') && ($orderItem["le_pd"] <> 'û')){
			$le_pd = $orderItem["le_pd"];
			}else {
			$le_pd = "";
			}
			
			if ((strlen($orderItem["re_pd"])>1)  && ($orderItem["re_pd"] <> '-') && ($orderItem["re_pd"] <> 'û')){
			$re_pd = $orderItem["re_pd"];
			}else {
			$re_pd = "";
			}
	
			if ((strlen($orderItem["le_pd_near"])>1) && ($orderItem["le_pd_near"] <> '-') && ($orderItem["le_pd_near"] <> 'û')){
			$le_pd_near = $orderItem["le_pd_near"];
			}else {
			$le_pd_near = "";
			}
			
			
			if ((strlen($orderItem["re_pd_near"])>1) && ($orderItem["re_pd_near"] <> '-') && ($orderItem["re_pd_near"] <> 'û')){
			$re_pd_near = $orderItem["re_pd_near"];
			}else {
			$re_pd_near = "";
			}
	
	
	
			if ((strlen($orderItem["le_height"])>1) && ($orderItem["le_height"] <> '-') && ($orderItem["le_height"] <> 'û')){
			$le_height = $orderItem["le_height"];
			}else {
			$le_height = "";
			}
			
			if ((strlen($orderItem["re_height"])>1) && ($orderItem["re_height"] <> '-') && ($orderItem["re_height"] <> 'û')){
			$re_height = $orderItem["re_height"];
			}else {
			$re_height = "";
			}
	
			if ((strlen($orderItem["re_add"])>1)&& ($orderItem["re_add"] <> '-') && ($orderItem["re_add"] <> 'û')){
			$re_add = $orderItem["re_add"];
			}else {
			$re_add = "";
			}	
	
			if ((strlen($orderItem["le_add"])>1) && ($orderItem["le_add"] <> '-') && ($orderItem["le_add"] <> 'û')){
			$le_add = $orderItem["le_add"];
			}else {
			$le_add = "";
			}
			
			if (($orderItem["re_axis"]>0)  && ($orderItem["re_axis"] <> '-') && ($orderItem["re_axis"] <> 'û')){
			$re_axis = $orderItem["re_axis"];
			}else {
			$re_axis = "";
			}
	
			if (($orderItem["le_axis"]>0)  && ($orderItem["le_axis"] <> '-') && ($orderItem["le_axis"] <> 'û')){
			$le_axis = $orderItem["le_axis"];
			}else {
			$le_axis = "";
			}
	
			
			if ((strlen($orderItem["re_pr_ax"])>0) && ($orderItem["re_pr_ax"] <> '-') && ($orderItem["re_pr_ax"] <> 'û')){
			$re_pr_ax = $orderItem["re_pr_ax"];
			}else {
			$re_pr_ax = "0";
			}
				
			
			if ((strlen($orderItem["le_pr_ax"])>0) && ($orderItem["le_pr_ax"] <> '-') && ($orderItem["le_pr_ax"] <> 'û')){
			$le_pr_ax = $orderItem["le_pr_ax"];
			}else {
			$le_pr_ax = "0";
			}
	
			if ((strlen($orderItem["re_pr_ax2"])>0) && ($orderItem["re_pr_ax2"] <> '-') && ($orderItem["re_pr_ax2"] <> 'û')){
			$re_pr_ax2 = $orderItem["re_pr_ax2"];
			}else {
			$re_pr_ax2 = "0";
			}
			
			if ((strlen($orderItem["le_pr_ax2"])>0) && ($orderItem["le_pr_ax2"] <> '-') && ($orderItem["le_pr_ax2"] <> 'û') ){
			$le_pr_ax2 = $orderItem["le_pr_ax2"];
			}else {
			$le_pr_ax2 = "0";
			}
	
			if ((strlen($orderItem["re_cyl"])>1) && ($orderItem["re_cyl"] <> '-') && ($orderItem["re_cyl"] <> 'û') ){
			$re_cyl = $orderItem["re_cyl"];
			}else {
			$re_cyl = "0";
			}
	
			if ((strlen($orderItem["le_cyl"])>1) && ($orderItem["le_cyl"] <> '-') && ($orderItem["le_cyl"] <> 'û') ){
			$le_cyl = $orderItem["le_cyl"];
			}else {
			$le_cyl = "0";
			}
			
			if ((strlen($orderItem["re_sphere"])>1) && ($orderItem["re_sphere"] <> '-') && ($orderItem["re_sphere"] <> 'û') ){		
			$re_sphere = $orderItem["re_sphere"];
			}else {
			$re_sphere = "0";
			}
			
			if ((strlen($orderItem["le_sphere"])>1) && ($orderItem["le_sphere"] <> "-") && ($orderItem["le_sphere"] <> 'û') ){	
			$le_sphere = $orderItem["le_sphere"];
			}else {
			$le_sphere = "0";
			}
		}
		
		if( $orderItem["eye"]=="R.E."){
		$le_pd = "0";
		$le_sphere = "0";
		$le_cyl="0";
		$le_pr_ax2 ="0";
		$le_pr_ax ="0";
		$le_axis ="0";
		$le_add ="0";
		$le_height ="0";
		$le_pd ="0";
		$le_pd_near ="0";
		$re_pd 	     = $orderItem["re_pd"];
		$re_sphere   = $orderItem["re_sphere"];
		$re_cyl      = $orderItem["re_cyl"];
		$re_pr_ax2   = $orderItem["re_pr_ax2"];
		$re_pr_ax    = $orderItem["re_pr_ax"];
		$re_axis     = $orderItem["re_axis"];
		$re_add      = $orderItem["re_add"];
		$re_height   = $orderItem["re_height"];
		$re_pd       = $orderItem["re_pd"];
		$re_pd_near  = $orderItem["re_pd_near"];
		}
		
		if( $orderItem["eye"]=="L.E."){
		$re_pd = "0";
		$re_sphere = "0";
		$re_cyl="0";
		$re_pr_ax2 ="0";
		$re_pr_ax ="0";
		$re_axis ="0";
		$re_add ="0";
		$re_height ="0";
		$re_pd ="0";
		$re_pd_near ="0";
		
		$le_pd 	     = $orderItem["ll_pd"];
		$le_sphere   = $orderItem["le_sphere"];
		$le_cyl      = $orderItem["le_cyl"];
		$le_pr_ax2   = $orderItem["le_pr_ax2"];
		$le_pr_ax    = $orderItem["le_pr_ax"];
		$le_axis     = $orderItem["le_axis"];
		$le_add      = $orderItem["le_add"];
		$le_height   = $orderItem["le_height"];
		$le_pd       = $orderItem["le_pd"];
		$le_pd_near  = $orderItem["le_pd_near"];
		}
		
		
		
		if( $orderItem["eye"]=="R.E."){
			$le_pd      = "0";
			$le_sphere  = "0";
			$le_cyl     = "0";
			$le_pr_ax2  = "0";
			$le_pr_ax   = "0";
			$le_axis    = "0";
			$le_add     = "0";
			$le_height  = "0";
			$le_pd 	    = "0";
			$le_pd_near = "0";
		}
		
		if( $orderItem["eye"]=="L.E."){
			$re_pd       = "0";
			$re_sphere   = "0";
			$re_cyl	     = "0";
			$re_pr_ax2   = "0";
			$re_pr_ax    = "0";
			$re_axis 	 = "0";
			$re_add 	 = "0";
			$re_height   = "0";
			$re_pd 	     = "0";
			$re_pd_near  = "0";
		}
		
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$re_sphere.'","'.$le_sphere.'","'.$re_cyl.'","'.$le_cyl.'","'.$re_add.'","';
		
		$outputstring.=$le_add.'","'.$re_axis.'","'.$le_axis.'","'.$re_pr_ax.'","'.$le_pr_ax.'","'.$re_pr_ax2.'","'.$le_pr_ax2.'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$re_pd.'","'.$re_pd_near.'","'.$re_height.'","'.$le_pd.'","'.$le_pd_near .'","'.$le_height.'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}//End While
			
	return $outputstring;
			
}






function export_order_DLAB($order_num){
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
	$Query="select * from orders WHERE order_num=$order_num"; //Get Order Data
	$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because 0.1: ' . $Query.' ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
	//echo '<br>cle'. $orderItem[primary_key];
		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
		if ($orderItem["redo_order_num"] <>''){
			$orderItem["order_num"] = $orderItem["order_num"] . 'R';	
		}
		
		if ($orderItem["shipping_code"] <> ''){
			$shipping_code =$orderItem["shipping_code"];
		}else{
			//$shipping_code = 'OR009-SCT';
		}
		

		switch($orderItem["order_product_coating"]){
						case 'Dream AR':	 $orderItem["order_product_coating"] = "HC";		break;
						case 'DH2':			 $orderItem["order_product_coating"] = "HC";		break;
						case 'Smart AR':	 $orderItem["order_product_coating"] = "CR+G";		break;
						case 'Hard Coat':	 $orderItem["order_product_coating"] = "HC";		break;
						case 'ITO AR':		 $orderItem["order_product_coating"] = "CR+ETC";	break;
						case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";		break;
						case 'Uncoated':	 $orderItem["order_product_coating"] = " ";			break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)	or die  ('I cannot select items because 0.2: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)	or die  ('I cannot select items because 0.3: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)	or die  ('I cannot select items because 0.4: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$special_instructions = addslashes($orderItem["special_instructions"]);
		
		//PARTIE UV400
		if ($orderItem["UV400"] <> ""){
			$special_instructions = $special_instructions . ' UV400';		
		}//END IF
		
		
		$extra_product        = addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysqli_query($con,$ProdQuery)	or die  ('I cannot select items because 0.5: ' . mysqli_error($con));
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
			
		$color_code   = $ProdItem[color_code];
		$product_code = $ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)	or die  ('I cannot select items because 0.6: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
			
		$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because 0.7: ' . mysqli_error($con));
		$EngrItem=mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EngrResult);
		if ($usercount!=0){
			$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because 0.8: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery  = "select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult = mysqli_query($con,$EdgeQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem   = mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EdgeResult);
		
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
			}else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
			}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		
		if ($orderItem["myupload"] =="") {
			$myupload = "none";
		}else{
			$myupload = $orderItem["myupload"] ;
		}

		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'","'.$myupload .'"'. "\r\n";
			}
			
	return $outputstring;
			
}







function export_order_DLAB_HBC($order_num){
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
	$Query="select * from orders WHERE order_num=$order_num"; //Get Order Data
	$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because 0.1: ' . $Query.' ' . mysqli_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
	//echo '<br>cle'. $orderItem[primary_key];
		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		
		if ($orderItem["redo_order_num"] <>''){
			$orderItem["order_num"] = $orderItem["order_num"] . 'R';	
		}
		
		if ($orderItem["shipping_code"] <> ''){
			$shipping_code =$orderItem["shipping_code"];
		}else{
			//$shipping_code = 'OR009-SCT';
		}
		

		switch($orderItem["order_product_coating"]){
						case 'Dream AR':	 $orderItem["order_product_coating"] = "HC";		break;
						case 'DH2':			 $orderItem["order_product_coating"] = "HC";		break;
						case 'Smart AR':	 $orderItem["order_product_coating"] = "CR+G";		break;
						case 'Hard Coat':	 $orderItem["order_product_coating"] = "HC";		break;
						case 'ITO AR':		 $orderItem["order_product_coating"] = "CR+ETC";	break;
						case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";		break;
						case 'Uncoated':	 $orderItem["order_product_coating"] = " ";			break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)	or die  ('I cannot select items because 0.2: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)	or die  ('I cannot select items because 0.3: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)	or die  ('I cannot select items because 0.4: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$special_instructions = addslashes($orderItem["special_instructions"]);
		$extra_product        = addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysqli_query($con,$ProdQuery)	or die  ('I cannot select items because 0.5: ' . mysqli_error($con));
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
			
		$color_code   = $ProdItem[color_code];
		$product_code = $ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)	or die  ('I cannot select items because 0.6: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
			
		$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because 0.7: ' . mysqli_error($con));
		$EngrItem=mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EngrResult);
		if ($usercount!=0){
			$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because 0.8: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery  = "select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult = mysqli_query($con,$EdgeQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem   = mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EdgeResult);
		
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
			}else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
			}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		
		if ($orderItem["myupload"] =="") {
			$myupload = "none";
		}else{
			$myupload = $orderItem["myupload"] ;
		}

		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'","'.$myupload .'"'. "\r\n";
			}
			
	return $outputstring;
			
}



function export_order_IFC($order_num){

	$Query="select orders.*, ifc_exclusive.product_code from orders, ifc_exclusive WHERE orders.order_product_id = ifc_exclusive.primary_key  AND order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);		
		$realTotal = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
	
		$queryFrm = "SELECT  temple_model_num   FROM extra_product_orders WHERE category = 'Frame' and order_num  =". $orderItem["order_num"] ;
		$resultFrm=mysql_query($queryFrm)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrResult = mysql_num_rows($resultFrm);
		if ($NbrResult > 0){
		$DataFrm=mysql_fetch_array($resultFrm);
		
		
		$QueryProductCode = "SELECT  product_code   FROM ifc_exclusive WHERE primary_key = ". $orderItem["order_product_id"] ;
		echo $QueryProductCode . '<br>';
		$resultProductCode=mysql_query($QueryProductCode)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrdeResult = mysql_num_rows($resultProductCode);
		if ($NbrdeResult > 0){
		$DataProductCode=mysql_fetch_array($resultProductCode);
		}
		
		
		}
		
		
		//$outputstring.= $orderItem["order_num"].','.$orderItem["product_code"].','.$orderItem["patient_ref_num"] .','.$realTotal  . "\r\n";
		$outputstring.= $accItem["company"]  .','.$orderItem["order_product_name"]  .','.$DataProductCode["product_code"] .','.$DataFrm["temple_model_num"] .','.$orderItem["order_quantity"] .','.$orderItem["order_patient_first"] 
		. ' ' .$orderItem["order_patient_last"] .','.$orderItem["order_num"] .','.$orderItem["order_date_processed"]  .','.$realTotal  . "\r\n";
		
//accounts.Account_num, (code client)
//accounts.first_name accounts.last_name, (nom du client)
 //			(Référence complète de la monture)
//orders.order_quantity, (Quantité)
 //orders.order_patient_first 	orders.order_patient_last , (Nom du porteur)
//orders.order_num, (numéro de commande)
//orders.order_date_processed, (date de la commande)
//orders.order_total )Montant total de la commande)
		
			}		
	return $outputstring;		
}

 	 	 




function export_order_SOI($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);		
		$realTotal = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		$outputstring.=$accNum. ',' . $orderItem["order_num"].','.$orderItem["order_date_shipped"].','.$realTotal  . "\r\n";
			}		
	return $outputstring;		
}



function export_order_SOI_NET($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);	
		$orderTotalMajorer = ($orderItem["order_total"] + $orderItem["order_shipping_cost"]) * 1.335;//On met ici le pourcentage a majorer 1.25 = 25% de majoration
		$orderTotalMajorer = money_format('%.2n',$orderTotalMajorer);
		$outputstring.=$accNum. ',' . $orderItem["order_num"].','.$orderItem["order_date_shipped"].','.$orderTotalMajorer . "\r\n";
			}		
	return $outputstring;		
}






function export_credit_SOI($order_num){
	$Query="select * from memo_credits WHERE mcred_order_num='$order_num'"; //Get credit Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[mcred_acct_user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);	
		$accNum=$accItem[account_num];	
		
		 if ($orderItem["mcred_cred_type"] =="debit"){
		$outputstring = "";		
		$outputstring.=$accNum. ',' . $orderItem["mcred_order_num"].','.$orderItem["mcred_date"].','.$orderItem["mcred_abs_amount"] . "\r\n";
		}else{
		$outputstring = "";		
		$outputstring.=$accNum. ',' . $orderItem["mcred_order_num"].','.$orderItem["mcred_date"].',-'.$orderItem["mcred_abs_amount"] . "\r\n";
		}
		
		
			}	
	return $outputstring;		
}




function export_credit_Conso($order_num){
	$Query="select * from memo_credits WHERE mcred_order_num='$order_num'"; //Get credit Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[mcred_acct_user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);	
		$accNum=$accItem[account_num];	
		
		 if ($orderItem["mcred_cred_type"] =="debit"){
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n"   ;
		}else{
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',-' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n" ;
		}
		
			}	
	return $outputstring;		
}


function export_order_Conso($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		//$special_instructions=addslashes($orderItem["special_instructions"]);
		//$extra_product=addslashes($orderItem["extra_product"]);		
		$outputstring.= $orderItem["order_num"].','.$orderItem["order_date_processed"] .','.$orderItem["order_date_shipped"].','.$orderItem["order_total"].','.$orderItem["order_patient_first"] . ' '   . $orderItem["order_patient_last"] .','.$accNum.','.$orderItem["user_id"] . "\r\n";
			}		
	return $outputstring;		
}




function get_IFC_header_string(){//CREATE HEADER LIST

$headerstring='"NOM DU MAGASIN",';
$headerstring.='"PRODUIT",';
$headerstring.='"CODE ARTICLE",';
$headerstring.='"REFERENCE MONTURE",';
$headerstring.='"QTE",';
$headerstring.='"NOM PORTEUR",';
$headerstring.='"NUMERO COMMANDE",';
$headerstring.='"DATE COMMANDE",';
$headerstring.='"MONTANT TOTAL",'.chr(13);

return $headerstring;
}









function get_header_string(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}






















function get_header_string_swiss(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';

$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';

$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}






function get_header_string_swiss_2014(){//CREATE HEADER LIST  Include the Safety collumn

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';

$headerstring.='"BASE CURVE",';
$headerstring.='"SWISS EDGING BARCODE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"SAFETY",';
/*
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);
*/

$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"IIMPACT",';
$headerstring.='"CORRIDOR"'.chr(13);

return $headerstring;
}



















































function get_header_string_crystal(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';

$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"INSET",';

$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}









function get_header_stringIFC(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"RE SPHERE POS",';
$headerstring.='"RE CYLINDER POS",';
$headerstring.='"RE AXIS POS",';
$headerstring.='"LE SPHERE POS",';
$headerstring.='"LE CYLINDER POS",';
$headerstring.='"LE AXIS POS"'.chr(13);


return $headerstring;
}



function get_header_string_SOI(){//CREATE HEADER LIST

$headerstring='';
$headerstring.= chr(13);

return $headerstring;
}







function get_header_string_HKO(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"EDGE POLISH",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"UV400",';
$headerstring.='"CORRIDOR",';
$headerstring.='"SAFETY"'.chr(13);
return $headerstring;
}



function get_header_string_HKO_HBC(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"EDGE POLISH",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"UV400",';
$headerstring.='"CORRIDOR"'.chr(13);
return $headerstring;
}



function get_header_string_KNR(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"EDGE POLISH",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"UV400"'.chr(13);

return $headerstring;
}





















function Export_Rebilling_Admin($order_num){


	
	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	
	
	while ($orderItem=mysql_fetch_array($Result)){
	
	
		$queryElabPrice = "Select e_lab_can_price as theprice from exclusive where primary_key =" . $orderItem[order_product_id];
		$rptElabPrice=mysql_query($queryElabPrice);
		$DataElabPrice=mysql_fetch_array($rptElabPrice);
		$elabPrice = $DataElabPrice['theprice'];
		if ($orderItem[eye] != "Both") {
		$elabPrice = $elabPrice/2;
		}
	
	
		$queryCompany = "Select company from accounts where user_id = (Select user_id from orders where order_num = $order_num LIMIT 0,1) ";
		$ResultCompany=mysql_query($queryCompany)	or die  ('I cannot select items because: ' . mysql_error());
		$DataCompany=mysql_fetch_array($ResultCompany);
		$company = $DataCompany['company'];
	
		$outputstring.='"'.$orderItem["order_num"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$company.'","'.$orderItem["patient_ref_num"].'","'. $orderItem["order_patient_first"]. ' ' . $orderItem["order_patient_last"].'","'.$orderItem["order_product_name"].'","'.$orderItem["order_total"].'","'.$elabPrice.'"'. "\r\n";
			}
			
	return $outputstring;
			
}









function export_order_Stock_DLAB($primary_key){

	$Query="select * from orders WHERE primary_key='$primary_key' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		


		$QueryCodeOpc="select right_opc, left_opc from products WHERE primary_key ='$orderItem[order_product_id]'"; //ACCOUNT INFO SECTION
		$ResultCodeOpc=mysql_query($QueryCodeOpc)	or die  ('I cannot select items because: ' . mysql_error());
		$DataOpc=mysql_fetch_array($ResultCodeOpc);
	    $Stock_OPC =$DataOpc[right_opc];
		$orderItem["order_item_number"] = $Stock_OPC;

		
		$shipping_code =$orderItem["shipping_code"];

		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";		
						break;
						
						case 'DH2':						$orderItem["order_product_coating"] = "HC";	
						break;
						
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			
						break;
						
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";					
						break;
						
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";				
						break;
						
						
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			
						break;
						
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";			
						break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$Stock_OPC.'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		echo $Stock_OPC . '<br>';
		
		if ($orderItem["shape_name_bk"] =="") {
		$myupload = "none";
		}else{
		$myupload = $orderItem["shape_name_bk"] ;
		}
		//$orderItem["order_patient_first"]="xxx";//XXX out certain field
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
		$accItem[company]= str_replace(',',' ',$accItem[company]);
		$accItem[ship_address1]= str_replace(',',' ',$accItem[ship_address1]);
		$accItem[ship_address2]= str_replace(',',' ',$accItem[ship_address2]);
		$accItem[ship_city]= str_replace(',',' ',$accItem[ship_city]);
		$accItem[ship_zip]= str_replace(',',' ',$accItem[ship_zip]);
		$accItem[ship_country]= str_replace(',',' ',$accItem[ship_country]);
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$orderItem["order_item_number"].'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
	/*	$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum .'","'.$myupload .'"'. "\r\n";*/
$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum .'","'.$myupload .'","'. $accItem[company].'","'. $accItem[ship_address1] .'","'. $accItem[ship_address2] .'","'. $accItem[ship_city] .'","'. $accItem[ship_state] .'","'. $accItem[ship_zip].'","'. $accItem[ship_country] .'","'. $accItem[depot_number] .'","'. $accItem[bill_to] .'"' . "\r\n";
	
			}
			
	return $outputstring;
			
}
















function export_order_Conant_IFC($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		//Modification request by HKO  2010-08-11
		switch($orderItem["order_product_coating"]){
			case 'Dream AR':				$shipping_code = "NA108";			break;
			case 'DH2':						$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Hard Coat':				$shipping_code = "NA108";			break;
			case 'ITO AR':					$shipping_code = "NA108";			break;
			case 'Aqua Dream AR':			$shipping_code = "NA108";			break;
			case 'Uncoated    ':			$shipping_code = "NA108";			break;
			case 'MultiClear AR':			$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Multiclear AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;
						
		}
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";				break;
						case 'DH2':						$orderItem["order_product_coating"] = "HC";				break;
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			break;
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";				break;
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";			break;
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			break;
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";				break;
						case 'Nu':				        $orderItem["order_product_coating"] = " ";				break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		if ($orderItem[lab]==37){
		$ProdQuery="select product_code,color_code from ifc_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			//$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];
				
					
					if ($tint_color == 'Brun'){
					$tint_color = 'Brown';
					}
					
					if ($tint_color == 'Grey'){
					$tint_color = 'Grey';
					}
					
									
					if ($tint == 'Solid 60'){
					$tint='Solid';
					$from_perc=60;
					$to_perc=60;
					$tint_color = 	$tint_color . '-' .  $from_perc  . '%';
					}
					
					if ($tint == 'Solid 80'){
					$tint='Solid';
					$from_perc=82;
					$to_perc=82;
					$tint_color = 	$tint_color . '-' .  $from_perc . '%' ;
					}
					
}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging_Frame'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				
				
				
		$FrameModelQuery="select * from ifc_frames_french 
		WHERE code ='$EdgeItem[temple_model_num]' AND color ='$EdgeItem[color]'
		OR model   ='$EdgeItem[temple_model_num]' AND color ='$EdgeItem[color]' "; //Get Frame details
		echo '<br>'. $FrameModelQuery;
		$FrameModelResult=mysql_query($FrameModelQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrameModel=mysql_fetch_array($FrameModelResult);
		$frame_model = $DataFrameModel[upc];
		$color=$DataFrameModel[color_code];
		echo 'Frame model : ' . $frame_model. '  Color Code:'   . $color .  '<br>';		
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				//$frame_model=$EdgeItem[temple_model_num];
				//$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
			if ($orderItem["frame_type"]=="Plastique")
				{
				$orderItem["frame_type"] = "Plastic";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
				
		if ($orderItem["eye"] == "Both")//Both eyes are in the Rx		
		{
				
			if ((strlen($orderItem["le_pd"])>1)  && ($orderItem["le_pd"] <> '-') && ($orderItem["le_pd"] <> 'û')){
			$le_pd = $orderItem["le_pd"];
			}else {
			$le_pd = "";
			}
			
			if ((strlen($orderItem["re_pd"])>1)  && ($orderItem["re_pd"] <> '-') && ($orderItem["re_pd"] <> 'û')){
			$re_pd = $orderItem["re_pd"];
			}else {
			$re_pd = "";
			}
	
			if ((strlen($orderItem["le_pd_near"])>1) && ($orderItem["le_pd_near"] <> '-') && ($orderItem["le_pd_near"] <> 'û')){
			$le_pd_near = $orderItem["le_pd_near"];
			}else {
			$le_pd_near = "";
			}
			
			
			if ((strlen($orderItem["re_pd_near"])>1) && ($orderItem["re_pd_near"] <> '-') && ($orderItem["re_pd_near"] <> 'û')){
			$re_pd_near = $orderItem["re_pd_near"];
			}else {
			$re_pd_near = "";
			}
	
	
	
			if ((strlen($orderItem["le_height"])>1) && ($orderItem["le_height"] <> '-') && ($orderItem["le_height"] <> 'û')){
			$le_height = $orderItem["le_height"];
			}else {
			$le_height = "";
			}
			
			if ((strlen($orderItem["re_height"])>1) && ($orderItem["re_height"] <> '-') && ($orderItem["re_height"] <> 'û')){
			$re_height = $orderItem["re_height"];
			}else {
			$re_height = "";
			}
	
			if ((strlen($orderItem["re_add"])>1)&& ($orderItem["re_add"] <> '-') && ($orderItem["re_add"] <> 'û')){
			$re_add = $orderItem["re_add"];
			}else {
			$re_add = "";
			}	
	
			if ((strlen($orderItem["le_add"])>1) && ($orderItem["le_add"] <> '-') && ($orderItem["le_add"] <> 'û')){
			$le_add = $orderItem["le_add"];
			}else {
			$le_add = "";
			}
			
			if (($orderItem["re_axis"]>0)  && ($orderItem["re_axis"] <> '-') && ($orderItem["re_axis"] <> 'û')){
			$re_axis = $orderItem["re_axis"];
			}else {
			$re_axis = "";
			}
	
			if (($orderItem["le_axis"]>0)  && ($orderItem["le_axis"] <> '-') && ($orderItem["le_axis"] <> 'û')){
			$le_axis = $orderItem["le_axis"];
			}else {
			$le_axis = "";
			}
	
			
			if ((strlen($orderItem["re_pr_ax"])>0) && ($orderItem["re_pr_ax"] <> '-') && ($orderItem["re_pr_ax"] <> 'û')){
			$re_pr_ax = $orderItem["re_pr_ax"];
			}else {
			$re_pr_ax = "0";
			}
				
			
			if ((strlen($orderItem["le_pr_ax"])>0) && ($orderItem["le_pr_ax"] <> '-') && ($orderItem["le_pr_ax"] <> 'û')){
			$le_pr_ax = $orderItem["le_pr_ax"];
			}else {
			$le_pr_ax = "0";
			}
	
			if ((strlen($orderItem["re_pr_ax2"])>0) && ($orderItem["re_pr_ax2"] <> '-') && ($orderItem["re_pr_ax2"] <> 'û')){
			$re_pr_ax2 = $orderItem["re_pr_ax2"];
			}else {
			$re_pr_ax2 = "0";
			}
			
			if ((strlen($orderItem["le_pr_ax2"])>0) && ($orderItem["le_pr_ax2"] <> '-') && ($orderItem["le_pr_ax2"] <> 'û') ){
			$le_pr_ax2 = $orderItem["le_pr_ax2"];
			}else {
			$le_pr_ax2 = "0";
			}
	
			if ((strlen($orderItem["re_cyl"])>1) && ($orderItem["re_cyl"] <> '-') && ($orderItem["re_cyl"] <> 'û') ){
			$re_cyl = $orderItem["re_cyl"];
			}else {
			$re_cyl = "0";
			}
	
			if ((strlen($orderItem["le_cyl"])>1) && ($orderItem["le_cyl"] <> '-') && ($orderItem["le_cyl"] <> 'û') ){
			$le_cyl = $orderItem["le_cyl"];
			}else {
			$le_cyl = "0";
			}
			
			if ((strlen($orderItem["re_sphere"])>1) && ($orderItem["re_sphere"] <> '-') && ($orderItem["re_sphere"] <> 'û') ){		
			$re_sphere = $orderItem["re_sphere"];
			}else {
			$re_sphere = "0";
			}
			
			if ((strlen($orderItem["le_sphere"])>1) && ($orderItem["le_sphere"] <> "-") && ($orderItem["le_sphere"] <> 'û') ){	
			$le_sphere = $orderItem["le_sphere"];
			}else {
			$le_sphere = "0";
			}
		}elseif( $orderItem["eye"]=="R.E."){
		$le_pd = "0";
		$le_sphere = "0";
		$le_cyl="0";
		$le_pr_ax2 ="0";
		$le_pr_ax ="0";
		$le_axis ="0";
		$le_add ="0";
		$le_height ="0";
		$le_pd ="0";
		$le_pd_near ="0";
		
		$re_pd 	     = $orderItem["re_pd"];
		$re_sphere   = $orderItem["re_sphere"];
		$re_cyl      = $orderItem["re_cyl"];
		$re_pr_ax2   = $orderItem["re_pr_ax2"];
		$re_pr_ax    = $orderItem["re_pr_ax"];
		$re_axis     = $orderItem["re_axis"];
		$re_add      = $orderItem["re_add"];
		$re_height   = $orderItem["re_height"];
		$re_pd       = $orderItem["re_pd"];
		$re_pd_near  = $orderItem["re_pd_near"];
		
		}elseif( $orderItem["eye"]=="L.E."){
		$re_pd = "0";
		$re_sphere = "0";
		$re_cyl="0";
		$re_pr_ax2 ="0";
		$re_pr_ax ="0";
		$re_axis ="0";
		$re_add ="0";
		$re_height ="0";
		$re_pd ="0";
		$re_pd_near ="0";
		
		$le_pd 	     = $orderItem["ll_pd"];
		$le_sphere   = $orderItem["le_sphere"];
		$le_cyl      = $orderItem["le_cyl"];
		$le_pr_ax2   = $orderItem["le_pr_ax2"];
		$le_pr_ax    = $orderItem["le_pr_ax"];
		$le_axis     = $orderItem["le_axis"];
		$le_add      = $orderItem["le_add"];
		$le_height   = $orderItem["le_height"];
		$le_pd       = $orderItem["le_pd"];
		$le_pd_near  = $orderItem["le_pd_near"];
		}
		
		
		$re_sphere_pos = $re_sphere ;
		$le_sphere_pos = $le_sphere ;
		$re_cyl_pos = $re_cyl ;
		$le_cyl_pos = $le_cyl ;
		$re_axis_pos = $re_axis;
		$le_axis_pos = $le_axis;
		
		
//European conversion for Conant
	
	if ( $re_cyl <> '0'){
	$re_sphere  = $re_sphere+$re_cyl;
	if ($re_sphere>0) $re_sphere="+".$re_sphere;
	$re_cyl ="-".ABS($re_cyl);
	$re_axis=$re_axis+90;
	if ($re_axis>180) $re_axis=$re_axis-180;
	}


	if ( $le_cyl <> '0'){
	$le_sphere  = $le_sphere+$le_cyl;
	if ($le_sphere>0) $le_sphere="+".$le_sphere;
	$le_cyl="-".ABS($le_cyl);
	$le_axis=$le_axis+90;
	if ($le_axis>180) $le_axis=$le_axis-180;
	}


		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$re_sphere.'","'.$le_sphere.'","'.$re_cyl.'","'.$le_cyl.'","'.$re_add.'","';
		
		$outputstring.=$le_add.'","'.$re_axis.'","'.$le_axis.'","'.$re_pr_ax.'","'.$le_pr_ax.'","'.$re_pr_ax2.'","'.$le_pr_ax2.'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$re_pd.'","'.$re_pd_near.'","'.$re_height.'","'.$le_pd.'","'.$le_pd_near .'","'.$le_height.'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		.$orderItem["patient_ref_num"].'","' .$accNum  . '","' . ' ' . '","'  . $re_sphere_pos .'","' . $re_cyl_pos  .'","' . $re_axis_pos .  '","' . $le_sphere_pos .'","' . $le_cyl_pos  .'","' . $le_axis_pos 	.'"'.chr(13);
			}
			
	return $outputstring;
			
}








function Export_Inventory_IFC($lab_id){

	
	$Query="SELECT ifc_frames_french.*, product_inventory_ifc.product_inventory_id, product_inventory_ifc.min_inventory, product_inventory_ifc.inventory, product_inventory_ifc.last_updated, product_inventory_ifc.product_id
		FROM ifc_frames_french 
		LEFT JOIN product_inventory_ifc ON (product_inventory_ifc.product_id=ifc_frames_french.ifc_frames_id && product_inventory_ifc.lab_id='$lab_id' ) 
		ORDER BY code"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	
	
	while ($orderItem=mysql_fetch_array($Result)){
	
		$outputstring.='"'.$orderItem["upc"].'","'.$orderItem["code"].'","'.$orderItem["type"].'","'.$orderItem["color"].'","'. $orderItem["collection"] .'","' . $orderItem["inventory"].'","'.$orderItem["min_inventory"].'","'.$orderItem["last_updated"].'"'. "\r\n";
			}
			
	return $outputstring;
			
}










function export_monthly_orders_acomba($order_num){	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
	
	$queryAcomba2 = "SELECT distinct acomba_account_num FROM accounts WHERE user_id = (SELECT  user_id from orders WHERE order_num = $order_num LIMIT 0,1 )  LIMIT 0,1";
	//echo '<br><br><br>' . $queryAcomba2 . '<br>';
	$ResultAcomba2=mysqli_query($con,$queryAcomba2)		or die  ('I cannot select 1.4 items because: ' .$queryAcomba2 . ' '  . mysqli_error($con));
	$DataAcomba2=mysqli_fetch_array($ResultAcomba2,MYSQLI_ASSOC);
	
	$queryOrder = "SELECT  order_date_shipped, order_date_processed, order_num, order_total, order_from, order_shipping_cost, lab FROM orders  WHERE  order_num = $order_num LIMIT 0,1";
	//echo '<br>' . $queryOrder . '<br>';
	$ResultOrder=mysqli_query($con,$queryOrder)		or die  ('I cannot select 1.5 items because: ' . mysqli_error($con));
	$DataOrder=mysqli_fetch_array($ResultOrder,MYSQLI_ASSOC);
	
	//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($DataOrder["order_from"]){
			case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
			case "safety":		$Prefix_Facture = "7"; break;//SAFE
			case "eye-recommend":$Prefix_Facture = "8"; break;//Eye Recommend
			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		}
	
	if (($DataOrder["lab"] == 1) || ($DataOrder["lab"] == 3)){
		$Prefix_Facture = $Prefix_Facture . '0'.  $DataOrder["lab"]; 
		}else{
		//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
		$Prefix_Facture = $Prefix_Facture .  $DataOrder["lab"]; 
		}
		$OrderNum  =  $Prefix_Facture . '-'. $DataOrder["order_num"];
		
	
	$queryCreditCard = "SELECT * FROM payments WHERE order_num = " . $DataOrder["order_num"]. " AND cclast4 <> ''";
	//echo '<br><br>'. $queryCreditCard;
	
	$ResultCreditCard=mysqli_query($con,$queryCreditCard)		or die  ('I cannot select 1.6 items because: '.$queryCreditCard .' ' . mysqli_error($con));
	$nbrResult = mysqli_num_rows($ResultCreditCard);
	if ($nbrResult ==1)
	$PayeparCC = 'oui';
	else
	$PayeparCC = 'non';
	
	
	$acomba_acct_num = $DataAcomba2[acomba_account_num];
	//echo '<br>acomba num: '.  $acomba_acct_num;
	//echo '<br><br>Order Num :'. $DataOrder[order_num];
	$OrderTotal = $DataOrder[order_total] + $DataOrder[order_shipping_cost]  ;	
	if ($PayeparCC == 'oui'){	
		$outputstring.=$acomba_acct_num. ';' . $DataOrder["order_date_processed"].';'. $OrderNum . ';'. $OrderTotal. "\r\n";
	}else{
		$outputstring.=$acomba_acct_num. ';' . $DataOrder["order_date_shipped"].';'. $OrderNum . ';'. $OrderTotal. "\r\n";
	}
	return $outputstring;		
}





function export_paid_creditcard_orders_acomba($order_num){
	$queryAcomba = "SELECT distinct acomba_account_num FROM accounts WHERE user_id = (SELECT  user_id from orders WHERE order_num = $order_num  LIMIT 0,1)  LIMIT 0,1";
	echo '<br><br><br>' . $queryAcomba . '<br>';
	$ResultAcomba=mysql_query($queryAcomba)		or die  ('I cannot select 1.0 items because: ' . mysql_error());
	$DataAcomba=mysql_fetch_array($ResultAcomba);
	
	$queryOrder = "SELECT order_date_shipped,order_date_processed, order_num, order_total, order_from, order_shipping_cost, lab FROM orders  WHERE order_num = $order_num LIMIT 0,1";
	echo '<br>' . $queryOrder . '<br>';
	$ResultOrder=mysql_query($queryOrder)		or die  ('I cannot select 1.1 items because: ' . mysql_error());
	$DataOrder=mysql_fetch_array($ResultOrder);
	
	//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($DataOrder["order_from"]){
			case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
			case "safety":		$Prefix_Facture = "7"; break;//SAFE
			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		}
	
	if (($DataOrder["lab"] == 1) || ($DataOrder["lab"] == 3)){
		$Prefix_Facture = $Prefix_Facture . '0'.  $DataOrder["lab"]; 
		}else{
		//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
		$Prefix_Facture = $Prefix_Facture .  $DataOrder["lab"]; 
		}
		$OrderNum  =  $Prefix_Facture . '-'. $DataOrder["order_num"];
		
	$acomba_acct_num = $DataAcomba[acomba_account_num];
	$OrderTotal = $DataOrder[order_total] + $DataOrder[order_shipping_cost]  ;	
	
	echo '<br>acomba num: '.  $acomba_acct_num;
	echo '<br><br>Order Num :'. $DataOrder[order_num];
	echo '<br><br>Date paid :'. $DataOrder[order_date_processed];
	echo '<br>total: ' . $OrderTotal;
		
	$outputstring.=$acomba_acct_num. ';' . $DataOrder["order_date_processed"].';'. $OrderNum .';'. $OrderTotal. "\r\n";
	
	return $outputstring;		
}






function export_monthly_credits_acomba($memo_num){

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

	$queryAcomba = "SELECT distinct acomba_account_num FROM accounts WHERE user_id = (SELECT  mcred_acct_user_id from memo_credits WHERE mcred_memo_num = '$memo_num'  LIMIT 0,1)  LIMIT 0,1";
	$ResultAcomba=mysqli_query($con,$queryAcomba)		or die  ('I cannot select 1.2 items because: ' . $queryAcomba . mysqli_error($con));
	$DataAcomba=mysqli_fetch_array($ResultAcomba,MYSQLI_ASSOC);
	
	$queryOrderNum = "SELECT mcred_order_num, mcred_memo_num, mcred_date from memo_Credits WHERE mcred_memo_num = '$memo_num' ";
	$ResultOrderNum=mysqli_query($con,$queryOrderNum)		or die  ('I cannot select 1.3 items because: ' . mysqli_error($con));
	$DataOrderNum=mysqli_fetch_array($ResultOrderNum,MYSQLI_ASSOC);

	$queryOrder = "SELECT  order_date_processed, order_num, order_total, order_shipping_cost, order_from, lab FROM orders  WHERE order_num = $DataOrderNum[mcred_order_num] LIMIT 0,1";
	$ResultOrder=mysqli_query($con,$queryOrder)		or die  ('I cannot select 4 items because: ' . mysqli_error($con));
	$DataOrder=mysqli_fetch_array($ResultOrder,MYSQLI_ASSOC);
	
	//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($DataOrder["order_from"]){
			case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
			case "safety":		$Prefix_Facture = "7"; break;//SAFE
			case "eye-recommend":	$Prefix_Facture = "8"; break;//Prestige (Anciennement Eye-Recommend)
			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		}
	
	if (($DataOrder["lab"] == 1) || ($DataOrder["lab"] == 3)){
			$Prefix_Facture = $Prefix_Facture . '0'.  $DataOrder["lab"]; 
		}else{
			//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
			$Prefix_Facture = $Prefix_Facture .  $DataOrder["lab"]; 
		}	
	
	$acomba_acct_num = $DataAcomba[acomba_account_num];
	$OrderTotal = $DataOrder[order_total] + $DataOrder[order_shipping_cost]  ;	
	
	echo '<br>acomba num: '.  $acomba_acct_num;
	echo '<br>total: ' . $OrderTotal;
	$outputstring.=$acomba_acct_num. ';' . $DataOrderNum["mcred_date"].';'. $Prefix_Facture. '-'.  substr($DataOrderNum["mcred_memo_num"],1,8).';'. $OrderTotal. "\r\n";
	echo '<br><br>Memo num:' . substr($DataOrder["mcred_memo_num"],1,8);
	
	return $outputstring;		
}








function export_order_DLAB_VOT($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		

		$shipping_code =$orderItem["shipping_code"];

		switch($orderItem["order_product_coating"]){
			case 'Dream AR':		$orderItem["order_product_coating"] = "HC";		break;
			case 'DH2':				$orderItem["order_product_coating"] = "HC";		break;
			case 'Smart AR':		$orderItem["order_product_coating"] = "CR+G";	break;
			case 'Hard Coat':		$orderItem["order_product_coating"] = "HC";		break;
			case 'ITO AR':			$orderItem["order_product_coating"] = "CR+ETC";	break;
			case 'MultiClear AR':	$orderItem["order_product_coating"] = "GL+G";	break;
			case 'Uncoated':		$orderItem["order_product_coating"] = " ";		break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
	
	
	if ($orderItem["lab"]==50){
		$labname='Directlab Eagle';
	}else{
		$labname='DirectLab Network Inc.';	
	}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labname.'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		
		if ($orderItem["myupload"] =="") {
		$myupload = "none";
		}else{
		$myupload = $orderItem["myupload"] ;
		}
		//$orderItem["order_patient_first"]="xxx";//XXX out certain field
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'","'.$myupload .'"'. "\r\n";
			}
			
	return $outputstring;
			
}








//New swiss function
function export_order_swiss($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		//----------ici------
		if ($orderItem[order_from]!="ifcclubca"){
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		$ProdResult=mysql_query($ProdQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
				
		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "Select myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysql_query($VerifIfShape)or die  ('I cannot select items because: ' . mysql_error());
		$DataVerif=mysql_fetch_array($VerifResult);
		$TheShape = $DataVerif['myupload'];
		
		if ($TheShape <> "") {
		$TheShape ="Yes";
		echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		echo  '<br> une shape attaché: Non';
		}
				
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				
				case 'Nylon Groove':
				$frame_type="Nylon Groove";
				break;
				
				case 'Metal':
				$frame_type="Metal";
				break;
			
				case 'Drill & Notch':
				$frame_type="Drill & Notch";
				break;
				
				case 'Plastic':
				$frame_type="Plastic";
				break;
				
				case 'Metal Groove':
				$frame_type="Metal Groove";
				break;
				
				case 'Drill and Notch':
				$frame_type="Drill and Notch";
				break;

				case 'Edge Polish':
				$frame_type="Edge Polish";
				break;
					
				case 'Métal            ':
				$frame_type="Metal";
				break;
				
				case 'Fil Nylon        ':
				$frame_type="Nylon Groove";
				break;
				
				case 'Percé       ':
				$frame_type="Drill and Notch";
				break;
				
				case 'Fil Métal           ':
				$frame_type="Metal Groove";
				break;
				
				case 'Plastique':
				$frame_type="Plastic";
				break;
				
								
				default: 
				$frame_type= $EdgeItem[frame_type];
				break;
				}
				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'.$accNum. '","'.$TheShape.'"'.chr(13);
						
			}
	
	return $outputstring;
						
}




































//New crystal function
function export_order_crystal($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		//----------ici------
		if ($orderItem[order_from]=="ifcclubca"){
		$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="safety"){
		$ProdQuery="select product_code,color_code from safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		$ProdResult=mysql_query($ProdQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$inset        = $ProdItem[inset];
			$color_code   = $ProdItem[color_code];
			$product_code = $ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
				
		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "Select myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysql_query($VerifIfShape)or die  ('I cannot select items because: ' . mysql_error());
		$DataVerif=mysql_fetch_array($VerifResult);
		$TheShape = $DataVerif['myupload'];
		
		if ($TheShape <> "") {
		$TheShape ="Yes";
		//echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		//echo  '<br> une shape attaché: Non';
		}
				
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category in ('Edging_Frame','Edging')"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				
				case 'Nylon Groove':
				$frame_type="Nylon Groove";
				break;
				
				case 'Metal':
				$frame_type="Metal";
				break;
			
				case 'Drill & Notch':
				$frame_type="Drill & Notch";
				break;
				
				case 'Plastic':
				$frame_type="Plastic";
				break;
				
				case 'Metal Groove':
				$frame_type="Metal Groove";
				break;
				
				case 'Drill and Notch':
				$frame_type="Drill and Notch";
				break;

				case 'Edge Polish':
				$frame_type="Edge Polish";
				break;
					
				case 'Métal            ':
				$frame_type="Metal";
				break;
				
				case 'Fil Nylon        ':
				$frame_type="Nylon Groove";
				break;
				
				case 'Percé       ':
				$frame_type="Drill and Notch";
				break;
				
				case 'Fil Métal           ':
				$frame_type="Metal Groove";
				break;
				
				case 'Plastique':
				$frame_type="Plastic";
				break;
				
								
				default: 
				$frame_type= $EdgeItem[frame_type];
				break;
				}
				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","B'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
		//SI le frame a et Frame ED sont égaux, on ajoute 1mm au frame ED
		if (($orderItem["frame_a"] ==  $orderItem["frame_ed"]) &&  ($orderItem["frame_a"] > 0) &&  ($orderItem["frame_a"] <> ''))
		{
		$orderItem["frame_ed"] = $orderItem["frame_ed"]+1;
		}
		
		
		
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","' .$inset.'","' .$accNum. '","'.$TheShape.'"'.chr(13);
						
			}
	
	return $outputstring;
						
}









//New GKB function
function export_order_gkb_2015($order_num){

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	echo '<br><br><br>Query: '. $Query; 
	$Result=mysqli_query($con,$Query)		or die  ('I cannot select items because: ' . mysqli_connect_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		
	$UV400 = $orderItem[UV400];	
	if ($UV400<>''){	
		$UV400='400';	
	}
	
		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];
		
		$labQuery  = "select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult = mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem   = mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		
		//PARTIE OPTICAL CENTER	
	if ($orderItem["optical_center"] <> ""){
		echo '<br><b>Partie Optical Center</b>';
		//Mettre les valeurs dans les bons champs
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	

	}//End if there is an optical center
		
		/*if ($orderItem["order_product_polar"]<>'None'){
			//Le produit demandé est bien un polarisé, on doit vérifier s'il fait partie de la promo
			$PositionPromo = strpos($orderItem["order_product_name"],'Promotion');
			
			if ($PositionPromo === false) {
			//ON N'A PAS TROUVÉ 'PROMOTION' Dans le nom du produit	
			}else{
				// ON A TROUVÉ LA CHAINE PROMOTION DANS LE NOM DU PRODUIT
				//ON DOIT DONC AJOUTER LE SLP DANS L'INSTRUCTION SPÉCIALE
				$special_instructions = $special_instructions . ' SLP ';	
			}
			
		}//END IF*/
		
		$extra_product=addslashes($orderItem["extra_product"]);
		
		if ($orderItem[order_from]=="ifcclubca"){
		$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif ($orderItem[order_from]=="safety"){
		$ProdQuery="select product_code,color_code from safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		echo '<br>ProdQuery: '. $ProdQuery;
		
		$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
			
		$color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
		$shipping_code=$ShipItem[shipping_code];
			
			
		$EngrQuery  = "select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult = mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem   = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];
			}else{
				$engraving="";}
			
		$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
				//Attribuer les bon codes de teintes avec GKB
				if ($tint_color=='Shooter Yellow'){//Fonctionne
					$tint_color = 'YELLSH';
				}
				if ($tint_color=='Shooter Orange'){//Fonctionne
					$tint_color = 'ORNASH';
				}
				if (($tint_color=='G-15') && ($from_perc==45)&& ($to_perc==45)){//Fonctionne
					$tint_color = 'G15C';
				}
				if (($tint_color=='G-15') && ($from_perc==65)&& ($to_perc==65)){//Fonctionne
					$tint_color = 'G15D';
				}
				if (($tint_color=='G-15') && ($from_perc==85)&& ($to_perc==85)){//Fonctionne
					$tint_color = 'G15E';
				}
				if (($tint_color=='Brown') && ($from_perc==45)&& ($to_perc==45)){//Fonctionne
					$tint_color = 'BROWNC';
				}
				if (($tint_color=='Brown') && ($from_perc==65)&& ($to_perc==65)){//Fonctionne
					$tint_color = 'BROWND';
				}
				if (($tint_color=='Brown') && ($from_perc==85)&& ($to_perc==85)){//Fonctionne
					$tint_color = 'BROWNE';
				}
				
			
				if (($tint_color=='Black Grey') && ($from_perc==45)&& ($to_perc==45)){//fonctionne
					$tint_color = 'GKB27';
				}
				if (($tint_color=='Black Grey') && ($from_perc==65)&& ($to_perc==65)){//fonctionne
					$tint_color = 'BLAGD';
				}
				if (($tint_color=='Black Grey') && ($from_perc==85)&& ($to_perc==85)){//fonctionne
					$tint_color = 'BLAGE';
				}
				if (($tint_color=='Black Grey') && ($from_perc==0)&& ($to_perc==85)){//fonctionne
					$tint_color = 'GKB25';
				}
				if (($tint_color=='Brown') && ($from_perc==0)&& ($to_perc==85)){//A TESTER
					$tint_color = 'GRBN85';
				}
				if ($tint_color=='Sky Blue'){
					$tint_color = 'SKYBLU';
					$tint="Solid";
				}

	
		
	
						switch($orderItem["order_product_coating"]){
							case 'Hard Coat': 	 		  $Coating = 'HC';        break;// Ok Validé avec Danielle
							case 'Smart AR':  	 		  $Coating = 'HMC Super'; break;
							case 'Dream AR':     		  $Coating = 'AQUA'; 	  break;// Ok Validé avec Danielle
							case 'ITO AR':   	 		  $Coating = 'AQUA';      break;// Ok Validé avec Danielle
							case 'AR':   	 	 		  $Coating = 'AQUA';      break;
							case 'HD AR':        		  $Coating = 'LR';        break;// Ok Validé avec Danielle
							case 'Xlr':          		  $Coating = 'LR';        break; //LNC
							case 'AR Backside':  		  $Coating = 'HMCB';  	  break;// Ok Validé avec Danielle 2015-09-16
							case 'SPF': 		 		  $Coating = 'UV';   	  break;// Ok Validé avec Kamal 2015-12-10
							case 'Low Reflexion':		  $Coating = 'LR';    	  break;//ajouté 7 février 2017
							case 'Low Reflexion Backside':$Coating = 'BLR';    	  break;//ajouté 30 Novembre 2021
							case 'iBlu':				  $Coating = 'BLUEX';     break;//ATTENTE DU CODE IBLU DE GKB
							default:             		  $Coating = $orderItem["order_product_coating"]; 
						}			
						
					
				
		$queryMirror  = "select category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;
				
					if (($tint_color=='Green') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'GEHC';//HC
						$Coating    = 'GEHC';
					}elseif (($tint_color=='Green') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'GEAP';//AR Backside
						$Coating    = 'GEAP';//AR Backside
					}elseif (($tint_color=='Gold') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'GHC';//HC
						$Coating    = 'GHC';//HC
					}elseif (($tint_color=='Gold') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'GAP';//AR Backside
						$Coating    = 'GAP';//AR Backside
					}elseif (($tint_color=='Ocean Blue') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'OBHC';//HC
						$Coating    = 'OBHC';//HC
					}elseif (($tint_color=='Ocean Blue') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'OBAP';//AR Backside
						$Coating    = 'OBAP';//AR Backside
					}elseif (($tint_color=='Red') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'RHC';//HC
						$Coating    = 'RHC';//HC
					}elseif (($tint_color=='Red') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'RAP';//AR Backside
						$Coating    = 'RAP';//AR Backside
					}elseif (($tint_color=='Silver') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'SIHC';//HC
						$Coating    = 'SIHC';//HC
					}elseif (($tint_color=='Silver') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'SIAP';//AR Backside
						$Coating    = 'SIAP';//AR Backside
					}elseif (($tint_color=='Yellow') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'YHC';//HC
						$Coating    = 'YHC';//HC
					}elseif (($tint_color=='Yellow') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'YAP';//AR Backside
						$Coating    = 'YAP';//AR Backside
					}
				
				}	//End IF


		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "SELECT myupload FROM orders  WHERE order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
		

		if ($TheShape <> "") {
		$TheShape ="Yes";
		echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		echo  '<br> une shape attaché: Non';
		}
		
		//Demande de Swiss 2015-06-16: Si produit Stock: The shape='no'
		if (strpos($orderItem["order_product_name"],'Stock') == true){
			$TheShape ="No";
		}		
				

				
		$EdgeQuery  = "select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category IN('Edging','Edging_Frame')"; //Get EDGING
		$EdgeResult = mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem   = mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				case 'Nylon Groove':		$frame_type="Nylon Groove";        break;
				case 'Metal':				$frame_type="Metal";		       break;
				case 'Drill & Notch':		$frame_type="Drill & Notch";       break;
				case 'Plastic':				$frame_type="Plastic";		       break;
				case 'Metal Groove':		$frame_type="Metal Groove";	  	   break;
				case 'Drill and Notch':		$frame_type="Drill and Notch";	   break;
				case 'Edge Polish':			$frame_type="Edge Polish";	       break;
				case 'Métal            ':	$frame_type="Metal";               break;
				case 'Fil Nylon        ':	$frame_type="Nylon Groove";	       break;
				case 'Percé       ':		$frame_type="Drill and Notch";     break;
				case 'Fil Métal           ':$frame_type="Metal Groove";        break;
				case 'Plastique':			$frame_type="Plastic";		  	   break;				
				default:					$frame_type= $EdgeItem[frame_type];break;
				}
				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				
				//Dans le cas d'un remote edging on ne transmet pas les mesures de la monture
				case 'remote edging':
				$job_type="remote edging";
				//$orderItem["frame_a"]   = '';
				//$orderItem["frame_b"]   = '';
				//$orderItem["frame_ed"]  = '';
				//$orderItem["frame_dbl"] = '';
				break;
				
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
				
	$accNum = " 000073";
	
	if ($shipping_code == 'OR005EDL'){
		$accNum = "000091";
	}
	
	
	echo '<br><br>Lab:'.$orderItem[lab].'<br>' ;
	
	/*if ($orderItem[lab]==59){//Lab = Safety, on demande la commande 'Remote Edging' car STC a de la difficulté à tailler le 1.59.
		$job_type="remote edging";
	}*/
	//Nouvelle demande Kelly 2022-06-20 Ticket #6045
	if ($orderItem[lab]==59){//Lab = Safety, on demande la commande 'Remote Edging' car STC a de la difficulté à tailler le 1.59.
		$job_type="Edge and Mount";
	}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","DirectLab Network","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$Coating.'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'
		//.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$accNum. '","'.$TheShape.'"'.chr(13);
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'.chr(13);
					
			}
	
	return $outputstring;
						
}





//New swiss function
function export_order_swiss_2014($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
$Query  = "select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result = mysqli_query($con,$Query)		or die  ('I cannot select items because: ' . mysqli_error($con));

	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){

		
	$special_instructions=addslashes($orderItem["special_instructions"]);
	
	//PARTIE OPTICAL CENTER	
	if ($orderItem["optical_center"] <> ""){
		echo '<br><b>Partie Optical Center</b>';
		echo '<br><br>Special inst. avant: '. $special_instructions;
		//Mettre les valeurs dans les bons champs
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		
		//Trouver la longeur exacte du champ afin de  l'effacer de l'instruction spéciale
		$Position_OPTICAL = strpos(strtolower($special_instructions),'optical');
		
		$Position_MM = strpos(strtolower($special_instructions),'mm');
		echo '<br>Position Optical:'. $Position_OPTICAL;
		echo '<br>Position MM:'. $Position_MM;
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		echo '<br>NombreCaractereChaineOpticalCenter :'. $NombreCaractereChaineOpticalCenter;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		echo '<br>ChaineOpticalCenter :'. $ChaineOpticalCenter;
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions);
		echo '<br><br>Special inst. APRES: '. $special_instructions;
	}//End if there is an optical center
	
	//PARTIE UV400: Si un UV400 est demandé dans la commande, on l'ajoute dans l'instruction spéciale
	if ($orderItem["UV400"] <> ""){
		$special_instructions = $special_instructions . ' UV400';		
	}//END IF
		
		
	//PARTIE CORRIDOR
	if ($special_instructions <> ""){//Si il y a une instruction spéciale, on doit vérifier si un corridor a été demandé dans celle-ci.
		echo '<br><b>Partie Corridor</b>';
		echo '<br>Special inst. avant: '. $special_instructions;
		$Position_Corridor = strpos(strtolower($special_instructions),'corridor:');
		$Position_MiliMetre = strpos(strtolower($special_instructions),'mm');
		echo '<br>Position Corridor:' . $Position_Corridor;
		echo '<br>Position MM:' . $Position_MiliMetre;
		echo '<br>Nbr Char:'.$NombreCaractereChaineCorridor;
		
		$NombreCaractereChaineCorridor = $Position_MiliMetre - $Position_Corridor +2;
		//Derniere étape: TODO: supprimer l'info Corridor du champ special_instructions
		if ($NombreCaractereChaineCorridor>4){
			$ChaineCorridor = substr($special_instructions,$Position_Corridor, $NombreCaractereChaineCorridor);
			$CORRIDOR = $ChaineCorridor;
			$special_instructions = str_replace($ChaineCorridor,'',$special_instructions);
		}
		echo '<br><br>Special inst. APRES: '. $special_instructions;
		echo '<br>Corridor:'.$CORRIDOR. '<br><br><br><br><br>';

		
	}//End if there is an optical center
	
		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);	
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		
		$extra_product=addslashes($orderItem["extra_product"]);
		


		if ($orderItem[order_from]=="ifcclubca"){
			$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="lensnetclub"){
			$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="safety"){
			$ProdQuery="select product_code,color_code from safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
			$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		
		$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
		$color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
			
		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
		$shipping_code=$ShipItem[shipping_code];
			
			
		$EngrQuery="SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem=mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EngrResult);
		if ($usercount!=0){
			$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		$TintQuery  = "SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult = mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem   = mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($TintResult);
		
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}

				
		$queryMirror  = "select category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		//echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				//echo '<br>Tint'. $tint	;
				//echo '<br>tint_color'. $tint_color	;
				}	
			
			
			//echo '<br>switch tint';	
			switch($tint_color){//For Swiss tints, we remove the customer percentages
				case 'SW010': 	$from_perc = ""; $to_perc   = ""; $tint="Solid"; 	break;
				case 'SW027/50':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;
				case 'SW030/50':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;
				case 'SW051':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW035':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'GOL':	    $from_perc = ""; $to_perc   = ""; $tint="Gradient"; break;	
				case 'SW015':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'RAV':	    $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW034':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW012':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW023':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW046':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW025':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW004':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW036':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		                 
				case 'SW054':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	     
				case 'SW062':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW032':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW026':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		            	  
				case 'TEN':  	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	 
				case 'AZU':     $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	 
				case 'SW007':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW001':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW001/25':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	             	  
            }//End Switch
			
		
		$IIMPACT = '';//Initialiser l'extra iimpact comme s'il n'avait en a pas. 
		//IIMPACT: si la commande contient un extra IIMPACT, on l'ajoute dans le csv pour Swiss.
		$queryIIMPACT  = "SELECT * FROM extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Iimpact'"; //Get IIMPACT
		$ResultIIMPACT = mysqli_query($con,$queryIIMPACT)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataIIMPACT   = mysqli_fetch_array($ResultIIMPACT,MYSQLI_ASSOC);
		$usercount     = mysqli_num_rows($ResultIIMPACT);
		if ($usercount!=0){//Un extra IImpact est présent
			$IIMPACT = 'iimpact';
		}				
				
		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "SELECT shape_name_bk FROM orders  WHERE order_num ='$orderItem[order_num]'";
		$VerifResult  = mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif    = mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape     = $DataVerif['shape_name_bk'];
		

		if ($TheShape <> "") {
		$TheShape ="Yes";
		//echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		//echo  '<br> une shape attaché: Non';
		}
		
		/*//Demande de Swiss 2015-06-16: Si produit Stock: The shape='no'
		if (strpos($orderItem["order_product_name"],'Stock') == true){
			$TheShape ="No";
		}*/		
				
		
		$queryEdgingBarcode  = "SELECT swiss_edging_barcode FROM swiss_edging_barcodes WHERE order_num = '$orderItem[order_num]'";
		$resultEdgingBarcode = mysqli_query($con,$queryEdgingBarcode)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataEdgingBarcode   = mysqli_fetch_array($resultEdgingBarcode,MYSQLI_ASSOC);
		//$Swiss_Edging_Barcode = "DL" .$DataEdgingBarcode[swiss_edging_barcode];
		$Swiss_Edging_Barcode = $DataEdgingBarcode[swiss_edging_barcode];
		
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category IN('Edging','Edging_Frame')"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				case 'Nylon Groove':		$frame_type="Nylon Groove";        break;
				case 'Metal':				$frame_type="Metal";		       break;
				case 'Drill & Notch':		$frame_type="Drill & Notch";       break;
				case 'Plastic':				$frame_type="Plastic";		       break;
				case 'Metal Groove':		$frame_type="Metal Groove";	  	   break;
				case 'Drill and Notch':		$frame_type="Drill and Notch";	   break;
				case 'Edge Polish':			$frame_type="Edge Polish";	       break;
				case 'Métal            ':	$frame_type="Metal";               break;
				case 'Fil Nylon        ':	$frame_type="Nylon Groove";	       break;
				case 'Percé       ':		$frame_type="Drill and Notch";     break;
				case 'Fil Métal           ':$frame_type="Metal Groove";        break;
				case 'Plastique':			$frame_type="Plastic";		  	   break;				
				default:					$frame_type= $EdgeItem[frame_type];break;
				}
				

				switch ($EdgeItem[job_type]) {
					case 'Taillé-monté                  ':$job_type="Edge and Mount";	break;
					case 'Non-taillé      ':$job_type="Uncut";							break;
					case 'Edge and Mount':$job_type="Edge and Mount";					break;
					case 'Uncut':$job_type="Uncut";										break;
					case 'remote edging':$job_type="remote edging";						break;
					default:  $job_type= $EdgeItem[job_type];							break;
				}

							
				
				if ($job_type=='Edge and Mount'){
			    //Évaluer si la job vient de entrepotqc (66) ou warehouseca (67)
					if (($orderItem[lab] == 66) || ($orderItem[lab] == 67)){
						//Modifier le job type en 'Frame to follow'
						$job_type = 'Frame to follow';
						//$TheShape ="Yes";
					}else{
						switch($orderItem[user_id]){
							case 'HBEC':            $job_type = 'Frame to follow';   break;	
							case 'HBECDL':    		$job_type = 'Frame to follow';   break;	
							case 'Lenzandtrenz':    $job_type = 'Frame to follow';   break;	
							case 'LenzandtrenzLNC': $job_type = 'Frame to follow';   break;	
						}//End Switch
						
					}//End IF

				}//End IF
				
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
		
		

			
			switch($frame_model){// Si la monture est une FUGLIES, on doit mettre le job type a edge and mount et ne pas afficher le swiss edging barcode, puisque swiss va fournir la monture.
				case 'RX01_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX02_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX03_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX04_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX05_TORTOISE':  $job_type="Edge and Mount";  $Swiss_Edging_Barcode = "";  break;
				case 'RX06_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX07_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX08_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX09_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX10_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX11_BROWN':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX12_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX13_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX14_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;	
				case 'RX15_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX16_WHITE':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;	
			}//End Switch	
	
		
		
		
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		
		
		//Évaluer si reprise de Québec pour Swiss, si oui aux 2, on demande UNCUT
		if ($orderItem["user_id"]=='entrepotquebec'){
			
			$queryUncut = "SELECT redo_order_num FROM orders where user_id='".$orderItem["user_id"]."' AND order_num=".$orderItem["order_num"];
			echo '<br>'.$queryUncut;
			$resultUncut= mysqli_query($con,$queryUncut)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataUncut  = mysqli_fetch_array($resultUncut,MYSQLI_ASSOC);
			$RedoOrderNum = $DataUncut[redo_order_num];
			$longeurRedo = strlen($RedoOrderNum);	
			
			if ($longeurRedo==7){//Signifie que c'est une reprise de Québec, Pour Swiss donc on demande la job UNCUT
				echo '<br>Redo order num longeur 7 !!';
				$job_type="Uncut";	
			}else{
				
				echo '<br>Longeur redo order num:'. $longeurRedo;
			}
		}//End IF
		
		 
		 $outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'	
		
		 //.  $orderItem["base_curve"].'","'  .  $Swiss_Edging_Barcode .'","'  . $orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$safety.'","'.$accNum. '","'.$TheShape.'"'.chr(13);
		.  $orderItem["base_curve"].'","'  .  $Swiss_Edging_Barcode .'","'  . $orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$safety.'","'
		
		//.$accNum. '","'.$TheShape.'"'.chr(13);
		//.$accNum.'","'.$TheShape. '","'.$IIMPACT.'"'.chr(13);
		.$accNum.'","'.$TheShape. '","'.$IIMPACT.'","'.$CORRIDOR.'"'.chr(13);
					
			}
	
	return $outputstring;
						
}




















function export_order_PlasticPlus($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

	//Special Instruction
	$special_instructions=addslashes($orderItem["special_instructions"]);
	$special_instructions=strtoupper($special_instructions);
	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
	$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
	$Position_MM = strpos($special_instructions,'MM');
	$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
	$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
	$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center
	
	$special_instructions = ' '. $special_instructions;
	//Base Curve
	//Recherche de 'BASE CURVE' parmis l'instruction speciale
	$PositionBaseCurve = strpos($special_instructions,'BASE CURVE');
	
	
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		echo '<br>Special instruction:<br>'. $special_instructions;
		echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve
	
	

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		//TODO: doit on avoir deux codes différents, un pour edll et un pour le reste ? 
		$shipping_code = "NA108";
			
			
		//TODO intégrer les coatings de Plastic Plus
		switch($orderItem["order_product_coating"]){
			case 'Dream AR':	 $orderItem["order_product_coating"] = "CR+ETC";    break;
			case 'Xlr':     	 $orderItem["order_product_coating"] = "CR+ETC";    break;			
			case 'HD AR':   	 $orderItem["order_product_coating"] = "HDC";       break;			
			case 'DH2':     	 $orderItem["order_product_coating"] = "HC";        break;			
			case 'DH1':     	 $orderItem["order_product_coating"] = "HC";        break;			
			case 'Smart AR':	 $orderItem["order_product_coating"] = "CR+G";      break;			
			case 'Hard Coat':    $orderItem["order_product_coating"] = "HC";        break;				
			case 'ITO AR':       $orderItem["order_product_coating"] = "CR+ETC";    break;			
			case 'AR':           $orderItem["order_product_coating"] = "CR+ETC";    break;	
			case 'MultiClear AR':$orderItem["order_product_coating"] = "GL+G";      break;
			case 'Uncoated':     $orderItem["order_product_coating"] = " ";		    break;
			
			//LEURS CODES, atten d'avoir plus de details pour faire les correspondances
			case 'PPS':     $orderItem["order_product_coating"] =   "PPS";	break;
			case 'ELITE':   $orderItem["order_product_coating"] = "ELITE";	break;
			case 'PBV':     $orderItem["order_product_coating"] =   "PBV";	break;
			case 'PPH':     $orderItem["order_product_coating"] =   "PPH";	break;
		}
		

		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$PlabItem=mysql_fetch_array($PlabResult);
			
		
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		if (($orderItem[order_from]!="ifcclubca") &&  ($orderItem[order_from]!="safety")){
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="safety"){
		$ProdQuery="select product_code,color_code from safety_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}elseif($orderItem[order_from]=="ifcclubca"){
		$ProdQuery="select product_code,color_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
		
		$ProdResult=mysql_query($ProdQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$nbrResult = mysql_num_rows($ProdResult);
		
			
		if ($nbrResult < 1)
		{
			$ProdResult=mysql_query($ProdQuery)		or die  ('I cannot select items because: ' . mysql_error());
		}
		
		
		
		$ProdItem     = mysql_fetch_array($ProdResult);
	    $color_code   = $ProdItem[color_code];
		$product_code = $ProdItem[product_code];
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
		$ShipItem = mysql_fetch_array($ShipResult);
			
			
		$VerifIfShape = "Select myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysql_query($VerifIfShape)or die  ('I cannot select items because: ' . mysql_error());
		$DataVerif=mysql_fetch_array($VerifResult);
		$TheShape = $DataVerif['myupload'];
				
		if ($TheShape <> "") {
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
			
			
		$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)	or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
	
		
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$EdgeItem=mysql_fetch_array($EdgeResult);
		$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$supplier=$EdgeItem[supplier];
			}
			

	//TODO Plastic Plus feront-ils autre chose que du UNCUT ? 
		$job_type    = 'Uncut';	
	

	
	echo '<br><br>Order num    ' . $orderItem["order_num"] . '&nbsp;&nbsp;Eye:'.  $orderItem["eye"];
	$THE_EYE = $orderItem["eye"];
	
	
	switch($orderItem["order_product_index"]){
		case '1.50': $orderItem["order_product_index"] =  '0'; break;
		case '1.53': $orderItem["order_product_index"] = '24'; break;
		case '1.59': $orderItem["order_product_index"] =  '1'; break;	
		case '1.60': $orderItem["order_product_index"] =  '2'; break;
		case '1.67': $orderItem["order_product_index"] = '10'; break;
		case '1.74': $orderItem["order_product_index"] = '23'; break;
	}
	
	switch(strtolower($orderItem["order_product_photo"])){
		case 'grey':  		      $orderItem["order_product_photo"] =  '719'; break;
		case 'brown': 		      $orderItem["order_product_photo"] =  '717'; break;
		case 'green': 		      $orderItem["order_product_photo"] =  '754'; break;
		case 'extra active grey': $orderItem["order_product_photo"] =   '96'; break;
	}
	
	switch(strtolower($orderItem["order_product_polar"])){
		case 'grey':  		      $orderItem["order_product_polar"] =   '83'; break;
		case 'brown': 		      $orderItem["order_product_polar"] =   '84'; break;
		case 'green': 		      $orderItem["order_product_polar"] =  '108'; break;
	}
	
	
	if($THE_EYE == "R.E."){
			$orderItem["le_pd"]      = "0";
			$orderItem["le_sphere"]  = "0";
			$orderItem["le_cyl"]     = "0";
			$orderItem["le_pr_ax2"]  = "0";
			$orderItem["le_pr_ax"]   = "0";
			$orderItem["le_axis"]    = "0";
			$orderItem["le_add"]     = "0";
			$orderItem["le_height"]  = "0";
			$orderItem["le_pd"]		 = "0";
			$orderItem["le_pd_near"] = "0";
		}
		
		if( $THE_EYE == "L.E."){
			$orderItem["re_pd"]      = "0";
			$orderItem["re_sphere"]  = "0";
			$orderItem["re_cyl"]     = "0";
			$orderItem["re_pr_ax2"]  = "0";
			$orderItem["re_pr_ax"]   = "0";
			$orderItem["re_axis"]    = "0";
			$orderItem["re_ad"]      = "0";
			$orderItem["re_height"]  = "0";
			$orderItem["re_pd"]		 = "0";
			$orderItem["re_pd_near"] = "0";
		}
		
		$PositionEdgePolish = strpos($special_instructions,'EDGE POLISH');
		if ($PositionEdgePolish !== false) {
			$EDGE_POLISH   = 'Yes';
		}else{
			$EDGE_POLISH   = 'No';
		}

				
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["eye"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]   = "xxx";
		$orderItem["order_product_discount"]= "xxx";
		$orderItem["coupon_dsc"]            = "xxx";
		$orderItem["order_total"]           = "xxx";
		
	    $outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$product_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$special_instructions.'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$frame_model.'","'.$color.'","'.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"].'","'	.$EDGE_POLISH . '","' 
		.$TheShape.'"'.chr(13);//$accNum.'"'.chr(13);

			}
					
	return $outputstring;
			
}



//New swiss function
function export_order_swiss_HBC($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
  
$Query  = "SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
$Result = mysqli_query($con,$Query)		or die  ('I cannot select items because 2: ' . mysqli_error($con));

	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){

	
	if ($orderItem["optical_center"] <> ""){
		$orderItem["le_height"] = $orderItem["optical_center"];
		$orderItem["re_height"] = $orderItem["optical_center"];	
		$Position_OPTICAL = strpos($special_instructions,'OPTICAL');
		$Position_MM = strpos($special_instructions,'MM');
		$NombreCaractereChaineOpticalCenter = $Position_MM - $Position_OPTICAL +2;
		$ChaineOpticalCenter = substr($special_instructions,$Position_OPTICAL, $NombreCaractereChaineOpticalCenter);
		$special_instructions = str_replace($ChaineOpticalCenter,'',$special_instructions );
	}//End if there is an optical center

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}
		
		$accQuery   = "SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult	= mysqli_query($con,$accQuery)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
		$accItem	= mysqli_fetch_array($accResult,MYSQLI_ASSOC);	
		$accNum		= $accItem[account_num];
		
		$labQuery	= "SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult	= mysqli_query($con,$labQuery)		or die  ('I cannot select items because 4: ' . mysqli_error($con));
		$labItem	= mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery	= "SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult	= mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
		$PlabItem	= mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$UV400 = $orderItem["UV400"];
		if ($UV400 <> ""){
			$special_instructions = $special_instructions . ' UV400';		
		}//END IF
		//Partie UV400
		

		$extra_product=addslashes($orderItem["extra_product"]);

		$ProdQuery="select product_code from ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because 6: ' . mysqli_error($con));
		
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
		$color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
			
		$ShipQuery="SELECT shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
		$shipping_code=$ShipItem[shipping_code];
			
			
		$EngrQuery="SELECT engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem=mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EngrResult);
		if ($usercount!=0){
			$engraving=$EngrItem[engraving];
		}else{
			$engraving="";
		}
			
		$TintQuery  = "SELECT tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult = mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem   = mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($TintResult);
		
		if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}

				
		$queryMirror  = "select category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
					$tint		= $DataMirror[category];
					$tint_color = $DataMirror[tint_color];
					$from_perc  = $DataMirror[from_perc];
					$to_perc    = $DataMirror[to_perc];
				}	
			
			
			switch($tint_color){//For Swiss tints, we remove the customer percentages
				case 'SW010': 	$from_perc = ""; $to_perc   = ""; $tint="Solid"; 	break;
				case 'SW027/50':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;
				case 'SW030/50':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;
				case 'SW051':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW035':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'GOL':	    $from_perc = ""; $to_perc   = ""; $tint="Gradient"; break;	
				case 'SW015':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'RAV':	    $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW034':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW012':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW023':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		
				case 'SW046':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW025':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW004':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW036':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		                 
				case 'SW054':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	     
				case 'SW062':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW032':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW026':	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;		            	  
				case 'TEN':  	$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	 
				case 'AZU':     $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	 
				case 'SW007':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW001':   $from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	
				case 'SW001/25':$from_perc = ""; $to_perc   = ""; $tint="Solid";	break;	             	  
            }//End Switch
			
		
		$IIMPACT = '';//Initialiser l'extra iimpact comme s'il n'avait en a pas. 
		//IIMPACT: si la commande contient un extra IIMPACT, on l'ajoute dans le csv pour Swiss.
		$queryIIMPACT  = "SELECT * FROM extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Iimpact'"; //Get IIMPACT
		$ResultIIMPACT = mysqli_query($con,$queryIIMPACT)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataIIMPACT   = mysqli_fetch_array($ResultIIMPACT,MYSQLI_ASSOC);
		$usercount     = mysqli_num_rows($ResultIIMPACT);
		if ($usercount!=0){//Un extra IImpact est présent
			$IIMPACT = 'iimpact';
		}				
				
		$VerifIfShape = "SELECT shape_name_bk FROM orders  WHERE order_num ='$orderItem[order_num]'";
		$VerifResult  = mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif    = mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape     = $DataVerif['shape_name_bk'];
		
		if ($TheShape <> ""){
			$TheShape ="Yes";
		}else{
			$TheShape ="No";
		}
		
		$queryEdgingBarcode  = "SELECT swiss_edging_barcode FROM swiss_edging_barcodes WHERE order_num = '$orderItem[order_num]'";
		$resultEdgingBarcode = mysqli_query($con,$queryEdgingBarcode)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataEdgingBarcode   = mysqli_fetch_array($resultEdgingBarcode,MYSQLI_ASSOC);
		//$Swiss_Edging_Barcode = "DL" .$DataEdgingBarcode[swiss_edging_barcode];
		$Swiss_Edging_Barcode = $DataEdgingBarcode[swiss_edging_barcode];
		
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category IN('Edging','Edging_Frame')"; //Get EDGING
		$EdgeResult=mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem=mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				case 'Nylon Groove':		$frame_type="Nylon Groove";        break;
				case 'Metal':				$frame_type="Metal";		       break;
				case 'Drill & Notch':		$frame_type="Drill & Notch";       break;
				case 'Plastic':				$frame_type="Plastic";		       break;
				case 'Metal Groove':		$frame_type="Metal Groove";	  	   break;
				case 'Drill and Notch':		$frame_type="Drill and Notch";	   break;
				case 'Edge Polish':			$frame_type="Edge Polish";	       break;
				case 'Métal            ':	$frame_type="Metal";               break;
				case 'Fil Nylon        ':	$frame_type="Nylon Groove";	       break;
				case 'Percé       ':		$frame_type="Drill and Notch";     break;
				case 'Fil Métal           ':$frame_type="Metal Groove";        break;
				case 'Plastique':			$frame_type="Plastic";		  	   break;				
				default:					$frame_type= $EdgeItem[frame_type];break;
				}
				
				//100% des commandes Swiss seront taillé-monté par Swiss, sauf exception, donc on met ce switch
				//$job_type="Edge and Mount";
				
				//Email avec Swiss Penny 29 octobre 2018
				switch($EdgeItem[job_type]){
					case 'Edge and Mount':	$job_type="Frame to follow";  	break;
					case 'remote edging':	$job_type="Remote Edging";		break;
					case 'Remote Edging':	$job_type="Remote Edging";  	break;
					case 'Uncut':			$job_type="Uncut";  			break;
				}//End Switch			
								
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
		

			switch($frame_model){// Si la monture est une FUGLIES, on doit mettre le job type a edge and mount et ne pas afficher le swiss edging barcode, puisque swiss va fournir la monture.
				case 'RX01_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX02_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX03_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX04_BLK':  $job_type="Edge and Mount";  		$Swiss_Edging_Barcode = "";  break;
				case 'RX05_TORTOISE':  $job_type="Edge and Mount";  $Swiss_Edging_Barcode = "";  break;
				case 'RX06_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX07_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX08_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX09_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX10_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX11_BROWN':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX12_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX13_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX14_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;	
				case 'RX15_BLACK':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;
				case 'RX16_WHITE':  $job_type="Edge and Mount";  	$Swiss_Edging_Barcode = "";  break;	
			}//End Switch	
	
		//Temporairement hard  coder le job type a Edge and Mount a cause du bug Optipro  'Taillé non monté' qui apparait comme 'équilibrer dans certains optipro, apparament) 2018-11-21
		$job_type="Frame to Follow"; 
		
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		 $outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'	
		
		.  $orderItem["base_curve"].'","'  .  $Swiss_Edging_Barcode .'","'  . $orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$safety.'","'
		
		.$accNum.'","'.$TheShape. '","'.$IIMPACT.'"'.chr(13);
					
			}
	
	return $outputstring;
						
}







function get_header_string_PlasticPlus(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"EYE",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"BASE CURVE",';
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"EDGE POLISH",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}






function export_monthly_orders_acomba_hbc($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

	$queryAcomba = "SELECT distinct acomba_account_num FROM accounts WHERE user_id = (SELECT  user_id FROM orders WHERE order_num = $order_num  LIMIT 0,1)  LIMIT 0,1";
	echo '<br><br><br>' . $queryAcomba . '<br>';
	$ResultAcomba=mysqli_query($con,$queryAcomba)		or die  ('I cannot select 1 items because: ' . mysqli_error($con));
	$DataAcomba=mysqli_fetch_array($ResultAcomba,MYSQLI_ASSOC);
	
	$queryOrder = "SELECT  order_date_shipped, order_date_processed, order_num, order_total, order_from, order_shipping_cost, lab FROM orders  WHERE  order_num = $order_num LIMIT 0,1";
	echo '<br>' . $queryOrder . '<br>';
	$ResultOrder=mysqli_query($con,$queryOrder)		or die  ('I cannot select 1 items because: ' . mysqli_error($con));
	$DataOrder=mysqli_fetch_array($ResultOrder,MYSQLI_ASSOC);
	
	//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($DataOrder["order_from"]){
			case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
			case "safety":		$Prefix_Facture = "7"; break;//SAFE
			case "eye-recommend":$Prefix_Facture = "8"; break;//Eye Recommend
			case "hbc":			$Prefix_Facture = "9"; break;//Eye Recommend

			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		}
	
	if (($DataOrder["lab"] == 1) || ($DataOrder["lab"] == 3)){
		$Prefix_Facture = $Prefix_Facture . '0'.  $DataOrder["lab"]; 
		}else{
		//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
		$Prefix_Facture = $Prefix_Facture .  $DataOrder["lab"]; 
		}
		$OrderNum  =  $Prefix_Facture . '-'. $DataOrder["order_num"];
		
	
		$PayeparCC = 'non';
	
	
	$acomba_acct_num = $DataAcomba[acomba_account_num];
	echo '<br>acomba num: '.  $acomba_acct_num;
	echo '<br><br>Order Num :'. $DataOrder[order_num];
	$OrderTotal = $DataOrder[order_total] + $DataOrder[order_shipping_cost]  ;	
	if ($PayeparCC == 'oui'){	
	$outputstring.=$acomba_acct_num. ';' . $DataOrder["order_date_processed"].';'. $OrderNum . ';'. $OrderTotal. "\r\n";
	}else{
	$outputstring.=$acomba_acct_num. ';' . $DataOrder["order_date_shipped"].';'. $OrderNum . ';'. $OrderTotal. "\r\n";
	}
	return $outputstring;		
}








//New GKB-->HBC Export function 
function export_order_gkb_hbc($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

//SELECT DataBase->HBC
$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

	$Query="SELECT * FROM orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	echo '<br><br><br>Query: '. $Query; 
	$Result=mysqli_query($con,$Query)		or die  ('I cannot select items because: ' . mysqli_connect_error($con));
	
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		
	$UV400 = $orderItem[UV400];	
	if ($UV400<>''){	
		$UV400='400';	
	}
	
		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						default: 						$order_status = "UNKNOWN";	
		}

		$accQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysqli_query($con,$accQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$accItem=mysqli_fetch_array($accResult,MYSQLI_ASSOC);
			
		$accNum=$accItem[account_num];//TODO: Hard Coder le numéro de compte
		
		$labQuery  = "SELECT lab_name FROM labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult = mysqli_query($con,$labQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$labItem   = mysqli_fetch_array($labResult,MYSQLI_ASSOC);
			
		$PlabQuery="SELECT lab_name FROM labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysqli_query($con,$PlabQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$PlabItem=mysqli_fetch_array($PlabResult,MYSQLI_ASSOC);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
	
	
		if ($orderItem["optical_center"] <> ""){
			echo '<br><b>Partie Optical Center</b>';
			//Mettre les valeurs dans les bons champs
			$orderItem["le_height"] = $orderItem["optical_center"];
			$orderItem["re_height"] = $orderItem["optical_center"];	
		}//End if there is an optical center
	
		$ProdQuery="SELECT product_code FROM ifc_ca_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		echo '<br>ProdQuery: '. $ProdQuery;
		
		$ProdResult=mysqli_query($con,$ProdQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ProdItem=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);
			
		$color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
			
		$ShipQuery="SELECT shipping_code FROM accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysqli_query($con,$ShipQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ShipItem=mysqli_fetch_array($ShipResult,MYSQLI_ASSOC);
		//$shipping_code=$ShipItem[shipping_code];
		$shipping_code= 'OR005DLN';//Même que Dlab, car c'est expédié au même endroit. 
			
		$EngrQuery  = "SELECT engraving FROM extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult = mysqli_query($con,$EngrQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EngrItem   = mysqli_fetch_array($EngrResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];
			}else{
				$engraving="";}
			
		$TintQuery="SELECT tint,tint_color,from_perc,to_perc FROM extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysqli_query($con,$TintQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$TintItem=mysqli_fetch_array($TintResult,MYSQLI_ASSOC);
		$usercount=mysqli_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
				//Attribuer les bon codes de teintes avec GKB
				if ($tint_color=='Shooter Yellow'){//Fonctionne
					$tint_color = 'YELLSH';
				}
				if ($tint_color=='Shooter Orange'){//Fonctionne
					$tint_color = 'ORNASH';
				}
				if (($tint_color=='G-15') && ($from_perc==45)&& ($to_perc==45)){//Fonctionne
					$tint_color = 'G15C';
				}
				if (($tint_color=='G-15') && ($from_perc==65)&& ($to_perc==65)){//Fonctionne
					$tint_color = 'G15D';
				}
				if (($tint_color=='G-15') && ($from_perc==85)&& ($to_perc==85)){//Fonctionne
					$tint_color = 'G15E';
				}
				if (($tint_color=='Brown') && ($from_perc==45)&& ($to_perc==45)){//Fonctionne
					$tint_color = 'BROWNC';
				}
				if (($tint_color=='Brown') && ($from_perc==65)&& ($to_perc==65)){//Fonctionne
					$tint_color = 'BROWND';
				}
				if (($tint_color=='Brown') && ($from_perc==85)&& ($to_perc==85)){//Fonctionne
					$tint_color = 'BROWNE';
				}
				
			
				if (($tint_color=='Black Grey') && ($from_perc==45)&& ($to_perc==45)){//fonctionne
					$tint_color = 'GKB27';
				}
				if (($tint_color=='Black Grey') && ($from_perc==65)&& ($to_perc==65)){//fonctionne
					$tint_color = 'BLAGD';
				}
				if (($tint_color=='Black Grey') && ($from_perc==85)&& ($to_perc==85)){//fonctionne
					$tint_color = 'BLAGE';
				}
				if (($tint_color=='Black Grey') && ($from_perc==0)&& ($to_perc==85)){//fonctionne
					$tint_color = 'GKB25';
				}
				if (($tint_color=='Brown') && ($from_perc==0)&& ($to_perc==85)){//A TESTER
					$tint_color = 'GRBN85';
				}
				if ($tint_color=='Sky Rose'){
					$tint_color = 'SATROS';
					$tint="Solid";
				}

	
		
	
						switch($orderItem["order_product_coating"]){
							case 'Hard Coat': 	 $Coating = 'HC';        break;// Ok Validé avec Danielle
							case 'Uncoated': 	 $Coating = 'Uncoated';  break;
							case 'SPC':     	 $Coating = 'AQUA'; 	 break;
							case 'SPC Backside': $Coating = 'HMCB';  	 break;// Ok Validé avec Danielle 2015-09-16
							case 'Smart AR':  	 $Coating = 'HMC Super'; break;
							case 'Dream AR':     $Coating = 'AQUA'; 	 break;// Ok Validé avec Danielle
							case 'ITO AR':   	 $Coating = 'AQUA';      break;// Ok Validé avec Danielle
							case 'AR':   	 	 $Coating = 'AQUA';      break;
							case 'HD AR':        $Coating = 'LR';        break;// Ok Validé avec Danielle
							case 'Xlr':          $Coating = 'LR';        break; //LNC
							case 'AR Backside':  $Coating = 'HMCB';  	 break;// Ok Validé avec Danielle 2015-09-16
							case 'SPF': 		 $Coating = 'UV';   	 break;// Ok Validé avec Kamal 2015-12-10
							case 'Low Reflexion':$Coating = 'LR';    	 break;//ajouté 7 février 2017
							case 'iBlu':		 $Coating = 'BLUEX';     break;//ATTENTE DU CODE IBLU DE GKB
							default:             $Coating =$orderItem["order_product_coating"]; 
						}			
						
					
				
		$queryMirror  = "select category,tint_color, from_perc, to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Mirror'"; //Get MIRROR
		echo '<br>'. $queryMirror;
		$ResultMirror = mysqli_query($con,$queryMirror)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataMirror   = mysqli_fetch_array($ResultMirror,MYSQLI_ASSOC);
		$usercount    = mysqli_num_rows($ResultMirror);
			if ($usercount!=0){
				$tint		= $DataMirror[category];
				$tint_color = $DataMirror[tint_color];
				$from_perc  = $DataMirror[from_perc];
				$to_perc    = $DataMirror[to_perc];
				echo '<br>Tint'. $tint	;
				echo '<br>tint_color'. $tint_color	;
				
					if (($tint_color=='Green') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'GEHC';//HC
						$Coating    = 'GEHC';
					}elseif (($tint_color=='Green') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'GEAP';//AR Backside
						$Coating    = 'GEAP';//AR Backside
					}elseif (($tint_color=='Gold') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'GHC';//HC
						$Coating    = 'GHC';//HC
					}elseif (($tint_color=='Gold') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'GAP';//AR Backside
						$Coating    = 'GAP';//AR Backside
					}elseif (($tint_color=='Ocean Blue') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'OBHC';//HC
						$Coating    = 'OBHC';//HC
					}elseif (($tint_color=='Ocean Blue') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'OBAP';//AR Backside
						$Coating    = 'OBAP';//AR Backside
					}elseif (($tint_color=='Red') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'RHC';//HC
						$Coating    = 'RHC';//HC
					}elseif (($tint_color=='Red') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'RAP';//AR Backside
						$Coating    = 'RAP';//AR Backside
					}elseif (($tint_color=='Silver') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'SIHC';//HC
						$Coating    = 'SIHC';//HC
					}elseif (($tint_color=='Silver') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'SIAP';//AR Backside
						$Coating    = 'SIAP';//AR Backside
					}elseif (($tint_color=='Yellow') && ($orderItem["order_product_coating"]=='Hard Coat')){
						$tint_color = 'YHC';//HC
						$Coating    = 'YHC';//HC
					}elseif (($tint_color=='Yellow') && ($orderItem["order_product_coating"]<>'Hard Coat')){
						$tint_color = 'YAP';//AR Backside
						$Coating    = 'YAP';//AR Backside
					}
				
				}	//End IF


		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "SELECT myupload FROM orders  WHERE order_num ='$orderItem[order_num]'";
		$VerifResult=mysqli_query($con,$VerifIfShape)or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataVerif=mysqli_fetch_array($VerifResult,MYSQLI_ASSOC);
		$TheShape = $DataVerif['myupload'];
		

		if ($TheShape <> "") {
		$TheShape ="Yes";
		echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		echo  '<br> une shape attaché: Non';
		}
		
		//Demande de Swiss 2015-06-16: Si produit Stock: The shape='no'
		if (strpos($orderItem["order_product_name"],'Stock') == true){
			$TheShape ="No";
		}		
				

				
		$EdgeQuery  = "select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category IN('Edging','Edging_Frame')"; //Get EDGING
		$EdgeResult = mysqli_query($con,$EdgeQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$EdgeItem   = mysqli_fetch_array($EdgeResult,MYSQLI_ASSOC);
		$usercount  = mysqli_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				case 'Nylon Groove':		$frame_type="Nylon Groove";        break;
				case 'Metal':				$frame_type="Metal";		       break;
				case 'Drill & Notch':		$frame_type="Drill & Notch";       break;
				case 'Plastic':				$frame_type="Plastic";		       break;
				case 'Metal Groove':		$frame_type="Metal Groove";	  	   break;
				case 'Drill and Notch':		$frame_type="Drill and Notch";	   break;
				case 'Edge Polish':			$frame_type="Edge Polish";	       break;
				case 'Métal            ':	$frame_type="Metal";               break;
				case 'Fil Nylon        ':	$frame_type="Nylon Groove";	       break;
				case 'Percé       ':		$frame_type="Drill and Notch";     break;
				case 'Fil Métal           ':$frame_type="Metal Groove";        break;
				case 'Plastique':			$frame_type="Plastic";		  	   break;				
				default:					$frame_type= $EdgeItem[frame_type];break;
				}
				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				
				//Dans le cas d'un remote edging on ne transmet pas les mesures de la monture
				case 'remote edging':
				$job_type="remote edging";
				//$orderItem["frame_a"]   = '';
				//$orderItem["frame_b"]   = '';
				//$orderItem["frame_ed"]  = '';
				//$orderItem["frame_dbl"] = '';
				break;
				
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
				
				
	//Demande de GKB: remplacer la virgule dans le nom du produit par un point, car ils utilisent la virgule comme caractère délimitateur
	$orderItem["order_product_name"]=	str_replace(',','.',$orderItem["order_product_name"]);
	
	//Paramètres spéciaux pour HBC-->GKB
	$job_type  = "Uncut";
	$accNum    = "000126";//<----Nouveau numéro de compte confirmé par Sameer et Kamal 
	
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","DirectLab Network","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$Coating.'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.'","'
		//.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$accNum. '","'.$TheShape.'"'.chr(13);
		.$orderItem["base_curve"].'","'.$orderItem["RE_CT"].'","' .$orderItem["LE_CT"].'","' .$orderItem["RE_ET"].'","' .$orderItem["LE_ET"] .'","' .$EDGE_POLISH . '","'  .$accNum. '","'  .$TheShape. '","'. $UV400.'"'.chr(13);
					
			}
	
	return $outputstring;
						
}



?>