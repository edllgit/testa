<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');


//Définir les dates du rapport
$date1="2023-01-01";
$date2="2023-01-31";

//On sort les comptes Prestige QUI ONT UN ACOMBA_ACCOUNT_NUM, S'il n'en ont pas, ils ne l'ont jamais utilisés
$rptQuery  = "SELECT distinct acomba_account_num FROM accounts WHERE product_line IN ('eye-recommend','lensnetclub','directlens')  AND acomba_account_num <> '' AND acomba_account_num <> 'PEOP' AND approved = 'approved' ORDER BY acomba_account_num";


echo '<br><br>'. $rptQuery;
$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
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


		$message.="<body><table width=\"730\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
		<tr><th colspan=\"6\">VENTE ENTRE LE $date1 ET $date2</th></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <th align=\"center\" width=\"295\">Company</th>
                <th align=\"center\" width=\"45\"># ACOMBA</th>
                <th align=\"center\" width=\"80\">VENTE PRESTIGE</th>
				<th align=\"center\" width=\"80\">VENTE LNC</th>
				<th align=\"center\" width=\"80\">VENTE DL</th>
				<th align=\"center\" width=\"80\">TOTAL</th>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			

//echo '<br><br><br>Acomba acct num:'.$listItem[acomba_account_num];

//echo '<br>Company :'.$listItem[company];

$queryP      = "SELECT * FROM accounts WHERE acomba_account_num = '$listItem[acomba_account_num]' AND product_line = 'eye-recommend'";
echo '<br>query'. $queryP;
$ResultP     = mysqli_query($con,$queryP)		or die  ('I cannot select items because: ' . mysqli_error());
$NbrResultat = mysqli_num_rows($ResultP);
$TotalVentePrestige   = 0;
$TotalCreditPrestige  = 0;

if ($NbrResultat > 0) {
	$DataP   = mysqli_fetch_array($ResultP,MYSQLI_ASSOC);
	$UserIDPrestige = $DataP[user_id];
	
	$queryAchatPrestige  = "SELECT distinct order_num, order_total FROM orders WHERE order_date_shipped BETWEEN '$date1' AND '$date2' AND user_id = '$UserIDPrestige'";
	echo '<br><br>query:' .	$queryAchatPrestige .'<br>' ;
	$ResultAchatPrestige = mysqli_query($con,$queryAchatPrestige)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($DataAchatPrestige   = mysqli_fetch_array($ResultAchatPrestige,MYSQLI_ASSOC)){
		$TotalVentePrestige = $TotalVentePrestige + $DataAchatPrestige[order_total];
	}
	
	
	$queryCreditPrestige  = "SELECT distinct  mcred_primary_key, mcred_acct_user_id , mcred_abs_amount, mcred_date FROM memo_credits WHERE mcred_date BETWEEN '$date1' AND '$date2' AND mcred_acct_user_id = '$UserIDPrestige'";
	echo '<br><br>query:' .	$queryCreditPrestige .'<br>' ;
	$ResultCreditPrestige = mysqli_query($con,$queryCreditPrestige)		or die  ('I cannot select items because: ' . mysqli_error($con));

	while ($DataCreditPrestige   = mysqli_fetch_array($ResultCreditPrestige,MYSQLI_ASSOC)){
	$TotalCreditPrestige = $TotalCreditPrestige + $DataCreditPrestige[mcred_abs_amount];
	}
	
	//echo '<br>Total Prestige: '. $DataAchatPrestige[TotalPrestige];
}//End IF

