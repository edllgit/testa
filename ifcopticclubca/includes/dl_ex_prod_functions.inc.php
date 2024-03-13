<?php
function addExtraProducts($order_id){//ADD EXTRA PRODUCT ITEM TO ORDERS DB
	include "../sec_connectEDLL.inc.php";	
	$queryUserid  = "SELECT user_id FROM orders where primary_key = $order_id";	
	$resultUserid = mysqli_query($con,$queryUserid)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataUserID   = mysqli_fetch_array($resultUserid,MYSQLI_ASSOC);
	$TheUserId    = $DataUserID[user_id] ;
	
	$CompteEntrepotTR    = 'no';	
	$CompteEntrepotAutre = 'no';
	
	
	if ($TheUserId=='entrepotifc') 
	{
		$CompteEntrepotTR      = 'yes';
		$CompteEntrepotAutre   = 'no';
	}
	
	if ($TheUserId=='entrepotdr') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}	
	
	if ($TheUserId=='longueuil') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}	
	
	if ($TheUserId=='terrebonne') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}	
	
	if ($TheUserId=='levis') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}	
	
	if ($TheUserId=='warehousestc') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}	
	
		
	if ($TheUserId=='laval') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	
	if ($TheUserId=='warehousehal') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	
	if ($TheUserId=='sherbrooke') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	
	if ($TheUserId=='chicoutimi') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	
	if ($TheUserId=='granby') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	

	
	
	if ($TheUserId=='entrepotquebec') 
	{
		$CompteEntrepotTR      = 'no';
		$CompteEntrepotAutre   = 'yes';
	}
	
	//Cushion
	if ($_SESSION['PrescrData']['CUSHION']!=0){
		addCushion($order_id, $_SESSION['PrescrData']['CUSHION_SELLING_PRICE']);  
	}
	
	//Dust Bar
	if ($_SESSION['PrescrData']['DUST_BAR']=='on'){
		addDustBar($order_id, $_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE']);  
	}
	
	//Edge Polish
	if (($CompteEntrepotTR == 'yes') && ($_SESSION['PrescrData']['EDGE_POLISH'] == 'yes')){
		addEdgePolish($order_id);
	}
		
	
	
	
	////Début Prisme
		//Prismes entre 0.1 et 4
		//If order contains BOTH EYES, we check to see if there is a prism in one of the eye
			if ($_SESSION['PrescrData']['EYE']=="Both"){
				if  (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX']< 10 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX']< 10 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX2']< 10 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX2']< 10 )){
					addPrismItem($order_id);
				}//End IF
			}

			//If order contains LEFT EYE ONLY, we check to see if there is a prism in the left eye
			if ($_SESSION['PrescrData']['EYE']=="L.E."){
				if       (($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX']< 10 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX2']< 10 )){
					addPrismItem($order_id);
				}//End IF
			}
			
			
			//If order contains RIGHT EYE ONLY, we check to see if there is a prism in the right eye
			if ($_SESSION['PrescrData']['EYE']=="R.E."){
				if      (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX']< 10 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX2']< 10 )){
					addPrismItem($order_id);
				}//End IF
			}	
	/////////////Fin prisme
		


	if ($_SESSION['PrescrData']['TEMPLE_MODEL']!=""){
		if ($CompteEntrepotTR =='yes'){
			//addFrameItemIfcEntrepotTR($order_id);  
			addFrameItemIfcEntrepotAutre($order_id);//Pour TOUS les entrepots SAUF TROIS-RIVIERE
		}
		
		if($CompteEntrepotAutre == 'yes'){
			addFrameItemIfcEntrepotAutre($order_id);//Pour TOUS les entrepots SAUF TROIS-RIVIERES 
		}
		
		
		if (($CompteEntrepotTR =='no') && ($CompteEntrepotAutre =='no')){
			addFrameItemIfc($order_id);//Client régulier ifc.ca--> PAS UN ENTREPOT
		}
	}

	

	/*if ($_SESSION['PrescrData']['ENGRAVING']!=""){
		addEngravingItem($order_id);
		}*/
		
		
	if ($_SESSION['PrescrData']['MIRROR']!="none"){//UN MIRROIR A ÉTÉ COMMANDÉ
		if (($CompteEntrepotTR == 'yes') || ($CompteEntrepotAutre == 'yes'))	{
			addMirrorItem($order_id);
		}
	}
	

	if ($_SESSION['PrescrData']['TINT']!="None"){
		addTintItem($order_id);
		}
		
		
		
			
	if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
		if (($CompteEntrepotTR =='no') && ($CompteEntrepotAutre == 'no')){//N'est pas un entrepot
			addEdgingItem($order_id);
		}else{//Est un compte EDLL
			addEdgingItemEntrepot($order_id);
		}
	}
		
	
	
	
		
	if ($_SESSION['top_urgent'] == "yes"){
		addTopUrgent($order_id);
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
	
		
	
	//Cylindre Over Range
	$RE_CYL = $_SESSION['PrescrData']['RE_CYL_NUM'] . $_SESSION['PrescrData']['RE_CYL_DEC'];
	$LE_CYL = $_SESSION['PrescrData']['LE_CYL_NUM'] . $_SESSION['PrescrData']['LE_CYL_DEC'];
	if (($RE_CYL < -4) || ($LE_CYL < -4)){
		addCylOverRange($order_id);
		//echo '<br>Extra Over Range Cylindre';	
	}
	//echo '<br>RE:'. $RE_CYL ;
	//echo '<br>LE:'. $LE_CYL ;
		
		
			
	//Dispensing FEE SV
	if ($_SESSION['PrescrData']['DISPENSING_FEE_20']> 0){
		//addDispensingFeeSV($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_20']); 
		//echo '<br>Ajout Frais Dispensing FEE 2:' . $_SESSION['PrescrData']['DISPENSING_FEE_SV'];
	} 
	
	
	
	//Dispensing FEE Progressive
	if ($_SESSION['PrescrData']['DISPENSING_FEE_30']> 0){
		//addDispensingFeeProg($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_30']);  
		//echo '<br>Ajout Frais Dispensing FEE 30:' . $_SESSION['PrescrData']['DISPENSING_FEE_PROG'];
	} 
	
	
	//Dispensing FEE Bifocal
	if ($_SESSION['PrescrData']['DISPENSING_FEE_25']> 0){
		//addDispensingFeeBifocal($order_id, $_SESSION['PrescrData']['DISPENSING_FEE_25']); 
		//echo '<br>Ajout Frais Dispensing FEE25:' . $_SESSION['PrescrData']['DISPENSING_FEE_BIFOCAL']; 
	} 
	
	
}
	
	
	function addPrismItem($order_id){//ADD PRISM PRODUCT ITEM TO ORDERS DB
	include "../sec_connectEDLL.inc.php";
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert Prism query failed: " . mysqli_error($con) . "<br/>" . $query );
}



