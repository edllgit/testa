<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");
include('../phpmailer_email_functions.inc.php');
//require_once('../class.ses.php');


session_start();
$today = date("Y-m-d");// current date

if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.html'>here</a> to login.";
	exit();
}
?>
<html>
<head>
<title>Préparer le rapport de montures envoyés au laboratoire</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

<script type="text/javascript">
function scrollTo(hash) {
	location.hash = "#" + hash;
}
</script>
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
           



<form  method="post" name="verification" id="verification" action="rapport_monture_pour_labo.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Préparer le rapport de montures envoyés au laboratoire</font></b></td>
            	</tr>
  
                 <?php 
				 switch($AccessData[id]){
					 case 191: $UserIDIN = "('laval','lavalsafe','garantieatoutcasser')";  	
					 $CompteIfc = 'laval'; $CompteSafe='lavalsafe';	    $IdentificationEntrepot = "Entrepot de la lunette Laval";   		
					 break;	
					 
					 case 192: $UserIDIN = "('entrepotifc','entrepotsafe','garantieatoutcasser')";    
					 $CompteIfc = 'entrepotifc'; $CompteSafe='entrepotsafe';  $IdentificationEntrepot = "Entrepot de la lunette  Trois-Rivieres";
					 break;	
					 
					 
					 case 193: $UserIDIN = "('entrepotdr','safedr','garantieatoutcasser')";  	
					 $CompteIfc = 'entrepotdr'; $CompteSafe='safedr';		$IdentificationEntrepot = "Entrepot de la lunette  Drummondville";  
					 break;
					 
					 case 196: $UserIDIN = "('warehousehal','warehousehalsafe','garantieatoutcasser')"; 
					 $CompteIfc = 'warehousehal'; $CompteSafe='warehousehalsafe'; $IdentificationEntrepot = "Optical Warehouse Halifax";   	  		
					 break;
					 
					 case 199: $UserIDIN = "('terrebonne','terrebonnesafe','garantieatoutcasser')";   
					 $CompteIfc = 'terrebonne'; $CompteSafe='terrebonnesafe';  $IdentificationEntrepot = "Entrepot de la lunette  Terrebonne";     
					 break;
					 
					 case 203: $UserIDIN = "('sherbrooke','sherbrookesafe','garantieatoutcasser')"; 
					 $CompteIfc = 'sherbrooke'; $CompteSafe='sherbrookesafe';    $IdentificationEntrepot = "Entrepot de la lunette Sherbrooke";     
					  break;
					 
					 case 208: $UserIDIN = "('longueuil','longueuilsafe','garantieatoutcasser')";   
					 $CompteIfc = 'longueuil'; $CompteSafe='longueuilsafe';    $IdentificationEntrepot = "Entrepot de la lunette Longueuil";       
					 break;
					 
					 case 207: $UserIDIN = "('levis','levissafe','garantieatoutcasser')";            
					 $CompteIfc = 'levis'; $CompteSafe='levissafe';   $IdentificationEntrepot = "Entrepot de la lunette Levis";   		
					 break;
					 
					 case 205: $UserIDIN = "('chicoutimi','chicoutimisafe','garantieatoutcasser')";   
					 $CompteIfc = 'chicoutimi'; $CompteSafe='chicoutimisafe';  $IdentificationEntrepot = "Entrepot de la lunette Chicoutimi";      
					 break;
					 
					 case 210: $UserIDIN = "('granby','granbysafe','garantieatoutcasser')";          
					 $CompteIfc = 'granby'; $CompteSafe='granbysafe';   $IdentificationEntrepot = "Entrepot de la lunette Granby";   	    
					 break;
					 
					 
					 case 221: $UserIDIN = "('entrepotquebec','quebecsafe','garantieatoutcasser')";      
					 $CompteIfc = 'entrepotquebec'; $CompteSafe='quebecsafe';   $IdentificationEntrepot = "Entrepot de la lunette Quebec";    
					 break;
						 
					/* case 228: $UserIDIN = "('montreal','montrealsafe','garantieatoutcasser')";      
					 $CompteIfc = 'montreal'; $CompteSafe='montrealsafe';   $IdentificationEntrepot = "Entrepot de la lunette Montreal ZT1";    
					 break; */
						 
					 case 230: $UserIDIN = "('stjerome','stjeromesafe','garantieatoutcasser')";      
					 $CompteIfc = 'stjerome'; $CompteSafe='stjeromesafe';   $IdentificationEntrepot = "Entrepot de la lunette Saint-Jérome Zone Tendance";    
					 break;
						 
					 case 231: $UserIDIN = "('gatineau','gatineausafe','garantieatoutcasser')";      
					 $CompteIfc = 'gatineau'; $CompteSafe='gatineausafe';   $IdentificationEntrepot = "Entrepot de la lunette Gatineau";    
					 break;
					 
					 case 240: $UserIDIN = "('edmundston','edmundstonsafe','garantieatoutcasser')";      
					 $CompteIfc = 'edmundston'; $CompteSafe='edmundstonsafe';   $IdentificationEntrepot = "Entrepot de la lunette Edmundston";    
					 break;
					 
					 case 242: $UserIDIN = "('vaudreuil','vaudreuilsafe','garantieatoutcasser')";      
					 $CompteIfc = 'vaudreuil'; $CompteSafe='vaudreuilsafe';   $IdentificationEntrepot = "Entrepot de la lunette Vaudreuil";    
					 break;
					 
					 case 243: $UserIDIN = "('sorel','sorelsafe','garantieatoutcasser')";      
					 $CompteIfc = 'sorel'; $CompteSafe='sorelsafe';   $IdentificationEntrepot = "Entrepot de la lunette Sorel";    
					 break;
					 
					 case 244: $UserIDIN = "('moncton','monctonsafe','garantieatoutcasser')";      
					 $CompteIfc = 'moncton'; $CompteSafe='monctonsafe';   $IdentificationEntrepot = "Entrepot de la lunette Moncton";    
					 break;
					 
					 case 250: $UserIDIN = "('fredericton','frederictonsafe','garantieatoutcasser')";      
					 $CompteIfc = 'fredericton'; $CompteSafe='frederictonsafe';   $IdentificationEntrepot = "Entrepot de la lunette Fredericton";    
					 break;
					 
					 default:  $UserIDIN = "('aaa')"; 
				 }
				 ?>
                
				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
                        
                        <div><strong></strong> Vous êtes présentement connectés comme: <h3><font color="#FF0004"><?php echo $IdentificationEntrepot; ?></font></h3> </div>	
                        <div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Recharger cette page" class="formField"></div></td>					
                        <td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
	
			</table>

			<?php 
			$queryJobsDujourHORSQC = "SELECT distinct orders.order_num, orders.order_num,color, temple_model_num,model, order_patient_first, order_patient_last, orders.user_id, orders.internal_note, orders.date_frame_sent_saintcath FROM orders, extra_product_orders WHERE orders.order_num = extra_product_orders.order_num AND frame_sent_saintcath ='yes' AND user_id IN $UserIDIN AND category='Edging' AND order_status NOT IN ('cancelled','on hold')";
			//echo '<br>'. $queryJobsDujourHORSQC;

