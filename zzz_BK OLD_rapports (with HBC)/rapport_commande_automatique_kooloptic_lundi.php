<?php
//Afficher toutes les erreurs/avertissements
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
//include("../sec_connect.inc.php");//Fichier de DataBase:HBC
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');


//Date du rapport
$ilya6jours  		= mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateilya6jours     = date("Y/m/d", $ilya6jours);

$datedhier  		= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     			= date("Y/m/d", $datedhier);

$CollectionsAEvaluer =" AND supplier IN ('MICOLII')";


//A RECOMMENTER
/*
$dateilya3jours = 	"2021-04-05";
$hier 			=	"2021-04-07";
*/

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold','cancelled') 
AND extra_product_orders.category IN ('Frame') 
AND  redo_order_num is null
$CollectionsAEvaluer
AND orders.code_source_monture='V'
AND order_date_processed BETWEEN '$dateilya6jours' AND '$hier'
GROUP BY orders.order_num ORDER BY user_id";

echo '<br>requete:'. $rptQuery;


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

		$message.="<body>";

		if ($ordersnum>0){
			$message.="<table width=\"700\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Store</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"80\" align=\"center\">Collection</th>
							<th width=\"80\" align=\"center\">Model</th>
							<th width=\"80\" align=\"center\">Color</th>
							<th width=\"70\" align=\"center\">A-Bridge</th>
						</tr>";
		}		

		$CompteurResultat = 0;
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		$CompteurResultat += 1;
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
		WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));

		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
		$Collection = $DataFrame[supplier];

		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = $DataFrame[temple_model_num];
						
					$message.="	<tr bgcolor=\"$bgcolor\">
								<td align=\"center\">$listItem[order_date_processed]</td>
								<td align=\"center\">$listItem[user_id]</td>
								<td align=\"center\">$listItem[order_num]</td>
								<td align=\"center\">$Collection</td>
								<td align=\"center\">$ModeleMonture</td>
								<td align=\"center\">$DataFrame[color]</td>
								<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
				  $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Nombre de commande(s): $CompteurResultat</td></tr></table>";
		}//END IF

		
echo '<br><br>' . $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');//LIVE
//$send_to_address = array('rapports@direct-lens.com');//TEST


echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="[Frames sold Report] Micolii EDLL--> [$dateilya6jours - $hier] ";

$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>email sent';
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