function addTopUrgent($order_id){//ADD Top urgent
	include "../sec_connectEDLL.inc.php";
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
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert top urgent query failed: " . mysqli_error($con) . "<br/>" . $query );
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert Engraving query failed: " . mysqli_error($con) . "<br/>" . $query );
}
function addTintItem($order_id){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	include "../sec_connectEDLL.inc.php";
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
	
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert Tint query failed: " . mysqli_error($con) . "<br/>" . $query );
}




function addEdgingItem($order_id){//ADD EDGING PRODUCT ITEM TO ORDERS DB
	
	include "../sec_connectEDLL.inc.php";
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
	$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);
	$temple_model_num=addslashes($_SESSION['PrescrData']['TEMPLE_MODEL']);
	$color=addslashes($_SESSION['PrescrData']['COLOR']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$temple=addslashes($_SESSION['PrescrData']['TEMPLE']);

	$order_num=$order_id;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	

		$category="Edging";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	//if ($TheUserId='terrebonne')
	//echo '<br>'. $query;
	$result=mysqli_query($con,$query)				or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
		
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','0')";
	//if ($TheUserId='terrebonne')
	//echo '<br>'. $query;

	$result=mysqli_query($con,$query)		or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $query );
}

function addFrameItem($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	
	include "../sec_connectEDLL.inc.php";
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






function addFrameItemIfcEntrepotTR($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	
	include "../sec_connectEDLL.inc.php";
	
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



switch($supplier){
case 'NAPOLEONE':	     $price = 79.95; break;
case 'PREMIUM PLUS':	 $price = 55.00; break;
case 'BRANDS':           $price = 52.00; break;
case 'TOM FORD':    	 $price = 52.00; break;
case 'RAY-BAN':     	 $price = 52.00; break;
case 'OXBOW':       	 $price = 52.00; break;
case 'NIKE VISION': 	 $price = 52.00; break;  
case 'JOHN LENNON': 	 $price = 52.00; break;   
case 'GIVENCHY':    	 $price = 52.00; break; 
case 'CALVIN KLEIN':	 $price = 52.00; break;  
case 'GANT':        	 $price = 52.00; break; 
case 'ECLIPSE':     	 $price = 52.00; break;
case 'ARROW':       	 $price = 52.00; break;
case 'NURBS':       	 $price = 50.00; break; 
case 'FUGLIES_C':   	 $price = 49.95; break;
//Sous collections appartenant a Fuglies_C
case 'RX03':   	         $price = 49.95; break;
case 'RX04':   	         $price = 49.95; break;
case 'RX14':   	         $price = 49.95; break;
case 'RX15':   	         $price = 49.95; break;
case 'RX16':   	         $price = 49.95; break;

case '19V69':       	 $price = 49.00; break;
case 'MONTANA +':   	 $price = 45.00; break;//Modifié le 11 aout 2014
case 'MILANO 6769': 	 $price = 45.00; break;
case 'MILANO 6769 BRERA': 	$price = 45.00; break;
case 'MILANO 6769 CONSIGNE':$price = 45.00; break;
case 'PERCE':       	 $price = 40.00; break;
case 'CEBE':        	 $price = 40.00; break;
case 'OPTIMIZE':    	 $price = 40.00; break;
case 'RUIMANNI':    	 $price = 40.00; break;  
case 'SERMATT':    	 	 $price = 40.00; break;
case 'ISEE 2':        	 $price = 39.95; break;
case 'MONTANA':    	 	 $price = 39.00; break;//ajouté 7 mai
case 'PREMIUM':    		 $price = 39.00; break;  
case 'FUGLIES_B':  	 	 $price = 38.95; break;
//Sous collections appartenant a Fuglies_B
case 'RX05':  	 	     $price = 38.95; break;
case 'RX06':  	 	     $price = 38.95; break;

case 'BUGETTI':     	 $price = 0.00; break;//Mis a jour 3 février 2016: Émilie
case 'RENDEZVOUS':  	 $price = 0.00; break;//Mis a jour 3 février 2016: Émilie
case 'MODELLI':     	 $price = 0.00; break;//Mis a jour 3 février 2016: Émilie

case 'FUGLIES_A':   	 $price = 27.95; break;
//Sous collections appartenant a Fuglies_A
case 'RX01':   		 	 $price = 27.95; break;
case 'RX02':   	 		 $price = 27.95; break;
case 'RX07':   	 		 $price = 27.95; break;
case 'RX08':   			 $price = 27.95; break;
case 'RX09':   			 $price = 27.95; break;
case 'RX10':   			 $price = 27.95; break;
case 'RX11':   			 $price = 27.95; break;
case 'RX12':   		 	 $price = 27.95; break;
case 'RX13':   			 $price = 27.95; break;

case 'SUNOPTIC':    	 $price = 25.00; break;
case 'SUNOPTIC MASSIMO': $price = 25.00; break; //ajouté 7 mai
case 'MASSIMO':     	 $price = 25.00; break;
case 'ISEE':        	 $price = 23.00; break;
case 'VARIONET':    	 $price = 20.00; break;//Modifié le 8 mai
case 'CLIP SOLAIRES':    $price = 19.80; break;//ajouté 4 juillet
case 'SUNOPTIC AK': 	 $price = 19.50; break;//ajouté 7 mai		
case 'FREE PLUS':   	 $price = 15.00; break;
case 'SUNOPTIC K':  	 $price = 14.50; break;//ajouté 7 mai
case 'BLUE RAY':    	 $price = 12.00; break;//maj 29 sept
case 'FREE':        	 $price =  8.00; break;
case 'FRM':        	 	 $price =  8.00; break;//Sous collection de FREE
case 'FRP':        	 	 $price =  8.00; break;//Sous collection de FREE
case 'SM':        	 	 $price =  8.00; break;//Sous collection de FREE
case 'SP':        	 	 $price =  8.00; break;//Sous collection de FREE
case 'SUNOPTIC CP': 	 $price =  8.00; break; //ajouté 7 mai
case 'SUNOPTIC PK': 	 $price =  8.00; break; //ajouté 7 mai	

case 'SEVENTEEN':    	 $price =  0.00; break;
case 'ELEGANTE':    	 $price =  0.00; break;
case 'PEACE':    		 $price =  0.00; break;
case 'REFLEXION':    	 $price =  0.00; break;
case 'FOCUS':    	 	 $price =  0.00; break;
case 'ADIDAS':    	 	 $price =  0.00; break;
case 'INFACE':    	 	 $price =  0.00; break;
case 'EDEL':    	 	 $price =  0.00; break;
case 'PRO':    		 	 $price =  0.00; break;
case 'JIL SANDER':       $price =  0.00; break;
case 'JIL SANDER SOL':   $price =  0.00; break;
case 'FINEZZA':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'PASCALE':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'CHARMANT':         $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'ARISTAR':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'ESPRIT':           $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'PUMA':			 $price =     0; break;
case 'FELIX MARCS':      $price =     0; break;//Sans Frais Puisque  JN fournit la monture Ajouté le 5 septembre 2014
case 'AUTRES':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'HAGGAR':     		 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'DI GIANNI':   	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'POLAR':       	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'GO IWEAR':    	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'BRENDELL':    	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'GIA VISTO':   	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'MARC OPOLO':  	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'SECG':        	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'SILOAM':     	 	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'STAR':       	 	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture 
case 'VENETO':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'ZENZERO':     	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'NORDIC':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'HUM SOL':     	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'KING SIZE':  	     $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'ERNEST HEMINGWAY': $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'WRANGLER JEANS CO':$price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'SILHOUETTE': 		 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'CASINO':     	 	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'JELLY BEAN':  	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'MARC HUNTER': 	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'JUBILLE':     	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'DALE JR':     	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'WOOLRICH':    	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'JOAN COLLINS':	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'NICKELODEON': 	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'HUMPHREY':    	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'DOLCE GABANNA':    $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'TIFFANY & CO':     $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'OAKLEY':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'LACOSTE':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'ARGOS':      		 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'CARISMA':      	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
case 'ArmouRx':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price];
break;
//Sous collections faisant partie de ARmouRx
case 'Basic':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price];
break;
case 'Classic':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price];
break;
case 'Metro':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price];
break;
case 'Wrap-Rx':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price];
break;
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














