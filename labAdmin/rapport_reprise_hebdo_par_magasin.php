<?php
include('../Connections/directlens.php'); 
include "../includes/getlang.php";
//include('../../phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);   

session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/'>here</a> to login.";
	exit();
}
  
//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);

$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui = date("Y-m-d", $ladate);


$Ilya6jours = "2021-07-11";
$aujourdhui = "2021-08-04";

	
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
			
$totalFirstTimeOrder        = 0;
$MontanttotalFirstTimeOrder = 0;
$totalRedos                 = 0;
$MontanttotalRedos          = 0;
//Période sélectionnée
$CompteurReprisesGlobal 	= 0;	
$CompteurValeurReprisesGlobal 	= 0;
$CompteurVentesGlobal 	= 0;	
//An Dernier
$CompteurReprisesGlobal_An_Dernier 			= 0;	
$CompteurValeurReprisesGlobalAn_Dernier 	= 0;
$CompteurVentesGlobalAn_Dernier 			= 0;

//Période de comparaison
$CompteurReprisesGlobal_Comparaison 		= 0;	
$CompteurValeurReprisesGlobal_Comparaison 	= 0;
$CompteurVentesGlobalComparaison 			= 0;
	
$queryAccess = "SELECT * FROM access WHERE  id=" . $_SESSION["accessid"];
//echo '<br>'. $queryAccess . '<br>';
$resultAccess= mysql_query($queryAccess)		or die ('Error'. $queryAccess .' ' . mysql_error());
$AccessData  = mysql_fetch_array($resultAccess);	
//echo '<br>Access Data:'.$AccessData[id] . '<br>';

if($AccessData[id]== 191){//Entrepot Laval
	$USER_ID = " AND orders.user_id  IN ('laval','lavalsafe')";
}elseif($AccessData[id]== 192){//Entrepot Trois-Rivieres
	$USER_ID = " AND orders.user_id  IN ('entrepotifc','entrepotsafe')";
}elseif($AccessData[id]== 193){//Entrepot Drummondville
	$USER_ID = " AND orders.user_id  IN ('safedr','entrepotdr')";
}elseif($AccessData[id]== 196){//Optical Warehouse halifax
	$USER_ID = " AND orders.user_id  IN ('warehousehal','warehousehalsafe')";
}elseif($AccessData[id]== 199){//Entrepot Terrebonne
	$USER_ID = " AND orders.user_id  IN ('terrebonne','terrebonnesafe')";
}elseif($AccessData[id]== 203){//Entrepot Sherbrooke
	$USER_ID = " AND orders.user_id  IN ('sherbrooke','sherbrookesafe')";
}elseif($AccessData[id]== 208){//Entrepot Longueuil
	$USER_ID = " AND orders.user_id  IN ('longueuil','longueuilsafe')";
}elseif($AccessData[id]== 207){//Entrepot Levis
	$USER_ID = " AND orders.user_id  IN ('levis','levissafe')";
}elseif($AccessData[id]== 205){//Entrepot Chicoutimi
	$USER_ID = " AND orders.user_id  IN ('chicoutimi','chicoutimisafe')";
}elseif($AccessData[id]== 210){//Entrepot Granby
	$USER_ID = " AND orders.user_id  IN ('granby','granbysafe')";
}elseif($AccessData[id]== 221){//Entrepot Québec
	$USER_ID = " AND orders.user_id  IN ('entrepotquebec','quebecsafe')";

}elseif($AccessData[id]== 230){//Entrepot St-Jérôme Zone Tendance
	$USER_ID = " AND orders.user_id  IN ('stjerome','stjeromesafe')";
}elseif($AccessData[id]== 231){//Entrepot Gatineau ZT
	$USER_ID = " AND orders.user_id  IN ('gatineau','gatineausafe')";
}elseif($AccessData[id]== 219){//Optique Quebec
	$USER_ID = " AND orders.user_id  IN ('entrepotquebec','quebecsafe')";
}elseif($AccessData[id]== 240){//Edmundston
	$USER_ID = " AND orders.user_id  IN ('edmundston','edmundstonsafe')";
}elseif($AccessData[id]== 243){//Sorel-Tracy
	$USER_ID = " AND orders.user_id  IN ('sorel','sorelsafe')";
}elseif($AccessData[id]== 242){//Vaudreuil-Dorion
	$USER_ID = " AND orders.user_id  IN ('vaudreuil','vaudreuilsafe')";
}	
	

