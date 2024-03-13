<?php
include("../Connections/sec_connect.inc.php");
$today=date("Y-m-d");
$time_start = microtime(true);
//CREATE EXPORT FILE//
$filename="../acomba/FROM DIRECT-LENS/DL_CCImport.txt";
$fp=fopen($filename, "w");

//EXPORTATION DES COMMANDES SHIPPÉS
//On utilise le distinct pour eviter d'avoir plusieurs lignes pour les commandes stock
//$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped='$today' ORDER by order_num";
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  BETWEEN '2012-09-01' and '2012-09-30'
AND user_id not in (
'rcogroup',
'drummond',
'stcatharines',
'benoit',
'St. Catharines',
'atlanticredo',
'redoqc',
'redoatl',
'rcogroupnet',
'grmredo',
'Dlabeagle',
'lensneton',
'rcogroupifc') ORDER by order_num";
echo $orderQuery;
$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());
$itemcount=mysql_num_rows($orderResult);
$outputstring = 'Ligne de commentaire qui n\'est pas utilisé'  .  "\r\n" ;//Ligne 1
$outputstring.= 'LFACT=12' .  "\r\n" 	;// Ligne 2 pour laisser savoir que nos order num auront 12 caractères
fwrite($fp,$outputstring);
while ($orderData=mysql_fetch_array($orderResult)){
$outputstring=export_order_acomba($orderData[order_num]);
fwrite($fp,$outputstring);
}



/*
//EXPORTATION DES CRÉDITS
$QueryCredit="select * from memo_credits WHERE  mcred_date  = '$today'"; //Get Credit Data
//$QueryCredit="select * from memo_credits WHERE  mcred_date   BETWEEN '2012-08-01' and '2012-09-30'"; //Get Credit Data Aout-Septembre
echo '<br>'. $QueryCredit;
$ResultCredit=mysql_query($QueryCredit)	or die  ('I cannot select items because: ' . mysql_error());
while ($DataCredit=mysql_fetch_array($ResultCredit)){
$outputstring=export_credit_acomba($DataCredit[mcred_primary_key]);
fwrite($fp,$outputstring);
}*/




//EXPORTATION DES PAIEMENTS
//$queryPayments="select * from payments WHERE pmt_date = '$today'"; //Get Credit Data 
//$queryPayments="select * from payments WHERE pmt_date  BETWEEN '2012-08-01' and '2012-09-30'"; //Get Credit Data aout-septembre

/*$queryPayments="select * from payments WHERE pmt_date  = '2012-09-20'"; //Get Credit Data
echo '<br>'. $queryPayments;
$ResultPayments=mysql_query($queryPayments)	or die  ('I cannot select items because: ' . mysql_error());
while ($DataPayments=mysql_fetch_array($ResultPayments)){
$outputstring=export_payment_acomba($DataPayments[primary_key]);
fwrite($fp,$outputstring);
}*/

//Ajout des 2 sauts de ligne a la fin du fichier pour le terminer
$outputstring.=  "\r\n". "\r\n";
fwrite($fp,$outputstring);

echo '<br><br>Output: ' . $outputstring;
fclose($fp);



