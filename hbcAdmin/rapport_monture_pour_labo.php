<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include("admin_functions.inc.php");
include "../connexion_hbc.inc.php";
//Le fichier getlang est partagé avec le labAdmin..Ne pas modifier!
include "../includes/getlang.php";

session_start();

$today = date("Y-m-d");// current date

if ($_SESSION[labAdminData][primary_key]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
?>
<html>
<head>
<title>Prepare the report: Frames sent to the lab</title>
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
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Prepare the report: Frames sent to the Lab</font></b></td>
            	</tr>
  
                 <?php 
				 switch($AccessData[id]){
					case 1: $UserIDIN = "('88666')";  	
					$CompteIfc = '88666';	    $IdentificationEntrepot = "Griffé Lunettier Trois-Rivières";   		
					break;	
						 
					case 2: $UserIDIN = "('88666')";  	
					$CompteIfc = 'gfd';	    	$IdentificationEntrepot = "COMME ADMIN DES 21 HBC VOUS NE POUVEZ PAS UTILISER CE RAPPORT. SVP VOUS CONNECTER DANS CHAQUE MAGASIN INDIVIDUELLEMENT POUR PREPARER CE RAPPORT.";   
					break;
						 
					case 26: $UserIDIN = "('88433')";  	
					$CompteIfc = '88433';	    $IdentificationEntrepot = "HBC Store #88433: Polo Park";   
					break;	 
					
					case 25: $UserIDIN = "('88430')";  	
					$CompteIfc = '88430';	    $IdentificationEntrepot = "HBC Store #88430: St. Vital";   
					break;	 
					
					case 24: $UserIDIN = "('88444')";  	
					$CompteIfc = '88444';	    $IdentificationEntrepot = "HBC Store #88444: Mayfair";   
					break;	  
						 
					case 23: $UserIDIN = "('88439')";  	
					$CompteIfc = '88439';	    $IdentificationEntrepot = "HBC Store #88439: Langley";   
					break;	 
							 
					case 22: $UserIDIN = "('88438')";  	
					$CompteIfc = '88438';	    $IdentificationEntrepot = "HBC Store #88438: Metrotown";   
					break;	 
					  
					case 21: $UserIDIN = "('88416')";  	
					$CompteIfc = '88416';	    $IdentificationEntrepot = "HBC Store #88416: Vancouver DTN";   
					break;	 

					case 20: $UserIDIN = "('88429')";  	
					$CompteIfc = '88429';	    $IdentificationEntrepot = "HBC Store #88429: Saskatoon";   
					break;	 
						  
					case 19: $UserIDIN = "('88441')";  	
					$CompteIfc = '88441';	    $IdentificationEntrepot = "HBC Store #88441: Southcentre";   
					break;	
 
					case 18: $UserIDIN = "('88435')";  	
					$CompteIfc = '88435';	    $IdentificationEntrepot = "HBC Store #88435: West Edmonton";   
					break;	
						 
					case 17: $UserIDIN = "('88434')";  	
					$CompteIfc = '88434';	    $IdentificationEntrepot = "HBC Store #88434: Market Mall";   
					break;	
					
					case 16: $UserIDIN = "('88432')";  	
					$CompteIfc = '88432';	    $IdentificationEntrepot = "HBC Store #88432: Edmonton Centre ";   
					break;	
						 
					case 15: $UserIDIN = "('88431')";  	
					$CompteIfc = '88431';	    $IdentificationEntrepot = "HBC Store #88431: Calgary DTN";   
					break;	
						
					case 14: $UserIDIN = "('88442')";  	
					$CompteIfc = '88442';	    $IdentificationEntrepot = "HBC Store #88442: Bayshore";   
					break;	
						 
					case 13: $UserIDIN = "('88440')";  	
					$CompteIfc = '88440';	    $IdentificationEntrepot = "HBC Store #88440: Rideau";   
					break;
						 
					case 12: $UserIDIN = "('88414')";  	
					$CompteIfc = '88414';	    $IdentificationEntrepot = "HBC Store #88414: Yorkdale";   
					break;
						 
					case 11: $UserIDIN = "('88411')";  	
					$CompteIfc = '88411';	    $IdentificationEntrepot = "HBC Store #88411: Sherway";   
					break;	 
						
					case 10: $UserIDIN = "('88408')";  	
					$CompteIfc = '88408';	    $IdentificationEntrepot = "HBC Store #88408: Oshawa";   
					break;	
						 
					case 9: $UserIDIN = "('88403')";  	
					$CompteIfc = '88403';	    $IdentificationEntrepot = "HBC Store #88403: Bloor St.";   
					break;
						 
					case 8: $UserIDIN = "('88449')";  	
					$CompteIfc = '88449';	    $IdentificationEntrepot = "HBC Store #88449: Mississauga";   
					break;
						 
					case 7: $UserIDIN = "('88405')";  	
					$CompteIfc = '88405';	    $IdentificationEntrepot = "HBC Store #88405: Fairview";   
					break;
						
					case 6: $UserIDIN = "('88409')";  	
					$CompteIfc = '88409';	    $IdentificationEntrepot = "HBC Store #88409: Eglinton";   
					break;
					
						 
					default:  $UserIDIN = "('aaa')"; 
				 }
				 ?>
                
				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
                        
                        <div><strong></strong> You are currently connected as: <h3><font color="#FF0004"><?php echo $IdentificationEntrepot; ?></font></h3> </div>	
                        <div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Refresh this page" class="formField"></div></td>					
                        <td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
	
			</table>

			<?php 
			$queryJobsDujourHORSQC = "SELECT distinct orders.order_num, orders.order_num,color, temple_model_num,model, order_patient_first, order_patient_last, orders.user_id, orders.internal_note, orders.date_frame_sent_saintcath  FROM orders, extra_product_orders WHERE orders.order_num = extra_product_orders.order_num AND frame_sent_saintcath ='yes' AND user_id IN $UserIDIN AND category='Frame' AND order_status NOT IN ('cancelled','on hold')";
			//echo '<br>'. $queryJobsDujourHORSQC;

if ($queryJobsDujourHORSQC!=""){
	$rptResult = mysqli_query($con,$queryJobsDujourHORSQC) or die  ('<strong>Errors occured during the process:</strong> '. $queryJobsDujourHORSQC . mysqli_error($con));
	$usercount = mysqli_num_rows($rptResult);
}
					
	
if ($usercount != 0){
	
$message="<div align=\"center\"><h2>Saint-Catharines lab Frames box</h2></div>";	
				
$message.="<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr>
	<td align=\"center\"><strong>Order Number</strong></td>
	<td align=\"center\"><strong>Frame Description</strong></td>
	<td align=\"center\"><strong>Color</strong></td>
	<td align=\"center\"><strong>Detail</strong></td>
	<td align=\"center\"><strong>Date d'ajout</strong></td>
	<td align=\"center\"><strong>Patient Reference</strong></td>
	<td align=\"center\"><strong>Remove</strong></td>
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
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">Remove from this list</td>";
			}else{
				
				if ($listItem[user_id]=='garantieatoutcasser'){
					$PositionAncienCompteDeuxPoint = strpos($listItem[internal_note],'Ancien compte:')+13;
					//echo '<br><br><br>Position deuxpoint:'. $PositionAncienCompteDeuxPoint;
					$LongeurTotaleInternalNote = strlen($listItem[internal_note]);
					$LongeurCompte = $LongeurTotaleInternalNote - $PositionAncienCompteDeuxPoint;
					//echo '<br>Longeur Compte:'.  $LongeurCompte;
					$CompteOriginal = substr($listItem[internal_note],$PositionAncienCompteDeuxPoint+1,$LongeurCompte);
					//echo '<br>Compte original:'. $CompteOriginal;
					if ($CompteOriginal == $CompteIfc){//SI la GTC appartient a l'entrepot, on affiche la commande
						  $OrderAbleToUpdate +=1;
						  $message.= "<tr><td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[temple_model_num]</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[color]</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[model]</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$DateEnvoieLabo</td>
						   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
						   <td style=\"font-size:16px;\"  align=\"center\"><a href=\"retirer_de_la_liste.php?on=$listItem[order_num]\">Delete from this list</a></td>";
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
				   <td style=\"font-size:16px;\"  align=\"center\"><a href=\"retirer_de_la_liste.php?on=$listItem[order_num]\">Delete from this list</a></td>";
				}
			}
			
			

		$message.=	"</tr>";
}//END WHILE

$message.=	"<tr><td colspan=\"7\" align=\"center\" style=\"font-size:16px;\" >Total:$OrderAbleToUpdate frame(s)</td></tr>";	
$message.=	"</table>";
}







