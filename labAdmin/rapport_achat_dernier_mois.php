<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$time_start = microtime(true);
$date1 = "2013-07-01";
$date2 = "2013-07-31";
$MontantAAtteindre = 30;


$QueryAccount="SELECT user_id, main_lab, company FROM  accounts WHERE  product_line in ('directlens','lensnetclub') AND main_lab in (29,3) order by company";
//echo '<br><br><br>'. $QueryAccount;
$ResultAccount=mysql_query($QueryAccount)	or die  ('I cannot select items because: ' . mysql_error());
$Compteur=0;

while ($DataAccount=mysql_fetch_array($ResultAccount)){
//Passer a travers de chaque clients de SCT et Lensnet ON afin de cummuler leurs achats	
		
	
	$QueryCustomer="SELECT *, sum(order_total) as TotalClient FROM orders WHERE user_id ='". $DataAccount[user_id] . "' AND order_date_shipped between '$date1' and '$date2' AND order_status='filled'";
	//echo '<br><br>'. $QueryCustomer;
	$ResultCustomer=mysql_query($QueryCustomer)	or die  ('I cannot select items because: ' . mysql_error() . '<br'. $QueryCustomer);	
	$DataCustomer=mysql_fetch_array($ResultCustomer);
	$user_id     = $DataCustomer['user_id'];
	$total_achat = $DataCustomer['TotalClient'];
	
	if ($user_id <> ''){
	//echo '<br><br>Client: '	 . $user_id;
	//echo '<br>Total achat: ' . $total_achat;
	
	
	
	if ($DataCustomer['TotalClient'] >= $MontantAAtteindre){
	//Ajouter le client et le montant dans l'Array
	$Compteur = $Compteur + 1;
	$Array_Client_Montant_Atteint[$Compteur] = $user_id . ',' . $DataCustomer['TotalClient'];
	}	
}


	
	
}

echo 'Compteur: '. $Compteur;
echo '<br><br>';
var_dump($Array_Client_Montant_Atteint);

//Preparer email

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
$message.="<body><table width=\"320\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="<tr bgcolor=\"CCCCCC\">
<td align=\"center\">User ID</td>
<td align=\"center\">Total Sales</td>
</tr>";

foreach ($Array_Client_Montant_Atteint as &$value) {
$thevalue = $value;
$PositionVirgule = strpos($thevalue,',');
//echo '<br><br>Position virgule:'. $PositionVirgule;

$customer = substr($thevalue,0,$PositionVirgule);
echo '<br><br>Customer:'. $customer;

$queryCompany="SELECT company FROM accounts WHERE user_id = '". $customer . "'";
$ResultCompany=mysql_query($queryCompany)	or die  ('I cannot select items because: ' . mysql_error());	
$DataCompany=mysql_fetch_array($ResultCompany);
$company = $DataCompany[company];

$montant_achat = substr($thevalue,$PositionVirgule+1,strlen($thevalue)-$PositionVirgule);
echo '<br>montant achat:'. $montant_achat;

$message.="<tr>
<td align=\"center\">$company</td>
<td align=\"center\">$montant_achat$</td>
</tr>";
}

echo '<br><br>'. $message;



//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Customers who bought for more than " . $MontantAAtteindre. "$ Between $date1 and $date2";
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