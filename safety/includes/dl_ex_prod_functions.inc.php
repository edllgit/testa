<?php 
function addExtraProducts($order_id){//ADD EXTRA PRODUCT ITEM TO ORDERS DB
			  

	addFrameItemSafety($order_id);

	
	if ($_SESSION['PrescrData']['ENGRAVING']!=""){
		addEngravingItem($order_id);
		}
	

	 /*?>if ($_SESSION['PrescrData']['TINT']!="None"){
		addTintItem($order_id);
	}<?php */
	
	
   //Removable side shield
	if ($_SESSION['PrescrData']['REMOVABLE_SIDE_SHIELD']!=0){
		addRemovableSideShield($order_id, $_SESSION['PrescrData']['REMOVABLE_SIDE_SHIELD_PRICE']);
	}
	
	//Cushion
	if ($_SESSION['PrescrData']['CUSHION']!=0){
		addCushion($order_id, $_SESSION['PrescrData']['CUSHION_SELLING_PRICE']);  
	}
	
	//Dust Bar
	if ($_SESSION['PrescrData']['DUST_BAR']=='on'){
		addDustBar($order_id, $_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE']);  
	}
		
		
	//Dispensing FEE SV
	if ($_SESSION['PrescrData']['DISPENSING_FEE_SV']> 0){
		//addDispensingFeeSV($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_SV']); 
		//echo '<br>Ajout Frais Dispensing FEE SV:' . $_SESSION['PrescrData']['DISPENSING_FEE_SV'];
	} 
	
	
	
	//Dispensing FEE Progressive
	if ($_SESSION['PrescrData']['DISPENSING_FEE_PROG']> 0){
		//addDispensingFeeProg($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_PROG']);  
		//echo '<br>Ajout Frais Dispensing FEE PROG:' . $_SESSION['PrescrData']['DISPENSING_FEE_PROG'];
	} 
	
	//Dispensing FEE Bifocal
	if ($_SESSION['PrescrData']['DISPENSING_FEE_BIFOCAL']> 0){
		//addDispensingFeeBifocal($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_BIFOCAL']); 
		//echo '<br>Ajout Frais Dispensing FEE BIFOCAL:' . $_SESSION['PrescrData']['DISPENSING_FEE_BIFOCAL']; 
	} 
			
	if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
			addEdgingItem($order_id);
	}
		
	
	
	
		
	if ($_SESSION['top_urgent'] == "yes"){
		addTopUrgent($order_id);
	}	
		
	
		
		//If order contains BOTH EYES, we check to see if there is a prism in one of the eye
	if ($_SESSION['PrescrData']['EYE']=="Both"){
		if (($_SESSION['PrescrData']['RE_PR_IO']!="None")||($_SESSION['PrescrData']['RE_PR_UD']!="None")||($_SESSION['PrescrData']['LE_PR_IO']!="None")||(	$_SESSION['PrescrData']['LE_PR_UD']!="None")){
			addPrismItem($order_id);
			}
	}
	
	
	//If order contains LEFT EYE ONLY, we check to see if there is a prism in the left eye
	if ($_SESSION['PrescrData']['EYE']=="L.E."){
		if (($_SESSION['PrescrData']['LE_PR_IO']!="None")||(	$_SESSION['PrescrData']['LE_PR_UD']!="None")){
			addPrismItem($order_id);
			}
	}
	
	
	//If order contains RIGHT EYE ONLY, we check to see if there is a prism in the right eye
	if ($_SESSION['PrescrData']['EYE']=="R.E."){
		if (($_SESSION['PrescrData']['RE_PR_IO']!="None")||($_SESSION['PrescrData']['RE_PR_UD']!="None")){
			addPrismItem($order_id);
			}
	}
	
	
}
	
	
	function addPrismItem($order_id){//ADD PRISM PRODUCT ITEM TO ORDERS DB

	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Prism";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
	$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysql_query($query)
		or die ( "Insert Prism query failed: " . mysql_error() . "<br/>" . $query );
}