//Début des fonctions
function export_order_acomba($order_num){
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
			
	
	  /* //Mapping avec les numéro de compte D'acomba	
		switch($orderItem["account_num"]){
			case "154":	    $Account_Num_Acomba = ""; 	break;
			case "201":	    $Account_Num_Acomba	= "";	break;
			case "202":	    $Account_Num_Acomba	= ""; 	break;
			case "203":	    $Account_Num_Acomba	= "";	break;
			case "288":	    $Account_Num_Acomba	= ""; 	break;
			case "547":	    $Account_Num_Acomba	= ""; 	break;
			case "565":	    $Account_Num_Acomba	= ""; 	break;
			case "766":		$Account_Num_Acomba	= ""; 	break;
			case "769":		$Account_Num_Acomba	= ""; 	break;
			case "841":		$Account_Num_Acomba	= ""; 	break;
			case "853":		$Account_Num_Acomba	= ""; 	break;
			case "875":		$Account_Num_Acomba	= ""; 	break;
			case "888":		$Account_Num_Acomba	= ""; 	break;
			case "1234":	$Account_Num_Acomba	= ""; 	break;
			case "895":		$Account_Num_Acomba	= ""; 	break;	
			case "900":		$Account_Num_Acomba	= ""; 	break;
			case "1177":	$Account_Num_Acomba	= ""; 	break;
			case "1251":	$Account_Num_Acomba	= ""; 	break;
			
			case "374":	    $Account_Num_Acomba	= "21250"; 	break;
			case "412":	    $Account_Num_Acomba	= "30220"; 	break;
			case "570":	    $Account_Num_Acomba	= "25200"; 	break;
			case "577":		$Account_Num_Acomba	= "25210"; 	break;
			case "605":		$Account_Num_Acomba	= "11160"; 	break;
			case "610":		$Account_Num_Acomba	= "12130"; 	break;
			case "698":		$Account_Num_Acomba	= "SPEB"; 	break;
			case "95":	    $Account_Num_Acomba = "19110";	break;
			case "207-2":   $Account_Num_Acomba	= "28130"; 	break;
			case "208":	    $Account_Num_Acomba	= "24130";	break;
			case "265":	    $Account_Num_Acomba	= "LUNO"; 	break;
			case "271":	    $Account_Num_Acomba	= "21220"; 	break;
			case "91007":   $Account_Num_Acomba	= "12240"; 	break;
			case "AQ5020":  $Account_Num_Acomba	= "11130"; 	break;
			case "og10880": $Account_Num_Acomba	= "19120"; 	break;
			case "206-1":   $Account_Num_Acomba	= "17130"; 	break;
			case "360":	    $Account_Num_Acomba	= "24190"; 	break;
			case "361":	    $Account_Num_Acomba	= "24200"; 	break;
			case "362":	    $Account_Num_Acomba	= "24170"; 	break;
			case "363":	    $Account_Num_Acomba	= "16190"; 	break;
			case "1283":	$Account_Num_Acomba	= "EYESC"; 	break;
			case "5002":	$Account_Num_Acomba	= "LUNA"; 	break;
			case "5007":	$Account_Num_Acomba	= "INTL"; 	break;
			case "5018":	$Account_Num_Acomba	= "CENO"; 	break;
			case "1341":	$Account_Num_Acomba	= "OPTT"; 	break;
			case "864":		$Account_Num_Acomba	= "30100"; 	break;
			case "865":		$Account_Num_Acomba	= "30100"; 	break;
			case "804":		$Account_Num_Acomba	= "IFCI"; 	break;
			case "806":		$Account_Num_Acomba	= "26100"; 	break;
			case "755":		$Account_Num_Acomba	= "OTTP"; 	break;
			case "756":		$Account_Num_Acomba	= "30000"; 	break;
			case "759":		$Account_Num_Acomba	= "24131"; 	break;
			case "5073":	$Account_Num_Acomba	= "ALPO"; 	break;
			case "5124":	$Account_Num_Acomba	= "OPTA"; 	break;
			case "5135":	$Account_Num_Acomba	= "OPTD"; 	break;
			case "5152":	$Account_Num_Acomba	= "COMH"; 	break;
			case "5154":	$Account_Num_Acomba	= "COML"; 	break;
			case "5019":	$Account_Num_Acomba	= "OPTG"; 	break;
			case "5035":	$Account_Num_Acomba	= "BREO"; 	break;	
			case "5043":	$Account_Num_Acomba	= "ATOP"; 	break;
			case "5060":	$Account_Num_Acomba	= "INTM"; 	break;
			case "5072":	$Account_Num_Acomba	= "OPTM"; 	break;
			case "5161":	$Account_Num_Acomba	= "STEO"; 	break;
			case "5169":	$Account_Num_Acomba	= "OPTS"; 	break;
			case "5170":	$Account_Num_Acomba	= "COUO"; 	break;
			case "5198":	$Account_Num_Acomba	= "OPTST"; 	break;
			case "5155":	$Account_Num_Acomba	= "COMLU"; 	break;
			case "5156":	$Account_Num_Acomba	= "ISLO"; 	break;
			case "1146":	$Account_Num_Acomba	= "28177"; 	break;
			case "5217":	$Account_Num_Acomba	= "OPTPL"; 	break;
			case "5218":	$Account_Num_Acomba	= "OPTPB"; 	break;
			case "5219":	$Account_Num_Acomba	= "OPTD"; 	break;
			case "5203":	$Account_Num_Acomba	= "COMT"; 	break;
			case "5204":	$Account_Num_Acomba	= "OPIG"; 	break;
			case "5206":	$Account_Num_Acomba	= "OPTIDU"; break;
			case "5212":	$Account_Num_Acomba	= "BJOP"; 	break;
			case "5216":	$Account_Num_Acomba	= "OPTP"; 	break;
			case "1329":	$Account_Num_Acomba	= "14170"; 	break;
			case "5227":	$Account_Num_Acomba	= "OPTDR"; 	break;
			case "1237":	$Account_Num_Acomba	= "OLAB"; 	break;
			case "1218":	$Account_Num_Acomba	= "EYEL"; 	break;
			case "1244":	$Account_Num_Acomba	= "11100"; 	break;
			case "1219":	$Account_Num_Acomba	= "SMIO"; 	break;
			case "1250":	$Account_Num_Acomba	= "LUND"; 	break;
			case "1170":	$Account_Num_Acomba	= "GLAH"; 	break;
			case "1173":	$Account_Num_Acomba	= "CENV"; 	break;
			case "5222":	$Account_Num_Acomba	= "COMP"; 	break;
			case "1142":	$Account_Num_Acomba	= "25134"; 	break;
			case "1252":	$Account_Num_Acomba	= "HABO"; 	break;
			case "1253":	$Account_Num_Acomba	= "SPEB"; 	break;
			case "1254":	$Account_Num_Acomba	= "OLAB"; 	break;
			case "1271":	$Account_Num_Acomba	= "EYEL"; 	break;
			case "1282":	$Account_Num_Acomba	= "EYESH"; 	break;
			case "1279":	$Account_Num_Acomba	= "12168"; 	break;
			case "1280":	$Account_Num_Acomba	= "OPTCE"; 	break;
			case "1281":	$Account_Num_Acomba	= "OPTCP"; 	break;
			case "1318":	$Account_Num_Acomba	= "STUO"; 	break;
			case "1323":	$Account_Num_Acomba	= "COME"; 	break;
			case "1326":	$Account_Num_Acomba	= "GABO"; 	break;
			case "64363":   $Account_Num_Acomba = "16170"; 	break;
			case "64362":   $Account_Num_Acomba = "16160";	break;
			case "95":	    $Account_Num_Acomba = "19110"; 	break;
			case "346":	    $Account_Num_Acomba = "10100";	break;
			case "226-1":   $Account_Num_Acomba = "28120"; 	break;
			case "208":     $Account_Num_Acomba = "24130";	break;
			case "207-1":   $Account_Num_Acomba = "28130"; 	break;
			case "35796":   $Account_Num_Acomba = "18130";	break;
			case "226-2":   $Account_Num_Acomba = "28120"; 	break;
			case "AQ5020":  $Account_Num_Acomba = "11130"; 	break;
			case "64364":   $Account_Num_Acomba = "16180";	break;
			case "217":	    $Account_Num_Acomba = "16110";	break;
			case "64365":   $Account_Num_Acomba = "16120"; 	break;
			case "205":	    $Account_Num_Acomba	= "11160"; 	break;
			case "206-2":   $Account_Num_Acomba	= "17130";	break;
			case "AQ0800":  $Account_Num_Acomba	= "12166"; 	break;
			case "836":	    $Account_Num_Acomba	= "10105";	break;
			case "218":	    $Account_Num_Acomba	= "30220"; 	break;
			case "AQ7200":  $Account_Num_Acomba	= "28140";	break;
			case "252":	    $Account_Num_Acomba	= "22140"; 	break;
			case "OR1080":  $Account_Num_Acomba	= "24210";	break;
			case "16190":   $Account_Num_Acomba	= "16190"; 	break;
			case "24200":   $Account_Num_Acomba	= "24200"; 	break;
			case "24170":   $Account_Num_Acomba	= "24170"; 	break;
			case "24190":   $Account_Num_Acomba	= "24190"; 	break;
			case "269":	    $Account_Num_Acomba	= "16150"; 	break;
			case "AQ1670":  $Account_Num_Acomba	= "12168"; 	break;
			case "294":	    $Account_Num_Acomba	= "28110"; 	break;
			case "91007":   $Account_Num_Acomba	= "12240"; 	break;
			case "317":	    $Account_Num_Acomba	= "12150"; 	break;
			case "311":	    $Account_Num_Acomba	= "25130"; 	break;
			case "312":	    $Account_Num_Acomba	= "25120"; 	break;
			case "320":	    $Account_Num_Acomba	= "13120"; 	break;
			case "321":	    $Account_Num_Acomba	= "11170"; 	break;
			case "217":	    $Account_Num_Acomba	= "16110"; 	break;
			case "257":	    $Account_Num_Acomba	= "28170"; 	break;
			case "339":	    $Account_Num_Acomba	= "15100"; 	break;
			case "353":	    $Account_Num_Acomba	= "10130"; 	break;
			case "359":	    $Account_Num_Acomba	= "14170"; 	break;
			case "AQ4580":  $Account_Num_Acomba	= "13210"; 	break;
			case "371":	    $Account_Num_Acomba	= "18140"; 	break;
			case "373":	    $Account_Num_Acomba	= "21250"; 	break;
			case "381":	    $Account_Num_Acomba	= "24110"; 	break;
			case "384":	    $Account_Num_Acomba	= "34100"; 	break;
			case "AQ0050":  $Account_Num_Acomba	= "11115"; 	break;
			case "390":     $Account_Num_Acomba	= "12210"; 	break;
			case "og1380":  $Account_Num_Acomba	= "17110"; 	break;
			case "RA201":   $Account_Num_Acomba	= "20500"; 	break;
			case "396":	    $Account_Num_Acomba	= "25110"; 	break;
			case "398":	    $Account_Num_Acomba	= "12130"; 	break;
			case "256":	    $Account_Num_Acomba	= "23100"; 	break;
			case "407":	    $Account_Num_Acomba	= "22120"; 	break;
			case "OG 1335": $Account_Num_Acomba	= "10110"; 	break;
			case "430":	    $Account_Num_Acomba	= "21220"; 	break;
			case "436":	    $Account_Num_Acomba	= "22150"; 	break;
			case "451":	    $Account_Num_Acomba	= "25200"; 	break;
			case "458":	    $Account_Num_Acomba	= "25210"; 	break;
			case "OR1050":  $Account_Num_Acomba	= "16100"; 	break;
			case "468":	    $Account_Num_Acomba	= "24230"; 	break;
			case "4710":    $Account_Num_Acomba	= "17100"; 	break;
			case "L0004":   $Account_Num_Acomba	= "21160"; 	break;
			case "500":	    $Account_Num_Acomba	= "27300"; 	break;
			case "AQ9120":	$Account_Num_Acomba	= "14150"; 	break;
			case "AQ6370":	$Account_Num_Acomba	= "16130"; 	break;
			case "1635":	$Account_Num_Acomba	= "27100"; 	break;
			case "1865":	$Account_Num_Acomba	= "14120"; 	break;
			case "1090":	$Account_Num_Acomba	= "11152"; 	break;
			case "561":	    $Account_Num_Acomba	= "12170"; 	break;
			case "3735":	$Account_Num_Acomba	= "22110"; 	break;
			case "600":		$Account_Num_Acomba	= "14140"; 	break;
			case "601":		$Account_Num_Acomba	= "21150"; 	break;
			case "602":		$Account_Num_Acomba	= "26100"; 	break;
			case "620":		$Account_Num_Acomba	= "24120"; 	break;
			case "622":		$Account_Num_Acomba	= "28150"; 	break;
			case "625":		$Account_Num_Acomba	= "25100"; 	break;
			case "626":		$Account_Num_Acomba	= "11100"; 	break;
			case "636":		$Account_Num_Acomba	= "29810"; 	break;
			case "637":		$Account_Num_Acomba	= "29800"; 	break;
			case "648":		$Account_Num_Acomba	= "14130"; 	break;
			case "651":		$Account_Num_Acomba	= "14180"; 	break;
			case "652":		$Account_Num_Acomba	= "13110"; 	break;
			case "663":		$Account_Num_Acomba	= "11122"; 	break;
			case "666":		$Account_Num_Acomba	= "25192"; 	break;
			case "670":		$Account_Num_Acomba	= "12220"; 	break;
			case "64366":	$Account_Num_Acomba	= "16125"; 	break;
			case "694":		$Account_Num_Acomba	= "32500"; 	break;
			case "738":		$Account_Num_Acomba	= "24131"; 	break;
			case "744":		$Account_Num_Acomba	= "OLAB"; 	break;
			case "749":		$Account_Num_Acomba	= "30000"; 	break;
			case "767":		$Account_Num_Acomba	= "28123"; 	break;
			case "850":		$Account_Num_Acomba	= "30230"; 	break;
			case "881":		$Account_Num_Acomba	= "14100"; 	break;
			case "907":		$Account_Num_Acomba	= "28125"; 	break;
			case "908":		$Account_Num_Acomba	= "10010"; 	break;
			case "1141":	$Account_Num_Acomba	= "25134"; 	break;
			case "1145":	$Account_Num_Acomba	= "28177"; 	break;
			case "1157":	$Account_Num_Acomba	= "ABBV"; 	break;
			case "1275":	$Account_Num_Acomba	= "32500"; 	break;
	
		}*/
		$queryAcombaAcctNum = "SELECT acomba_account_num from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		echo $queryAcombaAcctNum  . '<br>';
		$ResultAcombaAcctNum=mysql_query($queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysql_error());
		$DataAcomba=mysql_fetch_array($ResultAcombaAcctNum);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		echo '<br>Acomba order num: ' . $Account_Num_Acomba;
		
		//Amener account_num a 8 caractères en ajoutant des espaces à droite
		$Longeur_Account = strlen($Account_Num_Acomba);
		$account_num = $Account_Num_Acomba;	
		for ($counter = $Longeur_Account ; $counter < 8; $counter++) {
		$account_num .= ' ';
		}
		
		//Formatter la date en format AAMMJJ
		$date_shipped = date("ymd", strtotime($orderItem["order_date_shipped"]));	
		
		
			
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
		case "ifcclub":		$Prefix_Facture = "001"; break;//IFC France
		case "ifcclubca":	$Prefix_Facture = "002"; break;//IFC.ca
		case "ifcclubus":	$Prefix_Facture = "003"; break;//IFC.us
		case "directlens":	$Prefix_Facture = "004"; break;//Direct-Lens
		case "lensnetclub":	$Prefix_Facture = "005"; break;//Lensnet Club
		case "aitlensclub":	$Prefix_Facture = "006"; break;//AIT lens club
		default:    		$Prefix_Facture = "000"; break;//Source de la commande inconnue
		} 
		
		
		
		//Formatter le numéro de commande pour atteindre 12 caractères, on ajout des espaces du coté droit
		$The_order_num = $Prefix_Facture .'-'. $orderItem["order_num"];	
		$Longeur_Ordernum = strlen($The_order_num);
		for ($counter = $Longeur_Ordernum ; $counter < 12; $counter++) 
		{
		$The_order_num .= ' ';
		}
					
		//Calcul de l'escompte accordé au client
		$escompte = $orderItem["order_product_price"] - $orderItem["order_product_discount"];
		$order_total_sans_escompte = $orderItem["order_total"] + $orderItem["order_shipping_cost"]  + $escompte;
	
	
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche
		//$order_total = $orderItem["order_total"] ;	
		$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"];	
		$order_total = money_format('%.2n',$order_total);	
		$Longeur_ordertotal= strlen($order_total);
		
		
		for ($counter = $Longeur_ordertotal ; $counter < 9; $counter++) 
		{
		$order_total = ' ' . $order_total;
		}

		//Formatter le montant total de la commande (AVANT ESCOMPTE)  Amener a 18 caractères, ajouter des 'blancs' à gauche
		$Longeur_ordertotal_sans_escompte= strlen(money_format('%.2n',$order_total_sans_escompte));
		$espaceaajouter = "";
		for ($counter = $Longeur_ordertotal_sans_escompte ; $counter < 18; $counter++)
		{
		$espaceaajouter .= ' ';
		}
		$order_total_sans_escompte =money_format('%.2n',$order_total_sans_escompte);
		$order_total_sans_escompte = $espaceaajouter. $order_total_sans_escompte;
		
		
		//Numero de vendeur (utilisé pour identifier le Lab)
		switch($orderItem["lab"]){
			//Direct-Lens
			case "1":	$Lab_Vendeur	= "01"; $Vente_Provient_De = 'DL'; 		break;//Vision Optic Technologies
			case "3":	$Lab_Vendeur	= "02";	$Vente_Provient_De = 'DL';		break;//Directlab Saint-Catharines
			case "21":	$Lab_Vendeur	= "03";	$Vente_Provient_De = 'DL';		break;//Directlab trois-rivieres
			case "22":	$Lab_Vendeur	= "04";	$Vente_Provient_De = 'DL';		break;//Directlab Drummondville
			case "40":	$Lab_Vendeur	= "16";	$Vente_Provient_De = 'DL';		break;//Directlab Italia
			case "41":	$Lab_Vendeur	= "17";	$Vente_Provient_De = 'DL';		break;//Directlab USA
			case "43":	$Lab_Vendeur	= "19";	$Vente_Provient_De = 'DL';		break;//Directlab Pacific
			case "45":	$Lab_Vendeur	= "21";	$Vente_Provient_De = 'DL';		break;//Directlab Suisse
			case "46":	$Lab_Vendeur	= "22"; $Vente_Provient_De = 'DL';		break;//Directlab Illinois
			case "36":	$Lab_Vendeur	= "12";	$Vente_Provient_De = 'DL';		break;//Directlab Atlantic
			case "50":	$Lab_Vendeur	= "24"; $Vente_Provient_De = 'DL';		break;//Directlab Eagle
			//Lensnet
			case "28":	$Lab_Vendeur	= "06";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Quebec
			case "29":	$Lab_Vendeur	= "07";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Ontario
			case "31":	$Lab_Vendeur	= "08";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Elite
			case "32":	$Lab_Vendeur	= "09";	$Vente_Provient_De = 'LN';		break;//Lensnet Club USA
			case "33":	$Lab_Vendeur	= "10";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Atlantic
			case "34":	$Lab_Vendeur	= "11";	$Vente_Provient_De = 'LN';		break;//Lensnet Club West
			case "38":	$Lab_Vendeur	= "14";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Afrique de l'Ouest
			case "42":	$Lab_Vendeur	= "18";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Italia
			case "44":	$Lab_Vendeur	= "20";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Pacific
			//Autres
			case "37":	$Lab_Vendeur	= "13";	$Vente_Provient_De = '??';		break;//IFC Club
			case "47":	$Lab_Vendeur	= "23"; $Vente_Provient_De = '??';		break;//AIT lens Club
			default:    $Lab_Vendeur	= "00";	$Vente_Provient_De = '??';		break;//Valeur par défaut si le lab est inconnu: 00
		}
		
		

		
		
		//Définir si la commande vient de Direct-Lens ou de Lensnet Club
		switch($Vente_Provient_De)
		{
			case "LN":	$CodeIdentifiant = "404100";		break;//Lensnet club
		    case "DL":	$CodeIdentifiant = "405100";		break;//Direct-lens
		    case "DL":	$CodeIdentifiant = "";				break;//Direct-lens
			default:    $CodeIdentifiant = "000000";		break;//Source de la commande inconnue
		}
		
		
		$outputstring.= 'F'		   .  $account_num 		.  $date_shipped .	$The_order_num	.  $order_total  .  "99" . $Lab_Vendeur .  "\r\n" 	;//Ligne 1
		$outputstring.= 'T 110100' .   $order_total 		.  "\r\n" 	;// (compte client)
		$outputstring.= 'T '	   .  $CodeIdentifiant  . $order_total_sans_escompte.   "\r\n" 	;// (vente)    
		
		
		//Cette écriture est  facultative, on l'ajoute seulement SI le client a eu une escompte
		if ($escompte >0)
		{
		//Formatter l'escompte pour atteindre 9 caracteres, ajouter blanc à gauche
		$Longeur_escompte= strlen(money_format('%.2n',$escompte));
		$espace_a_ajouter = "";
		for ($counter = $Longeur_escompte ; $counter < 9; $counter++) 
		{
		$espace_a_ajouter .= ' ' ;		
		}
		$outputstring.= 'T 601000'.  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
		
	}//End while			
