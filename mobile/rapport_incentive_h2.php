<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];

//Date du rapport
$ilya6jours  	= mktime(0,0,0,date("m"),date("d")-8,date("Y"));
$date1 = date("Y/m/d", $ilya6jours);

$ajd  			= mktime(0,0,0,date("m"),date("d")-2,date("Y"));
$date2     = date("Y/m/d", $ajd);


//DATES HARD CODÉS MANUELLE

/*
$date1        = "2022-03-13";
$date2        = "2022-03-19";
*/


include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>date1: '. $date1;
echo '<br>date2: '. $date2;
//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau =3;	//Nombre de TD dans le tableau
$WidthTableau = 750;		//Pixels

//HBC.CA PRODUCTION PART

//Prepare email 
$message="<html>
<head><style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>";			

$message.= "
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2 DM: Johnny Chow</h3></th>
	</tr>";
	
$messageJohnnyChow.= "
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2 DM: Johnny Chow</h3></th>
	</tr>";	
	
	$messageSukhMaghera.= "
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2 DM: Sukh Maghera</h3></th>
	</tr>";	
	
//Johnny's Stores	
$message88403 =$messageJohnnyChow;//Johnny Chow
$message88405 =$messageJohnnyChow;//Johnny Chow
$message88408 =$messageJohnnyChow;//Johnny Chow
$message88409 =$messageJohnnyChow;//Johnny Chow
$message88411 =$messageJohnnyChow;//Johnny Chow
$message88414 =$messageJohnnyChow;//Johnny Chow
$message88440 =$messageJohnnyChow;//Johnny Chow
$message88449 =$messageJohnnyChow;//Johnny Chow





//DÉBUT DE LA PARTIE DE JOHNNY CHOW
//#1er:Début magasin #88403
$store				= "88403";
$StoreDescription	= "#88403-BLOOR STREET";
$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88403.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88403.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88403



//#2ieme Début Magasin #88405
$store				= "88405";
$StoreDescription	= "#88405-FAIRVIEW";
$store_category 	= "D";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88405.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88405.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88405


//#3ieme Début Magasin #88408
$store				= "88408";
$StoreDescription	= "#88408-OSHAWA";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88408.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88408.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88408


//#4ieme Début Magasin #88409
$store				= "88409";
$StoreDescription	= "#88409-EGLINTON";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88409.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88409.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88409


//#5ieme Début Magasin #88411
$store				= "88411";
$StoreDescription	= "#88411-SHERWAY";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88411.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88411.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88411


//#6ieme Début Magasin #88414
$store				= "88414";
$StoreDescription	= "#88414-YORKDALE";
$store_category 	= "B";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88414.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88414.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88414


//#7ieme Début Magasin #88440
$store				= "88440";
$StoreDescription	= "#88440-RIDEAU";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88440.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88440.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin Magasin #88440




//#9ieme Début Magasin #88449
$store				= "88449";
$StoreDescription	= "#88449-MISSISSAUGA";
$store_category 	= "E";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$message.= '</table><br><br><br>';
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageJohnnyChow.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageJohnnyChow.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$messageJohnnyChow.= '</table><br><br><br>';
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88449.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88449.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$message88449.= '</table><br><br><br>';
//Fin Magasin #88449
//FIN DE LA PARTIE DE JOHNNY CHOW


//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


//Envoie des courriels concernant les magasins de Johnny Chow: individuellement à chaque magasin.
//Envoie du courriel  #88403
$subject ="HBC Incentive Report for Store #88403 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER	
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88403 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88403;
$response=office365_mail($to_address, $from_address, $subject, null, $message88403);
echo '<br>Report for #88403:Sucessfully Sent.<br>';

//Envoie du courriel #88405
$subject ="HBC Incentive Report for Store #88405 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88405 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88405;
$response=office365_mail($to_address, $from_address, $subject, null, $message88405);
echo '<br>Report for #88405:Sucessfully Sent.<br>';

//Envoie du courriel #88408
$subject ="HBC Incentive Report for Store #88408 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88408 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88408;
$response=office365_mail($to_address, $from_address, $subject, null, $message88408);
echo '<br>Report for #88408:Sucessfully Sent.<br>';

