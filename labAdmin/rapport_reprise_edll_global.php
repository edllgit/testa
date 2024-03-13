<?php
header('Content-Type: text/html; charset=iso-8859-1');
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);
$date1 	    = "2017-01-01";
$date2      = "2017-01-31";
$NombredeSuccursale = 12;



//PARTIE 1: SOMMAIRE DANIEL BEAULIEU

//1.1-Nombre de commandes envoyees
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe')              AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 2  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesDR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')            AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 3  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesCH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')    AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;	 
	   case 4  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesTR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')     AND order_date_shipped BETWEEN '$date1' and '$date2'";  break; 
	   case 5  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesSH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')    AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 6  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesTE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')    AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 7  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLO  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')      AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 8  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesLE  FROM ORDERS WHERE user_id IN ('levis','levissafe')              AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 9  :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesHA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe') AND order_date_shipped BETWEEN '$date1' and '$date2'"; break;
	   case 10 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesGR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')            AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 11 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesSMB FROM ORDERS WHERE user_id IN ('fsdf','sdfsd')        AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
	   case 12 :$QuerySucc = "SELECT count(order_num) as NbrEnvoyesQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')            AND order_date_shipped BETWEEN '$date1' and '$date2'";  break;
   }//End Switch
	
	
	$resultNbrEnvoyes = mysql_query($QuerySucc)		or die  ('I cannot select items because: ' . mysql_error());
	$DataNbrEnvoyes   = mysql_fetch_array($resultNbrEnvoyes);
		
	 switch($x){ 
	   case 1  :$NbrEnvoyesLV  = $DataNbrEnvoyes[NbrEnvoyesLV];  break;
	   case 2  :$NbrEnvoyesDR  = $DataNbrEnvoyes[NbrEnvoyesDR];  break;
	   case 3  :$NbrEnvoyesCH  = $DataNbrEnvoyes[NbrEnvoyesCH];  break;
	   case 4  :$NbrEnvoyesTR  = $DataNbrEnvoyes[NbrEnvoyesTR];  break; 
	   case 5  :$NbrEnvoyesSH  = $DataNbrEnvoyes[NbrEnvoyesSH];  break;
	   case 6  :$NbrEnvoyesTE  = $DataNbrEnvoyes[NbrEnvoyesTE];  break;
	   case 7  :$NbrEnvoyesLO  = $DataNbrEnvoyes[NbrEnvoyesLO];  break;
	   case 8  :$NbrEnvoyesLE  = $DataNbrEnvoyes[NbrEnvoyesLE];  break;
	   case 9  :$NbrEnvoyesHA  = $DataNbrEnvoyes[NbrEnvoyesHA];  break;
	   case 10 :$NbrEnvoyesGR  = $DataNbrEnvoyes[NbrEnvoyesGR];  break;
	   case 11 :$NbrEnvoyesSMB = $DataNbrEnvoyes[NbrEnvoyesSMB]; break;
	   case 12 :$NbrEnvoyesQC  = $DataNbrEnvoyes[NbrEnvoyesQC];  break;
   }//End Switch	
	$TotalCommandesEnvoyees = $NbrEnvoyesLV+$NbrEnvoyesDR+$NbrEnvoyesCH+$NbrEnvoyesTR+$NbrEnvoyesSH+$NbrEnvoyesTE+$NbrEnvoyesLO+$NbrEnvoyesLE+$NbrEnvoyesHA+$NbrEnvoyesGR+$NbrEnvoyesSMB+$NbrEnvoyesQC;
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
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseSMB, sum(order_total) as ValeurRepriseSMB FROM ORDERS WHERE user_id IN ('432','4324')  AND redo_order_num IS NOT NULL       
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 12:
	   $QueryReprise = "SELECT count(order_num) as NbrRepriseQC, sum(order_total) as ValeurRepriseQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_order_num IS NOT NULL           
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch
	
	
	$resultNbrReprise = mysql_query($QueryReprise)		or die  ('I cannot select items because: ' . mysql_error());
	$DataReprise      = mysql_fetch_array($resultNbrReprise);
		
	 switch($x){ 
	   case 1  :$NbrRepriseLV  = $DataReprise[NbrRepriseLV];  $ValeurRepriseLV  = number_format($DataReprise[ValeurRepriseLV], 2, ",", "." );  $ValeurRepriseLVBU  = $DataReprise[ValeurRepriseLV];  break;
	   case 2  :$NbrRepriseDR  = $DataReprise[NbrRepriseDR];  $ValeurRepriseDR  = number_format($DataReprise[ValeurRepriseDR], 2, ",", "." );  $ValeurRepriseDRBU  = $DataReprise[ValeurRepriseDR];  break;
	   case 3  :$NbrRepriseCH  = $DataReprise[NbrRepriseCH];  $ValeurRepriseCH  = number_format($DataReprise[ValeurRepriseCH], 2, ",", "." );  $ValeurRepriseCHBU  = $DataReprise[ValeurRepriseCH];  break;
	   case 4  :$NbrRepriseTR  = $DataReprise[NbrRepriseTR];  $ValeurRepriseTR  = number_format($DataReprise[ValeurRepriseTR], 2, ",", "." );  $ValeurRepriseTRBU  = $DataReprise[ValeurRepriseTR];  break;
	   case 5  :$NbrRepriseSH  = $DataReprise[NbrRepriseSH];  $ValeurRepriseSH  = number_format($DataReprise[ValeurRepriseSH], 2, ",", "." );  $ValeurRepriseSHBU  = $DataReprise[ValeurRepriseSH];  break;
	   case 6  :$NbrRepriseTE  = $DataReprise[NbrRepriseTE];  $ValeurRepriseTE  = number_format($DataReprise[ValeurRepriseTE], 2, ",", "." );  $ValeurRepriseTEBU  = $DataReprise[ValeurRepriseTE];  break;
	   case 7  :$NbrRepriseLO  = $DataReprise[NbrRepriseLO];  $ValeurRepriseLO  = number_format($DataReprise[ValeurRepriseLO], 2, ",", "." );  $ValeurRepriseLOBU  = $DataReprise[ValeurRepriseLO];  break;
	   case 8  :$NbrRepriseLE  = $DataReprise[NbrRepriseLE];  $ValeurRepriseLE  = number_format($DataReprise[ValeurRepriseLE], 2, ",", "." );  $ValeurRepriseLEBU  = $DataReprise[ValeurRepriseLE];  break;
	   case 9  :$NbrRepriseHA  = $DataReprise[NbrRepriseHA];  $ValeurRepriseHA  = number_format($DataReprise[ValeurRepriseHA], 2, ",", "." );  $ValeurRepriseHABU  = $DataReprise[ValeurRepriseHA];  break;
	   case 10 :$NbrRepriseGR  = $DataReprise[NbrRepriseGR];  $ValeurRepriseGR  = number_format($DataReprise[ValeurRepriseGR], 2, ",", "." );  $ValeurRepriseGRBU  = $DataReprise[ValeurRepriseGR];  break;
	   case 11 :$NbrRepriseSMB = $DataReprise[NbrRepriseSMB]; $ValeurRepriseSMB = number_format($DataReprise[ValeurRepriseSMB], 2, ",", "." ); $ValeurRepriseSMBBU = $DataReprise[ValeurRepriseSMB]; break;
	   case 12 :$NbrRepriseQC  = $DataReprise[NbrRepriseQC];  $ValeurRepriseQC  = number_format($DataReprise[ValeurRepriseQC], 2, ",", "." );  $ValeurRepriseQCBU  = $DataReprise[ValeurRepriseQC];  break;
   }//End Switch	
	$TotalReprises = $NbrRepriseLV+$NbrRepriseDR+$NbrRepriseCH+$NbrRepriseTR+$NbrRepriseSH+$NbrRepriseTE+$NbrRepriseLO+$NbrRepriseLE+$NbrRepriseHA+$NbrRepriseGR+$NbrRepriseSMB+$NbrRepriseQC;
	
	$TotalValeurReprises =$ValeurRepriseLVBU+$ValeurRepriseDRBU+$ValeurRepriseCHBU+$ValeurRepriseTRBU+$ValeurRepriseSHBU+$ValeurRepriseTEBU+$ValeurRepriseLOBU+$ValeurRepriseLEBU+$ValeurRepriseHABU +$ValeurRepriseGRBU +$ValeurRepriseSMBBU + $ValeurRepriseQCBU;
	$MoyenneValeurReprise = $TotalValeurReprises/$NombredeSuccursale;
	$MoyenneValeurReprise  = number_format($MoyenneValeurReprise, 2, ",", "." );
	$TotalValeurReprises  = number_format($TotalValeurReprises, 2, ",", "." );
	
	
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
							  
$PourcentageRepriseSMB = ($NbrRepriseSMB/$NbrEnvoyesSMB)*100;
$PourcentageRepriseSMB = round($PourcentageRepriseSMB,2);
							   
$PourcentageRepriseQC = ($NbrRepriseQC/$NbrEnvoyesQC)*100;
$PourcentageRepriseQC = round($PourcentageRepriseQC,2);

//Moyenne des pourcentages de reprise
$MoyennePourcentageReprise = ($PourcentageRepriseLV + $PourcentageRepriseDR + $PourcentageRepriseCH + $PourcentageRepriseTR + $PourcentageRepriseSH +  $PourcentageRepriseTE + $PourcentageRepriseLO + $PourcentageRepriseLE + $PourcentageRepriseHA + $PourcentageRepriseGR + $PourcentageRepriseSMB + $PourcentageRepriseQC)/$NombredeSuccursale;
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
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGSMB, sum(order_total) as ValeurRepriseGSMB FROM ORDERS WHERE user_id IN ('fds','sdf')  
	   AND redo_order_num IS NOT NULL   
	   AND redo_origin='retour_client'     
	   AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 12:
	   $QueryRepriseGaranties = "SELECT count(order_num) as NbrRepriseGQC, sum(order_total) as ValeurRepriseGQC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  
	   AND redo_order_num IS NOT NULL     
	   AND redo_origin='retour_client'       
	   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch
	
	
	$resultNbrRepriseGaranties = mysql_query($QueryRepriseGaranties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseGaranties      = mysql_fetch_array($resultNbrRepriseGaranties);
		
	 switch($x){ 
	   case 1  :$NbrRepriseGLV       = $DataRepriseGaranties[NbrRepriseGLV]; 
			    $ValeurRepriseGLV    = number_format($DataRepriseGaranties[ValeurRepriseGLV], 2, ",", "." );  
			    $ValeurRepriseGLVBU  = $DataRepriseGaranties[ValeurRepriseGLV]; 
	   break;
			 
			 
	   case 2  :$NbrRepriseGDR       = $DataRepriseGaranties[NbrRepriseGDR];  
	   			$ValeurRepriseGDR    = number_format($DataRepriseGaranties[ValeurRepriseGDR], 2, ",", "." );  
			    $ValeurRepriseGDRBU  = $DataRepriseGaranties[ValeurRepriseGDR]; 
	   break;
	   
			 
	   case 3  :$NbrRepriseGCH       = $DataRepriseGaranties[NbrRepriseGCH];  
	   	        $ValeurRepriseGCH    = number_format($DataRepriseGaranties[ValeurRepriseGCH], 2, ",", "." );  
			    $ValeurRepriseGCHBU  = $DataRepriseGaranties[ValeurRepriseGCH]; 
	   break;
		
			 
	   case 4  :$NbrRepriseGTR       = $DataRepriseGaranties[NbrRepriseGTR]; 
	  		    $ValeurRepriseGTR    = number_format($DataRepriseGaranties[ValeurRepriseGTR], 2, ",", "." );  
			    $ValeurRepriseGTRBU  = $DataRepriseGaranties[ValeurRepriseGTR]; 
	   break;
		
			 
	   case 5  :$NbrRepriseGSH       = $DataRepriseGaranties[NbrRepriseGSH];  
	   		    $ValeurRepriseGSH    = number_format($DataRepriseGaranties[ValeurRepriseGSH], 2, ",", "." );  
			    $ValeurRepriseGSHBU  = $DataRepriseGaranties[ValeurRepriseGSH]; 
	   break;
		
			 
	   case 6  :$NbrRepriseGTE       = $DataRepriseGaranties[NbrRepriseGTE];  
	   			$ValeurRepriseGTE    = number_format($DataRepriseGaranties[ValeurRepriseGTE], 2, ",", "." );  
			    $ValeurRepriseGTEBU  = $DataRepriseGaranties[ValeurRepriseGTE]; 
	   break;
			
			 
	   case 7  :$NbrRepriseGLO       = $DataRepriseGaranties[NbrRepriseGLO];  
  	  			$ValeurRepriseGLO    = number_format($DataRepriseGaranties[ValeurRepriseGLO], 2, ",", "." );  
			    $ValeurRepriseGLOBU  = $DataRepriseGaranties[ValeurRepriseGLO]; 
	   break;
			
			 
	   case 8  :$NbrRepriseGLE       = $DataRepriseGaranties[NbrRepriseGLE]; 
     		    $ValeurRepriseGLE    = number_format($DataRepriseGaranties[ValeurRepriseGLE], 2, ",", "." );  
			    $ValeurRepriseGLEBU  = $DataRepriseGaranties[ValeurRepriseGLE]; 
	   break;
			
			 
	   case 9  :$NbrRepriseGHA       = $DataRepriseGaranties[NbrRepriseGHA];  
	  		    $ValeurRepriseGHA    = number_format($DataRepriseGaranties[ValeurRepriseGHA], 2, ",", "." );  
			    $ValeurRepriseGHABU  = $DataRepriseGaranties[ValeurRepriseGHA]; 
	   break;
		
			 
	   case 10 :$NbrRepriseGGR       = $DataRepriseGaranties[NbrRepriseGGR];  
	   		    $ValeurRepriseGGR    = number_format($DataRepriseGaranties[ValeurRepriseGGR], 2, ",", "." );  
			    $ValeurRepriseGGRBU  = $DataRepriseGaranties[ValeurRepriseGGR]; 
	   break;
		
			 
	   case 11 :$NbrRepriseGSMB       = $DataRepriseGaranties[NbrRepriseGSMB];
	   		    $ValeurRepriseGSMB    = number_format($DataRepriseGaranties[ValeurRepriseGSMB], 2, ",", "." );  
			    $ValeurRepriseGSMBBU  = $DataRepriseGaranties[ValeurRepriseGSMB]; 
	   break;
		
			 
	   case 12 :$NbrRepriseGQC       = $DataRepriseGaranties[NbrRepriseGQC]; 
	            $ValeurRepriseGQC    = number_format($DataRepriseGaranties[ValeurRepriseGQC], 2, ",", "." );  
			    $ValeurRepriseGQCBU  = $DataRepriseGaranties[ValeurRepriseGQC]; 
	   break;
   }//End Switch	
	$TotalReprisesGaranties = $NbrRepriseGLV+$NbrRepriseGDR+$NbrRepriseGCH+$NbrRepriseGTR+$NbrRepriseGSH+$NbrRepriseGTE+ $NbrRepriseGLO+$NbrRepriseGLE+$NbrRepriseGHA+$NbrRepriseGGR+$NbrRepriseGSMB+$NbrRepriseGQC;
	$MoyenneReprisesGaranties = $TotalReprisesGaranties/$NombredeSuccursale;
	$MoyenneReprisesGaranties = round($MoyenneReprisesGaranties,2);
	
	$SommeValeurReprisesGaranties =  $ValeurRepriseGLVBU+ $ValeurRepriseGDRBU+ $ValeurRepriseGCHBU+ $ValeurRepriseGTRBU+ $ValeurRepriseGSHBU+$ValeurRepriseGTEBU+ $ValeurRepriseGLOBU+ $ValeurRepriseGLEBU+ $ValeurRepriseGHABU+ $ValeurRepriseGGRBU+ $ValeurRepriseGSMBBU+ $ValeurRepriseGQCBU;
	$MoyenneValeurRepriseGaranties = $SommeValeurReprisesGaranties/$NombredeSuccursale;
	$MoyenneValeurRepriseGaranties = number_format($MoyenneValeurRepriseGaranties, 2, ",", "." );
	$SommeValeurReprisesGaranties = number_format($SommeValeurReprisesGaranties, 2, ",", "." );
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
							  
$PourcentageRepriseGSMB = ($NbrRepriseGSMB/$NbrEnvoyesSMB)*100;
$PourcentageRepriseGSMB = round($PourcentageRepriseGSMB,2);
							   
$PourcentageRepriseGQC = ($NbrRepriseGQC/$NbrEnvoyesQC)*100;
$PourcentageRepriseGQC = round($PourcentageRepriseGQC,2);


//Moyenne des pourcentages de reprise
$MoyennePourcentageRepriseGaranties = ($PourcentageRepriseGLV + $PourcentageRepriseGDR + $PourcentageRepriseGCH + $PourcentageRepriseGTR + $PourcentageRepriseGSH +  $PourcentageRepriseGTE + $PourcentageRepriseGLO + $PourcentageRepriseGLE + $PourcentageRepriseGHA + $PourcentageRepriseGGR + $PourcentageRepriseGSMB + $PourcentageRepriseGQC)/$NombredeSuccursale;
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
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_SMB FROM ORDERS WHERE user_id IN ('fds','sdf')  
	   AND redo_order_num IS NOT NULL   AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 12:
	   $QueryRepriseDLAB = "SELECT count(order_num) as NbrRepriseDLAB_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  
	   AND redo_order_num IS NOT NULL  AND prescript_lab=3 AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch
	
	
	$resultNbrRepriseDlab = mysql_query($QueryRepriseDLAB)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseDLAB      = mysql_fetch_array($resultNbrRepriseDlab);
		
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
	   case 11 :$NbrRepriseDLAB_SMB = $DataRepriseDLAB[NbrRepriseDLAB_SMB]; break;
	   case 12 :$NbrRepriseDLAB_QC  = $DataRepriseDLAB[NbrRepriseDLAB_QC];  break;
   }//End Switch	
	
	$TotalReprisesDLAB = $NbrRepriseDLAB_LV+$NbrRepriseDLAB_DR+$NbrRepriseDLAB_CH+$NbrRepriseDLAB_TR+$NbrRepriseDLAB_SH+$NbrRepriseDLAB_TE+ $NbrRepriseDLAB_LO+$NbrRepriseDLAB_LE+$NbrRepriseDLAB_HA+$NbrRepriseDLAB_GR+$NbrRepriseDLAB_SMB+$NbrRepriseDLAB_QC;
	
	$MoyenneRepriseDLAB   = $TotalReprisesDLAB/$NombredeSuccursale;
	$MoyenneRepriseDLAB   = round($MoyenneRepriseDLAB,2);
	$TotalValeurReprises  = $ValeurRepriseLVBU+$ValeurRepriseDRBU+$ValeurRepriseCHBU+$ValeurRepriseTRBU+$ValeurRepriseSHBU+$ValeurRepriseTEBU+$ValeurRepriseLOBU+$ValeurRepriseLEBU+$ValeurRepriseHABU +$ValeurRepriseGRBU +$ValeurRepriseSMBBU + $ValeurRepriseQCBU;
	$MoyenneValeurReprise = $TotalValeurReprises/$NombredeSuccursale;
	$MoyenneValeurReprise = number_format($MoyenneValeurReprise, 2, ",", "." );
	$TotalValeurReprises  = number_format($TotalValeurReprises, 2, ",", "." );
	
	
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
	
	$PourcentageRepriseDLAB_SMB= ($NbrRepriseDLAB_SMB/$NbrRepriseSMB)*100;
	$PourcentageRepriseDLAB_SMB = round($PourcentageRepriseDLAB_SMB,2);
	
	$PourcentageRepriseDLAB_QC = ($NbrRepriseDLAB_QC/$NbrRepriseQC)*100;
	$PourcentageRepriseDLAB_QC = round($PourcentageRepriseDLAB_QC,2);
	
	
	
}//End FOR



