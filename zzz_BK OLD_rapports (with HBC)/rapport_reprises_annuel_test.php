<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=iso-8859-1');
*/
?>
<?php
   // error_reporting( E_ALL );
?>
<?php
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
//require_once('../includes/class.ses.php');
$time_start = microtime(true);
/*$date1 	    = "2019-01-01";
$date2      = "2019-03-27";*/
$date1 	    = "2019-01-01";
$date2      = "2019-07-10";
$NombredeSuccursale = 13;



//PARTIE 1: SOMMAIRE DANIEL BEAULIEU: Fonctionne correctement[Testé le 27 Mars 2019]
//1.1-Nombre de commandes envoyees: [INCLUE LES REPRISES]
/*
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){/
	   case 1  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe')             	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 2  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesDR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')            	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 3  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesCH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')    	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;	 
	   case 4  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesTR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')     	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break; 
	   case 5  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesSH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')    	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 6  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesTE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')    	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 7  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLO  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')      	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 8  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLE  FROM ORDERS WHERE user_id IN ('levis','levissafe')              	AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 9  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesHA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe') AND order_date_shipped BETWEEN '$date1' and '$date2'"; 	break;
	   case 10 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesGR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')            	AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 11 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')    	AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   //case 12 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesSMB FROM ORDERS WHERE user_id IN ('stemarie','stemariesafe')    		AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 12 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesMTL FROM ORDERS WHERE user_id IN ('montreal','montrealsafe')    		AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   
   }//End Switch
	
	
	$resultNbrEnvoyes = mysqli_query($con,$QuerySucc)		or die  ('I cannot select items because 5r6: ' . mysqli_error($con));
	$DataNbrEnvoyes   = mysqli_fetch_array($resultNbrEnvoyes,MYSQLI_ASSOC);
		
	 switch($x){ 
	   case 1  :$NbrEnvoyesLV   =  $DataNbrEnvoyes[NbrEnvoyesLV];  break;
	   case 2  :$NbrEnvoyesDR   =  $DataNbrEnvoyes[NbrEnvoyesDR];  break;
	   case 3  :$NbrEnvoyesCH   =  $DataNbrEnvoyes[NbrEnvoyesCH];  break;
	   case 4  :$NbrEnvoyesTR   =  $DataNbrEnvoyes[NbrEnvoyesTR];  break; 
	   case 5  :$NbrEnvoyesSH   =  $DataNbrEnvoyes[NbrEnvoyesSH];  break;
	   case 6  :$NbrEnvoyesTE   =  $DataNbrEnvoyes[NbrEnvoyesTE];  break;
	   case 7  :$NbrEnvoyesLO   =  $DataNbrEnvoyes[NbrEnvoyesLO];  break;
	   case 8  :$NbrEnvoyesLE   =  $DataNbrEnvoyes[NbrEnvoyesLE];  break;
	   case 9  :$NbrEnvoyesHA   =  $DataNbrEnvoyes[NbrEnvoyesHA];  break;
	   case 10 :$NbrEnvoyesGR   =  $DataNbrEnvoyes[NbrEnvoyesGR];  break;
	   case 11 :$NbrEnvoyesQC   =  $DataNbrEnvoyes[NbrEnvoyesQC];  break;
	  // case 12 :$NbrEnvoyesSMB  =  $DataNbrEnvoyes[NbrEnvoyesSMB]; break;
	   case 12 :$NbrEnvoyesMTL  =  $DataNbrEnvoyes[NbrEnvoyesMTL]; break;
	   
   }//End Switch	
	$TotalCommandesEnvoyees = $NbrEnvoyesLV+$NbrEnvoyesDR+$NbrEnvoyesCH+$NbrEnvoyesTR+$NbrEnvoyesSH+$NbrEnvoyesTE+$NbrEnvoyesLO+$NbrEnvoyesLE+$NbrEnvoyesHA+$NbrEnvoyesGR+$NbrEnvoyesQC+$NbrEnvoyesMTL ;//+$NbrEnvoyesMTL
}//End FOR





//1.2- Nombre de reprises
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseLV, sum(order_total) as ValeurRepriseLV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_order_num IS NOT NULL 
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 2: 
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseDR, sum(order_total) as ValeurRepriseDR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')  AND redo_order_num IS NOT NULL 
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 3:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseCH, sum(order_total) as ValeurRepriseCH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_order_num IS NOT NULL 
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 4:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseTR, sum(order_total) as ValeurRepriseTR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_order_num IS NOT NULL      
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseSH, sum(order_total) as ValeurRepriseSH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')  AND redo_order_num IS NOT NULL    
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
	  
	   case 6:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseTE, sum(order_total) as ValeurRepriseTE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe') AND redo_order_num IS NOT NULL    
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
       $QueryReprise = "SELECT count(order_num) as NbrRepriseLO, sum(order_total) as ValeurRepriseLO  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_order_num IS NOT NULL      
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 8:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseLE, sum(order_total) as ValeurRepriseLE  FROM ORDERS WHERE user_id IN ('levis','levissafe') AND redo_order_num IS NOT NULL               
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 9:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseHA, sum(order_total) as ValeurRepriseHA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe') AND redo_order_num IS NOT NULL 
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseGR, sum(order_total) as ValeurRepriseGR  FROM ORDERS WHERE user_id IN ('granby','granbysafe') AND redo_order_num IS NOT NULL             
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   	   
	   case 11:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseQC, sum(order_total) as ValeurRepriseQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_order_num IS NOT NULL           
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   //case 12:
	   //$QueryReprise = "SELECT count(order_num) as NbrRepriseSMB, sum(order_total) as ValeurRepriseSMB  FROM ORDERS WHERE user_id IN ('stemarie','stemariesafe')  AND redo_order_num IS NOT NULL           
	   //AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //break;
	   
	   case 12:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseMTL, sum(order_total) as ValeurRepriseMTL  FROM ORDERS WHERE user_id IN ('montreal','montrealsafe')  AND redo_order_num IS NOT NULL           
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	 
   }//End Switch
	
	
	$resultNbrReprise = mysqli_query($con,$QueryReprise)		or die  ('I cannot select items because d9: ' . mysqli_error($con));
	$DataReprise      = mysqli_fetch_array($resultNbrReprise,MYSQLI_ASSOC);
		
	 switch($x){ 
	   case 1  :$NbrRepriseLV  = $DataReprise[NbrRepriseLV];  $ValeurRepriseLV  = number_format($DataReprise[ValeurRepriseLV], 2, ",", " " );  $ValeurRepriseLVBU  = $DataReprise[ValeurRepriseLV];  		break;
	   case 2  :$NbrRepriseDR  = $DataReprise[NbrRepriseDR];  $ValeurRepriseDR  = number_format($DataReprise[ValeurRepriseDR], 2, ",", " " );  $ValeurRepriseDRBU  = $DataReprise[ValeurRepriseDR];  		break;
	   case 3  :$NbrRepriseCH  = $DataReprise[NbrRepriseCH];  $ValeurRepriseCH  = number_format($DataReprise[ValeurRepriseCH], 2, ",", " " );  $ValeurRepriseCHBU  = $DataReprise[ValeurRepriseCH];  		break;
	   case 4  :$NbrRepriseTR  = $DataReprise[NbrRepriseTR];  $ValeurRepriseTR  = number_format($DataReprise[ValeurRepriseTR], 2, ",", " " );  $ValeurRepriseTRBU  = $DataReprise[ValeurRepriseTR];  		break;
	   case 5  :$NbrRepriseSH  = $DataReprise[NbrRepriseSH];  $ValeurRepriseSH  = number_format($DataReprise[ValeurRepriseSH], 2, ",", " " );  $ValeurRepriseSHBU  = $DataReprise[ValeurRepriseSH];  		break;
	   case 6  :$NbrRepriseTE  = $DataReprise[NbrRepriseTE];  $ValeurRepriseTE  = number_format($DataReprise[ValeurRepriseTE], 2, ",", " " );  $ValeurRepriseTEBU  = $DataReprise[ValeurRepriseTE];  		break;
	   case 7  :$NbrRepriseLO  = $DataReprise[NbrRepriseLO];  $ValeurRepriseLO  = number_format($DataReprise[ValeurRepriseLO], 2, ",", " " );  $ValeurRepriseLOBU  = $DataReprise[ValeurRepriseLO];  		break;
	   case 8  :$NbrRepriseLE  = $DataReprise[NbrRepriseLE];  $ValeurRepriseLE  = number_format($DataReprise[ValeurRepriseLE], 2, ",", " " );  $ValeurRepriseLEBU  = $DataReprise[ValeurRepriseLE];  		break;
	   case 9  :$NbrRepriseHA  = $DataReprise[NbrRepriseHA];  $ValeurRepriseHA  = number_format($DataReprise[ValeurRepriseHA], 2, ",", " " );  $ValeurRepriseHABU  = $DataReprise[ValeurRepriseHA];  		break;
	   case 10 :$NbrRepriseGR  = $DataReprise[NbrRepriseGR];  $ValeurRepriseGR  = number_format($DataReprise[ValeurRepriseGR], 2, ",", " " );  $ValeurRepriseGRBU  = $DataReprise[ValeurRepriseGR];  		break;
	   case 11 :$NbrRepriseQC  = $DataReprise[NbrRepriseQC];  $ValeurRepriseQC  = number_format($DataReprise[ValeurRepriseQC], 2, ",", " " );  $ValeurRepriseQCBU  = $DataReprise[ValeurRepriseQC];  		break;
	   //case 12 :$NbrRepriseSMB = $DataReprise[NbrRepriseSMB]; $ValeurRepriseSMB  = number_format($DataReprise[ValeurRepriseSMB], 2, ",", " " );  $ValeurRepriseSMBBU  = $DataReprise[ValeurRepriseSMB];  	break;
	   case 12 :$NbrRepriseMTL = $DataReprise[NbrRepriseMTL]; $ValeurRepriseMTL  = number_format($DataReprise[ValeurRepriseMTL], 2, ",", " " );  $ValeurRepriseMTLBU  = $DataReprise[ValeurRepriseMTL];  	break;
	  
   }//End Switch	
	$TotalReprises = $NbrRepriseLV+$NbrRepriseDR+$NbrRepriseCH+$NbrRepriseTR+$NbrRepriseSH+$NbrRepriseTE+$NbrRepriseLO+$NbrRepriseLE+$NbrRepriseHA+$NbrRepriseGR+$NbrRepriseQC+$NbrRepriseMTL;// +$NbrRepriseMTL;
			
}//End FOR


//1.3- Calcul des pourcentages de reprise
$PourcentageRepriseLV = ($NbrRepriseLV/$NbrEnvoyesLV)*100;
$PourcentageRepriseLV = round($PourcentageRepriseLV,2);
	
$PourcentageRepriseDR = ($NbrRepriseDR/$NbrEnvoyesDR)*100;
$PourcentageRepriseDR = round($PourcentageRepriseDR,2);

$PourcentageRepriseCH = ($NbrRepriseCH/$NbrEnvoyesCH)*100;
$PourcentageRepriseCH = round($PourcentageRepriseCH,2);

$PourcentageRepriseTR = ($NbrRepriseTR/$NbrEnvoyesTR)*100;
$PourcentageRepriseTR = round($PourcentageRepriseTR,2);

$PourcentageRepriseSH = ($NbrRepriseSH/$NbrEnvoyesSH)*100;
$PourcentageRepriseSH = round($PourcentageRepriseSH,2);

$PourcentageRepriseTE = ($NbrRepriseTE/$NbrEnvoyesTE)*100;
$PourcentageRepriseTE = round($PourcentageRepriseTE,2);

$PourcentageRepriseLO = ($NbrRepriseLO/$NbrEnvoyesLO)*100;
$PourcentageRepriseLO = round($PourcentageRepriseLO,2);

$PourcentageRepriseLE = ($NbrRepriseLE/$NbrEnvoyesLE)*100;
$PourcentageRepriseLE = round($PourcentageRepriseLE,2);

$PourcentageRepriseHA = ($NbrRepriseHA/$NbrEnvoyesHA)*100;
$PourcentageRepriseHA = round($PourcentageRepriseHA,2);
							  
$PourcentageRepriseGR = ($NbrRepriseGR/$NbrEnvoyesGR)*100;
$PourcentageRepriseGR = round($PourcentageRepriseGR,2);
							   
$PourcentageRepriseQC = ($NbrRepriseQC/$NbrEnvoyesQC)*100;
$PourcentageRepriseQC = round($PourcentageRepriseQC,2);

$PourcentageRepriseMTL = ($NbrRepriseMTL/$NbrEnvoyesMTL)*100;
$PourcentageRepriseMTL = round($PourcentageRepriseMTL,2);

$PourcentageRepriseSMB = ($NbrRepriseSMB/$NbrEnvoyesSMB)*100;
$PourcentageRepriseSMB = round($PourcentageRepriseSMB,2);

//Moyenne des pourcentages de reprise
$MoyennePourcentageReprise = ($PourcentageRepriseLV + $PourcentageRepriseDR + $PourcentageRepriseCH + $PourcentageRepriseTR + $PourcentageRepriseSH +  $PourcentageRepriseTE +
 $PourcentageRepriseLO + $PourcentageRepriseLE + $PourcentageRepriseHA + $PourcentageRepriseGR  + $PourcentageRepriseQC  + $PourcentageRepriseMTL + 0 )/$NombredeSuccursale;//TEMP: SMB:+  $PourcentageRepriseMTL
$MoyennePourcentageReprise = round($MoyennePourcentageReprise,2);



//1.4- Nombre de reprises (GARANTIES)
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGLV, sum(order_total) as ValeurRepriseGLV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') 
	   AND redo_order_num IS NOT NULL 
	   AND redo_origin='retour_client'
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 2: 
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGDR, sum(order_total) as ValeurRepriseGDR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') 
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 3:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGCH, sum(order_total) as ValeurRepriseGCH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') 
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 4:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGTR, sum(order_total) as ValeurRepriseGTR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') 
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'    
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGSH, sum(order_total) as ValeurRepriseGSH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') 
	   AND redo_order_num IS NOT NULL  
	   AND redo_origin='retour_client'   
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGTE, sum(order_total) as ValeurRepriseGTE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe') 
	   AND redo_order_num IS NOT NULL  
	   AND redo_origin='retour_client'   
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
       $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGLO, sum(order_total) as ValeurRepriseGLO  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'    
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 8:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGLE, sum(order_total) as ValeurRepriseGLE  FROM ORDERS WHERE user_id IN ('levis','levissafe') 
	   AND redo_order_num IS NOT NULL     
	   AND redo_origin='retour_client'            
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
       break;
		   
	   case 9:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGHA, sum(order_total) as ValeurRepriseGHA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe') 
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGGR, sum(order_total) as ValeurRepriseGGR  FROM ORDERS WHERE user_id IN ('granby','granbysafe') 
	   AND redo_order_num IS NOT NULL    
	   AND redo_origin='retour_client'           
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGQC, sum(order_total) as ValeurRepriseGQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  
	   AND redo_order_num IS NOT NULL     
	   AND redo_origin='retour_client'       
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	  
	   //case 12:
	   //$QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGSMB, sum(order_total) as ValeurRepriseGSMB  FROM ORDERS WHERE user_id IN ('stemarie','stemariesafe')  
	   //AND redo_order_num IS NOT NULL     
	   //AND redo_origin='retour_client'       
	   //AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //break;

	   case 12:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGMTL, sum(order_total) as ValeurRepriseGMTL  FROM ORDERS WHERE user_id IN ('montreal','montrealsafe')  
	   AND redo_order_num IS NOT NULL     
	   AND redo_origin='retour_client'       
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	  
   }//End Switch
	
	
	$resultNbrRepriseGaranties = mysqli_query($con,$QueryRepriseGaranties)		or die  ('I cannot select items because e8: ' . mysqli_error($con));
	$DataRepriseGaranties      = mysqli_fetch_array($resultNbrRepriseGaranties,MYSQLI_ASSOC);
		
	 switch($x){ 
	   case 1  :$NbrRepriseGLV       = $DataRepriseGaranties[NbrRepriseGLV]; 
			    $ValeurRepriseGLV    = number_format($DataRepriseGaranties[ValeurRepriseGLV], 2, ",", " " );  
			    $ValeurRepriseGLVBU  = $DataRepriseGaranties[ValeurRepriseGLV]; 
	   break;
			 
			 
	   case 2  :$NbrRepriseGDR       = $DataRepriseGaranties[NbrRepriseGDR];  
	   			$ValeurRepriseGDR    = number_format($DataRepriseGaranties[ValeurRepriseGDR], 2, ",", " " );  
			    $ValeurRepriseGDRBU  = $DataRepriseGaranties[ValeurRepriseGDR]; 
	   break;
	   
			 
	   case 3  :$NbrRepriseGCH       = $DataRepriseGaranties[NbrRepriseGCH];  
	   	        $ValeurRepriseGCH    = number_format($DataRepriseGaranties[ValeurRepriseGCH], 2, ",", " " );  
			    $ValeurRepriseGCHBU  = $DataRepriseGaranties[ValeurRepriseGCH]; 
	   break;
		
			 
	   case 4  :$NbrRepriseGTR       = $DataRepriseGaranties[NbrRepriseGTR]; 
	  		    $ValeurRepriseGTR    = number_format($DataRepriseGaranties[ValeurRepriseGTR], 2, ",", " " );  
			    $ValeurRepriseGTRBU  = $DataRepriseGaranties[ValeurRepriseGTR]; 
	   break;
		
			 
	   case 5  :$NbrRepriseGSH       = $DataRepriseGaranties[NbrRepriseGSH];  
	   		    $ValeurRepriseGSH    = number_format($DataRepriseGaranties[ValeurRepriseGSH], 2, ",", " " );  
			    $ValeurRepriseGSHBU  = $DataRepriseGaranties[ValeurRepriseGSH]; 
	   break;
		
			 
	   case 6  :$NbrRepriseGTE       = $DataRepriseGaranties[NbrRepriseGTE];  
	   			$ValeurRepriseGTE    = number_format($DataRepriseGaranties[ValeurRepriseGTE], 2, ",", " " );  
			    $ValeurRepriseGTEBU  = $DataRepriseGaranties[ValeurRepriseGTE]; 
	   break;
			
			 
	   case 7  :$NbrRepriseGLO       = $DataRepriseGaranties[NbrRepriseGLO];  
  	  			$ValeurRepriseGLO    = number_format($DataRepriseGaranties[ValeurRepriseGLO], 2, ",", " " );  
			    $ValeurRepriseGLOBU  = $DataRepriseGaranties[ValeurRepriseGLO]; 
	   break;
			
			 
	   case 8  :$NbrRepriseGLE       = $DataRepriseGaranties[NbrRepriseGLE]; 
     		    $ValeurRepriseGLE    = number_format($DataRepriseGaranties[ValeurRepriseGLE], 2, ",", " " );  
			    $ValeurRepriseGLEBU  = $DataRepriseGaranties[ValeurRepriseGLE]; 
	   break;
			
			 
	   case 9  :$NbrRepriseGHA       = $DataRepriseGaranties[NbrRepriseGHA];  
	  		    $ValeurRepriseGHA    = number_format($DataRepriseGaranties[ValeurRepriseGHA], 2, ",", " " );  
			    $ValeurRepriseGHABU  = $DataRepriseGaranties[ValeurRepriseGHA]; 
	   break;
		
			 
	   case 10 :$NbrRepriseGGR       = $DataRepriseGaranties[NbrRepriseGGR];  
	   		    $ValeurRepriseGGR    = number_format($DataRepriseGaranties[ValeurRepriseGGR], 2, ",", " " );  
			    $ValeurRepriseGGRBU  = $DataRepriseGaranties[ValeurRepriseGGR]; 
	   break;
		
			 			 
	   case 11 :$NbrRepriseGQC       = $DataRepriseGaranties[NbrRepriseGQC]; 
	            $ValeurRepriseGQC    = number_format($DataRepriseGaranties[ValeurRepriseGQC], 2, ",", " " );  
			    $ValeurRepriseGQCBU  = $DataRepriseGaranties[ValeurRepriseGQC]; 
	   break;
	   

	  // case 12 :$NbrRepriseGSMB       = $DataRepriseGaranties[NbrRepriseGSMB]; 
	   //         $ValeurRepriseGSMB    = number_format($DataRepriseGaranties[ValeurRepriseGSMB], 2, ",", " " );  
		//	    $ValeurRepriseGSMBBU  = $DataRepriseGaranties[ValeurRepriseGSMB]; 
	  // break;
	   
	   
	   case 12 :$NbrRepriseGMTL       = $DataRepriseGaranties[NbrRepriseGMTL]; 
	            $ValeurRepriseGMTL    = number_format($DataRepriseGaranties[ValeurRepriseGMTL], 2, ",", " " );  
			    $ValeurRepriseGMTLBU  = $DataRepriseGaranties[ValeurRepriseGMTL]; 
	   break;
   }//End Switch	
	$TotalReprisesGaranties   = $NbrRepriseGLV+$NbrRepriseGDR+$NbrRepriseGCH+$NbrRepriseGTR+$NbrRepriseGSH+$NbrRepriseGTE+ $NbrRepriseGLO+$NbrRepriseGLE+$NbrRepriseGHA+$NbrRepriseGGR+$NbrRepriseGQC+$NbrRepriseGMTL;//+$NbrRepriseGMTL
	$MoyenneReprisesGaranties = $TotalReprisesGaranties/$NombredeSuccursale;
	$MoyenneReprisesGaranties = round($MoyenneReprisesGaranties,2);
	//Calcul total et moyenne
	$SommeValeurReprisesGaranties =  $ValeurRepriseGLVBU + $ValeurRepriseGDRBU + $ValeurRepriseGCHBU + $ValeurRepriseGTRBU + $ValeurRepriseGSHBU + $ValeurRepriseGTEBU + 
	$ValeurRepriseGLOBU + $ValeurRepriseGLEBU + $ValeurRepriseGHABU + $ValeurRepriseGGRBU +  $ValeurRepriseGQCBU +$ValeurRepriseGMTLBU ;//+  $ValeurRepriseGSMBBU;
	$MoyenneValeurRepriseGaranties = $SommeValeurReprisesGaranties/$NombredeSuccursale;
	//Formatter les variables
	$MoyenneValeurRepriseGaranties = number_format($MoyenneValeurRepriseGaranties, 2, ",", " " );
	$SommeValeurReprisesGaranties  = number_format($SommeValeurReprisesGaranties, 2, ",", " " );
}//End FOR



//5- Calcul des pourcentages de reprise (SUR GARANTIES)
$PourcentageRepriseGLV = ($NbrRepriseGLV/$NbrEnvoyesLV)*100;
$PourcentageRepriseGLV = round($PourcentageRepriseGLV,2);

$PourcentageRepriseGDR = ($NbrRepriseGDR/$NbrEnvoyesDR)*100;
$PourcentageRepriseGDR = round($PourcentageRepriseGDR,2);

$PourcentageRepriseGCH = ($NbrRepriseGCH/$NbrEnvoyesCH)*100;
$PourcentageRepriseGCH = round($PourcentageRepriseGCH,2);

$PourcentageRepriseGTR = ($NbrRepriseGTR/$NbrEnvoyesTR)*100;
$PourcentageRepriseGTR = round($PourcentageRepriseGTR,2);

$PourcentageRepriseGSH = ($NbrRepriseGSH/$NbrEnvoyesSH)*100;
$PourcentageRepriseGSH = round($PourcentageRepriseGSH,2);

$PourcentageRepriseGTE = ($NbrRepriseGTE/$NbrEnvoyesTE)*100;
$PourcentageRepriseGTE = round($PourcentageRepriseGTE,2);

$PourcentageRepriseGLO = ($NbrRepriseGLO/$NbrEnvoyesLO)*100;
$PourcentageRepriseGLO = round($PourcentageRepriseGLO,2);

$PourcentageRepriseGLE = ($NbrRepriseGLE/$NbrEnvoyesLE)*100;
$PourcentageRepriseGLE = round($PourcentageRepriseGLE,2);

$PourcentageRepriseGHA = ($NbrRepriseGHA/$NbrEnvoyesHA)*100;
$PourcentageRepriseGHA = round($PourcentageRepriseGHA,2);
							  
$PourcentageRepriseGGR = ($NbrRepriseGGR/$NbrEnvoyesGR)*100;
$PourcentageRepriseGGR = round($PourcentageRepriseGGR,2);
							  							   
$PourcentageRepriseGQC = ($NbrRepriseGQC/$NbrEnvoyesQC)*100;
$PourcentageRepriseGQC = round($PourcentageRepriseGQC,2);

$PourcentageRepriseGMTL = ($NbrRepriseGMTL/$NbrEnvoyesMTL)*100;
$PourcentageRepriseGMTL = round($PourcentageRepriseGMTL,2);

$PourcentageRepriseGSMB = ($NbrRepriseGSMB/$NbrEnvoyesSMB)*100;
$PourcentageRepriseGSMB = round($PourcentageRepriseGSMB,2);

//Calcul Moyenne de Pourcentages de reprise
$MoyennePourcentageRepriseGaranties = ($PourcentageRepriseGLV + $PourcentageRepriseGDR + $PourcentageRepriseGCH + $PourcentageRepriseGTR + $PourcentageRepriseGSH +  $PourcentageRepriseGTE + 
$PourcentageRepriseGLO + $PourcentageRepriseGLE + $PourcentageRepriseGHA + $PourcentageRepriseGGR  + $PourcentageRepriseGQC 
 + $PourcentageRepriseGMTL)/$NombredeSuccursale;//Tmp: pour SMB  + $PourcentageRepriseGMTL
$MoyennePourcentageRepriseGaranties = round($MoyennePourcentageRepriseGaranties,2);



//1.6- Nombre de reprises DLAB
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 2: 
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')  
	   AND redo_order_num IS NOT NULL    AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 3:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 4:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') 
	   AND redo_order_num IS NOT NULL    AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')  
	   AND redo_order_num IS NOT NULL    AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
       $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 8:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'";  
       break;
		   
	   case 9:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe') 
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe') 
	   AND redo_order_num IS NOT NULL    AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   		   
	   case 11:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  
	   AND redo_order_num IS NOT NULL  AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   //case 12:
	   //$QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_SMB  FROM ORDERS WHERE user_id IN ('stemarie','stemariesafe')  
	   //AND redo_order_num IS NOT NULL  AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //break;

	   case 12:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_MTL  FROM ORDERS WHERE user_id IN ('montreal','montrealsafe')  
	   AND redo_order_num IS NOT NULL  AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
   }//End Switch
	
	
	$resultNbrRepriseDlab = mysqli_query($con,$QueryRepriseDLAB)		or die  ('I cannot select items because z9: ' . mysqli_error($con));
	$DataRepriseDLAB      = mysqli_fetch_array($resultNbrRepriseDlab,MYSQLI_ASSOC);
		
	 switch($x){ 
	   case 1  :$NbrRepriseDLAB_LV  = $DataRepriseDLAB[NbrRepriseDLAB_LV];  break;
	   case 2  :$NbrRepriseDLAB_DR  = $DataRepriseDLAB[NbrRepriseDLAB_DR];  break;	 
	   case 3  :$NbrRepriseDLAB_CH  = $DataRepriseDLAB[NbrRepriseDLAB_CH];  break;	 
	   case 4  :$NbrRepriseDLAB_TR  = $DataRepriseDLAB[NbrRepriseDLAB_TR];  break;
	   case 5  :$NbrRepriseDLAB_SH  = $DataRepriseDLAB[NbrRepriseDLAB_SH];  break;
       case 6  :$NbrRepriseDLAB_TE  = $DataRepriseDLAB[NbrRepriseDLAB_TE];  break;
	   case 7  :$NbrRepriseDLAB_LO  = $DataRepriseDLAB[NbrRepriseDLAB_LO];  break;
	   case 8  :$NbrRepriseDLAB_LE  = $DataRepriseDLAB[NbrRepriseDLAB_LE];  break;
	   case 9  :$NbrRepriseDLAB_HA  = $DataRepriseDLAB[NbrRepriseDLAB_HA];  break;
	   case 10 :$NbrRepriseDLAB_GR  = $DataRepriseDLAB[NbrRepriseDLAB_GR];  break;
	   case 11 :$NbrRepriseDLAB_QC  = $DataRepriseDLAB[NbrRepriseDLAB_QC];  break;
	   //case 12 :$NbrRepriseDLAB_SMB = $DataRepriseDLAB[NbrRepriseDLAB_SMB]; break;
	   case 12 :$NbrRepriseDLAB_MTL = $DataRepriseDLAB[NbrRepriseDLAB_MTL]; break;
	  
   }//End Switch	
	
	//Calcul du total et de la moyenne 
	$TotalReprisesDLAB = $NbrRepriseDLAB_LV + $NbrRepriseDLAB_DR + $NbrRepriseDLAB_CH + $NbrRepriseDLAB_TR + $NbrRepriseDLAB_SH + $NbrRepriseDLAB_TE + $NbrRepriseDLAB_LO +
	$NbrRepriseDLAB_LE + $NbrRepriseDLAB_HA + $NbrRepriseDLAB_GR + $NbrRepriseDLAB_QC  + $NbrRepriseDLAB_MTL;//+ $NbrRepriseDLAB_MTL
	$MoyenneRepriseDLAB   = $TotalReprisesDLAB/$NombredeSuccursale;
	$MoyenneRepriseDLAB   = round($MoyenneRepriseDLAB,2);
		
	//Calcul du pourcentage de reprise que DLAB represente parmis toutes les reprises de la succursale
	$PourcentageRepriseDLAB_LV = ($NbrRepriseDLAB_LV/$NbrRepriseLV)*100;
	$PourcentageRepriseDLAB_LV = round($PourcentageRepriseDLAB_LV,2);
	
	$PourcentageRepriseDLAB_DR = ($NbrRepriseDLAB_DR/$NbrRepriseDR)*100;
	$PourcentageRepriseDLAB_DR = round($PourcentageRepriseDLAB_DR,2);
	
	$PourcentageRepriseDLAB_CH = ($NbrRepriseDLAB_CH/$NbrRepriseCH)*100;
	$PourcentageRepriseDLAB_CH = round($PourcentageRepriseDLAB_CH,2);
	
	$PourcentageRepriseDLAB_TR = ($NbrRepriseDLAB_TR/$NbrRepriseTR)*100;
	$PourcentageRepriseDLAB_TR = round($PourcentageRepriseDLAB_TR,2);
	
	$PourcentageRepriseDLAB_SH = ($NbrRepriseDLAB_SH/$NbrRepriseSH)*100;
	$PourcentageRepriseDLAB_SH = round($PourcentageRepriseDLAB_SH,2);
	
	$PourcentageRepriseDLAB_TE = ($NbrRepriseDLAB_TE/$NbrRepriseTE)*100;
	$PourcentageRepriseDLAB_TE = round($PourcentageRepriseDLAB_TE,2);
	
	$PourcentageRepriseDLAB_LO = ($NbrRepriseDLAB_LO/$NbrRepriseLO)*100;
	$PourcentageRepriseDLAB_LO = round($PourcentageRepriseDLAB_LO,2);
	
	$PourcentageRepriseDLAB_LE = ($NbrRepriseDLAB_LE/$NbrRepriseLE)*100;
	$PourcentageRepriseDLAB_LE = round($PourcentageRepriseDLAB_LE,2);
	
	$PourcentageRepriseDLAB_HA = ($NbrRepriseDLAB_HA/$NbrRepriseHA)*100;
	$PourcentageRepriseDLAB_HA = round($PourcentageRepriseDLAB_HA,2);
	
	$PourcentageRepriseDLAB_GR = ($NbrRepriseDLAB_GR/$NbrRepriseGR)*100;
	$PourcentageRepriseDLAB_GR = round($PourcentageRepriseDLAB_GR,2);
		
	$PourcentageRepriseDLAB_QC = ($NbrRepriseDLAB_QC/$NbrRepriseQC)*100;
	$PourcentageRepriseDLAB_QC = round($PourcentageRepriseDLAB_QC,2);
	
	$PourcentageRepriseDLAB_MTL = ($NbrRepriseDLAB_MTL/$NbrRepriseMTL)*100;
	$PourcentageRepriseDLAB_MTL = round($PourcentageRepriseDLAB_MTL,2);
	
	$PourcentageRepriseDLAB_SMB = ($NbrRepriseDLAB_SMB/$NbrRepriseSMB)*100;
	$PourcentageRepriseDLAB_SMB = round($PourcentageRepriseDLAB_SMB,2);
		
}//End FOR





//1.99- Calcul du montant déja crédité à chaque magasin
	for ($x = 1; $x <= $NombredeSuccursale; $x++) {
		switch($x){
			case 1: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_LV    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('laval','lavalsafe')   AND mcred_date BETWEEN '$date1' and '$date2'"; 		  		break;
			case 2: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_DR    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('entrepotdr','safedr') AND mcred_date BETWEEN '$date1' and '$date2'"; 		  		break;
			case 3: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_CH    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('chicoutimi','chicoutimisafe') AND mcred_date BETWEEN '$date1' and '$date2'"; 		break;
			case 4: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_TR    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('entrepotifc','entrepotsafe') AND mcred_date BETWEEN '$date1' and '$date2'";  		break; 
			case 5: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_SH    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('sherbrooke','sherbrookesafe') AND mcred_date BETWEEN '$date1' and '$date2'"; 		break;
			case 6: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_TE    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('terrebonne','terrebonnesafe') AND mcred_date BETWEEN '$date1' and '$date2'"; 		break;
			case 7: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_LO    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('longueuil','longueuilsafe')  AND mcred_date BETWEEN '$date1' and '$date2'";  		break;  
			case 8: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_LE    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('levis','levissafe') AND mcred_date BETWEEN '$date1' and '$date2'";        	   	break;
			case 9: $QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_HA    FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('warehousehal','warehousehalsafe')  AND mcred_date BETWEEN '$date1' and '$date2'"; break;
			case 10:$QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_GR   FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('granby','granbysafe')  AND mcred_date BETWEEN '$date1' and '$date2'"; 	     		break;
			case 11:$QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_QC   FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('entrepotquebec','quebecsafe')  AND mcred_date BETWEEN '$date1' and '$date2'"; 		break;
			//case 12:$QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_SMB  FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('stemarie','stemariesafe') AND mcred_date BETWEEN '$date1' and '$date2'"; 			break;
			case 12:$QueryMontantTotalCrediter = "SELECT sum(mcred_abs_amount) as Montant_Total_Credit_MTL  FROM MEMO_CREDITS WHERE mcred_acct_user_id IN ('montreal','montrealsafe') AND mcred_date BETWEEN '$date1' and '$date2'"; 			break;
			 }//End Switch
		
		$resultMontantTotalCrediter = mysqli_query($con,$QueryMontantTotalCrediter)		or die  ('I cannot select items because w7: ' . mysqli_error($con));
		$DataMontantTotalCrediter   = mysqli_fetch_array($resultMontantTotalCrediter,MYSQLI_ASSOC);
			
		 switch($x){ 
			 case 1 :$Montant_Total_Crediter_LV  = $DataMontantTotalCrediter[Montant_Total_Credit_LV];   break;
			 case 2 :$Montant_Total_Crediter_DR  = $DataMontantTotalCrediter[Montant_Total_Credit_DR];   break; 
			 case 3 :$Montant_Total_Crediter_CH  = $DataMontantTotalCrediter[Montant_Total_Credit_CH];   break;
			 case 4 :$Montant_Total_Crediter_TR  = $DataMontantTotalCrediter[Montant_Total_Credit_TR];   break;
			 case 5 :$Montant_Total_Crediter_SH  = $DataMontantTotalCrediter[Montant_Total_Credit_SH];   break;
			 case 6 :$Montant_Total_Crediter_TE  = $DataMontantTotalCrediter[Montant_Total_Credit_TE];   break;
			 case 7 :$Montant_Total_Crediter_LO  = $DataMontantTotalCrediter[Montant_Total_Credit_LO];   break;
			 case 8 :$Montant_Total_Crediter_LE  = $DataMontantTotalCrediter[Montant_Total_Credit_LE];   break;
			 case 9 :$Montant_Total_Crediter_HA  = $DataMontantTotalCrediter[Montant_Total_Credit_HA];   break;
			 case 10:$Montant_Total_Crediter_GR  = $DataMontantTotalCrediter[Montant_Total_Credit_GR];   break;
			 case 11:$Montant_Total_Crediter_QC  = $DataMontantTotalCrediter[Montant_Total_Credit_QC];   break;
			 //case 12:$Montant_Total_Crediter_SMB = $DataMontantTotalCrediter[Montant_Total_Credit_SMB];  break;
			 case 12:$Montant_Total_Crediter_MTL = $DataMontantTotalCrediter[Montant_Total_Credit_MTL];  break;
		 }//End Switch	
		
		
	}//End FOR 

	//Calculer Total et moyenne
	$TOTAL_CREDITER_EDLL = $Montant_Total_Crediter_LV + $Montant_Total_Crediter_DR + $Montant_Total_Crediter_CH + $Montant_Total_Crediter_TR + $Montant_Total_Crediter_SH + $Montant_Total_Crediter_TE 
		+ $Montant_Total_Crediter_LO +	$Montant_Total_Crediter_LE + $Montant_Total_Crediter_HA + $Montant_Total_Crediter_GR + $Montant_Total_Crediter_QC  + $Montant_Total_Crediter_MTL;//+ $Montant_Total_Crediter_MTL	
	$MOYENNE_CREDITEE_PAR_MAGASIN = $TOTAL_CREDITER_EDLL/$NombredeSuccursale;
	
	//Formatter les variables
	$TOTAL_CREDITER_EDLL 		= number_format($TOTAL_CREDITER_EDLL, 2, ",", " " );
	$MOYENNE_CREDITEE_PAR_MAGASIN= number_format($MOYENNE_CREDITEE_PAR_MAGASIN, 2, ",", " " );
	$Montant_Total_Crediter_LV  = number_format($Montant_Total_Crediter_LV,2,","," ");   
	$Montant_Total_Crediter_DR  = number_format($Montant_Total_Crediter_DR,2,","," ");     
	$Montant_Total_Crediter_CH  = number_format($Montant_Total_Crediter_CH,2,","," ");   
	$Montant_Total_Crediter_TR  = number_format($Montant_Total_Crediter_TR,2,","," ");   
	$Montant_Total_Crediter_SH  = number_format($Montant_Total_Crediter_SH,2,","," ");  
	$Montant_Total_Crediter_TE  = number_format($Montant_Total_Crediter_TE,2,","," ");     
	$Montant_Total_Crediter_LO  = number_format($Montant_Total_Crediter_LO,2,","," ");      
	$Montant_Total_Crediter_LE  = number_format($Montant_Total_Crediter_LE,2,","," ");    
	$Montant_Total_Crediter_HA  = number_format($Montant_Total_Crediter_HA,2,","," ");    
	$Montant_Total_Crediter_GR  = number_format($Montant_Total_Crediter_GR,2,","," ");    
	$Montant_Total_Crediter_QC  = number_format($Montant_Total_Crediter_QC,2,","," ");     
	$Montant_Total_Crediter_MTL = number_format($Montant_Total_Crediter_MTL,2,","," ");   
	$Montant_Total_Crediter_SMB = number_format($Montant_Total_Crediter_SMB,2,","," ");   
//Fin Crédit Déja émis aux EDLLs


//2.01 Total des Achats [Intercos] de chaque magasin EDLL
	for ($x = 1; $x <= $NombredeSuccursale; $x++) {
		switch($x){
			case 1: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_LV  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('laval','lavalsafe')"; 		  		break;
			case 2: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_DR  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('entrepotdr','safedr')"; 			break;
			case 3: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_CH  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('chicoutimi','chicoutimisafe')"; 	break;
			case 4: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_TR  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('entrepotifc','entrepotsafe')";  	break; 
			case 5: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_SH  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('sherbrooke','sherbrookesafe')"; 	break;
			case 6: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_TE  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('terrebonne','terrebonnesafe')"; 	break;
			case 7: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_LO  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('longueuil','longueuilsafe')";  		break;  
			case 8: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_LE  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('levis','levissafe')";        	   	break;
			case 9: $QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_HA  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('warehousehal','warehousehalsafe')"; break;
			case 10:$QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_GR  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('granby','granbysafe')"; 	     	break;
			case 11:$QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_QC  FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('entrepotquebec','quebecsafe')"; 	break;
			
			//case 12:$QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_SMB FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('stemarie','stemariesafe')"; 		break;
			case 12:$QueryAchatIntercos = "SELECT sum(order_total) as Achat_Interco_MTL FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN ('montreal','montrealsafe')"; 		break;
	   }//End Switch
		
		
		$resultAchatIntercos = mysqli_query($con,$QueryAchatIntercos)		or die  ('I cannot select items because f4: ' . mysqli_error($con));
		$DataAchatIntercos   = mysqli_fetch_array($resultAchatIntercos,MYSQLI_ASSOC);
			
		 switch($x){ 
			 case 1 :$Achat_Interco_LV  = $DataAchatIntercos[Achat_Interco_LV];   break;
			 case 2 :$Achat_Interco_DR  = $DataAchatIntercos[Achat_Interco_DR];   break; 
			 case 3 :$Achat_Interco_CH  = $DataAchatIntercos[Achat_Interco_CH];   break;
			 case 4 :$Achat_Interco_TR  = $DataAchatIntercos[Achat_Interco_TR];   break;
			 case 5 :$Achat_Interco_SH  = $DataAchatIntercos[Achat_Interco_SH];   break;
			 case 6 :$Achat_Interco_TE  = $DataAchatIntercos[Achat_Interco_TE];   break;
			 case 7 :$Achat_Interco_LO  = $DataAchatIntercos[Achat_Interco_LO];   break;
			 case 8 :$Achat_Interco_LE  = $DataAchatIntercos[Achat_Interco_LE];   break;
			 case 9 :$Achat_Interco_HA  = $DataAchatIntercos[Achat_Interco_HA];   break;
			 case 10:$Achat_Interco_GR  = $DataAchatIntercos[Achat_Interco_GR];   break;
			 case 11:$Achat_Interco_QC  = $DataAchatIntercos[Achat_Interco_QC];   break;
			
			 //case 12:$Achat_Interco_SMB = $DataAchatIntercos[Achat_Interco_SMB];  break;
			 case 12:$Achat_Interco_MTL = $DataAchatIntercos[Achat_Interco_MTL];  break;
		 }//End Switch	
		
		
	}//End FOR 

	//Calcul du total et de la moyenne
	$TOTAL_ACHAT_INTERCOS = $Achat_Interco_LV + $Achat_Interco_DR + $Achat_Interco_CH + $Achat_Interco_TR + $Achat_Interco_SH + $Achat_Interco_TE + $Achat_Interco_LO +
	$Achat_Interco_LE + $Achat_Interco_HA + $Achat_Interco_GR + $Achat_Interco_QC + $Achat_Interco_MTL;//+ $Achat_Interco_MTL 
	$MOYENNE_ACHAT_INTERCOS = $TOTAL_ACHAT_INTERCOS/$NombredeSuccursale;

	//Formatter les variables	
	$TOTAL_ACHAT_INTERCOS   = number_format($TOTAL_ACHAT_INTERCOS,2,","," ");
	$MOYENNE_ACHAT_INTERCOS = number_format($MOYENNE_ACHAT_INTERCOS,2,","," ");
	$Achat_Interco_LV  = number_format($Achat_Interco_LV,2,","," ");
	$Achat_Interco_DR  = number_format($Achat_Interco_DR,2,","," ");
	$Achat_Interco_CH  = number_format($Achat_Interco_CH,2,","," ");
	$Achat_Interco_TR  = number_format($Achat_Interco_TR,2,","," ");
	$Achat_Interco_SH  = number_format($Achat_Interco_SH,2,","," ");
	$Achat_Interco_TE  = number_format($Achat_Interco_TE,2,","," ");
	$Achat_Interco_LO  = number_format($Achat_Interco_LO,2,","," ");
	$Achat_Interco_LE  = number_format($Achat_Interco_LE,2,","," ");
	$Achat_Interco_HA  = number_format($Achat_Interco_HA,2,","," ");
	$Achat_Interco_GR  = number_format($Achat_Interco_GR,2,","," ");
	$Achat_Interco_QC  = number_format($Achat_Interco_QC,2,","," ");
	$Achat_Interco_MTL = number_format($Achat_Interco_MTL,2,","," ");
	$Achat_Interco_SMB = number_format($Achat_Interco_SMB,2,","," ");
//Fin Crédit Déja émis aux EDLLs


//Calcul Moyenne
$MoyennePourcentageRepriseDLAB = ($PourcentageRepriseDLAB_LV + $PourcentageRepriseDLAB_DR + $PourcentageRepriseDLAB_CH + $PourcentageRepriseDLAB_TR + $PourcentageRepriseDLAB_SH + $PourcentageRepriseDLAB_TE + 
						$PourcentageRepriseDLAB_LO + $PourcentageRepriseDLAB_LE + $PourcentageRepriseDLAB_HA + $PourcentageRepriseDLAB_GR
						+ $PourcentageRepriseDLAB_QC  + $PourcentageRepriseDLAB_MTL   )/$NombredeSuccursale;//Tmp pour SMB; + $PourcentageRepriseDLAB_MTL 
$MoyennePourcentageRepriseDLAB = round($MoyennePourcentageRepriseDLAB,2);
//Calcul Total et Moyenne
$TotalValeurReprises  = $ValeurRepriseLVBU + $ValeurRepriseDRBU + $ValeurRepriseCHBU + $ValeurRepriseTRBU + $ValeurRepriseSHBU + $ValeurRepriseTEBU + $ValeurRepriseLOBU + 
							$ValeurRepriseLEBU + $ValeurRepriseHABU + $ValeurRepriseGRBU + $ValeurRepriseQCBU + $ValeurRepriseMTLBU;// + $ValeurRepriseMTLBU
$MoyenneValeurReprise = $TotalValeurReprises/$NombredeSuccursale;
//Formatter les variables
$MoyenneValeurReprise = number_format($MoyenneValeurReprise, 2, ",", " " );
$TotalValeurReprises  = number_format($TotalValeurReprises, 2, ",", " " );


//Définire la palette de couleur:
$Couleur_PETAL		 	="#F98866";//Reprises Garanties(Nombre, % reprise, valeur$$)
$Couleur_POPPY		 	="#FF420E";
$Couleur_STEM		 	="#80BD9E";
$Couleur_SPRINGGREEN 	="#89DA59";//Total de vente, $ Achats Intercos
$Couleur_JAUNATRE 		="#fbf579";//Nombre de reprise, % de reprise, valeur net des reprises.
$Couleur_BLEU_PALE		="#569DBD";//Bleuté

//Preparer le courriel 

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

		$message.="<body><table width=\"1325\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="
				<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"12\">P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th bgcolor=\"#E3E2DF\" width=\"25\">A</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\">B</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\">C</th>
					<th bgcolor=\"$Couleur_POPPY\">D</th>
					<th bgcolor=\"$Couleur_JAUNATRE\">E</th>
					<th bgcolor=\"$Couleur_JAUNATRE\">F</th>	
					
					<th bgcolor=\"$Couleur_JAUNATRE\">G</th>
					<th bgcolor=\"$Couleur_PETAL\">H</th>
					<th bgcolor=\"$Couleur_PETAL\">I</th>
					<th bgcolor=\"$Couleur_PETAL\">J</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">K</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">L</th>
				</tr>
				
				
				<tr>
					<th bgcolor=\"#E3E2DF\" width=\"25\">Magasins</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\"></b>Commande Valid&eacute;es </th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\">Achats</th>
					<th bgcolor=\"$Couleur_POPPY\">Cr&eacute;dits</th>
					<th bgcolor=\"$Couleur_JAUNATRE\">Total </th>
					<th bgcolor=\"$Couleur_JAUNATRE\">%</th>		
					
					<th bgcolor=\"$Couleur_JAUNATRE\">Valeur net</th>
					<th bgcolor=\"$Couleur_PETAL\">Total de reprise</th>
					<th bgcolor=\"$Couleur_PETAL\">% Reprise</th>
					<th bgcolor=\"$Couleur_PETAL\">Valeur net des reprises </th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">Total de</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">% Reprise Dlab</th>
				</tr>
				
				<tr>
					<th bgcolor=\"#E3E2DF\"  width=\"25\"></th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\">(Incluant reprises)</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\">Interco</th>
					<th bgcolor=\"$Couleur_POPPY\">D&eacute;ja &eacute;mis</th>
					<th bgcolor=\"$Couleur_JAUNATRE\"> de reprise</th>
					<th bgcolor=\"$Couleur_JAUNATRE\">de reprise</th>
					
					<th bgcolor=\"$Couleur_JAUNATRE\"> des reprises</th>
					<th bgcolor=\"$Couleur_PETAL\">(Garanties)</th>
					<th bgcolor=\"$Couleur_PETAL\">(Garanties)</th>
					<th bgcolor=\"$Couleur_PETAL\">(Garanties)</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">reprise Dlab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">(K / E)</th>
				</tr>";
		
		
		
		  //Partie e)Sherbrooke
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Sherbrooke</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesSH</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_SH$</td>
					<td bgcolor=\"$Couleur_POPPY\" align=\"center\">$Montant_Total_Crediter_SH$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseSH</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseSH%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseSH$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGSH</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGSH%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGSH$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_SH</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_SH%</td>
				</tr>";
				
			
		//Partie d)Trois-Rivieres
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Trois-Rivi&egrave;res</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesTR</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_TR$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_TR$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseTR</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseTR%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseTR$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGTR</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGTR%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGTR$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_TR</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_TR%</td>
				</tr>";		
				
				
		//Partie f)Terrebonne
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Terrebonne</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesTE</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_TE$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_TE$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseTE</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseTE%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseTE$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGTE</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGTE%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGTE$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_TE</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_TE%</td>
				</tr>";	

				
	
		
		 //Partie h)Levis
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">L&eacute;vis</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesLE</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_LE$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_LE$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseLE</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseLE%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseLE$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGLE</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGLE%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGLE$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_LE</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_LE%</td>
				</tr>";	
	
		
		//Partie c)Chicoutimi
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Chicoutimi</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesCH</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_CH$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_CH$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseCH</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseCH%</td>		
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseCH$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGCH</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGCH%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGCH$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_CH</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_CH%</td>
				</tr>";
		
		
				
		//Partie j)Granby
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Granby</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesGR</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_GR$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_GR$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseGR</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseGR%</td>	
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseGR$</td>

					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGGR</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGGR%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGGR$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_GR</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_GR%</td>
				</tr>";		
				
	
		
		//Partie i)Halifax
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Halifax</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesHA</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_HA$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_HA$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseHA</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseHA%</td>			
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseHA$</td>	
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGHA</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGHA%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGHA$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_HA</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_HA%</td>
				</tr>";	

	
	
		 //Partie g)Longueuil
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Longueuil</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesLO</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_LO$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_LO$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseLO</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseLO%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseLO$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGLO</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGLO%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGLO$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_LO</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_LO%</td>
				</tr>";	

				
	 //Partie a)Laval
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Laval</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesLV</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_LV$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_LV$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseLV</td>	
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseLV%</td>
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseLV$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGLV</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGLV%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGLV$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_LV</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_LV%</td>
				</tr>";
				
			
							
		//Partie b)Drummondville
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Drummondville</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesDR</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_DR$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_DR$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseDR</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseDR%</td>	
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseDR$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGDR</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGDR%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGDR$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_DR</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_DR%</td>
				</tr>";

				
	
		//Partie k)Quebec
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Qu&eacute;bec</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesQC</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_QC$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_QC$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseQC</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseQC%</td>		
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseQC$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGQC</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGQC%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGQC$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_QC</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_QC%</td>
				</tr>";	

				
	
	
				
	
		

				
	 //Partie l)Montréal Zone Tendance 1 [HBC Sainte-Catherine]
			$message.="	
				<tr>
					<th bgcolor=\"#E3E2DF\">Montr&eacute;al</th>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$NbrEnvoyesMTL</td>
					<td bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$Achat_Interco_MTL$</td>
					<td bgcolor=\"$Couleur_POPPY\"  align=\"center\">$Montant_Total_Crediter_MTL$</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$NbrRepriseMTL</td>
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$PourcentageRepriseMTL%</td>		
					
					<td bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$ValeurRepriseMTL$</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$NbrRepriseGMTL</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$PourcentageRepriseGMTL%</td>
					<td bgcolor=\"$Couleur_PETAL\" align=\"center\">$ValeurRepriseGMTL$</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$NbrRepriseDLAB_MTL</td>
					<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$PourcentageRepriseDLAB_MTL%</td>
				</tr>";	
					
		
		
 //Partie M)TOTAUX
			$message.="	<tr>
					<td colspan=\"12\"></td>
				</tr>
				<tr  bgcolor=\"#B4AEAE\">
					<th>Totaux</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$TotalCommandesEnvoyees commandes</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" align=\"center\">$TOTAL_ACHAT_INTERCOS$</th>
					<th bgcolor=\"$Couleur_POPPY\"  align=\"center\">$TOTAL_CREDITER_EDLL$</th>
					<th bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$TotalReprises reprises</th>
					<th bgcolor=\"$Couleur_JAUNATRE\">-</th>	
					
					<th bgcolor=\"$Couleur_JAUNATRE\" align=\"center\">$TotalValeurReprises$</th>
					<th bgcolor=\"$Couleur_PETAL\" align=\"center\">$TotalReprisesGaranties reprises</th>
					<th bgcolor=\"$Couleur_PETAL\">-</th>
					<th bgcolor=\"$Couleur_PETAL\" align=\"center\">$SommeValeurReprisesGaranties$</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\">$TotalReprisesDLAB reprises</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\">-</th>
				</tr>";		

$MoyenneRepriseParSuccursale =$TotalReprises/$NombredeSuccursale;
$MoyenneRepriseParSuccursale = round($MoyenneRepriseParSuccursale,2);

$MoyenneNombreCommandesValidees =$TotalCommandesEnvoyees/$NombredeSuccursale;
$MoyenneNombreCommandesValidees = round($MoyenneNombreCommandesValidees,2);
//Partie n)MOYENNES
	$message.="	
				
				<tr>
					<td colspan=\"12\"></td>
				</tr>
				<tr>
					<td colspan=\"12\"></td>
				</tr>
				<tr bgcolor=\"#3FEEE6\">
					<th align=\"center\">Moyennes</th>
					<td align=\"center\">$MoyenneNombreCommandesValidees</td>
					<td align=\"center\">$MOYENNE_ACHAT_INTERCOS$</td>
					<td align=\"center\">$MOYENNE_CREDITEE_PAR_MAGASIN$</td>
					<td align=\"center\">$MoyenneRepriseParSuccursale</td>
					<td align=\"center\">$MoyennePourcentageReprise%</td>
					
					<td align=\"center\">$MoyenneValeurReprise$</td>	
					<td align=\"center\">$MoyenneReprisesGaranties</td>
					<td align=\"center\">$MoyennePourcentageRepriseGaranties%</td>
					<td align=\"center\">$MoyenneValeurRepriseGaranties$</td>
					<td align=\"center\">$MoyenneRepriseDLAB</td>
					<td align=\"center\">$MoyennePourcentageRepriseDLAB%</td>
				</tr>
				</table>";	
*/










