<?php
//Afficher toutes les erreurs/avertissements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/../../../constants/ftp.constant.php');
require_once(__DIR__.'/../../../constants/mysql.constant.php');

//Créer le fichier CCimportEDLL pour exporter les Entrepot de la lunette vers le Logiciel Comptable Acomba
include("../../../sec_connectEDLL.inc.php");//RDL:LNC, Direct-Lens/Prestige sont tous dans la bd direct54_lens
$today		= date("ymd") . '_' . date("Gi") ;
$datedebut  = date("Y-m-d");//"2013-02-21";
$datefin    = date("Y-m-d");//"2013-02-24";
$FichierestVide = 'oui';//pour identifier si le fichier est vide ou non, et donc si on doit le copier sur le ftp

//DATE HARD CODÉ
//$datedebut ="2016-01-11";
//$datefin   ="2016-01-11";


echo '<br><br>FichierestVide:' . $FichierestVide . '<br>';
$LigneCommentaire = 'Du ' . $datedebut . ' au ' . $datefin;

echo $LigneCommentaire;

//CREATE EXPORT FILE//
//$filename="../acomba/FROM DIRECT-LENS/CCImport.EDLL.C$today.001";
$filename="../../../../../../../ftp_root/acomba/CCImport.EDLL$today.001"; //Le fichier sera créé dans un dossier ou  l'utilisateur FTP (ehandfield) à accès [en écriture]
$fp = fopen($filename, "w");
$outputstring  = $LigneCommentaire .  "\r\n" ;//Ligne 1
$outputstring .= 'LFACT=12' .  "\r\n" 	;// Ligne 2 pour laisser savoir que nos order num auront 12 caractères
fwrite($fp,$outputstring);

//EXPORTATION DES COMMANDES SHIPPÉS
/* 
  1- IFC.CA (Packages verres et monture)
  2- IFC.CA (Frames seulement)
  3- export_SAFE_order SAFETY SEULEMENT (on soustrait le montant déja payé par le client avant l'envoie a acomba) 
  4- Exportation des crédits 
*/

