<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


$datedujour     = date("Y-m-d");//Pour mettre la date du jour dans les date_from et date_to au chargement du rapport	
$datefrom  = $_POST[date_from];
$dateto    = $_POST[date_to];
$user_id   = $_POST[user_id];



switch($user_id){
	case '': 		   		  
		$Filter_user_id_Rx     		 = " AND user_id IN ('entrepotifc','entrepotdr','laval','warehousehal','terrebonne','levis', 'sherbrooke','chicoutimi','longueuil','granby','entrepotquebec','gatineau','stjerome')";
		$Filter_user_id_Rx_SAFETY    = " AND user_id IN ('entrepotsafe','safedr','lavalsafe','warehousehalsafe','terrebonnesafe','levissafe','sherbrookesafe',
		'chicoutimisafe','longueuilsafe','granbysafe','quebecsafe','gatineausafe','stjeromesafe')";
	break;
	
	case 'ifc.ca': 			  
		$Filter_user_id_Rx 	   		= " AND order_from IN ('ifcclubca') ";
		$Filter_user_id_Rx_SAFETY   = " AND order_from IN ('safety') ";
	break;		
	 
	case 'entrepotifc':
		$Filter_user_id_Rx	  		= " AND user_id IN ('entrepotifc') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id IN ('entrepotsafe') ";
	break;

	
	case 'granby': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('granby') ";
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('granbysafe') ";
	break;
	
	case 'entrepotquebec': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('entrepotquebec') ";  
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('quebecsafe') ";  
	break;
	
	case 'terrebonne': 	 
		$Filter_user_id_Rx	  		= " AND user_id in ('terrebonne') ";  
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('terrebonnesafe') ";  
	break;
	
	case 'levis': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('levis') ";   
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('levissafe') ";   
	break;
	
	case 'sherbrooke': 	 
		$Filter_user_id_Rx	  	 	= " AND user_id in ('sherbrooke') ";   
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('sherbrookesafe') ";   
	break;
	
	case 'chicoutimi': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('chicoutimi') ";  
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('chicoutimisafe') ";  
	break;
	
	
	case 'warehousehal': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('warehousehal') ";  
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('warehousehalsafe') ";  
	break;
	
	
	case 'entrepotdr':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('entrepotdr') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('safedr') "; 
	break;
	
	case 'longueuil': 	 
		$Filter_user_id_Rx	   		= " AND user_id in ('longueuil') ";  
		$Filter_user_id_Rx_SAFETY	= " AND user_id in ('longueuilsafe') ";  
 	break;
	
	case 'laval':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('laval') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('lavalsafe') "; 
	break;
		
	/*case 'montreal':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('montreal') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('montrealsafe') "; 
	break; */
		
	case 'gatineau':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('gatineau') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('gatineausafe') "; 
	break;
	
	case 'sorel':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('sorel') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('sorelsafe') "; 
	break;
	
	case 'vaudreuil':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('vaudreuil') "; 
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('vaudreuilsafe') "; 
	break;
		
		
	case 'stjerome':  	  
		$Filter_user_id_Rx     		= " AND user_id in ('stjerome') ";  
		$Filter_user_id_Rx_SAFETY   = " AND user_id in ('stjeromesafe') "; 
	break;
	
	case 'toutsaufentrepot':  
		$Filter_user_id_Rx     = " AND order_from IN ('ifcclubca') AND user_id NOT IN ('entrepotifc'   ,'entrepotdr','laval','warehousehal','terrebonne','levis', 'sherbrooke','chicoutimi','longueuil','granby','entrepotquebec','gatineau','stjerome','sorel','vaudreuil') "; 
		$Filter_user_id_Rx_SAFETY     = " AND order_from IN ('safety') AND user_id NOT IN ('entrepotsafe','safedr','warehousestcsafe','lavalsafe','warehousehalsafe','terrebonnesafe','levissafe','sherbrookesafe',
		'chicoutimisafe','longueuilsafe','granbysafe','quebecsafe','gatineausafe','stjeromesafe,'sorelsafe','vaudreuilsafe') "; 
	break;
} 

$misc_unknown_purpose   = $_POST[misc_unknown_purpose];
$Filtre_collection 		= ' AND 2=2 ';