//Envoie du courriel #88409
$subject ="HBC Incentive Report for Store #88409 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88409 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88409;
$response=office365_mail($to_address, $from_address, $subject, null, $message88409);
echo '<br>Report for #88409:Sucessfully Sent.<br>';

//Envoie du courriel #88411
$subject ="HBC Incentive Report for Store #88411 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88411 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88411;
$response=office365_mail($to_address, $from_address, $subject, null, $message88411);
echo '<br>Report for #88411:Sucessfully Sent.<br>';

//Envoie du courriel #88414
$subject ="HBC Incentive Report for Store #88414 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88411 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88414;
$response=office365_mail($to_address, $from_address, $subject, null, $message88414);
echo '<br>Report for #88414:Sucessfully Sent.<br>';


//Envoie du courriel #88440
$subject ="HBC Incentive Report for Store #88440 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88440 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88416;
$response=office365_mail($to_address, $from_address, $subject, null, $message88440);
echo '<br>Report for #88440:Sucessfully Sent.<br>';



//Envoie du courriel #88449
$subject ="HBC Incentive Report for Store #88449 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88449 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88449;
$response=office365_mail($to_address, $from_address, $subject, null, $message88449);
echo '<br>Report for #88449:Sucessfully Sent.<br>';


//Envoie du rapport par courriel  de TOUS ses magasins combinés à Johnny Chow
$subject ="HBC Incentive Report for DM Johnny Chow  Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport Johnny Chow en cours..<br>';
echo 'Contenu du rapport:<br>'. $messageJohnnyChow;
$response=office365_mail($to_address, $from_address, $subject, null, $messageJohnnyChow);
echo '<br>Report for Johnny Chow:Sucessfully Sent.<br>';
//DÉBUT de l'envoie de courriel pour la partie DE Johnny Chow






//DÉBUT DE LA PARTIE DE SUKH MAGHERA
$message.= "<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th colspan=\"$LargeurColspanTableau \"><h3>HBC Incentive Report between $date1-$date2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DM: Sukh Maghera</b></h3></th>
	</tr>";


//Sukh's Stores
$message88416 =$messageSukhMaghera;//Sukh Maghera
$message88431 =$messageSukhMaghera;//Sukh Maghera
$message88433 =$messageSukhMaghera;//Sukh Maghera
$message88434 =$messageSukhMaghera;//Sukh Maghera
$message88438 =$messageSukhMaghera;//Sukh Maghera
$message88439 =$messageSukhMaghera;//Sukh Maghera
$message88444 =$messageSukhMaghera;//Sukh Maghera


//#1er  Début Magasin #88416
$store				= "88416";
$StoreDescription	= "#88416-VANCOUVER DTN";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th  bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88416.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88416.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88416





//#3ieme  Début Magasin #88431
$store				= "88431";
$StoreDescription	= "#88431-CALGARY DTN";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88431.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88431.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88431


//#4ieme  Début Magasin #88433
$store				= "88433";
$StoreDescription	= "#88433-Polo Park";
$store_category 	= "D";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88433.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88433.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88433


//#5ieme  Début Magasin #88434
$store				= "88434";
$StoreDescription	= "#88434-MARKET MALL";
$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88434.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88434.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88434


//#6ieme  Début Magasin #88438
$store				= "88438";
$StoreDescription	= "#88438-METROTOWN";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88438.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88438.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88438


//#7ieme  Début Magasin #88439
$store				= "88439";
$StoreDescription	= "#88439-Langley";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88439.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88439.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//Fin magasin #88439





//#9ieme  Début Magasin #88444
$store				= "88444";
$StoreDescription	= "#88444-MAYFAIR";
$store_category 	= "C";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$messageSukhMaghera.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageSukhMaghera.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88444.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88444.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$message.= '</table><br><br><br>';
//Fin magasin #88444


//Envoie des courriels concernant les magasins de Sukh Maghera: individuellement à chaque magasin.
//Envoie du courriel  #88416
$subject ="HBC Incentive Report for Store #88416 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88416 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88416;
$response=office365_mail($to_address, $from_address, $subject, null, $message88416);
echo '<br>Report for #88416:Sucessfully Sent.<br>';


