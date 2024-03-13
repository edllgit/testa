<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include('../sec_connectEDLL.inc.php'); 
include "../includes/getlang.php";
$time_start = microtime(true);   

session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/'>here</a> to login.";
	exit();
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
	$USER_ID = " AND orders.user_id  IN ('')";
}
	
$CommandeAmettreAjour= $_REQUEST[order_num];
	
$todayDate 		  = date("Y-m-d g:i a");// current date
$currentTime 	  = time($todayDate); //Change date into time
$timeAfterOneHour = $currentTime-((60*60)*4);
$datecomplete	  = date("Y-m-d H:i:s",$timeAfterOneHour);
	
	
$nouveau_status=	$datecomplete;
$LongeurOrderNum = strlen($_REQUEST[order_num]);

	if ($LongeurOrderNum==7){
		$QueryAjouterVerresBoite ="UPDATE ORDERS 
		SET	statut_verres='$nouveau_status'
		WHERE order_num = $CommandeAmettreAjour 
		$USER_ID";
		echo '<br>' .     $QueryAjouterVerresBoite . '<br><br>';
		$ResultAjouterVerresBoite  	= mysqli_query($con,$QueryAjouterVerresBoite)		or die  ('I cannot select items 1a because: ' .$QueryAjouterVerresBoite . ' ' . mysqli_error($con));
	}else{
		echo 'strlen($_REQUEST[order_num]:'. strlen($_REQUEST[order_num]);	
	}//End IF	
		
		
	//Rediriger l'employé vers le rapport	
	header("Location: ../labAdmin/rapport_suivi_verres_credits.php");/* go to admin home page */
 		
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