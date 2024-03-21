<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$time_start = microtime(true);     

//POUR ADMINISTRATEURS SEULEMENT CAR CONTIENT LES $$$
$aWeekAgo      = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateaweekago  = date("Y-m-d",$aWeekAgo );
$aujourdhui    = date("Y-m-d");//Aujourd'hui, journee  ou le rapport est execute Ex 19 janvier contiendra du 13 au 19 janvier 2015

//RE COMMENCER CES DATES
/*
$dateaweekago  = "2021-03-14";
$aujourdhui    = "2021-03-20";
*/
//A REMETTRE EN COMMENTAIRE


	
if ($_REQUEST['email'] == 'no'){
	$SendEmail = 'no';
}elseif($_REQUEST['email'] == 'admin'){
	$SendEmail = 'no';
	$SendAdmin = 'yes';
}else{
	$SendEmail = 'yes';
}
if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}


$count    = 0;
$message  = "";
$message  = "<html>";
$message .= "
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
	<!-- Bootstrap core CSS -->
	<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
	<!-- Custom styles for this template -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
	<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
	<![endif]-->
</head>";

$message.="<body>
<table class=\"table\" border=\"1\">
<thead>
	<th align=\"center\">Company</th>
	<th align=\"center\">Nbr Orders (First time order)</th>
	<th align=\"center\">Total Purchase (First time order)</th>
	<th align=\"center\">Redos</th>
	<th align=\"center\">Total Purchase (Redos)</th>
	<th align=\"center\">%</th>
</thead>";
			
$totalFirstTimeOrder        = 0;
$MontanttotalFirstTimeOrder = 0;
$totalRedos                 = 0;
$MontanttotalRedos          = 0;
		
	
//FOR pour parcourir les Succursales
for ($i = 1; $i <= 20; $i++) {
   // echo '<br>'. $i;	
		
//Nouvelle partie
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Compagnie = 'L\'Entrepot de la lunette Trois-Rivieres';		$Succ = 'Trois-Rivieres'; 	break;
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Compagnie = 'L\'Entrepot de la lunette Drummondville';		$Succ = 'Drummondville';	break;
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Optical Warehouse Halifax'; 					$Succ = 'Halifax'; 	  		break;
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Laval';	  			$Succ = 'Laval';   			break;
	//case  5: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";       $Compagnie = 'L\'Entrepot de la lunette Montreal HBC ZT1';  	$Succ = 'MTL-ZT1 HBC';    	break;
	case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Compagnie = 'L\'Entrepot de la lunette Terrebonne'; 	  	  	$Succ = 'Terrebonne';		break;
	case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Compagnie = 'L\'Entrepot de la lunette Sherbrooke'; 		  	$Succ = 'Sherbrooke'; 		break;
	case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Compagnie = 'L\'Entrepot de la lunette Chicoutimi';		  	$Succ = 'Chicoutimi'; 		break;
	case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Lévis';      		  	$Succ = 'Lévis'; 			break;   
	case  9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Compagnie = 'L\'Entrepot de la lunette Longueuil';  			$Succ = 'Longueuil'; 		break;   
	case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Compagnie = 'L\'Entrepot de la lunette Granby';    			$Succ = 'Granby'; 			break;   
	case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";     $Compagnie = 'L\'Entrepot de la lunette Quebec';  			$Succ = 'Quebec'; 			break; 
	case 12: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";         $Compagnie = 'L\'Entrepot de la lunette Gatineau';  		 	$Succ = 'Gatineau'; 		break; 
	case 13: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";         $Compagnie = 'L\'Entrepot de la lunette St-Jérôme';  			$Succ = 'St-Jérôme'; 		break;
	case 14: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";     $Compagnie = 'L\'Entrepot de la lunette Edmundston';  		$Succ = 'Edmundston'; 		break;  	
	case 15: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";       $Compagnie = 'L\'Entrepot de la lunette Vaudreuil';  			$Succ = 'Vaudreuil'; 		break;  	
	case 16: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";       		  $Compagnie = 'L\'Entrepot de la lunette Sorel';  				$Succ = 'Sorel'; 			break;  	
	case 17: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";       	  $Compagnie = 'L\'Entrepot de la lunette Moncton';  			$Succ = 'Moncton'; 			break;  	
	case 18: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";   $Compagnie = 'L\'Entrepot de la lunette Fredericton'; 		$Succ = 'Fredericton'; 		break; 
	case 19: $Userid =  " orders.user_id IN ('88666')";                           $Compagnie = 'Griffe Trois-Rivieres'; 						$Succ = 'Griffe Lunetier #88666'; 		break;  
	case 20: $Userid =  " orders.user_id IN ('stjohn','stjohnsafe')";             $Compagnie = 'L\'Entrepot de la lunette St-John'; 		    $Succ = 'St-John'; 		break; 	 	
}//End Switch