//PARTIE 2: SOMMAIRE CHARLES (Séparé par FOURNISSEURS)

//TODO: Ajouter le nombre total de commande envoyée vers chaque fournisseur.

/* Dans cet ordre:
a)Directlab STC
b)Swiss
c)Central Lab
d)Essilor Lab #1
e)Tous les autres
*/

//Recueillir les donnees
//REPRISE PAR FOURNISSEUR: DLAB

/*
$lab = 3;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $user_id = "  user_ID in ('laval','lavalsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_LV, sum(order_total) as ValeurRepriseDLAB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_LV, sum(order_total) as ValeurRepriseDLAB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_LV, sum(order_total) as ValeurRepriseDLAB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   
	   case 2:
	   $user_id = "  user_ID in ('entrepotdr','safedr')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_DR, sum(order_total) as ValeurRepriseDLAB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_DR, sum(order_total) as ValeurRepriseDLAB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_DR, sum(order_total) as ValeurRepriseDLAB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   
	   
	   case 3:
	   $user_id = "  user_ID in ('chicoutimi','chicoutimisafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_CH, sum(order_total) as ValeurRepriseDLAB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_CH, sum(order_total) as ValeurRepriseDLAB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_CH, sum(order_total) as ValeurRepriseDLAB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 4:
	   $user_id = "  user_ID in ('entrepotifc','entrepotsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_TR, sum(order_total) as ValeurRepriseDLAB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_TR, sum(order_total) as ValeurRepriseDLAB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_TR, sum(order_total) as ValeurRepriseDLAB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		   
	   case 5:
	   $user_id = "  user_ID in ('sherbrooke','sherbrookesafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_SH, sum(order_total) as ValeurRepriseDLAB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_SH, sum(order_total) as ValeurRepriseDLAB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_SH, sum(order_total) as ValeurRepriseDLAB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	  
	  
	   case 6:
	   $user_id = "  user_ID in ('terrebonne','terrebonnesafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_TE, sum(order_total) as ValeurRepriseDLAB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_TE, sum(order_total) as ValeurRepriseDLAB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_TE, sum(order_total) as ValeurRepriseDLAB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		     
	   case 7:
	   $user_id = "  user_ID in ('longueuil','longueuilsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_LO, sum(order_total) as ValeurRepriseDLAB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_LO, sum(order_total) as ValeurRepriseDLAB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_LO, sum(order_total) as ValeurRepriseDLAB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 8:
	   $user_id = "  user_ID in ('levis','levissafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_LE, sum(order_total) as ValeurRepriseDLAB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_LE, sum(order_total) as ValeurRepriseDLAB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_LE, sum(order_total) as ValeurRepriseDLAB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
	   case 9:
	   $user_id = "  user_ID in ('warehousehal','warehousehalsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_HA, sum(order_total) as ValeurRepriseDLAB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_HA, sum(order_total) as ValeurRepriseDLAB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_HA, sum(order_total) as ValeurRepriseDLAB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
		   
	   case 10:
	    $user_id = "  user_ID in ('granby','granbysafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_GR, sum(order_total) as ValeurRepriseDLAB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_GR, sum(order_total) as ValeurRepriseDLAB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_GR, sum(order_total) as ValeurRepriseDLAB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $user_id = "  user_ID in ('entrepotquebec','quebecsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_QC, sum(order_total) as ValeurRepriseDLAB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_QC, sum(order_total) as ValeurRepriseDLAB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_QC, sum(order_total) as ValeurRepriseDLAB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 12:
	   $user_id = "  user_ID in ('montreal','montrealsafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_MTL, sum(order_total) as ValeurRepriseDLAB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_MTL, sum(order_total) as ValeurRepriseDLAB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_MTL, sum(order_total) as ValeurRepriseDLAB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   
	   case 13:
	   $user_id = "  user_ID in ('stemarie','stemariesafe')";
	   //Garanties
	   $QueryRepriseDLAB_G = "SELECT count(order_num) as NbrRepriseDLAB_SMB, sum(order_total) as ValeurRepriseDLAB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_SMB, sum(order_total) as ValeurRepriseDLAB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseDLAB_LAB= "SELECT count(order_num) as NbrRepriseDLAB_SMB, sum(order_total) as ValeurRepriseDLAB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseDLAB     = mysqli_query($con,$QueryRepriseDLAB_G)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseDLAB_G        = mysqli_fetch_array($resultNbrRepriseDLAB);
		
	
	//Hors Garanties
	$resultNbrRepriseDLAB_HG  = mysqli_query($con,$QueryRepriseDLAB_HG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseDLAB_HG       = mysqli_fetch_array($resultNbrRepriseDLAB_HG);	
	
	//Lab
	$resultNbrRepriseDLAB_LAB = mysqli_query($con,$QueryRepriseDLAB_LAB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseDLAB_LAB      = mysqli_fetch_array($resultNbrRepriseDLAB_LAB);	
	
 switch($x){ 
	   case 1://Laval
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_LV_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_LV]; 
	   $ValeurRepriseDLAB_LV_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_LV];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_LV_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_LV]; 
	   $ValeurRepriseDLAB_LV_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_LV];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_LV_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_LV];  
	   $ValeurRepriseDLAB_LV_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_LV];
	   break;
		 
		 
	   case 2://Drummondville
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_DR_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_DR]; 
	   $ValeurRepriseDLAB_DR_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_DR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_DR_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_DR]; 
	   $ValeurRepriseDLAB_DR_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_DR];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_DR_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_DR];  
	   $ValeurRepriseDLAB_DR_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_DR];
	   break;	 
		 
	   case 3://Chicoutimi
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_CH_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_CH]; 
	   $ValeurRepriseDLAB_CH_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_CH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_CH_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_CH]; 
	   $ValeurRepriseDLAB_CH_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_CH];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_CH_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_CH];  
	   $ValeurRepriseDLAB_CH_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_CH];
	   break; 
		 
	   case 4://Trois-Rivièeres
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_TR_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_TR]; 
	   $ValeurRepriseDLAB_TR_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_TR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_TR_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_TR]; 
	   $ValeurRepriseDLAB_TR_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_TR];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_TR_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_TR];  
	   $ValeurRepriseDLAB_TR_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_TR];
	   break;
		 
	   case 5://Sherbrooke
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_SH_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_SH]; 
	   $ValeurRepriseDLAB_SH_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_SH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_SH_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_SH]; 
	   $ValeurRepriseDLAB_SH_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_SH];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_SH_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_SH];  
	   $ValeurRepriseDLAB_SH_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_SH];
	   break;
		 
       case 6://Terrebonne
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_TE_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_TE]; 
	   $ValeurRepriseDLAB_TE_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_TE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_TE_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_TE]; 
	   $ValeurRepriseDLAB_TE_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_TE];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_TE_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_TE];  
	   $ValeurRepriseDLAB_TE_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_TE];
	   break;
		 
	   case 7://Longueuil
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_LO_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_LO]; 
	   $ValeurRepriseDLAB_LO_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_LO];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_LO_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_LO]; 
	   $ValeurRepriseDLAB_LO_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_LO];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_LO_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_LO];  
	   $ValeurRepriseDLAB_LO_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_LO];	
	   break;
		 
	   case 8://Lévis
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_LE_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_LE]; 
	   $ValeurRepriseDLAB_LE_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_LE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_LE_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_LE]; 
	   $ValeurRepriseDLAB_LE_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_LE];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_LE_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_LE];  
	   $ValeurRepriseDLAB_LE_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_LE];	
	   break;
	 
	   case 9://Halifax
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_HA_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_HA]; 
	   $ValeurRepriseDLAB_HA_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_HA];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_HA_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_HA]; 
	   $ValeurRepriseDLAB_HA_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_HA];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_HA_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_HA];  
	   $ValeurRepriseDLAB_HA_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_HA];	
	   break;
		 
	   case 10://Granby
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_GR_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_GR]; 
	   $ValeurRepriseDLAB_GR_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_GR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_GR_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_GR]; 
	   $ValeurRepriseDLAB_GR_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_GR];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_GR_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_GR];  
	   $ValeurRepriseDLAB_GR_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_GR];		
	   break;
		 
	   case 11://Québec
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_QC_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_QC]; 
	   $ValeurRepriseDLAB_QC_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_QC];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_QC_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_QC]; 
	   $ValeurRepriseDLAB_QC_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_QC];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_QC_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_QC];  
	   $ValeurRepriseDLAB_QC_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_QC];		
	   break;
	   
	   case 12://Montréal
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_MTL_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_MTL]; 
	   $ValeurRepriseDLAB_MTL_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_MTL];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_MTL_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_MTL]; 
	   $ValeurRepriseDLAB_MTL_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_MTL];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_MTL_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_MTL];  
	   $ValeurRepriseDLAB_MTL_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_MTL];		
	   break;
	   
	   case 13://Sainte-Marie
	   //Reprises 'Garanties'
	   $NbrRepriseDLAB_SMB_G   	 	= $DataRepriseDLAB_G[NbrRepriseDLAB_SMB]; 
	   $ValeurRepriseDLAB_SMB_G	 	= $DataRepriseDLAB_G[ValeurRepriseDLAB_SMB];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseDLAB_SMB_HG 	 	= $DataRepriseDLAB_HG[NbrRepriseDLAB_SMB]; 
	   $ValeurRepriseDLAB_SMB_HG  	= $DataRepriseDLAB_HG[ValeurRepriseDLAB_SMB];
	   //Reprises 'Lab'
	   $NbrRepriseDLAB_SMB_LAB 		= $DataRepriseDLAB_LAB[NbrRepriseDLAB_SMB];  
	   $ValeurRepriseDLAB_SMB_LAB	= $DataRepriseDLAB_LAB[ValeurRepriseDLAB_SMB];		
	   break;
   }//End Switch	
	
}//end FOR


//GARANTIES: 1-Calcul du total de reprises 
$NbrRepriseDLAB_G = $NbrRepriseDLAB_LV_G + $NbrRepriseDLAB_DR_G + $NbrRepriseDLAB_CH_G + $NbrRepriseDLAB_TE_G + $NbrRepriseDLAB_TR_G + $NbrRepriseDLAB_SH_G + $NbrRepriseDLAB_LE_G 
+ $NbrRepriseDLAB_HA_G + $NbrRepriseDLAB_LO_G + $NbrRepriseDLAB_GR_G + $NbrRepriseDLAB_QC_G+ $NbrRepriseDLAB_MTL_G  + $NbrRepriseDLAB_SMB_G;
//GARANTIES: 1.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseDLAB_G = $ValeurRepriseDLAB_LV_G + $ValeurRepriseDLAB_DR_G + $ValeurRepriseDLAB_CH_G + $ValeurRepriseDLAB_TE_G + $ValeurRepriseDLAB_TR_G + $ValeurRepriseDLAB_SH_G + $ValeurRepriseDLAB_LE_G 
+ $ValeurRepriseDLAB_HA_G + $ValeurRepriseDLAB_LO_G + $ValeurRepriseDLAB_GR_G + $ValeurRepriseDLAB_QC_G+ $ValeurRepriseDLAB_MTL_G  + $ValeurRepriseDLAB_SMB_G;

//LAB: 2-Calcul du total de reprises 
$NbrRepriseDLAB_LAB = $NbrRepriseDLAB_LV_LAB + $NbrRepriseDLAB_DR_LAB + $NbrRepriseDLAB_CH_LAB + $NbrRepriseDLAB_TE_LAB + $NbrRepriseDLAB_TR_LAB + $NbrRepriseDLAB_SH_LAB + $NbrRepriseDLAB_LE_LAB 
+ $NbrRepriseDLAB_HA_LAB + $NbrRepriseDLAB_LO_LAB + $NbrRepriseDLAB_GR_LAB + $NbrRepriseDLAB_QC_LAB + $NbrRepriseDLAB_MTL_LAB + $NbrRepriseDLAB_SMB_LAB;
//LAB 2.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseDLAB_LAB  = $ValeurRepriseDLAB_LV_LAB + $ValeurRepriseDLAB_DR_LAB + $ValeurRepriseDLAB_CH_LAB + $ValeurRepriseDLAB_TE_LAB + $ValeurRepriseDLAB_TR_LAB + $ValeurRepriseDLAB_SH_LAB + $ValeurRepriseDLAB_LE_LAB 
+ $ValeurRepriseDLAB_HA_LAB + $ValeurRepriseDLAB_LO_LAB + $ValeurRepriseDLAB_GR_LAB + $ValeurRepriseDLAB_QC_LAB + $ValeurRepriseDLAB_MTL_LAB + $ValeurRepriseDLAB_SMB_LAB;

//HORS GARANTIE:  3-Calcul du total de reprises 
$NbrRepriseDLAB_HG = $NbrRepriseDLAB_LV_HG + $NbrRepriseDLAB_DR_HG + $NbrRepriseDLAB_CH_HG + $NbrRepriseDLAB_TE_HG + $NbrRepriseDLAB_TR_HG + $NbrRepriseDLAB_SH_HG + $NbrRepriseDLAB_LE_HG 
+ $NbrRepriseDLAB_HA_HG + $NbrRepriseDLAB_LO_HG + $NbrRepriseDLAB_GR_HG + $NbrRepriseDLAB_QC_HG+ $NbrRepriseDLAB_MTL_HG  + $NbrRepriseDLAB_SMB_HG;
//HORS GARANTIEL: 3.5-Calcul de la valeur TOTALE($$) 
$ValeurRepriseDLAB_HG = $ValeurRepriseDLAB_LV_HG + $ValeurRepriseDLAB_DR_HG + $ValeurRepriseDLAB_CH_HG + $ValeurRepriseDLAB_TE_HG + $ValeurRepriseDLAB_TR_HG + $ValeurRepriseDLAB_SH_HG + $ValeurRepriseDLAB_LE_HG 
+ $ValeurRepriseDLAB_HA_HG + $ValeurRepriseDLAB_LO_HG + $ValeurRepriseDLAB_GR_HG + $ValeurRepriseDLAB_QC_HG+ $ValeurRepriseDLAB_MTL_HG  + $ValeurRepriseDLAB_SMB_HG;

//Calcul total de jobs fabriqués par le lab pour cette période
$queryTotalDLABPeriode = "SELECT count(order_num) as NbrCommandes_DLAB FROM orders 
WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = 3";
$resultTotalDLAB = mysqli_query($con,$queryTotalDLABPeriode)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTotalDLAB   = mysqli_fetch_array($resultTotalDLAB);


$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">DLAB: P&eacute;riode du $date1 au $date2      <h3> Total fabriqu&eacute; par DLAB: $DataTotalDLAB[NbrCommandes_DLAB] commandes</h3></th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Reprises Garanties ($)</th>
					<th>Reprises Lab ($)</th>
					<th>Reprises Hors Garanties ($)</th>	
				</tr>";

//Calcul de la somme des 3 types de reprises				
$TotalRepriseDLAB=$NbrRepriseDLAB_G +$NbrRepriseDLAB_LAB + $NbrRepriseDLAB_HG;

//Calcul du pourcentage de reprises vs total de commande envoyé a ce fournisseur
$PourcentageRepriseDLAB =($TotalRepriseDLAB/$DataTotalDLAB[NbrCommandes_DLAB])*100;
$PourcentageRepriseDLAB = round($PourcentageRepriseDLAB,2);
//DIRECTLAB SAINT-CATHARINES
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseDLAB_LV_G ($ValeurRepriseDLAB_LV_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LV_LAB ($ValeurRepriseDLAB_LV_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LV_HG ($ValeurRepriseDLAB_LV_HG$)</th>	
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseDLAB_DR_G ($ValeurRepriseDLAB_DR_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_DR_LAB ($ValeurRepriseDLAB_DR_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_DR_HG ($ValeurRepriseDLAB_DR_HG$)</th>	
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseDLAB_CH_G ($ValeurRepriseDLAB_CH_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_CH_LAB ($ValeurRepriseDLAB_CH_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_CH_HG ($ValeurRepriseDLAB_CH_HG$)</th>	
</tr>

<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseDLAB_TE_G ($ValeurRepriseDLAB_TE_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_TE_LAB ($ValeurRepriseDLAB_TE_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_TE_HG ($ValeurRepriseDLAB_TE_HG$)</th>	
</tr>

<tr>
	<th>Trois-Rivi&egraveres</th>
	<th align=\"center\">$NbrRepriseDLAB_TR_G ($ValeurRepriseDLAB_TR_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_TR_LAB ($ValeurRepriseDLAB_TR_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_TR_HG ($ValeurRepriseDLAB_TR_HG$)</th>	
</tr>

<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseDLAB_SH_G ($ValeurRepriseDLAB_SH_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_SH_LAB ($ValeurRepriseDLAB_SH_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_SH_HG ($ValeurRepriseDLAB_SH_HG$)</th>	
</tr>

<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseDLAB_LE_G ($ValeurRepriseDLAB_LE_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LE_LAB ($ValeurRepriseDLAB_LE_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LE_HG ($ValeurRepriseDLAB_LE_HG$)</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseDLAB_HA_G ($ValeurRepriseDLAB_HA_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_HA_LAB ($ValeurRepriseDLAB_HA_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_HA_HG ($ValeurRepriseDLAB_HA_HG$)</th>			
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseDLAB_LO_G ($ValeurRepriseDLAB_LO_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LO_LAB ($ValeurRepriseDLAB_LO_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LO_HG ($ValeurRepriseDLAB_LO_HG$)</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseDLAB_GR_G ($ValeurRepriseDLAB_GR_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_GR_LAB ($ValeurRepriseDLAB_GR_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_GR_HG ($ValeurRepriseDLAB_GR_HG$)</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseDLAB_QC_G ($ValeurRepriseDLAB_QC_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_QC_LAB ($ValeurRepriseDLAB_QC_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_QC_HG ($ValeurRepriseDLAB_QC_HG$)</th>		
</tr>
<tr>
	<th>Montr&eacute;al</th>
	<th align=\"center\">$NbrRepriseDLAB_MTL_G ($ValeurRepriseDLAB_MTL_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_MTL_LAB ($ValeurRepriseDLAB_MTL_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_MTL_HG ($ValeurRepriseDLAB_MTL_HG$)</th>				
</tr>

<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseDLAB_SMB_G ($ValeurRepriseDLAB_SMB_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_SMB_LAB ($ValeurRepriseDLAB_SMB_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_SMB_HG ($ValeurRepriseDLAB_SMB_HG$)</th>			
</tr>

<tr bgcolor=\"#B1DEFE\">
	<th>TOTAUX</th>
	<th align=\"center\">$NbrRepriseDLAB_G ($ValeurRepriseDLAB_G$)</th>
	<th align=\"center\">$NbrRepriseDLAB_LAB ($ValeurRepriseDLAB_LAB$)</th>
	<th align=\"center\">$NbrRepriseDLAB_HG ($ValeurRepriseDLAB_HG$)</th>
</tr>

<tr bgcolor=\"#89DA59\">
	<th>Analyse</th>
	<th align=\"center\">$NbrRepriseDLAB_G +$NbrRepriseDLAB_LAB + $NbrRepriseDLAB_HG = $TotalRepriseDLAB  </th>
	<th colspan=\"2\" align=\"center\"> $TotalRepriseDLAB / $DataTotalDLAB[NbrCommandes_DLAB] = $PourcentageRepriseDLAB%</th>
</tr>
</table>";








//REPRISE PAR FOURNISSEUR: Swiss
$lab = 10;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $user_id = "  user_ID in ('laval','lavalsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_LV, sum(order_total) as ValeurRepriseSWISS_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_LV, sum(order_total) as ValeurRepriseSWISS_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_LV, sum(order_total) as ValeurRepriseSWISS_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   
	   case 2:
	   $user_id = "  user_ID in ('entrepotdr','safedr')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_DR, sum(order_total) as ValeurRepriseSWISS_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_DR, sum(order_total) as ValeurRepriseSWISS_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_DR, sum(order_total) as ValeurRepriseSWISS_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   
	   
	   case 3:
	   $user_id = "  user_ID in ('chicoutimi','chicoutimisafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_CH, sum(order_total) as ValeurRepriseSWISS_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_CH, sum(order_total) as ValeurRepriseSWISS_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_CH, sum(order_total) as ValeurRepriseSWISS_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 4:
	   $user_id = "  user_ID in ('entrepotifc','entrepotsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_TR, sum(order_total) as ValeurRepriseSWISS_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_TR, sum(order_total) as ValeurRepriseSWISS_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_TR, sum(order_total) as ValeurRepriseSWISS_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		   
	   case 5:
	   $user_id = "  user_ID in ('sherbrooke','sherbrookesafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_SH, sum(order_total) as ValeurRepriseSWISS_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_SH, sum(order_total) as ValeurRepriseSWISS_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_SH, sum(order_total) as ValeurRepriseSWISS_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	  
	  
	   case 6:
	   $user_id = "  user_ID in ('terrebonne','terrebonnesafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_TE, sum(order_total) as ValeurRepriseSWISS_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_TE, sum(order_total) as ValeurRepriseSWISS_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_TE, sum(order_total) as ValeurRepriseSWISS_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		     
	   case 7:
	   $user_id = "  user_ID in ('longueuil','longueuilsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_LO, sum(order_total) as ValeurRepriseSWISS_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_LO, sum(order_total) as ValeurRepriseSWISS_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_LO, sum(order_total) as ValeurRepriseSWISS_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 8:
	   $user_id = "  user_ID in ('levis','levissafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_LE, sum(order_total) as ValeurRepriseSWISS_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_LE, sum(order_total) as ValeurRepriseSWISS_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_LE, sum(order_total) as ValeurRepriseSWISS_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
	   case 9:
	   $user_id = "  user_ID in ('warehousehal','warehousehalsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_HA, sum(order_total) as ValeurRepriseSWISS_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_HA, sum(order_total) as ValeurRepriseSWISS_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_HA, sum(order_total) as ValeurRepriseSWISS_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
		   
	   case 10:
	    $user_id = "  user_ID in ('granby','granbysafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_GR, sum(order_total) as ValeurRepriseSWISS_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_GR, sum(order_total) as ValeurRepriseSWISS_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_GR, sum(order_total) as ValeurRepriseSWISS_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $user_id = "  user_ID in ('entrepotquebec','quebecsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_QC, sum(order_total) as ValeurRepriseSWISS_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_QC, sum(order_total) as ValeurRepriseSWISS_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_QC, sum(order_total) as ValeurRepriseSWISS_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 12:
	   $user_id = "  user_ID in ('montreal','montrealsafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_MTL, sum(order_total) as ValeurRepriseSWISS_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_MTL, sum(order_total) as ValeurRepriseSWISS_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_MTL, sum(order_total) as ValeurRepriseSWISS_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   
	   case 13:
	   $user_id = "  user_ID in ('stemarie','stemariesafe')";
	   //Garanties
	   $QueryRepriseSWISS_G = "SELECT count(order_num) as NbrRepriseSWISS_SMB, sum(order_total) as ValeurRepriseSWISS_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_SMB, sum(order_total) as ValeurRepriseSWISS_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseSWISS_LAB= "SELECT count(order_num) as NbrRepriseSWISS_SMB, sum(order_total) as ValeurRepriseSWISS_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseSWISS     = mysqli_query($con,$QueryRepriseSWISS_G)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseSWISS_G        = mysqli_fetch_array($resultNbrRepriseSWISS);
		
	
	//Hors Garanties
	$resultNbrRepriseSWISS_HG  = mysqli_query($con,$QueryRepriseSWISS_HG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseSWISS_HG       = mysqli_fetch_array($resultNbrRepriseSWISS_HG);	
	
	//Lab
	$resultNbrRepriseSWISS_LAB = mysqli_query($con,$QueryRepriseSWISS_LAB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseSWISS_LAB      = mysqli_fetch_array($resultNbrRepriseSWISS_LAB);	
	
 switch($x){ 
	   case 1://Laval
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_LV_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_LV]; 
	   $ValeurRepriseSWISS_LV_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_LV];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_LV_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_LV]; 
	   $ValeurRepriseSWISS_LV_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_LV];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_LV_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_LV];  
	   $ValeurRepriseSWISS_LV_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_LV];
	   break;
		 
		 
	   case 2://Drummondville
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_DR_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_DR]; 
	   $ValeurRepriseSWISS_DR_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_DR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_DR_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_DR]; 
	   $ValeurRepriseSWISS_DR_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_DR];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_DR_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_DR];  
	   $ValeurRepriseSWISS_DR_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_DR];
	   break;	 
		 
	   case 3://Chicoutimi
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_CH_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_CH]; 
	   $ValeurRepriseSWISS_CH_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_CH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_CH_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_CH]; 
	   $ValeurRepriseSWISS_CH_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_CH];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_CH_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_CH];  
	   $ValeurRepriseSWISS_CH_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_CH];
	   break; 
		 
	   case 4://Trois-Rivièeres
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_TR_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_TR]; 
	   $ValeurRepriseSWISS_TR_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_TR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_TR_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_TR]; 
	   $ValeurRepriseSWISS_TR_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_TR];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_TR_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_TR];  
	   $ValeurRepriseSWISS_TR_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_TR];
	   break;
		 
	   case 5://Sherbrooke
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_SH_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_SH]; 
	   $ValeurRepriseSWISS_SH_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_SH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_SH_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_SH]; 
	   $ValeurRepriseSWISS_SH_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_SH];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_SH_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_SH];  
	   $ValeurRepriseSWISS_SH_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_SH];
	   break;
		 
       case 6://Terrebonne
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_TE_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_TE]; 
	   $ValeurRepriseSWISS_TE_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_TE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_TE_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_TE]; 
	   $ValeurRepriseSWISS_TE_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_TE];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_TE_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_TE];  
	   $ValeurRepriseSWISS_TE_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_TE];
	   break;
		 
	   case 7://Longueuil
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_LO_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_LO]; 
	   $ValeurRepriseSWISS_LO_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_LO];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_LO_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_LO]; 
	   $ValeurRepriseSWISS_LO_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_LO];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_LO_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_LO];  
	   $ValeurRepriseSWISS_LO_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_LO];	
	   break;
		 
	   case 8://Lévis
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_LE_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_LE]; 
	   $ValeurRepriseSWISS_LE_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_LE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_LE_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_LE]; 
	   $ValeurRepriseSWISS_LE_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_LE];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_LE_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_LE];  
	   $ValeurRepriseSWISS_LE_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_LE];	
	   break;
	 
	   case 9://Halifax
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_HA_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_HA]; 
	   $ValeurRepriseSWISS_HA_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_HA];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_HA_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_HA]; 
	   $ValeurRepriseSWISS_HA_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_HA];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_HA_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_HA];  
	   $ValeurRepriseSWISS_HA_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_HA];	
	   break;
		 
	   case 10://Granby
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_GR_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_GR]; 
	   $ValeurRepriseSWISS_GR_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_GR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_GR_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_GR]; 
	   $ValeurRepriseSWISS_GR_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_GR];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_GR_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_GR];  
	   $ValeurRepriseSWISS_GR_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_GR];		
	   break;
		 
	   case 11://Québec
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_QC_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_QC]; 
	   $ValeurRepriseSWISS_QC_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_QC];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_QC_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_QC]; 
	   $ValeurRepriseSWISS_QC_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_QC];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_QC_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_QC];  
	   $ValeurRepriseSWISS_QC_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_QC];		
	   break;
	   
	   case 12://Montréal
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_MTL_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_MTL]; 
	   $ValeurRepriseSWISS_MTL_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_MTL];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_MTL_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_MTL]; 
	   $ValeurRepriseSWISS_MTL_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_MTL];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_MTL_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_MTL];  
	   $ValeurRepriseSWISS_MTL_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_MTL];		
	   break;
	   
	   case 13://Sainte-Marie
	   //Reprises 'Garanties'
	   $NbrRepriseSWISS_SMB_G   	 	= $DataRepriseSWISS_G[NbrRepriseSWISS_SMB]; 
	   $ValeurRepriseSWISS_SMB_G	 	= $DataRepriseSWISS_G[ValeurRepriseSWISS_SMB];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseSWISS_SMB_HG 	 	= $DataRepriseSWISS_HG[NbrRepriseSWISS_SMB]; 
	   $ValeurRepriseSWISS_SMB_HG  	= $DataRepriseSWISS_HG[ValeurRepriseSWISS_SMB];
	   //Reprises 'Lab'
	   $NbrRepriseSWISS_SMB_LAB 		= $DataRepriseSWISS_LAB[NbrRepriseSWISS_SMB];  
	   $ValeurRepriseSWISS_SMB_LAB	= $DataRepriseSWISS_LAB[ValeurRepriseSWISS_SMB];		
	   break;
   }//End Switch	
	
}//end FOR


//GARANTIES: 1-Calcul du total de reprises 
$NbrRepriseSWISS_G = $NbrRepriseSWISS_LV_G + $NbrRepriseSWISS_DR_G + $NbrRepriseSWISS_CH_G + $NbrRepriseSWISS_TE_G + $NbrRepriseSWISS_TR_G + $NbrRepriseSWISS_SH_G + $NbrRepriseSWISS_LE_G 
+ $NbrRepriseSWISS_HA_G + $NbrRepriseSWISS_LO_G + $NbrRepriseSWISS_GR_G + $NbrRepriseSWISS_QC_G+ $NbrRepriseSWISS_MTL_G  + $NbrRepriseSWISS_SMB_G;
//GARANTIES: 1.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseSWISS_G = $ValeurRepriseSWISS_LV_G + $ValeurRepriseSWISS_DR_G + $ValeurRepriseSWISS_CH_G + $ValeurRepriseSWISS_TE_G + $ValeurRepriseSWISS_TR_G + $ValeurRepriseSWISS_SH_G + $ValeurRepriseSWISS_LE_G 
+ $ValeurRepriseSWISS_HA_G + $ValeurRepriseSWISS_LO_G + $ValeurRepriseSWISS_GR_G + $ValeurRepriseSWISS_QC_G+ $ValeurRepriseSWISS_MTL_G  + $ValeurRepriseSWISS_SMB_G;

//LAB: 2-Calcul du total de reprises 
$NbrRepriseSWISS_LAB = $NbrRepriseSWISS_LV_LAB + $NbrRepriseSWISS_DR_LAB + $NbrRepriseSWISS_CH_LAB + $NbrRepriseSWISS_TE_LAB + $NbrRepriseSWISS_TR_LAB + $NbrRepriseSWISS_SH_LAB + $NbrRepriseSWISS_LE_LAB 
+ $NbrRepriseSWISS_HA_LAB + $NbrRepriseSWISS_LO_LAB + $NbrRepriseSWISS_GR_LAB + $NbrRepriseSWISS_QC_LAB + $NbrRepriseSWISS_MTL_LAB + $NbrRepriseSWISS_SMB_LAB;
//LAB 2.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseSWISS_LAB  = $ValeurRepriseSWISS_LV_LAB + $ValeurRepriseSWISS_DR_LAB + $ValeurRepriseSWISS_CH_LAB + $ValeurRepriseSWISS_TE_LAB + $ValeurRepriseSWISS_TR_LAB + $ValeurRepriseSWISS_SH_LAB + $ValeurRepriseSWISS_LE_LAB 
+ $ValeurRepriseSWISS_HA_LAB + $ValeurRepriseSWISS_LO_LAB + $ValeurRepriseSWISS_GR_LAB + $ValeurRepriseSWISS_QC_LAB + $ValeurRepriseSWISS_MTL_LAB + $ValeurRepriseSWISS_SMB_LAB;

//HORS GARANTIE:  3-Calcul du total de reprises 
$NbrRepriseSWISS_HG = $NbrRepriseSWISS_LV_HG + $NbrRepriseSWISS_DR_HG + $NbrRepriseSWISS_CH_HG + $NbrRepriseSWISS_TE_HG + $NbrRepriseSWISS_TR_HG + $NbrRepriseSWISS_SH_HG + $NbrRepriseSWISS_LE_HG 
+ $NbrRepriseSWISS_HA_HG + $NbrRepriseSWISS_LO_HG + $NbrRepriseSWISS_GR_HG + $NbrRepriseSWISS_QC_HG+ $NbrRepriseSWISS_MTL_HG  + $NbrRepriseSWISS_SMB_HG;
//HORS GARANTIEL: 3.5-Calcul de la valeur TOTALE($$) 
$ValeurRepriseSWISS_HG = $ValeurRepriseSWISS_LV_HG + $ValeurRepriseSWISS_DR_HG + $ValeurRepriseSWISS_CH_HG + $ValeurRepriseSWISS_TE_HG + $ValeurRepriseSWISS_TR_HG + $ValeurRepriseSWISS_SH_HG + $ValeurRepriseSWISS_LE_HG 
+ $ValeurRepriseSWISS_HA_HG + $ValeurRepriseSWISS_LO_HG + $ValeurRepriseSWISS_GR_HG + $ValeurRepriseSWISS_QC_HG+ $ValeurRepriseSWISS_MTL_HG  + $ValeurRepriseSWISS_SMB_HG;



//Calcul total de jobs fabriqués par le lab pour cette période
$queryTotalSWISSPeriode = "SELECT count(order_num) as NbrCommandes_SWISS FROM orders 
WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = 10";
$resultTotalSWISS = mysqli_query($con,$queryTotalSWISSPeriode)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTotalSWISS   = mysqli_fetch_array($resultTotalSWISS);



//Calcul de la somme des 3 types de reprises				
$TotalRepriseSWISS=$NbrRepriseSWISS_G +$NbrRepriseSWISS_LAB + $NbrRepriseSWISS_HG;

//Calcul du pourcentage de reprises vs total de commande envoyé a ce fournisseur
$PourcentageRepriseSWISS =($TotalRepriseSWISS/$DataTotalSWISS[NbrCommandes_SWISS])*100;
$PourcentageRepriseSWISS = round($PourcentageRepriseSWISS,2);

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">SWISS: P&eacute;riode du $date1 au $date2
					<h3> Total fabriqu&eacute; par SWISS: $DataTotalSWISS[NbrCommandes_SWISS] commandes</h3></th>
					
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Reprises Garanties ($)</th>
					<th>Reprises Lab ($)</th>
					<th>Reprises Hors Garanties ($)</th>	
				</tr>";

//Swisscoat = #10
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseSWISS_LV_G ($ValeurRepriseSWISS_LV_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LV_LAB ($ValeurRepriseSWISS_LV_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LV_HG ($ValeurRepriseSWISS_LV_HG$)</th>	
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseSWISS_DR_G ($ValeurRepriseSWISS_DR_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_DR_LAB ($ValeurRepriseSWISS_DR_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_DR_HG ($ValeurRepriseSWISS_DR_HG$)</th>	
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseSWISS_CH_G ($ValeurRepriseSWISS_CH_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_CH_LAB ($ValeurRepriseSWISS_CH_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_CH_HG ($ValeurRepriseSWISS_CH_HG$)</th>	
</tr>

<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseSWISS_TE_G ($ValeurRepriseSWISS_TE_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_TE_LAB ($ValeurRepriseSWISS_TE_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_TE_HG ($ValeurRepriseSWISS_TE_HG$)</th>	
</tr>

<tr>
	<th>Trois-Rivi&egraveres</th>
	<th align=\"center\">$NbrRepriseSWISS_TR_G ($ValeurRepriseSWISS_TR_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_TR_LAB ($ValeurRepriseSWISS_TR_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_TR_HG ($ValeurRepriseSWISS_TR_HG$)</th>	
</tr>

<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseSWISS_SH_G ($ValeurRepriseSWISS_SH_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_SH_LAB ($ValeurRepriseSWISS_SH_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_SH_HG ($ValeurRepriseSWISS_SH_HG$)</th>	
</tr>

<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseSWISS_LE_G ($ValeurRepriseSWISS_LE_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LE_LAB ($ValeurRepriseSWISS_LE_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LE_HG ($ValeurRepriseSWISS_LE_HG$)</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseSWISS_HA_G ($ValeurRepriseSWISS_HA_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_HA_LAB ($ValeurRepriseSWISS_HA_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_HA_HG ($ValeurRepriseSWISS_HA_HG$)</th>			
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseSWISS_LO_G ($ValeurRepriseSWISS_LO_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LO_LAB ($ValeurRepriseSWISS_LO_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LO_HG ($ValeurRepriseSWISS_LO_HG$)</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseSWISS_GR_G ($ValeurRepriseSWISS_GR_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_GR_LAB ($ValeurRepriseSWISS_GR_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_GR_HG ($ValeurRepriseSWISS_GR_HG$)</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseSWISS_QC_G ($ValeurRepriseSWISS_QC_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_QC_LAB ($ValeurRepriseSWISS_QC_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_QC_HG ($ValeurRepriseSWISS_QC_HG$)</th>		
</tr>
<tr>
	<th>Montr&eacute;al</th>
	<th align=\"center\">$NbrRepriseSWISS_MTL_G ($ValeurRepriseSWISS_MTL_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_MTL_LAB ($ValeurRepriseSWISS_MTL_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_MTL_HG ($ValeurRepriseSWISS_MTL_HG$)</th>				
</tr>

<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseSWISS_SMB_G ($ValeurRepriseSWISS_SMB_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_SMB_LAB ($ValeurRepriseSWISS_SMB_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_SMB_HG ($ValeurRepriseSWISS_SMB_HG$)</th>			
</tr>

<tr bgcolor=\"#B1DEFE\">
	<th>TOTAUX</th>
	<th align=\"center\">$NbrRepriseSWISS_G ($ValeurRepriseSWISS_G$)</th>
	<th align=\"center\">$NbrRepriseSWISS_LAB ($ValeurRepriseSWISS_LAB$)</th>
	<th align=\"center\">$NbrRepriseSWISS_HG ($ValeurRepriseSWISS_HG$)</th>
</tr>

<tr bgcolor=\"#89DA59\">
	<th>Analyse</th>
	<th align=\"center\">$NbrRepriseSWISS_G +$NbrRepriseSWISS_LAB + $NbrRepriseSWISS_HG = $TotalRepriseSWISS  </th>
	<th colspan=\"2\" align=\"center\"> $TotalRepriseSWISS / $DataTotalSWISS[NbrCommandes_SWISS] = $PourcentageRepriseSWISS%</th>
</tr>	
</table>";



//REPRISE PAR FOURNISSEUR: HKO
$lab = 25;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $user_id = "  user_ID in ('laval','lavalsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_LV, sum(order_total) as ValeurRepriseHKO_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_LV, sum(order_total) as ValeurRepriseHKO_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_LV, sum(order_total) as ValeurRepriseHKO_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   
	   case 2:
	   $user_id = "  user_ID in ('entrepotdr','safedr')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_DR, sum(order_total) as ValeurRepriseHKO_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_DR, sum(order_total) as ValeurRepriseHKO_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_DR, sum(order_total) as ValeurRepriseHKO_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   
	   
	   case 3:
	   $user_id = "  user_ID in ('chicoutimi','chicoutimisafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_CH, sum(order_total) as ValeurRepriseHKO_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_CH, sum(order_total) as ValeurRepriseHKO_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_CH, sum(order_total) as ValeurRepriseHKO_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 4:
	   $user_id = "  user_ID in ('entrepotifc','entrepotsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_TR, sum(order_total) as ValeurRepriseHKO_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_TR, sum(order_total) as ValeurRepriseHKO_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_TR, sum(order_total) as ValeurRepriseHKO_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		   
	   case 5:
	   $user_id = "  user_ID in ('sherbrooke','sherbrookesafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_SH, sum(order_total) as ValeurRepriseHKO_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_SH, sum(order_total) as ValeurRepriseHKO_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_SH, sum(order_total) as ValeurRepriseHKO_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	  
	  
	   case 6:
	   $user_id = "  user_ID in ('terrebonne','terrebonnesafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_TE, sum(order_total) as ValeurRepriseHKO_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_TE, sum(order_total) as ValeurRepriseHKO_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_TE, sum(order_total) as ValeurRepriseHKO_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		     
	   case 7:
	   $user_id = "  user_ID in ('longueuil','longueuilsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_LO, sum(order_total) as ValeurRepriseHKO_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_LO, sum(order_total) as ValeurRepriseHKO_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_LO, sum(order_total) as ValeurRepriseHKO_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 8:
	   $user_id = "  user_ID in ('levis','levissafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_LE, sum(order_total) as ValeurRepriseHKO_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_LE, sum(order_total) as ValeurRepriseHKO_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_LE, sum(order_total) as ValeurRepriseHKO_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
	   case 9:
	   $user_id = "  user_ID in ('warehousehal','warehousehalsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_HA, sum(order_total) as ValeurRepriseHKO_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_HA, sum(order_total) as ValeurRepriseHKO_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_HA, sum(order_total) as ValeurRepriseHKO_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
		   
	   case 10:
	    $user_id = "  user_ID in ('granby','granbysafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_GR, sum(order_total) as ValeurRepriseHKO_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_GR, sum(order_total) as ValeurRepriseHKO_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_GR, sum(order_total) as ValeurRepriseHKO_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $user_id = "  user_ID in ('entrepotquebec','quebecsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_QC, sum(order_total) as ValeurRepriseHKO_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_QC, sum(order_total) as ValeurRepriseHKO_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_QC, sum(order_total) as ValeurRepriseHKO_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 12:
	   $user_id = "  user_ID in ('montreal','montrealsafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_MTL, sum(order_total) as ValeurRepriseHKO_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_MTL, sum(order_total) as ValeurRepriseHKO_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_MTL, sum(order_total) as ValeurRepriseHKO_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   
	   case 13:
	   $user_id = "  user_ID in ('stemarie','stemariesafe')";
	   //Garanties
	   $QueryRepriseHKO_G = "SELECT count(order_num) as NbrRepriseHKO_SMB, sum(order_total) as ValeurRepriseHKO_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_SMB, sum(order_total) as ValeurRepriseHKO_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseHKO_LAB= "SELECT count(order_num) as NbrRepriseHKO_SMB, sum(order_total) as ValeurRepriseHKO_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseHKO     = mysqli_query($con,$QueryRepriseHKO_G)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseHKO_G        = mysqli_fetch_array($resultNbrRepriseHKO);
		
	
	//Hors Garanties
	$resultNbrRepriseHKO_HG  = mysqli_query($con,$QueryRepriseHKO_HG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseHKO_HG       = mysqli_fetch_array($resultNbrRepriseHKO_HG);	
	
	//Lab
	$resultNbrRepriseHKO_LAB = mysqli_query($con,$QueryRepriseHKO_LAB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseHKO_LAB      = mysqli_fetch_array($resultNbrRepriseHKO_LAB);	
	
 switch($x){ 
	   case 1://Laval
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_LV_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_LV]; 
	   $ValeurRepriseHKO_LV_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_LV];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_LV_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_LV]; 
	   $ValeurRepriseHKO_LV_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_LV];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_LV_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_LV];  
	   $ValeurRepriseHKO_LV_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_LV];
	   break;
		 
		 
	   case 2://Drummondville
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_DR_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_DR]; 
	   $ValeurRepriseHKO_DR_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_DR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_DR_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_DR]; 
	   $ValeurRepriseHKO_DR_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_DR];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_DR_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_DR];  
	   $ValeurRepriseHKO_DR_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_DR];
	   break;	 
		 
	   case 3://Chicoutimi
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_CH_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_CH]; 
	   $ValeurRepriseHKO_CH_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_CH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_CH_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_CH]; 
	   $ValeurRepriseHKO_CH_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_CH];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_CH_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_CH];  
	   $ValeurRepriseHKO_CH_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_CH];
	   break; 
		 
	   case 4://Trois-Rivièeres
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_TR_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_TR]; 
	   $ValeurRepriseHKO_TR_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_TR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_TR_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_TR]; 
	   $ValeurRepriseHKO_TR_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_TR];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_TR_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_TR];  
	   $ValeurRepriseHKO_TR_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_TR];
	   break;
		 
	   case 5://Sherbrooke
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_SH_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_SH]; 
	   $ValeurRepriseHKO_SH_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_SH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_SH_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_SH]; 
	   $ValeurRepriseHKO_SH_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_SH];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_SH_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_SH];  
	   $ValeurRepriseHKO_SH_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_SH];
	   break;
		 
       case 6://Terrebonne
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_TE_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_TE]; 
	   $ValeurRepriseHKO_TE_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_TE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_TE_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_TE]; 
	   $ValeurRepriseHKO_TE_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_TE];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_TE_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_TE];  
	   $ValeurRepriseHKO_TE_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_TE];
	   break;
		 
	   case 7://Longueuil
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_LO_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_LO]; 
	   $ValeurRepriseHKO_LO_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_LO];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_LO_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_LO]; 
	   $ValeurRepriseHKO_LO_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_LO];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_LO_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_LO];  
	   $ValeurRepriseHKO_LO_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_LO];	
	   break;
		 
	   case 8://Lévis
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_LE_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_LE]; 
	   $ValeurRepriseHKO_LE_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_LE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_LE_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_LE]; 
	   $ValeurRepriseHKO_LE_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_LE];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_LE_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_LE];  
	   $ValeurRepriseHKO_LE_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_LE];	
	   break;
	 
	   case 9://Halifax
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_HA_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_HA]; 
	   $ValeurRepriseHKO_HA_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_HA];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_HA_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_HA]; 
	   $ValeurRepriseHKO_HA_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_HA];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_HA_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_HA];  
	   $ValeurRepriseHKO_HA_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_HA];	
	   break;
		 
	   case 10://Granby
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_GR_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_GR]; 
	   $ValeurRepriseHKO_GR_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_GR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_GR_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_GR]; 
	   $ValeurRepriseHKO_GR_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_GR];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_GR_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_GR];  
	   $ValeurRepriseHKO_GR_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_GR];		
	   break;
		 
	   case 11://Québec
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_QC_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_QC]; 
	   $ValeurRepriseHKO_QC_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_QC];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_QC_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_QC]; 
	   $ValeurRepriseHKO_QC_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_QC];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_QC_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_QC];  
	   $ValeurRepriseHKO_QC_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_QC];		
	   break;
	   
	   case 12://Montréal
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_MTL_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_MTL]; 
	   $ValeurRepriseHKO_MTL_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_MTL];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_MTL_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_MTL]; 
	   $ValeurRepriseHKO_MTL_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_MTL];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_MTL_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_MTL];  
	   $ValeurRepriseHKO_MTL_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_MTL];		
	   break;
	   
	   case 13://Sainte-Marie
	   //Reprises 'Garanties'
	   $NbrRepriseHKO_SMB_G   	 	= $DataRepriseHKO_G[NbrRepriseHKO_SMB]; 
	   $ValeurRepriseHKO_SMB_G	 	= $DataRepriseHKO_G[ValeurRepriseHKO_SMB];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseHKO_SMB_HG 	 	= $DataRepriseHKO_HG[NbrRepriseHKO_SMB]; 
	   $ValeurRepriseHKO_SMB_HG  	= $DataRepriseHKO_HG[ValeurRepriseHKO_SMB];
	   //Reprises 'Lab'
	   $NbrRepriseHKO_SMB_LAB 		= $DataRepriseHKO_LAB[NbrRepriseHKO_SMB];  
	   $ValeurRepriseHKO_SMB_LAB	= $DataRepriseHKO_LAB[ValeurRepriseHKO_SMB];		
	   break;
   }//End Switch	
	
}//end FOR


//GARANTIES: 1-Calcul du total de reprises 
$NbrRepriseHKO_G = $NbrRepriseHKO_LV_G + $NbrRepriseHKO_DR_G + $NbrRepriseHKO_CH_G + $NbrRepriseHKO_TE_G + $NbrRepriseHKO_TR_G + $NbrRepriseHKO_SH_G + $NbrRepriseHKO_LE_G 
+ $NbrRepriseHKO_HA_G + $NbrRepriseHKO_LO_G + $NbrRepriseHKO_GR_G + $NbrRepriseHKO_QC_G+ $NbrRepriseHKO_MTL_G  + $NbrRepriseHKO_SMB_G;
//GARANTIES: 1.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseHKO_G = $ValeurRepriseHKO_LV_G + $ValeurRepriseHKO_DR_G + $ValeurRepriseHKO_CH_G + $ValeurRepriseHKO_TE_G + $ValeurRepriseHKO_TR_G + $ValeurRepriseHKO_SH_G + $ValeurRepriseHKO_LE_G 
+ $ValeurRepriseHKO_HA_G + $ValeurRepriseHKO_LO_G + $ValeurRepriseHKO_GR_G + $ValeurRepriseHKO_QC_G+ $ValeurRepriseHKO_MTL_G  + $ValeurRepriseHKO_SMB_G;

//LAB: 2-Calcul du total de reprises 
$NbrRepriseHKO_LAB = $NbrRepriseHKO_LV_LAB + $NbrRepriseHKO_DR_LAB + $NbrRepriseHKO_CH_LAB + $NbrRepriseHKO_TE_LAB + $NbrRepriseHKO_TR_LAB + $NbrRepriseHKO_SH_LAB + $NbrRepriseHKO_LE_LAB 
+ $NbrRepriseHKO_HA_LAB + $NbrRepriseHKO_LO_LAB + $NbrRepriseHKO_GR_LAB + $NbrRepriseHKO_QC_LAB + $NbrRepriseHKO_MTL_LAB + $NbrRepriseHKO_SMB_LAB;
//LAB 2.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseHKO_LAB  = $ValeurRepriseHKO_LV_LAB + $ValeurRepriseHKO_DR_LAB + $ValeurRepriseHKO_CH_LAB + $ValeurRepriseHKO_TE_LAB + $ValeurRepriseHKO_TR_LAB + $ValeurRepriseHKO_SH_LAB + $ValeurRepriseHKO_LE_LAB 
+ $ValeurRepriseHKO_HA_LAB + $ValeurRepriseHKO_LO_LAB + $ValeurRepriseHKO_GR_LAB + $ValeurRepriseHKO_QC_LAB + $ValeurRepriseHKO_MTL_LAB + $ValeurRepriseHKO_SMB_LAB;

//HORS GARANTIE:  3-Calcul du total de reprises 
$NbrRepriseHKO_HG = $NbrRepriseHKO_LV_HG + $NbrRepriseHKO_DR_HG + $NbrRepriseHKO_CH_HG + $NbrRepriseHKO_TE_HG + $NbrRepriseHKO_TR_HG + $NbrRepriseHKO_SH_HG + $NbrRepriseHKO_LE_HG 
+ $NbrRepriseHKO_HA_HG + $NbrRepriseHKO_LO_HG + $NbrRepriseHKO_GR_HG + $NbrRepriseHKO_QC_HG+ $NbrRepriseHKO_MTL_HG  + $NbrRepriseHKO_SMB_HG;
//HORS GARANTIEL: 3.5-Calcul de la valeur TOTALE($$) 
$ValeurRepriseHKO_HG = $ValeurRepriseHKO_LV_HG + $ValeurRepriseHKO_DR_HG + $ValeurRepriseHKO_CH_HG + $ValeurRepriseHKO_TE_HG + $ValeurRepriseHKO_TR_HG + $ValeurRepriseHKO_SH_HG + $ValeurRepriseHKO_LE_HG 
+ $ValeurRepriseHKO_HA_HG + $ValeurRepriseHKO_LO_HG + $ValeurRepriseHKO_GR_HG + $ValeurRepriseHKO_QC_HG+ $ValeurRepriseHKO_MTL_HG  + $ValeurRepriseHKO_SMB_HG;


//Calcul total de jobs fabriqués par le lab pour cette période
$queryTotalHKOPeriode = "SELECT count(order_num) as NbrCommandes_HKO FROM orders 
WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = 25";
$resultTotalHKO = mysqli_query($con,$queryTotalHKOPeriode)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTotalHKO   = mysqli_fetch_array($resultTotalHKO);

//Calcul de la somme des 3 types de reprises				
$TotalRepriseHKO=$NbrRepriseHKO_G +$NbrRepriseHKO_LAB + $NbrRepriseHKO_HG;

//Calcul du pourcentage de reprises vs total de commande envoyé a ce fournisseur
$PourcentageRepriseHKO =($TotalRepriseHKO/$DataTotalHKO[NbrCommandes_HKO])*100;
$PourcentageRepriseHKO = round($PourcentageRepriseHKO,2);


$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">HKO: P&eacute;riode du $date1 au $date2 
					 <h3> Total fabriqu&eacute; par HKO: $DataTotalHKO[NbrCommandes_HKO] commandes</h3>
					</th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Reprises Garanties ($)</th>
					<th>Reprises Lab ($)</th>
					<th>Reprises Hors Garanties ($)</th>	
				</tr>";

//HKOcoat = #10
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseHKO_LV_G ($ValeurRepriseHKO_LV_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_LV_LAB ($ValeurRepriseHKO_LV_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_LV_HG ($ValeurRepriseHKO_LV_HG$)</th>	
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseHKO_DR_G ($ValeurRepriseHKO_DR_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_DR_LAB ($ValeurRepriseHKO_DR_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_DR_HG ($ValeurRepriseHKO_DR_HG$)</th>	
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseHKO_CH_G ($ValeurRepriseHKO_CH_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_CH_LAB ($ValeurRepriseHKO_CH_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_CH_HG ($ValeurRepriseHKO_CH_HG$)</th>	
</tr>

<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseHKO_TE_G ($ValeurRepriseHKO_TE_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_TE_LAB ($ValeurRepriseHKO_TE_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_TE_HG ($ValeurRepriseHKO_TE_HG$)</th>	
</tr>

<tr>
	<th>Trois-Rivi&egraveres</th>
	<th align=\"center\">$NbrRepriseHKO_TR_G ($ValeurRepriseHKO_TR_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_TR_LAB ($ValeurRepriseHKO_TR_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_TR_HG ($ValeurRepriseHKO_TR_HG$)</th>	
</tr>

<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseHKO_SH_G ($ValeurRepriseHKO_SH_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_SH_LAB ($ValeurRepriseHKO_SH_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_SH_HG ($ValeurRepriseHKO_SH_HG$)</th>	
</tr>

<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseHKO_LE_G ($ValeurRepriseHKO_LE_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_LE_LAB ($ValeurRepriseHKO_LE_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_LE_HG ($ValeurRepriseHKO_LE_HG$)</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseHKO_HA_G ($ValeurRepriseHKO_HA_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_HA_LAB ($ValeurRepriseHKO_HA_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_HA_HG ($ValeurRepriseHKO_HA_HG$)</th>			
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseHKO_LO_G ($ValeurRepriseHKO_LO_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_LO_LAB ($ValeurRepriseHKO_LO_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_LO_HG ($ValeurRepriseHKO_LO_HG$)</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseHKO_GR_G ($ValeurRepriseHKO_GR_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_GR_LAB ($ValeurRepriseHKO_GR_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_GR_HG ($ValeurRepriseHKO_GR_HG$)</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseHKO_QC_G ($ValeurRepriseHKO_QC_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_QC_LAB ($ValeurRepriseHKO_QC_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_QC_HG ($ValeurRepriseHKO_QC_HG$)</th>		
</tr>
<tr>
	<th>Montr&eacute;al</th>
	<th align=\"center\">$NbrRepriseHKO_MTL_G ($ValeurRepriseHKO_MTL_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_MTL_LAB ($ValeurRepriseHKO_MTL_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_MTL_HG ($ValeurRepriseHKO_MTL_HG$)</th>				
</tr>

<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseHKO_SMB_G ($ValeurRepriseHKO_SMB_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_SMB_LAB ($ValeurRepriseHKO_SMB_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_SMB_HG ($ValeurRepriseHKO_SMB_HG$)</th>			
</tr>

<tr bgcolor=\"#B1DEFE\">
	<th>TOTAUX</th>
	<th align=\"center\">$NbrRepriseHKO_G ($ValeurRepriseHKO_G$)</th>
	<th align=\"center\">$NbrRepriseHKO_LAB ($ValeurRepriseHKO_LAB$)</th>
	<th align=\"center\">$NbrRepriseHKO_HG ($ValeurRepriseHKO_HG$)</th>
</tr>

<tr bgcolor=\"#89DA59\">
	<th>Analyse</th>
	<th align=\"center\">$NbrRepriseHKO_G +$NbrRepriseHKO_LAB + $NbrRepriseHKO_HG = $TotalRepriseHKO </th>
	<th colspan=\"2\" align=\"center\"> $TotalRepriseHKO / $DataTotalHKO[NbrCommandes_HKO] = $PourcentageRepriseHKO%</th>
</tr>	
	
</table>";









//REPRISE PAR FOURNISSEUR: GKB
$lab = 69;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $user_id = "  user_ID in ('laval','lavalsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_LV, sum(order_total) as ValeurRepriseGKB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_LV, sum(order_total) as ValeurRepriseGKB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_LV, sum(order_total) as ValeurRepriseGKB_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   
	   case 2:
	   $user_id = "  user_ID in ('entrepotdr','safedr')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_DR, sum(order_total) as ValeurRepriseGKB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_DR, sum(order_total) as ValeurRepriseGKB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_DR, sum(order_total) as ValeurRepriseGKB_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   
	   
	   case 3:
	   $user_id = "  user_ID in ('chicoutimi','chicoutimisafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_CH, sum(order_total) as ValeurRepriseGKB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_CH, sum(order_total) as ValeurRepriseGKB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_CH, sum(order_total) as ValeurRepriseGKB_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 4:
	   $user_id = "  user_ID in ('entrepotifc','entrepotsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_TR, sum(order_total) as ValeurRepriseGKB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_TR, sum(order_total) as ValeurRepriseGKB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_TR, sum(order_total) as ValeurRepriseGKB_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		   
	   case 5:
	   $user_id = "  user_ID in ('sherbrooke','sherbrookesafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_SH, sum(order_total) as ValeurRepriseGKB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_SH, sum(order_total) as ValeurRepriseGKB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_SH, sum(order_total) as ValeurRepriseGKB_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	  
	  
	   case 6:
	   $user_id = "  user_ID in ('terrebonne','terrebonnesafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_TE, sum(order_total) as ValeurRepriseGKB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_TE, sum(order_total) as ValeurRepriseGKB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_TE, sum(order_total) as ValeurRepriseGKB_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		     
	   case 7:
	   $user_id = "  user_ID in ('longueuil','longueuilsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_LO, sum(order_total) as ValeurRepriseGKB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_LO, sum(order_total) as ValeurRepriseGKB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_LO, sum(order_total) as ValeurRepriseGKB_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 8:
	   $user_id = "  user_ID in ('levis','levissafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_LE, sum(order_total) as ValeurRepriseGKB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_LE, sum(order_total) as ValeurRepriseGKB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_LE, sum(order_total) as ValeurRepriseGKB_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
	   case 9:
	   $user_id = "  user_ID in ('warehousehal','warehousehalsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_HA, sum(order_total) as ValeurRepriseGKB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_HA, sum(order_total) as ValeurRepriseGKB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_HA, sum(order_total) as ValeurRepriseGKB_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
		   
	   case 10:
	    $user_id = "  user_ID in ('granby','granbysafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_GR, sum(order_total) as ValeurRepriseGKB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_GR, sum(order_total) as ValeurRepriseGKB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_GR, sum(order_total) as ValeurRepriseGKB_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $user_id = "  user_ID in ('entrepotquebec','quebecsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_QC, sum(order_total) as ValeurRepriseGKB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_QC, sum(order_total) as ValeurRepriseGKB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_QC, sum(order_total) as ValeurRepriseGKB_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 12:
	   $user_id = "  user_ID in ('montreal','montrealsafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_MTL, sum(order_total) as ValeurRepriseGKB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_MTL, sum(order_total) as ValeurRepriseGKB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_MTL, sum(order_total) as ValeurRepriseGKB_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   
	   case 13:
	   $user_id = "  user_ID in ('stemarie','stemariesafe')";
	   //Garanties
	   $QueryRepriseGKB_G = "SELECT count(order_num) as NbrRepriseGKB_SMB, sum(order_total) as ValeurRepriseGKB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_SMB, sum(order_total) as ValeurRepriseGKB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseGKB_LAB= "SELECT count(order_num) as NbrRepriseGKB_SMB, sum(order_total) as ValeurRepriseGKB_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseGKB     = mysqli_query($con,$QueryRepriseGKB_G)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseGKB_G        = mysqli_fetch_array($resultNbrRepriseGKB);
		
	
	//Hors Garanties
	$resultNbrRepriseGKB_HG  = mysqli_query($con,$QueryRepriseGKB_HG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseGKB_HG       = mysqli_fetch_array($resultNbrRepriseGKB_HG);	
	
	//Lab
	$resultNbrRepriseGKB_LAB = mysqli_query($con,$QueryRepriseGKB_LAB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseGKB_LAB      = mysqli_fetch_array($resultNbrRepriseGKB_LAB);	
	
 switch($x){ 
	   case 1://Laval
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_LV_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_LV]; 
	   $ValeurRepriseGKB_LV_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_LV];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_LV_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_LV]; 
	   $ValeurRepriseGKB_LV_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_LV];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_LV_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_LV];  
	   $ValeurRepriseGKB_LV_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_LV];
	   break;
		 
		 
	   case 2://Drummondville
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_DR_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_DR]; 
	   $ValeurRepriseGKB_DR_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_DR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_DR_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_DR]; 
	   $ValeurRepriseGKB_DR_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_DR];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_DR_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_DR];  
	   $ValeurRepriseGKB_DR_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_DR];
	   break;	 
		 
	   case 3://Chicoutimi
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_CH_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_CH]; 
	   $ValeurRepriseGKB_CH_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_CH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_CH_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_CH]; 
	   $ValeurRepriseGKB_CH_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_CH];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_CH_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_CH];  
	   $ValeurRepriseGKB_CH_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_CH];
	   break; 
		 
	   case 4://Trois-Rivièeres
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_TR_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_TR]; 
	   $ValeurRepriseGKB_TR_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_TR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_TR_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_TR]; 
	   $ValeurRepriseGKB_TR_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_TR];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_TR_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_TR];  
	   $ValeurRepriseGKB_TR_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_TR];
	   break;
		 
	   case 5://Sherbrooke
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_SH_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_SH]; 
	   $ValeurRepriseGKB_SH_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_SH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_SH_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_SH]; 
	   $ValeurRepriseGKB_SH_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_SH];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_SH_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_SH];  
	   $ValeurRepriseGKB_SH_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_SH];
	   break;
		 
       case 6://Terrebonne
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_TE_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_TE]; 
	   $ValeurRepriseGKB_TE_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_TE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_TE_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_TE]; 
	   $ValeurRepriseGKB_TE_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_TE];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_TE_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_TE];  
	   $ValeurRepriseGKB_TE_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_TE];
	   break;
		 
	   case 7://Longueuil
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_LO_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_LO]; 
	   $ValeurRepriseGKB_LO_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_LO];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_LO_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_LO]; 
	   $ValeurRepriseGKB_LO_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_LO];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_LO_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_LO];  
	   $ValeurRepriseGKB_LO_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_LO];	
	   break;
		 
	   case 8://Lévis
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_LE_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_LE]; 
	   $ValeurRepriseGKB_LE_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_LE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_LE_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_LE]; 
	   $ValeurRepriseGKB_LE_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_LE];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_LE_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_LE];  
	   $ValeurRepriseGKB_LE_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_LE];	
	   break;
	 
	   case 9://Halifax
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_HA_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_HA]; 
	   $ValeurRepriseGKB_HA_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_HA];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_HA_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_HA]; 
	   $ValeurRepriseGKB_HA_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_HA];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_HA_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_HA];  
	   $ValeurRepriseGKB_HA_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_HA];	
	   break;
		 
	   case 10://Granby
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_GR_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_GR]; 
	   $ValeurRepriseGKB_GR_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_GR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_GR_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_GR]; 
	   $ValeurRepriseGKB_GR_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_GR];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_GR_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_GR];  
	   $ValeurRepriseGKB_GR_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_GR];		
	   break;
		 
	   case 11://Québec
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_QC_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_QC]; 
	   $ValeurRepriseGKB_QC_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_QC];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_QC_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_QC]; 
	   $ValeurRepriseGKB_QC_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_QC];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_QC_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_QC];  
	   $ValeurRepriseGKB_QC_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_QC];		
	   break;
	   
	   case 12://Montréal
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_MTL_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_MTL]; 
	   $ValeurRepriseGKB_MTL_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_MTL];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_MTL_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_MTL]; 
	   $ValeurRepriseGKB_MTL_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_MTL];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_MTL_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_MTL];  
	   $ValeurRepriseGKB_MTL_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_MTL];		
	   break;
	   
	   case 13://Sainte-Marie
	   //Reprises 'Garanties'
	   $NbrRepriseGKB_SMB_G   	 	= $DataRepriseGKB_G[NbrRepriseGKB_SMB]; 
	   $ValeurRepriseGKB_SMB_G	 	= $DataRepriseGKB_G[ValeurRepriseGKB_SMB];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseGKB_SMB_HG 	 	= $DataRepriseGKB_HG[NbrRepriseGKB_SMB]; 
	   $ValeurRepriseGKB_SMB_HG  	= $DataRepriseGKB_HG[ValeurRepriseGKB_SMB];
	   //Reprises 'Lab'
	   $NbrRepriseGKB_SMB_LAB 		= $DataRepriseGKB_LAB[NbrRepriseGKB_SMB];  
	   $ValeurRepriseGKB_SMB_LAB	= $DataRepriseGKB_LAB[ValeurRepriseGKB_SMB];		
	   break;
   }//End Switch	
	
}//end FOR


//GARANTIES: 1-Calcul du total de reprises 
$NbrRepriseGKB_G = $NbrRepriseGKB_LV_G + $NbrRepriseGKB_DR_G + $NbrRepriseGKB_CH_G + $NbrRepriseGKB_TE_G + $NbrRepriseGKB_TR_G + $NbrRepriseGKB_SH_G + $NbrRepriseGKB_LE_G 
+ $NbrRepriseGKB_HA_G + $NbrRepriseGKB_LO_G + $NbrRepriseGKB_GR_G + $NbrRepriseGKB_QC_G+ $NbrRepriseGKB_MTL_G  + $NbrRepriseGKB_SMB_G;
//GARANTIES: 1.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseGKB_G = $ValeurRepriseGKB_LV_G + $ValeurRepriseGKB_DR_G + $ValeurRepriseGKB_CH_G + $ValeurRepriseGKB_TE_G + $ValeurRepriseGKB_TR_G + $ValeurRepriseGKB_SH_G + $ValeurRepriseGKB_LE_G 
+ $ValeurRepriseGKB_HA_G + $ValeurRepriseGKB_LO_G + $ValeurRepriseGKB_GR_G + $ValeurRepriseGKB_QC_G+ $ValeurRepriseGKB_MTL_G  + $ValeurRepriseGKB_SMB_G;

//LAB: 2-Calcul du total de reprises 
$NbrRepriseGKB_LAB = $NbrRepriseGKB_LV_LAB + $NbrRepriseGKB_DR_LAB + $NbrRepriseGKB_CH_LAB + $NbrRepriseGKB_TE_LAB + $NbrRepriseGKB_TR_LAB + $NbrRepriseGKB_SH_LAB + $NbrRepriseGKB_LE_LAB 
+ $NbrRepriseGKB_HA_LAB + $NbrRepriseGKB_LO_LAB + $NbrRepriseGKB_GR_LAB + $NbrRepriseGKB_QC_LAB + $NbrRepriseGKB_MTL_LAB + $NbrRepriseGKB_SMB_LAB;
//LAB 2.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseGKB_LAB  = $ValeurRepriseGKB_LV_LAB + $ValeurRepriseGKB_DR_LAB + $ValeurRepriseGKB_CH_LAB + $ValeurRepriseGKB_TE_LAB + $ValeurRepriseGKB_TR_LAB + $ValeurRepriseGKB_SH_LAB + $ValeurRepriseGKB_LE_LAB 
+ $ValeurRepriseGKB_HA_LAB + $ValeurRepriseGKB_LO_LAB + $ValeurRepriseGKB_GR_LAB + $ValeurRepriseGKB_QC_LAB + $ValeurRepriseGKB_MTL_LAB + $ValeurRepriseGKB_SMB_LAB;

//HORS GARANTIE:  3-Calcul du total de reprises 
$NbrRepriseGKB_HG = $NbrRepriseGKB_LV_HG + $NbrRepriseGKB_DR_HG + $NbrRepriseGKB_CH_HG + $NbrRepriseGKB_TE_HG + $NbrRepriseGKB_TR_HG + $NbrRepriseGKB_SH_HG + $NbrRepriseGKB_LE_HG 
+ $NbrRepriseGKB_HA_HG + $NbrRepriseGKB_LO_HG + $NbrRepriseGKB_GR_HG + $NbrRepriseGKB_QC_HG+ $NbrRepriseGKB_MTL_HG  + $NbrRepriseGKB_SMB_HG;
//HORS GARANTIEL: 3.5-Calcul de la valeur TOTALE($$) 
$ValeurRepriseGKB_HG = $ValeurRepriseGKB_LV_HG + $ValeurRepriseGKB_DR_HG + $ValeurRepriseGKB_CH_HG + $ValeurRepriseGKB_TE_HG + $ValeurRepriseGKB_TR_HG + $ValeurRepriseGKB_SH_HG + $ValeurRepriseGKB_LE_HG 
+ $ValeurRepriseGKB_HA_HG + $ValeurRepriseGKB_LO_HG + $ValeurRepriseGKB_GR_HG + $ValeurRepriseGKB_QC_HG+ $ValeurRepriseGKB_MTL_HG  + $ValeurRepriseGKB_SMB_HG;

//Calcul total de jobs fabriqués par le lab pour cette période
$queryTotalGKBPeriode = "SELECT count(order_num) as NbrCommandes_GKB FROM orders 
WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = 69";
$resultTotalGKB = mysqli_query($con,$queryTotalGKBPeriode)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTotalGKB   = mysqli_fetch_array($resultTotalGKB);


//Calcul de la somme des 3 types de reprises				
$TotalRepriseGKB=$NbrRepriseGKB_G +$NbrRepriseGKB_LAB + $NbrRepriseGKB_HG;

//Calcul du pourcentage de reprises vs total de commande envoyé a ce fournisseur
$PourcentageRepriseGKB =($TotalRepriseGKB/$DataTotalGKB[NbrCommandes_GKB])*100;
$PourcentageRepriseGKB = round($PourcentageRepriseGKB,2);

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">GKB: P&eacute;riode du $date1 au $date2 
					<h3> Total fabriqu&eacute; par GKB: $DataTotalGKB[NbrCommandes_GKB] commandes</h3>
					</th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Reprises Garanties ($)</th>
					<th>Reprises Lab ($)</th>
					<th>Reprises Hors Garanties ($)</th>	
				</tr>";

//GKBcoat = #10
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseGKB_LV_G ($ValeurRepriseGKB_LV_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_LV_LAB ($ValeurRepriseGKB_LV_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_LV_HG ($ValeurRepriseGKB_LV_HG$)</th>	
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseGKB_DR_G ($ValeurRepriseGKB_DR_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_DR_LAB ($ValeurRepriseGKB_DR_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_DR_HG ($ValeurRepriseGKB_DR_HG$)</th>	
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseGKB_CH_G ($ValeurRepriseGKB_CH_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_CH_LAB ($ValeurRepriseGKB_CH_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_CH_HG ($ValeurRepriseGKB_CH_HG$)</th>	
</tr>

<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseGKB_TE_G ($ValeurRepriseGKB_TE_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_TE_LAB ($ValeurRepriseGKB_TE_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_TE_HG ($ValeurRepriseGKB_TE_HG$)</th>	
</tr>

<tr>
	<th>Trois-Rivi&egraveres</th>
	<th align=\"center\">$NbrRepriseGKB_TR_G ($ValeurRepriseGKB_TR_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_TR_LAB ($ValeurRepriseGKB_TR_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_TR_HG ($ValeurRepriseGKB_TR_HG$)</th>	
</tr>

<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseGKB_SH_G ($ValeurRepriseGKB_SH_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_SH_LAB ($ValeurRepriseGKB_SH_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_SH_HG ($ValeurRepriseGKB_SH_HG$)</th>	
</tr>

<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseGKB_LE_G ($ValeurRepriseGKB_LE_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_LE_LAB ($ValeurRepriseGKB_LE_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_LE_HG ($ValeurRepriseGKB_LE_HG$)</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseGKB_HA_G ($ValeurRepriseGKB_HA_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_HA_LAB ($ValeurRepriseGKB_HA_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_HA_HG ($ValeurRepriseGKB_HA_HG$)</th>			
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseGKB_LO_G ($ValeurRepriseGKB_LO_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_LO_LAB ($ValeurRepriseGKB_LO_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_LO_HG ($ValeurRepriseGKB_LO_HG$)</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseGKB_GR_G ($ValeurRepriseGKB_GR_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_GR_LAB ($ValeurRepriseGKB_GR_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_GR_HG ($ValeurRepriseGKB_GR_HG$)</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseGKB_QC_G ($ValeurRepriseGKB_QC_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_QC_LAB ($ValeurRepriseGKB_QC_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_QC_HG ($ValeurRepriseGKB_QC_HG$)</th>		
</tr>
<tr>
	<th>Montr&eacute;al</th>
	<th align=\"center\">$NbrRepriseGKB_MTL_G ($ValeurRepriseGKB_MTL_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_MTL_LAB ($ValeurRepriseGKB_MTL_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_MTL_HG ($ValeurRepriseGKB_MTL_HG$)</th>				
</tr>

<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseGKB_SMB_G ($ValeurRepriseGKB_SMB_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_SMB_LAB ($ValeurRepriseGKB_SMB_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_SMB_HG ($ValeurRepriseGKB_SMB_HG$)</th>			
</tr>

<tr bgcolor=\"#B1DEFE\">
	<th>TOTAUX</th>
	<th align=\"center\">$NbrRepriseGKB_G ($ValeurRepriseGKB_G$)</th>
	<th align=\"center\">$NbrRepriseGKB_LAB ($ValeurRepriseGKB_LAB$)</th>
	<th align=\"center\">$NbrRepriseGKB_HG ($ValeurRepriseGKB_HG$)</th>
</tr>

<tr bgcolor=\"#89DA59\">
	<th>Analyse</th>
	<th align=\"center\">$NbrRepriseGKB_G +$NbrRepriseGKB_LAB + $NbrRepriseGKB_HG = $TotalRepriseGKB  </th>
	<th colspan=\"2\" align=\"center\"> $TotalRepriseGKB / $DataTotalGKB[NbrCommandes_GKB] = $PourcentageRepriseGKB%</th>
</tr>
</table>";



$lab= "  prescript_lab NOT IN (3,10,25,69)";
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   $user_id = "  user_ID in ('laval','lavalsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_LV, sum(order_total) as ValeurRepriseAUTRES_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_LV, sum(order_total) as ValeurRepriseAUTRES_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL  AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_LV, sum(order_total) as ValeurRepriseAUTRES_LV  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL  AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   
	   case 2:
	   $user_id = "  user_ID in ('entrepotdr','safedr')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_DR, sum(order_total) as ValeurRepriseAUTRES_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_DR, sum(order_total) as ValeurRepriseAUTRES_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_DR, sum(order_total) as ValeurRepriseAUTRES_DR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
	   
	   
	   
	   case 3:
	   $user_id = "  user_ID in ('chicoutimi','chicoutimisafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_CH, sum(order_total) as ValeurRepriseAUTRES_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_CH, sum(order_total) as ValeurRepriseAUTRES_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_CH, sum(order_total) as ValeurRepriseAUTRES_CH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 4:
	   $user_id = "  user_ID in ('entrepotifc','entrepotsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_TR, sum(order_total) as ValeurRepriseAUTRES_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_TR, sum(order_total) as ValeurRepriseAUTRES_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_TR, sum(order_total) as ValeurRepriseAUTRES_TR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		   
	   case 5:
	   $user_id = "  user_ID in ('sherbrooke','sherbrookesafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_SH, sum(order_total) as ValeurRepriseAUTRES_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_SH, sum(order_total) as ValeurRepriseAUTRES_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_SH, sum(order_total) as ValeurRepriseAUTRES_SH  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	  
	  
	   case 6:
	   $user_id = "  user_ID in ('terrebonne','terrebonnesafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_TE, sum(order_total) as ValeurRepriseAUTRES_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_TE, sum(order_total) as ValeurRepriseAUTRES_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_TE, sum(order_total) as ValeurRepriseAUTRES_TE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
		     
	   case 7:
	   $user_id = "  user_ID in ('longueuil','longueuilsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_LO, sum(order_total) as ValeurRepriseAUTRES_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_LO, sum(order_total) as ValeurRepriseAUTRES_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_LO, sum(order_total) as ValeurRepriseAUTRES_LO  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		  
		  
	   case 8:
	   $user_id = "  user_ID in ('levis','levissafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_LE, sum(order_total) as ValeurRepriseAUTRES_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_LE, sum(order_total) as ValeurRepriseAUTRES_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_LE, sum(order_total) as ValeurRepriseAUTRES_LE  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
	   case 9:
	   $user_id = "  user_ID in ('warehousehal','warehousehalsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_HA, sum(order_total) as ValeurRepriseAUTRES_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_HA, sum(order_total) as ValeurRepriseAUTRES_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_HA, sum(order_total) as ValeurRepriseAUTRES_HA  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break; 
		   
		   
	   case 10:
	    $user_id = "  user_ID in ('granby','granbysafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_GR, sum(order_total) as ValeurRepriseAUTRES_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_GR, sum(order_total) as ValeurRepriseAUTRES_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_GR, sum(order_total) as ValeurRepriseAUTRES_GR  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   $user_id = "  user_ID in ('entrepotquebec','quebecsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_QC, sum(order_total) as ValeurRepriseAUTRES_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_QC, sum(order_total) as ValeurRepriseAUTRES_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_QC, sum(order_total) as ValeurRepriseAUTRES_QC  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   case 12:
	   $user_id = "  user_ID in ('montreal','montrealsafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_MTL, sum(order_total) as ValeurRepriseAUTRES_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_MTL, sum(order_total) as ValeurRepriseAUTRES_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_MTL, sum(order_total) as ValeurRepriseAUTRES_MTL  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
	   
	   
	   case 13:
	   $user_id = "  user_ID in ('stemarie','stemariesafe')";
	   //Garanties
	   $QueryRepriseAUTRES_G = "SELECT count(order_num) as NbrRepriseAUTRES_SMB, sum(order_total) as ValeurRepriseAUTRES_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_SMB, sum(order_total) as ValeurRepriseAUTRES_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='reception_commande_entrepot'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Lab
	   $QueryRepriseAUTRES_LAB= "SELECT count(order_num) as NbrRepriseAUTRES_SMB, sum(order_total) as ValeurRepriseAUTRES_SMB  FROM ORDERS 
	   WHERE $user_id AND redo_origin='lab'
	   AND redo_order_num IS NOT NULL   AND $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseAUTRES     = mysqli_query($con,$QueryRepriseAUTRES_G)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseAUTRES_G        = mysqli_fetch_array($resultNbrRepriseAUTRES);
		
	
	//Hors Garanties
	$resultNbrRepriseAUTRES_HG  = mysqli_query($con,$QueryRepriseAUTRES_HG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseAUTRES_HG       = mysqli_fetch_array($resultNbrRepriseAUTRES_HG);	
	
	//Lab
	$resultNbrRepriseAUTRES_LAB = mysqli_query($con,$QueryRepriseAUTRES_LAB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataRepriseAUTRES_LAB      = mysqli_fetch_array($resultNbrRepriseAUTRES_LAB);	
	
 switch($x){ 
	   case 1://Laval
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_LV_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_LV]; 
	   $ValeurRepriseAUTRES_LV_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_LV];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_LV_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LV]; 
	   $ValeurRepriseAUTRES_LV_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_LV];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_LV_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_LV];  
	   $ValeurRepriseAUTRES_LV_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_LV];
	   break;
		 
		 
	   case 2://Drummondville
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_DR_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_DR]; 
	   $ValeurRepriseAUTRES_DR_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_DR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_DR_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_DR]; 
	   $ValeurRepriseAUTRES_DR_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_DR];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_DR_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_DR];  
	   $ValeurRepriseAUTRES_DR_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_DR];
	   break;	 
		 
	   case 3://Chicoutimi
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_CH_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_CH]; 
	   $ValeurRepriseAUTRES_CH_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_CH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_CH_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_CH]; 
	   $ValeurRepriseAUTRES_CH_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_CH];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_CH_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_CH];  
	   $ValeurRepriseAUTRES_CH_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_CH];
	   break; 
		 
	   case 4://Trois-Rivièeres
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_TR_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_TR]; 
	   $ValeurRepriseAUTRES_TR_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_TR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_TR_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_TR]; 
	   $ValeurRepriseAUTRES_TR_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_TR];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_TR_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_TR];  
	   $ValeurRepriseAUTRES_TR_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_TR];
	   break;
		 
	   case 5://Sherbrooke
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_SH_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_SH]; 
	   $ValeurRepriseAUTRES_SH_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_SH];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_SH_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_SH]; 
	   $ValeurRepriseAUTRES_SH_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_SH];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_SH_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_SH];  
	   $ValeurRepriseAUTRES_SH_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_SH];
	   break;
		 
       case 6://Terrebonne
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_TE_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_TE]; 
	   $ValeurRepriseAUTRES_TE_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_TE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_TE_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_TE]; 
	   $ValeurRepriseAUTRES_TE_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_TE];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_TE_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_TE];  
	   $ValeurRepriseAUTRES_TE_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_TE];
	   break;
		 
	   case 7://Longueuil
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_LO_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_LO]; 
	   $ValeurRepriseAUTRES_LO_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_LO];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_LO_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LO]; 
	   $ValeurRepriseAUTRES_LO_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_LO];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_LO_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_LO];  
	   $ValeurRepriseAUTRES_LO_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_LO];	
	   break;
		 
	   case 8://Lévis
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_LE_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_LE]; 
	   $ValeurRepriseAUTRES_LE_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_LE];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_LE_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LE]; 
	   $ValeurRepriseAUTRES_LE_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_LE];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_LE_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_LE];  
	   $ValeurRepriseAUTRES_LE_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_LE];	
	   break;
	 
	   case 9://Halifax
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_HA_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_HA]; 
	   $ValeurRepriseAUTRES_HA_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_HA];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_HA_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_HA]; 
	   $ValeurRepriseAUTRES_HA_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_HA];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_HA_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_HA];  
	   $ValeurRepriseAUTRES_HA_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_HA];	
	   break;
		 
	   case 10://Granby
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_GR_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_GR]; 
	   $ValeurRepriseAUTRES_GR_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_GR];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_GR_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_GR]; 
	   $ValeurRepriseAUTRES_GR_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_GR];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_GR_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_GR];  
	   $ValeurRepriseAUTRES_GR_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_GR];		
	   break;
		 
	   case 11://Québec
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_QC_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_QC]; 
	   $ValeurRepriseAUTRES_QC_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_QC];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_QC_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_QC]; 
	   $ValeurRepriseAUTRES_QC_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_QC];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_QC_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_QC];  
	   $ValeurRepriseAUTRES_QC_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_QC];		
	   break;
	   
	   case 12://Montréal
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_MTL_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_MTL]; 
	   $ValeurRepriseAUTRES_MTL_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_MTL];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_MTL_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_MTL]; 
	   $ValeurRepriseAUTRES_MTL_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_MTL];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_MTL_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_MTL];  
	   $ValeurRepriseAUTRES_MTL_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_MTL];		
	   break;
	   
	   case 13://Sainte-Marie
	   //Reprises 'Garanties'
	   $NbrRepriseAUTRES_SMB_G   	 	= $DataRepriseAUTRES_G[NbrRepriseAUTRES_SMB]; 
	   $ValeurRepriseAUTRES_SMB_G	 	= $DataRepriseAUTRES_G[ValeurRepriseAUTRES_SMB];
	   //Reprises 'Hors Garanties'
	   $NbrRepriseAUTRES_SMB_HG 	 	= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_SMB]; 
	   $ValeurRepriseAUTRES_SMB_HG  	= $DataRepriseAUTRES_HG[ValeurRepriseAUTRES_SMB];
	   //Reprises 'Lab'
	   $NbrRepriseAUTRES_SMB_LAB 		= $DataRepriseAUTRES_LAB[NbrRepriseAUTRES_SMB];  
	   $ValeurRepriseAUTRES_SMB_LAB	= $DataRepriseAUTRES_LAB[ValeurRepriseAUTRES_SMB];		
	   break;
   }//End Switch	
	
}//end FOR


//GARANTIES: 1-Calcul du total de reprises 
$NbrRepriseAUTRES_G = $NbrRepriseAUTRES_LV_G + $NbrRepriseAUTRES_DR_G + $NbrRepriseAUTRES_CH_G + $NbrRepriseAUTRES_TE_G + $NbrRepriseAUTRES_TR_G + $NbrRepriseAUTRES_SH_G + $NbrRepriseAUTRES_LE_G 
+ $NbrRepriseAUTRES_HA_G + $NbrRepriseAUTRES_LO_G + $NbrRepriseAUTRES_GR_G + $NbrRepriseAUTRES_QC_G+ $NbrRepriseAUTRES_MTL_G  + $NbrRepriseAUTRES_SMB_G;
//GARANTIES: 1.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseAUTRES_G = $ValeurRepriseAUTRES_LV_G + $ValeurRepriseAUTRES_DR_G + $ValeurRepriseAUTRES_CH_G + $ValeurRepriseAUTRES_TE_G + $ValeurRepriseAUTRES_TR_G + $ValeurRepriseAUTRES_SH_G + $ValeurRepriseAUTRES_LE_G 
+ $ValeurRepriseAUTRES_HA_G + $ValeurRepriseAUTRES_LO_G + $ValeurRepriseAUTRES_GR_G + $ValeurRepriseAUTRES_QC_G+ $ValeurRepriseAUTRES_MTL_G  + $ValeurRepriseAUTRES_SMB_G;

//LAB: 2-Calcul du total de reprises 
$NbrRepriseAUTRES_LAB = $NbrRepriseAUTRES_LV_LAB + $NbrRepriseAUTRES_DR_LAB + $NbrRepriseAUTRES_CH_LAB + $NbrRepriseAUTRES_TE_LAB + $NbrRepriseAUTRES_TR_LAB + $NbrRepriseAUTRES_SH_LAB + $NbrRepriseAUTRES_LE_LAB 
+ $NbrRepriseAUTRES_HA_LAB + $NbrRepriseAUTRES_LO_LAB + $NbrRepriseAUTRES_GR_LAB + $NbrRepriseAUTRES_QC_LAB + $NbrRepriseAUTRES_MTL_LAB + $NbrRepriseAUTRES_SMB_LAB;
//LAB 2.5-Calcul de la valeur TOTALE($$)
$ValeurRepriseAUTRES_LAB  = $ValeurRepriseAUTRES_LV_LAB + $ValeurRepriseAUTRES_DR_LAB + $ValeurRepriseAUTRES_CH_LAB + $ValeurRepriseAUTRES_TE_LAB + $ValeurRepriseAUTRES_TR_LAB + $ValeurRepriseAUTRES_SH_LAB + $ValeurRepriseAUTRES_LE_LAB 
+ $ValeurRepriseAUTRES_HA_LAB + $ValeurRepriseAUTRES_LO_LAB + $ValeurRepriseAUTRES_GR_LAB + $ValeurRepriseAUTRES_QC_LAB + $ValeurRepriseAUTRES_MTL_LAB + $ValeurRepriseAUTRES_SMB_LAB;

//HORS GARANTIE:  3-Calcul du total de reprises 
$NbrRepriseAUTRES_HG = $NbrRepriseAUTRES_LV_HG + $NbrRepriseAUTRES_DR_HG + $NbrRepriseAUTRES_CH_HG + $NbrRepriseAUTRES_TE_HG + $NbrRepriseAUTRES_TR_HG + $NbrRepriseAUTRES_SH_HG + $NbrRepriseAUTRES_LE_HG 
+ $NbrRepriseAUTRES_HA_HG + $NbrRepriseAUTRES_LO_HG + $NbrRepriseAUTRES_GR_HG + $NbrRepriseAUTRES_QC_HG+ $NbrRepriseAUTRES_MTL_HG  + $NbrRepriseAUTRES_SMB_HG;
//HORS GARANTIEL: 3.5-Calcul de la valeur TOTALE($$) 
$ValeurRepriseAUTRES_HG = $ValeurRepriseAUTRES_LV_HG + $ValeurRepriseAUTRES_DR_HG + $ValeurRepriseAUTRES_CH_HG + $ValeurRepriseAUTRES_TE_HG + $ValeurRepriseAUTRES_TR_HG + $ValeurRepriseAUTRES_SH_HG + $ValeurRepriseAUTRES_LE_HG 
+ $ValeurRepriseAUTRES_HA_HG + $ValeurRepriseAUTRES_LO_HG + $ValeurRepriseAUTRES_GR_HG + $ValeurRepriseAUTRES_QC_HG+ $ValeurRepriseAUTRES_MTL_HG  + $ValeurRepriseAUTRES_SMB_HG;


//Calcul total de jobs fabriqués par le lab pour cette période
$queryTotalAUTRESPeriode = "SELECT count(order_num) as NbrCommandes_AUTRES FROM orders 
WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab NOT IN (3,10,25,69)";
$resultTotalAUTRES = mysqli_query($con,$queryTotalAUTRESPeriode)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTotalAUTRES   = mysqli_fetch_array($resultTotalAUTRES);

//Calcul de la somme des 3 types de reprises				
$TotalRepriseAUTRES=$NbrRepriseAUTRES_G +$NbrRepriseAUTRES_LAB + $NbrRepriseAUTRES_HG;

//Calcul du pourcentage de reprises vs total de commande envoyé a ce fournisseur
$PourcentageRepriseAUTRES =($TotalRepriseAUTRES/$DataTotalAUTRES[NbrCommandes_AUTRES])*100;
$PourcentageRepriseAUTRES = round($PourcentageRepriseAUTRES,2);


$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">AUTRES: P&eacute;riode du $date1 au $date2 
					 <h3> Total fabriqu&eacute; par AUTRES: $DataTotalAUTRES[NbrCommandes_AUTRES] commandes</h3>
					</th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Reprises Garanties ($)</th>
					<th>Reprises Lab ($)</th>
					<th>Reprises Hors Garanties ($)</th>	
				</tr>";

//AUTRES
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseAUTRES_LV_G ($ValeurRepriseAUTRES_LV_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LV_LAB ($ValeurRepriseAUTRES_LV_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LV_HG ($ValeurRepriseAUTRES_LV_HG$)</th>	
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseAUTRES_DR_G ($ValeurRepriseAUTRES_DR_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_DR_LAB ($ValeurRepriseAUTRES_DR_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_DR_HG ($ValeurRepriseAUTRES_DR_HG$)</th>	
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseAUTRES_CH_G ($ValeurRepriseAUTRES_CH_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_CH_LAB ($ValeurRepriseAUTRES_CH_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_CH_HG ($ValeurRepriseAUTRES_CH_HG$)</th>	
</tr>

<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseAUTRES_TE_G ($ValeurRepriseAUTRES_TE_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_TE_LAB ($ValeurRepriseAUTRES_TE_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_TE_HG ($ValeurRepriseAUTRES_TE_HG$)</th>	
</tr>

<tr>
	<th>Trois-Rivi&egraveres</th>
	<th align=\"center\">$NbrRepriseAUTRES_TR_G ($ValeurRepriseAUTRES_TR_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_TR_LAB ($ValeurRepriseAUTRES_TR_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_TR_HG ($ValeurRepriseAUTRES_TR_HG$)</th>	
</tr>

<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseAUTRES_SH_G ($ValeurRepriseAUTRES_SH_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_SH_LAB ($ValeurRepriseAUTRES_SH_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_SH_HG ($ValeurRepriseAUTRES_SH_HG$)</th>	
</tr>

<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseAUTRES_LE_G ($ValeurRepriseAUTRES_LE_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LE_LAB ($ValeurRepriseAUTRES_LE_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LE_HG ($ValeurRepriseAUTRES_LE_HG$)</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseAUTRES_HA_G ($ValeurRepriseAUTRES_HA_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_HA_LAB ($ValeurRepriseAUTRES_HA_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_HA_HG ($ValeurRepriseAUTRES_HA_HG$)</th>			
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseAUTRES_LO_G ($ValeurRepriseAUTRES_LO_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LO_LAB ($ValeurRepriseAUTRES_LO_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LO_HG ($ValeurRepriseAUTRES_LO_HG$)</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseAUTRES_GR_G ($ValeurRepriseAUTRES_GR_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_GR_LAB ($ValeurRepriseAUTRES_GR_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_GR_HG ($ValeurRepriseAUTRES_GR_HG$)</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseAUTRES_QC_G ($ValeurRepriseAUTRES_QC_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_QC_LAB ($ValeurRepriseAUTRES_QC_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_QC_HG ($ValeurRepriseAUTRES_QC_HG$)</th>		
</tr>
<tr>
	<th>Montr&eacute;al</th>
	<th align=\"center\">$NbrRepriseAUTRES_MTL_G ($ValeurRepriseAUTRES_MTL_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_MTL_LAB ($ValeurRepriseAUTRES_MTL_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_MTL_HG ($ValeurRepriseAUTRES_MTL_HG$)</th>				
</tr>

<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseAUTRES_SMB_G ($ValeurRepriseAUTRES_SMB_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_SMB_LAB ($ValeurRepriseAUTRES_SMB_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_SMB_HG ($ValeurRepriseAUTRES_SMB_HG$)</th>			
</tr>

<tr bgcolor=\"#B1DEFE\">
	<th>TOTAUX</th>
	<th align=\"center\">$NbrRepriseAUTRES_G ($ValeurRepriseAUTRES_G$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_LAB ($ValeurRepriseAUTRES_LAB$)</th>
	<th align=\"center\">$NbrRepriseAUTRES_HG ($ValeurRepriseAUTRES_HG$)</th>
</tr>

<tr bgcolor=\"#89DA59\">
	<th>Analyse</th>
	<th align=\"center\">$NbrRepriseAUTRES_G +$NbrRepriseAUTRES_LAB + $NbrRepriseAUTRES_HG = $TotalRepriseAUTRES </th>
	<th colspan=\"2\" align=\"center\"> $TotalRepriseAUTRES / $DataTotalAUTRES[NbrCommandes_AUTRES] = $PourcentageRepriseAUTRES%</th>
</tr>
</table>";
*/












