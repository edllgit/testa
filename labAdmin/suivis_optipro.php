<?php 
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$NombredeSuccursale = 12;

//Octobre 2017



$date4= "2017-10-04";
$date5= "2017-10-05";
$date6= "2017-10-06";
$date7= "2017-10-07";
$date8= "2017-10-08";
$date9= "2017-10-09";
$date10="2017-10-10";
$date11= "2017-10-11";
$date12= "2017-10-12";
$date13= "2017-10-13";
$date14= "2017-10-14";
$date15= "2017-10-15";
$date16= "2017-10-16";
$date17= "2017-10-17";
$date18= "2017-10-18";
$date19= "2017-10-19";
$date20= "2017-10-20";
$date21= "2017-10-21";
$date22= "2017-10-22";
$date23= "2017-10-23";
$date24= "2017-10-24";
$date25= "2017-10-25";
$date26= "2017-10-26";
$date27= "2017-10-27";
$date28= "2017-10-28";
$date29= "2017-10-29";
$date30= "2017-10-30";
$date31= "2017-10-31";



?>

<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="charles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
<meta http-equiv="refresh" content="35"><!--Refresh every 70 seconds -->
</head>

<?php //Tableau avec job dans le basket/et confirmés ?>
<table width="80%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th align="center">Compte</th>
			<th align="center" bgcolor="#ECAAAB">1er</th>
            <th align="center" bgcolor="#F4F791">2</th>
            <th align="center" bgcolor="#C7FCC4">3</th>
            <th align="center" bgcolor="#ECAAAB">4</th>
            <th align="center" bgcolor="#F4F791">5</th>
            <th align="center" bgcolor="#C7FCC4">6</th>
            <th align="center" bgcolor="#ECAAAB">7</th>
	</tr>
    </thead>
    
    
    
<?php
	
