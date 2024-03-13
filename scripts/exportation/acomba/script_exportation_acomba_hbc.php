<?php
//Afficher toutes les erreurs/avertissements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/../../../constants/ftp.constant.php');
require_once(__DIR__.'/../../../constants/mysql.constant.php');

//Créer le fichier CCimport pour HBC
include("../../../sec_connect.inc.php");//RDL:LNC, Direct-Lens/Prestige sont tous dans la bd direct54_lens
$today	   = date("ymd") . '_' . date("Gi") ;
$datedebut = date("Y-m-d");//"2018-06-18";
$datefin   = date("Y-m-d");//"2018-06-18";
$FichierestVide = 'oui';//pour identifier si le fichier est vide ou non, et donc si on doit le copier sur le ftp


//DATE HARD CODÉ
/*
$datedebut ="2019-04-03";
$datefin   ="2019-04-03";
*/

echo '<br><br>FichierestVide:' . $FichierestVide . '<br>';
$LigneCommentaire = 'Du ' . $datedebut . ' au ' . $datefin.'<br><br>' ;

echo $LigneCommentaire;
//CREATE EXPORT FILE//
$filename="../../../../../../../ftp_root/acomba/CCImport.HBC$today.001"; //Le fichier sera créé dans un dossier ou  l'utilisateur FTP (ehandfield) à accès [en écriture]
$fp=fopen($filename, "w");
$outputstring  = $LigneCommentaire .  "\r\n" ;//Ligne 1
$outputstring .= 'LFACT=12' .  "\r\n" 	;// Ligne 2 pour laisser savoir que nos order num auront 12 caractères
fwrite($fp,$outputstring);


//EXPORTATION DES COMMANDES SHIPPÉS
/* 
  1- Commandes Hbc
  2- Exportation des crédits HBC
*/


//1- HBC  //Ne pas exporter les commandes des comptes de reprises  et de Griffé-TR
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
 AND user_id NOT IN ('hbc_redo','redo_hbc')  AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('hbc')
 AND order_date_processed > '2018-01-01' 
 AND order_Date_shipped <> '0001-01-01' ORDER by order_num";
 
 //PRESCRIPT_LAB <> 10
 /*$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
 AND user_id NOT IN ('hbc_redo','redo_hbc')  AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('hbc')
 AND prescript_lab<>10
 AND order_date_processed > '2018-01-01' 
 AND order_Date_shipped <> '0001-01-01' ORDER by order_num";*/
 
echo $orderQuery;



$orderResult = mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount   = mysqli_num_rows($orderResult);

echo '<br>itemcount:'. $itemcount.'<br>'; 

while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	$outputstring=export_directlens_order_acomba($orderData[order_num]);
	
	if ($outputstring <> '')
		$FichierestVide = 'non';
	
	fwrite($fp,$outputstring);
}
echo '4P1- HBC Partie Verres Écriture #1/1';


//TODO: 
//1-Exporter les crédits 
//2-Faire en sorte que les crédits de 88666 NE SOIT PAS exportés dans ce fichier de commandes HBC


//Ajout des 2 sauts de ligne à la fin du fichier pour le terminer
$outputstring=  "\r\n". "\r\n";
fwrite($fp,$outputstring);
echo '<br><br>Output: ' . $outputstring;
//Close the file
fclose($fp);


if ($FichierestVide =='non')
{
	echo '<br><br>Copier du fichier sur le ftp de godaddy.'; 	
	//Ajouts pour copier  le  fichier sur le  ftp de Godaddy 2013-02-21
	$ftp_server = constant("GODADDY_FTP");
	$ftp_user = constant("FTP_USER_AGIASSON");
	$ftp_pass = constant("FTP_PASSWORD_AGIASSON");
	
	// set up a connection or die
	$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
	
	// try to login
	if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
		echo "Connected as $ftp_user@$ftp_server\n";
	} else {
		echo "Couldn't connect as $ftp_user\n";
	}
	
	// turn passive mode on
	ftp_pasv($conn_id, true);	
	//ftp_chdir($conn_id,"FROM_DL");
	
	$file		 = $filename;//"PrecisionOrderData-".$today.".csv";
	$remote_file = "CCImport.HBC$today.001";//"PrecisionOrderData-".$today.".csv";
	
	// upload a file
	if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
	 echo "successfully uploaded $file\n";
	} else {
	 echo "There was a problem while uploading $file\n";
	}
	
	ftp_close($conn_id);  
}else {
	echo '<br><br>Fichier non copié sur le ftp de  godaddy puisqu\'il est vide.';
}//End if file is not empty