//PARTIE 3: DETAIL PAR SUCCURSALE PAR FOURNISSEURS AVEC RAISONS DE REPRISES
//3.1: DIRECTLAB
/*
$prescript_lab = 3;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1  :$Detail_Succursale = "LAVAL"; 
		   		$USER_ID = "('laval','lavalsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		        $TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   
	   case 2  :$Detail_Succursale = "DRUMMONDVILLE"; 	
		   		$USER_ID = "('entrepotdr','safedr')";
		   		$TotalShipped 	   = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		    	$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL          AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   	    $queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = 3 AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 3  :$Detail_Succursale = "CHICOUTIMI"; 
		   		$USER_ID = "('chicoutimi','chicoutimisafe')";
		   		$TotalShipped	   = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise	   = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
       break;	 
	
	   case 4  :$Detail_Succursale = "TROIS-RIVIERES";	
		   		$USER_ID = "('entrepotifc','chicoutimisafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL        AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'";
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
		   	 	$USER_ID = "('entrepotifc','entrepotsafe')";
	   break; 
	 
	   case 5  :$Detail_Succursale = "SHERBROOKE";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 	
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND              prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0  ";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
	   break;
	
	   case 6  :$Detail_Succursale = "TERREBONNE";
		   		$USER_ID = "('terrebonne','terrebonnesafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL              AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		  		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
	   break;
	
	   case 7  :$Detail_Succursale = "LONGUEUIL";
		   		$USER_ID = "('longueuil','longueuilsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   	    $TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL";
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  ('longueuil','longueuilsafe') AND redo_order_num<>0 ";
	   break;
		   
	   case 8  :$Detail_Succursale = "LEVIS"; 
		   		$USER_ID = "('levis','levissafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 9  :$Detail_Succursale = "HALIFAX"; 
		   		$USER_ID = "('warehousehal','warehousehalsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab =$prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 10 :$Detail_Succursale = "GRANBY"; 
		   		$USER_ID = "('granby','granbysafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 	
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID  AND redo_order_num<>0 ";
	   break;
		   
	 
		   
	   case 11 :$Detail_Succursale = "QUEBEC"; 
		   		$USER_ID = "('entrepotquebec','quebecsafe')";
		   		$TotalShipped = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID            AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
	   
	   
	    case 12 :$Detail_Succursale = "MONTREAL ZT1"; 
		   		$USER_ID = "('montreal','montrealsafe')";
		   		$TotalShipped = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID            AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
   
   }//End Switch
	
	
	$resultTotalShipped = mysqli_query($con,$TotalShipped)		or die  ('I cannot select items because: ' . mysqli_error($con) .$TotalShipped );
	$DataTotalShipped   = mysqli_fetch_array($resultTotalShipped);
	
	$resultTotalShipped_DLAB = mysqli_query($con,$TotalShipped_DLAB)		or die  ('I cannot select items because: ' . mysqli_error($con) .$TotalShipped_DLAB );
	$DataTotalShipped_DLAB   = mysqli_fetch_array($resultTotalShipped_DLAB);
		
	$resultTotalReprise = mysqli_query($con,$TotalReprise)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise);
	$DataTotalReprises  = mysqli_fetch_array($resultTotalReprise);
	
	$resultTotalReprise_DLAB = mysqli_query($con,$TotalReprise_DLAB)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_DLAB);
	$DataTotalReprises_DLAB  = mysqli_fetch_array($resultTotalReprise_DLAB);	
	
	
	$resultTotalReprise_DLAB_G = mysqli_query($con,$TotalReprise_DLAB_G)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_DLAB_G);
	$DataTotalReprises_DLAB_G  = mysqli_fetch_array($resultTotalReprise_DLAB_G);	
	
	$resultTotalReprise_DLAB_HG = mysqli_query($con,$TotalReprise_DLAB_HG)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_DLAB_HG);
	$DataTotalReprises_DLAB_HG  = mysqli_fetch_array($resultTotalReprise_DLAB_HG);	
		
	$CommandeOriginalesDurantPeriode = $DataTotalShipped[Totalshipped]-$DataTotalReprises[TotalReprise];
	$PourcentageReprise =  ($DataTotalReprises[TotalReprise]/$CommandeOriginalesDurantPeriode)*100;
	$PourcentageReprise = round($PourcentageReprise,2);
	
	
	//3.1 Afficher detail Généraux de la succursale
	$message.="<br><br><br><table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">Succursale: $Detail_Succursale: P&eacute;riode du $date1 au $date2. Statistiques Globales (incluant tous les fabriquants)</th>
				</tr>

				
				<tr>
					<th width=\"625\">Nombre de commandes durant la p&eacute;riode (Inc. reprises):</th>
					<td width=\"100\">$DataTotalShipped[Totalshipped]</td>
				</tr>
				<tr>
					<th width=\"625\">Nombre de reprises:</th>
					<td width=\"100\">$DataTotalReprises[TotalReprise]</td>
				</tr>
				<tr>
					<th width=\"625\">Nombre de commandes originales (Exc. reprises):</th>
					<td width=\"100\">$CommandeOriginalesDurantPeriode</td>
				</tr>
				<tr><td colspan=\"2\"></td></tr>";
	
	
	
	
	
	
	
	
	
	
//Partie Dlab
	$CommandeOriginalesDurantPeriode_DLAB = $DataTotalShipped_DLAB[Totalshipped]-$DataTotalReprises_DLAB[TotalReprise_DLAB];
	$PourcentageReprise_DLAB =  ($DataTotalReprises_DLAB[TotalReprise_DLAB]/$CommandeOriginalesDurantPeriode_DLAB)*100;
	$PourcentageReprise_DLAB =  round($PourcentageReprise_DLAB,2);
	
	$message.="	<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#F1F71D\" align=\"center\" colspan=\"10\">PARTIE DIRECTLAB</th>
				</tr>

				
				<tr>
					<th  bgcolor=\"#F1F71D\" width=\"625\">Nombre de commandes durant la p&eacute;riode</th>
					<td bgcolor=\"#F1F71D\" width=\"100\">$DataTotalShipped_DLAB[Totalshipped]</td>
				</tr>
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"625\">Nombre reprise:</th>
					<td bgcolor=\"#F1F71D\" width=\"100\">$DataTotalReprises_DLAB[TotalReprise_DLAB]</td>
				</tr>
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"625\">Nombre de commandes originales</th>
					<td bgcolor=\"#F1F71D\" width=\"100\">$CommandeOriginalesDurantPeriode_DLAB</td>
				</tr>
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"625\">Pourcentage de reprise Dlab (reprises sur le total de jobs fait par Dlab)</th>
					<td bgcolor=\"#F1F71D\" width=\"100\">$PourcentageReprise_DLAB%</td>
				</tr>
				
				<tr>
					<th bgcolor=\"#B2B706\" width=\"625\">Garanties</th>
					<td bgcolor=\"#B2B706\" width=\"100\">$DataTotalReprises_DLAB_G[TotalReprise_DLAB_G]</td>
				</tr>
				<tr>
					<th  bgcolor=\"#F79E9F\" width=\"625\">Hors Garanties:</th>
					<td  bgcolor=\"#F79E9F\" width=\"100\">$DataTotalReprises_DLAB_HG[TotalReprise_DLAB_HG]</td>
				</tr>
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"625\">$ Net des reprises Hors Garanties</th>
					<td bgcolor=\"#F1F71D\" width=\"100\">$DataTotalReprises_DLAB_HG[ValeurRepriseDLAB_HG]$</td>
				</tr></table><br>";
				
			   
	
	  //Aller cherchers les differentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonDLAB=mysqli_query($con,$queryRedoReasonDLAB)		or die ( "Query failed: " . mysqli_error($con));
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#F1F71D\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonDLAB  = mysqli_fetch_array($resultRedoReasonDLAB)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]";
		$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
		$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);

		
		$queryRedo_DLAB_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3 AND redo_reason_id = $DataRedoReasonDLAB[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_DLAB_G.'<br>';
		$resultRedo_DLAB_G = mysqli_query($con,$queryRedo_DLAB_G)		or die ( "Query failed: " . mysqli_error($con));
		$DataRedo_DLAB_G   = mysqli_fetch_array($resultRedo_DLAB_G);
		
		
		$queryRedo_DLAB_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_DLAB_HG.'<br><br><br>';
		$resultRedo_DLAB_HG = mysqli_query($con,$queryRedo_DLAB_HG)		or die ( "Query failed: " . mysqli_error($con));
		$DataRedo_DLAB_HG   = mysqli_fetch_array($resultRedo_DLAB_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_DLAB_HG[NbrRedoHorsGaranties] + $DataRedo_DLAB_G[NbrRedoGaranties])/$DataTotalReprises_DLAB[TotalReprise_DLAB])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		
		//Afficher le contenu de l'array
		
		 $message.="<tr>
						<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_DLAB_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_DLAB_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie DIRECTLAB
	
}//End FOR
	
	
	
	
	
	
	
	
//Partie Swiss
	
	
//3.1: SWISSCOAT
$prescript_lab = 10;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1  :$Detail_Succursale = "LAVAL"; 
		   		$USER_ID = "('laval','lavalsafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   
	   case 2  :$Detail_Succursale = "DRUMMONDVILLE"; 	
		   		$USER_ID = "('entrepotdr','safedr')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL          AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   	    $queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = 3 AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 3  :$Detail_Succursale = "CHICOUTIMI"; 
		   		$USER_ID = "('chicoutimi','chicoutimisafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
       break;	 
	
	   case 4  :$Detail_Succursale = "TROIS-RIVIERES";	
		   		$USER_ID = "('entrepotifc','chicoutimisafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL        AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'";
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
		   	 	$USER_ID = "('entrepotifc','entrepotsafe')";
	   break; 
	 
	   case 5  :$Detail_Succursale = "SHERBROOKE";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND              prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0  ";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
	   break;
	
	   case 6  :$Detail_Succursale = "TERREBONNE";
		   		$USER_ID = "('terrebonne','terrebonnesafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL              AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		  		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
	   break;
	
	   case 7  :$Detail_Succursale = "LONGUEUIL";
		   		$USER_ID = "('longueuil','longueuilsafe')";
		   	    $TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  ('longueuil','longueuilsafe') AND redo_order_num<>0 ";
	   break;
		   
	   case 8  :$Detail_Succursale = "LEVIS"; 
		   		$USER_ID = "('levis','levissafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 9  :$Detail_Succursale = "HALIFAX"; 
		   		$USER_ID = "('warehousehal','warehousehalsafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab =$prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
	   break;
		   
	   case 10 :$Detail_Succursale = "GRANBY"; 
		   		$USER_ID = "('granby','granbysafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID  AND redo_order_num<>0 ";
	   break;
		   
	   case 11 :$Detail_Succursale = "-E"; 
		   		$USER_ID = "('gfd')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
	   break;
		   
	   case 12 :$Detail_Succursale = "QUEBEC"; 
		   		$USER_ID = "('entrepotquebec','quebecsafe')";
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   break;
   
   }//End Switch
	
		
	$resultTotalShipped_SWISS = mysqli_query($con,$TotalShipped_SWISS)		or die  ('I cannot select items because: ' . mysqli_error($con) .$TotalShipped_SWISS );
	$DataTotalShipped_SWISS   = mysqli_fetch_array($resultTotalShipped_SWISS);
			
	$resultTotalReprise_SWISS = mysqli_query($con,$TotalReprise_SWISS)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_SWISS);
	$DataTotalReprises_SWISS  = mysqli_fetch_array($resultTotalReprise_SWISS);	
	
	
	$resultTotalReprise_SWISS_G = mysqli_query($con,$TotalReprise_SWISS_G)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_SWISS_G);
	$DataTotalReprises_SWISS_G  = mysqli_fetch_array($resultTotalReprise_SWISS_G);	
	
	$resultTotalReprise_SWISS_HG = mysqli_query($con,$TotalReprise_SWISS_HG)		or die  ('I cannot select items because: ' . mysqli_error($con) . $TotalReprise_SWISS_HG);
	$DataTotalReprises_SWISS_HG  = mysqli_fetch_array($resultTotalReprise_SWISS_HG);	
		
	//$CommandeOriginalesDurantPeriode = $DataTotalShipped[Totalshipped]-$DataTotalReprises[TotalReprise];
	//$PourcentageReprise =  ($DataTotalReprises[TotalReprise]/$CommandeOriginalesDurantPeriode)*100;
	//$PourcentageReprise = round($PourcentageReprise,2);
	
	$CommandeOriginalesDurantPeriode_SWISS = $DataTotalShipped_SWISS[Totalshipped]-$DataTotalReprises_SWISS[TotalReprise_SWISS];
	$PourcentageReprise_SWISS =  ($DataTotalReprises_SWISS[TotalReprise_SWISS]/$CommandeOriginalesDurantPeriode_SWISS)*100;
	$PourcentageReprise_SWISS =  round($PourcentageReprise_SWISS,2);
	
	$message.="	<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#AADCA9\" align=\"center\" colspan=\"10\">PARTIE SWISSCOAT:$Detail_Succursale</th>
				</tr>

				
				<tr>
					<th  bgcolor=\"#AADCA9\" width=\"625\">Nombre de commandes durant la p&eacute;riode</th>
					<td bgcolor=\"#AADCA9\" width=\"100\">$DataTotalShipped_SWISS[Totalshipped]</td>
				</tr>
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"625\">Nombre reprise:</th>
					<td bgcolor=\"#AADCA9\" width=\"100\">$DataTotalReprises_SWISS[TotalReprise_SWISS]</td>
				</tr>
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"625\">Nombre de commandes originales</th>
					<td bgcolor=\"#AADCA9\" width=\"100\">$CommandeOriginalesDurantPeriode_SWISS</td>
				</tr>
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"625\">Pourcentage de reprise</th>
					<td bgcolor=\"#AADCA9\" width=\"100\">$PourcentageReprise_SWISS%</td>
				</tr>
				
				<tr>
					<th bgcolor=\"#B2B706\" width=\"625\">Garanties</th>
					<td bgcolor=\"#B2B706\" width=\"100\">$DataTotalReprises_SWISS_G[TotalReprise_SWISS_G]</td>
				</tr>
				<tr>
					<th  bgcolor=\"#F79E9F\" width=\"625\">Hors Garanties:</th>
					<td  bgcolor=\"#F79E9F\" width=\"100\">$DataTotalReprises_SWISS_HG[TotalReprise_SWISS_HG]</td>
				</tr>
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"625\">$ Net des reprises Hors Garanties</th>
					<td bgcolor=\"#AADCA9\" width=\"100\">$DataTotalReprises_SWISS_HG[ValeurRepriseSWISS_HG]$</td>
				</tr></table><br>";
				
			   
	
	  //Aller cherchers les diffrentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonSWISS=mysqli_query($con,$queryRedoReasonSWISS)		or die ( "Query failed: " . mysqli_error($con));
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#AADCA9\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonSWISS  = mysqli_fetch_array($resultRedoReasonSWISS)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]";
		$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
		$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);

		
		$queryRedo_SWISS_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3 AND redo_reason_id = $DataRedoReasonSWISS[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_SWISS_G.'<br>';
		$resultRedo_SWISS_G = mysqli_query($con,$queryRedo_SWISS_G)		or die ( "Query failed: " . mysqli_error($con));
		$DataRedo_SWISS_G   = mysqli_fetch_array($resultRedo_SWISS_G);
		
		
		$queryRedo_SWISS_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_SWISS_HG.'<br><br><br>';
		$resultRedo_SWISS_HG = mysqli_query($con,$queryRedo_SWISS_HG)		or die ( "Query failed: " . mysqli_error($con));
		$DataRedo_SWISS_HG   = mysqli_fetch_array($resultRedo_SWISS_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_SWISS_HG[NbrRedoHorsGaranties] + $DataRedo_SWISS_G[NbrRedoGaranties])/$DataTotalReprises_SWISS[TotalReprise_SWISS])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		
		//Afficher le contenu de l'array
		
		 $message.="<tr>
						<td bgcolor=\"#AADCA9\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SWISS_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SWISS_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#AADCA9\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie SWISSCOAT
	
	
}//End FOR
*/

















