//Les House accounts sont nos propres comptes, on ne doit pas exporter ces données dans Acomba pour le moment
$HouseAccountQuery="SELECT  user_id FROM accounts WHERE house_account = 1 ";
echo '<br>'. $HouseAccountQuery;
$HouseAccountResult=mysqli_query($con,$HouseAccountQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$House_Accounts = '(';
$compteur = 0;
while ($HouseAccountData=mysqli_fetch_array($HouseAccountResult,MYSQLI_ASSOC)){
	if ($compteur > 0)
	$House_Accounts .= ' , ';
	$House_Accounts .= '\'' . $HouseAccountData[user_id] .'\'' ;
	$compteur   = $compteur+1;
}
$House_Accounts .= ')';




echo '<br>Début Partie 1-<br>';
//1- EDLL: Partie Packages verres + monture  
//*NE PAS EXPORTER LES COMMANDES DE L'ENTREPOT DE TR
//*Exporter seulement les Commandes entrées dans mon systeme à partir du 1er janvier 2016: order _date_processed"
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca')  AND user_id <> 'garantieatoutcasser'
AND order_product_type <> 'frame_stock_tray'
AND order_date_processed AND orders.lab IN (66) AND order_date_processed > '2016-01-01' AND user_id not in ('St.Catharines','redoifc','warehousehal','edmundston','vaudreuil','sorel','moncton','fredericton') ORDER by order_num";
echo '<br>Partie 1: '. $orderQuery . '<br><br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
//echo '4P1- EDLL Partie Verres Écriture #1/1';

echo '<br>Fin Partie 1-<br>';




//Partie 1.5 Commandes du compte Garantieatoutcasser SEULEMENT
echo '<br>Début Partie 1.5-<br>';
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id = 'garantieatoutcasser'  AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca')  
AND order_date_processed AND orders.lab IN (66) AND order_date_processed > '2016-01-01' ORDER by order_num";
echo '<br>Partie 1.5: '. $orderQuery . '<br><br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba_GTC($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br>Fin Partie 1.5<br>';




echo '<br>Début Partie 2-<br>';
//2-Partie 2 EDLL FRAMES SEULEMENT: 
//**NE PAS EXPORTER LES COMMANDES DE L'ENTREPOT DE TR, 
//**"Exporter seulement les Commandes entrées à partir du 1er janvier 2016"
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca') 
AND user_id not in ('entrepotframes')  AND order_date_processed > '2016-01-01'  AND user_id <> 'garantieatoutcasser'
AND order_product_type = 'frame_stock_tray' ORDER by order_num";
echo '<br>Partie 2:' . $orderQuery . '<br><br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_ifc_frames($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br>Fin Partie 2<br>';




echo '<br>Début Partie 3-<br>';
//3- SAFETY SEULEMENT EDLL (on soustrait le montant déja payé par le client avant l'envoie a acomba) 
$orderQuerySAFE="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id not in $House_Accounts  AND user_id NOT IN ('warehousehalsafe','edmundstonsafe','vaudreuilsafe','sorelsafe','monctonsafe','frederictonsafe') AND  transfered_acomba_dln_customer <> 'yes' AND order_from = 'safety'  AND order_date_shipped > '2019-04-01'  ORDER by order_num";
echo '<br>Partie 3:'. $orderQuerySAFE. '<br><br>';

$orderResultSafe=mysqli_query($con,$orderQuerySAFE)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResultSafe,MYSQLI_ASSOC)){
$outputstring=export_SAFE_order($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br>Fin Partie 3<br>';



echo '<br>Début Partie 4-<br>';
//4- EXPORTATION DES CRÉDITS EDLL
$QueryClients6667 = "SELECT user_id FROM `accounts` WHERE main_lab IN (66,59) AND approved = 'approved'";
echo '<br>'. $QueryClients6667;
$ResultClient6667 = mysqli_query($con,$QueryClients6667)	or die  ('I cannot select items because: ' . mysqli_error($con));
$Clients_6667 = '(';
$compteurEdll = 0;

while ($DataEDLL=mysqli_fetch_array($ResultClient6667,MYSQLI_ASSOC)){
if ($compteurEdll > 0)
$Clients_6667 .= ' , ';
$Clients_6667 .= '\'' . $DataEDLL[user_id] .'\'' ;
$compteurEdll   = $compteurEdll + 1;
} 

//LIGNE CI-DESSOUS DOIT ETRE MIS A JOUR QUAND ON CRÉÉ DES COMPTE SAFE EDLL
$Clients_6667  .= ",'entrepotsafe','safedr','lavalsafe',
'terrebonnesafe','sherbrookesafe','chicoutimisafe','longueuilsafe','levissafe','granbysafe','garagemp','BSG','villeshannon','gatineausafe','stjeromesafe'";
$Clients_6667 .= ')';

$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes' AND mcred_acct_user_id NOT IN ('warehousehal','warehousehalsafe','edmundston','edmundstonsafe','vaudreuil','vaudreuilsafe','sorel','sorelsafe','moncton','monctonsafe','fredericton','frederictonsafe')  AND mcred_date  <=  '$datefin' AND mcred_acct_user_id  NOT IN $House_Accounts AND mcred_acct_user_id  IN $Clients_6667 "; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}

echo '<br>Fin Partie 4<br>';


//Ajout des 2 sauts de ligne a la fin du fichier pour le terminer
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
	
	$file=$filename;
	$remote_file = "CCImport.EDLL.C$today.001";//"PrecisionOrderData-".$today.".csv";
	
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





//FUNCTIONS

// 1- FACTURES (autres que IFC)
function export_order_acomba($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}	
	
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){	
	   //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num_edll as acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		echo '<br>'. $queryAcombaAcctNum;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		$Buying_Group = $DataAcomba[buying_group];
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
		
		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE  payments.cclast4 <> '' AND  order_num = ". $order_num;
		echo '<br>' . $queryValiderPaiement;
		$ResultValiderPaiement=mysqli_query($con,$queryValiderPaiement)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataValiderPaiement=mysqli_fetch_array($ResultValiderPaiement,MYSQLI_ASSOC);
		if ($DataValiderPaiement[nbrPaiement] > 0)
		{
		$date_processed = date("ymd", strtotime($orderItem["order_date_processed"]));
		echo '<br>date ship:' . $order_num;
		}
		
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.caus
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
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
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
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
		switch ($orderItem["currency"]){//Correspond a la ligne #3
		//case "CA":$CodeIdentifiant = '401330';break;//Currency: CAD-->Nouveau GL donné par Éric 2016-01-12//Nouveau plan comptable changé le 11 juil 2016
		case "CA": $CodeIdentifiant = '   4700';break;//Currency: CAD-->//Nouveau GL/plan comptable changé le 11 juil 2016
		default:  $CodeIdentifiant = '000000';break;//Currency: Inconnue		
		}
		
		
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
			//case "CA":	$CompteClient = '110100'; break;//Currency: CAD// 
			case "CA":	$CompteClient = '   1121'; break;//Currency: CAD// Nouveau GL/plan comptable changé le 11 juil 2016
			default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
			//case "CA":	$EscompteClient = '401330'; break;//Currency: CAD -->Nouveau GL donné par Éric 2016-01-12
			case "CA":	$EscompteClient = '4700'; break;//Currency: CAD -->Nouveau GL/plan comptable changé le 11 juil 2016
			default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		//Terme de paiement
		switch ($DataAcomba['account_rebate']){
		case "0":	$TermePaiement = '1'; break;//Account rebate 0%
		case "5":	$TermePaiement = '5'; break;//Account rebate 5%
		case "15":	$TermePaiement = '15'; break;//Account rebate 15%
		case "20":	$TermePaiement = '20'; break;//Account rebate 20% 
		case "25":	$TermePaiement = '25'; break;//Account rebate 25% 
		case "30":	$TermePaiement = '30'; break;//Account rebate 30%
		case "35":	$TermePaiement = '35'; break;//Account rebate 35% 
		case "45":	$TermePaiement = '45'; break;//Account rebate 35% 
		case "50":	$TermePaiement = '50'; break;//Account rebate 50% 
		case "52":	$TermePaiement = '52'; break;//Account rebate 50% Eye-Recommend 
		default:   	$TermePaiement = '00'; break;//Account rebate non mappé%		
		}
		
		//Ajouts pour ne pas donner de rabais sur les commandes stock Direct-Lens
		if ($orderItem[order_product_type] =='stock_tray')
		$TermePaiement = '1'; 
		
		if ($orderItem[order_product_type] =='stock')
		$TermePaiement = '1'; 
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		 .	$The_order_num	.  $order_total . $TermePaiement  . '  ' . $TermePaiement. '|' .$nom_patient  .  "\r\n" 	;//Ligne 1
		//$outputstring.= 'T ' . $CompteClient  	 . $order_total    .  "\r\n" 	;// (compte client)
		$outputstring.= 'T' . $CompteClient  	 . $order_total  . "         LAB"			  .  "\r\n" 	;// (compte client)
		//$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)  
		$outputstring.= 'T' . $CodeIdentifiant  . $order_total_sans_escompte . "LAB".   "\r\n" 	;// (vente)   
		
		$queryFlag = "UPDATE ORDERS SET    transfered_acomba_dln_customer ='yes',  date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
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
		$outputstring.= 'T '. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
	}//End while			
return $outputstring;			
}//End function Factures (autre que IFC)