$QueryNbrCommande ="SELECT count(order_num) AS NbrCommande, sum(order_total) as TotalPurchase
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS  NULL";	
//echo '<br>Query1: '.     $QueryNbrCommande . '<br>';
$ResultNbrCommande   = mysqli_query($con,$QueryNbrCommande)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
$DataNbrCommande     = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
$NbrTotaldeCommande  = $DataNbrCommande[NbrCommande];
$MontantdesCommandes = $DataNbrCommande[TotalPurchase];


$rptQueryRedos="SELECT accounts.company, sum( order_total ) AS TotalPurchaseRedo, count(order_num) as NbrRedo
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
ORDER BY TotalPurchaseRedo";	
//echo '<br>Query2: '.     $rptQueryRedos . '<br>';
$rptResultRedo  = mysqli_query($con,$rptQueryRedos)		or die  ('I cannot select items 2 because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
$NombreRedos    = $DataRedos[NbrRedo];
$ValeurdesRedos = $DataRedos[TotalPurchaseRedo];

$PourcentageReprise = ($NombreRedos/$NbrTotaldeCommande)*100;
$PourcentageReprise=money_format('%.2n',$PourcentageReprise);
$message .="
<tr>
	<td align=\"center\">$Compagnie</td>
	<td align=\"center\">$NbrTotaldeCommande</td>
	<td align=\"center\">$MontantdesCommandes$</td>
	<td align=\"center\">$NombreRedos</td>
	<td align=\"center\">$ValeurdesRedos</td>
	<td align=\"center\">$PourcentageReprise%</td>
</tr>";		
		
$totalFirstTimeOrder 	    += $NbrTotaldeCommande;
$MontanttotalFirstTimeOrder += $MontantdesCommandes;
$totalRedos 				+= $NombreRedos;
$MontanttotalRedos 			+= $ValeurdesRedos;
		
}//End FOR

$message.="
<tr>
	<td align=\"right\"><b>Totaux:</b></th>
	<td align=\"center\"><b>$totalFirstTimeOrder</b></td>
	<td align=\"center\"><b>$MontanttotalFirstTimeOrder$</b></td>
	<td align=\"center\"><b>$totalRedos</b></td>
	<td align=\"center\"><b>$MontanttotalRedos$</b></td>
</tr>
</table>";
		



//2ieme partie ne pas modifier


