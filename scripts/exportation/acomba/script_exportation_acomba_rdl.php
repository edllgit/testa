<?php
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once(__DIR__.'/../../../constants/ftp.constant.php');
require_once(__DIR__.'/../../../constants/mysql.constant.php');

//Créer le fichier CCimport pour DirectLab Network
include("../../../sec_connectEDLL.inc.php");//RDL:LNC, Direct-Lens/Prestige sont tous dans la bd direct54_lens
$today=date("ymd") . '_' . date("Gi") ;

$datedebut = date("Y-m-d");//"2018-06-18";
$datefin   = date("Y-m-d");//"2018-06-18";
$FichierestVide = 'oui';//pour identifier si le fichier est vide ou non, et donc si on doit le copier sur le ftp

//DATE HARD CODÉ
//$datedebut ="2024-04-29";
//$datefin   ="2024-05-08";

echo '<br><br>FichierestVide:' . $FichierestVide . '<br>';
$LigneCommentaire = 'Du ' . $datedebut . ' au ' . $datefin;

echo $LigneCommentaire;
//CREATE EXPORT FILE//
//$filename="../acomba/FROM DIRECT-LENS/CCImport.C$today.001";//TODO: modifier pour dossier sur Windows VM
$filename="../../../../../../../ftp_root/acomba/CCImport.C$today.001"; //Le fichier sera créé dans un dossier ou  l'utilisateur FTP (ehandfield) à accès [en écriture]
$fp=fopen($filename, "w");
$outputstring  = $LigneCommentaire .  "\r\n" ;//Ligne 1
$outputstring .= 'LFACT=12' .  "\r\n" 	;// Ligne 2 pour laisser savoir que nos order num auront 12 caractères
fwrite($fp,$outputstring);

$Compte_GRM = "('grm64362','grmstock','grm64364','rgiguere',grmstocknet')";
$CompteSafeEDLL = " ('entrepotsafe','safedr','lavalsafe','terrebonnesafe','sherbrookesafe','chicoutimisafe','levissafe','longueuilsafe','granbysafe','quebecsafe','stjeromesafe','gatineausafe','redoifc') ";
 

//EXPORTATION DES COMMANDES SHIPPÉS
/* 
  2- export_lensnet_order_acomba: Lensnet Club QUI RECOIVE  UN CRÉDIT SUR CHAQUE VENTE seulement: Pour les labs: LNC Atlantic:33 , LNC Pacific:44
  3- export_order_acomba Directlens/Lensnet Commandes regulieres autre que les labs deja exportés: tout sauf lab: 33,36,39,43,44,50
  4- IFC.CA (Packages verres et monture)
  5- IFC.CA (Frames seulement)
  6- export_DIRA_order Directlab Atlantic SEULEMENT (génère un crédit de 15%) 
  7- export_SAFE_order SAFETY SEULEMENT (on soustrait le montant déja payé par le client avant l'envoie a acomba) 
  8- export_illinois_order_acomba (AIT LENS CLUB)
  9- export_eagle_order_acomba (Directlab Eagle) 
  10- Exportation des crédits 
*/

echo '<br>Passe A1<br>';
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







echo '<br>Passe B2<br>';
// 2- Lensnet Club QUI RECOIVE  UN CRÉDIT SUR CHAQUE VENTE seulement: Pour les labs: LensnetClub Atlantic:33 (crédit 15%) (produits exclusifs seulement)
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND lab  IN (33) and order_from = 'lensnetclub' ORDER by order_num";
echo '<br>'. $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_lensnet_order_acomba($orderData[order_num]);
echo '<br>'. $outputstring;
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '2- Lensnet 15% ecriture #1';


echo '<br>Passe C3<br>';

$orderQuery="SELECT  distinct  payments.order_num, orders.transfered_acomba_dln_customer  FROM payments, orders
WHERE payments.order_num = orders.order_num 
AND payments.cclast4 <> '' AND payments.pmt_date <=  '$datefin'
AND payments.user_id not in $House_Accounts AND orders.transfered_acomba_dln_customer <> 'yes' AND lab  IN (33) and order_from = 'lensnetclub' ORDER by order_num";
echo '<br>'. $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_lensnet_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>2- Lensnet 15% ecriture #2<br>';

echo '<br>Passe D4<br>';



//3- Directlens/Lensnet Commandes regulieres autre que les labs deja exportés: tout sauf lab: 33,36,39,43,50,?  
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('directlens','lensnetclub','eye-recommend')  AND lab NOT IN (33,36,39,50,46)
 ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>3- Direct-lens et Lensnet écriture #1<br>';


$orderQuery="SELECT  distinct  payments.order_num, orders.transfered_acomba_dln_customer  FROM payments, orders
WHERE payments.order_num = orders.order_num 
AND payments.cclast4 <> '' AND payments.pmt_date  <=  '$datefin'
AND payments.user_id not in $House_Accounts AND orders.transfered_acomba_dln_customer <> 'yes' AND order_from in ('directlens','lensnetclub','eye-recommend') 
 AND lab NOT IN (33,36,39,50,46) ORDER by order_num";
 
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>3- Direct-lens et Lensnet écriture #2<br>';



