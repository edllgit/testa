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
//PAS POUR LES  ADMINISTRATEURS SEULEMENT CAR NE CONTIENT PAS LES $$$
$aWeekAgo      = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateaweekago  = date("Y-m-d",$aWeekAgo );
$aujourdhui    = date("Y-m-d");//Aujourd'hui, journée  ou le rapport est exécuté Ex 19 janvier contiendra du 13 au 19 janvier 2015	

//A REMETTRE EN COMMENTAIRE
/*
$dateaweekago  = "2019-03-17";
$aujourdhui    = "2019-03-23";
*/

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

//Ventes totales pour chaque entrepot avec nombre de commandes
$rptQuery="SELECT accounts.company, sum( order_total ) AS TotalPurchase, count(order_num) as NbrOrder
FROM accounts, orders 
WHERE accounts.user_id IN ('entrepotifc', 'entrepotdr', 'laval', 'warehousehal','terrebonne','sherbrooke','chicoutimi','levis','longueuil','granby','entrepotquebec','gatineau','stjerome','edmundston','vaudreuil','sorel','fredericton','88666')
AND orders.user_id = accounts.user_id 
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY accounts.user_id 
ORDER BY TotalPurchase";
	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

$count   = 0;
$message = "";		
$message = "<html>";
$message.= "
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

$message.= "<body><table class=\"table\">";
$message.= "
<thead>
	<th>Company</th>
	<th>Nbr Orders</th>
</thead>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem[company]</td>
		<td>$listItem[NbrOrder]</td>
	</tr>";
}//END WHILE
$message.="</table><br><br>";
		






