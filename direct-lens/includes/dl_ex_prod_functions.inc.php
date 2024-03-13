<?php
 
function addExtraProducts($order_id){//ADD EXTRA PRODUCT ITEM TO ORDERS DB
			include "../sec_connectEDLL.inc.php";
		
	//Gestion des extras programme Prestige
	if ($_SESSION["sessionUserData"]["product_line"] =='eye-recommend'){
		
		//Edging
		if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
			addEdgingItemPrestige($order_id);//Fonctionne
		}
		
		//Edge Polish
		if ($_SESSION['PrescrData']['EDGE_POLISH']=="yes"){
			addEdgePolishItemPrestige($order_id);
		}
		
		//High Addition		
		if (($_SESSION['PrescrData']['RE_ADD'] > 3.00) ||($_SESSION['PrescrData']['LE_ADD'] > 3.00)){
			addHighAddItemPrestige($order_id);//Fontionne
		}
		
		//Special Base Curve
		if (($_SESSION['PrescrData']['base_curve'] <> '') && ($_SESSION['PrescrData']['base_curve'] > 0)){
			//Add Special_Base_Curve
			addSpecialBaseCurvePrestige($order_id);//Fonctionne
		}
		
		//Tint (Solid, Gradient)
		if ($_SESSION['PrescrData']['TINT']!="None"){//Fontionne
			addTintItemPrestige($order_id);
		}
			
	
		//High Cylinder
		if (($_SESSION['PrescrData']['RE_CYL'] < -4.00) ||($_SESSION['PrescrData']['LE_CYL'] < -4.00)){
			addHighCylinderPrestige($order_id);
		}
	
		//Special Size
		if (($_SESSION['PrescrData']['FRAME_A'] > 59) || ($_SESSION['PrescrData']['FRAME_ED'] > 59)){
			
		    $queryStock  = "SELECT * FROM orders WHERE primary_key= $order_id AND order_product_name like '%stock%'";
			$resultStock = mysqli_query($con,$queryStock);
 			$CountStock  = mysqli_num_rows($resultStock);
			
			if ($CountStock < 1){
				addSpecialSizePrestige($order_id);
			}
		
		}
		
		
		//Prismes entre 0.1 et 4
		//If order contains BOTH EYES, we check to see if there is a prism in one of the eye
			if ($_SESSION['PrescrData']['EYE']=="Both"){
				if  (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX']< 4.01 )){
					addPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX']< 4.01 )){
					addPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX2']< 4.01 )){
					addPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX2']< 4.01 )){
					addPrismItemPrestige($order_id);
				}//End IF
			}

			//If order contains LEFT EYE ONLY, we check to see if there is a prism in the left eye
			if ($_SESSION['PrescrData']['EYE']=="L.E."){
				if       (($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX']< 4.01 )){
					addPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['LE_PR_AX2']< 4.01 )){
					addPrismItemPrestige($order_id);
				}//End IF
			}
			
			
			//If order contains RIGHT EYE ONLY, we check to see if there is a prism in the right eye
			if ($_SESSION['PrescrData']['EYE']=="R.E."){
				if      (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX']< 4.01 )){
					addPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0 ) && ($_SESSION['PrescrData']['RE_PR_AX2']< 4.01 )){
					addPrismItemPrestige($order_id);
				}//End IF
			}
			
			
		//Prismes 4 et +
		//If order contains BOTH EYES, we check to see if there is a prism in one of the eye
			if ($_SESSION['PrescrData']['EYE']=="Both"){
				if  (($_SESSION['PrescrData']['RE_PR_AX']!="")     && ($_SESSION['PrescrData']['RE_PR_AX'] > 4 )){
					addHighPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX']!="")  && ($_SESSION['PrescrData']['LE_PR_AX'] > 4)){
					addHighPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 4 )){
					addHighPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 4 )){
					addHighPrismItemPrestige($order_id);
				}//End IF
			}

			//If order contains LEFT EYE ONLY, we check to see if there is a prism in the left eye
			if ($_SESSION['PrescrData']['EYE']=="L.E."){
				if       (($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 4 )){
					addHighPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 4 )){
					addHighPrismItemPrestige($order_id);
				}//End IF
			}
			
			
			//If order contains RIGHT EYE ONLY, we check to see if there is a prism in the right eye
			if ($_SESSION['PrescrData']['EYE']=="R.E."){
				if      (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 4 )){
					addHighPrismItemPrestige($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 4 )){
					addHighPrismItemPrestige($order_id);
				}//End IF
			}
		
	}
	

	//Gestion des extras autre que Prestige
	if ($_SESSION["sessionUserData"]["product_line"] <> 'eye-recommend'){
		
			
			//Edge Polish
			if ($_SESSION['PrescrData']['EDGE_POLISH']=="yes"){
				addEdgePolishItem($order_id);
			}
			
			if ($_SESSION['PrescrData']['FRAME_MODEL']!=""){
				addFrameItem($order_id);
			}
						
			if ($_SESSION["sessionUserData"]["product_line"] == 'lensnetclub'){
				if (($_SESSION['PrescrData']['FRAME_A'] > 59) || ($_SESSION['PrescrData']['FRAME_ED'] > 59)){					
					addLargeFrameItem($order_id);//Fonction d'extra produit: Large Frame 8$
				}
			}
			
			if ($_SESSION['PrescrData']['ENGRAVING']!=""){
				addEngravingItem($order_id);
			}
				
				
			if ($_SESSION['PrescrData']['TINT']!="None"){
				addTintItem($order_id);
			}
				
					
			if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
				addEdgingItem($order_id);
			}
				
					
			if ($_SESSION['PrescrData']['base_curve']=="8"){
				addSpecialBase($order_id);
			}
			
				
		
			
			
			
		//Prismes
		//If order contains BOTH EYES, we check to see if there is a prism in one of the eye
			if ($_SESSION['PrescrData']['EYE']=="Both"){
				if     (($_SESSION['PrescrData']['RE_PR_AX']!="")  && ($_SESSION['PrescrData']['RE_PR_AX']> 0)){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX']!="")  && ($_SESSION['PrescrData']['LE_PR_AX']> 0)){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0)){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0)){
					addPrismItem($order_id);
				}//End IF
			}

			//If order contains LEFT EYE ONLY, we check to see if there is a prism in the left eye
			if ($_SESSION['PrescrData']['EYE']=="L.E."){
				if       (($_SESSION['PrescrData']['LE_PR_AX']!="") && ($_SESSION['PrescrData']['LE_PR_AX']> 0 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['LE_PR_AX2']!="") && ($_SESSION['PrescrData']['LE_PR_AX2']> 0 )){
					addPrismItem($order_id);
				}//End IF
			}
			
			
			//If order contains RIGHT EYE ONLY, we check to see if there is a prism in the right eye
			if ($_SESSION['PrescrData']['EYE']=="R.E."){
				if      (($_SESSION['PrescrData']['RE_PR_AX']!="") && ($_SESSION['PrescrData']['RE_PR_AX']> 0 )){
					addPrismItem($order_id);
				}elseif(($_SESSION['PrescrData']['RE_PR_AX2']!="") && ($_SESSION['PrescrData']['RE_PR_AX2']> 0 )){
					addPrismItem($order_id);
				}//End IF
			}
			
			
			
			
			
			
				
	}//End if Product line IS NOT Eye Recommend/Prestige
	
	
}//End Function
	
	
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
			  
	$query="SELECT * FROM extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
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

	$result=mysqli_query($con,$query)		or die ( "Insert Prism query failed: " . mysqli_error($con) . "<br/>" . $query );
}