$queryLNC      = "SELECT * FROM accounts WHERE acomba_account_num = '$listItem[acomba_account_num]' AND product_line = 'lensnetclub'";
echo '<br>query'. $queryLNC;
$ResultLNC       = mysqli_query($con,$queryLNC)		or die  ('I cannot select items because: ' . mysqli_error($con));
$NbrResultatLNC  = mysqli_num_rows($ResultLNC);
$TotalCreditLNC  = 0;
$DataAchatLNC[TotalLNC] =0;
if ($NbrResultatLNC > 0) {
	$DataLNC   = mysqli_fetch_array($ResultLNC,MYSQLI_ASSOC);
	$UserIDLNC = $DataLNC[user_id];
	$queryAchatLNC  = "SELECT sum(order_total) as TotalLNC, sum(order_shipping_cost) as TotalShipCost FROM orders WHERE order_date_shipped BETWEEN '$date1' AND '$date2'  AND user_id = '$UserIDLNC'";
	echo '<br>query:' .	$queryAchatLNC ;
	$resultAchatLNC = mysqli_query($con,$queryAchatLNC)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataAchatLNC   = mysqli_fetch_array($resultAchatLNC,MYSQLI_ASSOC);
	echo '<br>Total LNC: '. $DataAchatLNC[TotalLNC];
	echo '<br>Total Shipping LNC: '. $DataAchatLNC[TotalShipCost];
	$DataAchatLNC[TotalLNC] = $DataAchatLNC[TotalLNC]+ $DataAchatLNC[TotalShipCost];
	echo '<br>Total LNC + shipping: : '. $DataAchatLNC[TotalLNC];
	

	$queryCreditLNC  = "SELECT distinct  mcred_primary_key, mcred_acct_user_id , mcred_abs_amount, mcred_date FROM memo_credits WHERE mcred_date BETWEEN '$date1' AND '$date2' AND mcred_acct_user_id = '$UserIDLNC'";
	echo '<br><br>query:' .	$queryCreditLNC .'<br>' ;
	$ResultCreditLNC = mysqli_query($con,$queryCreditLNC)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
	while ($DataCreditLNC   = mysqli_fetch_array($ResultCreditLNC,MYSQLI_ASSOC)){
	$TotalCreditLNC = $TotalCreditLNC + $DataCreditLNC[mcred_abs_amount];
	}
	
}//End IF



$queryDL     = "SELECT * FROM accounts WHERE acomba_account_num = '$listItem[acomba_account_num]' AND product_line = 'directlens'";
echo '<br>query'. $queryDL;
$ResultDL     = mysqli_query($con,$queryDL)		or die  ('I cannot select items because: ' . mysqli_error($con));
$NbrResultatDL = mysqli_num_rows($ResultDL);
if ($NbrResultatDL > 0) {
	$DataDL   = mysqli_fetch_array($ResultDL,MYSQLI_ASSOC);
	$UserIDDL = $DataDL[user_id];
	//On exclus le stock
	$queryAchatDL  = "SELECT sum(order_total) as TotalDL FROM orders WHERE order_date_shipped BETWEEN '$date1' AND '$date2'  AND user_id = '$UserIDDL' AND order_product_type='exclusive'";	
	echo '<br>query:' .	$queryAchatDL ;
	$resultAchatDL = mysqli_query($con,$queryAchatDL)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataAchatDL   = mysqli_fetch_array($resultAchatDL,MYSQLI_ASSOC);
	
	
	$queryAchatDLStock    = "SELECT distinct order_num FROM orders WHERE order_date_shipped BETWEEN '$date1' AND '$date2'  AND user_id = '$UserIDDL' AND order_product_type<>'exclusive'";
	echo '<br>query:' .	$queryAchatDLStock ;	
	$resultAchatDLStock   = mysqli_query($con,$queryAchatDLStock)		or die  ('I cannot select items because: ' . mysqli_error($con));
	//TODO: Boucle While qui additionne le total des commandes stock durant cette période
	
	$TotalStockDL = 0;
	while($DataAchatDLStock = mysqli_fetch_array($resultAchatDLStock,MYSQLI_ASSOC)){
		$queryPrisStockOrder   = "SELECT order_total FROM orders WHERE order_num= $DataAchatDLStock[order_num]";	
		$resultPrixStockOrder  = mysqli_query($con,$queryPrisStockOrder)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataStockDL   = mysqli_fetch_array($resultPrixStockOrder,MYSQLI_ASSOC);
		$TotalStockDL  = TotalStockDL+ $DataStockDL[order_total];	
	}
	$DataAchatDL[TotalDL] = $DataAchatDL[TotalDL]+ $TotalStockDL;
	
	echo '<br>Total DL: '. $DataAchatDL[TotalDL];
	
	$queryCreditDL  = "SELECT distinct  mcred_primary_key, mcred_acct_user_id , mcred_abs_amount, mcred_date FROM memo_credits WHERE mcred_date BETWEEN '$date1' AND '$date2' AND mcred_acct_user_id = '$UserIDDL'";
	echo '<br><br>query:' .	$queryCreditDL .'<br>' ;
	$ResultCreditDL = mysqli_query($con,$queryCreditDL)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$TotalCreditDL  = 0;
	while ($DataCreditDL   = mysqli_fetch_array($ResultCreditDL,MYSQLI_ASSOC)){
	$TotalCreditDL = $TotalCreditDL + $DataCreditDL[mcred_abs_amount];
	}
}//End IF


