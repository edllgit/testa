<?php 
/*
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

function CalculerIncentiveHBC($Description_Bonus,$Montant_Bonus, $Userid_Magasin, $Nom_Magasin, $Filtre_Nom_Produit, $FiltreCoating, $Date_Depart, $Date_Fin){
	/*
	Fonction avec 8 paramètres:
	1-Description du comment atteindre le bonus
	2-Valeur du Bonus
	3-User id du magasin
	4-Nom/Description du magasin
	5-Filtre à appliquer: nom de produit // OU requete personnalisée
	6-Filtre a appliquer: concerne le traitement aka Coating
	7-Date de début
	8-Date de fin
	*/
	include('../connexion_hbc.inc.php');
	
	//echo "<br><br>Fonction CalculerIncentiveHBC()";
	//PARTIE 1: SINGLE VISION +AR
	$Description_Bonus   = $Description_Bonus;  //Description de ce qui donne droit au bonus
	$ValeurBonus 		 = $Montant_Bonus;		//Définir la valeur de ce bonus: x$/Commande
	$user_id 	 		 = $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
	$Company	 		 = $Nom_Magasin;		//Définir le nom du magasin 
	$NomProduitPourBonus = $Filtre_Nom_Produit; //Nom de produit à utiliser pour le Filtre
	$Coating_A_Filtrer	 = $FiltreCoating; 		//Nom de coating à utiliser pour le Filtre		
	$date1				 = $Date_Depart;		//Définir  de début
	$date2				 = $Date_Fin;			//Définir  de  fin
	
	//Passer les différents salesperson ID dans un for un ou while
	
	$queryDistinctEmployeesofThisStore = 
	"SELECT distinct salesperson_id FROM orders
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id='$user_id'
	AND salesperson_id NOT IN ('21hbc-admin-all-access')
	ORDER BY salesperson_id";
	
	//echo '<br>requete: '.$queryDistinctEmployeesofThisStore.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesofThisStore) or die  ('I cannot select items because #2g: '. $queryDistinctEmployeesofThisStore . mysqli_error($con));
	echo "
		<tr align=\"center\" bgcolor=\"#000000\">
			<th colspan=\"9\"><h3><font color=\"white\">$Description_Bonus</font></h3></th>
		</tr>
		<tr>
			<th align=\"center\">Employee</th>
			<th align=\"center\">Jobs that qualifies</th>
			<th align=\"center\">($) Bonus per job</th>
			<th align=\"center\">Total ($)</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		
		
		//echo '<br>Employé en cours:<b>' . $DataDistinctEmployees[salesperson_id].': </b>';
		//La requête	
		$queryIncentive 	= "SELECT count(order_num) as Nbr_Bonus_Atteint FROM orders
		WHERE redo_order_num IS NULL 
		AND user_id IN ('$user_id') 
		AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
		AND $NomProduitPourBonus
		AND  $Coating_A_Filtrer 
		AND order_date_processed BETWEEN '$date1' AND '$date2'
		AND order_status NOT IN ('on hold', 'cancelled') 	
		ORDER BY order_product_name";

		//echo "<br><br>Query : ". $queryIncentive ."<br>";	
		$resultIncentive 	= mysqli_query($con, $queryIncentive)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentive . mysqli_error($con));
		$DataIncentive 		= mysqli_fetch_array($resultIncentive,MYSQLI_ASSOC);
		$Nbr_Bonus_Atteint = $DataIncentive[Nbr_Bonus_Atteint];	
		//Calcul du bonus total mérité pour SV+AR vendu par CET Employé 
		$ResultatValeurBonusCourrant = $ValeurBonus  * $Nbr_Bonus_Atteint;
		//echo ' Bonus: ' .$ResultatValeurBonusCourrant .'<br>';
		//Composer le résultat
		
		//Ajouter au cummul par employé
		$TotalIncentiveForthisEmployee+= $ResultatSvwithAREmployeeCourant;
		
		
			echo "<tr>
						<td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>
						<td align=\"center\">". $Nbr_Bonus_Atteint. "</td>
						<td align=\"center\">". $ValeurBonus. "$</td>
						<td align=\"center\">". $Nbr_Bonus_Atteint . " x $ValeurBonus$ = "."$ResultatValeurBonusCourrant $</td>
				</tr>";
		
		//Insérer les employés dans un Array identifié avec le numéro du magasin
		//$user_id[$Description_Bonus][$DataDistinctEmployees[salesperson_id]]= $ResultatValeurBonusCourrant;
		//Fin partie 1
		
		
	}//End While
		
	//echo '<br><br>';
	//'<br><br>Va sortir de la function CalculerIncentiveHBC';
	
	
}//End Function CalculerIncentiveHBC
		


