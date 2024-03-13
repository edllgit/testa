<?php

function updateIFCInventory($order_num)
{
//Préparation du email pour analyse
$message="";
$message="<html>
<head><style type='text/css'>
<!--
.TextSize {
font-size: 8pt;
font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>";

$sql	   = "SELECT ep_order_id, color, temple_model_num FROM extra_product_orders WHERE order_num='$order_num' AND category IN('Edging','Frame','Edging_Frame') AND order_type='Provide'";
$message  .= '<br><br>'.$sql; 
$result	   = mysql_query($sql)	or die  ('ERROR ' . mysql_error().' 1sql='.$sql);
$epItems   = mysql_fetch_assoc($result);
$itemCount = mysql_num_rows($result);
$DeletedFromInv = 'no';		

if ($itemCount > 0 ){//A FRAME HAS BEEN ORDERED
						
	//GET FRAME INFO
	$sql 		  = "SELECT * FROM ifc_frames_french WHERE color = '".$epItems['color']."' AND code='".$epItems['temple_model_num']."' OR color_en = '".$epItems['color']."' AND code='".$epItems['temple_model_num']."' ";
	$message     .= '<br><br>'.$sql; 
	$result		  = mysql_query($sql) or die  ('ERROR ' . mysql_error().' 2sql='.$sql);
	$frameItem	  = mysql_fetch_array($result);
	$LaCollection = $frameItem[misc_unknown_purpose];	
	$InventaireAMettreAJour = 21; //DLN Inventory will be updated
							
	//GET Actual STOCK of this product
	$sql		 = "SELECT product_inventory_id, inventory, order_total, min_inventory FROM product_inventory_ifc WHERE lab_id='$InventaireAMettreAJour' AND product_id='".$frameItem['ifc_frames_id']."'";
	$message    .= '<br>GET Actual STOCK of this product:<br>'.$sql; 
	$result      = mysql_query($sql)			or die  ('ERROR ' . mysql_error().' 3sql='.$sql);
	$nbrResultat = mysql_num_rows($result);
					
	if ($nbrResultat > 0)//SI > 0, le tuple existe dans product_inventory_ifc ON FAIT DONC UNE MISE A JOUR
	{
		$invItem         = mysql_fetch_array($result);			
		$new_inventory   = $invItem['inventory'] - 1;//Nouvelle valeur de l'inventaire dans le cas ou on a deja un tuple dans la database	
		/*if ($new_inventory == 0){//Si l'inventaire est mit a 0 pour ce produit..on envoie un email
			$message    .= '<br><b>Email de produit a 0 a envoyer ICI..</b><br>';
			//TODO ENVOYER LE EMAIL POUR VRAI A monture et rco.daniel
			Email_Inventory_at_0("dbeaulieu@direct-lens.com",$epItems['temple_model_num'],$frameItem[collection],$epItems['color'],$epItems['color_en'],$order_num);
			$queryDelete  = "DELETE FROM product_inventory_ifc WHERE lab_id = '$InventaireAMettreAJour' AND  product_inventory_id=".$invItem['product_inventory_id'];
			$resultDelete = mysql_query($queryDelete)	or die  ('ERROR ' . mysql_error().' 5sql='.$sql);
			$DeletedFromInv = 'yes';
		}*/
		
		
		if ($DeletedFromInv <> 'yes'){	
			$new_order_total = $invItem['order_total'] + 1;//Nombre de fois que ce produit a été commandé, on incrémente de 1			
			$message 	    .=  '<br><br>Inventory actuel:' . $invItem['inventory'];
			$message	    .=  '<br>Nouvel inventaire:'    . $new_inventory;			
		//MISE A JOUR DE L'INVENTAIRE
			$sql="UPDATE product_inventory_ifc SET order_total='".$new_order_total."',inventory='".$new_inventory."',sent=0 WHERE lab_id = '$InventaireAMettreAJour' AND  product_inventory_id='".$invItem['product_inventory_id']."'";
			$message.= '<br>REQUETE DE MISE A JOUR<br>'. $sql;
			$result=mysql_query($sql)	or die  ('ERROR ' . mysql_error().' 4sql='.$sql);
			
			if ($new_inventory < 1){//Si l'inventaire est mit a 0 pour ce produit..on envoie un email
			$message    .= '<br><b>Email de produit a 0 a envoyer ICI..</b><br>';
			//TODO ENVOYER LE EMAIL POUR VRAI A monture et rco.daniel
			Email_Inventory_at_0("dbeaulieu@direct-lens.com",$epItems['temple_model_num'],$frameItem[collection],$epItems['color'],$epItems['color_en'],$order_num);
			//$queryDelete  = "DELETE FROM product_inventory_ifc WHERE lab_id = '$InventaireAMettreAJour' AND  product_inventory_id=".$invItem['product_inventory_id'];
			//$resultDelete = mysql_query($queryDelete)	or die  ('ERROR ' . mysql_error().' 5sql='.$sql);
			$DeletedFromInv = 'yes';
			}
			
		}
		
		
		
		
		
	}else{
		
		//TODO ENVOYER UN EMAIL A CHARLES CE PRODUIT N'AURAIT PAS DU ETRE DISPONIBLE
		Email_Inventory_under_0("dbeaulieu@direct-lens.com",$epItems['temple_model_num'],$frameItem[collection],$epItems['color'],$epItems['color_en'],$order_num);
		$message    .= '<br><b>Email de produit EN NEGATIF POUR MOI/ADMIN a envoyer ICI..</b><br>';
		//ON INSERT LE TUPLE PUISQU'IL N'EXISTE PAS DÉJA, donc c'était comme a 0
		/*$message	    .=  '<br><br>ON INSERT LE TUPLE PUISQU\'IL N\'EXISTE PAS DÉJA:' ;	
		$new_inventory  = -1;//Nouvelle valeur de l'inventaire dans le cas ou on n'a pas déja un tuple dans la database
		$Prod_id        = $frameItem['ifc_frames_id'];
	    $sql 	 		= "INSERT INTO product_inventory_ifc (lab_id,product_id,inventory,order_total) VALUES ($InventaireAMettreAJour,$Prod_id, -1 ,1)";
		$message	   .= '<br>'. $sql;
		$result		    = mysql_query($sql)	or die  ('ERROR ' . mysql_error().' 4sql='.$sql);	*/
	}//Enf IF Existe deja dans l'inventaire
														
}//END IF itemCount
			

		//Envoie des résultats par email	
		$send_to_address = array('rapports@direct-lens.com');
		$curTime= date("m-d-Y");	
		$to_address=$send_to_address;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject='Mouvement d\'inventaire Ifc.ca (B) :'.$curTime;
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
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
			

}//Fin Fonction updateIFcInventory


function Email_Inventory_at_0($destinataire,$model,$collection, $color, $couleur, $order_num){	
	$message="";
	$message="<html>
	<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>
	<body>
	Collection: $collection<br>
	Model:$model<br>
	Color:$color $couleur<br>";
	$message .= "Ce modèle est désormais à 0 dans l'inventaire.
	<br> Commande: $order_num";
	//Envoie des résultats par email	
	$send_to_address=str_split($destinataire,150);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject='Avertissement: Modele de monture atteint 0 dans l\'inventaire:'.$curTime;
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}


function Email_Inventory_under_0($destinataire,$model,$collection, $color, $couleur,$order_num){	
	$message="";
	$message="<html>
	<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>
	<body>
	Collection: $collection<br>
	Model:$model<br>
	Color:$color $couleur<br>";
	$message .= "Ce modèle n'était pas en stock dans l'inventaire. Il a tout de même été commandé. <br>Numéro de Commande: $order_num ";
	//Envoie des résultats par email	
	$send_to_address=str_split($destinataire,150);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject='Avertissement: Modele de monture atteint 0 dans l\'inventaire:'.$curTime;
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}


function Automatic_ReOrder_OPB($order_num){//Si le client est l'entrepot ET  le frame est dans: Bugetti, RENDEZVOUS: On doit recommander immédiatement chez Optique Pierre Bouchard
	
		$queryEPO = "SELECT * FROM extra_product_orders WHERE supplier IN ('BUGETTI','RENDEZVOUS','CLIP SOLAIRES') AND order_num = $order_num AND category='Frame'";
		$resultEPO=mysql_query($queryEPO)			or die  ('ERROR ' . mysql_error().' 5sql='.$queryEPO);
		$nbrResultatEPO = mysql_num_rows($resultEPO);
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
		
		$message .= '<p>Client: 8404622</p>';
		$message .= '<p>Référence: L\'ENTREPOT DE LA LUNETTE</p>';
		$message .= '<p>Expédier à : <br>ATTN. Kelly Gawel
						DIRECTLAB ST. CATHARINES (Directlab STC)<br>
						325 WELLAND AVE 120, ST CATHARINES, ON<br>
						L2R 2R2<br><br>SVP ne pas expédier de facture avec les prix dans la boîte, seulement la liste des montures</p><br>';
						
		$message .= '<p>Facturer à :<br>
						DIRECT LAB  <br>
						240 RUE DES FORGES, LOCAL 203<br>
						TROIS-RIVIERES, QC<br>
						G9A 2G8</p>';
		
		$message.="<table width=\"650\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Commande</td>
				<td align=\"center\">Fournisseur</td>
				<td align=\"center\">Modèle</td>
				<td align=\"center\">Couleur</td>
				<td align=\"center\">Boxing</td>
				</tr>";
		
		if ($nbrResultatEPO>0){
		$envoyerEmail = 'oui';
		$DataEPO =mysql_fetch_assoc($resultEPO);
		
		$queryFrameMeasurements = "SELECT boxing FROM ifc_frames_french 
								WHERE model  = '$DataEPO[temple_model_num]' AND color    = '$DataEPO[color]' 
								OR    model  = '$DataEPO[temple_model_num]' AND color_en = '$DataEPO[color]'";
								
		$resultFrameMeasurements  = mysql_query($queryFrameMeasurements)	or die  ('ERROR ' . mysql_error().' 6sql='.$queryEPO);
		$NombreResuls             = mysql_num_rows($resultFrameMeasurements);
		if ($NombreResuls > 0)
		$DataFrameMeasurements	  = mysql_fetch_assoc($resultFrameMeasurements);
		
		$message.="	<tr bgcolor=\"$bgcolor\">
		    		<td align=\"center\">$order_num</td>";
        $message.=" <td align=\"center\">$DataEPO[supplier]</td>
					<td align=\"center\">$DataEPO[temple_model_num]</td>
					<td align=\"center\">$DataEPO[color]</td>
					<td align=\"center\">$DataFrameMeasurements[boxing]</td>";
        $message.=" </tr></table>";
		}	

		//Envoie des résultats par email	
		$send_to_address = array('rapports@direct-lens.com');
		//$send_to_address = array('rapports@direct-lens.com');
		$curTime= date("m-d-Y");	
		$to_address=$send_to_address;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject='Recommande automatique OPB :'.$curTime;
		
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
		

	
}//End function Automatic_ReOrder_OPB




function Automatic_ReOrder_Armourx($order_num){//Si le client est l'entrepot ET  le frame est dans: Bugetti, RENDEZVOUS: On doit recommander immédiatement chez Optique Pierre Bouchard

		$queryEPO = "SELECT * FROM extra_product_orders WHERE supplier in ('Basic','Metro','Wrap-Rx','Classic','ArmouRx') AND order_num = $order_num AND category='Frame'";
		$resultEPO=mysql_query($queryEPO)			or die  ('ERROR ' . mysql_error().' 5sql='.$queryEPO);
		$nbrResultatEPO = mysql_num_rows($resultEPO);
		$envoyerEmail = 'non';
		
		$queryFrameA  = "SELECT frame_a FROM orders WHERE order_num = " . $order_num;
		$resultFrameA = mysql_query($queryFrameA)			or die  ('ERROR ' . mysql_error().' sql='.$queryFrameA);
		$DataFrameA   = mysql_fetch_assoc($resultFrameA);
		$Frame_A      = $DataFrameA[frame_a];
		
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
                <td align=\"center\">Order #</td>
				<td align=\"center\">Supplier</td>
				<td align=\"center\">Model</td>
				<td align=\"center\">Color</td>
				<td align=\"center\">Frame A</td>
				</tr>";
		
		if ($nbrResultatEPO>0){
		$envoyerEmail = 'oui';
		$DataEPO =mysql_fetch_assoc($resultEPO);
		
		$queryFrameMeasurements = "SELECT boxing FROM ifc_frames_french 
								WHERE model  = '$DataEPO[temple_model_num]' AND color    = '$DataEPO[color]' 
								OR    model  = '$DataEPO[temple_model_num]' AND color_en = '$DataEPO[color]'";
								
		$resultFrameMeasurements  = mysql_query($queryFrameMeasurements)	or die  ('ERROR ' . mysql_error().' 6sql='.$queryEPO);
		$NombreResuls             = mysql_num_rows($resultFrameMeasurements);
		if ($NombreResuls > 0)
		$DataFrameMeasurements	  = mysql_fetch_assoc($resultFrameMeasurements);
		
		$message.="	<tr bgcolor=\"$bgcolor\">
		    		<td align=\"center\">$order_num</td>";
        $message.=" <td align=\"center\">$DataEPO[supplier]</td>
					<td align=\"center\">$DataEPO[temple_model_num]</td>
					<td align=\"center\">$DataEPO[color]</td>
					<td align=\"center\">$Frame_A</td>";
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
		
	
}//End function Automatic_ReOrder_ArmouRx

?>