//Envoie du courriel  #88431
$subject ="HBC Incentive Report for Store #88431 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88431 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88431;
$response=office365_mail($to_address, $from_address, $subject, null, $message88431);
echo '<br>Report for #88431:Sucessfully Sent.<br>';

//Envoie du courriel  #88433
$subject ="HBC Incentive Report for Store #88433 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88433 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88433;
$response=office365_mail($to_address, $from_address, $subject, null, $message88433);
echo '<br>Report for #88433:Sucessfully Sent.<br>';


//Envoie du courriel  #88434
$subject ="HBC Incentive Report for Store #88434 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88434 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88434;
$response=office365_mail($to_address, $from_address, $subject, null, $message88434);
echo '<br>Report for #88434:Sucessfully Sent.<br>';

//Envoie du courriel  #88438
$subject ="HBC Incentive Report for Store #88438 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88438 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88438;
$response=office365_mail($to_address, $from_address, $subject, null, $message88438);
echo '<br>Report for #88438:Sucessfully Sent.<br>';


//Envoie du courriel  #88439
$subject ="HBC Incentive Report for Store #88439 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88439 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88439;
$response=office365_mail($to_address, $from_address, $subject, null, $message88439);
echo '<br>Report for #88439:Sucessfully Sent.<br>';




//Envoie du courriel  #88444
$subject ="HBC Incentive Report for Store #88444 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport 88444 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88444;
$response=office365_mail($to_address, $from_address, $subject, null, $message88444);
echo '<br>Report for #88444:Sucessfully Sent.<br>';


//Envoie du rapport par courriel  de TOUS ses magasins combinés à Sukh Maghera
$subject ="HBC Incentive Report for DM Sukh Maghera Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport Sukh Maghera en cours..<br>';
echo 'Contenu du rapport:<br>'. $messageSukhMaghera;
$response=office365_mail($to_address, $from_address, $subject, null, $messageSukhMaghera);
echo '<br>Report for Sukh Maghera :Sucessfully Sent.<br>';
//DÉBUT de l'envoie de courriel pour la partie DE Sukh Maghera

//FIN DE LA PARTIE DE SUKH MAGHERA



$message.= "<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DM: Elaine Macalolooy</h3></th>
	</tr>";
	
	
$messageElaineMacalolooy ="<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DM: Elaine Macalolooy</h3></th>
	</tr>";
	
//Elaine's Stores
$message88435 =$messageElaineMacalolooy;//Elaine Macalolooy	
		
//DÉBUT DE LA PARTIE DE ELAINE MACALOLOOY 




//#3ieme  Début Magasin #88435
$store				= "88435";
$StoreDescription	= "#88435-WEST EDMONTON";
$store_category 	= "B";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th  bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau \"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$message.= '</table><br><br><br>';
//B)Inserer dans le string qui accumule uniquement les STATS des magasins d'Elaine MACALOLOOY 
$messageElaineMacalolooy.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$messageElaineMacalolooy.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$messageElaineMacalolooy.= '</table><br><br><br>';
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message88435.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message88435.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);
$message88435.= '</table><br><br><br>';
//Fin Magasin #88435


//Envoie des courriels concernant les magasins d'Elaine MACALOLOOY: individuellement à chaque magasin.

//Envoie du courriel  #88435
$subject ="HBC Incentive Report for Store #88435 Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport 88435 en cours..<br>';
echo 'Contenu du rapport:<br>'. $message88435;
$response=office365_mail($to_address, $from_address, $subject, null, $message88435);
echo '<br>Report for #88435:Sucessfully Sent.<br>';

//Envoie du rapport par courriel  de TOUS ses magasins combinés à Sukh Maghera
$subject ="HBC Incentive Report for DM Elaine MACALOLOOY Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE	
echo '<br><br>Envoie du rapport d\'Elaine MACALOLOOY  en cours..<br>';
echo 'Contenu du rapport:<br>'. $messageElaineMacalolooy;
$response=office365_mail($to_address, $from_address, $subject, null, $messageElaineMacalolooy);
echo '<br>Report for Elaine MACALOLOOY :Sucessfully Sent.<br>';
//DÉBUT de l'envoie de courriel pour la partie DE Sukh Maghera