// 2- FACTURES Garantieatoutcasser 
function export_order_acomba_GTC($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}	
	
$Query="SELECT orders.*,  accounts.account_num FROM orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){	
	   //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num_edll as acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		echo '<br>'. $queryAcombaAcctNum;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		$Buying_Group = $DataAcomba[buying_group];
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
		
		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE  payments.cclast4 <> '' AND  order_num = ". $order_num;
		echo '<br>' . $queryValiderPaiement;
		$ResultValiderPaiement=mysqli_query($con,$queryValiderPaiement)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataValiderPaiement=mysqli_fetch_array($ResultValiderPaiement,MYSQLI_ASSOC);
		if ($DataValiderPaiement[nbrPaiement] > 0)
		{
		$date_processed = date("ymd", strtotime($orderItem["order_date_processed"]));
		echo '<br>date ship:' . $order_num;
		}
		
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.caus
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
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
		$order_total_sans_escompte = $orderItem["order_total"] + $orderItem["order_shipping_cost"]  + $escompte + 0.01;
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		
		$order_total2 = $orderItem["order_total"] + $orderItem["order_shipping_cost"];	
		$order_total2 = money_format('%.2n',$order_total2);	
		$Longeur_ordertotal2= strlen($order_total2);
		for ($counter = $Longeur_ordertotal2 ; $counter < 9; $counter++) 
		{
		$order_total2 = ' ' . $order_total2;
		}
		
		
		$order_total = 0.01;	
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
		switch ($orderItem["currency"]){//Correspond a la ligne #3
		//case "CA":$CodeIdentifiant = '401330';break;//Currency: CAD-->Nouveau GL donné par Éric 2016-01-12//Nouveau plan comptable changé le 11 juil 2016
		case "CA": $CodeIdentifiant = '   4700';break;//Currency: CAD-->//Nouveau GL/plan comptable changé le 11 juil 2016
		default:  $CodeIdentifiant = '000000';break;//Currency: Inconnue		
		}
		
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
			//case "CA":	$CompteClient = '110100'; break;//Currency: CAD// 
			case "CA":	$CompteClient = '1121'; break;//Currency: CAD// Nouveau GL/plan comptable changé le 11 juil 2016
			default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
			//case "CA":	$EscompteClient = '401330'; break;//Currency: CAD -->Nouveau GL donné par Éric 2016-01-12
			case "CA":	$EscompteClient = '4700'; break;//Currency: CAD -->Nouveau GL/plan comptable changé le 11 juil 2016
			default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		//Terme de paiement
		switch ($DataAcomba['account_rebate']){
			case "0":	$TermePaiement = '1'; break;//Account rebate 0%		
		}
		
		//Ajouts pour ne pas donner de rabais sur les commandes stock Direct-Lens
		if ($orderItem[order_product_type] =='stock_tray')
		$TermePaiement = '1'; 
		
		if ($orderItem[order_product_type] =='stock')
		$TermePaiement = '1'; 
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		 .	$The_order_num	.  $order_total . $TermePaiement  . '  ' . $TermePaiement. '|' .$nom_patient  .  "\r\n" 	;//Ligne 1
		$outputstring.= 'T   1121'   	 . $order_total . '         LAB'			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T' . $CodeIdentifiant  . $order_total_sans_escompte .  'LAB'.  "\r\n" 	;// (vente)    
		$outputstring.= 'T   2620'  	 . $order_total2 . '         LAB'  .  "\r\n" 	;// (compte client)
		
		$queryFlag = "UPDATE ORDERS SET    transfered_acomba_dln_customer ='yes',  date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
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
		$outputstring.= 'T '. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
	}//End while			