//Vente par lens category:Moncton
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('moncton','monctonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Moncton</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	




//Vente par lens category:Fredericton
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('fredericton','frederictonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Fredericton</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	



//Vente par lens category:Griffe
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Griffe Lunetier</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	



//Vente par lens category:Edmundston
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('edmundston','edmundstonsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Edmundston</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	


		
		
		
		
		
/*
//Vente par lens category:MTL-ZT1
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('montreal','montrealsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot MTL-ZT1 HBC</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";		
*/		
		
		

//Vente par lens category:EDLL-GRANBY
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('granby','granbysafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Granby</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";







	
//Vente par lens category:EDLL-LG	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('longueuil','longueuilsafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Longueuil</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
	
	
	
	
	
	
//Vente par lens category:EDLL-LE	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('levis','levissafe')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Levis</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
		
	
	
//Vente par lens category:EDLL-CHI	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('chicoutimi')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Chicoutimi</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
		
		
//Vente par lens category:EDLL-DR	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;
	
$message.="<tr><td><b>Entrepot Drummondville</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//FIN CHICOUTIMI
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
		
//Vente par lens category:EDLL-TR	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotifc')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;			
$message.= "
<table class=\"table\">
<tr><td><b>Entrepot Trois-Rivieres</b></td></tr>
<tr>
    <td>Lens Category</td>
    <td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
		
		
		
			
//Vente par lens category:EDLL-TERREBONNE	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('terrebonne')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Entrepot Terrebonne</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
		

//Vente par lens category:EDLL-LAVAL	
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('laval')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;
	
$message.="<tr><td><b>Entrepot Laval</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		



//Vente par lens category:EDLL-SHER
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sherbrooke')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Entrepot Sherbrooke</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";




//Vente par lens category:EDLL-QC
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdequebec')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Entrepot Quebec</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Vente par lens category:COW-HAL
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('warehousehal')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>COW-Halifax</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Vente par lens category:Gatineau
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('gatineau')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Gatineau</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Gatineau





//Vente par lens category:St-Jérome
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjerome')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>St-Jérome</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin St-Jérome




//Vente par lens category:Vaudreuil
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('vaudreuil')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Vaudreuil</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Vaudreuil




//Vente par lens category:Sorel
$rptQuery2="SELECT lens_category, count( lens_category ) AS Nbr_Category
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sorel')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY lens_category 
ORDER BY Nbr_Category";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery2 . '<br>';

$rptResult2 = mysqli_query($con,$rptQuery2)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult2);
$count      = 0;	

$message.="<tr><td><b>Sorel</b></td></tr>
<tr>
	<td>Lens Category</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem2=mysqli_fetch_array($rptResult2,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem2[lens_category]</td>
		<td>$listItem2[Nbr_Category]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Sorel


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
	
$message.="<tr><td><b>Entrepot Montréal ZT1 HBC</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Montreal
*/








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
	
$message.="<tr><td><b>Entrepot Moncton</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
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
	
$message.="<tr><td><b>Entrepot Fredericton</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Fredericton



//Vente par traitement vendus: Griffe
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
	
$message.="<tr><td><b>Griffe Lunetier</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Griffe



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
	
$message.="<tr><td><b>Entrepot Edmundston</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Edmundston








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
	
$message.="<tr><td><b>Entrepot Granby</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Granby








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
$count      = 0;
	
$message.="<tr><td><b>Entrepot Longueuil</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Longueuil



//Vente par traitement vendus: Levis
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
	
$message.="<tr><td><b>Entrepot Levis</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Lévis


//Vente par traitement vendus: Chicoutimi
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('chicoutimi')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;
	
$message.="<tr><td><b>Entrepot Chicoutimi</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin chicoutimi



//Vente par traitement vendus: trois-rivieres
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotifc')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;
	
$message.="<tr><td><b>Entrepot Trois-Rivieres</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
		
		
		
//Vente par traitement vendus: drummondville
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td><b>Entrepot Drummondville</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";





//Vente par traitement vendus: Laval
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('laval')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td><b>Entrepot Laval</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
		
		
		
	
//Vente par traitement vendus: Terrebonne
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('terrebonne')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	

$message.="<tr><td><b>Entrepot Terrebonne</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
		
				
		
		
//Vente par traitement vendus: Sherbrooke
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sherbrooke')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>';

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;
	
$message.="<tr><td><b>Entrepot Sherbrooke</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
				



	
//Vente par traitement vendus: Quebec
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('entrepotdequebec')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Quebec</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	




		
		
		
//Vente par traitement vendus: Halifax
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('warehousehal')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>COW-Halifax</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Halifax




//Vente par traitement vendus: Gatineau
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('gatineau')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Gatineau</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Gatineau



//Vente par traitement vendus: St Jérome
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('stjerome')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>St-Jérome</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin St Jérome




//Vente par traitement vendus: Vaudreuil
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('vaudreuil')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Vaudreuil</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Vaudreuil




//Vente par traitement vendus: Sorel
$rptQuery3="SELECT  order_product_coating as Coating, count(order_product_coating) as NbrSold
FROM orders, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key
AND orders.user_id IN ('sorel')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
GROUP BY order_product_coating 
ORDER BY nbrSold";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Sorel</b></td></tr>
<tr>
	<td>Coating</td>
	<td>Nbr Sold</td>
</tr>";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	<tr>
		<td>$listItem3[Coating]</td>
		<td>$listItem3[NbrSold]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Sorel


/*
//Redo Montreal HBC ZT1
$rptQuery3="SELECT  count(*) as nbrRedoMTL  FROM orders
WHERE  orders.user_id IN ('montreal')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>MTL-ZT1 HBC</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[nbrRedoMTL]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//FIN MTL-ZT1
*/





//Redo Moncton
$rptQuery3="SELECT  count(*) as nbrRedoMONCTON  FROM orders
WHERE  orders.user_id IN ('moncton')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>EDLL-Moncton</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[nbrRedoMONCTON]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Moncton




//Redo Fredericton
$rptQuery3="SELECT  count(*) as nbrRedoFREDERICTON  FROM orders
WHERE  orders.user_id IN ('fredericton')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>EDLL-Fredericton</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[nbrRedoFREDERICTON]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Fredericton



//Redo Griffe
$rptQuery3="SELECT  count(*) as nbrRedoGriffe  FROM orders
WHERE  orders.user_id IN ('88666')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>EDLL-Griffe</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[nbrRedoGriffe]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Griffe




//Redo Edmundston
$rptQuery3="SELECT  count(*) as nbrRedoEDM  FROM orders
WHERE  orders.user_id IN ('edmundston')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>EDLL-Edmundston</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[nbrRedoEDM]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";	
//Fin Edmundston



//Redo Halifax
$rptQuery3="SELECT  count(*) as NbrredoHalifax  FROM orders
WHERE  orders.user_id IN ('warehousehal')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>COW-Halifax</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoHalifax]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";		




//Redos Drummondville
$rptQuery3="SELECT  count(*) as NbrredoDrummondville  FROM orders
WHERE  orders.user_id IN ('entrepotdr')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Drummondville</b></td></tr>
<tr>
	<td>Redos: ";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	
		$listItem3[NbrredoDrummondville]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Redos Lévis
$rptQuery3="SELECT  count(*) as NbrredoLevis  FROM orders
WHERE  orders.user_id IN ('levis')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Levis</b></td></tr>
<tr>
	<td>Redos: ";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	
		$listItem3[NbrredoLevis]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Redos Chicoutimi
$rptQuery3="SELECT  count(*) as NbrredoLevis  FROM orders
WHERE  orders.user_id IN ('chicoutimi')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Chicoutimi</b></td></tr>
<tr>
	<td>Redos: ";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	
		$listItem3[NbrredoLevis]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";

//Redos Terrebonne
$rptQuery3="SELECT  count(*) as NbrredoTerrebonne  FROM orders
WHERE  orders.user_id IN ('terrebonne')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Terrebonne</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoTerrebonne]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Redos Laval
$rptQuery3="SELECT  count(*) as NbrredoLaval  FROM orders
WHERE  orders.user_id IN ('laval')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Laval</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoLaval]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";




//Redos Trois-Rivieres
$rptQuery3="SELECT  count(*) as NbrredoTR  FROM orders
WHERE  orders.user_id IN ('entrepotifc')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Trois-Rivieres</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoTR]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";