//Vente par lens category:Vaudreuil
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('vaudreuil','vaudreuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Vaudreuil</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('vaudreuil','vaudreuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('vaudreuil','vaudreuilsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Vaudreuil	
	
	
	
	
	
	
	
	
	
	
//Vente par lens category:Sorel
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sorel','sorelsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Sorel</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sorel','sorelsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('sorel','sorelsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Sorel	
	
	
	
	
	
	
	

//Vente par lens category:Edmundston
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('edmundston','edmundstonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Edmundston</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('edmundston','edmundstonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('edmundston','edmundstonsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Edmundston	







//Vente par lens category:Moncton
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('moncton','monctonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Moncton</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('moncton','monctonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('moncton','monctonsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Moncton




//Vente par lens category:Fredericton
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('fredericton','frederictonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Fredericton</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('fredericton','frederictonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('fredericton','frederictonsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Fredericton



//Vente par lens category:St-John
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjohn','stjohnsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot St-John</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjohn','stjohnsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('stjohn','stjohnsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin St-John

	
//Vente par lens category:Griffe
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Griffe lunetier</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";
	
	
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('88666') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('88666')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Griffe


	
/*	
//Vente par lens category:MTL-ZT1 HBC	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('montreal','montrealsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot MTL-ZT1 HBC</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('montreal','montrealsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";


$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('montreal','montrealsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr></table><br>";
*/	
//Fin MTL-ZT1 HBC

	
	
	
	
//Vente par lens category:EDLL-GRANBY	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('granby','granbysafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<br><tr><td align=\"center\" colspan=\"3\"><b>Entrepot Granby</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('granby','granbysafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('granby','granbysafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('granby','granbysafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('granby','granbysafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('granby','granbysafe') AND order_product_name like '%promo%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr><br>";

$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('granby','granbysafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('granby','granbysafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";


//Fin Granby
	
		
		
	
//Vente par lens category:EDLL-LE	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('levis','levissafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Levis</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('levis','levissafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('levis','levissafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('levis','levissafe') AND order_product_name like '%Maxiwide%'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('levis','levissafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('levis','levissafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('levis','levissafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];

$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('levis','levissafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
//Fin Lévis
	
		
		
//Vente par lens category:EDLL-CHI	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td  align=\"center\" colspan=\"3\"><b>Entrepot Chicoutimi</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('chicoutimi','chicoutimisafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('chicoutimi','chicoutimisafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";

$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('chicoutimi','chicoutimisafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('chicoutimi','chicoutimisafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('granby','granbysafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('granby','granbysafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
//Fin Chicoutimi
	
	
	
	
	
	
	
//Vente par lens category:EDLL-TR	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td  align=\"center\" colspan=\"3\"><b>Entrepot Trois-Rivieres</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	

$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('entrepotifc','entrepotsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Trois-Rivieres
		




//Vente par lens category:EDLL-DR	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdr','safedr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Drummondville</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdr','safedr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('entrepotdr','safedr')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

	
//Fin Drummondville




//Vente par lens category:EDLL-LAVAL
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('laval','lavalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Laval</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('laval','lavalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('laval','lavalsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('laval') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('laval','lavalsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('laval','lavalsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('laval','lavalsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('laval','lavalsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
//Fin Laval
		
		
//TERREBONNE
//Vente par lens category:EDLL-TE
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Terrebonne</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('terrebonne','terrebonnesafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
	
//Fin Terrebonne
		
		
			
		
		
//Sherbrooke
//Vente par lens category:EDLL-SH
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Sherbrooke</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";

$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('sherbrooke','sherbrookesafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";

//Fin Sherbrooke
		








	
//Longueuil
//Vente par lens category:EDLL-LG
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('longueuil','longueuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Longueuil</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('longueuil','longueuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('longueuil','longueuilsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";	
	
//Fin Longueuil


		




//Québec
//Vente par lens category:EDLL-QC
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Québec</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('entrepotquebec','quebecsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	

//Fin Québec








//Halifax
//Vente par lens category:COW-HAL
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Halifax</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('warehousehal','warehousehalsafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
	
	//Fin Halifax
	
	
	
	
	
//Gatineau
//Vente par lens category:EDLL-GAT
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('gatineau','gatineausafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot Gatineau</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('gatineau','gatineausafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id  IN ('gatineau','gatineausafe')  AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('gatineau','gatineausafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('gatineau','gatineausafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('gatineau','gatineausafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('gatineau','gatineausafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('gatineau','gatineausafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";
	
	//Fin Gatineau
	
	
	
	
//St-Jérôme
//Vente par lens category:STJ
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjerome','stjeromesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Entrepot St-Jérome</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN  ('stjerome','stjeromesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id  IN  ('stjerome','stjeromesafe')  AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN  ('stjerome','stjeromesafe') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN  ('stjerome','stjeromesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN   ('stjerome','stjeromesafe') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$rptQueryAdvance="SELECT count(order_num) as NbrAdvance FROM orders
WHERE orders.user_id IN ('stjerome','stjeromesafe') 
AND order_product_name like '%advance%' AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvance=mysqli_query($con,$rptQueryAdvance)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvance = mysqli_fetch_array($rptResultAdvance,MYSQLI_ASSOC);
$NbrAdvance  = $DataAdvance[NbrAdvance];


$rptQueryAdvanceRedo="SELECT count(order_num) as NbrAdvanceRedo FROM orders
WHERE orders.user_id IN ('stjerome','stjeromesafe')
 AND order_product_name like '%advance%' AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultAdvanceRedo=mysqli_query($con,$rptQueryAdvanceRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAdvanceRedo = mysqli_fetch_array($rptResultAdvanceRedo,MYSQLI_ASSOC);
$NbrAdvanceRedo  = $DataAdvanceRedo[NbrAdvanceRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";
	
	$message.="
	<tr>
		<td align=\"center\">Advance</td>
		<td align=\"center\">$NbrAdvance</td>
		<td align=\"center\">$NbrAdvanceRedo</td>
	</tr><br>";


	//Fin St-Jérôme
	
$message.="</table><br><br>";





//////////////////////////////////////////////////////////////////////////////////////////////
//Partie 2


//Vente par traitement vendus: Vaudreuil
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('vaudreuil','vaudreuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Vaudreuil</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Vaudreuil



//Vente par traitement vendus: Sorel
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sorel','sorelsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Sorel</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Sorel




//Vente par traitement vendus: Edmundston
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('edmundston','edmundstonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Edmundston</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Edmundston




//Vente par traitement vendus: Moncton
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('moncton','monctonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Moncton</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Moncton



//Vente par traitement vendus: Fredericton
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('fredericton','frederictonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Fredericton</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Fredericton



//Vente par traitement vendus: St-John
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjohn','stjohnsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot St-John</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin st-John


//Vente par traitement vendus: Griffé
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Griffe Lunetier</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Griffe

/*
//Vente par traitement vendus: MTL-ZT1 HBC
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('montreal','montrealsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot MTL-ZT1 HBC</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin MTL
*/






//Vente par traitement vendus: Granby
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('granby','granbysafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Granby</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Granby



//Vente par traitement vendus: Lévis
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('levis','levissafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="
<tr><td align=\"center\"  colspan=\"2\"><b>Entrepot Levis</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Lévis



//Vente par traitement vendus: Chicoutimi
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Chicoutimi</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin chicoutimi






//Vente par traitement vendus: trois-rivieres
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="
<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Trois-Rivieres</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
		
		
		
//Vente par traitement vendus: drummondville
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdr','safedr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3=mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult3,MYSQLI_ASSOC);
	
$count=0;	
$message.="<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Drummondville</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";





//Vente par traitement vendus: Laval
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('laval','lavalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Laval</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
	$bgcolor="#E5E5E5";
	else 
	$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
		
		
		
		
		

//Vente par traitement vendus: Terrebonne
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count	    = 0;	
	
$message.="<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Terrebonne</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
		
		
		
//Vente par traitement vendus: Sherbrooke
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
	
$count   = 0;	
$message.= "
<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Sherbrooke</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";



//Vente par traitement vendus: Longueuil
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('longueuil','longueuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
	
$count   = 0;	
$message.= "
<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Longueuil</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";

//


		
	
	
	
//Vente par traitement vendus: Québec
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
	
$count   = 0;	
$message.= "
<tr><td align=\"center\" colspan=\"2\"><b>Entrepot Quebec</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin QUÉBEC
			
		
		
		
//Vente par traitement vendus: Halifax
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td align=\"center\" colspan=\"2\"><b>COW-Halifax</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
//End Halifax




//Vente par traitement vendus: Gatineau
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('gatineau','gatineausafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td align=\"center\" colspan=\"2\"><b>EDLL-Gatineau</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
//End Gatineau



//Vente par traitement vendus: St-Jérome
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjerome','stjeromesafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td align=\"center\" colspan=\"2\"><b>EDLL ST-Jérome</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
//End St-Jérome
$message.="</table>";


	
























//PARTIE HBO pour Griffé
include("../connexion_hbc.inc.php");




//FOR pour parcourir les Succursales
for ($i = 1; $i <= 1; $i++) {
    echo '<br>'. $i;	
		
//Nouvelle partie
switch($i){
	case  1: $Userid =  " orders.user_id IN ('88666')";      $Compagnie = 'Griffé Trois-Rivieres';		$Succ = 'Griffé Trois-Rivieres'; 	break;
}//End Switch


$QueryNbrCommande ="SELECT count(order_num) AS NbrCommande, sum(order_total) as TotalPurchase
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS  NULL";	
echo '<br>Query1: '.     $QueryNbrCommande . '<br>';
$ResultNbrCommande   = mysqli_query($con,$QueryNbrCommande)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
$DataNbrCommande     = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
$NbrTotaldeCommande  = $DataNbrCommande[NbrCommande];
$MontantdesCommandes = $DataNbrCommande[TotalPurchase];


$rptQueryRedos="SELECT accounts.company, sum( order_total ) AS TotalPurchaseRedo, count(order_num) as NbrRedo
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
ORDER BY TotalPurchaseRedo";	
echo '<br>Query2: '.     $rptQueryRedos . '<br>';
$rptResultRedo  = mysqli_query($con,$rptQueryRedos)		or die  ('I cannot select items 2 because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
$NombreRedos    = $DataRedos[NbrRedo];
$ValeurdesRedos = $DataRedos[TotalPurchaseRedo];

$PourcentageReprise = ($NombreRedos/$NbrTotaldeCommande)*100;
$PourcentageReprise=money_format('%.2n',$PourcentageReprise);
$message .="
<table class=\"table\" border=\"1\">
<thead>
	<th align=\"center\">Company</th>
	<th align=\"center\">Nbr Orders (First time order)</th>
	<th align=\"center\">Total Purchase (First time order)</th>
	<th align=\"center\">Redos</th>
	<th align=\"center\">Total Purchase (Redos)</th>
	<th align=\"center\">%</th>
</thead>
<tr>
	<td align=\"center\">$Compagnie</td>
	<td align=\"center\">$NbrTotaldeCommande</td>
	<td align=\"center\">$MontantdesCommandes$</td>
	<td align=\"center\">$NombreRedos</td>
	<td align=\"center\">$ValeurdesRedos</td>
	<td align=\"center\">$PourcentageReprise%</td>
</tr>";		
		
$totalFirstTimeOrder 	    += $NbrTotaldeCommande;
$MontanttotalFirstTimeOrder += $MontantdesCommandes;
$totalRedos 				+= $NombreRedos;
$MontanttotalRedos 			+= $ValeurdesRedos;
		
}//End FOR






//Griffé Trois-Rivieres
//Vente par lens category
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NULL
GROUP BY lens_category ORDER BY Nbr_Category LIMIT 0 , 15000";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2=mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
	
$count   = 0;	
$message.= "<tr><td colspan=\"3\">&nbsp;</td></tr>
<tr><td align=\"center\" colspan=\"3\"><b>Griffé Trois-Rivières</b></td></tr>
<tr>
	<th align=\"center\">Lens Category</th>
	<th align=\"center\">Nbr Sold (First order)</th>
	<th align=\"center\">Nbr Redos</th>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){	

$rptQuery2Redos="SELECT  count(order_num) AS Nbr_Redos
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND lens_category = '$listItem2[lens_category]'
LIMIT 0 , 15000";

//echo '<br>$rptQuery2Redos:' . $rptQuery2Redos . '<br>';
$rptResult2Redos =mysqli_query($con,$rptQuery2Redos)		or die  ('I cannot select items because: ' . mysqli_error($con));
$Data2Redo=mysqli_fetch_array($rptResult2Redos,MYSQLI_ASSOC);
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
		
	 $message.="
	<tr>
		<td align=\"center\">$listItem2[lens_category]</td>
		<td align=\"center\">$listItem2[Nbr_Category]</td>
		<td align=\"center\">$Data2Redo[Nbr_Redos]</td>
	</tr>";
}//END WHILE

$rptQueryCamber="SELECT count(order_num) as nbrCamber FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamber=mysqli_query($con,$rptQueryCamber)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber = mysqli_fetch_array($rptResultCamber,MYSQLI_ASSOC);
$nbrCamber  = $DataCamber[nbrCamber];


$rptQueryCamberRedo  = "SELECT count(order_num) as nbrCamberRedo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%Maxiwide%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultCamberRedo = mysqli_query($con,$rptQueryCamberRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamberRedo = mysqli_fetch_array($rptResultCamberRedo,MYSQLI_ASSOC);
$nbrCamberRedo  = $DataCamberRedo[nbrCamberRedo];

$message.="
	<tr>
		<td align=\"center\">MaxiWide</td>
		<td align=\"center\">$nbrCamber</td>
		<td align=\"center\">$nbrCamberRedo</td>
	</tr>";


$rptQueryPromoDuo="SELECT count(order_num) as NbrPromoDuo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%promo%'
AND redo_order_num IS NULL
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuo=mysqli_query($con,$rptQueryPromoDuo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuo = mysqli_fetch_array($rptResultPromoDuo,MYSQLI_ASSOC);
$NbrPromoDuo  = $DataPromoDuo[NbrPromoDuo];


$rptQueryPromoDuoRedo="SELECT count(order_num) as NbrPromoDuoRedo FROM orders
WHERE orders.user_id IN ('88666') AND order_product_name like '%promo%'
AND redo_order_num IS NOT NULL
AND order_status NOT in ('cancelled')
AND  order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'";
$rptResultPromoDuoRedo=mysqli_query($con,$rptQueryPromoDuoRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoDuoRedo = mysqli_fetch_array($rptResultPromoDuoRedo,MYSQLI_ASSOC);
$NbrPromoDuoRedo  = $DataPromoDuoRedo[NbrPromoDuoRedo];

$message.="
	<tr>
		<td align=\"center\">Promo Duo</td>
		<td align=\"center\">$NbrPromoDuo</td>
		<td align=\"center\">$NbrPromoDuoRedo</td>
	</tr>";

//Fin Griffé-TR



//Vente par traitement vendus: Griffé-TR
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
$message.="<table width=\"50%\" class=\"table\" border=\"1\">
<tr><td align=\"center\"  colspan=\"2\"><b>Griffé Trois-Rivières</b></td></tr>
<tr>
	<td align=\"center\">Coating</td>
	<td align=\"center\">Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td align=\"center\">$listItem3[Coating]</td>
		<td align=\"center\">$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td colspan=\"2\">&nbsp;</td></tr>";
//Fin Griffé-TR



$message.="
</table><br><br>";

$to_address = array('rapports@direct-lens.com');	

//A RECOMMENTER
//$to_address = array('rapports@direct-lens.com');	

$curTime      = date("m-d-Y");	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Entrepots / Warehouse Sales between $dateaweekago and $aujourdhui";
//SEND EMAIL
if ($SendEmail == 'yes'){
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

if($SendAdmin == 'yes'){
	$to_address = array('rapports@direct-lens.com');
	$response=office365_mail($to_address, $from_address, $subject, null, $message);	
}


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


	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_semaine_vente_entrepot_admin_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/Semaine/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
if($response){ 
	echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
}else{
	echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
}	


echo $message;

//Logs	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport semaine ventes entrepots admin (avec  $$) 2.0', '$time','$today','$timeplus3heures','rapport_semaine_vente_entrepots_admin.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)	or die ( "Query failed: " . mysqli_error($con)); 
?>
   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
  </body>
</html>