switch($misc_unknown_purpose){
	case 'FUGLIES_A':  	 	 
	$Filtre_collection        = " AND supplier IN ('FUGLIES_A','RX01','RX02','RX07','RX08','RX09','RX10','RX11','RX12','RX13')";
	$Filtre_collection_frames = " AND (order_product_name like '%FUGLIES_A%' OR order_product_name like '%RX01%' OR order_product_name like '%RX02%' OR order_product_name like '%RX07%'
	OR order_product_name like '%RX08%' OR order_product_name like '%RX09%'   OR order_product_name like '%RX10%'  OR order_product_name like '%RX11%' OR order_product_name like '%RX12%'  OR order_product_name like '%RX13%')";
	break;

	case 'FUGLIES_B':  	 	
	$Filtre_collection        = " AND supplier IN ('FUGLIES_B','RX05','RX06')"; 
	$Filtre_collection_frames = " AND (order_product_name like '%FUGLIES_A%' OR order_product_name like '%RX05%' OR order_product_name like '%RX06%')";					
	break;
	
	case 'FUGLIES_C':   	
	$Filtre_collection        = " AND supplier IN ('FUGLIES_C','RX03','RX04','RX14','RX15','RX16')"; 	
	$Filtre_collection_frames = " AND (order_product_name like '%FUGLIES_C%' OR order_product_name like '%RX03%'  OR order_product_name like '%RX04%' OR order_product_name like '%RX14%'
	OR order_product_name like '%RX15%' OR order_product_name like '%RX16%')";	 	
	break;
	 
	case 'FREE':        	 
	$Filtre_collection        = " AND supplier IN ('FREE','FRM','FRP','SM','SP')";	
	$Filtre_collection_frames = " AND (order_product_name like '%FREE%' OR order_product_name like '%FRM%'  OR order_product_name like '%FRP%' OR order_product_name like '%SM%'
	OR order_product_name like '%SP%')";	 				  		
	break;
	
	case 'ArmouRx':          
	$Filtre_collection 		  = " AND supplier IN ('ArmouRx','Basic','Classic','Metro','Wrap-Rx')";
	$Filtre_collection_frames = " AND (order_product_name like '%ArmouRx%' OR order_product_name like '%Basic%'  OR order_product_name like '%Classic%' OR order_product_name like '%Metro%'
	OR order_product_name like '%Wrap-Rx%')";       
	break;
	
	case 'PREMIUM PLUS':	 
	$Filtre_collection		  = " AND supplier = 'PREMIUM PLUS'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%PREMIUM PLUS%'"; 
	break;
	
	case 'FACETALK':	 
	$Filtre_collection		  = " AND supplier = 'FACETALK'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%FACETALK%'"; 
	break;
	
	case 'TMX':	 
	$Filtre_collection		  = " AND supplier = 'TMX'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%TMX%'"; 
	break;
	
	case 'JUNGLE':	 
	$Filtre_collection		  = " AND supplier = 'JUNGLE'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%JUNGLE%'"; 
	break;
	 
	case 'PRIIVALI':	 
	$Filtre_collection		  = " AND supplier = 'PRIIVALI'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%PRIIVALI%'"; 
	break;
	
	case 'WILLOW MAE':	 
	$Filtre_collection		  = " AND supplier = 'WILLOW MAE'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%WILLOW MAE%'"; 
	break;
	
	case 'ONESUN':	 
	$Filtre_collection		  = " AND supplier = 'ONESUN'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%ONESUN%'"; 
	break;
	 
	case 'EGO':	 
	$Filtre_collection		  = " AND supplier = 'EGO'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%EGO%'"; 
	break;
	
	
	case 'SYOPTICAL':	 
	$Filtre_collection		  = " AND supplier = 'SYOPTICAL'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%SYOPTICAL%'"; 
	break;
	
	case 'KANGLE':	 
	$Filtre_collection		  = " AND supplier = 'KANGLE'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%KANGLE%'"; 
	break;
	
	case 'BENCH':	 
	$Filtre_collection		  = " AND supplier = 'BENCH'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%BENCH%'"; 
	break;
	
	case 'MODERN PLASTICS II':	 
	$Filtre_collection		  = " AND supplier = 'MODERN PLASTICS II'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%MODERN PLASTICS II%'"; 
	break;
	
	case 'X-LOOK':	 
	$Filtre_collection		  = " AND supplier = 'X-LOOK'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%X-LOOK%'"; 
	break;
	
		
	case 'TOM FORD':    	 
	$Filtre_collection 		  = " AND supplier = 'TOM FORD'"; 
	$Filtre_collection_frames = " AND order_product_name like '%TOM FORD%'";       	
	break;
	
	case 'MOSKI':    	 
	$Filtre_collection 		  = " AND supplier = 'MOSKI'"; 
	$Filtre_collection_frames = " AND order_product_name like '%MOSKI%'";       	
	break;
	
	case 'NOUVELLE TENDANCE':    	 
	$Filtre_collection 		  = " AND supplier = 'NOUVELLE TENDANCE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%NOUVELLE TENDANCE%'";       	
	break;
	
	case 'ELEGANTE':    	 
	$Filtre_collection 		  = " AND supplier = 'ELEGANTE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ELEGANTE%'";       	
	break;
	
	case 'FLOATS':    	 
	$Filtre_collection 		  = " AND supplier = 'FLOATS'"; 
	$Filtre_collection_frames = " AND order_product_name like '%FLOATS%'";       	
	break;
		
	case 'MARIE CLAIRE':    	 
	$Filtre_collection 		  = " AND supplier = 'MARIE CLAIRE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%MARIE CLAIRE%'";       	
	break;	
	
	case 'GB+':    	 
	$Filtre_collection 		  = " AND supplier = 'GB+'"; 
	$Filtre_collection_frames = " AND order_product_name like '%GB+%'";       	
	break;
	
	case 'ELEGANTIA':    	 
	$Filtre_collection 		  = " AND supplier = 'ELEGANTIA'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ELEGANTIA%'";       	
	break;
	
	case 'ELLE':    	 
	$Filtre_collection 		  = " AND supplier = 'ELLE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ELLE%'";       	
	break;
	
	case 'ALPHA':    	 
	$Filtre_collection 		  = " AND supplier = 'ALPHA'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ALPHA%'";       	
	break;
	
	case 'MORRIZ OF SWEDEN':    	 
	$Filtre_collection 		  = " AND supplier = 'MORRIZ OF SWEDEN'"; 
	$Filtre_collection_frames = " AND order_product_name like '%MORRIZ OF SWEDEN%'";       	
	break;
	
	case 'PERFECTO':    	 
	$Filtre_collection 		  = " AND supplier = 'PERFECTO'"; 
	$Filtre_collection_frames = " AND order_product_name like '%PERFECTO%'";       	
	break;
	
	case 'STYRKA':    	 
	$Filtre_collection 		  = " AND supplier = 'STYRKA'"; 
	$Filtre_collection_frames = " AND order_product_name like '%STYRKA%'";       	
	break;
	
	case 'ARTLIFE':    	 
	$Filtre_collection 		  = " AND supplier = 'ARTLIFE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ARTLIFE%'";       	
	break;
	
	case 'AIE EYEWEAR':    	 
	$Filtre_collection 		  = " AND supplier = 'AIE EYEWEAR'"; 
	$Filtre_collection_frames = " AND order_product_name like '%AIE EYEWEAR%'";       	
	break;
	
	case 'WAHO':    	 
	$Filtre_collection 		  = " AND supplier = 'WAHO'"; 
	$Filtre_collection_frames = " AND order_product_name like '%WAHO%'";       	
	break;
	
	case 'MAGNETO':    	 
	$Filtre_collection 		  = " AND supplier = 'MAGNETO'"; 
	$Filtre_collection_frames = " AND order_product_name like '%MAGNETO%'";       	
	break;
	

	
	case 'ESQUIRE':    	 
	$Filtre_collection 		  = " AND supplier = 'ESQUIRE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ESQUIRE%'";       	
	break;
	
	case 'BUNOVIATA':    	 
	$Filtre_collection 		  = " AND supplier = 'BUNOVIATA'"; 
	$Filtre_collection_frames = " AND order_product_name like '%BUNOVIATA%'";       	
	break;
	
	case 'AFTERBANG':    	 
	$Filtre_collection 		  = " AND supplier = 'AFTERBANG'"; 
	$Filtre_collection_frames = " AND order_product_name like '%AFTERBANG%'";       	
	break;
	
	case 'EYEDANCE':    	 
	$Filtre_collection 		  = " AND supplier = 'EYEDANCE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%EYEDANCE%'";       	
	break;
	
	case 'VISIBLE':    	 
	$Filtre_collection 		  = " AND supplier = 'VISIBLE'"; 
	$Filtre_collection_frames = " AND order_product_name like '%VISIBLE%'";       	
	break;
	
	case 'MAX&TIBER':    	 
	$Filtre_collection 		  = " AND supplier = 'MAX&TIBER'"; 
	$Filtre_collection_frames = " AND order_product_name like '%MAX&TIBER%'";       	
	break;
	
	case 'KACTUS':    	 
	$Filtre_collection 		  = " AND supplier = 'KACTUS'"; 
	$Filtre_collection_frames = " AND order_product_name like '%KACTUS%'";       	
	break;

	
	case 'JOHANN VON GOISERN':    	 
	$Filtre_collection 		  = " AND supplier = 'JOHANN VON GOISERN'"; 
	$Filtre_collection_frames = " AND order_product_name like '%JOHANN VON GOISERN%'";       		
	break;
	
	
	case 'RAY-BAN':     	 
	$Filtre_collection		  = " AND supplier = 'RAY-BAN'";  
	$Filtre_collection_frames = " AND order_product_name like '%RAY-BAN%'";       	
	break;
	
	case 'OXBOW':       	 
	$Filtre_collection 		  = " AND supplier = 'OXBOW'";   
	$Filtre_collection_frames = " AND order_product_name like '%OXBOW%'";        	
	break;
	
	case 'PROFILO':       	 
	$Filtre_collection 		  = " AND supplier = 'PROFILO'";   
	$Filtre_collection_frames = " AND order_product_name like '%PROFILO%'";        	
	break;
	
	case 'NIKE VISION': 	 
	$Filtre_collection 		  = " AND supplier = 'NIKE VISION'";  	
	$Filtre_collection_frames = " AND order_product_name like '%NIKE VISION%'";   
	break;
	
	case 'JOHN LENNON': 	 
	$Filtre_collection 		  = " AND supplier = 'JOHN LENNON'";  	
	$Filtre_collection_frames = " AND order_product_name like '%JOHN LENNON%'";   
	break;
	
	case 'JIL SANDER':    	 
	$Filtre_collection		  = " AND supplier = 'JIL SANDER'"; 
	$Filtre_collection_frames = " AND order_product_name like '%JIL SANDER%'";       	
	break;
	
	case 'VALENTINO':    	 
	$Filtre_collection		  = " AND supplier = 'VALENTINO'"; 
	$Filtre_collection_frames = " AND order_product_name like '%VALENTINO%'";       	
	break;
	
	case 'VICOMTE A':    	 
	$Filtre_collection		  = " AND supplier = 'VICOMTE A'"; 
	$Filtre_collection_frames = " AND order_product_name like '%VICOMTE A%'";       	
	break;
	
	case 'GIVENCHY':    	 
	$Filtre_collection		  = " AND supplier = 'GIVENCHY'"; 
	$Filtre_collection_frames = " AND order_product_name like '%GIVENCHY%'";       	
	break;
	
	case 'CALVIN KLEIN':	 
	$Filtre_collection 		  = " AND supplier = 'CALVIN KLEIN'"; 
	$Filtre_collection_frames = " AND order_product_name like '%CALVIN KLEIN%'";    	
	break;  
	
	case 'GANT':        	 
	$Filtre_collection 		  = " AND supplier = 'GANT'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%GANT%'";       	
	break;  
	
	case 'MODERN PLASTICS I':        	 
	$Filtre_collection 		  = " AND supplier = 'MODERN PLASTICS I'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%MODERN PLASTICS I%'";       	
	break;  
	
	case 'FASHIONTABULOUS':        	 
	$Filtre_collection 		  = " AND supplier = 'FASHIONTABULOUS'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%FASHIONTABULOUS%'";       	
	break;  
	
	case 'GENEVIEVE BOUTIQUE':        	 
	$Filtre_collection 		  = " AND supplier = 'GENEVIEVE BOUTIQUE'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%GENEVIEVE BOUTIQUE%'";       	
	break;  
	
	case 'GEN-Y':        	 
	$Filtre_collection 		  = " AND supplier = 'GEN-Y'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%GEN-Y%'";       	
	break;  
	
	case 'U ROCK':        	 
	$Filtre_collection 		  = " AND supplier = 'U ROCK'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%U ROCK%'";       	
	break;  
	
	case 'CZONE':        	 
	$Filtre_collection 		  = " AND supplier = 'CZONE'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%CZONE%'";       	
	break; 
	
	case 'MICHAEL KORS':        	 
	$Filtre_collection 		  = " AND supplier = 'MICHAEL KORS'"; 	 
	$Filtre_collection_frames = " AND order_product_name like '%MICHAEL KORS%'";       	
	break; 
	
	case 'ECLIPSE':     	 
	$Filtre_collection 		  = " AND supplier = 'ECLIPSE'"; 	
	$Filtre_collection_frames = " AND order_product_name like '%ECLIPSE%'";        	
	break;  
	
	case 'ARROW':       	 
	$Filtre_collection 		  = " AND supplier = 'ARROW'";	
	$Filtre_collection_frames = " AND order_product_name like '%ARROW%'";    		
	break;
	  
	case 'NURBS':       	 
	$Filtre_collection 		  = " AND supplier = 'NURBS'"; 
	$Filtre_collection_frames = " AND order_product_name like '%NURBS%'";    	    	
	break;
	
	case '19V69':       	 
	$Filtre_collection 		  = " AND supplier = '19V69'";	
	$Filtre_collection_frames = " AND order_product_name like '%19V69%'";    		
	break;
	
	case 'MONTANA +':   	 
	$Filtre_collection 		  = " AND supplier = 'MONTANA +'";	
	$Filtre_collection_frames = " AND order_product_name like '%MONTANA +%'";	
	break;
	
	case 'KUBIK':   	 
	$Filtre_collection 		  = " AND supplier = 'KUBIK'";	
	$Filtre_collection_frames = " AND order_product_name like '%KUBIK%'";	
	break;
		
	case 'KUBIK ONE-CA':   	 
	$Filtre_collection 		  = " AND supplier = 'KUBIK ONE-CA'";	
	$Filtre_collection_frames = " AND order_product_name like '%KUBIK ONE-CA%'";	
	break;
	
	case 'GENEVIEVE PARIS DESIGN':   	 
	$Filtre_collection 		  = " AND supplier = 'GENEVIEVE PARIS DESIGN'";	
	$Filtre_collection_frames = " AND order_product_name like '%GENEVIEVE PARIS DESIGN%'";	
	break;
	
	case 'MODERN PLASTIC II':   	 
	$Filtre_collection 		  = " AND supplier = 'MODERN PLASTIC II'";	
	$Filtre_collection_frames = " AND order_product_name like '%MODERN PLASTIC II%'";	
	break;
	
	case 'MILANO 6769': 	 
	$Filtre_collection 		  = " AND  (supplier = 'MILANO 6769' OR  supplier = 'MILANO6769')";	
	$Filtre_collection_frames = " AND order_product_name like '%MILANO 6769%'";	
	break;
	
	case 'MILANO YOUNG': 	 
	$Filtre_collection 		  = " AND supplier = 'MILANO YOUNG'";	
	$Filtre_collection_frames = " AND order_product_name like '%MILANO YOUNG%'";	
	break;
	
	case 'MILANO 6769 CONSIGNE': 	 
	$Filtre_collection 		  = " AND supplier = 'MILANO 6769 CONSIGNE'";	
	$Filtre_collection_frames = " AND order_product_name like '%MILANO 6769 CONSIGNE%'";	
	break;
	
	case 'AZARO': 	 
	$Filtre_collection 		  = " AND supplier = 'AZARO'";	
	$Filtre_collection_frames = " AND order_product_name like '%AZARO%'";	
	break;
	
	case 'SORA': 	 
	$Filtre_collection 		  = " AND supplier = 'SORA'";	
	$Filtre_collection_frames = " AND order_product_name like '%SORA%'";	
	break;
	
	case 'CARISMA': 	 
	$Filtre_collection 		  = " AND supplier = 'CARISMA'";	
	$Filtre_collection_frames = " AND order_product_name like '%CARISMA%'";	
	break;
	
	case 'SUN TREND': 	 
	$Filtre_collection 		  = " AND supplier = 'SUN TREND'";	
	$Filtre_collection_frames = " AND order_product_name like '%SUN TREND%'";	
	break;
	
	case 'MILANO 6769 BRERA': 	 
	$Filtre_collection 		  = " AND supplier = 'MILANO 6769 BRERA'";	
	$Filtre_collection_frames = " AND order_product_name like '%MILANO 6769 BRERA%'";	
	break;
	
	case 'MICOLII': 	 
	$Filtre_collection 		  = " AND supplier like '%MICOLII%'";	
	$Filtre_collection_frames = " AND order_product_name like '%MICOLII%'";	
	break;
	
	case 'MILANO MONTENAPOLEONE': 	 
	$Filtre_collection 		  = " AND supplier like '%NAPOLEONE%'";	
	$Filtre_collection_frames = " AND order_product_name like '%NAPOLEONE%'";	
	break;
	
		
	case 'PERCE':       	 
	$Filtre_collection		  = " AND supplier = 'PERCE'";	
	$Filtre_collection_frames = " AND order_product_name like '%PERCE%'";		
	break;
	
	case 'CEBE':        	 
	$Filtre_collection 		  = " AND supplier = 'CEBE'";	
	$Filtre_collection_frames = " AND order_product_name like '%CEBE%'";			
	break;
	
	case 'OPTIMIZE':    	 
	$Filtre_collection 		  = " AND supplier = 'OPTIMIZE'";
	$Filtre_collection_frames = " AND order_product_name like '%OPTIMIZE%'";			
	break;
	
	case 'RUIMANNI':    	 
	$Filtre_collection 		  = " AND supplier = 'RUIMANNI'";	
	$Filtre_collection_frames = " AND order_product_name like '%RUIMANNI%'";		
	break; 
	 
	case 'SERMATT':    	 	 
	$Filtre_collection 	      = " AND supplier = 'SERMATT'";	
	$Filtre_collection_frames = " AND order_product_name like '%SERMATT%'";		
	break;
	
	case 'MONTANA':    	 	 
	$Filtre_collection        = " AND supplier = 'MONTANA'";	
	$Filtre_collection_frames = " AND order_product_name like '%MONTANA%'";		
	break;
	
	case 'PREMIUM':    		 
	$Filtre_collection 		  = " AND supplier = 'PREMIUM'";	
	$Filtre_collection_frames = " AND order_product_name like '%PREMIUM%'";		
	break; 
	
	case 'BUGETTI':    		 
	$Filtre_collection 	      = " AND supplier = 'BUGETTI'";	
	$Filtre_collection_frames = " AND order_product_name like '%BUGETTI%'";		
	break; 
	
	case 'RENDEZVOUS':    	 
	$Filtre_collection 		  = " AND supplier = 'RENDEZVOUS'";
	$Filtre_collection_frames = " AND order_product_name like '%RENDEZVOUS%'";		
	break;
	
	case 'TOKADO':    	 
	$Filtre_collection 		  = " AND supplier = 'TOKADO'";
	$Filtre_collection_frames = " AND order_product_name like '%TOKADO%'";		
	break;
	
	case 'VINYL FACTORY':    	 
	$Filtre_collection 		  = " AND supplier = 'VINYL FACTORY'";
	$Filtre_collection_frames = " AND order_product_name like '%VINYL FACTORY%'";		
	break;
	
	case 'MODELLI':      	 
	$Filtre_collection		  = " AND supplier = 'MODELLI'";		
	$Filtre_collection_frames = " AND order_product_name like '%MODELLI%'";	
	break;
	
	case 'BLUE RAY':    	 
	$Filtre_collection 		  = " AND supplier = 'BLUE RAY'";	
	$Filtre_collection_frames = " AND order_product_name like '%BLUE RAY%'";		
	break;
	
	case 'SUNOPTIC':    	 
	$Filtre_collection 		  = " AND supplier = 'SUNOPTIC'";	
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC%'";		
	break;
	
	case 'SUNOPTIC MASSIMO': 
	$Filtre_collection 		  = " AND supplier = 'SUNOPTIC MASSIMO'"; 
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC MASSIMO%'";
	break;
	
	
	case 'ISEE':        	 
	$Filtre_collection 		  = " AND supplier = 'ISEE'";	
	$Filtre_collection_frames = " AND order_product_name like '%ISEE%'";			
	break;
	
	case 'VARIONET':    	 
	$Filtre_collection 		  = " AND supplier = 'VARIONET'";	
	$Filtre_collection_frames = " AND order_product_name like '%VARIONET%'";		
	break;
	
	case 'CLIP SOLAIRES':    
	$Filtre_collection 		  = " AND supplier = 'CLIP SOLAIRES'";	
	$Filtre_collection_frames = " AND order_product_name like '%CLIP SOLAIRES%'";
	break;
	
	case 'SUNOPTIC AK': 	 
	$Filtre_collection 		  = " AND supplier = 'SUNOPTIC AK'";	
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC AK%'";	
	break;
	
	case 'FREE PLUS':   	 
	$Filtre_collection 		  = " AND supplier = 'FREE PLUS'";	
	$Filtre_collection_frames = " AND order_product_name like '%FREE PLUS%'";	
	break;
	
	case 'SUNOPTIC K':  	 
	$Filtre_collection		  = " AND supplier = 'SUNOPTIC K'";
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC K%'";			
	break;
	
	case 'SUNOPTIC CP': 	 
	$Filtre_collection		  = " AND supplier = 'SUNOPTIC CP'";
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC CP%'";			
	break;
	
	case 'SUNOPTIC PK': 	 
	$Filtre_collection		  = " AND supplier = 'SUNOPTIC PK'";	
	$Filtre_collection_frames = " AND order_product_name like '%SUNOPTIC PK%'";		
	break;
	
	case 'AUTRES':      	 
	$Filtre_collection 		  = " AND supplier = 'AUTRES'";	
	$Filtre_collection_frames = " AND order_product_name like '%AUTRES%'";			
	break;
		
	case 'ELLE':      	 
	$Filtre_collection 		  = " AND supplier = 'ELLE'";	
	$Filtre_collection_frames = " AND order_product_name like '%ELLE%'";			
	break;
		
	case 'ELLE-CA':      	 
	$Filtre_collection 		  = " AND supplier = 'ELLE-CA'";	
	$Filtre_collection_frames = " AND order_product_name like '%ELLE-CA%'";			
	break;
		
	case 'ELLE-ECA':      	 
	$Filtre_collection 		  = " AND supplier = 'ELLE-ECA'";	
	$Filtre_collection_frames = " AND order_product_name like '%ELLE-ECA%'";			
	break;

	case 'HAGGAR':     		
	$Filtre_collection 	   = " AND supplier = 'HAGGAR'";
	$Filtre_collection_frames = " AND order_product_name like '%HAGGAR%'";					
	break;
	 
	case 'DI GIANNI':   	 
	$Filtre_collection 	      = " AND supplier = 'DI GIANNI'";
	$Filtre_collection_frames = " AND order_product_name like '%DI GIANNI%'";				
	break;
	
	case 'POLAR':       	 
	$Filtre_collection 		  = " AND supplier = 'POLAR'";	
	$Filtre_collection_frames = " AND order_product_name like '%POLAR%'";				
	break;
	
	case 'GO IWEAR':    	 
	$Filtre_collection		  = " AND supplier = 'GO IWEAR'";	
	$Filtre_collection_frames = " AND order_product_name like '%GO IWEAR%'";				
	break;
	
	case 'BRENDELL':    	 
	$Filtre_collection 		  = " AND supplier = 'BRENDELL'";	
	$Filtre_collection_frames = " AND order_product_name like '%BRENDELL%'";				
	break;
	
	case 'GIA VISTO':   	 
	$Filtre_collection 		  = " AND supplier = 'GIA VISTO'";	
	$Filtre_collection_frames = " AND order_product_name like '%GIA VISTO%'";			
	break;
	
	case 'MARC OPOLO':  	 
	$Filtre_collection = " AND supplier = 'MARC OPOLO'";	
	$Filtre_collection_frames = " AND order_product_name like '%MARC OPOLO%'";			
	break;
	
	case 'SECG':        	 
	$Filtre_collection = " AND supplier = 'SECG'";	
	$Filtre_collection_frames = " AND order_product_name like '%SECG%'";					
	break;
	
	case 'SILOAM':     	 	 
	$Filtre_collection = " AND supplier = 'SILOAM'";
	$Filtre_collection_frames = " AND order_product_name like '%SILOAM%'";				
	break;
	
	case 'STAR':       	 	 
	$Filtre_collection 		  = " AND supplier = 'STAR'";		
	$Filtre_collection_frames = " AND order_product_name like '%STAR%'";				
	break;
	
	case 'VENETO':      	 
	$Filtre_collection 		  = " AND supplier = 'VENETO'";	
	$Filtre_collection_frames = " AND order_product_name like '%VENETO%'";		
	break;
	
	case 'ZENZERO':     	 
	$Filtre_collection 		  = " AND supplier = 'ZENZERO'";
	$Filtre_collection_frames = " AND order_product_name like '%ZENZERO%'";				
	break;
	
	case 'NORDIC':      	 
	$Filtre_collection 		 = " AND supplier = 'NORDIC'";
	$Filtre_collection_frames = " AND order_product_name like '%NORDIC%'";							
	break;
	
	case 'HUM SOL':     	 
	$Filtre_collection 		  = " AND supplier = 'HUM SOL'";
	$Filtre_collection_frames = " AND order_product_name like '%HUM SOL%'";				
	break;
	
	case 'KING SIZE':  	     
	$Filtre_collection 		  = " AND supplier = 'KING SIZE'";	
	$Filtre_collection_frames = " AND order_product_name like '%KING SIZE%'";		
	break;
	
	case 'ERNEST HEMINGWAY': 
	$Filtre_collection 	      = " AND supplier = 'ERNEST HEMINGWAY'"; 
	$Filtre_collection_frames = " AND order_product_name like '%ERNEST HEMINGWAY%'";	
	break;
	
	case 'WRANGLER JEANS CO':
	$Filtre_collection 		  = " AND supplier = 'WRANGLER JEANS CO'";
	$Filtre_collection_frames = " AND order_product_name like '%WRANGLER JEANS CO%'";	
	break;

	case 'SILHOUETTE': 		 
	$Filtre_collection 		  = " AND supplier = 'SILHOUETTE'";
	$Filtre_collection_frames = " AND order_product_name like '%SILHOUETTE%'";			
	break;
	
	case 'CASINO':     	 	 
	$Filtre_collection 		  = " AND supplier = 'CASINO'";	
	$Filtre_collection_frames = " AND order_product_name like '%CASINO%'";			
	break;
	
	case 'JELLY BEAN':  	 
	$Filtre_collection 		  = " AND supplier = 'JELLY BEAN'";
	$Filtre_collection_frames = " AND order_product_name like '%JELLY BEAN%'";			
	break;
	
	case 'MARC HUNTER': 	 
	$Filtre_collection		  = " AND supplier = 'MARC HUNTER'";	
	$Filtre_collection_frames = " AND order_product_name like '%MARC HUNTER%'";		
	break;
	
	case 'JUBILLE':     	 
	$Filtre_collection 		  = " AND supplier = 'JUBILLE'";	
	$Filtre_collection_frames = " AND order_product_name like '%JUBILLE%'";			
	break;
	
	
	case 'DALE JR':     	 
	$Filtre_collection 		  = " AND supplier = 'DALE JR'";	
	$Filtre_collection_frames = " AND order_product_name like '%DALE JR%'";			
	break;
	
	case 'WOOLRICH':    	 
	$Filtre_collection		  = " AND supplier = 'WOOLRICH'";	
	$Filtre_collection_frames = " AND order_product_name like '%WOOLRICH%'";			
	break;
	
	case 'JOAN COLLINS':	 
	$Filtre_collection 		  = " AND supplier = 'JOAN COLLINS'";
	$Filtre_collection_frames = " AND order_product_name like '%JOAN COLLINS%'";			
	break;
	
	case 'NICKELODEON': 	 
	$Filtre_collection 		  = " AND supplier = 'NICKELODEON'";
	$Filtre_collection_frames = " AND order_product_name like '%NICKELODEON%'";			
	break;
	
	case 'HUMPHREY':    	 
	$Filtre_collection 		  = " AND supplier = 'HUMPHREY'";	
	$Filtre_collection_frames = " AND order_product_name like '%HUMPHREY%'";			
	break;
	
	case 'DOLCE GABANNA':    
	$Filtre_collection		  = " AND supplier = 'DOLCE GABANNA'";
	$Filtre_collection_frames = " AND order_product_name like '%DOLCE GABANNA%'";		
	break;
	
	case 'TIFFANY & CO':     
	$Filtre_collection 		  = " AND supplier = 'TIFFANY & CO'";
	$Filtre_collection_frames = " AND order_product_name like '%TIFFANY & CO%'";			
	break;
	
	case 'OAKLEY':      	 
	$Filtre_collection 		  = " AND supplier = 'OAKLEY'";
	$Filtre_collection_frames = " AND order_product_name like '%OAKLEY%'";				
	break;
	
	case 'LACOSTE':      	 
	$Filtre_collection 		  = " AND supplier = 'LACOSTE'";	
	$Filtre_collection_frames = " AND order_product_name like '%LACOSTE%'";			
	break;	
	
	case 'ENHANCE':      	 
	$Filtre_collection 		  = " AND supplier = 'ENHANCE'";	
	$Filtre_collection_frames = " AND order_product_name like '%ENHANCE%'";			
	break;	
	
	case 'ARMANI':      	 
	$Filtre_collection 		  = " AND supplier = 'ARMANI'";	
	$Filtre_collection_frames = " AND order_product_name like '%ARMANI%'";			
	break;	
	
	case 'ADIDAS':      	 
	$Filtre_collection 		  = " AND supplier = 'ADIDAS'";	
	$Filtre_collection_frames = " AND order_product_name like '%ADIDAS%'";			
	break;
	
	case 'CHARMANT':      	 
	$Filtre_collection 		  = " AND supplier = 'CHARMANT'";	
	$Filtre_collection_frames = " AND order_product_name like '%CHARMANT%'";			
	break;	
	
	case 'EDDIE BAUER':      	 
	$Filtre_collection 		  = " AND supplier = 'EDDIE BAUER'";	
	$Filtre_collection_frames = " AND order_product_name like '%EDDIE BAUER%'";			
	break;	
	
	case 'ELEGANTE':      	 
	$Filtre_collection 		  = " AND supplier = 'ELEGANTE'";	
	$Filtre_collection_frames = " AND order_product_name like '%ELEGANTE%'";			
	break;	
	
	case 'ESPRIT':      	 
	$Filtre_collection 		  = " AND supplier = 'ESPRIT'";	
	$Filtre_collection_frames = " AND order_product_name like '%ESPRIT%'";			
	break;
		
		
	case 'ESPRIT-CA':      	 
	$Filtre_collection 		  = " AND supplier = 'ESPRIT-CA'";	
	$Filtre_collection_frames = " AND order_product_name like '%ESPRIT-CA%'";			
	break;
		
		
	case 'ESPRIT-ECA':      	 
	$Filtre_collection 		  = " AND supplier = 'ESPRIT-ECA'";	
	$Filtre_collection_frames = " AND order_product_name like '%ESPRIT-ECA%'";			
	break;
	
	case 'FELIX MARCS':      	 
	$Filtre_collection 		  = " AND supplier = 'FELIX MARCS'";	
	$Filtre_collection_frames = " AND order_product_name like '%FELIX MARCS%'";			
	break;
	
	case 'FINEZZA':      	 
	$Filtre_collection 		  = " AND supplier = 'FINEZZA'";	
	$Filtre_collection_frames = " AND order_product_name like '%FINEZZA%'";			
	break;
	
	case 'FOCUS':      	 
	$Filtre_collection 		  = " AND supplier = 'FOCUS'";	
	$Filtre_collection_frames = " AND order_product_name like '%FOCUS%'";			
	break;
	
	case 'GENEVIEVE':      	 
	$Filtre_collection 		  = " AND supplier = 'GENEVIEVE'";	
	$Filtre_collection_frames = " AND order_product_name like '%GENEVIEVE%'";			
	break;
	
	case 'TRUSSARDI':      	 
	$Filtre_collection 		  = " AND supplier = 'TRUSSARDI'";	
	$Filtre_collection_frames = " AND order_product_name like '%TRUSSARDI%'";			
	break;
	
	case 'GIOVANI DI VENEZI':      	 
	$Filtre_collection 		  = " AND supplier = 'GIOVANI DI VENEZI'";	
	$Filtre_collection_frames = " AND order_product_name like '%GIOVANI DI VENEZI%'";			
	break;
	
	case 'IDEAL':      	 
	$Filtre_collection 		  = " AND supplier = 'IDEAL'";	
	$Filtre_collection_frames = " AND order_product_name like '%IDEAL%'";			
	break;
	
	case 'GENEVIEVE':      	 
	$Filtre_collection 		  = " AND supplier = 'GENEVIEVE'";	
	$Filtre_collection_frames = " AND order_product_name like '%GENEVIEVE%'";			
	break;
	
	case 'IKII':      	 
	$Filtre_collection 		  = " AND supplier = 'IKII'";	
	$Filtre_collection_frames = " AND order_product_name like '%IKII%'";			
	break;
	
	case 'MODERN':      	 
	$Filtre_collection 		  = " AND supplier = 'MODERN'";	
	$Filtre_collection_frames = " AND order_product_name like '%MODERN%'";			
	break;
	
	case 'MODERN TIMES':      	 
	$Filtre_collection 		  = " AND supplier = 'MODERN TIMES'";	
	$Filtre_collection_frames = " AND order_product_name like '%MODERN TIMES%'";			
	break;
	
	case 'MODZ':      	 
	$Filtre_collection 		  = " AND supplier = 'MODZ'";	
	$Filtre_collection_frames = " AND order_product_name like '%MODZ%'";			
	break;
		
	case 'MODZ KIDS':      	 
	$Filtre_collection 		  = " AND supplier = 'MODZ KIDS'";	
	$Filtre_collection_frames = " AND order_product_name like '%MODZ KIDS%'";			
	break;
	
	case 'PASCALE':      	 
	$Filtre_collection 		  = " AND supplier = 'PASCALE'";	
	$Filtre_collection_frames = " AND order_product_name like '%PASCALE%'";			
	break;
	
	case 'PEACE':      	 
	$Filtre_collection 		  = " AND supplier = 'PEACE'";	
	$Filtre_collection_frames = " AND order_product_name like '%PEACE%'";			
	break;
	
	case 'PUMA':      	 
	$Filtre_collection 		  = " AND supplier = 'PUMA'";	
	$Filtre_collection_frames = " AND order_product_name like '%PUMA%'";			
	break;
	
	case 'REFLEXION':      	 
	$Filtre_collection 		  = " AND supplier = 'REFLEXION'";	
	$Filtre_collection_frames = " AND order_product_name like '%REFLEXION%'";			
	break;
	
	case 'SEVENTEEN':      	 
	$Filtre_collection 		  = " AND supplier = 'SEVENTEEN'";	
	$Filtre_collection_frames = " AND order_product_name like '%SEVENTEEN%'";			
	break;
	
	case 'SUPER CLIP':      	 
	$Filtre_collection 		  = " AND supplier = 'SUPER CLIP'";	
	$Filtre_collection_frames = " AND order_product_name like '%SUPER CLIP%'";			
	break;
	
	case 'THIERRY MUGLER':      	 
	$Filtre_collection 		  = " AND supplier = 'THIERRY MUGLER'";	
	$Filtre_collection_frames = " AND order_product_name like '%THIERRY MUGLER%'";			
	break;
	
	case 'VALERIE SPENCER':      	 
	$Filtre_collection 		  = " AND supplier = 'VALERIE SPENCER'";	
	$Filtre_collection_frames = " AND order_product_name like '%VALERIE SPENCER%'";			
	break;
	
	case 'VICROLA':      	 
	$Filtre_collection 		  = " AND supplier = 'VICROLA'";	
	$Filtre_collection_frames = " AND order_product_name like '%VICROLA%'";			
	break;
	
}

