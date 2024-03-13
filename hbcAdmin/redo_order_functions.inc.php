<?php 

function updateRedoOrder($pkey){//PRESCRIPTION ORDER UPDATE
include "../connexion_hbc.inc.php";
	$eye=$_POST['eye'];
	$order_product_id=$_POST['product_id'];
	$user_id=$_POST['user_id'];
	
$userQuery="SELECT * from accounts WHERE user_id='$user_id'";//GET ORDER INFO
$userResult=mysqli_query($con,$userQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$userItem=mysqli_fetch_array($userResult,MYSQLI_ASSOC);

switch($userItem[product_line]){
	case 'hbc':	$prodQuery = "SELECT * from ifc_ca_exclusive WHERE primary_key='$order_product_id'"; break;
	default :	$prodQuery = "SELECT * from exclusive        WHERE primary_key='$order_product_id'"; break;
}

$prodResult = mysqli_query($con,$prodQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$prodItem   = mysqli_fetch_array($prodResult,MYSQLI_ASSOC);
$order_product_name    = $prodItem[product_name]; 
$Lecoating  = $prodItem[coating]; 

//Always Canadian Currency for HBC	
$order_product_price=$prodItem[price_can];

	
if (($eye=="R.E.")||($eye=="L.E.")){
		$order_product_price=money_format('%.2n',$order_product_price/2);
	}
	
	$lab_id=$userItem['main_lab'];//GET Presciprtion Lab
	$labQuery="SELECT * FROM labs WHERE primary_key ='$lab_id'";
	$labResult=mysqli_query($con,$labQuery)		or die ("Could not find account");
	$labItem = mysqli_fetch_array($labResult,MYSQLI_ASSOC);
	
	$order_product_discount=$order_product_price;
	

	if ($prodItem[collection]=="Verres"){
		$order_product_discount=$order_product_discount-($order_product_price*$userItem["verres_dsc"]/100);
	}
	

	$order_product_coating = $Lecoating;
	$order_product_photo   = $_POST[order_product_photo];
	$order_product_polar   = $_POST[order_product_polar];
	$order_product_index   = $_POST[order_product_index];

	$RE_CT=$_POST[RE_CT];
	$LE_CT=$_POST[LE_CT];
	$RE_ET=$_POST[RE_ET];
	$LE_ET=$_POST[LE_ET];
	$order_patient_first= mysqli_escape_string($con,$_POST[order_patient_first]);
	$order_patient_last = mysqli_escape_string($con,$_POST[order_patient_last]);
	$patient_ref_num    = mysqli_escape_string($con,$_POST[patient_ref_num]);
	$salesperson_id=$_POST[salesperson_id];
	$order_quantity=$_POST[order_quantity];
	$frame_a=$_POST[frame_a];
	$frame_b=$_POST[frame_b];
	$frame_ed=$_POST[frame_ed];
	$frame_dbl=$_POST[frame_dbl];
	$frame_type=$_POST[frame_type];
	$tray_num  = mysqli_escape_string($con,$_POST[tray_num]);
	
	$redo_reason_id=$_POST[redo_reason_id];
	$redo_origin=$_POST[redo_origin];
	$re_sphere=$_POST[re_sphere];
	$re_cyl=$_POST[re_cyl];
	$re_axis=$_POST[re_axis];
	$re_add=$_POST[re_add];
	$re_pr_ax=$_POST[re_pr_ax];
	$re_pr_ax2=$_POST[re_pr_ax2];
	$re_pr_io=$_POST[RE_PR_IO];
	$re_pr_ud=$_POST[RE_PR_UD];
	$re_pd=$_POST[re_pd];
	$re_pd_near=$_POST[re_pd_near];
	$re_height=$_POST[re_height];
	
	$le_sphere 	 = $_POST[le_sphere];
	$le_cyl		 = $_POST[le_cyl];
	$le_axis 	 = $_POST[le_axis];
	$le_add	  	 = $_POST[le_add];
	$le_pr_ax	 = $_POST[le_pr_ax];
	$le_pr_ax2	 = $_POST[le_pr_ax2];
	$le_pr_io	 = $_POST[LE_PR_IO];
	$le_pr_ud	 = $_POST[LE_PR_UD];
	$le_pd	     = $_POST[le_pd];
	$le_pd_near  = $_POST[le_pd_near];
	$le_height	 = $_POST[le_height];
	$job_type    = $_POST[job_type];
	$redo_detail = $_POST[redo_detail];
	$PT			 = $_POST[PT];
	$PA			 = $_POST[PA];
	$vertex 	 = $_POST[vertex];
	
	$special_instructions = mysqli_escape_string($con,$_POST[special_instructions]);
	$internal_note		  = mysqli_escape_string($con,$_POST[internal_note]);
	$extra_product	  	  = mysqli_escape_string($con,$_POST[extra_product]);
	$extra_product_price  = $_POST[extra_product_price];
	$sphere_min			  = $prodItem[sphere_min];//CALCULATE OVER RANGE FEE
	$sphere_max			  = $prodItem[sphere_max];
	$cyl_min			  = $prodItem[cyl_min];


if ($_SESSION['RedoData']['myupload'] <> ''){
//Signifie qu'une shape a été uploadé, on doit la sauvegarder dans shape_bk_name et myupload
	$resultQueryShape = "UPDATE orders SET myupload = '" . $_SESSION['RedoData']['myupload']. "', shape_name_bk = '" . $_SESSION['RedoData']['myupload']. "' WHERE  primary_key=$pkey";
	//echo '<br>'. $resultQueryShape;
	$resultQueryShape = mysqli_query($con,$resultQueryShape)	or die ( "Query failed: " . mysqli_error($con));		
}
$_SESSION['RedoData']['myupload'] = '';//On vide ce champ

$queryOrderNum  = "SELECT order_num, order_product_index, order_product_photo, order_product_polar, user_id FROM orders WHERE primary_key =  $pkey";
$resultOrderNum = mysqli_query($con,$queryOrderNum)	or die ( "Query failed: " . mysqli_error($con));	
$DataOrderNum   = mysqli_fetch_array($resultOrderNum,MYSQLI_ASSOC);
$OrderNumber    = $DataOrderNum[order_num];
$USERID 	    = $DataOrderNum[user_id];	
$NouvelIndex    = $_POST[order_product_index];
$NouveauPhoto 	= $_POST[order_product_photo];
$NouvelPolar  	= $_POST[order_product_polar];


$query="UPDATE orders SET ";
if ($redo_reason_id <> 0){
	$query.="redo_reason_id='$redo_reason_id',";
}
$query.="order_patient_first='$order_patient_first',";
$query.="order_patient_last='$order_patient_last',";
$query.="salesperson_id='$salesperson_id',";
$query.="order_quantity='$order_quantity',";
$query.="frame_a='$frame_a',";
$query.="frame_b='$frame_b',";
$query.="frame_ed='$frame_ed',";
$query.="frame_dbl='$frame_dbl',";
$query.="frame_type='$frame_type',";
$query.="re_sphere='$re_sphere',";
$query.="re_cyl='$re_cyl',";
$query.="tray_num='$tray_num',";
$query.="re_axis='$re_axis',";
$query.="re_add='$re_add',";
$query.="re_pr_ax='$re_pr_ax',";
$query.="re_pr_ax2='$re_pr_ax2',";
$query.="re_pr_io='$re_pr_io',";
$query.="re_pr_ud='$re_pr_ud',";
$query.="re_pd='$re_pd',";
$query.="re_pd_near='$re_pd_near',";
$query.="re_height='$re_height',";
$query.="le_sphere='$le_sphere',";
$query.="le_cyl='$le_cyl',";
$query.="le_axis='$le_axis',";
$query.="le_add='$le_add',";
$query.="le_pr_ax='$le_pr_ax',";
$query.="le_pr_ax2='$le_pr_ax2',";
$query.="le_pr_io='$le_pr_io',";
$query.="le_pr_ud='$le_pr_ud',";
$query.="le_pd='$le_pd',";
$query.="le_pd_near='$le_pd_near',";
$query.="le_height='$le_height',";
$query.="PT='$PT',";
$query.="PA='$PA',";
$query.="redo_detail='$redo_detail',";
$query.="RE_CT='$RE_CT',";
$query.="LE_CT='$LE_CT',";
$query.="RE_ET='$RE_ET',";
$query.="LE_ET='$LE_ET',";
$query.="vertex='$vertex',";
$query.="special_instructions='$special_instructions',";
$query.="extra_product='$extra_product',";
$query.="extra_product_price='$extra_product_price',";
$query.="eye='$eye',";
$query.="order_product_name='$order_product_name',";
$query.="order_product_id='$order_product_id',";
$query.="order_product_index='$order_product_index',";
$query.="order_product_price='$order_product_price',";
$query.="order_product_discount='$order_product_discount',";
$query.="order_product_coating='$order_product_coating',";
$query.="order_product_photo='$order_product_photo',";
$query.="redo_origin='$redo_origin',";
$query.="order_product_polar='$order_product_polar'";



if ($redo_reason_id == 65){//Garantie a tout casser, on doit envoyer un email
	$internal_note = $internal_note . ' Ancien compte:' .  $USERID;
	$patient_ref_num =  'Ancien compte:' .  $USERID;;
	$query.=",patient_ref_num='$patient_ref_num'";
	$query.=",user_id='garantieatoutcasser',internal_note='$internal_note'";
}elseif ($redo_reason_id == 100 || $redo_reason_id == 101){//Garantie a tout casser v3, on doit envoyer un email
	$internal_note = $internal_note . ' Ancien compte:' .  $USERID;
	$patient_ref_num =  'Ancien compte:' .  $USERID;;
	$query.=",patient_ref_num='$patient_ref_num'";
	$query.=",user_id='garantieatoutcasser',internal_note='$internal_note'";
}else{
	$query.=",internal_note='$internal_note'";	
}

$query.=" WHERE primary_key=$pkey";	 




if ($redo_reason_id == 65){//Garantie a tout casser, on doit envoyer un email
	//Envoyer email a Kelly pour l'aviser du redo
			$message="";	
			$message="
			<html>
			<head>
			<meta charset=\"utf-8\">
			<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
			<!-- Bootstrap core CSS -->
			<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
			<!-- Custom styles for this template -->
			<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
			<!--[if lt IE 9]>
			<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
			<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
			<![endif]-->
			</head>
			<body>
			<h3>Une nouvelle reprise a été fait sous la Garantie à tout casser</h3><br>
			<p>Nom d'utilisateur:  <b>$USERID</b></p>
			<p># de Commande:  <b>$OrderNumber</b></p>
			<p>Merci de valider que cette reprise respecte les règles en place pour la GTC. <br><br>Si ce n'est pas le cas, elle devras être re-transféré dans le compte de la succursale. (La personne responsable de cet entrepot doit être avisé de la situation)</p>
			</body>
			</html>";
			
			$to_address = array('rapports@direct-lens.com');
			$from_address = 'donotreply@entrepotdelalunette.com';
			$subject      = "Nouveau cas GTC: $USERID:  $OrderNumber";
			$response     = office365_mail($to_address, $from_address, $subject, null, $message);	
}




$ValiderPassword = 'oui';

if ($NouvelIndex <> $DataOrderNum[order_product_index]){//Changement d'index, pas besoin de valider le mot de passe, on ne sauvegarde que l'index.
	$queryUpdateIndex  = "UPDATE ORDERS set order_product_index = '$NouvelIndex'  WHERE primary_key = $pkey";
	$NbrResultatRedoAccess  = 0;
	$resultUpdateIndex = mysqli_query($con,$queryUpdateIndex)	or die ( "Query failed: " . mysqli_error($con));	
	$ValiderPassword = 'non';
}elseif ($NouveauPhoto <> $DataOrderNum[order_product_photo]){//Changement de photochromic, pas besoin de valider le mot de passe, on ne sauvegarde que la couleur du photochromic
	$queryUpdateIndex  = "UPDATE ORDERS set order_product_photo = '$NouveauPhoto'  WHERE primary_key = $pkey";
	$NbrResultatRedoAccess  = 0;
	$resultUpdateIndex = mysqli_query($con,$queryUpdateIndex)	or die ( "Query failed: " . mysqli_error($con));	
	$ValiderPassword = 'non';
}elseif ($NouvelPolar <> $DataOrderNum[order_product_polar]){//Changement de polarizé, pas besoin de valider le mot de passe, on ne sauvegarde que la couleur du polarized
	$queryUpdateIndex  = "UPDATE ORDERS set order_product_polar = '$NouvelPolar'  WHERE primary_key = $pkey";
	$NbrResultatRedoAccess  = 0;
	$resultUpdateIndex = mysqli_query($con,$queryUpdateIndex)	or die ( "Query failed: " . mysqli_error($con));	
	$ValiderPassword = 'non';
}else{
	//Sinon on valide le mot de passe et on poursuit le traitement
	$RedoPassword = $_POST[redo_password];		
	//Vérifying Redo Password
	$queryRedoAccess  	    = "SELECT * FROM access_redo WHERE password='$RedoPassword'";
	$resultRedoAccess 	    = mysqli_query($con,$queryRedoAccess)	or die ( "Query failed: " . mysqli_error($con));	
	$NbrResultatRedoAccess  = mysqli_num_rows($resultRedoAccess);
	$ValiderPassword = 'oui';
}//END IF



if ($_POST[redo_password] <> ''){
	$RedoPassword = $_POST[redo_password];		
	//Vérifying Redo Password
	$queryRedoAccess  	    = "SELECT * FROM access_redo WHERE password='$RedoPassword'";
	//echo $queryRedoAccess;
	$resultRedoAccess 	    = mysqli_query($con,$queryRedoAccess)	or die ( "Query failed: " . mysqli_error($con));	
	$NbrResultatRedoAccess  = mysqli_num_rows($resultRedoAccess);
}

		
		
	if ($NbrResultatRedoAccess > 0 ){
		$DataRedoAccess =  mysqli_fetch_array($resultRedoAccess,MYSQLI_ASSOC);
		
		$todayDate = date("Y-m-d g:i a");// current date
		$currentTime = time($todayDate); //Change date into time
		$timeAfterOneHour = $currentTime-((60*60)*4);
		$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
		$ip 		  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		$update_ip2   = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		$result=mysqli_query($con,$query)	or die ( "Query failed: " . mysqli_error($con));//Sauvegarder le redo
		
		//Redo password is Valid.
		if (strlen($OrderNumber)==5){
			$queryUpdateStatus  = "UPDATE ORDERS set order_status = 'processing', prescript_lab = 21 WHERE order_num = $OrderNumber";
			//echo '<br>query:'. $queryUpdateStatus;
			$resultUpdateStatus = mysqli_query($con,$queryUpdateStatus)	or die ( "Query failed: " . mysqli_error($con));		
		}
		//Logger dans l'historique de status de cette commande qui a autorisé le redo
		$queryHistory = "INSERT INTO status_history (order_num,     update_time,     update_type, order_status,   update_ip,    update_ip2,     redo_approved_by) 
											 VALUES ($OrderNumber, '$datecomplete',   'manual',     'processing',               '$ip',       '$update_ip2',  '$DataRedoAccess[name]')";
		$result=mysqli_query($con,$queryHistory)	or die ( "Query failed: " . mysqli_error($con));	


		$queryRedoRebate  = "SELECT redo_rebate_detail, redo_reason_id, order_total, order_product_price,order_product_discount,extra_product, extra_product_price, eye, lab  FROM orders WHERE primary_key = $pkey";
		$ResultRedoRebate = mysqli_query($con,$queryRedoRebate)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataRedoRebate   = mysqli_fetch_array($ResultRedoRebate,MYSQLI_ASSOC);
		
		$CompteEntrepot = 0;
		if (($DataRedoRebate[lab]==66) || ($DataRedoRebate[lab]==67) || ($DataRedoRebate[lab]==59)){
			$CompteEntrepot = 1;
		}
		
		
		
		//Sauvegarde des données du redo		
		if (($_POST[model] <> '' ) || ($_POST[temple_model_num] <> '' )){//IF A MODEL IS SUPPLIED
		$query="UPDATE extra_product_orders SET 
		supplier 		 = '$_POST[supplier]',
		model 			 = '$_POST[model]',
		temple_model_num = '$_POST[temple_model_num]',
		color 			 = '$_POST[color]',
		ep_frame_a		 = '$frame_a',
		ep_frame_b		 = '$frame_b',
		ep_frame_ed		 = '$frame_ed',
		ep_frame_dbl	 = '$frame_dbl',
		frame_type		 = '$frame_type'
		WHERE category IN ('Frame','Edging')  AND order_id = $pkey";
		$result=mysqli_query($con,$query)		or die  ('I cannot delete items because: ' . mysqli_error($con));
		}
		
		
		$queryEdg  = "SELECT * from extra_product_orders WHERE order_id='$pkey' AND category='Edging'";//GET ORDER INFO
		$resultEdg = mysqli_query($con,$queryEdg) or die  ('I cannot select items because: ' . mysqli_error($con));
		$Edging_orders_count=mysqli_num_rows($resultEdg);
		if ($Edging_orders_count!= 0){
			$EdgItem=mysqli_fetch_array($resultEdg,MYSQLI_ASSOC);
		}//END IF ORDER COUNT
		
				
		if ($job_type == 'Uncut'){//Job non taillé, on doit effacer d'extra_product_orders les elements Frame et Edging
			$QueryDeleteEPO = "DELETE FROM extra_product_orders WHERE  order_id = $pkey AND category IN ('Frame','Edging')";
			$resultDeleteEPO=mysqli_query($con,$QueryDeleteEPO)		or die  ('I cannot delete items because: ' . mysqli_error($con));
		}
			
		if (($job_type == 'Edge and Mount') && ($Edging_orders_count > 0)){//Job taillé-monté, on doit effacer d'extra_product_orders l'élément Edging
			$QueryUpdateJobtype = "UPDATE extra_product_orders  SET job_type = '$job_type' WHERE  order_id = $pkey";
			$resultDeleteEPO=mysqli_query($con,$QueryUpdateJobtype)		or die  ('I cannot delete items because: ' . mysqli_error($con));
		}
			
		if (($job_type == 'remote edging') && ($Edging_orders_count > 0)){//Job taillé-monté, on doit effacer d'extra_product_orders l'élément Edging
			$QueryUpdateJobtype = "UPDATE extra_product_orders  SET job_type = '$job_type' WHERE  order_id = $pkey";
			$resultDeleteEPO=mysqli_query($con,$QueryUpdateJobtype)		or die  ('I cannot delete items because 5: ' . mysqli_error($con));
		}
			
		$Query="SELECT * from orders WHERE primary_key='$pkey'";//GET ORDER INFO
		$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Item=mysqli_fetch_array($Result,MYSQLI_ASSOC);
			
		if (($job_type == 'remote edging') && ($Edging_orders_count == 0)){//Job taillé-monté, on doit inserer puisque le tuple n'existe pas
			$QueryUpdateJobtype = "INSERT INTO  extra_product_orders (order_id, order_num, category,  job_type,  ep_frame_a, ep_frame_b,  ep_frame_ed, ep_frame_dbl,  frame_type) 
															VALUES ($pkey, $Item[order_num], 'Edging',  '$job_type','$frame_a', '$frame_b', '$frame_ed', '$frame_dbl', '$frame_type')";
			$resultDeleteEPO=mysqli_query($con,$QueryUpdateJobtype)		or die  ('I cannot delete items because 1: ' . mysqli_error($con));
		}
			
		if (($job_type == 'Edge and Mount') && ($Edging_orders_count == 0)){//Job taillé-monté, on doit inserer puisque le tuple n'existe pas
			$QueryUpdateJobtype = "INSERT INTO  extra_product_orders (order_id, order_num, category,  job_type,  ep_frame_a, ep_frame_b,  ep_frame_ed, ep_frame_dbl,  frame_type) 
															VALUES ($pkey, $Item[order_num], 'Edging',  '$job_type','$frame_a', '$frame_b', '$frame_ed', '$frame_dbl', '$frame_type')";
			$resultDeleteEPO=mysqli_query($con,$QueryUpdateJobtype)		or die  ('I cannot delete items because 2: ' . mysqli_error($con));
		}
					
			
		updateExtraProductsRedo($_POST,$userItem,$pkey);
					
		$gTotal=calculateTotal($Item[order_num]);
		addOrderTotal($Item[order_num],$gTotal);
			
		$Query  = "SELECT * FROM orders WHERE primary_key='$pkey'";//GET ORDER INFO
		$Result = mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Item   = mysqli_fetch_array($Result,MYSQLI_ASSOC);
			
				
		if ($Item[warranty] > 0){
									
			$newOrderTotal = $Item[order_product_price] + $WarrantyPrice;
				
			$queryTotal="UPDATE orders SET ";
			$queryTotal.="order_product_price= $newOrderTotal,";
			$queryTotal.="order_product_discount= $newOrderTotal";
			$queryTotal.=" WHERE primary_key=$pkey";	 
			$result     = mysqli_query($con,$queryTotal)	or die ( "Query failed: " . mysqli_error($con));	
		}
	
		$query="SELECT * from extra_product_orders WHERE order_id='$pkey' AND category='Tint'";//GET ORDER INFO
		//echo '<br>'. $query;
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ep_orders_count=mysqli_num_rows($result);
		if ($ep_orders_count!= 0){
			$tintItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
		}//END IF ORDER COUNT
			
		//echo '<br>POST TINT:'. $_POST['tint'];
		//echo '<br>$ep_orders_count: '. $ep_orders_count;
			
		if (($_POST['tint']=="None")&&($ep_orders_count!= 0)){
			$query="DELETE FROM extra_product_orders WHERE ep_order_id='$tintItem[ep_order_id]' ";
			//echo '<br>'. $query;
			$result=mysqli_query($con,$query)		or die  ('I cannot delete items because: ' . mysqli_error($con));
		}else if (($_POST['tint']!="None")&&($ep_orders_count!=0)){
			$query="UPDATE extra_product_orders SET tint='$_POST[tint]',from_perc='$_POST[from_perc]',tint_color='$_POST[tint_color]',to_perc='$_POST[to_perc]' WHERE ep_order_id='$tintItem[ep_order_id]' ";
			//echo '<br>'. $query;
			$result=mysqli_query($con,$query)		or die  ('I cannot UPDATE items because: ' . mysqli_error($con));
		}else if (($_POST['tint']!="None")&&($ep_orders_count==0)){
			addTintItem($_POST,$pkey,$userItem);
		}
		
		
		//EDGING SECTION;
		$query="SELECT * from extra_product_orders WHERE order_id='$pkey' AND category='Edging'";//GET ORDER INFO
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ep_orders_count=mysqli_num_rows($result);
		if ($ep_orders_count!= 0){
		$edgingItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
		}//END IF ORDER COUNT
			
		if (($data['job_type']=="Uncut")&&($ep_orders_count!= 0)){
			$query="DELETE FROM extra_product_orders WHERE ep_order_id='$edgingItem[ep_order_id]' ";
			$result=mysqli_query($con,$query) or die  ('I cannot delete items because: ' . mysqli_error($con));
		}else if (($data['job_type']!="Uncut")&&($ep_orders_count==0)){
			addEdgingItem($data,$order_id,$userItem);
		}
		

		
		echo '<div class="alert alert-success" role="alert"><strong>Redo Sucessfully Saved</strong></div>';
	
	
	}elseif($ValiderPassword == 'oui'){//End if Redo Password is Valid
		echo '<div align="center" class="alert alert-danger" role="alert"><strong>Error: Employee Password is invalid</strong></div>';
	}//End IF Redo Password is Valid

}//End Function



function updateExtraProductsRedo($data,$userItem,$pkey){
	include "../connexion_hbc.inc.php";
$order_id=$pkey;

//MIRROR SECTION; / NEW: 2018-07-09
$query    = "SELECT * from extra_product_orders WHERE order_id='$pkey' AND category='Mirror'";//GET ORDER INFO
$result   = mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ep_orders_count = mysqli_num_rows($result);
if ($ep_orders_count!= 0){
	$edgingItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
}//END IF ORDER COUNT


if (($data['mirror']=="None")&&($ep_orders_count!= 0)){//Signifie qu'un  mirroir EST présentement dans la commande et que le client veut enlever ce mirroir de la reprise: On doit effacer cet extra de extra_product_orders
	$query="DELETE FROM extra_product_orders WHERE ep_order_id='$edgingItem[ep_order_id]' ";
	$result=mysqli_query($con,$query) or die  ('I cannot delete items because: ' . mysqli_error($con));		
}else if (($ep_orders_count==0)&&($data['mirror']<>"None")){
	AddMirrorItem($data,$order_id,$userItem);//AddMirrorItem
}
	


	
}//END FUNCTION

function addPrismItem($data,$order_id,$userItem){//ADD PRISM PRODUCT ITEM TO ORDERS DB
include "../connexion_hbc.inc.php";
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
	
	$order_num=$data['order_num'];
	$main_lab_id=$userItem["main_lab"];
	$category="Prism";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
	$result=mysqli_query($con,$query)				or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($userItem["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($userItem["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($userItem["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Prism query failed: " . mysqli_error($con) );
	
	//UPDATE THE ORDER TOTAL TO INCLUDE THE TINT PRICE
	$queryOrderTotal  = "SELECT order_total FROM orders WHERE primary_key = $order_id";
	$resultOrderTotal = mysqli_query($con,$queryOrderTotal)		or die ( "Get order total query failed: " . mysqli_error($con) );
	$DataOrderTotal   = mysqli_fetch_array($resultOrderTotal,MYSQLI_ASSOC);
	$OLD_OrderTotal   = $DataOrderTotal[order_total];
	$NEW_OrderTotal   = $OLD_OrderTotal  + $price;
	
	//UPDATE NEW ORDER_TOTAL
	$queryNewOrderTotal = "UPDATE orders SET order_total = $NEW_OrderTotal  WHERE primary_key = $order_id ";
	echo $queryNewOrderTotal;
}
function addEngravingItem($data,$order_id,$userItem){//ADD ENGRAVING PRODUCT ITEM TO ORDERS DB
include "../connexion_hbc.inc.php";
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving=addslashes($data['engraving']);
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
	
	$order_num=$data['order_num'];
	$main_lab_id=$userItem["main_lab"];
	$category="Engraving";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' ";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($userItem["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($userItem["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($userItem["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query) or die ( "Insert Engraving query failed: " . mysqli_error($con) );
}
function addTintItem($data,$order_id){//ADD TINT PRODUCT ITEM TO ORDERS DB
include "../connexion_hbc.inc.php";

	$queryAccount  = "SELECT * FROM ACCOUNTS WHERE user_id = (SELECT USER_ID FROM ORDERS WHERE primary_key = $order_id)";
	$resultAccount = mysqli_query($con,$queryAccount) or die ( "Insert Engraving query failed: " . mysqli_error($con));
	$DataAccount   = mysqli_fetch_array($resultAccount,MYSQLI_ASSOC);
	
	$ep_frame_a="";
	$ep_frame_b="";
	$ep_frame_ed="";
	$ep_frame_dbl="";
	$frame_type="";

	$engraving="";
	$tint=addslashes($data['tint']);
	$tint_color=addslashes($data['tint_color']);
	$from_perc=addslashes($data['from_perc']);
	$to_perc=addslashes($data['to_perc']);
	$job_type="";
	$order_type="";
	$supplier="";
	$model="";
	$color="";
	$order_type="";
	$temple="";
	
	$order_num=$data['order_num'];
	$main_lab_id=$DataAccount["main_lab"];
	$category="Tint";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND tint='$tint' ";
	
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($DataAccount["currency"]=="US"){
		 $price=$listItem[price_us];}
	else if ($DataAccount["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($DataAccount["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Tint query failed: " . mysqli_error($con) );
	
	//UPDATE THE ORDER TOTAL TO INCLUDE THE TINT PRICE
	$queryOrderTotal  = "SELECT order_total FROM orders WHERE primary_key = $order_id";
	$resultOrderTotal = mysqli_query($con,$queryOrderTotal)		or die ( "Get order total query failed: " . mysqli_error($con) );
	$DataOrderTotal   = mysqli_fetch_array($resultOrderTotal,MYSQLI_ASSOC);
	$OLD_OrderTotal   = $DataOrderTotal[order_total];
	$NEW_OrderTotal   = $OLD_OrderTotal  + $price;
	
	//UPDATE NEW ORDER_TOTAL
	$queryNewOrderTotal = "UPDATE orders SET order_total = $NEW_OrderTotal  WHERE primary_key = $order_id ";
	$resultNewOrderTotal =mysqli_query($con,$queryNewOrderTotal)		or die ( "Insert Tint query failed: " . mysqli_error($con) );
}




function addEdgingItem($data,$order_id,$userItem){//ADD EDGING PRODUCT ITEM TO ORDERS DB
include "../connexion_hbc.inc.php";	
	$ep_frame_a=addslashes($data['frame_a']);
	$ep_frame_b=addslashes($data['frame_b']);
	$ep_frame_ed=addslashes($data['frame_ed']);
	$ep_frame_dbl=addslashes($data['frame_dbl']);
	$frame_type=addslashes($data['frame_type']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($data['job_type']);
	$order_type=addslashes($data['order_type']);
	$supplier=addslashes($data['supplier']);
	$frame_model=addslashes($data['model']);
	$temple_model_num=addslashes($data['temple_model_num']);
	$color=addslashes($data['color']);
	$order_type=addslashes($data['order_type']);
	$temple=addslashes($data['temple']);

	$order_num=$data['order_num'];
	$main_lab_id=$userItem["main_lab"];
	
	if (($frame_model!="")&&($order_type=="Provide"))//HAS FRAME
		$category="Edging_Frame";
	else
		$category="Edging";
			  
	$query="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	$result=mysqli_query($con,$query)			or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($userItem["currency"]=="US"){
		$price=$listItem[price_us];}
	else if ($userItem["currency"]=="CA"){
		$price=$listItem[price_can];}
	else if ($userItem["currency"]=="EUR"){
		$price=$listItem[price_eur];}
	$ep_prod_id=$listItem[prod_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	$result=mysqli_query($con,$query)		or die ( "Insert Edging query failed: " . mysqli_error($con));
}




function addMirrorItem($data,$order_id,$userItem){//ADD EDGING PRODUCT ITEM TO ORDERS DB
include "../connexion_hbc.inc.php";	
	//Variables qui doivent rester vides
	$engraving    	  = "";
	$tint        	  = "";
	$from_perc    	  = "";
	$to_perc     	  = "";
	$frame_type  	  = "";
	$job_type    	  = "";
	$supplier    	  = "";
	$temple_model_num = "";
	$color            = "";
	$order_type       = "";
	$temple           = "";
	$order_type       = "";
	
	//Variables importantes
	$tint_color   = $data['mirror'];
	$order_num    = $data['order_num'];
	$category     = "Mirror";
	$ep_frame_a   = addslashes($data['frame_a']);
	$ep_frame_b   = addslashes($data['frame_b']);
	$ep_frame_ed  = addslashes($data['frame_ed']);
	$ep_frame_dbl = addslashes($data['frame_dbl']);
	$frame_model  = addslashes($data['model']);
	$main_lab_id  = $userItem["main_lab"];
	
	//Aller chercher le prix de cet extra
	$query      = "SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) WHERE lab_id='$main_lab_id' AND category='$category' AND frame_type='$frame_type' ";
	//echo '<br>'.$query;
	$result     = mysqli_query($con,$query)			or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem   = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$price      = $listItem[price_can];
	$ep_prod_id = $listItem[prod_id];
	
	//Insérer l'extra dans la BD
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
	//echo '<br>'.$query;
	$result=mysqli_query($con,$query)		or die ( "Insert Edging query failed: " . mysqli_error($con));
	//echo '<br>Mirror added sucessfully';
}



function addFrameItem($data,$order_id,$userItem){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	include "../connexion_hbc.inc.php";
	$ep_frame_a=addslashes($data['frame_a']);
	$ep_frame_b=addslashes($data['frame_b']);
	$ep_frame_ed=addslashes($data['frame_ed']);
	$ep_frame_dbl=addslashes($data['frame_dbl']);
	$frame_type=addslashes($data['frame_type']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($data['job_type']);
	$order_type=addslashes($data['order_type']);
	$supplier=addslashes($data['supplier']);
	$frame_model=addslashes($data['model']);
	$temple_model_num=addslashes($data['temple_model_num']);
	$color=addslashes($data['color']);
	$order_type=addslashes($data['order_type']);
	$temple=addslashes($data['temple']);

	$order_num=$data['order_num'];
	$main_lab_id=$userItem["main_lab"];
	$category="Frame";
	
	$frame_model_num=addslashes($data['model']);
	$query="SELECT * FROM frames 
			LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
			WHERE model_num='$frame_model_num'";
	
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($userItem["currency"]=="US"){
		$price=$listItem[price_US];
		$indexString="US".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	else if ($userItem["currency"]=="CA"){
		$price=$listItem[price_CA];
		$indexString="CA".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	else if ($userItem["currency"]=="EUR"){
		$price=$listItem[price_EUR];
		$indexString="EUR".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	$ep_prod_id=$listItem[frame_id];
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,temple_model_num,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price,high_index_addition) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$frame_model','$temple_model_num','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price','$high_index_addition')";
	$result=mysqli_query($con,$query)		or die ( "Insert Frame query failed: " . mysqli_error($con)  );

}


function updateFrameItem($data,$order_id,$userItem,$ep_order_id){//ADD FRAME PRODUCT ITEM TO ORDERS DB
	include "../connexion_hbc.inc.php";
	
	$ep_frame_a=addslashes($data['frame_a']);
	$ep_frame_b=addslashes($data['frame_b']);
	$ep_frame_ed=addslashes($data['frame_ed']);
	$ep_frame_dbl=addslashes($data['frame_dbl']);
	$frame_type=addslashes($data['frame_type']);

	$engraving="";
	$tint="";
	$tint_color="";
	$from_perc="";
	$to_perc="";
	$job_type=addslashes($data['job_type']);
	$order_type=addslashes($data['order_type']);
	$supplier=addslashes($data['supplier']);
	$frame_model=addslashes($data['model']);
	$temple_model_num=addslashes($data['temple_model_num']);
	$color=addslashes($data['color']);
	$order_type=addslashes($data['order_type']);
	$temple=addslashes($data['temple']);

	$order_num=$data['order_num'];
	$main_lab_id=$userItem["main_lab"];
	$category="Frame";
	
	$frame_model_num=addslashes($data['model']);
	$query="SELECT * FROM frames 
			LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
			WHERE model_num='$frame_model_num'";

	
	$result=mysqli_query($con,$query)				or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($userItem["currency"]=="US"){
		$price=$listItem[price_US];
		$indexString="US".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	else if ($userItem["currency"]=="CA"){
		$price=$listItem[price_CA];
		$indexString="CA".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	else if ($userItem["currency"]=="EUR"){
		$price=$listItem[price_EUR];
		$indexString="EUR".str_replace(".","",$data[order_product_index]);
		$high_index_addition=$listItem[$indexString];
		$price=money_format('%.2n',$price);
		$high_index_addition=money_format('%.2n',$high_index_addition);
		}
	$ep_prod_id=$listItem[frame_id];
	
	$query="UPDATE extra_product_orders SET
	order_id='$order_id',
	order_num='$order_num',
	category='$category',
	engraving='$engraving',
	tint='$tint',
	tint_color='$tint_color',
	from_perc='$from_perc',
	to_perc='$to_perc',
	ep_prod_id='$ep_prod_id',
	frame_type='$frame_type',
	job_type='$job_type',
	supplier='$supplier',
	model='$frame_model',
	temple_model_num='$temple_model_num',
	color='$color',
	order_type='$order_type',
	ep_frame_a='$ep_frame_a',
	ep_frame_b='$ep_frame_b',
	ep_frame_ed='$ep_frame_ed',
	ep_frame_dbl='$ep_frame_dbl',
	temple='$temple',
	price='$price',
	high_index_addition='$high_index_addition'
	WHERE ep_order_id='$ep_order_id'";
	$result=mysqli_query($con,$query)		or die ( "Update Frame query failed: " . mysqli_error($con));
}

?>