//4- IFC.CA: Partie Verres (AUCUN EDLL) 
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca')
AND order_product_type <> 'frame_stock_tray' and user_id not in ('rcogroupifc') AND order_date_processed AND orders.lab NOT IN (66) ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- IFC.CA Partie Verres Écriture #1/1<br>';
 
 
 
//EDLL-Entrepot de la lunette: Partie Verres 
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca')
AND order_product_type <> 'frame_stock_tray' AND order_date_processed AND orders.lab IN (67) AND order_date_processed < '2016-01-01' AND user_id not in ('St.Catharines','redoifc') ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres Écriture #1/1<br>';


//PARTIE EDMUNDSTON
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('edmundston','edmundstonsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres Edmundston<br>';



//PARTIE VAUDREUIL
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('vaudreuil','vaudreuilsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres Vaudreuil<br>';


//PARTIE SOREL
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('sorel','sorelsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres Sorel<br>';




//PARTIE MONCTON
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('moncton','monctonsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres MONCTON<br>';




//PARTIE FREDERICTON
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('fredericton','frederictonsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres FREDERICTON<br>';


//PARTIE ST-JOHN
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca','safety')
AND order_product_type <> 'frame_stock_tray'  AND orders.user_id in ('stjohn','stjohnsafe')
 AND order_date_processed > '2016-01-01'  ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>4P1- EDLL Partie Verres Saint-John<br>';

// 5-Partie 2 IFC.CA & IFC.US FRAMES SEULEMENT
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped  <=  '$datefin'
AND user_id not in $House_Accounts   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in ('ifcclubca') 
AND order_product_type = 'frame_stock_tray' ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_ifc_frames($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>5P2- IFC.CA ET IFC.US écriture #1<br>';





// 6-Commandes Directlab Atlantic(36) SEULEMENT (générer un credit 15%)
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id not in $House_Accounts AND lab = 36   AND  transfered_acomba_dln_customer <> 'yes' AND order_from in('directlens','eye-recommend')  ORDER by order_num";
echo '<br>'. $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_DIRA_order($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>6- DIRA écriture #1<br>';

$orderQuery="SELECT  distinct  payments.order_num, orders.transfered_acomba_dln_customer  FROM payments, orders
WHERE payments.order_num = orders.order_num 
AND payments.cclast4 <> '' AND orders.lab = 36  AND payments.pmt_date <=  '$datefin'
AND payments.user_id not in $House_Accounts AND orders.transfered_acomba_dln_customer <> 'yes' AND order_from = 'directlens'  ORDER by order_num";
echo '<br>'. $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_DIRA_order($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>6- DIRA écriture #2<br>';




//7- SAFETY (HORS ENTREPOTS SEULEMENT) (on soustrait le montant déja payé par le client avant l'envoie a acomba) 
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id NOT IN $House_Accounts
AND user_id NOT IN $CompteSafeEDLL AND user_id <> 'redoifc'
AND  transfered_acomba_dln_customer <> 'yes' AND order_from = 'safety'  ORDER by order_num";

echo '<br>'. $orderQuery.'<br>';

$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_SAFE_order($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>7- SAFE écriture #1 de 1<br>';



//7- SAFETY EDLL (ENTREPOTS SEULEMENT) (on soustrait le montant déja payé par le client avant l'envoie a acomba) 
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE order_total > 0 AND order_status='filled' AND order_date_shipped <=  '$datefin'
AND user_id NOT IN $CompteSafeEDLL  AND order_date_processed < '2016-01-01'  
AND transfered_acomba_dln_customer <> 'yes' AND order_from = 'safety'  ORDER by order_num";
echo '<br>'. $orderQuery.'<br><br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_SAFE_order($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '7- SAFE écriture #1 de 1';




//8- AITLENSCLUB (1 étape)
echo '<br><br>DEBUT AITLENSCLUB';
$orderQuery="SELECT * FROM orders 
WHERE lab IN (47) and order_Date_shipped 
BETWEEN '$datedebut' AND '$datefin' 
AND transfered_acomba_dln_customer <> 'yes'
AND order_total > 0
AND user_id NOT IN('eyelationnet', 'eyelationcan')
GROUP BY order_num ORDER BY user_id LIMIT 0 , 3000000";
echo '<br>'. $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_illinois_order_acomba($orderData[order_num],0.7);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br>AITLENSCLUB écriture #1/1 (PAS DE EYELATION)';
echo '<br>FIN AITLENSCLUB<br><br>';
//FIN AITLENSCLUB

/*
//9 Commandes de ZT1-Montreal
$orderQuery="SELECT distinct order_num FROM ORDERS WHERE  user_id IN ('montreal') 
AND order_status='filled'
AND order_date_shipped NOT IN ('0000-00-00','0001-01-01')
AND transfered_acomba_dln_customer <> 'yes'
ORDER by order_num";
echo $orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount=mysqli_num_rows($orderResult);
while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
$outputstring=export_order_acomba($orderData[order_num]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
echo '<br><br>9-Commandes de Montreal ZT1<br>'; 
*/



//10- EXPORTATION DES CRÉDITS
$QueryClientsde464750="SELECT user_id FROM `accounts` WHERE main_lab IN ( 46, 47, 50 ) AND approved = 'approved' ";
echo '<br>'. $QueryClientsde464750;
$ResultClientde464750=mysqli_query($con,$QueryClientsde464750)	or die  ('I cannot select items because: ' . mysqli_error($con));
$Clients_Illinois = '(';
$compteurillinois = 0;
while ($DataClientIllinois=mysqli_fetch_array($ResultClientde464750,MYSQLI_ASSOC)){
if ($compteurillinois > 0)
$Clients_Illinois .= ' , ';
$Clients_Illinois .= '\'' . $DataClientIllinois[user_id] .'\'' ;
$compteurillinois   = $compteurillinois + 1;
} 
$Clients_Illinois .= ')';




$QueryClients6667 = "SELECT user_id FROM `accounts` WHERE main_lab IN (66) AND approved = 'approved'";
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
$Clients_6667  .= ",'entrepotsafe','safedr','warehousestcsafe','lavalsafe',
'terrebonnesafe','quebecsafe','sherbrookesafe','levissafe','terrebonnesafe','levissafe','longueuilsafe','chicoutimisafe','longueuilsafe','granbysafe'";
$Clients_6667 .= ')';

echo 'Client de 66-67:'. $Clients_6667;


//NE PAS EXPORTER LES CRÉDITS DES ENTREPOTS, ils iront plutot dans le fichier CCIMPORTEDLL
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' AND mcred_acct_user_id  NOT IN $House_Accounts AND mcred_acct_user_id NOT IN $Clients_Illinois AND mcred_acct_user_id NOT IN $Clients_6667 AND mcred_acct_user_id NOT IN 
('milano6769','directlabnetwork','brianmilano','milanordl','opticaldepomilano6769','opticalboutique','73699','leonardmann','testc','entrepotmilanotroisri','entrepotmilanosherbrooke',
'entrepotmilanodrummondville','warehousemilanohalifax','laval@entrepotdelalunette.com','terrebonne@entrepotdelalunette.com','entrepotmilanochicoutimi','mainopticalmilano','3for1glassesmilano',
'imperialmilano6769','firstrateopticalsupply','carloshahnmilano6769','poloniaopticalmilano6769','eyesonrichmondmilano','opticalmarketmilano','nanakmilano','carmenmilano6769','mattieyewearmilano6769',
'towneopticalmilano6769','oandomilano6769','kawarthamilano6769','carlosmilano6769','brianmilano6769','ezvisionmilano6769','445982','eyecandymilano6769','eyesonmainmilano6769','rjcampbellmilano6769',
'eyecandybarriemilano6769','lewismilano6769','streetsvillemilano','humberbaymilano','globconmilano','ralphsaarimilano6769','bramptonmilano6769','opticalshoppe','eyezonevisioncare','invisionoptical',
'opticalmodamilano','eyeviewmilano6769','eyeonoptical','Ashleymilano6769','elmiraeyewear','glenysmilano6769','5676','wisevisioncentremilano6769','simcoeopticalmilano6769','parkwayopticalmilano','visioncareopticalmilano')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}

/*
//EXPORTER CRÉDITS MONTREAL
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('montreal','montrealsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}
*/

//EXPORTER CRÉDITS Edmundston
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('edmundston','edmundstonsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}

//EXPORTER CRÉDITS Vaudreuil
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('vaudreuil','vaudreuilsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}


//EXPORTER CRÉDITS Sorel
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('sorel','sorelsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}


//EXPORTER CRÉDITS Moncton
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('moncton','monctonsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}

//EXPORTER CRÉDITS Fredericton
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('fredericton','frederictonsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}


//EXPORTER CRÉDITS stjohn
$QueryCredit="SELECT * FROM memo_credits WHERE  transfered_acomba_dln_customer <> 'yes'  AND mcred_date  <=  '$datefin' 
AND mcred_acct_user_id IN ('stjohn','stjohnsafe')"; 
echo '<br>'. $QueryCredit;
$ResultCredit=mysqli_query($con,$QueryCredit)	or die  ('I cannot select items because: ' . mysqli_error($con));
while ($DataCredit=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
$outputstring=export_credit_Acomba($DataCredit[mcred_primary_key]);
if ($outputstring <> '')
$FichierestVide = 'non';
fwrite($fp,$outputstring);
}

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
		
	//ftp_chdir($conn_id,"FROM_DL");
	
	$file=$filename;//"PrecisionOrderData-".$today.".csv";
	$remote_file = "CCImport.C$today.001";//"PrecisionOrderData-".$today.".csv";
	
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
  1- export_order_acomba FACTURES (autres que IFC)
  2- export_ifc_order_acomba FACTURES IFC
  3- export_directlens_order_acomba FACTURES Directlens (LAB  paient le prix elab)
  4- export_lensnet_order_acomba FACTURES LensnetClub qui génèreront un crédit (15%)
  5- export_ait_order_acomba FACTURES AITLENSCLUB  qui génèreront un crédit (30%)
  6- export_eagle_order_acomba Factures DIRECTLENS de DIRECTLAB EAGLE seulement(LAB PAIE PRIX ELAB)
  10- export_order_ifc_frames pour ifc.ca: commandes de frames seulement
*/

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
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
		case "safety":	    $Prefix_Facture = "7"; break;//Safety
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
		
		//Terme de paiement
		/*switch ($DataAcomba['account_rebate']){
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
		}*/
		
		//Hard coder le terme de paiement
		$TermePaiement = '1';
		
		
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




// 2- FACTURES IFC
function export_ifc_order_acomba($order_num){
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
		$queryAcombaAcctNum = "SELECT acomba_account_num from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		$ResultAcombaAcctNum=mysqli_query($con, $queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
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
		
		
		//$nom_patient = substr($orderItem["order_patient_first"],0,1) .  $caractereJonction .  substr($orderItem["order_patient_last"],0,6) ;	
		$nom_patient  = strtoupper($nom_patient);
		
		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
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
		$The_order_num = $Prefix_Facture .'-'. $orderItem["order_num"] ;	
		$Longeur_Ordernum = strlen($The_order_num);
		for ($counter = $Longeur_Ordernum ; $counter < 12; $counter++) 
		{
		$The_order_num .= ' ';
		}			
		//Calcul de l'escompte accordé au client
		$escompte = $orderItem["order_product_price"] - $orderItem["order_product_discount"];
		$order_total_sans_escompte = $orderItem["order_total"] + $orderItem["order_shipping_cost"]  + $escompte;
		$order_total_sans_escompte = 0.02 * $order_total_sans_escompte ;
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		echo '<br>Order total avant 2%: '. $order_total ;	
		$order_total = 0.02 * $order_total;//2% de chaque commande
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
}//End function Factures (IFC)



//3- FACTURES Directlens
//On utilise le prix elab plutot que que le prix de la commande
function export_directlens_order_acomba($order_num){
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error());
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
	   //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
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
			case "ifcclub":			$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":		$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":		$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":		$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":		$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":		$Prefix_Facture = "6"; break;//AIT lens club
			case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
			default:    			$Prefix_Facture = "0"; break;//Source de la commande inconnue
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
		$queryOrderType  = "SELECT   order_product_type, eye   from orders WHERE order_num = ". $orderItem["order_num"]; 
		echo ' Order Num: '. $orderItem["order_num"] . ' ';
		$ResultOrderType = mysqli_query($con,$queryOrderType)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataOrderType=mysqli_fetch_array($ResultOrderType,MYSQLI_ASSOC);
		$OrderType = $DataOrderType['order_product_type'];
		echo ' Order type:'.$OrderType;
		
		if ($OrderType == 'exclusive'){
		$queryPrixElab = "SELECT  e_lab_us_price  from exclusive WHERE primary_key = ". $orderItem["order_product_id"]; 
		echo '<br>'. $queryPrixElab;
		$resultPrixElab=mysqli_query($con,$queryPrixElab)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataPrixElab=mysqli_fetch_array($resultPrixElab,MYSQLI_ASSOC);
		$PrixElab = $DataPrixElab['e_lab_us_price'];
		}else{//Commande de stock ou by Tray, il faut commuler la valeur des prix elab de chaque produit commandé
		$queryStockOrders = "SELECT * from orders WHERE order_num = " . $orderItem["order_num"];
		$ResultStockOrders = mysqli_query($con,$queryStockOrders)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Total_Elab_Stock_Order = 0;
			while ($DataStockOrders=mysqli_fetch_array($ResultStockOrders,MYSQLI_ASSOC)){
			//Pour chaque tuple dans orders,  on cummule le total de facture elab
			$queryPrixElab = "SELECT  e_lab_us_price  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
			echo '<br>'. $queryPrixElab;
			$resultPrixElab=mysqli_query($con,$queryPrixElab)	or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataPrixElab=mysqli_fetch_array($resultPrixElab,MYSQLI_ASSOC);
			$PrixElab = $DataPrixElab['e_lab_us_price'];
			$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
			}//End While
		
		
		}//End if
						
		if ($OrderType == 'exclusive'){
		$PrixElab = $PrixElab;
		}else{
		$PrixElab = $Total_Elab_Stock_Order;
		}
		echo ' Prix elab '	 . $PrixElab; 
		$order_total_sans_escompte = $PrixElab ;
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		//$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		$order_total = $PrixElab;
		echo '<br><br>';
		//echo '<br>Order total: '. $order_total ;	
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
		
		//Terme de paiement
		/*switch ($DataAcomba['account_rebate']){
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
		}*/
		//Hard codé le terme de paiement
		$TermePaiement = '1';
		
		
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		
		//' 1' equivault au terme de paiement 0 (net 30 jours)
		$outputstring.= 'F'	.  $date_processed	 . $account_num 		 .	$The_order_num	.  $order_total . ' 1'  . '  ' . ' 1|' . $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
		$queryFlag = "UPDATE ORDERS SET  transfered_to_acomba ='yes',  transfered_acomba_dln_customer ='yes', date_transfer_acomba = '$dateheure', date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	}//End while			
return $outputstring;			
}//End function Factures 



//4- FACTURES LensnetClub qui génèreront un crédit (15%)
function export_lensnet_order_acomba($order_num){
	
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
echo '<br><br>'. $order_num;

$Query="SELECT orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));

//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, buying_group, account_rebate from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
		$ResultAcombaAcctNum=mysqli_query($con,$queryAcombaAcctNum)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataAcomba=mysqli_fetch_array($ResultAcombaAcctNum,MYSQLI_ASSOC);
		$Account_Num_Acomba = $DataAcomba[acomba_account_num];
		$Buying_Group = $DataAcomba[buying_group];
		
		//Trouver le main lab du Client pour le numéro de compte acomba du crédit
		$queryMainLab = "SELECT main_lab from accounts WHERE user_id = '" . $orderItem["user_id"] . "'";
		$ResultMainLab=mysqli_query($con,$queryMainLab)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataMainLab=mysqli_fetch_array($ResultMainLab,MYSQLI_ASSOC);
		switch($DataMainLab['main_lab']){
		case "33":	$CreditAcctNum = "DIRA    "; break;//Lensnet Club Atlantic
		case "44":	$CreditAcctNum = "DIRP    "; break;//Lensnet Club Pacific
		default:    $CreditAcctNum = "        "; break;//Source de la commande inconnue
		}
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
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
		//echo '<br>' . $queryValiderPaiement;
		$ResultValiderPaiement=mysqli_query($con,$queryValiderPaiement)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataValiderPaiement=mysqli_fetch_array($ResultValiderPaiement,MYSQLI_ASSOC);
		
		if ($DataValiderPaiement[nbrPaiement] > 0){
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
		//echo '<br>Order total: '. $order_total ;	
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
		
	//Terme de paiement
		/*switch ($DataAcomba['account_rebate']){
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
		}*/
		
		//Hard codé terme de paiement
		$TermePaiement = '1';
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
	

		$outputstring.= 'F'	.  $date_processed	 . $account_num 		 .	$The_order_num	.  $order_total   . $TermePaiement  . '  ' . $TermePaiement. '|'. $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
		//Noter la commande comme transféré a acomba
		$queryFlag = "UPDATE ORDERS SET  transfered_acomba_dln_customer ='yes', date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
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
		
	//Générer le crédit de 15% pour cette vente
	$date_du_credit = date("ymd");
	//echo '<br>date du credit:' . $date_du_credit ;	
	$MontantCredit  = 0.0375 * $order_total; 
	$MontantCredit = money_format('%.2n',$MontantCredit);
	//Formatter le montant total du crédit   Amener a 9 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	for ($counter = $Longeur_MontantCredit ; $counter < 9; $counter++) 
	{
	$MontantCredit = ' ' . $MontantCredit;
	}
	//Re-Formatter le montant total du crédit   Amener a 18 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	$montant_credit_longeur_18 = $MontantCredit;	
	for ($counter = $Longeur_MontantCredit ; $counter < 18; $counter++) 
	{
	$montant_credit_longeur_18 = ' ' . $montant_credit_longeur_18;
	}
	//ajouter le Z pour identifier les crédits généré pour payer une comission Lensnetclub
	$The_order_num = substr($The_order_num,0,11) . 'Z';
	
	
	//Débute par un C pour Crédit	
		//$outputstring.= 'C'	 .  $date_processed   .  $CreditAcctNum 		     .  $The_order_num 	.  $MontantCredit . $TermePaiement  . '  ' . $TermePaiement .'|'. $nom_patient .   "\r\n" 	;//Validé	
		//$outputstring.= 'T ' .  $CompteClient 	  .  $montant_credit_longeur_18  .  "\r\n"; //(compte client) Validé
		//$outputstring.= 'T ' .  '401200'   		  .  $MontantCredit		 	     .  "\r\n"; // (vente)   Validé  
				
	}//End while			
return $outputstring;			
}//End function Factures (Lensnetclub that receives credits for every lensnet order)




// 5- FACTURES AITLENSCLUB  qui génèreront un crédit (30%)
function export_ait_order_acomba($order_num){
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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

		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
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
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		      .	$The_order_num	.  $order_total  . ' 1'  . '  ' . ' 1|'. $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
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
		
	//Générer le crédit de 15% pour cette vente
	echo '<br>date du credit:' . $date_du_credit ;	
	$MontantCredit  = 0.3 * $order_total; 
	$MontantCredit = money_format('%.2n',$MontantCredit);
	//Formatter le montant total du crédit   Amener a 9 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	for ($counter = $Longeur_MontantCredit ; $counter < 9; $counter++) 
	{
	$MontantCredit = ' ' . $MontantCredit;
	}
	//Re-Formatter le montant total du crédit   Amener a 18 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	$montant_credit_longeur_18 = $MontantCredit;	
	for ($counter = $Longeur_MontantCredit ; $counter < 18; $counter++) 
	{
	$montant_credit_longeur_18 = ' ' . $montant_credit_longeur_18;
	}
	//ajouter le Z pour identifier les crédits généré pour payer une comission aitlensclub
	$The_order_num = substr($The_order_num,0,11) . 'Z';
	$account_num  = 'DIRI    ';//Hard codé pour toujours envoyer le crédit a Directlab Illinois
	//Débute par un C pour Crédit	
		$outputstring.= 'C'		   .  $date_processed   .  $account_num 		   .  $The_order_num 	.  $MontantCredit . ' 1'  . '  ' .  ' 1|' . $nom_patient .   "\r\n" 	;//Validé	
		$outputstring.= 'T ' 	   .  $CompteClient 	.  $montant_credit_longeur_18 .  "\r\n"; //(compte client) Validé
		$outputstring.= 'T ' 	   .  '402200'   .  $MontantCredit		 	   .  "\r\n"; // (vente)   Validé  
			
	}//End while			
return $outputstring;			
}//End function Factures (AITLENSCLUB that receives credits for every lensnet order)





//6- FACTURES Directlens DIRECTLAB EAGLE SEULEMENT
//On utilise le prix elab plutot que que le prix de la commande
function export_eagle_order_acomba($order_num){
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
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
		$nom_patient = substr($orderItem["order_patient_first"],0,1) .  '.' .  substr($orderItem["order_patient_last"],0,6) ;	
		$nom_patient  = strtoupper($nom_patient);


		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
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
		$queryOrderType  = "SELECT   order_product_type, eye   from orders WHERE order_num = ". $orderItem["order_num"]; 
		echo ' Order Num: '. $orderItem["order_num"] . ' ';
		$ResultOrderType = mysqli_query($con,$queryOrderType)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataOrderType=mysqli_fetch_array($ResultOrderType,MYSQLI_ASSOC);
		$OrderType = $DataOrderType['order_product_type'];
		echo ' Order type:'.$OrderType;
		if ($OrderType == 'exclusive'){
		$queryPrixElab = "SELECT  e_lab_us_price  from exclusive WHERE primary_key = ". $orderItem["order_product_id"]; 
		echo '<br>'. $queryPrixElab;
		$resultPrixElab=mysqli_query($con,$queryPrixElab)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataPrixElab=mysqli_fetch_array($resultPrixElab,MYSQLI_ASSOC);
		$PrixElab = $DataPrixElab['e_lab_us_price'];
		}else{//Commande de stock ou by Tray, il faut commuler la valeur des prix elab de chaque produit commandé
		$queryStockOrders = "SELECT * from orders WHERE order_num = " . $orderItem["order_num"];
		$ResultStockOrders = mysqli_query($con,$queryStockOrders)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Total_Elab_Stock_Order = 0;
			while ($DataStockOrders=mysqli_fetch_array($ResultStockOrders,MYSQLI_ASSOC)){
			//Pour chaque tuple dans orders,  on cummule le total de facture elab
			$queryPrixElab = "SELECT  e_lab_us_price  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
			echo '<br>'. $queryPrixElab;
			$resultPrixElab=mysqli_query($con,$queryPrixElab)	or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataPrixElab=mysqli_fetch_array($resultPrixElab,MYSQLI_ASSOC);
			$PrixElab = $DataPrixElab['e_lab_us_price'];
			$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
			}//End While
		}//End if
						
		if ($OrderType == 'exclusive'){
		$PrixElab = $PrixElab;
		
		if ($DataOrderType[eye] != "Both") {
			$PrixElab = $PrixElab/2;
			}
			
		}else{
		$PrixElab = $Total_Elab_Stock_Order;
		}
		echo ' Prix elab '	 . $PrixElab; 
		$order_total_sans_escompte = $PrixElab ;
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		//$order_total = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		$order_total = $PrixElab;
		echo '<br><br>';
		//echo '<br>Order total: '. $order_total ;	
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
		
		/*//Terme de paiement
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
		}*/
		
		
		//if ($Buying_Group == 14)//Buying group DEN = 5%
		//$TermePaiement = $TermePaiement  + 5;//On ajoute 5% au  terme de paiement car c'est un client DEN
		
		if ($DataAcomba['account_rebate'] == '0')
		$TermePaiement = $TermePaiement  - 1;
		
		if ($TermePaiement == 0)
		$TermePaiement =  1;
		
		if ($TermePaiement < 10)
		$TermePaiement = ' ' . $TermePaiement;
		$account_num='DIRI    ';
		//numero de client ZZZ est invalide pour detecter sur des nouveaux comptes sont operationnel dans Dlab Eagle
		$outputstring.= 'F'	.  $date_processed	   .  $account_num		 .	$The_order_num	.  $order_total  . $TermePaiement  . '  ' . $TermePaiement . '|' . $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . '110200'  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . '402100'  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab

		$queryFlag = "UPDATE ORDERS SET  transfered_acomba_dln_customer ='yes',  date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
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
}//End function Factures (Directlab EAGLE SEULEMENT)
















// 7- FACTURES DIRA(Directlab Atlantic 36)  qui génèreront un crédit (15%)
function export_DIRA_order($order_num){

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
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate FROM accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
			case "ifcclub":			$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":		$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":		$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":		$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":		$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":		$Prefix_Facture = "6"; break;//AIT lens club
			case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
			default:    			$Prefix_Facture = "0"; break;//Source de la commande inconnue
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
		case "CA":	$CodeIdentifiant = '401100'; break;//Currency: CAD
		case "EUR":	$CodeIdentifiant = '403100'; break;//Currency: Euro 
		case "EU":	$CodeIdentifiant = '403100'; break;//Currency: Euro 
		case "US":	$CodeIdentifiant = '402100'; break;//Currency: US 
		default:   	$CodeIdentifiant = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne #2(compte client)
		case "CA":	$CompteClient = '110100'; break;//Currency: CAD
		default:   	$CompteClient = '000000'; break;//Currency: Inconnue		
		}
		switch ($orderItem["currency"]){//Correspond a la ligne 4(escompte client)
		case "CA":	$EscompteClient = '601000'; break;//Currency: CAD
		case "EUR":	$EscompteClient = '601200'; break;//Currency: Euro 
		case "EU":	$EscompteClient = '601200'; break;//Currency: Euro 
		case "US":	$EscompteClient = '601100'; break;//Currency: US 
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
		
		
		
		$outputstring.= 'F'	.  $date_processed	 .  $account_num 		      .	$The_order_num	.  $order_total . $TermePaiement  . '  ' . $TermePaiement. '|'. $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
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
		$outputstring.= 'T '. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
		
	//Générer le crédit de 15% pour cette vente
	echo '<br>date du credit:' . $date_du_credit ;	
	$MontantCredit  = 0.0375 * $order_total; 
	$MontantCredit = money_format('%.2n',$MontantCredit);
	//Formatter le montant total du crédit   Amener a 9 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	for ($counter = $Longeur_MontantCredit ; $counter < 9; $counter++) 
	{
	$MontantCredit = ' ' . $MontantCredit;
	}
	//Re-Formatter le montant total du crédit   Amener a 18 caractères, ajouter des 'blancs' à gauche
	$Longeur_MontantCredit= strlen($MontantCredit);
	$montant_credit_longeur_18 = $MontantCredit;	
	for ($counter = $Longeur_MontantCredit ; $counter < 18; $counter++) 
	{
	$montant_credit_longeur_18 = ' ' . $montant_credit_longeur_18;
	}
	//ajouter le Z pour identifier les crédits généré pour payer une comission aitlensclub
	$The_order_num = substr($The_order_num,0,11) . 'Z';
	$account_num  = 'DIRA    ';//Hard codé pour toujours envoyer le crédit a Directlab Atlantic
	
	//Débute par un C pour Crédit non généré temporairement	
		//$outputstring.= 'C'		   .  $date_processed   .  $account_num 		   .  $The_order_num 	.  $MontantCredit . ' 1'  . '  ' .  ' 1|' . $nom_patient .   "\r\n" 	;//Validé	
		//$outputstring.= 'T ' 	   .  $CompteClient 	.  $montant_credit_longeur_18 .  "\r\n"; //(compte client) Validé
		//$outputstring.= 'T ' 	   .  '402200'   .  $MontantCredit		 	   .  "\r\n"; // (vente)   Validé  
			
	}//End while			
return $outputstring;			
}//End function Factures (DIRA Directlab Atlantic that receives credits for every order)






// 8- FACTURES SAFE (on soustraira le paiment fait par le client avant l'exportation vers acomba)
function export_SAFE_order($order_num){
$Query="SELECT orders.*,  accounts.account_num FROM orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
echo '<br>'.$Query.'<br>';

$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: <br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate FROM accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
			case "ifcclub":			$Prefix_Facture = "1"; break;//IFC France
			case "ifcclubca":		$Prefix_Facture = "2"; break;//IFC.ca
			case "ifcclubus":		$Prefix_Facture = "3"; break;//IFC.us
			case "directlens":		$Prefix_Facture = "4"; break;//Direct-Lens
			case "lensnetclub":		$Prefix_Facture = "5"; break;//Lensnet Club
			case "aitlensclub":		$Prefix_Facture = "6"; break;//AIT lens club
			case "safety":	    	$Prefix_Facture = "7"; break;//SAFETY
			case "eye-recommend":	$Prefix_Facture = "8"; break;//AIT lens club
			default:    			$Prefix_Facture = "0"; break;//Source de la commande inconnue
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
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  .  "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . $CodeIdentifiant  . $order_total_sans_escompte .   "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
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
		$outputstring.= 'T '. $EscompteClient .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
		
			
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
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate, main_lab, buying_group from accounts WHERE user_id ='"  . $DataCredit["mcred_acct_user_id"]. "'" ;
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
			case "safety":		$Prefix_Facture = "7"; break;//SAFETY
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















// 10- FACTURES Ifc.ca FRAMES SEULEMENT 
function export_order_ifc_frames($order_num){
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
		$queryAcombaAcctNum = "SELECT acomba_account_num, account_rebate, buying_group from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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
			case "CA":	$CodeIdentifiant = '402320'; break;//Currency: CAD
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
















function export_illinois_order_acomba($order_num, $pourcentage){
$Query="select orders.*,  accounts.account_num from orders, accounts 
WHERE  orders.user_id = accounts.user_id AND order_num='$order_num' ORDER by primary_key LIMIT 0,1"; //Get Order Data
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because: ' .$Query . '<br>'. mysqli_error($con));
//Calculate current time
$todayDate   = date("Y-m-d H:i");//Current date
$currentTime = time($todayDate); //Change date into time
$timeAfter3Hours = $currentTime+(60*60 *3);
$dateheure   = date("Y-m-d H:i:s",$timeAfter3Hours);
	while ($orderItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		 //Mapping avec les numéro de compte D'acomba	
		$queryAcombaAcctNum = "SELECT acomba_account_num,account_rebate from accounts WHERE user_id ='"  . $orderItem["user_id"]. "'" ;
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

		//If there is a payment on this order, we use the date processed instead of the date shipped
		$queryValiderPaiement = "SELECT COUNT(*) as nbrPaiement from payments WHERE payments.cclast4 <> '' AND order_num = ". $order_num;
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
		$escompte = ($orderItem["order_product_price"] - $orderItem["order_product_discount"]) * $pourcentage;
		$escompte = money_format('%.2n',$escompte);
		$order_total_sans_escompte = $orderItem["order_total"] + $orderItem["order_shipping_cost"]  + $escompte;
		$order_total_sans_escompte = money_format('%.2n',$order_total_sans_escompte);
		//Formatter le montant total de la commande (APRÈS escompte)  Amener a 9 caractères, ajouter des 'blancs' à gauche	
		$order_total = ($orderItem["order_total"] + $orderItem["order_shipping_cost"] - 7.5 ) * $pourcentage;
		echo '<br><br>Order total = ('. 	$orderItem["order_total"] . '+'. $orderItem["order_shipping_cost"] . ' -7,50)* '.$pourcentage;
		
		
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
		$order_total_sans_escompte = ($order_total_sans_escompte -7.5)* $pourcentage;
		$order_total_sans_escompte =money_format('%.2n',$order_total_sans_escompte);
		$order_total_sans_escompte = $espaceaajouter. $order_total_sans_escompte;
			
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
		
		$account_num  = 'DIRI    ';
		$CompteClient = '110200';
		
		$outputstring.= 'F'	.  $date_processed	 . $account_num 		      .	$The_order_num	.  $order_total  . $TermePaiement  . '  ' . $TermePaiement . '|' . $nom_patient .   "\r\n" 	;//Ligne 1
		$outputstring.= 'T ' . $CompteClient  	 . $order_total 			  . "\r\n" 	;// (compte client)
		$outputstring.= 'T ' . '402100'  . $order_total_sans_escompte . "\r\n" 	;// (vente)    //Ligne ou doit apparaitre le prix elab
		
		$queryFlag = "UPDATE ORDERS SET  transfered_acomba_dln_customer ='yes', date_transfer_acomba_dln_customer = '$dateheure'  WHERE order_num =  " . $orderItem["order_num"];
		$resultFlag=mysqli_query($con,$queryFlag)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
		
		
		$escompte = $order_total_sans_escompte -$order_total; 
		$escompte = money_format('%.2n',$escompte);
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
		$outputstring.= 'T '. '601100' .  $espace_a_ajouter .  money_format('%.2n',$escompte).   "\r\n" 	;// (escompte sur vente)
		}//End if
		
		
		
	}//End while			
return $outputstring;			
}//End function Factures (AITLENSCLUB that receives credits for every lensnet order)


?>