//Redos Sherbrooke
$rptQuery3="SELECT  count(*) as NbrredoSherbrooke  FROM orders
WHERE  orders.user_id IN ('sherbrooke')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Sherbrooke</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoSherbrooke]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";




//Redos Gatineau
$rptQuery3="SELECT  count(*) as NbrredoGatineau  FROM orders
WHERE  orders.user_id IN ('gatineau')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Gatineau</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoGatineau]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Gatineau



//Redos St-Jérome
$rptQuery3="SELECT  count(*) as NbrredoSTjerome FROM orders
WHERE  orders.user_id IN ('stjerome')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot St-Jérome</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoSTjerome]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Gatineau





//Redos Vaudreuil
$rptQuery3="SELECT  count(*) as NbrredoVaudreuil FROM orders
WHERE  orders.user_id IN ('vaudreuil')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Vaudreuil</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoVaudreuil]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Vaudreuil





//Redos Sorel
$rptQuery3="SELECT  count(*) as NbrredoVaudreuil FROM orders
WHERE  orders.user_id IN ('sorel')
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND order_status NOT in ('cancelled')
and redo_order_num is not null";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery3 . '<br>'; 

$rptResult3 = mysqli_query($con,$rptQuery3)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult3);
$count      = 0;	
		
$message.="<tr><td><b>Entrepot Sorel</b></td></tr>
<tr>
	<td>Redos";
				
while ($listItem3=mysqli_fetch_array($rptResult3,MYSQLI_ASSOC)){
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$message.="
	$listItem3[NbrredoVaudreuil]</td>
	</tr>";
}//END WHILE
$message.="<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
//Fin Sorel


$to_address = array('rapports@direct-lens.com');	
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
	$nomFichier = 'r_semaine_vente_entrepots_'. $timestamp;

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

echo 'envoie a '.var_dump($to_address); 


//Logs	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport semaine ventes entrepots (sans $$) 2.0)', '$time','$today','$timeplus3heures','rapport_semaine_vente_entrepots.php') "  ; 					
$cronResult      = mysqli_query($con,$CronQuery)	or die ( "Query failed: " . mysqli_error($con)); 
?>