$MoyennePourcentageRepriseDLAB = ($PourcentageRepriseDLAB_LV + $PourcentageRepriseDLAB_DR + $PourcentageRepriseDLAB_CH + $PourcentageRepriseDLAB_TR + $PourcentageRepriseDLAB_SH + $PourcentageRepriseDLAB_TE + $PourcentageRepriseDLAB_LO + $PourcentageRepriseDLAB_LE + $PourcentageRepriseDLAB_HA + $PourcentageRepriseDLAB_GR + $PourcentageRepriseDLAB_SMB + $PourcentageRepriseDLAB_QC)/$NombredeSuccursale;
$MoyennePourcentageRepriseDLAB = round($MoyennePourcentageRepriseDLAB,2);

//Preparer le courriel 

		$message="";
		$message="<html>";
		$message.="<head>
		<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
		<style type='text/css'>
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
                	<th align=\"center\" colspan=\"10\">P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>Succursale</th>
					<th>Nombre de commande envoy&eacute;</th>
					<th>Nombre de reprise</th>
					<th>% Reprise commande</th>		
					<th>Nombre de reprise (Garantie)</th>
					<th>% Reprise (Garantie)</th>
					<th>Valeur net des reprises</th>
					<th>Valeur net des reprises (Garantie)</th>
					<th>Nombre reprise Dlab</th>
					<th>% reprise Dlab</th>
				</tr>";
		
		
		 //Partie a)Laval
			$message.="	
				<tr>
					<td>Laval</th>
					<td align=\"center\">$NbrEnvoyesLV</td>
					<td align=\"center\">$NbrRepriseLV</td>	
					<td align=\"center\">$PourcentageRepriseLV%</td>
					<td align=\"center\">$NbrRepriseGLV</td>
					<td align=\"center\">$PourcentageRepriseGLV%</td>
					<td align=\"center\">$ValeurRepriseLV$</td>
					<td align=\"center\">$ValeurRepriseGLV$</td>
					<td align=\"center\">$NbrRepriseDLAB_LV</td>
					<td align=\"center\">$PourcentageRepriseDLAB_LV%</td>
				</tr>";
		
		//Partie b)Drummondville
			$message.="	
				<tr>
					<td>Drummondville</th>
					<td align=\"center\">$NbrEnvoyesDR</td>
					<td align=\"center\">$NbrRepriseDR</td>
					<td align=\"center\">$PourcentageRepriseDR%</td>		
					<td align=\"center\">$NbrRepriseGDR</td>
					<td align=\"center\">$PourcentageRepriseGDR%</td>
					<td align=\"center\">$ValeurRepriseDR$</td>
					<td align=\"center\">$ValeurRepriseGDR$</td>
					<td align=\"center\">$NbrRepriseDLAB_DR</td>
					<td align=\"center\">$PourcentageRepriseDLAB_DR%</td>
				</tr>";
		
		
	//Partie c)Chicoutimi
			$message.="	
				<tr>
					<td>Chicoutimi</th>
					<td align=\"center\">$NbrEnvoyesCH</td>
					<td align=\"center\">$NbrRepriseCH</td>
					<td align=\"center\">$PourcentageRepriseCH%</td>			
					<td align=\"center\">$NbrRepriseGCH</td>
					<td align=\"center\">$PourcentageRepriseGCH%</td>
					<td align=\"center\">$ValeurRepriseCH$</td>
					<td align=\"center\">$ValeurRepriseGCH$</td>
					<td align=\"center\">$NbrRepriseDLAB_CH</td>
					<td align=\"center\">$PourcentageRepriseDLAB_CH%</td>
				</tr>";
	
  //Partie d)Trois-Rivieres
			$message.="	
				<tr>
					<td>Trois-Rivi&egrave;res</th>
					<td align=\"center\">$NbrEnvoyesTR</td>
					<td align=\"center\">$NbrRepriseTR</td>
					<td align=\"center\">$PourcentageRepriseTR%</td>				
					<td align=\"center\">$NbrRepriseGTR</td>
					<td align=\"center\">$PourcentageRepriseGTR%</td>
					<td align=\"center\">$ValeurRepriseTR$</td>
					<td align=\"center\">$ValeurRepriseGTR$</td>
					<td align=\"center\">$NbrRepriseDLAB_TR</td>
					<td align=\"center\">$PourcentageRepriseDLAB_TR%</td>
				</tr>";
		
  //Partie e)Sherbrooke
			$message.="	
				<tr>
					<td>Sherbrooke</th>
					<td align=\"center\">$NbrEnvoyesSH</td>
					<td align=\"center\">$NbrRepriseSH</td>
					<td align=\"center\">$PourcentageRepriseSH%</td>				
					<td align=\"center\">$NbrRepriseGSH</td>
					<td align=\"center\">$PourcentageRepriseGSH%</td>
					<td align=\"center\">$ValeurRepriseSH</td>
					<td align=\"center\">$ValeurRepriseGSH$</td>
					<td align=\"center\">$NbrRepriseDLAB_SH</td>
					<td align=\"center\">$PourcentageRepriseDLAB_SH%</td>
				</tr>";
			
 //Partie f)Terrebonne
			$message.="	
				<tr>
					<td>Terrebonne</th>
					<td align=\"center\">$NbrEnvoyesTE</td>
					<td align=\"center\">$NbrRepriseTE</td>
					<td align=\"center\">$PourcentageRepriseTE%</td>				
					<td align=\"center\">$NbrRepriseGTE</td>
					<td align=\"center\">$PourcentageRepriseGTE%</td>
					<td align=\"center\">$ValeurRepriseTE$</td>
					<td align=\"center\">$ValeurRepriseGTE$</td>
					<td align=\"center\">$NbrRepriseDLAB_TE</td>
					<td align=\"center\">$PourcentageRepriseDLAB_TE%</td>
				</tr>";	
		
 //Partie g)Longueuil
			$message.="	
				<tr>
					<td>Longueuil</th>
					<td align=\"center\">$NbrEnvoyesLO</td>
					<td align=\"center\">$NbrRepriseLO</td>
					<td align=\"center\">$PourcentageRepriseLO%</td>				
					<td align=\"center\">$NbrRepriseGLO</td>
					<td align=\"center\">$PourcentageRepriseGLO%</td>
					<td align=\"center\">$ValeurRepriseLO$</td>
					<td align=\"center\">$ValeurRepriseGLO$</td>
					<td align=\"center\">$NbrRepriseDLAB_LO</td>
					<td align=\"center\">$PourcentageRepriseDLAB_LO%</td>
				</tr>";	
					
 //Partie h)Levis
			$message.="	
				<tr>
					<td>L&eacute;vis</th>
					<td align=\"center\">$NbrEnvoyesLE</td>
					<td align=\"center\">$NbrRepriseLE</td>
					<td align=\"center\">$PourcentageRepriseLE%</td>				
					<td align=\"center\">$NbrRepriseGLE</td>
					<td align=\"center\">$PourcentageRepriseGLE%</td>
					<td align=\"center\">$ValeurRepriseLE$</td>
					<td align=\"center\">$ValeurRepriseGLE$</td>
					<td align=\"center\">$NbrRepriseDLAB_LE</td>
					<td align=\"center\">$PourcentageRepriseDLAB_LE%</td>
				</tr>";				
		
	
 //Partie i)Halifax
			$message.="	
				<tr>
					<td>Halifax</th>
					<td align=\"center\">$NbrEnvoyesHA</td>
					<td align=\"center\">$NbrRepriseHA</td>
					<td align=\"center\">$PourcentageRepriseHA%</td>				
					<td align=\"center\">$NbrRepriseGHA</td>
					<td align=\"center\">$PourcentageRepriseGHA%</td>
					<td align=\"center\">$ValeurRepriseHA$</td>
					<td align=\"center\">$ValeurRepriseGHA$</td>
					<td align=\"center\">$NbrRepriseDLAB_HA</td>
					<td align=\"center\">$PourcentageRepriseDLAB_HA%</td>
				</tr>";			

		
 //Partie j)Granby
			$message.="	
				<tr>
					<td>Granby</th>
					<td align=\"center\">$NbrEnvoyesGR</td>
					<td align=\"center\">$NbrRepriseGR</td>
					<td align=\"center\">$PourcentageRepriseGR%</td>			
					<td align=\"center\">$NbrRepriseGGR</td>
					<td align=\"center\">$PourcentageRepriseGGR%</td>
					<td align=\"center\">$ValeurRepriseGR$</td>
					<td align=\"center\">$ValeurRepriseGGR$</td>
					<td align=\"center\">$NbrRepriseDLAB_GR</td>
					<td align=\"center\">$PourcentageRepriseDLAB_GR%</td>
				</tr>";			
		
		
 //Partie k)Sainte-Marie
			$message.="	
				<tr>
					<td>Sainte-Marie</th>
					<td align=\"center\">$NbrEnvoyesSMB</td>
					<td align=\"center\">$NbrRepriseSMB</td>
					<td align=\"center\">$PourcentageRepriseSMB%</td>				
					<td align=\"center\">$NbrRepriseGSMB</td>
					<td align=\"center\">$PourcentageRepriseGSMB%</td>
					<td align=\"center\">$ValeurRepriseSMB$</td>
					<td align=\"center\">$ValeurRepriseGSMB$</td>
					<td align=\"center\">$NbrRepriseDLAB_SMB</td>
					<td align=\"center\">$PourcentageRepriseDLAB_SMB%</td>
				</tr>";		
		
		
		
			
 //Partie l)Quebec
			$message.="	
				<tr>
					<td>Qu&eacute;bec</th>
					<td align=\"center\">$NbrEnvoyesQC</td>
					<td align=\"center\">$NbrRepriseQC</td>
					<td align=\"center\">$PourcentageRepriseQC%</td>				
					<td align=\"center\">$NbrRepriseGQC</td>
					<td align=\"center\">$PourcentageRepriseGQC%</td>
					<td align=\"center\">$ValeurRepriseQC$</td>
					<td align=\"center\">$ValeurRepriseGQC$</td>
					<td align=\"center\">$NbrRepriseDLAB_QC</td>
					<td align=\"center\">$PourcentageRepriseDLAB_QC%</td>
				</tr>";	
		
		
 //Partie M)TOTAUX
			$message.="	
				<tr>
					<th bgcolor=\"#AFADAD\">Totaux</th>
					<th bgcolor=\"#AFADAD\" align=\"center\">$TotalCommandesEnvoyees</th>
					<th bgcolor=\"#AFADAD\" align=\"center\">$TotalReprises</th>
					<th bgcolor=\"#AFADAD\">-</th>		
					<th bgcolor=\"#AFADAD\" align=\"center\">$TotalReprisesGaranties</th>
					<th bgcolor=\"#AFADAD\">-</th>
					<th bgcolor=\"#AFADAD\" align=\"center\">$TotalValeurReprises$</th>
					<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurReprisesGaranties$</th>
					<th bgcolor=\"#AFADAD\" bgcolor=\"#AFADAD\" align=\"center\">$TotalReprisesDLAB</th>
					<th bgcolor=\"#AFADAD\">-</th>
				</tr>";		


