<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$aujourdhui=date("Y-m-d");

//$aujourdhui="2021-04-07";	//TEST SEULEMENT**

//RAPPORT QUI ROULE CHAQUE JOUR et qui inclus toutes les montures Armourx commandés durant a journée
//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders WHERE orders.order_num = extra_product_orders.order_num AND extra_product_orders.category in 
('Edging','Frame','Edging_Frame') AND order_from IN ('ifcclubca','safety') AND order_status not in ('cancelled','basket','on hold') AND order_date_processed = '$aujourdhui'  AND order_product_type = 'exclusive'
AND orders.user_id NOT IN ('redoifc','redosafety') AND supplier in ('Basic','Metro','Wrap-Rx','Classic','ArmouRx') and redo_order_num is null
AND orders.code_source_monture='V'
GROUP BY orders.order_num ORDER BY  model  DESC";

echo 'requete:'. $rptQuery;

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	
	
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

		$message.="<body><table width=\"610\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td width=\"80\" align=\"center\">Order #</td>
                <td width=\"80\" align=\"center\">Date</td>
				<td width=\"80\" align=\"center\">Supplier</td>
                <td width=\"80\" align=\"center\">Model</td>
				<td width=\"70\" align=\"center\">Frame A</td>
				<td width=\"80\" align=\"center\">Color</td>
				<td width=\"140\" align=\"center\">Extra</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$Extra = '';	
		
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
		
		$queryRemSideShield  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Removable Side Shield')";
		$resultRemSideShield = mysqli_query($con,$queryRemSideShield)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatSideShield = mysqli_num_rows($resultRemSideShield );
		if ($NbrResultatSideShield  > 0){
			$queryRemSideShieldDetail  = "SELECT  distinct  removable_side_shield_ID  FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultRemSideShieldDetail = mysqli_query($con,$queryRemSideShieldDetail) or die  ('I : ' . mysqli_error($con));
			$DataRemoveableSideShield  = mysqli_fetch_array($resultRemSideShieldDetail,MYSQLI_ASSOC);
			$Extra .= ' Removable Side Shield  ' . $DataRemoveableSideShield[removable_side_shield_ID]  ;
		}
		
		//Cushion
		$queryCushion  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Cushion')";
		$resultCushion= mysqli_query($con,$queryCushion)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatCushion = mysqli_num_rows($resultCushion );
		if ($NbrResultatCushion  > 0) {
			$queryCushionDetail  = "SELECT  distinct cushion_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultCushionDetail = mysqli_query($con,$queryCushionDetail)		or die  ('I cannot select items because: ' . mysqli_error());
			$DataCushionDetail   = mysqli_fetch_array($resultCushionDetail,MYSQLI_ASSOC);
			$Extra .= ' Cushion ' . $DataCushionDetail[cushion_ID]  ;
		}
		
		//Dust bar
		$queryDustBar  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Dust Bar')";
		$resultDustBar= mysqli_query($con,$queryDustBar)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$NbrResultatDustBar = mysqli_num_rows($resultDustBar );
		if ($NbrResultatDustBar  > 0) {
			$queryDustBarDetail  = "SELECT  distinct dust_bar_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultDustBarDetail = mysqli_query($con,$queryDustBarDetail)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataDustBarDetail   = mysqli_fetch_array($resultDustBarDetail,MYSQLI_ASSOC);
			$Extra .= ' Dust Bar ' . $DataDustBarDetail[dust_bar_ID]  ;
		}
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

	

			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[order_num]</td>
               				<td align=\"center\">$listItem[order_date_processed]</td>";
             $message.="    <td align=\"center\">$DataFrame[supplier]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[ep_frame_a]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$Extra</td>";
              $message.="</tr>";
		}//END WHILE

		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		
echo '<br><br>' . $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');//PRODUCTION CREDENTIALS
$send_to_address = array('rapports@direct-lens.com');//PRODUCTION CREDENTIALS
$send_to_address = array('info@armourxsafety.com','monture@entrepotdelalunette.com','renouvellement@entrepotdelalunette.com');

//$send_to_address = array('rapports@direct-lens.com');//Armourx ne le recevra plus puisqu'ils auront les email a mesure que les commandes sont faites
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Direct lab Network ArmouRx Frames order(s) of the day: $aujourdhui";
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
	

?>