$TotalVentePrestige = $TotalVentePrestige - $TotalCreditPrestige;
$TotalVentePrestige = money_format('%.2n',$TotalVentePrestige);
$TotalVenteLNC 	= $DataAchatLNC[TotalLNC] - $TotalCreditLNC; 
$TotalVenteLNC  = money_format('%.2n',$TotalVenteLNC);
$TotalVenteDL   = $DataAchatDL[TotalDL] - $TotalCreditDL;
$TotalVenteDL   = money_format('%.2n',$TotalVenteDL);

if ($TotalVenteDL == 0.00)
$TotalVenteDL = '';

if ($TotalVentePrestige == 0.00)
$TotalVentePrestige= '';

$Totaldes3collones = $TotalVentePrestige + $TotalVenteLNC + $TotalVenteDL;
$Totaldes3collones = money_format('%.2n',$Totaldes3collones);

if ($TotalVentePrestige == 0.00)
$TotalVentePrestige= '';

if ($TotalVentePrestige <> ''){
	$TotalVentePrestige = $TotalVentePrestige. '$';
}

if ($TotalVenteLNC <> ''){
	$TotalVenteLNC = $TotalVenteLNC . '$';
}

if ($DataAchatDL[TotalDL]<>''){
	$DataAchatDL[TotalDL] = $DataAchatDL[TotalDL]. '$';
}


if ($listItem[ship_state]=='Ontario')
$listItem[ship_state] = 'ON';

if ($LastAcombaAcctNum <> $DataP[acomba_account_num])
{
	$message.="<tr>
	<th align=\"left\">$DataP[company]</th>
	<th align=\"center\">$DataP[acomba_account_num]</th>
	<th align=\"center\">$TotalVentePrestige</th>
	<th align=\"center\">$TotalVenteLNC</th>
	<th align=\"center\">$TotalVenteDL</th>";
	if ($Totaldes3collones=='0.00'){
	$Totaldes3collones = '';
	}else{
		$Totaldes3collones = $Totaldes3collones. '$';
	}
	$message .="<th align=\"center\">$Totaldes3collones</th>
	</tr>";
}
$LastAcombaAcctNum = $DataP[acomba_account_num];
		 
}//END WHILE



//Gestion Hard codé du compte PEOP puisqu'il a plus d'un compte Lensnet Club
$message.="<tr>
<th align=\"left\">People's Optical (Mount Pearl)</th>
<th align=\"center\">PEOP</th>
<th align=\"center\"></th>
<th align=\"center\"></th>
<th align=\"center\">&nbsp;</th>";
$TotalPEOP = $TotalPrestigePEOP + $TotalLNCPEOP;
$TotalPEOP =money_format('%.2n',$TotalPEOP);
if ($TotalPEOP=='0.00'){
$TotalPEOP = '';
}else{
	$TotalPEOP = $TotalPEOP. '$';
}
$message .="<th align=\"center\">$TotalPEOP</th>
</tr>";

		
	echo '<br><br>'.$message;
		$message.="</table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	
//echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Rapport vente Clients Prestige $date1 - $date2";
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
	}else{
		echo 'Echec';
	}	
}		



?>