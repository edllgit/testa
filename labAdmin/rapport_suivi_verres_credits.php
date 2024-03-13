<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
$time_start = microtime(true);   

session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/'>here</a> to login.";
	exit();
}

$redoReasonsIncluded = "2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97";
  
//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);

$ladatedunmois  = strtotime("-1 month");
$Ilya1mois     	  = date("Y-m-d", $ladatedunmois);

$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui = date("Y-m-d", $ladate);

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
$resultAccess= mysqli_query($con,$queryAccess)		or die ('Error'. $queryAccess .' ' . mysqli_error($con));
$AccessData  = mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);	
//echo '<br>Access Data:'.$AccessData[id] . '<br>';

if($AccessData[id]== 191){//Entrepot Laval
	$USER_ID = " AND orders.user_id  IN ('laval','lavalsafe')";
}elseif($AccessData[id]== 192){//Entrepot Trois-Rivieres
	$USER_ID = " AND orders.user_id  IN ('entrepotifc','entrepotsafe')";
}elseif($AccessData[id]== 193){//Entrepot Drummondville
	$USER_ID = " AND orders.user_id  IN ('safedr','entrepotdr')";
}elseif($AccessData[id]== 194){//EntrepotQC
	$USER_ID = " AND orders.user_id  IN ('entrepotqc')";
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
}else{
//DEFAULT
	$USER_ID = " AND orders.user_id  IN ('')";// Cache toutes les commandes si l'utilisateur accède a cette page sans être connecté
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
	
	$QueryReprise100Pourcent ="SELECT * FROM orders 	
	WHERE redo_order_num IS NOT NULL
	$USER_ID
	AND redo_reason_id IN ($redoReasonsIncluded)
	AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'
	AND statut_verres =''
	AND order_status NOT IN ('cancelled','on hold')";	

	//echo '<br>' .     $QueryReprise100Pourcent . '<br><br>';
	$ResultReprise100Pourcent  	= mysqli_query($con,$QueryReprise100Pourcent)		or die  ('I cannot select items 1a because: ' .$QueryReprise100Pourcent . ' ' . mysqli_error($con));
		
	echo "<h3>1- Nous attendons les verres du tableau jaune avant d'émettre vos crédits. <br>A mesure que vous ajoutez les verres dans la boîte pour nous les retourner, veuillez cliquez sur le lien Ajouter correspondant</h3>";
$message.= "<table class=\"table\" border=\"1\">

     <tr><td align=\"center\" bgcolor=\"#20639B\" colspan=\"5\"><b>Dates sélectionnées: [$Ilya6jours -$aujourdhui]</b></td></tr>";
	
$message.= "<tr>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Ajouter dans la boîte</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b># Originale IFC </b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"left\"><b>Produit</b></td>
	</tr>";
	
	//<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Crédit émis</b></td>

while ($DataReprise100Pourcent     = mysqli_fetch_array($ResultReprise100Pourcent,MYSQLI_ASSOC)){
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataReprise100Pourcent[order_num]";
	//echo $queryCreditEmis.'<br>'; 
	$ResultCreditEmis  	= mysqli_query($con,$queryCreditEmis)		or die  ('I cannot select items 1b because: ' . mysqli_error($con));
	$DataCreditemis     = mysqli_fetch_array($ResultCreditEmis,MYSQLI_ASSOC);
	$NombreCreditEmis = mysqli_num_rows($ResultCreditEmis);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message.= "<tr>
					<td  bgcolor=\"#F6D55C\" align=\"center\"><a href=\"ajouter_verres_boite.php?order_num=$DataReprise100Pourcent[order_num]\">Ajouter</a></td>
					<td  bgcolor=\"#F6D55C\" align=\"center\"><b>$DataReprise100Pourcent[redo_order_num]</b></td>	
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataReprise100Pourcent[order_patient_first] $DataReprise100Pourcent[order_patient_last]</td>
					<td  bgcolor=\"#F6D55C\" align=\"left\">$DataReprise100Pourcent[order_product_name]</td>
					
			</tr>";
			//<td  bgcolor=\"#F6D55C\" align=\"center\">$DataCreditemis[mcred_memo_num]</td>
	}//END IF
		
	
}//END WHILE

	$message.= "<tr><td colspan=\"9\" bgcolor=\"#cccccc\" align=\"left\"><b>*N.B.</b>Tant que ces verres n'auront pas été reçu, aucun crédit ne sera émis.";
	$message.="</td></tr></table>";
	echo $message;

	echo "<h3>2- Les verres du tableau vert ont été expédiés (ou sont en cours d'expédition) mais ils n'ont pas encore été traités par le département de crédit:</h3>";

    $QueryRepriseverresenvoyes ="SELECT * FROM orders 	
	WHERE redo_order_num IS NOT NULL
	$USER_ID
	AND redo_reason_id IN ($redoReasonsIncluded)
	AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'
	AND statut_verres <>''
	AND order_status NOT IN ('cancelled','on hold')
	ORDER BY statut_verres";	

	//echo '<br>' .     $QueryRepriseverresenvoyes . '<br><br>';
	$ResultVerresEnvoyes  	= mysqli_query($con,$QueryRepriseverresenvoyes)		or die  ('I cannot select items 1a because: ' .$QueryRepriseverresenvoyes . ' ' . mysqli_error($con));
		
	
$message2.= "<table class=\"table\" border=\"1\">

     <tr><td align=\"center\" bgcolor=\"#20639B\" colspan=\"5\"><b>Dates sélectionnées: [$Ilya6jours -$aujourdhui]</b></td></tr>";
	