/*
//PARTIE 4: Part 4 Raisons de reprises (détaillée) PAR Fournisseur
//4.0
$NombreFournisseur = 4;

//RAISONS DE REPRISES A EXCLURE DE CE RAPPORT;
$REDO_REASON_NOT_IN = "  redo_reason_id NOT IN (0,42,43,44,50,61,64,65,66) ";


//NOT REDOS
//#64:Frame not available anymore 
//#65:Garantie a tout casser
//#66:Not a redo, second pair

//EDGING PART BELOW
//#42:Lab Edging Error
//#43:Customer Edging Error
//#44:Bad Remote Edging
//#50:Product | Edging Error
//#61:CL EDGING ERROR



//Définire la palette de couleur:
$Couleur_PETAL		 	="#F98866";//Reprises Garanties(Nombre, % reprise, valeur$$)
$Couleur_POPPY		 	="#FF420E";
$Couleur_STEM		 	="#80BD9E";
$Couleur_SPRINGGREEN 	="#89DA59";//Total de vente, $ Achats Intercos
$Couleur_JAUNATRE 		="#fbf579";//Nombre de reprise, % de reprise, valeur net des reprises.
$Couleur_BLEU_PALE		="#569DBD";//Bleuté
$LargeurTableaux		="855";
//Parcourir les fournisseurs [SWISS, HKO, GKB, Dlab(St.Catharines)]
for ($x = 1; $x <= $NombreFournisseur; $x++) {
   switch($x){
	 
	 case 1  :$Detail_Succursale = "Redos SWISSCOAT [Without Lab Edging Error] [$date1 - $date2]"; 		
				$prescript_lab = 10;
				$TotalReprise_SWISS 	= "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				AND prescript_lab = $prescript_lab AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_SWISS 	= mysqli_query($con,$TotalReprise_SWISS)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_SWISS);
				$DataTotalReprises_SWISS 	= mysqli_fetch_array($resultTotalReprise_SWISS);	
				
				$TotalJobSwissSansRedo = "SELECT count(order_num) as TotalJob_SWISS_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = $prescript_lab   AND redo_order_num IS null"; 	
				echo '<br>Query:'. $TotalJobSwissSansRedo.'<br>';
				$resultJobSwissSansRedo 	= mysqli_query($con,$TotalJobSwissSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSwissSansRedo);
				$DatatotalJob_SWISSSansRedo = mysqli_fetch_array($resultJobSwissSansRedo);	
				
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSWISS  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSWISS=mysqli_query($con,$queryRedoReasonSWISS)		or die ( "Query  4321 failed:". $queryRedoReasonSWISS.  mysqli_error($con));
				
				$PercentageSwiss=($DataTotalReprises_SWISS[TotalReprise_SWISS]/$DatatotalJob_SWISSSansRedo[TotalJob_SWISS_SansRedo])*100;
				$PercentageSwiss  = number_format($PercentageSwiss, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total Redo: <b>$DataTotalReprises_SWISS[TotalReprise_SWISS]</b> | Total Original: <b>$DatatotalJob_SWISSSansRedo[TotalJob_SWISS_SansRedo]</b>
				 | Percentage:<b>$PercentageSwiss%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Reason</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Redo Store</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Redo Warranty</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Redo Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSwiss 		= 0;
				$SommeReprisesHorsGarantiesSwiss 	= 0;
				$SommeReprisesLabSwiss 				= 0;
				
				while($DataRedoReasonSWISS  = mysqli_fetch_array($resultRedoReasonSWISS)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);

					$queryRedo_SWISS_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab
					AND redo_reason_id  = $DataRedoReasonSWISS[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SWISS_G = mysqli_query($con,$queryRedo_SWISS_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SWISS_G   = mysqli_fetch_array($resultRedo_SWISS_G);
					//On garde la trace du total
					$SommeReprisesGarantiesSwiss  = $SommeReprisesGarantiesSwiss +$DataRedo_SWISS_G[NbrRedoGaranties];	
						
					$queryRedo_SWISS_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SWISS_HG = mysqli_query($con,$queryRedo_SWISS_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SWISS_HG   = mysqli_fetch_array($resultRedo_SWISS_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSwiss  = $SommeReprisesHorsGarantiesSwiss +$DataRedo_SWISS_HG[NbrRedoHorsGaranties];	
					
					
					$queryRedo_SWISS_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2'  AND prescript_lab=$prescript_lab AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SWISS_LAB = mysqli_query($con,$queryRedo_SWISS_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SWISS_LAB	  = mysqli_fetch_array($resultRedo_SWISS_LAB);
					//On garde la trace du total
					$SommeReprisesLabSwiss  = $SommeReprisesLabSwiss +$DataRedo_SWISS_LAB[NbrRedoLAB];
					
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SWISS_HG[NbrRedoHorsGaranties] + $DataRedo_SWISS_G[NbrRedoGaranties]+$DataRedo_SWISS_LAB[NbrRedoLAB])/$DataTotalReprises_SWISS[TotalReprise_SWISS])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$TotalPourCetteRaison = $DataRedo_SWISS_HG[NbrRedoHorsGaranties] + $DataRedo_SWISS_G[NbrRedoGaranties] + $DataRedo_SWISS_LAB[NbrRedoLAB];
				
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SWISS_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SWISS_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SWISS_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\">$TotalPourCetteRaison</td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSwiss + $SommeReprisesGarantiesSwiss + $SommeReprisesLabSwiss;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSwiss</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSwiss</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSwiss</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";
	   break;
		  
		  
	   case 2  :$Detail_Succursale = "Redos HKO [Without Lab Edging Error] [$date1 - $date2]";    				
				$prescript_lab = 25;
				
		   		$TotalReprise_HKO 	= "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL  AND prescript_lab = $prescript_lab  AND $REDO_REASON_NOT_IN   AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				
		   		$TotalReprise_HKO_G  	= "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab  AND $REDO_REASON_NOT_IN  AND redo_origin='retour_client'"; 
				
		   		$TotalReprise_HKO_HG 	= "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL  AND $REDO_REASON_NOT_IN  AND prescript_lab = $prescript_lab AND redo_origin='reception_commande_entrepot'"; 

		   		$queryRedoReasonHKO  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN     ORDER BY redo_reason_id";
				
				$resultTotalReprise_HKO 	= mysqli_query($con,$TotalReprise_HKO)		or die  ('I cannot select items because 55338: ' . mysqli_error($con) . $TotalReprise_HKO);
				$DataTotalReprises_HKO 		= mysqli_fetch_array($resultTotalReprise_HKO);	
				$resultTotalReprise_HKO_G 	= mysqli_query($con,$TotalReprise_HKO_G)		or die  ('I cannot select items because 114573: ' . mysqli_error($con) . $TotalReprise_HKO_G);
				$DataTotalReprises_HKO_G  	= mysqli_fetch_array($resultTotalReprise_HKO_G);	
				$resultTotalReprise_HKO_HG 	= mysqli_query($con,$TotalReprise_HKO_HG)		or die  ('I cannot select items because 1231123: ' . mysqli_error($con) . $TotalReprise_HKO_HG);
				$DataTotalReprises_HKO_HG  	= mysqli_fetch_array($resultTotalReprise_HKO_HG);

				$TotalJobHKOSansRedo = "SELECT count(order_num) as TotalJob_HKO_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = $prescript_lab AND redo_order_num IS null"; 	
				$resultJobHKOSansRedo 	= mysqli_query($con,$TotalJobHKOSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobHKOSansRedo);
				$DatatotalJob_HKOSansRedo = mysqli_fetch_array($resultJobHKOSansRedo);	

				//Aller cherchers les differentes raisons de reprises pour ce fabriquant et la succursale choisie
				$resultRedoReasonHKO=mysqli_query($con,$queryRedoReasonHKO)		or die ( "Query  4321 failed:". $queryRedoReasonHKO.  mysqli_error($con));
				
				$PercentageHKO=($DataTotalReprises_HKO[TotalReprise_HKO]/$DatatotalJob_HKOSansRedo[TotalJob_HKO_SansRedo])*100;
				$PercentageHKO  = number_format($PercentageHKO, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total Redo: <b>$DataTotalReprises_HKO[TotalReprise_HKO]</b> | Total Original: <b>$DatatotalJob_HKOSansRedo[TotalJob_HKO_SansRedo]</b>
				 | Percentage:<b>$PercentageHKO%</b></td>
				 </tr>
							
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Reason</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Redo Store</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Redo Warranty</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Redo Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesHKO 		= 0;
				$SommeReprisesHorsGarantiesHKO 	= 0;
				$SommeReprisesLabHKO 			= 0;
				while($DataRedoReasonHKO  = mysqli_fetch_array($resultRedoReasonHKO)){
					

					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonHKO[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);

					$queryRedo_HKO_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab
					AND redo_reason_id = $DataRedoReasonHKO[redo_reason_id] AND $REDO_REASON_NOT_IN 
					AND redo_origin='retour_client'";
					$resultRedo_HKO_G = mysqli_query($con,$queryRedo_HKO_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_HKO_G   = mysqli_fetch_array($resultRedo_HKO_G);
					//On garde la trace du total
					$SommeReprisesGarantiesHKO  = $SommeReprisesGarantiesHKO +$DataRedo_HKO_G[NbrRedoGaranties];
					
					
					$queryRedo_HKO_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonHKO[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND $REDO_REASON_NOT_IN   AND prescript_lab=$prescript_lab  
					AND redo_origin='reception_commande_entrepot'";
					$resultRedo_HKO_HG = mysqli_query($con,$queryRedo_HKO_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_HKO_HG   = mysqli_fetch_array($resultRedo_HKO_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesHKO  = $SommeReprisesHorsGarantiesHKO +$DataRedo_HKO_HG[NbrRedoHorsGaranties];	


					$queryRedo_HKO_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonHKO[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab  AND $REDO_REASON_NOT_IN 
					AND redo_origin='lab'";
					$resultRedo_HKO_LAB = mysqli_query($con,$queryRedo_HKO_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_HKO_LAB	  = mysqli_fetch_array($resultRedo_HKO_LAB);
					//On garde la trace du total
					$SommeReprisesLabHKO  = $SommeReprisesLabHKO +$DataRedo_HKO_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_HKO_HG[NbrRedoHorsGaranties] + $DataRedo_HKO_G[NbrRedoGaranties]+$DataRedo_HKO_LAB[NbrRedoLAB])/$DataTotalReprises_HKO[TotalReprise_HKO])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$TotalPourCetteRaison = $DataRedo_HKO_HG[NbrRedoHorsGaranties] + $DataRedo_HKO_G[NbrRedoGaranties] + $DataRedo_HKO_LAB[NbrRedoLAB];
					
					//Afficher le contenu de l'array
					if ($SommeRedopourCetteRaison<>0){
					$message.="<tr>
									<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]</td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_HKO_HG[NbrRedoHorsGaranties]</td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_HKO_G[NbrRedoGaranties]</td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_HKO_LAB[NbrRedoLAB]</td>
									<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\">$TotalPourCetteRaison</td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
								</tr>";
					}//End IF
				
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesHKO + $SommeReprisesGarantiesHKO + $SommeReprisesLabHKO;
				
				$message.="<tr>
				<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
				<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><b>$SommeReprisesHorsGarantiesHKO<b/></td>
				<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><b>$SommeReprisesGarantiesHKO</b></td>
				<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><b>$SommeReprisesLabHKO</b></td>
				<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
				<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
				</tr>";
				
				 $message.="</table><br><br>";			
	   break;
		 
		 
	  case 3  :$Detail_Succursale = "Redos GKB [Without Lab Edging Error] [$date1 - $date2]"; 		
				$prescript_lab = 69;
				
		   		$TotalReprise_GKB 		= "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN    AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				
				$TotalReprise_GKB_G  	= "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL  AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN    AND redo_origin='retour_client'"; 
		   		
				$TotalReprise_GKB_HG 	= "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN   AND redo_origin<>'retour_client'"; 
		   		
				$queryRedoReasonGKB  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND $REDO_REASON_NOT_IN  ORDER BY redo_reason_id";
				
				$TotalJobGKBSansRedo = "SELECT count(order_num) as TotalJob_GKB_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = $prescript_lab AND redo_order_num IS null"; 	
				$resultJobGKBSansRedo 	= mysqli_query($con,$TotalJobGKBSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobGKBSansRedo);
				$DatatotalJob_GKBSansRedo = mysqli_fetch_array($resultJobGKBSansRedo);	
				
				$resultTotalReprise_GKB = mysqli_query($con,$TotalReprise_GKB)		or die  ('I cannot select items because 5521338: ' . mysqli_error($con) . $TotalReprise_GKB);
				$DataTotalReprises_GKB = mysqli_fetch_array($resultTotalReprise_GKB);	
				$resultTotalReprise_GKB_G = mysqli_query($con,$TotalReprise_GKB_G)		or die  ('I cannot select items because 1145273: ' . mysqli_error($con) . $TotalReprise_GKB_G);
				$DataTotalReprises_GKB_G  = mysqli_fetch_array($resultTotalReprise_GKB_G);	
				$resultTotalReprise_GKB_HG = mysqli_query($con,$TotalReprise_GKB_HG)		or die  ('I cannot select items because 12321123: ' . mysqli_error($con) . $TotalReprise_GKB_HG);
				$DataTotalReprises_GKB_HG  = mysqli_fetch_array($resultTotalReprise_GKB_HG);
				
				//Aller cherchers les differentes raisons de reprises pour ce fabriquant et la succursale choisie
				$resultRedoReasonGKB=mysqli_query($con,$queryRedoReasonGKB)		or die ( "Query  4321 failed:". $queryRedoReasonGKB.  mysqli_error($con));
				
				$PercentageGKB=($DataTotalReprises_GKB[TotalReprise_GKB]/$DatatotalJob_GKBSansRedo[TotalJob_GKB_SansRedo])*100;
				$PercentageGKB  = number_format($PercentageGKB, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total Redo: <b>$DataTotalReprises_GKB[TotalReprise_GKB]</b> | Total Original: <b>$DatatotalJob_GKBSansRedo[TotalJob_GKB_SansRedo]</b>
				 | Percentage:<b>$PercentageGKB%</b></td>
				 </tr>
				
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Reason</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Redo Store</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Redo Warranty</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Redo Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesGKB 		= 0;
				$SommeReprisesHorsGarantiesGKB 	= 0;
				$SommeReprisesLabGKB 			= 0;
				while($DataRedoReasonGKB  = mysqli_fetch_array($resultRedoReasonGKB)){
					

					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonGKB[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);

					$queryRedo_GKB_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab
					AND redo_reason_id = $DataRedoReasonGKB[redo_reason_id] AND $REDO_REASON_NOT_IN
					AND redo_origin='retour_client'";
					//echo '<br>'.$queryRedo_GKB_G.'<br>';
					$resultRedo_GKB_G = mysqli_query($con,$queryRedo_GKB_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_GKB_G   = mysqli_fetch_array($resultRedo_GKB_G);
					//On garde la trace du total
					$SommeReprisesGarantiesGKB  = $SommeReprisesGarantiesGKB +$DataRedo_GKB_G[NbrRedoGaranties];
			
					
					$queryRedo_GKB_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonGKB[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab  AND $REDO_REASON_NOT_IN
					AND redo_origin='reception_commande_entrepot'";
					//echo '<br>'.$queryRedo_GKB_HG.'<br><br><br>';
					$resultRedo_GKB_HG = mysqli_query($con,$queryRedo_GKB_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_GKB_HG   = mysqli_fetch_array($resultRedo_GKB_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesGKB  = $SommeReprisesHorsGarantiesGKB +$DataRedo_GKB_HG[NbrRedoHorsGaranties];	


					$queryRedo_GKB_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonGKB[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab AND $REDO_REASON_NOT_IN
					AND redo_origin='lab'";
					$resultRedo_GKB_LAB = mysqli_query($con,$queryRedo_GKB_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_GKB_LAB	  = mysqli_fetch_array($resultRedo_GKB_LAB);
					//On garde la trace du total
					$SommeReprisesLabGKB  = $SommeReprisesLabGKB +$DataRedo_GKB_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_GKB_HG[NbrRedoHorsGaranties] + $DataRedo_GKB_G[NbrRedoGaranties] + $DataRedo_GKB_LAB[NbrRedoLAB])/$DataTotalReprises_GKB[TotalReprise_GKB])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$TotalPourCetteRaison = $DataRedo_GKB_HG[NbrRedoHorsGaranties] + $DataRedo_GKB_G[NbrRedoGaranties] + $DataRedo_GKB_LAB[NbrRedoLAB];
					
					//Afficher le contenu de l'array
					if ($SommeRedopourCetteRaison<>0){
					$message.="<tr>
									<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]</td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_GKB_HG[NbrRedoHorsGaranties]</td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_GKB_G[NbrRedoGaranties]</td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_GKB_LAB[NbrRedoLAB]</td>
									<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\">$TotalPourCetteRaison</td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
								</tr>";
					}//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesGKB + $SommeReprisesGarantiesGKB + $SommeReprisesLabGKB;
				
				$message.="<tr>
				<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
				<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><b>$SommeReprisesHorsGarantiesGKB</b></td>
				<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><b>$SommeReprisesGarantiesGKB</b></td>
				<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><b>$SommeReprisesLabGKB</b></td>
				<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
				<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
				</tr>";
				 $message.="</table><br><br>";
		break;
	
	
	
	
	   case 4  :$Detail_Succursale = "Redos DLAB [Without Lab Edging Error] [$date1 - $date2]"; 
				
				//echo '<br>Passe case 4:DLAB<br>';	   		
				$prescript_lab = 3;
		   		$TotalReprise_DLAB 		= "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN    AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
		   		
				$TotalReprise_DLAB_G  	= "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN 
				AND redo_origin='retour_client'"; 
		   		
				$TotalReprise_DLAB_HG 	= "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND $REDO_REASON_NOT_IN 
				AND redo_origin='reception_commande_entrepot'"; 
				
				$TotalReprise_DLAB_LAB 	= "SELECT count(order_num) as TotalReprise_DLAB_LAB, sum(order_total) as  ValeurRepriseDLAB_LAB  FROM ORDERS WHERE  order_date_shipped BETWEEN '$date1' and '$date2' 
				AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab  AND $REDO_REASON_NOT_IN 
				AND redo_origin='lab'"; 
		   		
				$queryRedoReasonDLAB  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0 AND $REDO_REASON_NOT_IN  ORDER BY redo_reason_id";

				$TotalJobDLABSansRedo = "SELECT count(order_num) as TotalJob_DLAB_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab = $prescript_lab AND redo_order_num IS null"; 	
				$resultJobDLABSansRedo 	= mysqli_query($con,$TotalJobDLABSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobDLABSansRedo);
				$DatatotalJob_DLABSansRedo = mysqli_fetch_array($resultJobDLABSansRedo);	

				$resultTotalReprise_DLAB 	= mysqli_query($con,$TotalReprise_DLAB)		or die  ('I cannot select items because 552a1338: ' . mysqli_error($con) . $TotalReprise_DLAB);
				$DataTotalReprises_DLAB 	= mysqli_fetch_array($resultTotalReprise_DLAB);	
				$resultTotalReprise_DLAB_G 	= mysqli_query($con,$TotalReprise_DLAB_G)		or die  ('I cannot select items because 114a5273: ' . mysqli_error($con) . $TotalReprise_DLAB_G);
				$DataTotalReprises_DLAB_G  	= mysqli_fetch_array($resultTotalReprise_DLAB_G);	
				$resultTotalReprise_DLAB_HG = mysqli_query($con,$TotalReprise_DLAB_HG)		or die  ('I cannot select items because 12321a123: ' . mysqli_error($con) . $TotalReprise_DLAB_HG);
				$DataTotalReprises_DLAB_HG  = mysqli_fetch_array($resultTotalReprise_DLAB_HG);
				$resultTotalReprise_DLAB_LAB = mysqli_query($con,$TotalReprise_DLAB_LAB)		or die  ('I cannot select items because 12321a123: ' . mysqli_error($con) . $TotalReprise_DLAB_LAB);
				$DataTotalReprises_DLAB_LAB  = mysqli_fetch_array($resultTotalReprise_DLAB_LAB);	
				
				//Aller cherchers les differentes raisons de reprises pour ce fabriquant et la succursale choisie
				$resultRedoReasonDLAB=mysqli_query($con,$queryRedoReasonDLAB)		or die ( "Query  4321 failed:". $queryRedoReasonDLAB.  mysqli_error($con));
				
				
				$PercentageDLAB=($DataTotalReprises_DLAB[TotalReprise_DLAB]/$DatatotalJob_DLABSansRedo[TotalJob_DLAB_SansRedo])*100;
				$PercentageDLAB  = number_format($PercentageDLAB, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total Redo: <b>$DataTotalReprises_DLAB[TotalReprise_DLAB]</b> | Total Original: <b>$DatatotalJob_DLABSansRedo[TotalJob_DLAB_SansRedo]</b>
				 | Percentage:<b>$PercentageDLAB%</b></td>
				 </tr>
				
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Reason</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Redo Store</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Redo Warranty</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Redo Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesDLAB 		= 0;
				$SommeReprisesHorsGarantiesDLAB 	= 0;
				$SommeReprisesLabDLAB 				= 0;
				while($DataRedoReasonDLAB  = mysqli_fetch_array($resultRedoReasonDLAB)){
					

					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					
					$queryRedo_DLAB_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders 
					WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND $REDO_REASON_NOT_IN  AND prescript_lab=$prescript_lab AND redo_reason_id = $DataRedoReasonDLAB[redo_reason_id] 
					AND redo_origin='retour_client'";
					//echo '<br>'.$queryRedo_DLAB_G.'<br>';
					$resultRedo_DLAB_G = mysqli_query($con,$queryRedo_DLAB_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_DLAB_G   = mysqli_fetch_array($resultRedo_DLAB_G);
					//On garde la trace du total
					$SommeReprisesGarantiesDLAB  = $SommeReprisesGarantiesDLAB +$DataRedo_DLAB_G[NbrRedoGaranties];
			
					$queryRedo_DLAB_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab   AND $REDO_REASON_NOT_IN
					AND redo_origin='reception_commande_entrepot'";
					//echo '<br>'.$queryRedo_DLAB_HG.'<br><br><br>';
					$resultRedo_DLAB_HG = mysqli_query($con,$queryRedo_DLAB_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_DLAB_HG   = mysqli_fetch_array($resultRedo_DLAB_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesDLAB  = $SommeReprisesHorsGarantiesDLAB +$DataRedo_DLAB_HG[NbrRedoHorsGaranties];	

					$queryRedo_DLAB_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab  AND $REDO_REASON_NOT_IN
					AND redo_origin='lab'";
					$resultRedo_DLAB_LAB = mysqli_query($con,$queryRedo_DLAB_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_DLAB_LAB	  = mysqli_fetch_array($resultRedo_DLAB_LAB);
					//On garde la trace du total
					$SommeReprisesLabDLAB  = $SommeReprisesLabDLAB +$DataRedo_DLAB_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_DLAB_HG[NbrRedoHorsGaranties] + $DataRedo_DLAB_G[NbrRedoGaranties] + +$DataRedo_DLAB_LAB[NbrRedoLAB])/$DataTotalReprises_DLAB[TotalReprise_DLAB])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$TotalPourCetteRaison = $DataRedo_DLAB_HG[NbrRedoHorsGaranties] + $DataRedo_DLAB_G[NbrRedoGaranties] + $DataRedo_DLAB_LAB[NbrRedoLAB];
										
					//Afficher le contenu de l'array
					if ($SommeRedopourCetteRaison<>0){
					$message.="<tr>
									<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]</td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_DLAB_HG[NbrRedoHorsGaranties]</td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_DLAB_G[NbrRedoGaranties]</td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_DLAB_LAB[NbrRedoLAB]</td>
									<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\">$TotalPourCetteRaison</td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
								</tr>";
					}//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesDLAB + $SommeReprisesGarantiesDLAB + $SommeReprisesLabDLAB;
				
				$message.="<tr>
				<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
				<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><b>$SommeReprisesHorsGarantiesDLAB</b></td>
				<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><b>$SommeReprisesGarantiesDLAB</b></td>
				<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><b>$SommeReprisesLabDLAB<b/></td>
				<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
				<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
				</tr>";
				 $message.="</table><br><br>";
				
	   break; 
  
   }//End Switch
	
}//End FOR*/	