//FIN ENVOIE DES COURRIELS POUR LA PARTIE ELAINE MACALOLOOY

//FIN DE LA PARTIE DE ELAINE MACALOLOOY



$TotalHBC= CalculerTotauxHBC("88403",$date1,$date2) + CalculerTotauxHBC("88405",$date1,$date2) +CalculerTotauxHBC("88408",$date1,$date2)
+CalculerTotauxHBC("88409",$date1,$date2) +CalculerTotauxHBC("88411",$date1,$date2) +CalculerTotauxHBC("88414",$date1,$date2) 
+CalculerTotauxHBC("88416",$date1,$date2) +CalculerTotauxHBC("88431",$date1,$date2) +CalculerTotauxHBC("88433",$date1,$date2) 
+CalculerTotauxHBC("88434",$date1,$date2) +CalculerTotauxHBC("88435",$date1,$date2) +CalculerTotauxHBC("88438",$date1,$date2) 
+CalculerTotauxHBC("88439",$date1,$date2) +CalculerTotauxHBC("88440",$date1,$date2) +CalculerTotauxHBC("88444",$date1,$date2) +CalculerTotauxHBC("88449",$date1,$date2);


echo 'TOTALHBC:' . $TotalHBC;

$calcul60Pourcent = ($TotalHBC * 60) / 100;

$TotalHBC = number_format($TotalHBC, 2);
$calcul60Pourcent = number_format($calcul60Pourcent, 2);
//Fin Magasin #88435

$message.="<br><br>
<table width='60' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr>
<th>100%</th>
<th>60%</th>
</tr>
<tr>
<td>$TotalHBC$</td>
<td>$calcul60Pourcent$</td>
</tr>";



echo $message;	

//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.

$subject ="Global HBC Incentive Report between $date1-$date2 [All Stores]";
$Report_Email	= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//A COMMENTER	
//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE	
$to_address		= $Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";


?>





<?php 

