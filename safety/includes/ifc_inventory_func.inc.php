<?php

function updateIFCInventory($order_num)
{
	include "../sec_connectEDLL.inc.php";
	$sql="SELECT ep_order_id, color, temple_model_num FROM extra_product_orders WHERE order_num='$order_num' AND category='Frame' AND order_type='Provide'";
	$result=mysqli_query($con,$sql) or die  ('ERROR ' . mysqli_error($con).' sql='.$sql);
	$epItems=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$itemCount=mysqli_num_rows($result);
	
	if ($itemCount>0){//A FRAME HAS BEEN ORDERED
		
		//GET MAIN LAB INFO
	
		$qstring = "SELECT lab, order_quantity, order_product_id FROM orders WHERE order_num='".$order_num."'";
		$result=mysqli_query($qstring) or die  ('ERROR ' . mysqli_error($con));
		$orderItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
			

	
		//GET FRAME INFO
	
		$sql= "SELECT * FROM ifc_frames_french WHERE color_en = '".$epItems['color']."' AND code='".$epItems['temple_model_num']."'";
		$result=mysqli_query($con,$sql)			or die  ('ERROR ' . mysqli_error($con).' sql='.$sql);
		$frameItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
			

	
	  //For IFC.ca we need to check the product bought was in which collection
	  $queryProduct = "SELECT collection from  ifc_ca_exclusive WHERE  primary_key = $orderItem[order_product_id]";
	  $resultProduct=mysqli_query($con,$queryProduct)	or die  ('ERROR ' . mysqli_error($con).' sql='.$queryProduct);
	  $DataItem=mysqli_fetch_array($resultProduct,MYSQLI_ASSOC);
	  
	  
	  switch($DataItem['collection']){
		case 'IFC CA Younger Dr':  $labtoUpdate = 22; break;
		case 'IFC CA Express Dr':  $labtoUpdate = 22; break;
		case 'IFC CA Younger Sct': $labtoUpdate = 3;  break;
		case 'IFC CA Express Sct': $labtoUpdate = 3;  break;
		case 'IFC CA Free': 	   $labtoUpdate = 37; break;	
		case 'NURBS sunglasses':   $labtoUpdate = 0;  break;							
	  }
	
		if ($labtoUpdate <> 0)
		{
		
			//GET PRODUCT INVENTORY INFO
			$sql="SELECT product_inventory_id, inventory, order_total, min_inventory from product_inventory_ifc where lab_id='".$labtoUpdate."' and product_id='".$frameItem['ifc_frames_id']."'";
			$result=mysqli_query($con,$sql) or die  ('ERROR ' . mysqli_error($con).' sql='.$sql);
			$invItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
				
			$new_inventory=$invItem['inventory']-$orderItem['order_quantity'];
			$new_order_total=$invItem['order_total']+$orderItem['order_quantity'];
			
			//UPDATE IFC FRAME INVENTORY
			$sql = "UPDATE product_inventory_ifc SET order_total='".$new_order_total."', inventory='".$new_inventory."', sent=0 WHERE lab_id = $labtoUpdate AND  product_inventory_id='".$invItem['product_inventory_id']."'";
			$result=mysqli_query($con,$sql) or die  ('ERROR ' . mysqli_error($con).' sql='.$sql);
									
		}//End if  $labtoUpdate <> 0						
								
	}//END IF itemCount
	
}