//Partie n)MOYENNES
	$message.="	
				<tr>
					<th bgcolor=\"#6A6868\">&nbsp;</th>
					<th bgcolor=\"#6A6868\">&nbsp;</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">Moyenne</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyennePourcentageReprise%</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyenneReprisesGaranties</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyennePourcentageRepriseGaranties%</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyenneValeurReprise$</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyenneValeurRepriseGaranties$</th>
					<th bgcolor=\"#AADCA9\" bgcolor=\"#AADCA9\" align=\"center\">$MoyenneRepriseDLAB</th>
					<th bgcolor=\"#AADCA9\" align=\"center\">$MoyennePourcentageRepriseDLAB%</th>
				</tr></table>";	





//PARTIE 2: SOMMAIRE CHARLES (Separe par FOURNISSEURS)
/* Dans cet ordre:
a)Directlab STC
b)Swiss
c)Central Lab
d)Essilor Lab #1
e)Tous les autres
*/

//Recueillir les donnees

//REPRISE PAR FOURNISSEUR: DLAB
$lab = 3;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseDLAB_HG= "SELECT count(order_num) as NbrRepriseDLAB_LV, sum(order_total) as ValeurHorsGaranties_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
		   
	   case 2: 
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties   
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_DR, sum(order_total) as ValeurHorsGaranties_DR   FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	   
	   case 3:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_CH, sum(order_total) as ValeurHorsGaranties_CH   FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 4:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_TR, sum(order_total) as ValeurHorsGaranties_TR   FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_SH, sum(order_total) as ValeurHorsGaranties_SH   FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')AND redo_origin<>'retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_TE, sum(order_total) as ValeurHorsGaranties_TE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
	   //Garanties
       $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
       $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_LO, sum(order_total) as ValeurHorsGaranties_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 8:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_LE, sum(order_total) as ValeurHorsGaranties_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
       break;
		   
	   case 9:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_HA, sum(order_total) as ValeurHorsGaranties_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_GR, sum(order_total) as ValeurHorsGaranties_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_SMB FROM ORDERS WHERE user_id IN ('543'   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_SMB, sum(order_total) as ValeurHorsGaranties_SMB FROM ORDERS WHERE user_id IN ('543')   AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 12:
	   //Garanties
	   $QueryRepriseDLAB_Garanties = "SELECT count(order_num) as NbrRepriseDLAB_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseDLAB_HG = "SELECT count(order_num) as NbrRepriseDLAB_QC, sum(order_total) as ValeurHorsGaranties_QC   FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseDLAB     = mysql_query($QueryRepriseDLAB_Garanties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseDLAB          = mysql_fetch_array($resultNbrRepriseDLAB);	
	//Hors Garanties
	$resultNbrRepriseDLAB_HG  = mysql_query($QueryRepriseDLAB_HG)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseDLAB_HG       = mysql_fetch_array($resultNbrRepriseDLAB_HG);	
	
 switch($x){ 
	   case 1:$NbrRepriseDLAB_LV   = $DataRepriseDLAB[NbrRepriseDLAB_LV]; $NbrRepriseDLAB_LV_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_LV];  
	   $ValeurHorsGaranties_LV     = $DataRepriseDLAB_HG[ValeurHorsGaranties_LV]; 
	   break;
		 
	   case 2:$NbrRepriseDLAB_DR   = $DataRepriseDLAB[NbrRepriseDLAB_DR]; $NbrRepriseDLAB_DR_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_DR];  
	   $ValeurHorsGaranties_DR     = $DataRepriseDLAB_HG[ValeurHorsGaranties_DR]; 
	   break;	 
		 
	   case 3:$NbrRepriseDLAB_CH   = $DataRepriseDLAB[NbrRepriseDLAB_CH]; $NbrRepriseDLAB_CH_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_CH];   
  	   $ValeurHorsGaranties_CH     = $DataRepriseDLAB_HG[ValeurHorsGaranties_CH]; 
	   break;	 
		 
	   case 4:$NbrRepriseDLAB_TR   = $DataRepriseDLAB[NbrRepriseDLAB_TR]; $NbrRepriseDLAB_TR_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_TR];
	   $ValeurHorsGaranties_TR     = $DataRepriseDLAB_HG[ValeurHorsGaranties_TR]; 
	   break;
		 
	   case 5:$NbrRepriseDLAB_SH   = $DataRepriseDLAB[NbrRepriseDLAB_SH]; $NbrRepriseDLAB_SH_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_SH]; 
	   $ValeurHorsGaranties_SH     = $DataRepriseDLAB_HG[ValeurHorsGaranties_SH]; 
	   break;
		 
       case 6:$NbrRepriseDLAB_TE   = $DataRepriseDLAB[NbrRepriseDLAB_TE]; $NbrRepriseDLAB_TE_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_TE];   
	   $ValeurHorsGaranties_TE     = $DataRepriseDLAB_HG[ValeurHorsGaranties_TE]; 
	   break;
		 
	   case 7:$NbrRepriseDLAB_LO   = $DataRepriseDLAB[NbrRepriseDLAB_LO]; $NbrRepriseDLAB_LO_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_LO];
	   $ValeurHorsGaranties_LO     = $DataRepriseDLAB_HG[ValeurHorsGaranties_LO]; 
	   break;
		 
	   case 8:$NbrRepriseDLAB_LE   = $DataRepriseDLAB[NbrRepriseDLAB_LE]; $NbrRepriseDLAB_LE_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_LE]; 
	   $ValeurHorsGaranties_LE     = $DataRepriseDLAB_HG[ValeurHorsGaranties_LE]; 
	   break;
	 
	   case 9:$NbrRepriseDLAB_HA   = $DataRepriseDLAB[NbrRepriseDLAB_HA]; $NbrRepriseDLAB_HA_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_HA];  
	   $ValeurHorsGaranties_HA     = $DataRepriseDLAB_HG[ValeurHorsGaranties_HA]; 
	   break;
		 
	   case 10:$NbrRepriseDLAB_GR  = $DataRepriseDLAB[NbrRepriseDLAB_GR]; $NbrRepriseDLAB_GR_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_GR];  
	   $ValeurHorsGaranties_GR     = $DataRepriseDLAB_HG[ValeurHorsGaranties_GR]; 
	   break;
		 
	   case 11:$NbrRepriseDLAB_SMB = $DataRepriseDLAB[NbrRepriseDLAB_SMB];$NbrRepriseDLAB_SMB_HG= $DataRepriseDLAB_HG[NbrRepriseDLAB_SMB];
	   $ValeurHorsGaranties_SMB    = $DataRepriseDLAB_HG[ValeurHorsGaranties_SMB]; 
	   break;
		 
	   case 12:$NbrRepriseDLAB_QC  = $DataRepriseDLAB[NbrRepriseDLAB_QC]; $NbrRepriseDLAB_QC_HG = $DataRepriseDLAB_HG[NbrRepriseDLAB_QC];  
	   $ValeurHorsGaranties_QC     = $DataRepriseDLAB_HG[ValeurHorsGaranties_QC];  
	   break;
   }//End Switch	
	
}//end FOR

$SommeDLABGaranties = $NbrRepriseDLAB_LV + $NbrRepriseDLAB_DR + $NbrRepriseDLAB_CH + $NbrRepriseDLAB_TE + $NbrRepriseDLAB_TR + $NbrRepriseDLAB_SH + $NbrRepriseDLAB_LE + $NbrRepriseDLAB_HA + $NbrRepriseDLAB_LO + $NbrRepriseDLAB_GR+ $NbrRepriseDLAB_SMB + $NbrRepriseDLAB_QC;

$SommeDLAB_LAB = $NbrRepriseDLAB_LV_LAB + $NbrRepriseDLAB_DR_LAB + $NbrRepriseDLAB_CH_LAB + $NbrRepriseDLAB_TE_LAB + $NbrRepriseDLAB_TR_LAB + $NbrRepriseDLAB_SH_LAB + $NbrRepriseDLAB_LE_LAB + $NbrRepriseDLAB_HA_LAB + $NbrRepriseDLAB_LO_LAB + $NbrRepriseDLAB_GR_LAB+ $NbrRepriseDLAB_SMB_LAB + $NbrRepriseDLAB_QC_LAB;

$SommeDLAB_HG = $NbrRepriseDLAB_LV_HG + $NbrRepriseDLAB_DR_HG + $NbrRepriseDLAB_CH_HG + $NbrRepriseDLAB_TE_HG + $NbrRepriseDLAB_TR_HG + $NbrRepriseDLAB_SH_HG + $NbrRepriseDLAB_LE_HG + $NbrRepriseDLAB_HA_HG + $NbrRepriseDLAB_LO_HG + $NbrRepriseDLAB_GR_HG+ $NbrRepriseDLAB_SMB_HG + $NbrRepriseDLAB_QC_HG;

$SommeValeurDLAB_HG=$ValeurHorsGaranties_LV+$ValeurHorsGaranties_DR+$ValeurHorsGaranties_CH+$ValeurHorsGaranties_TE+$ValeurHorsGaranties_TR+$ValeurHorsGaranties_SH+$ValeurHorsGaranties_LE+$ValeurHorsGaranties_HA+$ValeurHorsGaranties_LO+$ValeurHorsGaranties_GR+$ValeurHorsGaranties_SMB+$ValeurHorsGaranties_QC;

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">Dlab: P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Nombre Garanties</th>
					<th>Nombre Hors Garanties</th>
					<th>Valeur des commandes hors garanties</th>		
				</tr>";

//DIRECTLAB SAINT-CATHARINES
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseDLAB_LV</th>
	<th align=\"center\">$NbrRepriseDLAB_LV_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LV$</th>		
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseDLAB_DR</th>
	<th align=\"center\">$NbrRepriseDLAB_DR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_DR$</th>		
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseDLAB_CH</th>
	<th align=\"center\">$NbrRepriseDLAB_CH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_CH$</th>		
</tr>
<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseDLAB_TE</th>
	<th align=\"center\">$NbrRepriseDLAB_TE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TE$</th>			
</tr>
<tr>
<th>Trois-Rivi&egrave;res</th>
	<th align=\"center\">$NbrRepriseDLAB_TR</th>
	<th align=\"center\">$NbrRepriseDLAB_TR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TR$</th>		
</tr>
<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseDLAB_SH</th>
	<th align=\"center\">$NbrRepriseDLAB_SH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SH$</th>		
</tr>
<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseDLAB_LE</th>
	<th align=\"center\">$NbrRepriseDLAB_LE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LE$</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseDLAB_HA</th>
	<th align=\"center\">$NbrRepriseDLAB_HA_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_HA$</th>		
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseDLAB_LO</th>
	<th align=\"center\">$NbrRepriseDLAB_LO_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LO$</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseDLAB_GR</th>
	<th align=\"center\">$NbrRepriseDLAB_GR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_GR$</th>		
</tr>
<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseDLAB_SMB</th>
	<th align=\"center\">$NbrRepriseDLAB_SMB_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SMB$</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseDLAB_QC</th>
	<th align=\"center\">$NbrRepriseDLAB_QC_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_QC$</th>	
</tr>
<tr>
	<th bgcolor=\"#AFADAD\">TOTAUX</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeDLABGaranties</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeDLAB_HG</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurDLAB_HG$</th>	
</tr>
</table>";
