function addPrismItemPrestige($order_id){//ADD PRISM PRODUCT ITEM TO ORDERS DB (Prestige customers)
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
			  
	switch($_SESSION["sessionUserData"]["prestige_level"]){
			case 'high':   $price =  5.50;   	break;	
			case 'medium': $price =  7.50;   	break;	
			case 'low':    $price =  9.00;   	break;	
			case 'none':    $price = 10.00;   	break;	
	}
	
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Prism query failed: " . mysqli_error($con) . "<br/>" . $query );
}



function addHighPrismItemPrestige($order_id){//ADD PRISM PRODUCT ITEM TO ORDERS DB (Prestige customers)
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
			  
	switch($_SESSION["sessionUserData"]["prestige_level"]){
			case 'high':   $price =   8.25;   	break;	
			case 'medium': $price =  11.25;   	break;	
			case 'low':    $price =  13.50;   	break;	
			case 'none':    $price = 15.00;   	break;	
	}
	
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Prism query failed: " . mysqli_error($con) . "<br/>" . $query );
}





function addSpecialSizePrestige($order_id){//ADD Top urgent
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
	$category="Special Size";
			  
	switch($_SESSION["sessionUserData"]["prestige_level"]){
			case 'high':   $price =  13.75;   	break;	
			case 'medium': $price =  18.75;   	break;	
			case 'low':    $price =  22.50;   	break;
			case 'none':   $price =  25.00;   	break;		
	}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert top urgent query failed: " . mysqli_error($con) . "<br/>" . $query );
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

	$result=mysqli_query($con,$query)		or die ( "Insert top urgent query failed: " . mysqli_error($con) . "<br/>" . $query );
}