echo $message;	
echo '<div align="center"><p><b>1- Please print this page before sending the report to the lab.</b><br><br> <br>2- Make sure your box is 100% ready before sending it to the lab</p>
<input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Send this list to the lab" class="formField"><br>
<b>N.B.:</b> <b>Once this report is sent, all the frames that it contains will be removed from this list.</b> <br><br>
</div>';	  				  
echo "</form>";
?>

</td>
	  </tr>
</table>

</body>

<?php
if ($_REQUEST[rpt_search]=='Send this list to the lab'){
//Envoyer le rapport par courriel a Kelly + copie au lab
	switch($AccessData[id]){
			
		case 1: 
		$UserIDIN = "('88666')";  			 
		$IdentificationEntrepot = "Griffé Lunetier Trois-Rivieres";   		
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
			
		case 2: 
		$UserIDIN = "('88666')";  	    	
		$IdentificationEntrepot = "COMME ADMIN DES 21 HBC VOUS NE POUVEZ PAS UTILISER CE RAPPORT. SVP VOUS CONNECTER DANS CHAQUE MAGASIN INDIVIDUELLEMENT POUR PREPARER CE RAPPORT.";   
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						 
		case 26: 
		$UserIDIN = "('88433')";  	
		$IdentificationEntrepot = "HBC Store #88433: Polo Park";   
		$send_to_address = array('rapports@direct-lens.com');
		break;	 
					
		case 25: 
		$UserIDIN = "('88430')";  		   
		$IdentificationEntrepot = "HBC Store #88430: St. Vital";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 
					
		case 24: 
		$UserIDIN = "('88444')";  	   
		$IdentificationEntrepot = "HBC Store #88444: Mayfair"; 
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	  
						 
		case 23: 
		$UserIDIN = "('88439')";  	    
		$IdentificationEntrepot = "HBC Store #88439: Langley"; 
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 
							 
		case 22: 
		$UserIDIN = "('88438')";  		    
		$IdentificationEntrepot = "HBC Store #87438: Metrotown";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 
					  
		case 21: 
		$UserIDIN = "('88416')";  		   
		$IdentificationEntrepot = "HBC Store #88416: Vancouver DTN"; 
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 

		case 20: 
		$UserIDIN = "('88429')";  	  
		$IdentificationEntrepot = "HBC Store #88429: Saskatoon"; 
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 
						  
		case 19: 
		$UserIDIN = "('88441')";  		    
		$IdentificationEntrepot = "HBC Store #88441: Southcentre";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
 
		case 18: 
		$UserIDIN = "('88435')";  		    
		$IdentificationEntrepot = "HBC Store #88435: West Edmonton";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
						 
		case 17: 
		$UserIDIN = "('88434')";  	
		$IdentificationEntrepot = "HBC Store #88434: Market Mall";   
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
					
		case 16: 
		$UserIDIN = "('88432')";  	
		$IdentificationEntrepot = "HBC Store #88432: Edmonton Centre"; 
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
						 
		case 15: 
		$UserIDIN = "('88431')";  	;	    
		$IdentificationEntrepot = "HBC Store #88431: Calgary DTN";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
						
		case 14: 
		$UserIDIN = "('88442')";  	    
		$IdentificationEntrepot = "HBC Store #88442: Bayshore";   
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
						 
		case 13: 
		$UserIDIN = "('88440')";  	  
		$IdentificationEntrepot = "HBC Store #88440: Rideau";   
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						 
		case 12: 
		$UserIDIN = "('88414')";  	    
		$IdentificationEntrepot = "HBC Store #88414: Yorkdale";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						 
		case 11: 
		$UserIDIN = "('88411')";  	   
		$IdentificationEntrepot = "HBC Store #88411: Sherway";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	 
						
		case 10: 
		$UserIDIN = "('88408')";  	
		$IdentificationEntrepot = "HBC Store #88408: Oshawa";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
						 
		case 9: 
		$UserIDIN = "('88403')";  	  
		$IdentificationEntrepot = "HBC Store #88403: Bloor St.";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						 
		case 8: 
		$UserIDIN = "('88449')";  		    
		$IdentificationEntrepot = "HBC Store #88449: Mississauga";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						 
		case 7: 
		$UserIDIN = "('88405')";  		    
		$IdentificationEntrepot = "HBC Store #88405: Fairview";
		$send_to_address = array('rapports@direct-lens.com'); 
		break;
						
		case 6: 
		$UserIDIN = "('88409')";  	
		$IdentificationEntrepot = "HBC Store #88409: Eglinton";  
		$send_to_address = array('rapports@direct-lens.com'); 
		break;	
				
		default:  $UserIDIN = "('aaa')"; $send_to_address = array('rapports@direct-lens.com');  
	}
	
	//Envoie du courriel
	$curTime      = date("Y-m-d");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Frame sent ". $IdentificationEntrepot;
	//echo '<br>'. $subject;
	//echo '<br>';
	//var_dump($to_address);
	
	
	$QueryUpdateEnvoyes = "UPDATE orders set frame_sent_saintcath ='sent', date_frame_sent_saintcath ='$curTime' WHERE user_id IN $UserIDIN AND frame_Sent_saintcath = 'yes'";
	$resultCleanEnvoyes = mysqli_query($con,$QueryUpdateEnvoyes) or die  ('<strong>SVP AVISER CHARLES DE CE MESSAGE D\'ERREUR EN L\'IMPRIMANT:</strong> '. $QueryUpdateEnvoyes . mysqli_error($con));
	
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	if($response){ 
		echo '<div align="center" id="anchorresultat"><font color="#136744">Rapport envoyé avec succès au laboratoire.</font><br>
		<font color="#FF0004">Suppression de la liste des montures de ce rapport en cours....terminé avec succès.</font></div>';
    }else{
		echo $response;
		echo '<div align="center" id="anchorresultat">PROBLEM  CANNOT SEND THE  REPORT BY EMAIL.'. $response. '  PLEASE PRINT THIS REPORT AND ADD IT IN THE BOX WITH THE FRAMES</div>';
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
		
	
}//End if ('Envoyer cette liste par courriel au Lab')
 ?>

</html>