function Automatic_ReOrder_Armourx($order_num){//Si le client est l'entrepot ET  le frame est dans: Bugetti, RENDEZVOUS: On doit recommander immédiatement chez Optique Pierre Bouchard
	include "../sec_connectEDLL.inc.php";
	
	$CompteEntrepot = 'oui';
	/*
	switch($_SESSION["sessionUser_Id"]){
		case 'warehousehalsafe':  	$CompteEntrepot = 'oui';  break;
		case 'lavalsafe':  			$CompteEntrepot = 'oui';  break;	
		case 'levissafe':        	$CompteEntrepot = 'oui';  break;	
		case 'safedr':  			$CompteEntrepot = 'oui';  break;	
		case 'entrepotsafe':  		$CompteEntrepot = 'oui';  break;	
		case 'terrebonnesafe':  	$CompteEntrepot = 'oui';  break;
		case 'sherbrookesafe':  	$CompteEntrepot = 'oui';  break;	
		case 'chicoutimisafe':  	$CompteEntrepot = 'oui';  break;
		case 'longueuilsafe':  		$CompteEntrepot = 'oui';  break;			
	}*/
	

	
	if ($CompteEntrepot  =='oui')//Si la commande provient d'un entrepot
	{
		$queryEPO = "SELECT * FROM extra_product_orders WHERE supplier in ('Basic','Metro','Wrap-Rx','Classic','ArmouRx') AND order_num = $order_num AND category='Frame'";
		$resultEPO=mysqli_query($con,$queryEPO) or die  ('ERROR ' . mysqli_error($con).' 5sql='.$queryEPO);
		$nbrResultatEPO = mysqli_num_rows($resultEPO);
		$envoyerEmail = 'non';
		
		//Préparation du email pour analyse
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
		
		$message.="<table width=\"650\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Commande</td>
				<td align=\"center\">Supplier</td>
				<td align=\"center\">Model</td>
				<td align=\"center\">Color</td>
				<td align=\"center\">Frame A</td>
				<td align=\"center\">Extra</td>
				</tr>";
		
		if ($nbrResultatEPO>0){
		$envoyerEmail = 'oui';
		$DataEPO =mysqli_fetch_array($resultEPO,MYSQLI_ASSOC);
		
		$queryFrameMeasurements = "SELECT boxing FROM ifc_frames_french 
								WHERE model  = '$DataEPO[temple_model_num]' AND color    = '$DataEPO[color]' 
								OR    model  = '$DataEPO[temple_model_num]' AND color_en = '$DataEPO[color]'";
								
								
		$Extra = '';	
		
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a, color FROM extra_product_orders WHERE order_num = $order_num AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
		
		$queryRemSideShield  = "SELECT * FROM extra_product_orders WHERE order_num = $order_num  AND category in ('Removable Side Shield')";
		$resultRemSideShield = mysqli_query($con,$queryRemSideShield)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatSideShield = mysqli_num_rows($resultRemSideShield );
		if ($NbrResultatSideShield  > 0){
			$queryRemSideShieldDetail  = "SELECT  distinct  removable_side_shield_ID  FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultRemSideShieldDetail = mysqli_query($con,$queryRemSideShieldDetail) or die  ('I : ' . mysqli_error($con));
			$DataRemoveableSideShield   = mysqli_fetch_array($resultRemSideShieldDetail,MYSQLI_ASSOC);
			$Extra .= ' Removable Side Shield  ' . $DataRemoveableSideShield[removable_side_shield_ID]  ;
		}
		
		//Cushion
		$queryCushion  = "SELECT * FROM extra_product_orders WHERE order_num = $order_num  AND category in ('Cushion')";
		$resultCushion= mysqli_query($con,$queryCushion) or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatCushion = mysqli_num_rows($resultCushion );
		if ($NbrResultatCushion  > 0) {
			$queryCushionDetail  = "SELECT  distinct cushion_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultCushionDetail = mysqli_query($con,$queryCushionDetail)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataCushionDetail   = mysqli_fetch_array($resultCushionDetail,MYSQLI_ASSOC);
			$Extra .= ' Cushion ' . $DataCushionDetail[cushion_ID]  ;
		}
		
		//Dust bar
		$queryDustBar  = "SELECT * FROM extra_product_orders WHERE order_num = $order_num AND category in ('Dust Bar')";
		$resultDustBar= mysqli_query($con,$queryDustBar)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatDustBar = mysqli_num_rows($resultDustBar);
		if ($NbrResultatDustBar  > 0) {
			$queryDustBarDetail  = "SELECT  distinct dust_bar_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultDustBarDetail = mysqli_query($con,$queryDustBarDetail)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataDustBarDetail   = mysqli_fetch_array($resultDustBarDetail,MYSQLI_ASSOC);
			$Extra .= ' Dust Bar ' . $DataDustBarDetail[dust_bar_ID]  ;
		}	
		
		$queryFrameA 	 = "SELECT ep_frame_a FROM extra_product_orders WHERE order_num = $order_num";
		$resultFrameA 	 = mysqli_query($con,$queryFrameA)		or die  ('I cannot select items because: ' . mysqli_error($con));				
		$DataFrameA	   	 = mysqli_fetch_array($resultFrameA,MYSQLI_ASSOC);	
		$Frame_A         = $DataFrameA[ep_frame_a];   					
					
								
		$resultFrameMeasurements  = mysqli_query($con,$queryFrameMeasurements)	or die  ('ERROR ' . mysqli_error($con).' 6sql='.$queryEPO);
		$NombreResuls             = mysqli_num_rows($resultFrameMeasurements);
		if ($NombreResuls > 0)
		$DataFrameMeasurements	  = mysqli_fetch_array($resultFrameMeasurements,MYSQLI_ASSOC);
		
		$message.="	<tr bgcolor=\"$bgcolor\">
		    		<td align=\"center\">$order_num</td>";
        $message.=" <td align=\"center\">$DataEPO[supplier]</td>
					<td align=\"center\">$DataEPO[temple_model_num]</td>
					<td align=\"center\">$DataEPO[color]</td>
					<td align=\"center\">$Frame_A</td>
					<td align=\"center\">$Extra</td>";
        $message.=" </tr></table>";
		}	

		//Envoie des résultats par email	
		//$send_to_address = array('rapports@direct-lens.com');
		$send_to_address = array('rapports@direct-lens.com');
		$curTime= date("m-d-Y");	
		$to_address=$send_to_address;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject='Entrepot de la lunette ArmouRx Frame order: '.$curTime;
		
		if($envoyerEmail == 'oui'){
			$response=office365_mail($to_address, $from_address, $subject, null, $message);
		}
		
		//Log email
		$compteur = 0;
		foreach($to_address as $key => $value)
		{
			if ($compteur == 0 )
				$EmailEnvoyerA = $value;
			else
				$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
			$compteur += 1;	
		}
		

	}//End if account=entrepotifc
	
}//End function Automatic_ReOrder_ArmouRx



?>