function addEngravingItem($order_id){//ADD ENGRAVING PRODUCT ITEM TO ORDERS DB
	include "../sec_connectEDLL.inc.php";
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
			  
	$query="SELECT * FROM extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
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


function addTintItemPrestige($order_id){//ADD TINT PRODUCT ITEM TO ORDERS DB
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
	
	if (strtolower($tint)=='gradient'){ 	//teinte dégradé	  
		switch($_SESSION["sessionUserData"]["prestige_level"]){
			case 'high':   $price =  11.00;   	break;	
			case 'medium': $price =  15.00;   	break;	
			case 'low':    $price =  18.00;   	break;
			case 'none':    $price = 20.00;   	break;	
		}
	}elseif (strtolower($tint)=='solid'){ //Teinte Unie
		switch($_SESSION["sessionUserData"]["prestige_level"]){
			case 'high':   $price =   8.25;   	break;	
			case 'medium': $price =  11.25;   	break;	
			case 'low':    $price =  13.50;   	break;	
			case 'none':   $price =  15.00;   	break;	
		}
	}
	
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	
	$queryProductLine  = 'SELECT product_line, prestige_level FROM accounts WHERE user_id= \''. $_SESSION["sessionUser_Id"]. "'";
	$resultProductLine = mysqli_query($con,$queryProductLine)	or die  ('I cannot select items because 11: ' . mysqli_error($con) . $queryProductLine);
	$DataProductLine   = mysqli_fetch_array($resultProductLine,MYSQLI_ASSOC);
	$ProductLine	   = $DataProductLine[product_line];
	$Prestige_Level    = $DataProductLine[prestige_level];
	
	
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
	
		$Eyelation='no';
		if ($_SESSION["sessionUser_Id"]=='eyelationcan')
		$Eyelation='yes';
		if ($_SESSION["sessionUser_Id"]=='eyelationnet')
		$Eyelation='yes';
		
		
		$Eyelation_frame_type='no';
		if ($frame_type=='Nylon Groove')
		$Eyelation_frame_type='yes';
		if ($frame_type=='Metal Groove')
		$Eyelation_frame_type='yes';
		
		if (($Eyelation_frame_type=='yes')&& ($Eyelation=='yes'))
			$price=10.00;
		else
			$price=$listItem[price_us];
			$price=money_format('%.2n',$price);		
		}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$queryFreeEdging  = "SELECT free_edging FROM accounts WHERE user_id = '".$_SESSION["sessionUser_Id"]."'";
	$resultFreeEdging = mysqli_query($con,$queryFreeEdging)		or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $queryFreeEdging );
	$DataFreeEdging   = mysqli_fetch_array($resultFreeEdging,MYSQLI_ASSOC);
	if ($DataFreeEdging[free_edging] =='yes'){
		$FreeEdging = true;
	}else{
		$FreeEdging = false;
	}
	
	if ($FreeEdging == false){
		$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	$result=mysqli_query($con,$query) or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $query );
	}else{
		$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','0')";
		$result=mysqli_query($con,$query) or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $query );
	}
		
	
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$price=$listItem[price_US];}
	else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$price=$listItem[price_CA];}
	else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$price=$listItem[price_EUR];}
	$ep_prod_id=$listItem[frame_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price,high_index_addition) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price','$high_index_addition')";

	$result=mysqli_query($con,$query) or die ( "Insert Frame query failed: " . mysqli_error($con) . "<br/>" . $query );
}