if ($Userid ==  " orders.user_id IN ('88666')"){
		//echo '<br>Partie Griffe TR. Cnnexion DB HBO en cours..';
		//Connexion DB HBO pour info reprises Griffé
		include("../../connexion_hbc.inc.php");
		//echo 'connecté';
}//END IF

	//REMETTRE EN COMMENTAIRE
	/*$AnneeEnCours="2022";
	$JourDebut = "04-01";
	$JourFin = "04-30";
	*/
	
	$QueryReprise100Pourcent ="SELECT * FROM ORDERS 	
	WHERE redo_order_num IS NOT NULL
	$USER_ID
	AND redo_reason_id IN (2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97)
	AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'
	AND statut_verres =''
	AND order_status NOT IN ('cancelled','on hold')";	

	echo '<br>' .     $QueryReprise100Pourcent . '<br><br>';
	$ResultReprise100Pourcent  	= mysql_query($QueryReprise100Pourcent)		or die  ('I cannot select items 1a because: ' .$QueryReprise100Pourcent . ' ' . mysql_error($con));
		
	echo "<h3>Nous attendons ces verres pour émettre vos crédits:</h3>";
$message.= "<table class=\"table\" border=\"1\">

     <tr><td align=\"center\" bgcolor=\"#20639B\" colspan=\"5\"><b>Dates sélectionnées: [$Ilya6jours -$aujourdhui]</b></td></tr>";
	
$message.= "<tr>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Ajouter dans la boîte</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Numéro de commande IFC</b></td>	
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"left\"><b>Produit</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Crédit émis</b></td>
	</tr>";

while ($DataReprise100Pourcent     = mysql_fetch_array($ResultReprise100Pourcent)){
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataReprise100Pourcent[order_num]";
	//echo $queryCreditEmis.'<br>'; 
	$ResultCreditEmis  	= mysql_query($queryCreditEmis)		or die  ('I cannot select items 1b because: ' . mysql_error($con));
	$DataCreditemis     = mysql_fetch_array($ResultCreditEmis);
	$NombreCreditEmis = mysql_num_rows($ResultCreditEmis);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message.= "<tr>
					<td  bgcolor=\"#F6D55C\" align=\"center\"><a href=\"ajouter_verres_boite.php?order_num=$DataReprise100Pourcent[order_num]\">Ajouter</a></td>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataReprise100Pourcent[order_num]<b></b></td>	
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataReprise100Pourcent[order_patient_first] $DataReprise100Pourcent[order_patient_last]</td>
					<td  bgcolor=\"#F6D55C\" align=\"left\">$DataReprise100Pourcent[order_product_name]</td>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataCreditemis[mcred_memo_num]</td>
			</tr>";
	}//END IF
	
	
	
}//END WHILE

	$message.= "<tr><td colspan=\"9\" bgcolor=\"#cccccc\" align=\"left\"><b>*N.B.</b>Tant que ces verres n'auront pas été reçu, aucun crédit ne sera émis. :<br><br>";
	$message.="</td></tr></table>
<br><br>";
	echo $message;

	echo "<h3>Ces verres ont été expédiés mais n'ont pas encore été traités:</h3><br>";

    $QueryRepriseverresenvoyes ="SELECT * FROM ORDERS 	
	WHERE redo_order_num IS NOT NULL
	$USER_ID
	AND redo_reason_id IN (2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97)
	AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'
	AND statut_verres <>''
	AND order_status NOT IN ('cancelled','on hold')";	

	echo '<br>' .     $QueryRepriseverresenvoyes . '<br><br>';
	$ResultVerresEnvoyes  	= mysql_query($QueryRepriseverresenvoyes)		or die  ('I cannot select items 1a because: ' .$QueryRepriseverresenvoyes . ' ' . mysql_error($con));
		
	
$message2.= "<table class=\"table\" border=\"1\">

     <tr><td align=\"center\" bgcolor=\"#20639B\" colspan=\"5\"><b>Dates sélectionnées: [$Ilya6jours -$aujourdhui]</b></td></tr>";
	
$message2.= "<tr>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Ajouter dans la boîte</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Numéro de commande IFC</b></td>	
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"left\"><b>Produit</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Crédit émis</b></td>
	</tr>";
	
	while ($DataVerresEnvoyes     = mysql_fetch_array($ResultVerresEnvoyes)){
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataVerresEnvoyes[order_num]";
	//echo $queryCreditEmis.'<br>'; 
	$ResultCreditEmis  	= mysql_query($queryCreditEmis)		or die  ('I cannot select items 1b because: ' . mysql_error($con));
	$DataCreditemis     = mysql_fetch_array($ResultCreditEmis);
	$NombreCreditEmis = mysql_num_rows($ResultCreditEmis);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message2.= "<tr>
					<td  bgcolor=\"#F6D55C\" align=\"center\"><a href=\"ajouter_verres_boite.php?order_num=$DataVerresEnvoyes[order_num]\">Ajouter</a></td>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataVerresEnvoyes[order_num]<b></b></td>	
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataVerresEnvoyes[order_patient_first] $DataVerresEnvoyes[order_patient_last]</td>
					<td  bgcolor=\"#F6D55C\" align=\"left\">$DataVerresEnvoyes[order_product_name]</td>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataCreditemis[mcred_memo_num]</td>
			</tr>";
	}//END IF
	
	
	
}//END WHILE
	
	
	echo $message2;



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
/*	
if($response){ 
	echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
}else{
	echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
}*/	
 		
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