return $outputstring;			
}//End function Factures GTC (autre que IFC)





















// 8- FACTURES SAFE (on soustraira le paiment fait par le client avant l'exportation vers acomba)
function export_SAFE_order($order_num){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}	
	
echo '<br>3.1<br>';
	
$Query="SELECT orders.*,  accounts.account_num FROM orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
echo '<br>Requete: '.$query . '<br>';
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);

echo '<br>3.2<br>';

	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		
		echo '<br>3.3<br>';
		
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num_edll as acomba_account_num, account_rebate FROM accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
		
		echo '<br>3.4<br>';
		
		if ($PatientContientChiffres){
		$nom_patient =  substr($PatientLastName,0,7) ;	
		}
		$nom_patient  = strtoupper($nom_patient);

		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement FROM payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
		echo '<br>' . $queryValiderPaiement;
		$ResultValiderPaiement=mysqli_query($con,$queryValiderPaiement)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataValiderPaiement=mysqli_fetch_array($ResultValiderPaiement,MYSQLI_ASSOC);
		if ($DataValiderPaiement[nbrPaiement] > 0)
		{
		$date_processed = date("ymd", strtotime($orderItem["order_date_processed"]));
		echo '<br>date ship:' . $order_num;
		}	
		
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
		case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
		case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
		case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
		case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
		case "safety":	    $Prefix_Facture = "7"; break;//SAFETY
		case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
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
		
		
		$queryPaiementFaitparClient  = "SELECT payment_amount FROM payments_safety WHERE order_id = (SELECT primary_key FROM orders WHERE order_num =".  $orderItem["order_num"] . ')';
		$ResultPaiementFaitParClient = mysqli_query($con,$queryPaiementFaitparClient)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Nombre_Rows			     = mysqli_num_rows($ResultPaiementFaitParClient);
		
		if ($Nombre_Rows > 0){
		$DataPaiementFaitParClient   = mysqli_fetch_array($ResultPaiementFaitParClient,MYSQLI_ASSOC);
		$AmountAlreadyPaidByCustomer = $DataPaiementFaitParClient[payment_amount];
		}else
		$AmountAlreadyPaidByCustomer = 0;
		echo '<br>Montant deja payé:'. $AmountAlreadyPaidByCustomer;
		

		//Calcul de l'escompte accordé au client
		$escompte = $orderItem["order_product_price"] - $orderItem["order_product_discount"];
		$order_total_sans_escompte = $orderItem["order_total"] + $orderItem["order_shipping_cost"]  + $escompte - $AmountAlreadyPaidByCustomer;
		echo '<br>3.5<br>';

		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"] - $AmountAlreadyPaidByCustomer;	
		$order_total = money_format('%.2n',$order_total);	
		$Longeur_ordertotal= strlen($order_total);
		echo '<br>Order total: '. $order_total ;	
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
		
		switch ($orderItem["currency"]){//Correspond a la ligne #3
		//case "CA":	$CodeIdentifiant = '401330'; break;//Currency: CAD
		case "CA":	$CodeIdentifiant = '   4700'; break;//Currency: CAD //Nouveau GL/Plan comptable 2016-07-11
		default:   	$CodeIdentifiant = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
		//case "CA":	$CompteClient = '110100'; break;//Currency: CAD
		case "CA":	$CompteClient = '   1121'; break;//Currency: CAD
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
		//case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
		case "CA":	$EscompteClient = '   4400'; break;//Currency: CAD//Nouveau Plan comptable 2016-07-11
		default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		//Terme de paiement
		switch ($DataAcomba['account_rebate']){
		case "0":	$TermePaiement = '1'; break;//Account rebate 0%
		case "5":	$TermePaiement = '5'; break;//Account rebate 5%
		case "15":	$TermePaiement = '15'; break;//Account rebate 15%
		case "20":	$TermePaiement = '20'; break;//Account rebate 20% 
		case "25":	$TermePaiement = '25'; break;//Account rebate 25% 
		case "30":	$TermePaiement = '30'; break;//Account rebate 30%
		case "35":	$TermePaiement = '35'; break;//Account rebate 35% 
		case "45":	$TermePaiement = '45'; break;//Account rebate 35% 
		case "50":	$TermePaiement = '50'; break;//Account rebate 50% 
		case "52":	$TermePaiement = '52'; break;//Account rebate 50% Eye-Recommend 
		default:   	$TermePaiement = '00'; break;//Account rebate non mappé%		
		}
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		
		
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		      .	$The_order_num	.  $order_total . $TermePaiement  . '  ' . $TermePaiement. '|'. $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T' . $CompteClient  	 . $order_total . '         LAB'  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T' . $CodeIdentifiant  . $order_total_sans_escompte . 'LAB'.  "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
		$queryFlag = "UPDATE ORDERS SET    transfered_acomba_dln_customer ='yes', date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
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
		$outputstring.= 'T'. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
		
		echo '<br>3.6<br>';
	}//End while			
