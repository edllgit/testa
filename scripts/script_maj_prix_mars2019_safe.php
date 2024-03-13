<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
//require_once('../includes/class.ses.php');
$time_start = microtime(true);
$today     =  date("Y-m-d");
echo 'debut:';

//Phase 1: tous les produits SAUF LES STOCKS
$rptQuery="SELECT * FROM safety_Exclusive 
WHERE  prod_status = 'active'
AND maj_prix_juillet2021 = ''
AND product_name not like '%stock%'
LIMIT 0,30";
 
 
echo $rptQuery.'<br>';

	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResult);
	
	if ($ordersnum!=0){
		$count=0;
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

		$message.="<body><table width=\"850\" cellpadding=\"2\" border=\"1\" cellspacing=\"0\" class=\"TextSize\">
				<tr><th>Avant mise à jour</th></tr>
				<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">Produit</td>
					<td align=\"center\">Clé</td>
					<td align=\"center\">Price Discounted</td>
					
					<td align=\"center\">Indice</td>
					<td align=\"center\">Majoration pour Indice</td>
					
					<td align=\"center\">Traitement</td>
					<td align=\"center\">Majoration pour Traitement</td>
					
					<td align=\"center\">Collection</td>
					
					<td align=\"center\">TOTAL Majoration</td>
					<td align=\"center\">Nouveau Prix</td>
					<td align=\"center\">MAJ EFFECTUÉ</td>
				</tr>";
				
	}
	
	
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
				
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		$primary_key		= $listItem[primary_key];
		$index_v   			= $listItem[index_v];
		$coating   			= $listItem[coating]; 
		$price_discounted 	= $listItem[price_discounted];	
		$vendant_edll 		= $listItem[vendant_edll];		
		$MajorationIndice 	= 0;
		$MajorationCoating 	= 0;
			
	
		
			$MajorationIndice =4;  				
					
			
			//$message.='<br>Majoration pour indice: :' .$MajorationIndice;
		
		
			

$MajorationTotale = $MajorationIndice;
$new_price_discounted = $price_discounted + $MajorationTotale;

if ($new_price_discounted<>$price_discounted){
	//Doit mettre a jour avec le nouveau prix		
	$maj_prix_juillet2021 = 'Prix mis a jour par (script de Charles)'. $today;
	$QueryMAJPrix  = "UPDATE safety_exclusive SET  price_discounted = $new_price_discounted , maj_prix_juillet2021='$maj_prix_juillet2021' WHERE  primary_key = $primary_key";
}elseif($new_price_discounted==$price_discounted){
	$maj_prix_juillet2021 = 'Aucune maj a effectuer.(script de Charles)'. $today;
	$QueryMAJPrix  = "UPDATE safety_exclusive SET  maj_prix_juillet2021='$maj_prix_juillet2021' WHERE  primary_key = $primary_key";
}

echo '<br>'. $QueryMAJPrix;
	
	
	$resultMAJPrix = mysqli_query($con,$QueryMAJPrix) or die  ('I cannot select items because: ' . mysqli_error($con));


	$message.="<tr bgcolor=\"FFFFFF\">
					<td align=\"center\">".$listItem[product_name]."</td>
					<td align=\"center\">".$listItem[primary_key]."</td>
					<td align=\"center\">".$listItem[price_discounted]."</td>
					
					<td align=\"center\">".$listItem[index_v]."</td>
					<td align=\"center\">".$MajorationIndice."$</td>
					
					<td align=\"center\">".$listItem[coating]."</td>
					<td align=\"center\">".$MajorationCoating."$</td>
					
					<td align=\"center\">".$listItem[collection]."</td>
					
					<td align=\"center\">".$MajorationTotale."$</td>
					<td align=\"center\">".$new_price_discounted."$</td>
					<td align=\"center\">".$today."</td>
				</tr>";	

		}//End While
	
			
		$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"12\">Number of product analyzed: $ordersnum</td></tr></table>";
		echo $message;
		


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="MAJ prix SAFETY [REEL]  Mars 2021  Rapport de Validation [Prod Inactifs]: ". $curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);


	if($response){ 
		echo 'envoyé avec succes';
		//log_email("REPORT: Login Attempt",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
			echo 'probleme dans l\'envoie';
		//log_email("REPORT: Login Attempt",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}

exit();	
//}//end IF	

?>