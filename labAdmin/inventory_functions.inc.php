<?php
//A SUPPRIMER
include "../Connections/directlens.php";
//Fonction de mise a jour de l'inventaire Version 3.0 : Achat de MONTURES SEULEMENT.
function DecreaseInventory($ordernum){
	//Préparation du email pour analyse
		$message="";
		$message="<html><head><style type='text/css'>
		<!--
		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head><body>";


			$queryOrder  = "SELECT * FROM orders WHERE order_num = ".$ordernum;
			$message    .= '<br><br>'.$queryOrder; 
			$resultOrder = mysql_query($queryOrder)	or die ("Erreur inventaire 1".mysql_error());	
		
			while ($DataOrder = mysql_fetch_array($resultOrder)){
				$Product_id           = $DataOrder[order_product_id];
				$Quantity		      = $DataOrder[order_quantity];
				$Frame_name 	      = $DataOrder[order_product_name];
				$queryProduct  		  = "SELECT misc_unknown_purpose, collection, color FROM ifc_frames_french WHERE ifc_frames_id= '$Product_id'";
				$message 	  		 .= '<hr><br><br><br>Query Product:<br>'.$queryProduct; 
				$resultProduct 		  = mysql_query($queryProduct)	or die ("Erreur inventaire 3.0".mysql_error() . '<br>'. $queryProduct);	
				$DataProduct   		  = mysql_fetch_array($resultProduct);
			    $Collection   	      = $DataProduct[misc_unknown_purpose];
			    $ColorEn   	          = $DataProduct[color_en];
				$Color 	         	  = $DataProduct[color];
				$IDInventaireAUpdater = 21;
				
				$message .= '<br>Product ID: '			 . $Product_id; 
				$message .= '<br>Quantity : '			 . $Quantity; 
				$message .= '<br>Frame name : '			 . $Frame_name; 
				$message .= '<br>Inventaire a Updater: ' . $IDInventaireAUpdater; 

				//Aller chercher les stock actuels pour ce produit
				$queryInventaire  = "SELECT * FROM product_inventory_ifc WHERE lab_id = $IDInventaireAUpdater AND product_id =". $Product_id;
				$message 		 .= '<br>QueryInventaire: <br>'.$queryInventaire; 
				$resultInventaire = mysql_query($queryInventaire)	or die ("Erreur inventaire 4.0" . mysql_error() . '<br>'. $queryInventaire);	
				$NombreResultatInventaire = mysql_num_rows($resultInventaire);
				
				if ($NombreResultatInventaire > 0){//Le tuple existe déja dans l'inventaire
					$DataInventaire  = mysql_fetch_array($resultInventaire);
					$StockActuel     = $DataInventaire[inventory];
					$NouveauStock    = $StockActuel  - $Quantity ;
					$message 	    .= '<br>Stock actuel:'  . $StockActuel; 
					$message 	    .= '<br>Nouveau Stock:' . $NouveauStock; 
					
					if ($NouveauStock <= 0){
						Email_Inventory_at_0("dbeaulieu@direct-lens.com",$Frame_name, $Collection, $Color, $ColorEn,$ordernum,$NouveauStock);
					}
					
					$new_order_total = $DataInventaire[order_total] + $Quantity;
					$queryUpdateInventaire = "UPDATE product_inventory_ifc SET inventory = $NouveauStock, order_total = $new_order_total  WHERE product_id = $Product_id AND lab_id = $IDInventaireAUpdater ";
					$message 	    .= '<br><br> Traitement a effectuer:  '.$queryUpdateInventaire; 
					$resultUpateInventaire = mysql_query($queryUpdateInventaire)	or die ("Erreur inventaire 3".mysql_error().$queryUpdateInventaire);		
				}else{//Si aucun résultat, il n'y avait aucune valeur dans l'inventaire, on ne gère pas l'inventaire de ce produit.							
					$message.='<br><b>Produit introuvable dans l\'inventaire, on ne gère donc pas l\'inventaire de ce produit.</b>'; 
				}
			}//End While
	
			//Envoie des résultats par email	
			$send_to_address = array('rapports@direct-lens.com');
			$curTime	     = date("m-d-Y");	
			$to_address	     = $send_to_address;
			$from_address    = 'donotreply@entrepotdelalunette.com';
			$subject	     = 'Mouvement Inventaire DLN: Commande de montures (B):' . $curTime;
			$response=office365_mail($to_address, $from_address, $subject, null, $message);			
}//End Function



function Email_Inventory_at_0($destinataire,$model,$collection, $color, $couleur, $order_num, $new_inv_value){	
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
	$message .= "Ce modèle est désormais à $new_inv_value dans l'inventaire.
	<br> Commande: $order_num";
	//Envoie des résultats par email	
	$send_to_address=str_split($destinataire,150);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject='Avertissement: Modèle de monture épuisé dans l\'inventaire:'.$curTime;
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}
?>