if($_POST[rpt_search]=="search orders"){
//1- Commandes Rx incluant un frame
$rptQuery="SELECT orders.*, ifc_ca_exclusive.price FROM orders, extra_product_orders , ifc_ca_exclusive
WHERE ifc_ca_exclusive.primary_key = orders.order_product_id
AND orders.order_num = extra_product_orders.order_num 
AND extra_product_orders.category='Frame' 
AND order_date_processed BETWEEN '$datefrom' AND '$dateto'
AND order_product_type  IN ('exclusive') 
$Filter_user_id_Rx
$Filtre_collection

UNION 

SELECT orders.*, safety_exclusive.price FROM orders, extra_product_orders , safety_exclusive
WHERE safety_exclusive.primary_key = orders.order_product_id
AND orders.order_num = extra_product_orders.order_num 
AND extra_product_orders.category='Frame' 
AND order_date_processed BETWEEN '$datefrom' AND '$dateto'
AND order_product_type  IN ('exclusive') 
$Filter_user_id_Rx_SAFETY
$Filtre_collection
";
//ORDER BY supplier,temple_model_num DESC
//echo '<br>' .  $rptQuery . '<br>' ;



if ($rptQuery!="")
{
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items1 because: ' . mysqli_error($con). $rptQuery);
$usercount=mysqli_num_rows($rptResult);
$rptQuery="";
}
				


}