////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Début des fonctions
/*
  1- export_directlens_order_acomba FACTURES Directlens (LAB paie le prix 'Interco')
  2- export_credit_acomba (CRÉDITS)
*/


//3- FACTURES HBC
//On utilise le prix elab plutot que que le prix de la commande
function export_directlens_order_acomba($order_num){


global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}	

$Query="SELECT orders.*,  accounts.account_num FROM orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' 
ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
	   //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		//Amener account_num a 8 caractères en ajoutant des espaces à droite
		$Longeur_Account = strlen($Account_Num_Acomba);
		$account_num = $Account_Num_Acomba;	
		for ($counter = $Longeur_Account ; $counter < 8; $counter++) {
		$account_num .= ' ';
		}
		//Formatter la date en format AAMMJJ
		$date_processed = date("ymd", strtotime($orderItem["order_date_shipped"]));	
		
				//Formatter le nom du patient
		$PatientFirstName = $orderItem['order_patient_first'];
		$PatientLastName  = $orderItem['order_patient_last']; 
		$PatientContientChiffres = false;
		
		if (preg_match('#[0-9]#',$PatientFirstName))
		$PatientContientChiffres    = true;
		
		if (preg_match('#[0-9]#',$PatientLastName))
		$PatientContientChiffres    = true;
	

		if (($PatientFirstName == '') && ($PatientLastName == '')){
		$nom_patient = '';	
		}else{
		
			if($PatientFirstName != ''){
			$nom_patient = substr($PatientFirstName,0,1) .  '.' .  substr($PatientLastName,0,5) ;	
			}else{
			$nom_patient = substr($PatientLastName,0,7) ;	
			}
		
		}
		
		
		if ($PatientContientChiffres){
			$nom_patient =  substr($PatientLastName,0,7) ;	
		}
		
		$nom_patient  = strtoupper($nom_patient);


		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
			case "hbc":			$Prefix_Facture = "9"; break;//HBC
			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		} 
		
		if (($orderItem["lab"] == 1) || ($orderItem["lab"] == 3)){
		$Prefix_Facture = $Prefix_Facture . '0'.  $orderItem["lab"]; 
		}else{
		//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
		$Prefix_Facture = $Prefix_Facture .  $orderItem["lab"]; 
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
		//$order_total_sans_escompte = 0.02 * $order_total_sans_escompte ;
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		echo '<br>Order total avant 2%: '. $order_total ;	
		//$order_total = 0.02 * $order_total;//2% de chaque commande
		$order_total = money_format('%.2n',$order_total);	
		echo '<br>Order total apres 2%: '. $order_total ;
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
	
		switch ($orderItem["currency"]){//Correspond a la ligne #3
		case "CA":	$CodeIdentifiant = '401100'; break;//Currency: CAD
		case "EUR":	$CodeIdentifiant = '403100'; break;//Currency: Euro 
		case "EU":	$CodeIdentifiant = '403100'; break;//Currency: Euro 
		case "US":	$CodeIdentifiant = '402100'; break;//Currency: US 
		default:   	$CodeIdentifiant = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
		case "CA":	$CompteClient = '110100'; break;//Currency: CAD
		case "EUR":	$CompteClient = '110300'; break;//Currency: Euro 
		case "EU":	$CompteClient = '110300'; break;//Currency: Euro 
		case "US":	$CompteClient = '110200'; break;//Currency: US 
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
		case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
		case "EUR":	$EscompteClient = '601200'; break;//Currency: Euro 
		case "EU":	$EscompteClient = '601200'; break;//Currency: Euro 
		case "US":	$EscompteClient = '601100'; break;//Currency: US 
		default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		 .	$The_order_num	.  $order_total  . ' 1' . '  ' . ' 1|' . $nom_patient .  "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    
		
		
		$queryFlag = "UPDATE ORDERS SET  transfered_to_acomba ='yes',  transfered_acomba_dln_customer ='yes', date_transfer_acomba = '$dateheure', date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	}//End while			
return $outputstring;			
}//End function Factures 











//CREDITS
function export_credit_acomba($mcred_primary_key){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$Query="select memo_credits.*, accounts.account_num, accounts.main_lab from memo_credits, accounts 
 WHERE memo_credits.mcred_acct_user_id = accounts.user_id AND  memo_credits.mcred_primary_key = $mcred_primary_key"; //Get Credits Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));

//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);

	while ($DataCredit=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate, main_lab from accounts WHERE user_id ='"  . $DataCredit["mcred_acct_user_id"]. "'" ;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		
		//Amener account_num a 8 caractères en ajoutant des espaces à droite
		$Longeur_Account = strlen($Account_Num_Acomba);
		$account_num = $Account_Num_Acomba;	
		for ($counter = $Longeur_Account ; $counter < 8; $counter++) {
		$account_num .= ' ';
		}
		
		//Formatter la date en format AAMMJJ
		$date_du_credit = date("ymd", strtotime($DataCredit["mcred_date"]));	

		$queryOrderFrom = "SELECT order_from, lab, order_patient_first,order_patient_last FROM orders WHERE order_num = " .$DataCredit["mcred_order_num"];
		$ResultOrderFrom=mysqli_query($con,$queryOrderFrom)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataOrderFrom=mysqli_fetch_array($ResultOrderFrom,MYSQLI_ASSOC);
		
		
		$queryPatient  = "SELECT patient_first_name ,patient_last_name  FROM memo_credits WHERE mcred_order_num = " .$DataCredit["mcred_order_num"];
		$ResultPatient = mysqli_query($con,$queryPatient)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Datapatient   = mysqli_fetch_array($ResultPatient,MYSQLI_ASSOC);
		
		
		//Formatter le nom du patient
		$PatientFirstName = $Datapatient['patient_first_name'];
		$PatientLastName  = $Datapatient['patient_last_name']; 
		$PatientContientChiffres = false;
		
		if (preg_match('#[0-9]#',$PatientFirstName))
		$PatientContientChiffres    = true;
		
		if (preg_match('#[0-9]#',$PatientLastName))
		$PatientContientChiffres    = true;
	

		if (($PatientFirstName == '') && ($PatientLastName == '')){
		$nom_patient = '';	
		}else{
		
			if ($PatientFirstName != ''){
			$nom_patient = substr($PatientFirstName,0,1) .  '.' .  substr($PatientLastName,0,5) ;	
			}else{
			$nom_patient = substr($PatientLastName,0,7) ;	
			}
		
		
		}
		
		if ($PatientContientChiffres){
		$nom_patient =  substr($PatientLastName,0,7) ;	
		}
		
		$nom_patient  = strtoupper($nom_patient);

	
	 
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($DataOrderFrom["order_from"]){
			case "hbc":			$Prefix_Facture = "9"; break;//HBC
			default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
		} 

		if (($DataOrderFrom["lab"] == 1) || ($DataOrderFrom["lab"] == 3)){
		$Prefix_Facture = $Prefix_Facture . '0'.  $DataAcomba["main_lab"]; 
		}else{
		//Ajouter le numéro de lab au préfixe de la facture pour identifier le lab
		$Prefix_Facture = $Prefix_Facture .  $DataAcomba["main_lab"]; 
		}
			
		//Formatter le numéro de commande pour atteindre 12 caractères, on ajout des espaces du coté droit
		$OrderNumComplet = $Prefix_Facture . '-'. substr($DataCredit["mcred_memo_num"],1,8);
		$Longeur_Ordernum = strlen($OrderNumComplet);
		for ($counter = $Longeur_Ordernum ; $counter < 12; $counter++) 
		{
		$OrderNumComplet .= ' ';
		}	
		
		//Terme de paiement
		switch ($DataAcomba[account_rebate]){
		case "0":	$TermePaiement = '1'; break;//Account rebate 0%
		case "5":	$TermePaiement = '5'; break;//Account rebate 5%
		case "15":	$TermePaiement = '15'; break;//Account rebate 15%
		case "20":	$TermePaiement = '20'; break;//Account rebate 20% 
		case "25":	$TermePaiement = '25'; break;//Account rebate 25% 
		case "30":	$TermePaiement = '30'; break;//Account rebate 30%
		case "35":	$TermePaiement = '35'; break;//Account rebate 35% 
		case "45":	$TermePaiement = '45'; break;//Account rebate 50% 
		case "50":	$TermePaiement = '50'; break;//Account rebate 50% 
		case "52":	$TermePaiement = '52'; break;//Account rebate 50% Eye-Recommend 
		default:   	$TermePaiement = '00'; break;//Account rebate non mappé%		
		}
		
		

		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;

		
		//Re-Formatter le montant total du crédit   Amener a 18 caractères, ajouter des 'blancs' à gauche
		$order_total_longeur_18 = $DataCredit["mcred_abs_amount"];
		
		//echo '<br>Order total longeur 18:  credit AVANT modif:' . $order_total_longeur_18;
		switch ($TermePaiement){
			case " 0":	$order_total_longeur_18 = $order_total_longeur_18;  $TermePaiement =' 1';	          break;//Account rebate 0%
			case " 1":	$order_total_longeur_18 = $order_total_longeur_18;  					              break;//Account rebate 0%
			case " 5":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.05); break;//Account rebate 5%
			case "15":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.15); break;//Account rebate 15%
			case "20":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.20); break;//Account rebate 20% 
			case "25":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.25); break;//Account rebate 25% 
			case "30":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.30); break;//Account rebate 30%
			case "35":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.35); break;//Account rebate 35% 
			case "45":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.45); break;//Account rebate 45% 
			case "50":	$order_total_longeur_18 = $order_total_longeur_18 - ($order_total_longeur_18 * 0.50); break;//Account rebate 50% 
			default:   	$order_total_longeur_18 = $order_total_longeur_18;									  break;//Account rebate non mappé%		
		}
		$order_total_longeur_18 = money_format('%.2n',$order_total_longeur_18);
		$Longeur_ordertotal= strlen($order_total_longeur_18);
		for ($counter = $Longeur_ordertotal ; $counter < 18; $counter++) 
		{
		$order_total_longeur_18 = ' ' . $order_total_longeur_18;
		}
	
		//Formatter le montant total du crédit   Amener a 9 caractères, ajouter des 'blancs' à gauche
		$order_total = $DataCredit["mcred_abs_amount"];	
		$order_total_avant_escompte = $order_total;	

	

	switch ($TermePaiement){
		case " 1":	$order_total = $order_total; 						 break;//Account rebate 0%
		case " 5":	$order_total = $order_total - ($order_total * 0.05); break;//Account rebate 5%
		case "15":	$order_total = $order_total - ($order_total * 0.15); break;//Account rebate 15%
		case "20":	$order_total = $order_total - ($order_total * 0.20); break;//Account rebate 20% 
		case "25":	$order_total = $order_total - ($order_total * 0.25); break;//Account rebate 25% 
		case "30":	$order_total = $order_total - ($order_total * 0.30); break;//Account rebate 30%
		case "35":	$order_total = $order_total - ($order_total * 0.35); break;//Account rebate 35% 
		case "45":	$order_total = $order_total - ($order_total * 0.45); break;//Account rebate 45% 
		case "50":	$order_total = $order_total - ($order_total * 0.50); break;//Account rebate 50% 
		default:   	$order_total = $order_total;						 break;//Account rebate non mappé%		
	}
	$order_total_avant_escompte = money_format('%.2n',$order_total_avant_escompte);	
	$bk_total_avant_escompte = $order_total_avant_escompte;
	echo '<br>order_total_avant_escompte: ' . $order_total_avant_escompte ;
	$Longeur_order_total_avant_escompte= strlen($order_total_avant_escompte);
		
		for ($counter = $Longeur_order_total_avant_escompte ; $counter < 9; $counter++) 
		{
		$order_total_avant_escompte = ' ' . $order_total_avant_escompte;
		}
	
	echo '<br>order_total_avant_escompte: ' . $order_total_avant_escompte ;
	
	$order_total = money_format('%.2n',$order_total);
	$bk_total = $order_total;	
	$Longeur_ordertotal= strlen($order_total);
		
		for ($counter = $Longeur_ordertotal ; $counter < 9; $counter++) 
		{
		$order_total = ' ' . $order_total;
		}
		
		$Montant_escompte = $bk_total_avant_escompte -  $bk_total  ;
		$Montant_escompte = money_format('%.2n',$Montant_escompte);	
		
		
		$Longeur_montant_escompte = strlen($Montant_escompte);
		for ($counter = $Longeur_montant_escompte ; $counter < 18; $counter++) 
		{
		$Montant_escompte = ' ' . $Montant_escompte;
		}
		
		$queryCreditCurrency = "SELECT currency from accounts where user_id = '" . $DataCredit["mcred_acct_user_id"] . "'";
		$ResultCreditCurrency=mysqli_query($con,$queryCreditCurrency)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataCreditCurrency=mysqli_fetch_array($ResultCreditCurrency,MYSQLI_ASSOC);
		
		switch ($DataCreditCurrency[currency]){		//Correspond a la ligne #2(compte client)
			case "CA":	$CompteClient = '110100'; break;//Currency: CAD
			case "EUR":	$CompteClient = '110300'; break;//Currency: Euro 
			case "EU":	$CompteClient = '110300'; break;//Currency: Euro 
			case "US":	$CompteClient = '110200'; break;//Currency: US 
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
				
		switch ($DataCreditCurrency[currency]){//Correspond a la ligne 4(escompte client)
			case "CA":	$EscompteClient = '401100'; break;//Currency: CAD
			case "EUR":	$EscompteClient = '403100'; break;//Currency: Euro 
			case "EU":	$EscompteClient = '403100'; break;//Currency: Euro 
			case "US":	$EscompteClient = '402100'; break;//Currency: US 
		default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
//Débute par un C pour Crédit	
$outputstring.= 'C'		   .  $date_du_credit   .  $account_num 		   .  $OrderNumComplet	.  $order_total . $TermePaiement . '  ' . $TermePaiement . '|' . $nom_patient .   "\r\n" 	;//Ligne 1
$outputstring.= 'T ' 	   .  $CompteClient 	.  $order_total_longeur_18 .  "\r\n"; //(compte client) //Ligne 2	
$outputstring.= 'T ' 	   .  $EscompteClient   .  $order_total_avant_escompte	   .  "\r\n"; //(vente)    //Ligne 3
		
		switch ($DataCreditCurrency[currency]){//Correspond a la ligne 4(escompte client)
			case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
			case "EUR":	$EscompteClient = '601200'; break;//Currency: Euro 
			case "EU":	$EscompteClient = '601200'; break;//Currency: Euro 
			case "US":	$EscompteClient = '601100'; break;//Currency: US 
			default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}

		$outputstring.= 'T ' 	   . $EscompteClient    . $Montant_escompte . "\r\n"; //    //Ligne 4
		
		 $queryFlag = "UPDATE memo_credits SET  transfered_acomba_dln_customer ='yes', date_transfer_acomba_dln_customer = '$dateheure'  WHERE mcred_primary_key  =  " .$DataCredit["mcred_primary_key"];
		 $resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));	
	}//End while			
return $outputstring;		
}//End function





?>