function addFrameItemIfc($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	
	include "../sec_connectEDLL.inc.php";

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


switch($supplier){
case 'MILANO 6769':      $price = 62.95; break;
case 'MILANO 6769 BRERA':   $price = 62.95; break;
case 'MILANO 6769 CONSIGNE':$price = 62.95; break;
case 'BRANDS':           $price = 59.95; break;
case 'PREMIUM PLUS':     $price = 55.00; break;
case 'PREMIUM':    	     $price = 55.00; break;
case 'SUNOPTIC MASSIMO': $price = 53.95; break;
case 'NURBS':            $price = 50.00; break;

case 'FUGLIES_C':        $price = 49.95; break;
//Sous collections appartenant a Fuglies_C
case 'RX03':   	         $price = 49.95; break;
case 'RX04':   	         $price = 49.95; break;
case 'RX14':   	         $price = 49.95; break;
case 'RX15':   	         $price = 49.95; break;
case 'RX16':   	         $price = 49.95; break;

case 'BUGETTI':          $price = 44.95; break;
case 'ISEE':             $price = 34.95; break;//Ajouté le 10 juillet 2014
case 'CEBE':        	 $price = 40.00; break;
case 'RENDEZVOUS': 	     $price = 39.95; break;
case 'MODELLI':     	 $price = 39.95; break;

case 'FUGLIES_B':   	 $price = 38.95; break;
//Sous collections appartenant a Fuglies_B
case 'RX05':  	 	     $price = 38.95; break;
case 'RX06':  	 	     $price = 38.95; break;

case 'FUGLIES_A':   	 $price = 27.95; break;
//Sous collections appartenant a Fuglies_A
case 'RX01':   		 	 $price = 27.95; break;
case 'RX02':   	 		 $price = 27.95; break;
case 'RX07':   	 		 $price = 27.95; break;
case 'RX08':   			 $price = 27.95; break;
case 'RX09':   			 $price = 27.95; break;
case 'RX10':   			 $price = 27.95; break;
case 'RX11':   			 $price = 27.95; break;
case 'RX12':   		 	 $price = 27.95; break;
case 'RX13':   			 $price = 27.95; break;



case 'FREE PLUS':   	 $price = 19.95; break;
case 'BLUE RAY':         $price = 24.95; break;//Modifié le 29 sept 2014
case 'FREE':        	 $price = 14.95; break;
case 'FRM':        	 	 $price = 14.95; break;//Sous collection de FREE
case 'FRP':        	 	 $price = 14.95; break;//Sous collection de FREE
case 'SM':        	 	 $price = 14.95; break;//Sous collection de FREE
case 'SP':        	 	 $price = 14.95; break;//Sous collection de FREE
case 'POLAR':       	 $price =     0; break;//Sans Frais Puisque  JN fournit la monture   
case 'MONTANA':     	 $price =     0; break;//ATTENTE DU PRIX     
case '':      	    	 $price =     0; break;//Aucune monture
case 'AUTRES':     		 $price =     0; break;// 0 Car monture fournie par le client

}

	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Frame";
	
	$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
	$query="SELECT * FROM frames 
			LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
			WHERE model_num='$frame_model_num'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$ep_prod_id=$listItem[frame_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price,high_index_addition) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price','$high_index_addition')";

	$result=mysqli_query($con,$query)		or die ( "Insert Frame query failed: " . mysqli_error($con) . "<br/>" . $query );
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
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','0')";

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
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','0')";

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
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','0')";

	$result=mysql_query($query) or die ( "Insert Dispensing Fee Bifocal query failed: " . mysql_error() . "<br/>" . $query );
}