function addTopUrgent($order_id){//ADD Top urgent

	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Top urgent";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
	$result=mysql_query($query)	or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysql_query($query)
		or die ( "Insert top urgent query failed: " . mysql_error() . "<br/>" . $query );
}





function addEngravingItem($order_id){//ADD ENGRAVING PRODUCT ITEM TO ORDERS DB

	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving=addslashes($_SESSION['PrescrData']['ENGRAVING']);
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Engraving";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
	$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysql_query($query)
		or die ( "Insert Engraving query failed: " . mysql_error() . "<br/>" . $query );
}
function addTintItem($order_id){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving="";
	$tint=addslashes($_SESSION['PrescrData']['TINT']);
	$tint_color=addslashes($_SESSION['PrescrData']['TINT_COLOR']);
	$from_perc=addslashes($_SESSION['PrescrData']['FROM_PERC']);
	$to_perc=addslashes($_SESSION['PrescrData']['TO_PERC']);
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Tint";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND tint='$tint' ";
	
	$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysql_query($query)
		or die ( "Insert Tint query failed: " . mysql_error() . "<br/>" . $query );
}
function addEdgingItem($order_id){//ADD EDGING PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a=addslashes($_SESSION['PrescrData']['FRAME_A']);
	$ep_frame_b=addslashes($_SESSION['PrescrData']['FRAME_B']);
	$ep_frame_ed=addslashes($_SESSION['PrescrData']['FRAME_ED']);
	$ep_frame_dbl=addslashes($_SESSION['PrescrData']['FRAME_DBL']);
	$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($_SESSION['PrescrData']['JOB_TYPE']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$supplier=addslashes($_SESSION['PrescrData']['SUPPLIER']);
	$frame_model=addslashes($_SESSION['PrescrData']['FRAME_MODEL']);
	$temple_model_num=addslashes($_SESSION['PrescrData']['TEMPLE_MODEL']);
	$color=addslashes($_SESSION['PrescrData']['COLOR']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$temple=addslashes($_SESSION['PrescrData']['TEMPLE']);

	$order_num=$order_id;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	
	if (($frame_model!="")&&($order_type=="Provide"))//HAS FRAME
		$category="Edging_Frame";
	else
		$category="Edging";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
		
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysql_query($query)
		or die ( "Insert Edging query failed: " . mysql_error() . "<br/>" . $query );
}

function addFrameItem($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a=addslashes($_SESSION['PrescrData']['FRAME_A']);
	$ep_frame_b=addslashes($_SESSION['PrescrData']['FRAME_B']);
	$ep_frame_ed=addslashes($_SESSION['PrescrData']['FRAME_ED']);
	$ep_frame_dbl=addslashes($_SESSION['PrescrData']['FRAME_DBL']);
	$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($_SESSION['PrescrData']['JOB_TYPE']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$supplier=addslashes($_SESSION['PrescrData']['SUPPLIER']);
	$frame_model=addslashes($_SESSION['PrescrData']['FRAME_MODEL']);
	$temple_model_num=addslashes($_SESSION['PrescrData']['TEMPLE_MODEL']);
	$color=addslashes($_SESSION['PrescrData']['COLOR']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$temple=addslashes($_SESSION['PrescrData']['TEMPLE']);
	$high_index_addition=$_POST['high_index_addition'];

	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Frame";
	
	$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
	$query="SELECT * FROM frames 
			LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
			WHERE model_num='$frame_model_num'";
	$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_US];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_CA];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_EUR];}
	$ep_prod_id=$listItem[frame_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price,high_index_addition) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price','$high_index_addition')";

	$result=mysql_query($query)
		or die ( "Insert Frame query failed: " . mysql_error() . "<br/>" . $query );
}




