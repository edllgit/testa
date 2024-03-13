<?php
//Afficher toutes les erreurs/avertissements
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
//require_once('../includes/class.ses.php');
$time_start = microtime(true);
$today     =  date("Y-m-d");
echo '<br>Test<br>';


//Phase 1: tous les produits SAUF LES STOCKS
$rptQuery="SELECT * FROM ifc_ca_Exclusive WHERE 
 collection IN ('Entrepot CSC', 'Entrepot FT', 'Entrepot HKO', 'Entrepot Promo', 'Entrepot Sky', 'Entrepot STC', 'Entrepot Swiss', 'NURBS sunglasses','Entrepot KNR')
AND prod_status = 'active'
AND maj_prix_novembre2022 = ''
AND categorie_optipro<>''
AND categorie_optipro<>'INTERNET'
AND index_v IN (1.50, 1.53, 1.59, 1.60, 1.67, 1.74 )
AND product_name not like '%stock%'
order by categorie_optipro
LIMIT 0,100";


 
//On doit prendre tous les indices car il y a des MAJ a faire dans tous les indices meme les 1.5 (exemple si ce sont des AR+ETC)
echo $rptQuery.'<br>';




	
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResult);
	
	if ($ordersnum!=0){
		$count=0;
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

		$message.="<body><table width=\"850\" cellpadding=\"2\" border=\"1\" cellspacing=\"0\" class=\"TextSize\">
				<tr><th colspan=\"15\">Avant mise à jour</th></tr>
				<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">Produit</td>
					<td align=\"center\">Clé</td>
					
					<td bgcolor=\'#ffff66\' align=\"center\">Vendant EDLL <br>(Database, PAIRE)</td>
					<td bgcolor=\'#ffff66\' align=\"center\">Vendant EDLL<br> CALCULÉ (PAIRE)</td>
					<td bgcolor=\'#ffff66\' align=\"center\">Majoration Vendant EDLL</td>
					<td align=\"center\">Cat. Optipro</td>
					<td  bgcolor=\"#33ccff\" align=\"center\"><b>INTERCO</b><br>(DATABASE, Paire)</td>
					<td  bgcolor=\"#33ccff\" align=\"center\"><b>INTERCO</b><br>(CALCULÉ, Paire)</td>
				</tr>";
				
	}
	
	
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
				
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		$primary_key		= $listItem[primary_key];
		$index_v   			= $listItem[index_v];
		$categorie_optipro  = $listItem[categorie_optipro];
		$coating   			= $listItem[coating]; 
		$price_can 			= $listItem[price_can];	
		$vendant_edll 		= $listItem[vendant_edll];
		$photo 				= $listItem[photo];
		$polar 				= $listItem[polar];
		$MajorationIndice 	= 0;
		$MajorationCoating 	= 0;
		
	$PrixTraitementDoitEtreDiviserParDeux = 'non';
		
		//Élaborer le prix du produit selon la catégorie dans Optipro
		switch($categorie_optipro){
			case 'SV HD': 
				$Prix_Optipro_Paire_Indice_150	=100;
				$Prix_Optipro_Paire_Indice_153	=160;
				$Prix_Optipro_Paire_Indice_159	=160;
				$Prix_Optipro_Paire_Indice_160	=190;
				$Prix_Optipro_Paire_Indice_167	=210;
				$Prix_Optipro_Paire_Indice_174	=260;
			break;	
			
			case 'SIMPLE VISION SURFACE': 
				$Prix_Optipro_Paire_Indice_150	= 70;
				$Prix_Optipro_Paire_Indice_153	=130;
				$Prix_Optipro_Paire_Indice_159	=130;
				$Prix_Optipro_Paire_Indice_160	=160;
				$Prix_Optipro_Paire_Indice_167	=180;
				$Prix_Optipro_Paire_Indice_174	=230;
			break;	
			
			case 'SIMPLE VISION SURFACE 420': 
				$Prix_Optipro_Paire_Indice_150	=80;
				$Prix_Optipro_Paire_Indice_153	=170;
				$Prix_Optipro_Paire_Indice_159	=170;
				$Prix_Optipro_Paire_Indice_160	=170;
				$Prix_Optipro_Paire_Indice_167	=190;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;
			
			case 'IACTION SV':      			
				$Prix_Optipro_Paire_Indice_150	=160;
				$Prix_Optipro_Paire_Indice_153	=250;
				$Prix_Optipro_Paire_Indice_159	=250;
				$Prix_Optipro_Paire_Indice_160	=250;
				$Prix_Optipro_Paire_Indice_167	=270;
				$Prix_Optipro_Paire_Indice_174	=320;
			break;
			
			case 'MAXIWIDE':  
				$Prix_Optipro_Paire_Indice_150	=340;
				$Prix_Optipro_Paire_Indice_153	=430;
				$Prix_Optipro_Paire_Indice_159	=430;
				$Prix_Optipro_Paire_Indice_160	=430;
				$Prix_Optipro_Paire_Indice_167	=450;
				$Prix_Optipro_Paire_Indice_174	=500;		
			break;	
			
			case 'IACTION':      			
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=370;
				$Prix_Optipro_Paire_Indice_159	=370;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=440;
			break;	
			
			case 'IRELAX': 
				$Prix_Optipro_Paire_Indice_150	=160;
				$Prix_Optipro_Paire_Indice_153	=250;
				$Prix_Optipro_Paire_Indice_159	=250;
				$Prix_Optipro_Paire_Indice_160	=250;
				$Prix_Optipro_Paire_Indice_167	=270;
				$Prix_Optipro_Paire_Indice_174	=320;
			break;	
			
			case 'AI':      				
				$Prix_Optipro_Paire_Indice_150	=340;
				$Prix_Optipro_Paire_Indice_153	=400;
				$Prix_Optipro_Paire_Indice_159	=400;
				$Prix_Optipro_Paire_Indice_160	=430;
				$Prix_Optipro_Paire_Indice_167	=450;
				$Prix_Optipro_Paire_Indice_174	=500;	
			break;	
			
			case 'AI 2ND':      				
				$Prix_Optipro_Paire_Indice_150	=170;
				$Prix_Optipro_Paire_Indice_153	=200;
				$Prix_Optipro_Paire_Indice_159	=200;
				$Prix_Optipro_Paire_Indice_160	=215;
				$Prix_Optipro_Paire_Indice_167	=225;
				$Prix_Optipro_Paire_Indice_174	=250;	
			break;
			
			case 'IFREE':      				
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=370;
				$Prix_Optipro_Paire_Indice_159	=370;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=440;	
			break;	
			
			case 'PROGRESSIF HD IOT':      				
				$Prix_Optipro_Paire_Indice_150	=230;
				$Prix_Optipro_Paire_Indice_153	=320;
				$Prix_Optipro_Paire_Indice_159	=320;
				$Prix_Optipro_Paire_Indice_160	=320;
				$Prix_Optipro_Paire_Indice_167	=340;
				$Prix_Optipro_Paire_Indice_174	=390;	
			break;	
			
			case 'FT35':      				
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=999;
				$Prix_Optipro_Paire_Indice_159	=999;
				$Prix_Optipro_Paire_Indice_160	=999;
				$Prix_Optipro_Paire_Indice_167	=999;
				$Prix_Optipro_Paire_Indice_174	=999;	
			break;	
			
			case 'ULTIMATE':  
				$Prix_Optipro_Paire_Indice_150	=230;
				$Prix_Optipro_Paire_Indice_153	=320;
				$Prix_Optipro_Paire_Indice_159	=320;
				$Prix_Optipro_Paire_Indice_160	=320;
				$Prix_Optipro_Paire_Indice_167	=340;
				$Prix_Optipro_Paire_Indice_174	=390;	
			break;	
			
			case 'BETTER':
				$Prix_Optipro_Paire_Indice_150	=230;
				$Prix_Optipro_Paire_Indice_153	=320;
				$Prix_Optipro_Paire_Indice_159	=320;
				$Prix_Optipro_Paire_Indice_160	=320;
				$Prix_Optipro_Paire_Indice_167	=340;
				$Prix_Optipro_Paire_Indice_174	=390;	
			break;	

			case 'GOOD':
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=230;
				$Prix_Optipro_Paire_Indice_159	=230;
				$Prix_Optipro_Paire_Indice_160	=230;
				$Prix_Optipro_Paire_Indice_167	=999;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;	
			
			case 'INTERNET FATIGUE':
				$Prix_Optipro_Paire_Indice_150	=90;
				$Prix_Optipro_Paire_Indice_153	=170;
				$Prix_Optipro_Paire_Indice_159	=170;
				$Prix_Optipro_Paire_Indice_160	=170;
				$Prix_Optipro_Paire_Indice_167	=170;
				$Prix_Optipro_Paire_Indice_174	=170;
			break;	
			
			case 'NUM IOT':      				
				$Prix_Optipro_Paire_Indice_150	=160;
				$Prix_Optipro_Paire_Indice_153	=250;
				$Prix_Optipro_Paire_Indice_159	=250;
				$Prix_Optipro_Paire_Indice_160	=250;
				$Prix_Optipro_Paire_Indice_167	=270;
				$Prix_Optipro_Paire_Indice_174	=320;
			break;	
			
			case 'PRECISION FATIGUE':
				$Prix_Optipro_Paire_Indice_150	=160;
				$Prix_Optipro_Paire_Indice_153	=250;
				$Prix_Optipro_Paire_Indice_159	=250;
				$Prix_Optipro_Paire_Indice_160	=250;
				$Prix_Optipro_Paire_Indice_167	=270;
				$Prix_Optipro_Paire_Indice_174	=320;
			break;
			
			case 'BEST':
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=370;
				$Prix_Optipro_Paire_Indice_159	=370;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=440;
			break;
			
			case 'BEST ACTIVE':
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=370;
				$Prix_Optipro_Paire_Indice_159	=370;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=440;
			break;
			
			case 'INTERNET':
				$Prix_Optipro_Paire_Indice_150	=100;
				$Prix_Optipro_Paire_Indice_153	=135;
				$Prix_Optipro_Paire_Indice_159	=135;
				$Prix_Optipro_Paire_Indice_160	=135;
				$Prix_Optipro_Paire_Indice_167	=200;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;
			
			case 'IFREE PLUS ADVANCE':      				
				$Prix_Optipro_Paire_Indice_150	=430;
				$Prix_Optipro_Paire_Indice_153	=430;
				$Prix_Optipro_Paire_Indice_159	=430;
				$Prix_Optipro_Paire_Indice_160	=430;
				$Prix_Optipro_Paire_Indice_167	=450;
				$Prix_Optipro_Paire_Indice_174	=500;	
			break;	
			
			case 'PROGRESSIF ADVANCE': 		
				$Prix_Optipro_Paire_Indice_150	=340;
				$Prix_Optipro_Paire_Indice_153	=430;
				$Prix_Optipro_Paire_Indice_159	=430;
				$Prix_Optipro_Paire_Indice_160	=430;
				$Prix_Optipro_Paire_Indice_167	=450;
				$Prix_Optipro_Paire_Indice_174	=520;	
			break;
			
			case 'READER': case 'OFFICE':    case 'ROOM':   				
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=230;
				$Prix_Optipro_Paire_Indice_159	=230;
				$Prix_Optipro_Paire_Indice_160	=230;
				$Prix_Optipro_Paire_Indice_167	=250;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;	
			
			case 'PROGRESSIF DUO HD':    				
				$Prix_Optipro_Paire_Indice_150	=230;
				$Prix_Optipro_Paire_Indice_153	=290;
				$Prix_Optipro_Paire_Indice_159	=290;
				$Prix_Optipro_Paire_Indice_160	=320;
				$Prix_Optipro_Paire_Indice_167	=340;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;	
			
			case 'PROGRESSIF DUO NUMERIQUE HD':    				
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=200;
				$Prix_Optipro_Paire_Indice_159	=200;
				$Prix_Optipro_Paire_Indice_160	=230;
				$Prix_Optipro_Paire_Indice_167	=250;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;	
			
			case 'PROG. DUO NUMERIQUE HD IOT':    				
				$Prix_Optipro_Paire_Indice_150	=160;
				$Prix_Optipro_Paire_Indice_153	=220;
				$Prix_Optipro_Paire_Indice_159	=220;
				$Prix_Optipro_Paire_Indice_160	=250;
				$Prix_Optipro_Paire_Indice_167	=270;
				$Prix_Optipro_Paire_Indice_174	=999;
			break;	
			
			case 'ALPHA HD':    				
				$Prix_Optipro_Paire_Indice_150	=230;
				$Prix_Optipro_Paire_Indice_153	=320;
				$Prix_Optipro_Paire_Indice_159	=320;
				$Prix_Optipro_Paire_Indice_160	=320;
				$Prix_Optipro_Paire_Indice_167	=340;
				$Prix_Optipro_Paire_Indice_174	=390;
			break;	
			
			case 'ALPHA 4D':    				
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=340;
				$Prix_Optipro_Paire_Indice_159	=340;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=440;
			break;	
			
				     				
			case 'PROGRESSIF DUO INDIVIDUALISE':
				$Prix_Optipro_Paire_Indice_150	=280;
				$Prix_Optipro_Paire_Indice_153	=340;
				$Prix_Optipro_Paire_Indice_159	=340;
				$Prix_Optipro_Paire_Indice_160	=370;
				$Prix_Optipro_Paire_Indice_167	=390;
				$Prix_Optipro_Paire_Indice_174	=999;	
			break;	
			
			
			case 'FT28':
				$Prix_Optipro_Paire_Indice_150	=130;
				$Prix_Optipro_Paire_Indice_153	=190;
				$Prix_Optipro_Paire_Indice_159	=190;
				$Prix_Optipro_Paire_Indice_160	=190;
				$Prix_Optipro_Paire_Indice_167	=250;
				$Prix_Optipro_Paire_Indice_174	=999;//Non offert en 1.74	
			break;
				
				
			//2ieme paire a moitié prix
			
			case 'PRECISION FATIGUE 2ND':
				$Prix_Optipro_Paire_Indice_150	=80;
				$Prix_Optipro_Paire_Indice_153	=125;
				$Prix_Optipro_Paire_Indice_159	=125;
				$Prix_Optipro_Paire_Indice_160	=125;
				$Prix_Optipro_Paire_Indice_167	=135;
				$Prix_Optipro_Paire_Indice_174	=160;
				$PrixTraitementDoitEtreDiviserParDeux = 'oui';
			break;
			
			case 'GOOD 2ND':
				$Prix_Optipro_Paire_Indice_150	=70;
				$Prix_Optipro_Paire_Indice_153	=115;
				$Prix_Optipro_Paire_Indice_159	=115;
				$Prix_Optipro_Paire_Indice_160	=115;
				$Prix_Optipro_Paire_Indice_167	=999;
				$Prix_Optipro_Paire_Indice_174	=999;
				$PrixTraitementDoitEtreDiviserParDeux = 'oui';
			break;	
			
			case 'BETTER 2ND':
				$Prix_Optipro_Paire_Indice_150	=115;
				$Prix_Optipro_Paire_Indice_153	=160;
				$Prix_Optipro_Paire_Indice_159	=160;
				$Prix_Optipro_Paire_Indice_160	=160;
				$Prix_Optipro_Paire_Indice_167	=170;
				$Prix_Optipro_Paire_Indice_174	=195;	
				$PrixTraitementDoitEtreDiviserParDeux = 'oui';
			break;
			
			case 'BEST 2ND':
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=185;
				$Prix_Optipro_Paire_Indice_159	=185;
				$Prix_Optipro_Paire_Indice_160	=185;
				$Prix_Optipro_Paire_Indice_167	=195;
				$Prix_Optipro_Paire_Indice_174	=220;	
				$PrixTraitementDoitEtreDiviserParDeux = 'oui';
			break;
			
			case 'BEST ACTIVE 2ND':
				$Prix_Optipro_Paire_Indice_150	=140;
				$Prix_Optipro_Paire_Indice_153	=185;
				$Prix_Optipro_Paire_Indice_159	=185;
				$Prix_Optipro_Paire_Indice_160	=185;
				$Prix_Optipro_Paire_Indice_167	=195;
				$Prix_Optipro_Paire_Indice_174	=220;	
				$PrixTraitementDoitEtreDiviserParDeux = 'oui';
			break;
			
					
			default: echo 'Categorie Introuvable:'. $categorie_optipro.' , arrêt'; 
			exit();		
		}
		
	
	
	$coating = strtoupper($coating);
	
	switch($coating){
			//Aucune majoration
			case 'HARD COAT':  				$CoutPourTraitement=0;		break; 
			case 'UNCOATED':  				$CoutPourTraitement=0;		break;	
			//ANTI-REFLETS: 90$
			case 'DREAM AR':  				$CoutPourTraitement=90;		break;  	
			case 'AR BACKSIDE':  			$CoutPourTraitement=90;		break;
			case 'ITO AR':  				$CoutPourTraitement=90;		break;
			case 'IBLU':  					$CoutPourTraitement=90;		break;
			case 'NIGHT VISION':  			$CoutPourTraitement=90;		break;
			case 'MULTICLEAR AR':  			$CoutPourTraitement=90;		break;
			case 'SUPER AR':  				$CoutPourTraitement=90;		break;
			case 'SUPER AR BACKSIDE':  		$CoutPourTraitement=90;		break;
			//TRAITEMENT SUPÉRIEURS: 120$
			case 'HD AR':  					$CoutPourTraitement=120;	break;		
			case 'HD AR BACKSIDE':  		$CoutPourTraitement=120;	break;	
			case 'XLR':  					$CoutPourTraitement=120;	break;	
			case 'XLR BACKSIDE':  			$CoutPourTraitement=120;	break;	
			case 'STRESSFREE':  			$CoutPourTraitement=120;	break;	
			case 'LOW REFLEXION': 			$CoutPourTraitement=120;	break;	
			case 'LOW REFLEXION BACKSIDE': 	$CoutPourTraitement=120;	break;	
			case 'LOW REFLEXION BACKSIDE':  $CoutPourTraitement=120;	break;	
			case 'AR-ES': 					$CoutPourTraitement=120;	break;	
			//TRAITEMENT Lumière bleue: 145$
			case 'BLUCUT': 					$CoutPourTraitement=145;	break;	
			
			default: echo 'Traitement inconnu:' .$coating . ' On stoppe le traitement'; //exit();
		}
		
		if ($PrixTraitementDoitEtreDiviserParDeux=='oui'){
			$CoutPourTraitement=$CoutPourTraitement/2;
		}
	
	
		switch($photo){
			//Aucun transitions
			case 'None':  				$CoutPourPhoto=0;		break; 
			//Transitions réguliers = 100$
			case 'Grey':  				$CoutPourPhoto=100;		break;	
			case 'Brown':  				$CoutPourPhoto=100;		break;	
			case 'Blue':  				$CoutPourPhoto=100;		break;	
			case 'Day Nite':  			$CoutPourPhoto=100;		break;	
			//Transitions Vert = 140$
			case 'Green':  				$CoutPourPhoto=140;		break;	
			case 'Grafite':  			$CoutPourPhoto=140;		break;	
			//Extra Active = 140$
			case 'Extra Active Grey':  	$CoutPourPhoto=140;		break;	
			case 'Extra Active Brown':  $CoutPourPhoto=140;		break;	
			//Drivewear 
			case 'Drivewear':  			$CoutPourPhoto=150;		break;
			
			default: echo 'Photo inconnu:' .$photo . '  on stoppe le traitement'; exit();
		}
		
		
		switch($polar){
			//Aucun polarisé
			case 'None':  				$CoutPourPolar=0;		break; 
			//Polarisé réguliers = 100$
			case 'Grey':  				$CoutPourPolar=100;		break;	
			case 'Brown':  				$CoutPourPolar=100;		break;	
			case 'Green':  				$CoutPourPolar=100;		break;	
			case 'Drivewear':  			$CoutPourPolar=0;		break;//Car déja chargé dans la partie PHOTO	
			
			default: echo 'Polar inconnu:' .$polar . '  on stoppe le traitement'; exit();
		}
		
		
		if ($categorie_optipro<>'SIMPLE VISION SURFACE 420'){
			//UV420 
			$CoutPourUV420 = 0;
			
			$Position = strpos($listItem[product_name],'420');
			
			if ($Position>0){//Il y a '420' dans le nom du produit
				//echo '<br>Position:'.  $Position;
				$CoutPourUV420 = 25;
			}
		}//END IF
		
		
		
		//ARMOUR 420
		$CoutPourARMOUR420 = 0;
		
		$PositionArmour420 = strpos($listItem[product_name],'Armour 420');
		
		if ($PositionArmour420>0){//Il y a 'Armour 420' dans le nom du produit
			//echo '<br>PositionArmour420:'.  $Position;
			$CoutPourARMOUR420 = 25;
		}
		
		
		
		
		
		//THIN ATORIQUE 
		$CoutPourThinAtorique = 0;
		
		$PositionThinAtorique = strpos($listItem[product_name],'Atorique');
		
		if ($PositionThinAtorique>0){//Il y a '420' dans le nom du produit
			//echo '<br>PositionThinAtorique:'.  $PositionThinAtorique;
			$CoutPourThinAtorique = 15;
		}
		
		
	

	switch($index_v){
		case '1.50':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_150 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;	
		
		case '1.53':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_153 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;	
		
		case '1.59':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_159 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;	
		
		case '1.60':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_160 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;	
		
		case '1.67':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_167 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;	
		
		case '1.74':   $PrixPaireOptipro= $Prix_Optipro_Paire_Indice_174 + $CoutPourTraitement + $CoutPourPhoto + $CoutPourPolar + $CoutPourUV420 + $CoutPourThinAtorique + $CoutPourARMOUR420;  break;		
	}

		
	