return $outputstring;			
}//End function Factures SAFE



















//CREDITS
function export_credit_acomba($mcred_primary_key){
	
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
	
$Query="SELECT memo_credits.*, accounts.account_num, accounts.main_lab FROM memo_credits, accounts 
 WHERE memo_credits.mcred_acct_user_id = accounts.user_id AND  memo_credits.mcred_primary_key = $mcred_primary_key"; //Get Credits Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));

//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);

	while ($DataCredit=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num_edll as acomba_account_num, account_rebate, main_lab, buying_group from accounts WHERE user_id ='"  . $DataCredit["mcred_acct_user_id"]. "'" ;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		$Buying_Group = $DataAcomba[buying_group];
		
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
		case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
		case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
		case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
		case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
		case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
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
		
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
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
		//case "CA":	$CompteClient = '110100'; break;//Currency: CAD
		case "CA":	$CompteClient = '   1121'; break;//Currency: CAD Nouveau Plan comptable 2016-07-11
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
				
		switch ($DataCreditCurrency[currency]){//Correspond a la ligne 4(escompte client)
		//case "CA":	$EscompteClient = '401330'; break;//Currency: CAD
		case "CA":	$EscompteClient = '   4700'; break;//Currency: CAD
		default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
//Débute par un C pour Crédit	
$outputstring.= 'C'		   .  $date_du_credit   .  $account_num 		   .  $OrderNumComplet	.  $order_total . $TermePaiement . '  ' . $TermePaiement . '|' . $nom_patient .   "\r\n" 	;//Ligne 1
$outputstring.= 'T' 	   .  $CompteClient 	.  $order_total_longeur_18 . 'LAB'.  "\r\n"; //(compte client) //Ligne 2	
$outputstring.= 'T' 	   .  $EscompteClient   .  $order_total_avant_escompte . '         LAB'	   .  "\r\n"; //(vente)    //Ligne 3
		
		switch ($DataCreditCurrency[currency]){//Correspond a la ligne 4(escompte client)
			//case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
			case "CA":	$EscompteClient = '   4400'; break;//Currency: CAD Nouveau Plan comptable 2016-07-11
			default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}

		//$outputstring.= 'T' 	   . $EscompteClient     . $Montant_escompte .'LAB' . "\r\n"; //    //Ligne 4
		
		 $queryFlag = "UPDATE memo_credits SET  transfered_acomba_dln_customer ='yes', date_transfer_acomba_dln_customer = '$dateheure'  WHERE mcred_primary_key  =  " .$DataCredit["mcred_primary_key"];
		 $resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));	
	}//End while			