$time_start  = microtime(true);	
$totalCharles = 0;

$date1        = date("Y-m-d");
$date2        = date("Y-m-d");

$date1        = "2019-06-01";
$date2        = "2019-06-30";

//HBC.CA PRODUCTION PART
$totalCharles = 0;
///Initialisation des variables
$Total_BY_DM_Johnny_Chow 		= 0;
$Total_BY_DM_Sukh_Maghera 		= 0;
$Total_BY_DM_Elaine_Macalolooy 	= 0;

	
//Prepare email 
$message="<html>";
$message.="<head><style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";
		$message.="<body>";
		
				

echo "
<table width='675' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th colspan=\"9\"><h3>HBC Incentive Report between $date1-$date2</b></h3></th>
	</tr>";

echo "
	<tr>
		<th colspan=\"9\"><h3>DM: Johnny Chow</b></h3></th>
	</tr>";

	
//#1er: 88403
$store				= "88403";
$StoreDescription	= "#88403-Bloor Street";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription</h3></th>
	</tr>";


//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
	


echo '<tr><td colspan="4">&nbsp;</td></tr>';
echo '<tr><td colspan="4">&nbsp;</td></tr>';




//#3ieme: 88408
$store				= "88408";
$StoreDescription	= "#88408-Oshawa";
$DM 				= "Johnny Chow";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription DM: <b>$DM</b></h3></th>
	</tr>";
//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);


echo '<tr><td colspan="4">&nbsp;</td></tr>';
echo '<tr><td colspan="4">&nbsp;</td></tr>';


//#3ieme: 88409
$store				= "88409";
$StoreDescription	= "#88409-Eglinton";
$DM 				= "Johnny Chow";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription DM: <b>$DM</b></h3></th>
	</tr>";
//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
	



	
echo '<tr><td colspan="4">&nbsp;</td></tr>';
echo '<tr><td colspan="4">&nbsp;</td></tr>';

//#3ieme: 88411
$store				= "88411";
$StoreDescription	= "#88411-Sherway";
$DM 				= "Johnny Chow";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription DM: <b>$DM</b></h3></th>
	</tr>";
//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);





	
echo '<tr><td colspan="4">&nbsp;</td></tr>';
echo '<tr><td colspan="4">&nbsp;</td></tr>';

//#3ieme: 88411
$store				= "88414";
$StoreDescription	= "#88414-Yorkdale";
$DM 				= "Johnny Chow";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription DM: <b>$DM</b></h3></th>
	</tr>";
//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN  ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);






echo '<tr><td colspan="4">&nbsp;</td></tr>';
echo '<tr><td colspan="4">&nbsp;</td></tr>';

//#3ieme: 88411
$store				= "88440";
$StoreDescription	= "#88440-Rideau";
$DM 				= "Johnny Chow";
echo "<tr>
		<th colspan=\"9\"><h3>Store:$StoreDescription DM: <b>$DM</b></h3></th>
	</tr>";
//1- SV + AR
CalculerIncentiveHBC("Single Vision with AR Orders (3$/job)",'3',$store,$StoreDescription,"order_product_name like '%vision%'"," order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//2- HD IOT + AR || Precision Advance  + AR
CalculerIncentiveHBC("HD IOT OR Precision Advance with AR ($/job)",'5',$store,$StoreDescription,"(order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')","order_product_coating NOT IN ('Hard Coat')",$date1,$date2);	
//3-Maxiwide + Maxivue2
CalculerIncentiveHBC("Maxiwide with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%maxiwide%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//4-iFree + Maxivue2
CalculerIncentiveHBC("iFree with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%ifree%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);
//5-iAction + Maxivue2
CalculerIncentiveHBC("i-Action with Maxivue (8$/job)",'8',$store,$StoreDescription,"order_product_name like '%i-action%'"," order_product_coating IN ('MaxiVue2','MaxiVue2 Backside')",$date1,$date2);



//FIN DE LA PARTIE DE JOHNNY CHOW



//var_dump($user_id);


//echo $message;	
//$subject ="HBC Incentive Report between $date1-$date2";
//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','riazzolino@opticalvisiongroup.com');
//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//A SUPPRIMER
//echo '<br><br>';
/*
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';

$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";
	$totalCharles = 0;
*/

*/	
?></strong>