//REPRISE PAR FOURNISSEUR: Swiss
$lab = 10;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseSWISS_HG= "SELECT count(order_num) as NbrRepriseSWISS_LV, sum(order_total) as ValeurHorsGaranties_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	   case 2: 
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties   
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_DR, sum(order_total) as ValeurHorsGaranties_DR   FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";   
	   break;
	   
	   case 3:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_CH, sum(order_total) as ValeurHorsGaranties_CH   FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 4:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_TR, sum(order_total) as ValeurHorsGaranties_TR   FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_SH, sum(order_total) as ValeurHorsGaranties_SH   FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')AND redo_origin<>'retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_TE, sum(order_total) as ValeurHorsGaranties_TE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
	   //Garanties
       $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
       $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_LO, sum(order_total) as ValeurHorsGaranties_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 8:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_LE, sum(order_total) as ValeurHorsGaranties_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
       break;
		   
	   case 9:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_HA, sum(order_total) as ValeurHorsGaranties_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_GR, sum(order_total) as ValeurHorsGaranties_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_SMB FROM ORDERS WHERE user_id IN ('gfd'   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_SMB, sum(order_total) as ValeurHorsGaranties_SMB FROM ORDERS WHERE user_id IN ('gfd'')   AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 12:
	   //Garanties
	   $QueryRepriseSWISS_Garanties = "SELECT count(order_num) as NbrRepriseSWISS_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseSWISS_HG = "SELECT count(order_num) as NbrRepriseSWISS_QC, sum(order_total) as ValeurHorsGaranties_QC   FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseSWISS     = mysql_query($QueryRepriseSWISS_Garanties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseSWISS          = mysql_fetch_array($resultNbrRepriseSWISS);	
	//Hors Garanties
	$resultNbrRepriseSWISS_HG  = mysql_query($QueryRepriseSWISS_HG)		        or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseSWISS_HG       = mysql_fetch_array($resultNbrRepriseSWISS_HG);	
	
 switch($x){ 
	   case 1:$NbrRepriseSWISS_LV  = $DataRepriseSWISS[NbrRepriseSWISS_LV]; $NbrRepriseSWISS_LV_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_LV];
	   $ValeurHorsGaranties_LV     = $DataRepriseSWISS_HG[ValeurHorsGaranties_LV]; 
	   break;
		 
	   case 2:$NbrRepriseSWISS_DR  = $DataRepriseSWISS[NbrRepriseSWISS_DR]; $NbrRepriseSWISS_DR_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_DR];  
	   $ValeurHorsGaranties_DR     = $DataRepriseSWISS_HG[ValeurHorsGaranties_DR]; 
	   break;	 
		 
	   case 3:$NbrRepriseSWISS_CH   = $DataRepriseSWISS[NbrRepriseSWISS_CH]; $NbrRepriseSWISS_CH_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_CH];  
  	   $ValeurHorsGaranties_CH     = $DataRepriseSWISS_HG[ValeurHorsGaranties_CH]; 
	   break;	 
		 
	   case 4:$NbrRepriseSWISS_TR   = $DataRepriseSWISS[NbrRepriseSWISS_TR]; $NbrRepriseSWISS_TR_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_TR]; 
	   $ValeurHorsGaranties_TR     = $DataRepriseSWISS_HG[ValeurHorsGaranties_TR]; 
	   break;
		 
	   case 5:$NbrRepriseSWISS_SH   = $DataRepriseSWISS[NbrRepriseSWISS_SH]; $NbrRepriseSWISS_SH_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_SH];   
	   $ValeurHorsGaranties_SH     = $DataRepriseSWISS_HG[ValeurHorsGaranties_SH]; 
	   break;
		 
       case 6:$NbrRepriseSWISS_TE   = $DataRepriseSWISS[NbrRepriseSWISS_TE]; $NbrRepriseSWISS_TE_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_TE];  
	   $ValeurHorsGaranties_TE     = $DataRepriseSWISS_HG[ValeurHorsGaranties_TE]; 
	   break;
		 
	   case 7:$NbrRepriseSWISS_LO   = $DataRepriseSWISS[NbrRepriseSWISS_LO]; $NbrRepriseSWISS_LO_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_LO];  
	   $ValeurHorsGaranties_LO     = $DataRepriseSWISS_HG[ValeurHorsGaranties_LO]; 
	   break;
		 
	   case 8:$NbrRepriseSWISS_LE   = $DataRepriseSWISS[NbrRepriseSWISS_LE]; $NbrRepriseSWISS_LE_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_LE]; 
	   $ValeurHorsGaranties_LE     = $DataRepriseSWISS_HG[ValeurHorsGaranties_LE]; 
	   break;
	 
	   case 9:$NbrRepriseSWISS_HA   = $DataRepriseSWISS[NbrRepriseSWISS_HA]; $NbrRepriseSWISS_HA_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_HA];  
	   $ValeurHorsGaranties_HA     = $DataRepriseSWISS_HG[ValeurHorsGaranties_HA]; 
	   break;
		 
	   case 10:$NbrRepriseSWISS_GR  = $DataRepriseSWISS[NbrRepriseSWISS_GR]; $NbrRepriseSWISS_GR_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_GR];  
	   $ValeurHorsGaranties_GR     = $DataRepriseSWISS_HG[ValeurHorsGaranties_GR]; 
	   break;
		 
	   case 11:$NbrRepriseSWISS_SMB = $DataRepriseSWISS[NbrRepriseSWISS_SMB];$NbrRepriseSWISS_SMB_HG= $DataRepriseSWISS_HG[NbrRepriseSWISS_SMB]; 
	   $ValeurHorsGaranties_SMB    = $DataRepriseSWISS_HG[ValeurHorsGaranties_SMB]; 
	   break;
		 
	   case 12:$NbrRepriseSWISS_QC  = $DataRepriseSWISS[NbrRepriseSWISS_QC]; $NbrRepriseSWISS_QC_HG = $DataRepriseSWISS_HG[NbrRepriseSWISS_QC];   
	   $ValeurHorsGaranties_QC      = $DataRepriseSWISS_HG[ValeurHorsGaranties_QC];  
	   break;
   }//End Switch	
	
}//end FOR

$SommeSWISSGaranties = $NbrRepriseSWISS_LV + $NbrRepriseSWISS_DR + $NbrRepriseSWISS_CH + $NbrRepriseSWISS_TE + $NbrRepriseSWISS_TR + $NbrRepriseSWISS_SH + $NbrRepriseSWISS_LE + $NbrRepriseSWISS_HA + $NbrRepriseSWISS_LO + $NbrRepriseSWISS_GR+ $NbrRepriseSWISS_SMB + $NbrRepriseSWISS_QC;


$SommeSWISS_HG = $NbrRepriseSWISS_LV_HG + $NbrRepriseSWISS_DR_HG + $NbrRepriseSWISS_CH_HG + $NbrRepriseSWISS_TE_HG + $NbrRepriseSWISS_TR_HG + $NbrRepriseSWISS_SH_HG + $NbrRepriseSWISS_LE_HG + $NbrRepriseSWISS_HA_HG + $NbrRepriseSWISS_LO_HG + $NbrRepriseSWISS_GR_HG+ $NbrRepriseSWISS_SMB_HG + $NbrRepriseSWISS_QC_HG;

$SommeValeurSWISS_HG=$ValeurHorsGaranties_LV+$ValeurHorsGaranties_DR+$ValeurHorsGaranties_CH+$ValeurHorsGaranties_TE+$ValeurHorsGaranties_TR+$ValeurHorsGaranties_SH+$ValeurHorsGaranties_LE+$ValeurHorsGaranties_HA+$ValeurHorsGaranties_LO+$ValeurHorsGaranties_GR+$ValeurHorsGaranties_SMB+$ValeurHorsGaranties_QC;


$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">Swiss: P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Nombre Garanties</th>
					<th>Nombre Hors Garanties</th>
					<th>Valeur des commandes hors garanties</th>		
				</tr>";

//DIRECTLAB SWISS
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseSWISS_LV</th>
	<th align=\"center\">$NbrRepriseSWISS_LV_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LV$</th>		
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseSWISS_DR</th>
	<th align=\"center\">$NbrRepriseSWISS_DR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_DR$</th>		
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseSWISS_CH</th>
	<th align=\"center\">$NbrRepriseSWISS_CH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_CH$</th>		
</tr>
<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseSWISS_TE</th>
	<th align=\"center\">$NbrRepriseSWISS_TE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TE$</th>			
</tr>
<tr>
<th>Trois-Rivi&egrave;res</th>
	<th align=\"center\">$NbrRepriseSWISS_TR</th>
	<th align=\"center\">$NbrRepriseSWISS_TR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TR$</th>		
</tr>
<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseSWISS_SH</th>
	<th align=\"center\">$NbrRepriseSWISS_SH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SH$</th>		
</tr>
<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseSWISS_LE</th>
	<th align=\"center\">$NbrRepriseSWISS_LE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LE$</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseSWISS_HA</th>
	<th align=\"center\">$NbrRepriseSWISS_HA_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_HA$</th>		
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseSWISS_LO</th>
	<th align=\"center\">$NbrRepriseSWISS_LO_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LO$</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseSWISS_GR</th>
	<th align=\"center\">$NbrRepriseSWISS_GR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_GR$</th>		
</tr>
<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseSWISS_SMB</th>
	<th align=\"center\">$NbrRepriseSWISS_SMB_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SMB$</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseSWISS_QC</th>
	<th align=\"center\">$NbrRepriseSWISS_QC_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_QC$</th>	
</tr>
<tr>
	<th bgcolor=\"#AFADAD\" bgcolor=\"#AFADAD\">TOTAUX</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeSWISSGaranties</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeSWISS_HG</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurSWISS_HG$</th>	
</tr>
</table>";