function addDustBar($order_id, $extra_amount ){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	$order_num=-1;
	$category="Dust Bar";
	//$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";

//echo '<br><br>'. $query . '<br><br>';
	$result=mysql_query($query) or die ( "Insert Dust Bar query failed: " . mysql_error() . "<br/>" . $query );
}






function addRemovableSideShield($order_id, $extra_amount){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	$order_num=-1;
	$category="Removable Side Shield";
	//$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";

//echo '<br><br>'. $query . '<br><br>';
	$result=mysql_query($query) or die ( "Insert removable side shield query failed: " . mysql_error() . "<br/>" . $query );
}



function addCushion($order_id, $extra_amount){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	$order_num=-1;
	$category="Cushion";
	//$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";

//echo '<br><br>'. $query . '<br><br>';
	$result=mysql_query($query) or die ( "Insert Cushion query failed: " . mysql_error() . "<br/>" . $query );
}











function addDispensingFeeSV($order_id, $Dispensing_fee_amount){//ADD Dispensing Fee PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple=$Dispensing_fee_amount;
	$order_num=-1;
	$category="Dispensing Fee SV";
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','15')";

	$result=mysql_query($query) or die ( "Insert Dispensing Fee SV query failed: " . mysql_error() . "<br/>" . $query );
}



function addDispensingFeeProg($order_id, $Dispensing_fee_amount){//ADD Dispensing Fee PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple=$Dispensing_fee_amount;
	$order_num=-1;
	$category="Dispensing Fee Progressive";
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','25')";

	$result=mysql_query($query) or die ( "Insert Dispensing Fee SV query failed: " . mysql_error() . "<br/>" . $query );
}




function addDispensingFeeBifocal($order_id, $Dispensing_fee_amount){//ADD Dispensing Fee PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple=$Dispensing_fee_amount;
	$order_num=-1;
	$category="Dispensing Fee Bifocal";
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','20')";

	$result=mysql_query($query) or die ( "Insert Dispensing Fee Bifocal query failed: " . mysql_error() . "<br/>" . $query );
}











function addFrameItemSafety($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	$ep_frame_a=addslashes($_SESSION['PrescrData']['FRAME_A']);
	$ep_frame_b=addslashes($_SESSION['PrescrData']['FRAME_B']);
	$ep_frame_ed=addslashes($_SESSION['PrescrData']['FRAME_ED']);
	$ep_frame_dbl=addslashes($_SESSION['PrescrData']['FRAME_DBL']);
	$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($_SESSION['PrescrData']['JOB_TYPE']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$supplier=addslashes($_SESSION['PrescrData']['SUPPLIER']);
	$frame_model=addslashes($_SESSION['PrescrData']['FRAME_MODEL']);
	$temple_model_num=addslashes($_SESSION['PrescrData']['TEMPLE_MODEL']);
	$color=addslashes($_SESSION['PrescrData']['COLOR']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$temple=addslashes($_SESSION['PrescrData']['TEMPLE']);
	$high_index_addition=$_POST['high_index_addition'];

//Pour obtenir le prix, on doit additionner le prix de la monture et des verres			
if ($_SESSION[safety_plan]     == 'regular price'){
	$price =  $_SESSION['PrescrData']['FRAME_SELLING_PRICE'] ;
}elseif($_SESSION[safety_plan] == 'interco price'){
	$price =  $_SESSION['PrescrData']['FRAME_INTERCO'] ;
}elseif($_SESSION[safety_plan] == 'discounted price'){
	$price =  $_SESSION['PrescrData']['FRAME_DISCOUNTED_PRICE'] ;
}

	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Frame";
	
	$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
	$query="SELECT * FROM frames 
			LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
			WHERE model_num='$frame_model_num'";
	$result=mysql_query($query) or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	$ep_prod_id=$listItem[frame_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price,high_index_addition) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price','$high_index_addition')";

	$result=mysql_query($query)
		or die ( "Insert Frame query failed: " . mysql_error() . "<br/>" . $query );
}




?>