if ($queryJobsDujourHORSQC!=""){
	$rptResult = mysqli_query($con,$queryJobsDujourHORSQC) or die  ('<strong>Errors occured during the process:</strong> '. $queryJobsDujourHORSQC . mysqli_error($con));
	$usercount = mysqli_num_rows($rptResult);
}
					
	
if ($usercount != 0){
	
$message="<div align=\"center\"><h2>Boite pour Saint-Catharines</h2></div>";	
				
$message.="<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr>
	<td align=\"center\"><strong>Numéro de facture Directlab</strong></td>
	<td align=\"center\"><strong>Description Monture</strong></td>
	<td align=\"center\"><strong>Couleur</strong></td>
	<td align=\"center\"><strong>Detail</strong></td>
	<td align=\"center\"><strong>Date d'ajout</strong></td>
	<td align=\"center\"><strong>Reference patient</strong></td>
	<td align=\"center\"><strong>Retirer</strong></td>
</tr>";		
							  
$OrderAbleToUpdate = 0;
$CompteurFrame     = 0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	$CompteurFrame  = $CompteurFrame +1;

		$DateEnvoieLabo = substr($listItem[date_frame_sent_saintcath],0,10);
	
			//Commande déja shippé, on disable la case a cocher
			if (($list_order_status=='Cancelled') ||($list_order_status=='filled')) {
		   $message.= "<tr><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[temple_model_num]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[color]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[model]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$DateEnvoieLabo</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">Retirer de cette liste</td>";
			}else{
				
				if ($listItem[user_id]=='garantieatoutcasser'){
					$PositionAncienCompteDeuxPoint = strpos($listItem[internal_note],'Ancien compte:')+13;
					//echo '<br><br><br>Position deuxpoint:'. $PositionAncienCompteDeuxPoint;
					$LongeurTotaleInternalNote = strlen($listItem[internal_note]);
					$LongeurCompte = $LongeurTotaleInternalNote - $PositionAncienCompteDeuxPoint;
					//echo '<br>Longeur Compte:'.  $LongeurCompte;
					$CompteOriginal = substr($listItem[internal_note],$PositionAncienCompteDeuxPoint+1,$LongeurCompte);
					//echo '<br>Compte original:'. $CompteOriginal;
					if (($CompteOriginal == $CompteIfc) || ($CompteOriginal == $CompteSafe)){//SI la GTC appartient a l'entrepot, on affiche la commande
						  $OrderAbleToUpdate +=1;
						  $message.= "<tr><td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[temple_model_num]</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[color]</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[model]</td>
						   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$DateEnvoieLabo</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
						   <td style=\"font-size:16px;\"  align=\"center\"><a href=\"retirer_de_la_liste.php?on=$listItem[order_num]\">Retirer de cette liste</a></td>";
					}
				}//End if GTC
				
				if ($listItem[user_id]<>'garantieatoutcasser'){
				
			$OrderAbleToUpdate +=1;
	   $message.= "<tr><td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
			       <td style=\"font-size:16px;\"  align=\"center\">$listItem[temple_model_num]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[color]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[model]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$DateEnvoieLabo</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
				   <td style=\"font-size:16px;\"  align=\"center\"><a href=\"retirer_de_la_liste.php?on=$listItem[order_num]\">Retirer de cette liste</a></td>";
				}
			}
			
			

		$message.=	"</tr>";
}//END WHILE

