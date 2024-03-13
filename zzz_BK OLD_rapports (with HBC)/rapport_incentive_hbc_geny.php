<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/



/*
$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];

//Date du rapport
$ilya6jours  	= mktime(0,0,0,date("m"),date("d")-8,date("Y"));
$date1 = date("Y/m/d", $ilya6jours);

$ajd  			= mktime(0,0,0,date("m"),date("d")-2,date("Y"));
$date2     = date("Y/m/d", $ajd);



include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>date1: '. $date1;
echo '<br>date2: '. $date2;
//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau =4;	//Nombre de TD dans le tableau
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC GEN-Y Incentive Report Between $date1-$date2</h3></th>
	</tr>";
	



//Magasin #88403
$store				= "88403";
$StoreDescription	= "#88403-BLOOR";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);





//Magasin #88408
$store				= "88408";
$StoreDescription	= "#88408-OSHAWA";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88409
$store				= "88409";
$StoreDescription	= "#88409-EGLINTON";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88411
$store				= "88411";
$StoreDescription	= "#88411-SHERWAY";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88414
$store				= "88414";
$StoreDescription	= "#88414-YORKDALE";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);


//Magasin #88416
$store				= "88416";
$StoreDescription	= "#88416-Vancouver DTN";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88431
$store				= "88431";
$StoreDescription	= "#88431-Calgary DTN";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);


//Magasin #88433
$store				= "88433";
$StoreDescription	= "#88433-Polo Park";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);

//Magasin #88434
$store				= "88434";
$StoreDescription	= "#88434-Market Mall";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);

//Magasin #88435
$store				= "88435";
$StoreDescription	= "#88435-West Edmonton";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88438
$store				= "88438";
$StoreDescription	= "#88438-Metrotown";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);


//Magasin #88439
$store				= "88439";
$StoreDescription	= "#88439-Langley";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);


//Magasin #88440
$store				= "88440";
$StoreDescription	= "#88440-Rideau";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);



//Magasin #88444
$store				= "88444";
$StoreDescription	= "#88444-Mayfair";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>$StoreDescription</h3></th></tr>";
$message.=CalculerIncentiveHBC($store,$StoreDescription,$date1,$date2);


//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';

//Envoie du rapport par courriel  de TOUS ses magasins combinés à Johnny Chow
$subject ="Gen-Y HBC Incentive Report Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','riazzolino@opticalvisiongroup.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','tzelardonis@gmail.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com');//LIVE
//$to_address	= array('dbeaulieu@direct-lens.com');//A COMMENTER
echo '<br><br>Envoie du rapport en cours..<br>';
echo 'Contenu du rapport:<br>'. $messageJohnnyChow;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>Report for Johnny Chow:Sucessfully Sent.<br>';
//DÉBUT de l'envoie de courriel pour la partie DE Johnny Chow

echo $message;	

//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.

$subject ="Global HBC GEN-Y Incentive Report between $date1-$date2 [All Stores]";

	
$to_address		= $Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';

echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";
*
?>





<?php 

function CalculerIncentiveHBC($Userid_Magasin,$Description_Magasin,$date1,$date2){
	/*
	
	//Fonction avec 4 paramètres:
	//1-User id du magasin Ex:88440
	//2-Description du magasin Ex: 88440-Rideau
	//3-Date de début
	//4-Date de fin
	
	
	// Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	
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
	
	
	
		
		$message.= "
		<tr>
			<th align=\"center\">&nbsp;</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"1\">Bonus for all Gen-y</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"1\">Bonus for Specific Gen-y models</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"1\">TOTAL</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
		//Promo A: Monture de la collection GEN-Y
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA 	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$Coating_A_FiltrerA	 	=  " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(orders.order_num) as Nbr_Bonus_AtteintA FROM orders, extra_product_orders WHERE orders.user_id IN ('$user_idA') AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2' AND extra_product_orders.supplier='GEN-Y'  AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold') AND salesperson_id='$DataDistinctEmployees[salesperson_id]'";
			//echo '<br>query:'. $queryIncentiveA.'<br>';
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Precision Advance
			$Description_BonusB  	= "Montures GEN-Y specifique qui donne 1$/vente"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 1;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " order_product_name LIKE '%Precision Advance%' "; //Nom de produit à utiliser pour le Filtre	
			//La requête	
			$queryIncentiveB 	    = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id IN ('$user_idA') AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2' AND extra_product_orders.supplier='GEN-Y'  AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold') AND salesperson_id='$DataDistinctEmployees[salesperson_id]'";
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveB . mysqli_error($con));
			
			$TotalEmployeCourant = 0;
			while ($DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC)){
				//Si la monture correspond a une de celles du fichier de Roberto, on augmente le bonus de cet employé de 1$
				$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
				WHERE order_num = $DataIncentiveB[order_num] AND category in ('Frame')";
				$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
				//echo '<br><br>$queryFrame:' . $queryFrame. '<br>';
				$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
				$CouleurMontureEnMajuscule = strtoupper($DataFrame[color]);
				
				switch ($DataFrame[temple_model_num]){
					//List from roberto, Ticket #3806
					case '1634': if(($CouleurMontureEnMajuscule =='1') 	 || ($CouleurMontureEnMajuscule=='2')) 	 {$TotalEmployeCourant+=1;} break;
					case '1644': if(($CouleurMontureEnMajuscule =='2') 	 || ($CouleurMontureEnMajuscule=='3')) 	 {$TotalEmployeCourant+=1;} break;
					case '1701': if(($CouleurMontureEnMajuscule =='3')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1714': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1724': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;
					case '1737': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1738': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1744': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break; 
					case '1751': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1810': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1812': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1813': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1818': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1819': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1820': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1827': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1828': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1906': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$TotalEmployeCourant+=1;}	break;  
					case '1907': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
					case '1914': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$TotalEmployeCourant+=1;}	break;  
			}//END SWITCH
			
			
			
				 
			 }//END WHILE
			 
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		$SelectedStoreTotal += $ResultatValeurBonusCourrantA + $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI;
		
		
		//Afficher les résultats
		$message.= "<tr>
					<td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$TotalEmployeCourant 		= number_format($TotalEmployeCourant, 2);
		$BonusTotalpourCetEmployé 	= $TotalIncentiveForthisEmployee + $TotalEmployeCourant;
		$BonusTotalpourCetEmployé 	= number_format($BonusTotalpourCetEmployé, 2);
		
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalEmployeCourant ."$</th>";
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $BonusTotalpourCetEmployé ."$</th>";
		
		

	}//End While
	
	$SoixantePourcentTotalforStore = 0.6 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	

	
	$GrandTotalpourTouslesHBC = $GrandTotalpourTouslesHBC + $SelectedStoreTotal;
	
	$message.= "<tr>
			<th colspan=\"5\" align=\"right\">&nbsp;</th>
		</tr>";
	return $message;
	
	
}//END FUNCTION CalculerIncentiveHBC

*/
?>