//REPRISE PAR FOURNISSEUR: HKO
$lab = 25;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseHKO_HG= "SELECT count(order_num) as NbrRepriseHKO_LV, sum(order_total) as ValeurHorsGaranties_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	   case 2: 
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties   
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_DR, sum(order_total) as ValeurHorsGaranties_DR   FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	   
	   case 3:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_CH, sum(order_total) as ValeurHorsGaranties_CH   FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 4:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_TR, sum(order_total) as ValeurHorsGaranties_TR   FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_SH, sum(order_total) as ValeurHorsGaranties_SH   FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')AND redo_origin<>'retour_client' 
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_TE, sum(order_total) as ValeurHorsGaranties_TE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 7:
	   //Garanties
       $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
       $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_LO, sum(order_total) as ValeurHorsGaranties_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 8:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_LE, sum(order_total) as ValeurHorsGaranties_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
       break;
		   
	   case 9:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_HA, sum(order_total) as ValeurHorsGaranties_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";    
	   break;
		   
	   case 10:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_GR, sum(order_total) as ValeurHorsGaranties_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_SMB FROM ORDERS WHERE user_id IN ('gfd'   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_SMB, sum(order_total) as ValeurHorsGaranties_SMB FROM ORDERS WHERE user_id IN ('dsa'   AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 12:
	   //Garanties
	   $QueryRepriseHKO_Garanties = "SELECT count(order_num) as NbrRepriseHKO_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseHKO_HG = "SELECT count(order_num) as NbrRepriseHKO_QC, sum(order_total) as ValeurHorsGaranties_QC   FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseHKO     = mysql_query($QueryRepriseHKO_Garanties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseHKO          = mysql_fetch_array($resultNbrRepriseHKO);	
	//Hors Garanties
	$resultNbrRepriseHKO_HG  = mysql_query($QueryRepriseHKO_HG)		        or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseHKO_HG        = mysql_fetch_array($resultNbrRepriseHKO_HG);	
	
 switch($x){ 
	   case 1:$NbrRepriseHKO_LV   = $DataRepriseHKO[NbrRepriseHKO_LV]; $NbrRepriseHKO_LV_HG = $DataRepriseHKO_HG[NbrRepriseHKO_LV]; 
	   $ValeurHorsGaranties_LV    = $DataRepriseHKO_HG[ValeurHorsGaranties_LV]; 
	   break;
		 
	   case 2:$NbrRepriseHKO_DR   = $DataRepriseHKO[NbrRepriseHKO_DR]; $NbrRepriseHKO_DR_HG = $DataRepriseHKO_HG[NbrRepriseHKO_DR];   
	   $ValeurHorsGaranties_DR    = $DataRepriseHKO_HG[ValeurHorsGaranties_DR]; 
	   break;	 
		 
	   case 3:$NbrRepriseHKO_CH   = $DataRepriseHKO[NbrRepriseHKO_CH]; $NbrRepriseHKO_CH_HG = $DataRepriseHKO_HG[NbrRepriseHKO_CH]; 
  	   $ValeurHorsGaranties_CH    = $DataRepriseHKO_HG[ValeurHorsGaranties_CH]; 
	   break;	 
		 
	   case 4:$NbrRepriseHKO_TR   = $DataRepriseHKO[NbrRepriseHKO_TR]; $NbrRepriseHKO_TR_HG = $DataRepriseHKO_HG[NbrRepriseHKO_TR]; 
	   $ValeurHorsGaranties_TR    = $DataRepriseHKO_HG[ValeurHorsGaranties_TR]; 
	   break;
		 
	   case 5:$NbrRepriseHKO_SH   = $DataRepriseHKO[NbrRepriseHKO_SH]; $NbrRepriseHKO_SH_HG = $DataRepriseHKO_HG[NbrRepriseHKO_SH];  
	   $ValeurHorsGaranties_SH    = $DataRepriseHKO_HG[ValeurHorsGaranties_SH]; 
	   break;
		 
       case 6:$NbrRepriseHKO_TE   = $DataRepriseHKO[NbrRepriseHKO_TE]; $NbrRepriseHKO_TE_HG = $DataRepriseHKO_HG[NbrRepriseHKO_TE];
	   $ValeurHorsGaranties_TE    = $DataRepriseHKO_HG[ValeurHorsGaranties_TE]; 
	   break;
		 
	   case 7:$NbrRepriseHKO_LO   = $DataRepriseHKO[NbrRepriseHKO_LO]; $NbrRepriseHKO_LO_HG = $DataRepriseHKO_HG[NbrRepriseHKO_LO]; 
	   $ValeurHorsGaranties_LO    = $DataRepriseHKO_HG[ValeurHorsGaranties_LO]; 
	   break;
		 
	   case 8:$NbrRepriseHKO_LE   = $DataRepriseHKO[NbrRepriseHKO_LE]; $NbrRepriseHKO_LE_HG = $DataRepriseHKO_HG[NbrRepriseHKO_LE];
	   $ValeurHorsGaranties_LE    = $DataRepriseHKO_HG[ValeurHorsGaranties_LE]; 
	   break;
	 
	   case 9:$NbrRepriseHKO_HA   = $DataRepriseHKO[NbrRepriseHKO_HA]; $NbrRepriseHKO_HA_HG = $DataRepriseHKO_HG[NbrRepriseHKO_HA];  
	   $ValeurHorsGaranties_HA    = $DataRepriseHKO_HG[ValeurHorsGaranties_HA]; 
	   break;
		 
	   case 10:$NbrRepriseHKO_GR  = $DataRepriseHKO[NbrRepriseHKO_GR]; $NbrRepriseHKO_GR_HG = $DataRepriseHKO_HG[NbrRepriseHKO_GR];
	   $ValeurHorsGaranties_GR    = $DataRepriseHKO_HG[ValeurHorsGaranties_GR]; 
	   break;
		 
	   case 11:$NbrRepriseHKO_SMB = $DataRepriseHKO[NbrRepriseHKO_SMB];$NbrRepriseHKO_SMB_HG= $DataRepriseHKO_HG[NbrRepriseHKO_SMB]; 
	   $ValeurHorsGaranties_SMB   = $DataRepriseHKO_HG[ValeurHorsGaranties_SMB]; 
	   break;
		 
	   case 12:$NbrRepriseHKO_QC  = $DataRepriseHKO[NbrRepriseHKO_QC]; $NbrRepriseHKO_QC_HG = $DataRepriseHKO_HG[NbrRepriseHKO_QC]; 
	   $ValeurHorsGaranties_QC    = $DataRepriseHKO_HG[ValeurHorsGaranties_QC];  
	   break;
   }//End Switch	
	
}//end FOR

$SommeHKOGaranties = $NbrRepriseHKO_LV + $NbrRepriseHKO_DR + $NbrRepriseHKO_CH + $NbrRepriseHKO_TE + $NbrRepriseHKO_TR + $NbrRepriseHKO_SH + $NbrRepriseHKO_LE + $NbrRepriseHKO_HA + $NbrRepriseHKO_LO + $NbrRepriseHKO_GR+ $NbrRepriseHKO_SMB + $NbrRepriseHKO_QC;

$SommeHKO_HG = $NbrRepriseHKO_LV_HG + $NbrRepriseHKO_DR_HG + $NbrRepriseHKO_CH_HG + $NbrRepriseHKO_TE_HG + $NbrRepriseHKO_TR_HG + $NbrRepriseHKO_SH_HG + $NbrRepriseHKO_LE_HG + $NbrRepriseHKO_HA_HG + $NbrRepriseHKO_LO_HG + $NbrRepriseHKO_GR_HG+ $NbrRepriseHKO_SMB_HG + $NbrRepriseHKO_QC_HG;

$SommeValeurHKO_HG=$ValeurHorsGaranties_LV+$ValeurHorsGaranties_DR+$ValeurHorsGaranties_CH+$ValeurHorsGaranties_TE+$ValeurHorsGaranties_TR+$ValeurHorsGaranties_SH+$ValeurHorsGaranties_LE+$ValeurHorsGaranties_HA+$ValeurHorsGaranties_LO+$ValeurHorsGaranties_GR+$ValeurHorsGaranties_SMB+$ValeurHorsGaranties_QC;

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">HKO: P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Nombre Garanties</th>
					<th>Nombre Hors Garanties</th>
					<th>Valeur des commandes hors garanties</th>		
				</tr>";

//HKO
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseHKO_LV</th>
	<th align=\"center\">$NbrRepriseHKO_LV_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LV$</th>		
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseHKO_DR</th>
	<th align=\"center\">$NbrRepriseHKO_DR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_DR$</th>		
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseHKO_CH</th>
	<th align=\"center\">$NbrRepriseHKO_CH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_CH$</th>		
</tr>
<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseHKO_TE</th>
	<th align=\"center\">$NbrRepriseHKO_TE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TE$</th>			
</tr>
<tr>
<th>Trois-Rivi&egrave;res</th>
	<th align=\"center\">$NbrRepriseHKO_TR</th>
	<th align=\"center\">$NbrRepriseHKO_TR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TR$</th>		
</tr>
<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseHKO_SH</th>
	<th align=\"center\">$NbrRepriseHKO_SH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SH$</th>		
</tr>
<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseHKO_LE</th>
	<th align=\"center\">$NbrRepriseHKO_LE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LE$</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseHKO_HA</th>
	<th align=\"center\">$NbrRepriseHKO_HA_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_HA$</th>		
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseHKO_LO</th>
	<th align=\"center\">$NbrRepriseHKO_LO_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LO$</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseHKO_GR</th>
	<th align=\"center\">$NbrRepriseHKO_GR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_GR$</th>		
</tr>
<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseHKO_SMB</th>
	<th align=\"center\">$NbrRepriseHKO_SMB_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SMB$</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseHKO_QC</th>
	<th align=\"center\">$NbrRepriseHKO_QC_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_QC$</th>	
</tr>
<tr>
	<th bgcolor=\"#AFADAD\">TOTAUX</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeHKOGaranties</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeHKO_HG</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurHKO_HG$</th>	
</tr>
</table>";














//REPRISE PAR FOURNISSEUR: GKB
$lab = 69;
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseGKB_HG= "SELECT count(order_num) as NbrRepriseGKB_LV, sum(order_total) as ValeurHorsGaranties_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	   case 2: 
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties   
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_DR, sum(order_total) as ValeurHorsGaranties_DR   FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";   
	   break;
	   
	   case 3:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_CH, sum(order_total) as ValeurHorsGaranties_CH   FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 4:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_TR, sum(order_total) as ValeurHorsGaranties_TR   FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break; 
		   
	   case 5:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_SH, sum(order_total) as ValeurHorsGaranties_SH   FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')AND redo_origin<>'retour_client' 
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_TE, sum(order_total) as ValeurHorsGaranties_TE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 7:
	   //Garanties
       $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
       $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_LO, sum(order_total) as ValeurHorsGaranties_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	   case 8:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_LE, sum(order_total) as ValeurHorsGaranties_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
       break;
		   
	   case 9:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_HA, sum(order_total) as ValeurHorsGaranties_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
		   
	   case 10:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_GR, sum(order_total) as ValeurHorsGaranties_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_SMB FROM ORDERS WHERE user_id IN ('fds')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_SMB, sum(order_total) as ValeurHorsGaranties_SMB FROM ORDERS WHERE user_id IN ('gfd')   AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 12:
	   //Garanties
	   $QueryRepriseGKB_Garanties = "SELECT count(order_num) as NbrRepriseGKB_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseGKB_HG = "SELECT count(order_num) as NbrRepriseGKB_QC, sum(order_total) as ValeurHorsGaranties_QC   FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab= $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseGKB      = mysql_query($QueryRepriseGKB_Garanties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseGKB           = mysql_fetch_array($resultNbrRepriseGKB);	
	//Hors Garanties
	$resultNbrRepriseGKB_HG   = mysql_query($QueryRepriseGKB_HG)		        or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseGKB_HG        = mysql_fetch_array($resultNbrRepriseGKB_HG);	
	
 switch($x){ 
	   case 1:$NbrRepriseGKB_LV   = $DataRepriseGKB[NbrRepriseGKB_LV]; $NbrRepriseGKB_LV_HG = $DataRepriseGKB_HG[NbrRepriseGKB_LV]; $ValeurHorsGaranties_LV    = $DataRepriseGKB_HG[ValeurHorsGaranties_LV]; 
	   break;
		 
	   case 2:$NbrRepriseGKB_DR   = $DataRepriseGKB[NbrRepriseGKB_DR]; $NbrRepriseGKB_DR_HG = $DataRepriseGKB_HG[NbrRepriseGKB_DR]; $ValeurHorsGaranties_DR    = $DataRepriseGKB_HG[ValeurHorsGaranties_DR]; 
	   break;	 
		 
	   case 3:$NbrRepriseGKB_CH   = $DataRepriseGKB[NbrRepriseGKB_CH]; $NbrRepriseGKB_CH_HG = $DataRepriseGKB_HG[NbrRepriseGKB_CH]; $ValeurHorsGaranties_CH    = $DataRepriseGKB_HG[ValeurHorsGaranties_CH]; 
	   break;	 
		 
	   case 4:$NbrRepriseGKB_TR   = $DataRepriseGKB[NbrRepriseGKB_TR]; $NbrRepriseGKB_TR_HG = $DataRepriseGKB_HG[NbrRepriseGKB_TR]; $ValeurHorsGaranties_TR    = $DataRepriseGKB_HG[ValeurHorsGaranties_TR]; 
	   break;
		 
	   case 5:$NbrRepriseGKB_SH   = $DataRepriseGKB[NbrRepriseGKB_SH]; $NbrRepriseGKB_SH_HG = $DataRepriseGKB_HG[NbrRepriseGKB_SH]; $ValeurHorsGaranties_SH    = $DataRepriseGKB_HG[ValeurHorsGaranties_SH]; 
	   break;
		 
       case 6:$NbrRepriseGKB_TE   = $DataRepriseGKB[NbrRepriseGKB_TE]; $NbrRepriseGKB_TE_HG = $DataRepriseGKB_HG[NbrRepriseGKB_TE]; $ValeurHorsGaranties_TE    = $DataRepriseGKB_HG[ValeurHorsGaranties_TE]; 
	   break;
		 
	   case 7:$NbrRepriseGKB_LO   = $DataRepriseGKB[NbrRepriseGKB_LO]; $NbrRepriseGKB_LO_HG = $DataRepriseGKB_HG[NbrRepriseGKB_LO]; $ValeurHorsGaranties_LO    = $DataRepriseGKB_HG[ValeurHorsGaranties_LO]; 
	   break;
		 
	   case 8:$NbrRepriseGKB_LE   = $DataRepriseGKB[NbrRepriseGKB_LE]; $NbrRepriseGKB_LE_HG = $DataRepriseGKB_HG[NbrRepriseGKB_LE]; $ValeurHorsGaranties_LE    = $DataRepriseGKB_HG[ValeurHorsGaranties_LE]; 
	   break;
	 
	   case 9:$NbrRepriseGKB_HA   = $DataRepriseGKB[NbrRepriseGKB_HA]; $NbrRepriseGKB_HA_HG = $DataRepriseGKB_HG[NbrRepriseGKB_HA]; $ValeurHorsGaranties_HA    = $DataRepriseGKB_HG[ValeurHorsGaranties_HA]; 
	   break;
		 
	   case 10:$NbrRepriseGKB_GR  = $DataRepriseGKB[NbrRepriseGKB_GR]; $NbrRepriseGKB_GR_HG = $DataRepriseGKB_HG[NbrRepriseGKB_GR]; $ValeurHorsGaranties_GR    = $DataRepriseGKB_HG[ValeurHorsGaranties_GR]; 
	   break;
		 
	   case 11:$NbrRepriseGKB_SMB = $DataRepriseGKB[NbrRepriseGKB_SMB];$NbrRepriseGKB_SMB_HG= $DataRepriseGKB_HG[NbrRepriseGKB_SMB]; $ValeurHorsGaranties_SMB   = $DataRepriseGKB_HG[ValeurHorsGaranties_SMB]; 
	   break;
		 
	   case 12:$NbrRepriseGKB_QC  = $DataRepriseGKB[NbrRepriseGKB_QC]; $NbrRepriseGKB_QC_HG = $DataRepriseGKB_HG[NbrRepriseGKB_QC];  $ValeurHorsGaranties_QC    = $DataRepriseGKB_HG[ValeurHorsGaranties_QC];  
	   break;
   }//End Switch	
	
}//end FOR

$SommeGKBGaranties = $NbrRepriseGKB_LV + $NbrRepriseGKB_DR + $NbrRepriseGKB_CH + $NbrRepriseGKB_TE + $NbrRepriseGKB_TR + $NbrRepriseGKB_SH + $NbrRepriseGKB_LE + $NbrRepriseGKB_HA + $NbrRepriseGKB_LO + $NbrRepriseGKB_GR+ $NbrRepriseGKB_SMB + $NbrRepriseGKB_QC;

$SommeGKB_HG = $NbrRepriseGKB_LV_HG + $NbrRepriseGKB_DR_HG + $NbrRepriseGKB_CH_HG + $NbrRepriseGKB_TE_HG + $NbrRepriseGKB_TR_HG + $NbrRepriseGKB_SH_HG + $NbrRepriseGKB_LE_HG + $NbrRepriseGKB_HA_HG + $NbrRepriseGKB_LO_HG + $NbrRepriseGKB_GR_HG+ $NbrRepriseGKB_SMB_HG + $NbrRepriseGKB_QC_HG;

$SommeValeurGKB_HG=$ValeurHorsGaranties_LV+$ValeurHorsGaranties_DR+$ValeurHorsGaranties_CH+$ValeurHorsGaranties_TE+$ValeurHorsGaranties_TR+$ValeurHorsGaranties_SH+$ValeurHorsGaranties_LE+$ValeurHorsGaranties_HA+$ValeurHorsGaranties_LO+$ValeurHorsGaranties_GR+$ValeurHorsGaranties_SMB+$ValeurHorsGaranties_QC;

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">GKB: P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Nombre Garanties</th>
					<th>Nombre Hors Garanties</th>
					<th>Valeur des commandes hors garanties</th>		
				</tr>";

//GKB
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseGKB_LV</th>
	<th align=\"center\">$NbrRepriseGKB_LV_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LV$</th>		
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseGKB_DR</th>
	<th align=\"center\">$NbrRepriseGKB_DR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_DR$</th>		
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseGKB_CH</th>
	<th align=\"center\">$NbrRepriseGKB_CH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_CH$</th>		
</tr>
<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseGKB_TE</th>
	<th align=\"center\">$NbrRepriseGKB_TE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TE$</th>			
</tr>
<tr>
<th>Trois-Rivi&egrave;res</th>
	<th align=\"center\">$NbrRepriseGKB_TR</th>
	<th align=\"center\">$NbrRepriseGKB_TR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TR$</th>		
</tr>
<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseGKB_SH</th>
	<th align=\"center\">$NbrRepriseGKB_SH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SH$</th>		
</tr>
<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseGKB_LE</th>
	<th align=\"center\">$NbrRepriseGKB_LE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LE$</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseGKB_HA</th>
	<th align=\"center\">$NbrRepriseGKB_HA_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_HA$</th>		
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseGKB_LO</th>
	<th align=\"center\">$NbrRepriseGKB_LO_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LO$</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseGKB_GR</th>
	<th align=\"center\">$NbrRepriseGKB_GR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_GR$</th>		
</tr>
<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseGKB_SMB</th>
	<th align=\"center\">$NbrRepriseGKB_SMB_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SMB$</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseGKB_QC</th>
	<th align=\"center\">$NbrRepriseGKB_QC_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_QC$</th>	
</tr>
<tr>
	<th bgcolor=\"#AFADAD\">TOTAUX</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeGKBGaranties</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeGKB_HG</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurGKB_HG$</th>	
</tr>
</table>";











//REPRISE PAR FOURNISSEUR: AUTRES que Dlab, Swiss, HKO et GKB
$lab= " NOT IN (3,10,25,69)";
for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors garanties
	   $QueryRepriseAUTRES_HG= "SELECT count(order_num) as NbrRepriseAUTRES_LV, sum(order_total) as ValeurHorsGaranties_LV  FROM ORDERS WHERE user_id IN ('laval','lavalsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;  
	   case 2: 
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_DR  FROM ORDERS WHERE user_id IN ('entrepotdr','safedr') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties   
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_DR, sum(order_total) as ValeurHorsGaranties_DR   FROM ORDERS WHERE user_id IN ('entrepotdr','safedr')AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	   
	   case 3:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_CH  FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   //Hors garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_CH, sum(order_total) as ValeurHorsGaranties_CH   FROM ORDERS WHERE user_id IN ('chicoutimi','chicoutimisafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 4:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_TR  FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_TR, sum(order_total) as ValeurHorsGaranties_TR   FROM ORDERS WHERE user_id IN ('entrepotifc','entrepotsafe') AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";   
	   break; 
		   
	   case 5:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_SH  FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe') AND redo_origin='retour_client'  
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_SH, sum(order_total) as ValeurHorsGaranties_SH   FROM ORDERS WHERE user_id IN ('sherbrooke','sherbrookesafe')AND redo_origin<>'retour_client' 
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  
	   case 6:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_TE FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_TE, sum(order_total) as ValeurHorsGaranties_TE  FROM ORDERS WHERE user_id IN ('terrebonne','terrebonnesafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		  
	  
		   
	   case 7:
	   //Garanties
       $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
       $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_LO, sum(order_total) as ValeurHorsGaranties_LO FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";
	   break;
		   
	 
	   case 8:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_LE, sum(order_total) as ValeurHorsGaranties_LE  FROM ORDERS WHERE user_id IN ('levis','levissafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
       break;
		   
	   case 9:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_HA, sum(order_total) as ValeurHorsGaranties_HA  FROM ORDERS WHERE user_id IN ('warehousehal','warehousehalsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab  AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   break;
	  //RENDU ICI  
	   case 10:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_GR, sum(order_total) as ValeurHorsGaranties_GR  FROM ORDERS WHERE user_id IN ('granby','granbysafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL    AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 11:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_SMB FROM ORDERS WHERE user_id IN ('hgf'   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'";  
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_SMB, sum(order_total) as ValeurHorsGaranties_SMB FROM ORDERS WHERE user_id IN ('hgf')   AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL   AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
		   
	   case 12:
	   //Garanties
	   $QueryRepriseAUTRES_Garanties = "SELECT count(order_num) as NbrRepriseAUTRES_QC  FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')   AND redo_origin='retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   //Hors Garanties
	   $QueryRepriseAUTRES_HG = "SELECT count(order_num) as NbrRepriseAUTRES_QC, sum(order_total) as ValeurHorsGaranties_QC   FROM ORDERS WHERE user_id IN ('entrepotquebec','quebecsafe')  AND redo_origin<>'retour_client'
	   AND redo_order_num IS NOT NULL  AND prescript_lab $lab AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
	   break;
   }//End Switch

	//Garanties
	$resultNbrRepriseAUTRES      = mysql_query($QueryRepriseAUTRES_Garanties)		or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseAUTRES           = mysql_fetch_array($resultNbrRepriseAUTRES);	
	//Hors Garanties
	$resultNbrRepriseAUTRES_HG   = mysql_query($QueryRepriseAUTRES_HG)		        or die  ('I cannot select items because: ' . mysql_error());
	$DataRepriseAUTRES_HG        = mysql_fetch_array($resultNbrRepriseAUTRES_HG);	
	
 switch($x){ 
	   case 1:$NbrRepriseAUTRES_LV  = $DataRepriseAUTRES[NbrRepriseAUTRES_LV]; $NbrRepriseAUTRES_LV_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LV]; $ValeurHorsGaranties_LV = $DataRepriseAUTRES_HG[ValeurHorsGaranties_LV]; 
	   break;
		 
	   case 2:$NbrRepriseAUTRES_DR  = $DataRepriseAUTRES[NbrRepriseAUTRES_DR]; $NbrRepriseAUTRES_DR_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_DR]; $ValeurHorsGaranties_DR  = $DataRepriseAUTRES_HG[ValeurHorsGaranties_DR]; 
	   break;	 
		 
	   case 3:$NbrRepriseAUTRES_CH  = $DataRepriseAUTRES[NbrRepriseAUTRES_CH]; $NbrRepriseAUTRES_CH_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_CH];  $ValeurHorsGaranties_CH  = $DataRepriseAUTRES_HG[ValeurHorsGaranties_CH]; 
	   break;	 
		
	   case 4:$NbrRepriseAUTRES_TR  = $DataRepriseAUTRES[NbrRepriseAUTRES_TR]; $NbrRepriseAUTRES_TR_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_TR];$ValeurHorsGaranties_TR   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_TR]; 
	   break;
		 
	   case 5:$NbrRepriseAUTRES_SH  = $DataRepriseAUTRES[NbrRepriseAUTRES_SH]; $NbrRepriseAUTRES_SH_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_SH]; $ValeurHorsGaranties_SH  = $DataRepriseAUTRES_HG[ValeurHorsGaranties_SH]; 
	   break;
		 
       case 6:$NbrRepriseAUTRES_TE  = $DataRepriseAUTRES[NbrRepriseAUTRES_TE]; $NbrRepriseAUTRES_TE_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_TE]; $ValeurHorsGaranties_TE   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_TE]; 
	   break;
		 
	   case 7:$NbrRepriseAUTRES_LO  = $DataRepriseAUTRES[NbrRepriseAUTRES_LO]; $NbrRepriseAUTRES_LO_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LO]; $ValeurHorsGaranties_LO   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_LO]; 
	   break;
		 
	   case 8:$NbrRepriseAUTRES_LE  = $DataRepriseAUTRES[NbrRepriseAUTRES_LE]; $NbrRepriseAUTRES_LE_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_LE]; $ValeurHorsGaranties_LE   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_LE]; 
	   break;
	 
	   case 9:$NbrRepriseAUTRES_HA  = $DataRepriseAUTRES[NbrRepriseAUTRES_HA]; $NbrRepriseAUTRES_HA_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_HA]; $ValeurHorsGaranties_HA   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_HA]; 
	   break;
		 
	   case 10:$NbrRepriseAUTRES_GR = $DataRepriseAUTRES[NbrRepriseAUTRES_GR]; $NbrRepriseAUTRES_GR_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_GR]; $ValeurHorsGaranties_GR   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_GR]; 
	   break;
		 
	   case 11:$NbrRepriseAUTRES_SMB = $DataRepriseAUTRES[NbrRepriseAUTRES_SMB];$NbrRepriseAUTRES_SMB_HG= $DataRepriseAUTRES_HG[NbrRepriseAUTRES_SMB]; $ValeurHorsGaranties_SMB = $DataRepriseAUTRES_HG[ValeurHorsGaranties_SMB]; 
	   break;
		 
	   case 12:$NbrRepriseAUTRES_QC  = $DataRepriseAUTRES[NbrRepriseAUTRES_QC]; $NbrRepriseAUTRES_QC_HG = $DataRepriseAUTRES_HG[NbrRepriseAUTRES_QC]; $ValeurHorsGaranties_QC   = $DataRepriseAUTRES_HG[ValeurHorsGaranties_QC];  
	   break;
   }//End Switch	
	
}//end FOR