$message.=	"<tr><td colspan=\"7\" align=\"center\" style=\"font-size:16px;\" >Total:$OrderAbleToUpdate monture(s)</td></tr>";	
$message.=	"</table>";
}







echo $message;	
echo '<div align="center"><p><b>1- Veuillez imprimer cette page avant de transférer le rapport au lab.</b><br><br> <br>2- SVP Assurez-vous d\'avoir bien terminé cette liste à 100% avant de la transmettre au lab.</p>
<input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Envoyer cette liste au Lab" class="formField"><br>
<b>N.B.:</b> <b>Une fois ce rapport envoyé, toutes les montures qu\'il contient seront immédiatement effacés de cette liste.</b> 
<br><br>
</u></div>';	  				  
echo "</form>";
?>

</td>
	  </tr>
</table>

</body>

<?php
if ($_REQUEST[rpt_search]=='Envoyer cette liste au Lab'){
	
//1-Sauvegarder dans la DB.
	
	
//Envoyer le rapport par courriel a Kelly + copie au lab
	switch($AccessData[id]){
		case 191: 
		$UserIDIN = "('laval','lavalsafe')";  			 $IdentificationEntrepot = "Entrepot de la lunette Laval";   		
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com'); 
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%laval%'  ";
		break;	
		
		case 192: 
		$UserIDIN = "('entrepotifc','entrepotsafe')";      $IdentificationEntrepot = "Entrepot de la lunette  Trois-Rivieres";

	  $send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  

		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%entrepotifc%'  ";
		break;	  
		
		case 193: 
		$UserIDIN = "('entrepotdr','safedr')";  			 $IdentificationEntrepot = "Entrepot de la lunette  Drummondville";  
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com'); 
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%entrepotdr%'  ";
		break;	 
		
		case 196: 
		$UserIDIN = "('warehousehal','warehousehalsafe')"; $IdentificationEntrepot = "Optical Warehouse Halifax";   	  		
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%warehousehal%'  ";
		break;	
		
		case 199: 
		$UserIDIN = "('terrebonne','terrebonnesafe')";     $IdentificationEntrepot = "Entrepot de la lunette  Terrebonne";     
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%terrebonne%'  ";
		break;	
		
		case 203: 
		$UserIDIN = "('sherbrooke','sherbrookesafe')";     $IdentificationEntrepot = "Entrepot de la lunette Sherbrooke";     
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%sherbrooke%'  ";	
		break;	
		
		case 208: 
		$UserIDIN = "('longueuil','longueuilsafe')";       $IdentificationEntrepot = "Entrepot de la lunette Longueuil";       
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%longueuil%'  ";	
		break;	
		
		case 207: 
		$UserIDIN = "('levis','levissafe')";               $IdentificationEntrepot = "Entrepot de la lunette Levis";   		  
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%levis%'  ";
		break;	
		
		case 205: 
		$UserIDIN = "('chicoutimi','chicoutimisafe')";     $IdentificationEntrepot = "Entrepot de la lunette Chicoutimi";       
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%chicoutimi%'  ";
		break;	
		
		case 210: 
		$UserIDIN = "('granby','granbysafe')";             $IdentificationEntrepot = "Entrepot de la lunette Granby";   	     
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%granby%'  ";
		break;	
		
	
		
		case 221: 
		$UserIDIN = "('entrepotquebec','quebecsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Quebec";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%entrepotquebec%'  ";
		break;	
			
			
		/*case 228: 
		$UserIDIN = "('montreal','montrealsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Montreal ZT1";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%montreal%'  ";
		break;	 */
			
			
		case 230: 
		$UserIDIN = "('stjerome','stjeromesafe')";         $IdentificationEntrepot = "Entrepot de la lunette St-Jérome";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%stjerome%'  ";
		break;		
			
		case 231: 
		$UserIDIN = "('gatineau','gatineausafe')";         $IdentificationEntrepot = "Entrepot de la lunette Gatineau";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%gatineau%'  ";
		break;		
		
		
		case 240: 
		$UserIDIN = "('edmundston','edmundstonsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Edmundston";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%edmundston%'  ";
		break;		
		
		case 242: 
		$UserIDIN = "('vaudreuil','vaudreuilsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Vaudreuil";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%vaudreuil%'  ";
		break;
		
		case 243: 
		$UserIDIN = "('sorel','sorelsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Sorel";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%sorel%'  ";
		break;
		
		case 244: 
		$UserIDIN = "('moncton','monctonsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Moncton";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');    
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%moncton%'  ";
		break;
		
		case 250: 
		$UserIDIN = "('fredericton','frederictonsafe')";         $IdentificationEntrepot = "Entrepot de la lunette Fredericton";    
		$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');   
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%fredericton%'  ";
		break;
		
		default:  $UserIDIN = "('aaa')"; $send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  

		/*$send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  
		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%moncton%'  ";
		break;
		
		default:  $UserIDIN = "('aaa')"; $send_to_address = array('rapports@direct-lens.com','jmotyka@direct-lens.com','kgawel@direct-lens.com');  */

		$InternalNotelike = "UPDATE orders set frame_sent_saintcath ='no' WHERE user_id='GARANTIEATOUTCASSER' AND frame_Sent_saintcath = 'yes'  AND INTERNAL_NOTE LIKE '%moncton%'  ";
		break;
		
	
		

	}
	

	
	$curTime= date("m-d-Y");	
	$to_address   = $send_to_address ;
	echo 'Mail envoyé a'.var_dump($to_address);
	
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Frame sent ".$IdentificationEntrepot;


	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Nous n'envoyons plus de courriel, donc l'envoie est mis en commentaire.
	
	
		$QueryUpdateEnvoyes = "UPDATE orders set frame_sent_saintcath ='sent', date_frame_sent_saintcath='$curTime' WHERE user_id IN $UserIDIN AND frame_Sent_saintcath = 'yes'";
		$resultCleanEnvoyes = mysqli_query($con,$QueryUpdateEnvoyes) or die  ('<strong>SVP AVISER CHARLES DE CE MESSAGE D\'ERREUR EN L\'IMPRIMANT:</strong> '. $QueryUpdateEnvoyes . mysqli_error($con));
		
		$QueryUpdateEnvoyes2 = $InternalNotelike;
		//echo '<br>'. $QueryUpdateEnvoyes2.'<br>';
		$resultCleanEnvoyes2 = mysqli_query($con,$QueryUpdateEnvoyes2) or die  ('<strong>SVP AVISER CHARLES DE CE MESSAGE D\'ERREUR EN L\'IMPRIMANT:</strong> '. $QueryUpdateEnvoyes2 . mysqli_error($con));
	
	
	if($response){ 
	
		echo '<div align="center" id="anchorresultat"><font color="#136744">Rapport envoyé avec succès au laboratoire.</font><br>
		<font color="#FF0004">Suppression de la liste des montures de ce rapport en cours....terminé avec succès.</font></div>';
	
    }else{
		echo '<div align="center" id="anchorresultat">Erreur durant l\'envoie du courriel. SVP IMPRIMEZ CETTE PAGE ET INSÉRER LA AVEC VOS MONTURES</div>';
	}
	
	
	//Log email
	$compteur = 0;
	foreach($to_address as $key => $value){
		if ($compteur == 0 )
			$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}
		
	


}//End if ('Envoyer cette liste au Lab')
 ?>

</html>