//Fin de la partie 4*/
	







//PARTIE 5: Copie de la 4 pour splitter par magasin demande de Daniel......
$NombreFournisseur = 12;
//RAISONS DE REPRISES A EXCLURE DE CE RAPPORT;
$REDO_REASON_NOT_IN = "  redo_reason_id NOT IN (0,42,43,44,50,61,64,65,66) ";
//Définire la palette de couleur:
$Couleur_PETAL		 	="#F98866";//Reprises Garanties(Nombre, % reprise, valeur$$)
$Couleur_POPPY		 	="#FF420E";
$Couleur_STEM		 	="#80BD9E";
$Couleur_SPRINGGREEN 	="#89DA59";//Total de vente, $ Achats Intercos
$Couleur_JAUNATRE 		="#fbf579";//Nombre de reprise, % de reprise, valeur net des reprises.
$Couleur_BLEU_PALE		="#569DBD";//Bleuté
$LargeurTableaux		="1000";


//1-Trois-riviers	$User_ID_IN = ('entrepotifc','entrepotsafe');
//2-Drummondville   $User_ID_IN = " ('entrepotdr','safedr') "; 
//3-Chicoutimi      $User_ID_IN = " ('chicoutimi','chicoutimisafe') ";
//4-Halifax 		$User_ID_IN = " ('warehousehal','warehousehalsafe') ";
//5-Laval 			$User_ID_IN = " ('laval','lavalsafe') ";
//6-Lévis			$User_ID_IN = " ('levis','levissafe') ";	
//7-Terrebonne		$User_ID_IN = " ('terrebonne','terrebonnesafe') ";
//8-Sherbrooke		$User_ID_IN = " ('sherbrooke','sherbrookesafe') ";
//9-Granby			$User_ID_IN = " ('granby','granbysafe') ";
//10-Longueuil    	$User_ID_IN = " ('longueuil','longueuilsafe') ";  
//11-Québec 		$User_ID_IN = " ('entrepotquebec','quebecsafe') "; 
//12-Montréal 		$User_ID_IN = " ('montreal','montrealsafe') ";	
//13-Gatineau		$User_ID_IN = " ('gatineau','gatineausafe') ";	
 