$SommeAUTRESGaranties = $NbrRepriseAUTRES_LV + $NbrRepriseAUTRES_DR + $NbrRepriseAUTRES_CH + $NbrRepriseAUTRES_TE + $NbrRepriseAUTRES_TR + $NbrRepriseAUTRES_SH + $NbrRepriseAUTRES_LE + $NbrRepriseAUTRES_HA + $NbrRepriseAUTRES_LO + $NbrRepriseAUTRES_GR+ $NbrRepriseAUTRES_SMB + $NbrRepriseAUTRES_QC;

$SommeAUTRES_HG = $NbrRepriseAUTRES_LV_HG + $NbrRepriseAUTRES_DR_HG + $NbrRepriseAUTRES_CH_HG + $NbrRepriseAUTRES_TE_HG + $NbrRepriseAUTRES_TR_HG + $NbrRepriseAUTRES_SH_HG + $NbrRepriseAUTRES_LE_HG + $NbrRepriseAUTRES_HA_HG + $NbrRepriseAUTRES_LO_HG + $NbrRepriseAUTRES_GR_HG+ $NbrRepriseAUTRES_SMB_HG + $NbrRepriseAUTRES_QC_HG;

$SommeValeurAUTRES_HG=$ValeurHorsGaranties_LV+$ValeurHorsGaranties_DR+$ValeurHorsGaranties_CH+$ValeurHorsGaranties_TE+$ValeurHorsGaranties_TR+$ValeurHorsGaranties_SH+$ValeurHorsGaranties_LE+$ValeurHorsGaranties_HA+$ValeurHorsGaranties_LO+$ValeurHorsGaranties_GR+$ValeurHorsGaranties_SMB+$ValeurHorsGaranties_QC;

$message.="<br><br><table width=\"900\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.="	<tr bgcolor=\"CCCCCC\">
                	<th align=\"center\" colspan=\"10\">AUTRES: P&eacute;riode du $date1 au $date2 </th>
				</tr>
				
				<tr>
					<th>&nbsp;</th>
					<th>Nombre Garanties</th>
					<th>Nombre Hors Garanties</th>
					<th>Valeur des commandes hors garanties</th>		
				</tr>";

//AUTRES
$message.="	
<tr>
	<th>Laval</th>
	<th align=\"center\">$NbrRepriseAUTRES_LV</th>
	<th align=\"center\">$NbrRepriseAUTRES_LV_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LV$</th>		
</tr>
<tr>
	<th>Drummondville</th>
	<th align=\"center\">$NbrRepriseAUTRES_DR</th>
	<th align=\"center\">$NbrRepriseAUTRES_DR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_DR$</th>		
</tr>
<tr>
	<th>Chicoutimi</th>
	<th align=\"center\">$NbrRepriseAUTRES_CH</th>
	<th align=\"center\">$NbrRepriseAUTRES_CH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_CH$</th>		
</tr>
<tr>
	<th>Terrebonne</th>
	<th align=\"center\">$NbrRepriseAUTRES_TE</th>
	<th align=\"center\">$NbrRepriseAUTRES_TE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TE$</th>			
</tr>
<tr>
<th>Trois-Rivi&egrave;res</th>
	<th align=\"center\">$NbrRepriseAUTRES_TR</th>
	<th align=\"center\">$NbrRepriseAUTRES_TR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_TR$</th>		
</tr>
<tr>
	<th>Sherbrooke</th>
	<th align=\"center\">$NbrRepriseAUTRES_SH</th>
	<th align=\"center\">$NbrRepriseAUTRES_SH_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SH$</th>		
</tr>
<tr>
	<th>L&eacute;vis</th>
	<th align=\"center\">$NbrRepriseAUTRES_LE</th>
	<th align=\"center\">$NbrRepriseAUTRES_LE_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LE$</th>		
</tr>
<tr>
	<th>Halifax</th>
	<th align=\"center\">$NbrRepriseAUTRES_HA</th>
	<th align=\"center\">$NbrRepriseAUTRES_HA_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_HA$</th>		
</tr>
<tr>
	<th>Longueuil</th>
	<th align=\"center\">$NbrRepriseAUTRES_LO</th>
	<th align=\"center\">$NbrRepriseAUTRES_LO_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_LO$</th>			
</tr>
<tr>
	<th>Granby</th>
	<th align=\"center\">$NbrRepriseAUTRES_GR</th>
	<th align=\"center\">$NbrRepriseAUTRES_GR_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_GR$</th>		
</tr>
<tr>
	<th>Sainte-Marie</th>
	<th align=\"center\">$NbrRepriseAUTRES_SMB</th>
	<th align=\"center\">$NbrRepriseAUTRES_SMB_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_SMB$</th>			
</tr>
<tr>
	<th>Qu&eacute;bec</th>
	<th align=\"center\">$NbrRepriseAUTRES_QC</th>
	<th align=\"center\">$NbrRepriseAUTRES_QC_HG</th>
	<th align=\"center\">$ValeurHorsGaranties_QC$</th>	
</tr>
<tr>
	<th bgcolor=\"#AFADAD\">TOTAUX</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeAUTRESGaranties</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeAUTRES_HG</th>
	<th bgcolor=\"#AFADAD\" align=\"center\">$SommeValeurGKB_HG$</th>	
</tr>
</table>";







//PARTIE 3: DETAIL PAR SUCCURSALE PAR FOURNISSEURS AVEC RAISONS DE REPRISES
//3.1: DIRECTLAB