?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>
var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}
</script>
</head>
<body onload="doOnLoad();" onLoad="goto_date.order_num.focus();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="frame_report.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Order Reports</font></b></td>
            		</tr>

			
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						Date From
					</div></td>
					<td><input name="date_from" type="text" class="formField" id="date_from" value="<?php if ($datefrom <> '') echo $datefrom;else echo $datedujour?>" size="11">
					</td>
					<td><div align="center">
						Through
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="<?php  if ($dateto <> '') echo $dateto;else echo $datedujour?>" size="11">
					</td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
						Select account
					</div></td>
					
                    <td align="left" nowrap >
                    <select id="user_id" name="user_id" class="formField">
						<option value="ifc.ca"      	<?php if ($user_id =='ifc.ca')      		echo ' selected'; ?>>Tous les comptes Ifc.ca</option>
                        <option value="" disabled ></option>
                        <option value=""            	<?php if ($user_id =='')            	    echo ' selected'; ?>>Tous les  Entrepots</option>
                        <option value="" disabled ></option>
                        <option value="toutsaufentrepot"<?php if ($user_id =='toutsaufentrepot')    echo ' selected'; ?>>Comptes Ifc.ca SAUF les Entrepots</option>
                        <option value="" disabled ></option>
                        <option value="chicoutimi"  	<?php if ($user_id =='chicoutimi')  	    echo ' selected'; ?>>Entrepot de la lunette Chicoutimi</option>
                        <option value="entrepotdr"  	<?php if ($user_id =='entrepotdr')  	    echo ' selected'; ?>>Entrepot de la lunette Drummondville</option>
                        <option value="longueuil"  	    <?php if ($user_id =='longueuil')  	    	echo ' selected'; ?>>Entrepot de la lunette Longueuil</option>
						<option value="entrepotifc" 	<?php if ($user_id =='entrepotifc') 	    echo ' selected'; ?>>Entrepot de la lunette Trois-Rivieres</option>
                        <option value="gatineau" 	    <?php if ($user_id =='gatineau') 	        echo ' selected'; ?>>Entrepot de la lunette Gatineau</option>
						<option value="granby" 	        <?php if ($user_id =='granby') 	            echo ' selected'; ?>>Entrepot de la lunette Granby</option>
                        <option value="entrepotquebec" 	<?php if ($user_id =='entrepotquebec') 	    echo ' selected'; ?>>Entrepot de la lunette Qu&eacute;bec</option>
                        <option value="laval"           <?php if ($user_id =='laval')       	    echo ' selected'; ?>>Entrepot de la lunette Laval</option>
                        <option value="levis"           <?php if ($user_id =='levis')       	    echo ' selected'; ?>>Entrepot de la lunette Levis</option>
                        <option value="sherbrooke"      <?php if ($user_id =='sherbrooke')       	echo ' selected'; ?>>Entrepot de la lunette Sherbrooke</option>
                        <option value="terrebonne"      <?php if ($user_id =='terrebonne')       	echo ' selected'; ?>>Entrepot de la lunette Terrebonne</option>
                        <option value="warehousehal"    <?php if ($user_id =='warehousehal')       	echo ' selected'; ?>>Optical Warehouse Halifax</option>
						<!-- <option value="montreal"   <?php// if ($user_id =='montreal')       	    echo ' selected'; ?>>Zone Tendance Montreal ZT1</option>  -->
						<option value="stjerome"        <?php if ($user_id =='stjerome')       	    echo ' selected'; ?>>Zone Tendance St-Jerome</option>
                       <option value="sorel"        	<?php if ($user_id =='sorel')       	    echo ' selected'; ?>>Entrepot de la lunette Sorel</option>
                       <option value="vaudreuil"        <?php if ($user_id =='vaudreuil')       	echo ' selected'; ?>>Entrepot de la lunette Vaudreuil</option>
					</select></td>
                    
					<td align="left" nowrap ><div align="right">
						Select Frame Collection
					</div></td>
					<td align="left" nowrap >
                    <select name="misc_unknown_purpose" id="misc_unknown_purpose" class="formField">
						<option value=""        	 	 <?php if ($misc_unknown_purpose == '')         	 echo ' selected'; ?>>All</option>
                        <option value="19V69"    	 	 <?php if ($misc_unknown_purpose == '19V69')    	 echo ' selected'; ?>>19V69</option>
                        <option value="AIE EYEWEAR"    	 <?php if ($misc_unknown_purpose == 'AIE EYEWEAR')   echo ' selected'; ?>>AIE EYEWEAR</option>
                        <option value="ADIDAS"    	 	 <?php if ($misc_unknown_purpose == 'ADIDAS')    	 echo ' selected'; ?>>ADIDAS</option>
                        <option value="AFTERBANG"    	 <?php if ($misc_unknown_purpose == 'AFTERBANG')   	 echo ' selected'; ?>>AFTERBANG</option>
                        <option value="ALPHA"    	     <?php if ($misc_unknown_purpose == 'ALPHA')   	     echo ' selected'; ?>>ALPHA</option>
                        <option value="ARMANI"    	 	 <?php if ($misc_unknown_purpose == 'ARMANI')    	 echo ' selected'; ?>>ARMANI</option>
                        <option value="ARTLIFE"    	 	 <?php if ($misc_unknown_purpose == 'ARTLIFE')    	 echo ' selected'; ?>>ARTLIFE</option>
                        <option value="AZARO"    	 	 <?php if ($misc_unknown_purpose == 'AZARO')    	 echo ' selected'; ?>>AZARO</option>
                        <option value="ArmouRx"  	 	 <?php if ($misc_unknown_purpose == 'ArmouRx')  	 echo ' selected'; ?>>ArmouRx</option>
                        <option value="AUTRES"   	  	 <?php if ($misc_unknown_purpose == 'AURES') 		 echo ' selected'; ?>>AUTRES</option>
                        <option value="ARROW"    	  	 <?php if ($misc_unknown_purpose == 'ARROW') 		 echo ' selected'; ?>>ARROW</option>
                        <option value="BENCH" 	  	 	 <?php if ($misc_unknown_purpose == 'BENCH') 	 echo ' selected'; ?>>BENCH</option>
                        <option value="BLUE RAY" 	  	 <?php if ($misc_unknown_purpose == 'BLUE RAY') 	 echo ' selected'; ?>>BLUE RAY</option>
                        <option value="BRENDELL" 	  	 <?php if ($misc_unknown_purpose == 'BRENDELL') 	 echo ' selected'; ?>>BRENDELL</option>
                        <option value="BUGETTI" 	  	 <?php if ($misc_unknown_purpose == 'BUGETTI') 	 	 echo ' selected'; ?>>BUGETTI</option>
                        <option value="BUNOVIATA" 	  	 <?php if ($misc_unknown_purpose == 'BUNOVIATA') 	 echo ' selected'; ?>>BUNOVIATA</option>
                        <option value="CALVIN KLEIN"  	 <?php if ($misc_unknown_purpose == 'CALVIN KLEIN')  echo ' selected'; ?>>CALVIN KLEIN</option>
                        <option value="CASINO"		  	 <?php if ($misc_unknown_purpose == 'CASINO') 		 echo ' selected'; ?>>CASINO</option>
                        <option value="CARISMA"		  	 <?php if ($misc_unknown_purpose == 'CARISMA') 		 echo ' selected'; ?>>CARISMA</option>
                        <option value="CLIP SOLAIRES" 	 <?php if ($misc_unknown_purpose == 'CLIP SOLAIRES') echo ' selected'; ?>>CLIP SOLAIRES</option>
                        <option value="CEBE" 		  	 <?php if ($misc_unknown_purpose == 'CEBE') 		 echo ' selected'; ?>>CEBE</option>
                        <option value="CZONE" 		  	 <?php if ($misc_unknown_purpose == 'CZONE') 		 echo ' selected'; ?>>CZONE</option>
                        <option value="CHARMANT" 		 <?php if ($misc_unknown_purpose == 'CHARMANT') 	 echo ' selected'; ?>>CHARMANT</option>
                        <option value="DALE JR" 	  	 <?php if ($misc_unknown_purpose == 'DALE JR') 		 echo ' selected'; ?>>DALE JR</option>
                        <option value="DI GIANNI" 	  	 <?php if ($misc_unknown_purpose == 'DI GIANNI') 	 echo ' selected'; ?>>DI GIANNI</option>
                        <option value="DOLCE GABANNA" 	 <?php if ($misc_unknown_purpose == 'DOLCE GABANNA') echo ' selected'; ?>>DOLCE GABANNA</option>
                        <option value="ECLIPSE"		  	 <?php if ($misc_unknown_purpose == 'ECLIPSE') 		 echo ' selected'; ?>>ECLIPSE</option>
                        <option value="EDDIE BAUER"		 <?php if ($misc_unknown_purpose == 'EDDIE BAUER') 	 echo ' selected'; ?>>EDDIE BAUER</option>
                        <option value="EGO"		         <?php if ($misc_unknown_purpose == 'EGO') 	 echo ' selected'; ?>>EGO</option>
                        <option value="ELEGANTE"	 	 <?php if ($misc_unknown_purpose == 'ELEGANTE') 	 echo ' selected'; ?>>ELEGANTE</option>
                        <option value="ELEGANTIA"	 	 <?php if ($misc_unknown_purpose == 'ELEGANTIA') 	 echo ' selected'; ?>>ELEGANTIA</option>
                        <option value="ELLE"	 	 	 <?php if ($misc_unknown_purpose == 'ELLE') 	 	 echo ' selected'; ?>>ELLE</option>
						<option value="ELLE-CA"	 	 	 <?php if ($misc_unknown_purpose == 'ELLE-CA') 	 	 echo ' selected'; ?>>ELLE-CA</option>
						<option value="ELLE-ECA"	 	 <?php if ($misc_unknown_purpose == 'ELLE-ECA')  	 echo ' selected'; ?>>ELLE-ECA</option>
                        <option value="ENHANCE"	 	 	 <?php if ($misc_unknown_purpose == 'ENHANCE') 	     echo ' selected'; ?>>ENHANCE</option>
                        <option value="ERNEST HEMINGWAY" <?php if ($misc_unknown_purpose == 'ERNEST HEMINGWAY') echo ' selected'; ?>>ERNEST HEMINGWAY</option>
                        <option value="ESQUIRE" 		 <?php if ($misc_unknown_purpose == 'ESQUIRE')	     echo ' selected'; ?>>ESQUIRE</option>
                        <option value="ESPRIT" 			 <?php if ($misc_unknown_purpose == 'ESPRIT')	     echo ' selected'; ?>>ESPRIT</option>
						<option value="ESPRIT-CA" 		 <?php if ($misc_unknown_purpose == 'ESPRIT-CA')	 echo ' selected'; ?>>ESPRIT-CA</option>
						<option value="ESPRIT-ECA" 		 <?php if ($misc_unknown_purpose == 'ESPRIT-ECA')	 echo ' selected'; ?>>ESPRIT-ECA</option>
                        <option value="EYEDANCE" 		 <?php if ($misc_unknown_purpose == 'EYEDANCE')	     echo ' selected'; ?>>EYEDANCE</option>
                        <option value="FACETALK" 	 	 <?php if ($misc_unknown_purpose == 'FACETALK')	 	 echo ' selected'; ?>>FACETALK</option>
                        <option value="FASHIONTABULOUS"  <?php if ($misc_unknown_purpose == 'FASHIONTABULOUS')echo ' selected'; ?>>FASHIONTABULOUS</option>
                        <option value="FELIX MARCS" 	 <?php if ($misc_unknown_purpose == 'FELIX MARCS')	 echo ' selected'; ?>>FELIX MARCS</option>
                        <option value="FINEZZA" 	 	 <?php if ($misc_unknown_purpose == 'FINEZZA')	 	 echo ' selected'; ?>>FINEZZA</option>
                        <option value="FLOATS" 	 		 <?php if ($misc_unknown_purpose == 'FLOATS')	 	 echo ' selected'; ?>>FLOATS</option>
                        <option value="FOCUS" 	 	     <?php if ($misc_unknown_purpose == 'FOCUS')	 	 echo ' selected'; ?>>FOCUS</option>
                        <option value="FREE" 			 <?php if ($misc_unknown_purpose == 'FREE') 		 echo ' selected'; ?>>FREE</option>
                        <option value="FREE PLUS"	     <?php if ($misc_unknown_purpose == 'FREE PLUS') 	 echo ' selected'; ?>>FREE PLUS</option>
                        <option value="FUGLIES_A" 		 <?php if ($misc_unknown_purpose == 'FUGLIES_A') 	 echo ' selected'; ?>>FUGLIES_A</option>
                        <option value="FUGLIES_B" 		 <?php if ($misc_unknown_purpose == 'FUGLIES_B') 	 echo ' selected'; ?>>FUGLIES_B</option>
                        <option value="FUGLIES_C" 		 <?php if ($misc_unknown_purpose == 'FUGLIES_C') 	 echo ' selected'; ?>>FUGLIES_C</option>   
                        <option value="GANT" 			 <?php if ($misc_unknown_purpose == 'GANT') 		 echo ' selected'; ?>>GANT</option>
                        <option value="GB+" 			 <?php if ($misc_unknown_purpose == 'GB+') 		 	 echo ' selected'; ?>>GB+</option>
                        <option value="GENEVIEVE" 	     <?php if ($misc_unknown_purpose == 'GENEVIEVE') 	 echo ' selected'; ?>>GENEVIEVE</option>
                        <option value="GENEVIEVE BOUTIQUE"<?php if ($misc_unknown_purpose == 'GENEVIEVE BOUTIQUE') echo ' selected'; ?>>GENEVIEVE BOUTIQUE</option>
                        <option value="GENEVIEVE PARIS DESIGN"<?php if ($misc_unknown_purpose == 'GENEVIEVE PARIS DESIGN') echo ' selected'; ?>>GENEVIEVE PARIS DESIGN</option>
                        
                         <option value="GEN-Y"<?php if ($misc_unknown_purpose == 'GEN-Y') echo ' selected'; ?>>GEN-Y</option>
                        <option value="GIA VISTO" 		 <?php if ($misc_unknown_purpose == 'GIA VISTO')  	 echo ' selected'; ?>>GIA VISTO</option>
                        <option value="GIOVANI DI VENEZI"<?php if ($misc_unknown_purpose == 'GIOVANI DI VENEZI') echo ' selected'; ?>>GIOVANI DI VENEZI</option>
                        <option value="GIVENCHY" 		 <?php if ($misc_unknown_purpose == 'GIVENCHY') 	 echo ' selected'; ?>>GIVENCHY</option>
                        <option value="GO IWEAR" 		 <?php if ($misc_unknown_purpose == 'GO IWEAR')      echo ' selected'; ?>>GO IWEAR</option>
                        <option value="HAGGAR" 			 <?php if ($misc_unknown_purpose == 'HAGGAR')        echo ' selected'; ?>>HAGGAR</option>
                        <option value="HUMPHREY" 		 <?php if ($misc_unknown_purpose == 'HUMPHREY') 	 echo ' selected'; ?>>HUMPHREY</option>
                        <option value="HUM SOL" 		 <?php if ($misc_unknown_purpose == 'HUM SOL') 	     echo ' selected'; ?>>HUM SOL</option>
                        <option value="IKII" 		 	 <?php if ($misc_unknown_purpose == 'IKII') 	     echo ' selected'; ?>>IKII</option>
                        <option value="ISEE" 			 <?php if ($misc_unknown_purpose == 'ISEE')          echo ' selected'; ?>>ISEE</option>
                        <option value="KACTUS" 			 <?php if ($misc_unknown_purpose == 'KACTUS')        echo ' selected'; ?>>KACTUS</option>
                        <option value="KANGLE" 			 <?php if ($misc_unknown_purpose == 'KANGLE')        echo ' selected'; ?>>KANGLE</option>
                        <option value="JELLY BEAN" 		 <?php if ($misc_unknown_purpose == 'JELLY BEAN')    echo ' selected'; ?>>JELLY BEAN</option>
                        <option value="JIL SANDER" 		 <?php if ($misc_unknown_purpose == 'JIL SANDER')    echo ' selected'; ?>>JIL SANDER</option>
                        <option value="JOAN COLLINS" 	 <?php if ($misc_unknown_purpose == 'JOAN COLLINS')  echo ' selected'; ?>>JOAN COLLINS</option>
                        <option value="JOHANN VON GOISERN" 	 <?php if ($misc_unknown_purpose == 'JOHANN VON GOISERN')  echo ' selected'; ?>>JOHANN VON GOISERN</option>
                        <option value="JOHN LENNON" 	 <?php if ($misc_unknown_purpose == 'JOHN LENNON')   echo ' selected'; ?>>JOHN LENNON</option>
                        <option value="JUBILLE" 		 <?php if ($misc_unknown_purpose == 'JUBILLE') 		 echo ' selected'; ?>>JUBILLE</option>
                        <option value="JUNGLE"			 <?php if ($misc_unknown_purpose == 'JUNGLE') 		 echo ' selected'; ?>>JUNGLE</option>
                        <option value="KUBIK" 		     <?php if ($misc_unknown_purpose == 'KUBIK') 	     echo ' selected'; ?>>KUBIK</option>
						<option value="KUBIK ONE-CA" 	 <?php if ($misc_unknown_purpose == 'KUBIK ONE-CA')	 echo ' selected'; ?>>KUBIK ONE-CA</option>
                        <option value="KING SIZE" 		 <?php if ($misc_unknown_purpose == 'KING SIZE') 	 echo ' selected'; ?>>KING SIZE</option>
                        <option value="LACOSTE" 		 <?php if ($misc_unknown_purpose == 'LACOSTE') 		 echo ' selected'; ?>>LACOSTE</option>
                        <option value="MAGNETO" 	     <?php if ($misc_unknown_purpose == 'MAGNETO')   	 echo ' selected'; ?>>MAGNETO</option>
                        <option value="MARC HUNTER" 	 <?php if ($misc_unknown_purpose == 'MARC HUNTER')   echo ' selected'; ?>>MARC HUNTER</option>
                        <option value="MARC OPOLO" 		 <?php if ($misc_unknown_purpose == 'MARC OPOLO')    echo ' selected'; ?>>MARC OPOLO</option>
						<option value="MARIE CLAIRE" 	 <?php if ($misc_unknown_purpose == 'MARIE CLAIRE')  echo ' selected'; ?>>MARIE CLAIRE</option>
						<option value="MAX&TIBER" 		 <?php if ($misc_unknown_purpose == 'MAX&TIBER')     echo ' selected'; ?>>MAX & TIBER</option>
                        <option value="MICHAEL KORS"     <?php if ($misc_unknown_purpose == 'MICHAEL KORS')  echo ' selected'; ?>>MICHAEL KORS</option>
						<option value="MICOLII"    		 <?php if ($misc_unknown_purpose == 'MICOLII')  	 echo ' selected'; ?>>MICOLII</option>
                        <option value="MILANO 6769" 	 <?php if ($misc_unknown_purpose == 'MILANO 6769')   echo ' selected'; ?>>MILANO 6769</option>
                        <option value="MILANO 6769 BRERA" 	 <?php if ($misc_unknown_purpose == 'MILANO 6769 BRERA')      echo ' selected'; ?>>MILANO 6769 BRERA</option>
                        <option value="MILANO 6769 CONSIGNE" <?php if ($misc_unknown_purpose == 'MILANO 6769 CONSIGNE')   echo ' selected'; ?>>MILANO 6769 CONSIGNE</option>
                        <option value="MILANO YOUNG" <?php if ($misc_unknown_purpose == 'MILANO YOUNG')   echo ' selected'; ?>>MILANO YOUNG</option>
                        <option value="MILANO MONTENAPOLEONE" 	     <?php if ($misc_unknown_purpose == 'MILANO MONTENAPOLEONE')     echo ' selected'; ?>>MILANO MONTENAPOLEONE</option>
                        <option value="MODELLI" 		 <?php if ($misc_unknown_purpose == 'MODELLI')		 echo ' selected'; ?>>MODELLI</option>
                        <option value="MODERN" 			 <?php if ($misc_unknown_purpose == 'MODERN')		 echo ' selected'; ?>>MODERN</option>
                        <option value="MODERN TIMES" 	 <?php if ($misc_unknown_purpose == 'MODERN TIMES')	 echo ' selected'; ?>>MODERN TIMES</option>
                        <option value="MODERN PLASTICS I" <?php if ($misc_unknown_purpose == 'MODERN PLASTICS I')echo ' selected'; ?>>MODERN PLASTICS I</option>
                        <option value="MODERN PLASTICS II" <?php if ($misc_unknown_purpose == 'MODERN PLASTICS II')echo ' selected'; ?>>MODERN PLASTICS II</option>
                        <option value="MODZ" 	 	     <?php if ($misc_unknown_purpose == 'MODZ')	 		 echo ' selected'; ?>>MODZ</option>
                        <option value="MODZ KIDS" 	 	 <?php if ($misc_unknown_purpose == 'MODZ KIDS')	 echo ' selected'; ?>>MODZ KIDS</option>
                        <option value="MONTANA" 		 <?php if ($misc_unknown_purpose == 'MONTANA') 		 echo ' selected'; ?>>MONTANA</option>
                        <option value="MONTANA +" 		 <?php if ($misc_unknown_purpose == 'MONTANA +') 	 echo ' selected'; ?>>MONTANA +</option>
                  		<option value="MORRIZ OF SWEDEN" <?php if ($misc_unknown_purpose == 'MORRIZ OF SWEDEN')  echo ' selected'; ?>>MORRIZ OF SWEDEN</option>
                 	    <option value="MOSKI" <?php if ($misc_unknown_purpose == 'MOSKI')  echo ' selected'; ?>>MOSKI</option>
                        
                        <option value="NICKELODEON"      <?php if ($misc_unknown_purpose == 'NICKELODEON')   echo ' selected'; ?>>NICKELODEON</option>
                        <option value="NIKE VISION"      <?php if ($misc_unknown_purpose == 'NIKE VISION')   echo ' selected'; ?>>NIKE VISION</option>
                        <option value="NORDIC" 			 <?php if ($misc_unknown_purpose == 'NORDIC') 		 echo ' selected'; ?>>NORDIC</option>
                        <option value="NOUVELLE TENDANCE" <?php if ($misc_unknown_purpose == 'NOUVELLE TENDANCE') 		 echo ' selected'; ?>>NOUVELLE TENDANCE</option>
                       
                        <option value="NURBS" 			 <?php if ($misc_unknown_purpose == 'NURBS') 		 echo ' selected'; ?>>NURBS</option>
                        <option value="ONESUN" 			 <?php if ($misc_unknown_purpose == 'ONESUN') 		 echo ' selected'; ?>>ONESUN</option>
                        <option value="OAKLEY" 			 <?php if ($misc_unknown_purpose == 'OAKLEY')	     echo ' selected'; ?>>OAKLEY</option>
                        <option value="OPTIMIZE" 		 <?php if ($misc_unknown_purpose == 'OPTIMIZE')	     echo ' selected'; ?>>OPTIMIZE</option>
                        <option value="OXBOW" 			 <?php if ($misc_unknown_purpose == 'OXBOW')		 echo ' selected'; ?>>OXBOW</option>
                        <option value="PASCALE" 		 <?php if ($misc_unknown_purpose == 'PASCALE')		 echo ' selected'; ?>>PASCALE</option>
                        <option value="PEACE" 		     <?php if ($misc_unknown_purpose == 'PEACE')		 echo ' selected'; ?>>PEACE</option>
                        <option value="PERFECTO" 	     <?php if ($misc_unknown_purpose == 'PERFECTO') 	 echo ' selected'; ?>>PERFECTO</option>
                        <option value="PERCE" 			 <?php if ($misc_unknown_purpose == 'PERCE') 		 echo ' selected'; ?>>PERCE</option>
                        <option value="POLAR" 			 <?php if ($misc_unknown_purpose == 'POLAR') 		 echo ' selected'; ?>>POLAR</option>
                        <option value="PREMIUM" 		 <?php if ($misc_unknown_purpose == 'PREMIUM') 		 echo ' selected'; ?>>PREMIUM</option>
						<option value="PREMIUM PLUS"	 <?php if ($misc_unknown_purpose == 'PREMIUM PLUS')  echo ' selected'; ?>>PREMIUM PLUS</option>
                        <option value="PRIIVALI" 		 <?php if ($misc_unknown_purpose == 'PRIIVALI') 	 echo ' selected'; ?>>PRIIVALI</option>
                        <option value="PROFILO"			 <?php if ($misc_unknown_purpose == 'PROFILO')  	 echo ' selected'; ?>>PROFILO</option>
                        <option value="PUMA"			 <?php if ($misc_unknown_purpose == 'PUMA')  		 echo ' selected'; ?>>PUMA</option>
                        <option value="RAY-BAN" 		 <?php if ($misc_unknown_purpose == 'RAY-BAN') 		 echo ' selected'; ?>>RAY-BAN</option>
                        <option value="REFLEXION" 		 <?php if ($misc_unknown_purpose == 'REFLEXION') 	 echo ' selected'; ?>>REFLEXION</option>
                        <option value="RENDEZVOUS" 		 <?php if ($misc_unknown_purpose == 'RENDEZVOUS')	 echo ' selected'; ?>>RENDEZVOUS</option>
                        <option value="RUIMANNI" 		 <?php if ($misc_unknown_purpose == 'RUIMANNI') 	 echo ' selected'; ?>>RUIMANNI</option>
                        <option value="SECG" 		     <?php if ($misc_unknown_purpose == 'SECG') 		 echo ' selected'; ?>>SECG</option>
                        <option value="SERMATT" 		 <?php if ($misc_unknown_purpose == 'SERMATT') 		 echo ' selected'; ?>>SERMATT</option>
                        <option value="SEVENTEEN" 		 <?php if ($misc_unknown_purpose == 'SEVENTEEN') 	 echo ' selected'; ?>>SEVENTEEN</option>
                        <option value="SILHOUETTE" 		 <?php if ($misc_unknown_purpose == 'SILHOUETTE')    echo ' selected'; ?>>SILHOUETTE</option>
                        <option value="SILOAM"			 <?php if ($misc_unknown_purpose == 'SILOAM')        echo ' selected'; ?>>SILOAM</option>
                        <option value="SORA" 			 <?php if ($misc_unknown_purpose == 'SORA')    echo ' selected'; ?>>SORA</option>
                        <option value="STAR"		     <?php if ($misc_unknown_purpose == 'STAR')		     echo ' selected'; ?>>STAR</option>
                        <option value="STYRKA"		     <?php if ($misc_unknown_purpose == 'STYRKA')		     echo ' selected'; ?>>STYRKA</option>
                        <option value="SUNOPTIC AK" 	 <?php if ($misc_unknown_purpose == 'SUNOPTIC AK')   echo ' selected'; ?>>SUNOPTIC AK</option>
                        <option value="SUNOPTIC CP" 	 <?php if ($misc_unknown_purpose == 'SUNOPTIC CP')   echo ' selected'; ?>>SUNOPTIC CP</option>
                        <option value="SUNOPTIC K"  	 <?php if ($misc_unknown_purpose == 'SUNOPTIC K')    echo ' selected'; ?>>SUNOPTIC K</option>
                        <option value="SUNOPTIC MASSIMO" <?php if ($misc_unknown_purpose == 'SUNOPTIC MASSIMO') echo ' selected'; ?>>SUNOPTIC MASSIMO</option>
                        <option value="SUNOPTIC PK" 	 <?php if ($misc_unknown_purpose == 'SUNOPTIC PK')   echo ' selected'; ?>>SUNOPTIC PK</option>
                        <option value="SUN TREND" 		 <?php if ($misc_unknown_purpose == 'SUN TREND')     echo ' selected'; ?>>SUN TREND</option>
                        <option value="SUPER CLIP" 	 	 <?php if ($misc_unknown_purpose == 'SUPER CLIP')    echo ' selected'; ?>>SUPER CLIP</option>
                        <option value="SYOPTICAL" 	 	 <?php if ($misc_unknown_purpose == 'SYOPTICAL')     echo ' selected'; ?>>SYOPTICAL</option>
                        <option value="THIERRY MUGLER" 	 <?php if ($misc_unknown_purpose == 'THIERRY MUGLER')echo ' selected'; ?>>THIERRY MUGLER</option>
                        <option value="TIFFANY & CO" 	 <?php if ($misc_unknown_purpose == 'TIFFANY & CO')  echo ' selected'; ?>>TIFFANY & CO</option>
                        <option value="TOKADO" 			 <?php if ($misc_unknown_purpose == 'TOKADO') 	 	 echo ' selected'; ?>>TOKADO</option>
                        <option value="TOM FORD" 		 <?php if ($misc_unknown_purpose == 'TOM FORD') 	 echo ' selected'; ?>>TOM FORD</option>
                        <option value="TMX" 			 <?php if ($misc_unknown_purpose == 'TMX') 	 		 echo ' selected'; ?>>TMX</option>
                        <option value="TRUSSARDI" 		 <?php if ($misc_unknown_purpose == 'TRUSSARDI') 	 echo ' selected'; ?>>TRUSSARDI</option>
                        <option value="U ROCK" 		      <?php if ($misc_unknown_purpose == 'U ROCK') 	     echo ' selected'; ?>>U ROCK</option>
                        <option value="VALERIE SPENCER"  <?php if ($misc_unknown_purpose == 'VALERIE SPENCER')echo ' selected'; ?>>VALERIE SPENCER</option>
                        <option value="VALENTINO" 		 <?php if ($misc_unknown_purpose == 'VALENTINO')     echo ' selected'; ?>>VALENTINO</option>
						<option value="VARIONET" 		 <?php if ($misc_unknown_purpose == 'VARIONET')	     echo ' selected'; ?>>VARIONET</option>
                        <option value="VENETO" 			 <?php if ($misc_unknown_purpose == 'VENETO') 	     echo ' selected'; ?>>VENETO</option>
                        <option value="VICOMTE A" 		 <?php if ($misc_unknown_purpose == 'VICOMTE A') 	 echo ' selected'; ?>>VICOMTE A</option>
                        <option value="VICROLA" 		 <?php if ($misc_unknown_purpose == 'VICROLA') 	     echo ' selected'; ?>>VICROLA</option>
                        <option value="VINYL FACTORY" 	 <?php if ($misc_unknown_purpose == 'VINYL FACTORY') echo ' selected'; ?>>VINYL FACTORY</option>
                        <option value="VISIBLE" 	     <?php if ($misc_unknown_purpose == 'VISIBLE') echo ' selected'; ?>>VISIBLE</option>
                        <option value="X-LOOK" 		 	 <?php if ($misc_unknown_purpose == 'X-LOOK')		 echo ' selected'; ?>>X-LOOK</option>
                        <option value="WOOLRICH" 		 <?php if ($misc_unknown_purpose == 'WOOLRICH')		 echo ' selected'; ?>>WOOLRICH</option>
                        <option value="WAHO" 		 	 <?php if ($misc_unknown_purpose == 'WAHO')		     echo ' selected'; ?>>WAHO</option>
                        <option value="WILLOW MAE"<?php if ($misc_unknown_purpose == 'WILLOW MAE') echo ' selected'; ?>>WILLOW MAE</option>
                        <option value="WRANGLER JEANS CO"<?php if ($misc_unknown_purpose == 'WRANGLER JEANS CO') echo ' selected'; ?>>WRANGLER JEANS CO</option>
                        <option value="ZENZERO" 		 <?php if ($misc_unknown_purpose == 'ZENZERO')		 echo ' selected'; ?>>ZENZERO</option>
                   </select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
			<?php 
			
			