function addFrameItemIfcEntrepotAutre($order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
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



switch($supplier){
//On charge uniquement sur chaque facture pour : Fuglies,  les collections Sunoptics et ArmouRx 2014-08-20:Daniel Beaulieu
case 'FUGLIES_C':   	 $price = 49.95; break;
//Sous collections appartenant a Fuglies_C
case 'RX03':   	         $price = 49.95; break;
case 'RX04':   	         $price = 49.95; break;
case 'RX14':   	         $price = 49.95; break;
case 'RX15':   	         $price = 49.95; break;
case 'RX16':   	         $price = 49.95; break;	
case 'FUGLIES_B':  	 	 $price = 38.95; break;
//Sous collections appartenant a Fuglies_B
case 'RX05':  	 	     $price = 38.95; break;
case 'RX06':  	 	     $price = 38.95; break;
case 'BUGETTI':  	 	 $price = 0.00; break;//Ajouté le 24 Octobre 2014
case 'RENDEZVOUS':  	 $price = 0.00; break;//Ajouté le 24 Octobre 2014
case 'MODELLI':  	 	 $price = 0.00; break;//Ajouté le 24 Octobre 2014
case 'FUGLIES_A':   	 $price = 27.95; break;
//Sous collections appartenant a Fuglies_A
case 'RX01':   		 	 $price = 27.95; break;
case 'RX02':   	 		 $price = 27.95; break;
case 'RX07':   	 		 $price = 27.95; break;
case 'RX08':   			 $price = 27.95; break;
case 'RX09':   			 $price = 27.95; break;
case 'RX10':   			 $price = 27.95; break;
case 'RX11':   			 $price = 27.95; break;
case 'RX12':   		 	 $price = 27.95; break;
case 'RX13':   			 $price = 27.95; break;

case 'ArmouRx':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price_entrepot FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price_entrepot];
break;
//Sous collections faisant partie de ARmouRx
case 'Basic':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price_entrepot FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price_entrepot];
break;
case 'Classic':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price_entrepot FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price_entrepot];
break;
case 'Metro':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price_entrepot FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price_entrepot];
break;
case 'Wrap-Rx':  
//Prix différents pour chaque monture, aller le chercher dans la base de données
$queryPrice  	= "SELECT stock_price_entrepot FROM ifc_frames_french WHERE model = '". $_SESSION['PrescrData']['TEMPLE_MODEL']. "' AND color_en =  '". $_SESSION['PrescrData']['COLOR']. "'";
$resultPrice 	= mysql_query($queryPrice)	or die  ("An error occured.". mysql_error());
$DataPrice   	= mysql_fetch_array($resultPrice);
$price          = $DataPrice[stock_price_entrepot];
break;


