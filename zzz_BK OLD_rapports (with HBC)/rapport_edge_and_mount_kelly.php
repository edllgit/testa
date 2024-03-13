<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");//Fichier de DataBase:EDLL
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);	

/*
 A quelle heure devrait être généré ce rapport ? 
8h chaque matin, et il analysera les commandes de la veille

Partie 1- Ce rapport doit contenir 
-Les commandes shippés durant la veille:OK
-Qui appartiennent aux entrepots:OK
-Qui sont des commandes demandées 'Edge and Mount':OK
-Excluant les commandes fabriqués par Swiss:OK


Partie 2- Il doit aussi contenir:
-Les commandes Prestige et Lensnet Ontario 
-Qui sont demandées 'Edge and Mount' 
-Qui ont été expédié la veille 


Partie 3- Ce rapport doit contenir 
-Les commandes shippés durant la veille:
-Qui appartiennent aux HBC :
-Qui sont des commandes demandées 'Edge and Mount'
-Excluant les commandes fabriqués par Swiss
*/


$time_start = microtime(true);
$nbrResultat= 0;
$ladate  	= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     	= date("Y-m-d", $ladate);

//$hier='2020-02-19';


//Début Partie 1
$Condition1 = " LAB in (66,67,59)";								//Lab appartient à Edll: Entrepot QC, Warehouse CA ou SAFETY
$Condition2 = " order_date_shipped='$hier'";				//Date d'expédition = hier
$Condition3 = " extra_product_orders.job_type='Edge and Mount'";//Uniquement les Edge and Mount
$Condition4 = " extra_product_orders.category='Edging'";
$Condition5 = " prescript_lab<>10";//QUi ne sont pas fabriqués par Swisscoat

$rptQueryPart1    = "SELECT  orders.user_id, orders.order_num,  orders.order_date_shipped, orders.prescript_lab,  orders.tray_num,
extra_product_orders.job_type, extra_product_orders.category   FROM orders, extra_product_orders
WHERE $Condition1  
AND   $Condition2
AND   $Condition3
AND   $Condition4
AND   $Condition5
AND   orders.order_num = extra_product_orders.order_num
ORDER BY user_id";

$Result_QueryPart1    = mysqli_query($con,$rptQueryPart1)	or die  ('I cannot select items because: ' . mysqli_error($con));

//echo $rptQueryPart1;


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


		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
		<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"4\"><h3>EDLL ORDERS</h3></td></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>User ID</b></td>
				<td align=\"center\"><b>Order Num</b></td>
				<td align=\"center\"><b>Date Shipped</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				</tr>";
				
		$ordersnum=0;//Compteur de job	
		while ($listItemPart1=mysqli_fetch_array($Result_QueryPart1,MYSQLI_ASSOC)){
			
		
		



			$ordersnum = $ordersnum+1;
			$count++;
			if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
			$message.="<tr bgcolor=\"$bgcolor\">";
			$message.="<td align=\"center\">$listItemPart1[user_id]</td>";
			$message.="<td align=\"center\">$listItemPart1[order_num]</td>";
			$message.="<td align=\"center\">$listItemPart1[order_date_shipped]</td>";
			$message.="<td align=\"center\">$listItemPart1[tray_num]</td>";
			$message.="</tr>";

			
			
			
		}//END WHILE
		
		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"9\"><b>Number of EDLL Orders: $ordersnum</b></td></tr></table>";
		}

//FIN DE LA PARTIE 1 [EDLL]


//Début de le partie 2: [Prestige]
//echo '<br>Debut partie 2:<br>';
$Condition1 = " order_from = 'eye-recommend' ";				//Commandes Prestige
$Condition2 = " order_date_shipped='$hier'";				//Date d'expédition = hier
$Condition3 = " extra_product_orders.job_type='Edge and Mount'";//Uniquement les Edge and Mount
$Condition4 = " extra_product_orders.category='Edging'";
$Condition5 = " prescript_lab<>10";							//Qui ne sont pas fabriqués par Swisscoat

$QueryPart2    = "SELECT  orders.user_id, orders.order_num,  orders.order_date_shipped, orders.prescript_lab,  orders.tray_num,
extra_product_orders.job_type, extra_product_orders.category  FROM orders, extra_product_orders
WHERE $Condition1  
AND   $Condition2
AND   $Condition3
AND   $Condition4
AND   $Condition5
AND   orders.order_num = extra_product_orders.order_num
ORDER BY user_id";

//echo $QueryPart2;

$Result_QueryPart2    = mysqli_query($con,$QueryPart2)	or die  ('I cannot select items because: ' . mysqli_error($con));

		$message.="<br><br><br><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
		<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"4\"><h3>PRESTIGE ORDERS</h3></td></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>User ID</b></td>
				<td align=\"center\"><b>Order Num</b></td>
				<td align=\"center\"><b>Date Shipped</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				</tr>";
				
$ordersnum=0;//Compteur de job	
while ($listItemPart2=mysqli_fetch_array($Result_QueryPart2,MYSQLI_ASSOC)){
		
	$ordersnum = $ordersnum+1;
		
	$count++;
	 if (($count%2)==0)
  				$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		$message.="<tr bgcolor=\"$bgcolor\">";
		$message.="<td align=\"center\">$listItemPart2[user_id]</td>";
		$message.="<td align=\"center\">$listItemPart2[order_num]</td>";
		$message.="<td align=\"center\">$listItemPart2[order_date_shipped]</td>";
		$message.="<td align=\"center\">$listItemPart2[tray_num]</td>";
		$message.="</tr>";
	}//END WHILE
	
	if ($ordersnum>0){
		$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"9\"><b>Number of  Prestige Order(s): $ordersnum</b></td></tr></table>";
	}