function addSpecialBase($order_id){//ADD Special Base
	include "../sec_connectEDLL.inc.php";
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
	$category="Special_Base";
			  
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
	
	$result=mysqli_query($con,$query)	or die ( "Insert Special Base query failed: " . mysqli_error($con) . "<br/>" . $query );
}




function addEdgePolishItemPrestige($order_id){//ADD Edge Polish
	include "../sec_connectEDLL.inc.php";
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
	$frame_type="Edge Polish";
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Edge Polish";

	switch($_SESSION["sessionUserData"]["prestige_level"]){
		case 'high':   $price =  5.00;   	break;	//Attente du vrai prix
		case 'medium': $price =  5.00;   	break;	//Attente du vrai prix
		case 'low':    $price =  5.00;   	break;	//Attente du vrai prix
		case 'none':   $price =  5.00;   	break;	//Attente du vrai prix
	}
		
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	$result=mysqli_query($con,$query)	or die ( "Insert Special Base query failed: " . mysqli_error($con) . "<br/>" . $query );
}



function addEdgePolishItem($order_id){//ADD Edge Polish
include "../sec_connectEDLL.inc.php";
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
	$frame_type="Edge Polish";
	$order_num=-1;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$category="Edge Polish";
    $price =  5.00; //Attente du vrai prix
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	$result=mysqli_query($con,$query)	or die ( "Insert Special Base query failed: " . mysqli_error($con) . "<br/>" . $query );
}





function addSpecialBaseCurvePrestige($order_id){//ADD Special Base
	include "../sec_connectEDLL.inc.php";
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
	$category="Special_Base";

	switch($_SESSION["sessionUserData"]["prestige_level"]){
		case 'high':   $price =  11.00;   	break;	
		case 'medium': $price =  15.00;   	break;	
		case 'low':    $price =  18.00;   	break;	
		case 'none':   $price =  20.00;   	break;	
	}
		
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	
	$result=mysqli_query($con,$query)	or die ( "Insert Special Base query failed: " . mysqli_error($con) . "<br/>" . $query );
}






function addLargeFrameItem($order_id){//ADD Large frames fee  ITEM TO ORDERS DB
	include "../sec_connectEDLL.inc.php";
	$ep_frame_a   = $_SESSION["PrescrData"]["FRAME_A"];
	$ep_frame_b   = $_SESSION["PrescrData"]["FRAME_B"];
	$ep_frame_ed  = $_SESSION["PrescrData"]["FRAME_ED"];
	$ep_frame_dbl = $_SESSION["PrescrData"]["FRAME_DBL"];
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
	$category="Large frame";
			  
	$price=8;//Hard code the price of this extra

	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl', '$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Large Frame fee failed: " . mysqli_error($con) . "<br/>" . $query );
}




function addHighCylinderPrestige($order_id){//Add Fees per pair when at least one of the eye is with a  high Cylinder (under -4.00) Only for Prestige Program Customers
	include "../sec_connectEDLL.inc.php";
	
	$ep_frame_a   = $_SESSION["PrescrData"]["FRAME_A"];
	$ep_frame_b   = $_SESSION["PrescrData"]["FRAME_B"];
	$ep_frame_ed  = $_SESSION["PrescrData"]["FRAME_ED"];
	$ep_frame_dbl = $_SESSION["PrescrData"]["FRAME_DBL"];
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
	$category="High Cylinder";
		
	switch($_SESSION["sessionUserData"]["prestige_level"]){
		case 'high':   $price =  5.50;   	break;	
		case 'medium': $price =  7.50;   	break;	
		case 'low':    $price =  9.00;   	break;	
		case 'none':   $price = 10.00;   	break;
	}

	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl', '$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Large Frame fee failed: " . mysqli_error($con) . "<br/>" . $query );
}