function CalculerIncentiveHBC($Userid_Magasin,$Description_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88449
	2-Description du magasin Ex: 88449-Mississauga
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../connexion_hbc.inc.php');
	//echo "<br><br>Fonction CalculerIncentiveHBC()<br>";
	
	$SelectedStoreTotal=0;//Initialiser le total par magasin
	
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesofThisStore = 
	"SELECT distinct salesperson_id FROM orders
	
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id='$Userid_Magasin'
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl')
	AND salesperson_id NOT LIKE '%accounting%'
	ORDER BY salesperson_id";
	
	//echo $queryDistinctEmployeesofThisStore.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesofThisStore) or die  ('I cannot select items because #2g: '. $queryDistinctEmployeesofThisStore . mysqli_error($con));
	
	
	/*$message.= "
		<tr>
			<th align=\"center\">&nbsp;</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\" colspan=\"5\">PROGRESSIVE</th>
			<th bgcolor=\"#B15E6C\" align=\"center\" colspan=\"4\">OTHER</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"2\">TOTAL</th>
		</tr>";*/
		
		$message.= "
		<tr>
			<th align=\"center\">&nbsp;</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"2\">TOTAL</th>
		</tr>";
	
	
	/*$message.= "
		<tr>
			<th align=\"center\">Employee</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">HD IOT(A)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Precision Advance(B)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">iFree(C)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Maxiwide(D)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Office HD(E)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">ABC Warranty(F)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">AR+ETC(G)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">XLR(H)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">Trans/Polar(I)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total(N)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">60% of Total(O)</th>
		</tr>";*/
		
		$message.= "
		<tr>
			<th bgcolor=\"#DAE0F2\" align=\"center\"></th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">60% of Total</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
		

		
		//Promo A: HD IOT 	
			$Description_BonusA  	= "HD IOT (1$/job)";  	//Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA 	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " order_product_name LIKE '%HD IOT%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerA	 	=  " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idA') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusA
			AND  $Coating_A_FiltrerA 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Precision Advance
			$Description_BonusB  	= "PRECISION ADVANCE (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " order_product_name LIKE '%Precision Advance%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB	 	=  " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idB') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB
			AND  $Coating_A_FiltrerB
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		//Promo C: iFree
			$Description_BonusC  	= "iFree (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC 	= "order_product_name like '%ifree%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idC') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND  $Coating_A_FiltrerC 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
		//Fin partie Promo C
		
		
		//Promo D: Maxiwide
			$Description_BonusD  	= "Maxiwide (4$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 4;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD 	= "order_product_name like '%maxiwide%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerD 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idD') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD
			AND  $Coating_A_FiltrerD
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD  * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
		//Fin partie Promo F

		
		

		//Promo E: Office HD
			$Description_BonusE  	= "Office HD (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE 	= "order_product_name like '%i-Office%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idE') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND  $Coating_A_FiltrerE 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE  * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
		//Fin partie Promo E	
		
	
		
		
		
		
		
		//Promo F:ABC Warranty
			$Description_BonusF  	= "ABC Warranty (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF 	= " Warranty like '%Extended Warranty%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idF') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND  $Coating_A_FiltrerF 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF  * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F
		
		
		
			//Promo G:AR+ETC
			$Description_BonusG  	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG 	= " (order_product_name like '%AR+ETC%' OR order_product_coating='SPC' OR order_product_coating='SPC Backside' OR order_product_name like '%AR Backside%' OR order_product_name like '%StressFree%' )  "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerG 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idG') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG
			AND  $Coating_A_FiltrerG 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
		
			//echo '<br>Query:'. $queryIncentiveG.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG  * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G
		
		
		
			//Promo H:XLR
			$Description_BonusH  	= "XLR (3$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH 	= " (order_product_name like '%Maxivue%' OR order_product_name like '%XLR%' OR order_product_coating in ('MaxiVue2','MaxiVue2 Backside')) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idH') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND  $Coating_A_FiltrerH 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			//echo $queryIncentiveH. '<br><br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH  * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H
		
		
		
		
			//Promo I:Transitions/Polarized
			$Description_BonusI  	= "Transitions/Polarized (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 3;								//Définir la valeur de ce bonus: x$/Commande
			$user_idI	 		 	= $Userid_Magasin;					//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idI') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND  $Coating_A_FiltrerI
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI  * $Nbr_Bonus_AtteintI;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI;
			//Fin partie Promo I
		
		
		$SelectedStoreTotal += $ResultatValeurBonusCourrantA + $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI;
		
		
		//Afficher les résultats
		$message.= "<tr>
					<td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
		
				
		
					//PROG:HD IOT
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		
		
					//PROG:Precision Advance
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$</td>";
		
		
					//PROG:iFree
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . " x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$</td>";
		
					
		
					//PROG:Maxiwide
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . " x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$</td>";
		
		
					
					//OFFICE:Office HD
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintE . " x $ValeurBonusE$ ="."$ResultatValeurBonusCourrantE$</td>";
		
		
	
					//OTHER:ABC Warranty
		//$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintF . " x $ValeurBonusF$ ="."$ResultatValeurBonusCourrantF$</td>";
		
		
				   //OTHER:AR+ETC
		//$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintG . " x $ValeurBonusG$ ="."$ResultatValeurBonusCourrantG$</td>";
		
		
		   			//OTHER:XLR
		//$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintH . " x $ValeurBonusH$ ="."$ResultatValeurBonusCourrantH$</td>";
		
					//OTHER:Transitions/Polarized
		//$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintI . " x $ValeurBonusI$ ="."$ResultatValeurBonusCourrantI$</td>";
		

		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		
		$SoixantePourcentTotalForthisEmployee=0.6*$TotalIncentiveForthisEmployee;
		$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	$SoixantePourcentTotalforStore = 0.6 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	
  /* $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"10\" align=\"right\">TOTAL FOR STORE $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";*/
	
	 $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\" align=\"right\">TOTAL FOR STORE $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";
	
	$GrandTotalpourTouslesHBC = $GrandTotalpourTouslesHBC + $SelectedStoreTotal;
	
	$message.= "<tr>
			<th colspan=\"5\" align=\"right\">&nbsp;</th>
		</tr>";
	return $message;
}//END FUNCTION CalculerIncentiveHBC



