return $outputstring;			
}//End function







function export_credit_acomba($mcred_primary_key){
$Query="select memo_credits.*, accounts.account_num, accounts.main_lab from memo_credits, accounts 
 WHERE memo_credits.mcred_acct_user_id = accounts.user_id AND  memo_credits.mcred_primary_key = $mcred_primary_key"; //Get Credits Data
$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($DataCredit=mysql_fetch_array($Result)){

		//Amener account_num a 8 caractères en ajoutant des espaces à droite
		//TO DO DEVRA UTILISER LE NUMÉRO DE CLIENT FOURNIT PAR ALEX GRACE AU MAPPIN CLIENTS DL/CLIENTS ACOMBA
		$Longeur_Account = strlen($DataCredit["account_num"]);
		$account_num = $DataCredit["account_num"];	
		for ($counter = $Longeur_Account ; $counter < 8; $counter++) {
		$account_num .= ' ';
		}
		
		//Formatter la date en format AAMMJJ
		$date_du_credit = date("ymd", strtotime($DataCredit["mcred_date"]));	

		//Formatter le numéro de commande pour atteindre 12 caractères, on ajout des espaces du coté droit
		$Longeur_Ordernum = strlen($DataCredit["mcred_order_num"]);
		$The_order_num = $DataCredit["mcred_order_num"];	
		for ($counter = $Longeur_Ordernum ; $counter < 12; $counter++) 
		{
		$The_order_num .= ' ';
		}	
		
		
		//Re-Formatter le montant total du crédit   Amener a 18 caractères, ajouter des 'blancs' à gauche
		$Longeur_ordertotal= strlen($DataCredit["mcred_abs_amount"]);
		$order_total_longeur_18 = $DataCredit["mcred_abs_amount"];	
		for ($counter = $Longeur_ordertotal ; $counter < 18; $counter++) 
		{
		$order_total_longeur_18 = ' ' . $order_total_longeur_18;
		}
		
		//Formatter le montant total du crédit   Amener a 9 caractères, ajouter des 'blancs' à gauche
		$Longeur_ordertotal= strlen($DataCredit["mcred_abs_amount"]);
		$order_total = $DataCredit["mcred_abs_amount"];	
		for ($counter = $Longeur_ordertotal ; $counter < 9; $counter++) 
		{
		$order_total = ' ' . $order_total;
		}
		
		//Numéro de vendeur (utilisé pour identifier le Lab)
		switch($DataCredit["main_lab"]){
			//Direct-Lens
			case "1":	$Lab_Vendeur	= "01"; $Vente_Provient_De = 'DL'; 		break;//Vision Optic Technologies
			case "3":	$Lab_Vendeur	= "02";	$Vente_Provient_De = 'DL';		break;//Directlab Saint-Catharines
			case "21":	$Lab_Vendeur	= "03";	$Vente_Provient_De = 'DL';		break;//Directlab trois-rivieres
			case "22":	$Lab_Vendeur	= "04";	$Vente_Provient_De = 'DL';		break;//Directlab Drummondville
			case "40":	$Lab_Vendeur	= "16";	$Vente_Provient_De = 'DL';		break;//Directlab Italia
			case "41":	$Lab_Vendeur	= "17";	$Vente_Provient_De = 'DL';		break;//Directlab USA
			case "43":	$Lab_Vendeur	= "19";	$Vente_Provient_De = 'DL';		break;//Directlab Pacific
			case "45":	$Lab_Vendeur	= "21";	$Vente_Provient_De = 'DL';		break;//Directlab Suisse
			case "46":	$Lab_Vendeur	= "22"; $Vente_Provient_De = 'DL';		break;//Directlab Illinois
			case "36":	$Lab_Vendeur	= "12";	$Vente_Provient_De = 'DL';		break;//Directlab Atlantic
			case "50":	$Lab_Vendeur	= "24"; $Vente_Provient_De = 'DL';		break;//Directlab Eagle
			//Lensnet
			case "28":	$Lab_Vendeur	= "06";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Quebec
			case "29":	$Lab_Vendeur	= "07";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Ontario
			case "31":	$Lab_Vendeur	= "08";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Elite
			case "32":	$Lab_Vendeur	= "09";	$Vente_Provient_De = 'LN';		break;//Lensnet Club USA
			case "33":	$Lab_Vendeur	= "10";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Atlantic
			case "34":	$Lab_Vendeur	= "11";	$Vente_Provient_De = 'LN';		break;//Lensnet Club West
			case "38":	$Lab_Vendeur	= "14";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Afrique de l'Ouest
			case "42":	$Lab_Vendeur	= "18";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Italia
			case "44":	$Lab_Vendeur	= "20";	$Vente_Provient_De = 'LN';		break;//Lensnet Club Pacific
			//Autres
			case "37":	$Lab_Vendeur	= "13";	$Vente_Provient_De = '??';		break;//IFC Club
			case "47":	$Lab_Vendeur	= "23"; $Vente_Provient_De = '??';		break;//AIT lens Club
			default:    $Lab_Vendeur	= "00";	$Vente_Provient_De = '??';		break;//Valeur par défaut si le lab est inconnu: 00
		}
		
		//Débute par un C pour Crédit	
		$outputstring.= 'C'		   .  $account_num  		  .  $date_du_credit .	$The_order_num	.  $order_total  .  "99" . $Lab_Vendeur .  "\r\n" 	;//Validé	
		$outputstring.= 'T 110100' .  $order_total_longeur_18 .  "\r\n"; //(compte client) Validé
		$outputstring.= 'T 602000' .  $order_total	  		  .  "\r\n"; // (vente)   Validé  
		
	}//End while			
return $outputstring;		
}//End function