//Fin de la partie 2: [Prestige]




//Début de le partie 3: [Lensnet Ontario]
//echo '<br>Debut partie 3:<br>';
$Condition1 = " order_from = 'lensnetclub' AND lab=29  ";	//Commandes Lensnet Club [Ontario]
$Condition2 = " order_date_shipped='$hier'";				//Date d'expédition = hier
$Condition3 = " extra_product_orders.job_type='Edge and Mount'";//Uniquement les Edge and Mount
$Condition4 = " extra_product_orders.category='Edging'";
$Condition5 = " prescript_lab<>10";							//Qui ne sont pas fabriqués par Swisscoat

$QueryPart3    = "SELECT  orders.user_id, orders.order_num,  orders.order_date_shipped, orders.prescript_lab,  orders.tray_num,
extra_product_orders.job_type, extra_product_orders.category  FROM orders, extra_product_orders
WHERE $Condition1  
AND   $Condition2
AND   $Condition3
AND   $Condition4
AND   $Condition5
AND   orders.order_num = extra_product_orders.order_num
ORDER BY user_id";

//echo $QueryPart3;

$Result_QueryPart3    = mysqli_query($con,$QueryPart3)	or die  ('I cannot select items because: ' . mysqli_error($con));

		$message.="<br><br><br><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
		<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"4\"><h3>LensNetClub Ontario's ORDER(S)</h3></td></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>User ID</b></td>
				<td align=\"center\"><b>Order Num</b></td>
				<td align=\"center\"><b>Date Shipped</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				</tr>";
				
$ordersnum=0;//Compteur de job	
while ($listItemPart3=mysqli_fetch_array($Result_QueryPart3,MYSQLI_ASSOC)){
		
	$ordersnum = $ordersnum+1;
		
	$count++;
	 if (($count%2)==0)
  				$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		$message.="<tr bgcolor=\"$bgcolor\">";
		$message.="<td align=\"center\">$listItemPart3[user_id]</td>";
		$message.="<td align=\"center\">$listItemPart3[order_num]</td>";
		$message.="<td align=\"center\">$listItemPart3[order_date_shipped]</td>";
		$message.="<td align=\"center\">$listItemPart3[tray_num]</td>";
		$message.="</tr>";
	}//END WHILE
	
	if ($ordersnum>0){
		$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"9\"><b>Number of LNC Ontario Order(s): $ordersnum</b></td></tr></table><br><br><br>";
	}
//Fin de la partie 3: [Lensnet Ontario]









//Chemin de connexion DB: HBC pour la partie 4
include('../connexion_hbc.inc.php');
//Début de le partie 4: [HBC]
//echo '<br>Debut partie 4:<br>';
$Condition1 = " order_from = 'hbc' ";						//Commandes HBC
$Condition2 = " order_date_shipped='$hier'";				//Date d'expédition = hier
$Condition3 = " extra_product_orders.job_type='Edge and Mount'";//Uniquement les Edge and Mount
$Condition4 = " extra_product_orders.category='Edging'";
$Condition5 = " prescript_lab<>10";							//Qui ne sont pas fabriqués par Swisscoat

$QueryPart4    = "SELECT  orders.user_id, orders.order_num,  orders.order_date_shipped, orders.prescript_lab,  orders.tray_num,
extra_product_orders.job_type, extra_product_orders.category  FROM orders, extra_product_orders
WHERE $Condition1  
AND   $Condition2
AND   $Condition3
AND   $Condition4
AND   $Condition5
AND   orders.order_num = extra_product_orders.order_num
ORDER BY user_id";

//echo $QueryPart4;

$Result_QueryPart4    = mysqli_query($con,$QueryPart4)	or die  ('I cannot select items because: ' . mysqli_error($con));

		$message.="<br><br><br><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
		<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"4\"><h3>HBC'S ORDER(S)</h3></td></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>User ID</b></td>
				<td align=\"center\"><b>Order Num</b></td>
				<td align=\"center\"><b>Date Shipped</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				</tr>";
				
$ordersnum=0;//Compteur de job	
while ($listItemPart4=mysqli_fetch_array($Result_QueryPart4,MYSQLI_ASSOC)){

	$ordersnum = $ordersnum+1;
		
	$count++;
	 if (($count%2)==0)
  				$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		$message.="<tr bgcolor=\"$bgcolor\">";
		$message.="<td align=\"center\">$listItemPart4[user_id]</td>";
		$message.="<td align=\"center\">$listItemPart4[order_num]</td>";
		$message.="<td align=\"center\">$listItemPart4[order_date_shipped]</td>";
		$message.="<td align=\"center\">$listItemPart4[tray_num]</td>";
		$message.="</tr>";
	}//END WHILE
	
	if ($ordersnum>0){
		$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"9\"><b>Number of HBC'S Order(s): $ordersnum</b></td></tr></table>";
	}
//Fin de la partie 4 : [HBC]





//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');		
//$send_to_address = array('rapports@direct-lens.com');		
echo "<br>".$send_to_address;	
echo '<br>'. $message;	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Jobs Done by Saint-Catharines Shipped yesterday [Edge and Mount]";
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
	
	
	if($response){ 
		echo 'Reussi';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		echo 'Echec';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
	
			
/*
function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because 6: ' . mysql_error());	
}
*/
?>