function addHighAddItemPrestige($order_id){//Add Fees per pair when at least one of the eye is with a  high addition (higher than 3.00) Only for Prestige Program Customers
	include "../sec_connectEDLL.inc.php";
	$ep_frame_a   = $_SESSION["PrescrData"]["FRAME_A"];
	$ep_frame_b   = $_SESSION["PrescrData"]["FRAME_B"];
	$ep_frame_ed  = $_SESSION["PrescrData"]["FRAME_ED"];
	$ep_frame_dbl = $_SESSION["PrescrData"]["FRAME_DBL"];
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
	$category="High Addition";
		
	switch($_SESSION["sessionUserData"]["prestige_level"]){
		case 'high':   $price =  11.00;   	break;	
		case 'medium': $price =  15.00;   	break;	
		case 'low':    $price =  18.00;   	break;	
		case 'none':   $price =  20.00;   	break;	
	}

	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl', '$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert Large Frame fee failed: " . mysqli_error($con) . "<br/>" . $query );
}






function addEdgingItemPrestige($order_id){//ADD EDGING PRODUCT ITEM TO ORDERS DB
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

	$order_num=$order_id;
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	
	if (($frame_model!="")&&($order_type=="Provide"))//HAS FRAME
		$category="Edging_Frame";
	else
		$category="Edging";
				  
	$query="SELECT * FROM extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	
		//Prix Spéciaux Prestige (Brian Mcbride)
		 if ($frame_type=='Nylon Groove'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =  11.00;   	break;	
				case 'medium': $price =  15.00;   	break;	
				case 'low':    $price =  18.00;   	break;
				case 'none':   $price =  20.00;   	break;	
			}	
		}elseif ($frame_type=='Metal Groove'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =  13.75;   	break;	
				case 'medium': $price =  18.75;   	break;	
				case 'low':    $price =  22.50;   	break;
				case 'none':   $price =  25.00;   	break;	
			}	
		}elseif ($frame_type=='Metal'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =   8.80;   	break;	
				case 'medium': $price =  12.00;   	break;	
				case 'low':    $price =  14.40;   	break;	
				case 'none':   $price =  16.00;   	break;	
			}	
		}elseif ($frame_type=='Plastic'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =   8.80;   	break;	
				case 'medium': $price =  12.00;   	break;	
				case 'low':    $price =  14.40;   	break;
				case 'none':   $price =  16.00;   	break;		
			}	
		}elseif ($frame_type=='Drill and Notch'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =  22.00;   	break;	
				case 'medium': $price =  30.00;   	break;	
				case 'low':    $price =  36.00;   	break;
				case 'none':   $price =  40.00;   	break;		
			}
		}elseif ($frame_type=='Edge Polish'){
			switch($_SESSION["sessionUserData"]["prestige_level"]){
				case 'high':   $price =  5.00;   	break;	//ATTENTE DU PRIX BRIAN MCBRIDE
				case 'medium': $price =  5.00;   	break;	//ATTENTE DU PRIX BRIAN MCBRIDE
				case 'low':    $price =  5.00;   	break;	//ATTENTE DU PRIX BRIAN MCBRIDE
				case 'none':   $price =  5.00;   	break;	
			}	
		}//End IF
		$price=money_format('%.2n',$price);	
		

	$queryFreeEdging  = "SELECT free_edging FROM accounts WHERE user_id = '".$_SESSION["sessionUser_Id"]."'";
	$resultFreeEdging = mysqli_query($con,$queryFreeEdging)		or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $queryFreeEdging );
	$DataFreeEdging   = mysqli_fetch_array($resultFreeEdging,MYSQLI_ASSOC);
	if ($DataFreeEdging[free_edging] =='yes'){
		$FreeEdging = true;
	}else{
		$FreeEdging = false;
	}
	
	if ($FreeEdging == false){
		$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $query );
	}else{
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple',0)";

	$result=mysqli_query($con,$query)		or die ( "Insert Edging query failed: " . mysqli_error($con) . "<br/>" . $query );	
	}

		
	
}


?>