//For qui passe les 31 journées du mois une par une
for ($y = 1; $y <= 31; $y++) {
	switch($y){
		case 1:  $ladate= "2017-10-01";  break;
		case 2:  $ladate= "2017-10-02";  break;
		case 3:  $ladate= "2017-10-03";  break;
		case 4:  $ladate= "2017-10-04";  break;
		case 5:  $ladate= "2017-10-05";  break;
		case 6:  $ladate= "2017-10-06";  break;
		case 7:  $ladate= "2017-10-07";  break;
		case 8:  $ladate= "2017-10-08";  break;
		case 9:  $ladate= "2017-10-09";  break;
		case 10: $ladate= "2017-10-10";  break;
		case 11: $ladate= "2017-10-11";  break;
		case 12: $ladate= "2017-10-12";  break;
		case 13: $ladate= "2017-10-13";  break;
		case 14: $ladate= "2017-10-14";  break;
		case 15: $ladate= "2017-10-15";  break;
		case 16: $ladate= "2017-10-16";  break;
		case 17: $ladate= "2017-10-17";  break;
		case 18: $ladate= "2017-10-18";  break;
		case 19: $ladate= "2017-10-19";  break;
		case 20: $ladate= "2017-10-20";  break;
		case 21: $ladate= "2017-10-21";  break;
		case 22: $ladate= "2017-10-22";  break;
		case 23: $ladate= "2017-10-23";  break;
		case 24: $ladate= "2017-10-24";  break;
		case 25: $ladate= "2017-10-25";  break;
		case 26: $ladate= "2017-10-26";  break;
		case 27: $ladate= "2017-10-27";  break;
		case 28: $ladate= "2017-10-28";  break;
		case 29: $ladate= "2017-10-29";  break;
		case 30: $ladate= "2017-10-30";  break;
		case 31: $ladate= "2017-10-31";  break;
	}//End Switch
	
	echo '<br>'.$ladate;
	
	
	//Passer tous les comptes un par un pour sortir les chiffres de la journée sélectionnée	
	for ($x = 1; $x <= 11; $x++) {
		switch($x){
			case 1:  $Succ = "Trois-Rivieres";$user_id = "SELECT * FROM orders WHERE USER_ID IN ('entrepotifc','entrepotsafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 		break;
			case 2:  $Succ = "Drummondville"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('entrepotdr','safedr') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 				break;
			case 3:  $Succ = "Granby"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('granby','granbysafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' ";		    	break;
			case 4:  $Succ = "levis"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('levis','levissafe') AND order_date_processed ='$ladate' AND order_num_optipro<>''"; 				break;
			case 5:  $Succ = "chicoutimi"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('chicoutimi','chicoutimisafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 		break;
			case 6:  $Succ = "laval"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('laval','lavalsafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 				break;
			case 7:  $Succ = "terrebonne"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('terrebonne','terrebonnesafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 		break;
			case 8:  $Succ = "sherbrooke"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('sherbrooke','sherbrookesafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' "; 		break;
			case 9:  $Succ = "longueuil"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('longueuil','longueuilsafe') AND order_date_processed ='$ladate' AND order_num_optipro<>''";		break;
			case 10: $Succ = "warehousehal"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('warehousehal','warehousehalsafe') AND order_date_processed ='$ladate'AND order_num_optipro<>'' ";	break;
			case 11: $Succ = "Drummondville"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('ghfjfg') AND order_date_processed ='$ladate' AND order_num_optipro<>''"; 			break;
				
			case 12: $Succ = "Quebec"; $user_id = "SELECT * FROM orders WHERE USER_ID IN ('entrepotquebec','quebecsafe')  AND order_date_processed ='$ladate' AND order_num_optipro<>''"; 			break;
		}
		
		$resultCommandeDuJour = "SELECT * FROM orders WHERE $user_id AND order_date_processed ='$ladate' AND order_num_optipro<>'' ";
		$DataCommandeDuJour     			= mysql_fetch_array($resultCommandeDuJour);
		echo '<br>'. $resultCommandeDuJour;
		
		//echo '<br>'.$user_id;
	}//End FOR
	
}//End FOR
	
	
exit();
	
		//Trois-Rivieres
		$CompteIFC              =  " user_id IN ('entrepotifc')";  
		$CompteSAFE             =  " user_id IN ('entrepotsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because 3: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_TR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_TR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TR   = $DataValiderAJD[NbrCommandeTransferer] ;
	
		//Drummondville
		$CompteIFC              	=  " user_id IN ('entrepotdr')";  
		$CompteSAFE             	=  " user_id IN ('safedr')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	   		= mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     			= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_DR      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     		= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_DR     	= $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_DR   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Granby
		$CompteIFC              =  " user_id IN ('granby')";  
		$CompteSAFE             =  " user_id IN ('granbysafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_GR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_GR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_GR   = $DataValiderAJD[NbrCommandeTransferer] ;
			
		//Lévis
		$CompteIFC              =  " user_id IN ('levis')";  
		$CompteSAFE             =  " user_id IN ('levissafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LE      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LE     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LE   = $DataValiderAJD[NbrCommandeTransferer] ;
			
		
		//Chicoutimi
		$CompteIFC              =  " user_id IN ('chicoutimi')";  
		$CompteSAFE             =  " user_id IN ('chicoutimisafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_CH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_CH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_CH   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Laval
		$CompteIFC              =  " user_id IN ('laval')";  
		$CompteSAFE             =  " user_id IN ('lavalsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LV      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LV     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LV   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		
		//Terrebonne
		$CompteIFC              =  " user_id IN ('terrebonne')";  
		$CompteSAFE             =  " user_id IN ('terrebonnesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_TE      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_TE     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_TB   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		//Sherbrooke
		$CompteIFC              =  " user_id IN ('sherbrooke')";  
		$CompteSAFE             =  " user_id IN ('sherbrookesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_SH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_SH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_SH   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Longueuil
		$CompteIFC              =  " user_id IN ('longueuil')";  
		$CompteSAFE             =  " user_id IN ('longueuilsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_LO      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_LO     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_LO   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		//Halifax
		$CompteIFC              =  " user_id IN ('warehousehal')";  
		$CompteSAFE             =  " user_id IN ('warehousehalsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_HA      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_HA     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_HA   = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		
		//Québec
		$CompteIFC              =  " user_id IN ('entrepotquebec')";  
		$CompteSAFE             =  " user_id IN ('quebecsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysql_query($queryJobPanierIFC) or die  ('I cannot select items because: ' . mysql_error()); 
		$DataPanierIFC     		= mysql_fetch_array($resultPanierIFC);
		$NbrCommandeIfc_QC     = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysql_query($queryJobPanierSAFE) or die  ('I cannot select items because: ' . mysql_error());   
		$DataPanierSAFE     	= mysql_fetch_array($resultSAFE);
		$NbrCommandeSAFE_QC    = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysql_query($queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysql_error()); 
        $DataValiderAJD        		= mysql_fetch_array($resultValiderAJD);
		$NbrCommandeValiderAJD_QC   = $DataValiderAJD[NbrCommandeTransferer] ;	
		

?>	



	<tr>
			<th align="center">Trois-Rivières</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    <tr>	
    		<th align="center">Drummondville</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    
   
    <tr>	
    		<th align="center">Granby</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    
    <tr>
	    	<th align="center">Lévis</th>	
		    <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    
     <tr>
     		<th align="center">Chicoutimi</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
     <tr>
     		<th align="center">Laval</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
     <tr>
     		<th align="center">Terrebonne</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
           
	</tr>
    
    <tr>
    		<th align="center">Sherbrooke</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
     
      <tr>
			<th align="center">Longueuil</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    
      <tr>
			<th align="center">Halifax</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
      <tr>
      		<th align="center">Sainte-Marie</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
      <tr>
      		<th align="center">Québec</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_TR; ?></th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeIfc_TR ?></th>
	</tr>
    
    <tr>
			<th align="center">Total</th>
			<th align="center"><?php echo $totalPanierIFC;?></th>
            <th align="center"><?php echo $totalPanierSAFE?>&nbsp;</th>
            <th align="center"><?php echo $totalValidees;?></th>
	</tr>
    </table>
    
    <br>
    
    
    <?php  
	$queryID  = "SELECT max(log_id) as max_log_id FROM log_shapes";
	$resultID = mysql_query($queryID) or die  ('I cannot select items because 55: ' . mysql_error());
	//echo '<br>query:'. $queryID ;
	$DataID   = mysql_fetch_array($resultID);
	
	$queryTraces = "SELECT * FROM log_shapes WHERE log_id = $DataID[max_log_id]";
	//echo '<br>queryTraces:'. $queryTraces . '<br>';
	$resultTraces = mysql_query($queryTraces) or die  ('I cannot select items because 22: ' . mysql_error());
	$DataTraces   = mysql_fetch_array($resultTraces);
	?>
    
    <table align="center"  width="800" border="1">
    	<tr align="center">
        	<td colspan="2" width="20%" align="center"><h2 align="center">Traces</h2></td></td>
        </tr>
        
        <tr  align="center">
        	<td width="20%"><h3>Dernière date d'exécution:</h3></td>
            <td width="20%"><h3><?php echo $DataTraces[date_debut] . ' ' . $DataTraces[heure_debut]; ?></h3></td>
        </tr>
                
         <tr  align="center">
        	<td width="20%"><h3>Heure de fin:</h3></td>
            <td width="20%"><h3><?php if ($DataTraces[heure_fin] <> '') echo $DataTraces[heure_fin]; else echo 'aucune, en cours d\'exécution?'; ?></h3></td>
        </tr>
        
        <tr  align="center">
        	<td width="20%"><h3>Durée d'exécution:</h3></td>
            <td width="20%"><h3><?php echo $DataTraces[duree_execution]; ?> <?php if($DataTraces[duree_execution] > 1) echo ' secondes'; ?></h3></td>
        </tr>
        
         <tr  align="center">
        	<td width="20%"><h3>Nombre de fichiers:</h3></td>
            <td width="20%"><?php echo $DataTraces[nbr_fichier]; ?></td>
        </tr>
        
    </table>

  <p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>