//Parcourir les magasins [SWISS, HKO, GKB, Dlab(St.Catharines)]
for ($x = 1; $x <= $NombreFournisseur; $x++) {
	//echo '<br>Rentre dans le  for<br>';
   switch($x){
	 
		  
	 case 1  :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Trois-Rivieres";
			$User_ID_IN = "('entrepotifc','entrepotsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case
		  
		   
		case 2  :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Drummondville";
			$User_ID_IN = "('entrepotdr','safedr')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case  2 
		   
		   
		   
		   
	 case 3 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Chicoutimi";
			$User_ID_IN = "('chicoutimi','chicoutimisafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case  3
		   
		   
		  case 4 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Halifax";
			$User_ID_IN = "('warehousehal','warehousehalsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case  4 
		   
		   
		   
		   
	case 5 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Laval";
			$User_ID_IN = "('laval','lavalsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 5
		   
		   
	   case 6 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Lévis";
			$User_ID_IN = "('levis','levissafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 6
		   
		   
	   case 7 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Terrebonne";
			$User_ID_IN = "('terrebonne','terrebonnesafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 7
		   
	
	   
	   case 8 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Granby";
			$User_ID_IN = "('granby','granbysafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 8
		   
	  case 9 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Longueuil";
			$User_ID_IN = "('longueuil','longueuilsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 9  
		   
		   
		   
	 case 10 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Québec";
			$User_ID_IN = "('entrepotquebec','quebecsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 10
		   
		   
	 case 11 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Montréal";
			$User_ID_IN = "('montreal','montrealsafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 11
		   
		   
		   
		   
	 case 12 :
		   	//Partie personnalisé par succursale
		    $NomSuccursale="Gatineau";
			$User_ID_IN = "('gatineau','gatineausafe')";
		    //Fin partie personnalisée
		    $Detail_Succursale = "Reprises  $NomSuccursale  [Sans 'Lab Edging Error'] [".$date1."-".$date2."]";
		   		
				$TotalReprise_Succursale 	= "SELECT count(order_num) as TotalReprisedeCeMagasin FROM ORDERS WHERE user_id IN $User_ID_IN AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL  
				 AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN  AND redo_order_num<>0  AND redo_origin IN ('retour_client', 'reception_commande_entrepot','lab')"; 	
				$resultTotalReprise_Succursale 	= mysqli_query($con,$TotalReprise_Succursale)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalReprise_Succursale);
				$DataTotalReprise_Succursale 	= mysqli_fetch_array($resultTotalReprise_Succursale);	
		   		//echo '<br>'.$TotalReprise_Succursale.'<br>';
		   
				$TotalJobSuccSansRedo = "SELECT count(order_num) as TotalJob_Succ_SansRedo FROM ORDERS WHERE  
				order_date_shipped BETWEEN '$date1' and '$date2' and  user_id IN $User_ID_IN     AND redo_order_num IS null"; 	
				//echo '<br>Query:'. $TotalJobSuccSansRedo.'<br><br>';
				$resultJobSuccSansRedo 	= mysqli_query($con,$TotalJobSuccSansRedo)		or die  ('I cannot select items because 5538: ' . mysqli_error($con) . $TotalJobSuccSansRedo);
				$DatatotalJob_SUCCSansRedo = mysqli_fetch_array($resultJobSuccSansRedo);	
						   
				//Aller cherchers les différentes raisons de reprises pour ce fabriquant et la succursale choisie				
				$queryRedoReasonSucc  	= "SELECT distinct redo_reason_id FROM ORDERS WHERE user_id IN $User_ID_IN AND  order_date_shipped BETWEEN '$date1' and '$date2'  
				AND redo_order_num<>0  AND redo_reason_id<>0 AND $REDO_REASON_NOT_IN      ORDER BY redo_reason_id";				
				$resultRedoReasonSucc=mysqli_query($con,$queryRedoReasonSucc)		or die ( "Query  4321 failed:". $queryRedoReasonSucc.  mysqli_error($con));
		   		//echo '<br>'.$queryRedoReasonSucc.'<br>';
		   
				$PercentageSucc=($DataTotalReprise_Succursale[TotalReprisedeCeMagasin]/$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo])*100;
				$PercentageSucc  = number_format($PercentageSucc, 2, ",", " " );
				 $message.="<table width=\"$LargeurTableaux\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				 <tr><td align=\"center\" colspan=\"6\"><b>$Detail_Succursale</b>      |  
				 Total: <b>$DataTotalReprise_Succursale[TotalReprisedeCeMagasin] Reprises</b> | Total: <b>$DatatotalJob_SUCCSansRedo[TotalJob_Succ_SansRedo] Originales</b>
				 | Pourcentage: <b>$PercentageSucc%</b></td>
				 </tr>
		
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"40%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"13%\">Reprise Magasin</th>
					<th bgcolor=\"#B2B706\" width=\"13%\">Reprise Garantie</th>
					<th bgcolor=\"$Couleur_SPRINGGREEN\" width=\"13%\">Reprise Lab</th>
					<th bgcolor=\"$Couleur_BLEU_PALE\" width=\"13%\">TOTAL</th>
					<th bgcolor=\"#F1F71D\" width=\"8%\">%</th>
				</tr>";
				
				$SommeReprisesGarantiesSucc 		= 0;
				$SommeReprisesHorsGarantiesSucc 	= 0;
				$SommeReprisesLabSucc 				= 0;
				
				while($DataRedoReasonSucc  = mysqli_fetch_array($resultRedoReasonSucc)){
					
					$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSucc[redo_reason_id]";
					$resultDetailRedoReason = mysqli_query($con,$queryDetailRedoReason)		or die ( "Query failed: " . mysqli_error($con));
					$DataDetailRedoReason   = mysqli_fetch_array($resultDetailRedoReason);
					//echo '<br>'.$queryDetailRedoReason.'<br>';
					
					$queryRedo_SUCC_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN
					AND redo_reason_id  = $DataRedoReasonSucc[redo_reason_id] AND $REDO_REASON_NOT_IN   AND redo_origin='retour_client'";
					$resultRedo_SUCC_G = mysqli_query($con,$queryRedo_SUCC_G)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_G   = mysqli_fetch_array($resultRedo_SUCC_G);
					//echo '<br>'.$queryRedo_SUCC_G.'<br>';
					
					//On garde la trace du total
					$SommeReprisesGarantiesSucc  = $SommeReprisesGarantiesSucc +$DataRedo_SUCC_G[NbrRedoGaranties];	
						
					$queryRedo_SUCC_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN  AND $REDO_REASON_NOT_IN    AND redo_origin='reception_commande_entrepot'";
					$resultRedo_SUCC_HG = mysqli_query($con,$queryRedo_SUCC_HG)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_HG   = mysqli_fetch_array($resultRedo_SUCC_HG);
					//On garde la trace du total
					$SommeReprisesHorsGarantiesSucc  = $SommeReprisesHorsGarantiesSucc +$DataRedo_SUCC_HG[NbrRedoHorsGaranties];	
					//echo '<br>'.$queryRedo_SUCC_HG.'<br>';
										
					$queryRedo_SUCC_LAB  = "SELECT count(order_num) as NbrRedoLAB FROM orders WHERE  redo_reason_id = $DataRedoReasonSucc[redo_reason_id]
					AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $User_ID_IN   AND $REDO_REASON_NOT_IN    AND redo_origin='lab'";
					$resultRedo_SUCC_LAB = mysqli_query($con,$queryRedo_SUCC_LAB)		or die ( "Query failed: " . mysqli_error($con));
					$DataRedo_SUCC_LAB	  = mysqli_fetch_array($resultRedo_SUCC_LAB);
					//On garde la trace du total
					$SommeReprisesLabSucc  = $SommeReprisesLabSucc +$DataRedo_SUCC_LAB[NbrRedoLAB];
					//echo '<br>'.$queryRedo_SWISS_LAB.'<br>';
					
					$TotalPourCetteRaison = $DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties] + $DataRedo_SUCC_LAB[NbrRedoLAB];
					
					//Calcul du pourcentage
					$SommeRedopourCetteRaison = (($DataRedo_SUCC_HG[NbrRedoHorsGaranties] + $DataRedo_SUCC_G[NbrRedoGaranties]+$DataRedo_SUCC_LAB[NbrRedoLAB])/$DataTotalReprises_SUCC[TotalReprise_SUCC])*100;
					$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
					
					$Percentage = ($TotalPourCetteRaison/$DataTotalReprise_Succursale[TotalReprisedeCeMagasin])*100;
					$Percentage = round($Percentage,2);
					//Afficher le contenu de l'array
					 if ($SommeRedopourCetteRaison<>0){
					 $message.="<tr>
										<td bgcolor=\"#F1F71D\" width=\"60%\">$DataDetailRedoReason[redo_reason_fr]</td>
										<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SUCC_HG[NbrRedoHorsGaranties]</td>
										<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_G[NbrRedoGaranties]</td>
										<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\">$DataRedo_SUCC_LAB[NbrRedoLAB]</td>
										<td bgcolor=\"$Couleur_BLEU_PALE\"  width=\"15%\" align=\"center\"><b>$TotalPourCetteRaison</b></td>
										<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\">$Percentage%</td>
									</tr>";
					 }//End IF
		
				}//End While
				
				$GrandTotalReprisespourceFournisseur = $SommeReprisesHorsGarantiesSucc + $SommeReprisesGarantiesSucc + $SommeReprisesLabSucc;
				 
				 $message.="<tr>
									<td bgcolor=\"#F1F71D\" align=\"right\" width=\"60%\"><b>TOTAL:</b></td>
									<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\"><h4>$SommeReprisesHorsGarantiesSucc</h4></td>
									<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesGarantiesSucc</h4></td>
									<td bgcolor=\"$Couleur_SPRINGGREEN\"  width=\"15%\" align=\"center\"><h4>$SommeReprisesLabSucc</h4></td>
									<td bgcolor=\"$Couleur_BLEU_PALE\" align=\"center\" width=\"10%\"><h2>$GrandTotalReprisespourceFournisseur</h2></td>
									<td bgcolor=\"#F1F71D\" align=\"center\" width=\"10%\"></td>
								</tr>";
				 $message.="</table><br><br>";  
	  break;//Fin du case 	   
		   
		   

   }//End Switch
	
}//End FOR*/	
//echo '<br>Sort du For';



echo $message;

//Fermer le tableau

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de reprise annuel Partie 5:[$date1-$date2]";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
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
	
	if($response){ 
		//log_email("REPORT: Rapport de reprise annuel 2018",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: sucess';
    }else{
		//log_email("REPORT: Rapport de reprise annuel 2018",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: failed';
	}	

		
$time_end = microtime(true);
$time = $time_end - $time_start;
//echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Email redirection report DR', '$time','$today','$timeplus3heures','cron_send_redirection_report_dr.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));

?>