//Toutes les autres montures sont déja facturé aux entrepots donc a 0 sur facture package
case 'MONTANA':    	 	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'MONTANA +':   	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC':    	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC MASSIMO': $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC AK': 	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC K':  	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC CP': 	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SUNOPTIC PK': 	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie
case 'SERMATT':    	 	 $price = 0.00; break;//Mis a 0 le 4 janvier 2016:Émilie

case 'ENHANCE':          $price =  0.00; break;
case 'HAGGAR HFT':       $price =  0.00; break;
case 'VALENTINO':        $price =  0.00; break;
case 'JIL SANDER':       $price =  0.00; break;
case 'JIL SANDER SOL':   $price =  0.00; break;
case 'SEVENTEEN':    	 $price =  0.00; break;
case 'ELEGANTE':    	 $price =  0.00; break;
case 'PEACE':    		 $price =  0.00; break;
case 'REFLEXION':    	 $price =  0.00; break;
case 'FOCUS':    	 	 $price =  0.00; break;
case 'ADIDAS':    	 	 $price =  0.00; break;
case 'INFACE':    	 	 $price =  0.00; break;
case 'EDEL':    	 	 $price =  0.00; break;
case 'PRO':    		 	 $price =  0.00; break;
case 'CARISMA':      	 $price =     0; break;
case 'NAPOLEONE':	     $price =     0; break;
case 'FELIX MARCS':      $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 5 septembre 2014
case 'FINEZZA':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'PASCALE':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'CHARMANT':         $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'ARISTAR':          $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'ESPRIT':           $price =     0; break;//Sans Frais Puisque  les entrepot fournissent la monture: Ajouté le 11 septembre 2014
case 'THIERRY MUGLER':	 $price =     0; break;
case 'PUMA':			 $price =     0; break;
case 'VALERIE SPENCER':	 $price =     0; break;
case 'IKII':	 		 $price =     0; break;
case 'XONE':	 		 $price =     0; break;
case 'AZARO':	 		 $price =     0; break;
case 'PREMIUM PLUS':	 $price =     0; break;
case 'BRANDS':           $price =     0; break;
case 'TOM FORD':    	 $price =     0; break;
case 'RAY-BAN':     	 $price =     0; break;
case 'OXBOW':       	 $price =     0; break;
case 'NIKE VISION': 	 $price =     0; break;  
case 'JOHN LENNON': 	 $price =     0; break;   
case 'GIVENCHY':    	 $price =     0; break; 
case 'CALVIN KLEIN':	 $price =     0; break;  
case 'GANT':        	 $price =     0; break; 
case 'ECLIPSE':     	 $price =     0; break;
case 'ARROW':       	 $price =     0; break;
case 'NURBS':       	 $price =     0; break; 
case '19V69':       	 $price =     0; break;
case 'MILANO 6769': 	 $price =     0; break;
case 'MILANO 6769 BRERA': 	$price =  0; break;
case 'MILANO 6769 CONSIGNE':$price =  0; break;
case 'PERCE':       	 $price =     0; break;
case 'CEBE':        	 $price =     0; break;
case 'OPTIMIZE':    	 $price =     0; break;
case 'RUIMANNI':    	 $price =     0; break;  
case 'PREMIUM':    		 $price =     0; break;  
case 'BUGETTI':     	 $price =     0; break;
case 'RENDEZVOUS':  	 $price =     0; break;
case 'MODELLI':     	 $price =     0; break;
case 'MASSIMO':     	 $price =     0; break;
case 'ISEE':        	 $price =     0; break;
case 'ISEE 2':        	 $price =     0; break;
case 'VARIONET':    	 $price =     0; break;
case 'CLIP SOLAIRES':    $price =     0; break;
case 'FREE PLUS':   	 $price =     0; break;
case 'BLUE RAY':    	 $price =     0; break;
case 'FREE':        	 $price =     0; break;
case 'FRM':        	 	 $price =     0; break;
case 'FRP':        	 	 $price =     0; break;
case 'SM':        	 	 $price =     0; break;
case 'SP':        	 	 $price =     0; break;
case 'AUTRES':      	 $price =     0; break;
case 'HAGGAR':     		 $price =     0; break;
case 'DI GIANNI':   	 $price =     0; break;
case 'POLAR':       	 $price =     0; break;
case 'GO IWEAR':    	 $price =     0; break;
case 'BRENDELL':    	 $price =     0; break;
case 'GIA VISTO':   	 $price =     0; break;
case 'MARC OPOLO':  	 $price =     0; break;
case 'SECG':        	 $price =     0; break;
case 'SILOAM':     	 	 $price =     0; break;
case 'STAR':       	 	 $price =     0; break; 
case 'VENETO':      	 $price =     0; break;
case 'ZENZERO':     	 $price =     0; break;
case 'NORDIC':      	 $price =     0; break;
case 'HUM SOL':     	 $price =     0; break;
case 'KING SIZE':  	     $price =     0; break;
case 'ERNEST HEMINGWAY': $price =     0; break;
case 'WRANGLER JEANS CO':$price =     0; break;
case 'SILHOUETTE': 		 $price =     0; break;
case 'CASINO':     	 	 $price =     0; break;
case 'JELLY BEAN':  	 $price =     0; break;
case 'MARC HUNTER': 	 $price =     0; break;
case 'JUBILLE':     	 $price =     0; break;
case 'DALE JR':     	 $price =     0; break;
case 'WOOLRICH':    	 $price =     0; break;
case 'JOAN COLLINS':	 $price =     0; break;
case 'NICKELODEON': 	 $price =     0; break;
case 'HUMPHREY':    	 $price =     0; break;
case 'DOLCE GABANNA':    $price =     0; break;
case 'TIFFANY & CO':     $price =     0; break;
case 'OAKLEY':      	 $price =     0; break;
case 'LACOSTE':      	 $price =     0; break;
case 'ARGOS':      		 $price =     0; break;//Sans Frais Puisque  JN fournit la monture
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