if (($usercount != 0) || ($usercountFrames != 0)){
echo  "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
echo "</tr>";
			  echo "
			  <tr>
				<th align=\"center\">Compte</th>
				<th align=\"center\">Order #</th>
				<th align=\"center\">Redo of #</th>
                <th align=\"center\">Date</th>
				<th align=\"center\">Product</th>
                <th align=\"center\">Supplier</th>
                <th align=\"center\">Model</th>
                <th align=\"center\">Color</th>
				<th align=\"center\">Frame A</th>
				<th align=\"center\">Qty</th>
				<th align=\"center\">Order Status</th>
			</tr>";
$labTotal=0;	
//While de la premiere requete (Rx)	
			  	  
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging')";
$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
		
echo "
<tr bgcolor=\"$bgcolor\">
	<td align=\"center\">$listItem[user_id]&nbsp;</td>
	<td align=\"center\">$listItem[order_num]&nbsp;</td>
	<td align=\"center\">$listItem[redo_order_num]&nbsp;</td>
	<td align=\"center\">$listItem[order_date_processed]&nbsp;</td>
	<td align=\"center\">$listItem[order_product_name]&nbsp;</td>
	<td align=\"center\">$DataFrame[supplier]&nbsp;</td>
	<td align=\"center\">$DataFrame[temple_model_num]&nbsp;</td>
	<td align=\"center\">$DataFrame[color]&nbsp;</td>
	<td align=\"center\">$DataFrame[ep_frame_a]&nbsp;</td>
	<td align=\"center\">1</td>
	<td align=\"center\">$listItem[order_status]</td>
</tr>";
			  		
}//END WHILE


echo "</table>";
}else{
echo "<div class=\"formField\">No frame Found</div>";	
}
?></td>
	  </tr>
</table>
  <p>&nbsp;</p>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>