return $outputstring;		
}//End function















// 10- FACTURES Ifc.ca FRAMES SEULEMENT 
function export_order_ifc_frames($order_num){
		
global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}	
	

echo '<br>Passe dans export order ifc frames<br>';
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' . mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){	
	   //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num_edll as acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		echo '<br>'. $queryAcombaAcctNum;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		$Buying_Group = $DataAcomba[buying_group];
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
		
		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE  payments.cclast4 <> '' AND  order_num = ". $order_num;
		echo '<br>' . $queryValiderPaiement;
		$ResultValiderPaiement=mysqli_query($con,$queryValiderPaiement)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataValiderPaiement=mysqli_fetch_array($ResultValiderPaiement,MYSQLI_ASSOC);
		if ($DataValiderPaiement[nbrPaiement] > 0)
		{
		$date_processed = date("ymd", strtotime($orderItem["order_date_processed"]));
		echo '<br>date ship:' . $order_num;
		}
		
		//Preparer le préfix de facture pour Identifier la commande vient de quelle plate-forme(Longeur de 3)
		switch($orderItem["order_from"]){
		case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
		case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
		case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
		case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
		case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
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
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
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
		
		switch ($orderItem["currency"]){//Correspond a la ligne #3
		case "CA":	$CodeIdentifiant = '402320'; break;//Currency: CAD// Pas troubable dans nouveau plan comptable donc inchangé pour le moment
		default:   	$CodeIdentifiant = '000000'; break;//Currency: Inconnue		
		}
		
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
		//case "CA":	$CompteClient = '110100'; break;//Currency: CAD
		case "CA":	$CompteClient = '1121'; break;//Currency: CAD Nouveau Plan comptable 2016-07-11
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
		//case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
		case "CA":	$EscompteClient = '4400'; break;//Currency: CAD Nouveau Plan comptable 2016-07-11
		default:   	$EscompteClient = '000000'; break;//Currency: Inconnue		
		}
		
		//Terme de paiement
		switch ($DataAcomba['account_rebate']){
		case "0":	$TermePaiement = '1'; break;//Account rebate 0%
		case "5":	$TermePaiement = '5'; break;//Account rebate 5%
		case "15":	$TermePaiement = '15'; break;//Account rebate 15%
		case "20":	$TermePaiement = '20'; break;//Account rebate 20% 
		case "25":	$TermePaiement = '25'; break;//Account rebate 25% 
		case "30":	$TermePaiement = '30'; break;//Account rebate 30%
		case "35":	$TermePaiement = '35'; break;//Account rebate 35% 
		case "45":	$TermePaiement = '45'; break;//Account rebate 35% 
		case "50":	$TermePaiement = '50'; break;//Account rebate 50% 
		case "52":	$TermePaiement = '52'; break;//Account rebate 50% Eye-Recommend 
		default:   	$TermePaiement = '00'; break;//Account rebate non mappé%		
		}
		
		//Ajouts pour ne pas donner de rabais sur les commandes stock Direct-Lens
		if ($orderItem[order_product_type] =='stock_tray')
		$TermePaiement = '1'; 
		
		if ($orderItem[order_product_type] =='stock')
		$TermePaiement = '1'; 
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		 .	$The_order_num	.  $order_total . $TermePaiement  . '  ' . $TermePaiement. '|' .$nom_patient  .  "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    
		
		$queryFlag = "UPDATE ORDERS SET    transfered_acomba_dln_customer ='yes',  date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
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
		$outputstring.= 'T '. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
	}//End while			
return $outputstring;			
}//End function Factures (autre que IFC)





?>