function CalculerTotauxHBC($Userid_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88449
	2-Description du magasin Ex: 88449-Mississauga
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../connexion_hbc.inc.php');
	//echo "<br><br>Fonction CalculerIncentiveHBC()<br>";
	
	$SelectedStoreTotal=0;//Initialiser le total par magasin
	
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesofThisStore = 
	"SELECT distinct salesperson_id FROM orders
	
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id='$Userid_Magasin'
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl')
	AND salesperson_id NOT LIKE '%accounting%'
	ORDER BY salesperson_id";
	
	//echo $queryDistinctEmployeesofThisStore.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesofThisStore) or die  ('I cannot select items because #2g: '. $queryDistinctEmployeesofThisStore . mysqli_error($con));
	
	

	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
			
		//Promo A: HD IOT 	
			$Description_BonusA  	= "HD IOT (1$/job)";  	//Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA 	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " order_product_name LIKE '%HD IOT%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerA	 	=  " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idA') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusA
			AND  $Coating_A_FiltrerA 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Precision Advance
			$Description_BonusB  	= "PRECISION ADVANCE (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " order_product_name LIKE '%Precision Advance%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB	 	=  " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idB') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB
			AND  $Coating_A_FiltrerB
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		//Promo C: iFree
			$Description_BonusC  	= "iFree (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC 	= "order_product_name like '%ifree%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idC') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND  $Coating_A_FiltrerC 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
		//Fin partie Promo C
		
		
		//Promo D: Maxiwide
			$Description_BonusD  	= "Maxiwide (4$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 4;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD 	= "order_product_name like '%maxiwide%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerD 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idD') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD
			AND  $Coating_A_FiltrerD
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD  * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
		//Fin partie Promo F

		
		

		//Promo E: Office HD
			$Description_BonusE  	= "Office HD (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE 	= "order_product_name like '%i-Office%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idE') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND  $Coating_A_FiltrerE 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE  * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
		//Fin partie Promo E	
		
	
		
		
		
		
		
		//Promo F:ABC Warranty
			$Description_BonusF  	= "ABC Warranty (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF 	= " Warranty like '%Extended Warranty%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idF') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND  $Coating_A_FiltrerF 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF  * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F
		
		
		
			//Promo G:AR+ETC
			$Description_BonusG  	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG 	= " (order_product_name like '%AR+ETC%' OR order_product_coating='SPC' OR order_product_coating='SPC Backside' OR order_product_name like '%AR Backside%' OR order_product_name like '%StressFree%' )  "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerG 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idG') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG
			AND  $Coating_A_FiltrerG 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
		
			//echo '<br>Query:'. $queryIncentiveG.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG  * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G
		
		
		
			//Promo H:XLR
			$Description_BonusH  	= "XLR (3$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH 	= " (order_product_name like '%Maxivue%' OR order_product_name like '%XLR%' OR order_product_coating in ('MaxiVue2','MaxiVue2 Backside')) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idH') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND  $Coating_A_FiltrerH 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			//echo $queryIncentiveH. '<br><br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH  * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H
		
				
			//Promo I:Transitions/Polarized
			$Description_BonusI  	= "Transitions/Polarized (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 3;								//Définir la valeur de ce bonus: x$/Commande
			$user_idI	 		 	= $Userid_Magasin;					//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idI') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND  $Coating_A_FiltrerI
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI  * $Nbr_Bonus_AtteintI;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI;
			//Fin partie Promo I
		
		
		$SelectedStoreTotal += $ResultatValeurBonusCourrantA + $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI ;
		

		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		$SoixantePourcentTotalForthisEmployee=0.6*$TotalIncentiveForthisEmployee;
		$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	$SoixantePourcentTotalforStore = 0.6 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	$message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"10\" align=\"right\">TOTAL FOR STORE $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";
	

	return $SelectedStoreTotal;
}//END FUNCTION CalculerTotauxHBC
$time_start  = microtime(true);	
?>
