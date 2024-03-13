PAGE NOT USED ANYMORE

<?php 
//error_reporting(E_ALL); 
//ini_set('display_errors', '1');


/*
require_once(__DIR__.'/../constants/aws.constant.php');
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);

//Search errors of the day   
		   $rptQuery="SELECT * FROM erreurs_optipro 
		   WHERE  detail NOT LIKE '%a deja ete importee pour ce client%'
		   AND user_id NOT IN ('test')
		   AND order_num_optipro <> 0
		   ORDER BY  order_num_optipro, nombre_notification_succursale desc"; 
		   


if ($_REQUEST[delete_id] <> ''){
	//0-Aller chercher le user id concerné par l'erreur
	$queryUserid  = "SELECT user_id, order_num_optipro from erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
	$resultUserID = mysql_query($queryUserid) or die  ('I cannot select items because 222: ' . mysql_error());
	$DataUserID   = mysql_fetch_array($resultUserID);
	$UserID       = $DataUserID[user_id];
	$LorderNumOptipro  = $DataUserID[order_num_optipro];
	switch($UserID){
		case 'granby':      case 'granbysafe':      	$User_ID_IN = " ('granby','granbysafe') ";	       		break;
		case 'levis': 	    case 'levissafe':       	$User_ID_IN = " ('levis','levissafe') ";	       		break;
		case 'chicoutimi':  case 'chicoutimisafe':  	$User_ID_IN = " ('chicoutimi','chicoutimisafe') "; 		break;
		case 'entrepotifc': case 'entrepotsafe':    	$User_ID_IN = " ('entrepotifc','entrepotsafe') ";  		break;
		case 'entrepotdr':  case 'safedr':          	$User_ID_IN = " ('entrepotdr','safedr') ";         		break;
		case 'laval': 		case 'lavalsafe':       	$User_ID_IN = " ('laval','lavalsafe') ";           		break;
		case 'terrebonne':  case 'terrebonnesafe':  	$User_ID_IN = " ('terrebonne','terrebonnesafe') "; 		break;
		case 'sherbrooke':  case 'sherbrookesafe':  	$User_ID_IN = " ('sherbrooke','sherbrookesafe') ";		break;
		case 'longueuil':   case 'longueuilsafe':   	$User_ID_IN = " ('longueuil','longueuilsafe') ";   		break;
		case 'entrepotquebec': case 'quebecsafe': 		$User_ID_IN = " ('entrepotquebec','quebecsafe') "; 		break;	
		case 'warehousehal':  case 'warehousehalsafe':	$User_ID_IN = " ('warehousehal','warehousehalsafe') ";	break;	
		case 'montreal':  	case 'montrealsafe':		$User_ID_IN = " ('montreal','montrealsafe') ";			break;	
	}	
		
	//1-Vérifier si la commande a été 'transféré avec succès'
	$queryValiderPasser  = "SELECT count(order_num) as NbMatch FROM orders WHERE order_status <> 'cancelled' AND user_id IN $User_ID_IN AND order_num_optipro = $LorderNumOptipro";
	echo '<br>'. $queryValiderPasser;
	$resultValiderPasser = mysql_query($queryValiderPasser) or die  ('I cannot select items because 2222: ' . mysql_error());
	//$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysql_fetch_array($resultValiderPasser);
	$NbrMatch = $DataValiderPasser[NbMatch];
	echo '<br>NB MATCH:'. $NbrMatch;
	
	if ($NbrMatch == 1){
		//Signifie que la commande a été correctement transmise, 
		//ON DOIT EFFACER TOUTES LES ERREURS ID LIÉ A CETTE COMMANDE	
		$queryErreurIDs = "SELECT erreur_id FROM erreurs_optipro WHERE order_num_optipro = $LorderNumOptipro AND erreur_id <> $_REQUEST[delete_id]  AND user_id IN  $User_ID_IN ";
		echo '<br>'. $queryErreurIDs;
		$resultErreurIDs = mysql_query($queryErreurIDs) or die  ('I cannot select items because 33: ' . mysql_error());
		while ($DataErreurIDs=mysql_fetch_array($resultErreurIDs)){
			echo '<br><br>Autre ID a effacer:'.	$DataErreurIDs[erreur_id];
			$queryDelete  = "DELETE FROM erreurs_optipro WHERE erreur_id = $DataErreurIDs[erreur_id]";
			//echo '<br>'. $queryDelete;
			//NE PAS RÉELLEMENT EFFACER LE TEMPS DES TESTS DONC EN COMMENTAIRE
			$resultDelete = mysql_query($queryDelete) or die  ('I cannot delete  items because 5: ' . mysql_error());
		}//End While
	}else{
	echo '<br>Aucun match, la commande n\'a pas été transféré. Donc, aucun autre ID a effacer. ';	
	}//End IF
		
		
		//Effacer le tuple
		$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id] ORDER BY user_id, order_num_optipro";
		//echo '<br>'. $queryDetail;
		$resultDetail = mysql_query($queryDetail) or die  ('I cannot select items because 4: ' . mysql_error());
		$DataDetail   = mysql_fetch_array($resultDetail);
		
		//echo '<br>ID A EFFACER: ' . $_REQUEST[delete_id];
		$queryDelete  = "DELETE FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
		//echo '<br>'. $queryDelete;
		$resultDelete = mysql_query($queryDelete) or die  ('I cannot delete  items because 5: ' . mysql_error());
		echo '<br>Tuple Effacé..Redirection en cours';
		
		//Rediriger à la date ou la commande a été effacée
		if  ($DataDetail[date]<>''){
			header("Location: optipro_today.php");
			exit();	
		} 
}//End IF There is an ID to delete





if ($_REQUEST[aviser_id] <> ''){
	//Aviser la succursale de cette erreur par courriel
	$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_id] ORDER BY user_id, order_num_optipro";
	echo '<br>'. $queryDetail .'<br>';
	$resultDetail = mysql_query($queryDetail) or die  ('I cannot select items because 6: ' . mysql_error());
	$DataDetail   = mysql_fetch_array($resultDetail);
	
	switch($DataDetail[user_id]){
		case 'granby':      	case 'granbysafe':	      	$Succursale = "Granby";         $EmailSuccursale = "granby@entrepotdelalunette.com";         break;
		case 'levis': 	    	case 'levissafe':       	$Succursale = "Lévis";          $EmailSuccursale = "levis@entrepotdelalunette.com";          break;
		case 'chicoutimi':  	case 'chicoutimisafe':  	$Succursale = "Chicoutimi";     $EmailSuccursale = "chicoutimi@entrepotdelalunette.com";     break;
		case 'entrepotquebec':  case 'quebecsafe':   		$Succursale = "Québec";         $EmailSuccursale = "quebec@entrepotdelalunette.com";    	 break;
		case 'entrepotifc': 	case 'entrepotsafe':    	$Succursale = "Trois-Rivières"; $EmailSuccursale = "trois-rivieres@entrepotdelalunette.com"; break;
		case 'entrepotdr':  	case 'safedr':          	$Succursale = "Drummondville";  $EmailSuccursale = "drummondville@entrepotdelalunette.com";  break;
		case 'laval': 			case 'lavalsafe':       	$Succursale = "Laval"; 			$EmailSuccursale = "laval@entrepotdelalunette.com";          break;
		case 'terrebonne':  	case 'terrebonnesafe':  	$Succursale = "Terrebonne"; 	$EmailSuccursale = "terrebonne@entrepotdelalunette.com";     break;
		case 'sherbrooke':  	case 'sherbrookesafe':  	$Succursale = "Sherbrooke"; 	$EmailSuccursale = "sherbrooke@entrepotdelalunette.com";     break;
		case 'longueuil':   	case 'longueuilsafe':   	$Succursale = "Longueuil"; 		$EmailSuccursale = "longueuil@entrepotdelalunette.com";      break;
		case 'warehousehal':	case 'warehousehalsafe':   	$Succursale = "Halifax"; 		$EmailSuccursale = "halifax@opticalwarehouse.ca";     		 break;
		case 'montreal':		case 'montrealsafe':   		$Succursale = "Montréal"; 		$EmailSuccursale = "dbeaulieu@direct-lens.com";  break;
	}
	
	echo '<br><b>Succursale</b>:' .  $Succursale. 	
	     '<br><b>Email</b>: '     . $EmailSuccursale	
	   . '<br><b>Num commande optipro</b>:' .$DataDetail[order_num_optipro]	
	   . '<br><b>Produit demandé</b> :' .$DataDetail[produit_optipro]
	   . '<br><b>Detail</b>:' .$DataDetail[detail]
	   . '<br><b>ID</b>:' .$DataDetail[erreur_id]  
	   . '<br><b>Nbr nbotification</b>:' .$DataDetail[nombre_notification_succursale] ;  
	$NombrePrecedentNotification = $DataDetail[nombre_notification_succursale];
	
	
	//Préparer le courriel a envoyer a la succursale
	$message="";
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.='<body>';
	
	if ($Succursale <> "Halifax"){
	$message.="
		<p>Bonjour $Succursale, <br>il y a un problème avec votre facture Optipro <b>#$DataDetail[order_num_optipro]</b>.<br><br>
		Le problème est le suivant:<i><b>$DataDetail[detail]</b></i>.<br> <br>Merci de faire la correction nécessaire, sauvegarder puis ré-exporter la commande.<br>
		Ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
		<br><br>
		P.S. Si vous avez déja réussi à transférer cette commande, ne tenez pas compte de ce courriel.<br>
		Bonne journée.
		</p>";	
	}else{
	//La succursale étant Halifax, le email doit être envoyé en Anglais
		$message.="
		<p>Hi  $Succursale, <br> There is a problem with your Optipro Invoice <b>#$DataDetail[order_num_optipro]</b>.<br><br>
		The problem is the following:<i><b>$DataDetail[detail]</b></i>.<br> <br>Thanks to do the necessary updates, save and re-export the order.<br>
		Please do not reply to this email directly, since it won't be received. Ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
		<br><br>
		P.S. Si vous avez déja réussi à transférer cette commande, ne tenez pas compte de ce courriel.<br>
		Bonne journée.
		</p>";		
	}
	//Send EMAIL
			

	$send_to_address[] = $EmailSuccursale;	
	$curTime      = date("m-d-Y");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Erreur Optipro $DataDetail[order_num_optipro] $Succursale";
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Avant d'envoyer le courriel, on valide que la commande n'as pas été transféré avec succès
	$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id = '$DataDetail[user_id]' AND order_num_optipro = $DataDetail[order_num_optipro]";
	$resultValiderPasser = mysql_query($queryValiderPasser) or die  ('I cannot select items because 7: ' . mysql_error());
	$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysql_fetch_array($resultValiderPasser);
	if ($CountValiderPasser > 0){
		$TransfertReussi = 'oui';
	}else{
		$TransfertReussi = 'non';	
	}
	
	if ($TransfertReussi == 'non'){//La commande n'as pas encore été transféré dans Ifc/Safe
		if ($response){
			//TODO Enregistrer la notification à la succursale
			$NouveauNombreNotification   = $NombrePrecedentNotification + 1;
			//echo '<br>Nouveau nombre de notification a enregistrer'. $NouveauNombreNotification;
			$Datedujour      		 = date("Y-m-d");	
			$queryUpdateNotification = "UPDATE erreurs_optipro SET nombre_notification_succursale = $NouveauNombreNotification, date_derniere_notification = '$Datedujour' WHERE erreur_id =  $DataDetail[erreur_id] ";
			$resultUpdate            = mysql_query($queryUpdateNotification) or die  ('I cannot update items because: ' . mysql_error());
			//echo '<br>query:'. $queryUpdateNotification;
			echo '<br>Courriel envoyé..Redirection en cours';
			header("Location: optipro_today.php");
			exit();	
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	//Redirection vers optopro_today tout en avisant que la commande est déja transféré.
	//echo 'Cette commande a déja été transféré avec succès, il est donc inutile d\'aviser la succursale. ';
	//exit();	
	header("Location: optipro_today.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise




if ($_REQUEST[aviser_direction_id] <> ''){

	//Aviser la direction de cette erreur par courriel
	$queryDetail    = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_direction_id] ORDER BY user_id, order_num_optipro";
	//echo '<br>'. $queryDetail .'<br>';
	$resultDetail   = mysql_query($queryDetail) or die  ('I cannot select items because 6: ' . mysql_error());
	$DataDetail     = mysql_fetch_array($resultDetail);

	$NombreNotificationDirection = $DataDetail[nombre_notification_direction];
	$EmailDirection = "dbeaulieu@direct-lens.com";//TODO CHANGER POUR EMAIL DE KASSANDRA APRES MES TESTS

	switch($DataDetail[user_id]){
		case 'granby':      case 'granbysafe':      $Succursale = "Granby";         break;    
		case 'levis': 	    case 'levissafe':       $Succursale = "Lévis";          break;        
		case 'chicoutimi':  case 'chicoutimisafe':  $Succursale = "Chicoutimi";     break;   
		case 'entrepotquebec':  case 'quebecsafe': $Succursale = "Québec";          break;
		case 'entrepotifc': case 'entrepotsafe':    $Succursale = "Trois-Rivières"; break;
		case 'entrepotdr':  case 'safedr':          $Succursale = "Drummondville";  break;
		case 'laval': 		case 'lavalsafe':       $Succursale = "Laval"; 			break;
		case 'terrebonne':  case 'terrebonnesafe':  $Succursale = "Terrebonne"; 	break;
		case 'sherbrooke':  case 'sherbrookesafe':  $Succursale = "Sherbrooke"; 	break;
		case 'longueuil':   case 'longueuilsafe':   $Succursale = "Longueuil"; 		break;
		case 'warehousehal':case 'warehousehalsafe':$Succursale = "Halifax"; 		break;
		case 'montreal':   	case 'montrealsafe':	$Succursale = "Montreal"; 		break;
	}
	

	//Préparer le courriel a envoyer a la succursale
	$message="";
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	
	$message.='<body>';
	$message.="
	<p>Bonjour, <br><br>Il y a un problème avec la facture Optipro <b>#$DataDetail[order_num_optipro]</b> de<b> $Succursale</b>.<br><br>
	Le problème est le suivant: <i><b>$DataDetail[detail]</b></i>.<br> <br>La succursale a été avisé du problème au moins trois fois par courriel.<br>
	<br>Merci de ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
	<br><br>
	Bonne journée.
	</p>";	
	
	//echo '<br>'.$message;
	
		
	//Send EMAIL	
	//$send_to_address = array('rapports@direct-lens.com');	
	$send_to_address[] = $EmailDirection;	
	$curTime      = date("m-d-Y");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Erreur Optipro $DataDetail[order_num_optipro] $Succursale : Avis à la direction";
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Avant d'envoyer le courriel, on valide que la commande n'as pas été transféré avec succès
	$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id = '$DataDetail[user_id]' AND order_num_optipro = $DataDetail[order_num_optipro]";
	$resultValiderPasser = mysql_query($queryValiderPasser) or die  ('I cannot select items because 7: ' . mysql_error());
	$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysql_fetch_array($resultValiderPasser);
	if ($CountValiderPasser > 0){
		$TransfertReussi = 'oui';
	}else{
		$TransfertReussi = 'non';	
	}
	
	if ($TransfertReussi == 'non'){//La commande n'as pas encore été transféré dans Ifc/Safe
		if (($response) && ($NombreNotificationDirection==0)){
			//TODO Enregistrer la notification à la direction
			$NouveauNombreNotificationDirection   = $listItem[nombre_notification_direction] + 1;
			//echo '<br>Nouveau nombre de notification a enregistrer'. $NouveauNombreNotification;
			$Datedujour      		 = date("Y-m-d");	
			$queryUpdateNotificationDirection = "UPDATE erreurs_optipro SET nombre_notification_direction = $NouveauNombreNotificationDirection, date_derniere_notification_direction='$Datedujour'  WHERE erreur_id =  $DataDetail[erreur_id] ";
			$resultUpdate            = mysql_query($queryUpdateNotificationDirection) or die  ('I cannot update items because: ' . mysql_error());
			echo '<br>query:'. $queryUpdateNotification;
			echo '<br>Courriel envoyé..Redirection en cours';
			header("Location: optipro_today.php");
			exit();	
			echo '<h3>Envoie à la direction..réussie</h>';
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	header("Location: optipro_today.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise








?>
<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="charles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
<meta http-equiv="refresh" content="35"><!--Refresh every 70 seconds -->
</head>
<?php
$Aleatoire =  rand(1, 15);
switch($Aleatoire){
	case 1:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 2:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 3:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 4:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 5:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 6:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 7:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 8:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 9:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 10: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 11: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 12: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 13: $BGCOL = 'style="background-color:#F5BDEE"'; break;
	case 14: $BGCOL = 'style="background-color:#F5BDEE"'; break;
	case 15: $BGCOL = 'style="background-color:#F5BDEE"'; break;
}
?>
<body <?php echo $BGCOL; ?>>
<form  method="post" name="optipro_today" id="optipro_today" action="optipro_today.php">
    <div align="center">
     	<p align="center"><h3>Recherche parmis les erreurs d'importation Optipro</h3></p>
    
    
    <?php 
	if ($_REQUEST[message] <> ''){
		
		switch($_REQUEST[acct]){
			case 'granby':      case 'grabysafe':  		$Succ = "Granby"; 		  break;	
			case 'levis':       case 'levissafe':  		$Succ = "Lévis"; 		  break;
			case 'chicoutimi':  case 'chicoutimisafe':  $Succ = "Chicoutimi"; 	  break;	
			case 'entrepotifc': case 'entrepotsafe':    $Succ = "Trois-Rivières"; break;	
			case 'entrepotdr':  case 'safedr':  	    $Succ = "Drummondville";  break;
			case 'laval': 		case 'lavalsafe':       $Succ = "Laval"; 		  break;
			case 'terrebonne':  case 'terrebonne':      $Succ = "Terrebonne";     break;
			case 'sherbrooke':  case 'sherbrookesafe':  $Succ = "Sherbrooke";     break;
			case 'longueuil':   case 'longueuilsafe':   $Succ = "Longueuil";      break;
			case 'entrepotquebec':    case 'quebecsafe':  $Succ = "Quebec";     break;
			case 'warehousehal':  case 'warehousehalsafe':$Succ = "Halifax";     break;
			case 'montreal':  case 'montrealsafe'		:$Succ = "Montréal";     break;
		}
		
		switch($_REQUEST[message]){
		case 'dejatransfere': 
		echo '<div style="width:750px;background-color:#E6F18F;"><font color="#F50004">La commande #'.$_REQUEST[order_num_optipro].' de '. $Succ . ' a déja été transféré avec succès, il est donc inutile d\'aviser la succursale.</font></div>';   break;
		}//End Switch
		
	}//End if On a un message a afficher
	?>
        <input name="submit" type="submit" id="submit" value="Voir les erreurs d'aujourd'hui" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField">
        <label for="filter">Filtre</label>
		<input type="text" name="filter" value="" id="filter" />
    </div>
</form>


<form  method="post" name="optipro_today_part2" id="optipro_today_part2" action="optipro_today.php">
<?php 		
	$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because 1a: ' . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
	if ($usercount == 0){
	echo '<br><br><div align="center"><h3>Excellent travail, aucune erreur présentement!</h3></div>';	
	}
	if (($usercount != 0)){//some products were found
?>

	<table width="100%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th width="6%"  align="center">Date</th>
			<th width="6%"  align="center">Compte</th>
            <th width="5%"  align="center"># Optipro</th>
            <th width="5%"  align="center">Modifier</th>
			<th width="30%" align="center">Erreur</th>
            <th width="4%"  align="center">Effacer</th>
            <th width="10%" align="center">Succursale avisé?</th>
            <th width="8%" align="center">Courriel</th>
			<th width="8%" align="center">Transféré ?</th>
			<th width="14%" align="center">Produit Demandé</th>
			<th width="7%" align="center">Modifier produit (Ifc.ca)</th>
			<th width="7%" align="center">Modifier produit (SAFE)</th>
			<th width="5%" align="center">Aviser la direction</th>
	</tr>
		
		
    </thead>
    
    <tbody>

<?php
	
	while ($listItem=mysql_fetch_array($rptResult)){
		
		if ($listItem[order_num_optipro] <> ''){
			
			//echo '<br>User id:'. $listItem[user_id];
			switch($listItem[user_id]){
				case 'entrepotifc':     $LesComptes = "('entrepotifc','entrepotsafe') ";  	break;	
				case 'entrepotsafe':    $LesComptes = "('entrepotifc','entrepotsafe') ";  	break;
				
				case 'entrepotdr':      $LesComptes = "('entrepotdr','safedr') ";  			break;	
				case 'safedr':          $LesComptes = "('entrepotdr','safedr') ";  	    	break;	
				
				case 'longueuil':       $LesComptes = "('longueuil','longueuilsafe') ";  	break;	
				case 'longueuilsafe':   $LesComptes = "('longueuil','longueuilsafe') ";  	break;	
				
				case 'laval':           $LesComptes = "('laval','lavalsafe') ";  			break;	
				case 'lavalsafe':       $LesComptes = "('laval','lavalsafe') ";  			break;		
				
				case 'terrebonne':      $LesComptes = "('terrebonne','terrebonnesafe') "; 	break;	
				case 'terrebonnesafe':  $LesComptes = "('terrebonne','terrebonnesafe') "; 	break;
				
				case 'sherbrooke':      $LesComptes = "('sherbrooke','sherbrookesafe') "; 	break;	
				case 'sherbrookesafe':  $LesComptes = "('sherbrooke','sherbrookesafe') "; 	break;	
			
				case 'chicoutimi':      $LesComptes = "('chicoutimi','chicoutimisafe') "; 	break;	
				case 'chicoutimisafe':  $LesComptes = "('chicoutimi','chicoutimisafe') "; 	break;	
				
				case 'levis':           $LesComptes = "('levis','levissafe') "; 			break;	
				case 'levissafe':       $LesComptes = "('levis','levissafe') "; 			break;	
					
				case 'entrepotquebec':  $LesComptes = "('entrepotquebec','quebecsafe') "; 	break;	
				case 'quebecsafe':      $LesComptes = "('entrepotquebec','quebecsafe') "; 	break;
				
				case 'granby':          $LesComptes = "('granby','granbysafe') "; 		 	break;	
				case 'granbysafe':      $LesComptes = "('granby','granbysafe') "; 		 	break;
					
				case 'warehousehal':    $LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
				case 'warehousehalsafe':$LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
					
				case 'montreal':   	 	$LesComptes = "('montreal','montrealsafe') "; 	break;
				case 'montrealsafe':	$LesComptes = "('montreal','montrealsafe') "; 	break;	
			}
			

			
			//echo '<br>Les comptes:'. $LesComptes;
			
				$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id IN $LesComptes AND order_num_optipro = $listItem[order_num_optipro]";
				//echo '<br>'. $queryValiderPasser. '<br>';
				$resultValiderPasser = mysql_query($queryValiderPasser) or die  ('I cannot select items because 44: ' . mysql_error());
				$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
				$DataValiderPasser   = mysql_fetch_array($resultValiderPasser);
				if ($CountValiderPasser > 0){
					$EtatCommande = 'Oui, #' . $DataValiderPasser[order_num];	
				}else{
					$EtatCommande = 'Non';	
				}
			}else{
				$EtatCommande = 'N/D';		
			}//End IF there is an order num optipro
			
			$valeurRedirection  = "optipro_today.php?delete_id=$listItem[erreur_id]";	
			$valeurEnvoyerEmail = "optipro_today.php?aviser_id=$listItem[erreur_id]"; 
			$valeurEnvoyerEmailDirection = "optipro_today.php?aviser_direction_id=$listItem[erreur_id]"; 
			$valeurModifier     = "edit_optipro.php?erreur_id=$listItem[erreur_id]"; 
			
			$Notif 			    = $listItem[nombre_notification_succursale] . " fois";
			if ($listItem[nombre_notification_succursale] > 0){
				$Notif  = $Notif  . '  <br>Dernier: <b>'.$listItem[date_derniere_notification].'</b>';
			}
		
			$NotifDirection 			    = $listItem[nombre_notification_direction] . " fois";
			if ($listItem[nombre_notification_direction] > 0){
				$NotifDirection  = 'Date de l\'avis: ' . $listItem[date_derniere_notification_direction].'</b>';
			}
			if ($NotifDirection == '0 fois') 
				$NotifDirection = '';
			//echo 'notif'. $Notif;
			
			
	?>		
    
    <tr <?php if ($CountValiderPasser > 0){ echo ' bgcolor="#6FD474"';} ?>>
			
			<td align="center"><?php  echo $listItem[date]; ?></td>
			<td align="center"><?php  echo $listItem[user_id]; ?></td>
            
             <td align="center"><?php  echo $listItem[order_num_optipro]; ?></td>
            
             <td align="center"><a href="<?php echo $valeurModifier;  ?>"><img alt="Modifier cette erreur" title="Modifier cette erreur" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/modifier.png" width="30" height="30" alt=""/></a></td>
            
           
			<td align="center"><?php  echo $listItem[detail]; ?></td>
            <td align="center"><a href="<?php echo $valeurRedirection;  ?>"><img alt="Effacer cette erreur" title="Effacer cette erreur" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/effacer.gif" width="30" height="30" alt=""/></a></td>
           
           
           <td align="center"><?php echo $Notif; ?></td>
                      
                      
            <td align="center">
            <?php if ($CountValiderPasser == 0){ ?>
            <a href="<?php echo $valeurEnvoyerEmail;  ?>"><img alt="Envoyer l'avis a la succursale" title="Envoyer l'avis a la succursale" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/envoyer.jpg" width="45" height="45" alt=""/></a>
            <?php }?>
            </td>
            
            
            <td align="center"> <?php if ($CountValiderPasser > 0){ echo '<b>';} ?><?php   echo $EtatCommande; ?>  <?php if ($CountValiderPasser > 0){ echo '</b>';} ?></td>
            <td align="center"><?php  echo $listItem[produit_optipro]; ?></td>
            
		<?php	
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"http://c.direct-lens.com/admin/update_exclusive_product_ifc.php?pkey=". $listItem[cle_produit]. "\">Voir"."</td>";
			}else {
			echo '<td>&nbsp;</td>';	
			}
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"http://c.direct-lens.com/admin/update_exclusive_product_safety.php?pkey=". $listItem[cle_produit]. "\">Voir"."</td>";
			}else{
			echo '<td>&nbsp;</td>';		
			}
		
		
?>
			 
 			 <td align="center">
            <?php if (($listItem[nombre_notification_direction]==0) && ($Notif>2)){ ?>
              <a href="<?php echo $valeurEnvoyerEmailDirection;  ?>"><img alt="Envoyer l'avis à la direction. Nombre d'avis envoyé à date: <?php echo $listItem[nombre_notification_direction]; ?>" title="Envoyer l'avis à la direction. Nombre d'avis envoyé à date: <?php echo $listItem[nombre_notification_direction]; ?>" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/envoyer.jpg" width="45" height="45" alt=""/></a>
            <?php }else{?>
           		  <?php echo $NotifDirection;
				}  ?>
            
            </td>	
		
			
					
		
<?php
			
			
			echo "</tr>";
	}//END WHILE
	
echo "</tbody></table>";

}
?>
</td>
	  </tr>
</table> 

<br>
<?php //Tableau avec job dans le basket/et confirmés ?>
<table width="80%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th align="center">Compte</th>
			<th align="center" bgcolor="#ECAAAB">Panier Ifc</th>
            <th align="center" bgcolor="#F4F791">Panier Safe</th>
            <th align="center" bgcolor="#C7FCC4">Optipro validées</th>
		    <th align="center" bgcolor="#C7FCC4">Total Excluant Redos</th>
		 	<th align="center" bgcolor="#25A0DD">Redos</th>
			
	</tr>
    </thead>

<?php

	
		//Trois-Rivieres
		$CompteIFC              =  " user_id IN ('entrepotifc')";  
		$CompteSAFE             =  " user_id IN ('entrepotsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 3: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_TR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 20: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_TR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TR_OP   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_TR                 = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
	
		//Drummondville
		$CompteIFC              =  " user_id IN ('entrepotdr')";  
		$CompteSAFE             =  " user_id IN ('safedr')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 24: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_DR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 25: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_DR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_DR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 26: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_DR_OP   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 27: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_DR                 = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Granby
		$CompteIFC              =  " user_id IN ('granby')";  
		$CompteSAFE             =  " user_id IN ('granbysafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 28: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_GR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 29: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_GR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 30: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_GR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 31: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_GR_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 32: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_GR                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Lévis
		$CompteIFC              =  " user_id IN ('levis')";  
		$CompteSAFE             =  " user_id IN ('levissafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 33: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LE      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 34: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LE     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 35: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LE   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 36: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LE_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 37: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_LE                = $DataValiderAJD[NbrCommandeTransferer] ;
	
		
		//Chicoutimi
		$CompteIFC              =  " user_id IN ('chicoutimi')";  
		$CompteSAFE             =  " user_id IN ('chicoutimisafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 38: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_CH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 39: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_CH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 40: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_CH   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null  AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 41: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_CH_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 42: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_CH                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Laval
		$CompteIFC              =  " user_id IN ('laval')";  
		$CompteSAFE             =  " user_id IN ('lavalsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 43: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LV      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 44: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LV     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 45: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LV   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 46: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LV_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 47: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_LV                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		//Terrebonne
		$CompteIFC              =  " user_id IN ('terrebonne')";  
		$CompteSAFE             =  " user_id IN ('terrebonnesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 48: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_TE      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 49: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_TE     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 50: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TB   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 51: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TB_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 52: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_TB                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Sherbrooke
		$CompteIFC              =  " user_id IN ('sherbrooke')";  
		$CompteSAFE             =  " user_id IN ('sherbrookesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 53: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_SH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 54: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_SH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 55: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_SH   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 56: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_SH_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 57: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_SH                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Longueuil
		$CompteIFC              =  " user_id IN ('longueuil')";  
		$CompteSAFE             =  " user_id IN ('longueuilsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 58: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LO      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 59: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LO     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 60: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LO   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 61: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LO_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 62: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_LO                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Halifax
		$CompteIFC              	=  " user_id IN ('warehousehal')";  
		$CompteSAFE             	=  " user_id IN ('warehousehalsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysql_error()); 
		$DataPanierIFC     			= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_HA      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysql_error());   
		$DataPanierSAFE     		= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_HA     	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_HA   = $DataValiderAJD[NbrCommandeTransferer] ;
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_opticbox<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 66: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_HA_OPB   = $DataValiderAJD[NbrCommandeTransferer] ;
	
		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	   // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   		= mysql_query($queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysql_error()); 
        $DataValiderAJDIFC        		= mysql_fetch_array($resultValiderAJDIFC);
		$NbrCommandeValiderAJD_HA_OPTIPRO = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_HA                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		
	
		//Québec
		$CompteIFC              =  " user_id IN ('entrepotquebec')";  
		$CompteSAFE             =  " user_id IN ('quebecsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 73: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_QC     = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because 74: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_QC    = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 75: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_QC   = $DataValiderAJD[NbrCommandeTransferer] ;	
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 76: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_QC_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because 77: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrRedos_QC                = $DataValiderAJD[NbrCommandeTransferer] ;
	
		$TotalOptipro = $NbrCommandeValiderAJD_TR_OP + $NbrCommandeValiderAJD_DR_OP  + $NbrCommandeValiderAJD_GR_OP + $NbrCommandeValiderAJD_LE_OP + $NbrCommandeValiderAJD_CH_OP + $NbrCommandeValiderAJD_LV_OP + $NbrCommandeValiderAJD_TB_OP + $NbrCommandeValiderAJD_SH_OP + $NbrCommandeValiderAJD_LO_OP  + $NbrCommandeValiderAJD_QC_OP;
	
		$TotalRedos = $NbrRedos_TR + $NbrRedos_DR + $NbrRedos_GR + $NbrRedos_LE+ $NbrRedos_CH + $NbrRedos_LV+ $NbrRedos_TB + $NbrRedos_SH+ $NbrRedos_LO +  $NbrRedos_QC + $NbrRedos_HA;
	
		$TotalOpticbox = $NbrCommandeValiderAJD_HA_OPB;
	
	
		$totalPanierIFC = $NbrCommandeIfc_TR +$NbrCommandeIfc_DR+$NbrCommandeIfc_GR+$NbrCommandeIfc_LE+$NbrCommandeIfc_CH+$NbrCommandeIfc_LV+$NbrCommandeIfc_TE+$NbrCommandeIfc_SH+$NbrCommandeIfc_LO+$NbrCommandeIfc_HA+$NbrCommandeIfc_QC;
		
		$totalPanierSAFE = $NbrCommandeSAFE_TR +$NbrCommandeSAFE_DR+$NbrCommandeSAFE_GR+$NbrCommandeSAFE_LE+$NbrCommandeSAFE_CH+$NbrCommandeSAFE_LV+$NbrCommandeSAFE_TE+$NbrCommandeSAFE_SH+$NbrCommandeSAFE_LO+$NbrCommandeSAFE_HA+$NbrCommandeSAFE_QC;
		
		$totalValidees = $NbrCommandeValiderAJD_TR+$NbrCommandeValiderAJD_DR+$NbrCommandeValiderAJD_GR+$NbrCommandeValiderAJD_LE+$NbrCommandeValiderAJD_CH+$NbrCommandeValiderAJD_LV +$NbrCommandeValiderAJD_TB+$NbrCommandeValiderAJD_SH+$NbrCommandeValiderAJD_LO+$NbrCommandeValiderAJD_HA+$NbrCommandeValiderAJD_QC;
?>	



	<tr>
			<th align="center">Trois-Rivières</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_TR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_TR.'</h2>';
				}else {
					echo $NbrCommandeIfc_TR; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TR_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TR; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_TR; ?></th>
           
	</tr>
    
    <tr>
			<th align="center">Drummondville</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_DR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_DR.'</h2>';
				}else {
					echo $NbrCommandeIfc_DR; 
				} ?>
			</th>
            
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_DR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_DR_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_DR; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_DR; ?></th>
	</tr>
    
    
   
    <tr>
			<th align="center">Granby</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_GR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_GR.'</h2>';
				}else {
					echo $NbrCommandeIfc_GR; 
				} ?>
			</th>
             <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_GR; ?></th>
             <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_GR_OP; ?></th>
			 <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_GR; ?></th>
			 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_GR; ?></th>
	</tr>
    
    
    <tr>
			<th align="center">Lévis</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LE == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LE.'</h2>';
				}else {
					echo $NbrCommandeIfc_LE; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LE; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LE_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LE; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_LE; ?></th>	
	</tr>
    
    
     <tr>
			<th align="center">Chicoutimi</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_CH == 15){ 
					echo '<h2>'.$NbrCommandeIfc_CH.'</h2>';
				}else {
					echo $NbrCommandeIfc_CH; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_CH; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_CH_OP; ?></th>
		 	<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_CH; ?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_CH; ?></th
	
	</tr>
    
     <tr>
			<th align="center">Laval</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LV == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LV.'</h2>';
				}else {
					echo $NbrCommandeIfc_LV; 
				} ?>                                           
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LV; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LV_OP; ?></th>
		 	<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LV; ?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_LV; ?></th>
	
	</tr>
    
     <tr>
			<th align="center">Terrebonne</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_TE == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_TE.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_TE; } ?>
            </th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TE; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TB_OP; ?></th>
		    <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TB; ?></th>
		 	 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_TB; ?></th>
		 
	</tr>
    
      <tr>
			<th align="center">Sherbrooke</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_SH == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_SH.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_SH; } ?>
            </th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_SH; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_SH_OP; ?></th>
		  	<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_SH; ?></th><br>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_SH; ?></th>
	</tr>
    
     
      <tr>
			<th align="center">Longueuil</th>
             <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LO == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LO.'</h2>';
				}else {
					echo $NbrCommandeIfc_LO; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LO; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LO_OP; ?></th>
		  	<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LO; ?></th>
		  	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_LO; ?></th>
	</tr>
    
    
   
	
      <tr>
			<th align="center">Québec</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_QC == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_QC.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_QC; } ?>
			
			
			</th>
             <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_QC; ?></th>
             <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_QC_OP; ?></th>
		  	 <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_QC; ?></th>
		  	 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_QC; ?></th>
		  
	</tr>
    
	
	 <tr>
			<th align="center">Halifax</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_HA == 15){ 
					echo '<h2>'.$NbrCommandeIfc_HA.'</h2>';
				}else {
					echo $NbrCommandeIfc_HA; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_HA; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HA_OPTIPRO;?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HA; ?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HA; ?></th>
	</tr>
	
    <tr>
			<th align="center">Total</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $totalPanierIFC;?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $totalPanierSAFE?>&nbsp;</th>
            <th align="center" bgcolor="#C7FCC4" >Total Optipro:<?php echo $TotalOptipro?></th>
		 	<th align="center" bgcolor="#C7FCC4">Grand Total validées excluant redos:<?php echo $totalValidees;?></th>
			<th align="center" bgcolor="#25A0DD">Total Redos:<?php echo $TotalRedos; ?></th>
	</tr>
    </table>
    
    <br>
   
    
    <?php  
	$queryBasket  = "SELECT max(update_time)as max_last_basket, order_primary_key  FROM status_history WHERE order_status='Basket'";
	$resultBasket = mysql_query($queryBasket) or die  ('I cannot select items because 55: ' . mysql_error());
	$DataBasket   = mysql_fetch_array($resultBasket);	

	$queryDetailBasket  = "SELECT user_id, order_num_optipro FROM orders WHERE order_num= (SELECT order_num FROM orders  WHERE primary_key= $DataBasket[order_primary_key])";
	$resultDetailBasket = mysql_query($queryDetailBasket) or die  ('I cannot select items because 55: ' . mysql_error());
	$DataDetailBasket   = mysql_fetch_array($resultDetailBasket);	

	$datelive    	   = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$datecomplete 	   = date("Y-m-d", $datelive);

	$datehier    	   = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
	$datecompletehier  = date("Y-m-d", $datehier);

	$queryCountBasket  = "SELECT count(*) as NbrImportation FROM status_history WHERE order_status='Basket' AND update_time like '%$datecomplete%' ";
	$resultCountBasket = mysql_query($queryCountBasket) or die  ('I cannot select items because 55: ' . mysql_error());
	$DataCountBasket   = mysql_fetch_array($resultCountBasket);	

	$queryCountBasketHier  = "SELECT count(*) as NbrImportation FROM status_history WHERE order_status='Basket' AND update_time like '%$datecompletehier%' ";
	$resultCountBasketHier = mysql_query($queryCountBasketHier) or die  ('I cannot select items because 55: ' . mysql_error());
	$DataCountBasketHier   = mysql_fetch_array($resultCountBasketHier);	
	?>
    
    
    
    <br>
     <table align="center"  width="1000" border="1">
    	<tr align="center">
        	<td colspan="2" width="20%" align="center"><h2 align="center">Importation</h2></td></td>
        </tr>
        
        <tr  align="center">
        	<td width="20%"><h3>Dernière commande importée avec succès:</h3></td>
            <td width="20%"><h3><?php echo $DataBasket[max_last_basket]; ?>   Commande #<?php echo $DataDetailBasket[order_num_optipro];?>--> <?php echo $DataDetailBasket[user_id]; ?></h3></td>
        </tr>

        <tr  align="center">
        	<td width="20%"><h3>Nombre d'importation avec succès aujourd'hui/hier </h3></td>
            <td width="20%"><h3><?php echo $DataCountBasket[NbrImportation];?> / <?php echo $DataCountBasketHier[NbrImportation];?></h3></td>

    </table>




 <table align="center"  width="800" border="1">
    	<tr align="center">
        	<td colspan="2" width="20%" align="center"><h2 align="center">Traces</h2></td></td>
        </tr>
        
    
 <?php
/*
$today      = date("Y-m-d");

$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (10,25,69)
AND order_date_processed='$today'
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY prescript_lab, shape_name_bk desc";


echo '<br>Query: <br>'. $rptQuery . '<br>';	

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 1b: . <br>'. $rptQuery . ' <br>'. mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
$count   = 0;
$message = "";		
$message="<html>
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

$message.="<body><table class=\"table\">
<tr><td colspan=\"5\">Ce rapport inclus toutes les commandes envoyées vers Swiss, HKO et GKB durant la journée</td></tr>";
$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\" width=\"150\">Date confirmation</td>
	<td align=\"center\">EDLL Order #</td>
	<td align=\"center\">Prescript Lab</td>
	<td align=\"center\">Nom du fichier de trace</td>
	<td align=\"center\">Trace envoyé à qui</td>
	<td align=\"center\" width=\"150\">Resultat Shapes</td>
	<td align=\"center\">Date</td>
	<td align=\"center\">Status</td>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){ 			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$QueryConfirmation   = "SELECT update_time FROM status_history
	WHERE order_num = $listItem[order_num]
	AND order_status='processing'";
	$resultConfirmation = mysqli_query($con,$QueryConfirmation)		or die  ('I cannot select items because 1c: ' . mysqli_error($con));
	$DataConfirmation   = mysqli_fetch_array($resultConfirmation,MYSQLI_ASSOC);
		
	$message.="
	<tr>
		<td align=\"center\">$DataConfirmation[update_time]</td>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[prescript_lab]</td>";
		
		if ($listItem[shape_copied_ftp]=='0000-00-00 00:00:00'){
			$message.="<td align=\"center\"><b>$listItem[shape_name_bk]</b></td>";
		}else{
			$message.="<td align=\"center\">$listItem[shape_name_bk]</td>";
		}
		$message.="<td align=\"center\">$listItem[shape_sent_to_who]</td>
		<td align=\"center\">$listItem[result_copy_ftp]</td>
		<td align=\"center\">$listItem[shape_copied_ftp]</td>
		<td align=\"center\">$listItem[order_status]</td>
	</tr>";

		
}//END WHILE */  ?>

</table>

  <p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>