$NouvelInterco = $PrixPaireOptipro/2.5;
$MajorationTotale =  $PrixPaireOptipro - $listItem[vendant_edll];

if ($NouvelInterco<>$price_can){
	//Doit mettre a jour avec le nouveau prix		
	$maj_prix_novembre2022 = 'Prix mis a jour par (script de Charles)'. $today;
	$QueryMAJPrix  = "UPDATE ifc_ca_exclusive 
	SET  price = $NouvelInterco,  price_can = $NouvelInterco, 
	vendant_edll = $PrixPaireOptipro,
	maj_prix_novembre2022='$maj_prix_novembre2022' 
	WHERE  primary_key = $primary_key";
}elseif($NouvelInterco==$price_can){
	$maj_prix_novembre2022 = 'Aucune maj a effectuer.(script de Charles)'. $today;
	$QueryMAJPrix  = "UPDATE ifc_ca_exclusive 
	SET  maj_prix_novembre2022='$maj_prix_novembre2022' 
	WHERE  primary_key = $primary_key";
}

echo '<br>'. $QueryMAJPrix;
	
	//TEMPORAIREMENT EN COMMENTAIRE LE TEMPS DE VALIDER
	$resultMAJPrix = mysqli_query($con,$QueryMAJPrix) or die  ('I cannot select items because: ' . mysqli_error($con));






	$message.="<tr bgcolor=\"FFFFFF\">
					<td align=\"center\">".$listItem[product_name]."</td>
					<td align=\"center\">".$listItem[primary_key]."</td>
					<td bgcolor=\'#ffff66\' align=\"center\">".$listItem[vendant_edll]."</td>
					<td bgcolor=\'#ffff66\' align=\"center\"><b>".$PrixPaireOptipro."</b></td>
					<td bgcolor=\'#ffff66\' align=\"center\">".$MajorationTotale."$</td>
					<td align=\"center\">".$categorie_optipro."</td>
					<td bgcolor=\"#33ccff\" align=\"center\">".$listItem[price_can]."</td>
					<td  bgcolor=\"#33ccff\" align=\"center\"><b>".$NouvelInterco."</b></td>
				</tr>";	

}//End While
	
			
		$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"15\">Number of product analyzed: $ordersnum</td></tr></table>";
		echo $message;
		


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="MAJ prix EDLL [RÉEL] Novembre 2022 Rapport de Validation: ". $curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);


	if($response){ 
		echo 'envoyé avec succes';
		//log_email("REPORT: Login Attempt",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
			echo 'probleme dans l\'envoie';
		//log_email("REPORT: Login Attempt",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}

//exit();	
//}//end IF	

?>