for ($x = 1; $x <= $NombredeSuccursale; $x++) {
   switch($x){
	   case 1  :$Detail_Succursale = "LAVAL"; 
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('laval','lavalsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		        $TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE HKO
		   		$prescript_lab    = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   
	   case 2  :$Detail_Succursale = "DRUMMONDVILLE"; 	
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('entrepotdr','safedr')";
		   		$TotalShipped 	   = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		    	$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL          AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   	    $queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = 3 AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
	   			//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL          AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   	    $queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = 3 AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL          AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   	    $queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = 3 AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   
	   case 3  :$Detail_Succursale = "CHICOUTIMI";
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('chicoutimi','chicoutimisafe')";
		   		$TotalShipped	   = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise	   = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
       break;	 
		   
	
	   case 4  :$Detail_Succursale = "TROIS-RIVIERES";	
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('entrepotifc','entrepotsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL        AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'";
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
		   	 	$USER_ID = "('entrepotifc','entrepotsafe')";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL        AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'";
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO    = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO    = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL        AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_HKO_G  = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'";
		   		$queryRedoReasonHKO  = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0  ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		break; 
	 
	   case 5  ://PARTIE DLAB
		   		$prescript_lab = 3;
		   		$Detail_Succursale = "SHERBROOKE";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 	
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND              prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0  ";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND              prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0  ";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND              prescript_lab = $prescript_lab AND redo_reason_id<>0"; 
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0  ";
		   		$USER_ID = "('sherbrooke','sherbrookesafe')";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
	
	   case 6  :$Detail_Succursale = "TERREBONNE";
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('terrebonne','terrebonnesafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL              AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		  		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL              AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		  		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL              AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		  		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
	
	   case 7  :$Detail_Succursale = "LONGUEUIL";
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('longueuil','longueuilsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   	    $TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL";
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  ('longueuil','longueuilsafe') AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  ('longueuil','longueuilsafe') AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN ('longueuil','longueuilsafe')  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  ('longueuil','longueuilsafe') AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   
	   case 8  :$Detail_Succursale = "LEVIS";
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('levis','levissafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	  break;
		   
	  case 9  :$Detail_Succursale = "HALIFAX"; 
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('warehousehal','warehousehalsafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2'";
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab =$prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
	   			//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab =$prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab =$prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	  break;
		   
	   case 10 :$Detail_Succursale = "GRANBY"; 
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('granby','granbysafe')";
		   		$TotalShipped      = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise      = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 	
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as  ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID  AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as  ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID  AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as  ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID  AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
		   

		   
	   case 12 :$Detail_Succursale = "QUEBEC"; 
		   		//PARTIE DLAB
		   		$prescript_lab = 3;
		   		$USER_ID = "('entrepotquebec','quebecsafe')";
		   		$TotalShipped = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE  user_id IN $USER_ID            AND order_date_shipped BETWEEN '$date1' and '$date2'"; 
		   		$TotalShipped_DLAB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise = "SELECT count(order_num) as TotalReprise FROM ORDERS WHERE  user_id IN $USER_ID   AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL"; 
		   		$TotalReprise_DLAB = "SELECT count(order_num) as TotalReprise_DLAB FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_DLAB_G = "SELECT count(order_num) as TotalReprise_DLAB_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_DLAB_HG = "SELECT count(order_num) as TotalReprise_DLAB_HG, sum(order_total) as ValeurRepriseDLAB_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonDLAB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE SWISS
		   		$prescript_lab = 10;
		   		$TotalShipped_SWISS = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_SWISS = "SELECT count(order_num) as TotalReprise_SWISS FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_SWISS_G = "SELECT count(order_num) as TotalReprise_SWISS_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_SWISS_HG = "SELECT count(order_num) as TotalReprise_SWISS_HG, sum(order_total) as ValeurRepriseSWISS_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonSWISS = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE HKO
		   		$prescript_lab = 25;
		   		$TotalShipped_HKO = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID     AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_HKO = "SELECT count(order_num) as TotalReprise_HKO FROM ORDERS WHERE  user_id IN $USER_ID    AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_reason_id<>0";
		   		$TotalReprise_HKO_G = "SELECT count(order_num) as TotalReprise_HKO_G FROM ORDERS WHERE user_id IN   $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_HKO_HG = "SELECT count(order_num) as TotalReprise_HKO_HG, sum(order_total) as ValeurRepriseHKO_HG  FROM ORDERS WHERE user_id IN  $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonHKO = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN  $USER_ID AND redo_order_num<>0 ";
		   		//PARTIE GKB
		   		$prescript_lab    = 69;
		   		$TotalShipped_GKB = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=$prescript_lab";
		   		$TotalReprise_GKB = "SELECT count(order_num) as TotalReprise_GKB FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                     AND prescript_lab = $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_GKB_G = "SELECT count(order_num) as TotalReprise_GKB_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_GKB_HG = "SELECT count(order_num) as TotalReprise_GKB_HG, sum(order_total) as  ValeurRepriseGKB_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab = $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonGKB = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab = $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";	
		   		//PARTIE AUTRES
		   		$prescript_lab    = " NOT IN (3,10,25,69) ";
		   		$TotalShipped_AUTRES = "SELECT count(order_num) as Totalshipped FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab $prescript_lab";
		   		$TotalReprise_AUTRES = "SELECT count(order_num) as TotalReprise_AUTRES FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL                  AND prescript_lab  $prescript_lab AND redo_reason_id<>0"; 	
		   		$TotalReprise_AUTRES_G = "SELECT count(order_num) as TotalReprise_AUTRES_G FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab $prescript_lab AND redo_origin='retour_client'"; 
		   		$TotalReprise_AUTRES_HG = "SELECT count(order_num) as TotalReprise_AUTRES_HG, sum(order_total) as  ValeurRepriseAUTRES_HG  FROM ORDERS WHERE user_id IN $USER_ID  AND order_date_shipped BETWEEN '$date1' and '$date2' AND redo_order_num IS NOT NULL AND prescript_lab  $prescript_lab AND redo_origin<>'retour_client'"; 
		   		$queryRedoReasonAUTRES = "SELECT distinct redo_reason_id FROM ORDERS WHERE prescript_lab  $prescript_lab AND  order_date_shipped BETWEEN '$date1' and '$date2' AND user_id IN $USER_ID AND redo_order_num<>0      ORDER BY redo_reason_id";
	   break;
   
   }//End Switch
	
	//Partie Directlab
	$resultTotalShipped = mysql_query($TotalShipped)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped );
	$DataTotalShipped   = mysql_fetch_array($resultTotalShipped);
	
	$resultTotalShipped_DLAB = mysql_query($TotalShipped_DLAB)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped_DLAB );
	$DataTotalShipped_DLAB   = mysql_fetch_array($resultTotalShipped_DLAB);
		
	$resultTotalReprise = mysql_query($TotalReprise)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise);
	$DataTotalReprises  = mysql_fetch_array($resultTotalReprise);
	
	$resultTotalReprise_DLAB = mysql_query($TotalReprise_DLAB)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_DLAB);
	$DataTotalReprises_DLAB  = mysql_fetch_array($resultTotalReprise_DLAB);	
	
	
	$resultTotalReprise_DLAB_G = mysql_query($TotalReprise_DLAB_G)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_DLAB_G);
	$DataTotalReprises_DLAB_G  = mysql_fetch_array($resultTotalReprise_DLAB_G);	
	
	$resultTotalReprise_DLAB_HG = mysql_query($TotalReprise_DLAB_HG)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_DLAB_HG);
	$DataTotalReprises_DLAB_HG  = mysql_fetch_array($resultTotalReprise_DLAB_HG);	
		
	$CommandeOriginalesDurantPeriode = $DataTotalShipped[Totalshipped]-$DataTotalReprises[TotalReprise];
	$PourcentageReprise =  ($DataTotalReprises[TotalReprise]/$CommandeOriginalesDurantPeriode)*100;
	$PourcentageReprise = round($PourcentageReprise,2);
	
	//Partie Swiss
	$resultTotalShipped_SWISS = mysql_query($TotalShipped_SWISS)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped_SWISS );
	$DataTotalShipped_SWISS   = mysql_fetch_array($resultTotalShipped_SWISS);
			
	$resultTotalReprise_SWISS = mysql_query($TotalReprise_SWISS)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_SWISS);
	$DataTotalReprises_SWISS  = mysql_fetch_array($resultTotalReprise_SWISS);	
	
	$resultTotalReprise_SWISS_G = mysql_query($TotalReprise_SWISS_G)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_SWISS_G);
	$DataTotalReprises_SWISS_G  = mysql_fetch_array($resultTotalReprise_SWISS_G);	
	
	$resultTotalReprise_SWISS_HG = mysql_query($TotalReprise_SWISS_HG)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_SWISS_HG);
	$DataTotalReprises_SWISS_HG  = mysql_fetch_array($resultTotalReprise_SWISS_HG);	
	
	//Partie HKO
	$resultTotalShipped_HKO  = mysql_query($TotalShipped_HKO)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped_HKO );
	$DataTotalShipped_HKO    = mysql_fetch_array($resultTotalShipped_HKO);
			
	$resultTotalReprise_HKO = mysql_query($TotalReprise_HKO)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_HKO);
	$DataTotalReprises_HKO  = mysql_fetch_array($resultTotalReprise_HKO);	

	$resultTotalReprise_HKO_G = mysql_query($TotalReprise_HKO_G)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_HKO_G);
	$DataTotalReprises_HKO_G  = mysql_fetch_array($resultTotalReprise_HKO_G);	
	
	$resultTotalReprise_HKO_HG = mysql_query($TotalReprise_HKO_HG)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_HKO_HG);
	$DataTotalReprises_HKO_HG  = mysql_fetch_array($resultTotalReprise_HKO_HG);	
	
	
	//Partie GKB
	$resultTotalShipped_GKB  = mysql_query($TotalShipped_GKB)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped_GKB );
	$DataTotalShipped_GKB    = mysql_fetch_array($resultTotalShipped_GKB);
			
	$resultTotalReprise_GKB = mysql_query($TotalReprise_GKB)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_GKB);
	$DataTotalReprises_GKB  = mysql_fetch_array($resultTotalReprise_GKB);	

	$resultTotalReprise_GKB_G = mysql_query($TotalReprise_GKB_G)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_GKB_G);
	$DataTotalReprises_GKB_G  = mysql_fetch_array($resultTotalReprise_GKB_G);	
	
	$resultTotalReprise_GKB_HG = mysql_query($TotalReprise_GKB_HG)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_GKB_HG);
	$DataTotalReprises_GKB_HG  = mysql_fetch_array($resultTotalReprise_GKB_HG);	
	
	
	
	//Partie AUTRES
	$resultTotalShipped_AUTRES    = mysql_query($TotalShipped_AUTRES)		or die  ('I cannot select items because: ' . mysql_error() .$TotalShipped_AUTRES );
	$DataTotalShipped_AUTRES      = mysql_fetch_array($resultTotalShipped_AUTRES);
			
	$resultTotalReprise_AUTRES    = mysql_query($TotalReprise_AUTRES)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_AUTRES);
	$DataTotalReprises_AUTRES     = mysql_fetch_array($resultTotalReprise_AUTRES);	

	$resultTotalReprise_AUTRES_G  = mysql_query($TotalReprise_AUTRES_G)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_AUTRES_G);
	$DataTotalReprises_AUTRES_G   = mysql_fetch_array($resultTotalReprise_AUTRES_G);	
	
	$resultTotalReprise_AUTRES_HG = mysql_query($TotalReprise_AUTRES_HG)		or die  ('I cannot select items because: ' . mysql_error() . $TotalReprise_AUTRES_HG);
	$DataTotalReprises_AUTRES_HG  = mysql_fetch_array($resultTotalReprise_AUTRES_HG);	
	
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
				<tr>
					<th>Pourcentage de reprise global pour $Detail_Succursale</th>
					<td width=\"100\">$PourcentageReprise%</td>
				</tr>";
	
	
	
	
	
	