$message2.= "<tr>
	<td  bgcolor=\"#76ff7a\" align=\"center\"><b>Retirer dans la boîte</b></td>
	<td  bgcolor=\"#76ff7a\" align=\"center\"><b>Numéro de commande IFC</b></td>	
	<td  bgcolor=\"#76ff7a\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#76ff7a\" align=\"left\"><b>Produit</b></td>
	<td  bgcolor=\"#76ff7a\" align=\"center\"><b>Date d'ajout dans la boîte</b></td>
	</tr>";
	
	while ($DataVerresEnvoyes     = mysqli_fetch_array($ResultVerresEnvoyes,MYSQLI_ASSOC)){
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataVerresEnvoyes[order_num]";
	//echo $queryCreditEmis.'<br>'; 
	$ResultCreditEmis  	= mysqli_query($con,$queryCreditEmis)		or die  ('I cannot select items 1b because: ' . mysqli_error($con));
	$DataCreditemis     = mysqli_fetch_array($ResultCreditEmis,MYSQLI_ASSOC);
	$NombreCreditEmis = mysqli_num_rows($ResultCreditEmis);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message2.= "<tr>
					<td  bgcolor=\"#76ff7a\" align=\"center\"><a href=\"retirer_verres_boite.php?order_num=$DataVerresEnvoyes[order_num]\">Retirer</a></td>
					<td  bgcolor=\"#76ff7a\" align=\"center\">$DataVerresEnvoyes[redo_order_num]<b></b></td>	
					<td  bgcolor=\"#76ff7a\" align=\"center\">$DataVerresEnvoyes[order_patient_first] $DataVerresEnvoyes[order_patient_last]</td>
					<td  bgcolor=\"#76ff7a\" align=\"left\">$DataVerresEnvoyes[order_product_name]</td>
					<td  bgcolor=\"#76ff7a\" align=\"center\">$DataVerresEnvoyes[statut_verres]</td>
			</tr>";
	}//END IF
	
	
	
}//END WHILE
$message2.= "</table>";
	
echo $message2;






//Afficher un tableau avec les 50% et 100% expédiés mais sans crédit émis depuis plus d'un mois, et une colonne pour voir si un crédit a été émis
echo "<h3>3- Les verres du tableau orange ont été expédiés depuis plus d'un mois mais aucun crédit émis:</h3>";

    $QueryRepriseverresenvoyes ="SELECT * FROM orders 	
	WHERE redo_order_num IS NOT NULL
	$USER_ID
	AND redo_reason_id IN ($redoReasonsIncluded)
	AND order_date_shipped > '$Ilya1mois'
	AND order_status NOT IN ('cancelled','on hold')";

	//echo '<br>' .     $QueryRepriseverresenvoyes . '<br><br>';
	$ResultVerresEnvoyes  	= mysqli_query($con,$QueryRepriseverresenvoyes)		or die  ('I cannot select items 1a because: ' .$QueryRepriseverresenvoyes . ' ' . mysqli_error($con));
		
	
$message3.= "<table class=\"table\" border=\"1\">

     <tr><td align=\"center\" bgcolor=\"#20639B\" colspan=\"5\"><b>Dates sélectionnées: Avant $Ilya1mois</b></td></tr>";
	
$message3.= "<tr>
	<td  bgcolor=\"#ED7014\" align=\"center\"><b>Numéro de commande IFC</b></td>	
	<td  bgcolor=\"#ED7014\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#ED7014\" align=\"center\"><b>Raison de reprise</b></td>
	<td  bgcolor=\"#ED7014\" align=\"left\"><b>Produit</b></td>
	<td  bgcolor=\"#ED7014\" align=\"left\"><b>Crédit émis</b></td>
	</tr>";
	
	while ($DataVerresEnvoyes     = mysqli_fetch_array($ResultVerresEnvoyes,MYSQLI_ASSOC)){
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataVerresEnvoyes[order_num]";
	$ResultCreditEmis  	= mysqli_query($con,$queryCreditEmis)		or die  ('I cannot select items 1b because: ' . mysqli_error($con));
	$DataCreditemis     = mysqli_fetch_array($ResultCreditEmis,MYSQLI_ASSOC);
	$NombreCreditEmis   = mysqli_num_rows($ResultCreditEmis);
	
	$queryRaisonReprise	   = "SELECT redo_reason_fr FROM redo_reasons WHERE redo_reason_id=  (select redo_reason_id from orders where order_num= $DataVerresEnvoyes[order_num])";
	$ResultRaisonReprise   = mysqli_query($con,$queryRaisonReprise)		or die  ('I cannot select items 1b because: ' . mysqli_error($con));
	$DataRaisonReprise     = mysqli_fetch_array($ResultRaisonReprise,MYSQLI_ASSOC);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message3.= "<tr>
					<td  bgcolor=\"#ED7014\" align=\"center\">$DataVerresEnvoyes[redo_order_num]/$DataVerresEnvoyes[order_num]R<b></b></td>	
					<td  bgcolor=\"#ED7014\" align=\"center\">$DataVerresEnvoyes[order_patient_first] $DataVerresEnvoyes[order_patient_last]</td>
					<td  bgcolor=\"#ED7014\" align=\"center\">$DataRaisonReprise[redo_reason_fr] </td>
					<td  bgcolor=\"#ED7014\" align=\"left\">$DataVerresEnvoyes[order_product_name]</td>
					<td  bgcolor=\"#ED7014\" align=\"center\">Pas encore</td>
			</tr>";
	}
	
}//END WHILE
$message3.= "</table>";
	
echo $message3;

 		
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