function addMirrorItem($order_id){//ADD TINT PRODUCT ITEM TO ORDERS DB
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";
	$engraving="";
	$mirror		= addslashes($_SESSION['PrescrData']['MIRROR']);
	$tint_color = addslashes($_SESSION['PrescrData']['MIRROR']);
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Mirror";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category'";
	$result=mysql_query($query)	or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
		$ep_prod_id=$listItem[prod_id];
	
	if ($price > 0){
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	$result=mysql_query($query)		or die ( "Insert Mirror query failed: " . mysql_error() . "<br/>" . $query );
	}

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



function addCylOverRange($order_id){//ADD Cylinder Over Range
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
	$category="Cylinder Over Range";
	$extra_amount = 10;
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";

//echo '<br><br>'. $query . '<br><br>';
	$result=mysql_query($query) or die ( "Insert Dust Bar query failed: " . mysql_error() . "<br/>" . $query );
}





function addEdgingItemEntrepot($order_id){//ADD EDGING PRODUCT ITEM TO ORDERS DB
		
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
	$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);
	$temple_model_num=addslashes($_SESSION['PrescrData']['TEMPLE_MODEL']);
	$color=addslashes($_SESSION['PrescrData']['COLOR']);
	$order_type=addslashes($_SESSION['PrescrData']['ORDER_TYPE']);
	$temple=addslashes($_SESSION['PrescrData']['TEMPLE']);

	$order_num=$order_id;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Edging";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	$result=mysql_query($query)				or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	//Verifier si le produit commandé est un progressif
	$queryLensCategory = "SELECT lens_category FROM ifc_ca_exclusive WHERE primary_key = (select order_product_id from orders where primary_key = $order_id)";
	$resultLensCat     = mysql_query($queryLensCategory)		or die ( "Insert Edging query failed: " . mysql_error() . "<br/>" . $queryLensCategory );
	$DataLensCat 	   = mysql_fetch_array($resultLensCat);
	$LensCategory 	   = strtolower($DataLensCat[lens_category]);

	if ($LensCategory == 'sv'){
		$price = 0;//Les prix des SV incluent déja un frais de taillage, on en rajoute donc pas
	}elseif ($LensCategory == 'bifocal'){
		$price = 5;
	}else{
		$price = 6;//Si ce n'est ni un sv ni un bifocal, c'est un progressif
	}

	//if ($TheUserId='terrebonne')
	//echo '<br>Query: '. $queryLensCategory  . '<br>Lens Cat: '. $LensCategory ;
	
	/*switch(strtolower($supplier)){//Toutes ces collections sont recommandés automatiquement: donc pas de frais de taillage
		case 'sunoptic pk': 	$price = 0; 	break;
		case 'sunoptic k':  	$price = 0; 	break;
		case 'sunoptic ak':  	$price = 0;  	break;
		case 'sunoptic cp':  	$price = 0; 	break;
		case 'sunoptic massimo':$price = 0; 	break;
		case 'montana': 		$price = 0; 	break;
		case 'montana +': 		$price = 0; 	break;
		case 'sunoptic': 		$price = 0;		break;
		case 'armourx': 		$price = 0; 	break;
		case 'basic': 		 	$price = 0;		break;
		case 'classic': 		$price = 0; 	break;
		case 'metro': 		 	$price = 0;		break;
		case 'bugetti': 		$price = 0;		break;
		case 'modelli': 		$price = 0; 	break;
		case 'rendezvous': 		$price = 0; 	break;
	}*/
	
	$ep_prod_id=$listItem[prod_id];
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	
	if ($TheUserId='terrebonne')
	//echo '<br>'. $query;

	$result=mysql_query($query)		or die ( "Insert Edging query failed: " . mysql_error() . "<br/>" . $query );
}




function addEdgePolish($order_id){//ADD Edge Polish

	$ep_frame_a   = "";
	$ep_frame_b   = "";
	$ep_frame_ed  = "";
	$ep_frame_dbl = "";
	$frame_type   = "";
	$engraving    = "";
	$tint         = "";
	$tint_color   = "";
	$from_perc    = "";
	$to_perc      = "";
	$job_type     = "";
	$order_type   = "";
	$supplier     = "";
	$model        = "";
	$color        = "";
	$order_type   = "";
	$temple       = "";
	$order_num    = -1;
	$main_lab_id  = $_SESSION["sessionUserData"]["main_lab"];
	$category     = "Edge Polish";
    $price = 4;//4$ par paire

	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	
	
	//echo $query;

	$result=mysql_query($query)		or die ( "Insert Edge Polish query failed: " . mysql_error() . "<br/>" . $query );
}


?>