function export_payment_acomba($primary_key){
$QueryPayments="select payments.*, accounts.account_num  from payments, accounts 
WHERE payments.user_id = accounts.user_id AND payments.primary_key = $primary_key"; //Get Payments Data

$ResultPayments=mysql_query($QueryPayments)	or die  ('I cannot select items because: ' . mysql_error());

	while ($DataPayments=mysql_fetch_array($ResultPayments)){
	
	//Formatter la date en format AAMMJJ
	$date_du_paiement = date("ymd", strtotime($DataPayments["pmt_date"]));	
			

	//Amener account_num a 8 caractères en ajoutant des espaces à droite
	//TO DO DEVRA UTILISER LE NUMÉRO DE CLIENT FOURNIT PAR ALEX GRACE AU MAPPIN CLIENTS DL/CLIENTS ACOMBA
	$Longeur_Account = strlen($DataPayments["account_num"]);
	$account_num = $DataPayments["account_num"];	
	for ($counter = $Longeur_Account ; $counter < 8; $counter++) {
	$account_num .= ' ';
	}		
	
	//Amener le montant du paiement  a 10 caractères en ajoutant des espaces à gauche
	$Longeur_montant_paiement = strlen($DataPayments["pmt_amount"]);
	$montant_paiement = $DataPayments["pmt_amount"];	
	for ($counter = $Longeur_montant_paiement ; $counter < 10; $counter++) {
	$montant_paiement = ' ' . $montant_paiement;
	}	
					
	//Débute par un P pour Payment	
	$outputstring.= 'P'	 .  $date_du_paiement . $account_num . $montant_paiement	 .  "\r\n" 	;//Validé			
	}//End while			
return $outputstring;		
}//End function
?>