//Partie Dlab
	$CommandeOriginalesDurantPeriode_DLAB = $DataTotalShipped_DLAB[Totalshipped]-$DataTotalReprises_DLAB[TotalReprise_DLAB];
	$PourcentageReprise_DLAB =  ($DataTotalReprises_DLAB[TotalReprise_DLAB]/$CommandeOriginalesDurantPeriode_DLAB)*100;
	$PourcentageReprise_DLAB =  round($PourcentageReprise_DLAB,2);
	
	$message.="	<tr>
                	<th colspan=\"2\">&nbsp;</th>
				</tr>
	
				<tr bgcolor=\"CCCCCC\">
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
					<th bgcolor=\"#F1F71D\" width=\"625\">Pourcentage de reprise</th>
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
				
			   
	
	  //Aller cherchers les diffrentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonDLAB=mysql_query($queryRedoReasonDLAB)		or die ( "Query failed: " . mysql_error());
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#F1F71D\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#F1F71D\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonDLAB  = mysql_fetch_array($resultRedoReasonDLAB)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]";
		$resultDetailRedoReason = mysql_query($queryDetailRedoReason)		or die ( "Query failed: " . mysql_error());
		$DataDetailRedoReason   = mysql_fetch_array($resultDetailRedoReason);

		
		$queryRedo_DLAB_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3 AND redo_reason_id = $DataRedoReasonDLAB[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_DLAB_G.'<br>';
		$resultRedo_DLAB_G = mysql_query($queryRedo_DLAB_G)		or die ( "Query failed: " . mysql_error());
		$DataRedo_DLAB_G   = mysql_fetch_array($resultRedo_DLAB_G);
		
		
		$queryRedo_DLAB_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonDLAB[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=3  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_DLAB_HG.'<br><br><br>';
		$resultRedo_DLAB_HG = mysql_query($queryRedo_DLAB_HG)		or die ( "Query failed: " . mysql_error());
		$DataRedo_DLAB_HG   = mysql_fetch_array($resultRedo_DLAB_HG);
		
		
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
	
	
	
	
	
//Partie Swiss
		
	$CommandeOriginalesDurantPeriode_SWISS = $DataTotalShipped_SWISS[Totalshipped]-$DataTotalReprises_SWISS[TotalReprise_SWISS];
	$PourcentageReprise_SWISS =  ($DataTotalReprises_SWISS[TotalReprise_SWISS]/$CommandeOriginalesDurantPeriode_SWISS)*100;
	$PourcentageReprise_SWISS =  round($PourcentageReprise_SWISS,2);
	
	$message.="	<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#AADCA9\" align=\"center\" colspan=\"10\">PARTIE SWISSCOAT</th>
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
				
			   
	
	  //Aller cherchers les differentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonSWISS=mysql_query($queryRedoReasonSWISS)		or die ( "Query failed: " . mysql_error());
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#AADCA9\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#AADCA9\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonSWISS  = mysql_fetch_array($resultRedoReasonSWISS)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]";
		$resultDetailRedoReason = mysql_query($queryDetailRedoReason)		or die ( "Query failed: " . mysql_error());
		$DataDetailRedoReason   = mysql_fetch_array($resultDetailRedoReason);

		
		$queryRedo_SWISS_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=10 AND redo_reason_id = $DataRedoReasonSWISS[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_SWISS_G.'<br>';
		$resultRedo_SWISS_G = mysql_query($queryRedo_SWISS_G)		or die ( "Query failed: " . mysql_error());
		$DataRedo_SWISS_G   = mysql_fetch_array($resultRedo_SWISS_G);
		
		
		$queryRedo_SWISS_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonSWISS[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=10  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_SWISS_HG.'<br><br><br>';
		$resultRedo_SWISS_HG = mysql_query($queryRedo_SWISS_HG)		or die ( "Query failed: " . mysql_error());
		$DataRedo_SWISS_HG   = mysql_fetch_array($resultRedo_SWISS_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_SWISS_HG[NbrRedoHorsGaranties] + $DataRedo_SWISS_G[NbrRedoGaranties])/$DataTotalReprises_SWISS[TotalReprise_SWISS])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		

		$message.="<tr>
						<td bgcolor=\"#AADCA9\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_SWISS_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_SWISS_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#AADCA9\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie SWISSCOAT
	
	
	
	
		
//Partie HKO
		
	/*$CommandeOriginalesDurantPeriode = $DataTotalShipped[Totalshipped]-$DataTotalReprises[TotalReprise];
	$PourcentageReprise =  ($DataTotalReprises[TotalReprise]/$CommandeOriginalesDurantPeriode)*100;
	$PourcentageReprise = round($PourcentageReprise,2);*/
	
	$CommandeOriginalesDurantPeriode_HKO = $DataTotalShipped_HKO[Totalshipped]-$DataTotalReprises_HKO[TotalReprise_HKO];
	$PourcentageReprise_HKO =  ($DataTotalReprises_HKO[TotalReprise_HKO]/$CommandeOriginalesDurantPeriode_HKO)*100;
	$PourcentageReprise_HKO =  round($PourcentageReprise_HKO,2);
	
	$message.="	<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#9CCEDD\" align=\"center\" colspan=\"10\">PARTIE HKO</th>
				</tr>

				
				<tr>
					<th  bgcolor=\"#9CCEDD\" width=\"625\">Nombre de commandes durant la p&eacute;riode</th>
					<td bgcolor=\"#9CCEDD\" width=\"100\">$DataTotalShipped_HKO[Totalshipped]</td>
				</tr>
				<tr>
					<th bgcolor=\"#9CCEDD\" width=\"625\">Nombre reprise:</th>
					<td bgcolor=\"#9CCEDD\" width=\"100\">$DataTotalReprises_HKO[TotalReprise_HKO]</td>
				</tr>
				<tr>
					<th bgcolor=\"#9CCEDD\" width=\"625\">Nombre de commandes originales</th>
					<td bgcolor=\"#9CCEDD\" width=\"100\">$CommandeOriginalesDurantPeriode_HKO</td>
				</tr>
				<tr>
					<th bgcolor=\"#9CCEDD\" width=\"625\">Pourcentage de reprise</th>
					<td bgcolor=\"#9CCEDD\" width=\"100\">$PourcentageReprise_HKO%</td>
				</tr>
				
				<tr>
					<th bgcolor=\"#B2B706\" width=\"625\">Garanties</th>
					<td bgcolor=\"#B2B706\" width=\"100\">$DataTotalReprises_HKO_G[TotalReprise_HKO_G]</td>
				</tr>
				<tr>
					<th  bgcolor=\"#F79E9F\" width=\"625\">Hors Garanties:</th>
					<td  bgcolor=\"#F79E9F\" width=\"100\">$DataTotalReprises_HKO_HG[TotalReprise_HKO_HG]</td>
				</tr>
				<tr>
					<th bgcolor=\"#9CCEDD\" width=\"625\">$ Net des reprises Hors Garanties</th>
					<td bgcolor=\"#9CCEDD\" width=\"100\">$DataTotalReprises_HKO_HG[ValeurRepriseHKO_HG]$</td>
				</tr></table><br>";
				
			   
	
	  //Aller cherchers les diffrentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonHKO=mysql_query($queryRedoReasonHKO)		or die ( "Query failed: " . mysql_error());
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#9CCEDD\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#9CCEDD\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonHKO  = mysql_fetch_array($resultRedoReasonHKO)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonHKO[redo_reason_id]";
		$resultDetailRedoReason = mysql_query($queryDetailRedoReason)		or die ( "Query failed: " . mysql_error());
		$DataDetailRedoReason   = mysql_fetch_array($resultDetailRedoReason);

		
		$queryRedo_HKO_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=25 AND redo_reason_id = $DataRedoReasonHKO[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_HKO_G.'<br>';
		$resultRedo_HKO_G = mysql_query($queryRedo_HKO_G)		or die ( "Query failed: " . mysql_error());
		$DataRedo_HKO_G   = mysql_fetch_array($resultRedo_HKO_G);
		
		
		$queryRedo_HKO_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonHKO[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=25  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_HKO_HG.'<br><br><br>';
		$resultRedo_HKO_HG = mysql_query($queryRedo_HKO_HG)		or die ( "Query failed: " . mysql_error());
		$DataRedo_HKO_HG   = mysql_fetch_array($resultRedo_HKO_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_HKO_HG[NbrRedoHorsGaranties] + $DataRedo_HKO_G[NbrRedoGaranties])/$DataTotalReprises_HKO[TotalReprise_HKO])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		

		$message.="<tr>
						<td bgcolor=\"#9CCEDD\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_HKO_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_HKO_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#9CCEDD\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie HKO
	

		
//Partie GKB
			
	$CommandeOriginalesDurantPeriode_GKB = $DataTotalShipped_GKB[Totalshipped]-$DataTotalReprises_GKB[TotalReprise_GKB];
	$PourcentageReprise_GKB =  ($DataTotalReprises_GKB[TotalReprise_GKB]/$CommandeOriginalesDurantPeriode_GKB)*100;
	$PourcentageReprise_GKB =  round($PourcentageReprise_GKB,2);
	
	$message.="	<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#D89B2A\" align=\"center\" colspan=\"10\">PARTIE GKB</th>
				</tr>

				
				<tr>
					<th  bgcolor=\"#D89B2A\" width=\"625\">Nombre de commandes durant la p&eacute;riode</th>
					<td bgcolor=\"#D89B2A\" width=\"100\">$DataTotalShipped_GKB[Totalshipped]</td>
				</tr>
				<tr>
					<th bgcolor=\"#D89B2A\" width=\"625\">Nombre reprise:</th>
					<td bgcolor=\"#D89B2A\" width=\"100\">$DataTotalReprises_GKB[TotalReprise_GKB]</td>
				</tr>
				<tr>
					<th bgcolor=\"#D89B2A\" width=\"625\">Nombre de commandes originales</th>
					<td bgcolor=\"#D89B2A\" width=\"100\">$CommandeOriginalesDurantPeriode_GKB</td>
				</tr>
				<tr>
					<th bgcolor=\"#D89B2A\" width=\"625\">Pourcentage de reprise</th>
					<td bgcolor=\"#D89B2A\" width=\"100\">$PourcentageReprise_GKB%</td>
				</tr>
				
				<tr>
					<th bgcolor=\"#B2B706\" width=\"625\">Garanties</th>
					<td bgcolor=\"#B2B706\" width=\"100\">$DataTotalReprises_GKB_G[TotalReprise_GKB_G]</td>
				</tr>
				<tr>
					<th  bgcolor=\"#F79E9F\" width=\"625\">Hors Garanties:</th>
					<td  bgcolor=\"#F79E9F\" width=\"100\">$DataTotalReprises_GKB_HG[TotalReprise_GKB_HG]</td>
				</tr>
				<tr>
					<th bgcolor=\"#D89B2A\" width=\"625\">$ Net des reprises Hors Garanties</th>
					<td bgcolor=\"#D89B2A\" width=\"100\">$DataTotalReprises_GKB_HG[ValeurRepriseGKB_HG]$</td>
				</tr></table><br>";
				
			   
	
	  //Aller cherchers les diffrentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonGKB=mysql_query($queryRedoReasonGKB)		or die ( "Query failed: " . mysql_error());
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#D89B2A\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#D89B2A\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonGKB  = mysql_fetch_array($resultRedoReasonGKB)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonGKB[redo_reason_id]";
		$resultDetailRedoReason = mysql_query($queryDetailRedoReason)		or die ( "Query failed: " . mysql_error());
		$DataDetailRedoReason   = mysql_fetch_array($resultDetailRedoReason);

		
		$queryRedo_GKB_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=69 AND redo_reason_id = $DataRedoReasonGKB[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_GKB_G.'<br>';
		$resultRedo_GKB_G = mysql_query($queryRedo_GKB_G)		or die ( "Query failed: " . mysql_error());
		$DataRedo_GKB_G   = mysql_fetch_array($resultRedo_GKB_G);
		
		
		$queryRedo_GKB_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonGKB[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=69  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_GKB_HG.'<br><br><br>';
		$resultRedo_GKB_HG = mysql_query($queryRedo_GKB_HG)		or die ( "Query failed: " . mysql_error());
		$DataRedo_GKB_HG   = mysql_fetch_array($resultRedo_GKB_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_GKB_HG[NbrRedoHorsGaranties] + $DataRedo_GKB_G[NbrRedoGaranties])/$DataTotalReprises_GKB[TotalReprise_GKB])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		

		$message.="<tr>
						<td bgcolor=\"#D89B2A\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_GKB_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_GKB_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#D89B2A\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie GKB
	
	
	
	
	
	
	//Partie AUTRES
			
	$CommandeOriginalesDurantPeriode_AUTRES = $DataTotalShipped_AUTRES[Totalshipped]-$DataTotalReprises_AUTRES[TotalReprise_AUTRES];
	$PourcentageReprise_AUTRES =  ($DataTotalReprises_AUTRES[TotalReprise_AUTRES]/$CommandeOriginalesDurantPeriode_AUTRES)*100;
	$PourcentageReprise_AUTRES =  round($PourcentageReprise_AUTRES,2);
	
	$message.="	<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr bgcolor=\"CCCCCC\">
                	<th bgcolor=\"#A6E8D4\" align=\"center\" colspan=\"10\">PARTIE AUTRES FABRIQUANTS</th>
				</tr>

				
				<tr>
					<th  bgcolor=\"#A6E8D4\" width=\"625\">Nombre de commandes durant la p&eacute;riode</th>
					<td bgcolor=\"#A6E8D4\" width=\"100\">$DataTotalShipped_AUTRES[Totalshipped]</td>
				</tr>
				<tr>
					<th bgcolor=\"#A6E8D4\" width=\"625\">Nombre reprise:</th>
					<td bgcolor=\"#A6E8D4\" width=\"100\">$DataTotalReprises_AUTRES[TotalReprise_AUTRES]</td>
				</tr>
				<tr>
					<th bgcolor=\"#A6E8D4\" width=\"625\">Nombre de commandes originales</th>
					<td bgcolor=\"#A6E8D4\" width=\"100\">$CommandeOriginalesDurantPeriode_AUTRES</td>
				</tr>
				<tr>
					<th bgcolor=\"#A6E8D4\" width=\"625\">Pourcentage de reprise</th>
					<td bgcolor=\"#A6E8D4\" width=\"100\">$PourcentageReprise_AUTRES%</td>
				</tr>
				
				<tr>
					<th bgcolor=\"#B2B706\" width=\"625\">Garanties</th>
					<td bgcolor=\"#B2B706\" width=\"100\">$DataTotalReprises_AUTRES_G[TotalReprise_AUTRES_G]</td>
				</tr>
				<tr>
					<th  bgcolor=\"#F79E9F\" width=\"625\">Hors Garanties:</th>
					<td  bgcolor=\"#F79E9F\" width=\"100\">$DataTotalReprises_AUTRES_HG[TotalReprise_AUTRES_HG]</td>
				</tr>
				<tr>
					<th bgcolor=\"#A6E8D4\" width=\"625\">$ Net des reprises Hors Garanties</th>
					<td bgcolor=\"#A6E8D4\" width=\"100\">$DataTotalReprises_AUTRES_HG[ValeurRepriseAUTRES_HG]$</td>
				</tr></table><br>";
				
			   
	
	  //Aller cherchers les diffrentes raisons de reprises pour ce fabriquant et la succursale choisie
	  $resultRedoReasonAUTRES=mysql_query($queryRedoReasonAUTRES)		or die ( "Query failed: " . mysql_error());
	  $message.="<table width=\"725\" border=\"1\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
				<tr>
					<th bgcolor=\"#A6E8D4\" width=\"61%\">Raison</th>
					<th bgcolor=\"#F79E9F\" width=\"16%\">Nombre reprises Hors Garanties</th>
					<th bgcolor=\"#B2B706\" width=\"16%\">Nombre reprises Garanties</th>
					<th bgcolor=\"#A6E8D4\" width=\"7%\">%</th>
				</tr>";
	
	while($DataRedoReasonAUTRES  = mysql_fetch_array($resultRedoReasonAUTRES)){
		
		$queryDetailRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = $DataRedoReasonAUTRES[redo_reason_id]";
		$resultDetailRedoReason = mysql_query($queryDetailRedoReason)		or die ( "Query failed: " . mysql_error());
		$DataDetailRedoReason   = mysql_fetch_array($resultDetailRedoReason);

		
		$queryRedo_AUTRES_G  = "SELECT count(order_num) as NbrRedoGaranties FROM orders WHERE USER_ID IN $USER_ID
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=69 AND redo_reason_id = $DataRedoReasonAUTRES[redo_reason_id] AND redo_origin='retour_client'";
		//echo '<br>'.$queryRedo_AUTRES_G.'<br>';
		$resultRedo_AUTRES_G = mysql_query($queryRedo_AUTRES_G)		or die ( "Query failed: " . mysql_error());
		$DataRedo_AUTRES_G   = mysql_fetch_array($resultRedo_AUTRES_G);
		
		
		$queryRedo_AUTRES_HG  = "SELECT count(order_num) as NbrRedoHorsGaranties FROM orders WHERE USER_ID IN $USER_ID AND redo_reason_id = $DataRedoReasonAUTRES[redo_reason_id]
		AND order_date_shipped BETWEEN '$date1' and '$date2' AND prescript_lab=69  AND redo_origin<>'retour_client'";
		//echo '<br>'.$queryRedo_AUTRES_HG.'<br><br><br>';
		$resultRedo_AUTRES_HG = mysql_query($queryRedo_AUTRES_HG)		or die ( "Query failed: " . mysql_error());
		$DataRedo_AUTRES_HG   = mysql_fetch_array($resultRedo_AUTRES_HG);
		
		
		//Calcul du pourcentage
		$SommeRedopourCetteRaison = (($DataRedo_AUTRES_HG[NbrRedoHorsGaranties] + $DataRedo_AUTRES_G[NbrRedoGaranties])/$DataTotalReprises_AUTRES[TotalReprise_AUTRES])*100;
		$SommeRedopourCetteRaison = round($SommeRedopourCetteRaison,2);
		

		$message.="<tr>
						<td bgcolor=\"#A6E8D4\" width=\"60%\">$DataDetailRedoReason[redo_reason_en]/$DataDetailRedoReason[redo_reason_fr]</td>
						<td width=\"15%\" align=\"center\"  bgcolor=\"#F79E9F\">$DataRedo_AUTRES_HG[NbrRedoHorsGaranties]</td>
						<td bgcolor=\"#B2B706\"  width=\"15%\" align=\"center\">$DataRedo_AUTRES_G[NbrRedoGaranties]</td>
						<td bgcolor=\"#A6E8D4\" align=\"center\" width=\"10%\">$SommeRedopourCetteRaison%</td>
					</tr>";
		
		
	}//End While
	 $message.="</table><br><br>";
	//Fin de la partie AUTRES FOURNISSEURS
	
}//End FOR
	


echo $message;

//Fermer le tableau

//SEND EMAIL
$send_to_address=array('dbeaulieu@direct-lens.com';
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport Global EDLL Entre $date1 et $date2";
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
		log_email("REPORT: Redirection Report DR",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: sucess';
    }else{
		log_email("REPORT: Redirection Report DR",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: failed';
	}	

		
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Email redirection report DR', '$time','$today','$timeplus3heures','cron_send_redirection_report_dr.php') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}
?>