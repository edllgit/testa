<?php 
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Inclusions
require_once(__DIR__.'/../constants/url.constant.php');
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");
//include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include "../barcodes/Barcode39.php";

//DÉMARRER LA SESSION
session_start();




$today     = date("Y-m-d");
//$today     = "2015-11-16";
$rptQuery  = "SELECT * FROM orders  WHERE  prescript_lab = 10 AND frame_sent_swiss <> '0000-00-00 00:00:00'  AND orders.frame_sent_swiss like  '%$today%'";
echo $rptQuery;
$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
		
	
	if ($ordersnum == 0){
		echo 'No results';
	}
	
	
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

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Tray</td>
                <td align=\"center\">Frame Collection</td>
				<td align=\"center\">Frame Model</td>
				<td align=\"center\">Customer</td>
                <td align=\"center\">Product</td>
			    <td align=\"center\">Edging Barcode</td>
				</tr>";
		
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


	    $queryFrame  = "SELECT * FROM extra_product_orders WHERE category= 'Frame' AND order_num = $listItem[order_num]";
		$resultFrame = mysqli_query($con,$queryFrame)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$ModeleTrouver = mysqli_num_rows($resultFrame);
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
		$Collection  = $DataFrame[supplier];
		$FrameModel  = $DataFrame[temple_model_num];
		
		$queryCompany  =  "SELECT company FROM accounts WHERE user_id = '$listItem[user_id]'";	
		$resultCompany =  mysqli_query($con,$queryCompany)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataCompany   =  mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		$Company       =  $DataCompany[company];

	$QuerySwissEdgingBarcode = "SELECT swiss_edging_barcode FROM swiss_edging_barcodes WHERE order_num = $listItem[order_num]";
	$resultEdgingBarcodee    =  mysqli_query($con,$QuerySwissEdgingBarcode)	or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataEdgingBarcode  	 = mysqli_fetch_array($resultEdgingBarcodee,MYSQLI_ASSOC);
	$SwissEdgingBarcode      = $DataEdgingBarcode[swiss_edging_barcode];


	// set object
	$bc = new Barcode39($SwissEdgingBarcode);
	// set text size
	$bc->barcode_text_size = 3;
	// set barcode bar thickness (thick bars)
	$bc->barcode_bar_thick = 4;
	// set barcode bar thickness (thin bars)
	$bc->barcode_bar_thin = 2;
	$bc->draw("$SwissEdgingBarcode".".gif");
	$barcode= constant('DIRECT_LENS_URL')."/labAdmin/".$SwissEdgingBarcode.".gif";

	//<td align=\"center\"><img src=\"'.$barcode.'\" width=\"190\" height=\"50\" /></td>";

			$message.="<tr bgcolor=\"$bgcolor\">
			    <td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[tray_num]</td>
				<td align=\"center\">$Collection</td>
			    <td align=\"center\">$FrameModel</td>
                <td align=\"center\">$Company</td>
                <td align=\"center\">$listItem[order_product_name]</td>
				<td align=\"center\"><img src=".$barcode." width=\"190\" height=\"50\" /></td>";
              $message.="</tr>";
		}//END WHILE
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	


//echo "<br>".$send_to_address;
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Official Report Frames sent to Swiss ";
$response     = office365_mail($to_address, $from_address, $subject, null, $message);

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
	
	if($response){ 
		log_email("REPORT: Official Report Frames sent to Swiss",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Official Report Frames sent to Swiss",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		




echo $message;

?>